import { AlertTriangle, Filter, Pencil, Plus, Search, ShieldAlert, Star, Trash2, X } from 'lucide-react';
import { useMemo, useState } from 'react';
import AdminLayout from '../components/AdminLayout.jsx';
import ViperaDevSignature from '../components/ViperaDevSignature.jsx';

const grades = ['Hazırlık', '1. Sınıf', '2. Sınıf', '3. Sınıf', '4. Sınıf', 'Mezun'];
const statuses = [
  { value: 'active', label: 'Aktif' },
  { value: 'inactive', label: 'Pasif' },
  { value: 'alumni', label: 'Mezun' },
  { value: 'probation', label: 'Gözlem' },
];

function formatNumber(value) {
  return new Intl.NumberFormat('tr-TR').format(Number(value || 0));
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

function CsrfField({ token }) {
  return <input type="hidden" name="csrf_token" value={token || ''} />;
}

function MemberForm({ action, csrfToken, committees, member, submitLabel }) {
  return (
    <form action={action} method="POST" className="admin-form">
      <CsrfField token={csrfToken} />
      {member?.id ? <input type="hidden" name="id" value={member.id} /> : null}

      <div className="admin-form__grid">
        <label>
          <span>Ad Soyad</span>
          <input name="name" required defaultValue={member?.name || ''} />
        </label>
        <label>
          <span>E-posta</span>
          <input name="email" type="email" required defaultValue={member?.email || ''} />
        </label>
        <label>
          <span>Telefon</span>
          <input name="phone" defaultValue={member?.phone || ''} />
        </label>
        <label>
          <span>Öğrenci No</span>
          <input name="student_id" defaultValue={member?.student_id || ''} />
        </label>
        <label>
          <span>Üniversite</span>
          <input name="university" defaultValue={member?.university || ''} />
        </label>
        <label>
          <span>Fakülte</span>
          <input name="faculty" defaultValue={member?.faculty || ''} />
        </label>
        <label>
          <span>Bölüm</span>
          <input name="department" defaultValue={member?.department || ''} />
        </label>
        <label>
          <span>Sınıf</span>
          <select name="grade" defaultValue={member?.grade || ''}>
            <option value="">Seçiniz</option>
            {grades.map((grade) => (
              <option value={grade} key={grade}>
                {grade}
              </option>
            ))}
          </select>
        </label>
        <label>
          <span>Komite</span>
          <select name="committee_id" defaultValue={member?.committee_id || ''}>
            <option value="">Genel Üye</option>
            {committees.map((committee) => (
              <option value={committee.id} key={committee.id}>
                {committee.name}
              </option>
            ))}
          </select>
        </label>
        <label>
          <span>Durum</span>
          <select name="status" defaultValue={member?.status || 'active'}>
            {statuses.map((status) => (
              <option value={status.value} key={status.value}>
                {status.label}
              </option>
            ))}
          </select>
        </label>
      </div>

      <div className="admin-form__actions">
        <button className="admin-button admin-button--primary" type="submit">
          {submitLabel}
        </button>
      </div>
    </form>
  );
}

function FilterSheet({ boot, filters, committees, open, onClose }) {
  return (
    <Modal title="Gelişmiş filtreleme" description="Üye listesini akademik ve idari kriterlere göre daralt." open={open} onClose={onClose}>
      <form action={`${boot.baseUrl}/admin/members`} method="GET" className="admin-form">
        <input type="hidden" name="q" value={filters.search || ''} />
        <div className="admin-form__grid">
          <label>
            <span>Üniversite</span>
            <input name="university" defaultValue={filters.university || ''} />
          </label>
          <label>
            <span>Fakülte</span>
            <input name="faculty" defaultValue={filters.faculty || ''} />
          </label>
          <label>
            <span>Bölüm</span>
            <input name="department" defaultValue={filters.department || ''} />
          </label>
          <label>
            <span>Sınıf</span>
            <select name="grade" defaultValue={filters.grade || ''}>
              <option value="">Tümü</option>
              {grades.map((grade) => (
                <option value={grade} key={grade}>
                  {grade}
                </option>
              ))}
            </select>
          </label>
          <label>
            <span>Komite</span>
            <select name="committee" defaultValue={filters.committee_id || ''}>
              <option value="">Tüm komiteler</option>
              {committees.map((committee) => (
                <option value={committee.id} key={committee.id}>
                  {committee.name}
                </option>
              ))}
            </select>
          </label>
          <label>
            <span>Durum</span>
            <select name="status" defaultValue={filters.status || ''}>
              <option value="">Tümü</option>
              {statuses.map((status) => (
                <option value={status.value} key={status.value}>
                  {status.label}
                </option>
              ))}
            </select>
          </label>
          <label>
            <span>Liste Türü</span>
            <select name="red_list" defaultValue={filters.is_red_listed || ''}>
              <option value="">Tümü</option>
              <option value="1">Kırmızı Liste</option>
              <option value="0">Standart Üyeler</option>
            </select>
          </label>
        </div>
        <div className="admin-form__actions">
          <a className="admin-button admin-button--ghost" href={`${boot.baseUrl}/admin/members`}>
            Sıfırla
          </a>
          <button className="admin-button admin-button--primary" type="submit">
            Sonuçları göster
          </button>
        </div>
      </form>
    </Modal>
  );
}

function StatusBadge({ status }) {
  const label = statuses.find((item) => item.value === status)?.label || status || 'Bilinmiyor';
  return <span className={`admin-status admin-status--${status || 'unknown'}`}>{label}</span>;
}

function MemberActions({ member, onEdit, onXp, onDelete, onRedList }) {
  return (
    <div className="member-actions">
      <button className="admin-icon-button" type="button" title="XP ekle" onClick={() => onXp(member)}>
        <Star size={17} />
      </button>
      <button
        className={`admin-icon-button ${Number(member.is_red_listed) ? 'is-muted' : 'is-danger'}`}
        type="button"
        title={Number(member.is_red_listed) ? 'Kırmızı listeden çıkar' : 'Kırmızı listeye ekle'}
        onClick={() => onRedList(member)}
      >
        <ShieldAlert size={17} />
      </button>
      <button className="admin-icon-button" type="button" title="Düzenle" onClick={() => onEdit(member)}>
        <Pencil size={17} />
      </button>
      <button className="admin-icon-button is-danger" type="button" title="Sil" onClick={() => onDelete(member)}>
        <Trash2 size={17} />
      </button>
    </div>
  );
}

function EmptyMembers({ asset }) {
  return (
    <div className="admin-empty member-empty">
      <Search size={34} />
      <strong>Üye bulunamadı.</strong>
      <span>Filtreleri temizleyebilir ya da yeni üye ekleyebilirsin.</span>
      <ViperaDevSignature asset={asset} />
    </div>
  );
}

export default function MembersPage({ boot }) {
  const membersData = boot.members || {};
  const members = membersData.result?.data || [];
  const pagination = membersData.result?.pagination || {};
  const filters = membersData.filters || {};
  const committees = membersData.committees || [];
  const actions = membersData.actions || {};
  const activeFilterCount = useMemo(
    () => [filters.committee_id, filters.status, filters.is_red_listed, filters.university, filters.faculty, filters.department, filters.grade]
      .filter((value) => value !== undefined && value !== null && value !== '').length,
    [filters],
  );

  const [isCreateOpen, setCreateOpen] = useState(false);
  const [isFilterOpen, setFilterOpen] = useState(false);
  const [editMember, setEditMember] = useState(null);
  const [xpMember, setXpMember] = useState(null);
  const [deleteMember, setDeleteMember] = useState(null);
  const [redListMember, setRedListMember] = useState(null);

  return (
    <AdminLayout boot={boot}>
      <div className="admin-page-heading">
        <div>
          <span className="dashboard-hero__eyebrow">Member Operations</span>
          <h2>Üye Listesi</h2>
          <p>{formatNumber(pagination.total_records)} kayıt bulundu. Filtreler ve aksiyonlar mevcut backend route'larıyla çalışır.</p>
        </div>
        <button className="admin-button admin-button--primary" type="button" onClick={() => setCreateOpen(true)}>
          <Plus size={18} />
          Yeni Üye
        </button>
      </div>

      {membersData.flash?.message ? (
        <div className={`admin-alert admin-alert--${membersData.flash.type || 'success'}`}>{membersData.flash.message}</div>
      ) : null}

      <form className="member-filter" action={`${boot.baseUrl}/admin/members`} method="GET">
        <div className="member-filter__search">
          <Search size={18} />
          <input name="q" defaultValue={filters.search || ''} placeholder="İsim, e-posta veya öğrenci no ile ara..." />
        </div>
        <button className="admin-button admin-button--ghost" type="button" onClick={() => setFilterOpen(true)}>
          <Filter size={18} />
          Filtrele
          {activeFilterCount > 0 ? <span className="admin-button__count">{activeFilterCount}</span> : null}
        </button>
        {(activeFilterCount > 0 || filters.search) ? (
          <a className="admin-button admin-button--ghost" href={`${boot.baseUrl}/admin/members`}>
            Sıfırla
          </a>
        ) : null}
      </form>

      <section className="members-table-wrap" aria-label="Üye listesi">
        {members.length === 0 ? (
          <EmptyMembers asset={boot.assets?.viperaDev} />
        ) : (
          <>
            <table className="members-table">
              <thead>
                <tr>
                  <th>Üye Bilgisi</th>
                  <th>Öğrenci No</th>
                  <th>Komite</th>
                  <th>Durum</th>
                  <th>XP</th>
                  <th>İşlem</th>
                </tr>
              </thead>
              <tbody>
                {members.map((member) => (
                  <tr key={member.id} className={Number(member.is_red_listed) ? 'is-red-listed' : ''}>
                    <td data-label="Üye Bilgisi">
                      <div className="member-cell">
                        <strong>{member.name}</strong>
                        <span>{member.email}</span>
                        {member.phone ? <em>{member.phone}</em> : null}
                        <small>
                          {[member.university, member.faculty, member.department, member.grade].filter(Boolean).join(' / ') || 'Akademik bilgi yok'}
                        </small>
                      </div>
                    </td>
                    <td data-label="Öğrenci No">{member.student_id || '-'}</td>
                    <td data-label="Komite">{member.committee_name || 'Genel Üye'}</td>
                    <td data-label="Durum"><StatusBadge status={member.status} /></td>
                    <td data-label="XP" className="member-xp">{formatNumber(member.xp_total)}</td>
                    <td data-label="İşlem">
                      <MemberActions
                        member={member}
                        onEdit={setEditMember}
                        onXp={setXpMember}
                        onDelete={setDeleteMember}
                        onRedList={setRedListMember}
                      />
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
            {Number(pagination.total_pages || 0) > 1 ? (
              <div className="admin-pagination">
                <span>
                  Sayfa {pagination.current_page} / {pagination.total_pages}
                </span>
              </div>
            ) : null}
          </>
        )}
      </section>

      <Modal title="Yeni üye ekle" description="Üye bilgilerini gir; kayıt mevcut create route'una gönderilir." open={isCreateOpen} onClose={() => setCreateOpen(false)}>
        <MemberForm action={actions.create} csrfToken={boot.csrfToken} committees={committees} submitLabel="Üyeyi ekle" />
      </Modal>

      <Modal title="Üye bilgilerini düzenle" description={editMember?.name} open={Boolean(editMember)} onClose={() => setEditMember(null)}>
        <MemberForm action={actions.update} csrfToken={boot.csrfToken} committees={committees} member={editMember} submitLabel="Değişiklikleri kaydet" />
      </Modal>

      <Modal title="XP ekle" description={xpMember?.name} open={Boolean(xpMember)} onClose={() => setXpMember(null)} tone="teal">
        <form action={actions.addXp} method="POST" className="admin-form">
          <CsrfField token={boot.csrfToken} />
          <input type="hidden" name="id" value={xpMember?.id || ''} />
          <label>
            <span>XP Miktarı</span>
            <input name="amount" type="number" min="1" required defaultValue="50" />
          </label>
          <label>
            <span>Gerekçe</span>
            <textarea name="reason" defaultValue="Admin tarafından eklendi" />
          </label>
          <div className="admin-form__actions">
            <button className="admin-button admin-button--primary" type="submit">XP ekle</button>
          </div>
        </form>
      </Modal>

      <Modal title="Üyeyi sil" description={deleteMember?.name} open={Boolean(deleteMember)} onClose={() => setDeleteMember(null)} tone="danger">
        <form action={actions.delete} method="POST" className="admin-form">
          <CsrfField token={boot.csrfToken} />
          <input type="hidden" name="id" value={deleteMember?.id || ''} />
          <div className="admin-warning-box">
            <AlertTriangle size={20} />
            Bu işlem üyeyi kalıcı olarak siler.
          </div>
          <div className="admin-form__actions">
            <button className="admin-button admin-button--danger" type="submit">Sil</button>
          </div>
        </form>
      </Modal>

      <Modal
        title={Number(redListMember?.is_red_listed) ? 'Kırmızı listeden çıkar' : 'Kırmızı listeye ekle'}
        description={redListMember?.name}
        open={Boolean(redListMember)}
        onClose={() => setRedListMember(null)}
        tone="danger"
      >
        <form action={actions.redList} method="POST" className="admin-form">
          <CsrfField token={boot.csrfToken} />
          <input type="hidden" name="id" value={redListMember?.id || ''} />
          <input type="hidden" name="status" value={Number(redListMember?.is_red_listed) ? '0' : '1'} />
          {!Number(redListMember?.is_red_listed) ? (
            <label>
              <span>Ekleme gerekçesi</span>
              <textarea name="reason" required placeholder="Gerekçeyi profesyonel bir dille yazın..." />
            </label>
          ) : (
            <input type="hidden" name="reason" value="" />
          )}
          <div className="admin-form__actions">
            <button className="admin-button admin-button--danger" type="submit">
              {Number(redListMember?.is_red_listed) ? 'Listeden çıkar' : 'Listeye ekle'}
            </button>
          </div>
        </form>
      </Modal>

      <FilterSheet boot={boot} filters={filters} committees={committees} open={isFilterOpen} onClose={() => setFilterOpen(false)} />
    </AdminLayout>
  );
}
