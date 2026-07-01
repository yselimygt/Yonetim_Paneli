import { ClipboardList, Copy, ExternalLink, FileText, MoreVertical, Plus, Trash2, X } from 'lucide-react';
import { useState } from 'react';
import AdminLayout from '../components/AdminLayout.jsx';
import StatPanel from '../components/StatPanel.jsx';
import ViperaDevSignature from '../components/ViperaDevSignature.jsx';

function CsrfField({ token }) {
  return <input type="hidden" name="csrf_token" value={token || ''} />;
}

function CreateFormModal({ boot, templates, open, onClose }) {
  if (!open) return null;

  return (
    <div className="admin-modal-backdrop" role="presentation" onMouseDown={onClose}>
      <section className="admin-modal" role="dialog" aria-modal="true" onMouseDown={(event) => event.stopPropagation()}>
        <header className="admin-modal__header">
          <div>
            <h2>Yeni Form Oluştur</h2>
            <p>Başvuru veya anket için yeni bir form başlatın.</p>
          </div>
          <button className="admin-icon-button" type="button" aria-label="Kapat" onClick={onClose}>
            <X size={18} />
          </button>
        </header>
        <form action={boot.forms?.actions?.create} method="POST" className="admin-form">
          <CsrfField token={boot.csrfToken} />
          <label>
            <span>Form Başlığı</span>
            <input name="title" required placeholder="ör: 2024 Yaz Kampı Başvurusu" />
          </label>
          <label>
            <span>Açıklama (Opsiyonel)</span>
            <textarea name="description" placeholder="Katılımcılara form hakkında kısa bir bilgi verin..." />
          </label>
          <label>
            <span>Şablondan Üret (Opsiyonel)</span>
            <select name="template_id" defaultValue="">
              <option value="">Sıfırdan Başla (Boş Form)</option>
              {(templates || []).map((tpl) => (
                <option value={tpl.id} key={tpl.id}>
                  {tpl.title}
                </option>
              ))}
            </select>
          </label>
          <div className="admin-warning-box">
            Şablon seçerseniz o taslaktaki tüm sorular otomatik kopyalanır. Yoksa ileri düzey form alanlarını bir sonraki adımda ekleyebilirsiniz.
          </div>
          <div className="admin-form__actions">
            <button className="admin-button admin-button--ghost" type="button" onClick={onClose}>
              İptal
            </button>
            <button className="admin-button admin-button--primary" type="submit">
              Formu Başlat
            </button>
          </div>
        </form>
      </section>
    </div>
  );
}

function DeleteFormModal({ boot, form, onClose }) {
  if (!form) return null;

  return (
    <div className="admin-modal-backdrop" role="presentation" onMouseDown={onClose}>
      <section className="admin-modal admin-modal--danger" role="dialog" aria-modal="true" onMouseDown={(event) => event.stopPropagation()}>
        <header className="admin-modal__header">
          <div>
            <h2>Form Silinsin mi?</h2>
            <p>{form.title} formu mevcut backend silme route'u ile kaldırılacak.</p>
          </div>
          <button className="admin-icon-button" type="button" aria-label="Kapat" onClick={onClose}>
            <X size={18} />
          </button>
        </header>
        <form action={`${boot.forms?.actions?.deleteBase}/${form.id}/delete`} method="POST" className="admin-form" data-form-delete={form.id}>
          <CsrfField token={boot.csrfToken} />
          <div className="admin-warning-box">Bu işlem geri alınamaz. Form ve ilişkili tüm yanıtlar etkilenir.</div>
          <div className="admin-form__actions">
            <button className="admin-button admin-button--ghost" type="button" onClick={onClose}>
              Vazgeç
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

function FormCard({ boot, form, onDelete }) {
  const [menuOpen, setMenuOpen] = useState(false);
  const [active, setActive] = useState(!!form.is_active);
  const [pending, setPending] = useState(false);
  const publicUrl = `${window.location.origin}${boot.forms?.publicBase || '/f'}/${form.slug}`;

  async function toggleActive() {
    setPending(true);
    try {
      const res = await fetch(`${boot.forms?.actions?.toggleBase}/${form.id}/toggle-active`, {
        method: 'POST',
        headers: { 'X-CSRF-Token': boot.csrfToken || '', 'X-Requested-With': 'XMLHttpRequest' },
      });
      const data = await res.json();
      if (data.success) {
        setActive(!!data.is_active);
      }
    } catch (error) {
      console.error('Form durumu degistirilemedi.', error);
    }
    setPending(false);
  }

  function copyLink() {
    navigator.clipboard?.writeText(publicUrl).catch(() => {});
  }

  return (
    <article className="form-card">
      <div className="form-card__top">
        <span className={`status-badge ${active ? 'status-badge--active' : 'status-badge--inactive'}`}>{active ? 'Aktif' : 'Pasif'}</span>
        <div className="form-card__actions">
          <button
            className={`form-toggle-btn ${active ? 'is-active' : 'is-inactive'}`}
            type="button"
            onClick={toggleActive}
            disabled={pending}
            title={active ? 'Formu Kapat' : 'Formu Aç'}
          >
            <span className="form-toggle-btn__dot" />
            {active ? 'Yayında' : 'Kapalı'}
          </button>
          <button className="admin-icon-button" type="button" title="Linki Kopyala" onClick={copyLink}>
            <Copy size={16} />
          </button>
          <div className="form-card__menu">
            <button className="admin-icon-button" type="button" title="Daha Fazla" onClick={() => setMenuOpen((value) => !value)}>
              <MoreVertical size={16} />
            </button>
            {menuOpen ? (
              <div className="form-card__dropdown" role="menu">
                <a href={`${boot.baseUrl}/admin/forms/${form.id}/edit`}>Düzenle</a>
                <a href={`${boot.baseUrl}/admin/forms/${form.id}/submissions`}>Yanıtları Gör</a>
                <hr />
                <button
                  type="button"
                  className="is-danger"
                  onClick={() => {
                    setMenuOpen(false);
                    onDelete(form);
                  }}
                >
                  <Trash2 size={14} /> Sil
                </button>
              </div>
            ) : null}
          </div>
        </div>
      </div>

      <div className="form-card__identity">
        <h3>{form.title}</h3>
        <p>{form.description || 'Açıklama belirtilmedi.'}</p>
      </div>

      <div className="form-card__footer">
        <a className="form-card__submission-count" href={`${boot.baseUrl}/admin/forms/${form.id}/submissions`}>
          <ClipboardList size={16} />
          {form.submission_count || 0} Yanıt
        </a>
        <a className="form-card__edit-link" href={`${boot.baseUrl}/admin/forms/${form.id}/edit`}>
          Düzenle <ExternalLink size={14} />
        </a>
      </div>
    </article>
  );
}

function TemplateCard({ boot, template }) {
  return (
    <article className="form-card form-card--template">
      <span className="status-badge status-badge--template">Şablon Tasarım</span>
      <div className="form-card__identity">
        <h3>{template.title}</h3>
        <p>{template.description || 'Açıklama belirtilmedi.'}</p>
      </div>
      <div className="form-card__footer form-card__footer--end">
        <a className="form-card__edit-link" href={`${boot.baseUrl}/admin/forms/${template.id}/edit`}>
          Taslağı Düzenle <ExternalLink size={14} />
        </a>
      </div>
    </article>
  );
}

export default function FormsListPage({ boot }) {
  const formsData = boot.forms || {};
  const forms = formsData.items || [];
  const templates = formsData.templates || [];
  const [tab, setTab] = useState('forms');
  const [isCreateOpen, setCreateOpen] = useState(false);
  const [deleteForm, setDeleteForm] = useState(null);

  return (
    <AdminLayout boot={boot}>
      <div className="admin-page-heading">
        <div>
          <span className="dashboard-hero__eyebrow">Form Yönetimi</span>
          <h2>Formlar ve Şablonlar</h2>
          <p>Başvurular, anketler ve topluluk veri havuzunu tek ekrandan yönetin.</p>
        </div>
        <button className="admin-button admin-button--primary" type="button" onClick={() => setCreateOpen(true)}>
          <Plus size={18} />
          Yeni Form Oluştur
        </button>
      </div>

      <div className="form-tabs">
        <button className={`form-tab ${tab === 'forms' ? 'is-active' : ''}`} type="button" onClick={() => setTab('forms')}>
          Gerçek Formlar
        </button>
        <button className={`form-tab ${tab === 'templates' ? 'is-active' : ''}`} type="button" onClick={() => setTab('templates')}>
          Şablonlarım
        </button>
      </div>

      {tab === 'forms' ? (
        <section className="form-grid">
          {forms.length === 0 ? (
            <div className="admin-empty">
              <FileText size={34} />
              <strong>Henüz form oluşturulmadı.</strong>
              <span>Etkinlik başvuruları veya anketler için ilk formunuzu oluşturun.</span>
              <ViperaDevSignature asset={boot.assets?.viperaDev} />
            </div>
          ) : (
            forms.map((form) => <FormCard boot={boot} form={form} onDelete={setDeleteForm} key={form.id} />)
          )}
        </section>
      ) : (
        <section className="form-grid">
          {templates.length === 0 ? (
            <div className="admin-empty">
              <FileText size={34} />
              <strong>Henüz şablon kaydedilmedi.</strong>
              <span>Formları düzenlerken "Şablon Olarak Kaydet" diyerek buraya ekleyebilirsiniz.</span>
              <ViperaDevSignature asset={boot.assets?.viperaDev} />
            </div>
          ) : (
            templates.map((tpl) => <TemplateCard boot={boot} template={tpl} key={tpl.id} />)
          )}
        </section>
      )}

      <CreateFormModal boot={boot} templates={templates} open={isCreateOpen} onClose={() => setCreateOpen(false)} />
      <DeleteFormModal boot={boot} form={deleteForm} onClose={() => setDeleteForm(null)} />
    </AdminLayout>
  );
}
