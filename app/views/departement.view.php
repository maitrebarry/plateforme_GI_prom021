<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Espace Département']); ?>

<body class="public-site public-department">
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php $this->view('Partials/header'); ?>
<?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>
<?php
$departmentStats = $departmentStats ?? [];
$departmentPosts = $departmentPosts ?? [];
$departmentAllowedTypes = $departmentAllowedTypes ?? [];
$departmentTypeFilter = $departmentTypeFilter ?? 'all';
$departmentSearch = (string) ($departmentSearch ?? '');
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 6);
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalItems = max(0, (int) ($totalItems ?? count($departmentPosts)));
$paginationQuery = (string) ($paginationQuery ?? '');
$depTotal = array_sum(array_map('intval', $departmentStats));
$depTypeMeta = [
    'annonce'     => ['Annonces', 'bx-megaphone', 'annonces'],
    'information' => ['Informations', 'bx-info-circle', 'informations'],
    'evenement'   => ['Événements', 'bx-calendar-event', 'evenements'],
    'resultat'    => ['Résultats', 'bx-award', 'resultats'],
    'opportunite' => ['Opportunités', 'bx-briefcase-alt-2', 'opportunites'],
];
?>

<main>
    <style>
        .dep-wrap { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 16px; }

        /* Hero */
        .dep-hero { position: relative; overflow: hidden; background: linear-gradient(160deg, var(--ds-brand-700), var(--ds-brand-800)); color: #fff; padding: 42px 0 50px; }
        .dep-hero::before { content: ''; position: absolute; top: -90px; right: -70px; width: 290px; height: 290px; border-radius: 50%; background: radial-gradient(circle, rgba(224,168,46,.22), transparent 70%); pointer-events: none; }
        .dep-hero .dep-wrap { position: relative; z-index: 1; }
        .dep-kicker { display: inline-flex; align-items: center; gap: 7px; background: rgba(255,255,255,.13); border: 1px solid rgba(255,255,255,.22); color: #fff; font-weight: 700; font-size: .76rem; padding: 6px 14px; border-radius: var(--ds-radius-pill); }
        .dep-hero h1 { font-family: var(--ds-font-heading); font-weight: 800; font-size: 1.8rem; line-height: 1.2; margin: 14px 0 8px; color: #fff; overflow-wrap: break-word; }
        .dep-hero p { color: rgba(231,240,235,.82); font-size: 1rem; line-height: 1.55; margin: 0 0 20px; max-width: 600px; }
        .dep-search { display: flex; align-items: center; gap: 6px; background: #fff; border-radius: var(--ds-radius-lg); padding: 7px; box-shadow: var(--ds-shadow-md); max-width: 640px; }
        .dep-search > i { color: var(--ds-muted); font-size: 1.3rem; padding-left: 8px; flex-shrink: 0; }
        .dep-search input { flex: 1; min-width: 0; border: 0; outline: 0; background: transparent; font-size: .98rem; color: var(--ds-ink); padding: 10px 4px; font-family: var(--ds-font-sans); }
        .dep-search button { flex-shrink: 0; display: inline-flex; align-items: center; gap: 7px; background: var(--ds-brand-600); color: #fff; border: 0; font-weight: 700; padding: 11px 18px; border-radius: var(--ds-radius); cursor: pointer; transition: background var(--ds-transition); }
        .dep-search button:hover { background: var(--ds-brand-700); }

        /* Section + barre d'outils */
        .dep-section { padding: 24px 0 64px; }
        .dep-toolbar { display: flex; flex-direction: column; gap: 14px; margin-bottom: 18px; }
        .dep-chips { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 4px; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
        .dep-chips::-webkit-scrollbar { display: none; }
        .dep-chip { flex-shrink: 0; display: inline-flex; align-items: center; gap: 7px; background: var(--ds-surface); border: 1px solid var(--ds-border); color: var(--ds-ink); font-weight: 600; font-size: .85rem; padding: 9px 15px; border-radius: var(--ds-radius-pill); cursor: pointer; white-space: nowrap; transition: all var(--ds-transition); }
        .dep-chip i { font-size: 1.05rem; }
        .dep-chip:hover { border-color: var(--ds-brand-300); color: var(--ds-brand-700); }
        .dep-chip.is-active { background: var(--ds-brand-600); border-color: var(--ds-brand-600); color: #fff; }
        .dep-chip span { display: inline-flex; align-items: center; justify-content: center; min-width: 20px; height: 20px; padding: 0 6px; border-radius: var(--ds-radius-pill); background: var(--ds-surface-2); color: var(--ds-muted); font-size: .72rem; font-weight: 800; }
        .dep-chip.is-active span { background: rgba(255,255,255,.22); color: #fff; }
        .dep-toolbar__row { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
        .dep-count { color: var(--ds-muted); font-size: .9rem; font-weight: 600; }
        .dep-count strong { color: var(--ds-ink-strong); }
        .dep-field { display: flex; align-items: center; gap: 7px; }
        .dep-field label { color: var(--ds-muted); font-size: .85rem; font-weight: 600; }
        .dep-field select { border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius); padding: 8px 12px; font-size: .88rem; color: var(--ds-ink); background: var(--ds-surface); cursor: pointer; font-family: var(--ds-font-sans); }

        #department-posts-results { transition: opacity .2s ease; }

        /* Cartes publication (partial department-posts-list) */
        .public-department .department-post-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); padding: 20px; height: 100%; box-shadow: var(--ds-shadow-sm); transition: transform var(--ds-transition), box-shadow var(--ds-transition), border-color var(--ds-transition); }
        .public-department .department-post-card:hover { transform: translateY(-4px); box-shadow: var(--ds-shadow-md); border-color: var(--ds-brand-200); }
        .public-department .department-post-card strong { display: block; font-family: var(--ds-font-heading); font-size: 1.12rem; font-weight: 800; color: var(--ds-ink-strong); margin-bottom: 10px; }
        .public-department .department-post-meta { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }
        .public-department .department-post-meta span { display: inline-flex; align-items: center; gap: 5px; background: var(--ds-brand-50); color: var(--ds-brand-700); font-size: .76rem; font-weight: 600; padding: 4px 11px; border-radius: var(--ds-radius-pill); }
        .public-department .department-post-card .mb-3 { color: var(--ds-ink); line-height: 1.6; font-size: .92rem; }
        .public-department .department-image-preview { display: grid; gap: 8px; margin-bottom: 14px; border-radius: var(--ds-radius); overflow: hidden; }
        .public-department .department-image-preview--1 { grid-template-columns: 1fr; }
        .public-department .department-image-preview--2 { grid-template-columns: 1fr 1fr; }
        .public-department .department-image-preview--3 { grid-template-columns: 2fr 1fr; }
        .public-department .department-image-preview__item { position: relative; line-height: 0; display: block; }
        .public-department .department-image-preview__item img { width: 100%; height: 150px; object-fit: cover; }
        .public-department .department-image-preview--3 .department-image-preview__item:first-child img { height: 100%; }
        .public-department .department-image-preview__item span { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(10,35,27,.55); color: #fff; font-weight: 800; font-size: 1.1rem; }
        .public-department .department-file-link { display: inline-flex; align-items: center; gap: 6px; margin: 0 6px 6px 0; padding: 7px 13px; border-radius: var(--ds-radius-pill); border: 1px solid var(--ds-border); background: var(--ds-surface-2); color: var(--ds-ink); text-decoration: none; font-weight: 600; font-size: .82rem; transition: all var(--ds-transition); }
        .public-department .department-file-link:hover { background: var(--ds-brand-600); color: #fff; border-color: var(--ds-brand-600); }
        .public-department .department-post-card .btn-outline-primary { border: 1px solid var(--ds-brand-300); color: var(--ds-brand-700); border-radius: var(--ds-radius-pill); font-weight: 700; font-size: .85rem; padding: 8px 16px; background: transparent; transition: all var(--ds-transition); }
        .public-department .department-post-card .btn-outline-primary:hover { background: var(--ds-brand-600); color: #fff; border-color: var(--ds-brand-600); }

        /* Etat vide */
        #department-posts-results > p.text-muted { text-align: center; background: var(--ds-surface); border: 1px dashed var(--ds-border-strong); border-radius: var(--ds-radius-lg); padding: 40px 24px; color: var(--ds-muted); }

        /* Pagination (Partials/admin-pagination) */
        .public-department .admin-pagination-wrap { display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap; margin-top: 26px; }
        .public-department .admin-pagination-summary { color: var(--ds-muted); font-size: .85rem; font-weight: 600; }
        .public-department .admin-pagination { display: flex; gap: 6px; flex-wrap: wrap; }
        .public-department .page-link-nav { min-width: 40px; height: 40px; padding: 0 12px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--ds-radius); border: 1px solid var(--ds-border); background: var(--ds-surface); color: var(--ds-ink); text-decoration: none; font-weight: 700; font-size: .9rem; transition: all var(--ds-transition); }
        .public-department .page-link-nav:hover:not(.is-disabled) { border-color: var(--ds-brand-300); color: var(--ds-brand-700); }
        .public-department .page-link-nav.is-active { background: var(--ds-brand-600); border-color: var(--ds-brand-600); color: #fff; }
        .public-department .page-link-nav.is-disabled { pointer-events: none; opacity: .4; }

        @media (min-width: 768px) {
            .dep-hero { padding: 58px 0 66px; }
            .dep-hero h1 { font-size: 2.5rem; }
            .dep-toolbar { flex-direction: row; align-items: center; justify-content: space-between; }
            .dep-chips { flex-wrap: wrap; overflow: visible; }
        }
    </style>

    <!-- ===== HERO ===== -->
    <section class="dep-hero">
        <div class="dep-wrap">
            <span class="dep-kicker"><i class='bx bx-buildings'></i> Département</span>
            <h1>Espace Département — Génie Informatique</h1>
            <p><?= htmlspecialchars($department['subtitle'] ?? 'Annonces, informations officielles, résultats et opportunités du département.') ?></p>
            <form id="department-filter-form" method="get" action="<?= ROOT ?>/Homes/departement" class="dep-search" role="search">
                <i class='bx bx-search'></i>
                <input type="search" id="depSearch" name="search" value="<?= htmlspecialchars($departmentSearch) ?>" placeholder="Rechercher une publication…" aria-label="Rechercher" autocomplete="off">
                <input type="hidden" id="depType" name="type" value="<?= htmlspecialchars($departmentTypeFilter) ?>">
                <button type="submit"><i class='bx bx-search'></i><span class="d-none d-sm-inline">Rechercher</span></button>
            </form>
        </div>
    </section>

    <!-- ===== PUBLICATIONS ===== -->
    <section class="dep-section">
        <div class="dep-wrap">
            <div class="dep-toolbar">
                <div class="dep-chips">
                    <button type="button" class="dep-chip <?= $departmentTypeFilter === 'all' ? 'is-active' : '' ?>" data-type="all"><i class='bx bx-grid-alt'></i> Tous <span><?= $depTotal ?></span></button>
                    <?php foreach ($departmentAllowedTypes as $type): ?>
                        <?php $m = $depTypeMeta[$type] ?? [ucfirst($type), 'bx-bookmark', '']; $cnt = $m[2] !== '' ? (int) ($departmentStats[$m[2]] ?? 0) : 0; ?>
                        <button type="button" class="dep-chip <?= $departmentTypeFilter === $type ? 'is-active' : '' ?>" data-type="<?= htmlspecialchars($type) ?>"><i class='bx <?= htmlspecialchars($m[1]) ?>'></i> <?= htmlspecialchars($m[0]) ?> <span><?= $cnt ?></span></button>
                    <?php endforeach; ?>
                </div>
                <div class="dep-toolbar__row">
                    <div class="dep-count"><strong id="depCountNum"><?= $totalItems ?></strong> publication(s)</div>
                    <div class="dep-field">
                        <label for="depPerPage">Par page</label>
                        <select id="depPerPage">
                            <?php foreach ([6, 12, 24] as $pp): ?>
                                <option value="<?= $pp ?>" <?= $perPage === $pp ? 'selected' : '' ?>><?= $pp ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div id="department-posts-results">
                <?php $this->view('Partials/department-posts-list', [
                    'departmentPosts' => $departmentPosts,
                    'currentPage' => $currentPage,
                    'perPage' => $perPage,
                    'totalPages' => $totalPages,
                    'totalItems' => $totalItems,
                    'paginationQuery' => $paginationQuery,
                ]); ?>
            </div>
        </div>
    </section>

    <?php $this->view('Partials/footer'); ?>
</main>

<?php $this->view('Partials/scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var filterForm = document.getElementById('department-filter-form');
        var resultsContainer = document.getElementById('department-posts-results');
        var searchInput = document.getElementById('depSearch');
        var typeInput = document.getElementById('depType');
        var perPageSelect = document.getElementById('depPerPage');
        var chips = document.querySelectorAll('.dep-chip');
        var debounceTimer = null;
        var action = filterForm ? filterForm.getAttribute('action') : '<?= ROOT ?>/Homes/departement';

        function currentUrl() {
            var p = new URLSearchParams();
            var s = searchInput ? searchInput.value.trim() : '';
            if (s) { p.set('search', s); }
            var t = typeInput ? typeInput.value : 'all';
            if (t && t !== 'all') { p.set('type', t); }
            var pp = perPageSelect ? perPageSelect.value : '6';
            if (pp && pp !== '6') { p.set('per_page', pp); }
            var qs = p.toString();
            return action + (qs ? '?' + qs : '');
        }

        function bindPagination() {
            if (!resultsContainer) { return; }
            resultsContainer.querySelectorAll('.admin-pagination a.page-link-nav').forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    if (link.classList.contains('is-disabled')) { return; }
                    load(link.getAttribute('href'));
                });
            });
        }

        function load(url) {
            if (!resultsContainer || !url) { return; }
            resultsContainer.style.opacity = '0.55';
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) { return r.json(); })
                .then(function (payload) {
                    if (!payload || payload.ok !== true || typeof payload.html !== 'string') { resultsContainer.style.opacity = '1'; return; }
                    resultsContainer.innerHTML = payload.html;
                    resultsContainer.style.opacity = '1';
                    bindPagination();
                    var cnt = document.getElementById('depCountNum');
                    if (cnt && typeof payload.totalItems !== 'undefined') { cnt.textContent = payload.totalItems; }
                    window.history.replaceState({}, '', url);
                })
                .catch(function () { resultsContainer.style.opacity = '1'; });
        }

        chips.forEach(function (chip) {
            chip.addEventListener('click', function () {
                if (typeInput) { typeInput.value = chip.getAttribute('data-type') || 'all'; }
                chips.forEach(function (c) { c.classList.remove('is-active'); });
                chip.classList.add('is-active');
                load(currentUrl());
            });
        });
        if (perPageSelect) { perPageSelect.addEventListener('change', function () { load(currentUrl()); }); }
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function () { load(currentUrl()); }, 320);
            });
        }
        if (filterForm) {
            filterForm.addEventListener('submit', function (event) { event.preventDefault(); load(currentUrl()); });
        }
        bindPagination();
    });
</script>
</body>
</html>
