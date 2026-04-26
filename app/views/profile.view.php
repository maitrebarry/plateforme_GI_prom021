<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Profile']); ?>

<body>
    <?php $this->view('Partials/global-shell'); ?>
    <?php $this->view('Partials/mobile-menu'); ?>

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

    <section class="dashboard">
        <div class="dashboard__inner d-flex">
            <?php $this->view('Partials/dashboard-sidebar'); ?>
            
            <div class="dashboard-body">
                <?php $this->view('Partials/dashboard-nav'); ?>
                
                <div class="dashboard-body__content p-4">
                    <style>
                    :root {
                        --primary-color: #6366f1;
                        --primary-hover: #4f46e5;
                        --bg-light: #f1f5f9;
                        --text-main: #0f172a;
                        --text-muted: #64748b;
                        --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                    }

                    .dashboard-body__content {
                        background-color: var(--bg-light);
                    }

                    .profile-header {
                        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                        border-radius: 24px;
                        padding: 3rem 2rem;
                        color: white;
                        margin-bottom: 2rem;
                        position: relative;
                        overflow: hidden;
                    }

                    .profile-header::after {
                        content: '';
                        position: absolute;
                        top: -50%;
                        right: -10%;
                        width: 300px;
                        height: 300px;
                        background: rgba(99, 102, 241, 0.1);
                        border-radius: 50%;
                    }

                    .common-card {
                        border: none;
                        border-radius: 20px;
                        box-shadow: var(--card-shadow);
                        background: #ffffff;
                        padding: 2rem;
                    }

                    .avatar-container {
                        position: relative;
                        width: 150px;
                        height: 150px;
                        margin: 0 auto 1.5rem;
                    }

                    .avatar-img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        border-radius: 50%;
                        border: 5px solid white;
                        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                    }

                    .avatar-edit-btn {
                        position: absolute;
                        bottom: 5px;
                        right: 5px;
                        background: var(--primary-color);
                        color: white;
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border: 3px solid white;
                        cursor: pointer;
                        transition: 0.3s;
                    }

                    .avatar-edit-btn:hover { transform: scale(1.1); background: var(--primary-hover); }

                    .form-label {
                        font-weight: 700;
                        color: var(--text-main);
                        font-size: 0.9rem;
                        margin-bottom: 0.5rem;
                    }

                    .form-control {
                        border-radius: 12px;
                        border: 1.5px solid #e2e8f0;
                        padding: 0.75rem 1rem;
                        font-size: 0.95rem;
                        transition: all 0.3s;
                    }

                    .form-control:focus {
                        border-color: var(--primary-color);
                        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
                        outline: none;
                    }

                    .nav-pills-custom .nav-link {
                        color: var(--text-muted);
                        font-weight: 700;
                        padding: 0.75rem 1.5rem;
                        border-radius: 12px;
                        transition: 0.3s;
                    }

                    .nav-pills-custom .nav-link.active {
                        background: var(--primary-color);
                        color: white !important;
                        box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2);
                    }

                    .btn-save {
                        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
                        color: white !important;
                        padding: 0.75rem 2rem;
                        border-radius: 12px;
                        font-weight: 700;
                        border: none;
                        box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2);
                        transition: 0.3s;
                    }

                    .btn-save:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
                    }

                    .info-row {
                        padding: 1rem 0;
                        border-bottom: 1px solid #f1f5f9;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    }
                    .info-row:last-child { border-bottom: none; }
                    .info-label { font-weight: 700; color: var(--text-muted); font-size: 0.9rem; }
                    .info-value { font-weight: 800; color: var(--text-main); }
                    </style>

                    <div class="profile-header">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h1 class="text-white fw-bold mb-2">Mon Profil</h1>
                                <p class="opacity-75 mb-0">Gérez vos informations personnelles et la sécurité de votre compte.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row gy-4">
                        <!-- Sidebar Info -->
                        <div class="col-xl-4">
                            <div class="common-card text-center">
                                <form action="<?= ROOT ?>/Profiles/modifier_image" method="post" enctype="multipart/form-data" id="avatarForm">
                                    <div class="avatar-container">
                                        <img src="<?= ROOT ?>/image_profile/<?= $profileImage ?>" class="avatar-img" id="profileImage">
                                        <label for="fileInput" class="avatar-edit-btn">
                                            <i class='bx bx-camera'></i>
                                        </label>
                                        <input type="file" id="fileInput" name="newAvatar" accept="image/*" class="d-none">
                                    </div>
                                    <h4 class="fw-bold mb-1"><?= $sessionFullName ?></h4>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-4"><?= $sessionRole ?></span>
                                    
                                    <button type="submit" class="btn btn-primary w-100 pill mb-3 d-none" id="saveAvatarBtn">
                                        Confirmer le changement
                                    </button>
                                </form>

                                <div class="mt-4 text-start">
                                    <div class="info-row">
                                        <span class="info-label">Email</span>
                                        <span class="info-value small"><?= $sessionEmail ?></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Contact</span>
                                        <span class="info-value"><?= $sessionContact ?></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Université</span>
                                        <span class="info-value text-end small"><?= $sessionUniversite ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Main Tabs -->
                        <div class="col-xl-8">
                            <div class="common-card">
                                <ul class="nav nav-pills nav-pills-custom mb-4" id="profileTabs">
                                    <li class="nav-item">
                                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#personalInfo">Informations</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#security">Sécurité</button>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <!-- Personal Info Tab -->
                                    <div class="tab-pane fade show active" id="personalInfo">
                                        <?php $this->view('set_flash'); ?>
                                        <form action="<?= ROOT ?>/Profiles/appercu" method="POST" class="row g-3">
                                            <input type="hidden" name="update_profile_info" value="1">
                                            <div class="col-md-6">
                                                <label class="form-label">Prénom</label>
                                                <input type="text" class="form-control" name="user_firstname" value="<?= $profilePrenom ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Nom</label>
                                                <input type="text" class="form-control" name="user_lastname" value="<?= $profileNom ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" name="user_email" value="<?= $profileEmail ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Contact</label>
                                                <input type="text" class="form-control" name="user_contact" value="<?= $profileContact ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Université</label>
                                                <input type="text" class="form-control" name="user_universite" value="<?= $profileUniversite ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Faculté / Institut</label>
                                                <input type="text" class="form-control" name="user_faculte" value="<?= $profileFaculte ?>">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Filière</label>
                                                <input type="text" class="form-control" name="user_filiere" value="<?= $profileFiliere ?>">
                                            </div>
                                            <div class="col-12 text-end mt-4">
                                                <button type="submit" class="btn-save">Enregistrer les modifications</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Security Tab -->
                                    <div class="tab-pane fade" id="security">
                                        <form action="<?= ROOT ?>/Profiles/appercu" method="POST" class="row g-4">
                                            <div class="col-12">
                                                <label class="form-label">Mot de passe actuel</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0"><i class='bx bx-key'></i></span>
                                                    <input type="password" class="form-control border-start-0" name="ancien_mot_de_passe" placeholder="Confirmez pour modifier" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Nouveau mot de passe</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0"><i class='bx bx-lock-alt'></i></span>
                                                    <input type="password" class="form-control border-start-0" name="nouveau_mot_de_passe" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Confirmer nouveau mot de passe</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0"><i class='bx bx-lock-alt'></i></span>
                                                    <input type="password" class="form-control border-start-0" name="comfirme_mot_de_passe" required>
                                                </div>
                                            </div>
                                            <div class="col-12 text-end mt-4">
                                                <button type="submit" name="modifier" class="btn-save">Mettre à jour la sécurité</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php $this->view('Partials/dashboard-footer'); ?>
            </div>
        </div>
    </section>

    <?php $this->view('Partials/scripts'); ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('fileInput');
        const saveBtn = document.getElementById('saveAvatarBtn');
        const previewImage = document.getElementById('profileImage');

        if(fileInput) {
            fileInput.onchange = () => {
                if (fileInput.files && fileInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => previewImage.src = e.target.result;
                    reader.readAsDataURL(fileInput.files[0]);
                    saveBtn.classList.remove('d-none');
                }
            };
        }
    });
    </script>
</body>
</html>
