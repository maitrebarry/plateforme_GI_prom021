<?php

class Universites extends Controller
{

    public function index()
    {

        $universite = new Universite();
        $faculte = new Faculte();

        // Ajouter université
        if(isset($_POST['save_universite']))
        {

            $data = [
                "nom_universite" => $_POST['nom_universite']
            ];

            $universite->save_universite($data);

            $universite->set_flash("Université ajoutée avec succès", "success");

            $this->redirect("Universites/index");
        }

        // Ajouter faculté / institut
        if(isset($_POST['save_faculte']))
        {

            $data = [
                "nom_faculte" => $_POST['nom_faculte'],
                "universite_id" => $_POST['universite_id']
            ];

            $faculte->save_faculte($data);

            $faculte->set_flash("Faculté/Institut ajouté avec succès", "success");

            $this->redirect("Universites/index");
        }

        $universites = $universite->SelectAllData("*","universites");
        $facultes = $faculte->SelectAllData("*","facultes");

        $this->view("universiter_filiere",[
            "universites"=>$universites,
            "facultes"=>$facultes
        ]);

    }

    public function getFacultes($universite_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        $faculte = new Faculte();
        $result = $faculte->getFacultesByUniversite((int) $universite_id);
        echo json_encode($result);
    }

}