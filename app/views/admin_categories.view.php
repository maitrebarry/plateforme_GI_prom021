<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Gestion des categories']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$categorySearch = $categorySearch ?? '';
$categorySortBy = $categorySortBy ?? 'name';
$categorySortDir = $categorySortDir ?? 'asc';
$categoryUsageFilter = $categoryUsageFilter ?? 'all';
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 10);
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalItems = max(0, (int) ($totalItems ?? count($categories ?? [])));
$paginationQuery = (string) ($paginationQuery ?? '');
$categoryStats = $categoryStats ?? ['total' => 0, 'used' => 0, 'unused' => 0];
?>
<style>
.admin-category-card,
.admin-category-card .card-body,
.admin-category-card h5,
.admin-category-card p,
.admin-category-card label {
    color: #0f172a;
}

.admin-category-card .table {
    color: #0f172a;
    --bs-table-bg: #ffffff;
    --bs-table-striped-bg: #f8fafc;
}

.admin-category-card .table thead th {
    background: #e2e8f0;
    color: #0f172a;
    font-weight: 800;
}

.admin-category-card .table tbody td {
    background: #ffffff;
    color: #1e293b;
    vertical-align: middle;
}

.admin-category-card .btn {
    font-weight: 700;
}

.admin-category-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}

.admin-category-stat {
    padding: 16px 18px;
    border-radius: 20px;
    background: #f8fafc;
    border: 1px solid #dbe4ee;
}

.admin-category-stat strong {
    display: block;
    font-size: 1.7rem;
    line-height: 1.1;
    margin-top: 8px;
}

.admin-pagination-wrap {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.admin-pagination-summary {
    color: #475569;
    font-weight: 600;
}

.admin-pagination {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.page-link-nav {
    min-width: 40px;
    height: 40px;
    padding: 0 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #0f172a;
    text-decoration: none;
    font-weight: 700;
}

.page-link-nav.is-active {
    background: #0d6efd;
    border-color: #0d6efd;
    color: #ffffff;
}

.page-link-nav.is-disabled {
    pointer-events: none;
    opacity: 0.45;
}
</style>
<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-4">
                <?php $this->view('set_flash'); ?>

                <div class="card common-card mb-4 admin-category-card">
                    <div class="card-body">
                        <h5 class="mb-3">Ajouter une categorie</h5>
                        <form method="POST" action="<?= ROOT ?>/Admins/categories" class="row gy-3">
                            <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
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

                <div class="card common-card admin-category-card">
                    <div class="card-body">
                        <h5 class="mb-3">Liste des categories</h5>

                        <div class="admin-category-stats">
                            <div class="admin-category-stat"><span>Total categories</span><strong><?= (int) ($categoryStats['total'] ?? 0) ?></strong></div>
                            <div class="admin-category-stat"><span>Avec projets</span><strong><?= (int) ($categoryStats['used'] ?? 0) ?></strong></div>
                            <div class="admin-category-stat"><span>Sans projets</span><strong><?= (int) ($categoryStats['unused'] ?? 0) ?></strong></div>
                        </div>

                        <form method="GET" action="<?= ROOT ?>/Admins/categories" class="row gy-3 mb-4" id="category-filter-form">
                            <div class="col-md-4">
                                <input type="text" name="search" value="<?= htmlspecialchars($categorySearch) ?>" class="common-input common-input--bg" placeholder="Rechercher une categorie">
                            </div>
                            <div class="col-md-2">
                                <select name="usage" class="common-input common-input--bg auto-submit-filter">
                                    <option value="all" <?= $categoryUsageFilter === 'all' ? 'selected' : '' ?>>Toutes</option>
                                    <option value="used" <?= $categoryUsageFilter === 'used' ? 'selected' : '' ?>>Avec projets</option>
                                    <option value="unused" <?= $categoryUsageFilter === 'unused' ? 'selected' : '' ?>>Sans projets</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="sort_by" class="common-input common-input--bg auto-submit-filter">
                                    <option value="name" <?= $categorySortBy === 'name' ? 'selected' : '' ?>>Nom</option>
                                    <option value="projects" <?= $categorySortBy === 'projects' ? 'selected' : '' ?>>Nb projets</option>
                                    <option value="description" <?= $categorySortBy === 'description' ? 'selected' : '' ?>>Description</option>
                                    <option value="id" <?= $categorySortBy === 'id' ? 'selected' : '' ?>>ID</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="sort_dir" class="common-input common-input--bg auto-submit-filter">
                                    <option value="asc" <?= $categorySortDir === 'asc' ? 'selected' : '' ?>>Asc</option>
                                    <option value="desc" <?= $categorySortDir === 'desc' ? 'selected' : '' ?>>Desc</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <select name="per_page" class="common-input common-input--bg auto-submit-filter">
                                    <option value="10" <?= $perPage === 10 ? 'selected' : '' ?>>10</option>
                                    <option value="20" <?= $perPage === 20 ? 'selected' : '' ?>>20</option>
                                    <option value="50" <?= $perPage === 50 ? 'selected' : '' ?>>50</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-primary w-100" type="submit">OK</button>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Description</th>
                                        <th>Projets</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <?php $formId = 'category-form-' . (int) ($category->id ?? 0); ?>
                                            <tr>
                                                <form method="POST" action="<?= ROOT ?>/Admins/categories" id="<?= $formId ?>">
                                                    <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                                                    <input type="hidden" name="id" value="<?= (int) ($category->id ?? 0) ?>">
                                                </form>
                                                <td><?= (int) ($category->id ?? 0) ?></td>
                                                <td>
                                                    <input type="text" name="nom" value="<?= htmlspecialchars((string) ($category->nom ?? '')) ?>" class="common-input common-input--bg" form="<?= $formId ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="description" value="<?= htmlspecialchars((string) ($category->description ?? '')) ?>" class="common-input common-input--bg" form="<?= $formId ?>">
                                                </td>
                                                <td><span class="badge bg-primary-subtle text-primary"><?= (int) ($category->total_projects ?? 0) ?></span></td>
                                                <td class="d-flex gap-2 flex-wrap">
                                                    <button class="btn btn-success btn-sm" name="update_category" type="submit" form="<?= $formId ?>">Modifier</button>
                                                    <button class="btn btn-danger btn-sm" name="delete_category" type="submit" form="<?= $formId ?>" onclick="return confirm('Supprimer cette categorie ?')">Supprimer</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5" class="text-center text-muted">Aucune categorie disponible.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php $this->view('Partials/admin-pagination', [
                            'currentPage' => $currentPage,
                            'perPage' => $perPage,
                            'totalPages' => $totalPages,
                            'totalItems' => $totalItems,
                            'basePath' => 'Admins/categories',
                            'queryString' => $paginationQuery,
                            'itemLabel' => 'categorie(s)',
                        ]); ?>
                    </div>
                </div>
            </div>
            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>
<?php $this->view('Partials/scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.auto-submit-filter').forEach(function (element) {
        element.addEventListener('change', function () {
            var form = document.getElementById('category-filter-form');
            if (form) {
                form.submit();
            }
        });
    });
});
</script>
</body>
</html>
