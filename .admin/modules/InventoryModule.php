<?php

class InventoryModule extends BaseModule
{
    public function render(): string
    {
        $search = $_GET['q'] ?? '';
        $statusFilter = $_GET['status'] ?? '';
        $catFilter = $_GET['category'] ?? '';

        $query = "SELECT * FROM app_inventory WHERE app_id = ? AND department_id = ?";
        $params = [$this->app['id'], $this->department['id']];

        if ($search) {
            $query .= " AND (item_name LIKE ? OR serial_number LIKE ? OR assigned_to LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if ($statusFilter) {
            $query .= " AND status = ?";
            $params[] = $statusFilter;
        }

        if ($catFilter) {
            $query .= " AND category = ?";
            $params[] = $catFilter;
        }

        $query .= " ORDER BY created_at DESC";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $items = $stmt->fetchAll();

        // Benzersiz kategorileri çek (filtreleme için)
        $catStmt = $this->pdo->prepare("SELECT DISTINCT category FROM app_inventory WHERE app_id = ? AND department_id = ? AND category != ''");
        $catStmt->execute([$this->app['id'], $this->department['id']]);
        $categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

        $allUsersStmt = $this->pdo->query("SELECT id, name FROM users ORDER BY name ASC");
        $allUsers = $allUsersStmt->fetchAll();

        $canEdit = $this->isDirectorOrAdmin();

        return $this->renderView('inventory', compact('items', 'categories', 'search', 'statusFilter', 'catFilter', 'canEdit', 'allUsers'));
    }

    public function handlePost(array $postData): void
    {
        $action = $postData['action'] ?? '';
        $canEdit = $this->isDirectorOrAdmin();

        if (!$canEdit) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Bu işlem için yetkiniz yok.'];
            $this->redirectBack();
        }

        if ($action === 'save_item') {
            $id = (int)($postData['item_id'] ?? 0);
            $itemName = trim($postData['item_name'] ?? '');
            $category = trim($postData['category'] ?? '');
            $serialNumber = trim($postData['serial_number'] ?? '');
            $price = (float)($postData['price'] ?? 0);
            $location = trim($postData['location'] ?? '');
            $assignedTo = trim($postData['assigned_to'] ?? '');
            $status = trim($postData['status'] ?? 'active');
            $purchaseDate = !empty($postData['purchase_date']) ? $postData['purchase_date'] : null;
            $notes = trim($postData['notes'] ?? '');

            if (empty($itemName)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Eşya adı zorunludur.'];
                $this->redirectBack();
            }

            // Fotoğraf Yükleme İşlemi
            $imageUrl = $postData['existing_image'] ?? null;
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/inventory/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $filename = uniqid('inv_') . '.' . $ext;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                        // Eski fotoğrafı sil
                        if ($imageUrl && file_exists(__DIR__ . '/../public/' . $imageUrl)) {
                            unlink(__DIR__ . '/../public/' . $imageUrl);
                        }
                        $imageUrl = 'uploads/inventory/' . $filename;
                    }
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Geçersiz dosya formatı. Sadece JPG, PNG, WEBP.'];
                    $this->redirectBack();
                }
            }

            if ($id > 0) {
                $stmt = $this->pdo->prepare("
                    UPDATE app_inventory SET 
                        item_name=?, category=?, serial_number=?, price=?, location=?, 
                        assigned_to=?, status=?, purchase_date=?, notes=?, image_url=?
                    WHERE id=? AND department_id=?
                ");
                $stmt->execute([
                    $itemName, $category, $serialNumber, $price, $location, 
                    $assignedTo, $status, $purchaseDate, $notes, $imageUrl,
                    $id, $this->department['id']
                ]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Eşya başarıyla güncellendi.'];
            } else {
                $stmt = $this->pdo->prepare("
                    INSERT INTO app_inventory 
                        (app_id, department_id, item_name, category, serial_number, price, 
                         location, assigned_to, status, purchase_date, notes, image_url, created_by)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)
                ");
                $stmt->execute([
                    $this->app['id'], $this->department['id'], $itemName, $category, $serialNumber, $price, 
                    $location, $assignedTo, $status, $purchaseDate, $notes, $imageUrl, $this->user['id']
                ]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Yeni eşya başarıyla eklendi.'];
            }
        } elseif ($action === 'delete_item') {
            $id = (int)($postData['item_id'] ?? 0);
            
            // Görseli silmek için veritabanından yolu çek
            $stmt = $this->pdo->prepare("SELECT image_url FROM app_inventory WHERE id=? AND department_id=?");
            $stmt->execute([$id, $this->department['id']]);
            $item = $stmt->fetch();
            
            if ($item && $item['image_url'] && file_exists(__DIR__ . '/../public/' . $item['image_url'])) {
                unlink(__DIR__ . '/../public/' . $item['image_url']);
            }

            $del = $this->pdo->prepare("DELETE FROM app_inventory WHERE id=? AND department_id=?");
            $del->execute([$id, $this->department['id']]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Eşya başarıyla silindi.'];
        }

        $this->redirectBack();
    }

    private function redirectBack(): void
    {
        header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
        exit;
    }
}
