import React, { useState, useEffect } from 'react';
import { getStorage, updateItem, addItem } from '../../utils/storage';
import StatusBadge from '../../components/ui/StatusBadge';
import Button from '../../components/ui/Button';
import { Eye, X } from 'lucide-react';

const Applications = () => {
  const [applications, setApplications] = useState(getStorage('applications'));
  const [selectedApp, setSelectedApp] = useState(null);
  const [isMobile, setIsMobile] = useState(window.innerWidth < 768);

  useEffect(() => {
    const handleResize = () => setIsMobile(window.innerWidth < 768);
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  const handleApprove = (app) => {
    // Topluluk oluştur
    addItem('communities', {
      applicationId: app.id,
      name: app.communityName,
      university: app.university,
      category: app.category,
      categoryVotes: app.categoryVotes || {},
      status: 'active',
      planId: 'basic',
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString()
    });
    
    // Başvuruyu güncelle
    updateItem('applications', app.id, { status: 'active' });
    setApplications(getStorage('applications'));
    setSelectedApp(null);
  };

  const handleReject = (app) => {
    updateItem('applications', app.id, { status: 'rejected' });
    setApplications(getStorage('applications'));
    setSelectedApp(null);
  };

  const handleHold = (app) => {
    updateItem('applications', app.id, { status: 'pending' });
    setApplications(getStorage('applications'));
    setSelectedApp(null);
  };

  return (
    <div>
      <h2 className="text-2xl font-bold mb-6">Başvurular</h2>
      
      {/* Mobile/Desktop Conditional Table */}
      <div className="bg-white/[0.02] border border-white/10 rounded-2xl overflow-hidden">
        <table className="w-full text-left whitespace-nowrap">
          <thead className="bg-white/[0.05] text-gray-400">
            <tr>
              <th className="p-4 font-medium">Topluluk Adı</th>
              {!isMobile && <th className="p-4 font-medium">Üniversite</th>}
              {!isMobile && <th className="p-4 font-medium">Durum</th>}
              <th className="p-4 font-medium">İşlemler</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-white/5">
            {applications.map(app => (
              <tr key={app.id} className="hover:bg-white/[0.02]">
                <td className="p-4 font-medium">{app.communityName}</td>
                {!isMobile && <td className="p-4 text-gray-400">{app.university}</td>}
                {!isMobile && <td className="p-4"><StatusBadge status={app.status} /></td>}
                <td className="p-4">
                  {isMobile ? (
                    <button 
                      onClick={() => setSelectedApp(app)}
                      className="p-2 bg-white/5 hover:bg-white/10 rounded-lg text-gray-300 transition-colors"
                    >
                      <Eye size={18} />
                    </button>
                  ) : (
                    <div className="flex items-center gap-2">
                      {app.status === 'pending' && (
                        <>
                          <Button variant="success" onClick={() => handleApprove(app)} className="text-xs py-1 px-3">
                            Onayla
                          </Button>
                          <Button variant="danger" onClick={() => handleReject(app)} className="text-xs py-1 px-3">
                            Reddet
                          </Button>
                        </>
                      )}
                    </div>
                  )}
                </td>
              </tr>
            ))}
            {applications.length === 0 && (
              <tr>
                <td colSpan={isMobile ? 2 : 4} className="p-6 text-center text-gray-500">
                  Henüz başvuru bulunmuyor.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      {/* Mobile Details Modal */}
      {selectedApp && isMobile && (
        <div className="fixed inset-0 z-[60] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
          <style>{`
            @keyframes popIn {
              0% { opacity: 0; transform: scale(0.9); }
              100% { opacity: 1; transform: scale(1); }
            }
            .animate-pop-in {
              animation: popIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
          `}</style>
          <div className="bg-[#1B1E27] w-full max-w-md rounded-2xl border border-white/10 shadow-2xl overflow-hidden relative animate-pop-in">
            <div className="p-4 border-b border-white/10 flex justify-between items-center bg-white/[0.02]">
              <h3 className="font-bold text-lg text-white">Başvuru Detayı</h3>
              <button onClick={() => setSelectedApp(null)} className="text-gray-400 hover:text-white p-1 rounded-md">
                <X size={20} />
              </button>
            </div>
            
            <div className="p-6 space-y-4">
              <div>
                <label className="text-xs text-gray-500 uppercase">Topluluk Adı</label>
                <p className="font-medium text-white">{selectedApp.communityName}</p>
              </div>
              <div>
                <label className="text-xs text-gray-500 uppercase">Üniversite</label>
                <p className="text-gray-300">{selectedApp.university}</p>
              </div>
              <div>
                <label className="text-xs text-gray-500 uppercase">Durum</label>
                <div className="mt-1">
                  <StatusBadge status={selectedApp.status} />
                </div>
              </div>
              <div>
                <label className="text-xs text-gray-500 uppercase">Kategori</label>
                <p className="text-gray-300 text-sm whitespace-normal">{selectedApp.category}</p>
              </div>
            </div>
            
            <div className="p-4 border-t border-white/10 bg-white/[0.02] flex flex-col gap-2">
              {selectedApp.status === 'pending' ? (
                <>
                  <Button variant="success" onClick={() => handleApprove(selectedApp)} className="w-full justify-center">Onayla</Button>
                  <Button variant="danger" onClick={() => handleReject(selectedApp)} className="w-full justify-center">Reddet</Button>
                  <Button variant="secondary" onClick={() => handleHold(selectedApp)} className="w-full justify-center">Beklet</Button>
                </>
              ) : (
                <Button variant="secondary" onClick={() => setSelectedApp(null)} className="w-full justify-center">Kapat</Button>
              )}
            </div>
          </div>
        </div>
      )}

    </div>
  );
};

export default Applications;
