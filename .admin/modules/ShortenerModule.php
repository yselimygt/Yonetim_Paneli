<?php

require_once __DIR__ . '/BaseModule.php';

class ShortenerModule extends BaseModule
{
    public function render(): string
    {
        $config = $this->getConfig();
        $links = $config['links'] ?? [];

        return $this->renderView('shortener_admin', compact('links'));
    }

    public function handlePost(array $postData): void
    {
        $config = $this->getConfig();
        $links = $config['links'] ?? [];

        if (isset($postData['action']) && $postData['action'] === 'delete') {
            $codeToDelete = $postData['code'] ?? '';
            $links = array_filter($links, fn($l) => $l['code'] !== $codeToDelete);
            $links = array_values($links);
        } else {
            $url = trim($postData['url'] ?? '');
            $customCode = trim($postData['custom_code'] ?? '');
            $title = trim($postData['title'] ?? '');

            if ($url !== '') {
                // Ensure URL has http/https
                if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                    $url = "http://" . $url;
                }

                $code = $customCode;
                if ($code === '') {
                    // Generate random code (6 chars)
                    $code = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);
                } else {
                    // Sanitize custom code
                    $code = preg_replace('/[^a-zA-Z0-9_-]/', '', $code);
                }

                // Check for duplicate code in THIS department (global duplicate checking is harder with JSON, 
                // but we assume reasonable uniqueness or the user will notice)
                // To be safe, we only check this department's links
                $exists = false;
                foreach ($links as $l) {
                    if ($l['code'] === $code) {
                        $exists = true;
                        break;
                    }
                }

                if ($exists) {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => "Bu kısaltma kodu zaten kullanımda. Lütfen başka bir kod deneyin."];
                } else {
                    array_unshift($links, [
                        'code' => $code,
                        'url' => $url,
                        'title' => $title,
                        'clicks' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    $_SESSION['success_msg'] = "Kısa link başarıyla oluşturuldu.";
                }
            }
        }

        $config['links'] = $links;

        $stmt = $this->pdo->prepare("UPDATE apps SET config = ? WHERE id = ? AND department_id = ?");
        $stmt->execute([json_encode($config), $this->app['id'], $this->department['id']]);

        header("Location: " . BASE_URL . "/panel/" . $this->department['slug'] . "/apps/" . $this->app['slug']);
        exit;
    }

    public function renderPublic(): void
    {
        // Public interface is handled by ShortenerController routing (/go/{code})
        http_response_code(404);
        die("Sayfa bulunamadı.");
    }
}
