import React from 'react';
import { Instagram, Linkedin, Youtube, Github, Globe, MessageCircle, ArrowUpRight } from 'lucide-react';
import { useSiteData } from '../context/SiteDataContext';

/* ── Custom Icons ── */
const XIcon = ({ className }) => (
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className={className}>
    <path d="M4 4l11.733 16h4.267l-11.733 -16z" /><path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772" />
  </svg>
);

const TikTokIcon = ({ className }) => (
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className={className}>
    <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5" />
  </svg>
);

const WhatsAppIcon = ({ className }) => (
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className={className}>
    <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" />
  </svg>
);

/* ── Link data ── */
const links = [
  {
    icon: <WhatsAppIcon className="w-5 h-5" />,
    label: 'WhatsApp Grubu',
    sub: 'Topluluğa katıl',
    href: 'https://chat.whatsapp.com/LrrOtKMDEBtGMEoMqF400w',
    color: 'from-green-500 to-emerald-600',
    glow: 'rgba(34,197,94,0.35)',
  },
  {
    icon: <Instagram className="w-5 h-5" />,
    label: 'Instagram',
    sub: '@neuinnomis',
    href: 'https://www.instagram.com/neuinnomis/',
    color: 'from-pink-500 to-purple-600',
    glow: 'rgba(236,72,153,0.35)',
  },
  {
    icon: <Linkedin className="w-5 h-5" />,
    label: 'LinkedIn',
    sub: 'InnoMIS Community',
    href: 'https://www.linkedin.com/company/innomis-community',
    color: 'from-blue-500 to-blue-700',
    glow: 'rgba(59,130,246,0.35)',
  },
  {
    icon: <Globe className="w-5 h-5" />,
    label: 'Resmi Web Sitesi',
    sub: 'innomis.tr',
    href: 'https://innomis.tr',
    color: 'from-orange-500 to-red-600',
    glow: 'rgba(244,159,32,0.35)',
  },
  {
    icon: <XIcon className="w-5 h-5" />,
    label: 'X (Twitter)',
    sub: '@neuinnomis',
    href: 'https://x.com/neuinnomis',
    color: 'from-zinc-400 to-zinc-600',
    glow: 'rgba(161,161,170,0.25)',
  },
  {
    icon: <Youtube className="w-5 h-5" />,
    label: 'YouTube',
    sub: '@neuinnomis',
    href: 'https://www.youtube.com/@neuinnomis',
    color: 'from-red-500 to-red-700',
    glow: 'rgba(239,68,68,0.35)',
  },
  {
    icon: <TikTokIcon className="w-5 h-5" />,
    label: 'TikTok',
    sub: '@neuinnomis',
    href: 'https://www.tiktok.com/@neuinnomis',
    color: 'from-pink-400 to-cyan-500',
    glow: 'rgba(236,72,153,0.25)',
  },
];

/* ── Social icon row (bottom) ── */
const socials = [
  { icon: <Instagram className="w-4 h-4" />, href: 'https://www.instagram.com/neuinnomis/', label: 'Instagram' },
  { icon: <XIcon className="w-4 h-4" />, href: 'https://x.com/neuinnomis', label: 'X' },
  { icon: <Youtube className="w-4 h-4" />, href: 'https://www.youtube.com/@neuinnomis', label: 'YouTube' },
  { icon: <TikTokIcon className="w-4 h-4" />, href: 'https://www.tiktok.com/@neuinnomis', label: 'TikTok' },
  { icon: <Linkedin className="w-4 h-4" />, href: 'https://www.linkedin.com/company/innomis-community', label: 'LinkedIn' },
];

export default function LinksPage() {
  const { siteData } = useSiteData();
  const brand = siteData?.settings?.brand || {};
  const contact = siteData?.settings?.contact || {};
  const contactSocials = contact.socials || {};
  const socialKey = (label) => {
    const normalized = label.toLowerCase();
    if (normalized.startsWith('x')) return 'x';
    return normalized;
  };
  const hrefFor = (link) => contactSocials[socialKey(link.label)] || link.href;
  const dynamicLinks = links.map((link) => ({ ...link, href: hrefFor(link) }));

  return (
    <div
      className="min-h-screen flex flex-col items-center px-4 py-10 relative overflow-hidden"
      style={{ background: '#0C0A09', fontFamily: "'Space Grotesk', 'Manrope', sans-serif" }}
    >
      {/* Background glows */}
      <div
        className="pointer-events-none fixed inset-0 z-0"
        aria-hidden
        style={{
          background:
            'radial-gradient(ellipse 60% 35% at 50% -10%, rgba(244,159,32,0.18), transparent),' +
            'radial-gradient(ellipse 50% 30% at 100% 90%, rgba(218,44,46,0.12), transparent)',
        }}
      />

      {/* Animated ring behind logo */}
      <div className="relative z-10 mt-4 mb-6 flex items-center justify-center">
        <div
          className="absolute w-32 h-32 rounded-full"
          style={{
            background: 'conic-gradient(from 0deg, #F49F20, #DA2C2E, #F49F20)',
            animation: 'spin 6s linear infinite',
            filter: 'blur(8px)',
            opacity: 0.6,
          }}
        />
        <img
          src={brand.popupLogo || '/innomis/assets/logo/Ana-Logo-Arma.png'}
          alt={brand.brandName || 'InnoMIS Community'}
          className="relative w-24 h-24 rounded-full object-contain z-10"
          style={{ background: '#fff', padding: '4px' }}
        />
      </div>

      {/* Name & bio */}
      <div className="relative z-10 text-center mb-8 space-y-1">
        <h1 className="text-2xl font-bold text-white tracking-tight">{brand.brandName || 'InnoMIS Community'}</h1>
        <p className="text-sm" style={{ color: '#A1A1AA' }}>
          Bilişimde Yeniliğe, İnovasyonda Zirveye!
        </p>
        <p className="text-xs mt-1" style={{ color: '#71717A' }}>
          Necmettin Erbakan Üniversitesi · Konya
        </p>
      </div>

      {/* Link cards */}
      <div className="relative z-10 w-full" style={{ maxWidth: '480px' }}>
        <div className="flex flex-col gap-3">
          {dynamicLinks.map((link, i) => (
            <a
              key={i}
              href={link.href}
              target="_blank"
              rel="noreferrer"
              className="group flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 active:scale-[0.98]"
              style={{
                background: 'rgba(255,255,255,0.04)',
                border: '1px solid rgba(255,255,255,0.08)',
                backdropFilter: 'blur(12px)',
                WebkitBackdropFilter: 'blur(12px)',
                boxShadow: '0 2px 16px rgba(0,0,0,0.4)',
              }}
              onMouseEnter={e => {
                e.currentTarget.style.boxShadow = `0 0 20px ${link.glow}, 0 2px 16px rgba(0,0,0,0.4)`;
                e.currentTarget.style.border = '1px solid rgba(255,255,255,0.15)';
                e.currentTarget.style.background = 'rgba(255,255,255,0.07)';
              }}
              onMouseLeave={e => {
                e.currentTarget.style.boxShadow = '0 2px 16px rgba(0,0,0,0.4)';
                e.currentTarget.style.border = '1px solid rgba(255,255,255,0.08)';
                e.currentTarget.style.background = 'rgba(255,255,255,0.04)';
              }}
            >
              {/* Color stripe + icon */}
              <div
                className={`w-10 h-10 rounded-xl flex items-center justify-center text-white shrink-0 bg-gradient-to-br ${link.color}`}
                style={{ boxShadow: `0 0 12px ${link.glow}` }}
              >
                {link.icon}
              </div>

              {/* Text */}
              <div className="flex-1 min-w-0">
                <p className="text-sm font-semibold text-white leading-tight truncate">{link.label}</p>
                <p className="text-xs mt-0.5 truncate" style={{ color: '#71717A' }}>{link.sub}</p>
              </div>

              {/* Arrow */}
              <ArrowUpRight
                className="w-4 h-4 shrink-0 opacity-30 group-hover:opacity-80 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-all"
                style={{ color: '#fff' }}
              />
            </a>
          ))}
        </div>
      </div>

      {/* Footer - VipeRaDev signature */}
      <div className="relative z-10 mt-10 flex items-center justify-center gap-2 text-xs" style={{ color: '#52525B' }}>
        <span>Powered by</span>
        <a href="https://viperadev.com" target="_blank" rel="noopener noreferrer" className="group flex items-center">
          <img
            src="https://viperadev.com/ViperaDev-aydinlik.png"
            alt="ViperaDev"
            className="h-5 w-auto opacity-50 group-hover:opacity-100 transition-all duration-300 dark:hidden"
          />
          <img
            src="https://viperadev.com/ViperaDev-Logo.png"
            alt="ViperaDev"
            className="h-5 w-auto opacity-50 brightness-0 invert group-hover:opacity-100 group-hover:brightness-100 group-hover:invert-0 transition-all duration-300 hidden dark:block"
          />
        </a>
      </div>

      {/* Spin keyframe */}
      <style>{`@keyframes spin { to { transform: rotate(360deg); } }`}</style>
    </div>
  );
}
