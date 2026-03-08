<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Espace Département']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php $this->view('Partials/header'); ?>
<?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

<main class="change-gradient">
    <section class="breadcrumb mb-0 bg-main-two position-relative z-index-1 overflow-hidden">
        <div class="container container-two">
            <div class="breadcrumb-two">
                <h1 class="breadcrumb-two__title text-white mb-2">Espace Département</h1>
                <p class="text-white mb-0">
                    <?= htmlspecialchars($department['name'] ?? 'Département') ?>
                </p>
            </div>
        </div>
    </section>

    <section class="padding-y-120" data-dynamic-block="department-space">
        <div class="container container-two">
            <div class="card common-card" data-dynamic-block="department-latest-posts">
                <div class="card-body">
                    <h5 class="mb-3">Aperçu des publications</h5>
                    <?php if (!empty($latestDepartmentPosts)): ?>
                        <div class="row gy-3">
                            <?php foreach ($latestDepartmentPosts as $item): ?>
                                <div class="col-lg-6">
                                    <div class="border rounded p-3 h-100">
                                        <strong><?= htmlspecialchars($item['title'] ?? '') ?></strong>
                                        <span class="text-muted d-block mb-2"><?= htmlspecialchars($item['date'] ?? '') ?> · <?= htmlspecialchars(ucfirst($item['type'] ?? 'publication')) ?></span>
                                        <div><?= nl2br(htmlspecialchars($item['content'] ?? '')) ?></div>
                                        <?php if (!empty($item['files'])): ?>
                                            <div class="mt-2">
                                                <small class="text-muted d-block mb-1">Documents à télécharger</small>
                                                <?php foreach ($item['files'] as $file): ?>
                                                    <a class="d-block text-primary" href="<?= htmlspecialchars($file['url'] ?? '#') ?>" download>
                                                        <?= htmlspecialchars($file['name'] ?? 'Fichier') ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">Aucune publication du DER n'est encore visible sur le site.</p>
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
