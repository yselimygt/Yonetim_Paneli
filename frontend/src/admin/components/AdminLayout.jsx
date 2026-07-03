import { LogOut, Menu, X } from 'lucide-react';
import { useMemo, useState } from 'react';
import ViperaDevSignature from './ViperaDevSignature.jsx';

function normalizeApps(allowedApps) {
  if (!allowedApps || typeof allowedApps !== 'object') {
    return [];
  }

  return Object.entries(allowedApps).map(([section, apps]) => ({
    section,
    apps: Array.isArray(apps) ? apps : [],
  }));
}

function getInitials(name = 'Admin') {
  return name
    .split(' ')
    .filter(Boolean)
    .slice(0, 2)
    .map((part) => part[0])
    .join('')
    .toUpperCase();
}

function Sidebar({ boot, open, onClose }) {
  const groupedApps = useMemo(() => normalizeApps(boot.allowedApps), [boot.allowedApps]);
  const activeRoute = boot.currentRoute || '';
  const logo = boot.assets?.logoWhite || '/public/assets/logo/Yatay-Beyaz.png';
  const logoutUrl = `${boot.baseUrl || ''}/logout`;
  const initials = getInitials(boot.admin?.name);

  return (
    <>
      <button
        className={`admin-shell__overlay ${open ? 'is-open' : ''}`}
        type="button"
        aria-label="Menüyü kapat"
        onClick={onClose}
      />
      <aside className={`admin-sidebar ${open ? 'is-open' : ''}`}>
        <div className="admin-sidebar__brand">
          <img src={logo} alt="InnoMIS Community" className="admin-sidebar__logo" />
        </div>

        <nav className="admin-sidebar__nav" aria-label="Admin menüsü">
          {groupedApps.length === 0 ? (
            <div className="admin-sidebar__empty">Kullanılabilir uygulama bulunamadı.</div>
          ) : (
            groupedApps.map(({ section, apps }) => (
              <section key={section} className="admin-sidebar__section">
                <div className="admin-sidebar__section-title">{section}</div>
                {apps.map((app) => {
                  const href = `${boot.baseUrl || ''}${app.route || '#'}`;
                  const isActive = activeRoute.includes(app.route || app.slug);

                  return (
                    <a
                      href={href}
                      key={`${section}-${app.slug || app.name}`}
                      className={`admin-sidebar__item ${isActive ? 'is-active' : ''}`}
                      onClick={onClose}
                    >
                      <span
                        className="admin-sidebar__icon"
                        dangerouslySetInnerHTML={{ __html: app.icon_svg || '' }}
                        aria-hidden="true"
                      />
                      <span>{app.name}</span>
                    </a>
                  );
                })}
              </section>
            ))
          )}
        </nav>

        <div className="admin-sidebar__footer">
          <div className="admin-user">
            <div className="admin-user__avatar">
              {boot.admin?.avatar ? (
                <img src={`/public/${boot.admin.avatar}`} alt="Avatar" style={{width: '100%', height: '100%', borderRadius: '50%', objectFit: 'cover'}} />
              ) : (
                initials
              )}
            </div>
            <div className="admin-user__meta">
              <div className="admin-user__name">{boot.admin?.name || 'Admin'}</div>
              <div className="admin-user__role">{(boot.admin?.role || 'admin').replace('_', ' ')}</div>
            </div>
            <a className="admin-icon-button" href={logoutUrl} title="Çıkış yap">
              <LogOut size={18} />
            </a>
          </div>
          <ViperaDevSignature asset={boot.assets?.viperaDev} />
        </div>
      </aside>
    </>
  );
}

export default function AdminLayout({ boot, children }) {
  const [isNavOpen, setIsNavOpen] = useState(false);
  const role = (boot.admin?.role || 'admin').replace('_', ' ').toUpperCase();

  return (
    <div className="admin-shell">
      <Sidebar boot={boot} open={isNavOpen} onClose={() => setIsNavOpen(false)} />

      <main className="admin-main">
        <header className="admin-topbar">
          <div className="admin-topbar__title-row">
            <button
              className="admin-menu-button"
              type="button"
              aria-label={isNavOpen ? 'Menüyü kapat' : 'Menüyü aç'}
              onClick={() => setIsNavOpen((value) => !value)}
            >
              {isNavOpen ? <X size={20} /> : <Menu size={20} />}
            </button>
            <div>
              <h1>{boot.page?.title || 'Admin Panel'}</h1>
              {boot.page?.subtitle ? <p>{boot.page.subtitle}</p> : null}
            </div>
          </div>
          <span className="admin-role-badge">{role}</span>
        </header>

        <div className="admin-content">{children}</div>
      </main>
    </div>
  );
}
