<?php
$departmentPosts = $departmentPosts ?? [];
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 6);
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalItems = max(0, (int) ($totalItems ?? count($departmentPosts)));
$paginationQuery = (string) ($paginationQuery ?? '');

if (!function_exists('department_post_file_is_image')) {
    function department_post_file_is_image(object $file): bool
    {
        $type = strtolower((string) ($file->file_type ?? ''));
        $path = strtolower((string) ($file->file_path ?? ''));
        $name = strtolower((string) ($file->original_name ?? ''));
        $extension = pathinfo($path !== '' ? $path : $name, PATHINFO_EXTENSION);

        return str_starts_with($type, 'image/')
            || in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)
            || in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
    }
}
?>
<?php if (!empty($departmentPosts)): ?>
    <div class="row gy-3">
        <?php foreach ($departmentPosts as $item): ?>
            <?php
            $files = $item->files ?? [];
            $imageFiles = array_values(array_filter($files, static fn($file) => department_post_file_is_image($file)));
            $documentFiles = array_values(array_filter($files, static fn($file) => !department_post_file_is_image($file)));
            ?>
            <div class="col-lg-6">
                <div class="department-post-card">
                    <?php if (!empty($imageFiles)): ?>
                        <div class="department-image-preview department-image-preview--<?= min(count($imageFiles), 3) ?>">
                            <?php foreach (array_slice($imageFiles, 0, 3) as $index => $file): ?>
                                <?php $relativePath = ltrim(str_replace('\\', '/', (string) ($file->file_path ?? '')), '/'); ?>
                                <a href="<?= ROOT . '/' . $relativePath ?>" class="department-image-preview__item" target="_blank" rel="noopener">
                                    <img src="<?= ROOT . '/' . $relativePath ?>" alt="<?= htmlspecialchars((string) ($file->original_name ?? 'Image publication')) ?>">
                                    <?php if ($index === 2 && count($imageFiles) > 3): ?>
                                        <span>+<?= count($imageFiles) - 3 ?></span>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <strong><?= htmlspecialchars((string) ($item->titre ?? '')) ?></strong>
                    <div class="department-post-meta">
                        <span><?= htmlspecialchars((string) ($item->publication_date ?? '')) ?></span>
                        <span><?= htmlspecialchars(ucfirst((string) ($item->type ?? 'publication'))) ?></span>
                        <span><?= htmlspecialchars((string) ($item->author_name ?? 'Responsable Scolaire')) ?></span>
                    </div>
                    <div class="mb-3"><?= nl2br(htmlspecialchars((string) ($item->contenu ?? ''))) ?></div>
                    <?php if (!empty($documentFiles)): ?>
                        <div class="mt-2">
                            <small class="text-muted d-block mb-1">Documents à télécharger</small>
                            <?php foreach ($documentFiles as $file): ?>
                                <?php $relativePath = ltrim(str_replace('\\', '/', (string) ($file->file_path ?? '')), '/'); ?>
                                <a class="department-file-link" href="<?= ROOT . '/' . $relativePath ?>" target="_blank" rel="noopener">
                                    <i class="las la-file-alt"></i>
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
    <p class="text-muted mb-0">Aucune publication du responsable scolaire n'est encore visible sur le site.</p>
<?php endif; ?>
