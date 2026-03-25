<div class="dashboard-nav bg-white flx-between gap-md-3 gap-2">
    <?php
    $role = strtolower((string)($_SESSION['role'] ?? 'etudiant'));
    $studentUnreadMessages = (int) ($studentUnreadMessages ?? ($_SESSION['student_unread_messages'] ?? 0));
    ?>
    <style>
    .nav-badge-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 22px;
        height: 22px;
        padding: 0 7px;
        margin-left: 8px;
        border-radius: 999px;
        background: #ef4444;
        color: #fff;
        font-size: .72rem;
        font-weight: 800;
        line-height: 1;
    }
    </style>
    <div class="dashboard-nav__left flx-align gap-md-3 gap-2">
        <button type="button" class="icon-btn bar-icon text-heading bg-gray-seven flx-center">
            <img src="<?= ROOT ?>/assets/images/icons/menu-bar.svg" alt="">
        </button>
        <button type="button" class="icon-btn arrow-icon text-heading bg-gray-seven flx-center">
            <img src="<?= ROOT ?>/assets/images/icons/angle-right.svg" alt="">
        </button>
        <h6 class="mb-0">
            <?php if ($role === 'admin'): ?>Administration plateforme
            <?php elseif ($role === 'der'): ?>Espace DER
            <?php else: ?>Espace etudiant
            <?php endif; ?>
        </h6>
    </div>
    <div class="dashboard-nav bg-white flx-between gap-md-3 gap-2">
        <div class="dashboard-nav__left flx-align gap-md-3 gap-2"></div>
        <div class="dashboard-nav__right">
            <div class="header-right flx-align">
                <div class="header-right__inner gap-sm-3 gap-2 flx-align d-flex">
                    <div class="user-profile">
                        <button class="user-profile__button flex-align">
                            <span class="user-profile__thumb">
                                <img src="<?= ROOT ?>/image_profile/<?= $_SESSION['image']; ?>" class="cover-img" alt="">
                            </span>
                        </button>
                        <ul class="user-profile-dropdown">
                            <?php if ($role === 'etudiant'): ?>
                            <li class="sidebar-list__item">
                                <a href="<?= ROOT ?>/Projets/mes_projets" class="sidebar-list__link">
                                    <span class="sidebar-list__icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon3.svg" alt="" class="icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active3.svg" alt="" class="icon icon-active">
                                    </span>
                                    <span class="text">Mes projets</span>
                                </a>
                            </li>
                            <li class="sidebar-list__item">
                                <a href="<?= ROOT ?>/Homes/messages_recus" class="sidebar-list__link">
                                    <span class="sidebar-list__icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon10.svg" alt="" class="icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active10.svg" alt="" class="icon icon-active">
                                    </span>
                                    <span class="text">Messages recus</span><?php if ($studentUnreadMessages > 0): ?><span class="nav-badge-count"><?= $studentUnreadMessages > 99 ? '99+' : $studentUnreadMessages ?></span><?php endif; ?>
                                </a>
                            </li>
                            <?php elseif ($role === 'admin'): ?>
                            <li class="sidebar-list__item">
                                <a href="<?= ROOT ?>/Admins/pending_projects" class="sidebar-list__link">
                                    <span class="sidebar-list__icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon10.svg" alt="" class="icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active10.svg" alt="" class="icon icon-active">
                                    </span>
                                    <span class="text">Projets a valider</span>
                                </a>
                            </li>
                            <li class="sidebar-list__item">
                                <a href="<?= ROOT ?>/Admins/projects_management" class="sidebar-list__link">
                                    <span class="sidebar-list__icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon3.svg" alt="" class="icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active3.svg" alt="" class="icon icon-active">
                                    </span>
                                    <span class="text">Gestion des projets</span>
                                </a>
                            </li>
                            <li class="sidebar-list__item">
                                <a href="<?= ROOT ?>/Admins/users_management" class="sidebar-list__link">
                                    <span class="sidebar-list__icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon2.svg" alt="" class="icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active2.svg" alt="" class="icon icon-active">
                                    </span>
                                    <span class="text">Gestion des utilisateurs</span>
                                </a>
                            </li>
                            <li class="sidebar-list__item">
                                <a href="<?= ROOT ?>/Admins/categories" class="sidebar-list__link">
                                    <span class="sidebar-list__icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon2.svg" alt="" class="icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active2.svg" alt="" class="icon icon-active">
                                    </span>
                                    <span class="text">Gestion des categories</span>
                                </a>
                            </li>
                            <li class="sidebar-list__item">
                                <a href="<?= ROOT ?>/Admins/messages" class="sidebar-list__link">
                                    <span class="sidebar-list__icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon10.svg" alt="" class="icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active10.svg" alt="" class="icon icon-active">
                                    </span>
                                    <span class="text">Messages / Contact</span>
                                </a>
                            </li>
                            <?php else: ?>
                            <li class="sidebar-list__item">
                                <a href="<?= ROOT ?>/Homes/der_espace" class="sidebar-list__link">
                                    <span class="sidebar-list__icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon10.svg" alt="" class="icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active10.svg" alt="" class="icon icon-active">
                                    </span>
                                    <span class="text">Gestion publications DER</span>
                                </a>
                            </li>
                            <?php endif; ?>

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
                                <a href="<?= ROOT ?>/Logins/logout" class="sidebar-list__link">
                                    <span class="sidebar-list__icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon13.svg" alt="" class="icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active13.svg" alt="" class="icon icon-active">
                                    </span>
                                    <span class="text">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
