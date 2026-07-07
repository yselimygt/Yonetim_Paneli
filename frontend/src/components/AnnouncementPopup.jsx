import React, { useState, useEffect, useRef } from 'react';
import { Link } from 'react-router-dom';
import { X, Sparkles } from 'lucide-react';
import { useSiteData } from '../context/SiteDataContext';

export default function AnnouncementPopup() {
  const { siteData, loading } = useSiteData();
  const popupConfig = siteData?.settings?.popupConfig;
  const popupLogo = siteData?.settings?.brand?.popupLogo || '/innomis/assets/logo/Ana-Logo-Arma.png';

  const [visible, setVisible] = useState(false);
  const [closing, setClosing] = useState(false);
  const [countdown, setCountdown] = useState(0);
  const timerRef = useRef(null);
  const countRef = useRef(null);

  useEffect(() => {
    if (popupConfig) {
      setCountdown(popupConfig.autoCloseSec || 5);
    }
  }, [popupConfig]);

  useEffect(() => {
    if (loading || !popupConfig || !popupConfig.enabled) return;
    const seen = localStorage.getItem(popupConfig.storageKey);
    if (seen) return;

    const show = setTimeout(() => setVisible(true), 800);
    return () => clearTimeout(show);
  }, [loading, popupConfig]);

  useEffect(() => {
    if (!visible || !popupConfig) return;

    countRef.current = setInterval(() => {
      setCountdown(prev => {
        if (prev <= 1) {
          clearInterval(countRef.current);
          return 0;
        }
        return prev - 1;
      });
    }, 1000);

    timerRef.current = setTimeout(() => handleClose(), popupConfig.autoCloseSec * 1000);

    return () => {
      clearTimeout(timerRef.current);
      clearInterval(countRef.current);
    };
  }, [visible, popupConfig]);

  const handleClose = () => {
    if (!popupConfig) return;
    setClosing(true);
    setTimeout(() => {
      setVisible(false);
      setClosing(false);
      localStorage.setItem(popupConfig.storageKey, 'true');
    }, 300);
  };

  if (!visible) return null;

  const progress = ((popupConfig.autoCloseSec - countdown) / popupConfig.autoCloseSec) * 100;
  const primaryCta = popupConfig.cta || {};
  const secondaryCta = popupConfig.secondaryCta || {};
  const hasPrimaryCta = primaryCta.label && primaryCta.href;
  const hasSecondaryCta = secondaryCta.label && secondaryCta.href;

  return (
    <>
      {/* Backdrop */}
      <div
        onClick={handleClose}
        className="fixed inset-0 z-[9998] bg-black/60 backdrop-blur-sm"
        style={{
          opacity: closing ? 0 : 1,
          transition: 'opacity 0.3s ease',
        }}
      />

      {/* Popup Card */}
      <div
        className="fixed z-[9999] inset-0 flex items-center justify-center px-4"
        style={{ pointerEvents: closing ? 'none' : 'auto' }}
      >
        <div
          className="relative w-full max-w-md rounded-3xl overflow-hidden"
          style={{
            background: 'rgba(28,25,23,0.96)',
            border: '1px solid rgba(244,159,32,0.25)',
            boxShadow: '0 0 60px rgba(244,159,32,0.15), 0 25px 50px rgba(0,0,0,0.6)',
            backdropFilter: 'blur(20px)',
            transform: closing ? 'scale(0.95) translateY(8px)' : 'scale(1) translateY(0)',
            opacity: closing ? 0 : 1,
            transition: 'all 0.3s cubic-bezier(0.16,1,0.3,1)',
          }}
        >
          {/* Progress bar (auto-close countdown) */}
          <div className="absolute top-0 left-0 right-0 h-0.5 bg-white/5">
            <div
              className="h-full bg-gradient-to-r from-primary to-secondary transition-all duration-1000 ease-linear"
              style={{ width: `${progress}%` }}
            />
          </div>

          {/* Decorative glow */}
          <div
            className="absolute -top-16 -right-16 w-48 h-48 rounded-full pointer-events-none"
            style={{ background: 'radial-gradient(circle, rgba(244,159,32,0.2), transparent 70%)' }}
          />

          {/* Close button */}
          <button
            onClick={handleClose}
            className="absolute top-4 right-4 w-8 h-8 rounded-xl flex items-center justify-center text-white/40 hover:text-white hover:bg-white/10 transition-all duration-200 z-10"
            aria-label="Kapat"
          >
            <X className="w-4 h-4" />
          </button>

          {/* Top Banner Image (Optional) */}
          {popupConfig.image && (
            <div className="w-full relative bg-zinc-900 border-b border-zinc-800">
              <img 
                src={popupConfig.image} 
                alt="Duyuru Görseli" 
                className="w-full h-auto max-h-56 object-cover" 
              />
            </div>
          )}

          {/* Content */}
          <div className={`p-7 ${popupConfig.image ? 'pt-6' : 'pt-8'}`}>
            {/* Badge */}
            <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold mb-5"
              style={{ background: 'rgba(244,159,32,0.15)', border: '1px solid rgba(244,159,32,0.3)', color: '#F49F20' }}>
              <Sparkles className="w-3 h-3" />
              {popupConfig.badge}
            </div>

            {/* Logo + Title */}
            <div className="flex items-start gap-4 mb-4">
              <img
                src={popupLogo}
                alt="InnoMIS"
                className="w-14 h-14 rounded-2xl object-contain shrink-0"
                style={{ background: '#fff', padding: '4px' }}
              />
              <div>
                <h2 className="font-display text-xl font-bold text-white leading-tight">
                  {popupConfig.title}
                </h2>
                <p className="text-xs mt-1" style={{ color: '#71717A' }}>innomis.tr</p>
              </div>
            </div>

            {/* Description */}
            <p className="text-sm leading-relaxed mb-6" style={{ color: '#A1A1AA' }}>
              {popupConfig.description}
            </p>

            {/* CTA buttons */}
            {(hasPrimaryCta || hasSecondaryCta) && (
              <div className="flex gap-3">
                {hasPrimaryCta && (
                  <Link
                    to={primaryCta.href}
                    onClick={handleClose}
                    className="flex-1 text-center py-2.5 rounded-xl text-sm font-semibold text-white transition-all duration-200 hover:brightness-110 active:scale-95"
                    style={{ background: 'linear-gradient(to right, #F49F20, #DA2C2E)' }}
                  >
                    {primaryCta.label}
                  </Link>
                )}
                {hasSecondaryCta && (
                  <Link
                    to={secondaryCta.href}
                    onClick={handleClose}
                    className="flex-1 text-center py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 hover:bg-white/10 active:scale-95"
                    style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)', color: '#D4D4D8' }}
                  >
                    {secondaryCta.label}
                  </Link>
                )}
              </div>
            )}

            {/* Countdown hint */}
            <p className="text-center text-xs mt-4" style={{ color: '#52525B' }}>
              {countdown > 0 ? `${countdown} saniye sonra kapanacak` : 'Kapanıyor…'}
            </p>
          </div>
        </div>
      </div>
    </>
  );
}
