<?php $pageTitle = 'Site Galeri'; ?>

<div class="page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <h2>Site Galeri</h2>
    <button class="btn btn-primary" onclick="openModal('addImageModal')">Yeni Resim Ekle</button>
</div>

<div class="card">
    <div style="overflow-x:auto;">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:100px;">Görsel</th>
                    <th>Kategori</th>
                    <th>Başlık (Hover)</th>
                    <th>Grid Sınıfı</th>
                    <th style="width:100px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($images)): ?>
                <tr>
                    <td colspan="5" style="text-align:center; padding:20px; color:var(--muted);">Henüz resim eklenmemiş.</td>
                </tr>
                <?php else: ?>
                    <?php foreach($images as $img): ?>
                    <tr>
                        <td>
                            <img src="<?= View::escape($img['image_url']) ?>" alt="Gallery" style="width:60px; height:60px; object-fit:cover; border-radius:8px; border:1px solid var(--border);">
                        </td>
                        <td><span class="badge" style="background:var(--bg); border:1px solid var(--border); color:var(--text);"><?= View::escape($img['category']) ?></span></td>
                        <td><?= View::escape($img['title']) ?></td>
                        <td><code><?= View::escape($img['span_class']) ?></code></td>
                        <td>
                            <div style="display:flex; gap:8px;">
                                <button class="btn btn-sm btn-outline" onclick='editImage(<?= json_encode($img) ?>)'>Düzenle</button>
                                <form method="POST" action="<?= BASE_URL ?>/superadmin/site-gallery/<?= $img['id'] ?>/delete" onsubmit="return confirm('Resmi silmek istediğinize emin misiniz?');">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
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
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-control" required>
                            <option value="Genel">Genel</option>
                            <option value="Etkinlikler">Etkinlikler</option>
                            <option value="Kamplar">Kamplar</option>
                            <option value="Teknoloji">Teknoloji</option>
                        </select>
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
                        <label class="form-label">Kategori</label>
                        <select name="category" id="edit_category" class="form-control" required>
                            <option value="Genel">Genel</option>
                            <option value="Etkinlikler">Etkinlikler</option>
                            <option value="Kamplar">Kamplar</option>
                            <option value="Teknoloji">Teknoloji</option>
                        </select>
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
