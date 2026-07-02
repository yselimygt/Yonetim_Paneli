# Admin ve Super Admin Mobil UI/UX Planı

Bu plan yalnızca yönetim arayüzlerini kapsar. Public web sitesi, public React sayfaları ve ziyaretçi tarafı bileşenler bu kapsamın dışındadır.

## Kapsam

- React tabanlı admin shell ve sayfalar: `frontend/src/admin`
- PHP tabanlı super admin layout ve view dosyaları: `backend/views/layouts/superadmin.php`, `backend/views/superadmin`
- PHP tabanlı departman admin panel layout ve view dosyaları: `backend/views/layouts/panel.php`, `backend/views/panel`
- Departman içi mini uygulama ekranları: `backend/views/modules`, `backend/modules`

## Tasarım Kuralı

Mevcut görsel dil korunacak. Renk paleti, kart dili, border radius, font ailesi, ikon dili ve marka hissi değiştirilmeyecek. Değişiklikler sadece mobil kırılımda okunabilirlik, dokunma hedefleri, yatay taşma, modal davranışı, tablo/kart dönüşümü ve navigasyon erişilebilirliği için yapılacak.

## Mobil Kırılım Standartları

- Ana hedef: `360px`, `390px`, `430px`, `768px`
- Minimum dokunma hedefi: 44px
- Sayfa yatay taşma hedefi: `body` ve ana shell seviyesinde `overflow-x` oluşmayacak
- Modal hedefi: mobilde tam genişliğe yakın, ekran yüksekliğine göre scroll edilebilir
- Tablolar: veri kaybı olmadan kart görünümü veya kontrollü yatay scroll
- Formlar: iki kolonlu alanlar mobilde tek kolon; aksiyon butonları alt alta veya tam genişlik

## Plan Dosyaları

- [01-react-admin-shell.md](./admin/01-react-admin-shell.md)
- [02-react-admin-pages.md](./admin/02-react-admin-pages.md)
- [03-php-superadmin-panel-layouts.md](./backend-moduller/03-php-superadmin-panel-layouts.md)
- [04-php-mini-uygulama-modulleri.md](./backend-moduller/04-php-mini-uygulama-modulleri.md)
- [05-uygulama-sirasi-ve-kabul-kriterleri.md](./05-uygulama-sirasi-ve-kabul-kriterleri.md)
