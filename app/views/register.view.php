<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Inscription']); ?>

<body>
    <style>
        .register-card {
            width: 100%;
            max-width: 720px;
            border: 0;
            border-radius: 16px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }

        .register-card .card-header {
            background: var(--main-600, #0d6efd);
            color: #fff;
            padding: 18px 24px;
            font-size: 1.35rem;
            font-weight: 700;
        }

        .register-card .card-body {
            padding: 28px;
        }

        .register-card .common-input {
            min-height: 52px;
            font-size: 15px;
        }
    </style>

    <?php $this->view('Partials/global-shell'); ?>
    <?php $this->view('Partials/mobile-menu'); ?>
    <?php $this->view('Partials/header'); ?>
    <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

    <main class="change-gradient">

        <section class="account d-flex">
            <img src="assets/images/thumbs/account-img.png" alt="" class="account__img">
            <div class="account__left d-md-flex d-none flx-align section-bg position-relative z-index-1 overflow-hidden">
                <img src="<?= ROOT ?>/assets/images/shapes/pattern-curve-seven.png" alt="" class="position-absolute end-0 top-0 z-index--1 h-100">
                <div class="account-thumb">
                    <img src="<?= ROOT ?>/assets/images/thumbs/banner-img.png" alt="">

                </div>
            </div>
            <div class="account__right padding-t-120 flx-align">
                <div class="account-content">
                    <a href="index.html" class="logo mb-64">
                        <img src="<?= ROOT ?>/assets/images/logo/logo.png" alt="">
                    </a>
                    <div class="card register-card">
                        <div class="card-header">Creation du nouveau compte</div>
                        <div class="card-body">
                            <form action="<?= ROOT ?>/Utilisateurs/ajouter_utilisateur" method="POST" autocomplete="off">
                                <?php $this->view("set_flash"); ?>
                                <input type="text" name="fake_username" autocomplete="username" class="d-none" tabindex="-1">
                                <input type="password" name="fake_password" autocomplete="new-password" class="d-none" tabindex="-1">
                                <div class="row gy-4">

                            <div class="col-sm-6 col-xs-6">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Prénom</label>
                                <div class="position-relative">
                                    <input type="text" name="prenom" class="common-input common-input--bg common-input--withIcon" placeholder="Prénom" autocomplete="given-name" required>
                                    <span class="input-icon"><img src="<?= ROOT ?>/assets/images/icons/user-icon.svg"></span>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xs-6">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Nom</label>
                                <div class="position-relative">
                                    <input type="text" name="nom" class="common-input common-input--bg common-input--withIcon" placeholder="Nom" autocomplete="family-name" required>
                                    <span class="input-icon"><img src="<?= ROOT ?>/assets/images/icons/user-icon.svg"></span>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Email</label>
                                <div class="position-relative">
                                    <input type="email" name="email" id="email" class="common-input common-input--bg common-input--withIcon" placeholder="votre.email@exemple.com" autocomplete="off" readonly required>
                                    <span class="input-icon"><img src="<?= ROOT ?>/assets/images/icons/envelope-icon.svg"></span>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xs-6">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Université / Établissement</label>

                              
                                <select name="universite_id" id="universite" class="common-input common-input--bg" required>
    <option value="">Choisir université</option>
    <?php foreach(($universites ?? []) as $u): ?>
        <option value="<?= $u->id_universite ?>"><?= $u->nom_universite ?></option>
    <?php endforeach; ?>
    <option value="autre">Autre établissement (privé)</option>
</select>

                            </div>

                            <div class="col-sm-6 col-xs-6">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Faculté / Institut</label>

<select name="faculte_id" id="faculte" class="common-input common-input--bg">
    <option value="">Choisir faculté / institut</option>
</select>
                            </div>

                            <div class="col-sm-6 col-xs-6 d-none" id="autreEtablissementWrap">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Nom de l'établissement privé</label>
                                <input type="text" name="autre_etablissement" id="autreEtablissement" class="common-input common-input--bg" placeholder="Ex: Institut X">
                            </div>

                            <div class="col-sm-6 col-xs-6 d-none" id="autreDepartementWrap">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Département / Institut (optionnel)</label>
                                <input type="text" name="autre_departement" id="autreDepartement" class="common-input common-input--bg" placeholder="Ex: Département Informatique">
                            </div>

                            <div class="col-12">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Filière</label>
                                <input type="text" name="filiere" id="filiere" class="common-input common-input--bg" placeholder="Ex: Informatique, Droit, Médecine" autocomplete="off" readonly required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Mot de passe</label>

                                <input type="password" name="password" class="common-input common-input--bg" placeholder="Mot de passe" autocomplete="new-password" required>

                            </div>

                            <div class="col-md-6">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Confirmation</label>

                                <input type="password" name="password_confirm" class="common-input common-input--bg" placeholder="Confirmer le mot de passe" autocomplete="new-password" required>

                            </div>

                            <div class="col-sm-12">
                                <button type="submit" name="submit" class="btn btn-primary btn-lg w-100 pill">
                                    Créer le compte
                                </button>
                            </div>

                                </div>

                            </form>
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

</html>