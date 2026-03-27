<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Messages / Contact']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 10);
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalItems = max(0, (int) ($totalItems ?? count($messages ?? [])));
$paginationQuery = (string) ($paginationQuery ?? '');
?>
<style>
.admin-table-card,
.admin-table-card .card-body,
.admin-table-card h5,
.admin-table-card p {
    color: #0f172a;
}

.admin-table-card .table {
    color: #0f172a;
    --bs-table-color: #0f172a;
    --bs-table-bg: #ffffff;
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
                <div class="card common-card admin-table-card">
                    <div class="card-body">
                        <h5 class="mb-3">Messages / Contact</h5>
                        <form method="GET" action="<?= ROOT ?>/Admins/messages" class="row gy-3 mb-4">
                            <div class="col-md-3">
                                <select name="per_page" class="common-input common-input--bg">
                                    <option value="10" <?= $perPage === 10 ? 'selected' : '' ?>>10 par page</option>
                                    <option value="20" <?= $perPage === 20 ? 'selected' : '' ?>>20 par page</option>
                                    <option value="50" <?= $perPage === 50 ? 'selected' : '' ?>>50 par page</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary" type="submit">Actualiser l affichage</button>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Projet</th>
                                        <th>Message</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($messages)): ?>
                                        <?php foreach ($messages as $msg): ?>
                                            <tr>
                                                <td><?= htmlspecialchars((string) ($msg->nom ?? '')) ?></td>
                                                <td><?= htmlspecialchars((string) ($msg->email ?? '')) ?></td>
                                                <td><?= htmlspecialchars((string) ($msg->projet ?? '')) ?></td>
                                                <td><?= htmlspecialchars((string) ($msg->message ?? '')) ?></td>
                                                <td><?= htmlspecialchars(date('Y-m-d', strtotime((string)($msg->created_at ?? 'now')))) ?></td>
                                                <td>
                                                    <a href="<?= ROOT ?>/Admins/message_detail/<?= (int) ($msg->id ?? 0) ?>" class="btn btn-outline-secondary btn-sm">Voir / Repondre</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="6" class="text-center text-muted">Aucun message recu.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php $this->view('Partials/admin-pagination', [
                            'currentPage' => $currentPage,
                            'perPage' => $perPage,
                            'totalPages' => $totalPages,
                            'totalItems' => $totalItems,
                            'basePath' => 'Admins/messages',
                            'queryString' => $paginationQuery,
                            'itemLabel' => 'message(s)',
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
