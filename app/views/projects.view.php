<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Projets']); ?>
<body class="public-site public-list">
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php $this->view('Partials/header'); ?>
<?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

<?php
$projects = $projects ?? [];
$projectCategories = $projectCategories ?? [];
$projectSearch = (string) ($projectSearch ?? '');
$selectedCategoryId = !empty($selectedCategoryId) ? (int) $selectedCategoryId : null;
$projectCount = (int) ($projectCount ?? count($projects));
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 5);
$totalPages = max(1, (int) ($totalPages ?? 1));
$projectSort = (string) ($projectSort ?? 'recent');
$hasFilters = ($projectSearch !== '') || !empty($selectedCategoryId);
$sortLabels = [
    'appreciated' => 'Plus pertinents',
    'recent'      => 'Plus récents',
    'rating'      => 'Mieux notés',
    'likes'       => 'Plus aimés',
    'title'       => 'A → Z',
];
?>

<main>
    <style>
        .pl-wrap { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 16px; }

        /* ===== Hero + recherche ===== */
        .pl-hero { position: relative; overflow: hidden; background: linear-gradient(160deg, var(--ds-brand-700), var(--ds-brand-800)); color: #fff; padding: 42px 0 50px; }
        .pl-hero::before { content: ''; position: absolute; top: -90px; right: -70px; width: 290px; height: 290px; border-radius: 50%; background: radial-gradient(circle, rgba(224,168,46,.22), transparent 70%); pointer-events: none; }
        .pl-hero .pl-wrap { position: relative; z-index: 1; }
        .pl-kicker { display: inline-flex; align-items: center; gap: 7px; background: rgba(255,255,255,.13); border: 1px solid rgba(255,255,255,.22); color: #fff; font-weight: 700; font-size: .76rem; padding: 6px 14px; border-radius: var(--ds-radius-pill); }
        .pl-hero h1 { font-family: var(--ds-font-heading); font-weight: 800; font-size: 1.8rem; line-height: 1.2; margin: 14px 0 8px; color: #fff; overflow-wrap: break-word; }
        .pl-hero p { color: rgba(231,240,235,.82); font-size: 1rem; line-height: 1.55; margin: 0 0 20px; max-width: 560px; }
        .pl-search { display: flex; align-items: center; gap: 6px; background: #fff; border-radius: var(--ds-radius-lg); padding: 7px; box-shadow: var(--ds-shadow-md); max-width: 640px; }
        .pl-search > i { color: var(--ds-muted); font-size: 1.3rem; padding-left: 8px; flex-shrink: 0; }
        .pl-search input { flex: 1; min-width: 0; border: 0; outline: 0; background: transparent; font-size: .98rem; color: var(--ds-ink); padding: 10px 4px; font-family: var(--ds-font-sans); }
        .pl-search button { flex-shrink: 0; display: inline-flex; align-items: center; gap: 7px; background: var(--ds-brand-600); color: #fff; border: 0; font-weight: 700; padding: 11px 18px; border-radius: var(--ds-radius); cursor: pointer; transition: background var(--ds-transition); }
        .pl-search button:hover { background: var(--ds-brand-700); }

        /* ===== Section + barre d'outils ===== */
        .pl-section { padding: 24px 0 64px; }
        .pl-toolbar { display: flex; flex-direction: column; gap: 14px; margin-bottom: 16px; }
        .pl-chips { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 4px; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
        .pl-chips::-webkit-scrollbar { display: none; }
        .pl-chip { flex-shrink: 0; display: inline-flex; align-items: center; gap: 6px; background: var(--ds-surface); border: 1px solid var(--ds-border); color: var(--ds-ink); font-weight: 600; font-size: .85rem; padding: 9px 15px; border-radius: var(--ds-radius-pill); cursor: pointer; white-space: nowrap; transition: all var(--ds-transition); }
        .pl-chip:hover { border-color: var(--ds-brand-300); color: var(--ds-brand-700); }
        .pl-chip.is-active { background: var(--ds-brand-600); border-color: var(--ds-brand-600); color: #fff; }
        .pl-chip span { font-size: .72rem; opacity: .7; font-weight: 700; }
        .pl-toolbar__row { display: flex; align-items: center; justify-content: space-between; gap: 12px 16px; flex-wrap: wrap; }
        .pl-count { color: var(--ds-muted); font-size: .9rem; font-weight: 600; }
        .pl-count strong { color: var(--ds-ink-strong); }
        .pl-toolbar__right { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .pl-reset { color: var(--ds-brand-600); font-weight: 700; font-size: .85rem; text-decoration: none; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; white-space: nowrap; background: none; border: 0; }
        .pl-reset:hover { color: var(--ds-brand-700); }
        .pl-field { display: flex; align-items: center; gap: 7px; }
        .pl-field label { color: var(--ds-muted); font-size: .85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap; }
        .pl-field label .bx { font-size: 1.05rem; }
        .pl-field select { border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius); padding: 8px 12px; font-size: .88rem; color: var(--ds-ink); background: var(--ds-surface); cursor: pointer; font-family: var(--ds-font-sans); }

        /* ===== Carte projet (cohérente avec l'accueil) ===== */
        .project-card-modern { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); overflow: hidden; height: 100%; display: flex; flex-direction: column; transition: transform var(--ds-transition), box-shadow var(--ds-transition), border-color var(--ds-transition); }
        .project-card-modern:hover { transform: translateY(-4px); box-shadow: var(--ds-shadow-md); border-color: var(--ds-brand-200); }
        .project-visual { position: relative; overflow: hidden; }
        .project-carousel, .project-slide { line-height: 0; }
        .project-slide img { width: 100%; height: 200px; object-fit: cover; transition: transform .5s ease; }
        .project-card-modern:hover .project-slide img { transform: scale(1.05); }
        .project-category { position: absolute; top: 12px; left: 12px; z-index: 2; display: inline-flex; align-items: center; gap: 5px; background: rgba(255,255,255,.92); color: var(--ds-brand-700); font-weight: 700; font-size: .74rem; padding: 5px 11px; border-radius: var(--ds-radius-pill); }
        .project-image-count { position: absolute; top: 12px; right: 12px; z-index: 2; display: inline-flex; align-items: center; gap: 4px; background: rgba(15,23,42,.6); color: #fff; font-size: .74rem; padding: 5px 10px; border-radius: var(--ds-radius-pill); }
        .project-body { padding: 16px; display: flex; flex-direction: column; flex: 1; }
        .project-topline { margin-bottom: 8px; }
        .project-meta { display: flex; flex-wrap: wrap; gap: 12px; color: var(--ds-muted); font-size: .82rem; }
        .project-meta span { display: inline-flex; align-items: center; gap: 5px; }
        .project-stats { display: flex; flex-wrap: wrap; gap: 8px; margin: 4px 0 10px; }
        .project-stat { display: inline-flex; align-items: center; gap: 5px; font-size: .78rem; font-weight: 600; padding: 4px 10px; border-radius: var(--ds-radius-pill); background: var(--ds-surface-2); color: var(--ds-muted); }
        .project-stat--rating { background: var(--ds-accent-soft); color: #8a6310; }
        .project-stat--likes { background: var(--ds-danger-soft); color: #a3322e; }
        .project-stat--reviews { background: var(--ds-brand-50); color: var(--ds-brand-700); }
        .project-title { font-family: var(--ds-font-heading); font-size: 1.12rem; font-weight: 800; margin: 0 0 6px; line-height: 1.3; }
        .project-title a { color: var(--ds-ink-strong); text-decoration: none; }
        .project-title a:hover { color: var(--ds-brand-600); }
        .project-text { color: var(--ds-muted); font-size: .9rem; line-height: 1.55; margin: 0 0 12px; }
        .tech-list { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 14px; }
        .tech-pill { font-size: .74rem; font-weight: 600; background: var(--ds-surface-2); color: var(--ds-ink); border: 1px solid var(--ds-border); padding: 4px 10px; border-radius: var(--ds-radius-pill); }
        .project-actions { margin-top: auto; display: flex; align-items: center; justify-content: space-between; gap: 10px; }
        .project-link { display: inline-flex; align-items: center; gap: 6px; font-weight: 700; font-size: .9rem; color: var(--ds-brand-600); text-decoration: none; }
        .project-link:hover { color: var(--ds-brand-700); }
        .project-flag { font-size: .76rem; font-weight: 600; color: var(--ds-danger); display: inline-flex; align-items: center; gap: 4px; }

        /* Carrousel (slick) */
        .project-carousel .slick-prev, .project-carousel .slick-next { width: 34px; height: 34px; background: rgba(255,255,255,.9); border-radius: 50%; z-index: 3; display: flex !important; align-items: center; justify-content: center; border: 0; }
        .project-carousel .slick-prev { left: 10px; } .project-carousel .slick-next { right: 10px; }
        .project-carousel .slick-prev::before, .project-carousel .slick-next::before { content: ''; }
        .project-carousel .slick-prev .bx, .project-carousel .slick-next .bx { color: var(--ds-brand-700); font-size: 1.3rem; }
        .project-carousel .slick-dots { bottom: 10px; } .project-carousel .slick-dots li button::before { color: #fff; opacity: .8; }
        .project-carousel .slick-dots li.slick-active button::before { color: var(--ds-brand-300); opacity: 1; }

        /* Bandeau résultats */
        .results-chip { display: inline-flex; align-items: center; gap: 7px; background: var(--ds-brand-50); color: var(--ds-brand-700); font-weight: 600; font-size: .85rem; padding: 7px 14px; border-radius: var(--ds-radius-pill); margin-bottom: 18px; }

        /* État vide + pagination (partial) */
        .empty-projects { text-align: center; background: var(--ds-surface); border: 1px dashed var(--ds-border-strong); border-radius: var(--ds-radius-lg); padding: 40px 24px; }
        .empty-projects__icon, .empty-projects .bx { font-size: 2.4rem; color: var(--ds-brand-400); margin-bottom: 10px; }
        .empty-projects__eyebrow { display: inline-block; color: var(--ds-brand-600); font-weight: 700; font-size: .78rem; text-transform: uppercase; letter-spacing: .06em; }
        .empty-projects h3 { font-family: var(--ds-font-heading); color: var(--ds-ink-strong); margin: 8px 0 6px; }
        .empty-projects p { color: var(--ds-muted); max-width: 460px; margin: 0 auto 16px; }
        .empty-projects__actions { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; }
        .hero-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: var(--ds-brand-600); color: #fff; font-weight: 700; font-size: .92rem; padding: 11px 20px; border-radius: var(--ds-radius-pill); border: 0; cursor: pointer; text-decoration: none; transition: all var(--ds-transition); }
        .hero-btn:hover { background: var(--ds-brand-700); color: #fff; transform: translateY(-1px); }
        .hero-btn-outline { display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: transparent; color: var(--ds-brand-700); font-weight: 700; font-size: .92rem; padding: 11px 20px; border-radius: var(--ds-radius-pill); border: 1px solid var(--ds-brand-300); cursor: pointer; text-decoration: none; transition: all var(--ds-transition); }
        .hero-btn-outline:hover { background: var(--ds-brand-50); color: var(--ds-brand-800); }
        .project-pagination-wrap { margin-top: 26px; text-align: center; }
        .project-pagination-wrap > p, .project-pagination-summary { color: var(--ds-muted); font-size: .85rem; margin-bottom: 10px; }
        .project-pagination { display: flex; flex-wrap: wrap; gap: 6px; justify-content: center; }
        .page-nav { min-width: 38px; height: 38px; padding: 0 10px; border: 1px solid var(--ds-border); background: var(--ds-surface); color: var(--ds-ink); border-radius: var(--ds-radius); font-weight: 600; font-size: .88rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: all var(--ds-transition); }
        .page-nav:hover:not(:disabled) { border-color: var(--ds-brand-400); color: var(--ds-brand-700); }
        .page-nav.is-active { background: var(--ds-brand-600); border-color: var(--ds-brand-600); color: #fff; }
        .page-nav:disabled { opacity: .4; cursor: not-allowed; }

        @media (min-width: 768px) {
            .pl-hero { padding: 60px 0 68px; }
            .pl-hero h1 { font-size: 2.5rem; }
            .pl-toolbar { flex-direction: row; align-items: center; justify-content: space-between; }
            .pl-chips { flex-wrap: wrap; overflow: visible; }
            .pl-toolbar__row { flex: 1; }
        }
    </style>

    <!-- ===== HERO + RECHERCHE ===== -->
    <section class="pl-hero">
        <div class="pl-wrap">
            <span class="pl-kicker"><i class='bx bx-collection'></i> Catalogue</span>
            <h1>Explorez tous les projets</h1>
            <p>Recherchez par mot-clé, filtrez par catégorie et trouvez la solution numérique qu'il vous faut.</p>
            <form class="pl-search" id="plForm" method="get" action="<?= ROOT ?>/Homes/projects" role="search">
                <i class='bx bx-search'></i>
                <input type="search" id="plSearch" name="search" value="<?= htmlspecialchars($projectSearch) ?>" placeholder="Rechercher un projet, une techno, un domaine…" aria-label="Rechercher" autocomplete="off">
                <input type="hidden" id="plCategory" name="category" value="<?= $selectedCategoryId ? (int) $selectedCategoryId : '' ?>">
                <button type="submit"><i class='bx bx-search'></i><span class="d-none d-sm-inline">Rechercher</span></button>
            </form>
        </div>
    </section>

    <!-- ===== CATALOGUE (async) ===== -->
    <section class="pl-section">
        <div class="pl-wrap">
            <div class="pl-toolbar">
                <div class="pl-chips">
                    <button type="button" class="pl-chip <?= empty($selectedCategoryId) ? 'is-active' : '' ?>" data-cat="">
                        <i class='bx bx-grid-alt'></i> Tous
                    </button>
                    <?php foreach ($projectCategories as $category): ?>
                        <button type="button" class="pl-chip <?= ((int) $selectedCategoryId === (int) ($category->id ?? 0)) ? 'is-active' : '' ?>" data-cat="<?= (int) ($category->id ?? 0) ?>">
                            <?= htmlspecialchars((string) ($category->nom ?? 'Sans nom')) ?>
                            <?php if (!empty($category->total_projects)): ?><span><?= (int) $category->total_projects ?></span><?php endif; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <div class="pl-toolbar__row">
                    <div class="pl-count" id="plCount">
                        <strong><?= (int) $projectCount ?></strong> projet<?= $projectCount > 1 ? 's' : '' ?>
                        <?= $projectSearch !== '' ? 'pour « ' . htmlspecialchars($projectSearch) . ' »' : 'disponible' . ($projectCount > 1 ? 's' : '') ?>
                    </div>
                    <div class="pl-toolbar__right">
                        <button type="button" class="pl-reset" id="plReset" style="<?= $hasFilters ? '' : 'display:none' ?>"><i class='bx bx-x'></i> Réinitialiser</button>
                        <button type="button" class="pl-sheet-trigger" id="plSheetTrigger"><i class='bx bx-slider-alt'></i> Trier &amp; afficher</button>
                        <div class="pl-field">
                            <label for="plSort"><i class='bx bx-sort-alt-2'></i> Trier</label>
                            <select id="plSort">
                                <?php foreach ($sortLabels as $key => $label): ?>
                                    <option value="<?= $key ?>" <?= $projectSort === $key ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="pl-field">
                            <label for="plPerPage">Par page</label>
                            <select id="plPerPage">
                                <?php foreach ([5, 10, 15, 20] as $pp): ?>
                                    <option value="<?= $pp ?>" <?= $perPage === $pp ? 'selected' : '' ?>><?= $pp ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="pl-view" role="group" aria-label="Mode d'affichage">
                            <button type="button" class="pl-view__btn" data-view="grid" aria-label="Vue grille" title="Grille"><i class='bx bx-grid-alt'></i></button>
                            <button type="button" class="pl-view__btn is-active" data-view="list" aria-label="Vue liste" title="Liste"><i class='bx bx-list-ul'></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pl-sheet" id="plSheet" aria-hidden="true">
                <div class="pl-sheet__backdrop" data-sheet-close></div>
                <div class="pl-sheet__panel" role="dialog" aria-label="Trier et afficher">
                    <div class="pl-sheet__handle"></div>
                    <div class="pl-sheet__head">
                        <h3>Trier &amp; afficher</h3>
                        <button type="button" class="pl-sheet__x" data-sheet-close aria-label="Fermer"><i class='bx bx-x'></i></button>
                    </div>
                    <div class="pl-sheet__group">
                        <span class="pl-sheet__label">Trier par</span>
                        <div class="pl-sheet__options" id="plSheetSort">
                            <?php foreach ($sortLabels as $key => $label): ?>
                                <button type="button" class="pl-sheet__opt <?= $projectSort === $key ? 'is-active' : '' ?>" data-sort="<?= htmlspecialchars((string) $key) ?>"><span><?= htmlspecialchars((string) $label) ?></span><i class='bx bx-check'></i></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="pl-sheet__group">
                        <span class="pl-sheet__label">Projets par page</span>
                        <div class="pl-sheet__chips" id="plSheetPerPage">
                            <?php foreach ([5, 10, 15, 20] as $pp): ?>
                                <button type="button" class="pl-sheet__chip <?= $perPage === $pp ? 'is-active' : '' ?>" data-pp="<?= $pp ?>"><?= $pp ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button type="button" class="pl-sheet__apply" data-sheet-close><i class='bx bx-check'></i> Voir les résultats</button>
                </div>
            </div>

            <div id="projectResults" class="project-results-shell is-list">
                <?php $this->view('Partials/home-project-results', compact('projects', 'projectSearch', 'projectCount', 'currentPage', 'perPage', 'totalPages')); ?>
            </div>

            <div class="pl-loadmore-wrap" id="plLoadMoreWrap">
                <button type="button" class="pl-loadmore" id="plLoadMore"><i class='bx bx-chevron-down'></i> Charger plus de projets</button>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        if (!window.jQuery) { return; }
        (function ($) {
            var $results = $('#projectResults');
            var $search = $('#plSearch');
            var $cat = $('#plCategory');
            var $sort = $('#plSort');
            var $perPage = $('#plPerPage');
            var endpoint = '<?= ROOT ?>/Homes/index';
            var pretty = '<?= ROOT ?>/Homes/projects';
            var debounceTimer = null;
            var state = { page: <?= (int) ($currentPage ?? 1) ?>, totalPages: <?= (int) ($totalPages ?? 1) ?> };

            function buildParams(page) {
                var p = {};
                var s = $.trim($search.val());
                if (s) { p.search = s; }
                var c = $cat.val();
                if (c) { p.category = c; }
                var sortVal = $sort.val();
                if (sortVal && sortVal !== 'recent') { p.sort = sortVal; }
                var pp = $perPage.val();
                if (pp && pp !== '5') { p.per_page = pp; }
                if (page && page > 1) { p.page = page; }
                return p;
            }

            function initCarousels(scope) {
                if (typeof $.fn.slick !== 'function') { return; }
                var $scope = scope ? $(scope) : $results;
                $scope.find('.js-project-carousel').each(function () {
                    var $c = $(this);
                    if ($c.hasClass('slick-initialized')) { $c.slick('unslick'); }
                    if ($c.children().length <= 1) { return; }
                    $c.slick({
                        slidesToShow: 1, slidesToScroll: 1, arrows: true, dots: true, infinite: true,
                        autoplay: true, autoplaySpeed: 3600, pauseOnHover: true, speed: 700,
                        cssEase: 'cubic-bezier(.2,.8,.2,1)',
                        prevArrow: '<button type="button" class="slick-prev" aria-label="Image precedente"><i class="bx bx-chevron-left"></i></button>',
                        nextArrow: '<button type="button" class="slick-next" aria-label="Image suivante"><i class="bx bx-chevron-right"></i></button>'
                    });
                });
            }

            function updateCount(n) {
                var s = $.trim($search.val());
                var safe = $('<div>').text(s).html();
                $('#plCount').html('<strong>' + n + '</strong> projet' + (n > 1 ? 's' : '') + (s ? ' pour « ' + safe + ' »' : ' disponible' + (n > 1 ? 's' : '')));
            }

            function syncReset() {
                var active = $.trim($search.val()) !== '' || !!$cat.val();
                $('#plReset').toggle(active);
            }

            function load(page) {
                var params = buildParams(page);
                $results.addClass('is-loading');
                $.ajax({ url: endpoint, method: 'GET', data: params, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .done(function (res) {
                        if (res && typeof res.html !== 'undefined') {
                            $results.html(res.html);
                            if (typeof res.count !== 'undefined') { updateCount(res.count); }
                            state.page = parseInt(res.currentPage, 10) || 1;
                            state.totalPages = parseInt(res.totalPages, 10) || 1;
                            var qs = $.param(params);
                            window.history.replaceState({}, '', pretty + (qs ? '?' + qs : ''));
                            initCarousels();
                            updateLoadMore();
                        }
                    })
                    .always(function () { $results.removeClass('is-loading'); syncReset(); });
            }

            // « Charger plus » : recupere la page suivante et AJOUTE les cartes
            // (au lieu de remplacer) — parcours continu, plus naturel au doigt.
            function loadMore() {
                if (state.page >= state.totalPages) { return; }
                var $btn = $('#plLoadMore').addClass('is-loading');
                $.ajax({ url: endpoint, method: 'GET', data: buildParams(state.page + 1), headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .done(function (res) {
                        if (res && typeof res.html !== 'undefined') {
                            var $newCards = $('<div>').html(res.html).find('.row').first().children();
                            $results.find('.row').first().append($newCards);
                            state.page = parseInt(res.currentPage, 10) || (state.page + 1);
                            state.totalPages = parseInt(res.totalPages, 10) || state.totalPages;
                            initCarousels($newCards);
                            updateLoadMore();
                        }
                    })
                    .always(function () { $btn.removeClass('is-loading'); });
            }

            function updateLoadMore() {
                $('#plLoadMoreWrap').toggleClass('has-more', state.page < state.totalPages);
            }

            $('#plForm').on('submit', function (e) { e.preventDefault(); load(1); });
            $search.on('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function () { load(1); }, 350);
            });
            $sort.add($perPage).on('change', function () { load(1); });
            $('.pl-chip[data-cat]').on('click', function () {
                $cat.val(String($(this).data('cat') || ''));
                $('.pl-chip').removeClass('is-active');
                $(this).addClass('is-active');
                load(1);
            });
            $('#plReset').on('click', function () {
                $search.val(''); $cat.val(''); $sort.val('recent'); $perPage.val('5');
                $('.pl-chip').removeClass('is-active');
                $('.pl-chip[data-cat=""]').addClass('is-active');
                load(1);
            });
            $results.on('click', '.page-nav[data-page]', function () {
                var page = parseInt($(this).attr('data-page'), 10);
                if (!page || $(this).is(':disabled')) { return; }
                load(page);
                var top = $('.pl-section').offset().top - 70;
                $('html, body').animate({ scrollTop: top }, 300);
            });
            $('#plLoadMore').on('click', loadMore);

            // Feuille « Trier & afficher » (mobile) — synchronise les selects + recharge.
            // Sortie du flux transforme (section .public-reveal) sinon position:fixed casse.
            var $sheet = $('#plSheet').appendTo('body');
            function closeSheet() { $sheet.removeClass('is-open').attr('aria-hidden', 'true'); $('body').removeClass('overflow-hidden'); }
            $('#plSheetTrigger').on('click', function () { $sheet.addClass('is-open').attr('aria-hidden', 'false'); $('body').addClass('overflow-hidden'); });
            $sheet.on('click', '[data-sheet-close]', closeSheet);
            $('#plSheetSort').on('click', '.pl-sheet__opt', function () {
                $('#plSheetSort .pl-sheet__opt').removeClass('is-active');
                $(this).addClass('is-active');
                $sort.val(String($(this).data('sort')));
                load(1);
            });
            $('#plSheetPerPage').on('click', '.pl-sheet__chip', function () {
                $('#plSheetPerPage .pl-sheet__chip').removeClass('is-active');
                $(this).addClass('is-active');
                $perPage.val(String($(this).data('pp')));
                load(1);
            });

            // Bascule grille / liste (memorisee, defaut liste) — meme cle que l'accueil.
            var KEY = 'ngakodon-projects-view';
            var btns = document.querySelectorAll('.pl-view__btn');
            function applyView(view, reinit) {
                $results.toggleClass('is-list', view === 'list');
                btns.forEach(function (b) { b.classList.toggle('is-active', b.getAttribute('data-view') === view); });
                if (reinit) { initCarousels(); }
            }
            var savedView = 'list';
            try { savedView = localStorage.getItem(KEY) || 'list'; } catch (e) {}
            applyView(savedView, false);
            btns.forEach(function (b) {
                b.addEventListener('click', function () {
                    var v = b.getAttribute('data-view');
                    try { localStorage.setItem(KEY, v); } catch (e) {}
                    applyView(v, true);
                });
            });

            initCarousels();
            updateLoadMore();
        })(window.jQuery);
        });
    </script>

    <?php $this->view('Partials/footer'); ?>
</main>

<?php $this->view('Partials/scripts'); ?>
</body>
</html>
