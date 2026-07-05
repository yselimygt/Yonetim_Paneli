<?php

class BootcampModule extends BaseModule
{
    public function render(): string
    {
        $stmt = $this->pdo->prepare("
            SELECT b.*, u.name AS creator_name 
            FROM app_bootcamps b
            JOIN apps a ON a.id = b.app_id
            JOIN users u ON u.id = b.created_by
            WHERE a.module_type = 'bootcamp' AND a.is_active = 1
            ORDER BY b.start_date ASC
        ");
        $stmt->execute();
        $bootcamps = $stmt->fetchAll();

        $upcoming = [];
        $past = [];
        $today = date('Y-m-d');

        foreach ($bootcamps as $bc) {
            if ($bc['is_completed'] || $bc['end_date'] < $today) {
                $past[] = $bc;
            } else {
                $upcoming[] = $bc;
            }
        }

        $past = array_reverse($past);
        $canEdit = $this->isDirectorOrAdmin();

        $stmtPartners = $this->pdo->prepare("SELECT id, venue_name, address, map_link FROM app_partners WHERE department_id = ? AND status = 1 ORDER BY venue_name ASC");
        $stmtPartners->execute([$this->department['id']]);
        $partners = $stmtPartners->fetchAll();

        $stmtForms = $this->pdo->prepare("SELECT id, name, slug FROM apps WHERE module_type = 'form' ORDER BY name ASC");
        $stmtForms->execute();
        $forms = $stmtForms->fetchAll();

        return $this->renderView('bootcamp', compact('upcoming', 'past', 'canEdit', 'partners', 'forms'));
    }

    public function handlePost(array $postData): void
    {
        $action = $postData['action'] ?? '';
        $canEdit = $this->isDirectorOrAdmin();

        if (!$canEdit) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Bu işlem için yetkiniz yok.'];
            $this->redirectBack();
        }

        if ($action === 'add') {
            $title = trim($postData['title'] ?? '');
            $type = trim($postData['type'] ?? 'physical');
            $location = ($type === 'physical') ? trim($postData['location_or_platform_physical'] ?? '') : trim($postData['location_or_platform_online'] ?? '');
            $description = trim($postData['description'] ?? '');
            $startDate = trim($postData['start_date'] ?? '');
            $endDate = trim($postData['end_date'] ?? '');
            $instructor = trim($postData['instructor'] ?? '');
            $quota = !empty($postData['quota']) ? (int)$postData['quota'] : null;
            $link = trim($postData['link'] ?? '');
            $locationLink = trim($postData['location_link'] ?? '');
            $category = trim($postData['category'] ?? '');
            $status = 1;

            if (empty($title) || empty($startDate) || empty($endDate)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Başlık, Başlangıç ve Bitiş tarihleri zorunludur.'];
                $this->redirectBack();
            }

            // Image Upload
            $imagePath = null;
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/bootcamps/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($ext, $allowed)) {
                    $filename = uniqid('bc_') . '.' . $ext;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                        $imagePath = 'uploads/bootcamps/' . $filename;
                    }
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Sadece resim dosyaları yüklenebilir.'];
                    $this->redirectBack();
                }
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO app_bootcamps (app_id, department_id, title, type, location, description, start_date, end_date, instructor, quota, link, location_link, category, image_path, status, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $this->app['id'], $this->department['id'], $title, $type, $location, $description, 
                $startDate, $endDate, $instructor, $quota, $link, $locationLink, $category, 
                $imagePath, $status, $this->user['id']
            ]);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Bootcamp başarıyla eklendi.'];
            $this->redirectBack();
        }

        if ($action === 'edit') {
            $id = (int) ($postData['bootcamp_id'] ?? 0);
            $title = trim($postData['title'] ?? '');
            $type = trim($postData['type'] ?? 'physical');
            $location = ($type === 'physical') ? trim($postData['location_or_platform_physical'] ?? '') : trim($postData['location_or_platform_online'] ?? '');
            $description = trim($postData['description'] ?? '');
            $startDate = trim($postData['start_date'] ?? '');
            $endDate = trim($postData['end_date'] ?? '');
            $instructor = trim($postData['instructor'] ?? '');
            $quota = !empty($postData['quota']) ? (int)$postData['quota'] : null;
            $link = trim($postData['link'] ?? '');
            $locationLink = trim($postData['location_link'] ?? '');
            $category = trim($postData['category'] ?? '');

            if (empty($title) || empty($startDate) || empty($endDate)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Başlık, Başlangıç ve Bitiş tarihleri zorunludur.'];
                $this->redirectBack();
            }

            $stmtCheck = $this->pdo->prepare("SELECT image_path FROM app_bootcamps WHERE id = ?");
            $stmtCheck->execute([$id]);
            $existing = $stmtCheck->fetch();

            if (!$existing) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Bootcamp bulunamadı.'];
                $this->redirectBack();
            }

            $imagePath = $existing['image_path'];
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/bootcamps/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($ext, $allowed)) {
                    $filename = uniqid('bc_') . '.' . $ext;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                        if ($imagePath && file_exists(__DIR__ . '/../public/' . $imagePath)) {
                            unlink(__DIR__ . '/../public/' . $imagePath);
                        }
                        $imagePath = 'uploads/bootcamps/' . $filename;
                    }
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Sadece resim dosyaları yüklenebilir.'];
                    $this->redirectBack();
                }
            }

            $stmt = $this->pdo->prepare("
                UPDATE app_bootcamps SET 
                    title=?, type=?, location=?, description=?, start_date=?, end_date=?, 
                    instructor=?, quota=?, link=?, location_link=?, category=?, image_path=?
                WHERE id=?
            ");
            $stmt->execute([
                $title, $type, $location, $description, $startDate, $endDate, 
                $instructor, $quota, $link, $locationLink, $category, $imagePath,
                $id
            ]);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Bootcamp güncellendi.'];
            $this->redirectBack();
        }

        if ($action === 'delete') {
            $id = (int) ($postData['bootcamp_id'] ?? 0);
            $stmtCheck = $this->pdo->prepare("SELECT image_path FROM app_bootcamps WHERE id = ?");
            $stmtCheck->execute([$id]);
            $existing = $stmtCheck->fetch();
            
            if ($existing) {
                if ($existing['image_path'] && file_exists(__DIR__ . '/../public/' . $existing['image_path'])) {
                    unlink(__DIR__ . '/../public/' . $existing['image_path']);
                }
                $stmt = $this->pdo->prepare("DELETE FROM app_bootcamps WHERE id = ?");
                $stmt->execute([$id]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Bootcamp silindi.'];
            }
            $this->redirectBack();
        }

        if ($action === 'toggle_status') {
            $id = (int) ($postData['bootcamp_id'] ?? 0);
            $stmt = $this->pdo->prepare("UPDATE app_bootcamps SET status = NOT status WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Durum güncellendi.'];
            $this->redirectBack();
        }

        if ($action === 'toggle_completed') {
            $id = (int) ($postData['bootcamp_id'] ?? 0);
            $stmtCheck = $this->pdo->prepare("SELECT is_completed FROM app_bootcamps WHERE id = ?");
            $stmtCheck->execute([$id]);
            $bc = $stmtCheck->fetch();

            if ($bc) {
                $newCompleted = !$bc['is_completed'];
                if ($newCompleted) {
                    $stmt = $this->pdo->prepare("UPDATE app_bootcamps SET is_completed = 1, status = 0 WHERE id = ?");
                } else {
                    $stmt = $this->pdo->prepare("UPDATE app_bootcamps SET is_completed = 0, status = 1 WHERE id = ?");
                }
                $stmt->execute([$id]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Tamamlanma durumu güncellendi.'];
            }
            $this->redirectBack();
        }
    }

    private function redirectBack(): void
    {
        header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
        exit;
    }
}
