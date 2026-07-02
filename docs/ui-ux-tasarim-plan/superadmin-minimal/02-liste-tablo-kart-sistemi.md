# Liste, Tablo ve Kart Minimal Mobil Planı

## Hedef

Super admin içindeki veri yoğun sayfalar mobilde tablo gibi değil, taranabilir yönetim kartları gibi okunacak. Her kartta en önemli bilgi üstte, durum ortada, aksiyonlar altta yer alacak.

## Ortak Mobil Kart Standardı

Her tablo kart dönüşümünde:

- Desktop `thead` mobilde gizlenir.
- Her `td` için `data-label` kullanılır.
- `tr` mobilde tek kart olur.
- Kart padding: 14px.
- Kart radius: 12-16px.
- Gölge azaltılır, border korunur.
- Son hücre aksiyon alanı olur.

## Kart Bilgi Hiyerarşisi

1. Birincil başlık
   - Kullanıcı adı, departman adı, etkinlik/proje başlığı, log işlemi.

2. İkincil bilgi
   - E-posta, açıklama, tarih, kategori.

3. Durum
   - Badge/pill korunur ama taşma engellenir.

4. Aksiyon
   - Düzenle/Sil gibi ikili aksiyonlar iki kolon veya alt alta.

## Sayfa Grupları

### Yönetim Listeleri

- `backend/views/superadmin/departments/index.php`
- `backend/views/superadmin/users/index.php`

Plan:
- Kart başlığı net olmalı.
- Uzun e-posta/departman listeleri kırılmalı.
- Silme butonu görsel olarak ikincil kalmalı.

### Web Sitesi İçerik Listeleri

- `site_events/index.php`
- `site_projects/index.php`
- `site_history/index.php`
- `site_gallery/index.php`

Plan:
- Görsel varsa kartın üst/sol alanında sade boyut.
- Açıklamalar maksimum 2-3 satır hissinde.
- Linkler tam görünmeli, taşmamalı.
- Hover ile görünen aksiyonlar mobilde daima erişilebilir olmalı.

### Sistem ve Başvuru Listeleri

- `applications/index.php`
- `logs/index.php`

Plan:
- Başvurular split-pane yapısını mobilde üst liste + alt detay yapısına indirir.
- Loglar mobilde detaylı kart olur.
- JSON/detay içerikleri monospace blok gibi değil, okunabilir key-value listesi gibi görünür.

## Kabul Kriterleri

- Hiçbir tablo 360px ekranda body yatay scroll üretmez.
- Kartta aksiyonlar görünür ve minimum 44px hedefe sahiptir.
- Uzun metinler `overflow-wrap: anywhere` ile kırılır.
- Empty state kart yapısını bozmaz.
