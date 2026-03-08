<?php

class Admins extends Controller
{
    private function guardAdmin(): void
    {
        $role = strtolower((string)($_SESSION['role'] ?? ''));
        if ($role !== 'admin') {
            $_SESSION['notification'] = [
                'type' => 'warning',
                'message' => 'Accès réservé aux administrateurs.',
            ];
            $this->redirect('Homes/dashboard');
        }
    }

    public function index(): void
    {
        $this->dashboard();
    }

    public function dashboard(): void
    {
        $this->guardAdmin();
        $data = [
            'pageTitle' => 'Dashboard administrateur',
            'dashboardStats' => [
                'users' => 128,
                'projects' => 42,
                'pending' => 7,
                'messages' => 15,
                'categories' => 9,
            ],
            'pendingProjects' => $this->mockPendingProjects(),
        ];

        $this->view('admin_dashboard', $data);
    }

    public function pending_projects(): void
    {
        $this->guardAdmin();

        if (isset($_POST['validate_project'])) {
            $_SESSION['notification'] = ['type' => 'success', 'message' => 'Projet validé avec succès.'];
            $this->redirect('Admins/pending_projects');
        }

        if (isset($_POST['reject_project'])) {
            $_SESSION['notification'] = ['type' => 'warning', 'message' => 'Projet refusé/supprimé.'];
            $this->redirect('Admins/pending_projects');
        }

        $this->view('admin_pending_projects', [
            'pageTitle' => 'Projets à valider',
            'projects' => $this->mockPendingProjects(),
        ]);
    }

    public function projects_management(): void
    {
        $this->guardAdmin();

        if (isset($_POST['validate_project'])) {
            $_SESSION['notification'] = ['type' => 'success', 'message' => 'Projet validé avec succès.'];
            $this->redirect('Admins/projects_management');
        }

        if (isset($_POST['reject_project'])) {
            $_SESSION['notification'] = ['type' => 'warning', 'message' => 'Projet supprimé.'];
            $this->redirect('Admins/projects_management');
        }

        $this->view('admin_projects_management', [
            'pageTitle' => 'Gestion des projets',
            'projects' => $this->mockAllProjects(),
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

        if (isset($_POST['add_category'])) {
            $_SESSION['notification'] = ['type' => 'info', 'message' => 'Mode maquette: action Ajouter simulée.'];
            $this->redirect('Admins/categories');
        }

        if (isset($_POST['update_category'])) {
            $_SESSION['notification'] = ['type' => 'info', 'message' => 'Mode maquette: action Modifier simulée.'];
            $this->redirect('Admins/categories');
        }

        if (isset($_POST['delete_category'])) {
            $_SESSION['notification'] = ['type' => 'info', 'message' => 'Mode maquette: action Supprimer simulée.'];
            $this->redirect('Admins/categories');
        }

        $this->view('admin_categories', [
            'pageTitle' => 'Gestion des catégories',
            'categories' => $this->mockCategories(),
        ]);
    }

    public function messages(): void
    {
        $this->guardAdmin();

        $this->view('admin_messages', [
            'pageTitle' => 'Messages / Contact',
            'messages' => $this->mockMessages(),
        ]);
    }

    private function mockPendingProjects(): array
    {
        return [
            (object) ['id' => 1, 'title' => 'Plateforme e-learning', 'auteur' => 'Traoré Awa', 'categorie' => 'Web', 'created_at' => '2026-03-01', 'statut' => 'en_attente'],
            (object) ['id' => 2, 'title' => 'Application mobile santé', 'auteur' => 'Diallo Moussa', 'categorie' => 'Mobile', 'created_at' => '2026-03-02', 'statut' => 'en_attente'],
            (object) ['id' => 3, 'title' => 'Système de bibliothèque', 'auteur' => 'Coulibaly Aminata', 'categorie' => 'Desktop', 'created_at' => '2026-03-03', 'statut' => 'en_attente'],
        ];
    }

    private function mockAllProjects(): array
    {
        return [
            (object) ['id' => 1, 'title' => 'Plateforme e-learning', 'auteur' => 'Traoré Awa', 'categorie' => 'Web', 'created_at' => '2026-03-01', 'statut' => 'en_attente'],
            (object) ['id' => 2, 'title' => 'Application mobile santé', 'auteur' => 'Diallo Moussa', 'categorie' => 'Mobile', 'created_at' => '2026-03-02', 'statut' => 'valide'],
            (object) ['id' => 3, 'title' => 'Système de bibliothèque', 'auteur' => 'Coulibaly Aminata', 'categorie' => 'Desktop', 'created_at' => '2026-03-03', 'statut' => 'valide'],
            (object) ['id' => 4, 'title' => 'Portail alumni', 'auteur' => 'Koné Fatou', 'categorie' => 'Web', 'created_at' => '2026-03-04', 'statut' => 'en_attente'],
        ];
    }

    private function mockCategories(): array
    {
        return [
            (object) ['id' => 1, 'nom' => 'Web', 'description' => 'Applications web'],
            (object) ['id' => 2, 'nom' => 'Mobile', 'description' => 'Applications Android/iOS'],
            (object) ['id' => 3, 'nom' => 'Desktop', 'description' => 'Applications bureau'],
            (object) ['id' => 4, 'nom' => 'IA', 'description' => 'Machine learning / IA'],
        ];
    }

    private function mockMessages(): array
    {
        return [
            (object) ['nom' => 'Sangaré Oumar', 'email' => 'oumar@gmail.com', 'projet' => 'Plateforme e-learning', 'message' => 'Projet très intéressant.', 'created_at' => '2026-03-05'],
            (object) ['nom' => 'Maiga Mariam', 'email' => 'mariam@gmail.com', 'projet' => 'Application mobile santé', 'message' => 'Pouvez-vous partager le code source ?', 'created_at' => '2026-03-06'],
            (object) ['nom' => 'Keita Idrissa', 'email' => 'idrissa@gmail.com', 'projet' => 'Portail alumni', 'message' => 'Bravo pour ce travail.', 'created_at' => '2026-03-07'],
        ];
    }
}
