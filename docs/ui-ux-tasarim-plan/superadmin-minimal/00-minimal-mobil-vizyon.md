# Super Admin Minimal Mobil Vizyonu

## Amaç

Super admin paneli mobilde yalnızca sığan bir arayüz olmayacak. Küçük ekranda hızlı karar verme, tek elle kullanım, az dikkat dağıtıcı yüzey ve net aksiyon önceliği hedeflenecek.

Bu faz yalnızca `backend/views/layouts/superadmin.php` ve `backend/views/superadmin` kapsamındadır. Public site ve departman paneli bu faza dahil değildir.

## Minimal Tasarım İlkeleri

1. Tek ana aksiyon
   - Her sayfanın mobil üst alanında en fazla bir ana buton öne çıkar.
   - İkincil aksiyonlar kart içinde veya modal içinde tutulur.

2. Düşük görsel yoğunluk
   - Mobilde ağır gölge, büyük boşluk ve çok katmanlı kart kullanımı azaltılır.
   - Kartlar sade border + yüzey rengi ile ayrılır.
   - Nested card görünümü kaldırılır veya düz bölüme çevrilir.

3. Liste öncelikli deneyim
   - Tablolar mobilde veri kartlarına dönüşür.
   - Kartlarda başlık, durum ve ana aksiyon ilk bakışta görünür.
   - Detay metinleri ikinci satırda, düşük vurgu ile yer alır.

4. Formlarda tek kolon
   - Tüm form alanları mobilde tek kolon olur.
   - Checkbox/toggle grupları kart benzeri sade satırlara dönüşür.
   - Kaydet/iptal butonları ekranın sonunda tam genişlikli olur.

5. Modal yerine alt akış hissi
   - Mevcut modal altyapısı korunur.
   - Mobilde modal tam genişliğe yakın, yüksekliği kontrollü ve içeriği kaydırılabilir olur.
   - Modal footer sabit veya görünür aksiyon alanı gibi davranır.

## Dokunmatik Standartlar

- Minimum hedef: 44px
- Birincil buton: tam genişlik
- İkili aksiyon: iki kolon veya alt alta
- Kritik silme aksiyonu: her zaman ikinci planda ama erişilebilir
- Kartlar arasında minimum 10-12px boşluk

## Görsel Dil

Masaüstü tasarım korunacak. Minimal mobil fazda yalnızca mobil kırılımda aşağıdaki sadeleştirmeler yapılacak:

- Daha az gölge
- Daha düz kart yüzeyleri
- Daha küçük padding
- Daha net metin kırılımı
- Daha az yatay hizalama, daha çok dikey akış
- Butonlarda tam genişlik ve açık aksiyon hiyerarşisi

## Kapsam Dışı

- Public web sitesi
- Departman admin paneli
- React admin sayfaları
- Backend iş mantığı
- Veritabanı ve route değişiklikleri
- Masaüstü tasarım revizyonu
