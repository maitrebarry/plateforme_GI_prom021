<?php
$nkOnbRoute = strtolower(trim((string) ($_GET['url'] ?? ''), '/'));
$nkOnbSkip = in_array($nkOnbRoute, ['homes/login', 'logins/index', 'homes/register'], true);
if (!isset($_SESSION['user_id']) && !$nkOnbSkip): // onboarding = visiteurs non connectes, hors pages d'authentification
?>
<div class="nk-onb" id="nkOnb" aria-hidden="true" role="dialog" aria-label="Configuration de NGAKODON">
    <div class="nk-onb__card">
        <button type="button" class="nk-onb__skip" data-onb-skip>Passer</button>
        <div class="nk-onb__dots"><span class="is-active"></span><span></span><span></span></div>

        <!-- 1 — Bienvenue -->
        <section class="nk-onb__step is-active" data-step="0">
            <img src="<?= ROOT ?>/assets/icons/nk-192.png" alt="" class="nk-onb__logo">
            <h2>Bienvenue sur NGAKODON</h2>
            <p>La vitrine des projets étudiants en informatique. Configurons votre application en quelques secondes.</p>
            <button type="button" class="ds-btn ds-btn--primary ds-btn--block" data-onb-next>Commencer <i class='bx bx-right-arrow-alt'></i></button>
        </section>

        <!-- 2 — Apparence -->
        <section class="nk-onb__step" data-step="1">
            <h2>Choisissez votre apparence</h2>
            <p class="nk-onb__label">Mode d'affichage</p>
            <div class="nk-onb__themes">
                <button type="button" class="nk-onb__theme" data-onb-theme="light"><i class='bx bx-sun'></i><span>Clair</span></button>
                <button type="button" class="nk-onb__theme" data-onb-theme="dark"><i class='bx bx-moon'></i><span>Sombre</span></button>
            </div>
            <p class="nk-onb__label">Couleur d'accent</p>
            <div class="nk-onb__colors">
                <button type="button" class="ds-color-swatch" data-color-value="green" style="--sw:#157f5a" aria-label="Vert"></button>
                <button type="button" class="ds-color-swatch" data-color-value="blue" style="--sw:#1d59b8" aria-label="Bleu"></button>
                <button type="button" class="ds-color-swatch" data-color-value="orange" style="--sw:#cf5410" aria-label="Orange"></button>
                <button type="button" class="ds-color-swatch" data-color-value="violet" style="--sw:#6236c4" aria-label="Violet"></button>
            </div>
            <div class="nk-onb__nav">
                <button type="button" class="ds-btn ds-btn--ghost" data-onb-prev><i class='bx bx-left-arrow-alt'></i> Retour</button>
                <button type="button" class="ds-btn ds-btn--primary" data-onb-next>Continuer <i class='bx bx-right-arrow-alt'></i></button>
            </div>
        </section>

        <!-- 3 — Compte -->
        <section class="nk-onb__step" data-step="2">
            <h2>Créez votre compte</h2>
            <p>Pour publier vos projets, suivre vos favoris et échanger avec les porteurs.</p>
            <a href="<?= ROOT ?>/Homes/register" class="ds-btn ds-btn--primary ds-btn--block" data-onb-go><i class='bx bx-user-plus'></i> Créer un compte</a>
            <a href="<?= ROOT ?>/Homes/login" class="ds-btn ds-btn--outline ds-btn--block" data-onb-go><i class='bx bx-log-in-circle'></i> J'ai déjà un compte</a>
            <?php $this->view('Partials/social-login'); ?>
            <button type="button" class="nk-onb__guest" data-onb-finish>Explorer en invité <i class='bx bx-right-arrow-alt'></i></button>
        </section>
    </div>
</div>

<style>
    .nk-onb { position: fixed; inset: 0; z-index: 2000; display: none; align-items: center; justify-content: center; padding: 18px;
        background: radial-gradient(120% 120% at 50% 0%, rgba(21,127,90,.22), rgba(8,20,16,.78) 60%), rgba(8,20,16,.82); backdrop-filter: blur(4px); }
    .nk-onb.is-shown { display: flex; }
    .nk-onb__card { position: relative; width: 100%; max-width: 420px; background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: 22px; box-shadow: 0 30px 70px -20px rgba(0,0,0,.5); padding: 28px 24px 24px; max-height: 92vh; overflow-y: auto; }
    .nk-onb__skip { position: absolute; top: 14px; right: 16px; background: transparent; border: 0; color: var(--ds-muted); font-weight: 700; font-size: .84rem; cursor: pointer; }
    .nk-onb__skip:hover { color: var(--ds-brand-700); }
    .nk-onb__dots { display: flex; gap: 7px; justify-content: center; margin-bottom: 18px; }
    .nk-onb__dots span { width: 7px; height: 7px; border-radius: 50%; background: var(--ds-border-strong); transition: all var(--ds-transition); }
    .nk-onb__dots span.is-active { width: 22px; border-radius: 4px; background: var(--ds-brand-600); }
    .nk-onb__step { display: none; text-align: center; animation: nkOnbIn .3s ease; }
    .nk-onb__step.is-active { display: block; }
    @keyframes nkOnbIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
    .nk-onb__logo { width: 84px; height: 84px; border-radius: 22px; margin: 4px auto 16px; display: block; box-shadow: var(--ds-shadow); }
    .nk-onb__step h2 { font-family: var(--ds-font-heading); font-size: 1.35rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0 0 8px; }
    .nk-onb__step > p { color: var(--ds-muted); font-size: .92rem; line-height: 1.5; margin: 0 0 20px; }
    .nk-onb__label { text-align: left; font-weight: 700; font-size: .78rem; text-transform: uppercase; letter-spacing: .04em; color: var(--ds-muted); margin: 0 0 10px !important; }
    .nk-onb__themes { display: flex; gap: 12px; margin-bottom: 22px; }
    .nk-onb__theme { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 7px; padding: 16px; border: 2px solid var(--ds-border); border-radius: var(--ds-radius-lg); background: var(--ds-surface-2); color: var(--ds-ink); font-weight: 700; cursor: pointer; transition: all var(--ds-transition); }
    .nk-onb__theme i { font-size: 1.7rem; color: var(--ds-muted); }
    .nk-onb__theme.is-active { border-color: var(--ds-brand-500); background: var(--ds-brand-50); color: var(--ds-brand-700); }
    .nk-onb__theme.is-active i { color: var(--ds-brand-600); }
    .nk-onb__colors { display: flex; gap: 14px; justify-content: center; margin-bottom: 24px; }
    .nk-onb__colors .ds-color-swatch { width: 38px; height: 38px; }
    .nk-onb__nav { display: flex; gap: 10px; }
    .nk-onb__nav .ds-btn { flex: 1; justify-content: center; }
    .nk-onb__guest { display: inline-flex; align-items: center; gap: 5px; margin: 14px auto 0; background: transparent; border: 0; color: var(--ds-muted); font-weight: 700; font-size: .88rem; cursor: pointer; }
    .nk-onb__guest:hover { color: var(--ds-brand-700); }
    .nk-onb .nk-social { margin-top: 16px; }
</style>

<script>
    (function () {
        var onb = document.getElementById('nkOnb');
        if (!onb) { return; }
        var KEY = 'nkadon-setup-done';
        var done = false;
        try { done = localStorage.getItem(KEY) === '1'; } catch (e) {}
        if (done) { return; }

        var steps = onb.querySelectorAll('.nk-onb__step');
        var dots = onb.querySelectorAll('.nk-onb__dots span');
        var i = 0;
        function show(n) {
            i = Math.max(0, Math.min(steps.length - 1, n));
            steps.forEach(function (s, k) { s.classList.toggle('is-active', k === i); });
            dots.forEach(function (d, k) { d.classList.toggle('is-active', k === i); });
        }
        function finish() {
            try { localStorage.setItem(KEY, '1'); } catch (e) {}
            onb.classList.remove('is-shown');
        }

        // Sync l'etat courant (theme + couleur) au demarrage
        var curTheme = document.documentElement.getAttribute('data-theme') || 'light';
        onb.querySelectorAll('[data-onb-theme]').forEach(function (b) { b.classList.toggle('is-active', b.getAttribute('data-onb-theme') === curTheme); });
        var curColor = document.documentElement.getAttribute('data-color') || 'green';
        onb.querySelectorAll('.nk-onb__colors [data-color-value]').forEach(function (s) { s.classList.toggle('is-active', s.getAttribute('data-color-value') === curColor); });

        onb.addEventListener('click', function (e) {
            var t = e.target.closest ? e.target.closest('[data-onb-theme],[data-onb-next],[data-onb-prev],[data-onb-skip],[data-onb-finish],[data-onb-go]') : null;
            if (!t) { return; }
            if (t.hasAttribute('data-onb-theme')) {
                var th = t.getAttribute('data-onb-theme');
                document.documentElement.setAttribute('data-theme', th);
                try { localStorage.setItem('ngakodon-theme', th); } catch (e2) {}
                onb.querySelectorAll('[data-onb-theme]').forEach(function (b) { b.classList.toggle('is-active', b === t); });
                document.querySelectorAll('[data-theme-toggle] i').forEach(function (ic) { ic.className = (th === 'dark') ? 'bx bx-sun' : 'bx bx-moon'; });
            } else if (t.hasAttribute('data-onb-next')) { show(i + 1); }
            else if (t.hasAttribute('data-onb-prev')) { show(i - 1); }
            else if (t.hasAttribute('data-onb-skip') || t.hasAttribute('data-onb-finish')) { finish(); }
            else if (t.hasAttribute('data-onb-go')) { try { localStorage.setItem(KEY, '1'); } catch (e3) {} } // lien : marque fait puis suit le href
        });

        show(0);
        onb.classList.add('is-shown');
    })();
</script>
<?php endif; ?>
