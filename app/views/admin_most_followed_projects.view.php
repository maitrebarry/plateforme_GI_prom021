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
?>
<style>
:root {
    --primary-color: #6366f1;
    --primary-hover: #4f46e5;
    --secondary-color: #94a3b8;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #0ea5e9;
    --bg-light: #f1f5f9;
    --text-main: #0f172a;
    --text-muted: #64748b;
    --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

body {
    background-color: var(--bg-light);
    color: var(--text-main);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.dashboard-body {
    background-color: var(--bg-light);
    min-height: 100vh;
}

.common-card {
    border: none;
    border-radius: 16px;
    box-shadow: var(--card-shadow);
    background: #ffffff;
    transition: all 0.3s;
}

.common-card:hover {
    transform: translateY(-5px);
}

.common-card p {
    color: var(--text-muted);
    font-weight: 700;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 0.5rem;
}

.common-card h4 {
    font-weight: 800;
    font-size: 1.75rem;
    margin: 0;
}

.stat-icon {
    position: absolute;
    right: 1rem;
    bottom: 1rem;
    font-size: 2.5rem;
    opacity: 0.1;
}

.admin-table-card .table thead th {
    background: #f8fafc !important;
    color: #1e293b;
    font-size: 0.75rem;
    font-weight: 800;
    padding: 1.25rem 1rem;
    border: none;
    text-transform: uppercase;
}

.admin-table-card .table tbody td {
    padding: 1.25rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    font-weight: 600;
    color: #0f172a;
    vertical-align: middle;
}

.badge {
    padding: 0.6em 1em;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 700;
}

.btn {
    font-weight: 700;
    border-radius: 10px;
    transition: all 0.2s;
}

.btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; }
.btn-outline-secondary { border: 2px solid #e2e8f0; color: #64748b; }

.metric-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-weight: 700;
}

.rating-stars {
    color: #f59e0b;
    font-weight: 800;
}
</style>
<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-4">
                <?php $this->view('set_flash'); ?>

                <div class="card common-card mb-4 border-0" style="background: transparent; box-shadow: none;">
                    <div class="card-body p-0 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <a href="<?= ROOT ?>/Admins/dashboard" class="btn btn-light rounded-circle p-2 shadow-sm d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;" title="Retour au Dashboard">
                                ⬅️
                            </a>
                            <div>
                                <h3 class="mb-0 fw-800">🏆 Projets suivis</h3>
                                <p class="text-muted small mb-0">Analyse détaillée de l'activité</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                             <form method="GET" action="<?= ROOT ?>/Admins/most_followed_projects" class="d-flex gap-2">
                                <select name="per_page" class="form-select form-select-sm border-0 shadow-sm rounded-pill px-3" style="width: auto;">
                                    <option value="10" <?= $perPage === 10 ? 'selected' : '' ?>>10 / page</option>
                                    <option value="20" <?= $perPage === 20 ? 'selected' : '' ?>>20 / page</option>
                                    <option value="50" <?= $perPage === 50 ? 'selected' : '' ?>>50 / page</option>
                                </select>
                                <button class="btn btn-primary btn-sm rounded-pill px-3" type="submit">Actualiser</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row gy-4 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card border-top border-5 border-primary"><div class="card-body"><p class="mb-1">Total Likes</p><h4>❤️ <?= (int) ($projectPlatformStats['likes'] ?? 0) ?></h4><div class="stat-icon">❤️</div></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card border-top border-5 border-success"><div class="card-body"><p class="mb-1">Total Avis</p><h4>💬 <?= (int) ($projectPlatformStats['reviews'] ?? 0) ?></h4><div class="stat-icon">💬</div></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card border-top border-5 border-secondary"><div class="card-body"><p class="mb-1">Total Messages</p><h4>📩 <?= (int) ($projectPlatformStats['messages'] ?? 0) ?></h4><div class="stat-icon">📩</div></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card border-top border-5 border-warning"><div class="card-body"><p class="mb-1">Note platforme</p><h4>⭐ <?= number_format((float) ($projectPlatformStats['average_rating'] ?? 0), 1) ?>/5</h4><div class="stat-icon">⭐</div></div></div>
                    </div>
                </div>

                <div class="card common-card admin-table-card">
                    <div class="card-body">
                        <h5 class="mb-3">Classement des projets suivis</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Titre</th>
                                        <th>Auteur</th>
                                        <th>Categorie</th>
                                        <th>Statut</th>
                                        <th>Likes</th>
                                        <th>Avis</th>
                                        <th>Messages</th>
                                        <th>Note</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($projects)): ?>
                                        <?php foreach ($projects as $index => $project): ?>
                                            <?php
                                            $status = (string) ($project->statut ?? 'en_attente');
                                            $badgeClass = 'bg-warning text-dark';
                                            if ($status === 'valide') {
                                                $badgeClass = 'bg-success';
                                            } elseif ($status === 'rejete') {
                                                $badgeClass = 'bg-danger';
                                            }
                                            ?>
                                            <tr>
                                                <td class="fw-bold text-muted">#<?= (($currentPage - 1) * $perPage) + $index + 1 ?></td>
                                                <td class="fw-bold text-primary"><?= htmlspecialchars((string) ($project->title ?? 'Projet')) ?></td>
                                                <td><span class="text-muted">par</span> <?= htmlspecialchars((string) ($project->auteur ?? '-')) ?></td>
                                                <td><span class="badge bg-secondary bg-opacity-10 text-secondary"><?= htmlspecialchars((string) ($project->categorie ?? '-')) ?></span></td>
                                                <td><span class="badge <?= $badgeClass ?> bg-opacity-10 text-<?= str_replace('bg-', '', explode(' ', $badgeClass)[0]) ?>"><?= htmlspecialchars($status) ?></span></td>
                                                <td><span class="metric-badge">❤️ <?= (int) ($project->likes_count ?? 0) ?></span></td>
                                                <td><span class="metric-badge">💬 <?= (int) ($project->reviews_count ?? 0) ?></span></td>
                                                <td><span class="metric-badge">📩 <?= (int) ($project->messages_count ?? 0) ?></span></td>
                                                <td><span class="rating-stars">⭐ <?= number_format((float) ($project->average_rating ?? 0), 1) ?></span></td>
                                                <td>
                                                    <a href="<?= ROOT ?>/Admins/project_detail/<?= (int) ($project->id ?? 0) ?>" class="btn btn-primary btn-sm rounded-pill">Details</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="10" class="text-center text-muted">Aucun projet suivi disponible.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php $this->view('Partials/admin-pagination', [
                            'currentPage' => $currentPage,
                            'perPage' => $perPage,
                            'totalPages' => $totalPages,
                            'totalItems' => $totalItems,
                            'basePath' => 'Admins/most_followed_projects',
                            'queryString' => $paginationQuery,
                            'itemLabel' => 'projet(s)',
                        ]); ?>
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
