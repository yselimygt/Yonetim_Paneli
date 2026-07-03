import { useState, useMemo } from 'react';
import { Search, UserCircle, Building } from 'lucide-react';
import Panel from '../components/Panel.jsx';

export default function ManagementDirectoryPage({ boot }) {
  const { directory = [], totalUserCount = 0 } = boot;
  const [searchQuery, setSearchQuery] = useState('');

  const filteredDirectory = useMemo(() => {
    if (!searchQuery.trim()) return directory;
    const lowerQ = searchQuery.toLowerCase();
    return directory.filter(u => 
      (u.name && u.name.toLowerCase().includes(lowerQ)) || 
      (u.email && u.email.toLowerCase().includes(lowerQ)) ||
      (u.depts_info && u.depts_info.toLowerCase().includes(lowerQ))
    );
  }, [directory, searchQuery]);

  const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('tr-TR', {
      day: '2-digit', month: 'short', year: 'numeric'
    });
  };

  const renderDepts = (deptsInfoStr) => {
    if (!deptsInfoStr) return <span className="mgmt-dir-chip empty">Departman Yok</span>;
    const parts = deptsInfoStr.split(',');
    return (
      <div className="mgmt-dir-chips">
        {parts.map((p, idx) => {
          const [dName, dRole] = p.split('|');
          const isDir = ['director', 'president', 'vice_president'].includes(dRole);
          return (
            <span key={idx} className={`mgmt-dir-chip${isDir ? ' is-dir' : ''}`}>
              {dName}
            </span>
          );
        })}
      </div>
    );
  };

  return (
    <div className="mgmt-dir-root fade-in-up">
      <Panel title={`Rehber (${totalUserCount} Kişi)`} padding="0">
        
        {/* ── Toolbar ── */}
        <div className="mgmt-dir-toolbar">
          <div className="mgmt-dir-search">
            <Search size={16} className="mgmt-dir-search-icon" />
            <input 
              type="text" 
              placeholder="İsim, e-posta veya departman ara..." 
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="mgmt-dir-search-input"
            />
          </div>
        </div>

        {/* ── Table (Desktop) ── */}
        <div className="mgmt-dir-table-wrap">
          <table className="admin-table mgmt-dir-table">
            <thead>
              <tr>
                <th>Üye</th>
                <th>E-posta</th>
                <th>Durum</th>
                <th>Departmanlar</th>
                <th>Kayıt Tarihi</th>
              </tr>
            </thead>
            <tbody>
              {filteredDirectory.length === 0 ? (
                <tr>
                  <td colSpan="5" className="admin-table-empty">
                    Kullanıcı bulunamadı.
                  </td>
                </tr>
              ) : (
                filteredDirectory.map((user) => (
                  <tr key={user.id}>
                    <td>
                      <div className="mgmt-dir-user">
                        <UserCircle size={20} className="mgmt-dir-avatar-icon" />
                        <span className="mgmt-dir-user-name">{user.name}</span>
                      </div>
                    </td>
                    <td><span className="mgmt-dir-email">{user.email}</span></td>
                    <td>
                      <span className={`status-badge status-badge--${user.is_active ? 'active' : 'inactive'}`}>
                        {user.is_active ? 'Aktif' : 'Pasif'}
                      </span>
                    </td>
                    <td>{renderDepts(user.depts_info)}</td>
                    <td><span className="mgmt-dir-date">{formatDate(user.created_at)}</span></td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>

        {/* ── Cards (Mobile) ── */}
        <div className="mgmt-dir-cards">
          {filteredDirectory.length === 0 ? (
            <div className="mgmt-empty">Kullanıcı bulunamadı.</div>
          ) : (
            filteredDirectory.map((user) => (
              <div key={user.id} className="mgmt-dir-card">
                <div className="mgmt-dir-card__header">
                  <div className="mgmt-dir-user">
                    <UserCircle size={24} className="mgmt-dir-avatar-icon" />
                    <div>
                      <div className="mgmt-dir-user-name">{user.name}</div>
                      <div className="mgmt-dir-email">{user.email}</div>
                    </div>
                  </div>
                  <span className={`status-badge status-badge--${user.is_active ? 'active' : 'inactive'}`}>
                    {user.is_active ? 'Aktif' : 'Pasif'}
                  </span>
                </div>
                <div className="mgmt-dir-card__body">
                  <div className="mgmt-dir-card__label">Departmanlar:</div>
                  {renderDepts(user.depts_info)}
                </div>
                <div className="mgmt-dir-card__footer">
                  Kayıt: {formatDate(user.created_at)}
                </div>
              </div>
            ))
          )}
        </div>
      </Panel>
    </div>
  );
}
