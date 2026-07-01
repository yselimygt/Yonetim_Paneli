<?php
// views/certificate_verify.php
// $cert, $template, $department

$recipientName = htmlspecialchars($cert['recipient_name'] ?? '');
$certNo        = htmlspecialchars($cert['certificate_no']  ?? '');
$issueDate     = isset($cert['issue_date']) ? date('d F Y', strtotime($cert['issue_date'])) : '';
$expiryDate    = !empty($cert['expiry_date']) ? date('d F Y', strtotime($cert['expiry_date'])) : null;
$templateTitle = htmlspecialchars($template['title'] ?? 'Sertifika');
$deptName      = htmlspecialchars($department['name'] ?? '');
$status        = $cert['status'] ?? 'published';
$isValid       = ($status === 'published');
$verifyUrl     = BASE_URL . '/sertifika/' . $certNo;
$bgPath        = !empty($template['background_path']) ? BASE_URL . '/public/' . $template['background_path'] : '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $recipientName ?> — <?= $templateTitle ?></title>

    <!-- Open Graph / LinkedIn -->
    <meta property="og:type"        content="website">
    <meta property="og:title"       content="<?= $recipientName ?> — <?= $templateTitle ?>">
    <meta property="og:description" content="<?= $deptName ?> tarafından verilen sertifika. Verilme tarihi: <?= $issueDate ?>. No: <?= $certNo ?>">
    <meta property="og:url"         content="<?= htmlspecialchars($verifyUrl) ?>">
    <?php if($bgPath): ?>
    <meta property="og:image"       content="<?= htmlspecialchars($bgPath) ?>">
    <?php endif; ?>
    <meta property="og:site_name"   content="<?= $deptName ?>">
    <meta name="twitter:card"       content="summary_large_image">
    <meta name="twitter:title"      content="<?= $recipientName ?> — <?= $templateTitle ?>">
    <meta name="twitter:description" content="<?= $deptName ?> sertifikası doğrulandı.">
    <?php if($bgPath): ?><meta name="twitter:image" content="<?= htmlspecialchars($bgPath) ?>"><?php endif; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Inter', sans-serif;
        background: #0d1117;
        color: #e6edf3;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 32px 16px;
    }
    .verify-container {
        width: 100%;
        max-width: 700px;
    }
    .verify-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: .5px;
        margin-bottom: 28px;
    }
    .badge-valid   { background: rgba(16,185,129,.15); color: #10b981; border: 1px solid rgba(16,185,129,.35); }
    .badge-invalid { background: rgba(239,68,68,.15); color: #ef4444; border: 1px solid rgba(239,68,68,.35); }
    .badge-revoked { background: rgba(107,114,128,.15); color: #9ca3af; border: 1px solid rgba(107,114,128,.35); }
    .verify-badge svg { width:18px; height:18px; }

    .cert-preview {
        background: #161b22;
        border: 1px solid #30363d;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 28px;
        position: relative;
    }
    .cert-preview-bg {
        width: 100%;
        max-height: 420px;
        object-fit: cover;
        display: block;
        opacity: <?= $isValid ? '1' : '.4' ?>;
    }
    .cert-preview-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(22,27,34,1) 0%, rgba(22,27,34,0) 50%);
    }
    .cert-info {
        background: #161b22;
        border: 1px solid #30363d;
        border-radius: 20px;
        padding: 32px;
        margin-bottom: 24px;
    }
    .cert-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 24px;
    }
    @media(max-width:540px){ .cert-info-grid { grid-template-columns: 1fr; } }
    .info-item label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #6e7681;
        display: block;
        margin-bottom: 6px;
    }
    .info-item .val {
        font-size: 16px;
        font-weight: 600;
        color: #e6edf3;
    }
    .info-item .mono {
        font-family: monospace;
        font-size: 15px;
        color: #f59e0b;
        word-break: break-all;
    }
    .cert-name {
        font-size: 28px;
        font-weight: 800;
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 8px;
    }
    .cert-title { font-size: 16px; color: #8b949e; margin-bottom: 4px; }
    .cert-org   { font-size: 14px; color: #6e7681; }

    .action-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 8px;
    }
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 22px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        border: none;
        transition: all .25s;
    }
    .btn-linkedin {
        background: #0e76a8;
        color: #fff;
    }
    .btn-linkedin:hover { background: #0a5a80; transform:translateY(-2px); }
    .btn-share {
        background: rgba(245,158,11,.12);
        color: #f59e0b;
        border: 1px solid rgba(245,158,11,.3);
    }
    .btn-share:hover { background:rgba(245,158,11,.2); transform:translateY(-2px); }
    .btn-ghost {
        background: rgba(255,255,255,.05);
        color: #8b949e;
        border: 1px solid #30363d;
    }
    .btn-ghost:hover { background:rgba(255,255,255,.08); }

    .footer-note {
        font-size:12px;
        color:#6e7681;
        text-align:center;
        margin-top:20px;
    }
    .divider { border:none; border-top:1px solid #21262d; margin:20px 0; }
    </style>
</head>
<body>
<div class="verify-container">

    <?php if($isValid): ?>
    <div class="verify-badge badge-valid">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        ✅ Bu Sertifika Doğrulandı
    </div>
    <?php elseif($status === 'revoked'): ?>
    <div class="verify-badge badge-revoked">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        ❌ Bu Sertifika İptal Edildi
    </div>
    <?php else: ?>
    <div class="verify-badge badge-invalid">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        ⚠️ Taslak — Yayınlanmamış
    </div>
    <?php endif; ?>

    <?php if($bgPath): ?>
    <div class="cert-preview">
        <img src="<?= htmlspecialchars($bgPath) ?>" class="cert-preview-bg" alt="Sertifika Görseli">
        <div class="cert-preview-overlay"></div>
    </div>
    <?php endif; ?>

    <div class="cert-info">
        <div class="cert-name"><?= $recipientName ?></div>
        <div class="cert-title"><?= $templateTitle ?></div>
        <div class="cert-org">📍 <?= $deptName ?></div>

        <hr class="divider">

        <div class="cert-info-grid">
            <div class="info-item">
                <label>Sertifika No</label>
                <div class="mono"><?= $certNo ?></div>
            </div>
            <div class="info-item">
                <label>Verilme Tarihi</label>
                <div class="val"><?= $issueDate ?></div>
            </div>
            <?php if($expiryDate): ?>
            <div class="info-item">
                <label>Son Geçerlilik</label>
                <div class="val"><?= $expiryDate ?></div>
            </div>
            <?php endif; ?>
            <?php if(!empty($cert['notes'])): ?>
            <div class="info-item" style="grid-column:1/-1;">
                <label>Not</label>
                <div class="val" style="font-size:14px;font-weight:400;color:#8b949e;"><?= htmlspecialchars($cert['notes']) ?></div>
            </div>
            <?php endif; ?>
        </div>

        <?php if($isValid): ?>
        <hr class="divider">
        <div class="action-row">
            <?php
            $linkedInUrl = 'https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME'
                . '&name='              . rawurlencode($templateTitle)
                . '&organizationName=' . rawurlencode($deptName)
                . '&issueYear='        . date('Y', strtotime($cert['issue_date']))
                . '&issueMonth='       . date('n', strtotime($cert['issue_date']))
                . ((!empty($cert['expiry_date'])) ? '&expirationYear=' . date('Y', strtotime($cert['expiry_date'])) . '&expirationMonth=' . date('n', strtotime($cert['expiry_date'])) : '')
                . '&certUrl='          . rawurlencode($verifyUrl)
                . '&certId='           . rawurlencode($certNo);
            ?>
            <a href="<?= htmlspecialchars($linkedInUrl) ?>" target="_blank" class="action-btn btn-linkedin">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                LinkedIn'e Ekle
            </a>
            <button class="action-btn btn-share" onclick="copyLink()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                Linki Kopyala
            </button>
        </div>
        <?php endif; ?>
    </div>

    <p class="footer-note">Bu sertifika <strong><?= $deptName ?></strong> tarafından oluşturulmuştur. Sertifika numarasını sisteme girerek gerçekliğini doğrulayabilirsiniz.</p>
</div>

<script>
function copyLink() {
    navigator.clipboard.writeText(<?= json_encode($verifyUrl) ?>).then(() => {
        const btn = event.target.closest('.action-btn');
        const orig = btn.innerHTML;
        btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Kopyalandı!';
        setTimeout(() => { btn.innerHTML = orig; }, 2000);
    });
}
</script>
</body>
</html>
