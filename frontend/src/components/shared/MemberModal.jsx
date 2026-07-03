import React, { useState } from 'react';
import { Linkedin, X, Mail, Phone, Globe, GraduationCap, Github, Share2, Check, ChevronUp, Briefcase } from 'lucide-react';

const MemberModal = ({ member, onClose }) => {
  const [copied, setCopied] = useState(false);
  const [showHistory, setShowHistory] = useState(false);

  if (!member) return null;

  const hasHistory = member.communityHistory && member.communityHistory.length > 0;

  const handleShare = (e) => {
    e.stopPropagation();
    navigator.clipboard.writeText(window.location.href);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  return (
    <div
      className="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-black/60 backdrop-blur-sm animate-fade-in"
      onClick={onClose}
    >
      <div
        className="bg-surface border border-white/10 rounded-3xl w-full max-w-md relative overflow-hidden shadow-2xl"
        style={{ maxHeight: '90vh' }}
        onClick={(e) => e.stopPropagation()}
      >
        {/* Header Glow */}
        <div className="absolute top-0 left-0 right-0 h-32 bg-gradient-to-b from-primary/20 to-transparent opacity-50 pointer-events-none" />

        {/* Close Button */}
        <button
          onClick={onClose}
          className="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-white/5 hover:bg-white/10 text-text-meta hover:text-white transition-all duration-200 z-50 cursor-pointer"
          style={{ opacity: showHistory ? 0 : 1, pointerEvents: showHistory ? 'none' : 'auto' }}
        >
          <X className="w-4 h-4" />
        </button>

        {/* Share Button */}
        <button
          onClick={handleShare}
          className="absolute top-4 right-14 w-8 h-8 flex items-center justify-center rounded-full bg-white/5 hover:bg-white/10 text-text-meta hover:text-white transition-all duration-200 z-50 cursor-pointer"
          title="Profili Paylaş"
          style={{ opacity: showHistory ? 0 : 1, pointerEvents: showHistory ? 'none' : 'auto' }}
        >
          {copied ? <Check className="w-4 h-4 text-primary" /> : <Share2 className="w-4 h-4" />}
        </button>

        {/* ── Front Panel ── */}
        <div className="p-6 sm:p-8 pt-12 relative z-10 overflow-y-auto custom-scrollbar" style={{ maxHeight: '90vh' }}>
          {/* Avatar */}
          <div className="flex justify-center mb-6">
            <div className="w-24 h-24 rounded-full bg-white/5 border border-white/10 flex items-center justify-center relative">
              {member.image_url ? (
                <img src={member.image_url} alt="" className="w-full h-full rounded-full object-cover" />
              ) : (
                <span className="text-3xl font-display font-bold text-white/90">{member.initials}</span>
              )}
              <div className="absolute inset-0 rounded-full bg-primary/20 blur-md -z-10" />
            </div>
          </div>

          {/* Info */}
          <div className="text-center mb-8">
            <h3 className="text-2xl font-display font-bold text-white mb-1">{member.name}</h3>
            <p className="text-primary font-medium text-sm">{member.role}</p>
            {member.currentJob && (
              <p className="text-white/80 font-medium text-sm mt-1">{member.currentJob}</p>
            )}
            {member.bio && (
              <p className="text-white/70 text-xs sm:text-sm mt-3 leading-relaxed max-w-sm mx-auto">{member.bio}</p>
            )}
          </div>

          {/* Details List */}
          <div className="space-y-4">
            {/* Education Block */}
            {(member.universityLogo || member.companyLogo || member.faculty || member.department || member.grade) && (
              <div className="flex flex-row items-stretch gap-3 sm:gap-4">
                <div className="w-24 h-24 sm:w-28 sm:h-28 shrink-0 bg-white border border-white/10 rounded-xl flex items-center justify-center p-2 sm:p-3">
                  {(member.companyLogo || member.universityLogo) ? (
                    <img src={member.companyLogo || member.universityLogo} alt="Kurum Logosu" className="w-full h-full object-contain drop-shadow-md" />
                  ) : (
                    <div className="flex flex-col items-center justify-center text-text-meta/50">
                      <GraduationCap className="w-7 h-7 sm:w-8 sm:h-8 mb-1 opacity-50" />
                      <span className="text-[9px] sm:text-[10px] font-semibold opacity-50">LOGO</span>
                    </div>
                  )}
                </div>
                <div className="flex-1 flex flex-col justify-between gap-1.5 sm:gap-2 overflow-hidden py-0">
                  {member.faculty && (
                    <div className="flex-1 w-full bg-white/5 border border-white/10 rounded-lg flex items-center justify-center px-2 text-center overflow-hidden">
                      <p className="text-[10px] sm:text-xs text-white font-medium truncate w-full" title={member.faculty}>{member.faculty}</p>
                    </div>
                  )}
                  {member.department && (
                    <div className="flex-1 w-full bg-white/5 border border-white/10 rounded-lg flex items-center justify-center px-2 text-center overflow-hidden">
                      <p className="text-[10px] sm:text-xs text-white font-medium truncate w-full" title={member.department}>{member.department}</p>
                    </div>
                  )}
                  {member.grade && (
                    <div className="flex-1 w-full bg-white/5 border border-white/10 rounded-lg flex items-center justify-center px-2 text-center overflow-hidden">
                      <p className="text-[10px] sm:text-xs text-white font-medium truncate w-full" title={member.grade}>{member.grade}</p>
                    </div>
                  )}
                </div>
              </div>
            )}

            {member.email && (
              <div className="flex items-center gap-4 p-3 rounded-xl bg-white/5 border border-white/5">
                <Mail className="w-5 h-5 text-primary shrink-0" />
                <div className="flex-1 min-w-0 text-left">
                  <p className="text-[10px] text-text-meta uppercase tracking-wider font-semibold mb-0.5">E-Posta</p>
                  <p className="text-sm text-white truncate">
                    <a href={`mailto:${member.email}`} className="hover:text-primary transition-colors">{member.email}</a>
                  </p>
                </div>
              </div>
            )}

            {member.phone && (
              <div className="flex items-center gap-4 p-3 rounded-xl bg-white/5 border border-white/5">
                <Phone className="w-5 h-5 text-primary shrink-0" />
                <div className="flex-1 min-w-0 text-left">
                  <p className="text-[10px] text-text-meta uppercase tracking-wider font-semibold mb-0.5">Telefon</p>
                  <p className="text-sm text-white truncate">
                    <a href={`tel:${member.phone.replace(/\s+/g, '')}`} className="hover:text-primary transition-colors">{member.phone}</a>
                  </p>
                </div>
              </div>
            )}

            {(member.website || member.linkedin || member.github) && (
              <div className="bg-white/5 border border-white/5 rounded-xl p-3 mt-2">
                <p className="text-[10px] text-text-meta uppercase tracking-wider font-semibold mb-2.5">Bağlantılar</p>
                <div className="flex gap-1.5 sm:gap-2">
                  {member.website && (
                    <a href={member.website.includes('http') ? member.website : `https://${member.website}`} target="_blank" rel="noopener noreferrer"
                      className="flex-1 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-white/10 transition-all rounded-xl p-2 sm:p-3 flex flex-col items-center justify-center gap-1.5 sm:gap-2 group">
                      <Globe className="w-4 h-4 sm:w-5 sm:h-5 text-text-meta group-hover:text-primary transition-colors" />
                      <span className="text-[9px] sm:text-[10px] uppercase tracking-wider font-semibold text-text-meta group-hover:text-white transition-colors">Web</span>
                    </a>
                  )}
                  {member.linkedin && member.linkedin !== '#' && (
                    <a href={member.linkedin} target="_blank" rel="noopener noreferrer"
                      className="flex-1 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-white/10 transition-all rounded-xl p-2 sm:p-3 flex flex-col items-center justify-center gap-1.5 sm:gap-2 group">
                      <Linkedin className="w-4 h-4 sm:w-5 sm:h-5 text-text-meta group-hover:text-primary transition-colors" />
                      <span className="text-[9px] sm:text-[10px] uppercase tracking-wider font-semibold text-text-meta group-hover:text-white transition-colors">LinkedIn</span>
                    </a>
                  )}
                  {member.github && member.github !== '#' && (
                    <a href={member.github} target="_blank" rel="noopener noreferrer"
                      className="flex-1 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-white/10 transition-all rounded-xl p-2 sm:p-3 flex flex-col items-center justify-center gap-1.5 sm:gap-2 group">
                      <Github className="w-4 h-4 sm:w-5 sm:h-5 text-text-meta group-hover:text-primary transition-colors" />
                      <span className="text-[9px] sm:text-[10px] uppercase tracking-wider font-semibold text-text-meta group-hover:text-white transition-colors">GitHub</span>
                    </a>
                  )}
                </div>
              </div>
            )}
          </div>

          {/* Career History Trigger */}
          {hasHistory && (
            <button
              onClick={() => setShowHistory(true)}
              className="mt-6 w-full flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-semibold transition-all duration-200 hover:brightness-110 active:scale-[0.98]"
              style={{ background: 'rgba(244,159,32,0.1)', border: '1px solid rgba(244,159,32,0.25)', color: '#F49F20' }}
            >
              <Briefcase className="w-4 h-4" />
              Topluluk Kariyer Geçmişi
              <ChevronUp className="w-4 h-4" />
            </button>
          )}
        </div>

        {/* ── Career History Slide-Up Panel ── */}
        {hasHistory && (
          <div
            className="absolute inset-0 rounded-3xl z-20 flex flex-col"
            style={{
              background: '#1C1917',
              transform: showHistory ? 'translateY(0)' : 'translateY(100%)',
              transition: 'transform 0.38s cubic-bezier(0.16, 1, 0.3, 1)',
            }}
          >
            {/* Panel Header */}
            <div className="flex items-center gap-3 px-6 pt-6 pb-4 border-b border-white/10 shrink-0">
              <button
                onClick={() => setShowHistory(false)}
                className="w-8 h-8 flex items-center justify-center rounded-full bg-white/5 hover:bg-white/10 text-text-meta hover:text-white transition-colors"
              >
                <X className="w-4 h-4" />
              </button>
              <div className="flex items-center gap-2 flex-1">
                <Briefcase className="w-4 h-4 text-primary" />
                <span className="font-display font-bold text-white text-sm tracking-wide">Topluluk Kariyer Geçmişi</span>
              </div>
              <span className="text-xs px-2 py-1 rounded-full font-semibold" style={{ background: 'rgba(244,159,32,0.15)', color: '#F49F20' }}>
                {member.communityHistory.length} görev
              </span>
            </div>

            {/* Member info reminder */}
            <div className="px-6 py-3 border-b border-white/5 shrink-0">
              <p className="text-sm font-semibold text-white">{member.name}</p>
              <p className="text-xs text-text-meta">{member.role}</p>
            </div>

            {/* Timeline */}
            <div className="flex-1 overflow-y-auto px-6 py-5 custom-scrollbar">
              <div className="relative">
                {/* Vertical line */}
                <div
                  className="absolute left-[7px] top-2 bottom-2 w-px"
                  style={{ background: 'linear-gradient(to bottom, #F49F20, rgba(218,44,46,0.3))' }}
                />

                <div className="space-y-6">
                  {member.communityHistory.map((item, idx) => {
                    const isLatest = idx === 0;
                    return (
                      <div key={idx} className="flex gap-4 relative">
                        {/* Dot */}
                        <div
                          className="w-4 h-4 rounded-full shrink-0 mt-1 border-2 relative z-10"
                          style={{
                            background: isLatest ? 'linear-gradient(135deg, #F49F20, #DA2C2E)' : '#1C1917',
                            borderColor: isLatest ? '#F49F20' : 'rgba(255,255,255,0.2)',
                            boxShadow: isLatest ? '0 0 10px rgba(244,159,32,0.6)' : 'none',
                          }}
                        />

                        {/* Content */}
                        <div
                          className="flex-1 rounded-xl px-4 py-3"
                          style={{
                            background: isLatest ? 'rgba(244,159,32,0.08)' : 'rgba(255,255,255,0.04)',
                            border: isLatest ? '1px solid rgba(244,159,32,0.2)' : '1px solid rgba(255,255,255,0.07)',
                          }}
                        >
                          <div className="flex items-start justify-between gap-2 mb-1">
                            <p className="text-sm font-semibold text-white leading-tight">{item.title}</p>
                            {isLatest && (
                              <span className="text-[9px] px-1.5 py-0.5 rounded-full font-bold shrink-0" style={{ background: 'rgba(244,159,32,0.2)', color: '#F49F20' }}>
                                Aktif
                              </span>
                            )}
                          </div>
                          <p className="text-xs font-medium mb-1" style={{ color: '#F49F20' }}>{item.dept}</p>
                          <p className="text-[11px]" style={{ color: '#71717A' }}>{item.period}</p>
                        </div>
                      </div>
                    );
                  })}
                </div>
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default MemberModal;
