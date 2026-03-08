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

 public function ajouter_utilisateur()
{
    $utilisateur = new Utilisateur();
    $universiteModel = new Universite();
    $faculteModel = new Faculte();

    $universiteModel->seedDefaultUniversitesIfEmpty();

    // Récupérer toutes les universités pour le select
    $universites = $universiteModel->getAllUniversites();

    if(isset($_POST['submit']))
    {
        if($_POST['password'] != $_POST['password_confirm'])
        {
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
            $utilisateur->set_flash("Veuillez renseigner votre filière.", "danger");
            $this->redirect("Utilisateurs/ajouter_utilisateur");
            return;
        }

        $universiteNom = '';
        $faculteNom = '';

        if (($universiteId ?? 0) > 0) {
            $universite = $universiteModel->getUniversiteById($universiteId);

            if (!$universite) {
                $utilisateur->set_flash("Université invalide.", "danger");
                $this->redirect("Utilisateurs/ajouter_utilisateur");
                return;
            }

            $universiteNom = $universite->nom_universite;

            if (($faculteId ?? 0) > 0) {
                $faculte = $faculteModel->getFaculteByIdAndUniversite($faculteId, $universiteId);

                if (!$faculte) {
                    $utilisateur->set_flash("Faculté/Institut invalide pour cette université.", "danger");
                    $this->redirect("Utilisateurs/ajouter_utilisateur");
                    return;
                }

                $faculteNom = $faculte->nom_faculte;
            }
        } else {
            if ($autreEtablissement === '') {
                $utilisateur->set_flash("Veuillez saisir le nom de votre établissement.", "danger");
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

        if($insert)
        {
            $utilisateur->set_flash("Compte créé avec succès", "success");
        }
    }

    // Passer les universités au view
    $this->view("register", ['universites' => $universites]);
}
public function liste_utilisateur()
{
    $utilisateur = new Utilisateur();
    $universiteModel = new Universite();
    $faculteModel = new Faculte();

    $universiteModel->seedDefaultUniversitesIfEmpty();
    $universites = $universiteModel->getAllUniversites();

      if(isset($_POST['save_user']))
        {
            if($_POST['password'] != $_POST['password_confirm'])
            {
                $utilisateur->set_flash("Les mots de passe ne correspondent pas", "danger");
                $this->redirect("Utilisateurs/liste_utilisateur");
                return;
            }

            if (!$this->isValidContact($_POST['contact_utilisateur'] ?? '')) {
                $utilisateur->set_flash("Le contact doit contenir 8 chiffres, par exemple 76 56 23 17.", "danger");
                $this->redirect("Utilisateurs/liste_utilisateur");
                return;
            }

            $universiteId = isset($_POST['universite_id']) && $_POST['universite_id'] !== '' ? (int) $_POST['universite_id'] : null;
            $faculteId = isset($_POST['faculte_id']) && $_POST['faculte_id'] !== '' ? (int) $_POST['faculte_id'] : null;

            if (($universiteId ?? 0) <= 0) {
                $utilisateur->set_flash("Veuillez sélectionner une université.", "danger");
                $this->redirect("Utilisateurs/liste_utilisateur");
                return;
            }

            if (($faculteId ?? 0) <= 0) {
                $utilisateur->set_flash("Veuillez sélectionner une faculté ou un institut.", "danger");
                $this->redirect("Utilisateurs/liste_utilisateur");
                return;
            }

            $universite = $universiteModel->getUniversiteById($universiteId);
            $faculte = $faculteModel->getFaculteByIdAndUniversite($faculteId, $universiteId);

            if (!$universite || !$faculte) {
                $utilisateur->set_flash("Université ou faculté invalide.", "danger");
                $this->redirect("Utilisateurs/liste_utilisateur");
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

            if($insert)
            {
                $utilisateur->set_flash("Compte créé avec succès", "success");
            }
        }
    // Requête simplifiée pour récupérer les utilisateurs uniquement
    $select = "
        SELECT 
            user_id,
            nom,
            prenom,
            email,
            role,
                universite,
                faculte,
              filiere
        FROM users
    ";

    $liste = $utilisateur->select_data_table_join_where($select);

    // Appel de la vue avec uniquement les utilisateurs
    $this->view('liste_utilisateur', ['liste' => $liste, 'universites' => $universites]);
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