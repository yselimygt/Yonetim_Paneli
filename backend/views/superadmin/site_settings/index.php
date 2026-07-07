<?php
$pageTitle = 'Site Ayarları';
$brandImageFields = [
    'favicon' => 'Favicon',
    'navbarLogo' => 'Navbar Logo',
    'footerLogo' => 'Footer Logo',
    'popupLogo' => 'Pop-up Logo',
    'heroMascot' => 'Hero Maskot',
    'faqMascot' => 'SSS Maskot'
];
?>

<div class="page-header settings-page-header">
    <div>
        <h2>Genel Site Ayarları</h2>
        <p style="color:var(--muted); font-size:13px; margin-top:4px;">Web sitesinin temel bileşenlerini ve içeriklerini buradan yönetebilirsiniz.</p>
    </div>
</div>

<!-- Tabs -->
<div class="settings-tabs">
    <button class="tab-btn active" onclick="switchTab(this, 'hero')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l2.8 6.2L21 9l-4.6 4.4 1.2 6.4L12 16.6 6.4 19.8l1.2-6.4L3 9l6.2-.8L12 2z"/></svg>
        <span class="tab-label">Hero</span>
    </button>
    <button class="tab-btn" onclick="switchTab(this, 'popup')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
        <span class="tab-label">Duyuru Pop-up</span>
    </button>
    <button class="tab-btn" onclick="switchTab(this, 'brand')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 3v3m0 12v3m9-9h-3M6 12H3m15.4-6.4-2.1 2.1M7.7 16.3l-2.1 2.1m12.8 0-2.1-2.1M7.7 7.7 5.6 5.6"/></svg>
        <span class="tab-label">Marka</span>
    </button>
    <button class="tab-btn" onclick="switchTab(this, 'stats')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        <span class="tab-label">İstatistikler</span>
    </button>
    <button class="tab-btn" onclick="switchTab(this, 'sponsors')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 17 22 12"/></svg>
        <span class="tab-label">Sponsorlar</span>
    </button>
    <button class="tab-btn" onclick="switchTab(this, 'faqs')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <span class="tab-label">Sık Sorulan Sorular</span>
    </button>
    <button class="tab-btn" onclick="switchTab(this, 'routing')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
        <span class="tab-label">Form Yönlendirme</span>
    </button>
    <button class="tab-btn" onclick="switchTab(this, 'maintenance')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
        <span class="tab-label">Bakım Modu</span>
    </button>
</div>

<!-- 0. HERO -->
<div id="tab-hero" class="tab-content active">
    <div class="card">
        <div class="card-header">
            Hero Alanı
        </div>
        <div class="card-body">
            <p style="color:var(--muted); font-size:13px; margin-bottom:24px;">Ana sayfanın giriş başlığı, açıklaması ve çağrı butonları.</p>

            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-settings/hero">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Başlık 1. Satır</label>
                        <input type="text" name="titleLine1" class="form-control" value="<?= View::escape($hero['titleLine1'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Başlık 2. Satır</label>
                        <input type="text" name="titleLine2" class="form-control" value="<?= View::escape($hero['titleLine2'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Açıklama</label>
                    <textarea name="description" class="form-control" rows="3"><?= View::escape($hero['description'] ?? '') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">1. Buton Yazısı</label>
                        <input type="text" name="primaryCta_label" class="form-control" value="<?= View::escape($hero['primaryCta']['label'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">1. Buton Linki</label>
                        <input type="text" name="primaryCta_href" class="form-control" value="<?= View::escape($hero['primaryCta']['href'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">2. Buton Yazısı</label>
                        <input type="text" name="secondaryCta_label" class="form-control" value="<?= View::escape($hero['secondaryCta']['label'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">2. Buton Linki</label>
                        <input type="text" name="secondaryCta_href" class="form-control" value="<?= View::escape($hero['secondaryCta']['href'] ?? '') ?>">
                    </div>
                </div>

                <div class="dynamic-item mt-4">
                    <div class="item-header" style="margin-bottom:12px; padding-bottom:8px;">
                        <strong style="display:flex; align-items:center; gap:8px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--accent);"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            3D Galeri Görselleri
                        </strong>
                        <span style="font-size:12px; color:var(--muted); font-weight:normal;">Ana sayfa 3D dönen galeri (Minimum 4 görsel tavsiye edilir)</span>
                    </div>
                    <div id="hero-gallery-container" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap:16px;">
                        <?php 
                        $heroGallery = $hero['heroGallery'] ?? [];
                        if (empty($heroGallery)): $heroGallery = ['']; endif; 
                        ?>
                        <?php foreach ($heroGallery as $idx => $gImage): ?>
                        <div class="image-picker hero-gallery-item" data-picker="heroGallery_<?= $idx ?>" style="position:relative;">
                            <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.hero-gallery-item').remove()" title="Sil" style="position:absolute; top:-10px; right:-10px; z-index:10; background:var(--bg); border:1px solid var(--border); border-radius:50%; width:28px; height:28px; padding:0; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 5px rgba(0,0,0,0.2);">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                            <div class="image-picker__head">
                                <label class="form-label" for="heroGallery_url_<?= $idx ?>">Galeri Görseli</label>
                                <button type="button" class="btn btn-outline image-picker__library" onclick="openImageLibrary('heroGallery_<?= $idx ?>')" style="padding:4px 8px; font-size:11px;">Dosyadan Seç</button>
                            </div>
                            <label class="image-picker__drop" for="heroGallery_<?= $idx ?>_file">
                                <input type="file" id="heroGallery_<?= $idx ?>_file" name="heroGallery_file_<?= $idx ?>" accept="image/*" onchange="previewPickedImage(this, 'heroGallery_<?= $idx ?>')">
                                <span class="image-picker__preview" id="heroGallery_<?= $idx ?>_preview_wrap">
                                    <?php if (trim($gImage) !== ''): ?>
                                        <img id="heroGallery_<?= $idx ?>_preview" src="<?= View::escape($gImage) ?>" alt="">
                                    <?php else: ?>
                                        <img id="heroGallery_<?= $idx ?>_preview" src="" alt="" style="display:none;">
                                        <small>Görsel Yok</small>
                                    <?php endif; ?>
                                </span>
                                <span class="image-picker__hint">Sürükle bırak veya dokun</span>
                            </label>
                            <input type="text" id="heroGallery_<?= $idx ?>" name="heroGallery_url_<?= $idx ?>" class="form-control image-picker__url" value="<?= View::escape($gImage) ?>" placeholder="Görsel URL">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline" style="margin-top:12px; font-size:12px; padding:6px 12px;" onclick="addHeroGalleryItem()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Yeni Görsel Ekle
                    </button>
                </div>

                <script>
                function initDropZone(dropZone) {
                    const picker = dropZone.closest('.image-picker');
                    const fileInput = dropZone.querySelector('input[type="file"]');
                    if (!picker || !fileInput) return;
                    const target = picker.getAttribute('data-picker');

                    ['dragenter', 'dragover'].forEach(eventName => {
                        dropZone.addEventListener(eventName, event => {
                            event.preventDefault();
                            dropZone.classList.add('is-dragover');
                        });
                    });

                    ['dragleave', 'drop'].forEach(eventName => {
                        dropZone.addEventListener(eventName, event => {
                            event.preventDefault();
                            dropZone.classList.remove('is-dragover');
                        });
                    });

                    dropZone.addEventListener('drop', event => {
                        const file = event.dataTransfer.files && event.dataTransfer.files[0];
                        if (!file || !file.type.startsWith('image/')) return;
                        const transfer = new DataTransfer();
                        transfer.items.add(file);
                        fileInput.files = transfer.files;
                        previewPickedImage(fileInput, target);
                    });
                }

                function addHeroGalleryItem() {
                    const container = document.getElementById('hero-gallery-container');
                    const idx = Date.now();
                    const html = `
                        <div class="image-picker hero-gallery-item" data-picker="heroGallery_${idx}" style="position:relative;">
                            <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.hero-gallery-item').remove()" title="Sil" style="position:absolute; top:-10px; right:-10px; z-index:10; background:var(--bg); border:1px solid var(--border); border-radius:50%; width:28px; height:28px; padding:0; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 5px rgba(0,0,0,0.2);">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                            <div class="image-picker__head">
                                <label class="form-label" for="heroGallery_url_${idx}">Galeri Görseli</label>
                                <button type="button" class="btn btn-outline image-picker__library" onclick="openImageLibrary('heroGallery_${idx}')" style="padding:4px 8px; font-size:11px;">Dosyadan Seç</button>
                            </div>
                            <label class="image-picker__drop" for="heroGallery_${idx}_file">
                                <input type="file" id="heroGallery_${idx}_file" name="heroGallery_file_${idx}" accept="image/*" onchange="previewPickedImage(this, 'heroGallery_${idx}')">
                                <span class="image-picker__preview" id="heroGallery_${idx}_preview_wrap">
                                    <img id="heroGallery_${idx}_preview" src="" alt="" style="display:none;">
                                    <small>Görsel Yok</small>
                                </span>
                                <span class="image-picker__hint">Sürükle bırak veya dokun</span>
                            </label>
                            <input type="text" id="heroGallery_${idx}" name="heroGallery_url_${idx}" class="form-control image-picker__url" placeholder="Görsel URL">
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', html);
                    const newDropZone = container.lastElementChild.querySelector('.image-picker__drop');
                    if (newDropZone) {
                        initDropZone(newDropZone);
                    }
                }
                </script>

                <div class="settings-save-bar" style="border-top:1px solid var(--border); margin-top:24px; padding-top:20px; display:flex; justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Hero Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 0.1 BRAND & CONTACT -->
<div id="tab-brand" class="tab-content" style="display:none;">
    <div class="card">
        <div class="card-header">
            Marka & İletişim
        </div>
        <div class="card-body">
            <p style="color:var(--muted); font-size:13px; margin-bottom:24px;">Frontend logoları, maskot görselleri, iletişim bilgileri ve sosyal medya linkleri.</p>

            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-settings/brand-contact" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                <div class="dynamic-item">
                    <div class="item-header"><strong>Logolar ve Görseller</strong></div>
                    <div class="brand-image-grid">
                        <?php foreach ($brandImageFields as $field => $label): ?>
                            <?php $currentImage = trim((string)($brand[$field] ?? '')); ?>
                            <div class="image-picker" data-picker="<?= View::escape($field) ?>">
                                <div class="image-picker__head">
                                    <label class="form-label" for="<?= View::escape($field) ?>"><?= View::escape($label) ?></label>
                                    <button type="button" class="btn btn-outline image-picker__library" onclick="openImageLibrary('<?= View::escape($field) ?>')">Dosyadan Seç</button>
                                </div>
                                <label class="image-picker__drop" for="<?= View::escape($field) ?>_file">
                                    <input type="file" id="<?= View::escape($field) ?>_file" name="<?= View::escape($field) ?>_file" accept="image/*" onchange="previewPickedImage(this, '<?= View::escape($field) ?>')">
    <span class="image-picker__preview" id="<?= View::escape($field) ?>_preview_wrap">
        <?php if ($currentImage !== ''): ?>
            <img id="<?= View::escape($field) ?>_preview" src="<?= View::escape($currentImage) ?>" alt="<?= View::escape($label) ?>">
        <?php else: ?>
                                            <img id="<?= View::escape($field) ?>_preview" src="" alt="" style="display:none;">
                                            <small>Görsel Yok</small>
                                        <?php endif; ?>
                                    </span>
                                    <span class="image-picker__hint">Sürükle bırak veya dokun</span>
                                </label>
                                <input type="text" id="<?= View::escape($field) ?>" name="<?= View::escape($field) ?>" class="form-control image-picker__url" value="<?= View::escape($currentImage) ?>" placeholder="Görsel URL">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="dynamic-item">
                    <div class="item-header"><strong>İletişim Bilgileri</strong></div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Marka Adı</label>
                            <input type="text" name="brandName" class="form-control" value="<?= View::escape($brand['brandName'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">E-posta</label>
                            <input type="email" name="email" class="form-control" value="<?= View::escape($contact['email'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telefon</label>
                        <input type="text" name="phone" class="form-control" value="<?= View::escape($contact['phone'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Adres</label>
                        <textarea name="address" class="form-control" rows="2"><?= View::escape($contact['address'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Adres Detayı</label>
                        <input type="text" name="addressDetail" class="form-control" value="<?= View::escape($contact['addressDetail'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Footer Açıklaması</label>
                        <textarea name="footerDescription" class="form-control" rows="2"><?= View::escape($brand['footerDescription'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Harita Embed URL</label>
                        <textarea name="mapEmbedUrl" class="form-control" rows="2"><?= View::escape($contact['mapEmbedUrl'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="dynamic-item">
                    <div class="item-header"><strong>Sosyal Medya Linkleri</strong></div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Instagram</label><input type="text" name="instagram" class="form-control" value="<?= View::escape($contact['socials']['instagram'] ?? '') ?>"></div>
                        <div class="form-group"><label class="form-label">LinkedIn</label><input type="text" name="linkedin" class="form-control" value="<?= View::escape($contact['socials']['linkedin'] ?? '') ?>"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">X</label><input type="text" name="x" class="form-control" value="<?= View::escape($contact['socials']['x'] ?? '') ?>"></div>
                        <div class="form-group"><label class="form-label">YouTube</label><input type="text" name="youtube" class="form-control" value="<?= View::escape($contact['socials']['youtube'] ?? '') ?>"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">TikTok</label><input type="text" name="tiktok" class="form-control" value="<?= View::escape($contact['socials']['tiktok'] ?? '') ?>"></div>
                        <div class="form-group"><label class="form-label">GitHub</label><input type="text" name="github" class="form-control" value="<?= View::escape($contact['socials']['github'] ?? '') ?>"></div>
                    </div>
                </div>

                <div class="settings-save-bar" style="border-top:1px solid var(--border); margin-top:24px; padding-top:20px; display:flex; justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary">Logo ve İletişimi Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="image-library-modal" id="imageLibraryModal" aria-hidden="true">
    <div class="image-library-modal__dialog">
        <div class="image-library-modal__header">
            <strong>Dosya Yönetiminden Görsel Seç</strong>
            <button type="button" class="btn-icon" onclick="closeImageLibrary()" aria-label="Kapat">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="image-library-toolbar">
            <button type="button" class="btn btn-outline image-library-back" id="imageLibraryBack" onclick="goImageLibraryBack()" disabled>Geri</button>
            <span id="imageLibraryPath">Ana Dizin</span>
        </div>
        <div class="image-library-grid" id="imageLibraryGrid"></div>
        <div class="image-library-empty" id="imageLibraryEmpty" style="display:none;">Bu klasörde seçilebilir resim veya video bulunamadı.</div>
    </div>
</div>

<!-- 1. POPUP -->
<div id="tab-popup" class="tab-content" style="display:none;">
    <div class="card">
        <div class="card-header">
            Duyuru Pop-up Ayarları
        </div>
        <div class="card-body">
            <p style="color:var(--muted); font-size:13px; margin-bottom:24px;">Sitenin girişinde görünen duyuru modalını buradan yönetebilirsiniz.</p>
            
            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-settings/popup">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="form-group" style="display:flex; align-items:center; gap:12px; background:var(--input-bg); padding:16px; border-radius:12px; border:1px solid var(--border);">
                    <label class="toggle-switch">
                        <input type="checkbox" name="enabled" <?= !empty($popupConfig['enabled']) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <div>
                        <strong style="display:block; font-size:14px;">Duyuruyu Aktif Et</strong>
                        <span style="font-size:12px; color:var(--muted);">Kullanıcılar siteye girdiğinde pop-up olarak gösterilir.</span>
                    </div>
                </div>

                <div class="form-row mt-4">
                    <div class="form-group">
                        <label class="form-label">Başlık</label>
                        <input type="text" name="title" class="form-control" placeholder="Örn: Erken Kayıt Başladı!" value="<?= View::escape($popupConfig['title'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Etiket (Badge)</label>
                        <input type="text" name="badge" class="form-control" placeholder="Örn: YENİ, DUYURU" value="<?= View::escape($popupConfig['badge'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Açıklama (Description)</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Pop-up içeriği buraya yazılır..."><?= View::escape($popupConfig['description'] ?? '') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">1. Buton Yazısı</label>
                        <input type="text" name="cta_label" class="form-control" placeholder="Örn: Detaylı Bilgi" value="<?= View::escape($popupConfig['cta']['label'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">1. Buton Linki</label>
                        <input type="text" name="cta_href" class="form-control" placeholder="https://..." value="<?= View::escape($popupConfig['cta']['href'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">2. Buton Yazısı</label>
                        <input type="text" name="secondaryCta_label" class="form-control" placeholder="İsteğe bağlı" value="<?= View::escape($popupConfig['secondaryCta']['label'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">2. Buton Linki</label>
                        <input type="text" name="secondaryCta_href" class="form-control" placeholder="İsteğe bağlı" value="<?= View::escape($popupConfig['secondaryCta']['href'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group" style="max-width: 200px;">
                    <label class="form-label">Otomatik Kapanma (Saniye)</label>
                    <div style="position:relative;">
                        <input type="number" name="autoCloseSec" class="form-control" style="padding-right:40px;" value="<?= (int)($popupConfig['autoCloseSec'] ?? 5) ?>">
                        <span style="position:absolute; right:14px; top:50%; transform:translateY(-50%); color:var(--muted); font-size:12px;">sn</span>
                    </div>
                </div>

                <div class="settings-save-bar" style="border-top:1px solid var(--border); margin-top:24px; padding-top:20px; display:flex; justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Ayarları Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. STATS -->
<div id="tab-stats" class="tab-content" style="display:none;">
    <div class="card">
        <div class="card-header">
            İstatistikler (Metrics)
        </div>
        <div class="card-body">
            <p style="color:var(--muted); font-size:13px; margin-bottom:24px;">Sitenin ana sayfasında yer alan 4 metrik alanı.</p>
            
            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-settings/stats">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div id="stats-container">
                    <?php if (empty($stats)): $stats = [['label'=>'', 'value'=>'', 'desc'=>'']]; endif; ?>
                    <?php foreach ($stats as $idx => $s): ?>
                    <div class="dynamic-item">
                        <div class="item-header">
                            <strong>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--accent);"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                                Metrik #<?= $idx + 1 ?>
                            </strong>
                            <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.dynamic-item').remove()" title="Sil">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Başlık (Örn: Aktif Üye)</label>
                                <input type="text" name="label[]" class="form-control" value="<?= View::escape($s['label']) ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Değer (Örn: 500+)</label>
                                <input type="text" name="value[]" class="form-control" value="<?= View::escape($s['value']) ?>">
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Açıklama</label>
                            <input type="text" name="desc[]" class="form-control" value="<?= View::escape($s['desc']) ?>">
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="settings-save-bar" style="border-top:1px solid var(--border); margin-top:24px; padding-top:20px; display:flex; justify-content:space-between; align-items:center;">
                    <button type="button" class="btn btn-outline" onclick="addStat()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Yeni Metrik Ekle
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        İstatistikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 3. SPONSORS -->
<div id="tab-sponsors" class="tab-content" style="display:none;">
    <div class="card">
        <div class="card-header">
            Sponsor Logoları
        </div>
        <div class="card-body">
            <p style="color:var(--muted); font-size:13px; margin-bottom:24px;">Destekçilerin ve sponsorların logolarını anasayfada gösterir.</p>

            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-settings/sponsors">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div id="sponsors-container" class="sponsors-grid">
                    <?php if (empty($allSponsors)): ?>
                        <div class="empty-state" style="grid-column:1/-1; padding:24px; text-align:center; color:var(--muted); border:1px dashed var(--border); border-radius:12px;">
                            Sponsor modülünde kayıt bulunmuyor.
                        </div>
                    <?php else: ?>
                        <?php foreach ($allSponsors as $sponsor): ?>
                        <?php
                            $sponsorId = (int)$sponsor['id'];
                            $isSelected = in_array($sponsorId, $selectedSponsorIds ?? [], true);
                            $logoUrl = trim((string)($sponsor['logo_url'] ?? ''));
                            $logoSrc = $logoUrl !== '' ? BASE_URL . '/' . ltrim($logoUrl, '/') : '';
                        ?>
                        <label class="sponsor-select-card <?= $isSelected ? 'is-selected' : '' ?>">
                            <input type="checkbox" name="sponsor_ids[]" value="<?= $sponsorId ?>" <?= $isSelected ? 'checked' : '' ?>>
                            <span class="sponsor-select-card__check">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </span>
                            <span class="sponsor-select-card__logo">
                                <?php if ($logoSrc): ?>
                                    <img src="<?= View::escape($logoSrc) ?>" alt="<?= View::escape($sponsor['name']) ?>">
                                <?php else: ?>
                                    <span>Logo Yok</span>
                                <?php endif; ?>
                            </span>
                            <span class="sponsor-select-card__body">
                                <strong><?= View::escape($sponsor['name']) ?></strong>
                                <span>
                                    <?= View::escape($sponsor['sponsorship_type'] ?: 'Sponsor') ?>
                                    · <?= $sponsor['status'] === 'active' ? 'Aktif' : 'Pasif' ?>
                                </span>
                            </span>
                        </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <div class="settings-save-bar" style="border-top:1px solid var(--border); margin-top:24px; padding-top:20px; display:flex; justify-content:space-between; align-items:center;">
                    <span style="color:var(--muted); font-size:12px;">Seçilen sponsorlar web sitesinde görünür.</span>
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Sponsorları Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 4. FAQS -->
<div id="tab-faqs" class="tab-content" style="display:none;">
    <div class="card">
        <div class="card-header">
            Sık Sorulan Sorular (FAQ)
        </div>
        <div class="card-body">
            <p style="color:var(--muted); font-size:13px; margin-bottom:24px;">Anasayfada yer alan kullanıcı soruları ve cevapları.</p>

            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-settings/faqs">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div id="faqs-container">
                    <?php if (empty($faqs)): $faqs = [['q'=>'', 'a'=>'']]; endif; ?>
                    <?php foreach ($faqs as $idx => $f): ?>
                    <div class="dynamic-item">
                        <div class="item-header">
                            <strong>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--accent);"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                Soru #<?= $idx + 1 ?>
                            </strong>
                            <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.dynamic-item').remove()" title="Sil">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Soru</label>
                            <input type="text" name="q[]" class="form-control" value="<?= View::escape($f['q']) ?>">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Cevap</label>
                            <textarea name="a[]" class="form-control" rows="2"><?= View::escape($f['a']) ?></textarea>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="settings-save-bar" style="border-top:1px solid var(--border); margin-top:24px; padding-top:20px; display:flex; justify-content:space-between; align-items:center;">
                    <button type="button" class="btn btn-outline" onclick="addFaq()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Soru Ekle
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        SSS Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 5. FORM ROUTING -->
<div id="tab-routing" class="tab-content" style="display:none;">
    <div class="card">
        <div class="card-header">
            Form Yönlendirmeleri (Routing)
        </div>
        <div class="card-body">
            <p style="color:var(--muted); font-size:13px; margin-bottom:24px;">Web sitesinden gelen formların hangi departmanların Gelen Kutusu'na düşeceğini ayarlayın. Bir form için birden fazla departman seçebilirsiniz.</p>

            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-settings/form-routing">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div id="routing-container">
                    <?php 
                    // Normalize all formats to simple dept_ids array
                    $normalizedRouting = [];
                    foreach (($formRouting ?: []) as $type => $rule) {
                        $deptIds = [];
                        if (isset($rule['targets'])) {
                            foreach ($rule['targets'] as $t) {
                                if (!empty($t['department_id'])) $deptIds[] = (int)$t['department_id'];
                            }
                        } elseif (isset($rule['department_id']) && !empty($rule['department_id'])) {
                            $deptIds[] = (int)$rule['department_id'];
                        } elseif (is_array($rule)) {
                            $deptIds = array_filter(array_map('intval', $rule));
                        }
                        $normalizedRouting[$type] = $deptIds;
                    }
                    if (empty($normalizedRouting)) {
                        $normalizedRouting = ['sponsorship' => ['']];
                    }
                    ?>
                    <?php foreach ($normalizedRouting as $ruleType => $deptIds): ?>
                    <?php $ruleIdx = array_search($ruleType, array_keys($normalizedRouting)); ?>
                    <div class="routing-rule dynamic-item" data-rule-index="<?= $ruleIdx ?>">
                        <div class="item-header">
                            <strong style="display:flex; align-items:center; gap:8px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--accent);"><path d="M9 18l6-6-6-6"/></svg>
                                <select name="rules[<?= $ruleIdx ?>][type]" class="form-control" style="width:240px; display:inline-block;">
                                    <option value="">Form Türü Seçin...</option>
                                    <?php foreach (FORM_TYPES as $typeKey => $typeLabel): ?>
                                    <option value="<?= $typeKey ?>" <?= $ruleType === $typeKey ? 'selected' : '' ?>><?= View::escape($typeLabel) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </strong>
                            <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.routing-rule').remove()" title="Kuralı Sil">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>

                        <div class="targets-container" style="margin-top:12px; display:flex; flex-direction:column; gap:8px;">
                            <?php foreach (($deptIds ?: ['']) as $tIdx => $selDeptId): ?>
                            <div class="target-row" style="display:flex; align-items:center; gap:10px;">
                                <select name="rules[<?= $ruleIdx ?>][dept_ids][]" class="form-control">
                                    <option value="">Departman Seçin...</option>
                                    <?php foreach ($departments as $d): ?>
                                    <option value="<?= $d['id'] ?>" <?= $selDeptId == $d['id'] ? 'selected' : '' ?>><?= View::escape($d['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.target-row').remove()" title="Kaldır" style="flex-shrink:0;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <button type="button" class="btn btn-outline" style="margin-top:10px; font-size:12px; padding:6px 12px;" onclick="addTarget(this, <?= $ruleIdx ?>)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Departman Ekle
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="settings-save-bar" style="border-top:1px solid var(--border); margin-top:24px; padding-top:20px; display:flex; justify-content:space-between; align-items:center;">
                    <button type="button" class="btn btn-outline" onclick="addRoutingRule()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Yeni Kural Ekle
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Ayarları Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 7. MAINTENANCE MODE -->
<div id="tab-maintenance" class="tab-content" style="display:none;">
    <div class="card">
        <div class="card-header">
            Bakım Modu
        </div>
        <div class="card-body">
            <p style="color:var(--muted); font-size:13px; margin-bottom:24px;">Siteyi geçici olarak kullanıma kapatmak için bakım modunu aktifleştirebilirsiniz. Sadece yöneticiler panele erişmeye devam edebilir.</p>

            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-settings/maintenance">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="form-group" style="margin-bottom:20px;">
                    <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                        <input type="checkbox" name="enabled" <?= !empty($maintenanceMode['enabled']) ? 'checked' : '' ?>>
                        <span style="font-weight:500; color:var(--text);">Bakım Modu Aktif</span>
                    </label>
                </div>

                <div class="form-group" style="margin-bottom:20px;">
                    <label>Başlık</label>
                    <input type="text" name="title" class="form-control" value="<?= View::escape($maintenanceMode['title'] ?? '') ?>" placeholder="Örn: Bakım Molası / Servis Dışı">
                </div>

                <div class="form-group">
                    <label>Açıklama</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Örn: Sistemlerimizi güncelliyoruz..."><?= View::escape($maintenanceMode['description'] ?? '') ?></textarea>
                </div>

                <div class="settings-save-bar" style="border-top:1px solid var(--border); margin-top:24px; padding-top:20px; display:flex; justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Ayarları Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
    scrollbar-width: none; /* Firefox */
}
.settings-tabs::-webkit-scrollbar { display: none; } /* Chrome */

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
    background: rgba(255,255,255,0.03);
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

/* Toggle Switch */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
    flex-shrink: 0;
}
.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: rgba(255,255,255,0.1);
    transition: .3s;
    border-radius: 24px;
    border: 1px solid var(--border);
}
.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 2px;
    bottom: 2px;
    background-color: var(--muted);
    transition: .3s;
    border-radius: 50%;
}
input:checked + .toggle-slider {
    background-color: rgba(0, 188, 212, 0.2);
    border-color: var(--accent);
}
input:checked + .toggle-slider:before {
    transform: translateX(20px);
    background-color: var(--accent);
}

/* Dynamic Items */
.dynamic-item {
    background: rgba(255,255,255,0.015);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 16px;
    position: relative;
    transition: all 0.2s;
}
.dynamic-item:hover {
    border-color: rgba(255,255,255,0.05);
    background: rgba(255,255,255,0.03);
}
.item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255,255,255,0.03);
}
.item-header strong {
    font-size: 14px;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Icon Buttons */
.btn-icon {
    background: transparent;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    transition: all 0.2s;
}
.btn-danger-icon {
    color: var(--muted);
}
.btn-danger-icon:hover {
    background: rgba(239,68,68,0.1);
    color: var(--danger);
}

.brand-image-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}
.image-picker {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
}
.image-picker__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}
.image-picker__head .form-label {
    margin: 0;
}
.image-picker__library {
    flex-shrink: 0;
    padding: 6px 9px;
    font-size: 11px;
    border-radius: 8px;
}
.image-picker__drop {
    display: flex;
    position: relative;
    min-height: 112px;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border: 1px dashed rgba(0,188,212,0.28);
    border-radius: 12px;
    background: rgba(0,188,212,0.035);
    cursor: pointer;
}
.image-picker__drop.is-dragover {
    border-color: var(--accent);
    background: rgba(0,188,212,0.12);
}
.image-picker__drop input[type="file"] {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
    pointer-events: none;
}
.image-picker__preview {
    display: flex;
    width: 100%;
    height: 100%;
    min-height: 112px;
    align-items: center;
    justify-content: center;
    color: var(--muted);
    font-size: 12px;
}
.image-picker__preview img {
    width: 100%;
    height: 100%;
    min-height: 112px;
    object-fit: contain;
    padding: 10px;
}
.image-picker__preview video {
    width: 100%;
    height: 100%;
    min-height: 112px;
    object-fit: cover;
}
.image-picker__hint {
    position: absolute;
    right: 8px;
    bottom: 8px;
    padding: 4px 7px;
    border-radius: 999px;
    background: rgba(0,0,0,0.58);
    color: rgba(255,255,255,0.78);
    font-size: 10px;
}
.image-picker__url {
    font-size: 11px;
}
.image-library-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 2000;
    align-items: flex-end;
    justify-content: center;
    padding: 16px;
    background: rgba(0,0,0,0.62);
}
.image-library-modal.is-open {
    display: flex;
}
.image-library-modal__dialog {
    width: min(760px, 100%);
    max-height: min(78vh, 680px);
    overflow: hidden;
    border: 1px solid var(--border);
    border-radius: 16px;
    background: var(--card);
    box-shadow: 0 18px 44px rgba(0,0,0,0.35);
}
.image-library-modal__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
}
.image-library-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-bottom: 1px solid rgba(255,255,255,0.055);
}
.image-library-toolbar span {
    min-width: 0;
    overflow: hidden;
    color: var(--muted);
    font-size: 12px;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.image-library-back {
    flex-shrink: 0;
    padding: 6px 10px;
    font-size: 11px;
    border-radius: 8px;
}
.image-library-back:disabled {
    opacity: 0.45;
    cursor: not-allowed;
}
.image-library-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 10px;
    max-height: calc(min(78vh, 680px) - 106px);
    overflow-y: auto;
    padding: 14px;
}
.image-library-card {
    display: flex;
    position: relative;
    min-width: 0;
    flex-direction: column;
    gap: 7px;
    padding: 8px;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: rgba(255,255,255,0.02);
    color: var(--text);
    cursor: pointer;
    text-align: left;
}
.image-library-card:hover {
    border-color: rgba(0,188,212,0.42);
    background: rgba(0,188,212,0.08);
}
.image-library-card span {
    display: flex;
    height: 82px;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: rgba(0,0,0,0.18);
}
.image-library-card img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 5px;
}
.image-library-card video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 6px;
}
.image-library-folder span {
    color: rgba(0,188,212,0.86);
}
.image-library-folder svg {
    width: 42px;
    height: 42px;
}
.image-library-card__badge {
    position: absolute;
    top: 8px;
    right: 8px;
    padding: 3px 6px;
    border-radius: 999px;
    background: rgba(0,0,0,0.62);
    color: rgba(255,255,255,0.82);
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.04em;
}
.image-library-card strong {
    overflow: hidden;
    font-size: 11px;
    line-height: 1.25;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.image-library-empty {
    padding: 28px 16px;
    color: var(--muted);
    font-size: 13px;
    text-align: center;
}

.sponsors-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.sponsor-select-card {
    position: relative;
    display: grid;
    grid-template-columns: 92px 1fr;
    gap: 14px;
    align-items: center;
    min-height: 94px;
    padding: 14px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: rgba(255,255,255,0.015);
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s, transform 0.2s;
}
.sponsor-select-card:hover {
    border-color: rgba(0,188,212,0.28);
    background: rgba(255,255,255,0.03);
}
.sponsor-select-card input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}
.sponsor-select-card.is-selected,
.sponsor-select-card:has(input:checked) {
    border-color: rgba(0,188,212,0.5);
    background: rgba(0,188,212,0.08);
}
.sponsor-select-card__check {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    border-radius: 999px;
    border: 1px solid rgba(255,255,255,0.14);
    color: transparent;
    background: rgba(0,0,0,0.16);
}
.sponsor-select-card__check svg {
    width: 12px;
    height: 12px;
}
.sponsor-select-card.is-selected .sponsor-select-card__check,
.sponsor-select-card:has(input:checked) .sponsor-select-card__check {
    border-color: var(--accent);
    color: #001014;
    background: var(--accent);
}
.sponsor-select-card__logo {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 92px;
    height: 58px;
    padding: 8px;
    border-radius: 10px;
    background: rgba(255,255,255,0.04);
    color: var(--muted);
    font-size: 11px;
}
.sponsor-select-card__logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
.sponsor-select-card__body {
    display: flex;
    min-width: 0;
    flex-direction: column;
    gap: 5px;
    padding-right: 22px;
}
.sponsor-select-card__body strong {
    overflow: hidden;
    color: var(--text);
    font-size: 14px;
    line-height: 1.2;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.sponsor-select-card__body span {
    color: var(--muted);
    font-size: 12px;
}
@media (max-width: 768px) {
    .sponsors-grid { grid-template-columns: 1fr; }
}

@media (max-width: 700px) {

@media (max-width: 700px) {
    .settings-page-header {
        margin-bottom: 10px;
    }

    .settings-page-header h2 {
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .settings-page-header p {
        display: none;
    }

    .settings-tabs {
        display: grid !important;
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 6px !important;
        margin-bottom: 16px !important;
        padding: 0 !important;
        border: 0 !important;
        background: transparent !important;
    }

    .settings-tabs .tab-btn {
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 4px !important;
        width: 100% !important;
        padding: 8px 4px !important;
        border-radius: 8px !important;
        background: rgba(255,255,255,0.025) !important;
        font-size: 9px !important;
        line-height: 1.1 !important;
        text-align: center !important;
        color: rgba(255,255,255,0.56) !important;
        white-space: normal !important;
        box-shadow: none !important;
    }

    .settings-tabs .tab-btn svg {
        flex: 0 0 auto;
        width: 14px;
        height: 14px;
        margin: 0 auto;
    }

    .settings-tabs .tab-label {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .settings-tabs .tab-btn.active {
        background: rgba(0,188,212,0.22) !important;
        color: var(--accent) !important;
    }

    .tab-content .form-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .settings-save-bar {
        flex-direction: column !important;
        gap: 12px !important;
        align-items: stretch !important;
    }
    .settings-save-bar .btn {
        width: 100% !important;
        justify-content: center !important;
    }

    .tab-content .card {
        border: 0;
        background: transparent;
        box-shadow: none;
    }

    .tab-content .card-header {
        display: none;
    }

    .tab-content .card-body {
        padding: 0;
    }

    .tab-content .card-body > p {
        display: none;
    }

    .tab-content .form-row {
        gap: 8px;
    }

    .tab-content .form-group {
        margin-bottom: 10px;
        max-width: none !important;
    }

    .tab-content .form-label {
        margin-bottom: 5px;
        font-size: 10px;
        letter-spacing: 0.04em;
        color: rgba(255,255,255,0.5);
    }

    .tab-content .form-control {
        min-height: 36px;
        border-radius: 9px;
        font-size: 12px;
        background: rgba(255,255,255,0.035);
        border-color: rgba(255,255,255,0.055);
    }

    .dynamic-item {
        margin-bottom: 9px;
        padding: 11px !important;
        border-radius: 10px;
        background: rgba(255,255,255,0.02);
        border-color: rgba(255,255,255,0.055);
        box-shadow: none;
    }

    .item-header {
        align-items: center;
        flex-direction: row;
        gap: 8px;
        margin-bottom: 8px;
        padding-bottom: 0;
        border-bottom: 0;
    }

    .item-header strong {
        min-width: 0;
        font-size: 12px;
    }

    .item-header strong svg {
        width: 13px;
        height: 13px;
    }


    .settings-tabs .tab-btn.active svg {
        transform: none;
    }

    .tab-content .card {
        border: 0;
        background: transparent;
        box-shadow: none;
    }

    .tab-content .card-header {
        display: none;
    }

    .tab-content .card-body {
        padding: 0;
    }

    .tab-content .card-body > p {
        display: none;
    }

    .tab-content .form-row {
        gap: 8px;
    }

    .tab-content .form-group {
        margin-bottom: 10px;
        max-width: none !important;
    }

    .tab-content .form-label {
        margin-bottom: 5px;
        font-size: 10px;
        letter-spacing: 0.04em;
        color: rgba(255,255,255,0.5);
    }

    .tab-content .form-control {
        min-height: 36px;
        border-radius: 9px;
        font-size: 12px;
        background: rgba(255,255,255,0.035);
        border-color: rgba(255,255,255,0.055);
    }

    .dynamic-item {
        margin-bottom: 9px;
        padding: 11px !important;
        border-radius: 10px;
        background: rgba(255,255,255,0.02);
        border-color: rgba(255,255,255,0.055);
        box-shadow: none;
    }

    .item-header {
        align-items: center;
        flex-direction: row;
        gap: 8px;
        margin-bottom: 8px;
        padding-bottom: 0;
        border-bottom: 0;
    }

    .item-header strong {
        min-width: 0;
        font-size: 12px;
    }

    .item-header strong svg {
        width: 13px;
        height: 13px;
    }

    .item-header strong select {
        min-width: 0;
        width: 100% !important;
        font-size: 11px;
    }

    .btn-icon {
        flex: 0 0 34px;
        width: 34px;
        height: 34px;
    }

    .sponsor-url-row,
    .target-row {
        align-items: flex-end !important;
        flex-direction: row !important;
        gap: 8px !important;
    }

    .sponsor-url-row .btn-icon,
    .target-row .btn-icon {
        width: 34px !important;
        margin-top: 0 !important;
    }

    .brand-image-grid {
        grid-template-columns: 1fr;
        gap: 9px;
    }

    .image-picker__drop,
    .image-picker__preview,
    .image-picker__preview img {
        min-height: 88px;
    }

    .image-picker__library {
        padding: 5px 7px;
        font-size: 10px;
    }

    .image-picker__hint {
        font-size: 9px;
    }

    .image-library-modal {
        align-items: flex-end;
        padding: 10px;
    }

    .image-library-modal__dialog {
        max-height: 82vh;
        border-radius: 14px 14px 10px 10px;
    }

    .image-library-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
        max-height: calc(82vh - 104px);
        padding: 10px;
    }

    .image-library-card span {
        height: 74px;
    }

    .sponsors-grid {
        gap: 8px;
    }

    .sponsor-select-card {
        grid-template-columns: 74px 1fr;
        min-height: 74px;
        padding: 9px;
        border-radius: 10px;
    }

    .sponsor-select-card__logo {
        width: 74px;
        height: 46px;
        padding: 6px;
    }

    .sponsor-select-card__body {
        gap: 3px;
        padding-right: 20px;
    }

    .sponsor-select-card__body strong {
        font-size: 12px;
    }

    .sponsor-select-card__body span {
        font-size: 10px;
    }

    .sponsor-select-card__check {
        top: 8px;
        right: 8px;
        width: 20px;
        height: 20px;
    }

    .settings-save-bar {
        align-items: center !important;
        flex-direction: row !important;
        gap: 8px;
        margin-top: 12px !important;
        padding-top: 12px !important;
        border-top-color: rgba(255,255,255,0.06) !important;
    }

    .settings-save-bar .btn {
        flex: 1 1 0;
        width: auto !important;
        min-height: 34px;
        justify-content: center;
        padding: 8px 9px;
        font-size: 11px;
        line-height: 1.1;
        white-space: normal;
        border-radius: 9px;
        box-shadow: none;
    }

    .settings-save-bar .btn svg {
        width: 13px;
        height: 13px;
    }

    .toggle-switch {
        width: 38px;
        height: 22px;
    }

    .toggle-slider:before {
        width: 16px;
        height: 16px;
    }

    input:checked + .toggle-slider:before {
        transform: translateX(16px);
    }
}

.mt-4 { margin-top: 24px; }
</style>

<script>
const DRIVE_MEDIA_ITEMS = <?= json_encode($driveMediaItems ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
let activeImageTarget = null;
let currentLibraryFolderId = null;

function getImagePicker(target) {
    return document.querySelector(`.image-picker[data-picker="${target}"]`);
}

function setImageValue(target, url, mediaType = 'image') {
    const input = document.getElementById(target);
    const fileInput = document.getElementById(target + '_file');
    const preview = document.getElementById(target + '_preview');
    if (!input || !preview) return;

    input.value = url;
    if (fileInput) fileInput.value = '';
    setImagePreview(target, url, mediaType);
}

function setImagePreview(target, url, mediaType = 'image') {
    const previewWrap = document.getElementById(target + '_preview_wrap');
    if (!previewWrap) return;

    previewWrap.querySelectorAll('video').forEach(video => video.remove());
    let preview = document.getElementById(target + '_preview');
    if (!preview) {
        preview = document.createElement('img');
        preview.id = target + '_preview';
        preview.alt = '';
        previewWrap.prepend(preview);
    }

    if (mediaType === 'video') {
        preview.style.display = 'none';
        const video = document.createElement('video');
        video.src = url;
        video.muted = true;
        video.playsInline = true;
        video.controls = false;
        video.style.width = '100%';
        video.style.height = '100%';
        video.style.objectFit = 'cover';
        previewWrap.prepend(video);
    } else {
        preview.src = url;
        preview.style.display = 'block';
    }

    const emptyText = previewWrap.querySelector('small');
    if (emptyText) emptyText.style.display = 'none';
}

function previewPickedImage(input, target) {
    const file = input.files && input.files[0];
    if (!file) return;
    setImagePreview(target, URL.createObjectURL(file));
}

function openImageLibrary(target) {
    activeImageTarget = target;
    currentLibraryFolderId = null;
    renderImageLibrary();
    const modal = document.getElementById('imageLibraryModal');
    if (!modal) return;
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
}

function closeImageLibrary() {
    const modal = document.getElementById('imageLibraryModal');
    if (!modal) return;
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
}

function selectLibraryImage(url, mediaType = 'image') {
    if (activeImageTarget) {
        setImageValue(activeImageTarget, url, mediaType);
    }
    closeImageLibrary();
}

function getLibraryChildren(parentId) {
    return DRIVE_MEDIA_ITEMS.filter(item => {
        const itemParent = item.parent_id === undefined ? null : item.parent_id;
        return String(itemParent ?? 'root') === String(parentId ?? 'root');
    });
}

function libraryFolderHasMedia(folderId) {
    const children = getLibraryChildren(folderId);
    return children.some(item => {
        if (item.type === 'file') return true;
        return libraryFolderHasMedia(item.id);
    });
}

function getLibraryPath(folderId) {
    if (folderId === null) return 'Ana Dizin';

    const parts = [];
    let current = DRIVE_MEDIA_ITEMS.find(item => String(item.id) === String(folderId));
    while (current) {
        parts.unshift(current.name);
        if (current.parent_id === null || current.parent_id === undefined) break;
        current = DRIVE_MEDIA_ITEMS.find(item => String(item.id) === String(current.parent_id));
    }

    return parts.length ? 'Ana Dizin / ' + parts.join(' / ') : 'Ana Dizin';
}

function openLibraryFolder(folderId) {
    currentLibraryFolderId = folderId;
    renderImageLibrary();
}

function goImageLibraryBack() {
    if (currentLibraryFolderId === null) return;

    const current = DRIVE_MEDIA_ITEMS.find(item => String(item.id) === String(currentLibraryFolderId));
    currentLibraryFolderId = current && current.parent_id !== undefined ? current.parent_id : null;
    renderImageLibrary();
}

function createLibraryCard(item) {
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'image-library-card' + (item.type === 'folder' ? ' image-library-folder' : '');

    const preview = document.createElement('span');
    const title = document.createElement('strong');
    title.textContent = item.name || (item.type === 'folder' ? 'Klasör' : 'Medya');

    if (item.type === 'folder') {
        preview.innerHTML = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M10 4l2 2h8a2 2 0 0 1 2 2v9.5a2.5 2.5 0 0 1-2.5 2.5h-15A2.5 2.5 0 0 1 2 17.5v-11A2.5 2.5 0 0 1 4.5 4H10z"/></svg>';
        button.append(preview, title);
        button.addEventListener('click', () => openLibraryFolder(item.id));
        return button;
    }

    if (item.media_type === 'video') {
        const video = document.createElement('video');
        video.src = item.url;
        video.muted = true;
        video.playsInline = true;
        video.preload = 'metadata';
        preview.appendChild(video);

        const badge = document.createElement('em');
        badge.className = 'image-library-card__badge';
        badge.textContent = 'VIDEO';
        button.appendChild(badge);
    } else {
        const img = document.createElement('img');
        img.src = item.url;
        img.alt = item.name || '';
        preview.appendChild(img);
    }

    button.append(preview, title);
    button.addEventListener('click', () => selectLibraryImage(item.url, item.media_type || 'image'));
    return button;
}

function renderImageLibrary() {
    const grid = document.getElementById('imageLibraryGrid');
    const empty = document.getElementById('imageLibraryEmpty');
    const back = document.getElementById('imageLibraryBack');
    const path = document.getElementById('imageLibraryPath');
    if (!grid || !empty || !back || !path) return;

    grid.innerHTML = '';
    path.textContent = getLibraryPath(currentLibraryFolderId);
    back.disabled = currentLibraryFolderId === null;

    const items = getLibraryChildren(currentLibraryFolderId)
        .filter(item => item.type === 'file' || libraryFolderHasMedia(item.id))
        .sort((a, b) => {
            if (a.type !== b.type) return a.type === 'folder' ? -1 : 1;
            return String(a.name || '').localeCompare(String(b.name || ''), 'tr');
        });

    items.forEach(item => grid.appendChild(createLibraryCard(item)));

    const isEmpty = items.length === 0;
    grid.style.display = isEmpty ? 'none' : 'grid';
    empty.style.display = isEmpty ? 'block' : 'none';
}

document.querySelectorAll('.image-picker__drop').forEach(dropZone => {
    const picker = dropZone.closest('.image-picker');
    const fileInput = dropZone.querySelector('input[type="file"]');
    if (!picker || !fileInput) return;

    const target = picker.getAttribute('data-picker');

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, event => {
            event.preventDefault();
            dropZone.classList.add('is-dragover');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, event => {
            event.preventDefault();
            dropZone.classList.remove('is-dragover');
        });
    });

    dropZone.addEventListener('drop', event => {
        const file = event.dataTransfer.files && event.dataTransfer.files[0];
        if (!file || !file.type.startsWith('image/')) return;

        const transfer = new DataTransfer();
        transfer.items.add(file);
        fileInput.files = transfer.files;
        previewPickedImage(fileInput, target);
    });
});

document.getElementById('imageLibraryModal')?.addEventListener('click', event => {
    if (event.target.id === 'imageLibraryModal') {
        closeImageLibrary();
    }
});

document.addEventListener('keydown', event => {
    if (event.key === 'Escape') {
        closeImageLibrary();
    }
});

function switchTab(btn, tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + tabId).style.display = 'block';
    btn.classList.add('active');
}

function addStat() {
    const idx = document.querySelectorAll('#stats-container .dynamic-item').length + 1;
    const html = `
    <div class="dynamic-item">
        <div class="item-header">
            <strong>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--accent);"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Yeni Metrik
            </strong>
            <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.dynamic-item').remove()" title="Sil">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Başlık</label>
                <input type="text" name="label[]" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Değer</label>
                <input type="text" name="value[]" class="form-control">
            </div>
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Açıklama</label>
            <input type="text" name="desc[]" class="form-control">
        </div>
    </div>`;
    document.getElementById('stats-container').insertAdjacentHTML('beforeend', html);
}

function addFaq() {
    const idx = document.querySelectorAll('#faqs-container .dynamic-item').length + 1;
    const html = `
    <div class="dynamic-item">
        <div class="item-header">
            <strong>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--accent);"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Yeni Soru
            </strong>
            <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.dynamic-item').remove()" title="Sil">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
        </div>
        <div class="form-group">
            <label class="form-label">Soru</label>
            <input type="text" name="q[]" class="form-control">
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Cevap</label>
            <textarea name="a[]" class="form-control" rows="2"></textarea>
        </div>
    </div>`;
    document.getElementById('faqs-container').insertAdjacentHTML('beforeend', html);
}

// Form Türleri
const FORM_TYPES = <?= json_encode(FORM_TYPES) ?>;

function buildFormTypeOptions(selectedKey = '') {
    return Object.entries(FORM_TYPES)
        .map(([key, label]) => `<option value="${key}" ${key === selectedKey ? 'selected' : ''}>${label}</option>`)
        .join('');
}

const DEPT_OPTIONS = <?= json_encode(array_map(fn($d) => ['id' => $d['id'], 'name' => $d['name']], $departments)) ?>;

function buildDeptOptions(selectedId = '') {
    return DEPT_OPTIONS.map(d => `<option value="${d.id}" ${String(d.id) === String(selectedId) ? 'selected' : ''}>${d.name}</option>`).join('');
}

function buildTargetRow(ruleIdx) {
    return `
    <div class="target-row" style="display:flex; align-items:center; gap:10px;">
        <select name="rules[${ruleIdx}][dept_ids][]" class="form-control">
            <option value="">Departman Seçin...</option>
            ${buildDeptOptions()}
        </select>
        <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.target-row').remove()" title="Kaldır" style="flex-shrink:0;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>`;
}

function addTarget(btn, ruleIdx) {
    const container = btn.closest('.routing-rule').querySelector('.targets-container');
    container.insertAdjacentHTML('beforeend', buildTargetRow(ruleIdx));
}

function addRoutingRule() {
    const container = document.getElementById('routing-container');
    const ruleIdx = container.querySelectorAll('.routing-rule').length;
    const html = `
    <div class="routing-rule dynamic-item" data-rule-index="${ruleIdx}">
        <div class="item-header">
            <strong style="display:flex; align-items:center; gap:8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--accent);"><path d="M9 18l6-6-6-6"/></svg>
                <select name="rules[${ruleIdx}][type]" class="form-control" style="width:240px; display:inline-block;">
                    <option value="">Form Türü Seçin...</option>
                    ${buildFormTypeOptions()}
                </select>
            </strong>
            <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.routing-rule').remove()" title="Kuralı Sil">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
        </div>
        <div class="targets-container" style="margin-top:12px; display:flex; flex-direction:column; gap:8px;">
            ${buildTargetRow(ruleIdx)}
        </div>
        <button type="button" class="btn btn-outline" style="margin-top:10px; font-size:12px; padding:6px 12px;" onclick="addTarget(this, ${ruleIdx})">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Departman Ekle
        </button>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);
}
</script>
