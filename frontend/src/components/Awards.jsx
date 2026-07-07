import React from 'react';
import { Trophy, Award, Medal, Target, Star, Crown, FileText, ExternalLink } from 'lucide-react';
import { useSiteData } from '../context/SiteDataContext';

const Awards = () => {
  const { siteData } = useSiteData();
  const awards = siteData?.awards || [];
  
  if (!awards || awards.length === 0) return null;

  return (
    <section className="py-20 lg:py-32 relative overflow-hidden" id="awards">
      {/* Background Elements */}
      <div className="absolute right-0 top-0 w-full h-full bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-accent-yellow/10 via-background to-background pointer-events-none"></div>
      <div className="absolute left-0 bottom-0 w-[500px] h-[500px] bg-primary/5 blur-[150px] rounded-full pointer-events-none"></div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
          <div>
            <div className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-accent-yellow/10 border border-accent-yellow/20 text-accent-yellow text-sm font-semibold mb-4">
              <Award className="w-4 h-4" />
              Gurur Tablomuz
            </div>
            <h2 className="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight">
              Sınırları Aşan <br/>
              <span className="text-transparent bg-clip-text bg-gradient-to-r from-accent-yellow to-primary">Başarılarımız</span>
            </h2>
          </div>
          <p className="text-text-secondary text-lg max-w-md">
            Ürettiğimiz projeler, ortaya koyduğumuz vizyon ve ekosisteme kattığımız değerle layık görüldüğümüz ödüller.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {awards.map((item, idx) => (
            <div key={idx} className="group relative flex flex-col h-full bg-white/[0.02] border border-white/10 hover:border-accent-yellow/30 rounded-3xl p-1 overflow-hidden transition-all duration-500 hover:shadow-[0_0_40px_rgba(234,179,8,0.1)] hover:-translate-y-2">
              
              {/* Inner Glow on Hover */}
              <div className="absolute inset-0 bg-gradient-to-b from-accent-yellow/0 to-accent-yellow/0 group-hover:from-accent-yellow/5 group-hover:to-transparent transition-all duration-500"></div>

              <div className="flex flex-col h-full p-6 sm:p-8 relative z-10 pb-4">
                {/* Top Section */}
                <div className="flex justify-between items-start mb-6 gap-4">
                  {/* Institution name and logo */}
                  {(item.institution_name || item.organization) ? (
                    <div className="flex items-center gap-3 bg-black/20 border border-white/5 py-2 px-3 rounded-xl backdrop-blur-sm">
                      {item.institution_logo_url && (
                        <img src={item.institution_logo_url} alt="Kurum" className="w-6 h-6 object-contain" />
                      )}
                      <span className="text-sm font-medium text-white/90">
                        {item.institution_name || item.organization}
                      </span>
                    </div>
                  ) : (
                    <div className="w-12 h-12 rounded-2xl bg-gradient-to-br from-accent-yellow/20 to-primary/10 border border-accent-yellow/20 flex items-center justify-center text-accent-yellow shadow-inner group-hover:scale-110 transition-transform duration-500 shrink-0">
                      {item.icon === 'Medal' ? <Medal className="w-6 h-6" /> : 
                       item.icon === 'Target' ? <Target className="w-6 h-6" /> : 
                       item.icon === 'Star' ? <Star className="w-6 h-6" /> : 
                       item.icon === 'Crown' ? <Crown className="w-6 h-6" /> : 
                       <Trophy className="w-6 h-6" />}
                    </div>
                  )}
                  
                  <span className="px-4 py-1.5 bg-white/5 border border-white/10 rounded-full text-sm font-bold text-white shadow-sm shrink-0">
                    {item.year}
                  </span>
                </div>

                {/* Content */}
                <div className="flex-grow">
                  <h3 className="text-2xl font-display font-bold text-white mb-4 leading-tight group-hover:text-accent-yellow transition-colors duration-300">
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
              {item.image_url && (
                <div className="relative mt-auto mx-2 mb-2 h-64 sm:h-72 lg:h-80 rounded-2xl overflow-hidden bg-gradient-to-b from-transparent to-black/40 border border-white/5 shrink-0">
                  <div className="absolute inset-0 bg-accent-yellow/10 mix-blend-overlay opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>
                  <img 
                    src={item.image_url} 
                    alt={item.title} 
                    className="w-full h-full object-contain p-4 relative z-0 group-hover:scale-105 transition-transform duration-700 ease-out" 
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
