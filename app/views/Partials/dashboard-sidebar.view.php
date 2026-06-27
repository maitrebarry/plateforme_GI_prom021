<?php
$role = strtolower((string) ($_SESSION['role'] ?? 'etudiant'));
$cur = strtolower(trim((string) ($_GET['url'] ?? ''), '/'));
$unread = (int) ($studentUnreadMessages ?? ($_SESSION['student_unread_messages'] ?? 0));
$sideName = trim((string) (($_SESSION['prenom'] ?? '') . ' ' . ($_SESSION['nom'] ?? '')));
if ($sideName === '') { $sideName = 'Utilisateur'; }
$sideInitial = strtoupper(mb_substr($sideName, 0, 1));
$sideImage = basename((string) ($_SESSION['image'] ?? ''));
$hasSideImg = $sideImage !== '' && $sideImage !== 'default.png';
$roleLabel = $role === 'admin' ? 'Administrateur' : ($role === 'der' ? 'Responsable DER' : 'Étudiant');

$dsActive = static function (array $keys) use ($cur): string {
    foreach ($keys as $k) {
        if ($k !== '' && str_contains($cur, $k)) { return 'is-active'; }
    }
    return '';
};

if ($role === 'admin') {
    $navMain = [
        ['Tableau de bord', 'Admins/dashboard', 'bx-grid-alt', ['admins/dashboard', 'admins/index']],
        ['Gestion des projets', 'Admins/projects_management', 'bx-folder-open', ['projects_management', 'project_detail', 'most_followed']],
        ['Utilisateurs', 'Admins/users_management', 'bx-group', ['users_management', 'utilisateurs']],
        ['Catégories', 'Admins/categories', 'bx-category', ['categories']],
        ['Messages / Contact', 'Admins/messages', 'bx-envelope', ['admins/messages', 'message_detail']],
        ['Statistiques', 'Admins/statistics', 'bx-bar-chart-alt-2', ['statistics']],
    ];
} elseif ($role === 'der') {
    $navMain = [
        ['Tableau de bord', 'Homes/der_dashboard', 'bx-grid-alt', ['der_dashboard']],
        ['Publications DER', 'Homes/der_espace', 'bx-news', ['der_espace', 'der_annonces', 'der_publication']],
        ['Corbeille', 'Homes/der_corbeille', 'bx-trash', ['der_corbeille', 'der_trash']],
    ];
} else {
    $navMain = [
        ['Tableau de bord', 'Homes/dashboard', 'bx-grid-alt', ['dashboard', 'student_dashboard']],
        ['Mes projets', 'Projets/mes_projets', 'bx-folder', ['mes_projets', 'publier_projet', 'projets/modifier']],
        ['Messages reçus', 'Homes/messages_recus', 'bx-envelope', ['messages_recus'], $unread],
    ];
}
$navAccount = [
    ['Profil', 'Profiles/appercu', 'bx-user', ['profiles', 'appercu', 'profile']],
    ['Retour au site', 'Homes/index', 'bx-globe', []],
];
?>
<div class="dashboard-sidebar">
    <button type="button" class="dashboard-sidebar__close d-lg-none d-flex"><i class="bx bx-x"></i></button>
    <div class="dashboard-sidebar__inner">
        <style>
            .dashboard-sidebar { background: var(--ds-surface) !important; border-right: 1px solid var(--ds-border) !important; }
            .dashboard-sidebar__inner { padding: 14px 14px 24px; }
            .dashboard-sidebar__close { background: var(--ds-surface-2); border: 1px solid var(--ds-border); border-radius: 10px; color: var(--ds-ink); width: 38px; height: 38px; align-items: center; justify-content: center; }
            .ds-side__brand { display: flex; align-items: center; gap: 10px; padding: 6px 8px 14px; }
            .ds-side__brand img { width: 36px; height: 36px; object-fit: contain; border-radius: 9px; }
            .ds-side__brand b { font-family: var(--ds-font-heading); font-weight: 800; color: var(--ds-ink-strong); font-size: 1.08rem; letter-spacing: -.01em; }
            .ds-side__user { display: flex; align-items: center; gap: 11px; padding: 11px; border-radius: var(--ds-radius); background: var(--ds-surface-2); border: 1px solid var(--ds-border); margin-bottom: 8px; }
            .ds-side__ava { width: 40px; height: 40px; border-radius: 50%; overflow: hidden; flex-shrink: 0; background: linear-gradient(135deg, var(--ds-brand-500), var(--ds-brand-700)); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1rem; }
            .ds-side__ava img { width: 100%; height: 100%; object-fit: cover; }
            .ds-side__name { font-weight: 800; color: var(--ds-ink-strong); font-size: .9rem; line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 130px; }
            .ds-side__role { display: inline-block; margin-top: 3px; font-size: .68rem; font-weight: 800; color: var(--ds-brand-700); background: var(--ds-brand-50); padding: 2px 9px; border-radius: var(--ds-radius-pill); }
            .ds-side__group { font-size: .66rem; font-weight: 800; text-transform: uppercase; letter-spacing: .09em; color: var(--ds-muted-soft); padding: 16px 10px 7px; }
            .ds-side__link { display: flex; align-items: center; gap: 11px; padding: 10px 11px; border-radius: var(--ds-radius); color: var(--ds-ink); text-decoration: none; font-weight: 600; font-size: .9rem; position: relative; transition: all var(--ds-transition); margin-bottom: 2px; }
            .ds-side__link i { font-size: 1.25rem; color: var(--ds-muted); transition: color var(--ds-transition); flex-shrink: 0; }
            .ds-side__link:hover { background: var(--ds-surface-2); color: var(--ds-ink-strong); }
            .ds-side__link:hover i { color: var(--ds-brand-600); }
            .ds-side__link.is-active { background: var(--ds-brand-50); color: var(--ds-brand-700); }
            .ds-side__link.is-active i { color: var(--ds-brand-600); }
            .ds-side__link.is-active::before { content: ''; position: absolute; left: -14px; top: 9px; bottom: 9px; width: 3px; border-radius: 0 3px 3px 0; background: var(--ds-brand-600); }
            .ds-side__badge { margin-left: auto; min-width: 20px; height: 20px; padding: 0 6px; border-radius: var(--ds-radius-pill); background: var(--ds-danger); color: #fff; font-size: .7rem; font-weight: 800; display: inline-flex; align-items: center; justify-content: center; }
            .ds-side__link--out { color: var(--ds-danger); }
            .ds-side__link--out i { color: var(--ds-danger); }
            .ds-side__link--out:hover { background: var(--ds-danger-soft); color: var(--ds-danger); }
            .ds-side__sep { height: 1px; background: var(--ds-border); margin: 8px 4px; }
        </style>

        <div class="ds-side__brand">
            <img src="<?= ROOT ?>/assets/images/logo/n'kakodon.png" alt="NGAKODON" onerror="this.style.display='none'">
            <b>NGAKODON</b>
        </div>

        <div class="ds-side__user">
            <span class="ds-side__ava">
                <?php if ($hasSideImg): ?><img src="<?= ROOT ?>/image_profile/<?= htmlspecialchars($sideImage) ?>" alt=""><?php else: ?><?= htmlspecialchars($sideInitial) ?><?php endif; ?>
            </span>
            <div>
                <div class="ds-side__name"><?= htmlspecialchars($sideName) ?></div>
                <span class="ds-side__role"><?= htmlspecialchars($roleLabel) ?></span>
            </div>
        </div>

        <ul class="sidebar-list" style="list-style:none;padding:0;margin:0;">
            <li class="ds-side__group">Principal</li>
            <?php foreach ($navMain as $item): ?>
                <li>
                    <a href="<?= ROOT ?>/<?= $item[1] ?>" class="ds-side__link <?= $dsActive($item[3]) ?>">
                        <i class='bx <?= $item[2] ?>'></i>
                        <span><?= htmlspecialchars($item[0]) ?></span>
                        <?php if (!empty($item[4]) && (int) $item[4] > 0): ?><span class="ds-side__badge"><?= (int) $item[4] > 99 ? '99+' : (int) $item[4] ?></span><?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>

            <li class="ds-side__group">Compte</li>
            <?php foreach ($navAccount as $item): ?>
                <li>
                    <a href="<?= ROOT ?>/<?= $item[1] ?>" class="ds-side__link <?= $dsActive($item[3]) ?>">
                        <i class='bx <?= $item[2] ?>'></i>
                        <span><?= htmlspecialchars($item[0]) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
            <li class="ds-side__sep"></li>
            <li>
                <a href="<?= ROOT ?>/Logins/logout" data-logout class="ds-side__link ds-side__link--out">
                    <i class='bx bx-log-out'></i>
                    <span>Déconnexion</span>
                </a>
            </li>
        </ul>
    </div>
</div>
