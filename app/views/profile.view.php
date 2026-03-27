<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Profile']); ?>

<body>
    <?php $this->view('Partials/global-shell'); ?>
    <?php $this->view('Partials/mobile-menu'); ?>
    <?php $this->view('Partials/header'); ?>
    <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

    <?php
        $profileUser = $user ?? null;
        $profilePrenom = htmlspecialchars($profileUser->prenom ?? ($_SESSION['prenom'] ?? ''), ENT_QUOTES, 'UTF-8');
        $profileNom = htmlspecialchars($profileUser->nom ?? ($_SESSION['nom'] ?? ''), ENT_QUOTES, 'UTF-8');
        $profileEmail = htmlspecialchars($profileUser->email ?? ($_SESSION['email'] ?? ''), ENT_QUOTES, 'UTF-8');
        $rawContact = $profileUser->contact ?? ($_SESSION['contact'] ?? '');
        $contactDigits = preg_replace('/\D+/', '', (string)$rawContact);
        $profileContact = $contactDigits !== '' ? trim(preg_replace('/(\d{2})(?=\d)/', '$1 ', $contactDigits)) : '';
        $profileUniversite = htmlspecialchars($profileUser->universite ?? ($_SESSION['universite'] ?? ''), ENT_QUOTES, 'UTF-8');
        $profileFaculte = htmlspecialchars($profileUser->faculte ?? ($_SESSION['faculte'] ?? ''), ENT_QUOTES, 'UTF-8');
        $profileFiliere = htmlspecialchars($profileUser->filiere ?? ($_SESSION['filiere'] ?? ''), ENT_QUOTES, 'UTF-8');
        $profileImage = htmlspecialchars(basename((string) ($_SESSION['image'] ?? ($profileUser->image ?? 'default.png'))), ENT_QUOTES, 'UTF-8');
        $sessionFullName = htmlspecialchars(trim((string) (($_SESSION['nom'] ?? '') . ' ' . ($_SESSION['prenom'] ?? ''))), ENT_QUOTES, 'UTF-8');
        $sessionRole = htmlspecialchars((string) ($_SESSION['role'] ?? 'N/A'), ENT_QUOTES, 'UTF-8');
        $sessionNom = htmlspecialchars((string) ($_SESSION['nom'] ?? 'N/A'), ENT_QUOTES, 'UTF-8');
        $sessionEmail = htmlspecialchars((string) ($_SESSION['email'] ?? 'N/A'), ENT_QUOTES, 'UTF-8');
        $sessionContact = htmlspecialchars((string) ($_SESSION['contact'] ?? 'N/A'), ENT_QUOTES, 'UTF-8');
        $sessionUniversite = htmlspecialchars((string) ($_SESSION['universite'] ?? 'N/A'), ENT_QUOTES, 'UTF-8');
    ?>

    <main class="change-gradient">

        <div class="cover-photo position-relative z-index-1 overflow-hidden">
            <div class="avatar-upload">
                <div class="avatar-edit">
                    <input type='file' id="imageUploadTwo" accept=".png, .jpg, .jpeg">
                    <label for="imageUploadTwo">
                        <span class="icon"> <img src="assets/images/icons/camera-two.svg" alt=""> </span>
                        <span class="text">Change Cover</span>
                    </label>
                </div>
                <div class="avatar-preview">
                    <div id="imagePreviewTwo">
                    </div>
                </div>
            </div>
        </div>
        <!-- Cover Photo End -->


        <div class="dashboard-body__content profile-content-wrapper z-index-1 position-relative mt--100">
            <!-- Profile Content Start -->
            <div class="profile">
                <div class="row gy-4">
                    <div class="col-xxl-3 col-xl-4">
                        <div class="profile-info">
                            <div class="profile-info__inner mb-40 text-center">
<form action="<?= ROOT ?>/Profiles/modifier_image" method="post" enctype="multipart/form-data" id="avatarForm" class="text-center">
                                <div class="avatar-upload mb-24">
                                    <div class="avatar-preview mb-3">
                                        <img src="<?= ROOT ?>/image_profile/<?= $profileImage ?>" class="rounded-circle" width="140" height="140" alt="Photo de profil" id="profileImage">
                                    </div>
                                    <input type="file" id="fileInput" name="newAvatar" accept="image/*" class="d-none">
                                    <button type="button" class="btn btn-outline-primary w-100 mb-2" id="triggerAvatarUpload">
                                        <i class='bx bx-image-add me-1'></i>
                                        Choisir une nouvelle photo
                                    </button>
                                    <button type="submit" class="btn btn-primary w-100" id="saveAvatarBtn" disabled>
                                        <i class='bx bx-cloud-upload me-1'></i>
                                        Enregistrer la modification
                                    </button>
                                    <small class="d-block mt-2 text-muted" id="avatarHelperText">Formats acceptés: JPG, PNG, GIF, WEBP (max 5 Mo)</small>
                                </div>
</form>
                                <h5 class="profile-info__name mb-1"><?= $sessionFullName !== '' ? $sessionFullName : 'N/A'; ?></h5>
                                <span class="profile-info__designation font-14"><?= $sessionRole; ?></span>
                            </div>

                            <ul class="profile-info-list">
                                <li class="profile-info-list__item">
                                    <span class="profile-info-list__content flx-align flex-nowrap gap-2">
                                        <img src="assets/images/icons/profile-info-icon1.svg" alt="" class="icon">
                                        <span class="text text-heading fw-500">Username</span>
                                    </span>
                                    <span class="profile-info-list__info"><?= $sessionNom ?></span>
                                </li>
                                <li class="profile-info-list__item">
                                    <span class="profile-info-list__content flx-align flex-nowrap gap-2">
                                        <img src="assets/images/icons/profile-info-icon2.svg" alt="" class="icon">
                                        <span class="text text-heading fw-500">Email</span>
                                    </span>
                                    <span class="profile-info-list__info"><?= $sessionEmail ?></span>
                                </li>
                                <li class="profile-info-list__item">
                                    <span class="profile-info-list__content flx-align flex-nowrap gap-2">
                                        <img src="assets/images/icons/profile-info-icon3.svg" alt="" class="icon">
                                        <span class="text text-heading fw-500">Phone</span>
                                    </span>
                                    <span class="profile-info-list__info"><?= $sessionContact ?></span>
                                </li>
                                <li class="profile-info-list__item">
                                    <span class="profile-info-list__content flx-align flex-nowrap gap-2">
                                        <img src="assets/images/icons/profile-info-icon4.svg" alt="" class="icon">
                                        <span class="text text-heading fw-500">Universite</span>
                                    </span>
                                    <span class="profile-info-list__info"><?= $sessionUniversite ?></span>
                                </li>
                            </ul>

                        </div>
                    </div>
                    <div class="col-xxl-9 col-xl-8">
                        <div class="dashboard-card">
                            <div class="dashboard-card__header pb-0">
                                <ul class="nav tab-bordered nav-pills" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link font-18 font-heading active" id="pills-personalInfo-tab" data-bs-toggle="pill" data-bs-target="#pills-personalInfo" type="button" role="tab" aria-controls="pills-personalInfo" aria-selected="true">Informations personnelles</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link font-18 font-heading" id="pills-changePassword-tab" data-bs-toggle="pill" data-bs-target="#pills-changePassword" type="button" role="tab" aria-controls="pills-changePassword" aria-selected="false">Modifier le mot de passe</button>
                                    </li>
                                </ul>
                            </div>

                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-personalInfo" role="tabpanel" aria-labelledby="pills-personalInfo-tab" tabindex="0">
                                        <form action="<?= ROOT ?>/Profiles/appercu" autocomplete="off" method="POST">
                                            <input type="hidden" name="update_profile_info" value="1">
                                            <div class="row gy-4">
                                                <div class="col-sm-6 col-xs-6">
                                                    <label class="form-label mb-2 font-18 font-heading fw-600">Prénom</label>
                                                    <input type="text" class="common-input border" name="user_firstname" value="<?= $profilePrenom ?>" required>
                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <label class="form-label mb-2 font-18 font-heading fw-600">Nom</label>
                                                    <input type="text" class="common-input border" name="user_lastname" value="<?= $profileNom ?>" required>
                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <label class="form-label mb-2 font-18 font-heading fw-600">Email</label>
                                                    <input type="email" class="common-input border" name="user_email" value="<?= $profileEmail ?>" required>
                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <label class="form-label mb-2 font-18 font-heading fw-600">Contact</label>
                                                    <input type="text" class="common-input border" name="user_contact" value="<?= htmlspecialchars($profileContact, ENT_QUOTES, 'UTF-8') ?>" placeholder="76 56 23 17" inputmode="numeric">
                                                    <small class="text-muted">8 chiffres (ex: 76 56 23 17)</small>
                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <label class="form-label mb-2 font-18 font-heading fw-600">Université</label>
                                                    <input type="text" class="common-input border" name="user_universite" value="<?= $profileUniversite ?>">
                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <label class="form-label mb-2 font-18 font-heading fw-600">Faculté / Institut</label>
                                                    <input type="text" class="common-input border" name="user_faculte" value="<?= $profileFaculte ?>">
                                                </div>
                                                <div class="col-sm-12">
                                                    <label class="form-label mb-2 font-18 font-heading fw-600">Filière</label>
                                                    <input type="text" class="common-input border" name="user_filiere" value="<?= $profileFiliere ?>">
                                                </div>
                                                <div class="col-sm-12 text-end">
                                                    <button class="btn btn-main btn-lg pill mt-4" type="submit">Enregistrer les modifications</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="pills-changePassword" role="tabpanel" aria-labelledby="pills-changePassword-tab" tabindex="0">
                                        <form action="<?= ROOT ?>/Profiles/appercu" autocomplete="off" method="POST">
                                            <div class="row gy-4">
                                                <div class="col-12">
                                                    <label class="form-label mb-2 font-18 font-heading fw-600">Mot de passe actuel</label>
                                                    <div class="position-relative">
                                                        <input type="password" class="common-input common-input--withIcon common-input--withLeftIcon" name="ancien_mot_de_passe" placeholder="************" required>
                                                        <span class="input-icon input-icon--left"><img src="assets/images/icons/key-icon.svg" alt=""></span>
                                                        <span class="input-icon password-show-hide fas fa-eye la-eye-slash toggle-password-two" id="#current-password"></span>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-xs-6">
                                                    <label class="form-label mb-2 font-18 font-heading fw-600">Nouveau mot de passe</label>
                                                    <div class="position-relative">
                                                        <input type="password" class="common-input common-input--withIcon common-input--withLeftIcon" name="nouveau_mot_de_passe" placeholder="************" required>
                                                        <span class="input-icon input-icon--left"><img src="assets/images/icons/lock-two.svg" alt=""></span>
                                                        <span class="input-icon password-show-hide fas fa-eye la-eye-slash toggle-password-two" id="#new-password"></span>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-xs-6">
                                                    <label class="form-label mb-2 font-18 font-heading fw-600">Confirmer le mot de passe</label>
                                                    <div class="position-relative">
                                                        <input type="password" class="common-input common-input--withIcon common-input--withLeftIcon" name="comfirme_mot_de_passe" placeholder="************" required>
                                                        <span class="input-icon input-icon--left"><img src="assets/images/icons/lock-two.svg" alt=""></span>
                                                        <span class="input-icon password-show-hide fas fa-eye la-eye-slash toggle-password-two" id="#confirm-password"></span>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 text-end">
                                                    <button class="btn btn-main btn-lg" type="submit" name="modifier">Mettre à jour le mot de passe</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <?php $this->view('Partials/footer'); ?>
    </main>

    <?php $this->view('Partials/scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput = document.getElementById('fileInput');
            const triggerBtn = document.getElementById('triggerAvatarUpload');
            const saveBtn = document.getElementById('saveAvatarBtn');
            const helperText = document.getElementById('avatarHelperText');
            const previewImage = document.getElementById('profileImage');

            if (!fileInput || !triggerBtn || !saveBtn || !previewImage) {
                return;
            }

            triggerBtn.addEventListener('click', () => fileInput.click());

            fileInput.addEventListener('change', () => {
                if (!fileInput.files || fileInput.files.length === 0) {
                    helperText.textContent = 'Formats acceptés: JPG, PNG, GIF, WEBP (max 5 Mo)';
                    saveBtn.disabled = true;
                    return;
                }

                const file = fileInput.files[0];
                helperText.textContent = `Fichier sélectionné: ${file.name}`;
                saveBtn.disabled = false;

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        previewImage.src = event.target?.result || previewImage.src;
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</body>

</html>
