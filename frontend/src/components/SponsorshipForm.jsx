import React, { useState } from 'react';
import { Send, Building2, User, Mail, Phone, CheckCircle2 } from 'lucide-react';
import { submitMessage } from '../services/siteApi';

const SponsorshipForm = () => {
  const [formData, setFormData] = useState({
    fullName: '',
    company: '',
    email: '',
    phone: '',
    category: '',
    message: ''
  });
  const [status, setStatus] = useState('idle'); // idle, loading, success, error
  const [errorMessage, setErrorMessage] = useState('');

  const handleChange = (e) => {
    let val = e.target.value;
    if (e.target.name === 'phone') {
      let x = val.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
      val = !x[1] ? '' : (!x[2] ? '(' + x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? ' ' + x[3] : '') + (x[4] ? ' ' + x[4] : ''));
    }
    setFormData({ ...formData, [e.target.name]: val });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!formData.fullName || !formData.company || !formData.email || !formData.category) {
      setErrorMessage('Lütfen gerekli alanları doldurun.');
      setStatus('error');
      return;
    }

    setStatus('loading');
    setErrorMessage('');

    try {
      const response = await submitMessage({
        ...formData,
        type: 'sponsorship'
      });

      if (response.success) {
        setStatus('success');
        setFormData({
          fullName: '',
          company: '',
          email: '',
          phone: '',
          category: '',
          message: ''
        });
      } else {
        setStatus('error');
        setErrorMessage(response.message || 'Bir hata oluştu.');
      }
    } catch (error) {
      setStatus('error');
      setErrorMessage(error.message || 'Bağlantı hatası oluştu.');
    }
  };

  return (
    <section className="py-16 lg:py-24 relative" id="sponsorship-form">
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div className="glass-card p-8 sm:p-12 rounded-3xl relative overflow-hidden">
          {/* Background Glow */}
          <div className="absolute top-0 right-0 w-64 h-64 bg-primary/10 blur-[80px] rounded-full pointer-events-none"></div>
          <div className="absolute bottom-0 left-0 w-64 h-64 bg-accent-purple/10 blur-[80px] rounded-full pointer-events-none"></div>

          <div className="relative z-10">
            <div className="text-center mb-10">
              <h2 className="font-display font-bold text-3xl sm:text-4xl text-white mb-4">Sponsorluk <span className="text-gradient">Başvurusu</span></h2>
              <p className="text-text-secondary">InnoMIS Community ile partner olmak için aşağıdaki formu doldurun, ekibimiz en kısa sürede sizinle iletişime geçsin.</p>
            </div>

            {status === 'success' ? (
              <div className="bg-green-500/10 border border-green-500/20 rounded-2xl p-8 text-center fade-in-up">
                <div className="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                  <CheckCircle2 className="w-8 h-8 text-green-400" />
                </div>
                <h3 className="text-xl font-bold text-white mb-2">Başvurunuz Alındı!</h3>
                <p className="text-green-200/80">En kısa sürede ekibimiz tarafından incelenip size geri dönüş sağlanacaktır. Teşekkür ederiz.</p>
                <button 
                  onClick={() => setStatus('idle')}
                  className="mt-6 px-6 py-2 bg-white/5 hover:bg-white/10 rounded-lg text-white transition-colors"
                >
                  Yeni Başvuru Yap
                </button>
              </div>
            ) : (
            <form className="space-y-6" onSubmit={handleSubmit}>
              {status === 'error' && (
                <div className="p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm">
                  {errorMessage}
                </div>
              )}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {/* Yetkili Ad Soyad */}
                <div className="space-y-2">
                  <label htmlFor="fullName" className="text-sm font-medium text-text-main flex items-center gap-2">
                    <User className="w-4 h-4 text-primary" /> Yetkili Ad Soyad *
                  </label>
                  <input 
                    type="text" 
                    id="fullName"
                    name="fullName"
                    value={formData.fullName}
                    onChange={handleChange}
                    placeholder="İsminiz" 
                    required
                    className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-[0_0_15px_rgba(244,159,32,0.2)] transition-all"
                  />
                </div>

                {/* Şirket Adı */}
                <div className="space-y-2">
                  <label htmlFor="company" className="text-sm font-medium text-text-main flex items-center gap-2">
                    <Building2 className="w-4 h-4 text-primary" /> Şirket / Kurum Adı *
                  </label>
                  <input 
                    type="text" 
                    id="company"
                    name="company"
                    value={formData.company}
                    onChange={handleChange}
                    placeholder="Şirketinizin Adı" 
                    required
                    className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-[0_0_15px_rgba(244,159,32,0.2)] transition-all"
                  />
                </div>

                {/* E-posta */}
                <div className="space-y-2">
                  <label htmlFor="email" className="text-sm font-medium text-text-main flex items-center gap-2">
                    <Mail className="w-4 h-4 text-primary" /> Kurumsal E-posta *
                  </label>
                  <input 
                    type="email" 
                    id="email"
                    name="email"
                    value={formData.email}
                    onChange={handleChange}
                    placeholder="ornek@sirketiniz.com" 
                    required
                    className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-[0_0_15px_rgba(244,159,32,0.2)] transition-all"
                  />
                </div>

                {/* Telefon */}
                <div className="space-y-2">
                  <label htmlFor="phone" className="text-sm font-medium text-text-main flex items-center gap-2">
                    <Phone className="w-4 h-4 text-primary" /> Telefon Numarası
                  </label>
                  <input 
                    type="tel" 
                    id="phone"
                    name="phone"
                    value={formData.phone}
                    onChange={handleChange}
                    placeholder="+90 (___) ___ __ __" 
                    className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-[0_0_15px_rgba(244,159,32,0.2)] transition-all"
                  />
                </div>
              </div>

              <div className="space-y-2">
                <label htmlFor="category" className="text-sm font-medium text-text-main">İlgilenilen Sponsorluk Türü *</label>
                <select 
                  id="category"
                  name="category"
                  value={formData.category}
                  onChange={handleChange}
                  required
                  className="w-full px-4 py-3 rounded-xl bg-background/50 border border-white/10 text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all appearance-none"
                  style={{ backgroundImage: `url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e")`, backgroundPosition: `right .5rem center`, backgroundRepeat: `no-repeat`, backgroundSize: `1.5em 1.5em`, paddingRight: `2.5rem` }}
                >
                  <option value="" disabled className="bg-background text-text-meta">Bir seçenek seçin...</option>
                  <option value="Ana Sponsorluk" className="bg-background">Ana Sponsorluk</option>
                  <option value="Etkinlik Sponsorluğu" className="bg-background">Etkinlik Sponsorluğu</option>
                  <option value="Ürün/Hizmet Desteği" className="bg-background">Ürün / Hizmet Desteği</option>
                  <option value="Diğer" className="bg-background">Diğer İş Birlikleri</option>
                </select>
              </div>

              {/* Mesaj */}
              <div className="space-y-2">
                <label htmlFor="message" className="text-sm font-medium text-text-main">Mesajınız & Detaylar</label>
                <textarea 
                  id="message"
                  name="message"
                  value={formData.message}
                  onChange={handleChange}
                  rows={4}
                  placeholder="İş birliği hakkında eklemek istedikleriniz..." 
                  className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-[0_0_15px_rgba(244,159,32,0.2)] transition-all resize-none"
                ></textarea>
              </div>

              <div className="pt-4">
                <button 
                  type="submit" 
                  disabled={status === 'loading'}
                  className={`w-full py-4 bg-gradient-primary text-white rounded-xl font-bold transition-all flex justify-center items-center gap-2 ${status === 'loading' ? 'opacity-70 cursor-not-allowed' : 'hover:-translate-y-1 shadow-[0_0_15px_rgba(244,159,32,0.3)] hover:shadow-[0_0_25px_rgba(218,44,46,0.5)]'}`}
                >
                  <Send className={`w-5 h-5 ${status === 'loading' ? 'animate-pulse' : ''}`} />
                  {status === 'loading' ? 'Gönderiliyor...' : 'Başvuruyu Gönder'}
                </button>
              </div>
            </form>
            )}

          </div>
        </div>
      </div>
    </section>
  );
};

export default SponsorshipForm;
