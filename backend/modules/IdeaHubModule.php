<?php

class IdeaHubModule extends BaseModule
{
    public function render(): string
    {
        // 1. Fetch all ideas with vote counts and user info
        $stmt = $this->pdo->prepare("
            SELECT i.*, 
                   u.name AS creator_name, u.avatar AS creator_avatar,
                   (SELECT COUNT(*) FROM app_idea_votes v WHERE v.idea_id = i.id) as vote_count,
                   (SELECT COUNT(*) FROM app_idea_votes v WHERE v.idea_id = i.id AND v.user_id = ?) as user_voted
            FROM app_ideas i
            JOIN users u ON u.id = i.user_id
            WHERE i.app_id = ? AND i.department_id = ?
            ORDER BY vote_count DESC, i.created_at DESC
        ");
        $stmt->execute([$this->user['id'], $this->app['id'], $this->department['id']]);
        $ideas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. Fetch comments for these ideas
        $commentsByIdea = [];
        if (!empty($ideas)) {
            $ideaIds = array_column($ideas, 'id');
            $inQuery = implode(',', array_fill(0, count($ideaIds), '?'));
            
            $stmtComments = $this->pdo->prepare("
                SELECT c.*, u.name as user_name, u.avatar as user_avatar
                FROM app_idea_comments c
                JOIN users u ON u.id = c.user_id
                WHERE c.idea_id IN ($inQuery)
                ORDER BY c.created_at ASC
            ");
            $stmtComments->execute($ideaIds);
            $allComments = $stmtComments->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($allComments as $c) {
                $commentsByIdea[$c['idea_id']][] = $c;
            }
        }

        // 3. Attach comments to ideas
        foreach ($ideas as &$idea) {
            $idea['comments'] = $commentsByIdea[$idea['id']] ?? [];
        }

        return $this->renderView('ideahub', [
            'ideas' => $ideas,
            'categories' => ['Yazılım', 'Donanım', 'İş Süreçleri', 'Sosyal Etkinlik', 'Diğer'],
            'statuses' => [
                'pending' => ['label' => 'Bekliyor', 'color' => '#6b7280'],
                'reviewed' => ['label' => 'İncelendi', 'color' => '#3b82f6'],
                'accepted' => ['label' => 'Kabul Edildi', 'color' => '#10b981'],
                'rejected' => ['label' => 'Reddedildi', 'color' => '#ef4444'],
            ]
        ]);
    }

    public function handlePost(array $postData): void
    {
        $action = $postData['action'] ?? '';
        $redirectUrl = BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}";

        if ($action === 'create') {
            $title = trim($postData['title'] ?? '');
            $description = trim($postData['description'] ?? '');
            $category = trim($postData['category'] ?? 'Diğer');

            if (empty($title) || empty($description)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Başlık ve açıklama zorunludur.'];
            } else {
                $stmt = $this->pdo->prepare("
                    INSERT INTO app_ideas (app_id, department_id, user_id, title, description, category, status) 
                    VALUES (?, ?, ?, ?, ?, ?, 'pending')
                ");
                $stmt->execute([
                    $this->app['id'],
                    $this->department['id'],
                    $this->user['id'],
                    $title,
                    $description,
                    $category
                ]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Fikriniz başarıyla eklendi.'];
            }
            header("Location: " . $redirectUrl);
            exit;
        }

        if ($action === 'upvote') {
            $ideaId = (int) ($postData['idea_id'] ?? 0);
            
            // Check if voted
            $stmtCheck = $this->pdo->prepare("SELECT id FROM app_idea_votes WHERE idea_id = ? AND user_id = ?");
            $stmtCheck->execute([$ideaId, $this->user['id']]);
            $existingVote = $stmtCheck->fetch();

            if ($existingVote) {
                // Remove vote (toggle off)
                $stmtDel = $this->pdo->prepare("DELETE FROM app_idea_votes WHERE id = ?");
                $stmtDel->execute([$existingVote['id']]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Oyunuz geri alındı.'];
            } else {
                // Add vote
                $stmtAdd = $this->pdo->prepare("INSERT INTO app_idea_votes (idea_id, user_id) VALUES (?, ?)");
                $stmtAdd->execute([$ideaId, $this->user['id']]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Fikre oy verdiniz.'];
            }
            header("Location: " . $redirectUrl);
            exit;
        }

        if ($action === 'add_comment') {
            $ideaId = (int) ($postData['idea_id'] ?? 0);
            $comment = trim($postData['comment'] ?? '');

            if (!empty($comment)) {
                $stmt = $this->pdo->prepare("INSERT INTO app_idea_comments (idea_id, user_id, comment) VALUES (?, ?, ?)");
                $stmt->execute([$ideaId, $this->user['id'], $comment]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Yorumunuz eklendi.'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Yorum boş olamaz.'];
            }
            header("Location: " . $redirectUrl);
            exit;
        }

        if ($action === 'update_status') {
            if (!$this->isDirectorOrAdmin()) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Yetkiniz yok.'];
                header("Location: " . $redirectUrl);
                exit;
            }

            $ideaId = (int) ($postData['idea_id'] ?? 0);
            $status = trim($postData['status'] ?? 'pending');

            $stmt = $this->pdo->prepare("UPDATE app_ideas SET status = ? WHERE id = ? AND app_id = ?");
            $stmt->execute([$status, $ideaId, $this->app['id']]);
            
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Fikir durumu güncellendi.'];
            header("Location: " . $redirectUrl);
            exit;
        }

        if ($action === 'delete') {
            $ideaId = (int) ($postData['idea_id'] ?? 0);
            
            // Allow creator or admin to delete
            $stmtCheck = $this->pdo->prepare("SELECT user_id FROM app_ideas WHERE id = ?");
            $stmtCheck->execute([$ideaId]);
            $creatorId = (int) $stmtCheck->fetchColumn();

            if ($this->isDirectorOrAdmin() || $creatorId === $this->user['id']) {
                $stmt = $this->pdo->prepare("DELETE FROM app_ideas WHERE id = ?");
                $stmt->execute([$ideaId]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Fikir silindi.'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Bu fikri silme yetkiniz yok.'];
            }
            header("Location: " . $redirectUrl);
            exit;
        }
    }
}
