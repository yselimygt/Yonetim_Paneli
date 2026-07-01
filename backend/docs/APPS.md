# APPS.md

## Mini Uygulama Sistemi

Her departman, süper admin tarafından atanmış mini uygulamalar çalıştırır.
Her uygulama bir **modül tipine** dayanır. Modül tipi, uygulamanın ne yapacağını tanımlar.

---

## Modül Sistemi Mantığı

```
apps tablosu (DB)
  └── module_type: 'tracker'
        └── modules/TrackerModule.php → load → render()

AppRunnerController:
  1. /panel/{slug}/apps/{app-slug} isteği gelir
  2. DB'den app kaydı çekilir (department kontrolü ile)
  3. module_type → sınıf adı eşleştirme
  4. Sınıf yüklenir, config + role inject edilir
  5. render() çağrılır → view'a gönderilir
```

---

## BaseModule (Abstract Sınıf)

```php
abstract class BaseModule {
    protected array  $config;
    protected int    $departmentId;
    protected string $role;        // 'director' | 'member'
    protected int    $userId;

    public function __construct(array $config, int $deptId, string $role, int $userId) {
        $this->config       = $config;
        $this->departmentId = $deptId;
        $this->role         = $role;
        $this->userId       = $userId;
    }

    abstract public function render(): string;
    abstract public function handlePost(array $data): array; // ['success' => bool, 'message' => '']
    abstract public static function getLabel(): string;      // "Form Uygulaması"

    protected function isDirector(): bool {
        return $this->role === 'director';
    }
}
```

---

## Modül Tipi → Sınıf Eşleştirmesi

```php
// AppRunnerController içinde
private const MODULE_MAP = [
    'form'         => FormModule::class,
    'tracker'      => TrackerModule::class,
    'report'       => ReportModule::class,
    'announcement' => AnnouncementModule::class,
    'calendar'     => CalendarModule::class,
];
```

---

## v1 Modülleri

---

### 1. FormModule
**Amaç:** Özelleştirilebilir veri toplama formu.

**Config Yapısı:**
```json
{
  "fields": [
    { "name": "ad_soyad",   "label": "Ad Soyad",    "type": "text",     "required": true },
    { "name": "tarih",      "label": "Tarih",        "type": "date",     "required": true },
    { "name": "kategori",   "label": "Kategori",     "type": "select",   "options": ["A","B","C"] },
    { "name": "aciklama",   "label": "Açıklama",     "type": "textarea", "required": false }
  ],
  "allow_edit": true,
  "allow_delete_director_only": true
}
```

**Veri Tablosu:**
```sql
CREATE TABLE form_entries (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    app_id        INT NOT NULL,
    department_id INT NOT NULL,
    user_id       INT NOT NULL,
    data          JSON NOT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Director:** Tüm kayıtları görür, düzenler, siler.
**Member:** Kendi kayıtlarını görür, yeni ekler.

---

### 2. TrackerModule
**Amaç:** Kayıt durumu takip sistemi (task/ticket/personel vb.)

**Config Yapısı:**
```json
{
  "statuses": ["Bekliyor", "Devam Ediyor", "Tamamlandı", "İptal"],
  "status_colors": {
    "Bekliyor":      "#f59e0b",
    "Devam Ediyor":  "#3b82f6",
    "Tamamlandı":    "#22c55e",
    "İptal":         "#ef4444"
  },
  "fields": [
    { "name": "baslik",  "label": "Başlık",  "type": "text" },
    { "name": "sorumlu", "label": "Sorumlu", "type": "text" }
  ]
}
```

**Veri Tablosu:**
```sql
CREATE TABLE tracker_items (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    app_id        INT NOT NULL,
    department_id INT NOT NULL,
    created_by    INT NOT NULL,
    title         VARCHAR(255) NOT NULL,
    status        VARCHAR(100) NOT NULL,
    data          JSON NULL,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Director:** Tüm kayıtlar, durum güncelleme, silme.
**Member:** Kendi kaydını ekler, durumunu günceller.

---

### 3. ReportModule
**Amaç:** Mevcut verileri filtreli tablo olarak görüntüleme.

**Config Yapısı:**
```json
{
  "source_app_id": 3,
  "columns": ["ad_soyad", "tarih", "kategori"],
  "filters": ["tarih", "kategori"],
  "director_only": true
}
```

**Veri Kaynağı:** Başka bir app'in form_entries veya tracker_items tablosu.
**Not:** Kendi tablosu yok, var olan veriden rapor üretir.

---

### 4. AnnouncementModule
**Amaç:** Departman içi duyuru yayınlama.

**Config Yapısı:**
```json
{
  "pinnable": true,
  "max_visible": 10
}
```

**Veri Tablosu:**
```sql
CREATE TABLE announcements (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    app_id        INT NOT NULL,
    department_id INT NOT NULL,
    author_id     INT NOT NULL,
    title         VARCHAR(255) NOT NULL,
    content       TEXT NOT NULL,
    is_pinned     TINYINT(1) DEFAULT 0,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Director:** Yayınlar, düzenler, siler, sabitlere alır.
**Member:** Sadece okur.

---

### 5. CalendarModule *(v1 son öncelik)*
**Amaç:** Departman etkinlik takvimi.

**Config Yapısı:**
```json
{
  "member_can_add": false
}
```

**Veri Tablosu:**
```sql
CREATE TABLE calendar_events (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    app_id        INT NOT NULL,
    department_id INT NOT NULL,
    created_by    INT NOT NULL,
    title         VARCHAR(255) NOT NULL,
    description   TEXT NULL,
    event_date    DATE NOT NULL,
    event_time    TIME NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## Yeni Modül Eklemek

1. `modules/` altında `XxxModule.php` oluştur
2. `BaseModule`'u extend et
3. `render()` ve `handlePost()` metodlarını implement et
4. `AppRunnerController::MODULE_MAP`'e ekle
5. SA panelinde "modül tipi" seçeneğine ekle

Yeni bir modül eklemek için başka hiçbir şeyi değiştirmene gerek yok.

---

## v2 Modül Fikirleri
- **SurveyModule** — anket toplama
- **DocumentModule** — dosya paylaşımı
- **BudgetModule** — harcama takibi
- **MemberDirectoryModule** — departman rehberi
- **CrossDeptReportModule** — departmanlar arası rapor (v2 data sharing sonrası)
