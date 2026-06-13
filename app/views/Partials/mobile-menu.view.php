<?php
$publicNavItems = [
    ['label' => 'Accueil', 'href' => ROOT . '/Homes/index', 'icon' => 'las la-home'],
    ['label' => 'Projets', 'href' => ROOT . '/Homes/projects', 'icon' => 'las la-layer-group'],
    ['label' => 'Département', 'href' => ROOT . '/Homes/departement', 'icon' => 'las la-bullhorn'],
];
?>
<div class="mobile-menu d-lg-none d-block">
    <button type="button" class="close-button"><i class="las la-times"></i></button>
    <div class="mobile-menu__inner">
        <a href="<?= ROOT ?>/Homes/index" class="mobile-menu__logo">
            <img src="<?= ROOT ?>/assets/images/logo/n'kakodon.png" alt="Logo">
        </a>
        <div class="mobile-menu__menu">
            <ul class="nav-menu flx-align nav-menu--mobile">
                <?php foreach ($publicNavItems as $item): ?>
                <li class="nav-menu__item">
                    <a href="<?= htmlspecialchars($item['href']) ?>" class="nav-menu__link">
                        <i class="<?= htmlspecialchars($item['icon']) ?>"></i>
                        <?= htmlspecialchars($item['label']) ?>
                    </a>
                </li>
                <?php endforeach; ?>
                <?php if (!isset($_SESSION['user_id'])): ?>
                <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/login" class="nav-menu__link"><i class="las la-sign-in-alt"></i>Connexion</a></li>
                <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/register" class="nav-menu__link"><i class="las la-user-plus"></i>Inscription</a></li>
                <?php else: ?>
                <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/dashboard" class="nav-menu__link"><i class="las la-th-large"></i>Mon espace</a></li>
                <li class="nav-menu__item"><a href="<?= ROOT ?>/Profiles/appercu" class="nav-menu__link"><i class="las la-user-circle"></i>Profil</a></li>
                <li class="nav-menu__item"><a href="<?= ROOT ?>/Logins/logout" class="nav-menu__link"><i class="las la-sign-out-alt"></i>Déconnexion</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
