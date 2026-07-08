<?php

// -------------------------------------------------------
// Config & Core yükle
// -------------------------------------------------------
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/View.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/Logger.php';
require_once __DIR__ . '/core/StringUtils.php';
require_once __DIR__ . '/core/ImageOptimizer.php';

// Oturumu başlat
Auth::start();

// -------------------------------------------------------
// Router
// -------------------------------------------------------
$router = new Router();

// Auth
$router->get('/',       ['AuthController', 'showLogin']);
$router->get('/login',  ['AuthController', 'showLogin']);
$router->post('/login', ['AuthController', 'processLogin']);
$router->get('/logout', ['AuthController', 'logout']);
$router->post('/first-login-password', ['AuthController', 'firstLoginPassword']);
$router->get('/pending', ['AuthController', 'showPending']);

// Profil (Tüm kullanıcılar için)
$router->get('/profile',  ['ProfileController', 'index']);
$router->post('/profile', ['ProfileController', 'update']);

// Super Admin (Phase 2)
$router->get('/superadmin',              ['SuperAdminController', 'dashboard']);

$router->get('/superadmin/departments',              ['DepartmentAdminController', 'index']);
$router->post('/superadmin/departments',             ['DepartmentAdminController', 'store']);
$router->get('/superadmin/departments/{id}/edit',    ['DepartmentAdminController', 'edit']);
$router->post('/superadmin/departments/{id}',        ['DepartmentAdminController', 'update']);
$router->post('/superadmin/departments/{id}/delete', ['DepartmentAdminController', 'destroy']);

$router->get('/superadmin/users',              ['UserAdminController', 'index']);
$router->post('/superadmin/users',             ['UserAdminController', 'store']);
$router->get('/superadmin/users/{id}/edit',   ['UserAdminController', 'edit']);
$router->post('/superadmin/users/{id}',        ['UserAdminController', 'update']);
$router->post('/superadmin/users/{id}/delete', ['UserAdminController', 'destroy']);
$router->post('/superadmin/users/{id}/assign', ['UserAdminController', 'assign']);

$router->get('/superadmin/site-events',              ['SiteEventAdminController', 'index']);
$router->post('/superadmin/site-events',             ['SiteEventAdminController', 'store']);
$router->post('/superadmin/site-events/{id}',        ['SiteEventAdminController', 'update']);
$router->post('/superadmin/site-events/{id}/delete', ['SiteEventAdminController', 'destroy']);

$router->get('/superadmin/site-settings',                 ['SiteSettingsAdminController', 'index']);
$router->post('/superadmin/site-settings/hero',           ['SiteSettingsAdminController', 'updateHero']);
$router->post('/superadmin/site-settings/brand-contact',  ['SiteSettingsAdminController', 'updateBrandContact']);
$router->post('/superadmin/site-settings/popup',          ['SiteSettingsAdminController', 'updatePopup']);
$router->post('/superadmin/site-settings/stats',          ['SiteSettingsAdminController', 'updateStats']);
$router->post('/superadmin/site-settings/sponsors',       ['SiteSettingsAdminController', 'updateSponsors']);
$router->post('/superadmin/site-settings/faqs',           ['SiteSettingsAdminController', 'updateFaqs']);
$router->post('/superadmin/site-settings/form-routing',   ['SiteSettingsAdminController', 'updateFormRouting']);
$router->post('/superadmin/site-settings/maintenance',    ['SiteSettingsAdminController', 'updateMaintenanceMode']);

$router->get('/superadmin/social-links',                  ['SocialLinksAdminController', 'index']);
$router->post('/superadmin/social-links',                 ['SocialLinksAdminController', 'update']);

$router->get('/superadmin/site-projects',                 ['SiteProjectAdminController', 'index']);
$router->post('/superadmin/site-projects',                ['SiteProjectAdminController', 'store']);
$router->post('/superadmin/site-projects/{id}',           ['SiteProjectAdminController', 'update']);
$router->post('/superadmin/site-projects/{id}/delete',    ['SiteProjectAdminController', 'destroy']);

$router->get('/superadmin/site-gallery',                  ['SiteGalleryAdminController', 'index']);
$router->post('/superadmin/site-gallery',                 ['SiteGalleryAdminController', 'store']);
$router->post('/superadmin/site-gallery/{id}',            ['SiteGalleryAdminController', 'update']);
$router->post('/superadmin/site-gallery/{id}/delete',     ['SiteGalleryAdminController', 'destroy']);


$router->get('/superadmin/site-team',                     ['SiteTeamAdminController', 'index']);
$router->post('/superadmin/site-team',                    ['SiteTeamAdminController', 'store']);
$router->post('/superadmin/site-team/profile',            ['SiteTeamAdminController', 'saveProfile']);
$router->post('/superadmin/site-team/visibility',         ['SiteTeamAdminController', 'toggleVisibility']);

$router->get('/superadmin/site-history',                  ['SiteHistoryAdminController', 'index']);
$router->post('/superadmin/site-history',                 ['SiteHistoryAdminController', 'store']);
$router->post('/superadmin/site-history/{id}',            ['SiteHistoryAdminController', 'update']);
$router->post('/superadmin/site-history/{id}/delete',     ['SiteHistoryAdminController', 'destroy']);
$router->post('/superadmin/site-history/trophies/{id}',   ['SiteHistoryAdminController', 'updateTrophy']);
$router->post('/superadmin/site-history/trophies/{id}/delete', ['SiteHistoryAdminController', 'destroyTrophy']);

$router->get('/superadmin/apps',              ['AppAdminController', 'index']);
$router->post('/superadmin/apps/assign',      ['AppAdminController', 'assign']);

$router->get('/superadmin/applications',            ['ApplicationController', 'index']);
$router->post('/superadmin/applications/status',    ['ApplicationController', 'updateStatus']);
$router->post('/superadmin/applications/delete',    ['ApplicationController', 'delete']);

$router->get('/superadmin/logs',              ['LogController', 'index']);

$router->get('/superadmin/database',              ['DatabaseAdminController', 'index']);
$router->post('/superadmin/database/backup',      ['DatabaseAdminController', 'backup']);
$router->get('/superadmin/database/download',     ['DatabaseAdminController', 'download']);
$router->post('/superadmin/database/delete',      ['DatabaseAdminController', 'delete']);
$router->post('/superadmin/database/settings',    ['DatabaseAdminController', 'updateSettings']);

// Panel (Phase 3)
$router->get('/panel',         ['PanelController', 'select']);
$router->get('/panel/{slug}',  ['PanelController', 'dashboard']);

// Yönetim Paneli Özel Rotaları
$router->get('/panel/{slug}/management/reports',   ['PanelController', 'managementReports']);
$router->get('/panel/{slug}/management/announce',  ['PanelController', 'managementAnnounce']);
$router->post('/panel/{slug}/management/announce', ['PanelController', 'managementAnnounce']);
$router->get('/panel/{slug}/management/directory', ['PanelController', 'managementDirectory']);

// Sosyal Medya Yönetimi (Global)
$router->get('/panel/{slug}/social-links',         ['PanelController', 'socialLinks']);
$router->post('/panel/{slug}/social-links',        ['PanelController', 'updateSocialLinks']);

// Form Yönetimi
$router->get('/panel/{slug}/forms',                 ['PanelController', 'formManagement']);
$router->post('/panel/{slug}/forms/create',         ['PanelController', 'createFormApp']);
$router->post('/panel/{slug}/forms/toggle-template',['PanelController', 'toggleTemplateForm']);

// Finans Yönetimi
$router->get('/panel/{slug}/finance',                 ['FinanceController', 'index']);
$router->post('/panel/{slug}/finance/store',          ['FinanceController', 'store']);
$router->post('/panel/{slug}/finance/{id}/update',    ['FinanceController', 'update']);
$router->post('/panel/{slug}/finance/{id}/delete',    ['FinanceController', 'destroy']);
$router->post('/panel/{slug}/finance/{id}/receive',   ['FinanceController', 'receiveProxy']);

// Aidat Yönetimi
$router->get('/panel/{slug}/dues',                    ['DuesController', 'index']);
$router->post('/panel/{slug}/dues/periods/store',     ['DuesController', 'storePeriod']);
$router->post('/panel/{slug}/dues/periods/{id}/delete', ['DuesController', 'destroyPeriod']);
$router->post('/panel/{slug}/dues/payments/toggle',   ['DuesController', 'togglePayment']);

// Uygulama Modülleri (Phase 4)
$router->get('/panel/{slug}/apps/{app}',  ['AppRunnerController', 'run']);
$router->post('/panel/{slug}/apps/{app}', ['AppRunnerController', 'handle']);

// Kamuya Açık (Public) API Rotaları
$router->get('/api/events', ['PublicApiController', 'getEvents']);
$router->get('/api/site-data', ['PublicApiController', 'getSiteData']);
$router->post('/api/submit-message', ['PublicApiController', 'submitMessage']);
$router->post('/api/submit-application', ['PublicApiController', 'submitApplication']);
$router->post('/api/submit-membership', ['PublicApiController', 'submitMembership']);
$router->get('/api/cron/auto-backup', ['DatabaseAdminController', 'cronBackup']);

// Kamuya Açık (Public) Rotalar
$router->get('/f/{slug}/{app}',  ['AppRunnerController', 'runPublic']);
$router->post('/f/{slug}/{app}', ['AppRunnerController', 'handlePublic']);

// Genel Formlar (Departmansız) İçin Rotalar
$router->get('/f/{app}',         ['AppRunnerController', 'runPublicGlobal']);
$router->post('/f/{app}',        ['AppRunnerController', 'handlePublicGlobal']);

// Link Kısaltıcı Rotaları
$router->get('/go/{code}',       ['ShortenerController', 'redirect']);

// Sosyal Medya Linktree (Public)
$router->get('/l/sosyal',        ['PublicSocialController', 'index']);

// Sertifika Doğrulama (Public)
$router->get('/sertifika/{no}',  ['CertificateVerifyController', 'verify']);


try {
    $router->dispatch(
        $_SERVER['REQUEST_URI'],
        $_SERVER['REQUEST_METHOD']
    );
} catch (Throwable $e) {
    // Hatayı loglamak iyi bir pratiktir
    error_log($e->getMessage() . "\n" . $e->getTraceAsString());
    
    http_response_code(500);
    require_once __DIR__ . '/views/errors/500.php';
}
