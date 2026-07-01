<?php
require_once __DIR__ . '/../core/Controller.php';

class ShortenerController extends Controller
{
    public function redirect(string $code): void
    {
        $db = Database::getInstance();
        $code = trim($code);
        
        if (empty($code)) {
            http_response_code(404);
            die("Geçersiz link.");
        }

        // Güvenlik: LIKE wildcard karakterlerini escape et (SQL injection önlemi)
        $safeCode = str_replace(['%', '_', '\\'], ['\\%', '\\_', '\\\\'], $code);
        $stmt = $db->prepare("SELECT id, config FROM apps WHERE module_type = 'shortener' AND config LIKE ? ESCAPE '\\'");
        $stmt->execute(['%"code":"' . $safeCode . '"%']);
        $apps = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $foundLink = null;
        $appId = null;
        $appConfig = null;

        foreach ($apps as $app) {
            $config = json_decode($app['config'] ?? '{}', true);
            if (!empty($config['links']) && is_array($config['links'])) {
                foreach ($config['links'] as &$link) {
                    if ($link['code'] === $code) {
                        $foundLink = $link;
                        
                        // Tıklama sayısını artır
                        $link['clicks'] = ($link['clicks'] ?? 0) + 1;
                        
                        $appId = $app['id'];
                        $appConfig = $config;
                        break 2;
                    }
                }
            }
        }

        if ($foundLink && $appId) {
            // Güvenlik: Open Redirect koruması — sadece http/https protokolüne izin ver
            $url = $foundLink['url'];
            $parsed = parse_url($url);
            $scheme = strtolower($parsed['scheme'] ?? '');
            if (!in_array($scheme, ['http', 'https'], true)) {
                http_response_code(400);
                die("Geçersiz hedef URL.");
            }

            // Config'i güncelle (tıklama sayısını veritabanına yaz)
            $updateStmt = $db->prepare("UPDATE apps SET config = ? WHERE id = ?");
            $updateStmt->execute([json_encode($appConfig), $appId]);

            // Yönlendir
            header("Location: " . $url, true, 302);
            exit;
        }

        http_response_code(404);
        die("Kısa link bulunamadı veya süresi dolmuş.");
    }
}

