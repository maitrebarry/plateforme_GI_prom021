<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Inscription']); ?>

<body class="public-site public-auth public-register">

    <?php $this->view('Partials/global-shell'); ?>
    <?php $this->view('Partials/mobile-menu'); ?>
    <?php $this->view('Partials/header'); ?>
    <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

    <main>
        <section class="ds-auth">
            <div class="ds-auth__card">

                <aside class="ds-auth__panel">
                    <span class="ds-auth__badge">Rejoignez NGAKODON</span>
                    <img src="<?= ROOT ?>/assets/images/logo/creer_compte.png" alt="" class="ds-auth__illus" onerror="this.style.display='none'">
                    <h2>Valorisez vos projets</h2>
                    <p>Créez votre compte et présentez vos réalisations au monde professionnel.</p>
                    <ul class="ds-auth__features">
                        <li><i class='bx bx-check-circle'></i> Publiez vos projets étudiants</li>
                        <li><i class='bx bx-check-circle'></i> Gagnez en visibilité</li>
                        <li><i class='bx bx-check-circle'></i> Recevez opportunités et contacts</li>
                        <li><i class='bx bx-check-circle'></i> Faites connaître votre talent</li>
                    </ul>
                </aside>

                <div class="ds-auth__body">
                    <h3>Création de compte</h3>
                    <p class="ds-auth__sub">Remplissez le formulaire ci-dessous pour commencer.</p>

                    <form action="<?= ROOT ?>/Utilisateurs/ajouter_utilisateur" method="POST" autocomplete="off">
                        <?php $this->view("set_flash"); ?>

                        <input type="text" name="fake_username" autocomplete="username" class="d-none" tabindex="-1">
                        <input type="password" name="fake_password" autocomplete="new-password" class="d-none" tabindex="-1">

                        <div class="ds-auth__row mb-3">
                            <div>
                                <label class="ds-label" for="reg-prenom">Prénom</label>
                                <div class="ds-auth__field">
                                    <i class='bx bx-user'></i>
                                    <input type="text" id="reg-prenom" name="prenom" class="ds-input" placeholder="Votre prénom" autocomplete="given-name" required>
                                </div>
                            </div>
                            <div>
                                <label class="ds-label" for="reg-nom">Nom</label>
                                <div class="ds-auth__field">
                                    <i class='bx bx-user'></i>
                                    <input type="text" id="reg-nom" name="nom" class="ds-input" placeholder="Votre nom" autocomplete="family-name" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="ds-label" for="email">Adresse email</label>
                            <div class="ds-auth__field">
                                <i class='bx bx-envelope'></i>
                                <input type="email" name="email" id="email" class="ds-input" placeholder="votre.email@exemple.com" autocomplete="off" readonly required>
                            </div>
                        </div>

                        <div class="ds-auth__row mb-3">
                            <div>
                                <label class="ds-label" for="universite">Université / Établissement</label>
                                <select name="universite_id" id="universite" class="ds-select" required>
                                    <option value="">Choisir université</option>
                                    <?php foreach (($universites ?? []) as $u): ?>
                                        <option value="<?= $u->id_universite ?>"><?= htmlspecialchars((string) $u->nom_universite) ?></option>
                                    <?php endforeach; ?>
                                    <option value="autre">Autre établissement (privé)</option>
                                </select>
                            </div>
                            <div>
                                <label class="ds-label" for="faculte">Faculté / Institut</label>
                                <select name="faculte_id" id="faculte" class="ds-select">
                                    <option value="">Choisir faculté / institut</option>
                                </select>
                            </div>
                        </div>

                        <div id="autreEtablissementWrap" class="d-none mb-3">
                            <label class="ds-label" for="autreEtablissement">Nom de l'établissement privé</label>
                            <input type="text" name="autre_etablissement" id="autreEtablissement" class="ds-input" placeholder="Ex: Institut X">
                        </div>

                        <div id="autreDepartementWrap" class="d-none mb-3">
                            <label class="ds-label" for="autreDepartement">Département / Institut (optionnel)</label>
                            <input type="text" name="autre_departement" id="autreDepartement" class="ds-input" placeholder="Ex: Département Informatique">
                        </div>

                        <div class="mb-3">
                            <label class="ds-label" for="filiere">Filière</label>
                            <div class="ds-auth__field">
                                <i class='bx bx-book-bookmark'></i>
                                <input type="text" name="filiere" id="filiere" class="ds-input" placeholder="Ex: Informatique, Droit, Médecine" autocomplete="off" readonly required>
                            </div>
                        </div>

                        <div class="ds-auth__row mb-4">
                            <div>
                                <label class="ds-label" for="reg-password">Mot de passe</label>
                                <div class="ds-auth__field">
                                    <i class='bx bx-lock-alt'></i>
                                    <input type="password" id="reg-password" name="password" class="ds-input" placeholder="••••••••" autocomplete="new-password" required>
                                </div>
                            </div>
                            <div>
                                <label class="ds-label" for="reg-password-confirm">Confirmation</label>
                                <div class="ds-auth__field">
                                    <i class='bx bx-lock-alt'></i>
                                    <input type="password" id="reg-password-confirm" name="password_confirm" class="ds-input" placeholder="••••••••" autocomplete="new-password" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="submit" class="ds-btn ds-btn--primary ds-btn--block">
                            <i class='bx bx-user-plus'></i> Créer mon compte
                        </button>
                    </form>

                    <?php $this->view('Partials/social-login'); ?>

                    <p class="ds-auth__alt">Vous avez déjà un compte ? <a href="<?= ROOT ?>/Homes/login">Se connecter</a></p>
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

</html>
