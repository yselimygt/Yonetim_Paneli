import React, { useState, useEffect } from 'react';
import { getCurrentUser } from '../../utils/auth';
import { getStorage } from '../../utils/storage';
import { addVoteLog } from '../../utils/vote';
import Button from '../../components/ui/Button';
import Input from '../../components/ui/Input';

const VoteTracker = () => {
  const [community, setCommunity] = useState(null);
  const [note, setNote] = useState('');
  const [customAmount, setCustomAmount] = useState('');
  const [totalSet, setTotalSet] = useState('');
  const user = getCurrentUser();

  const loadCommunity = () => {
    if (user && user.communityId) {
      const comms = getStorage('communities');
      setCommunity(comms.find(c => c.id === user.communityId));
    }
  };

  useEffect(() => {
    loadCommunity();
  }, [user]);

  const handleAction = (change, type) => {
    if (!community) return;
    try {
      addVoteLog(community.id, change, type, note);
      setNote('');
      setCustomAmount('');
      setTotalSet('');
      loadCommunity(); // Refresh state
    } catch (error) {
      alert(error.message);
    }
  };

  if (!community) return <div>Yükleniyor...</div>;

  return (
    <div className="max-w-4xl mx-auto">
      <h2 className="text-2xl font-bold mb-6">Oy Girişi</h2>
      
      <div className="bg-white/[0.02] border border-white/10 rounded-3xl p-10 backdrop-blur-md mb-8 text-center relative overflow-hidden">
        <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#DA2F81] to-[#ffb74d]"></div>
        <h2 className="text-gray-400 font-medium mb-2 uppercase tracking-wider text-sm">Toplam Oy Sayınız</h2>
        <div className="text-7xl md:text-8xl font-black bg-gradient-to-r from-[#DA2F81] to-[#ffb74d] bg-clip-text text-transparent">
          {community.currentVotes}
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        {/* Hızlı Aksiyonlar */}
        <div className="bg-white/[0.02] border border-white/10 rounded-2xl p-6">
          <h3 className="text-lg font-semibold mb-4 border-b border-white/10 pb-2">Hızlı İşlemler</h3>
          <div className="flex gap-4">
            <Button className="flex-1 text-xl py-4" onClick={() => handleAction(1, 'increment')}>+1 Ekle</Button>
            <Button variant="danger" className="flex-1 text-xl py-4" onClick={() => handleAction(-1, 'decrement')}>-1 Azalt</Button>
          </div>
        </div>

        {/* Toplu İşlemler */}
        <div className="bg-white/[0.02] border border-white/10 rounded-2xl p-6">
          <h3 className="text-lg font-semibold mb-4 border-b border-white/10 pb-2">Toplu Ekle / Çıkar</h3>
          <div className="flex gap-2">
            <Input 
              type="number" 
              placeholder="Miktar" 
              value={customAmount} 
              onChange={e => setCustomAmount(e.target.value)} 
            />
            <Button onClick={() => handleAction(customAmount, 'manual_add')} disabled={!customAmount}>Ekle</Button>
            <Button variant="danger" onClick={() => handleAction(`-${customAmount}`, 'manual_subtract')} disabled={!customAmount}>Çıkar</Button>
          </div>
        </div>

        {/* Direkt Sayı Belirleme */}
        <div className="bg-white/[0.02] border border-white/10 rounded-2xl p-6 md:col-span-2">
          <h3 className="text-lg font-semibold mb-4 border-b border-white/10 pb-2">Direkt Sayıyı Güncelle</h3>
          <div className="flex gap-4 flex-col sm:flex-row items-end">
            <Input 
              label="Yeni Toplam Oy Sayısı"
              type="number" 
              placeholder="Yeni Sayı" 
              value={totalSet} 
              onChange={e => setTotalSet(e.target.value)} 
            />
            <div className="w-full sm:w-auto">
              <label className="block text-sm font-medium text-gray-300 mb-1">Kısa Not (Opsiyonel)</label>
              <input
                className="w-full bg-[#0d0a1a]/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"
                placeholder="Örn: Hata düzeltmesi"
                value={note}
                onChange={e => setNote(e.target.value)}
              />
            </div>
            <Button className="w-full sm:w-32" onClick={() => handleAction(totalSet, 'set_total')} disabled={!totalSet}>Güncelle</Button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default VoteTracker;
