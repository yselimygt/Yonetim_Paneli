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

        // Tek departmansa ve yetkili değilse direkt yönlendir
        if (count($depts) === 1 && !(Auth::isSuperAdmin() || Auth::isPresidentOrVice())) {
            $this->redirect('/panel/' . $depts[0]['slug']);
        }

        // Yetkili hiç departmanda değilse dashboard'a gönder
        if ((Auth::isSuperAdmin() || Auth::isPresidentOrVice()) && empty($depts)) {
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
        if ($currentDept['is_management'] || Auth::isSuperAdmin() || Auth::isPresidentOrVice()) {
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

        // 5. Resmi Tatiller (from JSON)
        $holidaysFile = dirname(__DIR__, 2) . '/docs/turkiye_resmi_tatiller_2026_2027.json';
        if (file_exists($holidaysFile)) {
            $holidaysJson = file_get_contents($holidaysFile);
            $holidaysData = json_decode($holidaysJson, true);
            if ($holidaysData && isset($holidaysData['yillar'])) {
                foreach ($holidaysData['yillar'] as $yilData) {
                    foreach ($yilData['tatiller'] as $tatil) {
                        $trFlag = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800" width="16" height="11" style="vertical-align: middle; border-radius: 2px; display: inline-block;"><rect width="1200" height="800" fill="#E30A17"/><circle cx="425" cy="400" r="200" fill="#fff"/><circle cx="475" cy="400" r="160" fill="#E30A17"/><polygon points="800,400 644.25,450.45 693.4,304.5 561.6,400 725,495.5" fill="#fff"/></svg>';
                        $icon = $trFlag; // Default for national holidays
                        $ad_lower = mb_strtolower($tatil['ad']);
                        if (strpos($ad_lower, 'kurban') !== false) {
                            $icon = '🐑';
                        } elseif (strpos($ad_lower, 'ramazan') !== false) {
                            $icon = '🌙';
                        } elseif (strpos($ad_lower, 'yılbaşı') !== false || strpos($ad_lower, 'yilbasi') !== false) {
                            $icon = '🎆';
                        } elseif (strpos($ad_lower, 'emek') !== false) {
                            $icon = '👷‍♂️';
                        }
                        
                        $calendarData['events'][] = [
                            'id' => 'hol_' . md5($tatil['tarih'] . $tatil['ad']),
                            'title' => $tatil['ad'],
                            'date' => $tatil['tarih'],
                            'time' => null,
                            'type' => 'holiday',
                            'category' => 'Resmi Tatil',
                            'source' => 'holiday',
                            'is_holiday' => true,
                            'icon' => $icon
                        ];
                    }
                }
            }
        }

        // 6. Akademik Takvim (from JSON)
        $academicFile = dirname(__DIR__, 2) . '/docs/neu_akademik_takvim_2026_2027.json';
        if (file_exists($academicFile)) {
            $academicJson = file_get_contents($academicFile);
            $academicData = json_decode($academicJson, true);
            if ($academicData && isset($academicData['etkinlikler'])) {
                foreach ($academicData['etkinlikler'] as $etkinlik) {
                    $start_date = new \DateTime($etkinlik['baslangic_tarihi']);
                    $end_date = (isset($etkinlik['bitis_tarihi']) && $etkinlik['bitis_tarihi']) ? new \DateTime($etkinlik['bitis_tarihi']) : null;
                    
                    $is_exam = ($etkinlik['kategori'] === 'sinav');
                    $is_lesson = ($etkinlik['kategori'] === 'ders');
                    
                    if ($is_lesson) {
                        // For lessons, only add start and end date as single day events
                        $dates_to_add = [$start_date];
                        if ($end_date && $start_date->format('Y-m-d') !== $end_date->format('Y-m-d')) {
                            $dates_to_add[] = clone $end_date;
                        }
                        
                        foreach ($dates_to_add as $dt) {
                            $suffix = ($dt === $start_date) ? ' (Başlangıç)' : ' (Bitiş)';
                            $calendarData['events'][] = [
                                'id' => 'acad_' . $etkinlik['id'] . '_' . $dt->format('Y-m-d'),
                                'title' => $etkinlik['baslik'] . $suffix,
                                'date' => $dt->format('Y-m-d'),
                                'time' => $etkinlik['saat'] ?? null,
                                'type' => 'academic',
                                'category' => $etkinlik['kategori'],
                                'source' => 'academic',
                                'is_exam' => false,
                                'is_academic_event' => true
                            ];
                        }
                    } else {
                        // For exams and other events, span the full date range
                        if (!$end_date) {
                            $end_date = clone $start_date;
                        }
                        $end_date_modified = clone $end_date;
                        $end_date_modified->modify('+1 day'); // DatePeriod is exclusive
                        $period = new \DatePeriod($start_date, new \DateInterval('P1D'), $end_date_modified);
                        
                        foreach ($period as $dt) {
                            $calendarData['events'][] = [
                                'id' => 'acad_' . $etkinlik['id'] . '_' . $dt->format('Y-m-d'),
                                'title' => $etkinlik['baslik'],
                                'date' => $dt->format('Y-m-d'),
                                'time' => $etkinlik['saat'] ?? null,
                                'type' => 'academic',
                                'category' => $etkinlik['kategori'],
                                'source' => 'academic',
                                'is_exam' => $is_exam,
                                'is_academic_event' => !$is_exam
                            ];
                        }
                    }
                }
            }
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

        // Kurumsal rehber kayıtları
        $directory = $pdo->query("
            SELECT id, full_name AS name, company, title
            FROM app_directory
            ORDER BY full_name ASC
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
        if (Auth::isSuperAdmin() || Auth::isPresidentOrVice()) return 'director';
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

        // Yetkili veya yönetim departmanı üyesi olmalı
        if (!(Auth::isSuperAdmin() || Auth::isPresidentOrVice()) && !$dept['is_management']) {
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit;
        }

        if (!(Auth::isSuperAdmin() || Auth::isPresidentOrVice()) && !Auth::hasDepartmentAccess($slug)) {
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
    
    // -------------------------------------------------------
    // GET /panel/{slug}/social-links
    // -------------------------------------------------------
    public function socialLinks(string $slug): void
    {
        Auth::requireLogin();
        Auth::requireDepartment($slug);
        
        $currentDept = $this->deptModel->getBySlug($slug);
        $userRole    = $this->getUserRoleInDept($slug);
        $allDepts    = $this->deptModel->getAll();
        $deptApps    = $this->appModel->getByDepartment($currentDept['id']);
        
        require_once __DIR__ . '/../models/SiteSetting.php';
        $siteSettingModel = new SiteSetting();
        $allSettings = $siteSettingModel->getAllSettings();
        
        $socialLinks = $allSettings['social_links'] ?? [];

        $this->view('panel/social_links/index', [
            'currentDept' => $currentDept,
            'userRole'    => $userRole,
            'allDepts'    => $allDepts,
            'deptApps'    => $deptApps,
            'socialLinks' => $socialLinks
        ], 'panel');
    }

    // -------------------------------------------------------
    // POST /panel/{slug}/social-links
    // -------------------------------------------------------
    public function updateSocialLinks(string $slug): void
    {
        Auth::requireLogin();
        Auth::requireDepartment($slug);
        $this->csrfCheck();

        $currentDept = $this->deptModel->getBySlug($slug);
        
        require_once __DIR__ . '/../models/SiteSetting.php';
        $siteSettingModel = new SiteSetting();

        $platforms = $_POST['platform'] ?? [];
        $urls = $_POST['url'] ?? [];
        $icons = $_POST['icon'] ?? [];
        $colors = $_POST['color'] ?? [];

        $socialLinks = [];
        foreach ($platforms as $i => $platform) {
            $platform = trim($platform);
            $url = trim($urls[$i] ?? '');
            if (!empty($platform) && !empty($url)) {
                $socialLinks[] = [
                    'platform' => $platform,
                    'url' => $url,
                    'icon' => trim($icons[$i] ?? ''),
                    'color' => trim($colors[$i] ?? '#ffffff')
                ];
            }
        }

        $siteSettingModel->set('social_links', $socialLinks);
        Logger::log('UPDATE_SITE_SETTING', 'SiteSetting', null, ['key' => 'social_links', 'department_id' => $currentDept['id']]);
        
        $this->flashSuccess('Sosyal medya linkleri başarıyla güncellendi.');
        $this->redirect("/panel/{$slug}/social-links");
    }
}
