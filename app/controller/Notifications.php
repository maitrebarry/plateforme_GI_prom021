<?php

class Notifications extends Controller
{
    private function requireUserId(): int
    {
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        if ($userId <= 0) {
            $_SESSION['notification'] = ['type' => 'warning', 'message' => 'Veuillez vous connecter pour voir vos notifications.'];
            $this->redirect('Homes/login');
        }

        return $userId;
    }

    public function index(): void
    {
        $userId = $this->requireUserId();
        $model = new Notification();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_all_read'])) {
            $model->markAllRead($userId);
            $_SESSION['notification'] = ['type' => 'success', 'message' => 'Toutes les notifications ont ete marquees comme lues.'];
            $this->redirect('Notifications/index');
        }

        $this->view('notifications', [
            'pageTitle' => 'Mes notifications',
            'notificationsList' => $model->getForUser($userId, 50),
            'unreadCount' => $model->countUnread($userId),
        ]);
    }

    public function read($id = 0): void
    {
        $userId = $this->requireUserId();
        $model = new Notification();
        $notifId = (int) $id;

        $link = trim($model->getLink($notifId, $userId));
        $model->markRead($notifId, $userId);

        // Securite : on ne redirige que vers un chemin interne relatif.
        if ($link === '' || preg_match('#^https?://#i', $link) || str_starts_with($link, '//')) {
            $this->redirect('Notifications/index');
        }

        $this->redirect($link);
    }

    public function read_all(): void
    {
        $userId = $this->requireUserId();
        (new Notification())->markAllRead($userId);
        $this->redirect('Notifications/index');
    }
}
