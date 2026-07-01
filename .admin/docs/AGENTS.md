# AGENTS.md

## AI Geliştirme Asistanı Kılavuzu

Bu dosya, bu projede çalışan her AI asistanına (Claude ve diğerleri) yönelik
bağlam, kural ve konvansiyonları içerir.
Yeni bir oturumda bu projeyle çalışmadan önce bu dosya okunmalı.

---

## Projeye Genel Bakış

```
Çok departmanlı, rol tabanlı PHP yönetim paneli.
Her departmanın izole alanı, kendi mini uygulamaları var.
Süper admin sistemi dinamik olarak yönetir.
```

Detay için: `PROJECT.md`, `ARCHITECTURE.md`

---

## Stack

```
PHP 8+ (OOP, custom MVC, framework yok)
MySQL 8 (PDO)
Vanilla JS (ES6+)
Custom CSS (dark mode, CSS variables)
Apache + mod_rewrite
```

---

## Alınmış Kararlar — Değiştirilmez

Aşağıdaki kararlar verildi ve tartışmaya kapalı:

| Karar             | Detay                                                        |
| ----------------- | ------------------------------------------------------------ |
| Framework yok     | Custom MVC yazıldı                                         |
| Pivot tablo       | `user_departments` — pivot, `users.department_id` yok   |
| Çok departman    | Bir kullanıcı N departmanda olabilir                       |
| Seçim ekranı    | 2+ departman →`/panel` ara ekranı                        |
| v1 izolasyon      | Departmanlar arası veri v2'ye ertelendi                     |
| `director_only` | `apps` tablosunda flag, ayrı permission tablosu v1'de yok |

Detay için: `NOTES.md`

---

## Kod Yazarken Kurallar

### Zorunlu

- Tüm SQL → PDO prepared statement (`$pdo->prepare()`)
- Tüm HTML çıktısı → `htmlspecialchars()`
- Tüm form → CSRF token
- Login sonrası → `session_regenerate_id(true)`
- Her controller → başında yetki guard

### Konvansiyonlar

```
Sınıflar:    PascalCase    → UserController, FormModule
Metodlar:    camelCase     → processLogin()
Değişkenler: camelCase     → $departmentId
Sabitler:    UPPER_SNAKE   → BASE_URL
DB tablolar: snake_case    → user_departments
DB kolonlar: snake_case    → created_at, is_active
CSS sınıflar: kebab-case   → btn-primary, card-header
```

### Yasak

- `$_GET`, `$_POST` doğrudan SQL'e ekleme
- `echo $_GET['x']` → XSS
- `die()` production kodda
- Hardcoded şifre veya key

---

## Dosya Yapısı (Hızlı Referans)

```
core/          → Router, Auth, Database, Controller, Model, View
controllers/   → AuthController, SuperAdminController, PanelController, AppRunnerController
models/        → User, Department, UserDepartment, App
views/         → auth/, superadmin/, panel/, layouts/
modules/       → BaseModule + alt sınıflar
assets/        → css/, js/, img/
config/        → app.php, database.php, routes.php
```

---

## Yeni Kod Eklerken Sor

Bir özellik eklemeden önce şu soruları cevapla:

1. Bu işlem hangi rol tarafından yapılabilir?
2. Bu veritabanı değişikliği cascade'i etkiler mi?
3. Bu yeni modül tipi mi, mevcut modül konfigürasyonu mu?
4. Bu v1 kapsamında mı, yoksa v2'ye mi ait?

---

## Bilinen Açık Noktalar

Bunları kendin karar verme — kullanıcıya sor:

- Proje adı
- v1'de hangi departmanlar ve hangi app'ler
- Departmanlar arası paylaşılacak veri tipleri
- "Beni hatırla" gerekli mi

---

## Kritik Güvenlik Kontrolleri

Yetki guard eksikliği en sık hata:

```php
// SA sayfaları başında
if (!Auth::isSuperAdmin()) {
    redirect('/login');
    exit;
}

// Panel sayfaları başında
if (!Auth::hasDepartmentAccess($slug)) {
    redirect('/panel');
    exit;
}

// Director-only işlemler
if (!Auth::isDirectorOf($deptId)) {
    forbidden(); // 403
    exit;
}
```

---

## Test Senaryoları (Her Özellikten Sonra)

1. Super admin olmayan biri `/superadmin`'e erişmeye çalışıyor → 403
2. Departmana atanmamış biri `/panel/{slug}`'a erişiyor → redirect
3. Member, `director_only` app'i açmaya çalışıyor → 403
4. Kullanıcı 2 departmanda → seçim ekranı görünüyor
5. SQL injection denemesi → prepared statement blokluyor

---

## Tema

```css
--bg-primary:   #0a0a0a   /* Void Black */
--accent:       #00BCD4   /* Turquoise */
--text-primary: #f0f0f0
--border:       #2a2a2a
```

Gradient yok. Progress bar animasyonu yok. Emoji yok.
Minimal, precis, dark-mode first.

---

## Referans Dosyalar

| Dosya           | İçerik                                 |
| --------------- | ---------------------------------------- |
| PROJECT.md      | Proje özeti, kapsam                     |
| ARCHITECTURE.md | DB şeması, URL tablosu, dosya yapısı |
| TODO.md         | Öncelikli görev listesi                |
| ROADMAP.md      | Faz planı                               |
| APPS.md         | Modül sistemi ve modül tipleri         |
| NOTES.md        | Karar günlüğü                        |
| SKILLS.md       | Kod konvansiyonları                     |
| SECURITY.md     | Güvenlik                                |
