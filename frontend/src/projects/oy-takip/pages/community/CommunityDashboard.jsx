import React, { useState, useEffect } from 'react';
import { getCurrentUser } from '../../utils/auth';
import { getStorage } from '../../utils/storage';
import StatCard from '../../components/ui/StatCard';
import { Target, TrendingUp, TrendingDown, Star } from 'lucide-react';

const CommunityDashboard = () => {
  const [community, setCommunity] = useState(null);
  const [votes, setVotes] = useState([]);
  const user = getCurrentUser();

  useEffect(() => {
    if (user && user.communityId) {
      const comms = getStorage('communities');
      const allVotes = getStorage('votes');
      setCommunity(comms.find(c => c.id === user.communityId));
      setVotes(allVotes.filter(v => v.communityId === user.communityId));
    }
  }, [user]);

  if (!community) return <div>Yükleniyor...</div>;

  const todayStr = new Date().toDateString();
  const todayVotes = votes.filter(v => new Date(v.createdAt).toDateString() === todayStr);
  
  // Hesaplarken type'a göre değişimleri toplayalım
  const todayIncrease = todayVotes.reduce((acc, v) => acc + (Number(v.change) || 0), 0);
  const totalIncrease = votes.reduce((acc, v) => acc + (Number(v.change) || 0), 0);

  // Kategori bazlı oyları topla
  const totalCurrentVotes = community.categoryVotes 
    ? Object.values(community.categoryVotes).reduce((acc, val) => acc + (val.current || 0), 0)
    : (Number(community.currentVotes) || 0);
    
  const totalTargetVotes = community.categoryVotes 
    ? Object.values(community.categoryVotes).reduce((acc, val) => acc + (val.target || 0), 0)
    : (Number(community.targetVotes) || 0);

  const remaining = Math.max(0, totalTargetVotes - totalCurrentVotes);

  return (
    <div>
      <h2 className="text-2xl font-bold mb-6">Hoş Geldiniz, {community.name}</h2>
      
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatCard title="Güncel Oy (Toplam)" value={totalCurrentVotes} icon={Star} trend="up" subtext="Tüm kategorilerin toplamı" />
        <StatCard title="Bugünkü Artış" value={`+${todayIncrease}`} icon={TrendingUp} trend="up" subtext="Bugün girilen net oy" />
        <StatCard title="Toplam Artış" value={`+${totalIncrease}`} icon={TrendingUp} trend="up" subtext="Sistemden girilen oylar" />
        <StatCard title="Hedefe Kalan (Toplam)" value={remaining} icon={Target} subtext={`Hedef: ${totalTargetVotes}`} />
      </div>

      <div className="mt-8 bg-white/[0.02] border border-white/10 rounded-2xl p-6">
        <h3 className="text-xl font-bold mb-4">Kategori Dağılımı</h3>
        {community.categoryVotes ? (
          <div className="space-y-4">
            {Object.entries(community.categoryVotes).map(([cat, data], idx) => (
              <div key={idx} className="flex justify-between items-center bg-white/5 p-4 rounded-xl border border-white/5">
                <span className="font-medium text-gray-300">{cat}</span>
                <span className="font-bold text-[#DA2F81] text-lg">{data.current} Oy</span>
              </div>
            ))}
          </div>
        ) : (
          <p className="text-gray-500">Detaylı kategori verisi bulunamadı.</p>
        )}
      </div>
    </div>
  );
};

export default CommunityDashboard;
