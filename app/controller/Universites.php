<?php

class Universites extends Controller
{

    public function index()
    {

        $universite = new Universite();
        $filiere = new Filiere();

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

        // Ajouter filiere
        if(isset($_POST['save_filiere']))
        {

            $data = [
                "nom_filiere" => $_POST['nom_filiere'],
                "universite_id" => $_POST['universite_id']
            ];

            $filiere->save_filiere($data);

            $filiere->set_flash("Filière ajoutée avec succès", "success");

            $this->redirect("Universites/index");
        }

        $universites = $universite->SelectAllData("*","universites");
        $filieres = $filiere->SelectAllData("*","filieres");

        $this->view("universiter_filiere",[
            "universites"=>$universites,
            "filieres"=>$filieres
        ]);

    }

}