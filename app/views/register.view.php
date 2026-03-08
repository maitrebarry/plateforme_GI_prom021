<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Inscription']); ?>

<body>
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
                    <h4 class="account-content__title mb-48 text-capitalize">Creation du nouveau compte</h4>
                    <form action="<?= ROOT ?>/Utilisateurs/ajouter_utilisateur" method="POST">
                        <?php $this->view("set_flash"); ?>
                        <div class="row gy-4">

                            <div class="col-sm-6 col-xs-6">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Prénom</label>
                                <div class="position-relative">
                                    <input type="text" name="prenom" class="common-input common-input--bg common-input--withIcon" placeholder="Prénom" required>
                                    <span class="input-icon"><img src="<?= ROOT ?>/assets/images/icons/user-icon.svg"></span>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xs-6">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Nom</label>
                                <div class="position-relative">
                                    <input type="text" name="nom" class="common-input common-input--bg common-input--withIcon" placeholder="Nom" required>
                                    <span class="input-icon"><img src="<?= ROOT ?>/assets/images/icons/user-icon.svg"></span>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Email</label>
                                <div class="position-relative">
                                    <input type="email" name="email" class="common-input common-input--bg common-input--withIcon" placeholder="infoname@mail.com" required>
                                    <span class="input-icon"><img src="<?= ROOT ?>/assets/images/icons/envelope-icon.svg"></span>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xs-6">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Université</label>

                              
                                <select name="universite_id" id="universite" class="common-input common-input--bg" required>
    <option value="">Choisir université</option>
    <?php foreach($universites as $u): ?>
        <option value="<?= $u->id_universite ?>"><?= $u->nom_universite ?></option>
    <?php endforeach; ?>
</select>

                            </div>

                            <div class="col-sm-6 col-xs-6">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Filière</label>

<select name="filiere_id" id="filiere"class="common-input common-input--bg" required>
    <option value="">Choisir filière</option>
</select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Mot de passe</label>

                                <input type="password" name="password" class="common-input common-input--bg" placeholder="Mot de passe" required>

                            </div>

                            <div class="col-md-6">
                                <label class="form-label mb-2 font-18 font-heading fw-600">Confirmation</label>

                                <input type="password" name="password_confirm" class="common-input common-input--bg" placeholder="Confirmer le mot de passe" required>

                            </div>

                            <div class="col-sm-12">
                                <button type="submit" name="submit" class="btn btn-main btn-lg w-100 pill">
                                    Créer le compte
                                </button>
                            </div>

                        </div>

                    </form>
                </div>
            </div>
        </section>
        <?php $this->view('Partials/footer'); ?>
    </main>

    <?php $this->view('Partials/scripts'); ?>
    <script>
document.getElementById("universite").addEventListener("change", function() {
    let universite_id = this.value;

    fetch("<?= ROOT ?>/Universite/getFilieres/" + universite_id)
    .then(response => response.json())
    .then(data => {
        let filiere = document.getElementById("filiere");
        filiere.innerHTML = '<option value="">Choisir filière</option>';
        data.forEach(function(item) {
            filiere.innerHTML += `<option value="${item.id_filiere}">${item.nom_filiere}</option>`;
        });
    });
});
</script>
</body>

</html>