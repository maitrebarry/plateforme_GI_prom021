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
    padding: 1.25rem 1rem !important;
    font-weight: 600;
    color: #1e293b;
    vertical-align: middle;
}

.table tbody tr {
    transition: all 0.2s;
}

.table tbody tr:hover {
    background-color: #f1f5f9 !important;
    transform: scale(1.002);
}

.badge {
    padding: 0.6em 1.25em;
    border-radius: 10px;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.7rem;
}

.btn {
    font-weight: 700;
    border-radius: 12px;
    padding: 0.6rem 1.2rem;
    transition: all 0.3s;
}

.btn-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; }
.btn-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none; color: white; }
.btn-danger { background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); border: none; }
.btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; }

.filter-shelf {
    background: #f8fafc;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #e2e8f0;
}

.admin-bulk-bar {
    background: #f1f5f9;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}

.page-link-nav {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: white;
    border: 1px solid #e2e8f0;
    color: var(--text-main);
    text-decoration: none;
    font-weight: 700;
    transition: all 0.2s;
}

.page-link-nav.is-active {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
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
                                <h3 class="mb-0 fw-800 text-primary">Gestion des Projets</h3>
                                <p class="text-muted small mb-0">Vue d'ensemble et contrôle global de la plateforme</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                             <a href="<?= ROOT ?>/Admins/pending_projects" class="btn btn-warning btn-sm rounded-pill px-4">⌛ File d'attente</a>
                        </div>
                    </div>
                </div>

                <div class="card common-card">
                    <div class="card-body p-4">

                        <div class="filter-shelf">
                            <form method="GET" action="<?= ROOT ?>/Admins/projects_management" class="row gy-3">
                                <div class="col-md-3">
                                    <label class="small fw-bold text-muted mb-2">Recherche</label>
                                    <input type="text" name="search" value="<?= htmlspecialchars($projectSearch) ?>" class="form-control rounded-3 border-0 bg-white" placeholder="Titre, auteur...">
                                </div>
                                <div class="col-md-2">
                                    <label class="small fw-bold text-muted mb-2">Statut</label>
                                    <select name="status" class="form-select rounded-3 border-0 bg-white">
                                        <option value="all" <?= $projectStatusFilter === 'all' ? 'selected' : '' ?>>Tous les statuts</option>
                                        <option value="en_attente" <?= $projectStatusFilter === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                        <option value="valide" <?= $projectStatusFilter === 'valide' ? 'selected' : '' ?>>Valide</option>
                                        <option value="rejete" <?= $projectStatusFilter === 'rejete' ? 'selected' : '' ?>>Rejeté</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="small fw-bold text-muted mb-2">Catégorie</label>
                                    <select name="category" class="form-select rounded-3 border-0 bg-white">
                                        <option value="">Toutes catégories</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= (int) ($category->id ?? 0) ?>" <?= (int) $projectCategoryFilter === (int) ($category->id ?? 0) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars((string) ($category->nom ?? 'Categorie')) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="small fw-bold text-muted mb-2">Tri par</label>
                                    <div class="input-group">
                                        <select name="sort_by" class="form-select rounded-start-3 border-0 bg-white">
                                            <option value="date" <?= $projectSortBy === 'date' ? 'selected' : '' ?>>Date</option>
                                            <option value="title" <?= $projectSortBy === 'title' ? 'selected' : '' ?>>Titre</option>
                                            <option value="author" <?= $projectSortBy === 'author' ? 'selected' : '' ?>>Auteur</option>
                                        </select>
                                        <select name="sort_dir" class="form-select rounded-end-3 border-0 bg-white">
                                            <option value="desc" <?= $projectSortDir === 'desc' ? 'selected' : '' ?>>DESC</option>
                                            <option value="asc" <?= $projectSortDir === 'asc' ? 'selected' : '' ?>>ASC</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-primary w-100 shadow-sm" type="submit">🔍 Filtrer</button>
                                </div>
                            </form>
                        </div>
                        <form method="POST" action="<?= ROOT ?>/Admins/projects_management">
                            <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                            <div class="admin-bulk-bar shadow-sm">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" id="select-all-management" style="width: 1.2rem; height: 1.2rem;">
                                    <label class="form-check-label fw-bold small ms-2" for="select-all-management">TOUT SÉLECTIONNER</label>
                                </div>
                                <div class="admin-bulk-actions">
                                    <button class="btn btn-success btn-sm px-3" type="submit" name="bulk_validate_projects">✅ Valider</button>
                                    <button class="btn btn-warning btn-sm px-3" type="submit" name="bulk_set_pending_projects">⌛ Attente</button>
                                    <button class="btn btn-danger btn-sm px-3" type="submit" name="bulk_reject_projects" onclick="return confirm('Rejeter les projets selectionnes ?');">❌ Rejeter</button>
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
                                            $status = (string) ($project->statut_admin ?? 'en_attente');
                                            $badgeClass = 'bg-warning text-dark';
                                            if ($status === 'valide') {
                                                $badgeClass = 'bg-success';
                                            } elseif ($status === 'rejete') {
                                                $badgeClass = 'bg-danger';
                                            }
                                            ?>
                                            <tr>
                                                <td><input type="checkbox" class="management-project-checkbox form-check-input" name="project_ids[]" value="<?= (int)($project->id ?? 0) ?>" style="width: 1.1rem; height: 1.1rem;"></td>
                                                <td class="fw-800 text-dark"><?= htmlspecialchars($project->title ?? '') ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-size: 0.8rem;">👤</div>
                                                        <span><?= htmlspecialchars($project->auteur ?? '') ?></span>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($project->categorie ?? '') ?></span></td>
                                                <td class="text-muted small"><?= htmlspecialchars(date('d/m/Y', strtotime((string)($project->created_at ?? 'now')))) ?></td>
                                                <td><span class="badge <?= $badgeClass ?> bg-opacity-10 text-<?= str_replace('bg-', '', explode(' ', $badgeClass)[0]) ?>"><?= htmlspecialchars($status) ?></span></td>
                                                <td class="admin-table-actions">
                                                    <div class="d-flex gap-1">
                                                        <a href="<?= ROOT ?>/Admins/project_detail/<?= (int)($project->id ?? 0) ?>" class="btn btn-light btn-sm border" title="Détails">👁️</a>
                                                        <button class="btn btn-success btn-sm" type="submit" name="single_validate_project" value="<?= (int)($project->id ?? 0) ?>" title="Valider">✅</button>
                                                        <button class="btn btn-warning btn-sm" type="submit" name="single_set_pending_project" value="<?= (int)($project->id ?? 0) ?>" title="Attente">⌛</button>
                                                        <button class="btn btn-danger btn-sm" type="submit" name="single_reject_project" value="<?= (int)($project->id ?? 0) ?>" onclick="return confirm('Rejeter ce projet ?');" title="Rejeter">❌</button>
                                                    </div>
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
