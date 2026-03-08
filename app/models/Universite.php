<?php

class Universite extends model
{

    public function save_universite($data)
    {

        $sql = "INSERT INTO universites (nom_universite)
                VALUES (:nom_universite)";

        $params = [
            ":nom_universite" => $data['nom_universite']
        ];

        return $this->insertion_update_simples($sql, $params);

    }
    

    public function getAllUniversites()
    {
        return $this->SelectAllData("*", "universites");
    }

    public function getFilieresByUniversite($universite_id)
    {
        return $this->FetchSelectWhere(
            "*",
            "filieres",
            "universite_id = ?",
            [$universite_id]
        );
    
}

}