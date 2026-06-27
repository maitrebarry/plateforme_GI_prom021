<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Gestion des utilisateurs']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$liste = $liste ?? [];
$userSearch = $userSearch ?? '';
$userRoleFilter = $userRoleFilter ?? 'all';
$userStatusFilter = $userStatusFilter ?? 'all';
$userUniversiteFilter = $userUniversiteFilter ?? '';
$userSortBy = $userSortBy ?? 'name';
$userSortDir = $userSortDir ?? 'asc';
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 10);
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalItems = max(0, (int) ($totalItems ?? count($liste ?? [])));
$paginationQuery = (string) ($paginationQuery ?? '');
$userStats = $userStats ?? (object) ['total_users' => 0, 'student_users' => 0, 'admin_users' => 0, 'der_users' => 0, 'blocked_users' => 0];
$csrf = (string) ($_SESSION['csrf_token'] ?? '');
$usrStatCards = [
    ['Total', (int) ($userStats->total_users ?? 0), 'bx-group', 'brand'],
    ['Étudiants', (int) ($userStats->student_users ?? 0), 'bx-user', 'blue'],
    ['Admins', (int) ($userStats->admin_users ?? 0), 'bx-shield', 'accent'],
    ['DER', (int) ($userStats->der_users ?? 0), 'bx-id-card', 'success'],
    ['Bloqués', (int) ($userStats->blocked_users ?? 0), 'bx-block', 'danger'],
];
?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

            <div class="dashboard-body__content p-3 p-lg-4">
                <?php $this->view('set_flash'); ?>

                <style>
                    .usr-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 18px; }
                    .usr-head h1 { display: flex; align-items: center; gap: 9px; font-family: var(--ds-font-heading); font-size: 1.35rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0; }
                    .usr-head h1 i { color: var(--ds-brand-600); }
                    .usr-add { display: inline-flex; align-items: center; gap: 7px; background: var(--ds-brand-600); color: #fff; font-weight: 700; font-size: .88rem; padding: 10px 18px; border-radius: var(--ds-radius-pill); border: 0; cursor: pointer; }
                    .usr-add:hover { background: var(--ds-brand-700); }

                    .usr-stats { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 12px; margin-bottom: 18px; }
                    .usr-stat { position: relative; overflow: hidden; background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); padding: 14px; box-shadow: var(--ds-shadow-sm); }
                    .usr-stat::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
                    .usr-stat--brand::before { background: var(--ds-brand-500); } .usr-stat--brand .usr-stat__ic { background: var(--ds-brand-50); color: var(--ds-brand-600); }
                    .usr-stat--blue::before { background: #1d59b8; } .usr-stat--blue .usr-stat__ic { background: #e3effb; color: #1d59b8; }
                    .usr-stat--accent::before { background: var(--ds-accent); } .usr-stat--accent .usr-stat__ic { background: var(--ds-accent-soft); color: #8a6310; }
                    .usr-stat--success::before { background: #1f8a4d; } .usr-stat--success .usr-stat__ic { background: #e4f3ea; color: #11703a; }
                    .usr-stat--danger::before { background: var(--ds-danger); } .usr-stat--danger .usr-stat__ic { background: var(--ds-danger-soft); color: var(--ds-danger); }
                    .usr-stat__ic { width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 8px; }
                    .usr-stat__v { font-family: var(--ds-font-heading); font-size: 1.55rem; font-weight: 800; color: var(--ds-ink-strong); line-height: 1; }
                    .usr-stat__l { color: var(--ds-muted); font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; margin-top: 5px; }

                    .usr-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); box-shadow: var(--ds-shadow-sm); padding: 20px; }
                    .usr-filter { background: var(--ds-surface-2); border: 1px solid var(--ds-border); border-radius: var(--ds-radius); padding: 14px; margin-bottom: 16px; }
                    .usr-filter .form-control, .usr-filter .form-select { width: 100%; border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius); padding: 9px 12px; font-size: .86rem; color: var(--ds-ink); background: var(--ds-surface); font-family: var(--ds-font-sans); }
                    .usr-filter .form-control:focus, .usr-filter .form-select:focus { outline: none; border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); }
                    .usr-bulk { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; background: var(--ds-surface-2); border: 1px solid var(--ds-border); border-radius: var(--ds-radius); padding: 12px 16px; margin-bottom: 14px; }
                    .usr-bulk__check { display: inline-flex; align-items: center; gap: 8px; font-weight: 700; font-size: .82rem; color: var(--ds-ink); }
                    .usr-check { width: 18px; height: 18px; accent-color: var(--ds-brand-600); cursor: pointer; }
                    .usr-bbtn { display: inline-flex; align-items: center; gap: 6px; font-weight: 700; font-size: .8rem; padding: 8px 14px; border-radius: var(--ds-radius-pill); border: 0; cursor: pointer; color: #fff; }
                    .usr-bbtn--ok { background: #1f8a4d; } .usr-bbtn--wait { background: var(--ds-accent); color: #3d2900; } .usr-bbtn--no { background: var(--ds-danger); }

                    .usr-table-wrap { overflow-x: auto; }
                    .usr-table { width: 100%; border-collapse: collapse; min-width: 820px; }
                    .usr-table thead th { text-align: left; font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .04em; color: var(--ds-muted); padding: 11px 12px; border-bottom: 2px solid var(--ds-border); white-space: nowrap; }
                    .usr-table tbody td { padding: 12px; border-bottom: 1px solid var(--ds-border); color: var(--ds-ink); font-size: .88rem; vertical-align: middle; }
                    .usr-table tbody tr:hover { background: var(--ds-surface-2); }
                    .usr-name { display: inline-flex; align-items: center; gap: 9px; font-weight: 700; color: var(--ds-ink-strong); }
                    .usr-ava { width: 32px; height: 32px; border-radius: 50%; background: var(--ds-brand-100); color: var(--ds-brand-700); display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: .78rem; flex-shrink: 0; }
                    .usr-affil { color: var(--ds-muted); font-size: .8rem; max-width: 240px; }
                    .usr-pill { display: inline-flex; align-items: center; height: 22px; padding: 0 11px; border-radius: var(--ds-radius-pill); font-size: .7rem; font-weight: 800; text-transform: uppercase; }
                    .usr-pill--etudiant { background: #e3effb; color: #1d59b8; } .usr-pill--admin { background: var(--ds-accent-soft); color: #8a6310; } .usr-pill--der { background: var(--ds-brand-50); color: var(--ds-brand-700); }
                    .usr-pill--actif { background: #e4f3ea; color: #11703a; } .usr-pill--bloque { background: var(--ds-surface-2); color: var(--ds-muted); }
                    .usr-act { display: inline-flex; align-items: center; gap: 5px; font-weight: 700; font-size: .78rem; padding: 6px 13px; border-radius: var(--ds-radius-pill); border: 1px solid var(--ds-border); cursor: pointer; text-decoration: none; }
                    .usr-act--block { background: var(--ds-accent-soft); color: #8a6310; border-color: transparent; }
                    .usr-act--unblock { background: #e4f3ea; color: #11703a; border-color: transparent; }
                    .usr-act--del { background: var(--ds-surface); color: var(--ds-danger); border-color: var(--ds-danger); }
                    .usr-act--del:hover { background: var(--ds-danger); color: #fff; }
                    .usr-empty { text-align: center; color: var(--ds-muted); padding: 28px; }

                    .admin-pagination-wrap { display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap; margin-top: 20px; }
                    .admin-pagination-summary { color: var(--ds-muted); font-size: .85rem; font-weight: 600; }
                    .admin-pagination { display: flex; gap: 6px; flex-wrap: wrap; }
                    .page-link-nav { min-width: 40px; height: 40px; padding: 0 12px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--ds-radius); border: 1px solid var(--ds-border); background: var(--ds-surface); color: var(--ds-ink); text-decoration: none; font-weight: 700; font-size: .9rem; transition: all var(--ds-transition); }
                    .page-link-nav:hover:not(.is-disabled) { border-color: var(--ds-brand-300); color: var(--ds-brand-700); }
                    .page-link-nav.is-active { background: var(--ds-brand-600); border-color: var(--ds-brand-600); color: #fff; }
                    .page-link-nav.is-disabled { pointer-events: none; opacity: .4; }

                    /* Modale (Bootstrap) — restylée tokens */
                    #large .modal-content { border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); overflow: hidden; }
                    #large .modal-header { background: linear-gradient(135deg, var(--ds-brand-700), var(--ds-brand-800)); color: #fff; border: 0; padding: 18px 22px; }
                    #large .modal-title { font-family: var(--ds-font-heading); font-weight: 800; }
                    #large .modal-body { padding: 22px; }
                    #large .form-label { font-weight: 700; color: var(--ds-ink); font-size: .86rem; }
                    #large .common-input, #large input, #large select { width: 100%; border: 1px solid var(--ds-border-strong) !important; border-radius: var(--ds-radius) !important; padding: 11px 14px !important; font-size: .92rem; color: var(--ds-ink); background: var(--ds-surface) !important; font-family: var(--ds-font-sans); }
                    #large input:focus, #large select:focus { outline: none; border-color: var(--ds-brand-400) !important; box-shadow: var(--ds-ring); }
                    #large .input-icon { display: none; }
                    #large .modal-footer { border-top: 1px solid var(--ds-border); padding: 16px 22px; }
                    #large .btn-primary { background: var(--ds-brand-600); border: 0; border-radius: var(--ds-radius-pill); font-weight: 700; padding: 10px 22px; }
                    #large .btn-secondary { background: var(--ds-surface-2); color: var(--ds-ink); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-pill); font-weight: 700; padding: 10px 22px; }

                    @media (min-width: 768px) { .usr-stats { grid-template-columns: repeat(5, minmax(0,1fr)); } }
                </style>

                <div class="usr-head">
                    <h1><i class='bx bx-group'></i> Gestion des utilisateurs</h1>
                    <button type="button" class="usr-add" data-bs-toggle="modal" data-bs-target="#large"><i class='bx bx-user-plus'></i> Ajouter un utilisateur</button>
                </div>

                <div class="usr-stats">
                    <?php foreach ($usrStatCards as $s): ?>
                        <div class="usr-stat usr-stat--<?= $s[3] ?>"><span class="usr-stat__ic"><i class='bx <?= $s[2] ?>'></i></span><div class="usr-stat__v"><?= $s[1] ?></div><div class="usr-stat__l"><?= htmlspecialchars($s[0]) ?></div></div>
                    <?php endforeach; ?>
                </div>

                <div class="usr-card">
                    <div class="usr-filter">
                        <form method="GET" action="<?= ROOT ?>/Utilisateurs/liste_utilisateur" class="row gy-2 gx-2" id="user-filter-form">
                            <div class="col-md-3 col-12"><input type="text" name="search" value="<?= htmlspecialchars($userSearch) ?>" class="form-control" placeholder="Rechercher nom, email, université…"></div>
                            <div class="col-md-2 col-6"><select name="role" class="form-select auto-submit-filter"><option value="all" <?= $userRoleFilter === 'all' ? 'selected' : '' ?>>Tous rôles</option><option value="admin" <?= $userRoleFilter === 'admin' ? 'selected' : '' ?>>Admin</option><option value="der" <?= $userRoleFilter === 'der' ? 'selected' : '' ?>>DER</option><option value="etudiant" <?= $userRoleFilter === 'etudiant' ? 'selected' : '' ?>>Étudiant</option></select></div>
                            <div class="col-md-2 col-6"><select name="status" class="form-select auto-submit-filter"><option value="all" <?= $userStatusFilter === 'all' ? 'selected' : '' ?>>Tous statuts</option><option value="actif" <?= $userStatusFilter === 'actif' ? 'selected' : '' ?>>Actif</option><option value="bloque" <?= $userStatusFilter === 'bloque' ? 'selected' : '' ?>>Bloqué</option></select></div>
                            <div class="col-md-2 col-6"><select name="universite" class="form-select auto-submit-filter"><option value="">Toutes universités</option><?php foreach (($universites ?? []) as $u): ?><?php $nu = (string) ($u->nom_universite ?? ''); ?><option value="<?= htmlspecialchars($nu) ?>" <?= $userUniversiteFilter === $nu ? 'selected' : '' ?>><?= htmlspecialchars($nu) ?></option><?php endforeach; ?></select></div>
                            <div class="col-md-1 col-4"><select name="sort_by" class="form-select auto-submit-filter"><option value="name" <?= $userSortBy === 'name' ? 'selected' : '' ?>>Nom</option><option value="email" <?= $userSortBy === 'email' ? 'selected' : '' ?>>Email</option><option value="role" <?= $userSortBy === 'role' ? 'selected' : '' ?>>Rôle</option><option value="university" <?= $userSortBy === 'university' ? 'selected' : '' ?>>Univ.</option><option value="status" <?= $userSortBy === 'status' ? 'selected' : '' ?>>Statut</option></select></div>
                            <div class="col-md-1 col-4"><select name="sort_dir" class="form-select auto-submit-filter"><option value="asc" <?= $userSortDir === 'asc' ? 'selected' : '' ?>>↑</option><option value="desc" <?= $userSortDir === 'desc' ? 'selected' : '' ?>>↓</option></select></div>
                            <div class="col-md-1 col-4"><select name="per_page" class="form-select auto-submit-filter"><?php foreach ([10, 20, 50] as $pp): ?><option value="<?= $pp ?>" <?= $perPage === $pp ? 'selected' : '' ?>><?= $pp ?></option><?php endforeach; ?></select></div>
                        </form>
                    </div>

                    <form method="POST" action="<?= ROOT ?>/Utilisateurs/liste_utilisateur">
                        <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                        <div class="usr-bulk">
                            <label class="usr-bulk__check"><input class="usr-check" type="checkbox" id="select-all-users"> Tout sélectionner</label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="usr-bbtn usr-bbtn--ok" name="bulk_user_action" value="activate"><i class='bx bx-check'></i> Activer</button>
                                <button type="submit" class="usr-bbtn usr-bbtn--wait" name="bulk_user_action" value="block"><i class='bx bx-block'></i> Bloquer</button>
                                <button type="submit" class="usr-bbtn usr-bbtn--no" name="bulk_user_action" value="delete" onclick="return confirm('Supprimer les utilisateurs sélectionnés ?');"><i class='bx bx-trash'></i> Supprimer</button>
                            </div>
                        </div>

                        <div class="usr-table-wrap">
                            <table class="usr-table">
                                <thead><tr><th></th><th>Nom &amp; prénom</th><th>Email</th><th>Rôle</th><th>Université · Faculté · Filière</th><th>Statut</th><th>Actions</th></tr></thead>
                                <tbody>
                                    <?php if (!empty($liste)): ?>
                                        <?php foreach ($liste as $u): ?>
                                            <?php
                                            $uStatus = (string) ($u->statut_compte ?? 'actif'); $isActive = $uStatus === 'actif';
                                            $uRole = (string) ($u->role ?? ''); $isStudent = $uRole === 'etudiant';
                                            $isMe = ((int) ($_SESSION['user_id'] ?? 0) === (int) $u->user_id);
                                            $uFull = trim((string) (($u->nom ?? '') . ' ' . ($u->prenom ?? '')));
                                            ?>
                                            <tr>
                                                <td class="is-cardcheck"><?php if ($isStudent && !$isMe): ?><input type="checkbox" class="user-checkbox usr-check" name="user_ids[]" value="<?= (int) $u->user_id ?>"><?php endif; ?></td>
                                                <td class="is-cardtitle"><span class="usr-name"><span class="usr-ava"><?= htmlspecialchars(strtoupper(mb_substr($uFull !== '' ? $uFull : 'U', 0, 1))) ?></span><?= htmlspecialchars($uFull !== '' ? $uFull : '—') ?></span></td>
                                                <td class="text-muted" data-label="Email"><?= htmlspecialchars((string) ($u->email ?? '')) ?></td>
                                                <td data-label="Rôle"><span class="usr-pill usr-pill--<?= htmlspecialchars($uRole) ?>"><?= htmlspecialchars($uRole) ?></span></td>
                                                <td class="usr-affil" data-label="Affiliation"><?= htmlspecialchars(trim(($u->universite ?? 'N/A') . ' · ' . ($u->faculte ?? 'N/A') . ' · ' . ($u->filiere ?? 'N/A'))) ?></td>
                                                <td data-label="Statut"><span class="usr-pill usr-pill--<?= $isActive ? 'actif' : 'bloque' ?>"><?= $isActive ? 'Actif' : 'Bloqué' ?></span></td>
                                                <td class="is-cardaction">
                                                    <?php if ($isStudent): ?>
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <form method="post" action="<?= ROOT ?>/Utilisateurs/liste_utilisateur" class="d-inline">
                                                                <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                                                <input type="hidden" name="user_action" value="toggle_status">
                                                                <input type="hidden" name="user_id" value="<?= (int) $u->user_id ?>">
                                                                <input type="hidden" name="target_status" value="<?= $isActive ? 'bloque' : 'actif' ?>">
                                                                <button type="submit" class="usr-act <?= $isActive ? 'usr-act--block' : 'usr-act--unblock' ?>"><i class='bx <?= $isActive ? 'bx-block' : 'bx-check' ?>'></i> <?= $isActive ? 'Bloquer' : 'Activer' ?></button>
                                                            </form>
                                                            <?php if (!$isMe): ?>
                                                                <form method="post" action="<?= ROOT ?>/Utilisateurs/liste_utilisateur" class="d-inline" onsubmit="return confirm('Supprimer définitivement cet utilisateur ?');">
                                                                    <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                                                    <input type="hidden" name="user_action" value="delete_user">
                                                                    <input type="hidden" name="user_id" value="<?= (int) $u->user_id ?>">
                                                                    <button type="submit" class="usr-act usr-act--del"><i class='bx bx-trash'></i></button>
                                                                </form>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php else: ?><span class="text-muted">—</span><?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?><tr><td colspan="7" class="usr-empty">Aucun utilisateur trouvé.</td></tr><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <?php $this->view('Partials/admin-pagination', [
                        'currentPage' => $currentPage, 'perPage' => $perPage, 'totalPages' => $totalPages,
                        'totalItems' => $totalItems, 'basePath' => 'Utilisateurs/liste_utilisateur',
                        'queryString' => $paginationQuery, 'itemLabel' => 'utilisateur(s)',
                    ]); ?>
                </div>

                <!-- Modale Ajout utilisateur (Bootstrap) -->
                <div class="modal fade" id="large" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <form method="post" action="<?= ROOT ?>/Utilisateurs/liste_utilisateur">
                                <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="largeModalLabel"><i class='bx bx-user-plus'></i> Ajouter un utilisateur</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row gy-3">
                                        <div class="col-sm-6"><label class="form-label">Prénom</label><input type="text" name="prenom" class="common-input" placeholder="Prénom" required></div>
                                        <div class="col-sm-6"><label class="form-label">Nom</label><input type="text" name="nom" class="common-input" placeholder="Nom" required></div>
                                        <div class="col-12"><label class="form-label">Email</label><input type="email" name="email" class="common-input" placeholder="exemple@mail.com" required></div>
                                        <div class="col-sm-6"><label class="form-label">Contact</label><input type="tel" name="contact_utilisateur" id="contact_utilisateur" class="common-input" placeholder="76 56 23 17" inputmode="numeric" maxlength="11" pattern="[0-9]{2}(\s?[0-9]{2}){3}" required><small class="text-muted d-block mt-1">8 chiffres (ex : 76562317).</small></div>
                                        <div class="col-sm-6"><label class="form-label">Université</label><select class="common-input" name="universite_id" id="universite_id_admin" required><option value="">Choisir une université</option><?php foreach (($universites ?? []) as $u): ?><option value="<?= (int) ($u->id_universite ?? 0) ?>"><?= htmlspecialchars((string) ($u->nom_universite ?? '')) ?></option><?php endforeach; ?></select></div>
                                        <div class="col-sm-6"><label class="form-label">Faculté / Institut</label><select class="common-input" name="faculte_id" id="faculte_id_admin" required disabled><option value="">Sélectionnez d'abord une université</option></select></div>
                                        <div class="col-sm-6"><label class="form-label">Rôle</label><select name="role" class="common-input" required><option value="admin">Administrateur</option><option value="der">DER</option></select></div>
                                        <div class="col-sm-6"><label class="form-label">Mot de passe</label><input type="password" name="password" class="common-input" placeholder="Mot de passe" required></div>
                                        <div class="col-12"><label class="form-label">Confirmation</label><input type="password" name="password_confirm" class="common-input" placeholder="Confirmer le mot de passe" required></div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" name="save_user" class="btn btn-primary"><i class='bx bx-save'></i> Enregistrer</button>
                                </div>
                            </form>
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
    document.querySelectorAll('.auto-submit-filter').forEach(function (el) {
        el.addEventListener('change', function () { var f = document.getElementById('user-filter-form'); if (f) { f.submit(); } });
    });

    var universiteSelect = document.getElementById('universite_id_admin');
    var faculteSelect = document.getElementById('faculte_id_admin');
    var contactInput = document.getElementById('contact_utilisateur');

    function formatContact(value) { var d = value.replace(/\D/g, '').slice(0, 8); return d.replace(/(\d{2})(?=\d)/g, '$1 ').trim(); }
    if (contactInput) { contactInput.addEventListener('input', function () { this.value = formatContact(this.value); }); }

    if (universiteSelect && faculteSelect) {
        universiteSelect.addEventListener('change', async function () {
            var id = this.value;
            faculteSelect.innerHTML = '<option value="">Chargement…</option>';
            faculteSelect.disabled = true;
            if (!id) { faculteSelect.innerHTML = '<option value="">Sélectionnez d\'abord une université</option>'; return; }
            try {
                var res = await fetch('<?= ROOT ?>/Utilisateurs/getFacultes/' + id);
                var facultes = await res.json();
                if (!Array.isArray(facultes) || facultes.length === 0) { faculteSelect.innerHTML = '<option value="">Aucune faculté disponible</option>'; return; }
                faculteSelect.innerHTML = '<option value="">Choisir une faculté / un institut</option>';
                facultes.forEach(function (f) { var o = document.createElement('option'); o.value = f.id_faculte; o.textContent = f.nom_faculte; faculteSelect.appendChild(o); });
                faculteSelect.disabled = false;
            } catch (e) { faculteSelect.innerHTML = '<option value="">Erreur de chargement</option>'; }
        });
    }

    var selectAll = document.getElementById('select-all-users');
    if (selectAll) {
        var cbs = Array.from(document.querySelectorAll('.user-checkbox'));
        selectAll.addEventListener('change', function () { cbs.forEach(function (cb) { cb.checked = selectAll.checked; }); });
    }
});
</script>
</body>
</html>
