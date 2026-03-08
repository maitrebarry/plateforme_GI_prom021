<?php

class Utilisateur extends Model
{
    public $errors = [];

   public function save_utilisateur($data)
{
    $check = $this->FetchSelectWhere(
        "*",
        "users",
        "email = ?",
        [$data['email']]
    );

    if (!empty($check)) {
        $this->set_flash("Cet email existe déjà", "warning");
        return false;
    }

    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users
            (prenom, nom, email, universite_id, filiere_id, password)
            VALUES
            (:prenom, :nom, :email, :universite_id, :filiere_id, :password)";

    $params = [
        ":prenom" => $data['prenom'],
        ":nom" => $data['nom'],
        ":email" => $data['email'],
        ":universite_id" => $data['universite_id'],
        ":filiere_id" => $data['filiere_id'],
        ":password" => $password
    ];

    return $this->insertion_update_simples($sql, $params);
}
    public function save_utilisateur_admin($data)
    {
        // Vérifier si email existe déjà
        $check = $this->FetchSelectWhere(
            "*",
            "users",
            "email = ?",
            [$data['email']]
        );

        if (!empty($check)) {
            $this->set_flash("Cet email existe déjà", "warning");
            return false;
        }

        // Hash mot de passe
        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO users
                (prenom, nom, email, universite, role, contact, password)
                VALUES
                (:prenom, :nom, :email, :universite, :role, :contact, :password)";

        $params = [
            ":prenom" => $data['prenom'],
            ":nom" => $data['nom'],
            ":email" => $data['email'],
            ":universite" => $data['universite'],
            ":role" => $data['role'],
            ":contact" => $data['contact'],
            ":password" => $password
        ];

        return $this->insertion_update_simples($sql, $params);
    }
}