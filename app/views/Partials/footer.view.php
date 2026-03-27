<footer class="footer">
    <img src="<?= ROOT ?>/assets/images/shapes/pattern.png" alt="" class="bg-pattern">
    <div class="container container-two">
        <div class="row gy-5">
            <div class="col-xl-4 col-sm-6">
                <div class="footer-item">
                    <div class="footer-item__logo">
                        <a href="<?= ROOT ?>/Homes/index"><img src="<?= ROOT ?>/assets/images/logo/white-n'kakodon.png" alt=""></a>
                    </div>
                    <p class="footer-item__desc">Plateforme GI Promo 21 - structure découpée à partir de dpmarkethtml-10.</p>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6">
                <div class="footer-item">
                    <h5 class="footer-item__title">Navigation</h5>
                    <ul class="footer-menu">
                        <li class="footer-menu__item"><a href="<?= ROOT ?>/Homes/index" class="footer-menu__link">Accueil</a></li>
                        <li class="footer-menu__item"><a href="<?= ROOT ?>/Homes/projects" class="footer-menu__link">Projets</a></li>
                        <li class="footer-menu__item"><a href="<?= ROOT ?>/Homes/departement" class="footer-menu__link">Espace Département</a></li>
                        <li class="footer-menu__item"><a href="<?= ROOT ?>/Homes/profile" class="footer-menu__link">Profil</a></li>
                        <?php if (!isset($_SESSION['user_id'])): ?>
                        <li class="footer-menu__item"><a href="<?= ROOT ?>/Homes/login" class="footer-menu__link">Connexion</a></li>
                        <li class="footer-menu__item"><a href="<?= ROOT ?>/Homes/register" class="footer-menu__link">Inscription</a></li>
                        <?php else: ?>
                        <li class="footer-menu__item"><a href="<?= ROOT ?>/Homes/dashboard" class="footer-menu__link">Espace</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<div class="bottom-footer">
    <div class="container container-two">
        <div class="bottom-footer__inner flx-between gap-3">
            <p class="bottom-footer__text font-14">Copyright © 2026, Plateforme GI Promo 21.</p>
        </div>
    </div>
</div>
