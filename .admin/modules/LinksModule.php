<?php

require_once __DIR__ . '/BaseModule.php';

class LinksModule extends BaseModule
{
    public function render(): string
    {
        $configStr = $this->app['config'];
        if (empty($configStr) || $configStr === '[]' || $configStr === '{}') {
            $stmt = $this->pdo->prepare("SELECT config FROM apps WHERE module_type = 'links' AND config IS NOT NULL AND config != '' AND config != '{}' LIMIT 1");
            $stmt->execute();
            $existing = $stmt->fetchColumn();
            if ($existing) {
                $configStr = $existing;
            }
        }

        $config = json_decode($configStr ?? '{}', true) ?: [];
        $profile = $config['profile'] ?? [
            'name' => 'Genel Bağlantılar',
            'bio' => '',
            'image' => '',
            'theme_color' => '#3b82f6'
        ];
        $links = $config['links'] ?? [];

        return $this->renderView('links_admin', compact('profile', 'links'));
    }

    public function handlePost(array $postData): void
    {
        $uploadDir = __DIR__ . '/../public/uploads/links/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $profileImage = trim($postData['profile_image'] ?? '');
        $existingProfImg = trim($postData['existing_profile_image'] ?? '');

        if ($profileImage === '' && $existingProfImg !== '' && !preg_match('/^https?:\/\//i', $existingProfImg)) {
            $profileImage = $existingProfImg;
        }

        if (!empty($_FILES['profile_image_file']['name']) && $_FILES['profile_image_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['profile_image_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) {
                $filename = uniqid('profile_') . '.' . $ext;
                if (move_uploaded_file($_FILES['profile_image_file']['tmp_name'], $uploadDir . $filename)) {
                    $profileImage = 'uploads/links/' . $filename;
                }
            }
        }

        $profile = [
            'name' => trim($postData['profile_name'] ?? ''),
            'bio' => trim($postData['profile_bio'] ?? ''),
            'image' => $profileImage,
            'theme_color' => trim($postData['theme_color'] ?? '#3b82f6')
        ];

        $links = [];
        if (!empty($postData['link_title']) && is_array($postData['link_title'])) {
            foreach ($postData['link_title'] as $index => $title) {
                $title = trim($title);
                $url = trim($postData['link_url'][$index] ?? '');
                $icon = trim($postData['link_icon'][$index] ?? '');
                $existingIcon = trim($postData['existing_link_icon'][$index] ?? '');

                if ($icon === '' && $existingIcon !== '' && !preg_match('/^https?:\/\//i', $existingIcon)) {
                    $icon = $existingIcon;
                }
                
                if (!empty($_FILES['link_icon_file']['name'][$index]) && $_FILES['link_icon_file']['error'][$index] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['link_icon_file']['name'][$index], PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) {
                        $filename = uniqid('icon_') . '.' . $ext;
                        if (move_uploaded_file($_FILES['link_icon_file']['tmp_name'][$index], $uploadDir . $filename)) {
                            $icon = 'uploads/links/' . $filename;
                        }
                    }
                }

                if ($title !== '' && $url !== '') {
                    $links[] = [
                        'title' => $title,
                        'url' => $url,
                        'icon' => $icon
                    ];
                }
            }
        }

        $config = json_encode(['profile' => $profile, 'links' => $links]);

        $stmt = $this->pdo->prepare("UPDATE apps SET config = ? WHERE module_type = 'links'");
        $stmt->execute([$config]);

        $_SESSION['success_msg'] = "Linkler tüm departmanlar için başarıyla güncellendi.";
        header("Location: " . BASE_URL . "/panel/" . $this->department['slug'] . "/apps/" . $this->app['slug']);
        exit;
    }

    public function renderPublic(): void
    {
        if (!$this->app['is_active']) {
            http_response_code(404);
            die("Bu sayfa aktif değil.");
        }

        $configStr = $this->app['config'];
        if (empty($configStr) || $configStr === '[]' || $configStr === '{}') {
            $stmt = $this->pdo->prepare("SELECT config FROM apps WHERE module_type = 'links' AND config IS NOT NULL AND config != '' AND config != '{}' LIMIT 1");
            $stmt->execute();
            $existing = $stmt->fetchColumn();
            if ($existing) {
                $configStr = $existing;
            }
        }

        $config = json_decode($configStr ?? '{}', true) ?: [];
        $profile = $config['profile'] ?? [
            'name' => 'Genel Bağlantılar',
            'bio' => '',
            'image' => '',
            'theme_color' => '#3b82f6'
        ];
        $links = $config['links'] ?? [];

        // We bypass the standard admin layout and render a completely standalone view
        extract(compact('profile', 'links'));
        require __DIR__ . '/../views/modules/links_public.php';
        exit;
    }
}
