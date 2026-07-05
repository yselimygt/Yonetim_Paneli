import React, { useState, useMemo } from 'react';
import { getStorage } from '../../utils/storage';
import { Trophy, Medal, Award, ChevronDown } from 'lucide-react';

const CATEGORIES = [
  "Tüm Kategoriler (Genel Sıralama)",
  "Yılın En İlgi Çekici Topluluğu",
  "Yılın En Eğlenceli Etkinliklerini Düzenleyen Topluluk",
  "Yılın En Aktif Topluluğu",
  "Yılın En Tanınır ve En İyi Çıkış Yapan Topluluğu",
  "Yılın Sosyal Medyayı En İyi Yöneten Topluluğu",
  "Yılın En İyi Proje Fikrine Sahip Topluluğu",
  "Yılın En İyi Sosyal Sorumluluk Ve Sürdürülebilirlik Projesini Yapan Topluluğu",
  "Yılın Ulusal Düzeyde En Başarılı Topluluğu",
  "Yılın Tematik Etkinliklerde En Başarılı Topluluğu",
  "Yılın En Çok Ve Nitelikli İş Birliği Yapan Topluluğu",
  "Yılın Topluluk İçi İletişimi ve Dayanışması En İyi Olan Topluluk",
  "Yılın En Yüksek Katılımlı Etkinliğini Düzenleyen Topluluğu"
];

const Rankings = () => {
  const [selectedCategory, setSelectedCategory] = useState(CATEGORIES[0]);
  const [isDropdownOpen, setIsDropdownOpen] = useState(false);
  const communities = getStorage('communities') || [];

  // Sıralamayı hesapla
  const rankedList = useMemo(() => {
    let filtered = communities.filter(c => c.status === 'active');

    if (selectedCategory !== CATEGORIES[0]) {
      // Virgülle ayrılmış kategori stringini array'e çevir ve içinde seçili kategori var mı kontrol et
      filtered = filtered.filter(c => {
        if (!c.category) return false;
        const cats = c.category.split(',').map(s => s.trim());
        return cats.includes(selectedCategory);
      });
    }

    // Oylara göre azalan sırada sırala
    return filtered.sort((a, b) => (Number(b.currentVotes) || 0) - (Number(a.currentVotes) || 0));
  }, [communities, selectedCategory]);

  const getRankIcon = (index) => {
    if (index === 0) return <Trophy className="text-yellow-400 drop-shadow-[0_0_8px_rgba(250,204,21,0.6)]" size={28} />;
    if (index === 1) return <Medal className="text-gray-300 drop-shadow-[0_0_8px_rgba(209,213,219,0.5)]" size={26} />;
    if (index === 2) return <Award className="text-amber-600 drop-shadow-[0_0_8px_rgba(217,119,6,0.4)]" size={26} />;
    return <span className="font-bold text-gray-500 w-[26px] text-center block text-lg">{index + 1}</span>;
  };

  const getRankStyle = (index) => {
    if (index === 0) return "bg-gradient-to-r from-yellow-500/20 to-yellow-500/5 border-yellow-500/30 text-white";
    if (index === 1) return "bg-gradient-to-r from-gray-300/15 to-gray-300/5 border-gray-400/20 text-white";
    if (index === 2) return "bg-gradient-to-r from-amber-700/20 to-amber-700/5 border-amber-700/20 text-white";
    return "bg-white/[0.02] border-white/5 text-gray-300";
  };

  return (
    <div className="pb-12 max-w-5xl mx-auto">
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
          <h2 className="text-2xl md:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#DA2F81] to-[#ffb74d] mb-2">
            Canlı Sıralama (Leaderboard)
          </h2>
          <p className="text-gray-400 text-sm md:text-base">Kategorilere göre toplulukların güncel oy sıralamasını inceleyin.</p>
        </div>

        {/* Custom Dropdown for Category Selection */}
        <div className="relative w-full md:w-80 z-20">
          <button 
            onClick={() => setIsDropdownOpen(!isDropdownOpen)}
            className="w-full bg-[#0d0a1a] border border-[#DA2F81]/30 hover:border-[#DA2F81] rounded-xl px-4 py-3 text-left flex items-center justify-between transition-colors shadow-lg shadow-[#DA2F81]/10"
          >
            <span className="truncate text-sm font-medium text-white pr-4">{selectedCategory}</span>
            <ChevronDown size={18} className={`text-[#ffb74d] transition-transform ${isDropdownOpen ? 'rotate-180' : ''}`} />
          </button>
          
          {isDropdownOpen && (
            <>
              <div className="fixed inset-0 z-10" onClick={() => setIsDropdownOpen(false)}></div>
              <div className="absolute top-full left-0 right-0 mt-2 bg-[#1B1E27] border border-white/10 rounded-xl shadow-2xl overflow-hidden z-20 max-h-80 overflow-y-auto">
                {CATEGORIES.map((cat, idx) => (
                  <button
                    key={idx}
                    onClick={() => {
                      setSelectedCategory(cat);
                      setIsDropdownOpen(false);
                    }}
                    className={`w-full text-left px-4 py-3 text-sm transition-colors
                      ${selectedCategory === cat 
                        ? 'bg-[#DA2F81]/20 text-[#ffb74d] font-bold border-l-2 border-[#ffb74d]' 
                        : 'text-gray-300 hover:bg-white/5 border-l-2 border-transparent'
                      }
                    `}
                  >
                    {cat}
                  </button>
                ))}
              </div>
            </>
          )}
        </div>
      </div>

      {/* Leaderboard List */}
      <div className="space-y-3 relative">
        {rankedList.map((com, index) => (
          <div 
            key={com.id} 
            className={`flex items-center justify-between p-4 md:p-5 rounded-2xl border transition-all duration-300 hover:scale-[1.01] ${getRankStyle(index)}`}
            style={{ animation: `popIn 0.3s ease-out forwards`, animationDelay: `${index * 0.05}s`, opacity: 0 }}
          >
            <div className="flex items-center gap-4 md:gap-6">
              <div className="w-10 flex justify-center">
                {getRankIcon(index)}
              </div>
              <div>
                <h3 className={`font-bold text-base md:text-lg ${index < 3 ? 'text-white' : 'text-gray-200'}`}>
                  {com.name}
                </h3>
                <p className="text-xs md:text-sm text-gray-400">{com.university}</p>
              </div>
            </div>
            <div className="text-right">
              <div className="text-xl md:text-2xl font-black text-[#DA2F81] drop-shadow-[0_0_10px_rgba(218,47,129,0.3)]">
                {com.currentVotes}
              </div>
              <div className="text-[10px] md:text-xs text-gray-500 font-medium uppercase tracking-wider">Oy</div>
            </div>
          </div>
        ))}

        {rankedList.length === 0 && (
          <div className="text-center py-16 bg-white/[0.02] border border-white/5 rounded-2xl">
            <Trophy className="mx-auto text-gray-600 mb-4 opacity-50" size={48} />
            <p className="text-gray-400">Bu kategoride henüz aktif veya onaylanmış bir topluluk bulunmuyor.</p>
          </div>
        )}

        <style>{`
          @keyframes popIn {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
          }
        `}</style>
      </div>
    </div>
  );
};

export default Rankings;
