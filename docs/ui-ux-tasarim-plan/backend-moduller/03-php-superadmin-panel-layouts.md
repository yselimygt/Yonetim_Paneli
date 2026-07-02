# PHP Super Admin ve Panel Layout Mobil Planı

## İlgili Dosyalar

- `backend/views/layouts/superadmin.php`
- `backend/views/layouts/panel.php`
- `backend/views/superadmin/*`
- `backend/views/panel/*`

## Mevcut Durum

PHP layoutlarında sabit sidebar, sticky topbar, tablo, kart, modal ve form stilleri inline/global CSS içinde tanımlı. React admin tarafındaki mobil sistemden ayrı çalıştığı için mobilde benzer davranışların burada da ortaklaştırılması gerekiyor.

## Mobilde Yapılacaklar

1. PHP layout sidebar mobil drawer olacak.
   - `superadmin.php` ve `panel.php` içinde sidebar mobilde ekran dışına taşınacak.
   - Topbar'a mobil menü butonu eklenecek veya mevcut yapı varsa görünür hale getirilecek.
   - Overlay ile kapanma davranışı sağlanacak.

2. Main ve page-content mobil uyumlu olacak.
   - `.main` mobilde `margin-left: 0` olacak.
   - `.page-content` paddingleri küçük ekranlara göre azaltılacak.
   - Topbar başlığı uzun olduğunda taşmayacak.

3. Tablo sarmalayıcı standardı eklenecek.
   - Superadmin dashboard, users, departments, logs, site events/projects/history gibi tablolarda `overflow-x: auto` veya mobil kart standardı uygulanacak.
   - `min-width: 800px` gibi sabit tablo davranışları mobilde kontrollü scroll içine alınacak.

4. Form ve modal standardı güncellenecek.
   - `.form-row` mobilde tek kolon olacak.
   - `.modal-content` mobilde `width: calc(100vw - 24px)` ve yüksekliği ekranla sınırlı olacak.
   - Modal footer butonları küçük ekranda saracak veya tam genişlik olacak.

5. Eski superadmin view dosyaları düzenlenecek.
   - `backend/views/superadmin/users/index.php`
   - `backend/views/superadmin/departments/index.php`
   - `backend/views/superadmin/site_events/index.php`
   - `backend/views/superadmin/site_projects/index.php`
   - `backend/views/superadmin/site_gallery/index.php`
   - `backend/views/superadmin/site_history/index.php`
   - `backend/views/superadmin/site_settings/index.php`
   - `backend/views/superadmin/logs/index.php`

## Kabul Kriterleri

- PHP superadmin ve panel layoutları 360px genişlikte sidebar sebebiyle içerik taşırmaz.
- Tablolar mobilde okunabilir şekilde kaydırılır veya kartlaşır.
- Modal formlar ekran dışına taşmadan kullanılabilir.
- Masaüstü layout değişmeden kalır.
