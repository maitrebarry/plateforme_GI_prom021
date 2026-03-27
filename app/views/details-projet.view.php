<?php $this->view('Partials/head', ['pageTitle' => $project->title ?? 'Detail projet']); ?>

<body>
    <?php $this->view('Partials/global-shell'); ?>
    <?php $this->view('Partials/mobile-menu'); ?>
    <?php $this->view('Partials/header'); ?>
    <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

    <?php
$avgRating = (float) ($reviewSummary->average_rating ?? 0);
$totalReviews = (int) ($reviewSummary->total_reviews ?? 0);
$likesCount = (int) ($likesCount ?? 0);
$currentUserId = (int) ($currentUserId ?? 0);
$ownerId = (int) ($ownerId ?? 0);
$userHasLiked = !empty($userHasLiked);
?>

    <main class="change-gradient">
        <style>
        .pd-shell {
            padding: 56px 0 90px;
            background: linear-gradient(180deg, #f7fffd 0%, #fff 36%, #f8fafc 100%)
        }

        .pd-card,
        .pd-side,
        .pd-section {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 28px;
            box-shadow: 0 24px 60px -40px rgba(15, 23, 42, .35)
        }

        .pd-card,
        .pd-side,
        .pd-section {
            padding: 24px
        }

        .pd-hero {
            margin-bottom: 24px
        }

        .pd-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            color: #0f172a
        }

        .pd-badges,
        .pd-meta,
        .pd-owner-links,
        .pd-engage {
            display: flex;
            flex-wrap: wrap;
            gap: 10px
        }

        .pd-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            border-radius: 999px;
            background: #f0fdfa;
            color: #115e59;
            font-weight: 700
        }

        .pd-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-top: 20px
        }

        .pd-gallery img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 18px
        }

        .pd-desc {
            color: #334155;
            line-height: 1.8
        }

        .pd-side h4,
        .pd-section h4 {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 14px
        }

        .pd-owner {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px
        }

        .pd-owner-avatar {
            width: 74px;
            height: 74px;
            border-radius: 50%;
            background: #ecfeff;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: #0f766e;
            font-weight: 800
        }

        .pd-owner-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .pd-stars {
            display: flex;
            gap: 4px;
            color: #f59e0b;
            font-size: 1.15rem
        }

        .pd-like-btn,
        .pd-submit {
            border: none;
            border-radius: 16px;
            padding: 12px 16px;
            font-weight: 800
        }

        .pd-like-btn {
            background: #fee2e2;
            color: #b91c1c
        }

        .pd-like-btn.is-active {
            background: #dcfce7;
            color: #166534
        }

        .pd-submit {
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            color: #fff
        }

        .pd-review,
        .pd-message {
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #f8fafc
        }

        .pd-form-control {
            width: 100%;
            border: 1px solid #dbe4ee;
            border-radius: 16px;
            padding: 12px 14px
        }

        .pd-form-control:focus {
            outline: none;
            border-color: #14b8a6;
            box-shadow: 0 0 0 4px rgba(20, 184, 166, .12)
        }

        .pd-related {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px
        }

        .pd-related-card {
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            background: #fff
        }

        .pd-file-list {
            padding-left: 18px
        }

        .pd-file-list li {
            margin-bottom: 8px
        }

        .pd-ai-chat {
            display: flex;
            flex-direction: column;
            gap: 12px;
            min-height: 260px;
            max-height: 380px;
            overflow: auto
        }

        .pd-ai-bubble {
            padding: 14px 16px;
            border-radius: 18px;
            white-space: pre-wrap;
            line-height: 1.6
        }

        .pd-ai-bubble.user {
            background: #ecfeff;
            color: #134e4a
        }

        .pd-ai-bubble.assistant {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155
        }

        .pd-ai-input {
            width: 100%;
            border: 1px solid #dbe4ee;
            border-radius: 16px;
            padding: 12px 14px;
            min-height: 100px
        }

        .pd-ai-suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 14px
        }

        .pd-ai-chip {
            border: 1px solid #dbe4ee;
            background: #f8fafc;
            color: #0f766e;
            border-radius: 999px;
            padding: 10px 14px;
            font-weight: 700
        }

        @media (max-width:1199px) {
            .pd-gallery {
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr))
            }

            .pd-related {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media (max-width:991px) {
            .pd-shell {
                padding: 36px 0 72px
            }

            .pd-card,
            .pd-side,
            .pd-section {
                padding: 20px;
                border-radius: 24px
            }

            .pd-title {
                font-size: clamp(1.7rem, 7vw, 2.5rem)
            }

            .pd-gallery img {
                height: 170px
            }

            .pd-ai-chat {
                min-height: 220px;
                max-height: 320px
            }

            .pd-like-btn,
            .pd-submit {
                width: 100%
            }
        }

        @media (max-width:767px) {
            .pd-shell {
                padding: 28px 0 56px
            }

            .pd-card,
            .pd-side,
            .pd-section {
                padding: 18px;
                border-radius: 20px
            }

            .pd-badges,
            .pd-meta,
            .pd-owner-links,
            .pd-engage {
                gap: 8px
            }

            .pd-pill {
                padding: 8px 12px;
                font-size: .9rem
            }

            .pd-owner {
                align-items: flex-start
            }

            .pd-owner-avatar {
                width: 62px;
                height: 62px
            }

            .pd-gallery {
                grid-template-columns: 1fr 1fr;
                gap: 10px
            }

            .pd-gallery img {
                height: 150px;
                border-radius: 16px
            }

            .pd-related {
                grid-template-columns: 1fr
            }

            .pd-ai-input,
            .pd-form-control {
                font-size: .95rem
            }

            .pd-review,
            .pd-message {
                padding: 14px
            }
        }

        @media (max-width:575px) {
            .pd-title {
                font-size: 1.55rem;
                line-height: 1.2
            }

            .pd-gallery {
                grid-template-columns: 1fr
            }

            .pd-gallery img {
                height: 210px
            }

            .pd-desc {
                font-size: .97rem;
                line-height: 1.7
            }

            .pd-ai-chat {
                min-height: 200px;
                max-height: 280px
            }

            .pd-ai-bubble {
                padding: 12px 14px
            }
        }
        </style>

        <section class="pd-shell">
            <div class="container container-two">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="pd-card pd-hero">
                            <a href="<?= ROOT ?>/Homes/index" class="pd-pill mb-3" style="text-decoration:none"><i
                                    class='bx bx-left-arrow-alt'></i> Retour a l'accueil</a>
                            <h1 class="pd-title mb-3"><?= htmlspecialchars($project->title ?? 'Projet') ?></h1>
                            <div class="pd-badges mb-3">
                                <span class="pd-pill"><i
                                        class='bx bx-category'></i><?= htmlspecialchars($project->categorie ?? 'Sans categorie') ?></span>
                                <span class="pd-pill"><i
                                        class='bx bx-time'></i><?= htmlspecialchars($this->temps_relatif($project->created_at ?? date('Y-m-d H:i:s'))) ?></span>
                                <span class="pd-pill"><i class='bx bx-heart'></i><?= $likesCount ?> mention(s)
                                    J'aime</span>
                                <span class="pd-pill"><i class='bx bx-star'></i><?= number_format($avgRating, 1) ?>/5
                                    sur <?= $totalReviews ?> avis</span>
                            </div>
                            <div class="pd-desc"><?= nl2br(htmlspecialchars((string) ($project->description ?? ''), ENT_QUOTES, 'UTF-8')) ?></div>

                            <?php if (!empty($images)): ?>
                            <div class="pd-gallery">
                                <?php foreach ($images as $img): ?>
                                <img src="<?= ROOT_IMG ?>/uploads/projects/images/<?= htmlspecialchars($img->image ?? '') ?>"
                                    alt="Image projet">
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($project->technologies)): ?>
                            <div class="pd-badges mt-4">
                                <?php foreach (explode(',', (string) $project->technologies) as $tech): ?>
                                <?php if (trim($tech) !== ''): ?><span
                                    class="pd-pill"><?= htmlspecialchars(trim($tech)) ?></span><?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($project->video) && ($videoUrl = $this->youtube_embed($project->video))): ?>
                            <div class="pd-section mt-4">
                                <h4>Video de demonstration</h4>
                                <div
                                    style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:18px">
                                    <iframe src="<?= htmlspecialchars($videoUrl) ?>" title="Video du projet"
                                        style="position:absolute;top:0;left:0;width:100%;height:100%;border:none"
                                        allowfullscreen></iframe>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="pd-section mt-4">
                                <h4>Avis et notation</h4>
                                <div class="pd-engage mb-3">
                                    <div class="pd-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class='bx <?= $i <= round($avgRating) ? 'bxs-star' : 'bx-star' ?>'></i>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="text-muted"><?= number_format($avgRating, 1) ?>/5 base sur
                                        <?= $totalReviews ?> avis</div>
                                </div>

                                <?php if ($currentUserId > 0): ?>
                                <form method="post" class="mb-4">
                                    <input type="hidden" name="action" value="submit_review">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Note</label>
                                            <select name="rating" class="pd-form-control" required>
                                                <option value="">Choisir</option>
                                                <?php for ($i = 5; $i >= 1; $i--): ?><option value="<?= $i ?>"><?= $i ?>
                                                    etoile(s)</option><?php endfor; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-9">
                                            <label class="form-label fw-semibold">Votre avis</label>
                                            <textarea name="review" class="pd-form-control" rows="3"
                                                placeholder="Partagez votre retour sur ce projet"></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="pd-submit mt-3">Publier mon avis</button>
                                </form>
                                <?php endif; ?>

                                <div class="row g-3">
                                    <?php foreach ($reviews as $review): ?>
                                    <div class="col-12">
                                        <div class="pd-review">
                                            <div class="d-flex justify-content-between flex-wrap gap-2 mb-2">
                                                <strong><?= htmlspecialchars(trim(($review->prenom ?? '') . ' ' . ($review->nom ?? 'Utilisateur'))) ?></strong>
                                                <span class="pd-stars"><?php for ($i = 1; $i <= 5; $i++): ?><i
                                                        class='bx <?= $i <= (int) ($review->rating ?? 0) ? 'bxs-star' : 'bx-star' ?>'></i><?php endfor; ?></span>
                                            </div>
                                            <div class="text-muted small mb-2">
                                                <?= htmlspecialchars((string) ($review->created_at ?? '')) ?></div>
                                            <div><?= nl2br(htmlspecialchars((string) ($review->review ?? ''))) ?></div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php if (empty($reviews)): ?><div class="col-12">
                                        <div class="pd-review">Aucun avis pour le moment.</div>
                                    </div><?php endif; ?>
                                </div>
                            </div>

                            <div class="pd-section mt-4">
                                <h4>Discussion avec le proprietaire</h4>
                                <?php if ($currentUserId > 0 && $currentUserId !== $ownerId): ?>
                                <div class="row g-3 mb-4">
                                    <?php foreach ($conversation as $message): ?>
                                    <div class="col-12">
                                        <div class="pd-message"
                                            style="<?= (int) ($message->sender_id ?? 0) === $currentUserId ? 'background:#ecfeff;border-color:#a7f3d0' : '' ?>">
                                            <div class="small text-muted mb-2">
                                                <?= htmlspecialchars(trim(($message->sender_prenom ?? '') . ' ' . ($message->sender_nom ?? ''))) ?>
                                                • <?= htmlspecialchars((string) ($message->created_at ?? '')) ?></div>
                                            <div><?= nl2br(htmlspecialchars((string) ($message->message ?? ''))) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php if (empty($conversation)): ?><div class="col-12">
                                        <div class="pd-message">Commencez la conversation avec le proprietaire du
                                            projet.</div>
                                    </div><?php endif; ?>
                                </div>
                                <form method="post">
                                    <input type="hidden" name="action" value="send_message">
                                    <input type="hidden" name="receiver_id" value="<?= $ownerId ?>">
                                    <textarea name="message" class="pd-form-control" rows="4"
                                        placeholder="Ecrivez votre message au proprietaire" required></textarea>
                                    <button type="submit" class="pd-submit mt-3">Envoyer le message</button>
                                </form>
                                <?php elseif ($currentUserId === 0): ?>
                                <div class="pd-message">Connectez-vous pour discuter avec le proprietaire du projet.
                                </div>
                                <?php else: ?>
                                <div class="pd-message">Vous etes le proprietaire de ce projet. Les utilisateurs peuvent
                                    vous contacter depuis cette page.</div>
                                <?php endif; ?>
                            </div>
                        </div>


                    </div>

                    <div class="col-lg-4">
                        <div class="pd-side mb-4">
                            <h4>Proprietaire du projet</h4>
                            <div class="pd-owner">
                                <div class="pd-owner-avatar">
                                    <?php if (!empty($project->owner_image)): ?>
                                    <img src="<?= ROOT_IMG ?>/<?= htmlspecialchars(ltrim((string) $project->owner_image, '/')) ?>"
                                        alt="Profil">
                                    <?php else: ?>
                                    <?= strtoupper(substr((string) ($project->prenom ?? 'U'), 0, 1)) ?>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <strong><?= htmlspecialchars(trim(($project->prenom ?? '') . ' ' . ($project->nom ?? ''))) ?></strong>
                                    <div class="text-muted small">
                                        <?= htmlspecialchars((string) ($project->filiere ?? 'Etudiant')) ?></div>
                                    <div class="text-muted small">
                                        <?= htmlspecialchars((string) ($project->universite ?? '')) ?></div>
                                </div>
                            </div>
                            <div class="small text-muted mb-2">Contact</div>
                            <div class="mb-2"><?= htmlspecialchars((string) ($project->email ?? 'Non renseigne')) ?>
                            </div>
                            <div class="mb-3">
                                <?= htmlspecialchars((string) ($project->contact ?? 'Non renseigne')) ?></div>
                            <div class="pd-owner-links">
                                <?php if (!empty($project->github)): ?><a class="pd-pill"
                                    href="<?= htmlspecialchars($project->github) ?>"
                                    target="_blank">GitHub</a><?php endif; ?>
                                <?php if (!empty($project->linkedin)): ?><a class="pd-pill"
                                    href="<?= htmlspecialchars($project->linkedin) ?>"
                                    target="_blank">LinkedIn</a><?php endif; ?>
                            </div>
                        </div>

                        <div class="pd-side mb-4">
                            <h4>Engagement</h4>
                            <form method="post">
                                <input type="hidden" name="action" value="toggle_like">
                                <button type="submit" class="pd-like-btn <?= $userHasLiked ? 'is-active' : '' ?>">
                                    <?= $userHasLiked ? 'Retirer mon j aime' : 'J aime ce projet' ?> •
                                    <?= $likesCount ?>
                                </button>
                            </form>
                            <div class="pd-badges mt-3">
                                <span class="pd-pill"><i
                                        class='bx bx-star'></i><?= number_format($avgRating, 1) ?>/5</span>
                                <span class="pd-pill"><i
                                        class='bx bx-message-rounded-dots'></i><?= count($conversation ?? []) ?>
                                    message(s)</span>
                            </div>
                        </div>

                        <div class="pd-side mb-4">
                            <h4>Assistant IA sur ce projet</h4>
                            <p class="text-muted small">Posez une question sur l'utilite du projet, ses
                                technologies, son niveau de difficulte ou les points a demander au proprietaire.</p>
                            <div class="pd-ai-chat" id="projectAiChat">
                                <div class="pd-ai-bubble assistant">Je peux expliquer ce projet comme un assistant
                                    IA, a partir de ses informations reelles.</div>
                            </div>
                            <div class="pd-ai-suggestions" id="projectAiSuggestions">
                                <button type="button" class="pd-ai-chip" data-project-ai-prompt="Ce projet est-il adapte a un debutant ?">Pour debutant ?</button>
                                <button type="button" class="pd-ai-chip" data-project-ai-prompt="Quels sont ses points forts pour un salon numerique ?">Pour le salon ?</button>
                                <button type="button" class="pd-ai-chip" data-project-ai-prompt="Quelles ameliorations prioritaires proposer ?">Ameliorations</button>
                            </div>
                            <div class="mt-3">
                                <textarea id="projectAiInput" class="pd-ai-input"
                                    placeholder="Exemple : Ce projet est-il pertinent pour un etudiant qui veut apprendre PHP et MySQL ?"></textarea>
                                <button type="button" class="pd-submit mt-3" id="projectAiSend">Demander a
                                    l'assistant</button>
                            </div>
                        </div>

                        <div class="pd-side mb-4">
                            <h4>Fichiers du projet</h4>
                            <?php if (!empty($files)): ?>
                            <ul class="pd-file-list">
                                <?php foreach ($files as $file): ?>
                                <li><a href="<?= ROOT_IMG ?>/uploads/projects/files/<?= htmlspecialchars($file->fichier ?? '') ?>"
                                        target="_blank"><?= htmlspecialchars($file->fichier ?? 'Document') ?></a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php else: ?>
                            <div class="text-muted">Aucun fichier associe.</div>
                            <?php endif; ?>
                        </div>

                        <div class="pd-side">
                            <h4>Autres projets a fort impact</h4>
                            <div class="pd-related">
                                <?php foreach ($relatedProjects as $item): ?>
                                <div class="pd-related-card">
                                    <strong><?= htmlspecialchars($item['title'] ?? '') ?></strong>
                                    <div class="small text-muted mb-2">
                                        <?= htmlspecialchars($item['category'] ?? '') ?></div>
                                    <a href="<?= ROOT ?>/Projets/detail/<?= (int) ($item['id'] ?? 0) ?>">Voir le
                                        projet</a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
        </section>

        <?php $this->view('Partials/footer'); ?>
    </main>

    <?php $this->view('Partials/scripts'); ?>
    <script>
    (function($) {
        const projectAiHistory = [{
            role: 'assistant',
            content: "Je peux expliquer ce projet comme un assistant IA, a partir de ses informations reelles."
        }];

        function appendProjectAi(role, text) {
            const safe = $('<div>').text(text).html().replace(/\n/g, '<br>');
            $('#projectAiChat').append('<div class="pd-ai-bubble ' + role + '">' + safe + '</div>');
            const box = $('#projectAiChat').get(0);
            if (box) box.scrollTop = box.scrollHeight;
        }

        function renderProjectSuggestions(items) {
            const suggestions = Array.isArray(items) ? items.filter(Boolean).slice(0, 3) : [];
            if (!suggestions.length) return;
            $('#projectAiSuggestions').html(suggestions.map(function(item) {
                const safe = $('<div>').text(item).html();
                return '<button type="button" class="pd-ai-chip" data-project-ai-prompt="' + safe + '">' +
                    safe + '</button>';
            }).join(''));
        }

        function sendProjectAi() {
            const text = $('#projectAiInput').val().trim();
            if (!text) return;
            appendProjectAi('user', text);
            projectAiHistory.push({
                role: 'user',
                content: text
            });
            $('#projectAiInput').val('');
            $.post('<?= ROOT ?>/Projets/ai_assistant/<?= (int) ($project->id ?? 0) ?>', {
                message: text,
                history: JSON.stringify(projectAiHistory.slice(-6))
            }, function(response) {
                const answer = response && response.message ? response.message :
                    "Je n'ai pas pu repondre pour le moment.";
                appendProjectAi('assistant', answer);
                projectAiHistory.push({
                    role: 'assistant',
                    content: answer
                });
                renderProjectSuggestions(response && response.suggestions ? response.suggestions : []);
            }, 'json').fail(function() {
                const fallback = "L'assistant IA n'est pas disponible pour le moment.";
                appendProjectAi('assistant', fallback);
                projectAiHistory.push({
                    role: 'assistant',
                    content: fallback
                });
            });
        }

        $('#projectAiSend').on('click', sendProjectAi);
        $('#projectAiInput').on('keydown', function(event) {
            if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') sendProjectAi();
        });
        $(document).on('click', '[data-project-ai-prompt]', function() {
            $('#projectAiInput').val($(this).data('project-ai-prompt'));
            sendProjectAi();
        });
    })(jQuery);
    </script>
</body>

</html>
