<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Projets']); ?>
<body class="public-site public-list">
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php $this->view('Partials/header'); ?>
<?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

<main class="change-gradient">
    <style>
        /* Projects Page Modernization & Animations */
        
        /* Banner animations */
        .public-site .breadcrumb {
            background: linear-gradient(135deg, #123c34, #1c5248) !important;
            box-shadow: 0 10px 30px -10px rgba(18, 60, 52, 0.5) !important;
            border-radius: 0 0 30px 30px !important;
            margin-bottom: 24px !important;
            padding: 80px 0 !important;
            position: relative;
            overflow: hidden;
        }

        .public-site .breadcrumb::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at 80% 20%, rgba(189, 74, 31, 0.15) 0%, transparent 40%);
            animation: pulse-glow 6s infinite alternate;
        }

        @keyframes pulse-glow {
            from { opacity: 0.6; transform: scale(1); }
            to { opacity: 1; transform: scale(1.1); }
        }

        .public-site .breadcrumb-two__title {
            animation: title-rise 0.9s cubic-bezier(0.16, 1, 0.3, 1) both;
            font-family: "Sora", sans-serif;
            font-size: 2.8rem;
        }

        @keyframes title-rise {
            0% { opacity: 0; transform: translateY(30px); filter: blur(4px); }
            100% { opacity: 1; transform: translateY(0); filter: blur(0); }
        }

        /* Product Card Animations */
        .public-site .product-card {
            border-radius: 20px !important;
            border: 1px solid rgba(23, 21, 18, 0.08) !important;
            box-shadow: 0 15px 35px -20px rgba(23, 21, 18, 0.08) !important;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(8px) !important;
            opacity: 0;
            transform: translateY(20px);
            animation: card-reveal 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes card-reveal {
            to { opacity: 1; transform: translateY(0); }
        }

        /* Stagger the delay for cards */
        <?php for($i=1; $i<=20; $i++): ?>
        .public-site .col-xl-4:nth-child(<?= $i ?>) .product-card {
            animation-delay: <?= 0.1 * $i ?>s;
        }
        <?php endfor; ?>

        .public-site .product-card:hover {
            transform: translateY(-8px) scale(1.02) !important;
            box-shadow: 0 25px 45px -20px rgba(189, 74, 31, 0.15) !important;
            border-color: rgba(189, 74, 31, 0.3) !important;
        }

        .public-site .product-card__thumb img {
            border-radius: 20px 20px 0 0 !important;
            transition: transform 0.6s ease;
        }

        .public-site .product-card:hover .product-card__thumb img {
            transform: scale(1.05);
        }

        .public-site .product-card__content {
            padding: 24px !important;
        }

        .public-site .product-card__title a {
            color: #123c34;
            font-family: "Sora", sans-serif;
            transition: color 0.3s ease;
        }

        .public-site .product-card:hover .product-card__title a {
            color: #bd4a1f;
        }



        /* ============================================
           MINIMALIST HERO & MARQUEE ANIMATIONS
        ============================================ */
        .project-hero-minimal {
            padding: 100px 0 60px;
            background: #fffaf4; /* Clean background instead of big dark box */
            text-align: center;
            overflow: hidden;
            position: relative;
        }

        .project-hero-minimal .kicker {
            display: inline-block;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: #bd4a1f;
            font-weight: 700;
            margin-bottom: 20px;
            opacity: 0;
            animation: fade-up-kicker 1s 0.2s forwards;
        }

        /* Typewriter Title Animation */
        .project-hero-minimal .title-wrap {
            display: inline-block;
            overflow: hidden;
        }

        .project-hero-minimal .hero-title {
            font-family: "Sora", sans-serif;
            font-size: clamp(2rem, 5vw, 4.5rem);
            font-weight: 800;
            color: #123c34;
            line-height: 1.1;
            margin: 0;
            white-space: nowrap; /* Kept for typewriter effect */
            overflow: hidden;
            border-right: 3px solid #bd4a1f;
            width: 0;
            animation: 
                typing 2s steps(22, end) 0.5s forwards,
                blink-caret 0.75s step-end infinite;
        }

        @media (max-width: 768px) {
            .project-hero-minimal .hero-title {
                white-space: normal; /* Allow wrapping on small screens */
                border-right: none;
                animation: fade-up-title 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                width: auto;
                opacity: 0;
                transform: translateY(20px);
            }
        }

        @keyframes fade-up-title {
            to { opacity: 1; transform: translateY(0); }
        }

        .project-hero-minimal .subtitle {
            font-size: 1.1rem;
            color: #67615a;
            max-width: 600px;
            margin: 20px auto 0;
            opacity: 0;
            animation: fade-up-kicker 1s 1.5s forwards;
            line-height: 1.6;
        }

        @keyframes fade-up-kicker {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent; }
            50% { border-color: #bd4a1f; }
        }

        /* Infinite Image Marquee (Défilement) */
        .marquee-wrapper {
            margin-top: 50px;
            width: 100%;
            overflow: hidden;
            position: relative;
            display: flex;
            opacity: 0;
            animation: fade-in-marquee 1.5s 1.8s forwards;
        }

        .marquee-wrapper::before,
        .marquee-wrapper::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 150px;
            z-index: 2;
            pointer-events: none;
        }
        
        .marquee-wrapper::before {
            left: 0;
            background: linear-gradient(to right, #fffaf4, transparent);
        }

        .marquee-wrapper::after {
            right: 0;
            background: linear-gradient(to left, #fffaf4, transparent);
        }

        .marquee-track {
            display: flex;
            gap: 20px;
            padding: 0 10px;
            animation: scroll-marquee 25s linear infinite;
            width: max-content;
        }

        .marquee-track img {
            height: 120px;
            width: 200px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 10px 20px -10px rgba(0,0,0,0.15);
            filter: grayscale(80%) opacity(0.8);
            transition: all 0.4s ease;
        }

        .marquee-track img:hover {
            filter: grayscale(0%) opacity(1);
            transform: scale(1.05);
        }

        @keyframes fade-in-marquee {
            to { opacity: 1; }
        }

        @keyframes scroll-marquee {
            from { transform: translateX(0); }
            to { transform: translateX(calc(-33.33% - 6.66px)); }
        }

    </style>

    <section class="project-hero-minimal">
        <div class="container-fluid container-two">
            <span class="kicker">Nos Réalisations</span>
            <div class="title-wrap">
                <h1 class="hero-title">Catalogue des Projets</h1>
            </div>
            <p class="subtitle">
                Découvrez les solutions innovantes, les applications et les projets créatifs réalisés par nos talents au sein du département.
            </p>
        </div>
        
        <!-- Scrolling Images Marquee (Dynamic) -->
        <div class="marquee-wrapper">
            <div class="marquee-track">
                <?php 
                // We'll extract image URLs from $projects, filter empty ones, and duplicate them to ensure a smooth loop.
                $marqueeImages = [];
                if (!empty($projects)) {
                    foreach ($projects as $proj) {
                        if (!empty($proj['image'])) {
                            $marqueeImages[] = $proj['image'];
                        }
                    }
                }
                // If we don't have enough images, add some defaults
                if (count($marqueeImages) < 4) {
                    $marqueeImages = array_merge($marqueeImages, [
                        ROOT . '/assets/images/thumbs/product-img1.png',
                        ROOT . '/assets/images/thumbs/product-img2.png',
                        ROOT . '/assets/images/thumbs/product-img3.png',
                        ROOT . '/assets/images/thumbs/product-img4.png',
                        ROOT . '/assets/images/thumbs/product-img5.png',
                    ]);
                }
                ?>
                <!-- Group 1 -->
                <?php foreach ($marqueeImages as $img): ?>
                    <img src="<?= htmlspecialchars($img) ?>" alt="Project Thumbnail" onerror="this.src='<?= ROOT ?>/assets/images/thumbs/product-img1.png'">
                <?php endforeach; ?>
                <!-- Group 2 (Duplicate for infinite scroll loop) -->
                <?php foreach ($marqueeImages as $img): ?>
                    <img src="<?= htmlspecialchars($img) ?>" alt="Project Thumbnail" onerror="this.src='<?= ROOT ?>/assets/images/thumbs/product-img1.png'">
                <?php endforeach; ?>
                <!-- Group 3 (Extra Duplicate for safety on ultra-wide screens) -->
                <?php foreach ($marqueeImages as $img): ?>
                    <img src="<?= htmlspecialchars($img) ?>" alt="Project Thumbnail" onerror="this.src='<?= ROOT ?>/assets/images/thumbs/product-img1.png'">
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="all-product padding-y-120" data-dynamic-block="projects-list">
        <div class="container-fluid container-two">
            <div class="row gy-4">
                <?php if (!empty($projects)): ?>
                    <?php foreach ($projects as $project): ?>
                        <div class="col-xl-4 col-lg-4 col-sm-6">
                            <div class="product-card">
                                <div class="product-card__thumb d-flex">
                                    <a href="<?= ROOT ?>/Projets/detail/<?= (int) ($project['id'] ?? 0) ?>" class="link w-100">
                                        <img src="<?= htmlspecialchars($project['image'] ?? (ROOT . '/assets/images/thumbs/product-img1.png')) ?>" alt="" class="cover-img">
                                    </a>
                                </div>
                                <div class="product-card__content">
                                    <h6 class="product-card__title"><a href="<?= ROOT ?>/Projets/detail/<?= (int) ($project['id'] ?? 0) ?>" class="link"><?= htmlspecialchars($project['title'] ?? 'Projet') ?></a></h6>
                                    <div class="product-card__info flx-between gap-2 mb-2">
                                        <span class="product-card__author">par <?= htmlspecialchars($project['author'] ?? '-') ?></span>
                                        <h6 class="product-card__price mb-0"><?= htmlspecialchars($project['price'] ?? '-') ?></h6>
                                    </div>
                                    <span class="product-card__sales font-14 mb-2"><?= htmlspecialchars($project['category'] ?? '-') ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info mb-0">Aucun projet disponible pour le moment. Cette section sera alimentée par la base de données.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php $this->view('Partials/footer'); ?>
</main>

<?php $this->view('Partials/scripts'); ?>
</body>
</html>
