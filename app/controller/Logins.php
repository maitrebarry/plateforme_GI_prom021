<?php

class Logins extends Controller
{

    public function index()
    {

        $login = new Login();

        if(isset($_POST['submit']))
        {
             //echo "ok";exit;
            $login->connecter();
        }

        $this->view("login");
    }
    public function logout()
{
    session_destroy();
    $this->redirect("logins/index");
}

}