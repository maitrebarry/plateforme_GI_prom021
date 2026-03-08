<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Messages / Contact']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <div class="dashboard-body__content p-4">
                <div class="card common-card">
                    <div class="card-body">
                        <h5 class="mb-3">Messages / Contact</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Projet</th>
                                        <th>Message</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($messages)): ?>
                                        <?php foreach ($messages as $msg): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($msg->nom ?? '') ?></td>
                                                <td><?= htmlspecialchars($msg->email ?? '') ?></td>
                                                <td><?= htmlspecialchars($msg->projet ?? '') ?></td>
                                                <td><?= htmlspecialchars($msg->message ?? '') ?></td>
                                                <td><?= htmlspecialchars(date('Y-m-d', strtotime((string)($msg->created_at ?? 'now')))) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5" class="text-center text-muted">Aucun message reçu.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>
<?php $this->view('Partials/scripts'); ?>
</body>
</html>
