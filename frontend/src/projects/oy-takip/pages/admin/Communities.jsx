import React, { useState } from 'react';
import { getStorage } from '../../utils/storage';
import StatusBadge from '../../components/ui/StatusBadge';

const Communities = () => {
  const [communities, setCommunities] = useState(getStorage('communities'));

  return (
    <div>
      <h2 className="text-2xl font-bold mb-6">Topluluklar</h2>
      <div className="bg-white/[0.02] border border-white/10 rounded-2xl overflow-x-auto">
        <table className="w-full text-left whitespace-nowrap">
          <thead className="bg-white/[0.05] text-gray-400">
            <tr>
              <th className="p-4 font-medium">Topluluk Adı</th>
              <th className="p-4 font-medium">Üniversite</th>
              <th className="p-4 font-medium">Güncel Oy</th>
              <th className="p-4 font-medium">Durum</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-white/5">
            {communities.map(com => (
              <tr key={com.id} className="hover:bg-white/[0.02]">
                <td className="p-4">{com.name}</td>
                <td className="p-4 text-gray-400">{com.university}</td>
                <td className="p-4 font-bold text-[#DA2F81]">{com.currentVotes}</td>
                <td className="p-4"><StatusBadge status={com.status} /></td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default Communities;
