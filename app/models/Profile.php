<?php

class Profile extends model
{
    public function updatePassword($id_user, $new_password)
    {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = :pwd WHERE user_id = :id";
        $params = [
            ':pwd' => $hash,
            ':id' => $id_user
        ];
        return $this->insertion_update_simples($sql, $params);
    }
      
public function findById($id_user)
{
    return $this->SelectOne("*", "users", "user_id = ?", [$id_user]);
}

public function emailExistsForOther(string $email, int $userId): bool
{
    $result = $this->FetchSelectWhere("user_id", "users", "email = ? AND user_id != ?", [$email, $userId]);
    return !empty($result);
}

public function updateUserInfo(int $userId, array $data): bool
{
    $sql = "UPDATE users SET prenom = :prenom, nom = :nom, email = :email, contact = :contact, universite = :universite, faculte = :faculte, filiere = :filiere WHERE user_id = :id";

    $params = [
        ':prenom' => $data['prenom'] ?? null,
        ':nom' => $data['nom'] ?? null,
        ':email' => $data['email'] ?? null,
        ':contact' => $data['contact'] ?? null,
        ':universite' => $data['universite'] ?? null,
        ':faculte' => $data['faculte'] ?? null,
        ':filiere' => $data['filiere'] ?? null,
        ':id' => $userId,
    ];

    return (bool) $this->insertion_update_simples($sql, $params);
}
}