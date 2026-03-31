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
<style>
/* DER Post Cards */
.der-post-card {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 18px;
    padding: 1.5rem;
    margin-bottom: 1.25rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    transition: all 0.3s;
    position: relative;
}

.der-post-card:hover {
    box-shadow: 0 10px 25px -10px rgba(0,0,0,0.1);
    border-color: #e0e7ff;
}

.der-post-card.active-edit {
    border: 2px solid #6366f1;
    box-shadow: 0 0 0 4px rgba(99,102,241,0.08);
}

.der-post-card h5 {
    font-weight: 800;
    font-size: 1.05rem;
    color: #0f172a;
    border: none;
    padding: 0;
    margin: 0;
}

.der-type-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 14px;
    border-radius: 999px;
    background: #ede9fe;
    color: #5b21b6;
    font-weight: 800;
    font-size: .73rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.der-post-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    color: #64748b;
    font-size: .85rem;
    margin: 8px 0 12px;
}

.der-post-meta span {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Inline edit form inside each card */
.der-inline-form {
    background: #f8fafc;
    border-radius: 14px;
    padding: 1rem 1.25rem;
    border: 1px solid #f1f5f9;
    margin-top: 1rem;
}

.der-inline-form input[type="text"],
.der-inline-form input[type="date"],
.der-inline-form select,
.der-inline-form textarea {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.6rem 0.9rem;
    font-size: 0.88rem;
    width: 100%;
    background: #fff;
    color: #0f172a;
    transition: 0.2s;
    font-family: inherit;
}

.der-inline-form input:focus,
.der-inline-form select:focus,
.der-inline-form textarea:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
}

/* File links */
.der-file-link {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 14px;
    border-radius: 999px;
    border: 1px solid #e2e8f0;
    color: #0f172a;
    text-decoration: none;
    font-weight: 600;
    font-size: .82rem;
    background: #fff;
    transition: 0.2s;
}

.der-file-link:hover {
    background: #f1f5f9;
    border-color: #c7d2fe;
}

/* Action buttons inside cards */
.btn-action-sm {
    padding: 7px 16px;
    border-radius: 10px;
    font-weight: 700;
    font-size: .82rem;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: 0.25s;
    cursor: pointer;
    text-decoration: none;
}

.btn-action-sm:hover { transform: translateY(-2px); }

.btn-action-sm--primary   { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; }
.btn-action-sm--success   { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
.btn-action-sm--warning   { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
.btn-action-sm--danger    { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; }
.btn-action-sm--secondary { background: #94a3b8; color: #fff; }
.btn-action-sm--ghost     { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
.btn-action-sm--ghost:hover { background: #e2e8f0; color: #0f172a; }

/* Empty state */
.der-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #94a3b8;
}

.der-empty i {
    font-size: 3rem;
    display: block;
    margin-bottom: 1rem;
    opacity: 0.4;
}

/* Results header */
.der-results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f1f5f9;
}

.der-results-header h6 {
    font-weight: 800;
    color: #0f172a;
    font-size: 1rem;
    margin: 0;
}

.der-count-pill {
    padding: 4px 14px;
    border-radius: 999px;
    background: #f0fdf4;
    color: #059669;
    font-weight: 800;
    font-size: .78rem;
}
</style>

<div class="der-results-header">
    <h6>📋 Publications DER</h6>
    <span class="der-count-pill"><?= $totalItems ?> élément<?= $totalItems > 1 ? 's' : '' ?></span>
</div>

<?php if (!empty($derPosts)): ?>
    <?php foreach ($derPosts as $post): ?>
        <article
            class="der-post-card <?= $activeEditPostId === (int) ($post->id ?? 0) ? 'active-edit' : '' ?>"
            id="der-post-<?= (int) ($post->id ?? 0) ?>"
        >
            <div class="d-flex justify-content-between gap-3 flex-wrap mb-1 align-items-start">
                <h5><?= htmlspecialchars((string) ($post->titre ?? 'Publication')) ?></h5>
                <div class="d-flex gap-2 flex-wrap align-items-center">
                    <span class="der-type-badge"><?= htmlspecialchars((string) ($post->type ?? 'publication')) ?></span>
                    <?php if ((int) ($post->is_archived ?? 0) === 1): ?>
                        <span style="padding:4px 12px;border-radius:999px;background:#f1f5f9;color:#64748b;font-size:.73rem;font-weight:800;">🗄️ Archivée</span>
                    <?php else: ?>
                        <span style="padding:4px 12px;border-radius:999px;background:#f0fdf4;color:#059669;font-size:.73rem;font-weight:800;">✅ Active</span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($activeEditPostId === (int) ($post->id ?? 0)): ?>
                <div style="background:#eff6ff;border:1px solid #c7d2fe;border-radius:10px;padding:0.6rem 1rem;margin:0.5rem 0 0.75rem;font-size:.88rem;font-weight:700;color:#4338ca;">
                    ✏️ Cette publication est prête à être modifiée ci-dessous.
                </div>
            <?php endif; ?>

            <div class="der-post-meta">
                <span><i class="bx bx-calendar"></i><?= htmlspecialchars((string) ($post->publication_date ?? '')) ?></span>
                <span><i class="bx bx-user"></i><?= htmlspecialchars((string) ($post->author_name ?? 'Responsable DER')) ?></span>
                <span><i class="bx bx-time-five"></i>Ajouté le <?= htmlspecialchars(!empty($post->created_at) ? date('d/m/Y', strtotime((string) $post->created_at)) : '') ?></span>
            </div>

            <p class="mb-0" style="color:#475569;font-size:.9rem;line-height:1.7;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                <?= nl2br(htmlspecialchars((string) ($post->contenu ?? ''))) ?>
            </p>

            <!-- Files -->
            <?php if (!empty($post->files ?? [])): ?>
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <?php foreach (($post->files ?? []) as $file): ?>
                        <?php $relativePath = ltrim(str_replace('\\', '/', (string) ($file->file_path ?? '')), '/'); ?>
                        <a class="der-file-link" href="<?= ROOT . '/' . $relativePath ?>" target="_blank" rel="noopener">
                            <i class="bx bx-paperclip"></i>
                            <?= htmlspecialchars((string) ($file->original_name ?? 'Fichier')) ?>
                        </a>
                        <form method="POST" action="<?= htmlspecialchars($formAction) ?>" class="d-inline">
                            <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                            <input type="hidden" name="file_id" value="<?= (int) ($file->id ?? 0) ?>">
                            <button type="submit" name="delete_der_file" class="btn-action-sm btn-action-sm--danger" onclick="return confirm('Supprimer ce fichier joint ?');">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Inline Edit Form -->
            <div class="der-inline-form">
                <form method="POST" action="<?= htmlspecialchars($formAction) ?>" class="row gy-2 gx-2">
                    <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                    <input type="hidden" name="post_id" value="<?= (int) ($post->id ?? 0) ?>">
                    <div class="col-md-5">
                        <input type="text" name="titre" value="<?= htmlspecialchars((string) ($post->titre ?? '')) ?>" placeholder="Titre">
                    </div>
                    <div class="col-md-2">
                        <select name="type">
                            <?php foreach ($derAllowedTypes as $type): ?>
                                <option value="<?= htmlspecialchars($type) ?>" <?= (string) ($post->type ?? '') === $type ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($type)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_publication" value="<?= htmlspecialchars((string) ($post->publication_date ?? '')) ?>">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" name="update_der_post" class="btn-action-sm btn-action-sm--success w-100">
                            <i class="bx bx-save"></i> Mettre à jour
                        </button>
                    </div>
                    <div class="col-12">
                        <textarea name="contenu" rows="3" placeholder="Contenu"><?= htmlspecialchars((string) ($post->contenu ?? '')) ?></textarea>
                    </div>
                </form>
            </div>

            <!-- Action Buttons Row -->
            <div class="d-flex gap-2 flex-wrap mt-3 align-items-center">
                <a href="<?= ROOT ?>/Homes/der_publication_detail/<?= (int) ($post->id ?? 0) ?>?return=<?= urlencode(trim($detailReturnBasePath, '/') . ($paginationQuery !== '' ? '?' . $paginationQuery : '')) ?>" class="btn-action-sm btn-action-sm--ghost">
                    <i class="bx bx-show"></i> Voir détail
                </a>

                <?php if ((int) ($post->is_archived ?? 0) === 1): ?>
                    <form method="POST" action="<?= htmlspecialchars($formAction) ?>" class="d-inline">
                        <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                        <input type="hidden" name="post_id" value="<?= (int) ($post->id ?? 0) ?>">
                        <button type="submit" name="restore_der_post" class="btn-action-sm btn-action-sm--primary">
                            <i class="bx bx-refresh"></i> Restaurer
                        </button>
                    </form>
                    <?php if (str_contains($formAction, '/Homes/der_corbeille')): ?>
                        <form method="POST" action="<?= htmlspecialchars($formAction) ?>" class="d-inline" onsubmit="return confirm('Supprimer définitivement cette publication et ses fichiers ?');">
                            <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                            <input type="hidden" name="post_id" value="<?= (int) ($post->id ?? 0) ?>">
                            <button type="submit" name="purge_der_post" class="btn-action-sm btn-action-sm--danger">
                                <i class="bx bx-trash"></i> Supprimer définitivement
                            </button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <form method="POST" action="<?= htmlspecialchars($formAction) ?>" class="d-inline" onsubmit="return confirm('Archiver cette publication DER ?');">
                        <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                        <input type="hidden" name="post_id" value="<?= (int) ($post->id ?? 0) ?>">
                        <button type="submit" name="delete_der_post" class="btn-action-sm btn-action-sm--danger">
                            <i class="bx bx-archive"></i> Archiver
                        </button>
                    </form>

                    <form method="POST" action="<?= htmlspecialchars($formAction) ?>" enctype="multipart/form-data" class="d-inline-flex align-items-center gap-2 flex-grow-1">
                        <input type="hidden" name="return_query" value="<?= htmlspecialchars($paginationQuery) ?>">
                        <input type="hidden" name="post_id" value="<?= (int) ($post->id ?? 0) ?>">
                        <input type="file" name="fichiers[]" multiple style="font-size:.82rem;padding:6px 10px;border:1px dashed #c7d2fe;border-radius:10px;background:#f5f3ff;color:#5b21b6;cursor:pointer;max-width:240px;">
                        <button type="submit" name="attach_der_files" class="btn-action-sm btn-action-sm--primary">
                            <i class="bx bx-upload"></i> Joindre
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </article>
    <?php endforeach; ?>
<?php else: ?>
    <div class="der-empty">
        <i class="bx bx-file-blank"></i>
        Aucune publication DER ne correspond aux filtres.
    </div>
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
