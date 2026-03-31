<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Detail projet administrateur']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$metrics = $metrics ?? [];
$images = $images ?? [];
$files = $files ?? [];
$reviews = $reviews ?? [];
$avgRating = (float) ($reviewSummary->average_rating ?? 0);
$totalReviews = (int) ($reviewSummary->total_reviews ?? 0);
$adminStatus = (string) ($project->statut_admin ?? 'en_attente');
$statusBadgeClass = 'bg-warning text-dark';
if ($adminStatus === 'valide') {
    $statusBadgeClass = 'bg-success';
} elseif ($adminStatus === 'rejete') {
    $statusBadgeClass = 'bg-danger';
}
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
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #f8fafc;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-icon {
    width: 32px;
    height: 32px;
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

.btn-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; }
.btn-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none; color: white; }
.btn-danger { background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); border: none; }

.indicator-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f8fafc;
}

.review-item {
    background: #f8fafc;
    border-radius: 16px;
    padding: 1.25rem;
    border: 1px solid #f1f5f9;
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
</style>
<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-4">
                <?php $this->view('set_flash'); ?>

                <!-- HEADER WITH ACTIONS -->
                <div class="card common-card mb-4 border-0" style="background: transparent; box-shadow: none;">
                    <div class="card-body p-0 d-flex flex-wrap justify-content-between align-items-center gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <a href="<?= ROOT ?>/Admins/dashboard" class="header-back-btn">
                                ⬅️
                            </a>
                            <div>
                                <h3 class="mb-0 fw-800 text-primary"><?= htmlspecialchars($project->title ?? 'Détails du Projet') ?></h3>
                                <p class="text-muted small mb-0">par <strong><?= htmlspecialchars(trim((string) (($project->nom ?? '') . ' ' . ($project->prenom ?? ''))) ?: 'Auteur inconnu') ?></strong></p>
                            </div>
                        </div>
                        <div class="d-flex gap-3 flex-wrap">
                            <form method="POST" action="<?= ROOT ?>/Admins/project_detail/<?= (int) ($project->id ?? 0) ?>" class="d-inline">
                                <button class="btn btn-success rounded-pill px-4 shadow-sm" type="submit" name="validate_project">✅ Valider</button>
                            </form>
                            <form method="POST" action="<?= ROOT ?>/Admins/project_detail/<?= (int) ($project->id ?? 0) ?>" class="d-inline">
                                <button class="btn btn-warning rounded-pill px-4 shadow-sm" type="submit" name="set_pending_project">⌛ Attente</button>
                            </form>
                            <form method="POST" action="<?= ROOT ?>/Admins/project_detail/<?= (int) ($project->id ?? 0) ?>" class="d-inline" onsubmit="return confirm('Rejeter ce projet ?');">
                                <button class="btn btn-danger rounded-pill px-4 shadow-sm" type="submit" name="reject_project">❌ Rejeter</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- QUICK METRICS ROW -->
                <div class="row gy-4 mb-4">
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="card common-card h-100 text-center py-3 border-top border-5 border-primary"><div class="card-body p-1"><p class="small text-muted mb-1">LIKES</p><h4 class="mb-0">❤️ <?= (int) ($metrics['likes'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="card common-card h-100 text-center py-3 border-top border-5 border-success"><div class="card-body p-1"><p class="small text-muted mb-1">AVIS</p><h4 class="mb-0">💬 <?= (int) ($metrics['reviews'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="card common-card h-100 text-center py-3 border-top border-5 border-secondary"><div class="card-body p-1"><p class="small text-muted mb-1">MSGS</p><h4 class="mb-0">📩 <?= (int) ($metrics['messages'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="card common-card h-100 text-center py-3 border-top border-5 border-warning"><div class="card-body p-1"><p class="small text-muted mb-1">NOTE</p><h4 class="mb-0">⭐ <?= number_format($avgRating, 1) ?></h4></div></div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="card common-card h-100 text-center py-3 border-top border-5 border-info"><div class="card-body p-1"><p class="small text-muted mb-1">IMG</p><h4 class="mb-0">🖼️ <?= (int) ($metrics['images'] ?? 0) ?></h4></div></div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="card common-card h-100 text-center py-3 border-top border-5 border-dark"><div class="card-body p-1"><p class="small text-muted mb-1">DOCS</p><h4 class="mb-0">📄 <?= (int) ($metrics['files'] ?? 0) ?></h4></div></div>
                    </div>
                </div>

                <div class="row gy-4">
                    <!-- MAIN COLUMN -->
                    <div class="col-xl-8">
                        <div class="card common-card mb-4 min-vh-25">
                            <div class="card-body">
                                <h5>📝 Description du Projet</h5>
                                <div class="text-muted" style="line-height:2; font-size: 1.05rem;">
                                    <?= nl2br((string) ($project->description ?? 'Aucune description fournie.')) ?>
                                </div>
                            </div>
                        </div>

                        <div class="card common-card mb-4">
                            <div class="card-body">
                                <h5>💻 Technologies & Vidéo</h5>
                                <div class="detail-item">
                                    <div class="detail-icon">⚙️</div>
                                    <div><strong>Technologies:</strong> <?= htmlspecialchars((string) ($project->technologies ?? 'Non précisées')) ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-icon">🎬</div>
                                    <div><strong>Lien Vidéo:</strong> 
                                        <?php if (!empty($project->video)): ?>
                                            <a href="<?= htmlspecialchars((string) $project->video) ?>" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-primary fw-bold"><?= htmlspecialchars((string) $project->video) ?> 🔗</a>
                                        <?php else: ?>
                                            <span class="text-muted">Aucune vidéo</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card common-card mb-4">
                            <div class="card-body">
                                <h5>🖼️ Galerie d'Images</h5>
                                <?php if (!empty($images)): ?>
                                    <div class="row g-3">
                                        <?php foreach ($images as $image): ?>
                                            <div class="col-md-4">
                                                <img src="<?= ROOT_IMG ?>/uploads/projects/images/<?= rawurlencode((string) ($image->image ?? '')) ?>" alt="Project Image" class="img-fluid shadow-sm hover-lift" style="height:200px; width:100%; object-fit:cover; border-radius:18px;">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4 bg-light rounded-4 border border-dashed"><p class="text-muted mb-0">Aucune image disponible.</p></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card common-card">
                            <div class="card-body">
                                <h5>📄 Documents Joints</h5>
                                <?php if (!empty($files)): ?>
                                    <div class="d-flex flex-column gap-2">
                                        <?php foreach ($files as $file): ?>
                                            <a href="<?= ROOT_IMG ?>/uploads/projects/files/<?= rawurlencode((string) ($file->fichier ?? '')) ?>" target="_blank" class="btn btn-light text-start d-flex align-items-center gap-3 rounded-4 p-3 border-0 transition-2">
                                                <span class="fs-4">📄</span>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold text-dark"><?= htmlspecialchars((string) ($file->fichier ?? 'Document')) ?></div>
                                                    <small class="text-muted">Cliquez pour consulter</small>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted mb-0">Aucun fichier disponible.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- SIDEBAR -->
                    <div class="col-xl-4">
                        <div class="card common-card">
                            <div class="card-body">
                                <h5>ℹ️ Meta Informations</h5>
                                <div class="detail-item">
                                    <div class="detail-icon">🏷️</div>
                                    <div>
                                        <div class="small text-muted">Statut Admin</div>
                                        <span class="badge <?= $statusBadgeClass ?> bg-opacity-10 text-<?= str_replace('bg-', '', explode(' ', $statusBadgeClass)[0]) ?> px-3"><?= htmlspecialchars($adminStatus) ?></span>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-icon">🎓</div>
                                    <div>
                                        <div class="small text-muted">Université / Filière</div>
                                        <strong><?= htmlspecialchars((string) ($project->universite ?? '')) ?></strong><br>
                                        <span class="text-muted small"><?= htmlspecialchars((string) ($project->filiere ?? 'N/A')) ?></span>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-icon">✉️</div>
                                    <div>
                                        <div class="small text-muted">Email de l'auteur</div>
                                        <a href="mailto:<?= htmlspecialchars((string) ($project->email ?? '')) ?>" class="text-decoration-none"><?= htmlspecialchars((string) ($project->email ?? '')) ?></a>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-icon">📅</div>
                                    <div>
                                        <div class="small text-muted">Publié le</div>
                                        <strong><?= !empty($project->created_at) ? htmlspecialchars(date('d F Y à H:i', strtotime((string) $project->created_at))) : '-' ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card common-card">
                            <div class="card-body">
                                <h5>⭐ Avis des Visiteurs</h5>
                                <div class="d-flex align-items-center justify-content-between mb-4 bg-light p-3 rounded-4">
                                    <div class="text-center">
                                        <div class="h2 fw-800 mb-0"><?= number_format($avgRating, 1) ?></div>
                                        <div class="small text-muted">sur 5</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-warning h5 mb-0"><?= str_repeat('★', floor($avgRating)) . (fmod($avgRating, 1) >= 0.5 ? '½' : '') ?></div>
                                        <div class="small text-muted"><?= $totalReviews ?> avis au total</div>
                                    </div>
                                </div>
                                
                                <div class="d-flex flex-column gap-3">
                                    <?php if (!empty($reviews)): ?>
                                        <?php foreach (array_slice($reviews, 0, 5) as $review): ?>
                                            <div class="review-item">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <strong class="small"><?= htmlspecialchars(trim((string) (($review->nom ?? '') . ' ' . ($review->prenom ?? ''))) ?: 'Visiteur') ?></strong>
                                                    <div class="text-warning small"><?= str_repeat('★', max(0, (int) ($review->rating ?? 0))) ?></div>
                                                </div>
                                                <p class="mb-0 text-muted small" style="line-height: 1.6"><?= htmlspecialchars((string) ($review->review ?? '')) ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-muted text-center py-3">Aucun avis pour le moment.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
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
