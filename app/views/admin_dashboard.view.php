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
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Utilisateurs</p><h4><?= (int) ($dashboardStats['users'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Projets</p><h4><?= (int) ($dashboardStats['projects'] ?? count($projects ?? [])) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Inscriptions</p><h4><?= (int) ($dashboardStats['registrations'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Alertes</p><h4><?= (int) ($dashboardStats['alerts'] ?? 0) ?></h4></div></div>
                    </div>
                </div>

                <div class="card common-card mt-4">
                    <div class="card-body">
                        <h5 class="mb-3">Projets récents</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr><th>#</th><th>Titre</th><th>Catégorie</th><th>Auteur</th></tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($projects)): ?>
                                        <?php foreach ($projects as $project): ?>
                                            <tr>
                                                <td><?= (int) ($project['id'] ?? 0) ?></td>
                                                <td><a href="<?= ROOT ?>/Homes/project/<?= (int) ($project['id'] ?? 0) ?>"><?= htmlspecialchars($project['title'] ?? 'Projet') ?></a></td>
                                                <td><?= htmlspecialchars($project['category'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($project['author'] ?? '-') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Aucun projet à afficher (sera alimenté par le backend).</td>
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
