<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Corbeille DER']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$derStats = $derStats ?? [];
$derPosts = $derPosts ?? [];
$derAllowedTypes = $derAllowedTypes ?? [];
$derTypeFilter = $derTypeFilter ?? 'all';
$derSearch = $derSearch ?? '';
$derDateFrom = $derDateFrom ?? '';
$derDateTo = $derDateTo ?? '';
$derSortBy = $derSortBy ?? 'date';
$derSortDir = $derSortDir ?? 'desc';
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 10);
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalItems = max(0, (int) ($totalItems ?? count($derPosts ?? [])));
$paginationQuery = (string) ($paginationQuery ?? '');
?>
<style>
.der-trash-card,
.der-trash-card .card-body,
.der-trash-card h3,
.der-trash-card h4,
.der-trash-card h5,
.der-trash-card p,
.der-trash-card span,
.der-trash-card label {
    color: #0f172a;
}

.der-trash-hero {
    padding: 24px 28px;
    border-radius: 28px;
    background: linear-gradient(135deg, #ffffff, #f8fafc);
    border: 1px solid #dbe4ee;
    margin-bottom: 24px;
}
</style>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-4 der-trash-card">
                <?php $this->view('set_flash'); ?>

                <div class="der-trash-hero">
                    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                        <div>
                            <h3 class="mb-1">Corbeille DER</h3>
                            <p class="mb-0 text-muted">Retrouvez ici les publications archivees et restaurez-les si necessaire.</p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="<?= ROOT ?>/Homes/der_espace" class="btn btn-outline-primary">Retour a la gestion</a>
                            <span class="btn btn-light border disabled">Archives: <?= (int) ($derStats['archived'] ?? 0) ?></span>
                        </div>
                    </div>
                </div>

                <div class="card common-card">
                    <div class="card-body">
                        <form method="GET" action="<?= ROOT ?>/Homes/der_corbeille" class="row gy-3 mb-4" id="der-trash-filter-form">
                            <input type="hidden" name="visibility" value="archived">
                            <div class="col-md-4">
                                <input type="text" name="search" value="<?= htmlspecialchars($derSearch) ?>" class="common-input common-input--bg" placeholder="Rechercher dans la corbeille">
                            </div>
                            <div class="col-md-2">
                                <select name="type" class="common-input common-input--bg">
                                    <option value="all" <?= $derTypeFilter === 'all' ? 'selected' : '' ?>>Tous types</option>
                                    <?php foreach ($derAllowedTypes as $type): ?>
                                        <option value="<?= htmlspecialchars($type) ?>" <?= $derTypeFilter === $type ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($type)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_from" value="<?= htmlspecialchars($derDateFrom) ?>" class="common-input common-input--bg">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_to" value="<?= htmlspecialchars($derDateTo) ?>" class="common-input common-input--bg">
                            </div>
                            <div class="col-md-1">
                                <select name="sort_by" class="common-input common-input--bg">
                                    <option value="date" <?= $derSortBy === 'date' ? 'selected' : '' ?>>Date</option>
                                    <option value="title" <?= $derSortBy === 'title' ? 'selected' : '' ?>>Titre</option>
                                    <option value="type" <?= $derSortBy === 'type' ? 'selected' : '' ?>>Type</option>
                                    <option value="author" <?= $derSortBy === 'author' ? 'selected' : '' ?>>Auteur</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <select name="sort_dir" class="common-input common-input--bg">
                                    <option value="desc" <?= $derSortDir === 'desc' ? 'selected' : '' ?>>Desc</option>
                                    <option value="asc" <?= $derSortDir === 'asc' ? 'selected' : '' ?>>Asc</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="per_page" class="common-input common-input--bg">
                                    <option value="5" <?= $perPage === 5 ? 'selected' : '' ?>>5</option>
                                    <option value="10" <?= $perPage === 10 ? 'selected' : '' ?>>10</option>
                                    <option value="20" <?= $perPage === 20 ? 'selected' : '' ?>>20</option>
                                    <option value="50" <?= $perPage === 50 ? 'selected' : '' ?>>50</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                            </div>
                        </form>

                        <?php $this->view('Partials/der-posts-list', [
                            'derPosts' => $derPosts,
                            'derAllowedTypes' => $derAllowedTypes,
                            'formAction' => ROOT . '/Homes/der_corbeille',
                            'paginationBasePath' => 'Homes/der_corbeille',
                            'detailReturnBasePath' => 'Homes/der_corbeille',
                            'paginationQuery' => $paginationQuery,
                            'currentPage' => $currentPage,
                            'perPage' => $perPage,
                            'totalPages' => $totalPages,
                            'totalItems' => $totalItems,
                            'activeEditPostId' => 0,
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
