<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Detail publication DER']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php $post = $post ?? null; ?>
<?php $returnUrl = $returnUrl ?? (ROOT . '/Homes/der_espace'); ?>
<style>
.der-detail-card,
.der-detail-card .card-body,
.der-detail-card h3,
.der-detail-card h4,
.der-detail-card h5,
.der-detail-card p,
.der-detail-card span {
    color: #0f172a;
}

.der-detail-hero {
    padding: 24px 28px;
    border-radius: 28px;
    background: linear-gradient(135deg, #ffffff, #eef4ff);
    border: 1px solid #dbe4ee;
}

.der-detail-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    color: #64748b;
    margin-top: 12px;
}

.der-detail-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 999px;
    background: #dbeafe;
    color: #1d4ed8;
    font-weight: 700;
    font-size: .82rem;
}

.der-detail-file {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 18px;
    border: 1px solid #dbe4ee;
    background: #ffffff;
    margin-bottom: 12px;
}
</style>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-4 der-detail-card">
                <?php $this->view('set_flash'); ?>

                <?php if ($post): ?>
                    <div class="der-detail-hero mb-4">
                        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                            <div>
                                <div class="d-flex gap-2 flex-wrap mb-2">
                                    <span class="der-detail-badge"><?= htmlspecialchars((string) ($post->type ?? 'publication')) ?></span>
                                    <?php if ((int) ($post->is_archived ?? 0) === 1): ?>
                                        <span class="badge bg-secondary">Archivee</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php endif; ?>
                                </div>
                                <h3 class="mb-2"><?= htmlspecialchars((string) ($post->titre ?? 'Publication DER')) ?></h3>
                                <div class="der-detail-meta">
                                    <span>Date de publication: <?= htmlspecialchars((string) ($post->publication_date ?? '')) ?></span>
                                    <span>Auteur: <?= htmlspecialchars((string) ($post->author_name ?? 'Responsable DER')) ?></span>
                                    <span>Cree le: <?= htmlspecialchars(!empty($post->created_at) ? date('Y-m-d', strtotime((string) $post->created_at)) : '') ?></span>
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="<?= htmlspecialchars($returnUrl) ?>" class="btn btn-outline-secondary">Retour a l espace DER</a>
                            </div>
                        </div>
                    </div>

                    <div class="card common-card mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">Contenu complet</h5>
                            <div class="border rounded-4 p-3 bg-light text-dark" style="line-height:1.8">
                                <?= nl2br(htmlspecialchars((string) ($post->contenu ?? ''))) ?>
                            </div>
                        </div>
                    </div>

                    <div class="card common-card">
                        <div class="card-body">
                            <h5 class="mb-3">Fichiers joints</h5>
                            <?php if (!empty($post->files ?? [])): ?>
                                <?php foreach (($post->files ?? []) as $file): ?>
                                    <?php $relativePath = ltrim(str_replace('\\', '/', (string) ($file->file_path ?? '')), '/'); ?>
                                    <div class="der-detail-file">
                                        <div>
                                            <strong><?= htmlspecialchars((string) ($file->original_name ?? 'Fichier')) ?></strong>
                                            <div class="text-muted small">Type: <?= htmlspecialchars((string) ($file->file_type ?? '')) ?></div>
                                        </div>
                                        <a href="<?= ROOT . '/' . $relativePath ?>" class="btn btn-outline-primary btn-sm" target="_blank" rel="noopener">Ouvrir</a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-muted">Aucun fichier joint sur cette publication.</div>
                            <?php endif; ?>
                        </div>
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
