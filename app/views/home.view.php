<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Accueil']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php $this->view('Partials/header'); ?>
<?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

<main class="change-gradient">
    <section class="banner section-bg z-index-1">
        <div class="container container-two">
            <div class="row align-items-center gy-sm-5 gy-4">
                <div class="col-lg-6">
                    <div class="banner-content">
                        <h1 class="banner-content__title">Plateforme GI Promo 21</h1>
                        <p class="banner-content__desc font-18">Interface intégrée prête pour le backend.</p>
                        <div class="d-flex gap-2 flex-wrap mt-4">
                            <a href="<?= ROOT ?>/Homes/projects" class="btn btn-main pill">Voir les projets</a>
                            <a href="<?= ROOT ?>/Homes/register" class="btn btn-outline-light pill">Créer un compte</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="banner-thumb">
                        <img src="<?= ROOT ?>/assets/images/thumbs/banner-img.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="arrival-product padding-y-120 section-bg position-relative z-index-1" data-dynamic-block="featured-projects">
        <div class="container container-two">
            <div class="section-heading">
                <h3 class="section-heading__title">Projets en avant</h3>
            </div>
            <div class="row gy-4">
                <?php if (!empty($projects)): ?>
                    <?php foreach (array_slice($projects, 0, 3) as $project): ?>
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
                        <div class="alert alert-info mb-0">Aucun projet en avant pour le moment.</div>
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
