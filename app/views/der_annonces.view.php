<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Espace DER']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$derStats = $derStats ?? [];
$derPosts = $derPosts ?? [];
$derAllowedTypes = $derAllowedTypes ?? [];
$derVisibilityFilter = $derVisibilityFilter ?? 'active';
$derTypeFilter = $derTypeFilter ?? 'all';
$derSearch = $derSearch ?? '';
$derDateFrom = $derDateFrom ?? '';
$derDateTo = $derDateTo ?? '';
$derSortBy = $derSortBy ?? 'date';
$derSortDir = $derSortDir ?? 'desc';
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 10);
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalItems = max(0, (int) ($totalItems ?? count($derPosts ?? [])));
$paginationQuery = (string) ($paginationQuery ?? '');
?>
<style>
:root {
    --primary-color: #6366f1;
    --primary-hover: #4f46e5;
    --secondary-color: #94a3b8;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #0ea5e9;
    --teal-color: #14b8a6;
    --bg-light: #f1f5f9;
    --glass-bg: rgba(255, 255, 255, 0.8);
    --text-main: #0f172a;
    --text-muted: #64748b;
    --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --card-hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

body {
    background-color: var(--bg-light);
    color: var(--text-main);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.dashboard-body {
    background-color: var(--bg-light);
    min-height: 100vh;
    padding-bottom: 3rem;
}

.dashboard-body__content {
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Hero Banner (same as admin) */
.der-hero-banner {
    background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
    border-radius: 20px;
    padding: 2.5rem 3rem;
    color: #fff;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.der-hero-banner::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
    pointer-events: none;
}

.der-hero-banner::after {
    content: '';
    position: absolute;
    bottom: -60px; left: 30%;
    width: 300px; height: 300px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
    pointer-events: none;
}

.der-hero-banner h3,
.der-hero-banner p { color: #fff; position: relative; z-index: 2; }
.der-hero-banner p { opacity: 0.85; font-size: 0.95rem; }

/* Stat cards (same as admin) */
.common-card {
    border: none;
    border-radius: 16px;
    box-shadow: var(--card-shadow);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    background: #ffffff;
    overflow: hidden;
    position: relative;
}

.common-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--card-hover-shadow);
}

.common-card .card-body {
    padding: 1.75rem;
    z-index: 1;
    position: relative;
}

.common-card p {
    color: var(--text-muted);
    font-weight: 700;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 0.75rem;
}

.common-card h4 {
    color: var(--text-main);
    font-weight: 800;
    font-size: 2rem;
    margin: 0;
}

.stat-icon {
    position: absolute;
    right: 1.5rem;
    bottom: 1rem;
    font-size: 3rem;
    opacity: 0.1;
    transition: all 0.3s;
}

.common-card:hover .stat-icon {
    opacity: 0.2;
    transform: scale(1.1) rotate(-10deg);
}

.stat-card-total      { border-top: 5px solid var(--teal-color); }
.stat-card-annonces   { border-top: 5px solid var(--success-color); }
.stat-card-infos      { border-top: 5px solid var(--primary-color); }
.stat-card-evenements { border-top: 5px solid var(--warning-color); }
.stat-card-resultats  { border-top: 5px solid var(--info-color); }
.stat-card-opps       { border-top: 5px solid var(--danger-color); }
.stat-card-files      { border-top: 5px solid var(--secondary-color); }
.stat-card-archived   { border-top: 5px solid #6b7280; }

/* Filter shelf (same as admin management pages) */
.filter-shelf {
    background: #ffffff;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: var(--card-shadow);
    margin-bottom: 1.5rem;
    border: none;
}

.filter-shelf label {
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-muted);
    display: block;
    margin-bottom: 6px;
}

.filter-shelf .form-control,
.filter-shelf .form-select,
.filter-shelf input[type="date"] {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.65rem 1rem;
    font-size: 0.9rem;
    color: var(--text-main);
    background: #f8fafc;
    transition: 0.2s;
}

.filter-shelf .form-control:focus,
.filter-shelf .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    background: #fff;
}

/* Create form card */
.form-card {
    border: none;
    border-radius: 16px;
    background: #fff;
    box-shadow: var(--card-shadow);
    overflow: hidden;
}

.form-card .card-body {
    padding: 2rem;
}

.form-card h5 {
    font-weight: 800;
    font-size: 1.15rem;
    color: var(--text-main);
    padding-bottom: 1rem;
    border-bottom: 2px solid #f1f5f9;
    margin-bottom: 1.5rem;
}

.form-card label {
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 6px;
    display: block;
}

.form-card .form-control,
.form-card .form-select,
.form-card textarea {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.7rem 1rem;
    font-size: 0.9rem;
    transition: 0.2s;
}

.form-card .form-control:focus,
.form-card .form-select:focus,
.form-card textarea:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

/* Two-column layout */
.der-post-grid {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 24px;
    align-items: start;
}

@media (max-width: 1100px) {
    .der-post-grid { grid-template-columns: 1fr; }
}

/* Pagination */
.admin-pagination-wrap {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-top: 1.5rem;
}

.admin-pagination-summary { color: #475569; font-weight: 600; }

.admin-pagination { display: flex; gap: 8px; flex-wrap: wrap; }

.page-link-nav {
    min-width: 40px;
    height: 40px;
    padding: 0 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #0f172a;
    text-decoration: none;
    font-weight: 700;
    transition: 0.2s;
}

.page-link-nav:hover { background: #f1f5f9; text-decoration: none; }

.page-link-nav.is-active {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: #ffffff;
}

.page-link-nav.is-disabled { pointer-events: none; opacity: 0.45; }

/* Table card */
.admin-table-card h5 {
    font-weight: 800;
    font-size: 1.15rem;
    color: var(--text-main);
    padding-bottom: 1rem;
    border-bottom: 2px solid #f1f5f9;
    margin-bottom: 1.5rem;
}

/* Buttons */
.btn {
    padding: 0.75rem 1.5rem;
    font-weight: 700;
    border-radius: 12px;
    transition: all 0.3s;
    border: none;
}

.btn:hover {
    transform: translateY(-3px);
    filter: brightness(1.1);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.btn-primary   { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; }
.btn-success   { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
.btn-warning   { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
.btn-danger    { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
.btn-secondary { background: #64748b; color: white; }

.btn-outline-primary {
    border: 2px solid var(--primary-color);
    background: var(--primary-color);
    color: white;
}

.btn-outline-secondary {
    border: 2px solid #94a3b8;
    background: #94a3b8;
    color: white;
}

/* badge type */
.der-type-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 14px;
    border-radius: 999px;
    background: #ede9fe;
    color: #5b21b6;
    font-weight: 700;
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.der-file-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 14px;
    border-radius: 999px;
    border: 1px solid #e2e8f0;
    color: var(--text-main);
    text-decoration: none;
    font-weight: 600;
    font-size: .875rem;
    transition: 0.25s;
}

.der-file-link:hover { background: #f8fafc; border-color: #cbd5e1; }
</style>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

            <div class="dashboard-body__content p-4">
                <?php $this->view('set_flash'); ?>

                <!-- Hero Banner -->
                <div class="der-hero-banner mb-4">
                    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap" style="position:relative;z-index:2;">
                        <div>
                            <h3 class="fw-800 mb-1" style="font-size:1.8rem;letter-spacing:-0.5px;">Espace DER</h3>
                            <p class="mb-0">Gérez les publications du département : ajout, modification, suppression et fichiers joints.</p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="<?= ROOT ?>/Homes/der_espace#create-der-post" class="btn btn-warning">
                                <i class="bx bx-plus"></i> Nouvelle publication
                            </a>
                            <a href="<?= ROOT ?>/Homes/der_dashboard" class="btn btn-secondary">
                                <i class="bx bx-arrow-back"></i> Dashboard DER
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stat Cards (same as admin dashboard) -->
                <div class="row gy-4 mb-4">
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card common-card stat-card-total">
                            <div class="card-body">
                                <p class="mb-1">Total publications</p>
                                <h4><?= (int) ($derStats['total'] ?? 0) ?></h4>
                                <div class="stat-icon">📋</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card common-card stat-card-annonces">
                            <div class="card-body">
                                <p class="mb-1">Annonces</p>
                                <h4><?= (int) ($derStats['annonces'] ?? 0) ?></h4>
                                <div class="stat-icon">📢</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card common-card stat-card-evenements">
                            <div class="card-body">
                                <p class="mb-1">Événements</p>
                                <h4><?= (int) ($derStats['evenements'] ?? 0) ?></h4>
                                <div class="stat-icon">📅</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card common-card stat-card-resultats">
                            <div class="card-body">
                                <p class="mb-1">Résultats</p>
                                <h4><?= (int) ($derStats['resultats'] ?? 0) ?></h4>
                                <div class="stat-icon">📊</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card common-card stat-card-opps">
                            <div class="card-body">
                                <p class="mb-1">Opportunités</p>
                                <h4><?= (int) ($derStats['opportunites'] ?? 0) ?></h4>
                                <div class="stat-icon">💼</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card common-card stat-card-infos">
                            <div class="card-body">
                                <p class="mb-1">Informations</p>
                                <h4><?= (int) ($derStats['informations'] ?? 0) ?></h4>
                                <div class="stat-icon">ℹ️</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card common-card stat-card-files">
                            <div class="card-body">
                                <p class="mb-1">Fichiers</p>
                                <h4><?= (int) ($derStats['files'] ?? 0) ?></h4>
                                <div class="stat-icon">📎</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card common-card stat-card-archived">
                            <div class="card-body">
                                <p class="mb-1">Archives</p>
                                <h4><?= (int) ($derStats['archived'] ?? 0) ?></h4>
                                <div class="stat-icon">🗄️</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Two-column grid: Create form + Manage list -->
                <div class="der-post-grid">

                    <!-- CREATE FORM -->
                    <div class="form-card" id="create-der-post">
                        <div class="card-body">
                            <h5>Nouvelle publication DER</h5>
                            <form method="POST" action="<?= ROOT ?>/Homes/der_espace" enctype="multipart/form-data" class="row gy-3">
                                <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                                <div class="col-12">
                                    <label>Type</label>
                                    <select name="type" class="form-select" required>
                                        <?php foreach ($derAllowedTypes as $type): ?>
                                            <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars(ucfirst($type)) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label>Titre</label>
                                    <input type="text" name="titre" class="form-control" placeholder="Titre de la publication" required>
                                </div>
                                <div class="col-12">
                                    <label>Date de publication</label>
                                    <input type="date" name="date_publication" class="form-control" value="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="col-12">
                                    <label>Contenu</label>
                                    <textarea name="contenu" class="form-control" rows="6" placeholder="Contenu de la publication" required></textarea>
                                </div>
                                <div class="col-12">
                                    <label>Fichiers joints</label>
                                    <input type="file" name="fichiers[]" class="form-control" multiple>
                                    <small class="text-muted d-block mt-2">Formats : pdf, doc, docx, xls, xlsx, ppt, pptx, jpg, jpeg, png.</small>
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="save_der_post" class="btn btn-primary w-100">
                                        <i class="bx bx-send me-1"></i>Publier
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- LIST + FILTER -->
                    <div class="card common-card admin-table-card" id="manage-der-posts">
                        <div class="card-body p-4">
                            <h5>Gérer les publications</h5>

                            <!-- Filter Shelf -->
                            <div class="filter-shelf mb-3">
                                <form method="GET" action="<?= ROOT ?>/Homes/der_espace" class="row gy-2 gx-3" id="der-filter-form">
                                    <div class="col-md-4">
                                        <label>Recherche</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 border border-end-0" style="border-radius:10px 0 0 10px;border-color:#e2e8f0;">
                                                <i class="bx bx-search text-muted"></i>
                                            </span>
                                            <input type="text" name="search" value="<?= htmlspecialchars($derSearch) ?>" class="form-control border-start-0 ps-0" placeholder="Rechercher..." style="border-radius:0 10px 10px 0;border-color:#e2e8f0;">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Statut</label>
                                        <select name="visibility" class="form-select auto-submit-der">
                                            <option value="active" <?= $derVisibilityFilter === 'active' ? 'selected' : '' ?>>Actives</option>
                                            <option value="archived" <?= $derVisibilityFilter === 'archived' ? 'selected' : '' ?>>Archivées</option>
                                            <option value="all" <?= $derVisibilityFilter === 'all' ? 'selected' : '' ?>>Toutes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Type</label>
                                        <select name="type" class="form-select auto-submit-der">
                                            <option value="all" <?= $derTypeFilter === 'all' ? 'selected' : '' ?>>Tous</option>
                                            <?php foreach ($derAllowedTypes as $type): ?>
                                                <option value="<?= htmlspecialchars($type) ?>" <?= $derTypeFilter === $type ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($type)) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Du</label>
                                        <input type="date" name="date_from" value="<?= htmlspecialchars($derDateFrom) ?>" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Au</label>
                                        <input type="date" name="date_to" value="<?= htmlspecialchars($derDateTo) ?>" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Trier par</label>
                                        <select name="sort_by" class="form-select auto-submit-der">
                                            <option value="date" <?= $derSortBy === 'date' ? 'selected' : '' ?>>Date</option>
                                            <option value="title" <?= $derSortBy === 'title' ? 'selected' : '' ?>>Titre</option>
                                            <option value="type" <?= $derSortBy === 'type' ? 'selected' : '' ?>>Type</option>
                                            <option value="author" <?= $derSortBy === 'author' ? 'selected' : '' ?>>Auteur</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Ordre</label>
                                        <select name="sort_dir" class="form-select auto-submit-der">
                                            <option value="desc" <?= $derSortDir === 'desc' ? 'selected' : '' ?>>Desc</option>
                                            <option value="asc" <?= $derSortDir === 'asc' ? 'selected' : '' ?>>Asc</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Par page</label>
                                        <select name="per_page" class="form-select auto-submit-der">
                                            <option value="5" <?= $perPage === 5 ? 'selected' : '' ?>>5</option>
                                            <option value="10" <?= $perPage === 10 ? 'selected' : '' ?>>10</option>
                                            <option value="20" <?= $perPage === 20 ? 'selected' : '' ?>>20</option>
                                            <option value="50" <?= $perPage === 50 ? 'selected' : '' ?>>50</option>
                                        </select>
                                    </div>
                                </form>
                            </div>

                            <div id="der-posts-results">
                                <?php $this->view('Partials/der-posts-list', [
                                    'derPosts' => $derPosts,
                                    'derAllowedTypes' => $derAllowedTypes,
                                    'formAction' => ROOT . '/Homes/der_espace',
                                    'paginationBasePath' => 'Homes/der_espace',
                                    'detailReturnBasePath' => 'Homes/der_espace',
                                    'paginationQuery' => $paginationQuery,
                                    'currentPage' => $currentPage,
                                    'perPage' => $perPage,
                                    'totalPages' => $totalPages,
                                    'totalItems' => $totalItems,
                                    'activeEditPostId' => $activeEditPostId ?? 0,
                                ]); ?>
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
    var filterForm = document.getElementById('der-filter-form');
    var resultsContainer = document.getElementById('der-posts-results');
    var searchInput = filterForm ? filterForm.querySelector('input[name="search"]') : null;
    var debounceTimer = null;

    function bindDerPagination() {
        if (!resultsContainer) return;
        resultsContainer.querySelectorAll('.admin-pagination a.page-link-nav').forEach(function (link) {
            link.addEventListener('click', function (event) {
                if (link.classList.contains('is-disabled')) { event.preventDefault(); return; }
                event.preventDefault();
                loadDerResults(link.getAttribute('href'));
            });
        });
    }

    function loadDerResults(url) {
        if (!resultsContainer || !url) return;
        resultsContainer.style.opacity = '0.6';
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (payload) {
                if (!payload || payload.ok !== true || typeof payload.html !== 'string') return;
                resultsContainer.innerHTML = payload.html;
                resultsContainer.style.opacity = '1';
                bindDerPagination();
                window.history.replaceState({}, '', url);
                if (window.location.hash === '#manage-der-posts') {
                    var s = document.getElementById('manage-der-posts');
                    if (s) s.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            })
            .catch(function () { resultsContainer.style.opacity = '1'; });
    }

    document.querySelectorAll('.auto-submit-der').forEach(function (el) {
        el.addEventListener('change', function () {
            if (filterForm) loadDerResults(filterForm.getAttribute('action') + '?' + new URLSearchParams(new FormData(filterForm)).toString());
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                loadDerResults(filterForm.getAttribute('action') + '?' + new URLSearchParams(new FormData(filterForm)).toString());
            }, 300);
        });
    }

    if (filterForm) {
        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            loadDerResults(filterForm.getAttribute('action') + '?' + new URLSearchParams(new FormData(filterForm)).toString());
        });
    }

    bindDerPagination();
});
</script>
</body>
</html>
