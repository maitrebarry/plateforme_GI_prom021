<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Dashboard administrateur']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
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
    --glass-bg: rgba(255, 255, 255, 0.8);
    --text-main: #0f172a;
    --text-muted: #64748b;
    --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --card-hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

body {
    background-color: var(--bg-light);
    color: var(--text-main);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.dashboard-body {
    background-color: var(--bg-light);
    min-height: 100vh;
    padding-bottom: 3rem;
}

.dashboard-body__content {
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.common-card {
    border: none;
    border-radius: 16px;
    box-shadow: var(--card-shadow);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    background: #ffffff;
    overflow: hidden;
    position: relative;
}

.common-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--card-hover-shadow);
}

.common-card .card-body {
    padding: 1.75rem;
    z-index: 1;
    position: relative;
}

.common-card p {
    color: var(--text-muted);
    font-weight: 700;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 0.75rem;
}

.common-card h4 {
    color: var(--text-main);
    font-weight: 800;
    font-size: 2rem;
    margin: 0;
}

/* Stat Icon Backdrop */
.stat-icon {
    position: absolute;
    right: 1.5rem;
    bottom: 1rem;
    font-size: 3rem;
    opacity: 0.1;
    transition: all 0.3s;
}

.common-card:hover .stat-icon {
    opacity: 0.2;
    transform: scale(1.1) rotate(-10deg);
}

.stat-card-users { border-top: 5px solid var(--primary-color); }
.stat-card-projects { border-top: 5px solid var(--success-color); }
.stat-card-pending { border-top: 5px solid var(--warning-color); }
.stat-card-messages { border-top: 5px solid var(--secondary-color); }
.stat-card-categories { border-top: 5px solid var(--info-color); }
.stat-card-validated { border-top: 5px solid var(--success-color); }
.stat-card-rejected { border-top: 5px solid var(--danger-color); }

.quick-links-bar {
    background: var(--glass-bg);
    backdrop-filter: blur(8px);
    padding: 2rem;
    border-radius: 20px;
    box-shadow: var(--card-shadow);
    margin: 2rem 0;
    border: 1px solid rgba(255, 255, 255, 0.5);
}

.admin-table-card h5 {
    font-weight: 800;
    font-size: 1.25rem;
    color: var(--text-main);
    padding-bottom: 1rem;
    border-bottom: 2px solid #f1f5f9;
}

.table {
    --bs-table-hover-bg: #f8fafc;
}

.table thead th {
    background: #f8fafc !important;
    color: #1e293b; /* Darker header color for visibility */
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 0.05em;
    padding: 1.25rem 1rem;
    border: none;
    text-transform: uppercase;
}

.table tbody td {
    padding: 1.25rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    font-weight: 600; /* Bolder text for better visibility */
    color: #0f172a; /* Explicit very dark color */
    vertical-align: middle;
}

.table tbody tr:hover {
    background-color: #f8fafc;
}

.badge {
    padding: 0.6em 1em;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
}

.btn {
    padding: 0.75rem 1.5rem;
    font-weight: 700;
    border-radius: 12px;
    transition: all 0.3s;
    border: none;
}

.btn:hover {
    transform: translateY(-3px);
    filter: brightness(1.1);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; }
.btn-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
.btn-info { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; }
.btn-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
.btn-secondary { background: #64748b; color: white; }

.btn-outline-primary {
    border: 2px solid var(--primary-color);
    background: var(--primary-color);
    color: white;
}

.leaderboard-table thead th {
    background: linear-gradient(to right, #f8fafc, #ffffff) !important;
    border-bottom: 2px solid #e2e8f0;
}

.leaderboard-table tbody tr {
    transition: all 0.2s;
}

.leaderboard-table tbody tr:hover {
    background-color: #f1f5f9;
    transform: scale(1.005);
}

.metric-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-weight: 700;
    color: var(--text-main);
}

.rating-stars {
    color: #f59e0b;
    font-weight: 800;
}

.project-title-cell {
    font-weight: 800;
    color: var(--primary-color);
}
</style>
<?php
$projectPlatformStats = $projectPlatformStats ?? [];
$mostFollowedProjects = $mostFollowedProjects ?? [];
?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

            <div class="dashboard-body__content p-4">
                <div class="row gy-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card stat-card-users">
                            <div class="card-body">
                                <p class="mb-1">Utilisateurs inscrits</p>
                                <h4><?= (int) ($dashboardStats['users'] ?? 0) ?></h4>
                                <div class="stat-icon">👥</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card stat-card-projects">
                            <div class="card-body">
                                <p class="mb-1">Projets publies</p>
                                <h4><?= (int) ($dashboardStats['projects'] ?? 0) ?></h4>
                                <div class="stat-icon">📁</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card stat-card-pending">
                            <div class="card-body">
                                <p class="mb-1">Projets en attente</p>
                                <h4><?= (int) ($dashboardStats['pending'] ?? 0) ?></h4>
                                <div class="stat-icon">⏳</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card stat-card-messages">
                            <div class="card-body">
                                <p class="mb-1">Messages / Contact</p>
                                <h4><?= (int) ($dashboardStats['messages'] ?? 0) ?></h4>
                                <div class="stat-icon">✉️</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row gy-4 mt-1">
                    <div class="col-xl-4 col-md-6">
                        <div class="card common-card stat-card-categories"><div class="card-body"><p class="mb-1">Categories</p><h4><?= (int) ($dashboardStats['categories'] ?? 0) ?></h4><div class="stat-icon">🏷️</div></div></div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="card common-card stat-card-validated"><div class="card-body"><p class="mb-1">Projets valides</p><h4 class="text-success"><?= (int) ($dashboardStats['validated'] ?? 0) ?></h4><div class="stat-icon">✅</div></div></div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="card common-card stat-card-rejected"><div class="card-body"><p class="mb-1">Projets rejetes</p><h4 class="text-danger"><?= (int) ($dashboardStats['rejected'] ?? 0) ?></h4><div class="stat-icon">❌</div></div></div>
                    </div>
                </div>

                <div class="quick-links-bar d-flex flex-wrap gap-2 mt-4">
                    <a href="<?= ROOT ?>/Admins/pending_projects" class="btn btn-primary">Projets a valider</a>
                    <a href="<?= ROOT ?>/Admins/projects_management" class="btn btn-success">Gestion des projets</a>
                    <a href="<?= ROOT ?>/Admins/most_followed_projects" class="btn btn-info">Projets les plus suivis</a>
                    <a href="<?= ROOT ?>/Admins/statistics" class="btn btn-secondary">Statistiques</a>
                    <a href="<?= ROOT ?>/Admins/users_management" class="btn btn-warning">Gestion des utilisateurs</a>
                    <a href="<?= ROOT ?>/Admins/categories" class="btn btn-outline-primary">Gestion des categories</a>
                    <a href="<?= ROOT ?>/Admins/messages" class="btn btn-secondary">Messages / Contact</a>
                </div>

                <div class="row gy-4 mt-2">
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Likes projets</p><h4>❤️ <?= (int) ($projectPlatformStats['likes'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Avis projets</p><h4>💬 <?= (int) ($projectPlatformStats['reviews'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Messages projets</p><h4>📩 <?= (int) ($projectPlatformStats['messages'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Note moyenne</p><h4>⭐ <?= number_format((float) ($projectPlatformStats['average_rating'] ?? 0), 1) ?>/5</h4></div></div>
                    </div>
                </div>

                <div class="card common-card mt-4 admin-table-card">
                    <div class="card-body">
                        <h5 class="mb-4">⏳ Projets en attente de validation</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr><th>#</th><th>🚀 Titre</th><th>👤 Auteur</th><th>🏷️ Categorie</th><th>📅 Date</th><th>Statut</th></tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($pendingProjects)): ?>
                                        <?php foreach ($pendingProjects as $project): ?>
                                            <tr>
                                                <td><?= (int) ($project->id ?? 0) ?></td>
                                                <td><?= htmlspecialchars($project->title ?? 'Projet') ?></td>
                                                <td><?= htmlspecialchars($project->auteur ?? '-') ?></td>
                                                <td><?= htmlspecialchars($project->categorie ?? '-') ?></td>
                                                <td><?= htmlspecialchars(date('Y-m-d', strtotime((string)($project->created_at ?? 'now')))) ?></td>
                                                <td><span class="badge bg-warning bg-opacity-10 text-warning"><?= htmlspecialchars($project->statut ?? 'en_attente') ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Aucun projet en attente.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card common-card mt-5 admin-table-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="m-0">🏆 Projets les plus suivis (Leaderboard)</h5>
                            <a href="<?= ROOT ?>/Admins/most_followed_projects" class="btn btn-sm btn-outline-primary">Voir tout les projets</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table leaderboard-table">
                                <thead>
                                    <tr>
                                        <th>🚀 Titre</th>
                                        <th>👤 Auteur</th>
                                        <th>🏷️ Categorie</th>
                                        <th>Status</th>
                                        <th>❤️ Likes</th>
                                        <th>💬 Avis</th>
                                        <th>📩 Msg</th>
                                        <th>⭐ Note</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($mostFollowedProjects)): ?>
                                        <?php foreach ($mostFollowedProjects as $project): ?>
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
                                                <td class="project-title-cell"><?= htmlspecialchars((string) ($project->title ?? 'Projet')) ?></td>
                                                <td><span class="text-muted">par</span> <?= htmlspecialchars((string) ($project->auteur ?? '-')) ?></td>
                                                <td><span class="badge bg-secondary bg-opacity-10 text-secondary"><?= htmlspecialchars((string) ($project->categorie ?? '-')) ?></span></td>
                                                <td><span class="badge <?= $badgeClass ?> bg-opacity-10 text-<?= str_replace('bg-', '', explode(' ', $badgeClass)[0]) ?>"><?= htmlspecialchars($status) ?></span></td>
                                                <td><span class="metric-badge">❤️ <?= (int) ($project->likes_count ?? 0) ?></span></td>
                                                <td><span class="metric-badge">💬 <?= (int) ($project->reviews_count ?? 0) ?></span></td>
                                                <td><span class="metric-badge">📩 <?= (int) ($project->messages_count ?? 0) ?></span></td>
                                                <td><span class="rating-stars">⭐ <?= number_format((float) ($project->average_rating ?? 0), 1) ?></span></td>
                                                <td><a href="<?= ROOT ?>/Admins/project_detail/<?= (int) ($project->id ?? 0) ?>" class="btn btn-primary btn-sm rounded-pill">Details</a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-5">Aucune statistique projet disponible.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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
