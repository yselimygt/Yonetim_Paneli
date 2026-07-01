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
        
        $popupConfig = $allSettings['popupConfig'] ?? [];
        $stats = $allSettings['stats'] ?? [];
        $sponsors = $allSettings['sponsors'] ?? [];
        $faqs = $allSettings['faqs'] ?? [];

        $this->view('superadmin/site_settings/index', compact('popupConfig', 'stats', 'sponsors', 'faqs'), 'superadmin');
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

        $urls = $_POST['sponsor_url'] ?? [];
        
        $sponsors = [];
        foreach ($urls as $url) {
            $url = trim($url);
            if (!empty($url)) {
                $sponsors[] = $url;
            }
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
}
