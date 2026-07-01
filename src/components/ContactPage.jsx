import React from 'react';
import { Mail, MapPin, Phone } from 'lucide-react';
import { socials } from '../data/innomisData';

const ContactPage = () => {
  return (
    <section className="py-24 pt-32 relative bg-surface/30 min-h-screen border-y border-white/5" id="contact-page">
      {/* Glow */}
      <div className="absolute top-0 right-1/4 w-[400px] h-[400px] bg-primary/5 blur-[120px] rounded-full pointer-events-none"></div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div className="text-center mb-16">
          <h2 className="font-display text-4xl sm:text-5xl font-bold text-white mb-4">
            Bizimle <span className="text-gradient">İletişime Geçin</span>
          </h2>
          <p className="text-text-secondary max-w-2xl mx-auto">
            Yeni fikirler, projeler ve ekosistemdeki yeriniz için bizimle iletişime geçmekten çekinmeyin.
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
          
          {/* Left: Form */}
          <div className="order-2 lg:order-1 glass-card p-8 sm:p-10">
            <div className="mb-8">
              <h3 className="font-display font-bold text-3xl text-white mb-2">Bize Ulaşın</h3>
              <p className="text-text-secondary text-sm">Sorularınız veya iş birlikleri için aşağıdaki formu doldurabilirsiniz.</p>
            </div>

            <form className="space-y-5" onSubmit={(e) => e.preventDefault()}>
              <div className="space-y-1.5">
                <label htmlFor="name" className="text-sm font-medium text-text-main">Ad Soyad</label>
                <input 
                  type="text" 
                  id="name"
                  placeholder="İsminiz" 
                  className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-[0_0_15px_rgba(244,159,32,0.2)] transition-all"
                />
              </div>

              <div className="space-y-1.5">
                <label htmlFor="email" className="text-sm font-medium text-text-main">E-posta</label>
                <input 
                  type="email" 
                  id="email"
                  placeholder="ornek@email.com" 
                  className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-[0_0_15px_rgba(244,159,32,0.2)] transition-all"
                />
              </div>

              <div className="space-y-1.5">
                <label htmlFor="subject" className="text-sm font-medium text-text-main">Konu</label>
                <input 
                  type="text" 
                  id="subject"
                  placeholder="Mesajınızın konusu" 
                  className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-[0_0_15px_rgba(244,159,32,0.2)] transition-all"
                />
              </div>

              <div className="space-y-1.5">
                <label htmlFor="message" className="text-sm font-medium text-text-main">Mesaj</label>
                <textarea 
                  id="message"
                  rows={4}
                  placeholder="Mesajınız..." 
                  className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-[0_0_15px_rgba(244,159,32,0.2)] transition-all resize-none"
                ></textarea>
              </div>

              <button type="submit" className="w-full py-4 bg-gradient-primary text-white rounded-xl font-bold hover:-translate-y-1 shadow-[0_0_15px_rgba(244,159,32,0.3)] hover:shadow-[0_0_25px_rgba(218,44,46,0.5)] transition-all">
                Mesajı Gönder
              </button>
            </form>
          </div>

          {/* Right: Info */}
          <div className="order-1 lg:order-2 space-y-8">

            <div className="space-y-6">
              {/* E-posta */}
              <div className="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/10">
                <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                  <Mail className="w-6 h-6" />
                </div>
                <div>
                  <p className="text-text-meta text-xs uppercase tracking-wider font-bold mb-1">E-posta</p>
                  <a href="mailto:info.innomis@gmail.com" className="text-white font-medium hover:text-primary transition-colors">info.innomis@gmail.com</a>
                </div>
              </div>

              {/* Telefon */}
              <div className="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/10">
                <div className="w-12 h-12 rounded-xl bg-accent-teal/10 flex items-center justify-center text-accent-teal shrink-0">
                  <Phone className="w-6 h-6" />
                </div>
                <div>
                  <p className="text-text-meta text-xs uppercase tracking-wider font-bold mb-1">Telefon</p>
                  <a href="tel:05555555555" className="text-white font-medium hover:text-primary transition-colors">0555 555 55 55</a>
                </div>
              </div>

              {/* Adres */}
              <div className="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/10">
                <div className="w-12 h-12 rounded-xl bg-accent-purple/10 flex items-center justify-center text-accent-purple shrink-0">
                  <MapPin className="w-6 h-6" />
                </div>
                <div>
                  <p className="text-text-meta text-xs uppercase tracking-wider font-bold mb-1">Konum</p>
                  <p className="text-white font-medium text-sm leading-relaxed">
                    Dere Aşıklar, Köyceğiz Yerleşkesi Köyceğiz, Demeç Sk. No:42/3, 42005 Meram/Konya<br/>
                    <span className="text-text-secondary">Necmettin Erbakan Üniversitesi / Uygulamalı Bilimler Fakültesi</span>
                  </p>
                </div>
              </div>
            </div>

            <div>
              <p className="text-text-meta text-xs uppercase tracking-wider font-bold mb-4">Sosyal Medya</p>
              <div className="flex gap-4">
                {socials.map((social, idx) => (
                  <a 
                    key={idx} 
                    href={social.href} 
                    className="w-12 h-12 rounded-xl glass-card flex items-center justify-center text-text-secondary hover:text-primary hover:border-primary/50 hover:shadow-[0_0_15px_rgba(244,159,32,0.3)] hover:-translate-y-1 transition-all duration-300"
                    aria-label={social.label}
                  >
                    {social.icon}
                  </a>
                ))}
              </div>
            </div>
          </div>
        </div>

        {/* Map Section */}
        <div className="mt-16 glass-card p-2 sm:p-4 rounded-3xl overflow-hidden h-[400px] relative border border-white/10 hover:border-white/20 transition-all duration-300">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.727670977827!2d32.42436737521876!3d37.81989437197368!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14d08db8c0b2f56b%3A0x6b801a2f6fb39e80!2sNecmettin%20Erbakan%20%C3%9Cniversitesi%20Uygulamal%C4%B1%20Bilimler%20Fak%C3%BCltesi!5e0!3m2!1str!2str!4v1700000000000!5m2!1str!2str" 
            width="100%" 
            height="100%" 
            style={{ border: 0, borderRadius: '1rem' }} 
            allowFullScreen="" 
            loading="lazy" 
            referrerPolicy="no-referrer-when-downgrade"
            title="InnoMIS Community Konum"
            className="filter invert-[90%] hue-rotate-180 opacity-80 mix-blend-screen"
          ></iframe>
        </div>

      </div>
    </section>
  );
};

export default ContactPage;
