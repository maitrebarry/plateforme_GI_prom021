<?php
// Bouton retour INTELLIGENT (flottant, surtout mobile).
// - Revient via history.back() si la navigation precedente est interne au site.
// - Sinon (entree directe / lien partage), repli vers un parent contextuel.
// - Masque sur les pages racine (rien derriere).
$nkUrl = strtolower(trim((string) ($_GET['url'] ?? ''), '/'));
$nkRoots = ['', 'homes', 'homes/index', 'admins', 'admins/index', 'admins/dashboard', 'homes/login', 'homes/register', 'homes/dashboard', 'homes/student_dashboard', 'homes/der_dashboard'];
$nkShowBack = !in_array($nkUrl, $nkRoots, true);

if ($nkShowBack) {
    $nkFallback = 'Homes/index';
    if (str_starts_with($nkUrl, 'projets/detail')) {
        $nkFallback = 'Homes/projects';
    } elseif (str_contains($nkUrl, 'department_publication') || str_starts_with($nkUrl, 'homes/department_')) {
        $nkFallback = 'Homes/departement';
    } elseif (str_starts_with($nkUrl, 'admins/') || str_starts_with($nkUrl, 'utilisateurs/')) {
        $nkFallback = 'Admins/dashboard';
    } elseif (str_starts_with($nkUrl, 'projets/') || str_starts_with($nkUrl, 'profiles/') || str_starts_with($nkUrl, 'notifications') || str_starts_with($nkUrl, 'homes/messages') || str_starts_with($nkUrl, 'homes/student') || str_starts_with($nkUrl, 'homes/der')) {
        $nkFallback = 'Homes/dashboard';
    }
}
?>
<?php if (!empty($nkShowBack)): ?>
<button type="button" class="nk-backfab" id="nkBackFab" data-fallback="<?= ROOT ?>/<?= htmlspecialchars($nkFallback) ?>" aria-label="Revenir en arrière" title="Retour">
    <i class='bx bx-chevron-left'></i>
    <span class="nk-backfab__label">Retour</span>
</button>
<style>
    .nk-backfab { position: fixed; left: 14px; bottom: 20px; z-index: 1090; height: 46px; padding: 0 14px 0 11px; border-radius: var(--ds-radius-pill, 999px); background: var(--ds-surface, #fff); border: 1px solid var(--ds-border, #e2e2e2); box-shadow: 0 8px 22px -8px rgba(0,0,0,.28); color: var(--ds-ink, #14211c); display: none; align-items: center; gap: 4px; font-family: var(--ds-font-sans, system-ui); font-weight: 700; font-size: .9rem; cursor: pointer; transition: transform .15s ease, background .15s ease, color .15s ease, border-color .15s ease; }
    .nk-backfab i { font-size: 1.5rem; }
    .nk-backfab:hover, .nk-backfab:active { background: var(--ds-brand-600, #157f5a); color: #fff; border-color: var(--ds-brand-600, #157f5a); transform: translateY(-2px); }
    /* Surtout mobile/tablette ; au-dessus de la barre de navigation basse sur le site public. */
    @media (max-width: 991px) { .nk-backfab { display: inline-flex; } }
    .public-site .nk-backfab, .um6p-site .nk-backfab { bottom: 82px; }
</style>
<script>
    (function () {
        var b = document.getElementById('nkBackFab');
        if (!b) { return; }
        b.addEventListener('click', function () {
            var fb = b.getAttribute('data-fallback') || '/';
            var ref = document.referrer || '';
            var internal = ref !== '' && ref.indexOf(window.location.origin) === 0 && ref !== window.location.href;
            if (window.history.length > 1 && internal) {
                window.history.back();
            } else {
                window.location.href = fb;
            }
        });
    })();
</script>
<?php endif; ?>
