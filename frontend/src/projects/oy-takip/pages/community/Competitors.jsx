import React, { useState, useEffect } from 'react';
import { getCurrentUser } from '../../utils/auth';
import { getStorage } from '../../utils/storage';

const Competitors = () => {
  const [competitors, setCompetitors] = useState([]);
  const user = getCurrentUser();

  useEffect(() => {
    if (user && user.communityId) {
      const allComps = getStorage('competitors');
      setCompetitors(allComps.filter(c => c.communityId === user.communityId));
    }
  }, [user]);

  return (
    <div>
      <h2 className="text-2xl font-bold mb-6">Rakip Takibi</h2>
      <div className="bg-white/[0.02] border border-white/10 rounded-2xl overflow-hidden p-6 text-gray-400">
        Bu özellik yapım aşamasındadır.
      </div>
    </div>
  );
};

export default Competitors;
