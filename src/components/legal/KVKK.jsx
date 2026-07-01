import React from 'react';
import LegalLayout from '../LegalLayout';

const KVKK = () => {
  return (
    <LegalLayout title="KVKK Aydınlatma Metni" lastUpdated="Ağustos 2026">
      <p>
        <strong>InnoMIS Community</strong> (Bundan böyle "Topluluk" olarak anılacaktır) olarak, 
        kişisel verilerinizin güvenliğine büyük önem veriyoruz. 6698 sayılı Kişisel Verilerin Korunması 
        Kanunu ("KVKK") uyarınca, veri sorumlusu sıfatıyla kişisel verilerinizi aşağıda açıklanan amaçlar 
        kapsamında işliyoruz.
      </p>

      <h2>1. İşlenen Kişisel Veriler</h2>
      <p>
        Platformumuz üzerinden bizimle iletişime geçtiğinizde veya etkinliklerimize kaydolduğunuzda:
      </p>
      <ul>
        <li><strong>Kimlik Bilgileri:</strong> Ad, soyad.</li>
        <li><strong>İletişim Bilgileri:</strong> E-posta adresi, telefon numarası.</li>
        <li><strong>Öğrenci Bilgileri:</strong> Okul, bölüm, sınıf bilgileri (etkinlik bazlı olarak).</li>
      </ul>

      <h2>2. Kişisel Verilerin İşlenme Amacı</h2>
      <p>
        Kişisel verileriniz aşağıdaki amaçlarla işlenmektedir:
      </p>
      <ul>
        <li>Etkinlik, atölye ve zirve organizasyonlarının yürütülmesi ve katılımın sağlanması.</li>
        <li>Topluluk içi iletişim ve bilgilendirme faaliyetlerinin gerçekleştirilmesi.</li>
        <li>Katılımcı istatistiklerinin oluşturulması ve topluluk deneyiminin iyileştirilmesi.</li>
      </ul>

      <h2>3. Kişisel Verilerin Aktarımı</h2>
      <p>
        Verileriniz kural olarak üçüncü kişilerle paylaşılmamaktadır. Ancak, yasal bir zorunluluk 
        bulunması halinde veya etkinliğin fiziksel mekan sponsoru tarafından güvenlik gereği istenmesi 
        durumunda ilgili mercilere/sponsorlara aktarılabilir.
      </p>

      <h2>4. İlgili Kişinin Hakları</h2>
      <p>
        KVKK’nın 11. maddesi uyarınca veri sahibi olarak;
      </p>
      <ul>
        <li>Kişisel verilerinizin işlenip işlenmediğini öğrenme,</li>
        <li>İşlenmişse buna ilişkin bilgi talep etme,</li>
        <li>İşlenme amacını ve amacına uygun kullanılıp kullanılmadığını öğrenme,</li>
        <li>Eksik veya yanlış işlenmişse düzeltilmesini isteme haklarına sahipsiniz.</li>
      </ul>

      <p className="mt-8">
        Haklarınızı kullanmak için <strong>info@innomis.tr</strong> adresi üzerinden bizimle iletişime geçebilirsiniz.
      </p>

      <p className="text-sm mt-8 p-4 bg-white/5 rounded-lg border border-white/10 text-text-meta">
        <strong>Not:</strong> Bu metin bir taslak (placeholder) metindir. Lütfen hukuk biriminiz tarafından kurumunuza özel hazırlanmış resmi KVKK Aydınlatma Metnini buraya ekleyiniz.
      </p>
    </LegalLayout>
  );
};

export default KVKK;
