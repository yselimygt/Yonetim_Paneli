<style>
/* Glassmorphism & Premium UI Styles (NO BOOTSTRAP) */
.finance-wrapper {
    --glass-bg: var(--card-alt);
    --glass-border: var(--border);
    --glow-success: rgba(34, 197, 94, 0.2);
    --glow-danger: rgba(239, 68, 68, 0.2);
    --glow-primary: rgba(59, 130, 246, 0.2);
    font-family: 'Inter', sans-serif;
}

/* Flex Utilities Replacement */
.flex-row { display: flex; align-items: center; }
.justify-between { justify-content: space-between; }
.justify-end { justify-content: flex-end; }
.align-start { align-items: flex-start; }
.align-center { align-items: center; }
.gap-2 { gap: 8px; }
.gap-3 { gap: 16px; }
.gap-4 { gap: 24px; }
.w-100 { width: 100%; }
.mb-1 { margin-bottom: 4px; }
.mb-2 { margin-bottom: 8px; }
.mb-3 { margin-bottom: 16px; }
.mb-4 { margin-bottom: 24px; }
.mb-5 { margin-bottom: 32px; }
.ms-2 { margin-left: 8px; }
.ms-3 { margin-left: 16px; }
.me-2 { margin-right: 8px; }
.p-0 { padding: 0; }
.p-3 { padding: 16px; }
.p-4 { padding: 24px; }
.p-5 { padding: 32px; }
.text-center { text-align: center; }
.text-end { text-align: right; }
.text-uppercase { text-transform: uppercase; }
.fw-medium { font-weight: 500; }
.fw-semibold { font-weight: 600; }
.fw-bold { font-weight: 700; }

.glass-card {
    background: var(--glass-bg);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    overflow: hidden;
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    padding: 24px;
}

.glass-card:hover { transform: translateY(-2px); }

.glass-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
}

/* Grid for Stats */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-bottom: 32px;
}

/* Stat Cards Glows */
.stat-card-success:hover { box-shadow: 0 8px 32px 0 var(--glow-success); border-color: rgba(34, 197, 94, 0.3); }
.stat-card-danger:hover { box-shadow: 0 8px 32px 0 var(--glow-danger); border-color: rgba(239, 68, 68, 0.3); }
.stat-card-primary:hover { box-shadow: 0 8px 32px 0 var(--glow-primary); border-color: rgba(59, 130, 246, 0.3); }
.stat-card-warning:hover { box-shadow: 0 8px 32px 0 rgba(245, 158, 11, 0.2); border-color: rgba(245, 158, 11, 0.3); }

/* Decorative Icons */
.stat-icon {
    position: absolute;
    right: -10px;
    bottom: -15px;
    opacity: 0.1;
    transform: rotate(-15deg);
    transition: transform 0.3s ease, opacity 0.3s ease;
}
.glass-card:hover .stat-icon {
    transform: rotate(0) scale(1.1);
    opacity: 0.2;
}

/* Buttons */
.btn-glass {
    backdrop-filter: blur(4px);
    border-radius: 8px;
    font-weight: 500;
    letter-spacing: 0.3px;
    transition: all 0.2s ease;
    padding: 10px 16px;
    display: inline-flex;
    align-items: center;
    border: none;
    cursor: pointer;
    font-family: inherit;
    font-size: 13px;
    text-decoration: none;
}
.btn-glass-success {
    background: rgba(34, 197, 94, 0.15);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #4ade80;
}
.btn-glass-success:hover { background: rgba(34, 197, 94, 0.25); box-shadow: 0 0 15px var(--glow-success); color: var(--bg); }
.btn-glass-danger {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #f87171;
}
.btn-glass-danger:hover { background: rgba(239, 68, 68, 0.25); box-shadow: 0 0 15px var(--glow-danger); color: var(--bg); }
.btn-glass-danger-text { background: transparent; border: none; color: #f87171; opacity: 0.7; padding: 4px; cursor: pointer; }
.btn-glass-danger-text:hover { opacity: 1; }

/* Table Enhancements */
.glass-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
    margin-top: -8px;
}
.glass-table th {
    color: var(--muted);
    font-weight: 500;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 12px 20px;
    border: none;
    text-align: left;
}
.glass-table td {
    background: var(--card-alt);
    padding: 16px 20px;
    vertical-align: middle;
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    transition: background 0.2s;
}
.glass-table td:first-child { border-left: 1px solid var(--border); border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
.glass-table td:last-child { border-right: 1px solid var(--border); border-top-right-radius: 12px; border-bottom-right-radius: 12px; }
.glass-table tr:hover td { background: var(--card); }

/* Badges */
.badge-neon {
    padding: 6px 10px;
    border-radius: 6px;
    font-weight: 500;
    font-size: 11px;
    letter-spacing: 0.5px;
    display: inline-block;
}
.badge-neon-success { background: rgba(34, 197, 94, 0.1); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2); }
.badge-neon-danger { background: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); }
.badge-neon-info { background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2); }
.btn-glass-primary-text { background: transparent; border: none; color: #60a5fa; cursor: pointer; padding: 4px; border-radius: 6px; transition: background 0.2s; }
.btn-glass-primary-text:hover { background: rgba(59, 130, 246, 0.1); }

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
}
.modal-glass {
    background: var(--card);
    backdrop-filter: blur(20px);
    border: 1px solid var(--border);
    border-radius: 20px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    animation: modalSlide 0.3s ease-out;
}
@keyframes modalSlide {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-title { font-size: 16px; font-weight: 600; color: var(--text); margin: 0; display: flex; align-items: center; }
.btn-close-modal { background: transparent; border: none; color: var(--muted); cursor: pointer; font-size: 20px; transition: color 0.2s; }
.btn-close-modal:hover { color: var(--text); }
.modal-body { padding: 24px; }
.modal-footer { padding: 20px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 12px; }

/* Custom Inputs for Modal */
.modal-glass .form-group { margin-bottom: 20px; }
.modal-glass .form-label { display: block; color: var(--muted); font-size: 11px; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; margin-bottom: 8px; }
.modal-glass .form-control {
    width: 100%;
    background: var(--input-bg);
    border: 1px solid var(--border);
    color: var(--text);
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 14px;
    outline: none;
    transition: all 0.2s;
}
.modal-glass .form-control:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 2px rgba(0, 188, 212, 0.1);
}
.input-group { display: flex; }
.input-group .input-prefix { background: var(--input-bg); border: 1px solid var(--border); border-right: none; border-radius: 8px 0 0 8px; padding: 12px 16px; font-weight: bold; }
.input-group .form-control { border-radius: 0 8px 8px 0; }
select.form-control option { background: var(--bg); color: var(--text); }
</style>

<div class="finance-wrapper">
    <!-- Header Area -->
    <div class="flex-row justify-between align-start mb-5">
        <div>
            <div class="flex-row align-center mb-2">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(34, 197, 94, 0.1); display: flex; align-items: center; justify-content: center; color: #4ade80; margin-right: 16px;">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                </div>
                <h3 style="margin:0; font-size:24px; font-weight:700; color:var(--text); letter-spacing: -0.5px;">Finans Paneli</h3>
            </div>
            <p style="color:var(--muted); font-size: 14px; margin: 0 0 0 56px;">
                <span style="color:var(--text);"><?= View::escape($currentDept['name']) ?></span> departmanı detaylı gelir gider analizi
            </p>
        </div>

        <?php if ($userRole === 'director' || Auth::isSuperAdmin()): ?>
        <div class="flex-row gap-3">
            <button type="button" class="btn-glass btn-glass-danger" onclick="openModal('expenseModal')">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none" class="me-2"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>
                Gider Ekle
            </button>
            <button type="button" class="btn-glass btn-glass-success" onclick="openModal('incomeModal')">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none" class="me-2"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
                Gelir Ekle
            </button>
        </div>
        <?php endif; ?>
    </div>

    <!-- Summary Cards -->
    <div class="stats-grid">
        <!-- Toplam Gelir -->
        <div class="glass-card stat-card-success">
            <svg class="stat-icon" viewBox="0 0 24 24" width="120" height="120" stroke="#4ade80" stroke-width="1" fill="none"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
            <div style="position:relative; z-index:1;">
                <p class="text-uppercase fw-semibold mb-2" style="color: #4ade80; font-size: 12px; letter-spacing: 1px; margin:0 0 8px 0;">Toplam Gelir</p>
                <h2 style="margin:0; font-size: 32px; font-weight:700; letter-spacing: -1px; color:var(--text);">
                    <span style="color: var(--muted); font-size: 24px;">₺</span><?= number_format($summary['income'], 2, ',', '.') ?>
                </h2>
            </div>
        </div>
        <!-- Toplam Gider -->
        <div class="glass-card stat-card-danger">
            <svg class="stat-icon" viewBox="0 0 24 24" width="120" height="120" stroke="#f87171" stroke-width="1" fill="none"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>
            <div style="position:relative; z-index:1;">
                <p class="text-uppercase fw-semibold mb-2" style="color: #f87171; font-size: 12px; letter-spacing: 1px; margin:0 0 8px 0;">Toplam Gider</p>
                <h2 style="margin:0; font-size: 32px; font-weight:700; letter-spacing: -1px; color:var(--text);">
                    <span style="color: var(--muted); font-size: 24px;">₺</span><?= number_format($summary['expense'], 2, ',', '.') ?>
                </h2>
            </div>
        </div>
        <!-- Mevcut Bakiye -->
        <div class="glass-card <?= $summary['balance'] >= 0 ? 'stat-card-primary' : 'stat-card-warning' ?>">
            <svg class="stat-icon" viewBox="0 0 24 24" width="120" height="120" stroke="<?= $summary['balance'] >= 0 ? '#60a5fa' : '#fbbf24' ?>" stroke-width="1" fill="none"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><line x1="6" y1="8" x2="6.01" y2="8"></line><line x1="10" y1="8" x2="10.01" y2="8"></line></svg>
            <div style="position:relative; z-index:1;">
                <p class="text-uppercase fw-semibold mb-2" style="color: <?= $summary['balance'] >= 0 ? '#60a5fa' : '#fbbf24' ?>; font-size: 12px; letter-spacing: 1px; margin:0 0 8px 0;">Mevcut Bakiye</p>
                <h2 style="margin:0; font-size: 32px; font-weight:700; letter-spacing: -1px; color:var(--text);">
                    <span style="color: var(--muted); font-size: 24px;">₺</span><?= number_format($summary['balance'], 2, ',', '.') ?>
                </h2>
            </div>
        </div>
    </div>

    <!-- Transactions List -->
    <div>
        <h5 class="fw-semibold mb-4" style="color: var(--text); font-size:16px;">Son İşlemler</h5>
        
        <?php if (empty($transactions)): ?>
            <div class="glass-card text-center" style="padding: 40px 20px;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--border); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <svg viewBox="0 0 24 24" width="28" height="28" stroke="var(--muted)" stroke-width="2" fill="none"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                </div>
                <h5 style="color:var(--text); font-size:15px; margin:0 0 4px 0;">Kayıt Bulunamadı</h5>
                <p style="color:var(--muted); font-size:13px; margin:0;">Henüz bir finansal işlem gerçekleştirilmemiş.</p>
            </div>
        <?php else: ?>
            <div style="overflow-x:auto;">
                <table class="glass-table">
                    <thead>
                        <tr>
                            <th style="width: 15%">Tarih</th>
                            <th style="width: 10%">Tip</th>
                            <th style="width: 20%">Kategori</th>
                            <th style="width: 35%">Açıklama</th>
                            <th class="text-end" style="width: 15%; text-align:right;">Tutar</th>
                            <?php if ($userRole === 'director' || Auth::isSuperAdmin()): ?>
                            <th class="text-end" style="width: 5%"></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $tx): ?>
                        <tr>
                            <td>
                                <div class="flex-row align-center" style="color:var(--muted);">
                                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" class="me-2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    <span style="font-size: 13px; font-weight: 500;"><?= date('d.m.Y', strtotime($tx['transaction_date'])) ?></span>
                                </div>
                            </td>
                            <td>
                                <?php if ($tx['type'] === 'income'): ?>
                                    <span class="badge-neon badge-neon-success">GELİR</span>
                                <?php else: ?>
                                    <span class="badge-neon badge-neon-danger">GİDER</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="color:var(--text); font-size: 14px; font-weight:500;"><?= View::escape($tx['category']) ?></span>
                            </td>
                            <td>
                                <div class="flex-row align-center">
                                    <span style="color:var(--muted); font-size: 13px;"><?= View::escape($tx['description']) ?></span>
                                    <?php if ($tx['related_due_id']): ?>
                                        <span class="badge-neon badge-neon-info ms-3">AİDAT</span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($tx['proxy_user_id'])): ?>
                                    <div style="font-size: 11px; color:var(--muted); margin-top: 4px;">
                                        <svg viewBox="0 0 24 24" width="10" height="10" stroke="currentColor" stroke-width="2" fill="none" style="margin-right:2px; display:inline-block; vertical-align:middle;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                        Vekaleten: <?= View::escape($tx['proxy_user_name']) ?>
                                        <?php if ($tx['proxy_status'] === 'pending'): ?>
                                            <span style="color:#fbbf24; margin-left: 5px;">(Bekliyor)</span>
                                        <?php else: ?>
                                            <span style="color:#4ade80; margin-left: 5px;">(Teslim Alındı)</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:right;">
                                <span style="font-weight:700; font-size: 15px; color: <?= $tx['type'] === 'income' ? '#4ade80' : '#f87171' ?>;">
                                    <?= $tx['type'] === 'income' ? '+' : '-' ?>₺<?= number_format($tx['amount'], 2, ',', '.') ?>
                                </span>
                            </td>
                            <?php if ($userRole === 'director' || Auth::isSuperAdmin()): ?>
                            <td style="text-align:right; width: 140px; vertical-align: top;">
                                <?php if (!$tx['related_due_id']): ?>
                                <div style="display:flex; flex-direction:column; gap:6px; align-items:flex-end;">
                                    <?php if (!empty($tx['proxy_user_id']) && $tx['proxy_status'] === 'pending'): ?>
                                    <form action="<?= BASE_URL ?>/panel/<?= $currentDept['slug'] ?>/finance/<?= $tx['id'] ?>/receive" method="POST" style="margin:0; width:100%;" onsubmit="return confirm('Teslim aldığınızı onaylıyor musunuz?');">
                                        <?= '<input type="hidden" name="csrf_token" value="' . ($_SESSION['csrf_token'] ?? '') . '">' ?>
                                        <button type="submit" class="btn-glass-primary-text" style="display:flex; align-items:center; gap:8px; padding:6px 10px; font-size:12px; width:100%; justify-content:flex-start;">
                                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                            Teslim Alındı
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    <button type="button" class="btn-glass-primary-text" style="display:flex; align-items:center; gap:8px; padding:6px 10px; font-size:12px; width:100%; justify-content:flex-start;" onclick="openEditModal(<?= htmlspecialchars(json_encode([
                                        'id' => $tx['id'],
                                        'type' => $tx['type'],
                                        'amount' => $tx['amount'],
                                        'category' => $tx['category'],
                                        'transaction_date' => $tx['transaction_date'],
                                        'proxy_user_id' => $tx['proxy_user_id'],
                                        'description' => $tx['description']
                                    ])) ?>)">
                                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        Düzenle
                                    </button>
                                    <form action="<?= BASE_URL ?>/panel/<?= $currentDept['slug'] ?>/finance/<?= $tx['id'] ?>/delete" method="POST" style="margin:0; width:100%;" onsubmit="return confirm('Bu işlemi silmek istediğinize emin misiniz?');">
                                        <?= '<input type="hidden" name="csrf_token" value="' . ($_SESSION['csrf_token'] ?? '') . '">' ?>
                                        <button type="submit" class="btn-glass-danger-text" style="display:flex; align-items:center; gap:8px; padding:6px 10px; font-size:12px; width:100%; justify-content:flex-start;">
                                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            Sil
                                        </button>
                                    </form>
                                </div>
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($userRole === 'director' || Auth::isSuperAdmin()): ?>
<!-- Income Modal -->
<div class="modal-overlay" id="incomeModalOverlay">
    <div class="modal-glass">
        <form action="<?= BASE_URL ?>/panel/<?= $currentDept['slug'] ?>/finance/store" method="POST">
            <?= '<input type="hidden" name="csrf_token" value="' . ($_SESSION['csrf_token'] ?? '') . '">' ?>
            <input type="hidden" name="type" value="income">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#4ade80; margin-right:12px; box-shadow: 0 0 10px #4ade80;"></span>
                    Yeni Gelir İşlemi
                </h5>
                <button type="button" class="btn-close-modal" onclick="closeModal('incomeModalOverlay')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Tutar (₺)</label>
                    <div class="input-group">
                        <span class="input-prefix" style="color:#4ade80;">₺</span>
                        <input type="number" step="0.01" min="0.01" name="amount" class="form-control" style="font-size: 18px; font-weight: 500;" placeholder="0.00" required>
                    </div>
                </div>
                <div class="flex-row gap-3">
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-control" required>
                            <option value="Bağış">Bağış</option>
                            <option value="Sponsorluk">Sponsorluk</option>
                            <option value="Etkinlik Geliri">Etkinlik Geliri</option>
                            <option value="Diğer">Diğer</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">İşlem Tarihi</label>
                        <input type="date" name="transaction_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Vekaleten Teslim Alan (İsteğe Bağlı)</label>
                    <select name="proxy_user_id" class="form-control">
                        <option value="">Seçiniz...</option>
                        <?php foreach($members as $m): ?>
                            <option value="<?= $m['user_id'] ?>"><?= View::escape($m['user_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Açıklama / Detay</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="İşlem detaylarını buraya girebilirsiniz..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-glass" style="color: var(--muted); background: transparent;" onclick="closeModal('incomeModalOverlay')">İptal Et</button>
                <button type="submit" class="btn-glass btn-glass-success">İşlemi Kaydet</button>
            </div>
        </form>
    </div>
</div>

<!-- Expense Modal -->
<div class="modal-overlay" id="expenseModalOverlay">
    <div class="modal-glass">
        <form action="<?= BASE_URL ?>/panel/<?= $currentDept['slug'] ?>/finance/store" method="POST">
            <?= '<input type="hidden" name="csrf_token" value="' . ($_SESSION['csrf_token'] ?? '') . '">' ?>
            <input type="hidden" name="type" value="expense">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#f87171; margin-right:12px; box-shadow: 0 0 10px #f87171;"></span>
                    Yeni Gider İşlemi
                </h5>
                <button type="button" class="btn-close-modal" onclick="closeModal('expenseModalOverlay')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Tutar (₺)</label>
                    <div class="input-group">
                        <span class="input-prefix" style="color:#f87171;">₺</span>
                        <input type="number" step="0.01" min="0.01" name="amount" class="form-control" style="font-size: 18px; font-weight: 500;" placeholder="0.00" required>
                    </div>
                </div>
                <div class="flex-row gap-3">
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-control" required>
                            <option value="Yazılım/Sunucu">Yazılım/Sunucu</option>
                            <option value="Etkinlik Gideri">Etkinlik Gideri</option>
                            <option value="Kırtasiye/Malzeme">Kırtasiye/Malzeme</option>
                            <option value="Reklam/Pazarlama">Reklam/Pazarlama</option>
                            <option value="Diğer">Diğer</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">İşlem Tarihi</label>
                        <input type="date" name="transaction_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Vekaleten Teslim Alan (İsteğe Bağlı)</label>
                    <select name="proxy_user_id" class="form-control">
                        <option value="">Seçiniz...</option>
                        <?php foreach($members as $m): ?>
                            <option value="<?= $m['user_id'] ?>"><?= View::escape($m['user_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Açıklama / Detay</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="İşlem detaylarını buraya girebilirsiniz..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-glass" style="color: var(--muted); background: transparent;" onclick="closeModal('expenseModalOverlay')">İptal Et</button>
                <button type="submit" class="btn-glass btn-glass-danger">Gideri Kaydet</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModalOverlay">
    <div class="modal-glass">
        <form id="editForm" action="" method="POST">
            <?= '<input type="hidden" name="csrf_token" value="' . ($_SESSION['csrf_token'] ?? '') . '">' ?>
            <div class="modal-header">
                <h5 class="modal-title">
                    <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#60a5fa; margin-right:12px; box-shadow: 0 0 10px #60a5fa;"></span>
                    İşlemi Düzenle
                </h5>
                <button type="button" class="btn-close-modal" onclick="closeModal('editModalOverlay')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Tip</label>
                    <select name="type" id="edit_type" class="form-control" required>
                        <option value="income">Gelir</option>
                        <option value="expense">Gider</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tutar (₺)</label>
                    <div class="input-group">
                        <span class="input-prefix" style="color:#60a5fa;">₺</span>
                        <input type="number" step="0.01" min="0.01" id="edit_amount" name="amount" class="form-control" style="font-size: 18px; font-weight: 500;" required>
                    </div>
                </div>
                <div class="flex-row gap-3">
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Kategori</label>
                        <select name="category" id="edit_category" class="form-control" required>
                            <option value="Bağış">Bağış</option>
                            <option value="Sponsorluk">Sponsorluk</option>
                            <option value="Etkinlik Geliri">Etkinlik Geliri</option>
                            <option value="Yazılım/Sunucu">Yazılım/Sunucu</option>
                            <option value="Etkinlik Gideri">Etkinlik Gideri</option>
                            <option value="Kırtasiye/Malzeme">Kırtasiye/Malzeme</option>
                            <option value="Reklam/Pazarlama">Reklam/Pazarlama</option>
                            <option value="Diğer">Diğer</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">İşlem Tarihi</label>
                        <input type="date" name="transaction_date" id="edit_date" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Vekaleten Teslim Alan (İsteğe Bağlı)</label>
                    <select name="proxy_user_id" id="edit_proxy" class="form-control">
                        <option value="">Seçiniz...</option>
                        <?php foreach($members as $m): ?>
                            <option value="<?= $m['user_id'] ?>"><?= View::escape($m['user_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Açıklama / Detay</label>
                    <textarea name="description" id="edit_desc" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-glass" style="color: var(--muted); background: transparent;" onclick="closeModal('editModalOverlay')">İptal Et</button>
                <button type="submit" class="btn-glass btn-glass-success" style="background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2);">Değişiklikleri Kaydet</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
function openModal(id) {
    document.getElementById(id + 'Overlay').style.display = 'flex';
}
function openEditModal(data) {
    document.getElementById('editForm').action = '<?= BASE_URL ?>/panel/<?= $currentDept['slug'] ?>/finance/' + data.id + '/update';
    document.getElementById('edit_type').value = data.type;
    document.getElementById('edit_amount').value = data.amount;
    document.getElementById('edit_category').value = data.category;
    document.getElementById('edit_date').value = data.transaction_date;
    document.getElementById('edit_proxy').value = data.proxy_user_id || '';
    document.getElementById('edit_desc').value = data.description || '';
    openModal('editModal');
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.className === 'modal-overlay') {
        event.target.style.display = 'none';
    }
}
</script>
