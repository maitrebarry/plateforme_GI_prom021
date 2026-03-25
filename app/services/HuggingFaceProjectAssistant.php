<?php

class HuggingFaceProjectAssistant
{
    private const API_URL = 'https://router.huggingface.co/v1/chat/completions';

    public function isConfigured(): bool
    {
        return HF_API_TOKEN !== '';
    }

    public function answerForCatalog(string $message, array $projects, array $history = []): array
    {
        $catalog = array_map(static function (array $project): array {
            return [
                'id' => $project['id'] ?? 0,
                'title' => $project['title'] ?? '',
                'category' => $project['category'] ?? '',
                'author' => $project['author'] ?? '',
                'technologies' => $project['technologies'] ?? [],
                'excerpt' => $project['excerpt'] ?? '',
                'likes_count' => (int) ($project['likes_count'] ?? 0),
                'reviews_count' => (int) ($project['reviews_count'] ?? 0),
                'average_rating' => (float) ($project['average_rating'] ?? 0),
            ];
        }, array_slice($projects, 0, 24));

        $systemPrompt = "Tu es un assistant de recommandation de projets etudiants. "
            . "Tu aides l'utilisateur a choisir un projet selon ses objectifs, son niveau et ses centres d'interet. "
            . "Tu dois repondre en francais, de facon claire, concrete, chaleureuse et structuree. "
            . "Commence par reformuler brievement le besoin de l'utilisateur, puis propose 2 a 4 projets du catalogue en citant leur titre exact, leur categorie, leur proprietaire et pourquoi ils sont pertinents. "
            . "Quand c'est utile, indique aussi le niveau, la valeur d'usage, la faisabilite et l'attrait pour une demonstration de salon. "
            . "Si le besoin n'est pas assez precis, pose une courte question de clarification a la fin. "
            . "N'invente jamais un projet absent du catalogue.";

        $userPrompt = "Historique recent :\n"
            . $this->formatHistoryForPrompt($history)
            . "\n\nCatalogue disponible :\n"
            . json_encode($catalog, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            . "\n\nQuestion utilisateur : " . $message;

        $response = $this->request($systemPrompt, $userPrompt);

        if (($response['ok'] ?? false) === true) {
            return $response;
        }

        return $this->buildLocalCatalogResponse($message, $projects, $history);
    }

    public function answerForProject(string $message, array $projectContext, array $history = []): array
    {
        $systemPrompt = "Tu es un assistant de presentation de projet. "
            . "Tu aides l'utilisateur a comprendre un projet, ses points forts, ses limites, ses technologies, "
            . "les competences mobilisees et les questions a poser au proprietaire. "
            . "Tu reponds en francais, de facon precise, structuree, utile et naturelle. "
            . "Adapte ta reponse a la question de l'utilisateur : utilite, niveau, faisabilite, innovation, demonstration, recrutement ou ameliorations possibles. "
            . "Tu t'appuies uniquement sur les informations du projet fournies.";

        $userPrompt = "Historique recent :\n"
            . $this->formatHistoryForPrompt($history)
            . "\n\nContexte du projet :\n"
            . json_encode($projectContext, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            . "\n\nQuestion utilisateur : " . $message;

        if (!$this->isConfigured()) {
            return $this->buildLocalProjectResponse($message, $projectContext, $history);
        }

        $response = $this->request($systemPrompt, $userPrompt);

        if (($response['ok'] ?? false) === true) {
            return $response;
        }

        return $this->buildLocalProjectResponse($message, $projectContext, $history);
    }

    private function request(string $instructions, string $input): array
    {
        if (!$this->isConfigured()) {
            return [
                'ok' => false,
                'mode' => 'local',
                'message' => "Le mode Hugging Face n'est pas encore configure. Ajoutez HF_API_TOKEN dans l'environnement du serveur.",
            ];
        }

        $payload = json_encode([
            'model' => HF_MODEL,
            'messages' => [
                ['role' => 'system', 'content' => $instructions],
                ['role' => 'user', 'content' => $input],
            ],
            'temperature' => 0.4,
            'max_tokens' => 700,
        ], JSON_UNESCAPED_UNICODE);

        $ch = curl_init(self::API_URL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . HF_API_TOKEN,
            ],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_TIMEOUT => 45,
        ]);

        $raw = curl_exec($ch);
        $error = curl_error($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if ($raw === false || $error !== '') {
            return [
                'ok' => false,
                'mode' => 'remote',
                'message' => "Impossible de joindre l'assistant Hugging Face pour le moment.",
            ];
        }

        $data = json_decode($raw, true);
        $text = trim((string) ($data['choices'][0]['message']['content'] ?? ''));

        if ($status >= 400 || $text === '') {
            return [
                'ok' => false,
                'mode' => 'remote',
                'message' => "L'assistant Hugging Face n'a pas renvoye de reponse exploitable.",
            ];
        }

        return [
            'ok' => true,
            'mode' => 'remote',
            'message' => $text,
            'suggestions' => [
                'Peux-tu affiner selon mon niveau ?',
                'Lequel est le plus fort pour une demonstration ?',
                'Donne-moi une comparaison rapide.',
            ],
        ];
    }

    private function buildLocalCatalogResponse(string $message, array $projects, array $history = []): array
    {
        $profile = $this->inferCatalogProfile($message, $history);
        $scoredProjects = $this->scoreCatalogProjects($projects, $profile);
        $selected = array_slice($scoredProjects, 0, 3);

        if (empty($selected) || (($selected[0]['local_score'] ?? 0) <= 0)) {
            return [
                'ok' => true,
                'mode' => 'local',
                'message' => "Je vois l'orientation generale de votre besoin, mais j'ai encore besoin d'un peu plus de precision.\n\nDites-moi par exemple votre niveau, votre domaine prefere ou le type d'application que vous voulez voir en priorite.",
                'suggestions' => $this->buildCatalogSuggestions($profile, false),
            ];
        }

        $lines = [
            "Voici ce que j'ai compris de votre besoin : " . $this->summarizeCatalogProfile($profile),
            '',
            'Mes meilleurs choix pour vous :',
            '',
        ];

        foreach ($selected as $index => $project) {
            $technologies = !empty($project['technologies'])
                ? implode(', ', array_slice($project['technologies'], 0, 4))
                : 'technologies non precisees';

            $lines[] = ($index + 1) . '. ' . ($project['title'] ?? 'Projet');
            $lines[] = '   Proprietaire : ' . ($project['author'] ?? 'Etudiant GI') . ' | Categorie : ' . ($project['category'] ?? 'Sans categorie');
            $lines[] = '   Pourquoi ce choix : ' . ($project['local_reason'] ?? 'Projet pertinent pour votre recherche.');
            $lines[] = '   Technologies : ' . $technologies . '.';
            $lines[] = '   Engagement : ' . number_format((float) ($project['average_rating'] ?? 0), 1) . '/5, '
                . (int) ($project['likes_count'] ?? 0) . ' likes, '
                . (int) ($project['reviews_count'] ?? 0) . ' avis.';
            $lines[] = '';
        }

        $lines[] = "Mon conseil : commencez par le premier projet si vous voulez la recommandation la plus proche de votre besoin actuel.";

        return [
            'ok' => true,
            'mode' => 'local',
            'message' => implode("\n", $lines),
            'suggestions' => $this->buildCatalogSuggestions($profile, true),
        ];
    }

    private function buildLocalProjectResponse(string $message, array $projectContext, array $history = []): array
    {
        $title = (string) ($projectContext['title'] ?? 'Ce projet');
        $category = (string) ($projectContext['category'] ?? 'Sans categorie');
        $owner = (string) ($projectContext['owner'] ?? 'Etudiant GI');
        $field = (string) ($projectContext['field'] ?? 'Genie Informatique');
        $description = trim((string) ($projectContext['description'] ?? ''));
        $technologies = trim((string) ($projectContext['technologies'] ?? ''));
        $question = mb_strtolower(trim($message));
        $historyContext = $this->formatHistoryForPrompt($history);

        $lines = [
            'Voici mon analyse de ' . $title . ' :',
            '',
            'Presentation rapide : c\'est un projet de categorie ' . $category . ', propose par ' . $owner . ', dans le contexte ' . $field . '.',
        ];

        if ($description !== '') {
            $lines[] = 'Valeur du projet : ' . $description;
        }

        if ($technologies !== '') {
            $lines[] = 'Technologies mobilisees : ' . $technologies . '.';
        }

        if (str_contains($question, 'niveau') || str_contains($question, 'debutant') || str_contains($question, 'difficulte')) {
            $lines[] = "Niveau estime : ce projet semble surtout interessant si l'etudiant maitrise deja les bases du developpement et peut structurer une application complete.";
        }

        if (str_contains($question, 'utile') || str_contains($question, 'utilite') || str_contains($question, 'besoin')) {
            $lines[] = "Utilite concrete : la valeur du projet se juge surtout par le probleme reel qu'il resout et par sa capacite a etre adopte par de vrais utilisateurs.";
        }

        if (str_contains($question, 'amelior') || str_contains($question, 'ameliore') || str_contains($question, 'evolu')) {
            $lines[] = "Pistes d'amelioration : clarifier les cas d'usage, enrichir l'interface, mesurer l'impact utilisateur et montrer une feuille de route evolutive.";
        }

        if (str_contains($question, 'salon') || str_contains($question, 'demo') || str_contains($question, 'presentation')) {
            $lines[] = "Pour une demonstration de salon, il faut surtout montrer le probleme de depart, la solution apportee, un scenario d'usage simple et un resultat visible en quelques clics.";
        }

        $lines[] = 'Questions utiles a poser au proprietaire :';
        $lines[] = '- Quel probleme reel vouliez-vous resoudre ?';
        $lines[] = '- Quel est l\'etat actuel du projet : prototype, version testable ou solution deja utilisee ?';
        $lines[] = '- Quelles sont les prochaines ameliorations prioritaires ?';

        if ($historyContext !== 'Aucun historique significatif.') {
            $lines[] = '';
            $lines[] = "Je garde aussi le contexte recent de la conversation pour repondre de facon plus coherente.";
        }

        return [
            'ok' => true,
            'mode' => 'local',
            'message' => implode("\n", $lines),
            'suggestions' => [
                'Ce projet est-il adapte a un debutant ?',
                'Quels sont ses points forts pour une demonstration ?',
                'Quelles ameliorations prioritaires proposer ?',
            ],
        ];
    }

    private function formatHistoryForPrompt(array $history): string
    {
        $clean = [];
        foreach (array_slice($history, -6) as $item) {
            $role = strtolower((string) ($item['role'] ?? 'user'));
            $content = trim((string) ($item['content'] ?? ''));
            if ($content === '' || !in_array($role, ['user', 'assistant'], true)) {
                continue;
            }
            $clean[] = strtoupper($role) . ' : ' . $content;
        }

        return !empty($clean) ? implode("\n", $clean) : 'Aucun historique significatif.';
    }

    private function inferCatalogProfile(string $message, array $history = []): array
    {
        $fullText = mb_strtolower(trim($this->formatHistoryForPrompt($history) . ' ' . $message));
        $tokens = array_values(array_filter(
            preg_split('/[\s,;:.!?]+/u', $fullText),
            static fn($token) => mb_strlen($token) >= 3
        ));

        $domains = [
            'web' => ['web', 'php', 'site', 'plateforme', 'laravel', 'mvc'],
            'mobile' => ['mobile', 'android', 'ios', 'flutter', 'react', 'kotlin'],
            'ia' => ['ia', 'intelligence', 'machine', 'data', 'analyse', 'prediction'],
            'gestion' => ['gestion', 'suivi', 'stock', 'presence', 'administration'],
            'education' => ['education', 'universite', 'ecole', 'cours', 'notes', 'campus'],
            'sante' => ['sante', 'medical', 'hopital', 'clinique'],
        ];

        $level = 'non precise';
        if (preg_match('/debutant|initiation|simple/', $fullText)) {
            $level = 'debutant';
        } elseif (preg_match('/intermediaire|moyen/', $fullText)) {
            $level = 'intermediaire';
        } elseif (preg_match('/avance|expert|complexe/', $fullText)) {
            $level = 'avance';
        }

        $priorities = [];
        foreach ([
            'utile' => ['utile', 'impact', 'pratique', 'besoin reel'],
            'demo' => ['demo', 'salon', 'presentation', 'jury'],
            'innovation' => ['innovant', 'innovation', 'original'],
            'faisable' => ['faisable', 'simple', 'realisable', 'rapide'],
            'popularite' => ['bien note', 'aime', 'top', 'populaire'],
        ] as $key => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($fullText, $keyword)) {
                    $priorities[] = $key;
                    break;
                }
            }
        }

        $matchedDomains = [];
        foreach ($domains as $domain => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($fullText, $keyword)) {
                    $matchedDomains[] = $domain;
                    break;
                }
            }
        }

        return [
            'level' => $level,
            'domains' => array_values(array_unique($matchedDomains)),
            'priorities' => array_values(array_unique($priorities)),
            'tokens' => array_values(array_unique($tokens)),
        ];
    }

    private function scoreCatalogProjects(array $projects, array $profile): array
    {
        $domainKeywords = [
            'web' => ['web', 'php', 'site', 'plateforme', 'portail'],
            'mobile' => ['mobile', 'android', 'ios', 'flutter', 'application mobile'],
            'ia' => ['ia', 'intelligence', 'prediction', 'data', 'analyse'],
            'gestion' => ['gestion', 'suivi', 'stock', 'presence', 'reservation'],
            'education' => ['education', 'universite', 'ecole', 'notes', 'etudiant'],
            'sante' => ['sante', 'medical', 'hopital', 'clinique'],
        ];

        foreach ($projects as &$project) {
            $haystack = mb_strtolower(implode(' ', [
                $project['title'] ?? '',
                $project['description'] ?? '',
                $project['excerpt'] ?? '',
                $project['category'] ?? '',
                implode(' ', $project['technologies'] ?? []),
                $project['author'] ?? '',
            ]));

            $score = 0;
            $reasons = [];

            foreach ($profile['tokens'] as $token) {
                if (str_contains($haystack, $token)) {
                    $score += 3;
                    $reasons[] = $token;
                }
            }

            foreach ($profile['domains'] as $domain) {
                foreach ($domainKeywords[$domain] ?? [] as $keyword) {
                    if (str_contains($haystack, $keyword)) {
                        $score += 4;
                        $reasons[] = $domain;
                        break;
                    }
                }
            }

            if (in_array('popularite', $profile['priorities'], true)) {
                $score += min(6, (int) floor(((float) ($project['average_rating'] ?? 0)) + ((int) ($project['likes_count'] ?? 0) / 4)));
            }

            if (in_array('demo', $profile['priorities'], true) && !empty($project['image'])) {
                $score += 2;
            }

            if (in_array('faisable', $profile['priorities'], true) && !empty($project['technologies']) && count($project['technologies']) <= 4) {
                $score += 2;
            }

            if (in_array('utile', $profile['priorities'], true) && preg_match('/gestion|suivi|presence|reservation|education|sante|universite/u', $haystack)) {
                $score += 3;
            }

            $project['local_score'] = $score;
            $project['local_reason'] = !empty($reasons)
                ? 'Bonne correspondance avec : ' . implode(', ', array_slice(array_unique($reasons), 0, 4))
                : 'Projet interessant par sa presentation, sa lisibilite et son potentiel d usage.';
        }
        unset($project);

        usort($projects, static function (array $left, array $right): int {
            return ($right['local_score'] <=> $left['local_score'])
                ?: (($right['likes_count'] ?? 0) <=> ($left['likes_count'] ?? 0))
                ?: (($right['average_rating'] ?? 0) <=> ($left['average_rating'] ?? 0))
                ?: strcmp((string) ($right['created_at'] ?? ''), (string) ($left['created_at'] ?? ''));
        });

        return $projects;
    }

    private function summarizeCatalogProfile(array $profile): string
    {
        $parts = [];
        $parts[] = 'niveau ' . $profile['level'];
        if (!empty($profile['domains'])) {
            $parts[] = 'orientation ' . implode(', ', $profile['domains']);
        }
        if (!empty($profile['priorities'])) {
            $parts[] = 'priorites ' . implode(', ', $profile['priorities']);
        }

        return implode(' ; ', $parts);
    }

    private function buildCatalogSuggestions(array $profile, bool $hasMatch): array
    {
        if ($hasMatch) {
            if (in_array('web', $profile['domains'], true)) {
                return [
                    'Montre-moi plutot les projets web les plus faciles a presenter.',
                    'Je veux maintenant les projets web les mieux notes.',
                    'Parmi ces choix, lequel est le plus utile pour une universite ?',
                ];
            }

            return [
                'Lequel est le plus facile a realiser ?',
                'Lequel impressionne le plus pour un salon ?',
                'Peux-tu me proposer une version pour debutant ?',
            ];
        }

        return [
            'Je cherche un projet web utile pour une universite.',
            'Je veux un projet mobile simple a presenter.',
            'Je cherche un projet IA accessible a un etudiant.',
        ];
    }
}
