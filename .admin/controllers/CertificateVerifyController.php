<?php

class CertificateVerifyController extends Controller
{
    public function verify(string $certNo): void
    {
        $pdo  = Database::getInstance();
        $certNo = preg_replace('/[^A-Z0-9]/', '', strtoupper(trim($certNo)));

        $stmt = $pdo->prepare("
            SELECT c.*, t.title AS template_title, t.background_path, t.fields_json, t.canvas_width, t.canvas_height,
                   d.name AS dept_name, d.slug AS dept_slug
            FROM app_certificates c
            JOIN app_certificate_templates t ON t.id = c.template_id
            JOIN departments d ON d.id = c.department_id
            WHERE c.certificate_no = ?
        ");
        $stmt->execute([$certNo]);
        $row = $stmt->fetch();

        if (!$row) {
            http_response_code(404);
            echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Sertifika Bulunamadı</title></head><body style="font-family:sans-serif;text-align:center;padding:80px;background:#0d1117;color:#e6edf3;"><h1>❌ Sertifika Bulunamadı</h1><p>Bu doğrulama kodu geçerli bir sertifikaya ait değil.</p></body></html>';
            return;
        }

        $cert       = $row;
        $template   = [
            'title'           => $row['template_title'],
            'background_path' => $row['background_path'],
            'fields_json'     => $row['fields_json'],
            'canvas_width'    => $row['canvas_width'],
            'canvas_height'   => $row['canvas_height'],
        ];
        $department = ['name' => $row['dept_name'], 'slug' => $row['dept_slug']];

        require_once __DIR__ . '/../views/certificate_verify.php';
    }
}
