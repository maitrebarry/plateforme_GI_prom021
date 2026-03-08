<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Gestion des catégories']); ?>
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

                <div class="card common-card mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Ajouter une catégorie</h5>
                        <form method="POST" action="<?= ROOT ?>/Admins/categories" class="row gy-3">
                            <div class="col-md-4">
                                <input type="text" name="nom" class="common-input common-input--bg" placeholder="Nom" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="description" class="common-input common-input--bg" placeholder="Description">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100" type="submit" name="add_category">Ajouter</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card common-card">
                    <div class="card-body">
                        <h5 class="mb-3">Liste des catégories</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead><tr><th>ID</th><th>Nom</th><th>Description</th><th>Actions</th></tr></thead>
                                <tbody>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <tr>
                                                <form method="POST" action="<?= ROOT ?>/Admins/categories">
                                                    <td>
                                                        <?= (int)($category->id ?? 0) ?>
                                                        <input type="hidden" name="id" value="<?= (int)($category->id ?? 0) ?>">
                                                    </td>
                                                    <td><input type="text" name="nom" value="<?= htmlspecialchars($category->nom ?? '') ?>" class="common-input common-input--bg"></td>
                                                    <td><input type="text" name="description" value="<?= htmlspecialchars($category->description ?? '') ?>" class="common-input common-input--bg"></td>
                                                    <td class="d-flex gap-2">
                                                        <button class="btn btn-success btn-sm" name="update_category" type="submit">Modifier</button>
                                                        <button class="btn btn-danger btn-sm" name="delete_category" type="submit" onclick="return confirm('Supprimer cette catégorie ?')">Supprimer</button>
                                                    </td>
                                                </form>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="4" class="text-center text-muted">Aucune catégorie disponible.</td></tr>
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
