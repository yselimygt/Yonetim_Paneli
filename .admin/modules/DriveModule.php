<?php

require_once __DIR__ . '/BaseModule.php';

class DriveModule extends BaseModule
{
    public function render(): string
    {
        $parentId = isset($_GET['folder']) && is_numeric($_GET['folder']) ? (int)$_GET['folder'] : null;
        $sort = $_GET['sort'] ?? 'name_asc';
        $search = trim($_GET['q'] ?? '');
        $starred = isset($_GET['starred']) && $_GET['starred'] == '1';

        $isSearchOrStarred = ($search !== '' || $starred);
        if ($isSearchOrStarred) {
            $parentId = null;
        }

        // Ekmek kırıntısı (Breadcrumbs)
        $breadcrumbs = [];
        $currentFolder = null;
        if ($parentId && !$isSearchOrStarred) {
            $currentFolder = $this->getItem($parentId);
            if ($currentFolder && $currentFolder['type'] === 'folder') {
                $breadcrumbs = $this->getBreadcrumbs($parentId);
            } else {
                $parentId = null; // Geçersiz folder
                $currentFolder = null;
            }
        }

        // Klasördeki dosyaları ve klasörleri al
        $items = $this->getItems($parentId, $sort, $search, $starred);

        // Tüm klasörleri (hiyerarşi olmaksızın düz liste olarak) taşıma/kopyalama hedefi için al
        $stmt = $this->pdo->prepare("SELECT id, name, parent_id FROM drive_items WHERE app_id = ? AND type = 'folder' ORDER BY name ASC");
        $stmt->execute([$this->app['id']]);
        $allFolders = $stmt->fetchAll();

        return $this->renderView('drive_admin', compact('items', 'breadcrumbs', 'currentFolder', 'parentId', 'allFolders', 'sort', 'search', 'starred', 'isSearchOrStarred'));
    }

    public function handlePost(array $postData): void
    {
        $action = $postData['action'] ?? '';
        $parentId = !empty($postData['parent_id']) ? (int)$postData['parent_id'] : null;

        if ($action === 'create_folder') {
            $name = trim($postData['name'] ?? 'Yeni Klasör');
            if ($name === '') $name = 'Yeni Klasör';

            $stmt = $this->pdo->prepare("INSERT INTO drive_items (app_id, department_id, created_by, parent_id, type, name) VALUES (?, ?, ?, ?, 'folder', ?)");
            $stmt->execute([
                $this->app['id'],
                $this->department['id'],
                $this->user['id'],
                $parentId,
                $name
            ]);
            $_SESSION['success_msg'] = "Klasör başarıyla oluşturuldu.";
        } 
        elseif ($action === 'upload_file') {
            $isAjax = !empty($_POST['is_ajax']);
            
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/drive/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileName = $_FILES['file']['name'];
                $fileSize = $_FILES['file']['size'];
                $fileTmp = $_FILES['file']['tmp_name'];

                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif', 'zip', 'rar', 'txt', 'csv'];

                // Güvenlik: Gerçek MIME type kontrolü (finfo ile)
                $allowedMimes = [
                    'application/pdf', 'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'image/jpeg', 'image/png', 'image/gif',
                    'application/zip', 'application/x-rar-compressed', 'application/x-rar',
                    'text/plain', 'text/csv', 'application/csv',
                    'application/octet-stream' // rar ve bazı formatlar için fallback
                ];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $realMime = finfo_file($finfo, $fileTmp);
                finfo_close($finfo);

                if (!in_array($ext, $allowed)) {
                    $msg = "Bu dosya türüne izin verilmiyor.";
                    if($isAjax) { header('Content-Type: application/json'); echo json_encode(['status'=>'error', 'message'=>$msg]); exit; }
                    else { $_SESSION['error_msg'] = $msg; }
                } elseif (!in_array($realMime, $allowedMimes)) {
                    $msg = "Dosya içeriği uzantıyla uyuşmuyor. Yükleme reddedildi.";
                    if($isAjax) { header('Content-Type: application/json'); echo json_encode(['status'=>'error', 'message'=>$msg]); exit; }
                    else { $_SESSION['error_msg'] = $msg; }
                } else {
                    $newFileName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '', $fileName);
                    $destPath = $uploadDir . $newFileName;

                    if (move_uploaded_file($fileTmp, $destPath)) {
                        $stmt = $this->pdo->prepare("INSERT INTO drive_items (app_id, department_id, created_by, parent_id, type, name, file_path, file_size, mime_type) VALUES (?, ?, ?, ?, 'file', ?, ?, ?, ?)");
                        $stmt->execute([
                            $this->app['id'],
                            $this->department['id'],
                            $this->user['id'],
                            $parentId,
                            $fileName,
                            'uploads/drive/' . $newFileName,
                            $fileSize,
                            $realMime // Güvenlik: İstemci yerine doğrulanmış MIME kullan
                        ]);
                        $msg = "Dosya başarıyla yüklendi.";
                        if($isAjax) { header('Content-Type: application/json'); echo json_encode(['status'=>'success', 'message'=>$msg]); exit; }
                        else { $_SESSION['success_msg'] = $msg; }
                    } else {
                        $msg = "Dosya yüklenirken bir hata oluştu.";
                        if($isAjax) { header('Content-Type: application/json'); echo json_encode(['status'=>'error', 'message'=>$msg]); exit; }
                        else { $_SESSION['error_msg'] = $msg; }
                    }
                }
            } else {
                $msg = "Lütfen bir dosya seçin veya hata kodunu kontrol edin.";
                if($isAjax) { header('Content-Type: application/json'); echo json_encode(['status'=>'error', 'message'=>$msg]); exit; }
                else { $_SESSION['error_msg'] = $msg; }
            }
        }
        elseif ($action === 'delete_item') {
            $itemId = (int)($postData['item_id'] ?? 0);
            $item = $this->getItem($itemId);

            if ($item) {
                // Delete physical files
                $this->deletePhysicalFiles($itemId);

                $stmt = $this->pdo->prepare("DELETE FROM drive_items WHERE id = ? AND app_id = ?");
                $stmt->execute([$itemId, $this->app['id']]);
                $_SESSION['success_msg'] = "Öğe başarıyla silindi.";
            }
        }
        elseif ($action === 'rename_item') {
            $itemId = (int)($postData['item_id'] ?? 0);
            $newName = trim($postData['new_name'] ?? '');
            if ($newName !== '') {
                $stmt = $this->pdo->prepare("UPDATE drive_items SET name = ? WHERE id = ? AND app_id = ?");
                $stmt->execute([$newName, $itemId, $this->app['id']]);
                $_SESSION['success_msg'] = "Ad başarıyla güncellendi.";
            }
        }
        elseif ($action === 'move_items') {
            $itemIds = explode(',', $postData['item_ids'] ?? '');
            $targetParentId = ($postData['target_parent_id'] ?? '') !== '' ? (int)$postData['target_parent_id'] : null;

            $successCount = 0;
            foreach ($itemIds as $id) {
                $id = (int)trim($id);
                if (!$id) continue;
                
                if ($this->canMoveTo($id, $targetParentId)) {
                    $stmt = $this->pdo->prepare("UPDATE drive_items SET parent_id = ? WHERE id = ? AND app_id = ?");
                    $stmt->execute([$targetParentId, $id, $this->app['id']]);
                    $successCount++;
                }
            }
            $_SESSION['success_msg'] = "$successCount öğe başarıyla taşındı.";
        }
        elseif ($action === 'copy_items') {
            $itemIds = explode(',', $postData['item_ids'] ?? '');
            $targetParentId = ($postData['target_parent_id'] ?? '') !== '' ? (int)$postData['target_parent_id'] : null;

            $successCount = 0;
            foreach ($itemIds as $id) {
                $id = (int)trim($id);
                if (!$id) continue;
                
                if ($this->copyItem($id, $targetParentId)) {
                    $successCount++;
                }
            }
            $_SESSION['success_msg'] = "$successCount öğe başarıyla kopyalandı.";
        }
        elseif ($action === 'bulk_delete') {
            $itemIds = explode(',', $postData['item_ids'] ?? '');
            $successCount = 0;
            foreach ($itemIds as $id) {
                $id = (int)trim($id);
                if (!$id) continue;
                
                $item = $this->getItem($id);
                if ($item) {
                    $this->deletePhysicalFiles($id);
                    $stmt = $this->pdo->prepare("DELETE FROM drive_items WHERE id = ? AND app_id = ?");
                    $stmt->execute([$id, $this->app['id']]);
                    $successCount++;
                }
            }
            $_SESSION['success_msg'] = "$successCount öğe silindi.";
        }
        elseif ($action === 'toggle_star') {
            $itemId = (int)($postData['item_id'] ?? 0);
            $isAjax = !empty($_POST['is_ajax']);
            $item = $this->getItem($itemId);
            if ($item) {
                $newVal = $item['is_starred'] ? 0 : 1;
                $stmt = $this->pdo->prepare("UPDATE drive_items SET is_starred = ? WHERE id = ? AND app_id = ?");
                $stmt->execute([$newVal, $itemId, $this->app['id']]);
                if($isAjax) { header('Content-Type: application/json'); echo json_encode(['status'=>'success', 'is_starred'=>$newVal]); exit; }
            }
            if($isAjax) { header('Content-Type: application/json'); echo json_encode(['status'=>'error']); exit; }
        }

        $folderQuery = $parentId ? "?folder=" . $parentId : "";
        header("Location: " . BASE_URL . "/panel/" . $this->department['slug'] . "/apps/" . $this->app['slug'] . $folderQuery);
        exit;
    }

    private function getItems(?int $parentId, string $sort = 'name_asc', string $search = '', bool $starred = false): array
    {
        $orderSql = "(d.type = 'folder') DESC, d.name ASC"; // default
        if ($sort === 'name_desc') $orderSql = "(d.type = 'folder') DESC, d.name DESC";
        elseif ($sort === 'date_desc') $orderSql = "(d.type = 'folder') DESC, d.created_at DESC";
        elseif ($sort === 'date_asc') $orderSql = "(d.type = 'folder') DESC, d.created_at ASC";
        elseif ($sort === 'size_desc') $orderSql = "(d.type = 'folder') DESC, d.file_size DESC";
        elseif ($sort === 'size_asc') $orderSql = "(d.type = 'folder') DESC, d.file_size ASC";
        elseif ($sort === 'creator_asc') $orderSql = "(d.type = 'folder') DESC, u.name ASC";
        elseif ($sort === 'creator_desc') $orderSql = "(d.type = 'folder') DESC, u.name DESC";

        $params = [$this->app['id']];
        $whereSql = "d.app_id = ?";

        if ($search !== '') {
            $whereSql .= " AND d.name LIKE ?";
            $params[] = "%$search%";
        } elseif ($starred) {
            $whereSql .= " AND d.is_starred = 1";
        } else {
            if ($parentId === null) {
                $whereSql .= " AND d.parent_id IS NULL";
            } else {
                $whereSql .= " AND d.parent_id = ?";
                $params[] = $parentId;
            }
        }

        $stmt = $this->pdo->prepare("SELECT d.*, u.name as creator_name FROM drive_items d LEFT JOIN users u ON d.created_by = u.id WHERE $whereSql ORDER BY $orderSql");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    private function getItem(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM drive_items WHERE id = ? AND app_id = ?");
        $stmt->execute([$id, $this->app['id']]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    private function getBreadcrumbs(int $folderId): array
    {
        $breadcrumbs = [];
        $current = $folderId;

        while ($current) {
            $item = $this->getItem($current);
            if ($item) {
                array_unshift($breadcrumbs, $item);
                $current = $item['parent_id'];
            } else {
                break;
            }
        }
        return $breadcrumbs;
    }

    private function deletePhysicalFiles(int $itemId): void
    {
        $item = $this->getItem($itemId);
        if (!$item) return;

        if ($item['type'] === 'file' && !empty($item['file_path'])) {
            $path = __DIR__ . '/../public/' . $item['file_path'];
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($item['type'] === 'folder') {
            // Find all children recursively
            $stmt = $this->pdo->prepare("SELECT id FROM drive_items WHERE parent_id = ?");
            $stmt->execute([$itemId]);
            $children = $stmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($children as $childId) {
                $this->deletePhysicalFiles($childId);
            }
        }
    }

    private function canMoveTo(int $itemId, ?int $targetId): bool
    {
        if ($targetId === null) return true;
        if ($itemId === $targetId) return false;

        $current = $targetId;
        while ($current) {
            $item = $this->getItem($current);
            if (!$item) return false;
            if ($item['parent_id'] === $itemId || $current === $itemId) return false;
            $current = $item['parent_id'];
        }
        return true;
    }

    private function copyItem(int $itemId, ?int $targetParentId): bool
    {
        $item = $this->getItem($itemId);
        if (!$item) return false;

        $newName = $item['name'] . ' - Kopya';
        $newFilePath = null;

        if ($item['type'] === 'file' && !empty($item['file_path'])) {
            $oldPhysicalPath = __DIR__ . '/../public/' . $item['file_path'];
            if (file_exists($oldPhysicalPath)) {
                $ext = pathinfo($oldPhysicalPath, PATHINFO_EXTENSION);
                $newFileName = uniqid() . '_' . rand(1000, 9999) . ($ext ? '.' . $ext : '');
                $newPhysicalPath = __DIR__ . '/../public/uploads/drive/' . $newFileName;
                if (copy($oldPhysicalPath, $newPhysicalPath)) {
                    $newFilePath = 'uploads/drive/' . $newFileName;
                }
            }
        }

        $stmt = $this->pdo->prepare("INSERT INTO drive_items (app_id, department_id, created_by, parent_id, type, name, file_path, file_size, mime_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $this->app['id'],
            $this->department['id'],
            $this->user['id'],
            $targetParentId,
            $item['type'],
            $newName,
            $newFilePath,
            $item['file_size'],
            $item['mime_type']
        ]);

        $newItemId = (int)$this->pdo->lastInsertId();

        if ($item['type'] === 'folder') {
            // Copy children recursively
            $stmt = $this->pdo->prepare("SELECT id FROM drive_items WHERE parent_id = ?");
            $stmt->execute([$itemId]);
            $children = $stmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($children as $childId) {
                // When copying children, we don't append "- Kopya" again, but the logic above does.
                // It's a minor detail. If we wanted exact copy, we'd need a helper that takes an exact name parameter.
                // Let's keep it simple for now, or just let them be "- Kopya".
                // Wait, it's better to preserve the original name for inner files.
                // I will extract the insert to a helper method if needed, but this is fine for now.
                $this->copyItemInner($childId, $newItemId);
            }
        }

        return true;
    }

    private function copyItemInner(int $itemId, ?int $targetParentId): bool
    {
        $item = $this->getItem($itemId);
        if (!$item) return false;

        $newName = $item['name']; // No " - Kopya" for inner children
        $newFilePath = null;

        if ($item['type'] === 'file' && !empty($item['file_path'])) {
            $oldPhysicalPath = __DIR__ . '/../public/' . $item['file_path'];
            if (file_exists($oldPhysicalPath)) {
                $ext = pathinfo($oldPhysicalPath, PATHINFO_EXTENSION);
                $newFileName = uniqid() . '_' . rand(1000, 9999) . ($ext ? '.' . $ext : '');
                $newPhysicalPath = __DIR__ . '/../public/uploads/drive/' . $newFileName;
                if (copy($oldPhysicalPath, $newPhysicalPath)) {
                    $newFilePath = 'uploads/drive/' . $newFileName;
                }
            }
        }

        $stmt = $this->pdo->prepare("INSERT INTO drive_items (app_id, department_id, created_by, parent_id, type, name, file_path, file_size, mime_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $this->app['id'],
            $this->department['id'],
            $this->user['id'],
            $targetParentId,
            $item['type'],
            $newName,
            $newFilePath,
            $item['file_size'],
            $item['mime_type']
        ]);

        $newItemId = (int)$this->pdo->lastInsertId();

        if ($item['type'] === 'folder') {
            $stmt = $this->pdo->prepare("SELECT id FROM drive_items WHERE parent_id = ?");
            $stmt->execute([$itemId]);
            $children = $stmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($children as $childId) {
                $this->copyItemInner($childId, $newItemId);
            }
        }
        return true;
    }
}
