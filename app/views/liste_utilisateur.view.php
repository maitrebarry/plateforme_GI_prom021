<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Dashboard administrateur']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
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
?>
<style>
.admin-users-card,
.admin-users-card .card-body,
.admin-users-card h5,
.admin-users-card p,
.admin-users-card label {
    color: #0f172a;
}

.admin-users-card .table {
    color: #0f172a;
    --bs-table-bg: #ffffff;
    --bs-table-hover-bg: #eef2ff;
}

.admin-users-card .table thead th {
    background: #e2e8f0;
    color: #0f172a;
    font-weight: 800;
}

.admin-users-card .table tbody td {
    background: #ffffff;
    color: #1e293b;
    vertical-align: middle;
}

.admin-users-card .btn {
    font-weight: 700;
}

.admin-users-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.admin-users-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}

.admin-users-stat {
    padding: 16px 18px;
    border-radius: 20px;
    background: #f8fafc;
    border: 1px solid #dbe4ee;
}

.admin-users-stat strong {
    display: block;
    font-size: 1.7rem;
    line-height: 1.1;
    margin-top: 8px;
}

.admin-bulk-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px;
    margin-bottom: 16px;
    border: 1px solid #dbe4ee;
    border-radius: 18px;
    background: #f8fafc;
}

.admin-bulk-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.admin-pagination-wrap {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.admin-pagination-summary {
    color: #475569;
    font-weight: 600;
}

.admin-pagination {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.page-link-nav {
    min-width: 40px;
    height: 40px;
    padding: 0 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #0f172a;
    text-decoration: none;
    font-weight: 700;
}

.page-link-nav.is-active {
    background: #0d6efd;
    border-color: #0d6efd;
    color: #ffffff;
}

.page-link-nav.is-disabled {
    pointer-events: none;
    opacity: 0.45;
}
</style>
<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

            <div class="dashboard-body__content p-4">
                <div class="col-12">
                    <div class="card common-card border border-gray-five admin-users-card">
                        <div class="card-body">
                            <?php $this->view("set_flash"); ?>
                            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                                <h5 class="card-title mb-0">Liste des utilisateurs</h5>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#large">
                                    <i class="bx bx-plus"></i> Ajouter un utilisateur
                                </button>
                            </div>

                            <div class="admin-users-stats">
                                <div class="admin-users-stat"><span>Total</span><strong><?= (int) ($userStats->total_users ?? 0) ?></strong></div>
                                <div class="admin-users-stat"><span>Etudiants</span><strong><?= (int) ($userStats->student_users ?? 0) ?></strong></div>
                                <div class="admin-users-stat"><span>Administrateurs</span><strong><?= (int) ($userStats->admin_users ?? 0) ?></strong></div>
                                <div class="admin-users-stat"><span>DER</span><strong><?= (int) ($userStats->der_users ?? 0) ?></strong></div>
                                <div class="admin-users-stat"><span>Bloques</span><strong><?= (int) ($userStats->blocked_users ?? 0) ?></strong></div>
                            </div>

                            <form method="GET" action="<?= ROOT ?>/Utilisateurs/liste_utilisateur" class="row gy-3 mb-4" id="user-filter-form">
                                <div class="col-md-3">
                                    <input type="text" name="search" value="<?= htmlspecialchars($userSearch) ?>" class="common-input common-input--bg" placeholder="Rechercher nom, email, universite">
                                </div>
                                <div class="col-md-2">
                                    <select name="role" class="common-input common-input--bg auto-submit-filter">
                                        <option value="all" <?= $userRoleFilter === 'all' ? 'selected' : '' ?>>Tous les roles</option>
                                        <option value="admin" <?= $userRoleFilter === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        <option value="der" <?= $userRoleFilter === 'der' ? 'selected' : '' ?>>DER</option>
                                        <option value="etudiant" <?= $userRoleFilter === 'etudiant' ? 'selected' : '' ?>>Etudiant</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="common-input common-input--bg auto-submit-filter">
                                        <option value="all" <?= $userStatusFilter === 'all' ? 'selected' : '' ?>>Tous statuts</option>
                                        <option value="actif" <?= $userStatusFilter === 'actif' ? 'selected' : '' ?>>Actif</option>
                                        <option value="bloque" <?= $userStatusFilter === 'bloque' ? 'selected' : '' ?>>Bloque</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="universite" class="common-input common-input--bg auto-submit-filter">
                                        <option value="">Toutes universites</option>
                                        <?php foreach (($universites ?? []) as $universite): ?>
                                            <?php $nomUniversite = (string) ($universite->nom_universite ?? ''); ?>
                                            <option value="<?= htmlspecialchars($nomUniversite) ?>" <?= $userUniversiteFilter === $nomUniversite ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($nomUniversite) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <select name="sort_by" class="common-input common-input--bg auto-submit-filter">
                                        <option value="name" <?= $userSortBy === 'name' ? 'selected' : '' ?>>Nom</option>
                                        <option value="email" <?= $userSortBy === 'email' ? 'selected' : '' ?>>Email</option>
                                        <option value="role" <?= $userSortBy === 'role' ? 'selected' : '' ?>>Role</option>
                                        <option value="university" <?= $userSortBy === 'university' ? 'selected' : '' ?>>Universite</option>
                                        <option value="status" <?= $userSortBy === 'status' ? 'selected' : '' ?>>Statut</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <select name="sort_dir" class="common-input common-input--bg auto-submit-filter">
                                        <option value="asc" <?= $userSortDir === 'asc' ? 'selected' : '' ?>>Asc</option>
                                        <option value="desc" <?= $userSortDir === 'desc' ? 'selected' : '' ?>>Desc</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <select name="per_page" class="common-input common-input--bg auto-submit-filter">
                                        <option value="10" <?= $perPage === 10 ? 'selected' : '' ?>>10</option>
                                        <option value="20" <?= $perPage === 20 ? 'selected' : '' ?>>20</option>
                                        <option value="50" <?= $perPage === 50 ? 'selected' : '' ?>>50</option>
                                    </select>
                                </div>
                            </form>

                            <form method="POST" action="<?= ROOT ?>/Utilisateurs/liste_utilisateur">
                                <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                                <div class="admin-bulk-bar">
                                    <label class="d-inline-flex align-items-center gap-2 mb-0">
                                        <input type="checkbox" id="select-all-users">
                                        <span>Tout selectionner</span>
                                    </label>
                                    <div class="admin-bulk-actions">
                                        <button type="submit" class="btn btn-success btn-sm" name="bulk_user_action" value="activate">Activer la selection</button>
                                        <button type="submit" class="btn btn-warning btn-sm" name="bulk_user_action" value="block">Bloquer la selection</button>
                                        <button type="submit" class="btn btn-danger btn-sm" name="bulk_user_action" value="delete" onclick="return confirm('Supprimer les utilisateurs selectionnes ?');">Supprimer la selection</button>
                                    </div>
                                </div>
                            <div class="table-responsive">
                                <table class="table text-body mt--24">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Nom & Prenom</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Universite - Faculte - Filiere</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($liste)): ?>
                                            <?php foreach ($liste as $listes): ?>
                                                <?php
                                                $userStatus = $listes->statut_compte ?? 'actif';
                                                $isActive = $userStatus === 'actif';
                                                $isStudent = ($listes->role === 'etudiant');
                                                $isCurrentUser = ((int)($_SESSION['user_id'] ?? 0) === (int)$listes->user_id);
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php if ($isStudent && !$isCurrentUser): ?>
                                                            <input type="checkbox" class="user-checkbox" name="user_ids[]" value="<?= (int) $listes->user_id ?>">
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars(($listes->nom ?? '') . ' ' . ($listes->prenom ?? '')) ?></td>
                                                    <td><?= htmlspecialchars((string) ($listes->email ?? '')) ?></td>
                                                    <td><?= htmlspecialchars((string) ($listes->role ?? '')) ?></td>
                                                    <td><?= htmlspecialchars(trim(($listes->universite ?? 'N/A') . ' - ' . ($listes->faculte ?? 'N/A') . ' - ' . ($listes->filiere ?? 'N/A'))) ?></td>
                                                    <td>
                                                        <span class="badge <?= $isActive ? 'bg-success' : 'bg-secondary' ?>">
                                                            <?= $isActive ? 'Actif' : 'Bloque' ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if ($isStudent): ?>
                                                            <div class="admin-users-actions">
                                                                <form method="post" action="<?= ROOT ?>/Utilisateurs/liste_utilisateur" class="d-inline">
                                                                    <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                                                                    <input type="hidden" name="user_action" value="toggle_status">
                                                                    <input type="hidden" name="user_id" value="<?= (int) $listes->user_id ?>">
                                                                    <input type="hidden" name="target_status" value="<?= $isActive ? 'bloque' : 'actif' ?>">
                                                                    <button type="submit" class="btn btn-sm <?= $isActive ? 'btn-warning' : 'btn-success' ?>">
                                                                        <?= $isActive ? 'Bloquer' : 'Activer' ?>
                                                                    </button>
                                                                </form>

                                                                <?php if (!$isCurrentUser): ?>
                                                                    <form method="post" action="<?= ROOT ?>/Utilisateurs/liste_utilisateur" class="d-inline" onsubmit="return confirm('Supprimer definitivement cet utilisateur ?');">
                                                                        <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                                                                        <input type="hidden" name="user_action" value="delete_user">
                                                                        <input type="hidden" name="user_id" value="<?= (int) $listes->user_id ?>">
                                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                                                    </form>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="7" class="text-center text-muted">Aucun utilisateur trouve.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            </form>

                            <?php $this->view('Partials/admin-pagination', [
                                'currentPage' => $currentPage,
                                'perPage' => $perPage,
                                'totalPages' => $totalPages,
                                'totalItems' => $totalItems,
                                'basePath' => 'Utilisateurs/liste_utilisateur',
                                'queryString' => $paginationQuery,
                                'itemLabel' => 'utilisateur(s)',
                            ]); ?>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="large" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form method="post" action="<?= ROOT ?>/Utilisateurs/liste_utilisateur">
                                <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                                <div class="modal-header bg-primary text-white justify-content-center position-relative">
                                    <h5 class="modal-title text-center w-100 text-white" id="largeModalLabel">Ajouter un utilisateur</h5>
                                    <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row gy-4">
                                        <div class="col-sm-6 col-xs-6">
                                            <label class="form-label mb-2 font-18 font-heading fw-600">Prenom</label>
                                            <div class="position-relative">
                                                <input type="text" name="prenom" class="common-input common-input--bg common-input--withIcon" placeholder="Prenom" required>
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
                                            <small class="text-muted d-block mt-2">Saisir 8 chiffres. Format accepte: 76562317 ou 76 56 23 17.</small>
                                        </div>
                                        <div class="col-sm-6 col-xs-6">
                                            <label class="form-label mb-2 font-18 font-heading fw-600">Universite</label>
                                            <select class="common-input common-input--bg" name="universite_id" id="universite_id_admin" required>
                                                <option value="">Choisir une universite</option>
                                                <?php foreach (($universites ?? []) as $universite): ?>
                                                    <option value="<?= (int) ($universite->id_universite ?? 0) ?>">
                                                        <?= htmlspecialchars((string) ($universite->nom_universite ?? '')) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 col-xs-6">
                                            <label class="form-label mb-2 font-18 font-heading fw-600">Faculte / Institut</label>
                                            <select class="common-input common-input--bg" name="faculte_id" id="faculte_id_admin" required disabled>
                                                <option value="">Selectionnez d abord une universite</option>
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
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="save_user" class="btn btn-primary">Enregistrer</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php $this->view('Partials/dashboard-footer'); ?>
            </div>
        </div>
    </div>
</section>

<?php $this->view('Partials/scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.auto-submit-filter').forEach(function (element) {
        element.addEventListener('change', function () {
            var form = document.getElementById('user-filter-form');
            if (form) {
                form.submit();
            }
        });
    });

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
                faculteSelect.innerHTML = '<option value="">Selectionnez d abord une universite</option>';
                return;
            }

            try {
                const response = await fetch('<?= ROOT ?>/Utilisateurs/getFacultes/' + universiteId);
                const facultes = await response.json();

                if (!Array.isArray(facultes) || facultes.length === 0) {
                    faculteSelect.innerHTML = '<option value="">Aucune faculte ou institut disponible</option>';
                    return;
                }

                faculteSelect.innerHTML = '<option value="">Choisir une faculte / un institut</option>';
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

    var selectAll = document.getElementById('select-all-users');
    if (selectAll) {
        var checkboxes = Array.from(document.querySelectorAll('.user-checkbox'));
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = selectAll.checked;
            });
        });
    }
});
</script>
</body>
</html>
