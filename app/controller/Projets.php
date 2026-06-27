<?php

class Projets extends Controller
 {
    private function requireAuthUserId(Model $model): int
    {
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        if ($userId <= 0) {
            $model->set_flash('Veuillez vous connecter pour continuer.', 'warning');
            $this->redirect('Homes/login');
        }

        return $userId;
    }

    private function canManageProject(Projet $model, int $projectId, int $userId): bool
    {
        if (!$model->userOwnsProject($projectId, $userId)) {
            $model->set_flash('Action non autorisee sur ce projet.', 'danger');
            $this->redirect('Projets/mes_projets');
            return false;
        }

        return true;
    }

    // Nom lisible de l'utilisateur courant (pour les notifications).
    private function actorName(): string
    {
        $name = trim((string) (($_SESSION['prenom'] ?? '') . ' ' . ($_SESSION['nom'] ?? '')));
        return $name !== '' ? $name : 'Un visiteur';
    }

    // Cree une notification pour le proprietaire (jamais pour soi-meme).
    private function notifyOwner(int $ownerId, int $actorId, string $type, string $title, string $message, string $link): void
    {
        if ($ownerId <= 0 || $ownerId === $actorId || !class_exists('Notification')) {
            return;
        }

        (new Notification())->create($ownerId, $type, $title, $message, $link);
    }

    // Traite le formulaire de contact public (table `messages`) + notifie les admins.
    private function handlePublicContact(Projet $model, int $projectId, $project): void
    {
        $nom = trim((string) ($_POST['contact_nom'] ?? ''));
        $email = trim((string) ($_POST['contact_email'] ?? ''));
        $message = trim((string) ($_POST['contact_message'] ?? ''));

        // Honeypot anti-spam : champ cache qui doit rester vide.
        if (trim((string) ($_POST['contact_website'] ?? '')) !== '') {
            $model->set_flash('Votre message a bien été envoyé. Merci !', 'success');
            return;
        }

        if ($nom === '' || $email === '' || $message === '') {
            $model->set_flash('Veuillez renseigner votre nom, votre email et votre message.', 'warning');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $model->set_flash('Adresse email invalide.', 'warning');
            return;
        }

        if (!RateLimiter::allow('contact_message', 5, 600)) {
            $model->set_flash('Trop de messages envoyés. Veuillez réessayer dans quelques minutes.', 'warning');
            return;
        }

        $message = mb_substr($message, 0, 2000);

        if ($model->saveContactMessage($projectId, $nom, $email, $message)) {
            if (class_exists('Notification')) {
                $notif = new Notification();
                $notif->createForMany(
                    $notif->getActiveAdminIds(),
                    'contact',
                    'Nouveau message de contact',
                    $nom . ' a écrit à propos de « ' . (string) ($project->title ?? 'un projet') . ' ».',
                    'Admins/messages'
                );
            }
            $model->set_flash('Votre message a bien été envoyé. Merci !', 'success');
        } else {
            $model->set_flash('Impossible d envoyer votre message pour le moment.', 'danger');
        }
    }

    private const PROJECT_IMAGE_EXT = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private const PROJECT_IMAGE_MIME = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private const PROJECT_FILE_EXT = ['zip', 'rar', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt'];
    private const PROJECT_MAX_IMAGE_SIZE = 5242880;    // 5 Mo
    private const PROJECT_MAX_FILE_SIZE = 20971520;    // 20 Mo
    private const BLOCKED_UPLOAD_MIME = ['text/html', 'application/x-httpd-php', 'application/x-php', 'text/x-php', 'application/javascript', 'application/xhtml+xml'];

    // Detecte le type MIME reel d'un fichier televerse (et non l'extension declaree).
    private function detectMime(string $tmpPath): string
    {
        if (!is_file($tmpPath) || !function_exists('finfo_open')) {
            return '';
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo === false) {
            return '';
        }

        $mime = (string) finfo_file($finfo, $tmpPath);
        finfo_close($finfo);

        return strtolower($mime);
    }

    // Televerse les images d'un projet : extension + taille + image reelle + MIME autorise.
    // Retourne le nombre de fichiers rejetes.
    private function saveProjectImages(Projet $model, int $projectId, array $filesInput): int
    {
        if (empty($filesInput['name']) || !is_array($filesInput['name'])) {
            return 0;
        }

        $dir = 'uploads/projects/images/';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $rejected = 0;

        foreach ($filesInput['name'] as $key => $name) {
            $name = trim((string) $name);
            $errorCode = (int) ($filesInput['error'][$key] ?? UPLOAD_ERR_NO_FILE);

            if ($errorCode === UPLOAD_ERR_NO_FILE || $name === '') {
                continue;
            }

            $tmp = (string) ($filesInput['tmp_name'][$key] ?? '');
            $size = (int) ($filesInput['size'][$key] ?? 0);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

            if (
                $errorCode !== UPLOAD_ERR_OK
                || !in_array($ext, self::PROJECT_IMAGE_EXT, true)
                || $size <= 0 || $size > self::PROJECT_MAX_IMAGE_SIZE
                || !is_uploaded_file($tmp)
                || @getimagesize($tmp) === false
                || !in_array($this->detectMime($tmp), self::PROJECT_IMAGE_MIME, true)
            ) {
                $rejected++;
                continue;
            }

            $newName = uniqid('img_') . '.' . $ext;
            if (move_uploaded_file($tmp, $dir . $newName)) {
                $model->addImage($projectId, $newName);
            } else {
                $rejected++;
            }
        }

        return $rejected;
    }

    // Televerse les fichiers d'un projet : extension + taille + blocage des MIME executables/scripts.
    // Retourne le nombre de fichiers rejetes.
    private function saveProjectFiles(Projet $model, int $projectId, array $filesInput): int
    {
        if (empty($filesInput['name']) || !is_array($filesInput['name'])) {
            return 0;
        }

        $dir = 'uploads/projects/files/';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $rejected = 0;

        foreach ($filesInput['name'] as $key => $name) {
            $name = trim((string) $name);
            $errorCode = (int) ($filesInput['error'][$key] ?? UPLOAD_ERR_NO_FILE);

            if ($errorCode === UPLOAD_ERR_NO_FILE || $name === '') {
                continue;
            }

            $tmp = (string) ($filesInput['tmp_name'][$key] ?? '');
            $size = (int) ($filesInput['size'][$key] ?? 0);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

            if (
                $errorCode !== UPLOAD_ERR_OK
                || !in_array($ext, self::PROJECT_FILE_EXT, true)
                || $size <= 0 || $size > self::PROJECT_MAX_FILE_SIZE
                || !is_uploaded_file($tmp)
                || in_array($this->detectMime($tmp), self::BLOCKED_UPLOAD_MIME, true)
            ) {
                $rejected++;
                continue;
            }

            $newName = uniqid('file_') . '.' . $ext;
            if (move_uploaded_file($tmp, $dir . $newName)) {
                $model->addFile($projectId, $newName);
            } else {
                $rejected++;
            }
        }

        return $rejected;
    }

    public function publier_projet() {
        $model = new Model();
        $this->requireAuthUserId($model);

        $data[ 'categories' ] = $model->SelectAllData( '*', 'categories' );

        $this->view( 'publier_projet', $data );
    }

    public function store() {

        if ( $_SERVER[ 'REQUEST_METHOD' ] !== 'POST' ) {
            $this->redirect( 'Projets/publier_projet' );
        }

        $model = new Model();
        $user_id = $this->requireAuthUserId($model);

        /* verification champs */

        $errors = $model->VerifyFields( [
            'title',
            'category_id',
            'description'
        ] );

        if ( !empty( $errors ) ) {
            $model->set_flash( implode( '<br>', $errors ), 'danger' );
            $this->redirect( 'Projets/publier_projet' );
        }

        /* nettoyage */

        $title = $model->e( $_POST[ 'title' ] );
        $category = $model->e( $_POST[ 'category_id' ] );
        $description = $model->e( $_POST[ 'description' ] );
        $technologies = $model->e( $_POST[ 'technologies' ] );
        $video = $model->e( $_POST[ 'video' ] );
        /* utilisateur */

        $projectModel = new Projet();

        /* insertion projet */

        $project = $projectModel->createProject( [
            'user_id'=>$user_id,
            'category_id'=>$category,
            'title'=>$title,
            'description'=>$description,
            'technologies'=>$technologies,
            'video'=>$video,
            'status'=>'en cours'
        ] );

        $project_id = $project[ 'lastInsertId' ];

        /* upload medias : validation extension + taille + type MIME reel */

        $rejected = $this->saveProjectImages( $projectModel, (int) $project_id, $_FILES['images'] ?? [] );
        $rejected += $this->saveProjectFiles( $projectModel, (int) $project_id, $_FILES['files'] ?? [] );

        /* success */

        if ( $rejected > 0 ) {
            $model->set_flash( 'Projet publié. ' . $rejected . ' fichier(s) ignoré(s) : format ou taille non autorisés.', 'warning' );
        } else {
            $model->set_flash( 'Projet publié avec succès', 'success' );
        }

        $this->redirect( 'Projets/publier_projet' );

    }

    public function mes_projets() {

        $projectModel = new Projet();
        $user_id = $this->requireAuthUserId($projectModel);

        $data[ 'projects' ] = $projectModel->getProjectsByUser( $user_id );

        $this->view( 'projets-list', $data );

    }

    /* PAGE MODIFIER */

    public function modifier( $id ) {

        $model = new Projet();
        $userId = $this->requireAuthUserId($model);
        $projectId = (int) $id;

        if ($projectId <= 0 || !$this->canManageProject($model, $projectId, $userId)) {
            return;
        }

        $data[ 'project' ] = $model->getProjectById( $projectId );

        $data[ 'images' ] = $model->getProjectImages( $projectId );

        $data[ 'files' ] = $model->getProjectFiles( $projectId );

        $data[ 'categories' ] = $model->SelectAllData( '*', 'categories' );

        $this->view( 'modifier-projet', $data );

    }

    /* UPDATE PROJET */

    public function update( $id ) {

        $model = new Projet();
        $userId = $this->requireAuthUserId($model);
        $projectId = (int) $id;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('Projets/mes_projets');
        }

        if ($projectId <= 0 || !$this->canManageProject($model, $projectId, $userId)) {
            return;
        }

        /* nettoyage */

        $data = [

            'title'=>$model->e( $_POST[ 'title' ] ),
            'category_id'=>$model->e( $_POST[ 'category_id' ] ),
            'description'=>$_POST[ 'description' ],
            'technologies'=>$model->e( $_POST[ 'technologies' ] ),
            'video'=>$model->e( $_POST[ 'video' ] ),

        ];

        $model->updateProject( $data, $projectId );

        /* upload medias : validation extension + taille + type MIME reel */

        $rejected = $this->saveProjectImages( $model, $projectId, $_FILES['images'] ?? [] );
        $rejected += $this->saveProjectFiles( $model, $projectId, $_FILES['files'] ?? [] );

        if ( $rejected > 0 ) {
            $model->set_flash( 'Projet modifié. ' . $rejected . ' fichier(s) ignoré(s) : format ou taille non autorisés.', 'warning' );
        } else {
            $model->set_flash( 'Projet modifié avec succès', 'success' );
        }

        $model->redirect( 'Projets/mes_projets' );

    }

    /* SUPPRIMER IMAGE */

    public function delete_image( $id, $projetId ) {

        $model = new Projet();
        $userId = $this->requireAuthUserId($model);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('Projets/mes_projets');
        }

        $imageId = (int) $id;
        $projectId = (int) $projetId;

        if (
            $projectId <= 0
            || !$this->canManageProject($model, $projectId, $userId)
            || !$model->imageBelongsToProject($imageId, $projectId)
        ) {
            return;
        }

        $image = $model->SelectOne( '*', 'project_images', 'id=?', [ $imageId ] );

        if ( $image ) {

            $path = 'uploads/projects/images/' . basename((string) $image->image);

            if ( file_exists( $path ) ) {
                unlink( $path );
            }

            $model->deleteImage( $imageId );

        }

        $model->set_flash( 'Image supprimée', 'success' );

        $model->redirect( 'Projets/modifier/'. $projectId );

    }

    public function delete_file( $id, $projetId ) {
        $model = new Projet();
        $userId = $this->requireAuthUserId($model);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('Projets/mes_projets');
        }

        $fileId = (int) $id;
        $projectId = (int) $projetId;

        if (
            $projectId <= 0
            || !$this->canManageProject($model, $projectId, $userId)
            || !$model->fileBelongsToProject($fileId, $projectId)
        ) {
            return;
        }

        $file = $model->SelectOne( '*', 'project_files', 'id=?', [ $fileId ] );

        if ( $file ) {

            $path = 'uploads/projects/files/' . basename((string) $file->fichier);

            if ( file_exists( $path ) ) {
                unlink( $path );
            }

            $model->deleteFile( $fileId );

        }

        $model->set_flash( 'Fichier supprimé', 'success' );

        $model->redirect( 'Projets/modifier/'. $projectId );
    }

    public function detail( $id ) {

        $model = new Projet();
        $projectId = (int) $id;
        $currentUserId = (int) ($_SESSION['user_id'] ?? 0);
        $currentUserRole = (string) ($_SESSION['role'] ?? '');

        if ($projectId <= 0) {
            $model->set_flash('Projet introuvable.', 'danger');
            $this->redirect('Homes/index');
        }

        $project = $model->getProjectDetailEnhanced($projectId);
        if (!$project) {
            $model->set_flash('Projet introuvable.', 'danger');
            $this->redirect('Homes/index');
        }

        $ownerId = (int) ($project->owner_id ?? $project->user_id ?? 0);
        $isAdmin = $currentUserRole === 'admin';
        $isOwner = $currentUserId > 0 && $currentUserId === $ownerId;
        $isPubliclyVisible = $model->isProjectPubliclyVisible($projectId);

        if (!$isPubliclyVisible && !$isAdmin && !$isOwner) {
            $model->set_flash('Ce projet est en attente de validation et n est pas encore visible.', 'warning');
            $this->redirect('Homes/index');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            // Formulaire de contact public : accessible SANS authentification.
            if ($action === 'contact') {
                $this->handlePublicContact($model, $projectId, $project);
                $this->redirect('Projets/detail/' . $projectId);
            }

            if ($currentUserId <= 0) {
                $model->set_flash('Veuillez vous connecter pour interagir avec ce projet.', 'warning');
                $this->redirect('Projets/detail/' . $projectId);
            }

            $isAjax = strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest';

            if ($action === 'toggle_like') {
                $wasLiked = $model->hasUserLikedProject($projectId, $currentUserId);
                $model->toggleProjectLike($projectId, $currentUserId);
                if (!$wasLiked) {
                    $this->notifyOwner($ownerId, $currentUserId, 'new_like', 'Nouveau like',
                        $this->actorName() . ' a aimé votre projet « ' . (string) ($project->title ?? '') . ' ».',
                        'Projets/detail/' . $projectId);
                }
                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(['ok' => true, 'liked' => !$wasLiked, 'count' => $model->getProjectLikesCount($projectId)]);
                    return;
                }
                $this->redirect('Projets/detail/' . $projectId);
            }

            if ($action === 'toggle_follow') {
                $result = $model->toggleProjectFollow($projectId, $currentUserId);
                if ($result === 'followed') {
                    $this->notifyOwner($ownerId, $currentUserId, 'new_follow', 'Nouvel abonné',
                        $this->actorName() . ' suit désormais votre projet « ' . (string) ($project->title ?? '') . ' ».',
                        'Projets/detail/' . $projectId);
                }
                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(['ok' => true, 'following' => $result === 'followed', 'count' => $model->getProjectFollowersCount($projectId)]);
                    return;
                }
                if ($result === 'followed') {
                    $model->set_flash('Vous suivez désormais ce projet. Vous serez informé de ses actualités.', 'success');
                } elseif ($result === 'unfollowed') {
                    $model->set_flash('Vous ne suivez plus ce projet.', 'info');
                }
                $this->redirect('Projets/detail/' . $projectId);
            }

            if ($action === 'submit_review') {
                $rating = (int) ($_POST['rating'] ?? 0);
                $review = trim((string) ($_POST['review'] ?? ''));
                if ($model->saveProjectReview($projectId, $currentUserId, $rating, $review)) {
                    $model->set_flash('Votre avis a bien ete enregistre.', 'success');
                } else {
                    $model->set_flash('Impossible d enregistrer votre avis.', 'danger');
                }
                $this->redirect('Projets/detail/' . $projectId);
            }

            if ($action === 'send_message') {
                $receiverId = (int) ($_POST['receiver_id'] ?? $ownerId);
                $message = trim((string) ($_POST['message'] ?? ''));
                if ($message === '') {
                    $model->set_flash('Veuillez saisir un message.', 'warning');
                } elseif ($receiverId <= 0) {
                    $model->set_flash('Destinataire introuvable.', 'danger');
                } elseif ($model->sendProjectMessage($projectId, $currentUserId, $receiverId, $message)) {
                    $model->set_flash('Message envoye avec succes.', 'success');
                    $this->notifyOwner($receiverId, $currentUserId, 'new_message', 'Nouveau message',
                        $this->actorName() . ' vous a écrit à propos de « ' . (string) ($project->title ?? '') . ' ».',
                        'Homes/messages_recus');
                } else {
                    $model->set_flash('Impossible d envoyer le message.', 'danger');
                }
                $this->redirect('Projets/detail/' . $projectId);
            }
        }

        $data[ 'project' ] = $project;
        $data[ 'images' ] = $model->getProjectImages( $projectId );
        $data[ 'files' ] = $model->getProjectFiles( $projectId );
        $data[ 'reviewSummary' ] = $model->getProjectReviewSummary( $projectId );
        $data[ 'reviews' ] = $model->getProjectReviews( $projectId );
        $data[ 'likesCount' ] = $model->getProjectLikesCount( $projectId );
        $data[ 'userHasLiked' ] = $currentUserId > 0 ? $model->hasUserLikedProject( $projectId, $currentUserId ) : false;
        $data[ 'followersCount' ] = $model->getProjectFollowersCount( $projectId );
        $data[ 'userIsFollowing' ] = $currentUserId > 0 ? $model->userIsFollowingProject( $projectId, $currentUserId ) : false;
        $data[ 'conversation' ] = ($currentUserId > 0 && $ownerId > 0 && $currentUserId !== $ownerId)
            ? $model->getConversationForProject( $projectId, $currentUserId, $ownerId )
            : [];
        $data[ 'currentUserId' ] = $currentUserId;
        $data[ 'ownerId' ] = $ownerId;
        $data[ 'relatedProjects' ] = array_slice(array_values(array_filter(
            $model->getTopLikedProjects(4),
            static fn(array $item): bool => (int) ($item['id'] ?? 0) !== $projectId
        )), 0, 3);

        $this->view( 'details-projet', $data );

    }

    public function ai_assistant($id): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['ok' => false, 'message' => 'Requete invalide.']);
            return;
        }

        $projectId = (int) $id;
        $message = trim((string) ($_POST['message'] ?? ''));
        $history = json_decode((string) ($_POST['history'] ?? '[]'), true);
        $history = is_array($history) ? $history : [];
        if ($projectId <= 0 || $message === '') {
            echo json_encode(['ok' => false, 'message' => 'Question ou projet invalide.']);
            return;
        }

        if (!RateLimiter::allow('ai_project', 15, 60)) {
            http_response_code(429);
            echo json_encode(['ok' => false, 'message' => 'Trop de requetes vers l assistant. Patientez quelques instants avant de reessayer.']);
            return;
        }

        $message = mb_substr($message, 0, 1200);

        $model = new Projet();
        $project = $model->getProjectDetailEnhanced($projectId);
        if (!$project) {
            echo json_encode(['ok' => false, 'message' => 'Projet introuvable.']);
            return;
        }

        $currentUserId = (int) ($_SESSION['user_id'] ?? 0);
        $currentUserRole = (string) ($_SESSION['role'] ?? '');
        $ownerId = (int) ($project->owner_id ?? $project->user_id ?? 0);
        $isAdmin = $currentUserRole === 'admin';
        $isOwner = $currentUserId > 0 && $currentUserId === $ownerId;

        if (!$model->isProjectPubliclyVisible($projectId) && !$isAdmin && !$isOwner) {
            echo json_encode(['ok' => false, 'message' => 'Ce projet n est pas encore accessible.']);
            return;
        }

        $context = [
            'id' => $project->id ?? 0,
            'title' => $project->title ?? '',
            'category' => $project->categorie ?? '',
            'description' => $project->description ?? '',
            'technologies' => $project->technologies ?? '',
            'owner' => trim((string) (($project->prenom ?? '') . ' ' . ($project->nom ?? ''))),
            'university' => $project->universite ?? '',
            'field' => $project->filiere ?? '',
        ];

        $assistant = new HuggingFaceProjectAssistant();
        echo json_encode($assistant->answerForProject($message, $context, $history));
    }

    public function temps_relatif ( $datetime ) {

        $time = strtotime( $datetime );
        $diff = time() - $time;

        $units = [

            31536000 => 'an',
            2592000 => 'mois',
            86400 => 'jour',
            3600 => 'heure',
            60 => 'minute',
            1 => 'seconde'

        ];

        foreach ( $units as $sec => $str ) {

            $d = floor( $diff / $sec );

            if ( $d >= 1 ) {

                if ( $str == 'mois' ) {
                    return "Il y a $d $str";
                }

                $str .= $d > 1 ? 's' : '';

                return "Il y a $d $str";

            }

        }

    }

    public function youtube_embed( $url ) {

        $video_id = '';

        /* cas 1 : youtube.com/watch */

        if ( preg_match( '/youtube\.com\/watch\?v=([^\&]+)/', $url, $match ) ) {
            $video_id = $match[ 1 ];
        }

        /* cas 2 : youtu.be */

        if ( preg_match( '/youtu\.be\/([^\?]+)/', $url, $match ) ) {
            $video_id = $match[ 1 ];
        }

        /* cas 3 : youtube embed déjà */

        if ( preg_match( '/youtube\.com\/embed\/([^\?]+)/', $url, $match ) ) {
            $video_id = $match[ 1 ];
        }

        if ( $video_id != '' ) {
            return 'https://www.youtube.com/embed/'.$video_id;
        }

        return '';

    }

}

