<?php $nkOauth = defined('OAUTH_PROVIDERS') ? OAUTH_PROVIDERS : []; ?>
<?php if (!empty($nkOauth)): ?>
<div class="nk-social">
    <div class="nk-social__sep"><span>ou continuez avec</span></div>
    <div class="nk-social__btns">
        <?php foreach ($nkOauth as $nkKey => $nkP): ?>
            <a href="<?= ROOT ?>/Logins/oauth/<?= htmlspecialchars((string) $nkKey) ?>" class="nk-social__btn nk-social__btn--<?= htmlspecialchars((string) $nkKey) ?>" aria-label="Continuer avec <?= htmlspecialchars((string) ($nkP['label'] ?? $nkKey)) ?>">
                <i class='bx <?= htmlspecialchars((string) ($nkP['icon'] ?? 'bx-user')) ?>'></i>
                <span><?= htmlspecialchars((string) ($nkP['label'] ?? $nkKey)) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
