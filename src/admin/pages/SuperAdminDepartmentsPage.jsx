import {
  Activity,
  Building2,
  CheckCircle2,
  Edit3,
  Folder,
  Grid3X3,
  Plus,
  Shield,
  Trash2,
  Users,
  X,
} from 'lucide-react';
import { useMemo, useState } from 'react';
import AdminLayout from '../components/AdminLayout.jsx';
import StatPanel from '../components/StatPanel.jsx';
import ViperaDevSignature from '../components/ViperaDevSignature.jsx';

const ICONS = {
  activity: Activity,
  building: Building2,
  folder: Folder,
  grid: Grid3X3,
  shield: Shield,
  users: Users,
};

function CsrfField({ token }) {
  return <input type="hidden" name="csrf_token" value={token || ''} />;
}

function Modal({ title, description, open, onClose, children, tone = 'orange' }) {
  if (!open) {
    return null;
  }

  return (
    <div className="admin-modal-backdrop" role="presentation" onMouseDown={onClose}>
      <section
        className={`admin-modal admin-modal--${tone}`}
        role="dialog"
        aria-modal="true"
        aria-label={title}
        onMouseDown={(event) => event.stopPropagation()}
      >
        <header className="admin-modal__header">
          <div>
            <h2>{title}</h2>
            {description ? <p>{description}</p> : null}
          </div>
          <button className="admin-icon-button" type="button" aria-label="Kapat" onClick={onClose}>
            <X size={18} />
          </button>
        </header>
        <div className="admin-modal__body">{children}</div>
      </section>
    </div>
  );
}

function CreateDepartmentForm({ action, csrfToken }) {
  return (
    <form action={action} method="POST" className="admin-form">
      <CsrfField token={csrfToken} />
      <div className="admin-form__grid">
        <label>
          <span>Ad *</span>
          <input name="name" required placeholder="Sosyal Medya" />
        </label>
        <label>
          <span>Renk</span>
          <input className="department-color-input" name="color" type="color" defaultValue="#00BCD4" />
        </label>
      </div>
      <label>
        <span>Açıklama</span>
        <textarea name="description" placeholder="Departmanın işlevi..." />
      </label>
      <label>
        <span>İkon</span>
        <input name="icon" placeholder="folder" defaultValue="folder" />
      </label>
      <label className="admin-form__checkbox">
        <input type="checkbox" name="is_management" value="1" />
        <span>Yönetim paneli ayrıcalığı ver</span>
      </label>
      <div className="admin-form__actions">
        <button className="admin-button admin-button--primary" type="submit">
          Departman oluştur
        </button>
      </div>
    </form>
  );
}

function DepartmentCard({ department, actions, onDelete }) {
  const Icon = ICONS[department.icon] || Folder;
  const active = Number(department.is_active);
  const management = Number(department.is_management);

  return (
    <article className={`department-card ${active ? 'is-active' : 'is-inactive'}`}>
      <div className="department-card__signal" style={{ '--department-color': department.color || '#00BCD4' }} />
      <div className="department-card__top">
        <div className="department-card__icon" style={{ '--department-color': department.color || '#00BCD4' }}>
          <Icon size={22} />
        </div>
        <div className="department-card__badges">
          <span className={`admin-status admin-status--${active ? 'active' : 'inactive'}`}>{active ? 'Aktif' : 'Pasif'}</span>
          <span className={`department-type ${management ? 'department-type--management' : 'department-type--standard'}`}>
            {management ? 'Yönetim' : 'Standart'}
          </span>
        </div>
      </div>
      <div className="department-card__body">
        <h3>{department.name}</h3>
        <code>{department.slug}</code>
        <p>{department.description || 'Açıklama belirtilmedi.'}</p>
      </div>
      <div className="department-card__footer">
        <a className="admin-button admin-button--ghost" href={`${actions.editBase}/${department.id}/edit`}>
          <Edit3 size={16} />
          Düzenle
        </a>
        <button className="admin-button admin-button--danger" type="button" onClick={() => onDelete(department)}>
          <Trash2 size={16} />
          Sil
        </button>
      </div>
    </article>
  );
}

function EmptyDepartments({ asset }) {
  return (
    <div className="admin-empty departments-empty">
      <Building2 size={36} />
      <strong>Henüz departman yok.</strong>
      <span>İlk departmanı oluşturarak panel organizasyonunu başlatın.</span>
      <ViperaDevSignature asset={asset} />
    </div>
  );
}

export default function SuperAdminDepartmentsPage({ boot }) {
  const data = boot.departments || {};
  const departments = data.items || [];
  const actions = data.actions || {};
  const [isCreateOpen, setCreateOpen] = useState(false);
  const [deleteDept, setDeleteDept] = useState(null);

  const stats = useMemo(() => {
    const active = departments.filter((department) => Number(department.is_active)).length;
    const management = departments.filter((department) => Number(department.is_management)).length;
    return { active, management, standard: departments.length - management };
  }, [departments]);

  return (
    <AdminLayout boot={boot}>
      <div className="admin-page-heading">
        <div>
          <span className="dashboard-hero__eyebrow">Department Registry</span>
          <h2>Departmanlar</h2>
          <p>Organizasyon birimlerini, yönetim ayrıcalıklarını ve panel görünürlüğünü yönetin.</p>
        </div>
        <button className="admin-button admin-button--primary" type="button" onClick={() => setCreateOpen(true)}>
          <Plus size={18} />
          Yeni Departman
        </button>
      </div>

      {boot.flash?.message ? (
        <div className={`admin-alert admin-alert--${boot.flash.type || 'success'}`}>{boot.flash.message}</div>
      ) : null}

      <section className="admin-stats-grid departments-stats" aria-label="Departman özeti">
        <StatPanel icon={<Building2 size={22} />} label="Toplam" value={departments.length} detail="Kayıtlı departman" tone="orange" />
        <StatPanel icon={<CheckCircle2 size={22} />} label="Aktif" value={stats.active} detail="Panelde kullanılabilir" tone="green" />
        <StatPanel icon={<Shield size={22} />} label="Yönetim" value={stats.management} detail="Ayrıcalıklı alan" tone="red" />
        <StatPanel icon={<Users size={22} />} label="Standart" value={stats.standard} detail="Topluluk birimi" tone="teal" />
      </section>

      {departments.length === 0 ? (
        <EmptyDepartments asset={boot.assets?.viperaDev} />
      ) : (
        <section className="departments-grid" aria-label="Departman listesi">
          {departments.map((department) => (
            <DepartmentCard department={department} actions={actions} onDelete={setDeleteDept} key={department.id} />
          ))}
        </section>
      )}

      <Modal
        title="Yeni departman"
        description="Slug ve benzersizlik kontrolü mevcut PHP endpointinde kalır."
        open={isCreateOpen}
        onClose={() => setCreateOpen(false)}
      >
        <CreateDepartmentForm action={actions.create} csrfToken={boot.csrfToken} />
      </Modal>

      <Modal
        title="Departmanı sil"
        description={deleteDept?.name}
        open={Boolean(deleteDept)}
        onClose={() => setDeleteDept(null)}
        tone="danger"
      >
        <form action={`${actions.deleteBase || ''}/${deleteDept?.id || ''}/delete`} method="POST" className="admin-form">
          <CsrfField token={boot.csrfToken} />
          <div className="admin-warning-box">Bu işlem departmanı kalıcı olarak siler. Bağlı kayıtları etkilemeden önce backend kurallarını kontrol eder.</div>
          <div className="admin-form__actions">
            <button className="admin-button admin-button--danger" type="submit">
              Sil
            </button>
          </div>
        </form>
      </Modal>
    </AdminLayout>
  );
}
