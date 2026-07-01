import React from 'react';
import { useSiteData } from '../context/SiteDataContext';

const Achievements = () => {
  const { siteData } = useSiteData();
  const achievements = siteData?.achievements || [];
  return (
    <section className="py-16 lg:py-24 relative overflow-hidden" id="achievements">
      <div className="absolute left-0 top-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-primary/10 blur-[150px] rounded-full pointer-events-none"></div>

      <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="text-center mb-16">
          <h2 className="font-display text-4xl sm:text-5xl font-bold text-white">
            Topluluk yolculuğumuzda <span className="text-gradient">öne çıkan</span> anlar
          </h2>
        </div>

        <div className="relative mt-8 sm:mt-16">
          {/* Vertical Line */}
          <div className="absolute left-1/2 -translate-x-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-primary via-accent-purple to-transparent opacity-50"></div>

          <div className="space-y-6 sm:space-y-12">
            {achievements.map((item, idx) => (
              <div key={idx} className={`relative flex items-center w-full ${idx % 2 === 0 ? 'justify-start' : 'justify-end'}`}>
                
                {/* Timeline Dot */}
                <div className="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-background border-2 border-primary shadow-[0_0_10px_rgba(244,159,32,0.6)] z-10"></div>

                {/* Content Card */}
                <div className={`w-1/2 ${idx % 2 === 0 ? 'pr-4 sm:pr-12' : 'pl-4 sm:pl-12'}`}>
                  <div className="glass-card p-3 sm:p-6 hover:border-primary/30 transition-all hover:-translate-y-1">
                    <div className="flex flex-col 2xl:flex-row justify-between items-start 2xl:items-center gap-1 sm:gap-0 mb-2 sm:mb-4">
                      <span className="text-primary font-display font-bold text-lg sm:text-2xl">{item.year}</span>
                      {item.link && (
                        <a href={item.link} target="_blank" rel="noreferrer" className="px-1.5 py-0.5 sm:px-2 sm:py-1 rounded text-[8px] sm:text-[10px] font-bold uppercase tracking-wider border border-primary/20 text-primary hover:bg-primary/10 transition-colors">
                          Bağlantı
                        </a>
                      )}
                    </div>
                    <h4 className="text-white font-display font-semibold text-xs sm:text-xl mb-1 sm:mb-2">{item.title}</h4>
                    <p className="text-text-secondary text-[10px] sm:text-sm leading-tight sm:leading-relaxed">{item.description}</p>
                  </div>
                </div>

              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
};

export default Achievements;
