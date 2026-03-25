<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/App.php';

spl_autoload_register(function ($class): void {
    $modelPath = __DIR__ . '/../models/' . ucfirst($class) . '.php';
    if (file_exists($modelPath)) {
        require_once $modelPath;
        return;
    }

    $servicePath = __DIR__ . '/../services/' . ucfirst($class) . '.php';
    if (file_exists($servicePath)) {
        require_once $servicePath;
    }
});
