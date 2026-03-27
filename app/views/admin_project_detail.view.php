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
<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-4">
                <?php $this->view('set_flash'); ?>

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div>
                        <h3 class="mb-1"><?= htmlspecialchars($project->title ?? 'Projet') ?></h3>
                        <p class="text-muted mb-0">Fiche detaillee pour validation administrateur.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?= ROOT ?>/Admins/pending_projects" class="btn btn-outline-secondary">Retour pending</a>
                        <a href="<?= ROOT ?>/Admins/projects_management" class="btn btn-outline-secondary">Tous les projets</a>
                    </div>
                </div>

                <div class="row gy-4">
                    <div class="col-xl-8">
                        <div class="card common-card mb-4">
                            <div class="card-body">
                                <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                                    <span class="badge <?= $statusBadgeClass ?>"><?= htmlspecialchars($adminStatus) ?></span>
                                    <span class="badge bg-light text-dark"><?= htmlspecialchars((string) ($project->status ?? 'en cours')) ?></span>
                                    <span class="badge bg-light text-dark"><?= htmlspecialchars((string) ($project->categorie ?? 'Sans categorie')) ?></span>
                                </div>
                                <p class="mb-3"><strong>Auteur:</strong> <?= htmlspecialchars(trim((string) (($project->nom ?? '') . ' ' . ($project->prenom ?? ''))) ?: 'N/A') ?></p>
                                <p class="mb-3"><strong>Email:</strong> <?= htmlspecialchars((string) ($project->email ?? '')) ?></p>
                                <p class="mb-3"><strong>Contact:</strong> <?= htmlspecialchars((string) ($project->contact ?? '')) ?></p>
                                <p class="mb-3"><strong>Universite:</strong> <?= htmlspecialchars((string) ($project->universite ?? '')) ?></p>
                                <p class="mb-3"><strong>Faculte:</strong> <?= htmlspecialchars((string) ($project->faculte ?? '')) ?></p>
                                <p class="mb-3"><strong>Filiere:</strong> <?= htmlspecialchars((string) ($project->filiere ?? '')) ?></p>
                                <p class="mb-0"><strong>Date de publication:</strong> <?= !empty($project->created_at) ? htmlspecialchars(date('Y-m-d H:i', strtotime((string) $project->created_at))) : '-' ?></p>
                            </div>
                        </div>

                        <div class="card common-card mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">Description</h5>
                                <div class="text-muted" style="line-height:1.8">
                                    <?= nl2br((string) ($project->description ?? 'Aucune description.')) ?>
                                </div>
                            </div>
                        </div>

                        <div class="card common-card mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">Technologies et video</h5>
                                <p><strong>Technologies:</strong> <?= htmlspecialchars((string) ($project->technologies ?? 'Non precisees')) ?></p>
                                <p class="mb-0"><strong>Video:</strong>
                                    <?php if (!empty($project->video)): ?>
                                        <a href="<?= htmlspecialchars((string) $project->video) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars((string) $project->video) ?></a>
                                    <?php else: ?>
                                        <span class="text-muted">Aucune video</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>

                        <div class="card common-card mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">Galerie</h5>
                                <?php if (!empty($images)): ?>
                                    <div class="row g-3">
                                        <?php foreach ($images as $image): ?>
                                            <div class="col-md-4">
                                                <img src="<?= ROOT_IMG ?>/uploads/projects/images/<?= rawurlencode((string) ($image->image ?? '')) ?>" alt="" style="width:100%;height:180px;object-fit:cover;border-radius:16px;">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted mb-0">Aucune image disponible.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card common-card">
                            <div class="card-body">
                                <h5 class="mb-3">Fichiers joints</h5>
                                <?php if (!empty($files)): ?>
                                    <ul class="mb-0">
                                        <?php foreach ($files as $file): ?>
                                            <li><a href="<?= ROOT_IMG ?>/uploads/projects/files/<?= rawurlencode((string) ($file->fichier ?? '')) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars((string) ($file->fichier ?? 'fichier')) ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted mb-0">Aucun fichier disponible.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="card common-card mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">Actions administrateur</h5>
                                <div class="d-grid gap-2">
                                    <form method="POST" action="<?= ROOT ?>/Admins/project_detail/<?= (int) ($project->id ?? 0) ?>">
                                        <button class="btn btn-success w-100" type="submit" name="validate_project">Valider le projet</button>
                                    </form>
                                    <form method="POST" action="<?= ROOT ?>/Admins/project_detail/<?= (int) ($project->id ?? 0) ?>">
                                        <button class="btn btn-warning w-100" type="submit" name="set_pending_project">Remettre en attente</button>
                                    </form>
                                    <form method="POST" action="<?= ROOT ?>/Admins/project_detail/<?= (int) ($project->id ?? 0) ?>" onsubmit="return confirm('Rejeter ce projet ?');">
                                        <button class="btn btn-danger w-100" type="submit" name="reject_project">Rejeter le projet</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card common-card mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">Indicateurs</h5>
                                <p class="mb-2"><strong>Likes:</strong> <?= (int) ($metrics['likes'] ?? 0) ?></p>
                                <p class="mb-2"><strong>Avis:</strong> <?= (int) ($metrics['reviews'] ?? 0) ?></p>
                                <p class="mb-2"><strong>Messages:</strong> <?= (int) ($metrics['messages'] ?? 0) ?></p>
                                <p class="mb-2"><strong>Images:</strong> <?= (int) ($metrics['images'] ?? 0) ?></p>
                                <p class="mb-0"><strong>Fichiers:</strong> <?= (int) ($metrics['files'] ?? 0) ?></p>
                            </div>
                        </div>

                        <div class="card common-card">
                            <div class="card-body">
                                <h5 class="mb-3">Avis visiteurs</h5>
                                <p class="mb-3"><strong>Note moyenne:</strong> <?= number_format($avgRating, 1) ?>/5</p>
                                <p class="mb-3"><strong>Total avis:</strong> <?= $totalReviews ?></p>
                                <?php if (!empty($reviews)): ?>
                                    <div class="d-flex flex-column gap-3">
                                        <?php foreach (array_slice($reviews, 0, 4) as $review): ?>
                                            <div class="border rounded-4 p-3 bg-light">
                                                <strong><?= htmlspecialchars(trim((string) (($review->nom ?? '') . ' ' . ($review->prenom ?? ''))) ?: 'Visiteur') ?></strong>
                                                <div class="text-warning mb-2"><?= str_repeat('★', max(0, (int) ($review->rating ?? 0))) ?></div>
                                                <p class="mb-0 text-muted"><?= htmlspecialchars((string) ($review->review ?? '')) ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted mb-0">Aucun avis pour le moment.</p>
                                <?php endif; ?>
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
