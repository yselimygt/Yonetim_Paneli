# 🛡️ NexPanel Güvenlik Denetim Raporu

**Tarih:** 12 Haziran 2026  
**Kapsam:** Tüm PHP dosyaları — config, core, controllers, models, modules  
**Kritiklik Seviyeleri:** 🔴 Kritik · 🟠 Yüksek · 🟡 Orta · 🔵 Düşük

---

## Özet Tablo

| Seviye | Bulgu Sayısı | Kategori |
|--------|-------------|----------|
| 🔴 Kritik | 5 | SQL Injection, Hardcoded Secret, IDOR, Şifre Loglama, CSRF Bypass |
| 🟠 Yüksek | 6 | Dosya Yükleme, Remember Me, Open Redirect, Yetki Atlama, Modül Injection |
| 🟡 Orta | 5 | Session, Debug Mode, Brute Force, Rate Limiting, Şifre Politikası |
| 🔵 Düşük | 4 | HTTP Only Cookie, Logout CSRF, DB Credentials, Directory Listing |

---

## 🔴 KRİTİK SEVİYE

### 1. SQL Injection — ShortenerController (LIKE Sorgusu)

[ShortenerController.php:17-18](file:///c:/xampp/htdocs/innomis-admin/controllers/ShortenerController.php#L17-L18)

```php
$stmt = $db->prepare("SELECT id, config FROM apps WHERE module_type = 'shortener' AND config LIKE ?");
$stmt->execute(['%"code":"' . $code . '"%']);
```

> [!CAUTION]
> `$code` değişkeni doğrudan LIKE parametresinin **içine** birleştiriliyor. Saldırgan `%` ve `_` gibi LIKE wildcard karakterleri kullanarak **tüm kısaltılmış linkleri keşfedebilir**. Daha da kötüsü, JSON içeriğindeki diğer alanları da eşleştirip yetkisiz URL yönlendirmesi yapabilir.

**Çözüm:**
```php
// LIKE wildcard'larını escape et
$safeCode = str_replace(['%', '_'], ['\\%', '\\_'], $code);
$stmt->execute(['%"code":"' . $safeCode . '"%']);
```

---

### 2. Hardcoded Encryption Key — PasswordsModule

[PasswordsModule.php:5](file:///c:/xampp/htdocs/innomis-admin/modules/PasswordsModule.php#L5)

```php
private $encryptionKey = 'nExP4n3l_S3cr3t_K3y_2026!';
```

> [!CAUTION]
> Şifre yöneticisi modülünün **şifreleme anahtarı doğrudan kaynak kodda** yazılı. Git geçmişi, yedekler veya kaynak kodu sızıntısında **tüm saklanan parolalar çözülebilir** hale gelir. AES-256-CBC kullanılıyor ama anahtar zayıf (256 bit'ten kısa).

**Çözüm:**
```php
// config/app.php'de tanımla (veya environment variable kullan)
define('ENCRYPTION_KEY', getenv('APP_ENCRYPTION_KEY') ?: 'fallback-sadece-gelistirme');

// PasswordsModule.php
private $encryptionKey; // constructor'da: $this->encryptionKey = ENCRYPTION_KEY;
```

---

### 3. IDOR — App::getBySlug `$deptId` Parametresini Yok Sayıyor

[App.php:40-48](file:///c:/xampp/htdocs/innomis-admin/models/App.php#L40-L48)

```php
public function getBySlug(int $deptId, string $slug): ?array
{
    $stmt = $this->pdo->prepare(
        "SELECT * FROM apps WHERE slug = ? LIMIT 1"  // ⚠️ $deptId KULLANILMIYOR!
    );
    $stmt->execute([$slug]);
    // ...
}
```

> [!CAUTION]
> `$deptId` parametresi **WHERE koşulunda hiç kullanılmıyor**. Bu, kullanıcının `/panel/kendi-dept/apps/baska-dept-uygulamasi` gibi bir URL ile **başka departmanın uygulamasına erişmesini** sağlar. `AppRunnerController` bu metodu yetkilendirme sonrası bile çağırdığı için, departman erişim kontrolü atlanmış olur.

**Çözüm:**
```php
$stmt = $this->pdo->prepare(
    "SELECT * FROM apps WHERE department_id = ? AND slug = ? LIMIT 1"
);
$stmt->execute([$deptId, $slug]);
```

---

### 4. Hassas Veri Loglama — Hash'lenmiş Şifreler Log'a Yazılıyor

[UserAdminController.php:139-143](file:///c:/xampp/htdocs/innomis-admin/controllers/UserAdminController.php#L139-L143)

```php
$updates['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
// ...
Logger::log('UPDATE_USER', 'User', (int)$id, $updates);  // ⚠️ $updates hash'li şifreyi içeriyor!
```

> [!CAUTION]
> Kullanıcı güncellenirken `$updates` dizisi hash'lenmiş parolayı da içeriyor ve bu JSON olarak `system_logs` tablosuna yazılıyor. Hash'ler çeşitli saldırı yöntemleriyle (rainbow table, brute-force) kırılabilir. `birth_date` gibi hassas veriler de loglanıyor.

**Çözüm:**
```php
$logData = array_diff_key($updates, array_flip(['password', 'birth_date']));
Logger::log('UPDATE_USER', 'User', (int)$id, $logData);
```

---

### 5. Tüm Modüllerde CSRF Koruması Yok

`AppRunnerController::handle()` ([L57-84](file:///c:/xampp/htdocs/innomis-admin/controllers/AppRunnerController.php#L57-L84)) `$module->handlePost($_POST)` çağırıyor ancak **ne controller'da ne de herhangi bir modülde `csrfCheck()` yapılmıyor**.

> [!CAUTION]
> 29 modülün **tamamında** CSRF koruması yok. Saldırgan, oturum açmış bir kullanıcıya zararlı bir HTML sayfası açtırarak şu işlemleri uzaktan yapabilir:
> - Dosya silme (DriveModule)
> - Şifre ekleme/silme (PasswordsModule)
> - Finansal işlem ekleme/silme (Finance, Dues)
> - Form verisi silme (FormModule)
> - Duyuru oluşturma, üye ekleme/silme, vb.

**Etkilenen modüller:** Drive, Passwords, Form, Announcement, Calendar, Event, Sponsor, Members, Directory, Report, Certificate, Inventory, Links, QR, Shortener, Agile, IdeaHub, Bylaws, Audit, Trophy, Wiki, Meeting, Budget, Mentorship, Social, Alumni, Bootcamp

**Çözüm:** `AppRunnerController::handle()` içinde `$this->csrfCheck()` ekle:
```php
public function handle(string $slug, string $appSlug): void
{
    Auth::requireLogin();
    $this->csrfCheck();  // ← buraya ekle
    // ...
}
```

---

## 🟠 YÜKSEK SEVİYE

### 6. Dosya Yükleme — MIME Type Doğrulanmıyor, Path Traversal Riski

[DriveModule.php:62-111](file:///c:/xampp/htdocs/innomis-admin/modules/DriveModule.php#L62-L111)

> [!WARNING]
> - Dosya uzantısı kontrol edilse de **MIME type ve dosya içeriği doğrulanmıyor**. Saldırgan `.jpg` uzantılı bir PHP dosyası yükleyebilir.
> - `$_FILES['file']['type']` güvenilmez — istemci tarafından ayarlanır ve veritabanına güvenilmez MIME type kaydedilir.
> - Yüklenen dosyalar doğrudan `public/uploads/drive/` altına kaydediliyor ve web üzerinden erişilebilir.
> - Aynı sorun [ProfileController.php:63-83](file:///c:/xampp/htdocs/innomis-admin/controllers/ProfileController.php#L63-L83) avatar yüklemesinde de var.
> - [FormModule.php:227-252](file:///c:/xampp/htdocs/innomis-admin/modules/FormModule.php#L227-L252) form dosya yüklemesinde de var, üstelik dosyalar `public/` dışında `uploads/forms/` dizinine yazılıyor.

**Çözüm:**
```php
// 1. MIME kontrolü (finfo ile gerçek dosya tipini doğrula)
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $fileTmp);
$allowedMimes = ['application/pdf', 'image/jpeg', 'image/png', ...];
if (!in_array($mimeType, $allowedMimes)) { /* reddet */ }

// 2. uploads/ dizinine .htaccess ekle
// .htaccess: php_flag engine off  (veya <FilesMatch "\.php$"> Deny from all </FilesMatch>)
```

---

### 7. Remember Me Token — Sadece Session'da Saklanıyor (İşlevsiz ve Riskli)

[Auth.php:96-117](file:///c:/xampp/htdocs/innomis-admin/core/Auth.php#L96-L117)

> [!WARNING]
> - `remember_token` cookie'ye yazılıyor ama veritabanına kaydedilmiyor — sadece session'da tutuluyor.
> - `loginFromCookie()` session zaten aktifse çalışıyor ama session sona erdiğinde hiçbir şey yapmıyor → **"Beni Hatırla" fonksiyonu tamamen işlevsiz**.
> - Cookie'de `secure` flag'i `false` — HTTP üzerinden çalınabilir.
> - Token'ın iptal mekanizması yok.

**Çözüm:** Token'ı DB'de sakla, hash'le, logout'ta sil, `secure: true` yap.

---

### 8. Open Redirect — ShortenerController

[ShortenerController.php:49](file:///c:/xampp/htdocs/innomis-admin/controllers/ShortenerController.php#L49)

```php
header("Location: " . $foundLink['url'], true, 302);
```

> [!WARNING]
> Kısaltılmış linkin hedef URL'si hiçbir doğrulamadan geçmeden kullanılıyor. Saldırgan `javascript:alert(1)` veya kötü niyetli bir URL kaydetmişse, tüm kullanıcılar bu URL'ye yönlendirilir. Ayrıca sahte URL'ler ile phishing saldırıları yapılabilir.

**Çözüm:**
```php
$url = $foundLink['url'];
$parsed = parse_url($url);
if (!in_array($parsed['scheme'] ?? '', ['http', 'https'])) {
    die("Geçersiz URL.");
}
```

---

### 9. Yetkisiz Modül Yükleme — module_type Doğrulanmıyor

[AppRunnerController.php:212-224](file:///c:/xampp/htdocs/innomis-admin/controllers/AppRunnerController.php#L212-L224)

```php
$type = $app['module_type'];
$className = ucfirst($type) . 'Module';
$file = __DIR__ . "/../modules/{$className}.php";
require_once $file;
return new $className($app, $dept, $user, $userRole);
```

> [!WARNING]
> `module_type` değeri veritabanından geliyor ve `../` gibi path traversal karakterleri filtrelenmemiyor. Eğer bir saldırgan veritabanına zararlı bir `module_type` enjekte edebilirse (örn. SQL injection veya admin panelinden), dosya sistemi üzerinde herhangi bir PHP dosyasını çalıştırabilir. `file_exists()` kontrolü var ama dosya adı manipüle edilebilir.

**Çözüm:**
```php
// Whitelist ile doğrula
$allowedModules = array_keys(AppAdminController::CORE_MODULES);
if (!in_array($type, $allowedModules)) {
    die("Geçersiz modül tipi.");
}
```

---

### 10. Public Endpoint'lerde Kimlik Doğrulama ve CSRF Yok

[AppRunnerController.php:88-142](file:///c:/xampp/htdocs/innomis-admin/controllers/AppRunnerController.php#L88-L142)

> [!WARNING]
> `runPublic()` ve `handlePublic()` metodları kimlik doğrulama gerektirmiyor ve CSRF token kontrolü yapmıyor. Public form gönderimlerinde (`handlePostPublic`) herhangi bir rate limiting veya spam koruması yok — botlar sınırsız form gönderebilir.

**Çözüm:** Public form'lara CAPTCHA, rate limiting ve temel honeypot koruması ekle.

---

### 11. `mkdir()` 0777 İzinleri

Birden fazla dosyada:
- [DriveModule.php:68](file:///c:/xampp/htdocs/innomis-admin/modules/DriveModule.php#L68)
- [ProfileController.php:70](file:///c:/xampp/htdocs/innomis-admin/controllers/ProfileController.php#L70)
- [FormModule.php:241](file:///c:/xampp/htdocs/innomis-admin/modules/FormModule.php#L241)

```php
mkdir($targetDir, 0777, true);
```

> [!WARNING]
> `0777` izni sunucudaki **tüm kullanıcılara** tam okuma/yazma/çalıştırma izni verir. Bu, shared hosting ortamlarında diğer hesapların yüklenen dosyalara erişmesini sağlar.

**Çözüm:** `0755` veya `0750` kullanın.

---

## 🟡 ORTA SEVİYE

### 12. Session Yapılandırması — `secure: false`

[Auth.php:14](file:///c:/xampp/htdocs/innomis-admin/core/Auth.php#L14)

```php
'secure' => false, // production'da true
```

> [!IMPORTANT]
> Session cookie'si HTTP üzerinden de gönderilir. Man-in-the-Middle (MITM) saldırısıyla session çalınabilir. Production'da mutlaka `true` yapılmalı ve HTTPS zorunlu olmalı.

---

### 13. Debug Mode Açık

[app.php:15](file:///c:/xampp/htdocs/innomis-admin/config/app.php#L15)

```php
define('DEBUG_MODE', true);
```

> [!IMPORTANT]
> - `display_errors = 1` etkin — hata mesajları dosya yolları, veritabanı bilgileri vb. sızdırabilir.
> - [Database.php:25](file:///c:/xampp/htdocs/innomis-admin/core/Database.php#L25) debug modda tam PDO hata mesajını gösterir.
> - Production'da mutlaka `false` yapılmalı.

---

### 14. Brute-Force Koruması Yetersiz

[AuthController.php:24-60](file:///c:/xampp/htdocs/innomis-admin/controllers/AuthController.php#L24-L60)

> [!IMPORTANT]
> - Math CAPTCHA var ama çok basit (2-12 arası sayılar).
> - **Başarısız giriş denemesi sınırı yok** — account lockout mekanizması yok.
> - IP bazlı rate limiting yok.
> - Saldırgan süresiz deneme yapabilir.

**Çözüm:** Hesap kilitlenmesi (5 hatalı denemeden sonra 15dk), IP bazlı rate limit, daha güçlü CAPTCHA.

---

### 15. Şifre Politikası Çok Zayıf

[UserAdminController.php:66-69](file:///c:/xampp/htdocs/innomis-admin/controllers/UserAdminController.php#L66-L69)

```php
if (strlen($password) < 6) { ... }
```

> [!IMPORTANT]
> Minimum 6 karakter çok zayıf. Büyük/küçük harf, sayı, özel karakter gereksinimleri yok. Profile sayfasında şifre değişikliğinde (`ProfileController`) ise **hiçbir uzunluk kontrolü yok**.

---

### 16. `password` Alanı `trim()` ile İşleniyor

[AuthController.php:29](file:///c:/xampp/htdocs/innomis-admin/controllers/AuthController.php#L29) ve [ProfileController.php:38](file:///c:/xampp/htdocs/innomis-admin/controllers/ProfileController.php#L38)

```php
$password = trim($_POST['password'] ?? '');
```

> [!IMPORTANT]
> Şifrelerin başındaki ve sonundaki boşluklar sessizce siliniyor. Kullanıcı bilinçli olarak boşluk içeren bir şifre belirlemiş olabilir. `password_hash()` ve `password_verify()` zaten binary-safe olduğundan `trim()` gereksiz ve zararlıdır.

---

## 🔵 DÜŞÜK SEVİYE

### 17. Logout GET ile Yapılıyor — CSRF ile Oturum Kapatma

[index.php:29](file:///c:/xampp/htdocs/innomis-admin/index.php#L29) ve [AuthController.php:63-67](file:///c:/xampp/htdocs/innomis-admin/controllers/AuthController.php#L63-L67)

```php
$router->get('/logout', ['AuthController', 'logout']);
```

> [!NOTE]
> Logout GET isteğiyle yapılıyor ve CSRF koruması yok. Saldırgan `<img src="/logout">` ile kullanıcının oturumunu kapatabilir.

---

### 18. Veritabanı Kimlik Bilgileri Şifresiz

[database.php:3-6](file:///c:/xampp/htdocs/innomis-admin/config/database.php#L3-L6)

```php
define('DB_USER', 'root');
define('DB_PASS', '');
```

> [!NOTE]
> Veritabanı kullanıcısı `root` ve şifre boş. Geliştirme ortamı olsa bile, production'a bu şekilde geçilmemeli. Environment variable kullanılmalı.

---

### 19. Seed'de Varsayılan Admin Şifresi

[database.sql:197-200](file:///c:/xampp/htdocs/innomis-admin/database.sql#L197-L200)

> [!NOTE]
> Varsayılan admin şifresi `password` — deploy sonrası değiştirilmezse ciddi güvenlik riski.

---

### 20. Security Header'lar Eksik

`.htaccess` dosyasında aşağıdaki güvenlik header'ları eksik:

```
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Content-Security-Policy: default-src 'self'
Strict-Transport-Security: max-age=31536000
Referrer-Policy: strict-origin-when-cross-origin
```

---

## ✅ Olumlu Bulgular

| Alan | Değerlendirme |
|------|--------------|
| **SQL Injection (genel)** | ✅ PDO prepared statement'lar tutarlı kullanılıyor |
| **XSS Koruması** | ✅ View'larda `View::escape()` ve `htmlspecialchars()` yaygın olarak kullanılıyor |
| **Şifre Hash'leme** | ✅ `password_hash(PASSWORD_BCRYPT)` doğru kullanılıyor |
| **Session Fixation** | ✅ Login'de `session_regenerate_id(true)` yapılıyor |
| **CSRF (Controller seviyesi)** | ✅ Ana controller'larda `csrfCheck()` tutarlı |
| **Yetkilendirme Guard'ları** | ✅ `requireSuperAdmin()`, `requireDepartment()`, `requireDirector()` düzgün tasarlanmış |
| **Audit Logging** | ✅ Kapsamlı loglama sistemi mevcut |
| **emulate_prepares = false** | ✅ PDO'da gerçek prepared statement'lar aktif |

---

## 🎯 Öncelikli Aksiyon Planı

| Öncelik | Eylem | Tahmini Süre |
|---------|-------|-------------|
| **1** | Tüm modüllerde CSRF koruması ekle | 30 dk |
| **2** | `App::getBySlug()` — `$deptId` WHERE koşuluna ekle | 5 dk |
| **3** | Encryption key'i config'e taşı (env variable) | 15 dk |
| **4** | Logger'dan hassas verileri filtrele | 10 dk |
| **5** | ShortenerController LIKE wildcard escape | 5 dk |
| **6** | Dosya yükleme MIME doğrulaması + uploads/.htaccess | 20 dk |
| **7** | `instantiateModule()` whitelist doğrulaması | 5 dk |
| **8** | Session cookie `secure: true` (production) | 5 dk |
| **9** | Debug mode kapatma mekanizması | 5 dk |
| **10** | Brute-force koruması (hesap kilitleme) | 45 dk |

> [!IMPORTANT]
> İlk 5 madde **hemen** uygulanmalıdır. Bunlar aktif olarak istismar edilebilecek kritik güvenlik açıklarıdır.
