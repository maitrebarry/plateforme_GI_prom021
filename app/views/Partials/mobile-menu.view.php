<div class="mobile-menu d-lg-none d-block">
    <button type="button" class="close-button"><i class="las la-times"></i></button>
    <div class="mobile-menu__inner">
        <a href="<?= ROOT ?>/Homes/index" class="mobile-menu__logo">
            <img src="<?= ROOT ?>/assets/images/logo/logo.png" alt="Logo">
        </a>
        <div class="mobile-menu__menu">
            <ul class="nav-menu flx-align nav-menu--mobile">
                <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/index" class="nav-menu__link">Accueil</a></li>
                <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/projects" class="nav-menu__link">Projets</a></li>
                <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/departement" class="nav-menu__link">Espace Département</a></li>
                <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/profile" class="nav-menu__link">Profil</a></li>
                <?php if (!isset($_SESSION['user_id'])): ?>
                <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/login" class="nav-menu__link">Connexion</a></li>
                <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/register" class="nav-menu__link">Inscription</a></li>
                <?php else: ?>
                <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/dashboard" class="nav-menu__link">Espace</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
