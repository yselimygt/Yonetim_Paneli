import { CheckCircle2, Clock3, Layers3, Pencil, Plus, ShieldCheck, Trash2, UserPlus, Users, X } from 'lucide-react';
import { useMemo, useState } from 'react';
import AdminLayout from '../components/AdminLayout.jsx';
import ViperaDevSignature from '../components/ViperaDevSignature.jsx';

const taskStatus = [
  { value: 'todo', label: 'Yapilacak', icon: Clock3 },
  { value: 'in_progress', label: 'Devam Ediyor', icon: Layers3 },
  { value: 'done', label: 'Tamamlandi', icon: CheckCircle2 },
];

function CsrfField({ token }) {
  return <input type="hidden" name="csrf_token" value={token || ''} />;
}

function Modal({ title, description, open, onClose, children, tone = 'orange' }) {
  if (!open) return null;

  return (
    <div className="admin-modal-backdrop" role="presentation" onMouseDown={onClose}>
      <section className={`admin-modal admin-modal--${tone}`} role="dialog" aria-modal="true" onMouseDown={(event) => event.stopPropagation()}>
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

function CommitteeEditForm({ boot, committee }) {
  return (
    <form action={boot.committeeDetail?.actions?.update} method="POST" className="admin-form">
      <CsrfField token={boot.csrfToken} />
      <div className="admin-form__grid">
        <label>
          <span>Komite Adi</span>
          <input name="name" required defaultValue={committee.name || ''} />
        </label>
        <label>
          <span>Ingilizce Ad</span>
          <input name="name_en" defaultValue={committee.name_en || ''} />
        </label>
        <label>
          <span>Tip</span>
          <select name="committee_type" defaultValue={committee.committee_type || 'active'}>
            <option value="board">Ust Yonetim</option>
            <option value="active">Aktif Komite</option>
            <option value="special">Ozel Birim</option>
            <option value="planned">Planlanan</option>
          </select>
        </label>
        <label>
          <span>Ikon</span>
          <input name="icon" defaultValue={committee.icon || '🏛️'} />
        </label>
      </div>
      <label className="admin-check-row">
        <input type="checkbox" name="is_active" defaultChecked={Number(committee.is_active) === 1} />
        <span>Aktif birim</span>
      </label>
      <div className="admin-form__actions">
        <button className="admin-button admin-button--primary" type="submit">
          Kaydet
        </button>
      </div>
    </form>
  );
}

function TaskCard({ boot, task, committeeId }) {
  return (
    <article className="committee-task-card">
      <strong>{task.title}</strong>
      <p>{task.description || 'Aciklama yok'}</p>
      <div className="committee-task-card__meta">
        <span>{task.unit_name}</span>
        {task.member_name ? <span>{task.member_name}</span> : null}
        {task.due_date ? <span>{task.due_date}</span> : null}
      </div>
      <div className="committee-task-card__actions">
        {taskStatus.map((status) => (
          <form action={boot.committeeDetail?.actions?.updateTaskStatus} method="POST" key={status.value}>
            <CsrfField token={boot.csrfToken} />
            <input type="hidden" name="task_id" value={task.id} />
            <input type="hidden" name="committee_id" value={committeeId} />
            <input type="hidden" name="status" value={status.value} />
            <button className={task.status === status.value ? 'is-active' : ''} type="submit">
              {status.label}
            </button>
          </form>
        ))}
      </div>
    </article>
  );
}

export default function CommitteesDetailPage({ boot }) {
  const detail = boot.committeeDetail || {};
  const committee = detail.committee || {};
  const units = committee.units || [];
  const members = committee.members || [];
  const admins = committee.admins || [];
  const tasks = committee.tasks || [];
  const [modal, setModal] = useState(null);
  const tasksByStatus = useMemo(
    () => Object.fromEntries(taskStatus.map((status) => [status.value, tasks.filter((task) => task.status === status.value)])),
    [tasks],
  );

  return (
    <AdminLayout boot={boot}>
      <div className="committee-detail-hero">
        <div>
          <a href={`${boot.baseUrl}/admin/committees`}>Komitelere don</a>
          <span>{committee.committee_type || 'active'}</span>
          <h2>
            <b>{committee.icon || '🏛️'}</b>
            {committee.name}
          </h2>
          <p>{committee.name_en || 'InnoMIS komite detayi'}</p>
        </div>
        <button className="admin-button admin-button--primary" type="button" onClick={() => setModal('edit')}>
          <Pencil size={18} />
          Ayarlar
        </button>
      </div>

      <section className="committee-detail-grid">
        <div className="committee-main-column">
          <div className="committee-panel">
            <div className="committee-panel__header">
              <div>
                <span>Birimler</span>
                <h3>Alt Birimler</h3>
              </div>
              <button className="admin-button admin-button--ghost" type="button" onClick={() => setModal('unit')}>
                <Plus size={17} />
                Birim
              </button>
            </div>
            <div className="committee-unit-list">
              {units.map((unit) => (
                <article key={unit.id}>
                  <div>
                    <strong>{unit.name}</strong>
                    <p>{unit.description || 'Aciklama eklenmemis.'}</p>
                  </div>
                  <form action={detail.actions?.deleteUnit} method="POST">
                    <CsrfField token={boot.csrfToken} />
                    <input type="hidden" name="unit_id" value={unit.id} />
                    <input type="hidden" name="committee_id" value={committee.id} />
                    <button className="admin-icon-button is-danger" type="submit" title="Sil">
                      <Trash2 size={16} />
                    </button>
                  </form>
                </article>
              ))}
              {units.length === 0 ? <p className="committee-muted">Bu komite icin henuz alt birim yok.</p> : null}
            </div>
          </div>

          <div className="committee-panel">
            <div className="committee-panel__header">
              <div>
                <span>Gorevler</span>
                <h3>Kanban Gorev Akisi</h3>
              </div>
              <button className="admin-button admin-button--ghost" type="button" onClick={() => setModal('task')}>
                <Plus size={17} />
                Gorev
              </button>
            </div>
            <div className="committee-kanban">
              {taskStatus.map((status) => {
                const Icon = status.icon;
                return (
                  <div className="committee-kanban__column" key={status.value}>
                    <header>
                      <Icon size={16} />
                      <strong>{status.label}</strong>
                      <span>{tasksByStatus[status.value]?.length || 0}</span>
                    </header>
                    {(tasksByStatus[status.value] || []).map((task) => (
                      <TaskCard boot={boot} task={task} committeeId={committee.id} key={task.id} />
                    ))}
                  </div>
                );
              })}
            </div>
          </div>
        </div>

        <aside className="committee-side-column">
          <div className="committee-panel">
            <div className="committee-panel__header">
              <div>
                <span>Yetkili Adminler</span>
                <h3>Komite Rolleri</h3>
              </div>
              <button className="admin-icon-button" type="button" onClick={() => setModal('admin')}>
                <UserPlus size={17} />
              </button>
            </div>
            <div className="committee-person-list">
              {admins.map((admin) => (
                <article key={admin.id}>
                  <ShieldCheck size={17} />
                  <div>
                    <strong>{admin.name}</strong>
                    <span>{admin.committee_role || admin.admin_role}</span>
                  </div>
                  <form action={detail.actions?.removeAdmin} method="POST">
                    <CsrfField token={boot.csrfToken} />
                    <input type="hidden" name="admin_id" value={admin.id} />
                    <input type="hidden" name="committee_id" value={committee.id} />
                    <button type="submit">Cikar</button>
                  </form>
                </article>
              ))}
              {admins.length === 0 ? <p className="committee-muted">Yetkili admin atanmamis.</p> : null}
            </div>
          </div>

          <div className="committee-panel">
            <div className="committee-panel__header">
              <div>
                <span>Uyeler</span>
                <h3>Kayitli Uyeler</h3>
              </div>
              <button className="admin-icon-button" type="button" onClick={() => setModal('member')}>
                <Users size={17} />
              </button>
            </div>
            <div className="committee-person-list">
              {members.map((member) => (
                <article key={member.id}>
                  <Users size={17} />
                  <div>
                    <strong>{member.name}</strong>
                    <span>{member.email}</span>
                  </div>
                  <form action={detail.actions?.removeMember} method="POST">
                    <CsrfField token={boot.csrfToken} />
                    <input type="hidden" name="member_id" value={member.id} />
                    <input type="hidden" name="committee_id" value={committee.id} />
                    <button type="submit">Cikar</button>
                  </form>
                </article>
              ))}
              {members.length === 0 ? <p className="committee-muted">Bu komiteye uye atanmamis.</p> : null}
            </div>
            <ViperaDevSignature asset={boot.assets?.viperaDev} />
          </div>
        </aside>
      </section>

      <Modal title="Komite Ayarlari" description="Mevcut update route'u korunarak komite bilgileri guncellenir." open={modal === 'edit'} onClose={() => setModal(null)}>
        <CommitteeEditForm boot={boot} committee={committee} />
      </Modal>

      <Modal title="Yeni Alt Birim" open={modal === 'unit'} onClose={() => setModal(null)}>
        <form action={detail.actions?.addUnit} method="POST" className="admin-form">
          <CsrfField token={boot.csrfToken} />
          <input type="hidden" name="committee_id" value={committee.id} />
          <label>
            <span>Birim Adi</span>
            <input name="name" required />
          </label>
          <label>
            <span>Aciklama</span>
            <textarea name="description" />
          </label>
          <label>
            <span>Siralama</span>
            <input name="sort_order" type="number" defaultValue="0" />
          </label>
          <div className="admin-form__actions">
            <button className="admin-button admin-button--primary" type="submit">
              Birim Olustur
            </button>
          </div>
        </form>
      </Modal>

      <Modal title="Yeni Gorev" open={modal === 'task'} onClose={() => setModal(null)}>
        <form action={detail.actions?.addTask} method="POST" className="admin-form">
          <CsrfField token={boot.csrfToken} />
          <input type="hidden" name="committee_id" value={committee.id} />
          <label>
            <span>Sorumlu Birim</span>
            <select name="unit_id" required>
              {units.map((unit) => (
                <option value={unit.id} key={unit.id}>
                  {unit.name}
                </option>
              ))}
            </select>
          </label>
          <label>
            <span>Atanacak Uye</span>
            <select name="assigned_to" defaultValue="">
              <option value="">Secilmedi</option>
              {members.map((member) => (
                <option value={member.id} key={member.id}>
                  {member.name}
                </option>
              ))}
            </select>
          </label>
          <label>
            <span>Baslik</span>
            <input name="title" required />
          </label>
          <label>
            <span>Aciklama</span>
            <textarea name="description" />
          </label>
          <label>
            <span>Teslim Tarihi</span>
            <input name="due_date" type="date" />
          </label>
          <div className="admin-form__actions">
            <button className="admin-button admin-button--primary" type="submit">
              Gorev Ekle
            </button>
          </div>
        </form>
      </Modal>

      <Modal title="Admin Ata" open={modal === 'admin'} onClose={() => setModal(null)}>
        <form action={detail.actions?.assignAdmin} method="POST" className="admin-form">
          <CsrfField token={boot.csrfToken} />
          <input type="hidden" name="committee_id" value={committee.id} />
          <label>
            <span>Admin ID</span>
            <input name="admin_id" type="number" required placeholder="Arama endpoint'i sonraki adimda autocomplete olur" />
          </label>
          <label>
            <span>Rol</span>
            <input name="role" defaultValue="Coordinator" />
          </label>
          <div className="admin-form__actions">
            <button className="admin-button admin-button--primary" type="submit">
              Ata
            </button>
          </div>
        </form>
      </Modal>

      <Modal title="Uye Ata" open={modal === 'member'} onClose={() => setModal(null)}>
        <form action={detail.actions?.assignMember} method="POST" className="admin-form">
          <CsrfField token={boot.csrfToken} />
          <input type="hidden" name="committee_id" value={committee.id} />
          <label>
            <span>Uye ID</span>
            <input name="member_id" type="number" required placeholder="Arama endpoint'i sonraki adimda autocomplete olur" />
          </label>
          <div className="admin-form__actions">
            <button className="admin-button admin-button--primary" type="submit">
              Uye Ata
            </button>
          </div>
        </form>
      </Modal>
    </AdminLayout>
  );
}
