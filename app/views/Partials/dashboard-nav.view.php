<div class="dashboard-nav bg-white flx-between gap-md-3 gap-2">
    <div class="dashboard-nav__left flx-align gap-md-3 gap-2">
        <button type="button" class="icon-btn bar-icon text-heading bg-gray-seven flx-center">
            <img src="<?= ROOT ?>/assets/images/icons/menu-bar.svg" alt="">
        </button>
        <button type="button" class="icon-btn arrow-icon text-heading bg-gray-seven flx-center">
            <img src="<?= ROOT ?>/assets/images/icons/angle-right.svg" alt="">
        </button>
        <h6 class="mb-0">Administration plateforme</h6>
    </div>
    <div class="dashboard-nav bg-white flx-between gap-md-3 gap-2">
        <div class="dashboard-nav__left flx-align gap-md-3 gap-2">
           
          
        </div>
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
                            <li class="sidebar-list__item">
                                <a href="<?= ROOT ?>/Profiles/appercu" class="sidebar-list__link">
                                   
                                    <span class="sidebar-list__icon">
                                  
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon2.svg" alt="" class="icon">
                                        <img src="<?= ROOT ?>/assets/images/icons/sidebar-icon-active2.svg" alt="" class="icon icon-active">
                                    </span>
                                     <!-- <span class="text"><?= $_SESSION['nom']. ' ' . $_SESSION['prenom']; ?></span><span class="user-status text-muted"><?= $_SESSION['role']; ?>
                                </span> -->
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