import React from 'react';
import { brandColors } from '../data/innomisData';

const BrandIdentity = () => {
  return (
    <section className="py-16 lg:py-24 relative" id="brand">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
          
          {/* Left: Text */}
          <div className="lg:col-span-5 space-y-6">
            <h2 className="font-display text-4xl sm:text-5xl font-bold leading-tight text-white">
              Kimliğimiz <span className="text-gradient">teknoloji</span> ve topluluk enerjisinden beslenir
            </h2>
            <p className="text-text-secondary text-lg">
              Modern, premium ve genç... InnoMIS Community'nin görsel dünyası, sahip olduğu üretim kültürünü dışarıya yansıtacak şekilde tasarlandı.
            </p>
            <div className="pt-4">
              <button className="px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl font-medium border border-white/10 transition-colors">
                Brand Kit'i İncele
              </button>
            </div>
          </div>

          {/* Right: Colors */}
          <div className="lg:col-span-7">
            <div className="grid grid-cols-2 sm:grid-cols-3 gap-4">
              {brandColors.map((color, idx) => (
                <div key={idx} className="glass-card p-4 hover:-translate-y-1 transition-transform group">
                  <div 
                    className="w-full h-24 rounded-lg mb-4 shadow-inner relative overflow-hidden"
                    style={{ backgroundColor: color.hex }}
                  >
                    {/* Inner highlight */}
                    <div className="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent"></div>
                  </div>
                  <div>
                    <h4 className="text-white font-semibold text-sm mb-1">{color.name}</h4>
                    <p className="text-text-meta font-mono text-xs uppercase mb-2">{color.hex}</p>
                    <p className="text-text-secondary text-[10px] leading-tight">{color.usage}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>
          
        </div>
      </div>
    </section>
  );
};

export default BrandIdentity;
