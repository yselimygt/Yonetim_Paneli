<?php

class ApplicationsModule extends BaseModule
{
    public function render(): string
    {
        $stmt = $this->pdo->query("SELECT * FROM app_applications ORDER BY created_at DESC");
        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // applications görünümüne veriyi gönder
        return $this->renderView('applications', compact('applications'));
    }

    public function handlePost(array $postData): void
    {
        $action = $postData['action'] ?? '';

        if ($action === 'status') {
            $id = (int)($postData['id'] ?? 0);
            $status = $postData['status'] ?? '';
            
            if ($id > 0 && in_array($status, ['pending', 'reviewing', 'approved', 'rejected'])) {
                $stmt = $this->pdo->prepare("UPDATE app_applications SET status = ? WHERE id = ?");
                $stmt->execute([$status, $id]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Başvuru durumu güncellendi.'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Geçersiz durum.'];
            }
        } elseif ($action === 'delete') {
            $id = (int)($postData['id'] ?? 0);
            if ($id > 0) {
                $stmt = $this->pdo->prepare("DELETE FROM app_applications WHERE id = ?");
                $stmt->execute([$id]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Başvuru başarıyla silindi.'];
            }
        }

        header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
        exit;
    }
}
