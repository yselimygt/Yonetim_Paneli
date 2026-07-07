import React from 'react';
import { Link } from 'react-router-dom';

const ErrorPage = ({ errorCode, title, description, mascotUrl, actionButton }) => {
  return (
    <div className="min-h-[80vh] flex items-center justify-center relative overflow-hidden py-20">
      {/* Background Blurs */}
      <div className="absolute top-1/4 -right-1/4 w-[500px] h-[500px] bg-primary/10 blur-[120px] rounded-full pointer-events-none"></div>
      <div className="absolute bottom-0 -left-1/4 w-[400px] h-[400px] bg-accent-purple/10 blur-[150px] rounded-full pointer-events-none"></div>

      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full text-center">
        {/* Mascot */}
        <div className="relative w-48 h-48 sm:w-64 sm:h-64 mx-auto mb-8 animate-[float_6s_ease-in-out_infinite]">
          <div className="absolute inset-0 bg-gradient-primary opacity-20 blur-[40px] rounded-full"></div>
          <img 
            src={mascotUrl || '/innomis/assets/innox/merak-eden-innox.svg'} 
            alt="Innox Mascot" 
            className="w-full h-full object-contain relative z-10 drop-shadow-[0_0_30px_rgba(244,159,32,0.3)]"
          />
        </div>

        {/* Error Code */}
        {errorCode && (
          <div className="text-6xl sm:text-8xl font-display font-extrabold text-gradient mb-4 drop-shadow-lg">
            {errorCode}
          </div>
        )}

        {/* Title */}
        <h1 className="text-3xl sm:text-4xl font-display font-bold text-white mb-6">
          {title}
        </h1>

        {/* Description */}
        <p className="text-text-secondary text-base sm:text-lg max-w-2xl mx-auto mb-10 leading-relaxed">
          {description}
        </p>

        {/* Action Button */}
        <div className="flex justify-center">
          {actionButton || (
            <Link 
              to="/" 
              className="px-8 py-3 bg-gradient-primary text-white rounded-xl font-bold text-base shadow-[0_0_20px_rgba(244,159,32,0.3)] hover:shadow-[0_0_30px_rgba(218,44,46,0.5)] hover:-translate-y-1 transition-all inline-flex items-center gap-2"
            >
              Ana Sayfaya Dön
            </Link>
          )}
        </div>
      </div>
    </div>
  );
};

export default ErrorPage;
