<?php

class FormModule extends BaseModule
{
    public function render(): string
    {
        $config = $this->getConfig();
        $fields = $config['fields'] ?? [];

        // Handle CSV Export
        if (isset($_GET['action']) && $_GET['action'] === 'export') {
            $this->exportCsv($fields);
        }

        // Handle Excel Export
        if (isset($_GET['action']) && $_GET['action'] === 'export_excel') {
            $this->exportExcel($fields);
        }

        // Fetch entries
        $stmt = $this->pdo->prepare("
            SELECT fe.*, u.name AS user_name, u.email AS user_email 
            FROM form_entries fe 
            LEFT JOIN users u ON u.id = fe.user_id 
            WHERE fe.app_id = ? 
            ORDER BY fe.created_at DESC
        ");
        $stmt->execute([$this->app['id']]);
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
        if ($action === 'save_config') {
            $newConfig = $postData['config'] ?? '';
            $newName = $postData['name'] ?? null;
            $newDesc = $postData['description'] ?? null;
            
            try {
                if ($newName !== null) {
                    $stmt = $this->pdo->prepare("UPDATE apps SET config = ?, name = ?, description = ? WHERE id = ?");
                    $stmt->execute([$newConfig, $newName, $newDesc, $this->app['id']]);
                } else {
                    $stmt = $this->pdo->prepare("UPDATE apps SET config = ? WHERE id = ?");
                    $stmt->execute([$newConfig, $this->app['id']]);
                }
                
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
            }
            exit;
        }

        // Başlık Görseli Yükleme ve Optimizasyonu
        if ($action === 'upload_banner') {
            if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['banner'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    $uploadDir = __DIR__ . '/../uploads/forms/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $fileName = 'banner_' . uniqid() . '.' . $ext;
                    $target = $uploadDir . $fileName;
                    if (move_uploaded_file($file['tmp_name'], $target)) {
                        $this->optimizeImageFile($target, $ext);
                        echo json_encode(['success' => true, 'url' => 'uploads/forms/' . $fileName]);
                        exit;
                    }
                }
            }
            echo json_encode(['success' => false, 'message' => 'Görsel yüklenemedi.']);
            exit;
        }

        if ($action === 'submit') {
            $this->processFormSubmission($postData, false);
        }

        // Silme yetkisi
        if ($action === 'delete') {
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
        if ($isPublic && empty($postData['kvkk_consent'])) {
            $this->redirectBackError('KVKK Açık Rıza Beyanı\'nı onaylamanız gerekmektedir.', $isPublic);
        }

        $config = $this->getConfig();
        $fields = $config['fields'] ?? [];
        $submittedData = [];

        foreach ($fields as $field) {
            $fieldName = $field['name'] ?? '';
            $fieldType = $field['type'] ?? 'text';
            if (empty($fieldName)) continue;

            $rawVal = $postData[$fieldName] ?? '';
            $val = is_array($rawVal) ? $rawVal : trim($rawVal);

            if ($fieldType === 'phone') {
                $code = $postData[$fieldName . '_code'] ?? '';
                if (!empty($val)) $val = trim($code . ' ' . $val);
            }

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

            // Education Alanı Mantığı
            if ($fieldType === 'education' && is_array($val)) {
                $uni = $val['universite'] ?? '';
                if ($uni === 'Diğer') {
                    $val['universite'] = $val['universite_other'] ?? '';
                    $val['fakulte'] = $val['fakulte_other'] ?? '';
                    $val['bolum'] = $val['bolum_other'] ?? '';
                }
                unset($val['universite_other'], $val['fakulte_other'], $val['bolum_other']);
                
                // Now check if required
                if (!empty($field['required'])) {
                    if (empty(trim($val['universite'])) || empty(trim($val['fakulte'])) || empty(trim($val['bolum'])) || empty(trim($val['sinif']))) {
                        $this->redirectBackError(($field['label'] ?? $fieldName) . ' alanındaki eğitim bilgileri eksiksiz doldurulmalıdır.', $isPublic);
                    }
                }
            } else if (!empty($field['required']) && empty($val)) {
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
                    if (is_string($val) && !preg_match('/^[0-9\s\+\-\(\)]+$/', $val)) {
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
            $this->user['id'] ?? null,
            json_encode($submittedData, JSON_UNESCAPED_UNICODE)
        ]);

        if ($isPublic) {
            $this->autoRegisterMember($submittedData, $fields);
            header("Location: " . PUBLIC_URL . "/f/{$this->app['slug']}?success=1");
        } else {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => $config['settings']['success_message'] ?? 'Form başarıyla gönderildi.'
            ];
            header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
        }
        exit;
    }

    private function autoRegisterMember(array $submittedData, array $fields)
    {
        $memberData = [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'phone' => '',
            'student_id' => '',
            'university' => '',
            'faculty' => '',
            'department' => '',
            'grade' => ''
        ];

        foreach ($fields as $field) {
            $name = $field['name'] ?? '';
            $type = $field['type'] ?? '';
            $label = $field['label'] ?? '';
            $val = $submittedData[$name] ?? '';

            if (empty($val)) continue;

            if ($type === 'text' && (stripos($label, 'Ad Soyad') !== false || stripos($label, 'İsim') !== false || stripos($label, 'Adı') !== false)) {
                $parts = explode(' ', trim((string)$val));
                $memberData['last_name'] = array_pop($parts);
                $memberData['first_name'] = implode(' ', $parts);
                if (empty($memberData['first_name'])) {
                    $memberData['first_name'] = $memberData['last_name'];
                    $memberData['last_name'] = '';
                }
            } elseif ($type === 'email') {
                $memberData['email'] = trim((string)$val);
            } elseif ($type === 'phone') {
                $memberData['phone'] = trim((string)$val);
            } elseif ($type === 'number' && stripos($label, 'Numara') !== false) {
                $memberData['student_id'] = trim((string)$val);
            } elseif ($type === 'education' && is_array($val)) {
                $memberData['university'] = trim($val['universite'] ?? '');
                $memberData['faculty'] = trim($val['fakulte'] ?? '');
                $memberData['department'] = trim($val['bolum'] ?? '');
                $memberData['grade'] = trim($val['sinif'] ?? '');
            }
        }

        if (empty($memberData['email']) || empty($memberData['first_name'])) {
            return;
        }

        $stmt = $this->pdo->prepare("SELECT id FROM apps WHERE department_id = ? AND module_type = 'members' LIMIT 1");
        $stmt->execute([$this->department['id']]);
        $membersApp = $stmt->fetch();

        if ($membersApp) {
            $appId = $membersApp['id'];
            // Check if user exists
            $stmt = $this->pdo->prepare("SELECT id FROM app_members WHERE app_id = ? AND (email = ? OR (student_id = ? AND student_id != '')) LIMIT 1");
            $stmt->execute([$appId, $memberData['email'], $memberData['student_id']]);
            $existing = $stmt->fetch();
            
            if (!$existing) {
                $ins = $this->pdo->prepare("INSERT INTO app_members (app_id, department_id, first_name, last_name, email, student_id, university, faculty, department, grade, phone, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')");
                $ins->execute([
                    $appId, $this->department['id'], 
                    $memberData['first_name'], $memberData['last_name'], 
                    $memberData['email'], $memberData['student_id'],
                    $memberData['university'], $memberData['faculty'], 
                    $memberData['department'], $memberData['grade'], 
                    $memberData['phone']
                ]);
            } else {
                // Update existing
                $upd = $this->pdo->prepare("UPDATE app_members SET 
                    first_name = COALESCE(?, first_name), 
                    last_name = COALESCE(?, last_name), 
                    student_id = COALESCE(?, student_id), 
                    university = COALESCE(?, university), 
                    faculty = COALESCE(?, faculty), 
                    department = COALESCE(?, department), 
                    grade = COALESCE(?, grade), 
                    phone = COALESCE(?, phone) 
                    WHERE id = ?");
                $upd->execute([
                    $memberData['first_name'] ?: null, 
                    $memberData['last_name'] ?: null, 
                    $memberData['student_id'] ?: null,
                    $memberData['university'] ?: null, 
                    $memberData['faculty'] ?: null, 
                    $memberData['department'] ?: null, 
                    $memberData['grade'] ?: null, 
                    $memberData['phone'] ?: null,
                    $existing['id']
                ]);
            }
        }
    }

    private function redirectBackError(string $message, bool $isPublic)
    {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => $message
        ];
        if ($isPublic) {
            header("Location: " . PUBLIC_URL . "/f/{$this->app['slug']}");
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
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $this->optimizeImageFile($target, $ext);
            }
            return 'uploads/forms/' . $fileName;
        }

        return false;
    }

    private function optimizeImageFile(string $filePath, string $ext): void
    {
        if (!function_exists('imagecreatefromjpeg')) return; // GD not installed

        list($width, $height) = @getimagesize($filePath);
        if (!$width || !$height) return;

        // Resize if wider than 1200px
        $newWidth = $width;
        $newHeight = $height;
        if ($width > 1200) {
            $newWidth = 1200;
            $newHeight = (int)(($height / $width) * 1200);
        }

        $srcImage = null;
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $srcImage = @imagecreatefromjpeg($filePath);
                break;
            case 'png':
                $srcImage = @imagecreatefrompng($filePath);
                break;
            case 'webp':
                $srcImage = @imagecreatefromwebp($filePath);
                break;
            case 'gif':
                $srcImage = @imagecreatefromgif($filePath);
                break;
        }

        if (!$srcImage) return;

        $dstImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Handle transparency for PNG/WebP/GIF
        if ($ext === 'png' || $ext === 'webp' || $ext === 'gif') {
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
            $transparent = imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
            imagefilledrectangle($dstImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Save optimized image back
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($dstImage, $filePath, 75);
                break;
            case 'png':
                imagepng($dstImage, $filePath, 6);
                break;
            case 'webp':
                imagewebp($dstImage, $filePath, 75);
                break;
            case 'gif':
                imagegif($dstImage, $filePath);
                break;
        }

        imagedestroy($srcImage);
        imagedestroy($dstImage);
    }

    private function exportExcel(array $fields): void
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

        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Content-Disposition: attachment; filename=form_export_" . $this->app['slug'] . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body>';
        echo '<table border="1">';
        echo '<tr>';
        echo '<th>Tarih</th>';
        echo '<th>Gönderen (Ad)</th>';
        echo '<th>Gönderen (E-posta)</th>';
        echo '<th>Kayıt Tipi</th>';
        foreach ($fields as $field) {
            echo '<th>' . htmlspecialchars($field['label'] ?? $field['name']) . '</th>';
        }
        echo '</tr>';

        foreach ($entries as $entry) {
            $data = json_decode($entry['data'], true);
            $isGuest = empty($entry['user_id']);
            echo '<tr>';
            echo '<td>' . htmlspecialchars($entry['created_at']) . '</td>';
            echo '<td>' . htmlspecialchars($isGuest ? 'Misafir' : $entry['user_name']) . '</td>';
            echo '<td>' . htmlspecialchars($isGuest ? ($data['_meta']['ip'] ?? 'Bilinmiyor') : $entry['user_email']) . '</td>';
            echo '<td>' . htmlspecialchars($isGuest ? 'Public' : 'Member') . '</td>';
            foreach ($fields as $field) {
                $fname = $field['name'];
                $val = $data[$fname] ?? '';
                if (is_array($val)) {
                    if (isset($val['universite'])) {
                        $val = trim($val['universite'] . ', ' . $val['fakulte'] . ', ' . $val['bolum'] . ' (' . $val['sinif'] . ')', ' ,()');
                    } else {
                        $val = implode(', ', $val);
                    }
                }
                echo '<td>' . htmlspecialchars((string)$val) . '</td>';
            }
            echo '</tr>';
        }
        echo '</table></body></html>';
        exit;
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
                    if (isset($val['universite'])) {
                        $val = trim($val['universite'] . ', ' . $val['fakulte'] . ', ' . $val['bolum'] . ' (' . $val['sinif'] . ')', ' ,()');
                    } else {
                        $val = implode(', ', $val); // Checkbox etc.
                    }
                }
                $row[] = $val;
            }
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }
}
