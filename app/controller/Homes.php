<?php

class Homes extends Controller
{
    private const DER_ALLOWED_UPLOAD_EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png'];
    private const DER_MAX_FILE_SIZE = 5242880;

    private function guardDer(): void
    {
        $role = strtolower((string)($_SESSION['role'] ?? ''));
        if ($role !== 'der') {
            $_SESSION['notification'] = [
                'type' => 'warning',
                'message' => 'Acces reserve au responsable DER.',
            ];
            $this->redirect('Homes/dashboard');
        }
    }

    private function paginationParams(int $defaultPerPage = 10): array
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = (int) ($_GET['per_page'] ?? $defaultPerPage);
        $allowedPerPage = [5, 10, 20, 50];
        $perPage = in_array($perPage, $allowedPerPage, true) ? $perPage : $defaultPerPage;

        return [
            'page' => $page,
            'perPage' => $perPage,
        ];
    }

    private function derFilters(): array
    {
        $postModel = new DepartmentPost();
        $allowedTypes = array_merge(['all'], $postModel->getAllowedTypes());
        $type = trim((string) ($_GET['type'] ?? 'all'));
        $sortBy = trim((string) ($_GET['sort_by'] ?? 'date'));
        $sortDir = trim((string) ($_GET['sort_dir'] ?? 'desc'));
        $visibility = trim((string) ($_GET['visibility'] ?? 'active'));

        return [
            'visibility' => in_array($visibility, ['active', 'archived', 'all'], true) ? $visibility : 'active',
            'type' => in_array($type, $allowedTypes, true) ? $type : 'all',
            'search' => trim((string) ($_GET['search'] ?? '')),
            'dateFrom' => trim((string) ($_GET['date_from'] ?? '')),
            'dateTo' => trim((string) ($_GET['date_to'] ?? '')),
            'sortBy' => in_array($sortBy, ['date', 'title', 'type', 'author', 'created'], true) ? $sortBy : 'date',
            'sortDir' => in_array(strtolower($sortDir), ['asc', 'desc'], true) ? strtolower($sortDir) : 'desc',
        ];
    }

    private function derQueryStringWithoutPage(array $extra = []): string
    {
        $params = array_merge($_GET, $extra);
        unset($params['page']);

        return http_build_query(array_filter($params, static function ($value) {
            return $value !== null && $value !== '';
        }));
    }

    private function redirectDerSpace(): void
    {
        $route = 'Homes/der_espace';
        $query = trim((string) ($_POST['return_query'] ?? ''));

        if ($query !== '') {
            $route .= '?' . ltrim($query, '?');
        }

        $this->redirect($route);
    }

    public function index(): void
    {
        $data = $this->baseViewData();
        $data['pageTitle'] = 'Accueil';
        $data = array_merge($data, $this->buildHomeProjectListingData());
        $data['departmentAnnouncements'] = $this->getDepartmentAnnouncements();
        $data['departmentInformations'] = $this->getDepartmentPostsByType('information', 3);
        $data['departmentResults'] = $this->getDepartmentPostsByType('resultat', 3);
        $data['departmentOpportunities'] = $this->getDepartmentPostsByType('opportunite', 3);

        if ($this->isAjaxRequest()) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'html' => $this->renderViewToString('Partials/home-project-results', $data),
                'count' => (int) ($data['projectCount'] ?? 0),
                'currentPage' => (int) ($data['currentPage'] ?? 1),
                'totalPages' => (int) ($data['totalPages'] ?? 1),
            ]);
            return;
        }

        $this->view('home', $data);
    }

    public function ai_assistant(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['ok' => false, 'message' => 'Requete invalide.']);
            return;
        }

        $message = trim((string) ($_POST['message'] ?? ''));
        if ($message === '') {
            echo json_encode(['ok' => false, 'message' => 'Veuillez saisir votre besoin.']);
            return;
        }

        $history = json_decode((string) ($_POST['history'] ?? '[]'), true);
        $history = is_array($history) ? $history : [];

        $projectModel = new Projet();
        $assistant = new HuggingFaceProjectAssistant();
        $projects = $projectModel->getHomepageProjects(null, null, 30);
        echo json_encode($assistant->answerForCatalog($message, $projects, $history));
    }

    public function dashboard(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['notification'] = [
                'type' => 'info',
                'message' => 'Veuillez vous connecter pour accéder à votre espace.',
            ];
            $this->redirect('Homes/login');
        }

        $role = strtolower((string)($_SESSION['role'] ?? 'etudiant'));

        if ($role === 'admin') {
            $this->redirect('Admins/dashboard');
        }

        if ($role === 'der') {
            $this->redirect('Homes/der_dashboard');
        }

        $this->redirect('Homes/student_dashboard');
    }

    public function student_dashboard(): void
    {
        $role = strtolower((string)($_SESSION['role'] ?? ''));
        if ($role !== 'etudiant') {
            $this->redirect('Homes/dashboard');
        }

        $projectModel = new Projet();
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $overview = $projectModel->getStudentDashboardOverview($userId, 5);
        $studentUnreadMessages = $projectModel->getStudentUnreadMessagesCount($userId);
        $studentName = trim((string) (($_SESSION['prenom'] ?? '') . ' ' . ($_SESSION['nom'] ?? '')));
        $studentName = $studentName !== '' ? $studentName : 'Etudiant';

        $data = $this->baseViewData();
        $data['pageTitle'] = 'Dashboard etudiant';
        $data['studentName'] = $studentName;
        $data['projects'] = !empty($overview['recentProjects']) ? $overview['recentProjects'] : $this->getMockProjects();
        $data['studentStats'] = $overview['stats'] ?? [
            'mesProjets' => 0,
            'enAttente' => 0,
            'valides' => 0,
            'messages' => 0,
            'likes' => 0,
            'reviews' => 0,
        ];
        $data['studentLatestProject'] = $overview['latestProject'] ?? null;
        $data['studentCompletionRate'] = (int) ($overview['completionRate'] ?? 0);
        $data['studentVisitorReviews'] = $projectModel->getStudentVisitorReviews($userId, 6);
        $data['studentUnreadThreadsPreview'] = $projectModel->getStudentMessageThreads($userId, 3, null, '', 'unread');
        $data['studentUnreadMessages'] = $studentUnreadMessages;
        $_SESSION['student_unread_messages'] = $studentUnreadMessages;
        $data['studentActions'] = [
            [
                'title' => 'Publier un nouveau projet',
                'text' => 'Ajoutez une nouvelle realisation et mettez votre savoir-faire en avant.',
                'icon' => 'bx bx-folder-plus',
                'href' => ROOT . '/Projets/publier_projet',
                'variant' => 'primary',
            ],
            [
                'title' => 'Voir mes projets',
                'text' => 'Suivez l evolution de vos publications et mettez-les a jour facilement.',
                'icon' => 'bx bx-grid-alt',
                'href' => ROOT . '/Projets/mes_projets',
                'variant' => 'soft',
            ],
            [
                'title' => 'Explorer la vitrine',
                'text' => 'Comparez votre travail avec les projets visibles sur la plateforme.',
                'icon' => 'bx bx-show-alt',
                'href' => ROOT . '/Homes/index',
                'variant' => 'soft',
            ],
        ];

        $this->view('student_dashboard', $data);
    }
    public function der_dashboard(): void
    {
        $role = strtolower((string)($_SESSION['role'] ?? ''));
        if ($role !== 'der') {
            $_SESSION['notification'] = [
                'type' => 'warning',
                'message' => 'Accès réservé au responsable DER.',
            ];
            $this->redirect('Homes/dashboard');
        }

        $postModel = new DepartmentPost();

        $data = $this->baseViewData();
        $data['pageTitle'] = 'Dashboard DER';
        $data['derStats'] = $postModel->getDashboardStats();
        $latestPage = $postModel->getPostsPaginated('active', 'all', '', '', '', 'date', 'desc', 1, 5);
        $data['latestPublications'] = $latestPage['items'];

        $this->view('der_dashboard', $data);
    }

    public function admin(): void
    {
        $role = strtolower((string)($_SESSION['role'] ?? 'etudiant'));

        if ($role !== 'admin') {
            $this->redirect('Homes/dashboard');
        }

        $data = $this->baseViewData();
        $data['role'] = $role;
        $data['pageTitle'] = $this->getDashboardTitleByRole($role);
        $data['projects'] = $this->getMockProjects();
        $data['dashboardStats'] = [
            'users' => 128,
            'projects' => count($data['projects']),
            'registrations' => 47,
            'alerts' => count($data['notifications']),
        ];

        $this->view('admin_dashboard', $data);
    }



    public function messages_recus(): void
    {
        $role = strtolower((string)($_SESSION['role'] ?? ''));
        if ($role !== 'etudiant') {
            $this->redirect('Homes/dashboard');
        }

        $projectModel = new Projet();
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $selectedProjectId = isset($_GET['project_id']) && $_GET['project_id'] !== '' ? (int) $_GET['project_id'] : null;
        $threadSearch = trim((string) ($_GET['search'] ?? ''));
        $threadStatus = trim((string) ($_GET['status'] ?? 'all'));

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && (string) ($_POST['action'] ?? '') === 'send_thread_reply') {
            $projectId = (int) ($_POST['project_id'] ?? 0);
            $receiverId = (int) ($_POST['receiver_id'] ?? 0);
            $message = trim((string) ($_POST['message'] ?? ''));

            if ($projectId <= 0 || $receiverId <= 0 || $message === '') {
                $_SESSION['notification'] = [
                    'type' => 'warning',
                    'message' => 'Veuillez saisir une reponse valide.',
                ];
            } elseif ($projectModel->sendProjectMessage($projectId, $userId, $receiverId, $message)) {
                $_SESSION['notification'] = [
                    'type' => 'success',
                    'message' => 'Votre reponse a bien ete envoyee.',
                ];
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Impossible d envoyer la reponse pour le moment.',
                ];
            }

            $this->redirect('Homes/messages_recus');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && (string) ($_POST['action'] ?? '') === 'mark_thread_read') {
            $projectId = (int) ($_POST['project_id'] ?? 0);
            $visitorId = (int) ($_POST['visitor_id'] ?? 0);
            $projectModel->markThreadMessagesAsRead($userId, $projectId, $visitorId);
            $this->redirect('Homes/messages_recus');
        }

        $data = $this->baseViewData();
        $data['pageTitle'] = 'Messages visiteurs';
        $data['studentMessageThreads'] = $projectModel->getStudentMessageThreads($userId, 18, $selectedProjectId, $threadSearch, $threadStatus);
        $data['studentVisitorReviews'] = $projectModel->getStudentVisitorReviews($userId, 12);
        $data['studentProjectsForFilter'] = $projectModel->getStudentDashboardOverview($userId, 50)['recentProjects'] ?? [];
        $data['messageFilterProjectId'] = $selectedProjectId;
        $data['messageFilterSearch'] = $threadSearch;
        $data['messageFilterStatus'] = in_array($threadStatus, ['all', 'read', 'unread'], true) ? $threadStatus : 'all';
        $data['studentUnreadMessages'] = $projectModel->getStudentUnreadMessagesCount($userId);
        $_SESSION['student_unread_messages'] = $data['studentUnreadMessages'];

        $this->view('student_messages', $data);
    }
    public function annonces_departement(): void
    {
        $this->departement();
    }

    public function der_espace(): void
    {
        $role = strtolower((string)($_SESSION['role'] ?? ''));
        if ($role !== 'der') {
            $_SESSION['notification'] = [
                'type' => 'warning',
                'message' => 'Accès réservé au responsable DER.',
            ];
            $this->redirect('Homes/dashboard');
        }

        $postModel = new DepartmentPost();
        $filters = $this->derFilters();
        $pagination = $this->paginationParams();

        if (isset($_POST['save_der_post'])) {
            $title = trim((string)($_POST['titre'] ?? ''));
            $content = trim((string)($_POST['contenu'] ?? ''));
            $type = trim((string)($_POST['type'] ?? 'information'));
            $publicationDate = trim((string)($_POST['date_publication'] ?? date('Y-m-d')));
            $userId = (int)($_SESSION['user_id'] ?? 0);

            if ($title === '' || $content === '' || $userId <= 0) {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Veuillez remplir le titre et le contenu.',
                ];
                $this->redirect('Homes/der_espace');
            }

            if (!$this->isValidPublicationDate($publicationDate)) {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'La date de publication est invalide.',
                ];
                $this->redirect('Homes/der_espace');
            }

            [$uploadedFiles, $uploadErrors] = $this->handleDepartmentPostUploads($_FILES['fichiers'] ?? []);

            if (!empty($uploadErrors)) {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => implode(' ', $uploadErrors),
                ];
                $this->redirect('Homes/der_espace');
            }

            if ($postModel->createPost($userId, $title, $content, $type, $publicationDate, $uploadedFiles)) {
                $_SESSION['notification'] = [
                    'type' => 'success',
                    'message' => 'Publication DER enregistrée avec succès' . (!empty($uploadedFiles) ? ' avec ses fichiers.' : '.'),
                ];
            } else {
                $this->cleanupUploadedDepartmentFiles($uploadedFiles);
                $_SESSION['notification'] = [
                    'type' => 'warning',
                    'message' => 'Table department_posts ou department_post_files introuvable. Exécutez le script SQL de migration.',
                ];
            }

            $this->redirect('Homes/der_espace');
        }

        if (isset($_POST['update_der_post'])) {
            $postId = (int) ($_POST['post_id'] ?? 0);
            $title = trim((string)($_POST['titre'] ?? ''));
            $content = trim((string)($_POST['contenu'] ?? ''));
            $type = trim((string)($_POST['type'] ?? 'information'));
            $publicationDate = trim((string)($_POST['date_publication'] ?? date('Y-m-d')));

            if ($postId <= 0 || $title === '' || $content === '' || !$this->isValidPublicationDate($publicationDate)) {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Impossible de modifier cette publication. Verifiez les champs.',
                ];
            } elseif ($postModel->updatePost($postId, $title, $content, $type, $publicationDate)) {
                $_SESSION['notification'] = [
                    'type' => 'success',
                    'message' => 'Publication DER mise a jour avec succes.',
                ];
            } else {
                $_SESSION['notification'] = [
                    'type' => 'warning',
                    'message' => 'Aucune modification enregistree sur cette publication.',
                ];
            }

            $this->redirectDerSpace();
        }

        if (isset($_POST['delete_der_post'])) {
            $postId = (int) ($_POST['post_id'] ?? 0);
            $deleted = $postModel->deletePost($postId);

            if ($deleted) {
                $_SESSION['notification'] = [
                    'type' => 'success',
                    'message' => 'Publication DER archivee avec succes.',
                ];
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Impossible d archiver cette publication.',
                ];
            }

            $this->redirectDerSpace();
        }

        if (isset($_POST['restore_der_post'])) {
            $postId = (int) ($_POST['post_id'] ?? 0);
            $restored = $postModel->restorePost($postId);

            $_SESSION['notification'] = [
                'type' => $restored ? 'success' : 'danger',
                'message' => $restored
                    ? 'Publication DER restauree avec succes.'
                    : 'Impossible de restaurer cette publication.',
            ];

            $this->redirectDerSpace();
        }

        if (isset($_POST['delete_der_file'])) {
            $fileId = (int) ($_POST['file_id'] ?? 0);
            $file = $postModel->getFileById($fileId);
            $deleted = $postModel->deleteFile($fileId);

            if ($deleted && $file) {
                $fullPath = dirname(__DIR__, 2) . '/public/' . ltrim((string) ($file->file_path ?? ''), '/');
                if (is_file($fullPath)) {
                    @unlink($fullPath);
                }

                $_SESSION['notification'] = [
                    'type' => 'success',
                    'message' => 'Fichier DER supprime avec succes.',
                ];
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Impossible de supprimer ce fichier.',
                ];
            }

            $this->redirectDerSpace();
        }

        if (isset($_POST['attach_der_files'])) {
            $postId = (int) ($_POST['post_id'] ?? 0);
            [$uploadedFiles, $uploadErrors] = $this->handleDepartmentPostUploads($_FILES['fichiers'] ?? []);

            if ($postId <= 0) {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Publication DER introuvable.',
                ];
            } elseif (!empty($uploadErrors)) {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => implode(' ', $uploadErrors),
                ];
            } elseif (!empty($uploadedFiles)) {
                $postModel->attachFiles($postId, $uploadedFiles);
                $_SESSION['notification'] = [
                    'type' => 'success',
                    'message' => 'Nouveaux fichiers ajoutes a la publication.',
                ];
            } else {
                $_SESSION['notification'] = [
                    'type' => 'warning',
                    'message' => 'Aucun fichier a ajouter.',
                ];
            }

            $this->redirectDerSpace();
        }

        $postsPage = $postModel->getPostsPaginated(
            $filters['visibility'],
            $filters['type'],
            $filters['search'],
            $filters['dateFrom'],
            $filters['dateTo'],
            $filters['sortBy'],
            $filters['sortDir'],
            $pagination['page'],
            $pagination['perPage']
        );

        $data = $this->baseViewData();
        $data['pageTitle'] = 'Espace DER - Gestion des publications';
        $data['derStats'] = $postModel->getDashboardStats();
        $data['derPosts'] = $postsPage['items'];
        $data['derAllowedTypes'] = $postModel->getAllowedTypes();
        $data['derVisibilityFilter'] = $filters['visibility'];
        $data['derTypeFilter'] = $filters['type'];
        $data['derSearch'] = $filters['search'];
        $data['derDateFrom'] = $filters['dateFrom'];
        $data['derDateTo'] = $filters['dateTo'];
        $data['derSortBy'] = $filters['sortBy'];
        $data['derSortDir'] = $filters['sortDir'];
        $data['currentPage'] = $postsPage['page'];
        $data['perPage'] = $postsPage['perPage'];
        $data['totalPages'] = $postsPage['totalPages'];
        $data['totalItems'] = $postsPage['total'];
        $data['paginationQuery'] = $this->derQueryStringWithoutPage(['per_page' => $pagination['perPage']]);
        $data['activeEditPostId'] = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;

        if ($this->isAjaxRequest()) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'ok' => true,
                'html' => $this->renderViewToString('Partials/der-posts-list', $data),
                'totalItems' => (int) ($data['totalItems'] ?? 0),
                'currentPage' => (int) ($data['currentPage'] ?? 1),
                'totalPages' => (int) ($data['totalPages'] ?? 1),
            ]);
            return;
        }

        $this->view('der_annonces', $data);
    }

    public function der_publication_detail($id): void
    {
        $this->guardDer();

        $postId = (int) $id;
        $postModel = new DepartmentPost();
        $post = $postModel->getPostById($postId);
        $returnQuery = trim((string) ($_GET['return'] ?? ''));

        if (!$post) {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Publication DER introuvable.',
            ];
            $this->redirect('Homes/der_espace');
        }

        $data = $this->baseViewData();
        $data['pageTitle'] = 'Detail publication DER';
        $data['post'] = $post;
        if ($returnQuery !== '' && str_contains($returnQuery, 'Homes/')) {
            $data['returnUrl'] = ROOT . '/' . ltrim($returnQuery, '/');
        } else {
            $data['returnUrl'] = ROOT . '/Homes/der_espace' . ($returnQuery !== '' ? '?' . ltrim($returnQuery, '?') : '');
        }

        $this->view('der_publication_detail', $data);
    }

    public function der_corbeille(): void
    {
        $this->guardDer();

        $postModel = new DepartmentPost();
        $filters = $this->derFilters();
        $pagination = $this->paginationParams();
        $filters['visibility'] = 'archived';

        if (isset($_POST['restore_der_post'])) {
            $postId = (int) ($_POST['post_id'] ?? 0);
            $restored = $postModel->restorePost($postId);

            $_SESSION['notification'] = [
                'type' => $restored ? 'success' : 'danger',
                'message' => $restored
                    ? 'Publication DER restauree avec succes.'
                    : 'Impossible de restaurer cette publication.',
            ];

            $this->redirect('Homes/der_corbeille');
        }

        if (isset($_POST['purge_der_post'])) {
            $postId = (int) ($_POST['post_id'] ?? 0);
            $post = $postModel->getPostById($postId);
            $deleted = $postModel->permanentlyDeletePost($postId);

            if ($deleted && $post) {
                foreach ($post->files ?? [] as $file) {
                    $fullPath = dirname(__DIR__, 2) . '/public/' . ltrim((string) ($file->file_path ?? ''), '/');
                    if (is_file($fullPath)) {
                        @unlink($fullPath);
                    }
                }
            }

            $_SESSION['notification'] = [
                'type' => $deleted ? 'success' : 'danger',
                'message' => $deleted
                    ? 'Publication DER supprimee definitivement.'
                    : 'Impossible de supprimer definitivement cette publication.',
            ];

            $this->redirect('Homes/der_corbeille');
        }

        $postsPage = $postModel->getPostsPaginated(
            'archived',
            $filters['type'],
            $filters['search'],
            $filters['dateFrom'],
            $filters['dateTo'],
            $filters['sortBy'],
            $filters['sortDir'],
            $pagination['page'],
            $pagination['perPage']
        );

        $data = $this->baseViewData();
        $data['pageTitle'] = 'Corbeille DER';
        $data['derStats'] = $postModel->getDashboardStats();
        $data['derPosts'] = $postsPage['items'];
        $data['derAllowedTypes'] = $postModel->getAllowedTypes();
        $data['derVisibilityFilter'] = 'archived';
        $data['derTypeFilter'] = $filters['type'];
        $data['derSearch'] = $filters['search'];
        $data['derDateFrom'] = $filters['dateFrom'];
        $data['derDateTo'] = $filters['dateTo'];
        $data['derSortBy'] = $filters['sortBy'];
        $data['derSortDir'] = $filters['sortDir'];
        $data['currentPage'] = $postsPage['page'];
        $data['perPage'] = $postsPage['perPage'];
        $data['totalPages'] = $postsPage['totalPages'];
        $data['totalItems'] = $postsPage['total'];
        $data['paginationQuery'] = $this->derQueryStringWithoutPage([
            'per_page' => $pagination['perPage'],
            'visibility' => 'archived',
        ]);
        $data['activeEditPostId'] = 0;

        $this->view('der_trash', $data);
    }

    public function department_publication_detail($id): void
    {
        $postId = (int) $id;
        $postModel = new DepartmentPost();
        $post = $postModel->getPostById($postId);
        $returnQuery = trim((string) ($_GET['return'] ?? ''));

        if (!$post) {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Publication du departement introuvable.',
            ];
            $this->redirect('Homes/departement');
        }

        $data = $this->baseViewData();
        $data['pageTitle'] = 'Publication du departement';
        $data['post'] = $post;
        $data['returnUrl'] = ROOT . '/Homes/departement' . ($returnQuery !== '' ? '?' . ltrim($returnQuery, '?') : '');
        $this->view('department_publication_detail', $data);
    }

    public function login(): void
    {
        $data = $this->baseViewData();
        $data['pageTitle'] = 'Connexion';

        $this->view('login', $data);
    }

    public function register(): void
    {
        $data = $this->baseViewData();
        $data['pageTitle'] = 'Inscription';

        $universiteModel = new Universite();
        $universiteModel->seedDefaultUniversitesIfEmpty();
        $data['universites'] = $universiteModel->getAllUniversites();

        $this->view('register', $data);
    }

    public function projects(): void
    {
        $data = $this->baseViewData();
        $data['pageTitle'] = 'Liste des projets';
        $data = array_merge($data, $this->buildHomeProjectListingData());

        $this->view('projects', $data);
    }

    private function buildHomeProjectListingData(): array
    {
        $projectModel = new Projet();
        $search = trim((string) ($_GET['search'] ?? ''));
        $aiQuery = trim((string) ($_GET['ai_query'] ?? ''));
        $categoryId = isset($_GET['category']) && $_GET['category'] !== '' ? (int) $_GET['category'] : null;
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $perPage = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 5;
        $listing = $projectModel->getHomepageProjectsPaginated($search, $categoryId, $page, $perPage);

        return [
            'projects' => $listing['projects'],
            'projectCategories' => $projectModel->getAvailableCategories(),
            'projectSearch' => $search,
            'selectedCategoryId' => $categoryId,
            'projectCount' => (int) ($listing['total'] ?? 0),
            'featuredProject' => $listing['projects'][0] ?? null,
            'currentPage' => (int) ($listing['page'] ?? 1),
            'perPage' => (int) ($listing['perPage'] ?? 5),
            'totalPages' => (int) ($listing['totalPages'] ?? 1),
            'topLikedProjects' => $projectModel->getTopLikedProjects(3),
            'presentationStats' => $projectModel->getPresentationStats(),
            'aiQuery' => $aiQuery,
            'aiRecommendedProjects' => $projectModel->getGuidedProjectRecommendations($aiQuery, 3),
            'presentationBenefits' => [
                [
                    'icon' => 'bx bx-rocket',
                    'title' => 'Valorisation immediate',
                    'text' => 'Les projets des etudiants sont presentes comme de vraies solutions numeriques, pas comme de simples travaux stockes.',
                ],
                [
                    'icon' => 'bx bx-network-chart',
                    'title' => 'Mise en relation rapide',
                    'text' => 'Le visiteur peut identifier un projet, comprendre sa valeur et contacter son proprietaire depuis la plateforme.',
                ],
                [
                    'icon' => 'bx bx-brain',
                    'title' => 'Guidage intelligent',
                    'text' => 'Un assistant IA aide a orienter les visiteurs vers les projets les plus pertinents selon leur besoin.',
                ],
            ],
            'presentationFlow' => [
                [
                    'step' => '01',
                    'title' => 'Explorer',
                    'text' => 'Le visiteur parcourt les projets par categorie, recherche et top des projets les plus engages.',
                ],
                [
                    'step' => '02',
                    'title' => 'Comprendre',
                    'text' => 'Il ouvre une fiche detaillee avec medias, proprietaire, note, likes, avis et explications IA.',
                ],
                [
                    'step' => '03',
                    'title' => 'Interagir',
                    'text' => 'Il echange avec le porteur du projet et identifie rapidement la solution la plus adaptee.',
                ],
            ],
        ];
    }

    private function isAjaxRequest(): bool
    {
        return strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest';
    }

    private function renderViewToString(string $view, array $data = []): string
    {
        $filename = 'app/views/' . $view . '.view.php';

        if (!file_exists($filename)) {
            return '';
        }

        extract($data);
        ob_start();
        require $filename;
        return (string) ob_get_clean();
    }

    private function buildLocalCatalogAssistantResponse(Projet $projectModel, string $message): array
    {
        $recommendations = $projectModel->getGuidedProjectRecommendations($message, 3);
        $topLikedProjects = $projectModel->getTopLikedProjects(3);

        if (!empty($recommendations)) {
            $lines = [
                "Voici les projets qui semblent les plus pertinents pour votre besoin :",
                "",
            ];

            foreach ($recommendations as $index => $project) {
                $technologies = !empty($project['technologies'])
                    ? implode(', ', array_slice($project['technologies'], 0, 4))
                    : 'technologies non precisees';

                $lines[] = ($index + 1) . ". " . ($project['title'] ?? 'Projet');
                $lines[] = "   Categorie : " . ($project['category'] ?? 'Sans categorie');
                $lines[] = "   Proprietaire : " . ($project['author'] ?? 'Etudiant GI');
                $lines[] = "   Pourquoi : " . ($project['ai_reason'] ?? 'Projet pertinent pour votre recherche.');
                $lines[] = "   Technologies : " . $technologies . ".";
                $lines[] = "";
            }

            $lines[] = "Pour affiner encore plus, dites-moi maintenant :";
            $lines[] = "- votre niveau : debutant, intermediaire ou avance";
            $lines[] = "- le domaine vise : education, sante, gestion, mobile, IA, web";
            $lines[] = "- si vous cherchez surtout un projet tres innovant, tres utile ou tres realisable";

            return [
                'ok' => true,
                'mode' => 'local',
                'message' => implode("\n", $lines),
            ];
        }

        $categories = array_map(static function ($category): string {
            return (string) ($category->nom ?? '');
        }, array_slice($projectModel->getAvailableCategories(), 0, 6));
        $categories = array_values(array_filter($categories));

        $lines = [
            "Je n'ai pas encore trouve de projet parfaitement correspondant.",
            "Precisez votre besoin avec 2 ou 3 elements comme le niveau, la technologie voulue, le domaine ou le type de solution.",
        ];

        if (!empty($categories)) {
            $lines[] = "Vous pouvez commencer par une categorie comme : " . implode(', ', $categories) . ".";
        }

        if (!empty($topLikedProjects)) {
            $highlights = array_map(static function (array $project): string {
                return (string) ($project['title'] ?? 'Projet');
            }, array_slice($topLikedProjects, 0, 3));
            $lines[] = "Vous pouvez aussi regarder les projets les plus apprecies : " . implode(', ', $highlights) . ".";
        }

        return [
            'ok' => true,
            'mode' => 'local',
            'message' => implode("\n", $lines),
        ];
    }

    public function project($id = null): void
    {
        $data = $this->baseViewData();
        $projects = $this->getMockProjects();
        $selected = $projects[0];

        foreach ($projects as $project) {
            if ((int) $project['id'] === (int) $id) {
                $selected = $project;
                break;
            }
        }

        $data['pageTitle'] = 'Détail du projet';
        $data['project'] = $selected;

        $this->view('project_detail', $data);
    }

    public function profile(): void
    {
        $data = $this->baseViewData();
        $data['pageTitle'] = 'Profil utilisateur';
        $data['user'] = [
            'id' => 1,
            'name' => 'Utilisateur Démo',
            'email' => 'user@plateforme.local',
            'role' => 'Étudiant',
            'phone' => '+223 00 00 00 00',
            'bio' => 'Compte de démonstration en attente du backend.',
        ];

        $this->view('profile', $data);
    }

    public function departement(): void
    {
        $data = $this->baseViewData();
        $data['pageTitle'] = 'Espace Département';
        $postModel = new DepartmentPost();
        $allowedTypes = $postModel->getAllowedTypes();
        $typeFilter = trim((string) ($_GET['type'] ?? 'all'));
        $typeFilter = $typeFilter === 'all' || in_array($typeFilter, $allowedTypes, true) ? $typeFilter : 'all';
        $search = trim((string) ($_GET['search'] ?? ''));
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $perPage = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 6;
        $perPage = in_array($perPage, [6, 12, 24], true) ? $perPage : 6;
        $announcements = $this->getDepartmentPostsByType('annonce');
        $informations = $this->getDepartmentPostsByType('information');
        $events = $this->getDepartmentPostsByType('evenement');
        $results = $this->getDepartmentPostsByType('resultat');
        $opportunities = $this->getDepartmentPostsByType('opportunite');
        $postsPage = $postModel->getPostsPaginated('active', $typeFilter, $search, '', '', 'date', 'desc', $page, $perPage);

        $latestDepartmentPosts = array_merge(
            array_slice($informations, 0, 10),
            array_slice($announcements, 0, 10),
            array_slice($events, 0, 10),
            array_slice($results, 0, 10),
            array_slice($opportunities, 0, 10)
        );

        usort($latestDepartmentPosts, static function (array $left, array $right): int {
            return strcmp((string)($right['date'] ?? ''), (string)($left['date'] ?? ''));
        });

        $data['department'] = [
            'name' => 'Département Génie Informatique',
            'subtitle' => 'Publications académiques, informations officielles, résultats et opportunités du département.',
        ];

        $data['departmentAnnouncements'] = $announcements;
        $data['departmentInformations'] = $informations;
        $data['departmentEvents'] = $events;
        $data['departmentResults'] = $results;
        $data['departmentOpportunities'] = $opportunities;
        $data['latestDepartmentPosts'] = array_slice($latestDepartmentPosts, 0, 6);
        $data['departmentPosts'] = $postsPage['items'];
        $data['departmentAllowedTypes'] = $allowedTypes;
        $data['departmentTypeFilter'] = $typeFilter;
        $data['departmentSearch'] = $search;
        $data['currentPage'] = $postsPage['page'];
        $data['perPage'] = $postsPage['perPage'];
        $data['totalPages'] = $postsPage['totalPages'];
        $data['totalItems'] = $postsPage['total'];
        $query = [
            'type' => $typeFilter,
            'search' => $search,
            'per_page' => $perPage,
        ];
        $data['paginationQuery'] = http_build_query(array_filter($query, static fn($value) => $value !== '' && $value !== null));
        $data['departmentStats'] = [
            'annonces' => count($announcements),
            'informations' => count($informations),
            'evenements' => count($events),
            'resultats' => count($results),
            'opportunites' => count($opportunities),
        ];

        if ($this->isAjaxRequest()) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'ok' => true,
                'html' => $this->renderViewToString('Partials/department-posts-list', $data),
                'totalItems' => (int) ($data['totalItems'] ?? 0),
                'currentPage' => (int) ($data['currentPage'] ?? 1),
                'totalPages' => (int) ($data['totalPages'] ?? 1),
            ]);
            return;
        }

        $this->view('departement', $data);
    }

    private function baseViewData(): array
    {
        return [
            // TODO backend: remplacer par des messages de session (flash)
            'flashMessages' => [],
            // TODO backend: remplacer par notifications utilisateur/admin
            'notifications' => [],
        ];
    }

    private function getDashboardTitleByRole(string $role): string
    {
        return match ($role) {
            'admin' => 'Dashboard administrateur',
            'der' => 'Dashboard DER',
            default => 'Dashboard étudiant',
        };
    }

    private function getMockProjects(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Gestion des présences',
                'category' => 'Application web',
                'author' => 'Promo 21',
                'price' => 'Gratuit',
                'image' => ROOT . '/assets/images/thumbs/product-img1.png',
                'description' => 'Application de suivi des présences en salle.',
            ],
            [
                'id' => 2,
                'title' => 'Portail des notes',
                'category' => 'Système académique',
                'author' => 'Promo 21',
                'price' => 'Interne',
                'image' => ROOT . '/assets/images/thumbs/product-img2.png',
                'description' => 'Consultation des résultats et statistiques pédagogiques.',
            ],
            [
                'id' => 3,
                'title' => 'Réservation de salles',
                'category' => 'Outil interne',
                'author' => 'Promo 21',
                'price' => 'Interne',
                'image' => ROOT . '/assets/images/thumbs/product-img3.png',
                'description' => 'Gestion des créneaux et conflits de réservation.',
            ],
        ];
    }

    private function getDepartmentAnnouncements(int $limit = 5): array
    {
        return $this->getDepartmentPostsByType('annonce', $limit);
    }

    private function getDepartmentPostsByType(string $type, int $limit = 5): array
    {
        $postModel = new DepartmentPost();
        $rows = $postModel->getLatestByType($type, $limit);

        if (!empty($rows)) {
            return array_map(static function ($row) use ($type) {
                return [
                    'id' => (int)($row->id ?? 0),
                    'title' => $row->titre ?? '',
                    'date' => !empty($row->publication_date) ? (string)$row->publication_date : (isset($row->created_at) ? date('Y-m-d', strtotime((string)$row->created_at)) : ''),
                    'content' => $row->contenu ?? '',
                    'type' => $row->type ?? $type,
                    'files' => array_map(static function ($file) {
                        $relativePath = ltrim(str_replace('\\', '/', (string)($file->file_path ?? '')), '/');
                        return [
                            'name' => $file->original_name ?? 'Fichier',
                            'url' => ROOT . '/' . $relativePath,
                            'type' => $file->file_type ?? '',
                        ];
                    }, $row->files ?? []),
                ];
            }, $rows);
        }

        return [];
    }

    private function isValidPublicationDate(string $date): bool
    {
        $parsed = DateTime::createFromFormat('Y-m-d', $date);
        return $parsed !== false && $parsed->format('Y-m-d') === $date;
    }

    private function handleDepartmentPostUploads(array $filesInput): array
    {
        if (empty($filesInput) || empty($filesInput['name']) || !is_array($filesInput['name'])) {
            return [[], []];
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/department_posts';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
            return [[], ['Le dossier de stockage des fichiers est introuvable.']];
        }

        $savedFiles = [];
        $errors = [];

        foreach ($filesInput['name'] as $index => $originalName) {
            $originalName = trim((string)$originalName);
            $errorCode = (int)($filesInput['error'][$index] ?? UPLOAD_ERR_NO_FILE);

            if ($errorCode === UPLOAD_ERR_NO_FILE || $originalName === '') {
                continue;
            }

            if ($errorCode !== UPLOAD_ERR_OK) {
                $errors[] = 'Un fichier n’a pas pu être téléversé correctement.';
                continue;
            }

            $tmpName = (string)($filesInput['tmp_name'][$index] ?? '');
            $size = (int)($filesInput['size'][$index] ?? 0);
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            if (!in_array($extension, self::DER_ALLOWED_UPLOAD_EXTENSIONS, true)) {
                $errors[] = 'Le fichier ' . $originalName . ' a une extension non autorisée.';
                continue;
            }

            if ($size <= 0 || $size > self::DER_MAX_FILE_SIZE) {
                $errors[] = 'Le fichier ' . $originalName . ' dépasse la taille maximale de 5 Mo.';
                continue;
            }

            $storedName = uniqid('post_', true) . '.' . $extension;
            $targetPath = $uploadDir . '/' . $storedName;

            if (!move_uploaded_file($tmpName, $targetPath)) {
                $errors[] = 'Impossible d’enregistrer le fichier ' . $originalName . '.';
                continue;
            }

            $savedFiles[] = [
                'original_name' => $originalName,
                'stored_name' => $storedName,
                'file_path' => 'uploads/department_posts/' . $storedName,
                'file_type' => $extension,
            ];
        }

        if (!empty($errors)) {
            $this->cleanupUploadedDepartmentFiles($savedFiles);
            return [[], $errors];
        }

        return [$savedFiles, []];
    }

    private function cleanupUploadedDepartmentFiles(array $files): void
    {
        foreach ($files as $file) {
            $fullPath = dirname(__DIR__, 2) . '/public/' . ltrim((string)($file['file_path'] ?? ''), '/');
            if (is_file($fullPath)) {
                @unlink($fullPath);
            }
        }
    }
}
