import React, { useState } from 'react';
import { ChevronDown } from 'lucide-react';
import { useSiteData } from '../context/SiteDataContext';

const FAQ = () => {
  const [openIndex, setOpenIndex] = useState(0);
  const { siteData, loading } = useSiteData();
  const faqs = siteData?.settings?.faqs || [];

  return (
    <section className="py-16 lg:py-24 relative bg-gray-50 dark:bg-surface/30 border-y border-white/5" id="faq">
      <div className="absolute right-0 bottom-0 w-[500px] h-[500px] bg-primary/5 blur-[150px] rounded-full pointer-events-none"></div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-16 items-start">
          
          {/* Left */}
          <div>
            <h2 className="font-display text-4xl sm:text-5xl font-bold text-white mb-4 lg:mb-6">
              Sık Sorulan <span className="text-gradient">Sorular</span>
            </h2>
            <p className="text-text-secondary text-lg mb-0 lg:mb-12 max-w-md">
              Topluluğumuz, etkinliklerimiz ve projelerimiz hakkında en çok merak edilenleri derledik.
            </p>

            {/* Mascot/graphic for FAQ */}
            <div className="hidden lg:flex w-full max-w-xs items-center justify-center relative mt-10">
              <div className="absolute inset-0 bg-gradient-primary blur-[80px] opacity-20 rounded-full"></div>
              <img 
                src="/innomis/assets/innox/merak-eden-innox.svg" 
                alt="Merak Eden Innox" 
                className="w-full h-auto relative z-10 drop-shadow-[0_0_30px_rgba(218,44,46,0.2)] animate-[float_5s_ease-in-out_infinite]"
              />
            </div>
          </div>

          {/* Right: Accordion */}
          <div className="space-y-4">
            {faqs.map((faq, idx) => (
              <div 
                key={idx} 
                className={`glass-card overflow-hidden transition-all duration-300 ${openIndex === idx ? 'border-primary/50 bg-white/5' : ''}`}
              >
                <button 
                  className="w-full text-left p-6 flex justify-between items-center"
                  onClick={() => setOpenIndex(openIndex === idx ? -1 : idx)}
                >
                  <span className="font-display font-semibold text-white pr-4">{faq.q}</span>
                  <ChevronDown className={`w-5 h-5 text-text-secondary transition-transform duration-300 flex-shrink-0 ${openIndex === idx ? 'rotate-180 text-primary' : ''}`} />
                </button>
                
                <div 
                  className={`px-6 overflow-hidden transition-all duration-300 ease-in-out ${openIndex === idx ? 'max-h-40 pb-6 opacity-100' : 'max-h-0 opacity-0'}`}
                >
                  <p className="text-text-secondary text-sm leading-relaxed border-t border-white/5 pt-4">
                    {faq.a}
                  </p>
                </div>
              </div>
            ))}
          </div>
          
        </div>
      </div>
    </section>
  );
};

export default FAQ;
