<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Connexion']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php $this->view('Partials/header'); ?>
<?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

<main class="change-gradient">
    <section class="account d-flex">
        <img src="<?= ROOT ?>/assets/images/thumbs/account-img.png" alt="" class="account__img">
        <div class="account__right padding-y-120 flx-align">
            <div class="account-content">
                <a href="<?= ROOT ?>/Homes/index" class="logo mb-64"><img src="<?= ROOT ?>/assets/images/logo/logo.png" alt=""></a>
                <h4 class="account-content__title mb-48 text-capitalize">Connexion</h4>
                <form action="<?= ROOT ?>/Homes/login" method="post" data-dynamic-block="auth-login-form">
                    <div class="row gy-4">
                        <div class="col-12">
                            <label for="email" class="form-label mb-2 font-18 font-heading fw-600">Email</label>
                            <input type="email" class="common-input common-input--bg" id="email" name="email" placeholder="email@domaine.com" required>
                        </div>
                        <div class="col-12">
                            <label for="password" class="form-label mb-2 font-18 font-heading fw-600">Mot de passe</label>
                            <input type="password" class="common-input common-input--bg" id="password" name="password" placeholder="Votre mot de passe" required>
                        </div>
                        <div class="col-12 d-flex justify-content-between flex-wrap gap-2">
                            <a href="<?= ROOT ?>/Homes/register" class="text-main text-decoration-underline">Créer un compte</a>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-main btn-lg w-100 pill">Se connecter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <?php $this->view('Partials/footer'); ?>
</main>

<?php $this->view('Partials/scripts'); ?>
</body>
</html>
