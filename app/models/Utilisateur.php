<?php

class Utilisateur extends Model
{
    public $errors = [];

    public function ensureDefaultAdminAccount(): void
    {
        $defaultEmail = 'barrymoustapha485@gmail.com';

        $existing = $this->FetchSelectWhere(
            "*",
            "users",
            "email = ?",
            [$defaultEmail]
        );

        if (!empty($existing)) {
            return;
        }

        $defaultPassword = password_hash('Admin@2026', PASSWORD_DEFAULT);

        $sql = "INSERT INTO users
                (prenom, nom, email, universite, role, contact, password)
                VALUES
                (:prenom, :nom, :email, :universite, :role, :contact, :password)";

        $params = [
            ':prenom' => 'Barry',
            ':nom' => 'Moustapha',
            ':email' => $defaultEmail,
            ':universite' => 'Administration',
            ':role' => 'admin',
            ':contact' => '',
            ':password' => $defaultPassword,
        ];

        $this->insertion_update_simples($sql, $params);
    }

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
            (prenom, nom, email, universite_id, faculte_id, universite, faculte, filiere, autre_etablissement, autre_departement, password)
            VALUES
            (:prenom, :nom, :email, :universite_id, :faculte_id, :universite, :faculte, :filiere, :autre_etablissement, :autre_departement, :password)";

    $params = [
        ":prenom" => $data['prenom'],
        ":nom" => $data['nom'],
        ":email" => $data['email'],
        ":universite_id" => $data['universite_id'],
        ":faculte_id" => $data['faculte_id'],
        ":universite" => $data['universite'],
        ":faculte" => $data['faculte'],
        ":filiere" => $data['filiere'],
        ":autre_etablissement" => $data['autre_etablissement'],
        ":autre_departement" => $data['autre_departement'],
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
                (prenom, nom, email, universite_id, faculte_id, universite, faculte, role, contact, password)
                VALUES
                (:prenom, :nom, :email, :universite_id, :faculte_id, :universite, :faculte, :role, :contact, :password)";

        $params = [
            ":prenom" => $data['prenom'],
            ":nom" => $data['nom'],
            ":email" => $data['email'],
            ":universite_id" => $data['universite_id'],
            ":faculte_id" => $data['faculte_id'],
            ":universite" => $data['universite'],
            ":faculte" => $data['faculte'],
            ":role" => $data['role'],
            ":contact" => $data['contact'],
            ":password" => $password
        ];

        return $this->insertion_update_simples($sql, $params);
    }
}