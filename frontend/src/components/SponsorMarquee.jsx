import React from 'react';
import { useSiteData } from '../context/SiteDataContext';

const SponsorMarquee = () => {
  const { siteData } = useSiteData();
  const sponsors = siteData?.settings?.sponsors || [];

  if (!sponsors.length) return null;

  return (
    <div className="py-3 sm:py-4 border-y border-white/5 bg-gray-50 dark:bg-surface/30 relative overflow-hidden flex flex-col justify-center">
      {/* Fade masks for smooth entry/exit */}
      <div className="absolute left-0 top-0 bottom-0 w-24 bg-gradient-to-r from-background to-transparent z-10 pointer-events-none"></div>
      <div className="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-background to-transparent z-10 pointer-events-none"></div>
      
      <div className="flex w-[200%] md:w-[150%] lg:w-[120%] animate-[marquee_15s_linear_infinite]">
        {/* Double array for infinite loop effect */}
        {[...sponsors, ...sponsors].map((sponsor, idx) => (
          <div 
            key={idx} 
            className="flex-1 flex justify-center items-center px-8"
          >
            <div className="flex items-center justify-center min-w-[140px] sm:min-w-[180px] h-12 sm:h-16 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl px-4 sm:px-6 hover:bg-white/10 hover:border-white/20 transition-all cursor-pointer group grayscale hover:grayscale-0">
              <img 
                src={sponsor} 
                alt="Sponsor Logo" 
                className="max-h-8 sm:max-h-10 max-w-[120px] sm:max-w-[150px] object-contain opacity-70 group-hover:opacity-100 transition-opacity"
              />
            </div>
          </div>
        ))}
      </div>

      <style dangerouslySetInnerHTML={{__html: `
        @keyframes marquee {
          0% { transform: translateX(0%); }
          100% { transform: translateX(-50%); }
        }
      `}} />
    </div>
  );
};

export default SponsorMarquee;
