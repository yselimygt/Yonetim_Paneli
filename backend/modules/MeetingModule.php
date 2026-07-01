<?php
// c:\xampp\htdocs\innomis-admin\modules\MeetingModule.php

class MeetingModule extends BaseModule
{
    public function render(): string
    {
        $isManagement = !empty($this->department['is_management']);
        $canManage = $this->isDirectorOrAdmin() || $isManagement;
        
        $meetingId = (int)($_GET['meeting'] ?? 0);
        $activeMeeting = null;
        $approvals = [];
        $departmentMembers = [];
        
        $allDepartments = [];
        if ($isManagement) {
            $stmt = $this->pdo->query("SELECT id, name FROM departments ORDER BY name ASC");
            $allDepartments = $stmt->fetchAll();
        }

        if ($meetingId) {
            // To ensure security: non-management can only see their department's meetings
            if ($isManagement) {
                $stmt = $this->pdo->prepare("
                    SELECT m.*, u.name as creator_name, d.name as department_name 
                    FROM app_meetings m 
                    LEFT JOIN users u ON m.created_by = u.id 
                    LEFT JOIN departments d ON m.department_id = d.id
                    WHERE m.id = ? AND m.app_id = ?
                ");
                $stmt->execute([$meetingId, $this->app['id']]);
            } else {
                $stmt = $this->pdo->prepare("
                    SELECT m.*, u.name as creator_name, NULL as department_name 
                    FROM app_meetings m 
                    LEFT JOIN users u ON m.created_by = u.id 
                    WHERE m.id = ? AND m.app_id = ? AND (m.department_id = ? OR m.department_id = 0)
                ");
                $stmt->execute([$meetingId, $this->app['id'], $this->department['id']]);
            }
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

        // Fetch department members for the relevant department
        $targetDepartmentId = $activeMeeting ? $activeMeeting['department_id'] : $this->department['id'];
        
        if ($targetDepartmentId == 0 && $isManagement) {
            $stmt = $this->pdo->query("
                SELECT DISTINCT u.id, u.name, u.avatar 
                FROM users u
                JOIN user_departments du ON u.id = du.user_id
                ORDER BY u.name ASC
            ");
            $departmentMembers = $stmt->fetchAll();
        } else {
            $fetchDeptId = ($targetDepartmentId == 0) ? $this->department['id'] : $targetDepartmentId;
            $stmt = $this->pdo->prepare("
                SELECT u.id, u.name, u.avatar 
                FROM users u 
                JOIN user_departments du ON u.id = du.user_id 
                WHERE du.department_id = ?
                ORDER BY u.name ASC
            ");
            $stmt->execute([$fetchDeptId]);
            $departmentMembers = $stmt->fetchAll();
        }

        $isFullyApproved = false;
        if ($activeMeeting) {
            $expectedApprovals = 0;
            if ($activeMeeting['department_id'] == 0) {
                $expectedApprovals = $this->pdo->query("SELECT count(DISTINCT user_id) FROM user_departments")->fetchColumn();
            } else {
                $stmt = $this->pdo->prepare("SELECT count(DISTINCT user_id) FROM user_departments WHERE department_id = ?");
                $stmt->execute([$activeMeeting['department_id']]);
                $expectedApprovals = $stmt->fetchColumn();
            }
            $actualApprovals = count($approvals);
            $isFullyApproved = ($expectedApprovals > 0 && $actualApprovals >= $expectedApprovals);
        }

        // List of meetings
        if ($isManagement) {
            $stmt = $this->pdo->prepare("
                SELECT m.id, m.title, m.meeting_date, m.status, 
                       IF(m.department_id = 0, 'Tüm Departmanlar', d.name) as department_name 
                FROM app_meetings m 
                LEFT JOIN departments d ON m.department_id = d.id
                WHERE m.app_id = ? 
                ORDER BY m.meeting_date DESC
            ");
            $stmt->execute([$this->app['id']]);
        } else {
            $stmt = $this->pdo->prepare("
                SELECT id, title, meeting_date, status, NULL as department_name 
                FROM app_meetings 
                WHERE app_id = ? AND (department_id = ? OR department_id = 0)
                ORDER BY meeting_date DESC
            ");
            $stmt->execute([$this->app['id'], $this->department['id']]);
        }
        $meetings = $stmt->fetchAll();

        ob_start();
        extract([
            'app' => $this->app,
            'department' => $this->department,
            'user' => $this->user,
            'canManage' => $canManage,
            'isManagement' => $isManagement,
            'allDepartments' => $allDepartments,
            'meetings' => $meetings,
            'activeMeeting' => $activeMeeting,
            'departmentMembers' => $departmentMembers,
            'approvals' => $approvals,
            'isFullyApproved' => $isFullyApproved
        ]);
        require __DIR__ . '/../views/modules/meeting.php';
        return ob_get_clean();
    }

    public function handlePost(array $data): void
    {
        $action = $data['action'] ?? '';
        $isManagement = !empty($this->department['is_management']);
        $canManage = $this->isDirectorOrAdmin() || $isManagement;

        if ($action === 'create_meeting' && $canManage) {
            $title = trim($data['title'] ?? '');
            $date = $data['meeting_date'] ?? '';
            $time = $data['meeting_time'] ?? '';
            $location = trim($data['location'] ?? '');
            $agenda = trim($data['agenda'] ?? '');
            
            $targetDepartmentId = $this->department['id'];
            if ($isManagement && isset($data['target_department_id'])) {
                $targetDepartmentId = (int)$data['target_department_id'];
            }
            
            if ($title && $date && $time && $agenda) {
                $datetime = $date . ' ' . $time . ':00';
                $stmt = $this->pdo->prepare("INSERT INTO app_meetings (app_id, department_id, created_by, title, meeting_date, location, agenda) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$this->app['id'], $targetDepartmentId, $this->user['id'], $title, $datetime, $location, $agenda]);
                $this->redirectBack("?meeting=" . $this->pdo->lastInsertId());
            }
        }

        if ($action === 'complete_meeting' && $canManage) {
            $meetingId = (int)($data['meeting_id'] ?? 0);
            $decisions = trim($data['decisions'] ?? '');
            
            if ($meetingId && $decisions) {
                // Herkes onayladıysa düzenlemeyi engelle
                $stmt = $this->pdo->prepare("SELECT department_id FROM app_meetings WHERE id = ? AND app_id = ?");
                $stmt->execute([$meetingId, $this->app['id']]);
                $mInfo = $stmt->fetch();
                
                if ($mInfo) {
                    $mDeptId = $mInfo['department_id'];
                    $expectedApprovals = 0;
                    if ($mDeptId == 0) {
                        $expectedApprovals = $this->pdo->query("SELECT count(DISTINCT user_id) FROM user_departments")->fetchColumn();
                    } else {
                        $stmt2 = $this->pdo->prepare("SELECT count(DISTINCT user_id) FROM user_departments WHERE department_id = ?");
                        $stmt2->execute([$mDeptId]);
                        $expectedApprovals = $stmt2->fetchColumn();
                    }
                    
                    $stmt3 = $this->pdo->prepare("SELECT count(*) FROM app_meeting_approvals WHERE meeting_id = ?");
                    $stmt3->execute([$meetingId]);
                    $actualApprovals = $stmt3->fetchColumn();
                    
                    if ($expectedApprovals > 0 && $actualApprovals >= $expectedApprovals) {
                        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Bu toplantı kararları herkes tarafından onaylandığı için artık düzenlenemez.'];
                        $this->redirectBack("?meeting=" . $meetingId);
                        return;
                    }

                    if ($isManagement) {
                        $stmt = $this->pdo->prepare("UPDATE app_meetings SET decisions = ?, status = 'completed' WHERE id = ? AND app_id = ?");
                        $stmt->execute([$decisions, $meetingId, $this->app['id']]);
                    } else {
                        $stmt = $this->pdo->prepare("UPDATE app_meetings SET decisions = ?, status = 'completed' WHERE id = ? AND app_id = ? AND (department_id = ? OR department_id = 0)");
                        $stmt->execute([$decisions, $meetingId, $this->app['id'], $this->department['id']]);
                    }
                    
                    // Düzenleme yapıldığı için mevcut onayları sıfırla
                    $stmt = $this->pdo->prepare("DELETE FROM app_meeting_approvals WHERE meeting_id = ?");
                    $stmt->execute([$meetingId]);
                }
            }
            $this->redirectBack("?meeting=" . $meetingId);
        }

        if ($action === 'approve_meeting') {
            $meetingId = (int)($data['meeting_id'] ?? 0);
            if ($meetingId) {
                // Determine if user can see this meeting
                if ($isManagement) {
                    $stmt = $this->pdo->prepare("SELECT status FROM app_meetings WHERE id = ? AND app_id = ?");
                    $stmt->execute([$meetingId, $this->app['id']]);
                } else {
                    $stmt = $this->pdo->prepare("SELECT status FROM app_meetings WHERE id = ? AND app_id = ? AND (department_id = ? OR department_id = 0)");
                    $stmt->execute([$meetingId, $this->app['id'], $this->department['id']]);
                }
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
                if ($isManagement) {
                    $this->pdo->prepare("DELETE FROM app_meetings WHERE id = ? AND app_id = ?")->execute([$meetingId, $this->app['id']]);
                } else {
                    $this->pdo->prepare("DELETE FROM app_meetings WHERE id = ? AND app_id = ? AND (department_id = ? OR department_id = 0)")->execute([$meetingId, $this->app['id'], $this->department['id']]);
                }
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
