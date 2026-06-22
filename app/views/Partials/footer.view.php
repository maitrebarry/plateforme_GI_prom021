<style>
    /* Premium Modern Footer Styles */
    .modern-footer {
        background-color: #0b221d; /* Very dark forest green */
        position: relative;
        overflow: hidden;
        color: #fffaf4;
        font-family: "Manrope", sans-serif;
        padding-top: 80px;
        z-index: 10;
        border-top: 1px solid rgba(189, 74, 31, 0.2);
    }

    /* Subtle background glow effect */
    .modern-footer::before {
        content: "";
        position: absolute;
        top: -150px;
        right: -100px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(189, 74, 31, 0.15) 0%, transparent 60%);
        border-radius: 50%;
        pointer-events: none;
    }

    .modern-footer::after {
        content: "";
        position: absolute;
        bottom: -200px;
        left: -150px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(18, 60, 52, 0.8) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: -1;
    }

    .modern-footer .container-two {
        position: relative;
        z-index: 2;
    }

    .modern-footer .footer-item__logo img {
        max-width: 180px;
        margin-bottom: 24px;
        filter: drop-shadow(0px 4px 10px rgba(0,0,0,0.3));
    }

    .modern-footer .footer-item__desc {
        color: rgba(255, 255, 255, 0.65);
        line-height: 1.8;
        font-size: 1rem;
        max-width: 350px;
    }

    .modern-footer .footer-item__title {
        color: #ffffff;
        font-family: "Sora", sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 28px;
        position: relative;
        display: inline-block;
    }

    .modern-footer .footer-item__title::after {
        content: "";
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 30px;
        height: 3px;
        background: #bd4a1f;
        border-radius: 2px;
        transition: width 0.3s ease;
    }

    .modern-footer .footer-item:hover .footer-item__title::after {
        width: 50px;
    }

    .modern-footer .footer-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .modern-footer .footer-menu__item {
        margin-bottom: 16px;
    }

    .modern-footer .footer-menu__link {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-size: 1.05rem;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: inline-flex;
        align-items: center;
        position: relative;
    }

    .modern-footer .footer-menu__link::before {
        content: '\2192'; /* Right arrow */
        font-family: monospace;
        margin-right: 0;
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.3s ease;
        color: #bd4a1f;
        display: inline-block;
        width: 0;
        overflow: hidden;
    }

    .modern-footer .footer-menu__link:hover {
        color: #ffffff;
        transform: translateX(5px);
    }

    .modern-footer .footer-menu__link:hover::before {
        opacity: 1;
        transform: translateX(0);
        width: 20px;
        margin-right: 4px;
    }

    /* Bottom footer bar */
    .modern-bottom-footer {
        background-color: #071714;
        padding: 24px 0;
        margin-top: 60px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .modern-bottom-footer__text {
        color: rgba(255, 255, 255, 0.5);
        margin: 0;
        font-size: 0.95rem;
        text-align: center;
        width: 100%;
    }

    .modern-bottom-footer__text span {
        color: #bd4a1f;
        font-weight: 600;
    }
</style>

<footer class="modern-footer">
    <div class="container container-two">
        <div class="row gy-5 justify-content-between">
            <div class="col-xl-4 col-lg-5 col-sm-12">
                <div class="footer-item">
                    <div class="footer-item__logo">
                        <a href="<?= ROOT ?>/Homes/index">
                            <!-- Make sure logo path is correct, use fallback if needed -->
                            <img src="<?= ROOT ?>/assets/images/logo/n'kakodon.png" alt="Logo Plateforme GI" onerror="this.src='<?= ROOT ?>/assets/images/logo/logo.png'">
                        </a>
                    </div>
                    <p class="footer-item__desc">
                        La Plateforme GI Promo 21 valorise les projets étudiants, facilite leur découverte et rapproche visiteurs, porteurs de projets et le département dans un écosystème numérique moderne.
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
                            <li class="footer-menu__item"><a href="<?= ROOT ?>/Logins/logout" class="footer-menu__link">Se déconnecter</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modern-bottom-footer">
        <div class="container container-two">
            <div class="d-flex justify-content-center align-items-center">
                <p class="modern-bottom-footer__text">Copyright © <?= date('Y') ?>. <span>Plateforme GI Promo 21</span>. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</footer>

<?php $this->view('Partials/nkadon-chatbot'); ?>
