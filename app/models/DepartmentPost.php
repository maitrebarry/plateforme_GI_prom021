<?php

class DepartmentPost extends Model
{
    private const ALLOWED_TYPES = ['annonce', 'information', 'resultat', 'evenement', 'opportunite'];
    private ?bool $archivedColumnChecked = null;
    private ?bool $likesTableChecked = null;

    public function getAllowedTypes(): array
    {
        return self::ALLOWED_TYPES;
    }

    private function hasTable(): bool
    {
        try {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'information_schema.tables', 'table_schema = DATABASE() AND table_name = ?', ['department_posts']);
            return !empty($row) && (int)($row->total ?? 0) > 0;
        } catch (Throwable $e) {
            return false;
        }
    }

    private function hasFilesTable(): bool
    {
        try {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'information_schema.tables', 'table_schema = DATABASE() AND table_name = ?', ['department_post_files']);
            return !empty($row) && (int)($row->total ?? 0) > 0;
        } catch (Throwable $e) {
            return false;
        }
    }

    private function ensureArchivedColumn(): bool
    {
        if ($this->archivedColumnChecked !== null) {
            return $this->archivedColumnChecked;
        }

        if (!$this->hasTable()) {
            $this->archivedColumnChecked = false;
            return false;
        }

        try {
            $row = $this->FetchSelectWhere(
                'COUNT(*) AS total',
                'information_schema.columns',
                'table_schema = DATABASE() AND table_name = ? AND column_name = ?',
                ['department_posts', 'is_archived']
            );

            if ((int) ($row->total ?? 0) === 0) {
                $this->insertion_update_simples(
                    "ALTER TABLE department_posts ADD COLUMN is_archived TINYINT(1) NOT NULL DEFAULT 0 AFTER created_at"
                );
            }

            $this->archivedColumnChecked = true;
            return true;
        } catch (Throwable $e) {
            $this->archivedColumnChecked = false;
            return false;
        }
    }

    private function archivedClause(string $mode = 'active'): string
    {
        if (!$this->ensureArchivedColumn()) {
            return '';
        }

        return match ($mode) {
            'archived' => 'p.is_archived = 1',
            'all' => '1=1',
            default => 'p.is_archived = 0',
        };
    }

    private function ensureLikesTable(): bool
    {
        if ($this->likesTableChecked !== null) {
            return $this->likesTableChecked;
        }

        try {
            $row = $this->FetchSelectWhere(
                'COUNT(*) AS total',
                'information_schema.tables',
                'table_schema = DATABASE() AND table_name = ?',
                ['department_post_likes']
            );

            if ((int) ($row->total ?? 0) === 0) {
                $this->insertion_update_simples(
                    "CREATE TABLE department_post_likes (
                        id INT(11) NOT NULL AUTO_INCREMENT,
                        post_id INT(11) NOT NULL,
                        user_id INT(11) NOT NULL,
                        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY (id),
                        UNIQUE KEY unique_department_post_like (post_id, user_id),
                        KEY fk_department_post_likes_post (post_id),
                        KEY fk_department_post_likes_user (user_id),
                        CONSTRAINT fk_department_post_likes_post FOREIGN KEY (post_id) REFERENCES department_posts(id) ON DELETE CASCADE,
                        CONSTRAINT fk_department_post_likes_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
                );
            }

            $this->likesTableChecked = true;
            return true;
        } catch (Throwable $e) {
            $this->likesTableChecked = false;
            return false;
        }
    }

    private function hydrateLikesForPosts(array $posts, int $currentUserId = 0): array
    {
        if (empty($posts) || !$this->ensureLikesTable()) {
            return array_map(static function ($post) {
                $post->likes_count = 0;
                $post->user_has_liked = false;
                return $post;
            }, $posts);
        }

        $postIds = array_values(array_filter(array_map(static fn($post) => (int) ($post->id ?? 0), $posts)));
        if (empty($postIds)) {
            return $posts;
        }

        $placeholders = implode(',', array_fill(0, count($postIds), '?'));
        $likeRows = $this->select_data_table_join_where(
            "SELECT post_id, user_id, COUNT(*) AS total
             FROM department_post_likes
             WHERE post_id IN ({$placeholders})
             GROUP BY post_id, user_id",
            $postIds
        );

        $counts = [];
        $likedMap = [];
        foreach ($likeRows as $row) {
            $postId = (int) ($row->post_id ?? 0);
            $counts[$postId] = ($counts[$postId] ?? 0) + 1;
            if ($currentUserId > 0 && (int) ($row->user_id ?? 0) === $currentUserId) {
                $likedMap[$postId] = true;
            }
        }

        foreach ($posts as $post) {
            $postId = (int) ($post->id ?? 0);
            $post->likes_count = (int) ($counts[$postId] ?? 0);
            $post->user_has_liked = !empty($likedMap[$postId]);
        }

        return $posts;
    }

    private function hydrateFilesForPosts(array $posts): array
    {
        if (empty($posts) || !$this->hasFilesTable()) {
            return array_map(static function ($post) {
                $post->files = [];
                return $post;
            }, $posts);
        }

        $postIds = array_values(array_filter(array_map(static fn($post) => (int)($post->id ?? 0), $posts)));
        if (empty($postIds)) {
            return $posts;
        }

        $placeholders = implode(',', array_fill(0, count($postIds), '?'));
        $files = $this->select_data_table_join_where(
            "SELECT id, post_id, original_name, stored_name, file_path, file_type, created_at
             FROM department_post_files
             WHERE post_id IN ({$placeholders})
             ORDER BY id ASC",
            $postIds
        );

        $grouped = [];
        foreach ($files as $file) {
            $grouped[(int)$file->post_id][] = $file;
        }

        foreach ($posts as $post) {
            $post->files = $grouped[(int)($post->id ?? 0)] ?? [];
        }

        return $posts;
    }

    private function enrichPosts(array $posts, int $currentUserId = 0): array
    {
        $posts = $this->hydrateFilesForPosts($posts);
        return $this->hydrateLikesForPosts($posts, $currentUserId);
    }

    public function getLatestByType(string $type, int $limit = 5): array
    {
        if (!$this->hasTable()) {
            return [];
        }

        if (!in_array($type, self::ALLOWED_TYPES, true)) {
            return [];
        }

        $posts = $this->select_data_table_join_where(
            "SELECT id, user_id, titre, contenu, type, publication_date, created_at
             FROM department_posts
             WHERE type = ?" . ($this->ensureArchivedColumn() ? " AND is_archived = 0" : "") . "
             ORDER BY publication_date DESC, created_at DESC
             LIMIT " . (int)$limit,
            [$type]
        );

        return $this->enrichPosts($posts);
    }

    public function getAllByType(string $type): array
    {
        if (!$this->hasTable() || !in_array($type, self::ALLOWED_TYPES, true)) {
            return [];
        }

        $posts = $this->select_data_table_join_where(
            "SELECT id, user_id, titre, contenu, type, publication_date, created_at
             FROM department_posts
             WHERE type = ?" . ($this->ensureArchivedColumn() ? " AND is_archived = 0" : "") . "
             ORDER BY publication_date DESC, created_at DESC",
            [$type]
        );

        return $this->enrichPosts($posts);
    }

    public function createPost(int $userId, string $title, string $content, string $type, string $publicationDate, array $files = []): int|false
    {
        if (!$this->hasTable()) {
            return false;
        }

        if (!empty($files) && !$this->hasFilesTable()) {
            return false;
        }

        if (!in_array($type, self::ALLOWED_TYPES, true)) {
            return false;
        }

        $sql = "INSERT INTO department_posts (user_id, titre, contenu, type, publication_date)
                VALUES (:user_id, :titre, :contenu, :type, :publication_date)";

        $res = $this->insertion_update_simples_insert_id($sql, [
            ':user_id' => $userId,
            ':titre' => $title,
            ':contenu' => $content,
            ':type' => $type,
            ':publication_date' => $publicationDate,
        ]);

        $postId = (int)($res['lastInsertId'] ?? 0);

        if ($postId <= 0) {
            return false;
        }

        if (!empty($files) && $this->hasFilesTable()) {
            $this->attachFiles($postId, $files);
        }

        return $postId;
    }

    public function attachFiles(int $postId, array $files): void
    {
        if (!$this->hasFilesTable() || $postId <= 0) {
            return;
        }

        $sql = "INSERT INTO department_post_files
                (post_id, original_name, stored_name, file_path, file_type)
                VALUES
                (:post_id, :original_name, :stored_name, :file_path, :file_type)";

        foreach ($files as $file) {
            $this->insertion_update_simples($sql, [
                ':post_id' => $postId,
                ':original_name' => $file['original_name'],
                ':stored_name' => $file['stored_name'],
                ':file_path' => $file['file_path'],
                ':file_type' => $file['file_type'],
            ]);
        }
    }

    public function countByType(string $type): int
    {
        if (!$this->hasTable()) {
            return 0;
        }

        if (!in_array($type, self::ALLOWED_TYPES, true)) {
            return 0;
        }

        if ($this->ensureArchivedColumn()) {
            $row = $this->select_data_table_join_where(
                'SELECT COUNT(*) AS total FROM department_posts WHERE type = ? AND is_archived = 0',
                [$type]
            )[0] ?? (object) ['total' => 0];
        } else {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'department_posts', 'type = ?', [$type]);
        }

        return (int)($row->total ?? 0);
    }

    public function getDashboardStats(): array
    {
        $stats = [
            'annonces' => 0,
            'informations' => 0,
            'evenements' => 0,
            'resultats' => 0,
            'opportunites' => 0,
            'total' => 0,
            'files' => 0,
            'archived' => 0,
        ];

        if (!$this->hasTable()) {
            return $stats;
        }

        foreach (self::ALLOWED_TYPES as $type) {
            $key = $type === 'information' ? 'informations' : ($type . 's');
            if ($type === 'resultat') {
                $key = 'resultats';
            } elseif ($type === 'evenement') {
                $key = 'evenements';
            } elseif ($type === 'opportunite') {
                $key = 'opportunites';
            }
            $stats[$key] = $this->countByType($type);
            $stats['total'] += $stats[$key];
        }

        if ($this->hasFilesTable()) {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'department_post_files', '1=1');
            $stats['files'] = (int) ($row->total ?? 0);
        }

        if ($this->ensureArchivedColumn()) {
            $row = $this->select_data_table_join_where(
                'SELECT COUNT(*) AS total FROM department_posts WHERE is_archived = 1'
            )[0] ?? (object) ['total' => 0];
            $stats['archived'] = (int) ($row->total ?? 0);
        }

        return $stats;
    }

    public function getPostsPaginated(
        string $visibility = 'active',
        string $type = 'all',
        string $search = '',
        string $dateFrom = '',
        string $dateTo = '',
        string $sortBy = 'date',
        string $sortDir = 'desc',
        int $page = 1,
        int $perPage = 10
    ): array
    {
        if (!$this->hasTable()) {
            return [
                'items' => [],
                'total' => 0,
                'page' => 1,
                'perPage' => $perPage,
                'totalPages' => 1,
            ];
        }

        $page = max(1, $page);
        $allowedPerPage = [5, 10, 20, 50];
        $perPage = in_array($perPage, $allowedPerPage, true) ? $perPage : 10;

        $clauses = [];
        $params = [];

        $archiveClause = $this->archivedClause($visibility);
        if ($archiveClause !== '') {
            $clauses[] = $archiveClause;
        }

        if ($type !== 'all' && in_array($type, self::ALLOWED_TYPES, true)) {
            $clauses[] = 'p.type = ?';
            $params[] = $type;
        }

        $search = trim($search);
        if ($search !== '') {
            $clauses[] = "(p.titre LIKE ? OR COALESCE(p.contenu, '') LIKE ? OR COALESCE(CONCAT(u.prenom, ' ', u.nom), '') LIKE ?)";
            $term = '%' . $search . '%';
            array_push($params, $term, $term, $term);
        }

        if ($dateFrom !== '') {
            $clauses[] = 'DATE(p.publication_date) >= ?';
            $params[] = $dateFrom;
        }

        if ($dateTo !== '') {
            $clauses[] = 'DATE(p.publication_date) <= ?';
            $params[] = $dateTo;
        }

        $whereSql = !empty($clauses) ? ' WHERE ' . implode(' AND ', $clauses) : '';
        $allowedSorts = [
            'date' => 'p.publication_date',
            'title' => 'p.titre',
            'type' => 'p.type',
            'author' => 'author_name',
            'created' => 'p.created_at',
        ];
        $sortColumn = $allowedSorts[$sortBy] ?? $allowedSorts['date'];
        $sortDirection = strtolower($sortDir) === 'asc' ? 'ASC' : 'DESC';

        $countRows = $this->select_data_table_join_where(
            "SELECT COUNT(*) AS total
             FROM department_posts p
             LEFT JOIN users u ON u.user_id = p.user_id
             {$whereSql}",
            $params
        );
        $total = (int) ($countRows[0]->total ?? 0);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;

        $posts = $this->select_data_table_join_where(
            "SELECT
                p.id, p.user_id, p.titre, p.contenu, p.type, p.publication_date, p.created_at, " . ($this->ensureArchivedColumn() ? "p.is_archived," : "0 AS is_archived,") . "
                COALESCE(CONCAT(u.prenom, ' ', u.nom), 'Responsable DER') AS author_name
             FROM department_posts p
             LEFT JOIN users u ON u.user_id = p.user_id
             {$whereSql}
             ORDER BY {$sortColumn} {$sortDirection}, p.created_at DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        return [
            'items' => $this->enrichPosts($posts, (int) ($_SESSION['user_id'] ?? 0)),
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
        ];
    }

    public function getPostById(int $postId)
    {
        if (!$this->hasTable() || $postId <= 0) {
            return null;
        }

        $rows = $this->select_data_table_join_where(
            "SELECT
                p.id, p.user_id, p.titre, p.contenu, p.type, p.publication_date, p.created_at, " . ($this->ensureArchivedColumn() ? "p.is_archived," : "0 AS is_archived,") . "
                COALESCE(CONCAT(u.prenom, ' ', u.nom), 'Responsable DER') AS author_name
             FROM department_posts p
             LEFT JOIN users u ON u.user_id = p.user_id
             WHERE p.id = ?
             LIMIT 1",
            [$postId]
        );

        if (empty($rows)) {
            return null;
        }

        return $this->enrichPosts($rows, (int) ($_SESSION['user_id'] ?? 0))[0] ?? null;
    }

    public function updatePost(int $postId, string $title, string $content, string $type, string $publicationDate): bool
    {
        if (!$this->hasTable() || $postId <= 0 || !in_array($type, self::ALLOWED_TYPES, true)) {
            return false;
        }

        $query = $this->insertion_update_simples(
            "UPDATE department_posts
             SET titre = ?, contenu = ?, type = ?, publication_date = ?
             WHERE id = ?",
            [$title, $content, $type, $publicationDate, $postId]
        );

        return $query->rowCount() > 0;
    }

    public function deletePost(int $postId): bool
    {
        if (!$this->hasTable() || $postId <= 0) {
            return false;
        }

        if ($this->ensureArchivedColumn()) {
            $query = $this->insertion_update_simples('UPDATE department_posts SET is_archived = 1 WHERE id = ?', [$postId]);
            return $query->rowCount() > 0;
        }

        $query = $this->insertion_update_simples('DELETE FROM department_posts WHERE id = ?', [$postId]);
        return $query->rowCount() > 0;
    }

    public function restorePost(int $postId): bool
    {
        if (!$this->hasTable() || $postId <= 0 || !$this->ensureArchivedColumn()) {
            return false;
        }

        $query = $this->insertion_update_simples('UPDATE department_posts SET is_archived = 0 WHERE id = ?', [$postId]);
        return $query->rowCount() > 0;
    }

    public function permanentlyDeletePost(int $postId): bool
    {
        if (!$this->hasTable() || $postId <= 0) {
            return false;
        }

        $query = $this->insertion_update_simples('DELETE FROM department_posts WHERE id = ?', [$postId]);
        return $query->rowCount() > 0;
    }

    public function getFileById(int $fileId)
    {
        if (!$this->hasFilesTable() || $fileId <= 0) {
            return null;
        }

        $rows = $this->select_data_table_join_where(
            "SELECT id, post_id, original_name, stored_name, file_path, file_type, created_at
             FROM department_post_files
             WHERE id = ?
             LIMIT 1",
            [$fileId]
        );

        return $rows[0] ?? null;
    }

    public function deleteFile(int $fileId): bool
    {
        if (!$this->hasFilesTable() || $fileId <= 0) {
            return false;
        }

        $query = $this->insertion_update_simples('DELETE FROM department_post_files WHERE id = ?', [$fileId]);
        return $query->rowCount() > 0;
    }

    public function getPostLikesCount(int $postId): int
    {
        if ($postId <= 0 || !$this->ensureLikesTable()) {
            return 0;
        }

        $row = $this->FetchSelectWhere('COUNT(*) AS total', 'department_post_likes', 'post_id = ?', [$postId]);
        return (int) ($row->total ?? 0);
    }

    public function hasUserLikedPost(int $postId, int $userId): bool
    {
        if ($postId <= 0 || $userId <= 0 || !$this->ensureLikesTable()) {
            return false;
        }

        return !empty($this->FetchSelectWhere('*', 'department_post_likes', 'post_id = ? AND user_id = ?', [$postId, $userId]));
    }

    public function togglePostLike(int $postId, int $userId): bool
    {
        if ($postId <= 0 || $userId <= 0 || !$this->ensureLikesTable()) {
            return false;
        }

        if ($this->hasUserLikedPost($postId, $userId)) {
            return (bool) $this->insertion_update_simples(
                'DELETE FROM department_post_likes WHERE post_id = ? AND user_id = ?',
                [$postId, $userId]
            );
        }

        return (bool) $this->insertion_update_simples(
            'INSERT INTO department_post_likes (post_id, user_id) VALUES (?, ?)',
            [$postId, $userId]
        );
    }
}
