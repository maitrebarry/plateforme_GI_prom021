<?php

class Utilisateurs extends Controller
{

 public function ajouter_utilisateur()
{
    $utilisateur = new Utilisateur();
    $universiteModel = new Universite();

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

        $data = [
            "prenom" => $_POST['prenom'],
            "nom" => $_POST['nom'],
            "email" => $_POST['email'],
            "universite_id" => $_POST['universite_id'],
            "filiere_id" => $_POST['filiere_id'],
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
      if(isset($_POST['save_user']))
        {
//echo "ok";exit;
            if($_POST['password'] != $_POST['password_confirm'])
            {
                $utilisateur->set_flash("Les mots de passe ne correspondent pas", "danger");
                $this->redirect("Utilisateurs/liste_utilisateur");
                return;
            }

            $data = [
                "prenom" => $_POST['prenom'],
                "nom" => $_POST['nom'],
                "email" => $_POST['email'],
                "universite" => $_POST['universite'],
                "role" => $_POST['role'],
                 "contact" => $_POST['contact_utilisateur'],
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
              filiere
        FROM users
    ";

    $liste = $utilisateur->select_data_table_join_where($select);

    // Appel de la vue avec uniquement les utilisateurs
    $this->view('liste_utilisateur', ['liste' => $liste]);
}
public function getFilieres($universite_id)
{

$filiere = new Filiere();

$result = $filiere->FetchSelectWhere(
"*",
"filieres",
"universite_id = ?",
[$universite_id]
);

echo json_encode($result);

}
}