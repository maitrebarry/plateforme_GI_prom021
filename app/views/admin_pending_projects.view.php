<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Projets à valider']); ?>
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
                <div class="card common-card">
                    <div class="card-body">
                        <h5 class="mb-3">Projets à valider</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Auteur</th>
                                        <th>Catégorie</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($projects)): ?>
                                        <?php foreach ($projects as $project): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($project->title ?? '') ?></td>
                                                <td><?= htmlspecialchars($project->auteur ?? '') ?></td>
                                                <td><?= htmlspecialchars($project->categorie ?? '') ?></td>
                                                <td><?= htmlspecialchars(date('Y-m-d', strtotime((string)($project->created_at ?? 'now')))) ?></td>
                                                <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($project->statut ?? 'en_attente') ?></span></td>
                                                <td class="d-flex gap-2">
                                                    <form method="POST" action="<?= ROOT ?>/Admins/pending_projects">
                                                        <input type="hidden" name="project_id" value="<?= (int)($project->id ?? 0) ?>">
                                                        <button class="btn btn-success btn-sm" type="submit" name="validate_project">Valider</button>
                                                    </form>
                                                    <form method="POST" action="<?= ROOT ?>/Admins/pending_projects" onsubmit="return confirm('Supprimer ce projet ?');">
                                                        <input type="hidden" name="project_id" value="<?= (int)($project->id ?? 0) ?>">
                                                        <button class="btn btn-danger btn-sm" type="submit" name="reject_project">Refuser</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="6" class="text-center text-muted">Aucun projet en attente.</td></tr>
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
