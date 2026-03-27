<?php

class Admins extends Controller
{
    private function guardAdmin(): void
    {
        $role = strtolower((string)($_SESSION['role'] ?? ''));
        if ($role !== 'admin') {
            $_SESSION['notification'] = [
                'type' => 'warning',
                'message' => 'Acces reserve aux administrateurs.',
            ];
            $this->redirect('Homes/dashboard');
        }
    }

    private function adminPanel(): AdminPanel
    {
        return new AdminPanel();
    }

    private function redirectWithStatus(AdminPanel $adminPanel, string $route, bool $success, string $successMessage, string $errorMessage): void
    {
        $_SESSION['notification'] = [
            'type' => $success ? 'success' : 'danger',
            'message' => $success ? $successMessage : $errorMessage,
        ];

        $query = trim((string) ($_POST['return_query'] ?? ''));
        if ($query !== '') {
            $query = ltrim($query, '?');
            $route .= '?' . $query;
        }

        $this->redirect($route);
    }

    private function handleProjectModeration(AdminPanel $adminPanel, int $projectId, string $route): void
    {
        if ($projectId <= 0) {
            $this->redirectWithStatus($adminPanel, $route, false, '', 'Projet introuvable.');
        }

        if (isset($_POST['validate_project'])) {
            $this->redirectWithStatus(
                $adminPanel,
                $route,
                $adminPanel->setProjectAdminStatus($projectId, 'valide'),
                'Projet valide avec succes.',
                'Impossible de valider ce projet.'
            );
        }

        if (isset($_POST['set_pending_project'])) {
            $this->redirectWithStatus(
                $adminPanel,
                $route,
                $adminPanel->setProjectAdminStatus($projectId, 'en_attente'),
                'Projet remis en attente.',
                'Impossible de remettre ce projet en attente.'
            );
        }

        if (isset($_POST['reject_project'])) {
            $this->redirectWithStatus(
                $adminPanel,
                $route,
                $adminPanel->setProjectAdminStatus($projectId, 'rejete'),
                'Projet rejete avec succes.',
                'Impossible de rejeter ce projet.'
            );
        }
    }

    private function handleBulkProjectModeration(AdminPanel $adminPanel, string $route, bool $allowPending = true): void
    {
        $projectIds = $_POST['project_ids'] ?? [];
        $projectIds = is_array($projectIds) ? $projectIds : [];

        if (empty($projectIds)) {
            $this->redirectWithStatus($adminPanel, $route, false, '', 'Veuillez selectionner au moins un projet.');
        }

        $targetStatus = null;
        $successMessage = '';

        if (isset($_POST['bulk_validate_projects'])) {
            $targetStatus = 'valide';
            $successMessage = 'Les projets selectionnes ont ete valides.';
        } elseif (isset($_POST['bulk_reject_projects'])) {
            $targetStatus = 'rejete';
            $successMessage = 'Les projets selectionnes ont ete rejetes.';
        } elseif ($allowPending && isset($_POST['bulk_set_pending_projects'])) {
            $targetStatus = 'en_attente';
            $successMessage = 'Les projets selectionnes ont ete remis en attente.';
        }

        if ($targetStatus === null) {
            return;
        }

        $updated = $adminPanel->setManyProjectAdminStatus($projectIds, $targetStatus);
        $this->redirectWithStatus(
            $adminPanel,
            $route,
            $updated > 0,
            $successMessage,
            'Impossible de mettre a jour les projets selectionnes.'
        );
    }

    private function projectListFilters(): array
    {
        $status = trim((string) ($_GET['status'] ?? 'all'));
        $sortBy = trim((string) ($_GET['sort_by'] ?? 'date'));
        $sortDir = trim((string) ($_GET['sort_dir'] ?? 'desc'));

        return [
            'search' => trim((string) ($_GET['search'] ?? '')),
            'status' => in_array($status, ['all', 'en_attente', 'valide', 'rejete'], true) ? $status : 'all',
            'categoryId' => isset($_GET['category']) && $_GET['category'] !== '' ? (int) $_GET['category'] : null,
            'dateFrom' => trim((string) ($_GET['date_from'] ?? '')),
            'dateTo' => trim((string) ($_GET['date_to'] ?? '')),
            'sortBy' => in_array($sortBy, ['date', 'title', 'author', 'category', 'status'], true) ? $sortBy : 'date',
            'sortDir' => in_array(strtolower($sortDir), ['asc', 'desc'], true) ? strtolower($sortDir) : 'desc',
        ];
    }

    private function categoryListFilters(): array
    {
        $sortBy = trim((string) ($_GET['sort_by'] ?? 'name'));
        $sortDir = trim((string) ($_GET['sort_dir'] ?? 'asc'));
        $usage = trim((string) ($_GET['usage'] ?? 'all'));

        return [
            'search' => trim((string) ($_GET['search'] ?? '')),
            'sortBy' => in_array($sortBy, ['name', 'projects', 'description', 'id'], true) ? $sortBy : 'name',
            'sortDir' => in_array(strtolower($sortDir), ['asc', 'desc'], true) ? strtolower($sortDir) : 'asc',
            'usage' => in_array($usage, ['all', 'used', 'unused'], true) ? $usage : 'all',
        ];
    }

    private function paginationParams(int $defaultPerPage = 10): array
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = (int) ($_GET['per_page'] ?? $defaultPerPage);
        $allowedPerPage = [10, 20, 50];
        $perPage = in_array($perPage, $allowedPerPage, true) ? $perPage : $defaultPerPage;

        return [
            'page' => $page,
            'perPage' => $perPage,
        ];
    }

    private function queryStringWithoutPage(array $extra = []): string
    {
        $params = array_merge($_GET, $extra);
        unset($params['page']);

        $query = http_build_query(array_filter($params, static function ($value) {
            return $value !== null && $value !== '';
        }));

        return $query;
    }

    public function index(): void
    {
        $this->dashboard();
    }

    public function dashboard(): void
    {
        $this->guardAdmin();

        $adminPanel = $this->adminPanel();

        $this->view('admin_dashboard', [
            'pageTitle' => 'Dashboard administrateur',
            'dashboardStats' => $adminPanel->getDashboardStats(),
            'pendingProjects' => array_slice($adminPanel->getPendingProjects(), 0, 8),
            'projectPlatformStats' => $adminPanel->getProjectPlatformStats(),
            'mostFollowedProjects' => $adminPanel->getMostFollowedProjects(6),
        ]);
    }

    public function pending_projects(): void
    {
        $this->guardAdmin();

        $adminPanel = $this->adminPanel();
        $filters = $this->projectListFilters();
        $pagination = $this->paginationParams();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['bulk_validate_projects']) || isset($_POST['bulk_reject_projects'])) {
                $this->handleBulkProjectModeration($adminPanel, 'Admins/pending_projects', false);
            }

            $projectId = (int) ($_POST['project_id'] ?? ($_POST['single_validate_project'] ?? $_POST['single_reject_project'] ?? 0));
            if (isset($_POST['single_validate_project'])) {
                $_POST['validate_project'] = true;
            }
            if (isset($_POST['single_reject_project'])) {
                $_POST['reject_project'] = true;
            }
            $this->handleProjectModeration($adminPanel, $projectId, 'Admins/pending_projects');
        }

        $projectPage = $adminPanel->getPendingProjectsPaginated(
            $filters['search'],
            $filters['categoryId'],
            $filters['dateFrom'],
            $filters['dateTo'],
            $filters['sortBy'],
            $filters['sortDir'],
            $pagination['page'],
            $pagination['perPage']
        );

        $this->view('admin_pending_projects', [
            'pageTitle' => 'Projets a valider',
            'projects' => $projectPage['items'],
            'projectSearch' => $filters['search'],
            'projectCategoryFilter' => $filters['categoryId'],
            'projectDateFrom' => $filters['dateFrom'],
            'projectDateTo' => $filters['dateTo'],
            'projectSortBy' => $filters['sortBy'],
            'projectSortDir' => $filters['sortDir'],
            'categories' => $adminPanel->getCategories(),
            'currentPage' => $projectPage['page'],
            'perPage' => $projectPage['perPage'],
            'totalPages' => $projectPage['totalPages'],
            'totalItems' => $projectPage['total'],
            'paginationQuery' => $this->queryStringWithoutPage(['per_page' => $pagination['perPage']]),
        ]);
    }

    public function projects_management(): void
    {
        $this->guardAdmin();

        $adminPanel = $this->adminPanel();
        $filters = $this->projectListFilters();
        $pagination = $this->paginationParams();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['bulk_validate_projects']) || isset($_POST['bulk_reject_projects']) || isset($_POST['bulk_set_pending_projects'])) {
                $this->handleBulkProjectModeration($adminPanel, 'Admins/projects_management', true);
            }

            $projectId = (int) ($_POST['project_id'] ?? ($_POST['single_validate_project'] ?? $_POST['single_set_pending_project'] ?? $_POST['single_reject_project'] ?? 0));
            if (isset($_POST['single_validate_project'])) {
                $_POST['validate_project'] = true;
            }
            if (isset($_POST['single_set_pending_project'])) {
                $_POST['set_pending_project'] = true;
            }
            if (isset($_POST['single_reject_project'])) {
                $_POST['reject_project'] = true;
            }
            $this->handleProjectModeration($adminPanel, $projectId, 'Admins/projects_management');
        }

        $projectPage = $adminPanel->getAllProjectsPaginated(
            $filters['search'],
            $filters['status'],
            $filters['categoryId'],
            $filters['dateFrom'],
            $filters['dateTo'],
            $filters['sortBy'],
            $filters['sortDir'],
            $pagination['page'],
            $pagination['perPage']
        );

        $this->view('admin_projects_management', [
            'pageTitle' => 'Gestion des projets',
            'projects' => $projectPage['items'],
            'projectSearch' => $filters['search'],
            'projectStatusFilter' => $filters['status'],
            'projectCategoryFilter' => $filters['categoryId'],
            'projectDateFrom' => $filters['dateFrom'],
            'projectDateTo' => $filters['dateTo'],
            'projectSortBy' => $filters['sortBy'],
            'projectSortDir' => $filters['sortDir'],
            'categories' => $adminPanel->getCategories(),
            'currentPage' => $projectPage['page'],
            'perPage' => $projectPage['perPage'],
            'totalPages' => $projectPage['totalPages'],
            'totalItems' => $projectPage['total'],
            'paginationQuery' => $this->queryStringWithoutPage(['per_page' => $pagination['perPage']]),
        ]);
    }

    public function project_detail($id): void
    {
        $this->guardAdmin();

        $projectId = (int) $id;
        $adminPanel = $this->adminPanel();
        $projectModel = new Projet();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleProjectModeration($adminPanel, $projectId, 'Admins/project_detail/' . $projectId);
        }

        $project = $adminPanel->getProjectAdminDetail($projectId);
        if (!$project) {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Projet introuvable.',
            ];
            $this->redirect('Admins/projects_management');
        }

        $this->view('admin_project_detail', [
            'pageTitle' => 'Detail projet administrateur',
            'project' => $project,
            'images' => $projectModel->getProjectImages($projectId),
            'files' => $projectModel->getProjectFiles($projectId),
            'reviewSummary' => $projectModel->getProjectReviewSummary($projectId),
            'reviews' => $projectModel->getProjectReviews($projectId),
            'metrics' => $adminPanel->getProjectAdminMetrics($projectId),
        ]);
    }

    public function most_followed_projects(): void
    {
        $this->guardAdmin();

        $adminPanel = $this->adminPanel();
        $pagination = $this->paginationParams();
        $projectPage = $adminPanel->getMostFollowedProjectsPaginated($pagination['page'], $pagination['perPage']);

        $this->view('admin_most_followed_projects', [
            'pageTitle' => 'Projets les plus suivis',
            'projects' => $projectPage['items'],
            'projectPlatformStats' => $adminPanel->getProjectPlatformStats(),
            'currentPage' => $projectPage['page'],
            'perPage' => $projectPage['perPage'],
            'totalPages' => $projectPage['totalPages'],
            'totalItems' => $projectPage['total'],
            'paginationQuery' => $this->queryStringWithoutPage(['per_page' => $pagination['perPage']]),
        ]);
    }

    public function statistics(): void
    {
        $this->guardAdmin();

        $adminPanel = $this->adminPanel();

        $this->view('admin_statistics', [
            'pageTitle' => 'Statistiques administrateur',
            'dashboardStats' => $adminPanel->getDashboardStats(),
            'projectPlatformStats' => $adminPanel->getProjectPlatformStats(),
            'categoryStats' => $adminPanel->getProjectsByCategoryStats(6),
            'monthlyStats' => $adminPanel->getProjectMonthlyStats(6),
            'topAuthors' => $adminPanel->getTopProjectAuthors(5),
        ]);
    }

    public function users_management(): void
    {
        $this->guardAdmin();
        $this->redirect('Utilisateurs/liste_utilisateur');
    }

    public function categories(): void
    {
        $this->guardAdmin();

        $adminPanel = $this->adminPanel();
        $filters = $this->categoryListFilters();
        $pagination = $this->paginationParams();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_category'])) {
                $nom = trim((string) ($_POST['nom'] ?? ''));
                $description = trim((string) ($_POST['description'] ?? ''));

                if ($nom === '') {
                    $this->redirectWithStatus($adminPanel, 'Admins/categories', false, '', 'Le nom de la categorie est obligatoire.');
                }

                $this->redirectWithStatus(
                    $adminPanel,
                    'Admins/categories',
                    $adminPanel->addCategory($nom, $description),
                    'Categorie ajoutee avec succes.',
                    'Impossible d ajouter cette categorie.'
                );
            }

            if (isset($_POST['update_category'])) {
                $id = (int) ($_POST['id'] ?? 0);
                $nom = trim((string) ($_POST['nom'] ?? ''));
                $description = trim((string) ($_POST['description'] ?? ''));

                if ($id <= 0 || $nom === '') {
                    $this->redirectWithStatus($adminPanel, 'Admins/categories', false, '', 'Categorie invalide.');
                }

                $this->redirectWithStatus(
                    $adminPanel,
                    'Admins/categories',
                    $adminPanel->updateCategory($id, $nom, $description),
                    'Categorie mise a jour avec succes.',
                    'Impossible de modifier cette categorie.'
                );
            }

            if (isset($_POST['delete_category'])) {
                $id = (int) ($_POST['id'] ?? 0);

                if ($id <= 0) {
                    $this->redirectWithStatus($adminPanel, 'Admins/categories', false, '', 'Categorie introuvable.');
                }

                $this->redirectWithStatus(
                    $adminPanel,
                    'Admins/categories',
                    $adminPanel->deleteCategory($id),
                    'Categorie supprimee avec succes.',
                    'Impossible de supprimer cette categorie.'
                );
            }
        }

        $categoryPage = $adminPanel->getCategoriesPaginated(
            $filters['search'],
            $filters['sortBy'],
            $filters['sortDir'],
            $filters['usage'],
            $pagination['page'],
            $pagination['perPage']
        );

        $this->view('admin_categories', [
            'pageTitle' => 'Gestion des categories',
            'categories' => $categoryPage['items'],
            'categoryStats' => $adminPanel->getCategoryOverviewStats(),
            'categorySearch' => $filters['search'],
            'categorySortBy' => $filters['sortBy'],
            'categorySortDir' => $filters['sortDir'],
            'categoryUsageFilter' => $filters['usage'],
            'currentPage' => $categoryPage['page'],
            'perPage' => $categoryPage['perPage'],
            'totalPages' => $categoryPage['totalPages'],
            'totalItems' => $categoryPage['total'],
            'paginationQuery' => $this->queryStringWithoutPage(['per_page' => $pagination['perPage']]),
        ]);
    }

    public function messages(): void
    {
        $this->guardAdmin();

        $adminPanel = $this->adminPanel();
        $pagination = $this->paginationParams();
        $messagePage = $adminPanel->getMessagesPaginated($pagination['page'], $pagination['perPage']);

        $this->view('admin_messages', [
            'pageTitle' => 'Messages / Contact',
            'messages' => $messagePage['items'],
            'currentPage' => $messagePage['page'],
            'perPage' => $messagePage['perPage'],
            'totalPages' => $messagePage['totalPages'],
            'totalItems' => $messagePage['total'],
            'paginationQuery' => $this->queryStringWithoutPage(['per_page' => $pagination['perPage']]),
        ]);
    }

    public function message_detail($id): void
    {
        $this->guardAdmin();

        $messageId = (int) $id;
        $adminPanel = $this->adminPanel();
        $message = $adminPanel->getMessageById($messageId);

        if (!$message) {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Message introuvable.',
            ];
            $this->redirect('Admins/messages');
        }

        $this->view('admin_message_detail', [
            'pageTitle' => 'Detail message utilisateur',
            'messageItem' => $message,
        ]);
    }
}
