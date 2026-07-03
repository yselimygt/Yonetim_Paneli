import {
  Activity,
  AlertTriangle,
  ChevronDown,
  ChevronLeft,
  ChevronRight,
  ChevronUp,
  ClipboardList,
  Globe,
  Info,
  LogIn,
  LogOut,
  Settings,
  Shield,
  Trash2,
  UserPlus,
  Users,
  X,
} from 'lucide-react';
import { useState } from 'react';
import AdminLayout from '../components/AdminLayout.jsx';
import StatPanel from '../components/StatPanel.jsx';
import ViperaDevSignature from '../components/ViperaDevSignature.jsx';

/* ── Action catalogue ─────────────────────────────────── */
const ACTION_MAP = {
  LOGIN_SUCCESS:                 { label: 'Giriş Başarılı',            tone: 'success', Icon: LogIn      },
  LOGIN_FAILED:                  { label: 'Giriş Başarısız',           tone: 'danger',  Icon: AlertTriangle },
  LOGOUT:                        { label: 'Çıkış Yapıldı',             tone: 'muted',   Icon: LogOut     },
  CREATE_USER:                   { label: 'Kullanıcı Eklendi',         tone: 'teal',    Icon: UserPlus   },
  UPDATE_USER:                   { label: 'Kullanıcı Güncellendi',     tone: 'teal',   Icon: Settings   },
  DELETE_USER:                   { label: 'Kullanıcı Silindi',         tone: 'danger',  Icon: Trash2     },
  CREATE_DEPARTMENT:             { label: 'Departman Eklendi',         tone: 'teal',    Icon: UserPlus   },
  UPDATE_DEPARTMENT:             { label: 'Departman Güncellendi',     tone: 'teal',   Icon: Settings   },
  DELETE_DEPARTMENT:             { label: 'Departman Silindi',         tone: 'danger',  Icon: Trash2     },
  ASSIGN_DEPARTMENT_TO_USER:     { label: 'Departman Atandı',          tone: 'teal',    Icon: Users      },
  REMOVE_DEPARTMENT_FROM_USER:   { label: 'Departman Çıkarıldı',      tone: 'teal',   Icon: Users      },
  ASSIGN_APPS_TO_DEPARTMENT:     { label: 'Uygulama Atandı',          tone: 'teal',    Icon: Globe      },
  APP_INTERACTION:               { label: 'Uygulama İçi İşlem',       tone: 'info',    Icon: Activity   },
  PUBLIC_APP_INTERACTION:        { label: 'Açık Form/App İşlemi',     tone: 'info',    Icon: Globe      },
  PUBLIC_GLOBAL_APP_INTERACTION: { label: 'Açık Global İşlem',        tone: 'info',    Icon: Globe      },
  UPDATE_PROFILE:                { label: 'Profil Güncellendi',        tone: 'teal',    Icon: Settings   },
  CREATE_GLOBAL_ANNOUNCEMENT:    { label: 'Küresel Duyuru',           tone: 'teal',   Icon: Activity   },
  CREATE_FORM:                   { label: 'Form Oluşturuldu',          tone: 'teal',    Icon: ClipboardList },
  TOGGLE_FORM_TEMPLATE:          { label: 'Şablon Durumu Değişti',    tone: 'muted',   Icon: Settings   },
  CREATE_DUES_PERIOD:            { label: 'Aidat Dönemi Açıldı',      tone: 'success', Icon: UserPlus   },
  DELETE_DUES_PERIOD:            { label: 'Aidat Dönemi Silindi',      tone: 'danger',  Icon: Trash2     },
  TOGGLE_DUES_PAYMENT:           { label: 'Aidat Ödemesi Güncellendi',tone: 'teal',   Icon: Settings   },
  CREATE_FINANCE_TX:             { label: 'Finans İşlemi Eklendi',    tone: 'success', Icon: Activity   },
  UPDATE_FINANCE_TX:             { label: 'Finans İşlemi Düzenlendi', tone: 'teal',   Icon: Settings   },
  DELETE_FINANCE_TX:             { label: 'Finans İşlemi Silindi',    tone: 'danger',  Icon: Trash2     },
  RECEIVE_FINANCE_PROXY:         { label: 'Elden Ödeme Alındı',       tone: 'teal',    Icon: Activity   },
};

/* ── Detail key translations ──────────────────────────── */
const KEY_MAP = {
  email: 'E-posta', name: 'Ad', slug: 'Kısa Ad',
  department_id: 'Departman', role: 'Rol',
  modules: 'Atanan Uygulamalar', status: 'Durum',
  type: 'Tür', amount: 'Tutar', title: 'Başlık',
  app_slug: 'Uygulama Slug', is_template: 'Şablon Mu?',
  is_active: 'Aktif', is_super_admin: 'Süper Admin',
  can_manage_passwords: 'Şifre Yönetimi', is_management: 'Yönetim Dept.',
  description: 'Açıklama', category: 'Kategori',
};

const APP_MAP = {
  form: 'Form', drive: 'Dosya Yöneticisi', calendar: 'Takvim',
  announcement: 'Duyuru', report: 'Rapor', directory: 'Kurumsal Rehber',
  members: 'Üye Listesi', finance: 'Finans Paneli', dues: 'Aidat Takibi',
  sponsor: 'Sponsorlar', event: 'Etkinlik', partner: 'Anlaşmalı Mekanlar',
  bootcamp: 'Bootcamp Eğitimleri', certificate: 'Sertifika', inventory: 'Envanter',
  links: 'Hızlı Bağlantılar', qr: 'Karekod', shortener: 'Link Kısaltıcı',
  agile: 'Agile', passwords: 'Şifre Yöneticisi', ideahub: 'Idea Hub',
  bylaws: 'Tüzük', audit: 'Denetim', trophy: 'Ödüller',
  wiki: 'Wiki', meeting: 'Toplantı', budget: 'Bütçe',
  mentorship: 'Mentor', social: 'İçerik', alumni: 'Mezunlar',
};

const ROLE_MAP   = { director: 'Yönetici', member: 'Üye', president: 'Başkan', vice_president: 'Başkan Yardımcısı' };
const STATUS_MAP = { paid: 'Ödendi', unpaid: 'Ödenmedi', pending: 'Bekliyor', delivered: 'Teslim Alındı' };

const ENTITY_MAP = {
  User: 'Kullanıcı', Department: 'Departman', App: 'Uygulama',
  Announcement: 'Duyuru', DuesPeriod: 'Aidat Dönemi',
  DuesPayment: 'Aidat Ödemesi', FinanceTransaction: 'Finans İşlemi',
};

/* ── Helpers ──────────────────────────────────────────── */
function formatDate(raw) {
  if (!raw) return '-';
  const d = new Date(raw.replace(' ', 'T'));
  if (isNaN(d)) return raw;
  return d.toLocaleString('tr-TR', {
    day: '2-digit', month: '2-digit', year: 'numeric',
    hour: '2-digit', minute: '2-digit', second: '2-digit',
  });
}

function resolveEntityName(log, deptsMap, usersMap) {
  const base = ENTITY_MAP[log.entity_type] ?? log.entity_type;
  if (!base) return '-';
  const id = log.entity_id;
  if (!id) return base;

  if (log.entity_type === 'User') {
    const uName = usersMap[id] ?? `Silinmiş Üye (#${id})`;
    return `${base} (${uName})`;
  }
  if (log.entity_type === 'Department') {
    const dName = deptsMap[id] ?? `Silinmiş Departman (#${id})`;
    return `${base} (${dName})`;
  }
  return `${base} (#${id})`;
}

function parseDetails(raw, deptsMap) {
  if (!raw) return null;
  try {
    const obj = JSON.parse(raw);
    if (typeof obj !== 'object' || obj === null) return String(raw);
    return Object.entries(obj).map(([k, v]) => {
      let val = v;
      if (k === 'modules' && Array.isArray(v)) {
        val = v.map((m) => APP_MAP[m] ?? m).join(', ');
      } else if (k === 'role' && typeof v === 'string') {
        val = ROLE_MAP[v] ?? v;
      } else if (k === 'status' && typeof v === 'string') {
        val = STATUS_MAP[v] ?? v;
      } else if (k === 'department_id') {
        val = deptsMap[v] ?? `Silinmiş Departman (#${v})`;
      } else if (/^(is_|can_|has_)/.test(k)) {
        val = v ? 'Evet' : 'Hayır';
      } else if (typeof v === 'object') {
        val = JSON.stringify(v);
      }
      return { key: KEY_MAP[k] ?? k, val: String(val) };
    });
  } catch {
    return String(raw);
  }
}

/* ── Action Badge ─────────────────────────────────────── */
function ActionBadge({ action }) {
  const info = ACTION_MAP[action] ?? { label: action, tone: 'muted', Icon: Info };
  const { label, tone, Icon } = info;
  return (
    <span className={`log-action-badge log-action-badge--${tone}`} title={action}>
      <Icon size={11} aria-hidden="true" />
      {label}
    </span>
  );
}

/* ── Collapsible Detail ───────────────────────────────── */
function DetailCell({ raw, deptsMap }) {
  const [open, setOpen] = useState(false);
  const parsed = parseDetails(raw, deptsMap);

  if (!parsed) return <span className="log-detail--empty">—</span>;

  if (typeof parsed === 'string') {
    return <span className="log-detail--text">{parsed}</span>;
  }

  if (parsed.length === 0) return <span className="log-detail--empty">—</span>;

  return (
    <div className="log-detail">
      <button
        type="button"
        className="log-detail__toggle"
        aria-expanded={open}
        onClick={() => setOpen((v) => !v)}
      >
        <span>{open ? 'Kapat' : `${parsed.length} alan`}</span>
        {open ? <ChevronUp size={13} /> : <ChevronDown size={13} />}
      </button>
      {open && (
        <ul className="log-detail__list" role="list">
          {parsed.map(({ key, val }) => (
            <li key={key}>
              <span className="log-detail__key">{key}:</span>
              <span className="log-detail__val">{val}</span>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}

/* ── Desktop Table Row ────────────────────────────────── */
function LogRow({ log, deptsMap, usersMap }) {
  const entityName = resolveEntityName(log, deptsMap, usersMap);
  return (
    <tr className="log-row">
      <td className="log-cell log-cell--id">#{log.id}</td>
      <td className="log-cell log-cell--date">
        <time dateTime={log.created_at}>{formatDate(log.created_at)}</time>
      </td>
      <td className="log-cell log-cell--user">
        {log.user_id ? (
          <>
            <span className="log-user__name">{log.user_name ?? '—'}</span>
            <span className="log-user__id">ID: {log.user_id}</span>
          </>
        ) : (
          <span className="log-user__anon">Sistem / Anonim</span>
        )}
      </td>
      <td className="log-cell log-cell--action">
        <ActionBadge action={log.action} />
      </td>
      <td className="log-cell log-cell--entity">{entityName}</td>
      <td className="log-cell log-cell--ip">{log.ip_address ?? '—'}</td>
      <td className="log-cell log-cell--detail">
        <DetailCell raw={log.details} deptsMap={deptsMap} />
      </td>
    </tr>
  );
}

/* ── Mobile Timeline Card ─────────────────────────────── */
function LogCard({ log, deptsMap, usersMap }) {
  const entityName = resolveEntityName(log, deptsMap, usersMap);
  return (
    <article className="log-card" aria-label={`Log #${log.id}`}>
      <div className="log-card__top">
        <ActionBadge action={log.action} />
        <time className="log-card__time" dateTime={log.created_at}>
          {formatDate(log.created_at)}
        </time>
      </div>
      <div className="log-card__meta">
        <span className="log-card__user">
          {log.user_id ? (log.user_name ?? `#${log.user_id}`) : 'Sistem / Anonim'}
        </span>
        <span className="log-card__sep">·</span>
        <span className="log-card__entity">{entityName}</span>
        {log.ip_address && (
          <>
            <span className="log-card__sep">·</span>
            <span className="log-card__ip">{log.ip_address}</span>
          </>
        )}
      </div>
      <DetailCell raw={log.details} deptsMap={deptsMap} />
    </article>
  );
}

/* ── Pagination ───────────────────────────────────────── */
function Pagination({ page, totalPages }) {
  if (totalPages <= 1) return null;

  const start = Math.max(1, page - 2);
  const end   = Math.min(totalPages, page + 2);
  const pages = [];
  for (let i = start; i <= end; i++) pages.push(i);

  return (
    <nav className="log-pagination" aria-label="Sayfalama">
      {page > 1 && (
        <a className="log-pagination__btn" href={`?page=${page - 1}`} aria-label="Önceki sayfa">
          <ChevronLeft size={16} />
        </a>
      )}
      {pages.map((p) => (
        <a
          key={p}
          className={`log-pagination__btn ${p === page ? 'is-active' : ''}`}
          href={`?page=${p}`}
          aria-current={p === page ? 'page' : undefined}
        >
          {p}
        </a>
      ))}
      {page < totalPages && (
        <a className="log-pagination__btn" href={`?page=${page + 1}`} aria-label="Sonraki sayfa">
          <ChevronRight size={16} />
        </a>
      )}
    </nav>
  );
}

/* ── Empty State ──────────────────────────────────────── */
function EmptyLogs({ asset }) {
  return (
    <div className="admin-empty logs-empty">
      <ClipboardList size={38} aria-hidden="true" />
      <strong>Henüz log kaydı yok.</strong>
      <span>Sistem işlemleri gerçekleştikçe burada görünür.</span>
      <ViperaDevSignature asset={asset} />
    </div>
  );
}

/* ── Page ─────────────────────────────────────────────── */
export default function SuperAdminLogsPage({ boot }) {
  const data       = boot.logsData || {};
  const logs       = data.logs      ?? [];
  const page       = data.page      ?? 1;
  const totalPages = data.totalPages ?? 1;
  const totalLogs  = data.totalLogs  ?? logs.length;
  const deptsMap   = data.deptsMap   ?? {};
  const usersMap   = data.usersMap   ?? {};

  const dangerCount = logs.filter((l) => ['LOGIN_FAILED', 'DELETE_USER', 'DELETE_DEPARTMENT', 'DELETE_DUES_PERIOD', 'DELETE_FINANCE_TX'].includes(l.action)).length;
  const authCount   = logs.filter((l) => ['LOGIN_SUCCESS', 'LOGOUT', 'LOGIN_FAILED'].includes(l.action)).length;

  return (
    <AdminLayout boot={boot}>
      {/* Page heading */}
      <div className="admin-page-heading">
        <div>
          <span className="dashboard-hero__eyebrow">Audit Log</span>
          <h2>Sistem Logları</h2>
          <p>Tüm sistem olaylarının denetim defteri — salt okunur, tam sıralı.</p>
        </div>
        <span className="admin-role-badge">
          <Shield size={13} />
          Süper Admin
        </span>
      </div>

      {/* Flash */}
      {boot.flash?.message && (
        <div className={`admin-alert admin-alert--${boot.flash.type || 'success'}`}>
          {boot.flash.message}
        </div>
      )}

      {/* Stats */}
      <section className="admin-stats-grid" aria-label="Log özeti">
        <StatPanel icon={<ClipboardList size={22} />} label="Toplam Log" value={totalLogs} detail={`Sayfa ${page} / ${totalPages}`} tone="orange" />
        <StatPanel icon={<Activity size={22} />} label="Bu Sayfada" value={logs.length} detail="Son işlemler önce" tone="teal" />
        <StatPanel icon={<LogIn size={22} />} label="Auth İşlemi" value={authCount} detail="Giriş / çıkış" tone="green" />
        <StatPanel icon={<AlertTriangle size={22} />} label="Kritik" value={dangerCount} detail="Silme / başarısız" tone="red" />
      </section>

      {/* Content panel */}
      <div className="admin-panel logs-panel">
        <header className="logs-panel__header">
          <h3>
            <ClipboardList size={16} aria-hidden="true" />
            Denetim Defteri
          </h3>
          <span className="logs-panel__count">{totalLogs} kayıt · sayfa {page}/{totalPages}</span>
        </header>

        {logs.length === 0 ? (
          <EmptyLogs asset={boot.assets?.viperaDev} />
        ) : (
          <>
            {/* Desktop table */}
            <div className="logs-table-wrap" role="region" aria-label="Log tablosu" tabIndex={0}>
              <table className="logs-table">
                <thead>
                  <tr>
                    <th scope="col" className="logs-th">ID</th>
                    <th scope="col" className="logs-th">Tarih</th>
                    <th scope="col" className="logs-th">Kullanıcı</th>
                    <th scope="col" className="logs-th">İşlem</th>
                    <th scope="col" className="logs-th">Varlık</th>
                    <th scope="col" className="logs-th">IP Adresi</th>
                    <th scope="col" className="logs-th">Detaylar</th>
                  </tr>
                </thead>
                <tbody>
                  {logs.map((log) => (
                    <LogRow key={log.id} log={log} deptsMap={deptsMap} usersMap={usersMap} />
                  ))}
                </tbody>
              </table>
            </div>

            {/* Mobile timeline */}
            <div className="logs-timeline" aria-label="Log zaman çizelgesi">
              {logs.map((log) => (
                <LogCard key={log.id} log={log} deptsMap={deptsMap} usersMap={usersMap} />
              ))}
            </div>

            <Pagination page={page} totalPages={totalPages} />
          </>
        )}
      </div>
    </AdminLayout>
  );
}
