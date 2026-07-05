import { addItem, getStorage, updateItem } from './storage';
import { getCurrentUser } from './auth';

export const addVoteLog = (communityId, change, type, note = '') => {
  const user = getCurrentUser();
  const communities = getStorage('communities');
  const community = communities.find(c => c.id === communityId);
  
  if (!community) throw new Error('Topluluk bulunamadı');

  let currentVotes = Number(community.currentVotes) || 0;
  const numChange = Number(change);
  
  if (isNaN(numChange)) throw new Error('Geçersiz oy değişimi');

  let totalAfterChange = 0;
  if (type === 'set_total') {
    totalAfterChange = numChange;
    // Calculate the difference just for the log
    change = totalAfterChange - currentVotes; 
  } else {
    totalAfterChange = currentVotes + numChange;
  }

  // Prevent negative totals
  if (totalAfterChange < 0) totalAfterChange = 0;

  // 1. Create log
  const log = {
    communityId,
    change: type === 'set_total' ? change : numChange,
    totalAfterChange,
    type,
    note,
    createdAt: new Date().toISOString(),
    createdBy: user ? user.name : 'Sistem'
  };

  addItem('votes', log);

  // 2. Update community
  updateItem('communities', communityId, { 
    currentVotes: totalAfterChange,
    updatedAt: new Date().toISOString()
  });

  return totalAfterChange;
};
