<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Dashboard DER']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$derStats = $derStats ?? [];
$latestPublications = $latestPublications ?? [];
?>
<style>
.der-card,
.der-card .card-body,
.der-card h3,
.der-card h4,
.der-card h5,
.der-card p,
.der-card span {
    color: #0f172a;
}

.der-hero {
    padding: 24px 28px;
    border-radius: 28px;
    background: linear-gradient(135deg, #ffffff, #eef4ff);
    border: 1px solid #dbe4ee;
}

.der-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
}

.der-stat {
    padding: 18px 20px;
    border-radius: 22px;
    background: #ffffff;
    border: 1px solid #dbe4ee;
    box-shadow: 0 14px 32px rgba(15, 23, 42, 0.05);
}

.der-stat strong {
    display: block;
    margin-top: 8px;
    font-size: 1.9rem;
    line-height: 1.1;
}

.der-publication {
    padding: 18px 20px;
    border-radius: 22px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    margin-bottom: 14px;
}

.der-publication-meta {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    color: #64748b;
    font-size: .92rem;
    margin-bottom: 10px;
}

.der-file-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 999px;
    border: 1px solid #cbd5e1;
    color: #0f172a;
    text-decoration: none;
    margin-right: 8px;
    margin-bottom: 8px;
}

.der-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 999px;
    background: #dbeafe;
    color: #1d4ed8;
    font-weight: 700;
    font-size: .82rem;
}

.der-publication-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 14px;
}
</style>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-4 der-card">
                <?php $this->view('set_flash'); ?>

                <div class="der-hero mb-4">
                        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                            <div>
                                <h3 class="mb-1">Dashboard DER</h3>
                                <p class="mb-0 text-muted">Suivi dynamique des publications, annonces et contenus du departement.</p>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="<?= ROOT ?>/Homes/der_espace#create-der-post" class="btn btn-primary">Ajouter une publication</a>
                                <a href="<?= ROOT ?>/Homes/der_espace" class="btn btn-primary">Gerer les publications</a>
                                <a href="<?= ROOT ?>/Homes/index" class="btn btn-outline-secondary">Voir le site</a>
                            </div>
                        </div>
                    </div>

                <div class="der-stats-grid mb-4">
                    <div class="der-stat"><span>Total publications</span><strong><?= (int) ($derStats['total'] ?? 0) ?></strong></div>
                    <div class="der-stat"><span>Annonces</span><strong><?= (int) ($derStats['annonces'] ?? 0) ?></strong></div>
                    <div class="der-stat"><span>Informations</span><strong><?= (int) ($derStats['informations'] ?? 0) ?></strong></div>
                    <div class="der-stat"><span>Evenements</span><strong><?= (int) ($derStats['evenements'] ?? 0) ?></strong></div>
                    <div class="der-stat"><span>Resultats</span><strong><?= (int) ($derStats['resultats'] ?? 0) ?></strong></div>
                    <div class="der-stat"><span>Opportunites</span><strong><?= (int) ($derStats['opportunites'] ?? 0) ?></strong></div>
                    <div class="der-stat"><span>Fichiers joints</span><strong><?= (int) ($derStats['files'] ?? 0) ?></strong></div>
                </div>

                <div class="card common-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-3">
                            <div>
                                <h5 class="mb-1">Dernieres publications DER</h5>
                                <p class="mb-0 text-muted">Les contenus les plus recents publies par le departement.</p>
                            </div>
                            <a href="<?= ROOT ?>/Homes/der_espace" class="btn btn-outline-primary">Tout gerer</a>
                        </div>

                        <?php if (!empty($latestPublications)): ?>
                            <?php foreach ($latestPublications as $post): ?>
                                <article class="der-publication">
                                    <div class="d-flex justify-content-between gap-3 flex-wrap mb-2">
                                        <h5 class="mb-0"><?= htmlspecialchars((string) ($post->titre ?? 'Publication')) ?></h5>
                                        <span class="der-badge"><?= htmlspecialchars((string) ($post->type ?? 'publication')) ?></span>
                                    </div>
                                    <div class="der-publication-meta">
                                        <span>Date: <?= htmlspecialchars((string) ($post->publication_date ?? '')) ?></span>
                                        <span>Auteur: <?= htmlspecialchars((string) ($post->author_name ?? 'Responsable DER')) ?></span>
                                    </div>
                                    <p class="mb-3"><?= nl2br(htmlspecialchars((string) ($post->contenu ?? ''))) ?></p>
                                    <?php if (!empty($post->files ?? [])): ?>
                                        <div>
                                            <?php foreach (($post->files ?? []) as $file): ?>
                                                <?php $relativePath = ltrim(str_replace('\\', '/', (string) ($file->file_path ?? '')), '/'); ?>
                                                <a class="der-file-link" href="<?= ROOT . '/' . $relativePath ?>" target="_blank" rel="noopener">
                                                    <i class='bx bx-paperclip'></i>
                                                    <?= htmlspecialchars((string) ($file->original_name ?? 'Fichier')) ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="der-publication-actions">
                                        <a href="<?= ROOT ?>/Homes/der_publication_detail/<?= (int) ($post->id ?? 0) ?>" class="btn btn-outline-secondary btn-sm">Voir detail</a>
                                        <a href="<?= ROOT ?>/Homes/der_espace?edit=<?= (int) ($post->id ?? 0) ?>#manage-der-posts" class="btn btn-success btn-sm">Modifier</a>
                                        <form method="POST" action="<?= ROOT ?>/Homes/der_espace" class="d-inline">
                                            <input type="hidden" name="post_id" value="<?= (int) ($post->id ?? 0) ?>">
                                            <button type="submit" name="delete_der_post" class="btn btn-danger btn-sm" onclick="return confirm('Archiver cette publication DER ?');">Archiver</button>
                                        </form>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-5 text-muted">Aucune publication DER disponible pour le moment.</div>
                        <?php endif; ?>
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
