<?php
// c:\xampp\htdocs\innomis-admin\modules\MeetingModule.php

class MeetingModule extends BaseModule
{
    public function render(): string
    {
        $canManage = $this->isDirectorOrAdmin() || !empty($this->department['is_management']);
        
        $meetingId = (int)($_GET['meeting'] ?? 0);
        $activeMeeting = null;
        $approvals = [];

        // All department members to show approval status
        $stmt = $this->pdo->prepare("
            SELECT u.id, u.name, u.avatar 
            FROM users u 
            JOIN user_departments du ON u.id = du.user_id 
            WHERE du.department_id = ?
            ORDER BY u.name ASC
        ");
        $stmt->execute([$this->department['id']]);
        $departmentMembers = $stmt->fetchAll();

        if ($meetingId) {
            $stmt = $this->pdo->prepare("
                SELECT m.*, u.name as creator_name 
                FROM app_meetings m 
                LEFT JOIN users u ON m.created_by = u.id 
                WHERE m.id = ? AND m.app_id = ?
            ");
            $stmt->execute([$meetingId, $this->app['id']]);
            $activeMeeting = $stmt->fetch();
            
            if ($activeMeeting) {
                // Get approvals
                $stmt = $this->pdo->prepare("SELECT user_id, approved_at FROM app_meeting_approvals WHERE meeting_id = ?");
                $stmt->execute([$meetingId]);
                $rows = $stmt->fetchAll();
                foreach($rows as $r) {
                    $approvals[$r['user_id']] = $r['approved_at'];
                }
            }
        }

        // List of meetings
        $stmt = $this->pdo->prepare("SELECT id, title, meeting_date, status FROM app_meetings WHERE app_id = ? AND department_id = ? ORDER BY meeting_date DESC");
        $stmt->execute([$this->app['id'], $this->department['id']]);
        $meetings = $stmt->fetchAll();

        ob_start();
        extract([
            'app' => $this->app,
            'department' => $this->department,
            'user' => $this->user,
            'canManage' => $canManage,
            'meetings' => $meetings,
            'activeMeeting' => $activeMeeting,
            'departmentMembers' => $departmentMembers,
            'approvals' => $approvals
        ]);
        require __DIR__ . '/../views/modules/meeting.php';
        return ob_get_clean();
    }

    public function handlePost(array $data): void
    {
        $action = $data['action'] ?? '';
        $canManage = $this->isDirectorOrAdmin() || !empty($this->department['is_management']);

        if ($action === 'create_meeting' && $canManage) {
            $title = trim($data['title'] ?? '');
            $date = $data['meeting_date'] ?? '';
            $time = $data['meeting_time'] ?? '';
            $location = trim($data['location'] ?? '');
            $agenda = trim($data['agenda'] ?? '');
            
            if ($title && $date && $time && $agenda) {
                $datetime = $date . ' ' . $time . ':00';
                $stmt = $this->pdo->prepare("INSERT INTO app_meetings (app_id, department_id, created_by, title, meeting_date, location, agenda) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$this->app['id'], $this->department['id'], $this->user['id'], $title, $datetime, $location, $agenda]);
                $this->redirectBack("?meeting=" . $this->pdo->lastInsertId());
            }
        }

        if ($action === 'complete_meeting' && $canManage) {
            $meetingId = (int)($data['meeting_id'] ?? 0);
            $decisions = trim($data['decisions'] ?? '');
            
            if ($meetingId && $decisions) {
                $stmt = $this->pdo->prepare("UPDATE app_meetings SET decisions = ?, status = 'completed' WHERE id = ? AND app_id = ?");
                $stmt->execute([$decisions, $meetingId, $this->app['id']]);
            }
            $this->redirectBack("?meeting=" . $meetingId);
        }

        if ($action === 'approve_meeting') {
            $meetingId = (int)($data['meeting_id'] ?? 0);
            if ($meetingId) {
                // Ensure meeting is completed before approving
                $stmt = $this->pdo->prepare("SELECT status FROM app_meetings WHERE id = ? AND app_id = ?");
                $stmt->execute([$meetingId, $this->app['id']]);
                $meeting = $stmt->fetch();
                
                if ($meeting && $meeting['status'] === 'completed') {
                    try {
                        $stmt = $this->pdo->prepare("INSERT INTO app_meeting_approvals (meeting_id, user_id) VALUES (?, ?)");
                        $stmt->execute([$meetingId, $this->user['id']]);
                    } catch (PDOException $e) {
                        // ignore duplicate
                    }
                }
            }
            $this->redirectBack("?meeting=" . $meetingId);
        }

        if ($action === 'delete_meeting' && $canManage) {
            $meetingId = (int)($data['meeting_id'] ?? 0);
            if ($meetingId) {
                $this->pdo->prepare("DELETE FROM app_meetings WHERE id = ? AND app_id = ?")->execute([$meetingId, $this->app['id']]);
            }
            $this->redirectBack();
        }

        $this->redirectBack();
    }

    private function redirectBack($query = "")
    {
        $url = BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}" . $query;
        header("Location: " . $url);
        exit;
    }
}
