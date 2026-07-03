<?php

require_once __DIR__ . '/../models/App.php';
require_once __DIR__ . '/../models/Department.php';

class AppAdminController extends Controller
{
    private App        $appModel;
    private Department $deptModel;

    private const SIDEBAR_CATEGORIES = [
        'people' => 'Üyeler & Ağ',
        'project' => 'Proje Yönetimi',
        'communication' => 'İletişim & Başvurular',
        'events' => 'Etkinlik & Takvim',
        'knowledge' => 'Bilgi & Dosyalar',
        'finance' => 'Finans & Raporlama',
        'operations' => 'Operasyon & Araçlar',
        'governance' => 'Yönetim & Denetim',
        'special' => 'Departman Özel',
        'custom' => 'Özel Kategori',
    ];

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
        'applications' => ['name' => 'Başvurular',      'icon' => 'file-text'],
        'media'        => ['name' => 'Medya Yöneticisi','icon' => 'image'],
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
        $this->ensureSidebarCategoryColumns();
        $departments = $this->deptModel->getAll();
        
        $selectedDeptId = (int)($_GET['dept_id'] ?? 0);
        $selectedDept = null;
        $assignedModules = [];
        $appData = [];

        if ($selectedDeptId) {
            $selectedDept = $this->deptModel->getById($selectedDeptId);
            if ($selectedDept) {
                $pdo = Database::getInstance();
                $stmt = $pdo->prepare("SELECT module_type, name, sort_order, sidebar_category_key, sidebar_category_label, sidebar_order, is_primary FROM apps WHERE department_id = ? AND is_active = 1 AND slug = module_type");
                $stmt->execute([$selectedDeptId]);
                $rows = $stmt->fetchAll();
                foreach($rows as $row) {
                    $assignedModules[] = $row['module_type'];
                    $appData[$row['module_type']] = [
                        'name' => $row['name'],
                        'sort_order' => (int)$row['sort_order'],
                        'sidebar_category_key' => $row['sidebar_category_key'] ?? '',
                        'sidebar_category_label' => $row['sidebar_category_label'] ?? '',
                        'sidebar_order' => (int)($row['sidebar_order'] ?? 100),
                        'is_primary' => (int)($row['is_primary'] ?? 0),
                    ];
                }
            }
        }

        $coreModules = self::CORE_MODULES;
        $sidebarCategories = self::SIDEBAR_CATEGORIES;

        $this->view('superadmin/apps/index', compact('departments', 'selectedDept', 'selectedDeptId', 'coreModules', 'assignedModules', 'appData', 'sidebarCategories'), 'superadmin');
    }

    // POST /superadmin/apps/assign
    public function assign(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();
        $this->ensureSidebarCategoryColumns();

        $deptId = (int)($_POST['department_id'] ?? 0);
        $modules = $_POST['modules'] ?? []; // array of selected module types
        $names = $_POST['names'] ?? [];
        $sortOrders = $_POST['sort_orders'] ?? [];
        $sidebarCategories = $_POST['sidebar_categories'] ?? [];
        $customCategoryLabels = $_POST['custom_category_labels'] ?? [];
        $sidebarOrders = $_POST['sidebar_orders'] ?? [];
        $primaryModules = $_POST['primary_modules'] ?? [];

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
            $sidebarOrder = (int)($sidebarOrders[$moduleType] ?? ($sortOrder ?: 100));
            $isPrimary = in_array($moduleType, $primaryModules, true) ? 1 : 0;
            $sidebarMeta = $this->resolveSidebarCategory(
                $moduleType,
                (string)($sidebarCategories[$moduleType] ?? ''),
                (string)($customCategoryLabels[$moduleType] ?? ''),
                $isPrimary
            );

            // Use slug = module_type for core modules
            $slug = $moduleType; 
            $stmt = $pdo->prepare("SELECT id FROM apps WHERE department_id = ? AND module_type = ? AND slug = ?");
            $stmt->execute([$deptId, $moduleType, $slug]);
            $existing = $stmt->fetch();

            if ($existing) {
                // Update
                $update = $pdo->prepare("UPDATE apps SET is_active = ?, name = ?, sort_order = ?, sidebar_category_key = ?, sidebar_category_label = ?, sidebar_order = ?, is_primary = ? WHERE id = ?");
                $update->execute([$isActive, $name, $sortOrder, $sidebarMeta['key'], $sidebarMeta['label'], $sidebarOrder, $isPrimary, $existing['id']]);
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
                    'sidebar_category_key' => $sidebarMeta['key'],
                    'sidebar_category_label' => $sidebarMeta['label'],
                    'sidebar_order' => $sidebarOrder,
                    'is_primary'    => $isPrimary,
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

    private function resolveSidebarCategory(string $moduleType, string $postedKey, string $customLabel, int $isPrimary): array
    {
        if ($isPrimary) {
            return ['key' => 'main', 'label' => 'Ana Uygulamalar'];
        }

        $key = trim($postedKey);
        if ($key === '') {
            $key = $this->defaultSidebarCategory($moduleType);
        }

        if ($key === 'custom') {
            $label = trim($customLabel);
            if ($label === '') {
                $label = 'Departman Özel';
            }
            return ['key' => $this->categoryKeyFromLabel($label), 'label' => $label];
        }

        $label = self::SIDEBAR_CATEGORIES[$key] ?? self::SIDEBAR_CATEGORIES[$this->defaultSidebarCategory($moduleType)];
        return ['key' => $key, 'label' => $label];
    }

    private function defaultSidebarCategory(string $moduleType): string
    {
        $defaults = [
            'members' => 'people',
            'directory' => 'people',
            'alumni' => 'people',
            'mentorship' => 'people',
            'applications' => 'communication',
            'inbox' => 'communication',
            'announcement' => 'communication',
            'form' => 'communication',
            'calendar' => 'events',
            'event' => 'events',
            'bootcamp' => 'events',
            'meeting' => 'events',
            'drive' => 'knowledge',
            'wiki' => 'knowledge',
            'media' => 'knowledge',
            'links' => 'knowledge',
            'certificate' => 'knowledge',
            'finance' => 'finance',
            'dues' => 'finance',
            'budget' => 'finance',
            'report' => 'finance',
            'inventory' => 'operations',
            'passwords' => 'operations',
            'qr' => 'operations',
            'shortener' => 'operations',
            'agile' => 'project',
            'ideahub' => 'project',
            'audit' => 'governance',
            'bylaws' => 'governance',
            'sponsor' => 'communication',
            'partner' => 'communication',
            'social' => 'communication',
            'trophy' => 'events',
        ];

        return $defaults[$moduleType] ?? 'special';
    }

    private function categoryKeyFromLabel(string $label): string
    {
        $key = strtolower(trim($label));
        $key = preg_replace('/[^a-z0-9]+/i', '-', $key) ?: 'custom';
        return 'custom-' . trim($key, '-');
    }

    private function ensureSidebarCategoryColumns(): void
    {
        $pdo = Database::getInstance();
        $columns = [];
        $stmt = $pdo->query("SHOW COLUMNS FROM apps");
        foreach ($stmt->fetchAll() as $column) {
            $columns[$column['Field']] = true;
        }

        $definitions = [
            'sidebar_category_key' => "ALTER TABLE apps ADD COLUMN sidebar_category_key VARCHAR(64) NULL AFTER director_only",
            'sidebar_category_label' => "ALTER TABLE apps ADD COLUMN sidebar_category_label VARCHAR(120) NULL AFTER sidebar_category_key",
            'sidebar_order' => "ALTER TABLE apps ADD COLUMN sidebar_order INT DEFAULT 100 AFTER sidebar_category_label",
            'is_primary' => "ALTER TABLE apps ADD COLUMN is_primary TINYINT(1) DEFAULT 0 AFTER sidebar_order",
        ];

        foreach ($definitions as $column => $sql) {
            if (!isset($columns[$column])) {
                $pdo->exec($sql);
            }
        }
    }
}
