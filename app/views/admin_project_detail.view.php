<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Détail projet administrateur']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$metrics = $metrics ?? [];
$images = $images ?? [];
$files = $files ?? [];
$reviews = $reviews ?? [];
$avgRating = (float) ($reviewSummary->average_rating ?? 0);
$totalReviews = (int) ($reviewSummary->total_reviews ?? 0);
$adminStatus = (string) ($project->statut_admin ?? 'en_attente');
$stCls = $adminStatus === 'valide' ? 'valide' : ($adminStatus === 'rejete' ? 'rejete' : 'pending');
$pid = (int) ($project->id ?? 0);
$author = trim((string) (($project->nom ?? '') . ' ' . ($project->prenom ?? '')));
if ($author === '') { $author = 'Auteur inconnu'; }
$csrf = (string) ($_SESSION['csrf_token'] ?? '');
$metricCards = [
    ['Likes', (int) ($metrics['likes'] ?? 0), 'bxs-heart', 'danger'],
    ['Avis', (int) ($metrics['reviews'] ?? 0), 'bxs-message-square-detail', 'brand'],
    ['Messages', (int) ($metrics['messages'] ?? 0), 'bx-mail-send', 'blue'],
    ['Note', number_format($avgRating, 1), 'bxs-star', 'accent'],
    ['Images', (int) ($metrics['images'] ?? 0), 'bx-images', 'brand'],
    ['Docs', (int) ($metrics['files'] ?? 0), 'bx-file', 'slate'],
];
?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-3 p-lg-4">
                <?php $this->view('set_flash'); ?>

                <style>
                    .apd-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; }
                    .apd-head__l { display: flex; align-items: center; gap: 12px; }
                    .adm-back { width: 42px; height: 42px; border-radius: 12px; background: var(--ds-surface); border: 1px solid var(--ds-border); display: inline-flex; align-items: center; justify-content: center; color: var(--ds-ink); text-decoration: none; font-size: 1.2rem; flex-shrink: 0; transition: all var(--ds-transition); }
                    .adm-back:hover { background: var(--ds-brand-50); color: var(--ds-brand-700); }
                    .apd-head h1 { font-family: var(--ds-font-heading); font-size: 1.3rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0; line-height: 1.2; }
                    .apd-head p { color: var(--ds-muted); font-size: .85rem; margin: 2px 0 0; }
                    .apd-mod { display: flex; gap: 8px; flex-wrap: wrap; }
                    .apd-modbtn { display: inline-flex; align-items: center; gap: 6px; font-weight: 700; font-size: .84rem; padding: 9px 16px; border-radius: var(--ds-radius-pill); border: 0; cursor: pointer; color: #fff; }
                    .apd-modbtn--ok { background: #1f8a4d; } .apd-modbtn--wait { background: var(--ds-accent); color: #3d2900; } .apd-modbtn--no { background: var(--ds-danger); }

                    .apd-metrics { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 12px; margin-bottom: 22px; }
                    .apd-metric { position: relative; overflow: hidden; background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius); padding: 13px; text-align: center; box-shadow: var(--ds-shadow-sm); }
                    .apd-metric::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
                    .apd-metric--brand::before { background: var(--ds-brand-500); } .apd-metric--brand i { color: var(--ds-brand-600); }
                    .apd-metric--danger::before { background: var(--ds-danger); } .apd-metric--danger i { color: var(--ds-danger); }
                    .apd-metric--blue::before { background: #1d59b8; } .apd-metric--blue i { color: #1d59b8; }
                    .apd-metric--accent::before { background: var(--ds-accent); } .apd-metric--accent i { color: #8a6310; }
                    .apd-metric--slate::before { background: #64748b; } .apd-metric--slate i { color: var(--ds-muted); }
                    .apd-metric i { font-size: 1.3rem; }
                    .apd-metric__v { font-family: var(--ds-font-heading); font-size: 1.4rem; font-weight: 800; color: var(--ds-ink-strong); line-height: 1; margin-top: 4px; }
                    .apd-metric__l { color: var(--ds-muted); font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; margin-top: 3px; }

                    .apd-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); box-shadow: var(--ds-shadow-sm); padding: 20px; margin-bottom: 20px; }
                    .apd-card h2 { display: flex; align-items: center; gap: 8px; font-family: var(--ds-font-heading); font-size: 1.05rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0 0 14px; padding-bottom: 12px; border-bottom: 1px solid var(--ds-border); }
                    .apd-card h2 i { color: var(--ds-brand-600); }
                    .apd-desc { color: var(--ds-ink); line-height: 1.8; font-size: .96rem; }
                    .apd-row { display: flex; align-items: center; gap: 11px; padding: 10px 0; border-bottom: 1px solid var(--ds-border); }
                    .apd-row:last-child { border-bottom: 0; }
                    .apd-row__ico { width: 32px; height: 32px; border-radius: 9px; background: var(--ds-brand-50); color: var(--ds-brand-600); display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
                    .apd-row small { color: var(--ds-muted); font-size: .74rem; display: block; }
                    .apd-row strong, .apd-row a { color: var(--ds-ink-strong); font-size: .9rem; word-break: break-word; }
                    .apd-row a { color: var(--ds-brand-600); text-decoration: none; }
                    .apd-gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; }
                    .apd-gallery img { width: 100%; height: 150px; object-fit: cover; border-radius: var(--ds-radius); }
                    .apd-doc { display: flex; align-items: center; gap: 11px; padding: 11px 13px; border: 1px solid var(--ds-border); border-radius: var(--ds-radius); text-decoration: none; color: var(--ds-ink); transition: all var(--ds-transition); }
                    .apd-doc + .apd-doc { margin-top: 8px; }
                    .apd-doc:hover { border-color: var(--ds-brand-300); background: var(--ds-brand-50); }
                    .apd-doc i { color: var(--ds-brand-600); font-size: 1.4rem; }
                    .apd-pill { display: inline-flex; align-items: center; height: 24px; padding: 0 12px; border-radius: var(--ds-radius-pill); font-size: .72rem; font-weight: 800; text-transform: uppercase; }
                    .apd-pill--pending { background: var(--ds-accent-soft); color: #8a6310; } .apd-pill--valide { background: #e4f3ea; color: #11703a; } .apd-pill--rejete { background: var(--ds-danger-soft); color: #a3322e; }
                    .apd-rating { display: flex; align-items: center; justify-content: space-between; background: var(--ds-surface-2); border-radius: var(--ds-radius); padding: 14px 16px; margin-bottom: 16px; }
                    .apd-rating__big { font-family: var(--ds-font-heading); font-size: 2rem; font-weight: 800; color: var(--ds-ink-strong); line-height: 1; }
                    .apd-stars { color: var(--ds-accent); font-size: 1.1rem; }
                    .apd-review { background: var(--ds-surface-2); border: 1px solid var(--ds-border); border-radius: var(--ds-radius); padding: 13px; }
                    .apd-review + .apd-review { margin-top: 10px; }
                    .apd-review__head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
                    .apd-review__who { font-weight: 700; color: var(--ds-ink-strong); font-size: .85rem; }
                    .apd-empty { text-align: center; color: var(--ds-muted); padding: 22px; border: 1px dashed var(--ds-border-strong); border-radius: var(--ds-radius); }
                    .apd-rejbanner { display: flex; align-items: flex-start; gap: 11px; background: var(--ds-danger-soft); border: 1px solid var(--ds-danger); border-radius: var(--ds-radius); padding: 14px 16px; margin-bottom: 20px; color: #a3322e; font-size: .9rem; line-height: 1.5; }
                    .apd-rejbanner i { font-size: 1.4rem; flex-shrink: 0; }

                    @media (min-width: 768px) { .apd-metrics { grid-template-columns: repeat(6, minmax(0,1fr)); } .apd-head h1 { font-size: 1.5rem; } }
                </style>

                <div class="apd-head">
                    <div class="apd-head__l">
                        <a href="<?= ROOT ?>/Admins/projects_management" class="adm-back"><i class='bx bx-left-arrow-alt'></i></a>
                        <div><h1><?= htmlspecialchars($project->title ?? 'Détails du projet') ?></h1><p>par <strong><?= htmlspecialchars($author) ?></strong> · <span class="apd-pill apd-pill--<?= $stCls ?>"><?= htmlspecialchars($adminStatus) ?></span></p></div>
                    </div>
                    <div class="apd-mod">
                        <form method="POST" action="<?= ROOT ?>/Admins/project_detail/<?= $pid ?>"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>"><button class="apd-modbtn apd-modbtn--ok" type="submit" name="validate_project"><i class='bx bx-check'></i> Valider</button></form>
                        <form method="POST" action="<?= ROOT ?>/Admins/project_detail/<?= $pid ?>"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>"><button class="apd-modbtn apd-modbtn--wait" type="submit" name="set_pending_project"><i class='bx bx-time'></i> Attente</button></form>
                        <form method="POST" action="<?= ROOT ?>/Admins/project_detail/<?= $pid ?>" onsubmit="var r=prompt('Motif du rejet (communiqué à l\'étudiant, optionnel) :', this.reject_reason.value); if(r===null){return false;} this.reject_reason.value=r; return true;"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>"><input type="hidden" name="reject_reason" value="<?= htmlspecialchars((string) ($project->reject_reason ?? '')) ?>"><button class="apd-modbtn apd-modbtn--no" type="submit" name="reject_project"><i class='bx bx-x'></i> Rejeter</button></form>
                    </div>
                </div>

                <?php if ($stCls === 'rejete' && !empty($project->reject_reason)): ?>
                    <div class="apd-rejbanner"><i class='bx bx-message-square-x'></i><div><strong>Motif du rejet communiqué à l'étudiant :</strong><br><?= htmlspecialchars((string) $project->reject_reason) ?></div></div>
                <?php endif; ?>

                <div class="apd-metrics">
                    <?php foreach ($metricCards as $m): ?>
                        <div class="apd-metric apd-metric--<?= $m[3] ?>"><i class='bx <?= $m[2] ?>'></i><div class="apd-metric__v"><?= $m[1] ?></div><div class="apd-metric__l"><?= htmlspecialchars($m[0]) ?></div></div>
                    <?php endforeach; ?>
                </div>

                <div class="row gy-0">
                    <div class="col-xl-8">
                        <div class="apd-card">
                            <h2><i class='bx bx-detail'></i> Description</h2>
                            <div class="apd-desc"><?= nl2br(htmlspecialchars((string) ($project->description ?? 'Aucune description fournie.'), ENT_QUOTES, 'UTF-8')) ?></div>
                        </div>

                        <div class="apd-card">
                            <h2><i class='bx bx-code-alt'></i> Technologies &amp; vidéo</h2>
                            <div class="apd-row"><span class="apd-row__ico"><i class='bx bx-chip'></i></span><div><small>Technologies</small><strong><?= htmlspecialchars((string) ($project->technologies ?? 'Non précisées')) ?></strong></div></div>
                            <div class="apd-row"><span class="apd-row__ico"><i class='bx bxl-youtube'></i></span><div><small>Lien vidéo</small><?php if (!empty($project->video)): ?><a href="<?= htmlspecialchars((string) $project->video) ?>" target="_blank" rel="noopener"><?= htmlspecialchars((string) $project->video) ?></a><?php else: ?><strong class="text-muted">Aucune vidéo</strong><?php endif; ?></div></div>
                        </div>

                        <div class="apd-card">
                            <h2><i class='bx bx-images'></i> Galerie d'images</h2>
                            <?php if (!empty($images)): ?>
                                <div class="apd-gallery">
                                    <?php foreach ($images as $image): ?><img src="<?= ROOT_IMG ?>/uploads/projects/images/<?= rawurlencode((string) ($image->image ?? '')) ?>" alt="" loading="lazy"><?php endforeach; ?>
                                </div>
                            <?php else: ?><div class="apd-empty">Aucune image disponible.</div><?php endif; ?>
                        </div>

                        <div class="apd-card">
                            <h2><i class='bx bx-folder'></i> Documents joints</h2>
                            <?php if (!empty($files)): ?>
                                <?php foreach ($files as $file): ?>
                                    <a href="<?= ROOT_IMG ?>/uploads/projects/files/<?= rawurlencode((string) ($file->fichier ?? '')) ?>" target="_blank" rel="noopener" class="apd-doc"><i class='bx bx-file'></i><div><div style="font-weight:700;color:var(--ds-ink-strong)"><?= htmlspecialchars((string) ($file->fichier ?? 'Document')) ?></div><small style="color:var(--ds-muted)">Cliquez pour consulter</small></div></a>
                                <?php endforeach; ?>
                            <?php else: ?><div class="apd-empty">Aucun fichier disponible.</div><?php endif; ?>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="apd-card">
                            <h2><i class='bx bx-info-circle'></i> Méta-informations</h2>
                            <div class="apd-row"><span class="apd-row__ico"><i class='bx bx-buildings'></i></span><div><small>Université / Filière</small><strong><?= htmlspecialchars((string) ($project->universite ?? '—')) ?></strong><br><small><?= htmlspecialchars((string) ($project->filiere ?? 'N/A')) ?></small></div></div>
                            <div class="apd-row"><span class="apd-row__ico"><i class='bx bx-at'></i></span><div><small>Email de l'auteur</small><a href="mailto:<?= htmlspecialchars((string) ($project->email ?? '')) ?>"><?= htmlspecialchars((string) ($project->email ?? '—')) ?></a></div></div>
                            <div class="apd-row"><span class="apd-row__ico"><i class='bx bx-calendar'></i></span><div><small>Publié le</small><strong><?= !empty($project->created_at) ? htmlspecialchars(date('d/m/Y à H:i', strtotime((string) $project->created_at))) : '—' ?></strong></div></div>
                        </div>

                        <div class="apd-card">
                            <h2><i class='bx bxs-star'></i> Avis des visiteurs</h2>
                            <div class="apd-rating">
                                <div><div class="apd-rating__big"><?= number_format($avgRating, 1) ?></div><small style="color:var(--ds-muted)">sur 5</small></div>
                                <div class="text-end"><div class="apd-stars"><?php for ($i = 1; $i <= 5; $i++): ?><i class='bx <?= $i <= round($avgRating) ? 'bxs-star' : 'bx-star' ?>'></i><?php endfor; ?></div><small style="color:var(--ds-muted)"><?= $totalReviews ?> avis</small></div>
                            </div>
                            <?php if (!empty($reviews)): ?>
                                <?php foreach (array_slice($reviews, 0, 5) as $review): ?>
                                    <?php $rWho = trim((string) (($review->nom ?? '') . ' ' . ($review->prenom ?? ''))); if ($rWho === '') { $rWho = 'Visiteur'; } ?>
                                    <div class="apd-review">
                                        <div class="apd-review__head"><span class="apd-review__who"><?= htmlspecialchars($rWho) ?></span><span class="apd-stars" style="font-size:.85rem"><?php for ($i = 1; $i <= 5; $i++): ?><i class='bx <?= $i <= (int) ($review->rating ?? 0) ? 'bxs-star' : 'bx-star' ?>'></i><?php endfor; ?></span></div>
                                        <p class="mb-0" style="color:var(--ds-ink);font-size:.85rem;line-height:1.5"><?= htmlspecialchars((string) ($review->review ?? '')) ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?><div class="apd-empty">Aucun avis pour le moment.</div><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>
<?php $this->view('Partials/scripts'); ?>
</body>
</html>
