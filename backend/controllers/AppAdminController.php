<?php

require_once __DIR__ . '/../models/App.php';
require_once __DIR__ . '/../models/Department.php';

class AppAdminController extends Controller
{
    private App        $appModel;
    private Department $deptModel;

    const CORE_MODULES = [
        'form'         => ['name' => 'Form',            'icon' => 'file-text'],
        'drive'        => ['name' => 'Dosya Yöneticisi', 'icon' => 'folder'],
        'calendar'     => ['name' => 'Takvim',          'icon' => 'calendar'],
        'announcement' => ['name' => 'Duyuru',          'icon' => 'volume-2'],
        'report'       => ['name' => 'Rapor',           'icon' => 'bar-chart'],
        'directory'    => ['name' => 'Kurumsal Rehber', 'icon' => 'book'],
        'members'      => ['name' => 'Üye Listesi',     'icon' => 'users'],
        'finance'      => ['name' => 'Finans Paneli',   'icon' => 'credit-card'],
        'dues'         => ['name' => 'Aidat Takibi',    'icon' => 'check-circle'],
        'sponsor'      => ['name' => 'Sponsorlar',      'icon' => 'gift'],
        'event'        => ['name' => 'Etkinlik',        'icon' => 'flag'],
        'partner'      => ['name' => 'Anlaşmalı Mekanlar','icon' => 'map-pin'],
        'bootcamp'     => ['name' => 'Bootcamp Eğitimleri','icon' => 'book-open'],
        'certificate'  => ['name' => 'Sertifika',       'icon' => 'award'],
        'inventory'    => ['name' => 'Envanter',        'icon' => 'archive'],
        'links'        => ['name' => 'Hızlı Bağlantılar', 'icon' => 'link'],
        'qr'           => ['name' => 'Karekod',         'icon' => 'qr'],
        'shortener'    => ['name' => 'Link Kısaltıcı',  'icon' => 'scissors'],
        'agile'        => ['name' => 'Agile',           'icon' => 'layout'],
        'passwords'    => ['name' => 'Şifre Yöneticisi','icon' => 'lock'],
        'ideahub'      => ['name' => 'Idea Hub',        'icon' => 'lightbulb'],
        'bylaws'       => ['name' => 'Tüzük',           'icon' => 'feather'],
        'audit'        => ['name' => 'Denetim',         'icon' => 'shield'],
        'trophy'       => ['name' => 'Ödüller',         'icon' => 'target'],
        'wiki'         => ['name' => 'Wiki',            'icon' => 'layers'],
        'meeting'      => ['name' => 'Toplantı',        'icon' => 'video'],
        'budget'       => ['name' => 'Bütçe',           'icon' => 'pie-chart'],
        'mentorship'   => ['name' => 'Mentor',          'icon' => 'navigation'],
        'social'       => ['name' => 'İçerik',          'icon' => 'share-2'],
        'alumni'       => ['name' => 'Mezunlar',        'icon' => 'compass'],
        'inbox'        => ['name' => 'Gelen Kutusu',    'icon' => 'inbox'],
    ];

    public function __construct()
    {
        $this->appModel  = new App();
        $this->deptModel = new Department();
    }

    // GET /superadmin/apps
    public function index(): void
    {
        Auth::requireSuperAdmin();
        $departments = $this->deptModel->getAll();
        
        $selectedDeptId = (int)($_GET['dept_id'] ?? 0);
        $selectedDept = null;
        $assignedModules = [];
        $appData = [];

        if ($selectedDeptId) {
            $selectedDept = $this->deptModel->getById($selectedDeptId);
            if ($selectedDept) {
                $pdo = Database::getInstance();
                $stmt = $pdo->prepare("SELECT module_type, name, sort_order FROM apps WHERE department_id = ? AND is_active = 1 AND slug = module_type");
                $stmt->execute([$selectedDeptId]);
                $rows = $stmt->fetchAll();
                foreach($rows as $row) {
                    $assignedModules[] = $row['module_type'];
                    $appData[$row['module_type']] = [
                        'name' => $row['name'],
                        'sort_order' => (int)$row['sort_order']
                    ];
                }
            }
        }

        $coreModules = self::CORE_MODULES;

        $this->view('superadmin/apps/index', compact('departments', 'selectedDept', 'selectedDeptId', 'coreModules', 'assignedModules', 'appData'), 'superadmin');
    }

    // POST /superadmin/apps/assign
    public function assign(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $deptId = (int)($_POST['department_id'] ?? 0);
        $modules = $_POST['modules'] ?? []; // array of selected module types
        $names = $_POST['names'] ?? [];
        $sortOrders = $_POST['sort_orders'] ?? [];

        if (!$deptId) {
            $this->flashError('Departman seçilmedi.');
            $this->redirect('/superadmin/apps');
        }

        $pdo = Database::getInstance();

        foreach (self::CORE_MODULES as $moduleType => $modInfo) {
            $isActive = in_array($moduleType, $modules) ? 1 : 0;
            
            $name = $names[$moduleType] ?? $modInfo['name'];
            if (trim($name) === '') $name = $modInfo['name'];
            $sortOrder = (int)($sortOrders[$moduleType] ?? 0);

            // Use slug = module_type for core modules
            $slug = $moduleType; 
            $stmt = $pdo->prepare("SELECT id FROM apps WHERE department_id = ? AND module_type = ? AND slug = ?");
            $stmt->execute([$deptId, $moduleType, $slug]);
            $existing = $stmt->fetch();

            if ($existing) {
                // Update
                $update = $pdo->prepare("UPDATE apps SET is_active = ?, name = ?, sort_order = ? WHERE id = ?");
                $update->execute([$isActive, $name, $sortOrder, $existing['id']]);
            } else if ($isActive) {
                // Insert
                $this->appModel->create([
                    'name'          => $name,
                    'slug'          => $slug,
                    'description'   => $name . ' modülü',
                    'module_type'   => $moduleType,
                    'department_id' => $deptId,
                    'icon'          => $modInfo['icon'],
                    'sort_order'    => $sortOrder,
                    'director_only' => 0,
                    'is_active'     => 1,
                    'config'        => '{}',
                ]);
            }
        }

        Logger::log('ASSIGN_APPS_TO_DEPARTMENT', 'Department', $deptId, ['modules' => $modules]);
        $this->flashSuccess('Uygulama atamaları başarıyla güncellendi.');
        $this->redirect('/superadmin/apps?dept_id=' . $deptId);
    }
}
