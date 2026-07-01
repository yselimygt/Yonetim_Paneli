import { 
  Users, 
  Grid, 
  LayoutTemplate, 
  FileText, 
  Megaphone, 
  BookOpen, 
  Calendar, 
  Activity,
  CreditCard,
  Briefcase
} from 'lucide-react';
import StatPanel from '../components/StatPanel.jsx';
import Panel from '../components/Panel.jsx';

export default function DeptDashboardPage({ boot }) {
  const { 
    currentDept, 
    userRole, 
    deptApps = [], 
    members = [], 
    memberCount = 0, 
    appCount = 0,
    totalUsers = 0,
    totalDepts = 0,
    baseUrl
  } = boot;

  const getAppIcon = (type) => {
    switch (type) {
      case 'form':
      case 'form_manager': return <LayoutTemplate size={20} />;
      case 'report': return <FileText size={20} />;
      case 'announcement': return <Megaphone size={20} />;
      case 'directory': return <BookOpen size={20} />;
      case 'calendar': return <Calendar size={20} />;
      case 'finance': return <Activity size={20} />;
      case 'dues': return <CreditCard size={20} />;
      default: return <Grid size={20} />;
    }
  };

  const getAppColor = (type) => {
    switch (type) {
      case 'form':
      case 'form_manager': return 'var(--admin-primary)'; // neon green
      case 'report': return '#10b981'; // emerald
      case 'announcement': return '#f59e0b'; // amber
      case 'directory': return '#8b5cf6'; // violet
      case 'calendar': return '#ec4899'; // pink
      case 'finance': return '#38bdf8'; // sky
      case 'dues': return '#f43f5e'; // rose
      default: return 'var(--admin-primary)';
    }
  };

  const getAppHref = (app) => {
    if (['form', 'form_manager'].includes(app.module_type)) {
      return `${baseUrl}/panel/${currentDept.slug}/forms`;
    }
    if (app.module_type === 'finance') {
      return `${baseUrl}/panel/${currentDept.slug}/finance`;
    }
    if (app.module_type === 'dues') {
      return `${baseUrl}/panel/${currentDept.slug}/dues`;
    }
    return `${baseUrl}/panel/${currentDept.slug}/apps/${app.slug}`;
  };

  // Dedup forms for view
  const renderedModules = new Set();
  const displayApps = [];
  
  deptApps.forEach(app => {
    if (app.module_type === 'form' && !['form', 'form_manager'].includes(app.slug)) return;
    
    let modGroup = app.module_type;
    if (['form', 'form_manager'].includes(modGroup)) modGroup = 'form_grouped';
    
    if (renderedModules.has(modGroup)) return;
    renderedModules.add(modGroup);
    
    // Check director only
    if (app.director_only && userRole !== 'director' && userRole !== 'super_admin') return;
    
    displayApps.push(app);
  });

  return (
    <div className="dept-dash-root fade-in-up">
      {/* ── Welcome Area ── */}
      <div className="dept-dash-hero">
        <h2 className="dept-dash-hero__title">
          Hoş geldiniz, {boot.admin?.name}
        </h2>
        <div className="dept-dash-hero__subtitle">
          {currentDept.name} departmanı paneli
          {currentDept.is_management === 1 && (
            <span className="dept-dash-hero__mgmt">
              YÖNETİM PANELİ
            </span>
          )}
        </div>
      </div>

      {/* ── Stats ── */}
      <div className="dept-dash-stats">
        <StatPanel value={memberCount} label="Üye" />
        <StatPanel value={appCount} label="Uygulama" />
        {(currentDept.is_management === 1 || boot.admin?.role === 'super_admin') && (
          <>
            <StatPanel value={totalUsers} label="Toplam Üye" />
            <StatPanel value={totalDepts} label="Departman" />
          </>
        )}
      </div>

      {/* ── Quick Access (Apps) ── */}
      {displayApps.length > 0 && (
        <div className="dept-dash-section">
          <h3 className="dept-dash-section__title">Hızlı Erişim</h3>
          <div className="dept-dash-apps">
            {displayApps.map((app) => (
              <a 
                key={app.id} 
                href={getAppHref(app)} 
                className="dept-app-card"
                style={{'--app-c': getAppColor(app.module_type)}}
              >
                <div className="dept-app-card__icon">
                  {getAppIcon(app.module_type)}
                </div>
                <div className="dept-app-card__body">
                  <div className="dept-app-card__name">
                    {app.module_type === 'form_manager' ? 'Form Yönetimi' : app.name}
                  </div>
                  <div className="dept-app-card__desc">
                    {app.module_type === 'form_manager' ? 'Departman formlarını düzenle' : app.description || 'Uygulama arayüzü'}
                  </div>
                </div>
                <div className="dept-app-card__arrow">→</div>
              </a>
            ))}
          </div>
        </div>
      )}

      {/* ── Members Preview ── */}
      {members.length > 0 && (
        <div className="dept-dash-section">
          <h3 className="dept-dash-section__title">Üyeler ({memberCount})</h3>
          <Panel padding="0">
            <div className="dept-members-list">
              {members.slice(0, 5).map(m => (
                <div key={m.id} className="dept-member-row">
                  <div className="dept-member-row__avatar">
                    {m.name?.[0]?.toUpperCase()}
                  </div>
                  <div className="dept-member-row__info">
                    <div className="dept-member-row__name">{m.name}</div>
                    <div className="dept-member-row__role">
                      {m.role === 'director' ? 'Direktör' : 'Üye'}
                    </div>
                  </div>
                </div>
              ))}
              {members.length > 5 && (
                <a href={`${baseUrl}/panel/${currentDept.slug}/members`} className="dept-member-more">
                  Tümünü Gör ({members.length}) →
                </a>
              )}
            </div>
          </Panel>
        </div>
      )}
    </div>
  );
}
