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
}

.btn {
    font-weight: 700;
    border-radius: 12px;
    padding: 0.6rem 1.2rem;
    transition: all 0.3s;
}

.btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; }
.btn-outline-primary { border: 2px solid var(--primary-color); color: var(--primary-color); }
.btn-outline-primary:hover { background: var(--primary-color); color: white; }

.message-snippet {
    display: block;
    max-width: 300px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: var(--text-muted);
    font-size: 0.9rem;
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
                                <h3 class="mb-0 fw-800 text-primary">Messages & Contacts</h3>
                                <p class="text-muted small mb-0">Communication directe avec les étudiants et visiteurs</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card common-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0 fw-800">📥 Boîte de réception</h5>
                            <form method="GET" action="<?= ROOT ?>/Admins/messages" class="d-flex gap-2">
                                <select name="per_page" class="form-select form-select-sm rounded-pill border-0 bg-light px-3">
                                    <option value="10" <?= $perPage === 10 ? 'selected' : '' ?>>10 / page</option>
                                    <option value="20" <?= $perPage === 20 ? 'selected' : '' ?>>20 / page</option>
                                    <option value="50" <?= $perPage === 50 ? 'selected' : '' ?>>50 / page</option>
                                </select>
                                <button class="btn btn-primary btn-sm rounded-pill px-4" type="submit">OK</button>
                            </form>
                        </div>
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
                                                <td class="fw-800 text-dark">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">👤</div>
                                                        <span><?= htmlspecialchars((string) ($msg->nom ?? 'Inconnu')) ?></span>
                                                    </div>
                                                </td>
                                                <td class="small text-muted"><?= htmlspecialchars((string) ($msg->email ?? '')) ?></td>
                                                <td><span class="badge bg-light text-dark border px-3"><?= htmlspecialchars((string) ($msg->projet ?? 'Général')) ?></span></td>
                                                <td><span class="message-snippet"><?= htmlspecialchars((string) ($msg->message ?? '')) ?></span></td>
                                                <td class="small text-muted"><?= htmlspecialchars(date('d/m/Y', strtotime((string)($msg->created_at ?? 'now')))) ?></td>
                                                <td class="text-end">
                                                    <a href="<?= ROOT ?>/Admins/message_detail/<?= (int) ($msg->id ?? 0) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                                        Répondre 📩
                                                    </a>
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
