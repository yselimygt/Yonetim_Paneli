import { Activity, AppWindow, Building2, Link2, Users } from 'lucide-react';
import AdminLayout from '../components/AdminLayout.jsx';
import Panel from '../components/Panel.jsx';
import StatPanel from '../components/StatPanel.jsx';
import ViperaDevSignature from '../components/ViperaDevSignature.jsx';

const numberFormatter = new Intl.NumberFormat('tr-TR');

function formatNumber(value) {
  return numberFormatter.format(Number(value || 0));
}

function MiniLineChart({ labels = [], data = [] }) {
  const width = 680;
  const height = 190;
  const values = data.map((value) => Number(value || 0));
  const max = Math.max(...values, 1);
  const points = values.map((value, index) => {
    const x = values.length === 1 ? width / 2 : (index / (values.length - 1)) * width;
    const y = height - (value / max) * (height - 24) - 12;
    return `${x},${y}`;
  });

  return (
    <div className="mini-chart" role="img" aria-label="Departman üye yoğunluğu grafiği">
      <svg viewBox={`0 0 ${width} ${height}`} preserveAspectRatio="none">
        <polyline points={points.join(' ')} fill="none" stroke="url(#growthGradient)" strokeWidth="5" strokeLinecap="round" />
        <defs>
          <linearGradient id="growthGradient" x1="0" x2="1">
            <stop offset="0%" stopColor="#F49F20" />
            <stop offset="100%" stopColor="#DA2C2E" />
          </linearGradient>
        </defs>
      </svg>
      <div className="mini-chart__labels">
        {labels.map((label) => (
          <span key={label}>{label}</span>
        ))}
      </div>
    </div>
  );
}

function DepartmentList({ departments = [] }) {
  if (!departments.length) {
    return <div className="admin-empty admin-empty--compact">Departman dağılımı için veri yok.</div>;
  }

  const total = departments.reduce((sum, dept) => sum + Number(dept.member_count || 0), 0) || 1;

  return (
    <div className="committee-list">
      {departments.map((dept) => {
        const value = Number(dept.member_count || 0);
        const percent = Math.round((value / total) * 100);

        return (
          <div className="committee-list__row" key={dept.id || dept.slug || dept.name}>
            <div>
              <strong>{dept.name}</strong>
              <span>{formatNumber(value)} üye</span>
            </div>
            <div className="committee-list__meter" aria-label={`${dept.name} yüzde ${percent}`}>
              <span style={{ width: `${percent}%` }} />
            </div>
          </div>
        );
      })}
    </div>
  );
}

function RecentUsers({ items = [], viperaDevAsset }) {
  if (!items.length) {
    return (
      <div className="admin-empty">
        <Activity size={32} />
        <strong>Yeni kullanıcı bulunmuyor.</strong>
        <span>Kullanıcı kayıtları oluştuğunda burada görünecek.</span>
        <ViperaDevSignature asset={viperaDevAsset} />
      </div>
    );
  }

  return (
    <div className="activity-list">
      {items.map((item, index) => (
        <article className="activity-item" key={`${item.id || index}-${item.created_at || ''}`}>
          <div className="activity-item__marker">+</div>
          <div>
            <p>
              <strong>{item.name || 'Kullanıcı'}</strong> sisteme eklendi.
            </p>
            <span>
              {item.email || ''}
              {item.created_at ? ` • ${item.created_at}` : ''}
            </span>
          </div>
        </article>
      ))}
    </div>
  );
}

export default function DashboardPage({ boot }) {
  const dashboard = boot.dashboard || {};
  const stats = dashboard.stats || {};
  const departments = dashboard.departments || [];
  const recentUsers = dashboard.recentUsers || [];
  const chartDepartments = departments.slice(0, 6);

  return (
    <AdminLayout boot={boot}>
      <div className="dashboard-hero">
        <div>
          <span className="dashboard-hero__eyebrow">InnoMIS Control Surface</span>
          <h2>Yönetim merkezi tek ekranda.</h2>
          <p>Departmanlar, kullanıcılar, uygulama atamaları ve son kayıtlar PHP backend davranışı korunarak React yüzeyinde okunuyor.</p>
        </div>
        <ViperaDevSignature asset={boot.assets?.viperaDev} />
      </div>

      <section className="admin-stats-grid" aria-label="Dashboard metrikleri">
        <StatPanel icon={<Building2 size={22} />} label="Departman" value={formatNumber(stats.total_departments)} tone="orange" />
        <StatPanel icon={<Users size={22} />} label="Kullanıcı" value={formatNumber(stats.total_users)} tone="red" />
        <StatPanel icon={<AppWindow size={22} />} label="Uygulama" value={formatNumber(stats.total_apps)} tone="teal" />
        <StatPanel icon={<Link2 size={22} />} label="Atama" value={formatNumber(stats.total_assignments)} tone="green" />
      </section>

      <div className="admin-dashboard-grid">
        <Panel title="Departman Üye Yoğunluğu" className="admin-panel--wide">
          <MiniLineChart labels={chartDepartments.map((dept) => dept.name)} data={chartDepartments.map((dept) => dept.member_count)} />
        </Panel>

        <Panel title="Departman Dağılımı">
          <DepartmentList departments={departments} />
        </Panel>

        <Panel title="Son Kullanıcılar" className="admin-panel--wide">
          <RecentUsers items={recentUsers} viperaDevAsset={boot.assets?.viperaDev} />
        </Panel>
      </div>
    </AdminLayout>
  );
}
