<?php

class SponsorModule extends BaseModule
{
    public function render(): string
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM sponsors 
            ORDER BY created_at DESC
        ");
        $stmt->execute();
        $sponsors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->renderView('sponsor', compact('sponsors'));
    }

    public function handlePost(array $postData): void
    {
        $action = $postData['action'] ?? '';

        if ($action === 'add' || $action === 'edit') {
            $name            = trim($postData['name'] ?? '');
            $contact_person  = trim($postData['contact_person'] ?? '');
            $email           = trim($postData['email'] ?? '');
            $phone           = trim($postData['phone'] ?? '');
            $sponsorship_type= trim($postData['sponsorship_type'] ?? '');
            $status          = trim($postData['status'] ?? 'active');
            $notes           = trim($postData['notes'] ?? '');
            $sponsorId       = (int)($postData['sponsor_id'] ?? 0);

            if (empty($name)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Sponsor adı zorunludur.'];
                header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
                exit;
            }

            // Handle Logo Upload
            $logoUrl = null;
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/sponsors/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = time() . '_' . basename($_FILES['logo']['name']);
                $targetFile = $uploadDir . $fileName;
                
                $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
                
                if (in_array($fileType, $allowedTypes)) {
                    if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile)) {
                        $logoUrl = 'public/uploads/sponsors/' . $fileName;
                    }
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Geçersiz dosya formatı. Sadece resim dosyaları kabul edilir.'];
                    header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
                    exit;
                }
            }

            if ($action === 'add') {
                $stmt = $this->pdo->prepare("
                    INSERT INTO sponsors (app_id, department_id, created_by, name, contact_person, email, phone, logo_url, sponsorship_type, status, notes)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $this->app['id'],
                    $this->department['id'],
                    $this->user['id'],
                    $name,
                    $contact_person,
                    $email,
                    $phone,
                    $logoUrl,
                    $sponsorship_type,
                    $status,
                    $notes
                ]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Sponsor başarıyla eklendi.'];
            } else { // edit
                // Retrieve existing sponsor to check for old logo if needed
                $stmtCheck = $this->pdo->prepare("SELECT logo_url FROM sponsors WHERE id = ?");
                $stmtCheck->execute([$sponsorId]);
                $existingSponsor = $stmtCheck->fetch();

                if (!$logoUrl && $existingSponsor) {
                    $logoUrl = $existingSponsor['logo_url'];
                }

                $stmt = $this->pdo->prepare("
                    UPDATE sponsors 
                    SET name=?, contact_person=?, email=?, phone=?, logo_url=?, sponsorship_type=?, status=?, notes=?
                    WHERE id=?
                ");
                $stmt->execute([
                    $name, $contact_person, $email, $phone, $logoUrl, $sponsorship_type, $status, $notes,
                    $sponsorId
                ]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Sponsor güncellendi.'];
            }

            header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
            exit;
        }

        if ($action === 'delete') {
            $sponsorId = (int)($postData['sponsor_id'] ?? 0);
            
            // Allow creator or director/admin to delete
            $stmtCheck = $this->pdo->prepare("SELECT created_by, logo_url FROM sponsors WHERE id = ?");
            $stmtCheck->execute([$sponsorId]);
            $sponsor = $stmtCheck->fetch();

            if ($sponsor && ($this->isDirectorOrAdmin() || $sponsor['created_by'] === $this->user['id'])) {
                // remove logo if exists
                if (!empty($sponsor['logo_url'])) {
                    $logoPath = __DIR__ . '/../' . $sponsor['logo_url'];
                    if (file_exists($logoPath)) {
                        unlink($logoPath);
                    }
                }

                $stmt = $this->pdo->prepare("DELETE FROM sponsors WHERE id = ?");
                $stmt->execute([$sponsorId]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Sponsor silindi.'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Bu sponsoru silmek için yetkiniz yok.'];
            }

            header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
            exit;
        }
    }
}
