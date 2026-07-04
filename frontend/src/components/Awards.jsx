import React from 'react';
import { Trophy, Award, Medal, Target, Star, Crown } from 'lucide-react';
import { useSiteData } from '../context/SiteDataContext';

const Awards = () => {
  const { siteData } = useSiteData();
  const awards = siteData?.awards || [];
  return (
    <section className="py-16 lg:py-24 relative overflow-hidden">
      <div className="absolute right-0 top-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-accent-yellow/10 blur-[150px] rounded-full pointer-events-none"></div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="text-center mb-16">
          <h2 className="font-display text-4xl sm:text-5xl font-bold text-white">
            Gurur Tablomuz: <span className="text-accent-yellow">Ödüllerimiz</span>
          </h2>
          <p className="mt-4 text-text-secondary text-lg">
            Başarımızın ve emeğimizin ekosistemdeki yansımaları.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {awards.map((item, idx) => (
            <div key={idx} className="glass-card p-8 rounded-2xl group hover:-translate-y-2 transition-transform hover:border-accent-yellow/30 relative overflow-hidden">
              <div className="absolute top-0 right-0 w-32 h-32 bg-accent-yellow/5 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-accent-yellow/10 transition-colors"></div>
              
              <div className="w-14 h-14 bg-accent-yellow/20 rounded-xl flex items-center justify-center mb-6 text-accent-yellow relative z-10">
                {item.icon === 'Trophy' ? <Trophy className="w-7 h-7" /> : 
                 item.icon === 'Medal' ? <Medal className="w-7 h-7" /> : 
                 item.icon === 'Target' ? <Target className="w-7 h-7" /> : 
                 item.icon === 'Star' ? <Star className="w-7 h-7" /> : 
                 item.icon === 'Crown' ? <Crown className="w-7 h-7" /> : 
                 <Award className="w-7 h-7" />}
              </div>
              
              <div className="flex items-center gap-3 mb-4">
                <span className="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-sm font-semibold text-white">{item.year}</span>
                {item.link && (
                  <a href={item.link} target="_blank" rel="noreferrer" className="text-accent-yellow/80 hover:text-accent-yellow text-sm font-medium transition-colors">Bağlantı</a>
                )}
              </div>
              
              <h3 className="text-xl font-display font-bold text-white mb-3 relative z-10">{item.title}</h3>
              <p className="text-text-secondary leading-relaxed relative z-10">{item.description}</p>

              {item.institution_name && (
                <div className="mt-5 flex items-center gap-2 bg-white/5 border border-white/5 py-1.5 px-3 rounded-lg w-fit relative z-10">
                  {item.institution_logo_url && (
                    <img src={item.institution_logo_url} alt="Kurum Logosu" className="w-5 h-5 object-contain" />
                  )}
                  <span className="text-xs font-semibold text-white/90">{item.institution_name}</span>
                </div>
              )}

              {item.image_url && (
                <div className="mt-6 relative z-10 w-full flex justify-center bg-white/5 rounded-xl p-2 border border-white/5">
                  <img 
                    src={item.image_url} 
                    alt={item.title} 
                    className="w-full h-auto object-contain rounded-lg max-h-64" 
                  />
                </div>
              )}
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default Awards;
