<?php

define('APP_NAME',    'NexPanel');
define('APP_VERSION', '0.1.0-alpha');
define('BASE_URL',    'http://localhost/innomis.tr/backend');

// Oturum ayarları
define('SESSION_LIFETIME', 7200);          // 2 saat (saniye)
define('REMEMBER_ME_DAYS', 7);             // "Beni hatırla" süresi

// Yönetim paneli slug'ı (ayrıcalık kontrollerinde kullanılır)
define('MANAGEMENT_DEPT_SLUG', 'yonetim');

// Hata gösterimi — geliştirme ortamında true, production'da false
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// -------------------------------------------------------
// Güvenlik Ayarları
// -------------------------------------------------------

// Şifreleme anahtarı (PasswordsModule vb. için)
// Production'da environment variable kullanılmalı:
//   putenv('APP_ENCRYPTION_KEY=gercek-guclu-anahtar-buraya');
define('ENCRYPTION_KEY', getenv('APP_ENCRYPTION_KEY') ?: 'nExP4n3l_S3cr3t_K3y_2026_DEV_ONLY!');

// Brute-force koruma ayarları
define('MAX_LOGIN_ATTEMPTS', 5);        // Hesap kilitlenmeden önce izin verilen hatalı giriş sayısı
define('LOGIN_LOCKOUT_MINUTES', 15);    // Kilitlenme süresi (dakika)

// Şifre politikası
define('MIN_PASSWORD_LENGTH', 8);       // Minimum şifre uzunluğu

// İzin verilen modül tipleri (whitelist)
define('ALLOWED_MODULE_TYPES', [
    'form', 'drive', 'calendar', 'announcement', 'report', 'directory',
    'members', 'finance', 'dues', 'sponsor', 'event', 'partner',
    'bootcamp', 'certificate', 'inventory', 'links', 'qr', 'shortener',
    'agile', 'passwords', 'ideahub', 'bylaws', 'audit', 'trophy',
    'wiki', 'meeting', 'budget', 'mentorship', 'social', 'alumni', 'inbox', 'applications', 'media'
]);

// Web sitesi form türleri (form yönlendirme için)
define('FORM_TYPES', [
    'all'               => 'Tüm Formlar (Varsayılan)',
    'sponsorship'       => 'Sponsorluk Başvurusu',
    'contact'           => 'İletişim Formu',
    'membership'        => 'Üyelik Başvurusu',
    'volunteer'         => 'Gönüllü Başvurusu',
    'partnership'       => 'İş Birliği Talebi',
    'event_registration'=> 'Etkinlik Kaydı',
    'job_application'   => 'İş Başvurusu',
    'feedback'          => 'Geri Bildirim',
    'press'             => 'Basın & Medya Talebi',
]);
