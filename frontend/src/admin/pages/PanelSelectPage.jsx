import { useState } from 'react';

export default function PanelSelectPage({ boot }) {
  const { departments = [], isSuperAdmin, admin, assets, baseUrl } = boot;
  const [hovered, setHovered] = useState(null);

  const roleLabel = (role) => role === 'director' ? 'Direktör' : 'Üye';

  return (
    <div className="psel-root">
      {/* ── Header ── */}
      <header className="psel-header">
        <img src={assets?.logoWhite} alt="InnoMIS" className="psel-logo" />
        <p className="psel-greeting">
          Merhaba, <strong>{admin?.name}</strong> — devam etmek istediğiniz departmanı seçin
        </p>
      </header>

      {/* ── Department Grid ── */}
      <main className="psel-grid">
        {departments.map((dept) => {
          const isDir = dept.role === 'director';
          return (
            <a
              key={dept.slug}
              href={`${baseUrl}/panel/${dept.slug}`}
              className={`psel-card${hovered === dept.slug ? ' is-hovered' : ''}${dept.is_management ? ' psel-card--mgmt' : ''}`}
              style={{ '--dept-color': dept.color || 'var(--admin-primary)' }}
              onMouseEnter={() => setHovered(dept.slug)}
              onMouseLeave={() => setHovered(null)}
            >
              {/* top signal bar */}
              <span className="psel-card__bar" />

              {dept.is_management && (
                <span className="psel-card__mgmt-badge">Yönetim</span>
              )}

              {/* icon */}
              <span className="psel-card__icon">
                {dept.icon_svg
                  ? <span dangerouslySetInnerHTML={{ __html: dept.icon_svg }} />
                  : <span className="psel-card__icon-letter">{dept.name?.[0]?.toUpperCase()}</span>
                }
              </span>

              <div className="psel-card__body">
                <div className="psel-card__name">{dept.name}</div>
                <span className={`psel-card__role${isDir ? ' psel-card__role--dir' : ''}`}>
                  {roleLabel(dept.role)}
                </span>
                {dept.description && (
                  <div className="psel-card__desc">
                    {dept.description.slice(0, 72)}{dept.description.length > 72 ? '…' : ''}
                  </div>
                )}
              </div>

              <div className="psel-card__arrow">→</div>
            </a>
          );
        })}

        {/* Super Admin kart */}
        {isSuperAdmin && (
          <a
            href={`${baseUrl}/superadmin`}
            className="psel-card psel-card--superadmin"
            style={{ '--dept-color': 'var(--admin-primary)' }}
          >
            <span className="psel-card__bar" />
            <span className="psel-card__mgmt-badge psel-card__mgmt-badge--sys">Sistem</span>
            <span className="psel-card__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8">
                <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
              </svg>
            </span>
            <div className="psel-card__body">
              <div className="psel-card__name">Süper Admin Paneli</div>
              <span className="psel-card__role psel-card__role--dir">Sistem Yöneticisi</span>
              <div className="psel-card__desc">Sistem ayarları, kullanıcı yönetimi ve genel atamalar.</div>
            </div>
            <div className="psel-card__arrow">→</div>
          </a>
        )}
      </main>

      {/* ── Footer ── */}
      <footer className="psel-footer">
        <a href={`${baseUrl}/logout`} className="psel-footer__link">Çıkış Yap</a>
        <span className="psel-footer__sep">·</span>
        <a href={`${baseUrl}/profile`} className="psel-footer__link">Profilim</a>
        <span className="psel-footer__vipera">
          <img src={assets?.viperaDev} alt="ViperaDev" />
        </span>
      </footer>
    </div>
  );
}
