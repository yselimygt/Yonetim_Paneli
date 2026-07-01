<?php

class InboxModule extends BaseModule
{
    public function render(): string
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM app_inbox_messages 
            WHERE app_id = ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$this->app['id']]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->renderView('inbox', compact('messages'));
    }

    public function handlePost(array $postData): void
    {
        $action = $postData['action'] ?? '';

        if ($action === 'mark_read') {
            $msgId = (int)($postData['message_id'] ?? 0);
            if ($msgId > 0) {
                $stmt = $this->pdo->prepare("UPDATE app_inbox_messages SET status = 'read' WHERE id = ? AND app_id = ?");
                $stmt->execute([$msgId, $this->app['id']]);
                
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Mesaj okundu olarak işaretlendi.'];
            }
        } elseif ($action === 'mark_unread') {
            $msgId = (int)($postData['message_id'] ?? 0);
            if ($msgId > 0) {
                $stmt = $this->pdo->prepare("UPDATE app_inbox_messages SET status = 'unread' WHERE id = ? AND app_id = ?");
                $stmt->execute([$msgId, $this->app['id']]);
                
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Mesaj okunmadı olarak işaretlendi.'];
            }
        } elseif ($action === 'delete') {
            $msgId = (int)($postData['message_id'] ?? 0);
            if ($msgId > 0) {
                $stmt = $this->pdo->prepare("DELETE FROM app_inbox_messages WHERE id = ? AND app_id = ?");
                $stmt->execute([$msgId, $this->app['id']]);
                
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Mesaj başarıyla silindi.'];
            }
        }

        header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
        exit;
    }
}
