<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Projets']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php $this->view('Partials/header'); ?>
<?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

<main class="change-gradient">
    <section class="breadcrumb mb-0 bg-main-two position-relative z-index-1 overflow-hidden">
        <div class="container container-two">
            <div class="breadcrumb-two">
                <h1 class="breadcrumb-two__title text-white mb-2">Liste des projets</h1>
            </div>
        </div>
    </section>

    <section class="all-product padding-y-120" data-dynamic-block="projects-list">
        <div class="container container-two">
            <div class="row gy-4">
                <?php if (!empty($projects)): ?>
                    <?php foreach ($projects as $project): ?>
                        <div class="col-xl-4 col-lg-4 col-sm-6">
                            <div class="product-card">
                                <div class="product-card__thumb d-flex">
                                    <a href="<?= ROOT ?>/Homes/project/<?= (int) ($project['id'] ?? 0) ?>" class="link w-100">
                                        <img src="<?= htmlspecialchars($project['image'] ?? (ROOT . '/assets/images/thumbs/product-img1.png')) ?>" alt="" class="cover-img">
                                    </a>
                                </div>
                                <div class="product-card__content">
                                    <h6 class="product-card__title"><a href="<?= ROOT ?>/Homes/project/<?= (int) ($project['id'] ?? 0) ?>" class="link"><?= htmlspecialchars($project['title'] ?? 'Projet') ?></a></h6>
                                    <div class="product-card__info flx-between gap-2 mb-2">
                                        <span class="product-card__author">par <?= htmlspecialchars($project['author'] ?? '-') ?></span>
                                        <h6 class="product-card__price mb-0"><?= htmlspecialchars($project['price'] ?? '-') ?></h6>
                                    </div>
                                    <span class="product-card__sales font-14 mb-2"><?= htmlspecialchars($project['category'] ?? '-') ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info mb-0">Aucun projet disponible pour le moment. Cette section sera alimentée par la base de données.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php $this->view('Partials/footer'); ?>
</main>

<?php $this->view('Partials/scripts'); ?>
</body>
</html>
