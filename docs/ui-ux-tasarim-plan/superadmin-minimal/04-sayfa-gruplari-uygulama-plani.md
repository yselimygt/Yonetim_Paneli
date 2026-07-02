# Super Admin Minimal Mobil Sayfa Grupları

## Faz 1: Ortak Kabuk

Dosya:

- `backend/views/layouts/superadmin.php`

Yapılacaklar:

- Topbar sadeleştirme.
- Drawer davranışı kontrolü.
- Global mobil card/form/modal tokenları.
- Page header ve button standardı.

Başarı ölçütü:

- Tüm sayfalarda temel mobil kabuk aynı davranır.

## Faz 2: Kritik Yönetim Sayfaları

Dosyalar:

- `backend/views/superadmin/dashboard.php`
- `backend/views/superadmin/departments/index.php`
- `backend/views/superadmin/departments/edit.php`
- `backend/views/superadmin/users/index.php`
- `backend/views/superadmin/users/edit.php`

Yapılacaklar:

- Dashboard panelleri sade tek kolon.
- Users/departments kart listeleri daha minimalist.
- Edit sayfalarında form grupları sade kart satırları.

## Faz 3: Kariyer ve İçerik Yönetimi

Dosyalar:

- `backend/views/superadmin/applications/index.php`
- `backend/views/superadmin/site_events/index.php`
- `backend/views/superadmin/site_projects/index.php`
- `backend/views/superadmin/site_gallery/index.php`
- `backend/views/superadmin/site_team/index.php`
- `backend/views/superadmin/site_history/index.php`

Yapılacaklar:

- Başvurular: liste + detay akışı daha sade.
- Etkinlik/proje/tarihçe: kart yoğunluğu azaltılır.
- Galeri: mobilde aksiyonlar görünür ama görseli boğmaz.
- Ekip: profil modalı mobilde daha akıcı.

## Faz 4: Ayarlar ve Sistem

Dosyalar:

- `backend/views/superadmin/site_settings/index.php`
- `backend/views/superadmin/apps/index.php`
- `backend/views/superadmin/apps/edit.php`
- `backend/views/superadmin/logs/index.php`

Yapılacaklar:

- Site settings sekmeleri sade yatay chip sistemi gibi davranır.
- Uygulama ata modül kartları daha kompakt.
- Logs kartlarında detay alanı sade key-value görünümü.

## Uygulama Sırası

1. `superadmin.php`
2. `dashboard.php`
3. `departments/*`
4. `users/*`
5. `applications/index.php`
6. `site_events/index.php`
7. `site_projects/index.php`
8. `site_gallery/index.php`
9. `site_team/index.php`
10. `site_history/index.php`
11. `site_settings/index.php`
12. `apps/*`
13. `logs/index.php`

## Not

Her dosya sonrası PHP lint çalıştırılacak. Büyük toplu refactor yerine sayfa sayfa ilerlemek tercih edilecek.
