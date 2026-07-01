<?php
// views/modules/event.php
// $physicalUpcoming, $physicalPast
// $onlineUpcoming, $onlinePast
// $canEdit

function renderEventCard($event, $canEdit, $currentDeptId) {
    $title = htmlspecialchars($event['title']);
    $desc = htmlspecialchars($event['description'] ?? '');
    $date = date('d.m.Y', strtotime($event['event_date']));
    $time = $event['event_time'] ? date('H:i', strtotime($event['event_time'])) : '';
    $loc = htmlspecialchars($event['location_or_platform'] ?? '');
    $link = htmlspecialchars($event['link'] ?? '');
    $cat = htmlspecialchars($event['category'] ?? '');
    $img = $event['image_path'] ? BASE_URL . '/public/' . $event['image_path'] : '';
    
    $statusClass = $event['status'] ? 'evt-badge-active' : 'evt-badge-inactive';
    $statusText = $event['status'] ? 'Aktif' : 'Pasif';
    $opacity = $event['status'] ? '1' : '0.65';
    
    $canModify = $canEdit;
    
    $csrf = $_SESSION['csrf_token'] ?? '';
    
    ob_start();
    ?>
    <div class="evt-card" style="opacity: <?= $opacity ?>;">
        <?php if($img): ?>
            <div class="evt-img-wrapper">
                <img src="<?= $img ?>" alt="Görsel" class="evt-img" loading="lazy">
                <div class="evt-img-overlay"></div>
            </div>
        <?php endif; ?>
        
        <div class="evt-content">
            <div class="evt-header">
                <h3 class="evt-title"><?= $title ?></h3>
                <div style="display:flex; flex-direction:column; gap:6px; align-items:flex-end;">
                    <span class="evt-badge <?= $statusClass ?>"><?= $statusText ?></span>
                    <span class="evt-badge" style="background:rgba(139,92,246,0.15); color:#c4b5fd; border:1px solid rgba(139,92,246,0.3);">Ortak</span>
                </div>
            </div>
            
            <div class="evt-details">
                <div class="evt-row">
                    <svg class="evt-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span><?= $date ?> <?= $time ?></span>
                </div>
                <?php if($loc): ?>
                    <div class="evt-row">
                        <svg class="evt-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <?php if($link): ?>
                            <a href="<?= $link ?>" target="_blank" style="color:inherit; text-decoration:underline; cursor:pointer;" title="Harita/Yayın Linkine Git"><?= $loc ?></a>
                        <?php else: ?>
                            <span><?= $loc ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if($cat): ?>
                    <div class="evt-row">
                        <svg class="evt-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        <span><?= $cat ?></span>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if($desc): ?>
                <p class="evt-desc"><?= nl2br($desc) ?></p>
            <?php endif; ?>
            
            <?php if($link): ?>
                <a href="<?= $link ?>" target="_blank" class="evt-link-btn">
                    Bağlantıya Git
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                </a>
            <?php endif; ?>
        </div>
        
        <?php if($canModify): ?>
            <div class="evt-actions">
                <button type="button" class="evt-action-btn" title="Düzenle" onclick='openEventEditModal(<?= json_encode($event, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>)'>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <form method="POST" style="margin:0;">
                    <input type="hidden" name="action" value="toggle_status">
                    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <button type="submit" class="evt-action-btn" title="Durumu Değiştir">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><polyline points="23 20 23 14 17 14"/><path d="M20.49 9A9 9 0 005.64 5.64L1 10M3.51 15A9 9 0 0018.36 18.36L23 14"/></svg>
                    </button>
                </form>
                <form method="POST" style="margin:0;" onsubmit="return confirm('Bu etkinliği silmek istediğinize emin misiniz?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <button type="submit" class="evt-action-btn evt-action-danger" title="Sil">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
?>

<style>
/* PREMIUM EVENT UI */
:root {
    --evt-bg: transparent;
    --evt-card: var(--card);
    --evt-border: var(--border);
    --evt-input: var(--input-bg);
    --evt-text: var(--text);
    --evt-muted: var(--muted);
    --evt-primary: var(--accent);
    --evt-danger: #ef4444;
    --evt-success: #10b981;
}

/* Header */
.evt-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    animation: fadeDown 0.4s ease-out;
    flex-wrap: wrap;
    gap: 16px;
}
.evt-header-title { margin: 0; font-size: 24px; font-weight: 700; color: var(--evt-text); letter-spacing: -0.5px; }
.evt-header-subtitle { color: var(--evt-muted); font-size: 14px; margin: 4px 0 0 0; }

.evt-btn-add {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.evt-btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.35);
}

/* Segmented Tabs */
.evt-tabs-container {
    display: inline-flex;
    background: var(--evt-input);
    padding: 6px;
    border-radius: 12px;
    margin-bottom: 32px;
    border: 1px solid var(--evt-border);
}
.evt-tab {
    background: transparent;
    border: none;
    color: var(--evt-muted);
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    padding: 10px 24px;
    border-radius: 8px;
    transition: all 0.3s;
}
.evt-tab:hover { color: var(--evt-text); }
.evt-tab.active { background: var(--card); color: var(--evt-text); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

/* Grid & Cards */
.evt-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 24px;
    margin-bottom: 48px;
}

.evt-card {
    background: var(--evt-card);
    border: 1px solid var(--evt-border);
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    animation: fadeUp 0.5s ease-out backwards;
    display: flex;
    flex-direction: column;
}
.evt-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 32px rgba(0,0,0,0.15);
    border-color: rgba(255,255,255,0.1);
}

.evt-img-wrapper {
    width: 100%;
    height: 180px;
    min-height: 180px;
    position: relative;
    overflow: hidden;
    background: #1a1a1a;
    flex-shrink: 0;
}
.evt-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.6s ease;
}
.evt-card:hover .evt-img { transform: scale(1.08); }
.evt-img-overlay {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 80px;
    background: linear-gradient(to top, var(--evt-card), transparent);
}

.evt-content {
    padding: 24px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.evt-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 16px;
}
.evt-title {
    font-size: 19px;
    font-weight: 700;
    color: var(--evt-text);
    margin: 0;
    line-height: 1.4;
    letter-spacing: -0.3px;
}
.evt-badge {
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    flex-shrink: 0;
}
.evt-badge-active { background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
.evt-badge-inactive { background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }

.evt-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}
.evt-row {
    font-size: 13px;
    color: var(--evt-muted);
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
}
.evt-icon { width: 16px; height: 16px; opacity: 0.7; color: var(--evt-text); }

.evt-desc {
    font-size: 14px;
    color: var(--evt-text);
    opacity: 0.85;
    line-height: 1.6;
    margin-bottom: 24px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.evt-link-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: var(--evt-input);
    color: var(--evt-text);
    border: 1px solid var(--evt-border);
    padding: 12px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 13px;
    text-decoration: none;
    transition: all 0.3s;
    margin-top: auto;
}
.evt-link-btn:hover {
    background: var(--evt-primary);
    border-color: var(--evt-primary);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(var(--accent-rgb, 59, 130, 246), 0.25);
}

.evt-actions {
    position: absolute;
    top: 12px; right: 12px;
    display: flex;
    gap: 8px;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    padding: 6px;
    border-radius: 12px;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s;
    border: 1px solid rgba(255,255,255,0.1);
}
.evt-card:hover .evt-actions { opacity: 1; transform: translateY(0); }

.evt-action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    background: transparent;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}
.evt-action-btn:hover { background: rgba(255,255,255,0.2); }
.evt-action-danger:hover { background: var(--evt-danger); }
.evt-action-btn svg { width: 16px; height: 16px; }

/* Section Title */
.section-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--evt-text);
    margin: 32px 0 20px 0;
    display: flex;
    align-items: center;
    gap: 12px;
}
.section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(to right, var(--evt-border), transparent);
}

/* Modal Premium */
.modal-overlay {
    position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    display: flex; justify-content: center; align-items: center;
    z-index: 1000; opacity: 0; pointer-events: none;
    transition: opacity 0.3s ease;
}
.modal-overlay.active { opacity: 1; pointer-events: auto; }
.modal-content {
    background: var(--card);
    width: 100%; max-width: 800px;
    border-radius: 20px;
    border: 1px solid var(--evt-border);
    padding: 32px;
    transform: translateY(30px) scale(0.95);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 24px 48px rgba(0,0,0,0.3);
}
.modal-overlay.active .modal-content { transform: translateY(0) scale(1); }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.modal-header h3 { margin: 0; font-size: 22px; font-weight: 700; color: var(--evt-text); }
.close-modal { background: var(--evt-input); border: none; color: var(--evt-text); width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s; }
.close-modal:hover { background: var(--evt-border); }

.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--evt-text); opacity: 0.9;}
.form-control {
    width: 100%; padding: 12px 16px; border-radius: 10px;
    background: var(--evt-input); border: 1px solid var(--evt-border);
    color: var(--evt-text); font-family: inherit; font-size: 14px;
    transition: all 0.2s;
}
.form-control:focus { border-color: var(--evt-primary); box-shadow: 0 0 0 3px rgba(var(--accent-rgb, 59, 130, 246), 0.15); outline: none; background: var(--card); }

.evt-btn-submit {
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(16,185,129,0.2);
    margin-top: 8px;
}
.evt-btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(16,185,129,0.3); }

/* Animations */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeDown {
    from { opacity: 0; transform: translateY(-15px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: var(--evt-input);
    border: 1px dashed var(--evt-border);
    border-radius: 16px;
    color: var(--evt-muted);
}
.empty-state svg { opacity: 0.5; margin-bottom: 16px; }
.empty-state h4 { margin: 0 0 8px 0; color: var(--evt-text); font-size: 18px; }
</style>

<div class="evt-header-bar">
    <div>
        <h2 class="evt-header-title">Etkinlik Yönetimi</h2>
        <p class="evt-header-subtitle">Tüm departmanlarda ortak görünen fiziksel ve online etkinlikleri planlayın.</p>
    </div>
    <?php if($canEdit): ?>
        <button type="button" class="evt-btn-add" onclick="openEventModal()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Yeni Etkinlik
        </button>
    <?php endif; ?>
</div>

<div class="evt-tabs-container">
    <button class="evt-tab active" onclick="switchTab('physical')">Fiziksel Etkinlikler</button>
    <button class="evt-tab" onclick="switchTab('online')">Online Etkinlikler</button>
    <button class="evt-tab" onclick="switchTab('categorized')">Kategorilere Göre</button>
</div>

<!-- PHYSICAL TAB -->
<div id="tab-physical" class="evt-tab-content">
    <?php if(!empty($physicalUpcoming)): ?>
        <h3 class="section-title">Yaklaşan Etkinlikler</h3>
        <div class="evt-grid">
            <?php foreach($physicalUpcoming as $ev) echo renderEventCard($ev, $canEdit, $currentDeptId); ?>
        </div>
    <?php endif; ?>
    
    <?php if(!empty($physicalPast)): ?>
        <h3 class="section-title">Geçmiş Etkinlikler</h3>
        <div class="evt-grid">
            <?php foreach($physicalPast as $ev) echo renderEventCard($ev, $canEdit, $currentDeptId); ?>
        </div>
    <?php endif; ?>
    
    <?php if(empty($physicalUpcoming) && empty($physicalPast)): ?>
        <div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <h4>Kayıtlı Etkinlik Yok</h4>
            <p style="margin:0; font-size:14px;">Henüz planlanmış bir fiziksel etkinlik bulunmuyor.</p>
        </div>
    <?php endif; ?>
</div>

<!-- ONLINE TAB -->
<div id="tab-online" class="evt-tab-content" style="display:none;">
    <?php if(!empty($onlineUpcoming)): ?>
        <h3 class="section-title">Yaklaşan Etkinlikler</h3>
        <div class="evt-grid">
            <?php foreach($onlineUpcoming as $ev) echo renderEventCard($ev, $canEdit, $currentDeptId); ?>
        </div>
    <?php endif; ?>
    
    <?php if(!empty($onlinePast)): ?>
        <h3 class="section-title">Geçmiş Etkinlikler</h3>
        <div class="evt-grid">
            <?php foreach($onlinePast as $ev) echo renderEventCard($ev, $canEdit, $currentDeptId); ?>
        </div>
    <?php endif; ?>
    
    <?php if(empty($onlineUpcoming) && empty($onlinePast)): ?>
        <div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            <h4>Kayıtlı Etkinlik Yok</h4>
            <p style="margin:0; font-size:14px;">Henüz planlanmış bir online etkinlik bulunmuyor.</p>
        </div>
    <?php endif; ?>
</div>

<!-- CATEGORIZED TAB -->
<div id="tab-categorized" class="evt-tab-content" style="display:none;">
    <?php if(!empty($categorizedEvents)): ?>
        <?php foreach($categorizedEvents as $catName => $catEvents): ?>
            <h3 class="section-title"><?= htmlspecialchars($catName) ?></h3>
            <div class="evt-grid">
                <?php foreach($catEvents as $ev) echo renderEventCard($ev, $canEdit, $currentDeptId); ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <h4>Kayıtlı Etkinlik Yok</h4>
            <p style="margin:0; font-size:14px;">Henüz planlanmış bir etkinlik bulunmuyor.</p>
        </div>
    <?php endif; ?>
</div>


<?php if($canEdit): ?>
<!-- Add Event Modal -->
<div class="modal-overlay" id="evtModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Yeni Etkinlik Oluştur</h3>
            <button type="button" class="close-modal" onclick="closeEventModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" enctype="multipart/form-data" id="evtForm">
            <input type="hidden" name="action" id="evtAction" value="add">
            <input type="hidden" name="event_id" id="evtId" value="">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <div style="display:flex; gap:16px;">
                <div class="form-group" style="flex:1;">
                    <label>Etkinlik Tipi</label>
                    <select name="type" id="evtType" class="form-control" onchange="toggleEventFields()">
                        <option value="physical">Fiziksel Etkinlik</option>
                        <option value="online">Online Etkinlik</option>
                    </select>
                </div>
                
                <div class="form-group" id="grpCategory" style="flex:1;">
                    <label>Kategori</label>
                    <select name="category" id="selCategory" class="form-control">
                        <option value="">Kategori Seçiniz</option>
                        <option value="Toplantı">Toplantı</option>
                        <option value="Eğitim">Eğitim</option>
                        <option value="Konferans / Seminer">Konferans / Seminer</option>
                        <option value="Webinar">Webinar</option>
                        <option value="Parti / Eğlence">Parti / Eğlence</option>
                        <option value="Yemek / İkram">Yemek / İkram</option>
                        <option value="Spor / Aktivite">Spor / Aktivite</option>
                        <option value="Networking">Networking</option>
                        <option value="Kültür / Sanat">Kültür / Sanat</option>
                        <option value="Diğer">Diğer</option>
                    </select>
                </div>
            </div>

            <input type="hidden" name="is_global" id="evtIsGlobal" value="1">
            <div style="padding:12px 14px;margin-bottom:16px;border:1px solid rgba(139,92,246,0.3);border-radius:10px;background:rgba(139,92,246,0.08);color:var(--evt-text);font-size:13px;">
                Bu etkinlik ortak havuza kaydedilir ve tüm departmanlarda görünür.
            </div>

            <div style="display:flex; gap:16px;">
                <div class="form-group" style="flex:2;">
                    <label>Başlık</label>
                    <input type="text" name="title" class="form-control" required placeholder="Örn: Yıl Sonu Yemeği">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Tarih</label>
                    <input type="date" name="event_date" class="form-control" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Saat</label>
                    <input type="time" name="event_time" class="form-control">
                </div>
            </div>
            
            <div style="display:flex; gap:16px;">
                <div class="form-group" id="grpLocationPhysical" style="flex:1;">
                    <label style="display:flex; justify-content:space-between; align-items:center;">
                        <span>Konum</span>
                        <select id="partnerSelect" style="font-size:12px; padding:2px 4px; border:1px solid var(--evt-border); border-radius:4px; background:var(--bg); color:var(--text); cursor:pointer; max-width: 140px;" onchange="applyPartnerLocation()">
                            <option value="">Anlaşmalı Mekan Seç...</option>
                            <?php if(!empty($partners)): foreach($partners as $p): ?>
                                <option value="<?= htmlspecialchars($p['venue_name']) ?>" data-map="<?= htmlspecialchars($p['map_link'] ?? '') ?>"><?= htmlspecialchars($p['venue_name']) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </label>
                    <input type="text" name="location_or_platform_physical" id="inpLocation" class="form-control" placeholder="Örn: Zorlu PSM, İstanbul">
                </div>
                
                <div class="form-group" id="grpLocationOnline" style="display:none; flex:1;">
                    <label>Platform</label>
                    <select name="location_or_platform_online" id="selPlatform" class="form-control">
                        <option value="">Platform Seçiniz</option>
                        <option value="Zoom">Zoom</option>
                        <option value="Google Meet">Google Meet</option>
                        <option value="Microsoft Teams">Microsoft Teams</option>
                        <option value="YouTube Live">YouTube Live</option>
                        <option value="Instagram Live">Instagram Live</option>
                        <option value="Twitch">Twitch</option>
                        <option value="Discord">Discord</option>
                        <option value="Webex">Webex</option>
                        <option value="Diğer">Diğer</option>
                    </select>
                </div>
                
                <div class="form-group" style="flex:1;">
                    <label id="lblLink">Kayıt / Form Linki</label>
                    <input type="url" name="link" class="form-control" placeholder="https://...">
                </div>
            </div>
            
            <div style="display:flex; gap:16px;">
                <div class="form-group" style="flex:2;">
                    <label>Açıklama</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Etkinlik hakkında detaylı bilgi verin..."></textarea>
                </div>
                
                <div class="form-group" style="flex:1;">
                    <label>Kapak Görseli (İsteğe Bağlı)</label>
                    <input type="file" name="image" class="form-control" accept="image/*" style="padding: 10px; height: auto;">
                    <p style="font-size:11px; color:var(--evt-muted); margin-top:6px;">Önerilen: Yatay, yüksek çözünürlüklü görsel.</p>
                </div>
            </div>
            
            <button type="submit" class="evt-btn-submit">Etkinliği Kaydet</button>
        </form>
    </div>
</div>

<script>
function openEventModal() {
    document.getElementById('evtForm').reset();
    document.getElementById('modalTitle').textContent = 'Yeni Etkinlik Oluştur';
    document.getElementById('evtAction').value = 'add';
    document.getElementById('evtId').value = '';
    document.getElementById('evtType').value = 'physical';
    
    document.getElementById('evtModal').classList.add('active');
    toggleEventFields();
}

function openEventEditModal(evt) {
    document.getElementById('evtForm').reset();
    document.getElementById('modalTitle').textContent = 'Etkinliği Düzenle';
    document.getElementById('evtAction').value = 'edit';
    document.getElementById('evtId').value = evt.id;
    
    const evtType = evt.type;
    document.getElementById('evtType').value = evtType;
    document.querySelector('input[name="title"]').value = evt.title;
    document.querySelector('input[name="event_date"]').value = evt.event_date;
    if(evt.event_time) document.querySelector('input[name="event_time"]').value = evt.event_time;
    
    if (evtType === 'physical') {
        document.getElementById('inpLocation').value = evt.location_or_platform || '';
        document.getElementById('selPlatform').value = '';
    } else {
        document.getElementById('selPlatform').value = evt.location_or_platform || '';
        document.getElementById('inpLocation').value = '';
    }
    
    document.getElementById('selCategory').value = evt.category || '';
    document.getElementById('evtIsGlobal').value = '1';
    document.querySelector('input[name="link"]').value = evt.link || '';
    document.querySelector('textarea[name="description"]').value = evt.description || '';
    
    document.getElementById('evtModal').classList.add('active');
    toggleEventFields();
}

function closeEventModal() {
    document.getElementById('evtModal').classList.remove('active');
}

function toggleEventFields() {
    const type = document.getElementById('evtType').value;
    const grpLocPhysical = document.getElementById('grpLocationPhysical');
    const grpLocOnline = document.getElementById('grpLocationOnline');
    const inpLocation = document.getElementById('inpLocation');
    const selPlatform = document.getElementById('selPlatform');
    const lblLink = document.getElementById('lblLink');
    const grpCat = document.getElementById('grpCategory');
    
    if(type === 'physical') {
        grpLocPhysical.style.display = 'block';
        grpLocOnline.style.display = 'none';
        inpLocation.name = 'location_or_platform';
        selPlatform.name = 'location_or_platform_online'; // disabled from submit basically
        
        lblLink.textContent = 'Kayıt / Form Linki';
        grpCat.style.display = 'block';
    } else {
        grpLocPhysical.style.display = 'none';
        grpLocOnline.style.display = 'block';
        selPlatform.name = 'location_or_platform';
        inpLocation.name = 'location_or_platform_physical'; // disabled from submit basically
        
        lblLink.textContent = 'Katılım Linki';
        grpCat.style.display = 'block';
    }
}
function applyPartnerLocation() {
    const select = document.getElementById('partnerSelect');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value !== "") {
        const venueName = selectedOption.value;
        const mapLink = selectedOption.getAttribute('data-map');
        
        document.getElementById('inpLocation').value = venueName;
        if (mapLink) {
            document.querySelector('input[name="link"]').value = mapLink;
        }
    }
}
</script>
<?php endif; ?>

<script>
function switchTab(tabId) {
    document.querySelectorAll('.evt-tab').forEach(btn => btn.classList.remove('active'));
    document.querySelector('.evt-tab[onclick="switchTab(\''+tabId+'\')"]').classList.add('active');
    
    document.getElementById('tab-physical').style.display = (tabId === 'physical') ? 'block' : 'none';
    document.getElementById('tab-online').style.display = (tabId === 'online') ? 'block' : 'none';
    document.getElementById('tab-categorized').style.display = (tabId === 'categorized') ? 'block' : 'none';
}
</script>
