import React from 'react';
import { Link } from 'react-router-dom';
import { Users, GraduationCap, ArrowRight } from 'lucide-react';

const ApplicationHub = () => {
  return (
    <div className="min-h-screen bg-background relative overflow-hidden pt-24 md:pt-32 pb-16 md:pb-24">
      {/* Decorative Glows */}
      <div className="absolute top-0 right-0 w-1/3 h-96 bg-primary/10 blur-[120px] rounded-full pointer-events-none"></div>
      <div className="absolute bottom-0 left-0 w-1/3 h-96 bg-secondary/10 blur-[120px] rounded-full pointer-events-none"></div>

      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {/* Header */}
        <div className="text-center mb-12 md:mb-20">
          <h1 className="font-display text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4 md:mb-6">
            InnoMIS <span className="text-gradient">Ekibine Katıl</span>
          </h1>
          <p className="text-text-secondary text-base md:text-lg max-w-2xl mx-auto px-2">
            Topluluğumuzu büyütmek ve vizyonumuzu daha geniş kitlelere ulaştırmak için çekirdek ekibimize veya temsilci ağımıza katılın.
          </p>
        </div>

        {/* Options Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10 max-w-4xl mx-auto">
          
          {/* Option 1: Yönetim Kurulu */}
          <div className="glass-card p-8 md:p-10 rounded-3xl border-white/5 shadow-2xl group hover:-translate-y-2 transition-all duration-300 hover:shadow-[0_20px_40px_rgba(244,159,32,0.1)] relative overflow-hidden flex flex-col h-full">
            <div className="absolute top-0 left-0 w-full h-1 bg-gradient-primary opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <div className="w-16 h-16 md:w-20 md:h-20 bg-primary/10 rounded-2xl flex items-center justify-center mb-8 border border-primary/20 group-hover:bg-primary group-hover:text-white transition-colors text-primary">
              <Users className="w-8 h-8 md:w-10 md:h-10" />
            </div>
            
            <h3 className="text-2xl md:text-3xl font-display font-bold text-white mb-4">
              Yönetim Kurulu Başvurusu
            </h3>
            
            <p className="text-text-secondary mb-8 leading-relaxed flex-grow">
              Topluluğun karar alma süreçlerinde yer almak, etkinlikleri organize etmek ve ekipleri yönetmek istiyorsanız Yönetim Kurulu'na katılın.
            </p>
            
            <Link to="/yonetim-basvuru" className="flex items-center gap-2 text-primary font-bold text-lg group-hover:gap-4 transition-all">
              Başvuru Yap <ArrowRight className="w-5 h-5" />
            </Link>
          </div>

          {/* Option 2: Temsilcilik */}
          <div className="glass-card p-8 md:p-10 rounded-3xl border-white/5 shadow-2xl group hover:-translate-y-2 transition-all duration-300 hover:shadow-[0_20px_40px_rgba(218,44,46,0.1)] relative overflow-hidden flex flex-col h-full">
            <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-secondary to-accent-cyan opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <div className="w-16 h-16 md:w-20 md:h-20 bg-secondary/10 rounded-2xl flex items-center justify-center mb-8 border border-secondary/20 group-hover:bg-secondary group-hover:text-white transition-colors text-secondary">
              <GraduationCap className="w-8 h-8 md:w-10 md:h-10" />
            </div>
            
            <h3 className="text-2xl md:text-3xl font-display font-bold text-white mb-4">
              Üniversite Temsilcisi Ol
            </h3>
            
            <p className="text-text-secondary mb-8 leading-relaxed flex-grow">
              Kendi üniversitenizde InnoMIS bayrağını dalgalandırmak, yerel bir ekosistem kurmak ve üniversitenizi temsil etmek istiyorsanız bize katılın.
            </p>
            
            <Link to="/temsilci-basvuru" className="flex items-center gap-2 text-secondary font-bold text-lg group-hover:gap-4 transition-all">
              Başvuru Yap <ArrowRight className="w-5 h-5" />
            </Link>
          </div>

        </div>

      </div>
    </div>
  );
};

export default ApplicationHub;
