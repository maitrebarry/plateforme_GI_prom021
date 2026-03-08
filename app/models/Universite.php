<?php

class Universite extends model
{

    public function seedDefaultUniversitesIfEmpty(): void
    {
        $existing = $this->select_data_table_join_where(
            "SELECT id_universite FROM universites LIMIT 1"
        );

        if (!empty($existing)) {
            return;
        }

        $defaults = [
            ['Université des Sciences, des Techniques et des Technologies de Bamako (USTTB)', 'universite_publique'],
            ['Université des Sciences Sociales et de Gestion de Bamako (USSGB)', 'universite_publique'],
            ['Université des Sciences Juridiques et Politiques de Bamako (USJPB)', 'universite_publique'],
            ['Université des Lettres et des Sciences Humaines de Bamako (ULSHB)', 'universite_publique'],
            ['Université de Ségou', 'universite_publique'],
            ['Centre de Recherche et de Formation pour l’Industrie Textile (CERFITEX)', 'grande_ecole'],
            ['Ecole Nationale d’Ingénieurs / Abderhamane Baba Touré (ENI-ABT)', 'grande_ecole'],
            ['Ecole Normale d’Enseignement Technique et Professionnel (ENETP)', 'grande_ecole'],
            ['Ecole Normale Supérieure (ENSUP)', 'grande_ecole'],
            ['Ecole Supérieure de Journalisme et des Sciences de la Communication (ESJSC)', 'grande_ecole'],
            ['Institut de Pédagogie Universitaire (IPU)', 'grande_ecole'],
            ['Institut National de Formation des Travailleurs Sociaux (INFTS)', 'grande_ecole'],
            ['Institut National de Formation en Sciences de la Santé (INFSS)', 'grande_ecole'],
            ['Institut National de la Jeunesse et des Sports (INJS)', 'grande_ecole'],
            ['Institut Polytechnique Rural de Formation et de Recherche Appliquée (IPR/IFRA)', 'grande_ecole'],
            ['Institut Zayed des Sciences Economiques et Juridiques (IZSEJ)', 'grande_ecole'],
        ];

        foreach ($defaults as $item) {
            $this->insertion_update_simples(
                "INSERT INTO universites (nom_universite, type_etablissement) VALUES (:nom_universite, :type_etablissement)",
                [
                    ':nom_universite' => $item[0],
                    ':type_etablissement' => $item[1],
                ]
            );
        }
    }

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
        return $this->select_data_table_join_where(
            "SELECT * FROM universites ORDER BY nom_universite ASC"
        );
    }

    public function getUniversiteById(int $universite_id)
    {
        return $this->FetchSelectWhere(
            "*",
            "universites",
            "id_universite = ?",
            [$universite_id]
        );
    }

    public function getFacultesByUniversite(int $universite_id)
    {
        return $this->select_data_table_join_where(
            "SELECT id_faculte, nom_faculte FROM facultes WHERE universite_id = ? ORDER BY nom_faculte ASC",
            [$universite_id]
        );
    }

}