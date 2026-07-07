<?php
// c:\xampp\htdocs\innomis-admin\modules\AuditModule.php

class AuditModule extends BaseModule
{
    public function render(): string
    {
        $canManage = $this->isDirectorOrAdmin() || !empty($this->department['is_management']);
        
        $myReports = [];
        $allReports = [];
        $activeReport = null;
        $activeReportNotes = [];

        if ($canManage) {
            // Managers see all reports
            $stmt = $this->pdo->prepare("
                SELECT r.*, u.name as reporter_name, u.avatar as reporter_avatar 
                FROM app_audit_reports r 
                LEFT JOIN users u ON r.reporter_id = u.id 
                WHERE r.app_id = ? 
                ORDER BY r.created_at DESC
            ");
            $stmt->execute([$this->app['id']]);
            $allReports = $stmt->fetchAll();

            $reportId = (int)($_GET['report'] ?? 0);
            if ($reportId) {
                foreach ($allReports as $r) {
                    if ($r['id'] == $reportId) {
                        $activeReport = $r;
                        break;
                    }
                }
                if ($activeReport) {
                    $stmt = $this->pdo->prepare("
                        SELECT n.*, u.name as author_name, u.avatar as author_avatar 
                        FROM app_audit_notes n 
                        JOIN users u ON n.user_id = u.id 
                        WHERE n.report_id = ? 
                        ORDER BY n.created_at ASC
                    ");
                    $stmt->execute([$activeReport['id']]);
                    $activeReportNotes = $stmt->fetchAll();
                }
            }
        } else {
            // Normal users see only their own reports
            $stmt = $this->pdo->prepare("
                SELECT * FROM app_audit_reports 
                WHERE app_id = ? AND reporter_id = ? 
                ORDER BY created_at DESC
            ");
            $stmt->execute([$this->app['id'], $this->user['id']]);
            $myReports = $stmt->fetchAll();
        }

        ob_start();
        extract([
            'app' => $this->app,
            'department' => $this->department,
            'user' => $this->user,
            'canManage' => $canManage,
            'myReports' => $myReports,
            'allReports' => $allReports,
            'activeReport' => $activeReport,
            'activeReportNotes' => $activeReportNotes
        ]);
        require __DIR__ . '/../views/modules/audit.php';
        return ob_get_clean();
    }

    public function handlePost(array $data): void
    {
        $action = $data['action'] ?? '';
        $canManage = $this->isDirectorOrAdmin() || !empty($this->department['is_management']);

        if ($action === 'submit_report') {
            $subject = trim($data['subject'] ?? '');
            $category = $data['category'] ?? 'evaluation';
            $description = trim($data['description'] ?? '');
            $isAnonymous = !empty($data['is_anonymous']);

            if ($subject && $description) {
                $reporterId = $this->user['id'];
                $isAnonFlag = $isAnonymous ? 1 : 0;
                $stmt = $this->pdo->prepare("INSERT INTO app_audit_reports (app_id, department_id, reporter_id, subject, description, category, is_anonymous) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$this->app['id'], $this->department['id'], $reporterId, $subject, $description, $category, $isAnonFlag]);
            }
            $this->redirectBack();
        }

        if ($action === 'update_status' && $canManage) {
            $reportId = (int)($data['report_id'] ?? 0);
            $status = $data['status'] ?? '';
            
            if ($reportId && in_array($status, ['pending', 'under_review', 'resolved', 'rejected'])) {
                $stmt = $this->pdo->prepare("UPDATE app_audit_reports SET status = ? WHERE id = ? AND app_id = ?");
                $stmt->execute([$status, $reportId, $this->app['id']]);
            }
            $this->redirectBack($reportId);
        }

        if ($action === 'add_note' && $canManage) {
            $reportId = (int)($data['report_id'] ?? 0);
            $note = trim($data['note'] ?? '');

            if ($reportId && $note) {
                // Ensure report exists
                $stmt = $this->pdo->prepare("SELECT id FROM app_audit_reports WHERE id = ? AND app_id = ?");
                $stmt->execute([$reportId, $this->app['id']]);
                if ($stmt->fetch()) {
                    $stmt = $this->pdo->prepare("INSERT INTO app_audit_notes (report_id, user_id, note) VALUES (?, ?, ?)");
                    $stmt->execute([$reportId, $this->user['id'], $note]);
                }
            }
            $this->redirectBack($reportId);
        }

        if ($action === 'delete_report' && $canManage) {
            $reportId = (int)($data['report_id'] ?? 0);
            if ($reportId) {
                $stmt = $this->pdo->prepare("DELETE FROM app_audit_reports WHERE id = ? AND app_id = ?");
                $stmt->execute([$reportId, $this->app['id']]);
            }
            $this->redirectBack();
        }

        $this->redirectBack();
    }

    private function redirectBack(int $reportId = 0)
    {
        $url = BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}";
        if ($reportId > 0) {
            $url .= "?report=" . $reportId;
        }
        header("Location: " . $url);
        exit;
    }
}
