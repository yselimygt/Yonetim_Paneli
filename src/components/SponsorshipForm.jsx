import React from 'react';
import { Send, Building2, User, Mail, Phone } from 'lucide-react';

const SponsorshipForm = () => {
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

            <form className="space-y-6" onSubmit={(e) => e.preventDefault()}>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {/* Yetkili Ad Soyad */}
                <div className="space-y-2">
                  <label htmlFor="fullName" className="text-sm font-medium text-text-main flex items-center gap-2">
                    <User className="w-4 h-4 text-primary" /> Yetkili Ad Soyad
                  </label>
                  <input 
                    type="text" 
                    id="fullName"
                    placeholder="İsminiz" 
                    className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-[0_0_15px_rgba(244,159,32,0.2)] transition-all"
                  />
                </div>

                {/* Şirket Adı */}
                <div className="space-y-2">
                  <label htmlFor="company" className="text-sm font-medium text-text-main flex items-center gap-2">
                    <Building2 className="w-4 h-4 text-primary" /> Şirket / Kurum Adı
                  </label>
                  <input 
                    type="text" 
                    id="company"
                    placeholder="Şirketinizin Adı" 
                    className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-[0_0_15px_rgba(244,159,32,0.2)] transition-all"
                  />
                </div>

                {/* E-posta */}
                <div className="space-y-2">
                  <label htmlFor="email" className="text-sm font-medium text-text-main flex items-center gap-2">
                    <Mail className="w-4 h-4 text-primary" /> Kurumsal E-posta
                  </label>
                  <input 
                    type="email" 
                    id="email"
                    placeholder="ornek@sirketiniz.com" 
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
                    placeholder="+90 (___) ___ __ __" 
                    className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-[0_0_15px_rgba(244,159,32,0.2)] transition-all"
                  />
                </div>
              </div>

              {/* Sponsorluk Türü */}
              <div className="space-y-2">
                <label htmlFor="type" className="text-sm font-medium text-text-main">İlgilenilen Sponsorluk Türü</label>
                <select 
                  id="type"
                  className="w-full px-4 py-3 rounded-xl bg-background/50 border border-white/10 text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all appearance-none"
                  style={{ backgroundImage: `url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e")`, backgroundPosition: `right .5rem center`, backgroundRepeat: `no-repeat`, backgroundSize: `1.5em 1.5em`, paddingRight: `2.5rem` }}
                >
                  <option value="" disabled selected className="bg-background text-text-meta">Bir seçenek seçin...</option>
                  <option value="main" className="bg-background">Ana Sponsorluk</option>
                  <option value="event" className="bg-background">Etkinlik Sponsorluğu</option>
                  <option value="product" className="bg-background">Ürün / Hizmet Desteği</option>
                  <option value="other" className="bg-background">Diğer İş Birlikleri</option>
                </select>
              </div>

              {/* Mesaj */}
              <div className="space-y-2">
                <label htmlFor="message" className="text-sm font-medium text-text-main">Mesajınız & Detaylar</label>
                <textarea 
                  id="message"
                  rows={4}
                  placeholder="İş birliği hakkında eklemek istedikleriniz..." 
                  className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-[0_0_15px_rgba(244,159,32,0.2)] transition-all resize-none"
                ></textarea>
              </div>

              <div className="pt-4">
                <button type="submit" className="w-full py-4 bg-gradient-primary text-white rounded-xl font-bold hover:-translate-y-1 shadow-[0_0_15px_rgba(244,159,32,0.3)] hover:shadow-[0_0_25px_rgba(218,44,46,0.5)] transition-all flex justify-center items-center gap-2">
                  <Send className="w-5 h-5" />
                  Başvuruyu Gönder
                </button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </section>
  );
};

export default SponsorshipForm;
