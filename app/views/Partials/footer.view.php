<?php /* Styles du footer : voir public/assets/css/design-system.css (.modern-footer) */ ?>
<footer class="modern-footer">
    <div class="container container-two">
        <div class="row gy-5 justify-content-between">
            <div class="col-xl-4 col-lg-5 col-sm-12">
                <div class="footer-item">
                    <div class="footer-item__logo">
                        <a href="<?= ROOT ?>/Homes/index">
                            <!-- Make sure logo path is correct, use fallback if needed -->
                            <img src="<?= ROOT ?>/assets/images/logo/n'kakodon.png" alt="Logo NGAKODON" onerror="this.src='<?= ROOT ?>/assets/images/logo/logo.png'">
                        </a>
                    </div>
                    <p class="footer-item__desc">
                        NGAKODON valorise les projets étudiants, facilite leur découverte et rapproche visiteurs, porteurs de projets et le département dans un écosystème numérique moderne.
                    </p>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="footer-item">
                    <h5 class="footer-item__title">Navigation</h5>
                    <ul class="footer-menu">
                        <li class="footer-menu__item"><a href="<?= ROOT ?>/Homes/index" class="footer-menu__link">Accueil</a></li>
                        <li class="footer-menu__item"><a href="<?= ROOT ?>/Homes/projects" class="footer-menu__link">Catalogue Projets</a></li>
                        <li class="footer-menu__item"><a href="<?= ROOT ?>/Homes/departement" class="footer-menu__link">Département</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="footer-item">
                    <h5 class="footer-item__title">Mon Espace</h5>
                    <ul class="footer-menu">
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <li class="footer-menu__item"><a href="<?= ROOT ?>/Homes/login" class="footer-menu__link">Connexion sécurisée</a></li>
                            <li class="footer-menu__item"><a href="<?= ROOT ?>/Homes/register" class="footer-menu__link">Créer un compte</a></li>
                        <?php else: ?>
                            <li class="footer-menu__item"><a href="<?= ROOT ?>/Homes/dashboard" class="footer-menu__link">Tableau de bord</a></li>
                            <li class="footer-menu__item"><a href="<?= ROOT ?>/Profiles/appercu" class="footer-menu__link">Mon Profil</a></li>
                            <li class="footer-menu__item"><a href="<?= ROOT ?>/Logins/logout" data-logout class="footer-menu__link">Se déconnecter</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modern-bottom-footer">
        <div class="container container-two">
            <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
                <p class="modern-bottom-footer__text" style="width:auto;margin:0;">Copyright © <?= date('Y') ?>. <span>NGAKODON</span> — GI Promo 2021. Tous droits réservés.</p>
                <button type="button" class="footer-theme-toggle" data-theme-toggle title="Basculer mode clair / sombre" aria-label="Basculer entre mode clair et sombre">
                    <i class="bx bx-moon"></i> <span data-theme-label>Mode sombre</span>
                </button>
                <span class="ds-color-swatches" title="Couleur du thème">
                    <button type="button" class="ds-color-swatch" data-color-value="green" style="--sw:#157f5a" title="Vert" aria-label="Vert"></button>
                    <button type="button" class="ds-color-swatch" data-color-value="blue" style="--sw:#1d59b8" title="Bleu" aria-label="Bleu"></button>
                    <button type="button" class="ds-color-swatch" data-color-value="orange" style="--sw:#cf5410" title="Orange" aria-label="Orange"></button>
                    <button type="button" class="ds-color-swatch" data-color-value="violet" style="--sw:#6236c4" title="Violet" aria-label="Violet"></button>
                </span>
            </div>
        </div>
    </div>
</footer>

<?php $this->view('Partials/nkadon-chatbot'); ?>
<?php $this->view('Partials/mobile-bottom-nav'); ?>
