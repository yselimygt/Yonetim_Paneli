import React from 'react';
import { Link, Outlet } from 'react-router-dom';
import { logout } from '../../utils/auth';
import { LayoutDashboard, CheckSquare, Target, Clock, LogOut } from 'lucide-react';

const CommunityLayout = () => {
  return (
    <div className="flex h-screen overflow-hidden">
      {/* Sidebar */}
      <aside className="w-64 bg-white/[0.02] border-r border-white/[0.05] flex flex-col">
        <div className="p-6">
          <h1 className="text-xl font-bold bg-gradient-to-r from-[#f49f20] to-[#da2c2e] bg-clip-text text-transparent">
            Topluluk Paneli
          </h1>
        </div>
        
        <nav className="flex-1 px-4 space-y-2">
          <Link to="/projects/oy-takip/community" className="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
            <LayoutDashboard size={20} />
            <span>Dashboard</span>
          </Link>
          <Link to="/projects/oy-takip/community/tracker" className="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
            <CheckSquare size={20} />
            <span>Oy Girişi</span>
          </Link>
          <Link to="/projects/oy-takip/community/competitors" className="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
            <Target size={20} />
            <span>Rakipler</span>
          </Link>
          <Link to="/projects/oy-takip/community/history" className="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
            <Clock size={20} />
            <span>Geçmiş</span>
          </Link>
        </nav>
        
        <div className="p-4 border-t border-white/[0.05]">
          <button 
            onClick={() => { logout(); window.location.href = '/projects/oy-takip/login'; }}
            className="flex items-center gap-3 w-full px-4 py-3 text-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors"
          >
            <LogOut size={20} />
            <span>Çıkış Yap</span>
          </button>
        </div>
      </aside>

      {/* Main Content */}
      <main className="flex-1 overflow-y-auto bg-gradient-to-br from-[#06040d] to-[#0d0a1a] p-8">
        <Outlet />
      </main>
    </div>
  );
};

export default CommunityLayout;
