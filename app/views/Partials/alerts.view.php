<?php if (!empty($flashMessages)): ?>
    <div class="container container-two pt-4" data-dynamic-block="flash-messages">
        <?php foreach ($flashMessages as $flash): ?>
            <?php $flashType = htmlspecialchars($flash['type'] ?? 'info'); ?>
            <div class="alert alert-<?= $flashType ?> mb-2" role="alert">
                <?= htmlspecialchars($flash['message'] ?? '') ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
