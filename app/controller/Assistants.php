<?php

class Assistants extends Controller
{
    public function chat(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Methode non autorisee.']);
            return;
        }

        $payload = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($payload)) {
            http_response_code(400);
            echo json_encode(['error' => 'Requete invalide.']);
            return;
        }

        $message = trim((string) ($payload['message'] ?? ''));
        $history = is_array($payload['history'] ?? null) ? $payload['history'] : [];
        $pageContext = is_array($payload['context'] ?? null) ? $payload['context'] : [];

        if ($message === '') {
            http_response_code(422);
            echo json_encode(['error' => 'Veuillez saisir une question.']);
            return;
        }

        if (!RateLimiter::allow('ai_chat', 20, 60)) {
            http_response_code(429);
            echo json_encode(['error' => 'Trop de messages envoyes. Patientez quelques instants avant de reessayer.']);
            return;
        }

        $pageContext['role'] = (string) ($_SESSION['role'] ?? 'visiteur');
        $pageContext['name'] = trim((string) (($_SESSION['prenom'] ?? '') . ' ' . ($_SESSION['nom'] ?? '')));

        $assistant = new HuggingFaceProjectAssistant();
        $response = $assistant->answerForPlatform(
            mb_substr($message, 0, 1200),
            $pageContext,
            $this->sanitizeHistory($history)
        );

        echo json_encode([
            'answer' => (string) ($response['message'] ?? "Je n'ai pas pu generer une reponse pour le moment."),
            'mode' => (string) ($response['mode'] ?? 'local'),
            'suggestions' => $response['suggestions'] ?? [],
        ], JSON_UNESCAPED_UNICODE);
    }

    private function sanitizeHistory(array $history): array
    {
        $clean = [];

        foreach (array_slice($history, -8) as $item) {
            if (!is_array($item)) {
                continue;
            }

            $role = (string) ($item['role'] ?? '');
            $content = trim((string) ($item['content'] ?? ''));

            if (!in_array($role, ['user', 'assistant'], true) || $content === '') {
                continue;
            }

            $clean[] = [
                'role' => $role,
                'content' => mb_substr($content, 0, 900),
            ];
        }

        return $clean;
    }
}
