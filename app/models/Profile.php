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
}