<?php 
$this->view('Partials/head', ['pageTitle' => $project->title ?? 'Détail projet']);

// Initialisation des données
$avgRating = (float)($reviewSummary->average_rating ?? 0);
$totalReviews = (int)($reviewSummary->total_reviews ?? 0);
$likesCount = (int)($likesCount ?? 0);
$currentUserId = (int)($currentUserId ?? 0);
$ownerId = (int)($ownerId ?? 0);
$userHasLiked = !empty($userHasLiked);
$isOwner = $currentUserId === $ownerId;
$isAuthenticated = $currentUserId > 0;
?>

<body>
    <?php 
    $this->view('Partials/global-shell');
    $this->view('Partials/mobile-menu');
    $this->view('Partials/header');
    $this->view('Partials/alerts', [
        'flashMessages' => $flashMessages ?? [], 
        'notifications' => $notifications ?? []
    ]); 
    ?>

    <main class="change-gradient">
        <style>
        /* ========== VARIABLES CSS ========== */
        :root {
            --pd-primary: #0f766e;
            --pd-primary-dark: #0d5c56;
            --pd-primary-light: #14b8a6;
            --pd-primary-bg: #f0fdfa;
            --pd-secondary: #8b5cf6;
            --pd-success: #10b981;
            --pd-danger: #ef4444;
            --pd-warning: #f59e0b;
            --pd-info: #3b82f6;
            --pd-gray-50: #f8fafc;
            --pd-gray-100: #f1f5f9;
            --pd-gray-200: #e2e8f0;
            --pd-gray-300: #cbd5e1;
            --pd-gray-400: #94a3b8;
            --pd-gray-500: #64748b;
            --pd-gray-600: #475569;
            --pd-gray-700: #334155;
            --pd-gray-800: #1e293b;
            --pd-gray-900: #0f172a;
            --pd-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --pd-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --pd-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --pd-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --pd-shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --pd-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ========== LAYOUT PRINCIPAL ========== */
        .pd-shell {
            padding: 56px 0 90px;
            background: linear-gradient(135deg, #f7fffd 0%, #ffffff 25%, var(--pd-gray-50) 100%);
            position: relative;
            overflow-x: hidden;
        }

        .pd-shell::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--pd-primary), var(--pd-primary-light), var(--pd-secondary));
        }

        /* ========== CARTES ========== */
        .pd-card,
        .pd-side,
        .pd-section {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(0px);
            border: 1px solid var(--pd-gray-200);
            border-radius: 32px;
            box-shadow: var(--pd-shadow-lg);
            transition: var(--pd-transition);
            position: relative;
            overflow: hidden;
        }

        .pd-card::before,
        .pd-side::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--pd-primary), var(--pd-primary-light));
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .pd-card:hover::before,
        .pd-side:hover::before {
            transform: scaleX(1);
        }

        .pd-card:hover,
        .pd-side:hover,
        .pd-section:hover {
            transform: translateY(-4px);
            box-shadow: var(--pd-shadow-xl);
            border-color: var(--pd-primary-light);
        }

        .pd-card,
        .pd-side,
        .pd-section {
            padding: 28px;
        }

        .pd-hero {
            margin-bottom: 28px;
        }

        /* ========== TYPOGRAPHIE ========== */
        .pd-title {
            font-size: clamp(2rem, 4vw, 3.2rem);
            font-weight: 800;
            background: linear-gradient(135deg, var(--pd-gray-900), var(--pd-primary-dark));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .pd-side h4,
        .pd-section h4 {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--pd-gray-900);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            padding-bottom: 12px;
        }

        .pd-side h4::after,
        .pd-section h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, var(--pd-primary), var(--pd-primary-light));
            border-radius: 3px;
        }

        /* ========== BADGES & PILLS ========== */
        .pd-badges,
        .pd-meta,
        .pd-owner-links,
        .pd-engage {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .pd-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 100px;
            background: var(--pd-primary-bg);
            color: var(--pd-primary-dark);
            font-weight: 600;
            font-size: 0.875rem;
            transition: var(--pd-transition);
            cursor: default;
        }

        .pd-pill i,
        .pd-pill .bx {
            font-size: 1.1rem;
        }

        .pd-pill:not([href]):hover {
            transform: translateY(-2px);
            background: #e6f7f3;
        }

        a.pd-pill {
            cursor: pointer;
            text-decoration: none;
        }

        a.pd-pill:hover {
            transform: translateY(-2px);
            background: var(--pd-primary);
            color: white;
        }

        /* ========== GALERIE ========== */
        .pd-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 24px;
        }

        .pd-gallery img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 20px;
            transition: var(--pd-transition);
            cursor: pointer;
            box-shadow: var(--pd-shadow-sm);
        }

        .pd-gallery img:hover {
            transform: scale(1.03);
            box-shadow: var(--pd-shadow-lg);
        }

        /* ========== DESCRIPTION ========== */
        .pd-desc {
            color: var(--pd-gray-700);
            line-height: 1.8;
            font-size: 1rem;
            margin-top: 20px;
        }

        /* ========== PROPRIÉTAIRE ========== */
        .pd-owner {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
            padding: 16px;
            background: var(--pd-gray-50);
            border-radius: 24px;
            transition: var(--pd-transition);
        }

        .pd-owner:hover {
            background: var(--pd-primary-bg);
            transform: translateX(5px);
        }

        .pd-owner-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--pd-primary), var(--pd-primary-light));
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            font-weight: 800;
            box-shadow: var(--pd-shadow-md);
            transition: var(--pd-transition);
        }

        .pd-owner-avatar:hover {
            transform: scale(1.05);
            box-shadow: var(--pd-shadow-lg);
        }

        .pd-owner-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ========== ÉTOILES ========== */
        .pd-stars {
            display: flex;
            gap: 6px;
            color: var(--pd-warning);
            font-size: 1.2rem;
        }

        .pd-stars i {
            transition: var(--pd-transition);
        }

        .pd-stars i:hover {
            transform: scale(1.1);
        }

        /* ========== BOUTONS ========== */
        .pd-like-btn,
        .pd-submit {
            border: none;
            border-radius: 20px;
            padding: 14px 24px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: var(--pd-transition);
            position: relative;
            overflow: hidden;
        }

        .pd-like-btn::before,
        .pd-submit::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .pd-like-btn:hover::before,
        .pd-submit:hover::before {
            width: 300px;
            height: 300px;
        }

        .pd-like-btn {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #b91c1c;
            width: 100%;
        }

        .pd-like-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px -5px rgba(185, 28, 28, 0.3);
        }

        .pd-like-btn.is-active {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
        }

        .pd-submit {
            background: linear-gradient(135deg, var(--pd-primary), var(--pd-primary-light));
            color: white;
            box-shadow: 0 4px 15px rgba(15, 118, 110, 0.3);
        }

        .pd-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px var(--pd-primary);
        }

        .pd-submit:active {
            transform: translateY(0);
        }

        /* ========== FORMULAIRES ========== */
        .pd-form-control {
            width: 100%;
            border: 2px solid var(--pd-gray-200);
            border-radius: 20px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: var(--pd-transition);
            background: white;
        }

        .pd-form-control:focus {
            outline: none;
            border-color: var(--pd-primary-light);
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.15);
        }

        .pd-form-control:hover {
            border-color: var(--pd-gray-300);
        }

        /* ========== REVIEWS & MESSAGES ========== */
        .pd-review,
        .pd-message {
            padding: 20px;
            border: 1px solid var(--pd-gray-200);
            border-radius: 24px;
            background: white;
            transition: var(--pd-transition);
        }

        .pd-review:hover,
        .pd-message:hover {
            transform: translateX(5px);
            border-color: var(--pd-primary-light);
            box-shadow: var(--pd-shadow-md);
        }

        /* ========== PROJETS LIÉS ========== */
        .pd-related {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        .pd-related-card {
            padding: 20px;
            border: 1px solid var(--pd-gray-200);
            border-radius: 24px;
            background: white;
            transition: var(--pd-transition);
            position: relative;
            overflow: hidden;
        }

        .pd-related-card::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--pd-primary), var(--pd-primary-light));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .pd-related-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--pd-shadow-lg);
            border-color: var(--pd-primary-light);
        }

        .pd-related-card:hover::before {
            transform: scaleX(1);
        }

        .pd-related-card a {
            color: var(--pd-primary);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: var(--pd-transition);
        }

        .pd-related-card a:hover {
            gap: 10px;
            color: var(--pd-primary-dark);
        }

        /* ========== ASSISTANT IA ========== */
        .pd-ai-chat {
            display: flex;
            flex-direction: column;
            gap: 16px;
            min-height: 280px;
            max-height: 400px;
            overflow-y: auto;
            padding: 4px;
        }

        .pd-ai-chat::-webkit-scrollbar {
            width: 6px;
        }

        .pd-ai-chat::-webkit-scrollbar-track {
            background: var(--pd-gray-100);
            border-radius: 10px;
        }

        .pd-ai-chat::-webkit-scrollbar-thumb {
            background: var(--pd-primary-light);
            border-radius: 10px;
        }

        .pd-ai-bubble {
            padding: 16px 20px;
            border-radius: 24px;
            white-space: pre-wrap;
            line-height: 1.6;
            animation: slideIn 0.3s ease;
            position: relative;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pd-ai-bubble.user {
            background: linear-gradient(135deg, var(--pd-primary-bg), #e6f7f3);
            color: var(--pd-primary-dark);
            margin-left: auto;
            max-width: 85%;
            border-bottom-right-radius: 8px;
        }

        .pd-ai-bubble.assistant {
            background: linear-gradient(135deg, white, var(--pd-gray-50));
            border: 1px solid var(--pd-gray-200);
            color: var(--pd-gray-700);
            margin-right: auto;
            max-width: 85%;
            border-bottom-left-radius: 8px;
        }

        .pd-ai-input {
            width: 100%;
            border: 2px solid var(--pd-gray-200);
            border-radius: 24px;
            padding: 14px 18px;
            min-height: 110px;
            font-size: 0.95rem;
            transition: var(--pd-transition);
            resize: vertical;
        }

        .pd-ai-input:focus {
            outline: none;
            border-color: var(--pd-primary-light);
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.1);
        }

        .pd-ai-suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 16px;
        }

        .pd-ai-chip {
            border: 1px solid var(--pd-gray-200);
            background: white;
            color: var(--pd-primary);
            border-radius: 100px;
            padding: 10px 18px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: var(--pd-transition);
        }

        .pd-ai-chip:hover {
            background: var(--pd-primary);
            color: white;
            border-color: var(--pd-primary);
            transform: translateY(-2px);
        }

        /* ========== FICHIERS ========== */
        .pd-file-list {
            padding-left: 20px;
            list-style: none;
        }

        .pd-file-list li {
            margin-bottom: 12px;
            position: relative;
            padding-left: 28px;
        }

        .pd-file-list li::before {
            content: '📄';
            position: absolute;
            left: 0;
            top: 0;
        }

        .pd-file-list a {
            color: var(--pd-gray-700);
            text-decoration: none;
            transition: var(--pd-transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .pd-file-list a:hover {
            color: var(--pd-primary);
            transform: translateX(5px);
        }

        /* ========== ANIMATIONS ========== */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        .loading-skeleton {
            animation: shimmer 2s infinite linear;
            background: linear-gradient(90deg, var(--pd-gray-100) 25%, var(--pd-gray-200) 50%, var(--pd-gray-100) 75%);
            background-size: 1000px 100%;
        }

        /* ========== MEDIA QUERIES ========== */
        @media (max-width: 1199px) {
            .pd-gallery {
                grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            }
            .pd-related {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 991px) {
            .pd-shell {
                padding: 40px 0 70px;
            }
            .pd-card, .pd-side, .pd-section {
                padding: 24px;
                border-radius: 28px;
            }
            .pd-title {
                font-size: clamp(1.7rem, 7vw, 2.5rem);
            }
            .pd-gallery img {
                height: 170px;
            }
            .pd-like-btn, .pd-submit {
                width: 100%;
            }
            .pd-owner-avatar {
                width: 65px;
                height: 65px;
                font-size: 1.6rem;
            }
        }

        @media (max-width: 767px) {
            .pd-shell {
                padding: 30px 0 50px;
            }
            .pd-card, .pd-side, .pd-section {
                padding: 20px;
                border-radius: 24px;
            }
            .pd-gallery {
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }
            .pd-gallery img {
                height: 150px;
            }
            .pd-related {
                grid-template-columns: 1fr;
            }
            .pd-owner {
                flex-direction: column;
                text-align: center;
            }
            .pd-owner-avatar {
                width: 70px;
                height: 70px;
            }
            .pd-side h4::after,
            .pd-section h4::after {
                width: 40px;
            }
        }

        @media (max-width: 575px) {
            .pd-title {
                font-size: 1.6rem;
            }
            .pd-gallery {
                grid-template-columns: 1fr;
            }
            .pd-gallery img {
                height: 200px;
            }
            .pd-badges {
                gap: 8px;
            }
            .pd-pill {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
            .pd-ai-bubble.user,
            .pd-ai-bubble.assistant {
                max-width: 95%;
            }
        }

        /* ========== UTILITAIRES ========== */
        .text-gradient {
            background: linear-gradient(135deg, var(--pd-primary), var(--pd-primary-light));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hover-lift {
            transition: var(--pd-transition);
        }

        .hover-lift:hover {
            transform: translateY(-5px);
        }

        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 20px;
            box-shadow: var(--pd-shadow-lg);
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        </style>

        <section class="pd-shell">
            <div class="container container-two">
                <div class="row g-4">
                    <!-- Colonne principale -->
                    <div class="col-lg-8">
                        <div class="pd-card pd-hero">
                            <!-- Bouton retour animé -->
                            <a href="<?= ROOT ?>/Homes/index" class="pd-pill mb-3" style="display: inline-flex;">
                                <i class='bx bx-left-arrow-alt'></i> Retour à l'accueil
                            </a>
                            
                            <h1 class="pd-title mb-3"><?= htmlspecialchars($project->title ?? 'Projet') ?></h1>
                            
                            <!-- Badges avec animations -->
                            <div class="pd-badges mb-3">
                                <span class="pd-pill">
                                    <i class='bx bx-category'></i>
                                    <?= htmlspecialchars($project->categorie ?? 'Sans catégorie') ?>
                                </span>
                                <span class="pd-pill">
                                    <i class='bx bx-time'></i>
                                    <?= htmlspecialchars($this->temps_relatif($project->created_at ?? date('Y-m-d H:i:s'))) ?>
                                </span>
                                <span class="pd-pill">
                                    <i class='bx bx-heart'></i>
                                    <?= $likesCount ?> mention(s) J'aime
                                </span>
                                <span class="pd-pill">
                                    <i class='bx bx-star'></i>
                                    <?= number_format($avgRating, 1) ?>/5 sur <?= $totalReviews ?> avis
                                </span>
                            </div>
                            
                            <!-- Description -->
                            <div class="pd-desc">
                                <?= nl2br(htmlspecialchars((string)($project->description ?? ''), ENT_QUOTES, 'UTF-8')) ?>
                            </div>

                            <!-- Galerie d'images avec effet lightbox -->
                            <?php if (!empty($images)): ?>
                            <div class="pd-gallery">
                                <?php foreach ($images as $index => $img): ?>
                                <img src="<?= ROOT_IMG ?>/uploads/projects/images/<?= htmlspecialchars($img->image ?? '') ?>"
                                     alt="Image projet <?= $index + 1 ?>"
                                     loading="lazy"
                                     onclick="openLightbox(this.src)"
                                     style="cursor: pointer;">
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <!-- Technologies -->
                            <?php if (!empty($project->technologies)): ?>
                            <div class="pd-badges mt-4">
                                <?php foreach (explode(',', (string)$project->technologies) as $tech): ?>
                                    <?php if (trim($tech) !== ''): ?>
                                        <span class="pd-pill">⚡ <?= htmlspecialchars(trim($tech)) ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <!-- Vidéo -->
                            <?php if (!empty($project->video) && ($videoUrl = $this->youtube_embed($project->video))): ?>
                            <div class="pd-section mt-4">
                                <h4>🎬 Vidéo de démonstration</h4>
                                <div class="video-container">
                                    <iframe src="<?= htmlspecialchars($videoUrl) ?>" 
                                            title="Vidéo du projet"
                                            allowfullscreen></iframe>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Section Avis -->
                            <div class="pd-section mt-4">
                                <h4>⭐ Avis et notation</h4>
                                <div class="pd-engage mb-3">
                                    <div class="pd-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class='bx <?= $i <= round($avgRating) ? 'bxs-star' : 'bx-star' ?>'></i>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="text-muted">
                                        <?= number_format($avgRating, 1) ?>/5 basé sur <?= $totalReviews ?> avis
                                    </div>
                                </div>

                                <?php if ($isAuthenticated && !$isOwner): ?>
                                <form method="post" class="mb-4">
                                    <input type="hidden" name="action" value="submit_review">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Note</label>
                                            <select name="rating" class="pd-form-control" required>
                                                <option value="">Choisir</option>
                                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                                    <option value="<?= $i ?>"><?= $i ?> étoile(s)</option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-9">
                                            <label class="form-label fw-semibold">Votre avis</label>
                                            <textarea name="review" class="pd-form-control" rows="3"
                                                      placeholder="Partagez votre retour sur ce projet"></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="pd-submit mt-3">
                                        📝 Publier mon avis
                                    </button>
                                </form>
                                <?php endif; ?>

                                <div class="row g-3">
                                    <?php foreach ($reviews as $review): ?>
                                    <div class="col-12">
                                        <div class="pd-review">
                                            <div class="d-flex justify-content-between flex-wrap gap-2 mb-2">
                                                <strong>
                                                    <?= htmlspecialchars(trim(($review->prenom ?? '') . ' ' . ($review->nom ?? 'Utilisateur'))) ?>
                                                </strong>
                                                <span class="pd-stars">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class='bx <?= $i <= (int)($review->rating ?? 0) ? 'bxs-star' : 'bx-star' ?>'></i>
                                                    <?php endfor; ?>
                                                </span>
                                            </div>
                                            <div class="text-muted small mb-2">
                                                <?= htmlspecialchars((string)($review->created_at ?? '')) ?>
                                            </div>
                                            <div><?= nl2br(htmlspecialchars((string)($review->review ?? ''))) ?></div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php if (empty($reviews)): ?>
                                        <div class="col-12">
                                            <div class="pd-review text-center">
                                                🤝 Aucun avis pour le moment. Soyez le premier à donner votre avis !
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Section Discussion -->
                            <div class="pd-section mt-4">
                                <h4>💬 Discussion avec le propriétaire</h4>
                                <?php if ($isAuthenticated && !$isOwner): ?>
                                    <div class="row g-3 mb-4">
                                        <?php foreach ($conversation as $message): ?>
                                        <div class="col-12">
                                            <div class="pd-message <?= (int)($message->sender_id ?? 0) === $currentUserId ? 'user-message' : '' ?>"
                                                 style="<?= (int)($message->sender_id ?? 0) === $currentUserId ? 'background: linear-gradient(135deg, #ecfeff, #d1fae5); border-color: #a7f3d0;' : '' ?>">
                                                <div class="small text-muted mb-2">
                                                    <?= htmlspecialchars(trim(($message->sender_prenom ?? '') . ' ' . ($message->sender_nom ?? ''))) ?>
                                                    • <?= htmlspecialchars((string)($message->created_at ?? '')) ?>
                                                </div>
                                                <div><?= nl2br(htmlspecialchars((string)($message->message ?? ''))) ?></div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php if (empty($conversation)): ?>
                                            <div class="col-12">
                                                <div class="pd-message text-center">
                                                    💬 Commencez la conversation avec le propriétaire du projet.
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <form method="post">
                                        <input type="hidden" name="action" value="send_message">
                                        <input type="hidden" name="receiver_id" value="<?= $ownerId ?>">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <textarea name="message" class="pd-form-control" rows="4"
                                                  placeholder="Écrivez votre message au propriétaire" required></textarea>
                                        <button type="submit" class="pd-submit mt-3">
                                            ✉️ Envoyer le message
                                        </button>
                                    </form>
                                <?php elseif (!$isAuthenticated): ?>
                                    <div class="pd-message text-center">
                                        🔒 Connectez-vous pour discuter avec le propriétaire du projet.
                                    </div>
                                <?php else: ?>
                                    <div class="pd-message text-center">
                                        👑 Vous êtes le propriétaire de ce projet. Les utilisateurs peuvent vous contacter depuis cette page.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne latérale -->
                    <div class="col-lg-4">
                        <!-- Carte propriétaire -->
                        <div class="pd-side mb-4">
                            <h4>👤 Propriétaire du projet</h4>
                            <div class="pd-owner">
                                <div class="pd-owner-avatar">
                                    <?php if (!empty($project->owner_image)): ?>
                                        <img src="<?= ROOT_IMG ?>/<?= htmlspecialchars(ltrim((string)$project->owner_image, '/')) ?>"
                                             alt="Photo de profil">
                                    <?php else: ?>
                                        <?= strtoupper(substr((string)($project->prenom ?? 'U'), 0, 1)) ?>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <strong><?= htmlspecialchars(trim(($project->prenom ?? '') . ' ' . ($project->nom ?? ''))) ?></strong>
                                    <div class="text-muted small">
                                        🎓 <?= htmlspecialchars((string)($project->filiere ?? 'Étudiant')) ?>
                                    </div>
                                    <div class="text-muted small">
                                        🏛️ <?= htmlspecialchars((string)($project->universite ?? '')) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="small text-muted mb-2">📞 Contact</div>
                            <div class="mb-2">
                                <i class='bx bx-envelope'></i> <?= htmlspecialchars((string)($project->email ?? 'Non renseigné')) ?>
                            </div>
                            <div class="mb-3">
                                <i class='bx bx-phone'></i> <?= htmlspecialchars((string)($project->contact ?? 'Non renseigné')) ?>
                            </div>
                            <div class="pd-owner-links">
                                <?php if (!empty($project->github)): ?>
                                    <a class="pd-pill" href="<?= htmlspecialchars($project->github) ?>" target="_blank" rel="noopener noreferrer">
                                        <i class='bx bxl-github'></i> GitHub
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($project->linkedin)): ?>
                                    <a class="pd-pill" href="<?= htmlspecialchars($project->linkedin) ?>" target="_blank" rel="noopener noreferrer">
                                        <i class='bx bxl-linkedin'></i> LinkedIn
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Carte engagement -->
                        <div class="pd-side mb-4">
                            <h4>❤️ Engagement</h4>
                            <form method="post">
                                <input type="hidden" name="action" value="toggle_like">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                <button type="submit" class="pd-like-btn <?= $userHasLiked ? 'is-active' : '' ?>">
                                    <?= $userHasLiked ? '❤️ Retirer mon j\'aime' : '🤍 J\'aime ce projet' ?> • <?= $likesCount ?>
                                </button>
                            </form>
                            <div class="pd-badges mt-3">
                                <span class="pd-pill">
                                    <i class='bx bx-star'></i> <?= number_format($avgRating, 1) ?>/5
                                </span>
                                <span class="pd-pill">
                                    <i class='bx bx-message-rounded-dots'></i> <?= count($conversation ?? []) ?> message(s)
                                </span>
                            </div>
                        </div>

                        <!-- Assistant IA -->
                        <div class="pd-side mb-4">
                            <h4>🤖 Assistant IA</h4>
                            <p class="text-muted small">
                                Posez une question sur l'utilité du projet, ses technologies, 
                                son niveau de difficulté ou les points à demander au propriétaire.
                            </p>
                            <div class="pd-ai-chat" id="projectAiChat">
                                <div class="pd-ai-bubble assistant">
                                    👋 Je peux vous aider à comprendre ce projet en détail !
                                </div>
                            </div>
                            <div class="pd-ai-suggestions" id="projectAiSuggestions">
                                <button type="button" class="pd-ai-chip" data-project-ai-prompt="Ce projet est-il adapté à un débutant ?">
                                    🎯 Pour débutant ?
                                </button>
                                <button type="button" class="pd-ai-chip" data-project-ai-prompt="Quels sont ses points forts pour un salon numérique ?">
                                    💪 Points forts
                                </button>
                                <button type="button" class="pd-ai-chip" data-project-ai-prompt="Quelles améliorations prioritaires proposer ?">
                                    🚀 Améliorations
                                </button>
                            </div>
                            <div class="mt-3">
                                <textarea id="projectAiInput" class="pd-ai-input"
                                          placeholder="💭 Exemple : Ce projet est-il pertinent pour un étudiant qui veut apprendre PHP et MySQL ?"></textarea>
                                <button type="button" class="pd-submit mt-3" id="projectAiSend">
                                    🤖 Demander à l'assistant
                                </button>
                            </div>
                        </div>

                        <!-- Fichiers -->
                        <div class="pd-side mb-4">
                            <h4>📁 Fichiers du projet</h4>
                            <?php if (!empty($files)): ?>
                                <ul class="pd-file-list">
                                    <?php foreach ($files as $file): ?>
                                        <li>
                                            <a href="<?= ROOT_IMG ?>/uploads/projects/files/<?= htmlspecialchars($file->fichier ?? '') ?>"
                                               target="_blank">
                                                📄 <?= htmlspecialchars($file->fichier ?? 'Document') ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <div class="text-muted text-center py-3">
                                    📂 Aucun fichier associé.
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Projets liés -->
                        <div class="pd-side">
                            <h4>🔗 Autres projets</h4>
                            <div class="pd-related">
                                <?php foreach ($relatedProjects as $item): ?>
                                    <div class="pd-related-card">
                                        <strong><?= htmlspecialchars($item['title'] ?? '') ?></strong>
                                        <div class="small text-muted mb-2">
                                            📂 <?= htmlspecialchars($item['category'] ?? '') ?>
                                        </div>
                                        <a href="<?= ROOT ?>/Projets/detail/<?= (int)($item['id'] ?? 0) ?>">
                                            Voir le projet →
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php 
        $this->view('Partials/footer');
        $this->view('Partials/scripts'); 
        ?>

        <!-- Lightbox pour les images -->
        <div id="lightbox" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.9); z-index:9999; cursor:pointer; align-items:center; justify-content:center;">
            <img id="lightbox-img" style="max-width:90%; max-height:90%; object-fit:contain;">
        </div>

        <script>
        // Lightbox functionality
        function openLightbox(src) {
            const lightbox = document.getElementById('lightbox');
            const lightboxImg = document.getElementById('lightbox-img');
            lightboxImg.src = src;
            lightbox.style.display = 'flex';
        }
        
        document.getElementById('lightbox')?.addEventListener('click', function() {
            this.style.display = 'none';
        });

        // Assistant IA avec animations améliorées
        (function($) {
            const projectAiHistory = [{
                role: 'assistant',
                content: "👋 Je peux vous aider à comprendre ce projet en détail !"
            }];

            function appendProjectAi(role, text) {
                const icon = role === 'user' ? '👤' : '🤖';
                const safe = $('<div>').text(text).html().replace(/\n/g, '<br>');
                const bubble = $('<div class="pd-ai-bubble ' + role + '">' + icon + ' ' + safe + '</div>');
                $('#projectAiChat').append(bubble);
                
                // Animation d'apparition
                bubble.hide().fadeIn(300);
                
                const box = $('#projectAiChat').get(0);
                if (box) box.scrollTop = box.scrollHeight;
            }

            function renderProjectSuggestions(items) {
                const suggestions = Array.isArray(items) ? items.filter(Boolean).slice(0, 3) : [];
                if (!suggestions.length) return;
                
                const suggestionIcons = ['🎯', '💡', '🚀'];
                $('#projectAiSuggestions').html(suggestions.map(function(item, index) {
                    const safe = $('<div>').text(item).html();
                    const icon = suggestionIcons[index % suggestionIcons.length];
                    return '<button type="button" class="pd-ai-chip" data-project-ai-prompt="' + safe + '">' +
                        icon + ' ' + safe + '</button>';
                }).join(''));
            }

            let isSending = false;
            
            function sendProjectAi() {
                if (isSending) {
                    appendProjectAi('assistant', "⏳ Veuillez patienter, je traite votre demande précédente...");
                    return;
                }
                
                const text = $('#projectAiInput').val().trim();
                if (!text) {
                    appendProjectAi('assistant', "💬 Veuillez écrire une question pour que je puisse vous aider.");
                    return;
                }
                
                isSending = true;
                appendProjectAi('user', text);
                projectAiHistory.push({
                    role: 'user',
                    content: text
                });
                $('#projectAiInput').val('');
                
                // Indicateur de chargement
                const loadingMsg = "🤔 Je réfléchis à votre question...";
                appendProjectAi('assistant', loadingMsg);
                
                $.post('<?= ROOT ?>/Projets/ai_assistant/<?= (int)($project->id ?? 0) ?>', {
                    message: text,
                    history: JSON.stringify(projectAiHistory.slice(-6))
                }, function(response) {
                    // Supprimer le message de chargement
                    $('#projectAiChat .pd-ai-bubble.assistant').last().remove();
                    
                    const answer = response && response.message ? response.message :
                        "😕 Je n'ai pas pu répondre pour le moment. Veuillez réessayer.";
                    appendProjectAi('assistant', answer);
                    projectAiHistory.push({
                        role: 'assistant',
                        content: answer
                    });
                    renderProjectSuggestions(response && response.suggestions ? response.suggestions : []);
                    isSending = false;
                }, 'json').fail(function() {
                    $('#projectAiChat .pd-ai-bubble.assistant').last().remove();
                    const fallback = "⚠️ L'assistant IA n'est pas disponible pour le moment. Veuillez réessayer plus tard.";
                    appendProjectAi('assistant', fallback);
                    projectAiHistory.push({
                        role: 'assistant',
                        content: fallback
                    });
                    isSending = false;
                });
            }

            $('#projectAiSend').on('click', sendProjectAi);
            $('#projectAiInput').on('keydown', function(event) {
                if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') {
                    event.preventDefault();
                    sendProjectAi();
                }
            });
            $(document).on('click', '[data-project-ai-prompt]', function() {
                $('#projectAiInput').val($(this).data('project-ai-prompt'));
                sendProjectAi();
            });
        })(jQuery);
        </script>
    </main>
</body>
</html>