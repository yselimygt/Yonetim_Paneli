# Super Admin Minimal Mobil Kabul Kriterleri ve Test Planı

## Test Ekranları

- 360 x 800
- 390 x 844
- 430 x 932
- 768 x 1024
- 1366 x 768 masaüstü regresyon

## Genel Kabul Kriterleri

1. Yatay taşma yok
   - `body` seviyesinde yatay scroll oluşmaz.
   - Kartlar, tablolar, modal ve form alanları ekran dışına taşmaz.

2. Tek elle kullanım
   - Ana aksiyonlar tam genişlik veya kolay dokunulur.
   - Buton yüksekliği minimum 44px.
   - Drawer, modal ve tab geçişleri tek elle kullanılabilir.

3. Minimal görsel yoğunluk
   - Mobilde aşırı gölge ve nested card hissi azaltılmış olur.
   - Sayfa başı açıklamalar kısa ve kırılabilir olur.
   - Liste kartları tek bakışta taranabilir olur.

4. Form güvenliği
   - Kaydet, iptal, sil aksiyonları açık ayrılır.
   - Modal footer görünür ve erişilebilir olur.
   - Uzun URL, JSON ve açıklama alanları taşmaz.

5. Masaüstü korunur
   - Desktop layout aynı bilgi mimarisiyle kalır.
   - Desktop tablo ve grid davranışları mobil değişikliklerden etkilenmez.

## Dosya Bazlı Kontrol

- `backend/views/layouts/superadmin.php`
  - Drawer açılır/kapanır.
  - Topbar taşmaz.
  - Page content paddingleri dengeli.

- `backend/views/superadmin/*`
  - PHP lint temiz.
  - Mobil kart/table dönüşümü varsa `data-label` eksiksiz.
  - Modal ve save bar butonları tam genişlik.

## Teknik Kontroller

Komut:

```bash
C:\xampp\php\php.exe -l backend\views\layouts\superadmin.php
C:\xampp\php\php.exe -l backend\views\superadmin\users\index.php
```

Tüm superadmin view dosyaları için lint çalıştırılmalı.

## Görsel Kontrol Checklist

- 360px ekranda menü açılınca içerik arkada kaymaz.
- Kart başlıkları tek satıra zorlanmaz.
- Sil/Düzenle butonları birbirine yapışmaz.
- Empty state ekranları kart gridini bozmaz.
- Modal açıldığında kapatma butonu görünür.
- Klavye açıldığında form gönderme butonu erişilebilir kalır.
