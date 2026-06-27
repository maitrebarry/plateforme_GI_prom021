<?php

/**
 * Notifications utilisateur (in-app).
 *
 * Table auto-creee au besoin (meme logique defensive que AdminPanel::ensureAdminStatusColumn).
 * Une notification = destinataire (user_id) + type + titre + message + lien interne + etat lu.
 */
class Notification extends Model
{
    private static bool $tableEnsured = false;

    private function ensureTable(): void
    {
        if (self::$tableEnsured) {
            return;
        }

        $this->insertion_update_simples(
            "CREATE TABLE IF NOT EXISTS notifications (
                id INT(11) NOT NULL AUTO_INCREMENT,
                user_id INT(11) NOT NULL,
                type VARCHAR(50) NOT NULL DEFAULT 'info',
                title VARCHAR(200) NOT NULL,
                message TEXT DEFAULT NULL,
                link VARCHAR(255) DEFAULT NULL,
                is_read TINYINT(1) NOT NULL DEFAULT 0,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY idx_notif_user (user_id),
                KEY idx_notif_read (user_id, is_read)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );

        self::$tableEnsured = true;
    }

    /** Cree une notification pour un utilisateur. */
    public function create(int $userId, string $type, string $title, string $message = '', string $link = ''): bool
    {
        if ($userId <= 0 || trim($title) === '') {
            return false;
        }

        $this->ensureTable();

        try {
            $query = $this->insertion_update_simples(
                'INSERT INTO notifications (user_id, type, title, message, link) VALUES (?, ?, ?, ?, ?)',
                [
                    $userId,
                    $type !== '' ? mb_substr($type, 0, 50) : 'info',
                    mb_substr(trim($title), 0, 200),
                    $message !== '' ? $message : null,
                    $link !== '' ? mb_substr($link, 0, 255) : null,
                ]
            );

            return $query->rowCount() > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /** Cree la meme notification pour plusieurs destinataires (ex : tous les admins). */
    public function createForMany(array $userIds, string $type, string $title, string $message = '', string $link = ''): int
    {
        $count = 0;
        foreach (array_unique(array_map('intval', $userIds)) as $userId) {
            if ($this->create((int) $userId, $type, $title, $message, $link)) {
                $count++;
            }
        }

        return $count;
    }

    /** Les N dernieres notifications d'un utilisateur (recentes d'abord). */
    public function getForUser(int $userId, int $limit = 30): array
    {
        if ($userId <= 0) {
            return [];
        }

        $this->ensureTable();
        $limit = max(1, min(100, $limit));

        return $this->select_data_table_join_where(
            "SELECT id, type, title, message, link, is_read, created_at
             FROM notifications
             WHERE user_id = ?
             ORDER BY created_at DESC, id DESC
             LIMIT {$limit}",
            [$userId]
        );
    }

    public function getRecent(int $userId, int $limit = 6): array
    {
        return $this->getForUser($userId, $limit);
    }

    public function countUnread(int $userId): int
    {
        if ($userId <= 0) {
            return 0;
        }

        $this->ensureTable();
        $row = $this->FetchSelectWhere('COUNT(*) AS total', 'notifications', 'user_id = ? AND is_read = 0', [$userId]);

        return (int) ($row->total ?? 0);
    }

    public function markRead(int $id, int $userId): bool
    {
        if ($id <= 0 || $userId <= 0) {
            return false;
        }

        $this->ensureTable();
        $this->insertion_update_simples(
            'UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?',
            [$id, $userId]
        );

        return true;
    }

    public function markAllRead(int $userId): bool
    {
        if ($userId <= 0) {
            return false;
        }

        $this->ensureTable();
        $this->insertion_update_simples(
            'UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0',
            [$userId]
        );

        return true;
    }

    /** Lien interne d'une notification (et la marque lue au passage). */
    public function getLink(int $id, int $userId): string
    {
        if ($id <= 0 || $userId <= 0) {
            return '';
        }

        $this->ensureTable();
        $row = $this->FetchSelectWhere('link', 'notifications', 'id = ? AND user_id = ?', [$id, $userId]);

        return (string) ($row->link ?? '');
    }

    /** Ids des administrateurs actifs (destinataires des messages de contact). */
    public function getActiveAdminIds(): array
    {
        if (!$this->existe_table('users')) {
            return [];
        }

        $rows = $this->select_data_table_join_where(
            "SELECT user_id FROM users WHERE role = 'admin' AND COALESCE(statut_compte, 'actif') = 'actif'"
        );

        return array_map(static fn($row): int => (int) $row->user_id, $rows);
    }

    private function existe_table(string $table): bool
    {
        $row = $this->FetchSelectWhere(
            'COUNT(*) AS total',
            'information_schema.tables',
            'table_schema = DATABASE() AND table_name = ?',
            [$table]
        );

        return !empty($row) && (int) ($row->total ?? 0) > 0;
    }
}
