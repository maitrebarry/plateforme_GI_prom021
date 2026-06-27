<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Gestion des catégories']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$categorySearch = $categorySearch ?? '';
$categorySortBy = $categorySortBy ?? 'name';
$categorySortDir = $categorySortDir ?? 'asc';
$categoryUsageFilter = $categoryUsageFilter ?? 'all';
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 10);
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalItems = max(0, (int) ($totalItems ?? count($categories ?? [])));
$paginationQuery = (string) ($paginationQuery ?? '');
$categories = $categories ?? [];
$categoryStats = $categoryStats ?? ['total' => 0, 'used' => 0, 'unused' => 0];
$csrf = (string) ($_SESSION['csrf_token'] ?? '');
?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-3 p-lg-4">
                <?php $this->view('set_flash'); ?>

                <style>
                    .adm-head { display: flex; align-items: center; gap: 12px; margin-bottom: 18px; }
                    .adm-back { width: 42px; height: 42px; border-radius: 12px; background: var(--ds-surface); border: 1px solid var(--ds-border); display: inline-flex; align-items: center; justify-content: center; color: var(--ds-ink); text-decoration: none; font-size: 1.2rem; transition: all var(--ds-transition); }
                    .adm-back:hover { background: var(--ds-brand-50); color: var(--ds-brand-700); }
                    .adm-head h1 { font-family: var(--ds-font-heading); font-size: 1.35rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0; }
                    .adm-head p { color: var(--ds-muted); font-size: .85rem; margin: 0; }

                    .adm-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); box-shadow: var(--ds-shadow-sm); padding: 20px; margin-bottom: 20px; }
                    .adm-card__title { display: flex; align-items: center; gap: 8px; font-family: var(--ds-font-heading); font-size: 1.05rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0 0 14px; padding-bottom: 12px; border-bottom: 1px solid var(--ds-border); }
                    .adm-card__title i { color: var(--ds-brand-600); }

                    .adm-input { width: 100%; border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius); padding: 10px 13px; font-size: .9rem; color: var(--ds-ink); background: var(--ds-surface); font-family: var(--ds-font-sans); }
                    .adm-input:focus { outline: none; border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); }
                    .adm-add-btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; width: 100%; background: var(--ds-brand-600); color: #fff; font-weight: 700; padding: 10px; border: 0; border-radius: var(--ds-radius); cursor: pointer; }
                    .adm-add-btn:hover { background: var(--ds-brand-700); }

                    .adm-stats3 { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 12px; margin-bottom: 18px; }
                    .adm-stat { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius); padding: 14px; position: relative; overflow: hidden; }
                    .adm-stat::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
                    .adm-stat--brand::before { background: var(--ds-brand-500); }
                    .adm-stat--success::before { background: #1f8a4d; }
                    .adm-stat--slate::before { background: #64748b; }
                    .adm-stat__label { color: var(--ds-muted); font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .04em; }
                    .adm-stat__value { font-family: var(--ds-font-heading); font-size: 1.7rem; font-weight: 800; color: var(--ds-ink-strong); line-height: 1.1; }

                    .adm-filter { background: var(--ds-surface-2); border: 1px solid var(--ds-border); border-radius: var(--ds-radius); padding: 14px; margin-bottom: 16px; }
                    .adm-filter label { font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; color: var(--ds-muted); margin-bottom: 5px; display: block; }
                    .adm-filter .form-control, .adm-filter .form-select { width: 100%; border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius); padding: 9px 12px; font-size: .87rem; color: var(--ds-ink); background: var(--ds-surface); }
                    .adm-filter .form-control:focus, .adm-filter .form-select:focus { outline: none; border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); }
                    .adm-filter__btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; width: 100%; background: var(--ds-brand-600); color: #fff; font-weight: 700; padding: 9px; border: 0; border-radius: var(--ds-radius); cursor: pointer; }

                    .adm-table-wrap { overflow-x: auto; }
                    .adm-table { width: 100%; border-collapse: collapse; min-width: 640px; }
                    .adm-table thead th { text-align: left; font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .04em; color: var(--ds-muted); padding: 11px 12px; border-bottom: 2px solid var(--ds-border); }
                    .adm-table tbody td { padding: 10px 12px; border-bottom: 1px solid var(--ds-border); color: var(--ds-ink); font-size: .88rem; vertical-align: middle; }
                    .adm-table tbody tr:hover { background: var(--ds-surface-2); }
                    .adm-cell-input { width: 100%; border: 1px solid transparent; border-radius: 8px; padding: 7px 10px; font-size: .88rem; color: var(--ds-ink); background: transparent; font-family: var(--ds-font-sans); transition: all var(--ds-transition); }
                    .adm-cell-input:hover { border-color: var(--ds-border); background: var(--ds-surface); }
                    .adm-cell-input:focus { outline: none; border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); background: var(--ds-surface); }
                    .adm-cell-input.is-name { font-weight: 800; color: var(--ds-ink-strong); }
                    .adm-pill { display: inline-flex; align-items: center; height: 24px; padding: 0 12px; border-radius: var(--ds-radius-pill); font-size: .76rem; font-weight: 800; background: var(--ds-brand-50); color: var(--ds-brand-700); white-space: nowrap; }
                    .adm-ico { width: 34px; height: 34px; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--ds-border); background: var(--ds-surface); cursor: pointer; font-size: 1.1rem; transition: all var(--ds-transition); }
                    .adm-ico--save { color: #11703a; } .adm-ico--save:hover { background: #1f8a4d; color: #fff; border-color: #1f8a4d; }
                    .adm-ico--del { color: var(--ds-danger); } .adm-ico--del:hover { background: var(--ds-danger); color: #fff; border-color: var(--ds-danger); }
                    .adm-empty { text-align: center; color: var(--ds-muted); padding: 28px; }

                    .admin-pagination-wrap { display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap; margin-top: 20px; }
                    .admin-pagination-summary { color: var(--ds-muted); font-size: .85rem; font-weight: 600; }
                    .admin-pagination { display: flex; gap: 6px; flex-wrap: wrap; }
                    .page-link-nav { min-width: 40px; height: 40px; padding: 0 12px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--ds-radius); border: 1px solid var(--ds-border); background: var(--ds-surface); color: var(--ds-ink); text-decoration: none; font-weight: 700; font-size: .9rem; transition: all var(--ds-transition); }
                    .page-link-nav:hover:not(.is-disabled) { border-color: var(--ds-brand-300); color: var(--ds-brand-700); }
                    .page-link-nav.is-active { background: var(--ds-brand-600); border-color: var(--ds-brand-600); color: #fff; }
                    .page-link-nav.is-disabled { pointer-events: none; opacity: .4; }

                    @media (min-width: 768px) { .adm-stats3 { } }
                </style>

                <div class="adm-head">
                    <a href="<?= ROOT ?>/Admins/dashboard" class="adm-back"><i class='bx bx-left-arrow-alt'></i></a>
                    <div>
                        <h1>Gestion des catégories</h1>
                        <p>Organisation thématique des projets de la plateforme.</p>
                    </div>
                </div>

                <div class="adm-card">
                    <h2 class="adm-card__title"><i class='bx bx-plus-circle'></i> Ajouter une catégorie</h2>
                    <form method="POST" action="<?= ROOT ?>/Admins/categories" class="row gy-2 gx-2 align-items-end">
                        <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                        <div class="col-md-4"><input type="text" name="nom" class="adm-input" placeholder="Nom (ex : Intelligence Artificielle)" required></div>
                        <div class="col-md-6"><input type="text" name="description" class="adm-input" placeholder="Brève description…"></div>
                        <div class="col-md-2"><button class="adm-add-btn" type="submit" name="add_category"><i class='bx bx-plus'></i> Ajouter</button></div>
                    </form>
                </div>

                <div class="adm-card">
                    <h2 class="adm-card__title"><i class='bx bx-category'></i> Liste des catégories</h2>
                    <div class="adm-stats3">
                        <div class="adm-stat adm-stat--brand"><div class="adm-stat__label">Total</div><div class="adm-stat__value"><?= (int) ($categoryStats['total'] ?? 0) ?></div></div>
                        <div class="adm-stat adm-stat--success"><div class="adm-stat__label">Avec projets</div><div class="adm-stat__value"><?= (int) ($categoryStats['used'] ?? 0) ?></div></div>
                        <div class="adm-stat adm-stat--slate"><div class="adm-stat__label">Sans projets</div><div class="adm-stat__value"><?= (int) ($categoryStats['unused'] ?? 0) ?></div></div>
                    </div>

                    <div class="adm-filter">
                        <form method="GET" action="<?= ROOT ?>/Admins/categories" class="row gy-2 gx-2" id="category-filter-form">
                            <div class="col-md-4"><label>Recherche</label><input type="text" name="search" value="<?= htmlspecialchars($categorySearch) ?>" class="form-control" placeholder="Nom de catégorie…"></div>
                            <div class="col-md-3 col-6"><label>Utilisation</label><select name="usage" class="form-select auto-submit-filter"><option value="all" <?= $categoryUsageFilter === 'all' ? 'selected' : '' ?>>Tout</option><option value="used" <?= $categoryUsageFilter === 'used' ? 'selected' : '' ?>>Utilisées</option><option value="unused" <?= $categoryUsageFilter === 'unused' ? 'selected' : '' ?>>Non utilisées</option></select></div>
                            <div class="col-md-2 col-6"><label>Tri</label><select name="sort_by" class="form-select auto-submit-filter"><option value="name" <?= $categorySortBy === 'name' ? 'selected' : '' ?>>Nom</option><option value="projects" <?= $categorySortBy === 'projects' ? 'selected' : '' ?>>Usage</option></select></div>
                            <div class="col-md-1 col-6"><label>Ordre</label><select name="sort_dir" class="form-select auto-submit-filter"><option value="asc" <?= $categorySortDir === 'asc' ? 'selected' : '' ?>>↑</option><option value="desc" <?= $categorySortDir === 'desc' ? 'selected' : '' ?>>↓</option></select></div>
                            <div class="col-md-2 col-6 d-flex align-items-end"><button class="adm-filter__btn" type="submit"><i class='bx bx-search'></i> OK</button></div>
                        </form>
                    </div>

                    <div class="adm-table-wrap">
                        <table class="adm-table">
                            <thead><tr><th>#</th><th>Nom</th><th>Description</th><th>Projets</th><th>Actions</th></tr></thead>
                            <tbody>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <?php $cid = (int) ($category->id ?? 0); $formId = 'category-form-' . $cid; ?>
                                        <tr>
                                            <form method="POST" action="<?= ROOT ?>/Admins/categories" id="<?= $formId ?>">
                                                <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                                <input type="hidden" name="id" value="<?= $cid ?>">
                                            </form>
                                            <td class="text-muted" data-label="ID">#<?= $cid ?></td>
                                            <td data-label="Nom"><input type="text" name="nom" value="<?= htmlspecialchars((string) ($category->nom ?? '')) ?>" class="adm-cell-input is-name" form="<?= $formId ?>"></td>
                                            <td data-label="Description"><input type="text" name="description" value="<?= htmlspecialchars((string) ($category->description ?? '')) ?>" class="adm-cell-input" form="<?= $formId ?>" placeholder="—"></td>
                                            <td data-label="Projets"><span class="adm-pill"><?= (int) ($category->total_projects ?? 0) ?> projet(s)</span></td>
                                            <td class="is-cardaction">
                                                <div class="d-flex gap-2">
                                                    <button class="adm-ico adm-ico--save" name="update_category" type="submit" form="<?= $formId ?>" title="Enregistrer"><i class='bx bx-save'></i></button>
                                                    <button class="adm-ico adm-ico--del" name="delete_category" type="submit" form="<?= $formId ?>" onclick="return confirm('Supprimer cette catégorie ?')" title="Supprimer"><i class='bx bx-trash'></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="adm-empty">Aucune catégorie disponible.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php $this->view('Partials/admin-pagination', [
                        'currentPage' => $currentPage,
                        'perPage' => $perPage,
                        'totalPages' => $totalPages,
                        'totalItems' => $totalItems,
                        'basePath' => 'Admins/categories',
                        'queryString' => $paginationQuery,
                        'itemLabel' => 'catégorie(s)',
                    ]); ?>
                </div>
            </div>
            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>

<?php $this->view('Partials/scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.auto-submit-filter').forEach(function (el) {
            el.addEventListener('change', function () {
                var form = document.getElementById('category-filter-form');
                if (form) { form.submit(); }
            });
        });
    });
</script>
</body>
</html>
