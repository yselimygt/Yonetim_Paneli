import { ChevronLeft, ClipboardList, X } from 'lucide-react';
import { useState } from 'react';
import AdminLayout from '../components/AdminLayout.jsx';
import ViperaDevSignature from '../components/ViperaDevSignature.jsx';

function formatDateTime(value) {
  if (!value) return '-';
  try {
    return new Intl.DateTimeFormat('tr-TR', {
      day: '2-digit',
      month: 'short',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    }).format(new Date(value.replace(' ', 'T')));
  } catch (error) {
    return value;
  }
}

function SubmissionDetailSheet({ form, submission, onClose }) {
  if (!submission) return null;
  const fields = form.fields || [];

  return (
    <div className="admin-modal-backdrop" role="presentation" onMouseDown={onClose}>
      <section className="admin-modal" role="dialog" aria-modal="true" onMouseDown={(event) => event.stopPropagation()}>
        <header className="admin-modal__header">
          <div>
            <h2>Yanıt Detayı</h2>
            <p>{formatDateTime(submission.submitted_at)}</p>
          </div>
          <button className="admin-icon-button" type="button" aria-label="Kapat" onClick={onClose}>
            <X size={18} />
          </button>
        </header>
        <dl className="submission-detail-list">
          {fields.map((field) => (
            <div key={field.id}>
              <dt>{field.label}</dt>
              <dd>{submission.data?.[field.id] || '-'}</dd>
            </div>
          ))}
        </dl>
      </section>
    </div>
  );
}

export default function FormsSubmissionsPage({ boot }) {
  const detail = boot.formSubmissions || {};
  const form = detail.form || {};
  const submissions = detail.submissions || [];
  const fields = form.fields || [];
  const [activeSubmission, setActiveSubmission] = useState(null);

  return (
    <AdminLayout boot={boot}>
      <div className="admin-page-heading">
        <div>
          <span className="dashboard-hero__eyebrow">Yanıtlar</span>
          <h2>{form.title}</h2>
          <p>{submissions.length} yanıt toplandı.</p>
        </div>
        <a className="admin-button admin-button--ghost" href={`${boot.baseUrl}/admin/forms`}>
          <ChevronLeft size={18} />
          Geri Dön
        </a>
      </div>

      <div className="committee-panel submission-panel">
        {submissions.length === 0 ? (
          <div className="admin-empty">
            <ClipboardList size={34} />
            <strong>Henüz yanıt yok.</strong>
            <span>Bu form için henüz kimse başvuru yapmamış.</span>
            <ViperaDevSignature asset={boot.assets?.viperaDev} />
          </div>
        ) : (
          <>
            <div className="submission-table">
              <table>
                <thead>
                  <tr>
                    <th>Tarih</th>
                    {fields.map((field) => (
                      <th key={field.id}>{field.label}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {submissions.map((submission) => (
                    <tr key={submission.id} onClick={() => setActiveSubmission(submission)}>
                      <td data-label="Tarih">{formatDateTime(submission.submitted_at)}</td>
                      {fields.map((field) => (
                        <td data-label={field.label} key={field.id} title={submission.data?.[field.id] || '-'}>
                          {submission.data?.[field.id] || '-'}
                        </td>
                      ))}
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>

            <div className="submission-cards">
              {submissions.map((submission) => (
                <button className="submission-card" type="button" key={submission.id} onClick={() => setActiveSubmission(submission)}>
                  <div className="submission-card__date">{formatDateTime(submission.submitted_at)}</div>
                  {fields.slice(0, 3).map((field) => (
                    <div className="submission-card__row" key={field.id}>
                      <span>{field.label}</span>
                      <strong>{submission.data?.[field.id] || '-'}</strong>
                    </div>
                  ))}
                  {fields.length > 3 ? <span className="submission-card__more">+{fields.length - 3} alan daha</span> : null}
                </button>
              ))}
            </div>
          </>
        )}
      </div>

      <SubmissionDetailSheet form={form} submission={activeSubmission} onClose={() => setActiveSubmission(null)} />
    </AdminLayout>
  );
}
