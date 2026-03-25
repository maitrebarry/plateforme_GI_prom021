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
                .student-inbox {
                    --si-pri: #0f766e;
                    --si-ink: #0f172a;
                    --si-muted: #475569;
                    --si-line: #dbe4ee;
                }

                .student-inbox-card,
                .student-inbox-thread,
                .student-inbox-review {
                    background: #fff;
                    border: 1px solid var(--si-line);
                    border-radius: 28px;
                    box-shadow: 0 24px 60px -42px rgba(15, 23, 42, .28);
                }

                .student-inbox-card,
                .student-inbox-thread,
                .student-inbox-review {
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

                .student-inbox-card {
                    padding: 24px;
                }

                .student-inbox-hero {
                    padding: 28px;
                    background:
                        radial-gradient(circle at top right, rgba(20, 184, 166, .16), transparent 28%),
                        linear-gradient(145deg, #ffffff 0%, #f4fffd 100%);
                }

                .student-inbox-kicker,
                .student-inbox-pill,
                .student-contact-btn,
                .student-thread-tag {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    border-radius: 999px;
                }

                .student-inbox-kicker {
                    padding: 9px 16px;
                    background: #ecfeff;
                    color: #115e59;
                    font-weight: 900;
                    letter-spacing: .05em;
                    text-transform: uppercase;
                    font-size: .78rem;
                }

                .student-inbox-title {
                    font-size: clamp(2rem, 4vw, 3rem);
                    line-height: 1.03;
                    font-weight: 900;
                    color: var(--si-ink);
                    margin: 18px 0 12px;
                }

                .student-inbox-copy,
                .student-inbox-muted {
                    color: var(--si-muted);
                }

                .student-inbox-grid {
                    display: grid;
                    grid-template-columns: 1.08fr .92fr;
                    gap: 18px;
                    margin-top: 22px;
                }

                .student-inbox-head {
                    display: flex;
                    justify-content: space-between;
                    gap: 16px;
                    flex-wrap: wrap;
                    margin-bottom: 18px;
                }

                .student-inbox-head h3 {
                    margin: 0;
                    color: var(--si-ink);
                    font-size: 1.28rem;
                    font-weight: 850;
                }

                .student-inbox-pill {
                    padding: 10px 14px;
                    background: #f0fdfa;
                    color: #115e59;
                    font-weight: 800;
                    text-decoration: none;
                }

                .student-inbox-list {
                    display: grid;
                    gap: 14px;
                }

                .student-inbox-filters {
                    display: grid;
                    grid-template-columns: 1.1fr .9fr .7fr auto;
                    gap: 12px;
                    margin-bottom: 18px;
                }

                .student-filter-input,
                .student-filter-select {
                    width: 100%;
                    min-height: 50px;
                    border: 1px solid var(--si-line);
                    border-radius: 18px;
                    padding: 12px 14px;
                    background: #f8fafc;
                }

                .student-filter-btn {
                    min-height: 50px;
                    border: none;
                    border-radius: 18px;
                    padding: 0 16px;
                    font-weight: 800;
                    color: #fff;
                    background: linear-gradient(135deg, #0f766e, #14b8a6);
                }

                .student-inbox-thread,
                .student-inbox-review {
                    padding: 18px;
                }

                .student-thread-top,
                .student-thread-meta,
                .student-contact-actions,
                .student-thread-preview-row,
                .student-review-meta {
                    display: flex;
                    gap: 10px;
                    flex-wrap: wrap;
                }

                .student-thread-top {
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 10px;
                }

                .student-thread-tag {
                    padding: 7px 12px;
                    background: #f8fafc;
                    border: 1px solid var(--si-line);
                    font-weight: 800;
                    color: #334155;
                    font-size: .84rem;
                }

                .student-thread-tag--received {
                    background: #ecfdf5;
                    border-color: #bbf7d0;
                    color: #166534;
                }

                .student-thread-tag--sent {
                    background: #eff6ff;
                    border-color: #bfdbfe;
                    color: #1d4ed8;
                }

                .student-thread-tag--unread {
                    background: #fef2f2;
                    border-color: #fecaca;
                    color: #b91c1c;
                }

                .student-thread-preview {
                    padding: 12px 14px;
                    border-radius: 18px;
                    background: #f8fafc;
                    border: 1px solid var(--si-line);
                }

                .student-thread-preview-row {
                    justify-content: space-between;
                    color: var(--si-muted);
                    font-size: .86rem;
                    margin-bottom: 6px;
                }

                .student-contact-actions {
                    margin-top: 16px;
                }

                .student-reply-form {
                    margin-top: 16px;
                    padding-top: 16px;
                    border-top: 1px solid var(--si-line);
                }

                .student-reply-input {
                    width: 100%;
                    min-height: 100px;
                    border: 1px solid var(--si-line);
                    border-radius: 18px;
                    padding: 12px 14px;
                    background: #f8fafc;
                }

                .student-reply-submit {
                    margin-top: 12px;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    border: none;
                    border-radius: 999px;
                    padding: 11px 16px;
                    font-weight: 800;
                    color: #fff;
                    background: linear-gradient(135deg, #0f766e, #14b8a6);
                }

                .student-contact-btn {
                    padding: 10px 14px;
                    text-decoration: none;
                    font-weight: 800;
                    border: 1px solid var(--si-line);
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

                .student-empty {
                    padding: 26px;
                    border-radius: 22px;
                    border: 1px dashed var(--si-line);
                    background: #f8fafc;
                    color: var(--si-muted);
                    text-align: center;
                }

                @media (max-width: 1199px) {
                    .student-inbox-grid {
                        grid-template-columns: 1fr;
                    }

                    .student-inbox-filters {
                        grid-template-columns: 1fr;
                    }
                }
                </style>

                <div class="student-inbox">
                    <section class="student-inbox-card student-inbox-hero mb-4" data-reveal>
                        <span class="student-inbox-kicker"><i class='bx bx-message-detail'></i> Messagerie visiteurs</span>
                        <h1 class="student-inbox-title">Centralisez les conversations et les retours autour de vos projets.</h1>
                        <p class="student-inbox-copy mb-0">Cette page vous permet de suivre les messages recus sur le site, les commentaires des visiteurs et de les recontacter rapidement via WhatsApp, appel ou email.</p>
                    </section>

                    <section class="student-inbox-grid">
                        <div class="student-inbox-card" data-reveal>
                            <div class="student-inbox-head">
                                <div>
                                    <h3>Conversations du site</h3>
                                    <p class="student-inbox-copy mb-0">Messages envoyes depuis les pages detail projet.</p>
                                </div>
                                <span class="student-inbox-pill"><i class='bx bx-chat'></i> <?= count($studentMessageThreads) ?> conversation(s)</span>
                            </div>

                            <form method="get" class="student-inbox-filters">
                                <input type="text" name="search" class="student-filter-input" value="<?= htmlspecialchars((string) $messageFilterSearch) ?>" placeholder="Rechercher un visiteur, un projet ou un message...">
                                <select name="project_id" class="student-filter-select">
                                    <option value="">Tous les projets</option>
                                    <?php foreach ($studentProjectsForFilter as $projectOption): ?>
                                    <option value="<?= (int) ($projectOption['id'] ?? 0) ?>" <?= ((int) ($messageFilterProjectId ?? 0) === (int) ($projectOption['id'] ?? 0)) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars((string) ($projectOption['title'] ?? 'Projet')) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="status" class="student-filter-select">
                                    <option value="all" <?= $messageFilterStatus === 'all' ? 'selected' : '' ?>>Tous</option>
                                    <option value="unread" <?= $messageFilterStatus === 'unread' ? 'selected' : '' ?>>Non lus</option>
                                    <option value="read" <?= $messageFilterStatus === 'read' ? 'selected' : '' ?>>Lus</option>
                                </select>
                                <button type="submit" class="student-filter-btn"><i class='bx bx-filter-alt'></i> Filtrer</button>
                            </form>

                            <?php if (!empty($studentMessageThreads)): ?>
                            <div class="student-inbox-list">
                                <?php foreach ($studentMessageThreads as $thread): ?>
                                <article class="student-inbox-thread" data-reveal>
                                    <div class="student-thread-top">
                                        <div>
                                            <h4 style="margin:0 0 6px;color:#0f172a;font-size:1.08rem;font-weight:850"><?= htmlspecialchars((string) ($thread['visitor_name'] ?? 'Visiteur')) ?></h4>
                                            <div class="student-inbox-muted small">A propos du projet <a href="<?= ROOT ?>/Projets/detail/<?= (int) ($thread['project_id'] ?? 0) ?>" style="color:#0f766e;text-decoration:none"><?= htmlspecialchars((string) ($thread['project_title'] ?? 'Projet')) ?></a></div>
                                        </div>
                                        <span class="student-thread-tag <?= ($thread['last_direction'] ?? '') === 'sent' ? 'student-thread-tag--sent' : 'student-thread-tag--received' ?>">
                                            <i class='bx <?= ($thread['last_direction'] ?? '') === 'sent' ? 'bx-reply' : 'bx-message-rounded-dots' ?>'></i>
                                            <?= ($thread['last_direction'] ?? '') === 'sent' ? 'Derniere reponse envoyee' : 'Dernier message recu' ?>
                                        </span>
                                        <?php if (!empty($thread['is_unread'])): ?>
                                        <span class="student-thread-tag student-thread-tag--unread">
                                            <i class='bx bx-bell'></i> <?= (int) ($thread['unread_count'] ?? 0) ?> non lu(s)
                                        </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="student-thread-meta student-inbox-muted small" style="margin-bottom:12px">
                                        <span><i class='bx bx-time-five'></i> <?= htmlspecialchars((string) ($thread['last_date'] ?? '')) ?></span>
                                        <span><i class='bx bx-layer'></i> <?= (int) ($thread['messages_count'] ?? 0) ?> message(s)</span>
                                        <?php if (!empty($thread['email'])): ?><span><i class='bx bx-envelope'></i> <?= htmlspecialchars((string) $thread['email']) ?></span><?php endif; ?>
                                        <?php if (!empty($thread['contact'])): ?><span><i class='bx bx-phone'></i> <?= htmlspecialchars((string) $thread['contact']) ?></span><?php endif; ?>
                                    </div>

                                    <?php foreach (($thread['messages_preview'] ?? []) as $preview): ?>
                                    <div class="student-thread-preview" style="margin-bottom:10px">
                                        <div class="student-thread-preview-row">
                                            <span><?= ($preview['direction'] ?? '') === 'sent' ? 'Vous' : 'Visiteur' ?></span>
                                            <span><?= htmlspecialchars((string) ($preview['date'] ?? '')) ?></span>
                                        </div>
                                        <div><?= nl2br(htmlspecialchars((string) ($preview['message'] ?? ''))) ?></div>
                                    </div>
                                    <?php endforeach; ?>

                                    <div class="student-contact-actions">
                                        <a class="student-contact-btn" href="<?= ROOT ?>/Projets/detail/<?= (int) ($thread['project_id'] ?? 0) ?>"><i class='bx bx-link-external'></i> Ouvrir la discussion</a>
                                        <?php if (!empty($thread['whatsapp_url'])): ?><a class="student-contact-btn student-contact-btn--wa" href="<?= htmlspecialchars((string) $thread['whatsapp_url']) ?>" target="_blank" rel="noopener"><i class='bx bxl-whatsapp'></i> WhatsApp</a><?php endif; ?>
                                        <?php if (!empty($thread['tel_url'])): ?><a class="student-contact-btn student-contact-btn--call" href="<?= htmlspecialchars((string) $thread['tel_url']) ?>"><i class='bx bx-phone-call'></i> Appeler</a><?php endif; ?>
                                        <?php if (!empty($thread['mailto_url'])): ?><a class="student-contact-btn student-contact-btn--mail" href="<?= htmlspecialchars((string) $thread['mailto_url']) ?>"><i class='bx bx-envelope-open'></i> Email</a><?php endif; ?>
                                        <?php if (!empty($thread['is_unread'])): ?>
                                        <form method="post" style="display:inline-flex">
                                            <input type="hidden" name="action" value="mark_thread_read">
                                            <input type="hidden" name="project_id" value="<?= (int) ($thread['project_id'] ?? 0) ?>">
                                            <input type="hidden" name="visitor_id" value="<?= (int) ($thread['visitor_id'] ?? 0) ?>">
                                            <button type="submit" class="student-contact-btn"><i class='bx bx-check-double'></i> Marquer comme lu</button>
                                        </form>
                                        <?php endif; ?>
                                    </div>

                                    <form method="post" class="student-reply-form">
                                        <input type="hidden" name="action" value="send_thread_reply">
                                        <input type="hidden" name="project_id" value="<?= (int) ($thread['project_id'] ?? 0) ?>">
                                        <input type="hidden" name="receiver_id" value="<?= (int) ($thread['visitor_id'] ?? 0) ?>">
                                        <label class="form-label fw-semibold" style="color:#0f172a">Repondre depuis cette boite de reception</label>
                                        <textarea name="message" class="student-reply-input" placeholder="Ecrivez votre reponse au visiteur..." required></textarea>
                                        <button type="submit" class="student-reply-submit"><i class='bx bx-send'></i> Envoyer la reponse</button>
                                    </form>
                                </article>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <div class="student-empty">
                                <i class='bx bx-message-rounded' style="font-size:2rem;color:#0f766e"></i>
                                <p class="mb-0 mt-2">Aucune conversation visiteur pour le moment.</p>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="student-inbox-card" data-reveal>
                            <div class="student-inbox-head">
                                <div>
                                    <h3>Commentaires visiteurs</h3>
                                    <p class="student-inbox-copy mb-0">Avis laisses par les visiteurs sur vos projets.</p>
                                </div>
                                <span class="student-inbox-pill"><i class='bx bxs-star'></i> <?= count($studentVisitorReviews) ?> avis</span>
                            </div>

                            <?php if (!empty($studentVisitorReviews)): ?>
                            <div class="student-inbox-list">
                                <?php foreach ($studentVisitorReviews as $feedback): ?>
                                <article class="student-inbox-review" data-reveal>
                                    <div class="student-thread-top">
                                        <div>
                                            <h4 style="margin:0 0 6px;color:#0f172a;font-size:1.04rem;font-weight:850"><?= htmlspecialchars((string) ($feedback['visitor_name'] ?? 'Visiteur')) ?></h4>
                                            <div class="student-inbox-muted small">Projet : <a href="<?= ROOT ?>/Projets/detail/<?= (int) ($feedback['project_id'] ?? 0) ?>" style="color:#0f766e;text-decoration:none"><?= htmlspecialchars((string) ($feedback['project_title'] ?? 'Projet')) ?></a></div>
                                        </div>
                                        <span class="student-thread-tag"><i class='bx bxs-star'></i> <?= (int) ($feedback['rating'] ?? 0) ?>/5</span>
                                    </div>

                                    <div class="student-review-meta student-inbox-muted small" style="margin-bottom:12px">
                                        <span><i class='bx bx-calendar'></i> <?= htmlspecialchars((string) ($feedback['date'] ?? '')) ?></span>
                                        <?php if (!empty($feedback['email'])): ?><span><i class='bx bx-envelope'></i> <?= htmlspecialchars((string) $feedback['email']) ?></span><?php endif; ?>
                                        <?php if (!empty($feedback['contact'])): ?><span><i class='bx bx-phone'></i> <?= htmlspecialchars((string) $feedback['contact']) ?></span><?php endif; ?>
                                    </div>

                                    <p class="student-inbox-copy mb-0"><?= nl2br(htmlspecialchars((string) ($feedback['review'] ?? 'Aucun commentaire detaille.'))) ?></p>

                                    <div class="student-contact-actions">
                                        <?php if (!empty($feedback['whatsapp_url'])): ?><a class="student-contact-btn student-contact-btn--wa" href="<?= htmlspecialchars((string) $feedback['whatsapp_url']) ?>" target="_blank" rel="noopener"><i class='bx bxl-whatsapp'></i> WhatsApp</a><?php endif; ?>
                                        <?php if (!empty($feedback['tel_url'])): ?><a class="student-contact-btn student-contact-btn--call" href="<?= htmlspecialchars((string) $feedback['tel_url']) ?>"><i class='bx bx-phone-call'></i> Appeler</a><?php endif; ?>
                                        <?php if (!empty($feedback['mailto_url'])): ?><a class="student-contact-btn student-contact-btn--mail" href="<?= htmlspecialchars((string) $feedback['mailto_url']) ?>"><i class='bx bx-envelope-open'></i> Email</a><?php endif; ?>
                                    </div>
                                </article>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <div class="student-empty">
                                <i class='bx bx-star' style="font-size:2rem;color:#0f766e"></i>
                                <p class="mb-0 mt-2">Aucun commentaire visiteur pour le moment.</p>
                            </div>
                            <?php endif; ?>
                        </div>
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
        item.style.transitionDelay = Math.min(index * 65, 360) + 'ms';
        observer.observe(item);
    });
})();
</script>
</body>
</html>
