<?php

class Login extends Model
{

    public function connecter()
    {

        $email = filter_var($_POST['email'] ?? null, FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? null;

        if(empty($email) || empty($password))
        {
            $this->set_flash("Veuillez remplir tous les champs", "danger");
            return false;
        }

        // chercher utilisateur
        $utilisateur = $this->FetchSelectWhere( "*", "users", "email = ?", [$email]  );

        if(!$utilisateur)
        {
            $this->set_flash("Email incorrect", "danger");
            return false;
        }

        // vérifier mot de passe
        if(password_verify($password, $utilisateur->password))
        {

            $_SESSION['user_id'] = $utilisateur->user_id;
            $_SESSION['nom'] = $utilisateur->nom;
            $_SESSION['prenom'] = $utilisateur->prenom;
            $_SESSION['email'] = $utilisateur->email;
            $_SESSION['universite'] = $utilisateur->universite;
            $_SESSION['faculte'] = $utilisateur->faculte ?? null;
            $_SESSION['filiere'] = $utilisateur->filiere;
            $_SESSION['role'] = $utilisateur->role;
            $_SESSION['contact'] = $utilisateur->contact;
                $_SESSION['image'] = $utilisateur->image ?? 'default.png';
    
               
            $this->redirect("Homes/dashboard");
        }
        else
        {
            $this->set_flash("Mot de passe incorrect", "danger");
        }

    }

}