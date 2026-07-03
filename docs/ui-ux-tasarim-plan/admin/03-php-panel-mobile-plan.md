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

6. Yönetim sayfaları
   - `management/announce.php`, `management/directory.php`, `management/reports.php` mobilde tek kolon ve kart liste düzenine alınacak.
   - Durum: küresel duyuru sayfası mobil tek kolon form ve kompakt duyuru kartlarıyla düzenlendi; kurumsal rehber mobil arama ve üye kart listesine dönüştürüldü; rapor izleme departman ve üye kart listesi olarak düzenlendi.

7. Modül sayfaları
   - Her modülde tablo/kart, modal, dosya yükleme ve aksiyon alanları tek tek kontrol edilecek.
   - Durum: kurumsal rehber kartları sadeleştirildi, detay bilgileri popup'a taşındı; dosya yönetimi mobil araç çubuğu, iki kolon dosya kartları, bottom-sheet modal ve yükleme paneli düzeniyle optimize edildi; etkinlik yönetimi iki kolon minimalist kart ve mobil bottom-sheet form düzenine alındı; fikir havuzu kartları, yorum alanı ve popup formları mobil için sıkıştırıldı; envanter sayfası iki kolon kart, kompakt filtre ve mobil form paneline alındı; bağlantılar yönetimi mobil tek kolon form ve kompakt link kartlarıyla düzenlendi; medya yöneticisi yatay klasör şeridi, iki kolon görsel grid ve kompakt yükleme alanıyla düzenlendi; toplantı modülü yatay toplantı geçmişi, tek kolon detay ve kompakt merkez popup ile düzenlendi; üyeler modülündeki son düzenlemeler geri alındı ve daha sonra tekrar ele alınacak; mentorluk modülü yatay eşleşme şeridi, tek kolon gelişim alanı ve merkez popup düzeniyle mobil uyumlu hale getirildi; anlaşmalı mekanlar modülü iki kolon kompakt kart, sade detay görünümü ve mobil popup form düzeniyle optimize edildi; şifre kasası modülündeki son düzenlemeler geri alındı ve daha sonra tekrar ele alınacak; QR yönetimi modülü kompakt form, küçük önizleme ve iki kolon geçmiş QR kartlarıyla mobil uyumlu hale getirildi.

## Kabul Kriterleri

- 360px genişlikte yatay taşma olmayacak.
- Departman değiştirme tek elle topbar’dan erişilebilir olacak.
- Seçim modalı ekranı taşırsa kendi içinde scroll edecek.
- Masaüstü davranışı korunacak.
- Her dosya sonrası PHP lint çalıştırılacak.
