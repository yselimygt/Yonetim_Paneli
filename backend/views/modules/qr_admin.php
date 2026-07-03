<style>
.qr-admin-container { max-width: 900px; margin: 0 auto; }
.card-grid { display: grid; grid-template-columns: 1fr 300px; gap: 24px; align-items: start; }

.q-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 24px; margin-bottom: 24px; }
.q-card h3 { margin-top: 0; margin-bottom: 20px; font-size: 18px; color: var(--text); }

.form-group { margin-bottom: 16px; }
.form-label { display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px; color: var(--muted); }
.form-control { width: 100%; padding: 10px 14px; background: var(--input-bg); border: 1px solid var(--border); color: var(--text); border-radius: 8px; font-size: 14px; }
.form-control:focus { outline: none; border-color: var(--accent); }

.color-picker { width: 100%; height: 42px; padding: 0; border: none; border-radius: 8px; cursor: pointer; }

/* Preview Box */
.qr-preview-box { background: #fff; padding: 20px; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 250px; border: 1px solid var(--border); }
.qr-preview-box #liveQrCode { margin-bottom: 16px; }
.qr-preview-box #liveQrCode img, .qr-preview-box #liveQrCode canvas { max-width: 100%; border-radius: 4px; }

/* Saved list */
.qr-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
.qr-item { background: var(--card-alt); border: 1px solid var(--border); border-radius: 12px; padding: 20px; display: flex; flex-direction: column; align-items: center; text-align: center; }
.qr-item-title { font-weight: 600; font-size: 15px; margin-bottom: 4px; width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.qr-item-val { font-size: 12px; color: var(--muted); margin-bottom: 16px; word-break: break-all; }
.qr-item-box { background: #fff; padding: 12px; border-radius: 8px; margin-bottom: 16px; }
.qr-item-actions { display: flex; gap: 8px; width: 100%; }
.qr-btn { flex: 1; padding: 8px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; text-align: center; border: 1px solid transparent; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none; }
.qr-btn-primary { background: var(--accent); color: #0a0a0a; }
.qr-btn-primary:hover { opacity: 0.9; }
.qr-btn-danger { background: rgba(239,68,68,0.1); color: #ef4444; border-color: rgba(239,68,68,0.2); }
.qr-btn-danger:hover { background: rgba(239,68,68,0.2); }

@media(max-width: 768px) {
    .qr-admin-container {
        max-width: 100%;
    }
    .qr-admin-container > div:first-child {
        margin-bottom: 14px !important;
    }
    .qr-admin-container h1 {
        font-size: 20px !important;
        line-height: 1.15;
    }
    .qr-admin-container > div:first-child p {
        display: none;
    }
    .card-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    .q-card {
        padding: 14px;
        border-radius: 14px;
        margin-bottom: 10px;
    }
    .q-card h3 {
        font-size: 15px;
        margin-bottom: 12px;
    }
    .form-group {
        margin-bottom: 12px;
    }
    .form-label {
        font-size: 11px;
        margin-bottom: 6px;
    }
    .form-control {
        min-height: 38px;
        padding: 9px 10px;
        border-radius: 9px;
        font-size: 13px;
    }
    .color-picker {
        height: 38px;
    }
    .q-card form > div[style*="display:flex"] {
        gap: 8px !important;
    }
    .q-card form > div[style*="margin-top: 16px"] {
        margin-top: 10px !important;
    }
    .q-card form > div[style*="margin-top: 16px"] .qr-btn {
        min-height: 40px;
        padding: 8px !important;
        border-radius: 10px;
        font-size: 12px !important;
    }
    .q-card form > div[style*="margin-top: 16px"] svg {
        width: 14px;
        height: 14px;
        margin-right: 0 !important;
    }
    .qr-preview-box {
        min-height: 180px;
        padding: 14px;
        border-radius: 12px;
    }
    .qr-preview-box #liveQrCode {
        margin-bottom: 10px;
    }
    .qr-preview-box #liveQrCode canvas,
    .qr-preview-box #liveQrCode img {
        width: 140px !important;
        height: 140px !important;
    }
    .qr-admin-container h2 {
        font-size: 17px !important;
        margin: 14px 0 10px !important;
    }
    .qr-list {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }
    .qr-item {
        min-width: 0;
        padding: 10px;
        border-radius: 14px;
    }
    .qr-item-title {
        font-size: 12px;
        margin-bottom: 3px;
    }
    .qr-item-val {
        font-size: 10px;
        line-height: 1.25;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .qr-item-box {
        padding: 8px;
        margin-bottom: 8px;
        border-radius: 9px;
        max-width: 100%;
    }
    .qr-item-box canvas,
    .qr-item-box img {
        width: 100px !important;
        height: 100px !important;
    }
    .qr-item-actions {
        gap: 6px;
    }
    .qr-btn {
        min-height: 30px;
        padding: 6px;
        border-radius: 9px;
        font-size: 11px;
        gap: 4px;
    }
    .qr-btn svg {
        width: 12px;
        height: 12px;
    }
}
</style>

<div class="qr-admin-container">
    <div style="margin-bottom:24px;">
        <h1 style="margin:0; font-size:24px;">Karekod (QR) Oluşturucu</h1>
        <p style="color:var(--muted); margin:4px 0 0 0;">Etkinlikleriniz, websiteniz veya hızlı erişim için QR kodlar oluşturun.</p>
    </div>

    <?php if(!empty($_SESSION['success_msg'])): ?>
        <div style="background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); color:#10b981; padding:12px 16px; border-radius:8px; margin-bottom:24px;">
            <?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
        </div>
    <?php endif; ?>

    <div class="card-grid">
        <!-- FORM -->
        <div class="q-card">
            <h3>Yeni QR Kod Oluştur</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="form-group">
                    <label class="form-label">Başlık (Kendiniz için)</label>
                    <input type="text" name="title" class="form-control" placeholder="Örn: 2026 Zirvesi Kayıt Formu" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Hedef Bağlantı (URL) veya Metin</label>
                    <input type="text" name="value" id="qrValueInput" class="form-control" placeholder="https://..." required>
                </div>

                <div style="display:flex; gap:16px;">
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">QR Kod Rengi (Koyu)</label>
                        <input type="color" name="colorDark" id="qrColorDark" class="color-picker" value="#000000">
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Arka Plan Rengi</label>
                        <input type="color" name="colorLight" id="qrColorLight" class="color-picker" value="#ffffff">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Ortadaki Logo (Opsiyonel)</label>
                    <input type="file" name="logoFile" id="qrLogoFile" class="form-control" accept="image/png, image/jpeg, image/svg+xml">
                    <div style="font-size:12px; color:var(--muted); margin-top:4px;">Logoyu QR koda yerleştirmek için seçin.</div>
                </div>

                <div style="display:flex; gap:16px;">
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Nokta Stili</label>
                        <select name="dotsType" id="qrDotsType" class="form-control">
                            <option value="square">Klasik (Kare)</option>
                            <option value="dots">Noktalı (Dots)</option>
                            <option value="rounded">Yuvarlak Hatlı (Rounded)</option>
                            <option value="classy">Şık (Classy)</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Köşe Stili</label>
                        <select name="cornersType" id="qrCornersType" class="form-control">
                            <option value="square">Klasik (Kare)</option>
                            <option value="dot">Noktalı (Dot)</option>
                            <option value="extra-rounded">Ekstra Yuvarlak</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 16px; margin-top: 16px;">
                    <button type="submit" class="qr-btn qr-btn-primary" style="flex:1; padding:12px; font-size:15px;">
                        Sisteme Kaydet
                    </button>
                    <button type="button" class="qr-btn" onclick="downloadLivePreview()" style="flex:1; padding:12px; font-size:15px; background:var(--input-bg); border:1px solid var(--border); color:var(--text);">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:6px; vertical-align:text-bottom;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        Hemen İndir
                    </button>
                </div>
            </form>
        </div>

        <!-- PREVIEW -->
        <div class="q-card">
            <h3>Canlı Önizleme</h3>
            <div class="qr-preview-box">
                <div id="liveQrCode"></div>
                <div style="font-size:12px; color:#6b7280; text-align:center;">Önizleme anında güncellenir.</div>
            </div>
        </div>
    </div>

    <!-- SAVED QR CODES -->
    <?php if(!empty($qrs)): ?>
        <h2 style="font-size:20px; margin-bottom:20px;">Geçmiş QR Kodlarınız</h2>
        <div class="qr-list">
            <?php foreach($qrs as $qr): ?>
                <div class="qr-item">
                    <div class="qr-item-title" title="<?= View::escape($qr['title']) ?>"><?= View::escape($qr['title']) ?></div>
                    <div class="qr-item-val" title="<?= View::escape($qr['value']) ?>"><?= View::escape($qr['value']) ?></div>
                    
                    <div class="qr-item-box" id="saved_qr_<?= $qr['id'] ?>"></div>

                    <div class="qr-item-actions">
                        <button class="qr-btn qr-btn-primary" onclick="downloadSavedQR('<?= $qr['id'] ?>', '<?= View::escape($qr['title']) ?>')">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            İndir
                        </button>
                        <form method="POST" style="flex:1;" onsubmit="return confirm('Bu QR kodu silmek istediğinize emin misiniz?');">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $qr['id'] ?>">
                            <button type="submit" class="qr-btn qr-btn-danger" style="width:100%;">Sil</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Load qr-code-styling from CDN -->
<script src="https://cdn.jsdelivr.net/npm/qr-code-styling@1.5.0/lib/qr-code-styling.js"></script>

<script>
// Keep references to all instances so we can download them later
const qrInstances = {};

document.addEventListener('DOMContentLoaded', function() {
    if (typeof QRCodeStyling === 'undefined') {
        return;
    }

    // 1. Live Preview Logic
    window.liveQr = new QRCodeStyling({
        width: 180,
        height: 180,
        data: "https://innomis.tr",
        dotsOptions: { color: "#000000", type: "square" },
        backgroundOptions: { color: "#ffffff" },
        cornersSquareOptions: { type: "square" },
        imageOptions: { crossOrigin: "anonymous", margin: 4 }
    });
    
    const liveBox = document.getElementById('liveQrCode');
    window.liveQr.append(liveBox);

    const valInput = document.getElementById('qrValueInput');
    const darkInput = document.getElementById('qrColorDark');
    const lightInput = document.getElementById('qrColorLight');
    const logoFile = document.getElementById('qrLogoFile');
    const dotsInput = document.getElementById('qrDotsType');
    const cornersInput = document.getElementById('qrCornersType');

    let currentLogoDataUrl = '';

    function updatePreview() {
        const val = valInput.value.trim() || "https://innomis.tr";
        window.liveQr.update({
            data: val,
            image: currentLogoDataUrl,
            dotsOptions: {
                color: darkInput.value,
                type: dotsInput.value
            },
            backgroundOptions: {
                color: lightInput.value
            },
            cornersSquareOptions: {
                type: cornersInput.value,
                color: darkInput.value
            }
        });
    }

    logoFile.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                currentLogoDataUrl = evt.target.result;
                updatePreview();
            }
            reader.readAsDataURL(file);
        } else {
            currentLogoDataUrl = '';
            updatePreview();
        }
    });

    valInput.addEventListener('input', updatePreview);
    darkInput.addEventListener('input', updatePreview);
    lightInput.addEventListener('input', updatePreview);
    dotsInput.addEventListener('change', updatePreview);
    cornersInput.addEventListener('change', updatePreview);

    // 2. Render Saved QRs
    const savedQrsData = <?= json_encode($qrs ?? []) ?>;
    savedQrsData.forEach(qr => {
        const qrId = 'saved_qr_' + qr.id;
        const box = document.getElementById(qrId);
        if (box) {
            const instance = new QRCodeStyling({
                width: 150,
                height: 150,
                data: qr.value || "https://innomis.tr",
                image: qr.logoUrl || "",
                dotsOptions: { 
                    color: qr.colorDark || "#000000", 
                    type: qr.dotsType || "square" 
                },
                backgroundOptions: { 
                    color: qr.colorLight || "#ffffff" 
                },
                cornersSquareOptions: { 
                    type: qr.cornersType || "square",
                    color: qr.colorDark || "#000000"
                },
                imageOptions: { crossOrigin: "anonymous", margin: 4 }
            });
            instance.append(box);
            qrInstances[qr.id] = instance;
        }
    });
});

// Download Logic using the library's built-in download method
window.downloadSavedQR = function downloadSavedQR(id, title) {
    const instance = qrInstances[id];
    if (instance) {
        let safeTitle = title.replace(/[^a-z0-9]/gi, '_').toLowerCase();
        instance.download({ name: 'qr_' + safeTitle, extension: "png" });
    } else {
        alert("QR Kod örneği bulunamadı.");
    }
};

window.downloadLivePreview = function downloadLivePreview() {
    if (window.liveQr) {
        const valInput = document.getElementById('qrValueInput');
        const titleInput = document.querySelector('input[name="title"]');
        let rawTitle = titleInput ? titleInput.value.trim() : "";
        if (!rawTitle) rawTitle = "onizleme";
        let safeTitle = rawTitle.replace(/[^a-z0-9]/gi, '_').toLowerCase();
        
        window.liveQr.download({ name: 'qr_' + safeTitle, extension: "png" });
    } else {
        alert("Canlı önizleme henüz hazır değil.");
    }
};
</script>
