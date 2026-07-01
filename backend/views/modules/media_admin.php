<div class="page-header">
    <div>
        <h2>Medya Yöneticisi</h2>
        <p style="color:var(--muted); font-size:13px; margin-top:4px;">Görsellerinizi klasörler halinde yönetin. Klasör adları etiket olarak kullanılır ve Site Galerisine otomatik senkronize edilir.</p>
    </div>
</div>

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
.folder-delete-btn {
    background: rgba(255,0,0,0.1); color: var(--danger);
    border: none; border-radius: 8px; padding: 6px 10px; cursor: pointer;
    opacity: 0; transition: all 0.2s; font-size: 12px;
}
.folder-item:hover .folder-delete-btn { opacity: 1; }
.folder-item.active .folder-delete-btn { background: rgba(255,255,255,0.25); color: white; }

/* Sağ Kısım - İçerik Alanı */
.gallery-content {
    flex: 1; display: flex; flex-direction: column; background: var(--bg); position: relative;
    overflow-y: auto;
}

/* Yüzen Klasör Başlığı (Sticky) */
.gallery-header-sticky {
    position: sticky; top: 0; z-index: 50;
    background: rgba(var(--bg-rgb, 255,255,255), 0.85);
    backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--border);
    padding: 24px 32px;
    display: flex; justify-content: space-between; align-items: center;
}
.gallery-header-sticky h3 { margin: 0; font-size: 20px; font-weight: 700; display:flex; align-items:center; gap:10px; }

/* Modern Dropzone */
.dropzone-wrapper { padding: 0 32px; margin-top: 24px; }
.dropzone {
    border: 2px dashed rgba(var(--primary-rgb, 0,0,0), 0.3);
    background: rgba(var(--primary-rgb, 0,0,0), 0.02);
    border-radius: 20px; padding: 40px 20px; text-align: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative; overflow: hidden; cursor: pointer;
}
.dropzone:hover {
    border-color: var(--primary); background: rgba(var(--primary-rgb, 0,0,0), 0.05);
    transform: translateY(-2px); box-shadow: 0 10px 30px rgba(var(--primary-rgb, 0,0,0), 0.1);
}
.dropzone.dragover {
    border-color: var(--primary); background: rgba(var(--primary-rgb, 0,0,0), 0.1);
    transform: scale(1.02);
}
.dropzone i { font-size: 40px; color: var(--primary); transition: transform 0.3s; margin-bottom: 15px; }
.dropzone:hover i { transform: scale(1.1) translateY(-5px); }

/* Araç Çubuğu (Toolbar) */
.media-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 32px; margin: 24px 0; color: var(--muted); font-size: 14px;
}

/* Image Grid - Google Photos Style */
.image-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 24px; padding: 0 32px 32px 32px;
}
.image-card {
    border-radius: 16px; overflow: hidden; background: var(--card-bg);
    position: relative; box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    border: 1px solid var(--border);
}
.image-card:hover {
    transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}
.image-wrapper { position: relative; width: 100%; aspect-ratio: 4/3; overflow: hidden; }
.image-wrapper img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform 0.7s cubic-bezier(0.165, 0.84, 0.44, 1);
}
.image-card:hover .image-wrapper img { transform: scale(1.08); }
.image-overlay {
    position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 40%, rgba(0,0,0,0.2) 100%);
    opacity: 0; transition: opacity 0.3s ease; pointer-events: none;
}
.image-card:hover .image-overlay { opacity: 1; pointer-events: auto; }

/* Butonlar ve Seçim */
.delete-file-btn {
    position: absolute; bottom: 12px; right: 12px;
    background: rgba(255,255,255,0.9); color: var(--danger);
    border: none; border-radius: 50%; width: 36px; height: 36px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transform: scale(0.5); opacity: 0;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 20;
}
.image-card:hover .delete-file-btn { transform: scale(1); opacity: 1; }
.delete-file-btn:hover { background: var(--danger); color: white; transform: scale(1.1) !important; }

.bulk-cb {
    position: absolute; top: 12px; left: 12px; z-index: 20;
    width: 22px; height: 22px; cursor: pointer; opacity: 0;
    transition: all 0.2s; box-shadow: 0 2px 6px rgba(0,0,0,0.3); border-radius: 6px;
}
.image-card:hover .bulk-cb, .bulk-cb:checked { opacity: 1; }

.image-card-info {
    padding: 14px; font-size: 13px; color: var(--text);
    text-align: center; font-weight: 500;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    border-top: 1px solid var(--border);
}
.load-more-wrap { display: flex; justify-content: center; padding-bottom: 40px; margin-top: 10px; }
</style>

<div class="gallery-layout">
    <!-- SOL: KLASÖRLER -->
    <div class="gallery-sidebar">
        <div class="sidebar-header">
            <span>Kategoriler</span>
            <button class="btn btn-sm btn-primary" onclick="openModal('addFolderModal')" style="border-radius:8px; width:32px; height:32px; padding:0; display:flex; align-items:center; justify-content:center;"><i class="fas fa-plus"></i></button>
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
    <div class="gallery-content">
        <?php if($selectedFolder): ?>
            
            <div class="gallery-header-sticky">
                <h3><i class="fas fa-folder-open" style="color:#FACC15;"></i> <?= View::escape($selectedFolder) ?></h3>
                <div style="font-size:13px; color:var(--muted); font-weight:500;">
                    <?= !empty($files) ? count($files).' / '.($totalFiles ?? count($files)).' öğe' : 'Boş klasör' ?>
                </div>
            </div>

            <div class="dropzone-wrapper">
                <div id="dropzone" class="dropzone" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <h4 style="margin:0 0 8px 0; font-size:16px;">Görselleri Sürükleyip Bırakın</h4>
                    <p style="margin:0; font-size:13px; color:var(--muted);">veya bilgisayarınızdan seçmek için tıklayın (Çoklu seçim yapılabilir)</p>
                    <p style="margin-top:12px; font-size:11px; color:var(--primary); font-weight:600;"><i class="fas fa-magic" style="font-size:10px; margin-bottom:0;"></i> WebP Otomatik Optimizasyon</p>
                    
                    <input type="file" id="fileInput" multiple accept="image/*" style="display:none;" onchange="handleFiles(this.files)">
                </div>

                <div id="uploadProgress" style="display:none; margin-top:15px; margin-bottom:20px;">
                    <div style="font-size:12px; margin-bottom:5px; color:var(--muted); font-weight:500;" id="uploadStatus">Yükleniyor... Lütfen bekleyin.</div>
                    <div style="width:100%; height:6px; background:var(--border); border-radius:3px; overflow:hidden;">
                        <div id="progressBar" style="width:0%; height:100%; background:var(--primary); transition:width 0.3s ease;"></div>
                    </div>
                </div>
            </div>

            <?php if(!empty($files)): ?>
                <div class="media-toolbar">
                    <span><?= (int) count($files) ?> öğe listeleniyor</span>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
                            <input type="checkbox" id="selectAllCheckbox" onchange="toggleAllCheckboxes(this)"> Tümünü Seç
                        </label>
                        <button type="button" class="btn btn-sm btn-danger" id="bulkDeleteBtn" style="display:none;" onclick="submitBulkDelete()"><i class="fas fa-trash"></i> Seçilenleri Sil</button>
                    </div>
                </div>
                <?php if(empty($canGenerateThumbnails)): ?>
                    <div style="margin:0 32px 16px 32px; padding:12px 14px; border:1px solid var(--border); border-radius:8px; color:var(--muted); font-size:13px;">
                        Thumbnail uretimi icin PHP GD eklentisi kapali. GD aktif edilirse bu liste kucuk optimize gorsellerle acilir.
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="image-grid">
                <?php if(empty($files)): ?>
                    <div style="grid-column: 1/-1; padding:30px; text-align:center; color:var(--muted); border:1px dashed var(--border); border-radius:8px;">
                        Bu klasörde henüz görsel bulunmuyor.
                    </div>
                <?php else: ?>
                    <?php foreach($files as $file): ?>
                    <div class="image-card">
                        <div class="image-wrapper">
                            <input type="checkbox" name="files[]" value="<?= View::escape($file['name']) ?>" class="bulk-cb" onchange="updateBulkDeleteBtn()">
                            <img src="<?= $file['thumb_url'] ?? $file['url'] ?>" alt="<?= View::escape($file['name']) ?>" loading="lazy" decoding="async" fetchpriority="low">
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

            <?php if(!empty($hasMoreFiles)): ?>
                <div class="load-more-wrap">
                    <a class="btn btn-outline" href="<?= BASE_URL ?>/panel/<?= $department['slug'] ?>/apps/media?folder=<?= urlencode($selectedFolder) ?>&page=<?= (int) (($mediaPage ?? 1) + 1) ?>">
                        Sonraki <?= (int) ($perPage ?? 48) ?> gorsel
                    </a>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:40px; color:var(--muted);">
                <i class="fas fa-images" style="font-size:64px; margin-bottom:20px; opacity:0.2;"></i>
                <h3 style="margin:0 0 10px 0; font-weight:600; color:var(--text);">Klasör Seçin</h3>
                <p style="margin:0; text-align:center;">İçeriğini görmek veya dosya yüklemek için sol taraftan bir klasör (etiket) seçin veya yeni bir klasör oluşturun.</p>
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

async function uploadSingleFile(file) {
    let formData = new FormData();
    formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?? '' ?>');
    formData.append('action', 'upload_files');
    formData.append('folder_name', '<?= addslashes($selectedFolder) ?>');
    formData.append('files[]', file);

    const response = await fetch('<?= BASE_URL ?>/panel/<?= $department['slug'] ?>/apps/media', {
        method: 'POST',
        body: formData
    });

    const text = await response.text();
    try {
        return JSON.parse(text);
    } catch (error) {
        throw new Error('Sunucu JSON yerine beklenmeyen bir cevap dondurdu.');
    }
}

async function handleFiles(files) {
    if(files.length === 0) return;

    const fileList = Array.from(files);
    let uploaded = 0;
    let failed = 0;

    uploadProgress.style.display = 'block';
    progressBar.style.width = '0%';
    uploadStatus.style.color = 'var(--muted)';
    uploadStatus.innerText = 'Yukleniyor... (0/' + fileList.length + ')';

    for (let i = 0; i < fileList.length; i++) {
        const file = fileList[i];
        uploadStatus.innerText = 'Yukleniyor... (' + i + '/' + fileList.length + ') ' + file.name;

        try {
            const data = await uploadSingleFile(file);
            if (data.success) {
                uploaded++;
            } else {
                failed++;
                console.warn('Upload failed:', file.name, data.message);
            }
        } catch (error) {
            failed++;
            console.error('Upload error:', file.name, error);
        }

        progressBar.style.width = Math.round(((i + 1) / fileList.length) * 100) + '%';
    }

    if (failed === 0) {
        uploadStatus.innerText = 'Basarili! ' + uploaded + ' dosya yuklendi. Sayfa yenileniyor...';
        uploadStatus.style.color = 'var(--primary)';
        setTimeout(() => window.location.reload(), 1000);
    } else if (uploaded > 0) {
        uploadStatus.innerText = uploaded + ' dosya yuklendi, ' + failed + ' dosya yuklenemedi. Sayfa yenileniyor...';
        uploadStatus.style.color = 'var(--danger)';
        setTimeout(() => window.location.reload(), 1500);
    } else {
        uploadStatus.innerText = 'Yukleme basarisiz. Dosyalar cok buyuk olabilir veya oturum suresi dolmus olabilir.';
        uploadStatus.style.color = 'var(--danger)';
    }

    const fileInput = document.getElementById('fileInput');
    if (fileInput) fileInput.value = '';
}

function toggleAllCheckboxes(source) {
    const checkboxes = document.querySelectorAll('.bulk-cb');
    checkboxes.forEach(cb => {
        cb.checked = source.checked;
    });
    updateBulkDeleteBtn();
}

function updateBulkDeleteBtn() {
    const anyChecked = document.querySelectorAll('.bulk-cb:checked').length > 0;
    const btn = document.getElementById('bulkDeleteBtn');
    if(btn) {
        btn.style.display = anyChecked ? 'inline-block' : 'none';
    }
}

function submitBulkDelete() {
    const checkboxes = document.querySelectorAll('.bulk-cb:checked');
    if (checkboxes.length === 0) return;
    
    if (!confirm(checkboxes.length + ' adet görseli silmek istediğinize emin misiniz?')) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= BASE_URL ?>/panel/<?= $department['slug'] ?>/apps/media';
    
    let csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = 'csrf_token';
    csrf.value = '<?= $_SESSION['csrf_token'] ?? '' ?>';
    form.appendChild(csrf);

    let action = document.createElement('input');
    action.type = 'hidden';
    action.name = 'action';
    action.value = 'delete_files_bulk';
    form.appendChild(action);

    let folder = document.createElement('input');
    folder.type = 'hidden';
    folder.name = 'folder_name';
    folder.value = '<?= View::escape($selectedFolder) ?>';
    form.appendChild(folder);

    checkboxes.forEach(cb => {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'files[]';
        input.value = cb.value;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
}
<?php endif; ?>
</script>
