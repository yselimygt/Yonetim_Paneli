# React Admin Sayfaları Mobil Planı

## İlgili Sayfalar

- `DashboardPage.jsx`
- `PanelSelectPage.jsx`
- `DeptDashboardPage.jsx`
- `MembersPage.jsx`
- `CommitteesListPage.jsx`
- `CommitteesDetailPage.jsx`
- `EventsListPage.jsx`
- `EventsDetailPage.jsx`
- `FormsListPage.jsx`
- `FormsBuilderPage.jsx`
- `FormsSubmissionsPage.jsx`
- `ManagementReportsPage.jsx`
- `ManagementAnnouncePage.jsx`
- `ManagementDirectoryPage.jsx`
- `FinancePage.jsx`
- `DuesPage.jsx`
- `SuperAdminUsersPage.jsx`
- `SuperAdminDepartmentsPage.jsx`
- `SuperAdminAppsPage.jsx`
- `SuperAdminLogsPage.jsx`

## Sayfa Grupları ve Mobil Düzenler

### Dashboard ve Panel Seçimi

- `DashboardPage`, `DeptDashboardPage`, `PanelSelectPage`
- Stat gridleri 4/3 kolon yerine mobilde tek veya iki kolona düşecek.
- Hero alanları mobilde dikey akacak; metin ve aksiyonlar aynı kart dilini koruyacak.
- Departman/app kartları `minmax(300px, 1fr)` yerine küçük ekranlarda tam genişlik davranışına alınacak.

### Üye ve Departman Yönetimi

- `MembersPage`, `SuperAdminUsersPage`, `SuperAdminDepartmentsPage`
- Filtre/search toolbar alanları mobilde tek kolona düşecek.
- Üye ve kullanıcı tabloları mobilde kart görünümüne dönüşecek veya mevcut kart dönüşümü eksik kolonlar için tamamlanacak.
- Satır aksiyonları sağa sıkışmak yerine kart altında tam genişlik buton grubu olacak.
- Kullanıcı/departman modal formları tek kolon çalışacak.

### Komiteler ve Etkinlikler

- `CommitteesListPage`, `CommitteesDetailPage`, `EventsListPage`, `EventsDetailPage`
- Kanban, görev, alt birim ve etkinlik detay gridleri mobilde tek kolona düşecek.
- Yönetim butonları page heading altında sarabilir/tam genişlik olabilir.
- Modal içindeki uzun form ve seçim listeleri kaydırılabilir olacak.

### Form Yönetimi

- `FormsListPage`, `FormsBuilderPage`, `FormsSubmissionsPage`
- Form builder alanında sol alan/panel düzeni mobilde tek kolona alınacak.
- Sürükleme veya alan listesi içeren bölgelerde minimum dokunma hedefi korunacak.
- Submission detay modalı mobilde tam genişliğe yakın açılacak.
- Tablo veya cevap listeleri mobilde alan adı/değer kartlarına dönüşecek.

### Yönetim Raporları ve Rehber

- `ManagementReportsPage`, `ManagementAnnouncePage`, `ManagementDirectoryPage`
- Rapor kartları ve departman accordion satırları mobilde sıkışmadan dikey akacak.
- Rehber ekranında mevcut kart görünümü mobilde ana görünüm olacak; tablo gizleme davranışı korunacak.
- Duyuru oluşturma ve duyuru listesi iki kolon yerine tek kolon olacak.

### Finans ve Aidat

- `FinancePage`, `DuesPage`
- Ana liste ve yan panel düzeni mobilde tek kolon olacak.
- Finans/aidat tabloları veri kaybı olmadan kart veya kontrollü yatay scroll ile sunulacak.
- Para tutarı, durum ve aksiyonlar küçük ekranda aynı satıra zorlanmayacak.

### Super Admin Log ve Uygulama Sayfaları

- `SuperAdminAppsPage`, `SuperAdminLogsPage`
- Log tablosu mobilde yatay scroll veya kart satırlarına dönüşecek.
- App atama kartları/table alanları tek kolon akacak.
- Uzun route, e-posta, açıklama gibi metinler taşmadan kırılacak.

## Kabul Kriterleri

- Her React admin sayfası 360px ve 430px genişlikte yatay scroll üretmeden kullanılabilir.
- Tüm butonlar ve ikon butonlar 44px hedefe yaklaşır.
- Form submit/cancel aksiyonları mobilde görünür ve kolay dokunulur kalır.
- Tablolarda kolon bilgisi kaybolmaz.
- Masaüstü düzen ve görsel karakter korunur.
