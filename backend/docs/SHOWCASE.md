# SHOWCASE.md

## Proje Özeti (Portfolio / Demo)

---

## Tek Cümle
> Çok departmanlı, rol bazlı izole yönetim sistemi — her departman kendi alanında çalışır, süper admin tüm sistemi tek noktadan yönetir.

---

## Ne Gösteriyor?

### Teknik Yetkinlikler
| Alan | Gösterilen Beceri |
|------|--------------------|
| PHP OOP | Abstract sınıflar, inheritance, interface |
| MVC Mimarisi | Custom router, controller, model katmanları |
| Veritabanı Tasarımı | Pivot tablo, foreign key, rol modeli |
| RBAC | Rol bazlı erişim kontrolü (3 seviye) |
| Güvenlik | CSRF, XSS koruması, prepared statements, session |
| Modüler Sistem | BaseModule → özelleştirilebilir mini uygulamalar |
| UI/UX | Dark mode, departmana özel tema rengi, izole arayüzler |

### Mimari Kararlar
- Framework olmadan custom MVC
- Dinamik departman oluşturma (sabit yapı yok)
- Aynı kullanıcı → farklı departmanlarda farklı rol
- App modül sistemi (yeni app tipi = yeni sınıf)

---

## Demo Akışı

### Senaryo 1: Süper Admin Bakış Açısı
1. `/login` ile süper admin girişi
2. SA dashboard — departman/kullanıcı/app sayıları
3. Yeni departman oluştur (İK Departmanı, turquoise)
4. Yeni kullanıcı oluştur
5. Kullanıcıyı İK'ya Director olarak ata
6. İK'ya "Personel Takip" uygulaması ekle (TrackerModule)
7. Çıkış → kullanıcı ile giriş

### Senaryo 2: Director Bakış Açısı
1. `/login` ile Director girişi
2. Direkt departman paneline yönlendirme
3. Dashboard: mevcut app'ler görünüyor
4. Personel Takip app'ini aç, kayıt ekle
5. `director_only` app görünüyor, member göremez

### Senaryo 3: Çok Departmanlı Kullanıcı
1. A departmanında Director, B departmanında Member olan kullanıcı girişi
2. `/panel` seçim ekranı — iki departman kartı
3. A'yı seç → Director panel
4. B'ye geç → Member panel (farklı app erişimi)

### Senaryo 4: Yeni Departman Dinamiği
1. SA yeni departman ekler
2. Kullanıcı atar
3. App ekler
4. Kullanıcı girişte yeni departmanı görür — sistem anında çalışır

---

## Ekran Görüntüsü Listesi (hazırlanacak)

- [ ] Login sayfası
- [ ] SA dashboard
- [ ] SA departman yönetimi
- [ ] SA kullanıcı atama
- [ ] Departman seçim ekranı
- [ ] Departman paneli (Director görünümü)
- [ ] Departman paneli (Member görünümü)
- [ ] FormModule çalışır hâlde
- [ ] TrackerModule çalışır hâlde

---

## Teknik Vurgular (README / Portfolio İçin)

```
✦ Custom PHP MVC — framework bağımlılığı yok
✦ 3 seviyeli RBAC: Super Admin → Director → Member
✦ Aynı kullanıcı N departmanda, her departmanda farklı rol
✦ Dinamik departman oluşturma — sınır yok
✦ Modüler mini uygulama sistemi — yeni tip = yeni sınıf
✦ Dark mode first UI, departmana özel renk teması
✦ PDO prepared statements, CSRF, XSS, session güvenliği
```

---

## Hedef Kitle (Sunum İçin)
- Öğrenci toplulukları ve STK'lar (gerçek kullanım)
- İşveren / mülakatçı (teknik portfolyo)
- ViperaDev müşteri adayları (ürün demo)

---

## Bağlantılar (doldurul)
- **Demo URL:** —
- **GitHub:** —
- **Portfolio:** labs.yavuzselimyigit.com
