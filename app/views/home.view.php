<?php 
// Ajout du CDN Boxicons dans l'en-tête
$this->view('Partials/head', [
    'pageTitle' => $pageTitle ?? 'Accueil',
    'additionalHead' => '<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">'
]); 
?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php $this->view('Partials/header'); ?>
<?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

<main class="change-gradient">
    <?php
    if (!function_exists('splitDepartmentFiles')) {
        function splitDepartmentFiles(array $files): array
        {
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
            $imageFiles = [];
            $documentFiles = [];

            foreach ($files as $file) {
                $mimeType = strtolower((string)($file['type'] ?? ''));
                $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
                $isImage = str_contains($mimeType, 'image') || in_array($extension, $imageExtensions, true);

                if ($isImage) {
                    $imageFiles[] = $file;
                } else {
                    $documentFiles[] = $file;
                }
            }

            return [$imageFiles, $documentFiles];
        }
    }
    ?>
    <!-- Bannière améliorée -->
    <section class="banner section-bg z-index-1 position-relative overflow-hidden">
        <div class="banner-particles"></div>
        <div class="container container-two">
            <div class="row align-items-center min-vh-50 py-5">
                <div class="col-lg-6">
                    <div class="banner-content">
                        <span class="badge bg-primary-soft text-primary mb-3 px-3 py-2 rounded-pill">
                            <i class='bx bxs-graduation me-1'></i>
                            Génie Informatique - Promo 21
                        </span>
                        <h1 class="banner-content__title display-4 fw-bold mb-3">
                            Plateforme GI <span class="text-primary">Promo 21</span>
                        </h1>
                        <p class="banner-content__desc font-18 text-secondary mb-4">
                            Interface intégrée pour la gestion des projets, annonces et résultats du département
                        </p>
                        <div class="d-flex gap-3 flex-wrap mt-4">
                            <a href="<?= ROOT ?>/Homes/projects" class="btn btn-primary btn-lg rounded-pill px-4">
                                <i class='bx bx-folder-open me-2'></i>
                                Voir les projets
                            </a>
                            <a href="<?= ROOT ?>/Homes/register" class="btn btn-primary btn-lg rounded-pill px-4">
                                <i class='bx bx-user-plus me-2'></i>
                                Créer un compte
                            </a>
                        </div>
                        <div class="mt-5 d-flex gap-4">
                            <div class="stat-item">
                                <div class="h3 fw-bold text-primary mb-0">50+</div>
                                <small class="text-secondary">Projets réalisés</small>
                            </div>
                            <div class="stat-item">
                                <div class="h3 fw-bold text-primary mb-0">100+</div>
                                <small class="text-secondary">Étudiants</small>
                            </div>
                            <div class="stat-item">
                                <div class="h3 fw-bold text-primary mb-0">30+</div>
                                <small class="text-secondary">Publications</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="banner-thumb position-relative">
                        <img src="<?= ROOT ?>/assets/images/thumbs/banner-img.png" alt="Banner" class="img-fluid floating-animation">
                        <div class="experience-badge">
                            <span class="text">Depuis 2021</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Style personnalisé -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --soft-bg: #f8faff;
        }

        .badge.bg-primary-soft {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            font-weight: 500;
        }

        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .experience-badge {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: white;
            padding: 15px 25px;
            border-radius: 50px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            font-weight: 600;
            color: #667eea;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .section-header h3 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .section-header p {
            color: #64748b;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .section-header .header-icon {
            width: 60px;
            height: 60px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 30px;
        }

        /* Cartes projet améliorées */
        .project-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
        }

        .project-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px -15px rgba(102, 126, 234, 0.3);
        }

        .project-card__thumb {
            position: relative;
            padding-top: 75%;
            overflow: hidden;
        }

        .project-card__thumb img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .project-card:hover .project-card__thumb img {
            transform: scale(1.1);
        }

        .project-card__category {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255,255,255,0.95);
            padding: 0.5rem 1rem;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #667eea;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .project-card__content {
            padding: 1.5rem;
        }

        .project-card__title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #1e293b;
        }

        .project-card__title a {
            text-decoration: none;
            color: inherit;
        }

        .project-card__meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            color: #64748b;
            font-size: 0.9rem;
        }

        .project-card__meta i {
            color: #667eea;
        }

        /* Cartes publication améliorées */
        .publication-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px -5px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #eef2f6;
            position: relative;
            overflow: hidden;
        }

        .publication-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .publication-card:hover::before {
            transform: scaleX(1);
        }

        .publication-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px -10px rgba(102, 126, 234, 0.2);
        }

        .publication-card__type {
            display: inline-block;
            padding: 0.35rem 1rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .type-information { background: rgba(102, 126, 234, 0.1); color: #667eea; }
        .type-annonce { background: rgba(16, 185, 129, 0.1); color: #10b981; }
        .type-evenement { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
        .type-resultat { background: rgba(217, 70, 239, 0.1); color: #d946ef; }
        .type-opportunite { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }

        .publication-card__title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #1e293b;
            line-height: 1.4;
        }

        .publication-card__date {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .publication-card__content {
            color: #475569;
            margin-bottom: 1.25rem;
            line-height: 1.6;
        }

        .publication-card__files {
            background: #f8fafc;
            border-radius: 15px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .publication-card__gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .gallery-item {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 12px 30px -18px rgba(15, 23, 42, 0.45);
            background: #fff;
            padding: 0;
            appearance: none;
            border: 1px solid #e2e8f0;
            cursor: zoom-in;
        }

        .gallery-item img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
        }

        .gallery-item:focus-visible {
            outline: 3px solid #6366f1;
            outline-offset: 3px;
        }

        .document-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .document-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.9rem 1.1rem;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            background: #fff;
            text-decoration: none;
            color: #0f172a;
            box-shadow: 0 10px 25px -18px rgba(15, 23, 42, 0.4);
            transition: all 0.3s ease;
        }

        .document-card:hover {
            background: #0f172a;
            color: #fff;
            border-color: transparent;
            transform: translateY(-2px);
        }

        .document-card i {
            font-size: 1.4rem;
            color: #667eea;
        }

        .document-card:hover i {
            color: #fff;
        }

        .document-card small {
            display: block;
            color: #94a3b8;
        }

        .document-card:hover small {
            color: rgba(255, 255, 255, 0.75);
        }

        .image-lightbox {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.85);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            z-index: 9999;
        }

        .image-lightbox__content {
            max-width: min(90vw, 960px);
            width: 100%;
            background: #0f172a;
            border-radius: 24px;
            padding: 1.5rem;
            box-shadow: 0 30px 80px -40px rgba(15, 23, 42, 0.8);
        }

        .image-lightbox__img {
            width: 100%;
            max-height: 70vh;
            object-fit: contain;
            border-radius: 18px;
            background: #020617;
        }

        .image-lightbox__caption {
            margin-top: 1rem;
            color: #f8fafc;
            text-align: center;
        }

        .image-lightbox__close {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: rgba(15, 23, 42, 0.6);
            border: none;
            color: #fff;
            font-size: 1.5rem;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            cursor: pointer;
        }

        .image-lightbox__close:hover {
            background: #6366f1;
        }

        .file-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 30px;
            color: #1e293b;
            text-decoration: none;
            font-size: 0.9rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .file-link:hover {
            background: #667eea;
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
        }

        .file-link i {
            font-size: 1.1rem;
        }

        /* État vide amélioré */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: #f8fafc;
            border-radius: 30px;
            border: 2px dashed #cbd5e1;
        }

        .empty-state i {
            font-size: 5rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .empty-state h5 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        .btn-gradient {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-outline-gradient {
            border: 2px solid #667eea;
            color: #667eea;
            background: transparent;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-outline-gradient:hover {
            background: var(--primary-gradient);
            border-color: transparent;
            color: white;
            transform: translateY(-2px);
        }

        body.lightbox-open {
            overflow: hidden;
        }
    </style>

    <!-- Projets en avant -->
    <section class="arrival-product padding-y-120 section-bg position-relative z-index-1">
        <div class="container container-two">
            <div class="section-header">
                <div class="header-icon">
                    <i class='bx bx-rocket'></i>
                </div>
                <h3>Projets en avant</h3>
                <p>Découvrez les projets exceptionnels réalisés par nos étudiants</p>
            </div>

            <div class="row g-4">
                <?php if (!empty($projects)): ?>
                    <?php foreach (array_slice($projects, 0, 3) as $project): ?>
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="project-card">
                                <div class="project-card__thumb">
                                    <img src="<?= htmlspecialchars($project['image'] ?? (ROOT . '/assets/images/thumbs/product-img1.png')) ?>" alt="<?= htmlspecialchars($project['title'] ?? 'Projet') ?>">
                                    <span class="project-card__category">
                                        <i class='bx bx-folder me-1'></i>
                                        <?= htmlspecialchars($project['category'] ?? 'Général') ?>
                                    </span>
                                </div>
                                <div class="project-card__content">
                                    <h5 class="project-card__title">
                                        <a href="<?= ROOT ?>/Homes/project/<?= (int) ($project['id'] ?? 0) ?>">
                                            <?= htmlspecialchars($project['title'] ?? 'Projet sans titre') ?>
                                        </a>
                                    </h5>
                                    <div class="project-card__meta">
                                        <span>
                                            <i class='bx bx-user-circle'></i>
                                            <?= htmlspecialchars($project['author'] ?? 'Anonyme') ?>
                                        </span>
                                        <span>
                                            <i class='bx bx-calendar'></i>
                                            <?= htmlspecialchars($project['date'] ?? date('Y')) ?>
                                        </span>
                                    </div>
                                    <p class="text-secondary mb-3">
                                        <?= htmlspecialchars(substr($project['description'] ?? 'Aucune description disponible', 0, 100)) ?>...
                                    </p>
                                    <a href="<?= ROOT ?>/Homes/project/<?= (int) ($project['id'] ?? 0) ?>" class="btn-outline-gradient w-100 text-center">
                                        <i class='bx bx-show'></i>
                                        Voir le projet
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if (count($projects) > 3): ?>
                    <div class="col-12 text-center mt-4">
                        <a href="<?= ROOT ?>/Homes/projects" class="btn-gradient">
                            <i class='bx bx-grid-alt'></i>
                            Voir tous les projets
                        </a>
                    </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="col-12">
                        <div class="empty-state">
                            <i class='bx bx-folder-open'></i>
                            <h5>Aucun projet pour le moment</h5>
                            <p>Soyez le premier à partager votre projet avec la communauté !</p>
                            <a href="<?= ROOT ?>/Homes/projects/add" class="btn-gradient">
                                <i class='bx bx-plus-circle'></i>
                                Ajouter un projet
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php if (!empty($departmentAnnouncements)): ?>
    <!-- Annonces du département -->
    <section class="padding-y-80 bg-light">
        <div class="container container-two">
            <div class="section-header">
                <div class="header-icon">
                    <i class='bx bx-megaphone'></i>
                </div>
                <h3>Annonces du département</h3>
                <p>Restez informé des dernières nouvelles du département GI</p>
            </div>

            <div class="row g-4">
                <?php foreach ($departmentAnnouncements as $item): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="publication-card">
                            <span class="publication-card__type type-annonce">
                                <i class='bx bx-bell me-1'></i>
                                Annonce
                            </span>
                            <h5 class="publication-card__title"><?= htmlspecialchars($item['title'] ?? 'Sans titre') ?></h5>
                            <div class="publication-card__date">
                                <i class='bx bx-calendar'></i>
                                <?= htmlspecialchars($item['date'] ?? date('d/m/Y')) ?>
                            </div>
                            <div class="publication-card__content">
                                <?= nl2br(htmlspecialchars($item['content'] ?? '')) ?>
                            </div>
                            <?php
                                [$imageFiles, $documentFiles] = splitDepartmentFiles($item['files'] ?? []);
                            ?>
                            <?php if (!empty($imageFiles)): ?>
                                <div class="publication-card__gallery">
                                    <?php foreach ($imageFiles as $file): ?>
                                        <button type="button" class="gallery-item" data-image="<?= htmlspecialchars($file['url'] ?? '#') ?>" data-caption="<?= htmlspecialchars($file['name'] ?? 'Affiche') ?>">
                                            <img src="<?= htmlspecialchars($file['url'] ?? '#') ?>" alt="<?= htmlspecialchars($file['name'] ?? 'Affiche') ?>">
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($documentFiles)): ?>
                                <div class="publication-card__files">
                                    <small class="text-muted d-block mb-2">
                                        <i class='bx bx-paperclip'></i>
                                        <?= count($documentFiles) ?> document(s)
                                    </small>
                                    <div class="document-grid">
                                        <?php foreach ($documentFiles as $file): ?>
                                            <a class="document-card" href="<?= htmlspecialchars($file['url'] ?? '#') ?>" download>
                                                <i class='bx bx-file-blank'></i>
                                                <div>
                                                    <strong><?= htmlspecialchars(substr($file['name'] ?? 'Document', 0, 40)) ?></strong>
                                                    <small>Télécharger</small>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <a href="#" class="text-primary text-decoration-none mt-3 d-inline-block">
                                Lire la suite <i class='bx bx-right-arrow-alt'></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($departmentInformations)): ?>
    <!-- Informations récentes -->
    <section class="padding-y-80">
        <div class="container container-two">
            <div class="section-header">
                <div class="header-icon">
                    <i class='bx bx-info-circle'></i>
                </div>
                <h3>Informations récentes</h3>
                <p>Toutes les informations importantes pour les étudiants</p>
            </div>

            <div class="row g-4">
                <?php foreach ($departmentInformations as $item): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="publication-card">
                            <span class="publication-card__type type-information">
                                <i class='bx bx-info-circle me-1'></i>
                                Information
                            </span>
                            <h5 class="publication-card__title"><?= htmlspecialchars($item['title'] ?? 'Sans titre') ?></h5>
                            <div class="publication-card__date">
                                <i class='bx bx-calendar'></i>
                                <?= htmlspecialchars($item['date'] ?? date('d/m/Y')) ?>
                            </div>
                            <div class="publication-card__content">
                                <?= nl2br(htmlspecialchars($item['content'] ?? '')) ?>
                            </div>
                                <?php
                                    [$imageFiles, $documentFiles] = splitDepartmentFiles($item['files'] ?? []);
                                ?>
                                <?php if (!empty($imageFiles)): ?>
                                    <div class="publication-card__gallery">
                                        <?php foreach ($imageFiles as $file): ?>
                                            <button type="button" class="gallery-item" data-image="<?= htmlspecialchars($file['url'] ?? '#') ?>" data-caption="<?= htmlspecialchars($file['name'] ?? 'Affiche') ?>">
                                                <img src="<?= htmlspecialchars($file['url'] ?? '#') ?>" alt="<?= htmlspecialchars($file['name'] ?? 'Affiche') ?>">
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($documentFiles)): ?>
                                    <div class="publication-card__files">
                                        <small class="text-muted d-block mb-2">
                                            <i class='bx bx-paperclip'></i>
                                            <?= count($documentFiles) ?> document(s)
                                        </small>
                                        <div class="document-grid">
                                            <?php foreach ($documentFiles as $file): ?>
                                                <a class="document-card" href="<?= htmlspecialchars($file['url'] ?? '#') ?>" download>
                                                    <i class='bx bx-file-blank'></i>
                                                    <div>
                                                        <strong><?= htmlspecialchars(substr($file['name'] ?? 'Document', 0, 40)) ?></strong>
                                                        <small>Télécharger</small>
                                                    </div>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Résultats récents -->
    <section class="padding-y-80 bg-light">
        <div class="container container-two">
            <div class="section-header">
                <div class="header-icon">
                    <i class='bx bx-trophy'></i>
                </div>
                <h3>Résultats récents</h3>
                <p>Consultez les derniers résultats publiés</p>
            </div>

            <div class="row g-4">
                <?php if (!empty($departmentResults)): ?>
                    <?php foreach ($departmentResults as $item): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="publication-card">
                                <span class="publication-card__type type-resultat">
                                    <i class='bx bx-award me-1'></i>
                                    Résultat
                                </span>
                                <h5 class="publication-card__title"><?= htmlspecialchars($item['title'] ?? 'Sans titre') ?></h5>
                                <div class="publication-card__date">
                                    <i class='bx bx-calendar'></i>
                                    <?= htmlspecialchars($item['date'] ?? date('d/m/Y')) ?>
                                </div>
                                <p class="publication-card__content">
                                    <?= htmlspecialchars(substr($item['content'] ?? '', 0, 150)) ?>...
                                </p>
                                <?php
                                    [$imageFiles, $documentFiles] = splitDepartmentFiles($item['files'] ?? []);
                                ?>
                                <?php if (!empty($imageFiles)): ?>
                                    <div class="publication-card__gallery">
                                        <?php foreach ($imageFiles as $file): ?>
                                            <button type="button" class="gallery-item" data-image="<?= htmlspecialchars($file['url'] ?? '#') ?>" data-caption="<?= htmlspecialchars($file['name'] ?? 'Affiche') ?>">
                                                <img src="<?= htmlspecialchars($file['url'] ?? '#') ?>" alt="<?= htmlspecialchars($file['name'] ?? 'Affiche') ?>">
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($documentFiles)): ?>
                                    <div class="publication-card__files">
                                        <small class="text-muted d-block mb-2">
                                            <i class='bx bx-paperclip'></i>
                                            <?= count($documentFiles) ?> document(s)
                                        </small>
                                        <div class="document-grid">
                                            <?php foreach ($documentFiles as $file): ?>
                                                <a class="document-card" href="<?= htmlspecialchars($file['url'] ?? '#') ?>" download>
                                                    <i class='bx bx-file-blank'></i>
                                                    <div>
                                                        <strong><?= htmlspecialchars(substr($file['name'] ?? 'Document', 0, 40)) ?></strong>
                                                        <small>Télécharger</small>
                                                    </div>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <a href="#" class="btn-outline-gradient w-100 text-center mt-3">
                                    <i class='bx bx-download'></i>
                                    Télécharger
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="empty-state">
                            <i class='bx bx-file-find'></i>
                            <h5>Aucun résultat disponible</h5>
                            <p>Les résultats seront publiés dès leur disponibilité</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php if (!empty($departmentOpportunities)): ?>
    <!-- Opportunités & stages -->
    <section class="padding-y-80">
        <div class="container container-two">
            <div class="section-header">
                <div class="header-icon">
                    <i class='bx bx-briefcase'></i>
                </div>
                <h3>Opportunités & stages</h3>
                <p>Postes, concours et appels à candidatures partagés par le DER</p>
            </div>

            <div class="row g-4">
                <?php foreach ($departmentOpportunities as $item): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="publication-card">
                            <span class="publication-card__type type-opportunite">
                                <i class='bx bx-briefcase-alt me-1'></i>
                                Opportunité
                            </span>
                            <h5 class="publication-card__title"><?= htmlspecialchars($item['title'] ?? 'Sans titre') ?></h5>
                            <div class="publication-card__date">
                                <i class='bx bx-calendar'></i>
                                <?= htmlspecialchars($item['date'] ?? date('d/m/Y')) ?>
                            </div>
                            <div class="publication-card__content">
                                <?= nl2br(htmlspecialchars($item['content'] ?? '')) ?>
                            </div>
                            <?php
                                [$imageFiles, $documentFiles] = splitDepartmentFiles($item['files'] ?? []);
                            ?>
                            <?php if (!empty($imageFiles)): ?>
                                <div class="publication-card__gallery">
                                    <?php foreach ($imageFiles as $file): ?>
                                        <button type="button" class="gallery-item" data-image="<?= htmlspecialchars($file['url'] ?? '#') ?>" data-caption="<?= htmlspecialchars($file['name'] ?? 'Affiche') ?>">
                                            <img src="<?= htmlspecialchars($file['url'] ?? '#') ?>" alt="<?= htmlspecialchars($file['name'] ?? 'Affiche') ?>">
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($documentFiles)): ?>
                                <div class="publication-card__files">
                                    <small class="text-muted d-block mb-2">
                                        <i class='bx bx-paperclip'></i>
                                        <?= count($documentFiles) ?> document(s)
                                    </small>
                                    <div class="document-grid">
                                        <?php foreach ($documentFiles as $file): ?>
                                            <a class="document-card" href="<?= htmlspecialchars($file['url'] ?? '#') ?>" download>
                                                <i class='bx bx-file-blank'></i>
                                                <div>
                                                    <strong><?= htmlspecialchars(substr($file['name'] ?? 'Document', 0, 40)) ?></strong>
                                                    <small>Télécharger</small>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <div class="image-lightbox" id="imageLightbox" aria-hidden="true" role="dialog">
        <button type="button" class="image-lightbox__close" aria-label="Fermer l'image">&times;</button>
        <div class="image-lightbox__content">
            <img src="" alt="Aperçu de l'affiche" class="image-lightbox__img" id="lightboxImage">
            <p class="image-lightbox__caption" id="lightboxCaption"></p>
        </div>
    </div>

    <!-- Section CTA (Call to Action) -->
    <section class="padding-y-80">
        <div class="container container-two">
            <div class="bg-gradient p-5 rounded-5 text-white text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h2 class="display-5 fw-bold mb-3">Rejoignez la communauté GI Promo 21</h2>
                <p class="lead mb-4">Partagez vos projets, suivez les annonces et restez connecté avec vos camarades</p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="<?= ROOT ?>/Homes/register" class="btn btn-light btn-lg rounded-pill px-5">
                        <i class='bx bx-user-plus me-2'></i>
                        S'inscrire
                    </a>
                    <a href="<?= ROOT ?>/Homes/login" class="btn btn-outline-light btn-lg rounded-pill px-5">
                        <i class='bx bx-log-in me-2'></i>
                        Se connecter
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php $this->view('Partials/footer'); ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const lightbox = document.getElementById('imageLightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxCaption = document.getElementById('lightboxCaption');
    const closeBtn = lightbox ? lightbox.querySelector('.image-lightbox__close') : null;

    if (!lightbox || !lightboxImage || !lightboxCaption) {
        return;
    }

    const openLightbox = (src, caption) => {
        lightboxImage.src = src || '';
        lightboxCaption.textContent = caption || '';
        lightbox.style.display = 'flex';
        lightbox.setAttribute('aria-hidden', 'false');
        document.body.classList.add('lightbox-open');
    };

    const closeLightbox = () => {
        lightbox.style.display = 'none';
        lightbox.setAttribute('aria-hidden', 'true');
        lightboxImage.src = '';
        lightboxCaption.textContent = '';
        document.body.classList.remove('lightbox-open');
    };

    document.querySelectorAll('.gallery-item').forEach((button) => {
        button.addEventListener('click', () => {
            const imageSrc = button.dataset.image || '';
            const caption = button.dataset.caption || '';
            openLightbox(imageSrc, caption);
        });
    });

    closeBtn?.addEventListener('click', closeLightbox);

    lightbox.addEventListener('click', (event) => {
        if (event.target === lightbox) {
            closeLightbox();
        }
    });

    document.addEventListener('keyup', (event) => {
        if (event.key === 'Escape' && lightbox.getAttribute('aria-hidden') === 'false') {
            closeLightbox();
        }
    });
});
</script>

<?php $this->view('Partials/scripts'); ?>
</body>
</html>