# NOTES.md

## Karar Günlüğü

Projeyle ilgili alınan kararlar, nedenleri ve alternatiflerin neden reddedildiği.

---

### [KARAR-001] Kullanıcı-Departman: Pivot Tablo

**Karar:** `users` tablosuna `department_id` koymak yerine `user_departments` pivot tablosu.

**Neden:**
Bir kullanıcı birden fazla departmanda olabilir ve her departmanda farklı rol taşıyabilir.
`users.department_id` bu ilişkiyi temsil edemez; ya çoklu kayıt ya pivot.

**Reddedilen Alternatif:**
`users.department_id` — sadece tek departman destekler, kısıtlayıcı.

---

### [KARAR-002] Framework Kullanmama

**Karar:** Laravel veya başka bir PHP framework'ü kullanmadan, custom lightweight MVC.

**Neden:**
- Öğrenme/kontrol: tüm katmanlar şeffaf
- Hafif deployment (Composer bağımlılığı yok)
- ViperaDev projeleri tutarlılığı

**Trade-off:**
Auth, routing, ORM gibi şeyler sıfırdan yazılıyor.
Eğer proje büyürse (API, event system, queue) framework'e geçiş maliyeti artar.

**Not:** Proje ileride Laravel'e taşınacaksa, controller/model yapısı benzer tutulmalı.

---

### [KARAR-003] Departman Seçim Ekranı

**Karar:** 2+ departmana atanmış kullanıcı için `/panel` ara seçim ekranı.

**Neden:**
Otomatik yönlendirme imkânsız — sistem hangi departmanı seçeceğini bilemez.

**Alternatif Değerlendirme:**
"Son kullanılan departman" cookie'si — eklenebilir ama v1'de fazla karmaşık.

---

### [KARAR-004] Departmanlar Arası Veri Paylaşımı → v2

**Karar:** v1'de departmanlar tamamen izole. Paylaşım v2'ye ertelendi.

**Neden:**
- Paylaşım modeli henüz netleşmedi (hangi veri, kim erişir, nasıl)
- v1'deki izolasyon mimarisini kirletmemek için
- Önce sağlam temel kur, sonra köprü ekle

**Not:** v2 tasarımında "shared data" tabloları veya event-bus mantığı değerlendirilecek.

---

### [KARAR-005] `director_only` Flag

**Karar:** `apps` tablosuna `director_only TINYINT(1)` eklendi.

**Neden:**
Bazı uygulamalar (raporlar, üye yönetimi) sadece directora görünmeli.
Ayrı bir permission tablosu v1 için fazla karmaşık.

**Trade-off:**
Granüler izin sistemi yok. Eğer ileride "bazı app'ler hem director hem seçili member'lar" ihtiyacı çıkarsa bu flag yetmez — `app_permissions` tablosu gerekir.

---

### [KARAR-006] Config JSON Kolonu

**Karar:** `apps.config JSON` — app modülüne özel ayarlar bu kolonda tutulur.

**Neden:**
Her modül farklı ayar gereksinimleri var (FormModule için alan listesi, TrackerModule için durum listesi vb.).
Bunlar için ayrı tablo açmak v1'de overkill.

**Risk:**
JSON içeriği validate edilmeli; modül yüklenirken eksik config beklenebilir.

---

## Açık Sorular (Henüz Karara Bağlanmadı)

| # | Soru | Son Tarih |
|---|------|-----------|
| Q1 | Proje adı ne olacak? | — |
| Q2 | Hangi departmanlar v1'de olacak? | — |
| Q3 | Her departman için hangi app'ler başlangıçta eklenecek? | — |
| Q4 | Departmanlar arası paylaşım için hangi veri tipleri öncelikli? | v2 başlamadan |
| Q5 | "Beni hatırla" özelliği gerekli mi? | Phase 1 bitmeden |

---

## Dikkat Edilecekler

1. **Slug çakışması:** Departman adı değişse bile slug değişmemeli — URL'ler bozulur.
   Slug bir kez set edilir, sonra düzenlenemez. (Ya da yönlendirme + eski slug kaydı gerekir.)

2. **Cascade delete:** Departman silindiğinde → `user_departments` ve `apps` cascade ile silinir.
   Bu veri kaybıdır. SA'ya silmeden önce uyarı göster.

3. **Super admin departmana atanamaz:** `is_super_admin = 1` olan kullanıcılar için
   `user_departments` kaydı gereksiz — SA zaten her şeyi görür.

4. **Session güvenliği:** Login sonrası `session_regenerate_id(true)` unutulursa
   session fixation açığı oluşur.

5. **`exit` sonrası redirect:** `header('Location: ...')` sonrası `exit` yazılmazsa
   PHP çalışmaya devam eder — güvenlik açığı.
