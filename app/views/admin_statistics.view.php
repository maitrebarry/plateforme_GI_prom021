<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Statistiques administrateur']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$dashboardStats = $dashboardStats ?? [];
$projectPlatformStats = $projectPlatformStats ?? [];
$categoryStats = $categoryStats ?? [];
$monthlyStats = $monthlyStats ?? [];
$topAuthors = $topAuthors ?? [];
$statusTotal = max(1, (int) (($dashboardStats['validated'] ?? 0) + ($dashboardStats['pending'] ?? 0) + ($dashboardStats['rejected'] ?? 0)));
$maxCategory = 1; foreach ($categoryStats as $item) { $maxCategory = max($maxCategory, (int) ($item->total ?? 0)); }
$maxAuthor = 1; foreach ($topAuthors as $item) { $maxAuthor = max($maxAuthor, (int) ($item->total ?? 0)); }
$maxMonthly = 1; foreach ($monthlyStats as $item) { $maxMonthly = max($maxMonthly, (int) ($item->total ?? 0)); }
$monthlyCount = count($monthlyStats);
$chartPoints = [];
if ($monthlyCount > 0) {
    foreach ($monthlyStats as $index => $item) {
        $x = $monthlyCount === 1 ? 210 : (20 + (($index / max(1, $monthlyCount - 1)) * 380));
        $y = 150 - (((int) ($item->total ?? 0) / $maxMonthly) * 110);
        $chartPoints[] = round($x, 2) . ',' . round($y, 2);
    }
}
$statPills = [
    ['Utilisateurs', (int) ($dashboardStats['users'] ?? 0), 'bx-group', 'brand'],
    ['Projets', (int) ($dashboardStats['projects'] ?? 0), 'bx-folder', 'blue'],
    ['Likes', (int) ($projectPlatformStats['likes'] ?? 0), 'bxs-heart', 'danger'],
    ['Avis', (int) ($projectPlatformStats['reviews'] ?? 0), 'bxs-star', 'accent'],
    ['Messages', (int) ($projectPlatformStats['messages'] ?? 0), 'bx-mail-send', 'blue'],
    ['Note moy.', number_format((float) ($projectPlatformStats['average_rating'] ?? 0), 1) . '/5', 'bx-bar-chart-alt-2', 'success'],
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
                    .st-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 18px; }
                    .st-head__l { display: flex; align-items: center; gap: 12px; }
                    .adm-back { width: 42px; height: 42px; border-radius: 12px; background: var(--ds-surface); border: 1px solid var(--ds-border); display: inline-flex; align-items: center; justify-content: center; color: var(--ds-ink); text-decoration: none; font-size: 1.2rem; transition: all var(--ds-transition); }
                    .adm-back:hover { background: var(--ds-brand-50); color: var(--ds-brand-700); }
                    .st-head h1 { display: flex; align-items: center; gap: 8px; font-family: var(--ds-font-heading); font-size: 1.3rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0; }
                    .st-head h1 i { color: var(--ds-brand-600); }
                    .st-headbtn { display: inline-flex; align-items: center; gap: 7px; background: var(--ds-brand-600); color: #fff; font-weight: 700; font-size: .85rem; padding: 9px 16px; border-radius: var(--ds-radius-pill); text-decoration: none; }
                    .st-headbtn:hover { background: var(--ds-brand-700); color: #fff; }

                    .st-pills { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 12px; margin-bottom: 22px; }
                    .st-pill { position: relative; overflow: hidden; background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); padding: 15px; box-shadow: var(--ds-shadow-sm); }
                    .st-pill::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
                    .st-pill--brand::before { background: var(--ds-brand-500); } .st-pill--brand .st-pill__ic { background: var(--ds-brand-50); color: var(--ds-brand-600); }
                    .st-pill--blue::before { background: #1d59b8; } .st-pill--blue .st-pill__ic { background: #e3effb; color: #1d59b8; }
                    .st-pill--danger::before { background: var(--ds-danger); } .st-pill--danger .st-pill__ic { background: var(--ds-danger-soft); color: var(--ds-danger); }
                    .st-pill--accent::before { background: var(--ds-accent); } .st-pill--accent .st-pill__ic { background: var(--ds-accent-soft); color: #8a6310; }
                    .st-pill--success::before { background: #1f8a4d; } .st-pill--success .st-pill__ic { background: #e4f3ea; color: #11703a; }
                    .st-pill__ic { width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 8px; }
                    .st-pill__v { font-family: var(--ds-font-heading); font-size: 1.5rem; font-weight: 800; color: var(--ds-ink-strong); line-height: 1; }
                    .st-pill__l { color: var(--ds-muted); font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; margin-top: 5px; }

                    .st-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); box-shadow: var(--ds-shadow-sm); padding: 20px; height: 100%; }
                    .st-card h2 { font-family: var(--ds-font-heading); font-size: 1.05rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0 0 16px; padding-bottom: 12px; border-bottom: 1px solid var(--ds-border); }
                    .st-bar { margin-bottom: 16px; }
                    .st-bar__meta { display: flex; justify-content: space-between; font-weight: 700; margin-bottom: 6px; font-size: .82rem; color: var(--ds-ink); }
                    .st-bar__meta .muted { color: var(--ds-muted); }
                    .st-track { height: 9px; background: var(--ds-surface-2); border-radius: var(--ds-radius-pill); overflow: hidden; }
                    .st-fill { height: 100%; border-radius: var(--ds-radius-pill); }
                    .st-fill--ok { background: #1f8a4d; } .st-fill--pending { background: var(--ds-accent); } .st-fill--no { background: var(--ds-danger); } .st-fill--brand { background: var(--ds-brand-500); }
                    .st-chart { padding: 14px; border-radius: var(--ds-radius); background: var(--ds-surface-2); border: 1px solid var(--ds-border); }
                    .st-item { background: var(--ds-surface-2); border: 1px solid var(--ds-border); border-radius: var(--ds-radius); padding: 14px; margin-bottom: 12px; }
                    .st-badges { display: flex; flex-wrap: wrap; gap: 6px; }
                    .st-badge { display: inline-flex; align-items: center; gap: 4px; font-size: .72rem; font-weight: 700; padding: 3px 9px; border-radius: var(--ds-radius-pill); }
                    .st-badge--ok { background: #e4f3ea; color: #11703a; } .st-badge--pending { background: var(--ds-accent-soft); color: #8a6310; } .st-badge--no { background: var(--ds-danger-soft); color: #a3322e; }

                    .st-table-wrap { overflow-x: auto; }
                    .st-table { width: 100%; border-collapse: collapse; min-width: 520px; }
                    .st-table thead th { text-align: left; font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .04em; color: var(--ds-muted); padding: 11px 12px; border-bottom: 2px solid var(--ds-border); }
                    .st-table tbody td { padding: 11px 12px; border-bottom: 1px solid var(--ds-border); color: var(--ds-ink); font-size: .88rem; font-weight: 600; }
                    .st-table .t-mois { font-family: var(--ds-font-heading); font-weight: 800; color: var(--ds-brand-700); }

                    @media (min-width: 768px) { .st-pills { grid-template-columns: repeat(6, minmax(0,1fr)); } .st-head h1 { font-size: 1.5rem; } }
                </style>

                <div class="st-head">
                    <div class="st-head__l">
                        <a href="<?= ROOT ?>/Admins/dashboard" class="adm-back"><i class='bx bx-left-arrow-alt'></i></a>
                        <h1><i class='bx bx-bar-chart-alt-2'></i> Analyses &amp; statistiques</h1>
                    </div>
                    <a href="<?= ROOT ?>/Admins/projects_management" class="st-headbtn"><i class='bx bx-list-ul'></i> Gérer les projets</a>
                </div>

                <div class="st-pills">
                    <?php foreach ($statPills as $s): ?>
                        <div class="st-pill st-pill--<?= $s[3] ?>"><span class="st-pill__ic"><i class='bx <?= $s[2] ?>'></i></span><div class="st-pill__v"><?= $s[1] ?></div><div class="st-pill__l"><?= htmlspecialchars($s[0]) ?></div></div>
                    <?php endforeach; ?>
                </div>

                <div class="row gy-4">
                    <div class="col-xl-5">
                        <div class="st-card">
                            <h2>Répartition des validations</h2>
                            <div class="st-bar"><div class="st-bar__meta"><span>Validés</span><span class="muted"><?= (int) ($dashboardStats['validated'] ?? 0) ?></span></div><div class="st-track"><div class="st-fill st-fill--ok" style="width: <?= round(((int) ($dashboardStats['validated'] ?? 0) / $statusTotal) * 100, 1) ?>%"></div></div></div>
                            <div class="st-bar"><div class="st-bar__meta"><span>En attente</span><span class="muted"><?= (int) ($dashboardStats['pending'] ?? 0) ?></span></div><div class="st-track"><div class="st-fill st-fill--pending" style="width: <?= round(((int) ($dashboardStats['pending'] ?? 0) / $statusTotal) * 100, 1) ?>%"></div></div></div>
                            <div class="st-bar"><div class="st-bar__meta"><span>Rejetés</span><span class="muted"><?= (int) ($dashboardStats['rejected'] ?? 0) ?></span></div><div class="st-track"><div class="st-fill st-fill--no" style="width: <?= round(((int) ($dashboardStats['rejected'] ?? 0) / $statusTotal) * 100, 1) ?>%"></div></div></div>
                        </div>
                    </div>

                    <div class="col-xl-7">
                        <div class="st-card">
                            <h2>Évolution mensuelle</h2>
                            <div class="st-chart">
                                <?php if (!empty($monthlyStats)): ?>
                                    <svg viewBox="0 0 420 180" width="100%" height="210" role="img" aria-label="Evolution des projets par mois">
                                        <line x1="20" y1="150" x2="400" y2="150" stroke="var(--ds-border-strong)" stroke-width="1.5" />
                                        <line x1="20" y1="20" x2="20" y2="150" stroke="var(--ds-border-strong)" stroke-width="1.5" />
                                        <polyline fill="none" stroke-width="3.5" style="stroke:var(--ds-brand-500)" points="<?= htmlspecialchars(implode(' ', $chartPoints)) ?>"></polyline>
                                        <?php foreach ($monthlyStats as $index => $item): ?>
                                            <?php $x = $monthlyCount === 1 ? 210 : (20 + (($index / max(1, $monthlyCount - 1)) * 380)); $y = 150 - (((int) ($item->total ?? 0) / $maxMonthly) * 110); ?>
                                            <circle cx="<?= round($x, 2) ?>" cy="<?= round($y, 2) ?>" r="4.5" style="fill:var(--ds-brand-600)"></circle>
                                            <text x="<?= round($x, 2) ?>" y="170" text-anchor="middle" font-size="11" fill="var(--ds-muted)"><?= htmlspecialchars((string) ($item->month_label ?? '')) ?></text>
                                        <?php endforeach; ?>
                                    </svg>
                                <?php else: ?>
                                    <p class="mb-0 text-muted">Pas encore assez de données pour tracer l'évolution.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="st-card">
                            <h2>Top catégories</h2>
                            <?php if (!empty($categoryStats)): ?>
                                <?php foreach ($categoryStats as $item): ?>
                                    <div class="st-item">
                                        <div class="st-bar__meta"><span><?= htmlspecialchars((string) ($item->label ?? 'Sans catégorie')) ?></span><span class="muted"><?= (int) ($item->total ?? 0) ?> projets</span></div>
                                        <div class="st-track" style="margin-bottom:10px"><div class="st-fill st-fill--brand" style="width: <?= round(((int) ($item->total ?? 0) / $maxCategory) * 100, 1) ?>%"></div></div>
                                        <div class="st-badges">
                                            <span class="st-badge st-badge--ok"><i class='bx bx-check'></i> <?= (int) ($item->validated_total ?? 0) ?> validés</span>
                                            <span class="st-badge st-badge--pending"><i class='bx bx-time'></i> <?= (int) ($item->pending_total ?? 0) ?> attente</span>
                                            <span class="st-badge st-badge--no"><i class='bx bx-x'></i> <?= (int) ($item->rejected_total ?? 0) ?> rejetés</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?><p class="mb-0 text-muted">Aucune catégorie disponible.</p><?php endif; ?>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="st-card">
                            <h2>Auteurs les plus actifs</h2>
                            <?php if (!empty($topAuthors)): ?>
                                <?php foreach ($topAuthors as $item): ?>
                                    <div class="st-item">
                                        <div class="st-bar__meta"><span><?= htmlspecialchars((string) ($item->label ?? 'Auteur inconnu')) ?></span><span class="muted"><?= (int) ($item->total ?? 0) ?> projets</span></div>
                                        <div class="st-track" style="margin-bottom:8px"><div class="st-fill st-fill--ok" style="width: <?= round(((int) ($item->total ?? 0) / $maxAuthor) * 100, 1) ?>%"></div></div>
                                        <div class="st-badge st-badge--ok"><i class='bx bx-check'></i> <?= (int) ($item->validated_total ?? 0) ?> validés</div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?><p class="mb-0 text-muted">Aucun auteur actif.</p><?php endif; ?>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="st-card">
                            <h2>Détail mensuel</h2>
                            <div class="st-table-wrap">
                                <table class="st-table">
                                    <thead><tr><th>Mois</th><th>Total créés</th><th>Validés</th><th>En attente</th><th>Rejetés</th></tr></thead>
                                    <tbody>
                                        <?php if (!empty($monthlyStats)): ?>
                                            <?php foreach ($monthlyStats as $item): ?>
                                                <tr>
                                                    <td class="t-mois is-cardtitle"><?= htmlspecialchars((string) ($item->month_label ?? '')) ?></td>
                                                    <td data-label="Total créés"><strong><?= (int) ($item->total ?? 0) ?></strong></td>
                                                    <td data-label="Validés" style="color:#11703a"><?= (int) ($item->validated_total ?? 0) ?></td>
                                                    <td data-label="En attente" style="color:#8a6310"><?= (int) ($item->pending_total ?? 0) ?></td>
                                                    <td data-label="Rejetés" style="color:#a3322e"><?= (int) ($item->rejected_total ?? 0) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?><tr><td colspan="5" class="text-center text-muted" style="padding:24px">Aucune donnée mensuelle disponible.</td></tr><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>
<?php $this->view('Partials/scripts'); ?>
</body>
</html>
