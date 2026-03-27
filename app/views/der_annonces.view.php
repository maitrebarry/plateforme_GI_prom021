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
.der-manage-card,
.der-manage-card .card-body,
.der-manage-card h3,
.der-manage-card h4,
.der-manage-card h5,
.der-manage-card p,
.der-manage-card span,
.der-manage-card label {
    color: #0f172a;
}

.der-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 16px;
    margin-bottom: 22px;
}

.der-stat {
    padding: 16px 18px;
    border-radius: 22px;
    background: #ffffff;
    border: 1px solid #dbe4ee;
}

.der-stat strong {
    display: block;
    font-size: 1.75rem;
    line-height: 1.1;
    margin-top: 8px;
}

.der-post-card {
    padding: 20px;
    border-radius: 22px;
    border: 1px solid #dbe4ee;
    background: #ffffff;
    margin-bottom: 16px;
}

.der-post-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    color: #64748b;
    font-size: .92rem;
    margin-bottom: 12px;
}

.der-type-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 999px;
    background: #dbeafe;
    color: #1d4ed8;
    font-weight: 700;
    font-size: .82rem;
}

.der-file-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 999px;
    border: 1px solid #cbd5e1;
    color: #0f172a;
    text-decoration: none;
    margin-right: 8px;
    margin-bottom: 8px;
}

.der-post-grid {
    display: grid;
    grid-template-columns: 1.1fr 1.4fr;
    gap: 24px;
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

@media (max-width: 991px) {
    .der-post-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-4 der-manage-card">
                <?php $this->view('set_flash'); ?>

                <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4">
                    <div>
                        <h3 class="mb-1">Espace DER</h3>
                        <p class="mb-0 text-muted">Le DER peut ici ajouter, modifier, supprimer et enrichir les publications du departement avec des fichiers joints.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?= ROOT ?>/Homes/der_espace#create-der-post" class="btn btn-primary">Ajouter une publication</a>
                        <a href="<?= ROOT ?>/Homes/der_espace#manage-der-posts" class="btn btn-outline-primary">Modifier ou supprimer</a>
                        <a href="<?= ROOT ?>/Homes/der_dashboard" class="btn btn-outline-secondary">Retour dashboard DER</a>
                    </div>
                </div>

                <div class="der-stats-grid">
                    <div class="der-stat"><span>Total</span><strong><?= (int) ($derStats['total'] ?? 0) ?></strong></div>
                    <div class="der-stat"><span>Annonces</span><strong><?= (int) ($derStats['annonces'] ?? 0) ?></strong></div>
                    <div class="der-stat"><span>Informations</span><strong><?= (int) ($derStats['informations'] ?? 0) ?></strong></div>
                    <div class="der-stat"><span>Evenements</span><strong><?= (int) ($derStats['evenements'] ?? 0) ?></strong></div>
                    <div class="der-stat"><span>Resultats</span><strong><?= (int) ($derStats['resultats'] ?? 0) ?></strong></div>
                    <div class="der-stat"><span>Opportunites</span><strong><?= (int) ($derStats['opportunites'] ?? 0) ?></strong></div>
                    <div class="der-stat"><span>Fichiers</span><strong><?= (int) ($derStats['files'] ?? 0) ?></strong></div>
                    <div class="der-stat"><span>Archives</span><strong><?= (int) ($derStats['archived'] ?? 0) ?></strong></div>
                </div>

                <div class="der-post-grid">
                    <div class="card common-card" id="create-der-post">
                        <div class="card-body">
                            <h5 class="mb-3">Nouvelle publication DER</h5>
                            <form method="POST" action="<?= ROOT ?>/Homes/der_espace" enctype="multipart/form-data" class="row gy-3">
                                <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                                <div class="col-12">
                                    <label class="form-label">Type</label>
                                    <select name="type" class="common-input common-input--bg" required>
                                        <?php foreach ($derAllowedTypes as $type): ?>
                                            <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars(ucfirst($type)) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Titre</label>
                                    <input type="text" name="titre" class="common-input common-input--bg" placeholder="Titre de la publication" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Date de publication</label>
                                    <input type="date" name="date_publication" class="common-input common-input--bg" value="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Contenu</label>
                                    <textarea name="contenu" class="common-input common-input--bg" rows="6" placeholder="Contenu de la publication" required></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Fichiers joints</label>
                                    <input type="file" name="fichiers[]" class="common-input common-input--bg" multiple>
                                    <small class="text-muted d-block mt-2">Formats autorises: pdf, doc, docx, xls, xlsx, ppt, pptx, jpg, jpeg, png.</small>
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="save_der_post" class="btn btn-primary w-100">Publier</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card common-card" id="manage-der-posts">
                        <div class="card-body">
                            <form method="GET" action="<?= ROOT ?>/Homes/der_espace" class="row gy-3 mb-4" id="der-filter-form">
                                <div class="col-md-3">
                                    <input type="text" name="search" value="<?= htmlspecialchars($derSearch) ?>" class="common-input common-input--bg" placeholder="Rechercher une publication">
                                </div>
                                <div class="col-md-2">
                                    <select name="visibility" class="common-input common-input--bg auto-submit-der">
                                        <option value="active" <?= $derVisibilityFilter === 'active' ? 'selected' : '' ?>>Actives</option>
                                        <option value="archived" <?= $derVisibilityFilter === 'archived' ? 'selected' : '' ?>>Archivees</option>
                                        <option value="all" <?= $derVisibilityFilter === 'all' ? 'selected' : '' ?>>Toutes</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="type" class="common-input common-input--bg auto-submit-der">
                                        <option value="all" <?= $derTypeFilter === 'all' ? 'selected' : '' ?>>Tous types</option>
                                        <?php foreach ($derAllowedTypes as $type): ?>
                                            <option value="<?= htmlspecialchars($type) ?>" <?= $derTypeFilter === $type ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($type)) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <input type="date" name="date_from" value="<?= htmlspecialchars($derDateFrom) ?>" class="common-input common-input--bg">
                                </div>
                                <div class="col-md-1">
                                    <input type="date" name="date_to" value="<?= htmlspecialchars($derDateTo) ?>" class="common-input common-input--bg">
                                </div>
                                <div class="col-md-1">
                                    <select name="sort_by" class="common-input common-input--bg auto-submit-der">
                                        <option value="date" <?= $derSortBy === 'date' ? 'selected' : '' ?>>Date</option>
                                        <option value="title" <?= $derSortBy === 'title' ? 'selected' : '' ?>>Titre</option>
                                        <option value="type" <?= $derSortBy === 'type' ? 'selected' : '' ?>>Type</option>
                                        <option value="author" <?= $derSortBy === 'author' ? 'selected' : '' ?>>Auteur</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <select name="sort_dir" class="common-input common-input--bg auto-submit-der">
                                        <option value="desc" <?= $derSortDir === 'desc' ? 'selected' : '' ?>>Desc</option>
                                        <option value="asc" <?= $derSortDir === 'asc' ? 'selected' : '' ?>>Asc</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <select name="per_page" class="common-input common-input--bg auto-submit-der">
                                        <option value="5" <?= $perPage === 5 ? 'selected' : '' ?>>5</option>
                                        <option value="10" <?= $perPage === 10 ? 'selected' : '' ?>>10</option>
                                        <option value="20" <?= $perPage === 20 ? 'selected' : '' ?>>20</option>
                                        <option value="50" <?= $perPage === 50 ? 'selected' : '' ?>>50</option>
                                    </select>
                                </div>
                            </form>
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
        if (!resultsContainer) {
            return;
        }

        resultsContainer.querySelectorAll('.admin-pagination a.page-link-nav').forEach(function (link) {
            link.addEventListener('click', function (event) {
                if (link.classList.contains('is-disabled')) {
                    event.preventDefault();
                    return;
                }

                event.preventDefault();
                loadDerResults(link.getAttribute('href'));
            });
        });
    }

    function loadDerResults(url) {
        if (!resultsContainer || !url) {
            return;
        }

        resultsContainer.style.opacity = '0.6';
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(function (response) { return response.json(); })
            .then(function (payload) {
                if (!payload || payload.ok !== true || typeof payload.html !== 'string') {
                    return;
                }

                resultsContainer.innerHTML = payload.html;
                resultsContainer.style.opacity = '1';
                bindDerPagination();
                window.history.replaceState({}, '', url);
                if (window.location.hash === '#manage-der-posts') {
                    var manageSection = document.getElementById('manage-der-posts');
                    if (manageSection) {
                        manageSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            })
            .catch(function () {
                resultsContainer.style.opacity = '1';
            });
    }

    document.querySelectorAll('.auto-submit-der').forEach(function (element) {
        element.addEventListener('change', function () {
            if (filterForm) {
                loadDerResults(filterForm.getAttribute('action') + '?' + new URLSearchParams(new FormData(filterForm)).toString());
            }
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
        filterForm.addEventListener('submit', function (event) {
            event.preventDefault();
            loadDerResults(filterForm.getAttribute('action') + '?' + new URLSearchParams(new FormData(filterForm)).toString());
        });
    }

    bindDerPagination();
});
</script>
</body>
</html>
