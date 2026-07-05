import React from 'react';
import StatCard from '../../components/ui/StatCard';
import { Users, FileText, CheckCircle, Activity } from 'lucide-react';
import { getStorage } from '../../utils/storage';

const AdminDashboard = () => {
  const applications = getStorage('applications');
  const communities = getStorage('communities');
  const votes = getStorage('votes');

  const pendingApps = applications.filter(a => a.status === 'pending').length;
  const activeComms = communities.filter(c => c.status === 'active').length;
  const todayVotes = votes.filter(v => new Date(v.createdAt).toDateString() === new Date().toDateString()).length;

  return (
    <div>
      <h2 className="text-2xl font-bold mb-6">Admin Dashboard</h2>
      
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatCard title="Toplam Başvuru" value={applications.length} icon={FileText} />
        <StatCard title="Bekleyen Başvurular" value={pendingApps} icon={CheckCircle} />
        <StatCard title="Aktif Topluluklar" value={activeComms} icon={Users} />
        <StatCard title="Bugünkü İşlem" value={todayVotes} icon={Activity} />
      </div>
    </div>
  );
};

export default AdminDashboard;
