<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Profile']); ?>

<body>
    <?php $this->view('Partials/global-shell'); ?>
    <?php $this->view('Partials/mobile-menu'); ?>
    <?php $this->view('Partials/header'); ?>
    <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

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
                                        <img src="<?= ROOT ?>/image_profile/<?= htmlspecialchars($_SESSION['image'] ?? 'default.png'); ?>" class="rounded-circle" width="140" height="140" alt="Photo de profil" id="profileImage">
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
                                <h5 class="profile-info__name mb-1"><?= $_SESSION['nom'] . ' ' . $_SESSION['prenom']; ?></h5>
                                <span class="profile-info__designation font-14"><?= $_SESSION['role']; ?></span>
                            </div>

                            <ul class="profile-info-list">
                                <li class="profile-info-list__item">
                                    <span class="profile-info-list__content flx-align flex-nowrap gap-2">
                                        <img src="assets/images/icons/profile-info-icon1.svg" alt="" class="icon">
                                        <span class="text text-heading fw-500">Username</span>
                                    </span>
                                    <span class="profile-info-list__info"><?= $_SESSION['nom'] ?? 'N/A' ?></span>
                                </li>
                                <li class="profile-info-list__item">
                                    <span class="profile-info-list__content flx-align flex-nowrap gap-2">
                                        <img src="assets/images/icons/profile-info-icon2.svg" alt="" class="icon">
                                        <span class="text text-heading fw-500">Email</span>
                                    </span>
                                    <span class="profile-info-list__info"><?= $_SESSION['email'] ?? 'N/A' ?></span>
                                </li>
                                <li class="profile-info-list__item">
                                    <span class="profile-info-list__content flx-align flex-nowrap gap-2">
                                        <img src="assets/images/icons/profile-info-icon3.svg" alt="" class="icon">
                                        <span class="text text-heading fw-500">Phone</span>
                                    </span>
                                    <span class="profile-info-list__info"><?= $_SESSION['contact'] ?? 'N/A' ?></span>
                                </li>
                                <li class="profile-info-list__item">
                                    <span class="profile-info-list__content flx-align flex-nowrap gap-2">
                                        <img src="assets/images/icons/profile-info-icon4.svg" alt="" class="icon">
                                        <span class="text text-heading fw-500">Universite</span>
                                    </span>
                                    <span class="profile-info-list__info"><?= $_SESSION['universite'] ?? 'N/A' ?></span>
                                </li>
                            </ul>

                        </div>
                    </div>
                    <div class="col-xxl-9 col-xl-8">
                        <div class="dashboard-card">
                            <div class="dashboard-card__header pb-0">
                                <ul class="nav tab-bordered nav-pills" id="pills-tab" role="tablist">
                                    <!-- <li class="nav-item" role="presentation">
                          <button class="nav-link font-18 font-heading active" id="pills-personalInfo-tab" data-bs-toggle="pill" data-bs-target="#pills-personalInfo" type="button" role="tab" aria-controls="pills-personalInfo" aria-selected="true">Personal Info</button>
                        </li> -->

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link font-18 font-heading active" id="pills-changePassword-tab" data-bs-toggle="pill" data-bs-target="#pills-changePassword" type="button" role="tab" aria-controls="pills-changePassword" aria-selected="false">Change Password</button>
                                    </li>
                                </ul>
                            </div>

                            <div class="profile-info-content">
                                <div class="tab-content" id="pills-tabContent">
                                    <!-- <div class="tab-pane fade show active" id="pills-personalInfo" role="tabpanel" aria-labelledby="pills-personalInfo-tab" tabindex="0">
                                        <form action="#" autocomplete="off">
                                            <div class="row gy-4">
                                                <div class="col-sm-6 col-xs-6">
                                                    <label for="fName" class="form-label mb-2 font-18 font-heading fw-600">First Name</label>
                                                    <input type="text" class="common-input border" name="nom" id="fName" placeholder="First Name">
                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <label for="lastNamee" class="form-label mb-2 font-18 font-heading fw-600">Last Name</label>
                                                    <input type="text" class="common-input border" name="prenom" id="lastNamee" placeholder="Last Name">
                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <label for="phonee" class="form-label mb-2 font-18 font-heading fw-600">Phone Number</label>
                                                    <input type="tel" class="common-input border" name="contact" id="phonee" placeholder="Phone Number">
                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <label for="emailAdddd" class="form-label mb-2 font-18 font-heading fw-600">Email Address</label>
                                                    <input type="email" class="common-input border" name="email" id="emailAdddd" placeholder="Email Address">
                                                </div>


                                                <div class="col-sm-12 text-end">
                                                    <button class="btn btn-main btn-lg pill mt-4"> Update Profile</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div> -->

                                    <div class="tab-pane fade fade show active" id="pills-changePassword" role="tabpanel" aria-labelledby="pills-changePassword-tab" tabindex="0">
                                        <form action="#" autocomplete="off" method="POST"
                                          <?php $this->view("set_flash"); ?>>
                                            <div class="row gy-4">

                                                <div class="col-12">
                                                    <label for="current-password" class="form-label mb-2 font-18 font-heading fw-600">Current Password</label>
                                                    <div class="position-relative">
                                                        <input type="password" class="common-input common-input--withIcon common-input--withLeftIcon " name="ancien_mot_de_passe"  placeholder="************">
                                                        <span class="input-icon input-icon--left"><img src="assets/images/icons/key-icon.svg" alt=""></span>
                                                        <span class="input-icon password-show-hide fas fa-eye la-eye-slash toggle-password-two" id="#current-password"></span>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-xs-6">
                                                    <label for="new-password" class="form-label mb-2 font-18 font-heading fw-600">New Password</label>
                                                    <div class="position-relative">
                                                        <input type="password" class="common-input common-input--withIcon common-input--withLeftIcon " name="nouveau_mot_de_passe"  placeholder="************">
                                                        <span class="input-icon input-icon--left"><img src="assets/images/icons/lock-two.svg" alt=""></span>
                                                        <span class="input-icon password-show-hide fas fa-eye la-eye-slash toggle-password-two" id="#new-password"></span>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-xs-6">
                                                    <label for="confirm-password" class="form-label mb-2 font-18 font-heading fw-600">Confirm New Password</label>
                                                    <div class="position-relative">
                                                        <input type="password" class="common-input common-input--withIcon common-input--withLeftIcon " name="comfirme_mot_de_passe"  placeholder="************">
                                                        <span class="input-icon input-icon--left"><img src="assets/images/icons/lock-two.svg" alt=""></span>
                                                        <span class="input-icon password-show-hide fas fa-eye la-eye-slash toggle-password-two" id="#confirm-password"></span>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 text-end">
                                                    <button class="btn btn-main btn-lg " type="submit" name="modifier"> Update Password</button>
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