import { Building2, Crown, Layers3, Plus, Sparkles, Trash2, X } from 'lucide-react';
import { useMemo, useState } from 'react';
import AdminLayout from '../components/AdminLayout.jsx';
import StatPanel from '../components/StatPanel.jsx';
import ViperaDevSignature from '../components/ViperaDevSignature.jsx';

const typeMeta = {
  board: { label: 'Ust Yonetim', icon: Crown },
  active: { label: 'Aktif Komite', icon: Building2 },
  special: { label: 'Ozel Birim', icon: Sparkles },
  planned: { label: 'Planlanan', icon: Layers3 },
};

function CsrfField({ token }) {
  return <input type="hidden" name="csrf_token" value={token || ''} />;
}

function CommitteeModal({ boot, open, onClose }) {
  if (!open) return null;

  return (
    <div className="admin-modal-backdrop" role="presentation" onMouseDown={onClose}>
      <section className="admin-modal admin-modal--orange" role="dialog" aria-modal="true" onMouseDown={(event) => event.stopPropagation()}>
        <header className="admin-modal__header">
          <div>
            <h2>Yeni Komite Ekle</h2>
            <p>Organizasyon yapisina yeni bir komite veya planlanan birim ekleyin.</p>
          </div>
          <button className="admin-icon-button" type="button" aria-label="Kapat" onClick={onClose}>
            <X size={18} />
          </button>
        </header>
        <form action={boot.committees?.actions?.create} method="POST" className="admin-form">
          <CsrfField token={boot.csrfToken} />
          <div className="admin-form__grid">
            <label>
              <span>Komite Adi</span>
              <input name="name" required />
            </label>
            <label>
              <span>Ingilizce Ad</span>
              <input name="name_en" />
            </label>
            <label>
              <span>Tip</span>
              <select name="committee_type" defaultValue="active">
                <option value="board">Ust Yonetim</option>
                <option value="active">Aktif Komite</option>
                <option value="special">Ozel Birim</option>
                <option value="planned">Planlanan</option>
              </select>
            </label>
            <label>
              <span>Ikon</span>
              <input name="icon" defaultValue="🏛️" />
            </label>
          </div>
          <div className="admin-form__actions">
            <button className="admin-button admin-button--ghost" type="button" onClick={onClose}>
              Iptal
            </button>
            <button className="admin-button admin-button--primary" type="submit">
              Komite Olustur
            </button>
          </div>
        </form>
      </section>
    </div>
  );
}

function DeleteModal({ boot, committee, onClose }) {
  if (!committee) return null;

  return (
    <div className="admin-modal-backdrop" role="presentation" onMouseDown={onClose}>
      <section className="admin-modal admin-modal--danger" role="dialog" aria-modal="true" onMouseDown={(event) => event.stopPropagation()}>
        <header className="admin-modal__header">
          <div>
            <h2>Komite Silinsin mi?</h2>
            <p>{committee.name} kaydi mevcut backend silme route'u ile kaldirilacak.</p>
          </div>
          <button className="admin-icon-button" type="button" aria-label="Kapat" onClick={onClose}>
            <X size={18} />
          </button>
        </header>
        <form action={boot.committees?.actions?.delete} method="POST" className="admin-form">
          <CsrfField token={boot.csrfToken} />
          <input type="hidden" name="id" value={committee.id} />
          <div className="admin-warning-box">Bu islem mevcut controller ve veritabani davranisi korunarak calisir.</div>
          <div className="admin-form__actions">
            <button className="admin-button admin-button--ghost" type="button" onClick={onClose}>
              Vazgec
            </button>
            <button className="admin-button admin-button--danger" type="submit">
              Sil
            </button>
          </div>
        </form>
      </section>
    </div>
  );
}

function CommitteeCard({ committee, boot, onDelete }) {
  const meta = typeMeta[committee.committee_type] || typeMeta.active;
  const TypeIcon = meta.icon;
  const units = committee.units || [];

  return (
    <article className={`committee-card committee-card--${committee.committee_type || 'active'}`}>
      <div className="committee-card__top">
        <span className={`committee-type committee-type--${committee.committee_type || 'active'}`}>
          <TypeIcon size={15} />
          {meta.label}
        </span>
        <button className="admin-icon-button is-danger" type="button" title="Sil" onClick={() => onDelete(committee)}>
          <Trash2 size={16} />
        </button>
      </div>
      <div className="committee-card__identity">
        <span className="committee-card__emoji">{committee.icon || '🏛️'}</span>
        <div>
          <h3>{committee.name}</h3>
          <p>{committee.name_en || 'InnoMIS birimi'}</p>
        </div>
      </div>
      <div className="committee-unit-row">
        {units.slice(0, 4).map((unit) => (
          <span key={unit.id}>{unit.name}</span>
        ))}
        {units.length > 4 ? <span>+{units.length - 4}</span> : null}
        {units.length === 0 ? <span>Birim yok</span> : null}
      </div>
      <div className="committee-card__footer">
        <span>{Number(committee.member_count || 0).toLocaleString('tr-TR')} uye</span>
        <a href={`${boot.baseUrl}/admin/committees/${committee.id}`}>Detaylar</a>
      </div>
    </article>
  );
}

export default function CommitteesListPage({ boot }) {
  const committeesData = boot.committees || {};
  const grouped = committeesData.grouped || {};
  const stats = committeesData.stats || {};
  const [isCreateOpen, setCreateOpen] = useState(false);
  const [deleteCommittee, setDeleteCommittee] = useState(null);
  const allCommittees = useMemo(() => ['board', 'active', 'special', 'planned'].flatMap((type) => grouped[type] || []), [grouped]);

  return (
    <AdminLayout boot={boot}>
      <div className="admin-page-heading">
        <div>
          <span className="dashboard-hero__eyebrow">Organization Map</span>
          <h2>Komiteler ve Birimler</h2>
          <p>InnoMIS organizasyon yapisini, birimleri ve komite detaylarini tek ekrandan yonetin.</p>
        </div>
        <button className="admin-button admin-button--primary" type="button" onClick={() => setCreateOpen(true)}>
          <Plus size={18} />
          Yeni Komite
        </button>
      </div>

      <section className="committee-stat-grid">
        <StatPanel label="Toplam Komite" value={stats.total || allCommittees.length} tone="orange" />
        <StatPanel label="Aktif Yapilar" value={stats.active || 0} tone="green" />
        <StatPanel label="Planlanan" value={stats.planned || 0} tone="blue" />
      </section>

      <section className="committee-board">
        {['board', 'active', 'special', 'planned'].map((type) => {
          const items = grouped[type] || [];
          if (!items.length) return null;
          const meta = typeMeta[type];
          const Icon = meta.icon;
          return (
            <div className="committee-section" key={type}>
              <div className="committee-section__header">
                <Icon size={18} />
                <h3>{meta.label}</h3>
                <span>{items.length}</span>
              </div>
              <div className="committee-grid">
                {items.map((committee) => (
                  <CommitteeCard committee={committee} boot={boot} onDelete={setDeleteCommittee} key={committee.id} />
                ))}
              </div>
            </div>
          );
        })}

        {allCommittees.length === 0 ? (
          <div className="admin-empty">
            <Building2 size={34} />
            <strong>Henuz komite yok.</strong>
            <span>Yeni komite ekleyerek organizasyon haritasini baslatabilirsiniz.</span>
            <ViperaDevSignature asset={boot.assets?.viperaDev} />
          </div>
        ) : null}
      </section>

      <CommitteeModal boot={boot} open={isCreateOpen} onClose={() => setCreateOpen(false)} />
      <DeleteModal boot={boot} committee={deleteCommittee} onClose={() => setDeleteCommittee(null)} />
    </AdminLayout>
  );
}
