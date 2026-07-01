import React from 'react';
import { ArrowRight, Instagram } from 'lucide-react';
import { Link } from 'react-router-dom';

const JoinCTA = () => {
  return (
    <section className="py-16 lg:py-24 relative overflow-hidden" id="join">
      {/* Background Aurora */}
      <div className="absolute inset-0 bg-surface z-0"></div>
      <div className="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] [mask-image:radial-gradient(ellipse_at_center,black_40%,transparent_80%)] pointer-events-none z-0"></div>
      
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl h-[400px] bg-gradient-to-r from-primary/20 to-accent-purple/20 blur-[120px] rounded-full pointer-events-none z-0"></div>

      <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="glass-card p-10 md:p-16 text-center border-white/10 shadow-[0_0_50px_rgba(0,0,0,0.5)] overflow-hidden relative">
          
          {/* Inner Glow */}
          <div className="absolute top-0 right-0 w-64 h-64 bg-primary/20 blur-[80px] rounded-full pointer-events-none"></div>
          <div className="absolute bottom-0 left-0 w-64 h-64 bg-accent-purple/20 blur-[80px] rounded-full pointer-events-none"></div>

          <h2 className="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6 relative z-10">
            Sadece izleme, <br/>
            geleceği üreten ekibin <span className="text-gradient">parçası ol.</span>
          </h2>
          
          <p className="text-text-secondary text-lg sm:text-xl max-w-2xl mx-auto mb-10 relative z-10 leading-relaxed">
            InnoMIS Community'de etkinliklerde yer alabilir, projeler geliştirebilir, yeni insanlarla tanışabilir ve kariyer yolculuğunu güçlendirebilirsin.
          </p>

          <div className="flex flex-col sm:flex-row justify-center items-center gap-4 relative z-10">
            <Link to="/kayit" className="w-full sm:w-auto px-8 py-4 bg-gradient-primary text-white rounded-xl font-bold text-lg shadow-[0_0_20px_rgba(244,159,32,0.4)] hover:shadow-[0_0_30px_rgba(218,44,46,0.6)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2 group">
              Topluluğa Katıl
              <ArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
            </Link>
            <button className="w-full sm:w-auto px-8 py-4 bg-white/5 border border-white/10 text-white rounded-xl font-bold text-lg hover:bg-white/10 hover:border-white/20 transition-all flex items-center justify-center gap-2 group">
              <Instagram className="w-5 h-5 text-text-secondary group-hover:text-primary transition-colors" />
              Instagram'da Takip Et
            </button>
          </div>

        </div>
      </div>
    </section>
  );
};

export default JoinCTA;
