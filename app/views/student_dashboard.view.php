<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Dashboard etudiant']); ?>
<?php
$studentStats = $studentStats ?? [];
$projects = $projects ?? [];
$studentName = $studentName ?? 'Etudiant';
$studentLatestProject = $studentLatestProject ?? null;
$studentCompletionRate = (int) ($studentCompletionRate ?? 0);
$studentVisitorReviews = $studentVisitorReviews ?? [];
$studentUnreadThreadsPreview = $studentUnreadThreadsPreview ?? [];
$studentUnreadMessages = (int) ($studentUnreadMessages ?? 0);
$studentActions = $studentActions ?? [];
?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>

            <div class="dashboard-body__content p-4">
                <?php $this->view('set_flash'); ?>

                <style>
                .student-dash {
                    --sd-pri: #0f766e;
                    --sd-pri-2: #14b8a6;
                    --sd-ink: #0f172a;
                    --sd-muted: #475569;
                    --sd-line: #dbe4ee;
                    --sd-soft: #f8fafc;
                }

                .student-hero,
                .student-card,
                .student-project,
                .student-action {
                    background: #fff;
                    border: 1px solid var(--sd-line);
                    border-radius: 28px;
                    box-shadow: 0 24px 60px -42px rgba(15, 23, 42, .28);
                }

                .student-card,
                .student-hero,
                .student-project,
                .student-action,
                .student-feedback-card {
                    transition: transform .32s ease, box-shadow .32s ease, opacity .55s ease, filter .55s ease;
                }

                [data-reveal] {
                    opacity: 0;
                    transform: translateY(24px) scale(.985);
                    filter: blur(3px);
                }

                [data-reveal].is-visible {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                    filter: blur(0);
                }

                .student-hero {
                    padding: 30px;
                    background:
                        radial-gradient(circle at top right, rgba(20, 184, 166, .16), transparent 26%),
                        linear-gradient(145deg, #ffffff 0%, #f4fffd 100%);
                }

                .student-kicker,
                .student-pill,
                .student-tag,
                .student-mini-stat {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    border-radius: 999px;
                }

                .student-kicker {
                    padding: 9px 16px;
                    background: #ecfeff;
                    color: #115e59;
                    font-weight: 900;
                    letter-spacing: .05em;
                    text-transform: uppercase;
                    font-size: .78rem;
                }

                .student-title {
                    font-size: clamp(2rem, 4vw, 3.2rem);
                    line-height: 1.03;
                    font-weight: 900;
                    color: var(--sd-ink);
                    margin: 18px 0 12px;
                }

                .student-copy,
                .student-muted {
                    color: var(--sd-muted);
                }

                .student-hero-grid,
                .student-project-grid,
                .student-actions-grid,
                .student-summary-grid {
                    display: grid;
                    gap: 18px;
                }

                .student-hero-grid {
                    grid-template-columns: 1.2fr .8fr;
                    align-items: stretch;
                }

                .student-hero-side {
                    display: grid;
                    gap: 14px;
                }

                .student-progress-card {
                    padding: 22px;
                    border-radius: 24px;
                    background: linear-gradient(160deg, #0f172a 0%, #17344d 58%, #0f766e 100%);
                    color: #fff;
                }

                .student-progress-ring {
                    position: relative;
                    width: 132px;
                    height: 132px;
                    margin: 10px auto 18px;
                    border-radius: 50%;
                    background: conic-gradient(#5eead4 <?= max(0, min(100, $studentCompletionRate)) ?>%, rgba(255,255,255,.12) 0);
                    display: grid;
                    place-items: center;
                }

                .student-progress-ring::before {
                    content: '';
                    width: 92px;
                    height: 92px;
                    border-radius: 50%;
                    background: #10263a;
                    box-shadow: inset 0 0 0 1px rgba(255,255,255,.08);
                }

                .student-progress-value {
                    position: absolute;
                    font-size: 1.65rem;
                    font-weight: 900;
                    color: #fff;
                }

                .student-mini-stat {
                    padding: 10px 14px;
                    background: rgba(255,255,255,.1);
                    border: 1px solid rgba(255,255,255,.12);
                    color: rgba(255,255,255,.92);
                    font-weight: 700;
                }

                .student-summary-grid {
                    grid-template-columns: repeat(4, minmax(0, 1fr));
                    margin-top: 22px;
                }

                .student-card {
                    padding: 22px;
                }

                .student-stat-card {
                    position: relative;
                    overflow: hidden;
                }

                .student-stat-card::after {
                    content: '';
                    position: absolute;
                    inset: auto -20px -36px auto;
                    width: 120px;
                    height: 120px;
                    background: radial-gradient(circle, rgba(20, 184, 166, .14), transparent 68%);
                }

                .student-stat-icon {
                    width: 54px;
                    height: 54px;
                    border-radius: 18px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.45rem;
                    margin-bottom: 16px;
                    color: #0f766e;
                    background: #ecfeff;
                }

                .student-stat-label {
                    color: var(--sd-muted);
                    font-weight: 700;
                    margin-bottom: 8px;
                }

                .student-stat-value {
                    font-size: 2rem;
                    line-height: 1;
                    font-weight: 900;
                    color: var(--sd-ink);
                    margin-bottom: 6px;
                }

                .student-project-grid {
                    grid-template-columns: 1.05fr .95fr;
                    margin-top: 22px;
                }

                .student-section-head {
                    display: flex;
                    justify-content: space-between;
                    gap: 16px;
                    flex-wrap: wrap;
                    margin-bottom: 18px;
                }

                .student-section-head h3 {
                    margin: 0;
                    color: var(--sd-ink);
                    font-size: 1.3rem;
                    font-weight: 850;
                }

                .student-section-head p {
                    margin: 6px 0 0;
                    color: var(--sd-muted);
                }

                .student-pill {
                    padding: 10px 15px;
                    background: #f0fdfa;
                    color: #115e59;
                    font-weight: 800;
                    text-decoration: none;
                }

                .student-project-list {
                    display: grid;
                    gap: 14px;
                }

                .student-project {
                    padding: 16px;
                    display: grid;
                    grid-template-columns: 96px minmax(0, 1fr);
                    gap: 16px;
                    align-items: center;
                }

                .student-project img {
                    width: 96px;
                    height: 96px;
                    object-fit: cover;
                    border-radius: 20px;
                }

                .student-project h4 {
                    margin: 0 0 8px;
                    font-size: 1.08rem;
                    font-weight: 850;
                    color: var(--sd-ink);
                }

                .student-project h4 a {
                    color: inherit;
                    text-decoration: none;
                }

                .student-project-meta,
                .student-project-stats,
                .student-tech-list {
                    display: flex;
                    gap: 10px;
                    flex-wrap: wrap;
                }

                .student-project-meta,
                .student-project-stats {
                    margin-bottom: 10px;
                    color: var(--sd-muted);
                    font-size: .94rem;
                }

                .student-tag {
                    padding: 7px 12px;
                    background: #f8fafc;
                    border: 1px solid var(--sd-line);
                    color: #334155;
                    font-weight: 700;
                    font-size: .86rem;
                }

                .student-status {
                    padding: 7px 12px;
                    border-radius: 999px;
                    font-weight: 800;
                    font-size: .84rem;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                }

                .student-status--pending {
                    background: #fff7ed;
                    color: #b45309;
                }

                .student-status--valid {
                    background: #ecfdf5;
                    color: #047857;
                }

                .student-status--draft {
                    background: #eef2ff;
                    color: #4338ca;
                }

                .student-latest {
                    padding: 22px;
                    background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
                }

                .student-latest-cover {
                    width: 100%;
                    height: 240px;
                    object-fit: cover;
                    border-radius: 22px;
                    margin-bottom: 18px;
                }

                .student-actions-grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                    margin-top: 22px;
                }

                .student-feedback-grid {
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 16px;
                    margin-top: 22px;
                }

                .student-inbox-preview {
                    display: grid;
                    gap: 14px;
                    margin-top: 22px;
                }

                .student-inbox-preview__item {
                    padding: 18px;
                    border-radius: 22px;
                    border: 1px solid var(--sd-line);
                    background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
                }

                .student-inbox-preview__meta {
                    display: flex;
                    gap: 10px;
                    flex-wrap: wrap;
                    color: var(--sd-muted);
                    font-size: .92rem;
                    margin: 8px 0 10px;
                }

                .student-unread-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    padding: 8px 12px;
                    border-radius: 999px;
                    background: #fef2f2;
                    border: 1px solid #fecaca;
                    color: #b91c1c;
                    font-weight: 800;
                    font-size: .84rem;
                }

                .student-feedback-card {
                    padding: 20px;
                }

                .student-feedback-head,
                .student-contact-actions,
                .student-feedback-meta {
                    display: flex;
                    gap: 10px;
                    flex-wrap: wrap;
                }

                .student-feedback-head {
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 12px;
                }

                .student-feedback-user {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }

                .student-feedback-avatar {
                    width: 52px;
                    height: 52px;
                    border-radius: 50%;
                    background: #ecfeff;
                    color: #0f766e;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.1rem;
                    font-weight: 900;
                    overflow: hidden;
                }

                .student-feedback-avatar img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .student-feedback-meta {
                    color: var(--sd-muted);
                    font-size: .92rem;
                    margin-bottom: 12px;
                }

                .student-contact-actions {
                    margin-top: 16px;
                }

                .student-contact-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    padding: 10px 14px;
                    border-radius: 999px;
                    text-decoration: none;
                    font-weight: 800;
                    border: 1px solid var(--sd-line);
                    background: #f8fafc;
                    color: #0f172a;
                }

                .student-contact-btn--wa {
                    background: #ecfdf5;
                    border-color: #bbf7d0;
                    color: #166534;
                }

                .student-contact-btn--call {
                    background: #eff6ff;
                    border-color: #bfdbfe;
                    color: #1d4ed8;
                }

                .student-contact-btn--mail {
                    background: #fff7ed;
                    border-color: #fed7aa;
                    color: #c2410c;
                }

                .student-action {
                    padding: 22px;
                    text-decoration: none;
                    color: inherit;
                    transition: transform .24s ease, box-shadow .24s ease;
                }

                .student-action:hover,
                .student-project:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 26px 48px -38px rgba(15, 23, 42, .32);
                }

                .student-action__icon {
                    width: 56px;
                    height: 56px;
                    border-radius: 18px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.5rem;
                    margin-bottom: 16px;
                }

                .student-action--primary .student-action__icon {
                    background: linear-gradient(135deg, var(--sd-pri), var(--sd-pri-2));
                    color: #fff;
                }

                .student-action--soft .student-action__icon {
                    background: #ecfeff;
                    color: #0f766e;
                }

                .student-empty {
                    padding: 26px;
                    border-radius: 22px;
                    border: 1px dashed var(--sd-line);
                    background: #f8fafc;
                    color: var(--sd-muted);
                    text-align: center;
                }

                @media (max-width: 1399px) {
                    .student-summary-grid {
                        grid-template-columns: repeat(2, minmax(0, 1fr));
                    }

                    .student-actions-grid {
                        grid-template-columns: 1fr;
                    }
                }

                @media (max-width: 1199px) {
                    .student-hero-grid,
                    .student-project-grid {
                        grid-template-columns: 1fr;
                    }

                    .student-feedback-grid {
                        grid-template-columns: 1fr;
                    }
                }

                @media (max-width: 767px) {
                    .student-hero,
                    .student-card,
                    .student-project,
                    .student-action {
                        border-radius: 22px;
                    }

                    .student-hero {
                        padding: 22px;
                    }

                    .student-summary-grid {
                        grid-template-columns: 1fr;
                    }

                    .student-project {
                        grid-template-columns: 1fr;
                    }

                    .student-project img,
                    .student-latest-cover {
                        width: 100%;
                        height: auto;
                        aspect-ratio: 16 / 10;
                    }
                }
                </style>

                <div class="student-dash">
                    <section class="student-hero mb-4" data-reveal>
                        <div class="student-hero-grid">
                            <div>
                                <span class="student-kicker"><i class='bx bx-graduation'></i> Espace etudiant</span>
                                <h1 class="student-title">Bonjour <?= htmlspecialchars($studentName) ?>, pilotez vos projets comme un vrai portfolio numerique.</h1>
                                <p class="student-copy mb-0">Suivez vos publications, votre progression, l'engagement recu et les actions prioritaires depuis un tableau de bord plus propre, plus lisible et plus professionnel.</p>
                            </div>
                            <div class="student-hero-side">
                                <div class="student-progress-card">
                                    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                                        <div>
                                            <strong style="font-size:1.1rem">Taux de projets valides</strong>
                                            <div style="color:rgba(255,255,255,.76);margin-top:4px">Mesurez rapidement votre avancement sur la plateforme.</div>
                                        </div>
                                        <span class="student-mini-stat"><i class='bx bx-check-double'></i> <?= (int) ($studentStats['valides'] ?? 0) ?> valide(s)</span>
                                    </div>
                                    <div class="student-progress-ring">
                                        <span class="student-progress-value"><?= $studentCompletionRate ?>%</span>
                                    </div>
                                    <div class="d-flex gap-2 flex-wrap justify-content-center">
                                        <span class="student-mini-stat"><i class='bx bx-folder'></i> <?= (int) ($studentStats['mesProjets'] ?? 0) ?> projet(s)</span>
                                        <span class="student-mini-stat"><i class='bx bx-time-five'></i> <?= (int) ($studentStats['enAttente'] ?? 0) ?> en attente</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="student-summary-grid">
                            <article class="student-card student-stat-card" data-reveal>
                                <span class="student-stat-icon"><i class='bx bx-collection'></i></span>
                                <div class="student-stat-label">Mes projets</div>
                                <div class="student-stat-value"><?= (int) ($studentStats['mesProjets'] ?? 0) ?></div>
                                <div class="student-muted">Vos publications deja deposees sur la plateforme.</div>
                            </article>
                            <article class="student-card student-stat-card" data-reveal>
                                <span class="student-stat-icon"><i class='bx bx-heart'></i></span>
                                <div class="student-stat-label">Likes recus</div>
                                <div class="student-stat-value"><?= (int) ($studentStats['likes'] ?? 0) ?></div>
                                <div class="student-muted">Signal rapide de l'interet suscite par vos projets.</div>
                            </article>
                            <article class="student-card student-stat-card" data-reveal>
                                <span class="student-stat-icon"><i class='bx bxs-star'></i></span>
                                <div class="student-stat-label">Avis et notes</div>
                                <div class="student-stat-value"><?= (int) ($studentStats['reviews'] ?? 0) ?></div>
                                <div class="student-muted">Retours visibles pour renforcer votre credibilite.</div>
                            </article>
                            <article class="student-card student-stat-card" data-reveal>
                                <span class="student-stat-icon"><i class='bx bx-message-square-dots'></i></span>
                                <div class="student-stat-label">Messages recus</div>
                                <div class="student-stat-value"><?= (int) ($studentStats['messages'] ?? 0) ?></div>
                                <div class="student-muted">Contacts entrants autour de vos projets et de votre profil.</div>
                            </article>
                        </div>
                    </section>

                    <section class="student-project-grid mb-4">
                        <div class="student-card" data-reveal>
                            <div class="student-section-head">
                                <div>
                                    <h3>Mes projets recents</h3>
                                    <p>Retrouvez vos publications les plus recentes avec leur statut et leur niveau d'engagement.</p>
                                </div>
                                <a href="<?= ROOT ?>/Projets/mes_projets" class="student-pill"><i class='bx bx-right-arrow-alt'></i> Tout voir</a>
                            </div>

                            <?php if (!empty($projects)): ?>
                            <div class="student-project-list">
                                <?php foreach ($projects as $project): ?>
                                <?php
                                $statusText = (string) ($project['status'] ?? 'En attente');
                                $statusLower = strtolower($statusText);
                                $statusClass = str_contains($statusLower, 'valid') || str_contains($statusLower, 'publ') || str_contains($statusLower, 'accept')
                                    ? 'student-status student-status--valid'
                                    : (str_contains($statusLower, 'brouillon') || str_contains($statusLower, 'draft')
                                        ? 'student-status student-status--draft'
                                        : 'student-status student-status--pending');
                                ?>
                                <article class="student-project">
                                    <img src="<?= htmlspecialchars((string) ($project['image'] ?? (ROOT . '/assets/images/thumbs/product-img1.png'))) ?>" alt="<?= htmlspecialchars((string) ($project['title'] ?? 'Projet')) ?>">
                                    <div>
                                        <div class="d-flex justify-content-between gap-3 flex-wrap align-items-start mb-2">
                                            <div>
                                                <h4><a href="<?= ROOT ?>/Projets/detail/<?= (int) ($project['id'] ?? 0) ?>"><?= htmlspecialchars((string) ($project['title'] ?? 'Projet')) ?></a></h4>
                                                <div class="student-project-meta">
                                                    <span><i class='bx bx-category'></i> <?= htmlspecialchars((string) ($project['category'] ?? 'Sans categorie')) ?></span>
                                                    <span><i class='bx bx-calendar'></i> <?= htmlspecialchars((string) ($project['date'] ?? '')) ?></span>
                                                </div>
                                            </div>
                                            <span class="<?= $statusClass ?>"><i class='bx bx-pulse'></i> <?= htmlspecialchars($statusText) ?></span>
                                        </div>
                                        <p class="student-copy" style="margin-bottom:12px"><?= htmlspecialchars((string) ($project['excerpt'] ?? '')) ?></p>
                                        <div class="student-project-stats">
                                            <span><i class='bx bxs-star'></i> <?= number_format((float) ($project['average_rating'] ?? 0), 1) ?>/5</span>
                                            <span><i class='bx bxs-heart'></i> <?= (int) ($project['likes_count'] ?? 0) ?> likes</span>
                                            <span><i class='bx bxs-message-square-detail'></i> <?= (int) ($project['reviews_count'] ?? 0) ?> avis</span>
                                        </div>
                                        <?php if (!empty($project['technologies'])): ?>
                                        <div class="student-tech-list">
                                            <?php foreach (array_slice((array) $project['technologies'], 0, 4) as $tech): ?>
                                            <span class="student-tag"><?= htmlspecialchars((string) $tech) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </article>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <div class="student-empty">
                                <i class='bx bx-folder-open' style="font-size:2rem;color:#0f766e"></i>
                                <p class="mb-2 mt-2">Vous n'avez pas encore publie de projet.</p>
                                <a href="<?= ROOT ?>/Projets/publier_projet" class="student-pill">Publier mon premier projet</a>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="student-card student-latest" data-reveal>
                            <div class="student-section-head">
                                <div>
                                    <h3>Projet a surveiller</h3>
                                    <p>Le projet le plus recent ou le plus strategique dans votre espace personnel.</p>
                                </div>
                            </div>

                            <?php if (!empty($studentLatestProject)): ?>
                            <img class="student-latest-cover" src="<?= htmlspecialchars((string) ($studentLatestProject['image'] ?? (ROOT . '/assets/images/thumbs/product-img1.png'))) ?>" alt="<?= htmlspecialchars((string) ($studentLatestProject['title'] ?? 'Projet')) ?>">
                            <h4 style="font-size:1.25rem;font-weight:850;color:#0f172a;margin-bottom:10px"><?= htmlspecialchars((string) ($studentLatestProject['title'] ?? 'Projet')) ?></h4>
                            <div class="student-project-meta">
                                <span><i class='bx bx-category'></i> <?= htmlspecialchars((string) ($studentLatestProject['category'] ?? 'Sans categorie')) ?></span>
                                <span><i class='bx bx-calendar'></i> <?= htmlspecialchars((string) ($studentLatestProject['date'] ?? '')) ?></span>
                            </div>
                            <p class="student-copy mt-3 mb-3"><?= htmlspecialchars((string) ($studentLatestProject['excerpt'] ?? '')) ?></p>
                            <div class="student-project-stats">
                                <span><i class='bx bxs-star'></i> <?= number_format((float) ($studentLatestProject['average_rating'] ?? 0), 1) ?>/5</span>
                                <span><i class='bx bxs-heart'></i> <?= (int) ($studentLatestProject['likes_count'] ?? 0) ?> likes</span>
                                <span><i class='bx bxs-message-square-detail'></i> <?= (int) ($studentLatestProject['reviews_count'] ?? 0) ?> avis</span>
                            </div>
                            <div class="d-flex gap-2 flex-wrap mt-3">
                                <a href="<?= ROOT ?>/Projets/detail/<?= (int) ($studentLatestProject['id'] ?? 0) ?>" class="student-pill">Voir le detail</a>
                                <a href="<?= ROOT ?>/Projets/modifier/<?= (int) ($studentLatestProject['id'] ?? 0) ?>" class="student-pill">Modifier le projet</a>
                            </div>
                            <?php else: ?>
                            <div class="student-empty">
                                <i class='bx bx-rocket' style="font-size:2rem;color:#0f766e"></i>
                                <p class="mb-2 mt-2">Votre prochain projet peut devenir votre vitrine principale.</p>
                                <a href="<?= ROOT ?>/Projets/publier_projet" class="student-pill">Commencer maintenant</a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </section>

                    <section data-reveal>
                        <div class="student-section-head mb-3">
                            <div>
                                <h3>Actions rapides</h3>
                                <p>Accedez en un clic aux actions les plus utiles pour votre activite sur la plateforme.</p>
                            </div>
                        </div>
                        <div class="student-actions-grid">
                            <?php foreach ($studentActions as $action): ?>
                            <a href="<?= htmlspecialchars((string) ($action['href'] ?? '#')) ?>" class="student-action student-action--<?= htmlspecialchars((string) ($action['variant'] ?? 'soft')) ?>">
                                <span class="student-action__icon"><i class='<?= htmlspecialchars((string) ($action['icon'] ?? 'bx bx-link')) ?>'></i></span>
                                <h4 style="font-size:1.08rem;font-weight:850;color:#0f172a;margin-bottom:8px"><?= htmlspecialchars((string) ($action['title'] ?? 'Action')) ?></h4>
                                <p class="student-copy mb-0"><?= htmlspecialchars((string) ($action['text'] ?? '')) ?></p>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <section class="mt-4" data-reveal>
                        <div class="student-section-head mb-3">
                            <div>
                                <h3>Derniers messages non lus</h3>
                                <p>Vos conversations les plus urgentes apparaissent ici pour vous faire gagner du temps.</p>
                            </div>
                            <?php if ($studentUnreadMessages > 0): ?><span class="student-unread-badge"><i class='bx bx-bell'></i> <?= $studentUnreadMessages ?> non lu(s)</span><?php endif; ?>
                        </div>

                        <?php if (!empty($studentUnreadThreadsPreview)): ?>
                        <div class="student-inbox-preview">
                            <?php foreach ($studentUnreadThreadsPreview as $thread): ?>
                            <article class="student-inbox-preview__item">
                                <div class="d-flex justify-content-between gap-3 flex-wrap align-items-start">
                                    <div>
                                        <h4 style="margin:0 0 6px;color:#0f172a;font-size:1.04rem;font-weight:850"><?= htmlspecialchars((string) ($thread['visitor_name'] ?? 'Visiteur')) ?></h4>
                                        <div class="student-muted small">Projet : <a href="<?= ROOT ?>/Projets/detail/<?= (int) ($thread['project_id'] ?? 0) ?>" style="color:#0f766e;text-decoration:none"><?= htmlspecialchars((string) ($thread['project_title'] ?? 'Projet')) ?></a></div>
                                    </div>
                                    <span class="student-unread-badge"><i class='bx bx-bell'></i> <?= (int) ($thread['unread_count'] ?? 0) ?> non lu(s)</span>
                                </div>
                                <div class="student-inbox-preview__meta">
                                    <span><i class='bx bx-time-five'></i> <?= htmlspecialchars((string) ($thread['last_date'] ?? '')) ?></span>
                                    <span><i class='bx bx-layer'></i> <?= (int) ($thread['messages_count'] ?? 0) ?> message(s)</span>
                                </div>
                                <p class="student-copy mb-3"><?= htmlspecialchars((string) ($thread['last_message'] ?? '')) ?></p>
                                <a href="<?= ROOT ?>/Homes/messages_recus?status=unread&project_id=<?= (int) ($thread['project_id'] ?? 0) ?>" class="student-pill">Ouvrir dans la boite de reception</a>
                            </article>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <div class="student-empty">
                            <i class='bx bx-check-shield' style="font-size:2rem;color:#0f766e"></i>
                            <p class="mb-0 mt-2">Aucun message non lu pour le moment.</p>
                        </div>
                        <?php endif; ?>
                    </section>

                    <section class="mt-4" data-reveal>
                        <div class="student-section-head mb-3">
                            <div>
                                <h3>Commentaires des visiteurs</h3>
                                <p>Consultez les avis laisses sur vos projets et recontactez rapidement les visiteurs par WhatsApp, appel ou email.</p>
                            </div>
                        </div>

                        <?php if (!empty($studentVisitorReviews)): ?>
                        <div class="student-feedback-grid">
                            <?php foreach ($studentVisitorReviews as $feedback): ?>
                            <article class="student-card student-feedback-card">
                                <div class="student-feedback-head">
                                    <div class="student-feedback-user">
                                        <span class="student-feedback-avatar">
                                            <?php if (!empty($feedback['visitor_image'])): ?>
                                            <img src="<?= htmlspecialchars((string) $feedback['visitor_image']) ?>" alt="<?= htmlspecialchars((string) $feedback['visitor_name']) ?>">
                                            <?php else: ?>
                                            <?= htmlspecialchars(strtoupper(substr((string) ($feedback['visitor_name'] ?? 'V'), 0, 1))) ?>
                                            <?php endif; ?>
                                        </span>
                                        <div>
                                            <strong style="color:#0f172a"><?= htmlspecialchars((string) ($feedback['visitor_name'] ?? 'Visiteur')) ?></strong>
                                            <div class="student-muted small">Sur le projet <a href="<?= ROOT ?>/Projets/detail/<?= (int) ($feedback['project_id'] ?? 0) ?>" style="color:#0f766e;text-decoration:none"><?= htmlspecialchars((string) ($feedback['project_title'] ?? 'Projet')) ?></a></div>
                                        </div>
                                    </div>
                                    <div class="student-project-stats" style="margin-bottom:0">
                                        <span><i class='bx bxs-star'></i> <?= (int) ($feedback['rating'] ?? 0) ?>/5</span>
                                    </div>
                                </div>

                                <div class="student-feedback-meta">
                                    <span><i class='bx bx-calendar'></i> <?= htmlspecialchars((string) ($feedback['date'] ?? '')) ?></span>
                                    <?php if (!empty($feedback['email'])): ?><span><i class='bx bx-envelope'></i> <?= htmlspecialchars((string) $feedback['email']) ?></span><?php endif; ?>
                                    <?php if (!empty($feedback['contact'])): ?><span><i class='bx bx-phone'></i> <?= htmlspecialchars((string) $feedback['contact']) ?></span><?php endif; ?>
                                </div>

                                <p class="student-copy mb-0"><?= nl2br(htmlspecialchars((string) ($feedback['review'] ?? 'Aucun commentaire detaille.'))) ?></p>

                                <div class="student-contact-actions">
                                    <?php if (!empty($feedback['whatsapp_url'])): ?>
                                    <a class="student-contact-btn student-contact-btn--wa" href="<?= htmlspecialchars((string) $feedback['whatsapp_url']) ?>" target="_blank" rel="noopener">
                                        <i class='bx bxl-whatsapp'></i> WhatsApp
                                    </a>
                                    <?php endif; ?>
                                    <?php if (!empty($feedback['tel_url'])): ?>
                                    <a class="student-contact-btn student-contact-btn--call" href="<?= htmlspecialchars((string) $feedback['tel_url']) ?>">
                                        <i class='bx bx-phone-call'></i> Appeler
                                    </a>
                                    <?php endif; ?>
                                    <?php if (!empty($feedback['mailto_url'])): ?>
                                    <a class="student-contact-btn student-contact-btn--mail" href="<?= htmlspecialchars((string) $feedback['mailto_url']) ?>">
                                        <i class='bx bx-envelope-open'></i> Email
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </article>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <div class="student-empty">
                            <i class='bx bx-message-rounded-detail' style="font-size:2rem;color:#0f766e"></i>
                            <p class="mb-0 mt-2">Les commentaires des visiteurs apparaitront ici des qu'ils noteront ou commenteront vos projets.</p>
                        </div>
                        <?php endif; ?>
                    </section>
                </div>
            </div>

            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>

<?php $this->view('Partials/scripts'); ?>
<script>
(function() {
    const items = document.querySelectorAll('[data-reveal]');
    if (!items.length) return;

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12
    });

    items.forEach(function(item, index) {
        item.style.transitionDelay = Math.min(index * 70, 320) + 'ms';
        observer.observe(item);
    });
})();
</script>
</body>
</html>
