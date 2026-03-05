<?php

class Controller
{
    public function view(string $view, array $data = []): void
    {
        $filename = 'app/views/' . $view . '.view.php';

        if (!file_exists($filename)) {
            http_response_code(404);
            require 'app/views/404.view.php';
            return;
        }

        extract($data);
        require $filename;
    }

    public function redirect(string $page): void
    {
        header('Location: ' . ROOT . '/' . trim($page, '/'));
        exit;
    }
}
