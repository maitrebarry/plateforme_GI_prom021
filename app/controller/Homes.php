<?php

class Homes extends Controller
{
    private const DER_ALLOWED_UPLOAD_EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png'];
    private const DER_MAX_FILE_SIZE = 5242880;

    public function index(): void
    {
        $data = $this->baseViewData();
        $data['pageTitle'] = 'Accueil';
        $data['projects'] = $this->getMockProjects();
        $data['departmentAnnouncements'] = $this->getDepartmentAnnouncements();
        $data['departmentInformations'] = $this->getDepartmentPostsByType('information', 3);
        $data['departmentResults'] = $this->getDepartmentPostsByType('resultat', 3);
        $data['departmentOpportunities'] = $this->getDepartmentPostsByType('opportunite', 3);

        $this->view('home', $data);
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

        $data = $this->baseViewData();
        $data['pageTitle'] = 'Dashboard étudiant';
        $data['projects'] = $this->getMockProjects();
        $data['studentStats'] = [
            'mesProjets' => 3,
            'enAttente' => 1,
            'valides' => 2,
            'messages' => 0,
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
        $data['derStats'] = [
            'annonces' => $postModel->countByType('annonce'),
            'informations' => $postModel->countByType('information'),
            'evenements' => $postModel->countByType('evenement'),
            'resultats' => $postModel->countByType('resultat'),
            'opportunites' => $postModel->countByType('opportunite'),
        ];
        $data['latestPublications'] = array_slice($this->getDepartmentPostsByType('annonce', 10), 0, 5);

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


    public function mes_projets(): void
    {
        $data = $this->baseViewData();
        $data['pageTitle'] = 'Mes projets';
        $data['projects'] = $this->getMockProjects();
        $this->view('projects', $data);
    }

    public function messages_recus(): void
    {
        $data = $this->baseViewData();
        $data['pageTitle'] = 'Messages reçus';
        $_SESSION['notification'] = [
            'type' => 'info',
            'message' => 'Aucun message pour le moment.',
        ];
        $this->view('profile', $data);
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

        $data = $this->baseViewData();
        $data['pageTitle'] = 'Espace DER - Gestion des publications';
        $data['annonces'] = $this->getDepartmentPostsByType('annonce', 20);
        $data['informations'] = $this->getDepartmentPostsByType('information', 20);
        $data['events'] = $this->getDepartmentPostsByType('evenement', 20);
        $data['results'] = $this->getDepartmentPostsByType('resultat', 20);
        $data['opportunities'] = $this->getDepartmentPostsByType('opportunite', 20);

        $this->view('der_annonces', $data);
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
        $data['projects'] = $this->getMockProjects();

        $this->view('projects', $data);
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
        $announcements = $this->getDepartmentPostsByType('annonce');
        $informations = $this->getDepartmentPostsByType('information');
        $events = $this->getDepartmentPostsByType('evenement');
        $results = $this->getDepartmentPostsByType('resultat');
        $opportunities = $this->getDepartmentPostsByType('opportunite');

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
        $data['departmentStats'] = [
            'annonces' => count($announcements),
            'informations' => count($informations),
            'evenements' => count($events),
            'resultats' => count($results),
            'opportunites' => count($opportunities),
        ];

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