<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Detail message utilisateur']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$messageItem = $messageItem ?? null;
$email = (string) ($messageItem->email ?? '');
$projectTitle = (string) ($messageItem->projet ?? 'Votre projet');
$defaultSubject = 'Reponse concernant votre message sur ' . $projectTitle;
$defaultBody = "Bonjour " . ((string) ($messageItem->nom ?? '')) . ",\n\nMerci pour votre message concernant le projet \"" . $projectTitle . "\".\n\nVotre reponse ici.\n\nCordialement,\nAdministration";
?>
<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-4">
                <?php $this->view('set_flash'); ?>

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div>
                        <h3 class="mb-1">Detail du message</h3>
                        <p class="text-muted mb-0">Consultation et reponse rapide au message utilisateur.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?= ROOT ?>/Admins/messages" class="btn btn-outline-secondary">Retour messages</a>
                        <?php if (!empty($messageItem->project_id)): ?>
                            <a href="<?= ROOT ?>/Admins/project_detail/<?= (int) ($messageItem->project_id ?? 0) ?>" class="btn btn-outline-primary">Voir le projet</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row gy-4">
                    <div class="col-xl-5">
                        <div class="card common-card">
                            <div class="card-body">
                                <h5 class="mb-3">Message recu</h5>
                                <p><strong>Nom:</strong> <?= htmlspecialchars((string) ($messageItem->nom ?? '')) ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
                                <p><strong>Projet:</strong> <?= htmlspecialchars($projectTitle) ?></p>
                                <p><strong>Date:</strong> <?= !empty($messageItem->created_at) ? htmlspecialchars(date('Y-m-d H:i', strtotime((string) $messageItem->created_at))) : '-' ?></p>
                                <div class="border rounded-4 p-3 bg-light text-dark" style="line-height:1.7">
                                    <?= nl2br(htmlspecialchars((string) ($messageItem->message ?? ''))) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7">
                        <div class="card common-card">
                            <div class="card-body">
                                <h5 class="mb-3">Repondre a l utilisateur</h5>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Objet</label>
                                    <input type="text" id="reply-subject" class="common-input common-input--bg" value="<?= htmlspecialchars($defaultSubject) ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Message de reponse</label>
                                    <textarea id="reply-body" class="common-input common-input--bg" rows="10"><?= htmlspecialchars($defaultBody) ?></textarea>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <a id="reply-mailto-link" href="mailto:<?= rawurlencode($email) ?>?subject=<?= rawurlencode($defaultSubject) ?>&body=<?= rawurlencode($defaultBody) ?>" class="btn btn-primary">Ouvrir la reponse email</a>
                                    <a href="mailto:<?= rawurlencode($email) ?>" class="btn btn-outline-secondary">Contacter directement</a>
                                </div>
                                <p class="text-muted mt-3 mb-0">Le bouton ouvre votre client email avec l objet et le contenu prepares.</p>
                            </div>
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

    function updateMailtoLink() {
        link.href = 'mailto:' + encodeURIComponent(email) + '?subject=' + encodeURIComponent(subject.value) + '&body=' + encodeURIComponent(body.value);
    }

    subject.addEventListener('input', updateMailtoLink);
    body.addEventListener('input', updateMailtoLink);
    updateMailtoLink();
});
</script>
</body>
</html>
