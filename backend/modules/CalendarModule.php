<?php

class CalendarModule extends BaseModule
{
    public function render(): string
    {
        $config = $this->getConfig();
        $allowMemberEvent = !empty($config['allow_member_event']);

        $stmt = $this->pdo->prepare("
            SELECT ce.*, u.name AS creator_name 
            FROM calendar_events ce
            JOIN users u ON u.id = ce.created_by
            ORDER BY ce.event_date ASC, ce.event_time ASC
        ");
        $stmt->execute();
        $events = $stmt->fetchAll();

        // Separate upcoming and past events
        $upcomingEvents = [];
        $pastEvents = [];
        $today = date('Y-m-d');

        foreach ($events as $event) {
            if ($event['event_date'] >= $today) {
                $upcomingEvents[] = $event;
            } else {
                $pastEvents[] = $event;
            }
        }

        // Reverse past events so most recent past is first
        $pastEvents = array_reverse($pastEvents);

        $canAdd = $this->isDirectorOrAdmin() || $allowMemberEvent;

        return $this->renderView('calendar', compact('upcomingEvents', 'pastEvents', 'canAdd'));
    }

    public function handlePost(array $postData): void
    {
        $action = $postData['action'] ?? '';
        $config = $this->getConfig();
        $allowMemberEvent = !empty($config['allow_member_event']);
        $canAdd = $this->isDirectorOrAdmin() || $allowMemberEvent;

        if ($action === 'add' && $canAdd) {
            $title = trim($postData['title'] ?? '');
            $description = trim($postData['description'] ?? '');
            $eventDate = trim($postData['event_date'] ?? '');
            $eventTime = trim($postData['event_time'] ?? null);

            if (empty($title) || empty($eventDate)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Etkinlik adı ve tarihi zorunludur.'];
                header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
                exit;
            }

            if (empty($eventTime)) {
                $eventTime = null;
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO calendar_events (app_id, department_id, created_by, title, description, event_date, event_time) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $this->app['id'],
                $this->department['id'],
                $this->user['id'],
                $title,
                $description,
                $eventDate,
                $eventTime
            ]);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Etkinlik başarıyla eklendi.'];
            header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
            exit;
        }

        if ($action === 'delete') {
            $eventId = (int) ($postData['event_id'] ?? 0);

            // Sadece oluşturan kişi, direktör veya super_admin silebilir
            $stmtCheck = $this->pdo->prepare("SELECT created_by FROM calendar_events WHERE id = ?");
            $stmtCheck->execute([$eventId]);
            $creatorId = (int) $stmtCheck->fetchColumn();

            if ($this->isDirectorOrAdmin() || $creatorId === $this->user['id']) {
                $stmt = $this->pdo->prepare("DELETE FROM calendar_events WHERE id = ?");
                $stmt->execute([$eventId]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Etkinlik silindi.'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Bu etkinliği silmek için yetkiniz yok.'];
            }

            header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
            exit;
        }
    }
}
