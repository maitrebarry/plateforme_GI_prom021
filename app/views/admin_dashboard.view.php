<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Dashboard administrateur']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>

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
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Projets publiés</p><h4><?= (int) ($dashboardStats['projects'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Projets à valider</p><h4><?= (int) ($dashboardStats['pending'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Messages / Contact</p><h4><?= (int) ($dashboardStats['messages'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Catégories</p><h4><?= (int) ($dashboardStats['categories'] ?? 0) ?></h4></div></div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <a href="<?= ROOT ?>/Admins/pending_projects" class="btn btn-primary">Projets à valider</a>
                    <a href="<?= ROOT ?>/Admins/projects_management" class="btn btn-outline-primary">Gestion des projets</a>
                    <a href="<?= ROOT ?>/Admins/users_management" class="btn btn-outline-primary">Gestion des utilisateurs</a>
                    <a href="<?= ROOT ?>/Admins/categories" class="btn btn-outline-primary">Gestion des catégories</a>
                    <a href="<?= ROOT ?>/Admins/messages" class="btn btn-outline-primary">Messages / Contact</a>
                </div>

                <div class="card common-card mt-4">
                    <div class="card-body">
                        <h5 class="mb-3">Projets en attente de validation</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr><th>#</th><th>Titre</th><th>Auteur</th><th>Catégorie</th><th>Date</th><th>Statut</th></tr>
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
            </div>

            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>

<?php $this->view('Partials/scripts'); ?>
</body>
</html>
