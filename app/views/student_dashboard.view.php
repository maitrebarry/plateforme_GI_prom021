<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Dashboard etudiant']); ?>
<?php
$studentStats = $studentStats ?? [];
$projects = $projects ?? [];
$studentName = $studentName ?? 'Etudiant';
$studentCompletionRate = max(0, min(100, (int) ($studentCompletionRate ?? 0)));
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

            <div class="dashboard-body__content p-3 p-lg-4">
                <?php $this->view('set_flash'); ?>

                <style>
                    .sd-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); box-shadow: var(--ds-shadow-sm); }
                    .sd-card__body { padding: 20px; }
                    .sd-title { display: flex; align-items: center; gap: 9px; font-family: var(--ds-font-heading); font-size: 1.1rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0; }
                    .sd-title i { color: var(--ds-brand-600); font-size: 1.25rem; }
                    .sd-link { display: inline-flex; align-items: center; gap: 5px; color: var(--ds-brand-600); font-weight: 700; font-size: .82rem; text-decoration: none; }
                    .sd-link:hover { color: var(--ds-brand-700); }

                    .sd-hero { position: relative; overflow: hidden; background: linear-gradient(135deg, var(--ds-brand-700), var(--ds-brand-800)); border-radius: var(--ds-radius-xl); padding: 26px; color: #fff; margin-bottom: 22px; }
                    .sd-hero::before { content: ''; position: absolute; top: -70px; right: -50px; width: 280px; height: 280px; border-radius: 50%; background: radial-gradient(circle, rgba(224,168,46,.2), transparent 70%); pointer-events: none; }
                    .sd-hero__grid { position: relative; z-index: 1; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 20px; }
                    .sd-hero h1 { font-family: var(--ds-font-heading); font-weight: 800; font-size: 1.7rem; line-height: 1.2; margin: 0 0 8px; color: #fff; }
                    .sd-hero p { color: rgba(231,240,235,.82); font-size: .96rem; line-height: 1.55; margin: 0 0 16px; max-width: 540px; }
                    .sd-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-weight: 700; font-size: .9rem; padding: 11px 18px; border-radius: var(--ds-radius-pill); border: 0; cursor: pointer; text-decoration: none; transition: all var(--ds-transition); }
                    .sd-btn--gold { background: var(--ds-accent); color: #3d2900; } .sd-btn--gold:hover { background: #f0b53e; color: #3d2900; transform: translateY(-1px); }
                    .sd-btn--glass { background: rgba(255,255,255,.12); color: #fff; border: 1px solid rgba(255,255,255,.22); } .sd-btn--glass:hover { background: rgba(255,255,255,.2); color: #fff; }
                    .sd-ring-wrap { display: flex; flex-direction: column; align-items: center; gap: 8px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12); border-radius: var(--ds-radius-lg); padding: 16px 22px; }
                    .sd-ring { position: relative; width: 96px; height: 96px; border-radius: 50%; display: grid; place-items: center; background: conic-gradient(#fff calc(var(--p, 0) * 1%), rgba(255,255,255,.18) 0); }
                    .sd-ring::after { content: ''; position: absolute; width: 74px; height: 74px; border-radius: 50%; background: var(--ds-brand-800); }
                    .sd-ring b { position: relative; z-index: 1; color: #fff; font-weight: 800; font-size: 1.25rem; }
                    .sd-ring-wrap small { color: rgba(231,240,235,.8); font-size: .76rem; text-align: center; } .sd-ring-wrap strong { color: #fff; font-size: .9rem; }

                    .sd-stats { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; margin-bottom: 22px; }
                    .sd-stat { position: relative; overflow: hidden; background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); padding: 16px; box-shadow: var(--ds-shadow-sm); transition: transform var(--ds-transition), box-shadow var(--ds-transition); }
                    .sd-stat:hover { transform: translateY(-3px); box-shadow: var(--ds-shadow-md); }
                    .sd-stat::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--ds-brand-500); }
                    .sd-stat--likes::before { background: var(--ds-danger); } .sd-stat--reviews::before { background: var(--ds-accent); } .sd-stat--messages::before { background: #1d59b8; }
                    .sd-stat__icon { width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.35rem; background: var(--ds-brand-50); color: var(--ds-brand-600); margin-bottom: 10px; }
                    .sd-stat--likes .sd-stat__icon { background: var(--ds-danger-soft); color: var(--ds-danger); }
                    .sd-stat--reviews .sd-stat__icon { background: var(--ds-accent-soft); color: #8a6310; }
                    .sd-stat--messages .sd-stat__icon { background: #e3effb; color: #1d59b8; }
                    .sd-stat__value { font-family: var(--ds-font-heading); font-size: 1.9rem; font-weight: 800; color: var(--ds-ink-strong); line-height: 1; }
                    .sd-stat__label { color: var(--ds-muted); font-size: .76rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; margin-top: 6px; }
                    .sd-stat__sub { color: var(--ds-muted-soft); font-size: .78rem; margin-top: 4px; }

                    .sd-proj { display: flex; gap: 14px; padding: 14px 0; border-bottom: 1px solid var(--ds-border); }
                    .sd-proj:last-child { border-bottom: 0; padding-bottom: 0; }
                    .sd-proj__thumb { width: 64px; height: 64px; border-radius: 12px; object-fit: cover; flex-shrink: 0; }
                    .sd-proj__body { flex: 1; min-width: 0; }
                    .sd-proj__title { font-family: var(--ds-font-heading); font-weight: 800; font-size: .98rem; margin: 0 0 4px; }
                    .sd-proj__title a { color: var(--ds-ink-strong); text-decoration: none; } .sd-proj__title a:hover { color: var(--ds-brand-600); }
                    .sd-proj__meta { display: flex; flex-wrap: wrap; gap: 12px; color: var(--ds-muted); font-size: .78rem; }
                    .sd-proj__meta span { display: inline-flex; align-items: center; gap: 4px; }
                    .sd-proj__stats { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 6px; font-size: .8rem; font-weight: 700; }
                    .sd-proj__stats .s-like { color: var(--ds-danger); } .sd-proj__stats .s-rev { color: var(--ds-brand-600); } .sd-proj__stats .s-rate { color: #8a6310; }
                    .sd-status { flex-shrink: 0; display: inline-flex; align-items: center; height: 24px; padding: 0 11px; border-radius: var(--ds-radius-pill); font-size: .7rem; font-weight: 800; text-transform: uppercase; letter-spacing: .03em; }
                    .sd-status--ok { background: #e4f3ea; color: #11703a; }
                    .sd-status--pending { background: var(--ds-accent-soft); color: #8a6310; }
                    .sd-status--draft { background: var(--ds-surface-2); color: var(--ds-muted); }

                    .sd-action { display: flex; align-items: center; gap: 12px; padding: 13px 15px; border-radius: var(--ds-radius); border: 1px solid var(--ds-border); background: var(--ds-surface); text-decoration: none; transition: all var(--ds-transition); }
                    .sd-action + .sd-action { margin-top: 10px; }
                    .sd-action:hover { border-color: var(--ds-brand-300); background: var(--ds-brand-50); transform: translateX(3px); }
                    .sd-action__icon { width: 38px; height: 38px; border-radius: 11px; display: inline-flex; align-items: center; justify-content: center; background: var(--ds-brand-600); color: #fff; font-size: 1.2rem; flex-shrink: 0; }
                    .sd-action__title { font-weight: 700; color: var(--ds-ink-strong); font-size: .92rem; }
                    .sd-action__text { color: var(--ds-muted); font-size: .78rem; }

                    .sd-thread { padding: 12px 0; border-bottom: 1px solid var(--ds-border); }
                    .sd-thread:last-child { border-bottom: 0; }
                    .sd-thread__head { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
                    .sd-thread__who { font-weight: 700; color: var(--ds-ink-strong); font-size: .88rem; }
                    .sd-thread__date { color: var(--ds-muted-soft); font-size: .74rem; }
                    .sd-thread__msg { color: var(--ds-muted); font-size: .82rem; margin: 4px 0 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
                    .sd-thread__reply { color: var(--ds-brand-600); font-weight: 700; font-size: .8rem; text-decoration: none; }

                    .sd-review { border: 1px solid var(--ds-border); border-radius: var(--ds-radius); padding: 16px; height: 100%; background: var(--ds-surface); }
                    .sd-review__head { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
                    .sd-ava { width: 38px; height: 38px; border-radius: 50%; background: var(--ds-brand-100); color: var(--ds-brand-700); display: inline-flex; align-items: center; justify-content: center; font-weight: 800; flex-shrink: 0; }
                    .sd-review__name { font-weight: 700; color: var(--ds-ink-strong); font-size: .9rem; } .sd-review__on { color: var(--ds-muted); font-size: .76rem; }
                    .sd-review__rating { margin-left: auto; color: var(--ds-accent); font-weight: 800; font-size: .85rem; white-space: nowrap; }
                    .sd-review__text { color: var(--ds-ink); font-size: .88rem; line-height: 1.55; margin-bottom: 12px; }
                    .sd-review__actions { display: flex; flex-wrap: wrap; gap: 8px; }
                    .sd-pill-btn { display: inline-flex; align-items: center; gap: 5px; font-size: .76rem; font-weight: 700; padding: 6px 12px; border-radius: var(--ds-radius-pill); text-decoration: none; }
                    .sd-pill-btn--wa { background: #e7f7ed; color: #1a7f43; } .sd-pill-btn--tel { background: #e3effb; color: #1d59b8; }

                    .sd-empty { text-align: center; padding: 30px 16px; color: var(--ds-muted); }
                    .sd-empty i { font-size: 2.4rem; color: var(--ds-brand-300); display: block; margin-bottom: 8px; }
                    .sd-badge-unread { display: inline-flex; align-items: center; height: 22px; padding: 0 9px; border-radius: var(--ds-radius-pill); background: var(--ds-danger); color: #fff; font-size: .72rem; font-weight: 800; }

                    [data-reveal] { opacity: 0; transform: translateY(16px); transition: all .5s ease; }
                    [data-reveal].is-visible { opacity: 1; transform: none; }

                    @media (min-width: 768px) { .sd-hero { padding: 32px; } .sd-hero h1 { font-size: 2.1rem; } .sd-stats { grid-template-columns: repeat(4, minmax(0, 1fr)); } }
                </style>

                <div class="sd-hero" data-reveal>
                    <div class="sd-hero__grid">
                        <div>
                            <h1>Bonjour, <?= htmlspecialchars($studentName) ?> !</h1>
                            <p>Bienvenue sur votre espace. Pilotez vos projets, suivez votre engagement et échangez avec vos visiteurs — au même endroit.</p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="<?= ROOT ?>/Projets/publier_projet" class="sd-btn sd-btn--gold"><i class='bx bx-plus-circle'></i> Publier un projet</a>
                                <a href="<?= ROOT ?>/Projets/mes_projets" class="sd-btn sd-btn--glass"><i class='bx bx-grid-alt'></i> Mes publications</a>
                            </div>
                        </div>
                        <div class="sd-ring-wrap">
                            <div class="sd-ring" style="--p: <?= $studentCompletionRate ?>"><b><?= $studentCompletionRate ?>%</b></div>
                            <strong>Taux de validation</strong>
                            <small>Projets validés / total</small>
                        </div>
                    </div>
                </div>

                <div class="sd-stats">
                    <div class="sd-stat" data-reveal>
                        <span class="sd-stat__icon"><i class='bx bx-folder'></i></span>
                        <div class="sd-stat__value"><?= (int) ($studentStats['mesProjets'] ?? 0) ?></div>
                        <div class="sd-stat__label">Publications</div>
                        <div class="sd-stat__sub"><?= (int) ($studentStats['enAttente'] ?? 0) ?> en attente</div>
                    </div>
                    <div class="sd-stat sd-stat--likes" data-reveal>
                        <span class="sd-stat__icon"><i class='bx bxs-heart'></i></span>
                        <div class="sd-stat__value"><?= (int) ($studentStats['likes'] ?? 0) ?></div>
                        <div class="sd-stat__label">Likes reçus</div>
                        <div class="sd-stat__sub">Engagement total</div>
                    </div>
                    <div class="sd-stat sd-stat--reviews" data-reveal>
                        <span class="sd-stat__icon"><i class='bx bxs-star'></i></span>
                        <div class="sd-stat__value"><?= (int) ($studentStats['reviews'] ?? 0) ?></div>
                        <div class="sd-stat__label">Avis &amp; notes</div>
                        <div class="sd-stat__sub">Retour visiteurs</div>
                    </div>
                    <div class="sd-stat sd-stat--messages" data-reveal>
                        <span class="sd-stat__icon"><i class='bx bx-envelope'></i></span>
                        <div class="sd-stat__value"><?= (int) ($studentStats['messages'] ?? 0) ?></div>
                        <div class="sd-stat__label">Messages</div>
                        <div class="sd-stat__sub"><?= $studentUnreadMessages ?> non lus</div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8" data-reveal>
                        <div class="sd-card">
                            <div class="sd-card__body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h2 class="sd-title"><i class='bx bx-rocket'></i> Mes projets récents</h2>
                                    <a href="<?= ROOT ?>/Projets/mes_projets" class="sd-link">Voir tout <i class='bx bx-right-arrow-alt'></i></a>
                                </div>
                                <?php if (!empty($projects)): ?>
                                    <?php foreach ($projects as $project): ?>
                                        <?php
                                        $statusText = (string) ($project['status'] ?? 'En attente');
                                        $sl = strtolower($statusText); $sc = 'pending';
                                        if (str_contains($sl, 'valid') || str_contains($sl, 'publ') || str_contains($sl, 'termin')) { $sc = 'ok'; }
                                        elseif (str_contains($sl, 'draft') || str_contains($sl, 'brouillon')) { $sc = 'draft'; }
                                        ?>
                                        <div class="sd-proj">
                                            <img src="<?= htmlspecialchars((string) ($project['image'] ?? (ROOT . '/assets/images/thumbs/product-img1.png'))) ?>" class="sd-proj__thumb" alt="" loading="lazy">
                                            <div class="sd-proj__body">
                                                <h3 class="sd-proj__title"><a href="<?= ROOT ?>/Projets/detail/<?= (int) ($project['id'] ?? 0) ?>"><?= htmlspecialchars((string) ($project['title'] ?? 'Projet')) ?></a></h3>
                                                <div class="sd-proj__meta">
                                                    <span><i class='bx bx-category'></i> <?= htmlspecialchars((string) ($project['category'] ?? 'Sans catégorie')) ?></span>
                                                    <span><i class='bx bx-calendar'></i> <?= htmlspecialchars((string) ($project['date'] ?? '')) ?></span>
                                                </div>
                                                <div class="sd-proj__stats">
                                                    <span class="s-like"><i class='bx bxs-heart'></i> <?= (int) ($project['likes_count'] ?? 0) ?></span>
                                                    <span class="s-rev"><i class='bx bxs-message-square-detail'></i> <?= (int) ($project['reviews_count'] ?? 0) ?></span>
                                                    <span class="s-rate"><i class='bx bxs-star'></i> <?= number_format((float) ($project['average_rating'] ?? 0), 1) ?></span>
                                                </div>
                                            </div>
                                            <span class="sd-status sd-status--<?= $sc ?>"><?= htmlspecialchars($statusText) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="sd-empty"><i class='bx bx-folder-open'></i><p>Vous n'avez pas encore publié de projet.</p><a href="<?= ROOT ?>/Projets/publier_projet" class="sd-btn sd-btn--gold" style="background:var(--ds-brand-600);color:#fff"><i class='bx bx-plus-circle'></i> Commencer</a></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="sd-card mb-4" data-reveal>
                            <div class="sd-card__body">
                                <h2 class="sd-title mb-3"><i class='bx bx-bolt-circle'></i> Actions rapides</h2>
                                <?php foreach ($studentActions as $action): ?>
                                    <a href="<?= htmlspecialchars((string) ($action['href'] ?? '#')) ?>" class="sd-action">
                                        <span class="sd-action__icon"><i class='<?= htmlspecialchars((string) ($action['icon'] ?? 'bx bx-link')) ?>'></i></span>
                                        <span><span class="sd-action__title d-block"><?= htmlspecialchars((string) ($action['title'] ?? 'Action')) ?></span><span class="sd-action__text d-block"><?= htmlspecialchars((string) ($action['text'] ?? '')) ?></span></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="sd-card" data-reveal>
                            <div class="sd-card__body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h2 class="sd-title"><i class='bx bx-message-dots'></i> Messages récents</h2>
                                    <?php if ($studentUnreadMessages > 0): ?><span class="sd-badge-unread"><?= $studentUnreadMessages ?> non lus</span><?php endif; ?>
                                </div>
                                <?php if (!empty($studentUnreadThreadsPreview)): ?>
                                    <?php foreach (array_slice($studentUnreadThreadsPreview, 0, 3) as $thread): ?>
                                        <div class="sd-thread">
                                            <div class="sd-thread__head"><span class="sd-thread__who"><?= htmlspecialchars((string) ($thread['visitor_name'] ?? 'Visiteur')) ?></span><span class="sd-thread__date"><?= htmlspecialchars((string) ($thread['last_date'] ?? '')) ?></span></div>
                                            <div class="sd-thread__msg"><?= htmlspecialchars((string) ($thread['last_message'] ?? '')) ?></div>
                                            <a href="<?= ROOT ?>/Homes/messages_recus?project_id=<?= (int) ($thread['project_id'] ?? 0) ?>" class="sd-thread__reply"><i class='bx bx-reply'></i> Répondre</a>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="sd-empty"><i class='bx bx-message-square-check'></i><p class="mb-0">Aucun message non lu.</p></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sd-card mt-4" data-reveal>
                    <div class="sd-card__body">
                        <h2 class="sd-title mb-3"><i class='bx bx-comment-detail'></i> Derniers commentaires des visiteurs</h2>
                        <?php if (!empty($studentVisitorReviews)): ?>
                            <div class="row g-3">
                                <?php foreach ($studentVisitorReviews as $feedback): ?>
                                    <?php $fName = (string) ($feedback['visitor_name'] ?? 'Visiteur'); ?>
                                    <div class="col-md-6">
                                        <div class="sd-review">
                                            <div class="sd-review__head">
                                                <span class="sd-ava"><?= htmlspecialchars(strtoupper(mb_substr($fName, 0, 1))) ?></span>
                                                <div><div class="sd-review__name"><?= htmlspecialchars($fName) ?></div><div class="sd-review__on">Sur <?= htmlspecialchars((string) ($feedback['project_title'] ?? 'Projet')) ?></div></div>
                                                <span class="sd-review__rating"><i class='bx bxs-star'></i> <?= (int) ($feedback['rating'] ?? 0) ?>/5</span>
                                            </div>
                                            <div class="sd-review__text">« <?= nl2br(htmlspecialchars((string) ($feedback['review'] ?? ''))) ?> »</div>
                                            <div class="sd-review__actions">
                                                <?php if (!empty($feedback['whatsapp_url'])): ?><a href="<?= htmlspecialchars((string) $feedback['whatsapp_url']) ?>" target="_blank" rel="noopener" class="sd-pill-btn sd-pill-btn--wa"><i class='bx bxl-whatsapp'></i> WhatsApp</a><?php endif; ?>
                                                <?php if (!empty($feedback['tel_url'])): ?><a href="<?= htmlspecialchars((string) $feedback['tel_url']) ?>" class="sd-pill-btn sd-pill-btn--tel"><i class='bx bx-phone-call'></i> Appeler</a><?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="sd-empty"><i class='bx bx-comment'></i><p class="mb-0">Pas encore d'avis sur vos projets.</p></div>
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
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('is-visible'); obs.unobserve(e.target); } });
        }, { threshold: 0.1 });
        items.forEach(function (item, i) { item.style.transitionDelay = Math.min(i * 60, 300) + 'ms'; obs.observe(item); });
    })();
</script>
</body>
</html>
