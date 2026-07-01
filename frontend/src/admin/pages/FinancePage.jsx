import { useState } from 'react';
import { Plus, Trash2, TrendingUp, TrendingDown, DollarSign } from 'lucide-react';
import Panel from '../components/Panel.jsx';
import StatPanel from '../components/StatPanel.jsx';

export default function FinancePage({ boot }) {
  const { transactions = [], summary, members = [], actions, csrfToken, flash, userRole } = boot;
  const isAdminOrDir = userRole === 'superadmin' || userRole === 'director';

  const [showAddForm, setShowAddForm] = useState(false);
  const [formData, setFormData] = useState({
    title: '',
    amount: '',
    type: 'income',
    description: '',
    member_id: ''
  });

  const formatDate = (dateStr) => {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleDateString('tr-TR', {
      day: '2-digit', month: 'short', year: 'numeric'
    });
  };

  const formatCurrency = (val) => {
    return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(val || 0);
  };

  return (
    <div className="finance-root fade-in-up">
      {flash?.error && <div className="admin-toast admin-toast--danger">{flash.error}</div>}
      {flash?.success && <div className="admin-toast admin-toast--success">{flash.success}</div>}

      <div className="dept-dash-stats" style={{ marginBottom: '32px' }}>
        <StatPanel 
          value={formatCurrency(summary?.total_income)} 
          label="Toplam Gelir"
          icon={<TrendingUp size={24} style={{ color: '#00e676' }} />}
        />
        <StatPanel 
          value={formatCurrency(summary?.total_expense)} 
          label="Toplam Gider"
          icon={<TrendingDown size={24} style={{ color: '#ef4444' }} />}
        />
        <StatPanel 
          value={formatCurrency(summary?.net_balance)} 
          label="Net Bakiye"
          icon={<DollarSign size={24} style={{ color: summary?.net_balance >= 0 ? '#00e676' : '#ef4444' }} />}
        />
      </div>

      <div className="finance-layout">
        {/* ── Transaction List ── */}
        <div className="finance-list-col">
          <Panel 
            title="İşlem Geçmişi" 
            padding="0"
            headerAction={
              isAdminOrDir && (
                <button 
                  className="admin-btn admin-btn--primary admin-btn--sm"
                  onClick={() => setShowAddForm(!showAddForm)}
                >
                  <Plus size={16} /> Yeni İşlem
                </button>
              )
            }
          >
            <div className="finance-table-wrap">
              <table className="admin-table">
                <thead>
                  <tr>
                    <th>Tarih</th>
                    <th>Başlık</th>
                    <th>İlgili Üye</th>
                    <th>Tutar</th>
                    {isAdminOrDir && <th style={{ width: 60 }}>Sil</th>}
                  </tr>
                </thead>
                <tbody>
                  {transactions.length === 0 ? (
                    <tr>
                      <td colSpan={isAdminOrDir ? 5 : 4} className="admin-table-empty">Henüz işlem yok.</td>
                    </tr>
                  ) : (
                    transactions.map(t => (
                      <tr key={t.id}>
                        <td>{formatDate(t.transaction_date || t.created_at)}</td>
                        <td>
                          <div style={{ fontWeight: 500, color: 'var(--admin-text)' }}>{t.title}</div>
                          <div style={{ fontSize: '0.8rem', color: 'var(--admin-muted)' }}>{t.description}</div>
                        </td>
                        <td>{t.member_name || '-'}</td>
                        <td>
                          <span className={`finance-amount finance-amount--${t.type}`}>
                            {t.type === 'income' ? '+' : '-'}{formatCurrency(t.amount)}
                          </span>
                        </td>
                        {isAdminOrDir && (
                          <td>
                            <form action={actions.delete} method="POST" onSubmit={(e) => {
                              if(!window.confirm('Bu işlemi silmek istediğinize emin misiniz?')) e.preventDefault();
                            }}>
                              <input type="hidden" name="csrf_token" value={csrfToken} />
                              <input type="hidden" name="id" value={t.id} />
                              <button type="submit" className="admin-icon-btn text-danger">
                                <Trash2 size={16} />
                              </button>
                            </form>
                          </td>
                        )}
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </Panel>
        </div>

        {/* ── Add Form (Conditional) ── */}
        {isAdminOrDir && showAddForm && (
          <div className="finance-form-col">
            <Panel title="Yeni İşlem Ekle">
              <form action={actions.add} method="POST" className="mgmt-form">
                <input type="hidden" name="csrf_token" value={csrfToken} />

                <div className="mgmt-form-group">
                  <label className="mgmt-label">İşlem Tipi</label>
                  <select 
                    name="type" 
                    className="mgmt-input"
                    value={formData.type}
                    onChange={e => setFormData({...formData, type: e.target.value})}
                  >
                    <option value="income">Gelir (+)</option>
                    <option value="expense">Gider (-)</option>
                  </select>
                </div>

                <div className="mgmt-form-group">
                  <label className="mgmt-label">Başlık</label>
                  <input 
                    type="text" 
                    name="title" 
                    className="mgmt-input" 
                    required 
                    value={formData.title}
                    onChange={e => setFormData({...formData, title: e.target.value})}
                  />
                </div>

                <div className="mgmt-form-group">
                  <label className="mgmt-label">Tutar (TL)</label>
                  <input 
                    type="number" 
                    step="0.01" 
                    name="amount" 
                    className="mgmt-input" 
                    required 
                    value={formData.amount}
                    onChange={e => setFormData({...formData, amount: e.target.value})}
                  />
                </div>

                <div className="mgmt-form-group">
                  <label className="mgmt-label">İlgili Üye (Opsiyonel)</label>
                  <select 
                    name="member_id" 
                    className="mgmt-input"
                    value={formData.member_id}
                    onChange={e => setFormData({...formData, member_id: e.target.value})}
                  >
                    <option value="">-- Seçiniz --</option>
                    {members.map(m => (
                      <option key={m.user_id} value={m.user_id}>{m.user_name} ({m.email})</option>
                    ))}
                  </select>
                </div>

                <div className="mgmt-form-group">
                  <label className="mgmt-label">Açıklama (Opsiyonel)</label>
                  <textarea 
                    name="description" 
                    className="mgmt-input"
                    rows={3}
                    value={formData.description}
                    onChange={e => setFormData({...formData, description: e.target.value})}
                  ></textarea>
                </div>

                <div className="mgmt-form-actions">
                  <button type="submit" className="admin-btn admin-btn--primary">
                    Kaydet
                  </button>
                </div>
              </form>
            </Panel>
          </div>
        )}
      </div>
    </div>
  );
}
