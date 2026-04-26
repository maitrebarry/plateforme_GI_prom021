<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Messages visiteurs']); ?>
<?php
$studentMessageThreads = $studentMessageThreads ?? [];
$studentVisitorReviews = $studentVisitorReviews ?? [];
$studentProjectsForFilter = $studentProjectsForFilter ?? [];
$messageFilterProjectId = $messageFilterProjectId ?? null;
$messageFilterSearch = $messageFilterSearch ?? '';
$messageFilterStatus = $messageFilterStatus ?? 'all';
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

                .common-card {
                    border: none;
                    border-radius: 16px;
                    box-shadow: var(--card-shadow);
                    background: #ffffff;
                    overflow: hidden;
                    position: relative;
                    margin-bottom: 2rem;
                }

                .common-card .card-body { padding: 1.5rem; }

                /* Hero Section */
                .student-hero {
                    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                    border-radius: 20px;
                    padding: 2.5rem;
                    color: white;
                    margin-bottom: 2rem;
                    position: relative;
                    overflow: hidden;
                    box-shadow: var(--card-shadow);
                }

                .student-hero h1 { font-weight: 800; font-size: 2.2rem; margin-bottom: 0.75rem; }
                .student-hero p { font-size: 1.1rem; opacity: 0.9; max-width: 800px; }

                /* Filters */
                .filter-bar {
                    background: #fff;
                    padding: 1.25rem;
                    border-radius: 12px;
                    box-shadow: var(--card-shadow);
                    margin-bottom: 2rem;
                }

                .form-control, .form-select {
                    border-radius: 10px;
                    border: 1px solid #e2e8f0;
                    padding: 0.6rem 1rem;
                }

                /* Thread Card */
                .thread-card {
                    border-left: 4px solid var(--secondary-color);
                    transition: all 0.3s;
                }

                .thread-card.unread { border-left-color: var(--danger-color); }
                .thread-card:hover { transform: translateX(5px); }

                .message-bubble {
                    background: #f8fafc;
                    border-radius: 14px;
                    padding: 1rem;
                    margin-bottom: 1rem;
                    border: 1px solid #f1f5f9;
                }

                .message-bubble.sent {
                    background: #eff6ff;
                    border-color: #dbeafe;
                }

                /* Buttons */
                .btn {
                    padding: 0.6rem 1.25rem;
                    font-weight: 700;
                    border-radius: 10px;
                    transition: all 0.3s;
                    border: none;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                }

                .btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; }
                .btn-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
                .btn-info { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; }
                .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.85rem; }

                /* Badges */
                .badge { padding: 0.5rem 0.75rem; border-radius: 6px; font-weight: 700; }

                [data-reveal] {
                    opacity: 0;
                    transform: translateY(20px);
                    transition: all 0.6s cubic-bezier(0.22, 1, 0.36, 1);
                }

                [data-reveal].is-visible {
                    opacity: 1;
                    transform: translateY(0);
                }
                </style>

                <div class="student-inbox-container">
                    <!-- Hero Section -->
                    <div class="student-hero" data-reveal>
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <span class="badge bg-primary bg-opacity-25 text-white">Inbox</span>
                        </div>
                        <h1 class="text-white">Messagerie & Commentaires</h1>
                        <p>Gérez les interactions avec vos visiteurs. Répondez aux messages et suivez les avis laissés sur vos projets pour améliorer votre visibilité.</p>
                    </div>

                    <!-- Filters Section -->
                    <div class="filter-bar" data-reveal>
                        <form method="get" class="row g-3">
                            <div class="col-lg-4">
                                <label class="small fw-bold text-muted mb-1">Rechercher</label>
                                <input type="text" name="search" class="form-control" value="<?= htmlspecialchars((string) $messageFilterSearch) ?>" placeholder="Visiteur, projet...">
                            </div>
                            <div class="col-lg-3">
                                <label class="small fw-bold text-muted mb-1">Projet</label>
                                <select name="project_id" class="form-select">
                                    <option value="">Tous les projets</option>
                                    <?php foreach ($studentProjectsForFilter as $projectOption): ?>
                                    <option value="<?= (int) ($projectOption['id'] ?? 0) ?>" <?= ((int) ($messageFilterProjectId ?? 0) === (int) ($projectOption['id'] ?? 0)) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars((string) ($projectOption['title'] ?? 'Projet')) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label class="small fw-bold text-muted mb-1">Statut</label>
                                <select name="status" class="form-select">
                                    <option value="all" <?= $messageFilterStatus === 'all' ? 'selected' : '' ?>>Tout afficher</option>
                                    <option value="unread" <?= $messageFilterStatus === 'unread' ? 'selected' : '' ?>>Non lus</option>
                                    <option value="read" <?= $messageFilterStatus === 'read' ? 'selected' : '' ?>>Lus uniquement</option>
                                </select>
                            </div>
                            <div class="col-lg-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100 justify-content-center">Filtrer</button>
                            </div>
                        </form>
                    </div>

                    <div class="row g-4 mt-2">
                        <!-- Conversations List -->
                        <div class="col-lg-8">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="fw-bold m-0"><i class='bx bx-chat'></i> Conversations récentes</h4>
                                <span class="badge bg-info text-white"><?= count($studentMessageThreads) ?> threads</span>
                            </div>

                            <?php if (!empty($studentMessageThreads)): ?>
                                <?php foreach ($studentMessageThreads as $thread): ?>
                                    <div class="common-card thread-card <?= !empty($thread['is_unread']) ? 'unread' : '' ?>" data-reveal>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h5 class="fw-bold m-0 text-primary"><?= htmlspecialchars((string) ($thread['visitor_name'] ?? 'Visiteur')) ?></h5>
                                                    <p class="small text-muted mb-0">Sur le projet : <span class="fw-bold"><?= htmlspecialchars((string) ($thread['project_title'] ?? 'Projet')) ?></span></p>
                                                </div>
                                                <div class="text-end">
                                                    <span class="small text-muted d-block mb-1"><?= htmlspecialchars((string) ($thread['last_date'] ?? '')) ?></span>
                                                    <?php if (!empty($thread['is_unread'])): ?>
                                                        <span class="badge bg-danger">Nouveau</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div class="messages-preview mb-3">
                                                <?php foreach (($thread['messages_preview'] ?? []) as $preview): ?>
                                                    <div class="message-bubble <?= ($preview['direction'] ?? '') === 'sent' ? 'sent' : '' ?>">
                                                        <div class="d-flex justify-content-between mb-1 small opacity-75">
                                                            <span><?= ($preview['direction'] ?? '') === 'sent' ? 'Moi' : 'Visiteur' ?></span>
                                                            <span><?= htmlspecialchars((string) ($preview['date'] ?? '')) ?></span>
                                                        </div>
                                                        <?= nl2br(htmlspecialchars((string) ($preview['message'] ?? ''))) ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                            <div class="d-flex flex-wrap gap-2 mb-4">
                                                <?php if (!empty($thread['whatsapp_url'])): ?>
                                                    <a href="<?= htmlspecialchars((string) $thread['whatsapp_url']) ?>" target="_blank" class="btn btn-sm btn-success"><i class='bx bxl-whatsapp'></i> WhatsApp</a>
                                                <?php endif; ?>
                                                <?php if (!empty($thread['tel_url'])): ?>
                                                    <a href="<?= htmlspecialchars((string) $thread['tel_url']) ?>" class="btn btn-sm btn-info"><i class='bx bx-phone-call'></i> Appeler</a>
                                                <?php endif; ?>
                                                <a href="<?= ROOT ?>/Projets/detail/<?= (int) ($thread['project_id'] ?? 0) ?>" class="btn btn-sm btn-primary"><i class='bx bx-link-external'></i> Page projet</a>
                                            </div>

                                            <!-- Quick Reply -->
                                            <form method="post" class="border-top pt-3">
                                                <input type="hidden" name="action" value="send_thread_reply">
                                                <input type="hidden" name="project_id" value="<?= (int) ($thread['project_id'] ?? 0) ?>">
                                                <input type="hidden" name="receiver_id" value="<?= (int) ($thread['visitor_id'] ?? 0) ?>">
                                                <div class="input-group">
                                                    <textarea name="message" class="form-control" placeholder="Répondre ici..." rows="2" required></textarea>
                                                    <button type="submit" class="btn btn-primary"><i class='bx bx-send'></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="common-card text-center py-5">
                                    <div class="mb-3" style="font-size: 3rem; opacity: 0.2;">✉️</div>
                                    <p class="text-muted">Aucune conversation trouvée.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Side Column: Reviews -->
                        <div class="col-lg-4">
                            <h4 class="fw-bold mb-4"><i class='bx bxs-star'></i> Avis reçus</h4>

                            <?php if (!empty($studentVisitorReviews)): ?>
                                <?php foreach ($studentVisitorReviews as $feedback): ?>
                                    <div class="common-card" data-reveal>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                    <?= htmlspecialchars(strtoupper(substr((string) ($feedback['visitor_name'] ?? 'V'), 0, 1))) ?>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold m-0"><?= htmlspecialchars((string) ($feedback['visitor_name'] ?? 'Visiteur')) ?></h6>
                                                    <span class="small text-muted"><?= htmlspecialchars((string) ($feedback['date'] ?? '')) ?></span>
                                                </div>
                                            </div>
                                            <div class="text-warning mb-2" style="font-size: 0.9rem;">
                                                <?php for($i=1; $i<=5; $i++): ?>
                                                    <i class='bx <?= $i <= (int)($feedback['rating'] ?? 0) ? 'bxs-star' : 'bx-star' ?>'></i>
                                                <?php endfor; ?>
                                            </div>
                                            <p class="small mb-3">"<?= htmlspecialchars((string) ($feedback['review'] ?? '')) ?>"</p>
                                            <p class="small text-muted mb-3 border-top pt-2">Sur : <span class="fw-bold"><?= htmlspecialchars((string) ($feedback['project_title'] ?? '')) ?></span></p>
                                            
                                            <div class="d-flex gap-2">
                                                <?php if (!empty($feedback['whatsapp_url'])): ?>
                                                    <a href="<?= htmlspecialchars((string) $feedback['whatsapp_url']) ?>" target="_blank" class="btn btn-sm btn-success p-1 rounded-circle" title="WhatsApp"><i class='bx bxl-whatsapp'></i></a>
                                                <?php endif; ?>
                                                <?php if (!empty($feedback['tel_url'])): ?>
                                                    <a href="<?= htmlspecialchars((string) $feedback['tel_url']) ?>" class="btn btn-sm btn-info p-1 rounded-circle" title="Appeler"><i class='bx bx-phone-call'></i></a>
                                                <?php endif; ?>
                                                <?php if (!empty($feedback['mailto_url'])): ?>
                                                    <a href="<?= htmlspecialchars((string) $feedback['mailto_url']) ?>" class="btn btn-sm btn-warning p-1 rounded-circle" title="Email"><i class='bx bx-envelope'></i></a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted small text-center">Aucun avis pour le moment.</p>
                            <?php endif; ?>
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
        item.style.transitionDelay = Math.min(index * 65, 360) + 'ms';
        observer.observe(item);
    });
})();
</script>
</body>
</html>

