<?php
$role = strtolower((string) ($_SESSION['role'] ?? 'etudiant'));
$navTitle = $role === 'admin' ? 'Administration' : ($role === 'der' ? 'Espace DER' : 'Espace étudiant');
$navName = trim((string) (($_SESSION['prenom'] ?? '') . ' ' . ($_SESSION['nom'] ?? '')));
if ($navName === '') { $navName = 'Utilisateur'; }
$navInitial = strtoupper(mb_substr($navName, 0, 1));
$navImage = basename((string) ($_SESSION['image'] ?? ''));
$hasNavImg = $navImage !== '' && $navImage !== 'default.png';

$navUserId = (int) ($_SESSION['user_id'] ?? 0);
$navNotifs = [];
$navUnread = 0;
if ($navUserId > 0 && class_exists('Notification')) {
    $navNotifModel = new Notification();
    $navNotifs = $navNotifModel->getRecent($navUserId, 6);
    $navUnread = $navNotifModel->countUnread($navUserId);
}
$navNotifIcon = static function (string $type): array {
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
};
?>
<div class="dashboard-nav">
    <style>
        .dashboard-nav { background: var(--ds-surface) !important; border-bottom: 1px solid var(--ds-border) !important; padding: 11px 18px !important; display: flex; align-items: center; justify-content: space-between; gap: 14px; }
        .dsnav__left { display: flex; align-items: center; gap: 12px; min-width: 0; }
        .dsnav__burger { width: 40px; height: 40px; border-radius: 11px; background: var(--ds-surface-2); border: 1px solid var(--ds-border); display: inline-flex; align-items: center; justify-content: center; color: var(--ds-ink); font-size: 1.35rem; cursor: pointer; flex-shrink: 0; transition: all var(--ds-transition); }
        .dsnav__burger:hover { background: var(--ds-brand-50); color: var(--ds-brand-700); }
        .dsnav__title { font-family: var(--ds-font-heading); font-weight: 800; font-size: 1.1rem; color: var(--ds-ink-strong); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .dsnav__right { display: flex; align-items: center; gap: 8px; }
        .dashboard-nav .ds-color-picker { margin: 0 !important; }
        .dashboard-nav .ds-theme-toggle { width: 40px; height: 40px; border-radius: 11px; background: var(--ds-surface-2); border: 1px solid var(--ds-border); color: var(--ds-ink); }
        .dashboard-nav .ds-theme-toggle:hover { background: var(--ds-brand-50); color: var(--ds-brand-700); }
        .dsnav__profile { position: relative; }
        .dsnav__pbtn { display: flex; align-items: center; gap: 9px; background: var(--ds-surface-2); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-pill); padding: 5px 6px 5px 12px; cursor: pointer; transition: all var(--ds-transition); }
        .dsnav__pbtn:hover { border-color: var(--ds-brand-300); }
        .dsnav__pname { font-weight: 700; font-size: .85rem; color: var(--ds-ink-strong); max-width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .dsnav__pava { width: 32px; height: 32px; border-radius: 50%; overflow: hidden; background: linear-gradient(135deg, var(--ds-brand-500), var(--ds-brand-700)); color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: .82rem; flex-shrink: 0; }
        .dsnav__pava img { width: 100%; height: 100%; object-fit: cover; }
        .dsnav__menu { position: absolute; top: calc(100% + 8px); right: 0; min-width: 200px; background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius); box-shadow: var(--ds-shadow-lg, 0 20px 40px -16px rgba(0,0,0,.2)); padding: 7px; opacity: 0; visibility: hidden; transform: translateY(-6px); transition: all .18s ease; z-index: 60; }
        .dsnav__profile.is-open .dsnav__menu { opacity: 1; visibility: visible; transform: none; }
        .dsnav__mhead { padding: 8px 11px; border-bottom: 1px solid var(--ds-border); margin-bottom: 5px; }
        .dsnav__mhead b { display: block; color: var(--ds-ink-strong); font-size: .88rem; }
        .dsnav__mhead span { color: var(--ds-muted); font-size: .76rem; }
        .dsnav__mlink { display: flex; align-items: center; gap: 9px; padding: 9px 11px; border-radius: 10px; color: var(--ds-ink); text-decoration: none; font-weight: 600; font-size: .87rem; }
        .dsnav__mlink i { font-size: 1.15rem; color: var(--ds-muted); }
        .dsnav__mlink:hover { background: var(--ds-surface-2); }
        .dsnav__mlink--out { color: var(--ds-danger); } .dsnav__mlink--out i { color: var(--ds-danger); }

        .dsnav__notif { position: relative; }
        .dsnav__bell { position: relative; width: 40px; height: 40px; border-radius: 11px; background: var(--ds-surface-2); border: 1px solid var(--ds-border); color: var(--ds-ink); font-size: 1.3rem; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all var(--ds-transition); }
        .dsnav__bell:hover { background: var(--ds-brand-50); color: var(--ds-brand-700); }
        .dsnav__bellbadge { position: absolute; top: -4px; right: -4px; min-width: 18px; height: 18px; padding: 0 4px; border-radius: 9px; background: var(--ds-danger); color: #fff; font-size: .64rem; font-weight: 800; display: inline-flex; align-items: center; justify-content: center; border: 2px solid var(--ds-surface); }
        .dsnav__notifmenu { position: absolute; top: calc(100% + 8px); right: 0; width: 350px; max-width: calc(100vw - 32px); background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius); box-shadow: var(--ds-shadow-lg, 0 20px 40px -16px rgba(0,0,0,.2)); opacity: 0; visibility: hidden; transform: translateY(-6px); transition: all .18s ease; z-index: 60; overflow: hidden; }
        .dsnav__notif.is-open .dsnav__notifmenu { opacity: 1; visibility: visible; transform: none; }
        .dsnav__notifhead { display: flex; align-items: center; justify-content: space-between; padding: 13px 16px; border-bottom: 1px solid var(--ds-border); }
        .dsnav__notifhead b { color: var(--ds-ink-strong); font-size: .92rem; }
        .dsnav__notifhead span { color: var(--ds-danger); font-size: .74rem; font-weight: 700; }
        .dsnav__notiflist { max-height: 358px; overflow-y: auto; }
        .dsnav__nitem { display: flex; align-items: flex-start; gap: 11px; padding: 12px 16px; text-decoration: none; border-bottom: 1px solid var(--ds-border); transition: background var(--ds-transition); }
        .dsnav__nitem:hover { background: var(--ds-surface-2); }
        .dsnav__nitem.is-unread { background: var(--ds-brand-50); }
        .dsnav__nitem:last-child { border-bottom: 0; }
        .dsnav__nic { width: 34px; height: 34px; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; background: var(--ds-surface-2); color: var(--ds-muted); }
        .dsnav__nic--ok { background: #e4f3ea; color: #11703a; } .dsnav__nic--no { background: var(--ds-danger-soft); color: var(--ds-danger); } .dsnav__nic--wait { background: var(--ds-accent-soft); color: #8a6310; } .dsnav__nic--brand { background: var(--ds-brand-100); color: var(--ds-brand-700); } .dsnav__nic--blue { background: #e3effb; color: #1d59b8; }
        .dsnav__ntext { flex: 1; min-width: 0; }
        .dsnav__ntext b { display: block; color: var(--ds-ink-strong); font-size: .83rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .dsnav__ntext small { color: var(--ds-muted); font-size: .76rem; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .dsnav__notifempty { padding: 32px 16px; text-align: center; color: var(--ds-muted); font-size: .85rem; }
        .dsnav__notifempty i { font-size: 2rem; display: block; margin-bottom: 6px; color: var(--ds-border-strong); }
        .dsnav__notifall { display: block; text-align: center; padding: 12px; color: var(--ds-brand-700); font-weight: 700; font-size: .85rem; text-decoration: none; border-top: 1px solid var(--ds-border); background: var(--ds-surface-2); }
        .dsnav__notifall:hover { background: var(--ds-brand-50); }
        @media (max-width: 575px) { .dsnav__pname { display: none; } .dsnav__title { font-size: 1rem; } .dsnav__notifmenu { position: fixed; left: 16px; right: 16px; top: 64px; width: auto; } }
    </style>

    <div class="dsnav__left">
        <button type="button" class="dsnav__burger bar-icon" aria-label="Menu"><i class='bx bx-menu'></i></button>
        <h6 class="dsnav__title"><?= htmlspecialchars($navTitle) ?></h6>
    </div>

    <div class="dsnav__right">
        <div class="ds-color-picker">
            <button type="button" class="ds-theme-toggle" data-color-toggle title="Couleur du thème" aria-label="Choisir la couleur du thème"><i class="bx bx-palette"></i></button>
            <div class="ds-color-menu" data-color-menu>
                <button type="button" class="ds-color-swatch" data-color-value="green" style="--sw:#157f5a" title="Vert" aria-label="Vert"></button>
                <button type="button" class="ds-color-swatch" data-color-value="blue" style="--sw:#1d59b8" title="Bleu" aria-label="Bleu"></button>
                <button type="button" class="ds-color-swatch" data-color-value="orange" style="--sw:#cf5410" title="Orange" aria-label="Orange"></button>
                <button type="button" class="ds-color-swatch" data-color-value="violet" style="--sw:#6236c4" title="Violet" aria-label="Violet"></button>
            </div>
        </div>
        <button type="button" class="ds-theme-toggle" data-theme-toggle title="Basculer mode clair / sombre" aria-label="Basculer entre mode clair et sombre"><i class="bx bx-moon"></i></button>

        <?php if ($navUserId > 0): ?>
        <div class="dsnav__notif" id="dsnavNotif">
            <button type="button" class="dsnav__bell" id="dsnavBellBtn" title="Notifications" aria-label="Notifications">
                <i class='bx bx-bell'></i>
                <?php if ($navUnread > 0): ?><span class="dsnav__bellbadge"><?= $navUnread > 9 ? '9+' : $navUnread ?></span><?php endif; ?>
            </button>
            <div class="dsnav__notifmenu">
                <div class="dsnav__notifhead">
                    <b>Notifications</b>
                    <?php if ($navUnread > 0): ?><span><?= $navUnread ?> non lue<?= $navUnread > 1 ? 's' : '' ?></span><?php endif; ?>
                </div>
                <div class="dsnav__notiflist">
                    <?php if (!empty($navNotifs)): ?>
                        <?php foreach ($navNotifs as $nn): ?>
                            <?php $ic = $navNotifIcon((string) ($nn->type ?? 'info')); $nUnread = (int) ($nn->is_read ?? 0) === 0; ?>
                            <a href="<?= ROOT ?>/Notifications/read/<?= (int) ($nn->id ?? 0) ?>" class="dsnav__nitem<?= $nUnread ? ' is-unread' : '' ?>">
                                <span class="dsnav__nic dsnav__nic--<?= $ic[1] ?>"><i class='bx <?= $ic[0] ?>'></i></span>
                                <span class="dsnav__ntext"><b><?= htmlspecialchars((string) ($nn->title ?? '')) ?></b><?php if (!empty($nn->message)): ?><small><?= htmlspecialchars((string) $nn->message) ?></small><?php endif; ?></span>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="dsnav__notifempty"><i class='bx bx-bell-off'></i>Aucune notification</div>
                    <?php endif; ?>
                </div>
                <a href="<?= ROOT ?>/Notifications/index" class="dsnav__notifall">Voir toutes les notifications</a>
            </div>
        </div>
        <?php endif; ?>

        <div class="dsnav__profile" id="dsnavProfile">
            <button type="button" class="dsnav__pbtn" id="dsnavProfileBtn">
                <span class="dsnav__pname"><?= htmlspecialchars($navName) ?></span>
                <span class="dsnav__pava"><?php if ($hasNavImg): ?><img src="<?= ROOT ?>/image_profile/<?= htmlspecialchars($navImage) ?>" alt=""><?php else: ?><?= htmlspecialchars($navInitial) ?><?php endif; ?></span>
            </button>
            <div class="dsnav__menu">
                <div class="dsnav__mhead"><b><?= htmlspecialchars($navName) ?></b><span><?= htmlspecialchars($role === 'admin' ? 'Administrateur' : ($role === 'der' ? 'Responsable DER' : 'Étudiant')) ?></span></div>
                <a href="<?= ROOT ?>/Profiles/appercu" class="dsnav__mlink"><i class='bx bx-user'></i> Mon profil</a>
                <a href="<?= ROOT ?>/Homes/index" class="dsnav__mlink"><i class='bx bx-globe'></i> Retour au site</a>
                <a href="<?= ROOT ?>/Logins/logout" data-logout class="dsnav__mlink dsnav__mlink--out"><i class='bx bx-log-out'></i> Déconnexion</a>
            </div>
        </div>
    </div>
</div>
<script>
    (function () {
        var p = document.getElementById('dsnavProfile');
        var b = document.getElementById('dsnavProfileBtn');
        var n = document.getElementById('dsnavNotif');
        var nb = document.getElementById('dsnavBellBtn');
        if (p && b) {
            b.addEventListener('click', function (e) { e.stopPropagation(); p.classList.toggle('is-open'); if (n) { n.classList.remove('is-open'); } });
        }
        if (n && nb) {
            nb.addEventListener('click', function (e) { e.stopPropagation(); n.classList.toggle('is-open'); if (p) { p.classList.remove('is-open'); } });
        }
        document.addEventListener('click', function (e) {
            if (p && !p.contains(e.target)) { p.classList.remove('is-open'); }
            if (n && !n.contains(e.target)) { n.classList.remove('is-open'); }
        });
    })();
</script>
