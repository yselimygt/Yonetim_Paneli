import React, { useState } from 'react';
import { getStorage, updateItem, addItem } from '../../utils/storage';
import StatusBadge from '../../components/ui/StatusBadge';
import Button from '../../components/ui/Button';

const Applications = () => {
  const [applications, setApplications] = useState(getStorage('applications'));

  const handleApprove = (app) => {
    // Topluluk oluştur
    addItem('communities', {
      applicationId: app.id,
      name: app.communityName,
      university: app.university,
      category: app.category,
      status: 'active',
      currentVotes: Number(app.currentVotes) || 0,
      targetVotes: Number(app.targetVotes) || 0,
      planId: 'basic',
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString()
    });
    
    // Başvuruyu güncelle
    updateItem('applications', app.id, { status: 'active' });
    setApplications(getStorage('applications'));
  };

  return (
    <div>
      <h2 className="text-2xl font-bold mb-6">Başvurular</h2>
      <div className="bg-white/[0.02] border border-white/10 rounded-2xl overflow-hidden">
        <table className="w-full text-left">
          <thead className="bg-white/[0.05] text-gray-400">
            <tr>
              <th className="p-4 font-medium">Topluluk Adı</th>
              <th className="p-4 font-medium">Üniversite</th>
              <th className="p-4 font-medium">Durum</th>
              <th className="p-4 font-medium">İşlemler</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-white/5">
            {applications.map(app => (
              <tr key={app.id} className="hover:bg-white/[0.02]">
                <td className="p-4">{app.communityName}</td>
                <td className="p-4 text-gray-400">{app.university}</td>
                <td className="p-4"><StatusBadge status={app.status} /></td>
                <td className="p-4">
                  {app.status === 'pending' && (
                    <Button variant="success" onClick={() => handleApprove(app)} className="text-xs py-1 px-3">
                      Onayla
                    </Button>
                  )}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default Applications;
