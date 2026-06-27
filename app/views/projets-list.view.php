<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Mes projets']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php $projects = $projects ?? []; ?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>

            <div class="dashboard-body__content p-3 p-lg-4">
                <?php $this->view('set_flash'); ?>

                <style>
                    .mp-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 18px; }
                    .mp-title { display: flex; align-items: center; gap: 9px; font-family: var(--ds-font-heading); font-size: 1.3rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0; }
                    .mp-title i { color: var(--ds-brand-600); }
                    .mp-btn { display: inline-flex; align-items: center; gap: 7px; background: var(--ds-brand-600); color: #fff; font-weight: 700; font-size: .9rem; padding: 10px 18px; border-radius: var(--ds-radius-pill); text-decoration: none; transition: all var(--ds-transition); }
                    .mp-btn:hover { background: var(--ds-brand-700); color: #fff; transform: translateY(-1px); }

                    .mp-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); overflow: hidden; height: 100%; display: flex; flex-direction: column; box-shadow: var(--ds-shadow-sm); transition: transform var(--ds-transition), box-shadow var(--ds-transition), border-color var(--ds-transition); }
                    .mp-card:hover { transform: translateY(-4px); box-shadow: var(--ds-shadow-md); border-color: var(--ds-brand-200); }
                    .mp-card__media { position: relative; overflow: hidden; line-height: 0; }
                    .mp-card__media img { width: 100%; height: 168px; object-fit: cover; transition: transform .5s ease; }
                    .mp-card:hover .mp-card__media img { transform: scale(1.05); }
                    .mp-card__cat { position: absolute; top: 12px; left: 12px; display: inline-flex; align-items: center; gap: 5px; background: rgba(255,255,255,.92); color: var(--ds-brand-700); font-weight: 700; font-size: .74rem; padding: 5px 11px; border-radius: var(--ds-radius-pill); }
                    .mp-card__status { position: absolute; top: 12px; right: 12px; display: inline-flex; align-items: center; height: 24px; padding: 0 11px; border-radius: var(--ds-radius-pill); font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .03em; }
                    .mp-card__status--ok { background: #e4f3ea; color: #11703a; }
                    .mp-card__status--pending { background: var(--ds-accent-soft); color: #8a6310; }
                    .mp-card__status--no { background: var(--ds-danger-soft); color: #a3322e; }
                    .mp-card__status--draft { background: rgba(255,255,255,.92); color: var(--ds-muted); }
                    .mp-card__status i { font-size: .85rem; margin-right: 3px; }
                    .mp-reject { display: flex; align-items: flex-start; gap: 7px; background: var(--ds-danger-soft); border: 1px solid var(--ds-danger); color: #a3322e; border-radius: var(--ds-radius); padding: 9px 11px; font-size: .8rem; line-height: 1.45; margin-bottom: 12px; }
                    .mp-reject i { font-size: 1rem; flex-shrink: 0; margin-top: 1px; }
                    .mp-card__body { padding: 16px; display: flex; flex-direction: column; flex: 1; }
                    .mp-card__title { font-family: var(--ds-font-heading); font-size: 1.05rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0 0 8px; line-height: 1.3; }
                    .mp-card__desc { color: var(--ds-muted); font-size: .86rem; line-height: 1.5; margin: 0 0 12px; }
                    .mp-card__tech { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 14px; }
                    .mp-tech { font-size: .72rem; font-weight: 600; background: var(--ds-surface-2); color: var(--ds-ink); border: 1px solid var(--ds-border); padding: 3px 9px; border-radius: var(--ds-radius-pill); }
                    .mp-card__actions { margin-top: auto; display: flex; gap: 8px; }
                    .mp-act { display: inline-flex; align-items: center; gap: 6px; font-weight: 700; font-size: .82rem; padding: 8px 14px; border-radius: var(--ds-radius-pill); text-decoration: none; transition: all var(--ds-transition); }
                    .mp-act--view { background: var(--ds-brand-50); color: var(--ds-brand-700); }
                    .mp-act--view:hover { background: var(--ds-brand-600); color: #fff; }
                    .mp-act--edit { background: var(--ds-accent-soft); color: #8a6310; }
                    .mp-act--edit:hover { background: var(--ds-accent); color: #3d2900; }

                    .mp-empty { text-align: center; background: var(--ds-surface); border: 1px dashed var(--ds-border-strong); border-radius: var(--ds-radius-lg); padding: 48px 24px; }
                    .mp-empty i { font-size: 2.8rem; color: var(--ds-brand-300); }
                    .mp-empty h3 { font-family: var(--ds-font-heading); font-size: 1.15rem; color: var(--ds-ink-strong); margin: 12px 0 6px; }
                    .mp-empty p { color: var(--ds-muted); margin: 0 auto 18px; max-width: 360px; }
                </style>

                <div class="mp-head">
                    <h2 class="mp-title"><i class='bx bx-folder'></i> Mes projets <span class="text-muted fw-normal" style="font-size:.9rem">(<?= count($projects) ?>)</span></h2>
                    <a href="<?= ROOT ?>/Projets/publier_projet" class="mp-btn"><i class='bx bx-plus-circle'></i> Publier un projet</a>
                </div>

                <?php if (!empty($projects)): ?>
                    <div class="row g-4">
                        <?php foreach ($projects as $project): ?>
                            <?php
                            $adminStatus = (string) ($project->admin_status ?? 'en_attente');
                            $statusMap = [
                                'valide' => ['ok', 'Validé', 'bx-check-circle'],
                                'en_attente' => ['pending', 'En attente', 'bx-time-five'],
                                'rejete' => ['no', 'Rejeté', 'bx-x-circle'],
                            ];
                            $st = $statusMap[$adminStatus] ?? $statusMap['en_attente'];
                            $rejectReason = trim((string) ($project->reject_reason ?? ''));
                            $cleanDesc = trim(strip_tags((string) ($project->description ?? '')));
                            $techs = array_values(array_filter(array_map('trim', explode(',', (string) ($project->technologies ?? '')))));
                            ?>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="mp-card">
                                    <div class="mp-card__media">
                                        <?php if (!empty($project->image)): ?>
                                            <img src="<?= ROOT_IMG ?>/uploads/projects/images/<?= rawurlencode((string) $project->image) ?>" alt="<?= htmlspecialchars((string) ($project->title ?? '')) ?>" loading="lazy">
                                        <?php else: ?>
                                            <img src="<?= ROOT ?>/assets/images/thumbs/product-img1.png" alt="" loading="lazy">
                                        <?php endif; ?>
                                        <span class="mp-card__cat"><i class='bx bx-category'></i><?= htmlspecialchars((string) ($project->categorie ?? 'Projet')) ?></span>
                                        <span class="mp-card__status mp-card__status--<?= $st[0] ?>"><i class='bx <?= $st[2] ?>'></i><?= $st[1] ?></span>
                                    </div>
                                    <div class="mp-card__body">
                                        <h3 class="mp-card__title"><?= htmlspecialchars((string) ($project->title ?? 'Projet')) ?></h3>
                                        <?php if ($adminStatus === 'rejete' && $rejectReason !== ''): ?>
                                            <div class="mp-reject"><i class='bx bx-message-square-x'></i><span><strong>Motif du rejet :</strong> <?= htmlspecialchars($rejectReason) ?></span></div>
                                        <?php endif; ?>
                                        <?php if ($cleanDesc !== ''): ?><p class="mp-card__desc"><?= htmlspecialchars(mb_strimwidth($cleanDesc, 0, 110, '…')) ?></p><?php endif; ?>
                                        <?php if (!empty($techs)): ?>
                                            <div class="mp-card__tech">
                                                <?php foreach (array_slice($techs, 0, 4) as $tech): ?><span class="mp-tech"><?= htmlspecialchars($tech) ?></span><?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="mp-card__actions">
                                            <a href="<?= ROOT ?>/Projets/detail/<?= (int) ($project->id ?? 0) ?>" class="mp-act mp-act--view"><i class='bx bx-show'></i> Voir</a>
                                            <a href="<?= ROOT ?>/Projets/modifier/<?= (int) ($project->id ?? 0) ?>" class="mp-act mp-act--edit"><i class='bx bx-edit-alt'></i> Modifier</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="mp-empty">
                        <i class='bx bx-folder-open'></i>
                        <h3>Aucun projet publié</h3>
                        <p>Commencez par publier votre premier projet pour le mettre en avant sur la vitrine.</p>
                        <a href="<?= ROOT ?>/Projets/publier_projet" class="mp-btn"><i class='bx bx-plus-circle'></i> Publier un projet</a>
                    </div>
                <?php endif; ?>
            </div>

            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>

<?php $this->view('Partials/scripts'); ?>
</body>
</html>
