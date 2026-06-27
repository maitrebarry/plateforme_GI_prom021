<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Messages visiteurs']); ?>
<?php
$studentMessageThreads = $studentMessageThreads ?? [];
$studentVisitorReviews = $studentVisitorReviews ?? [];
$studentProjectsForFilter = $studentProjectsForFilter ?? [];
$messageFilterProjectId = $messageFilterProjectId ?? null;
$messageFilterSearch = $messageFilterSearch ?? '';
$messageFilterStatus = $messageFilterStatus ?? 'all';
$csrf = (string) ($_SESSION['csrf_token'] ?? '');
?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>

            <div class="dashboard-body__content p-3 p-lg-4">
                <?php $this->view('set_flash'); ?>

                <style>
                    .sm-hero { position: relative; overflow: hidden; background: linear-gradient(135deg, var(--ds-brand-700), var(--ds-brand-800)); border-radius: var(--ds-radius-xl); padding: 24px; color: #fff; margin-bottom: 20px; }
                    .sm-hero::before { content: ''; position: absolute; top: -70px; right: -50px; width: 260px; height: 260px; border-radius: 50%; background: radial-gradient(circle, rgba(224,168,46,.22), transparent 70%); }
                    .sm-hero__tag { position: relative; z-index: 1; display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,.13); border: 1px solid rgba(255,255,255,.22); color: #fff; font-weight: 700; font-size: .72rem; padding: 5px 12px; border-radius: var(--ds-radius-pill); }
                    .sm-hero h1 { position: relative; z-index: 1; font-family: var(--ds-font-heading); font-weight: 800; font-size: 1.55rem; color: #fff; margin: 12px 0 8px; }
                    .sm-hero p { position: relative; z-index: 1; color: rgba(231,240,235,.82); font-size: .94rem; line-height: 1.55; margin: 0; max-width: 640px; }

                    .sm-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); box-shadow: var(--ds-shadow-sm); }
                    .sm-filter { padding: 16px; margin-bottom: 20px; }
                    .sm-label { font-weight: 700; color: var(--ds-muted); font-size: .78rem; margin-bottom: 5px; display: block; }
                    .sm-input, .sm-select { width: 100%; border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius); padding: 10px 13px; font-size: .9rem; color: var(--ds-ink); background: var(--ds-surface); font-family: var(--ds-font-sans); }
                    .sm-input:focus, .sm-select:focus { outline: none; border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); }
                    .sm-btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; background: var(--ds-brand-600); color: #fff; font-weight: 700; font-size: .9rem; padding: 10px 18px; border: 0; border-radius: var(--ds-radius-pill); cursor: pointer; text-decoration: none; transition: all var(--ds-transition); }
                    .sm-btn:hover { background: var(--ds-brand-700); color: #fff; }

                    .sm-section-title { display: flex; align-items: center; gap: 8px; font-family: var(--ds-font-heading); font-size: 1.1rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0; }
                    .sm-section-title i { color: var(--ds-brand-600); font-size: 1.25rem; }
                    .sm-count { display: inline-flex; align-items: center; height: 24px; padding: 0 11px; border-radius: var(--ds-radius-pill); background: var(--ds-brand-50); color: var(--ds-brand-700); font-weight: 800; font-size: .78rem; }

                    .sm-thread { border: 1px solid var(--ds-border); border-left: 4px solid var(--ds-border-strong); border-radius: var(--ds-radius-lg); background: var(--ds-surface); padding: 18px; margin-bottom: 16px; box-shadow: var(--ds-shadow-sm); transition: transform var(--ds-transition); }
                    .sm-thread.is-unread { border-left-color: var(--ds-danger); }
                    .sm-thread__head { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 14px; }
                    .sm-thread__who { font-family: var(--ds-font-heading); font-weight: 800; color: var(--ds-brand-700); font-size: 1.02rem; }
                    .sm-thread__on { color: var(--ds-muted); font-size: .82rem; }
                    .sm-thread__on b { color: var(--ds-ink); }
                    .sm-thread__date { color: var(--ds-muted-soft); font-size: .76rem; white-space: nowrap; }
                    .sm-new { display: inline-flex; align-items: center; height: 22px; padding: 0 10px; border-radius: var(--ds-radius-pill); background: var(--ds-danger); color: #fff; font-size: .68rem; font-weight: 800; text-transform: uppercase; margin-top: 4px; }

                    .sm-bubble { background: var(--ds-surface-2); border: 1px solid var(--ds-border); border-radius: 14px; padding: 11px 14px; margin-bottom: 10px; max-width: 85%; }
                    .sm-bubble__head { display: flex; justify-content: space-between; gap: 10px; font-size: .72rem; color: var(--ds-muted); margin-bottom: 4px; }
                    .sm-bubble__text { color: var(--ds-ink); font-size: .9rem; line-height: 1.5; }
                    .sm-bubble.sent { background: var(--ds-brand-50); border-color: var(--ds-brand-200); margin-left: auto; }

                    .sm-contacts { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
                    .sm-pill { display: inline-flex; align-items: center; gap: 5px; font-size: .78rem; font-weight: 700; padding: 7px 13px; border-radius: var(--ds-radius-pill); text-decoration: none; }
                    .sm-pill--wa { background: #e7f7ed; color: #1a7f43; }
                    .sm-pill--tel { background: #e3effb; color: #1d59b8; }
                    .sm-pill--link { background: var(--ds-brand-50); color: var(--ds-brand-700); }

                    .sm-reply { display: flex; gap: 8px; align-items: flex-end; border-top: 1px solid var(--ds-border); padding-top: 14px; }
                    .sm-reply textarea { flex: 1; border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius); padding: 10px 13px; font-size: .9rem; resize: vertical; min-height: 46px; color: var(--ds-ink); background: var(--ds-surface); font-family: var(--ds-font-sans); }
                    .sm-reply textarea:focus { outline: none; border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); }
                    .sm-reply button { flex-shrink: 0; width: 46px; height: 46px; border-radius: 50%; background: var(--ds-brand-600); color: #fff; border: 0; cursor: pointer; font-size: 1.2rem; display: inline-flex; align-items: center; justify-content: center; }
                    .sm-reply button:hover { background: var(--ds-brand-700); }

                    .sm-review { padding: 16px; margin-bottom: 14px; }
                    .sm-review__head { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
                    .sm-ava { width: 36px; height: 36px; border-radius: 50%; background: var(--ds-brand-100); color: var(--ds-brand-700); display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: .85rem; flex-shrink: 0; }
                    .sm-review__name { font-weight: 700; color: var(--ds-ink-strong); font-size: .9rem; }
                    .sm-review__date { color: var(--ds-muted-soft); font-size: .74rem; }
                    .sm-stars { color: var(--ds-accent); font-size: .95rem; margin-bottom: 8px; }
                    .sm-stars .bx-star { color: var(--ds-border-strong); }
                    .sm-review__text { color: var(--ds-ink); font-size: .88rem; line-height: 1.5; margin-bottom: 10px; }
                    .sm-review__on { color: var(--ds-muted); font-size: .78rem; border-top: 1px solid var(--ds-border); padding-top: 8px; margin-bottom: 10px; }
                    .sm-circle { width: 34px; height: 34px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 1.05rem; text-decoration: none; }
                    .sm-circle--wa { background: #e7f7ed; color: #1a7f43; }
                    .sm-circle--tel { background: #e3effb; color: #1d59b8; }
                    .sm-circle--mail { background: var(--ds-accent-soft); color: #8a6310; }

                    .sm-empty { text-align: center; padding: 40px 20px; color: var(--ds-muted); }
                    .sm-empty i { font-size: 2.6rem; color: var(--ds-brand-300); display: block; margin-bottom: 8px; }

                    [data-reveal] { opacity: 0; transform: translateY(18px); transition: all .6s cubic-bezier(.22,1,.36,1); }
                    [data-reveal].is-visible { opacity: 1; transform: none; }

                    @media (min-width: 768px) { .sm-hero { padding: 30px; } .sm-hero h1 { font-size: 1.9rem; } }
                </style>

                <div class="sm-hero" data-reveal>
                    <span class="sm-hero__tag"><i class='bx bx-message-rounded-dots'></i> Boîte de réception</span>
                    <h1>Messagerie &amp; commentaires</h1>
                    <p>Gérez les échanges avec vos visiteurs : répondez aux messages et suivez les avis laissés sur vos projets.</p>
                </div>

                <div class="sm-card sm-filter" data-reveal>
                    <form method="get" action="<?= ROOT ?>/Homes/messages_recus" class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <label class="sm-label">Rechercher</label>
                            <input type="text" name="search" class="sm-input" value="<?= htmlspecialchars((string) $messageFilterSearch) ?>" placeholder="Visiteur, projet…">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="sm-label">Projet</label>
                            <select name="project_id" class="sm-select">
                                <option value="">Tous les projets</option>
                                <?php foreach ($studentProjectsForFilter as $projectOption): ?>
                                    <option value="<?= (int) ($projectOption['id'] ?? 0) ?>" <?= ((int) ($messageFilterProjectId ?? 0) === (int) ($projectOption['id'] ?? 0)) ? 'selected' : '' ?>><?= htmlspecialchars((string) ($projectOption['title'] ?? 'Projet')) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="sm-label">Statut</label>
                            <select name="status" class="sm-select">
                                <option value="all" <?= $messageFilterStatus === 'all' ? 'selected' : '' ?>>Tout afficher</option>
                                <option value="unread" <?= $messageFilterStatus === 'unread' ? 'selected' : '' ?>>Non lus</option>
                                <option value="read" <?= $messageFilterStatus === 'read' ? 'selected' : '' ?>>Lus uniquement</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6 d-flex align-items-end">
                            <button type="submit" class="sm-btn w-100"><i class='bx bx-filter-alt'></i> Filtrer</button>
                        </div>
                    </form>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="sm-section-title"><i class='bx bx-chat'></i> Conversations</h2>
                            <span class="sm-count"><?= count($studentMessageThreads) ?> fil(s)</span>
                        </div>

                        <?php if (!empty($studentMessageThreads)): ?>
                            <?php foreach ($studentMessageThreads as $thread): ?>
                                <div class="sm-thread <?= !empty($thread['is_unread']) ? 'is-unread' : '' ?>" data-reveal>
                                    <div class="sm-thread__head">
                                        <div>
                                            <div class="sm-thread__who"><?= htmlspecialchars((string) ($thread['visitor_name'] ?? 'Visiteur')) ?></div>
                                            <div class="sm-thread__on">Sur : <b><?= htmlspecialchars((string) ($thread['project_title'] ?? 'Projet')) ?></b></div>
                                        </div>
                                        <div class="text-end">
                                            <div class="sm-thread__date"><?= htmlspecialchars((string) ($thread['last_date'] ?? '')) ?></div>
                                            <?php if (!empty($thread['is_unread'])): ?><span class="sm-new">Nouveau</span><?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <?php foreach (($thread['messages_preview'] ?? []) as $preview): ?>
                                            <?php $sent = ($preview['direction'] ?? '') === 'sent'; ?>
                                            <div class="sm-bubble <?= $sent ? 'sent' : '' ?>">
                                                <div class="sm-bubble__head"><span><?= $sent ? 'Moi' : 'Visiteur' ?></span><span><?= htmlspecialchars((string) ($preview['date'] ?? '')) ?></span></div>
                                                <div class="sm-bubble__text"><?= nl2br(htmlspecialchars((string) ($preview['message'] ?? ''))) ?></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <div class="sm-contacts">
                                        <?php if (!empty($thread['whatsapp_url'])): ?><a href="<?= htmlspecialchars((string) $thread['whatsapp_url']) ?>" target="_blank" rel="noopener" class="sm-pill sm-pill--wa"><i class='bx bxl-whatsapp'></i> WhatsApp</a><?php endif; ?>
                                        <?php if (!empty($thread['tel_url'])): ?><a href="<?= htmlspecialchars((string) $thread['tel_url']) ?>" class="sm-pill sm-pill--tel"><i class='bx bx-phone-call'></i> Appeler</a><?php endif; ?>
                                        <a href="<?= ROOT ?>/Projets/detail/<?= (int) ($thread['project_id'] ?? 0) ?>" class="sm-pill sm-pill--link"><i class='bx bx-link-external'></i> Page projet</a>
                                    </div>

                                    <form method="post" class="sm-reply">
                                        <input type="hidden" name="action" value="send_thread_reply">
                                        <input type="hidden" name="project_id" value="<?= (int) ($thread['project_id'] ?? 0) ?>">
                                        <input type="hidden" name="receiver_id" value="<?= (int) ($thread['visitor_id'] ?? 0) ?>">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                        <textarea name="message" placeholder="Répondre ici…" rows="2" required></textarea>
                                        <button type="submit" title="Envoyer"><i class='bx bx-send'></i></button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="sm-card sm-empty"><i class='bx bx-message-square-x'></i><p class="mb-0">Aucune conversation trouvée.</p></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-lg-4">
                        <h2 class="sm-section-title mb-3"><i class='bx bxs-star'></i> Avis reçus</h2>
                        <?php if (!empty($studentVisitorReviews)): ?>
                            <?php foreach ($studentVisitorReviews as $feedback): ?>
                                <?php $fName = (string) ($feedback['visitor_name'] ?? 'Visiteur'); ?>
                                <div class="sm-card sm-review" data-reveal>
                                    <div class="sm-review__head">
                                        <span class="sm-ava"><?= htmlspecialchars(strtoupper(mb_substr($fName, 0, 1))) ?></span>
                                        <div>
                                            <div class="sm-review__name"><?= htmlspecialchars($fName) ?></div>
                                            <div class="sm-review__date"><?= htmlspecialchars((string) ($feedback['date'] ?? '')) ?></div>
                                        </div>
                                    </div>
                                    <div class="sm-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?><i class='bx <?= $i <= (int) ($feedback['rating'] ?? 0) ? 'bxs-star' : 'bx-star' ?>'></i><?php endfor; ?>
                                    </div>
                                    <div class="sm-review__text">« <?= htmlspecialchars((string) ($feedback['review'] ?? '')) ?> »</div>
                                    <div class="sm-review__on">Sur : <b><?= htmlspecialchars((string) ($feedback['project_title'] ?? '')) ?></b></div>
                                    <div class="d-flex gap-2">
                                        <?php if (!empty($feedback['whatsapp_url'])): ?><a href="<?= htmlspecialchars((string) $feedback['whatsapp_url']) ?>" target="_blank" rel="noopener" class="sm-circle sm-circle--wa" title="WhatsApp"><i class='bx bxl-whatsapp'></i></a><?php endif; ?>
                                        <?php if (!empty($feedback['tel_url'])): ?><a href="<?= htmlspecialchars((string) $feedback['tel_url']) ?>" class="sm-circle sm-circle--tel" title="Appeler"><i class='bx bx-phone-call'></i></a><?php endif; ?>
                                        <?php if (!empty($feedback['mailto_url'])): ?><a href="<?= htmlspecialchars((string) $feedback['mailto_url']) ?>" class="sm-circle sm-circle--mail" title="Email"><i class='bx bx-envelope'></i></a><?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="sm-card sm-empty"><i class='bx bx-comment'></i><p class="mb-0">Aucun avis pour le moment.</p></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>

<?php $this->view('Partials/scripts'); ?>
<script>
    (function () {
        var items = document.querySelectorAll('[data-reveal]');
        if (!items.length) { return; }
        if (!('IntersectionObserver' in window)) { items.forEach(function (i) { i.classList.add('is-visible'); }); return; }
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) { if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); } });
        }, { threshold: 0.12 });
        items.forEach(function (item, index) { item.style.transitionDelay = Math.min(index * 60, 320) + 'ms'; observer.observe(item); });
    })();
</script>
</body>
</html>
