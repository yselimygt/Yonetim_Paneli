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
            $institutionName = trim($data['institution_name'] ?? '');
            
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

            // Handle Institution Logo Upload
            $institutionLogo = '';
            if (isset($_FILES['institution_logo']) && $_FILES['institution_logo']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['institution_logo']['tmp_name'];
                $fileName = basename($_FILES['institution_logo']['name']);
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','webp', 'svg'];
                
                if (in_array($ext, $allowed)) {
                    $newName = uniqid('inst_logo_') . '.' . $ext;
                    $destPath = __DIR__ . '/../public/uploads/trophies/' . $newName;
                    if (move_uploaded_file($fileTmp, $destPath)) {
                        $institutionLogo = $newName;
                    }
                }
            }

            // Handle PDF File Upload
            $pdfUrl = '';
            if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['pdf_file']['tmp_name'];
                $fileName = basename($_FILES['pdf_file']['name']);
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowed = ['pdf'];
                
                if (in_array($ext, $allowed)) {
                    $newName = uniqid('trophy_doc_') . '.' . $ext;
                    $destPath = __DIR__ . '/../public/uploads/trophies/' . $newName;
                    if (move_uploaded_file($fileTmp, $destPath)) {
                        $pdfUrl = BASE_URL . '/public/uploads/trophies/' . $newName;
                    }
                }
            }

            $link = trim($data['link'] ?? '');

            if ($title && $imagePath) {
                $stmt = $this->pdo->prepare("INSERT INTO app_trophies (app_id, department_id, title, description, award_type, award_date, image_path, institution_name, institution_logo, pdf_url, link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$this->app['id'], $this->department['id'], $title, $description, $awardType, $awardDate, $imagePath, $institutionName, $institutionLogo, $pdfUrl, $link]);
            }
        }
        if ($action === 'edit_trophy') {
            $trophyId = (int)($data['trophy_id'] ?? 0);
            $title = trim($data['title'] ?? '');
            $description = trim($data['description'] ?? '');
            $awardType = $data['award_type'] ?? 'other';
            $awardDate = $data['award_date'] ?? date('Y-m-d');
            $institutionName = trim($data['institution_name'] ?? '');
            
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

            // Handle Institution Logo Upload
            $institutionLogo = '';
            $updateLogoSql = '';
            $logoParams = [];
            if (isset($_FILES['institution_logo']) && $_FILES['institution_logo']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['institution_logo']['tmp_name'];
                $fileName = basename($_FILES['institution_logo']['name']);
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','webp', 'svg'];
                
                if (in_array($ext, $allowed)) {
                    $newName = uniqid('inst_logo_') . '.' . $ext;
                    $destPath = __DIR__ . '/../public/uploads/trophies/' . $newName;
                    if (move_uploaded_file($fileTmp, $destPath)) {
                        $institutionLogo = $newName;
                        $updateLogoSql = ", institution_logo=?";
                        $logoParams[] = $institutionLogo;
                        
                        // Delete old logo
                        $stmt = $this->pdo->prepare("SELECT institution_logo FROM app_trophies WHERE id = ?");
                        $stmt->execute([$trophyId]);
                        $oldLogo = $stmt->fetchColumn();
                        if ($oldLogo) {
                            $oldFilePath = __DIR__ . '/../public/uploads/trophies/' . $oldLogo;
                            if (file_exists($oldFilePath)) {
                                @unlink($oldFilePath);
                            }
                        }
                    }
                }
            }

            // Handle PDF File Upload
            $pdfUrl = '';
            $updatePdfSql = '';
            $pdfParams = [];
            if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['pdf_file']['tmp_name'];
                $fileName = basename($_FILES['pdf_file']['name']);
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowed = ['pdf'];
                
                if (in_array($ext, $allowed)) {
                    $newName = uniqid('trophy_doc_') . '.' . $ext;
                    $destPath = __DIR__ . '/../public/uploads/trophies/' . $newName;
                    if (move_uploaded_file($fileTmp, $destPath)) {
                        $pdfUrl = BASE_URL . '/public/uploads/trophies/' . $newName;
                        $updatePdfSql = ", pdf_url=?";
                        $pdfParams[] = $pdfUrl;
                        
                        // Delete old PDF
                        $stmt = $this->pdo->prepare("SELECT pdf_url FROM app_trophies WHERE id = ?");
                        $stmt->execute([$trophyId]);
                        $oldPdf = $stmt->fetchColumn();
                        if ($oldPdf) {
                            $oldFileName = basename($oldPdf);
                            $oldFilePath = __DIR__ . '/../public/uploads/trophies/' . $oldFileName;
                            if (file_exists($oldFilePath)) {
                                @unlink($oldFilePath);
                            }
                        }
                    }
                }
            }

            $link = trim($data['link'] ?? '');

            if ($title && $trophyId) {
                $baseSql = "UPDATE app_trophies SET title=?, description=?, award_type=?, award_date=?, institution_name=?, link=?";
                $params = [$title, $description, $awardType, $awardDate, $institutionName, $link];

                if ($imagePath) {
                    $baseSql .= ", image_path=?";
                    $params[] = $imagePath;
                }
                
                if ($updateLogoSql) {
                    $baseSql .= $updateLogoSql;
                    $params = array_merge($params, $logoParams);
                }
                
                if ($updatePdfSql) {
                    $baseSql .= $updatePdfSql;
                    $params = array_merge($params, $pdfParams);
                }

                $baseSql .= " WHERE id=?";
                $params[] = $trophyId;
                
                $stmt = $this->pdo->prepare($baseSql);
                $stmt->execute($params);
            }
        }
        if ($action === 'delete_trophy') {
            $trophyId = (int)($data['trophy_id'] ?? 0);
            if ($trophyId) {
                // Get image path to delete file
                $stmt = $this->pdo->prepare("SELECT image_path, institution_logo, pdf_url FROM app_trophies WHERE id = ? AND app_id = ?");
                $stmt->execute([$trophyId, $this->app['id']]);
                $trophy = $stmt->fetch();
                
                if ($trophy) {
                    $filePath = __DIR__ . '/../public/uploads/trophies/' . $trophy['image_path'];
                    if ($trophy['image_path'] && file_exists($filePath)) {
                        @unlink($filePath);
                    }
                    if (!empty($trophy['institution_logo'])) {
                        $logoPath = __DIR__ . '/../public/uploads/trophies/' . $trophy['institution_logo'];
                        if (file_exists($logoPath)) {
                            @unlink($logoPath);
                        }
                    }
                    if (!empty($trophy['pdf_url'])) {
                        $pdfFileName = basename($trophy['pdf_url']);
                        $pdfPath = __DIR__ . '/../public/uploads/trophies/' . $pdfFileName;
                        if (file_exists($pdfPath)) {
                            @unlink($pdfPath);
                        }
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
