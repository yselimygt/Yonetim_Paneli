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
  const remaining = Math.max(0, (Number(community.targetVotes) || 0) - Number(community.currentVotes));

  return (
    <div>
      <h2 className="text-2xl font-bold mb-6">Hoş Geldiniz, {community.name}</h2>
      
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatCard title="Güncel Oy" value={community.currentVotes} icon={Star} trend="up" subtext="Toplam kayıtlı oy" />
        <StatCard title="Bugünkü Artış" value={`+${todayIncrease}`} icon={TrendingUp} trend="up" subtext="Bugün girilen net oy" />
        <StatCard title="Toplam Artış" value={`+${totalIncrease}`} icon={TrendingUp} trend="up" subtext="Sistemden girilen oylar" />
        <StatCard title="Hedefe Kalan" value={remaining} icon={Target} subtext={`Hedef: ${community.targetVotes}`} />
      </div>
    </div>
  );
};

export default CommunityDashboard;
