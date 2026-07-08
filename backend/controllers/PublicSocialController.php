<?php
require_once __DIR__ . '/../models/SiteSetting.php';

class PublicSocialController extends Controller
{
    public function index(): void
    {
        $siteSettingModel = new SiteSetting();
        $allSettings = $siteSettingModel->getAllSettings();
        
        $socialLinks = $allSettings['social_links'] ?? [];
        $brandSettings = $allSettings['brand'] ?? [];
        
        // Bu görünüm için layout kullanmayacağız (veya 'blank' gibi içi boş layout kullanacağız).
        // Ancak View sınıfında layout null verilemiyor olabilir, bu yüzden layout kısmına '' (boş) göndereceğiz
        // ve View'de var olup olmadığını kontrol edeceğiz. Yoksa doğrudan view içeriğini basar.
        $this->view('public/social_links', [
            'socialLinks'   => $socialLinks,
            'brandSettings' => $brandSettings
        ], ''); 
    }
}
