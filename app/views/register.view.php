<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Inscription']); ?>
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
                <h4 class="account-content__title mb-48 text-capitalize">Inscription</h4>
                <form action="<?= ROOT ?>/Homes/register" method="post" data-dynamic-block="auth-register-form">
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <label for="fullname" class="form-label mb-2 font-18 font-heading fw-600">Nom complet</label>
                            <input type="text" class="common-input common-input--bg" id="fullname" name="fullname" placeholder="Votre nom complet" required>
                        </div>
                        <div class="col-md-6">
                            <label for="role" class="form-label mb-2 font-18 font-heading fw-600">Rôle</label>
                            <select class="common-input common-input--bg" id="role" name="role" required>
                                <option>Étudiant</option>
                                <option>Encadreur</option>
                                <option>Administrateur</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="email_register" class="form-label mb-2 font-18 font-heading fw-600">Email</label>
                            <input type="email" class="common-input common-input--bg" id="email_register" name="email" placeholder="email@domaine.com" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password_register" class="form-label mb-2 font-18 font-heading fw-600">Mot de passe</label>
                            <input type="password" class="common-input common-input--bg" id="password_register" name="password" placeholder="Mot de passe" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirm" class="form-label mb-2 font-18 font-heading fw-600">Confirmation</label>
                            <input type="password" class="common-input common-input--bg" id="password_confirm" name="password_confirm" placeholder="Confirmer le mot de passe" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-main btn-lg w-100 pill">Créer le compte</button>
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
