<?php

function detect_app_scheme(): string
{
    $forwardedProto = strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''));
    $forwardedSsl = strtolower((string) ($_SERVER['HTTP_X_FORWARDED_SSL'] ?? ''));

    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || $forwardedProto === 'https'
        || $forwardedSsl === 'on';

    return $isHttps ? 'https' : 'http';
}

function detect_app_base_url(): string
{
    $scheme = detect_app_scheme();
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '')));
    $scriptDir = trim($scriptDir, '/');

    if ($scriptDir === '') {
        return $scheme . '://' . $host;
    }

    if (substr($scriptDir, -6) === 'public') {
        $scriptDir = trim(substr($scriptDir, 0, -6), '/');
    }

    return $scheme . '://' . $host . ($scriptDir !== '' ? '/' . $scriptDir : '');
}

if (!defined('ROOT_IMG')) {
    define('ROOT_IMG', rtrim(detect_app_base_url(), '/'));
}

if (!defined('ROOT')) {
    define('ROOT', ROOT_IMG . '/public');
}

define( 'APP_NAME', 'Plateforme GI Promo 21' );

define( 'DB_NAME', 'plateforme_gi_promo21' );
define( 'DBHOST', 'localhost' );
define( 'DB_USERNAME', 'root' );
define( 'DB_PASSWORD', '' );

// Definissez HF_API_TOKEN dans votre environnement local au lieu de le commiter.
define('HF_API_TOKEN', getenv('HF_API_TOKEN') ?: '');
define('HF_MODEL', getenv('HF_MODEL') ?: 'Qwen/Qwen2.5-7B-Instruct');
