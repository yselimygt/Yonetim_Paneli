# PHP Admin Panel Mobil Planı

## Kapsam

İlgili dosyalar:

- `backend/views/layouts/panel.php`
- `backend/views/panel/select.php`
- `backend/views/panel/dashboard.php`
- `backend/views/panel/forms.php`
- `backend/views/panel/finance/index.php`
- `backend/views/panel/dues/index.php`
- `backend/views/panel/management/*.php`
- `backend/views/modules/*.php`

## Uygulama Sırası

1. Departman değiştirme alanı
   - Mobil topbar içinde mevcut departmanı gösteren kompakt buton.
   - Butona basınca departman seçim modalı.
   - Aktif departman seçili etiketle görünsün.
   - Durum: uygulandı.

2. Panel ortak shell
   - Mobil sidebar/drawer davranışı.
   - Topbar sıkışma ve başlık taşma kontrolü.
   - Bottom kullanıcı/çıkış alanı standardı.
   - Durum: mobil drawer, topbar erişimi ve bottom hesap menüsü uygulandı.

3. Dashboard
   - Uygulama kartları iki kolon/tek kolon kırılımı.
   - Duyuru, takvim ve istatistik alanları kompakt düzen.
   - Durum: mobil istatistik, duyuru/takvim ve hızlı erişim kırılımları uygulandı.

4. Global form yönetimi
   - Sekmeler ve form kartları mobilde sadeleşecek.
   - Form oluşturma alanı popup veya alt panel davranışı alacak.
   - Durum: mobil sekme, kart matrisi ve oluşturma alt paneli uygulandı.

5. Finans ve aidat
   - Özet kartları kompakt matris.
   - İşlem tabloları mobil kartlara dönüşecek.
   - Modal/form aksiyonları yan yana veya tam genişlik standardına alınacak.
   - Durum: finans ve aidat sayfalarında mobil özet matrisi, işlem/ödeme kartları ve bottom-sheet modal düzeni uygulandı.

6. Modül sayfaları
   - Her modülde tablo/kart, modal, dosya yükleme ve aksiyon alanları tek tek kontrol edilecek.

## Kabul Kriterleri

- 360px genişlikte yatay taşma olmayacak.
- Departman değiştirme tek elle topbar’dan erişilebilir olacak.
- Seçim modalı ekranı taşırsa kendi içinde scroll edecek.
- Masaüstü davranışı korunacak.
- Her dosya sonrası PHP lint çalıştırılacak.
