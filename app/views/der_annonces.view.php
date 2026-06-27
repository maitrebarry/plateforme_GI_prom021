<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Espace DER']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$derStats = $derStats ?? [];
$derPosts = $derPosts ?? [];
$derAllowedTypes = $derAllowedTypes ?? [];
$derVisibilityFilter = $derVisibilityFilter ?? 'active';
$derTypeFilter = $derTypeFilter ?? 'all';
$derSearch = $derSearch ?? '';
$derDateFrom = $derDateFrom ?? '';
$derDateTo = $derDateTo ?? '';
$derSortBy = $derSortBy ?? 'date';
$derSortDir = $derSortDir ?? 'desc';
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 10);
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalItems = max(0, (int) ($totalItems ?? count($derPosts ?? [])));
$paginationQuery = (string) ($paginationQuery ?? '');
$csrf = (string) ($_SESSION['csrf_token'] ?? '');
$derStatCards = [
    ['Total', (int) ($derStats['total'] ?? 0), 'bx-collection', 'brand'],
    ['Annonces', (int) ($derStats['annonces'] ?? 0), 'bx-megaphone', 'danger'],
    ['Informations', (int) ($derStats['informations'] ?? 0), 'bx-info-circle', 'brand'],
    ['Événements', (int) ($derStats['evenements'] ?? 0), 'bx-calendar-event', 'accent'],
    ['Résultats', (int) ($derStats['resultats'] ?? 0), 'bx-award', 'blue'],
    ['Opportunités', (int) ($derStats['opportunites'] ?? 0), 'bx-briefcase-alt-2', 'gold'],
    ['Fichiers', (int) ($derStats['files'] ?? 0), 'bx-paperclip', 'slate'],
    ['Archives', (int) ($derStats['archived'] ?? 0), 'bx-archive', 'slate'],
];
?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

            <div class="dashboard-body__content p-3 p-lg-4">
                <?php $this->view('set_flash'); ?>

                <style>
                    .der-hero { position: relative; overflow: hidden; background: linear-gradient(135deg, var(--ds-brand-700), var(--ds-brand-800)); border-radius: var(--ds-radius-xl); padding: 26px; color: #fff; margin-bottom: 22px; }
                    .der-hero::before { content: ''; position: absolute; top: -70px; right: -50px; width: 280px; height: 280px; border-radius: 50%; background: radial-gradient(circle, rgba(224,168,46,.2), transparent 70%); }
                    .der-hero__row { position: relative; z-index: 1; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; }
                    .der-hero h1 { font-family: var(--ds-font-heading); font-weight: 800; font-size: 1.55rem; color: #fff; margin: 0 0 6px; }
                    .der-hero p { color: rgba(231,240,235,.82); font-size: .94rem; margin: 0; max-width: 520px; }
                    .der-btn { display: inline-flex; align-items: center; gap: 7px; font-weight: 700; font-size: .88rem; padding: 10px 16px; border-radius: var(--ds-radius-pill); border: 0; cursor: pointer; text-decoration: none; transition: all var(--ds-transition); }
                    .der-btn--gold { background: var(--ds-accent); color: #3d2900; } .der-btn--gold:hover { background: #f0b53e; color: #3d2900; }
                    .der-btn--glass { background: rgba(255,255,255,.12); color: #fff; border: 1px solid rgba(255,255,255,.22); } .der-btn--glass:hover { background: rgba(255,255,255,.2); color: #fff; }

                    .der-stats { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 12px; margin-bottom: 22px; }
                    .der-stat { position: relative; overflow: hidden; background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); padding: 14px; box-shadow: var(--ds-shadow-sm); }
                    .der-stat::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
                    .der-stat--brand::before { background: var(--ds-brand-500); } .der-stat--brand .der-stat__icon { background: var(--ds-brand-50); color: var(--ds-brand-600); }
                    .der-stat--danger::before { background: var(--ds-danger); } .der-stat--danger .der-stat__icon { background: var(--ds-danger-soft); color: var(--ds-danger); }
                    .der-stat--accent::before { background: var(--ds-accent); } .der-stat--accent .der-stat__icon { background: var(--ds-accent-soft); color: #8a6310; }
                    .der-stat--blue::before { background: #1d59b8; } .der-stat--blue .der-stat__icon { background: #e3effb; color: #1d59b8; }
                    .der-stat--gold::before { background: #d99a16; } .der-stat--gold .der-stat__icon { background: var(--ds-accent-soft); color: #8a6310; }
                    .der-stat--slate::before { background: #64748b; } .der-stat--slate .der-stat__icon { background: var(--ds-surface-2); color: var(--ds-muted); }
                    .der-stat__icon { width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 8px; }
                    .der-stat__value { font-family: var(--ds-font-heading); font-size: 1.5rem; font-weight: 800; color: var(--ds-ink-strong); line-height: 1; }
                    .der-stat__label { color: var(--ds-muted); font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; margin-top: 5px; }

                    .der-grid { display: grid; grid-template-columns: 1fr; gap: 20px; align-items: start; }
                    @media (min-width: 1100px) { .der-grid { grid-template-columns: minmax(0, 0.85fr) minmax(0, 1.4fr); } .der-hero h1 { font-size: 1.9rem; } .der-stats { grid-template-columns: repeat(4, minmax(0,1fr)); } }

                    .der-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); box-shadow: var(--ds-shadow-sm); padding: 20px; }
                    .der-card__title { font-family: var(--ds-font-heading); font-size: 1.08rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0 0 16px; padding-bottom: 12px; border-bottom: 1px solid var(--ds-border); display: flex; align-items: center; gap: 8px; }
                    .der-card__title i { color: var(--ds-brand-600); }
                    .der-card label { font-weight: 700; color: var(--ds-ink); font-size: .82rem; margin-bottom: 6px; display: block; }
                    .der-card .form-control, .der-card .form-select, .der-card textarea { width: 100%; border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius); padding: 10px 13px; font-size: .9rem; color: var(--ds-ink); background: var(--ds-surface); font-family: var(--ds-font-sans); }
                    .der-card .form-control:focus, .der-card .form-select:focus, .der-card textarea:focus { outline: none; border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); }
                    .der-submit { display: inline-flex; align-items: center; justify-content: center; gap: 7px; width: 100%; background: var(--ds-brand-600); color: #fff; font-weight: 700; padding: 11px; border: 0; border-radius: var(--ds-radius-pill); cursor: pointer; transition: all var(--ds-transition); }
                    .der-submit:hover { background: var(--ds-brand-700); }

                    .der-filter { background: var(--ds-surface-2); border: 1px solid var(--ds-border); border-radius: var(--ds-radius); padding: 14px; margin-bottom: 16px; }
                    .der-filter label { font-size: .7rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: var(--ds-muted); margin-bottom: 5px; }
                    .der-filter .form-control, .der-filter .form-select { width: 100%; border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius); padding: 8px 11px; font-size: .85rem; color: var(--ds-ink); background: var(--ds-surface); }
                    .der-filter .form-control:focus, .der-filter .form-select:focus { outline: none; border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); }
                </style>

                <div class="der-hero">
                    <div class="der-hero__row">
                        <div>
                            <h1>Espace DER — Gestion</h1>
                            <p>Ajoutez, modifiez, archivez les publications du département et gérez leurs fichiers.</p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="#create-der-post" class="der-btn der-btn--gold"><i class='bx bx-plus-circle'></i> Nouvelle</a>
                            <a href="<?= ROOT ?>/Homes/der_dashboard" class="der-btn der-btn--glass"><i class='bx bx-arrow-back'></i> Dashboard</a>
                        </div>
                    </div>
                </div>

                <div class="der-stats">
                    <?php foreach ($derStatCards as $s): ?>
                        <div class="der-stat der-stat--<?= $s[3] ?>">
                            <span class="der-stat__icon"><i class='bx <?= $s[2] ?>'></i></span>
                            <div class="der-stat__value"><?= $s[1] ?></div>
                            <div class="der-stat__label"><?= htmlspecialchars($s[0]) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="der-grid">
                    <!-- CREATE -->
                    <div class="der-card" id="create-der-post">
                        <h2 class="der-card__title"><i class='bx bx-plus-circle'></i> Nouvelle publication</h2>
                        <form method="POST" action="<?= ROOT ?>/Homes/der_espace" enctype="multipart/form-data" class="row gy-3">
                            <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                            <div class="col-12"><label>Type</label>
                                <select name="type" class="form-select" required>
                                    <?php foreach ($derAllowedTypes as $type): ?><option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars(ucfirst($type)) ?></option><?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12"><label>Titre</label><input type="text" name="titre" class="form-control" placeholder="Titre de la publication" required></div>
                            <div class="col-12"><label>Date de publication</label><input type="date" name="date_publication" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
                            <div class="col-12"><label>Contenu</label><textarea name="contenu" class="form-control" rows="6" placeholder="Contenu de la publication" required></textarea></div>
                            <div class="col-12"><label>Fichiers joints</label><input type="file" name="fichiers[]" class="form-control" multiple><small class="text-muted d-block mt-2">pdf, doc, docx, xls, xlsx, ppt, pptx, jpg, jpeg, png.</small></div>
                            <div class="col-12"><button type="submit" name="save_der_post" class="der-submit"><i class='bx bx-send'></i> Publier</button></div>
                        </form>
                    </div>

                    <!-- MANAGE -->
                    <div class="der-card" id="manage-der-posts">
                        <h2 class="der-card__title"><i class='bx bx-cog'></i> Gérer les publications</h2>
                        <div class="der-filter">
                            <form method="GET" action="<?= ROOT ?>/Homes/der_espace" class="row gy-2 gx-2" id="der-filter-form">
                                <div class="col-md-4"><label>Recherche</label><input type="text" name="search" value="<?= htmlspecialchars($derSearch) ?>" class="form-control" placeholder="Rechercher…"></div>
                                <div class="col-md-4 col-6"><label>Statut</label><select name="visibility" class="form-select auto-submit-der"><option value="active" <?= $derVisibilityFilter === 'active' ? 'selected' : '' ?>>Actives</option><option value="archived" <?= $derVisibilityFilter === 'archived' ? 'selected' : '' ?>>Archivées</option><option value="all" <?= $derVisibilityFilter === 'all' ? 'selected' : '' ?>>Toutes</option></select></div>
                                <div class="col-md-4 col-6"><label>Type</label><select name="type" class="form-select auto-submit-der"><option value="all" <?= $derTypeFilter === 'all' ? 'selected' : '' ?>>Tous</option><?php foreach ($derAllowedTypes as $type): ?><option value="<?= htmlspecialchars($type) ?>" <?= $derTypeFilter === $type ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($type)) ?></option><?php endforeach; ?></select></div>
                                <div class="col-md-3 col-6"><label>Du</label><input type="date" name="date_from" value="<?= htmlspecialchars($derDateFrom) ?>" class="form-control"></div>
                                <div class="col-md-3 col-6"><label>Au</label><input type="date" name="date_to" value="<?= htmlspecialchars($derDateTo) ?>" class="form-control"></div>
                                <div class="col-md-2 col-6"><label>Trier</label><select name="sort_by" class="form-select auto-submit-der"><option value="date" <?= $derSortBy === 'date' ? 'selected' : '' ?>>Date</option><option value="title" <?= $derSortBy === 'title' ? 'selected' : '' ?>>Titre</option><option value="type" <?= $derSortBy === 'type' ? 'selected' : '' ?>>Type</option><option value="author" <?= $derSortBy === 'author' ? 'selected' : '' ?>>Auteur</option></select></div>
                                <div class="col-md-2 col-6"><label>Ordre</label><select name="sort_dir" class="form-select auto-submit-der"><option value="desc" <?= $derSortDir === 'desc' ? 'selected' : '' ?>>Desc</option><option value="asc" <?= $derSortDir === 'asc' ? 'selected' : '' ?>>Asc</option></select></div>
                                <div class="col-md-2 col-6"><label>Par page</label><select name="per_page" class="form-select auto-submit-der"><?php foreach ([5, 10, 20, 50] as $pp): ?><option value="<?= $pp ?>" <?= $perPage === $pp ? 'selected' : '' ?>><?= $pp ?></option><?php endforeach; ?></select></div>
                            </form>
                        </div>

                        <div id="der-posts-results">
                            <?php $this->view('Partials/der-posts-list', [
                                'derPosts' => $derPosts,
                                'derAllowedTypes' => $derAllowedTypes,
                                'formAction' => ROOT . '/Homes/der_espace',
                                'paginationBasePath' => 'Homes/der_espace',
                                'detailReturnBasePath' => 'Homes/der_espace',
                                'paginationQuery' => $paginationQuery,
                                'currentPage' => $currentPage,
                                'perPage' => $perPage,
                                'totalPages' => $totalPages,
                                'totalItems' => $totalItems,
                                'activeEditPostId' => $activeEditPostId ?? 0,
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>

<?php $this->view('Partials/scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var filterForm = document.getElementById('der-filter-form');
    var resultsContainer = document.getElementById('der-posts-results');
    var searchInput = filterForm ? filterForm.querySelector('input[name="search"]') : null;
    var debounceTimer = null;

    function bindDerPagination() {
        if (!resultsContainer) return;
        resultsContainer.querySelectorAll('.admin-pagination a.page-link-nav').forEach(function (link) {
            link.addEventListener('click', function (event) {
                if (link.classList.contains('is-disabled')) { event.preventDefault(); return; }
                event.preventDefault();
                loadDerResults(link.getAttribute('href'));
            });
        });
    }
    function loadDerResults(url) {
        if (!resultsContainer || !url) return;
        resultsContainer.style.opacity = '0.6';
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (payload) {
                if (!payload || payload.ok !== true || typeof payload.html !== 'string') { resultsContainer.style.opacity = '1'; return; }
                resultsContainer.innerHTML = payload.html;
                resultsContainer.style.opacity = '1';
                bindDerPagination();
                window.history.replaceState({}, '', url);
            })
            .catch(function () { resultsContainer.style.opacity = '1'; });
    }
    document.querySelectorAll('.auto-submit-der').forEach(function (el) {
        el.addEventListener('change', function () {
            if (filterForm) loadDerResults(filterForm.getAttribute('action') + '?' + new URLSearchParams(new FormData(filterForm)).toString());
        });
    });
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () { loadDerResults(filterForm.getAttribute('action') + '?' + new URLSearchParams(new FormData(filterForm)).toString()); }, 300);
        });
    }
    if (filterForm) {
        filterForm.addEventListener('submit', function (e) { e.preventDefault(); loadDerResults(filterForm.getAttribute('action') + '?' + new URLSearchParams(new FormData(filterForm)).toString()); });
    }
    bindDerPagination();
});
</script>
</body>
</html>
