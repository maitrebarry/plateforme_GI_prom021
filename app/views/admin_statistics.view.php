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
$maxCategory = 1;
foreach ($categoryStats as $item) {
    $maxCategory = max($maxCategory, (int) ($item->total ?? 0));
}

$maxAuthor = 1;
foreach ($topAuthors as $item) {
    $maxAuthor = max($maxAuthor, (int) ($item->total ?? 0));
}

$maxMonthly = 1;
foreach ($monthlyStats as $item) {
    $maxMonthly = max($maxMonthly, (int) ($item->total ?? 0));
}

$chartPoints = [];
$monthlyCount = count($monthlyStats);
if ($monthlyCount > 0) {
    foreach ($monthlyStats as $index => $item) {
        $x = $monthlyCount === 1 ? 210 : (20 + (($index / max(1, $monthlyCount - 1)) * 380));
        $y = 150 - (((int) ($item->total ?? 0) / $maxMonthly) * 110);
        $chartPoints[] = round($x, 2) . ',' . round($y, 2);
    }
}
?>
<style>
.stats-page,
.stats-page h2,
.stats-page h3,
.stats-page h4,
.stats-page p,
.stats-page span,
.stats-page label {
    color: #0f172a;
}

.stats-hero {
    padding: 24px 28px;
    border-radius: 28px;
    background:
        radial-gradient(circle at top left, rgba(14, 165, 233, 0.22), transparent 30%),
        radial-gradient(circle at bottom right, rgba(34, 197, 94, 0.18), transparent 28%),
        linear-gradient(135deg, #ffffff, #f8fafc);
    border: 1px solid #dbe4ee;
}

.stats-card {
    height: 100%;
    border: 1px solid #dbe4ee;
    border-radius: 24px;
    background: #fff;
    box-shadow: 0 14px 32px rgba(15, 23, 42, 0.06);
}

.stats-card__body {
    padding: 22px;
}

.stats-pill-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
}

.stats-pill {
    padding: 18px 20px;
    border-radius: 20px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

.stats-pill strong {
    display: block;
    font-size: 1.8rem;
    line-height: 1.1;
    margin-top: 8px;
}

.status-stack {
    display: grid;
    gap: 14px;
}

.status-bar__meta,
.mini-bar__meta {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    font-weight: 700;
    margin-bottom: 8px;
}

.status-bar__track,
.mini-bar__track {
    width: 100%;
    height: 14px;
    border-radius: 999px;
    background: #e2e8f0;
    overflow: hidden;
}

.status-bar__fill,
.mini-bar__fill {
    height: 100%;
    border-radius: 999px;
}

.status-bar__fill--validated { background: linear-gradient(90deg, #16a34a, #22c55e); }
.status-bar__fill--pending { background: linear-gradient(90deg, #d97706, #f59e0b); }
.status-bar__fill--rejected { background: linear-gradient(90deg, #dc2626, #f87171); }
.mini-bar__fill--primary { background: linear-gradient(90deg, #2563eb, #38bdf8); }
.mini-bar__fill--success { background: linear-gradient(90deg, #16a34a, #4ade80); }

.chart-shell {
    padding: 18px;
    border-radius: 20px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

.chart-legend {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 10px;
}

.chart-legend span {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #475569;
}

.legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 999px;
}

.stats-list {
    display: grid;
    gap: 16px;
}

.stats-list__item {
    padding: 16px 18px;
    border-radius: 18px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

.stats-table {
    width: 100%;
    border-collapse: collapse;
}

.stats-table th,
.stats-table td {
    padding: 12px 10px;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: middle;
}

.stats-table th {
    font-size: .9rem;
    color: #334155;
}
</style>

<section class="dashboard stats-page">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-4">
                <?php $this->view('set_flash'); ?>

                <div class="stats-hero mb-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h2 class="mb-1">Diagrammes des statistiques</h2>
                            <p class="mb-0 text-muted">Vue synthese sur les validations, l activite des projets et les tendances de la plateforme.</p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="<?= ROOT ?>/Admins/dashboard" class="btn btn-outline-secondary">Retour dashboard</a>
                            <a href="<?= ROOT ?>/Admins/projects_management" class="btn btn-primary">Gerer les projets</a>
                        </div>
                    </div>
                </div>

                <div class="stats-pill-grid mb-4">
                    <div class="stats-pill"><span>Utilisateurs</span><strong><?= (int) ($dashboardStats['users'] ?? 0) ?></strong></div>
                    <div class="stats-pill"><span>Projets</span><strong><?= (int) ($dashboardStats['projects'] ?? 0) ?></strong></div>
                    <div class="stats-pill"><span>Likes</span><strong><?= (int) ($projectPlatformStats['likes'] ?? 0) ?></strong></div>
                    <div class="stats-pill"><span>Avis</span><strong><?= (int) ($projectPlatformStats['reviews'] ?? 0) ?></strong></div>
                    <div class="stats-pill"><span>Messages projets</span><strong><?= (int) ($projectPlatformStats['messages'] ?? 0) ?></strong></div>
                    <div class="stats-pill"><span>Note moyenne</span><strong><?= number_format((float) ($projectPlatformStats['average_rating'] ?? 0), 1) ?>/5</strong></div>
                </div>

                <div class="row gy-4">
                    <div class="col-xl-5">
                        <div class="stats-card">
                            <div class="stats-card__body">
                                <h4 class="mb-3">Repartition des validations</h4>
                                <div class="status-stack">
                                    <div class="status-bar">
                                        <div class="status-bar__meta"><span>Valides</span><span><?= (int) ($dashboardStats['validated'] ?? 0) ?></span></div>
                                        <div class="status-bar__track"><div class="status-bar__fill status-bar__fill--validated" style="width: <?= round(((int) ($dashboardStats['validated'] ?? 0) / $statusTotal) * 100, 1) ?>%"></div></div>
                                    </div>
                                    <div class="status-bar">
                                        <div class="status-bar__meta"><span>En attente</span><span><?= (int) ($dashboardStats['pending'] ?? 0) ?></span></div>
                                        <div class="status-bar__track"><div class="status-bar__fill status-bar__fill--pending" style="width: <?= round(((int) ($dashboardStats['pending'] ?? 0) / $statusTotal) * 100, 1) ?>%"></div></div>
                                    </div>
                                    <div class="status-bar">
                                        <div class="status-bar__meta"><span>Rejetes</span><span><?= (int) ($dashboardStats['rejected'] ?? 0) ?></span></div>
                                        <div class="status-bar__track"><div class="status-bar__fill status-bar__fill--rejected" style="width: <?= round(((int) ($dashboardStats['rejected'] ?? 0) / $statusTotal) * 100, 1) ?>%"></div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7">
                        <div class="stats-card">
                            <div class="stats-card__body">
                                <h4 class="mb-3">Evolution mensuelle des projets</h4>
                                <div class="chart-shell">
                                    <?php if (!empty($monthlyStats)): ?>
                                        <svg viewBox="0 0 420 180" width="100%" height="220" role="img" aria-label="Evolution des projets par mois">
                                            <line x1="20" y1="150" x2="400" y2="150" stroke="#cbd5e1" stroke-width="2" />
                                            <line x1="20" y1="20" x2="20" y2="150" stroke="#cbd5e1" stroke-width="2" />
                                            <?php foreach ($monthlyStats as $index => $item): ?>
                                                <?php
                                                $x = $monthlyCount === 1 ? 210 : (20 + (($index / max(1, $monthlyCount - 1)) * 380));
                                                $y = 150 - (((int) ($item->total ?? 0) / $maxMonthly) * 110);
                                                ?>
                                                <circle cx="<?= round($x, 2) ?>" cy="<?= round($y, 2) ?>" r="5" fill="#2563eb"></circle>
                                                <text x="<?= round($x, 2) ?>" y="170" text-anchor="middle" font-size="11" fill="#475569"><?= htmlspecialchars((string) ($item->month_label ?? '')) ?></text>
                                            <?php endforeach; ?>
                                            <polyline fill="none" stroke="#2563eb" stroke-width="4" points="<?= htmlspecialchars(implode(' ', $chartPoints)) ?>"></polyline>
                                        </svg>
                                        <div class="chart-legend">
                                            <span><i class="legend-dot" style="background:#2563eb"></i>Total projets crees</span>
                                        </div>
                                    <?php else: ?>
                                        <p class="mb-0 text-muted">Pas encore assez de donnees pour tracer l evolution mensuelle.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="stats-card">
                            <div class="stats-card__body">
                                <h4 class="mb-3">Top categories</h4>
                                <div class="stats-list">
                                    <?php if (!empty($categoryStats)): ?>
                                        <?php foreach ($categoryStats as $item): ?>
                                            <div class="stats-list__item">
                                                <div class="mini-bar__meta">
                                                    <span><?= htmlspecialchars((string) ($item->label ?? 'Sans categorie')) ?></span>
                                                    <span><?= (int) ($item->total ?? 0) ?> projet(s)</span>
                                                </div>
                                                <div class="mini-bar__track mb-2">
                                                    <div class="mini-bar__fill mini-bar__fill--primary" style="width: <?= round(((int) ($item->total ?? 0) / $maxCategory) * 100, 1) ?>%"></div>
                                                </div>
                                                <div class="d-flex flex-wrap gap-3 text-muted small">
                                                    <span>Valides: <?= (int) ($item->validated_total ?? 0) ?></span>
                                                    <span>En attente: <?= (int) ($item->pending_total ?? 0) ?></span>
                                                    <span>Rejetes: <?= (int) ($item->rejected_total ?? 0) ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="mb-0 text-muted">Aucune categorie avec projets pour le moment.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="stats-card">
                            <div class="stats-card__body">
                                <h4 class="mb-3">Auteurs les plus actifs</h4>
                                <div class="stats-list">
                                    <?php if (!empty($topAuthors)): ?>
                                        <?php foreach ($topAuthors as $item): ?>
                                            <div class="stats-list__item">
                                                <div class="mini-bar__meta">
                                                    <span><?= htmlspecialchars((string) ($item->label ?? 'Auteur inconnu')) ?></span>
                                                    <span><?= (int) ($item->total ?? 0) ?> projet(s)</span>
                                                </div>
                                                <div class="mini-bar__track mb-2">
                                                    <div class="mini-bar__fill mini-bar__fill--success" style="width: <?= round(((int) ($item->total ?? 0) / $maxAuthor) * 100, 1) ?>%"></div>
                                                </div>
                                                <div class="text-muted small">Projets valides: <?= (int) ($item->validated_total ?? 0) ?></div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="mb-0 text-muted">Les auteurs actifs apparaitront ici des qu il y aura plus de publications.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="stats-card">
                            <div class="stats-card__body">
                                <h4 class="mb-3">Detail mensuel</h4>
                                <div class="table-responsive">
                                    <table class="stats-table">
                                        <thead>
                                            <tr>
                                                <th>Mois</th>
                                                <th>Total</th>
                                                <th>Valides</th>
                                                <th>En attente</th>
                                                <th>Rejetes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($monthlyStats)): ?>
                                                <?php foreach ($monthlyStats as $item): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars((string) ($item->month_label ?? '')) ?></td>
                                                        <td><?= (int) ($item->total ?? 0) ?></td>
                                                        <td><?= (int) ($item->validated_total ?? 0) ?></td>
                                                        <td><?= (int) ($item->pending_total ?? 0) ?></td>
                                                        <td><?= (int) ($item->rejected_total ?? 0) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="5" class="text-center text-muted">Aucune donnee mensuelle disponible.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
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
