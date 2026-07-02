# Form, Modal ve Aksiyon Minimal Mobil Planı

## Hedef

Mobil super admin formları hızlı doldurulabilir, hatasız dokunulabilir ve net aksiyon hiyerarşisine sahip olmalıdır.

## Form Standardı

1. Tek kolon
   - Tüm `.form-row` yapıları mobilde `1fr`.
   - İki alan yan yana bırakılmaz.

2. Alan aralıkları
   - Form group aralığı 14-16px.
   - Uzun label metinleri kırılır.
   - Placeholder ve hint metinleri taşmaz.

3. Checkbox / toggle
   - Checkbox grupları mobilde bordered sade satır olur.
   - Toggle açıklamaları alt satıra kırılabilir.
   - Kritik yetki checkboxları renk korur ama kart yoğunluğu azaltılır.

4. JSON / uzun metin alanları
   - `textarea` min-height korunur.
   - Mobilde monospace alanlar yatay scroll üretmez.
   - Uzun URL ve JSON parçaları kırılabilir olur.

## Modal Standardı

Mobil modal:

- `width: calc(100vw - 24px)`
- `max-height: calc(100dvh - 24px)`
- İçerik scroll edilebilir.
- Header sade ve kısa.
- Close butonu minimum 40px.
- Footer butonları tam genişlik ve alt alta.

## Aksiyon Bar Standardı

Sayfa sonu aksiyonları:

- `.save-bar`, `.settings-save-bar`, modal action alanları mobilde dikey olur.
- Birincil aksiyon en altta veya en görünür yerde kalır.
- İptal/geri butonu ikinci sırada kalır.
- Silme aksiyonu birincil aksiyonla aynı görsel ağırlığa sahip olmaz.

## Sayfa Örnekleri

- `users/edit.php`: rol/yetki checkboxları sade kart satırı.
- `departments/edit.php`: renk ve yönetim checkboxları tek kolon.
- `apps/edit.php`: JSON config alanı taşmadan.
- `site_settings/index.php`: dinamik itemlar tek kolon.
- `site_team/index.php`: profil modalı tam ekran hissinde.

## Kabul Kriterleri

- Mobilde hiçbir form alanı ekran dışına taşmaz.
- Her modal kapatılabilir, kaydırılabilir ve gönderilebilir.
- Footer butonları klavyeyle ve dokunmayla erişilebilir kalır.
- Input focus durumları korunur.
