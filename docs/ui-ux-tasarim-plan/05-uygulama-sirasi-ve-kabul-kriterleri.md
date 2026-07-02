# Uygulama Sırası ve Kabul Kriterleri

## Önerilen Sıra

1. React admin ortak shell
   - `AdminLayout.jsx`
   - `frontend/src/admin/styles.css`

2. React admin kritik liste/form sayfaları
   - üyeler, kullanıcılar, departmanlar
   - formlar, submissions, form builder
   - finans, aidat, loglar

3. PHP layout temel mobil altyapısı
   - `backend/views/layouts/superadmin.php`
   - `backend/views/layouts/panel.php`

4. PHP superadmin view dosyaları
   - users, departments, site events, projects, gallery, history, settings, logs

5. PHP mini uygulama modülleri
   - önce tablo ve modal yoğun modüller
   - sonra kart/grid yoğun modüller

## Test Matriksi

- 360x800 mobil
- 390x844 mobil
- 430x932 mobil
- 768x1024 tablet
- 1366x768 masaüstü regresyon kontrolü

## Genel Kabul Kriterleri

- Admin ve super admin sayfalarında yatay body scroll oluşmaz.
- Sidebar mobilde içerik üstüne kontrollü drawer olarak açılır.
- Tüm modal ve formlar mobilde kapatılabilir, kaydırılabilir ve gönderilebilir.
- Tablolar veri kaybetmeden okunur.
- Aksiyon butonları görünür ve dokunulabilir kalır.
- Public site kodlarına dokunulmaz.

## Uygulama Notu

Uygulama aşamasında önce ortak CSS ve layout davranışları düzeltilmeli. Sonra sayfa özelindeki grid, tablo, modal ve toolbar problemleri tek tek ele alınmalı. Böylece aynı mobil kuralı hem React admin hem PHP panel tarafında tekrar tekrar elle çözmek yerine ortak davranışlarla ilerlenir.
