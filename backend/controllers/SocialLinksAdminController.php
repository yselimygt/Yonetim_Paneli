<?php

require_once __DIR__ . '/../models/SiteSetting.php';

class SocialLinksAdminController extends Controller
{
    private SiteSetting $settings;

    public function __construct()
    {
        $this->settings = new SiteSetting();
    }

    public function index(): void
    {
        Auth::requireSuperAdmin();
        $allSettings = $this->settings->getAllSettings();

        // Default social links if not set
        if (!array_key_exists('social_links', $allSettings)) {
            $defaultLinks = [
                ['platform' => 'Instagram', 'url' => 'https://www.instagram.com/neuinnomis/', 'icon' => 'fa-brands fa-instagram', 'color' => '#E1306C'],
                ['platform' => 'LinkedIn', 'url' => 'https://www.linkedin.com/company/innomis-community', 'icon' => 'fa-brands fa-linkedin-in', 'color' => '#0077B5'],
                ['platform' => 'X', 'url' => 'https://x.com/neuinnomis', 'icon' => 'fa-brands fa-x-twitter', 'color' => '#1da1f2'],
                ['platform' => 'YouTube', 'url' => 'https://www.youtube.com/@neuinnomis', 'icon' => 'fa-brands fa-youtube', 'color' => '#FF0000'],
                ['platform' => 'TikTok', 'url' => 'https://www.tiktok.com/@neuinnomis', 'icon' => 'fa-brands fa-tiktok', 'color' => '#000000'],
                ['platform' => 'GitHub', 'url' => 'https://github.com/neuinnomis', 'icon' => 'fa-brands fa-github', 'color' => '#333333']
            ];
            $this->settings->set('social_links', $defaultLinks);
            $socialLinks = $defaultLinks;
        } else {
            $socialLinks = $allSettings['social_links'];
        }

        $this->view('superadmin/social_links/index', ['socialLinks' => $socialLinks], 'superadmin');
    }

    public function update(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

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

        $this->settings->set('social_links', $socialLinks);
        Logger::log('UPDATE_SITE_SETTING', 'SiteSetting', null, ['key' => 'social_links']);
        
        $this->flashSuccess('Sosyal medya linkleri başarıyla güncellendi.');
        $this->redirect('/superadmin/social-links');
    }
}
