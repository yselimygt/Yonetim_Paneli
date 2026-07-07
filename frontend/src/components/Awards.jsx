import React from 'react';
import { Trophy, Award, Medal, Target, Star, Crown, FileText, ExternalLink } from 'lucide-react';
import { useSiteData } from '../context/SiteDataContext';

const Awards = () => {
  const { siteData } = useSiteData();
  const awards = siteData?.awards || [];
  
  if (!awards || awards.length === 0) return null;

  return (
    <section className="py-20 lg:py-32 relative overflow-hidden" id="awards">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="flex flex-col md:flex-row md:items-center justify-between mb-16 gap-6">
          <div>
            <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-white/20 text-white text-sm font-medium mb-6">
              <Award className="w-4 h-4" />
              Gurur Tablomuz
            </div>
            <h2 className="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight">
              Ödüller ve Derecelerimiz
            </h2>
          </div>
          <p className="text-text-secondary text-base lg:text-lg max-w-md md:text-right">
            Ürettiğimiz projeler, ortaya koyduğumuz vizyon ve ekosisteme kattığımız değerle layık görüldüğümüz ödüller.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {awards.map((item, idx) => (
            <div key={idx} className="group relative flex flex-col h-full bg-[#0a0a0a] border border-white/10 rounded-[2rem] overflow-hidden transition-all duration-500 hover:-translate-y-2">
              
              <div className="flex flex-col h-full p-6 sm:p-8 relative z-10 pb-4">
                {/* Top Section */}
                <div className="flex justify-between items-start mb-8 gap-4">
                  {/* Institution name and logo */}
                  {(item.institution_name || item.organization) ? (
                    <div className="flex items-center gap-3 bg-white/5 border border-white/5 py-2 px-4 rounded-xl backdrop-blur-sm">
                      {item.institution_logo_url && (
                        <img src={item.institution_logo_url} alt="Kurum" className="w-5 h-5 object-contain" />
                      )}
                      <span className="text-xs sm:text-sm font-medium text-white/90">
                        {item.institution_name || item.organization}
                      </span>
                    </div>
                  ) : (
                    <div className="flex items-center gap-3 bg-white/5 border border-white/5 py-2 px-4 rounded-xl backdrop-blur-sm">
                       <span className="text-xs sm:text-sm font-medium text-white/90">InnoMIS Community</span>
                    </div>
                  )}
                  
                  <span className="px-5 py-2 bg-white/5 border border-white/10 rounded-full text-sm font-medium text-white shadow-sm shrink-0">
                    {item.year}
                  </span>
                </div>

                {/* Content */}
                <div className="flex-grow">
                  {item.ranking && (
                    <div className="inline-flex items-center gap-1.5 px-3 py-1 bg-accent-yellow/10 border border-accent-yellow/20 rounded-lg text-accent-yellow text-xs font-bold uppercase tracking-wider mb-3">
                      <Trophy className="w-3.5 h-3.5" />
                      {item.ranking}
                    </div>
                  )}
                  <h3 className="text-2xl font-display font-bold text-white mb-4 leading-tight">
                    {item.title}
                  </h3>
                  <p className="text-text-secondary leading-relaxed mb-6">
                    {item.description || item.desc}
                  </p>
                </div>

                {/* Actions */}
                <div className="mt-auto space-y-4">
                  {/* Buttons Row */}
                  {(item.link || item.pdf_url) && (
                    <div className="flex flex-wrap gap-3 pt-2">
                      {item.link && (
                        <a href={item.link} target="_blank" rel="noreferrer" className="flex-1 flex items-center justify-center gap-2 py-2.5 bg-accent-yellow/10 hover:bg-accent-yellow/20 border border-accent-yellow/20 rounded-xl transition-all duration-300 text-sm font-semibold text-accent-yellow">
                          Detaylar
                          <ExternalLink className="w-4 h-4" />
                        </a>
                      )}
                      {item.pdf_url && (
                        <a href={item.pdf_url} target="_blank" rel="noreferrer" className="flex-1 flex items-center justify-center gap-2 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-all duration-300 text-sm font-medium text-white">
                          <FileText className="w-4 h-4" />
                          Belge (PDF)
                        </a>
                      )}
                    </div>
                  )}
                </div>
              </div>
              
              {/* Image Section - if it exists */}
              {/* Image Section - if it exists */}
              {item.image_url && (
                <div className="relative mt-auto h-64 sm:h-72 lg:h-80 overflow-hidden shrink-0 border-t border-white/5 bg-black/40">
                  <div className="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10 pointer-events-none"></div>
                  <img 
                    src={item.image_url} 
                    alt={item.title} 
                    className="w-full h-full object-contain p-6 relative z-0 group-hover:scale-105 transition-transform duration-700 ease-out" 
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
