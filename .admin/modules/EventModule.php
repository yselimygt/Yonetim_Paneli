<?php

class EventModule extends BaseModule
{
    public function render(): string
    {
        $stmt = $this->pdo->prepare("
            SELECT e.*, u.name AS creator_name 
            FROM app_events e
            JOIN users u ON u.id = e.created_by
            WHERE e.app_id = ? AND (e.department_id = ? OR e.is_global = 1)
            ORDER BY e.event_date ASC, e.event_time ASC
        ");
        $stmt->execute([$this->app['id'], $this->department['id']]);
        $events = $stmt->fetchAll();

        $physicalUpcoming = [];
        $physicalPast = [];
        $onlineUpcoming = [];
        $onlinePast = [];
        $today = date('Y-m-d');

        foreach ($events as $event) {
            $isPast = ($event['event_date'] < $today);
            if ($event['type'] === 'physical') {
                if ($isPast) $physicalPast[] = $event;
                else $physicalUpcoming[] = $event;
            } else {
                if ($isPast) $onlinePast[] = $event;
                else $onlineUpcoming[] = $event;
            }
        }

        // Reverse past events to show most recent past first
        $physicalPast = array_reverse($physicalPast);
        $onlinePast = array_reverse($onlinePast);

        $canEdit = $this->isDirectorOrAdmin();

        // Anlaşmalı Mekanları (Partners) çekiyoruz
        $stmtPartners = $this->pdo->prepare("SELECT id, venue_name, address, map_link FROM app_partners WHERE department_id = ? AND status = 1 ORDER BY venue_name ASC");
        $stmtPartners->execute([$this->department['id']]);
        $partners = $stmtPartners->fetchAll();

        // Kategorilere göre ayırma
        $categorizedEvents = [];
        foreach ($events as $event) {
            $cat = trim($event['category'] ?? '');
            if (empty($cat)) {
                $cat = 'Diğer';
            }
            if (!isset($categorizedEvents[$cat])) {
                $categorizedEvents[$cat] = [];
            }
            $categorizedEvents[$cat][] = $event;
        }

        $currentDeptId = $this->department['id'];

        return $this->renderView('event', compact(
            'physicalUpcoming', 'physicalPast', 
            'onlineUpcoming', 'onlinePast', 
            'categorizedEvents',
            'canEdit', 'partners', 'currentDeptId'
        ));
    }

    public function handlePost(array $postData): void
    {
        $action = $postData['action'] ?? '';
        $canEdit = $this->isDirectorOrAdmin();

        if (!$canEdit) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Bu işlem için yetkiniz yok.'];
            header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
            exit;
        }

        if ($action === 'add') {
            $type = $postData['type'] ?? 'physical';
            $title = trim($postData['title'] ?? '');
            $eventDate = trim($postData['event_date'] ?? '');
            $eventTime = trim($postData['event_time'] ?? null);
            $description = trim($postData['description'] ?? '');
            $locationOrPlatform = trim($postData['location_or_platform'] ?? '');
            $link = trim($postData['link'] ?? '');
            $category = trim($postData['category'] ?? '');
            $status = isset($postData['status']) ? 1 : 0;
            $isGlobal = isset($postData['is_global']) ? 1 : 0;

            if (empty($title) || empty($eventDate)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Etkinlik başlığı ve tarihi zorunludur.'];
                $this->redirectBack();
            }
            if (empty($eventTime)) $eventTime = null;

            // Handle Image Upload
            $imagePath = null;
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/events/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($ext, $allowed)) {
                    $filename = uniqid('evt_') . '.' . $ext;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                        $imagePath = 'uploads/events/' . $filename;
                    }
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Sadece resim dosyaları yüklenebilir.'];
                    $this->redirectBack();
                }
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO app_events (app_id, department_id, type, title, event_date, event_time, location_or_platform, description, link, image_path, category, status, created_by, is_global)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $this->app['id'],
                $this->department['id'],
                $type,
                $title,
                $eventDate,
                $eventTime,
                $locationOrPlatform,
                $description,
                $link,
                $imagePath,
                $category,
                $status,
                $this->user['id'],
                $isGlobal
            ]);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Etkinlik başarıyla eklendi.'];
            $this->redirectBack();
        }

        if ($action === 'edit') {
            $eventId = (int) ($postData['event_id'] ?? 0);
            $type = $postData['type'] ?? 'physical';
            $title = trim($postData['title'] ?? '');
            $eventDate = trim($postData['event_date'] ?? '');
            $eventTime = trim($postData['event_time'] ?? null);
            $description = trim($postData['description'] ?? '');
            $locationOrPlatform = trim($postData['location_or_platform'] ?? '');
            $link = trim($postData['link'] ?? '');
            $category = trim($postData['category'] ?? '');
            $isGlobal = isset($postData['is_global']) ? 1 : 0;

            if (empty($title) || empty($eventDate)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Etkinlik başlığı ve tarihi zorunludur.'];
                $this->redirectBack();
            }
            if (empty($eventTime)) $eventTime = null;

            // Check if event exists
            $stmtCheck = $this->pdo->prepare("SELECT image_path FROM app_events WHERE id = ? AND app_id = ?");
            $stmtCheck->execute([$eventId, $this->app['id']]);
            $existingEvent = $stmtCheck->fetch();

            if (!$existingEvent) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Etkinlik bulunamadı.'];
                $this->redirectBack();
            }

            $imagePath = $existingEvent['image_path'];
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/events/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($ext, $allowed)) {
                    $filename = uniqid('evt_') . '.' . $ext;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                        // delete old
                        if ($imagePath && file_exists(__DIR__ . '/../public/' . $imagePath)) {
                            unlink(__DIR__ . '/../public/' . $imagePath);
                        }
                        $imagePath = 'uploads/events/' . $filename;
                    }
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Sadece resim dosyaları yüklenebilir.'];
                    $this->redirectBack();
                }
            }

            $stmt = $this->pdo->prepare("
                UPDATE app_events SET 
                    type=?, title=?, event_date=?, event_time=?, 
                    location_or_platform=?, description=?, link=?, 
                    image_path=?, category=?, is_global=?
                WHERE id=? AND app_id=? AND department_id=?
            ");
            $stmt->execute([
                $type, $title, $eventDate, $eventTime,
                $locationOrPlatform, $description, $link,
                $imagePath, $category, $isGlobal,
                $eventId, $this->app['id'], $this->department['id']
            ]);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Etkinlik başarıyla güncellendi.'];
            $this->redirectBack();
        }

        if ($action === 'delete') {
            $eventId = (int) ($postData['event_id'] ?? 0);
            
            // Delete image if exists
            $stmtCheck = $this->pdo->prepare("SELECT image_path FROM app_events WHERE id = ? AND app_id = ?");
            $stmtCheck->execute([$eventId, $this->app['id']]);
            $event = $stmtCheck->fetch();
            
            if ($event) {
                if ($event['image_path']) {
                    $file = __DIR__ . '/../public/' . $event['image_path'];
                    if (file_exists($file)) unlink($file);
                }
                $stmt = $this->pdo->prepare("DELETE FROM app_events WHERE id = ? AND department_id = ?");
                $stmt->execute([$eventId, $this->department['id']]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Etkinlik silindi.'];
            }
            $this->redirectBack();
        }

        if ($action === 'toggle_status') {
            $eventId = (int) ($postData['event_id'] ?? 0);
            $stmt = $this->pdo->prepare("UPDATE app_events SET status = NOT status WHERE id = ? AND app_id = ? AND department_id = ?");
            $stmt->execute([$eventId, $this->app['id'], $this->department['id']]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Durum güncellendi.'];
            $this->redirectBack();
        }
    }

    private function redirectBack(): void
    {
        header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
        exit;
    }
}
