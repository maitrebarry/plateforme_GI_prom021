<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Gestion des projets']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$projectSearch = $projectSearch ?? '';
$projectStatusFilter = $projectStatusFilter ?? 'all';
$projectCategoryFilter = $projectCategoryFilter ?? null;
$projectSortBy = $projectSortBy ?? 'date';
$projectSortDir = $projectSortDir ?? 'desc';
$categories = $categories ?? [];
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 10);
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalItems = max(0, (int) ($totalItems ?? count($projects ?? [])));
$paginationQuery = (string) ($paginationQuery ?? '');
$dashboardStats = $dashboardStats ?? [];
$projects = $projects ?? [];
$csrf = (string) ($_SESSION['csrf_token'] ?? '');
$pendingCount = (int) ($dashboardStats['pending'] ?? 0);
$validatedCount = (int) ($dashboardStats['validated'] ?? 0);
$rejectedCount = (int) ($dashboardStats['rejected'] ?? 0);
$statusTabs = [
    ['all', 'Tous', $pendingCount + $validatedCount + $rejectedCount],
    ['en_attente', 'À valider', $pendingCount],
    ['valide', 'Validés', $validatedCount],
    ['rejete', 'Rejetés', $rejectedCount],
];
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

                    .adm-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); box-shadow: var(--ds-shadow-sm); padding: 20px; }
                    .adm-tabs { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 18px; }
                    .adm-tab { display: inline-flex; align-items: center; gap: 8px; padding: 9px 15px; border-radius: var(--ds-radius-pill); background: var(--ds-surface); border: 1px solid var(--ds-border); color: var(--ds-ink); text-decoration: none; font-weight: 700; font-size: .86rem; transition: all var(--ds-transition); }
                    .adm-tab:hover { border-color: var(--ds-brand-300); color: var(--ds-brand-700); }
                    .adm-tab.is-active { background: var(--ds-brand-600); border-color: var(--ds-brand-600); color: #fff; }
                    .adm-tab__count { min-width: 22px; height: 20px; padding: 0 6px; border-radius: var(--ds-radius-pill); display: inline-flex; align-items: center; justify-content: center; background: var(--ds-surface-2); color: var(--ds-muted); font-size: .72rem; font-weight: 800; }
                    .adm-tab.is-active .adm-tab__count { background: rgba(255,255,255,.22); color: #fff; }

                    .adm-filter { background: var(--ds-surface-2); border: 1px solid var(--ds-border); border-radius: var(--ds-radius); padding: 14px; margin-bottom: 18px; }
                    .adm-filter label { font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; color: var(--ds-muted); margin-bottom: 5px; display: block; }
                    .adm-filter .form-control, .adm-filter .form-select { width: 100%; border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius); padding: 9px 12px; font-size: .87rem; color: var(--ds-ink); background: var(--ds-surface); font-family: var(--ds-font-sans); }
                    .adm-filter .form-control:focus, .adm-filter .form-select:focus { outline: none; border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); }
                    .adm-filter__btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; width: 100%; background: var(--ds-brand-600); color: #fff; font-weight: 700; padding: 9px; border: 0; border-radius: var(--ds-radius); cursor: pointer; }
                    .adm-filter__btn:hover { background: var(--ds-brand-700); }

                    .adm-bulk { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; background: var(--ds-surface-2); border: 1px solid var(--ds-border); border-radius: var(--ds-radius); padding: 12px 16px; margin-bottom: 14px; }
                    .adm-bulk__check { display: inline-flex; align-items: center; gap: 8px; font-weight: 700; font-size: .82rem; color: var(--ds-ink); }
                    .adm-bulk__actions { display: flex; flex-wrap: wrap; gap: 8px; }
                    .adm-bbtn { display: inline-flex; align-items: center; gap: 6px; font-weight: 700; font-size: .8rem; padding: 8px 14px; border-radius: var(--ds-radius-pill); border: 0; cursor: pointer; color: #fff; }
                    .adm-bbtn--ok { background: #1f8a4d; } .adm-bbtn--wait { background: var(--ds-accent); color: #3d2900; } .adm-bbtn--no { background: var(--ds-danger); }

                    .adm-table-wrap { overflow-x: auto; }
                    .adm-table { width: 100%; border-collapse: collapse; min-width: 720px; }
                    .adm-table thead th { text-align: left; font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .04em; color: var(--ds-muted); padding: 11px 12px; border-bottom: 2px solid var(--ds-border); white-space: nowrap; }
                    .adm-table tbody td { padding: 12px; border-bottom: 1px solid var(--ds-border); color: var(--ds-ink); font-size: .88rem; font-weight: 600; vertical-align: middle; }
                    .adm-table tbody tr:hover { background: var(--ds-surface-2); }
                    .adm-table .t-title { font-family: var(--ds-font-heading); font-weight: 800; color: var(--ds-ink-strong); }
                    .adm-author { display: inline-flex; align-items: center; gap: 8px; }
                    .adm-author__ava { width: 28px; height: 28px; border-radius: 50%; background: var(--ds-brand-100); color: var(--ds-brand-700); display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: .72rem; }
                    .adm-pill { display: inline-flex; align-items: center; height: 22px; padding: 0 11px; border-radius: var(--ds-radius-pill); font-size: .7rem; font-weight: 800; text-transform: uppercase; }
                    .adm-pill--cat { background: var(--ds-surface-2); color: var(--ds-muted); }
                    .adm-pill--pending { background: var(--ds-accent-soft); color: #8a6310; }
                    .adm-pill--valide { background: #e4f3ea; color: #11703a; }
                    .adm-pill--rejete { background: var(--ds-danger-soft); color: #a3322e; }
                    .adm-row-actions { display: flex; gap: 6px; }
                    .adm-ico { width: 32px; height: 32px; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--ds-border); background: var(--ds-surface); color: var(--ds-ink); cursor: pointer; font-size: 1.05rem; text-decoration: none; transition: all var(--ds-transition); }
                    .adm-ico:hover { transform: translateY(-1px); }
                    .adm-ico--view:hover { background: var(--ds-brand-50); color: var(--ds-brand-700); border-color: var(--ds-brand-300); }
                    .adm-ico--ok:hover { background: #1f8a4d; color: #fff; border-color: #1f8a4d; }
                    .adm-ico--wait:hover { background: var(--ds-accent); color: #3d2900; border-color: var(--ds-accent); }
                    .adm-ico--no:hover { background: var(--ds-danger); color: #fff; border-color: var(--ds-danger); }
                    .adm-check { width: 18px; height: 18px; accent-color: var(--ds-brand-600); cursor: pointer; }
                    .adm-empty { text-align: center; color: var(--ds-muted); padding: 30px; }

                    /* Pagination (admin-pagination partial) */
                    .admin-pagination-wrap { display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap; margin-top: 20px; }
                    .admin-pagination-summary { color: var(--ds-muted); font-size: .85rem; font-weight: 600; }
                    .admin-pagination { display: flex; gap: 6px; flex-wrap: wrap; }
                    .page-link-nav { min-width: 40px; height: 40px; padding: 0 12px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--ds-radius); border: 1px solid var(--ds-border); background: var(--ds-surface); color: var(--ds-ink); text-decoration: none; font-weight: 700; font-size: .9rem; transition: all var(--ds-transition); }
                    .page-link-nav:hover:not(.is-disabled) { border-color: var(--ds-brand-300); color: var(--ds-brand-700); }
                    .page-link-nav.is-active { background: var(--ds-brand-600); border-color: var(--ds-brand-600); color: #fff; }
                    .page-link-nav.is-disabled { pointer-events: none; opacity: .4; }
                </style>

                <div class="adm-head">
                    <a href="<?= ROOT ?>/Admins/dashboard" class="adm-back"><i class='bx bx-left-arrow-alt'></i></a>
                    <div>
                        <h1>Gestion des projets</h1>
                        <p>Validation, suivi et modération des publications.</p>
                    </div>
                </div>

                <div class="adm-card">
                    <div class="adm-tabs">
                        <?php foreach ($statusTabs as $t): ?>
                            <?php $q = $t[0] === 'all' ? '' : ('?status=' . urlencode($t[0])); ?>
                            <a class="adm-tab <?= $projectStatusFilter === $t[0] ? 'is-active' : '' ?>" href="<?= ROOT ?>/Admins/projects_management<?= $q ?>"><?= htmlspecialchars($t[1]) ?> <span class="adm-tab__count"><?= (int) $t[2] ?></span></a>
                        <?php endforeach; ?>
                    </div>

                    <div class="adm-filter">
                        <form method="GET" action="<?= ROOT ?>/Admins/projects_management" class="row gy-2 gx-2">
                            <div class="col-md-4"><label>Recherche</label><input type="text" name="search" value="<?= htmlspecialchars($projectSearch) ?>" class="form-control" placeholder="Titre, auteur…"></div>
                            <div class="col-md-2 col-6"><label>Statut</label><select name="status" class="form-select"><option value="all" <?= $projectStatusFilter === 'all' ? 'selected' : '' ?>>Tous</option><option value="en_attente" <?= $projectStatusFilter === 'en_attente' ? 'selected' : '' ?>>En attente</option><option value="valide" <?= $projectStatusFilter === 'valide' ? 'selected' : '' ?>>Validé</option><option value="rejete" <?= $projectStatusFilter === 'rejete' ? 'selected' : '' ?>>Rejeté</option></select></div>
                            <div class="col-md-3 col-6"><label>Catégorie</label><select name="category" class="form-select"><option value="">Toutes</option><?php foreach ($categories as $category): ?><option value="<?= (int) ($category->id ?? 0) ?>" <?= (int) $projectCategoryFilter === (int) ($category->id ?? 0) ? 'selected' : '' ?>><?= htmlspecialchars((string) ($category->nom ?? 'Catégorie')) ?></option><?php endforeach; ?></select></div>
                            <div class="col-md-2 col-6"><label>Tri</label><select name="sort_by" class="form-select"><option value="date" <?= $projectSortBy === 'date' ? 'selected' : '' ?>>Date</option><option value="title" <?= $projectSortBy === 'title' ? 'selected' : '' ?>>Titre</option><option value="author" <?= $projectSortBy === 'author' ? 'selected' : '' ?>>Auteur</option></select></div>
                            <div class="col-md-1 col-6"><label>Ordre</label><select name="sort_dir" class="form-select"><option value="desc" <?= $projectSortDir === 'desc' ? 'selected' : '' ?>>↓</option><option value="asc" <?= $projectSortDir === 'asc' ? 'selected' : '' ?>>↑</option></select></div>
                            <div class="col-12"><button class="adm-filter__btn" type="submit" style="width:auto;padding:9px 22px"><i class='bx bx-filter-alt'></i> Filtrer</button></div>
                        </form>
                    </div>

                    <form method="POST" action="<?= ROOT ?>/Admins/projects_management">
                        <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                        <div class="adm-bulk">
                            <label class="adm-bulk__check"><input class="adm-check" type="checkbox" id="select-all-management"> Tout sélectionner</label>
                            <div class="adm-bulk__actions">
                                <button class="adm-bbtn adm-bbtn--ok" type="submit" name="bulk_validate_projects"><i class='bx bx-check'></i> Valider</button>
                                <button class="adm-bbtn adm-bbtn--wait" type="submit" name="bulk_set_pending_projects"><i class='bx bx-time'></i> Attente</button>
                                <button class="adm-bbtn adm-bbtn--no" type="submit" name="bulk_reject_projects" onclick="return confirm('Rejeter les projets sélectionnés ?');"><i class='bx bx-x'></i> Rejeter</button>
                            </div>
                        </div>

                        <div class="adm-table-wrap">
                            <table class="adm-table">
                                <thead><tr><th></th><th>Titre</th><th>Auteur</th><th>Catégorie</th><th>Date</th><th>Statut</th><th>Actions</th></tr></thead>
                                <tbody>
                                    <?php if (!empty($projects)): ?>
                                        <?php foreach ($projects as $project): ?>
                                            <?php $st = (string) ($project->statut ?? $project->statut_admin ?? 'en_attente'); $stCls = $st === 'valide' ? 'valide' : ($st === 'rejete' ? 'rejete' : 'pending'); $pid = (int) ($project->id ?? 0); $auteur = (string) ($project->auteur ?? '-'); ?>
                                            <tr>
                                                <td class="is-cardcheck"><input type="checkbox" class="management-project-checkbox adm-check" name="project_ids[]" value="<?= $pid ?>"></td>
                                                <td class="t-title is-cardtitle"><?= htmlspecialchars($project->title ?? '') ?></td>
                                                <td data-label="Auteur"><span class="adm-author"><span class="adm-author__ava"><?= htmlspecialchars(strtoupper(mb_substr($auteur, 0, 1))) ?></span><?= htmlspecialchars($auteur) ?></span></td>
                                                <td data-label="Catégorie"><span class="adm-pill adm-pill--cat"><?= htmlspecialchars($project->categorie ?? '-') ?></span></td>
                                                <td class="text-muted" data-label="Date"><?= htmlspecialchars(date('d/m/Y', strtotime((string) ($project->created_at ?? 'now')))) ?></td>
                                                <td data-label="Statut"><span class="adm-pill adm-pill--<?= $stCls ?>"><?= htmlspecialchars($st) ?></span></td>
                                                <td class="is-cardaction">
                                                    <div class="adm-row-actions">
                                                        <a href="<?= ROOT ?>/Admins/project_detail/<?= $pid ?>" class="adm-ico adm-ico--view" title="Détails"><i class='bx bx-show'></i></a>
                                                        <button class="adm-ico adm-ico--ok" type="submit" name="single_validate_project" value="<?= $pid ?>" title="Valider"><i class='bx bx-check'></i></button>
                                                        <button class="adm-ico adm-ico--wait" type="submit" name="single_set_pending_project" value="<?= $pid ?>" title="Mettre en attente"><i class='bx bx-time'></i></button>
                                                        <button class="adm-ico adm-ico--no" type="submit" name="single_reject_project" value="<?= $pid ?>" onclick="return confirm('Rejeter ce projet ?');" title="Rejeter"><i class='bx bx-x'></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="7" class="adm-empty">Aucun projet trouvé.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <?php $this->view('Partials/admin-pagination', [
                        'currentPage' => $currentPage,
                        'perPage' => $perPage,
                        'totalPages' => $totalPages,
                        'totalItems' => $totalItems,
                        'basePath' => 'Admins/projects_management',
                        'queryString' => $paginationQuery,
                        'itemLabel' => 'projet(s)',
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
        var selectAll = document.getElementById('select-all-management');
        if (!selectAll) { return; }
        var checkboxes = Array.from(document.querySelectorAll('.management-project-checkbox'));
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(function (cb) { cb.checked = selectAll.checked; });
        });
    });
</script>
</body>
</html>
