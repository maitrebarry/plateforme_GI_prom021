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
    // Deconnexion uniquement en POST (jeton CSRF valide centralement),
    // pour empecher la deconnexion forcee via un simple lien (CSRF logout).
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect("Homes/index");
    }

    $_SESSION = [];
    session_destroy();
    $this->redirect("Logins/index");
}

    /* ===================== CONNEXION SOCIALE (OAuth) ===================== */

    private function oauthConfig(string $provider): ?array
    {
        $all = defined('OAUTH_PROVIDERS') ? OAUTH_PROVIDERS : [];
        return $all[$provider] ?? null;
    }

    // Demarre le flux : redirige vers le fournisseur, ou message si pas encore configure.
    public function oauth($provider = '')
    {
        $provider = strtolower(preg_replace('/[^a-z]/i', '', (string) $provider));
        $cfg = $this->oauthConfig($provider);
        $model = new Login();

        if (!$cfg) {
            $model->set_flash("Fournisseur de connexion inconnu.", "danger");
            $this->redirect("Logins/index");
        }
        if (empty($cfg['client_id']) || empty($cfg['client_secret'])) {
            $model->set_flash("La connexion " . ($cfg['label'] ?? $provider) . " sera bientôt disponible — configuration en cours.", "info");
            $this->redirect("Logins/index");
        }

        $state = bin2hex(random_bytes(16));
        $_SESSION['oauth_state'] = $state;
        $params = http_build_query([
            'client_id'     => $cfg['client_id'],
            'redirect_uri'  => ROOT . '/Logins/oauth_callback/' . $provider,
            'response_type' => 'code',
            'scope'         => $cfg['scope'],
            'state'         => $state,
        ]);
        header('Location: ' . $cfg['auth_url'] . '?' . $params);
        exit;
    }

    // Retour du fournisseur : echange le code, recupere le profil, connecte (ou cree) l'utilisateur.
    public function oauth_callback($provider = '')
    {
        $provider = strtolower(preg_replace('/[^a-z]/i', '', (string) $provider));
        $cfg = $this->oauthConfig($provider);
        $model = new Login();

        if (!$cfg || empty($cfg['client_id'])) { $this->redirect("Logins/index"); }

        $state = (string) ($_GET['state'] ?? '');
        if (!isset($_SESSION['oauth_state']) || !hash_equals($_SESSION['oauth_state'], $state)) {
            $model->set_flash("Échec de la connexion sociale (état invalide). Réessayez.", "danger");
            $this->redirect("Logins/index");
        }
        unset($_SESSION['oauth_state']);

        $code = (string) ($_GET['code'] ?? '');
        if ($code === '') {
            $model->set_flash("Connexion " . ($cfg['label'] ?? '') . " annulée.", "warning");
            $this->redirect("Logins/index");
        }

        $token = $this->oauthPost($cfg['token_url'], [
            'client_id'     => $cfg['client_id'],
            'client_secret' => $cfg['client_secret'],
            'code'          => $code,
            'redirect_uri'  => ROOT . '/Logins/oauth_callback/' . $provider,
            'grant_type'    => 'authorization_code',
        ]);
        $accessToken = (string) ($token['access_token'] ?? '');
        if ($accessToken === '') { $model->set_flash("Connexion sociale impossible (jeton).", "danger"); $this->redirect("Logins/index"); }

        $info = $this->oauthGet($cfg['userinfo_url'], $accessToken);
        $email = (string) ($info['email'] ?? '');
        $name  = (string) ($info['name'] ?? trim((($info['given_name'] ?? $info['first_name'] ?? '') . ' ' . ($info['family_name'] ?? $info['last_name'] ?? ''))));
        if ($email === '' && $provider === 'github') {
            foreach ((array) $this->oauthGet('https://api.github.com/user/emails', $accessToken) as $em) {
                if (!empty($em['primary']) && !empty($em['email'])) { $email = $em['email']; break; }
            }
        }
        if ($email === '') { $model->set_flash("Impossible de récupérer votre email depuis " . ($cfg['label'] ?? '') . ".", "danger"); $this->redirect("Logins/index"); }

        $this->socialLogin($model, $email, $name !== '' ? $name : strstr($email, '@', true));
    }

    private function socialLogin(Login $model, string $email, string $name): void
    {
        $user = $model->FetchSelectWhere("*", "users", "email = ?", [$email]);
        if (!$user) {
            $parts  = preg_split('/\s+/', trim($name), 2);
            $prenom = $parts[0] ?? 'Utilisateur';
            $nom    = $parts[1] ?? '';
            $pass   = $model->bcript_hash_password(bin2hex(random_bytes(9)));
            $model->insertion_update_simples(
                "INSERT INTO users (nom, prenom, email, password, role, statut_compte) VALUES (?,?,?,?,?,?)",
                [$nom, $prenom, $email, $pass, 'etudiant', 'actif']
            );
            $user = $model->FetchSelectWhere("*", "users", "email = ?", [$email]);
        }
        if (!$user) { $model->set_flash("Création du compte impossible.", "danger"); $this->redirect("Logins/index"); }
        if (($user->statut_compte ?? 'actif') !== 'actif') { $model->set_flash("Votre compte est bloqué.", "warning"); $this->redirect("Logins/index"); }

        session_regenerate_id(true);
        $_SESSION['user_id']    = $user->user_id;
        $_SESSION['nom']        = $user->nom;
        $_SESSION['prenom']     = $user->prenom;
        $_SESSION['email']      = $user->email;
        $_SESSION['universite'] = $user->universite ?? null;
        $_SESSION['faculte']    = $user->faculte ?? null;
        $_SESSION['filiere']    = $user->filiere ?? null;
        $_SESSION['role']       = $user->role;
        $_SESSION['contact']    = $user->contact ?? null;
        $_SESSION['image']      = $user->image ?? 'default.png';
        $this->redirect("Homes/dashboard");
    }

    private function oauthPost(string $url, array $data): array
    {
        if (!function_exists('curl_init')) { return []; }
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
            CURLOPT_TIMEOUT => 15,
        ]);
        $res = curl_exec($ch);
        curl_close($ch);
        $json = json_decode((string) $res, true);
        return is_array($json) ? $json : [];
    }

    private function oauthGet(string $url, string $token): array
    {
        if (!function_exists('curl_init')) { return []; }
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token, 'Accept: application/json', 'User-Agent: NGAKODON'],
            CURLOPT_TIMEOUT => 15,
        ]);
        $res = curl_exec($ch);
        curl_close($ch);
        $json = json_decode((string) $res, true);
        return is_array($json) ? $json : [];
    }

}
