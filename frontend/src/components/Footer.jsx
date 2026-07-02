import React from 'react';
import { Link } from 'react-router-dom';
import { footerLinks, socials } from '../data/innomisData';
import { useSiteData } from '../context/SiteDataContext';

const Footer = () => {
  const { siteData } = useSiteData();
  const brand = siteData?.settings?.brand || {};
  const contactSocials = siteData?.settings?.contact?.socials || {};
  const socialKey = (label) => {
    const normalized = label.toLowerCase();
    if (normalized.startsWith('x')) return 'x';
    return normalized;
  };
  const socialHref = (social) => contactSocials[socialKey(social.label)] || social.href;

  return (
    <footer className="border-t border-white/10 bg-gray-50 dark:bg-surface/50 pt-16 pb-8 relative overflow-hidden">
      {/* Decorative Glow */}
      <div className="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-px bg-gradient-to-r from-transparent via-primary/50 to-transparent"></div>
      <div className="absolute top-0 left-1/2 -translate-x-1/2 w-1/4 h-[100px] bg-primary/20 blur-[100px] rounded-full pointer-events-none"></div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
          
          {/* Col 1: Brand */}
          <div className="space-y-6">
            <div className="flex items-center gap-3 mb-6">
              <img 
                src={brand.footerLogo || '/innomis/assets/logo/Yatay-Beyaz.png'}
                alt={brand.brandName || 'InnoMIS Logo'}
                className="h-12 w-auto"
              />
            </div>
            <p className="text-text-secondary leading-relaxed text-sm">
              {brand.footerDescription || 'Teknoloji, inovasyon ve kariyer odaklı öğrenci topluluğu. Öğrenen, üreten ve paylaşan öğrencileri aynı çatı altında buluşturur.'}
            </p>
            <div className="flex items-center space-x-4">
              {socials.map((social, idx) => (
                <a 
                  key={idx} 
                  href={socialHref(social)}
                  className="w-10 h-10 rounded-full glass-card flex items-center justify-center text-text-secondary hover:text-primary hover:border-primary/50 hover:shadow-[0_0_15px_rgba(244,159,32,0.3)] transition-all duration-300"
                  aria-label={social.label}
                >
                  {social.icon}
                </a>
              ))}
            </div>
          </div>

          {/* Col 2: Quick Links */}
          <div>
            <h4 className="text-white font-display font-semibold mb-6">Kurumsal</h4>
            <ul className="space-y-4">
              {footerLinks.corporate.map((link, idx) => (
                <li key={idx}>
                  {link.href.startsWith('http') ? (
                    <a href={link.href} className="text-text-secondary hover:text-primary transition-colors text-sm">
                      {link.name}
                    </a>
                  ) : (
                    <Link to={link.href} className="text-text-secondary hover:text-primary transition-colors text-sm">
                      {link.name}
                    </Link>
                  )}
                </li>
              ))}
            </ul>
          </div>

          {/* Col 3: Community */}
          <div>
            <h4 className="text-white font-display font-semibold mb-6">Etkinlik & Başvuru</h4>
            <ul className="space-y-4">
              {footerLinks.events.map((link, idx) => (
                <li key={idx}>
                  {link.href.startsWith('http') ? (
                    <a href={link.href} className="text-text-secondary hover:text-primary transition-colors text-sm">
                      {link.name}
                    </a>
                  ) : (
                    <Link to={link.href} className="text-text-secondary hover:text-primary transition-colors text-sm">
                      {link.name}
                    </Link>
                  )}
                </li>
              ))}
            </ul>
          </div>

          {/* Col 4: Newsletter */}
          <div>
            <h4 className="text-white font-display font-semibold mb-6">Bülten</h4>
            <p className="text-text-secondary text-sm mb-4">Etkinliklerden ve gelişmelerden ilk siz haberdar olun.</p>
            <form className="space-y-3" onSubmit={(e) => e.preventDefault()}>
              <input 
                type="email" 
                placeholder="E-posta adresiniz" 
                className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-text-meta focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/50 transition-all"
              />
              <button type="submit" className="w-full py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl font-medium transition-colors border border-white/5 hover:border-white/20">
                Abone Ol
              </button>
            </form>
          </div>

        </div>

        {/* Bottom Bar */}
        <div className="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-6 md:gap-4">
          <p className="text-text-meta text-sm text-center md:text-left">
            &copy; {new Date().getFullYear()} {brand.brandName || 'InnoMIS Community'}. Tüm hakları saklıdır.
          </p>

          <div className="flex items-center gap-2 text-sm text-text-meta">
            <span>Powered by</span>
            <a href="https://viperadev.com" target="_blank" rel="noopener noreferrer" className="group flex items-center">
              <img 
                src="https://viperadev.com/ViperaDev-Logo.png" 
                alt="ViperaDev" 
                className="h-6 sm:h-7 w-auto opacity-70 brightness-0 invert group-hover:opacity-100 group-hover:brightness-100 group-hover:invert-0 transition-all duration-300" 
              />
            </a>
          </div>

          <div className="flex flex-wrap justify-center md:justify-end gap-4 sm:gap-6 text-sm text-text-meta">
            {footerLinks.legal.map((link, idx) => (
              <Link key={idx} to={link.href} className="hover:text-white transition-colors">
                {link.name}
              </Link>
            ))}
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
