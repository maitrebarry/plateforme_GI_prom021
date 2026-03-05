<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Profil']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php $this->view('Partials/header'); ?>
<?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

<main class="change-gradient">
    <section class="breadcrumb mb-0 bg-main-two position-relative z-index-1 overflow-hidden">
        <div class="container container-two">
            <div class="breadcrumb-two">
                <h1 class="breadcrumb-two__title text-white mb-2">Profil utilisateur</h1>
            </div>
        </div>
    </section>

    <section class="profile py-5" data-dynamic-block="user-profile">
        <div class="container container-two">
            <div class="row gy-4">
                <div class="col-lg-4">
                    <div class="card common-card">
                        <div class="card-body text-center">
                            <img src="<?= ROOT ?>/assets/images/thumbs/author-img.png" alt="" style="max-width:120px; border-radius:50%;">
                            <h4 class="mt-3 mb-1"><?= htmlspecialchars($user['name'] ?? 'Utilisateur') ?></h4>
                            <p class="mb-0 text-body"><?= htmlspecialchars($user['role'] ?? '') ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card common-card">
                        <div class="card-body">
                            <h5 class="mb-3">Informations</h5>
                            <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? '-') ?></p>
                            <p><strong>Rôle:</strong> <?= htmlspecialchars($user['role'] ?? '-') ?></p>
                            <p><strong>Téléphone:</strong> <?= htmlspecialchars($user['phone'] ?? '-') ?></p>
                            <p><strong>Bio:</strong> <?= htmlspecialchars($user['bio'] ?? '-') ?></p>
                            <a href="<?= ROOT ?>/Homes/projects" class="btn btn-main pill">Mes projets</a>
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
