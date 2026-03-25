<?php
$projects = $projects ?? [];
$projectSearch = $projectSearch ?? '';
$projectCount = $projectCount ?? count($projects);
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 5);
$totalPages = max(1, (int) ($totalPages ?? 1));
$startItem = $projectCount > 0 ? (($currentPage - 1) * $perPage) + 1 : 0;
$endItem = $projectCount > 0 ? $startItem + count($projects) - 1 : 0;

if (!function_exists('home_pagination_window')) {
    function home_pagination_window(int $currentPage, int $totalPages): array
    {
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);

        if (($end - $start) < 4) {
            if ($start === 1) {
                $end = min($totalPages, $start + 4);
            } elseif ($end === $totalPages) {
                $start = max(1, $end - 4);
            }
        }

        return range($start, $end);
    }
}
?>
<div class="results-chip">
    <i class='bx bx-filter-alt'></i>
    <?= (int) $projectCount ?> résultat(s) <?= $projectSearch !== '' ? 'pour "' . htmlspecialchars($projectSearch) . '"' : 'affiché(s)' ?>
</div>

<div class="row g-4">
    <?php if (!empty($projects)): ?>
        <?php foreach ($projects as $project): ?>
            <div class="col-xl-6">
                <article class="project-card-modern project-card-rich">
                    <div class="project-visual">
                        <div class="project-carousel js-project-carousel">
                            <?php foreach (($project['images'] ?? [$project['image']]) as $image): ?>
                                <div class="project-slide">
                                    <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($project['title']) ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <span class="project-category">
                            <i class='bx bx-category'></i>
                            <?= htmlspecialchars($project['category']) ?>
                        </span>
                        <span class="project-image-count">
                            <i class='bx bx-images'></i>
                            <?= count($project['images'] ?? []) ?>
                        </span>
                    </div>
                    <div class="project-body">
                        <div class="project-topline">
                            <div class="project-meta">
                                <span><i class='bx bx-user-circle'></i><?= htmlspecialchars($project['author']) ?></span>
                                <span><i class='bx bx-time-five'></i><?= htmlspecialchars($project['date']) ?></span>
                            </div>
                        </div>

                        <div class="project-stats">
                            <span class="project-stat project-stat--rating"><i class='bx bxs-star'></i><?= number_format((float) ($project['average_rating'] ?? 0), 1) ?>/5</span>
                            <span class="project-stat project-stat--likes"><i class='bx bxs-heart'></i><?= (int) ($project['likes_count'] ?? 0) ?> likes</span>
                            <span class="project-stat project-stat--reviews"><i class='bx bxs-message-square-detail'></i><?= (int) ($project['reviews_count'] ?? 0) ?> avis</span>
                        </div>

                        <h3 class="project-title">
                            <a href="<?= ROOT ?>/Projets/detail/<?= (int) $project['id'] ?>">
                                <?= htmlspecialchars($project['title']) ?>
                            </a>
                        </h3>

                        <p class="project-text"><?= htmlspecialchars($project['excerpt']) ?></p>

                        <?php if (!empty($project['technologies'])): ?>
                            <div class="tech-list">
                                <?php foreach (array_slice($project['technologies'], 0, 4) as $tech): ?>
                                    <span class="tech-pill"><?= htmlspecialchars($tech) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="project-actions">
                            <a class="project-link" href="<?= ROOT ?>/Projets/detail/<?= (int) $project['id'] ?>">
                                Découvrir le projet
                                <i class='bx bx-right-arrow-alt'></i>
                            </a>
                            <?php if (!empty($project['video'])): ?>
                                <span class="project-flag">
                                    <i class='bx bx-play-circle'></i>
                                    Démo vidéo
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="empty-projects">
                <i class='bx bx-search-alt'></i>
                <h3 class="mb-2">Aucun projet ne correspond à votre recherche</h3>
                <p class="text-muted mb-4">
                    Essayez un autre mot-clé, retirez le filtre de catégorie, ou publiez un nouveau projet pour enrichir la vitrine.
                </p>
                <a href="<?= ROOT ?>/Homes/index" class="hero-btn-outline me-2">Réinitialiser les filtres</a>
                <a href="<?= ROOT ?>/Projets/publier_projet" class="hero-btn">Publier un projet</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if ($projectCount > 0): ?>
    <div class="project-pagination-wrap">
        <div class="project-pagination-summary">
            Elements <?= $startItem ?> a <?= $endItem ?> sur <?= $projectCount ?>, page <?= $currentPage ?> sur <?= $totalPages ?>.
        </div>
        <nav class="project-pagination" aria-label="Pagination des projets">
            <button type="button" class="page-nav page-arrow" data-page="1" <?= $currentPage <= 1 ? 'disabled' : '' ?> title="Premiere page">
                <i class='bx bx-chevrons-left'></i>
            </button>
            <button type="button" class="page-nav page-arrow" data-page="<?= max(1, $currentPage - 1) ?>" <?= $currentPage <= 1 ? 'disabled' : '' ?>>
                <i class='bx bx-chevron-left'></i>
            </button>

            <?php foreach (home_pagination_window($currentPage, $totalPages) as $pageNumber): ?>
                <button
                    type="button"
                    class="page-nav <?= $pageNumber === $currentPage ? 'is-active' : '' ?>"
                    data-page="<?= $pageNumber ?>"
                    <?= $pageNumber === $currentPage ? 'aria-current="page"' : '' ?>
                >
                    <?= $pageNumber ?>
                </button>
            <?php endforeach; ?>

            <button type="button" class="page-nav page-arrow" data-page="<?= min($totalPages, $currentPage + 1) ?>" <?= $currentPage >= $totalPages ? 'disabled' : '' ?>>
                <i class='bx bx-chevron-right'></i>
            </button>
            <button type="button" class="page-nav page-arrow" data-page="<?= $totalPages ?>" <?= $currentPage >= $totalPages ? 'disabled' : '' ?> title="Derniere page">
                <i class='bx bx-chevrons-right'></i>
            </button>
        </nav>
    </div>
<?php endif; ?>
