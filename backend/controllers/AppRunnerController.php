<?php

require_once __DIR__ . '/../models/Department.php';
require_once __DIR__ . '/../models/App.php';
require_once __DIR__ . '/../models/UserDepartment.php';
require_once __DIR__ . '/../modules/BaseModule.php';

class AppRunnerController extends Controller
{
    private Department $deptModel;
    private App $appModel;

    public function __construct()
    {
        $this->deptModel = new Department();
        $this->appModel = new App();
    }

    // GET /panel/{slug}/apps/{app}
    public function run(string $slug, string $appSlug): void
    {
        Auth::requireLogin();
        Auth::requireDepartment($slug);

        $currentDept = $this->deptModel->getBySlug($slug);
        if (!$currentDept || !$currentDept['is_active']) {
            $this->flashError('Departman bulunamadı veya aktif değil.');
            $this->redirect('/panel');
        }

        $app = $this->appModel->getBySlug($currentDept['id'], $appSlug);
        if (!$app || !$app['is_active']) {
            $this->flashError('Uygulama bulunamadı veya aktif değil.');
            $this->redirect("/panel/{$slug}");
        }

        $userRole = $this->getUserRoleInDept($slug);

        // Director kısıtı
        if ($app['director_only'] && $userRole !== 'director' && !Auth::isSuperAdmin()) {
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit;
        }

        $module = $this->instantiateModule($app, $currentDept, Auth::user(), $userRole);
        $moduleContent = $module->render();

        $deptApps = $this->appModel->getByDepartment($currentDept['id']);
        $pageTitle = $app['name'];
        $activeApp = $app['slug'];

        $this->view('panel/app_wrapper', compact('currentDept', 'userRole', 'deptApps', 'moduleContent', 'pageTitle', 'activeApp'), 'panel');
    }

    // POST /panel/{slug}/apps/{app}
    public function handle(string $slug, string $appSlug): void
    {
        Auth::requireLogin();
        Auth::requireDepartment($slug);
        $this->csrfCheck(); // Tüm modül POST isteklerinde CSRF koruması

        $currentDept = $this->deptModel->getBySlug($slug);
        if (!$currentDept || !$currentDept['is_active']) {
            $this->flashError('Departman bulunamadı.');
            $this->redirect('/panel');
        }

        $app = $this->appModel->getBySlug($currentDept['id'], $appSlug);
        if (!$app || !$app['is_active']) {
            $this->flashError('Uygulama bulunamadı.');
            $this->redirect("/panel/{$slug}");
        }

        $userRole = $this->getUserRoleInDept($slug);

        if ($app['director_only'] && $userRole !== 'director' && !Auth::isSuperAdmin()) {
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit;
        }

        $module = $this->instantiateModule($app, $currentDept, Auth::user(), $userRole);
        Logger::log('APP_INTERACTION', 'App', $app['id'], ['department_id' => $currentDept['id'], 'app_slug' => $appSlug]);
        $module->handlePost($_POST);
    }

    // GET /f/{slug}/{app}
    public function runPublic(string $slug, string $appSlug): void
    {
        $currentDept = $this->deptModel->getBySlug($slug);
        if (!$currentDept || !$currentDept['is_active']) {
            $this->flashError('Departman bulunamadı veya aktif değil.');
            $this->redirect('/login');
        }

        $app = $this->appModel->getBySlug($currentDept['id'], $appSlug);
        if (!$app || !$app['is_active']) {
            $this->flashError('Uygulama bulunamadı veya aktif değil.');
            $this->redirect('/login');
        }

        // Modülü instansiyate et, kullanıcı bilgilerini null/empty olarak geç.
        $module = $this->instantiateModule($app, $currentDept, [], 'guest');
        
        // Form modülü, is_public kontrolünü kendi içinde yapabilir.
        if (method_exists($module, 'renderPublic')) {
            $moduleContent = $module->renderPublic();
        } else {
            $moduleContent = "Bu modül kamuya açık erişime izin vermiyor.";
        }

        $pageTitle = $app['name'];
        
        // Ayrı bir layout kullanabiliriz veya public bir wrapper oluşturabiliriz.
        // Şimdilik auth klasöründeki gibi minimal bir view kullanabiliriz
        $this->view('panel/app_public_wrapper', compact('currentDept', 'app', 'moduleContent', 'pageTitle'), 'auth');
    }

    // POST /f/{slug}/{app}
    public function handlePublic(string $slug, string $appSlug): void
    {
        $currentDept = $this->deptModel->getBySlug($slug);
        if (!$currentDept || !$currentDept['is_active']) {
            $this->flashError('Departman bulunamadı.');
            $this->redirect('/login');
        }

        $app = $this->appModel->getBySlug($currentDept['id'], $appSlug);
        if (!$app || !$app['is_active']) {
            $this->flashError('Uygulama bulunamadı.');
            $this->redirect('/login');
        }

        $module = $this->instantiateModule($app, $currentDept, [], 'guest');
        
        if (method_exists($module, 'handlePostPublic')) {
            Logger::log('PUBLIC_APP_INTERACTION', 'App', $app['id'], ['department_id' => $currentDept['id'], 'app_slug' => $appSlug]);
            $module->handlePostPublic($_POST);
        } else {
            die("Bu modül kamuya açık post isteğini desteklemiyor.");
        }
    }
    // GET /f/{app}
    public function runPublicGlobal(string $appSlug): void
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM apps WHERE slug = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$appSlug]);
        $app = $stmt->fetch();

        if (!$app) {
            $this->flashError('Uygulama bulunamadı veya aktif değil.');
            $this->redirect('/login');
        }

        $currentDept = $this->deptModel->getById((int)$app['department_id']);
        if (!$currentDept) {
            $this->flashError('Bağlı departman bulunamadı.');
            $this->redirect('/login');
        }

        $module = $this->instantiateModule($app, $currentDept, [], 'guest');
        
        if (method_exists($module, 'renderPublic')) {
            $moduleContent = $module->renderPublic();
        } else {
            $moduleContent = "Bu modül kamuya açık erişime izin vermiyor.";
        }

        $pageTitle = $app['name'];
        $this->view('panel/app_public_wrapper', compact('currentDept', 'app', 'moduleContent', 'pageTitle'), 'auth');
    }

    // POST /f/{app}
    public function handlePublicGlobal(string $appSlug): void
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM apps WHERE slug = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$appSlug]);
        $app = $stmt->fetch();

        if (!$app) {
            $this->flashError('Uygulama bulunamadı veya aktif değil.');
            $this->redirect('/login');
        }

        $currentDept = $this->deptModel->getById((int)$app['department_id']);
        if (!$currentDept) {
            $this->flashError('Bağlı departman bulunamadı.');
            $this->redirect('/login');
        }

        $module = $this->instantiateModule($app, $currentDept, [], 'guest');
        
        if (method_exists($module, 'handlePostPublic')) {
            Logger::log('PUBLIC_GLOBAL_APP_INTERACTION', 'App', $app['id'], ['department_id' => $currentDept['id'], 'app_slug' => $appSlug]);
            $module->handlePostPublic($_POST);
        } else {
            die("Bu modül kamuya açık post isteğini desteklemiyor.");
        }
    }

    private function getUserRoleInDept(string $slug): string
    {
        if (Auth::isSuperAdmin()) return 'director';
        foreach (Auth::getDepartments() as $d) {
            if ($d['slug'] === $slug) return $d['role'];
        }
        return 'member';
    }

    private function instantiateModule(array $app, array $dept, array $user, string $userRole): BaseModule
    {
        $type = $app['module_type'];

        // Güvenlik: Sadece izin verilen modül tiplerini yükle (whitelist)
        if (!in_array($type, ALLOWED_MODULE_TYPES, true)) {
            http_response_code(403);
            die("Geçersiz modül tipi: " . htmlspecialchars($type));
        }

        $className = ucfirst($type) . 'Module';
        $file = __DIR__ . "/../modules/{$className}.php";

        if (!file_exists($file)) {
            die("Modül sınıfı bulunamadı: " . htmlspecialchars($className));
        }

        require_once $file;
        return new $className($app, $dept, $user, $userRole);
    }
}
