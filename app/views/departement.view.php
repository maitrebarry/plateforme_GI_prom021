<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Espace Département']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php $this->view('Partials/header'); ?>
<?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

<main class="change-gradient">
    <section class="breadcrumb mb-0 bg-main-two position-relative z-index-1 overflow-hidden">
        <div class="container container-two">
            <div class="breadcrumb-two">
                <h1 class="breadcrumb-two__title text-white mb-2">Espace Département</h1>
                <p class="text-white mb-0">
                    <?= htmlspecialchars($department['name'] ?? 'Département') ?> - Responsable: <?= htmlspecialchars($department['manager'] ?? 'DER') ?>
                </p>
            </div>
        </div>
    </section>

    <section class="padding-y-120" data-dynamic-block="department-space">
        <div class="container container-two">
            <div class="row gy-4">
                <div class="col-lg-8">
                    <div class="card common-card mb-4" data-dynamic-block="department-announcements">
                        <div class="card-body">
                            <h5 class="mb-3">Annonces du département</h5>
                            <?php if (!empty($departmentAnnouncements)): ?>
                                <ul class="mb-0">
                                    <?php foreach ($departmentAnnouncements as $item): ?>
                                        <li class="mb-2">
                                            <strong><?= htmlspecialchars($item['title'] ?? '') ?></strong>
                                            <span class="text-muted">(<?= htmlspecialchars($item['date'] ?? '') ?>)</span><br>
                                            <span><?= htmlspecialchars($item['content'] ?? '') ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-muted mb-0">Aucune annonce disponible.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card common-card mb-4" data-dynamic-block="department-events">
                        <div class="card-body">
                            <h5 class="mb-3">Événements</h5>
                            <?php if (!empty($departmentEvents)): ?>
                                <ul class="mb-0">
                                    <?php foreach ($departmentEvents as $item): ?>
                                        <li class="mb-2">
                                            <strong><?= htmlspecialchars($item['title'] ?? '') ?></strong>
                                            <span class="text-muted">(<?= htmlspecialchars($item['date'] ?? '') ?>)</span>
                                            <div><?= htmlspecialchars($item['location'] ?? '') ?></div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-muted mb-0">Aucun événement disponible.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card common-card" data-dynamic-block="department-opportunities">
                        <div class="card-body">
                            <h5 class="mb-3">Opportunités</h5>
                            <?php if (!empty($departmentOpportunities)): ?>
                                <ul class="mb-0">
                                    <?php foreach ($departmentOpportunities as $item): ?>
                                        <li class="mb-2">
                                            <strong><?= htmlspecialchars($item['title'] ?? '') ?></strong>
                                            <span class="text-muted">(<?= htmlspecialchars($item['date'] ?? '') ?>)</span>
                                            <div><?= htmlspecialchars($item['organization'] ?? '') ?></div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-muted mb-0">Aucune opportunité disponible.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card common-card mb-4" data-dynamic-block="department-results">
                        <div class="card-body">
                            <h5 class="mb-3">Résultats publiés</h5>
                            <?php if (!empty($departmentResults)): ?>
                                <ul class="mb-0">
                                    <?php foreach ($departmentResults as $item): ?>
                                        <li class="mb-2">
                                            <strong><?= htmlspecialchars($item['title'] ?? '') ?></strong><br>
                                            <small class="text-muted"><?= htmlspecialchars($item['date'] ?? '') ?></small>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-muted mb-0">Aucun résultat publié.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card common-card" data-dynamic-block="department-publication-form">
                        <div class="card-body">
                            <h5 class="mb-3">Publication DER</h5>
                            <form action="#" method="post">
                                <div class="mb-3">
                                    <label for="post_type" class="form-label">Type</label>
                                    <select id="post_type" name="post_type" class="common-input common-input--bg" required>
                                        <option value="announcement">Annonce</option>
                                        <option value="event">Événement</option>
                                        <option value="result">Résultat</option>
                                        <option value="opportunity">Opportunité</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="post_title" class="form-label">Titre</label>
                                    <input id="post_title" name="post_title" type="text" class="common-input common-input--bg" required>
                                </div>
                                <div class="mb-3">
                                    <label for="post_content" class="form-label">Contenu</label>
                                    <textarea id="post_content" name="post_content" class="common-input common-input--bg" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-main pill w-100">Publier</button>
                            </form>
                            <small class="text-muted d-block mt-2">Prêt pour liaison backend (validation DER + base de données).</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php $this->view('Partials/footer'); ?>
</main>

<?php $this->view('Partials/scripts'); ?>
</body>
</html>
