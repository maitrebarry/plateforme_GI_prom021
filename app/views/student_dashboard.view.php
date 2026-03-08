<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Dashboard étudiant']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>

            <div class="dashboard-body__content p-4">
                <?php $this->view('set_flash'); ?>

                <div class="row gy-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Mes projets</p><h4><?= (int)($studentStats['mesProjets'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">En attente</p><h4><?= (int)($studentStats['enAttente'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Validés</p><h4><?= (int)($studentStats['valides'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Messages reçus</p><h4><?= (int)($studentStats['messages'] ?? 0) ?></h4></div></div>
                    </div>
                </div>

                <div class="card common-card mt-4">
                    <div class="card-body">
                        <h5 class="mb-3">Mes projets récents (maquette)</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead><tr><th>#</th><th>Titre</th><th>Catégorie</th><th>Statut</th></tr></thead>
                                <tbody>
                                    <?php foreach (($projects ?? []) as $project): ?>
                                        <tr>
                                            <td><?= (int)($project['id'] ?? 0) ?></td>
                                            <td><?= htmlspecialchars($project['title'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($project['category'] ?? '') ?></td>
                                            <td><span class="badge bg-warning text-dark">en_attente</span></td>
                                        </tr>
                                    <?php endforeach; ?>
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
