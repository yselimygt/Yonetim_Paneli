<?php
// c:\xampp\htdocs\innomis-admin\modules\WikiModule.php

class WikiModule extends BaseModule
{
    public function render(): string
    {
        $canManage = $this->isDirectorOrAdmin() || !empty($this->department['is_management']);
        
        // Handling single article view vs list view
        $articleId = (int)($_GET['article'] ?? 0);
        $activeArticle = null;
        
        if ($articleId) {
            $stmt = $this->pdo->prepare("
                SELECT w.*, u.name as author_name, u.avatar as author_avatar 
                FROM app_wiki_articles w 
                LEFT JOIN users u ON w.author_id = u.id 
                WHERE w.id = ? AND w.app_id = ?
            ");
            $stmt->execute([$articleId, $this->app['id']]);
            $activeArticle = $stmt->fetch();
            
            if ($activeArticle) {
                // Increment views
                $this->pdo->prepare("UPDATE app_wiki_articles SET views = views + 1 WHERE id = ?")->execute([$articleId]);
                $activeArticle['views']++;
            }
        }

        // Get categories for sidebar
        $stmt = $this->pdo->prepare("SELECT DISTINCT category FROM app_wiki_articles WHERE app_id = ? AND department_id = ?");
        $stmt->execute([$this->app['id'], $this->department['id']]);
        $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Get list of articles
        $catFilter = $_GET['cat'] ?? '';
        $qFilter = trim($_GET['q'] ?? '');
        
        $sql = "SELECT w.id, w.title, w.category, w.views, w.created_at, u.name as author_name 
                FROM app_wiki_articles w 
                LEFT JOIN users u ON w.author_id = u.id 
                WHERE w.app_id = ? AND w.department_id = ?";
        $params = [$this->app['id'], $this->department['id']];
        
        if ($catFilter) {
            $sql .= " AND w.category = ?";
            $params[] = $catFilter;
        }
        if ($qFilter) {
            $sql .= " AND (w.title LIKE ? OR w.content LIKE ?)";
            $params[] = "%$qFilter%";
            $params[] = "%$qFilter%";
        }
        
        $sql .= " ORDER BY w.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $articles = $stmt->fetchAll();

        ob_start();
        extract([
            'app' => $this->app,
            'department' => $this->department,
            'user' => $this->user,
            'canManage' => $canManage,
            'articles' => $articles,
            'activeArticle' => $activeArticle,
            'categories' => $categories,
            'catFilter' => $catFilter,
            'qFilter' => $qFilter
        ]);
        require __DIR__ . '/../views/modules/wiki.php';
        return ob_get_clean();
    }

    public function handlePost(array $data): void
    {
        $action = $data['action'] ?? '';
        $canManage = $this->isDirectorOrAdmin() || !empty($this->department['is_management']);

        if ($action === 'create_article') {
            $title = trim($data['title'] ?? '');
            $category = trim($data['category'] ?? 'Genel');
            $content = trim($data['content'] ?? '');
            
            if ($title && $content) {
                $stmt = $this->pdo->prepare("INSERT INTO app_wiki_articles (app_id, department_id, author_id, title, content, category) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$this->app['id'], $this->department['id'], $this->user['id'], $title, $content, $category]);
                
                $newId = $this->pdo->lastInsertId();
                $this->redirectBack("?article=" . $newId);
            }
        }

        if ($action === 'edit_article') {
            $articleId = (int)($data['article_id'] ?? 0);
            $title = trim($data['title'] ?? '');
            $category = trim($data['category'] ?? 'Genel');
            $content = trim($data['content'] ?? '');
            
            if ($articleId && $title && $content) {
                // Verify ownership or management
                $stmt = $this->pdo->prepare("SELECT author_id FROM app_wiki_articles WHERE id = ? AND app_id = ?");
                $stmt->execute([$articleId, $this->app['id']]);
                $article = $stmt->fetch();
                
                if ($article && ($canManage || $article['author_id'] == $this->user['id'])) {
                    $upd = $this->pdo->prepare("UPDATE app_wiki_articles SET title = ?, category = ?, content = ? WHERE id = ?");
                    $upd->execute([$title, $category, $content, $articleId]);
                }
                $this->redirectBack("?article=" . $articleId);
            }
        }

        if ($action === 'delete_article') {
            $articleId = (int)($data['article_id'] ?? 0);
            if ($articleId) {
                $stmt = $this->pdo->prepare("SELECT author_id FROM app_wiki_articles WHERE id = ? AND app_id = ?");
                $stmt->execute([$articleId, $this->app['id']]);
                $article = $stmt->fetch();
                
                if ($article && ($canManage || $article['author_id'] == $this->user['id'])) {
                    $this->pdo->prepare("DELETE FROM app_wiki_articles WHERE id = ?")->execute([$articleId]);
                }
            }
            $this->redirectBack();
        }

        $this->redirectBack();
    }

    private function redirectBack($query = "")
    {
        $url = BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}" . $query;
        header("Location: " . $url);
        exit;
    }
}
