<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= Csrf::metaTag() ?>
    <title><?= htmlspecialchars($pageTitle) ?> - <?= APP_NAME ?></title>

    <script>
        // CSRF : expose le jeton et l'injecte automatiquement dans tout
        // formulaire POST au moment de la soumission (phase de capture).
        (function () {
            var meta = document.querySelector('meta[name="csrf-token"]');
            var token = meta ? meta.getAttribute('content') : '';
            window.CSRF_TOKEN = token;
            if (!token) { return; }

            document.addEventListener('submit', function (event) {
                var form = event.target;
                if (!form || form.tagName !== 'FORM') { return; }
                if ((form.getAttribute('method') || 'get').toLowerCase() !== 'post') { return; }

                var input = form.querySelector('input[name="csrf_token"]');
                if (!input) {
                    input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'csrf_token';
                    form.appendChild(input);
                }
                input.value = token;
            }, true);

            // Deconnexion : transforme le clic sur un lien [data-logout] en POST signe CSRF.
            document.addEventListener('click', function (event) {
                var link = event.target.closest ? event.target.closest('[data-logout]') : null;
                if (!link) { return; }
                event.preventDefault();

                var form = document.createElement('form');
                form.method = 'POST';
                form.action = link.getAttribute('href');

                var field = document.createElement('input');
                field.type = 'hidden';
                field.name = 'csrf_token';
                field.value = token;
                form.appendChild(field);

                document.body.appendChild(form);
                form.submit();
            }, true);
        })();
    </script>

    <script>
        // Theme clair/sombre : applique le theme sauvegarde (ou la preference
        // systeme) AVANT le rendu pour eviter tout flash, et gere la bascule.
        (function () {
            var KEY = 'ngakodon-theme';
            var saved = null;
            try { saved = localStorage.getItem(KEY); } catch (e) {}
            var theme = saved || ((window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);

            function syncIcons(t) {
                document.querySelectorAll('[data-theme-toggle] i').forEach(function (ic) {
                    ic.className = (t === 'dark') ? 'bx bx-sun' : 'bx bx-moon';
                });
                document.querySelectorAll('[data-theme-label]').forEach(function (el) {
                    el.textContent = (t === 'dark') ? 'Mode clair' : 'Mode sombre';
                });
            }
            function setTheme(t) {
                document.documentElement.setAttribute('data-theme', t);
                try { localStorage.setItem(KEY, t); } catch (e) {}
                syncIcons(t);
            }
            document.addEventListener('click', function (e) {
                var btn = e.target.closest ? e.target.closest('[data-theme-toggle]') : null;
                if (!btn) { return; }
                e.preventDefault();
                setTheme(document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
            });
            document.addEventListener('DOMContentLoaded', function () {
                syncIcons(document.documentElement.getAttribute('data-theme'));
            });

            // --- Couleur de theme (vert/bleu/orange/violet), independante du mode clair/sombre ---
            var CKEY = 'ngakodon-color';
            var savedColor = null;
            try { savedColor = localStorage.getItem(CKEY); } catch (e) {}
            if (savedColor) { document.documentElement.setAttribute('data-color', savedColor); }

            function syncColor(c) {
                document.querySelectorAll('[data-color-value]').forEach(function (s) {
                    s.classList.toggle('is-active', s.getAttribute('data-color-value') === (c || 'green'));
                });
            }
            document.addEventListener('click', function (e) {
                var sw = e.target.closest ? e.target.closest('[data-color-value]') : null;
                if (sw) {
                    e.preventDefault();
                    var c = sw.getAttribute('data-color-value');
                    document.documentElement.setAttribute('data-color', c);
                    try { localStorage.setItem(CKEY, c); } catch (e2) {}
                    syncColor(c);
                    document.querySelectorAll('[data-color-menu]').forEach(function (m) { m.classList.remove('is-open'); });
                    return;
                }
                var ct = e.target.closest ? e.target.closest('[data-color-toggle]') : null;
                if (ct) {
                    e.preventDefault();
                    var menu = ct.parentNode.querySelector('[data-color-menu]');
                    var willOpen = menu && !menu.classList.contains('is-open');
                    document.querySelectorAll('[data-color-menu]').forEach(function (m) { m.classList.remove('is-open'); });
                    if (willOpen) { menu.classList.add('is-open'); }
                    return;
                }
                document.querySelectorAll('[data-color-menu].is-open').forEach(function (m) { m.classList.remove('is-open'); });
            });
            document.addEventListener('DOMContentLoaded', function () {
                syncColor(document.documentElement.getAttribute('data-color') || 'green');
            });
        })();
    </script>

    <link rel="shortcut icon" href="<?= ROOT ?>/assets/images/logo/n'kakodon.png">
    <!-- PWA : application installable (mobile + desktop) -->
    <link rel="manifest" href="<?= ROOT ?>/manifest.webmanifest">
    <meta name="theme-color" content="#157f5a">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="NGAKODON">
    <link rel="apple-touch-icon" href="<?= ROOT ?>/assets/icons/nk-180.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/slick.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/magnific-popup.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/line-awesome.min.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/public-professional.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/nkadon-chatbot.css">
    <link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
    <!-- Design system unifie (vert NGAKODON + theme sombre) : charge en dernier pour primer -->
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/design-system.css?v=17">
</head>
