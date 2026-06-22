<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Espace Département']); ?>

<body class="public-site public-department">
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
    $departmentStatCards = [
        ['label' => 'Annonces', 'value' => (int) ($departmentStats['annonces'] ?? 0), 'icon' => 'bx bx-megaphone', 'class' => 'annonce'],
        ['label' => 'Informations', 'value' => (int) ($departmentStats['informations'] ?? 0), 'icon' => 'bx bx-info-circle', 'class' => 'information'],
        ['label' => 'Événements', 'value' => (int) ($departmentStats['evenements'] ?? 0), 'icon' => 'bx bx-calendar-event', 'class' => 'evenement'],
        ['label' => 'Résultats', 'value' => (int) ($departmentStats['resultats'] ?? 0), 'icon' => 'bx bx-award', 'class' => 'resultat'],
        ['label' => 'Opportunités', 'value' => (int) ($departmentStats['opportunites'] ?? 0), 'icon' => 'bx bx-briefcase-alt-2', 'class' => 'opportunite'],
    ];
    ?>
    <style>
        /* Import Boxicons fallback CDN */
        @import url('https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css');

        /* Fluid Container Layout */
        .public-department .container,
        .public-department .container-two {
            max-width: 1680px !important;
            width: 95% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            padding-left: 20px !important;
            padding-right: 20px !important;
        }

        /* Background & Breadcrumb Override */
        .public-department.change-gradient {
            background: linear-gradient(180deg, #fffaf4 0%, #f7f2ea 35%, #ffffff 70%, #fffaf4 100%) !important;
        }

        .public-department .breadcrumb {
            background: linear-gradient(135deg, #123c34, #1c5248) !important;
            box-shadow: 0 10px 30px -10px rgba(18, 60, 52, 0.5) !important;
            border-radius: 0 0 30px 30px !important;
            margin-bottom: 24px !important;
        }

        .public-department .department-card,
        .public-department .department-card .card-body,
        .public-department .department-card h3,
        .public-department .department-card h4,
        .public-department .department-card h5,
        .public-department .department-card p,
        .public-department .department-card span,
        .public-department .department-card label {
            color: #171512;
        }

        .public-department .department-card {
            background: #ffffff !important;
            border-radius: 24px !important;
            border: 1px solid rgba(23, 21, 18, 0.08) !important;
            box-shadow: 0 20px 55px -35px rgba(23, 21, 18, 0.12) !important;
            overflow: hidden;
        }

        .public-department .department-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }

        /* Stunning Stat Cards */
        .public-department .department-stat {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(189, 74, 31, 0.08);
            border-radius: 20px;
            padding: 24px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 10px 25px -15px rgba(23, 21, 18, 0.08);
        }

        .public-department .department-stat::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(189, 74, 31, 0.06) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            transition: transform 0.5s ease;
        }

        .public-department .department-stat:hover {
            transform: translateY(-6px);
            border-color: rgba(189, 74, 31, 0.25);
            background: #ffffff;
            box-shadow: 0 20px 45px -20px rgba(189, 74, 31, 0.2);
        }

        .public-department .department-stat:hover::before {
            transform: scale(1.5);
        }

        .public-department .department-stat strong {
            display: block;
            font-size: 2.2rem;
            font-weight: 850;
            margin-top: 10px;
            font-family: "Sora", sans-serif;
            color: #171512;
        }

        .public-department .department-stat__icon {
            font-size: 1.8rem;
            color: #bd4a1f;
            margin-bottom: 12px;
            display: inline-block;
        }

        /* Post Cards with Lift Effects */
        .public-department .department-post-card {
            background: #ffffff;
            border: 1px solid rgba(23, 21, 18, 0.08);
            border-radius: 22px;
            padding: 24px;
            height: 100%;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 12px 30px -15px rgba(23, 21, 18, 0.05);
        }

        .public-department .department-post-card:hover {
            transform: translateY(-8px);
            border-color: rgba(189, 74, 31, 0.25);
            box-shadow: 0 25px 60px -25px rgba(189, 74, 31, 0.18), 0 10px 20px -10px rgba(18, 60, 52, 0.06);
        }

        .public-department .department-post-card strong {
            font-family: "Sora", sans-serif;
            font-size: 1.25rem;
            color: #171512;
            margin-bottom: 12px;
            display: block;
        }

        .public-department .department-post-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            color: #67615a;
            font-size: 0.9rem;
            margin-bottom: 16px;
            font-weight: 600;
        }

        .public-department .department-post-meta span {
            background: rgba(189, 74, 31, 0.06);
            padding: 4px 12px;
            border-radius: 999px;
            color: #bd4a1f;
        }

        .public-department .department-file-link {
            display: inline-flex;
            align-items: center;
            margin-right: 8px;
            margin-bottom: 8px;
            padding: 8px 16px;
            border-radius: 999px;
            border: 1px solid #eaded3;
            background: #fdf5ef;
            color: #873115;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .public-department .department-file-link:hover {
            background: #bd4a1f;
            color: #ffffff;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px -5px rgba(189, 74, 31, 0.4);
        }

        .public-department .admin-pagination-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-top: 24px;
        }

        .public-department .admin-pagination-summary {
            color: #67615a;
            font-weight: 700;
        }

        .public-department .admin-pagination {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .public-department .page-link-nav {
            min-width: 42px;
            height: 42px;
            padding: 0 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            border: 1px solid #eaded3;
            background: #ffffff;
            color: #171512;
            text-decoration: none;
            font-weight: 750;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .public-department .page-link-nav:hover:not(.is-disabled) {
            background: #bd4a1f;
            color: #ffffff;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -8px rgba(189, 74, 31, 0.5);
        }

        .public-department .page-link-nav.is-active {
            background: #bd4a1f;
            border-color: transparent;
            color: #ffffff;
            box-shadow: 0 10px 20px -8px rgba(189, 74, 31, 0.5);
        }

        .public-department .page-link-nav.is-disabled {
            pointer-events: none;
            opacity: 0.45;
        }

        /* Animations for initial load */
        .public-department .department-stat,
        .public-department .department-post-card {
            animation: public-card-rise 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .public-department .department-stat:nth-child(2) {
            animation-delay: 0.1s;
        }

        .public-department .department-stat:nth-child(3) {
            animation-delay: 0.2s;
        }

        .public-department .department-stat:nth-child(4) {
            animation-delay: 0.3s;
        }

        .public-department .department-stat:nth-child(5) {
            animation-delay: 0.4s;
        }

        .public-department .department-post-card:nth-child(1) {
            animation-delay: 0.15s;
        }

        .public-department .department-post-card:nth-child(2) {
            animation-delay: 0.25s;
        }

        .public-department .department-post-card:nth-child(3) {
            animation-delay: 0.35s;
        }

        .public-department .department-post-card:nth-child(4) {
            animation-delay: 0.45s;
        }

        @keyframes public-card-rise {
            from {
                opacity: 0;
                transform: translateY(35px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
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
                    <?php foreach ($departmentStatCards as $stat): ?>
                        <div class="department-stat department-stat--<?= htmlspecialchars($stat['class']) ?>">
                            <span class="department-stat__icon"><i class='<?= htmlspecialchars($stat['icon']) ?>'></i></span>
                            <span class="department-stat__label"><?= htmlspecialchars($stat['label']) ?></span>
                            <strong><?= (int) $stat['value'] ?></strong>
                        </div>
                    <?php endforeach; ?>
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
        document.addEventListener('DOMContentLoaded', function() {
            var filterForm = document.getElementById('department-filter-form');
            var resultsContainer = document.getElementById('department-posts-results');
            var searchInput = filterForm ? filterForm.querySelector('input[name="search"]') : null;
            var debounceTimer = null;

            function bindDepartmentPagination() {
                if (!resultsContainer) {
                    return;
                }

                resultsContainer.querySelectorAll('.admin-pagination a.page-link-nav').forEach(function(link) {
                    link.addEventListener('click', function(event) {
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
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(payload) {
                        if (!payload || payload.ok !== true || typeof payload.html !== 'string') {
                            return;
                        }

                        resultsContainer.innerHTML = payload.html;
                        resultsContainer.style.opacity = '1';
                        bindDepartmentPagination();
                        window.history.replaceState({}, '', url);
                    })
                    .catch(function() {
                        resultsContainer.style.opacity = '1';
                    });
            }

            document.querySelectorAll('.auto-submit-department').forEach(function(element) {
                element.addEventListener('change', function() {
                    if (filterForm) {
                        loadDepartmentResults(filterForm.getAttribute('action') + '?' + new URLSearchParams(new FormData(filterForm)).toString());
                    }
                });
            });

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function() {
                        loadDepartmentResults(filterForm.getAttribute('action') + '?' + new URLSearchParams(new FormData(filterForm)).toString());
                    }, 300);
                });
            }

            if (filterForm) {
                filterForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    loadDepartmentResults(filterForm.getAttribute('action') + '?' + new URLSearchParams(new FormData(filterForm)).toString());
                });
            }

            bindDepartmentPagination();
        });
    </script>
</body>

</html>