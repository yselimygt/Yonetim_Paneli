import React from 'react';
import { useSiteData } from '../context/SiteDataContext';
import { motion } from 'framer-motion';

const SocialLinksModule = () => {
  const { siteData } = useSiteData();
  const socialLinks = siteData?.settings?.social_links || [];

  if (!socialLinks || socialLinks.length === 0) return null;

  return (
    <>
      <style>
        {`
          .social-dock-item:hover {
            box-shadow: 0 0 25px var(--hover-color), inset 0 0 10px var(--hover-color);
            border-color: var(--hover-color);
          }
        `}
      </style>
      <div className="fixed left-6 top-1/2 -translate-y-1/2 z-50 flex flex-col gap-4 hidden md:flex">
        {socialLinks.map((link, idx) => (
          <motion.a
            key={idx}
            href={link.url}
            target="_blank"
            rel="noopener noreferrer"
            initial={{ opacity: 0, x: -50 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ delay: idx * 0.1, duration: 0.5, type: 'spring' }}
            whileHover={{ scale: 1.15, x: 5 }}
            whileTap={{ scale: 0.95 }}
            className="social-dock-item relative group flex items-center justify-center w-12 h-12 rounded-full bg-white/5 backdrop-blur-xl border border-white/10 shadow-[0_8px_32px_rgba(0,0,0,0.3)] transition-all duration-300"
            style={{ '--hover-color': link.color }}
          >
            <i className={`${link.icon} text-[22px] text-white/70 group-hover:text-white transition-colors duration-300`} />
            
            {/* Glow effect behind the icon */}
            <div 
              className="absolute inset-0 rounded-full opacity-0 group-hover:opacity-20 transition-opacity duration-300" 
              style={{ backgroundColor: link.color }}
            />

            {/* Tooltip */}
            <span 
              className="absolute left-full ml-4 px-3 py-1.5 rounded-lg bg-black/90 text-white text-xs font-semibold whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none border border-white/10 shadow-xl"
              style={{
                transform: 'translateX(-10px)',
                boxShadow: `0 4px 12px ${link.color}30`
              }}
            >
              {link.platform}
              <div className="absolute top-1/2 -left-1 -translate-y-1/2 w-2 h-2 bg-black/90 border-l border-b border-white/10 rotate-45" />
            </span>
          </motion.a>
        ))}
      </div>

      {/* Mobile Bottom Dock (Optional, if we want it on mobile. Maybe hide it or show it differently) */}
      <div className="fixed bottom-0 left-0 right-0 z-40 bg-black/80 backdrop-blur-xl border-t border-white/10 p-4 flex justify-around items-center md:hidden pb-safe">
        {socialLinks.map((link, idx) => (
          <motion.a
            key={idx}
            href={link.url}
            target="_blank"
            rel="noopener noreferrer"
            whileTap={{ scale: 0.9 }}
            className="flex flex-col items-center gap-1 group"
          >
            <div 
              className="w-10 h-10 rounded-full flex items-center justify-center bg-white/5 border border-white/10 transition-colors"
              style={{ '--hover-color': link.color }}
            >
              <i className={`${link.icon} text-lg text-white/70`} />
            </div>
            <span className="text-[10px] text-white/50">{link.platform}</span>
          </motion.a>
        ))}
      </div>
    </>
  );
};

export default SocialLinksModule;
