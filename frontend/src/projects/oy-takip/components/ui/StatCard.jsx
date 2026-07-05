import React from 'react';

const StatCard = ({ title, value, subtext, icon: Icon, trend }) => {
  return (
    <div className="bg-white/[0.04] border border-white/[0.08] rounded-xl p-5 backdrop-blur-md relative overflow-hidden group">
      <div className="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
        {Icon && <Icon size={48} />}
      </div>
      <div className="flex flex-col relative z-10">
        <span className="text-gray-400 text-sm font-medium mb-1">{title}</span>
        <span className="text-3xl font-bold text-white mb-2">{value}</span>
        {subtext && (
          <div className="flex items-center text-sm">
            {trend === 'up' && <span className="text-green-400 mr-2">↑</span>}
            {trend === 'down' && <span className="text-red-400 mr-2">↓</span>}
            <span className="text-gray-500">{subtext}</span>
          </div>
        )}
      </div>
    </div>
  );
};

export default StatCard;
