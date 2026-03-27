<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Projets les plus suivis']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$projects = $projects ?? [];
$projectPlatformStats = $projectPlatformStats ?? [];
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
.admin-table-card h4 {
    color: #0f172a;
}

.admin-table-card .table {
    color: #0f172a;
    --bs-table-color: #0f172a;
    --bs-table-bg: #ffffff;
    --bs-table-hover-color: #0f172a;
    --bs-table-hover-bg: #eef2ff;
}

.admin-table-card .table thead th {
    color: #0f172a;
    background: #e2e8f0;
    font-weight: 800;
}

.admin-table-card .table tbody td {
    color: #1e293b;
    background: #ffffff;
    vertical-align: middle;
}

.admin-table-card .btn {
    font-weight: 700;
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

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div>
                        <h3 class="mb-1">Projets les plus suivis</h3>
                        <p class="text-muted mb-0">Vue admin des projets les plus actifs de la plateforme.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <form method="GET" action="<?= ROOT ?>/Admins/most_followed_projects" class="d-flex gap-2 flex-wrap">
                            <select name="per_page" class="common-input common-input--bg">
                                <option value="10" <?= $perPage === 10 ? 'selected' : '' ?>>10 par page</option>
                                <option value="20" <?= $perPage === 20 ? 'selected' : '' ?>>20 par page</option>
                                <option value="50" <?= $perPage === 50 ? 'selected' : '' ?>>50 par page</option>
                            </select>
                            <button class="btn btn-primary" type="submit">Actualiser</button>
                        </form>
                        <a href="<?= ROOT ?>/Admins/dashboard" class="btn btn-outline-secondary">Retour dashboard</a>
                    </div>
                </div>

                <div class="row gy-4 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Likes</p><h4><?= (int) ($projectPlatformStats['likes'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Avis</p><h4><?= (int) ($projectPlatformStats['reviews'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Messages</p><h4><?= (int) ($projectPlatformStats['messages'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card"><div class="card-body"><p class="mb-1">Note moyenne</p><h4><?= number_format((float) ($projectPlatformStats['average_rating'] ?? 0), 1) ?>/5</h4></div></div>
                    </div>
                </div>

                <div class="card common-card admin-table-card">
                    <div class="card-body">
                        <h5 class="mb-3">Classement des projets suivis</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Titre</th>
                                        <th>Auteur</th>
                                        <th>Categorie</th>
                                        <th>Statut</th>
                                        <th>Likes</th>
                                        <th>Avis</th>
                                        <th>Messages</th>
                                        <th>Note</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($projects)): ?>
                                        <?php foreach ($projects as $index => $project): ?>
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
                                                <td><?= (($currentPage - 1) * $perPage) + $index + 1 ?></td>
                                                <td><?= htmlspecialchars((string) ($project->title ?? 'Projet')) ?></td>
                                                <td><?= htmlspecialchars((string) ($project->auteur ?? '-')) ?></td>
                                                <td><?= htmlspecialchars((string) ($project->categorie ?? '-')) ?></td>
                                                <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span></td>
                                                <td><?= (int) ($project->likes_count ?? 0) ?></td>
                                                <td><?= (int) ($project->reviews_count ?? 0) ?></td>
                                                <td><?= (int) ($project->messages_count ?? 0) ?></td>
                                                <td><?= number_format((float) ($project->average_rating ?? 0), 1) ?>/5</td>
                                                <td>
                                                    <a href="<?= ROOT ?>/Admins/project_detail/<?= (int) ($project->id ?? 0) ?>" class="btn btn-outline-secondary btn-sm">Voir detail complet</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="10" class="text-center text-muted">Aucun projet suivi disponible.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php $this->view('Partials/admin-pagination', [
                            'currentPage' => $currentPage,
                            'perPage' => $perPage,
                            'totalPages' => $totalPages,
                            'totalItems' => $totalItems,
                            'basePath' => 'Admins/most_followed_projects',
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
</body>
</html>
