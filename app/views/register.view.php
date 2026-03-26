<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Inscription']); ?>

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
            min-height: 600px;
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
        }

        .image-card-content .features-list {
            text-align: left;
            margin-top: 32px;
            width: 100%;
        }

        .image-card-content .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .image-card-content .feature-item svg {
            flex-shrink: 0;
        }

        /* Carte droite - Formulaire */
        .form-card {
            background: white;
        }

        .form-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 24px 32px;
            text-align: center;
        }

        .form-card-header h3 {
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }

        .form-card-header p {
            color: rgba(255, 255, 255, 0.9);
            margin: 8px 0 0 0;
            font-size: 14px;
        }

        .form-card-body {
            padding: 32px;
        }

        /* Amélioration des inputs */
        .form-group {
            margin-bottom: 20px;
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
            padding-left: 40px;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            opacity: 0.6;
        }

        select.form-control-modern {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 20px;
        }

        .btn-create {
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
            margin-top: 8px;
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(102, 126, 234, 0.5);
        }

        .btn-create:active {
            transform: translateY(0);
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
                padding: 24px;
            }
        }

        .row-custom {
            display: flex;
            flex-wrap: wrap;
            margin: -12px;
        }

        .col-custom {
            flex: 1;
            padding: 12px;
            min-width: 200px;
        }

        @media (max-width: 576px) {
            .col-custom {
                min-width: 100%;
            }
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
                    <!-- Carte gauche - Image de création de compte -->
                    <div class="col-lg-6">
                        <div class="modern-card image-card">
                            <div class="image-card-content">
                                <img src="<?= ROOT ?>/assets/images/logo/creer_compte.png" alt="Créer un compte" style="max-width: 280px;">
                                <h2>Rejoignez NGAKODON</h2>
                                <p>Créez votre compte et valorisez vos projets auprès du monde</p>
                                <div class="features-list">
                                    <div class="feature-item">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                            <path d="M20 6L9 17L4 12" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span>Publiez vos projets étudiants</span>
                                    </div>
                                    <div class="feature-item">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                            <path d="M20 6L9 17L4 12" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span>Gagnez en visibilité</span>
                                    </div>
                                    <div class="feature-item">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                            <path d="M20 6L9 17L4 12" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span>Recevez des opportunités et contacts</span>
                                    </div>
                                    <div class="feature-item">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                            <path d="M20 6L9 17L4 12" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span>Faites connaître votre talent</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carte droite - Formulaire d'inscription -->
                    <div class="col-lg-6">
                        <div class="modern-card form-card">
                            <div class="form-card-header">
                                <h3>Création de compte</h3>
                                <p>Remplissez le formulaire ci-dessous pour commencer</p>
                            </div>
                            <div class="form-card-body">
                                <form action="<?= ROOT ?>/Utilisateurs/ajouter_utilisateur" method="POST" autocomplete="off">
                                    <?php $this->view("set_flash"); ?>
                                    
                                    <input type="text" name="fake_username" autocomplete="username" class="d-none" tabindex="-1">
                                    <input type="password" name="fake_password" autocomplete="new-password" class="d-none" tabindex="-1">
                                    
                                    <div class="row-custom">
                                        <div class="col-custom">
                                            <div class="form-group">
                                                <label class="form-label">Prénom</label>
                                                <div class="input-with-icon">
                                                    <input type="text" name="prenom" class="form-control-modern" placeholder="Votre prénom" autocomplete="given-name" required>
                                                    <span class="input-icon">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                                            <circle cx="12" cy="7" r="4"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-custom">
                                            <div class="form-group">
                                                <label class="form-label">Nom</label>
                                                <div class="input-with-icon">
                                                    <input type="text" name="nom" class="form-control-modern" placeholder="Votre nom" autocomplete="family-name" required>
                                                    <span class="input-icon">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                                            <circle cx="12" cy="7" r="4"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Adresse email</label>
                                        <div class="input-with-icon">
                                            <input type="email" name="email" id="email" class="form-control-modern" placeholder="votre.email@exemple.com" autocomplete="off" readonly required>
                                            <span class="input-icon">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                                    <polyline points="22,6 12,13 2,6"/>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row-custom">
                                        <div class="col-custom">
                                            <div class="form-group">
                                                <label class="form-label">Université / Établissement</label>
                                                <select name="universite_id" id="universite" class="form-control-modern" required>
                                                    <option value="">Choisir université</option>
                                                    <?php foreach(($universites ?? []) as $u): ?>
                                                        <option value="<?= $u->id_universite ?>"><?= $u->nom_universite ?></option>
                                                    <?php endforeach; ?>
                                                    <option value="autre">Autre établissement (privé)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-custom">
                                            <div class="form-group">
                                                <label class="form-label">Faculté / Institut</label>
                                                <select name="faculte_id" id="faculte" class="form-control-modern">
                                                    <option value="">Choisir faculté / institut</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="autreEtablissementWrap" class="d-none">
                                        <div class="form-group">
                                            <label class="form-label">Nom de l'établissement privé</label>
                                            <input type="text" name="autre_etablissement" id="autreEtablissement" class="form-control-modern" placeholder="Ex: Institut X">
                                        </div>
                                    </div>

                                    <div id="autreDepartementWrap" class="d-none">
                                        <div class="form-group">
                                            <label class="form-label">Département / Institut (optionnel)</label>
                                            <input type="text" name="autre_departement" id="autreDepartement" class="form-control-modern" placeholder="Ex: Département Informatique">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Filière</label>
                                        <input type="text" name="filiere" id="filiere" class="form-control-modern" placeholder="Ex: Informatique, Droit, Médecine" autocomplete="off" readonly required>
                                    </div>

                                    <div class="row-custom">
                                        <div class="col-custom">
                                            <div class="form-group">
                                                <label class="form-label">Mot de passe</label>
                                                <div class="input-with-icon">
                                                    <input type="password" name="password" class="form-control-modern" placeholder="••••••••" autocomplete="new-password" required>
                                                    <span class="input-icon">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-custom">
                                            <div class="form-group">
                                                <label class="form-label">Confirmation</label>
                                                <div class="input-with-icon">
                                                    <input type="password" name="password_confirm" class="form-control-modern" placeholder="••••••••" autocomplete="new-password" required>
                                                    <span class="input-icon">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" name="submit" class="btn-create">
                                        Créer mon compte
                                    </button>
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
    <script>
    const universiteSelect = document.getElementById("universite");
    const faculteSelect = document.getElementById("faculte");
    const autreEtablissementWrap = document.getElementById("autreEtablissementWrap");
    const autreDepartementWrap = document.getElementById("autreDepartementWrap");
    const autreEtablissementInput = document.getElementById("autreEtablissement");
    const emailInput = document.getElementById("email");
    const filiereInput = document.getElementById("filiere");

    [emailInput, filiereInput].forEach((input) => {
        if (!input) return;
        input.addEventListener("focus", function() {
            this.removeAttribute("readonly");
        });
        input.value = "";
    });

    function resetFacultes() {
        faculteSelect.innerHTML = '<option value="">Choisir faculté / institut</option>';
    }

    universiteSelect.addEventListener("change", function() {
        const universiteId = this.value;
        const isAutre = universiteId === "autre";

        resetFacultes();

        if (isAutre) {
            faculteSelect.disabled = true;
            faculteSelect.required = false;
            autreEtablissementWrap.classList.remove("d-none");
            autreDepartementWrap.classList.remove("d-none");
            autreEtablissementInput.required = true;
            return;
        }

        faculteSelect.disabled = false;
        autreEtablissementWrap.classList.add("d-none");
        autreDepartementWrap.classList.add("d-none");
        autreEtablissementInput.required = false;

        if (!universiteId) {
            return;
        }

        fetch("<?= ROOT ?>/Utilisateurs/getFacultes/" + universiteId)
            .then(response => response.json())
            .then(data => {
                data.forEach(function(item) {
                    faculteSelect.innerHTML += `<option value="${item.id_faculte}">${item.nom_faculte}</option>`;
                });
            })
            .catch(() => {
                resetFacultes();
            });
    });
    </script>
</body>

</html