<?php
// views/modules/certificate.php
// $templates, $certificates, $availableForms, $canEdit, $currentDeptId

$typeLabels = [
    'participation' => 'Katılım',
    'completion'    => 'Tamamlama',
    'achievement'   => 'Başarı',
    'honorary'      => 'Onur',
];
$typeColors = [
    'participation' => '#3b82f6',
    'completion'    => '#10b981',
    'achievement'   => '#f59e0b',
    'honorary'      => '#8b5cf6',
];
$statusLabels = ['draft' => 'Taslak', 'published' => 'Yayınlandı', 'revoked' => 'İptal'];
$statusColors = ['draft' => '#6b7280', 'published' => '#10b981', 'revoked' => '#ef4444'];

$csrf = $_SESSION['csrf_token'] ?? '';
?>

<style>
:root {
    --cert-primary: #f59e0b;
    --cert-primary-dark: #d97706;
    --cert-card: var(--card);
    --cert-border: var(--border);
    --cert-bg: var(--bg);
    --cert-text: var(--text);
    --cert-muted: var(--muted);
}

/* ── Header ── */
.cert-header-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; flex-wrap:wrap; gap:16px; animation: fadeDown .4s ease-out; }
.cert-header-title { margin:0; font-size:24px; font-weight:700; letter-spacing:-.5px; }
.cert-header-sub { color:var(--cert-muted); font-size:14px; margin:4px 0 0; }

/* ── Tabs ── */
.cert-tabs { display:inline-flex; background:var(--cert-bg); padding:6px; border-radius:12px; margin-bottom:28px; border:1px solid var(--cert-border); gap:4px; }
.cert-tab { background:transparent; border:none; color:var(--cert-muted); font-size:14px; font-weight:600; cursor:pointer; padding:10px 22px; border-radius:8px; transition:all .3s; }
.cert-tab.active { background:var(--cert-card); color:var(--cert-text); box-shadow:0 2px 8px rgba(0,0,0,.12); }

/* ── Buttons ── */
.cert-btn { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border-radius:10px; font-size:14px; font-weight:600; cursor:pointer; border:none; transition:all .3s; }
.cert-btn-primary { background:linear-gradient(135deg,var(--cert-primary),var(--cert-primary-dark)); color:#fff; box-shadow:0 4px 12px rgba(245,158,11,.25); }
.cert-btn-primary:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(245,158,11,.35); }
.cert-btn-ghost { background:var(--cert-bg); color:var(--cert-text); border:1px solid var(--cert-border); }
.cert-btn-ghost:hover { border-color:var(--cert-primary); color:var(--cert-primary); }

/* ── Template Grid ── */
.cert-tpl-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:24px; margin-bottom:32px; }
.cert-tpl-card { background:var(--cert-card); border:1px solid var(--cert-border); border-radius:16px; overflow:hidden; transition:all .4s cubic-bezier(.175,.885,.32,1.275); animation:fadeUp .5s ease-out backwards; position:relative; }
.cert-tpl-card:hover { transform:translateY(-6px); box-shadow:0 16px 32px rgba(0,0,0,.15); }
.cert-tpl-thumb { width:100%; height:160px; object-fit:cover; display:block; background:#1a1a2e; }
.cert-tpl-thumb-placeholder { width:100%; height:160px; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,rgba(245,158,11,.1),rgba(245,158,11,.05)); color:var(--cert-primary); opacity:.6; }
.cert-tpl-body { padding:20px; }
.cert-tpl-title { font-size:17px; font-weight:700; margin:0 0 6px; }
.cert-tpl-meta { display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-top:12px; }
.cert-badge { font-size:11px; font-weight:700; padding:4px 10px; border-radius:20px; letter-spacing:.5px; }

.cert-tpl-actions { position:absolute; top:10px; right:10px; display:flex; gap:6px; opacity:0; transform:translateY(-8px); transition:all .3s; }
.cert-tpl-card:hover .cert-tpl-actions { opacity:1; transform:translateY(0); }
.cert-tpl-btn { background:rgba(0,0,0,.65); backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,.12); color:#fff; border-radius:8px; width:32px; height:32px; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:.2s; }
.cert-tpl-btn:hover { background:var(--cert-primary); }
.cert-tpl-btn svg { width:15px; height:15px; }

/* ── Certificate Table ── */
.cert-table-wrap { background:var(--cert-card); border:1px solid var(--cert-border); border-radius:16px; overflow:hidden; }
.cert-table { width:100%; border-collapse:collapse; }
.cert-table th { padding:14px 20px; font-size:12px; font-weight:600; color:var(--cert-muted); text-transform:uppercase; letter-spacing:.8px; border-bottom:1px solid var(--cert-border); text-align:left; }
.cert-table td { padding:14px 20px; font-size:14px; border-bottom:1px solid var(--cert-border); }
.cert-table tr:last-child td { border-bottom:none; }
.cert-table tr:hover td { background:rgba(245,158,11,.03); }
.cert-action-row { display:flex; gap:6px; align-items:center; }
.cert-act-btn { display:inline-flex; align-items:center; gap:5px; padding:6px 12px; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; border:none; transition:.2s; }
.cert-act-publish { background:rgba(16,185,129,.12); color:#10b981; }
.cert-act-publish:hover { background:#10b981; color:#fff; }
.cert-act-download { background:rgba(59,130,246,.12); color:#3b82f6; }
.cert-act-download:hover { background:#3b82f6; color:#fff; }
.cert-act-linkedin { background:rgba(14,118,168,.12); color:#0e76a8; }
.cert-act-linkedin:hover { background:#0e76a8; color:#fff; }
.cert-act-revoke { background:rgba(239,68,68,.1); color:#ef4444; }
.cert-act-revoke:hover { background:#ef4444; color:#fff; }
.cert-act-delete { background:rgba(107,114,128,.1); color:var(--cert-muted); }
.cert-act-delete:hover { background:var(--cert-muted); color:#fff; }

/* ── Canvas Designer ── */
#certDesignerWrap { display:flex; gap:24px; }
.cert-designer-sidebar { width:300px; flex-shrink:0; display:flex; flex-direction:column; gap:16px; }
.cert-panel { background:var(--cert-card); border:1px solid var(--cert-border); border-radius:14px; padding:20px; }
.cert-panel h4 { font-size:14px; font-weight:700; margin:0 0 16px; color:var(--cert-text); opacity:.9; }
.cert-designer-main { flex:1; display:flex; flex-direction:column; gap:16px; min-width:0; }
.cert-canvas-wrapper { background:#111; border-radius:14px; overflow:hidden; display:flex; align-items:center; justify-content:center; min-height:500px; border:1px solid var(--cert-border); position:relative; }
#certCanvas { cursor:move; display:block; max-width:100%; }
#certFieldList { display:flex; flex-direction:column; gap:8px; max-height:260px; overflow-y:auto; }
.cert-field-item { display:flex; align-items:center; justify-content:space-between; background:var(--cert-bg); border:1px solid var(--cert-border); border-radius:10px; padding:10px 14px; cursor:pointer; transition:.2s; font-size:13px; font-weight:600; }
.cert-field-item:hover, .cert-field-item.selected { border-color:var(--cert-primary); background:rgba(245,158,11,.06); }
.cert-field-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
.cert-field-del { background:transparent; border:none; color:var(--cert-muted); cursor:pointer; opacity:.6; transition:.2s; }
.cert-field-del:hover { color:#ef4444; opacity:1; }

.cert-prop-row { display:flex; align-items:center; gap:10px; margin-bottom:12px; }
.cert-prop-row label { font-size:12px; color:var(--cert-muted); font-weight:600; min-width:70px; }
.cert-prop-row input, .cert-prop-row select { flex:1; background:var(--cert-bg); border:1px solid var(--cert-border); border-radius:8px; color:var(--cert-text); padding:7px 10px; font-size:13px; }
.cert-prop-row input[type=color] { padding:2px 4px; height:36px; cursor:pointer; }
.cert-prop-row input:focus, .cert-prop-row select:focus { outline:none; border-color:var(--cert-primary); }

/* ── Modals ── */
.modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,.6); backdrop-filter:blur(8px); display:flex; justify-content:center; align-items:center; z-index:1000; opacity:0; pointer-events:none; transition:opacity .3s; }
.modal-overlay.active { opacity:1; pointer-events:auto; }
.modal-box { background:var(--cert-card); width:100%; max-width:640px; border-radius:20px; border:1px solid var(--cert-border); padding:32px; transform:translateY(30px) scale(.95); transition:all .3s cubic-bezier(.175,.885,.32,1.275); max-height:90vh; overflow-y:auto; box-shadow:0 24px 48px rgba(0,0,0,.3); }
.modal-overlay.active .modal-box { transform:translateY(0) scale(1); }
.modal-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; }
.modal-head h3 { margin:0; font-size:20px; font-weight:700; }
.modal-close { background:var(--cert-bg); border:none; color:var(--cert-text); width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; }
.form-group { margin-bottom:18px; }
.form-group label { display:block; font-size:13px; font-weight:600; margin-bottom:8px; }
.form-control { width:100%; padding:11px 14px; border-radius:10px; background:var(--cert-bg); border:1px solid var(--cert-border); color:var(--cert-text); font-family:inherit; font-size:14px; }
.form-control:focus { outline:none; border-color:var(--cert-primary); box-shadow:0 0 0 3px rgba(245,158,11,.15); }
.cert-submit-btn { width:100%; padding:14px; border-radius:12px; border:none; background:linear-gradient(135deg,var(--cert-primary),var(--cert-primary-dark)); color:#fff; font-size:15px; font-weight:700; cursor:pointer; margin-top:8px; transition:.3s; }
.cert-submit-btn:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(245,158,11,.3); }

/* ── Empty State ── */
.empty-state { text-align:center; padding:60px 20px; background:var(--cert-bg); border:1px dashed var(--cert-border); border-radius:16px; color:var(--cert-muted); }
.empty-state svg { opacity:.4; margin-bottom:16px; }
.empty-state h4 { margin:0 0 8px; color:var(--cert-text); font-size:18px; }

@keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
@keyframes fadeDown { from{opacity:0;transform:translateY(-15px)} to{opacity:1;transform:translateY(0)} }
</style>

<!-- ══ HEADER ══════════════════════════════════════════════════════════════ -->
<div class="cert-header-bar">
    <div>
        <h2 class="cert-header-title">🏆 Sertifika Yönetimi</h2>
        <p class="cert-header-sub">Sertifika şablonları tasarlayın, üyelere atayın ve yayınlayın.</p>
    </div>
    <?php if($canEdit): ?>
    <button class="cert-btn cert-btn-primary" onclick="openDesignerNew()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Yeni Şablon Tasarla
    </button>
    <?php endif; ?>
</div>

<!-- ══ TABS ════════════════════════════════════════════════════════════════ -->
<div class="cert-tabs">
    <button class="cert-tab active" onclick="switchCertTab('templates')">📋 Şablonlar (<?= count($templates) ?>)</button>
    <button class="cert-tab" onclick="switchCertTab('issued')">🎓 Verilen Sertifikalar (<?= count($certificates) ?>)</button>
    <?php if($canEdit): ?><button class="cert-tab" id="designerTabBtn" onclick="switchCertTab('designer')" style="display:none;">✏️ Tasarım Editörü</button><?php endif; ?>
</div>

<!-- ══ TAB: TEMPLATES ══════════════════════════════════════════════════════ -->
<div id="tab-templates">
<?php if(!empty($templates)): ?>
    <div class="cert-tpl-grid">
    <?php foreach($templates as $tpl):
        $typeColor = $typeColors[$tpl['template_type']] ?? '#6b7280';
        $typeLabel = $typeLabels[$tpl['template_type']] ?? 'Diğer';
        $bgSrc = $tpl['background_path'] ? BASE_URL . '/public/' . $tpl['background_path'] : '';
    ?>
        <div class="cert-tpl-card">
            <?php if($bgSrc): ?>
                <img src="<?= htmlspecialchars($bgSrc) ?>" class="cert-tpl-thumb" alt="Arka Plan">
            <?php else: ?>
                <div class="cert-tpl-thumb-placeholder">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="16" rx="2"/><path d="M8 21h8M12 17v4"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
            <?php endif; ?>
            <?php if($canEdit): ?>
            <div class="cert-tpl-actions">
                <button class="cert-tpl-btn" title="Şablonu Düzenle" onclick='openDesignerEdit(<?= json_encode($tpl, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE) ?>)'>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <button class="cert-tpl-btn" title="Sertifika Ver" onclick='openIssueModal(<?= (int)$tpl["id"] ?>, <?= htmlspecialchars(json_encode($tpl['title'])) ?>)' style="background:rgba(245,158,11,.8);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </button>
                <form method="POST" style="margin:0;" onsubmit="return confirm('Bu şablonu silmek istediğinize emin misiniz?');">
                    <input type="hidden" name="action" value="delete_template">
                    <input type="hidden" name="template_id" value="<?= $tpl['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <button type="submit" class="cert-tpl-btn" title="Sil" style="background:rgba(239,68,68,.7);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                    </button>
                </form>
            </div>
            <?php endif; ?>
            <div class="cert-tpl-body">
                <div class="cert-tpl-title"><?= htmlspecialchars($tpl['title']) ?></div>
                <?php if($tpl['description']): ?><p style="font-size:13px;color:var(--cert-muted);margin:4px 0 0;"><?= htmlspecialchars(mb_substr($tpl['description'],0,80)) ?>...</p><?php endif; ?>
                <div class="cert-tpl-meta">
                    <span class="cert-badge" style="background:<?= $typeColor ?>22;color:<?= $typeColor ?>;border:1px solid <?= $typeColor ?>44;"><?= $typeLabel ?></span>
                    <span class="cert-badge" style="background:rgba(107,114,128,.1);color:var(--cert-muted);border:1px solid var(--cert-border);"><?= (int)$tpl['cert_count'] ?> Sertifika</span>
                    <?php if($tpl['status']==='active'): ?><span class="cert-badge" style="background:rgba(16,185,129,.1);color:#10b981;border:1px solid rgba(16,185,129,.3);">Aktif</span>
                    <?php else: ?><span class="cert-badge" style="background:rgba(107,114,128,.1);color:var(--cert-muted);border:1px solid var(--cert-border);">Taslak</span><?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        <h4>Henüz Şablon Yok</h4>
        <p style="font-size:14px;margin:0;">İlk sertifika şablonunuzu "Yeni Şablon Tasarla" butonuyla oluşturun.</p>
    </div>
<?php endif; ?>
</div>

<!-- ══ TAB: ISSUED ═════════════════════════════════════════════════════════ -->
<div id="tab-issued" style="display:none;">
<?php if(!empty($certificates)): ?>
    <div class="cert-table-wrap">
    <table class="cert-table">
        <thead>
            <tr>
                <th>Sertifika No</th>
                <th>Alıcı</th>
                <th>Şablon</th>
                <th>Tarih</th>
                <th>Durum</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($certificates as $cert):
            $sColor = $statusColors[$cert['status']] ?? '#6b7280';
            $sLabel = $statusLabels[$cert['status']] ?? $cert['status'];
            $verifyUrl = BASE_URL . '/sertifika/' . $cert['certificate_no'];
            $linkedInUrl = 'https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME'
                . '&name=' . rawurlencode($cert['template_title'] ?? 'Sertifika')
                . '&organizationName=' . rawurlencode($department['name'] ?? '')
                . '&issueYear=' . date('Y', strtotime($cert['issue_date']))
                . '&issueMonth=' . date('n', strtotime($cert['issue_date']))
                . '&certUrl=' . rawurlencode($verifyUrl)
                . '&certId=' . rawurlencode($cert['certificate_no']);
        ?>
            <tr>
                <td>
                    <a href="<?= htmlspecialchars($verifyUrl) ?>" target="_blank" style="font-family:monospace;font-size:12px;color:var(--cert-primary);text-decoration:none;font-weight:700;">
                        <?= htmlspecialchars($cert['certificate_no']) ?>
                    </a>
                </td>
                <td>
                    <div style="font-weight:600;"><?= htmlspecialchars($cert['recipient_name']) ?></div>
                    <?php if($cert['recipient_email']): ?><div style="font-size:12px;color:var(--cert-muted);"><?= htmlspecialchars($cert['recipient_email']) ?></div><?php endif; ?>
                </td>
                <td style="color:var(--cert-muted);"><?= htmlspecialchars($cert['template_title'] ?? '-') ?></td>
                <td style="color:var(--cert-muted);"><?= date('d.m.Y', strtotime($cert['issue_date'])) ?></td>
                <td>
                    <span class="cert-badge" style="background:<?= $sColor ?>22;color:<?= $sColor ?>;border:1px solid <?= $sColor ?>44;">
                        <?= $sLabel ?>
                    </span>
                </td>
                <td>
                    <div class="cert-action-row">
                        <?php if($canEdit && $cert['status']==='draft'): ?>
                        <form method="POST" style="margin:0;">
                            <input type="hidden" name="action" value="publish">
                            <input type="hidden" name="cert_id" value="<?= $cert['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                            <button type="submit" class="cert-act-btn cert-act-publish">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Yayınla
                            </button>
                        </form>
                        <?php endif; ?>
                        <!-- PNG İndir (Canvas tarafı) -->
                        <button class="cert-act-btn cert-act-download" onclick='downloadPng(<?= json_encode($cert, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE) ?>)' title="PNG İndir">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> PNG
                        </button>
                        <!-- PDF İndir (Sunucu tarafı) -->
                        <form method="POST" style="margin:0;">
                            <input type="hidden" name="action" value="download_pdf">
                            <input type="hidden" name="cert_id" value="<?= $cert['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                            <button type="submit" class="cert-act-btn cert-act-download">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> PDF
                            </button>
                        </form>
                        <?php if($cert['status']==='published'): ?>
                        <a href="<?= htmlspecialchars($linkedInUrl) ?>" target="_blank" class="cert-act-btn cert-act-linkedin" style="text-decoration:none;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            LinkedIn
                        </a>
                        <?php endif; ?>
                        <?php if($canEdit && $cert['status']==='published'): ?>
                        <form method="POST" style="margin:0;" onsubmit="return confirm('Bu sertifikayı iptal etmek istediğinize emin misiniz?');">
                            <input type="hidden" name="action" value="revoke">
                            <input type="hidden" name="cert_id" value="<?= $cert['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                            <button type="submit" class="cert-act-btn cert-act-revoke">İptal</button>
                        </form>
                        <?php endif; ?>
                        <?php if($canEdit): ?>
                        <form method="POST" style="margin:0;" onsubmit="return confirm('Bu sertifikayı tamamen silmek istediğinize emin misiniz?');">
                            <input type="hidden" name="action" value="delete_cert">
                            <input type="hidden" name="cert_id" value="<?= $cert['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                            <button type="submit" class="cert-act-btn cert-act-delete">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
<?php else: ?>
    <div class="empty-state">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        <h4>Henüz Sertifika Verilmedi</h4>
        <p style="font-size:14px;margin:0;">Şablonlardan birini seçip üyelerinize sertifika verin.</p>
    </div>
<?php endif; ?>
</div>

<!-- ══ TAB: DESIGNER ═══════════════════════════════════════════════════════ -->
<?php if($canEdit): ?>
<div id="tab-designer" style="display:none;">
<form method="POST" enctype="multipart/form-data" id="designerForm">
    <input type="hidden" name="action" value="save_template">
    <input type="hidden" name="template_id" id="dsgTemplateId" value="">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
    <input type="hidden" name="fields_json" id="dsgFieldsJson" value="[]">
    <input type="hidden" name="existing_bg" id="dsgExistingBg" value="">

    <div id="certDesignerWrap">
        <!-- LEFT SIDEBAR -->
        <div class="cert-designer-sidebar">
            <!-- Şablon Bilgileri -->
            <div class="cert-panel">
                <h4>📋 Şablon Bilgileri</h4>
                <div class="form-group">
                    <label>Başlık</label>
                    <input type="text" name="title" id="dsgTitle" class="form-control" required placeholder="Şablon adı...">
                </div>
                <div class="form-group">
                    <label>Tür</label>
                    <select name="template_type" id="dsgType" class="form-control">
                        <option value="participation">Katılım</option>
                        <option value="completion">Tamamlama</option>
                        <option value="achievement">Başarı</option>
                        <option value="honorary">Onur</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Durum</label>
                    <select name="status" id="dsgStatus" class="form-control">
                        <option value="draft">Taslak</option>
                        <option value="active">Aktif</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Açıklama</label>
                    <textarea name="description" id="dsgDesc" class="form-control" rows="2" placeholder="Opsiyonel..."></textarea>
                </div>
            </div>

            <!-- Arka Plan -->
            <div class="cert-panel">
                <h4>🖼️ Arka Plan Görseli</h4>
                <input type="file" name="background" id="dsgBgFile" accept="image/*" class="form-control" style="padding:10px;" onchange="loadBgPreview(this)">
                <p style="font-size:11px;color:var(--cert-muted);margin-top:8px;">Önerilen: 1200×850 px, PNG/JPG</p>
                <div style="display:flex;gap:8px;margin-top:12px;">
                    <div class="form-group" style="flex:1;margin:0;">
                        <label>Genişlik (px)</label>
                        <input type="number" name="canvas_width" id="dsgWidth" class="form-control" value="1200" min="400" max="2400" onchange="resizeCanvas()">
                    </div>
                    <div class="form-group" style="flex:1;margin:0;">
                        <label>Yükseklik (px)</label>
                        <input type="number" name="canvas_height" id="dsgHeight" class="form-control" value="850" min="300" max="1800" onchange="resizeCanvas()">
                    </div>
                </div>
            </div>

            <!-- Alan Ekle -->
            <div class="cert-panel">
                <h4>➕ Metin Alanı Ekle</h4>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <button type="button" class="cert-btn cert-btn-ghost" style="justify-content:center;" onclick="addField('recipient_name','{{recipient_name}}')">👤 Alıcı Adı</button>
                    <button type="button" class="cert-btn cert-btn-ghost" style="justify-content:center;" onclick="addField('certificate_no','{{certificate_no}}')">🔑 Sertifika No</button>
                    <button type="button" class="cert-btn cert-btn-ghost" style="justify-content:center;" onclick="addField('issue_date','{{issue_date}}')">📅 Tarih</button>
                    <button type="button" class="cert-btn cert-btn-ghost" style="justify-content:center;" onclick="addField('custom_text','Özel Metin')">✏️ Özel Metin</button>
                    <button type="button" class="cert-btn cert-btn-ghost" style="justify-content:center;" onclick="addField('program_name','Program Adı')">🎓 Program Adı</button>
                    <button type="button" class="cert-btn cert-btn-ghost" style="justify-content:center;" onclick="addField('signature_name','İmzalayan Adı')">✍️ İmzalayan</button>
                    <button type="button" class="cert-btn cert-btn-ghost" style="justify-content:center;" onclick="addField('org_name','Organizasyon Adı')">🏢 Kuruluş</button>
                </div>
            </div>
        </div>

        <!-- MAIN CANVAS AREA -->
        <div class="cert-designer-main">
            <!-- Canvas -->
            <div class="cert-panel" style="padding:16px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                    <h4 style="margin:0;">Kanvas Önizleme</h4>
                    <div style="display:flex;gap:8px;">
                        <span style="font-size:12px;color:var(--cert-muted);">Alanları sürükleyerek yerleştirin</span>
                    </div>
                </div>
                <div class="cert-canvas-wrapper" id="canvasWrapper">
                    <canvas id="certCanvas" width="1200" height="850"></canvas>
                </div>
            </div>

            <!-- Alan Listesi & Özellikler -->
            <div style="display:flex;gap:16px;">
                <div class="cert-panel" style="flex:1;">
                    <h4>📑 Alanlar</h4>
                    <div id="certFieldList"></div>
                </div>
                <div class="cert-panel" style="flex:1;" id="propPanel">
                    <h4>⚙️ Alan Özellikleri</h4>
                    <p style="font-size:13px;color:var(--cert-muted);" id="propHint">Bir alana tıklayın.</p>
                    <div id="propControls" style="display:none;">
                        <div class="cert-prop-row"><label>Metin</label><input type="text" id="propText" oninput="updateFieldProp('text', this.value)"></div>
                        <div class="cert-prop-row"><label>Font Boyutu</label><input type="number" id="propSize" value="36" min="8" max="200" oninput="updateFieldProp('fontSize', parseInt(this.value))"></div>
                        <div class="cert-prop-row"><label>Renk</label><input type="color" id="propColor" value="#1a1a2e" oninput="updateFieldProp('color', this.value)"></div>
                        <div class="cert-prop-row"><label>Hizalama</label>
                            <select id="propAlign" onchange="updateFieldProp('align', this.value)">
                                <option value="center">Orta</option>
                                <option value="left">Sol</option>
                                <option value="right">Sağ</option>
                            </select>
                        </div>
                        <div class="cert-prop-row"><label>Kalın</label><input type="checkbox" id="propBold" onchange="updateFieldProp('bold', this.checked)"></div>
                        <div class="cert-prop-row"><label>İtalik</label><input type="checkbox" id="propItalic" onchange="updateFieldProp('italic', this.checked)"></div>
                        <div class="cert-prop-row"><label>Genişlik%</label><input type="number" id="propWidth" value="60" min="10" max="100" oninput="updateFieldProp('width', parseInt(this.value))"></div>
                    </div>
                </div>
            </div>

            <!-- Kaydet Butonu -->
            <button type="submit" class="cert-submit-btn" onclick="syncFieldsJson()">
                💾 Şablonu Kaydet
            </button>
        </div>
    </div>
</form>
</div>
<?php endif; ?>

<!-- ══ MODAL: Sertifika Ver ════════════════════════════════════════════════ -->
<?php if($canEdit): ?>
<div class="modal-overlay" id="issueModal">
    <div class="modal-box">
        <div class="modal-head">
            <h3>🎓 Sertifika Ver</h3>
            <button type="button" class="modal-close" onclick="closeIssueModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="issue">
            <input type="hidden" name="template_id" id="issueTemplateId">
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

            <div style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);border-radius:10px;padding:12px 16px;margin-bottom:20px;">
                <span style="font-size:13px;font-weight:600;color:var(--cert-primary);">Şablon: </span>
                <span id="issueTemplateName" style="font-size:13px;color:var(--cert-text);"></span>
            </div>

            <div style="display:flex;gap:16px;">
                <div class="form-group" style="flex:2;">
                    <label>Alıcı Adı Soyadı</label>
                    <input type="text" name="recipient_name" class="form-control" required placeholder="Örn: Ali Veli">
                </div>
                <div class="form-group" style="flex:2;">
                    <label>E-posta (Opsiyonel)</label>
                    <input type="email" name="recipient_email" class="form-control" placeholder="ali@ornek.com">
                </div>
            </div>
            <div style="display:flex;gap:16px;">
                <div class="form-group" style="flex:1;">
                    <label>Verilme Tarihi</label>
                    <input type="date" name="issue_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Son Geçerlilik (Opsiyonel)</label>
                    <input type="date" name="expiry_date" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label>Not / Özel Bilgi</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Sertifikaya eklenecek özel not..."></textarea>
            </div>
            <button type="submit" class="cert-submit-btn">Sertifikayı Oluştur</button>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- ══ HIDDEN PNG RENDER CANVAS ═══════════════════════════════════════════ -->
<canvas id="pngRenderCanvas" style="display:none;"></canvas>

<script>
// ── Tab Switch ────────────────────────────────────────────────────────────
function switchCertTab(tabId) {
    ['templates','issued','designer'].forEach(t => {
        const el = document.getElementById('tab-' + t);
        if (el) el.style.display = (t === tabId) ? 'block' : 'none';
    });
    document.querySelectorAll('.cert-tab').forEach(btn => btn.classList.remove('active'));
    const clicked = document.querySelector(`.cert-tab[onclick="switchCertTab('${tabId}')"]`);
    if (clicked) clicked.classList.add('active');
    if (tabId === 'designer') drawCanvas();
}

// ── Issue Modal ───────────────────────────────────────────────────────────
function openIssueModal(templateId, templateName) {
    document.getElementById('issueTemplateId').value = templateId;
    document.getElementById('issueTemplateName').textContent = templateName;
    document.getElementById('issueModal').classList.add('active');
}
function closeIssueModal() {
    document.getElementById('issueModal').classList.remove('active');
}

// ── Canvas Designer ───────────────────────────────────────────────────────
let certFields     = [];    // { key, text, x, y, fontSize, color, align, bold, italic, width }
let selectedIndex  = -1;
let bgImage        = null;
let bgImageSrc     = '';
let dragState      = null;
let canvasW        = 1200;
let canvasH        = 850;
const COLORS       = ['#f59e0b','#3b82f6','#10b981','#8b5cf6','#ef4444','#ec4899','#06b6d4','#f97316'];

function getCanvas()  { return document.getElementById('certCanvas'); }
function getCtx()     { return getCanvas().getContext('2d'); }

function addField(key, defaultText) {
    certFields.push({
        key,
        text: defaultText,
        x: 25,
        y: Math.round(30 + (certFields.length * 12)),
        fontSize: key === 'recipient_name' ? 56 : 28,
        color: key === 'recipient_name' ? '#1a1a2e' : '#444444',
        align: 'center',
        bold: key === 'recipient_name',
        italic: false,
        width: 70,
        _color: COLORS[certFields.length % COLORS.length]
    });
    selectedIndex = certFields.length - 1;
    renderFieldList();
    drawCanvas();
    showProps(selectedIndex);
}

function renderFieldList() {
    const list = document.getElementById('certFieldList');
    list.innerHTML = '';
    certFields.forEach((f, i) => {
        const div = document.createElement('div');
        div.className = 'cert-field-item' + (i === selectedIndex ? ' selected' : '');
        div.innerHTML = `
            <div style="display:flex;align-items:center;gap:10px;overflow:hidden;">
                <div class="cert-field-dot" style="background:${f._color};"></div>
                <span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${escHtml(f.text.substring(0,30))}</span>
            </div>
            <button type="button" class="cert-field-del" onclick="deleteField(${i})">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>`;
        div.addEventListener('click', e => { if (!e.target.closest('.cert-field-del')) { selectedIndex = i; renderFieldList(); drawCanvas(); showProps(i); }});
        list.appendChild(div);
    });
}

function deleteField(i) {
    certFields.splice(i, 1);
    if (selectedIndex >= certFields.length) selectedIndex = certFields.length - 1;
    renderFieldList(); drawCanvas();
    if (selectedIndex >= 0) showProps(selectedIndex);
    else { document.getElementById('propControls').style.display='none'; document.getElementById('propHint').style.display=''; }
}

function showProps(i) {
    const f = certFields[i];
    if (!f) return;
    document.getElementById('propHint').style.display = 'none';
    document.getElementById('propControls').style.display = 'block';
    document.getElementById('propText').value   = f.text;
    document.getElementById('propSize').value   = f.fontSize;
    document.getElementById('propColor').value  = f.color;
    document.getElementById('propAlign').value  = f.align;
    document.getElementById('propBold').checked  = f.bold;
    document.getElementById('propItalic').checked = f.italic;
    document.getElementById('propWidth').value  = f.width;
}

function updateFieldProp(prop, value) {
    if (selectedIndex < 0) return;
    certFields[selectedIndex][prop] = value;
    renderFieldList();
    drawCanvas();
}

function drawCanvas() {
    const cv = getCanvas();
    const ctx = getCtx();
    cv.width  = canvasW;
    cv.height = canvasH;

    // Background
    if (bgImage) {
        ctx.drawImage(bgImage, 0, 0, canvasW, canvasH);
    } else {
        ctx.fillStyle = '#f8f5e9';
        ctx.fillRect(0, 0, canvasW, canvasH);
        ctx.strokeStyle = '#d4b896';
        ctx.lineWidth = 8;
        ctx.strokeRect(16, 16, canvasW-32, canvasH-32);
    }

    // Fields
    certFields.forEach((f, i) => {
        const x   = Math.round(f.x / 100 * canvasW);
        const y   = Math.round(f.y / 100 * canvasH);
        const w   = Math.round(f.width / 100 * canvasW);

        const fontStr = `${f.italic?'italic ':''} ${f.bold?'bold ':''} ${f.fontSize}px 'Segoe UI', Arial, sans-serif`;
        ctx.font      = fontStr;
        ctx.fillStyle = f.color;
        ctx.textAlign = f.align || 'center';

        const lines = f.text.split('\n');
        const lh    = f.fontSize * 1.4;
        lines.forEach((line, li) => {
            let tx = x;
            if (f.align === 'center') tx = x + w / 2;
            else if (f.align === 'right') tx = x + w;
            ctx.fillText(line, tx, y + li * lh);
        });

        // Selection highlight
        if (i === selectedIndex) {
            ctx.strokeStyle = '#f59e0b';
            ctx.lineWidth   = 2;
            ctx.setLineDash([6, 3]);
            ctx.strokeRect(x - 4, y - f.fontSize - 4, w + 8, lines.length * f.fontSize * 1.4 + 8);
            ctx.setLineDash([]);
        }
    });

    // Scale canvas for display
    const wrapper = document.getElementById('canvasWrapper');
    const maxW    = wrapper.clientWidth - 32;
    const scale   = Math.min(1, maxW / canvasW);
    cv.style.width  = Math.round(canvasW * scale) + 'px';
    cv.style.height = Math.round(canvasH * scale) + 'px';
}

function resizeCanvas() {
    canvasW = parseInt(document.getElementById('dsgWidth').value) || 1200;
    canvasH = parseInt(document.getElementById('dsgHeight').value) || 850;
    drawCanvas();
}

function loadBgPreview(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        bgImageSrc = e.target.result;
        const img  = new Image();
        img.onload = () => { bgImage = img; drawCanvas(); };
        img.src    = e.target.result;
    };
    reader.readAsDataURL(file);
}

// Drag & Drop on canvas
getCanvas()?.addEventListener('mousedown', e => {
    const cv     = getCanvas();
    const scale  = canvasW / cv.clientWidth;
    const rect   = cv.getBoundingClientRect();
    const mx     = (e.clientX - rect.left) * scale;
    const my     = (e.clientY - rect.top)  * scale;

    for (let i = certFields.length - 1; i >= 0; i--) {
        const f  = certFields[i];
        const fx = Math.round(f.x / 100 * canvasW);
        const fy = Math.round(f.y / 100 * canvasH);
        const fw = Math.round(f.width / 100 * canvasW);
        const fh = f.fontSize * 1.6 * f.text.split('\n').length;

        if (mx >= fx - 4 && mx <= fx + fw + 4 && my >= fy - f.fontSize - 4 && my <= fy + fh) {
            selectedIndex = i;
            dragState     = { idx: i, startMx: mx, startMy: my, startX: f.x, startY: f.y };
            renderFieldList();
            showProps(i);
            break;
        }
    }
});

document.addEventListener('mousemove', e => {
    if (!dragState) return;
    const cv     = getCanvas();
    const scale  = canvasW / cv.clientWidth;
    const rect   = cv.getBoundingClientRect();
    const mx     = (e.clientX - rect.left) * scale;
    const my     = (e.clientY - rect.top)  * scale;
    const dx     = ((mx - dragState.startMx) / canvasW) * 100;
    const dy     = ((my - dragState.startMy) / canvasH) * 100;
    certFields[dragState.idx].x = Math.max(0, Math.min(90, dragState.startX + dx));
    certFields[dragState.idx].y = Math.max(5, Math.min(98, dragState.startY + dy));
    drawCanvas();
});
document.addEventListener('mouseup', () => { dragState = null; });

function syncFieldsJson() {
    document.getElementById('dsgFieldsJson').value = JSON.stringify(certFields);
}

function openDesignerNew() {
    certFields = []; selectedIndex = -1; bgImage = null; bgImageSrc = '';
    document.getElementById('designerForm').reset();
    document.getElementById('dsgTemplateId').value = '';
    document.getElementById('dsgExistingBg').value = '';
    canvasW = 1200; canvasH = 850;
    document.getElementById('dsgWidth').value  = 1200;
    document.getElementById('dsgHeight').value = 850;
    document.getElementById('certFieldList').innerHTML = '';
    document.getElementById('propControls').style.display = 'none';
    document.getElementById('propHint').style.display = '';

    // Show designer tab
    document.getElementById('designerTabBtn').style.display = '';
    switchCertTab('designer');
}

function openDesignerEdit(tpl) {
    certFields = JSON.parse(tpl.fields_json || '[]');
    certFields = certFields.map((f, i) => ({ ...f, _color: COLORS[i % COLORS.length] }));
    selectedIndex = -1;
    bgImage = null; bgImageSrc = '';

    document.getElementById('dsgTemplateId').value = tpl.id;
    document.getElementById('dsgTitle').value       = tpl.title;
    document.getElementById('dsgType').value        = tpl.template_type;
    document.getElementById('dsgStatus').value      = tpl.status;
    document.getElementById('dsgDesc').value        = tpl.description || '';
    document.getElementById('dsgExistingBg').value  = tpl.background_path || '';
    canvasW = parseInt(tpl.canvas_width) || 1200;
    canvasH = parseInt(tpl.canvas_height) || 850;
    document.getElementById('dsgWidth').value  = canvasW;
    document.getElementById('dsgHeight').value = canvasH;

    // Load existing bg
    if (tpl.background_path) {
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = () => { bgImage = img; drawCanvas(); };
        img.onerror = () => { bgImage = null; drawCanvas(); };
        img.src = '<?= BASE_URL ?>/public/' + tpl.background_path + '?t=' + Date.now();
    }

    document.getElementById('designerTabBtn').style.display = '';
    renderFieldList();
    switchCertTab('designer');
    setTimeout(drawCanvas, 100);
}

// ── PNG Download ──────────────────────────────────────────────────────────
function downloadPng(cert) {
    const tpl    = JSON.parse(cert.fields_json   || '[]');
    const vals   = JSON.parse(cert.field_values  || '{}');
    const w      = parseInt(cert.canvas_width)   || 1200;
    const h      = parseInt(cert.canvas_height)  || 850;
    const cv     = document.getElementById('pngRenderCanvas');
    const ctx    = cv.getContext('2d');
    cv.width     = w;
    cv.height    = h;

    const recipientName = cert.recipient_name  || '';
    const certNo        = cert.certificate_no  || '';
    const issueDate     = cert.issue_date ? cert.issue_date.substring(0,10) : '';

    function renderPng() {
        if (cert.background_path) {
            ctx.drawImage(bgImg, 0, 0, w, h);
        } else {
            ctx.fillStyle = '#f8f5e9'; ctx.fillRect(0,0,w,h);
        }
        (tpl || []).forEach(f => {
            let text = vals[f.key] || f.default || f.text || '';
            text = text.replace('{{recipient_name}}', recipientName)
                       .replace('{{certificate_no}}', certNo)
                       .replace('{{issue_date}}', issueDate);
            const fx = Math.round((f.x||0) / 100 * w);
            const fy = Math.round((f.y||0) / 100 * h);
            const fw = Math.round((f.width||60) / 100 * w);
            ctx.font      = `${f.italic?'italic ':''} ${f.bold?'bold ':''} ${f.fontSize||28}px 'Segoe UI', Arial`;
            ctx.fillStyle = f.color || '#000';
            ctx.textAlign = f.align || 'center';
            text.split('\n').forEach((line, li) => {
                let tx = fx;
                if (f.align==='center') tx = fx + fw/2;
                else if (f.align==='right') tx = fx + fw;
                ctx.fillText(line, tx, fy + li * (f.fontSize||28) * 1.4);
            });
        });
        const link  = document.createElement('a');
        link.download = 'Sertifika_' + recipientName.replace(/\s+/g,'_') + '_' + certNo + '.png';
        link.href     = cv.toDataURL('image/png');
        link.click();
    }

    if (cert.background_path) {
        const bgImg = new Image();
        bgImg.crossOrigin = 'anonymous';
        bgImg.onload  = renderPng;
        bgImg.onerror = () => { renderPng(); };
        bgImg.src = '<?= BASE_URL ?>/public/' + cert.background_path + '?t=' + Date.now();
        // re-bind bgImg
        var _renderPng = renderPng;
        renderPng = function() { ctx.drawImage(bgImg,0,0,w,h); _renderPng.call(arguments); };
        // simpler approach:
        bgImg.onload = function() {
            ctx.drawImage(bgImg,0,0,w,h);
            (tpl||[]).forEach(f => {
                let text = vals[f.key] || f.default || f.text || '';
                text = text.replace('{{recipient_name}}', recipientName).replace('{{certificate_no}}', certNo).replace('{{issue_date}}', issueDate);
                const fx = Math.round((f.x||0)/100*w), fy = Math.round((f.y||0)/100*h), fw = Math.round((f.width||60)/100*w);
                ctx.font = `${f.italic?'italic ':''} ${f.bold?'bold ':''} ${f.fontSize||28}px 'Segoe UI', Arial`;
                ctx.fillStyle = f.color||'#000'; ctx.textAlign = f.align||'center';
                text.split('\n').forEach((line, li) => { let tx = fx; if(f.align==='center') tx=fx+fw/2; else if(f.align==='right') tx=fx+fw; ctx.fillText(line, tx, fy+li*(f.fontSize||28)*1.4); });
            });
            const link = document.createElement('a');
            link.download = 'Sertifika_' + recipientName.replace(/\s+/g,'_') + '_' + certNo + '.png';
            link.href = cv.toDataURL('image/png'); link.click();
        };
    } else {
        renderPng();
    }
}

function escHtml(t) { return t.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

// Init
window.addEventListener('load', () => {
    if (document.getElementById('certCanvas')) {
        document.getElementById('certCanvas').addEventListener('mousedown', function(){});
        drawCanvas();
    }
});
</script>
