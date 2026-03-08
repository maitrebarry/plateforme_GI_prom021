<?php

class Profiles extends Controller
{
    public function index()
    {
        $this->appercu();
    }

    private function requireAuth(): int
    {
        $userId = (int)($_SESSION['user_id'] ?? 0);
        if ($userId <= 0) {
            $this->redirect('Homes/login');
        }

        return $userId;
    }

    private function normalizeContact(?string $value): string
    {
        return preg_replace('/\D+/', '', (string)$value) ?? '';
    }

    public function appercu()
    {
        $userId = $this->requireAuth();
        $profile = new Profile();

        if (isset($_POST['update_profile_info'])) {
            $this->handleProfileInfoUpdate($profile, $userId);
            return;
        }

        if (isset($_POST['modifier'])) {
            $this->handlePasswordUpdate($profile, $userId);
            return;
        }

        $user = $profile->findById($userId);
        $this->view('profile', [
            'user' => $user,
            'pageTitle' => 'Mon profil',
        ]);
    }

    private function handlePasswordUpdate(Profile $profile, int $userId): void
    {
        $ancien = $_POST['ancien_mot_de_passe'] ?? '';
        $nouveau = $_POST['nouveau_mot_de_passe'] ?? '';
        $confirm = $_POST['comfirme_mot_de_passe'] ?? '';

        if ($ancien === '' || $nouveau === '' || $confirm === '') {
            $profile->set_flash("Veuillez remplir tous les champs.", 'danger');
            $this->redirect('Profiles/appercu');
        }

        $user = $profile->findById($userId);
        if (!$user || !password_verify($ancien, $user->password)) {
            $profile->set_flash("Ancien mot de passe incorrect.", 'danger');
            $this->redirect('Profiles/appercu');
        }

        if ($nouveau !== $confirm) {
            $profile->set_flash("Les nouveaux mots de passe ne correspondent pas.", 'danger');
            $this->redirect('Profiles/appercu');
        }

        $profile->updatePassword($userId, $nouveau);
        $profile->set_flash("Mot de passe modifié avec succès.", 'success');
        $this->redirect('Profiles/appercu');
    }

    private function handleProfileInfoUpdate(Profile $profile, int $userId): void
    {
        $prenom = trim((string)($_POST['user_firstname'] ?? ''));
        $nom = trim((string)($_POST['user_lastname'] ?? ''));
        $email = filter_var($_POST['user_email'] ?? '', FILTER_SANITIZE_EMAIL);
        $contact = $this->normalizeContact($_POST['user_contact'] ?? '');
        $universite = trim((string)($_POST['user_universite'] ?? ''));
        $faculte = trim((string)($_POST['user_faculte'] ?? ''));
        $filiere = trim((string)($_POST['user_filiere'] ?? ''));

        if ($prenom === '' || $nom === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $profile->set_flash("Veuillez fournir un prénom, un nom et un email valides.", 'danger');
            $this->redirect('Profiles/appercu');
        }

        if ($contact !== '' && strlen($contact) !== 8) {
            $profile->set_flash("Le contact doit contenir 8 chiffres.", 'danger');
            $this->redirect('Profiles/appercu');
        }

        if ($profile->emailExistsForOther($email, $userId)) {
            $profile->set_flash("Cet email est déjà utilisé par un autre compte.", 'warning');
            $this->redirect('Profiles/appercu');
        }

        $updated = $profile->updateUserInfo($userId, [
            'prenom' => $prenom,
            'nom' => $nom,
            'email' => $email,
            'contact' => $contact,
            'universite' => $universite,
            'faculte' => $faculte,
            'filiere' => $filiere,
        ]);

        if ($updated) {
            $_SESSION['prenom'] = $prenom;
            $_SESSION['nom'] = $nom;
            $_SESSION['email'] = $email;
            $_SESSION['contact'] = $contact;
            $_SESSION['universite'] = $universite;
            $_SESSION['faculte'] = $faculte;
            $_SESSION['filiere'] = $filiere;
            $profile->set_flash("Profil mis à jour avec succès.", 'success');
        } else {
            $profile->set_flash("Impossible de mettre à jour vos informations pour le moment.", 'danger');
        }

        $this->redirect('Profiles/appercu');
    }

    public function modifier_image()
    {
        $userId = $this->requireAuth();
        $profileModel = new Profile();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $profileModel->set_flash("Action non autorisée.", 'danger');
            $this->redirect('Profiles/appercu');
        }

        if (!isset($_FILES['newAvatar']) || $_FILES['newAvatar']['error'] !== UPLOAD_ERR_OK) {
            $profileModel->set_flash("Veuillez sélectionner une image valide.", 'danger');
            $this->redirect('Profiles/appercu');
        }

        $cheminTemp = $_FILES['newAvatar']['tmp_name'];
        $nomOriginal = $_FILES['newAvatar']['name'];
        $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower(pathinfo($nomOriginal, PATHINFO_EXTENSION));
        $tailleMax = 5 * 1024 * 1024;

        if (!in_array($extension, $extensionsAutorisees, true)) {
            $profileModel->set_flash("Format de fichier non autorisé.", 'danger');
            $this->redirect('Profiles/appercu');
        }

        if (!empty($_FILES['newAvatar']['size']) && $_FILES['newAvatar']['size'] > $tailleMax) {
            $profileModel->set_flash("L'image dépasse la taille maximale autorisée (5 Mo).", 'danger');
            $this->redirect('Profiles/appercu');
        }

        $nomFichier = uniqid('profil_', true) . '.' . $extension;
        $destination = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'image_profile' . DIRECTORY_SEPARATOR . $nomFichier;

        if (!move_uploaded_file($cheminTemp, $destination)) {
            $profileModel->set_flash("Erreur lors de l'enregistrement du fichier.", 'danger');
            $this->redirect('Profiles/appercu');
        }

        $_SESSION['image'] = $nomFichier;

        $sql = "UPDATE users SET image = :image WHERE user_id = :id";
        $params = ['image' => $nomFichier, 'id' => $userId];
        $profileModel->insertion_update_simples($sql, $params);

        $profileModel->set_flash("Photo de profil mise à jour avec succès.", 'success');
        $this->redirect('Profiles/appercu');
    }
}
