<style>
.shortener-container { max-width: 1000px; margin: 0 auto; }

.s-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 24px; margin-bottom: 24px; }
.s-card h3 { margin-top: 0; margin-bottom: 20px; font-size: 18px; color: var(--text); }

.form-group { margin-bottom: 16px; }
.form-label { display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px; color: var(--muted); }
.form-control { width: 100%; padding: 12px 14px; background: var(--input-bg); border: 1px solid var(--border); color: var(--text); border-radius: 8px; font-size: 14px; }
.form-control:focus { outline: none; border-color: var(--accent); }

.input-group { display: flex; align-items: stretch; }
.input-group-text { padding: 12px 14px; background: var(--border); border: 1px solid var(--border); border-right: none; border-radius: 8px 0 0 8px; color: var(--muted); font-size: 14px; display: flex; align-items: center; }
.input-group .form-control { border-radius: 0 8px 8px 0; }

.s-btn { padding: 12px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; text-align: center; border: 1px solid transparent; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; }
.s-btn-primary { background: var(--accent); color: #0a0a0a; }
.s-btn-primary:hover { opacity: 0.9; }

/* Table */
.link-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
.link-table th, .link-table td { padding: 14px; text-align: left; border-bottom: 1px solid var(--border); }
.link-table th { font-weight: 500; color: var(--muted); font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
.link-table td { font-size: 14px; }
.link-table tr:hover { background: rgba(255,255,255,0.02); }

.copy-btn { background: var(--input-bg); border: 1px solid var(--border); color: var(--text); padding: 6px 10px; border-radius: 6px; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 4px; transition: background 0.2s; }
.copy-btn:hover { background: var(--border); }

.delete-btn { color: #ef4444; background: none; border: none; cursor: pointer; font-size: 14px; opacity: 0.7; transition: opacity 0.2s; }
.delete-btn:hover { opacity: 1; }

.badge-clicks { background: rgba(59,130,246,0.1); color: #3b82f6; padding: 4px 10px; border-radius: 20px; font-weight: 600; font-size: 12px; }

@media(max-width: 768px) {
    .shortener-container {
        max-width: 100%;
    }
    .shortener-container > div:first-child {
        margin-bottom: 14px !important;
    }
    .shortener-container h1 {
        font-size: 20px !important;
        line-height: 1.15;
    }
    .shortener-container > div:first-child p {
        display: none;
    }
    .s-card {
        padding: 14px;
        border-radius: 14px;
        margin-bottom: 10px;
    }
    .s-card h3 {
        font-size: 15px;
        margin-bottom: 12px;
    }
    .s-card form > div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
        gap: 0 !important;
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
    .input-group {
        min-width: 0;
    }
    .input-group-text {
        max-width: 45%;
        padding: 9px 8px;
        font-size: 10px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .input-group .form-control {
        min-width: 0;
    }
    .s-btn {
        width: 100%;
        min-height: 40px;
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 13px;
    }
    .link-table,
    .link-table thead,
    .link-table tbody,
    .link-table tr,
    .link-table td {
        display: block;
        width: 100%;
    }
    .link-table {
        margin-top: 0;
        border-collapse: separate;
        border-spacing: 0 10px;
    }
    .link-table thead {
        display: none;
    }
    .link-table tr {
        background: var(--card-alt);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 10px;
    }
    .link-table tr:hover {
        background: var(--card-alt);
    }
    .link-table td {
        border-bottom: 0;
        padding: 4px 0;
        font-size: 12px;
    }
    .link-table td:first-child {
        font-size: 13px;
        line-height: 1.25;
    }
    .link-table td:nth-child(2) {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .link-table td:nth-child(2) a {
        flex: 1;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .link-table td:nth-child(3) {
        display: none;
    }
    .link-table td:nth-child(4) {
        position: absolute;
        right: 50px;
        top: 12px;
        width: auto;
        padding: 0;
    }
    .link-table td:nth-child(5) {
        position: absolute;
        right: 12px;
        top: 9px;
        width: auto;
        padding: 0;
    }
    .link-table tr {
        position: relative;
        padding-right: 92px;
        min-height: 72px;
    }
    .copy-btn {
        width: 30px;
        height: 30px;
        padding: 0;
        justify-content: center;
        margin-left: 0 !important;
        border-radius: 9px;
        flex: 0 0 auto;
    }
    .copy-btn svg {
        width: 13px;
        height: 13px;
    }
    .badge-clicks {
        padding: 4px 8px;
        font-size: 10px;
    }
    .delete-btn {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: rgba(239,68,68,0.1);
    }
    .delete-btn svg {
        width: 15px;
        height: 15px;
    }
}
</style>

<div class="shortener-container">
    <div style="margin-bottom:24px;">
        <h1 style="margin:0; font-size:24px;">Link Kısaltıcı</h1>
        <p style="color:var(--muted); margin:4px 0 0 0;">Uzun bağlantılarınızı kısaltın ve tıklanma istatistiklerini takip edin.</p>
    </div>

    <?php if(!empty($_SESSION['success_msg'])): ?>
        <div style="background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); color:#10b981; padding:12px 16px; border-radius:8px; margin-bottom:24px;">
            <?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
        </div>
    <?php endif; ?>
    <?php if(!empty($_SESSION['flash'])): ?>
        <div style="background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.2); color:#ef4444; padding:12px 16px; border-radius:8px; margin-bottom:24px;">
            <?= $_SESSION['flash']['message']; unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>

    <!-- CREATE FORM -->
    <div class="s-card">
        <h3>Yeni Link Kısalt</h3>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Başlık / Açıklama (Size özel)</label>
                    <input type="text" name="title" class="form-control" placeholder="Örn: Instagram Bio Linki">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Hedef URL (Uzun Link) <span style="color:red">*</span></label>
                    <input type="url" name="url" class="form-control" placeholder="https://cok-uzun-bir-link.com/sayfa/123" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Özel Kısa Kod (Opsiyonel)</label>
                <div class="input-group">
                    <div class="input-group-text"><?= preg_replace('#^https?://#', '', PUBLIC_URL) ?>/go/</div>
                    <input type="text" name="custom_code" class="form-control" placeholder="ozel-isim" pattern="[a-zA-Z0-9_-]+" title="Sadece harf, rakam, tire ve alt çizgi kullanın">
                </div>
                <div style="font-size:12px; color:var(--muted); margin-top:6px;">Eğer boş bırakırsanız sistem otomatik rastgele bir kod üretecektir.</div>
            </div>

            <button type="submit" class="s-btn s-btn-primary" style="margin-top: 8px;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="6" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><line x1="20" y1="4" x2="8.12" y2="15.88"></line><line x1="14.47" y1="14.48" x2="20" y2="20"></line><line x1="8.12" y1="8.12" x2="12" y2="12"></line></svg>
                Linki Kısalt
            </button>
        </form>
    </div>

    <!-- LINKS LIST -->
    <div class="s-card">
        <h3>Kısaltılmış Linkleriniz</h3>
        
        <?php if(empty($links)): ?>
            <div style="text-align:center; padding: 40px 20px; color:var(--muted);">Henüz hiç link kısaltmadınız.</div>
        <?php else: ?>
            <div style="overflow-x:auto;">
                <table class="link-table">
                    <thead>
                        <tr>
                            <th>Başlık</th>
                            <th>Kısa Link</th>
                            <th>Hedef (Uzun) URL</th>
                            <th>Tıklanma</th>
                            <th style="text-align:right;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($links as $link): ?>
                            <?php $fullShortUrl = PUBLIC_URL . '/go/' . $link['code']; ?>
                            <tr>
                                <td style="font-weight:500; color:var(--text);">
                                    <?= View::escape($link['title'] ?: 'İsimsiz Link') ?>
                                    <div style="font-size:11px; color:var(--muted); font-weight:400; margin-top:4px;">
                                        <?= date('d.m.Y', strtotime($link['created_at'])) ?>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?= $fullShortUrl ?>" target="_blank" style="color:var(--accent); text-decoration:none; font-weight:500;">
                                        /go/<?= View::escape($link['code']) ?>
                                    </a>
                                    <button class="copy-btn" onclick="copyToClipboard('<?= $fullShortUrl ?>')" style="margin-left:8px;" title="Kopyala">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                    </button>
                                </td>
                                <td>
                                    <div style="max-width: 250px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:var(--muted);" title="<?= View::escape($link['url']) ?>">
                                        <?= View::escape($link['url']) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-clicks"><?= number_format($link['clicks'] ?? 0) ?></span>
                                </td>
                                <td style="text-align:right;">
                                    <form method="POST" style="display:inline-block;" onsubmit="return confirm('Bu linki silmek istediğinize emin misiniz? (Bağlantı çalışmayı durdurur)');">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="code" value="<?= View::escape($link['code']) ?>">
                                        <button type="submit" class="delete-btn" title="Sil">
                                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
window.copyToClipboard = function copyToClipboard(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(() => {
            alert("Kısa link panoya kopyalandı:\n" + text);
        }).catch(() => {
            fallbackCopy(text);
        });
        return;
    }

    fallbackCopy(text);
};

function fallbackCopy(text) {
    var ta = document.createElement('textarea');
    ta.value = text;
    ta.setAttribute('readonly', '');
    ta.style.position = 'fixed';
    ta.style.opacity = '0';
    document.body.appendChild(ta);
    ta.select();
    document.execCommand('copy');
    document.body.removeChild(ta);
    alert("Kısa link panoya kopyalandı:\n" + text);
}
</script>
