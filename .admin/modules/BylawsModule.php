<?php
// c:\xampp\htdocs\innomis-admin\modules\BylawsModule.php

class BylawsModule extends BaseModule
{
    public function render(): string
    {
        // Get all documents
        $stmt = $this->pdo->prepare("
            SELECT * FROM app_bylaw_documents 
            WHERE app_id = ? AND is_active = 1 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$this->app['id']]);
        $documents = $stmt->fetchAll();

        // Check if a specific document is requested
        $docId = (int)($_GET['doc'] ?? 0);
        $activeDoc = null;
        $activeVersion = null;
        $allVersions = [];

        if (empty($documents)) {
            // No docs yet
        } else {
            if ($docId) {
                foreach ($documents as $d) {
                    if ($d['id'] == $docId) {
                        $activeDoc = $d;
                        break;
                    }
                }
            }
            if (!$activeDoc) {
                $activeDoc = $documents[0];
            }

            // Fetch all versions for the active doc
            $stmt = $this->pdo->prepare("
                SELECT v.*, u.name as creator_name, u.avatar as creator_avatar 
                FROM app_bylaw_versions v 
                JOIN users u ON v.created_by = u.id 
                WHERE v.document_id = ? 
                ORDER BY v.created_at DESC
            ");
            $stmt->execute([$activeDoc['id']]);
            $allVersions = $stmt->fetchAll();

            // Active version logic:
            // If admin clicks a specific version to view history
            $verId = (int)($_GET['v'] ?? 0);
            
            if ($verId) {
                foreach ($allVersions as $v) {
                    if ($v['id'] == $verId) {
                        $activeVersion = $v;
                        break;
                    }
                }
            } else {
                // Find published version
                foreach ($allVersions as $v) {
                    if ($v['is_published'] == 1) {
                        $activeVersion = $v;
                        break;
                    }
                }
                // Fallback to latest
                if (!$activeVersion && !empty($allVersions)) {
                    $activeVersion = $allVersions[0];
                }
            }
        }

        $canEdit = $this->isDirectorOrAdmin() && !empty($this->department['is_management']);

        ob_start();
        extract([
            'app' => $this->app,
            'department' => $this->department,
            'user' => $this->user,
            'isDirectorOrAdmin' => $this->isDirectorOrAdmin(),
            'canEdit' => $canEdit,
            'documents' => $documents,
            'activeDoc' => $activeDoc,
            'activeVersion' => $activeVersion,
            'allVersions' => $allVersions
        ]);
        require __DIR__ . '/../views/modules/bylaws.php';
        return ob_get_clean();
    }

    public function handlePost(array $data): void
    {
        $action = $data['action'] ?? '';

        if (!$this->isDirectorOrAdmin() || empty($this->department['is_management'])) {
            $this->redirectBack();
        }

        if ($action === 'create_document') {
            $title = trim($data['title'] ?? '');
            $description = trim($data['description'] ?? '');

            if ($title) {
                $stmt = $this->pdo->prepare("INSERT INTO app_bylaw_documents (app_id, department_id, title, description) VALUES (?, ?, ?, ?)");
                $stmt->execute([$this->app['id'], $this->department['id'], $title, $description]);
                $newDocId = $this->pdo->lastInsertId();

                // Create initial v1.0 version automatically
                $v1Content = trim($data['initial_content'] ?? 'İçerik henüz girilmedi.');
                $stmt = $this->pdo->prepare("INSERT INTO app_bylaw_versions (document_id, version_number, content, changes_summary, is_published, created_by) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$newDocId, 'v1.0', $v1Content, 'İlk versiyon', 1, $this->user['id']]);

                $this->redirectBack($newDocId);
            }
        }

        if ($action === 'add_version') {
            $docId = (int)($data['document_id'] ?? 0);
            $versionNumber = trim($data['version_number'] ?? '');
            $content = trim($data['content'] ?? '');
            $changes = trim($data['changes_summary'] ?? '');
            
            if ($docId && $versionNumber && $content) {
                // Ensure doc belongs to app
                $stmt = $this->pdo->prepare("SELECT id FROM app_bylaw_documents WHERE id = ? AND app_id = ?");
                $stmt->execute([$docId, $this->app['id']]);
                if ($stmt->fetch()) {
                    // Set all previous to unpublished
                    $stmt = $this->pdo->prepare("UPDATE app_bylaw_versions SET is_published = 0 WHERE document_id = ?");
                    $stmt->execute([$docId]);

                    $stmt = $this->pdo->prepare("INSERT INTO app_bylaw_versions (document_id, version_number, content, changes_summary, is_published, created_by) VALUES (?, ?, ?, ?, 1, ?)");
                    $stmt->execute([$docId, $versionNumber, $content, $changes, $this->user['id']]);
                }
                $this->redirectBack($docId);
            }
        }

        if ($action === 'publish_version') {
            $docId = (int)($data['document_id'] ?? 0);
            $verId = (int)($data['version_id'] ?? 0);
            
            if ($docId && $verId) {
                $stmt = $this->pdo->prepare("SELECT id FROM app_bylaw_documents WHERE id = ? AND app_id = ?");
                $stmt->execute([$docId, $this->app['id']]);
                if ($stmt->fetch()) {
                    // Unpublish all
                    $stmt = $this->pdo->prepare("UPDATE app_bylaw_versions SET is_published = 0 WHERE document_id = ?");
                    $stmt->execute([$docId]);
                    // Publish targeted
                    $stmt = $this->pdo->prepare("UPDATE app_bylaw_versions SET is_published = 1 WHERE id = ? AND document_id = ?");
                    $stmt->execute([$verId, $docId]);
                }
                $this->redirectBack($docId);
            }
        }

        if ($action === 'delete_document') {
            $docId = (int)($data['document_id'] ?? 0);
            if ($docId) {
                $stmt = $this->pdo->prepare("DELETE FROM app_bylaw_documents WHERE id = ? AND app_id = ?");
                $stmt->execute([$docId, $this->app['id']]);
            }
            $this->redirectBack();
        }

        $this->redirectBack();
    }

    private function redirectBack(int $docId = 0)
    {
        $url = BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}";
        if ($docId > 0) {
            $url .= "?doc=" . $docId;
        }
        header("Location: " . $url);
        exit;
    }
}
