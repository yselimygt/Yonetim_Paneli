# TODO.md

> Format: `[ ]` bekliyor · `[~]` devam ediyor · `[x]` tamamlandı
> Öncelik: 🔴 kritik · 🟠 yüksek · 🟡 orta · ⚪ düşük

---

## PHASE 0 — Proje Altyapısı

- [ ] 🔴 Klasör yapısını kur (ARCHITECTURE.md'ye uygun)
- [ ] 🔴 `.htaccess` yaz — mod_rewrite, front controller
- [ ] 🔴 `index.php` front controller
- [ ] 🔴 `config/database.php` — PDO bağlantısı
- [ ] 🔴 `config/app.php` — sabitler (BASE_URL, APP_NAME, vb.)
- [ ] 🔴 `core/Database.php` — PDO singleton wrapper
- [ ] 🔴 `core/Router.php` — basit URL router
- [ ] 🔴 `core/View.php` — view renderer
- [ ] 🔴 `core/Controller.php` — base controller
- [ ] 🔴 `core/Model.php` — base model (PDO helper)
- [ ] 🔴 Veritabanı şemasını oluştur (migration SQL dosyası)
- [ ] 🔴 Seed: ilk super_admin kullanıcısı

---

## PHASE 1 — Authentication

- [ ] 🔴 `views/auth/login.php` — login sayfası UI
- [ ] 🔴 `layouts/auth.php` — layout
- [ ] 🔴 `AuthController::showLogin()`
- [ ] 🔴 `AuthController::processLogin()` — doğrulama + session
- [ ] 🔴 `core/Auth.php` — session yönetimi, rol kontrolü
- [ ] 🔴 Session başlatma: user_id, is_super_admin, departments[]
- [ ] 🔴 Yönlendirme mantığı (super_admin / 1 dept / 2+ dept / 0 dept)
- [ ] 🔴 `AuthController::logout()`
- [ ] 🟠 "Atanmış departmanın yok" hata sayfası
- [ ] 🟡 "Beni hatırla" (7 günlük cookie)

---

## PHASE 2 — Super Admin Paneli

### Genel
- [ ] 🔴 `layouts/superadmin.php` — SA layout (sidebar + header)
- [ ] 🔴 SA dashboard — istatistik kartları (dept sayısı, kullanıcı, app)
- [ ] 🔴 `/superadmin` yetki guard'ı

### Departman Yönetimi
- [ ] 🔴 Departman listesi sayfası
- [ ] 🔴 Departman oluşturma formu + controller
- [ ] 🔴 Departman düzenleme
- [ ] 🔴 Departman silme (soft delete veya cascade)
- [ ] 🟠 Departman aktif/pasif toggle
- [ ] 🟡 Slug otomatik üretimi (name → slug)

### Kullanıcı Yönetimi
- [ ] 🔴 Kullanıcı listesi sayfası
- [ ] 🔴 Kullanıcı oluşturma formu + controller
- [ ] 🔴 Kullanıcı düzenleme (ad, email, şifre)
- [ ] 🔴 Kullanıcıyı departmana ata (rol seç)
- [ ] 🔴 Departman atamasını kaldır
- [ ] 🟠 Kullanıcı aktif/pasif toggle
- [ ] 🟡 Kullanıcının tüm departman atamalarını görüntüle

### Uygulama Yönetimi
- [ ] 🔴 App listesi (departmana göre filtreli)
- [ ] 🔴 App oluşturma (hangi dept, hangi modül tipi, director_only?)
- [ ] 🔴 App düzenleme
- [ ] 🔴 App silme
- [ ] 🟠 App sıralama (sort_order)
- [ ] 🟡 App aktif/pasif toggle

---

## PHASE 3 — Departman Paneli

- [ ] 🔴 `layouts/panel.php` — panel layout (sidebar + header)
- [ ] 🔴 `/panel` departman seçim ekranı
- [ ] 🔴 `/panel/{slug}` departman dashboard
- [ ] 🔴 Dashboard: departman app listesi (kart görünümü)
- [ ] 🔴 Yetki guard: doğru departmana mı atanmış?
- [ ] 🔴 Director görünümü vs Member görünümü farkı
- [ ] 🟠 Director: üye listesi görüntüleme
- [ ] 🟡 Aktif oturum bilgisi (hangi departmanda, hangi rolle)

---

## PHASE 4 — App Modülleri

### Altyapı
- [ ] 🔴 `modules/BaseModule.php` abstract sınıf
- [ ] 🔴 `AppRunnerController` — slug → modül sınıfı eşleştirme
- [ ] 🔴 `views/panel/apps/runner.php` — modül render wrapper

### Modüller
- [ ] 🔴 `FormModule` — özelleştirilebilir form + veri kaydetme
- [ ] 🔴 `TrackerModule` — kayıt takip sistemi (tablo + durum)
- [ ] 🟠 `ReportModule` — filtrelenebilir tablo + basit grafik
- [ ] 🟠 `AnnouncementModule` — duyuru yayınlama
- [ ] 🟡 `CalendarModule` — etkinlik takvimi

---

## PHASE 5 — Polishing

- [ ] 🟠 Form validasyon mesajları (hem server hem client)
- [ ] 🟠 Flash mesaj sistemi (başarı/hata bildirimleri)
- [ ] 🟠 Responsive tasarım kontrolleri
- [ ] 🟠 Boş durum UI'ları (henüz app yok, üye yok vb.)
- [ ] 🟡 Sayfalama (kullanıcı/app listeleri uzadığında)
- [ ] 🟡 Arama/filtreleme (SA listelerinde)

---

## PHASE 6 — Departmanlar Arası Veri (v2)

- [ ] ⚪ Paylaşım modeli kararlaştır
- [ ] ⚪ Paylaşılan veri tabloları
- [ ] ⚪ Erişim kuralları (hangi dept hangi veriye erişir)
- [ ] ⚪ Cross-dept rapor görünümleri

---

## Teknik Borç / Sonraya Bırakılan

- [ ] ⚪ Unit testler
- [ ] ⚪ API endpoint'leri
- [ ] ⚪ E-posta bildirimleri
- [ ] ⚪ Aktivite logu (kim ne yaptı)
- [ ] ⚪ Dışa aktarma (Excel/PDF)
