<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Dashboard administrateur']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<style>
.admin-table-card,
.admin-table-card .card-body,
.admin-table-card h5,
.admin-table-card p,
.admin-table-card h4 {
    color: #0f172a;
}

.admin-table-card .table {
    color: #0f172a;
    --bs-table-color: #0f172a;
    --bs-table-bg: #ffffff;
    --bs-table-striped-color: #0f172a;
    --bs-table-striped-bg: #f8fafc;
    --bs-table-hover-color: #0f172a;
    --bs-table-hover-bg: #eef2ff;
    vertical-align: middle;
}

.admin-table-card .table thead th {
    color: #0f172a;
    background: #e2e8f0;
    border-bottom: 1px solid #cbd5e1;
    font-weight: 800;
}

.admin-table-card .table tbody td {
    color: #1e293b;
    background: #ffffff;
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
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Utilisateurs inscrits</p><h4><?= (int) ($dashboardStats['users'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Projets publies</p><h4><?= (int) ($dashboardStats['projects'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Projets en attente</p><h4><?= (int) ($dashboardStats['pending'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Messages / Contact</p><h4><?= (int) ($dashboardStats['messages'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Categories</p><h4><?= (int) ($dashboardStats['categories'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Projets valides</p><h4><?= (int) ($dashboardStats['validated'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Projets rejetes</p><h4><?= (int) ($dashboardStats['rejected'] ?? 0) ?></h4></div></div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <a href="<?= ROOT ?>/Admins/pending_projects" class="btn btn-primary">Projets a valider</a>
                    <a href="<?= ROOT ?>/Admins/projects_management" class="btn btn-outline-primary">Gestion des projets</a>
                    <a href="<?= ROOT ?>/Admins/most_followed_projects" class="btn btn-outline-primary">Projets les plus suivis</a>
                    <a href="<?= ROOT ?>/Admins/statistics" class="btn btn-outline-primary">Statistiques</a>
                    <a href="<?= ROOT ?>/Admins/users_management" class="btn btn-outline-primary">Gestion des utilisateurs</a>
                    <a href="<?= ROOT ?>/Admins/categories" class="btn btn-outline-primary">Gestion des categories</a>
                    <a href="<?= ROOT ?>/Admins/messages" class="btn btn-outline-primary">Messages / Contact</a>
                </div>

                <div class="row gy-4 mt-1">
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Likes projets</p><h4><?= (int) ($projectPlatformStats['likes'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Avis projets</p><h4><?= (int) ($projectPlatformStats['reviews'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Messages projets</p><h4><?= (int) ($projectPlatformStats['messages'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Note moyenne</p><h4><?= number_format((float) ($projectPlatformStats['average_rating'] ?? 0), 1) ?>/5</h4></div></div>
                    </div>
                </div>

                <div class="card common-card mt-4 admin-table-card">
                    <div class="card-body">
                        <h5 class="mb-3">Projets en attente de validation</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr><th>#</th><th>Titre</th><th>Auteur</th><th>Categorie</th><th>Date</th><th>Statut</th></tr>
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
                                                <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($project->statut ?? 'en_attente') ?></span></td>
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

                <div class="card common-card mt-4 admin-table-card">
                    <div class="card-body">
                        <h5 class="mb-3">Projets les plus suivis</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Auteur</th>
                                        <th>Categorie</th>
                                        <th>Statut</th>
                                        <th>Likes</th>
                                        <th>Avis</th>
                                        <th>Messages</th>
                                        <th>Note</th>
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
                                                <td><?= htmlspecialchars((string) ($project->title ?? 'Projet')) ?></td>
                                                <td><?= htmlspecialchars((string) ($project->auteur ?? '-')) ?></td>
                                                <td><?= htmlspecialchars((string) ($project->categorie ?? '-')) ?></td>
                                                <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span></td>
                                                <td><?= (int) ($project->likes_count ?? 0) ?></td>
                                                <td><?= (int) ($project->reviews_count ?? 0) ?></td>
                                                <td><?= (int) ($project->messages_count ?? 0) ?></td>
                                                <td><?= number_format((float) ($project->average_rating ?? 0), 1) ?>/5</td>
                                                <td><a href="<?= ROOT ?>/Admins/project_detail/<?= (int) ($project->id ?? 0) ?>" class="btn btn-outline-secondary btn-sm">Voir detail</a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">Aucune statistique projet disponible.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <a href="<?= ROOT ?>/Admins/most_followed_projects" class="btn btn-outline-primary">Voir tous les projets suivis</a>
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
