<?php

class TaskManagerModule extends BaseModule
{
    public function render(): string
    {
        $isManagement = (Auth::isManagement() || Auth::isSuperAdmin()) && ($this->department['slug'] ?? '') === 'yonetim';
        $isDirector = Auth::isDirectorOf($this->department['slug'] ?? '') || Auth::isSuperAdmin();

        $tasks = $this->getTasks($isManagement, $isDirector);
        $teams = $this->getTeams();
        $allUsers = $this->getAllUsers();
        
        $app = $this->app;
        $department = $this->department;

        ob_start();
        require __DIR__ . '/../views/modules/task_manager.php';
        return ob_get_clean();
    }

    public function handlePost(array $data): void
    {
        $action = $data['action'] ?? '';
        $isManagement = (Auth::isManagement() || Auth::isSuperAdmin()) && ($this->department['slug'] ?? '') === 'yonetim';
        $isDirector = Auth::isDirectorOf($this->department['slug'] ?? '') || Auth::isSuperAdmin();
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        try {
            if ($action === 'create_task' && ($isManagement || $isDirector)) {
                $this->createTask($data);
                if ($isAjax) { echo json_encode(['success' => true]); exit; }
            } elseif ($action === 'edit_task' && ($isManagement || $isDirector)) {
                $this->editTask($data, $isManagement);
                if ($isAjax) { echo json_encode(['success' => true]); exit; }
            } elseif ($action === 'delete_task' && ($isManagement || $isDirector)) {
                $this->deleteTask($data, $isManagement);
                if ($isAjax) { echo json_encode(['success' => true]); exit; }
            } elseif ($action === 'update_status') {
                $this->updateStatus($data, $isManagement || $isDirector);
                if ($isAjax) { echo json_encode(['success' => true]); exit; }
            } elseif ($action === 'add_comment') {
                $this->addComment($data);
                if ($isAjax) { echo json_encode(['success' => true]); exit; }
            } elseif ($action === 'create_team' && ($isManagement || $isDirector)) {
                $this->createTeam($data);
                if ($isAjax) { echo json_encode(['success' => true]); exit; }
            } elseif ($action === 'edit_team' && ($isManagement || $isDirector)) {
                $this->editTeam($data);
                if ($isAjax) { echo json_encode(['success' => true]); exit; }
            } elseif ($action === 'delete_team' && ($isManagement || $isDirector)) {
                $this->deleteTeam($data);
                if ($isAjax) { echo json_encode(['success' => true]); exit; }
            } elseif ($action === 'get_comments') {
                $this->getComments($data['task_id'] ?? 0);
                exit; // Always AJAX
            } else {
                throw new Exception("Geçersiz işlem veya yetki yok.");
            }

            // Fallback for non-ajax
            $_SESSION['success_message'] = "İşlem başarıyla gerçekleştirildi.";
        } catch (Exception $e) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                exit;
            }
            $_SESSION['error_message'] = $e->getMessage();
        }

        header("Location: " . BASE_URL . "/panel/" . View::escape($this->department['slug'] ?? '') . "/apps/" . View::escape($this->app['slug'] ?? ''));
        exit;
    }

    private function getTasks(bool $isManagement, bool $isDirector): array
    {
        $userId = Auth::user()['id'];
        $query = "
            SELECT t.*, 
                   u.name as creator_name,
                   d_creator.slug as creator_dept_slug
            FROM app_tasks t
            JOIN users u ON t.created_by = u.id
            LEFT JOIN user_departments ud_creator ON u.id = ud_creator.user_id
            LEFT JOIN departments d_creator ON ud_creator.department_id = d_creator.id
            WHERE 1=1
        ";

        if (!$isManagement) {
            if ($isDirector && !empty($this->department['slug'])) {
                $query .= " AND (t.id IN (
                    SELECT ta.task_id 
                    FROM app_task_assignees ta
                    JOIN user_departments ud ON ta.user_id = ud.user_id
                    JOIN departments d ON ud.department_id = d.id
                    WHERE d.slug = :dept_slug
                ) OR t.id IN (
                    SELECT ta.task_id 
                    FROM app_task_assignees ta
                    WHERE ta.user_id = :user_id
                ))";
            } else {
                $query .= " AND t.id IN (
                    SELECT ta.task_id 
                    FROM app_task_assignees ta
                    WHERE ta.user_id = :user_id
                )";
            }
        }

        $query .= " ORDER BY t.created_at DESC";

        $stmt = $this->pdo->prepare($query);
        $params = [];
        if (!$isManagement) {
            if ($isDirector && !empty($this->department['slug'])) {
                $params['dept_slug'] = $this->department['slug'];
                $params['user_id'] = $userId;
            } else {
                $params['user_id'] = $userId;
            }
        }
        $stmt->execute($params);
        $tasks = $stmt->fetchAll();

        // Get assignees
        $taskIds = array_column($tasks, 'id');
        $assigneesByTask = [];
        if (!empty($taskIds)) {
            $inQuery = implode(',', array_fill(0, count($taskIds), '?'));
            $assigneeStmt = $this->pdo->prepare("
                SELECT ta.*, u.name, u.avatar 
                FROM app_task_assignees ta
                JOIN users u ON ta.user_id = u.id
                WHERE ta.task_id IN ($inQuery)
            ");
            $assigneeStmt->execute($taskIds);
            $assignees = $assigneeStmt->fetchAll();
            foreach ($assignees as $a) {
                $assigneesByTask[$a['task_id']][] = $a;
            }
        }

        foreach ($tasks as &$task) {
            $task['assignees'] = $assigneesByTask[$task['id']] ?? [];
        }

        return $tasks;
    }

    private function getTeams(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM app_task_teams ORDER BY name ASC");
        $teams = $stmt->fetchAll();

        $teamIds = array_column($teams, 'id');
        $membersByTeam = [];
        if (!empty($teamIds)) {
            $inQuery = implode(',', array_fill(0, count($teamIds), '?'));
            $memberStmt = $this->pdo->prepare("
                SELECT tm.team_id, u.id as user_id, u.name 
                FROM app_task_team_members tm
                JOIN users u ON tm.user_id = u.id
                WHERE tm.team_id IN ($inQuery)
            ");
            $memberStmt->execute($teamIds);
            $members = $memberStmt->fetchAll();
            foreach ($members as $m) {
                $membersByTeam[$m['team_id']][] = $m;
            }
        }

        foreach ($teams as &$t) {
            $t['members'] = $membersByTeam[$t['id']] ?? [];
        }
        return $teams;
    }

    private function getAllUsers(): array
    {
        $stmt = $this->pdo->query("SELECT id, name FROM users WHERE is_active = 1 ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    private function createTask(array $data): void
    {
        $title = trim($data['title'] ?? '');
        $description = trim($data['description'] ?? '');
        $dueDate = !empty($data['due_date']) ? $data['due_date'] : null;
        $userIds = $data['user_ids'] ?? [];
        $teamIds = $data['team_ids'] ?? [];

        if (empty($title) || (empty($userIds) && empty($teamIds))) {
            throw new Exception('Başlık ve en az bir alıcı gereklidir.');
        }

        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare("INSERT INTO app_tasks (title, description, created_by, due_date) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $description, Auth::user()['id'], $dueDate]);
            $taskId = $this->pdo->lastInsertId();

            $finalUserIds = [];
            foreach ((array)$userIds as $uid) {
                if ($uid) $finalUserIds[$uid] = true;
            }

            if (!empty($teamIds)) {
                $inQuery = implode(',', array_fill(0, count($teamIds), '?'));
                $teamUsersStmt = $this->pdo->prepare("SELECT user_id FROM app_task_team_members WHERE team_id IN ($inQuery)");
                $teamUsersStmt->execute((array)$teamIds);
                foreach ($teamUsersStmt->fetchAll() as $tu) {
                    $finalUserIds[$tu['user_id']] = true;
                }
            }

            $assignStmt = $this->pdo->prepare("INSERT INTO app_task_assignees (task_id, user_id) VALUES (?, ?)");
            foreach (array_keys($finalUserIds) as $uid) {
                $assignStmt->execute([$taskId, $uid]);
            }

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function editTask(array $data, bool $isManagement): void
    {
        $taskId = (int)($data['task_id'] ?? 0);
        $title = trim($data['title'] ?? '');
        $description = trim($data['description'] ?? '');
        $dueDate = !empty($data['due_date']) ? $data['due_date'] : null;
        $userIds = $data['user_ids'] ?? [];
        $teamIds = $data['team_ids'] ?? [];

        if (empty($taskId) || empty($title) || (empty($userIds) && empty($teamIds))) {
            throw new Exception('Görev ID, başlık ve en az bir alıcı gereklidir.');
        }

        $stmt = $this->pdo->prepare("
            SELECT d.slug 
            FROM app_tasks t
            JOIN users u ON t.created_by = u.id
            LEFT JOIN user_departments ud ON u.id = ud.user_id
            LEFT JOIN departments d ON ud.department_id = d.id
            WHERE t.id = ?
        ");
        $stmt->execute([$taskId]);
        $creatorDept = $stmt->fetchColumn();

        if ($creatorDept === 'yonetim' && !$isManagement) {
            throw new Exception("Yönetim tarafından oluşturulan görevleri sadece yönetim düzenleyebilir.");
        }

        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare("UPDATE app_tasks SET title = ?, description = ?, due_date = ? WHERE id = ?");
            $stmt->execute([$title, $description, $dueDate, $taskId]);

            $finalUserIds = [];
            foreach ((array)$userIds as $uid) {
                if ($uid) $finalUserIds[$uid] = true;
            }

            if (!empty($teamIds)) {
                $inQuery = implode(',', array_fill(0, count($teamIds), '?'));
                $teamUsersStmt = $this->pdo->prepare("SELECT user_id FROM app_task_team_members WHERE team_id IN ($inQuery)");
                $teamUsersStmt->execute((array)$teamIds);
                foreach ($teamUsersStmt->fetchAll() as $tu) {
                    $finalUserIds[$tu['user_id']] = true;
                }
            }

            // Remove old assignees
            $delStmt = $this->pdo->prepare("DELETE FROM app_task_assignees WHERE task_id = ?");
            $delStmt->execute([$taskId]);

            // Insert new assignees
            $assignStmt = $this->pdo->prepare("INSERT INTO app_task_assignees (task_id, user_id) VALUES (?, ?)");
            foreach (array_keys($finalUserIds) as $uid) {
                $assignStmt->execute([$taskId, $uid]);
            }

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function deleteTask(array $data, bool $isManagement): void
    {
        $taskId = (int)($data['task_id'] ?? 0);
        if (!$taskId) throw new Exception("Görev ID gereklidir.");

        $stmt = $this->pdo->prepare("
            SELECT d.slug 
            FROM app_tasks t
            JOIN users u ON t.created_by = u.id
            LEFT JOIN user_departments ud ON u.id = ud.user_id
            LEFT JOIN departments d ON ud.department_id = d.id
            WHERE t.id = ?
        ");
        $stmt->execute([$taskId]);
        $creatorDept = $stmt->fetchColumn();

        if ($creatorDept === 'yonetim' && !$isManagement) {
            throw new Exception("Yönetim tarafından oluşturulan görevleri sadece yönetim silebilir.");
        }

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("DELETE FROM app_task_assignees WHERE task_id = ?");
            $stmt->execute([$taskId]);
            
            $stmt = $this->pdo->prepare("DELETE FROM app_task_comments WHERE task_id = ?");
            $stmt->execute([$taskId]);

            $stmt = $this->pdo->prepare("DELETE FROM app_tasks WHERE id = ?");
            $stmt->execute([$taskId]);

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function updateStatus(array $data, bool $isManagement): void
    {
        $assigneeId = $data['assignee_id'] ?? 0;
        $status = $data['status'] ?? '';

        if (!$assigneeId || !in_array($status, ['pending', 'acknowledged', 'completed'])) {
            throw new Exception('Geçersiz parametreler.');
        }

        $stmt = $this->pdo->prepare("SELECT user_id FROM app_task_assignees WHERE id = ?");
        $stmt->execute([$assigneeId]);
        $assignee = $stmt->fetch();

        if (!$assignee) {
            throw new Exception('Atama bulunamadı.');
        }

        if ($assignee['user_id'] != Auth::user()['id'] && !$isManagement) {
            throw new Exception('Bu işlem için yetkiniz yok.');
        }

        $completedAt = ($status === 'completed') ? date('Y-m-d H:i:s') : null;
        $updateStmt = $this->pdo->prepare("UPDATE app_task_assignees SET status = ?, completed_at = ? WHERE id = ?");
        $updateStmt->execute([$status, $completedAt, $assigneeId]);
    }

    private function addComment(array $data): void
    {
        $taskId = $data['task_id'] ?? 0;
        $comment = trim($data['comment'] ?? '');

        if (!$taskId || empty($comment)) {
            throw new Exception('Geçersiz parametreler.');
        }

        $stmt = $this->pdo->prepare("INSERT INTO app_task_comments (task_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$taskId, Auth::user()['id'], $comment]);
    }

    private function getComments(int $taskId): void
    {
        $stmt = $this->pdo->prepare("
            SELECT c.*, u.name, u.avatar 
            FROM app_task_comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.task_id = ?
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([$taskId]);
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
    }

    private function createTeam(array $data): void
    {
        $name = trim($data['name'] ?? '');
        $description = trim($data['description'] ?? '');
        $userIds = $data['user_ids'] ?? [];

        if (empty($name)) {
            throw new Exception('Takım adı gerekli.');
        }

        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare("INSERT INTO app_task_teams (name, description, created_by) VALUES (?, ?, ?)");
            $stmt->execute([$name, $description, Auth::user()['id']]);
            $teamId = $this->pdo->lastInsertId();

            if (!empty($userIds)) {
                $memberStmt = $this->pdo->prepare("INSERT INTO app_task_team_members (team_id, user_id) VALUES (?, ?)");
                foreach ((array)$userIds as $uid) {
                    if ($uid) $memberStmt->execute([$teamId, $uid]);
                }
            }

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function editTeam(array $data): void
    {
        $teamId = (int)($data['team_id'] ?? 0);
        $name = trim($data['name'] ?? '');
        $description = trim($data['description'] ?? '');
        $userIds = $data['user_ids'] ?? [];

        if (empty($teamId) || empty($name)) {
            throw new Exception('Takım ID ve adı gerekli.');
        }

        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare("UPDATE app_task_teams SET name = ?, description = ? WHERE id = ?");
            $stmt->execute([$name, $description, $teamId]);

            // Delete old members
            $delStmt = $this->pdo->prepare("DELETE FROM app_task_team_members WHERE team_id = ?");
            $delStmt->execute([$teamId]);

            if (!empty($userIds)) {
                $memberStmt = $this->pdo->prepare("INSERT INTO app_task_team_members (team_id, user_id) VALUES (?, ?)");
                foreach ((array)$userIds as $uid) {
                    if ($uid) $memberStmt->execute([$teamId, $uid]);
                }
            }

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function deleteTeam(array $data): void
    {
        $teamId = (int)($data['team_id'] ?? 0);
        
        if (empty($teamId)) {
            throw new Exception('Takım ID gerekli.');
        }

        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare("DELETE FROM app_task_team_members WHERE team_id = ?");
            $stmt->execute([$teamId]);

            $stmt = $this->pdo->prepare("DELETE FROM app_task_teams WHERE id = ?");
            $stmt->execute([$teamId]);

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
