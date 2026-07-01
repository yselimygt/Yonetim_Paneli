<?php

class PartnerModule extends BaseModule
{
    public function render(): string
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*, u.name AS creator_name 
            FROM app_partners p
            JOIN users u ON u.id = p.created_by
            WHERE p.app_id = ? AND p.department_id = ?
            ORDER BY p.venue_name ASC
        ");
        $stmt->execute([$this->app['id'], $this->department['id']]);
        $partners = $stmt->fetchAll();

        $canEdit = $this->isDirectorOrAdmin();

        return $this->renderView('partner', compact('partners', 'canEdit'));
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
            $venueName = trim($postData['venue_name'] ?? '');
            $address = trim($postData['address'] ?? '');
            $mapLink = trim($postData['map_link'] ?? '');
            $discountDetail = trim($postData['discount_detail'] ?? '');
            $contactPerson = trim($postData['contact_person'] ?? '');
            $contactPhone = trim($postData['contact_phone'] ?? '');
            $status = isset($postData['status']) ? 1 : 0;

            if (empty($venueName)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Mekan Adı zorunludur.'];
                $this->redirectBack();
            }

            // Handle Image Upload
            $imagePath = null;
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/partners/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($ext, $allowed)) {
                    $filename = uniqid('prt_') . '.' . $ext;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                        $imagePath = 'uploads/partners/' . $filename;
                    }
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Sadece resim dosyaları yüklenebilir.'];
                    $this->redirectBack();
                }
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO app_partners (app_id, department_id, venue_name, address, map_link, discount_detail, contact_person, contact_phone, image_path, status, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $this->app['id'],
                $this->department['id'],
                $venueName,
                $address,
                $mapLink,
                $discountDetail,
                $contactPerson,
                $contactPhone,
                $imagePath,
                $status,
                $this->user['id']
            ]);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Anlaşmalı Mekan başarıyla eklendi.'];
            $this->redirectBack();
        }

        if ($action === 'edit') {
            $partnerId = (int) ($postData['partner_id'] ?? 0);
            $venueName = trim($postData['venue_name'] ?? '');
            $address = trim($postData['address'] ?? '');
            $mapLink = trim($postData['map_link'] ?? '');
            $discountDetail = trim($postData['discount_detail'] ?? '');
            $contactPerson = trim($postData['contact_person'] ?? '');
            $contactPhone = trim($postData['contact_phone'] ?? '');

            if (empty($venueName)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Mekan Adı zorunludur.'];
                $this->redirectBack();
            }

            $stmtCheck = $this->pdo->prepare("SELECT image_path FROM app_partners WHERE id = ? AND app_id = ?");
            $stmtCheck->execute([$partnerId, $this->app['id']]);
            $existing = $stmtCheck->fetch();

            if (!$existing) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Mekan bulunamadı.'];
                $this->redirectBack();
            }

            $imagePath = $existing['image_path'];
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/partners/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($ext, $allowed)) {
                    $filename = uniqid('prt_') . '.' . $ext;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                        if ($imagePath && file_exists(__DIR__ . '/../public/' . $imagePath)) {
                            unlink(__DIR__ . '/../public/' . $imagePath);
                        }
                        $imagePath = 'uploads/partners/' . $filename;
                    }
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Sadece resim dosyaları yüklenebilir.'];
                    $this->redirectBack();
                }
            }

            $stmt = $this->pdo->prepare("
                UPDATE app_partners SET 
                    venue_name=?, address=?, map_link=?, discount_detail=?, 
                    contact_person=?, contact_phone=?, image_path=?
                WHERE id=? AND app_id=?
            ");
            $stmt->execute([
                $venueName, $address, $mapLink, $discountDetail,
                $contactPerson, $contactPhone, $imagePath,
                $partnerId, $this->app['id']
            ]);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Anlaşmalı Mekan başarıyla güncellendi.'];
            $this->redirectBack();
        }

        if ($action === 'delete') {
            $partnerId = (int) ($postData['partner_id'] ?? 0);
            
            $stmtCheck = $this->pdo->prepare("SELECT image_path FROM app_partners WHERE id = ? AND app_id = ?");
            $stmtCheck->execute([$partnerId, $this->app['id']]);
            $existing = $stmtCheck->fetch();
            
            if ($existing) {
                if ($existing['image_path']) {
                    $file = __DIR__ . '/../public/' . $existing['image_path'];
                    if (file_exists($file)) unlink($file);
                }
                $stmt = $this->pdo->prepare("DELETE FROM app_partners WHERE id = ?");
                $stmt->execute([$partnerId]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Mekan silindi.'];
            }
            $this->redirectBack();
        }

        if ($action === 'toggle_status') {
            $partnerId = (int) ($postData['partner_id'] ?? 0);
            $stmt = $this->pdo->prepare("UPDATE app_partners SET status = NOT status WHERE id = ? AND app_id = ?");
            $stmt->execute([$partnerId, $this->app['id']]);
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
