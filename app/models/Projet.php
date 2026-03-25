<?php

class Projet extends Model
 {
    protected $table = 'projects';
    private array $tableExistsCache = [];

    private function excerpt(string $text, int $limit = 180): string
    {
        $text = trim($text);

        if ($text === '') {
            return 'Description indisponible.';
        }

        if (function_exists('mb_strimwidth')) {
            return mb_strimwidth($text, 0, $limit, '...');
        }

        return strlen($text) > $limit ? substr($text, 0, $limit - 3) . '...' : $text;
    }

    private function tableExists(string $table): bool
    {
        if (array_key_exists($table, $this->tableExistsCache)) {
            return $this->tableExistsCache[$table];
        }

        try {
            $row = $this->FetchSelectWhere(
                'COUNT(*) AS total',
                'information_schema.tables',
                'table_schema = DATABASE() AND table_name = ?',
                [$table]
            );

            return $this->tableExistsCache[$table] = (!empty($row) && (int)($row->total ?? 0) > 0);
        } catch (Throwable $e) {
            return $this->tableExistsCache[$table] = false;
        }
    }

    private function attachImagesToProjects(array $rows): array
    {
        if (empty($rows) || !$this->tableExists('project_images')) {
            return array_map(function ($row) {
                $row->images = [];
                return $row;
            }, $rows);
        }

        $projectIds = array_values(array_filter(array_map(static fn($row) => (int) ($row->id ?? 0), $rows)));
        if (empty($projectIds)) {
            return $rows;
        }

        $placeholders = implode(',', array_fill(0, count($projectIds), '?'));
        $images = $this->select_data_table_join_where(
            "SELECT id, project_id, image
             FROM project_images
             WHERE project_id IN ({$placeholders})
             ORDER BY id ASC",
            $projectIds
        );

        $grouped = [];
        foreach ($images as $image) {
            $grouped[(int) ($image->project_id ?? 0)][] = $image;
        }

        foreach ($rows as $row) {
            $row->images = $grouped[(int) ($row->id ?? 0)] ?? [];
        }

        return $rows;
    }

    private function buildProjectRowsQuery(?string $search = null, ?int $categoryId = null): array
    {
        $hasCategories = $this->tableExists('categories');
        $hasUsers = $this->tableExists('users');

        $sql = "FROM projects p";

        if ($hasCategories) {
            $sql .= " LEFT JOIN categories c ON c.id = p.category_id";
        }

        if ($hasUsers) {
            $sql .= " LEFT JOIN users u ON u.user_id = p.user_id";
        }

        $sql .= " WHERE 1 = 1";
        $params = [];

        if ($categoryId !== null && $categoryId > 0) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }

        if ($search !== null && $search !== '') {
            $searchClauses = [
                'p.title LIKE ?',
                'p.description LIKE ?',
                'p.technologies LIKE ?',
            ];

            if ($hasCategories) {
                $searchClauses[] = "COALESCE(c.nom, '') LIKE ?";
            }

            if ($hasUsers) {
                $searchClauses[] = "COALESCE(CONCAT(u.prenom, ' ', u.nom), '') LIKE ?";
            }

            $sql .= " AND (" . implode(' OR ', $searchClauses) . ")";
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;

            if ($hasCategories) {
                $params[] = $searchTerm;
            }

            if ($hasUsers) {
                $params[] = $searchTerm;
            }
        }

        return [
            'sql' => $sql,
            'params' => $params,
            'hasCategories' => $hasCategories,
            'hasUsers' => $hasUsers,
        ];
    }

    private function normalizeProjectRows(array $rows): array
    {
        $rows = $this->attachImagesToProjects($rows);

        return array_map(function ($row) {
            $images = [];

            foreach (($row->images ?? []) as $imageRow) {
                $images[] = ROOT_IMG . '/uploads/projects/images/' . rawurlencode((string) ($imageRow->image ?? ''));
            }

            if (empty($images) && !empty($row->cover_image)) {
                $images[] = ROOT_IMG . '/uploads/projects/images/' . rawurlencode((string) $row->cover_image);
            }

            if (empty($images)) {
                $images[] = ROOT . '/assets/images/thumbs/product-img1.png';
            }

            $description = trim((string) ($row->description ?? ''));
            $technologies = array_values(array_filter(array_map('trim', explode(',', (string) ($row->technologies ?? '')))));

            return [
                'id' => (int) ($row->id ?? 0),
                'title' => (string) ($row->title ?? 'Projet sans titre'),
                'description' => $description,
                'excerpt' => $this->excerpt($description, 180),
                'category' => (string) ($row->category_name ?? 'Sans categorie'),
                'category_id' => (int) ($row->category_id ?? 0),
                'author' => trim((string) ($row->author_name ?? 'Etudiant GI')),
                'image' => $images[0],
                'images' => $images,
                'date' => !empty($row->created_at) ? date('d/m/Y', strtotime((string) $row->created_at)) : '',
                'created_at' => (string) ($row->created_at ?? ''),
                'status' => (string) ($row->status ?? ''),
                'technologies' => $technologies,
                'video' => (string) ($row->video ?? ''),
                'likes_count' => (int) ($row->likes_count ?? 0),
                'reviews_count' => (int) ($row->reviews_count ?? 0),
                'average_rating' => (float) ($row->average_rating ?? 0),
            ];
        }, $rows);
    }

    public function getAvailableCategories(): array
    {
        if (!$this->tableExists('categories')) {
            return [];
        }

        if (!$this->tableExists('projects')) {
            return $this->select_data_table_join_where(
                "SELECT c.id, c.nom, c.description, 0 AS total_projects
                 FROM categories c
                 ORDER BY c.nom ASC"
            );
        }

        return $this->select_data_table_join_where(
            "SELECT c.id, c.nom, c.description, COUNT(p.id) AS total_projects
             FROM categories c
             LEFT JOIN projects p ON p.category_id = c.id
             GROUP BY c.id, c.nom, c.description
             ORDER BY c.nom ASC"
        );
    }

    public function getHomepageProjects(?string $search = null, ?int $categoryId = null, int $limit = 12): array
    {
        return $this->getHomepageProjectsPaginated($search, $categoryId, 1, $limit)['projects'];
    }

    public function getHomepageProjectsPaginated(?string $search = null, ?int $categoryId = null, int $page = 1, int $perPage = 5): array
    {
        if (!$this->tableExists('projects')) {
            return [
                'projects' => [],
                'total' => 0,
                'page' => 1,
                'perPage' => $perPage,
                'totalPages' => 1,
            ];
        }

        $hasProjectImages = $this->tableExists('project_images');
        $page = max(1, $page);
        $allowedPerPage = [5, 10, 15, 20];
        $perPage = in_array($perPage, $allowedPerPage, true) ? $perPage : 5;
        $queryParts = $this->buildProjectRowsQuery($search, $categoryId);
        $totalRow = $this->select_data_table_join_where(
            "SELECT COUNT(*) AS total " . $queryParts['sql'],
            $queryParts['params']
        );
        $total = (int) ($totalRow[0]->total ?? 0);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT
                    p.id,
                    p.title,
                    p.description,
                    p.technologies,
                    p.video,
                    p.status,
                    p.created_at,
                    p.category_id,
                    " . ($queryParts['hasCategories'] ? "COALESCE(c.nom, 'Sans categorie')" : "'Sans categorie'") . " AS category_name,
                    " . ($queryParts['hasUsers'] ? "COALESCE(CONCAT(u.prenom, ' ', u.nom), 'Etudiant GI')" : "'Etudiant GI'") . " AS author_name,
                    " . ($this->tableExists('project_likes')
                        ? "(SELECT COUNT(*) FROM project_likes WHERE project_id = p.id)"
                        : "0") . " AS likes_count,
                    " . ($this->tableExists('project_reviews')
                        ? "(SELECT COUNT(*) FROM project_reviews WHERE project_id = p.id)"
                        : "0") . " AS reviews_count,
                    " . ($this->tableExists('project_reviews')
                        ? "(SELECT COALESCE(ROUND(AVG(rating), 1), 0) FROM project_reviews WHERE project_id = p.id)"
                        : "0") . " AS average_rating,
                    " . ($hasProjectImages
                        ? "(SELECT image FROM project_images WHERE project_id = p.id ORDER BY id ASC LIMIT 1)"
                        : "NULL") . " AS cover_image
                " . $queryParts['sql'] . "
                ORDER BY p.created_at DESC
                LIMIT " . (int) $perPage . " OFFSET " . (int) $offset;

        $rows = $this->select_data_table_join_where($sql, $queryParts['params']);

        return [
            'projects' => $this->normalizeProjectRows($rows),
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
        ];
    }

    public function countProjects(?string $search = null, ?int $categoryId = null): int
    {
        if (!$this->tableExists('projects')) {
            return 0;
        }

        $hasCategories = $this->tableExists('categories');
        $hasUsers = $this->tableExists('users');

        $sql = "SELECT COUNT(*) AS total FROM projects p";

        if ($hasCategories) {
            $sql .= " LEFT JOIN categories c ON c.id = p.category_id";
        }

        if ($hasUsers) {
            $sql .= " LEFT JOIN users u ON u.user_id = p.user_id";
        }

        $sql .= " WHERE 1 = 1";

        $params = [];

        if ($categoryId !== null && $categoryId > 0) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }

        if ($search !== null && $search !== '') {
            $searchClauses = [
                'p.title LIKE ?',
                'p.description LIKE ?',
                'p.technologies LIKE ?',
            ];

            if ($hasCategories) {
                $searchClauses[] = "COALESCE(c.nom, '') LIKE ?";
            }

            if ($hasUsers) {
                $searchClauses[] = "COALESCE(CONCAT(u.prenom, ' ', u.nom), '') LIKE ?";
            }

            $sql .= " AND (" . implode(' OR ', $searchClauses) . ")";
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;

            if ($hasCategories) {
                $params[] = $searchTerm;
            }

            if ($hasUsers) {
                $params[] = $searchTerm;
            }
        }

        $row = $this->select_data_table_join_where($sql, $params);

        return (int) (($row[0]->total ?? 0));
    }

    public function createProject( $data ) {
        $sql = "INSERT INTO projects 
        (user_id, category_id, title, description, technologies, video, status) 
        VALUES (?,?,?,?,?,?,?)";

        return $this->insertion_update_simples_insert_id( $sql, [
            $data[ 'user_id' ],
            $data[ 'category_id' ],
            $data[ 'title' ],
            $data[ 'description' ],
            $data[ 'technologies' ],
            $data[ 'video' ],
            $data[ 'status' ]
        ] );
    }

    public function addImage( $project_id, $image ) {
        $sql = 'INSERT INTO project_images (project_id,image) VALUES (?,?)';
        return $this->insertion_update_simples( $sql, [ $project_id, $image ] );
    }

    public function addFile( $project_id, $file ) {
        $sql = 'INSERT INTO project_files (project_id,fichier) VALUES (?,?)';
        return $this->insertion_update_simples( $sql, [ $project_id, $file ] );
    }

    public function getProjectsByUser( $user_id ) {

        $sql = "SELECT 
            p.id,
            p.title,
            p.description,
            p.technologies,
            p.status,
            p.created_at,
            c.nom as categorie,
            (
            SELECT image 
            FROM project_images 
            WHERE project_id = p.id 
            LIMIT 1
            ) as image

            FROM projects p

            LEFT JOIN categories c ON c.id = p.category_id

            WHERE p.user_id = ?

            ORDER BY p.created_at DESC";

        return $this->select_data_table_join_where( $sql, [ $user_id ] );

    }

    public function getStudentDashboardOverview(int $userId, int $limit = 4): array
    {
        $projects = [];
        if ($this->tableExists('projects') && $userId > 0) {
            $projects = $this->normalizeStudentProjects($this->getProjectsByUser($userId));
        }

        $counts = [
            'mesProjets' => count($projects),
            'enAttente' => 0,
            'valides' => 0,
            'messages' => 0,
            'likes' => 0,
            'reviews' => 0,
        ];

        foreach ($projects as $project) {
            $status = strtolower((string) ($project['status'] ?? ''));
            if (str_contains($status, 'attente')) {
                $counts['enAttente']++;
            }
            if (str_contains($status, 'valid') || str_contains($status, 'publ') || str_contains($status, 'accept')) {
                $counts['valides']++;
            }
            $counts['likes'] += (int) ($project['likes_count'] ?? 0);
            $counts['reviews'] += (int) ($project['reviews_count'] ?? 0);
        }

        if ($this->tableExists('project_messages') && $userId > 0) {
            $row = $this->select_data_table_join_where(
                "SELECT COUNT(*) AS total FROM project_messages WHERE receiver_id = ?",
                [$userId]
            );
            $counts['messages'] = (int) ($row[0]->total ?? 0);
        }

        $recentProjects = array_slice($projects, 0, $limit);
        $latestProject = $recentProjects[0] ?? null;

        return [
            'stats' => $counts,
            'recentProjects' => $recentProjects,
            'latestProject' => $latestProject,
            'completionRate' => $counts['mesProjets'] > 0
                ? (int) round(($counts['valides'] / max(1, $counts['mesProjets'])) * 100)
                : 0,
        ];
    }

    private function normalizeStudentProjects(array $rows): array
    {
        $projects = [];

        foreach ($rows as $row) {
            $projectId = (int) ($row->id ?? 0);
            $reviewSummary = $projectId > 0 ? $this->getProjectReviewSummary($projectId) : null;
            $likesCount = $projectId > 0 ? $this->getProjectLikesCount($projectId) : 0;

            $projects[] = [
                'id' => $projectId,
                'title' => (string) ($row->title ?? 'Projet sans titre'),
                'description' => (string) ($row->description ?? ''),
                'excerpt' => $this->excerpt((string) ($row->description ?? ''), 120),
                'technologies' => array_values(array_filter(array_map('trim', explode(',', (string) ($row->technologies ?? ''))))),
                'status' => (string) ($row->status ?? 'En attente'),
                'category' => (string) ($row->categorie ?? 'Sans categorie'),
                'created_at' => (string) ($row->created_at ?? ''),
                'date' => !empty($row->created_at) ? date('d/m/Y', strtotime((string) $row->created_at)) : '',
                'image' => !empty($row->image)
                    ? ROOT_IMG . '/uploads/projects/images/' . rawurlencode((string) $row->image)
                    : ROOT . '/assets/images/thumbs/product-img1.png',
                'likes_count' => (int) $likesCount,
                'reviews_count' => (int) ($reviewSummary->total_reviews ?? 0),
                'average_rating' => (float) ($reviewSummary->average_rating ?? 0),
            ];
        }

        return $projects;
    }

    private function buildContactActions(?string $email, ?string $contact): array
    {
        $email = trim((string) $email);
        $contact = trim((string) $contact);
        $digits = preg_replace('/\D+/', '', $contact);

        $international = $digits;
        if ($international !== '' && str_starts_with($contact, '+')) {
            $international = ltrim($international, '0');
        } elseif (strlen($international) === 8) {
            $international = '223' . $international;
        }

        return [
            'email' => $email,
            'contact' => $contact,
            'mailto_url' => $email !== '' ? 'mailto:' . rawurlencode($email) : '',
            'tel_url' => $international !== '' ? 'tel:+' . $international : '',
            'whatsapp_url' => $international !== '' ? 'https://wa.me/' . $international : '',
        ];
    }

    public function getStudentVisitorReviews(int $ownerId, int $limit = 6): array
    {
        if ($ownerId <= 0 || !$this->tableExists('projects') || !$this->tableExists('project_reviews')) {
            return [];
        }

        $limit = max(1, min(12, $limit));
        $rows = $this->select_data_table_join_where(
            "SELECT pr.id, pr.rating, pr.review, pr.created_at,
                    p.id AS project_id, p.title AS project_title,
                    u.user_id AS visitor_id, u.prenom, u.nom, u.email, u.contact, u.image
             FROM project_reviews pr
             INNER JOIN projects p ON p.id = pr.project_id
             LEFT JOIN users u ON u.user_id = pr.user_id
             WHERE p.user_id = ?
             ORDER BY pr.updated_at DESC, pr.created_at DESC
             LIMIT {$limit}",
            [$ownerId]
        );

        return array_map(function ($row) {
            $contacts = $this->buildContactActions($row->email ?? '', $row->contact ?? '');

            return [
                'id' => (int) ($row->id ?? 0),
                'project_id' => (int) ($row->project_id ?? 0),
                'project_title' => (string) ($row->project_title ?? 'Projet'),
                'visitor_name' => trim((string) (($row->prenom ?? '') . ' ' . ($row->nom ?? ''))) ?: 'Visiteur',
                'visitor_image' => !empty($row->image)
                    ? ROOT_IMG . '/' . ltrim((string) $row->image, '/')
                    : '',
                'rating' => (int) ($row->rating ?? 0),
                'review' => (string) ($row->review ?? ''),
                'created_at' => (string) ($row->created_at ?? ''),
                'date' => !empty($row->created_at) ? date('d/m/Y H:i', strtotime((string) $row->created_at)) : '',
                'email' => $contacts['email'],
                'contact' => $contacts['contact'],
                'mailto_url' => $contacts['mailto_url'],
                'tel_url' => $contacts['tel_url'],
                'whatsapp_url' => $contacts['whatsapp_url'],
            ];
        }, $rows);
    }

    public function getStudentMessageThreads(
        int $ownerId,
        int $limit = 12,
        ?int $projectId = null,
        string $search = '',
        string $status = 'all'
    ): array
    {
        if (
            $ownerId <= 0
            || !$this->tableExists('projects')
            || !$this->tableExists('project_messages')
            || !$this->tableExists('users')
        ) {
            return [];
        }

        $limit = max(1, min(24, $limit));
        $search = trim($search);
        $rows = $this->select_data_table_join_where(
            "SELECT pm.id, pm.project_id, pm.sender_id, pm.receiver_id, pm.message, pm.is_read, pm.created_at,
                    p.title AS project_title,
                    su.user_id AS sender_user_id, su.prenom AS sender_prenom, su.nom AS sender_nom, su.email AS sender_email, su.contact AS sender_contact, su.image AS sender_image,
                    ru.user_id AS receiver_user_id, ru.prenom AS receiver_prenom, ru.nom AS receiver_nom, ru.email AS receiver_email, ru.contact AS receiver_contact, ru.image AS receiver_image
             FROM project_messages pm
             INNER JOIN projects p ON p.id = pm.project_id
             LEFT JOIN users su ON su.user_id = pm.sender_id
             LEFT JOIN users ru ON ru.user_id = pm.receiver_id
             WHERE p.user_id = ?
             ORDER BY pm.created_at DESC, pm.id DESC",
            [$ownerId]
        );

        $threads = [];
        foreach ($rows as $row) {
            $visitorId = (int) (($row->sender_id ?? 0) === $ownerId ? ($row->receiver_id ?? 0) : ($row->sender_id ?? 0));
            $projectId = (int) ($row->project_id ?? 0);
            $key = $projectId . ':' . $visitorId;

            $isSenderVisitor = (int) ($row->sender_id ?? 0) !== $ownerId;
            $visitorName = trim((string) (
                $isSenderVisitor
                    ? (($row->sender_prenom ?? '') . ' ' . ($row->sender_nom ?? ''))
                    : (($row->receiver_prenom ?? '') . ' ' . ($row->receiver_nom ?? ''))
            ));
            $visitorImage = $isSenderVisitor ? ($row->sender_image ?? '') : ($row->receiver_image ?? '');
            $visitorEmail = $isSenderVisitor ? ($row->sender_email ?? '') : ($row->receiver_email ?? '');
            $visitorContact = $isSenderVisitor ? ($row->sender_contact ?? '') : ($row->receiver_contact ?? '');
            $contacts = $this->buildContactActions($visitorEmail, $visitorContact);

            if (!isset($threads[$key])) {
                $threads[$key] = [
                    'project_id' => $projectId,
                    'project_title' => (string) ($row->project_title ?? 'Projet'),
                    'visitor_id' => $visitorId,
                    'visitor_name' => $visitorName !== '' ? $visitorName : 'Visiteur',
                    'visitor_image' => !empty($visitorImage) ? ROOT_IMG . '/' . ltrim((string) $visitorImage, '/') : '',
                    'email' => $contacts['email'],
                    'contact' => $contacts['contact'],
                    'mailto_url' => $contacts['mailto_url'],
                    'tel_url' => $contacts['tel_url'],
                    'whatsapp_url' => $contacts['whatsapp_url'],
                    'last_message' => (string) ($row->message ?? ''),
                    'last_date' => !empty($row->created_at) ? date('d/m/Y H:i', strtotime((string) $row->created_at)) : '',
                    'last_direction' => (int) ($row->sender_id ?? 0) === $ownerId ? 'sent' : 'received',
                    'unread_count' => 0,
                    'is_unread' => false,
                    'messages_count' => 0,
                    'messages_preview' => [],
                ];
            }

            $threads[$key]['messages_count']++;
            if ((int) ($row->receiver_id ?? 0) === $ownerId && (int) ($row->is_read ?? 0) === 0) {
                $threads[$key]['unread_count']++;
                $threads[$key]['is_unread'] = true;
            }
            if (count($threads[$key]['messages_preview']) < 3) {
                $threads[$key]['messages_preview'][] = [
                    'message' => (string) ($row->message ?? ''),
                    'date' => !empty($row->created_at) ? date('d/m/Y H:i', strtotime((string) $row->created_at)) : '',
                    'direction' => (int) ($row->sender_id ?? 0) === $ownerId ? 'sent' : 'received',
                    'is_read' => (int) ($row->is_read ?? 0) === 1,
                ];
            }
        }

        $threads = array_values($threads);

        if ($projectId !== null && $projectId > 0) {
            $threads = array_values(array_filter($threads, static fn(array $thread): bool => (int) ($thread['project_id'] ?? 0) === $projectId));
        }

        if ($search !== '') {
            $needle = mb_strtolower($search);
            $threads = array_values(array_filter($threads, static function (array $thread) use ($needle): bool {
                $haystack = mb_strtolower(implode(' ', [
                    $thread['visitor_name'] ?? '',
                    $thread['project_title'] ?? '',
                    $thread['last_message'] ?? '',
                    $thread['email'] ?? '',
                    $thread['contact'] ?? '',
                ]));

                return str_contains($haystack, $needle);
            }));
        }

        if ($status === 'unread') {
            $threads = array_values(array_filter($threads, static fn(array $thread): bool => !empty($thread['is_unread'])));
        } elseif ($status === 'read') {
            $threads = array_values(array_filter($threads, static fn(array $thread): bool => empty($thread['is_unread'])));
        }

        return array_slice($threads, 0, $limit);
    }

    public function markThreadMessagesAsRead(int $ownerId, int $projectId, int $visitorId): bool
    {
        if (
            $ownerId <= 0
            || $projectId <= 0
            || $visitorId <= 0
            || !$this->tableExists('project_messages')
        ) {
            return false;
        }

        return (bool) $this->insertion_update_simples(
            "UPDATE project_messages
             SET is_read = 1
             WHERE project_id = ?
               AND sender_id = ?
               AND receiver_id = ?
               AND is_read = 0",
            [$projectId, $visitorId, $ownerId]
        );
    }

    public function getStudentUnreadMessagesCount(int $ownerId): int
    {
        if (
            $ownerId <= 0
            || !$this->tableExists('projects')
            || !$this->tableExists('project_messages')
        ) {
            return 0;
        }

        $row = $this->select_data_table_join_where(
            "SELECT COUNT(*) AS total
             FROM project_messages pm
             INNER JOIN projects p ON p.id = pm.project_id
             WHERE p.user_id = ?
               AND pm.receiver_id = ?
               AND pm.is_read = 0",
            [$ownerId, $ownerId]
        );

        return (int) ($row[0]->total ?? 0);
    }

    public function getProjectById( $id ) {

        return $this->FetchSelectWhere( '*', 'projects', 'id=?', [ $id ] );

    }

    /* récupérer images projet */

    public function getProjectImages( $project_id ) {

        return $this->FetchSelectWhere2( '*', 'project_images', 'project_id=?', [ $project_id ] );

    }

    public function updateProject( $data, $id ) {

        $sql = "UPDATE projects SET
        title=?,
        category_id=?,
        description=?,
        technologies=?,
        video=?
        WHERE id=?";

        return $this->insertion_update_simples( $sql, [

            $data[ 'title' ],
            $data[ 'category_id' ],
            $data[ 'description' ],
            $data[ 'technologies' ],
            $data[ 'video' ],
            $id

        ] );

    }

    /* supprimer image */

    public function deleteImage( $id ) {

        $sql = 'DELETE FROM project_images WHERE id=?';

        return $this->insertion_update_simples( $sql, [ $id ] );

    }

    /* récupérer fichiers */

    public function getProjectFiles( $project_id ) {

        return $this->FetchSelectWhere2( '*', 'project_files', 'project_id=?', [ $project_id ] );

    }

    /* supprimer fichier */

    public function deleteFile( $id ) {

        $sql = 'DELETE FROM project_files WHERE id=?';

        return $this->insertion_update_simples( $sql, [ $id ] );

    }

    /* récupérer projet complet */

    public function getProjectDetail( $id ) {

        $sql = "SELECT 
        p.*,
        c.nom as categorie,
        u.nom,
        u.prenom

        FROM projects p

        LEFT JOIN categories c ON c.id = p.category_id
        LEFT JOIN users u ON u.user_id = p.user_id

        WHERE p.id=?";

        return $this->select_data_table_join_where( $sql, [ $id ] )[ 0 ];

    }

    public function getProjectDetailEnhanced(int $id)
    {
        if (!$this->tableExists('projects')) {
            return null;
        }

        $sql = "SELECT
                    p.*,
                    c.nom AS categorie,
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
                LIMIT 1";

        return $this->select_data_table_join_where($sql, [$id])[0] ?? null;
    }

    public function getProjectReviewSummary(int $projectId): object
    {
        if (!$this->tableExists('project_reviews')) {
            return (object) ['average_rating' => 0, 'total_reviews' => 0];
        }

        return $this->FetchSelectWhere(
            'COALESCE(ROUND(AVG(rating), 1), 0) AS average_rating, COUNT(*) AS total_reviews',
            'project_reviews',
            'project_id = ?',
            [$projectId]
        ) ?: (object) ['average_rating' => 0, 'total_reviews' => 0];
    }

    public function getProjectReviews(int $projectId): array
    {
        if (!$this->tableExists('project_reviews')) {
            return [];
        }

        return $this->select_data_table_join_where(
            "SELECT pr.id, pr.rating, pr.review, pr.created_at,
                    u.user_id, u.nom, u.prenom, u.image
             FROM project_reviews pr
             LEFT JOIN users u ON u.user_id = pr.user_id
             WHERE pr.project_id = ?
             ORDER BY pr.updated_at DESC, pr.created_at DESC",
            [$projectId]
        );
    }

    public function saveProjectReview(int $projectId, int $userId, int $rating, string $review = ''): bool
    {
        if (!$this->tableExists('project_reviews') || $rating < 1 || $rating > 5) {
            return false;
        }

        $exists = $this->FetchSelectWhere('*', 'project_reviews', 'project_id = ? AND user_id = ?', [$projectId, $userId]);

        if (!empty($exists)) {
            return (bool) $this->insertion_update_simples(
                "UPDATE project_reviews
                 SET rating = ?, review = ?, updated_at = CURRENT_TIMESTAMP
                 WHERE project_id = ? AND user_id = ?",
                [$rating, $review, $projectId, $userId]
            );
        }

        return (bool) $this->insertion_update_simples(
            "INSERT INTO project_reviews (project_id, user_id, rating, review)
             VALUES (?, ?, ?, ?)",
            [$projectId, $userId, $rating, $review]
        );
    }

    public function getProjectLikesCount(int $projectId): int
    {
        if (!$this->tableExists('project_likes')) {
            return 0;
        }

        $row = $this->FetchSelectWhere('COUNT(*) AS total', 'project_likes', 'project_id = ?', [$projectId]);
        return (int) ($row->total ?? 0);
    }

    public function hasUserLikedProject(int $projectId, int $userId): bool
    {
        if (!$this->tableExists('project_likes')) {
            return false;
        }

        return !empty($this->FetchSelectWhere('*', 'project_likes', 'project_id = ? AND user_id = ?', [$projectId, $userId]));
    }

    public function toggleProjectLike(int $projectId, int $userId): bool
    {
        if (!$this->tableExists('project_likes')) {
            return false;
        }

        if ($this->hasUserLikedProject($projectId, $userId)) {
            return (bool) $this->insertion_update_simples(
                'DELETE FROM project_likes WHERE project_id = ? AND user_id = ?',
                [$projectId, $userId]
            );
        }

        return (bool) $this->insertion_update_simples(
            'INSERT INTO project_likes (project_id, user_id) VALUES (?, ?)',
            [$projectId, $userId]
        );
    }

    public function getConversationForProject(int $projectId, int $userA, int $userB): array
    {
        if (!$this->tableExists('project_messages') || $userA <= 0 || $userB <= 0) {
            return [];
        }

        return $this->select_data_table_join_where(
            "SELECT pm.*, su.nom AS sender_nom, su.prenom AS sender_prenom, su.image AS sender_image
             FROM project_messages pm
             LEFT JOIN users su ON su.user_id = pm.sender_id
             WHERE pm.project_id = ?
               AND ((pm.sender_id = ? AND pm.receiver_id = ?) OR (pm.sender_id = ? AND pm.receiver_id = ?))
             ORDER BY pm.created_at ASC",
            [$projectId, $userA, $userB, $userB, $userA]
        );
    }

    public function sendProjectMessage(int $projectId, int $senderId, int $receiverId, string $message): bool
    {
        if (!$this->tableExists('project_messages') || trim($message) === '' || $senderId <= 0 || $receiverId <= 0) {
            return false;
        }

        return (bool) $this->insertion_update_simples(
            'INSERT INTO project_messages (project_id, sender_id, receiver_id, message) VALUES (?, ?, ?, ?)',
            [$projectId, $senderId, $receiverId, trim($message)]
        );
    }

    public function getTopLikedProjects(int $limit = 3): array
    {
        if (!$this->tableExists('projects')) {
            return [];
        }

        $limit = max(1, $limit);
        $hasCategories = $this->tableExists('categories');
        $hasUsers = $this->tableExists('users');
        $hasLikes = $this->tableExists('project_likes');
        $hasReviews = $this->tableExists('project_reviews');
        $hasImages = $this->tableExists('project_images');
        $groupBy = ['p.id', 'p.title', 'p.description', 'p.technologies', 'p.created_at'];
        if ($hasCategories) {
            $groupBy[] = 'c.nom';
        }
        if ($hasUsers) {
            $groupBy[] = 'u.prenom';
            $groupBy[] = 'u.nom';
        }

        $rows = $this->select_data_table_join_where(
            "SELECT
                p.id,
                p.title,
                p.description,
                p.technologies,
                p.created_at,
                " . ($hasCategories ? "COALESCE(c.nom, 'Sans categorie')" : "'Sans categorie'") . " AS category_name,
                " . ($hasUsers ? "COALESCE(CONCAT(u.prenom, ' ', u.nom), 'Etudiant GI')" : "'Etudiant GI'") . " AS author_name,
                " . ($hasLikes ? "COUNT(DISTINCT pl.id)" : "0") . " AS likes_count,
                " . ($hasReviews ? "COUNT(DISTINCT pr.id)" : "0") . " AS reviews_count,
                " . ($hasReviews ? "COALESCE(ROUND(AVG(pr.rating), 1), 0)" : "0") . " AS average_rating,
                " . ($hasImages ? "(SELECT image FROM project_images WHERE project_id = p.id ORDER BY id ASC LIMIT 1)" : "NULL") . " AS cover_image
             FROM projects p
             " . ($hasCategories ? "LEFT JOIN categories c ON c.id = p.category_id" : "") . "
             " . ($hasUsers ? "LEFT JOIN users u ON u.user_id = p.user_id" : "") . "
             " . ($hasLikes ? "LEFT JOIN project_likes pl ON pl.project_id = p.id" : "") . "
             " . ($hasReviews ? "LEFT JOIN project_reviews pr ON pr.project_id = p.id" : "") . "
             GROUP BY " . implode(', ', $groupBy) . "
             ORDER BY likes_count DESC, average_rating DESC, p.created_at DESC
             LIMIT {$limit}"
        );

        return $this->normalizeProjectRows($rows);
    }

    public function getGuidedProjectRecommendations(string $query, int $limit = 3): array
    {
        $query = trim($query);
        if ($query === '') {
            return [];
        }

        $projects = $this->getHomepageProjects(null, null, 60);
        $tokens = array_values(array_filter(preg_split('/[\s,;:.!?]+/u', mb_strtolower($query)), static function ($token) {
            return mb_strlen($token) >= 3;
        }));

        foreach ($projects as &$project) {
            $haystack = mb_strtolower(
                implode(' ', [
                    $project['title'] ?? '',
                    $project['description'] ?? '',
                    $project['category'] ?? '',
                    implode(' ', $project['technologies'] ?? []),
                    $project['author'] ?? '',
                ])
            );

            $score = 0;
            $matched = [];
            foreach ($tokens as $token) {
                if (str_contains($haystack, $token)) {
                    $score += 2;
                    $matched[] = $token;
                }
            }

            if (!empty($project['technologies'])) {
                $score += min(2, count($project['technologies']) / 2);
            }

            $project['ai_score'] = $score;
            $project['ai_reason'] = !empty($matched)
                ? 'Correspond a : ' . implode(', ', array_slice(array_unique($matched), 0, 4))
                : 'Projet recommande pour sa richesse technique et sa presentation.';
        }
        unset($project);

        usort($projects, static function (array $left, array $right): int {
            return ($right['ai_score'] <=> $left['ai_score']) ?: strcmp((string) ($right['created_at'] ?? ''), (string) ($left['created_at'] ?? ''));
        });

        return array_values(array_filter(array_slice($projects, 0, $limit), static fn($project) => ($project['ai_score'] ?? 0) > 0));
    }

    public function getPresentationStats(): array
    {
        $stats = [
            'projects' => $this->tableExists('projects') ? $this->countProjects() : 0,
            'categories' => count($this->getAvailableCategories()),
            'likes' => 0,
            'reviews' => 0,
            'messages' => 0,
            'owners' => 0,
            'average_rating' => 0.0,
        ];

        if ($this->tableExists('project_likes')) {
            $row = $this->select_data_table_join_where("SELECT COUNT(*) AS total FROM project_likes");
            $stats['likes'] = (int) ($row[0]->total ?? 0);
        }

        if ($this->tableExists('project_reviews')) {
            $row = $this->select_data_table_join_where(
                "SELECT COUNT(*) AS total, COALESCE(ROUND(AVG(rating), 1), 0) AS average_rating FROM project_reviews"
            );
            $stats['reviews'] = (int) ($row[0]->total ?? 0);
            $stats['average_rating'] = (float) ($row[0]->average_rating ?? 0);
        }

        if ($this->tableExists('project_messages')) {
            $row = $this->select_data_table_join_where("SELECT COUNT(*) AS total FROM project_messages");
            $stats['messages'] = (int) ($row[0]->total ?? 0);
        }

        if ($this->tableExists('projects')) {
            $row = $this->select_data_table_join_where("SELECT COUNT(DISTINCT user_id) AS total FROM projects");
            $stats['owners'] = (int) ($row[0]->total ?? 0);
        }

        return $stats;
    }

}
