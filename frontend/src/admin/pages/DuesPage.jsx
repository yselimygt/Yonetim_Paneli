import { useState } from 'react';
import { Plus, Trash2, Calendar, CreditCard, UserCircle } from 'lucide-react';
import Panel from '../components/Panel.jsx';
import StatPanel from '../components/StatPanel.jsx';

export default function DuesPage({ boot }) {
  const { 
    periods = [], 
    selectedPeriod, 
    payments = [], 
    myPayments = [], 
    userRole, 
    actions, 
    csrfToken, 
    flash, 
    currentDept 
  } = boot;
  
  const isDirector = userRole === 'director' || userRole === 'superadmin';

  const [showAddPeriod, setShowAddPeriod] = useState(false);
  const [showAddPayment, setShowAddPayment] = useState(false);
  const [periodForm, setPeriodForm] = useState({ name: '', amount: '' });
  const [paymentForm, setPaymentForm] = useState({ 
    user_id: '', 
    period_id: selectedPeriod?.id || '', 
    amount: selectedPeriod?.amount || '', 
    note: '' 
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

  const handlePeriodChange = (e) => {
    const periodId = e.target.value;
    window.location.href = `?period_id=${periodId}`;
  };

  return (
    <div className="dues-root fade-in-up">
      {flash?.error && <div className="admin-toast admin-toast--danger">{flash.error}</div>}
      {flash?.success && <div className="admin-toast admin-toast--success">{flash.success}</div>}

      <div className="dues-layout">
        {/* ── Main Area ── */}
        <div className="dues-main-col">
          {/* Director View */}
          {isDirector ? (
            <>
              <Panel 
                title="Dönem Seçimi" 
                headerAction={
                  <button 
                    className="admin-btn admin-btn--primary admin-btn--sm"
                    onClick={() => setShowAddPeriod(!showAddPeriod)}
                  >
                    <Plus size={16} /> Yeni Dönem
                  </button>
                }
              >
                {showAddPeriod && (
                  <form action={actions.storePeriod} method="POST" className="mgmt-form" style={{ marginBottom: 24, padding: 16, background: 'rgba(0,0,0,0.2)', borderRadius: 8 }}>
                    <input type="hidden" name="csrf_token" value={csrfToken} />
                    <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr auto', gap: 16, alignItems: 'end' }}>
                      <div className="mgmt-form-group">
                        <label className="mgmt-label">Dönem Adı (Örn: 2024-2025)</label>
                        <input type="text" name="name" className="mgmt-input" required value={periodForm.name} onChange={e => setPeriodForm({...periodForm, name: e.target.value})} />
                      </div>
                      <div className="mgmt-form-group">
                        <label className="mgmt-label">Tutar (TL)</label>
                        <input type="number" step="0.01" name="amount" className="mgmt-input" required value={periodForm.amount} onChange={e => setPeriodForm({...periodForm, amount: e.target.value})} />
                      </div>
                      <div className="mgmt-form-actions" style={{ marginTop: 0 }}>
                        <button type="submit" className="admin-btn admin-btn--primary">Ekle</button>
                      </div>
                    </div>
                  </form>
                )}

                <div className="mgmt-form-group">
                  <select 
                    className="mgmt-input" 
                    value={selectedPeriod?.id || ''} 
                    onChange={handlePeriodChange}
                  >
                    {periods.length === 0 && <option value="">Dönem Bulunmuyor</option>}
                    {periods.map(p => (
                      <option key={p.id} value={p.id}>
                        {p.name} - {formatCurrency(p.amount)}
                      </option>
                    ))}
                  </select>
                </div>
              </Panel>

              {selectedPeriod && (
                <Panel 
                  title={`${selectedPeriod.name} Ödemeleri`} 
                  padding="0"
                  style={{ marginTop: 24 }}
                  headerAction={
                    <button 
                      className="admin-btn admin-btn--primary admin-btn--sm"
                      onClick={() => setShowAddPayment(!showAddPayment)}
                    >
                      <Plus size={16} /> Ödeme Gir
                    </button>
                  }
                >
                  {showAddPayment && (
                    <div style={{ padding: 20, borderBottom: '1px solid var(--admin-border)', background: 'rgba(0, 0, 0, 0.15)' }}>
                      <form action={actions.storePayment} method="POST" className="mgmt-form">
                        <input type="hidden" name="csrf_token" value={csrfToken} />
                        <input type="hidden" name="period_id" value={selectedPeriod.id} />
                        
                        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(150px, 1fr))', gap: 16 }}>
                          <div className="mgmt-form-group">
                            <label className="mgmt-label">Üye ID</label>
                            <input type="number" name="user_id" className="mgmt-input" required value={paymentForm.user_id} onChange={e => setPaymentForm({...paymentForm, user_id: e.target.value})} />
                          </div>
                          <div className="mgmt-form-group">
                            <label className="mgmt-label">Tutar (TL)</label>
                            <input type="number" step="0.01" name="amount" className="mgmt-input" required value={paymentForm.amount} onChange={e => setPaymentForm({...paymentForm, amount: e.target.value})} />
                          </div>
                          <div className="mgmt-form-group">
                            <label className="mgmt-label">Not (Opsiyonel)</label>
                            <input type="text" name="note" className="mgmt-input" value={paymentForm.note} onChange={e => setPaymentForm({...paymentForm, note: e.target.value})} />
                          </div>
                          <div className="mgmt-form-actions" style={{ alignItems: 'flex-end' }}>
                            <button type="submit" className="admin-btn admin-btn--primary" style={{ width: '100%' }}>Kaydet</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  )}

                  <div className="dues-table-wrap">
                    <table className="admin-table">
                      <thead>
                        <tr>
                          <th>Üye</th>
                          <th>Tarih</th>
                          <th>Tutar</th>
                          <th>Not</th>
                          <th style={{ width: 60 }}>Sil</th>
                        </tr>
                      </thead>
                      <tbody>
                        {payments.length === 0 ? (
                          <tr><td colSpan="5" className="admin-table-empty">Henüz ödeme yok.</td></tr>
                        ) : (
                          payments.map(pay => (
                            <tr key={pay.id}>
                              <td>
                                <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                                  <UserCircle size={16} className="text-muted" />
                                  <span style={{ fontWeight: 500 }}>{pay.user_name}</span>
                                </div>
                              </td>
                              <td>{formatDate(pay.payment_date || pay.created_at)}</td>
                              <td>
                                <span className="dues-amount">{formatCurrency(pay.amount)}</span>
                              </td>
                              <td className="text-muted" style={{ fontSize: '0.85rem' }}>{pay.note || '-'}</td>
                              <td>
                                <form action={actions.deletePayment} method="POST" onSubmit={e => {
                                  if(!window.confirm('Bu ödemeyi silmek istediğinize emin misiniz?')) e.preventDefault();
                                }}>
                                  <input type="hidden" name="csrf_token" value={csrfToken} />
                                  <input type="hidden" name="id" value={pay.id} />
                                  <button type="submit" className="admin-icon-btn text-danger">
                                    <Trash2 size={16} />
                                  </button>
                                </form>
                              </td>
                            </tr>
                          ))
                        )}
                      </tbody>
                    </table>
                  </div>
                </Panel>
              )}
            </>
          ) : (
            // Member View
            <Panel title="Aidat Ödemelerim" padding="0">
              <div className="dues-table-wrap">
                <table className="admin-table">
                  <thead>
                    <tr>
                      <th>Dönem</th>
                      <th>Tarih</th>
                      <th>Tutar</th>
                      <th>Not</th>
                    </tr>
                  </thead>
                  <tbody>
                    {myPayments.length === 0 ? (
                      <tr><td colSpan="4" className="admin-table-empty">Henüz ödemeniz bulunmuyor.</td></tr>
                    ) : (
                      myPayments.map(pay => (
                        <tr key={pay.id}>
                          <td><strong style={{ color: 'var(--admin-text)' }}>{pay.period_name}</strong></td>
                          <td>{formatDate(pay.payment_date || pay.created_at)}</td>
                          <td><span className="dues-amount">{formatCurrency(pay.amount)}</span></td>
                          <td className="text-muted" style={{ fontSize: '0.85rem' }}>{pay.note || '-'}</td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
              </div>
            </Panel>
          )}
        </div>

        {/* ── Sidebar ── */}
        <div className="dues-side-col">
          <div className="dues-side-card">
            <div className="dues-side-icon">
              <CreditCard size={24} />
            </div>
            <div className="dues-side-title">Aidat Sistemi</div>
            <div className="dues-side-desc">
              Bu departmandaki aidat dönemlerini ve üye ödemelerini takip edebilirsiniz. Ödemeler finans modülüne otomatik olarak yansır.
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
