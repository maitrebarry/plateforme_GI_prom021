<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Connexion']); ?>

<body>
    <style>
        /* Styles principaux pour les deux cartes */
        .two-cards-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .cards-container {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
        }

        /* Style commun pour les deux cartes */
        .modern-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.3);
        }

        /* Carte gauche - Image */
        .image-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }

        .image-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="15" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="25" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="80" r="20" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: 200px;
            opacity: 0.5;
        }

        .image-card-content {
            position: relative;
            z-index: 2;
            padding: 48px 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            height: 100%;
            min-height: 550px;
        }

        .image-card-content img {
            max-width: 100%;
            height: auto;
            margin-bottom: 32px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .image-card-content h2 {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .image-card-content p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .image-card-content .stats-container {
            display: flex;
            gap: 32px;
            justify-content: center;
            margin-top: 16px;
        }

        .image-card-content .stat-item {
            text-align: center;
        }

        .image-card-content .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: white;
            display: block;
        }

        .image-card-content .stat-label {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Carte droite - Formulaire */
        .form-card {
            background: white;
        }

        .form-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 32px;
            text-align: center;
        }

        .form-card-header .logo {
            margin-bottom: 24px;
            display: inline-block;
        }

        .form-card-header .logo img {
            height: 50px;
            width: auto;
        }

        .form-card-header h3 {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 8px 0;
        }

        .form-card-header p {
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            font-size: 14px;
        }

        .form-card-body {
            padding: 40px 32px;
        }

        /* Amélioration des inputs */
        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            font-weight: 600;
            font-size: 14px;
            color: #374151;
            margin-bottom: 8px;
            display: block;
        }

        .form-control-modern {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon .form-control-modern {
            padding-left: 44px;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .input-icon img {
            width: 18px;
            height: 18px;
            opacity: 0.6;
        }

        /* Liens et options */
        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .forgot-password {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        /* Bouton de connexion */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(102, 126, 234, 0.5);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Lien d'inscription */
        .register-link {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
        }

        .register-link p {
            color: #6b7280;
            font-size: 14px;
            margin: 0;
        }

        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .two-cards-section {
                padding: 40px 20px;
            }
            
            .image-card-content {
                min-height: auto;
                padding: 40px 24px;
            }
            
            .image-card-content img {
                max-width: 200px;
            }
        }

        @media (max-width: 767px) {
            .image-card {
                margin-bottom: 24px;
            }
            
            .form-card-body {
                padding: 32px 24px;
            }
            
            .form-card-header {
                padding: 24px;
            }
        }

        /* Animation de la carte image */
        .image-card-content .welcome-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 8px 20px;
            display: inline-block;
            margin-bottom: 24px;
        }

        .image-card-content .welcome-badge span {
            color: white;
            font-size: 14px;
            font-weight: 500;
        }
    </style>

    <?php $this->view('Partials/global-shell'); ?>
    <?php $this->view('Partials/mobile-menu'); ?>
    <?php $this->view('Partials/header'); ?>
    <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

    <main>
        <section class="two-cards-section">
            <div class="cards-container">
                <div class="row g-4">
                    <!-- Carte gauche - Image de bienvenue -->
                    <div class="col-lg-6">
                        <div class="modern-card image-card">
                            <div class="image-card-content">
                                <div class="welcome-badge">
                                    <span>✨ Bienvenue sur N'kakodon ✨</span>
                                </div>
                                <img src="<?= ROOT ?>/assets/images/logo/login.png" alt="Connexion" style="max-width: 260px;">
                                <h2>Heureux de vous revoir !</h2>
                                <p>Connectez-vous pour accéder à vos projets et suivre votre activité sur NGAKODON</p>
                                <div class="stats-container">
                                    <div class="stat-item">
                                        <span class="stat-number">+100</span>
                                        <span class="stat-label">Projets publiés</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-number">+50</span>
                                        <span class="stat-label">Étudiants actifs</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-number">100%</span>
                                        <span class="stat-label">Valorisation des talents</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carte droite - Formulaire de connexion -->
                    <div class="col-lg-6">
                        <div class="modern-card form-card">
                            <div class="form-card-header">
                                <a href="index.html" class="logo">
                                    <img src="<?= ROOT ?>/assets/images/logo/n'kakodon.png" alt="N'kakodon">
                                </a>
                                <h3>Connexion</h3>
                                <p>Entrez vos identifiants pour accéder à votre compte</p>
                            </div>
                            <div class="form-card-body">
                                <form action="<?= ROOT ?>/Logins/index" method="POST">
                                    <?php $this->view("set_flash"); ?>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Adresse email</label>
                                        <div class="input-with-icon">
                                            <input type="email" name="email" class="form-control-modern" placeholder="exemple@email.com" required>
                                            <span class="input-icon">
                                                <img src="<?= ROOT ?>/assets/images/icons/envelope-icon.svg" alt="">
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Mot de passe</label>
                                        <div class="input-with-icon">
                                            <input type="password" name="password" class="form-control-modern" placeholder="Votre mot de passe" required>
                                            <span class="input-icon">
                                                <img src="<?= ROOT ?>/assets/images/icons/lock-icon.svg" alt="">
                                            </span>
                                        </div>
                                    </div>

                                    <div class="options-row">
                                        <div class="remember-me">
                                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                                <input type="checkbox" name="remember" style="width: 16px; height: 16px; cursor: pointer;">
                                                <span style="font-size: 14px; color: #6b7280;">Se souvenir de moi</span>
                                            </label>
                                        </div>
                                        <a href="#" class="forgot-password">
                                            Mot de passe oublié ?
                                        </a>
                                    </div>

                                    <button type="submit" name="submit" class="btn-login">
                                        Se connecter
                                    </button>

                                    <div class="register-link">
                                        <p>Vous n'avez pas de compte ? <a href="<?= ROOT ?>/Utilisateurs/inscription">Créer un compte</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php $this->view('Partials/footer'); ?>
    </main>

    <?php $this->view('Partials/scripts'); ?>
</body>

</html>