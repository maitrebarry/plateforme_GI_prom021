<?php
$derPosts = $derPosts ?? [];
$derAllowedTypes = $derAllowedTypes ?? [];
$paginationQuery = (string) ($paginationQuery ?? '');
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 10);
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalItems = max(0, (int) ($totalItems ?? count($derPosts)));
$activeEditPostId = (int) ($activeEditPostId ?? 0);
$formAction = (string) ($formAction ?? (ROOT . '/Homes/der_espace'));
$paginationBasePath = (string) ($paginationBasePath ?? 'Homes/der_espace');
$detailReturnBasePath = (string) ($detailReturnBasePath ?? 'Homes/der_espace');
?>
<div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-3">
    <h5 class="mb-0">Publications DER</h5>
    <span class="text-muted"><?= $totalItems ?> element(s)</span>
</div>

<?php if (!empty($derPosts)): ?>
    <?php foreach ($derPosts as $post): ?>
        <article class="der-post-card <?= $activeEditPostId === (int) ($post->id ?? 0) ? 'border border-primary shadow-sm' : '' ?>" id="der-post-<?= (int) ($post->id ?? 0) ?>">
            <div class="d-flex justify-content-between gap-3 flex-wrap mb-2">
                <h5 class="mb-0"><?= htmlspecialchars((string) ($post->titre ?? 'Publication')) ?></h5>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="der-type-badge"><?= htmlspecialchars((string) ($post->type ?? 'publication')) ?></span>
                    <?php if ((int) ($post->is_archived ?? 0) === 1): ?>
                        <span class="badge bg-secondary">Archivee</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($activeEditPostId === (int) ($post->id ?? 0)): ?>
                <div class="alert alert-primary py-2 px-3 mb-3">
                    Cette publication est prete a etre modifiee dans ce bloc.
                </div>
            <?php endif; ?>
            <div class="der-post-meta">
                <span>Date: <?= htmlspecialchars((string) ($post->publication_date ?? '')) ?></span>
                <span>Auteur: <?= htmlspecialchars((string) ($post->author_name ?? 'Responsable DER')) ?></span>
                <span>Cree le: <?= htmlspecialchars(!empty($post->created_at) ? date('Y-m-d', strtotime((string) $post->created_at)) : '') ?></span>
            </div>
            <p class="mb-3"><?= nl2br(htmlspecialchars((string) ($post->contenu ?? ''))) ?></p>

            <?php if (!empty($post->files ?? [])): ?>
                <div class="mb-3">
                    <?php foreach (($post->files ?? []) as $file): ?>
                        <?php $relativePath = ltrim(str_replace('\\', '/', (string) ($file->file_path ?? '')), '/'); ?>
                        <a class="der-file-link" href="<?= ROOT . '/' . $relativePath ?>" target="_blank" rel="noopener">
                            <i class='bx bx-paperclip'></i>
                            <?= htmlspecialchars((string) ($file->original_name ?? 'Fichier')) ?>
                        </a>
                        <form method="POST" action="<?= htmlspecialchars($formAction) ?>" class="d-inline">
                            <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                            <input type="hidden" name="file_id" value="<?= (int) ($file->id ?? 0) ?>">
                            <button type="submit" name="delete_der_file" class="btn btn-outline-danger btn-sm mb-2" onclick="return confirm('Supprimer ce fichier joint ?');">Supprimer le fichier</button>
                        </form>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= htmlspecialchars($formAction) ?>" class="row gy-2">
                <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                <input type="hidden" name="post_id" value="<?= (int) ($post->id ?? 0) ?>">
                <div class="col-md-4">
                    <input type="text" name="titre" value="<?= htmlspecialchars((string) ($post->titre ?? '')) ?>" class="common-input common-input--bg">
                </div>
                <div class="col-md-2">
                    <select name="type" class="common-input common-input--bg">
                        <?php foreach ($derAllowedTypes as $type): ?>
                            <option value="<?= htmlspecialchars($type) ?>" <?= (string) ($post->type ?? '') === $type ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($type)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_publication" value="<?= htmlspecialchars((string) ($post->publication_date ?? '')) ?>" class="common-input common-input--bg">
                </div>
                <div class="col-md-4">
                    <textarea name="contenu" class="common-input common-input--bg" rows="3"><?= htmlspecialchars((string) ($post->contenu ?? '')) ?></textarea>
                </div>
                <div class="col-12 d-flex gap-2 flex-wrap">
                    <a href="<?= ROOT ?>/Homes/der_publication_detail/<?= (int) ($post->id ?? 0) ?>?return=<?= urlencode(trim($detailReturnBasePath, '/') . ($paginationQuery !== '' ? '?' . $paginationQuery : '')) ?>" class="btn btn-outline-secondary btn-sm">Voir detail</a>
                    <?php if ((int) ($post->is_archived ?? 0) === 1): ?>
                        <button type="submit" name="update_der_post" class="btn btn-success btn-sm">Mettre a jour</button>
                    <?php else: ?>
                        <button type="submit" name="update_der_post" class="btn btn-success btn-sm">Mettre a jour</button>
                    <?php endif; ?>
                </div>
            </form>

            <div class="d-flex gap-2 flex-wrap mt-2">
                <?php if ((int) ($post->is_archived ?? 0) === 1): ?>
                    <form method="POST" action="<?= htmlspecialchars($formAction) ?>" class="d-inline">
                        <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                        <input type="hidden" name="post_id" value="<?= (int) ($post->id ?? 0) ?>">
                        <button type="submit" name="restore_der_post" class="btn btn-outline-primary btn-sm">Restaurer</button>
                    </form>
                    <?php if (str_contains($formAction, '/Homes/der_corbeille')): ?>
                        <form method="POST" action="<?= htmlspecialchars($formAction) ?>" class="d-inline">
                            <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                            <input type="hidden" name="post_id" value="<?= (int) ($post->id ?? 0) ?>">
                            <button type="submit" name="purge_der_post" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer definitivement cette publication et ses fichiers ?');">Supprimer definitivement</button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <form method="POST" action="<?= htmlspecialchars($formAction) ?>" class="d-inline">
                        <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                        <input type="hidden" name="post_id" value="<?= (int) ($post->id ?? 0) ?>">
                        <button type="submit" name="delete_der_post" class="btn btn-danger btn-sm" onclick="return confirm('Archiver cette publication DER ?');">Archiver</button>
                    </form>

                    <form method="POST" action="<?= htmlspecialchars($formAction) ?>" enctype="multipart/form-data" class="row gy-2 mt-0 flex-grow-1">
                        <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                        <input type="hidden" name="post_id" value="<?= (int) ($post->id ?? 0) ?>">
                        <div class="col-md-8">
                            <input type="file" name="fichiers[]" class="common-input common-input--bg" multiple>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" name="attach_der_files" class="btn btn-outline-primary btn-sm w-100">Ajouter des fichiers</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </article>
    <?php endforeach; ?>
<?php else: ?>
    <div class="text-center py-5 text-muted">Aucune publication DER ne correspond aux filtres.</div>
<?php endif; ?>

<?php $this->view('Partials/admin-pagination', [
    'currentPage' => $currentPage,
    'perPage' => $perPage,
    'totalPages' => $totalPages,
    'totalItems' => $totalItems,
    'basePath' => $paginationBasePath,
    'queryString' => $paginationQuery,
    'itemLabel' => 'publication(s)',
]); ?>
