import { useState } from 'react';
import { ChevronDown, ChevronRight, Users, Box, Building } from 'lucide-react';
import Panel from '../components/Panel.jsx';
import StatPanel from '../components/StatPanel.jsx';

export default function ManagementReportsPage({ boot }) {
  const { allDepts = [] } = boot;
  const [openDepts, setOpenDepts] = useState({});

  const toggleDept = (id) => {
    setOpenDepts(prev => ({ ...prev, [id]: !prev[id] }));
  };

  const totalUsers = allDepts.reduce((acc, d) => acc + (d.member_count || 0), 0);
  const totalApps = allDepts.reduce((acc, d) => acc + (d.app_count || 0), 0);

  return (
    <div className="mgmt-root fade-in-up">
      {/* ── Summary ── */}
      <div className="dept-dash-stats" style={{ marginBottom: '32px' }}>
        <StatPanel value={allDepts.length} label="Departman" />
        <StatPanel value={totalUsers} label="Toplam Üye (Departman Atamaları)" />
        <StatPanel value={totalApps} label="Dağıtılmış Uygulama" />
      </div>

      {/* ── Department List ── */}
      <div className="mgmt-depts">
        {allDepts.map(dept => {
          const isOpen = !!openDepts[dept.id];
          return (
            <Panel key={dept.id} padding="0" className="mgmt-dept-card">
              {/* Top color bar */}
              <div 
                className="mgmt-dept-card__bar" 
                style={{ background: dept.color || 'var(--admin-primary)' }} 
              />
              
              {/* Header (Clickable) */}
              <div 
                className="mgmt-dept-card__header"
                onClick={() => toggleDept(dept.id)}
              >
                <div className="mgmt-dept-card__header-main">
                  <div className="mgmt-dept-card__icon">
                    {isOpen ? <ChevronDown size={20} /> : <ChevronRight size={20} />}
                  </div>
                  <div>
                    <div className="mgmt-dept-card__name">
                      {dept.name}
                      {dept.is_management === 1 && (
                        <span className="mgmt-dept-card__mgmt-badge">Yönetim</span>
                      )}
                    </div>
                    <div className="mgmt-dept-card__meta">
                      <span className="mgmt-meta-item"><Users size={14}/> {dept.member_count} Üye</span>
                      <span className="mgmt-meta-item"><Box size={14}/> {dept.app_count} Uygulama</span>
                    </div>
                  </div>
                </div>
              </div>

              {/* Members List (Collapsible) */}
              {isOpen && (
                <div className="mgmt-dept-card__body">
                  {dept.members && dept.members.length > 0 ? (
                    <div className="mgmt-members-list">
                      {dept.members.map((m, idx) => (
                        <div key={idx} className="mgmt-member-row">
                          <div className="mgmt-member-row__avatar">
                            {m.avatar ? (
                              <img src={`/public/${m.avatar}`} alt="" style={{width: '100%', height: '100%', borderRadius: '50%', objectFit: 'cover'}} />
                            ) : (
                              m.user_name?.[0]?.toUpperCase()
                            )}
                          </div>
                          <div className="mgmt-member-row__info">
                            <div className="mgmt-member-row__name">{m.user_name}</div>
                            <div className="mgmt-member-row__email">{m.email}</div>
                          </div>
                          <div className="mgmt-member-row__role">
                            <span className={['director', 'president', 'vice_president'].includes(m.role) ? 'mgmt-role-badge dir' : 'mgmt-role-badge'}>
                              {m.role === 'director' ? 'Direktör' : m.role === 'president' ? 'Başkan' : m.role === 'vice_president' ? 'Başkan Yardımcısı' : 'Üye'}
                            </span>
                          </div>
                        </div>
                      ))}
                    </div>
                  ) : (
                    <div className="mgmt-empty">Bu departmanda henüz üye yok.</div>
                  )}
                </div>
              )}
            </Panel>
          );
        })}
      </div>
    </div>
  );
}
