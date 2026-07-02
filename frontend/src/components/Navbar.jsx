import React, { useState, useEffect } from 'react';
import { Menu, X, Globe, Heart } from 'lucide-react';
import { Link } from 'react-router-dom';
import { navLinks } from '../data/innomisData';
import { useSiteData } from '../context/SiteDataContext';

const Navbar = () => {
  const { siteData } = useSiteData();
  const [isOpen, setIsOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const navbarLogo = siteData?.settings?.brand?.navbarLogo || '/innomis/assets/logo/Yatay-Beyaz.png';

  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 50);
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  // Lock body scroll when mobile menu is open
  useEffect(() => {
    if (isOpen) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = 'unset';
    }
    return () => {
      document.body.style.overflow = 'unset';
    };
  }, [isOpen]);

  return (
    <>
      <nav className={`fixed top-0 w-full z-50 transition-all duration-300 ${scrolled ? 'glass-nav py-3' : 'bg-transparent py-5'}`}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center">
          
          <div className={`flex-shrink-0 flex items-center gap-2 cursor-pointer transition-opacity duration-300 ${isOpen ? 'opacity-0 pointer-events-none' : 'opacity-100'}`}>
            <Link to="/" className="flex items-center gap-2">
              <img 
                src={navbarLogo}
                alt="InnoMIS Logo" 
                className="h-10 w-auto transition-all"
              />
            </Link>
          </div>

          <div className="hidden lg:flex items-center space-x-8">
            {navLinks.map((link) => (
              <Link
                key={link.name}
                to={link.href}
                className="text-text-secondary hover:text-text-main transition-colors relative group font-medium text-sm"
              >
                {link.name}
                <span className="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-primary transition-all duration-300 group-hover:w-full rounded-full shadow-[0_0_10px_rgba(244,159,32,0.6)]"></span>
              </Link>
            ))}
          </div>

          <div className="hidden lg:flex items-center space-x-4">
            <button aria-label="Language Toggle" className="text-text-secondary hover:text-primary transition-colors">
              <Globe className="w-5 h-5" />
            </button>
            <Link to="/basvuru" className="px-5 py-2.5 border border-primary/50 text-white hover:bg-primary hover:border-primary rounded-xl font-semibold transition-all">
              Ekibe Katıl
            </Link>
            <Link to="/kayit" className="px-5 py-2.5 bg-gradient-primary text-white rounded-xl font-semibold shadow-[0_0_20px_rgba(244,159,32,0.3)] hover:shadow-[0_0_25px_rgba(218,44,46,0.5)] hover:-translate-y-0.5 transition-all">
              Topluluğa Katıl
            </Link>
          </div>

          <div className="lg:hidden flex items-center">
            <button
              onClick={() => setIsOpen(!isOpen)}
              className="text-text-secondary hover:text-text-main p-2"
              aria-expanded={isOpen}
              aria-label="Toggle Menu"
            >
              {isOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
            </button>
          </div>
        </div>
        </div>
      </nav>

      {/* Mobile Menu Drawer */}
      <div className={`lg:hidden fixed inset-0 z-[60] bg-background/95 backdrop-blur-xl transition-transform duration-300 ${isOpen ? 'translate-x-0' : 'translate-x-full'} pt-6 px-6 overflow-y-auto`}>
        <div className="flex flex-col h-full space-y-6">
          <div className="flex items-center justify-between mb-4">
            <Link to="/" onClick={() => setIsOpen(false)}>
              <img 
                src={navbarLogo}
                alt="InnoMIS Logo" 
                className="h-10 w-auto"
              />
            </Link>
            <button
              onClick={() => setIsOpen(false)}
              className="text-text-secondary hover:text-white p-2"
              aria-label="Close Menu"
            >
              <X className="w-7 h-7" />
            </button>
          </div>
          <Link
            to="/"
            onClick={() => setIsOpen(false)}
            className="text-2xl font-display font-semibold text-text-main hover:text-primary transition-colors"
          >
            Ana Sayfa
          </Link>
          {navLinks.map((link) => (
            <Link
              key={link.name}
              to={link.href}
              onClick={() => setIsOpen(false)}
              className="text-2xl font-display font-semibold text-text-main hover:text-primary transition-colors"
            >
              {link.name}
            </Link>
          ))}
          <div className="mt-8 flex items-center space-x-6 border-t border-white/10 pt-8">
            <button className="flex items-center gap-2 text-text-secondary hover:text-white">
              <Globe className="w-5 h-5" /> Dil
            </button>
          </div>
          <div className="mt-auto flex flex-row gap-3 mb-6">
            <Link to="/basvuru" onClick={() => setIsOpen(false)} className="flex-1 py-3.5 px-2 border border-primary/50 text-white hover:bg-primary hover:border-primary rounded-xl font-bold text-sm sm:text-base text-center transition-all flex items-center justify-center">
              Ekibe Katıl
            </Link>
            <Link to="/kayit" onClick={() => setIsOpen(false)} className="flex-1 py-3.5 px-2 bg-gradient-primary text-white rounded-xl font-bold text-sm sm:text-base shadow-[0_0_20px_rgba(244,159,32,0.3)] text-center flex items-center justify-center">
              Topluluğa Katıl
            </Link>
          </div>
          
          <div className="pb-8 flex justify-center">
            <div className="flex items-center gap-2 text-sm text-text-meta">
              <span>Powered by</span>
              <a href="https://viperadev.com" target="_blank" rel="noopener noreferrer" className="group flex items-center">
                <img 
                  src="https://viperadev.com/ViperaDev-Logo.png" 
                  alt="ViperaDev" 
                  className="h-6 w-auto opacity-70 brightness-0 invert group-hover:opacity-100 group-hover:brightness-100 group-hover:invert-0 transition-all duration-300" 
                />
              </a>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default Navbar;
