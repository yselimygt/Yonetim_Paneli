import React, { useState, useEffect } from 'react';
import { ArrowRight, MapPin, Users, PartyPopper, Mic, Star } from 'lucide-react';
import { Link } from 'react-router-dom';
import { useSiteData } from '../context/SiteDataContext';

const Hero = () => {
  const { siteData } = useSiteData();
  const hero = siteData?.settings?.hero || {};
  const brand = siteData?.settings?.brand || {};

  const features = [
    { icon: MapPin, text: 'Teknik ve Sosyal Geziler' },
    { icon: Users, text: 'Networking Etkinlikleri' },
    { icon: PartyPopper, text: 'Sosyal ve Teknik Etkinlikler' },
    { icon: Mic, text: 'Seminerler, Konferanslar' }
  ];

  const galleryImages = [
    '/innomis/assets/image/gallery/misconnect25-innomis.png',
    '/innomis/assets/image/gallery/google-cloud-sonrasi.png',
    '/innomis/assets/image/gallery/konya.png',
    '/innomis/assets/image/gallery/misconnect25.jpg'
  ];

  const [currentImageIndex, setCurrentImageIndex] = useState(0);

  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentImageIndex((prev) => (prev + 1) % galleryImages.length);
    }, 8000);
    return () => clearInterval(interval);
  }, [galleryImages.length]);

  return (
    <section className="relative min-h-[90vh] flex items-center pt-10 overflow-hidden" id="hero">
      {/* Advanced Fluid Background Layer */}
      <div className="hero-glow-bg pointer-events-none">
        <div className="hero-blob hero-blob-1"></div>
        <div className="hero-blob hero-blob-2"></div>
        <div className="hero-blob hero-blob-3"></div>
      </div>
      
      {/* Subtle Grid Overlay */}
      <div className="hero-grid-overlay pointer-events-none"></div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center min-h-[70vh]">
          
          {/* LEFT: Typography, Value Prop & CTA */}
          <div className="flex flex-col justify-center text-center lg:text-left z-20 space-y-8 animate-[fadeInUp_0.8s_ease-out]">
            
            {/* Headline */}
            <div className="space-y-4">
              <h1 className="font-display font-extrabold text-5xl sm:text-6xl lg:text-7xl leading-[1.1] tracking-tight text-white drop-shadow-md">
                <span className="block">{hero.titleLine1 || 'Bilişimde Yeniliğe,'}</span>
                <span className="block text-gradient">{hero.titleLine2 || 'İnovasyonda Zirveye!'}</span>
              </h1>
              <p className="text-text-secondary text-lg sm:text-xl max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                {hero.description || 'Teknoloji ve inovasyon dolu bu ekosistemde yerini al. Geleceğin projelerini üret, güçlü bir network kur ve kendi kariyerinin lideri ol.'}
              </p>
            </div>

            {/* Quick Features */}
            <div className="flex flex-col gap-4 text-white/90">
              {features.map((feature, idx) => {
                const Icon = feature.icon;
                return (
                  <div key={idx} className="flex items-center gap-4 text-lg font-medium justify-center lg:justify-start">
                    <div className="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center shadow-lg backdrop-blur-sm">
                      <Icon className="w-5 h-5 text-white" />
                    </div>
                    <span>{feature.text}</span>
                  </div>
                );
              })}
            </div>

            {/* CTA Box (No QR Code as requested) */}
            <div className="pt-4 flex justify-center lg:justify-start">
              <div className="glass-card p-6 flex flex-col sm:flex-row items-center justify-between gap-6 w-full max-w-md bg-white/5 border-white/10 relative overflow-hidden">
                <div className="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent pointer-events-none"></div>
                <div className="text-center sm:text-left relative z-10 w-full">
                  <h3 className="text-2xl font-bold text-white mb-1">Aramıza Katıl</h3>
                  <p className="text-sm text-text-secondary mb-4">Kariyerine ilk adımı at, Innomis ekosistemine dahil ol.</p>
                  <Link to={hero.primaryCta?.href || '/kayit'} className="inline-flex w-full sm:w-auto justify-center items-center gap-2 px-6 py-3 bg-white text-black rounded-full font-bold transition-all hover:-translate-y-1 hover:shadow-[0_0_20px_rgba(255,255,255,0.4)]">
                    <span>{hero.primaryCta?.label || 'Üye Ol'}</span>
                    <ArrowRight className="w-5 h-5" />
                  </Link>
                </div>
              </div>
            </div>

          </div>

          {/* RIGHT: Visual Identity & Proof */}
          <div className="relative flex justify-center items-center h-full min-h-[400px] lg:min-h-full">
            <div className="hero-perspective-container">
              <div className="hero-glass-frame">
                <div className="hero-slider-wrapper">
                  {galleryImages.map((src, index) => (
                    <div 
                      key={index} 
                      className={`hero-slide ${index === currentImageIndex ? 'active' : ''}`}
                      style={{ backgroundImage: `url('${src}')` }}
                    ></div>
                  ))}
                </div>
                
                <div className="absolute top-6 left-6 z-10">
                  <div className="flex items-center gap-2 px-4 py-2 bg-black/50 backdrop-blur-md border border-white/10 rounded-full font-semibold text-sm text-white shadow-lg">
                    <Star className="w-4 h-4 text-yellow-400 fill-yellow-400" />
                    <span>#InnoMISCommunity</span>
                  </div>
                </div>
              </div>
            </div>
            
            {/* Floating Mascot */}
            <div className="hero-floating-mascot">
              <img 
                src={brand.heroMascot || '/innomis/assets/innox/innox-kararli.png'} 
                alt="Innox Mascot" 
                className="w-full h-auto drop-shadow-2xl"
              />
            </div>
          </div>
          
        </div>
      </div>
    </section>
  );
};

export default Hero;
