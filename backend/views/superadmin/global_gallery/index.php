<?php $pageTitle = 'Global Galeri (Dosya Yöneticisi)'; ?>

<div class="page-header">
    <div>
        <h2>Global Galeri</h2>
        <p style="color:var(--muted); font-size:13px; margin-top:4px;">Görsellerinizi klasörler halinde yönetin. Klasör adları etiket olarak kullanılır ve Site Galerisine otomatik senkronize edilir.</p>
    </div>
</div>

<style>
.gallery-layout { display: flex; gap: 20px; align-items: flex-start; }
.folder-list { list-style: none; padding: 0; margin: 0; }
.folder-item { 
    display: flex; justify-content: space-between; align-items: center; 
    padding: 10px 15px; border-bottom: 1px solid var(--border); 
    transition: background 0.2s;
    text-decoration: none; color: var(--text);
}
.folder-item:hover, .folder-item.active { background: var(--input-bg); }
.folder-item.active { font-weight: bold; color: var(--primary); border-left: 3px solid var(--primary); }
.dropzone {
    border: 2px dashed var(--border);
    border-radius: 12px;
    padding: 40px;
    text-align: center;
    background: var(--bg);
    cursor: pointer;
    transition: all 0.3s;
    margin-bottom: 20px;
}
.dropzone.dragover {
    border-color: var(--primary);
    background: var(--input-bg);
}
.image-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
}
.image-card {
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    background: var(--bg);
    position: relative;
    group: hover;
}
.image-card img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    display: block;
}
.image-card-info {
    padding: 8px;
    font-size: 11px;
    color: var(--muted);
    text-align: center;
    word-break: break-all;
}
.delete-file-btn {
    position: absolute;
    top: 5px; right: 5px;
    background: rgba(255,0,0,0.8);
    color: white;
    border: none;
    border-radius: 4px;
    padding: 2px 6px;
    font-size: 10px;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.2s;
}
.image-card:hover .delete-file-btn { opacity: 1; }
</style>

<div class="gallery-layout">
    
    <!-- SOL: KLASÖRLER -->
    <div class="card" style="width: 280px; flex-shrink: 0;">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <span>Klasörler</span>
            <button class="btn btn-sm btn-primary" onclick="openModal('addFolderModal')">+</button>
        </div>
        <ul class="folder-list">
            <?php if(empty($folders)): ?>
                <li style="padding:15px; text-align:center; color:var(--muted); font-size:13px;">Henüz klasör yok.</li>
            <?php else: ?>
                <?php foreach($folders as $folder): ?>
                <a href="<?= BASE_URL ?>/superadmin/global-gallery?folder=<?= urlencode($folder) ?>" class="folder-item <?= ($selectedFolder === $folder) ? 'active' : '' ?>">
                    <span><i class="fas fa-folder" style="margin-right:8px; color:#FACC15;"></i> <?= View::escape($folder) ?></span>
                    <?php if($selectedFolder === $folder): ?>
                        <form method="POST" action="<?= BASE_URL ?>/superadmin/global-gallery/folder/delete" style="display:inline;" onsubmit="return confirm('Bu klasörü ve içindeki tüm görselleri kalıcı olarak silmek istediğinize emin misiniz? (Site galerisinden de silinecektir.)');">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="folder_name" value="<?= View::escape($folder) ?>">
                            <button type="submit" style="background:none; border:none; color:var(--danger); cursor:pointer;"><i class="fas fa-trash"></i></button>
                        </form>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <!-- SAĞ: İÇERİK -->
    <div style="flex: 1;">
        <?php if($selectedFolder): ?>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h3 style="margin-top:0; margin-bottom:15px; font-size:16px;">
                        <i class="fas fa-folder-open" style="margin-right:8px; color:#FACC15;"></i> <?= View::escape($selectedFolder) ?>
                    </h3>
                    
                    <div id="dropzone" class="dropzone" onclick="document.getElementById('fileInput').click()">
                        <i class="fas fa-cloud-upload-alt" style="font-size:32px; color:var(--primary); margin-bottom:10px;"></i>
                        <h4 style="margin:0 0 5px 0;">Görselleri Sürükleyip Bırakın</h4>
                        <p style="margin:0; font-size:13px; color:var(--muted);">veya bilgisayarınızdan seçmek için tıklayın (Çoklu seçim yapılabilir)</p>
                        <p style="margin-top:10px; font-size:11px; color:var(--primary);">* Otomatik olarak WebP formatına çevrilip optimize edilecektir.</p>
                        
                        <input type="file" id="fileInput" multiple accept="image/*" style="display:none;" onchange="handleFiles(this.files)">
                    </div>

                    <div id="uploadProgress" style="display:none; margin-bottom:20px;">
                        <div style="font-size:12px; margin-bottom:5px; color:var(--muted);" id="uploadStatus">Yükleniyor... Lütfen bekleyin.</div>
                        <div style="width:100%; height:6px; background:var(--border); border-radius:3px; overflow:hidden;">
                            <div id="progressBar" style="width:0%; height:100%; background:var(--primary); transition:width 0.3s;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="image-grid">
                <?php if(empty($files)): ?>
                    <div style="grid-column: 1/-1; padding:30px; text-align:center; color:var(--muted); border:1px dashed var(--border); border-radius:8px;">
                        Bu klasörde henüz görsel bulunmuyor.
                    </div>
                <?php else: ?>
                    <?php foreach($files as $file): ?>
                    <div class="image-card">
                        <a href="<?= $file['url'] ?>" target="_blank">
                            <img src="<?= $file['url'] ?>" alt="<?= View::escape($file['name']) ?>" loading="lazy">
                        </a>
                        <div class="image-card-info" title="<?= View::escape($file['name']) ?>">
                            <?= View::escape(strlen($file['name']) > 15 ? substr($file['name'],0,15).'...' : $file['name']) ?>
                        </div>
                        <form method="POST" action="<?= BASE_URL ?>/superadmin/global-gallery/file/delete" onsubmit="return confirm('Bu görseli silmek istediğinize emin misiniz?');">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="folder_name" value="<?= View::escape($selectedFolder) ?>">
                            <input type="hidden" name="file_name" value="<?= View::escape($file['name']) ?>">
                            <button type="submit" class="delete-file-btn"><i class="fas fa-times"></i></button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <div class="card" style="padding:40px; text-align:center; color:var(--muted);">
                <i class="fas fa-arrow-left" style="font-size:24px; margin-bottom:15px; opacity:0.5;"></i>
                <p>İçeriğini görmek veya dosya yüklemek için sol taraftan bir klasör seçin.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal: Yeni Klasör -->
<div id="addFolderModal" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header">
            <h3>Yeni Klasör (Etiket) Oluştur</h3>
            <button class="modal-close" onclick="closeModal('addFolderModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?= BASE_URL ?>/superadmin/global-gallery/folder">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="form-group">
                    <label class="form-label">Klasör / Etiket Adı</label>
                    <input type="text" name="folder_name" class="form-control" placeholder="Örn: 2026 Etkinlikleri" required>
                    <small style="color:var(--muted); margin-top:5px; display:block;">Boşluk yerine - (tire) veya _ (alt çizgi) kullanılması tavsiye edilir. Türkçe karakter kullanmamaya özen gösterin.</small>
                </div>

                <div style="margin-top:20px; display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('addFolderModal')">İptal</button>
                    <button type="submit" class="btn btn-primary">Oluştur</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
<?php if($selectedFolder): ?>
const dropzone = document.getElementById('dropzone');
const uploadProgress = document.getElementById('uploadProgress');
const progressBar = document.getElementById('progressBar');
const uploadStatus = document.getElementById('uploadStatus');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropzone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults (e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropzone.addEventListener(eventName, () => dropzone.classList.add('dragover'), false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropzone.addEventListener(eventName, () => dropzone.classList.remove('dragover'), false);
});

dropzone.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    let dt = e.dataTransfer;
    let files = dt.files;
    handleFiles(files);
}

function handleFiles(files) {
    if(files.length === 0) return;
    
    uploadProgress.style.display = 'block';
    progressBar.style.width = '0%';
    uploadStatus.innerText = 'Yükleniyor... (0/' + files.length + ')';
    
    let formData = new FormData();
    formData.append('folder_name', '<?= addslashes($selectedFolder) ?>');
    
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }
    
    fetch('<?= BASE_URL ?>/superadmin/global-gallery/upload', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        progressBar.style.width = '100%';
        if(data.success) {
            uploadStatus.innerText = 'Başarılı! Sayfa yenileniyor...';
            setTimeout(() => window.location.reload(), 1000);
        } else {
            uploadStatus.innerText = 'Hata: ' + data.message;
            uploadStatus.style.color = 'var(--danger)';
        }
    })
    .catch(error => {
        progressBar.style.width = '100%';
        uploadStatus.innerText = 'Bir hata oluştu.';
        uploadStatus.style.color = 'var(--danger)';
        console.error('Error:', error);
    });
}
<?php endif; ?>
</script>
