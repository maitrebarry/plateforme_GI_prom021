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
    padding: 1rem 1.5rem;
    border-radius: 16px;
    background: white;
    box-shadow: var(--card-shadow);
    display: flex;
    flex-direction: column;
    border-top: 4px solid var(--primary-color);
}

.table thead th {
    background: #f8fafc !important;
    color: #475569 !important;
    font-weight: 800 !important;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    padding: 1.25rem 1rem !important;
    border-bottom: 2px solid #e2e8f0 !important;
}

.table tbody td {
    padding: 1rem !important;
    font-weight: 600;
    color: #1e293b;
    vertical-align: middle;
}

.table tbody tr {
    transition: all 0.2s;
}

.table tbody tr:hover {
    background-color: #f1f5f9 !important;
}

.btn {
    font-weight: 700;
    border-radius: 12px;
    padding: 0.6rem 1.2rem;
    transition: all 0.3s;
}

.btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; }
.btn-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; }
.btn-danger { background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); border: none; }

.filter-shelf {
    background: #f8fafc;
    border-radius: 16px;
    padding: 1.25rem;
    margin-bottom: 2rem;
    border: 1px solid #e2e8f0;
}

.category-input {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.5rem 0.75rem;
    width: 100%;
    transition: all 0.2s;
}

.category-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    outline: none;
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
                            <a href="<?= ROOT ?>/Admins/dashboard" class="header-back-btn">
                                ⬅️
                            </a>
                            <div>
                                <h3 class="mb-0 fw-800 text-primary">Gestion des Catégories</h3>
                                <p class="text-muted small mb-0">Organisation thématique des projets de la plateforme</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card common-card mb-4">
                    <div class="card-body p-4">
                        <h5 class="mb-4 fw-800 border-bottom pb-2">➕ Ajouter une catégorie</h5>

                        <form method="POST" action="<?= ROOT ?>/Admins/categories" class="row gy-3">
                            <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                            <div class="row align-items-center gy-3">
                                <div class="col-md-4">
                                    <input type="text" name="nom" class="form-control rounded-3 border px-3 py-2" placeholder="Nom de la catégorie (ex: Intelligence Artificielle)" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="description" class="form-control rounded-3 border px-3 py-2" placeholder="Brève description de l'usage...">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary w-100 shadow-sm" type="submit" name="add_category">Ajouter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card common-card admin-category-card">
                    <div class="card-body">
                        <h5 class="mb-3">Liste des categories</h5>

                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <div class="stat-pill border-primary">
                                    <span class="small text-muted fw-bold">TOTAL CATÉGORIES</span>
                                    <strong class="h3 mb-0"><?= (int) ($categoryStats['total'] ?? 0) ?></strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-pill border-success">
                                    <span class="small text-muted fw-bold">AVEC PROJETS</span>
                                    <strong class="h3 mb-0"><?= (int) ($categoryStats['used'] ?? 0) ?></strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-pill border-secondary">
                                    <span class="small text-muted fw-bold">SANS PROJETS</span>
                                    <strong class="h3 mb-0"><?= (int) ($categoryStats['unused'] ?? 0) ?></strong>
                                </div>
                            </div>
                        </div>

                        <div class="filter-shelf">
                            <form method="GET" action="<?= ROOT ?>/Admins/categories" class="row gy-3" id="category-filter-form">
                                <div class="col-md-4">
                                    <label class="small fw-bold text-muted mb-2">Recherche</label>
                                    <input type="text" name="search" value="<?= htmlspecialchars($categorySearch) ?>" class="form-control rounded-3 border-0 bg-white" placeholder="Nom de catégorie...">
                                </div>
                                <div class="col-md-3">
                                    <label class="small fw-bold text-muted mb-2">Utilisation</label>
                                    <select name="usage" class="form-select rounded-3 border-0 bg-white auto-submit-filter">
                                        <option value="all" <?= $categoryUsageFilter === 'all' ? 'selected' : '' ?>>Tout afficher</option>
                                        <option value="used" <?= $categoryUsageFilter === 'used' ? 'selected' : '' ?>>Utilisées uniquement</option>
                                        <option value="unused" <?= $categoryUsageFilter === 'unused' ? 'selected' : '' ?>>Non utilisées</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="small fw-bold text-muted mb-2">Tri par</label>
                                    <div class="input-group">
                                        <select name="sort_by" class="form-select rounded-start-3 border-0 bg-white auto-submit-filter">
                                            <option value="name" <?= $categorySortBy === 'name' ? 'selected' : '' ?>>Nom</option>
                                            <option value="projects" <?= $categorySortBy === 'projects' ? 'selected' : '' ?>>Usage</option>
                                        </select>
                                        <select name="sort_dir" class="form-select rounded-end-3 border-0 bg-white auto-submit-filter">
                                            <option value="asc" <?= $categorySortDir === 'asc' ? 'selected' : '' ?>>ASC</option>
                                            <option value="desc" <?= $categorySortDir === 'desc' ? 'selected' : '' ?>>DESC</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-primary w-100 shadow-sm" type="submit">🔍 OK</button>
                                </div>
                            </form>
                        </div>

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
                                                <td class="small fw-bold text-muted">#<?= (int) ($category->id ?? 0) ?></td>
                                                <td>
                                                    <input type="text" name="nom" value="<?= htmlspecialchars((string) ($category->nom ?? '')) ?>" class="category-input fw-bold" form="<?= $formId ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="description" value="<?= htmlspecialchars((string) ($category->description ?? '')) ?>" class="category-input small" form="<?= $formId ?>">
                                                </td>
                                                <td><span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3"><?= (int) ($category->total_projects ?? 0) ?> projets</span></td>
                                                <td class="d-flex gap-2">
                                                    <button class="btn btn-success btn-sm p-2 d-flex align-items-center justify-content-center" name="update_category" type="submit" form="<?= $formId ?>" title="Enregistrer">💾</button>
                                                    <button class="btn btn-danger btn-sm p-2 d-flex align-items-center justify-content-center" name="delete_category" type="submit" form="<?= $formId ?>" onclick="return confirm('Supprimer cette categorie ?')" title="Supprimer">🗑️</button>
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
