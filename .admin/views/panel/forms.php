<?php 
$pageTitle = 'Form Yönetimi'; 
$activeSection = 'forms';

// Helpers
function getBadge($isActive, $config) {
    $conf = json_decode($config ?? '{}', true) ?: [];
    $isPublic = $conf['settings']['is_public'] ?? false;
    
    $html = '';
    if ($isActive) {
        $html .= '<span style="background:rgba(16,185,129,0.1); color:#10b981; border:1px solid rgba(16,185,129,0.2); border-radius:4px; padding:2px 8px; font-size:10px; font-weight:600; text-transform:uppercase;">AKTİF</span>';
    } else {
        $html .= '<span style="background:rgba(239,68,68,0.1); color:#ef4444; border:1px solid rgba(239,68,68,0.2); border-radius:4px; padding:2px 8px; font-size:10px; font-weight:600; text-transform:uppercase;">PASİF</span>';
    }
    
    if ($isPublic) {
        $html .= '<span style="background:rgba(16,185,129,0.1); color:#10b981; border:1px solid rgba(16,185,129,0.2); border-radius:4px; padding:2px 8px; font-size:10px; font-weight:600; margin-left:8px;"><span style="display:inline-block;width:6px;height:6px;background:#10b981;border-radius:50%;margin-right:4px;"></span>YAYINDA</span>';
    }
    
    return $html;
}
?>

<style>
/* Header */
.forms-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}
.forms-title h1 {
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 4px 0;
}
.forms-title p {
    color: var(--muted);
    font-size: 13px;
    margin: 0;
}

/* Tabs */
.forms-tabs {
    display: flex;
    gap: 24px;
    border-bottom: 1px solid var(--border);
    margin-bottom: 24px;
}
.forms-tabs button {
    background: transparent;
    border: none;
    color: var(--muted);
    font-size: 14px;
    font-weight: 500;
    padding: 12px 0;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
}
.forms-tabs button.active {
    color: var(--accent);
    border-bottom-color: var(--accent);
}
.forms-tab-content { display: none; }
.forms-tab-content.active { display: block; }

/* Grid */
.forms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 16px;
}

/* Card */
.f-card {
    background: var(--card);
    border: 1px solid var(--border, #2d3142);
    border-radius: 12px;
    padding: 20px;
    position: relative;
}
.f-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}
.f-card-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--text);
}
.f-card-desc {
    font-size: 13px;
    color: var(--muted);
    margin-bottom: 24px;
}
.f-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid var(--border);
    padding-top: 16px;
}
.f-resp-count {
    font-size: 12px;
    color: var(--muted);
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.05);
    padding: 4px 10px;
    border-radius: 6px;
}
.f-edit-link {
    color: var(--accent);
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
}
.f-edit-link:hover { text-decoration: underline; }

.f-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}
.f-actions button, .f-actions a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--input-bg);
    border: 1px solid var(--border);
    color: var(--text);
    border-radius: 8px;
    padding: 8px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
}
.f-actions button:hover, .f-actions a:hover { 
    background: var(--border);
    color: var(--text);
}
.f-actions button svg, .f-actions a svg {
    color: var(--muted);
}
.f-actions button:hover svg, .f-actions a:hover svg {
    color: var(--text);
}

/* Empty state */
.empty-tpl {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 48px;
    text-align: center;
}
.empty-tpl svg { color: var(--muted); margin-bottom: 16px; }
.empty-tpl h3 { font-size: 16px; font-weight: 600; margin:0 0 8px 0; }
.empty-tpl p { font-size: 13px; color: var(--muted); margin:0; }

/* Modal */
.f-modal {
    display: none;
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: var(--modal-overlay);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
.f-modal-content {
    background: var(--card-alt);
    border: 1px solid var(--border);
    border-radius: 16px;
    width: 90%;
    max-width: 500px;
    padding: 32px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.5);
}
.f-modal-content h2 { margin: 0 0 24px 0; font-size: 20px; }

.fm-label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.fm-input, .fm-textarea, .fm-select {
    width: 100%;
    background: var(--input-bg);
    border: 1px solid var(--border);
    color: var(--text);
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 20px;
    font-family: inherit;
}
.fm-input:focus, .fm-textarea:focus, .fm-select:focus {
    border-color: var(--accent);
    outline: none;
}
.fm-notice {
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 8px;
    padding: 12px;
    font-size: 12px;
    color: #93c5fd;
    margin-bottom: 24px;
    line-height: 1.5;
}
.fm-notice strong { color: #60a5fa; }
.fm-footer {
    display: flex;
    justify-content: space-between;
}
</style>

<div class="forms-header">
    <div class="forms-title">
        <h1>Form Yönetimi</h1>
        <p>Başvurular, anketler ve topluluk veri havuzu.</p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('createModal').style.display='flex'">
        + Yeni Form Oluştur
    </button>
</div>

<div class="forms-tabs">
    <button class="active" onclick="switchFTab('real')">Gerçek Formlar</button>
    <button onclick="switchFTab('tpl')">Şablonlarım</button>
</div>

<div id="tab-real" class="forms-tab-content active">
    <div class="forms-grid">
        <?php foreach ($realForms as $app): ?>
        <div class="f-card">
            <div class="f-card-header">
                <div><?= getBadge($app['is_active'], $app['config']) ?></div>
                <div class="f-actions">
                    <!-- Toggle Template -->
                    <form method="POST" action="<?= BASE_URL ?>/panel/<?= View::escape($currentDept['slug']) ?>/forms/toggle-template" style="margin:0;">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="hidden" name="app_id" value="<?= $app['id'] ?>">
                        <input type="hidden" name="is_template" value="1">
                        <button type="submit" title="Genel (Şablon) Yap">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 19h14M5 15h14M12 3v12m-4-4l4 4 4-4"/></svg>
                        </button>
                    </form>
                    
                    <?php 
                        $conf = json_decode($app['config'] ?? '{}', true) ?: [];
                        $isPublic = $conf['settings']['is_public'] ?? false;
                        if ($isPublic) {
                            $publicLink = BASE_URL . '/f/' . View::escape($app['slug']);
                        } else {
                            $publicLink = BASE_URL . '/f/' . View::escape($currentDept['slug']) . '/' . View::escape($app['slug']);
                        }
                    ?>
                    <a href="<?= $publicLink ?>" target="_blank" title="Yayına Git">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </a>
                </div>
            </div>
            <div class="f-card-title"><?= View::escape($app['name']) ?></div>
            <div class="f-card-desc"><?= empty($app['description']) ? 'Açıklama belirtilmedi.' : View::escape($app['description']) ?></div>
            
            <div class="f-card-footer">
                <a href="<?= BASE_URL ?>/panel/<?= View::escape($currentDept['slug']) ?>/apps/<?= View::escape($app['slug']) ?>?tab=responses" class="f-resp-count" style="text-decoration:none; transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'" title="Yanıtları İncele">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                    <?= $app['response_count'] ?> Yanıt
                </a>
                <a href="<?= BASE_URL ?>/panel/<?= View::escape($currentDept['slug']) ?>/apps/<?= View::escape($app['slug']) ?>" class="f-edit-link">Düzenle &rarr;</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="tab-tpl" class="forms-tab-content">
    <?php if(empty($templates)): ?>
    <div class="empty-tpl">
        <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
        </svg>
        <h3>Henüz şablon kaydedilmedi</h3>
        <p>Formları düzenlerken 'Şablon Olarak Kaydet' diyerek buraya ekleyebilirsiniz.</p>
    </div>
    <?php else: ?>
    <div class="forms-grid">
        <?php foreach ($templates as $app): ?>
        <div class="f-card">
            <div class="f-card-header">
                <div><span style="background:rgba(59,130,246,0.1); color:#3b82f6; border:1px solid rgba(59,130,246,0.2); border-radius:4px; padding:2px 8px; font-size:10px; font-weight:600;">ŞABLON</span></div>
                <div class="f-actions">
                    <form method="POST" action="<?= BASE_URL ?>/panel/<?= View::escape($currentDept['slug']) ?>/forms/toggle-template" style="margin:0;">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="hidden" name="app_id" value="<?= $app['id'] ?>">
                        <input type="hidden" name="is_template" value="0">
                        <button type="submit" title="Genel (Şablon) Özelliğini Kaldır">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </form>
                    <a href="<?= BASE_URL ?>/f/<?= View::escape($app['slug']) ?>" target="_blank" title="Genel Form Yayına Git">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </a>
                </div>
            </div>
            <div class="f-card-title"><?= View::escape($app['name']) ?></div>
            <div class="f-card-desc"><?= empty($app['description']) ? 'Açıklama belirtilmedi.' : View::escape($app['description']) ?></div>
            <div class="f-card-footer">
                <div class="f-resp-count">0 Yanıt</div>
                <a href="<?= BASE_URL ?>/panel/<?= View::escape($currentDept['slug']) ?>/apps/<?= View::escape($app['slug']) ?>" class="f-edit-link">Genel Formu Düzenle &rarr;</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Modal -->
<div id="createModal" class="f-modal">
    <div class="f-modal-content">
        <h2>Yeni Form Oluştur</h2>
        <form method="POST" action="<?= BASE_URL ?>/panel/<?= View::escape($currentDept['slug']) ?>/forms/create">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <label class="fm-label">FORM BAŞLIĞI</label>
            <input type="text" name="title" class="fm-input" placeholder="ör: 2024 Yaz Kampı Başvurusu" required>
            
            <label class="fm-label">AÇIKLAMA (OPSİYONEL)</label>
            <textarea name="description" class="fm-textarea" rows="3" placeholder="Katılımcılara form hakkında kısa bir bilgi verin..."></textarea>
            
            <label class="fm-label">ŞABLONDAN ÜRET (OPSİYONEL)</label>
            <select name="template_id" class="fm-select">
                <option value="0">Sıfırdan Başla (Boş Form)</option>
                <?php foreach ($templates as $tpl): ?>
                    <option value="<?= $tpl['id'] ?>"><?= View::escape($tpl['name']) ?></option>
                <?php endforeach; ?>
            </select>
            
            <div class="fm-notice">
                <strong>Not:</strong> Şablon seçerseniz o taslaktaki tüm sorular otomatik kopyalanır. Yoksa ileri düzey form alanlarını bir sonraki adımda ekleyebilirsiniz.
            </div>
            
            <div class="fm-footer">
                <button type="button" class="btn btn-outline" style="border-color:var(--border); color:var(--muted);" onclick="document.getElementById('createModal').style.display='none'">İptal</button>
                <button type="submit" class="btn btn-primary" style="background:#3b82f6; border:none;">Formu Başlat</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchFTab(tab) {
    document.querySelectorAll('.forms-tabs button').forEach(b => b.classList.remove('active'));
    document.querySelector(`.forms-tabs button[onclick="switchFTab('${tab}')"]`).classList.add('active');
    
    document.querySelectorAll('.forms-tab-content').forEach(c => c.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
}
</script>
