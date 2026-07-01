import { CalendarDays, ExternalLink, MapPin, MonitorPlay, Pencil, Plus, Trash2, Trophy, X } from 'lucide-react';
import { useMemo, useState } from 'react';
import AdminLayout from '../components/AdminLayout.jsx';
import StatPanel from '../components/StatPanel.jsx';
import ViperaDevSignature from '../components/ViperaDevSignature.jsx';

const statuses = [
  { value: 'active', label: 'Aktif' },
  { value: 'completed', label: 'Tamamlandi' },
  { value: 'cancelled', label: 'Iptal' },
  { value: 'draft', label: 'Taslak' },
];

function CsrfField({ token }) {
  return <input type="hidden" name="csrf_token" value={token || ''} />;
}

function formatDate(value) {
  if (!value) return 'Tarih yok';
  return new Intl.DateTimeFormat('tr-TR', {
    day: '2-digit',
    month: 'short',
    hour: '2-digit',
    minute: '2-digit',
  }).format(new Date(value.replace(' ', 'T')));
}

function toInputDateTime(value) {
  if (!value) return '';
  return value.replace(' ', 'T').slice(0, 16);
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

function EventForm({ boot, event, action, mode = 'create' }) {
  const committees = boot.events?.committees || [];
  const isOnline = event?.event_type === 'online';

  return (
    <form action={action} method="POST" className="admin-form">
      <CsrfField token={boot.csrfToken} />
      {event?.id ? <input type="hidden" name="id" value={event.id} /> : null}
      <div className="admin-form__grid">
        <label>
          <span>Etkinlik Basligi</span>
          <input name="title" required defaultValue={event?.title || ''} />
        </label>
        <label>
          <span>Duzenleyen Birim</span>
          <select name="committee_id" defaultValue={event?.committee_id || ''}>
            <option value="">Topluluk Genel</option>
            {committees.map((committee) => (
              <option value={committee.id} key={committee.id}>
                {committee.name}
              </option>
            ))}
          </select>
        </label>
        <label>
          <span>Baslangic</span>
          <input name="event_date" type="datetime-local" required defaultValue={toInputDateTime(event?.event_date)} />
        </label>
        <label>
          <span>Bitis</span>
          <input name="end_date" type="datetime-local" defaultValue={toInputDateTime(event?.end_date)} />
        </label>
        <label>
          <span>Etkinlik Tipi</span>
          <select name="event_type" defaultValue={event?.event_type || 'physical'}>
            <option value="physical">Fiziksel</option>
            <option value="online">Online</option>
          </select>
        </label>
        <label>
          <span>Konum</span>
          <input name="location" defaultValue={event?.location || ''} placeholder={isOnline ? 'Opsiyonel' : 'Salon, kampus veya adres'} />
        </label>
        <label>
          <span>Platform</span>
          <input name="platform" defaultValue={event?.platform || ''} placeholder="Zoom, Meet, YouTube..." />
        </label>
        <label>
          <span>XP Odulu</span>
          <input name="xp_reward" type="number" min="0" defaultValue={event?.xp_reward || 10} />
        </label>
        {mode === 'edit' ? (
          <label>
            <span>Durum</span>
            <select name="status" defaultValue={event?.status || 'active'}>
              {statuses.map((status) => (
                <option value={status.value} key={status.value}>
                  {status.label}
                </option>
              ))}
            </select>
          </label>
        ) : null}
        <label>
          <span>Kayit Formu Linki</span>
          <input name="registration_link" type="url" defaultValue={event?.registration_link || ''} />
        </label>
      </div>
      <label>
        <span>Aciklama</span>
        <textarea name="description" defaultValue={event?.description || ''} />
      </label>
      <div className="admin-form__actions">
        <button className="admin-button admin-button--primary" type="submit">
          {mode === 'edit' ? 'Etkinligi Guncelle' : 'Etkinlik Olustur'}
        </button>
      </div>
    </form>
  );
}

function EventCard({ event, boot, onEdit, onDelete }) {
  const isOnline = event.event_type === 'online';

  return (
    <article className={`event-card event-card--${event.status || 'active'}`}>
      <div className="event-card__actions">
        <button className="admin-icon-button" type="button" title="Duzenle" onClick={() => onEdit(event)}>
          <Pencil size={16} />
        </button>
        <button className="admin-icon-button is-danger" type="button" title="Sil" onClick={() => onDelete(event)}>
          <Trash2 size={16} />
        </button>
      </div>
      <div className="event-card__badges">
        <span className={`event-status event-status--${event.status || 'active'}`}>{statuses.find((item) => item.value === event.status)?.label || event.status || 'Aktif'}</span>
        <span className="event-type">{isOnline ? <MonitorPlay size={14} /> : <MapPin size={14} />}{isOnline ? 'Online' : 'Fiziksel'}</span>
        <span className="event-xp">
          <Trophy size={14} /> +{event.xp_reward || 0} XP
        </span>
      </div>
      <h3>{event.title}</h3>
      <p>{event.description || 'Etkinlik aciklamasi henuz eklenmemis.'}</p>
      <div className="event-card__meta">
        <span>
          <CalendarDays size={15} />
          {formatDate(event.event_date)}
        </span>
        <span>{isOnline ? event.platform || 'Online platform' : event.location || 'Konum belirtilmedi'}</span>
      </div>
      <div className="event-card__footer">
        <span>{event.committee_name || 'Topluluk Genel'}</span>
        <a href={`${boot.baseUrl}/admin/events/${event.id}`}>
          Yonet <ExternalLink size={15} />
        </a>
      </div>
    </article>
  );
}

export default function EventsListPage({ boot }) {
  const events = boot.events?.items || [];
  const actions = boot.events?.actions || {};
  const [createOpen, setCreateOpen] = useState(false);
  const [editEvent, setEditEvent] = useState(null);
  const [deleteEvent, setDeleteEvent] = useState(null);
  const stats = useMemo(() => {
    const active = events.filter((event) => event.status === 'active').length;
    const online = events.filter((event) => event.event_type === 'online').length;
    const totalXp = events.reduce((sum, event) => sum + Number(event.xp_reward || 0), 0);
    return { active, online, totalXp };
  }, [events]);

  return (
    <AdminLayout boot={boot}>
      <div className="admin-page-heading">
        <div>
          <span className="dashboard-hero__eyebrow">Community Calendar</span>
          <h2>Topluluk Etkinlikleri</h2>
          <p>Etkinlik takvimi, yoklama ve sertifika akisi mevcut PHP endpointleri korunarak yonetilir.</p>
        </div>
        <button className="admin-button admin-button--primary" type="button" onClick={() => setCreateOpen(true)}>
          <Plus size={18} />
          Yeni Etkinlik
        </button>
      </div>

      <section className="event-stat-grid">
        <StatPanel label="Toplam Etkinlik" value={events.length} tone="orange" />
        <StatPanel label="Aktif" value={stats.active} tone="green" />
        <StatPanel label="Online" value={stats.online} tone="blue" />
        <StatPanel label="Toplam XP" value={stats.totalXp} tone="teal" />
      </section>

      <section className="event-grid">
        {events.map((event) => (
          <EventCard event={event} boot={boot} onEdit={setEditEvent} onDelete={setDeleteEvent} key={event.id} />
        ))}
        {events.length === 0 ? (
          <div className="admin-empty event-empty">
            <CalendarDays size={34} />
            <strong>Henuz etkinlik yok.</strong>
            <span>Yeni etkinlik olusturarak takvimi baslatabilirsiniz.</span>
            <ViperaDevSignature asset={boot.assets?.viperaDev} />
          </div>
        ) : null}
      </section>

      <Modal title="Yeni Etkinlik" description="Form mevcut /admin/events/create route'una POST edilir." open={createOpen} onClose={() => setCreateOpen(false)}>
        <EventForm boot={boot} action={actions.create} />
      </Modal>

      <Modal title="Etkinligi Duzenle" description="Mevcut update endpoint'i ve CSRF korumasi korunur." open={Boolean(editEvent)} onClose={() => setEditEvent(null)}>
        <EventForm boot={boot} event={editEvent} action={actions.update} mode="edit" />
      </Modal>

      <Modal title="Etkinlik Silinsin mi?" description={deleteEvent?.title} open={Boolean(deleteEvent)} onClose={() => setDeleteEvent(null)} tone="danger">
        <form action={actions.delete} method="POST" className="admin-form">
          <CsrfField token={boot.csrfToken} />
          <input type="hidden" name="id" value={deleteEvent?.id || ''} />
          <div className="admin-warning-box">Bu islem mevcut delete route'u ile calisir ve veritabani iliskilerine dokunmaz.</div>
          <div className="admin-form__actions">
            <button className="admin-button admin-button--ghost" type="button" onClick={() => setDeleteEvent(null)}>
              Vazgec
            </button>
            <button className="admin-button admin-button--danger" type="submit">
              Sil
            </button>
          </div>
        </form>
      </Modal>
    </AdminLayout>
  );
}
