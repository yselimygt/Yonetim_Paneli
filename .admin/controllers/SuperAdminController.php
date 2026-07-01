<?php

require_once __DIR__ . '/../models/Department.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/App.php';

class SuperAdminController extends Controller
{
    public function dashboard(): void
    {
        Auth::requireSuperAdmin();

        $pdo = Database::getInstance();

        $deptCount   = (int) $pdo->query("SELECT COUNT(*) FROM departments")->fetchColumn();
        $userCount   = (int) $pdo->query("SELECT COUNT(*) FROM users WHERE is_super_admin = 0")->fetchColumn();
        $appCount    = (int) $pdo->query("SELECT COUNT(*) FROM apps")->fetchColumn();
        $assignCount = (int) $pdo->query("SELECT COUNT(*) FROM user_departments")->fetchColumn();

        // Departmanlar + üye sayısı
        $depts = $pdo->query("
            SELECT d.*, COUNT(ud.user_id) AS member_count
            FROM departments d
            LEFT JOIN user_departments ud ON ud.department_id = d.id
            GROUP BY d.id
            ORDER BY d.is_management DESC, d.name ASC
        ")->fetchAll();

        // Son 5 kullanıcı
        $recentUsers = $pdo->query(
            "SELECT * FROM users WHERE is_super_admin = 0 ORDER BY created_at DESC LIMIT 5"
        )->fetchAll();

        $this->view('superadmin/dashboard', [
            'deptCount'   => $deptCount,
            'userCount'   => $userCount,
            'appCount'    => $appCount,
            'assignCount' => $assignCount,
            'departments' => $depts,
            'recentUsers' => $recentUsers,
        ], 'superadmin');
    }
}
