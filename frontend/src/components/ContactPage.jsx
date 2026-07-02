import React, { useState } from 'react';
import { Mail, MapPin, Phone, CheckCircle2, Send } from 'lucide-react';
import { socials } from '../data/innomisData';
import { submitMessage } from '../services/siteApi';
import { useSiteData } from '../context/SiteDataContext';

const ContactPage = () => {
  const { siteData } = useSiteData();
  const contact = siteData?.settings?.contact || {};
  const contactSocials = contact.socials || {};
  const socialKey = (label) => {
    const normalized = label.toLowerCase();
    if (normalized.startsWith('x')) return 'x';
    return normalized;
  };
  const socialHref = (social) => contactSocials[socialKey(social.label)] || social.href;
  const email = contact.email || 'info.innomis@gmail.com';
  const phone = contact.phone || '0555 555 55 55';
  const phoneHref = phone.replace(/\s+/g, '');
  const mapEmbedUrl = contact.mapEmbedUrl || 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.727670977827!2d32.42436737521876!3d37.81989437197368!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14d08db8c0b2f56b%3A0x6b801a2f6fb39e80!2sNecmettin%20Erbakan%20%C3%9Cniversitesi%20Uygulamal%C4%B1%20Bilimler%20Fak%C3%BCltesi!5e0!3m2!1str!2str!4v1700000000000!5m2!1str!2str';
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    subject: '',
    message: ''
  });
  const [status, setStatus] = useState('idle'); // idle, loading, success, error
  const [errorMessage, setErrorMessage] = useState('');

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.id]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!formData.name || !formData.email || !formData.message) {
      setErrorMessage('Lütfen gerekli alanları doldurun.');
      setStatus('error');
      return;
    }

    setStatus('loading');
    setErrorMessage('');

    try {
      const response = await submitMessage({
        fullName: formData.name,
        email: formData.email,
        category: formData.subject || 'Genel İletişim',
        message: formData.message,
        type: 'contact'
      });

      if (response.success) {
        setStatus('success');
        setFormData({ name: '', email: '', subject: '', message: '' });
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

            {status === 'success' ? (
              <div className="bg-green-500/10 border border-green-500/20 rounded-2xl p-8 text-center fade-in-up">
                <div className="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                  <CheckCircle2 className="w-8 h-8 text-green-400" />
                </div>
                <h3 className="text-xl font-bold text-white mb-2">Mesajınız Alındı!</h3>
                <p className="text-green-200/80">Size en kısa sürede geri dönüş yapacağız. Teşekkür ederiz.</p>
                <button 
                  onClick={() => setStatus('idle')}
                  className="mt-6 px-6 py-2 bg-white/5 hover:bg-white/10 rounded-lg text-white transition-colors"
                >
                  Yeni Mesaj Gönder
                </button>
              </div>
            ) : (
            <form className="space-y-5" onSubmit={handleSubmit}>
              {status === 'error' && (
                <div className="p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm">
                  {errorMessage}
                </div>
              )}
              <div className="space-y-1.5">
                <label htmlFor="name" className="text-sm font-medium text-text-main">Ad Soyad *</label>
                <input 
                  type="text" 
                  id="name"
                  value={formData.name}
                  onChange={handleChange}
                  placeholder="İsminiz" 
                  required
                  className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-[0_0_15px_rgba(244,159,32,0.2)] transition-all"
                />
              </div>

              <div className="space-y-1.5">
                <label htmlFor="email" className="text-sm font-medium text-text-main">E-posta *</label>
                <input 
                  type="email" 
                  id="email"
                  value={formData.email}
                  onChange={handleChange}
                  placeholder="ornek@email.com" 
                  required
                  className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-[0_0_15px_rgba(244,159,32,0.2)] transition-all"
                />
              </div>

              <div className="space-y-1.5">
                <label htmlFor="subject" className="text-sm font-medium text-text-main">Konu</label>
                <input 
                  type="text" 
                  id="subject"
                  value={formData.subject}
                  onChange={handleChange}
                  placeholder="Mesajınızın konusu" 
                  className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-[0_0_15px_rgba(244,159,32,0.2)] transition-all"
                />
              </div>

              <div className="space-y-1.5">
                <label htmlFor="message" className="text-sm font-medium text-text-main">Mesaj *</label>
                <textarea 
                  id="message"
                  value={formData.message}
                  onChange={handleChange}
                  rows={4}
                  required
                  placeholder="Mesajınız..." 
                  className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-[0_0_15px_rgba(244,159,32,0.2)] transition-all resize-none"
                ></textarea>
              </div>

              <button 
                type="submit" 
                disabled={status === 'loading'}
                className={`w-full py-4 bg-gradient-primary text-white rounded-xl font-bold transition-all flex justify-center items-center gap-2 ${status === 'loading' ? 'opacity-70 cursor-not-allowed' : 'hover:-translate-y-1 shadow-[0_0_15px_rgba(244,159,32,0.3)] hover:shadow-[0_0_25px_rgba(218,44,46,0.5)]'}`}
              >
                <Send className={`w-5 h-5 ${status === 'loading' ? 'animate-pulse' : ''}`} />
                {status === 'loading' ? 'Gönderiliyor...' : 'Mesajı Gönder'}
              </button>
            </form>
            )}
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
                  <a href={`mailto:${email}`} className="text-white font-medium hover:text-primary transition-colors">{email}</a>
                </div>
              </div>

              {/* Telefon */}
              <div className="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/10">
                <div className="w-12 h-12 rounded-xl bg-accent-teal/10 flex items-center justify-center text-accent-teal shrink-0">
                  <Phone className="w-6 h-6" />
                </div>
                <div>
                  <p className="text-text-meta text-xs uppercase tracking-wider font-bold mb-1">Telefon</p>
                  <a href={`tel:${phoneHref}`} className="text-white font-medium hover:text-primary transition-colors">{phone}</a>
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
                    {contact.address || 'Dere Aşıklar, Köyceğiz Yerleşkesi Köyceğiz, Demeç Sk. No:42/3, 42005 Meram/Konya'}<br/>
                    <span className="text-text-secondary">{contact.addressDetail || 'Necmettin Erbakan Üniversitesi / Uygulamalı Bilimler Fakültesi'}</span>
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
                    href={socialHref(social)}
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
            src={mapEmbedUrl}
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
