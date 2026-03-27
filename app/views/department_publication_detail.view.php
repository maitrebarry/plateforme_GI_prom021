<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Publication du departement']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php $this->view('Partials/header'); ?>
<?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>
<?php $post = $post ?? null; ?>
<?php $returnUrl = $returnUrl ?? (ROOT . '/Homes/departement'); ?>

<main class="change-gradient">
    <section class="breadcrumb mb-0 bg-main-two position-relative z-index-1 overflow-hidden">
        <div class="container container-two">
            <div class="breadcrumb-two">
                <h1 class="breadcrumb-two__title text-white mb-2">Publication du departement</h1>
                <p class="text-white mb-0">Consultez le detail complet d'une information officielle du DER.</p>
            </div>
        </div>
    </section>

    <section class="padding-y-120">
        <div class="container container-two">
            <div class="card common-card">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4">
                        <div>
                            <span class="badge bg-info text-dark mb-2"><?= htmlspecialchars((string) ucfirst((string) ($post->type ?? 'publication'))) ?></span>
                            <h3 class="mb-2"><?= htmlspecialchars((string) ($post->titre ?? 'Publication')) ?></h3>
                            <div class="text-muted">
                                <span class="me-3">Date: <?= htmlspecialchars((string) ($post->publication_date ?? '')) ?></span>
                                <span>Auteur: <?= htmlspecialchars((string) ($post->author_name ?? 'Responsable DER')) ?></span>
                            </div>
                        </div>
                        <a href="<?= htmlspecialchars($returnUrl) ?>" class="btn btn-outline-secondary">Retour au departement</a>
                    </div>

                    <div class="mb-4" style="color:#0f172a; line-height:1.8;">
                        <?= nl2br(htmlspecialchars((string) ($post->contenu ?? ''))) ?>
                    </div>

                    <?php if (!empty($post->files ?? [])): ?>
                        <div>
                            <h5 class="mb-3">Fichiers joints</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach (($post->files ?? []) as $file): ?>
                                    <?php $relativePath = ltrim(str_replace('\\', '/', (string) ($file->file_path ?? '')), '/'); ?>
                                    <a class="btn btn-light border" href="<?= ROOT . '/' . $relativePath ?>" target="_blank" rel="noopener">
                                        <?= htmlspecialchars((string) ($file->original_name ?? 'Fichier')) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php $this->view('Partials/footer'); ?>
</main>

<?php $this->view('Partials/scripts'); ?>
</body>
</html>
