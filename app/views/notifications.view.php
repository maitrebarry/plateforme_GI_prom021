<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Mes notifications']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$notificationsList = $notificationsList ?? [];
$unreadCount = (int) ($unreadCount ?? 0);
$csrf = (string) ($_SESSION['csrf_token'] ?? '');

if (!function_exists('nk_notif_meta')) {
    function nk_notif_meta(string $type): array
    {
        switch ($type) {
            case 'project_validated': return ['bx-check-circle', 'ok'];
            case 'project_rejected':  return ['bx-x-circle', 'no'];
            case 'project_pending':   return ['bx-time-five', 'wait'];
            case 'new_like':          return ['bxs-heart', 'no'];
            case 'new_follow':        return ['bxs-star', 'brand'];
            case 'new_message':       return ['bx-message-rounded-dots', 'blue'];
            case 'contact':           return ['bx-envelope', 'blue'];
            default:                  return ['bx-info-circle', 'muted'];
        }
    }
}
if (!function_exists('nk_time_ago')) {
    function nk_time_ago($datetime): string
    {
        $time = strtotime((string) $datetime);
        if ($time === false) { return ''; }
        $diff = time() - $time;
        if ($diff < 60) { return "à l'instant"; }
        $units = [31536000 => 'an', 2592000 => 'mois', 604800 => 'sem', 86400 => 'j', 3600 => 'h', 60 => 'min'];
        foreach ($units as $sec => $label) {
            $d = (int) floor($diff / $sec);
            if ($d >= 1) {
                if ($label === 'an') { return "il y a {$d} an" . ($d > 1 ? 's' : ''); }
                return "il y a {$d} {$label}";
            }
        }
        return "à l'instant";
    }
}
?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-3 p-lg-4">
                <?php $this->view('set_flash'); ?>

                <style>
                    .ntf-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 20px; }
                    .ntf-head__l { display: flex; align-items: center; gap: 12px; }
                    .ntf-head h1 { display: flex; align-items: center; gap: 9px; font-family: var(--ds-font-heading); font-size: 1.4rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0; }
                    .ntf-head h1 i { color: var(--ds-brand-600); }
                    .ntf-count { display: inline-flex; align-items: center; justify-content: center; min-width: 24px; height: 24px; padding: 0 8px; border-radius: var(--ds-radius-pill); background: var(--ds-danger); color: #fff; font-size: .76rem; font-weight: 800; }
                    .ntf-allread { display: inline-flex; align-items: center; gap: 7px; background: var(--ds-surface-2); border: 1px solid var(--ds-border); color: var(--ds-ink); font-weight: 700; font-size: .84rem; padding: 9px 16px; border-radius: var(--ds-radius-pill); cursor: pointer; transition: all var(--ds-transition); }
                    .ntf-allread:hover { background: var(--ds-brand-50); color: var(--ds-brand-700); border-color: var(--ds-brand-300); }

                    .ntf-list { display: flex; flex-direction: column; gap: 10px; }
                    .ntf-item { display: flex; align-items: flex-start; gap: 14px; background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); box-shadow: var(--ds-shadow-sm); padding: 16px 18px; text-decoration: none; position: relative; transition: all var(--ds-transition); }
                    .ntf-item:hover { border-color: var(--ds-brand-300); transform: translateY(-1px); box-shadow: var(--ds-shadow); }
                    .ntf-item.is-unread { background: var(--ds-brand-50); border-color: var(--ds-brand-200); }
                    .ntf-item__ic { width: 44px; height: 44px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0; }
                    .ntf-ic--ok { background: #e4f3ea; color: #11703a; }
                    .ntf-ic--no { background: var(--ds-danger-soft); color: var(--ds-danger); }
                    .ntf-ic--wait { background: var(--ds-accent-soft); color: #8a6310; }
                    .ntf-ic--brand { background: var(--ds-brand-100); color: var(--ds-brand-700); }
                    .ntf-ic--blue { background: #e3effb; color: #1d59b8; }
                    .ntf-ic--muted { background: var(--ds-surface-2); color: var(--ds-muted); }
                    .ntf-item__body { flex: 1; min-width: 0; }
                    .ntf-item__title { font-weight: 800; color: var(--ds-ink-strong); font-size: .95rem; margin: 0 0 3px; }
                    .ntf-item__msg { color: var(--ds-muted); font-size: .87rem; line-height: 1.5; margin: 0; }
                    .ntf-item__time { color: var(--ds-muted); font-size: .76rem; font-weight: 600; white-space: nowrap; margin-top: 4px; display: inline-flex; align-items: center; gap: 4px; }
                    .ntf-dot { width: 9px; height: 9px; border-radius: 50%; background: var(--ds-brand-600); flex-shrink: 0; margin-top: 6px; }
                    .ntf-empty { text-align: center; padding: 60px 20px; color: var(--ds-muted); }
                    .ntf-empty i { font-size: 3.4rem; color: var(--ds-border-strong); display: block; margin-bottom: 12px; }
                    .ntf-empty p { font-weight: 600; margin: 0; }
                </style>

                <div class="ntf-head">
                    <div class="ntf-head__l">
                        <h1><i class='bx bx-bell'></i> Mes notifications</h1>
                        <?php if ($unreadCount > 0): ?><span class="ntf-count"><?= $unreadCount ?> non lue<?= $unreadCount > 1 ? 's' : '' ?></span><?php endif; ?>
                    </div>
                    <?php if (!empty($notificationsList) && $unreadCount > 0): ?>
                        <form method="POST" action="<?= ROOT ?>/Notifications/index">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                            <button type="submit" name="mark_all_read" value="1" class="ntf-allread"><i class='bx bx-check-double'></i> Tout marquer comme lu</button>
                        </form>
                    <?php endif; ?>
                </div>

                <?php if (!empty($notificationsList)): ?>
                    <div class="ntf-list">
                        <?php foreach ($notificationsList as $n): ?>
                            <?php
                            $meta = nk_notif_meta((string) ($n->type ?? 'info'));
                            $isUnread = (int) ($n->is_read ?? 0) === 0;
                            $hasLink = !empty($n->link);
                            $tag = $hasLink ? 'a' : 'div';
                            $href = $hasLink ? ' href="' . ROOT . '/Notifications/read/' . (int) ($n->id ?? 0) . '"' : '';
                            ?>
                            <<?= $tag ?><?= $href ?> class="ntf-item<?= $isUnread ? ' is-unread' : '' ?>">
                                <span class="ntf-item__ic ntf-ic--<?= $meta[1] ?>"><i class='bx <?= $meta[0] ?>'></i></span>
                                <div class="ntf-item__body">
                                    <p class="ntf-item__title"><?= htmlspecialchars((string) ($n->title ?? '')) ?></p>
                                    <?php if (!empty($n->message)): ?><p class="ntf-item__msg"><?= htmlspecialchars((string) $n->message) ?></p><?php endif; ?>
                                    <span class="ntf-item__time"><i class='bx bx-time'></i> <?= htmlspecialchars(nk_time_ago($n->created_at ?? '')) ?></span>
                                </div>
                                <?php if ($isUnread): ?><span class="ntf-dot" title="Non lue"></span><?php endif; ?>
                            </<?= $tag ?>>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="ntf-empty">
                        <i class='bx bx-bell-off'></i>
                        <p>Aucune notification pour le moment.</p>
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
