<?php

class AnnouncementModule extends BaseModule
{
    public function render(): string
    {
        $config = $this->getConfig();
        $allowMemberPost = !empty($config['allow_member_post']);

        // Fetch announcements: department-specific + global ones
        $stmt = $this->pdo->prepare("
            SELECT a.*, u.name AS author_name 
            FROM announcements a
            JOIN users u ON u.id = a.author_id
            ORDER BY a.is_pinned DESC, a.created_at DESC
        ");
        $stmt->execute();
        $announcements = $stmt->fetchAll();

        $canPost = $this->isDirectorOrAdmin() || $allowMemberPost;

        return $this->renderView('announcement', compact('announcements', 'canPost'));
    }

    public function handlePost(array $postData): void
    {
        $action = $postData['action'] ?? '';
        $config = $this->getConfig();
        $allowMemberPost = !empty($config['allow_member_post']);
        $canPost = $this->isDirectorOrAdmin() || $allowMemberPost;

        if ($action === 'add' && $canPost) {
            $title = trim($postData['title'] ?? '');
            $content = trim($postData['content'] ?? '');
            $isPinned = isset($postData['is_pinned']) ? 1 : 0;

            if (empty($title) || empty($content)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Başlık ve içerik doldurulmalıdır.'];
                header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
                exit;
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO announcements (app_id, department_id, author_id, title, content, is_pinned, is_global) 
                VALUES (?, ?, ?, ?, ?, ?, 0)
            ");
            $stmt->execute([
                $this->app['id'],
                $this->department['id'],
                $this->user['id'],
                $title,
                $content,
                $isPinned
            ]);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Duyuru başarıyla yayınlandı.'];
            header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
            exit;
        }

        if ($action === 'delete' && $this->isDirectorOrAdmin()) {
            $announcementId = (int) ($postData['announcement_id'] ?? 0);
            
            // Delete
            $stmt = $this->pdo->prepare("DELETE FROM announcements WHERE id = ?");
            $stmt->execute([$announcementId]);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Duyuru silindi.'];
            header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
            exit;
        }

        if ($action === 'toggle_pin' && $this->isDirectorOrAdmin()) {
            $announcementId = (int) ($postData['announcement_id'] ?? 0);
            
            // Get current pin status
            $stmtStatus = $this->pdo->prepare("SELECT is_pinned FROM announcements WHERE id = ?");
            $stmtStatus->execute([$announcementId]);
            $isPinned = $stmtStatus->fetchColumn();

            $newPin = $isPinned ? 0 : 1;
            $stmtUpdate = $this->pdo->prepare("UPDATE announcements SET is_pinned = ? WHERE id = ?");
            $stmtUpdate->execute([$newPin, $announcementId]);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Duyuru sabitleme durumu güncellendi.'];
            header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
            exit;
        }
    }
}
