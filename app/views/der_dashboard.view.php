<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Dashboard DER']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>

            <div class="dashboard-body__content p-4">
                <?php $this->view('set_flash'); ?>

                <style>
                    /* Variables de couleurs */
                    :root {
                        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        --success-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
                        --warning-gradient: linear-gradient(135deg, #fad961 0%, #f76b1c 100%);
                        --danger-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                        --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
                    }

                    /* Cartes statistiques améliorées */
                    .stat-card-modern {
                        border: none;
                        border-radius: 24px;
                        background: white;
                        box-shadow: 0 20px 35px -8px rgba(0, 0, 0, 0.1), 
                                    0 5px 15px -5px rgba(0, 0, 0, 0.05);
                        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
                        height: 100%;
                        position: relative;
                        overflow: hidden;
                    }

                    .stat-card-modern::before {
                        content: '';
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        height: 4px;
                        background: var(--primary-gradient);
                        transform: scaleX(0);
                        transition: transform 0.4s ease;
                    }

                    .stat-card-modern:nth-child(2)::before { background: var(--success-gradient); }
                    .stat-card-modern:nth-child(3)::before { background: var(--warning-gradient); }
                    .stat-card-modern:nth-child(4)::before { background: var(--danger-gradient); }
                    .stat-card-modern:nth-child(5)::before { background: var(--info-gradient); }

                    .stat-card-modern:hover {
                        transform: translateY(-8px) scale(1.02);
                        box-shadow: 0 30px 45px -12px rgba(0, 0, 0, 0.2);
                    }

                    .stat-card-modern:hover::before {
                        transform: scaleX(1);
                    }

                    .stat-icon-wrapper {
                        width: 80px;
                        height: 80px;
                        border-radius: 20px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: var(--primary-gradient);
                        color: white;
                        font-size: 38px;
                        box-shadow: 0 15px 25px -8px rgba(102, 126, 234, 0.4);
                        transition: all 0.3s ease;
                        position: relative;
                        z-index: 1;
                    }

                    .stat-card-modern:nth-child(2) .stat-icon-wrapper {
                        background: var(--success-gradient);
                        box-shadow: 0 15px 25px -8px rgba(132, 250, 176, 0.4);
                    }

                    .stat-card-modern:nth-child(3) .stat-icon-wrapper {
                        background: var(--warning-gradient);
                        box-shadow: 0 15px 25px -8px rgba(250, 217, 97, 0.4);
                    }

                    .stat-card-modern:nth-child(4) .stat-icon-wrapper {
                        background: var(--danger-gradient);
                        box-shadow: 0 15px 25px -8px rgba(240, 147, 251, 0.4);
                    }

                    .stat-card-modern:nth-child(5) .stat-icon-wrapper {
                        background: var(--info-gradient);
                        box-shadow: 0 15px 25px -8px rgba(79, 172, 254, 0.4);
                    }

                    .stat-card-modern:hover .stat-icon-wrapper {
                        transform: scale(1.1) rotate(5deg);
                    }

                    .stat-content {
                        flex: 1;
                        position: relative;
                        z-index: 1;
                    }

                    .stat-label {
                        font-size: 16px;
                        font-weight: 600;
                        color: #64748b;
                        margin-bottom: 8px;
                        letter-spacing: 0.5px;
                        text-transform: uppercase;
                    }

                    .stat-value {
                        font-size: 42px;
                        font-weight: 800;
                        color: #1e293b;
                        line-height: 1.2;
                        text-shadow: 2px 2px 4px rgba(0,0,0,0.05);
                    }

                    .stat-trend {
                        font-size: 14px;
                        color: #10b981;
                        background: rgba(16, 185, 129, 0.1);
                        padding: 4px 8px;
                        border-radius: 30px;
                        display: inline-block;
                        margin-top: 8px;
                    }

                    /* Carte des dernières publications */
                    .publications-card {
                        border: none;
                        border-radius: 24px;
                        background: white;
                        box-shadow: 0 20px 35px -8px rgba(0, 0, 0, 0.1);
                        overflow: hidden;
                    }

                    .publications-header {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        padding: 1.5rem 2rem;
                        color: white;
                    }

                    .publications-header h5 {
                        font-size: 1.5rem;
                        font-weight: 700;
                        margin: 0;
                    }

                    .publications-list {
                        padding: 1.5rem;
                    }

                    .publication-item {
                        background: white;
                        border: 2px solid #f1f5f9;
                        border-radius: 20px;
                        padding: 1.5rem;
                        margin-bottom: 1rem;
                        transition: all 0.3s ease;
                        position: relative;
                        overflow: hidden;
                    }

                    .publication-item::before {
                        content: '';
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 6px;
                        height: 100%;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        opacity: 0;
                        transition: opacity 0.3s ease;
                    }

                    .publication-item:nth-child(even)::before {
                        background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
                    }

                    .publication-item:hover {
                        transform: translateX(8px);
                        box-shadow: 0 15px 30px -12px rgba(0, 0, 0, 0.15);
                        border-color: transparent;
                    }

                    .publication-item:hover::before {
                        opacity: 1;
                    }

                    .publication-title {
                        font-size: 1.2rem;
                        font-weight: 700;
                        color: #1e293b;
                        margin-bottom: 0.5rem;
                    }

                    .publication-meta {
                        display: flex;
                        gap: 20px;
                        margin-bottom: 1rem;
                        color: #64748b;
                        font-size: 0.95rem;
                    }

                    .publication-meta i {
                        color: #667eea;
                        margin-right: 5px;
                    }

                    .publication-content {
                        color: #475569;
                        margin-bottom: 1rem;
                        line-height: 1.6;
                    }

                    .publication-files {
                        background: #f8fafc;
                        border-radius: 15px;
                        padding: 1rem;
                        margin-top: 1rem;
                    }

                    .file-link {
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        padding: 8px 16px;
                        background: white;
                        border: 1px solid #e2e8f0;
                        border-radius: 30px;
                        color: #1e293b;
                        text-decoration: none;
                        transition: all 0.3s ease;
                        margin-right: 10px;
                        margin-bottom: 5px;
                    }

                    .file-link:hover {
                        background: #667eea;
                        color: white;
                        border-color: transparent;
                        transform: translateY(-2px);
                    }

                    .file-link i {
                        font-size: 18px;
                    }

                    .btn-gradient {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        border: none;
                        padding: 12px 28px;
                        border-radius: 50px;
                        font-weight: 600;
                        box-shadow: 0 10px 20px -8px rgba(102, 126, 234, 0.4);
                        transition: all 0.3s ease;
                    }

                    .btn-gradient:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 15px 25px -8px rgba(102, 126, 234, 0.6);
                        color: white;
                    }

                    /* Animation de fond pour les cartes */
                    @keyframes float {
                        0% { transform: translateY(0px); }
                        50% { transform: translateY(-10px); }
                        100% { transform: translateY(0px); }
                    }

                    .bg-pattern {
                        position: absolute;
                        top: -50%;
                        right: -20%;
                        width: 200px;
                        height: 200px;
                        background: radial-gradient(circle, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.05) 100%);
                        border-radius: 50%;
                        z-index: 0;
                        animation: float 6s ease-in-out infinite;
                    }
                </style>

                <!-- Cartes statistiques améliorées -->
                <?php
                $derCards = [
                    ['label' => 'Informations', 'value' => (int)($derStats['informations'] ?? 0), 'icon' => 'bx bx-news', 'trend' => '+12%', 'class' => 'col-lg-4 col-md-6'],
                    ['label' => 'Annonces', 'value' => (int)($derStats['annonces'] ?? 0), 'icon' => 'bx bx-megaphone', 'trend' => '+8%', 'class' => 'col-lg-4 col-md-6'],
                    ['label' => 'Événements', 'value' => (int)($derStats['evenements'] ?? 0), 'icon' => 'bx bx-calendar-star', 'trend' => '+5%', 'class' => 'col-lg-4 col-md-6'],
                    ['label' => 'Résultats', 'value' => (int)($derStats['resultats'] ?? 0), 'icon' => 'bx bx-trophy', 'trend' => '+15%', 'class' => 'col-lg-6 col-md-6'],
                    ['label' => 'Opportunités', 'value' => (int)($derStats['opportunites'] ?? 0), 'icon' => 'bx bx-briefcase', 'trend' => '+20%', 'class' => 'col-lg-6 col-md-6'],
                ];
                ?>

                <div class="row g-4 mb-4">
                    <?php foreach ($derCards as $index => $card): ?>
                        <div class="<?= htmlspecialchars($card['class']) ?>">
                            <div class="stat-card-modern card">
                                <div class="card-body p-4 d-flex align-items-center gap-4 position-relative">
                                    <div class="bg-pattern"></div>
                                    <div class="stat-icon-wrapper">
                                        <i class="<?= htmlspecialchars($card['icon']) ?>"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-label"><?= htmlspecialchars($card['label']) ?></div>
                                        <div class="stat-value"><?= (int)$card['value'] ?></div>
                                        <span class="stat-trend">
                                            <i class="bx bx-trending-up"></i> <?= htmlspecialchars($card['trend']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Dernières publications améliorées -->
                <div class="publications-card card">
                    <div class="publications-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bx bx-time me-2"></i>
                            Dernières publications DER
                        </h5>
                        <a href="<?= ROOT ?>/Homes/der_espace" class="btn btn-gradient">
                            <i class="bx bx-plus-circle me-2"></i>
                            Gérer les publications
                        </a>
                    </div>
                    
                    <div class="publications-list">
                        <?php if (!empty($latestPublications)): ?>
                            <?php foreach ($latestPublications as $item): ?>
                                <div class="publication-item">
                                    <h6 class="publication-title">
                                        <i class="bx bx-detail me-2"></i>
                                        <?= htmlspecialchars($item['title'] ?? '') ?>
                                    </h6>
                                    <div class="publication-meta">
                                        <span>
                                            <i class="bx bx-calendar"></i>
                                            <?= htmlspecialchars($item['date'] ?? '') ?>
                                        </span>
                                        <span>
                                            <i class="bx bx-user"></i>
                                            DER
                                        </span>
                                    </div>
                                    <p class="publication-content">
                                        <?= htmlspecialchars($item['content'] ?? '') ?>
                                    </p>
                                    
                                    <?php if (!empty($item['files'])): ?>
                                        <div class="publication-files">
                                            <small class="text-muted d-block mb-2">
                                                <i class="bx bx-paperclip me-1"></i>
                                                Fichiers joints :
                                            </small>
                                            <?php foreach ($item['files'] as $file): ?>
                                                <a class="file-link" href="<?= htmlspecialchars($file['url'] ?? '#') ?>" download>
                                                    <i class="bx bx-file"></i>
                                                    <?= htmlspecialchars($file['name'] ?? 'Document') ?>
                                                    <i class="bx bx-download ms-1"></i>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="bx bx-folder-open" style="font-size: 64px; color: #cbd5e1;"></i>
                                </div>
                                <p class="text-muted fs-5 mb-0">Aucune publication pour le moment</p>
                                <p class="text-muted">Commencez par créer votre première publication !</p>
                                <a href="<?= ROOT ?>/Homes/der_espace" class="btn btn-gradient mt-3">
                                    <i class="bx bx-plus-circle me-2"></i>
                                    Créer une publication
                                </a>
                            </div>
                        <?php endif; ?>
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