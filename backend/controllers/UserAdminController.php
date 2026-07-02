<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Department.php';
require_once __DIR__ . '/../models/UserDepartment.php';

class UserAdminController extends Controller
{
    private User           $userModel;
    private Department     $deptModel;
    private UserDepartment $pivotModel;

    public function __construct()
    {
        $this->userModel  = new User();
        $this->deptModel  = new Department();
        $this->pivotModel = new UserDepartment();
    }

    // GET /superadmin/users
    public function index(): void
    {
        Auth::requireSuperAdmin();

        $pdo   = Database::getInstance();
        $users = $pdo->query("
            SELECT u.*,
                   GROUP_CONCAT(d.name ORDER BY d.name SEPARATOR ', ') AS depts,
                   GROUP_CONCAT(CONCAT(d.name, '::', d.color) ORDER BY d.name SEPARATOR '||') AS departments_meta
            FROM users u
            LEFT JOIN user_departments ud ON ud.user_id = u.id
            LEFT JOIN departments d ON d.id = ud.department_id
            GROUP BY u.id
            ORDER BY u.created_at DESC
        ")->fetchAll();

        $departments = $this->deptModel->getAll();

        $this->view('superadmin/users/index', compact('users', 'departments'), 'superadmin');
    }

    // POST /superadmin/users  (create)
    public function store(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $name     = trim($_POST['name']     ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password'] ?? ''; // Güvenlik: şifrelerde trim() kullanma

        if (empty($name) || empty($email) || empty($password)) {
            $this->flashError('Ad, e-posta ve şifre zorunludur.');
            $this->redirect('/superadmin/users');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flashError('Geçerli bir e-posta girin.');
            $this->redirect('/superadmin/users');
        }

        if ($this->userModel->findByEmail($email)) {
            $this->flashError('Bu e-posta zaten kayıtlı.');
            $this->redirect('/superadmin/users');
        }

        if (strlen($password) < MIN_PASSWORD_LENGTH) {
            $this->flashError('Şifre en az ' . MIN_PASSWORD_LENGTH . ' karakter olmalıdır.');
            $this->redirect('/superadmin/users');
        }

        $is_super_admin = isset($_POST['is_super_admin']) ? 1 : 0;
        $can_manage_passwords = isset($_POST['can_manage_passwords']) ? 1 : 0;
        $is_representative = isset($_POST['is_representative']) ? 1 : 0;
        $is_alumni = isset($_POST['is_alumni']) ? 1 : 0;
        $birth_date = !empty($_POST['birth_date']) ? $_POST['birth_date'] : null;
        
        $userId = $this->userModel->create($name, $email, $password, $is_super_admin, $can_manage_passwords, $is_representative, $is_alumni);
        if ($birth_date) {
            $this->userModel->updateUser($userId, ['birth_date' => $birth_date]);
        }
        Logger::log('CREATE_USER', 'User', $userId, ['name' => $name, 'email' => $email]);

        // Opsiyonel departman ataması
        if (!empty($_POST['department_id']) && !empty($_POST['role'])) {
            $this->pivotModel->assign(
                $userId,
                (int) $_POST['department_id'],
                $_POST['role']
            );
            $this->syncAlumniProfile($userId);
        }

        $this->flashSuccess("'{$name}' kullanıcısı oluşturuldu.");
        $this->redirect('/superadmin/users');
    }

    // GET /superadmin/users/{id}/edit
    public function edit(string $id): void
    {
        Auth::requireSuperAdmin();

        $user = $this->userModel->getById((int)$id);
        if (!$user) {
            $this->flashError('Kullanıcı bulunamadı.');
            $this->redirect('/superadmin/users');
        }

        $departments  = $this->deptModel->getAll();
        $userDepts    = $this->pivotModel->getByUser((int)$id);
        $assignedIds  = array_column($userDepts, 'department_id');

        $this->view('superadmin/users/edit', compact('user', 'departments', 'userDepts', 'assignedIds'), 'superadmin');
    }

    // POST /superadmin/users/{id}  (update)
    public function update(string $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $user = $this->userModel->getById((int)$id);
        if (!$user) {
            $this->flashError('Kullanıcı bulunamadı.');
            $this->redirect('/superadmin/users');
        }

        $name      = trim($_POST['name']      ?? '');
        $birth_date = !empty($_POST['birth_date']) ? $_POST['birth_date'] : null;
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $is_super_admin = isset($_POST['is_super_admin']) ? 1 : 0;
        $can_manage_passwords = isset($_POST['can_manage_passwords']) ? 1 : 0;
        $is_representative = isset($_POST['is_representative']) ? 1 : 0;
        $is_alumni = isset($_POST['is_alumni']) ? 1 : 0;

        $updates = [
            'name' => $name, 
            'birth_date' => $birth_date, 
            'is_active' => $is_active, 
            'is_super_admin' => $is_super_admin, 
            'can_manage_passwords' => $can_manage_passwords,
            'is_representative' => $is_representative,
            'is_alumni' => $is_alumni
        ];

        // Şifre değişimi opsiyonel
        if (!empty($_POST['password'])) {
            if (strlen($_POST['password']) < MIN_PASSWORD_LENGTH) {
                $this->flashError('Şifre en az ' . MIN_PASSWORD_LENGTH . ' karakter olmalıdır.');
                $this->redirect('/superadmin/users/' . $id . '/edit');
            }
            $updates['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }

        $this->userModel->updateUser((int)$id, $updates);

        // Güvenlik: Hassas verileri logdan filtrele (password hash, doğum tarihi)
        $logData = array_diff_key($updates, array_flip(['password', 'birth_date']));
        Logger::log('UPDATE_USER', 'User', (int)$id, $logData);

        if ((int)$id === ($_SESSION['user_id'] ?? 0)) {
            $_SESSION['user_name'] = $name;
            $_SESSION['is_super_admin'] = (bool)$is_super_admin;
        }

        $this->flashSuccess("'{$name}' güncellendi.");
        $this->redirect('/superadmin/users');
    }

    // POST /superadmin/users/{id}/delete
    public function destroy(string $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $user = $this->userModel->getById((int)$id);
        if (!$user) {
            $this->flashError('Kullanıcı bulunamadı.');
            $this->redirect('/superadmin/users');
        }

        $this->pivotModel->removeAllByUser((int)$id);
        $this->userModel->deleteUser((int)$id);
        Logger::log('DELETE_USER', 'User', (int)$id, ['name' => $user['name'], 'email' => $user['email']]);

        $this->flashSuccess("'{$user['name']}' silindi.");
        $this->redirect('/superadmin/users');
    }

    // POST /superadmin/users/{id}/assign  — departman atama/çıkarma
    public function assign(string $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $user = $this->userModel->getById((int)$id);
        if (!$user) {
            $this->flashError('Kullanıcı bulunamadı.');
            $this->redirect('/superadmin/users');
        }

        $deptId = (int) ($_POST['department_id'] ?? 0);
        $role   = $_POST['role']   ?? 'member';
        $action = $_POST['action'] ?? 'assign';

        if (!$deptId) {
            $this->flashError('Departman seçin.');
            $this->redirect('/superadmin/users/' . $id . '/edit');
        }

        if ($action === 'remove') {
            $this->pivotModel->remove((int)$id, $deptId);
            Logger::log('REMOVE_DEPARTMENT_FROM_USER', 'User', (int)$id, ['department_id' => $deptId]);
            $this->flashSuccess('Departman ataması kaldırıldı.');
        } else {
            $this->pivotModel->assign((int)$id, $deptId, $role);
            Logger::log('ASSIGN_DEPARTMENT_TO_USER', 'User', (int)$id, ['department_id' => $deptId, 'role' => $role]);
            $this->flashSuccess('Departman ataması yapıldı.');
            $this->syncAlumniProfile((int)$id);
        }

        $this->redirect('/superadmin/users/' . $id . '/edit');
    }

    private function syncAlumniProfile(int $userId): void
    {
        $pdo = Database::getInstance();
        
        $stmt = $pdo->prepare("
            SELECT d.slug FROM user_departments ud
            JOIN departments d ON d.id = ud.department_id
            WHERE ud.user_id = ? AND d.slug = 'mezunlar'
        ");
        $stmt->execute([$userId]);
        if (!$stmt->fetch()) {
            return;
        }
        
        $stmt = $pdo->prepare("SELECT id, department_id FROM apps WHERE slug = 'alumni' LIMIT 1");
        $stmt->execute();
        $app = $stmt->fetch();
        if (!$app) {
            return;
        }
        
        $stmt = $pdo->prepare("SELECT id FROM alumni_profiles WHERE user_id = ? AND app_id = ?");
        $stmt->execute([$userId, $app['id']]);
        if ($stmt->fetch()) {
            return;
        }
        
        $user = $this->userModel->getById($userId);
        if (!$user) return;
        
        $stmt = $pdo->prepare("
            INSERT INTO alumni_profiles (app_id, department_id, user_id, full_name, email, phone, university, major, avatar_path)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $app['id'],
            $app['department_id'],
            $userId,
            $user['name'],
            $user['email'],
            $user['phone'],
            $user['university'],
            $user['school_department'],
            $user['avatar']
        ]);
    }
}
