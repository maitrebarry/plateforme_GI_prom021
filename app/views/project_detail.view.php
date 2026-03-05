<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Détail projet']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php $this->view('Partials/header'); ?>
<?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

<main class="change-gradient">
    <section class="breadcrumb mb-0 bg-main-two position-relative z-index-1 overflow-hidden">
        <div class="container container-two">
            <div class="breadcrumb-two">
                <h1 class="breadcrumb-two__title text-white mb-2">Détail projet</h1>
            </div>
        </div>
    </section>

    <section class="product-details padding-y-120" data-dynamic-block="project-detail">
        <div class="container container-two">
            <div class="row gy-4">
                <div class="col-lg-7">
                    <div class="product-card">
                        <div class="product-card__thumb d-flex">
                            <img src="<?= htmlspecialchars($project['image'] ?? '') ?>" class="cover-img" alt="">
                        </div>
                        <div class="product-card__content">
                            <h3><?= htmlspecialchars($project['title'] ?? 'Projet') ?></h3>
                            <p class="mb-3"><?= htmlspecialchars($project['description'] ?? 'Description indisponible (sera alimentée par le backend).') ?></p>
                            <p class="mb-1"><strong>Catégorie:</strong> <?= htmlspecialchars($project['category'] ?? '') ?></p>
                            <p class="mb-1"><strong>Auteur:</strong> <?= htmlspecialchars($project['author'] ?? '') ?></p>
                            <p class="mb-0"><strong>Accès:</strong> <?= htmlspecialchars($project['price'] ?? '') ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card common-card">
                        <div class="card-body">
                            <h5 class="mb-3">Actions</h5>
                            <a href="<?= ROOT ?>/Homes/projects" class="btn btn-outline-light pill w-100 mb-2">Retour à la liste</a>
                            <a href="<?= ROOT ?>/Homes/profile" class="btn btn-main pill w-100">Voir mon profil</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php $this->view('Partials/footer'); ?>
</main>

<?php $this->view('Partials/scripts'); ?>
</body>
</html>
