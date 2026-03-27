<?php
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 10);
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalItems = max(0, (int) ($totalItems ?? 0));
$basePath = (string) ($basePath ?? '');
$queryString = trim((string) ($queryString ?? ''), '?');
$itemLabel = (string) ($itemLabel ?? 'element(s)');
$pageNumbers = [];
$start = max(1, $currentPage - 2);
$end = min($totalPages, $currentPage + 2);

if (($end - $start) < 4) {
    if ($start === 1) {
        $end = min($totalPages, $start + 4);
    } elseif ($end === $totalPages) {
        $start = max(1, $end - 4);
    }
}

for ($page = $start; $page <= $end; $page++) {
    $pageNumbers[] = $page;
}

$startItem = $totalItems > 0 ? (($currentPage - 1) * $perPage) + 1 : 0;
$endItem = $totalItems > 0 ? min($totalItems, $startItem + $perPage - 1) : 0;

if (!function_exists('admin_pagination_url')) {
    function admin_pagination_url(string $basePath, string $queryString, int $page): string
    {
        $separator = $queryString !== '' ? '&' : '';
        return ROOT . '/' . ltrim($basePath, '/') . '?' . $queryString . $separator . 'page=' . $page;
    }
}
?>
<?php if ($totalItems > 0): ?>
    <div class="admin-pagination-wrap mt-3">
        <div class="admin-pagination-summary">
            Elements <?= $startItem ?> a <?= $endItem ?> sur <?= $totalItems ?> <?= htmlspecialchars($itemLabel) ?>, page <?= $currentPage ?> sur <?= $totalPages ?>.
        </div>
        <nav class="admin-pagination" aria-label="Pagination">
            <a class="page-link-nav <?= $currentPage <= 1 ? 'is-disabled' : '' ?>" href="<?= $currentPage <= 1 ? '#' : admin_pagination_url($basePath, $queryString, 1) ?>">«</a>
            <a class="page-link-nav <?= $currentPage <= 1 ? 'is-disabled' : '' ?>" href="<?= $currentPage <= 1 ? '#' : admin_pagination_url($basePath, $queryString, max(1, $currentPage - 1)) ?>">‹</a>
            <?php foreach ($pageNumbers as $pageNumber): ?>
                <a
                    class="page-link-nav <?= $pageNumber === $currentPage ? 'is-active' : '' ?>"
                    href="<?= admin_pagination_url($basePath, $queryString, $pageNumber) ?>"
                    <?= $pageNumber === $currentPage ? 'aria-current="page"' : '' ?>
                >
                    <?= $pageNumber ?>
                </a>
            <?php endforeach; ?>
            <a class="page-link-nav <?= $currentPage >= $totalPages ? 'is-disabled' : '' ?>" href="<?= $currentPage >= $totalPages ? '#' : admin_pagination_url($basePath, $queryString, min($totalPages, $currentPage + 1)) ?>">›</a>
            <a class="page-link-nav <?= $currentPage >= $totalPages ? 'is-disabled' : '' ?>" href="<?= $currentPage >= $totalPages ? '#' : admin_pagination_url($basePath, $queryString, $totalPages) ?>">»</a>
        </nav>
    </div>
<?php endif; ?>
