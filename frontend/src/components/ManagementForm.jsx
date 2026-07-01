import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { CheckCircle2, ChevronDown, ChevronLeft, ChevronRight, Check } from 'lucide-react';
import { submitApplication } from '../services/siteApi';

const initialFormState = {
  // General
  name: '', phone: '', email: '', city: '',
  faculty: '', department: '', grade: '', studentId: '', targetTeam: '', motivation: '', securityCode: '', agreed: false,
  
  // Social Media
  hasSocialMediaExp: '', designHistory: '', postSeriesIdea: '', 
  socialPlatforms: [], designTools: [], portfolio: null, 
  reelsIdea: '', critiqueInnomis: '', weeklyHours: '', smMotivation: '',
  
  // HR
  hrCommunication: '', hrReporting: '', hrOfficeSkills: '', dailyHours: '',
  hasClubExp: '', clubExpDetails: '', hrMotivation: '', additionalNotesHR: '',
  
  // CR
  crMailCommunication: '', crProcessManagement: '', crRepresentationScore: '', additionalNotesCR: '',
  
  // Org
  orgHasExp: '', orgExpDetails: '', orgCrisisManagement: '', orgMultitasking: '', 
  orgEventType: '', orgEventIdea: '', orgTimeCommitment: '', orgEventDesign: '', 
  orgCommunication: '', additionalNotesOrg: '',
  
  // Ar-Ge
  argeTools: '', argePastProjectsDetails: '', argeContribution: '',
  argeTubitak: '', argeWebsiteCritique: '', argeFileUpload: null
};

const ManagementForm = () => {
  const [step, setStep] = useState(1);
  const [captchaNum1, setCaptchaNum1] = useState(0);
  const [captchaNum2, setCaptchaNum2] = useState(0);

  const [formData, setFormData] = useState(() => {
    const saved = localStorage.getItem('innomisForm');
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
    localStorage.setItem('innomisForm', JSON.stringify(formData));
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

  const handleArrayChange = (e, arrayName) => {
    const { value, checked } = e.target;
    setFormData(prev => {
      if (checked) {
        return { ...prev, [arrayName]: [...prev[arrayName], value] };
      } else {
        return { ...prev, [arrayName]: prev[arrayName].filter(item => item !== value) };
      }
    });
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
          type: 'management',
          fullName: formData.name,
          email: formData.email,
          phone: formData.phone,
          university: 'Selçuk Üniversitesi', 
          faculty: formData.faculty,
          message: JSON.stringify(formData, null, 2)
        });

        if (response.success) {
          localStorage.removeItem('innomisForm');
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
              Yönetim Kurulu başvurunuz başarıyla sistemimize iletildi. Değerlendirme süreci sonrası sizinle iletişime geçeceğiz.
            </p>
            <Link to="/" className="px-8 py-4 bg-gradient-primary text-white rounded-xl font-bold shadow-[0_0_20px_rgba(244,159,32,0.3)] hover:shadow-[0_0_30px_rgba(218,44,46,0.5)] transition-all">
              Ana Sayfaya Dön
            </Link>
          </div>
        </div>
      </div>
    );
  }

  const steps = ['Genel Bilgiler', 'Departman', 'Özel Sorular', 'Onay'];

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
    </div>
  );

  const renderStep2 = () => (
    <div className="space-y-4 md:space-y-6 animate-fade-in">
      <div className="pb-3 md:pb-4 border-b border-white/10">
        <h3 className="text-xl md:text-2xl font-display font-bold text-gradient">Departman Seçimi</h3>
        <p className="text-sm text-text-secondary mt-2">Hangi ekipte yer alarak topluluğa katkı sağlamak istiyorsunuz?</p>
      </div>
      
      <div className="grid grid-cols-1 gap-4 md:gap-6">
        <div className="space-y-1 md:space-y-2">
          <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">GÖREV ALMAK İSTEDİĞİNİZ DEPARTMAN *</label>
          <div className="relative">
            <select required name="targetTeam" value={formData.targetTeam} onChange={handleChange} 
              className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white appearance-none focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all [&>option]:bg-background [&>option]:text-white">
              <option value="" disabled>Departman Seçin...</option>
              <option value="Ar-Ge ve Teknoloji">Ar-Ge ve Teknoloji</option>
              <option value="İnsan Kaynakları">İnsan Kaynakları</option>
              <option value="Kurumsal İlişkiler">Kurumsal İlişkiler</option>
              <option value="Organizasyon">Organizasyon</option>
              <option value="Sosyal Medya">Sosyal Medya</option>
            </select>
            <ChevronDown className="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 md:w-5 md:h-5 text-white/50 pointer-events-none" />
          </div>
        </div>
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
        {formData.targetTeam} Adayı
      </div>
    </div>
  );

  const renderStep3 = () => {
    switch (formData.targetTeam) {
      case 'Sosyal Medya':
        return (
          <div className="space-y-4 md:space-y-6 animate-fade-in">
            {renderPreviewBox()}
            <div className="pb-3 md:pb-4 border-b border-white/10">
              <h3 className="text-2xl md:text-3xl font-display font-bold text-gradient mb-4">InnoMIS Community | Sosyal Medya Ekip Arkadaşı Başvurusu</h3>
              <p className="text-sm text-text-secondary leading-relaxed mb-4">
                InnoMIS olarak sadece etkinlik üretmiyoruz; aynı zamanda ürettiklerimizi en doğru şekilde anlatan, topluluğumuzu büyüten ve dijitalde güçlü bir iz bırakan bir ekip kuruyoruz.<br/><br/>
                Eğer; Sosyal medyaya ilgin varsa, Tasarım yapmayı seviyorsan (Canva, Photoshop vb.), Reels, story ve içerik üretmek sana keyif veriyorsa, Yaratıcı metinler yazabiliyorsan, Trendleri takip ediyor ve hızlı adapte olabiliyorsan... Seni sosyal medya ekibimizde görmek istiyoruz!
              </p>
            </div>
            
            <div className="grid grid-cols-1 gap-4 md:gap-6">
              <div className="space-y-3">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">DAHA ÖNCE SOSYAL MEDYADA GÖREV ALDIN MI? *</label>
                <div className="flex gap-4">
                  <label className="flex items-center gap-2 cursor-pointer text-white">
                    <input required type="radio" name="hasSocialMediaExp" value="Evet" onChange={handleChange} checked={formData.hasSocialMediaExp === 'Evet'} className="w-4 h-4 text-primary bg-white/5 border-white/20 focus:ring-primary" />
                    Evet
                  </label>
                  <label className="flex items-center gap-2 cursor-pointer text-white">
                    <input required type="radio" name="hasSocialMediaExp" value="Hayır" onChange={handleChange} checked={formData.hasSocialMediaExp === 'Hayır'} className="w-4 h-4 text-primary bg-white/5 border-white/20 focus:ring-primary" />
                    Hayır
                  </label>
                </div>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">KISACA TASARIM GEÇMİŞİNİ ANLAT *</label>
                <textarea required name="designHistory" value={formData.designHistory} onChange={handleChange} rows="3" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">BİR PAYLAŞIM SERİSİ FİKRİ YAZ</label>
                <textarea name="postSeriesIdea" value={formData.postSeriesIdea} onChange={handleChange} rows="2" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-3">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">HANGİ SOSYAL MEDYA ARAÇLARINI KULLANIYORSUN? *</label>
                <div className="grid grid-cols-2 sm:grid-cols-3 gap-3">
                  {['LinkedIn', 'Instagram', 'X', 'TikTok'].map(tool => (
                    <label key={tool} className="flex items-center gap-2 cursor-pointer text-white text-sm">
                      <input type="checkbox" value={tool} checked={formData.socialPlatforms.includes(tool)} onChange={(e) => handleArrayChange(e, 'socialPlatforms')} className="w-4 h-4 rounded text-primary bg-white/5 border-white/20 focus:ring-primary" />
                      {tool}
                    </label>
                  ))}
                </div>
              </div>
              <div className="space-y-3">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">HANGİ ARAÇLARI KULLANABİLİYORSUN? *</label>
                <div className="grid grid-cols-2 sm:grid-cols-3 gap-3">
                  {['Canva', 'Figma', 'CapCut', 'Illustrator', 'Photoshop', 'Premiere Pro'].map(tool => (
                    <label key={tool} className="flex items-center gap-2 cursor-pointer text-white text-sm">
                      <input type="checkbox" value={tool} checked={formData.designTools.includes(tool)} onChange={(e) => handleArrayChange(e, 'designTools')} className="w-4 h-4 rounded text-primary bg-white/5 border-white/20 focus:ring-primary" />
                      {tool}
                    </label>
                  ))}
                </div>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">PORTFÖY YÜKLEME (.ZIP VEYA .RAR) *</label>
                <input type="file" accept=".zip,.rar" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/80" />
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">YAKLAŞIK 10 SANİYELİK BİR REELS FİKRİ YAZ</label>
                <textarea name="reelsIdea" value={formData.reelsIdea} onChange={handleChange} rows="2" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">INNOMIS SOSYAL MEDYA EKİBİNİ ELEŞTİR *</label>
                <input required type="text" name="critiqueInnomis" value={formData.critiqueInnomis} onChange={handleChange} className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all" />
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">HAFTADA GERÇEKÇİ OLARAK KAÇ SAAT AYIRABİLECEKSİN? *</label>
                <input required type="text" name="weeklyHours" value={formData.weeklyHours} onChange={handleChange} className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all" />
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">BU EKİBE NEDEN KATILMAK İSTİYORSUN? *</label>
                <textarea required name="smMotivation" value={formData.smMotivation} onChange={handleChange} rows="3" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
            </div>
          </div>
        );
      case 'İnsan Kaynakları':
        return (
          <div className="space-y-4 md:space-y-6 animate-fade-in">
            {renderPreviewBox()}
            <div className="pb-3 md:pb-4 border-b border-white/10">
              <h3 className="text-2xl md:text-3xl font-display font-bold text-gradient mb-4">InnoMIS Community | İnsan Kaynakları Ekip Arkadaşı Başvuru Formu</h3>
              <p className="text-sm text-text-secondary leading-relaxed mb-4">
                InnoMIS'te sadece etkinlik üretmiyoruz; aynı zamanda güçlü bir ekip kültürü oluşturuyor, doğru insanları bir araya getiriyor ve birlikte büyüyoruz. İnsan Kaynakları ekibimiz; topluluğun kalbini oluşturan, ekip içi iletişimi güçlendiren kritik ekiplerden biridir.<br/><br/>
                Eğer; İnsanlarla iletişim kurmayı seviyorsan, Organizasyon ve planlama konusunda iyisen, Ekip içi uyumu önemsiyorsan ve Sorumluluk almaktan çekinmiyorsan... Seni İnsan Kaynakları ekibimizde görmek istiyoruz!
              </p>
            </div>
            
            <div className="grid grid-cols-1 gap-4 md:gap-6">
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">İNSANLARLA İLETİŞİM KURMA KONUSUNDA KENDİNİZİ NASIL TANIMLARSINIZ? *</label>
                <textarea required name="hrCommunication" value={formData.hrCommunication} onChange={handleChange} rows="3" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">DÜZENLİ RAPORLAMA VE TAKİP YAPABİLİR MİSİNİZ? *</label>
                <input required type="text" name="hrReporting" value={formData.hrReporting} onChange={handleChange} className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all" />
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">OFİS PROGRAMLARINI (WORD, EXCEL VB.) TEMEL DÜZEYDE KULLANABİLİR MİSİN? *</label>
                <textarea required name="hrOfficeSkills" value={formData.hrOfficeSkills} onChange={handleChange} rows="2" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">GÜNDE ORTALAMA KAÇ SAAT AYIRABİLİRSİN? *</label>
                <input required type="text" name="dailyHours" value={formData.dailyHours} onChange={handleChange} className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all" />
              </div>
              <div className="space-y-3">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">DAHA ÖNCE BİR TOPLULUK, KULÜP VEYA ORGANİZASYONDA GÖREV ALDIN MI? *</label>
                <div className="flex gap-4">
                  <label className="flex items-center gap-2 cursor-pointer text-white">
                    <input required type="radio" name="hasClubExp" value="Evet" onChange={handleChange} checked={formData.hasClubExp === 'Evet'} className="w-4 h-4 text-primary bg-white/5 border-white/20 focus:ring-primary" />
                    Evet
                  </label>
                  <label className="flex items-center gap-2 cursor-pointer text-white">
                    <input required type="radio" name="hasClubExp" value="Hayır" onChange={handleChange} checked={formData.hasClubExp === 'Hayır'} className="w-4 h-4 text-primary bg-white/5 border-white/20 focus:ring-primary" />
                    Hayır
                  </label>
                </div>
              </div>
              {formData.hasClubExp === 'Evet' && (
                <div className="space-y-1 md:space-y-2 animate-fade-in">
                  <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">EĞER ALDIYSAN KISACA AÇIKLAR MISIN. *</label>
                  <textarea required name="clubExpDetails" value={formData.clubExpDetails} onChange={handleChange} rows="2" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
                </div>
              )}
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">NEDEN İNSAN KAYNAKLARI EKİBİNDE YER ALMAK İSTİYORSUN? *</label>
                <textarea required name="hrMotivation" value={formData.hrMotivation} onChange={handleChange} rows="3" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">EKLEMEK İSTEDİĞİNİZ BİR ŞEY VAR MI? *</label>
                <textarea required name="additionalNotesHR" value={formData.additionalNotesHR} onChange={handleChange} rows="2" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
            </div>
          </div>
        );
      case 'Kurumsal İlişkiler':
        return (
          <div className="space-y-4 md:space-y-6 animate-fade-in">
            {renderPreviewBox()}
            <div className="pb-3 md:pb-4 border-b border-white/10">
              <h3 className="text-2xl md:text-3xl font-display font-bold text-gradient mb-4">InnoMIS Community | Kurumsal İlişkiler Ekip Arkadaşı Başvuru Formu</h3>
              <p className="text-sm text-text-secondary leading-relaxed mb-4">
                InnoMIS'te sadece etkinlik üretmiyoruz; aynı zamanda markalar, kurumlar ve topluluklarla güçlü bağlar kurarak etkimizi büyütüyoruz. Kurumsal İlişkiler ekibimiz; dış iletişimi yöneten, iş birlikleri geliştiren ve InnoMIS'i doğru şekilde temsil eden en kritik ekiplerden biridir.<br/><br/>
                Eğer; İletişim becerilerine güveniyorsan, İnsanlarla bağlantı kurmayı seviyorsan, Mailleşme ve resmi iletişim konusunda kendini geliştirmek istiyorsan, Markalarla çalışma fikri seni heyecanlandırıyorsa... Seni Kurumsal İlişkiler ekibimizde görmek istiyoruz!
              </p>
            </div>
            
            <div className="grid grid-cols-1 gap-4 md:gap-6">
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">MAİL YAZMA VE RESMİ İLETİŞİM KONULARINDA KENDİNİZE GÜVENİYOR MUSUNUZ? AÇIKLAYINIZ. *</label>
                <textarea required name="crMailCommunication" value={formData.crMailCommunication} onChange={handleChange} rows="3" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">GERİ DÖNÜŞ ALAMADIĞINIZDA SÜRECİ NASIL YÖNETİRSİNİZ? AÇIKLAYINIZ. *</label>
                <textarea required name="crProcessManagement" value={formData.crProcessManagement} onChange={handleChange} rows="3" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">TEMSİL YETENEĞİNİZİ 1-10 ARASI PUANLAYIN VE NEDENİNİ YAZIN. *</label>
                <textarea required name="crRepresentationScore" value={formData.crRepresentationScore} onChange={handleChange} rows="3" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">EKLEMEK İSTEDİĞİNİZ BİR ŞEY VAR MI? *</label>
                <textarea required name="additionalNotesCR" value={formData.additionalNotesCR} onChange={handleChange} rows="2" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
            </div>
          </div>
        );
      case 'Organizasyon':
        return (
          <div className="space-y-4 md:space-y-6 animate-fade-in">
            {renderPreviewBox()}
            <div className="pb-3 md:pb-4 border-b border-white/10">
              <h3 className="text-2xl md:text-3xl font-display font-bold text-gradient mb-4">InnoMIS Community | Organizasyon ve Planlama Ekibi</h3>
              <p className="text-sm text-text-secondary leading-relaxed mb-4">
                InnoMIS'te sadece fikir üretmiyoruz; bu fikirleri planlıyor, organize ediyor ve başarılı etkinliklere dönüştürüyoruz. Organizasyon & Planlama ekibimiz; etkinliklerin her aşamasını düşünen, süreci yöneten en önemli ekiplerden biridir.<br/><br/>
                Eğer; Planlı çalışmayı seviyorsan, Zaman yönetimi konusunda iyisen, Detayları fark ediyorsan, Sorumluluk alıp süreci yönetmek hoşuna gidiyorsa... Seni Organizasyon ekibimizde görmek istiyoruz!
              </p>
            </div>
            
            <div className="grid grid-cols-1 gap-4 md:gap-6">
              <div className="space-y-3">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">DAHA ÖNCE ETKİNLİK ORGANİZASYONUNDA GÖREV ALDINIZ MI? *</label>
                <div className="flex gap-4">
                  <label className="flex items-center gap-2 cursor-pointer text-white">
                    <input required type="radio" name="orgHasExp" value="Evet" onChange={handleChange} checked={formData.orgHasExp === 'Evet'} className="w-4 h-4 text-primary bg-white/5 border-white/20 focus:ring-primary" />
                    Evet
                  </label>
                  <label className="flex items-center gap-2 cursor-pointer text-white">
                    <input required type="radio" name="orgHasExp" value="Hayır" onChange={handleChange} checked={formData.orgHasExp === 'Hayır'} className="w-4 h-4 text-primary bg-white/5 border-white/20 focus:ring-primary" />
                    Hayır
                  </label>
                </div>
              </div>
              {formData.orgHasExp === 'Evet' && (
                <div className="space-y-1 md:space-y-2 animate-fade-in">
                  <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">ALDIYSANIZ AÇIKLAYINIZ.</label>
                  <textarea name="orgExpDetails" value={formData.orgExpDetails} onChange={handleChange} rows="2" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
                </div>
              )}
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">KRİZ ANINDA YAŞADIĞINIZ BİR DURUMU VE ÇÖZÜMÜNÜZÜ ANLATIN. *</label>
                <textarea required name="orgCrisisManagement" value={formData.orgCrisisManagement} onChange={handleChange} rows="3" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">AYNI ANDA BİRDEN FAZLA İŞİ NASIL YÖNETİRSİNİZ? *</label>
                <textarea required name="orgMultitasking" value={formData.orgMultitasking} onChange={handleChange} rows="2" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">AKADEMİK ETKİNLİKTE Mİ DAHA AKTİF OLABİLİRSİN SOSYAL ETKİNLİKLERDE Mİ? NEDEN? *</label>
                <textarea required name="orgEventType" value={formData.orgEventType} onChange={handleChange} rows="2" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">ŞUNU YAPARSAK TOPLULUK İÇİN İYİ OLUR DEDİĞİNİZ BİR ORGANİZASYON FİKRİNİZDEN BAHSEDİN. *</label>
                <textarea required name="orgEventIdea" value={formData.orgEventIdea} onChange={handleChange} rows="2" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">ETKİNLİKLERE NE KADAR VAKİT AYIRABİLİRSİN? *</label>
                <input required type="text" name="orgTimeCommitment" value={formData.orgTimeCommitment} onChange={handleChange} className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all" />
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">ÖRNEK BİR ETKİNLİK TASARLA VE ADIMLARINI SIRALA. *</label>
                <textarea required name="orgEventDesign" value={formData.orgEventDesign} onChange={handleChange} rows="3" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">İLETİŞİM KONUSUNDA KENDİNİ NASIL GÖRÜYORSUN? BAHSEDİNİZ. *</label>
                <textarea required name="orgCommunication" value={formData.orgCommunication} onChange={handleChange} rows="2" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">EKLEMEK İSTEDİĞİNİZ BİR ŞEY VAR MI? *</label>
                <textarea required name="additionalNotesOrg" value={formData.additionalNotesOrg} onChange={handleChange} rows="2" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
            </div>
          </div>
        );
      case 'Ar-Ge ve Teknoloji':
        return (
          <div className="space-y-4 md:space-y-6 animate-fade-in">
            {renderPreviewBox()}
            <div className="pb-3 md:pb-4 border-b border-white/10">
              <h3 className="text-2xl md:text-3xl font-display font-bold text-gradient mb-4">InnoMIS Community | Ar-Ge Ekip Arkadaşı Başvurusu</h3>
              <p className="text-sm text-text-secondary leading-relaxed mb-4">
                InnoMIS olarak sadece etkinlik üretmiyoruz; teknolojiyi, veriyi ve inovasyonu anlamlandıran, sahaya gerçek çıktılar koyan aktif bir Ar-Ge ekibimiz var.<br/><br/>
                Eğer; Yazılım geliştirmeyi seviyorsan, Arayüz tasarımıyla ilgileniyorsan, Veri analizi ilgi çekici geliyorsa, Fikirleri somut projelere dönüştürmek istiyorsan... Seni aramızda görmek istiyoruz!
              </p>
            </div>
            
            <div className="grid grid-cols-1 gap-4 md:gap-6">
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">KULLANDIĞIN ARAÇLAR / TEKNOLOJİLER NELER? *</label>
                <input required type="text" name="argeTools" value={formData.argeTools} onChange={handleChange} className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all" />
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">DAHA ÖNCE HERHANGİ BİR PROJE GELİŞTİRDİN Mİ? BÜYÜK KÜÇÜK, TAMAMLANMIŞ YARIM HİÇ FARK ETMEZ, KISACA ANLAT. *</label>
                <textarea required name="argePastProjectsDetails" value={formData.argePastProjectsDetails} onChange={handleChange} rows="3" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">EKİBİMİZE NE KATKI SAĞLAYABİLECEĞİNİ DÜŞÜNÜYORSUN? *</label>
                <textarea required name="argeContribution" value={formData.argeContribution} onChange={handleChange} rows="3" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">TÜBİTAK PROJE BAŞVURUSU VEYA ARAŞTIRMA SÜREÇLERİNE KATILMAYI DÜŞÜNÜR MÜSÜN? *</label>
                <div className="relative">
                  <select required name="argeTubitak" value={formData.argeTubitak} onChange={handleChange} 
                    className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white appearance-none focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all [&>option]:bg-background [&>option]:text-white">
                    <option value="" disabled>Seçin...</option>
                    <option value="Evet, düşünürüm">Evet, düşünürüm</option>
                    <option value="Hayır, düşünmem">Hayır, düşünmem</option>
                    <option value="Emin değilim">Emin değilim</option>
                  </select>
                  <ChevronDown className="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 md:w-5 md:h-5 text-white/50 pointer-events-none" />
                </div>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">INNOMIS.TR VE MISCONNECT.INNOMIS.TR/26 ADRESLERİNİ İNCELE. GELİŞTİRİLEBİLECEĞİNİ DÜŞÜNDÜĞÜN BİR ŞEYİ YAZ; TEKNİK, TASARIM VEYA İÇERİK FARK ETMEZ. *</label>
                <textarea required name="argeWebsiteCritique" value={formData.argeWebsiteCritique} onChange={handleChange} rows="3" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"></textarea>
              </div>
              <div className="space-y-1 md:space-y-2">
                <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">VARSA EKRAN GÖRÜNTÜSÜ VEYA DOSYA YÜKLEYEBİLİRSİN. (OPSİYONEL)</label>
                <input type="file" accept=".zip,.rar,.jpg,.png,.pdf" className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/80" />
              </div>
            </div>
          </div>
        );
      default:
        // Standart Departman (Bilinmeyen veya Genel)
        return (
          <div className="space-y-4 md:space-y-6 animate-fade-in">
            {renderPreviewBox()}
            <div className="pb-3 md:pb-4 border-b border-white/10">
              <h3 className="text-2xl md:text-3xl font-display font-bold text-gradient mb-4">{formData.targetTeam || 'Departman'} Başvurusu</h3>
            </div>
            <div className="space-y-1 md:space-y-2 mt-4">
              <label className="text-[10px] md:text-xs font-bold text-text-meta uppercase tracking-wider">MOTİVASYONUNUZ VE HEDEFLERİNİZ *</label>
              <textarea required name="motivation" value={formData.motivation} onChange={handleChange} rows="4"
                placeholder="Neden yönetim kurulunda yer almak istiyorsunuz? Topluluğa neler katabileceğinizi düşünüyorsunuz?" 
                className="w-full px-3 py-2.5 md:px-4 md:py-3 rounded-lg md:rounded-xl bg-white/5 border border-white/10 text-sm md:text-base text-white placeholder:text-white/20 focus:outline-none focus:border-primary/50 focus:bg-white/10 transition-all resize-y"
              ></textarea>
            </div>
          </div>
        );
    }
  };

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
            <Link to="/kullanim-sartlari" className="text-primary hover:underline underline-offset-2">Üye Olma Koşulları</Link>'nı ve yöneticilik sorumluluklarını okudum, kabul ediyorum. *
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
            Yönetim Kurulu <br />
            <span className="text-gradient">Başvuru Formu</span>
          </h1>
          <p className="text-text-secondary text-sm md:text-lg px-2">
            Topluluğa yön vermek ve ekibimizin bir parçası olmak için başvurunu tamamla.
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
                <div></div> // Empty div for spacing if no back button
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

export default ManagementForm;
