# SKILLS.md

## Tech Stack

| Katman | Teknoloji | Versiyon |
|--------|-----------|----------|
| Backend | PHP (OOP, MVC) | 8.0+ |
| Veritabanı | MySQL | 8.0+ |
| Frontend | Vanilla JS | ES6+ |
| Stil | Custom CSS | — |
| Sunucu | Apache (mod_rewrite) | — |

> Framework yok. Lightweight custom MVC.

---

## PHP — Bilinmesi Gerekenler

### OOP Temelleri
```php
// abstract class — BaseModule için şart
abstract class BaseModule {
    abstract public function render(): string;
}

// interface
interface Renderable {
    public function render(): string;
}
```

### PDO (Veritabanı)
```php
// Prepared statement — zorunlu, her SQL için
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
```

### Session Yönetimi
```php
session_start();
$_SESSION['user_id'] = $user['id'];
session_regenerate_id(true); // login sonrası MUTLAKA
```

### Şifre
```php
// Hash (kayıt/güncelleme)
password_hash($password, PASSWORD_BCRYPT)

// Doğrulama (login)
password_verify($input, $hash)
```

### XSS Koruması
```php
// Her view'da çıktı için zorunlu
htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
```

### CSRF Token
```php
// Token üretme
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Token doğrulama
hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
```

---

## Router Mantığı
```php
// .htaccess — tüm istekleri index.php'ye yönlendir
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

// index.php — URL parse et, controller + method çağır
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($uri, $_SERVER['REQUEST_METHOD']);
```

---

## Vanilla JS — Kullanım Alanları

```javascript
// DOM manipülasyon
document.querySelector('.btn-delete')
document.getElementById('form-dept')

// Fetch API (AJAX — formlar için)
fetch('/superadmin/departments', {
    method: 'POST',
    body: new FormData(form)
}).then(res => res.json())

// Basit state
let currentDept = null;
```

---

## CSS Konvansiyonları

```css
/* CSS Variables — tema */
:root {
    --bg-primary:   #0a0a0a;   /* Void Black */
    --bg-secondary: #111111;
    --bg-card:      #1a1a1a;
    --accent:       #00BCD4;   /* Turquoise */
    --accent-hover: #00ACC1;
    --text-primary: #f0f0f0;
    --text-muted:   #888888;
    --border:       #2a2a2a;
    --danger:       #ef4444;
    --success:      #22c55e;
    --warning:      #f59e0b;
}

/* BEM benzeri isimlendirme */
.card {}
.card__header {}
.card__title {}
.card--active {}
```

---

## Kodlama Konvansiyonları

### PHP
- Sınıf adları: `PascalCase` — `UserController`
- Metot adları: `camelCase` — `processLogin()`
- Değişkenler: `camelCase` — `$userId`
- Sabitler: `UPPER_SNAKE` — `BASE_URL`
- Her controller metodu başında yetki kontrolü

### SQL
- Tablo adları: `snake_case`, çoğul — `users`, `user_departments`
- Kolon adları: `snake_case` — `created_at`, `is_active`
- Tüm sorgular prepared statement

### HTML/CSS
- Sınıf adları: `kebab-case` — `btn-primary`, `card-header`
- ID'ler: `kebab-case` — `form-create-dept`

### Dosya adları
- Controller: `UserAdminController.php`
- Model: `User.php`
- View: `index.php` (klasör adı context'i verir)
- Modül: `FormModule.php`

---

## Mimari Kalıplar

### Controller → Model → View
```
İstek → Router → Controller::method()
                     → Model::query()   (veri)
                     → View::render()   (çıktı)
```

### Guard Pattern (yetki kontrolü)
```php
// Her controller'da ilk satır
Auth::requireSuperAdmin();    // SA sayfaları için
Auth::requireDepartment($slug); // Panel sayfaları için
```

### Flash Mesaj
```php
// Set
$_SESSION['flash'] = ['type' => 'success', 'message' => 'Kaydedildi.'];

// Get (view'da, bir kez okunur ve silinir)
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
```

---

## Güvenlik Checklist

- [ ] Tüm SQL → prepared statement
- [ ] Tüm `echo` → `htmlspecialchars()`
- [ ] Tüm formlar → CSRF token
- [ ] Login sonrası → `session_regenerate_id(true)`
- [ ] Dosya yükleme → tip + boyut doğrulama
- [ ] Redirect sonrası → `exit` (open redirect engeli)
- [ ] `is_super_admin` her SA sayfasında kontrol
- [ ] Departman ataması her panel sayfasında kontrol
