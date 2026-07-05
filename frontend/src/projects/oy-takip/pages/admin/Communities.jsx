import React, { useState, useEffect } from 'react';
import { getStorage } from '../../utils/storage';
import StatusBadge from '../../components/ui/StatusBadge';
import { Eye, X } from 'lucide-react';
import Button from '../../components/ui/Button';

const Communities = () => {
  const [communities, setCommunities] = useState(getStorage('communities'));
  const [selectedCom, setSelectedCom] = useState(null);
  const [isMobile, setIsMobile] = useState(window.innerWidth < 768);

  useEffect(() => {
    const handleResize = () => setIsMobile(window.innerWidth < 768);
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  return (
    <div>
      <h2 className="text-2xl font-bold mb-6">Topluluklar</h2>
      <div className="bg-white/[0.02] border border-white/10 rounded-2xl overflow-hidden">
        <table className="w-full text-left whitespace-nowrap">
          <thead className="bg-white/[0.05] text-gray-400">
            <tr>
              <th className="p-4 font-medium">Topluluk Adı</th>
              {!isMobile && <th className="p-4 font-medium">Üniversite</th>}
              {!isMobile && <th className="p-4 font-medium">Güncel Oy</th>}
              {!isMobile && <th className="p-4 font-medium">Durum</th>}
              <th className="p-4 font-medium">İşlemler</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-white/5">
            {communities.map(com => (
              <tr key={com.id} className="hover:bg-white/[0.02]">
                <td className="p-4 font-medium">{com.name}</td>
                {!isMobile && <td className="p-4 text-gray-400">{com.university}</td>}
                {!isMobile && <td className="p-4 font-bold text-[#DA2F81]">{com.currentVotes}</td>}
                {!isMobile && <td className="p-4"><StatusBadge status={com.status} /></td>}
                <td className="p-4">
                  {isMobile ? (
                    <button 
                      onClick={() => setSelectedCom(com)}
                      className="p-2 bg-white/5 hover:bg-white/10 rounded-lg text-gray-300 transition-colors"
                    >
                      <Eye size={18} />
                    </button>
                  ) : (
                    <span className="text-xs text-gray-500">Detaylar panelde</span>
                  )}
                </td>
              </tr>
            ))}
            {communities.length === 0 && (
              <tr>
                <td colSpan={isMobile ? 2 : 5} className="p-6 text-center text-gray-500">
                  Henüz topluluk bulunmuyor.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      {/* Mobile Details Modal */}
      {selectedCom && isMobile && (
        <div className="fixed inset-0 z-[60] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
          <style>{`
            @keyframes popIn {
              0% { opacity: 0; transform: scale(0.9); }
              100% { opacity: 1; transform: scale(1); }
            }
            .animate-pop-in {
              animation: popIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
          `}</style>
          <div className="bg-[#1B1E27] w-full max-w-md rounded-2xl border border-white/10 shadow-2xl overflow-hidden relative animate-pop-in">
            <div className="p-4 border-b border-white/10 flex justify-between items-center bg-white/[0.02]">
              <h3 className="font-bold text-lg text-white">Topluluk Detayı</h3>
              <button onClick={() => setSelectedCom(null)} className="text-gray-400 hover:text-white p-1 rounded-md">
                <X size={20} />
              </button>
            </div>
            
            <div className="p-6 space-y-4">
              <div>
                <label className="text-xs text-gray-500 uppercase">Topluluk Adı</label>
                <p className="font-medium text-white">{selectedCom.name}</p>
              </div>
              <div>
                <label className="text-xs text-gray-500 uppercase">Üniversite</label>
                <p className="text-gray-300">{selectedCom.university}</p>
              </div>
              <div className="flex justify-between items-center bg-white/[0.02] p-3 rounded-lg border border-white/5">
                <label className="text-xs text-gray-500 uppercase">Güncel Oy Sayısı</label>
                <p className="font-bold text-xl text-[#DA2F81]">{selectedCom.currentVotes}</p>
              </div>
              <div>
                <label className="text-xs text-gray-500 uppercase">Durum</label>
                <div className="mt-1">
                  <StatusBadge status={selectedCom.status} />
                </div>
              </div>
            </div>
            
            <div className="p-4 border-t border-white/10 bg-white/[0.02]">
               <Button variant="secondary" onClick={() => setSelectedCom(null)} className="w-full justify-center">Kapat</Button>
            </div>
          </div>
        </div>
      )}

    </div>
  );
};

export default Communities;
