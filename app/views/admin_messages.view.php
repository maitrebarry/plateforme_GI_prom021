<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Messages / Contact']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$messages = $messages ?? [];
$currentPage = max(1, (int) ($currentPage ?? 1));
$perPage = (int) ($perPage ?? 10);
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalItems = max(0, (int) ($totalItems ?? count($messages ?? [])));
$paginationQuery = (string) ($paginationQuery ?? '');
?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-3 p-lg-4">
                <?php $this->view('set_flash'); ?>

                <style>
                    .adm-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 18px; }
                    .adm-head__l { display: flex; align-items: center; gap: 12px; }
                    .adm-back { width: 42px; height: 42px; border-radius: 12px; background: var(--ds-surface); border: 1px solid var(--ds-border); display: inline-flex; align-items: center; justify-content: center; color: var(--ds-ink); text-decoration: none; font-size: 1.2rem; transition: all var(--ds-transition); }
                    .adm-back:hover { background: var(--ds-brand-50); color: var(--ds-brand-700); }
                    .adm-head h1 { display: flex; align-items: center; gap: 8px; font-family: var(--ds-font-heading); font-size: 1.35rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0; }
                    .adm-head h1 i { color: var(--ds-brand-600); }
                    .adm-pp { display: flex; gap: 8px; }
                    .adm-pp select { border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius-pill); padding: 8px 14px; font-size: .85rem; color: var(--ds-ink); background: var(--ds-surface); }
                    .adm-pp button { background: var(--ds-brand-600); color: #fff; font-weight: 700; font-size: .85rem; border: 0; border-radius: var(--ds-radius-pill); padding: 8px 16px; cursor: pointer; }

                    .adm-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); box-shadow: var(--ds-shadow-sm); padding: 20px; }
                    .adm-card__title { display: flex; align-items: center; gap: 8px; font-family: var(--ds-font-heading); font-size: 1.08rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0 0 14px; }
                    .adm-card__title i { color: var(--ds-brand-600); }
                    .adm-table-wrap { overflow-x: auto; }
                    .adm-table { width: 100%; border-collapse: collapse; min-width: 700px; }
                    .adm-table thead th { text-align: left; font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .04em; color: var(--ds-muted); padding: 11px 12px; border-bottom: 2px solid var(--ds-border); white-space: nowrap; }
                    .adm-table tbody td { padding: 12px; border-bottom: 1px solid var(--ds-border); color: var(--ds-ink); font-size: .88rem; vertical-align: middle; }
                    .adm-table tbody tr:hover { background: var(--ds-surface-2); }
                    .adm-author { display: inline-flex; align-items: center; gap: 9px; font-weight: 700; color: var(--ds-ink-strong); }
                    .adm-author__ava { width: 32px; height: 32px; border-radius: 50%; background: var(--ds-brand-100); color: var(--ds-brand-700); display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: .78rem; }
                    .adm-snippet { display: block; max-width: 280px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--ds-muted); }
                    .adm-pill { display: inline-flex; align-items: center; height: 22px; padding: 0 11px; border-radius: var(--ds-radius-pill); font-size: .72rem; font-weight: 700; background: var(--ds-surface-2); color: var(--ds-muted); }
                    .adm-reply { display: inline-flex; align-items: center; gap: 5px; background: var(--ds-brand-50); color: var(--ds-brand-700); font-weight: 700; font-size: .8rem; padding: 7px 14px; border-radius: var(--ds-radius-pill); text-decoration: none; transition: all var(--ds-transition); }
                    .adm-reply:hover { background: var(--ds-brand-600); color: #fff; }
                    .adm-empty { text-align: center; color: var(--ds-muted); padding: 28px; }

                    .admin-pagination-wrap { display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap; margin-top: 20px; }
                    .admin-pagination-summary { color: var(--ds-muted); font-size: .85rem; font-weight: 600; }
                    .admin-pagination { display: flex; gap: 6px; flex-wrap: wrap; }
                    .page-link-nav { min-width: 40px; height: 40px; padding: 0 12px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--ds-radius); border: 1px solid var(--ds-border); background: var(--ds-surface); color: var(--ds-ink); text-decoration: none; font-weight: 700; font-size: .9rem; transition: all var(--ds-transition); }
                    .page-link-nav:hover:not(.is-disabled) { border-color: var(--ds-brand-300); color: var(--ds-brand-700); }
                    .page-link-nav.is-active { background: var(--ds-brand-600); border-color: var(--ds-brand-600); color: #fff; }
                    .page-link-nav.is-disabled { pointer-events: none; opacity: .4; }
                </style>

                <div class="adm-head">
                    <div class="adm-head__l">
                        <a href="<?= ROOT ?>/Admins/dashboard" class="adm-back"><i class='bx bx-left-arrow-alt'></i></a>
                        <h1><i class='bx bx-envelope'></i> Messages &amp; contacts</h1>
                    </div>
                    <form method="GET" action="<?= ROOT ?>/Admins/messages" class="adm-pp">
                        <select name="per_page"><?php foreach ([10, 20, 50] as $pp): ?><option value="<?= $pp ?>" <?= $perPage === $pp ? 'selected' : '' ?>><?= $pp ?> / page</option><?php endforeach; ?></select>
                        <button type="submit">OK</button>
                    </form>
                </div>

                <div class="adm-card">
                    <h2 class="adm-card__title"><i class='bx bx-message-rounded-dots'></i> Boîte de réception</h2>
                    <div class="adm-table-wrap">
                        <table class="adm-table">
                            <thead><tr><th>Nom</th><th>Email</th><th>Projet</th><th>Message</th><th>Date</th><th></th></tr></thead>
                            <tbody>
                                <?php if (!empty($messages)): ?>
                                    <?php foreach ($messages as $msg): ?>
                                        <?php $mNom = (string) ($msg->nom ?? 'Inconnu'); ?>
                                        <tr>
                                            <td class="is-cardtitle"><span class="adm-author"><span class="adm-author__ava"><?= htmlspecialchars(strtoupper(mb_substr($mNom, 0, 1))) ?></span><?= htmlspecialchars($mNom) ?></span></td>
                                            <td class="text-muted" data-label="Email"><?= htmlspecialchars((string) ($msg->email ?? '')) ?></td>
                                            <td data-label="Projet"><span class="adm-pill"><?= htmlspecialchars((string) ($msg->projet ?? 'Général')) ?></span></td>
                                            <td data-label="Message"><span class="adm-snippet"><?= htmlspecialchars((string) ($msg->message ?? '')) ?></span></td>
                                            <td class="text-muted" data-label="Date"><?= htmlspecialchars(date('d/m/Y', strtotime((string) ($msg->created_at ?? 'now')))) ?></td>
                                            <td class="is-cardaction"><a href="<?= ROOT ?>/Admins/message_detail/<?= (int) ($msg->id ?? 0) ?>" class="adm-reply"><i class='bx bx-reply'></i> Répondre</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="adm-empty">Aucun message reçu.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php $this->view('Partials/admin-pagination', [
                        'currentPage' => $currentPage, 'perPage' => $perPage, 'totalPages' => $totalPages,
                        'totalItems' => $totalItems, 'basePath' => 'Admins/messages',
                        'queryString' => $paginationQuery, 'itemLabel' => 'message(s)',
                    ]); ?>
                </div>
            </div>
            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>
<?php $this->view('Partials/scripts'); ?>
</body>
</html>
