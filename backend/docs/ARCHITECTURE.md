# ARCHITECTURE.md

## Sistem Mimarisi

```
                        ┌─────────────┐
                        │  /login     │
                        └──────┬──────┘
                               │ POST auth
              ┌────────────────┼────────────────┐
              ▼                ▼                ▼
      super_admin          director           member
              │           (1 dept)          (1 dept)
              ▼                ▼                ▼
      /superadmin     /panel/{slug}    /panel/{slug}
                               │
                  (birden fazla departman)
                               ▼
                       /panel (seçim ekranı)
                               │
                       /panel/{slug}
```

---

## Veritabanı Şeması

```sql
-- Kullanıcılar
CREATE TABLE users (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100) NOT NULL,
    email         VARCHAR(150) UNIQUE NOT NULL,
    password      VARCHAR(255) NOT NULL,
    is_super_admin TINYINT(1) DEFAULT 0,
    is_active     TINYINT(1) DEFAULT 1,
    avatar        VARCHAR(255) NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Departmanlar
CREATE TABLE departments (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    slug        VARCHAR(100) UNIQUE NOT NULL,
    description TEXT NULL,
    icon        VARCHAR(50) NULL,         -- ikon adı (örn: "users", "bar-chart")
    color       VARCHAR(7) DEFAULT '#00BCD4',
    is_active   TINYINT(1) DEFAULT 1,
    created_by  INT NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Kullanıcı-Departman ilişkisi (pivot)
CREATE TABLE user_departments (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    user_id       INT NOT NULL,
    department_id INT NOT NULL,
    role          ENUM('director', 'member') DEFAULT 'member',
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_dept (user_id, department_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
);

-- Uygulamalar
CREATE TABLE apps (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100) NOT NULL,
    slug          VARCHAR(100) NOT NULL,
    description   TEXT NULL,
    module_type   VARCHAR(50) NOT NULL,   -- 'form','report','tracker','calendar','announcement'
    department_id INT NOT NULL,
    icon          VARCHAR(50) NULL,
    sort_order    INT DEFAULT 0,
    is_active     TINYINT(1) DEFAULT 1,
    director_only TINYINT(1) DEFAULT 0,   -- sadece director erişir
    config        JSON NULL,              -- app'e özel ayarlar
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_app_dept_slug (department_id, slug),
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
);
```

---

## Rol-Yetki Matrisi

| İşlem | super_admin | director | member |
|-------|:-----------:|:--------:|:------:|
| Departman oluştur | ✅ | ❌ | ❌ |
| Departman düzenle | ✅ | ❌ | ❌ |
| Kullanıcı oluştur | ✅ | ❌ | ❌ |
| Kullanıcı ata | ✅ | ❌ | ❌ |
| App oluştur | ✅ | ❌ | ❌ |
| App yönet (kendi dept) | ✅ | ✅ | ❌ |
| App görüntüle | ✅ | ✅ | ✅* |
| Dashboard görüntüle | ✅ | ✅ | ✅ |

> *`director_only = 1` olan app'leri member göremez.

---

## URL Yönlendirme Tablosu

```
GET  /                                        → redirect /login
GET  /login                                   → giriş sayfası
POST /login                                   → authenticate → redirect
GET  /logout                                  → session destroy → /login

# Super Admin
GET  /superadmin                              → SA dashboard
GET  /superadmin/departments                  → departman listesi
POST /superadmin/departments                  → departman oluştur
GET  /superadmin/departments/{id}/edit        → düzenleme formu
POST /superadmin/departments/{id}             → güncelle
POST /superadmin/departments/{id}/delete      → sil

GET  /superadmin/users                        → kullanıcı listesi
POST /superadmin/users                        → kullanıcı oluştur
GET  /superadmin/users/{id}/edit              → düzenleme formu
POST /superadmin/users/{id}                   → güncelle
POST /superadmin/users/{id}/delete            → sil
POST /superadmin/users/{id}/assign            → departmana ata

GET  /superadmin/apps                         → tüm app listesi
POST /superadmin/apps                         → app oluştur
GET  /superadmin/apps/{id}/edit
POST /superadmin/apps/{id}
POST /superadmin/apps/{id}/delete

# Panel
GET  /panel                                   → departman seçim ekranı
GET  /panel/{slug}                            → departman dashboard
GET  /panel/{slug}/apps/{app-slug}            → app görünümü
POST /panel/{slug}/apps/{app-slug}            → app aksiyonu
POST /panel/{slug}/apps/{app-slug}/{action}   → app sub-aksiyonu
```

---

## Dosya/Klasör Yapısı

```
project-root/
├── .htaccess                    (URL rewrite — mod_rewrite)
├── index.php                    (front controller)
│
├── config/
│   ├── app.php                  (sabitler, ayarlar)
│   ├── database.php             (DB bağlantısı)
│   └── routes.php               (route tanımları)
│
├── core/
│   ├── Router.php               (URL router)
│   ├── Auth.php                 (session, login, redirect)
│   ├── Database.php             (PDO wrapper)
│   ├── Controller.php           (base controller)
│   ├── Model.php                (base model)
│   └── View.php                 (view renderer)
│
├── controllers/
│   ├── AuthController.php
│   ├── SuperAdminController.php
│   ├── DepartmentAdminController.php   (SA: dept yönetim)
│   ├── UserAdminController.php         (SA: kullanıcı yönetim)
│   ├── AppAdminController.php          (SA: app yönetim)
│   ├── PanelController.php             (dept panel)
│   └── AppRunnerController.php         (app çalıştırma)
│
├── models/
│   ├── User.php
│   ├── Department.php
│   ├── UserDepartment.php
│   └── App.php
│
├── views/
│   ├── auth/
│   │   └── login.php
│   ├── superadmin/
│   │   ├── dashboard.php
│   │   ├── departments/
│   │   │   ├── index.php
│   │   │   ├── create.php
│   │   │   └── edit.php
│   │   ├── users/
│   │   │   ├── index.php
│   │   │   ├── create.php
│   │   │   └── edit.php
│   │   └── apps/
│   │       ├── index.php
│   │       ├── create.php
│   │       └── edit.php
│   ├── panel/
│   │   ├── select.php           (departman seçim ekranı)
│   │   ├── dashboard.php
│   │   └── apps/
│   │       └── runner.php       (app modülü renderer)
│   └── layouts/
│       ├── auth.php
│       ├── superadmin.php
│       └── panel.php
│
├── modules/                     (mini uygulama modülleri)
│   ├── BaseModule.php           (abstract)
│   ├── FormModule.php
│   ├── ReportModule.php
│   ├── TrackerModule.php
│   ├── CalendarModule.php
│   └── AnnouncementModule.php
│
├── assets/
│   ├── css/
│   │   ├── main.css
│   │   ├── superadmin.css
│   │   └── panel.css
│   ├── js/
│   │   ├── main.js
│   │   ├── superadmin.js
│   │   └── panel.js
│   └── img/
│
└── storage/
    └── uploads/
```

---

## Authentication Flow

```
1. POST /login (email + password)
2. PDO prepared statement → users tablosu
3. password_verify()
4. Session başlat:
   $_SESSION['user_id']
   $_SESSION['is_super_admin']
   $_SESSION['departments'] = [{id, slug, role}, ...]
5. Yönlendirme kararı:
   is_super_admin = true  →  /superadmin
   1 departman            →  /panel/{slug}
   2+ departman           →  /panel (seçim)
   0 departman            →  /login (hata: "Atanmış departman yok")
```

---

## App Modül Sistemi

```php
// modules/BaseModule.php
abstract class BaseModule {
    protected array $config;
    protected int   $departmentId;
    protected string $role; // 'director' | 'member'

    abstract public function render(): string;
    abstract public function handlePost(array $data): array;
    abstract public function getLabel(): string;

    public function canAccess(): bool {
        // director_only kontrolü
    }
}
```

Her yeni uygulama tipi = yeni bir `BaseModule` alt sınıfı.
`apps.module_type` hangi sınıfın yükleneceğini belirler.

---

## Güvenlik Notları
- Tüm SQL → PDO prepared statements
- XSS koruması → `htmlspecialchars()` tüm çıktılarda
- CSRF token → tüm formlarda
- Session fixation koruması → `session_regenerate_id(true)` login sonrası
- Yetki kontrolü → her controller başında `Auth::check()` + rol doğrulama
- Şifre → `password_hash()` bcrypt
