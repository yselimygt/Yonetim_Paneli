import React from 'react';
import { useSiteData } from '../context/SiteDataContext';

const Stats = () => {
  const { siteData } = useSiteData();
  const stats = siteData?.settings?.stats || [];
  return (
    <section className="py-12 border-y border-white/5 relative overflow-hidden bg-gradient-to-b from-surface/20 to-transparent">
      {/* Background glow */}
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-3xl h-[200px] bg-primary/10 blur-[100px] rounded-[100%] pointer-events-none"></div>
      
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className={`flex flex-wrap justify-center gap-3 sm:gap-8 mx-auto ${stats.length === 3 ? 'max-w-5xl' : ''}`}>
          {stats.map((stat, idx) => (
            <div key={idx} className={`glass-card p-4 sm:p-8 text-center group hover:border-white/20 transition-all flex flex-col justify-center w-[calc(50%-0.375rem)] sm:w-auto sm:flex-1 max-w-[280px] lg:max-w-none`}>
              <div className="text-3xl sm:text-5xl font-display font-extrabold text-gradient mb-1 sm:mb-3 drop-shadow-[0_0_15px_rgba(244,159,32,0.3)]">
                {stat.value}
              </div>
              <h4 className="text-white font-semibold text-sm sm:text-base mb-1 sm:mb-2">{stat.label}</h4>
              <p className="text-text-meta text-[11px] sm:text-sm">{stat.desc}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default Stats;
