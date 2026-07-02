# React Admin Shell Mobil Planı

## İlgili Dosyalar

- `frontend/src/admin/components/AdminLayout.jsx`
- `frontend/src/admin/styles.css`
- `frontend/src/admin/AdminApp.jsx`

## Mevcut Durum

React admin tarafında ortak layout, sabit sidebar ve sticky topbar üzerine kurulu. CSS içinde `max-width: 1024px` ve `max-width: 640px` civarında mobil kurallar var; fakat yoğun sayfalarda topbar, içerik paddingleri, sidebar drawer, modal ve tablo davranışlarının aynı sistemle standardize edilmesi gerekiyor.

## Mobilde Yapılacaklar

1. Sidebar drawer davranışı netleştirilecek.
   - Mobilde sidebar tam yükseklikte overlay olarak açılacak.
   - Overlay butonu tüm ekranı kapatacak ve içerik scrollunu kilitleme ihtiyacı değerlendirilecek.
   - Sidebar içinde kullanıcı footer alanı küçük ekranlarda erişilebilir kalacak.

2. Topbar mobil sıkışması giderilecek.
   - Başlık ve subtitle uzun olduğunda ellipsis veya iki satır sınırı kullanılacak.
   - Role badge küçük ekranlarda satır kırmadan sağda sıkışmayacak; gerekirse kompakt hale gelecek.
   - Menü butonu 44px dokunma hedefini koruyacak.

3. İçerik paddingleri mobilde azaltılacak.
   - `.admin-content` mobilde 28px yerine daha sıkı ama okunur değerlere inecek.
   - Dashboard hero, page heading ve panel header alanları küçük ekranlarda dikey hizaya geçecek.

4. Modal standardı güncellenecek.
   - `.admin-modal` mobilde `width: calc(100vw - 24px)` ve `max-height: calc(100dvh - 24px)` davranışına alınacak.
   - Header ve action alanları küçük ekranlarda saracak.
   - Form gridleri tek kolona düşecek.

5. Ortak tablo davranışı korunacak.
   - React admin tablolarında kart dönüşümü olan alanlar korunacak.
   - Kart dönüşümü olmayan tablo/listelerde kontrollü `overflow-x: auto` ve `min-width` stratejisi belirlenecek.

## Kabul Kriterleri

- 360px genişlikte admin shell yatay scroll üretmez.
- Sidebar aç/kapa, topbar ve içerik alanları üst üste binmez.
- Modal içeriği ekran dışına taşsa bile modal içinde scroll edilir.
- Masaüstü görünüm aynı kalır; değişiklikler mobil/tablet kırılımlarına sınırlı kalır.
