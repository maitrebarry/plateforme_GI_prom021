<?php

class Utilisateurs extends Controller
{
    private function sanitize(?string $value): string
    {
        return trim((string) $value);
    }

    private function normalizeContact(?string $value): string
    {
        return preg_replace('/\D+/', '', (string) $value) ?? '';
    }

    private function isValidContact(?string $value): bool
    {
        return preg_match('/^\d{8}$/', $this->normalizeContact($value)) === 1;
    }

    private function guardAdmin(): void
    {
        $role = strtolower((string) ($_SESSION['role'] ?? ''));
        if ($role !== 'admin') {
            $_SESSION['notification'] = [
                'type' => 'warning',
                'message' => 'Acces reserve aux administrateurs.',
            ];
            $this->redirect('Homes/dashboard');
        }
    }

    private function userListFilters(): array
    {
        $role = trim((string) ($_GET['role'] ?? 'all'));
        $status = trim((string) ($_GET['status'] ?? 'all'));
        $sortBy = trim((string) ($_GET['sort_by'] ?? 'name'));
        $sortDir = trim((string) ($_GET['sort_dir'] ?? 'asc'));

        return [
            'search' => trim((string) ($_GET['search'] ?? '')),
            'role' => in_array($role, ['all', 'admin', 'der', 'etudiant'], true) ? $role : 'all',
            'status' => in_array($status, ['all', 'actif', 'bloque'], true) ? $status : 'all',
            'universite' => trim((string) ($_GET['universite'] ?? '')),
            'sortBy' => in_array($sortBy, ['name', 'email', 'role', 'university', 'status'], true) ? $sortBy : 'name',
            'sortDir' => in_array(strtolower($sortDir), ['asc', 'desc'], true) ? strtolower($sortDir) : 'asc',
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

        return http_build_query(array_filter($params, static function ($value) {
            return $value !== null && $value !== '';
        }));
    }

    private function redirectUserList(): void
    {
        $route = 'Utilisateurs/liste_utilisateur';
        $query = trim((string) ($_POST['return_query'] ?? ''));

        if ($query !== '') {
            $route .= '?' . ltrim($query, '?');
        }

        $this->redirect($route);
    }

    public function ajouter_utilisateur()
    {
        $utilisateur = new Utilisateur();
        $universiteModel = new Universite();
        $faculteModel = new Faculte();

        $universiteModel->seedDefaultUniversitesIfEmpty();
        $universites = $universiteModel->getAllUniversites();

        if (isset($_POST['submit'])) {
            if ($_POST['password'] != $_POST['password_confirm']) {
                $utilisateur->set_flash("Les mots de passe ne correspondent pas", "danger");
                $this->redirect("Utilisateurs/ajouter_utilisateur");
                return;
            }

            $universiteId = isset($_POST['universite_id']) && $_POST['universite_id'] !== '' ? (int) $_POST['universite_id'] : null;
            $faculteId = isset($_POST['faculte_id']) && $_POST['faculte_id'] !== '' ? (int) $_POST['faculte_id'] : null;

            $filiere = $this->sanitize($_POST['filiere'] ?? '');
            $autreEtablissement = $this->sanitize($_POST['autre_etablissement'] ?? '');
            $autreDepartement = $this->sanitize($_POST['autre_departement'] ?? '');

            if ($filiere === '') {
                $utilisateur->set_flash("Veuillez renseigner votre filiere.", "danger");
                $this->redirect("Utilisateurs/ajouter_utilisateur");
                return;
            }

            $universiteNom = '';
            $faculteNom = '';

            if (($universiteId ?? 0) > 0) {
                $universite = $universiteModel->getUniversiteById($universiteId);

                if (!$universite) {
                    $utilisateur->set_flash("Universite invalide.", "danger");
                    $this->redirect("Utilisateurs/ajouter_utilisateur");
                    return;
                }

                $universiteNom = $universite->nom_universite;

                if (($faculteId ?? 0) > 0) {
                    $faculte = $faculteModel->getFaculteByIdAndUniversite($faculteId, $universiteId);

                    if (!$faculte) {
                        $utilisateur->set_flash("Faculte ou institut invalide pour cette universite.", "danger");
                        $this->redirect("Utilisateurs/ajouter_utilisateur");
                        return;
                    }

                    $faculteNom = $faculte->nom_faculte;
                }
            } else {
                if ($autreEtablissement === '') {
                    $utilisateur->set_flash("Veuillez saisir le nom de votre etablissement.", "danger");
                    $this->redirect("Utilisateurs/ajouter_utilisateur");
                    return;
                }

                $universiteNom = $autreEtablissement;
                $faculteNom = $autreDepartement;
            }

            $data = [
                "prenom" => $_POST['prenom'],
                "nom" => $_POST['nom'],
                "email" => $_POST['email'],
                "universite_id" => $universiteId,
                "faculte_id" => $faculteId,
                "universite" => $universiteNom,
                "faculte" => $faculteNom,
                "filiere" => $filiere,
                "autre_etablissement" => $autreEtablissement !== '' ? $autreEtablissement : null,
                "autre_departement" => $autreDepartement !== '' ? $autreDepartement : null,
                "password" => $_POST['password']
            ];

            $insert = $utilisateur->save_utilisateur($data);

            if ($insert) {
                $utilisateur->set_flash("Compte cree avec succes", "success");
            }
        }

        $this->view("register", ['universites' => $universites]);
    }

    public function liste_utilisateur()
    {
        $this->guardAdmin();

        $utilisateur = new Utilisateur();
        $universiteModel = new Universite();
        $faculteModel = new Faculte();
        $filters = $this->userListFilters();
        $pagination = $this->paginationParams();

        $universiteModel->seedDefaultUniversitesIfEmpty();
        $universites = $universiteModel->getAllUniversites();

        if (isset($_POST['bulk_user_action'])) {
            $userIds = $_POST['user_ids'] ?? [];
            $userIds = is_array($userIds) ? $userIds : [];

            if (empty($userIds)) {
                $utilisateur->set_flash("Veuillez selectionner au moins un utilisateur.", "warning");
                $this->redirectUserList();
                return;
            }

            $updated = 0;
            $action = (string) $_POST['bulk_user_action'];

            if ($action === 'activate') {
                $updated = $utilisateur->updateManyAccountStatus($userIds, 'actif');
                $utilisateur->set_flash($updated > 0 ? "Utilisateurs actives avec succes." : "Aucun utilisateur mis a jour.", $updated > 0 ? "success" : "warning");
            } elseif ($action === 'block') {
                $updated = $utilisateur->updateManyAccountStatus($userIds, 'bloque');
                $utilisateur->set_flash($updated > 0 ? "Utilisateurs bloques avec succes." : "Aucun utilisateur mis a jour.", $updated > 0 ? "success" : "warning");
            } elseif ($action === 'delete') {
                $currentUserId = (int) ($_SESSION['user_id'] ?? 0);
                $filteredIds = array_values(array_filter(array_map('intval', $userIds), static fn(int $id): bool => $id > 0 && $id !== $currentUserId));
                $updated = $utilisateur->deleteManyById($filteredIds);
                $utilisateur->set_flash($updated > 0 ? "Utilisateurs supprimes avec succes." : "Aucun utilisateur supprime.", $updated > 0 ? "success" : "warning");
            }

            $this->redirectUserList();
            return;
        }

        if (isset($_POST['user_action'], $_POST['user_id'])) {
            $targetId = (int) $_POST['user_id'];

            if ($targetId <= 0) {
                $utilisateur->set_flash("Utilisateur introuvable.", "danger");
                $this->redirectUserList();
                return;
            }

            $targetUser = $utilisateur->findById($targetId);
            if (!$targetUser || ($targetUser->role ?? '') !== 'etudiant') {
                $utilisateur->set_flash("Action reservee aux comptes Etudiant.", "warning");
                $this->redirectUserList();
                return;
            }

            if ($targetId === (int) ($_SESSION['user_id'] ?? 0) && ($_POST['user_action'] === 'delete_user')) {
                $utilisateur->set_flash("Vous ne pouvez pas supprimer votre propre compte.", "warning");
                $this->redirectUserList();
                return;
            }

            switch ($_POST['user_action']) {
                case 'toggle_status':
                    $status = $_POST['target_status'] ?? '';
                    if ($utilisateur->updateAccountStatus($targetId, $status)) {
                        $message = $status === 'actif' ? "Utilisateur reactive." : "Utilisateur bloque.";
                        $utilisateur->set_flash($message, 'success');
                    } else {
                        $utilisateur->set_flash("Impossible de mettre a jour le statut.", 'danger');
                    }
                    break;
                case 'delete_user':
                    if ($utilisateur->deleteById($targetId)) {
                        $utilisateur->set_flash("Utilisateur supprime avec succes.", 'success');
                    } else {
                        $utilisateur->set_flash("Impossible de supprimer cet utilisateur.", 'danger');
                    }
                    break;
                default:
                    $utilisateur->set_flash("Action inconnue.", 'danger');
                    break;
            }

            $this->redirectUserList();
            return;
        }

        if (isset($_POST['save_user'])) {
            if ($_POST['password'] != $_POST['password_confirm']) {
                $utilisateur->set_flash("Les mots de passe ne correspondent pas", "danger");
                $this->redirectUserList();
                return;
            }

            if (!$this->isValidContact($_POST['contact_utilisateur'] ?? '')) {
                $utilisateur->set_flash("Le contact doit contenir 8 chiffres, par exemple 76 56 23 17.", "danger");
                $this->redirectUserList();
                return;
            }

            $universiteId = isset($_POST['universite_id']) && $_POST['universite_id'] !== '' ? (int) $_POST['universite_id'] : null;
            $faculteId = isset($_POST['faculte_id']) && $_POST['faculte_id'] !== '' ? (int) $_POST['faculte_id'] : null;

            if (($universiteId ?? 0) <= 0) {
                $utilisateur->set_flash("Veuillez selectionner une universite.", "danger");
                $this->redirectUserList();
                return;
            }

            if (($faculteId ?? 0) <= 0) {
                $utilisateur->set_flash("Veuillez selectionner une faculte ou un institut.", "danger");
                $this->redirectUserList();
                return;
            }

            $universite = $universiteModel->getUniversiteById($universiteId);
            $faculte = $faculteModel->getFaculteByIdAndUniversite($faculteId, $universiteId);

            if (!$universite || !$faculte) {
                $utilisateur->set_flash("Universite ou faculte invalide.", "danger");
                $this->redirectUserList();
                return;
            }

            $data = [
                "prenom" => $_POST['prenom'],
                "nom" => $_POST['nom'],
                "email" => $_POST['email'],
                "universite_id" => $universiteId,
                "faculte_id" => $faculteId,
                "universite" => $universite->nom_universite,
                "faculte" => $faculte->nom_faculte,
                "role" => $_POST['role'],
                "contact" => $this->normalizeContact($_POST['contact_utilisateur']),
                "password" => $_POST['password']
            ];

            $insert = $utilisateur->save_utilisateur_admin($data);

            if ($insert) {
                $utilisateur->set_flash("Compte cree avec succes", "success");
            }

            $this->redirectUserList();
            return;
        }

        $clauses = [];
        $params = [];

        if ($filters['search'] !== '') {
            $clauses[] = "(CONCAT(COALESCE(nom, ''), ' ', COALESCE(prenom, '')) LIKE ? OR COALESCE(email, '') LIKE ? OR COALESCE(universite, '') LIKE ? OR COALESCE(faculte, '') LIKE ? OR COALESCE(filiere, '') LIKE ?)";
            $term = '%' . $filters['search'] . '%';
            array_push($params, $term, $term, $term, $term, $term);
        }

        if ($filters['role'] !== 'all') {
            $clauses[] = 'role = ?';
            $params[] = $filters['role'];
        }

        if ($filters['status'] !== 'all') {
            $clauses[] = "COALESCE(statut_compte, 'actif') = ?";
            $params[] = $filters['status'];
        }

        if ($filters['universite'] !== '') {
            $clauses[] = 'universite = ?';
            $params[] = $filters['universite'];
        }

        $whereSql = !empty($clauses) ? ' WHERE ' . implode(' AND ', $clauses) : '';
        $allowedSorts = [
            'name' => 'nom',
            'email' => 'email',
            'role' => 'role',
            'university' => 'universite',
            'status' => 'statut_compte',
        ];
        $sortColumn = $allowedSorts[$filters['sortBy']] ?? 'nom';
        $sortDirection = $filters['sortDir'] === 'desc' ? 'DESC' : 'ASC';

        $countRows = $utilisateur->select_data_table_join_where(
            "SELECT COUNT(*) AS total FROM users {$whereSql}",
            $params
        );
        $total = (int) ($countRows[0]->total ?? 0);
        $totalPages = max(1, (int) ceil($total / $pagination['perPage']));
        $currentPage = min($pagination['page'], $totalPages);
        $offset = ($currentPage - 1) * $pagination['perPage'];

        $liste = $utilisateur->select_data_table_join_where(
            "SELECT
                user_id,
                nom,
                prenom,
                email,
                role,
                universite,
                faculte,
                filiere,
                COALESCE(statut_compte, 'actif') AS statut_compte
             FROM users
             {$whereSql}
             ORDER BY {$sortColumn} {$sortDirection}, prenom {$sortDirection}
             LIMIT {$pagination['perPage']} OFFSET {$offset}",
            $params
        );

        $statsRows = $utilisateur->select_data_table_join_where(
            "SELECT
                COUNT(*) AS total_users,
                SUM(CASE WHEN role = 'etudiant' THEN 1 ELSE 0 END) AS student_users,
                SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) AS admin_users,
                SUM(CASE WHEN role = 'der' THEN 1 ELSE 0 END) AS der_users,
                SUM(CASE WHEN COALESCE(statut_compte, 'actif') = 'bloque' THEN 1 ELSE 0 END) AS blocked_users
             FROM users {$whereSql}",
            $params
        );
        $userStats = $statsRows[0] ?? (object) [
            'total_users' => 0,
            'student_users' => 0,
            'admin_users' => 0,
            'der_users' => 0,
            'blocked_users' => 0,
        ];

        $this->view('liste_utilisateur', [
            'liste' => $liste,
            'universites' => $universites,
            'userStats' => $userStats,
            'userSearch' => $filters['search'],
            'userRoleFilter' => $filters['role'],
            'userStatusFilter' => $filters['status'],
            'userUniversiteFilter' => $filters['universite'],
            'userSortBy' => $filters['sortBy'],
            'userSortDir' => $filters['sortDir'],
            'currentPage' => $currentPage,
            'perPage' => $pagination['perPage'],
            'totalPages' => $totalPages,
            'totalItems' => $total,
            'paginationQuery' => $this->queryStringWithoutPage(['per_page' => $pagination['perPage']]),
        ]);
    }

    public function getFacultes($universite_id)
    {
        header('Content-Type: application/json; charset=utf-8');

        $universite_id = (int) $universite_id;

        if ($universite_id <= 0) {
            echo json_encode([]);
            return;
        }

        $faculte = new Faculte();
        $result = $faculte->getFacultesByUniversite($universite_id);

        echo json_encode($result);
    }
}
