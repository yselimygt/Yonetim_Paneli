<?php

class PasswordsModule extends BaseModule
{
    private string $encryptionKey;

    public function __construct(array $app, array $department, array $user, string $userRole)
    {
        parent::__construct($app, $department, $user, $userRole);
        $this->encryptionKey = ENCRYPTION_KEY;
    }

    public function render(): string
    {
        // Yetki Kontrolü: Sadece Super Admin veya Director görebilir.
        if (!(Auth::isSuperAdmin() || Auth::isPresidentOrVice()) && $this->getUserRole() !== 'director') {
            return "<div style='padding:40px;text-align:center;'><h2 style='color:var(--danger);'>Erişim Reddedildi</h2><p>Şifre yöneticisine erişim yetkiniz bulunmamaktadır.</p></div>";
        }

        // Şifreleri çek
        $stmt = $this->pdo->prepare("SELECT * FROM passwords WHERE app_id = ? AND department_id = ? ORDER BY created_at DESC");
        $stmt->execute([$this->app['id'], $this->department['id']]);
        $passwords = $stmt->fetchAll();

        // Parolaları Decrypt et
        foreach ($passwords as &$pwd) {
            $pwd['decrypted_password'] = $this->decrypt($pwd['encrypted_password']);
        }

        // Düzenleme yetkisi (Super Admin, Yetkili kullanıcı veya o departmanın Direktörü)
        $canManage = Auth::isSuperAdmin() || Auth::isPresidentOrVice() || (!empty(Auth::user()['can_manage_passwords'])) || ($this->getUserRole() === 'director');

        return $this->renderView('passwords', compact('passwords', 'canManage'));
    }

    public function handlePost(array $postData): void
    {
        $canManage = Auth::isSuperAdmin() || Auth::isPresidentOrVice() || (!empty(Auth::user()['can_manage_passwords'])) || ($this->getUserRole() === 'director');
        
        if (!$canManage) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Şifre ekleme/silme yetkiniz yok.'];
            $this->redirectBack();
            return;
        }

        $action = $postData['action'] ?? '';

        if ($action === 'create' || $action === 'update') {
            $title = trim($postData['title'] ?? '');
            $url = trim($postData['url'] ?? '');
            $username = trim($postData['username'] ?? '');
            $rawPassword = $postData['password'] ?? '';
            $notes = trim($postData['notes'] ?? '');

            if (empty($title) || empty($rawPassword)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Başlık ve şifre zorunludur.'];
                $this->redirectBack();
                return;
            }

            $encrypted = $this->encrypt($rawPassword);

            if ($action === 'create') {
                $stmt = $this->pdo->prepare("INSERT INTO passwords (department_id, app_id, title, url, username, encrypted_password, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$this->department['id'], $this->app['id'], $title, $url, $username, $encrypted, $notes]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Şifre başarıyla eklendi.'];
            } else {
                $id = (int)($postData['id'] ?? 0);
                $stmt = $this->pdo->prepare("UPDATE passwords SET title = ?, url = ?, username = ?, encrypted_password = ?, notes = ? WHERE id = ? AND app_id = ?");
                $stmt->execute([$title, $url, $username, $encrypted, $notes, $id, $this->app['id']]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Şifre başarıyla güncellendi.'];
            }
        } elseif ($action === 'delete') {
            $id = (int)($postData['id'] ?? 0);
            $stmt = $this->pdo->prepare("DELETE FROM passwords WHERE id = ? AND app_id = ?");
            $stmt->execute([$id, $this->app['id']]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Şifre başarıyla silindi.'];
        }

        $this->redirectBack();
    }

    private function redirectBack()
    {
        header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
        exit;
    }

    private function getUserRole()
    {
        $stmt = $this->pdo->prepare("SELECT role FROM user_departments WHERE user_id = ? AND department_id = ?");
        $stmt->execute([Auth::user()['id'], $this->department['id']]);
        $row = $stmt->fetch();
        return $row ? $row['role'] : 'member';
    }

    private function encrypt($data)
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $this->encryptionKey, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    private function decrypt($data)
    {
        $decoded = base64_decode($data);
        if (strpos($decoded, '::') === false) return '';
        list($encrypted_data, $iv) = explode('::', $decoded, 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $this->encryptionKey, 0, $iv);
    }
}
