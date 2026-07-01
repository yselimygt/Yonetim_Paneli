import React from 'react';
import LegalLayout from '../LegalLayout';

const Privacy = () => {
  return (
    <LegalLayout title="Gizlilik Politikası" lastUpdated="Ağustos 2026">
      <h2>1. Veri Toplama</h2>
      <p>
        InnoMIS Community olarak gizliliğinize önem veriyoruz. Sitemizi ziyaret ettiğinizde, etkinliklerimize 
        kaydolduğunuzda veya bültenimize abone olduğunuzda temel iletişim bilgilerinizi (ad, soyad, e-posta, telefon vb.) 
        toplayabiliriz. Bu bilgiler tamamen rızanız dahilinde alınır.
      </p>

      <h2>2. Verilerin Kullanımı</h2>
      <p>
        Toplanan veriler yalnızca aşağıdaki amaçlarla kullanılır:
      </p>
      <ul>
        <li>Etkinlik katılım süreçlerini yönetmek.</li>
        <li>İlginizi çekebilecek duyuruları e-posta bülteni ile iletmek.</li>
        <li>Topluluk deneyimini iyileştirmek için anonimleştirilmiş analizler yapmak.</li>
      </ul>

      <h2>3. Veri Güvenliği</h2>
      <p>
        Sistemlerimizde barındırılan verileriniz, endüstri standartlarına uygun şifreleme ve güvenlik önlemleriyle 
        korunmaktadır. Verileriniz, yasal zorunluluklar dışında üçüncü şahıslarla asla paylaşılmaz ve ticari amaçla satılmaz.
      </p>

      <h2>4. Çerezler (Cookies)</h2>
      <p>
        Sitemizde kullanıcı deneyimini artırmak ve performans ölçümleri yapmak amacıyla temel çerezler kullanılmaktadır. 
        Tarayıcı ayarlarınızdan çerezleri dilediğiniz zaman kapatabilirsiniz.
      </p>

      <h2>5. İletişim</h2>
      <p>
        Gizlilik politikamız veya kişisel verilerinizle ilgili her türlü soru ve talebiniz için 
        bizimle <a href="mailto:info@innomis.tr">info@innomis.tr</a> adresi üzerinden iletişime geçebilirsiniz.
      </p>
      
      <p className="text-sm mt-8 p-4 bg-white/5 rounded-lg border border-white/10 text-text-meta">
        <strong>Not:</strong> Bu metin bir taslak (placeholder) metindir. Lütfen yasal danışmanınız tarafından hazırlanmış gerçek gizlilik politikanızı buraya ekleyiniz.
      </p>
    </LegalLayout>
  );
};

export default Privacy;
