<?php

/**
 * Protection CSRF (jeton synchroniseur lie a la session).
 *
 * - Csrf::token()    : retourne (et genere au besoin) le jeton de la session.
 * - Csrf::field()    : champ <input hidden> a inserer dans un formulaire POST.
 * - Csrf::metaTag()  : balise <meta> exposant le jeton au JavaScript.
 * - Csrf::guard()    : a appeler tot dans index.php ; bloque toute requete
 *                      mutante (POST/PUT/PATCH/DELETE) sans jeton valide.
 *
 * Le jeton soumis est accepte soit via le champ POST `csrf_token`,
 * soit via l'en-tete HTTP `X-CSRF-Token` (pour les appels AJAX / fetch).
 */
class Csrf
{
    private const SESSION_KEY = 'csrf_token';
    private const FIELD_NAME = 'csrf_token';
    private const HEADER_KEY = 'HTTP_X_CSRF_TOKEN';

    public static function token(): string
    {
        if (empty($_SESSION[self::SESSION_KEY]) || !is_string($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="' . self::FIELD_NAME . '" value="'
            . htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function metaTag(): string
    {
        return '<meta name="csrf-token" content="'
            . htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function requiresValidation(): bool
    {
        $method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));

        return in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true);
    }

    public static function isValidRequest(): bool
    {
        $sessionToken = $_SESSION[self::SESSION_KEY] ?? '';
        if (!is_string($sessionToken) || $sessionToken === '') {
            return false;
        }

        $submitted = $_POST[self::FIELD_NAME] ?? ($_SERVER[self::HEADER_KEY] ?? '');
        if (!is_string($submitted) || $submitted === '') {
            return false;
        }

        return hash_equals($sessionToken, $submitted);
    }

    public static function guard(): void
    {
        if (!self::requiresValidation() || self::isValidRequest()) {
            return;
        }

        // 403 (et non 419) : code standard reconnu par Apache. Un code non
        // standard comme 419 est remappe en 500 par certaines configurations.
        http_response_code(403);

        $wantsJson = strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest'
            || str_contains(strtolower((string) ($_SERVER['HTTP_ACCEPT'] ?? '')), 'application/json')
            || str_contains(strtolower((string) ($_SERVER['CONTENT_TYPE'] ?? '')), 'application/json');

        if ($wantsJson) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'ok' => false,
                'error' => 'Jeton de securite invalide ou expire. Rechargez la page et reessayez.',
            ]);
            exit;
        }

        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1">'
            . '<title>Action interrompue</title></head><body>'
            . '<div style="font-family:system-ui,Segoe UI,sans-serif;max-width:540px;margin:80px auto;padding:28px;'
            . 'text-align:center;border:1px solid #e2e2e2;border-radius:16px">'
            . '<h1 style="font-size:1.35rem;margin:0 0 12px">Action interrompue</h1>'
            . '<p style="color:#555;line-height:1.6">Votre jeton de securite est invalide ou expire '
            . '(session terminee ou page restee ouverte trop longtemps).</p>'
            . '<p><a href="javascript:history.back()" style="color:#1d4ed8">Revenir en arriere</a>, '
            . 'rechargez la page puis reessayez.</p></div></body></html>';
        exit;
    }
}
