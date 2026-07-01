import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { CheckCircle2, ChevronDown, Send } from 'lucide-react';
import { submitMessage } from '../services/siteApi';

const RegistrationForm = () => {
  const [captchaNum1, setCaptchaNum1] = useState(0);
  const [captchaNum2, setCaptchaNum2] = useState(0);

  const [formData, setFormData] = useState({
    name: '',
    phone: '',
    email: '',
    city: '',
    faculty: '',
    department: '',
    grade: '',
    studentId: '',
    securityCode: '',
    agreed: false
  });
  
  const [isSubmitted, setIsSubmitted] = useState(false);
  const [status, setStatus] = useState('idle');
  const [errorMessage, setErrorMessage] = useState('');

  // Sayfa yüklendiğinde rastgele 2 sayı belirle
  React.useEffect(() => {
    setCaptchaNum1(Math.floor(Math.random() * 9) + 1); // 1-9 arası
    setCaptchaNum2(Math.floor(Math.random() * 9) + 1);
  }, []);

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    let val = type === 'checkbox' ? checked : value;
    if (name === 'phone') {
      let x = val.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
      val = !x[1] ? '' : (!x[2] ? '(' + x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? ' ' + x[3] : '') + (x[4] ? ' ' + x[4] : ''));
    }
    setFormData(prev => ({
      ...prev,
      [name]: val
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const correctAnswer = captchaNum1 + captchaNum2;
    if(parseInt(formData.securityCode) !== correctAnswer) {
      setErrorMessage(`Güvenlik kodunu (${captchaNum1} + ${captchaNum2} = ${correctAnswer}) doğru girdiğinizden emin olun.`);
      return;
    }
    if(!formData.agreed) {
      setErrorMessage('Lütfen Üye Olma Koşullarını kabul ediniz.');
      return;
    }
    
    setStatus('loading');
    setErrorMessage('');

    try {
      const response = await submitMessage({
        fullName: formData.name,
        email: formData.email,
        phone: formData.phone,
        category: 'Üyelik Kaydı',
        company: formData.department ? `${formData.faculty} / ${formData.department}` : formData.faculty,
        message: `Şehir: ${formData.city}\nSınıf: ${formData.grade}\nÖğrenci No: ${formData.studentId}`,
        type: 'membership'
      });

      if (response.success) {
        setIsSubmitted(true);
        setStatus('success');
        window.scrollTo(0, 0);
      } else {
        setStatus('error');
        setErrorMessage(response.message || 'Kayıt sırasında bir hata oluştu.');
      }
    } catch (error) {
      setStatus('error');
      setErrorMessage(error.message || 'Bağlantı hatası oluştu.');
    }
  };

  if (isSubmitted) {
    return (
      <div className="min-h-screen bg-background relative overflow-hidden pt-24 pb-24 flex items-center justify-center">
        <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-primary/20 blur-[120px] rounded-full pointer-events-none"></div>
        <div className="max-w-xl mx-auto px-4 relative z-10 w-full text-center">
          <div className="glass-card p-12 rounded-3xl border-primary/30 flex flex-col items-center">
            <div className="w-20 h-20 bg-green-500/20 text-green-500 rounded-full flex items-center justify-center mb-6">
              <CheckCircle2 className="w-10 h-10" />
            </div>
            <h2 className="text-3xl sm:text-4xl font-display font-bold text-white mb-4">Kaydınız Alındı!</h2>
            <p className="text-text-secondary text-lg mb-8">
              Aramıza hoş geldiniz! Başvurunuz başarıyla InnoMIS Community sistemine kaydedildi. Gelişmeler için sizi e-posta üzerinden bilgilendireceğiz.
            </p>
            <Link to="/" className="px-8 py-4 bg-gradient-primary text-white rounded-xl font-bold shadow-[0_0_20px_rgba(244,159,32,0.3)] hover:shadow-[0_0_30px_rgba(218,44,46,0.5)] transition-all">
              Ana Sayfaya Dön
            </Link>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-background relative overflow-hidden pt-20 md:pt-28 pb-16 md:pb-24">
      {/* Decorative Glows */}
      <div className="absolute top-0 right-0 w-1/3 h-96 bg-primary/10 blur-[120px] rounded-full pointer-events-none"></div>
      <div className="absolute bottom-0 left-0 w-1/3 h-96 bg-accent-cyan/10 blur-[120px] rounded-full pointer-events-none"></div>

      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {/* Header */}
        <div className="text-center mb-6 md:mb-12">
          <h1 className="font-display text-2xl sm:text-3xl md:text-5xl lg:text-6xl font-bold text-white mb-2 md:mb-4">
            InnoMIS Community <br />
            <span className="text-gradient">Kayıt Formu</span>
          </h1>
          <p className="text-text-secondary text-sm md:text-lg px-2">
            Geleceği şekillendirecek projelere katılmak için ilk adımı at.
          </p>
        </div>

        {/* Form Container */}
        <div className="glass-card p-5 sm:p-8 md:p-12 rounded-2xl sm:rounded-3xl border-white/5 shadow-2xl">
          {errorMessage && (
            <div className="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm">
              {errorMessage}
            </div>
          )}
          <form onSubmit={handleSubmit} className="space-y-8 md:space-y-12">
            
            {/* 01 - Kişisel Bilgiler */}
            <div className="space-y-4 md:space-y-6">
              <div className="pb-3 md:pb-4 border-b border-white/10">
                <h3 className="text-xl md:text-2xl font-display font-bold text-gradient">01 — Kişisel Bilgiler</h3>
              </div>
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div className="space-y-1 md:space-y-2">
                  <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">ADI SOYADI *</label>
                  <input required type="text" name="name" value={formData.name} onChange={handleChange} placeholder="Adınız ve Soyadınız" 
                    className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all"
                  />
                </div>
                <div className="space-y-1 md:space-y-2">
                  <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">İRTİBAT TELEFONU *</label>
                  <input required type="tel" name="phone" value={formData.phone} onChange={handleChange} placeholder="05XX XXX XX XX" 
                    className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all"
                  />
                </div>
                <div className="space-y-1 md:space-y-2">
                  <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">E-POSTA ADRESİ *</label>
                  <input required type="email" name="email" value={formData.email} onChange={handleChange} placeholder="ornek@mail.com" 
                    className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all"
                  />
                </div>
                <div className="space-y-1 md:space-y-2">
                  <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">ŞEHİR / İLÇE (OPSİYONEL)</label>
                  <input type="text" name="city" value={formData.city} onChange={handleChange} placeholder="İlçe / Semt" 
                    className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all"
                  />
                </div>
              </div>
            </div>

            {/* 02 - Üniversite Bilgileri */}
            <div className="space-y-4 md:space-y-6">
              <div className="pb-3 md:pb-4 border-b border-white/10">
                <h3 className="text-xl md:text-2xl font-display font-bold text-gradient">02 — Üniversite Bilgileri</h3>
              </div>
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div className="space-y-1 md:space-y-2">
                  <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">FAKÜLTE / YÜKSEKOKUL *</label>
                  <div className="relative">
                    <select required name="faculty" value={formData.faculty} onChange={handleChange} 
                      className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white appearance-none focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all [&>option]:bg-background [&>option]:text-white">
                      <option value="" disabled>Fakülte Seçin...</option>
                      <option value="İktisadi ve İdari Bilimler">İktisadi ve İdari Bilimler Fakültesi</option>
                      <option value="Mühendislik">Mühendislik Fakültesi</option>
                      <option value="Diğer">Diğer</option>
                    </select>
                    <ChevronDown className="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 md:w-5 md:h-5 text-white/50 pointer-events-none" />
                  </div>
                </div>
                <div className="space-y-1 md:space-y-2">
                  <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">BÖLÜM *</label>
                  <div className="relative">
                    <select required name="department" value={formData.department} onChange={handleChange} 
                      className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white appearance-none focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all [&>option]:bg-background [&>option]:text-white">
                      <option value="" disabled>Bölüm Seçin...</option>
                      <option value="YBS">Yönetim Bilişim Sistemleri (YBS)</option>
                      <option value="Bilgisayar Müh">Bilgisayar Mühendisliği</option>
                      <option value="İşletme">İşletme</option>
                      <option value="Diğer">Diğer</option>
                    </select>
                    <ChevronDown className="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 md:w-5 md:h-5 text-white/50 pointer-events-none" />
                  </div>
                </div>
                <div className="space-y-1 md:space-y-2">
                  <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">SINIF *</label>
                  <div className="relative">
                    <select required name="grade" value={formData.grade} onChange={handleChange} 
                      className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white appearance-none focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all [&>option]:bg-background [&>option]:text-white">
                      <option value="" disabled>Seçin</option>
                      <option value="Hazırlık">Hazırlık</option>
                      <option value="1">1. Sınıf</option>
                      <option value="2">2. Sınıf</option>
                      <option value="3">3. Sınıf</option>
                      <option value="4">4. Sınıf</option>
                      <option value="Mezun">Mezun</option>
                    </select>
                    <ChevronDown className="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 md:w-5 md:h-5 text-white/50 pointer-events-none" />
                  </div>
                </div>
                <div className="space-y-1 md:space-y-2">
                  <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">ÖĞRENCİ NUMARASI *</label>
                  <input required type="text" name="studentId" value={formData.studentId} onChange={handleChange} placeholder="Öğrenci No" 
                    className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all"
                  />
                </div>
              </div>
            </div>

            {/* 03 - Onay ve Güvenlik */}
            <div className="space-y-4 md:space-y-6">
              <div className="pb-3 md:pb-4 border-b border-white/10">
                <h3 className="text-xl md:text-2xl font-display font-bold text-gradient">03 — Onay ve Güvenlik</h3>
              </div>
              
              <div className="space-y-6">
                <div className="flex flex-row gap-3 sm:gap-6 items-end">
                  <div className="space-y-1 md:space-y-2 flex-1 sm:max-w-xs">
                    <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">GÜVENLİK KODU *</label>
                    <input required type="text" name="securityCode" value={formData.securityCode} onChange={handleChange} placeholder="Cevabı girin" 
                      className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all"
                    />
                  </div>
                  
                  {/* Görsel Doğrulama (Captcha) */}
                  <div className="h-[44px] md:h-[50px] shrink-0 px-6 md:px-8 bg-black/30 border border-white/10 rounded-lg md:rounded-xl flex items-center justify-center select-none overflow-hidden relative">
                    <div className="absolute inset-0 opacity-20 pointer-events-none" style={{ backgroundImage: 'radial-gradient(#fff 1px, transparent 1px)', backgroundSize: '10px 10px' }}></div>
                    <span 
                      className="font-display text-2xl md:text-3xl font-bold text-primary tracking-widest relative z-10" 
                      style={{ transform: 'skew(-10deg) rotate(-2deg)' }}
                    >
                      {captchaNum1} + {captchaNum2}
                    </span>
                  </div>
                </div>

                <label className="flex items-start gap-3 cursor-pointer group">
                  <div className="relative flex items-start pt-0.5 md:pt-1">
                    <input required type="checkbox" name="agreed" checked={formData.agreed} onChange={handleChange} 
                      className="peer w-4 h-4 md:w-5 md:h-5 rounded border-white/20 bg-white/5 checked:bg-primary checked:border-primary focus:ring-primary focus:ring-offset-background transition-all"
                    />
                  </div>
                  <span className="text-xs md:text-sm text-text-secondary group-hover:text-white transition-colors leading-relaxed">
                    <Link to="/kullanim-sartlari" className="text-primary hover:underline underline-offset-2">Üye Olma Koşulları</Link>'nı okudum ve kabul ediyorum. 
                    InnoMIS'in üyelik beklentilerini, sorumluluk anlayışını ve aktif katılım şartını kabul ediyorum. *
                  </span>
                </label>
              </div>
            </div>

            {/* Submit Button */}
            <div className="pt-2 md:pt-6">
              <button 
                type="submit" 
                disabled={status === 'loading'}
                className={`w-full py-4 md:py-5 bg-gradient-primary text-white rounded-xl md:rounded-2xl font-bold md:text-lg transition-all flex justify-center items-center gap-2 ${status === 'loading' ? 'opacity-70 cursor-not-allowed' : 'hover:-translate-y-1 shadow-[0_0_20px_rgba(244,159,32,0.3)] hover:shadow-[0_0_30px_rgba(218,44,46,0.5)]'}`}
              >
                <Send className={`w-5 h-5 ${status === 'loading' ? 'animate-pulse' : ''}`} />
                {status === 'loading' ? 'İşleniyor...' : 'Başvurumu Tamamla'}
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  );
};

export default RegistrationForm;
