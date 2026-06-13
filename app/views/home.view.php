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
$presentationBenefits = $presentationBenefits ?? [];
$presentationFlow = $presentationFlow ?? [];
$departmentAnnouncements = $departmentAnnouncements ?? [];
$departmentInformations = $departmentInformations ?? [];
$departmentResults = $departmentResults ?? [];
$departmentOpportunities = $departmentOpportunities ?? [];
$heroVisual = $featuredProject['image'] ?? (ROOT . '/assets/images/thumbs/banner-img.png');
$heroSlides = [];
foreach (array_merge([$featuredProject], $topLikedProjects, $projects) as $slideProject) {
    if (!is_array($slideProject)) {
        continue;
    }
    $slideImage = $slideProject['image'] ?? (($slideProject['images'][0] ?? null));
    if ($slideImage && !in_array($slideImage, $heroSlides, true)) {
        $heroSlides[] = $slideImage;
    }
    if (count($heroSlides) >= 4) {
        break;
    }
}
if (empty($heroSlides)) {
    $heroSlides[] = $heroVisual;
}

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
?>

<body class="public-site um6p-site">
    <?php $this->view('Partials/global-shell'); ?>
    <?php $this->view('Partials/mobile-menu'); ?>
    <?php $this->view('Partials/header'); ?>
    <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

    <main class="change-gradient ai-home um6p-home">
        <style>
        .ai-home {
            --pri: #0f766e;
            --pri2: #14b8a6;
            --ink: #0f172a;
            --muted: #475569;
            --line: #dbe4ee;
            background: radial-gradient(circle at top left, rgba(15, 118, 110, .08), transparent 28%), linear-gradient(180deg, #f8fffe 0%, #fff 38%, #f8fafc 100%)
        }

        .ai-shell,
        .ai-card,
        .project-card-modern,
        .news-card,
        .empty-projects {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 30px;
            box-shadow: 0 24px 60px -40px rgba(15, 23, 42, .38)
        }

        .hero {
            padding: 72px 0 32px
        }

        .ai-shell,
        .ai-card {
            padding: 26px
        }

        .hero h1 {
            font-size: clamp(2.3rem, 4vw, 4.3rem);
            font-weight: 900;
            line-height: 1.02;
            color: var(--ink)
        }

        .hero h1 span {
            color: var(--pri)
        }

        .hero p,
        .section-copy,
        .project-text {
            color: var(--muted)
        }

        .hero-chip,
        .metric,
        .badge-pill,
        .ai-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px
        }

        .hero-chip {
            padding: 8px 16px;
            background: #ecfeff;
            color: #115e59;
            font-weight: 800;
            margin-bottom: 16px
        }

        .hero-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 22px
        }

        .hero-btn,
        .hero-btn-outline,
        .project-link,
        .ai-send {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            font-weight: 800;
            transition: .25s
        }

        .hero-btn,
        .ai-send {
            background: linear-gradient(135deg, var(--pri), var(--pri2));
            color: #fff;
            border: none;
            padding: 14px 20px;
            border-radius: 999px
        }

        .hero-btn-outline {
            padding: 14px 20px;
            border-radius: 999px;
            background: #fff;
            color: #115e59;
            border: 1px solid rgba(15, 118, 110, .18)
        }

        .hero-btn:hover,
        .hero-btn-outline:hover,
        .project-link:hover,
        .ai-send:hover {
            transform: translateY(-2px)
        }

        .metrics {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 24px
        }

        .metric {
            padding: 16px;
            border-radius: 22px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            flex-direction: column;
            align-items: flex-start
        }

        .metric strong {
            font-size: 1.6rem;
            color: var(--ink)
        }

        .section-head {
            margin-bottom: 22px
        }

        .section-head small {
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--pri)
        }

        .section-head h2 {
            font-size: clamp(1.8rem, 3vw, 2.8rem);
            font-weight: 900;
            color: var(--ink);
            margin: 10px 0 10px
        }

        .home-grid {
            display: grid;
            grid-template-columns: 1.15fr .85fr;
            gap: 22px;
            margin-top: 28px
        }

        .top-liked-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px
        }

        .mini-card {
            overflow: hidden;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            background: #fff
        }

        .mini-card img {
            width: 100%;
            height: 170px;
            object-fit: cover
        }

        .mini-card__body {
            padding: 16px
        }

        .ai-launcher {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 18px;
            min-height: 100%
        }

        .ai-launcher__preview {
            display: grid;
            gap: 12px
        }

        .ai-launcher__mini {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 18px;
            background: #f8fafc;
            border: 1px solid #e2e8f0
        }

        .ai-launcher__mini i {
            font-size: 1.25rem;
            color: var(--pri)
        }

        .ai-modal {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .56);
            backdrop-filter: blur(6px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            z-index: 1080
        }

        .ai-modal.is-open {
            display: flex
        }

        .ai-modal__dialog {
            width: min(920px, 100%);
            max-height: min(88vh, 920px);
            overflow: hidden;
            border-radius: 30px;
            background: #fff;
            border: 1px solid #dbe4ee;
            box-shadow: 0 30px 80px -35px rgba(15, 23, 42, .55);
            display: grid;
            grid-template-rows: auto 1fr auto
        }

        .ai-modal__head,
        .ai-modal__foot {
            padding: 20px 22px;
            border-bottom: 1px solid #e2e8f0
        }

        .ai-modal__foot {
            border-bottom: 0;
            border-top: 1px solid #e2e8f0
        }

        .ai-modal__close {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            border: 1px solid #dbe4ee;
            background: #fff;
            color: #0f172a;
            font-size: 1.15rem
        }

        .ai-modal__body {
            padding: 18px 22px 8px;
            overflow: auto
        }

        .ai-chat-window {
            display: flex;
            flex-direction: column;
            gap: 12px;
            min-height: 330px;
            max-height: 480px;
            overflow: auto;
            padding-right: 4px
        }

        .ai-bubble {
            max-width: 92%;
            padding: 14px 16px;
            border-radius: 22px;
            white-space: pre-wrap;
            line-height: 1.65
        }

        .ai-bubble.assistant {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155
        }

        .ai-bubble.user {
            background: #ecfeff;
            color: #134e4a;
            margin-left: auto
        }

        .ai-chip-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 14px 0 12px
        }

        .ai-chip-row--dynamic {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0
        }

        .ai-chip {
            padding: 10px 14px;
            border: none;
            background: #f0fdfa;
            color: #115e59;
            font-weight: 800
        }

        .ai-compose {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            margin-top: 14px
        }

        .ai-input {
            width: 100%;
            min-height: 110px;
            border: 1px solid #dbe4ee;
            border-radius: 18px;
            padding: 14px 16px
        }

        .filters {
            padding: 24px;
            margin: 26px 0
        }

        .filters .form-control,
        .filters .form-select {
            min-height: 54px;
            border-radius: 18px;
            border: 1px solid #dbe4ee
        }

        .project-results-shell {
            position: relative;
            min-height: 180px
        }

        .project-results-shell.is-loading {
            opacity: .7;
            pointer-events: none
        }

        .project-card-modern {
            overflow: hidden;
            height: 100%;
            transition: .25s
        }

        .project-card-modern:hover {
            transform: translateY(-5px)
        }

        .project-visual,
        .project-slide,
        .project-slide img {
            height: 300px
        }

        .project-slide img {
            width: 100%;
            object-fit: cover
        }

        .project-carousel,
        .project-carousel .slick-list,
        .project-carousel .slick-track {
            height: 100%
        }

        .project-carousel .slick-dots {
            position: absolute;
            left: 16px;
            bottom: 12px;
            display: flex;
            gap: 8px;
            list-style: none;
            margin: 0;
            padding: 0;
            z-index: 3
        }

        .project-carousel .slick-dots li button {
            width: 10px;
            height: 10px;
            border: none;
            border-radius: 50%;
            font-size: 0;
            background: rgba(255, 255, 255, .48)
        }

        .project-carousel .slick-dots li.slick-active button {
            background: #fff
        }

        .project-carousel .slick-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 3;
            width: 42px;
            height: 42px;
            border: none;
            border-radius: 50%;
            background: rgba(15, 23, 42, .65);
            color: #fff
        }

        .project-carousel .slick-prev {
            left: 14px
        }

        .project-carousel .slick-next {
            right: 14px
        }

        .project-category,
        .project-image-count,
        .tech-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px
        }

        .project-category {
            position: absolute;
            left: 16px;
            top: 16px;
            padding: 8px 14px;
            background: rgba(255, 255, 255, .95);
            color: #115e59;
            font-weight: 800;
            z-index: 3
        }

        .project-image-count {
            position: absolute;
            right: 16px;
            bottom: 14px;
            padding: 8px 14px;
            background: rgba(15, 23, 42, .72);
            color: #fff;
            font-weight: 700;
            z-index: 3
        }

        .project-body {
            padding: 24px
        }

        .project-stats {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 14px
        }

        .project-stat {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: .9rem;
            font-weight: 800;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #334155
        }

        .project-stat i {
            font-size: 1rem
        }

        .project-stat--rating {
            background: #fff7ed;
            border-color: #fed7aa;
            color: #b45309
        }

        .project-stat--rating i {
            color: #f59e0b;
            text-shadow: 0 2px 10px rgba(245, 158, 11, .28)
        }

        .project-stat--likes {
            background: #fff1f2;
            border-color: #fecdd3;
            color: #e11d48
        }

        .project-stat--likes i {
            color: #f43f5e;
            text-shadow: 0 2px 10px rgba(244, 63, 94, .24)
        }

        .project-stat--reviews {
            background: #eef2ff;
            border-color: #c7d2fe;
            color: #4338ca
        }

        .project-stat--reviews i {
            color: #4f46e5;
            text-shadow: 0 2px 10px rgba(79, 70, 229, .22)
        }

        .project-title {
            font-size: 1.28rem;
            font-weight: 800;
            line-height: 1.28;
            color: var(--ink);
            margin-bottom: 12px
        }

        .project-title a {
            text-decoration: none;
            color: inherit
        }

        .project-meta,
        .project-actions,
        .news-meta {
            display: flex;
            gap: 12px;
            flex-wrap: wrap
        }

        .tech-list {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin: 14px 0 18px
        }

        .tech-pill {
            padding: 7px 12px;
            background: #ecfeff;
            color: #115e59;
            font-size: .85rem;
            font-weight: 700
        }

        .project-link {
            color: #115e59
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px
        }

        .salon-grid {
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 22px;
            align-items: stretch;
            margin-bottom: 28px
        }

        .salon-hero-card {
            padding: 34px;
            background:
                radial-gradient(circle at top right, rgba(20, 184, 166, .18), transparent 30%),
                linear-gradient(145deg, #ffffff 0%, #f5fffd 100%)
        }

        .salon-kicker {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            border-radius: 999px;
            background: #ecfeff;
            color: #115e59;
            font-weight: 900;
            letter-spacing: .05em;
            text-transform: uppercase;
            font-size: .82rem
        }

        .salon-lead {
            font-size: clamp(2.4rem, 5vw, 4.6rem);
            line-height: 1.02;
            font-weight: 950;
            color: var(--ink);
            margin: 18px 0 16px
        }

        .salon-lead span {
            color: var(--pri)
        }

        .salon-proof {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 22px
        }

        .salon-proof__item,
        .benefit-card,
        .flow-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            box-shadow: 0 18px 40px -34px rgba(15, 23, 42, .34)
        }

        .salon-proof__item {
            padding: 18px
        }

        .salon-proof__item strong {
            display: block;
            font-size: 1.6rem;
            color: var(--ink)
        }

        .salon-side {
            padding: 26px;
            background: linear-gradient(165deg, #0f172a 0%, #17344d 55%, #0f766e 100%);
            color: #fff
        }

        .salon-side .section-copy,
        .salon-side .project-text {
            color: rgba(255, 255, 255, .78)
        }

        .salon-checklist {
            display: grid;
            gap: 12px;
            margin-top: 18px
        }

        .salon-check {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .12)
        }

        .salon-check i {
            font-size: 1.15rem;
            color: #5eead4;
            margin-top: 2px
        }

        .benefits-grid,
        .flow-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px
        }

        .benefit-card,
        .flow-card {
            padding: 22px
        }

        .benefit-card i,
        .flow-step {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 54px;
            height: 54px;
            border-radius: 18px;
            margin-bottom: 16px
        }

        .benefit-card i {
            background: #ecfeff;
            color: #0f766e;
            font-size: 1.5rem
        }

        .flow-step {
            background: #eef2ff;
            color: #3730a3;
            font-weight: 900;
            font-size: 1rem
        }

        .salon-highlight {
            display: grid;
            grid-template-columns: .95fr 1.05fr;
            gap: 18px;
            margin: 24px 0 30px
        }

        .news-card {
            padding: 18px
        }

        .badge-pill {
            padding: 8px 12px;
            font-weight: 800
        }

        .badge-ann {
            background: #dcfce7;
            color: #166534
        }

        .badge-info {
            background: #e0f2fe;
            color: #075985
        }

        .badge-res {
            background: #f5d0fe;
            color: #86198f
        }

        .badge-op {
            background: #ffedd5;
            color: #9a3412
        }

        .project-pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-top: 28px
        }

        .project-pagination-summary {
            color: var(--muted);
            font-weight: 700
        }

        .project-pagination {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap
        }

        .page-nav {
            min-width: 52px;
            height: 52px;
            padding: 0 14px;
            border-radius: 18px;
            border: 1px solid #d7e3ea;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            color: var(--ink);
            font-weight: 900;
            font-size: 1rem;
            letter-spacing: .01em;
            box-shadow: 0 14px 28px -24px rgba(15, 23, 42, .35);
            transition: transform .22s ease, box-shadow .22s ease, background .22s ease, border-color .22s ease, color .22s ease
        }

        .page-nav.page-arrow {
            min-width: 56px;
            background: #f8fafc;
            color: #334155
        }

        .page-nav.is-active {
            background: linear-gradient(135deg, var(--pri), var(--pri2));
            color: #fff;
            border-color: transparent;
            box-shadow: 0 22px 36px -24px rgba(15, 118, 110, .95);
            transform: translateY(-2px)
        }

        .page-nav:hover:not(:disabled) {
            background: linear-gradient(135deg, #134e4a, #0f766e);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 18px 32px -24px rgba(15, 118, 110, .8);
            transform: translateY(-2px)
        }

        .page-nav:disabled {
            opacity: .42;
            cursor: not-allowed;
            box-shadow: none;
            transform: none
        }

        @media (max-width:1199px) {
            .hero {
                padding: 56px 0 24px
            }

            .home-grid {
                grid-template-columns: 1fr
            }

            .salon-grid,
            .salon-highlight {
                grid-template-columns: 1fr
            }

            .news-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .top-liked-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .benefits-grid,
            .flow-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .project-visual,
            .project-slide,
            .project-slide img {
                height: 260px
            }
        }

        @media (max-width:991px) {

            .ai-shell,
            .ai-card,
            .project-card-modern,
            .news-card,
            .empty-projects {
                border-radius: 24px
            }

            .ai-shell,
            .ai-card {
                padding: 22px
            }

            .hero h1 {
                font-size: clamp(2rem, 7vw, 3.2rem)
            }

            .hero-actions {
                flex-direction: column;
                align-items: stretch
            }

            .hero-btn,
            .hero-btn-outline,
            .ai-send {
                width: 100%
            }

            .metrics {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .salon-proof {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .filters {
                padding: 20px
            }

            .project-body {
                padding: 20px
            }

            .project-pagination-wrap {
                justify-content: center
            }

            .project-pagination {
                justify-content: center
            }

            .ai-chat-window {
                min-height: 280px;
                max-height: 380px
            }

            .ai-modal {
                padding: 14px
            }

            .ai-modal__dialog {
                border-radius: 24px
            }
        }

        @media (max-width:767px) {
            .hero {
                padding: 42px 0 18px
            }

            .ai-shell,
            .ai-card,
            .project-card-modern,
            .news-card,
            .empty-projects {
                border-radius: 20px
            }

            .hero-chip,
            .metric,
            .badge-pill,
            .ai-chip,
            .project-category,
            .project-image-count,
            .tech-pill {
                font-size: .85rem
            }

            .section-head h2 {
                font-size: clamp(1.5rem, 8vw, 2rem)
            }

            .metrics {
                grid-template-columns: 1fr
            }

            .metric strong {
                font-size: 1.35rem
            }

            .top-liked-grid,
            .news-grid,
            .benefits-grid,
            .flow-grid {
                grid-template-columns: 1fr
            }

            .salon-hero-card,
            .salon-side {
                padding: 22px
            }

            .salon-proof {
                grid-template-columns: 1fr
            }

            .mini-card img {
                height: 210px
            }

            .ai-compose {
                grid-template-columns: 1fr
            }

            .ai-input {
                min-height: 96px
            }

            .project-visual,
            .project-slide,
            .project-slide img {
                height: 220px
            }

            .project-body {
                padding: 18px
            }

            .project-title {
                font-size: 1.1rem
            }

            .project-meta,
            .project-actions,
            .news-meta {
                gap: 10px;
                font-size: .92rem
            }

            .project-stats {
                gap: 8px
            }

            .project-stat {
                font-size: .85rem;
                padding: 7px 10px
            }

            .project-pagination-wrap {
                align-items: flex-start
            }

            .project-pagination-summary {
                width: 100%;
                text-align: center
            }

            .page-nav {
                min-width: 46px;
                height: 46px;
                padding: 0 12px;
                border-radius: 15px;
                font-size: .95rem
            }

            .page-nav.page-arrow {
                min-width: 50px
            }

            .ai-modal__head,
            .ai-modal__body,
            .ai-modal__foot {
                padding-left: 16px;
                padding-right: 16px
            }
        }

        @media (max-width:575px) {

            .hero-btn,
            .hero-btn-outline,
            .ai-send {
                padding: 13px 16px
            }

            .featured-media {
                height: 200px !important
            }

            .ai-chat-window {
                min-height: 240px;
                max-height: 320px
            }

            .project-carousel .slick-arrow {
                width: 36px;
                height: 36px
            }

            .project-carousel .slick-prev {
                left: 10px
            }

            .project-carousel .slick-next {
                right: 10px
            }

            .filters .form-control,
            .filters .form-select {
                min-height: 50px
            }

            .project-results-shell {
                min-height: 140px
            }

            .ai-modal__dialog {
                max-height: 92vh;
                border-radius: 20px
            }
        }
        </style>

        <style>
        .um6p-site .header {
            background: rgba(255, 255, 255, .94);
            border-bottom: 1px solid rgba(26, 28, 35, .08);
            box-shadow: 0 18px 50px -42px rgba(26, 28, 35, .45);
            position: sticky;
            top: 0;
            z-index: 1020;
            backdrop-filter: blur(16px)
        }

        .um6p-site .header-inner {
            min-height: 82px
        }

        .um6p-site .logo img {
            max-height: 54px;
            width: auto
        }

        .um6p-site .nav-menu {
            gap: 34px
        }

        .um6p-site .nav-menu__link {
            color: #151515;
            font-weight: 800;
            letter-spacing: 0;
            padding: 28px 0;
            position: relative
        }

        .um6p-site .nav-menu__link::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 21px;
            height: 3px;
            background: #c7511f;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .22s ease
        }

        .um6p-site .nav-menu__link:hover::after {
            transform: scaleX(1)
        }

        .um6p-site .btn-primary,
        .um6p-site .btn-main,
        .um6p-site .hero-btn,
        .um6p-site .ai-send {
            background: #c7511f !important;
            border-color: #c7511f !important;
            color: #fff !important;
            border-radius: 3px !important;
            box-shadow: none
        }

        .um6p-site .hero-btn-outline {
            border-radius: 3px !important;
            color: #151515;
            border: 1px solid #d8d0c7 !important;
            background: #fff
        }

        .um6p-home {
            --pri: #c7511f;
            --pri2: #8f2d13;
            --ink: #171717;
            --muted: #5c5c5c;
            --line: #e7dfd7;
            background: #f8f5ef
        }

        .um6p-home .hero {
            padding: 34px 0 20px
        }

        .um6p-home .salon-grid {
            grid-template-columns: minmax(0, .92fr) minmax(0, 1.08fr);
            min-height: calc(100vh - 128px);
            gap: 0;
            align-items: stretch;
            margin-top: 0;
            background: #fff;
            border: 1px solid rgba(26, 28, 35, .08)
        }

        .um6p-home .ai-card,
        .um6p-home .project-card-modern,
        .um6p-home .news-card,
        .um6p-home .empty-projects,
        .um6p-home .mini-card {
            border-radius: 0;
            box-shadow: none;
            border-color: rgba(26, 28, 35, .1)
        }

        .um6p-home .salon-hero-card {
            padding: clamp(32px, 5vw, 76px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #fff
        }

        .um6p-home .salon-kicker,
        .um6p-home .section-head small,
        .um6p-home .hero-chip {
            background: transparent;
            color: #c7511f;
            padding: 0;
            border-radius: 0;
            letter-spacing: .14em;
            font-size: .78rem
        }

        .um6p-home .salon-lead {
            max-width: 820px;
            color: #171717;
            font-size: clamp(2.45rem, 5.7vw, 6rem);
            line-height: .95;
            letter-spacing: 0;
            margin: 20px 0 22px
        }

        .um6p-home .salon-lead span {
            color: #c7511f
        }

        .um6p-home .section-copy,
        .um6p-home .project-text,
        .um6p-home .hero p {
            color: #5c5c5c;
            font-size: 1rem
        }

        .um6p-home .hero-actions {
            gap: 12px;
            margin-top: 26px
        }

        .um6p-home .hero-btn,
        .um6p-home .hero-btn-outline,
        .um6p-home .project-link {
            border-radius: 3px;
            min-height: 48px;
            padding: 13px 18px;
            font-weight: 900
        }

        .um6p-home .salon-proof {
            grid-template-columns: repeat(4, minmax(0, 1fr));
            border-top: 1px solid rgba(26, 28, 35, .1);
            margin-top: 38px;
            padding-top: 22px
        }

        .um6p-home .salon-proof__item {
            border: 0;
            border-left: 3px solid #c7511f;
            border-radius: 0;
            padding: 0 0 0 14px;
            box-shadow: none
        }

        .um6p-home .salon-proof__item strong {
            font-size: clamp(1.35rem, 2vw, 2rem);
            color: #171717
        }

        .um6p-home .salon-side {
            position: relative;
            min-height: 620px;
            padding: 0;
            overflow: hidden;
            background: #1a1a1a
        }

        .um6p-home .um6p-campus-frame,
        .um6p-home .um6p-campus-frame img {
            width: 100%;
            height: 100%
        }

        .um6p-home .um6p-campus-frame img {
            display: block;
            object-fit: cover;
            filter: saturate(.95) contrast(1.04)
        }

        .um6p-home .um6p-campus-caption {
            position: absolute;
            left: clamp(22px, 4vw, 48px);
            right: clamp(22px, 4vw, 48px);
            bottom: clamp(22px, 4vw, 48px);
            background: rgba(23, 23, 23, .82);
            color: #fff;
            padding: 22px;
            border-left: 5px solid #c7511f;
            backdrop-filter: blur(10px)
        }

        .um6p-home .um6p-campus-caption h2 {
            color: #fff;
            margin: 8px 0;
            font-size: clamp(1.35rem, 2.4vw, 2.25rem);
            line-height: 1.1
        }

        .um6p-home .um6p-campus-caption p {
            color: rgba(255, 255, 255, .78);
            margin: 0
        }

        .um6p-home .top-liked-shell,
        .um6p-home .ai-launcher,
        .um6p-home .filters {
            background: #fff;
            border: 1px solid rgba(26, 28, 35, .1);
            padding: clamp(24px, 4vw, 42px);
            margin-top: 28px
        }

        .um6p-home .section-head h2 {
            color: #171717;
            font-size: clamp(1.75rem, 3.2vw, 3.45rem);
            letter-spacing: 0;
            line-height: 1
        }

        .um6p-home .top-liked-grid,
        .um6p-home .news-grid {
            gap: 18px
        }

        .um6p-home .mini-card {
            background: #fbfaf7;
            transition: transform .22s ease
        }

        .um6p-home .mini-card:hover,
        .um6p-home .project-card-modern:hover,
        .um6p-home .news-card:hover {
            transform: translateY(-4px)
        }

        .um6p-home .mini-card img,
        .um6p-home .project-slide img {
            filter: saturate(.92)
        }

        .um6p-home .project-category,
        .um6p-home .tech-pill,
        .um6p-home .project-stat {
            border-radius: 2px;
            background: #f3eee7;
            color: #7b351b;
            border-color: #eaded3
        }

        .um6p-home .project-image-count {
            border-radius: 2px;
            background: rgba(23, 23, 23, .74)
        }

        .um6p-home .project-title,
        .um6p-home .publication-title {
            color: #171717;
            letter-spacing: 0
        }

        .um6p-home .project-link {
            color: #c7511f;
            padding: 0;
            min-height: auto
        }

        .um6p-home .filters .form-control,
        .um6p-home .filters .form-select,
        .um6p-home .ai-input {
            border-radius: 2px;
            border-color: #d8d0c7;
            background-color: #fff
        }

        .um6p-home .results-chip,
        .um6p-home .badge-pill,
        .um6p-home .ai-chip {
            border-radius: 2px;
            background: #efe5dc;
            color: #7b351b
        }

        .um6p-home .news-card {
            min-height: 100%;
            background: #fff
        }

        .um6p-home .footer {
            background: #171717
        }

        @media (max-width: 1199px) {
            .um6p-home .salon-grid {
                grid-template-columns: 1fr;
                min-height: auto
            }

            .um6p-home .salon-side {
                min-height: 520px
            }
        }

        @media (max-width: 767px) {
            .um6p-site .header-inner {
                min-height: 70px
            }

            .um6p-home .hero {
                padding-top: 18px
            }

            .um6p-home .salon-hero-card {
                padding: 26px 20px
            }

            .um6p-home .salon-lead {
                font-size: clamp(2.25rem, 13vw, 3.8rem)
            }

            .um6p-home .salon-proof {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .um6p-home .salon-side {
                min-height: 420px
            }

            .um6p-home .um6p-campus-caption {
                left: 16px;
                right: 16px;
                bottom: 16px;
                padding: 18px
            }
        }
        </style>

        <style>
        .um6p-site .header {
            background: rgba(255, 250, 244, .97) !important;
            border-bottom: 1px solid rgba(23, 21, 18, .08) !important;
            box-shadow: 0 18px 50px -44px rgba(23, 21, 18, .5) !important
        }

        .um6p-site .logo img {
            max-height: 50px !important
        }

        .um6p-site .nav-menu__link {
            color: #171512 !important;
            font-weight: 750 !important
        }

        .um6p-home {
            background:
                linear-gradient(180deg, #fffaf4 0%, #f6f1ea 44%, #fffaf4 100%) !important;
            font-family: "Manrope", "Segoe UI", Roboto, Arial, sans-serif !important
        }

        .um6p-home .hero {
            padding: clamp(22px, 4vw, 48px) 0 28px !important
        }

        .um6p-home .salon-grid {
            grid-template-columns: minmax(0, .96fr) minmax(0, 1.04fr) !important;
            gap: clamp(18px, 2.6vw, 34px) !important;
            min-height: auto !important;
            background: transparent !important;
            border: 0 !important;
            align-items: stretch !important
        }

        .um6p-home .ai-card,
        .um6p-home .project-card-modern,
        .um6p-home .news-card,
        .um6p-home .empty-projects,
        .um6p-home .mini-card {
            border-radius: 18px !important;
            border: 1px solid rgba(23, 21, 18, .1) !important;
            box-shadow: 0 24px 70px -50px rgba(23, 21, 18, .48) !important;
            background: #fff !important
        }

        .um6p-home .salon-hero-card {
            min-height: 620px !important;
            padding: clamp(32px, 5.5vw, 70px) !important;
            background:
                linear-gradient(145deg, rgba(255, 250, 244, .96), rgba(255, 255, 255, .98)),
                #fff !important;
            justify-content: center !important
        }

        .um6p-home .salon-kicker,
        .um6p-home .section-head small,
        .um6p-home .hero-chip {
            color: #bd4a1f !important;
            background: #f4e6dc !important;
            border-radius: 999px !important;
            padding: 8px 13px !important;
            letter-spacing: .08em !important;
            font-weight: 850 !important
        }

        .um6p-home .salon-lead {
            color: #171512 !important;
            font-family: "Sora", "Manrope", "Segoe UI", sans-serif !important;
            font-size: clamp(2.15rem, 4.45vw, 4.45rem) !important;
            line-height: 1.08 !important;
            letter-spacing: 0 !important;
            max-width: 850px !important;
            margin: 22px 0 !important
        }

        .um6p-home .salon-lead .lead-line {
            color: #171512 !important;
            display: block !important;
            max-width: 900px
        }

        .um6p-home .salon-lead .lead-line--accent {
            color: #bd4a1f !important
        }

        .um6p-home .section-copy,
        .um6p-home .project-text {
            color: #67615a !important;
            line-height: 1.7 !important
        }

        .um6p-home .hero-btn,
        .um6p-home .hero-btn-outline,
        .um6p-home .ai-send {
            border-radius: 12px !important;
            min-height: 52px !important;
            padding: 14px 19px !important;
            box-shadow: none !important
        }

        .um6p-home .hero-btn {
            background: #bd4a1f !important;
            border-color: #bd4a1f !important
        }

        .um6p-home .hero-btn-outline {
            background: #fff !important;
            border: 1px solid #e3d8cc !important;
            color: #171512 !important
        }

        .um6p-home .salon-proof {
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            gap: 12px !important;
            border-top: 0 !important;
            margin-top: 34px !important;
            padding-top: 0 !important
        }

        .um6p-home .salon-proof__item {
            border: 1px solid #eaded3 !important;
            border-radius: 14px !important;
            padding: 17px !important;
            background: #fffaf7 !important
        }

        .um6p-home .salon-proof__item strong {
            color: #171512 !important;
            font-size: clamp(1.25rem, 2vw, 1.85rem) !important
        }

        .um6p-home .salon-side {
            min-height: 620px !important;
            border-radius: 18px !important;
            background: #123c34 !important
        }

        .um6p-home .um6p-campus-frame img {
            border-radius: 18px !important
        }

        .um6p-home .um6p-campus-caption {
            left: 28px !important;
            right: 28px !important;
            bottom: 28px !important;
            border-radius: 16px !important;
            border: 1px solid rgba(255, 250, 244, .16) !important;
            border-left: 5px solid #bd4a1f !important;
            background: rgba(18, 60, 52, .88) !important
        }

        .um6p-home .top-liked-shell,
        .um6p-home .ai-launcher,
        .um6p-home .filters {
            border-radius: 18px !important;
            background: #fff !important;
            border: 1px solid rgba(23, 21, 18, .1) !important;
            padding: clamp(24px, 4vw, 42px) !important;
            box-shadow: 0 24px 70px -52px rgba(23, 21, 18, .42) !important
        }

        .um6p-home .section-head h2 {
            color: #171512 !important;
            line-height: 1.05 !important;
            letter-spacing: 0 !important
        }

        .um6p-home .mini-card,
        .um6p-home .project-card-modern {
            overflow: hidden !important;
            transition: transform .22s ease, box-shadow .22s ease !important
        }

        .um6p-home .mini-card:hover,
        .um6p-home .project-card-modern:hover,
        .um6p-home .news-card:hover {
            transform: translateY(-4px) !important;
            box-shadow: 0 30px 78px -50px rgba(23, 21, 18, .55) !important
        }

        .um6p-home .project-body,
        .um6p-home .mini-card__body,
        .um6p-home .news-card {
            padding: 22px !important
        }

        .um6p-home .project-title,
        .um6p-home .publication-title {
            color: #171512 !important;
            font-weight: 850 !important;
            line-height: 1.22 !important
        }

        .um6p-home .project-category,
        .um6p-home .project-stat,
        .um6p-home .tech-pill,
        .um6p-home .badge-pill,
        .um6p-home .results-chip,
        .um6p-home .ai-chip {
            border-radius: 999px !important;
            background: #f4e6dc !important;
            color: #873115 !important;
            border: 1px solid #eaded3 !important
        }

        .um6p-home .project-link {
            color: #bd4a1f !important;
            font-weight: 850 !important
        }

        .um6p-home .filters .form-control,
        .um6p-home .filters .form-select,
        .um6p-home .ai-input {
            border-radius: 12px !important;
            border: 1px solid #e3d8cc !important;
            min-height: 52px !important
        }

        .um6p-home .page-nav {
            border-radius: 12px !important
        }

        .um6p-home .page-nav.is-active {
            background: #bd4a1f !important
        }

        @media (max-width: 1199px) {
            .um6p-home .salon-grid {
                grid-template-columns: 1fr !important
            }

            .um6p-home .salon-hero-card,
            .um6p-home .salon-side {
                min-height: auto !important
            }

            .um6p-home .salon-side {
                height: 520px !important
            }
        }

        @media (max-width: 767px) {
            .um6p-home .salon-proof {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important
            }

            .um6p-home .salon-side {
                height: 430px !important
            }

            .um6p-home .um6p-campus-caption {
                left: 16px !important;
                right: 16px !important;
                bottom: 16px !important
            }
        }

        .um6p-home .catalogue-filter-grid {
            display: grid !important;
            grid-template-columns: minmax(260px, 1.2fr) minmax(210px, .85fr) minmax(150px, .55fr) minmax(170px, auto) !important;
            gap: 16px !important;
            align-items: end !important;
            margin-top: 24px !important
        }

        .um6p-home .filter-actions {
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 10px !important
        }

        .um6p-home .filter-actions .hero-btn,
        .um6p-home .filter-actions .hero-btn-outline {
            width: 100% !important;
            justify-content: center !important;
            white-space: nowrap !important;
            margin: 0 !important
        }

        .um6p-home .empty-projects {
            display: grid !important;
            grid-template-columns: auto minmax(0, 1fr) !important;
            gap: 24px !important;
            align-items: center !important;
            padding: clamp(28px, 5vw, 54px) !important;
            min-height: 280px !important;
            background: radial-gradient(circle at 100% 0%, rgba(189, 74, 31, .12), transparent 32%), linear-gradient(135deg, #ffffff, #fffaf4) !important;
            overflow: hidden !important
        }

        .um6p-home .empty-projects__icon {
            width: clamp(82px, 10vw, 118px) !important;
            height: clamp(82px, 10vw, 118px) !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 28px !important;
            background: #123c34 !important;
            color: #fffaf4 !important;
            animation: public-float 3.6s ease-in-out infinite !important
        }

        .um6p-home .empty-projects__icon i {
            font-size: clamp(2.35rem, 5vw, 4rem) !important
        }

        .um6p-home .empty-projects__eyebrow {
            display: inline-flex !important;
            margin-bottom: 10px !important;
            border-radius: 999px !important;
            padding: 8px 12px !important;
            background: #f4e6dc !important;
            color: #873115 !important;
            font-size: .78rem !important;
            font-weight: 850 !important;
            letter-spacing: .08em !important;
            text-transform: uppercase !important
        }

        .um6p-home .empty-projects h3 {
            margin: 0 0 10px !important;
            font-family: "Sora", "Manrope", sans-serif !important;
            color: #171512 !important;
            font-size: clamp(1.55rem, 3vw, 2.55rem) !important;
            line-height: 1.08 !important
        }

        .um6p-home .empty-projects p {
            margin: 0 0 22px !important;
            color: #67615a !important;
            line-height: 1.75 !important
        }

        .um6p-home .empty-projects__actions {
            display: flex !important;
            gap: 12px !important;
            flex-wrap: wrap !important
        }

        .um6p-home .empty-projects__actions .hero-btn,
        .um6p-home .empty-projects__actions .hero-btn-outline {
            margin: 0 !important
        }

        .um6p-home .home-department-card {
            padding: 0 !important;
            overflow: hidden !important;
            display: flex !important;
            flex-direction: column !important
        }

        .um6p-home .home-department-card__media {
            position: relative !important;
            display: block !important;
            height: 190px !important;
            overflow: hidden !important;
            background: #f4e6dc !important
        }

        .um6p-home .home-department-card__media img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            display: block !important;
            transition: transform .7s cubic-bezier(.2, .8, .2, 1), filter .3s ease !important
        }

        .um6p-home .home-department-card:hover .home-department-card__media img {
            transform: scale(1.06) !important;
            filter: saturate(1.05) !important
        }

        .um6p-home .home-department-card__media span {
            position: absolute !important;
            right: 14px !important;
            bottom: 14px !important;
            min-width: 42px !important;
            height: 34px !important;
            padding: 0 12px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 999px !important;
            background: rgba(18, 60, 52, .84) !important;
            color: #fffaf4 !important;
            font-weight: 850 !important
        }

        .um6p-home .home-department-card__body {
            padding: 22px !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 !important
        }

        .um6p-home .home-department-card .badge-pill {
            width: fit-content !important;
            gap: 7px !important;
            margin-bottom: 14px !important
        }

        .um6p-home .home-department-card .project-link {
            margin-top: auto !important
        }

        @media (max-width:1199px) {
            .um6p-home .catalogue-filter-grid {
                grid-template-columns: 1fr 1fr !important
            }

            .um6p-home .filter-field--search,
            .um6p-home .filter-actions {
                grid-column: span 2 !important
            }

            .um6p-home .filter-actions {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important
            }
        }

        @media (max-width:767px) {
            .um6p-home .catalogue-filter-grid {
                grid-template-columns: 1fr !important
            }

            .um6p-home .filter-field--search,
            .um6p-home .filter-actions {
                grid-column: auto !important
            }

            .um6p-home .filter-actions,
            .um6p-home .empty-projects {
                grid-template-columns: 1fr !important
            }
        }
        </style>

        <section class="hero">
            <div class="container">
                <div class="salon-grid">
                    <div class="ai-card salon-hero-card">
                        <div class="salon-kicker"><i class='bx bx-badge-check'></i> Pret pour un salon numerique</div>
                        <h1 class="salon-lead">
                            <span class="lead-line">La plateforme qui</span>
                            <span class="lead-line lead-line--accent">valorise les projets étudiants</span>
                            <span class="lead-line">et facilite la mise en relation.</span>
                        </h1>
                        <p class="section-copy">Cette vitrine presente les projets du departement comme de vraies
                            solutions numeriques : visibles, comparables, mieux expliques et directement reliables a
                            leurs proprietaires.</p>
                        <div class="hero-actions">
                            <a href="#catalogue-projets" class="hero-btn"><i class='bx bx-compass'></i> Explorer les
                                projets</a>
                            <button type="button" class="hero-btn-outline border-0 js-open-home-ai-modal"><i
                                    class='bx bx-bot'></i> Tester l'assistant IA</button>
                        </div>
                        <div class="salon-proof">
                            <div class="salon-proof__item">
                                <strong><?= (int) ($presentationStats['projects'] ?? $projectCount) ?></strong>
                                <span>projets valorises</span>
                            </div>
                            <div class="salon-proof__item">
                                <strong><?= (int) ($presentationStats['owners'] ?? 0) ?></strong>
                                <span>porteurs de projets</span>
                            </div>
                            <div class="salon-proof__item">
                                <strong><?= (int) ($presentationStats['likes'] ?? 0) ?></strong>
                                <span>likes enregistres</span>
                            </div>
                            <div class="salon-proof__item">
                                <strong><?= number_format((float) ($presentationStats['average_rating'] ?? 0), 1) ?>/5</strong>
                                <span>note moyenne</span>
                            </div>
                        </div>
                    </div>
                    <div class="ai-card salon-side">
                        <div class="um6p-campus-frame um6p-hero-carousel">
                            <?php foreach ($heroSlides as $index => $slide): ?>
                            <div class="um6p-hero-slide <?= $index === 0 ? 'is-active' : '' ?>">
                                <img src="<?= htmlspecialchars($slide) ?>" alt="Projet mis en avant <?= $index + 1 ?>">
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="um6p-campus-caption">
                            <small>Vitrine publique</small>
                            <h2><?= htmlspecialchars((string) ($featuredProject['title'] ?? 'Innovation et projets etudiants')) ?></h2>
                            <p>Une experience d'accueil plus institutionnelle, visuelle et orientee salon numerique.</p>
                        </div>
                    </div>
                </div>

                <!-- <div class="salon-highlight">
                    <div class="ai-card">
                        <div class="section-head">
                            <small>Valeur</small>
                            <h2>Ce que la plateforme apporte vraiment</h2>
                        </div>
                        <div class="benefits-grid">
                            <?php foreach ($presentationBenefits as $benefit): ?>
                                <article class="benefit-card">
                                    <i class='<?= htmlspecialchars($benefit['icon']) ?>'></i>
                                    <h3 class="project-title" style="font-size:1.08rem"><?= htmlspecialchars($benefit['title']) ?></h3>
                                    <p class="project-text mb-0"><?= htmlspecialchars($benefit['text']) ?></p>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="ai-card">
                        <div class="section-head">
                            <small>Parcours</small>
                            <h2>Comment se deroule la demonstration</h2>
                        </div>
                        <div class="flow-grid">
                            <?php foreach ($presentationFlow as $flow): ?>
                                <article class="flow-card">
                                    <div class="flow-step"><?= htmlspecialchars($flow['step']) ?></div>
                                    <h3 class="project-title" style="font-size:1.08rem"><?= htmlspecialchars($flow['title']) ?></h3>
                                    <p class="project-text mb-0"><?= htmlspecialchars($flow['text']) ?></p>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div> -->

                <div class="">

                    <div class="top-liked-shell">
                        <div class="section-head">
                            <small>Top Likes</small>
                            <h2>Les 3 projets les plus apprecies</h2>
                            <p class="section-copy">Ce bloc donne tout de suite une lecture forte des projets qui
                                attirent le plus l'attention de la communaute.</p>
                        </div>
                        <div class="top-liked-grid">
                            <?php foreach ($topLikedProjects as $item): ?>
                            <article class="mini-card">
                                <img src="<?= htmlspecialchars($item['image']) ?>"
                                    alt="<?= htmlspecialchars($item['title']) ?>">
                                <div class="mini-card__body">
                                    <h3 class="project-title" style="font-size:1.05rem"><a
                                            href="<?= ROOT ?>/Projets/detail/<?= (int) $item['id'] ?>"><?= htmlspecialchars($item['title']) ?></a>
                                    </h3>
                                    <div class="project-meta mb-2"><span><i
                                                class='bx bx-user-circle'></i><?= htmlspecialchars($item['author']) ?></span>
                                    </div>
                                    <div class="project-stats">
                                        <span class="project-stat project-stat--rating"><i
                                                class='bx bxs-star'></i><?= number_format((float) ($item['average_rating'] ?? 0), 1) ?>/5</span>
                                        <span class="project-stat project-stat--likes"><i
                                                class='bx bxs-heart'></i><?= (int) ($item['likes_count'] ?? 0) ?>
                                            likes</span>
                                        <span class="project-stat project-stat--reviews"><i
                                                class='bx bxs-message-square-detail'></i><?= (int) ($item['reviews_count'] ?? 0) ?>
                                            avis</span>
                                    </div>
                                    <p class="project-text" style="min-height:auto;margin:8px 0 14px">
                                        <?= htmlspecialchars($item['excerpt'] ?? '') ?></p>
                                    <a class="project-link"
                                        href="<?= ROOT ?>/Projets/detail/<?= (int) $item['id'] ?>">Voir le projet <i
                                            class='bx bx-right-arrow-alt'></i></a>
                                </div>
                            </article>
                            <?php endforeach; ?>
                            <?php if (empty($topLikedProjects)): ?><div class="empty-projects" style="padding:28px"><i
                                    class='bx bx-heart'></i>
                                <p class="mb-0">Le top apparaitra ici quand les utilisateurs commenceront a aimer les
                                    projets.</p>
                            </div><?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="ai-card ai-launcher mt-2">
                    <div class="section-head mb-0">
                        <small>Assistant IA</small>
                        <h2>Faites-vous guider en direct</h2>
                        <p class="section-copy">Pendant le salon, l'assistant peut servir de copilote de demonstration
                            pour montrer comment un visiteur est guide vers le bon projet.</p>
                    </div>

                    <div class="hero-actions mt-0">
                        <button type="button" class="hero-btn border-0"
                            data-ai-home-prompt="Je suis visiteur d'un salon numerique et je cherche un projet tres utile, bien note et facile a comprendre."><i
                                class='bx bx-play-circle'></i> Lancer une demo IA</button>
                        <button type="button" class="hero-btn-outline border-0 js-open-home-ai-modal"><i
                                class='bx bx-message-rounded-dots'></i> Ouvrir l'assistant</button>
                        <button type="button" class="hero-btn-outline border-0"
                            data-ai-home-prompt="Je cherche un projet web en PHP utile pour une universite."><i
                                class='bx bx-bulb'></i> Essai rapide</button>
                    </div>
                </div>

            </div>
        </section>

        <section class="container container-two" id="catalogue-projets">
            <div class="ai-card filters">
                <div class="section-head mb-0">
                    <small>Catalogue</small>
                    <h2>Explorez les projets comme dans une galerie de salon</h2>
                </div>
                <form method="get" action="<?= ROOT ?>/Homes/index" id="projectFilterForm">
                    <input type="hidden" name="page" value="<?= $currentPage ?>" id="projectPageInput">
                    <div class="catalogue-filter-grid">
                        <div class="filter-field filter-field--search"><label class="form-label fw-semibold">Recherche</label><input type="text"
                                name="search" class="form-control" id="projectSearchInput"
                                value="<?= htmlspecialchars($projectSearch) ?>"
                                placeholder="Titre, techno, categorie, auteur..."></div>
                        <div class="filter-field"><label class="form-label fw-semibold">Categorie</label><select
                                name="category" class="form-select" id="projectCategorySelect">
                                <option value="">Toutes les categories</option>
                                <?php foreach ($projectCategories as $category): ?><option
                                    value="<?= (int) ($category->id ?? 0) ?>"
                                    <?= ((int) ($selectedCategoryId ?? 0) === (int) ($category->id ?? 0)) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars((string) ($category->nom ?? 'Sans nom')) ?></option>
                                <?php endforeach; ?>
                            </select></div>
                        <div class="filter-field"><label class="form-label fw-semibold">Par page</label><select
                                name="per_page" class="form-select"
                                id="perPageSelect"><?php foreach ([5,10,15,20] as $option): ?><option
                                    value="<?= $option ?>" <?= $perPage === $option ? 'selected' : '' ?>><?= $option ?>
                                    projets</option><?php endforeach; ?></select></div>
                        <div class="filter-actions">
                            <button type="submit" class="hero-btn border-0"><i class='bx bx-search'></i> Rechercher</button>
                            <a href="<?= ROOT ?>/Homes/index"
                                class="hero-btn-outline justify-content-center" id="projectFilterReset"><i class='bx bx-reset'></i> Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <section class="container container-two" style="padding:22px 0 82px">
            <div id="projectResults" class="project-results-shell">
                <?php $this->view('Partials/home-project-results', compact('projects', 'projectSearch', 'projectCount', 'currentPage', 'perPage', 'totalPages')); ?>
            </div>
        </section>

        <section class="container container-two" style="padding-bottom:90px">
            <div class="section-head">
                <small>Departement</small>
                <h2>Annonces, informations, resultats et opportunites</h2>
                <p class="section-copy">Les informations importantes du departement restent visibles sans ecraser
                    l'espace principal reserve a la decouverte des projets.</p>
            </div>
            <div class="news-grid">
                <?php foreach ($homeDepartmentSections as $section): ?>
                    <?php foreach ($section['items'] as $item): ?>
                        <?php
                        $files = $item['files'] ?? [];
                        $imageFiles = array_values(array_filter($files, static fn($file) => home_department_file_is_image($file)));
                        ?>
                        <article class="news-card home-department-card">
                            <?php if (!empty($imageFiles)): ?>
                                <a class="home-department-card__media" href="<?= ROOT ?>/Homes/department_publication_detail/<?= (int) ($item['id'] ?? 0) ?>">
                                    <img src="<?= htmlspecialchars((string) ($imageFiles[0]['url'] ?? '')) ?>" alt="<?= htmlspecialchars((string) ($item['title'] ?? 'Publication')) ?>">
                                    <?php if (count($imageFiles) > 1): ?>
                                        <span>+<?= count($imageFiles) - 1 ?></span>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                            <div class="home-department-card__body">
                                <span class="badge-pill <?= htmlspecialchars($section['class']) ?>"><i class='<?= htmlspecialchars($section['icon']) ?>'></i><?= htmlspecialchars($section['label']) ?></span>
                                <h3 class="publication-title"><?= htmlspecialchars((string) ($item['title'] ?? 'Sans titre')) ?></h3>
                                <div class="news-meta mb-2 text-muted"><span><i class='bx bx-calendar'></i><?= htmlspecialchars((string) ($item['date'] ?? '')) ?></span></div>
                                <p class="publication-text"><?= htmlspecialchars(mb_strimwidth((string) ($item['content'] ?? ''), 0, 130, '...')) ?></p>
                                <a class="project-link" href="<?= ROOT ?>/Homes/department_publication_detail/<?= (int) ($item['id'] ?? 0) ?>">Voir la publication <i class='bx bx-right-arrow-alt'></i></a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </section>

        <div class="ai-modal" id="homeAiModal" aria-hidden="true">
            <div class="ai-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="homeAiModalTitle">
                <div class="ai-modal__head d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="hero-chip mb-2"><i class='bx bx-bot'></i> Assistant IA</div>
                        <h3 id="homeAiModalTitle" class="mb-2">Assistant de recommandation de projets</h3>
                        <p class="section-copy mb-0">Cet assistant utilise Hugging Face si la plateforme est configuree,
                            sinon il bascule automatiquement vers un mode local qui ne demande aucune cle API.</p>
                    </div>
                    <button type="button" class="ai-modal__close" id="closeHomeAiModal"
                        aria-label="Fermer l'assistant"><i class='bx bx-x'></i></button>
                </div>
                <div class="ai-modal__body">
                    <div class="ai-chip-row">
                        <button type="button" class="ai-chip"
                            data-ai-home-prompt="Je cherche un projet web en PHP utile pour une universite.">Projet
                            web PHP</button>
                        <button type="button" class="ai-chip"
                            data-ai-home-prompt="Je veux un projet mobile avec une vraie valeur utilisateur.">Projet
                            mobile utile</button>
                        <button type="button" class="ai-chip"
                            data-ai-home-prompt="Je cherche un projet data ou intelligence artificielle accessible.">Projet
                            data / IA</button>
                    </div>
                    <div class="ai-chat-window" id="homeAiChat">
                        <div class="ai-bubble assistant">Je peux vous guider pour choisir un projet adapte a votre
                            besoin. Decrivez votre objectif, votre niveau ou les technologies que vous preferez.</div>
                    </div>
                    <div class="ai-chip-row ai-chip-row--dynamic" id="homeAiDynamicSuggestions">
                        <button type="button" class="ai-chip"
                            data-ai-home-prompt="Je cherche un projet web utile pour une universite.">Projet web utile</button>
                        <button type="button" class="ai-chip"
                            data-ai-home-prompt="Je veux un projet simple a presenter devant un jury.">Projet pour jury</button>
                        <button type="button" class="ai-chip"
                            data-ai-home-prompt="Je cherche un projet innovant mais realisable.">Projet innovant</button>
                    </div>
                </div>
                <div class="ai-modal__foot">
                    <div class="ai-compose">
                        <textarea id="homeAiInput" class="ai-input"
                            placeholder="Exemple : Je veux un projet de gestion avec base de donnees, utile pour l'ecole et realisable en PHP."></textarea>
                        <button type="button" class="ai-send" id="homeAiSend"><i class='bx bx-send'></i>
                            Envoyer</button>
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

        function initProjectCarousels() {
            if (typeof $.fn.slick !== 'function') return;
            $results.find('.js-project-carousel').each(function() {
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
            const items = document.querySelectorAll('.mini-card, .project-card-modern, .news-card, .ai-launcher, .filters');
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
                        const syncedQuery = $form.serialize();
                        updateUrl(syncedQuery);
                        initProjectCarousels();
                        const block = document.getElementById('projectResults');
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
            $pageInput.val(1);
            loadProjects(1);
        });
        $results.on('click', '.page-nav[data-page]', function() {
            const page = parseInt($(this).data('page'), 10);
            if (!page || $(this).is(':disabled')) return;
            loadProjects(page);
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

        initProjectCarousels();
        initRevealOnScroll();
    })(jQuery);
    </script>
</body>

</html>
