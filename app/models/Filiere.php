<?php

class Filiere extends model
{

    public function save_filiere($data)
    {

        $sql = "INSERT INTO filieres (nom_filiere, universite_id)
                VALUES (:nom_filiere, :universite_id)";

        $params = [
            ":nom_filiere" => $data['nom_filiere'],
            ":universite_id" => $data['universite_id']
        ];

        return $this->insertion_update_simples($sql, $params);

    }

}