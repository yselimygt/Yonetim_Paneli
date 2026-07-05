<?php
// views/modules/bootcamp.php
// $upcoming, $past, $canEdit

function renderBootcampCard($bc, $canEdit) {
    $title = htmlspecialchars($bc['title']);
    $desc = htmlspecialchars($bc['description'] ?? '');
    $startDate = date('d.m.Y', strtotime($bc['start_date']));
    $endDate = date('d.m.Y', strtotime($bc['end_date']));
    $instructor = htmlspecialchars($bc['instructor'] ?? '');
    $quota = $bc['quota'] ? (int)$bc['quota'] . ' Kişi' : 'Sınırsız';
    $link = htmlspecialchars($bc['link'] ?? '');
    $cat = htmlspecialchars($bc['category'] ?? '');
    $img = $bc['image_path'] ? BASE_URL . '/public/' . $bc['image_path'] : '';
    
    $statusClass = $bc['status'] ? 'bc-badge-active' : 'bc-badge-inactive';
    $statusText = $bc['status'] ? 'Aktif' : 'Pasif';
    $opacity = $bc['status'] ? '1' : '0.65';
    
    $csrf = $_SESSION['csrf_token'] ?? '';
    
    ob_start();
    ?>
    <div class="bc-card" style="opacity: <?= $opacity ?>;">
        <?php if($img): ?>
            <div class="bc-img-wrapper">
                <img src="<?= $img ?>" alt="Görsel" class="bc-img" loading="lazy">
                <div class="bc-img-overlay"></div>
            </div>
        <?php endif; ?>
        
        <div class="bc-content">
            <div class="bc-header">
                <h3 class="bc-title"><?= $title ?></h3>
                <div style="display:flex; flex-direction:column; gap:6px; align-items:flex-end;">
                    <span class="bc-badge <?= $statusClass ?>"><?= $statusText ?></span>
                    <span class="bc-badge" style="background:rgba(139,92,246,0.15); color:#c4b5fd; border:1px solid rgba(139,92,246,0.3);">Ortak</span>
                </div>
            </div>
            
            <div class="bc-details">
                <div class="bc-row">
                    <svg class="bc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span><?= $startDate ?> - <?= $endDate ?></span>
                </div>
                <?php if(!empty($bc['location'])): ?>
                <div class="bc-row">
                    <svg class="bc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0018 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span>
                        <?php if($bc['type'] === 'physical' && !empty($bc['link'])): ?>
                            <a href="<?= htmlspecialchars($bc['link']) ?>" target="_blank" style="color:var(--bc-primary); text-decoration:none;">
                                <?= htmlspecialchars($bc['location']) ?>
                            </a>
                        <?php else: ?>
                            <?= htmlspecialchars($bc['location']) ?>
                        <?php endif; ?>
                    </span>
                </div>
                <?php endif; ?>
                <?php if($instructor): ?>
                    <div class="bc-row">
                        <svg class="bc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span><?= $instructor ?></span>
                    </div>
                <?php endif; ?>
                <div class="bc-row">
                    <svg class="bc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    <span><?= $quota ?></span>
                </div>
                <?php if($cat): ?>
                    <div class="bc-row">
                        <svg class="bc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        <span><?= $cat ?></span>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if($desc): ?>
                <p class="bc-desc"><?= nl2br($desc) ?></p>
            <?php endif; ?>
            
            <?php if($link && $bc['type'] !== 'physical'): ?>
                <a href="<?= $link ?>" target="_blank" class="bc-link-btn">
                    Başvuru / Görüşme Linki
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                </a>
            <?php endif; ?>
        </div>
        
        <?php if($canEdit): ?>
            <div class="bc-actions">
                <button type="button" class="bc-action-btn" title="Düzenle" onclick='openBcEditModal(<?= json_encode($bc, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>)'>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <form method="POST" style="margin:0;">
                    <input type="hidden" name="action" value="toggle_status">
                    <input type="hidden" name="bootcamp_id" value="<?= $bc['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <button type="submit" class="bc-action-btn" title="Durumu Değiştir">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><polyline points="23 20 23 14 17 14"/><path d="M20.49 9A9 9 0 005.64 5.64L1 10M3.51 15A9 9 0 0018.36 18.36L23 14"/></svg>
                    </button>
                </form>
                <form method="POST" style="margin:0;">
                    <input type="hidden" name="action" value="toggle_completed">
                    <input type="hidden" name="bootcamp_id" value="<?= $bc['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <button type="submit" class="bc-action-btn" title="<?= !empty($bc['is_completed']) ? 'Tamamlandı (Geri Al)' : 'Tamamlandı Olarak İşaretle' ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="<?= !empty($bc['is_completed']) ? '#10b981' : 'currentColor' ?>" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </button>
                </form>
                <form method="POST" style="margin:0;" onsubmit="return confirm('Bu Bootcamp programını silmek istediğinize emin misiniz?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="bootcamp_id" value="<?= $bc['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <button type="submit" class="bc-action-btn bc-action-danger" title="Sil">
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
/* PREMIUM BOOTCAMP UI */
:root {
    --bc-card: var(--card);
    --bc-border: var(--border);
    --bc-input: var(--bg);
    --bc-text: var(--text);
    --bc-muted: var(--muted);
    --bc-primary: #8b5cf6;
    --bc-primary-hover: #7c3aed;
    --bc-danger: #ef4444;
    --bc-success: #10b981;
}

/* Header */
.bc-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    animation: fadeDown 0.4s ease-out;
    flex-wrap: wrap;
    gap: 16px;
}
.bc-header-title { margin: 0; font-size: 24px; font-weight: 700; color: var(--bc-text); letter-spacing: -0.5px; }
.bc-header-subtitle { color: var(--bc-muted); font-size: 14px; margin: 4px 0 0 0; }

.bc-btn-add {
    background: linear-gradient(135deg, var(--bc-primary) 0%, var(--bc-primary-hover) 100%);
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
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.25);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.bc-btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(139, 92, 246, 0.35);
}

/* Tabs */
.bc-tabs-container {
    display: inline-flex;
    background: var(--bc-input);
    padding: 6px;
    border-radius: 12px;
    margin-bottom: 32px;
    border: 1px solid var(--bc-border);
}
.bc-tab {
    background: transparent;
    border: none;
    color: var(--bc-muted);
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    padding: 10px 24px;
    border-radius: 8px;
    transition: all 0.3s;
}
.bc-tab:hover { color: var(--bc-text); }
.bc-tab.active { background: var(--card); color: var(--bc-text); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

/* Grid & Cards */
.bc-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 24px;
    margin-bottom: 48px;
}

.bc-card {
    background: var(--bc-card);
    border: 1px solid var(--bc-border);
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    animation: fadeUp 0.5s ease-out backwards;
    display: flex;
    flex-direction: column;
}
.bc-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 32px rgba(0,0,0,0.15);
    border-color: rgba(255,255,255,0.1);
}

.bc-img-wrapper {
    width: 100%;
    height: 180px;
    min-height: 180px;
    position: relative;
    overflow: hidden;
    background: #1a1a1a;
    flex-shrink: 0;
}
.bc-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.6s ease;
}
.bc-card:hover .bc-img { transform: scale(1.08); }
.bc-img-overlay {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 80px;
    background: linear-gradient(to top, var(--bc-card), transparent);
}

.bc-content {
    padding: 24px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.bc-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 16px;
}
.bc-title {
    font-size: 19px;
    font-weight: 700;
    color: var(--bc-text);
    margin: 0;
    line-height: 1.4;
    letter-spacing: -0.3px;
}
.bc-badge {
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}
.bc-badge-active { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); }
.bc-badge-inactive { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }

.bc-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 16px;
}
.bc-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--bc-muted);
}
.bc-icon { width: 16px; height: 16px; color: var(--bc-primary); opacity: 0.8; }

.bc-desc {
    font-size: 14px;
    color: var(--bc-text);
    opacity: 0.85;
    line-height: 1.6;
    margin: 0 0 20px 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.bc-link-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px;
    background: transparent;
    border: 1px solid var(--bc-border);
    border-radius: 10px;
    color: var(--bc-text);
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
    margin-top: auto;
}
.bc-link-btn:hover {
    background: var(--bc-primary);
    border-color: var(--bc-primary);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.25);
}

.bc-actions {
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
.bc-card:hover .bc-actions { opacity: 1; transform: translateY(0); }

.bc-action-btn {
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
.bc-action-btn:hover { background: rgba(255,255,255,0.2); }
.bc-action-danger:hover { background: var(--bc-danger); }
.bc-action-btn svg { width: 16px; height: 16px; }

/* Modal */
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
    border: 1px solid var(--bc-border);
    padding: 32px;
    transform: translateY(30px) scale(0.95);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 24px 48px rgba(0,0,0,0.3);
}
.modal-overlay.active .modal-content { transform: translateY(0) scale(1); }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.modal-header h3 { margin: 0; font-size: 22px; font-weight: 700; color: var(--bc-text); }
.close-modal { background: var(--bc-input); border: none; color: var(--bc-text); width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s; }
.close-modal:hover { background: var(--bc-border); }

.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--bc-text); opacity: 0.9;}
.form-control {
    width: 100%; padding: 12px 16px; border-radius: 10px;
    background: var(--bc-input); border: 1px solid var(--bc-border);
    color: var(--bc-text); font-family: inherit; font-size: 14px;
    transition: all 0.2s;
}
.form-control:focus { border-color: var(--bc-primary); box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.15); outline: none; background: var(--card); }

.bc-btn-submit {
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(135deg, var(--bc-primary) 0%, var(--bc-primary-hover) 100%);
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);
    margin-top: 8px;
}
.bc-btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(139, 92, 246, 0.3); }

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
    background: var(--bc-input);
    border: 1px dashed var(--bc-border);
    border-radius: 16px;
    color: var(--bc-muted);
}
.empty-state svg { opacity: 0.5; margin-bottom: 16px; }
.empty-state h4 { margin: 0 0 8px 0; color: var(--bc-text); font-size: 18px; }
</style>

<div class="bc-header-bar">
    <div>
        <h2 class="bc-header-title">Bootcamp Programları</h2>
        <p class="bc-header-subtitle">Tüm departmanlarda ortak görünen eğitim kamplarını ve sertifika programlarını yönetin.</p>
    </div>
    <?php if($canEdit): ?>
        <button type="button" class="bc-btn-add" onclick="openBcModal()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Yeni Bootcamp
        </button>
    <?php endif; ?>
</div>

<div class="bc-tabs-container">
    <button class="bc-tab active" onclick="switchTab('upcoming')">Aktif Eğitimler</button>
    <button class="bc-tab" onclick="switchTab('past')">Tamamlananlar</button>
</div>

<!-- UPCOMING TAB -->
<div id="tab-upcoming" class="bc-tab-content">
    <?php if(!empty($upcoming)): ?>
        <div class="bc-grid">
            <?php foreach($upcoming as $bc) echo renderBootcampCard($bc, $canEdit); ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <h4>Aktif Eğitim Yok</h4>
            <p style="margin:0; font-size:14px;">Şu an planlanmış aktif bir bootcamp programı bulunmuyor.</p>
        </div>
    <?php endif; ?>
</div>

<!-- PAST TAB -->
<div id="tab-past" class="bc-tab-content" style="display:none;">
    <?php if(!empty($past)): ?>
        <div class="bc-grid">
            <?php foreach($past as $bc) echo renderBootcampCard($bc, $canEdit); ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            <h4>Kayıtlı Program Yok</h4>
            <p style="margin:0; font-size:14px;">Tamamlanmış bir eğitim programı bulunmuyor.</p>
        </div>
    <?php endif; ?>
</div>

<?php if($canEdit): ?>
<!-- Add Bootcamp Modal -->
<div class="modal-overlay" id="bcModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Yeni Bootcamp Programı</h3>
            <button type="button" class="close-modal" onclick="closeBcModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" enctype="multipart/form-data" id="bcForm">
            <input type="hidden" name="action" id="bcAction" value="add">
            <input type="hidden" name="bootcamp_id" id="bcId" value="">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div style="padding:12px 14px;margin-bottom:16px;border:1px solid rgba(139,92,246,0.3);border-radius:10px;background:rgba(139,92,246,0.08);color:var(--bc-text);font-size:13px;">
                Bu bootcamp ortak havuza kaydedilir ve tüm departmanlarda görünür.
            </div>
            
            <div style="display:flex; gap:16px;">
                <div class="form-group" style="flex:1;">
                    <label>Eğitim Tipi</label>
                    <select name="type" id="bcType" class="form-control" onchange="toggleBcFields()">
                        <option value="physical">Fiziksel</option>
                        <option value="online">Online</option>
                    </select>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Kategori</label>
                    <select name="category" id="selCategory" class="form-control">
                        <option value="">Kategori Seçiniz</option>
                        <option value="Yazılım Geliştirme">Yazılım Geliştirme</option>
                        <option value="Oyun Geliştirme">Oyun Geliştirme</option>
                        <option value="Grafik Tasarım">Grafik Tasarım</option>
                        <option value="UI/UX Tasarım">UI/UX Tasarım</option>
                        <option value="Veri Bilimi ve Yapay Zeka">Veri Bilimi ve Yapay Zeka</option>
                        <option value="Siber Güvenlik">Siber Güvenlik</option>
                        <option value="Dijital Pazarlama">Dijital Pazarlama</option>
                        <option value="Ürün Yönetimi">Ürün Yönetimi</option>
                        <option value="Sistem ve Ağ Yönetimi">Sistem ve Ağ Yönetimi</option>
                        <option value="Diğer">Diğer</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Program Başlığı</label>
                <input type="text" name="title" class="form-control" required placeholder="Örn: Full Stack Web Development Bootcamp">
            </div>

            <div style="display:flex; gap:16px;">
                <div class="form-group" style="flex:1;">
                    <label>Başlangıç Tarihi</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Bitiş Tarihi</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
            </div>
            
            <div style="display:flex; gap:16px;">
                <div class="form-group" style="flex:1;">
                    <label>Eğitmen / Mentor</label>
                    <input type="text" name="instructor" class="form-control" placeholder="Örn: John Doe">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Kontenjan</label>
                    <input type="number" name="quota" class="form-control" placeholder="Boş bırakılırsa sınırsız">
                </div>
            </div>

            <div style="display:flex; gap:16px;">
                <div class="form-group" id="grpLocationPhysical" style="flex:1;">
                    <label style="display:flex; justify-content:space-between; align-items:center;">
                        <span>Konum</span>
                        <select id="partnerSelect" style="font-size:12px; padding:2px 4px; border:1px solid var(--bc-border); border-radius:4px; background:var(--bg); color:var(--text); cursor:pointer; max-width: 140px;" onchange="applyPartnerLocation()">
                            <option value="">Anlaşmalı Mekan Seç...</option>
                            <?php if(!empty($partners)): foreach($partners as $p): ?>
                                <option value="<?= htmlspecialchars($p['venue_name']) ?>" data-map="<?= htmlspecialchars($p['map_link'] ?? '') ?>"><?= htmlspecialchars($p['venue_name']) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </label>
                    <input type="text" name="location_or_platform_physical" id="inpLocation" class="form-control" placeholder="Örn: Zorlu PSM, İstanbul">
                </div>
                
                <div class="form-group" id="grpLocationOnline" style="display:none; flex:1;">
                    <label style="display:flex; align-items:center; min-height:24px;">Platform</label>
                    <select name="location_or_platform_online" id="selPlatform" class="form-control">
                        <option value="">Platform Seçiniz</option>
                        <option value="Zoom">Zoom</option>
                        <option value="Google Meet">Google Meet</option>
                        <option value="Microsoft Teams">Microsoft Teams</option>
                        <option value="Skype">Skype</option>
                        <option value="Webex">Webex</option>
                        <option value="Diğer">Diğer</option>
                    </select>
                </div>

                <div class="form-group" style="flex:1;">
                    <label id="lblLocationLink" style="display:flex; align-items:center; min-height:24px;">Konum / Yayın Linki</label>
                    <input type="url" name="location_link" id="inpLocationLink" class="form-control" placeholder="Harita / Toplantı linki...">
                </div>
            </div>

            <div style="display:flex; gap:16px;">
                <div class="form-group" style="flex:1;">
                    <label id="lblLink" style="display:flex; align-items:center; min-height:24px;">
                        <span id="lblLinkText">Başvuru / Kayıt Linki</span>
                        <?php if(!empty($forms)): ?>
                            <select style="margin-left: 8px; font-size: 11px; padding: 2px 4px; border: 1px solid var(--bc-border); border-radius: 4px; background: var(--bg); color: var(--text); cursor: pointer;" onchange="if(this.value) { document.querySelector('input[name=\'link\']').value = this.value; this.value=''; }">
                                <option value="">Sistemden Form Seç</option>
                                <?php foreach($forms as $f): ?>
                                    <option value="<?= BASE_URL ?>/f/<?= htmlspecialchars($f['slug']) ?>"><?= htmlspecialchars($f['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </label>
                    <input type="url" name="link" class="form-control" placeholder="https://...">
                </div>
            </div>
            
            <div style="display:flex; gap:16px;">
                <div class="form-group" style="flex:2;">
                    <label>Açıklama / İçerik</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Eğitim programının kapsamı, önkoşullar vb..."></textarea>
                </div>
                
                <div class="form-group" style="flex:1;">
                    <label>Kapak Görseli</label>
                    <input type="file" name="image" class="form-control" accept="image/*" style="padding: 10px; height: auto;">
                    <p style="font-size:11px; color:var(--bc-muted); margin-top:6px;">Önerilen: Yatay, yüksek çözünürlüklü görsel.</p>
                </div>
            </div>
            
            <button type="submit" class="bc-btn-submit">Programı Kaydet</button>
        </form>
    </div>
</div>

<script>
function toggleBcFields() {
    const type = document.getElementById('bcType').value;
    if (type === 'physical') {
        document.getElementById('grpLocationPhysical').style.display = 'block';
        document.getElementById('grpLocationOnline').style.display = 'none';
    } else {
        document.getElementById('grpLocationPhysical').style.display = 'none';
        document.getElementById('grpLocationOnline').style.display = 'block';
    }
}

function applyPartnerLocation() {
    const sel = document.getElementById('partnerSelect');
    const inp = document.getElementById('inpLocation');
    const lnk = document.getElementById('inpLocationLink');
    
    if (sel.value) {
        inp.value = sel.value;
        const mapLink = sel.options[sel.selectedIndex].getAttribute('data-map');
        if (mapLink) {
            lnk.value = mapLink;
        }
    }
}

function openBcModal() {
    document.getElementById('bcForm').reset();
    document.getElementById('modalTitle').textContent = 'Yeni Bootcamp Programı';
    document.getElementById('bcAction').value = 'add';
    document.getElementById('bcId').value = '';
    toggleBcFields();
    
    document.getElementById('bcModal').classList.add('active');
}

function openBcEditModal(bc) {
    document.getElementById('bcForm').reset();
    document.getElementById('modalTitle').textContent = 'Programı Düzenle';
    document.getElementById('bcAction').value = 'edit';
    document.getElementById('bcId').value = bc.id;
    
    document.getElementById('bcType').value = bc.type || 'physical';
    toggleBcFields();
    if (bc.type === 'physical') {
        document.getElementById('inpLocation').value = bc.location || '';
    } else {
        document.getElementById('selPlatform').value = bc.location || '';
    }

    document.querySelector('input[name="title"]').value = bc.title;
    document.querySelector('input[name="start_date"]').value = bc.start_date;
    document.querySelector('input[name="end_date"]').value = bc.end_date;
    document.querySelector('input[name="instructor"]').value = bc.instructor || '';
    document.querySelector('input[name="quota"]').value = bc.quota || '';
    document.getElementById('selCategory').value = bc.category || '';
    document.querySelector('input[name="link"]').value = bc.link || '';
    document.getElementById('inpLocationLink').value = bc.location_link || '';
    document.querySelector('textarea[name="description"]').value = bc.description || '';
    
    document.getElementById('bcModal').classList.add('active');
}

function closeBcModal() {
    document.getElementById('bcModal').classList.remove('active');
}

function switchTab(tabId) {
    document.querySelectorAll('.bc-tab').forEach(btn => btn.classList.remove('active'));
    document.querySelector('.bc-tab[onclick="switchTab(\''+tabId+'\')"]').classList.add('active');
    
    document.getElementById('tab-upcoming').style.display = (tabId === 'upcoming') ? 'block' : 'none';
    document.getElementById('tab-past').style.display = (tabId === 'past') ? 'block' : 'none';
}
</script>
<?php endif; ?>
