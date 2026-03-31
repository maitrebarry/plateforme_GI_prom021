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
:root {
    --primary-color: #6366f1;
    --primary-hover: #4f46e5;
    --secondary-color: #94a3b8;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --bg-light: #f1f5f9;
    --text-main: #0f172a;
    --text-muted: #64748b;
    --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

body {
    background-color: var(--bg-light);
    color: var(--text-main);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.common-card {
    border: none;
    border-radius: 20px;
    box-shadow: var(--card-shadow);
    background: #ffffff;
    margin-bottom: 24px;
    overflow: hidden;
}

.common-card h4 {
    font-weight: 800;
    color: var(--text-main);
    border-bottom: 2px solid #f1f5f9;
    padding-bottom: 1rem;
    margin-bottom: 1.5rem;
}

.header-back-btn {
    width: 45px;
    height: 45px;
    background: white;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s;
}

.header-back-btn:hover {
    transform: translateX(-5px);
    background: #f8fafc;
}

.stat-pill {
    padding: 1.25rem;
    border-radius: 16px;
    background: white;
    box-shadow: var(--card-shadow);
    display: flex;
    flex-direction: column;
    border-top: 4px solid var(--primary-color);
    height: 100%;
}

.status-bar {
    margin-bottom: 1.25rem;
}

.status-bar__meta {
    display: flex;
    justify-content: space-between;
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    color: var(--text-muted);
}

.status-bar__track {
    height: 10px;
    background: #f1f5f9;
    border-radius: 999px;
    overflow: hidden;
}

.status-bar__fill {
    height: 100%;
    border-radius: 999px;
}

.status-bar__fill--validated { background: var(--success-color); }
.status-bar__fill--pending { background: var(--warning-color); }
.status-bar__fill--rejected { background: var(--danger-color); }

.chart-shell {
    padding: 1.5rem;
    border-radius: 16px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

.stats-list__item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.stats-table thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 800;
    text-transform: uppercase;
    font-size: 0.7rem;
    letter-spacing: 0.05em;
    padding: 1rem;
    border-bottom: 2px solid #e2e8f0;
}

.stats-table tbody td {
    padding: 1rem;
    font-weight: 600;
    color: #1e293b;
}

.btn {
    font-weight: 700;
    border-radius: 12px;
    padding: 0.6rem 1.2rem;
}

.btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; }

.stat-icon {
    position: absolute;
    right: 1rem;
    bottom: 0.5rem;
    font-size: 3.5rem;
    opacity: 0.08;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    pointer-events: none;
}

.stat-pill:hover .stat-icon {
    opacity: 0.15;
    transform: scale(1.1) rotate(-10deg);
}

.stat-pill {
    transition: all 0.3s;
}

.stat-pill:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px -5px rgba(0,0,0,0.1);
}
</style>

<section class="dashboard stats-page">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-4">
                <?php $this->view('set_flash'); ?>

                <div class="card common-card mb-4 border-0 bg-transparent shadow-none">
                    <div class="card-body p-0 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <a href="<?= ROOT ?>/Admins/dashboard" class="header-back-btn">
                                ⬅️
                            </a>
                            <div>
                                <h3 class="mb-0 fw-800 text-primary">Analyses & Statistiques</h3>
                                <p class="text-muted small mb-0">Indicateurs de performance et tendances de la plateforme</p>
                            </div>
                        </div>
                        <a href="<?= ROOT ?>/Admins/projects_management" class="btn btn-primary px-4 shadow-sm">
                            <i class="bx bx-list-ul"></i> Gérer les projets
                        </a>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-2">
                        <div class="stat-pill border-primary position-relative overflow-hidden">
                            <span class="small text-muted fw-bold">UTILISATEURS</span>
                            <strong class="h3 mb-0"><?= (int) ($dashboardStats['users'] ?? 0) ?></strong>
                            <i class="bx bx-group stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-pill border-indigo position-relative overflow-hidden" style="border-top-color: #818cf8;">
                            <span class="small text-muted fw-bold">PROJETS</span>
                            <strong class="h3 mb-0"><?= (int) ($dashboardStats['projects'] ?? 0) ?></strong>
                            <i class="bx bx-folder stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-pill border-success position-relative overflow-hidden" style="border-top-color: #10b981;">
                            <span class="small text-muted fw-bold">LIKES</span>
                            <strong class="h3 mb-0"><?= (int) ($projectPlatformStats['likes'] ?? 0) ?></strong>
                            <i class="bx bx-heart stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-pill border-warning position-relative overflow-hidden" style="border-top-color: #f59e0b;">
                            <span class="small text-muted fw-bold">AVIS</span>
                            <strong class="h3 mb-0"><?= (int) ($projectPlatformStats['reviews'] ?? 0) ?></strong>
                            <i class="bx bx-star stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-pill border-info position-relative overflow-hidden" style="border-top-color: #06b6d4;">
                            <span class="small text-muted fw-bold">MESSAGES</span>
                            <strong class="h3 mb-0"><?= (int) ($projectPlatformStats['messages'] ?? 0) ?></strong>
                            <i class="bx bx-message-detail stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-pill border-danger position-relative overflow-hidden" style="border-top-color: #ef4444;">
                            <span class="small text-muted fw-bold">NOTE MOY.</span>
                            <strong class="h3 mb-0"><?= number_format((float) ($projectPlatformStats['average_rating'] ?? 0), 1) ?>/5</strong>
                            <i class="bx bx-bar-chart-alt-2 stat-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="row gy-4">
                    <div class="col-xl-5">
                        <div class="card common-card">
                            <div class="card-body p-4">
                                <h4>Répartition des validations</h4>
                                <div class="status-stack">
                                    <div class="status-bar">
                                        <div class="status-bar__meta"><span>Validés</span><span><?= (int) ($dashboardStats['validated'] ?? 0) ?></span></div>
                                        <div class="status-bar__track"><div class="status-bar__fill status-bar__fill--validated" style="width: <?= round(((int) ($dashboardStats['validated'] ?? 0) / $statusTotal) * 100, 1) ?>%"></div></div>
                                    </div>
                                    <div class="status-bar">
                                        <div class="status-bar__meta"><span>En attente</span><span><?= (int) ($dashboardStats['pending'] ?? 0) ?></span></div>
                                        <div class="status-bar__track"><div class="status-bar__fill status-bar__fill--pending" style="width: <?= round(((int) ($dashboardStats['pending'] ?? 0) / $statusTotal) * 100, 1) ?>%"></div></div>
                                    </div>
                                    <div class="status-bar">
                                        <div class="status-bar__meta"><span>Rejetés</span><span><?= (int) ($dashboardStats['rejected'] ?? 0) ?></span></div>
                                        <div class="status-bar__track"><div class="status-bar__fill status-bar__fill--rejected" style="width: <?= round(((int) ($dashboardStats['rejected'] ?? 0) / $statusTotal) * 100, 1) ?>%"></div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7">
                        <div class="card common-card">
                            <div class="card-body p-4">
                                <h4>Évolution mensuelle</h4>
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
                                                <circle cx="<?= round($x, 2) ?>" cy="<?= round($y, 2) ?>" r="5" fill="#6366f1"></circle>
                                                <text x="<?= round($x, 2) ?>" y="170" text-anchor="middle" font-size="11" fill="#64748b"><?= htmlspecialchars((string) ($item->month_label ?? '')) ?></text>
                                            <?php endforeach; ?>
                                            <polyline fill="none" stroke="#6366f1" stroke-width="4" points="<?= htmlspecialchars(implode(' ', $chartPoints)) ?>"></polyline>
                                        </svg>
                                    <?php else: ?>
                                        <p class="mb-0 text-muted">Pas encore assez de données pour tracer l'évolution.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="card common-card">
                            <div class="card-body p-4">
                                <h4>Top Catégories</h4>
                                <div class="stats-list">
                                    <?php if (!empty($categoryStats)): ?>
                                        <?php foreach ($categoryStats as $item): ?>
                                            <div class="stats-list__item">
                                                <div class="status-bar__meta">
                                                    <span><?= htmlspecialchars((string) ($item->label ?? 'Sans catégorie')) ?></span>
                                                    <span><?= (int) ($item->total ?? 0) ?> projets</span>
                                                </div>
                                                <div class="status-bar__track mb-3">
                                                    <div class="status-bar__fill" style="background: var(--primary-color); width: <?= round(((int) ($item->total ?? 0) / $maxCategory) * 100, 1) ?>%"></div>
                                                </div>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <span class="badge bg-success bg-opacity-10 text-success border-0 px-2 small">✓ <?= (int) ($item->validated_total ?? 0) ?> validés</span>
                                                    <span class="badge bg-warning bg-opacity-10 text-warning border-0 px-2 small">⌛ <?= (int) ($item->pending_total ?? 0) ?> attente</span>
                                                    <span class="badge bg-danger bg-opacity-10 text-danger border-0 px-2 small">✗ <?= (int) ($item->rejected_total ?? 0) ?> rejetés</span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="mb-0 text-muted">Aucune catégorie disponible.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="card common-card">
                            <div class="card-body p-4">
                                <h4>Auteurs les plus actifs</h4>
                                <div class="stats-list">
                                    <?php if (!empty($topAuthors)): ?>
                                        <?php foreach ($topAuthors as $item): ?>
                                            <div class="stats-list__item">
                                                <div class="status-bar__meta">
                                                    <span><?= htmlspecialchars((string) ($item->label ?? 'Auteur inconnu')) ?></span>
                                                    <span><?= (int) ($item->total ?? 0) ?> projets</span>
                                                </div>
                                                <div class="status-bar__track mb-2">
                                                    <div class="status-bar__fill" style="background: var(--success-color); width: <?= round(((int) ($item->total ?? 0) / $maxAuthor) * 100, 1) ?>%"></div>
                                                </div>
                                                <div class="text-muted small fw-bold text-success">✓ <?= (int) ($item->validated_total ?? 0) ?> validés</div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="mb-0 text-muted">Aucun auteur actif.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card common-card">
                            <div class="card-body p-4">
                                <h4>Détail mensuel performance</h4>
                                <div class="table-responsive">
                                    <table class="table stats-table">
                                        <thead>
                                            <tr>
                                                <th>Mois</th>
                                                <th>Total créés</th>
                                                <th>Validés</th>
                                                <th>En attente</th>
                                                <th>Rejetés</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($monthlyStats)): ?>
                                                <?php foreach ($monthlyStats as $item): ?>
                                                    <tr>
                                                        <td class="fw-800 text-primary"><?= htmlspecialchars((string) ($item->month_label ?? '')) ?></td>
                                                        <td><span class="badge bg-light text-dark px-3 py-2"><?= (int) ($item->total ?? 0) ?></span></td>
                                                        <td class="text-success"><?= (int) ($item->validated_total ?? 0) ?></td>
                                                        <td class="text-warning"><?= (int) ($item->pending_total ?? 0) ?></td>
                                                        <td class="text-danger"><?= (int) ($item->rejected_total ?? 0) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="5" class="text-center text-muted">Aucune donnée mensuelle disponible.</td></tr>
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
