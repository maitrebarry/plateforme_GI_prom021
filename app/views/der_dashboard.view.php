<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Dashboard DER']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php
$derStats = $derStats ?? [];
$latestPublications = $latestPublications ?? [];
?>
<style>
:root {
    --primary-color: #6366f1;
    --primary-hover: #4f46e5;
    --secondary-color: #94a3b8;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #0ea5e9;
    --teal-color: #14b8a6;
    --bg-light: #f1f5f9;
    --glass-bg: rgba(255, 255, 255, 0.8);
    --text-main: #0f172a;
    --text-muted: #64748b;
    --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --card-hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

body {
    background-color: var(--bg-light);
    color: var(--text-main);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.dashboard-body {
    background-color: var(--bg-light);
    min-height: 100vh;
    padding-bottom: 3rem;
}

.dashboard-body__content {
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.common-card {
    border: none;
    border-radius: 16px;
    box-shadow: var(--card-shadow);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    background: #ffffff;
    overflow: hidden;
    position: relative;
}

.common-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--card-hover-shadow);
}

.common-card .card-body {
    padding: 1.75rem;
    z-index: 1;
    position: relative;
}

.common-card p {
    color: var(--text-muted);
    font-weight: 700;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 0.75rem;
}

.common-card h4 {
    color: var(--text-main);
    font-weight: 800;
    font-size: 2rem;
    margin: 0;
}

/* Stat Icon Backdrop */
.stat-icon {
    position: absolute;
    right: 1.5rem;
    bottom: 1rem;
    font-size: 3rem;
    opacity: 0.1;
    transition: all 0.3s;
}

.common-card:hover .stat-icon {
    opacity: 0.2;
    transform: scale(1.1) rotate(-10deg);
}

.stat-card-total      { border-top: 5px solid var(--teal-color); }
.stat-card-annonces   { border-top: 5px solid var(--success-color); }
.stat-card-infos      { border-top: 5px solid var(--primary-color); }
.stat-card-evenements { border-top: 5px solid var(--warning-color); }
.stat-card-resultats  { border-top: 5px solid var(--info-color); }
.stat-card-opps       { border-top: 5px solid var(--danger-color); }
.stat-card-files      { border-top: 5px solid var(--secondary-color); }

.quick-links-bar {
    background: var(--glass-bg);
    backdrop-filter: blur(8px);
    padding: 2rem;
    border-radius: 20px;
    box-shadow: var(--card-shadow);
    margin: 2rem 0;
    border: 1px solid rgba(255, 255, 255, 0.5);
}

/* Hero matching admin */
.der-hero-banner {
    background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
    border-radius: 20px;
    padding: 2.5rem 3rem;
    color: #fff;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.der-hero-banner::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
}

.der-hero-banner::after {
    content: '';
    position: absolute;
    bottom: -60px; left: 30%;
    width: 300px; height: 300px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
}

.der-hero-banner h3, .der-hero-banner p {
    color: #fff;
    position: relative;
    z-index: 2;
}

.der-hero-banner p {
    opacity: 0.85;
    font-size: 0.95rem;
}

.publication-card {
    border: none;
    border-radius: 16px;
    background: #ffffff;
    box-shadow: var(--card-shadow);
    margin-bottom: 1.25rem;
    overflow: hidden;
    transition: all 0.3s;
}

.publication-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--card-hover-shadow);
}

.publication-card .pub-body {
    padding: 1.5rem;
}

.publication-meta {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    color: var(--text-muted);
    font-size: .875rem;
    margin-bottom: 10px;
}

.pub-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 14px;
    border-radius: 999px;
    background: #ede9fe;
    color: #5b21b6;
    font-weight: 700;
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.der-file-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 14px;
    border-radius: 999px;
    border: 1px solid #e2e8f0;
    color: var(--text-main);
    text-decoration: none;
    font-weight: 600;
    font-size: .875rem;
    transition: 0.25s;
}

.der-file-link:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}

.admin-table-card h5 {
    font-weight: 800;
    font-size: 1.25rem;
    color: var(--text-main);
    padding-bottom: 1rem;
    border-bottom: 2px solid #f1f5f9;
    margin-bottom: 1.5rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    font-weight: 700;
    border-radius: 12px;
    transition: all 0.3s;
    border: none;
}

.btn:hover {
    transform: translateY(-3px);
    filter: brightness(1.1);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; }
.btn-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
.btn-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
.btn-danger  { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
.btn-secondary { background: #64748b; color: white; }

.btn-outline-primary {
    border: 2px solid var(--primary-color);
    background: transparent;
    color: var(--primary-color);
}
.btn-outline-secondary {
    border: 2px solid #94a3b8;
    background: transparent;
    color: #475569;
}
</style>

<?php
$projectPlatformStats = $projectPlatformStats ?? [];
$mostFollowedProjects = $mostFollowedProjects ?? [];
?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

            <div class="dashboard-body__content p-4">

                <!-- Hero Banner (same style as admin) -->
                <div class="der-hero-banner mb-4">
                    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap" style="position:relative;z-index:2;">
                        <div>
                            <h3 class="fw-800 mb-1" style="font-size:1.8rem;letter-spacing:-0.5px;">Dashboard DER</h3>
                            <p class="mb-0">Suivi dynamique des publications, annonces et contenus du département.</p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="<?= ROOT ?>/Homes/der_espace#create-der-post" class="btn btn-primary">
                                <i class="bx bx-plus"></i> Nouvelle publication
                            </a>
                            <a href="<?= ROOT ?>/Homes/der_espace" class="btn btn-success">
                                <i class="bx bx-list-ul"></i> Gérer les publications
                            </a>
                            <a href="<?= ROOT ?>/Homes/index" class="btn btn-secondary">
                                <i class="bx bx-link-external"></i> Voir le site
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards (same as admin) -->
                <div class="row gy-4 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card stat-card-total">
                            <div class="card-body">
                                <p class="mb-1">Total publications</p>
                                <h4><?= (int) ($derStats['total'] ?? 0) ?></h4>
                                <div class="stat-icon">📋</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card stat-card-annonces">
                            <div class="card-body">
                                <p class="mb-1">Annonces</p>
                                <h4><?= (int) ($derStats['annonces'] ?? 0) ?></h4>
                                <div class="stat-icon">📢</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card stat-card-infos">
                            <div class="card-body">
                                <p class="mb-1">Informations</p>
                                <h4><?= (int) ($derStats['informations'] ?? 0) ?></h4>
                                <div class="stat-icon">ℹ️</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card stat-card-evenements">
                            <div class="card-body">
                                <p class="mb-1">Événements</p>
                                <h4><?= (int) ($derStats['evenements'] ?? 0) ?></h4>
                                <div class="stat-icon">📅</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card stat-card-resultats">
                            <div class="card-body">
                                <p class="mb-1">Résultats</p>
                                <h4><?= (int) ($derStats['resultats'] ?? 0) ?></h4>
                                <div class="stat-icon">📊</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card stat-card-opps">
                            <div class="card-body">
                                <p class="mb-1">Opportunités</p>
                                <h4><?= (int) ($derStats['opportunites'] ?? 0) ?></h4>
                                <div class="stat-icon">💼</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card common-card stat-card-files">
                            <div class="card-body">
                                <p class="mb-1">Fichiers joints</p>
                                <h4><?= (int) ($derStats['files'] ?? 0) ?></h4>
                                <div class="stat-icon">📎</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links Bar (same as admin) -->
                <div class="quick-links-bar d-flex flex-wrap gap-3 align-items-center justify-content-between">
                    <span class="fw-800" style="font-size:1rem;">Accès rapide :</span>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?= ROOT ?>/Homes/der_espace#create-der-post" class="btn btn-primary btn-sm">
                            <i class="bx bx-plus-circle me-1"></i>Ajouter
                        </a>
                        <a href="<?= ROOT ?>/Homes/der_espace?filter=annonce" class="btn btn-success btn-sm">
                            <i class="bx bx-megaphone me-1"></i>Annonces
                        </a>
                        <a href="<?= ROOT ?>/Homes/der_espace?filter=evenement" class="btn btn-warning btn-sm">
                            <i class="bx bx-calendar-event me-1"></i>Événements
                        </a>
                        <a href="<?= ROOT ?>/Homes/der_espace?filter=resultat" class="btn btn-info btn-sm" style="background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff">
                            <i class="bx bx-bar-chart-alt-2 me-1"></i>Résultats
                        </a>
                    </div>
                </div>

                <!-- Publications Table Card (same style as admin leaderboard) -->
                <div class="card common-card admin-table-card" style="border-radius:20px;margin-top:2rem;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-3">
                            <h5>Dernières publications DER</h5>
                            <a href="<?= ROOT ?>/Homes/der_espace" class="btn btn-primary btn-sm">Tout gérer</a>
                        </div>

                        <?php if (!empty($latestPublications)): ?>
                            <?php foreach ($latestPublications as $post): ?>
                                <div class="publication-card">
                                    <div class="pub-body">
                                        <div class="d-flex justify-content-between gap-3 flex-wrap mb-2 align-items-start">
                                            <h6 class="mb-0 fw-800" style="font-size:1rem;"><?= htmlspecialchars((string) ($post->titre ?? 'Publication')) ?></h6>
                                            <span class="pub-badge"><?= htmlspecialchars((string) ($post->type ?? 'publication')) ?></span>
                                        </div>
                                        <div class="publication-meta">
                                            <span><i class="bx bx-calendar me-1"></i><?= htmlspecialchars((string) ($post->publication_date ?? '')) ?></span>
                                            <span><i class="bx bx-user me-1"></i><?= htmlspecialchars((string) ($post->author_name ?? 'Responsable DER')) ?></span>
                                        </div>
                                        <p class="mb-3" style="color:#475569;font-size:.92rem;line-height:1.65;"><?= nl2br(htmlspecialchars((string) ($post->contenu ?? ''))) ?></p>
                                        <?php if (!empty($post->files ?? [])): ?>
                                            <div class="d-flex gap-2 flex-wrap mb-3">
                                                <?php foreach (($post->files ?? []) as $file): ?>
                                                    <?php $relativePath = ltrim(str_replace('\\', '/', (string) ($file->file_path ?? '')), '/'); ?>
                                                    <a class="der-file-link" href="<?= ROOT . '/' . $relativePath ?>" target="_blank" rel="noopener">
                                                        <i class='bx bx-paperclip'></i>
                                                        <?= htmlspecialchars((string) ($file->original_name ?? 'Fichier')) ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <a href="<?= ROOT ?>/Homes/der_publication_detail/<?= (int) ($post->id ?? 0) ?>" class="btn btn-secondary btn-sm">Voir détail</a>
                                            <a href="<?= ROOT ?>/Homes/der_espace?edit=<?= (int) ($post->id ?? 0) ?>#manage-der-posts" class="btn btn-success btn-sm">Modifier</a>
                                            <form method="POST" action="<?= ROOT ?>/Homes/der_espace" class="d-inline">
                                                <input type="hidden" name="post_id" value="<?= (int) ($post->id ?? 0) ?>">
                                                <button type="submit" name="delete_der_post" class="btn btn-danger btn-sm" onclick="return confirm('Archiver cette publication DER ?');">Archiver</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-5 text-muted">
                                <i class="bx bx-file-blank" style="font-size:3rem;opacity:.3;display:block;margin-bottom:1rem;"></i>
                                <span class="fw-600">Aucune publication DER disponible pour le moment.</span>
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
