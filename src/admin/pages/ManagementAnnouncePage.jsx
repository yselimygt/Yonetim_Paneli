import { useState } from 'react';
import { Pin, Send, Megaphone, Globe } from 'lucide-react';
import Panel from '../components/Panel.jsx';

export default function ManagementAnnouncePage({ boot }) {
  const { announcements = [], actions, csrfToken, flash } = boot;

  const formatDate = (dateStr) => {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleString('tr-TR', {
      day: '2-digit', month: 'short', year: 'numeric',
      hour: '2-digit', minute: '2-digit'
    });
  };

  return (
    <div className="mgmt-announce-root fade-in-up">
      {flash?.error && (
        <div className="admin-toast admin-toast--danger">
          {flash.error}
        </div>
      )}
      {flash?.success && (
        <div className="admin-toast admin-toast--success">
          {flash.success}
        </div>
      )}

      <div className="mgmt-announce-layout">
        {/* ── Form Area ── */}
        <div className="mgmt-announce-form-col">
          <Panel title="Yeni Global Duyuru">
            <form action={actions.create} method="POST" className="mgmt-form">
              <input type="hidden" name="csrf_token" value={csrfToken} />
              
              <div className="mgmt-form-group">
                <label className="mgmt-label">Başlık</label>
                <input 
                  type="text" 
                  name="title" 
                  className="mgmt-input" 
                  placeholder="Duyuru başlığı..." 
                  required 
                />
              </div>

              <div className="mgmt-form-group">
                <label className="mgmt-label">İçerik</label>
                <textarea 
                  name="content" 
                  className="mgmt-input" 
                  rows={6}
                  placeholder="Tüm departmanların göreceği duyuru metni..." 
                  required 
                />
              </div>

              <div className="mgmt-form-group mgmt-form-group--inline">
                <label className="mgmt-checkbox-label">
                  <input type="checkbox" name="is_pinned" value="1" />
                  <span className="mgmt-checkbox-text">
                    <Pin size={16} /> Başa Tuttur
                  </span>
                </label>
              </div>

              <div className="mgmt-form-actions">
                <button type="submit" className="admin-btn admin-btn--primary">
                  <Send size={16} /> Gönder
                </button>
              </div>
            </form>
          </Panel>
        </div>

        {/* ── List Area ── */}
        <div className="mgmt-announce-list-col">
          <h3 className="mgmt-list-title">Geçmiş Duyurular</h3>
          <div className="mgmt-announce-list">
            {announcements.length === 0 ? (
              <div className="mgmt-empty">Henüz global duyuru bulunmuyor.</div>
            ) : (
              announcements.map((ann) => (
                <div key={ann.id} className={`mgmt-announce-card${ann.is_pinned === 1 ? ' is-pinned' : ''}`}>
                  <div className="mgmt-announce-card__header">
                    <div className="mgmt-announce-card__title">
                      {ann.is_pinned === 1 && <Pin size={14} className="mgmt-icon-pinned" />}
                      {ann.is_global === 1 && <Globe size={14} className="mgmt-icon-global" />}
                      {ann.title}
                    </div>
                    <div className="mgmt-announce-card__date">
                      {formatDate(ann.created_at)}
                    </div>
                  </div>
                  <div className="mgmt-announce-card__body">
                    {ann.content}
                  </div>
                </div>
              ))
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
