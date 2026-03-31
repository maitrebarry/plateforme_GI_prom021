<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Détail publication DER']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$post = $post ?? null;
$returnUrl = $returnUrl ?? (ROOT . '/Homes/der_espace');
$isArchived = (int) ($post->is_archived ?? 0) === 1;
$postType = htmlspecialchars((string) ($post->type ?? 'publication'));
$fileCount = count($post->files ?? []);
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

.dashboard-body {
    background-color: var(--bg-light);
    min-height: 100vh;
    padding-bottom: 3rem;
}

.dashboard-body__content { animation: fadeIn 0.5s ease-out; }

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

.common-card {
    border: none;
    border-radius: 20px;
    box-shadow: var(--card-shadow);
    background: #ffffff;
    margin-bottom: 24px;
}

.common-card h5 {
    font-weight: 800;
    color: var(--text-main);
    border-bottom: 2px solid #f1f5f9;
    padding-bottom: 1rem;
    margin-bottom: 1.25rem;
}

.detail-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #f8fafc;
}

.detail-item:last-child { border-bottom: none; }

.detail-icon {
    width: 32px;
    height: 32px;
    min-width: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #f1f5f9;
    font-size: 1rem;
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
    padding: 0.8rem 1.5rem;
    transition: all 0.3s;
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
}

.btn-primary  { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: white; }
.btn-success  { background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: white; }
.btn-warning  { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none; color: white; }
.btn-danger   { background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); border: none; color: white; }
.btn-secondary{ background: #64748b; border: none; color: white; }

.btn-outline-primary {
    border: 2px solid var(--primary-color);
    background: var(--primary-color);
    color: white;
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
    font-size: 1.2rem;
}

.header-back-btn:hover {
    transform: translateX(-5px);
    background: #f8fafc;
}

.der-file-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 16px;
    border: 1px solid #f1f5f9;
    background: #fafbfc;
    margin-bottom: 10px;
    transition: 0.2s;
}

.der-file-row:hover { background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
</style>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>
            <div class="dashboard-body__content p-4">
                <?php $this->view('set_flash'); ?>

                <?php if ($post): ?>

                    <!-- HEADER WITH BACK BUTTON + ACTIONS (same as admin_project_detail) -->
                    <div class="card common-card mb-4 border-0" style="background: transparent; box-shadow: none;">
                        <div class="card-body p-0 d-flex flex-wrap justify-content-between align-items-center gap-4">
                            <div class="d-flex align-items-center gap-3">
                                <a href="<?= htmlspecialchars($returnUrl) ?>" class="header-back-btn">⬅️</a>
                                <div>
                                    <h3 class="mb-0 fw-800 text-primary"><?= htmlspecialchars((string) ($post->titre ?? 'Publication DER')) ?></h3>
                                    <p class="text-muted small mb-0">par <strong><?= htmlspecialchars((string) ($post->author_name ?? 'Responsable DER')) ?></strong></p>
                                </div>
                            </div>
                            <div class="d-flex gap-3 flex-wrap">
                                <a href="<?= ROOT ?>/Homes/der_espace?edit=<?= (int) ($post->id ?? 0) ?>#manage-der-posts" class="btn btn-warning rounded-pill px-4 shadow-sm">✏️ Modifier</a>
                                <form method="POST" action="<?= ROOT ?>/Homes/der_espace" class="d-inline" onsubmit="return confirm('Archiver cette publication ?');">
                                    <input type="hidden" name="post_id" value="<?= (int) ($post->id ?? 0) ?>">
                                    <button type="submit" name="delete_der_post" class="btn btn-danger rounded-pill px-4 shadow-sm">🗄️ Archiver</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- QUICK METRICS ROW (same style as admin_project_detail) -->
                    <div class="row gy-4 mb-4">
                        <div class="col-xl-3 col-md-4 col-6">
                            <div class="card common-card h-100 text-center py-3 border-top border-5 border-primary">
                                <div class="card-body p-1">
                                    <p class="small text-muted mb-1">TYPE</p>
                                    <h5 class="mb-0" style="font-size:1rem;">🏷️ <?= htmlspecialchars(ucfirst($postType)) ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-6">
                            <div class="card common-card h-100 text-center py-3 border-top border-5 border-success">
                                <div class="card-body p-1">
                                    <p class="small text-muted mb-1">FICHIERS</p>
                                    <h4 class="mb-0">📎 <?= $fileCount ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-6">
                            <div class="card common-card h-100 text-center py-3 border-top border-5 <?= $isArchived ? 'border-secondary' : 'border-success' ?>">
                                <div class="card-body p-1">
                                    <p class="small text-muted mb-1">STATUT</p>
                                    <h5 class="mb-0" style="font-size:1rem;"><?= $isArchived ? '🗄️ Archivée' : '✅ Active' ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-6">
                            <div class="card common-card h-100 text-center py-3 border-top border-5 border-warning">
                                <div class="card-body p-1">
                                    <p class="small text-muted mb-1">DATE PUBLICATION</p>
                                    <h5 class="mb-0" style="font-size:.9rem;">📅 <?= htmlspecialchars((string) ($post->publication_date ?? '-')) ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MAIN CONTENT + SIDEBAR (same 8/4 split as admin_project_detail) -->
                    <div class="row gy-4">

                        <!-- MAIN COLUMN (8/12) -->
                        <div class="col-xl-8">
                            <div class="card common-card mb-4">
                                <div class="card-body">
                                    <h5>📝 Contenu de la publication</h5>
                                    <div class="text-muted" style="line-height: 2; font-size: 1.05rem;">
                                        <?= nl2br(htmlspecialchars((string) ($post->contenu ?? 'Aucun contenu fourni.'))) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="card common-card">
                                <div class="card-body">
                                    <h5>📄 Fichiers joints</h5>
                                    <?php if (!empty($post->files ?? [])): ?>
                                        <?php foreach (($post->files ?? []) as $file): ?>
                                            <?php $relativePath = ltrim(str_replace('\\', '/', (string) ($file->file_path ?? '')), '/'); ?>
                                            <div class="der-file-row">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span style="font-size:1.5rem;">📄</span>
                                                    <div>
                                                        <div class="fw-700 text-dark"><?= htmlspecialchars((string) ($file->original_name ?? 'Fichier')) ?></div>
                                                        <small class="text-muted"><?= htmlspecialchars((string) ($file->file_type ?? '')) ?></small>
                                                    </div>
                                                </div>
                                                <a href="<?= ROOT . '/' . $relativePath ?>" class="btn btn-primary btn-sm rounded-pill px-4" target="_blank" rel="noopener">Ouvrir 🔗</a>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-center py-4 bg-light rounded-4">
                                            <p class="text-muted mb-0">Aucun fichier joint à cette publication.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- SIDEBAR (4/12) -->
                        <div class="col-xl-4">
                            <div class="card common-card">
                                <div class="card-body">
                                    <h5>ℹ️ Informations</h5>

                                    <div class="detail-item">
                                        <div class="detail-icon">🏷️</div>
                                        <div>
                                            <div class="small text-muted">Type</div>
                                            <span class="badge" style="background:#ede9fe;color:#5b21b6;"><?= htmlspecialchars(ucfirst($postType)) ?></span>
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-icon">🔖</div>
                                        <div>
                                            <div class="small text-muted">Statut</div>
                                            <span class="badge <?= $isArchived ? 'bg-secondary' : 'bg-success' ?>">
                                                <?= $isArchived ? 'Archivée' : 'Active' ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-icon">👤</div>
                                        <div>
                                            <div class="small text-muted">Auteur</div>
                                            <strong><?= htmlspecialchars((string) ($post->author_name ?? 'Responsable DER')) ?></strong>
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-icon">📅</div>
                                        <div>
                                            <div class="small text-muted">Date de publication</div>
                                            <strong><?= htmlspecialchars((string) ($post->publication_date ?? '-')) ?></strong>
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-icon">🕐</div>
                                        <div>
                                            <div class="small text-muted">Créé le</div>
                                            <strong><?= !empty($post->created_at) ? htmlspecialchars(date('d F Y à H:i', strtotime((string) $post->created_at))) : '-' ?></strong>
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-icon">📎</div>
                                        <div>
                                            <div class="small text-muted">Fichiers joints</div>
                                            <strong><?= $fileCount ?> fichier<?= $fileCount > 1 ? 's' : '' ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions Card -->
                            <div class="card common-card">
                                <div class="card-body">
                                    <h5>⚡ Actions rapides</h5>
                                    <div class="d-flex flex-column gap-2">
                                        <a href="<?= ROOT ?>/Homes/der_espace?edit=<?= (int) ($post->id ?? 0) ?>#manage-der-posts" class="btn btn-warning text-start">
                                            ✏️ Modifier cette publication
                                        </a>
                                        <a href="<?= ROOT ?>/Homes/der_espace#create-der-post" class="btn btn-success text-start">
                                            ➕ Nouvelle publication
                                        </a>
                                        <a href="<?= htmlspecialchars($returnUrl) ?>" class="btn btn-secondary text-start">
                                            ⬅️ Retour à l'espace DER
                                        </a>
                                        <a href="<?= ROOT ?>/Homes/der_dashboard" class="btn btn-primary text-start">
                                            🏠 Dashboard DER
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="card common-card text-center p-5">
                        <p class="text-muted">Publication introuvable.</p>
                        <a href="<?= htmlspecialchars($returnUrl) ?>" class="btn btn-primary rounded-pill px-4">Retour</a>
                    </div>
                <?php endif; ?>

            </div>
            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>

<?php $this->view('Partials/scripts'); ?>
</body>
</html>
