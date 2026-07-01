<?php
// c:\xampp\htdocs\innomis-admin\modules\AgileModule.php

class AgileModule extends BaseModule
{
    private array $statuses = [
        'backlog'     => ['label' => 'Backlog', 'color' => '#6b7280'],
        'todo'        => ['label' => 'Yapılacaklar', 'color' => '#3b82f6'],
        'in_progress' => ['label' => 'Devam Ediyor', 'color' => '#f59e0b'],
        'testing'     => ['label' => 'Test Ediliyor', 'color' => '#8b5cf6'],
        'done'        => ['label' => 'Tamamlandı', 'color' => '#10b981']
    ];

    public function render(): string
    {
        // Get all sprints for this app
        $stmt = $this->pdo->prepare("SELECT * FROM app_agile_sprints WHERE app_id = ? ORDER BY start_date DESC");
        $stmt->execute([$this->app['id']]);
        $sprints = $stmt->fetchAll();

        // Get currently active or selected sprint
        $activeSprintId = $_GET['sprint_id'] ?? null;
        $activeSprint = null;

        if ($activeSprintId) {
            foreach ($sprints as $s) {
                if ($s['id'] == $activeSprintId) {
                    $activeSprint = $s;
                    break;
                }
            }
        } else {
            foreach ($sprints as $s) {
                if ($s['is_active']) {
                    $activeSprint = $s;
                    $activeSprintId = $s['id'];
                    break;
                }
            }
            if (!$activeSprint && !empty($sprints)) {
                $activeSprint = $sprints[0];
                $activeSprintId = $sprints[0]['id'];
            }
        }

        // Get tasks for active sprint (including backlog tasks which might have sprint_id = NULL)
        // Actually, if we view a sprint, we only show tasks in that sprint.
        // Wait, where is the Backlog? 
        // We'll fetch tasks where sprint_id = activeSprintId OR (sprint_id IS NULL AND status = 'backlog')
        $query = "SELECT t.*, u.name as assignee_name, u.avatar as assignee_avatar
                  FROM app_agile_tasks t 
                  LEFT JOIN users u ON t.assignee_id = u.id 
                  WHERE t.app_id = ?";
        $params = [$this->app['id']];
        
        if ($activeSprintId) {
            $query .= " AND (t.sprint_id = ? OR t.sprint_id IS NULL)";
            $params[] = $activeSprintId;
        }

        $query .= " ORDER BY t.sort_order ASC, t.id DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $tasks = $stmt->fetchAll();

        $groupedTasks = [];
        foreach ($this->statuses as $key => $s) {
            $groupedTasks[$key] = [];
        }

        $pendingTasks = [];
        foreach ($tasks as $t) {
            if (isset($t['is_approved']) && $t['is_approved'] == 0) {
                if ($this->isDirectorOrAdmin()) {
                    $pendingTasks[] = $t;
                }
                continue;
            }
            $status = $t['status'] ?? 'backlog';
            if (isset($groupedTasks[$status])) {
                $groupedTasks[$status][] = $t;
            }
        }

        // Get members
        $stmt = $this->pdo->prepare("SELECT u.id, u.name FROM users u JOIN user_departments ud ON u.id = ud.user_id WHERE ud.department_id = ?");
        $stmt->execute([$this->department['id']]);
        $members = $stmt->fetchAll();

        ob_start();
        extract([
            'app' => $this->app,
            'department' => $this->department,
            'user' => $this->user,
            'isDirectorOrAdmin' => $this->isDirectorOrAdmin(),
            'statuses' => $this->statuses,
            'sprints' => $sprints,
            'activeSprint' => $activeSprint,
            'groupedTasks' => $groupedTasks,
            'pendingTasks' => $pendingTasks,
            'members' => $members
        ]);
        require __DIR__ . '/../views/modules/agile.php';
        return ob_get_clean();
    }

    public function handlePost(array $data): void
    {
        $action = $data['action'] ?? '';

        if ($action === 'create_sprint' && $this->isDirectorOrAdmin()) {
            $name = trim($data['name'] ?? '');
            $start = !empty($data['start_date']) ? $data['start_date'] : null;
            $end = !empty($data['end_date']) ? $data['end_date'] : null;

            if ($name) {
                // If it's the first sprint, make it active
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM app_agile_sprints WHERE app_id = ?");
                $stmt->execute([$this->app['id']]);
                $count = $stmt->fetchColumn();
                $isActive = $count == 0 ? 1 : 0;

                $stmt = $this->pdo->prepare("INSERT INTO app_agile_sprints (app_id, department_id, name, start_date, end_date, is_active) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$this->app['id'], $this->department['id'], $name, $start, $end, $isActive]);
            }
            $this->redirectBack();
        }

        if ($action === 'create_task') {
            $title = trim($data['title'] ?? '');
            $description = trim($data['description'] ?? '');
            $sprint_id = !empty($data['sprint_id']) ? (int)$data['sprint_id'] : null;
            $assignee_id = !empty($data['assignee_id']) ? (int)$data['assignee_id'] : null;
            $priority = $data['priority'] ?? 'normal';
            $status = $data['status'] ?? 'backlog';
            $is_approved = $this->isDirectorOrAdmin() ? 1 : 0;

            if ($title) {
                $stmt = $this->pdo->prepare("INSERT INTO app_agile_tasks (app_id, department_id, sprint_id, title, description, status, assignee_id, priority, created_by, is_approved) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$this->app['id'], $this->department['id'], $sprint_id, $title, $description, $status, $assignee_id, $priority, $this->user['id'], $is_approved]);
            }
            $this->redirectBack();
        }

        if ($action === 'edit_task') {
            $id = (int)($data['task_id'] ?? 0);
            $title = trim($data['title'] ?? '');
            $description = trim($data['description'] ?? '');
            $sprint_id = !empty($data['sprint_id']) ? (int)$data['sprint_id'] : null;
            $assignee_id = !empty($data['assignee_id']) ? (int)$data['assignee_id'] : null;
            $priority = $data['priority'] ?? 'normal';

            if ($id && $title) {
                // Verify ownership or director
                $stmt = $this->pdo->prepare("SELECT created_by FROM app_agile_tasks WHERE id = ? AND app_id = ?");
                $stmt->execute([$id, $this->app['id']]);
                $task = $stmt->fetch();

                if ($task && $this->isDirectorOrAdmin()) {
                    $stmt = $this->pdo->prepare("UPDATE app_agile_tasks SET sprint_id = ?, title = ?, description = ?, assignee_id = ?, priority = ? WHERE id = ?");
                    $stmt->execute([$sprint_id, $title, $description, $assignee_id, $priority, $id]);
                }
            }
            $this->redirectBack();
        }

        if ($action === 'delete_task') {
            $id = (int)($data['task_id'] ?? 0);
            $stmt = $this->pdo->prepare("SELECT created_by FROM app_agile_tasks WHERE id = ? AND app_id = ?");
            $stmt->execute([$id, $this->app['id']]);
            $task = $stmt->fetch();

            if ($task && $this->isDirectorOrAdmin()) {
                $stmt = $this->pdo->prepare("DELETE FROM app_agile_tasks WHERE id = ?");
                $stmt->execute([$id]);
            }
            $this->redirectBack();
        }

        if ($action === 'approve_task') {
            if ($this->isDirectorOrAdmin()) {
                $id = (int)($data['task_id'] ?? 0);
                if ($id) {
                    $stmt = $this->pdo->prepare("UPDATE app_agile_tasks SET is_approved = 1 WHERE id = ? AND app_id = ?");
                    $stmt->execute([$id, $this->app['id']]);
                }
            }
            $this->redirectBack();
        }

        if ($action === 'update_status_ajax') {
            header('Content-Type: application/json');
            if (!$this->isDirectorOrAdmin()) {
                echo json_encode(['success' => false, 'message' => 'Yetkisiz işlem.']);
                exit;
            }
            $id = (int)($data['task_id'] ?? 0);
            $newStatus = $data['status'] ?? '';
            $order = (int)($data['order'] ?? 0);

            if ($id && array_key_exists($newStatus, $this->statuses)) {
                $stmt = $this->pdo->prepare("UPDATE app_agile_tasks SET status = ?, sort_order = ? WHERE id = ? AND app_id = ?");
                $success = $stmt->execute([$newStatus, $order, $id, $this->app['id']]);
                echo json_encode(['success' => $success]);
                exit;
            }
            echo json_encode(['success' => false]);
            exit;
        }

        if ($action === 'update_order_ajax') {
            header('Content-Type: application/json');
            if (!$this->isDirectorOrAdmin()) {
                echo json_encode(['success' => false, 'message' => 'Yetkisiz işlem.']);
                exit;
            }
            $tasks = json_decode($data['tasks'] ?? '[]', true);
            if (is_array($tasks)) {
                $this->pdo->beginTransaction();
                $stmt = $this->pdo->prepare("UPDATE app_agile_tasks SET sort_order = ? WHERE id = ? AND app_id = ?");
                foreach ($tasks as $t) {
                    $stmt->execute([(int)$t['order'], (int)$t['id'], $this->app['id']]);
                }
                $this->pdo->commit();
                echo json_encode(['success' => true]);
                exit;
            }
            echo json_encode(['success' => false]);
            exit;
        }

        if ($action === 'get_task_comments_ajax') {
            header('Content-Type: application/json');
            $taskId = (int)($data['task_id'] ?? 0);
            
            // Check if task belongs to this app
            $stmt = $this->pdo->prepare("SELECT id FROM app_agile_tasks WHERE id = ? AND app_id = ?");
            $stmt->execute([$taskId, $this->app['id']]);
            if (!$stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Görev bulunamadı.']);
                exit;
            }

            $stmt = $this->pdo->prepare("
                SELECT c.*, u.name as user_name, u.avatar as user_avatar 
                FROM app_agile_task_comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.task_id = ?
                ORDER BY c.created_at ASC
            ");
            $stmt->execute([$taskId]);
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Format data for JSON
            foreach ($comments as &$c) {
                $c['created_at_formatted'] = date('d.m.Y H:i', strtotime($c['created_at']));
                if (!empty($c['user_avatar'])) {
                    $c['user_avatar'] = BASE_URL . '/public/' . ltrim($c['user_avatar'], '/');
                }
                if (!empty($c['file_path'])) {
                    $c['file_path'] = BASE_URL . '/public/' . ltrim($c['file_path'], '/');
                }
            }

            echo json_encode(['success' => true, 'comments' => $comments]);
            exit;
        }

        if ($action === 'add_task_comment_ajax') {
            header('Content-Type: application/json');
            $taskId = (int)($data['task_id'] ?? 0);
            $commentText = trim($data['comment'] ?? '');
            
            $stmt = $this->pdo->prepare("SELECT id FROM app_agile_tasks WHERE id = ? AND app_id = ?");
            $stmt->execute([$taskId, $this->app['id']]);
            if (!$stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Görev bulunamadı.']);
                exit;
            }

            $filePath = null;
            $fileName = null;

            if (!empty($_FILES['attachment']['name']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/agile/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $ext = strtolower(pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION));
                $fileName = $_FILES['attachment']['name'];
                $newFileName = 'file_' . uniqid() . '.' . $ext;
                $dest = $uploadDir . $newFileName;

                if (move_uploaded_file($_FILES['attachment']['tmp_name'], $dest)) {
                    $filePath = 'uploads/agile/' . $newFileName;
                }
            }

            if ($commentText || $filePath) {
                $stmt = $this->pdo->prepare("INSERT INTO app_agile_task_comments (task_id, user_id, comment, file_path, file_name) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$taskId, $this->user['id'], $commentText, $filePath, $fileName]);
                echo json_encode(['success' => true]);
                exit;
            }

            echo json_encode(['success' => false, 'message' => 'Yorum veya dosya eklemelisiniz.']);
            exit;
        }

        $this->redirectBack();
    }

    private function redirectBack()
    {
        $url = BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}";
        if (!empty($_GET['sprint_id'])) {
            $url .= "?sprint_id=" . (int)$_GET['sprint_id'];
        }
        header("Location: " . $url);
        exit;
    }
}
