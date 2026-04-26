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
                :root {
                    --primary-color: #6366f1;
                    --primary-hover: #4f46e5;
                    --secondary-color: #94a3b8;
                    --success-color: #10b981;
                    --warning-color: #f59e0b;
                    --danger-color: #ef4444;
                    --info-color: #0ea5e9;
                    --bg-light: #f1f5f9;
                    --glass-bg: rgba(255, 255, 255, 0.85);
                    --text-main: #0f172a;
                    --text-muted: #64748b;
                    --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                    --card-hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                }

                .dashboard-body__content {
                    animation: fadeIn 0.5s ease-out;
                    background-color: var(--bg-light);
                }

                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }

                /* Common Card from Admin */
                .common-card {
                    border: none;
                    border-radius: 16px;
                    box-shadow: var(--card-shadow);
                    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                    background: #ffffff;
                    overflow: hidden;
                    position: relative;
                    height: 100%;
                }

                .common-card:hover {
                    transform: translateY(-5px);
                    box-shadow: var(--card-hover-shadow);
                }

                .common-card .card-body {
                    padding: 1.5rem;
                    z-index: 1;
                    position: relative;
                }

                .stat-icon {
                    position: absolute;
                    right: 1.25rem;
                    bottom: 1rem;
                    font-size: 2.5rem;
                    opacity: 0.1;
                    transition: all 0.3s;
                }

                .common-card:hover .stat-icon {
                    opacity: 0.2;
                    transform: scale(1.1) rotate(-10deg);
                }

                /* Border Accents */
                .stat-card-projects { border-top: 5px solid var(--primary-color); }
                .stat-card-likes { border-top: 5px solid var(--danger-color); }
                .stat-card-reviews { border-top: 5px solid var(--warning-color); }
                .stat-card-messages { border-top: 5px solid var(--info-color); }

                /* Hero Section */
                .student-hero {
                    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                    border-radius: 24px;
                    padding: 3rem;
                    color: white;
                    margin-bottom: 2rem;
                    position: relative;
                    overflow: hidden;
                    box-shadow: var(--card-shadow);
                }

                .student-hero::before {
                    content: '';
                    position: absolute;
                    top: -50px;
                    right: -50px;
                    width: 300px;
                    height: 300px;
                    background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
                    border-radius: 50%;
                }

                .student-hero h1 {
                    font-weight: 800;
                    font-size: 2.5rem;
                    margin-bottom: 1rem;
                    letter-spacing: -0.02em;
                }

                .student-hero p {
                    font-size: 1.1rem;
                    opacity: 0.9;
                    max-width: 700px;
                    line-height: 1.6;
                }

                .hero-stats-glass {
                    background: rgba(255, 255, 255, 0.1);
                    backdrop-filter: blur(10px);
                    border: 1px solid rgba(255, 255, 255, 0.1);
                    border-radius: 20px;
                    padding: 1.5rem;
                }

                /* Progress Ring */
                .progress-ring-container {
                    position: relative;
                    width: 100px;
                    height: 100px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .progress-ring-circle {
                    width: 100px;
                    height: 100px;
                    border-radius: 50%;
                    background: conic-gradient(var(--success-color) <?= max(0, min(100, $studentCompletionRate)) ?>%, rgba(255, 255, 255, 0.1) 0);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .progress-ring-circle::after {
                    content: '';
                    width: 80px;
                    height: 80px;
                    background: #1e293b;
                    border-radius: 50%;
                    position: absolute;
                }

                .progress-value {
                    position: relative;
                    z-index: 2;
                    font-weight: 800;
                    font-size: 1.25rem;
                }

                /* Buttons */
                .btn {
                    padding: 0.6rem 1.25rem;
                    font-weight: 700;
                    border-radius: 10px;
                    transition: all 0.3s;
                    border: none;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                }

                .btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; }
                .btn-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
                .btn-info { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; }
                .btn-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
                .btn-outline-primary { border: 2px solid var(--primary-color); color: var(--primary-color); background: transparent; }
                .btn-outline-primary:hover { background: var(--primary-color); color: white; }

                /* Project Cards */
                .project-item {
                    display: flex;
                    gap: 1.5rem;
                    padding: 1.25rem;
                    border-bottom: 1px solid #f1f5f9;
                    transition: background 0.2s;
                }

                .project-item:last-child { border-bottom: none; }
                .project-item:hover { background: #f8fafc; }

                .project-thumb {
                    width: 80px;
                    height: 80px;
                    object-fit: cover;
                    border-radius: 12px;
                    flex-shrink: 0;
                }

                .project-info h5 {
                    margin: 0 0 0.5rem;
                    font-weight: 700;
                    color: var(--text-main);
                }

                .badge {
                    padding: 0.5rem 0.75rem;
                    border-radius: 6px;
                    font-size: 0.7rem;
                    font-weight: 700;
                    text-transform: uppercase;
                }

                /* Animations */
                [data-reveal] {
                    opacity: 0;
                    transform: translateY(20px);
                    transition: all 0.6s cubic-bezier(0.22, 1, 0.36, 1);
                }

                [data-reveal].is-visible {
                    opacity: 1;
                    transform: translateY(0);
                }

                .unread-indicator {
                    width: 8px;
                    height: 8px;
                    background: var(--danger-color);
                    border-radius: 50%;
                    display: inline-block;
                }
                </style>

                <div class="student-dashboard" id="student_workspace">
                    <!-- Hero Section -->
                    <div class="student-hero" data-reveal>
                        <div class="row align-items-center g-4">
                            <div class="col-lg-8">
                                <h1 class="text-white">Bonjour, <?= htmlspecialchars($studentName) ?> !</h1>
                                <p>Bienvenue sur votre espace de gestion. Pilotez vos projets, suivez vos statistiques d'engagement et communiquez avec vos visiteurs en un seul endroit.</p>
                                <div class="mt-4 d-flex flex-wrap gap-2">
                                    <a href="<?= ROOT ?>/Projets/publier_projet" class="btn btn-primary"><i class='bx bx-plus-circle'></i> Publier un nouveau projet</a>
                                    <a href="<?= ROOT ?>/Projets/mes_projets" class="btn btn-primary" style="color: white; border-color: rgba(255,255,255,0.3); background: rgba(255,255,255,0.05);"><i class='bx bx-list-ul'></i> Mes publications</a>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="hero-stats-glass text-center d-flex flex-column align-items-center">
                                    <div class="progress-ring-container mb-3">
                                        <div class="progress-ring-circle">
                                            <span class="progress-value"><?= $studentCompletionRate ?>%</span>
                                        </div>
                                    </div>
                                    <h6 class="mb-1 fw-bold">Taux de validation</h6>
                                    <p class="small mb-0 opacity-75">Projets validés par rapport au total</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Grid -->
                    <div class="row g-4 mb-4">
                        <div class="col-xl-3 col-md-6" data-reveal>
                            <div class="common-card stat-card-projects">
                                <div class="card-body">
                                    <p class="text-muted small fw-bold text-uppercase mb-1">Publications</p>
                                    <h3 class="fw-bold mb-0"><?= (int) ($studentStats['mesProjets'] ?? 0) ?></h3>
                                    <div class="small text-muted mt-2">Plus <?= (int) ($studentStats['enAttente'] ?? 0) ?> en attente</div>
                                    <div class="stat-icon">📁</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6" data-reveal>
                            <div class="common-card stat-card-likes">
                                <div class="card-body">
                                    <p class="text-muted small fw-bold text-uppercase mb-1">Likes reçus</p>
                                    <h3 class="fw-bold mb-0">❤️ <?= (int) ($studentStats['likes'] ?? 0) ?></h3>
                                    <div class="small text-muted mt-2">Engagement total</div>
                                    <div class="stat-icon">❤️</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6" data-reveal>
                            <div class="common-card stat-card-reviews">
                                <div class="card-body">
                                    <p class="text-muted small fw-bold text-uppercase mb-1">Avis & Notes</p>
                                    <h3 class="fw-bold mb-0">⭐ <?= (int) ($studentStats['reviews'] ?? 0) ?></h3>
                                    <div class="small text-muted mt-2">Retour des visiteurs</div>
                                    <div class="stat-icon">💬</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6" data-reveal>
                            <div class="common-card stat-card-messages">
                                <div class="card-body">
                                    <p class="text-muted small fw-bold text-uppercase mb-1">Messages</p>
                                    <h3 class="fw-bold mb-0"><?= (int) ($studentStats['messages'] ?? 0) ?></h3>
                                    <div class="small text-muted mt-2"><?= $studentUnreadMessages ?> non lus</div>
                                    <div class="stat-icon">✉️</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 overflow-hidden">
                        <!-- Recent Projects -->
                        <div class="col-lg-8" data-reveal>
                            <div class="common-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h5 class="fw-bold m-0">🚀 Mes projets récents</h5>
                                        <a href="<?= ROOT ?>/Projets/mes_projets" class="btn btn-sm btn-primary">Voir tout</a>
                                    </div>

                                    <?php if (!empty($projects)): ?>
                                        <div class="project-list">
                                            <?php foreach ($projects as $project): ?>
                                                <?php
                                                $statusText = (string) ($project['status'] ?? 'En attente');
                                                $statusLower = strtolower($statusText);
                                                $badgeClass = 'bg-warning text-dark';
                                                if (str_contains($statusLower, 'valid') || str_contains($statusLower, 'publ')) $badgeClass = 'bg-success text-white';
                                                elseif (str_contains($statusLower, 'draft') || str_contains($statusLower, 'brouillon')) $badgeClass = 'bg-secondary text-white';
                                                ?>
                                                <div class="project-item">
                                                    <img src="<?= htmlspecialchars((string) ($project['image'] ?? (ROOT . '/assets/images/thumbs/product-img1.png'))) ?>" class="project-thumb">
                                                    <div class="project-info flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h5><a href="<?= ROOT ?>/Projets/detail/<?= (int) ($project['id'] ?? 0) ?>" class="text-decoration-none text-dark"><?= htmlspecialchars((string) ($project['title'] ?? 'Projet')) ?></a></h5>
                                                                <div class="d-flex gap-3 small text-muted">
                                                                    <span><i class='bx bx-category'></i> <?= htmlspecialchars((string) ($project['category'] ?? 'Sans catégorie')) ?></span>
                                                                    <span><i class='bx bx-calendar'></i> <?= htmlspecialchars((string) ($project['date'] ?? '')) ?></span>
                                                                </div>
                                                            </div>
                                                            <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                                                        </div>
                                                        <div class="mt-2 d-flex gap-3 small">
                                                            <span class="fw-bold">❤️ <?= (int) ($project['likes_count'] ?? 0) ?></span>
                                                            <span class="fw-bold">💬 <?= (int) ($project['reviews_count'] ?? 0) ?> avis</span>
                                                            <span class="fw-bold text-primary">⭐ <?= number_format((float) ($project['average_rating'] ?? 0), 1) ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-5">
                                            <div class="mb-3" style="font-size: 3rem; opacity: 0.2;">📂</div>
                                            <p class="text-muted">Vous n'avez pas encore publié de projet.</p>
                                            <a href="<?= ROOT ?>/Projets/publier_projet" class="btn btn-primary btn-sm">Commencer maintenant</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="col-lg-4" data-reveal>
                            <div class="common-card mb-4 border-start border-primary border-4">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">⚡ Actions rapides</h5>
                                    <div class="d-grid gap-2">
                                        <?php foreach ($studentActions as $action): ?>
                                            <a href="<?= htmlspecialchars((string) ($action['href'] ?? '#')) ?>" class="btn btn-primary text-start justify-content-start p-3 w-100">
                                                <i class='<?= htmlspecialchars((string) ($action['icon'] ?? 'bx bx-link')) ?>' style="font-size: 1.25rem;"></i>
                                                <span><?= htmlspecialchars((string) ($action['title'] ?? 'Action')) ?></span>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Unread Messages -->
                            <div class="common-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-bold m-0">✉️ Messages récents</h5>
                                        <?php if ($studentUnreadMessages > 0): ?>
                                            <span class="badge bg-danger"><?= $studentUnreadMessages ?> non lus</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (!empty($studentUnreadThreadsPreview)): ?>
                                        <?php foreach (array_slice($studentUnreadThreadsPreview, 0, 3) as $thread): ?>
                                            <div class="mb-3 p-2 rounded hover-bg-light border-bottom">
                                                <div class="d-flex justify-content-between">
                                                    <span class="fw-bold small"><?= htmlspecialchars((string) ($thread['visitor_name'] ?? 'Visiteur')) ?></span>
                                                    <span class="small text-muted"><?= htmlspecialchars((string) ($thread['last_date'] ?? '')) ?></span>
                                                </div>
                                                <p class="small text-muted text-truncate mb-1" style="max-width: 250px;"><?= htmlspecialchars((string) ($thread['last_message'] ?? '')) ?></p>
                                                <a href="<?= ROOT ?>/Homes/messages_recus?project_id=<?= (int) ($thread['project_id'] ?? 0) ?>" class="text-primary text-decoration-none small fw-bold">Répondre</a>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-muted small text-center py-3">Aucun message non lu.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Section -->
                    <div class="row mt-4">
                        <div class="col-12" data-reveal>
                            <div class="common-card">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-4">💬 Derniers commentaires des visiteurs</h5>
                                    
                                    <?php if (!empty($studentVisitorReviews)): ?>
                                        <div class="row g-4">
                                            <?php foreach ($studentVisitorReviews as $feedback): ?>
                                                <div class="col-md-6">
                                                    <div class="p-3 border rounded-4 h-100 position-relative">
                                                        <div class="d-flex gap-3 align-items-center mb-2">
                                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                                                <?= htmlspecialchars(strtoupper(substr((string) ($feedback['visitor_name'] ?? 'V'), 0, 1))) ?>
                                                            </div>
                                                            <div>
                                                                <h6 class="fw-bold m-0"><?= htmlspecialchars((string) ($feedback['visitor_name'] ?? 'Visiteur')) ?></h6>
                                                                <span class="small text-muted">Sur <?= htmlspecialchars((string) ($feedback['project_title'] ?? 'Projet')) ?></span>
                                                            </div>
                                                            <div class="ms-auto text-warning fw-bold">
                                                                ⭐ <?= (int) ($feedback['rating'] ?? 0) ?>/5
                                                            </div>
                                                        </div>
                                                        <p class="small text-dark mb-3">"<?= nl2br(htmlspecialchars((string) ($feedback['review'] ?? ''))) ?>"</p>
                                                        <div class="d-flex flex-wrap gap-2 mt-auto">
                                                            <?php if (!empty($feedback['whatsapp_url'])): ?>
                                                                <a href="<?= htmlspecialchars((string) $feedback['whatsapp_url']) ?>" target="_blank" class="btn btn-sm btn-success py-1 px-2" style="font-size: 0.75rem;"><i class='bx bxl-whatsapp'></i> WhatsApp</a>
                                                            <?php endif; ?>
                                                            <?php if (!empty($feedback['tel_url'])): ?>
                                                                <a href="<?= htmlspecialchars((string) $feedback['tel_url']) ?>" class="btn btn-sm btn-info py-1 px-2" style="font-size: 0.75rem;"><i class='bx bx-phone-call'></i> Appeler</a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted text-center py-4">Pas encore d'avis sur vos projets.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
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

