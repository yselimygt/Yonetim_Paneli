import React, { useState } from 'react';
import { Briefcase, Handshake, Target, ArrowRight, Award } from 'lucide-react';
import SponsorshipForm from './SponsorshipForm';
import BrandIdentity from './BrandIdentity';
import Awards from './Awards';
import { useSiteData } from '../context/SiteDataContext';

const Corporate = () => {
  const [activeTab, setActiveTab] = useState('sponsor');
  const { siteData } = useSiteData();
  const sponsors = siteData?.settings?.sponsors || [];

  return (
    <div className="pt-12">
      {/* Header Section */}
      <section className="py-16 lg:py-24 relative overflow-hidden">
        <div className="absolute top-0 right-0 w-96 h-96 bg-primary/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
          <div className="text-center max-w-3xl mx-auto">
            <h1 className="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">
              <span className="text-gradient">Kurumsal</span>
            </h1>
            <p className="text-lg text-text-secondary">
              InnoMIS Community olarak, teknoloji ve kariyer ekosistemine değer katmak isteyen markalarla güçlü iş birlikleri inşa ediyoruz. Öğrencilerle markaları buluşturan köprü olmaktan gurur duyuyoruz.
            </p>
          </div>
        </div>
      </section>

      {/* Tab Switcher */}
      <div className="flex justify-center mb-12 relative z-10 px-4">
        <div className="flex flex-col sm:flex-row bg-white/5 p-1.5 rounded-2xl sm:rounded-full border border-white/10 gap-1 sm:gap-2 w-full max-w-sm sm:max-w-none sm:w-auto mx-auto">
          <button 
            onClick={() => setActiveTab('sponsor')}
            className={`w-full px-2 sm:px-10 py-2.5 sm:py-3 text-xs sm:text-base font-semibold rounded-xl sm:rounded-full transition-all duration-300 ${activeTab === 'sponsor' ? 'bg-gradient-to-r from-primary to-secondary text-white shadow-lg' : 'text-text-secondary hover:text-white hover:bg-white/5'}`}
          >
            Sponsorluk
          </button>
          <button 
            onClick={() => setActiveTab('brand')}
            className={`w-full px-2 sm:px-10 py-2.5 sm:py-3 text-xs sm:text-base font-semibold rounded-xl sm:rounded-full transition-all duration-300 ${activeTab === 'brand' ? 'bg-gradient-to-r from-primary to-secondary text-white shadow-lg' : 'text-text-secondary hover:text-white hover:bg-white/5'}`}
          >
            Kurumsal Kimlik
          </button>
          <button 
            onClick={() => setActiveTab('awards')}
            className={`w-full px-2 sm:px-10 py-2.5 sm:py-3 text-xs sm:text-base font-semibold rounded-xl sm:rounded-full transition-all duration-300 ${activeTab === 'awards' ? 'bg-gradient-to-r from-primary to-secondary text-white shadow-lg' : 'text-text-secondary hover:text-white hover:bg-white/5'}`}
          >
            Ödüllerimiz
          </button>
        </div>
      </div>

      {activeTab === 'sponsor' && (
        <div className="animate-fade-in-up">
          {/* Values Section */}
      <section className="py-16 lg:py-24 bg-surface/30 border-y border-white/5 relative">
        <div className="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
          <div className="grid grid-cols-3 gap-2 sm:gap-8">
            
            <div className="glass-card p-3 sm:p-8 rounded-xl sm:rounded-2xl group hover:-translate-y-1 sm:hover:-translate-y-2 transition-transform flex flex-col items-center sm:items-start text-center sm:text-left">
              <div className="w-8 h-8 sm:w-14 sm:h-14 bg-primary/20 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-6">
                <Handshake className="w-4 h-4 sm:w-7 sm:h-7 text-primary" />
              </div>
              <h3 className="text-xs sm:text-2xl font-bold text-white mb-1 sm:mb-4 leading-tight">Sponsorluk Çözümleri</h3>
              <p className="text-text-secondary text-[9px] sm:text-base leading-tight sm:leading-relaxed hidden sm:block">
                Etkinlik sponsorlukları, ürün yerleştirme, stant alanları ve dijital görünürlük seçenekleri ile markanızı doğrudan hedef kitlenizle (öğrencilerle) buluşturuyoruz.
              </p>
            </div>

            <div className="glass-card p-3 sm:p-8 rounded-xl sm:rounded-2xl group hover:-translate-y-1 sm:hover:-translate-y-2 transition-transform flex flex-col items-center sm:items-start text-center sm:text-left">
              <div className="w-8 h-8 sm:w-14 sm:h-14 bg-accent-cyan/20 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-6">
                <Target className="w-4 h-4 sm:w-7 sm:h-7 text-accent-cyan" />
              </div>
              <h3 className="text-xs sm:text-2xl font-bold text-white mb-1 sm:mb-4 leading-tight">Hedef Kitle Erişimi</h3>
              <p className="text-text-secondary text-[9px] sm:text-base leading-tight sm:leading-relaxed hidden sm:block">
                Teknolojiye meraklı, kariyer hedefleri olan, yenilikçi ve dinamik üniversite öğrencilerine doğrudan ve organik bir şekilde ulaşma imkanı sunuyoruz.
              </p>
            </div>

            <div className="glass-card p-3 sm:p-8 rounded-xl sm:rounded-2xl group hover:-translate-y-1 sm:hover:-translate-y-2 transition-transform flex flex-col items-center sm:items-start text-center sm:text-left">
              <div className="w-8 h-8 sm:w-14 sm:h-14 bg-accent-purple/20 rounded-lg sm:rounded-xl flex items-center justify-center mb-2 sm:mb-6">
                <Briefcase className="w-4 h-4 sm:w-7 sm:h-7 text-accent-purple" />
              </div>
              <h3 className="text-xs sm:text-2xl font-bold text-white mb-1 sm:mb-4 leading-tight">Ortak Projeler</h3>
              <p className="text-text-secondary text-[9px] sm:text-base leading-tight sm:leading-relaxed hidden sm:block">
                Markanızın ihtiyaçlarına yönelik hackathonlar, fikir yarışmaları, atölye çalışmaları ve uzun soluklu eğitim programları tasarlıyoruz.
              </p>
            </div>

          </div>
        </div>
      </section>

      {/* Memberships & Affiliations */}
      <section className="py-12 relative border-b border-white/5">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="glass-card p-8 sm:p-12 rounded-3xl border border-white/10 relative overflow-hidden group">
            <div className="absolute inset-0 bg-gradient-to-r from-red-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
            <div className="flex flex-col md:flex-row items-center gap-8 md:gap-12 relative z-10">
              <div className="flex-1 space-y-4 text-center md:text-left">
                <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-500/20 text-red-400 text-xs sm:text-sm font-bold tracking-wider uppercase border border-red-500/20 mb-2">
                  <Award className="w-4 h-4" /> Gururla Üyesiyiz
                </div>
                <h3 className="font-display text-2xl sm:text-3xl font-bold text-white">
                  Milli Teknoloji Kulüpler Birliği
                </h3>
                <p className="text-text-secondary leading-relaxed text-sm sm:text-base">
                  TÜBİTAK öncülüğünde kurulan <strong>Milli Teknoloji Kulüpler Birliği (MTKB)</strong> üyesi olarak, ülkemizin Milli Teknoloji Hamlesi vizyonuna katkı sağlıyor, ulusal çapta teknoloji üreten diğer vizyoner öğrenci topluluklarıyla ortak projeler ve stratejik iş birlikleri geliştiriyoruz.
                </p>
                <div className="pt-2">
                  <a 
                    href="https://tubitak.gov.tr/tr/destekler/bilim-toplum/ulusal-destek-programlari/milli-teknoloji-kulupler-birligi" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    className="inline-flex items-center gap-2 text-white font-bold hover:text-red-400 transition-colors"
                  >
                    TÜBİTAK Sayfasını İncele
                    <ArrowRight className="w-5 h-5" />
                  </a>
                </div>
              </div>
              
              <div className="shrink-0 flex items-center justify-center bg-white/5 rounded-2xl p-4 sm:p-6 border border-white/10 shadow-[0_0_30px_rgba(239,68,68,0.1)] group-hover:shadow-[0_0_50px_rgba(239,68,68,0.2)] transition-shadow">
                <img 
                  src="/assets/sponsor/tubitak-logo.png" 
                  alt="TÜBİTAK Logo" 
                  className="h-12 sm:h-16 w-auto object-contain rounded-xl"
                />
                <div className="w-px h-12 sm:h-16 bg-white/10 mx-4 sm:mx-6"></div>
                <img 
                  src="/assets/sponsor/tubitak_mtkb_logo.png" 
                  alt="TÜBİTAK Milli Teknoloji Kulüpler Birliği Logo" 
                  className="h-12 sm:h-16 w-auto object-contain rounded-xl"
                />
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Sponsors Static Grid */}
      <section className="py-12 relative border-b border-white/5">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="font-display text-2xl sm:text-3xl font-bold text-white mb-10">
            Sponsorlar
          </h2>
          <div className="flex flex-wrap justify-center gap-4 sm:gap-6">
            {sponsors.map((sponsor, idx) => (
              <div 
                key={idx} 
                className="w-36 sm:w-48 h-20 sm:h-24 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl flex items-center justify-center p-2 sm:p-4 hover:-translate-y-2 hover:scale-105 hover:bg-white/10 hover:border-white/20 transition-all duration-300 group cursor-pointer"
              >
                <img 
                  src={sponsor} 
                  alt="Sponsor Logo" 
                  className="max-h-full max-w-full object-contain"
                />
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Call to Action for Brands */}
      <section className="py-16 lg:py-24 relative">
        <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="font-display text-3xl sm:text-4xl font-bold text-white mb-6">
            Bizimle Birlikte Büyüyün
          </h2>
          <p className="text-text-secondary text-lg mb-10 max-w-2xl mx-auto">
            Geleceğin yetenekleriyle bugünden tanışmak ve ekosistemde kalıcı bir iz bırakmak için InnoMIS Community ile partner olun.
          </p>
          <a href="#sponsorship-form" className="inline-flex items-center gap-2 px-8 py-4 bg-white text-background rounded-xl font-bold hover:bg-primary hover:text-white transition-all shadow-[0_0_20px_rgba(255,255,255,0.1)] hover:shadow-[0_0_30px_rgba(244,159,32,0.4)]">
            Partnerlik Dosyasını İste
            <ArrowRight className="w-5 h-5" />
          </a>
        </div>
      </section>

      {/* Sponsorship Form Section at the bottom */}
      <SponsorshipForm />
      </div>
      )}

      {activeTab === 'brand' && (
        <div className="animate-fade-in-up pb-16">
          {/* Brand Identity Section */}
          <BrandIdentity />
        </div>
      )}

      {activeTab === 'awards' && (
        <div className="animate-fade-in-up pb-16">
          {/* Awards Section */}
          <Awards />
        </div>
      )}
    </div>
  );
};

export default Corporate;
