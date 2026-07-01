import React from 'react';
import { values } from '../data/innomisData';

const About = () => {
  return (
    <section className="py-16 lg:py-24 relative" id="about">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          
          {/* Left: Text Content */}
          <div className="space-y-6">
            <h2 className="font-display text-4xl sm:text-5xl font-bold leading-[1.2] text-white">
              <span className="block">Fikirleri teknolojiye</span>
              <span className="block text-gradient">dönüştüren topluluk</span>
            </h2>
            <p className="text-text-secondary text-lg leading-relaxed">
              InnoMIS Community, öğrencilerin teknoloji, girişimcilik, yapay zeka ve kariyer alanlarında kendini geliştirmesini sağlayan; etkinlikler, eğitimler, projeler ve ekip çalışmalarıyla üretim kültürünü destekleyen yenilikçi bir öğrenci topluluğudur.
            </p>
            
            <ul className="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
              {[
                'Üniversite öğrencileri için öğrenme ortamı',
                'Takım çalışması',
                'Etkinlik deneyimi',
                'Kariyer ağı'
              ].map((item, idx) => (
                <li key={idx} className="flex items-center gap-3 text-sm text-text-main">
                  <div className="w-1.5 h-1.5 rounded-full bg-primary"></div>
                  {item}
                </li>
              ))}
            </ul>
          </div>

          {/* Right: Value Cards */}
          <div className="grid grid-cols-2 gap-3 sm:gap-4">
            {values.map((val, idx) => (
              <div 
                key={idx} 
                className="glass-card p-4 sm:p-6 group hover:border-primary/30 transition-all duration-300 relative overflow-hidden"
              >
                <div className="absolute top-0 right-0 p-8 bg-white/5 blur-2xl rounded-full group-hover:bg-primary/10 transition-colors"></div>
                <div className="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform [&>svg]:w-5 [&>svg]:h-5 sm:[&>svg]:w-6 sm:[&>svg]:h-6">
                  {val.icon}
                </div>
                <h3 className="text-white font-display font-semibold text-base sm:text-lg mb-1 sm:mb-2">{val.title}</h3>
                <p className="text-text-secondary text-[11px] sm:text-sm leading-relaxed">{val.desc}</p>
              </div>
            ))}
          </div>

        </div>
      </div>
    </section>
  );
};

export default About;
