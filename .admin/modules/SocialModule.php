<?php
// c:\xampp\htdocs\innomis-admin\modules\SocialModule.php

class SocialModule extends BaseModule
{
    public function render(): string
    {
        $canManage = $this->isDirectorOrAdmin() || !empty($this->department['is_management']);

        // Fetch all posts for this app and department
        $stmt = $this->pdo->prepare("
            SELECT s.*, u.name as creator_name, u.avatar as creator_avatar,
                   a.name as assigned_name, a.avatar as assigned_avatar
            FROM app_social_posts s
            JOIN users u ON s.creator_id = u.id
            LEFT JOIN users a ON s.assigned_to = a.id
            WHERE s.app_id = ? AND s.department_id = ?
            ORDER BY s.publish_date ASC
        ");
        $stmt->execute([$this->app['id'], $this->department['id']]);
        $posts = $stmt->fetchAll();

        // Group posts by status
        $board = [
            'draft' => [],
            'review' => [],
            'approved' => [],
            'published' => []
        ];

        foreach ($posts as $post) {
            $board[$post['status']][] = $post;
        }

        // Fetch department members for assignment
        $stmtUsers = $this->pdo->prepare("
            SELECT u.id, u.name, u.avatar 
            FROM user_departments du
            JOIN users u ON du.user_id = u.id
            WHERE du.department_id = ?
            ORDER BY u.name ASC
        ");
        $stmtUsers->execute([$this->department['id']]);
        $members = $stmtUsers->fetchAll();

        ob_start();
        extract([
            'app' => $this->app,
            'department' => $this->department,
            'user' => $this->user,
            'canManage' => $canManage,
            'board' => $board,
            'members' => $members
        ]);
        require __DIR__ . '/../views/modules/social.php';
        return ob_get_clean();
    }

    public function handlePost(array $data): void
    {
        $action = $data['action'] ?? '';
        $canManage = $this->isDirectorOrAdmin() || !empty($this->department['is_management']);

        if ($action === 'create_post') {
            $title = trim($data['title'] ?? '');
            $platform = trim($data['platform'] ?? '');
            $publishDate = trim($data['publish_date'] ?? '');
            $assignedTo = !empty($data['assigned_to']) ? (int)$data['assigned_to'] : null;
            
            if ($title && $platform && $publishDate) {
                // Ensure datetime format (if they only send date, append time)
                if (strlen($publishDate) == 10) {
                    $publishDate .= ' 12:00:00';
                }

                $stmt = $this->pdo->prepare("INSERT INTO app_social_posts (app_id, department_id, creator_id, assigned_to, platform, publish_date, title) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$this->app['id'], $this->department['id'], $this->user['id'], $assignedTo, $platform, $publishDate, $title]);
            }
        }

        if ($action === 'update_post') {
            $postId = (int)($data['post_id'] ?? 0);
            $contentText = trim($data['content_text'] ?? '');
            $assignedTo = !empty($data['assigned_to']) ? (int)$data['assigned_to'] : null;
            
            if ($postId) {
                // Handle file upload
                $imageUrl = null;
                if (!empty($_FILES['image']['name'])) {
                    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $filename = uniqid('soc_') . '.' . $ext;
                    $uploadPath = __DIR__ . '/../public/uploads/social/' . $filename;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        $imageUrl = $filename;
                    }
                }

                if ($imageUrl) {
                    $stmt = $this->pdo->prepare("UPDATE app_social_posts SET content_text = ?, image_url = ?, assigned_to = ? WHERE id = ? AND app_id = ?");
                    $stmt->execute([$contentText, $imageUrl, $assignedTo, $postId, $this->app['id']]);
                } else {
                    $stmt = $this->pdo->prepare("UPDATE app_social_posts SET content_text = ?, assigned_to = ? WHERE id = ? AND app_id = ?");
                    $stmt->execute([$contentText, $assignedTo, $postId, $this->app['id']]);
                }
            }
        }

        if ($action === 'change_status') {
            $postId = (int)($data['post_id'] ?? 0);
            $newStatus = trim($data['new_status'] ?? '');
            
            if ($postId && in_array($newStatus, ['draft', 'review', 'approved', 'published'])) {
                // Check if moving to approved/published needs management perms
                if (in_array($newStatus, ['approved', 'published']) && !$canManage) {
                    // Not allowed
                } else {
                    $stmt = $this->pdo->prepare("UPDATE app_social_posts SET status = ? WHERE id = ? AND app_id = ?");
                    $stmt->execute([$newStatus, $postId, $this->app['id']]);
                }
            }
        }
        
        if ($action === 'delete_post' && $canManage) {
            $postId = (int)($data['post_id'] ?? 0);
            if ($postId) {
                $stmt = $this->pdo->prepare("DELETE FROM app_social_posts WHERE id = ? AND app_id = ?");
                $stmt->execute([$postId, $this->app['id']]);
            }
        }

        if ($action === 'get_post_comments_ajax') {
            header('Content-Type: application/json');
            $postId = (int)($data['post_id'] ?? 0);
            
            $stmt = $this->pdo->prepare("
                SELECT c.*, u.name as user_name, u.avatar as user_avatar
                FROM app_social_post_comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.post_id = ?
                ORDER BY c.created_at ASC
            ");
            $stmt->execute([$postId]);
            $comments = $stmt->fetchAll();
            
            foreach ($comments as &$c) {
                if ($c['file_path']) {
                    $c['file_path'] = BASE_URL . '/public/' . $c['file_path'];
                }
                if ($c['user_avatar']) {
                    $c['user_avatar'] = BASE_URL . '/public/' . $c['user_avatar'];
                }
                $c['created_at_formatted'] = date('d.m.Y H:i', strtotime($c['created_at']));
            }
            
            echo json_encode(['success' => true, 'comments' => $comments]);
            exit;
        }

        if ($action === 'add_post_comment_ajax') {
            header('Content-Type: application/json');
            $postId = (int)($data['post_id'] ?? 0);
            $commentText = trim($data['comment'] ?? '');
            
            if (!$postId) {
                echo json_encode(['success' => false, 'message' => 'Geçersiz gönderi ID.']);
                exit;
            }
            
            $filePath = null;
            $fileName = null;
            
            if (!empty($_FILES['attachment']['name'])) {
                $ext = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
                $newName = uniqid('soc_comment_') . '.' . $ext;
                $uploadDir = __DIR__ . '/../public/uploads/social/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadDir . $newName)) {
                    $filePath = 'uploads/social/' . $newName;
                    $fileName = $_FILES['attachment']['name'];
                }
            }
            
            if ($commentText || $filePath) {
                $stmt = $this->pdo->prepare("INSERT INTO app_social_post_comments (post_id, user_id, comment, file_path, file_name) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$postId, $this->user['id'], $commentText, $filePath, $fileName]);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Yorum veya dosya eklemelisiniz.']);
            }
            exit;
        }

        $this->redirectBack();
    }

    private function redirectBack()
    {
        $url = BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}";
        header("Location: " . $url);
        exit;
    }
}
