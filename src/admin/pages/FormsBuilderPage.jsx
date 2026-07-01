import {
  AlignLeft,
  CheckSquare,
  ChevronDown,
  ChevronLeft,
  Eye,
  FileUp,
  GraduationCap,
  Link2,
  Mail,
  Phone,
  Save,
  Settings,
  Share2,
  Type as TypeIcon,
  User,
  X,
} from 'lucide-react';
import { useState } from 'react';
import AdminLayout from '../components/AdminLayout.jsx';

function generateFieldId() {
  return 'field_' + Math.random().toString(36).slice(2, 11);
}

const FIELD_TOOLS = [
  { type: 'text', label: 'Kısa Yanıt', icon: TypeIcon },
  { type: 'textarea', label: 'Uzun Paragraf', icon: AlignLeft },
  { type: 'select', label: 'Çoktan Seçmeli', icon: ChevronDown },
  { type: 'checkbox', label: 'Onay Kutusu', icon: CheckSquare },
  { type: 'file', label: 'Dosya Yükleme (CV/Belge)', icon: FileUp },
];

const PRESETS = [
  { key: 'fullname', label: 'Ad Soyad', icon: User },
  { key: 'email', label: 'E-Posta', icon: Mail },
  { key: 'phone', label: 'Telefon (Ülke Kodlu)', icon: Phone },
  { key: 'university', label: 'Üniversite & Bölüm', icon: GraduationCap },
  { key: 'student_no', label: 'Öğrenci No', icon: GraduationCap },
  { key: 'tc', label: 'TC Kimlik No', icon: GraduationCap },
  { key: 'social', label: 'Sosyal Medya Hesabı', icon: Link2 },
];

const SOCIAL_PLATFORMS = [
  { value: 'instagram', label: 'Instagram' },
  { value: 'twitter', label: 'X (Twitter)' },
  { value: 'linkedin', label: 'LinkedIn' },
  { value: 'github', label: 'GitHub' },
  { value: 'youtube', label: 'YouTube' },
];

function buildPresetField(key) {
  const field = { id: generateFieldId(), type: 'text', required: true, options: [] };
  switch (key) {
    case 'fullname':
      field.label = 'Adınız ve Soyadınız';
      break;
    case 'email':
      field.type = 'email';
      field.label = 'E-Posta Adresiniz';
      break;
    case 'phone':
      field.type = 'phone';
      field.label = 'Telefon Numaranız';
      break;
    case 'tc':
      field.type = 'tc_kimlik';
      field.label = 'TC Kimlik Numarası';
      break;
    case 'student_no':
      field.type = 'student_no';
      field.label = 'Öğrenci Numarası';
      break;
    case 'social':
      field.type = 'social';
      field.platform = 'instagram';
      field.label = 'Instagram Hesabınız';
      break;
    case 'university':
      field.type = 'cascading_university';
      field.label = 'Eğitim Bilgileriniz';
      break;
    default:
      break;
  }
  return field;
}

function FieldPreview({ field }) {
  switch (field.type) {
    case 'text':
      return <input className="form-control" type="text" placeholder="Kısa metin yanıtı..." disabled />;
    case 'textarea':
      return <textarea className="form-control" placeholder="Uzun paragraf yanıtı..." disabled />;
    case 'email':
      return <input className="form-control" type="email" placeholder="ornek@ogrenci.edu.tr" disabled />;
    case 'student_no':
      return <input className="form-control" type="text" placeholder="Örn: 2024123456" disabled />;
    case 'tc_kimlik':
      return <input className="form-control" type="text" placeholder="11 Haneli TC Kimlik No" disabled />;
    case 'checkbox':
      return (
        <div className="field-preview-checkbox">
          <input type="checkbox" disabled />
          <span>Seçili Yanıt</span>
        </div>
      );
    case 'phone':
      return (
        <div className="field-preview-phone">
          <select className="form-control" disabled>
            <option>+90</option>
          </select>
          <input className="form-control" type="text" placeholder="(555) 123 45 67" disabled />
        </div>
      );
    case 'social':
      return (
        <div className="field-preview-social">
          <Link2 size={18} />
          <input type="text" disabled placeholder="@kullaniciadi veya link" />
        </div>
      );
    case 'cascading_university':
      return (
        <div className="field-preview-stack">
          <select className="form-control" disabled>
            <option>Üniversite Seçiniz...</option>
          </select>
          <select className="form-control" disabled>
            <option>Fakülte Seçiniz...</option>
          </select>
          <select className="form-control" disabled>
            <option>Bölüm Seçiniz...</option>
          </select>
          <select className="form-control" disabled>
            <option>Sınıf Seçiniz...</option>
          </select>
        </div>
      );
    case 'file':
      return (
        <div className="field-preview-file">
          <span>Dosya seçme alanı önizlemesi</span>
        </div>
      );
    case 'select': {
      const options = field.options && field.options.length > 0 ? field.options : ['Seçenek yok'];
      return (
        <select className="form-control" disabled>
          {options.map((opt) => (
            <option key={opt}>{opt}</option>
          ))}
        </select>
      );
    }
    default:
      return null;
  }
}

export default function FormsBuilderPage({ boot }) {
  const detail = boot.formBuilder || {};
  const formMeta = detail.form || {};
  const [fields, setFields] = useState(() => formMeta.fields || []);
  const [title, setTitle] = useState(formMeta.title || '');
  const [description, setDescription] = useState(formMeta.description || '');
  const [isActive, setIsActive] = useState(!!formMeta.is_active);
  const [settingsField, setSettingsField] = useState(null);
  const [formSettingsOpen, setFormSettingsOpen] = useState(false);
  const [draggedIndex, setDraggedIndex] = useState(null);
  const [toast, setToast] = useState(null);

  function showToast(message) {
    setToast(message);
    setTimeout(() => setToast(null), 3000);
  }

  function addField(type) {
    setFields((prev) => [...prev, { id: generateFieldId(), type, label: `Yeni Soru (${type})`, required: false, options: type === 'select' ? ['Seçenek 1'] : [] }]);
  }

  function addPreset(key) {
    setFields((prev) => [...prev, buildPresetField(key)]);
  }

  function removeField(id) {
    setFields((prev) => prev.filter((field) => field.id !== id));
  }

  function updateFieldSettings(id, patch) {
    setFields((prev) => prev.map((field) => (field.id === id ? { ...field, ...patch } : field)));
  }

  function dropField(targetIndex) {
    if (draggedIndex === null || draggedIndex === targetIndex) return;
    setFields((prev) => {
      const next = [...prev];
      const [moved] = next.splice(draggedIndex, 1);
      next.splice(targetIndex, 0, moved);
      return next;
    });
    setDraggedIndex(null);
  }

  async function persist(isTemplate) {
    try {
      const response = await fetch(detail.actions?.save, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': boot.csrfToken || '',
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ title, description, is_active: isActive ? 1 : 0, fields, is_template: isTemplate }),
      });
      const data = await response.json();
      if (data.success) {
        showToast(isTemplate ? '✅ Şablon olarak kaydedildi!' : '✅ Değişiklikler başarıyla kaydedildi!');
      } else {
        window.alert('Kaydetme başarısız: ' + (data.message || 'bilinmeyen hata'));
      }
    } catch (error) {
      window.alert('Bir hata oluştu!');
    }
  }

  function saveAsTemplate() {
    if (window.confirm("Bu formu gelecekte yeniden kullanmak üzere bir 'Şablon' olarak kaydetmek istediğinize emin misiniz?\n(Not: Şablonlar dışarıdan yanıt alamazlar, sadece kalıp olarak kalırlar.)")) {
      persist(1);
    }
  }

  function copyLink() {
    const publicUrl = `${window.location.origin}${boot.forms?.publicBase || '/f'}/${formMeta.slug}`;
    navigator.clipboard?.writeText(publicUrl).then(() => showToast('🔗 Form linki kopyalandı!')).catch(() => {});
  }

  return (
    <AdminLayout boot={boot}>
      <div className="builder-header">
        <div>
          <span className="dashboard-hero__eyebrow">Form Düzenleyici</span>
          <h2>{title || formMeta.title}</h2>
        </div>
        <div className="builder-header__actions">
          <a className="admin-button admin-button--ghost" href={`${boot.baseUrl}/admin/forms`} title="Geri Dön">
            <ChevronLeft size={18} />
            <span className="btn-label">Geri Dön</span>
          </a>
          <button className="admin-button admin-button--ghost" type="button" onClick={() => setFormSettingsOpen(true)} title="Form Ayarları">
            <Settings size={18} />
            <span className="btn-label">Form Ayarları</span>
          </button>
          <button className="admin-button admin-button--ghost" type="button" onClick={copyLink} title="Paylaş">
            <Share2 size={18} />
            <span className="btn-label">Paylaş</span>
          </button>
          <a className="admin-button admin-button--ghost" href={`${boot.baseUrl}/admin/forms/${formMeta.id}/submissions`} title="Yanıtları Gör">
            <Eye size={18} />
            <span className="btn-label">Yanıtları Gör</span>
          </a>
          <button className="admin-button admin-button--success" type="button" onClick={saveAsTemplate} title="Şablon Olarak Kaydet">
            <span className="btn-label">Şablon Olarak Kaydet</span>
          </button>
          <button className="admin-button admin-button--primary" type="button" onClick={() => persist(0)} title="Formu Kaydet">
            <Save size={18} />
            <span className="btn-label">Formu Kaydet</span>
          </button>
        </div>
      </div>

      <div className="builder-container">
        <div className="toolbox">
          <h3>Temel Alanlar</h3>
          {FIELD_TOOLS.map(({ type, label, icon: Icon }) => (
            <button className="tool-btn" type="button" key={type} onClick={() => addField(type)}>
              <Icon size={20} />
              {label}
            </button>
          ))}

          <h3 className="toolbox__secondary-title">Hazır Kalıplar</h3>
          {PRESETS.map(({ key, label, icon: Icon }) => (
            <button className="tool-btn tool-btn--preset" type="button" key={key} onClick={() => addPreset(key)}>
              <Icon size={20} />
              {label}
            </button>
          ))}
        </div>

        <div className="canvas">
          {fields.length === 0 ? (
            <div className="canvas__empty">
              <TypeIcon size={48} />
              <p>Soldaki menüden form alanları eklemeye başlayın.</p>
            </div>
          ) : (
            fields.map((field, index) => (
              <div
                className="field-card"
                draggable
                onDragStart={() => setDraggedIndex(index)}
                onDragOver={(event) => event.preventDefault()}
                onDrop={() => dropField(index)}
                key={field.id}
              >
                <div className="field-card__header">
                  <div className="field-card__label">
                    {field.label} {field.required ? <span className="field-card__required">*</span> : null}
                  </div>
                  <div className="field-card__actions">
                    <button type="button" onClick={() => setSettingsField(field.id)} title="Ayarlar">
                      <Settings size={16} />
                    </button>
                    <button type="button" className="is-danger" onClick={() => removeField(field.id)} title="Sil">
                      <X size={16} />
                    </button>
                  </div>
                </div>
                <FieldPreview field={field} />
              </div>
            ))
          )}
        </div>
      </div>

      {settingsField ? (
        <FieldSettingsModal
          field={fields.find((f) => f.id === settingsField)}
          onClose={() => setSettingsField(null)}
          onApply={(patch) => {
            updateFieldSettings(settingsField, patch);
            setSettingsField(null);
          }}
        />
      ) : null}

      {formSettingsOpen ? (
        <div className="admin-modal-backdrop" role="presentation" onMouseDown={() => setFormSettingsOpen(false)}>
          <section className="admin-modal" role="dialog" aria-modal="true" onMouseDown={(event) => event.stopPropagation()}>
            <header className="admin-modal__header">
              <div>
                <h2>Form Genel Ayarları</h2>
              </div>
              <button className="admin-icon-button" type="button" aria-label="Kapat" onClick={() => setFormSettingsOpen(false)}>
                <X size={18} />
              </button>
            </header>
            <div className="admin-form">
              <label>
                <span>Form Başlığı</span>
                <input value={title} onChange={(event) => setTitle(event.target.value)} />
              </label>
              <label>
                <span>Açıklama / Alt Başlık</span>
                <textarea value={description} onChange={(event) => setDescription(event.target.value)} />
              </label>
              <label className="admin-form__checkbox">
                <input type="checkbox" checked={isActive} onChange={(event) => setIsActive(event.target.checked)} />
                <span>Bu Form Yayında / Aktif</span>
              </label>
              <div className="admin-form__actions">
                <button className="admin-button admin-button--ghost" type="button" onClick={() => setFormSettingsOpen(false)}>
                  İptal
                </button>
                <button
                  className="admin-button admin-button--primary"
                  type="button"
                  onClick={() => {
                    setFormSettingsOpen(false);
                    persist(0);
                  }}
                >
                  Değişiklikleri Kaydet
                </button>
              </div>
            </div>
          </section>
        </div>
      ) : null}

      {toast ? <div className="builder-toast">{toast}</div> : null}
    </AdminLayout>
  );
}

function FieldSettingsModal({ field, onClose, onApply }) {
  const [label, setLabel] = useState(field?.label || '');
  const [required, setRequired] = useState(!!field?.required);
  const [optionsText, setOptionsText] = useState((field?.options || []).join(', '));
  const [platform, setPlatform] = useState(field?.platform || 'instagram');

  if (!field) return null;

  function apply() {
    const patch = { label, required };
    if (field.type === 'select') {
      patch.options = optionsText
        .split(',')
        .map((opt) => opt.trim())
        .filter(Boolean);
    }
    if (field.type === 'social') {
      patch.platform = platform;
    }
    onApply(patch);
  }

  return (
    <div className="admin-modal-backdrop" role="presentation" onMouseDown={onClose}>
      <section className="admin-modal" role="dialog" aria-modal="true" onMouseDown={(event) => event.stopPropagation()}>
        <header className="admin-modal__header">
          <div>
            <h2>Alan Ayarları</h2>
          </div>
          <button className="admin-icon-button" type="button" aria-label="Kapat" onClick={onClose}>
            <X size={18} />
          </button>
        </header>
        <div className="admin-form">
          <label>
            <span>Soru / Başlık</span>
            <input value={label} onChange={(event) => setLabel(event.target.value)} />
          </label>
          <label className="admin-form__checkbox">
            <input type="checkbox" checked={required} onChange={(event) => setRequired(event.target.checked)} />
            <span>Bu alan zorunlu mu?</span>
          </label>
          {field.type === 'select' ? (
            <label>
              <span>Seçenekler (Virgülle ayırın)</span>
              <input value={optionsText} onChange={(event) => setOptionsText(event.target.value)} placeholder="Seçenek 1, Seçenek 2, Seçenek 3" />
            </label>
          ) : null}
          {field.type === 'social' ? (
            <label>
              <span>Sosyal Medya Platformu</span>
              <select value={platform} onChange={(event) => setPlatform(event.target.value)}>
                {SOCIAL_PLATFORMS.map((opt) => (
                  <option value={opt.value} key={opt.value}>
                    {opt.label}
                  </option>
                ))}
              </select>
            </label>
          ) : null}
          <div className="admin-form__actions">
            <button className="admin-button admin-button--ghost" type="button" onClick={onClose}>
              İptal
            </button>
            <button className="admin-button admin-button--primary" type="button" onClick={apply}>
              Uygula
            </button>
          </div>
        </div>
      </section>
    </div>
  );
}
