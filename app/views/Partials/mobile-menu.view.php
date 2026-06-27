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
                <li class="nav-menu__item"><a href="<?= ROOT ?>/Logins/logout" data-logout class="nav-menu__link"><i class="las la-sign-out-alt"></i>Déconnexion</a></li>
                <?php endif; ?>
                <li class="nav-menu__item">
                    <a href="#" class="nav-menu__link" role="button" onclick="if(window.nkInstall){window.nkInstall();}return false;">
                        <i class="bx bx-download"></i>Installer l'application
                    </a>
                </li>
                <li class="nav-menu__item">
                    <a href="#" class="nav-menu__link" data-theme-toggle role="button">
                        <i class="bx bx-moon"></i><span data-theme-label>Mode sombre</span>
                    </a>
                </li>
                <li class="nav-menu__item" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <i class='bx bx-palette'></i><span>Couleur</span>
                    <span class="ds-color-swatches" style="margin-left:auto;">
                        <button type="button" class="ds-color-swatch" data-color-value="green" style="--sw:#157f5a" title="Vert" aria-label="Vert"></button>
                        <button type="button" class="ds-color-swatch" data-color-value="blue" style="--sw:#1d59b8" title="Bleu" aria-label="Bleu"></button>
                        <button type="button" class="ds-color-swatch" data-color-value="orange" style="--sw:#cf5410" title="Orange" aria-label="Orange"></button>
                        <button type="button" class="ds-color-swatch" data-color-value="violet" style="--sw:#6236c4" title="Violet" aria-label="Violet"></button>
                    </span>
                </li>
            </ul>
        </div>
    </div>
</div>
