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

define( 'APP_NAME', 'NGAKODON' );

/*
|--------------------------------------------------------------------------
| Base de donnees
|--------------------------------------------------------------------------
| En LOCAL (XAMPP) : rien a modifier, les valeurs par defaut suffisent.
|
| En PRODUCTION : ne modifiez PAS ce bloc. Definissez les identifiants soit
| via des VARIABLES D'ENVIRONNEMENT (DB_HOST, DB_NAME, DB_USER,
| DB_PASSWORD), soit (plus simple sur un hebergement mutualise type LWS) via
| le fichier app/core/config.local.php (non versionne, voir
| config.local.php.example).
*/
$dbHost = 'localhost';
$dbName = 'plateforme_gi_promo21';
$dbUser = 'root';
$dbPass = '';

$dbHost = getenv('DB_HOST') ?: $dbHost;
$dbName = getenv('DB_NAME') ?: $dbName;
$dbUser = getenv('DB_USER') ?: $dbUser;
if (getenv('DB_PASSWORD') !== false) {
    $dbPass = getenv('DB_PASSWORD');
}

$hfApiToken = getenv('HF_API_TOKEN') ?: '';
$hfModel    = getenv('HF_MODEL') ?: 'Qwen/Qwen2.5-7B-Instruct';

// Surcharge par fichier local (NON versionne, PRIORITAIRE sur l'env).
$localConfig = __DIR__ . '/config.local.php';
if (is_readable($localConfig)) {
    require $localConfig; // peut redefinir $dbHost, $dbName, $dbUser, $dbPass, $hfApiToken, $hfModel
}

define('DB_NAME', $dbName);
define('DBHOST', $dbHost);
define('DB_USERNAME', $dbUser);
define('DB_PASSWORD', $dbPass);

// Definissez HF_API_TOKEN dans votre environnement local au lieu de le commiter.
define('HF_API_TOKEN', $hfApiToken);
define('HF_MODEL', $hfModel);

/* =========================================================
   CONNEXION SOCIALE (OAuth) — renseignez le client_id / client_secret
   de chaque fournisseur pour l'activer. Tant qu'ils sont vides, le bouton
   affiche « bientot disponible ». URI de redirection a declarer chez le
   fournisseur : <ROOT>/Logins/oauth_callback/<provider>  (HTTPS requis -> ngrok).
   ========================================================= */
if (!defined('OAUTH_PROVIDERS')) {
    define('OAUTH_PROVIDERS', [
        'google' => [
            'label' => 'Google', 'icon' => 'bxl-google', 'color' => '#ea4335',
            'client_id' => getenv('GOOGLE_CLIENT_ID') ?: '',
            'client_secret' => getenv('GOOGLE_CLIENT_SECRET') ?: '',
            'auth_url' => 'https://accounts.google.com/o/oauth2/v2/auth',
            'token_url' => 'https://oauth2.googleapis.com/token',
            'userinfo_url' => 'https://www.googleapis.com/oauth2/v3/userinfo',
            'scope' => 'openid email profile',
        ],
        'facebook' => [
            'label' => 'Facebook', 'icon' => 'bxl-facebook-circle', 'color' => '#1877f2',
            'client_id' => getenv('FACEBOOK_CLIENT_ID') ?: '',
            'client_secret' => getenv('FACEBOOK_CLIENT_SECRET') ?: '',
            'auth_url' => 'https://www.facebook.com/v19.0/dialog/oauth',
            'token_url' => 'https://graph.facebook.com/v19.0/oauth/access_token',
            'userinfo_url' => 'https://graph.facebook.com/me?fields=id,name,first_name,last_name,email',
            'scope' => 'email public_profile',
        ],
        'github' => [
            'label' => 'GitHub', 'icon' => 'bxl-github', 'color' => '#24292f',
            'client_id' => getenv('GITHUB_CLIENT_ID') ?: '',
            'client_secret' => getenv('GITHUB_CLIENT_SECRET') ?: '',
            'auth_url' => 'https://github.com/login/oauth/authorize',
            'token_url' => 'https://github.com/login/oauth/access_token',
            'userinfo_url' => 'https://api.github.com/user',
            'scope' => 'read:user user:email',
        ],
    ]);
}
