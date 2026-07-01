import {
  Archive,
  Award,
  BarChart3,
  Book,
  BookOpen,
  CalendarDays,
  Check,
  CheckCircle2,
  Compass,
  CreditCard,
  Feather,
  FileText,
  Flag,
  Folder,
  Gift,
  Layers3,
  LayoutPanelTop,
  Lightbulb,
  Link2,
  Lock,
  MapPin,
  Monitor,
  Navigation,
  PieChart,
  QrCode,
  Save,
  Scissors,
  Share2,
  Shield,
  Target,
  Users,
  Video,
  Volume2,
} from 'lucide-react';
import { useMemo, useState } from 'react';
import AdminLayout from '../components/AdminLayout.jsx';
import StatPanel from '../components/StatPanel.jsx';
import ViperaDevSignature from '../components/ViperaDevSignature.jsx';

const ICONS = {
  archive: Archive,
  award: Award,
  'bar-chart': BarChart3,
  book: Book,
  'book-open': BookOpen,
  calendar: CalendarDays,
  'check-circle': CheckCircle2,
  compass: Compass,
  'credit-card': CreditCard,
  feather: Feather,
  'file-text': FileText,
  flag: Flag,
  folder: Folder,
  gift: Gift,
  layers: Layers3,
  layout: LayoutPanelTop,
  lightbulb: Lightbulb,
  link: Link2,
  lock: Lock,
  'map-pin': MapPin,
  monitor: Monitor,
  navigation: Navigation,
  'pie-chart': PieChart,
  qr: QrCode,
  scissors: Scissors,
  'share-2': Share2,
  shield: Shield,
  target: Target,
  users: Users,
  video: Video,
  'volume-2': Volume2,
};

function CsrfField({ token }) {
  return <input type="hidden" name="csrf_token" value={token || ''} />;
}

function DepartmentRail({ departments, selectedDeptId, selectBase }) {
  if (!departments.length) {
    return <div className="apps-rail__empty">Departman bulunamadı.</div>;
  }

  return (
    <aside className="apps-rail" aria-label="Departman seçimi">
      <div className="apps-rail__title">Departmanlar</div>
      <div className="apps-rail__list">
        {departments.map((department) => {
          const active = Number(department.id) === Number(selectedDeptId);
          return (
            <a
              className={`apps-dept-link ${active ? 'is-active' : ''}`}
              href={`${selectBase}?dept_id=${department.id}`}
              key={department.id}
            >
              <span>{department.name}</span>
              <small>{Number(department.is_management) ? 'Yönetim' : 'Departman'}</small>
            </a>
          );
        })}
      </div>
    </aside>
  );
}

function ModuleCard({ module, selected, onToggle }) {
  const Icon = ICONS[module.icon] || Monitor;

  function handleKeyDown(event) {
    if (event.key === 'Enter' || event.key === ' ') {
      event.preventDefault();
      onToggle(module.type);
    }
  }

  return (
    <article
      className={`apps-module-card ${selected ? 'is-selected' : ''}`}
      role="button"
      tabIndex={0}
      aria-pressed={selected}
      onClick={() => onToggle(module.type)}
      onKeyDown={handleKeyDown}
    >
      <input type="checkbox" name="modules[]" value={module.type} checked={selected} readOnly hidden />
      <div className="apps-module-card__check" aria-hidden="true">
        <Check size={15} />
      </div>
      <div className="apps-module-card__icon" aria-hidden="true">
        <Icon size={22} />
      </div>
      <div className="apps-module-card__content">
        <strong>{module.name}</strong>
        <span>{module.type}</span>
      </div>
      <div className="apps-module-card__fields" onClick={(event) => event.stopPropagation()}>
        <label>
          <span>Görünen ad</span>
          <input name={`names[${module.type}]`} defaultValue={module.custom_name || module.name} />
        </label>
        <label>
          <span>Sıra</span>
          <input name={`sort_orders[${module.type}]`} type="number" defaultValue={module.sort_order || 0} />
        </label>
      </div>
    </article>
  );
}

function EmptySelection({ asset }) {
  return (
    <div className="admin-empty apps-empty">
      <LayoutPanelTop size={38} />
      <strong>Departman seçin.</strong>
      <span>Modül matrisi seçilen departman için açılır; kayıtlar mevcut PHP endpointine gönderilir.</span>
      <ViperaDevSignature asset={asset} />
    </div>
  );
}

export default function SuperAdminAppsPage({ boot }) {
  const data = boot.appsAssignment || {};
  const departments = data.departments || [];
  const modules = data.modules || [];
  const selectedDept = data.selectedDept;
  const actions = data.actions || {};
  const initialSelected = useMemo(() => modules.filter((module) => module.assigned).map((module) => module.type), [modules]);
  const [selectedModules, setSelectedModules] = useState(initialSelected);

  function toggleModule(type) {
    setSelectedModules((current) => (current.includes(type) ? current.filter((item) => item !== type) : [...current, type]));
  }

  const assignedCount = selectedModules.length;
  const activeDepartments = departments.filter((department) => Number(department.is_active)).length;

  return (
    <AdminLayout boot={boot}>
      <div className="admin-page-heading">
        <div>
          <span className="dashboard-hero__eyebrow">Module Matrix</span>
          <h2>Uygulama Ata</h2>
          <p>Departmanların panel sidebar'ında görünecek çekirdek modülleri yönetin.</p>
        </div>
        <span className="admin-role-badge">{selectedDept ? selectedDept.name : 'Departman seçilmedi'}</span>
      </div>

      {boot.flash?.message ? (
        <div className={`admin-alert admin-alert--${boot.flash.type || 'success'}`}>{boot.flash.message}</div>
      ) : null}

      <section className="admin-stats-grid apps-stats" aria-label="Uygulama atama özeti">
        <StatPanel icon={<Users size={22} />} label="Departman" value={departments.length} detail={`${activeDepartments} aktif`} tone="orange" />
        <StatPanel icon={<Layers3 size={22} />} label="Çekirdek Modül" value={modules.length} detail="Whitelist PHP tarafında" tone="red" />
        <StatPanel icon={<CheckCircle2 size={22} />} label="Seçili" value={assignedCount} detail={selectedDept ? selectedDept.name : 'Beklemede'} tone="green" />
        <StatPanel icon={<Shield size={22} />} label="Güvenlik" value="POST" detail="CSRF korumalı" tone="teal" />
      </section>

      <div className="apps-assignment">
        <DepartmentRail departments={departments} selectedDeptId={data.selectedDeptId} selectBase={actions.selectBase || ''} />

        <section className="apps-matrix">
          {!selectedDept ? (
            <EmptySelection asset={boot.assets?.viperaDev} />
          ) : (
            <form action={actions.assign} method="POST" className="apps-matrix__form">
              <CsrfField token={boot.csrfToken} />
              <input type="hidden" name="department_id" value={selectedDept.id} />

              <div className="apps-matrix__header">
                <div>
                  <span className="dashboard-hero__eyebrow">Atama Konsolu</span>
                  <h3>{selectedDept.name} uygulamaları</h3>
                  <p>Aktif olan kartlar departman panelinde görünecek. İsim ve sıra alanları backend tarafından aynen kaydedilir.</p>
                </div>
                <button className="admin-button admin-button--primary" type="submit">
                  <Save size={18} />
                  Değişiklikleri Kaydet
                </button>
              </div>

              <div className="apps-module-grid">
                {modules.map((module) => (
                  <ModuleCard module={module} selected={selectedModules.includes(module.type)} onToggle={toggleModule} key={module.type} />
                ))}
              </div>
            </form>
          )}
        </section>
      </div>
    </AdminLayout>
  );
}
