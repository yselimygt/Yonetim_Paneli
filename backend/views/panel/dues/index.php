<style>
/* Glassmorphism & Premium UI Styles (NO BOOTSTRAP) */
.dues-wrapper {
    --glass-bg: var(--card-alt);
    --glass-border: var(--border);
    --glow-primary: rgba(59, 130, 246, 0.2);
    --glow-success: rgba(34, 197, 94, 0.2);
    --glow-danger: rgba(239, 68, 68, 0.2);
    font-family: 'Inter', sans-serif;
}

/* Flex Utilities Replacement */
.flex-row { display: flex; align-items: center; }
.flex-col { display: flex; flex-direction: column; }
.justify-between { justify-content: space-between; }
.justify-center { justify-content: center; }
.align-start { align-items: flex-start; }
.align-center { align-items: center; }
.flex-1 { flex: 1; }
.gap-2 { gap: 8px; }
.gap-3 { gap: 16px; }
.gap-4 { gap: 24px; }
.w-100 { width: 100%; }
.mb-1 { margin-bottom: 4px; }
.mb-2 { margin-bottom: 8px; }
.mb-3 { margin-bottom: 16px; }
.mb-4 { margin-bottom: 24px; }
.mb-5 { margin-bottom: 32px; }
.mt-2 { margin-top: 8px; }
.me-2 { margin-right: 8px; }
.me-3 { margin-right: 16px; }
.ms-3 { margin-left: 16px; }
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
}

.glass-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
}

/* Layout Grid for Dues Page */
.dues-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 24px;
    align-items: start;
}

/* Sidebar List */
.glass-list {
    background: transparent;
    overflow-y: auto;
    max-height: 500px;
}
.glass-list-item {
    display: block;
    background: var(--card-alt);
    border: none;
    border-bottom: 1px solid var(--border);
    color: var(--text);
    transition: all 0.2s ease;
    padding: 16px 20px;
    text-decoration: none;
}
.glass-list-item:hover {
    background: var(--card);
    color: var(--text);
}
.glass-list-item.active {
    background: rgba(59, 130, 246, 0.15);
    border-left: 4px solid #3b82f6;
    color: var(--text);
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
.btn-glass-primary {
    background: rgba(59, 130, 246, 0.15);
    border: 1px solid rgba(59, 130, 246, 0.3);
    color: #60a5fa;
}
.btn-glass-primary:hover { background: rgba(59, 130, 246, 0.25); box-shadow: 0 0 15px var(--glow-primary); color: var(--bg); }
.btn-glass-danger {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #f87171;
}
.btn-glass-danger:hover { background: rgba(239, 68, 68, 0.25); box-shadow: 0 0 15px var(--glow-danger); color: var(--bg); }
.btn-glass-success {
    background: rgba(34, 197, 94, 0.15);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #4ade80;
}
.btn-glass-success:hover { background: rgba(34, 197, 94, 0.25); box-shadow: 0 0 15px var(--glow-success); color: var(--bg); }
.btn-glass-warning {
    background: rgba(245, 158, 11, 0.15);
    border: 1px solid rgba(245, 158, 11, 0.3);
    color: #fbbf24;
}
.btn-glass-warning:hover { background: rgba(245, 158, 11, 0.25); box-shadow: 0 0 15px rgba(245, 158, 11, 0.2); color: var(--bg); }
.btn-sm { padding: 6px 12px; font-size: 11px; }

/* Custom Progress Bar */
.neon-progress-bg {
    background: rgba(255,255,255,0.05);
    border-radius: 10px;
    height: 8px;
    overflow: hidden;
}
.neon-progress-bar {
    background: linear-gradient(90deg, #3b82f6, #60a5fa);
    box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
    height: 100%;
    border-radius: 10px;
    transition: width 1s ease-in-out;
}

/* Table Enhancements */
.glass-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
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
.modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
.modal-title { font-size: 16px; font-weight: 600; color: var(--text); margin: 0; display: flex; align-items: center; }
.btn-close-modal { background: transparent; border: none; color: var(--muted); cursor: pointer; font-size: 20px; transition: color 0.2s; }
.btn-close-modal:hover { color: var(--text); }
.modal-body { padding: 24px; }
.modal-footer { padding: 20px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 12px; }

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
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}
.input-group { display: flex; }
.input-group .input-prefix { background: var(--input-bg); border: 1px solid var(--border); border-right: none; border-radius: 8px 0 0 8px; padding: 12px 16px; font-weight: bold; }
.input-group .form-control { border-radius: 0 8px 8px 0; }
.form-text { font-size: 12px; color: var(--muted); margin-top: 8px; }

/* Grid for Stats in Right Panel */
.stats-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    text-align: center;
    margin-bottom: 40px;
}
.stats-row .divider {
    position: absolute; width: 1px; background: rgba(255,255,255,0.05); top: 10%; bottom: 10%;
}

@media (max-width: 900px) {
    .dues-wrapper > .flex-row.justify-between.align-start.mb-5 {
        flex-direction: column;
        align-items: stretch;
        gap: 14px;
        margin-bottom: 20px;
    }

    .dues-wrapper h3 {
        font-size: 19px !important;
        letter-spacing: -0.2px !important;
    }

    .dues-wrapper > .flex-row.justify-between.align-start.mb-5 p {
        margin-left: 0 !important;
        font-size: 12px !important;
        line-height: 1.4;
    }

    .dues-wrapper > .flex-row.justify-between.align-start.mb-5 .btn-glass {
        width: 100%;
        justify-content: center;
        min-height: 38px;
        padding: 9px 12px;
        font-size: 12px;
    }

    .dues-layout {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .dues-layout > .glass-card:first-child {
        height: auto !important;
        border-radius: 14px;
    }

    .dues-layout > .glass-card:first-child > div:first-child {
        padding: 14px 16px !important;
    }

    .glass-list {
        display: flex;
        gap: 8px;
        max-height: none;
        overflow-x: auto;
        padding: 10px;
        scroll-snap-type: x mandatory;
    }

    .glass-list::-webkit-scrollbar {
        display: none;
    }

    .glass-list-item {
        flex: 0 0 78%;
        max-width: 260px;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 12px;
        scroll-snap-align: start;
    }

    .glass-list-item.active {
        border-left-width: 1px;
        box-shadow: inset 0 3px 0 #3b82f6;
    }

    .dues-layout > div:last-child > .glass-card {
        border-radius: 14px;
    }

    .dues-layout .glass-card.p-5 {
        padding: 16px !important;
    }

    .dues-layout .glass-card.p-5 > .flex-row.justify-between {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
        margin-bottom: 18px !important;
    }

    .dues-layout .glass-card.p-5 h4 {
        font-size: 18px !important;
    }

    .dues-layout .glass-card.p-5 form .btn-glass {
        width: 100%;
        justify-content: center;
    }

    .stats-row {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
        margin-bottom: 22px;
    }

    .stats-row > div {
        min-width: 0;
        padding: 10px 6px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: rgba(255,255,255,0.02);
    }

    .stats-row .divider {
        display: none;
    }

    .stats-row p {
        font-size: 8px !important;
        line-height: 1.2;
        margin-bottom: 6px !important;
    }

    .stats-row h3 {
        font-size: clamp(13px, 3.6vw, 18px) !important;
        line-height: 1.15;
        word-break: break-word;
    }

    .glass-table {
        display: block;
        border-spacing: 0;
    }

    .glass-table thead {
        display: none;
    }

    .glass-table tbody,
    .glass-table tr,
    .glass-table td {
        display: block;
        width: 100%;
    }

    .glass-table tr {
        background: var(--card-alt);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 10px;
        margin-bottom: 10px;
    }

    .glass-table tr:hover td {
        background: transparent;
    }

    .glass-table td,
    .glass-table td:first-child,
    .glass-table td:last-child {
        border: 0;
        border-radius: 0;
        background: transparent;
        padding: 7px 2px;
    }

    .glass-table td {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        font-size: 12px;
    }

    .glass-table td::before {
        content: attr(data-label);
        color: var(--muted);
        flex: 0 0 auto;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: .6px;
        text-transform: uppercase;
    }

    .glass-table td > .flex-row {
        justify-content: flex-end;
        min-width: 0;
        text-align: right;
    }

    .glass-table td form {
        margin-left: auto;
    }

    .glass-table td .btn-glass {
        min-height: 30px;
        padding: 6px 9px;
        border-radius: 8px;
    }

    .modal-overlay {
        align-items: flex-end;
        padding: 10px;
    }

    .modal-glass {
        max-width: none;
        max-height: calc(100vh - 32px);
        border-radius: 18px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .modal-glass form {
        min-height: 0;
        display: flex;
        flex-direction: column;
    }

    .modal-header {
        padding: 16px;
    }

    .modal-title {
        font-size: 14px;
    }

    .modal-body {
        padding: 16px;
        overflow-y: auto;
    }

    .modal-glass .form-group {
        margin-bottom: 14px;
    }

    .modal-footer {
        padding: 12px 16px 16px;
        justify-content: stretch;
        gap: 8px;
    }

    .modal-footer .btn-glass {
        flex: 1;
        justify-content: center;
        min-height: 38px;
        padding: 9px 10px;
        font-size: 12px;
    }

    .dues-payment-table tr {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        grid-template-rows: auto auto;
        align-items: center;
        gap: 5px 10px;
        padding: 11px 12px;
    }

    .dues-payment-table td,
    .dues-payment-table td:first-child,
    .dues-payment-table td:last-child {
        display: block;
        width: auto;
        padding: 0;
    }

    .dues-payment-table td::before {
        display: none;
    }

    .dues-payment-table td[data-label="Üye"] {
        grid-column: 1;
        grid-row: 1 / 3;
        min-width: 0;
    }

    .dues-payment-table td[data-label="Üye"] > .flex-row {
        justify-content: flex-start;
        text-align: left;
    }

    .dues-payment-table td[data-label="Üye"] > .flex-row > div:first-child {
        display: none !important;
    }

    .dues-payment-table td[data-label="Üye"] span {
        display: block;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .dues-payment-table td[data-label="Durum"] {
        grid-column: 2;
        grid-row: 1;
        text-align: right;
    }

    .dues-payment-table td[data-label="Ödeme Tarihi"] {
        display: none;
    }

    .dues-payment-table td[data-label="İşlem"] {
        grid-column: 2;
        grid-row: 2;
        text-align: right !important;
    }

    .dues-payment-table td[data-label="İşlem"] form {
        display: block !important;
        margin: 0;
    }

    .dues-payment-table td[data-label="İşlem"] .btn-glass {
        min-width: 72px;
        min-height: 28px;
        justify-content: center;
        padding: 5px 9px;
        font-size: 10px;
    }
}
</style>

<div class="dues-wrapper">
    <!-- Header Area -->
    <div class="flex-row justify-between align-start mb-5">
        <div>
            <div class="flex-row align-center mb-2">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(59, 130, 246, 0.1); display: flex; align-items: center; justify-content: center; color: #60a5fa; margin-right: 16px;">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                </div>
                <h3 style="margin:0; font-size:24px; font-weight:700; color:var(--text); letter-spacing: -0.5px;">Aidat Takibi</h3>
            </div>
            <p style="color:var(--muted); font-size: 14px; margin: 0 0 0 56px;">
                <span style="color:var(--text);"><?= View::escape($currentDept['name']) ?></span> departmanı aidat ve ödeme yönetimi
            </p>
        </div>

        <?php if ($userRole === 'director' || Auth::isSuperAdmin()): ?>
        <div>
            <button class="btn-glass btn-glass-primary" onclick="openModal('newPeriodModalOverlay')">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none" class="me-2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Yeni Dönem Aç
            </button>
        </div>
        <?php endif; ?>
    </div>

    <div class="dues-layout">
        <!-- Dönem Seçici (Sidebar) -->
        <div class="glass-card p-0" style="height: 600px; display:flex; flex-direction:column;">
            <div style="padding: 24px; border-bottom: 1px solid var(--border);">
                <h6 style="margin:0; color:var(--text); font-weight:600; letter-spacing:0.5px; font-size:12px;">AİDAT DÖNEMLERİ</h6>
            </div>
            <div class="glass-list">
                <?php if (empty($periods)): ?>
                    <div class="p-4 text-center" style="color: rgba(255,255,255,0.5); font-size: 13px;">
                        <svg viewBox="0 0 24 24" width="32" height="32" stroke="currentColor" stroke-width="1.5" fill="none" class="mb-2" style="opacity:0.5;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <br>Henüz aidat dönemi açılmamış.
                    </div>
                <?php else: ?>
                    <?php foreach ($periods as $p): ?>
                        <a href="?period_id=<?= $p['id'] ?>" class="glass-list-item <?= ($selectedPeriod && $selectedPeriod['id'] == $p['id']) ? 'active' : '' ?>">
                            <div class="flex-row justify-between mb-1">
                                <h6 style="margin:0; font-size: 15px; font-weight:500;"><?= View::escape($p['name']) ?></h6>
                                <span style="font-weight:700; font-size: 14px;">₺<?= number_format($p['amount'], 0) ?></span>
                            </div>
                            <?php if ($userRole === 'director' || Auth::isSuperAdmin()): ?>
                            <div class="flex-row align-center mt-2">
                                <div class="neon-progress-bg flex-1 me-3" style="height: 4px;">
                                    <?php $pct = $p['total_users'] > 0 ? ($p['paid_users'] / $p['total_users']) * 100 : 0; ?>
                                    <div class="neon-progress-bar" style="width: <?= $pct ?>%; background: linear-gradient(90deg, <?= $pct==100 ? '#22c55e, #4ade80' : '#3b82f6, #60a5fa' ?>); box-shadow: none;"></div>
                                </div>
                                <span style="font-size: 11px; opacity: 0.7; white-space: nowrap;"><?= $p['paid_users'] ?> / <?= $p['total_users'] ?></span>
                            </div>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sağ Panel: İçerik -->
        <div>
            <?php if ($userRole === 'member'): ?>
                <!-- Normal Üye Görünümü -->
                <div class="glass-card mb-4">
                    <h5 class="fw-semibold mb-4" style="color:var(--text); font-size:18px;">Ödeme Geçmişim</h5>
                    <?php if (empty($myPayments)): ?>
                        <div class="p-5 text-center" style="color:var(--muted);">
                            Açılmış bir aidat dönemi bulunmuyor.
                        </div>
                    <?php else: ?>
                        <div style="overflow-x:auto;">
                            <table class="glass-table">
                                <thead>
                                    <tr>
                                        <th>Dönem</th>
                                        <th>Tutar</th>
                                        <th>Durum</th>
                                        <th>Ödeme Tarihi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($myPayments as $mp): ?>
                                    <tr>
                                        <td data-label="Dönem"><span style="color:var(--text); font-weight:500; font-size:14px;"><?= View::escape($mp['period_name']) ?></span></td>
                                        <td data-label="Tutar"><span style="color:var(--text); font-weight:700; font-size:14px;">₺<?= number_format($mp['expected_amount'], 2, ',', '.') ?></span></td>
                                        <td data-label="Durum">
                                            <?php if ($mp['status'] === 'paid'): ?>
                                                <span class="badge-neon badge-neon-success">ÖDENDİ</span>
                                            <?php else: ?>
                                                <span class="badge-neon badge-neon-danger">ÖDENMEDİ</span>
                                            <?php endif; ?>
                                        </td>
                                        <td data-label="Ödeme Tarihi" style="color:rgba(255,255,255,0.5); font-size:13px;">
                                            <?= $mp['payment_date'] ? date('d.m.Y H:i', strtotime($mp['payment_date'])) : '-' ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <!-- Yönetici Görünümü -->
                <?php if ($selectedPeriod): ?>
                    <?php 
                        $totalAmount = $selectedPeriod['total_users'] * $selectedPeriod['amount'];
                        $progress = $totalAmount > 0 ? ($selectedPeriod['collected_amount'] / $totalAmount) * 100 : 0;
                    ?>
                    <div class="glass-card p-5 mb-4">
                        <div class="flex-row justify-between align-start mb-5">
                            <div>
                                <h4 style="color:var(--text); font-weight:700; margin:0 0 4px 0; font-size:24px;"><?= View::escape($selectedPeriod['name']) ?></h4>
                                <p style="color:var(--muted); margin:0; font-size: 14px;">Aidat Dönemi Durum Özeti</p>
                            </div>
                            <form action="<?= BASE_URL ?>/panel/<?= $currentDept['slug'] ?>/dues/periods/<?= $selectedPeriod['id'] ?>/delete" method="POST" onsubmit="return confirm('Bu dönemi ve tüm ödeme kayıtlarını silmek istediğinize emin misiniz?');">
                                <?= '<input type="hidden" name="csrf_token" value="' . ($_SESSION['csrf_token'] ?? '') . '">' ?>
                                <button type="submit" class="btn-glass btn-glass-danger btn-sm text-uppercase">Dönemi Sil</button>
                            </form>
                        </div>
                        
                        <div class="stats-row">
                            <div>
                                <p style="color:var(--muted); text-transform:uppercase; font-weight:600; margin:0 0 8px 0; font-size:11px; letter-spacing: 1px;">Beklenen Toplam</p>
                                <h3 style="color:var(--text); font-weight:700; margin:0; font-size:28px;">₺<?= number_format($totalAmount, 2, ',', '.') ?></h3>
                            </div>
                            <div style="position:relative;">
                                <div class="divider" style="left:0;"></div>
                                <p style="color:#4ade80; text-transform:uppercase; font-weight:600; margin:0 0 8px 0; font-size:11px; letter-spacing: 1px; opacity: 0.8;">Toplanan</p>
                                <h3 style="color:#4ade80; font-weight:700; margin:0; font-size:28px; text-shadow: 0 0 15px rgba(34, 197, 94, 0.3);">₺<?= number_format($selectedPeriod['collected_amount'] ?: 0, 2, ',', '.') ?></h3>
                                <div class="divider" style="right:0;"></div>
                            </div>
                            <div>
                                <p style="color:#f87171; text-transform:uppercase; font-weight:600; margin:0 0 8px 0; font-size:11px; letter-spacing: 1px; opacity: 0.8;">Kalan Alacak</p>
                                <h3 style="color:#f87171; font-weight:700; margin:0; font-size:28px; text-shadow: 0 0 15px rgba(239, 68, 68, 0.3);">₺<?= number_format($totalAmount - $selectedPeriod['collected_amount'], 2, ',', '.') ?></h3>
                            </div>
                        </div>

                        <div>
                            <div class="flex-row justify-between mb-2">
                                <span style="color:var(--muted); font-size: 12px; font-weight: 500;">Tahsilat Oranı</span>
                                <span style="color:var(--text); font-weight: 700; font-size: 12px;"><?= number_format($progress, 1) ?>%</span>
                            </div>
                            <div class="neon-progress-bg">
                                <div class="neon-progress-bar" style="width: <?= $progress ?>%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card">
                        <h5 class="fw-semibold mb-4" style="color:var(--text); font-size:16px;">Üye Ödeme Durumları</h5>
                        <div style="overflow-x:auto;">
                            <table class="glass-table dues-payment-table">
                                <thead>
                                    <tr>
                                        <th style="width: 35%">Üye Adı</th>
                                        <th style="width: 20%">Durum</th>
                                        <th style="width: 25%">Ödeme Tarihi</th>
                                        <th class="text-end" style="width: 20%; text-align:right;">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($payments as $pay): ?>
                                    <tr>
                                        <td data-label="Üye">
                                            <div class="flex-row align-center">
                                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--input-bg); color: var(--text); display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 13px; margin-right: 12px;">
                                                    <?= mb_substr(View::escape($pay['user_name']), 0, 1) ?>
                                                </div>
                                                <span style="color:var(--text); font-weight:500; font-size:14px;"><?= View::escape($pay['user_name']) ?></span>
                                            </div>
                                        </td>
                                        <td data-label="Durum">
                                            <?php if ($pay['status'] === 'paid'): ?>
                                                <span class="badge-neon badge-neon-success">ÖDENDİ</span>
                                            <?php else: ?>
                                                <span class="badge-neon badge-neon-danger">BEKLİYOR</span>
                                            <?php endif; ?>
                                        </td>
                                        <td data-label="Ödeme Tarihi" style="color:rgba(255,255,255,0.5); font-size: 13px;">
                                            <?= $pay['payment_date'] ? date('d.m.Y H:i', strtotime($pay['payment_date'])) : '-' ?>
                                        </td>
                                        <td data-label="İşlem" style="text-align:right;">
                                            <form action="<?= BASE_URL ?>/panel/<?= $currentDept['slug'] ?>/dues/payments/toggle" method="POST" style="display:inline;">
                                                <?= '<input type="hidden" name="csrf_token" value="' . ($_SESSION['csrf_token'] ?? '') . '">' ?>
                                                <input type="hidden" name="payment_id" value="<?= $pay['id'] ?>">
                                                <?php if ($pay['status'] === 'paid'): ?>
                                                    <input type="hidden" name="status" value="unpaid">
                                                    <button type="submit" class="btn-glass btn-glass-warning btn-sm">GERİ AL</button>
                                                <?php else: ?>
                                                    <input type="hidden" name="status" value="paid">
                                                    <button type="submit" class="btn-glass btn-glass-success btn-sm">ÖDE</button>
                                                <?php endif; ?>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="glass-card" style="display:flex; align-items:center; justify-content:center; height:600px;">
                        <div class="text-center p-5">
                            <div style="width: 80px; height: 80px; border-radius: 20px; background: var(--input-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                                <svg viewBox="0 0 24 24" width="36" height="36" stroke="var(--muted)" stroke-width="1.5" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            </div>
                            <h4 style="color:var(--text); font-weight:700; margin:0 0 8px 0; font-size:20px;">Dönem Seçin veya Oluşturun</h4>
                            <p style="color:var(--muted); font-size:14px; line-height:1.5;">Detayları görmek için soldan bir aidat dönemi seçebilir <br> veya yeni bir dönem başlatabilirsiniz.</p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($userRole === 'director' || Auth::isSuperAdmin()): ?>
<!-- New Period Modal -->
<div class="modal-overlay" id="newPeriodModalOverlay">
    <div class="modal-glass">
        <form action="<?= BASE_URL ?>/panel/<?= $currentDept['slug'] ?>/dues/periods/store" method="POST">
            <?= '<input type="hidden" name="csrf_token" value="' . ($_SESSION['csrf_token'] ?? '') . '">' ?>
            <div class="modal-header">
                <h5 class="modal-title">
                    <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#3b82f6; margin-right:12px; box-shadow: 0 0 10px #3b82f6;"></span>
                    Yeni Aidat Dönemi Aç
                </h5>
                <button type="button" class="btn-close-modal" onclick="closeModal('newPeriodModalOverlay')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Dönem Adı</label>
                    <input type="text" name="name" class="form-control" placeholder="Örn: Kasım 2026 Aidatı" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Kişi Başı Aidat Tutarı (₺)</label>
                    <div class="input-group">
                        <span class="input-prefix" style="color:#60a5fa;">₺</span>
                        <input type="number" step="0.01" min="1" name="amount" class="form-control" style="font-size: 18px; font-weight: 500;" placeholder="150" required>
                    </div>
                    <div class="form-text">* Bu tutar sistemdeki tüm departman üyeleri için geçerli olacaktır.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-glass" style="color: var(--muted); background: transparent;" onclick="closeModal('newPeriodModalOverlay')">İptal Et</button>
                <button type="submit" class="btn-glass btn-glass-primary">Dönemi Başlat</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
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
