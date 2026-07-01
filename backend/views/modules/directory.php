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
    margin: 0 0 6px 0;
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
                    <p class="dir-company">
                        <?php if($c['company']): ?>
                            <svg style="width:14px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                            <?= htmlspecialchars($c['company']) ?> 
                        <?php endif; ?>
                        <?php if($c['title']): ?>
                            - <?= htmlspecialchars($c['title']) ?>
                        <?php endif; ?>
                    </p>
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

            <div class="dir-divider"></div>

            <ul class="dir-info-list">
                <?php if(!empty($c['phones'])): ?>
                <li class="dir-info-item">
                    <svg style="width:16px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    <div class="dir-info-content">
                        <?php foreach($c['phones'] as $p): ?>
                            <a href="tel:<?= htmlspecialchars($p) ?>" class="dir-info-link"><?= htmlspecialchars($p) ?></a>
                        <?php endforeach; ?>
                    </div>
                </li>
                <?php endif; ?>

                <?php if(!empty($c['emails'])): ?>
                <li class="dir-info-item">
                    <svg style="width:16px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <div class="dir-info-content">
                        <?php foreach($c['emails'] as $e): ?>
                            <a href="mailto:<?= htmlspecialchars($e) ?>" class="dir-info-link"><?= htmlspecialchars($e) ?></a>
                        <?php endforeach; ?>
                    </div>
                </li>
                <?php endif; ?>

                <?php if(!empty($c['links'])): ?>
                <li class="dir-info-item">
                    <svg style="width:16px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                    <div class="dir-info-content" style="flex-direction:row; flex-wrap:wrap; gap:8px; align-items:center;">
                        <?php foreach($c['links'] as $l): 
                            $soc = getSocialLinkInfo($l);
                        ?>
                            <a href="<?= htmlspecialchars($l) ?>" target="_blank" class="dir-info-link" title="<?= htmlspecialchars($soc['name']) ?>" style="display:inline-flex; align-items:center; gap:4px; padding:2px 8px; border-radius:12px; background:var(--bg); border:1px solid var(--border); font-size:12px;">
                                <span style="color:<?= $soc['color'] ?>;">
                                    <?php if($soc['icon'] == 'linkedin'): ?>
                                        <svg style="width:12px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                    <?php elseif($soc['icon'] == 'twitter'): ?>
                                        <svg style="width:12px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg>
                                    <?php elseif($soc['icon'] == 'instagram'): ?>
                                        <svg style="width:12px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                                    <?php elseif($soc['icon'] == 'youtube'): ?>
                                        <svg style="width:12px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.5 12 3.5 12 3.5s-7.505 0-9.377.55a3.016 3.016 0 0 0-2.122 2.136C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.55 9.376.55 9.376.55s7.505 0 9.377-.55a3.016 3.016 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    <?php else: ?>
                                        <svg style="width:12px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                    <?php endif; ?>
                                </span>
                                <?= htmlspecialchars($soc['name']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </li>
                <?php endif; ?>

                <?php if(!empty($c['notes'])): ?>
                <li class="dir-info-item">
                    <svg style="width:16px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    <div class="dir-info-content" style="color:var(--muted);">
                        <?= nl2br(htmlspecialchars($c['notes'])) ?>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
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

<script>
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
