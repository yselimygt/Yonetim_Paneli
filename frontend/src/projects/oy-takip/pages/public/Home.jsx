import React from 'react';
import { Link } from 'react-router-dom';

const Home = () => {
  return (
    <div className="min-h-screen flex flex-col items-center justify-center p-4 md:p-6 text-center bg-[#1B1E27] relative overflow-hidden">
      {/* Background Glows */}
      <div className="absolute top-[-5%] left-[-10%] w-[300px] h-[300px] md:w-[500px] md:h-[500px] bg-[#ffb74d]/10 blur-[100px] md:blur-[120px] rounded-full pointer-events-none" />
      <div className="absolute bottom-[-5%] right-[-10%] w-[400px] h-[400px] md:w-[600px] md:h-[600px] bg-[#DA2F81]/15 blur-[100px] md:blur-[120px] rounded-full pointer-events-none" />

      <div className="relative z-10 flex flex-col items-center w-full max-w-4xl mx-auto mt-8 md:mt-0">
        
        {/* Logo Section */}
        <div className="mb-8 relative group">
          <div className="absolute inset-0 bg-[#DA2F81]/20 blur-2xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          <img 
            src="https://www.gmtoplulukodulleri.com/gmalogov1.png" 
            alt="GM Topluluk Ödülleri Logo" 
            className="w-32 md:w-48 h-auto object-contain drop-shadow-[0_0_15px_rgba(255,255,255,0.2)] relative z-10"
          />
        </div>

        <div className="mb-6 inline-flex items-center gap-2 px-4 py-2 rounded-full border border-white/10 bg-white/5 text-xs md:text-sm text-gray-300">
          <span className="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
          Resmi Olmayan Takip Sistemi
        </div>
        
        <h1 className="text-4xl sm:text-5xl md:text-7xl font-extrabold mb-4 md:mb-6 tracking-tight leading-tight">
          <span className="bg-gradient-to-r from-[#DA2F81] to-[#ffb74d] bg-clip-text text-transparent">
            Topluluk Ödülleri
          </span>
          <br className="hidden sm:block" /> İçin Akıllı <br className="sm:hidden" />Oy Takip Paneli
        </h1>
        <p className="text-lg md:text-2xl text-gray-400 max-w-2xl mb-10 md:mb-12 font-light px-2">
          Oylar gizlendikten sonra topluluğunuzun manuel oy takibini düzenli, güvenli ve raporlanabilir şekilde yönetin.
        </p>
        
        <div className="flex flex-col w-full sm:w-auto sm:flex-row gap-4 sm:gap-6 px-4 sm:px-0">
          <Link to="/projects/oy-takip/apply" className="w-full sm:w-auto px-8 py-4 rounded-xl font-bold bg-gradient-to-r from-[#DA2F81] to-[#ffb74d] text-white hover:opacity-90 transition-opacity shadow-[0_0_30px_rgba(218,47,129,0.4)] text-center text-lg">
            Başvuru Yap
          </Link>
          <Link to="/projects/oy-takip/login" className="w-full sm:w-auto px-8 py-4 rounded-xl font-bold bg-white/5 border border-white/10 text-white hover:bg-white/10 transition-colors backdrop-blur-sm text-center text-lg">
            Panele Giriş
          </Link>
        </div>

        <div className="mt-16 md:mt-24 max-w-3xl w-full border border-[#DA2F81]/20 bg-white/[0.02] p-5 md:p-6 rounded-2xl backdrop-blur-md shadow-2xl mx-4 sm:mx-0">
          <p className="text-gray-400 text-xs md:text-sm leading-relaxed">
            <strong className="text-white">Etik Kullanım Uyarısı:</strong> Bu sistem yalnızca manuel takip amacıyla kullanılır. Yarışma sistemine müdahale etmez, otomatik oy göndermez, bot veya spam işlemi yapmaz.
          </p>
        </div>
      </div>
    </div>
  );
};

export default Home;
