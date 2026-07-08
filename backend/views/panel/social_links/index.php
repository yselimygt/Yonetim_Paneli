<?php
/** @var array $socialLinks */
/** @var array $currentDept */
$pageTitle = 'Sosyal Medya Linkleri';
?>

<div class="page-header">
    <div>
        <h2>Sosyal Medya Linkleri (Global)</h2>
        <p style="color:var(--muted); font-size:13px; margin-top:4px;">Web sitesinde ve tüm departmanlarda görünecek olan genel sosyal medya butonlarını buradan yönetebilirsiniz.</p>
        <p style="color:var(--warning); font-size:13px; margin-top:4px;"><i class="fa-solid fa-triangle-exclamation"></i> <strong>Uyarı:</strong> Burada yaptığınız değişiklikler tüm siteyi etkiler.</p>
    </div>
    <div style="display:flex; gap: 12px; align-items: center;">
        <a href="<?= BASE_URL ?>/l/sosyal" target="_blank" class="btn" style="background:var(--card-alt); border:1px solid var(--border); color:var(--text); text-decoration:none; display:inline-flex; align-items:center; gap:8px; padding: 10px 18px; border-radius: 8px; font-weight: 500; font-size: 14px; transition: all 0.2s;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                <polyline points="15 3 21 3 21 9"></polyline>
                <line x1="10" y1="14" x2="21" y2="3"></line>
            </svg>
            Siteyi Görüntüle
        </a>
        <button type="button" class="btn btn-primary" onclick="addSocialLink()">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Yeni Hesap Ekle
        </button>
    </div>
</div>

<?php if (isset($_SESSION['flash'])): ?>
    <div class="flash <?= View::escape($_SESSION['flash']['type']) ?>">
        <i class="fa-solid <?= $_SESSION['flash']['type'] === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation' ?>"></i>
        <?= View::escape($_SESSION['flash']['message']) ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<style>
.social-links-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.social-link-card {
    background: var(--card-alt);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 24px;
    position: relative;
    transition: all 0.3s ease;
}
.social-link-card:hover {
    border-color: rgba(255, 255, 255, 0.15);
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}
.social-link-card .remove-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255, 59, 48, 0.1);
    color: #ff3b30;
    border: none;
    border-radius: 6px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}
.social-link-card .remove-btn:hover {
    background: #ff3b30;
    color: #fff;
}
.social-icon-preview {
    width: 54px;
    height: 54px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    margin-bottom: 20px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border);
    color: var(--text);
    transition: all 0.3s;
}
.form-group {
    margin-bottom: 15px;
}
.form-group label {
    font-size: 13px;
    color: var(--muted);
    margin-bottom: 6px;
    display: block;
}
.form-control {
    width: 100%;
    background: rgba(0,0,0,0.2);
    border: 1px solid var(--border);
    color: var(--text);
    padding: 10px 12px;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
}
.form-control:focus {
    border-color: var(--accent);
    outline: none;
}
.color-picker-wrapper {
    display: flex;
    gap: 10px;
    align-items: center;
}
.color-picker-wrapper input[type="color"] {
    -webkit-appearance: none;
    border: none;
    width: 38px;
    height: 38px;
    border-radius: 8px;
    cursor: pointer;
    padding: 0;
    background: none;
}
.color-picker-wrapper input[type="color"]::-webkit-color-swatch-wrapper {
    padding: 0;
}
.color-picker-wrapper input[type="color"]::-webkit-color-swatch {
    border: 1px solid var(--border);
    border-radius: 8px;
}
.save-btn-wrapper {
    margin-top: 30px;
    display: flex;
    justify-content: flex-end;
}
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: var(--card-alt);
    border: 1px dashed var(--border);
    border-radius: 12px;
    grid-column: 1 / -1;
}
.empty-state i {
    font-size: 48px;
    color: var(--muted);
    margin-bottom: 15px;
}
.empty-state p {
    color: var(--text);
    font-size: 15px;
}
</style>

<form method="POST" action="<?= BASE_URL ?>/panel/<?= View::escape($currentDept['slug']) ?>/social-links" id="socialLinksForm">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    
    <div class="social-links-grid" id="socialLinksContainer">
        <?php foreach ($socialLinks as $index => $link): ?>
            <div class="social-link-card">
                <button type="button" class="remove-btn" onclick="this.closest('.social-link-card').remove(); checkEmpty();" title="Sil">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
                
                <div class="social-icon-preview" style="color: <?= View::escape($link['color'] ?? '#ffffff') ?>; box-shadow: 0 0 15px <?= View::escape($link['color'] ?? '#ffffff') ?>33;">
                    <i class="<?= View::escape($link['icon'] ?? 'fa-solid fa-link') ?>"></i>
                </div>

                <div class="form-group">
                    <label>Platform Adı</label>
                    <input type="text" name="platform[]" class="form-control" value="<?= View::escape($link['platform'] ?? '') ?>" placeholder="Örn: Instagram" required>
                </div>

                <div class="form-group">
                    <label>Profil URL'si</label>
                    <input type="url" name="url[]" class="form-control" value="<?= View::escape($link['url'] ?? '') ?>" placeholder="https://..." required>
                </div>

                <div class="form-group">
                    <label>İkon Sınıfı (FontAwesome)</label>
                    <input type="text" name="icon[]" class="form-control icon-input" value="<?= View::escape($link['icon'] ?? '') ?>" placeholder="Örn: fa-brands fa-instagram" oninput="updatePreview(this)">
                </div>

                <div class="form-group">
                    <label>Marka Rengi</label>
                    <div class="color-picker-wrapper">
                        <input type="color" name="color[]" value="<?= View::escape($link['color'] ?? '#ffffff') ?>" oninput="this.nextElementSibling.value = this.value; updatePreview(this)">
                        <input type="text" class="form-control" value="<?= View::escape($link['color'] ?? '#ffffff') ?>" oninput="this.previousElementSibling.value = this.value; updatePreview(this)" style="font-family: monospace;">
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="empty-state" id="emptyState" style="display: <?= empty($socialLinks) ? 'block' : 'none' ?>;">
        <i class="fa-solid fa-share-nodes"></i>
        <p>Henüz sosyal medya hesabı eklenmemiş.</p>
        <p style="color:var(--muted); font-size:13px; margin-top:5px;">Yukarıdaki "Yeni Hesap Ekle" butonuna tıklayarak ilk hesabınızı ekleyebilirsiniz.</p>
    </div>

    <div class="save-btn-wrapper">
        <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-size: 15px; background: var(--accent); border-color: var(--accent);">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Değişiklikleri Kaydet
        </button>
    </div>
</form>

<template id="socialLinkTemplate">
    <div class="social-link-card">
        <button type="button" class="remove-btn" onclick="this.closest('.social-link-card').remove(); checkEmpty();" title="Sil">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
        </button>
        
        <div class="social-icon-preview" style="color: var(--accent); box-shadow: 0 0 15px var(--accent);">
            <i class="fa-solid fa-link"></i>
        </div>

        <div class="form-group">
            <label>Platform Adı</label>
            <input type="text" name="platform[]" class="form-control" placeholder="Örn: Instagram" required>
        </div>

        <div class="form-group">
            <label>Profil URL'si</label>
            <input type="url" name="url[]" class="form-control" placeholder="https://..." required>
        </div>

        <div class="form-group">
            <label>İkon Sınıfı (FontAwesome)</label>
            <input type="text" name="icon[]" class="form-control icon-input" placeholder="Örn: fa-brands fa-instagram" oninput="updatePreview(this)">
        </div>

        <div class="form-group">
            <label>Marka Rengi</label>
            <div class="color-picker-wrapper">
                <input type="color" name="color[]" value="#ffffff" oninput="this.nextElementSibling.value = this.value; updatePreview(this)">
                <input type="text" class="form-control" value="#ffffff" oninput="this.previousElementSibling.value = this.value; updatePreview(this)" style="font-family: monospace;">
            </div>
        </div>
    </div>
</template>

<script>
function addSocialLink() {
    const container = document.getElementById('socialLinksContainer');
    const template = document.getElementById('socialLinkTemplate');
    const clone = template.content.cloneNode(true);
    container.appendChild(clone);
    checkEmpty();
}

function checkEmpty() {
    const container = document.getElementById('socialLinksContainer');
    const emptyState = document.getElementById('emptyState');
    if (container.children.length === 0) {
        emptyState.style.display = 'block';
    } else {
        emptyState.style.display = 'none';
    }
}

function updatePreview(element) {
    const card = element.closest('.social-link-card');
    const preview = card.querySelector('.social-icon-preview');
    const iconInput = card.querySelector('.icon-input');
    const colorInput = card.querySelector('input[type="color"]');
    
    if (iconInput.value) {
        preview.innerHTML = `<i class="${iconInput.value}"></i>`;
    } else {
        preview.innerHTML = `<i class="fa-solid fa-link"></i>`;
    }
    
    preview.style.color = colorInput.value;
    preview.style.boxShadow = `0 0 15px ${colorInput.value}33`;
}

document.addEventListener('DOMContentLoaded', () => {
    // Check initial color for new template dynamically from CSS var
    const accent = getComputedStyle(document.documentElement).getPropertyValue('--accent').trim();
    if (accent) {
        const template = document.getElementById('socialLinkTemplate');
        const colorInput = template.content.querySelector('input[type="color"]');
        const textInput = template.content.querySelector('.color-picker-wrapper .form-control');
        const preview = template.content.querySelector('.social-icon-preview');
        // color input needs hex
        // this is just a best-effort, we default to #ffffff anyway.
    }
});
</script>
