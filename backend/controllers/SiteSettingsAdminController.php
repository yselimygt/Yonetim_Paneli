<?php

require_once __DIR__ . '/../models/SiteSetting.php';

class SiteSettingsAdminController extends Controller
{
    private SiteSetting $settings;

    public function __construct()
    {
        $this->settings = new SiteSetting();
    }

    // GET /superadmin/site-settings
    public function index(): void
    {
        Auth::requireSuperAdmin();
        $allSettings = $this->settings->getAllSettings();

        foreach ($this->defaultSettings() as $key => $value) {
            if (!array_key_exists($key, $allSettings)) {
                $this->settings->set($key, $value);
                $allSettings[$key] = $value;
            }
        }

        $pdo = Database::getInstance();
        $departments = $pdo->query("SELECT id, name FROM departments ORDER BY name")->fetchAll();
        $inboxApps = $pdo->query("SELECT a.id, a.department_id, a.name, d.name AS dept_name FROM apps a JOIN departments d ON d.id = a.department_id WHERE a.module_type = 'inbox' ORDER BY a.name")->fetchAll();
        $allSponsors = $pdo->query("
            SELECT id, name, logo_url, sponsorship_type, status
            FROM sponsors
            ORDER BY status = 'active' DESC, name ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        if (!array_key_exists('sponsors', $allSettings)) {
            $allSettings['sponsors'] = array_values(array_map(
                fn($sponsor) => (int)$sponsor['id'],
                array_filter($allSponsors, fn($sponsor) => ($sponsor['status'] ?? '') === 'active')
            ));
            $this->settings->set('sponsors', $allSettings['sponsors']);
        }

        $hero = $allSettings['hero'] ?? [];
        $brand = $allSettings['brand'] ?? [];
        $contact = $allSettings['contact'] ?? [];
        $popupConfig = $allSettings['popupConfig'] ?? [];
        $stats = $allSettings['stats'] ?? [];
        $sponsors = $allSettings['sponsors'] ?? [];
        $faqs = $allSettings['faqs'] ?? [];
        $formRouting = $allSettings['form_routing'] ?? [];
        $maintenanceMode = $allSettings['maintenanceMode'] ?? [];
        $selectedSponsorIds = $this->normalizeSelectedSponsors($sponsors, $allSponsors);
        $driveMediaItems = $this->loadDriveMediaItems();

        $this->view('superadmin/site_settings/index', compact('hero', 'brand', 'contact', 'popupConfig', 'stats', 'sponsors', 'allSponsors', 'selectedSponsorIds', 'driveMediaItems', 'faqs', 'formRouting', 'departments', 'inboxApps', 'maintenanceMode'), 'superadmin');
    }

    // POST /superadmin/site-settings/hero
    public function updateHero(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $hero = [
            'titleLine1' => trim($_POST['titleLine1'] ?? ''),
            'titleLine2' => trim($_POST['titleLine2'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'primaryCta' => [
                'label' => trim($_POST['primaryCta_label'] ?? ''),
                'href' => trim($_POST['primaryCta_href'] ?? '')
            ],
            'secondaryCta' => [
                'label' => trim($_POST['secondaryCta_label'] ?? ''),
                'href' => trim($_POST['secondaryCta_href'] ?? '')
            ]
        ];

        $this->settings->set('hero', $hero);
        Logger::log('UPDATE_SITE_SETTING', 'SiteSetting', null, ['key' => 'hero']);
        $this->flashSuccess('Hero alanı güncellendi.');
        $this->redirect('/superadmin/site-settings');
    }

    // POST /superadmin/site-settings/brand-contact
    public function updateBrandContact(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $brand = [
            'navbarLogo' => $this->handleSiteImageUpload('navbarLogo', trim($_POST['navbarLogo'] ?? '')),
            'footerLogo' => $this->handleSiteImageUpload('footerLogo', trim($_POST['footerLogo'] ?? '')),
            'popupLogo' => $this->handleSiteImageUpload('popupLogo', trim($_POST['popupLogo'] ?? '')),
            'heroMascot' => $this->handleSiteImageUpload('heroMascot', trim($_POST['heroMascot'] ?? '')),
            'faqMascot' => $this->handleSiteImageUpload('faqMascot', trim($_POST['faqMascot'] ?? '')),
            'brandName' => trim($_POST['brandName'] ?? 'InnoMIS Community'),
            'footerDescription' => trim($_POST['footerDescription'] ?? '')
        ];

        $contact = [
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'addressDetail' => trim($_POST['addressDetail'] ?? ''),
            'mapEmbedUrl' => trim($_POST['mapEmbedUrl'] ?? ''),
            'socials' => [
                'instagram' => trim($_POST['instagram'] ?? ''),
                'x' => trim($_POST['x'] ?? ''),
                'youtube' => trim($_POST['youtube'] ?? ''),
                'tiktok' => trim($_POST['tiktok'] ?? ''),
                'github' => trim($_POST['github'] ?? ''),
                'linkedin' => trim($_POST['linkedin'] ?? '')
            ]
        ];

        $this->settings->set('brand', $brand);
        $this->settings->set('contact', $contact);
        Logger::log('UPDATE_SITE_SETTING', 'SiteSetting', null, ['key' => 'brand_contact']);
        $this->flashSuccess('Logo ve iletişim ayarları güncellendi.');
        $this->redirect('/superadmin/site-settings');
    }

    // POST /superadmin/site-settings/popup
    public function updatePopup(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $popupConfig = [
            'enabled' => isset($_POST['enabled']) && $_POST['enabled'] === 'on',
            'storageKey' => 'innomis_announcement_seen_v2', // bump version to show to all users again
            'autoCloseSec' => (int)($_POST['autoCloseSec'] ?? 5),
            'badge' => trim($_POST['badge'] ?? ''),
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'cta' => [
                'label' => trim($_POST['cta_label'] ?? ''),
                'href' => trim($_POST['cta_href'] ?? '')
            ],
            'secondaryCta' => [
                'label' => trim($_POST['secondaryCta_label'] ?? ''),
                'href' => trim($_POST['secondaryCta_href'] ?? '')
            ]
        ];

        $this->settings->set('popupConfig', $popupConfig);
        Logger::log('UPDATE_SITE_SETTING', 'SiteSetting', null, ['key' => 'popupConfig']);
        $this->flashSuccess('Duyuru Pop-up ayarları güncellendi.');
        $this->redirect('/superadmin/site-settings');
    }

    // POST /superadmin/site-settings/stats
    public function updateStats(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $labels = $_POST['label'] ?? [];
        $values = $_POST['value'] ?? [];
        $descs = $_POST['desc'] ?? [];

        $stats = [];
        foreach ($labels as $i => $label) {
            $label = trim($label);
            if (!empty($label)) {
                $stats[] = [
                    'label' => $label,
                    'value' => trim($values[$i] ?? ''),
                    'desc' => trim($descs[$i] ?? '')
                ];
            }
        }

        $this->settings->set('stats', $stats);
        Logger::log('UPDATE_SITE_SETTING', 'SiteSetting', null, ['key' => 'stats']);
        $this->flashSuccess('İstatistikler güncellendi.');
        $this->redirect('/superadmin/site-settings');
    }

    // POST /superadmin/site-settings/sponsors
    public function updateSponsors(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $ids = array_values(array_unique(array_filter(array_map('intval', $_POST['sponsor_ids'] ?? []))));
        $sponsors = [];

        if (!empty($ids)) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = Database::getInstance()->prepare("SELECT id FROM sponsors WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $validIds = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
            $sponsors = array_values(array_intersect($ids, $validIds));
        }

        $this->settings->set('sponsors', $sponsors);
        Logger::log('UPDATE_SITE_SETTING', 'SiteSetting', null, ['key' => 'sponsors']);
        $this->flashSuccess('Sponsor logoları güncellendi.');
        $this->redirect('/superadmin/site-settings');
    }

    // POST /superadmin/site-settings/faqs
    public function updateFaqs(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $qs = $_POST['q'] ?? [];
        $as = $_POST['a'] ?? [];

        $faqs = [];
        foreach ($qs as $i => $q) {
            $q = trim($q);
            if (!empty($q)) {
                $faqs[] = [
                    'q' => $q,
                    'a' => trim($as[$i] ?? '')
                ];
            }
        }

        $this->settings->set('faqs', $faqs);
        Logger::log('UPDATE_SITE_SETTING', 'SiteSetting', null, ['key' => 'faqs']);
        $this->flashSuccess('Sık sorulan sorular güncellendi.');
        $this->redirect('/superadmin/site-settings');
    }

    // POST /superadmin/site-settings/form-routing
    public function updateFormRouting(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $rules = $_POST['rules'] ?? [];
        $routing = [];

        foreach ($rules as $rule) {
            $type = trim($rule['type'] ?? '');
            if (empty($type)) continue;

            $deptIds = array_values(array_filter(array_map('intval', $rule['dept_ids'] ?? [])));
            if (!empty($deptIds)) {
                $routing[$type] = $deptIds;
            }
        }

        $this->settings->set('form_routing', $routing);
        Logger::log('UPDATE_SITE_SETTING', 'SiteSetting', null, ['key' => 'form_routing']);
        $this->flashSuccess('Form yönlendirme ayarları güncellendi.');
        $this->redirect('/superadmin/site-settings');
    }

    // POST /superadmin/site-settings/maintenance
    public function updateMaintenanceMode(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $maintenanceMode = [
            'enabled' => isset($_POST['enabled']) && $_POST['enabled'] === 'on',
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? '')
        ];

        $this->settings->set('maintenanceMode', $maintenanceMode);
        Logger::log('UPDATE_SITE_SETTING', 'SiteSetting', null, ['key' => 'maintenanceMode']);
        $this->flashSuccess('Bakım modu ayarları güncellendi.');
        $this->redirect('/superadmin/site-settings');
    }

    private function normalizeSelectedSponsors(array $settingSponsors, array $allSponsors): array
    {
        $ids = [];
        $logoMap = [];

        foreach ($allSponsors as $sponsor) {
            $logo = trim((string)($sponsor['logo_url'] ?? ''));
            if ($logo === '') {
                continue;
            }

            $id = (int)$sponsor['id'];
            $logoMap[$logo] = $id;
            $logoMap['/' . ltrim($logo, '/')] = $id;
            $logoMap[BASE_URL . '/' . ltrim($logo, '/')] = $id;
        }

        foreach ($settingSponsors as $value) {
            if (is_numeric($value)) {
                $ids[] = (int)$value;
                continue;
            }

            if (is_string($value)) {
                $normalized = trim($value);
                if (isset($logoMap[$normalized])) {
                    $ids[] = $logoMap[$normalized];
                }
            }
        }

        return array_values(array_unique(array_filter($ids)));
    }

    private function handleSiteImageUpload(string $field, string $fallback): string
    {
        $fileField = $field . '_file';
        if (empty($_FILES[$fileField]) || ($_FILES[$fileField]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return $fallback;
        }

        if (($_FILES[$fileField]['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            return $fallback;
        }

        $tmpPath = $_FILES[$fileField]['tmp_name'] ?? '';
        $originalName = $_FILES[$fileField]['name'] ?? '';
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

        if (!in_array($ext, $allowedExtensions, true) || !is_uploaded_file($tmpPath)) {
            return $fallback;
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/svg', 'text/xml', 'application/xml'];
        $mimeType = '';
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = $finfo ? (string)finfo_file($finfo, $tmpPath) : '';
            if ($finfo) {
                finfo_close($finfo);
            }
        }

        if ($mimeType !== '' && !in_array($mimeType, $allowedMimes, true)) {
            return $fallback;
        }

        $uploadDir = __DIR__ . '/../public/uploads/site-settings/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $safeBase = preg_replace('/[^a-zA-Z0-9_\.-]/', '', pathinfo($originalName, PATHINFO_FILENAME));
        $safeBase = $safeBase !== '' ? $safeBase : $field;
        $fileName = $field . '_' . uniqid('', true) . '_' . $safeBase . '.' . $ext;
        $destPath = $uploadDir . $fileName;

        if (!move_uploaded_file($tmpPath, $destPath)) {
            return $fallback;
        }

        return BASE_URL . '/public/uploads/site-settings/' . $fileName;
    }

    private function loadDriveMediaItems(): array
    {
        try {
            $stmt = Database::getInstance()->query("
                SELECT id, parent_id, type, name, file_path, file_size, mime_type, created_at
                FROM drive_items
                WHERE type = 'folder'
                   OR (
                        type = 'file'
                        AND file_path IS NOT NULL
                        AND (
                            mime_type LIKE 'image/%'
                            OR mime_type LIKE 'video/%'
                            OR LOWER(file_path) REGEXP '\\\\.(jpg|jpeg|png|gif|webp|svg|mp4|webm|mov|m4v)$'
                        )
                   )
                ORDER BY type = 'folder' DESC, name ASC
            ");

            $items = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $item) {
                $path = trim((string)($item['file_path'] ?? ''));
                $mimeType = (string)($item['mime_type'] ?? '');
                $isVideo = str_starts_with($mimeType, 'video/') || (bool)preg_match('/\.(mp4|webm|mov|m4v)$/i', $path);

                $items[] = [
                    'id' => (int)$item['id'],
                    'parent_id' => $item['parent_id'] !== null ? (int)$item['parent_id'] : null,
                    'type' => $item['type'],
                    'name' => $item['name'] ?? 'Görsel',
                    'url' => $path !== '' ? BASE_URL . '/public/' . ltrim($path, '/') : '',
                    'media_type' => $isVideo ? 'video' : 'image',
                    'mime_type' => $mimeType,
                    'file_size' => $item['file_size'] ?? null
                ];
            }

            return $items;
        } catch (Throwable $e) {
            return [];
        }
    }

    private function defaultSettings(): array
    {
        return [
            'hero' => [
                'titleLine1' => 'Bilişimde Yeniliğe',
                'titleLine2' => 'İnovasyonda Zirveye!',
                'description' => 'InnoMIS Community; teknoloji, yapay zeka, girişimcilik ve kariyer odaklı etkinliklerle öğrencileri üretmeye, öğrenmeye ve bağlantı kurmaya davet eden yenilikçi bir öğrenci topluluğudur.',
                'primaryCta' => [
                    'label' => 'Topluluğa Katıl',
                    'href' => '/kayit'
                ],
                'secondaryCta' => [
                    'label' => 'Etkinlikleri Keşfet',
                    'href' => '/events'
                ]
            ],
            'brand' => [
                'navbarLogo' => '/innomis/assets/logo/Yatay-Beyaz.png',
                'footerLogo' => '/innomis/assets/logo/Yatay-Beyaz.png',
                'popupLogo' => '/innomis/assets/logo/Ana-Logo-Arma.png',
                'heroMascot' => '/innomis/assets/innox/innox-kararli.png',
                'faqMascot' => '/innomis/assets/innox/merak-eden-innox.svg',
                'brandName' => 'InnoMIS Community',
                'footerDescription' => 'Teknoloji, inovasyon ve kariyer odaklı öğrenci topluluğu. Öğrenen, üreten ve paylaşan öğrencileri aynı çatı altında buluşturur.'
            ],
            'contact' => [
                'email' => 'info.innomis@gmail.com',
                'phone' => '0555 555 55 55',
                'address' => 'Dere Aşıklar, Köyceğiz Yerleşkesi Köyceğiz, Demeç Sk. No:42/3, 42005 Meram/Konya',
                'addressDetail' => 'Necmettin Erbakan Üniversitesi / Uygulamalı Bilimler Fakültesi',
                'mapEmbedUrl' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.727670977827!2d32.42436737521876!3d37.81989437197368!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14d08db8c0b2f56b%3A0x6b801a2f6fb39e80!2sNecmettin%20Erbakan%20%C3%9Cniversitesi%20Uygulamal%C4%B1%20Bilimler%20Fak%C3%BCltesi!5e0!3m2!1str!2str!4v1700000000000!5m2!1str!2str',
                'socials' => [
                    'instagram' => 'https://www.instagram.com/neuinnomis/',
                    'x' => 'https://x.com/neuinnomis',
                    'youtube' => 'https://www.youtube.com/@neuinnomis',
                    'tiktok' => 'https://www.tiktok.com/@neuinnomis',
                    'github' => 'https://github.com/neuinnomis',
                    'linkedin' => 'https://www.linkedin.com/company/innomis-community'
                ]
            ],
            'popupConfig' => [
                'enabled' => true,
                'storageKey' => 'innomis_announcement_seen_v1',
                'autoCloseSec' => 5,
                'badge' => 'Duyuru',
                'title' => 'InnoMIS Community\'ye Hoş Geldin!',
                'description' => 'Teknoloji, girişimcilik ve yapay zeka dünyasına adım atmaya hazır mısın? InnoMIS Community\'nin etkinliklerini, eğitimlerini ve projelerini takip et.',
                'cta' => [
                    'label' => 'Topluluğa Katıl',
                    'href' => '/kayit'
                ],
                'secondaryCta' => [
                    'label' => 'Keşfet',
                    'href' => '/#about'
                ]
            ],
            'stats' => [
                ['label' => 'Aktif Üye', 'value' => '500+', 'desc' => 'Topluluk ekosisteminde yer alan öğrenci ağı.'],
                ['label' => 'Etkinlik', 'value' => '25+', 'desc' => 'Workshop, panel, zirve ve buluşmalar.'],
                ['label' => 'Eğitim Saati', 'value' => '40+', 'desc' => 'Teknoloji ve kariyer odaklı eğitim içerikleri.'],
                ['label' => 'İş Birliği', 'value' => '15+', 'desc' => 'Marka, topluluk ve sektör paydaşları.']
            ],
            'faqs' => [
                ['q' => 'InnoMIS Community’ye kimler katılabilir?', 'a' => 'Teknoloji, girişimcilik, kariyer ve topluluk çalışmalarına ilgi duyan üniversite öğrencileri katılabilir.'],
                ['q' => 'Etkinliklere katılım ücretli mi?', 'a' => 'Etkinliğe göre değişebilir. Duyurularda katılım koşulları açıkça belirtilir.'],
                ['q' => 'Ekiplerde görev almak mümkün mü?', 'a' => 'Evet. Sosyal medya, etkinlik, Ar-Ge, sponsorluk, tasarım, yazılım ve operasyon ekiplerinde görev alınabilir.'],
                ['q' => 'Sponsor veya partner olarak nasıl iletişime geçilir?', 'a' => 'İletişim formu, e-posta veya sosyal medya hesapları üzerinden toplulukla iletişime geçilebilir.'],
                ['q' => 'Etkinlik duyurularını nereden takip edebilirim?', 'a' => 'Instagram, LinkedIn ve web sitesi üzerinden güncel duyurular takip edilebilir.']
            ],
            'maintenanceMode' => [
                'enabled' => false,
                'title' => 'Bakım Molası / Servis Dışı',
                'description' => 'Sistemlerimizi sizin için daha iyi hale getiriyoruz. Kısa bir bakım molasındayız, lütfen daha sonra tekrar deneyin.'
            ]
        ];
    }
}
