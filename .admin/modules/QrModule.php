<?php

require_once __DIR__ . '/BaseModule.php';

class QrModule extends BaseModule
{
    public function render(): string
    {
        $config = $this->getConfig();
        $qrs = $config['qrs'] ?? [];

        return $this->renderView('qr_admin', compact('qrs'));
    }

    public function handlePost(array $postData): void
    {
        $config = $this->getConfig();
        $qrs = $config['qrs'] ?? [];

        if (isset($postData['action']) && $postData['action'] === 'delete') {
            $idToDelete = $postData['id'] ?? '';
            $qrs = array_filter($qrs, fn($q) => $q['id'] !== $idToDelete);
            $qrs = array_values($qrs);
        } else {
            $title = trim($postData['title'] ?? '');
            $value = trim($postData['value'] ?? '');
            $colorDark = trim($postData['colorDark'] ?? '#000000');
            $colorLight = trim($postData['colorLight'] ?? '#ffffff');
            $dotsType = trim($postData['dotsType'] ?? 'square');
            $cornersType = trim($postData['cornersType'] ?? 'square');
            
            $logoUrl = '';
            
            // Handle logo file upload
            if (isset($_FILES['logoFile']) && $_FILES['logoFile']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/qr_logos/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileTmp = $_FILES['logoFile']['tmp_name'];
                $fileName = $_FILES['logoFile']['name'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                $allowedExts = ['jpg', 'jpeg', 'png', 'svg'];
                if (in_array($fileExt, $allowedExts)) {
                    $newFileName = uniqid('qr_logo_') . '.' . $fileExt;
                    $destPath = $uploadDir . $newFileName;
                    
                    if (move_uploaded_file($fileTmp, $destPath)) {
                        $logoUrl = BASE_URL . '/public/uploads/qr_logos/' . $newFileName;
                    }
                }
            }

            if ($title !== '' && $value !== '') {
                array_unshift($qrs, [
                    'id' => uniqid('qr_'),
                    'title' => $title,
                    'value' => $value,
                    'colorDark' => $colorDark,
                    'colorLight' => $colorLight,
                    'logoUrl' => $logoUrl,
                    'dotsType' => $dotsType,
                    'cornersType' => $cornersType,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        $config['qrs'] = $qrs;

        $stmt = $this->pdo->prepare("UPDATE apps SET config = ? WHERE id = ? AND department_id = ?");
        $stmt->execute([json_encode($config), $this->app['id'], $this->department['id']]);

        $_SESSION['success_msg'] = "İşlem başarıyla kaydedildi.";
        header("Location: " . BASE_URL . "/panel/" . $this->department['slug'] . "/apps/" . $this->app['slug']);
        exit;
    }

    public function renderPublic(): void
    {
        http_response_code(404);
        die("Bu sayfa bulunamadı.");
    }
}
