<?php

class LogController extends Controller
{
    public function index()
    {
        Auth::requireSuperAdmin();
        
        $pdo = Database::getInstance();
        
        // Simple pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        // Fetch logs with user info
        $stmt = $pdo->prepare("
            SELECT l.*, u.name as user_name, u.email as user_email
            FROM system_logs l
            LEFT JOIN users u ON l.user_id = u.id
            ORDER BY l.created_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $logs = $stmt->fetchAll();
        
        // Fetch total count for pagination
        $countStmt = $pdo->query("SELECT COUNT(*) FROM system_logs");
        $totalLogs = $countStmt->fetchColumn();
        $totalPages = ceil($totalLogs / $limit);
        
        $deptsMap = $pdo->query("SELECT id, name FROM departments")->fetchAll(PDO::FETCH_KEY_PAIR);
        $usersMap = $pdo->query("SELECT id, name FROM users")->fetchAll(PDO::FETCH_KEY_PAIR);

        $this->view('superadmin/logs/index', [
            'logs' => $logs,
            'page' => $page,
            'totalPages' => $totalPages,
            'deptsMap' => $deptsMap,
            'usersMap' => $usersMap,
            'title' => 'Sistem Logları'
        ], 'superadmin');
    }
}
