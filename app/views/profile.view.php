<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Mon profil']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$profileUser = $user ?? null;
$profilePrenom = htmlspecialchars($profileUser->prenom ?? ($_SESSION['prenom'] ?? ''), ENT_QUOTES, 'UTF-8');
$profileNom = htmlspecialchars($profileUser->nom ?? ($_SESSION['nom'] ?? ''), ENT_QUOTES, 'UTF-8');
$profileEmail = htmlspecialchars($profileUser->email ?? ($_SESSION['email'] ?? ''), ENT_QUOTES, 'UTF-8');
$rawContact = $profileUser->contact ?? ($_SESSION['contact'] ?? '');
$contactDigits = preg_replace('/\D+/', '', (string) $rawContact);
$profileContact = $contactDigits !== '' ? trim(preg_replace('/(\d{2})(?=\d)/', '$1 ', $contactDigits)) : '';
$profileUniversite = htmlspecialchars($profileUser->universite ?? ($_SESSION['universite'] ?? ''), ENT_QUOTES, 'UTF-8');
$profileFaculte = htmlspecialchars($profileUser->faculte ?? ($_SESSION['faculte'] ?? ''), ENT_QUOTES, 'UTF-8');
$profileFiliere = htmlspecialchars($profileUser->filiere ?? ($_SESSION['filiere'] ?? ''), ENT_QUOTES, 'UTF-8');
$profileImage = htmlspecialchars(basename((string) ($_SESSION['image'] ?? ($profileUser->image ?? ''))), ENT_QUOTES, 'UTF-8');
$hasRealImg = $profileImage !== '' && $profileImage !== 'default.png';
$fullName = trim($profilePrenom . ' ' . $profileNom);
if ($fullName === '') { $fullName = 'Utilisateur'; }
$initials = strtoupper(mb_substr($profilePrenom !== '' ? $profilePrenom : $profileNom, 0, 1));
$sessionRole = htmlspecialchars((string) ($_SESSION['role'] ?? 'membre'), ENT_QUOTES, 'UTF-8');
$csrf = (string) ($_SESSION['csrf_token'] ?? '');
?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>

            <div class="dashboard-body__content p-3 p-lg-4">
                <style>
                    .pf-hero { position: relative; overflow: hidden; background: linear-gradient(135deg, var(--ds-brand-700), var(--ds-brand-800)); border-radius: var(--ds-radius-xl); padding: 26px; color: #fff; margin-bottom: 22px; }
                    .pf-hero::before { content: ''; position: absolute; top: -70px; right: -50px; width: 260px; height: 260px; border-radius: 50%; background: radial-gradient(circle, rgba(224,168,46,.22), transparent 70%); }
                    .pf-hero h1 { position: relative; z-index: 1; font-family: var(--ds-font-heading); font-weight: 800; font-size: 1.6rem; color: #fff; margin: 0 0 6px; }
                    .pf-hero p { position: relative; z-index: 1; color: rgba(231,240,235,.82); font-size: .95rem; margin: 0; }

                    .pf-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); box-shadow: var(--ds-shadow-sm); padding: 22px; }

                    .pf-avatar { position: relative; width: 132px; height: 132px; margin: 0 auto 16px; }
                    .pf-avatar__img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; border: 4px solid var(--ds-surface); box-shadow: var(--ds-shadow-md); }
                    .pf-avatar__fallback { width: 100%; height: 100%; border-radius: 50%; background: linear-gradient(135deg, var(--ds-brand-500), var(--ds-brand-700)); color: #fff; display: flex; align-items: center; justify-content: center; font-family: var(--ds-font-heading); font-weight: 800; font-size: 3rem; }
                    .pf-avatar__edit { position: absolute; bottom: 4px; right: 4px; background: var(--ds-brand-600); color: #fff; width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid var(--ds-surface); cursor: pointer; font-size: 1.1rem; transition: all var(--ds-transition); }
                    .pf-avatar__edit:hover { transform: scale(1.08); background: var(--ds-brand-700); }
                    .pf-name { font-family: var(--ds-font-heading); font-weight: 800; font-size: 1.15rem; color: var(--ds-ink-strong); text-align: center; margin: 0 0 6px; }
                    .pf-role { display: inline-flex; align-items: center; gap: 5px; background: var(--ds-brand-50); color: var(--ds-brand-700); font-weight: 700; font-size: .76rem; padding: 5px 13px; border-radius: var(--ds-radius-pill); text-transform: capitalize; }
                    .pf-info { padding: 11px 0; border-bottom: 1px solid var(--ds-border); display: flex; justify-content: space-between; align-items: center; gap: 10px; }
                    .pf-info:last-child { border-bottom: 0; }
                    .pf-info__label { font-weight: 700; color: var(--ds-muted); font-size: .82rem; display: inline-flex; align-items: center; gap: 6px; }
                    .pf-info__label i { color: var(--ds-brand-600); }
                    .pf-info__value { font-weight: 700; color: var(--ds-ink); font-size: .85rem; text-align: right; word-break: break-word; }

                    .nav-pills-custom { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; padding: 0; }
                    .nav-pills-custom .nav-link { color: var(--ds-muted); font-weight: 700; padding: 9px 18px; border-radius: var(--ds-radius-pill); border: 1px solid var(--ds-border); background: var(--ds-surface); cursor: pointer; font-size: .9rem; transition: all var(--ds-transition); }
                    .nav-pills-custom .nav-link.active { background: var(--ds-brand-600); color: #fff !important; border-color: var(--ds-brand-600); }

                    .pf-card .form-label { font-weight: 700; color: var(--ds-ink); font-size: .86rem; margin-bottom: 6px; }
                    .pf-card .form-control { border-radius: var(--ds-radius); border: 1px solid var(--ds-border-strong); padding: 11px 14px; font-size: .92rem; color: var(--ds-ink); background: var(--ds-surface); }
                    .pf-card .form-control:focus { border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); outline: none; }
                    .pf-card .input-group-text { background: var(--ds-surface-2); border: 1px solid var(--ds-border-strong); border-right: 0; border-radius: var(--ds-radius) 0 0 var(--ds-radius); color: var(--ds-muted); }
                    .btn-save { background: var(--ds-brand-600); color: #fff !important; padding: 11px 26px; border-radius: var(--ds-radius-pill); font-weight: 700; border: none; cursor: pointer; transition: all var(--ds-transition); }
                    .btn-save:hover { background: var(--ds-brand-700); transform: translateY(-1px); }
                    .pf-save-avatar { display: inline-flex; align-items: center; gap: 7px; width: 100%; justify-content: center; background: var(--ds-accent); color: #3d2900; font-weight: 800; padding: 10px; border: 0; border-radius: var(--ds-radius-pill); cursor: pointer; margin-bottom: 14px; }
                </style>

                <div class="pf-hero">
                    <h1>Mon profil</h1>
                    <p>Gérez vos informations personnelles et la sécurité de votre compte.</p>
                </div>

                <div class="row gy-4">
                    <div class="col-xl-4">
                        <div class="pf-card text-center">
                            <form action="<?= ROOT ?>/Profiles/modifier_image" method="post" enctype="multipart/form-data" id="avatarForm">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                <div class="pf-avatar">
                                    <img src="<?= ROOT ?>/image_profile/<?= $profileImage ?>" class="pf-avatar__img" id="profileImage" <?= $hasRealImg ? '' : 'style="display:none"' ?> onerror="this.style.display='none';var f=document.getElementById('avatarFallback');if(f)f.style.display='flex';">
                                    <div class="pf-avatar__fallback" id="avatarFallback" <?= $hasRealImg ? 'style="display:none"' : '' ?>><?= htmlspecialchars($initials) ?></div>
                                    <label for="fileInput" class="pf-avatar__edit" title="Changer la photo"><i class='bx bx-camera'></i></label>
                                    <input type="file" id="fileInput" name="newAvatar" accept="image/*" class="d-none">
                                </div>
                                <h2 class="pf-name"><?= htmlspecialchars($fullName) ?></h2>
                                <span class="pf-role"><i class='bx bx-user'></i> <?= $sessionRole ?></span>
                                <button type="submit" class="pf-save-avatar d-none mt-3" id="saveAvatarBtn"><i class='bx bx-check'></i> Confirmer la photo</button>
                            </form>

                            <div class="mt-4 text-start">
                                <div class="pf-info"><span class="pf-info__label"><i class='bx bx-envelope'></i> Email</span><span class="pf-info__value"><?= $profileEmail !== '' ? $profileEmail : '—' ?></span></div>
                                <div class="pf-info"><span class="pf-info__label"><i class='bx bx-phone'></i> Contact</span><span class="pf-info__value"><?= $profileContact !== '' ? $profileContact : '—' ?></span></div>
                                <div class="pf-info"><span class="pf-info__label"><i class='bx bx-buildings'></i> Université</span><span class="pf-info__value"><?= $profileUniversite !== '' ? $profileUniversite : '—' ?></span></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8">
                        <div class="pf-card">
                            <ul class="nav nav-pills nav-pills-custom" id="profileTabs">
                                <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#personalInfo" type="button"><i class='bx bx-user'></i> Informations</button></li>
                                <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#security" type="button"><i class='bx bx-lock-alt'></i> Sécurité</button></li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="personalInfo">
                                    <?php $this->view('set_flash'); ?>
                                    <form action="<?= ROOT ?>/Profiles/appercu" method="POST" class="row g-3">
                                        <input type="hidden" name="update_profile_info" value="1">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                        <div class="col-md-6"><label class="form-label">Prénom</label><input type="text" class="form-control" name="user_firstname" value="<?= $profilePrenom ?>" required></div>
                                        <div class="col-md-6"><label class="form-label">Nom</label><input type="text" class="form-control" name="user_lastname" value="<?= $profileNom ?>" required></div>
                                        <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" name="user_email" value="<?= $profileEmail ?>" required></div>
                                        <div class="col-md-6"><label class="form-label">Contact</label><input type="text" class="form-control" name="user_contact" value="<?= $profileContact ?>"></div>
                                        <div class="col-md-6"><label class="form-label">Université</label><input type="text" class="form-control" name="user_universite" value="<?= $profileUniversite ?>"></div>
                                        <div class="col-md-6"><label class="form-label">Faculté / Institut</label><input type="text" class="form-control" name="user_faculte" value="<?= $profileFaculte ?>"></div>
                                        <div class="col-12"><label class="form-label">Filière</label><input type="text" class="form-control" name="user_filiere" value="<?= $profileFiliere ?>"></div>
                                        <div class="col-12 text-end mt-4"><button type="submit" class="btn-save"><i class='bx bx-save'></i> Enregistrer les modifications</button></div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="security">
                                    <form action="<?= ROOT ?>/Profiles/appercu" method="POST" class="row g-3">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                        <div class="col-12"><label class="form-label">Mot de passe actuel</label>
                                            <div class="input-group"><span class="input-group-text"><i class='bx bx-key'></i></span><input type="password" class="form-control border-start-0" name="ancien_mot_de_passe" placeholder="Confirmez pour modifier" required></div>
                                        </div>
                                        <div class="col-md-6"><label class="form-label">Nouveau mot de passe</label>
                                            <div class="input-group"><span class="input-group-text"><i class='bx bx-lock-alt'></i></span><input type="password" class="form-control border-start-0" name="nouveau_mot_de_passe" required></div>
                                        </div>
                                        <div class="col-md-6"><label class="form-label">Confirmer le nouveau mot de passe</label>
                                            <div class="input-group"><span class="input-group-text"><i class='bx bx-lock-alt'></i></span><input type="password" class="form-control border-start-0" name="comfirme_mot_de_passe" required></div>
                                        </div>
                                        <div class="col-12 text-end mt-4"><button type="submit" name="modifier" class="btn-save"><i class='bx bx-shield'></i> Mettre à jour la sécurité</button></div>
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
    document.addEventListener('DOMContentLoaded', function () {
        var fileInput = document.getElementById('fileInput');
        var saveBtn = document.getElementById('saveAvatarBtn');
        var previewImage = document.getElementById('profileImage');
        if (fileInput) {
            fileInput.onchange = function () {
                if (fileInput.files && fileInput.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        if (previewImage) { previewImage.src = e.target.result; previewImage.style.display = 'block'; }
                        var fb = document.getElementById('avatarFallback');
                        if (fb) { fb.style.display = 'none'; }
                        if (saveBtn) { saveBtn.classList.remove('d-none'); }
                    };
                    reader.readAsDataURL(fileInput.files[0]);
                }
            };
        }
    });
</script>
</body>
</html>
