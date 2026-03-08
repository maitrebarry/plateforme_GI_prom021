<?php

class Profiles extends Controller
{

    public function index()
    {
        if (isset($_FILES['newAvatar']) && $_FILES['newAvatar']['error'] === UPLOAD_ERR_OK) {
            $cheminComplet = $_FILES['newAvatar']['tmp_name'];
            $nomOriginal = $_FILES['newAvatar']['name'];

            $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $extension = strtolower(pathinfo($nomOriginal, PATHINFO_EXTENSION));

            if (in_array($extension, $extensionsAutorisees)) {
                $nomFichier = uniqid('profil_', true) . '.' . $extension;
                $destination = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'image_profile' . DIRECTORY_SEPARATOR . $nomFichier;
                if (move_uploaded_file($cheminComplet, $destination)) {
                    $_SESSION['image'] = $nomFichier;
                } else {
                    $_SESSION['flash'] = "Erreur lors de l'enregistrement du fichier.";
                }
            } else {
                $_SESSION['flash'] = "Format de fichier non autorisé.";
            }
        }

        $this->view("profile", [
            'image' => $_SESSION['image'] ?? 'default.png',
            'flash' => $_SESSION['flash'] ?? null,
            // autres données...
        ]);

        unset($_SESSION['flash']);
    }





    public function appercu()
    {
        if (isset($_POST["modifier"])) {
            //echo "ok";exit;
            $id_user = $_SESSION['user_id'] ?? null;
            $ancien = $_POST['ancien_mot_de_passe'] ?? '';
            $nouveau = $_POST['nouveau_mot_de_passe'] ?? '';
            $confirm = $_POST['comfirme_mot_de_passe'] ?? '';

            $profile = new Profile();

            if ($ancien && $nouveau && $confirm) {
                $user = $profile->findById($id_user);
                if ($user && password_verify($ancien, $user->password)) {
                    if ($nouveau === $confirm) {
                        $profile->updatePassword($id_user, $nouveau);
                        $profile->set_flash("Mot de passe modifié avec succès.", 'success');
                    } else {
                        $profile->set_flash("Les nouveaux mots de passe ne correspondent pas.", 'danger');
                    }
                } else {
                    $profile->set_flash("Ancien mot de passe incorrect.", 'danger');
                }
            } else {
                $profile->set_flash("Veuillez remplir tous les champs.", 'danger');
            }
        }
        $this->view("profile");
    }
public function modifier_image()
{
    $image = new Profile();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['newAvatar']) && $_FILES['newAvatar']['error'] === UPLOAD_ERR_OK) {
        $cheminTemp = $_FILES['newAvatar']['tmp_name'];
        $nomOriginal = $_FILES['newAvatar']['name'];
        $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower(pathinfo($nomOriginal, PATHINFO_EXTENSION));

        if (in_array($extension, $extensionsAutorisees)) {
            $nomFichier = uniqid('profil_', true) . '.' . $extension;
            $destination = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'image_profile' . DIRECTORY_SEPARATOR . $nomFichier;

            if (move_uploaded_file($cheminTemp, $destination)) {
                $_SESSION['image'] = $nomFichier;

                $sql = "UPDATE users SET image = :image WHERE user_id = :id";
                $params = ['image' => $nomFichier, 'id' => $_SESSION['user_id']];
                $image->insertion_update_simples($sql, $params);

                // Redirection pour éviter resoumission formulaire
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            } else {
                    $image->set_flash("Erreur lors de l'enregistrement du fichier.",'danger');
               
            }
        } else {
            $image->set_flash("Format de fichier non autorisé.",'danger');
            
        }
    }

    // Passer les données à la vue
    $this->view("profile");

    
}


}
