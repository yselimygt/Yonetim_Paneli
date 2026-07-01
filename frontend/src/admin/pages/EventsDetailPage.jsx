import { CalendarDays, ExternalLink, MapPin, MonitorPlay, QrCode, TicketCheck, Trophy, Users } from 'lucide-react';
import AdminLayout from '../components/AdminLayout.jsx';
import ViperaDevSignature from '../components/ViperaDevSignature.jsx';

function CsrfField({ token }) {
  return <input type="hidden" name="csrf_token" value={token || ''} />;
}

function formatDateTime(value) {
  if (!value) return 'Tarih yok';
  return new Intl.DateTimeFormat('tr-TR', {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  }).format(new Date(value.replace(' ', 'T')));
}

function attendanceUrl(boot, event) {
  return `${window.location.origin}${boot.baseUrl}/attendance/scan?secret=${encodeURIComponent(event.qr_code_secret || '')}`;
}

export default function EventsDetailPage({ boot }) {
  const detail = boot.eventDetail || {};
  const event = detail.event || {};
  const attendance = detail.attendance || [];
  const isOnline = event.event_type === 'online';
  const qrText = attendanceUrl(boot, event);
  const qrUrl = `https://quickchart.io/qr?text=${encodeURIComponent(qrText)}&size=220&margin=1`;

  return (
    <AdminLayout boot={boot}>
      <div className="event-detail-hero">
        <div>
          <a href={`${boot.baseUrl}/admin/events`}>Etkinliklere don</a>
          <span className={`event-status event-status--${event.status || 'active'}`}>{event.status || 'active'}</span>
          <h2>{event.title}</h2>
          <p>{event.description || 'Etkinlik aciklamasi eklenmemis.'}</p>
        </div>
        <div className="event-detail-hero__meta">
          <span>
            <Trophy size={16} /> +{event.xp_reward || 0} XP
          </span>
          <span>{event.committee_name || 'Topluluk Genel'}</span>
        </div>
      </div>

      <section className="event-detail-grid">
        <div className="event-detail-main">
          <div className="committee-panel">
            <div className="committee-panel__header">
              <div>
                <span>Yoklama</span>
                <h3>Katilimci Listesi</h3>
              </div>
              <div className="event-attendance-actions">
                {attendance.length > 0 ? (
                  <form action={detail.actions?.batchCertificates} method="POST">
                    <CsrfField token={boot.csrfToken} />
                    <button className="admin-button admin-button--primary" type="submit">
                      <TicketCheck size={17} />
                      Sertifika Uret
                    </button>
                  </form>
                ) : null}
                <span>{attendance.length} uye</span>
              </div>
            </div>
            {attendance.length === 0 ? (
              <div className="admin-empty">
                <Users size={34} />
                <strong>Henuz katilim yok.</strong>
                <span>Uyeler QR kodu okuttugunda burada listelenir.</span>
              </div>
            ) : (
              <div className="event-attendance-table">
                <table>
                  <thead>
                    <tr>
                      <th>Uye</th>
                      <th>Akademik Bilgi</th>
                      <th>Zaman</th>
                    </tr>
                  </thead>
                  <tbody>
                    {attendance.map((row) => (
                      <tr key={`${row.id}-${row.attended_at}`}>
                        <td data-label="Uye">
                          <strong>{row.name}</strong>
                          <span>{row.email}</span>
                        </td>
                        <td data-label="Akademik Bilgi">
                          <strong>{row.university || 'Universite yok'}</strong>
                          <span>{row.department || 'Bolum yok'}</span>
                        </td>
                        <td data-label="Zaman">{formatDateTime(row.attended_at)}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        </div>

        <aside className="event-detail-side">
          <div className="committee-panel event-qr-panel">
            <div className="event-qr-panel__title">
              <QrCode size={18} />
              <strong>Yoklama QR Kodu</strong>
            </div>
            <img src={qrUrl} alt="Yoklama QR kodu" />
            <p>Uyeler bu kodla katilim kaydi olusturur ve +{event.xp_reward || 0} XP kazanir.</p>
            <ViperaDevSignature asset={boot.assets?.viperaDev} />
          </div>

          <div className="committee-panel event-info-panel">
            <h3>Etkinlik Detaylari</h3>
            <dl>
              <div>
                <dt>Tip ve Konum</dt>
                <dd>{isOnline ? <MonitorPlay size={16} /> : <MapPin size={16} />}{isOnline ? event.platform || 'Online' : event.location || 'Konum belirtilmedi'}</dd>
              </div>
              <div>
                <dt>Zaman</dt>
                <dd><CalendarDays size={16} />{formatDateTime(event.event_date)}</dd>
              </div>
              {event.end_date ? (
                <div>
                  <dt>Bitis</dt>
                  <dd><CalendarDays size={16} />{formatDateTime(event.end_date)}</dd>
                </div>
              ) : null}
            </dl>
            {event.registration_link ? (
              <a className="admin-button admin-button--ghost" href={event.registration_link} target="_blank" rel="noreferrer">
                Kayit Formu <ExternalLink size={16} />
              </a>
            ) : null}
          </div>
        </aside>
      </section>
    </AdminLayout>
  );
}
