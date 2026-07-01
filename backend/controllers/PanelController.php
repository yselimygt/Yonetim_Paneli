<?php

require_once __DIR__ . '/../models/Department.php';
require_once __DIR__ . '/../models/App.php';
require_once __DIR__ . '/../models/UserDepartment.php';

class PanelController extends Controller
{
    private Department     $deptModel;
    private App            $appModel;
    private UserDepartment $pivotModel;

    public function __construct()
    {
        $this->deptModel  = new Department();
        $this->appModel   = new App();
        $this->pivotModel = new UserDepartment();
    }

    // -------------------------------------------------------
    // GET /panel  — Departman Seçim Ekranı
    // -------------------------------------------------------
    public function select(): void
    {
        Auth::requireLogin();

        $depts = Auth::getDepartments();

        // Tek departmansa ve süper admin değilse direkt yönlendir
        if (count($depts) === 1 && !Auth::isSuperAdmin()) {
            $this->redirect('/panel/' . $depts[0]['slug']);
        }

        // Super admin hiç departmanda değilse dashboard'a gönder
        if (Auth::isSuperAdmin() && empty($depts)) {
            $this->redirect('/superadmin');
        }

        // View için departman verilerini genişlet
        $departments = [];
        foreach ($depts as $d) {
            $full = $this->deptModel->getBySlug($d['slug']);
            if ($full) {
                $departments[] = array_merge($full, ['role' => $d['role']]);
            }
        }

        // select.php kendi tam HTML'ini içeriyor, layout kullanma
        extract(['departments' => $departments]);
        ob_start();
        require __DIR__ . '/../views/panel/select.php';
        echo ob_get_clean();
    }

    // -------------------------------------------------------
    // GET /panel/{slug}  — Departman Dashboard
    // -------------------------------------------------------
    public function dashboard(string $slug): void
    {
        Auth::requireLogin();
        Auth::requireDepartment($slug);

        $currentDept = $this->deptModel->getBySlug($slug);
        if (!$currentDept || !$currentDept['is_active']) {
            $this->flashError('Bu departman bulunamadı veya aktif değil.');
            $this->redirect('/panel');
        }

        // Kullanıcının bu departmandaki rolü
        $userRole = $this->getUserRoleInDept($slug);

        // Uygulamalar
        $deptApps = $this->appModel->getByDepartment($currentDept['id']);

        // Üyeler
        $members = $this->pivotModel->getByDepartment($currentDept['id']);

        // İstatistikler
        $memberCount = count($members);
        $appCount    = count($deptApps);

        $data = compact('currentDept', 'userRole', 'deptApps', 'members', 'memberCount', 'appCount');

        // Yönetim paneli ek istatistikleri
        if ($currentDept['is_management'] || Auth::isSuperAdmin()) {
            $pdo = Database::getInstance();
            $data['totalUsers'] = (int) $pdo->query("SELECT COUNT(*) FROM users WHERE is_super_admin=0")->fetchColumn();
            $data['totalDepts'] = (int) $pdo->query("SELECT COUNT(*) FROM departments WHERE is_active=1")->fetchColumn();
        } else {
            $data['totalUsers'] = 0;
            $data['totalDepts'] = 0;
        }
        // Son Duyuruları Çek
        $pdo = Database::getInstance();
        $stmtAnn = $pdo->prepare("
            SELECT a.*, u.name AS author_name, ap.slug AS app_slug 
            FROM announcements a
            JOIN users u ON u.id = a.author_id
            LEFT JOIN apps ap ON ap.id = a.app_id
            WHERE a.is_global = 1 OR a.department_id = ?
            ORDER BY a.created_at DESC
            LIMIT 2
        ");
        $stmtAnn->execute([$currentDept['id']]);
        $data['latestAnnouncements'] = $stmtAnn->fetchAll();

        // Takvim verilerini hazırla (Doğum günleri + Etkinlikler)
        $calendarData = [
            'birthdays' => [],
            'events' => []
        ];

        // 1. Doğum günleri
        foreach ($members as $m) {
            if (!empty($m['birth_date'])) {
                $bMonth = (int)date('m', strtotime($m['birth_date']));
                $bDay = (int)date('d', strtotime($m['birth_date']));
                $calendarData['birthdays'][] = [
                    'name' => $m['user_name'],
                    'month' => $bMonth,
                    'day' => $bDay
                ];
            }
        }

        // 2. Etkinlikler (app_events)
        $stmtAppEvt = $pdo->prepare("SELECT id, title, event_date, event_time, type, category FROM app_events WHERE is_global = 1 OR department_id = ?");
        $stmtAppEvt->execute([$currentDept['id']]);
        $appEvents = $stmtAppEvt->fetchAll();
        foreach ($appEvents as $e) {
            $calendarData['events'][] = [
                'id' => 'ae_' . $e['id'],
                'title' => $e['title'],
                'date' => $e['event_date'],
                'time' => $e['event_time'],
                'type' => $e['type'],
                'category' => $e['category'],
                'source' => 'app'
            ];
        }

        // 3. Takvim Etkinlikleri (calendar_events)
        $stmtCalEvt = $pdo->prepare("SELECT id, title, description, event_date, event_time FROM calendar_events WHERE department_id = ?");
        $stmtCalEvt->execute([$currentDept['id']]);
        $calEvents = $stmtCalEvt->fetchAll();
        foreach ($calEvents as $e) {
            $calendarData['events'][] = [
                'id' => 'ce_' . $e['id'],
                'title' => $e['title'],
                'date' => $e['event_date'],
                'time' => $e['event_time'],
                'type' => 'calendar',
                'category' => 'Takvim',
                'source' => 'calendar',
                'desc' => $e['description']
            ];
        }

        // 4. Site Etkinlikleri (site_events)
        try {
            $stmtSiteEvt = $pdo->query("SELECT id, title, event_date, category FROM site_events");
            $siteEvents = $stmtSiteEvt->fetchAll();
            foreach ($siteEvents as $e) {
                $dDate = $e['event_date'] ? date('Y-m-d', strtotime($e['event_date'])) : null;
                $dTime = $e['event_date'] ? date('H:i:s', strtotime($e['event_date'])) : null;
                if ($dDate) {
                    $calendarData['events'][] = [
                        'id' => 'se_' . $e['id'],
                        'title' => $e['title'],
                        'date' => $dDate,
                        'time' => $dTime,
                        'type' => 'site',
                        'category' => $e['category'] ?: 'Genel',
                        'source' => 'site'
                    ];
                }
            }
        } catch(PDOException $ex) {
            // site_events table might not exist in some setups, ignore if missing.
        }

        $data['calendarData'] = json_encode($calendarData, JSON_UNESCAPED_UNICODE);

        $this->view('panel/dashboard', $data, 'panel');
    }

    // -------------------------------------------------------
    // GET /panel/{slug}/management/reports
    // -------------------------------------------------------
    public function managementReports(string $slug): void
    {
        Auth::requireLogin();
        $this->requireManagement($slug);

        $currentDept = $this->deptModel->getBySlug($slug);
        $userRole    = $this->getUserRoleInDept($slug);

        $pdo      = Database::getInstance();
        $depts    = $pdo->query("SELECT * FROM departments WHERE is_active = 1 ORDER BY is_management DESC, name ASC")->fetchAll();
        $allDepts = [];

        foreach ($depts as $dept) {
            $memberCount = (int) $pdo->prepare(
                "SELECT COUNT(*) FROM user_departments WHERE department_id = ?"
            )->execute([$dept['id']]) ? 0 : 0;

            $countStmt = $pdo->prepare("SELECT COUNT(*) FROM user_departments WHERE department_id = ?");
            $countStmt->execute([$dept['id']]);
            $memberCount = (int) $countStmt->fetchColumn();

            $appCountStmt = $pdo->prepare("SELECT COUNT(*) FROM apps WHERE department_id = ? AND is_active = 1");
            $appCountStmt->execute([$dept['id']]);
            $appCount = (int) $appCountStmt->fetchColumn();

            $membersStmt = $pdo->prepare("
                SELECT ud.role, u.name AS user_name, u.email
                FROM user_departments ud
                JOIN users u ON u.id = ud.user_id
                WHERE ud.department_id = ?
                ORDER BY ud.role DESC, u.name ASC
            ");
            $membersStmt->execute([$dept['id']]);

            $allDepts[] = array_merge($dept, [
                'member_count' => $memberCount,
                'app_count'    => $appCount,
                'members'      => $membersStmt->fetchAll(),
            ]);
        }

        $deptApps = $this->appModel->getByDepartment($currentDept['id']);

        $this->view('panel/management/reports',
            compact('currentDept', 'userRole', 'allDepts', 'deptApps'),
            'panel'
        );
    }

    // -------------------------------------------------------
    // GET/POST /panel/{slug}/management/announce
    // -------------------------------------------------------
    public function managementAnnounce(string $slug): void
    {
        Auth::requireLogin();
        $this->requireManagement($slug);

        $currentDept = $this->deptModel->getBySlug($slug);
        $userRole    = $this->getUserRoleInDept($slug);
        $pdo         = Database::getInstance();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->csrfCheck();

            $title    = trim($_POST['title']   ?? '');
            $content  = trim($_POST['content'] ?? '');
            $isPinned = isset($_POST['is_pinned']) ? 1 : 0;

            if (empty($title) || empty($content)) {
                $this->flashError('Başlık ve içerik zorunludur.');
                $this->redirect("/panel/{$slug}/management/announce");
            }

            // Yönetim duyurusu app'i bul veya kullan
            $announceApp = $pdo->prepare(
                "SELECT id FROM apps WHERE department_id = ? AND module_type = 'announcement' LIMIT 1"
            );
            $announceApp->execute([$currentDept['id']]);
            $appRow = $announceApp->fetch();
            $appId  = $appRow ? $appRow['id'] : 0;

            // Küresel duyuru olarak kaydet (is_global = 1)
            $stmt = $pdo->prepare("
                INSERT INTO announcements (app_id, department_id, author_id, title, content, is_pinned, is_global)
                VALUES (?, ?, ?, ?, ?, ?, 1)
            ");
            $stmt->execute([
                $appId,
                $currentDept['id'],
                Auth::user()['id'],
                $title, $content, $isPinned
            ]);

            $this->flashSuccess('Küresel duyuru tüm departmanlara gönderildi.');
            Logger::log('CREATE_GLOBAL_ANNOUNCEMENT', 'Announcement', (int)$pdo->lastInsertId(), ['title' => $title]);
            $this->redirect("/panel/{$slug}/management/announce");
        }

        // Küresel duyuruları listele
        $announcements = $pdo->query(
            "SELECT * FROM announcements WHERE is_global = 1 ORDER BY is_pinned DESC, created_at DESC LIMIT 30"
        )->fetchAll();

        $deptApps = $this->appModel->getByDepartment($currentDept['id']);

        $this->view('panel/management/announce',
            compact('currentDept', 'userRole', 'announcements', 'deptApps'),
            'panel'
        );
    }

    // -------------------------------------------------------
    // GET /panel/{slug}/management/directory
    // -------------------------------------------------------
    public function managementDirectory(string $slug): void
    {
        Auth::requireLogin();
        $this->requireManagement($slug);

        $currentDept = $this->deptModel->getBySlug($slug);
        $userRole    = $this->getUserRoleInDept($slug);
        $pdo         = Database::getInstance();

        // Tüm üyeler + departman bilgisi
        $directory = $pdo->query("
            SELECT u.id, u.name, u.email, u.is_active, u.created_at,
                   GROUP_CONCAT(CONCAT(d.name, '|', ud.role) ORDER BY d.name SEPARATOR ',') AS depts_info
            FROM users u
            LEFT JOIN user_departments ud ON ud.user_id = u.id
            LEFT JOIN departments d ON d.id = ud.department_id
            WHERE u.is_super_admin = 0
            GROUP BY u.id
            ORDER BY u.name ASC
        ")->fetchAll();

        $totalUserCount = count($directory);
        $allDepts       = $this->deptModel->getAll();
        $deptApps       = $this->appModel->getByDepartment($currentDept['id']);

        $this->view('panel/management/directory',
            compact('currentDept', 'userRole', 'directory', 'totalUserCount', 'allDepts', 'deptApps'),
            'panel'
        );
    }

    // -------------------------------------------------------
    // Yardımcı: Kullanıcının bu departmandaki rolü
    // -------------------------------------------------------
    private function getUserRoleInDept(string $slug): string
    {
        if (Auth::isSuperAdmin()) return 'director';
        foreach (Auth::getDepartments() as $d) {
            if ($d['slug'] === $slug) return $d['role'];
        }
        return 'member';
    }

    // -------------------------------------------------------
    // Yardımcı: Yönetim yetkisi kontrol
    // -------------------------------------------------------
    private function requireManagement(string $slug): void
    {
        Auth::requireLogin();

        $dept = $this->deptModel->getBySlug($slug);
        if (!$dept) {
            $this->flashError('Departman bulunamadı.');
            $this->redirect('/panel');
        }

        // Süper admin veya yönetim departmanı üyesi olmalı
        if (!Auth::isSuperAdmin() && !$dept['is_management']) {
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit;
        }

        if (!Auth::isSuperAdmin() && !Auth::hasDepartmentAccess($slug)) {
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit;
        }
    }
    // -------------------------------------------------------
    // GET /panel/{slug}/forms
    // -------------------------------------------------------
    public function formManagement(string $slug): void
    {
        Auth::requireLogin();
        Auth::requireDepartment($slug);

        $currentDept = $this->deptModel->getBySlug($slug);
        $userRole    = $this->getUserRoleInDept($slug);
        
        $pdo = Database::getInstance();
        $forms = $pdo->query("
            SELECT a.*, 
                   (SELECT COUNT(*) FROM form_entries WHERE app_id = a.id) as response_count
            FROM apps a
            WHERE a.module_type = 'form' 
              AND a.is_active = 1 
              AND a.slug != 'form'
            ORDER BY a.created_at DESC
        ");
        $allForms = $forms->fetchAll();

        $realForms = [];
        $templates = [];

        foreach ($allForms as $f) {
            $config = json_decode($f['config'] ?? '{}', true) ?: [];
            $isTemplate = $config['settings']['is_template'] ?? false;
            
            if ($isTemplate) {
                $templates[] = $f;
            } else {
                $realForms[] = $f;
            }
        }

        $deptApps = $this->appModel->getByDepartment($currentDept['id']);

        $this->view('panel/forms', compact('currentDept', 'userRole', 'realForms', 'templates', 'deptApps'), 'panel');
    }

    private function generateUniqueAppSlug(string $title): string
    {
        $baseSlug = StringUtils::slugify($title);
        $slug = $baseSlug;
        $counter = 1;
        
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM apps WHERE slug = ?");
        
        while (true) {
            $stmt->execute([$slug]);
            if ((int)$stmt->fetchColumn() === 0) {
                break;
            }
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    // -------------------------------------------------------
    // POST /panel/{slug}/forms/create
    // -------------------------------------------------------
    public function createFormApp(string $slug): void
    {
        Auth::requireLogin();
        Auth::requireDepartment($slug);
        $this->csrfCheck();

        $currentDept = $this->deptModel->getBySlug($slug);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $templateId = (int)($_POST['template_id'] ?? 0);

        if (empty($title)) {
            $this->flashError('Form başlığı zorunludur.');
            $this->redirect("/panel/{$slug}/forms");
        }

        $appSlug = $this->generateUniqueAppSlug($title);
        $config = json_encode(['settings' => ['is_public' => true]], JSON_UNESCAPED_UNICODE);

        // Şablondan kopyalama
        if ($templateId > 0) {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("SELECT config FROM apps WHERE id = ? AND module_type = 'form'");
            $stmt->execute([$templateId]);
            $tpl = $stmt->fetch();
            if ($tpl) {
                $configData = json_decode($tpl['config'] ?? '{}', true) ?: [];
                if (!isset($configData['settings'])) {
                    $configData['settings'] = [];
                }
                // is_template bayrağını false yap
                if (isset($configData['settings']['is_template'])) {
                    $configData['settings']['is_template'] = false;
                }
                $configData['settings']['is_public'] = true;
                $config = json_encode($configData, JSON_UNESCAPED_UNICODE);
            }
        }

        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("
            INSERT INTO apps (department_id, name, slug, description, module_type, config, is_active, director_only)
            VALUES (?, ?, ?, ?, 'form', ?, 1, 0)
        ");
        $stmt->execute([
            $currentDept['id'],
            $title,
            $appSlug,
            $description,
            $config
        ]);

        $this->flashSuccess('Form başarıyla oluşturuldu.');
        Logger::log('CREATE_FORM', 'App', (int)$pdo->lastInsertId(), ['title' => $title, 'department_id' => $currentDept['id']]);
        $this->redirect("/panel/{$slug}/forms");
    }

    // -------------------------------------------------------
    // POST /panel/{slug}/forms/toggle-template
    // -------------------------------------------------------
    public function toggleTemplateForm(string $slug): void
    {
        Auth::requireLogin();
        Auth::requireDepartment($slug);
        $this->csrfCheck();

        $currentDept = $this->deptModel->getBySlug($slug);
        $appId = (int)($_POST['app_id'] ?? 0);
        $isTemplate = (int)($_POST['is_template'] ?? 0);

        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT id, config FROM apps WHERE id = ? AND module_type = 'form'");
        $stmt->execute([$appId]);
        $app = $stmt->fetch();

        if ($app) {
            $configData = json_decode($app['config'] ?? '{}', true) ?: [];
            if (!isset($configData['settings'])) {
                $configData['settings'] = [];
            }
            $configData['settings']['is_template'] = ($isTemplate === 1);
            
            $upd = $pdo->prepare("UPDATE apps SET config = ? WHERE id = ?");
            $upd->execute([json_encode($configData, JSON_UNESCAPED_UNICODE), $appId]);

            $this->flashSuccess($isTemplate ? 'Şablonlara eklendi.' : 'Şablonlardan çıkarıldı.');
            Logger::log('TOGGLE_FORM_TEMPLATE', 'App', $appId, ['is_template' => $isTemplate, 'department_id' => $currentDept['id']]);
        }

        $this->redirect("/panel/{$slug}/forms");
    }
}
