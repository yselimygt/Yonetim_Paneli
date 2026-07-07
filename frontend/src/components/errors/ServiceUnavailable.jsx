import React from 'react';
import ErrorPage from './ErrorPage';

const ServiceUnavailable = () => {
  return (
    <ErrorPage 
      errorCode="503"
      title="Bakım Molası / Servis Dışı"
      description="Sistemlerimizi sizin için daha iyi hale getiriyoruz. Kısa bir bakım molasındayız, lütfen daha sonra tekrar deneyin."
      actionButton={
        <button 
          onClick={() => window.location.reload()}
          className="px-8 py-3 bg-gradient-primary text-white rounded-xl font-bold text-base shadow-[0_0_20px_rgba(244,159,32,0.3)] hover:shadow-[0_0_30px_rgba(218,44,46,0.5)] hover:-translate-y-1 transition-all inline-flex items-center gap-2"
        >
          Sayfayı Yenile
        </button>
      }
    />
  );
};

export default ServiceUnavailable;
