<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Espace Département']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php $this->view('Partials/header'); ?>
<?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>
<?php
$departmentStats = $departmentStats ?? [];
$departmentPosts = $departmentPosts ?? [];
$departmentAllowedTypes = $departmentAllowedTypes ?? [];
$departmentTypeFilter = $departmentTypeFilter ?? 'all';
$departmentSearch = $departmentSearch ?? '';
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 6);
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalItems = max(0, (int) ($totalItems ?? count($departmentPosts)));
$paginationQuery = (string) ($paginationQuery ?? '');
?>
<style>
.department-card,
.department-card .card-body,
.department-card h3,
.department-card h4,
.department-card h5,
.department-card p,
.department-card span,
.department-card label {
    color: #0f172a;
}

.department-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.department-stat {
    background: #ffffff;
    border: 1px solid #dbe4ee;
    border-radius: 20px;
    padding: 18px;
}

.department-stat strong {
    display: block;
    font-size: 1.6rem;
    margin-top: 8px;
}

.department-post-card {
    background: #ffffff;
    border: 1px solid #dbe4ee;
    border-radius: 22px;
    padding: 22px;
    height: 100%;
}

.department-post-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    color: #64748b;
    font-size: .92rem;
    margin-bottom: 14px;
}

.department-file-link {
    display: inline-flex;
    align-items: center;
    margin-right: 8px;
    margin-bottom: 8px;
    padding: 8px 12px;
    border-radius: 999px;
    border: 1px solid #cbd5e1;
    color: #0f172a;
    text-decoration: none;
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

<main class="change-gradient">
    <section class="breadcrumb mb-0 bg-main-two position-relative z-index-1 overflow-hidden">
        <div class="container container-two">
            <div class="breadcrumb-two">
                <h1 class="breadcrumb-two__title text-white mb-2">Espace Département</h1>
                <p class="text-white mb-0"><?= htmlspecialchars($department['name'] ?? 'Département') ?></p>
            </div>
        </div>
    </section>

    <section class="padding-y-120" data-dynamic-block="department-space">
        <div class="container container-two">
            <div class="department-stats-grid">
                <div class="department-stat"><span>Annonces</span><strong><?= (int) ($departmentStats['annonces'] ?? 0) ?></strong></div>
                <div class="department-stat"><span>Informations</span><strong><?= (int) ($departmentStats['informations'] ?? 0) ?></strong></div>
                <div class="department-stat"><span>Evenements</span><strong><?= (int) ($departmentStats['evenements'] ?? 0) ?></strong></div>
                <div class="department-stat"><span>Resultats</span><strong><?= (int) ($departmentStats['resultats'] ?? 0) ?></strong></div>
                <div class="department-stat"><span>Opportunites</span><strong><?= (int) ($departmentStats['opportunites'] ?? 0) ?></strong></div>
            </div>

            <div class="card common-card department-card" data-dynamic-block="department-latest-posts">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-3">
                        <div>
                            <h5 class="mb-1">Publications du DER</h5>
                            <p class="mb-0 text-muted"><?= htmlspecialchars($department['subtitle'] ?? 'Informations officielles du département.') ?></p>
                        </div>
                        <span class="text-muted"><?= $totalItems ?> publication(s)</span>
                    </div>

                    <form method="GET" action="<?= ROOT ?>/Homes/departement" class="row gy-3 mb-4" id="department-filter-form">
                        <div class="col-md-5">
                            <input type="text" name="search" value="<?= htmlspecialchars($departmentSearch) ?>" class="common-input common-input--bg" placeholder="Rechercher une publication">
                        </div>
                        <div class="col-md-3">
                            <select name="type" class="common-input common-input--bg auto-submit-department">
                                <option value="all" <?= $departmentTypeFilter === 'all' ? 'selected' : '' ?>>Tous les types</option>
                                <?php foreach ($departmentAllowedTypes as $type): ?>
                                    <option value="<?= htmlspecialchars($type) ?>" <?= $departmentTypeFilter === $type ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($type)) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="per_page" class="common-input common-input--bg auto-submit-department">
                                <option value="6" <?= $perPage === 6 ? 'selected' : '' ?>>6</option>
                                <option value="12" <?= $perPage === 12 ? 'selected' : '' ?>>12</option>
                                <option value="24" <?= $perPage === 24 ? 'selected' : '' ?>>24</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                        </div>
                    </form>

                    <div id="department-posts-results">
                        <?php $this->view('Partials/department-posts-list', [
                            'departmentPosts' => $departmentPosts,
                            'currentPage' => $currentPage,
                            'perPage' => $perPage,
                            'totalPages' => $totalPages,
                            'totalItems' => $totalItems,
                            'paginationQuery' => $paginationQuery,
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php $this->view('Partials/footer'); ?>
</main>

<?php $this->view('Partials/scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var filterForm = document.getElementById('department-filter-form');
    var resultsContainer = document.getElementById('department-posts-results');
    var searchInput = filterForm ? filterForm.querySelector('input[name="search"]') : null;
    var debounceTimer = null;

    function bindDepartmentPagination() {
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
                loadDepartmentResults(link.getAttribute('href'));
            });
        });
    }

    function loadDepartmentResults(url) {
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
                bindDepartmentPagination();
                window.history.replaceState({}, '', url);
            })
            .catch(function () {
                resultsContainer.style.opacity = '1';
            });
    }

    document.querySelectorAll('.auto-submit-department').forEach(function (element) {
        element.addEventListener('change', function () {
            if (filterForm) {
                loadDepartmentResults(filterForm.getAttribute('action') + '?' + new URLSearchParams(new FormData(filterForm)).toString());
            }
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                loadDepartmentResults(filterForm.getAttribute('action') + '?' + new URLSearchParams(new FormData(filterForm)).toString());
            }, 300);
        });
    }

    if (filterForm) {
        filterForm.addEventListener('submit', function (event) {
            event.preventDefault();
            loadDepartmentResults(filterForm.getAttribute('action') + '?' + new URLSearchParams(new FormData(filterForm)).toString());
        });
    }

    bindDepartmentPagination();
});
</script>
</body>
</html>
