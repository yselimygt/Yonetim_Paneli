<?php

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

class CertificateModule extends BaseModule
{
    public function render(): string
    {
        // Şablonları çek
        $stmt = $this->pdo->prepare("
            SELECT t.*, 
                   (SELECT COUNT(*) FROM app_certificates c WHERE c.template_id = t.id) AS cert_count
            FROM app_certificate_templates t
            WHERE t.app_id = ? AND t.department_id = ?
            ORDER BY t.created_at DESC
        ");
        $stmt->execute([$this->app['id'], $this->department['id']]);
        $templates = $stmt->fetchAll();

        // Verilen sertifikaları çek
        $stmt2 = $this->pdo->prepare("
            SELECT c.*, t.title AS template_title, t.background_path, t.fields_json, t.canvas_width, t.canvas_height
            FROM app_certificates c
            JOIN app_certificate_templates t ON t.id = c.template_id
            WHERE c.app_id = ? AND c.department_id = ?
            ORDER BY c.created_at DESC
        ");
        $stmt2->execute([$this->app['id'], $this->department['id']]);
        $certificates = $stmt2->fetchAll();

        // Bağlanabilecek form modüllerini çek
        $stmtForms = $this->pdo->prepare("
            SELECT id, name, slug FROM apps 
            WHERE department_id = ? AND module_type IN ('form','form_manager') AND is_active = 1
        ");
        $stmtForms->execute([$this->department['id']]);
        $availableForms = $stmtForms->fetchAll();

        $canEdit = $this->isDirectorOrAdmin();
        $currentDeptId = $this->department['id'];

        return $this->renderView('certificate', compact(
            'templates', 'certificates', 'availableForms', 'canEdit', 'currentDeptId'
        ));
    }

    public function handlePost(array $postData): void
    {
        $action = $postData['action'] ?? '';
        $canEdit = $this->isDirectorOrAdmin();

        if (!$canEdit && !in_array($action, [])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Bu işlem için yetkiniz yok.'];
            $this->redirectBack();
        }

        // ─── Şablon Kaydet ───────────────────────────────────────────────
        if ($action === 'save_template') {
            $id          = (int)($postData['template_id'] ?? 0);
            $title       = trim($postData['title'] ?? '');
            $description = trim($postData['description'] ?? '');
            $type        = trim($postData['template_type'] ?? 'participation');
            $linkedForm  = !empty($postData['linked_form_app_id']) ? (int)$postData['linked_form_app_id'] : null;
            $fieldsJson  = $postData['fields_json'] ?? '[]';
            $formMap     = $postData['form_field_map'] ?? '{}';
            $canvasW     = (int)($postData['canvas_width'] ?? 1200);
            $canvasH     = (int)($postData['canvas_height'] ?? 850);
            $status      = $postData['status'] ?? 'draft';

            if (empty($title)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Şablon başlığı zorunludur.'];
                $this->redirectBack();
            }

            // Arka plan görseli yükleme
            $bgPath = $postData['existing_bg'] ?? null;
            if (!empty($_FILES['background']['name']) && $_FILES['background']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/certificates/bg/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $ext = strtolower(pathinfo($_FILES['background']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                    $filename = uniqid('cert_bg_') . '.' . $ext;
                    if (move_uploaded_file($_FILES['background']['tmp_name'], $uploadDir . $filename)) {
                        if ($bgPath && file_exists(__DIR__ . '/../public/' . $bgPath)) {
                            unlink(__DIR__ . '/../public/' . $bgPath);
                        }
                        $bgPath = 'uploads/certificates/bg/' . $filename;
                    }
                }
            }

            if ($id > 0) {
                $stmt = $this->pdo->prepare("
                    UPDATE app_certificate_templates SET 
                        title=?, description=?, template_type=?, background_path=?,
                        canvas_width=?, canvas_height=?, fields_json=?, 
                        linked_form_app_id=?, form_field_map=?, status=?
                    WHERE id=? AND department_id=?
                ");
                $stmt->execute([
                    $title, $description, $type, $bgPath,
                    $canvasW, $canvasH, $fieldsJson,
                    $linkedForm, $formMap, $status,
                    $id, $this->department['id']
                ]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Şablon güncellendi.'];
            } else {
                $stmt = $this->pdo->prepare("
                    INSERT INTO app_certificate_templates 
                        (app_id, department_id, title, description, template_type, background_path,
                         canvas_width, canvas_height, fields_json, linked_form_app_id, form_field_map, status, created_by)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)
                ");
                $stmt->execute([
                    $this->app['id'], $this->department['id'], $title, $description, $type, $bgPath,
                    $canvasW, $canvasH, $fieldsJson, $linkedForm, $formMap, $status, $this->user['id']
                ]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Şablon oluşturuldu.'];
            }
            $this->redirectBack();
        }

        // ─── Şablon Sil ──────────────────────────────────────────────────
        if ($action === 'delete_template') {
            $id = (int)($postData['template_id'] ?? 0);
            $row = $this->pdo->prepare("SELECT background_path FROM app_certificate_templates WHERE id=? AND department_id=?");
            $row->execute([$id, $this->department['id']]);
            $tpl = $row->fetch();
            if ($tpl) {
                if ($tpl['background_path'] && file_exists(__DIR__ . '/../public/' . $tpl['background_path'])) {
                    unlink(__DIR__ . '/../public/' . $tpl['background_path']);
                }
                $this->pdo->prepare("DELETE FROM app_certificate_templates WHERE id=?")->execute([$id]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Şablon silindi.'];
            }
            $this->redirectBack();
        }

        // ─── Sertifika Ver ───────────────────────────────────────────────
        if ($action === 'issue') {
            $templateId    = (int)($postData['template_id'] ?? 0);
            $recipientName = trim($postData['recipient_name'] ?? '');
            $recipientEmail= trim($postData['recipient_email'] ?? '');
            $issueDate     = trim($postData['issue_date'] ?? date('Y-m-d'));
            $expiryDate    = !empty($postData['expiry_date']) ? $postData['expiry_date'] : null;
            $notes         = trim($postData['notes'] ?? '');
            $fieldValues   = $postData['field_values'] ?? '{}';

            if (empty($recipientName) || !$templateId) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Alıcı adı ve şablon zorunludur.'];
                $this->redirectBack();
            }

            $certNo = strtoupper(substr(str_replace('-', '', base64_encode(random_bytes(12))), 0, 16));

            $stmt = $this->pdo->prepare("
                INSERT INTO app_certificates 
                    (template_id, app_id, department_id, recipient_name, recipient_email,
                     field_values, certificate_no, issue_date, expiry_date, notes, status, created_by)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
            ");
            $stmt->execute([
                $templateId, $this->app['id'], $this->department['id'],
                $recipientName, $recipientEmail,
                $fieldValues, $certNo,
                $issueDate, $expiryDate, $notes, 'draft', $this->user['id']
            ]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => "Sertifika oluşturuldu. No: {$certNo}"];
            $this->redirectBack();
        }

        // ─── Yayınla ─────────────────────────────────────────────────────
        if ($action === 'publish') {
            $id = (int)($postData['cert_id'] ?? 0);
            $this->pdo->prepare("UPDATE app_certificates SET status='published' WHERE id=? AND department_id=?")
                       ->execute([$id, $this->department['id']]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Sertifika yayınlandı.'];
            $this->redirectBack();
        }

        // ─── İptal Et ────────────────────────────────────────────────────
        if ($action === 'revoke') {
            $id = (int)($postData['cert_id'] ?? 0);
            $this->pdo->prepare("UPDATE app_certificates SET status='revoked' WHERE id=? AND department_id=?")
                       ->execute([$id, $this->department['id']]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Sertifika iptal edildi.'];
            $this->redirectBack();
        }

        // ─── Sil ─────────────────────────────────────────────────────────
        if ($action === 'delete_cert') {
            $id = (int)($postData['cert_id'] ?? 0);
            $this->pdo->prepare("DELETE FROM app_certificates WHERE id=? AND department_id=?")->execute([$id, $this->department['id']]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Sertifika silindi.'];
            $this->redirectBack();
        }

        // ─── PDF İndir ───────────────────────────────────────────────────
        if ($action === 'download_pdf') {
            $id = (int)($postData['cert_id'] ?? 0);
            $this->servePdf($id);
        }
    }

    private function servePdf(int $certId): void
    {
        $stmt = $this->pdo->prepare("
            SELECT c.*, t.background_path, t.fields_json, t.canvas_width, t.canvas_height, t.title AS template_title
            FROM app_certificates c
            JOIN app_certificate_templates t ON t.id = c.template_id
            WHERE c.id = ? AND c.department_id = ?
        ");
        $stmt->execute([$certId, $this->department['id']]);
        $cert = $stmt->fetch();

        if (!$cert) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Sertifika bulunamadı.'];
            $this->redirectBack();
        }

        $autoloadPath = __DIR__ . '/../vendor/autoload.php';
        if (!file_exists($autoloadPath)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'PDF kütüphanesi bulunamadı (vendor/autoload.php).'];
            $this->redirectBack();
        }
        require_once $autoloadPath;

        $fields      = json_decode($cert['fields_json'] ?? '[]', true);
        $fieldValues = json_decode($cert['field_values'] ?? '{}', true);
        $bgUrl       = '';
        if ($cert['background_path']) {
            $bgUrl = 'file://' . str_replace('\\', '/', realpath(__DIR__ . '/../public/' . $cert['background_path']));
        }

        $w = (int)($cert['canvas_width']  ?: 1200);
        $h = (int)($cert['canvas_height'] ?: 850);

        $html = $this->buildCertHtml($cert, $fields, $fieldValues, $bgUrl, $w, $h);

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper([$w, $h], 'landscape');
        $dompdf->render();

        $filename = 'Sertifika_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $cert['recipient_name']) . '_' . $cert['certificate_no'] . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }

    public static function buildCertHtml(array $cert, array $fields, array $fieldValues, string $bgUrl, int $w, int $h): string
    {
        $recipientName   = htmlspecialchars($cert['recipient_name']);
        $certNo          = htmlspecialchars($cert['certificate_no']);
        $issueDate       = date('d.m.Y', strtotime($cert['issue_date']));

        $fieldsHtml = '';
        foreach ($fields as $field) {
            $key  = $field['key'] ?? '';
            $text = $fieldValues[$key] ?? $field['default'] ?? '';
            // Replace placeholders
            $text = str_replace('{{recipient_name}}', $recipientName, $text);
            $text = str_replace('{{certificate_no}}', $certNo, $text);
            $text = str_replace('{{issue_date}}', $issueDate, $text);

            $x      = round(($field['x'] ?? 0) / 100 * $w);
            $y      = round(($field['y'] ?? 0) / 100 * $h);
            $fw     = round(($field['width'] ?? 30) / 100 * $w);
            $fSize  = (int)($field['fontSize'] ?? 24);
            $fColor = htmlspecialchars($field['color'] ?? '#000000');
            $align  = htmlspecialchars($field['align'] ?? 'center');
            $bold   = !empty($field['bold']) ? 'font-weight:bold;' : '';
            $italic = !empty($field['italic']) ? 'font-style:italic;' : '';

            $fieldsHtml .= "<div style=\"position:absolute;left:{$x}px;top:{$y}px;width:{$fw}px;font-size:{$fSize}px;color:{$fColor};text-align:{$align};{$bold}{$italic}\">"
                         . nl2br(htmlspecialchars($text))
                         . "</div>\n";
        }

        $bgStyle = $bgUrl ? "background-image:url('{$bgUrl}');background-size:cover;background-position:center;" : 'background:#fff;';

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { width:{$w}px; height:{$h}px; overflow:hidden; }
  .canvas { position:relative; width:{$w}px; height:{$h}px; {$bgStyle} }
</style>
</head>
<body>
<div class="canvas">
{$fieldsHtml}
</div>
</body>
</html>
HTML;
    }

    private function redirectBack(): void
    {
        header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
        exit;
    }
}
