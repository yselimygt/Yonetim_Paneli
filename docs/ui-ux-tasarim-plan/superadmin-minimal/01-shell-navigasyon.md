# Shell ve Navigasyon Minimal Mobil Planı

## İlgili Dosya

- `backend/views/layouts/superadmin.php`

## Hedef

Super admin mobil shell, küçük ekranda sade bir uygulama kabuğu gibi davranacak. İçerik alanı öncelikli olacak, menü yalnızca ihtiyaç halinde açılacak.

## Plan

1. Topbar sadeleştirme
   - Mobilde topbar yüksekliği 56px civarında tutulur.
   - Sol: menü butonu.
   - Orta: kısa sayfa başlığı.
   - Sağ: tema butonu.
   - Breadcrumb mobilde gizli kalır.

2. Sidebar drawer
   - Sidebar mobilde tam yükseklikli drawer olarak kalır.
   - Genişlik `min(84vw, 300px)` sınırında tutulur.
   - Overlay koyu ama sade olur.
   - Menü açıldığında body scroll kilitlenir.

3. Menü öğeleri
   - Mobilde nav link yüksekliği en az 44px olur.
   - Section başlıkları daha küçük ve daha az boşluklu olur.
   - Aktif menü vurgusu sade border veya yüzey rengi ile yapılır.

4. Page content
   - Mobil padding: 14-16px.
   - Sayfa başlıkları 18-22px bandında kalır.
   - Page header mobilde dikey hizalanır.

5. Global component sadeleştirme
   - `.card` mobilde bazı sayfalarda çerçevesiz wrapper olabilir.
   - `.card-body` mobilde 16px padding kullanır.
   - `.btn` mobilde minimum 44px yüksekliğe çıkar.

## Uygulama Notları

- Önce layout seviyesinde ortak mobil tokenlar eklenmeli.
- Sayfa özel CSS tekrarları daha sonra azaltılmalı.
- Masaüstü CSS davranışı bozulmamalı.

## Kabul Kriterleri

- 360px genişlikte body yatay scroll oluşturmaz.
- Menü açılıp kapanabilir ve overlay ile kapanır.
- Topbar başlığı uzun olsa bile taşmaz.
- Sayfa içerikleri menü altında kalmaz.
