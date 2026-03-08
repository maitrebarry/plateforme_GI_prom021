<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Connexion']); ?>

<body>
    <?php $this->view('Partials/global-shell'); ?>
    <?php $this->view('Partials/mobile-menu'); ?>
    <?php $this->view('Partials/header'); ?>
    <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

    <main class="change-gradient">
        <section class="account d-flex">
            <img src="assets/images/thumbs/account-img.png" alt="" class="account__img">
            <div class="account__left d-md-flex d-none flx-align section-bg position-relative z-index-1 overflow-hidden">
                <img src="<?= ROOT ?>/assets/images/shapes/pattern-curve-seven.png" alt="" class="position-absolute end-0 top-0 z-index--1 h-100">
                <div class="account-thumb">
                    <img src="/<?= ROOT ?>/assets/images/thumbs/banner-img.png" alt="">
                    <div class="statistics animation bg-main text-center">
                        <h5 class="statistics__amount text-white">50k</h5>
                        <span class="statistics__text text-white font-14">Customers</span>
                    </div>
                </div>
            </div>
            <div class="account__right padding-y-120 flx-align">
                <div class="account-content">
                    <a href="index.html" class="logo mb-64">
                        <img src="<?= ROOT ?>/assets/images/logo/logo.png" alt="">
                    </a>
                    <h4 class="account-content__title mb-48 text-capitalize">Bienvenue</h4>

                    <form action="<?= ROOT ?>/Logins/index" method="POST">
                        <?php $this->view("set_flash"); ?>
                        <div class="row gy-4">

                            <div class="col-12">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Email</label>

                                <div class="position-relative">
                                    <input type="email" name="email" class="common-input common-input--bg common-input--withIcon" placeholder="infoname@mail.com" required>

                                    <span class="input-icon"> <img src="<?= ROOT ?>/assets/images/icons/envelope-icon.svg" alt=""></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Mot de passe</label>
                                <div class="position-relative">
                                    <input type="password" name="password" class="common-input common-input--bg common-input--withIcon" placeholder="Mot de passe" required>
                                    <span class="input-icon"> <img src="<?= ROOT ?>/assets/images/icons/lock-icon.svg" alt=""></span>
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="flx-between gap-1">
                                    <a href="#" class="forgot-password text-decoration-underline text-main text-poppins font-14">
                                        Mot de passe oublié
                                    </a>
                                </div>
                            </div>


                            <div class="col-12">
                                <button type="submit" name="submit" class="btn btn-main btn-lg w-100 pill">
                                    Se connecter
                                </button>
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