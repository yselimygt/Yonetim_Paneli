<style>
.links-admin-container {
    max-width: 800px;
    margin: 0 auto;
}
.la-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
}
.la-card h3 {
    margin-top: 0;
    margin-bottom: 16px;
    font-size: 18px;
    color: var(--text);
}
.form-group {
    margin-bottom: 16px;
}
.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    font-size: 14px;
    color: var(--muted);
}
.form-control {
    width: 100%;
    padding: 10px 14px;
    background: var(--input-bg);
    border: 1px solid var(--border);
    color: var(--text);
    border-radius: 8px;
    font-size: 14px;
}
.form-control:focus {
    outline: none;
    border-color: var(--accent);
}

.links-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.link-item {
    display: flex;
    gap: 16px;
    background: var(--card-alt);
    padding: 16px;
    border: 1px solid var(--border);
    border-radius: 8px;
    align-items: flex-start;
}
.link-drag-handle {
    cursor: grab;
    color: var(--muted);
    padding-top: 10px;
}
.link-fields {
    flex: 1;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.link-icon-field {
    grid-column: span 2;
}
.link-remove {
    background: transparent;
    border: none;
    color: var(--danger);
    cursor: pointer;
    padding-top: 10px;
}
.add-link-btn {
    width: 100%;
    padding: 12px;
    background: transparent;
    border: 2px dashed var(--border);
    color: var(--text);
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s;
}
.add-link-btn:hover {
    border-color: var(--accent);
    color: var(--accent);
}
.color-picker-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
}
.color-picker {
    width: 40px;
    height: 40px;
    padding: 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

@media (max-width: 760px) {
    .links-admin-container {
        max-width: none;
        width: 100%;
    }

    .links-admin-container > div:first-child {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 10px !important;
        margin-bottom: 14px !important;
    }

    .links-admin-container > div:first-child h1 {
        font-size: 20px !important;
        line-height: 1.2 !important;
    }

    .links-admin-container > div:first-child p {
        display: none !important;
    }

    .links-admin-container > div:first-child .btn {
        width: 100% !important;
        justify-content: center !important;
        min-height: 40px !important;
        border-radius: 11px !important;
        font-size: 13px !important;
    }

    .links-admin-container > div[style*="background:rgba(59,130,246"] {
        display: none !important;
    }

    .la-card {
        padding: 14px;
        margin-bottom: 14px;
        border-radius: 14px;
    }

    .la-card h3 {
        margin-bottom: 12px;
        font-size: 16px;
    }

    .form-group {
        margin-bottom: 13px;
    }

    .form-label {
        margin-bottom: 6px;
        font-size: 12px;
    }

    .form-control {
        min-height: 40px;
        padding: 9px 12px;
        border-radius: 10px;
        font-size: 13px;
    }

    textarea.form-control {
        min-height: 72px;
    }

    .color-picker-wrapper {
        align-items: flex-start;
        gap: 9px;
    }

    .color-picker {
        width: 38px;
        height: 38px;
        flex-shrink: 0;
    }

    .color-picker-wrapper span {
        font-size: 12px !important;
        line-height: 1.35;
    }

    .links-list {
        gap: 10px;
    }

    .link-item {
        position: relative;
        gap: 8px;
        padding: 12px;
        border-radius: 13px;
    }

    .link-drag-handle {
        padding-top: 8px;
        flex-shrink: 0;
    }

    .link-fields {
        grid-template-columns: 1fr;
        gap: 9px;
        min-width: 0;
    }

    .link-icon-field {
        grid-column: auto;
    }

    .link-remove {
        position: absolute;
        top: 8px;
        right: 8px;
        padding: 6px;
        border-radius: 9px;
        background: rgba(239, 68, 68, 0.1);
    }

    .link-remove svg {
        width: 16px;
        height: 16px;
    }

    .add-link-btn {
        min-height: 40px;
        border-radius: 11px;
        font-size: 13px;
    }

    .links-admin-container form > div[style*="text-align:right"] {
        text-align: initial !important;
    }

    .links-admin-container form > div[style*="text-align:right"] .btn {
        width: 100%;
        min-height: 42px;
        justify-content: center;
        border-radius: 11px;
        font-size: 14px !important;
    }
}
</style>

<div class="links-admin-container">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
            <h1 style="margin:0; font-size:24px;">Bağlantılarım</h1>
            <p style="color:var(--muted); margin:4px 0 0 0;">Tüm departmanlarda ortak görünen sosyal medya hesaplarını ve hızlı bağlantıları yönetin.</p>
        </div>
        <a href="<?= BASE_URL ?>/f/<?= View::escape($this->app['slug']) ?>" target="_blank" class="btn btn-outline" style="border-color:var(--border); color:var(--text);">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:6px;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
            Yayını Gör
        </a>
    </div>

    <?php if(!empty($_SESSION['success_msg'])): ?>
        <div style="background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); color:#10b981; padding:12px 16px; border-radius:8px; margin-bottom:24px;">
            <?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
        </div>
    <?php endif; ?>

    <div style="background:rgba(59,130,246,0.1); border:1px solid rgba(59,130,246,0.2); color:#93c5fd; padding:12px 16px; border-radius:8px; margin-bottom:24px; font-size:13px; line-height:1.5;">
        <strong style="color:#60a5fa;">Global Linktree Havuzu:</strong> Buradaki değişiklikler tüm departmanlardaki Linktree modülüne ve public bağlantı sayfasına yansır.
    </div>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        
        <div class="la-card">
            <h3>Profil ve Görünüm</h3>
            <div class="form-group">
                <label class="form-label">Sayfa / Profil Adı</label>
                <input type="text" name="profile_name" class="form-control" value="<?= View::escape($profile['name'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Kısa Açıklama (Bio)</label>
                <textarea name="profile_bio" class="form-control" rows="2" placeholder="Örn: Topluluğumuzun tüm önemli bağlantıları burada!"><?= View::escape($profile['bio'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Profil Resmi URL'si veya Yükle</label>
                <?php
                $profVal = $profile['image'] ?? '';
                $isProfLocal = !empty($profVal) && !preg_match('/^https?:\/\//i', $profVal);
                ?>
                <input type="hidden" name="existing_profile_image" value="<?= View::escape($profVal) ?>">
                <input type="url" name="profile_image" class="form-control" placeholder="https://..." value="<?= $isProfLocal ? '' : View::escape($profVal) ?>" style="margin-bottom:8px;">
                <input type="file" name="profile_image_file" class="form-control" accept="image/*">
                <?php if($isProfLocal): ?>
                    <small style="display:block; margin-top:4px; color:#10b981; font-weight:500;">✓ Mevcut Yüklü Resim: <?= View::escape(basename($profVal)) ?></small>
                <?php endif; ?>
                <small style="color:var(--muted); display:block; margin-top:4px;">Logonuzun URL'sini yapıştırın veya cihazınızdan seçin.</small>
            </div>

            <div class="form-group">
                <label class="form-label">Tema Rengi</label>
                <div class="color-picker-wrapper">
                    <input type="color" name="theme_color" class="color-picker" value="<?= View::escape($profile['theme_color'] ?? '#3b82f6') ?>">
                    <span style="color:var(--muted); font-size:13px;">Sayfa arka planında kullanılacak ana renk.</span>
                </div>
            </div>
        </div>

        <div class="la-card">
            <h3>Bağlantılar (Linkler)</h3>
            <div class="links-list" id="linksList">
                <?php foreach($links as $index => $link): ?>
                <div class="link-item">
                    <div class="link-drag-handle">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="4" y1="8" x2="20" y2="8"></line><line x1="4" y1="16" x2="20" y2="16"></line></svg>
                    </div>
                    <div class="link-fields">
                        <div>
                            <label class="form-label" style="font-size:12px;">Başlık</label>
                            <input type="text" name="link_title[]" class="form-control" value="<?= View::escape($link['title']) ?>" placeholder="Örn: Instagram" required>
                        </div>
                        <div>
                            <label class="form-label" style="font-size:12px;">URL (Bağlantı)</label>
                            <input type="url" name="link_url[]" class="form-control" value="<?= View::escape($link['url']) ?>" placeholder="https://" required>
                        </div>
                        <div class="link-icon-field">
                            <?php
                            $iconVal = $link['icon'] ?? '';
                            $isIconLocal = !empty($iconVal) && !preg_match('/^https?:\/\//i', $iconVal);
                            ?>
                            <label class="form-label" style="font-size:12px;">İkon URL'si veya Yükle (Opsiyonel)</label>
                            <input type="hidden" name="existing_link_icon[]" value="<?= View::escape($iconVal) ?>">
                            <input type="url" name="link_icon[]" class="form-control" value="<?= $isIconLocal ? '' : View::escape($iconVal) ?>" placeholder="https://... (Örn: png veya svg bağlantısı)" style="margin-bottom:8px;">
                            <input type="file" name="link_icon_file[]" class="form-control" accept="image/*">
                            <?php if($isIconLocal): ?>
                                <small style="display:block; margin-top:4px; color:#10b981; font-weight:500; font-size:12px;">✓ Yüklü İkon: <?= View::escape(basename($iconVal)) ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button type="button" class="link-remove" onclick="this.parentElement.remove()">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
            
            <button type="button" class="add-link-btn" onclick="addLinkForm()" style="margin-top:16px;">
                + Yeni Bağlantı Ekle
            </button>
        </div>

        <div style="text-align:right;">
            <button type="submit" class="btn btn-primary" style="background:#10b981; border:none; padding:12px 32px; font-size:16px;">
                Kaydet
            </button>
        </div>
    </form>
</div>

<template id="linkTemplate">
    <div class="link-item">
        <div class="link-drag-handle">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="4" y1="8" x2="20" y2="8"></line><line x1="4" y1="16" x2="20" y2="16"></line></svg>
        </div>
        <div class="link-fields">
            <div>
                <label class="form-label" style="font-size:12px;">Başlık</label>
                <input type="text" name="link_title[]" class="form-control" placeholder="Örn: Websitemiz" required>
            </div>
            <div>
                <label class="form-label" style="font-size:12px;">URL (Bağlantı)</label>
                <input type="url" name="link_url[]" class="form-control" placeholder="https://" required>
            </div>
            <div class="link-icon-field">
                <label class="form-label" style="font-size:12px;">İkon URL'si veya Yükle (Opsiyonel)</label>
                <input type="hidden" name="existing_link_icon[]" value="">
                <input type="url" name="link_icon[]" class="form-control" placeholder="https://... (Örn: png veya svg bağlantısı)" style="margin-bottom:8px;">
                <input type="file" name="link_icon_file[]" class="form-control" accept="image/*">
            </div>
        </div>
        <button type="button" class="link-remove" onclick="this.parentElement.remove()">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
        </button>
    </div>
</template>

<script>
function addLinkForm() {
    const tpl = document.getElementById('linkTemplate').content.cloneNode(true);
    document.getElementById('linksList').appendChild(tpl);
}
</script>
