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
?>

<body>
    <?php $this->view('Partials/global-shell'); ?>
    <?php $this->view('Partials/mobile-menu'); ?>
    <?php $this->view('Partials/header'); ?>
    <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

    <main class="change-gradient ai-home">
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

        <section class="hero">
            <div class="container">
                <div class="salon-grid">
                    <div class="ai-card salon-hero-card">
                        <div class="salon-kicker"><i class='bx bx-badge-check'></i> Pret pour un salon numerique</div>
                        <h1 class="salon-lead">La plateforme qui <span>valorise</span> les projets etudiants, guide les
                            visiteurs et facilite la mise en relation.</h1>
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
                        <div class="section-head mb-0">
                            <small style="color:#5eead4">Demonstration</small>
                            <h2 style="color:#fff">Pourquoi cette plateforme marque en presentation</h2>
                            <p class="section-copy">Elle combine visibilite, tri intelligent, interaction humaine et
                                lecture immediate de la valeur des projets.</p>
                        </div>
                        <div class="salon-checklist">
                            <div class="salon-check"><i class='bx bx-check-circle'></i>
                                <div><strong>Lecture immediate</strong>
                                    <div class="section-copy mb-0">Les tops, les likes, les avis et les fiches
                                        detaillees donnent tout de suite de la credibilite.</div>
                                </div>
                            </div>
                            <div class="salon-check"><i class='bx bx-check-circle'></i>
                                <div><strong>Orientation intelligente</strong>
                                    <div class="section-copy mb-0">L'assistant IA guide le visiteur vers les projets les
                                        plus utiles selon son besoin.</div>
                                </div>
                            </div>
                            <div class="salon-check"><i class='bx bx-check-circle'></i>
                                <div><strong>Mise en relation directe</strong>
                                    <div class="section-copy mb-0">Le visiteur peut contacter le proprietaire et passer
                                        de la curiosite a l'echange concret.</div>
                                </div>
                            </div>
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
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-4"><label class="form-label fw-semibold">Recherche</label><input type="text"
                                name="search" class="form-control" id="projectSearchInput"
                                value="<?= htmlspecialchars($projectSearch) ?>"
                                placeholder="Titre, techno, categorie, auteur..."></div>
                        <div class="col-lg-3"><label class="form-label fw-semibold">Categorie</label><select
                                name="category" class="form-select" id="projectCategorySelect">
                                <option value="">Toutes les categories</option>
                                <?php foreach ($projectCategories as $category): ?><option
                                    value="<?= (int) ($category->id ?? 0) ?>"
                                    <?= ((int) ($selectedCategoryId ?? 0) === (int) ($category->id ?? 0)) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars((string) ($category->nom ?? 'Sans nom')) ?></option>
                                <?php endforeach; ?>
                            </select></div>
                        <div class="col-lg-3"><label class="form-label fw-semibold">Par page</label><select
                                name="per_page" class="form-select"
                                id="perPageSelect"><?php foreach ([5,10,15,20] as $option): ?><option
                                    value="<?= $option ?>" <?= $perPage === $option ? 'selected' : '' ?>><?= $option ?>
                                    projets</option><?php endforeach; ?></select></div>
                        <div class="col-md-6 col-lg-1"><button type="submit" class="hero-btn w-100 border-0">OK</button>
                        </div>
                        <div class="col-md-6 col-lg-1"><a href="<?= ROOT ?>/Homes/index"
                                class="hero-btn-outline w-100 justify-content-center" id="projectFilterReset">Reset</a>
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
                <?php foreach ($departmentAnnouncements as $item): ?><article class="news-card"><span
                        class="badge-pill badge-ann">Annonce</span>
                    <h3 class="publication-title"><?= htmlspecialchars((string) ($item['title'] ?? 'Sans titre')) ?>
                    </h3>
                    <div class="news-meta mb-2 text-muted"><span><i
                                class='bx bx-calendar'></i><?= htmlspecialchars((string) ($item['date'] ?? '')) ?></span>
                    </div>
                    <p class="publication-text">
                        <?= htmlspecialchars(mb_strimwidth((string) ($item['content'] ?? ''), 0, 120, '...')) ?></p>
                </article><?php endforeach; ?>
                <?php foreach ($departmentInformations as $item): ?><article class="news-card"><span
                        class="badge-pill badge-info">Information</span>
                    <h3 class="publication-title"><?= htmlspecialchars((string) ($item['title'] ?? 'Sans titre')) ?>
                    </h3>
                    <div class="news-meta mb-2 text-muted"><span><i
                                class='bx bx-calendar'></i><?= htmlspecialchars((string) ($item['date'] ?? '')) ?></span>
                    </div>
                    <p class="publication-text">
                        <?= htmlspecialchars(mb_strimwidth((string) ($item['content'] ?? ''), 0, 120, '...')) ?></p>
                </article><?php endforeach; ?>
                <?php foreach ($departmentResults as $item): ?><article class="news-card"><span
                        class="badge-pill badge-res">Resultat</span>
                    <h3 class="publication-title"><?= htmlspecialchars((string) ($item['title'] ?? 'Sans titre')) ?>
                    </h3>
                    <div class="news-meta mb-2 text-muted"><span><i
                                class='bx bx-calendar'></i><?= htmlspecialchars((string) ($item['date'] ?? '')) ?></span>
                    </div>
                    <p class="publication-text">
                        <?= htmlspecialchars(mb_strimwidth((string) ($item['content'] ?? ''), 0, 120, '...')) ?></p>
                </article><?php endforeach; ?>
                <?php foreach ($departmentOpportunities as $item): ?><article class="news-card"><span
                        class="badge-pill badge-op">Opportunite</span>
                    <h3 class="publication-title"><?= htmlspecialchars((string) ($item['title'] ?? 'Sans titre')) ?>
                    </h3>
                    <div class="news-meta mb-2 text-muted"><span><i
                                class='bx bx-calendar'></i><?= htmlspecialchars((string) ($item['date'] ?? '')) ?></span>
                    </div>
                    <p class="publication-text">
                        <?= htmlspecialchars(mb_strimwidth((string) ($item['content'] ?? ''), 0, 120, '...')) ?></p>
                </article><?php endforeach; ?>
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
                    speed: 450,
                    prevArrow: '<button type="button" class="slick-prev" aria-label="Image precedente"><i class="bx bx-chevron-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next" aria-label="Image suivante"><i class="bx bx-chevron-right"></i></button>'
                });
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
    })(jQuery);
    </script>
</body>

</html>
