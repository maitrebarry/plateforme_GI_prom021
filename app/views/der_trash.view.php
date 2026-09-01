<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Corbeille des publications']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$derStats = $derStats ?? [];
$derPosts = $derPosts ?? [];
$derAllowedTypes = $derAllowedTypes ?? [];
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
?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-3 p-lg-4">
                <?php $this->view('set_flash'); ?>

                <style>
                    .der-hero { position: relative; overflow: hidden; background: linear-gradient(135deg, var(--ds-brand-700), var(--ds-brand-800)); border-radius: var(--ds-radius-xl); padding: 26px; color: #fff; margin-bottom: 22px; }
                    .der-hero::before { content: ''; position: absolute; top: -60px; right: -40px; width: 240px; height: 240px; border-radius: 50%; background: radial-gradient(circle, rgba(224,168,46,.2), transparent 70%); }
                    .der-hero__row { position: relative; z-index: 1; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 14px; }
                    .der-hero h1 { font-family: var(--ds-font-heading); font-weight: 800; font-size: 1.55rem; color: #fff; margin: 0 0 6px; }
                    .der-hero p { color: rgba(231,240,235,.82); font-size: .94rem; margin: 0; }
                    .der-btn { display: inline-flex; align-items: center; gap: 7px; font-weight: 700; font-size: .88rem; padding: 10px 16px; border-radius: var(--ds-radius-pill); text-decoration: none; transition: all var(--ds-transition); }
                    .der-btn--glass { background: rgba(255,255,255,.12); color: #fff; border: 1px solid rgba(255,255,255,.22); } .der-btn--glass:hover { background: rgba(255,255,255,.2); color: #fff; }
                    .der-count-badge { background: rgba(255,255,255,.92); color: var(--ds-brand-700); font-weight: 800; font-size: .85rem; padding: 8px 16px; border-radius: var(--ds-radius-pill); }

                    .der-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); box-shadow: var(--ds-shadow-sm); padding: 20px; }
                    .der-filter { background: var(--ds-surface-2); border: 1px solid var(--ds-border); border-radius: var(--ds-radius); padding: 14px; margin-bottom: 18px; }
                    .der-filter label { font-size: .7rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: var(--ds-muted); margin-bottom: 5px; display: block; }
                    .der-filter .form-control, .der-filter .form-select { width: 100%; border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius); padding: 9px 12px; font-size: .87rem; color: var(--ds-ink); background: var(--ds-surface); font-family: var(--ds-font-sans); }
                    .der-filter .form-control:focus, .der-filter .form-select:focus { outline: none; border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); }
                    .der-filter__btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; width: 100%; background: var(--ds-brand-600); color: #fff; font-weight: 700; padding: 10px; border: 0; border-radius: var(--ds-radius); cursor: pointer; }
                    .der-filter__btn:hover { background: var(--ds-brand-700); }
                </style>

                <div class="der-hero">
                    <div class="der-hero__row">
                        <div>
                            <h1><i class='bx bx-trash'></i> Corbeille des publications</h1>
                            <p>Retrouvez et restaurez vos publications archivées, ou supprimez-les définitivement.</p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap align-items-center">
                            <a href="<?= ROOT ?>/Homes/der_espace" class="der-btn der-btn--glass"><i class='bx bx-arrow-back'></i> Gestion</a>
                            <span class="der-count-badge">Archives : <?= (int) ($derStats['archived'] ?? 0) ?></span>
                        </div>
                    </div>
                </div>

                <div class="der-card">
                    <div class="der-filter">
                        <form method="GET" action="<?= ROOT ?>/Homes/der_corbeille" class="row gy-2 gx-2" id="der-trash-filter-form">
                            <input type="hidden" name="visibility" value="archived">
                            <div class="col-md-4"><label>Rechercher</label><input type="text" name="search" value="<?= htmlspecialchars($derSearch) ?>" class="form-control" placeholder="Titre ou contenu…"></div>
                            <div class="col-md-2 col-6"><label>Type</label><select name="type" class="form-select"><option value="all" <?= $derTypeFilter === 'all' ? 'selected' : '' ?>>Tous</option><?php foreach ($derAllowedTypes as $type): ?><option value="<?= htmlspecialchars($type) ?>" <?= $derTypeFilter === $type ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($type)) ?></option><?php endforeach; ?></select></div>
                            <div class="col-md-2 col-6"><label>Depuis</label><input type="date" name="date_from" value="<?= htmlspecialchars($derDateFrom) ?>" class="form-control"></div>
                            <div class="col-md-2 col-6"><label>Jusqu'au</label><input type="date" name="date_to" value="<?= htmlspecialchars($derDateTo) ?>" class="form-control"></div>
                            <div class="col-md-2 col-6 d-flex align-items-end"><button type="submit" class="der-filter__btn"><i class='bx bx-filter-alt'></i> Filtrer</button></div>
                            <div class="col-md-3 col-6"><label>Trier par</label><select name="sort_by" class="form-select"><option value="date" <?= $derSortBy === 'date' ? 'selected' : '' ?>>Date</option><option value="title" <?= $derSortBy === 'title' ? 'selected' : '' ?>>Titre</option><option value="author" <?= $derSortBy === 'author' ? 'selected' : '' ?>>Auteur</option></select></div>
                            <div class="col-md-3 col-6"><label>Ordre</label><select name="sort_dir" class="form-select"><option value="desc" <?= $derSortDir === 'desc' ? 'selected' : '' ?>>Décroissant</option><option value="asc" <?= $derSortDir === 'asc' ? 'selected' : '' ?>>Croissant</option></select></div>
                            <div class="col-md-3 col-6"><label>Par page</label><select name="per_page" class="form-select"><?php foreach ([10, 20, 50] as $pp): ?><option value="<?= $pp ?>" <?= $perPage === $pp ? 'selected' : '' ?>><?= $pp ?> / page</option><?php endforeach; ?></select></div>
                        </form>
                    </div>

                    <?php $this->view('Partials/der-posts-list', [
                        'derPosts' => $derPosts,
                        'derAllowedTypes' => $derAllowedTypes,
                        'formAction' => ROOT . '/Homes/der_corbeille',
                        'paginationBasePath' => 'Homes/der_corbeille',
                        'detailReturnBasePath' => 'Homes/der_corbeille',
                        'paginationQuery' => $paginationQuery,
                        'currentPage' => $currentPage,
                        'perPage' => $perPage,
                        'totalPages' => $totalPages,
                        'totalItems' => $totalItems,
                        'activeEditPostId' => 0,
                    ]); ?>
                </div>
            </div>

            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>

<?php $this->view('Partials/scripts'); ?>
</body>
</html>
