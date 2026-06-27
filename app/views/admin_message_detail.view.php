<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Détail message']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$messageItem = $messageItem ?? null;
$email = (string) ($messageItem->email ?? '');
$mNom = (string) ($messageItem->nom ?? '');
$projectTitle = (string) ($messageItem->projet ?? 'Votre projet');
$defaultSubject = 'Réponse concernant votre message sur ' . $projectTitle;
$defaultBody = "Bonjour " . $mNom . ",\n\nMerci pour votre message concernant le projet \"" . $projectTitle . "\".\n\nVotre réponse ici.\n\nCordialement,\nAdministration";
?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-3 p-lg-4">
                <?php $this->view('set_flash'); ?>

                <style>
                    .amd-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 18px; }
                    .amd-head__l { display: flex; align-items: center; gap: 12px; }
                    .adm-back { width: 42px; height: 42px; border-radius: 12px; background: var(--ds-surface); border: 1px solid var(--ds-border); display: inline-flex; align-items: center; justify-content: center; color: var(--ds-ink); text-decoration: none; font-size: 1.2rem; transition: all var(--ds-transition); }
                    .adm-back:hover { background: var(--ds-brand-50); color: var(--ds-brand-700); }
                    .amd-head h1 { font-family: var(--ds-font-heading); font-size: 1.3rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0; }
                    .amd-head p { color: var(--ds-muted); font-size: .84rem; margin: 0; }

                    .amd-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); box-shadow: var(--ds-shadow-sm); padding: 22px; }
                    .amd-title { display: flex; align-items: center; gap: 8px; font-family: var(--ds-font-heading); font-size: 1.05rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0 0 16px; padding-bottom: 12px; border-bottom: 1px solid var(--ds-border); }
                    .amd-title i { color: var(--ds-brand-600); }
                    .amd-row { display: flex; align-items: center; gap: 11px; padding: 11px 0; border-bottom: 1px solid var(--ds-border); }
                    .amd-row__ico { width: 34px; height: 34px; border-radius: 10px; background: var(--ds-brand-50); color: var(--ds-brand-600); display: inline-flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0; }
                    .amd-row small { color: var(--ds-muted); font-size: .74rem; display: block; }
                    .amd-row strong { color: var(--ds-ink-strong); font-size: .92rem; word-break: break-word; }
                    .amd-bubble { background: var(--ds-surface-2); border: 1px solid var(--ds-border); border-radius: var(--ds-radius); padding: 16px; line-height: 1.7; color: var(--ds-ink); font-size: .96rem; margin-top: 16px; }

                    .amd-label { display: block; font-weight: 700; font-size: .76rem; text-transform: uppercase; letter-spacing: .05em; color: var(--ds-muted); margin-bottom: 7px; }
                    .amd-input, .amd-textarea { width: 100%; border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius); padding: 11px 14px; font-size: .94rem; color: var(--ds-ink); background: var(--ds-surface); font-family: var(--ds-font-sans); }
                    .amd-input:focus, .amd-textarea:focus { outline: none; border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); }
                    .amd-textarea { resize: vertical; min-height: 200px; line-height: 1.6; }
                    .amd-btn { display: inline-flex; align-items: center; gap: 8px; font-weight: 700; font-size: .9rem; padding: 11px 22px; border-radius: var(--ds-radius-pill); text-decoration: none; transition: all var(--ds-transition); }
                    .amd-btn--solid { background: var(--ds-brand-600); color: #fff; } .amd-btn--solid:hover { background: var(--ds-brand-700); color: #fff; }
                    .amd-btn--ghost { background: var(--ds-surface-2); color: var(--ds-brand-700); border: 1px solid var(--ds-border); }
                    .amd-note { margin-top: 16px; padding: 13px 15px; background: var(--ds-brand-50); border-left: 3px solid var(--ds-brand-500); border-radius: var(--ds-radius); color: var(--ds-muted); font-size: .85rem; }
                </style>

                <div class="amd-head">
                    <div class="amd-head__l">
                        <a href="<?= ROOT ?>/Admins/messages" class="adm-back"><i class='bx bx-left-arrow-alt'></i></a>
                        <div><h1>Détail du message</h1><p>Consultation et réponse personnalisée</p></div>
                    </div>
                    <?php if (!empty($messageItem->project_id)): ?>
                        <a href="<?= ROOT ?>/Admins/project_detail/<?= (int) ($messageItem->project_id ?? 0) ?>" class="amd-btn amd-btn--ghost"><i class='bx bx-show'></i> Voir le projet</a>
                    <?php endif; ?>
                </div>

                <div class="row gy-4">
                    <div class="col-xl-5">
                        <div class="amd-card">
                            <h2 class="amd-title"><i class='bx bx-envelope-open'></i> Message reçu</h2>
                            <div class="amd-row"><span class="amd-row__ico"><i class='bx bx-user'></i></span><div><small>Expéditeur</small><strong><?= htmlspecialchars($mNom !== '' ? $mNom : 'Inconnu') ?></strong></div></div>
                            <div class="amd-row"><span class="amd-row__ico"><i class='bx bx-at'></i></span><div><small>Email</small><strong><?= htmlspecialchars($email !== '' ? $email : '—') ?></strong></div></div>
                            <div class="amd-row"><span class="amd-row__ico"><i class='bx bx-folder'></i></span><div><small>Sujet / Projet</small><strong><?= htmlspecialchars($projectTitle) ?></strong></div></div>
                            <div class="amd-row"><span class="amd-row__ico"><i class='bx bx-calendar'></i></span><div><small>Reçu le</small><strong><?= !empty($messageItem->created_at) ? htmlspecialchars(date('d/m/Y à H:i', strtotime((string) $messageItem->created_at))) : '—' ?></strong></div></div>
                            <div class="amd-bubble"><?= nl2br(htmlspecialchars((string) ($messageItem->message ?? ''))) ?></div>
                        </div>
                    </div>

                    <div class="col-xl-7">
                        <div class="amd-card">
                            <h2 class="amd-title"><i class='bx bx-edit'></i> Répondre à l'utilisateur</h2>
                            <div class="mb-3">
                                <label class="amd-label">Objet de l'email</label>
                                <input type="text" id="reply-subject" class="amd-input" value="<?= htmlspecialchars($defaultSubject) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="amd-label">Corps du message</label>
                                <textarea id="reply-body" class="amd-textarea" rows="11"><?= htmlspecialchars($defaultBody) ?></textarea>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a id="reply-mailto-link" href="mailto:<?= rawurlencode($email) ?>?subject=<?= rawurlencode($defaultSubject) ?>&body=<?= rawurlencode($defaultBody) ?>" class="amd-btn amd-btn--solid"><i class='bx bx-send'></i> Envoyer la réponse</a>
                                <a href="mailto:<?= rawurlencode($email) ?>" class="amd-btn amd-btn--ghost"><i class='bx bx-envelope'></i> Contact direct</a>
                            </div>
                            <div class="amd-note"><strong>Note :</strong> ce bouton ouvre votre client de messagerie par défaut (Outlook, Gmail…) avec le contenu déjà formaté.</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>
<?php $this->view('Partials/scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var link = document.getElementById('reply-mailto-link');
        var subject = document.getElementById('reply-subject');
        var body = document.getElementById('reply-body');
        var email = <?= json_encode($email) ?>;
        function update() { link.href = 'mailto:' + encodeURIComponent(email) + '?subject=' + encodeURIComponent(subject.value) + '&body=' + encodeURIComponent(body.value); }
        if (subject && body && link) { subject.addEventListener('input', update); body.addEventListener('input', update); update(); }
    });
</script>
</body>
</html>
