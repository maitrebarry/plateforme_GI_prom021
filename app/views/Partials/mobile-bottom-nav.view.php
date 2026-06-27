<?php
$nkUrl = strtolower(trim((string) ($_GET['url'] ?? ''), '/'));
$nkActive = static function ($keys) use ($nkUrl): string {
    foreach ((array) $keys as $k) {
        if ($k === '' && ($nkUrl === '' || $nkUrl === 'homes' || $nkUrl === 'homes/index')) {
            return 'is-active';
        }
        if ($k !== '' && str_contains($nkUrl, $k)) {
            return 'is-active';
        }
    }
    return '';
};
$nkLogged = isset($_SESSION['user_id']);
?>
<nav class="nk-bottomnav" aria-label="Navigation mobile">
    <a href="<?= ROOT ?>/Homes/index" class="nk-bottomnav__item <?= $nkActive('') ?>">
        <i class='bx bx-home-alt-2'></i><span>Accueil</span>
    </a>
    <a href="<?= ROOT ?>/Homes/projects" class="nk-bottomnav__item <?= $nkActive(['projects', 'projets']) ?>">
        <i class='bx bx-grid-alt'></i><span>Projets</span>
    </a>
    <button type="button" class="nk-bottomnav__fab" data-nk-open-chat aria-label="Ouvrir l'assistant IA">
        <i class='bx bx-bot'></i>
    </button>
    <a href="<?= ROOT ?>/Homes/departement" class="nk-bottomnav__item <?= $nkActive('departement') ?>">
        <i class='bx bx-news'></i><span>Dépt.</span>
    </a>
    <?php if ($nkLogged): ?>
        <a href="<?= ROOT ?>/Homes/dashboard" class="nk-bottomnav__item <?= $nkActive(['dashboard', 'profile', 'appercu', 'mes_projets', 'messages_recus', 'der_']) ?>">
            <i class='bx bx-user-circle'></i><span>Espace</span>
        </a>
    <?php else: ?>
        <a href="<?= ROOT ?>/Homes/login" class="nk-bottomnav__item <?= $nkActive(['login', 'logins', 'register', 'utilisateurs']) ?>">
            <i class='bx bx-user-circle'></i><span>Compte</span>
        </a>
    <?php endif; ?>
</nav>
<script>
    (function () {
        document.addEventListener('click', function (e) {
            var t = e.target.closest ? e.target.closest('[data-nk-open-chat]') : null;
            if (!t) { return; }
            e.preventDefault();
            var toggle = document.querySelector('[data-nk-chatbot] [data-nk-chat-toggle]');
            if (toggle) { toggle.click(); }
        });
    })();
</script>
