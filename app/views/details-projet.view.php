<?php
$this->view('Partials/head', ['pageTitle' => $project->title ?? 'Détail projet']);

$avgRating = (float) ($reviewSummary->average_rating ?? 0);
$totalReviews = (int) ($reviewSummary->total_reviews ?? 0);
$likesCount = (int) ($likesCount ?? 0);
$currentUserId = (int) ($currentUserId ?? 0);
$ownerId = (int) ($ownerId ?? 0);
$userHasLiked = !empty($userHasLiked);
$followersCount = (int) ($followersCount ?? 0);
$userIsFollowing = !empty($userIsFollowing);
$isOwner = $currentUserId === $ownerId;
$isAuthenticated = $currentUserId > 0;
$csrf = class_exists('Csrf') ? Csrf::token() : (string) ($_SESSION['csrf_token'] ?? '');

$imgUrls = [];
foreach (($images ?? []) as $im) {
    $imgUrls[] = ROOT_IMG . '/uploads/projects/images/' . rawurlencode((string) ($im->image ?? ''));
}
$ownerName = trim((string) ($project->prenom ?? '') . ' ' . (string) ($project->nom ?? ''));
if ($ownerName === '') { $ownerName = 'Porteur du projet'; }
$ownerInitial = strtoupper(mb_substr($ownerName, 0, 1));
$techs = array_values(array_filter(array_map('trim', explode(',', (string) ($project->technologies ?? '')))));
?>

<body class="public-site public-detail">
<?php
$this->view('Partials/global-shell');
$this->view('Partials/mobile-menu');
$this->view('Partials/header');
$this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]);
?>

<main>
    <style>
        .pd-wrap { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 16px; }
        .pd-shell { padding: 22px 0 64px; background: var(--ds-bg); }
        .pd-card { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); padding: 22px; box-shadow: var(--ds-shadow-sm); }
        .pd-card + .pd-card, .pd-side + .pd-side { margin-top: 18px; }

        .pd-back { display: inline-flex; align-items: center; gap: 6px; color: var(--ds-muted); font-weight: 600; font-size: .88rem; text-decoration: none; margin-bottom: 14px; transition: color var(--ds-transition); }
        .pd-back:hover { color: var(--ds-brand-600); }

        .pd-title { font-family: var(--ds-font-heading); font-size: 1.55rem; font-weight: 800; line-height: 1.2; color: var(--ds-ink-strong); margin: 0 0 12px; overflow-wrap: break-word; }
        .pd-meta { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 18px; }
        .pd-chip { display: inline-flex; align-items: center; gap: 6px; font-size: .8rem; font-weight: 600; padding: 6px 12px; border-radius: var(--ds-radius-pill); background: var(--ds-surface-2); color: var(--ds-muted); }
        .pd-chip i { font-size: 1rem; }
        .pd-chip--cat { background: var(--ds-brand-50); color: var(--ds-brand-700); }
        .pd-chip--rating { background: var(--ds-accent-soft); color: #8a6310; }
        .pd-chip--likes { background: var(--ds-danger-soft); color: #a3322e; }

        /* Galerie */
        .pd-gallery__main { position: relative; border-radius: var(--ds-radius); overflow: hidden; background: var(--ds-surface-2); }
        .pd-gallery__main img { width: 100%; height: 300px; object-fit: cover; display: block; cursor: zoom-in; }
        .pd-gallery__count { position: absolute; top: 12px; right: 12px; display: inline-flex; align-items: center; gap: 5px; background: rgba(15,23,42,.62); color: #fff; font-size: .76rem; font-weight: 600; padding: 5px 11px; border-radius: var(--ds-radius-pill); }
        .pd-thumbs { display: flex; gap: 8px; margin-top: 10px; overflow-x: auto; padding-bottom: 4px; scrollbar-width: thin; }
        .pd-thumb { flex-shrink: 0; width: 72px; height: 56px; border-radius: 10px; overflow: hidden; border: 2px solid transparent; padding: 0; cursor: pointer; background: none; }
        .pd-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .pd-thumb.is-active { border-color: var(--ds-brand-600); }

        .pd-techs { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 18px; }
        .pd-tech { display: inline-flex; align-items: center; gap: 5px; font-size: .76rem; font-weight: 600; background: var(--ds-surface-2); color: var(--ds-ink); border: 1px solid var(--ds-border); padding: 5px 11px; border-radius: var(--ds-radius-pill); }
        .pd-tech i { color: var(--ds-brand-500); }

        .pd-desc { color: var(--ds-ink); line-height: 1.75; font-size: .98rem; margin-top: 18px; }

        .pd-section-title { display: flex; align-items: center; gap: 9px; font-family: var(--ds-font-heading); font-size: 1.18rem; font-weight: 800; color: var(--ds-ink-strong); margin: 0 0 16px; }
        .pd-section-title i { color: var(--ds-brand-600); font-size: 1.3rem; }

        .video-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: var(--ds-radius); }
        .video-container iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }

        /* Avis */
        .pd-rating-summary { display: flex; align-items: center; gap: 14px; margin-bottom: 18px; padding-bottom: 16px; border-bottom: 1px solid var(--ds-border); }
        .pd-rating-big { font-family: var(--ds-font-heading); font-size: 2.4rem; font-weight: 800; color: var(--ds-ink-strong); line-height: 1; }
        .pd-stars { display: inline-flex; gap: 2px; color: var(--ds-accent); font-size: 1.1rem; }
        .pd-stars .bx-star { color: var(--ds-border-strong); }
        .pd-rating-meta { color: var(--ds-muted); font-size: .85rem; }
        .pd-review { padding: 16px; border: 1px solid var(--ds-border); border-radius: var(--ds-radius); background: var(--ds-surface); }
        .pd-review + .pd-review { margin-top: 12px; }
        .pd-review__head { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; margin-bottom: 6px; }
        .pd-review__who { display: inline-flex; align-items: center; gap: 8px; font-weight: 700; color: var(--ds-ink-strong); }
        .pd-review__date { color: var(--ds-muted-soft); font-size: .78rem; }
        .pd-review__text { color: var(--ds-ink); line-height: 1.6; font-size: .92rem; }
        .pd-empty-box { text-align: center; padding: 24px; border: 1px dashed var(--ds-border-strong); border-radius: var(--ds-radius); color: var(--ds-muted); }

        /* Avatar pastille */
        .pd-ava { width: 32px; height: 32px; border-radius: 50%; background: var(--ds-brand-100); color: var(--ds-brand-700); display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: .85rem; flex-shrink: 0; }

        /* Formulaires */
        .pd-label { display: block; font-weight: 600; font-size: .86rem; color: var(--ds-ink); margin-bottom: 6px; }
        .pd-input, .pd-textarea, .pd-select { width: 100%; border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius); padding: 11px 14px; font-size: .94rem; color: var(--ds-ink); background: var(--ds-surface); font-family: var(--ds-font-sans); }
        .pd-input:focus, .pd-textarea:focus, .pd-select:focus { outline: none; border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); }
        .pd-textarea { resize: vertical; min-height: 90px; }
        .pd-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: var(--ds-brand-600); color: #fff; font-weight: 700; font-size: .92rem; padding: 11px 20px; border: 0; border-radius: var(--ds-radius-pill); cursor: pointer; text-decoration: none; transition: all var(--ds-transition); }
        .pd-btn:hover { background: var(--ds-brand-700); color: #fff; transform: translateY(-1px); }
        .pd-btn--block { width: 100%; }
        .pd-contact__row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px; }
        @media (max-width: 520px) { .pd-contact__row { grid-template-columns: 1fr; } }
        .pd-hp { position: absolute !important; left: -9999px !important; width: 1px; height: 1px; opacity: 0; pointer-events: none; }
        .pd-btn--ghost { background: var(--ds-surface-2); color: var(--ds-brand-700); border: 1px solid var(--ds-border); }
        .pd-btn--ghost:hover { background: var(--ds-brand-50); color: var(--ds-brand-800); }

        /* Etoiles interactives (note) */
        .pd-rate { display: inline-flex; flex-direction: row-reverse; }
        .pd-rate input { position: absolute; opacity: 0; pointer-events: none; }
        .pd-rate label { font-size: 1.7rem; color: var(--ds-border-strong); cursor: pointer; padding: 0 2px; transition: color .15s; }
        .pd-rate label:hover, .pd-rate label:hover ~ label, .pd-rate input:checked ~ label { color: var(--ds-accent); }

        /* Discussion */
        .pd-msg { padding: 12px 15px; border: 1px solid var(--ds-border); border-radius: 14px; background: var(--ds-surface); max-width: 88%; }
        .pd-msg + .pd-msg { margin-top: 10px; }
        .pd-msg.is-mine { margin-left: auto; background: var(--ds-brand-50); border-color: var(--ds-brand-200); }
        .pd-msg__head { font-size: .76rem; color: var(--ds-muted); margin-bottom: 4px; }
        .pd-msg__text { color: var(--ds-ink); font-size: .92rem; line-height: 1.5; }
        .pd-thread { display: flex; flex-direction: column; margin-bottom: 16px; max-height: 360px; overflow-y: auto; }

        /* Sidebar */
        .pd-side { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); padding: 20px; box-shadow: var(--ds-shadow-sm); }
        .pd-owner-card { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
        .pd-owner-avatar { width: 64px; height: 64px; border-radius: 50%; overflow: hidden; background: linear-gradient(135deg, var(--ds-brand-500), var(--ds-brand-700)); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800; font-size: 1.6rem; flex-shrink: 0; }
        .pd-owner-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .pd-owner-name { font-family: var(--ds-font-heading); font-weight: 800; color: var(--ds-ink-strong); font-size: 1.05rem; }
        .pd-owner-sub { color: var(--ds-muted); font-size: .82rem; display: flex; align-items: center; gap: 5px; margin-top: 2px; }
        .pd-contact-row { display: flex; align-items: center; gap: 9px; color: var(--ds-ink); font-size: .9rem; padding: 8px 0; border-top: 1px solid var(--ds-border); text-decoration: none; }
        .pd-contact-row i { color: var(--ds-brand-600); font-size: 1.15rem; }
        a.pd-contact-row:hover { color: var(--ds-brand-700); }
        .pd-social { display: flex; gap: 8px; margin-top: 12px; }
        .pd-social a { display: inline-flex; align-items: center; gap: 6px; font-size: .82rem; font-weight: 600; padding: 8px 13px; border-radius: var(--ds-radius-pill); background: var(--ds-surface-2); color: var(--ds-ink); border: 1px solid var(--ds-border); text-decoration: none; }
        .pd-social a:hover { border-color: var(--ds-brand-300); color: var(--ds-brand-700); }

        .pd-like-btn { width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 13px; border: 1px solid var(--ds-danger); border-radius: var(--ds-radius-pill); background: var(--ds-danger-soft); color: #a3322e; font-weight: 700; font-size: .95rem; cursor: pointer; transition: all var(--ds-transition); }
        .pd-like-btn:hover { transform: translateY(-1px); box-shadow: var(--ds-shadow); }
        .pd-like-btn.is-active { background: var(--ds-danger); color: #fff; }
        .pd-follow-btn { width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 13px; margin-top: 9px; border: 1px solid var(--ds-brand-300); border-radius: var(--ds-radius-pill); background: var(--ds-brand-50); color: var(--ds-brand-700); font-weight: 700; font-size: .95rem; cursor: pointer; text-decoration: none; transition: all var(--ds-transition); }
        .pd-follow-btn:hover { transform: translateY(-1px); box-shadow: var(--ds-shadow); color: var(--ds-brand-700); }
        .pd-follow-btn.is-active { background: var(--ds-brand-600); color: #fff; border-color: var(--ds-brand-600); }
        .pd-follow-btn.is-active:hover { color: #fff; }
        .pd-engage-stats { display: flex; gap: 10px; margin-top: 12px; }
        .pd-engage-stats .pd-chip { flex: 1; justify-content: center; }
        .pd-share-btn { width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px; margin-top: 12px; border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius-pill); background: var(--ds-surface); color: var(--ds-ink); font-weight: 700; font-size: .92rem; cursor: pointer; transition: all var(--ds-transition); }
        .pd-share-btn:hover { background: var(--ds-surface-2); border-color: var(--ds-brand-300); color: var(--ds-brand-700); }
        .pd-share-btn.is-done { background: #e4f3ea; border-color: #1f8a4d; color: #11703a; }

        /* Barre d'actions rapides (en haut de la fiche) */
        .pd-quickbar { display: flex; gap: 8px; margin: 2px 0 18px; }
        .pd-qbtn { flex: 1 1 0; min-width: 0; white-space: nowrap; display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 11px 8px; border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius-pill); background: var(--ds-surface); color: var(--ds-ink); font-weight: 700; font-size: .86rem; cursor: pointer; text-decoration: none; transition: all var(--ds-transition); }
        .pd-qbtn i { font-size: 1.1rem; flex-shrink: 0; }
        .pd-qbtn:hover { border-color: var(--ds-brand-300); color: var(--ds-brand-700); background: var(--ds-surface-2); }
        .pd-qbtn__count { display: inline-flex; align-items: center; justify-content: center; min-width: 22px; height: 20px; padding: 0 6px; border-radius: var(--ds-radius-pill); background: var(--ds-surface-2); color: var(--ds-muted); font-size: .76rem; font-weight: 800; }
        .pd-qbtn.is-active .pd-qbtn__count { background: rgba(255,255,255,.25); color: #fff; }
        .pd-qbtn--like.is-active { background: var(--ds-danger); border-color: var(--ds-danger); color: #fff; }
        .pd-qbtn--like.is-active:hover { color: #fff; }
        .pd-qbtn--follow.is-active { background: var(--ds-brand-600); border-color: var(--ds-brand-600); color: #fff; }
        .pd-qbtn--follow.is-active:hover { color: #fff; }
        .pd-qbtn.is-loading { opacity: .55; pointer-events: none; }
        @media (min-width: 992px) { .pd-quickbar { display: none; } }

        /* Assistant IA */
        .pd-ai-chat { display: flex; flex-direction: column; gap: 10px; min-height: 160px; max-height: 320px; overflow-y: auto; padding: 4px; }
        .pd-ai-bubble { padding: 11px 15px; border-radius: 14px; line-height: 1.5; font-size: .9rem; white-space: pre-wrap; max-width: 92%; }
        .pd-ai-bubble.assistant { background: var(--ds-surface-2); border: 1px solid var(--ds-border); color: var(--ds-ink); margin-right: auto; }
        .pd-ai-bubble.user { background: var(--ds-brand-600); color: #fff; margin-left: auto; }
        .pd-ai-suggestions { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 12px; }
        .pd-ai-chip { border: 1px solid var(--ds-border); background: var(--ds-surface); color: var(--ds-brand-700); border-radius: var(--ds-radius-pill); padding: 7px 13px; font-weight: 600; font-size: .8rem; cursor: pointer; transition: all var(--ds-transition); }
        .pd-ai-chip:hover { background: var(--ds-brand-50); border-color: var(--ds-brand-300); }
        .pd-ai-input { width: 100%; border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius); padding: 11px 14px; min-height: 80px; font-size: .92rem; resize: vertical; color: var(--ds-ink); background: var(--ds-surface); font-family: var(--ds-font-sans); margin-top: 12px; }
        .pd-ai-input:focus { outline: none; border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); }

        /* Fichiers */
        .pd-file { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: 1px solid var(--ds-border); border-radius: var(--ds-radius); color: var(--ds-ink); text-decoration: none; font-size: .88rem; transition: all var(--ds-transition); }
        .pd-file + .pd-file { margin-top: 8px; }
        .pd-file i { color: var(--ds-brand-600); font-size: 1.3rem; }
        .pd-file:hover { border-color: var(--ds-brand-300); color: var(--ds-brand-700); transform: translateX(3px); }

        /* Projets liés */
        .pd-related-card { display: block; padding: 12px; border: 1px solid var(--ds-border); border-radius: var(--ds-radius); text-decoration: none; transition: all var(--ds-transition); }
        .pd-related-card + .pd-related-card { margin-top: 10px; }
        .pd-related-card:hover { border-color: var(--ds-brand-200); transform: translateX(3px); box-shadow: var(--ds-shadow); }
        .pd-related-card strong { display: block; color: var(--ds-ink-strong); font-size: .92rem; font-family: var(--ds-font-heading); }
        .pd-related-card span { color: var(--ds-muted); font-size: .78rem; }

        @media (min-width: 992px) {
            .pd-title { font-size: 2rem; }
            .pd-gallery__main img { height: 380px; }
            .pd-sticky { position: sticky; top: 88px; }
        }
    </style>

    <section class="pd-shell">
        <div class="pd-wrap">
            <a href="<?= ROOT ?>/Homes/projects" class="pd-back"><i class='bx bx-left-arrow-alt'></i> Retour aux projets</a>
            <div class="row g-4">
                <!-- ===== Colonne principale ===== -->
                <div class="col-lg-8">
                    <div class="pd-card">
                        <h1 class="pd-title"><?= htmlspecialchars($project->title ?? 'Projet') ?></h1>
                        <div class="pd-meta">
                            <span class="pd-chip pd-chip--cat"><i class='bx bx-category'></i><?= htmlspecialchars($project->categorie ?? 'Sans catégorie') ?></span>
                            <span class="pd-chip"><i class='bx bx-time-five'></i><?= htmlspecialchars($this->temps_relatif($project->created_at ?? date('Y-m-d H:i:s'))) ?></span>
                            <span class="pd-chip pd-chip--rating"><i class='bx bxs-star'></i><?= number_format($avgRating, 1) ?>/5 · <?= $totalReviews ?> avis</span>
                            <span class="pd-chip pd-chip--likes"><i class='bx bxs-heart'></i><span data-like-count><?= $likesCount ?></span> j'aime</span>
                        </div>

                        <div class="pd-quickbar">
                            <?php if ($isAuthenticated && !$isOwner): ?>
                                <button type="button" class="pd-qbtn pd-qbtn--like <?= $userHasLiked ? 'is-active' : '' ?>" data-async="like" data-like-on="Aimé" data-like-off="J'aime" aria-pressed="<?= $userHasLiked ? 'true' : 'false' ?>">
                                    <i class='bx <?= $userHasLiked ? 'bxs-heart' : 'bx-heart' ?>'></i>
                                    <span data-like-text><?= $userHasLiked ? 'Aimé' : "J'aime" ?></span>
                                    <span class="pd-qbtn__count" data-like-count><?= $likesCount ?></span>
                                </button>
                                <button type="button" class="pd-qbtn pd-qbtn--follow <?= $userIsFollowing ? 'is-active' : '' ?>" data-async="follow" data-follow-on="Abonné" data-follow-off="Suivre" aria-pressed="<?= $userIsFollowing ? 'true' : 'false' ?>">
                                    <i class='bx <?= $userIsFollowing ? 'bxs-bell' : 'bx-bell' ?>'></i>
                                    <span data-follow-text><?= $userIsFollowing ? 'Abonné' : 'Suivre' ?></span>
                                    <span class="pd-qbtn__count" data-follow-count><?= $followersCount ?></span>
                                </button>
                            <?php elseif (!$isAuthenticated): ?>
                                <a href="<?= ROOT ?>/Homes/login" class="pd-qbtn"><i class='bx bx-heart'></i> <span>J'aime</span> <span class="pd-qbtn__count" data-like-count><?= $likesCount ?></span></a>
                                <a href="<?= ROOT ?>/Homes/login" class="pd-qbtn"><i class='bx bx-bell'></i> <span>Suivre</span> <span class="pd-qbtn__count" data-follow-count><?= $followersCount ?></span></a>
                            <?php endif; ?>
                            <button type="button" class="pd-qbtn pd-qbtn--share" data-share-btn><i class='bx bx-share-alt'></i> <span>Partager</span></button>
                        </div>

                        <?php if (!empty($imgUrls)): ?>
                            <div class="pd-gallery">
                                <div class="pd-gallery__main">
                                    <img id="pdMainImg" src="<?= htmlspecialchars($imgUrls[0]) ?>" alt="<?= htmlspecialchars($project->title ?? 'Projet') ?>" onclick="openLightbox(this.src)">
                                    <?php if (count($imgUrls) > 1): ?><span class="pd-gallery__count"><i class='bx bx-images'></i> <?= count($imgUrls) ?></span><?php endif; ?>
                                </div>
                                <?php if (count($imgUrls) > 1): ?>
                                    <div class="pd-thumbs">
                                        <?php foreach ($imgUrls as $i => $u): ?>
                                            <button type="button" class="pd-thumb <?= $i === 0 ? 'is-active' : '' ?>" onclick="pdSwap(this, '<?= htmlspecialchars($u, ENT_QUOTES) ?>')"><img src="<?= htmlspecialchars($u) ?>" alt="" loading="lazy"></button>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($techs)): ?>
                            <div class="pd-techs">
                                <?php foreach ($techs as $tech): ?>
                                    <span class="pd-tech"><i class='bx bx-code-alt'></i><?= htmlspecialchars($tech) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="pd-desc"><?= nl2br(htmlspecialchars((string) ($project->description ?? ''), ENT_QUOTES, 'UTF-8')) ?></div>
                    </div>

                    <?php if (!empty($project->video) && ($videoUrl = $this->youtube_embed($project->video))): ?>
                        <div class="pd-card">
                            <h3 class="pd-section-title"><i class='bx bx-movie-play'></i> Vidéo de démonstration</h3>
                            <div class="video-container"><iframe src="<?= htmlspecialchars($videoUrl) ?>" title="Vidéo du projet" allowfullscreen></iframe></div>
                        </div>
                    <?php endif; ?>

                    <!-- Avis -->
                    <div class="pd-card">
                        <h3 class="pd-section-title"><i class='bx bxs-star'></i> Avis et notation</h3>
                        <div class="pd-rating-summary">
                            <div class="pd-rating-big"><?= number_format($avgRating, 1) ?></div>
                            <div>
                                <div class="pd-stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class='bx <?= $i <= round($avgRating) ? 'bxs-star' : 'bx-star' ?>'></i>
                                    <?php endfor; ?>
                                </div>
                                <div class="pd-rating-meta"><?= $totalReviews ?> avis au total</div>
                            </div>
                        </div>

                        <?php if ($isAuthenticated && !$isOwner): ?>
                            <form method="post" class="mb-4">
                                <input type="hidden" name="action" value="submit_review">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                <label class="pd-label">Votre note</label>
                                <div class="pd-rate" role="radiogroup" aria-label="Note">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" name="rating" id="rate<?= $i ?>" value="<?= $i ?>" required>
                                        <label for="rate<?= $i ?>" title="<?= $i ?> étoile(s)"><i class='bx bxs-star'></i></label>
                                    <?php endfor; ?>
                                </div>
                                <label class="pd-label mt-2">Votre avis</label>
                                <textarea name="review" class="pd-textarea" placeholder="Partagez votre retour sur ce projet…"></textarea>
                                <button type="submit" class="pd-btn mt-3"><i class='bx bx-send'></i> Publier mon avis</button>
                            </form>
                        <?php elseif (!$isAuthenticated): ?>
                            <a href="<?= ROOT ?>/Homes/login" class="pd-btn pd-btn--ghost mb-3"><i class='bx bx-log-in'></i> Connectez-vous pour laisser un avis</a>
                        <?php endif; ?>

                        <?php if (!empty($reviews)): ?>
                            <?php foreach ($reviews as $review): ?>
                                <?php $rName = trim(($review->prenom ?? '') . ' ' . ($review->nom ?? '')); if ($rName === '') { $rName = 'Utilisateur'; } ?>
                                <div class="pd-review">
                                    <div class="pd-review__head">
                                        <span class="pd-review__who"><span class="pd-ava"><?= strtoupper(htmlspecialchars(mb_substr($rName, 0, 1))) ?></span><?= htmlspecialchars($rName) ?></span>
                                        <span class="pd-stars">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class='bx <?= $i <= (int) ($review->rating ?? 0) ? 'bxs-star' : 'bx-star' ?>'></i>
                                            <?php endfor; ?>
                                        </span>
                                    </div>
                                    <div class="pd-review__date"><?= htmlspecialchars((string) ($review->created_at ?? '')) ?></div>
                                    <?php if (!empty($review->review)): ?><div class="pd-review__text mt-1"><?= nl2br(htmlspecialchars((string) $review->review)) ?></div><?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="pd-empty-box"><i class='bx bx-message-square-dots'></i> Aucun avis pour le moment. Soyez le premier à donner le vôtre !</div>
                        <?php endif; ?>
                    </div>

                    <!-- Discussion -->
                    <div class="pd-card" id="pd-discussion">
                        <h3 class="pd-section-title"><i class='bx bx-message-dots'></i> Discussion avec le porteur</h3>
                        <?php if ($isAuthenticated && !$isOwner): ?>
                            <div class="pd-thread">
                                <?php if (!empty($conversation)): ?>
                                    <?php foreach ($conversation as $message): ?>
                                        <?php $mine = (int) ($message->sender_id ?? 0) === $currentUserId; ?>
                                        <div class="pd-msg <?= $mine ? 'is-mine' : '' ?>">
                                            <div class="pd-msg__head"><?= htmlspecialchars(trim(($message->sender_prenom ?? '') . ' ' . ($message->sender_nom ?? ''))) ?> · <?= htmlspecialchars((string) ($message->created_at ?? '')) ?></div>
                                            <div class="pd-msg__text"><?= nl2br(htmlspecialchars((string) ($message->message ?? ''))) ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="pd-empty-box">Démarrez la conversation avec le porteur du projet.</div>
                                <?php endif; ?>
                            </div>
                            <form method="post">
                                <input type="hidden" name="action" value="send_message">
                                <input type="hidden" name="receiver_id" value="<?= $ownerId ?>">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                <textarea name="message" id="pdMessageInput" class="pd-textarea" placeholder="Écrivez votre message au porteur…" required></textarea>
                                <button type="submit" class="pd-btn mt-3"><i class='bx bx-paper-plane'></i> Envoyer le message</button>
                            </form>
                        <?php elseif (!$isAuthenticated): ?>
                            <div class="pd-empty-box"><i class='bx bx-lock-alt'></i> Connectez-vous pour discuter avec le porteur.</div>
                            <a href="<?= ROOT ?>/Homes/login" class="pd-btn mt-3"><i class='bx bx-log-in'></i> Se connecter</a>
                        <?php else: ?>
                            <div class="pd-empty-box"><i class='bx bx-crown'></i> Vous êtes le porteur de ce projet. Les visiteurs peuvent vous contacter ici.</div>
                        <?php endif; ?>
                    </div>

                    <?php if (!$isOwner): ?>
                        <?php $prefillName = $isAuthenticated ? trim((string) (($_SESSION['prenom'] ?? '') . ' ' . ($_SESSION['nom'] ?? ''))) : ''; ?>
                        <!-- Contact public (alimente la boîte de l'administration) -->
                        <div class="pd-card">
                            <h3 class="pd-section-title"><i class='bx bx-envelope'></i> Une question sur ce projet ?</h3>
                            <p class="pd-rating-meta mb-3" style="color:var(--ds-muted)">Envoyez un message à l'équipe NGAKODON — pas besoin de compte.</p>
                            <form method="post">
                                <input type="hidden" name="action" value="contact">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                <input type="text" name="contact_website" class="pd-hp" tabindex="-1" autocomplete="off" aria-hidden="true">
                                <div class="pd-contact__row">
                                    <input type="text" name="contact_nom" class="pd-input" placeholder="Votre nom" value="<?= htmlspecialchars($prefillName) ?>" maxlength="100" required>
                                    <input type="email" name="contact_email" class="pd-input" placeholder="Votre email" maxlength="150" required>
                                </div>
                                <textarea name="contact_message" class="pd-textarea" placeholder="Votre message…" maxlength="2000" required></textarea>
                                <button type="submit" class="pd-btn mt-3"><i class='bx bx-paper-plane'></i> Envoyer le message</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ===== Colonne latérale ===== -->
                <div class="col-lg-4">
                    <div class="pd-sticky">
                        <!-- Porteur + contact -->
                        <div class="pd-side">
                            <h3 class="pd-section-title"><i class='bx bx-user'></i> Le porteur</h3>
                            <div class="pd-owner-card">
                                <div class="pd-owner-avatar">
                                    <?php if (!empty($project->owner_image)): ?>
                                        <img src="<?= ROOT_IMG ?>/<?= htmlspecialchars(ltrim((string) $project->owner_image, '/')) ?>" alt="<?= htmlspecialchars($ownerName) ?>">
                                    <?php else: ?>
                                        <?= htmlspecialchars($ownerInitial) ?>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="pd-owner-name"><?= htmlspecialchars($ownerName) ?></div>
                                    <?php if (!empty($project->filiere)): ?><div class="pd-owner-sub"><i class='bx bx-graduation'></i><?= htmlspecialchars((string) $project->filiere) ?></div><?php endif; ?>
                                    <?php if (!empty($project->universite)): ?><div class="pd-owner-sub"><i class='bx bx-buildings'></i><?= htmlspecialchars((string) $project->universite) ?></div><?php endif; ?>
                                </div>
                            </div>
                            <?php if (!empty($project->email)): ?>
                                <a class="pd-contact-row" href="mailto:<?= htmlspecialchars((string) $project->email) ?>"><i class='bx bx-envelope'></i> <?= htmlspecialchars((string) $project->email) ?></a>
                            <?php endif; ?>
                            <?php if (!empty($project->contact)): ?>
                                <a class="pd-contact-row" href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', (string) $project->contact)) ?>"><i class='bx bx-phone'></i> <?= htmlspecialchars((string) $project->contact) ?></a>
                            <?php endif; ?>
                            <?php if (!empty($project->github) || !empty($project->linkedin)): ?>
                                <div class="pd-social">
                                    <?php if (!empty($project->github)): ?><a href="<?= htmlspecialchars($project->github) ?>" target="_blank" rel="noopener noreferrer"><i class='bx bxl-github'></i> GitHub</a><?php endif; ?>
                                    <?php if (!empty($project->linkedin)): ?><a href="<?= htmlspecialchars($project->linkedin) ?>" target="_blank" rel="noopener noreferrer"><i class='bx bxl-linkedin'></i> LinkedIn</a><?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($isAuthenticated && !$isOwner): ?>
                                <button type="button" class="pd-btn pd-btn--block mt-3" id="pdContactBtn"><i class='bx bx-message-rounded-dots'></i> Contacter le porteur</button>
                            <?php elseif (!$isAuthenticated): ?>
                                <a href="<?= ROOT ?>/Homes/login" class="pd-btn pd-btn--block mt-3"><i class='bx bx-message-rounded-dots'></i> Contacter le porteur</a>
                            <?php endif; ?>
                        </div>

                        <!-- Engagement -->
                        <div class="pd-side">
                            <h3 class="pd-section-title"><i class='bx bxs-heart'></i> Engagement</h3>
                            <?php if ($isAuthenticated): ?>
                                <form method="post">
                                    <input type="hidden" name="action" value="toggle_like">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                    <button type="submit" class="pd-like-btn <?= $userHasLiked ? 'is-active' : '' ?>" data-async="like" data-like-on="Je n'aime plus" data-like-off="J'aime ce projet">
                                        <i class='bx <?= $userHasLiked ? 'bxs-heart' : 'bx-heart' ?>'></i>
                                        <span data-like-text><?= $userHasLiked ? "Je n'aime plus" : "J'aime ce projet" ?></span> · <span data-like-count><?= $likesCount ?></span>
                                    </button>
                                </form>
                            <?php else: ?>
                                <a href="<?= ROOT ?>/Homes/login" class="pd-like-btn" style="text-decoration:none"><i class='bx bx-heart'></i> J'aime ce projet · <?= $likesCount ?></a>
                            <?php endif; ?>

                            <?php if ($isAuthenticated && !$isOwner): ?>
                                <form method="post">
                                    <input type="hidden" name="action" value="toggle_follow">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                    <button type="submit" class="pd-follow-btn <?= $userIsFollowing ? 'is-active' : '' ?>" data-async="follow" data-follow-on="Abonné" data-follow-off="Suivre ce projet">
                                        <i class='bx <?= $userIsFollowing ? 'bxs-bell' : 'bx-bell' ?>'></i>
                                        <span data-follow-text><?= $userIsFollowing ? 'Abonné' : 'Suivre ce projet' ?></span> · <span data-follow-count><?= $followersCount ?></span>
                                    </button>
                                </form>
                            <?php elseif (!$isAuthenticated): ?>
                                <a href="<?= ROOT ?>/Homes/login" class="pd-follow-btn"><i class='bx bx-bell'></i> Suivre ce projet · <?= $followersCount ?></a>
                            <?php endif; ?>

                            <div class="pd-engage-stats">
                                <span class="pd-chip pd-chip--rating"><i class='bx bxs-star'></i><?= number_format($avgRating, 1) ?>/5</span>
                                <span class="pd-chip"><i class='bx bx-bell'></i><span data-follow-count><?= $followersCount ?></span>&nbsp;abonné(s)</span>
                                <span class="pd-chip"><i class='bx bx-message-rounded-dots'></i><?= count($conversation ?? []) ?> msg</span>
                            </div>

                            <button type="button" class="pd-share-btn" data-share-btn><i class='bx bx-share-alt'></i> Partager ce projet</button>
                        </div>

                        <!-- Assistant IA -->
                        <div class="pd-side">
                            <h3 class="pd-section-title"><i class='bx bx-bot'></i> Assistant IA</h3>
                            <p class="pd-rating-meta mb-2">Posez une question sur l'utilité, les technologies ou les points à demander au porteur.</p>
                            <div class="pd-ai-chat" id="projectAiChat">
                                <div class="pd-ai-bubble assistant">Je peux vous aider à comprendre ce projet en détail.</div>
                            </div>
                            <div class="pd-ai-suggestions" id="projectAiSuggestions">
                                <button type="button" class="pd-ai-chip" data-project-ai-prompt="Ce projet est-il adapté à un débutant ?">Pour débutant ?</button>
                                <button type="button" class="pd-ai-chip" data-project-ai-prompt="Quels sont ses points forts pour un salon numérique ?">Points forts</button>
                                <button type="button" class="pd-ai-chip" data-project-ai-prompt="Quelles améliorations prioritaires proposer ?">Améliorations</button>
                            </div>
                            <textarea id="projectAiInput" class="pd-ai-input" placeholder="Ex : ce projet est-il pertinent pour apprendre PHP et MySQL ?"></textarea>
                            <button type="button" class="pd-btn pd-btn--block mt-3" id="projectAiSend"><i class='bx bx-bot'></i> Demander à l'assistant</button>
                        </div>

                        <!-- Fichiers -->
                        <?php if (!empty($files)): ?>
                            <div class="pd-side">
                                <h3 class="pd-section-title"><i class='bx bx-folder'></i> Fichiers du projet</h3>
                                <?php foreach ($files as $file): ?>
                                    <a class="pd-file" href="<?= ROOT_IMG ?>/uploads/projects/files/<?= htmlspecialchars($file->fichier ?? '') ?>" target="_blank" rel="noopener">
                                        <i class='bx bx-file'></i> <span class="text-truncate"><?= htmlspecialchars($file->fichier ?? 'Document') ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Projets liés -->
                        <?php if (!empty($relatedProjects)): ?>
                            <div class="pd-side">
                                <h3 class="pd-section-title"><i class='bx bx-collection'></i> Autres projets</h3>
                                <?php foreach ($relatedProjects as $item): ?>
                                    <a class="pd-related-card" href="<?= ROOT ?>/Projets/detail/<?= (int) ($item['id'] ?? 0) ?>">
                                        <strong><?= htmlspecialchars($item['title'] ?? '') ?></strong>
                                        <span><i class='bx bx-category'></i> <?= htmlspecialchars($item['category'] ?? '') ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    $this->view('Partials/footer');
    $this->view('Partials/scripts');
    ?>

    <div id="lightbox" style="display:none; position:fixed; inset:0; background:rgba(8,18,14,.92); z-index:9999; cursor:zoom-out; align-items:center; justify-content:center; padding:20px;">
        <img id="lightbox-img" alt="" style="max-width:94%; max-height:92%; object-fit:contain; border-radius:12px;">
    </div>

    <script>
        function pdSwap(btn, url) {
            var m = document.getElementById('pdMainImg');
            if (m) { m.src = url; }
            document.querySelectorAll('.pd-thumb').forEach(function (t) { t.classList.remove('is-active'); });
            btn.classList.add('is-active');
        }
        function openLightbox(src) {
            var lb = document.getElementById('lightbox');
            document.getElementById('lightbox-img').src = src;
            lb.style.display = 'flex';
        }
        document.getElementById('lightbox') && document.getElementById('lightbox').addEventListener('click', function () { this.style.display = 'none'; });
        (function () {
            var btn = document.getElementById('pdContactBtn');
            if (!btn) { return; }
            btn.addEventListener('click', function () {
                var t = document.getElementById('pd-discussion');
                if (t) { t.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
                var i = document.getElementById('pdMessageInput');
                if (i) { setTimeout(function () { i.focus(); }, 400); }
            });
        })();
        // Partage : Web Share API (feuille native mobile) + repli copie-lien. Delegue => tous les [data-share-btn].
        (function () {
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('[data-share-btn]');
                if (!btn) { return; }
                var url = window.location.href, title = document.title || 'Projet NGAKODON', original = btn.innerHTML;
                if (navigator.share) {
                    navigator.share({ title: title, text: title, url: url }).catch(function () {});
                } else if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).then(function () {
                        btn.classList.add('is-done');
                        btn.innerHTML = '<i class="bx bx-check"></i> Lien copié !';
                        setTimeout(function () { btn.classList.remove('is-done'); btn.innerHTML = original; }, 2000);
                    }).catch(function () { window.prompt('Copiez le lien du projet :', url); });
                } else {
                    window.prompt('Copiez le lien du projet :', url);
                }
            });
        })();

        // Aimer / Suivre en ASYNCHRONE (sans rechargement) — synchronise tous les boutons + compteurs.
        (function () {
            var CSRF = <?= json_encode($csrf) ?>;
            var ENDPOINT = <?= json_encode(ROOT . '/Projets/detail/' . (int) ($project->id ?? 0)) ?>;

            function applyState(kind, on, count) {
                document.querySelectorAll('[data-async="' + kind + '"]').forEach(function (b) {
                    b.classList.toggle('is-active', on);
                    b.setAttribute('aria-pressed', on ? 'true' : 'false');
                    var i = b.querySelector('i');
                    if (i) { i.className = 'bx ' + (kind === 'like' ? (on ? 'bxs-heart' : 'bx-heart') : (on ? 'bxs-bell' : 'bx-bell')); }
                    var t = b.querySelector('[data-' + kind + '-text]');
                    if (t) { t.textContent = on ? (b.getAttribute('data-' + kind + '-on') || '') : (b.getAttribute('data-' + kind + '-off') || ''); }
                });
                if (typeof count !== 'undefined' && count !== null) {
                    document.querySelectorAll('[data-' + kind + '-count]').forEach(function (el) { el.textContent = count; });
                }
            }

            document.addEventListener('click', function (e) {
                var btn = e.target.closest('[data-async]');
                if (!btn) { return; }
                e.preventDefault();
                if (btn.classList.contains('is-loading')) { return; }
                var kind = btn.getAttribute('data-async');
                btn.classList.add('is-loading');
                var fd = new FormData();
                fd.append('action', kind === 'like' ? 'toggle_like' : 'toggle_follow');
                fd.append('csrf_token', CSRF);
                fetch(ENDPOINT, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: fd })
                    .then(function (r) { return r.json(); })
                    .then(function (res) {
                        if (res && res.ok) { applyState(kind, kind === 'like' ? res.liked : res.following, res.count); }
                    })
                    .catch(function () {})
                    .then(function () { btn.classList.remove('is-loading'); });
            });
        })();

        (function ($) {
            const projectAiHistory = [{ role: 'assistant', content: "Je peux vous aider à comprendre ce projet en détail." }];

            function appendProjectAi(role, text) {
                const safe = $('<div>').text(text).html().replace(/\n/g, '<br>');
                const bubble = $('<div class="pd-ai-bubble ' + role + '">' + safe + '</div>');
                $('#projectAiChat').append(bubble);
                bubble.hide().fadeIn(250);
                const box = $('#projectAiChat').get(0);
                if (box) { box.scrollTop = box.scrollHeight; }
            }

            function renderProjectSuggestions(items) {
                const suggestions = Array.isArray(items) ? items.filter(Boolean).slice(0, 3) : [];
                if (!suggestions.length) { return; }
                $('#projectAiSuggestions').html(suggestions.map(function (item) {
                    const safe = $('<div>').text(item).html();
                    return '<button type="button" class="pd-ai-chip" data-project-ai-prompt="' + safe + '">' + safe + '</button>';
                }).join(''));
            }

            let isSending = false;

            function sendProjectAi() {
                if (isSending) { return; }
                const text = $('#projectAiInput').val().trim();
                if (!text) { appendProjectAi('assistant', "Veuillez écrire une question pour que je puisse vous aider."); return; }
                isSending = true;
                appendProjectAi('user', text);
                projectAiHistory.push({ role: 'user', content: text });
                $('#projectAiInput').val('');
                appendProjectAi('assistant', "…");
                $.post('<?= ROOT ?>/Projets/ai_assistant/<?= (int) ($project->id ?? 0) ?>', {
                    message: text,
                    history: JSON.stringify(projectAiHistory.slice(-6))
                }, function (response) {
                    $('#projectAiChat .pd-ai-bubble.assistant').last().remove();
                    const answer = response && response.message ? response.message : "Je n'ai pas pu répondre pour le moment. Veuillez réessayer.";
                    appendProjectAi('assistant', answer);
                    projectAiHistory.push({ role: 'assistant', content: answer });
                    renderProjectSuggestions(response && response.suggestions ? response.suggestions : []);
                    isSending = false;
                }, 'json').fail(function () {
                    $('#projectAiChat .pd-ai-bubble.assistant').last().remove();
                    appendProjectAi('assistant', "L'assistant IA n'est pas disponible pour le moment. Veuillez réessayer plus tard.");
                    isSending = false;
                });
            }

            $('#projectAiSend').on('click', sendProjectAi);
            $('#projectAiInput').on('keydown', function (event) {
                if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') { event.preventDefault(); sendProjectAi(); }
            });
            $(document).on('click', '[data-project-ai-prompt]', function () {
                $('#projectAiInput').val($(this).data('project-ai-prompt'));
                sendProjectAi();
            });
        })(jQuery);
    </script>
</main>
</body>
</html>
