<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Connexion']); ?>

<body class="public-site public-auth">

    <?php $this->view('Partials/global-shell'); ?>
    <?php $this->view('Partials/mobile-menu'); ?>
    <?php $this->view('Partials/header'); ?>
    <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

    <main>
        <section class="ds-auth">
            <div class="ds-auth__card">

                <aside class="ds-auth__panel">
                    <span class="ds-auth__badge">Bienvenue sur NGAKODON</span>
                    <img src="<?= ROOT ?>/assets/images/logo/login.png" alt="" class="ds-auth__illus" onerror="this.style.display='none'">
                    <h2>Heureux de vous revoir</h2>
                    <p>Connectez-vous pour retrouver vos projets et suivre votre activité sur NGAKODON.</p>
                    <div class="ds-auth__stats">
                        <div class="ds-auth__stat"><b>+100</b><span>Projets publiés</span></div>
                        <div class="ds-auth__stat"><b>+50</b><span>Étudiants actifs</span></div>
                        <div class="ds-auth__stat"><b>100%</b><span>Talents valorisés</span></div>
                    </div>
                </aside>

                <div class="ds-auth__body">
                    <h3>Connexion</h3>
                    <p class="ds-auth__sub">Entrez vos identifiants pour accéder à votre compte.</p>

                    <form action="<?= ROOT ?>/Logins/index" method="POST">
                        <?php $this->view("set_flash"); ?>

                        <div class="mb-3">
                            <label class="ds-label" for="login-email">Adresse email</label>
                            <div class="ds-auth__field">
                                <i class='bx bx-envelope'></i>
                                <input type="email" id="login-email" name="email" class="ds-input" placeholder="exemple@email.com" required>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="ds-label" for="login-password">Mot de passe</label>
                            <div class="ds-auth__field">
                                <i class='bx bx-lock-alt'></i>
                                <input type="password" id="login-password" name="password" class="ds-input" placeholder="Votre mot de passe" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mb-4">
                            <a href="#" class="ds-auth__link">Mot de passe oublié ?</a>
                        </div>

                        <button type="submit" name="submit" class="ds-btn ds-btn--primary ds-btn--block">
                            <i class='bx bx-log-in-circle'></i> Se connecter
                        </button>
                    </form>

                    <?php $this->view('Partials/social-login'); ?>

                    <p class="ds-auth__alt">Vous n'avez pas de compte ? <a href="<?= ROOT ?>/Homes/register">Créer un compte</a></p>
                </div>

            </div>
        </section>

        <?php $this->view('Partials/footer'); ?>
    </main>

    <?php $this->view('Partials/scripts'); ?>
</body>

</html>
