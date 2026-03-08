<!-- <header class="header">
    <div class="container container-full">
        <nav class="header-inner flx-between">
            <div class="logo">
                <a href="<?= ROOT ?>/Homes/index" class="link">
                    <img src="<?= ROOT ?>/assets/images/logo/logo.png" alt="Logo">
                </a>
            </div>

            <div class="header-menu d-lg-block d-none">
                <ul class="nav-menu flx-align">
                    <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/index" class="nav-menu__link">Accueil</a></li>
                    <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/projects" class="nav-menu__link">Projets</a></li>
                    <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/departement" class="nav-menu__link">Espace Département</a></li>
                    <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/profile" class="nav-menu__link">Profil</a></li>
                    <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/login" class="nav-menu__link">Connexion</a></li>
                    <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/register" class="nav-menu__link">Inscription</a></li>
                    <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/dashboard" class="nav-menu__link">Espace</a></li>
                </ul>
                   <div class="header-right flx-align">
                <button type="button" class="header-right__button cart-btn position-relative">
                    <img src="assets/images/icons/cart.svg" alt="">
                    <span class="qty-badge font-12">0</span>
                </button>
                <button type="button" class="header-right__button"><img src="assets/images/icons/sun.svg" alt=""></button>
                <div class="header-right__inner gap-3 flx-align d-lg-flex d-none">
                    
    <a href="register.html" class="btn btn-main pill">
        <span class="icon-left icon"> 
            <img src="assets/images/icons/user.svg" alt="">
        </span>Create Account  
    </a>
    <div class="language-select flx-align select-has-icon">
        <img src="assets/images/icons/globe.svg" alt="" class="globe-icon">
        <select class="select py-0 ps-2 border-0 fw-500">
            <option value="1">Eng</option>
            <option value="2">Bn</option>
            <option value="3">Eur</option>
            <option value="4">Urd</option>
        </select>
    </div>
                </div>
                <button type="button" class="toggle-mobileMenu d-lg-none"> <i class="las la-bars"></i> </button>
            </div>
            </div>

          
        </nav>
    </div>
</header> -->
<header class="header">
    <div class="container container-full">
        <nav class="header-inner flx-between">
            <!-- Logo Start -->
            <div class="logo">
                <a href="<?= ROOT ?>/Homes/index" class="link">
                    <img src="<?= ROOT ?>/assets/images/logo/logo.png" alt="Logo">
                </a>
            </div>
            <!-- Logo End  -->

            <!-- Menu Start  -->
            <div class="header-menu d-lg-block d-none">

                <ul class="nav-menu flx-align ">
                    <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/index" class="nav-menu__link">Accueil</a></li>
                    <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/projects" class="nav-menu__link">Projets</a></li>
                     <?php if(!isset($_SESSION['user_id'])): ?>
                    <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/login" class="nav-menu__link">Connexion</a></li>
                       <?php endif; ?>
                        <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-menu__item"><a href="<?= ROOT ?>/Homes/dashboard" class="nav-menu__link">Espace</a></li>
                     <?php endif; ?>
                </ul>
            </div>
            <!-- Menu End  -->

            <!-- Header Right start -->
            <div class="header-right flx-align">
                <?php if(!isset($_SESSION['user_id'])): ?>
                <div class="header-right__inner gap-3 flx-align d-lg-flex d-none">

                    <a href="<?= ROOT ?>/Homes/register" class="btn btn-primary pill">
                        <span class="icon-left icon">
                            <img src="<?= ROOT ?>/assets/images/icons/user.svg" alt="">
                        </span>Créer un compte
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
                <?php endif; ?>
            </div>

            <!-- Header Right End  -->
        </nav>
    </div>
</header>