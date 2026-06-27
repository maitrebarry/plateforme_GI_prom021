<?php

declare(strict_types=1);

date_default_timezone_set('Africa/Abidjan');

// Durcissement du cookie de session : HttpOnly (anti-vol via XSS),
// SameSite=Lax (defense CSRF), Secure quand on est en HTTPS.
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off'),
]);
session_start();

require_once __DIR__ . '/app/core/autoload.php';

// Pages dynamiques : ne pas mettre en cache (le navigateur recupere
// toujours la derniere version, evite de "ne pas voir" les changements).
header('Cache-Control: no-cache, must-revalidate');
header('Expires: 0');

// Verifie le jeton CSRF sur toute requete mutante (POST/PUT/PATCH/DELETE)
// avant le dispatch vers le controleur.
Csrf::guard();

new App();
