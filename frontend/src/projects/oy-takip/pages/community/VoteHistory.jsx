import React, { useState, useEffect } from 'react';
import { getCurrentUser } from '../../utils/auth';
import { getStorage } from '../../utils/storage';
import { formatDate } from '../../utils/date';

const VoteHistory = () => {
  const [votes, setVotes] = useState([]);
  const user = getCurrentUser();

  useEffect(() => {
    if (user && user.communityId) {
      const allVotes = getStorage('votes');
      // En yeniden en eskiye sırala
      setVotes(allVotes.filter(v => v.communityId === user.communityId).sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt)));
    }
  }, [user]);

  const getTypeLabel = (type) => {
    const types = {
      increment: '+1 Ekleme',
      decrement: '-1 Çıkarma',
      manual_add: 'Toplu Ekleme',
      manual_subtract: 'Toplu Çıkarma',
      set_total: 'Direkt Güncelleme'
    };
    return types[type] || type;
  };

  return (
    <div>
      <h2 className="text-2xl font-bold mb-6">Oy Geçmişi</h2>
      <div className="bg-white/[0.02] border border-white/10 rounded-2xl overflow-hidden">
        <table className="w-full text-left">
          <thead className="bg-white/[0.05] text-gray-400">
            <tr>
              <th className="p-4 font-medium">Tarih</th>
              <th className="p-4 font-medium">İşlem</th>
              <th className="p-4 font-medium text-right">Değişim</th>
              <th className="p-4 font-medium text-right">Yeni Toplam</th>
              <th className="p-4 font-medium">Not</th>
              <th className="p-4 font-medium">Yapan</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-white/5">
            {votes.map(v => (
              <tr key={v.id} className="hover:bg-white/[0.02]">
                <td className="p-4 text-sm text-gray-400">{formatDate(v.createdAt)}</td>
                <td className="p-4 text-sm">{getTypeLabel(v.type)}</td>
                <td className={`p-4 text-right font-bold ${v.change > 0 ? 'text-green-400' : v.change < 0 ? 'text-red-400' : 'text-gray-400'}`}>
                  {v.change > 0 ? `+${v.change}` : v.change}
                </td>
                <td className="p-4 text-right font-bold text-white">{v.totalAfterChange}</td>
                <td className="p-4 text-sm text-gray-400">{v.note || '-'}</td>
                <td className="p-4 text-sm text-gray-400">{v.createdBy}</td>
              </tr>
            ))}
            {votes.length === 0 && (
              <tr>
                <td colSpan="6" className="p-8 text-center text-gray-500">Henüz hiç oy işlemi yapılmamış.</td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default VoteHistory;
