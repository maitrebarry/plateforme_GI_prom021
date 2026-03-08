<?php 
// Ajout du CDN Boxicons et CSS pour drag & drop
$this->view('Partials/head', [
    'pageTitle' => $pageTitle ?? 'Espace DER',
    'additionalHead' => '
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
    '
]); 
?>
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
                    /* Variables et styles modernes */
                    :root {
                        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        --success-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
                        --warning-gradient: linear-gradient(135deg, #fad961 0%, #f76b1c 100%);
                        --danger-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                        --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
                    }

                    /* Carte de publication */
                    .publish-card {
                        border: none;
                        border-radius: 30px;
                        background: white;
                        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.15);
                        overflow: hidden;
                        position: relative;
                    }

                    .publish-card::before {
                        content: '';
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        height: 5px;
                        background: var(--primary-gradient);
                    }

                    .publish-header {
                        background: linear-gradient(135deg, #f8faff 0%, #f0f3ff 100%);
                        padding: 1.8rem;
                        border-bottom: 1px solid rgba(102, 126, 234, 0.1);
                    }

                    .publish-header h5 {
                        font-size: 1.5rem;
                        font-weight: 700;
                        color: #1e293b;
                        margin: 0;
                    }

                    .publish-header i {
                        color: #667eea;
                        font-size: 2rem;
                        margin-right: 10px;
                    }

                    /* Champs de formulaire stylisés */
                    .modern-input {
                        border: 2px solid #e2e8f0;
                        border-radius: 16px;
                        padding: 12px 18px;
                        font-size: 1rem;
                        transition: all 0.3s ease;
                        background: white;
                        width: 100%;
                    }

                    .modern-input:focus {
                        border-color: #667eea;
                        box-shadow: 0 5px 15px -5px rgba(102, 126, 234, 0.3);
                        outline: none;
                    }

                    .modern-select {
                        border: 2px solid #e2e8f0;
                        border-radius: 16px;
                        padding: 12px 18px;
                        font-size: 1rem;
                        background: white;
                        cursor: pointer;
                        appearance: none;
                        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23667eea' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
                        background-repeat: no-repeat;
                        background-position: right 18px center;
                        background-size: 16px;
                    }

                    .modern-select:focus {
                        border-color: #667eea;
                        box-shadow: 0 5px 15px -5px rgba(102, 126, 234, 0.3);
                        outline: none;
                    }

                    /* Zone de drag & drop personnalisée */
                    .file-upload-area {
                        border: 3px dashed #cbd5e1;
                        border-radius: 20px;
                        padding: 2rem;
                        text-align: center;
                        background: #f8fafc;
                        transition: all 0.3s ease;
                        cursor: pointer;
                        position: relative;
                    }

                    .file-upload-area:hover,
                    .file-upload-area.dragover {
                        border-color: #667eea;
                        background: rgba(102, 126, 234, 0.05);
                        transform: scale(1.02);
                    }

                    .file-upload-area i {
                        font-size: 48px;
                        color: #667eea;
                        margin-bottom: 15px;
                    }

                    .file-upload-area .upload-text {
                        font-size: 1.1rem;
                        color: #1e293b;
                        font-weight: 600;
                        margin-bottom: 5px;
                    }

                    .file-upload-area .upload-hint {
                        color: #64748b;
                        font-size: 0.9rem;
                    }

                    .file-list {
                        margin-top: 20px;
                        display: none;
                    }

                    .file-list.show {
                        display: block;
                    }

                    .file-item {
                        background: white;
                        border: 1px solid #e2e8f0;
                        border-radius: 12px;
                        padding: 12px 16px;
                        margin-bottom: 10px;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        animation: slideIn 0.3s ease;
                    }

                    @keyframes slideIn {
                        from {
                            opacity: 0;
                            transform: translateY(-10px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }

                    .file-info {
                        display: flex;
                        align-items: center;
                        gap: 12px;
                    }

                    .file-info i {
                        font-size: 24px;
                        color: #667eea;
                    }

                    .file-name {
                        font-weight: 600;
                        color: #1e293b;
                    }

                    .file-size {
                        font-size: 0.85rem;
                        color: #64748b;
                    }

                    .file-remove {
                        color: #ef4444;
                        cursor: pointer;
                        padding: 5px;
                        border-radius: 8px;
                        transition: all 0.3s ease;
                    }

                    .file-remove:hover {
                        background: #fee2e2;
                        transform: scale(1.1);
                    }

                    /* Boutons stylisés */
                    .btn-publish {
                        background: var(--primary-gradient);
                        color: white;
                        border: none;
                        padding: 14px 28px;
                        border-radius: 50px;
                        font-weight: 600;
                        font-size: 1.1rem;
                        box-shadow: 0 10px 20px -8px rgba(102, 126, 234, 0.4);
                        transition: all 0.3s ease;
                        width: 100%;
                        cursor: pointer;
                    }

                    .btn-publish:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 15px 30px -8px rgba(102, 126, 234, 0.6);
                    }

                    .btn-outline-gradient {
                        background: transparent;
                        border: 2px solid #667eea;
                        color: #667eea;
                        padding: 12px 28px;
                        border-radius: 50px;
                        font-weight: 600;
                        transition: all 0.3s ease;
                        width: 100%;
                        text-decoration: none;
                        display: inline-block;
                        text-align: center;
                    }

                    .btn-outline-gradient:hover {
                        background: var(--primary-gradient);
                        border-color: transparent;
                        color: white;
                        transform: translateY(-2px);
                    }

                    /* Cartes de publications existantes */
                    .publications-container {
                        max-height: 800px;
                        overflow-y: auto;
                        padding-right: 10px;
                    }

                    .publications-container::-webkit-scrollbar {
                        width: 6px;
                    }

                    .publications-container::-webkit-scrollbar-track {
                        background: #f1f1f1;
                        border-radius: 10px;
                    }

                    .publications-container::-webkit-scrollbar-thumb {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        border-radius: 10px;
                    }

                    .publication-category {
                        margin-top: 25px;
                        margin-bottom: 15px;
                    }

                    .publication-category h6 {
                        font-size: 1.2rem;
                        font-weight: 700;
                        color: #1e293b;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        padding-bottom: 8px;
                        border-bottom: 3px solid #e2e8f0;
                    }

                    .publication-category h6 i {
                        font-size: 1.5rem;
                        color: #667eea;
                    }

                    .publication-item {
                        background: white;
                        border: 2px solid #f1f5f9;
                        border-radius: 20px;
                        padding: 1.5rem;
                        margin-bottom: 1rem;
                        transition: all 0.3s ease;
                        position: relative;
                    }

                    .publication-item:hover {
                        transform: translateX(8px);
                        box-shadow: 0 15px 30px -12px rgba(0, 0, 0, 0.15);
                        border-color: transparent;
                    }

                    .publication-item::before {
                        content: '';
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 6px;
                        height: 100%;
                        background: var(--primary-gradient);
                        border-radius: 20px 0 0 20px;
                        opacity: 0;
                        transition: opacity 0.3s ease;
                    }

                    .publication-item:hover::before {
                        opacity: 1;
                    }

                    .publication-title {
                        font-size: 1.1rem;
                        font-weight: 700;
                        color: #1e293b;
                        margin-bottom: 0.5rem;
                    }

                    .publication-date {
                        display: inline-block;
                        background: rgba(102, 126, 234, 0.1);
                        color: #667eea;
                        padding: 4px 12px;
                        border-radius: 30px;
                        font-size: 0.85rem;
                        margin-bottom: 1rem;
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

                    .badge-type {
                        position: absolute;
                        top: 1rem;
                        right: 1rem;
                        padding: 6px 12px;
                        border-radius: 30px;
                        font-size: 0.8rem;
                        font-weight: 600;
                    }

                    .badge-information { background: rgba(102, 126, 234, 0.1); color: #667eea; }
                    .badge-annonce { background: rgba(132, 250, 176, 0.2); color: #10b981; }
                    .badge-evenement { background: rgba(250, 217, 97, 0.2); color: #f59e0b; }
                    .badge-resultat { background: rgba(240, 147, 251, 0.2); color: #d946ef; }
                    .badge-opportunite { background: rgba(79, 172, 254, 0.2); color: #3b82f6; }
                </style>

                <div class="row g-4">
                    <!-- Colonne de gauche - Formulaire de publication -->
                    <div class="col-lg-5">
                        <div class="publish-card">
                            <div class="publish-header">
                                <h5>
                                    <i class='bx bxs-megaphone'></i>
                                    Publier du contenu DER
                                </h5>
                                <p class="text-muted mt-2 mb-0">
                                    <i class='bx bx-info-circle me-1'></i>
                                    Les publications apparaissent directement dans les sections publiques
                                </p>
                            </div>
                            <div class="card-body p-4">
                                <form method="POST" action="<?= ROOT ?>/Homes/der_espace" enctype="multipart/form-data" id="publishForm">
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">
                                            <i class='bx bx-category me-1'></i>
                                            Type de publication
                                        </label>
                                        <select name="type" class="modern-select" required>
                                            <option value="information">📢 Information</option>
                                            <option value="annonce">🔔 Annonce</option>
                                            <option value="evenement">📅 Événement</option>
                                            <option value="resultat">🏆 Résultat</option>
                                            <option value="opportunite">💼 Opportunité</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">
                                            <i class='bx bx-calendar me-1'></i>
                                            Date de publication
                                        </label>
                                        <input type="date" name="date_publication" class="modern-input" value="<?= date('Y-m-d') ?>" required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">
                                            <i class='bx bx-heading me-1'></i>
                                            Titre
                                        </label>
                                        <input type="text" name="titre" class="modern-input" placeholder="Ex: Résultats du semestre 1" required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">
                                            <i class='bx bx-detail me-1'></i>
                                            Contenu
                                        </label>
                                        <textarea name="contenu" class="modern-input" rows="6" placeholder="Rédigez votre publication ici..." required></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">
                                            <i class='bx bx-paperclip me-1'></i>
                                            Fichiers joints
                                        </label>
                                        
                                        <!-- Zone de drag & drop -->
                                        <div class="file-upload-area" id="dropZone">
                                            <i class='bx bxs-cloud-upload'></i>
                                            <div class="upload-text">Glissez vos fichiers ici</div>
                                            <div class="upload-hint">ou cliquez pour sélectionner</div>
                                            <input type="file" name="fichiers[]" id="fileInput" style="display: none;" multiple>
                                        </div>

                                        <!-- Liste des fichiers sélectionnés -->
                                        <div class="file-list" id="fileList"></div>

                                        <small class="text-muted d-block mt-3">
                                            <i class='bx bx-info-circle me-1'></i>
                                            Formats acceptés: PDF, Word, Excel, PowerPoint, JPG, PNG (max 5 Mo par fichier)
                                        </small>
                                    </div>

                                    <button type="submit" name="save_der_post" class="btn-publish">
                                        <i class='bx bxs-send me-2'></i>
                                        Publier maintenant
                                    </button>
                                </form>

                                <a href="<?= ROOT ?>/Homes/departement" class="btn-outline-gradient mt-3">
                                    <i class='bx bx-show me-2'></i>
                                    Voir le rendu côté site
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne de droite - Liste des publications -->
                    <div class="col-lg-7">
                        <div class="publish-card">
                            <div class="publish-header">
                                <h5>
                                    <i class='bx bxs-news'></i>
                                    Publications DER
                                </h5>
                                <p class="text-muted mt-2 mb-0">
                                    <i class='bx bx-time me-1'></i>
                                    Dernières publications par catégorie
                                </p>
                            </div>
                            <div class="card-body p-4">
                                <div class="publications-container">
                                    <!-- Informations -->
                                    <?php if (!empty($informations ?? [])): ?>
                                    <div class="publication-category">
                                        <h6>
                                            <i class='bx bxs-info-circle'></i>
                                            Informations
                                            <span class="badge-type badge-information"><?= count($informations) ?></span>
                                        </h6>
                                        <?php foreach ($informations as $item): ?>
                                            <div class="publication-item">
                                                <div class="publication-title">
                                                    <i class='bx bxs-detail me-2' style="color: #667eea;"></i>
                                                    <?= htmlspecialchars($item['title'] ?? '') ?>
                                                </div>
                                                <span class="publication-date">
                                                    <i class='bx bx-calendar me-1'></i>
                                                    <?= htmlspecialchars($item['date'] ?? '') ?>
                                                </span>
                                                <p class="publication-content">
                                                    <?= nl2br(htmlspecialchars($item['content'] ?? '')) ?>
                                                </p>
                                                <?php if (!empty($item['files'])): ?>
                                                    <div class="publication-files">
                                                        <small class="text-muted d-block mb-2">
                                                            <i class='bx bxs-paperclip me-1'></i>
                                                            Fichiers joints (<?= count($item['files']) ?>)
                                                        </small>
                                                        <?php foreach ($item['files'] as $file): ?>
                                                            <a class="file-link" href="<?= htmlspecialchars($file['url'] ?? '#') ?>" download>
                                                                <i class='bx bxs-file'></i>
                                                                <?= htmlspecialchars($file['name'] ?? 'Document') ?>
                                                                <i class='bx bxs-download ms-1'></i>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Annonces -->
                                    <?php if (!empty($annonces ?? [])): ?>
                                    <div class="publication-category">
                                        <h6>
                                            <i class='bx bxs-megaphone'></i>
                                            Annonces
                                            <span class="badge-type badge-annonce"><?= count($annonces) ?></span>
                                        </h6>
                                        <?php foreach ($annonces as $item): ?>
                                            <div class="publication-item">
                                                <div class="publication-title">
                                                    <i class='bx bxs-megaphone me-2' style="color: #10b981;"></i>
                                                    <?= htmlspecialchars($item['title'] ?? '') ?>
                                                </div>
                                                <span class="publication-date">
                                                    <i class='bx bx-calendar me-1'></i>
                                                    <?= htmlspecialchars($item['date'] ?? '') ?>
                                                </span>
                                                <p class="publication-content">
                                                    <?= nl2br(htmlspecialchars($item['content'] ?? '')) ?>
                                                </p>
                                                <?php if (!empty($item['files'])): ?>
                                                    <div class="publication-files">
                                                        <small class="text-muted d-block mb-2">
                                                            <i class='bx bxs-paperclip me-1'></i>
                                                            Fichiers joints (<?= count($item['files']) ?>)
                                                        </small>
                                                        <?php foreach ($item['files'] as $file): ?>
                                                            <a class="file-link" href="<?= htmlspecialchars($file['url'] ?? '#') ?>" download>
                                                                <i class='bx bxs-file'></i>
                                                                <?= htmlspecialchars($file['name'] ?? 'Document') ?>
                                                                <i class='bx bxs-download ms-1'></i>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Événements -->
                                    <?php if (!empty($events ?? [])): ?>
                                    <div class="publication-category">
                                        <h6>
                                            <i class='bx bxs-calendar-star'></i>
                                            Événements
                                            <span class="badge-type badge-evenement"><?= count($events) ?></span>
                                        </h6>
                                        <?php foreach ($events as $item): ?>
                                            <div class="publication-item">
                                                <div class="publication-title">
                                                    <i class='bx bxs-calendar-star me-2' style="color: #f59e0b;"></i>
                                                    <?= htmlspecialchars($item['title'] ?? '') ?>
                                                </div>
                                                <span class="publication-date">
                                                    <i class='bx bx-calendar me-1'></i>
                                                    <?= htmlspecialchars($item['date'] ?? '') ?>
                                                </span>
                                                <p class="publication-content">
                                                    <?= nl2br(htmlspecialchars($item['content'] ?? '')) ?>
                                                </p>
                                                <?php if (!empty($item['files'])): ?>
                                                    <div class="publication-files">
                                                        <small class="text-muted d-block mb-2">
                                                            <i class='bx bxs-paperclip me-1'></i>
                                                            Fichiers joints (<?= count($item['files']) ?>)
                                                        </small>
                                                        <?php foreach ($item['files'] as $file): ?>
                                                            <a class="file-link" href="<?= htmlspecialchars($file['url'] ?? '#') ?>" download>
                                                                <i class='bx bxs-file'></i>
                                                                <?= htmlspecialchars($file['name'] ?? 'Document') ?>
                                                                <i class='bx bxs-download ms-1'></i>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Résultats -->
                                    <?php if (!empty($results ?? [])): ?>
                                    <div class="publication-category">
                                        <h6>
                                            <i class='bx bxs-trophy'></i>
                                            Résultats
                                            <span class="badge-type badge-resultat"><?= count($results) ?></span>
                                        </h6>
                                        <?php foreach ($results as $item): ?>
                                            <div class="publication-item">
                                                <div class="publication-title">
                                                    <i class='bx bxs-trophy me-2' style="color: #d946ef;"></i>
                                                    <?= htmlspecialchars($item['title'] ?? '') ?>
                                                </div>
                                                <span class="publication-date">
                                                    <i class='bx bx-calendar me-1'></i>
                                                    <?= htmlspecialchars($item['date'] ?? '') ?>
                                                </span>
                                                <p class="publication-content">
                                                    <?= nl2br(htmlspecialchars($item['content'] ?? '')) ?>
                                                </p>
                                                <?php if (!empty($item['files'])): ?>
                                                    <div class="publication-files">
                                                        <small class="text-muted d-block mb-2">
                                                            <i class='bx bxs-paperclip me-1'></i>
                                                            Fichiers joints (<?= count($item['files']) ?>)
                                                        </small>
                                                        <?php foreach ($item['files'] as $file): ?>
                                                            <a class="file-link" href="<?= htmlspecialchars($file['url'] ?? '#') ?>" download>
                                                                <i class='bx bxs-file'></i>
                                                                <?= htmlspecialchars($file['name'] ?? 'Document') ?>
                                                                <i class='bx bxs-download ms-1'></i>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Opportunités -->
                                    <?php if (!empty($opportunities ?? [])): ?>
                                    <div class="publication-category">
                                        <h6>
                                            <i class='bx bxs-briefcase'></i>
                                            Opportunités
                                            <span class="badge-type badge-opportunite"><?= count($opportunities) ?></span>
                                        </h6>
                                        <?php foreach ($opportunities as $item): ?>
                                            <div class="publication-item">
                                                <div class="publication-title">
                                                    <i class='bx bxs-briefcase me-2' style="color: #3b82f6;"></i>
                                                    <?= htmlspecialchars($item['title'] ?? '') ?>
                                                </div>
                                                <span class="publication-date">
                                                    <i class='bx bx-calendar me-1'></i>
                                                    <?= htmlspecialchars($item['date'] ?? '') ?>
                                                </span>
                                                <p class="publication-content">
                                                    <?= nl2br(htmlspecialchars($item['content'] ?? '')) ?>
                                                </p>
                                                <?php if (!empty($item['files'])): ?>
                                                    <div class="publication-files">
                                                        <small class="text-muted d-block mb-2">
                                                            <i class='bx bxs-paperclip me-1'></i>
                                                            Fichiers joints (<?= count($item['files']) ?>)
                                                        </small>
                                                        <?php foreach ($item['files'] as $file): ?>
                                                            <a class="file-link" href="<?= htmlspecialchars($file['url'] ?? '#') ?>" download>
                                                                <i class='bx bxs-file'></i>
                                                                <?= htmlspecialchars($file['name'] ?? 'Document') ?>
                                                                <i class='bx bxs-download ms-1'></i>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Message si aucune publication -->
                                    <?php if (empty($informations) && empty($annonces) && empty($events) && empty($results) && empty($opportunities)): ?>
                                    <div class="text-center py-5">
                                        <i class='bx bxs-folder-open' style="font-size: 64px; color: #cbd5e1;"></i>
                                        <p class="text-muted fs-5 mt-3 mb-0">Aucune publication pour le moment</p>
                                        <p class="text-muted">Commencez par créer votre première publication !</p>
                                    </div>
                                    <?php endif; ?>
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

<!-- Script pour la gestion des fichiers -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');
    const selectedFiles = [];
    const maxFileSize = 5 * 1024 * 1024; // 5 Mo
    const allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'image/jpeg',
        'image/png'
    ];

    // Clique sur la zone pour ouvrir le sélecteur de fichiers
    dropZone.addEventListener('click', function() {
        fileInput.click();
    });

    // Gestion du drag & drop
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    // Gestion de la sélection de fichiers
    fileInput.addEventListener('change', function(e) {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        for (let file of files) {
            // Vérification de la taille
            if (file.size > maxFileSize) {
                alert(`Le fichier ${file.name} dépasse la taille maximale de 5 Mo`);
                continue;
            }

            // Vérification du type (optionnel)
            if (!allowedTypes.includes(file.type) && !file.type.startsWith('image/')) {
                alert(`Le type du fichier ${file.name} n'est pas autorisé`);
                continue;
            }

            const alreadyExists = selectedFiles.some(existingFile => (
                existingFile.name === file.name &&
                existingFile.size === file.size &&
                existingFile.lastModified === file.lastModified
            ));

            if (alreadyExists) {
                continue;
            }

            selectedFiles.push(file);
        }

        syncFileInput();
        renderFileList();
    }

    function syncFileInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
    }

    function renderFileList() {
        fileList.innerHTML = '';

        if (selectedFiles.length === 0) {
            fileList.classList.remove('show');
            return;
        }

        fileList.classList.add('show');

        selectedFiles.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';

            let fileSize = (file.size / 1024).toFixed(2) + ' Ko';
            if (file.size > 1024 * 1024) {
                fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' Mo';
            }

            let fileIcon = 'bxs-file';
            if (file.type.includes('pdf')) fileIcon = 'bxs-file-pdf';
            else if (file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx')) fileIcon = 'bxs-file-doc';
            else if (file.type.includes('excel') || file.name.endsWith('.xls') || file.name.endsWith('.xlsx')) fileIcon = 'bxs-file-excel';
            else if (file.type.includes('presentation') || file.name.endsWith('.ppt') || file.name.endsWith('.pptx')) fileIcon = 'bxs-file-ppt';
            else if (file.type.includes('image')) fileIcon = 'bxs-file-image';

            fileItem.innerHTML = `
                <div class="file-info">
                    <i class='bx ${fileIcon}'></i>
                    <div>
                        <div class="file-name">${file.name}</div>
                        <div class="file-size">${fileSize}</div>
                    </div>
                </div>
                <i class='bx bx-x file-remove' data-file-index="${index}"></i>
            `;

            fileList.appendChild(fileItem);
        });
    }

    fileList.addEventListener('click', function(e) {
        const removeButton = e.target.closest('.file-remove');
        if (!removeButton) {
            return;
        }

        const fileIndex = Number(removeButton.dataset.fileIndex);
        if (Number.isNaN(fileIndex)) {
            return;
        }

        selectedFiles.splice(fileIndex, 1);
        syncFileInput();
        renderFileList();
    });
});
</script>

<?php $this->view('Partials/scripts'); ?>
</body>
</html>