<?php

require_once __DIR__ . '/Controller.php';

class ApplicationController extends Controller
{
    public function __construct()
    {
        Auth::requireLogin();
        Auth::requireSuperAdmin();
    }

    public function index(): void
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM app_applications ORDER BY created_at DESC");
        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('superadmin/applications/index', compact('applications'), 'superadmin');
    }

    public function updateStatus(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/applications');
        }

        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if ($id > 0 && in_array($status, ['pending', 'reviewing', 'approved', 'rejected'])) {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("UPDATE app_applications SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Başvuru durumu güncellendi.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Geçersiz işlem.'];
        }

        $this->redirect('/superadmin/applications');
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/applications');
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("DELETE FROM app_applications WHERE id = ?");
            $stmt->execute([$id]);
            
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Başvuru silindi.'];
        }

        $this->redirect('/superadmin/applications');
    }
}
