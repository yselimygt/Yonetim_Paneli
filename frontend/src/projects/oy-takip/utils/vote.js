import { addItem, getStorage, updateItem } from './storage';
import { getCurrentUser } from './auth';

export const addVoteLog = (communityId, category, change, type, note = '') => {
  const user = getCurrentUser();
  const communities = getStorage('communities');
  const community = communities.find(c => c.id === communityId);
  
  if (!community) throw new Error('Topluluk bulunamadı');

  // Kategori oylarını al veya varsayılan oluştur
  const catVotes = community.categoryVotes || {};
  if (!catVotes[category]) {
    catVotes[category] = { current: 0, target: 5000 };
  }

  let currentVotes = Number(catVotes[category].current) || 0;
  const numChange = Number(change);
  
  if (isNaN(numChange)) throw new Error('Geçersiz oy değişimi');

  let totalAfterChange = 0;
  if (type === 'set_total') {
    totalAfterChange = numChange;
    change = totalAfterChange - currentVotes; 
  } else {
    totalAfterChange = currentVotes + numChange;
  }

  if (totalAfterChange < 0) totalAfterChange = 0;

  // 1. Create log
  const log = {
    communityId,
    category,
    change: type === 'set_total' ? change : numChange,
    totalAfterChange,
    type,
    note,
    createdAt: new Date().toISOString(),
    createdBy: user ? user.name : 'Sistem'
  };

  addItem('votes', log);

  // 2. Update community category votes
  catVotes[category].current = totalAfterChange;
  updateItem('communities', communityId, { 
    categoryVotes: catVotes,
    updatedAt: new Date().toISOString()
  });

  return totalAfterChange;
};
