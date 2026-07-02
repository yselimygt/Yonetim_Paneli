# PHP Mini Uygulama Modülleri Mobil Planı

## İlgili Alan

- `backend/views/modules`
- `backend/modules`
- `backend/views/panel/app_wrapper.php`
- `backend/views/panel/app_public_wrapper.php`

## Öncelikli Modül Grupları

### Veri Tablosu Yoğun Modüller

- `members.php`
- `form.php`
- `shortener_admin.php`
- `certificate.php`
- `directory.php`
- `report.php`
- `budget.php`
- `inventory.php`

Mobil plan:
- Tablolar kart görünümüne veya kontrollü yatay scroll'a alınacak.
- Aksiyon butonları satır sonunda sıkışmayacak; kart altında gruplandırılacak.
- Uzun e-posta, link, sertifika no ve dosya isimleri kırılabilir hale gelecek.

### Modal ve Form Yoğun Modüller

- `agile.php`
- `meeting.php`
- `social.php`
- `mentorship.php`
- `partner.php`
- `trophy.php`
- `wiki.php`
- `sponsor.php`
- `passwords.php`
- `_alumni_modals.php`

Mobil plan:
- Inline modal stilleri ortak mobil kurallarla uyumlu hale getirilecek.
- Modal içerikleri `max-height` ve iç scroll destekleyecek.
- Form gridleri tek kolona düşecek.
- Footer aksiyonları mobilde tam genişlik veya sarılabilir olacak.

### Kart/Grid Yoğun Modüller

- `drive_admin.php`
- `media_admin.php`
- `bootcamp.php`
- `event.php`
- `applications.php`
- `announcement.php`
- `ideahub.php`
- `links_admin.php`
- `alumni_admin.php`

Mobil plan:
- Kart gridleri `minmax` yerine 1 kolon davranışına alınacak.
- Toolbar ve filtreler dikey akacak.
- Dosya/list item metinleri taşmadan kırılacak.
- Çoklu seçim veya bulk action bar mobilde sabit alt bar gibi davranmayacaksa dikey toolbar'a alınacak.

## Public Wrapper Notu

`app_public_wrapper.php` sadece admin tarafındaki modüllerin public paylaşım çıktısı için değerlendirilir. Public web sitesi kapsam dışıdır; burada yapılacak dokunuşlar sadece admin modüllerinin kamuya açık form çıktısı mobilde bozuluyorsa ele alınacaktır.

## Kabul Kriterleri

- Her öncelikli modül 360px genişlikte temel işlem akışını tamamlatır.
- Modal açılan modüllerde içerik ekran dışına taşmaz.
- Tablo/kart dönüşümlerinde veri başlıkları kaybolmaz.
- Inline stiller mümkün olduğunca mevcut görsel dille uyumlu mobil override'lara taşınır.
