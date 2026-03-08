<?php

class Homes extends Controller
{
    public function index(): void
    {
        $data = $this->baseViewData();
        $data['pageTitle'] = 'Accueil';
        $data['projects'] = $this->getMockProjects();

        $this->view('home', [
            $data,
        ]);
    }

    public function dashboard(): void
    {
        $this->admin();
    }

    public function admin(): void
    {
        $data = $this->baseViewData();
        $data['pageTitle'] = 'Dashboard administrateur';
        $data['projects'] = $this->getMockProjects();
        $data['dashboardStats'] = [
            'users' => 128,
            'projects' => count($data['projects']),
            'registrations' => 47,
            'alerts' => count($data['notifications']),
        ];

        $this->view('admin_dashboard', [
            $data,
        ]);
    }

    public function login(): void
    {
        $data = $this->baseViewData();
        $data['pageTitle'] = 'Connexion';

        $this->view('login', [
            $data,
        ]);
    }

    public function register(): void
    {
        $data = $this->baseViewData();
        $data['pageTitle'] = 'Inscription';

        $this->view('register', [
            $data,
        ]);
    }

    public function projects(): void
    {
        $data = $this->baseViewData();
        $data['pageTitle'] = 'Liste des projets';
        $data['projects'] = $this->getMockProjects();

        $this->view('projects', [
            $data,
        ]);
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

        $this->view('project_detail', [
            $data,
        ]);
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

        $this->view('profile', [
            $data,
        ]);
    }

    public function departement(): void
    {
        $data = $this->baseViewData();
        $data['pageTitle'] = 'Espace Département';
        $data['department'] = [
            'name' => 'Département Génie Informatique',
            'manager' => 'DER - Nom à définir',
            'contact' => 'der.gi@universite.local',
        ];

        $data['departmentAnnouncements'] = [
            ['title' => 'Réunion pédagogique', 'date' => '2026-03-10', 'content' => 'Réunion avec les délégués de classe à 10h.'],
        ];
        $data['departmentEvents'] = [
            ['title' => 'Hackathon GI', 'date' => '2026-04-05', 'location' => 'Salle A1'],
        ];
        $data['departmentResults'] = [
            ['title' => 'Résultats semestre 1', 'date' => '2026-03-20', 'link' => '#'],
        ];
        $data['departmentOpportunities'] = [
            ['title' => 'Stage développeur web', 'date' => '2026-03-30', 'organization' => 'Entreprise partenaire'],
        ];

        $this->view('departement', [
            $data,
        ]);
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
}
