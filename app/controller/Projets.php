<?php

class Projets extends Controller
 {

    public function publier_projet() {
        $model = new Model();

        $data[ 'categories' ] = $model->SelectAllData( '*', 'categories' );

        $this->view( 'publier_projet', $data );
    }

    public function store() {

        if ( $_SERVER[ 'REQUEST_METHOD' ] !== 'POST' ) {
            $this->redirect( 'Projets/publier_projet' );
        }

        $model = new Model();

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

        $user_id = $_SESSION[ 'user_id' ] ?? 1;

        $projectModel = new Projet();

        /* insertion projet */

        $project = $projectModel->createProject( [
            'user_id'=>$user_id,
            'category_id'=>$category,
            'title'=>$title,
            'description'=>$description,
            'technologies'=>$technologies,
            'video'=>$video,
            'status'=>'En Attente'
        ] );

        $project_id = $project[ 'lastInsertId' ];

        /* dossier upload */

        $imageDir = 'uploads/projects/images/';
        $fileDir = 'uploads/projects/files/';

        if ( !file_exists( $imageDir ) ) {
            mkdir( $imageDir, 0777, true );
        }

        if ( !file_exists( $fileDir ) ) {
            mkdir( $fileDir, 0777, true );
        }

        /* ===  ===  ===  ===  ===  == */
        /* UPLOAD IMAGES */
        /* ===  ===  ===  ===  ===  == */

        if ( !empty( $_FILES[ 'images' ][ 'name' ][ 0 ] ) ) {

            foreach ( $_FILES[ 'images' ][ 'name' ] as $key=>$name ) {

                $tmp = $_FILES[ 'images' ][ 'tmp_name' ][ $key ];

                $ext = strtolower( pathinfo( $name, PATHINFO_EXTENSION ) );

                $allowed = [ 'jpg', 'jpeg', 'png', 'gif', 'webp' ];

                if ( !in_array( $ext, $allowed ) ) {
                    continue;
                }

                $newName = uniqid( 'img_' ).'.'.$ext;

                if ( move_uploaded_file( $tmp, $imageDir.$newName ) ) {
                    $projectModel->addImage( $project_id, $newName );
                }
            }
        }

        /* ===  ===  ===  ===  ===  == */
        /* UPLOAD FILES */
        /* ===  ===  ===  ===  ===  == */

        if ( !empty( $_FILES[ 'files' ][ 'name' ][ 0 ] ) ) {

            foreach ( $_FILES[ 'files' ][ 'name' ] as $key=>$name ) {

                $tmp = $_FILES[ 'files' ][ 'tmp_name' ][ $key ];

                $ext = strtolower( pathinfo( $name, PATHINFO_EXTENSION ) );

                $allowed = [
                    'zip', 'rar', 'pdf', 'doc', 'docx', 'ppt', 'pptx',
                    'xls', 'xlsx', 'txt'
                ];

                if ( !in_array( $ext, $allowed ) ) {
                    continue;
                }

                $newName = uniqid( 'file_' ).'.'.$ext;

                if ( move_uploaded_file( $tmp, $fileDir.$newName ) ) {
                    $projectModel->addFile( $project_id, $newName );
                }
            }
        }

        /* success */

        $model->set_flash( 'Projet publié avec succès', 'success' );

        $this->redirect( 'Projets/publier_projet' );

    }

    public function mes_projets() {

        $projectModel = new Projet();

        $user_id = $_SESSION[ 'user_id' ];

        $data[ 'projects' ] = $projectModel->getProjectsByUser( $user_id );

        $this->view( 'projets-list', $data );

    }

    /* PAGE MODIFIER */

    public function modifier( $id ) {

        $model = new Projet();

        $data[ 'project' ] = $model->getProjectById( $id );

        $data[ 'images' ] = $model->getProjectImages( $id );

        $data[ 'files' ] = $model->getProjectFiles( $id );

        $data[ 'categories' ] = $model->SelectAllData( '*', 'categories' );

        $this->view( 'modifier-projet', $data );

    }

    /* UPDATE PROJET */

    public function update( $id ) {

        $model = new Projet();

        /* nettoyage */

        $data = [

            'title'=>$model->e( $_POST[ 'title' ] ),
            'category_id'=>$model->e( $_POST[ 'category_id' ] ),
            'description'=>$_POST[ 'description' ],
            'technologies'=>$model->e( $_POST[ 'technologies' ] ),
            'video'=>$model->e( $_POST[ 'video' ] ),

        ];

        $model->updateProject( $data, $id );

        /* upload nouvelles images */

        if ( !empty( $_FILES[ 'images' ][ 'name' ][ 0 ] ) ) {

            foreach ( $_FILES[ 'images' ][ 'name' ] as $key=>$name ) {

                $tmp = $_FILES[ 'images' ][ 'tmp_name' ][ $key ];

                $ext = strtolower( pathinfo( $name, PATHINFO_EXTENSION ) );

                $allowed = [ 'jpg', 'jpeg', 'png', 'gif', 'webp' ];

                if ( !in_array( $ext, $allowed ) ) {
                    continue;
                }

                $newName = uniqid().'.'.$ext;

                if ( move_uploaded_file( $tmp, 'uploads/projects/images/'.$newName ) ) {
                    $model->addImage( $id, $newName );
                }

            }

        }

        /* upload nouveaux fichiers */

        if ( !empty( $_FILES[ 'files' ][ 'name' ][ 0 ] ) ) {

            foreach ( $_FILES[ 'files' ][ 'name' ] as $key=>$name ) {

                $tmp = $_FILES[ 'files' ][ 'tmp_name' ][ $key ];

                $ext = strtolower( pathinfo( $name, PATHINFO_EXTENSION ) );

                $allowed = [
                    'pdf', 'doc', 'docx', 'ppt', 'pptx',
                    'xls', 'xlsx', 'zip', 'rar', 'txt'
                ];

                if ( !in_array( $ext, $allowed ) ) {
                    continue;
                }

                $newName = uniqid().'.'.$ext;

                if ( move_uploaded_file( $tmp, 'uploads/projects/files/'.$newName ) ) {
                    $model->addFile( $id, $newName );
                }

            }

        }

        $model->set_flash( 'Projet modifié avec succès', 'success' );

        $model->redirect( 'Projets/mes_projets' );

    }

    /* SUPPRIMER IMAGE */

    public function delete_image( $id, $projetId ) {

        $model = new Projet();

        $image = $model->SelectOne( '*', 'project_images', 'id=?', [ $id ] );

        if ( $image ) {

            $path = 'uploads/projects/images/'.$image->image;

            if ( file_exists( $path ) ) {
                unlink( $path );
            }

            $model->deleteImage( $id );

        }

        $model->set_flash( 'Image supprimée', 'success' );

        $model->redirect( '/Projets/modifier/'. $projetId );

    }

    public function delete_file( $id, $projetId ) {
        $model = new Projet();

        $file = $model->SelectOne( '*', 'project_files', 'id=?', [ $id ] );

        if ( $file ) {

            $path = 'uploads/projects/files/'.$file->fichier;

            if ( file_exists( $path ) ) {
                unlink( $path );
            }

            $model->deleteFile( $id );

        }

        $model->set_flash( 'Fichier supprimé', 'success' );

        $model->redirect( '/Projets/modifier/'. $projetId );
    }

    public function detail( $id ) {

        $model = new Projet();
        $projectId = (int) $id;
        $currentUserId = (int) ($_SESSION['user_id'] ?? 0);

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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($currentUserId <= 0) {
                $model->set_flash('Veuillez vous connecter pour interagir avec ce projet.', 'warning');
                $this->redirect('Projets/detail/' . $projectId);
            }

            if ($action === 'toggle_like') {
                $model->toggleProjectLike($projectId, $currentUserId);
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

        $model = new Projet();
        $project = $model->getProjectDetailEnhanced($projectId);
        if (!$project) {
            echo json_encode(['ok' => false, 'message' => 'Projet introuvable.']);
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

