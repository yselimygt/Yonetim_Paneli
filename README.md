# InnoMIS Community — Web Sitesi ve Organizasyon Yönetim Platformu

InnoMIS’in kamuya açık web sitesi ile departman, üye, etkinlik, finans ve operasyon süreçlerini aynı projede birleştiren yönetim platformu.

Sistem; öğrenci toplulukları, kulüpler ve benzer organizasyonlarda bilgiyi merkezi olarak tutmak, departmanların kendi araçlarını kullanmasını sağlamak ve yönetimin süreçleri ortak bir panelden izlemesi amacıyla geliştirilmiştir. Yönetim çekirdeğinin kod içindeki adı **NexPanel**’dir.

> **Durum:** Geliştirme / alpha. Backend yapılandırmasında `0.1.0-alpha`, frontend paketinde `1.0.0` sürümü tanımlıdır. Bu sürümler ortak bir yayın olgunluğunu göstermemektedir. Bu README, `innomis.tr-main.zip` arşivindeki kaynak kodun incelenmesiyle hazırlanmıştır; uçtan uca çalışma veya üretime hazır olma garantisi değildir.

## İçindekiler

- [1. Projenin amacı ve kapsamı](#1-projenin-amacı-ve-kapsamı)
- [2. Temel özellikler](#2-temel-özellikler)
- [3. Teknolojiler](#3-teknolojiler)
- [4. Mimari ve klasör yapısı](#4-mimari-ve-klasör-yapısı)
- [5. Roller ve kullanıcı akışları](#5-roller-ve-kullanıcı-akışları)
- [6. Modüller](#6-modüller)
- [7. Gereksinimler](#7-gereksinimler)
- [8. Yerel kurulum](#8-yerel-kurulum)
- [9. Yapılandırma](#9-yapılandırma)
- [10. Sayfalar ve API uçları](#10-sayfalar-ve-api-uçları)
- [11. Veritabanı ve migration yaklaşımı](#11-veritabanı-ve-migration-yaklaşımı)
- [12. Yeni modül geliştirme](#12-yeni-modül-geliştirme)
- [13. Derleme ve dağıtım](#13-derleme-ve-dağıtım)
- [14. Güvenlik ve bilinen eksikler](#14-güvenlik-ve-bilinen-eksikler)
- [15. Doğrulama ve test](#15-doğrulama-ve-test)
- [16. Sorun giderme](#16-sorun-giderme)
- [17. Geliştirme öncelikleri](#17-geliştirme-öncelikleri)
- [18. Katkı, belgeler ve lisans](#18-katkı-belgeler-ve-lisans)

## 1. Projenin amacı ve kapsamı

Platform iki ana ihtiyacı karşılar:

1. **Dış iletişim:** Topluluğun tanıtımı, etkinliklerin duyurulması, ekip ve mezun bilgilerinin yayımlanması, üyelik ve diğer başvuruların alınması.
2. **İç operasyon:** Kullanıcıların departmanlara atanması, görev ve toplantıların takibi, mali kayıtlar, aidatlar, envanter ve ortak bilgi yönetimi.

Departmanlara atanabilen uygulamalar sayesinde farklı ekipler aynı altyapıyı kendi iş akışları için kullanabilir. Bir kullanıcı birden fazla departmana üye olabilir ve her departmanda farklı rol alabilir.

Proje, topluluk ve organizasyon süreçlerine yönelik **ERP benzeri modüler bir yönetim sistemi** olarak değerlendirilebilir. Kapsamlı ticari muhasebe, satış-sipariş, satın alma ve üretim planlama altyapısı bu arşivde doğrulanmamıştır.

## 2. Temel özellikler

### Kamuya açık web sitesi

- Ana sayfa, topluluk tanıtımı ve istatistikler.
- Etkinlik, galeri, kurumsal bilgi, ekip ve mezun sayfaları.
- Ekip ve mezun profilleri için dinamik adresler.
- Üyelik, yönetim ve temsilci başvuru ekranları.
- İletişim ve başvuru verilerini backend’e ileten servisler.
- Sosyal bağlantı sayfası ve duyuru açılır penceresi.
- Marka, iletişim, sponsor, SSS ve bakım modu ayarları.
- Gizlilik, kullanım şartları ve KVKK sayfa bileşenleri.

Yasal metin bileşenlerinin bulunması, içeriklerin hukuken veya operasyonel olarak onaylandığı anlamına gelmez.

### Yönetim platformu

- Oturum tabanlı giriş ve rol bazlı yönlendirme.
- Süper yönetici paneli ve departman panelleri.
- Kullanıcı, departman ve uygulama atama yönetimi.
- Departmana göre uygulama listesi ve menü kategorileri.
- Gelir-gider ve aidat yönetimi için özel sayfalar.
- Modüler görev, etkinlik, dosya ve bilgi yönetimi.
- Site içeriği ve başvuru yönetimi.
- İşlem günlükleri ve veritabanı yedekleme araçları.

## 3. Teknolojiler

| Katman | Kullanılan teknoloji |
| --- | --- |
| Kamuya açık arayüz | React 18, React Router, Vite |
| Arayüz stilleri | Tailwind CSS 3, özel CSS |
| Animasyon ve ikonlar | Framer Motion, Lucide React |
| Yönetim arayüzü | PHP şablonları; ayrıca React yönetim ekranı kaynakları |
| Backend | PHP 8+, framework kullanılmadan yazılmış MVC yapısı |
| Veri erişimi | PDO ve MySQL/MariaDB |
| PDF üretimi | Dompdf; Composer üzerinden yönetiliyor |
| Elektronik tablo yardımcıları | Backend içinde SimpleXLSX ve SimpleXLSXGen |
| Kimlik doğrulama | PHP session, parola hash doğrulaması, hatırlama çerezi |
| Yerel yönlendirme | Apache ve `.htaccess` / `mod_rewrite` |

Kesin bağımlılık sürümleri için `frontend/package-lock.json` ve `backend/composer.lock` esas alınmalıdır.

## 4. Mimari ve klasör yapısı

```text
innomis.tr/
├── .agents/                       # Arşivdeki yapay zekâ çalışma kaynakları
├── .htaccess                      # Seçili public yolları backend’e yönlendirir
├── backend/
│   ├── index.php                  # Başlangıç noktası ve rota tanımları
│   ├── .htaccess                  # Backend yönlendirme ve bazı HTTP başlıkları
│   ├── composer.json
│   ├── composer.lock
│   ├── config/                    # Uygulama ve veritabanı ayarları
│   ├── core/                      # Router, Auth, Model, View ve yardımcılar
│   ├── controllers/               # İstek işleme katmanı
│   ├── models/                    # Veri erişim sınıfları
│   ├── modules/                   # BaseModule ve 32 somut modül sınıfı
│   ├── views/                     # PHP sayfaları, modül görünümleri ve layout’lar
│   ├── migrations/                # SQL şema değişiklikleri
│   ├── database/
│   │   ├── seeds/                 # SQL dökümü ve başlangıç veri yardımcıları
│   │   └── backups/               # Veritabanı yedekleri
│   ├── public/
│   │   ├── assets/                # Stil, script, görsel ve admin çıktıları
│   │   └── uploads/               # Yüklenen dosyalar
│   └── docs/                      # Mimari, modül ve güvenlik belgeleri
├── frontend/
│   ├── package.json
│   ├── package-lock.json
│   ├── vite.config.js
│   ├── public/                    # Statik dosyalar
│   ├── scripts/                   # Derleme sonrası admin dosyası kopyalama
│   ├── src/
│   │   ├── App.jsx                # Public sayfa yönlendirmesi
│   │   ├── admin/                 # React yönetim ekranları
│   │   ├── components/            # Site bileşenleri
│   │   ├── context/               # Site verisi durumu ve normalizasyon
│   │   ├── data/                  # Varsayılan içerikler
│   │   ├── services/              # HTTP servisleri
│   │   └── utils/                 # Yardımcılar
│   └── dist/                      # Frontend derleme çıktısı
└── docs/                          # Geliştirme kurulumu ve arayüz planları
```

### İsteklerin işlenmesi

- **Public web sitesi:** React sayfası → `siteApi.js` → public API → PHP controller → veritabanı.
- **Yönetim paneli:** Apache → `backend/index.php` → Router → controller → model veya modül → PHP view ve layout.
- **Departman uygulaması:** Uygulama kaydı → `module_type` kontrolü → modül sınıfının yüklenmesi → `render()` veya `handlePost()`.

Mevcut yönetim paneli PHP şablonları kullanır. `frontend/src/admin/` altında React ekranları ve ayrı bir derleme girişi vardır; ancak incelenen PHP şablonlarında bu girişin beklediği `admin-root` ve `admin-boot` öğeleri bulunmamıştır. React panel geçişi tamamlanmış kabul edilmemelidir.

## 5. Roller ve kullanıcı akışları

| Rol / işaret | Kodda görülen kapsam |
| --- | --- |
| `is_super_admin` | Kullanıcı üzerindeki sistem yönetimi işareti; tüm departmanlara erişim |
| `president` | Departman atamasında saklanır; ortak Auth kontrollerinde departmanlar arası ayrıcalık sağlar |
| `vice_president` | Ortak Auth kontrollerinde başkana benzer ayrıcalık sağlar |
| `director` | Atandığı departmanda yönetici yetkisi |
| `member` | Atandığı departmanın izin verilen uygulamalarına erişim |
| `representative` | Şemada tanımlı temsilci rolü; ayrıcalıklar ilgili ekran ve modüle göre değerlendirilmelidir |

> Bu tablo kodun genel davranışını özetler. Modüller arasında yetki uygulaması farklılaşmaktadır; ayrıntılar için bilinen eksikler bölümüne bakın.

### Giriş sonrası yönlendirme

```text
Giriş
├── Süper yönetici                  → /superadmin
├── Departman ataması yok           → /pending
├── Tek departman                   → /panel/{slug}
└── Birden fazla departman          → /panel
```

### Tipik yönetim akışı

1. Süper yönetici departmanları ve kullanıcıları tanımlar.
2. Kullanıcıları departmanlara rol belirleyerek atar.
3. Departmanların kullanacağı uygulamaları etkinleştirir.
4. Kullanıcılar kendi panellerinden bu uygulamalara erişir.
5. Yetkili kullanıcılar görev, etkinlik, finans veya diğer operasyon kayıtlarını yönetir.

### Public başvuru akışı

Ziyaretçi ilgili formu doldurur; React servis katmanı JSON isteğini public API’ye gönderir. Kayıtların hangi uygulama veya departmana yönlendirileceği site ayarlarına ve ilgili backend işleyicisine bağlıdır.

## 6. Modüller

Aşağıdaki liste kaynak kodda bulunan somut `BaseModule` türevlerini gösterir. Dosyanın bulunması, bütün işlevlerinin test edildiği anlamına gelmez.

| Modül tipi | Sınıf | Amaç |
| --- | --- | --- |
| `form` | `FormModule` | Form yapılandırma ve yanıt toplama |
| `drive` | `DriveModule` | Dosya ve klasör yönetimi |
| `calendar` | `CalendarModule` | Takvim kayıtları |
| `announcement` | `AnnouncementModule` | Duyurular |
| `report` | `ReportModule` | Raporlama |
| `directory` | `DirectoryModule` | Kurumsal rehber |
| `members` | `MembersModule` | Üye kayıtları |
| `sponsor` | `SponsorModule` | Sponsor yönetimi |
| `event` | `EventModule` | Etkinlik yönetimi |
| `partner` | `PartnerModule` | İş ortakları ve anlaşmalı mekânlar |
| `bootcamp` | `BootcampModule` | Eğitim ve bootcamp kayıtları |
| `certificate` | `CertificateModule` | Sertifika şablonları, kayıtları ve PDF üretimi |
| `inventory` | `InventoryModule` | Demirbaş, eşya ve zimmet takibi |
| `links` | `LinksModule` | Hızlı bağlantılar |
| `qr` | `QrModule` | Karekod araçları |
| `shortener` | `ShortenerModule` | Kısa bağlantılar |
| `agile` | `AgileModule` | Sprint ve görev takibi |
| `passwords` | `PasswordsModule` | Şifre kasası |
| `ideahub` | `IdeaHubModule` | Fikir, yorum ve oylama kayıtları |
| `bylaws` | `BylawsModule` | Tüzük ve sürümleri |
| `audit` | `AuditModule` | Denetim kayıtları |
| `trophy` | `TrophyModule` | Ödül ve başarı kayıtları |
| `wiki` | `WikiModule` | Bilgi bankası |
| `meeting` | `MeetingModule` | Toplantılar ve onay kayıtları |
| `budget` | `BudgetModule` | Bütçe talepleri |
| `mentorship` | `MentorshipModule` | Mentor eşleştirmeleri ve notları |
| `social` | `SocialModule` | Sosyal içerik planlama |
| `alumni` | `AlumniModule` | Mezun profilleri ve ilgili kayıtlar |
| `inbox` | `InboxModule` | Gelen mesajlar |
| `applications` | `ApplicationsModule` | Başvurular |
| `media` | `MediaModule` | Medya yönetimi |
| `task_manager` | `TaskManagerModule` | Görev, ekip, atama ve yorum yönetimi |

**Özel controller kullanan alanlar:** Finans `FinanceController`, aidat `DuesController` üzerinden çalışır. Bunları 32 somut modül sınıfına eklemek doğru değildir. Global sosyal bağlantılar için de ayrı controller ve ekranlar vardır.

## 7. Gereksinimler

| Bileşen | Açıklama |
| --- | --- |
| PHP | Kaynakta PHP 8 özellikleri kullanılır; seçilen sürüm Composer bağımlılıklarıyla doğrulanmalıdır |
| Apache | `mod_rewrite`; `.htaccess` kurallarının uygulanmasına izin veren yapılandırma |
| Veritabanı | Döküm MariaDB 10.4.32’den alınmıştır; farklı MySQL/MariaDB sürümleri ayrıca doğrulanmalıdır |
| Composer | Backend bağımlılıkları için |
| Node.js ve npm | Vite ve React bağımlılıklarıyla uyumlu sürüm; sabitlenmiş Node sürümü bulunmamaktadır |
| PHP eklentileri | PDO MySQL, mbstring, OpenSSL, fileinfo; PDF için DOM/XML; görsel işleme için GD önerilir |
| Dosya izinleri | Upload ve yedek dizinlerine uygulama hesabının gerekli yazma erişimi |

Yerel kurulum belgeleri XAMPP ve `http://localhost/innomis.tr/backend` düzenini esas alır. Aşağıdaki adımlar da bu düzeni kullanır.

## 8. Yerel kurulum

### 8.1. Projeyi yerleştirin

Arşivi Apache’nin yerel web dizinine `innomis.tr` adıyla açın. XAMPP için örnek:

```text
C:/xampp/htdocs/innomis.tr/
```

Apache ve veritabanı servisini başlatın. Komutları proje kökünden çalıştırın. PHP, Composer, Node.js ve npm komutlarının terminalden erişilebilir olduğundan emin olun.

### 8.2. Veritabanını hazırlayın

> `backend/database/seeds/database.sql` yalnızca şema değildir: tablo silme ifadeleri ve veri kayıtları içerir. Mevcut veya üretim veritabanına doğrudan aktarmayın. Ayrı bir yerel veritabanı kullanın; paylaşılabilir kurulumlar için kişisel verilerden temizlenmiş seed hazırlanmalıdır.

MySQL/MariaDB istemcisinde:

```sql
CREATE DATABASE `innomis-nexpanel`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE `innomis-nexpanel`;

SOURCE C:/xampp/htdocs/innomis.tr/backend/database/seeds/database.sql;
```

`SOURCE` yolunu gerçek proje konumunuza göre değiştirin. phpMyAdmin üzerinden içe aktarma da kullanılabilir.

**Giriş akışı için şema eksiği:** Kod `users.last_login_at` ve `users.password_changed` alanlarını kullanır; incelenen başlangıç dökümünde bu alanlar bulunmamaktadır. Önce kontrol edin:

```sql
SHOW COLUMNS FROM users LIKE 'last_login_at';
SHOW COLUMNS FROM users LIKE 'password_changed';
```

Yalnızca eksik olan alanları ekleyin:

```sql
ALTER TABLE users
  ADD COLUMN last_login_at DATETIME NULL;

ALTER TABLE users
  ADD COLUMN password_changed TINYINT(1) NOT NULL DEFAULT 0;
```

Bu, mevcut koddan türetilen kurulum düzeltmesidir; arşivdeki migration dosyalarında yer almamaktadır. Kalıcı geliştirmede ayrı, sürümlenmiş migration haline getirilmelidir.

### 8.3. Backend ayarlarını yapın

`backend/config/database.php` dosyasını yerel bağlantınıza göre düzenleyin:

```php
<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'innomis-nexpanel');
define('DB_USER', 'yerel_uygulama_kullanicisi');
define('DB_PASS', 'KENDI_YEREL_PAROLANIZ');
define('DB_CHARSET', 'utf8mb4');
```

Örnek parolayı gerçek yapılandırmanızla değiştirin. Mevcut kod bu değerleri PHP dosyasından okur; otomatik `.env` yükleyicisi bulunmamaktadır.

`backend/config/app.php` içindeki `BASE_URL` değerini kontrol edin:

```php
define('BASE_URL', 'http://localhost/innomis.tr/backend');
```

### 8.4. PHP bağımlılıklarını kurun

```bash
cd backend
composer install
composer check-platform-reqs
cd ..
```

Composer sistemde kurulu değilse arşivdeki `composer.phar`, kaynağı ve bütünlüğü doğrulandıktan sonra PHP ile kullanılabilir. Sertifika PDF üretimi için `vendor/autoload.php` erişilebilir olmalıdır.

### 8.5. İlk yönetici hesabını hazırlayın

Bu README varsayılan kullanıcı adı veya parola yayımlamaz. İçe aktarılan hesapların güncel ve kullanılabilir olduğu varsayılmamalıdır.

Kontrollü yerel kurulumda ilk süper yönetici için PHP’nin `password_hash(..., PASSWORD_BCRYPT)` çıktısını üretin ve aşağıdaki SQL’deki hash yer tutucusunu değiştirin:

```sql
INSERT INTO users (name, email, password, is_super_admin, is_active)
VALUES (
  'Yerel Yönetici',
  'admin@example.test',
  'BURAYA_PHP_PASSWORD_HASH_CIKTISI',
  1,
  1
);
```

`password` alanına düz metin parola yazmayın. Örnek e-posta yerel test içindir. Aynı e-posta zaten mevcutsa yeni kayıt eklemeden önce ilgili hesabı inceleyin. Web üzerinden erişilebilir geçici yönetici oluşturma scripti bırakmayın.

### 8.6. Frontend bağımlılıklarını kurun

```bash
cd frontend
npm ci
```

**Arşivde eksik kaynak:** `src/App.jsx`, `./projects/oy-takip/OyTakipApp` bileşenini içe aktarıyor; karşılık gelen kaynak arşivde bulunmuyor. Ön yüz derlemesine geçmeden önce şu yollardan biri uygulanmalıdır:

- Özellik kullanılacaksa orijinal bileşen ve gerekli bağımlılıklarını geri getirin.
- Özellik kullanılmayacaksa import’u, ilgili `/projects/oy-takip/*` rotasını ve ona özel erken dönüşü birlikte kaldırın; bağlantıları da kontrol edin.

Bu düzeltme README hazırlanırken kaynak projeye uygulanmamıştır.

Ardından geliştirme sunucusunu başlatın:

```bash
npm run dev
```

Site varsayılan olarak `http://localhost:5173` adresinden açılır. Backend giriş ekranı ayrı olarak `http://localhost/innomis.tr/backend/login` adresindedir. Vite sunucusu PHP’yi çalıştırmaz; Apache ve veritabanı açık kalmalıdır.

## 9. Yapılandırma

### Backend

| Ayar | Dosya / kaynak | Açıklama |
| --- | --- | --- |
| `APP_NAME` | `backend/config/app.php` | Yönetim çekirdeğinin adı |
| `APP_VERSION` | Aynı dosya | Backend sürüm etiketi |
| `BASE_URL` | Aynı dosya | Backend’in tam kök adresi |
| `PUBLIC_URL` | Aynı dosya | `BASE_URL` içinden `/backend` çıkarılarak türetilir |
| `SESSION_LIFETIME` | Aynı dosya | Çerez yaşam süresi ayarı; arşivde 7200 saniye |
| `REMEMBER_ME_DAYS` | Aynı dosya | Hatırlama çerezi süresi; arşivde 7 gün |
| `MANAGEMENT_DEPT_SLUG` | Aynı dosya | Yönetim departmanı tanımı |
| `DEBUG_MODE` | Aynı dosya | Geliştirme hata gösterimi; arşivde açık |
| `APP_ENCRYPTION_KEY` | Sunucu ortam değişkeni | `ENCRYPTION_KEY` için kaynak |
| `MAX_LOGIN_ATTEMPTS` | `backend/config/app.php` | Arşivde 5 başarısız giriş |
| `LOGIN_LOCKOUT_MINUTES` | Aynı dosya | Arşivde 15 dakikalık kontrol aralığı |
| `MIN_PASSWORD_LENGTH` | Aynı dosya | Arşivde 8 karakter |
| `DB_*` | `backend/config/database.php` | Veritabanı bağlantısı |

`PUBLIC_URL` türetimi `/backend` adlı dizin varsayar. Farklı alan adı veya dizin düzeninde adres üretimi ve Router davranışı birlikte gözden geçirilmelidir.

Şifreleme anahtarını sunucu ortamında tanımlayın; kod içindeki geliştirme yedeğine güvenmeyin. Anahtar değişimi mevcut şifre kasası kayıtlarının okunmasını etkileyebilir. Veri taşıma ve anahtar geçişi planlanmadan değiştirmeyin.

### Frontend

`frontend/src/services/siteApi.js`, API tabanı için `VITE_API_URL` değişkenini kullanır. Değer verilmezse `/api` kullanılır.

Gerekirse `frontend/.env.local` oluşturun:

```dotenv
VITE_API_URL=/api
```

`VITE_` değişkenleri tarayıcıya gönderilen derleme çıktısına dahil olabilir; bunlarda parola veya özel anahtar saklamayın. Ortam dosyasını değiştirdikten sonra Vite sunucusunu yeniden başlatın; yayın çıktısı için yeniden derleyin.

Yerel Vite proxy’si `/api` isteklerini `http://localhost/innomis.tr/backend/api` düzenine taşır. Proje dizini değişirse `frontend/vite.config.js` dosyasındaki proxy dönüşümünü de güncelleyin.

Farklı origin üzerinde frontend/backend çalıştırmak için mevcut yapılandırmanın yeterli olduğu varsayılmamalıdır; CORS, çerez ve API adresleri ayrıca düzenlenmelidir.

## 10. Sayfalar ve API uçları

### Public React sayfaları

| Yol | Amaç |
| --- | --- |
| `/` | Ana sayfa |
| `/team`, `/team/:slug` | Ekip ve profil |
| `/alumni`, `/alumni/:year/:slug` | Mezunlar ve profil |
| `/corporate` | Kurumsal bilgiler |
| `/gallery` | Galeri |
| `/events` | Etkinlikler |
| `/iletisim` | İletişim |
| `/kayit` | Üyelik formu |
| `/basvuru` | Başvuru merkezi |
| `/yonetim-basvuru` | Yönetim başvurusu |
| `/temsilci-basvuru` | Temsilci başvurusu |
| `/links` | Bağlantılar |
| `/gizlilik-politikasi`, `/kullanim-sartlari`, `/kvkk` | Bilgilendirme sayfaları |

`/projects/oy-takip/*` rota tanımı vardır; kaynak eksiği nedeniyle kullanılabilir özellik olarak listelenmemiştir.

### Backend ve public API

Aşağıdaki yollar Router’a kayıtlı göreli yollardır; backend adresi üzerinden çağrılabilir. Seçili public yollar ayrıca proje kökündeki rewrite kurallarından geçer.

| Yöntem | Yol | Amaç |
| --- | --- | --- |
| GET / POST | `/login` | Giriş ekranı ve giriş işlemi |
| GET | `/pending` | Departman ataması bekleme |
| GET / POST | `/profile` | Profil görüntüleme ve güncelleme |
| GET | `/superadmin` | Sistem yönetimi |
| GET | `/panel` | Departman seçimi |
| GET | `/panel/{slug}` | Departman özeti |
| GET / POST | `/panel/{slug}/apps/{app}` | Modül görüntüleme / işlem |
| GET | `/panel/{slug}/finance` | Finans ekranı |
| GET | `/panel/{slug}/dues` | Aidat ekranı |
| GET | `/api/site-data` | Site içeriği ve ayarları |
| GET | `/api/events` | Public etkinlik verisi |
| POST | `/api/submit-message` | İletişim mesajı |
| POST | `/api/submit-application` | Başvuru |
| POST | `/api/submit-membership` | Üyelik başvurusu |
| GET / POST | `/f/{slug}/{app}` | Departmana bağlı public form |
| GET / POST | `/f/{app}` | Genel public form adresi |
| GET | `/go/{code}` | Kısa bağlantı yönlendirmesi |
| GET | `/l/sosyal` | Public sosyal bağlantılar |
| GET | `/sertifika/{no}` | Sertifika doğrulama |

Public form erişimi ilgili modülün public erişim işleyicisine ve ayarlarına bağlıdır. Her uygulama kendiliğinden public değildir.

Public React servisleri JSON gönderir. Yönetim ve modül POST işlemleri çoğunlukla form verisi ve session CSRF token’ı kullanır; iki arayüzün istek sözleşmeleri aynı değildir.

Rotaların tamamı için `backend/index.php` dosyasını inceleyin. Finans ve aidat yazma işlemleri için tabloda gösterilmeyen ayrı POST rotaları da bulunur.

## 11. Veritabanı ve migration yaklaşımı

Başlangıç dökümünde **59 tablo** bulunur. Başlıca gruplar:

| Grup | Örnek tablolar |
| --- | --- |
| Kimlik ve organizasyon | `users`, `departments`, `user_departments`, `apps` |
| Finans ve aidat | `finance_transactions`, `dues_periods`, `dues_payments`, `app_budget_requests` |
| Operasyon | `app_inventory`, `app_tasks`, `app_task_assignees`, `app_meetings` |
| Etkinlik ve eğitim | `app_events`, `app_bootcamps`, `app_certificates` |
| İletişim ve bilgi | `form_entries`, `app_inbox_messages`, `app_wiki_articles`, `drive_items` |
| Web içeriği | `site_settings`, `site_events`, `site_gallery`, `site_team_profiles` |
| İzlenebilirlik | `system_logs` |

`apps` tablosu uygulamanın modül tipi, departman ilişkisi, etkinlik durumu ve yapılandırması gibi bilgileri tutar. Birçok modül tablosunda `app_id` ve/veya `department_id` bulunur.

Bu alanların bulunması tek başına veri izolasyonu sağlamaz. Okuma, güncelleme ve silme sorguları da doğru kapsamı doğrulamalıdır.

### Migration kuralları

- `backend/migrations/` altında SQL değişiklikleri vardır; merkezi migration çalıştırıcısı bulunmamaktadır.
- Dosyaları körlemesine toplu çalıştırmayın. Başlangıç dökümü bazı değişiklikleri zaten içeriyor olabilir.
- Önce hedef şemayı ve ilgili SQL dosyasını karşılaştırın; yalnızca eksik değişiklikleri uygulayın.
- Bazı kod yolları çalışma sırasında şema kontrolleri veya değişiklikleri yapar. Üretimdeki veritabanı yetkileri bu davranış dikkate alınarak değerlendirilmelidir.
- Yeni değişiklikleri tarihli ve sürümlenmiş migration dosyasıyla kaydedin; temiz kurulum ve mevcut veriden yükseltme senaryolarını ayrı doğrulayın.

### Yedekleme

Süper yönetici panelinde manuel yedekleme, indirme, silme ve otomatik yedek ayarları bulunur. Otomatik yedek endpoint’i `/api/cron/auto-backup` yolundadır ve etkinleştirme/token ayarlarına bağlıdır. Arşiv bir zamanlayıcının sunucuda gerçekten kurulu olduğunu doğrulamaz.

Yedekler `backend/database/backups/` altında tutulur. Yedek almak kadar ayrı bir veritabanına geri yükleme denemesi yapmak da gereklidir. Yedek dosyaları kişisel veri ve hassas uygulama içeriği barındırabilir.

## 12. Yeni modül geliştirme

Yeni modüller `BaseModule` sınıfını genişletir. Gerçek imza:

```php
public function __construct(
    array $app,
    array $department,
    array $user,
    string $userRole
)
```

Temel sınıf şu iki metodu zorunlu tutar:

```php
public function render(): string;
public function handlePost(array $postData): void;
```

Önerilen geliştirme sırası:

1. `backend/modules/` altında modül sınıfını oluşturun.
2. Görünümü `backend/views/modules/` altına ekleyin.
3. Tipi `ALLOWED_MODULE_TYPES` listesine dahil edin.
4. Atama ekranında kullanılacaksa `AppAdminController::CORE_MODULES` tanımına ekleyin.
5. Gerekli tabloları migration ile oluşturun.
6. Görüntüleme ve her yazma işlemi için rol, uygulama ve departman kapsamını doğrulayın.
7. Public erişim gerekiyorsa mevcut `renderPublic()` / `handlePostPublic()` sözleşmelerini ve gizlilik ayarlarını inceleyin.
8. Departmanlar arası erişim ve yetkisiz işlem senaryolarını test edin.

`AppRunnerController`, sınıf adını `module_type` değerinden üretir; eski belgelerdeki sabit `MODULE_MAP` açıklaması mevcut uygulamayı yansıtmamaktadır. Dosya ve sınıf adının üretim kuralıyla, özellikle büyük/küçük harf duyarlı sistemlerde uyumlu olması gerekir. Örneğin mevcut `ideahub` tipi ile `IdeaHubModule.php` adı bu açıdan kontrol edilmelidir.

## 13. Derleme ve dağıtım

### Frontend komutları

```bash
cd frontend
npm ci
npm run build
npm run preview
```

- `npm run build`: Vite çıktısını `frontend/dist/` altına yazar.
- `postbuild`: `scripts/copy-admin-assets.mjs` otomatik çalışır; admin girişinin JS/CSS çıktıları ile bazı marka dosyalarını backend’e kopyalar.
- `npm run preview`: Derlenmiş ön yüzü yerel olarak incelemek içindir. Geliştirme proxy’si preview yapılandırmasında tanımlı değildir; API bağlantısı ayrıca doğrulanmalıdır.

Admin dosyalarının kopyalanması, React yönetim ekranlarının PHP sayfalarına bağlandığını göstermez.

### Sunucuda yerleşim

Dağıtımda şu eşleşmeler açıkça kurulmalıdır:

| İstek | Hedef |
| --- | --- |
| Public sayfalar | Frontend derleme çıktısı ve SPA geri dönüşü |
| Backend panel yolları | PHP front controller |
| API ve public form yolları | İlgili PHP rotaları |
| Statik dosyalar | Frontend ve backend public dizinleri |

Kök `.htaccess` yalnızca `f`, `api`, `go` ve `l` public yol gruplarını backend’e yönlendirir; proje köküne React çıktısının otomatik yayımlanmasını sağlamaz. Sertifika gibi başka backend yollarının kökten açılması isteniyorsa ayrıca yönlendirme gerekir.

Frontend `base` ayarı `/` değerindedir. Site alt dizinde yayımlanacaksa Vite base ayarı, React yönlendirmesi, statik dosya adresleri ve `.htaccess` geri dönüş hedefi birlikte düzenlenmelidir. Kaynakta `/innomis/assets/...` gibi farklı dizin varsayımları da vardır.

### Yayına çıkmadan önce

- Bilinen yetki ve CSRF eksiklerini kapatın.
- Eksik kaynak dosyasını ve veritabanı alanlarını giderin.
- `DEBUG_MODE` değerini `false` yapın; hata kayıtlarını kullanıcıya göstermeden tutun.
- HTTPS, çerez ve ters proxy davranışını doğrulayın.
- Ortama özel URL, veritabanı ve şifreleme ayarlarını yapılandırın.
- SQL dökümleri, yapılandırmalar ve yedeklere doğrudan web erişimini engelleyin.
- Apache dışındaki sunucularda `.htaccess` korumalarının otomatik uygulanmadığını dikkate alın.
- Upload dizinlerinde script çalıştırılmasını engelleyin ve dosya izinlerini sınırlandırın.
- Yalnızca gerekli yayın dosyalarını dağıtın; kişisel veri içeren geliştirme yedeklerini eklemeyin.
- Derleme, giriş, yetkilendirme, yükleme ve geri yükleme akışlarını doğrulayın.

## 14. Güvenlik ve bilinen eksikler

Bu bölüm kaynak kod incelemesinden türetilmiştir; canlı sistemde saldırı veya sızma testi yapılmamıştır.

### Mevcut korumalar

- Parolalarda PHP hash üretimi ve `password_verify()` kullanımı.
- Başarılı girişte session ID yenileme.
- Session çerezlerinde HttpOnly ve SameSite ayarları; HTTPS algılandığında Secure bayrağı.
- Hatırlama token’ının veritabanında SHA-256 özetiyle saklanması.
- Ortak CSRF kontrolü ve modül POST girişinde token doğrulaması.
- Giriş denemeleri için günlük tabanlı kilit kontrolü.
- Modül tipi izin listesi.
- Birçok sorguda parametreli PDO kullanımı.

Bu kontrollerin varlığı, her controller ve modülde tutarlı uygulandığı anlamına gelmez.

### Öncelikli sorunlar

| Öncelik | Alan | Kaynakta görülen durum | Gerekli çalışma |
| --- | --- | --- | --- |
| Yüksek | Envanter | Bazı yazma/silme sorguları yalnızca kayıt ID’sine bakıyor | Her işlemde uygulama, departman ve işlem yetkisini doğrulamak |
| Yüksek | Finans | Yönetici düğmeleri arayüzde gizleniyor; yazma endpoint’lerinde yönetici kontrolü eksik | Sunucu tarafında rol kontrolü eklemek |
| Yüksek | Veritabanı yönetimi | Yedek oluşturma, silme ve ayar güncellemede `csrfCheck()` çağrısı yok | Bütün ilgili POST işlemlerinde CSRF doğrulamak |
| Yüksek | Şema uyumu | Giriş kodunda kullanılan iki alan başlangıç dökümünde yok | Sürümlenmiş migration eklemek |
| Yüksek | Frontend derlemesi | `OyTakipApp` import’unun kaynak dosyası yok | Kaynağı geri getirmek veya özelliğin referanslarını kaldırmak |
| Orta | Şifre kasası | Kaynakta geliştirme anahtarı yedeği var; AES-CBC kullanılıyor | Ortam anahtarı zorunluluğu ve bütünlük doğrulamalı şifreleme geçişini değerlendirmek |
| Orta | Kimlik / yetki güncelliği | Roller ve departman atamaları session’a girişte yükleniyor | Hesap veya yetki değişikliklerinin açık oturumlara yansımasını doğrulamak |
| Orta | React admin | Gerekli mount ve başlangıç verisi öğeleri PHP şablonlarında bulunamadı | Ekran bazlı entegrasyonu tamamlamak |
| Orta | Dağıtım | Hata ayıklama açık, veri içeren döküm ve yedekler arşivde | Yayın yapılandırması ve veri temizliği |
| Orta | Testler | Otomatik test dosyaları ve test scriptleri bulunamadı | Yetki ve kritik iş akışları için test altyapısı kurmak |

Proje bu maddeler çözülmeden üretime hazır kabul edilmemelidir. Bu README sorunları belgeler; kaynak kodda düzeltme uygulamaz.

## 15. Doğrulama ve test

Arşivde `npm test`, PHPUnit veya uçtan uca test çalıştırma düzeni tanımlı değildir. Aşağıdakiler önerilen doğrulamalardır; çalıştırılmış test sonuçları değildir.

### Teknik kontroller

```bash
cd backend
composer validate
composer check-platform-reqs
cd ../frontend
npm ci
npm run build
```

PHP sözdizimini kontrol etmek için proje kökünden PowerShell örneği:

```powershell
Get-ChildItem backend -Recurse -Filter *.php |
  Where-Object { $_.FullName -notmatch '[\\/]vendor[\\/]' } |
  ForEach-Object { php -l $_.FullName }
```

Sözdizimi kontrolü ve başarılı derleme, iş akışlarının doğru çalıştığını kanıtlamaz.

### Kullanıcı kabul senaryoları

- [ ] Hatalı ve doğru giriş, CAPTCHA, hesap kilidi ve çıkış davranışı.
- [ ] Süper yönetici, tek departman, çok departman ve atamasız kullanıcı yönlendirmeleri.
- [ ] Üyenin yönetici işlemlerini doğrudan HTTP isteğiyle yapamaması.
- [ ] Bir departmandaki kullanıcının başka departmanın kaydını okuyamaması veya değiştirememesi.
- [ ] Finans ekleme, düzenleme, silme ve özetlerin birbiriyle uyumu.
- [ ] Aidat dönemi ve ödeme kayıtlarının tutarlılığı.
- [ ] Envanter ekleme, zimmet, devir ve silme işlemlerinde kayıt kapsamı.
- [ ] Public üyelik ve başvuru kayıtlarının doğru hedefe ulaşması.
- [ ] Formların public erişim ayarlarına uyması.
- [ ] Dosya türü, boyutu ve erişim yetkisi kontrolleri.
- [ ] Sertifika PDF üretimi ve doğrulama ekranı.
- [ ] Yedek oluşturma ve ayrı veritabanına geri yükleme.
- [ ] Mobil görünüm, klavye kullanımı ve form hata mesajları.
- [ ] Public alt sayfalarda doğrudan açılış / sayfa yenileme.

## 16. Sorun giderme

| Sorun | Kontrol |
| --- | --- |
| `Could not resolve ... OyTakipApp` | Eksik proje bileşeni ve `App.jsx` referansları |
| Girişte `Unknown column 'last_login_at'` veya `password_changed` | Kurulum bölümündeki şema kontrolü ve migration eksiği |
| API yerine HTML dönüyor | Vite proxy, backend kök adresi ve Apache rewrite kuralları |
| Public site açılıyor ama veriler gelmiyor | Ağ istekleri, veritabanı bağlantısı ve `/api/site-data` yanıtı |
| Panel rotaları 404 veriyor | `BASE_URL`, proje dizin adı, `mod_rewrite`, `AllowOverride` |
| Alt sayfayı yenileyince 404 oluşuyor | Frontend SPA geri dönüşü ve yayın dizini |
| Logolar veya görseller kayıp | `/innomis/assets/` dahil sabit yollar ve yayın kökü |
| PDF üretilemiyor | Composer kurulumu, `vendor/autoload.php`, PHP eklentileri ve dosya izinleri |
| Linux’ta modül dosyası bulunamıyor | Tipten üretilen sınıf adı ile gerçek dosya adının harf uyumu |
| React admin görünmüyor | PHP sayfasındaki `admin-root`, `admin-boot` ve bundle bağlantısı |
| Dosya yükleme başarısız | Yazma izinleri, PHP upload limitleri ve modül dosya doğrulaması |
| Migration tekrarında sütun hatası | İlgili alanın başlangıç dökümünde zaten bulunup bulunmadığı |

`SiteDataContext` bazı site ayarları için varsayılan içerikler kullanır. Sayfanın kısmen görünmesi API bağlantısının sağlıklı olduğunu göstermez; ağ yanıtlarını kontrol edin.

## 17. Geliştirme öncelikleri

Aşağıdaki sıra mevcut incelemeye göre önerilir; tamamlanmış iş listesi değildir.

1. **Çalışabilir kurulum:** Eksik React kaynağını, giriş şemasını ve adres uyumsuzluklarını giderme.
2. **Veri ve yetki güvenliği:** Envanter kapsamı, finans rolü, CSRF ve açık oturumların yetki güncelliği.
3. **Tekrarlanabilir veritabanı:** Temiz seed, sürümlü migration ve doğrulanmış geri yükleme.
4. **Yönetim arayüzü kararı:** PHP paneli ile React admin geçişinin sınırlarını belirleme ve entegrasyonu tamamlama.
5. **Kritik testler:** Rol/departman izolasyonu, finans, aidat, başvuru ve sertifika akışları.
6. **Yayın düzeni:** Ortam ayarları, güvenli dosya yerleşimi ve otomatik derleme kontrolleri.
7. **Belge uyumu:** Eski plan belgelerini mevcut kodla eşleştirme ve sürüm politikasını birleştirme.

## 18. Katkı, belgeler ve lisans

### Geliştirme ilkeleri

- Mevcut controller / model / module ayrımını koruyun.
- Yetkilendirmeyi yalnızca düğme veya menü görünürlüğüne bırakmayın.
- İstemciden gelen kayıt ID’si, uygulama ID’si ve departman bilgisini güvenilir kabul etmeyin.
- SQL sorgularında parametre kullanın; çıktıların bağlama uygun kaçışını yapın.
- İş akışına uygun hata mesajı üretin; hassas hata ayrıntılarını kullanıcıya göstermeyin.
- Şema değişikliklerini ve çalışma komutlarını belgeleyin.
- Gizli değer, kişisel veri, upload ve yedekleri kaynak kod değişikliklerine eklemeyin.
- Değişiklik açıklamasında etkilenen akışı ve uygulanan doğrulamayı belirtin.

### Mevcut belgeler

- [Geliştirme kurulumu](docs/DEV_SETUP.md)
- [Proje tanımı](backend/docs/PROJECT.md)
- [Mimari](backend/docs/ARCHITECTURE.md)
- [Uygulama sistemi](backend/docs/APPS.md)
- [Güvenlik belgesi](backend/docs/SECURITY.md)
- [Önceki güvenlik incelemesi](backend/docs/guvenlik-taramasi/security_audit.md)
- [Eski yol haritası](backend/docs/ROADMAP.md)
- [Görev notları](backend/docs/TODO.md)

Bu bağlantılar README proje köküne yerleştirildiğinde çalışır. Belgelerin bir kısmı eski plan ve örnekler içerir; teknik ayrıntılarda mevcut kodla karşılaştırılmalıdır.

### Lisans

İncelenen arşivin proje kökünde projeye ait bir `LICENSE` dosyası bulunmamıştır. Proje için açık kaynak lisansı varsayılmamalıdır. Kullanım ve dağıtım koşulları proje sahibi tarafından belirlenmelidir. Üçüncü taraf paketlerin lisansları ayrıca geçerlidir.
