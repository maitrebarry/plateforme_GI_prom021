<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Projets les plus suivis']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$projects = $projects ?? [];
$projectPlatformStats = $projectPlatformStats ?? [];
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 10);
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalItems = max(0, (int) ($totalItems ?? count($projects ?? [])));
$paginationQuery = (string) ($paginationQuery ?? '');
$platStats = [
    ['Abonnés', (int) ($projectPlatformStats['follows'] ?? 0), 'bxs-bell', 'brand'],
    ['Likes', (int) ($projectPlatformStats['likes'] ?? 0), 'bxs-heart', 'danger'],
    ['Avis', (int) ($projectPlatformStats['reviews'] ?? 0), 'bxs-message-square-detail', 'blue'],
    ['Messages', (int) ($projectPlatformStats['messages'] ?? 0), 'bx-mail-send', 'blue'],
    ['Note moyenne', number_format((float) ($projectPlatformStats['average_rating'] ?? 0), 1) . '/5', 'bxs-star', 'accent'],
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
                    .adm-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 18px; }
                    .adm-head__l { display: flex; align-items: center; gap: 12px; }
                    .adm-back { width: 42px; height: 42px; border-radius: 12px; background: var(--ds-surface); border: 1px solid var(--ds-border); display: inline-flex; align-items: center; justify-content: center; color: var(--ds-ink); text-decoration: none; font-size: 1.2rem; transition: all var(--ds-transition); }
                    .adm-back:hover { background: var(--ds-brand-50); color: var(--ds-brand-700); }
                    .adm-head h1 { display: flex; align-items: center; gap: 8px; font-family: var(--ds-font-heading); font-size: 1.35rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0; }
                    .adm-head h1 i { color: var(--ds-brand-600); }
                    .adm-pp { display: flex; gap: 8px; }
                    .adm-pp select { border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius-pill); padding: 8px 14px; font-size: .85rem; color: var(--ds-ink); background: var(--ds-surface); }
                    .adm-pp button { background: var(--ds-brand-600); color: #fff; font-weight: 700; font-size: .85rem; border: 0; border-radius: var(--ds-radius-pill); padding: 8px 16px; cursor: pointer; }

                    .adm-stats { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 13px; margin-bottom: 22px; }
                    .adm-stat { position: relative; overflow: hidden; background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); padding: 15px; box-shadow: var(--ds-shadow-sm); }
                    .adm-stat::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
                    .adm-stat--danger::before { background: var(--ds-danger); } .adm-stat--danger .adm-stat__icon { background: var(--ds-danger-soft); color: var(--ds-danger); }
                    .adm-stat--brand::before { background: var(--ds-brand-500); } .adm-stat--brand .adm-stat__icon { background: var(--ds-brand-50); color: var(--ds-brand-600); }
                    .adm-stat--blue::before { background: #1d59b8; } .adm-stat--blue .adm-stat__icon { background: #e3effb; color: #1d59b8; }
                    .adm-stat--accent::before { background: var(--ds-accent); } .adm-stat--accent .adm-stat__icon { background: var(--ds-accent-soft); color: #8a6310; }
                    .adm-stat__icon { width: 36px; height: 36px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-bottom: 8px; }
                    .adm-stat__value { font-family: var(--ds-font-heading); font-size: 1.6rem; font-weight: 800; color: var(--ds-ink-strong); line-height: 1; }
                    .adm-stat__label { color: var(--ds-muted); font-size: .73rem; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; margin-top: 5px; }

                    .adm-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); box-shadow: var(--ds-shadow-sm); padding: 20px; }
                    .adm-card__title { display: flex; align-items: center; gap: 8px; font-family: var(--ds-font-heading); font-size: 1.08rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0 0 14px; }
                    .adm-card__title i { color: var(--ds-brand-600); }
                    .adm-table-wrap { overflow-x: auto; }
                    .adm-table { width: 100%; border-collapse: collapse; min-width: 760px; }
                    .adm-table thead th { text-align: left; font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .04em; color: var(--ds-muted); padding: 11px 12px; border-bottom: 2px solid var(--ds-border); white-space: nowrap; }
                    .adm-table tbody td { padding: 12px; border-bottom: 1px solid var(--ds-border); color: var(--ds-ink); font-size: .88rem; font-weight: 600; vertical-align: middle; }
                    .adm-table tbody tr:hover { background: var(--ds-surface-2); }
                    .adm-table .t-title { font-family: var(--ds-font-heading); font-weight: 800; color: var(--ds-ink-strong); }
                    .adm-rank { display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 8px; background: var(--ds-surface-2); color: var(--ds-muted); font-weight: 800; font-size: .8rem; }
                    .adm-pill { display: inline-flex; align-items: center; height: 22px; padding: 0 10px; border-radius: var(--ds-radius-pill); font-size: .7rem; font-weight: 800; text-transform: uppercase; }
                    .adm-pill--cat { background: var(--ds-surface-2); color: var(--ds-muted); }
                    .adm-pill--pending { background: var(--ds-accent-soft); color: #8a6310; }
                    .adm-pill--valide { background: #e4f3ea; color: #11703a; }
                    .adm-pill--rejete { background: var(--ds-danger-soft); color: #a3322e; }
                    .adm-metric { font-weight: 700; white-space: nowrap; }
                    .adm-metric .bxs-heart { color: var(--ds-danger); } .adm-metric .bxs-star { color: var(--ds-accent); } .adm-metric .bx { color: var(--ds-brand-600); vertical-align: -1px; }
                    .adm-detail-btn { display: inline-flex; align-items: center; gap: 5px; background: var(--ds-brand-600); color: #fff; font-weight: 700; font-size: .78rem; padding: 6px 13px; border-radius: var(--ds-radius-pill); text-decoration: none; }
                    .adm-detail-btn:hover { background: var(--ds-brand-700); color: #fff; }
                    .adm-empty { text-align: center; color: var(--ds-muted); padding: 28px; }

                    .admin-pagination-wrap { display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap; margin-top: 20px; }
                    .admin-pagination-summary { color: var(--ds-muted); font-size: .85rem; font-weight: 600; }
                    .admin-pagination { display: flex; gap: 6px; flex-wrap: wrap; }
                    .page-link-nav { min-width: 40px; height: 40px; padding: 0 12px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--ds-radius); border: 1px solid var(--ds-border); background: var(--ds-surface); color: var(--ds-ink); text-decoration: none; font-weight: 700; font-size: .9rem; transition: all var(--ds-transition); }
                    .page-link-nav:hover:not(.is-disabled) { border-color: var(--ds-brand-300); color: var(--ds-brand-700); }
                    .page-link-nav.is-active { background: var(--ds-brand-600); border-color: var(--ds-brand-600); color: #fff; }
                    .page-link-nav.is-disabled { pointer-events: none; opacity: .4; }

                    @media (min-width: 768px) { .adm-stats { grid-template-columns: repeat(4, minmax(0,1fr)); } }
                </style>

                <div class="adm-head">
                    <div class="adm-head__l">
                        <a href="<?= ROOT ?>/Admins/dashboard" class="adm-back"><i class='bx bx-left-arrow-alt'></i></a>
                        <h1><i class='bx bx-trophy'></i> Projets les plus suivis</h1>
                    </div>
                    <form method="GET" action="<?= ROOT ?>/Admins/most_followed_projects" class="adm-pp">
                        <select name="per_page"><?php foreach ([10, 20, 50] as $pp): ?><option value="<?= $pp ?>" <?= $perPage === $pp ? 'selected' : '' ?>><?= $pp ?> / page</option><?php endforeach; ?></select>
                        <button type="submit"><i class='bx bx-refresh'></i> Actualiser</button>
                    </form>
                </div>

                <div class="adm-stats">
                    <?php foreach ($platStats as $s): ?>
                        <div class="adm-stat adm-stat--<?= $s[3] ?>"><span class="adm-stat__icon"><i class='bx <?= $s[2] ?>'></i></span><div class="adm-stat__value"><?= $s[1] ?></div><div class="adm-stat__label"><?= htmlspecialchars($s[0]) ?></div></div>
                    <?php endforeach; ?>
                </div>

                <div class="adm-card">
                    <h2 class="adm-card__title"><i class='bx bx-list-ol'></i> Classement des projets</h2>
                    <div class="adm-table-wrap">
                        <table class="adm-table">
                            <thead><tr><th>#</th><th>Titre</th><th>Auteur</th><th>Catégorie</th><th>Statut</th><th>Abonnés</th><th>Likes</th><th>Avis</th><th>Msg</th><th>Note</th><th></th></tr></thead>
                            <tbody>
                                <?php if (!empty($projects)): ?>
                                    <?php foreach ($projects as $index => $project): ?>
                                        <?php $st = (string) ($project->statut ?? 'en_attente'); $stCls = $st === 'valide' ? 'valide' : ($st === 'rejete' ? 'rejete' : 'pending'); ?>
                                        <tr>
                                            <td data-label="Rang"><span class="adm-rank"><?= (($currentPage - 1) * $perPage) + $index + 1 ?></span></td>
                                            <td class="t-title is-cardtitle"><?= htmlspecialchars((string) ($project->title ?? 'Projet')) ?></td>
                                            <td data-label="Auteur"><?= htmlspecialchars((string) ($project->auteur ?? '-')) ?></td>
                                            <td data-label="Catégorie"><span class="adm-pill adm-pill--cat"><?= htmlspecialchars((string) ($project->categorie ?? '-')) ?></span></td>
                                            <td data-label="Statut"><span class="adm-pill adm-pill--<?= $stCls ?>"><?= htmlspecialchars($st) ?></span></td>
                                            <td data-label="Abonnés"><span class="adm-metric" style="font-weight:800;color:var(--ds-brand-700)"><i class='bx bxs-bell'></i> <?= (int) ($project->follows_count ?? 0) ?></span></td>
                                            <td data-label="Likes"><span class="adm-metric"><i class='bx bxs-heart'></i> <?= (int) ($project->likes_count ?? 0) ?></span></td>
                                            <td data-label="Avis"><span class="adm-metric"><i class='bx bxs-message-square-detail'></i> <?= (int) ($project->reviews_count ?? 0) ?></span></td>
                                            <td data-label="Msg"><span class="adm-metric"><i class='bx bx-mail-send'></i> <?= (int) ($project->messages_count ?? 0) ?></span></td>
                                            <td data-label="Note"><span class="adm-metric"><i class='bx bxs-star'></i> <?= number_format((float) ($project->average_rating ?? 0), 1) ?></span></td>
                                            <td class="is-cardaction"><a href="<?= ROOT ?>/Admins/project_detail/<?= (int) ($project->id ?? 0) ?>" class="adm-detail-btn"><i class='bx bx-show'></i> Détail</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="11" class="adm-empty">Aucun projet suivi disponible.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php $this->view('Partials/admin-pagination', [
                        'currentPage' => $currentPage, 'perPage' => $perPage, 'totalPages' => $totalPages,
                        'totalItems' => $totalItems, 'basePath' => 'Admins/most_followed_projects',
                        'queryString' => $paginationQuery, 'itemLabel' => 'projet(s)',
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
