<?php

class FormModule extends BaseModule
{
    public function render(): string
    {
        $config = $this->getConfig();
        $fields = $config['fields'] ?? [];

        // Handle CSV Export
        if (isset($_GET['action']) && $_GET['action'] === 'export' && $this->isDirectorOrAdmin()) {
            $this->exportCsv($fields);
        }

        // Fetch entries
        if ($this->isDirectorOrAdmin()) {
            $stmt = $this->pdo->prepare("
                SELECT fe.*, u.name AS user_name, u.email AS user_email 
                FROM form_entries fe 
                LEFT JOIN users u ON u.id = fe.user_id 
                WHERE fe.app_id = ? 
                ORDER BY fe.created_at DESC
            ");
            $stmt->execute([$this->app['id']]);
        } else {
            $stmt = $this->pdo->prepare("
                SELECT fe.*, u.name AS user_name, u.email AS user_email 
                FROM form_entries fe 
                JOIN users u ON u.id = fe.user_id 
                WHERE fe.app_id = ? AND fe.user_id = ? 
                ORDER BY fe.created_at DESC
            ");
            $stmt->execute([$this->app['id'], $this->user['id'] ?? 0]);
        }
        $entries = $stmt->fetchAll();

        $settings = $config['settings'] ?? ['is_public' => false];
        return $this->renderView('form', compact('fields', 'entries', 'settings'));
    }

    public function renderPublic(): string
    {
        $config = $this->getConfig();
        
        // Eğer public ayarı yoksa veya false ise
        if (empty($config['settings']['is_public'])) {
            return "<div style='text-align:center; padding: 50px;'><h2 style='color:var(--danger);'>Erişim Reddedildi</h2><p>Bu form kamuya açık değildir.</p></div>";
        }

        $fields = $config['fields'] ?? [];
        return $this->renderView('form_public', compact('fields', 'config'));
    }

    public function handlePostPublic(array $postData): void
    {
        $config = $this->getConfig();
        if (empty($config['settings']['is_public'])) {
            die("Bu form kamuya açık değildir.");
        }

        $action = $postData['action'] ?? '';
        if ($action === 'submit') {
            $this->processFormSubmission($postData, true);
        }
    }

    public function handlePost(array $postData): void
    {
        $action = $postData['action'] ?? '';

        // Form Ayarlarını (Builder) Kaydetme İşlemi (Phase 3'te frontend yapacak, backend şimdilik desteklesin)
        if ($action === 'save_config' && $this->isDirectorOrAdmin()) {
            $newConfig = $postData['config'] ?? '';
            $stmt = $this->pdo->prepare("UPDATE apps SET config = ? WHERE id = ?");
            $stmt->execute([$newConfig, $this->app['id']]);
            echo json_encode(['success' => true]);
            exit;
        }

        if ($action === 'submit') {
            $this->processFormSubmission($postData, false);
        }

        // Silme yetkisi (Sadece direktör)
        if ($action === 'delete' && $this->isDirectorOrAdmin()) {
            $entryId = (int) ($postData['entry_id'] ?? 0);
            
            // Delete associated files first (if any)
            $stmt = $this->pdo->prepare("SELECT data FROM form_entries WHERE id = ? AND app_id = ?");
            $stmt->execute([$entryId, $this->app['id']]);
            $entry = $stmt->fetch();
            if ($entry) {
                $data = json_decode($entry['data'], true);
                if (is_array($data)) {
                    foreach ($data as $val) {
                        if (is_string($val) && strpos($val, 'uploads/forms/') !== false && file_exists(__DIR__ . '/../' . $val)) {
                            unlink(__DIR__ . '/../' . $val);
                        }
                    }
                }
            }

            $stmt = $this->pdo->prepare("DELETE FROM form_entries WHERE id = ? AND app_id = ?");
            $stmt->execute([$entryId, $this->app['id']]);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Form yanıtı başarıyla silindi.'
            ];
            header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
            exit;
        }
    }

    private function processFormSubmission(array $postData, bool $isPublic)
    {
        $config = $this->getConfig();
        $fields = $config['fields'] ?? [];
        $submittedData = [];

        foreach ($fields as $field) {
            $fieldName = $field['name'] ?? '';
            $fieldType = $field['type'] ?? 'text';
            if (empty($fieldName)) continue;

            $val = trim($postData[$fieldName] ?? '');

            // Dosya yükleme alanı
            if ($fieldType === 'file') {
                if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] !== UPLOAD_ERR_NO_FILE) {
                    $val = $this->handleFileUpload($field, $_FILES[$fieldName], $isPublic);
                    if ($val === false) {
                        $this->redirectBackError(($field['label'] ?? $fieldName) . ' dosya yükleme kurallarına uymuyor.', $isPublic);
                    }
                } else {
                    $val = '';
                }
            }

            if (!empty($field['required']) && empty($val)) {
                $this->redirectBackError(($field['label'] ?? $fieldName) . ' alanı zorunludur.', $isPublic);
            }

            // Validasyonlar
            if (!empty($val)) {
                if ($fieldType === 'email' && !filter_var($val, FILTER_VALIDATE_EMAIL)) {
                    $this->redirectBackError(($field['label'] ?? $fieldName) . ' geçerli bir e-posta adresi olmalıdır.', $isPublic);
                }
                if ($fieldType === 'tc' && !$this->validateTC($val)) {
                    $this->redirectBackError(($field['label'] ?? $fieldName) . ' geçersiz T.C. Kimlik Numarası.', $isPublic);
                }
                if ($fieldType === 'phone' || $fieldType === 'number') {
                    // Sadece numeric, bosluk ve + icerebilir (Phone/Number basit check)
                    if (!preg_match('/^[0-9\s\+\-\(\)]+$/', $val)) {
                        $this->redirectBackError(($field['label'] ?? $fieldName) . ' sadece sayısal karakterler içerebilir.', $isPublic);
                    }
                }
            }

            $submittedData[$fieldName] = $val;
        }

        // Ziyaretçi bilgileri
        if ($isPublic) {
            $submittedData['_meta'] = [
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                'submitted_at' => date('Y-m-d H:i:s')
            ];
        }

        // Insert to database
        $stmt = $this->pdo->prepare("
            INSERT INTO form_entries (app_id, department_id, user_id, data) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $this->app['id'],
            $this->department['id'],
            $isPublic ? null : ($this->user['id'] ?? null),
            json_encode($submittedData, JSON_UNESCAPED_UNICODE)
        ]);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => $config['settings']['success_message'] ?? 'Form başarıyla gönderildi.'
        ];

        if ($isPublic) {
            header("Location: " . BASE_URL . "/f/{$this->department['slug']}/{$this->app['slug']}");
        } else {
            header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
        }
        exit;
    }

    private function redirectBackError(string $message, bool $isPublic)
    {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => $message
        ];
        if ($isPublic) {
            header("Location: " . BASE_URL . "/f/{$this->department['slug']}/{$this->app['slug']}");
        } else {
            header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
        }
        exit;
    }

    private function validateTC(string $tc): bool
    {
        if (strlen($tc) != 11 || !is_numeric($tc) || $tc[0] == '0') return false;

        $digits = str_split($tc);
        $oddSum = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8];
        $evenSum = $digits[1] + $digits[3] + $digits[5] + $digits[7];

        $d10 = ($oddSum * 7 - $evenSum) % 10;
        $d11 = ($oddSum + $evenSum + $digits[9]) % 10;

        if ($digits[9] != $d10 || $digits[10] != $d11) return false;

        return true;
    }

    private function handleFileUpload(array $field, array $fileInfo, bool $isPublic)
    {
        if ($fileInfo['error'] !== UPLOAD_ERR_OK) return false;

        $maxSizeMB = (float)($field['max_size'] ?? 5);
        if ($fileInfo['size'] > $maxSizeMB * 1024 * 1024) return false;

        $ext = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
        $allowedExts = $field['allowed_exts'] ?? ['jpg', 'png', 'pdf'];
        
        if (!in_array($ext, $allowedExts)) return false;

        // Güvenlik: Gerçek MIME type kontrolü
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $realMime = finfo_file($finfo, $fileInfo['tmp_name']);
        finfo_close($finfo);

        // Tehlikeli MIME tipleri reddet (PHP, script dosyaları vb.)
        $dangerousMimes = ['application/x-php', 'application/x-httpd-php', 'text/x-php', 'application/x-executable'];
        if (in_array($realMime, $dangerousMimes)) return false;

        $uploadDir = __DIR__ . '/../uploads/forms/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = uniqid('file_') . '.' . $ext;
        $target = $uploadDir . $fileName;

        if (move_uploaded_file($fileInfo['tmp_name'], $target)) {
            return 'uploads/forms/' . $fileName;
        }

        return false;
    }

    private function exportCsv(array $fields): void
    {
        $stmt = $this->pdo->prepare("
            SELECT fe.*, u.name AS user_name, u.email AS user_email 
            FROM form_entries fe 
            LEFT JOIN users u ON u.id = fe.user_id 
            WHERE fe.app_id = ? 
            ORDER BY fe.created_at DESC
        ");
        $stmt->execute([$this->app['id']]);
        $entries = $stmt->fetchAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=form_export_' . $this->app['slug'] . '.csv');

        $output = fopen('php://output', 'w');
        
        // Add UTF-8 BOM for Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header row
        $headers = ['Tarih', 'Gönderen (Ad)', 'Gönderen (E-posta)', 'Kayıt Tipi'];
        foreach ($fields as $field) {
            $headers[] = $field['label'] ?? $field['name'];
        }
        fputcsv($output, $headers);

        // Data rows
        foreach ($entries as $entry) {
            $data = json_decode($entry['data'], true);
            $isGuest = empty($entry['user_id']);
            $row = [
                $entry['created_at'],
                $isGuest ? 'Misafir' : $entry['user_name'],
                $isGuest ? ($data['_meta']['ip'] ?? 'Bilinmiyor') : $entry['user_email'],
                $isGuest ? 'Public' : 'Member'
            ];
            foreach ($fields as $field) {
                $fieldName = $field['name'] ?? '';
                $val = $data[$fieldName] ?? '';
                if (is_array($val)) {
                    $val = implode(', ', $val); // Checkbox etc.
                }
                $row[] = $val;
            }
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }
}
