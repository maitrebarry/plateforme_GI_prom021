<?php

class AdminPanel extends Model
{
    private const ADMIN_STATUSES = ['en_attente', 'valide', 'rejete'];

    private function synchronizeProjectStatuses(): void
    {
        if (!$this->tableExists('projects') || !$this->columnExists('projects', 'admin_status')) {
            return;
        }

        $this->insertion_update_simples(
            "UPDATE projects
             SET admin_status = CASE
                 WHEN admin_status IN ('en_attente', 'valide', 'rejete') THEN admin_status
                 WHEN LOWER(TRIM(COALESCE(status, ''))) IN ('termine', 'valide', 'publie', 'publiee', 'accepte', 'acceptee') THEN 'valide'
                 WHEN LOWER(TRIM(COALESCE(status, ''))) IN ('rejete', 'refuse', 'refusee') THEN 'rejete'
                 ELSE 'en_attente'
             END"
        );

        $this->insertion_update_simples(
            "UPDATE projects
             SET status = CASE
                 WHEN admin_status = 'valide' THEN 'termine'
                 ELSE 'en cours'
             END
             WHERE status IS NULL OR status = '' OR status NOT IN ('en cours', 'termine')"
        );
    }

    private function buildProjectWhereClause(
        string $search = '',
        string $status = 'all',
        ?int $categoryId = null,
        string $dateFrom = '',
        string $dateTo = ''
    ): array
    {
        $clauses = [];
        $params = [];

        $search = trim($search);
        if ($search !== '') {
            $clauses[] = "(p.title LIKE ? OR COALESCE(p.description, '') LIKE ? OR COALESCE(c.nom, '') LIKE ? OR COALESCE(CONCAT(u.nom, ' ', u.prenom), '') LIKE ?)";
            $term = '%' . $search . '%';
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        if ($status === 'non_valide') {
            $clauses[] = "COALESCE(p.admin_status, 'en_attente') <> ?";
            $params[] = 'valide';
        } elseif (in_array($status, self::ADMIN_STATUSES, true)) {
            $clauses[] = 'p.admin_status = ?';
            $params[] = $status;
        }

        if ($categoryId !== null && $categoryId > 0) {
            $clauses[] = 'p.category_id = ?';
            $params[] = $categoryId;
        }

        if ($dateFrom !== '') {
            $clauses[] = 'DATE(p.created_at) >= ?';
            $params[] = $dateFrom;
        }

        if ($dateTo !== '') {
            $clauses[] = 'DATE(p.created_at) <= ?';
            $params[] = $dateTo;
        }

        $whereSql = '';
        if (!empty($clauses)) {
            $whereSql = ' WHERE ' . implode(' AND ', $clauses);
        }

        return [
            'where' => $whereSql,
            'params' => $params,
        ];
    }

    private function buildProjectOrderBy(string $sortBy = 'date', string $sortDir = 'desc'): string
    {
        $allowedSorts = [
            'date' => 'p.created_at',
            'title' => 'p.title',
            'author' => 'auteur',
            'category' => 'categorie',
            'status' => 'statut',
        ];

        $sortBy = array_key_exists($sortBy, $allowedSorts) ? $sortBy : 'date';
        $sortDir = strtolower($sortDir) === 'asc' ? 'ASC' : 'DESC';

        return ' ORDER BY ' . $allowedSorts[$sortBy] . ' ' . $sortDir;
    }

    private function tableExists(string $table): bool
    {
        $row = $this->FetchSelectWhere(
            'COUNT(*) AS total',
            'information_schema.tables',
            'table_schema = DATABASE() AND table_name = ?',
            [$table]
        );

        return !empty($row) && (int) ($row->total ?? 0) > 0;
    }

    private function columnExists(string $table, string $column): bool
    {
        $row = $this->FetchSelectWhere(
            'COUNT(*) AS total',
            'information_schema.columns',
            'table_schema = DATABASE() AND table_name = ? AND column_name = ?',
            [$table, $column]
        );

        return !empty($row) && (int) ($row->total ?? 0) > 0;
    }

    private function ensureAdminStatusColumn(): bool
    {
        if (!$this->tableExists('projects')) {
            return false;
        }

        if ($this->columnExists('projects', 'admin_status')) {
            $this->synchronizeProjectStatuses();
            return true;
        }

        $this->insertion_update_simples(
            "ALTER TABLE projects
             ADD COLUMN admin_status ENUM('en_attente','valide','rejete') NOT NULL DEFAULT 'en_attente'
             AFTER status"
        );

        $this->insertion_update_simples(
            "UPDATE projects
             SET admin_status = CASE
                 WHEN LOWER(TRIM(COALESCE(status, ''))) IN ('termine', 'valide', 'publie', 'publiee', 'accepte', 'acceptee') THEN 'valide'
                 WHEN LOWER(TRIM(COALESCE(status, ''))) IN ('rejete', 'refuse', 'refusee') THEN 'rejete'
                 ELSE 'en_attente'
             END"
        );

        $this->synchronizeProjectStatuses();

        return true;
    }

    private function projectStatusSql(): string
    {
        $this->ensureAdminStatusColumn();
        return 'COALESCE(p.admin_status, \'en_attente\')';
    }

    private function normalizePagination(int $page = 1, int $perPage = 10): array
    {
        $allowedPerPage = [10, 20, 50];
        $page = max(1, $page);
        $perPage = in_array($perPage, $allowedPerPage, true) ? $perPage : 10;

        return [
            'page' => $page,
            'perPage' => $perPage,
            'offset' => ($page - 1) * $perPage,
        ];
    }

    private function paginateSelect(string $countSql, string $dataSql, array $params = [], int $page = 1, int $perPage = 10): array
    {
        $pagination = $this->normalizePagination($page, $perPage);
        $countRows = $this->select_data_table_join_where($countSql, $params);
        $total = (int) ($countRows[0]->total ?? 0);
        $totalPages = max(1, (int) ceil($total / $pagination['perPage']));
        $page = min($pagination['page'], $totalPages);
        $offset = ($page - 1) * $pagination['perPage'];

        $items = $this->select_data_table_join_where(
            $dataSql . " LIMIT {$pagination['perPage']} OFFSET {$offset}",
            $params
        );

        return [
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'perPage' => $pagination['perPage'],
            'totalPages' => $totalPages,
        ];
    }

    public function getDashboardStats(): array
    {
        $stats = [
            'users' => 0,
            'projects' => 0,
            'pending' => 0,
            'messages' => 0,
            'categories' => 0,
            'validated' => 0,
            'rejected' => 0,
        ];

        if ($this->tableExists('users')) {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'users', '1=1');
            $stats['users'] = (int) ($row->total ?? 0);
        }

        if ($this->tableExists('projects')) {
            $this->ensureAdminStatusColumn();

            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'projects', '1=1');
            $stats['projects'] = (int) ($row->total ?? 0);

            $pending = $this->FetchSelectWhere('COUNT(*) AS total', 'projects', 'admin_status = ?', ['en_attente']);
            $validated = $this->FetchSelectWhere('COUNT(*) AS total', 'projects', 'admin_status = ?', ['valide']);
            $rejected = $this->FetchSelectWhere('COUNT(*) AS total', 'projects', 'admin_status = ?', ['rejete']);

            $stats['pending'] = (int) ($pending->total ?? 0);
            $stats['validated'] = (int) ($validated->total ?? 0);
            $stats['rejected'] = (int) ($rejected->total ?? 0);
        }

        if ($this->tableExists('messages')) {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'messages', '1=1');
            $stats['messages'] = (int) ($row->total ?? 0);
        }

        if ($this->tableExists('categories')) {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'categories', '1=1');
            $stats['categories'] = (int) ($row->total ?? 0);
        }

        return $stats;
    }

    public function getPendingProjects(
        string $search = '',
        ?int $categoryId = null,
        string $dateFrom = '',
        string $dateTo = '',
        string $sortBy = 'date',
        string $sortDir = 'desc'
    ): array
    {
        return $this->getPendingProjectsPaginated($search, $categoryId, $dateFrom, $dateTo, $sortBy, $sortDir)['items'];
    }

    public function getPendingProjectsPaginated(
        string $search = '',
        ?int $categoryId = null,
        string $dateFrom = '',
        string $dateTo = '',
        string $sortBy = 'date',
        string $sortDir = 'desc',
        int $page = 1,
        int $perPage = 10
    ): array
    {
        if (!$this->tableExists('projects')) {
            return [
                'items' => [],
                'total' => 0,
                'page' => 1,
                'perPage' => $perPage,
                'totalPages' => 1,
            ];
        }

        $this->ensureAdminStatusColumn();
        $filters = $this->buildProjectWhereClause($search, 'non_valide', $categoryId, $dateFrom, $dateTo);
        $orderBy = $this->buildProjectOrderBy($sortBy, $sortDir);
        $fromSql = "FROM projects p
                    LEFT JOIN users u ON u.user_id = p.user_id
                    LEFT JOIN categories c ON c.id = p.category_id
                    {$filters['where']}";

        return $this->paginateSelect(
            "SELECT COUNT(*) AS total {$fromSql}",
            "SELECT p.id, p.title, p.created_at,
                    COALESCE(CONCAT(u.nom, ' ', u.prenom), 'N/A') AS auteur,
                    COALESCE(c.nom, 'Sans categorie') AS categorie,
                    COALESCE(p.admin_status, 'en_attente') AS statut
             {$fromSql}
             {$orderBy}",
            $filters['params'],
            $page,
            $perPage
        );
    }

    public function getAllProjects(
        string $search = '',
        string $status = 'all',
        ?int $categoryId = null,
        string $dateFrom = '',
        string $dateTo = '',
        string $sortBy = 'date',
        string $sortDir = 'desc'
    ): array
    {
        return $this->getAllProjectsPaginated($search, $status, $categoryId, $dateFrom, $dateTo, $sortBy, $sortDir)['items'];
    }

    public function getAllProjectsPaginated(
        string $search = '',
        string $status = 'all',
        ?int $categoryId = null,
        string $dateFrom = '',
        string $dateTo = '',
        string $sortBy = 'date',
        string $sortDir = 'desc',
        int $page = 1,
        int $perPage = 10
    ): array
    {
        if (!$this->tableExists('projects')) {
            return [
                'items' => [],
                'total' => 0,
                'page' => 1,
                'perPage' => $perPage,
                'totalPages' => 1,
            ];
        }

        $statusSql = $this->projectStatusSql();
        $filters = $this->buildProjectWhereClause($search, $status, $categoryId, $dateFrom, $dateTo);
        $orderBy = $this->buildProjectOrderBy($sortBy, $sortDir);
        $fromSql = "FROM projects p
                    LEFT JOIN users u ON u.user_id = p.user_id
                    LEFT JOIN categories c ON c.id = p.category_id
                    {$filters['where']}";

        return $this->paginateSelect(
            "SELECT COUNT(*) AS total {$fromSql}",
            "SELECT p.id, p.title, p.created_at,
                    COALESCE(CONCAT(u.nom, ' ', u.prenom), 'N/A') AS auteur,
                    COALESCE(c.nom, 'Sans categorie') AS categorie,
                    {$statusSql} AS statut
             {$fromSql}
             {$orderBy}",
            $filters['params'],
            $page,
            $perPage
        );
    }

    public function setProjectAdminStatus(int $projectId, string $status): bool
    {
        if (!$this->tableExists('projects')) {
            return false;
        }

        if ($projectId <= 0 || !in_array($status, self::ADMIN_STATUSES, true)) {
            return false;
        }

        $this->ensureAdminStatusColumn();

        $legacyStatus = $status === 'valide' ? 'termine' : 'en cours';

        $query = $this->insertion_update_simples(
            'UPDATE projects SET admin_status = ?, status = ? WHERE id = ?',
            [$status, $legacyStatus, $projectId]
        );

        return $query->rowCount() > 0;
    }

    public function setManyProjectAdminStatus(array $projectIds, string $status): int
    {
        if (!$this->tableExists('projects') || !in_array($status, self::ADMIN_STATUSES, true)) {
            return 0;
        }

        $projectIds = array_values(array_unique(array_filter(array_map('intval', $projectIds), static fn(int $id): bool => $id > 0)));
        if (empty($projectIds)) {
            return 0;
        }

        $this->ensureAdminStatusColumn();

        $legacyStatus = $status === 'valide' ? 'termine' : 'en cours';
        $placeholders = implode(',', array_fill(0, count($projectIds), '?'));
        $params = array_merge([$status, $legacyStatus], $projectIds);

        $query = $this->insertion_update_simples(
            "UPDATE projects
             SET admin_status = ?, status = ?
             WHERE id IN ({$placeholders})",
            $params
        );

        return (int) $query->rowCount();
    }

    public function getProjectAdminDetail(int $projectId)
    {
        if (!$this->tableExists('projects') || $projectId <= 0) {
            return null;
        }

        $statusSql = $this->projectStatusSql();

        $rows = $this->select_data_table_join_where(
            "SELECT p.*,
                    {$statusSql} AS statut_admin,
                    COALESCE(c.nom, 'Sans categorie') AS categorie,
                    u.user_id AS owner_id,
                    u.nom,
                    u.prenom,
                    u.email,
                    u.contact,
                    u.image AS owner_image,
                    u.github,
                    u.linkedin,
                    u.universite,
                    u.faculte,
                    u.filiere
             FROM projects p
             LEFT JOIN categories c ON c.id = p.category_id
             LEFT JOIN users u ON u.user_id = p.user_id
             WHERE p.id = ?
             LIMIT 1",
            [$projectId]
        );

        return $rows[0] ?? null;
    }

    public function getProjectAdminMetrics(int $projectId): array
    {
        $metrics = [
            'likes' => 0,
            'reviews' => 0,
            'messages' => 0,
            'images' => 0,
            'files' => 0,
        ];

        if ($projectId <= 0) {
            return $metrics;
        }

        if ($this->tableExists('project_likes')) {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'project_likes', 'project_id = ?', [$projectId]);
            $metrics['likes'] = (int) ($row->total ?? 0);
        }

        if ($this->tableExists('project_reviews')) {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'project_reviews', 'project_id = ?', [$projectId]);
            $metrics['reviews'] = (int) ($row->total ?? 0);
        }

        if ($this->tableExists('project_messages')) {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'project_messages', 'project_id = ?', [$projectId]);
            $metrics['messages'] = (int) ($row->total ?? 0);
        }

        if ($this->tableExists('project_images')) {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'project_images', 'project_id = ?', [$projectId]);
            $metrics['images'] = (int) ($row->total ?? 0);
        }

        if ($this->tableExists('project_files')) {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'project_files', 'project_id = ?', [$projectId]);
            $metrics['files'] = (int) ($row->total ?? 0);
        }

        return $metrics;
    }

    public function getProjectPlatformStats(): array
    {
        $stats = [
            'likes' => 0,
            'reviews' => 0,
            'messages' => 0,
            'owners' => 0,
            'average_rating' => 0.0,
        ];

        if ($this->tableExists('project_likes')) {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'project_likes', '1=1');
            $stats['likes'] = (int) ($row->total ?? 0);
        }

        if ($this->tableExists('project_reviews')) {
            $row = $this->select_data_table_join_where(
                'SELECT COUNT(*) AS total, COALESCE(ROUND(AVG(rating), 1), 0) AS average_rating FROM project_reviews'
            );
            $stats['reviews'] = (int) ($row[0]->total ?? 0);
            $stats['average_rating'] = (float) ($row[0]->average_rating ?? 0);
        }

        if ($this->tableExists('project_messages')) {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'project_messages', '1=1');
            $stats['messages'] = (int) ($row->total ?? 0);
        }

        if ($this->tableExists('projects')) {
            $row = $this->select_data_table_join_where('SELECT COUNT(DISTINCT user_id) AS total FROM projects');
            $stats['owners'] = (int) ($row[0]->total ?? 0);
        }

        return $stats;
    }

    public function getMostFollowedProjects(int $limit = 5): array
    {
        if (!$this->tableExists('projects')) {
            return [];
        }

        $this->ensureAdminStatusColumn();
        $limit = max(1, min(12, $limit));
        $hasLikes = $this->tableExists('project_likes');
        $hasReviews = $this->tableExists('project_reviews');
        $hasMessages = $this->tableExists('project_messages');

        return $this->select_data_table_join_where(
            "SELECT
                p.id,
                p.title,
                p.created_at,
                COALESCE(p.admin_status, 'en_attente') AS statut,
                COALESCE(CONCAT(u.nom, ' ', u.prenom), 'N/A') AS auteur,
                COALESCE(c.nom, 'Sans categorie') AS categorie,
                " . ($hasLikes ? "COUNT(DISTINCT pl.id)" : "0") . " AS likes_count,
                " . ($hasReviews ? "COUNT(DISTINCT pr.id)" : "0") . " AS reviews_count,
                " . ($hasMessages ? "COUNT(DISTINCT pm.id)" : "0") . " AS messages_count,
                " . ($hasReviews ? "COALESCE(ROUND(AVG(pr.rating), 1), 0)" : "0") . " AS average_rating
             FROM projects p
             LEFT JOIN users u ON u.user_id = p.user_id
             LEFT JOIN categories c ON c.id = p.category_id
             " . ($hasLikes ? "LEFT JOIN project_likes pl ON pl.project_id = p.id" : "") . "
             " . ($hasReviews ? "LEFT JOIN project_reviews pr ON pr.project_id = p.id" : "") . "
             " . ($hasMessages ? "LEFT JOIN project_messages pm ON pm.project_id = p.id" : "") . "
             GROUP BY p.id, p.title, p.created_at, p.admin_status, u.nom, u.prenom, c.nom
             ORDER BY likes_count DESC, messages_count DESC, reviews_count DESC, average_rating DESC, p.created_at DESC
             LIMIT {$limit}"
        );
    }

    public function getMostFollowedProjectsPaginated(int $page = 1, int $perPage = 10): array
    {
        if (!$this->tableExists('projects')) {
            return [
                'items' => [],
                'total' => 0,
                'page' => 1,
                'perPage' => $perPage,
                'totalPages' => 1,
            ];
        }

        $this->ensureAdminStatusColumn();
        $hasLikes = $this->tableExists('project_likes');
        $hasReviews = $this->tableExists('project_reviews');
        $hasMessages = $this->tableExists('project_messages');
        $pagination = $this->normalizePagination($page, $perPage);
        $totalRow = $this->FetchSelectWhere('COUNT(*) AS total', 'projects', '1=1');
        $total = (int) ($totalRow->total ?? 0);
        $totalPages = max(1, (int) ceil($total / $pagination['perPage']));
        $page = min($pagination['page'], $totalPages);
        $offset = ($page - 1) * $pagination['perPage'];

        $items = $this->select_data_table_join_where(
            "SELECT
                p.id,
                p.title,
                p.created_at,
                COALESCE(p.admin_status, 'en_attente') AS statut,
                COALESCE(CONCAT(u.nom, ' ', u.prenom), 'N/A') AS auteur,
                COALESCE(c.nom, 'Sans categorie') AS categorie,
                " . ($hasLikes ? "COUNT(DISTINCT pl.id)" : "0") . " AS likes_count,
                " . ($hasReviews ? "COUNT(DISTINCT pr.id)" : "0") . " AS reviews_count,
                " . ($hasMessages ? "COUNT(DISTINCT pm.id)" : "0") . " AS messages_count,
                " . ($hasReviews ? "COALESCE(ROUND(AVG(pr.rating), 1), 0)" : "0") . " AS average_rating
             FROM projects p
             LEFT JOIN users u ON u.user_id = p.user_id
             LEFT JOIN categories c ON c.id = p.category_id
             " . ($hasLikes ? "LEFT JOIN project_likes pl ON pl.project_id = p.id" : "") . "
             " . ($hasReviews ? "LEFT JOIN project_reviews pr ON pr.project_id = p.id" : "") . "
             " . ($hasMessages ? "LEFT JOIN project_messages pm ON pm.project_id = p.id" : "") . "
             GROUP BY p.id, p.title, p.created_at, p.admin_status, u.nom, u.prenom, c.nom
             ORDER BY likes_count DESC, messages_count DESC, reviews_count DESC, average_rating DESC, p.created_at DESC
             LIMIT {$pagination['perPage']} OFFSET {$offset}"
        );

        return [
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'perPage' => $pagination['perPage'],
            'totalPages' => $totalPages,
        ];
    }

    public function getProjectsByCategoryStats(int $limit = 6): array
    {
        if (!$this->tableExists('projects')) {
            return [];
        }

        $this->ensureAdminStatusColumn();
        $limit = max(1, min(12, $limit));

        return $this->select_data_table_join_where(
            "SELECT
                COALESCE(c.nom, 'Sans categorie') AS label,
                COUNT(*) AS total,
                SUM(CASE WHEN COALESCE(p.admin_status, 'en_attente') = 'valide' THEN 1 ELSE 0 END) AS validated_total,
                SUM(CASE WHEN COALESCE(p.admin_status, 'en_attente') = 'en_attente' THEN 1 ELSE 0 END) AS pending_total,
                SUM(CASE WHEN COALESCE(p.admin_status, 'en_attente') = 'rejete' THEN 1 ELSE 0 END) AS rejected_total
             FROM projects p
             LEFT JOIN categories c ON c.id = p.category_id
             GROUP BY COALESCE(c.nom, 'Sans categorie')
             ORDER BY total DESC, label ASC
             LIMIT {$limit}"
        );
    }

    public function getProjectMonthlyStats(int $months = 6): array
    {
        if (!$this->tableExists('projects')) {
            return [];
        }

        $this->ensureAdminStatusColumn();
        $months = max(3, min(12, $months));
        $rows = $this->select_data_table_join_where(
            "SELECT
                DATE_FORMAT(created_at, '%Y-%m') AS month_key,
                DATE_FORMAT(created_at, '%b %Y') AS month_label,
                COUNT(*) AS total,
                SUM(CASE WHEN COALESCE(admin_status, 'en_attente') = 'valide' THEN 1 ELSE 0 END) AS validated_total,
                SUM(CASE WHEN COALESCE(admin_status, 'en_attente') = 'en_attente' THEN 1 ELSE 0 END) AS pending_total,
                SUM(CASE WHEN COALESCE(admin_status, 'en_attente') = 'rejete' THEN 1 ELSE 0 END) AS rejected_total
             FROM projects
             GROUP BY DATE_FORMAT(created_at, '%Y-%m'), DATE_FORMAT(created_at, '%b %Y')
             ORDER BY month_key DESC
             LIMIT {$months}"
        );

        return array_reverse($rows);
    }

    public function getTopProjectAuthors(int $limit = 5): array
    {
        if (!$this->tableExists('projects') || !$this->tableExists('users')) {
            return [];
        }

        $this->ensureAdminStatusColumn();
        $limit = max(1, min(10, $limit));

        return $this->select_data_table_join_where(
            "SELECT
                COALESCE(CONCAT(u.nom, ' ', u.prenom), 'Auteur inconnu') AS label,
                COUNT(*) AS total,
                SUM(CASE WHEN COALESCE(p.admin_status, 'en_attente') = 'valide' THEN 1 ELSE 0 END) AS validated_total
             FROM projects p
             LEFT JOIN users u ON u.user_id = p.user_id
             GROUP BY COALESCE(CONCAT(u.nom, ' ', u.prenom), 'Auteur inconnu')
             ORDER BY total DESC, label ASC
             LIMIT {$limit}"
        );
    }

    public function getCategories(): array
    {
        if (!$this->tableExists('categories')) {
            return [];
        }

        return $this->select_data_table_join_where(
            'SELECT id, nom, description FROM categories ORDER BY nom ASC'
        );
    }

    public function getCategoriesPaginated(
        string $search = '',
        string $sortBy = 'name',
        string $sortDir = 'asc',
        string $usage = 'all',
        int $page = 1,
        int $perPage = 10
    ): array
    {
        if (!$this->tableExists('categories')) {
            return [
                'items' => [],
                'total' => 0,
                'page' => 1,
                'perPage' => $perPage,
                'totalPages' => 1,
            ];
        }

        $search = trim($search);
        $clauses = [];
        $params = [];

        if ($search !== '') {
            $clauses[] = '(c.nom LIKE ? OR COALESCE(c.description, \'\') LIKE ?)';
            $term = '%' . $search . '%';
            $params[] = $term;
            $params[] = $term;
        }

        if ($usage === 'used') {
            $clauses[] = 'COUNT(p.id) > 0';
        } elseif ($usage === 'unused') {
            $clauses[] = 'COUNT(p.id) = 0';
        }

        $allowedSorts = [
            'name' => 'c.nom',
            'projects' => 'total_projects',
            'description' => 'c.description',
            'id' => 'c.id',
        ];
        $sortBy = $allowedSorts[$sortBy] ?? $allowedSorts['name'];
        $sortDir = strtolower($sortDir) === 'desc' ? 'DESC' : 'ASC';
        $havingSql = !empty($clauses) ? ' HAVING ' . implode(' AND ', $clauses) : '';

        $baseSql = " FROM categories c
                     LEFT JOIN projects p ON p.category_id = c.id
                     GROUP BY c.id, c.nom, c.description";

        return $this->paginateSelect(
            "SELECT COUNT(*) AS total FROM (
                SELECT c.id
                {$baseSql}
                {$havingSql}
            ) category_rows",
            "SELECT
                c.id,
                c.nom,
                c.description,
                COUNT(p.id) AS total_projects
             {$baseSql}
             {$havingSql}
             ORDER BY {$sortBy} {$sortDir}",
            $params,
            $page,
            $perPage
        );
    }

    public function getCategoryOverviewStats(): array
    {
        if (!$this->tableExists('categories')) {
            return [
                'total' => 0,
                'used' => 0,
                'unused' => 0,
            ];
        }

        $totalRow = $this->FetchSelectWhere('COUNT(*) AS total', 'categories', '1=1');
        $usedRows = $this->select_data_table_join_where(
            "SELECT COUNT(*) AS total
             FROM (
                SELECT c.id
                FROM categories c
                LEFT JOIN projects p ON p.category_id = c.id
                GROUP BY c.id
                HAVING COUNT(p.id) > 0
             ) used_categories"
        );

        $used = (int) ($usedRows[0]->total ?? 0);
        $total = (int) ($totalRow->total ?? 0);

        return [
            'total' => $total,
            'used' => $used,
            'unused' => max(0, $total - $used),
        ];
    }

    public function addCategory(string $nom, string $description): bool
    {
        if (!$this->tableExists('categories')) {
            return false;
        }

        $query = $this->insertion_update_simples(
            'INSERT INTO categories (nom, description) VALUES (?, ?)',
            [$nom, $description !== '' ? $description : null]
        );

        return $query->rowCount() > 0;
    }

    public function updateCategory(int $id, string $nom, string $description): bool
    {
        if (!$this->tableExists('categories') || $id <= 0) {
            return false;
        }

        $query = $this->insertion_update_simples(
            'UPDATE categories SET nom = ?, description = ? WHERE id = ?',
            [$nom, $description !== '' ? $description : null, $id]
        );

        return $query->rowCount() > 0;
    }

    public function deleteCategory(int $id): bool
    {
        if (!$this->tableExists('categories') || $id <= 0) {
            return false;
        }

        $query = $this->insertion_update_simples('DELETE FROM categories WHERE id = ?', [$id]);
        return $query->rowCount() > 0;
    }

    public function getMessages(): array
    {
        return $this->getMessagesPaginated()['items'];
    }

    public function getMessagesPaginated(int $page = 1, int $perPage = 10): array
    {
        if (!$this->tableExists('messages')) {
            return [
                'items' => [],
                'total' => 0,
                'page' => 1,
                'perPage' => $perPage,
                'totalPages' => 1,
            ];
        }

        return $this->paginateSelect(
            "SELECT COUNT(*) AS total
             FROM messages m",
            "SELECT m.id, m.nom, m.email, m.message, m.created_at,
                    COALESCE(p.title, 'Projet supprime') AS projet
             FROM messages m
             LEFT JOIN projects p ON p.id = m.project_id
             ORDER BY m.created_at DESC",
            [],
            $page,
            $perPage
        );
    }

    public function getMessageById(int $messageId)
    {
        if (!$this->tableExists('messages') || $messageId <= 0) {
            return null;
        }

        $rows = $this->select_data_table_join_where(
            "SELECT m.id, m.project_id, m.nom, m.email, m.message, m.created_at,
                    p.title AS projet,
                    p.user_id AS project_owner_id,
                    COALESCE(p.admin_status, 'en_attente') AS project_admin_status
             FROM messages m
             LEFT JOIN projects p ON p.id = m.project_id
             WHERE m.id = ?
             LIMIT 1",
            [$messageId]
        );

        return $rows[0] ?? null;
    }
}
