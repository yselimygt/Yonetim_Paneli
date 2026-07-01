<?php 
$pageTitle = 'Site Galerisi';

// Kategorileri çıkar (benzersiz)
$categories = [];
foreach ($images as $img) {
    $cat = $img['category'] ?: 'Genel';
    if (!in_array($cat, $categories)) {
        $categories[] = $cat;
    }
}
sort($categories);

$selectedCategory = $_GET['cat'] ?? ($categories[0] ?? 'Genel');

// Seçili kategoriye ait resimleri filtrele
$filteredImages = array_filter($images, function($i) use ($selectedCategory) {
    return ($i['category'] ?: 'Genel') === $selectedCategory;
});
?>

<style>
/* Ana Layout - Premium Glassmorphism */
.gallery-layout { 
    display: flex; height: calc(100vh - 120px); gap: 0;
    background: var(--card-bg); border-radius: 20px; border: 1px solid var(--border); overflow: hidden;
    box-shadow: 0 20px 50px rgba(0,0,0,0.15);
}

/* Sol Menü - Finder Sidebar */
.gallery-sidebar {
    width: 280px; flex-shrink: 0; background: rgba(0,0,0,0.02);
    border-right: 1px solid var(--border); display: flex; flex-direction: column;
}
.sidebar-header {
    padding: 24px 20px; border-bottom: 1px solid var(--border);
    display: flex; justify-content: space-between; align-items: center;
}
.sidebar-header span { font-weight: 600; font-size: 15px; color: var(--text); }

.folder-list { padding: 16px 12px; margin: 0; list-style: none; overflow-y: auto; flex: 1; }
.folder-item {
    display: flex; justify-content: space-between; align-items: center;
    padding: 12px 16px; margin-bottom: 6px;
    border-radius: 12px; color: var(--text); font-size: 14px; font-weight: 500;
    text-decoration: none; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid transparent;
}
.folder-item:hover { background: var(--input-bg); transform: translateX(4px); }
.folder-item.active {
    background: var(--primary); color: white;
    box-shadow: 0 8px 16px rgba(var(--primary-rgb, 0,0,0), 0.25);
    transform: translateX(4px);
}
.badge-count {
    background: var(--bg); color: var(--text);
    padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;
    transition: all 0.2s; box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
}
.folder-item.active .badge-count { background: rgba(255,255,255,0.25); color: white; box-shadow: none; }

/* Sağ Kısım - İçerik Alanı */
.gallery-content {
    flex: 1; display: flex; flex-direction: column; background: var(--bg); position: relative;
    overflow-y: auto;
}
.gallery-header-sticky {
    position: sticky; top: 0; z-index: 50;
    background: rgba(var(--bg-rgb, 255,255,255), 0.85);
    backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--border);
    padding: 24px 32px;
    display: flex; justify-content: space-between; align-items: center;
}
.gallery-header-sticky h3 { margin: 0; font-size: 20px; font-weight: 700; display:flex; align-items:center; gap:10px; }

/* Image Grid */
.image-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 24px; padding: 32px; }
.image-card {
    border-radius: 16px; overflow: hidden; background: var(--card-bg);
    position: relative; box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    border: 1px solid var(--border); display: flex; flex-direction: column;
}
.image-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
.image-wrapper { position: relative; width: 100%; aspect-ratio: 4/3; overflow: hidden; background: var(--bg); }
.image-wrapper img {
    position: absolute; top: 0; left: 0; width: 100%; height: 100%;
    object-fit: cover; transition: transform 0.7s cubic-bezier(0.165, 0.84, 0.44, 1);
}
.image-card:hover .image-wrapper img { transform: scale(1.08); }

/* Overlay Actions */
.image-overlay {
    position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 50%, rgba(0,0,0,0.4) 100%);
    opacity: 0; transition: opacity 0.3s ease; pointer-events: none;
    display: flex; flex-direction: column; justify-content: space-between; padding: 12px;
}
.image-card:hover .image-overlay { opacity: 1; pointer-events: auto; }

.action-buttons { display: flex; gap: 8px; align-self: flex-end; }
.btn-overlay {
    background: rgba(255,255,255,0.9); border: none; border-radius: 8px;
    padding: 8px 12px; font-size: 13px; font-weight: 600; cursor: pointer;
    transition: all 0.2s; color: var(--text); display: flex; align-items: center; gap: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.btn-overlay:hover { background: white; transform: scale(1.05); }
.btn-overlay.danger { color: var(--danger); }
.btn-overlay.danger:hover { background: var(--danger); color: white; }

.image-info { padding: 16px; font-size: 13px; color: var(--text); display:flex; flex-direction:column; gap:8px; border-top: 1px solid var(--border); }
.image-title { font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 14px; }
.image-span { font-size: 11px; color: var(--muted); font-family: monospace; background: var(--bg); padding:4px 8px; border-radius:6px; align-self:flex-start; border: 1px solid var(--border); }
</style>

<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 24px;">
    <div>
        <h2>Site Galerisi</h2>
        <p style="color:var(--muted); font-size:13px; margin-top:4px;">Web sitesinin ön yüzünde (Galeri veya Slider) görünen tüm dinamik içerikleri buradan yönetin.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addImageModal')" style="border-radius: 12px; padding: 10px 20px;">
        <i class="fas fa-plus"></i> Yeni Resim Ekle
    </button>
</div>

<div class="gallery-layout">
    <!-- Sol Taraf: Klasörler (Kategoriler) -->
    <div class="gallery-sidebar">
        <div class="sidebar-header">
            <span>Kategoriler (Etiketler)</span>
        </div>
        <ul class="folder-list">
            <?php foreach($categories as $cat): 
                $count = count(array_filter($images, fn($i) => ($i['category'] ?: 'Genel') === $cat));
            ?>
                <a href="<?= BASE_URL ?>/superadmin/site-gallery?cat=<?= urlencode($cat) ?>" class="folder-item <?= ($selectedCategory === $cat) ? 'active' : '' ?>">
                    <span style="display:flex; align-items:center; gap:8px;">
                        <i class="fas fa-folder<?= ($selectedCategory === $cat) ? '-open' : '' ?>" style="color:#FACC15;"></i>
                        <?= View::escape($cat) ?>
                    </span>
                    <span class="badge-count"><?= $count ?></span>
                </a>
            <?php endforeach; ?>
            <?php if(empty($categories)): ?>
                <div style="padding:20px; text-align:center; color:var(--muted); font-size:13px;">Henüz hiç kategori yok.</div>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Sağ Taraf: Görseller -->
    <div class="gallery-content">
        
        <div class="gallery-header-sticky">
            <h3><i class="fas fa-folder-open" style="color:#FACC15;"></i> <?= View::escape($selectedCategory) ?></h3>
            <div style="font-size:13px; color:var(--muted); font-weight:500;">
                <?= !empty($filteredImages) ? count($filteredImages).' öğe listeleniyor' : 'Boş kategori' ?>
            </div>
        </div>

        <div class="image-grid">
            <?php if(empty($filteredImages)): ?>
                <div style="grid-column: 1/-1; padding:60px; text-align:center; color:var(--muted); display:flex; flex-direction:column; align-items:center;">
                    <i class="fas fa-images" style="font-size:64px; margin-bottom:20px; opacity:0.2;"></i>
                    <h3 style="margin:0 0 10px 0; font-weight:600; color:var(--text);">Boş Kategori</h3>
                    <p style="margin:0;">Bu kategoride henüz resim bulunmuyor. Yeni resim ekleyerek başlayabilirsiniz.</p>
                </div>
            <?php else: ?>
                <?php foreach($filteredImages as $img): ?>
                    <div class="image-card">
                        <div class="image-wrapper">
                            <img src="<?= View::escape($img['image_url']) ?>" alt="<?= View::escape($img['title']) ?>" loading="lazy" decoding="async">
                            <div class="image-overlay">
                                <div></div> <!-- Flex spacer top -->
                                <div class="action-buttons">
                                    <button class="btn-overlay" onclick='editImage(<?= json_encode($img) ?>)' title="Düzenle">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <form method="POST" action="<?= BASE_URL ?>/superadmin/site-gallery/<?= $img['id'] ?>/delete" onsubmit="return confirm('Resmi silmek istediğinize emin misiniz?');" style="margin:0;">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <button type="submit" class="btn-overlay danger" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="image-info">
                            <div class="image-title" title="<?= View::escape($img['title']) ?>"><?= View::escape($img['title']) ?: 'Başlıksız' ?></div>
                            <div class="image-span"><?= View::escape($img['span_class']) ?: 'Varsayılan Sınıf' ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal: Add Image -->
<div id="addImageModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Yeni Resim Ekle</h3>
            <button class="modal-close" onclick="closeModal('addImageModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-gallery">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="form-group">
                    <label class="form-label">Resim URL</label>
                    <input type="text" name="image_url" class="form-control" placeholder="/innomis/assets/gallery/..." required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kategori (Etiket)</label>
                        <input type="text" name="category" class="form-control" value="<?= View::escape($selectedCategory) ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Başlık (Hover Metni)</label>
                        <input type="text" name="title" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Grid CSS Sınıfı (Örn: md:col-span-2, row-span-2)</label>
                    <input type="text" name="span_class" class="form-control">
                </div>

                <div style="margin-top:20px; display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('addImageModal')">İptal</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Image -->
<div id="editImageModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Resmi Düzenle</h3>
            <button class="modal-close" onclick="closeModal('editImageModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" id="editImageForm">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="form-group">
                    <label class="form-label">Resim URL</label>
                    <input type="text" name="image_url" id="edit_image_url" class="form-control" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kategori (Etiket)</label>
                        <input type="text" name="category" id="edit_category" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Başlık (Hover Metni)</label>
                        <input type="text" name="title" id="edit_title" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Grid CSS Sınıfı</label>
                    <input type="text" name="span_class" id="edit_span_class" class="form-control">
                </div>

                <div style="margin-top:20px; display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('editImageModal')">İptal</button>
                    <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editImage(img) {
    document.getElementById('edit_image_url').value = img.image_url;
    document.getElementById('edit_category').value = img.category;
    document.getElementById('edit_title').value = img.title || '';
    document.getElementById('edit_span_class').value = img.span_class || '';

    document.getElementById('editImageForm').action = `<?= BASE_URL ?>/superadmin/site-gallery/${img.id}`;
    openModal('editImageModal');
}
</script>
