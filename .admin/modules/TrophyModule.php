<?php
// c:\xampp\htdocs\innomis-admin\modules\TrophyModule.php

class TrophyModule extends BaseModule
{
    public function render(): string
    {
        $canEdit = $this->isDirectorOrAdmin() || !empty($this->department['is_management']);
        
        $stmt = $this->pdo->prepare("
            SELECT * FROM app_trophies 
            WHERE app_id = ? AND department_id = ? 
            ORDER BY award_date DESC, created_at DESC
        ");
        $stmt->execute([$this->app['id'], $this->department['id']]);
        $trophies = $stmt->fetchAll();

        ob_start();
        extract([
            'app' => $this->app,
            'department' => $this->department,
            'user' => $this->user,
            'canEdit' => $canEdit,
            'trophies' => $trophies
        ]);
        require __DIR__ . '/../views/modules/trophy.php';
        return ob_get_clean();
    }

    public function handlePost(array $data): void
    {
        $action = $data['action'] ?? '';
        $canEdit = $this->isDirectorOrAdmin() || !empty($this->department['is_management']);

        if (!$canEdit) {
            $this->redirectBack();
        }

        if ($action === 'add_trophy') {
            $title = trim($data['title'] ?? '');
            $description = trim($data['description'] ?? '');
            $awardType = $data['award_type'] ?? 'other';
            $awardDate = $data['award_date'] ?? date('Y-m-d');
            
            // Handle File Upload
            $imagePath = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['image']['tmp_name'];
                $fileName = basename($_FILES['image']['name']);
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','webp'];
                
                if (in_array($ext, $allowed)) {
                    $newName = uniqid('trophy_') . '.' . $ext;
                    $destPath = __DIR__ . '/../public/uploads/trophies/' . $newName;
                    if (move_uploaded_file($fileTmp, $destPath)) {
                        $imagePath = $newName;
                    }
                }
            }

            if ($title && $imagePath) {
                $stmt = $this->pdo->prepare("INSERT INTO app_trophies (app_id, department_id, title, description, award_type, award_date, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$this->app['id'], $this->department['id'], $title, $description, $awardType, $awardDate, $imagePath]);
            }
        }
        if ($action === 'edit_trophy') {
            $trophyId = (int)($data['trophy_id'] ?? 0);
            $title = trim($data['title'] ?? '');
            $description = trim($data['description'] ?? '');
            $awardType = $data['award_type'] ?? 'other';
            $awardDate = $data['award_date'] ?? date('Y-m-d');
            
            // Handle File Upload
            $imagePath = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['image']['tmp_name'];
                $fileName = basename($_FILES['image']['name']);
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','webp'];
                
                if (in_array($ext, $allowed)) {
                    $newName = uniqid('trophy_') . '.' . $ext;
                    $destPath = __DIR__ . '/../public/uploads/trophies/' . $newName;
                    if (move_uploaded_file($fileTmp, $destPath)) {
                        $imagePath = $newName;
                        
                        // Delete old image
                        $stmt = $this->pdo->prepare("SELECT image_path FROM app_trophies WHERE id = ?");
                        $stmt->execute([$trophyId]);
                        $oldImage = $stmt->fetchColumn();
                        if ($oldImage) {
                            $oldFilePath = __DIR__ . '/../public/uploads/trophies/' . $oldImage;
                            if (file_exists($oldFilePath)) {
                                @unlink($oldFilePath);
                            }
                        }
                    }
                }
            }

            if ($title && $trophyId) {
                if ($imagePath) {
                    $stmt = $this->pdo->prepare("UPDATE app_trophies SET title=?, description=?, award_type=?, award_date=?, image_path=? WHERE id=?");
                    $stmt->execute([$title, $description, $awardType, $awardDate, $imagePath, $trophyId]);
                } else {
                    $stmt = $this->pdo->prepare("UPDATE app_trophies SET title=?, description=?, award_type=?, award_date=? WHERE id=?");
                    $stmt->execute([$title, $description, $awardType, $awardDate, $trophyId]);
                }
            }
        }
        if ($action === 'delete_trophy') {
            $trophyId = (int)($data['trophy_id'] ?? 0);
            if ($trophyId) {
                // Get image path to delete file
                $stmt = $this->pdo->prepare("SELECT image_path FROM app_trophies WHERE id = ? AND app_id = ?");
                $stmt->execute([$trophyId, $this->app['id']]);
                $trophy = $stmt->fetch();
                
                if ($trophy) {
                    $filePath = __DIR__ . '/../public/uploads/trophies/' . $trophy['image_path'];
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                    $delStmt = $this->pdo->prepare("DELETE FROM app_trophies WHERE id = ?");
                    $delStmt->execute([$trophyId]);
                }
            }
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
