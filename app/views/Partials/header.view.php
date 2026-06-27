<?php
$publicNavItems = [
    ['label' => 'Accueil', 'href' => ROOT . '/Homes/index'],
    ['label' => 'Projets', 'href' => ROOT . '/Homes/projects'],
    ['label' => 'Département', 'href' => ROOT . '/Homes/departement'],
];
?>
<header class="header">
    <div class="container container-full">
        <nav class="header-inner flx-between">
            <div class="logo">
                <a href="<?= ROOT ?>/Homes/index" class="link">
                    <img src="<?= ROOT ?>/assets/images/logo/n'kakodon.png" alt="Logo">
                </a>
            </div>

            <div class="header-menu d-lg-block d-none">
                <ul class="nav-menu flx-align">
                    <?php foreach ($publicNavItems as $item): ?>
                    <li class="nav-menu__item">
                        <a href="<?= htmlspecialchars($item['href']) ?>" class="nav-menu__link"><?= htmlspecialchars($item['label']) ?></a>
                    </li>
                    <?php endforeach; ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/dashboard" class="nav-menu__link">Mon espace</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="header-right flx-align">
                <div class="ds-color-picker me-2">
                    <button type="button" class="ds-theme-toggle" data-color-toggle title="Couleur du thème" aria-label="Choisir la couleur du thème">
                        <i class="bx bx-palette"></i>
                    </button>
                    <div class="ds-color-menu" data-color-menu>
                        <button type="button" class="ds-color-swatch" data-color-value="green" style="--sw:#157f5a" title="Vert" aria-label="Vert"></button>
                        <button type="button" class="ds-color-swatch" data-color-value="blue" style="--sw:#1d59b8" title="Bleu" aria-label="Bleu"></button>
                        <button type="button" class="ds-color-swatch" data-color-value="orange" style="--sw:#cf5410" title="Orange" aria-label="Orange"></button>
                        <button type="button" class="ds-color-swatch" data-color-value="violet" style="--sw:#6236c4" title="Violet" aria-label="Violet"></button>
                    </div>
                </div>
                <button type="button" class="ds-theme-toggle me-2" data-theme-toggle title="Basculer mode clair / sombre" aria-label="Basculer entre mode clair et sombre">
                    <i class="bx bx-moon"></i>
                </button>
                <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="header-right__inner public-header-actions gap-2 flx-align d-lg-flex d-none">
                    <a href="<?= ROOT ?>/Homes/login" class="public-login-link">Connexion</a>
                    <a href="<?= ROOT ?>/Homes/register" class="btn btn-primary pill public-register-btn">
                        <span class="icon-left icon"><img src="<?= ROOT ?>/assets/images/icons/user.svg" alt=""></span>
                        Créer un compte
                    </a>
                </div>
                <button type="button" class="toggle-mobileMenu d-lg-none"> <i class="las la-bars"></i> </button>
                <?php else: ?>
                <div class="header-right__inner gap-sm-3 gap-2 flx-align d-flex">

                    <div class="user-profile">
                        <button class="user-profile__button flex-align">
                            <span class="user-profile__thumb">
                                <img src="<?= ROOT ?>/image_profile/<?= $_SESSION['image']; ?>" class="cover-img" alt="">
                            </span>
                        </button>
                        <ul class="user-profile-dropdown">
                            <li class="sidebar-list__item">
                                <a href="<?= ROOT ?>/Profiles/appercu" class="sidebar-list__link">
                                    <span class="sidebar-list__icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon2.svg" alt="" class="icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active2.svg" alt="" class="icon icon-active">
                                    </span>
                                    <span class="text">Profile</span>
                                </a>
                            </li>
                            <li class="sidebar-list__item">
                                <a href="<?= ROOT ?>/Homes/dashboard" class="sidebar-list__link">
                                    <span class="sidebar-list__icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon1.svg" alt="" class="icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active1.svg" alt="" class="icon icon-active">
                                    </span>
                                    <span class="text">Mon espace</span>
                                </a>
                            </li>
                            <li class="sidebar-list__item">
                                <a href="<?= ROOT ?>/Logins/logout" data-logout class="sidebar-list__link">
                                    <span class="sidebar-list__icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon13.svg" alt="" class="icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active13.svg" alt="" class="icon icon-active">
                                    </span>
                                    <span class="text">Déconnexion</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <button type="button" class="toggle-mobileMenu d-lg-none"> <i class="las la-bars"></i> </button>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>
