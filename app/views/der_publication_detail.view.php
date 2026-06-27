<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Détail publication DER']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$post = $post ?? null;
$returnUrl = $returnUrl ?? (ROOT . '/Homes/der_espace');
$isArchived = (int) ($post->is_archived ?? 0) === 1;
if (!function_exists('department_post_file_is_image')) {
    function department_post_file_is_image(object $file): bool
    {
        $type = strtolower((string) ($file->file_type ?? ''));
        $name = strtolower((string) ($file->original_name ?? ''));
        $path = strtolower((string) ($file->file_path ?? ''));
        $ext = pathinfo($path !== '' ? $path : $name, PATHINFO_EXTENSION);
        return str_starts_with($type, 'image/') || in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
    }
}
$files = $post->files ?? [];
$imageFiles = array_values(array_filter($files, static fn($f) => department_post_file_is_image($f)));
$docFiles = array_values(array_filter($files, static fn($f) => !department_post_file_is_image($f)));
$postType = (string) ($post->type ?? 'publication');
$typeMeta = [
    'annonce' => ['Annonce', 'bx-megaphone', 'ann'], 'information' => ['Information', 'bx-info-circle', 'info'],
    'evenement' => ['Événement', 'bx-calendar-event', 'evt'], 'resultat' => ['Résultat', 'bx-award', 'res'],
    'opportunite' => ['Opportunité', 'bx-briefcase-alt-2', 'opp'],
];
$tm = $typeMeta[$postType] ?? [ucfirst($postType), 'bx-bookmark', 'info'];
?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-3 p-lg-4">
                <?php $this->view('set_flash'); ?>

                <style>
                    .dpd-wrap { max-width: 900px; margin: 0 auto; }
                    .dpd-back { display: inline-flex; align-items: center; gap: 6px; color: var(--ds-muted); font-weight: 600; font-size: .88rem; text-decoration: none; margin-bottom: 14px; }
                    .dpd-back:hover { color: var(--ds-brand-600); }
                    .dpd-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); padding: 24px; box-shadow: var(--ds-shadow-sm); }
                    .dpd-badges { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 8px; }
                    .dpd-type { display: inline-flex; align-items: center; gap: 6px; font-size: .76rem; font-weight: 800; padding: 5px 13px; border-radius: var(--ds-radius-pill); background: var(--ds-surface-2); color: var(--ds-muted); }
                    .dpd-type--ann { background: var(--ds-danger-soft); color: #a3322e; } .dpd-type--info { background: var(--ds-brand-50); color: var(--ds-brand-700); }
                    .dpd-type--evt { background: #e3effb; color: #1d59b8; } .dpd-type--res { background: #e4f3ea; color: #11703a; } .dpd-type--opp { background: #f6e7d8; color: #9a5a2b; }
                    .dpd-status { display: inline-flex; align-items: center; gap: 5px; font-size: .76rem; font-weight: 800; padding: 5px 13px; border-radius: var(--ds-radius-pill); }
                    .dpd-status--on { background: #e4f3ea; color: #11703a; } .dpd-status--off { background: var(--ds-surface-2); color: var(--ds-muted); }
                    .dpd-title { font-family: var(--ds-font-heading); font-size: 1.55rem; font-weight: 800; line-height: 1.25; color: var(--ds-ink-strong); margin: 12px 0 10px; }
                    .dpd-meta { display: flex; flex-wrap: wrap; gap: 14px; margin-bottom: 18px; }
                    .dpd-meta span { display: inline-flex; align-items: center; gap: 5px; color: var(--ds-muted); font-size: .82rem; font-weight: 600; }
                    .dpd-meta i { color: var(--ds-brand-600); }
                    .dpd-content { color: var(--ds-ink); line-height: 1.85; font-size: 1rem; }
                    .dpd-gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px; margin-top: 22px; }
                    .dpd-gallery a { line-height: 0; border-radius: var(--ds-radius); overflow: hidden; cursor: zoom-in; }
                    .dpd-gallery img { width: 100%; height: 150px; object-fit: cover; }
                    .dpd-docs { margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--ds-border); }
                    .dpd-docs h3 { display: flex; align-items: center; gap: 8px; font-family: var(--ds-font-heading); font-size: 1.05rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0 0 12px; }
                    .dpd-docs h3 i { color: var(--ds-brand-600); }
                    .dpd-doc { display: inline-flex; align-items: center; gap: 7px; margin: 0 8px 8px 0; padding: 9px 15px; border-radius: var(--ds-radius-pill); border: 1px solid var(--ds-border); background: var(--ds-surface-2); color: var(--ds-ink); text-decoration: none; font-weight: 600; font-size: .85rem; }
                    .dpd-doc:hover { background: var(--ds-brand-600); color: #fff; }
                    .dpd-doc i { color: var(--ds-brand-600); } .dpd-doc:hover i { color: #fff; }
                    .dpd-edit { display: inline-flex; align-items: center; gap: 6px; background: var(--ds-brand-50); color: var(--ds-brand-700); font-weight: 700; font-size: .85rem; padding: 9px 16px; border-radius: var(--ds-radius-pill); text-decoration: none; margin-top: 18px; }
                    .dpd-edit:hover { background: var(--ds-brand-600); color: #fff; }
                </style>

                <div class="dpd-wrap">
                    <a href="<?= htmlspecialchars($returnUrl) ?>" class="dpd-back"><i class='bx bx-left-arrow-alt'></i> Retour à la gestion</a>
                    <article class="dpd-card">
                        <div class="dpd-badges">
                            <span class="dpd-type dpd-type--<?= $tm[2] ?>"><i class='bx <?= htmlspecialchars($tm[1]) ?>'></i> <?= htmlspecialchars($tm[0]) ?></span>
                            <span class="dpd-status dpd-status--<?= $isArchived ? 'off' : 'on' ?>"><i class='bx <?= $isArchived ? 'bx-archive' : 'bx-check-circle' ?>'></i> <?= $isArchived ? 'Archivée' : 'Active' ?></span>
                        </div>
                        <h1 class="dpd-title"><?= htmlspecialchars((string) ($post->titre ?? 'Publication')) ?></h1>
                        <div class="dpd-meta">
                            <span><i class='bx bx-calendar'></i> <?= htmlspecialchars((string) ($post->publication_date ?? '')) ?></span>
                            <span><i class='bx bx-user-circle'></i> <?= htmlspecialchars((string) ($post->author_name ?? 'Responsable DER')) ?></span>
                        </div>
                        <div class="dpd-content"><?= nl2br(htmlspecialchars((string) ($post->contenu ?? ''))) ?></div>

                        <?php if (!empty($imageFiles)): ?>
                            <div class="dpd-gallery">
                                <?php foreach ($imageFiles as $file): ?>
                                    <?php $rp = ltrim(str_replace('\\', '/', (string) ($file->file_path ?? '')), '/'); ?>
                                    <a href="<?= ROOT . '/' . $rp ?>" onclick="event.preventDefault(); derLightbox(this.href);"><img src="<?= ROOT . '/' . $rp ?>" alt="<?= htmlspecialchars((string) ($file->original_name ?? '')) ?>" loading="lazy"></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($docFiles)): ?>
                            <div class="dpd-docs">
                                <h3><i class='bx bx-folder'></i> Fichiers joints</h3>
                                <?php foreach ($docFiles as $file): ?>
                                    <?php $rp = ltrim(str_replace('\\', '/', (string) ($file->file_path ?? '')), '/'); ?>
                                    <a class="dpd-doc" href="<?= ROOT . '/' . $rp ?>" target="_blank" rel="noopener"><i class='bx bx-file'></i> <?= htmlspecialchars((string) ($file->original_name ?? 'Fichier')) ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div>
                            <a href="<?= ROOT ?>/Homes/der_espace?edit=<?= (int) ($post->id ?? 0) ?>#manage-der-posts" class="dpd-edit"><i class='bx bx-edit-alt'></i> Modifier cette publication</a>
                        </div>
                    </article>
                </div>
            </div>

            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>

<?php $this->view('Partials/scripts'); ?>
<div id="derLightbox" style="display:none; position:fixed; inset:0; background:rgba(8,18,14,.92); z-index:9999; cursor:zoom-out; align-items:center; justify-content:center; padding:20px;">
    <img id="derLightboxImg" alt="" style="max-width:94%; max-height:92%; object-fit:contain; border-radius:12px;">
</div>
<script>
    function derLightbox(src) { var lb = document.getElementById('derLightbox'); document.getElementById('derLightboxImg').src = src; lb.style.display = 'flex'; }
    document.getElementById('derLightbox') && document.getElementById('derLightbox').addEventListener('click', function () { this.style.display = 'none'; });
</script>
</body>
</html>
