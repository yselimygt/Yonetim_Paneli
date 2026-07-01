# PROJECT.md

## Proje Adı
**[PROJECT_NAME]**
> Öneri: `NexPanel` / `CorePanel` / `GridHub` — karar verilmedi.

## Sahip
Yavuz Selim Yiğit / ViperaDev
- info@viperadev.com
- yavuzselimyigit.com

---

## Özet
Çok departmanlı, rol tabanlı yönetim sistemi.
Her departman kendi izole panelinde çalışır — kendi dashboardu, kendi mini uygulamaları.
Süper admin tüm sistemi tek noktadan yönetir.
Tek giriş ekranı, rol bazlı otomatik yönlendirme.

---

## Problem
Organizasyonlarda departmanlar farklı araçları farklı yerlerde kullanır.
Merkezi bir sistem yoksa bilgi dağılır, erişim yönetimi zorlaşır, yetki ihlalleri oluşur.

## Çözüm
Her departman için izole bir alan.
Herkes sadece kendi araçlarını görür.
Süper admin hepsine hâkim.

---

## Kullanıcı Rolleri
| Rol | Kapsam |
|-----|--------|
| `super_admin` | Tüm sistem — departman, kullanıcı, uygulama yönetimi |
| `director` | Kendi departmanı — üyeleri ve uygulamaları görür/yönetir |
| `member` | Kendi departmanı — sadece izin verilen uygulamaları kullanır |

> Bir kullanıcı birden fazla departmanda olabilir.
> Aynı kullanıcı A departmanında `director`, B departmanında `member` olabilir.

---

## Temel Özellikler (v1)
- [x] Tek giriş → rol bazlı yönlendirme
- [x] Çok departman üyeliği
- [x] Departman seçim ekranı (birden fazla departman için)
- [x] Süper admin: departman CRUD
- [x] Süper admin: kullanıcı CRUD + departman ataması
- [x] Süper admin: uygulama yönetimi
- [x] Departman paneli: dashboard + uygulama listesi
- [x] Mini uygulama modül sistemi (form, rapor, takip)
- [x] Director/Member farklı görünüm

## Kapsam Dışı (v1)
- Departmanlar arası veri paylaşımı (v2'ye ertelendi)
- REST API
- Mobil uygulama
- Bildirim sistemi (push/email)
- Çoklu dil

---

## Tech Stack
- **Backend:** PHP 8+ (OOP, MVC, framework yok)
- **Veritabanı:** MySQL 8
- **Frontend:** Vanilla JS + Custom CSS
- **Tema:** Dark mode first — Void Black zemin, turquoise aksan

---

## Hedef Kullanıcılar
- Öğrenci toplulukları
- STK ve sivil yapılanmalar
- Küçük-orta ölçekli organizasyonlar

---

## Versiyon
`v0.1.0-alpha` — başlangıç
