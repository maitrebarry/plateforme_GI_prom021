<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Corbeille DER']); ?>

<style>
:root {
    --primary-color: #6366f1;
    --primary-hover: #4f46e5;
    --secondary-color: #94a3b8;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --bg-light: #f1f5f9;
    --text-main: #0f172a;
    --text-muted: #64748b;
    --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

body {
    background-color: var(--bg-light);
    color: var(--text-main);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.dashboard-body {
    background-color: var(--bg-light);
    min-height: 100vh;
}

/* Animation d'entrée fluide */
.dashboard-body__content { 
    animation: fadeIn 0.5s ease-out; 
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Hero Banner Style Moderne (Identique au Dashboard) */
.der-trash-hero {
    background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
    border-radius: 20px;
    padding: 2.5rem 3rem;
    color: #fff;
    margin-bottom: 2rem;
    box-shadow: var(--card-shadow);
    position: relative;
    overflow: hidden;
}

.der-trash-hero::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
}

.der-trash-hero h3 { 
    color: #ffffff; 
    font-weight: 800; 
    letter-spacing: -0.5px;
}

.der-trash-hero p { 
    color: rgba(255,255,255,0.85); 
    font-size: 0.95rem;
}

/* Cartes Communes */
.common-card {
    border: none;
    border-radius: 20px;
    box-shadow: var(--card-shadow);
    background: #ffffff;
    margin-bottom: 24px;
    transition: transform 0.3s ease;
}

/* Style des Champs de Saisie (Inputs) */
.common-input--bg {
    background: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 12px !important;
    padding: 0.7rem 1rem !important;
    font-weight: 500;
    color: var(--text-main) !important;
    transition: all 0.2s;
}

.common-input--bg:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
    background: #fff !important;
}

/* Boutons */
.btn {
    font-weight: 700;
    border-radius: 12px;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s;
    border: none;
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
}

.btn-primary { 
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); 
    color: white; 
}

.btn-outline-white { 
    background: rgba(255,255,255,0.15); 
    border: 1px solid rgba(255,255,255,0.3); 
    color: white; 
    backdrop-filter: blur(5px);
}

.btn-outline-white:hover {
    background: #ffffff;
    color: var(--primary-color);
}

label {
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--text-muted);
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>

<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>

<?php
// Initialisation des variables
$derStats = $derStats ?? [];
$derPosts = $derPosts ?? [];
$derAllowedTypes = $derAllowedTypes ?? [];
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

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            
            <div class="dashboard-body__content p-4">
                <?php $this->view('set_flash'); ?>

                <div class="der-trash-hero">
                    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap" style="position:relative; z-index:2;">
                        <div>
                            <h3 class="mb-1">🗑️ Corbeille DER</h3>
                            <p class="mb-0">Retrouvez et restaurez vos publications archivées en un clic.</p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="<?= ROOT ?>/Homes/der_espace" class="btn btn-outline-white">
                                <i class="bx bx-arrow-back"></i> Retour à la gestion
                            </a>
                            <div class="btn btn-light border-0 fw-bold px-4" style="background: rgba(255,255,255,0.9); color: var(--primary-color);">
                                Archives: <?= (int) ($derStats['archived'] ?? 0) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card common-card">
                    <div class="card-body p-4">
                        <form method="GET" action="<?= ROOT ?>/Homes/der_corbeille" class="row gy-3 mb-4" id="der-trash-filter-form">
                            <input type="hidden" name="visibility" value="archived">
                            
                            <div class="col-md-4">
                                <label>Rechercher</label>
                                <input type="text" name="search" value="<?= htmlspecialchars($derSearch) ?>" class="common-input common-input--bg w-100" placeholder="Titre ou contenu...">
                            </div>

                            <div class="col-md-2">
                                <label>Catégorie</label>
                                <select name="type" class="common-input common-input--bg w-100">
                                    <option value="all" <?= $derTypeFilter === 'all' ? 'selected' : '' ?>>Tous types</option>
                                    <?php foreach ($derAllowedTypes as $type): ?>
                                        <option value="<?= htmlspecialchars($type) ?>" <?= $derTypeFilter === $type ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($type)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Depuis le</label>
                                <input type="date" name="date_from" value="<?= htmlspecialchars($derDateFrom) ?>" class="common-input common-input--bg w-100">
                            </div>

                            <div class="col-md-2">
                                <label>Jusqu'au</label>
                                <input type="date" name="date_to" value="<?= htmlspecialchars($derDateTo) ?>" class="common-input common-input--bg w-100">
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                    <i class="bx bx-filter-alt"></i> Filtrer
                                </button>
                            </div>

                            <div class="col-12 mt-3 pt-3 border-top d-flex gap-4 flex-wrap align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="small fw-bold text-muted">Trier par :</span>
                                    <select name="sort_by" class="common-input--bg border-0 py-1 px-2 small">
                                        <option value="date" <?= $derSortBy === 'date' ? 'selected' : '' ?>>Date</option>
                                        <option value="title" <?= $derSortBy === 'title' ? 'selected' : '' ?>>Titre</option>
                                        <option value="author" <?= $derSortBy === 'author' ? 'selected' : '' ?>>Auteur</option>
                                    </select>
                                    <select name="sort_dir" class="common-input--bg border-0 py-1 px-2 small">
                                        <option value="desc" <?= $derSortDir === 'desc' ? 'selected' : '' ?>>Décroissant</option>
                                        <option value="asc" <?= $derSortDir === 'asc' ? 'selected' : '' ?>>Croissant</option>
                                    </select>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <span class="small fw-bold text-muted">Affichage :</span>
                                    <select name="per_page" class="common-input--bg border-0 py-1 px-2 small">
                                        <option value="10" <?= $perPage === 10 ? 'selected' : '' ?>>10 par page</option>
                                        <option value="20" <?= $perPage === 20 ? 'selected' : '' ?>>20 par page</option>
                                        <option value="50" <?= $perPage === 50 ? 'selected' : '' ?>>50 par page</option>
                                    </select>
                                </div>
                            </div>
                        </form>

                        <div class="mt-4">
                            <?php $this->view('Partials/der-posts-list', [
                                'derPosts' => $derPosts,
                                'derAllowedTypes' => $derAllowedTypes,
                                'formAction' => ROOT . '/Homes/der_corbeille',
                                'paginationBasePath' => 'Homes/der_corbeille',
                                'detailReturnBasePath' => 'Homes/der_corbeille',
                                'paginationQuery' => $paginationQuery,
                                'currentPage' => $currentPage,
                                'perPage' => $perPage,
                                'totalPages' => $totalPages,
                                'totalItems' => $totalItems,
                                'activeEditPostId' => 0,
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>

<?php $this->view('Partials/scripts'); ?>
</body>
</html>