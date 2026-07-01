# ROADMAP.md

## Geliştirme Yol Haritası

---

## Faz 0 — Altyapı
**Hedef:** Projeyi ayağa kaldır, router + DB + auth iskeleti çalışsın.
**Tahmini Süre:** 1–2 gün

### Çıktılar
- Klasör yapısı kuruldu
- `.htaccess` + front controller çalışıyor
- PDO bağlantısı aktif
- Veritabanı şeması oluşturuldu
- Seed: ilk super_admin

### Tamamlanma Kriteri
`/login` sayfası açılıyor, POST ile auth çalışıyor.

---

## Faz 1 — Authentication & Yönlendirme
**Hedef:** Login sistemi + rol bazlı yönlendirme tam çalışsın.
**Tahmini Süre:** 1–2 gün

### Çıktılar
- Login sayfası UI
- Session yönetimi (`core/Auth.php`)
- Yönlendirme mantığı (SA / 1 dept / 2+ dept / 0 dept)
- Departman seçim ekranı
- Logout
- Guard sistemi (her sayfada yetki kontrolü)

### Tamamlanma Kriteri
3 farklı kullanıcı tipi giriş yapıyor, doğru sayfaya yönlendiriliyor.

---

## Faz 2 — Süper Admin Paneli
**Hedef:** SA tüm sistemi yönetebilsin.
**Tahmini Süre:** 3–5 gün

### Çıktılar
- SA layout (sidebar + header)
- SA dashboard (istatistik kartları)
- Departman CRUD (oluştur, listele, düzenle, sil, aktif/pasif)
- Kullanıcı CRUD
- Kullanıcı → departman atama (rol seç)
- App kayıt sistemi (hangi dept, hangi modül tipi)

### Tamamlanma Kriteri
SA tüm CRUD'ları yapabiliyor. Yeni departman + kullanıcı + app oluşturulabiliyor.

---

## Faz 3 — Departman Paneli
**Hedef:** Kullanıcılar kendi departman panelinde çalışabilsin.
**Tahmini Süre:** 2–3 gün

### Çıktılar
- Panel layout
- Departman dashboard (app kartları)
- Director vs Member görünüm farkı
- Yetki guard (doğru departmana atanmış mı?)
- `director_only` app gizleme

### Tamamlanma Kriteri
Director ve member farklı görünümde paneli açıyor, app'leri görüyor.

---

## Faz 4 — App Modülleri
**Hedef:** İlk mini uygulamalar çalışsın.
**Tahmini Süre:** 4–7 gün (modül başına 1–2 gün)

### Çıktılar
- `BaseModule` abstract sınıf
- `AppRunnerController` — modül yükleyici
- `FormModule` — özelleştirilebilir form + veri kaydetme
- `TrackerModule` — kayıt takip + durum yönetimi
- `ReportModule` — filtrelenebilir tablo görünümü
- `AnnouncementModule` — duyuru yayınlama

### Tamamlanma Kriteri
4 modül tip tam çalışıyor. SA app ekleyebiliyor, kullanıcı çalıştırabiliyor.

---

## Faz 5 — Polish & Stabilizasyon
**Hedef:** Sistem üretim kalitesine ulaşsın.
**Tahmini Süre:** 2–3 gün

### Çıktılar
- Flash mesaj sistemi
- Form validasyon mesajları
- Boş durum UI'ları
- Responsive kontrol
- Güvenlik checklist tamamlama
- Temel hata sayfaları (403, 404, 500)

### Tamamlanma Kriteri
Hiç veri olmayan durumdan tam dolu duruma kadar sistem tutarlı çalışıyor.

---

## Faz 6 — Departmanlar Arası Veri (v2)
**Hedef:** İzole departmanlar arasında kontrollü veri paylaşımı.
**Tahmini Süre:** TBD (paylaşım modeli netleştikten sonra)

### Ön Koşul
Hangi verinin paylaşılacağı kararlaştırılmalı (bkz. NOTES.md → Q4).

### Olası Yaklaşımlar
- Paylaşılan veri tabloları (`shared_*`)
- Cross-departman okuma izinleri
- Dashboard widget'ları (başka dept'dan özet veri)

---

## Milestone Özeti

| Milestone | Hedef | Durum |
|-----------|-------|-------|
| M0 — Altyapı | Login çalışıyor | ⏳ Bekliyor |
| M1 — Auth | Rol yönlendirme çalışıyor | ⏳ Bekliyor |
| M2 — Super Admin | SA tüm CRUD yapabiliyor | ⏳ Bekliyor |
| M3 — Panel | Dept paneli açılıyor | ⏳ Bekliyor |
| M4 — Apps | 4 modül çalışıyor | ⏳ Bekliyor |
| M5 — v1 Release | Üretim kalitesi | ⏳ Bekliyor |
| M6 — v2 | Cross-dept veri | ⏳ Planlanıyor |

---

## Kısıtlar & Riskler

| Risk | Olasılık | Etki | Önlem |
|------|----------|------|-------|
| Modül sistemi esnek olmayabilir | Orta | Yüksek | BaseModule iyi tasarla, v1'de 2 modülle test et |
| Cross-dept veri mimarisini sonradan eklemek izolasyonu bozabilir | Düşük | Yüksek | v1 izolasyonu kesin tut, v2'de köprü ekle |
| Slug değişikliği URL kırılması | Düşük | Orta | Slug'u salt-okunur yap |
| Büyük org'da performans | Çok Düşük | Orta | Index'leri doğru kur |
