import React from 'react';

const StatusBadge = ({ status }) => {
  const statusConfig = {
    pending: { color: 'bg-yellow-500/20 text-yellow-400 border-yellow-500/20', label: 'Beklemede' },
    waiting_payment: { color: 'bg-orange-500/20 text-orange-400 border-orange-500/20', label: 'Ödeme Bekliyor' },
    active: { color: 'bg-green-500/20 text-green-400 border-green-500/20', label: 'Aktif' },
    locked: { color: 'bg-purple-500/20 text-purple-400 border-purple-500/20', label: 'Kilitli' },
    expired: { color: 'bg-gray-500/20 text-gray-400 border-gray-500/20', label: 'Süresi Doldu' },
    rejected: { color: 'bg-red-500/20 text-red-400 border-red-500/20', label: 'Reddedildi' }
  };

  const config = statusConfig[status] || { color: 'bg-gray-500/20 text-gray-400 border-gray-500/20', label: status };

  return (
    <span className={`px-2.5 py-1 text-xs font-semibold rounded-full border ${config.color}`}>
      {config.label}
    </span>
  );
};

export default StatusBadge;
