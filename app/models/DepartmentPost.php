<?php

class DepartmentPost extends Model
{
    private const ALLOWED_TYPES = ['annonce', 'information', 'resultat', 'evenement', 'opportunite'];

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
             WHERE type = ?
             ORDER BY publication_date DESC, created_at DESC
             LIMIT " . (int)$limit,
            [$type]
        );

        return $this->hydrateFilesForPosts($posts);
    }

    public function getAllByType(string $type): array
    {
        if (!$this->hasTable() || !in_array($type, self::ALLOWED_TYPES, true)) {
            return [];
        }

        $posts = $this->select_data_table_join_where(
            "SELECT id, user_id, titre, contenu, type, publication_date, created_at
             FROM department_posts
             WHERE type = ?
             ORDER BY publication_date DESC, created_at DESC",
            [$type]
        );

        return $this->hydrateFilesForPosts($posts);
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

        $row = $this->FetchSelectWhere('COUNT(*) AS total', 'department_posts', 'type = ?', [$type]);
        return (int)($row->total ?? 0);
    }
}
