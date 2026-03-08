<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Dashboard administrateur']); ?>


<body>
    <?php $this->view('Partials/global-shell'); ?>
    <?php $this->view('Partials/mobile-menu'); ?>

    <section class="dashboard">
        <div class="dashboard__inner d-flex">
            <?php $this->view('Partials/dashboard-sidebar'); ?>
            <div class="dashboard-body">
                <?php $this->view('Partials/dashboard-nav'); ?>
                <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

                <div class="dashboard-body__content p-4">
                    <div class="col-12">
                        <div class="card common-card border border-gray-five">
                            <div class="card-body">
                                 <?php $this->view("set_flash"); ?>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="card-title">Liste des utilisateurs</h5>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#large">
                                        <i class="bx bx-plus"></i> Ajouter un utilisateur
                                    </button>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table text-body mt--24">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Nom & Prénom</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>Université - Faculté - Filière</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($liste as $listes): ?>
                                                    <tr>
                                                        <td><?= $listes->nom . ' ' . $listes->prenom ?></td>
                                                        <td><?= $listes->email ?></td>
                                                        <td><?= $listes->role ?></td>
                                                        <td><?= trim(($listes->universite ?? 'N/A') . ' - ' . ($listes->faculte ?? 'N/A') . ' - ' . ($listes->filiere ?? 'N/A')) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Ajouter utilisateur -->
                    <div class="modal fade" id="large" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form method="post" action="<?= ROOT ?>/Utilisateurs/liste_utilisateur">
                                    <div class="modal-header bg-primary text-white justify-content-center position-relative">
                                        <h5 class="modal-title text-center w-100 text-white" id="largeModalLabel">Ajouter un utilisateur</h5>
                                        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
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
                                            <label class="form-label mb-2 font-18 font-heading fw-600">Contact</label>
                                            <div class="position-relative">
                                                <input type="tel" name="contact_utilisateur" id="contact_utilisateur" class="common-input common-input--bg common-input--withIcon" placeholder="76 56 23 17" inputmode="numeric" maxlength="11" pattern="[0-9]{2}(\s?[0-9]{2}){3}" required>
                                                <span class="input-icon"><img src="<?= ROOT ?>/assets/images/icons/phone-icon.svg"></span>
                                            </div>
                                            <small class="text-muted d-block mt-2">Saisir 8 chiffres. Format accepté: 76562317 ou 76 56 23 17.</small>
                                        </div>
                                        <div class="col-sm-6 col-xs-6">
                                            <label class="form-label mb-2 font-18 font-heading fw-600">Université</label>

                                            <select class="common-input common-input--bg" name="universite_id" id="universite_id_admin" required>
                                                <option value="">Choisir une université</option>
                                                <?php foreach (($universites ?? []) as $universite): ?>
                                                    <option value="<?= (int) ($universite->id_universite ?? 0) ?>">
                                                        <?= htmlspecialchars($universite->nom_universite ?? '') ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>

                                        </div>
                                        <div class="col-sm-6 col-xs-6">
                                            <label class="form-label mb-2 font-18 font-heading fw-600">Faculté / Institut</label>
                                            <select class="common-input common-input--bg" name="faculte_id" id="faculte_id_admin" required disabled>
                                                <option value="">Sélectionnez d'abord une université</option>
                                            </select>
                                        </div>
                                         <div class="col-12">
                                             <label class="form-label mb-2 font-18 font-heading fw-600">Role</label>
                                            <select name="role" class="common-input common-input--bg" required>
                                                <option value="admin">Administrateur</option>
                                                <option value="der">DER</option>
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
                                        <div class="form-group mb-3">
                                       
                                    </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="save_user" class="btn btn-primary">Enregistrer</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    </div>
                                </form>
                                <!-- <form action="<?= ROOT ?>/Utilisateurs/ajouter_utilisateur" method="POST">
                                   
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

                                            <select class="common-input common-input--bg" name="universite" required>
                                                <option value="">Choisir une université</option>
                                                <option value="Université de Ségou">Université de Ségou</option>
                                                <option value="Université de Bamako">Université de Bamako</option>
                                                <option value="USTTB">USTTB</option>
                                                <option value="Université de Sikasso">Université de Sikasso</option>
                                            </select>

                                        </div>

                                        <div class="col-sm-6 col-xs-6">
                                            <label class="form-label mb-2 font-18 font-heading fw-600">Role</label>
                                            <select name="role" class="form-control" required>
                                                <option value="Administrateur">Administrateur</option>
                                                <option value="Utilisateur">Utilisateur</option>
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

                                </form> -->
                            </div>
                        </div>
                    </div>

                    <?php $this->view('Partials/scripts'); ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const universiteSelect = document.getElementById('universite_id_admin');
                            const faculteSelect = document.getElementById('faculte_id_admin');
                            const contactInput = document.getElementById('contact_utilisateur');

                            const formatContactValue = (value) => {
                                const digits = value.replace(/\D/g, '').slice(0, 8);
                                return digits.replace(/(\d{2})(?=\d)/g, '$1 ').trim();
                            };

                            if (contactInput) {
                                contactInput.addEventListener('input', function () {
                                    this.value = formatContactValue(this.value);
                                });
                            }

                            if (universiteSelect && faculteSelect) {
                                universiteSelect.addEventListener('change', async function () {
                                    const universiteId = this.value;
                                    faculteSelect.innerHTML = '<option value="">Chargement...</option>';
                                    faculteSelect.disabled = true;

                                    if (!universiteId) {
                                        faculteSelect.innerHTML = '<option value="">Sélectionnez d\'abord une université</option>';
                                        return;
                                    }

                                    try {
                                        const response = await fetch('<?= ROOT ?>/Utilisateurs/getFacultes/' + universiteId);
                                        const facultes = await response.json();

                                        if (!Array.isArray(facultes) || facultes.length === 0) {
                                            faculteSelect.innerHTML = '<option value="">Aucune faculté ou institut disponible</option>';
                                            return;
                                        }

                                        faculteSelect.innerHTML = '<option value="">Choisir une faculté / un institut</option>';
                                        facultes.forEach(function (faculte) {
                                            const option = document.createElement('option');
                                            option.value = faculte.id_faculte;
                                            option.textContent = faculte.nom_faculte;
                                            faculteSelect.appendChild(option);
                                        });
                                        faculteSelect.disabled = false;
                                    } catch (error) {
                                        faculteSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                                    }
                                });
                            }
                        });
                    </script>

                    <?php $this->view('Partials/dashboard-footer'); ?>
                </div>
            </div>
    </section>

</body>

</html>