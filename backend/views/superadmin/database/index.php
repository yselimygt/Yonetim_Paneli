<?php
$pageTitle = 'Veritabanı Yedekleri';
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<style>
/* Modern Tabs */
.settings-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 24px;
    background: var(--card);
    padding: 8px;
    border-radius: 12px;
    border: 1px solid var(--border);
    overflow-x: auto;
    scrollbar-width: none;
}
.settings-tabs::-webkit-scrollbar { display: none; }

.tab-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: transparent;
    border: none;
    border-radius: 8px;
    color: var(--muted);
    font-weight: 500;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}
.tab-btn:hover {
    color: var(--text);
    background: rgba(128,128,128,0.05);
}
.tab-btn.active {
    background: var(--accent);
    color: #000;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0,188,212,0.25);
}
.tab-btn svg {
    width: 16px;
    height: 16px;
    transition: transform 0.2s;
}
.tab-btn.active svg {
    transform: scale(1.1);
}
</style>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
    <div style="flex: 1; min-width: 200px;">
        <h2>Veritabanı Yedekleri</h2>
        <p style="color:var(--muted); font-size:13px; margin-top:4px;">Sistem veritabanını yedekleyin ve otomatik yedekleme görevlerini yönetin.</p>
    </div>
</div>

<?php if ($flash && $flash['type'] === 'success'): ?>
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>
<?php if ($flash && $flash['type'] === 'error'): ?>
    <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>

<!-- Tabs -->
<div class="settings-tabs" style="margin-bottom: 24px;">
    <button class="tab-btn active" onclick="switchDbTab(this, 'manual')">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
        <span class="tab-label">Manuel Yedekleme</span>
    </button>
    <button class="tab-btn" onclick="switchDbTab(this, 'auto')">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        <span class="tab-label">Otomatik Yedekleme</span>
    </button>
</div>

<!-- MANUEL YEDEKLEME -->
<div class="tab-pane active" id="tab-manual" style="display: block;">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <h3 style="font-size: 16px; font-weight: 600; margin:0;">Mevcut Yedekler</h3>
            <form action="<?= BASE_URL ?>/superadmin/database/backup" method="POST" style="margin:0;">
                <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Yeni Yedek Al
                </button>
            </form>
        </div>
        
        <div class="card-body">
            <?php if (empty($backups)): ?>
                <div style="text-align: center; padding: 40px 20px; color: var(--muted);">
                    <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 16px; opacity: 0.5;"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                    <p style="font-size: 14px;">Henüz sunucuda kayıtlı bir veritabanı yedeği bulunmuyor.</p>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--border); color: var(--muted); font-size: 13px; font-weight: 600;">Dosya Adı</th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--border); color: var(--muted); font-size: 13px; font-weight: 600;">Tarih</th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--border); color: var(--muted); font-size: 13px; font-weight: 600;">Boyut</th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--border); color: var(--muted); font-size: 13px; font-weight: 600; text-align: right;">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($backups as $b): ?>
                            <tr>
                                <td style="padding: 12px 16px; border-bottom: 1px solid rgba(128,128,128,0.1); font-size: 14px; color: var(--text);">
                                    <div style="display: flex; align-items: center; gap: 8px; white-space: nowrap;">
                                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--muted); flex-shrink: 0;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                        <span style="overflow: hidden; text-overflow: ellipsis; max-width: 200px;"><?= htmlspecialchars($b['name']) ?></span>
                                    </div>
                                </td>
                                <td style="padding: 12px 16px; border-bottom: 1px solid rgba(128,128,128,0.1); font-size: 14px; color: var(--text); white-space: nowrap;"><?= date('d.m.Y H:i:s', $b['time']) ?></td>
                                <td style="padding: 12px 16px; border-bottom: 1px solid rgba(128,128,128,0.1); font-size: 14px; color: var(--text); white-space: nowrap;"><?= number_format($b['size'] / 1024 / 1024, 2) ?> MB</td>
                                <td style="padding: 12px 16px; border-bottom: 1px solid rgba(128,128,128,0.1); text-align: right; white-space: nowrap;">
                                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                        <a href="<?= BASE_URL ?>/superadmin/database/download?file=<?= urlencode($b['name']) ?>" class="btn" style="padding: 6px 12px; font-size: 13px; color: #0ea5e9; background: rgba(14, 165, 233, 0.1); border: 1px solid rgba(14, 165, 233, 0.2); text-decoration: none;">
                                            İndir
                                        </a>
                                        <form action="<?= BASE_URL ?>/superadmin/database/delete" method="POST" style="margin:0;" onsubmit="return confirm('Bu yedeği silmek istediğinize emin misiniz?');">
                                            <input type="hidden" name="file" value="<?= htmlspecialchars($b['name']) ?>">
                                            <button type="submit" class="btn" style="padding: 6px 12px; font-size: 13px; color: #ef4444; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2);">
                                                Sil
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- OTOMATIK YEDEKLEME -->
<div class="tab-pane" id="tab-auto" style="display: none;">
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 16px; font-weight: 600; margin:0;">Otomatik Yedekleme (Cron) Yapılandırması</h3>
        </div>
        <div class="card-body">
            <p style="color: var(--muted); font-size: 14px; margin-bottom: 24px;">Sunucunuzdaki veya dış bir servis (cron-job.org vb.) aracılığıyla otomatik yedekleme alınmasını sağlayabilirsiniz.</p>
            
            <form action="<?= BASE_URL ?>/superadmin/database/settings" method="POST">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="check-group" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="enabled" value="1" <?= !empty($settings['enabled']) ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: var(--accent);">
                        <span style="font-size: 14px; font-weight: 500; color: var(--text);">Otomatik yedeklemeyi aktifleştir</span>
                    </label>
                </div>

                <div class="form-row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text);">Kaç Günlük Yedek Saklansın?</label>
                        <input type="number" name="limit" class="form-control" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg); color: var(--text);" value="<?= htmlspecialchars($settings['limit'] ?? 7) ?>" min="1" max="90">
                        <div class="form-hint" style="font-size: 12px; color: var(--muted); margin-top: 6px;">Daha eski yedekler diskte yer kaplamaması için otomatik silinir.</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text);">Güvenlik Token'ı</label>
                        <input type="text" name="token" class="form-control" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border); background: rgba(0,0,0,0.1); color: var(--text);" value="<?= htmlspecialchars($settings['token'] ?? '') ?>" placeholder="Otomatik oluşturulacaktır. Sadece 'Kaydet'e basınız." readonly>
                        <div class="form-hint" style="font-size: 12px; color: var(--muted); margin-top: 6px;">Cron URL'sini yetkisiz kişilerin tetiklemesini engeller.</div>
                    </div>
                </div>

                <div style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">Ayarları Kaydet</button>
                </div>
            </form>

            <?php if (!empty($settings['enabled']) && !empty($settings['token'])): ?>
                <div style="margin-top: 32px; padding: 20px; background: rgba(0, 188, 212, 0.05); border: 1px solid rgba(0, 188, 212, 0.2); border-radius: 12px;">
                    <h4 style="font-weight: 600; font-size: 15px; color: var(--accent); margin:0 0 8px 0;">Cron Job URL</h4>
                    <p style="font-size: 13px; color: var(--muted); margin:0 0 16px 0;">Aşağıdaki URL'yi sunucunuzun cron yöneticisine veya cron-job.org gibi bir servise günlük/haftalık olarak çalışacak şekilde ekleyin:</p>
                    <div style="display: flex; align-items: stretch; gap: 8px; flex-wrap: wrap;">
                        <input type="text" id="cronUrl" class="form-control" readonly value="<?= rtrim(APP_URL ?? BASE_URL, '/') . '/api/cron/auto-backup?token=' . htmlspecialchars($settings['token']) ?>" style="flex: 1; min-width: 200px; padding: 12px 16px; border-radius: 8px; border: 1px solid var(--border); font-family: monospace; font-size: 12px; background: var(--bg); color: var(--text);">
                        <button type="button" class="btn" style="padding: 12px 20px; background: var(--card); border: 1px solid var(--border); color: var(--text); white-space: nowrap; flex-shrink: 0;" onclick="copyCron()">Kopyala</button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function switchDbTab(btn, tabId) {
    document.querySelectorAll('.tab-btn').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(c => c.style.display = 'none');
    
    btn.classList.add('active');
    document.getElementById('tab-' + tabId).style.display = 'block';
}

function copyCron() {
    const urlInput = document.getElementById('cronUrl');
    urlInput.select();
    document.execCommand('copy');
    alert('URL panoya kopyalandı!');
}
</script>
