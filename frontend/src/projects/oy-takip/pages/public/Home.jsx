import React from 'react';
import { Link } from 'react-router-dom';

const Home = () => {
  return (
    <div className="min-h-screen flex flex-col items-center justify-center p-6 text-center">
      <h1 className="text-5xl md:text-6xl font-extrabold mb-6">
        <span className="bg-gradient-to-r from-[#f49f20] to-[#da2c2e] bg-clip-text text-transparent">
          Topluluk Ödülleri
        </span>
        <br /> İçin Akıllı Oy Takip Paneli
      </h1>
      <p className="text-xl text-gray-400 max-w-2xl mb-12">
        Oylar gizlendikten sonra topluluğunuzun manuel oy takibini düzenli, güvenli ve raporlanabilir şekilde yönetin.
      </p>
      
      <div className="flex gap-4">
        <Link to="/projects/oy-takip/apply" className="px-8 py-4 rounded-xl font-bold bg-gradient-to-r from-[#f49f20] to-[#da2c2e] text-white hover:opacity-90 transition-opacity shadow-lg shadow-[#da2c2e]/20">
          Başvuru Yap
        </Link>
        <Link to="/projects/oy-takip/login" className="px-8 py-4 rounded-xl font-bold bg-white/5 border border-white/10 text-white hover:bg-white/10 transition-colors backdrop-blur-sm">
          Panele Giriş
        </Link>
      </div>

      <div className="mt-20 max-w-3xl border border-white/10 bg-white/[0.02] p-6 rounded-2xl backdrop-blur-sm">
        <p className="text-gray-400 text-sm">
          <strong className="text-white">Etik Kullanım Uyarısı:</strong> Bu sistem yalnızca manuel takip amacıyla kullanılır. Yarışma sistemine müdahale etmez, otomatik oy göndermez, bot veya spam işlemi yapmaz.
        </p>
      </div>
    </div>
  );
};

export default Home;
