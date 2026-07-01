<?php

require_once __DIR__ . '/BaseModule.php';
require_once __DIR__ . '/../core/Database.php';

class DirectoryModule extends BaseModule
{
    public function __construct(array $app, array $dept, ?array $user, string $userRole)
    {
        parent::__construct($app, $dept, $user, $userRole);
    }

    public function render(): string
    {
        $appId = $this->app['id'];
        
        $searchTerm = $_GET['q'] ?? '';
        
        // Search filter logic
        $query = "SELECT * FROM app_directory";
        $params = [];
        
        if (!empty($searchTerm)) {
            $query .= " WHERE (full_name LIKE ? OR company LIKE ? OR title LIKE ?)";
            $params[] = "%$searchTerm%";
            $params[] = "%$searchTerm%";
            $params[] = "%$searchTerm%";
        }
        
        $query .= " ORDER BY full_name ASC";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // JSON decode arrays
        foreach ($contacts as &$contact) {
            $contact['emails'] = json_decode($contact['emails'] ?? '[]', true) ?: [];
            $contact['phones'] = json_decode($contact['phones'] ?? '[]', true) ?: [];
            $contact['links']  = json_decode($contact['links'] ?? '[]', true) ?: [];
        }

        ob_start();
        extract([
            'contacts'   => $contacts,
            'searchTerm' => $searchTerm,
            'app'        => $this->app,
            'dept'       => $this->department,
            'userRole'   => $this->userRole
        ]);
        require __DIR__ . '/../views/modules/directory.php';
        return ob_get_clean();
    }

    public function handlePost(array $post): void
    {
        $action = $post['action'] ?? '';
        $appId  = $this->app['id'];
        $deptId = $this->department['id'];
        $userId = $this->user['id'] ?? null;

        try {
            if ($action === 'create' || $action === 'update') {
                $contactId = (int)($post['contact_id'] ?? 0);
                $full_name = trim($post['full_name'] ?? '');
                $company   = trim($post['company'] ?? '');
                $title     = trim($post['title'] ?? '');
                $notes     = trim($post['notes'] ?? '');

                if (empty($full_name)) {
                    throw new Exception("Ad Soyad zorunludur.");
                }

                // Process arrays
                $emails = array_filter(array_map('trim', $post['emails'] ?? []));
                $phones = array_filter(array_map('trim', $post['phones'] ?? []));
                $links  = array_filter(array_map('trim', $post['links'] ?? []));

                $emailsJson = json_encode(array_values($emails), JSON_UNESCAPED_UNICODE);
                $phonesJson = json_encode(array_values($phones), JSON_UNESCAPED_UNICODE);
                $linksJson  = json_encode(array_values($links), JSON_UNESCAPED_UNICODE);

                if ($action === 'create') {
                    $stmt = $this->pdo->prepare("INSERT INTO app_directory (app_id, department_id, created_by, full_name, company, title, emails, phones, links, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$appId, $deptId, $userId, $full_name, $company, $title, $emailsJson, $phonesJson, $linksJson, $notes]);
                    $_SESSION['flash_success'] = 'Kişi/Kurum başarıyla rehbere eklendi.';
                } else {
                    $stmt = $this->pdo->prepare("UPDATE app_directory SET full_name=?, company=?, title=?, emails=?, phones=?, links=?, notes=? WHERE id=?");
                    $stmt->execute([$full_name, $company, $title, $emailsJson, $phonesJson, $linksJson, $notes, $contactId]);
                    $_SESSION['flash_success'] = 'Kayıt güncellendi.';
                }
            } elseif ($action === 'delete') {
                $contactId = (int)($post['contact_id'] ?? 0);
                $stmt = $this->pdo->prepare("DELETE FROM app_directory WHERE id=?");
                $stmt->execute([$contactId]);
                $_SESSION['flash_success'] = 'Kayıt silindi.';
            }
        } catch (Throwable $e) {
            $_SESSION['flash_error'] = $e->getMessage();
        }

        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . "/panel"));
        exit;
    }
}
