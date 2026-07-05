import React, { useState } from 'react';
import { Link, Outlet } from 'react-router-dom';
import { logout } from '../../utils/auth';
import { LayoutDashboard, CheckSquare, Target, Clock, LogOut, Menu, X } from 'lucide-react';

const CommunityLayout = () => {
  const [isSidebarOpen, setIsSidebarOpen] = useState(false);

  const toggleSidebar = () => setIsSidebarOpen(!isSidebarOpen);
  const closeSidebar = () => setIsSidebarOpen(false);

  return (
    <div className="flex h-screen overflow-hidden bg-[#1B1E27]">
      {/* Mobile Header */}
      <div className="md:hidden fixed top-0 left-0 right-0 h-16 bg-[#1B1E27] border-b border-white/10 z-50 flex items-center justify-between px-4">
        <h1 className="text-lg font-bold bg-gradient-to-r from-[#DA2F81] to-[#ffb74d] bg-clip-text text-transparent">
          Topluluk Paneli
        </h1>
        <button onClick={toggleSidebar} className="text-gray-300 hover:text-white p-2">
          {isSidebarOpen ? <X size={24} /> : <Menu size={24} />}
        </button>
      </div>

      {/* Backdrop */}
      {isSidebarOpen && (
        <div 
          className="md:hidden fixed inset-0 bg-black/60 z-40 backdrop-blur-sm"
          onClick={closeSidebar}
        />
      )}

      {/* Sidebar */}
      <aside className={`fixed md:static inset-y-0 left-0 z-50 w-64 bg-[#1B1E27] border-r border-white/10 flex flex-col transform transition-transform duration-300 ease-in-out ${isSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'}`}>
        <div className="p-6 hidden md:block">
          <h1 className="text-xl font-bold bg-gradient-to-r from-[#DA2F81] to-[#ffb74d] bg-clip-text text-transparent">
            Topluluk Paneli
          </h1>
        </div>
        
        {/* Mobile top spacer */}
        <div className="h-16 md:hidden"></div>

        <nav className="flex-1 px-4 space-y-2 mt-4 md:mt-0 overflow-y-auto">
          <Link onClick={closeSidebar} to="/projects/oy-takip/community" className="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
            <LayoutDashboard size={20} />
            <span>Dashboard</span>
          </Link>
          <Link onClick={closeSidebar} to="/projects/oy-takip/community/tracker" className="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
            <CheckSquare size={20} />
            <span>Oy Girişi</span>
          </Link>
          <Link onClick={closeSidebar} to="/projects/oy-takip/community/competitors" className="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
            <Target size={20} />
            <span>Rakipler</span>
          </Link>
          <Link onClick={closeSidebar} to="/projects/oy-takip/community/history" className="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
            <Clock size={20} />
            <span>Geçmiş</span>
          </Link>
        </nav>
        
        <div className="p-4 border-t border-white/10">
          <button 
            onClick={() => { logout(); window.location.href = '/projects/oy-takip/login'; }}
            className="flex items-center gap-3 w-full px-4 py-3 text-gray-400 hover:text-[#DA2F81] hover:bg-[#DA2F81]/10 rounded-lg transition-colors"
          >
            <LogOut size={20} />
            <span>Çıkış Yap</span>
          </button>
        </div>
      </aside>

      {/* Main Content */}
      <main className="flex-1 overflow-y-auto bg-[#1B1E27] p-4 pt-20 md:p-8 md:pt-8 relative">
        {/* Glow Effects */}
        <div className="absolute top-0 right-0 w-[400px] h-[400px] bg-[#DA2F81]/5 blur-[120px] rounded-full pointer-events-none" />
        <div className="absolute bottom-0 left-0 w-[400px] h-[400px] bg-[#ffb74d]/5 blur-[120px] rounded-full pointer-events-none" />
        
        <div className="relative z-10">
          <Outlet />
        </div>
      </main>
    </div>
  );
};

export default CommunityLayout;
