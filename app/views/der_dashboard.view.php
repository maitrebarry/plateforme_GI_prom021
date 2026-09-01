<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Dashboard Responsable Scolaire']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$derStats = $derStats ?? [];
$latestPublications = $latestPublications ?? [];
$csrf = (string) ($_SESSION['csrf_token'] ?? '');
$derStatCards = [
    ['Total publications', (int) ($derStats['total'] ?? 0), 'bx-collection', 'brand'],
    ['Annonces', (int) ($derStats['annonces'] ?? 0), 'bx-megaphone', 'danger'],
    ['Informations', (int) ($derStats['informations'] ?? 0), 'bx-info-circle', 'brand'],
    ['Événements', (int) ($derStats['evenements'] ?? 0), 'bx-calendar-event', 'accent'],
    ['Résultats', (int) ($derStats['resultats'] ?? 0), 'bx-award', 'blue'],
    ['Opportunités', (int) ($derStats['opportunites'] ?? 0), 'bx-briefcase-alt-2', 'gold'],
    ['Fichiers joints', (int) ($derStats['files'] ?? 0), 'bx-paperclip', 'slate'],
];
$derTypeColor = ['annonce' => 'ann', 'information' => 'info', 'evenement' => 'evt', 'resultat' => 'res', 'opportunite' => 'opp'];
?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

            <div class="dashboard-body__content p-3 p-lg-4">
                <style>
                    .der-hero { position: relative; overflow: hidden; background: linear-gradient(135deg, var(--ds-brand-700), var(--ds-brand-800)); border-radius: var(--ds-radius-xl); padding: 26px; color: #fff; margin-bottom: 22px; }
                    .der-hero::before { content: ''; position: absolute; top: -70px; right: -50px; width: 280px; height: 280px; border-radius: 50%; background: radial-gradient(circle, rgba(224,168,46,.2), transparent 70%); }
                    .der-hero__row { position: relative; z-index: 1; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 18px; }
                    .der-hero h1 { font-family: var(--ds-font-heading); font-weight: 800; font-size: 1.6rem; color: #fff; margin: 0 0 6px; }
                    .der-hero p { color: rgba(231,240,235,.82); font-size: .95rem; margin: 0; max-width: 520px; }
                    .der-hero__cta { display: flex; flex-wrap: wrap; gap: 10px; }
                    .der-btn { display: inline-flex; align-items: center; gap: 7px; font-weight: 700; font-size: .88rem; padding: 10px 16px; border-radius: var(--ds-radius-pill); border: 0; cursor: pointer; text-decoration: none; transition: all var(--ds-transition); }
                    .der-btn--gold { background: var(--ds-accent); color: #3d2900; }
                    .der-btn--gold:hover { background: #f0b53e; color: #3d2900; }
                    .der-btn--glass { background: rgba(255,255,255,.12); color: #fff; border: 1px solid rgba(255,255,255,.22); }
                    .der-btn--glass:hover { background: rgba(255,255,255,.2); color: #fff; }

                    .der-stats { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 14px; margin-bottom: 22px; }
                    .der-stat { position: relative; overflow: hidden; background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); padding: 16px; box-shadow: var(--ds-shadow-sm); transition: transform var(--ds-transition), box-shadow var(--ds-transition); }
                    .der-stat:hover { transform: translateY(-3px); box-shadow: var(--ds-shadow-md); }
                    .der-stat::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
                    .der-stat--brand::before { background: var(--ds-brand-500); } .der-stat--brand .der-stat__icon { background: var(--ds-brand-50); color: var(--ds-brand-600); }
                    .der-stat--danger::before { background: var(--ds-danger); } .der-stat--danger .der-stat__icon { background: var(--ds-danger-soft); color: var(--ds-danger); }
                    .der-stat--accent::before { background: var(--ds-accent); } .der-stat--accent .der-stat__icon { background: var(--ds-accent-soft); color: #8a6310; }
                    .der-stat--blue::before { background: #1d59b8; } .der-stat--blue .der-stat__icon { background: #e3effb; color: #1d59b8; }
                    .der-stat--gold::before { background: #d99a16; } .der-stat--gold .der-stat__icon { background: var(--ds-accent-soft); color: #8a6310; }
                    .der-stat--slate::before { background: #64748b; } .der-stat--slate .der-stat__icon { background: var(--ds-surface-2); color: var(--ds-muted); }
                    .der-stat__icon { width: 38px; height: 38px; border-radius: 11px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 9px; }
                    .der-stat__value { font-family: var(--ds-font-heading); font-size: 1.7rem; font-weight: 800; color: var(--ds-ink-strong); line-height: 1; }
                    .der-stat__label { color: var(--ds-muted); font-size: .74rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; margin-top: 6px; }

                    .der-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); box-shadow: var(--ds-shadow-sm); padding: 20px; }
                    .der-card__head { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 16px; }
                    .der-card__title { display: flex; align-items: center; gap: 8px; font-family: var(--ds-font-heading); font-size: 1.1rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0; }
                    .der-card__title i { color: var(--ds-brand-600); font-size: 1.25rem; }

                    .der-pub { border: 1px solid var(--ds-border); border-radius: var(--ds-radius); padding: 16px; margin-bottom: 14px; transition: all var(--ds-transition); }
                    .der-pub:hover { border-color: var(--ds-brand-200); box-shadow: var(--ds-shadow-sm); }
                    .der-pub__head { display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap; align-items: flex-start; margin-bottom: 8px; }
                    .der-pub__title { font-family: var(--ds-font-heading); font-weight: 800; font-size: 1.02rem; color: var(--ds-ink-strong); margin: 0; }
                    .der-pill { display: inline-flex; align-items: center; height: 24px; padding: 0 12px; border-radius: var(--ds-radius-pill); font-size: .7rem; font-weight: 800; text-transform: uppercase; letter-spacing: .03em; background: var(--ds-surface-2); color: var(--ds-muted); }
                    .der-pill--ann { background: var(--ds-danger-soft); color: #a3322e; }
                    .der-pill--info { background: var(--ds-brand-50); color: var(--ds-brand-700); }
                    .der-pill--evt { background: var(--ds-accent-soft); color: #8a6310; }
                    .der-pill--res { background: #e3effb; color: #1d59b8; }
                    .der-pill--opp { background: #f6e7d8; color: #9a5a2b; }
                    .der-pub__meta { display: flex; flex-wrap: wrap; gap: 14px; color: var(--ds-muted); font-size: .8rem; margin-bottom: 10px; }
                    .der-pub__meta span { display: inline-flex; align-items: center; gap: 5px; }
                    .der-pub__text { color: var(--ds-ink); font-size: .9rem; line-height: 1.6; margin-bottom: 12px; }
                    .der-file { display: inline-flex; align-items: center; gap: 6px; margin: 0 7px 7px 0; padding: 7px 13px; border-radius: var(--ds-radius-pill); border: 1px solid var(--ds-border); background: var(--ds-surface-2); color: var(--ds-ink); text-decoration: none; font-weight: 600; font-size: .82rem; }
                    .der-file:hover { border-color: var(--ds-brand-300); color: var(--ds-brand-700); }
                    .der-pub__actions { display: flex; flex-wrap: wrap; gap: 8px; }
                    .der-act { display: inline-flex; align-items: center; gap: 5px; font-weight: 700; font-size: .8rem; padding: 7px 14px; border-radius: var(--ds-radius-pill); text-decoration: none; border: 0; cursor: pointer; }
                    .der-act--view { background: var(--ds-surface-2); color: var(--ds-ink); }
                    .der-act--edit { background: var(--ds-brand-50); color: var(--ds-brand-700); }
                    .der-act--edit:hover { background: var(--ds-brand-600); color: #fff; }
                    .der-act--del { background: var(--ds-danger-soft); color: #a3322e; }
                    .der-act--del:hover { background: var(--ds-danger); color: #fff; }
                    .der-empty { text-align: center; padding: 40px 20px; color: var(--ds-muted); }
                    .der-empty i { font-size: 2.6rem; color: var(--ds-brand-300); display: block; margin-bottom: 8px; }

                    @media (min-width: 768px) { .der-hero { padding: 32px; } .der-hero h1 { font-size: 1.95rem; } .der-stats { grid-template-columns: repeat(4, minmax(0,1fr)); } }
                </style>

                <div class="der-hero">
                    <div class="der-hero__row">
                        <div>
                            <h1>Dashboard Responsable Scolaire</h1>
                            <p>Suivi des publications, annonces et contenus officiels du département.</p>
                        </div>
                        <div class="der-hero__cta">
                            <a href="<?= ROOT ?>/Homes/der_espace#create-der-post" class="der-btn der-btn--gold"><i class='bx bx-plus-circle'></i> Nouvelle publication</a>
                            <a href="<?= ROOT ?>/Homes/der_espace" class="der-btn der-btn--glass"><i class='bx bx-list-ul'></i> Gérer</a>
                            <a href="<?= ROOT ?>/Homes/index" class="der-btn der-btn--glass"><i class='bx bx-link-external'></i> Voir le site</a>
                        </div>
                    </div>
                </div>

                <div class="der-stats">
                    <?php foreach ($derStatCards as $s): ?>
                        <div class="der-stat der-stat--<?= $s[3] ?>">
                            <span class="der-stat__icon"><i class='bx <?= $s[2] ?>'></i></span>
                            <div class="der-stat__value"><?= $s[1] ?></div>
                            <div class="der-stat__label"><?= htmlspecialchars($s[0]) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="der-card">
                    <div class="der-card__head">
                        <h2 class="der-card__title"><i class='bx bx-news'></i> Dernières publications</h2>
                        <a href="<?= ROOT ?>/Homes/der_espace" class="der-btn der-btn--gold" style="background:var(--ds-brand-600);color:#fff"><i class='bx bx-cog'></i> Tout gérer</a>
                    </div>

                    <?php if (!empty($latestPublications)): ?>
                        <?php foreach ($latestPublications as $post): ?>
                            <?php $tc = $derTypeColor[strtolower((string) ($post->type ?? ''))] ?? ''; ?>
                            <div class="der-pub">
                                <div class="der-pub__head">
                                    <h3 class="der-pub__title"><?= htmlspecialchars((string) ($post->titre ?? 'Publication')) ?></h3>
                                    <span class="der-pill der-pill--<?= $tc ?>"><?= htmlspecialchars((string) ($post->type ?? 'publication')) ?></span>
                                </div>
                                <div class="der-pub__meta">
                                    <span><i class='bx bx-calendar'></i><?= htmlspecialchars((string) ($post->publication_date ?? '')) ?></span>
                                    <span><i class='bx bx-user'></i><?= htmlspecialchars((string) ($post->author_name ?? 'Responsable Scolaire')) ?></span>
                                </div>
                                <p class="der-pub__text"><?= nl2br(htmlspecialchars(mb_strimwidth((string) ($post->contenu ?? ''), 0, 220, '…'))) ?></p>
                                <?php if (!empty($post->files ?? [])): ?>
                                    <div class="mb-2">
                                        <?php foreach (($post->files ?? []) as $file): ?>
                                            <?php $relativePath = ltrim(str_replace('\\', '/', (string) ($file->file_path ?? '')), '/'); ?>
                                            <a class="der-file" href="<?= ROOT . '/' . $relativePath ?>" target="_blank" rel="noopener"><i class='bx bx-paperclip'></i> <?= htmlspecialchars((string) ($file->original_name ?? 'Fichier')) ?></a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="der-pub__actions">
                                    <a href="<?= ROOT ?>/Homes/der_publication_detail/<?= (int) ($post->id ?? 0) ?>" class="der-act der-act--view"><i class='bx bx-show'></i> Détail</a>
                                    <a href="<?= ROOT ?>/Homes/der_espace?edit=<?= (int) ($post->id ?? 0) ?>#manage-der-posts" class="der-act der-act--edit"><i class='bx bx-edit-alt'></i> Modifier</a>
                                    <form method="POST" action="<?= ROOT ?>/Homes/der_espace" class="d-inline">
                                        <input type="hidden" name="post_id" value="<?= (int) ($post->id ?? 0) ?>">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                        <button type="submit" name="delete_der_post" class="der-act der-act--del" onclick="return confirm('Archiver cette publication ?');"><i class='bx bx-archive-in'></i> Archiver</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="der-empty"><i class='bx bx-file-blank'></i><p class="mb-0">Aucune publication pour le moment.</p></div>
                    <?php endif; ?>
                </div>
            </div>

            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>

<?php $this->view('Partials/scripts'); ?>
</body>
</html>
