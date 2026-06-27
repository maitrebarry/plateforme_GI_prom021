<?php
$this->view('Partials/head', [
    'pageTitle' => $pageTitle ?? 'Accueil',
]);
$projects = $projects ?? [];
$projectCategories = $projectCategories ?? [];
$projectSearch = $projectSearch ?? '';
$selectedCategoryId = $selectedCategoryId ?? null;
$projectCount = $projectCount ?? count($projects);
$featuredProject = $featuredProject ?? ($projects[0] ?? null);
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 5);
$totalPages = max(1, (int) ($totalPages ?? 1));
$topLikedProjects = $topLikedProjects ?? [];
$presentationStats = $presentationStats ?? [];
$departmentAnnouncements = $departmentAnnouncements ?? [];
$departmentInformations = $departmentInformations ?? [];
$departmentResults = $departmentResults ?? [];
$departmentOpportunities = $departmentOpportunities ?? [];

if (!function_exists('home_department_file_is_image')) {
    function home_department_file_is_image(array $file): bool
    {
        $type = strtolower((string) ($file['type'] ?? ''));
        $url = strtolower((string) ($file['url'] ?? ''));
        $name = strtolower((string) ($file['name'] ?? ''));
        $extension = pathinfo($url !== '' ? $url : $name, PATHINFO_EXTENSION);

        return str_starts_with($type, 'image/')
            || in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)
            || in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
    }
}

$homeDepartmentSections = [
    ['label' => 'Annonce', 'class' => 'badge-ann', 'icon' => 'bx bx-megaphone', 'items' => $departmentAnnouncements],
    ['label' => 'Information', 'class' => 'badge-info', 'icon' => 'bx bx-info-circle', 'items' => $departmentInformations],
    ['label' => 'Résultat', 'class' => 'badge-res', 'icon' => 'bx bx-award', 'items' => $departmentResults],
    ['label' => 'Opportunité', 'class' => 'badge-op', 'icon' => 'bx bx-briefcase-alt-2', 'items' => $departmentOpportunities],
];

$statProjects = (int) ($presentationStats['projects'] ?? $projectCount);
$statOwners = (int) ($presentationStats['owners'] ?? 0);
$statCategories = (int) ($presentationStats['categories'] ?? count($projectCategories));
$statRating = (float) ($presentationStats['average_rating'] ?? 0);
?>

<body class="public-site um6p-site">
    <?php $this->view('Partials/global-shell'); ?>
    <?php $this->view('Partials/mobile-menu'); ?>
    <?php $this->view('Partials/header'); ?>
    <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

    <main class="nk-home">
        <style>
        /* ===== Accueil NGAKODON — mobile-first, base tokens (suit couleur + theme) ===== */
        html, body.um6p-site { overflow-x: hidden; max-width: 100%; }
        body.um6p-site, .nk-home { background: var(--ds-bg); color: var(--ds-ink); }
        .nk-home { overflow-x: hidden; }
        *, *::before, *::after { box-sizing: border-box; }

        /* Header public (sticky, clair, accent de marque) */
        .um6p-site .header { position: sticky; top: 0; z-index: 1020; background: var(--ds-surface); border-bottom: 1px solid var(--ds-border); }
        .um6p-site .header-inner { min-height: 70px; }
        .um6p-site .logo img { max-height: 44px; width: auto; }
        .um6p-site .nav-menu__link { color: var(--ds-ink); font-weight: 600; }
        .um6p-site .nav-menu__link:hover { color: var(--ds-brand-600); }

        .nk-wrap { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 16px; }

        /* ===== HERO ===== */
        .nk-hero { position: relative; overflow: hidden; background: linear-gradient(150deg, var(--ds-brand-600) 0%, var(--ds-brand-800) 100%); color: #fff; padding: 40px 0 46px; }
        .nk-hero::after { content: ''; position: absolute; inset: 0; pointer-events: none;
            background: radial-gradient(circle at 85% 15%, rgba(255,255,255,.10) 0, transparent 30%), radial-gradient(circle at 10% 90%, rgba(224,168,46,.16) 0, transparent 32%); }
        .nk-hero__inner { position: relative; z-index: 1; display: grid; grid-template-columns: minmax(0, 1fr); gap: 28px; }
        .nk-hero__text { min-width: 0; }
        .nk-badge { display: inline-flex; align-items: center; gap: 7px; background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.22); color: #fff; font-size: .78rem; font-weight: 600; padding: 6px 14px; border-radius: var(--ds-radius-pill); }
        .nk-hero h1 { font-family: var(--ds-font-heading); color: #fff; font-size: 1.68rem; line-height: 1.18; margin: 16px 0 10px; font-weight: 800; overflow-wrap: break-word; }
        .nk-hero h1 .accent { color: var(--ds-brand-200); }
        .nk-hero__lead { color: rgba(255,255,255,.85); font-size: 1rem; line-height: 1.6; margin: 0 0 20px; max-width: 560px; }

        /* Carte de recherche blanche posee dans le hero */
        .nk-search { background: var(--ds-surface); border-radius: var(--ds-radius-lg); box-shadow: var(--ds-shadow-lg); padding: 14px; }
        .nk-search__main { display: flex; align-items: center; gap: 8px; background: var(--ds-surface-2); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-pill); padding: 5px 5px 5px 16px; }
        .nk-search__main > .bx { font-size: 1.25rem; color: var(--ds-muted); flex-shrink: 0; }
        .nk-search__main input { flex: 1; min-width: 0; border: 0; background: transparent; outline: none; font-size: .98rem; color: var(--ds-ink); padding: 10px 0; }
        .nk-search__btn { flex-shrink: 0; display: inline-flex; align-items: center; gap: 7px; border: 0; background: var(--ds-brand-600); color: #fff; font-weight: 700; font-size: .92rem; padding: 11px 18px; border-radius: var(--ds-radius-pill); cursor: pointer; transition: background var(--ds-transition); }
        .nk-search__btn:hover { background: var(--ds-brand-700); }
        .nk-search__row { display: flex; gap: 10px; margin-top: 12px; flex-wrap: wrap; }
        .nk-select { flex: 1; min-width: 140px; border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius); padding: 10px 12px; font-size: .9rem; color: var(--ds-ink); background: var(--ds-surface); cursor: pointer; }
        .nk-select:focus { outline: none; border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); }
        .nk-reset { display: inline-flex; align-items: center; gap: 6px; color: var(--ds-muted); font-weight: 600; font-size: .88rem; padding: 10px 14px; border-radius: var(--ds-radius); border: 1px solid var(--ds-border); }
        .nk-reset:hover { color: var(--ds-brand-700); border-color: var(--ds-brand-300); }

        .nk-cta { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 18px; }
        .nk-cta a, .nk-cta button { display: inline-flex; align-items: center; gap: 8px; font-weight: 700; font-size: .95rem; padding: 12px 20px; border-radius: var(--ds-radius-pill); cursor: pointer; border: 1px solid transparent; transition: all var(--ds-transition); text-decoration: none; }
        .nk-cta .is-solid { background: #fff; color: var(--ds-brand-700); }
        .nk-cta .is-solid:hover { transform: translateY(-1px); }
        .nk-cta .is-ghost { background: rgba(255,255,255,.12); color: #fff; border-color: rgba(255,255,255,.28); }
        .nk-cta .is-ghost:hover { background: rgba(255,255,255,.2); }

        .nk-stats { display: flex; gap: 10px; margin-top: 24px; flex-wrap: wrap; }
        .nk-stat { flex: 1; min-width: 72px; background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.16); border-radius: var(--ds-radius); padding: 12px; text-align: center; }
        .nk-stat b { display: block; font-family: var(--ds-font-heading); font-size: 1.5rem; line-height: 1; color: #fff; }
        .nk-stat span { font-size: .72rem; color: rgba(255,255,255,.78); }
        .nk-stat .bx { color: var(--ds-accent); font-size: .9rem; vertical-align: 1px; }

        .nk-hero__visual { display: none; }

        /* ===== Sections génériques ===== */
        .nk-section { padding: 36px 0; }
        .section-head { margin-bottom: 18px; }
        .section-head small { display: inline-block; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; font-size: .72rem; color: var(--ds-brand-600); margin-bottom: 6px; }
        .section-head h2 { font-family: var(--ds-font-heading); font-size: 1.5rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0 0 6px; }
        .section-copy { color: var(--ds-muted); font-size: .95rem; line-height: 1.6; margin: 0; max-width: 640px; }
        .nk-headrow { display: flex; align-items: flex-end; justify-content: space-between; gap: 12px; }
        .nk-seeall { white-space: nowrap; color: var(--ds-brand-600); font-weight: 700; font-size: .9rem; display: inline-flex; align-items: center; gap: 5px; }

        /* ===== Puces catégories (scroll horizontal mobile) ===== */
        .nk-cats { display: flex; gap: 9px; overflow-x: auto; padding: 4px 0 8px; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
        .nk-cats::-webkit-scrollbar { display: none; }
        .nk-cat { flex-shrink: 0; display: inline-flex; align-items: center; gap: 6px; background: var(--ds-surface); border: 1px solid var(--ds-border); color: var(--ds-ink); font-weight: 600; font-size: .85rem; padding: 8px 15px; border-radius: var(--ds-radius-pill); cursor: pointer; transition: all var(--ds-transition); }
        .nk-cat:hover { border-color: var(--ds-brand-300); color: var(--ds-brand-700); }
        .nk-cat.is-active { background: var(--ds-brand-600); border-color: var(--ds-brand-600); color: #fff; }
        .nk-cat small { opacity: .7; font-weight: 600; }

        /* ===== Cartes projet (rendues par le partial home-project-results) ===== */
        .project-results-shell.is-loading { opacity: .55; pointer-events: none; transition: opacity .2s; }
        .nk-headrow { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; text-align: left; }
        .results-chip { display: inline-flex; align-items: center; gap: 7px; background: var(--ds-brand-50); color: var(--ds-brand-700); font-weight: 600; font-size: .85rem; padding: 7px 14px; border-radius: var(--ds-radius-pill); margin-bottom: 18px; }

        .project-card-modern { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); overflow: hidden; height: 100%; display: flex; flex-direction: column; transition: transform var(--ds-transition), box-shadow var(--ds-transition), border-color var(--ds-transition); }
        .project-card-modern:hover { transform: translateY(-4px); box-shadow: var(--ds-shadow-md); border-color: var(--ds-brand-200); }
        .project-visual { position: relative; overflow: hidden; }
        .project-carousel, .project-slide { line-height: 0; }
        .project-slide img { width: 100%; height: 210px; object-fit: cover; transition: transform .5s ease; }
        .project-card-modern:hover .project-slide img { transform: scale(1.06); }
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
        .project-title a { color: var(--ds-ink-strong); }
        .project-title a:hover { color: var(--ds-brand-600); }
        .project-text { color: var(--ds-muted); font-size: .9rem; line-height: 1.55; margin: 0 0 12px; }
        .tech-list { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 14px; }
        .tech-pill { font-size: .74rem; font-weight: 600; background: var(--ds-surface-2); color: var(--ds-ink); border: 1px solid var(--ds-border); padding: 4px 10px; border-radius: var(--ds-radius-pill); }
        .project-actions { margin-top: auto; display: flex; align-items: center; justify-content: space-between; gap: 10px; }
        .project-link { display: inline-flex; align-items: center; gap: 6px; font-weight: 700; font-size: .9rem; color: var(--ds-brand-600); }
        .project-link:hover { color: var(--ds-brand-700); }
        .project-flag { font-size: .76rem; font-weight: 600; color: var(--ds-danger); display: inline-flex; align-items: center; gap: 4px; }

        /* Slick (carrousel images) */
        .project-carousel .slick-prev, .project-carousel .slick-next { width: 34px; height: 34px; background: rgba(255,255,255,.9); border-radius: 50%; z-index: 3; display: flex !important; align-items: center; justify-content: center; border: 0; }
        .project-carousel .slick-prev { left: 10px; } .project-carousel .slick-next { right: 10px; }
        .project-carousel .slick-prev::before, .project-carousel .slick-next::before { content: ''; }
        .project-carousel .slick-prev .bx, .project-carousel .slick-next .bx { color: var(--ds-brand-700); font-size: 1.3rem; }
        .project-carousel .slick-dots { bottom: 10px; } .project-carousel .slick-dots li button::before { color: #fff; opacity: .8; }
        .project-carousel .slick-dots li.slick-active button::before { color: var(--ds-brand-300); opacity: 1; }

        /* État vide + pagination */
        .empty-projects { text-align: center; background: var(--ds-surface); border: 1px dashed var(--ds-border-strong); border-radius: var(--ds-radius-lg); padding: 40px 24px; }
        .empty-projects__icon, .empty-projects .bx { font-size: 2.4rem; color: var(--ds-brand-400); margin-bottom: 10px; }
        .empty-projects h3 { font-family: var(--ds-font-heading); color: var(--ds-ink-strong); }
        .empty-projects p { color: var(--ds-muted); }
        .project-pagination-wrap { margin-top: 26px; text-align: center; }
        .project-pagination-wrap > p, .project-pagination-summary { color: var(--ds-muted); font-size: .85rem; margin-bottom: 10px; }
        .project-pagination { display: flex; flex-wrap: wrap; gap: 6px; justify-content: center; }
        .page-nav { min-width: 38px; height: 38px; padding: 0 10px; border: 1px solid var(--ds-border); background: var(--ds-surface); color: var(--ds-ink); border-radius: var(--ds-radius); font-weight: 600; font-size: .88rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: all var(--ds-transition); }
        .page-nav:hover:not(:disabled) { border-color: var(--ds-brand-400); color: var(--ds-brand-700); }
        .page-nav.is-active { background: var(--ds-brand-600); border-color: var(--ds-brand-600); color: #fff; }
        .page-nav:disabled { opacity: .4; cursor: not-allowed; }

        /* ===== Top liked (mini-card) ===== */
        .top-liked-grid { display: grid; grid-template-columns: 1fr; gap: 16px; }
        .mini-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); overflow: hidden; transition: transform var(--ds-transition), box-shadow var(--ds-transition); }
        .mini-card:hover { transform: translateY(-4px); box-shadow: var(--ds-shadow-md); }
        .mini-card img { width: 100%; height: 168px; object-fit: cover; }
        .mini-card__body { padding: 14px 16px 16px; }

        /* ===== Podium "projets les plus appréciés" ===== */
        .nk-podium { display: grid; grid-template-columns: minmax(0, 1fr); gap: 16px; }
        .nk-podium__hero { position: relative; background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); overflow: hidden; box-shadow: var(--ds-shadow-sm); }
        .nk-podium__hero::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #f4c020, var(--ds-accent)); z-index: 3; }
        .nk-podium__media { display: block; position: relative; line-height: 0; }
        .nk-podium__media img { width: 100%; height: 210px; object-fit: cover; }
        .nk-medal { display: inline-flex; align-items: center; gap: 5px; font-weight: 800; }
        .nk-medal--1 { position: absolute; top: 12px; left: 12px; background: linear-gradient(135deg, #f6c945, #e0a82e); color: #5a3d00; font-size: .82rem; padding: 6px 13px; border-radius: var(--ds-radius-pill); box-shadow: 0 6px 16px rgba(224, 168, 46, .45); }
        .nk-podium__cat { position: absolute; bottom: 12px; left: 12px; display: inline-flex; align-items: center; gap: 5px; background: rgba(255, 255, 255, .92); color: var(--ds-brand-700); font-weight: 700; font-size: .74rem; padding: 5px 11px; border-radius: var(--ds-radius-pill); }
        .nk-podium__body { padding: 18px; }
        .nk-coupdecoeur { display: inline-flex; align-items: center; gap: 6px; color: var(--ds-accent-600); font-weight: 800; font-size: .72rem; text-transform: uppercase; letter-spacing: .08em; }
        .nk-podium__title { font-family: var(--ds-font-heading); font-size: 1.32rem; font-weight: 800; line-height: 1.25; margin: 8px 0 6px; }
        .nk-podium__title a { color: var(--ds-ink-strong); }
        .nk-podium__title a:hover { color: var(--ds-brand-600); }
        .nk-podium__author { display: flex; align-items: center; gap: 8px; color: var(--ds-muted); font-size: .9rem; font-weight: 600; margin-bottom: 12px; }
        .nk-avatar { width: 28px; height: 28px; border-radius: 50%; background: var(--ds-brand-100); color: var(--ds-brand-700); display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: .82rem; flex-shrink: 0; }
        .nk-stars { display: flex; align-items: center; gap: 2px; color: var(--ds-accent); font-size: 1.1rem; margin-bottom: 14px; }
        .nk-stars .bx-star { color: var(--ds-border-strong); }
        .nk-stars b { margin-left: 7px; color: var(--ds-ink); font-size: .9rem; }
        .nk-metrics { display: flex; flex-wrap: wrap; gap: 18px; margin-bottom: 14px; }
        .nk-metrics span { display: inline-flex; align-items: center; gap: 6px; color: var(--ds-ink-strong); font-weight: 800; font-size: 1.05rem; }
        .nk-metrics .bx { color: var(--ds-brand-600); font-size: 1.15rem; }
        .nk-metrics small { color: var(--ds-muted); font-weight: 600; font-size: .72rem; }
        .nk-engbar { height: 8px; border-radius: var(--ds-radius-pill); background: var(--ds-surface-2); overflow: hidden; margin-bottom: 16px; }
        .nk-engbar > span { display: block; height: 100%; border-radius: var(--ds-radius-pill); background: linear-gradient(90deg, var(--ds-brand-400), var(--ds-brand-700)); }
        .nk-podium__rest { display: flex; flex-direction: column; gap: 12px; }
        .nk-rankrow { display: flex; align-items: center; gap: 12px; background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius); padding: 12px; text-decoration: none; transition: all var(--ds-transition); }
        .nk-rankrow:hover { transform: translateX(3px); box-shadow: var(--ds-shadow); border-color: var(--ds-brand-200); }
        .nk-medal--2, .nk-medal--3 { width: 30px; height: 30px; border-radius: 50%; align-items: center; justify-content: center; flex-shrink: 0; font-size: .95rem; }
        .nk-medal--2 { background: #e7edf2; color: #5b6b80; }
        .nk-medal--3 { background: #f6e7d8; color: #9a5a2b; }
        .nk-rankrow__thumb { width: 56px; height: 56px; border-radius: 12px; overflow: hidden; flex-shrink: 0; }
        .nk-rankrow__thumb img { width: 100%; height: 100%; object-fit: cover; }
        .nk-rankrow__body { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 3px; }
        .nk-rankrow__title { font-family: var(--ds-font-heading); font-weight: 800; color: var(--ds-ink-strong); font-size: .98rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .nk-rankrow__meta { color: var(--ds-muted); font-size: .78rem; display: inline-flex; align-items: center; gap: 4px; }
        .nk-rankrow__stats { color: var(--ds-muted); font-size: .78rem; }
        .nk-rankrow__stats .bx { color: var(--ds-brand-600); vertical-align: -1px; }
        .nk-rankrow__stats .bxs-heart { color: var(--ds-danger); }
        .nk-rankrow__stats .bxs-star { color: var(--ds-accent); }
        .nk-engbar--sm { height: 5px; margin: 5px 0 0; max-width: 170px; }
        .nk-rankrow__chev { color: var(--ds-muted-soft); font-size: 1.35rem; flex-shrink: 0; }

        @media (min-width: 1100px) {
            .nk-podium { grid-template-columns: minmax(0, 1.32fr) minmax(0, 1fr); align-items: start; }
            .nk-podium__media img { height: 280px; }
            .nk-podium__title { font-size: 1.5rem; }
        }

        /* ===== Cartes boutons hero/ai (réutilisées par partial + launcher) ===== */
        .hero-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: var(--ds-brand-600); color: #fff; font-weight: 700; font-size: .92rem; padding: 11px 20px; border-radius: var(--ds-radius-pill); border: 0; cursor: pointer; text-decoration: none; transition: all var(--ds-transition); }
        .hero-btn:hover { background: var(--ds-brand-700); color: #fff; transform: translateY(-1px); }
        .hero-btn-outline { display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: transparent; color: var(--ds-brand-700); font-weight: 700; font-size: .92rem; padding: 11px 20px; border-radius: var(--ds-radius-pill); border: 1px solid var(--ds-brand-300); cursor: pointer; text-decoration: none; transition: all var(--ds-transition); }
        .hero-btn-outline:hover { background: var(--ds-brand-50); color: var(--ds-brand-800); }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 10px; }

        .ai-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); padding: 22px; }
        /* Bannière Assistant IA */
        .nk-ai { position: relative; overflow: hidden; display: flex; gap: 22px; align-items: center; background: linear-gradient(135deg, var(--ds-brand-800), var(--ds-footer)); border-radius: var(--ds-radius-xl); padding: 26px; color: #fff; }
        .nk-ai__glow { position: absolute; top: -70px; right: -50px; width: 260px; height: 260px; border-radius: 50%; background: radial-gradient(circle, rgba(224,168,46,.28), transparent 70%); pointer-events: none; }
        .nk-ai__icon { flex-shrink: 0; width: 74px; height: 74px; border-radius: 22px; background: rgba(224,168,46,.15); border: 1px solid rgba(224,168,46,.32); display: flex; align-items: center; justify-content: center; }
        .nk-ai__icon .bx { font-size: 2.5rem; color: var(--ds-accent); }
        .nk-ai__content { position: relative; z-index: 1; flex: 1; min-width: 0; }
        .nk-ai__tag { display: inline-flex; align-items: center; gap: 6px; color: var(--ds-accent); font-weight: 800; font-size: .72rem; text-transform: uppercase; letter-spacing: .08em; }
        .nk-ai__title { font-family: var(--ds-font-heading); color: #fff; font-size: 1.45rem; font-weight: 800; line-height: 1.2; margin: 8px 0 6px; }
        .nk-ai__copy { color: rgba(231,240,235,.78); font-size: .95rem; line-height: 1.6; margin: 0 0 16px; max-width: 620px; }
        .nk-ai__actions { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; }
        .nk-ai__btn { display: inline-flex; align-items: center; gap: 8px; background: var(--ds-accent); color: #3d2900; font-weight: 800; font-size: .95rem; padding: 12px 22px; border: 0; border-radius: var(--ds-radius-pill); cursor: pointer; transition: transform var(--ds-transition), background var(--ds-transition); }
        .nk-ai__btn:hover { background: #f0b53e; transform: translateY(-2px); }
        .nk-ai__chips { display: flex; flex-wrap: wrap; gap: 8px; }
        .nk-ai__chip { display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.2); color: #fff; font-size: .82rem; font-weight: 600; padding: 8px 14px; border-radius: var(--ds-radius-pill); cursor: pointer; transition: background var(--ds-transition); }
        .nk-ai__chip:hover { background: rgba(255,255,255,.2); }

        /* ===== Département ===== */
        .news-grid { display: grid; grid-template-columns: 1fr; gap: 16px; }
        .news-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); overflow: hidden; transition: transform var(--ds-transition), box-shadow var(--ds-transition); }
        .news-card:hover { transform: translateY(-3px); box-shadow: var(--ds-shadow-md); }
        .home-department-card__media { display: block; position: relative; line-height: 0; }
        .home-department-card__media img { width: 100%; height: 150px; object-fit: cover; }
        .home-department-card__media span { position: absolute; bottom: 8px; right: 8px; background: rgba(15,23,42,.6); color: #fff; font-size: .74rem; padding: 3px 9px; border-radius: var(--ds-radius-pill); }
        .home-department-card__body { padding: 16px; }
        .home-department-card { position: relative; }
        .home-department-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; z-index: 3; background: var(--ds-border-strong); }
        .home-department-card[data-dept="badge-ann"]::before { background: var(--ds-danger); }
        .home-department-card[data-dept="badge-info"]::before { background: var(--ds-brand-500); }
        .home-department-card[data-dept="badge-res"]::before { background: var(--ds-success, #1f8a64); }
        .home-department-card[data-dept="badge-op"]::before { background: var(--ds-accent); }
        @media (max-width: 700px) {
            .nk-ai { flex-direction: column; align-items: flex-start; gap: 16px; padding: 22px; }
            .nk-ai__icon { width: 56px; height: 56px; border-radius: 16px; }
            .nk-ai__icon .bx { font-size: 1.9rem; }
            .nk-ai__title { font-size: 1.22rem; }
        }
        .badge-pill { display: inline-flex; align-items: center; gap: 5px; font-size: .74rem; font-weight: 700; padding: 4px 11px; border-radius: var(--ds-radius-pill); margin-bottom: 8px; }
        .badge-ann { background: var(--ds-danger-soft); color: #a3322e; }
        .badge-info { background: var(--ds-brand-50); color: var(--ds-brand-700); }
        .badge-res { background: var(--ds-success-soft); color: #11703a; }
        .badge-op { background: var(--ds-accent-soft); color: #8a6310; }
        .publication-title { font-family: var(--ds-font-heading); font-size: 1.05rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0 0 6px; }
        .news-meta { color: var(--ds-muted); font-size: .82rem; }
        .news-meta span { display: inline-flex; align-items: center; gap: 5px; }
        .publication-text { color: var(--ds-muted); font-size: .9rem; line-height: 1.55; margin: 8px 0 12px; }

        /* ===== Modal IA ===== */
        .ai-modal { position: fixed; inset: 0; z-index: 1200; background: rgba(10,23,18,.55); display: none; align-items: flex-end; justify-content: center; padding: 0; }
        .ai-modal.is-open { display: flex; }
        .ai-modal__dialog { background: var(--ds-surface); width: 100%; max-width: 640px; max-height: 92vh; display: flex; flex-direction: column; border-radius: var(--ds-radius-xl) var(--ds-radius-xl) 0 0; overflow: hidden; }
        .ai-modal__head { padding: 18px 20px; border-bottom: 1px solid var(--ds-border); }
        .ai-modal__head h3 { font-family: var(--ds-font-heading); font-size: 1.15rem; color: var(--ds-ink-strong); }
        .ai-modal__close { width: 38px; height: 38px; flex-shrink: 0; border: 1px solid var(--ds-border); background: var(--ds-surface-2); border-radius: 50%; color: var(--ds-ink); cursor: pointer; font-size: 1.2rem; }
        .ai-modal__body { padding: 16px 20px; overflow-y: auto; flex: 1; }
        .ai-modal__foot { padding: 14px 20px; border-top: 1px solid var(--ds-border); }
        .hero-chip { display: inline-flex; align-items: center; gap: 6px; background: var(--ds-brand-50); color: var(--ds-brand-700); font-weight: 700; font-size: .76rem; padding: 5px 12px; border-radius: var(--ds-radius-pill); }
        .ai-chip-row { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }
        .ai-chip { background: var(--ds-surface-2); border: 1px solid var(--ds-border); color: var(--ds-ink); font-size: .82rem; font-weight: 600; padding: 7px 13px; border-radius: var(--ds-radius-pill); cursor: pointer; transition: all var(--ds-transition); }
        .ai-chip:hover { border-color: var(--ds-brand-300); color: var(--ds-brand-700); }
        .ai-chat-window { background: var(--ds-surface-2); border: 1px solid var(--ds-border); border-radius: var(--ds-radius); padding: 14px; min-height: 180px; max-height: 320px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; }
        .ai-bubble { max-width: 88%; padding: 10px 14px; border-radius: 14px; font-size: .9rem; line-height: 1.5; }
        .ai-bubble.assistant { align-self: flex-start; background: var(--ds-surface); border: 1px solid var(--ds-border); color: var(--ds-ink); }
        .ai-bubble.user { align-self: flex-end; background: var(--ds-brand-600); color: #fff; }
        .ai-compose { display: flex; gap: 10px; align-items: flex-end; }
        .ai-input { flex: 1; border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius); padding: 10px 12px; font-size: .92rem; resize: vertical; min-height: 46px; max-height: 120px; color: var(--ds-ink); background: var(--ds-surface); font-family: var(--ds-font-sans); }
        .ai-input:focus { outline: none; border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); }
        .ai-send { flex-shrink: 0; display: inline-flex; align-items: center; gap: 7px; background: var(--ds-brand-600); color: #fff; border: 0; font-weight: 700; padding: 12px 18px; border-radius: var(--ds-radius-pill); cursor: pointer; }
        .ai-send:hover { background: var(--ds-brand-700); }

        /* ===== Reveal ===== */
        .reveal-item { opacity: 0; transform: translateY(16px); transition: opacity .5s ease, transform .5s ease; }
        .reveal-item.is-visible { opacity: 1; transform: none; }

        /* Très petits écrans : compacter le hero */
        @media (max-width: 460px) {
            .nk-search__btn span { display: none; }
            .nk-search__btn { padding: 12px 14px; }
            .nk-stat { min-width: 64px; padding: 10px 6px; }
            .nk-stat b { font-size: 1.3rem; }
        }

        /* ===== Tablette / Desktop ===== */
        @media (min-width: 768px) {
            .nk-hero { padding: 58px 0 64px; }
            .nk-hero h1 { font-size: 2.6rem; }
            .top-liked-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .news-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .ai-modal { align-items: center; padding: 24px; }
            .ai-modal__dialog { border-radius: var(--ds-radius-xl); }
        }
        @media (min-width: 1100px) {
            .nk-hero__inner { grid-template-columns: minmax(0, 1.15fr) minmax(0, .85fr); align-items: center; gap: 40px; }
            .nk-hero h1 { font-size: 3rem; }
            .nk-hero__visual { display: block; }
            .nk-hero__visual .vcard { background: var(--ds-surface); border-radius: var(--ds-radius-lg); overflow: hidden; box-shadow: var(--ds-shadow-lg); transform: rotate(1.5deg); }
            .nk-hero__visual .vcard img { width: 100%; height: 280px; object-fit: cover; }
            .nk-hero__visual .vcard__body { padding: 16px; }
            .top-liked-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .news-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }
        </style>

        <!-- ===== HERO ===== -->
        <section class="nk-hero">
            <div class="nk-wrap nk-hero__inner">
                <div class="nk-hero__text reveal-item is-visible">
                    <span class="nk-badge"><i class='bx bx-been-here'></i> Salon numérique — Génie Informatique</span>
                    <h1>Découvrez les <span class="accent">projets étudiants</span> et trouvez la solution qu'il vous faut</h1>
                    <p class="nk-hero__lead">Explorez, comparez et contactez directement les porteurs de projets. Une vitrine vivante des talents en informatique.</p>

                    <form method="get" action="<?= ROOT ?>/Homes/index" id="projectFilterForm" class="nk-search">
                        <input type="hidden" name="page" value="<?= $currentPage ?>" id="projectPageInput">
                        <div class="nk-search__main">
                            <i class='bx bx-search'></i>
                            <input type="text" name="search" id="projectSearchInput" value="<?= htmlspecialchars($projectSearch) ?>" placeholder="Rechercher un projet, une techno, un auteur…" aria-label="Rechercher un projet">
                            <button type="submit" class="nk-search__btn"><i class='bx bx-search'></i><span>Rechercher</span></button>
                        </div>
                        <div class="nk-search__row">
                            <select name="category" id="projectCategorySelect" class="nk-select" aria-label="Catégorie">
                                <option value="">Toutes les catégories</option>
                                <?php foreach ($projectCategories as $category): ?>
                                    <option value="<?= (int) ($category->id ?? 0) ?>" <?= ((int) ($selectedCategoryId ?? 0) === (int) ($category->id ?? 0)) ? 'selected' : '' ?>><?= htmlspecialchars((string) ($category->nom ?? 'Sans nom')) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="per_page" id="perPageSelect" class="nk-select" aria-label="Projets par page">
                                <?php foreach ([5, 10, 15, 20] as $option): ?><option value="<?= $option ?>" <?= $perPage === $option ? 'selected' : '' ?>><?= $option ?> / page</option><?php endforeach; ?>
                            </select>
                            <a href="<?= ROOT ?>/Homes/index" class="nk-reset" id="projectFilterReset"><i class='bx bx-reset'></i> Reset</a>
                        </div>
                    </form>

                    <div class="nk-cta">
                        <a href="#catalogue-projets" class="is-solid"><i class='bx bx-grid-alt'></i> Explorer les projets</a>
                        <button type="button" class="is-ghost js-open-home-ai-modal"><i class='bx bx-bot'></i> Assistant IA</button>
                    </div>

                    <div class="nk-stats">
                        <div class="nk-stat"><b><?= $statProjects ?></b><span>projets</span></div>
                        <div class="nk-stat"><b><?= $statOwners ?></b><span>porteurs</span></div>
                        <div class="nk-stat"><b><?= $statCategories ?></b><span>catégories</span></div>
                        <div class="nk-stat"><b><?= number_format($statRating, 1) ?><i class='bx bxs-star'></i></b><span>note moy.</span></div>
                    </div>
                </div>

                <?php if (!empty($featuredProject)): ?>
                    <div class="nk-hero__visual" aria-hidden="true">
                        <div class="vcard">
                            <img src="<?= htmlspecialchars((string) ($featuredProject['image'] ?? '')) ?>" alt="">
                            <div class="vcard__body">
                                <span class="project-category" style="position:static"><i class='bx bx-category'></i><?= htmlspecialchars((string) ($featuredProject['category'] ?? 'Projet')) ?></span>
                                <h3 class="project-title" style="margin:10px 0 4px"><?= htmlspecialchars((string) ($featuredProject['title'] ?? '')) ?></h3>
                                <div class="project-meta"><span><i class='bx bx-user-circle'></i><?= htmlspecialchars((string) ($featuredProject['author'] ?? 'Étudiant')) ?></span><span><i class='bx bxs-star' style="color:var(--ds-accent)"></i><?= number_format((float) ($featuredProject['average_rating'] ?? 0), 1) ?>/5</span></div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- ===== TOP / À LA UNE ===== -->
        <section class="nk-section">
            <div class="nk-wrap">
                <div class="section-head">
                    <small>À la une</small>
                    <h2>Les projets les plus appréciés</h2>
                    <p class="section-copy">Classement par <strong>indice d'appréciation</strong> — likes, avis et notes combinés.</p>
                </div>
                <?php $podiumHero = $topLikedProjects[0] ?? null; $podiumRest = array_slice($topLikedProjects, 1); ?>
                <?php if ($podiumHero): ?>
                    <div class="nk-podium">
                        <article class="nk-podium__hero">
                            <a class="nk-podium__media" href="<?= ROOT ?>/Projets/detail/<?= (int) $podiumHero['id'] ?>">
                                <img src="<?= htmlspecialchars($podiumHero['image']) ?>" alt="<?= htmlspecialchars($podiumHero['title']) ?>" loading="lazy">
                                <span class="nk-medal nk-medal--1"><i class='bx bxs-crown'></i> N°1</span>
                                <span class="nk-podium__cat"><i class='bx bx-category'></i><?= htmlspecialchars($podiumHero['category']) ?></span>
                            </a>
                            <div class="nk-podium__body">
                                <span class="nk-coupdecoeur"><i class='bx bxs-heart'></i> Coup de cœur de la communauté</span>
                                <h3 class="nk-podium__title"><a href="<?= ROOT ?>/Projets/detail/<?= (int) $podiumHero['id'] ?>"><?= htmlspecialchars($podiumHero['title']) ?></a></h3>
                                <div class="nk-podium__author"><span class="nk-avatar"><?= strtoupper(htmlspecialchars(mb_substr((string) $podiumHero['author'], 0, 1))) ?></span><?= htmlspecialchars($podiumHero['author']) ?></div>
                                <div class="nk-stars" aria-label="Note <?= number_format((float) $podiumHero['average_rating'], 1) ?> sur 5">
                                    <?php for ($s = 1; $s <= 5; $s++): ?>
                                        <i class='bx <?= $s <= ($podiumHero['stars_full'] ?? 0) ? 'bxs-star' : (($s === ($podiumHero['stars_full'] ?? 0) + 1 && ($podiumHero['stars_half'] ?? 0)) ? 'bxs-star-half' : 'bx-star') ?>'></i>
                                    <?php endfor; ?>
                                    <b><?= number_format((float) $podiumHero['average_rating'], 1) ?>/5</b>
                                </div>
                                <div class="nk-metrics">
                                    <span><i class='bx bxs-heart'></i> <?= (int) $podiumHero['likes_count'] ?> <small>likes</small></span>
                                    <span><i class='bx bxs-message-square-detail'></i> <?= (int) $podiumHero['reviews_count'] ?> <small>avis</small></span>
                                    <span><i class='bx bx-trending-up'></i> <?= (int) $podiumHero['engagement_score'] ?> <small>pts</small></span>
                                </div>
                                <div class="nk-engbar"><span style="width:<?= (int) $podiumHero['appreciation_pct'] ?>%"></span></div>
                                <a class="hero-btn" href="<?= ROOT ?>/Projets/detail/<?= (int) $podiumHero['id'] ?>"><i class='bx bx-show'></i> Voir le projet</a>
                            </div>
                        </article>
                        <?php if (!empty($podiumRest)): ?>
                            <div class="nk-podium__rest">
                                <?php foreach ($podiumRest as $item): ?>
                                    <a class="nk-rankrow" href="<?= ROOT ?>/Projets/detail/<?= (int) $item['id'] ?>">
                                        <span class="nk-medal nk-medal--<?= (int) $item['rank'] ?>"><?= (int) $item['rank'] ?></span>
                                        <span class="nk-rankrow__thumb"><img src="<?= htmlspecialchars($item['image']) ?>" alt="" loading="lazy"></span>
                                        <span class="nk-rankrow__body">
                                            <span class="nk-rankrow__title"><?= htmlspecialchars($item['title']) ?></span>
                                            <span class="nk-rankrow__meta"><i class='bx bx-user-circle'></i><?= htmlspecialchars($item['author']) ?></span>
                                            <span class="nk-rankrow__stats"><i class='bx bxs-star'></i><?= number_format((float) $item['average_rating'], 1) ?> &nbsp;·&nbsp; <i class='bx bxs-heart'></i><?= (int) $item['likes_count'] ?> &nbsp;·&nbsp; <i class='bx bxs-message-square-detail'></i><?= (int) $item['reviews_count'] ?></span>
                                            <span class="nk-engbar nk-engbar--sm"><span style="width:<?= (int) $item['appreciation_pct'] ?>%"></span></span>
                                        </span>
                                        <i class='bx bx-chevron-right nk-rankrow__chev'></i>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-projects"><i class='bx bx-heart'></i>
                        <h3>Bientôt vos coups de cœur</h3>
                        <p class="mb-0">Le classement apparaîtra dès que les visiteurs aimeront et noteront des projets.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- ===== CATALOGUE (chips + filtres + résultats) ===== -->
        <section class="nk-section" id="catalogue-projets" style="padding-top:8px">
            <div class="nk-wrap">
                <div class="nk-headrow section-head">
                    <div>
                        <small>Catalogue</small>
                        <h2>Explorez tous les projets</h2>
                    </div>
                    <div class="pl-view" role="group" aria-label="Mode d'affichage">
                        <button type="button" class="pl-view__btn" data-view="grid" aria-label="Vue grille" title="Grille"><i class='bx bx-grid-alt'></i></button>
                        <button type="button" class="pl-view__btn is-active" data-view="list" aria-label="Vue liste" title="Liste"><i class='bx bx-list-ul'></i></button>
                    </div>
                </div>
                <div class="nk-cats" role="tablist" aria-label="Filtrer par catégorie">
                    <button type="button" class="nk-cat is-active" data-cat=""><i class='bx bx-collection'></i> Tout</button>
                    <?php foreach ($projectCategories as $category): ?>
                        <button type="button" class="nk-cat" data-cat="<?= (int) ($category->id ?? 0) ?>"><?= htmlspecialchars((string) ($category->nom ?? 'Sans nom')) ?><?php if (!empty($category->total_projects)): ?> <small><?= (int) $category->total_projects ?></small><?php endif; ?></button>
                    <?php endforeach; ?>
                </div>

                <div id="projectResults" class="project-results-shell is-list" style="margin-top:18px">
                    <?php $this->view('Partials/home-project-results', compact('projects', 'projectSearch', 'projectCount', 'currentPage', 'perPage', 'totalPages')); ?>
                </div>
                <div class="pl-loadmore-wrap" id="homeLoadMoreWrap">
                    <button type="button" class="pl-loadmore" id="homeLoadMore"><i class='bx bx-chevron-down'></i> Charger plus de projets</button>
                </div>
            </div>
        </section>

        <!-- ===== ASSISTANT IA ===== -->
        <section class="nk-section" style="padding-top:0">
            <div class="nk-wrap">
                <div class="nk-ai">
                    <span class="nk-ai__glow" aria-hidden="true"></span>
                    <span class="nk-ai__icon" aria-hidden="true"><i class='bx bx-bot'></i></span>
                    <div class="nk-ai__content">
                        <span class="nk-ai__tag"><i class='bx bxs-magic-wand'></i> Assistant intelligent</span>
                        <h2 class="nk-ai__title">Décrivez votre besoin, l'IA trouve le projet</h2>
                        <p class="nk-ai__copy">Niveau, technologie, domaine… N'KadonBot vous oriente vers les réalisations les plus pertinentes en quelques secondes.</p>
                        <div class="nk-ai__actions">
                            <button type="button" class="nk-ai__btn js-open-home-ai-modal"><i class='bx bx-message-rounded-dots'></i> Ouvrir l'assistant</button>
                            <div class="nk-ai__chips">
                                <button type="button" class="nk-ai__chip" data-ai-home-prompt="Je cherche un projet web en PHP utile pour une universite."><i class='bx bx-globe'></i> Web utile</button>
                                <button type="button" class="nk-ai__chip" data-ai-home-prompt="Je veux un projet mobile avec une vraie valeur utilisateur."><i class='bx bx-mobile-alt'></i> Mobile</button>
                                <button type="button" class="nk-ai__chip" data-ai-home-prompt="Je cherche un projet data ou intelligence artificielle accessible."><i class='bx bx-data'></i> Data / IA</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== DÉPARTEMENT ===== -->
        <section class="nk-section" style="padding-bottom:64px">
            <div class="nk-wrap">
                <div class="section-head">
                    <small>Département</small>
                    <h2>Annonces, résultats et opportunités</h2>
                    <p class="section-copy">Les informations officielles du département, à portée de main.</p>
                </div>
                <div class="news-grid">
                    <?php foreach ($homeDepartmentSections as $section): ?>
                        <?php foreach ($section['items'] as $item): ?>
                            <?php
                            $files = $item['files'] ?? [];
                            $imageFiles = array_values(array_filter($files, static fn($file) => home_department_file_is_image($file)));
                            ?>
                            <article class="news-card home-department-card" data-dept="<?= htmlspecialchars($section['class']) ?>">
                                <?php if (!empty($imageFiles)): ?>
                                    <a class="home-department-card__media" href="<?= ROOT ?>/Homes/department_publication_detail/<?= (int) ($item['id'] ?? 0) ?>">
                                        <img src="<?= htmlspecialchars((string) ($imageFiles[0]['url'] ?? '')) ?>" alt="<?= htmlspecialchars((string) ($item['title'] ?? 'Publication')) ?>" loading="lazy">
                                        <?php if (count($imageFiles) > 1): ?><span>+<?= count($imageFiles) - 1 ?></span><?php endif; ?>
                                    </a>
                                <?php endif; ?>
                                <div class="home-department-card__body">
                                    <span class="badge-pill <?= htmlspecialchars($section['class']) ?>"><i class='<?= htmlspecialchars($section['icon']) ?>'></i><?= htmlspecialchars($section['label']) ?></span>
                                    <h3 class="publication-title"><?= htmlspecialchars((string) ($item['title'] ?? 'Sans titre')) ?></h3>
                                    <div class="news-meta"><span><i class='bx bx-calendar'></i><?= htmlspecialchars((string) ($item['date'] ?? '')) ?></span></div>
                                    <p class="publication-text"><?= htmlspecialchars(mb_strimwidth((string) ($item['content'] ?? ''), 0, 120, '...')) ?></p>
                                    <a class="project-link" href="<?= ROOT ?>/Homes/department_publication_detail/<?= (int) ($item['id'] ?? 0) ?>">Lire <i class='bx bx-right-arrow-alt'></i></a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- ===== MODAL IA ===== -->
        <div class="ai-modal" id="homeAiModal" aria-hidden="true">
            <div class="ai-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="homeAiModalTitle">
                <div class="ai-modal__head d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="hero-chip mb-2"><i class='bx bx-bot'></i> Assistant IA</div>
                        <h3 id="homeAiModalTitle" class="mb-1">Recommandation de projets</h3>
                        <p class="section-copy mb-0" style="font-size:.85rem">Décrivez votre besoin, l'assistant vous oriente vers les projets adaptés.</p>
                    </div>
                    <button type="button" class="ai-modal__close" id="closeHomeAiModal" aria-label="Fermer l'assistant"><i class='bx bx-x'></i></button>
                </div>
                <div class="ai-modal__body">
                    <div class="ai-chip-row">
                        <button type="button" class="ai-chip" data-ai-home-prompt="Je cherche un projet web en PHP utile pour une universite.">Projet web PHP</button>
                        <button type="button" class="ai-chip" data-ai-home-prompt="Je veux un projet mobile avec une vraie valeur utilisateur.">Projet mobile utile</button>
                        <button type="button" class="ai-chip" data-ai-home-prompt="Je cherche un projet data ou intelligence artificielle accessible.">Projet data / IA</button>
                    </div>
                    <div class="ai-chat-window" id="homeAiChat">
                        <div class="ai-bubble assistant">Je peux vous guider pour choisir un projet adapté à votre besoin. Décrivez votre objectif, votre niveau ou les technologies que vous préférez.</div>
                    </div>
                    <div class="ai-chip-row ai-chip-row--dynamic" id="homeAiDynamicSuggestions" style="margin-top:12px">
                        <button type="button" class="ai-chip" data-ai-home-prompt="Je cherche un projet web utile pour une universite.">Projet web utile</button>
                        <button type="button" class="ai-chip" data-ai-home-prompt="Je veux un projet simple a presenter devant un jury.">Projet pour jury</button>
                        <button type="button" class="ai-chip" data-ai-home-prompt="Je cherche un projet innovant mais realisable.">Projet innovant</button>
                    </div>
                </div>
                <div class="ai-modal__foot">
                    <div class="ai-compose">
                        <textarea id="homeAiInput" class="ai-input" placeholder="Ex : un projet de gestion avec base de données, utile pour l'école et réalisable en PHP."></textarea>
                        <button type="button" class="ai-send" id="homeAiSend"><i class='bx bx-send'></i> Envoyer</button>
                    </div>
                </div>
            </div>
        </div>

        <?php $this->view('Partials/footer'); ?>
    </main>

    <?php $this->view('Partials/scripts'); ?>
    <script>
        (function($) {
            const $form = $('#projectFilterForm');
            const $results = $('#projectResults');
            const $pageInput = $('#projectPageInput');
            const $aiModal = $('#homeAiModal');
            const $aiInput = $('#homeAiInput');
            const $aiSuggestions = $('#homeAiDynamicSuggestions');
            const endpoint = '<?= ROOT ?>/Homes/index';
            let debounceTimer = null;
            const homeState = { page: <?= (int) ($currentPage ?? 1) ?>, totalPages: <?= (int) ($totalPages ?? 1) ?> };
            const homeAiHistory = [{
                role: 'assistant',
                content: "Je peux vous guider pour choisir un projet adapte a votre besoin. Decrivez votre objectif, votre niveau ou les technologies que vous preferez."
            }];

            function openAiModal() {
                $aiModal.addClass('is-open').attr('aria-hidden', 'false');
                $('body').addClass('overflow-hidden');
                window.setTimeout(function() {
                    $aiInput.trigger('focus');
                }, 120);
            }

            function closeAiModal() {
                $aiModal.removeClass('is-open').attr('aria-hidden', 'true');
                $('body').removeClass('overflow-hidden');
            }

            function initProjectCarousels(scope) {
                if (typeof $.fn.slick !== 'function') return;
                var $scope = scope ? $(scope) : $results;
                $scope.find('.js-project-carousel').each(function() {
                    const $carousel = $(this);
                    if ($carousel.hasClass('slick-initialized')) $carousel.slick('unslick');
                    if ($carousel.children().length <= 1) return;
                    $carousel.slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: true,
                        dots: true,
                        infinite: true,
                        autoplay: true,
                        autoplaySpeed: 3600,
                        pauseOnHover: true,
                        speed: 700,
                        cssEase: 'cubic-bezier(.2,.8,.2,1)',
                        prevArrow: '<button type="button" class="slick-prev" aria-label="Image precedente"><i class="bx bx-chevron-left"></i></button>',
                        nextArrow: '<button type="button" class="slick-next" aria-label="Image suivante"><i class="bx bx-chevron-right"></i></button>'
                    });
                });
            }

            function initRevealOnScroll() {
                const items = document.querySelectorAll('.project-card-modern, .news-card, .nk-podium__hero, .nk-rankrow, .nk-ai');
                if (!('IntersectionObserver' in window)) {
                    items.forEach(function(item) {
                        item.classList.add('is-visible');
                    });
                    return;
                }
                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (!entry.isIntersecting) return;
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    });
                }, {
                    threshold: 0.12
                });
                items.forEach(function(item) {
                    item.classList.add('reveal-item');
                    observer.observe(item);
                });
            }

            function appendAiBubble(role, text) {
                const $chat = $('#homeAiChat');
                const safe = $('<div>').text(text).html().replace(/\n/g, '<br>');
                $chat.append('<div class="ai-bubble ' + role + '">' + safe + '</div>');
                $chat.scrollTop($chat[0].scrollHeight);
            }

            function renderHomeSuggestions(items) {
                const suggestions = Array.isArray(items) ? items.filter(Boolean).slice(0, 3) : [];
                if (!suggestions.length) return;
                $aiSuggestions.html(suggestions.map(function(item) {
                    return '<button type="button" class="ai-chip" data-ai-home-prompt="' + $('<div>').text(item)
                        .html() + '">' + $('<div>').text(item).html() + '</button>';
                }).join(''));
            }

            function sendHomeAiMessage(prompt) {
                const text = (prompt || $aiInput.val()).trim();
                if (!text) return;
                openAiModal();
                appendAiBubble('user', text);
                homeAiHistory.push({
                    role: 'user',
                    content: text
                });
                $aiInput.val('');
                $.post('<?= ROOT ?>/Homes/ai_assistant', {
                    message: text,
                    history: JSON.stringify(homeAiHistory.slice(-6))
                }, function(response) {
                    const answer = response && response.message ? response.message :
                        "Je n'ai pas pu repondre pour le moment.";
                    appendAiBubble('assistant', answer);
                    homeAiHistory.push({
                        role: 'assistant',
                        content: answer
                    });
                    renderHomeSuggestions(response && response.suggestions ? response.suggestions : []);
                }, 'json').fail(function() {
                    const fallback = "L'assistant IA n'est pas disponible pour le moment.";
                    appendAiBubble('assistant', fallback);
                    homeAiHistory.push({
                        role: 'assistant',
                        content: fallback
                    });
                });
            }

            function updateUrl(query) {
                window.history.replaceState({}, '', endpoint + (query ? '?' + query : ''));
            }

            function loadProjects(page) {
                if (page) $pageInput.val(page);
                const query = $form.serialize();
                $results.addClass('is-loading');
                $.ajax({
                        url: endpoint,
                        method: 'GET',
                        data: query,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .done(function(response) {
                        if (response && response.html) {
                            $results.html(response.html);
                            if (response.currentPage) $pageInput.val(response.currentPage);
                            homeState.page = parseInt(response.currentPage, 10) || 1;
                            homeState.totalPages = parseInt(response.totalPages, 10) || 1;
                            const syncedQuery = $form.serialize();
                            updateUrl(syncedQuery);
                            initProjectCarousels();
                            updateLoadMoreHome();
                            const block = document.getElementById('catalogue-projets');
                            if (block) block.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    })
                    .always(function() {
                        $results.removeClass('is-loading');
                    });
            }

            // « Charger plus » (accueil) : ajoute la page suivante au lieu de remplacer.
            function loadMoreHome() {
                if (homeState.page >= homeState.totalPages) return;
                $pageInput.val(homeState.page + 1);
                const $btn = $('#homeLoadMore').addClass('is-loading');
                $.ajax({ url: endpoint, method: 'GET', data: $form.serialize(), headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .done(function(response) {
                        if (response && response.html) {
                            const $newCards = $('<div>').html(response.html).find('.row').first().children();
                            $results.find('.row').first().append($newCards);
                            homeState.page = parseInt(response.currentPage, 10) || (homeState.page + 1);
                            homeState.totalPages = parseInt(response.totalPages, 10) || homeState.totalPages;
                            if (response.currentPage) $pageInput.val(response.currentPage);
                            initProjectCarousels($newCards);
                            updateLoadMoreHome();
                        }
                    })
                    .always(function() { $btn.removeClass('is-loading'); });
            }

            function updateLoadMoreHome() {
                $('#homeLoadMoreWrap').toggleClass('has-more', homeState.page < homeState.totalPages);
            }

            $form.on('submit', function(event) {
                event.preventDefault();
                $pageInput.val(1);
                loadProjects(1);
            });
            $('#projectCategorySelect, #perPageSelect').on('change', function() {
                $pageInput.val(1);
                loadProjects(1);
            });
            $('#projectSearchInput').on('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    $pageInput.val(1);
                    loadProjects(1);
                }, 350);
            });
            $('#projectFilterReset').on('click', function(event) {
                event.preventDefault();
                $('#projectSearchInput').val('');
                $('#projectCategorySelect').val('');
                $('#perPageSelect').val('5');
                $('.nk-cat').removeClass('is-active');
                $('.nk-cat[data-cat=""]').addClass('is-active');
                $pageInput.val(1);
                loadProjects(1);
            });
            $results.on('click', '.page-nav[data-page]', function() {
                const page = parseInt($(this).data('page'), 10);
                if (!page || $(this).is(':disabled')) return;
                loadProjects(page);
            });
            $('#homeLoadMore').on('click', loadMoreHome);
            $(document).on('click', '.nk-cat[data-cat]', function(event) {
                event.preventDefault();
                const cat = String($(this).data('cat') || '');
                $('#projectCategorySelect').val(cat);
                $('.nk-cat').removeClass('is-active');
                $(this).addClass('is-active');
                $pageInput.val(1);
                loadProjects(1);
            });
            $('.js-open-home-ai-modal').on('click', openAiModal);
            $('#closeHomeAiModal').on('click', closeAiModal);
            $aiModal.on('click', function(event) {
                if (event.target === this) closeAiModal();
            });
            $(document).on('keydown', function(event) {
                if (event.key === 'Escape' && $aiModal.hasClass('is-open')) closeAiModal();
            });
            $('#homeAiSend').on('click', function() {
                sendHomeAiMessage();
            });
            $aiInput.on('keydown', function(event) {
                if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') sendHomeAiMessage();
            });
            $(document).on('click', '[data-ai-home-prompt]', function() {
                sendHomeAiMessage($(this).data('ai-home-prompt'));
            });

            // Vue grille / liste (memorisee, defaut liste) — partagee avec la page Liste.
            (function () {
                var KEY = 'ngakodon-projects-view';
                var shell = document.getElementById('projectResults');
                var btns = document.querySelectorAll('.pl-view__btn');
                if (!shell || !btns.length) return;
                function applyView(view, reinit) {
                    shell.classList.toggle('is-list', view === 'list');
                    btns.forEach(function (b) { b.classList.toggle('is-active', b.getAttribute('data-view') === view); });
                    if (reinit) initProjectCarousels();
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
            })();

            initProjectCarousels();
            initRevealOnScroll();
            updateLoadMoreHome();
        })(jQuery);
    </script>
</body>

</html>
