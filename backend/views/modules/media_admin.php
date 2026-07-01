<div class="page-header">
    <div>
        <h2>Medya Yöneticisi</h2>
        <p style="color:var(--muted); font-size:13px; margin-top:4px;">Görsellerinizi klasörler halinde yönetin. Klasör adları etiket olarak kullanılır ve Site Galerisine otomatik senkronize edilir.</p>
    </div>
</div>

<style>
.gallery-layout { display: flex; gap: 24px; align-items: flex-start; }
.folder-list { list-style: none; padding: 0; margin: 0; display:flex; flex-direction:column; gap:6px; }
.folder-item { 
    display: flex; justify-content: space-between; align-items: center; 
    padding: 12px 16px; 
    border-radius: 8px;
    background: transparent;
    transition: all 0.3s ease;
    text-decoration: none; color: var(--text);
    border: 1px solid transparent;
}
.folder-item:hover { background: var(--input-bg); border-color: var(--border); transform: translateX(4px); }
.folder-item.active { 
    background: var(--primary); color: white; 
    box-shadow: 0 4px 12px rgba(var(--primary-rgb, 0,0,0), 0.2);
}
.folder-item.active .fas { color: white !important; }
.folder-delete-btn {
    background: rgba(255,0,0,0.1); color: var(--danger);
    border: none; border-radius: 6px; padding: 4px 8px; cursor: pointer;
    opacity: 0; transition: all 0.2s;
}
.folder-item:hover .folder-delete-btn { opacity: 1; }
.folder-item.active .folder-delete-btn { background: rgba(255,255,255,0.2); color: white; }

.dropzone {
    border: 2px dashed var(--border);
    border-radius: 16px;
    padding: 50px 20px;
    text-align: center;
    background: var(--bg);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    margin-bottom: 24px;
}
.dropzone:hover {
    border-color: var(--primary);
    background: var(--input-bg);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.05);
}
.dropzone.dragover {
    border-color: var(--primary);
    background: rgba(var(--primary-rgb, 0,0,0), 0.05);
    transform: scale(1.02);
}
.dropzone i { transition: transform 0.3s; }
.dropzone:hover i { transform: scale(1.1) translateY(-5px); }

.image-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 20px;
}
.image-card {
    border-radius: 12px;
    overflow: hidden;
    background: var(--bg);
    position: relative;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid var(--border);
}
.image-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.12);
}
.image-wrapper { position: relative; width: 100%; height: 140px; overflow: hidden; }
.image-wrapper img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform 0.5s ease;
}
.image-card:hover .image-wrapper img { transform: scale(1.1); }
.image-overlay {
    position: absolute; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.4);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity 0.3s;
    backdrop-filter: blur(2px);
}
.image-card:hover .image-overlay { opacity: 1; }
.delete-file-btn {
    background: #ef4444; color: white;
    border: none; border-radius: 50%;
    width: 36px; height: 36px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transform: scale(0.5); transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}
.image-card:hover .delete-file-btn { transform: scale(1); }
.delete-file-btn:hover { background: #dc2626; transform: scale(1.1) !important; }

.image-card-info {
    padding: 12px;
    font-size: 12px;
    color: var(--text);
    text-align: center;
    font-weight: 500;
    word-break: break-all;
    border-top: 1px solid var(--border);
}
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
                <a href="<?= BASE_URL ?>/panel/<?= $department['slug'] ?>/apps/media?folder=<?= urlencode($folder) ?>" class="folder-item <?= ($selectedFolder === $folder) ? 'active' : '' ?>">
                    <span><i class="fas fa-folder" style="margin-right:8px; color:#FACC15;"></i> <?= View::escape($folder) ?></span>
                    <?php if($selectedFolder === $folder): ?>
                        <form method="POST" action="<?= BASE_URL ?>/panel/<?= $department['slug'] ?>/apps/media" style="display:inline;" onsubmit="return confirm('Bu klasörü ve içindeki tüm görselleri kalıcı olarak silmek istediğinize emin misiniz?');">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="action" value="delete_folder">
                            <input type="hidden" name="folder_name" value="<?= View::escape($folder) ?>">
                            <button type="submit" class="folder-delete-btn" title="Klasörü Sil"><i class="fas fa-trash"></i></button>
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
                        <div class="image-wrapper">
                            <img src="<?= $file['url'] ?>" alt="<?= View::escape($file['name']) ?>" loading="lazy">
                            <div class="image-overlay">
                                <form method="POST" action="<?= BASE_URL ?>/panel/<?= $department['slug'] ?>/apps/media" onsubmit="return confirm('Bu görseli silmek istediğinize emin misiniz?');">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                    <input type="hidden" name="action" value="delete_file">
                                    <input type="hidden" name="folder_name" value="<?= View::escape($selectedFolder) ?>">
                                    <input type="hidden" name="file_name" value="<?= View::escape($file['name']) ?>">
                                    <button type="submit" class="delete-file-btn" title="Sil"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="image-card-info" title="<?= View::escape($file['name']) ?>">
                            <a href="<?= $file['url'] ?>" target="_blank" style="color:inherit; text-decoration:none;">
                                <?= View::escape(strlen($file['name']) > 15 ? substr($file['name'],0,15).'...' : $file['name']) ?>
                            </a>
                        </div>
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
            <form method="POST" action="<?= BASE_URL ?>/panel/<?= $department['slug'] ?>/apps/media">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="create_folder">
                
                <div class="form-group">
                    <label class="form-label">Klasör / Etiket Adı</label>
                    <input type="text" name="folder_name" class="form-control" placeholder="Örn: 2026 Etkinlikleri" required>
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

function preventDefaults (e) { e.preventDefault(); e.stopPropagation(); }

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
    formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?? '' ?>');
    formData.append('action', 'upload_files');
    formData.append('folder_name', '<?= addslashes($selectedFolder) ?>');
    
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }
    
    fetch('<?= BASE_URL ?>/panel/<?= $department['slug'] ?>/apps/media', {
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
