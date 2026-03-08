<?php

class Faculte extends Model
{
    public function save_faculte(array $data)
    {
        $sql = "INSERT INTO facultes (nom_faculte, universite_id)
                VALUES (:nom_faculte, :universite_id)";

        $params = [
            ':nom_faculte' => $data['nom_faculte'],
            ':universite_id' => $data['universite_id'],
        ];

        return $this->insertion_update_simples($sql, $params);
    }

    public function getFacultesByUniversite(int $universite_id): array
    {
        return $this->select_data_table_join_where(
            "SELECT id_faculte, nom_faculte FROM facultes WHERE universite_id = ? ORDER BY nom_faculte ASC",
            [$universite_id]
        );
    }

    public function getFaculteByIdAndUniversite(int $faculte_id, int $universite_id)
    {
        return $this->FetchSelectWhere(
            '*',
            'facultes',
            'id_faculte = ? AND universite_id = ?',
            [$faculte_id, $universite_id]
        );
    }
}
