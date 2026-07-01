import { Pencil, Plus, Search, ShieldCheck, Trash2, X } from 'lucide-react';
import { useMemo, useState } from 'react';
import AdminLayout from '../components/AdminLayout.jsx';
import ViperaDevSignature from '../components/ViperaDevSignature.jsx';

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

function CsrfField({ token }) {
  return <input type="hidden" name="csrf_token" value={token || ''} />;
}

function StatusBadge({ active }) {
  return <span className={`admin-status admin-status--${active ? 'active' : 'inactive'}`}>{active ? 'Aktif' : 'Pasif'}</span>;
}

function CreateUserForm({ action, csrfToken, departments }) {
  return (
    <form action={action} method="POST" className="admin-form">
      <CsrfField token={csrfToken} />

      <div className="admin-form__grid">
        <label>
          <span>Ad Soyad *</span>
          <input name="name" required placeholder="Ali Veli" />
        </label>
        <label>
          <span>E-posta *</span>
          <input name="email" type="email" required placeholder="ali@domain.com" />
        </label>
        <label>
          <span>Şifre *</span>
          <input name="password" type="password" required placeholder="En az 8 karakter" />
        </label>
        <label>
          <span>Doğum Tarihi</span>
          <input name="birth_date" type="date" />
        </label>
        <label>
          <span>Departman</span>
          <select name="department_id" defaultValue="">
            <option value="">Atama yapma</option>
            {departments.map((department) => (
              <option value={department.id} key={department.id}>
                {department.name}
              </option>
            ))}
          </select>
        </label>
        <label>
          <span>Rol</span>
          <select name="role" defaultValue="member">
            <option value="member">Üye</option>
            <option value="director">Direktör</option>
          </select>
        </label>
      </div>

      <label className="admin-form__checkbox">
        <input type="checkbox" name="is_super_admin" value="1" />
        <span>Süper admin yetkisi ver</span>
      </label>
      <label className="admin-form__checkbox">
        <input type="checkbox" name="can_manage_passwords" value="1" />
        <span>Şifreleri düzenleyebilir/silebilir</span>
      </label>

      <div className="admin-form__actions">
        <button className="admin-button admin-button--primary" type="submit">
          Kullanıcı oluştur
        </button>
      </div>
    </form>
  );
}

function EmptyUsers({ asset }) {
  return (
    <div className="admin-empty">
      <Search size={34} />
      <strong>Henüz kullanıcı yok.</strong>
      <span>İlk kullanıcıyı ekleyerek panel ekibini oluşturabilirsin.</span>
      <ViperaDevSignature asset={asset} />
    </div>
  );
}

export default function SuperAdminUsersPage({ boot }) {
  const usersData = boot.users || {};
  const users = usersData.items || [];
  const departments = usersData.departments || [];
  const actions = usersData.actions || {};
  const [isCreateOpen, setCreateOpen] = useState(false);
  const [deleteUser, setDeleteUser] = useState(null);
  const activeCount = useMemo(() => users.filter((user) => Number(user.is_active)).length, [users]);

  return (
    <AdminLayout boot={boot}>
      <div className="admin-page-heading">
        <div>
          <span className="dashboard-hero__eyebrow">User Operations</span>
          <h2>Kullanıcılar</h2>
          <p>{users.length} kullanıcı, {activeCount} aktif hesap. CRUD işlemleri mevcut PHP endpointleriyle çalışır.</p>
        </div>
        <button className="admin-button admin-button--primary" type="button" onClick={() => setCreateOpen(true)}>
          <Plus size={18} />
          Yeni Kullanıcı
        </button>
      </div>

      {boot.flash?.message ? (
        <div className={`admin-alert admin-alert--${boot.flash.type || 'success'}`}>{boot.flash.message}</div>
      ) : null}

      <section className="members-table-wrap" aria-label="Kullanıcı listesi">
        {users.length === 0 ? (
          <EmptyUsers asset={boot.assets?.viperaDev} />
        ) : (
          <table className="members-table">
            <thead>
              <tr>
                <th>Ad</th>
                <th>E-posta</th>
                <th>Departmanlar</th>
                <th>Durum</th>
                <th>Kayıt</th>
                <th>İşlem</th>
              </tr>
            </thead>
            <tbody>
              {users.map((user) => (
                <tr key={user.id}>
                  <td data-label="Ad">
                    <div className="member-cell">
                      <strong>
                        {user.name}
                        {Number(user.is_super_admin) ? <span className="admin-role-badge" style={{ marginLeft: 8 }}>SUPER ADMIN</span> : null}
                      </strong>
                      {Number(user.can_manage_passwords) ? <small><ShieldCheck size={13} /> Şifre yetkisi</small> : null}
                    </div>
                  </td>
                  <td data-label="E-posta">{user.email}</td>
                  <td data-label="Departmanlar">{user.depts || '-'}</td>
                  <td data-label="Durum"><StatusBadge active={Number(user.is_active)} /></td>
                  <td data-label="Kayıt">{user.created_at_label || user.created_at || '-'}</td>
                  <td data-label="İşlem">
                    <div className="member-actions">
                      <a className="admin-icon-button" href={`${actions.editBase || ''}/${user.id}/edit`} title="Düzenle">
                        <Pencil size={17} />
                      </a>
                      <button className="admin-icon-button is-danger" type="button" title="Sil" onClick={() => setDeleteUser(user)}>
                        <Trash2 size={17} />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        )}
      </section>

      <Modal title="Yeni kullanıcı" description="Kayıt mevcut PHP create endpointine gönderilir." open={isCreateOpen} onClose={() => setCreateOpen(false)}>
        <CreateUserForm action={actions.create} csrfToken={boot.csrfToken} departments={departments} />
      </Modal>

      <Modal title="Kullanıcıyı sil" description={deleteUser?.name} open={Boolean(deleteUser)} onClose={() => setDeleteUser(null)} tone="danger">
        <form action={`${actions.deleteBase || ''}/${deleteUser?.id || ''}/delete`} method="POST" className="admin-form">
          <CsrfField token={boot.csrfToken} />
          <div className="admin-warning-box">Bu işlem kullanıcıyı kalıcı olarak siler.</div>
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
