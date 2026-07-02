import React from 'react';
import { ArrowRight, PlayCircle } from 'lucide-react';
import { Link } from 'react-router-dom';
import { useSiteData } from '../context/SiteDataContext';

const Hero = () => {
  const { siteData } = useSiteData();
  const hero = siteData?.settings?.hero || {};
  const brand = siteData?.settings?.brand || {};
  const stats = siteData?.settings?.stats || [];
  const heroStats = stats.slice(0, 3);

  return (
    <section className="relative min-h-[90vh] flex items-center pt-10 overflow-hidden" id="hero">
      {/* Background Effects */}
      <div className="absolute top-1/4 -right-1/4 w-[600px] h-[600px] bg-primary/20 blur-[120px] rounded-full pointer-events-none"></div>
      <div className="absolute bottom-0 -left-1/4 w-[500px] h-[500px] bg-accent-purple/20 blur-[150px] rounded-full pointer-events-none"></div>
      
      {/* Dot Grid Overlay */}
      <div className="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] [mask-image:radial-gradient(ellipse_at_center,black_40%,transparent_80%)] pointer-events-none"></div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
          
          {/* Left Content */}
          <div className="space-y-8 text-center lg:text-left">
            <h1 className="font-display font-extrabold text-4xl sm:text-6xl lg:text-7xl leading-[1.1] tracking-tight">
              <span className="block text-white">{hero.titleLine1}</span>
              <span className="block text-gradient">{hero.titleLine2}</span>
            </h1>
            
            <p className="text-text-secondary text-lg sm:text-xl max-w-2xl mx-auto lg:mx-0 leading-relaxed">
              {hero.description}
            </p>

            <div className="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-4">
              <Link to={hero.primaryCta?.href || '/kayit'} className="w-full sm:w-auto px-8 py-4 bg-gradient-primary text-white rounded-xl font-bold text-lg shadow-[0_0_25px_rgba(244,159,32,0.4)] hover:shadow-[0_0_35px_rgba(218,44,46,0.6)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2 group">
                {hero.primaryCta?.label || 'Topluluğa Katıl'}
                <ArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
              </Link>
              <Link to={hero.secondaryCta?.href || '/events'} className="w-full sm:w-auto px-8 py-4 glass-card text-white rounded-xl font-bold text-lg hover:border-primary/50 hover:bg-white/5 transition-all flex items-center justify-center gap-2 group">
                <PlayCircle className="w-5 h-5 text-primary group-hover:scale-110 transition-transform" />
                {hero.secondaryCta?.label || 'Etkinlikleri Keşfet'}
              </Link>
            </div>
            
            {heroStats.length > 0 && (
              <div className="flex items-center justify-center lg:justify-start gap-8 pt-6">
                {heroStats.map((stat, idx) => (
                  <React.Fragment key={`${stat.label}-${idx}`}>
                    {idx > 0 && <div className="w-px h-10 bg-white/10"></div>}
                    <div>
                      <p className="text-3xl font-display font-bold text-white">{stat.value}</p>
                      <p className="text-text-meta text-sm font-medium">{stat.label}</p>
                    </div>
                  </React.Fragment>
                ))}
              </div>
            )}
          </div>

          {/* Right Content - Mascot & Countdown */}
          <div className="relative flex justify-center lg:justify-end mt-12 lg:mt-0">
            {/* Mascot Graphic */}
            <div className="relative w-full max-w-sm sm:max-w-md aspect-square flex items-center justify-center animate-[float_6s_ease-in-out_infinite]">
              <div className="absolute inset-0 bg-gradient-primary opacity-20 blur-[60px] rounded-full"></div>
              <img 
                src={brand.heroMascot || '/innomis/assets/innox/innox-kararli.png'}
                alt="Innox Mascot" 
                className="w-full h-auto relative z-10 drop-shadow-[0_0_50px_rgba(244,159,32,0.3)]"
              />
              


            </div>
          </div>
          
        </div>
      </div>
      
      {/* Custom float animation defined in style tag for simplicity */}
      <style dangerouslySetInnerHTML={{__html: `
        @keyframes float {
          0% { transform: translateY(0px); }
          50% { transform: translateY(-20px); }
          100% { transform: translateY(0px); }
        }
      `}} />
    </section>
  );
};

export default Hero;
