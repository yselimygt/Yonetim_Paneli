import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { CheckCircle2, ChevronDown, ChevronLeft, ChevronRight, Check } from 'lucide-react';
import { submitApplication } from '../services/siteApi';

const initialFormState = {
  // General Info
  name: '',
  phone: '',
  email: '',
  city: '',
  faculty: '',
  department: '',
  grade: '',
  studentId: '',

  // University Selection
  university: '',

  // Specific Questions
  pastCommunityExp: '',
  howDidYouHear: '',
  motivation: '',

  // Security
  securityCode: '',
  agreed: false
};

const RepresentativeForm = () => {
  const [step, setStep] = useState(1);
  const [captchaNum1, setCaptchaNum1] = useState(0);
  const [captchaNum2, setCaptchaNum2] = useState(0);

  const [formData, setFormData] = useState(() => {
    const saved = localStorage.getItem('innomisRepForm');
    if (saved) {
      try {
        return JSON.parse(saved);
      } catch (e) {
        return initialFormState;
      }
    }
    return initialFormState;
  });
  
  const [isSubmitted, setIsSubmitted] = useState(false);
  const [status, setStatus] = useState('idle');
  const [errorMessage, setErrorMessage] = useState('');

  useEffect(() => {
    setCaptchaNum1(Math.floor(Math.random() * 9) + 1);
    setCaptchaNum2(Math.floor(Math.random() * 9) + 1);
  }, []);

  useEffect(() => {
    localStorage.setItem('innomisRepForm', JSON.stringify(formData));
  }, [formData]);

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

  const handleNext = async (e) => {
    e.preventDefault(); 
    
    if (step === 4) {
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
        const response = await submitApplication({
          type: 'representative',
          fullName: formData.name,
          email: formData.email,
          phone: formData.phone,
          university: formData.university,
          faculty: formData.faculty,
          message: JSON.stringify(formData, null, 2)
        });

        if (response.success) {
          localStorage.removeItem('innomisRepForm');
          setIsSubmitted(true);
          setStatus('success');
          window.scrollTo(0, 0);
        } else {
          setStatus('error');
          setErrorMessage(response.message || 'Bir hata oluştu.');
        }
      } catch (error) {
        setStatus('error');
        setErrorMessage(error.message || 'Bağlantı hatası.');
      }
    } else {
      setStep(prev => prev + 1);
      window.scrollTo(0, 0);
    }
  };

  const handleBack = () => {
    setStep(prev => Math.max(1, prev - 1));
    window.scrollTo(0, 0);
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
            <h2 className="text-3xl sm:text-4xl font-display font-bold text-white mb-4">Başvurunuz Alındı!</h2>
            <p className="text-text-secondary text-lg mb-8">
              Kampüs Temsilcisi başvurunuz başarıyla sistemimize iletildi. İnceleme sonrası sizinle iletişime geçeceğiz.
            </p>
            <Link to="/" className="px-8 py-4 bg-gradient-primary text-white rounded-xl font-bold shadow-[0_0_20px_rgba(244,159,32,0.3)] hover:shadow-[0_0_30px_rgba(218,44,46,0.5)] transition-all">
              Ana Sayfaya Dön
            </Link>
          </div>
        </div>
      </div>
    );
  }

  const steps = ['Genel Bilgiler', 'Üniversite', 'Özel Sorular', 'Onay'];

  const renderProgressBar = () => (
    <div className="mb-12">
      <div className="flex justify-between relative">
        <div className="absolute top-1/2 left-0 w-full h-1 bg-white/10 -translate-y-1/2 rounded-full hidden sm:block"></div>
        
        {steps.map((label, index) => {
          const isCompleted = step > index + 1;
          const isActive = step === index + 1;
          return (
            <div key={label} className="relative z-10 flex flex-col items-center flex-1">
              <div className={`w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center text-xs md:text-sm font-bold border-2 transition-all duration-300
                ${isCompleted ? 'bg-primary border-primary text-white' : 
                  isActive ? 'bg-background border-primary text-primary shadow-[0_0_15px_rgba(244,159,32,0.4)]' : 
                  'bg-background border-white/20 text-white/50'}`}>
                {isCompleted ? <Check className="w-4 h-4 md:w-5 md:h-5" /> : index + 1}
              </div>
              <span className={`text-[10px] md:text-xs mt-2 font-medium hidden sm:block ${isActive ? 'text-primary' : isCompleted ? 'text-white' : 'text-white/50'}`}>
                {label}
              </span>
            </div>
          );
        })}
      </div>
    </div>
  );

  const renderPreviewBox = () => (
    <div className="mb-8 p-4 rounded-xl bg-primary/5 border border-primary/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <p className="text-white font-bold">{formData.name}</p>
        <p className="text-xs text-text-secondary">{formData.faculty} - {formData.department} ({formData.grade})</p>
      </div>
      <div className="text-xs font-bold text-primary px-3 py-1 rounded-full bg-primary/10 border border-primary/20 whitespace-nowrap">
        {formData.university}
      </div>
    </div>
  );

  const renderStep1 = () => (
    <div className="space-y-8 animate-fade-in">
      <div className="space-y-4 md:space-y-6">
        <div className="pb-3 md:pb-4 border-b border-white/10">
          <h3 className="text-xl md:text-2xl font-display font-bold text-gradient">Kişisel Bilgiler</h3>
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

      <div className="space-y-4 md:space-y-6">
        <div className="pb-3 md:pb-4 border-b border-white/10">
          <h3 className="text-xl md:text-2xl font-display font-bold text-gradient">Eğitim Bilgileri</h3>
        </div>
        
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
          <div className="space-y-1 md:space-y-2">
            <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">FAKÜLTE / YÜKSEKOKUL *</label>
            <input required type="text" name="faculty" value={formData.faculty} onChange={handleChange} placeholder="Mühendislik Fakültesi vb."
              className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all"
            />
          </div>
          <div className="space-y-1 md:space-y-2">
            <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">BÖLÜM *</label>
            <input required type="text" name="department" value={formData.department} onChange={handleChange} placeholder="Yönetim Bilişim Sistemleri vb."
              className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all"
            />
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
    </div>
  );

  const renderStep2 = () => (
    <div className="space-y-4 md:space-y-6 animate-fade-in">
      <div className="pb-3 md:pb-4 border-b border-white/10">
        <h3 className="text-xl md:text-2xl font-display font-bold text-gradient">Üniversite Seçimi</h3>
        <p className="text-sm text-text-secondary mt-2">Hangi üniversitede kampüs temsilcimiz olmak istiyorsunuz?</p>
      </div>
      
      <div className="grid grid-cols-1 gap-4 md:gap-6">
        <div className="space-y-1 md:space-y-2">
          <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">ÜNİVERSİTENİZ *</label>
          <div className="relative">
            <select required name="university" value={formData.university} onChange={handleChange} 
              className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white appearance-none focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all [&>option]:bg-background [&>option]:text-white">
              <option value="" disabled>Üniversite Seçin...</option>
              <option value="Selçuk Üniversitesi">Selçuk Üniversitesi</option>
              <option value="Konya Teknik Üniversitesi">Konya Teknik Üniversitesi</option>
              <option value="KTO Karatay Üniversitesi">KTO Karatay Üniversitesi</option>
              <option value="Konya Gıda ve Tarım Üniversitesi">Konya Gıda ve Tarım Üniversitesi</option>
            </select>
            <ChevronDown className="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 md:w-5 md:h-5 text-white/50 pointer-events-none" />
          </div>
        </div>
      </div>
    </div>
  );

  const renderStep3 = () => (
    <div className="space-y-4 md:space-y-6 animate-fade-in">
      {renderPreviewBox()}
      
      <div className="pb-3 md:pb-4 border-b border-white/10">
        <h3 className="text-2xl md:text-3xl font-display font-bold text-gradient mb-4">InnoMIS Community | Kampüs Temsilcisi Başvuru Formu</h3>
        <div className="space-y-4 text-sm text-text-secondary leading-relaxed">
          <p className="font-bold text-white">Kampüs Temsilcimiz Olmak İster Misin?</p>
          <p>Biz InnoMIS'te sadece etkinlik paylaşmıyoruz.<br/>Üretiyoruz, deniyoruz, öğreniyoruz ve öğrendiğimizi yayıyoruz.</p>
          <p>Yazılım, teknoloji ve inovasyona ilgin varsa; kendi kampüsünde aktif olmak, insanları bir araya getirmek ve gerçekten etki oluşturmak istiyorsan seni izleyici olarak değil ekip arkadaşı olarak arıyoruz.</p>
          <p className="font-bold text-primary">Bu üniversitelerde InnoMIS'i temsil edecek, sorumluluk almaktan kaçmayan yeni ekip arkadaşlarını arıyoruz.</p>
          <p>Kampüsünde InnoMIS'i temsil et.<br/>Topluluğun parçası olma, topluluğu büyüten kişi ol.</p>
        </div>
      </div>
      
      <div className="grid grid-cols-1 gap-4 md:gap-6 mt-6">
        <div className="space-y-1 md:space-y-2">
          <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">DAHA ÖNCE BİR ÜNİVERSİTE TOPLULUĞUNDA GÖREV ALDIN MI? ALDIYSAN HANGİ TOPLULUK? HALA ALIYOR MUSUN? *</label>
          <textarea required name="pastCommunityExp" value={formData.pastCommunityExp} onChange={handleChange} rows="3" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
        </div>
        
        <div className="space-y-1 md:space-y-2">
          <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">INNOMIS'İ NEREDEN DUYDUN? *</label>
          <div className="relative">
            <select required name="howDidYouHear" value={formData.howDidYouHear} onChange={handleChange} 
              className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white appearance-none focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all [&>option]:bg-background [&>option]:text-white">
              <option value="" disabled>Yazarak arayın veya seçin...</option>
              <option value="Sosyal Medya (Instagram vb.)">Sosyal Medya (Instagram vb.)</option>
              <option value="Arkadaş Tavsiyesi">Arkadaş Tavsiyesi</option>
              <option value="Akademisyen / Hoca">Akademisyen / Hoca</option>
              <option value="Okul Panoları / Afişler">Okul Panoları / Afişler</option>
              <option value="Etkinlikler">Etkinlikler</option>
              <option value="Diğer">Diğer</option>
            </select>
            <ChevronDown className="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 md:w-5 md:h-5 text-white/50 pointer-events-none" />
          </div>
        </div>

        <div className="space-y-1 md:space-y-2">
          <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">NEDEN KAMPÜS TEMSİLCİSİ OLMAK İSTİYORSUN? *</label>
          <textarea required name="motivation" value={formData.motivation} onChange={handleChange} rows="3" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
        </div>
      </div>
    </div>
  );

  const renderStep4 = () => (
    <div className="space-y-4 md:space-y-6 animate-fade-in">
      {renderPreviewBox()}
      <div className="pb-3 md:pb-4 border-b border-white/10">
        <h3 className="text-xl md:text-2xl font-display font-bold text-gradient">Onay ve Güvenlik</h3>
        <p className="text-sm text-text-secondary mt-2">Lütfen girdiğiniz bilgilerin doğruluğundan emin olun ve formu onaylayın.</p>
      </div>
      
      <div className="space-y-6">
        <div className="flex flex-row gap-3 sm:gap-6 items-end">
          <div className="space-y-1 md:space-y-2 flex-1 sm:max-w-xs">
            <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">GÜVENLİK KODU *</label>
            <input required type="text" name="securityCode" value={formData.securityCode} onChange={handleChange} placeholder="Cevabı girin" 
              className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all"
            />
          </div>
          
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
            <Link to="/kullanim-sartlari" className="text-primary hover:underline underline-offset-2">Üye Olma Koşulları</Link>'nı ve kampüs temsilciliği sorumluluklarını okudum, kabul ediyorum. *
          </span>
        </label>
      </div>
    </div>
  );

  return (
    <div className="min-h-screen bg-background relative overflow-hidden pt-20 md:pt-28 pb-16 md:pb-24">
      <div className="absolute top-0 right-0 w-1/3 h-96 bg-primary/10 blur-[120px] rounded-full pointer-events-none"></div>
      <div className="absolute bottom-0 left-0 w-1/3 h-96 bg-accent-cyan/10 blur-[120px] rounded-full pointer-events-none"></div>

      <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div className="text-center mb-8 md:mb-12">
          <h1 className="font-display text-2xl sm:text-3xl md:text-5xl lg:text-6xl font-bold text-white mb-2 md:mb-4">
            Kampüs Temsilcisi <br />
            <span className="text-gradient">Başvuru Formu</span>
          </h1>
          <p className="text-text-secondary text-sm md:text-lg px-2">
            Üniversitende InnoMIS bayrağını dalgalandırmak için ilk adımı at.
          </p>
        </div>

        {renderProgressBar()}

        <div className="glass-card p-5 sm:p-8 md:p-12 rounded-2xl sm:rounded-3xl border-white/5 shadow-2xl relative">
          <form onSubmit={handleNext} className="space-y-8">
            
            {step === 1 && renderStep1()}
            {step === 2 && renderStep2()}
            {step === 3 && renderStep3()}
            {step === 4 && renderStep4()}

            {/* Navigation Buttons */}
            <div className="pt-8 border-t border-white/10 flex items-center justify-between mt-8">
              {step > 1 ? (
                <button type="button" onClick={handleBack} className="flex items-center gap-2 px-4 py-2 sm:px-6 sm:py-3 text-sm sm:text-base font-bold text-white/70 hover:text-white bg-white/5 hover:bg-white/10 rounded-xl transition-all">
                  <ChevronLeft className="w-4 h-4 sm:w-5 sm:h-5" />
                  Geri
                </button>
              ) : (
                <div></div>
              )}
              
              <button type="submit" 
                className="flex items-center gap-2 px-6 py-2 sm:px-8 sm:py-3 bg-gradient-primary text-white rounded-xl font-bold text-sm sm:text-base shadow-[0_0_20px_rgba(244,159,32,0.3)] hover:shadow-[0_0_30px_rgba(218,44,46,0.5)] hover:-translate-y-0.5 transition-all">
                {step === 4 ? 'Başvuruyu Gönder' : 'İleri'}
                {step !== 4 && <ChevronRight className="w-4 h-4 sm:w-5 sm:h-5" />}
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  );
};

export default RepresentativeForm;
