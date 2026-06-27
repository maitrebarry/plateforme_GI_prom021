<!-- PWA : enregistrement du service worker + invite d'installation -->
<div id="nkInstall" class="nk-install" role="dialog" aria-label="Installer l'application">
    <img src="<?= ROOT ?>/assets/icons/nk-192.png" alt="" class="nk-install__ico">
    <div class="nk-install__txt">
        <b>Installer NGAKODON</b>
        <span>Accès rapide, plein écran, hors-ligne</span>
    </div>
    <button type="button" id="nkInstallBtn" class="nk-install__go"><i class='bx bx-download'></i> <span>Installer</span></button>
    <button type="button" id="nkInstallX" class="nk-install__x" aria-label="Fermer">&times;</button>
</div>
<style>
    .nk-install { position: fixed; left: 12px; right: 12px; bottom: 14px; z-index: 1300; display: none; align-items: center; gap: 12px;
        background: var(--ds-surface, #fff); border: 1px solid var(--ds-border, #e2e2e2); border-radius: 16px; padding: 11px 13px;
        box-shadow: 0 18px 44px -14px rgba(0,0,0,.34); max-width: 460px; margin: 0 auto; }
    .nk-install.is-shown { display: flex; }
    .nk-install__ico { width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0; }
    .nk-install__txt { flex: 1; min-width: 0; line-height: 1.3; }
    .nk-install__txt b { display: block; color: var(--ds-ink-strong, #14211c); font-size: .92rem; font-weight: 800; }
    .nk-install__txt span { color: var(--ds-muted, #5f6b66); font-size: .78rem; }
    .nk-install__go { display: inline-flex; align-items: center; gap: 5px; background: var(--ds-brand-600, #157f5a); color: #fff; border: 0; border-radius: 999px; font-weight: 700; font-size: .86rem; padding: 9px 16px; cursor: pointer; flex-shrink: 0; }
    .nk-install__go:hover { background: var(--ds-brand-700, #0f6647); }
    .nk-install__x { background: transparent; border: 0; color: var(--ds-muted, #5f6b66); font-size: 1.5rem; line-height: 1; cursor: pointer; padding: 0 4px; flex-shrink: 0; }
    .public-site .nk-install, .um6p-site .nk-install { bottom: 76px; }
    @media (min-width: 992px) { .nk-install, .public-site .nk-install, .um6p-site .nk-install { left: auto; right: 20px; bottom: 20px; width: 380px; } }
</style>
<script>
    (function () {
        // 1) Service worker (necessaire a l'installabilite)
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('<?= ROOT ?>/sw.js').catch(function () {});
            });
        }

        var banner = document.getElementById('nkInstall');
        if (!banner) { return; }
        var goBtn  = document.getElementById('nkInstallBtn');
        var xBtn   = document.getElementById('nkInstallX');
        var titleEl = banner.querySelector('.nk-install__txt b');
        var subEl   = banner.querySelector('.nk-install__txt span');
        var goLabel = goBtn ? goBtn.querySelector('span') : null;

        var deferred = null;
        var KEY = 'nkadon-install-dismissed';
        var isStandalone = (window.matchMedia && window.matchMedia('(display-mode: standalone)').matches) || window.navigator.standalone === true;
        var ua = navigator.userAgent || '';
        var isiOS = /iphone|ipad|ipod/i.test(ua) || (/Macintosh/.test(ua) && (navigator.maxTouchPoints || 0) > 1); // iPadOS

        function isDismissed() { try { return localStorage.getItem(KEY) === '1'; } catch (e) { return false; } }
        function show() { if (!isStandalone) { banner.classList.add('is-shown'); } }
        function hide() { banner.classList.remove('is-shown'); }
        function remember() { try { localStorage.setItem(KEY, '1'); } catch (e) {} }

        // Invite native (Android Chrome/Edge, desktop) : on capture l'evenement -> clic = VRAIE install.
        window.addEventListener('beforeinstallprompt', function (e) {
            e.preventDefault();
            deferred = e;
            if (!isDismissed()) { show(); }
        });
        window.addEventListener('appinstalled', function () { hide(); remember(); deferred = null; });

        // iOS / iPadOS : Apple n'autorise AUCUNE invite native -> texte adapte (Safari -> ecran d'accueil).
        if (isiOS && !isStandalone) {
            if (titleEl) { titleEl.textContent = "Ajouter NGAKODON à l'accueil"; }
            if (subEl)   { subEl.textContent = "Safari : Partager → Sur l'écran d'accueil"; }
            if (goLabel) { goLabel.textContent = "Comment faire"; }
        }

        // Banniere TOUJOURS visible (sauf deja installee/fermee), pour qu'on voie l'option d'installation.
        if (!isStandalone && !isDismissed()) { window.setTimeout(show, 2500); }

        function iosHelp() {
            alert("Installer NGAKODON sur iPhone / iPad :\n\n1) Ouvrez le site dans Safari (pas Chrome)\n2) Touchez l'icône Partager (carré avec une flèche ⬆️, en bas)\n3) Faites défiler puis « Sur l'écran d'accueil »\n4) Touchez « Ajouter »");
        }
        function androidHelp() {
            alert("Installer NGAKODON (vraie application) :\n\n1) Ouvrez le menu ⋮ de votre navigateur (en haut à droite)\n2) Touchez « Installer l'application »\n\nSi vous ne voyez que « Ajouter à l'écran d'accueil », naviguez 2-3 pages puis rouvrez le menu : le navigateur doit d'abord reconnaître l'application.");
        }
        function doInstall() {
            if (deferred) {
                deferred.prompt();
                deferred.userChoice.then(function (choice) { if (choice && choice.outcome === 'accepted') { remember(); } deferred = null; hide(); });
            } else if (isiOS) {
                iosHelp();
            } else {
                androidHelp();
            }
        }

        if (goBtn) { goBtn.addEventListener('click', doInstall); }
        if (xBtn)  { xBtn.addEventListener('click', function () { hide(); remember(); }); }

        // Point d'entree global (lien « Installer l'app » du menu mobile)
        window.nkInstall = function () {
            if (isStandalone) { alert("NGAKODON est déjà installée sur votre appareil. ✅"); return; }
            doInstall();
        };
    })();
</script>
