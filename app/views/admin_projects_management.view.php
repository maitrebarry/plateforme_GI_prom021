<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Gestion des projets']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$projectSearch = $projectSearch ?? '';
$projectStatusFilter = $projectStatusFilter ?? 'all';
$projectCategoryFilter = $projectCategoryFilter ?? null;
$projectDateFrom = $projectDateFrom ?? '';
$projectDateTo = $projectDateTo ?? '';
$projectSortBy = $projectSortBy ?? 'date';
$projectSortDir = $projectSortDir ?? 'desc';
$categories = $categories ?? [];
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 10);
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalItems = max(0, (int) ($totalItems ?? count($projects ?? [])));
$paginationQuery = (string) ($paginationQuery ?? '');
?>
<style>
.admin-table-card,
.admin-table-card .card-body,
.admin-table-card h5,
.admin-table-card p,
.admin-table-card label {
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

.admin-table-card .table a:not(.btn) {
    color: #0f766e;
    font-weight: 700;
}

.admin-table-card .common-input,
.admin-table-card select,
.admin-table-card input {
    color: #0f172a;
    background: #ffffff;
}

.admin-bulk-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px;
    margin-bottom: 16px;
    border: 1px solid #dbe4ee;
    border-radius: 18px;
    background: #f8fafc;
}

.admin-bulk-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.admin-table-actions .btn,
.admin-bulk-actions .btn,
.admin-table-card .btn {
    font-weight: 700;
    border-width: 1px;
    box-shadow: none;
}

.admin-table-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
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
                <div class="card common-card admin-table-card">
                    <div class="card-body">
                        <h5 class="mb-3">Gestion des projets</h5>
                        <form method="GET" action="<?= ROOT ?>/Admins/projects_management" class="row gy-3 mb-4">
                            <div class="col-md-4">
                                <input type="text" name="search" value="<?= htmlspecialchars($projectSearch) ?>" class="common-input common-input--bg" placeholder="Rechercher par titre, auteur ou categorie">
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="common-input common-input--bg">
                                    <option value="all" <?= $projectStatusFilter === 'all' ? 'selected' : '' ?>>Tous les statuts</option>
                                    <option value="en_attente" <?= $projectStatusFilter === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                    <option value="valide" <?= $projectStatusFilter === 'valide' ? 'selected' : '' ?>>Valide</option>
                                    <option value="rejete" <?= $projectStatusFilter === 'rejete' ? 'selected' : '' ?>>Rejete</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="category" class="common-input common-input--bg">
                                    <option value="">Toutes categories</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= (int) ($category->id ?? 0) ?>" <?= (int) $projectCategoryFilter === (int) ($category->id ?? 0) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars((string) ($category->nom ?? 'Categorie')) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_from" value="<?= htmlspecialchars($projectDateFrom) ?>" class="common-input common-input--bg">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_to" value="<?= htmlspecialchars($projectDateTo) ?>" class="common-input common-input--bg">
                            </div>
                            <div class="col-md-2">
                                <select name="sort_by" class="common-input common-input--bg">
                                    <option value="date" <?= $projectSortBy === 'date' ? 'selected' : '' ?>>Trier par date</option>
                                    <option value="title" <?= $projectSortBy === 'title' ? 'selected' : '' ?>>Trier par titre</option>
                                    <option value="author" <?= $projectSortBy === 'author' ? 'selected' : '' ?>>Trier par auteur</option>
                                    <option value="category" <?= $projectSortBy === 'category' ? 'selected' : '' ?>>Trier par categorie</option>
                                    <option value="status" <?= $projectSortBy === 'status' ? 'selected' : '' ?>>Trier par statut</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="sort_dir" class="common-input common-input--bg">
                                    <option value="desc" <?= $projectSortDir === 'desc' ? 'selected' : '' ?>>Descendant</option>
                                    <option value="asc" <?= $projectSortDir === 'asc' ? 'selected' : '' ?>>Ascendant</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="per_page" class="common-input common-input--bg">
                                    <option value="10" <?= $perPage === 10 ? 'selected' : '' ?>>10 par page</option>
                                    <option value="20" <?= $perPage === 20 ? 'selected' : '' ?>>20 par page</option>
                                    <option value="50" <?= $perPage === 50 ? 'selected' : '' ?>>50 par page</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100" type="submit">Filtrer</button>
                            </div>
                        </form>
                        <form method="POST" action="<?= ROOT ?>/Admins/projects_management">
                            <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                            <div class="admin-bulk-bar">
                                <label class="d-inline-flex align-items-center gap-2 mb-0">
                                    <input type="checkbox" id="select-all-management">
                                    <span>Tout selectionner</span>
                                </label>
                                <div class="admin-bulk-actions">
                                    <button class="btn btn-success btn-sm" type="submit" name="bulk_validate_projects">Valider la selection</button>
                                    <button class="btn btn-warning btn-sm" type="submit" name="bulk_set_pending_projects">Mettre en attente</button>
                                    <button class="btn btn-danger btn-sm" type="submit" name="bulk_reject_projects" onclick="return confirm('Rejeter les projets selectionnes ?');">Rejeter la selection</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Titre</th>
                                        <th>Auteur</th>
                                        <th>Categorie</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($projects)): ?>
                                        <?php foreach ($projects as $project): ?>
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
                                                <td><input type="checkbox" class="management-project-checkbox" name="project_ids[]" value="<?= (int)($project->id ?? 0) ?>"></td>
                                                <td><?= htmlspecialchars($project->title ?? '') ?></td>
                                                <td><?= htmlspecialchars($project->auteur ?? '') ?></td>
                                                <td><?= htmlspecialchars($project->categorie ?? '') ?></td>
                                                <td><?= htmlspecialchars(date('Y-m-d', strtotime((string)($project->created_at ?? 'now')))) ?></td>
                                                <td>
                                                    <span class="badge <?= $badgeClass ?>">
                                                        <?= htmlspecialchars($status) ?>
                                                    </span>
                                                </td>
                                                <td class="admin-table-actions">
                                                    <a href="<?= ROOT ?>/Admins/project_detail/<?= (int)($project->id ?? 0) ?>" class="btn btn-outline-secondary btn-sm">Voir detail</a>
                                                    <button class="btn btn-success btn-sm" type="submit" name="single_validate_project" value="<?= (int)($project->id ?? 0) ?>">Valider</button>
                                                    <button class="btn btn-warning btn-sm" type="submit" name="single_set_pending_project" value="<?= (int)($project->id ?? 0) ?>">Mettre en attente</button>
                                                    <button class="btn btn-danger btn-sm" type="submit" name="single_reject_project" value="<?= (int)($project->id ?? 0) ?>" onclick="return confirm('Rejeter ce projet ?');">Rejeter</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="7" class="text-center text-muted">Aucun projet trouve.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            </div>
                        </form>
                        <?php $this->view('Partials/admin-pagination', [
                            'currentPage' => $currentPage,
                            'perPage' => $perPage,
                            'totalPages' => $totalPages,
                            'totalItems' => $totalItems,
                            'basePath' => 'Admins/projects_management',
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    var selectAll = document.getElementById('select-all-management');
    if (!selectAll) {
        return;
    }

    var checkboxes = Array.from(document.querySelectorAll('.management-project-checkbox'));
    selectAll.addEventListener('change', function () {
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = selectAll.checked;
        });
    });
});
</script>
</body>
</html>
