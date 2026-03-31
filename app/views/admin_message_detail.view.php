<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Detail message utilisateur']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$messageItem = $messageItem ?? null;
$email = (string) ($messageItem->email ?? '');
$projectTitle = (string) ($messageItem->projet ?? 'Votre projet');
$defaultSubject = 'Réponse concernant votre message sur ' . $projectTitle;
$defaultBody = "Bonjour " . ((string) ($messageItem->nom ?? '')) . ",\n\nMerci pour votre message concernant le projet \"" . $projectTitle . "\".\n\nVotre réponse ici.\n\nCordialement,\nAdministration";
?>
<style>
:root {
    --primary-color: #6366f1;
    --primary-hover: #4f46e5;
    --secondary-color: #94a3b8;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --bg-light: #f1f5f9;
    --text-main: #0f172a;
    --text-muted: #64748b;
    --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

body {
    background-color: var(--bg-light);
    color: var(--text-main);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.common-card {
    border: none;
    border-radius: 20px;
    box-shadow: var(--card-shadow);
    background: #ffffff;
    margin-bottom: 24px;
    overflow: hidden;
}

.common-card h5 {
    font-weight: 800;
    color: var(--text-main);
    border-bottom: 2px solid #f1f5f9;
    padding-bottom: 1rem;
    margin-bottom: 1.25rem;
}

.header-back-btn {
    width: 45px;
    height: 45px;
    background: white;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s;
}

.header-back-btn:hover {
    transform: translateX(-5px);
    background: #f8fafc;
}

.btn {
    font-weight: 700;
    border-radius: 12px;
    padding: 0.8rem 1.5rem;
    transition: all 0.3s;
}

.btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; }
.btn-outline-primary { border: 2px solid var(--primary-color); color: var(--primary-color); }
.btn-outline-primary:hover { background: var(--primary-color); color: white; }

.message-bubble {
    background: #f8fafc;
    border-radius: 18px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    line-height: 1.8;
    color: #1e293b;
    font-size: 1.05rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #f1f5f9;
}

.detail-icon {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f5f9;
    border-radius: 8px;
    font-size: 0.9rem;
}
</style>
<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-4">
                <?php $this->view('set_flash'); ?>

                <div class="card common-card mb-4 border-0" style="background: transparent; box-shadow: none;">
                    <div class="card-body p-0 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <a href="<?= ROOT ?>/Admins/messages" class="header-back-btn">
                                ⬅️
                            </a>
                            <div>
                                <h3 class="mb-0 fw-800 text-primary">Détail du Message</h3>
                                <p class="text-muted small mb-0">Consultation et réponse personnalisée</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                             <?php if (!empty($messageItem->project_id)): ?>
                                <a href="<?= ROOT ?>/Admins/project_detail/<?= (int) ($messageItem->project_id ?? 0) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-4">👁️ Voir le projet</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row gy-4">
                    <div class="col-xl-5">
                        <div class="card common-card">
                            <div class="card-body">
                                <h5>📩 Message reçu</h5>
                                <div class="detail-item">
                                    <div class="detail-icon">👤</div>
                                    <div><span class="small text-muted d-block">Expéditeur</span><strong><?= htmlspecialchars((string) ($messageItem->nom ?? '')) ?></strong></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-icon">✉️</div>
                                    <div><span class="small text-muted d-block">Email</span><strong><?= htmlspecialchars($email) ?></strong></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-icon">📂</div>
                                    <div><span class="small text-muted d-block">Sujet / Projet</span><strong><?= htmlspecialchars($projectTitle) ?></strong></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-icon">📅</div>
                                    <div><span class="small text-muted d-block">Reçu le</span><strong><?= !empty($messageItem->created_at) ? htmlspecialchars(date('d/m/Y à H:i', strtotime((string) $messageItem->created_at))) : '-' ?></strong></div>
                                </div>
                                <div class="message-bubble mt-4">
                                    <?= nl2br(htmlspecialchars((string) ($messageItem->message ?? ''))) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7">
                        <div class="card common-card">
                            <div class="card-body">
                                <h5>✍️ Répondre à l'utilisateur</h5>
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted mb-2">OBJET DE L'EMAIL</label>
                                    <input type="text" id="reply-subject" class="form-control rounded-3 border px-3 py-2" value="<?= htmlspecialchars($defaultSubject) ?>">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted mb-2">CORPS DU MESSAGE</label>
                                    <textarea id="reply-body" class="form-control rounded-3 border px-3 py-2" rows="12" style="font-family: inherit;"><?= htmlspecialchars($defaultBody) ?></textarea>
                                </div>
                                <div class="d-flex flex-wrap gap-3">
                                    <a id="reply-mailto-link" href="mailto:<?= rawurlencode($email) ?>?subject=<?= rawurlencode($defaultSubject) ?>&body=<?= rawurlencode($defaultBody) ?>" class="btn btn-primary px-5 shadow-sm">
                                        🚀 Envoyer la réponse
                                    </a>
                                    <a href="mailto:<?= rawurlencode($email) ?>" class="btn btn-outline-primary rounded-pill px-4">
                                        📧 Contact direct
                                    </a>
                                </div>
                                <div class="mt-4 p-3 bg-light rounded-4 border-start border-4 border-primary">
                                    <p class="text-muted small mb-0"><strong>Note :</strong> Cette action ouvrira votre client de messagerie par défaut (Outlook, Gmail, etc.) avec le contenu déjà formaté.</p>
                                </div>
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
