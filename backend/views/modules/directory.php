<?php
function getSocialLinkInfo(string $url): array {
    $urlLower = strtolower($url);
    if (strpos($urlLower, 'linkedin.com') !== false) return ['name' => 'LinkedIn', 'icon' => 'linkedin', 'color' => '#0A66C2'];
    if (strpos($urlLower, 'twitter.com') !== false || strpos($urlLower, 'x.com') !== false) return ['name' => 'X', 'icon' => 'twitter', 'color' => '#000000'];
    if (strpos($urlLower, 'instagram.com') !== false) return ['name' => 'Instagram', 'icon' => 'instagram', 'color' => '#E1306C'];
    if (strpos($urlLower, 'facebook.com') !== false) return ['name' => 'Facebook', 'icon' => 'facebook', 'color' => '#1877F2'];
    if (strpos($urlLower, 'youtube.com') !== false) return ['name' => 'YouTube', 'icon' => 'youtube', 'color' => '#FF0000'];
    if (strpos($urlLower, 'github.com') !== false) return ['name' => 'GitHub', 'icon' => 'github', 'color' => '#181717'];
    return ['name' => 'Web Sitesi', 'icon' => 'globe', 'color' => 'var(--text)'];
}
?>

<style>
/* Premium Directory Styles - Sleek & Modern */
:root {
    --dir-bg: transparent;
    --dir-card: var(--card);
    --dir-border: var(--border);
    --dir-input: var(--input-bg);
    --dir-text: var(--text);
    --dir-muted: var(--muted);
    --dir-primary: var(--accent); 
    --dir-danger: #ef4444; 
    --dir-success: #10b981;
}

.dir-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 16px;
    animation: fadeDown 0.4s ease-out;
}

@keyframes fadeDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.dir-search-form {
    display: flex;
    align-items: center;
    background: var(--dir-input);
    border: 1px solid var(--dir-border);
    border-radius: 12px;
    padding: 8px 16px;
    flex: 1;
    max-width: 400px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(8px);
}

.dir-search-form:focus-within {
    border-color: var(--dir-primary);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    background: var(--card);
}

.dir-search-form input {
    background: transparent;
    border: none;
    color: var(--dir-text);
    padding: 8px 12px;
    outline: none;
    width: 100%;
    font-size: 14px;
    font-family: inherit;
}

.dir-search-form svg {
    color: var(--dir-muted);
    transition: color 0.3s;
}

.dir-search-form:focus-within svg {
    color: var(--dir-primary);
}

.dir-btn {
    padding: 8px 16px;
    border-radius: 8px;
    border: none;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    letter-spacing: 0.3px;
}

.dir-btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    border: 1px solid rgba(255,255,255,0.1);
}

.dir-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.35);
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
}

.dir-btn-dark {
    background: var(--dir-input);
    border: 1px solid var(--dir-border);
    color: var(--dir-text);
    backdrop-filter: blur(8px);
}

.dir-btn-dark:hover {
    background: var(--card);
    border-color: var(--dir-muted);
}

.dir-btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #ffffff;
}

.dir-btn-danger:hover { 
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3);
}

/* Grid Layout */
.dir-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 24px;
    perspective: 1000px;
}

/* Card Style - Glassmorphism */
.dir-card {
    background: var(--dir-card);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid var(--dir-border);
    border-radius: 20px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    animation: fadeUp 0.5s ease-out both;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.dir-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    border-color: rgba(255, 255, 255, 0.15);
}

.dir-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--avatar-color, #3b82f6), transparent);
    opacity: 0.7;
}

.dir-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 20px;
    position: relative;
    z-index: 2;
}

.dir-avatar {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 16px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);
    border: 1px solid rgba(255,255,255,0.05);
}

.dir-actions {
    display: flex;
    gap: 6px;
    background: var(--dir-input);
    padding: 4px;
    border-radius: 12px;
    border: 1px solid var(--dir-border);
    opacity: 0.6;
    transition: opacity 0.3s;
}

.dir-card:hover .dir-actions {
    opacity: 1;
}

.dir-action-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--dir-muted);
    background: transparent;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.dir-action-icon:hover {
    color: var(--dir-text);
    background: rgba(255,255,255,0.1);
    transform: scale(1.05);
}

.dir-name {
    font-size: 19px;
    font-weight: 700;
    color: var(--dir-text);
    margin: 0 0 10px 0;
    letter-spacing: -0.3px;
}

.dir-company {
    font-size: 14px;
    color: var(--dir-muted);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}

.dir-card-main {
    min-width: 0;
}

.dir-meta-line {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    color: var(--dir-muted);
    font-size: 12px;
    line-height: 1.35;
}

.dir-workplace {
    min-width: 0;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.dir-title-pill {
    display: inline-flex;
    align-items: center;
    min-height: 22px;
    max-width: 150px;
    padding: 3px 8px;
    border-radius: 999px;
    background: rgba(59, 130, 246, 0.14);
    color: #60a5fa;
    font-size: 11px;
    font-weight: 700;
    line-height: 1.2;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.dir-card-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 14px;
}

.dir-detail-btn {
    min-height: 30px;
    padding: 6px 13px;
    border: 1px solid rgba(245, 158, 11, 0.24);
    border-radius: 999px;
    background: rgba(245, 158, 11, 0.12);
    color: #f59e0b;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    transition: all 0.2s ease;
}

.dir-detail-btn:hover {
    background: rgba(245, 158, 11, 0.18);
    transform: translateY(-1px);
}

.dir-detail-summary {
    display: grid;
    gap: 10px;
}

.dir-detail-person {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border: 1px solid var(--dir-border);
    border-radius: 14px;
    background: var(--dir-input);
}

.dir-detail-avatar {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--dir-primary);
    background: rgba(59, 130, 246, 0.14);
    font-size: 16px;
    font-weight: 800;
    flex-shrink: 0;
}

.dir-detail-name {
    margin: 0 0 5px 0;
    color: var(--dir-text);
    font-size: 16px;
    font-weight: 800;
}

.dir-detail-sub {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    color: var(--dir-muted);
    font-size: 12px;
}

.dir-detail-section {
    padding: 12px;
    border: 1px solid var(--dir-border);
    border-radius: 14px;
    background: rgba(255,255,255,0.02);
}

.dir-detail-label {
    margin-bottom: 8px;
    color: var(--dir-muted);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.4px;
    text-transform: uppercase;
}

.dir-detail-list {
    display: grid;
    gap: 7px;
}

.dir-detail-value,
.dir-detail-link {
    color: var(--dir-text);
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    word-break: break-word;
}

.dir-detail-link:hover {
    color: var(--dir-primary);
}

.dir-detail-empty {
    color: var(--dir-muted);
    font-size: 12px;
}

.dir-divider {
    height: 1px;
    background: linear-gradient(90deg, var(--dir-border), transparent);
    margin: 16px 0;
    border: none;
}

.dir-info-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 14px;
    position: relative;
    z-index: 2;
}

.dir-info-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    font-size: 14px;
}

.dir-info-item svg {
    color: var(--dir-muted);
    margin-top: 2px;
    flex-shrink: 0;
    width: 16px;
    opacity: 0.8;
}

.dir-info-content {
    display: flex;
    flex-direction: column;
    gap: 6px;
    word-break: break-all;
}

.dir-info-link {
    color: var(--dir-text);
    text-decoration: none;
    transition: all 0.2s;
    font-weight: 500;
}

.dir-info-link:hover {
    color: var(--dir-primary);
    transform: translateX(4px);
}

/* Modal Styles matching the design */
.dir-modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(10, 10, 15, 0.8);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    display: flex;
    visibility: hidden;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    transition: opacity 0.3s, visibility 0.3s;
}

.dir-modal-overlay.active {
    visibility: visible;
    opacity: 1;
}

.dir-modal {
    background: var(--card);
    width: 100%;
    max-width: 520px;
    border-radius: 16px;
    border: 1px solid var(--dir-border);
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 24px 48px rgba(0,0,0,0.3);
    transform: scale(0.95) translateY(20px);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.dir-modal-overlay.active .dir-modal {
    transform: scale(1) translateY(0);
}

.dir-modal-header {
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--dir-border);
}

.dir-modal-title {
    font-size: 18px;
    font-weight: 700;
    margin: 0;
    color: var(--dir-text);
}

.dir-modal-close {
    background: var(--dir-input);
    border: 1px solid var(--dir-border);
    color: var(--dir-muted);
    cursor: pointer;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.dir-modal-close:hover { 
    color: var(--dir-text);
    background: rgba(128,128,128,0.1);
    transform: rotate(90deg);
}

#dirForm {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-height: 0;
}

.dir-modal-body {
    padding: 20px 24px;
    overflow-y: auto;
    flex: 1;
    min-height: 0;
}

/* Custom Scrollbar for Modal */
.dir-modal-body::-webkit-scrollbar {
    width: 8px;
}
.dir-modal-body::-webkit-scrollbar-track {
    background: transparent;
}
.dir-modal-body::-webkit-scrollbar-thumb {
    background: var(--dir-border);
    border-radius: 4px;
}
.dir-modal-body::-webkit-scrollbar-thumb:hover {
    background: var(--dir-muted);
}

.dir-modal-footer {
    padding: 16px 24px;
    border-top: 1px solid var(--dir-border);
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background: rgba(0,0,0,0.05);
    border-bottom-left-radius: 16px;
    border-bottom-right-radius: 16px;
}

.dir-form-group {
    margin-bottom: 20px;
}

.dir-form-label {
    display: block;
    margin-bottom: 6px;
    font-size: 12px;
    font-weight: 600;
    color: var(--dir-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.dir-form-control {
    width: 100%;
    padding: 10px 14px;
    background: var(--dir-input);
    border: 1px solid var(--dir-border);
    border-radius: 8px;
    color: var(--dir-text);
    font-size: 13px;
    transition: all 0.2s;
}

.dir-form-control:focus {
    outline: none;
    border-color: var(--dir-primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    background: var(--card);
}

textarea.dir-form-control {
    min-height: 80px;
    resize: vertical;
}

.dir-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

/* Dynamic Inputs */
.dir-dynamic-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.dir-dynamic-item {
    display: flex;
    gap: 10px;
    animation: fadeDown 0.2s ease-out;
}

.dir-btn-add {
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
    color: var(--dir-primary);
    padding: 3px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    margin-left: 8px;
    transition: all 0.2s;
    text-transform: none;
    letter-spacing: normal;
}

.dir-btn-add:hover { 
    background: var(--dir-primary); 
    color: #fff;
}

.dir-btn-remove {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 8px;
    width: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

    .dir-btn-remove:hover { 
    background: #ef4444;
    color: #fff;
    transform: scale(1.05);
}

@media (max-width: 640px) {
    .dir-card {
        padding: 18px;
        border-radius: 16px;
    }

    .dir-card-header {
        margin-bottom: 0;
        gap: 12px;
    }

    .dir-avatar {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        margin-bottom: 14px;
    }

    .dir-name {
        font-size: 17px;
        line-height: 1.25;
    }

    .dir-actions {
        flex-shrink: 0;
    }
}
</style>

<div class="dir-header-bar">
    <h2 style="margin:0; font-size: 24px;">Kurumsal Rehber</h2>
    
    <div style="display:flex; gap:16px; flex-wrap:wrap; flex:1; justify-content:flex-end;">
        <form class="dir-search-form" method="GET">
            <input type="hidden" name="app_id" value="<?= htmlspecialchars($_GET['app_id'] ?? '') ?>">
            <svg style="width:18px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" name="q" placeholder="Ad, Unvan veya Şirket Ara..." value="<?= htmlspecialchars($searchTerm ?? '') ?>">
        </form>

        <button class="dir-btn dir-btn-primary" onclick="openDirModal()">
            <svg style="width:16px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Yeni Kişi Ekle
        </button>
    </div>
</div>

<?php if(empty($contacts)): ?>
    <div style="text-align:center; padding: 60px 20px; background:var(--card); border:1px solid var(--border); border-radius:16px;">
        <svg style="width:64px; margin:0 auto 16px; color:var(--muted);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        <h3 style="margin:0 0 8px 0;">Kayıt Bulunamadı</h3>
        <p style="color:var(--muted); margin:0;">Rehberde henüz bir kişi veya kurum bulunmuyor.</p>
    </div>
<?php else: ?>
    <div class="dir-grid">
        <?php 
        $colors = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4'];
        $dirStagger = 1;
        foreach($contacts as $c): 
            $initials = mb_strtoupper(mb_substr($c['full_name'], 0, 1) . mb_substr(explode(' ', $c['full_name'].' ')[1], 0, 1));
            $avatarColor = $colors[$c['id'] % count($colors)];
        ?>
        <div class="dir-card stagger-<?= min($dirStagger++, 5) ?>">
            <div class="dir-card-header">
                <div>
                    <div class="dir-avatar" style="background: <?= $avatarColor ?>20; color: <?= $avatarColor ?>;">
                        <?= htmlspecialchars($initials) ?>
                    </div>
                    <h3 class="dir-name"><?= htmlspecialchars($c['full_name']) ?></h3>
                    <div class="dir-meta-line">
                        <span class="dir-workplace"><?= htmlspecialchars($c['company'] ?: 'Çalıştığı yer belirtilmedi') ?></span>
                        <span class="dir-title-pill"><?= htmlspecialchars($c['title'] ?: 'İlan yok') ?></span>
                    </div>
                </div>

                <div class="dir-actions">
                    <button class="dir-action-icon" onclick="openDirModal(<?= htmlspecialchars(json_encode($c)) ?>)" title="Düzenle">
                        <svg style="width:16px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    <form method="POST" style="margin:0;" onsubmit="return confirm('Bu kişiyi silmek istediğinize emin misiniz?');">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="contact_id" value="<?= $c['id'] ?>">
                        <button type="submit" class="dir-action-icon" style="color:#ef4444;" title="Sil">
                            <svg style="width:16px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
            <div class="dir-card-footer">
                <button type="button" class="dir-detail-btn" onclick="openDirDetailModal(<?= htmlspecialchars(json_encode($c)) ?>)">Detay</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Yeni/Düzenle Modal -->
<div class="dir-modal-overlay" id="dirModal" onclick="if(event.target===this) closeDirModal()">
    <div class="dir-modal pop-in">
        <div class="dir-modal-header">
            <h3 class="dir-modal-title" id="dirModalTitle">Yeni Kişi Ekle</h3>
            <button class="dir-modal-close" onclick="closeDirModal()">
                <svg style="width:24px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        
        <form method="POST" id="dirForm">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <div class="dir-modal-body">
                <input type="hidden" name="action" id="dirAction" value="create">
                <input type="hidden" name="contact_id" id="dirContactId" value="">

                <div class="dir-form-group">
                    <label class="dir-form-label">Ad Soyad <span style="color:#ef4444">*</span></label>
                    <input type="text" name="full_name" id="dirFullName" class="dir-form-control" required placeholder="Örn: Ahmet Yılmaz">
                </div>

                <div class="dir-row">
                    <div class="dir-form-group">
                        <label class="dir-form-label">Kurum / Şirket</label>
                        <input type="text" name="company" id="dirCompany" class="dir-form-control" placeholder="Örn: Innomis Tech">
                    </div>
                    <div class="dir-form-group">
                        <label class="dir-form-label">Unvan</label>
                        <input type="text" name="title" id="dirTitle" class="dir-form-control" placeholder="Örn: Kurucu Ortak">
                    </div>
                </div>

                <!-- E-postalar -->
                <div class="dir-form-group">
                    <label class="dir-form-label">
                        E-postalar
                        <button type="button" class="dir-btn-add" onclick="addDynamicField('emailsList', 'emails[]', 'ornek@sirket.com', 'email')">+ Ekle</button>
                    </label>
                    <div class="dir-dynamic-list" id="emailsList">
                        <!-- JS fills -->
                    </div>
                </div>

                <!-- Telefonlar -->
                <div class="dir-form-group">
                    <label class="dir-form-label">
                        Telefonlar
                        <button type="button" class="dir-btn-add" onclick="addDynamicField('phonesList', 'phones[]', '05XX XXX XX XX', 'text')">+ Ekle</button>
                    </label>
                    <div class="dir-dynamic-list" id="phonesList">
                        <!-- JS fills -->
                    </div>
                </div>

                <!-- Linkler -->
                <div class="dir-form-group">
                    <label class="dir-form-label">
                        Web & Sosyal Medya
                        <button type="button" class="dir-btn-add" onclick="addDynamicField('linksList', 'links[]', 'https://...', 'url')">+ Ekle</button>
                    </label>
                    <div class="dir-dynamic-list" id="linksList">
                        <!-- JS fills -->
                    </div>
                </div>

                <div class="dir-form-group" style="margin-bottom:0;">
                    <label class="dir-form-label">Notlar</label>
                    <textarea name="notes" id="dirNotes" class="dir-form-control" placeholder="Eklemek istediğiniz notlar..."></textarea>
                </div>
            </div>
            
            <div class="dir-modal-footer">
                <button type="button" class="dir-btn dir-btn-dark" onclick="closeDirModal()">İptal</button>
                <button type="submit" class="dir-btn dir-btn-primary">Kaydet</button>
            </div>
        </form>
    </div>
</div>

<!-- Detay Modal -->
<div class="dir-modal-overlay" id="dirDetailModal" onclick="if(event.target===this) closeDirDetailModal()">
    <div class="dir-modal pop-in">
        <div class="dir-modal-header">
            <h3 class="dir-modal-title">Kişi Detayı</h3>
            <button class="dir-modal-close" onclick="closeDirDetailModal()">
                <svg style="width:24px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div class="dir-modal-body">
            <div class="dir-detail-summary" id="dirDetailContent"></div>
        </div>
        <div class="dir-modal-footer">
            <button type="button" class="dir-btn dir-btn-dark" onclick="closeDirDetailModal()">Kapat</button>
        </div>
    </div>
</div>

<script>
function escapeDirHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, function(char) {
        return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'}[char];
    });
}

function dirInitials(name) {
    const parts = String(name || '').trim().split(/\s+/).filter(Boolean);
    return parts.slice(0, 2).map(part => part.charAt(0)).join('').toUpperCase() || '?';
}

function renderDirDetailList(label, values, type = 'text') {
    const list = Array.isArray(values) ? values.filter(Boolean) : [];
    const content = list.length
        ? list.map(value => {
            const safeValue = escapeDirHtml(value);
            if (type === 'email') return `<a class="dir-detail-link" href="mailto:${safeValue}">${safeValue}</a>`;
            if (type === 'phone') return `<a class="dir-detail-link" href="tel:${safeValue}">${safeValue}</a>`;
            if (type === 'link') return `<a class="dir-detail-link" href="${safeValue}" target="_blank" rel="noopener">${safeValue}</a>`;
            return `<div class="dir-detail-value">${safeValue}</div>`;
        }).join('')
        : '<div class="dir-detail-empty">Belirtilmedi</div>';

    return `
        <section class="dir-detail-section">
            <div class="dir-detail-label">${escapeDirHtml(label)}</div>
            <div class="dir-detail-list">${content}</div>
        </section>
    `;
}

function openDirDetailModal(data) {
    const content = document.getElementById('dirDetailContent');
    const fullName = data.full_name || '';
    const company = data.company || 'Çalıştığı yer belirtilmedi';
    const title = data.title || 'İlan yok';
    const notes = data.notes ? escapeDirHtml(data.notes).replace(/\n/g, '<br>') : '<span class="dir-detail-empty">Belirtilmedi</span>';

    content.innerHTML = `
        <section class="dir-detail-person">
            <div class="dir-detail-avatar">${escapeDirHtml(dirInitials(fullName))}</div>
            <div>
                <h4 class="dir-detail-name">${escapeDirHtml(fullName)}</h4>
                <div class="dir-detail-sub">
                    <span>${escapeDirHtml(company)}</span>
                    <span class="dir-title-pill">${escapeDirHtml(title)}</span>
                </div>
            </div>
        </section>
        ${renderDirDetailList('E-postalar', data.emails, 'email')}
        ${renderDirDetailList('Telefonlar', data.phones, 'phone')}
        ${renderDirDetailList('Web & Sosyal Medya', data.links, 'link')}
        <section class="dir-detail-section">
            <div class="dir-detail-label">Notlar</div>
            <div class="dir-detail-value">${notes}</div>
        </section>
    `;

    document.getElementById('dirDetailModal').classList.add('active');
}

function closeDirDetailModal() {
    document.getElementById('dirDetailModal').classList.remove('active');
}

function createDynamicField(name, placeholder, type, value = '') {
    const item = document.createElement('div');
    item.className = 'dir-dynamic-item';
    
    const input = document.createElement('input');
    input.type = type;
    input.name = name;
    input.placeholder = placeholder;
    input.className = 'dir-form-control';
    input.value = value;
    
    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'dir-btn-remove';
    removeBtn.innerHTML = '<svg style="width:20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
    removeBtn.onclick = function() {
        item.remove();
    };
    
    item.appendChild(input);
    item.appendChild(removeBtn);
    return item;
}

function addDynamicField(containerId, name, placeholder, type, value = '') {
    const container = document.getElementById(containerId);
    container.appendChild(createDynamicField(name, placeholder, type, value));
}

function populateDynamicFields(containerId, name, placeholder, type, valuesArray) {
    const container = document.getElementById(containerId);
    container.innerHTML = ''; // Clear existing
    
    if (!valuesArray || valuesArray.length === 0) {
        addDynamicField(containerId, name, placeholder, type); // Add one empty field by default
    } else {
        valuesArray.forEach(val => {
            addDynamicField(containerId, name, placeholder, type, val);
        });
    }
}

function openDirModal(data = null) {
    const modal = document.getElementById('dirModal');
    const form = document.getElementById('dirForm');
    
    if (data) {
        document.getElementById('dirModalTitle').textContent = 'Kişiyi Düzenle';
        document.getElementById('dirAction').value = 'update';
        document.getElementById('dirContactId').value = data.id;
        
        document.getElementById('dirFullName').value = data.full_name;
        document.getElementById('dirCompany').value = data.company || '';
        document.getElementById('dirTitle').value = data.title || '';
        document.getElementById('dirNotes').value = data.notes || '';
        
        populateDynamicFields('emailsList', 'emails[]', 'ornek@sirket.com', 'email', data.emails);
        populateDynamicFields('phonesList', 'phones[]', '05XX XXX XX XX', 'text', data.phones);
        populateDynamicFields('linksList', 'links[]', 'https://...', 'url', data.links);
    } else {
        document.getElementById('dirModalTitle').textContent = 'Yeni Kişi Ekle';
        document.getElementById('dirAction').value = 'create';
        document.getElementById('dirContactId').value = '';
        form.reset();
        
        // Add one empty field for each by default
        populateDynamicFields('emailsList', 'emails[]', 'ornek@sirket.com', 'email', []);
        populateDynamicFields('phonesList', 'phones[]', '05XX XXX XX XX', 'text', []);
        populateDynamicFields('linksList', 'links[]', 'https://...', 'url', []);
    }
    
    modal.classList.add('active');
}

function closeDirModal() {
    document.getElementById('dirModal').classList.remove('active');
}

// Close modal if clicked outside
document.getElementById('dirModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDirModal();
    }
});
</script>
