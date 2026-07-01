import React from 'react';
import LegalLayout from '../LegalLayout';

const Terms = () => {
  return (
    <LegalLayout title="Kullanım Şartları" lastUpdated="Ağustos 2026">
      <h2>1. Sözleşmenin Kabulü</h2>
      <p>
        Bu web sitesine (innomis.tr) erişim sağlayarak veya siteyi kullanarak bu Kullanım Şartları'nı 
        kabul etmiş sayılırsınız. Şartların herhangi bir bölümünü kabul etmiyorsanız, lütfen sitemizi kullanmayınız.
      </p>

      <h2>2. Hizmetlerin Sağlanması</h2>
      <p>
        InnoMIS Community, sitedeki içerikleri, etkinlik duyurularını ve platformları önceden bildirmeksizin 
        değiştirme, askıya alma veya kalıcı olarak sonlandırma hakkını saklı tutar.
      </p>

      <h2>3. Kullanıcı Yükümlülükleri</h2>
      <ul>
        <li>Kullanıcı, siteyi yasalara ve dürüstlük kurallarına uygun kullanmakla yükümlüdür.</li>
        <li>Siteye zarar verecek, işleyişini bozacak veya diğer kullanıcıların deneyimini engelleyecek 
        hiçbir yazılım, kod veya eylemde bulunulamaz.</li>
        <li>Etkinlik kayıtlarında veya formlarda verilen bilgilerin doğruluğu kullanıcının sorumluluğundadır.</li>
      </ul>

      <h2>4. Fikri Mülkiyet Hakları</h2>
      <p>
        Sitede yer alan logolar, tasarımlar, metinler, grafikler ve yazılımlar InnoMIS Community'nin mülkiyetindedir 
        veya lisansla kullanılmaktadır. İzinsiz kopyalanamaz, çoğaltılamaz ve dağıtılamaz.
      </p>

      <h2>5. Sorumluluk Sınırlandırması</h2>
      <p>
        Sitemizde yer alan dış bağlantılar (üçüncü taraf siteleri) tamamen bilgilendirme amaçlıdır. Bu sitelerin 
        içeriklerinden InnoMIS Community sorumlu tutulamaz.
      </p>

      <p className="text-sm mt-8 p-4 bg-white/5 rounded-lg border border-white/10 text-text-meta">
        <strong>Not:</strong> Bu metin bir taslak (placeholder) metindir. Lütfen yasal danışmanınız tarafından hazırlanmış gerçek kullanım şartlarınızı buraya ekleyiniz.
      </p>
    </LegalLayout>
  );
};

export default Terms;
