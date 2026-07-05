import React from 'react';
import { Link, Outlet } from 'react-router-dom';
import { logout } from '../../utils/auth';
import { LayoutDashboard, Users, FileText, Settings, LogOut } from 'lucide-react';

const AdminLayout = () => {
  return (
    <div className="flex h-screen overflow-hidden">
      {/* Sidebar */}
      <aside className="w-64 bg-white/[0.02] border-r border-white/[0.05] flex flex-col">
        <div className="p-6">
          <h1 className="text-xl font-bold bg-gradient-to-r from-[#f49f20] to-[#da2c2e] bg-clip-text text-transparent">
            Admin Panel
          </h1>
        </div>
        
        <nav className="flex-1 px-4 space-y-2">
          <Link to="/projects/oy-takip/admin" className="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
            <LayoutDashboard size={20} />
            <span>Dashboard</span>
          </Link>
          <Link to="/projects/oy-takip/admin/applications" className="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
            <FileText size={20} />
            <span>Başvurular</span>
          </Link>
          <Link to="/projects/oy-takip/admin/communities" className="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
            <Users size={20} />
            <span>Topluluklar</span>
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

export default AdminLayout;
