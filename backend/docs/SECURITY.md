# SECURITY.md

## Güvenlik Dokümantasyonu

Bu dosya projedeki tüm güvenlik katmanlarını, kurallarını ve
kontrol listelerini içerir. Kod yazmadan önce okunmalı,
her PR/commit öncesinde kontrol edilmeli.

---

## 1. Authentication

### Şifre Yönetimi
```php
// Kayıt / şifre güncelleme — MUTLAKA bcrypt
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Giriş doğrulama
if (!password_verify($inputPassword, $storedHash)) {
    // YANLIŞ: "Şifre hatalı" deme — hangi alanın hatalı olduğunu söyleme
    // DOĞRU:
    $error = "E-posta veya şifre hatalı.";
}
```

**Kurallar:**
- Şifre düz metin olarak asla DB'ye yazılmaz
- `MD5`, `SHA1` kullanılmaz — sadece `password_hash()`
- Şifre sıfırlama linki → `bin2hex(random_bytes(32))`, tek kullanımlık, 1 saat geçerli

---

### Session Güvenliği

```php
// Login başarılı olduğunda — ZORUNLU
session_start();
session_regenerate_id(true); // Session fixation koruması

$_SESSION['user_id']       = $user['id'];
$_SESSION['is_super_admin'] = (bool) $user['is_super_admin'];
$_SESSION['departments']   = $userDepartments; // [{id, slug, role}]
$_SESSION['created_at']    = time();           // Session yaşı kontrolü için
```

**Session Timeout:**
```php
// Her istekte kontrol
define('SESSION_LIFETIME', 3600); // 1 saat

if (isset($_SESSION['created_at'])) {
    if (time() - $_SESSION['created_at'] > SESSION_LIFETIME) {
        session_destroy();
        redirect('/login');
        exit;
    }
}
```

**Session Sabitleme (Fixation) Koruması:**
- `session_regenerate_id(true)` — login sonrası ZORUNLU
- Logout'ta `session_destroy()` + cookie sil

```php
// Logout
session_start();
session_unset();
session_destroy();
setcookie(session_name(), '', time() - 3600, '/');
redirect('/login');
exit;
```

---

## 2. SQL Injection Koruması

**Kural: Tek bir SQL bile olsa prepared statement.**

```php
// ❌ YASAK
$result = $pdo->query("SELECT * FROM users WHERE email = '$email'");

// ✅ DOĞRU
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Named parametreler (okunabilirlik için)
$stmt = $pdo->prepare("
    SELECT u.*, ud.role
    FROM users u
    JOIN user_departments ud ON u.id = ud.user_id
    WHERE ud.department_id = :deptId AND u.is_active = 1
");
$stmt->execute([':deptId' => $deptId]);
```

**PDO Konfigürasyonu:**
```php
$pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false, // Gerçek prepared statement
]);
```

> `ATTR_EMULATE_PREPARES => false` kritik.
> Emülasyon açıksa bazı edge case'lerde injection mümkün olabilir.

---

## 3. XSS (Cross-Site Scripting) Koruması

**Kural: Her `echo`, her view çıktısı `htmlspecialchars()` ile.**

```php
// ❌ YASAK
echo $user['name'];
echo $_GET['message'];

// ✅ DOĞRU
echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8');

// Helper fonksiyon (core/View.php içinde tanımla)
function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// View'da kullanım
<h1><?= e($department['name']) ?></h1>
<p><?= e($user['email']) ?></p>
```

**URL çıktısı:**
```php
// URL içinde de encode et
<a href="<?= e($url) ?>">Bağlantı</a>
```

**JavaScript'e veri aktarırken:**
```php
// ❌ YASAK
<script>var name = "<?= $user['name'] ?>";</script>

// ✅ DOĞRU
<script>var name = <?= json_encode($user['name'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;</script>
```

---

## 4. CSRF (Cross-Site Request Forgery) Koruması

**Kural: State değiştiren her form CSRF token içermeli.**

```php
// Token üretme (login sonrası, bir kez)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// View'da her formda
<form method="POST" action="/superadmin/departments">
    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
    ...
</form>

// Controller'da doğrulama — POST işleminden ÖNCE
private function verifyCsrf(): void {
    if (!isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        die('CSRF doğrulama başarısız.');
    }
}
```

**Etkilenen tüm işlemler:**
- Login formu
- Departman oluştur/düzenle/sil
- Kullanıcı oluştur/düzenle/sil
- Departman atama/kaldırma
- App oluştur/düzenle/sil
- Tüm modül POST işlemleri

---

## 5. Yetki Kontrolü (Authorization)

**Kural: Her controller metodunun ilk satırı yetki guard'ı.**

```php
// core/Auth.php
class Auth {

    public static function requireLogin(): void {
        if (empty($_SESSION['user_id'])) {
            redirect('/login');
            exit;
        }
    }

    public static function requireSuperAdmin(): void {
        self::requireLogin();
        if (empty($_SESSION['is_super_admin'])) {
            http_response_code(403);
            include views_path('errors/403.php');
            exit;
        }
    }

    public static function requireDepartmentAccess(string $slug): void {
        self::requireLogin();
        $depts = $_SESSION['departments'] ?? [];
        $slugs = array_column($depts, 'slug');
        if (!in_array($slug, $slugs, true)) {
            redirect('/panel');
            exit;
        }
    }

    public static function requireDirector(string $slug): void {
        self::requireDepartmentAccess($slug);
        $depts = $_SESSION['departments'] ?? [];
        foreach ($depts as $dept) {
            if ($dept['slug'] === $slug && $dept['role'] === 'director') {
                return;
            }
        }
        http_response_code(403);
        include views_path('errors/403.php');
        exit;
    }
}
```

**Kullanım örnekleri:**
```php
// SuperAdminController
public function index(): void {
    Auth::requireSuperAdmin(); // ← ilk satır
    ...
}

// PanelController
public function dashboard(string $slug): void {
    Auth::requireDepartmentAccess($slug); // ← ilk satır
    ...
}

// Director işlemi
public function deleteEntry(string $slug, int $id): void {
    Auth::requireDirector($slug); // ← ilk satır
    ...
}
```

---

## 6. Redirect Güvenliği

**Kural: `header('Location')` sonrası her zaman `exit`.**

```php
// ❌ YASAK — PHP çalışmaya devam eder
header('Location: /login');

// ✅ DOĞRU
header('Location: /login');
exit;

// Helper fonksiyon
function redirect(string $url): never {
    header('Location: ' . $url);
    exit;
}
```

**Open Redirect Koruması:**
```php
// Dışarıdan gelen redirect URL'lerine güvenme
// ❌ YASAK
redirect($_GET['return_url']);

// ✅ DOĞRU — izin verilen URL'leri whitelist'e al
$allowed = ['/panel', '/superadmin', '/login'];
$returnUrl = $_GET['return_url'] ?? '/login';
redirect(in_array($returnUrl, $allowed, true) ? $returnUrl : '/login');
```

---

## 7. Dosya Yükleme Güvenliği

*(Avatar, döküman yükleme için — v1'de opsiyonel)*

```php
// İzin verilen MIME tipler
$allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
$maxSize      = 2 * 1024 * 1024; // 2MB

// MIME tip kontrolü — $_FILES['type']'a güvenme, finfo kullan
$finfo    = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($_FILES['avatar']['tmp_name']);

if (!in_array($mimeType, $allowedTypes, true)) {
    $error = "Geçersiz dosya türü.";
}

if ($_FILES['avatar']['size'] > $maxSize) {
    $error = "Dosya çok büyük (maks. 2MB).";
}

// Dosya adını asla kullanıcıdan alma — yeniden adlandır
$extension = 'jpg'; // mime'den belirle
$newName   = bin2hex(random_bytes(16)) . '.' . $extension;
move_uploaded_file($_FILES['avatar']['tmp_name'], STORAGE_PATH . $newName);
```

**Depolama dizinini web erişimine kapat:**
```apache
# storage/.htaccess
Options -Indexes
<FilesMatch "\.(php|phtml|php3|php4|php5|pl|py|jsp|asp)$">
    Order Allow,Deny
    Deny from all
</FilesMatch>
```

---

## 8. HTTP Güvenlik Başlıkları

```php
// core/App.php veya index.php başında — her istekte
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline';");
```

---

## 9. Hata Yönetimi

**Kural: Production'da PHP hataları kullanıcıya gösterilmez.**

```php
// config/app.php
if (APP_ENV === 'production') {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    error_reporting(E_ALL);
    ini_set('error_log', ROOT_PATH . '/storage/logs/error.log');
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}
```

**Özel hata sayfaları:**
```
views/errors/403.php   → Yetkisiz erişim
views/errors/404.php   → Sayfa bulunamadı
views/errors/500.php   → Sunucu hatası
```

---

## 10. Güvenlik Checklist

Her özellik teslim edilmeden önce bu liste kontrol edilmeli.

### Authentication
- [ ] `password_hash()` bcrypt kullanıldı
- [ ] Login sonrası `session_regenerate_id(true)` var
- [ ] Session timeout uygulandı
- [ ] Logout tam session temizliği yapıyor

### Veri Girişi / Çıkışı
- [ ] Tüm SQL → prepared statement
- [ ] Tüm `echo` → `htmlspecialchars()` / `e()`
- [ ] JSON çıktısı → `json_encode()` güvenli flag'leri

### Form Güvenliği
- [ ] Tüm POST formlar → CSRF token
- [ ] Sunucu tarafı validasyon var (sadece client-side değil)
- [ ] Dosya yüklemede tip + boyut kontrolü

### Yetki
- [ ] Her controller → guard ilk satırda
- [ ] SA sayfaları `requireSuperAdmin()` kullanıyor
- [ ] Panel sayfaları `requireDepartmentAccess()` kullanıyor
- [ ] Director işlemleri `requireDirector()` kullanıyor
- [ ] `director_only` app'ler member tarafından erişilemiyor

### Redirect
- [ ] `header('Location')` sonrası `exit` var
- [ ] Dışarıdan gelen redirect URL whitelist'te

### Genel
- [ ] Production'da `display_errors` kapalı
- [ ] HTTP güvenlik başlıkları set edildi
- [ ] Storage dizini PHP çalıştıramıyor
- [ ] `.htaccess` ile hassas dizinler korunuyor
