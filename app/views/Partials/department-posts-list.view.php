<?php
$departmentPosts = $departmentPosts ?? [];
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 6);
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalItems = max(0, (int) ($totalItems ?? count($departmentPosts)));
$paginationQuery = (string) ($paginationQuery ?? '');
?>
<?php if (!empty($departmentPosts)): ?>
    <div class="row gy-3">
        <?php foreach ($departmentPosts as $item): ?>
            <div class="col-lg-6">
                <div class="department-post-card">
                    <strong><?= htmlspecialchars((string) ($item->titre ?? '')) ?></strong>
                    <div class="department-post-meta">
                        <span><?= htmlspecialchars((string) ($item->publication_date ?? '')) ?></span>
                        <span><?= htmlspecialchars(ucfirst((string) ($item->type ?? 'publication'))) ?></span>
                        <span><?= htmlspecialchars((string) ($item->author_name ?? 'Responsable DER')) ?></span>
                    </div>
                    <div class="mb-3"><?= nl2br(htmlspecialchars((string) ($item->contenu ?? ''))) ?></div>
                    <?php if (!empty($item->files ?? [])): ?>
                        <div class="mt-2">
                            <small class="text-muted d-block mb-1">Documents a telecharger</small>
                            <?php foreach (($item->files ?? []) as $file): ?>
                                <?php $relativePath = ltrim(str_replace('\\', '/', (string) ($file->file_path ?? '')), '/'); ?>
                                <a class="department-file-link" href="<?= ROOT . '/' . $relativePath ?>" target="_blank" rel="noopener">
                                    <?= htmlspecialchars((string) ($file->original_name ?? 'Fichier')) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="mt-3">
                        <a href="<?= ROOT ?>/Homes/department_publication_detail/<?= (int) ($item->id ?? 0) ?>?return=<?= urlencode($paginationQuery) ?>" class="btn btn-outline-primary btn-sm">Voir detail</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php $this->view('Partials/admin-pagination', [
        'currentPage' => $currentPage,
        'perPage' => $perPage,
        'totalPages' => $totalPages,
        'totalItems' => $totalItems,
        'basePath' => 'Homes/departement',
        'queryString' => $paginationQuery,
        'itemLabel' => 'publication(s)',
    ]); ?>
<?php else: ?>
    <p class="text-muted mb-0">Aucune publication du DER n'est encore visible sur le site.</p>
<?php endif; ?>
