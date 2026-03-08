<?php

class AdminPanel extends Model
{
    private function tableExists(string $table): bool
    {
        $row = $this->FetchSelectWhere(
            'COUNT(*) AS total',
            'information_schema.tables',
            'table_schema = DATABASE() AND table_name = ?',
            [$table]
        );

        return !empty($row) && (int)($row->total ?? 0) > 0;
    }

    private function columnExists(string $table, string $column): bool
    {
        $row = $this->FetchSelectWhere(
            'COUNT(*) AS total',
            'information_schema.columns',
            'table_schema = DATABASE() AND table_name = ? AND column_name = ?',
            [$table, $column]
        );

        return !empty($row) && (int)($row->total ?? 0) > 0;
    }

    public function getDashboardStats(): array
    {
        $stats = [
            'users' => 0,
            'projects' => 0,
            'pending' => 0,
            'messages' => 0,
            'categories' => 0,
        ];

        if ($this->tableExists('users')) {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'users', '1=1');
            $stats['users'] = (int)($row->total ?? 0);
        }

        if ($this->tableExists('projects')) {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'projects', '1=1');
            $stats['projects'] = (int)($row->total ?? 0);

            if ($this->columnExists('projects', 'admin_status')) {
                $rowPending = $this->FetchSelectWhere('COUNT(*) AS total', 'projects', 'admin_status = ?', ['en_attente']);
                $stats['pending'] = (int)($rowPending->total ?? 0);
            } else {
                $rowPending = $this->FetchSelectWhere('COUNT(*) AS total', 'projects', 'status = ?', ['en cours']);
                $stats['pending'] = (int)($rowPending->total ?? 0);
            }
        }

        if ($this->tableExists('messages')) {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'messages', '1=1');
            $stats['messages'] = (int)($row->total ?? 0);
        }

        if ($this->tableExists('categories')) {
            $row = $this->FetchSelectWhere('COUNT(*) AS total', 'categories', '1=1');
            $stats['categories'] = (int)($row->total ?? 0);
        }

        return $stats;
    }

    public function getPendingProjects(): array
    {
        if (!$this->tableExists('projects')) {
            return [];
        }

        $hasAdminStatus = $this->columnExists('projects', 'admin_status');

        $where = $hasAdminStatus ? "p.admin_status = 'en_attente'" : "p.status = 'en cours'";
        $statusExpr = $hasAdminStatus ? 'p.admin_status' : "IF(p.status = 'en cours', 'en_attente', 'valide')";

        return $this->select_data_table_join_where(
            "SELECT p.id, p.title, p.created_at,
                    COALESCE(CONCAT(u.nom, ' ', u.prenom), 'N/A') AS auteur,
                    COALESCE(c.nom, 'Sans catégorie') AS categorie,
                    {$statusExpr} AS statut
             FROM projects p
             LEFT JOIN users u ON u.user_id = p.user_id
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE {$where}
             ORDER BY p.created_at DESC"
        );
    }

    public function getAllProjects(): array
    {
        if (!$this->tableExists('projects')) {
            return [];
        }

        $hasAdminStatus = $this->columnExists('projects', 'admin_status');
        $statusExpr = $hasAdminStatus ? 'p.admin_status' : "IF(p.status = 'en cours', 'en_attente', 'valide')";

        return $this->select_data_table_join_where(
            "SELECT p.id, p.title, p.created_at,
                    COALESCE(CONCAT(u.nom, ' ', u.prenom), 'N/A') AS auteur,
                    COALESCE(c.nom, 'Sans catégorie') AS categorie,
                    {$statusExpr} AS statut
             FROM projects p
             LEFT JOIN users u ON u.user_id = p.user_id
             LEFT JOIN categories c ON c.id = p.category_id
             ORDER BY p.created_at DESC"
        );
    }

    public function validateProject(int $projectId): bool
    {
        if (!$this->tableExists('projects')) {
            return false;
        }

        if ($this->columnExists('projects', 'admin_status')) {
            $q = $this->insertion_update_simples(
                'UPDATE projects SET admin_status = ? WHERE id = ?',
                ['valide', $projectId]
            );
            return (bool)$q;
        }

        $q = $this->insertion_update_simples(
            'UPDATE projects SET status = ? WHERE id = ?',
            ['termine', $projectId]
        );

        return (bool)$q;
    }

    public function rejectProject(int $projectId): bool
    {
        if (!$this->tableExists('projects')) {
            return false;
        }

        $q = $this->insertion_update_simples('DELETE FROM projects WHERE id = ?', [$projectId]);
        return (bool)$q;
    }

    public function getCategories(): array
    {
        if (!$this->tableExists('categories')) {
            return [];
        }

        return $this->select_data_table_join_where('SELECT id, nom, description FROM categories ORDER BY id DESC');
    }

    public function addCategory(string $nom, string $description): bool
    {
        if (!$this->tableExists('categories')) {
            return false;
        }

        $q = $this->insertion_update_simples(
            'INSERT INTO categories (nom, description) VALUES (?, ?)',
            [$nom, $description]
        );

        return (bool)$q;
    }

    public function updateCategory(int $id, string $nom, string $description): bool
    {
        if (!$this->tableExists('categories')) {
            return false;
        }

        $q = $this->insertion_update_simples(
            'UPDATE categories SET nom = ?, description = ? WHERE id = ?',
            [$nom, $description, $id]
        );

        return (bool)$q;
    }

    public function deleteCategory(int $id): bool
    {
        if (!$this->tableExists('categories')) {
            return false;
        }

        $q = $this->insertion_update_simples('DELETE FROM categories WHERE id = ?', [$id]);
        return (bool)$q;
    }

    public function getMessages(): array
    {
        if (!$this->tableExists('messages')) {
            return [];
        }

        return $this->select_data_table_join_where(
            "SELECT m.id, m.nom, m.email, m.message, m.created_at,
                    COALESCE(p.title, 'Projet supprimé') AS projet
             FROM messages m
             LEFT JOIN projects p ON p.id = m.project_id
             ORDER BY m.created_at DESC"
        );
    }
}
