<div class="dashboard-sidebar">
    <button type="button" class="dashboard-sidebar__close d-lg-none d-flex"><i class="las la-times"></i></button>
    <div class="dashboard-sidebar__inner">
        <a href="<?= ROOT ?>/Homes/index" class="logo mb-48"><img src="<?= ROOT ?>/assets/images/logo/logo.png"
                alt=""></a>

        <?php $role = strtolower((string)($_SESSION['role'] ?? 'etudiant')); ?>

        <ul class="sidebar-list">
            <li class="sidebar-list__item">
                <a href="<?= ROOT ?>/Homes/dashboard" class="sidebar-list__link">
                    <span class="sidebar-list__icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon1.svg" alt="" class="icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active1.svg" alt=""
                            class="icon icon-active">
                    </span>
                    <span class="text">Tableau de bord</span>
                </a>
            </li>

            <?php if ($role === 'etudiant'): ?>
            <li class="sidebar-list__item">
                <a href="<?= ROOT ?>/Projets/mes_projets" class="sidebar-list__link">
                    <span class="sidebar-list__icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon3.svg" alt="" class="icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active3.svg" alt=""
                            class="icon icon-active">
                    </span>
                    <span class="text">Mes projets</span>
                </a>
            </li>
            <li class="sidebar-list__item">
                <a href="<?= ROOT ?>/Homes/messages_recus" class="sidebar-list__link">
                    <span class="sidebar-list__icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon10.svg" alt="" class="icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active10.svg" alt=""
                            class="icon icon-active">
                    </span>
                    <span class="text">Messages reçus</span>
                </a>
            </li>
            <?php elseif ($role === 'admin'): ?>
            <li class="sidebar-list__item">
                <a href="<?= ROOT ?>/Admins/pending_projects" class="sidebar-list__link">
                    <span class="sidebar-list__icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon10.svg" alt="" class="icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active10.svg" alt=""
                            class="icon icon-active">
                    </span>
                    <span class="text">Projets à valider</span>
                </a>
            </li>
            <li class="sidebar-list__item">
                <a href="<?= ROOT ?>/Admins/projects_management" class="sidebar-list__link">
                    <span class="sidebar-list__icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon3.svg" alt="" class="icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active3.svg" alt=""
                            class="icon icon-active">
                    </span>
                    <span class="text">Gestion des projets</span>
                </a>
            </li>
            <li class="sidebar-list__item">
                <a href="<?= ROOT ?>/Admins/users_management" class="sidebar-list__link">
                    <span class="sidebar-list__icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon2.svg" alt="" class="icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active2.svg" alt=""
                            class="icon icon-active">
                    </span>
                    <span class="text">Gestion des utilisateurs</span>
                </a>
            </li>
            <li class="sidebar-list__item">
                <a href="<?= ROOT ?>/Admins/categories" class="sidebar-list__link">
                    <span class="sidebar-list__icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon2.svg" alt="" class="icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active2.svg" alt=""
                            class="icon icon-active">
                    </span>
                    <span class="text">Gestion des catégories</span>
                </a>
            </li>
            <li class="sidebar-list__item">
                <a href="<?= ROOT ?>/Admins/messages" class="sidebar-list__link">
                    <span class="sidebar-list__icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon10.svg" alt="" class="icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active10.svg" alt=""
                            class="icon icon-active">
                    </span>
                    <span class="text">Messages / Contact</span>
                </a>
            </li>
            <?php else: ?>
            <li class="sidebar-list__item">
                <a href="<?= ROOT ?>/Homes/der_espace" class="sidebar-list__link">
                    <span class="sidebar-list__icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon10.svg" alt="" class="icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active10.svg" alt=""
                            class="icon icon-active">
                    </span>
                    <span class="text">Gestion publications DER</span>
                </a>
            </li>
            <?php endif; ?>

            <li class="sidebar-list__item">
                <a href="<?= ROOT ?>/Homes/index" class="sidebar-list__link">
                    <span class="sidebar-list__icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon13.svg" alt="" class="icon">
                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active13.svg" alt=""
                            class="icon icon-active">
                    </span>
                    <span class="text">Retour au site</span>
                </a>
            </li>
        </ul>
    </div>
</div>