<?php
// views/modules/inventory.php
// Beklenen değişkenler: $items, $categories, $search, $statusFilter, $catFilter, $canEdit, $allUsers
?>

<style>
.inv-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}

.inv-filters {
    display: flex;
    gap: 12px;
    background: var(--card);
    padding: 12px;
    border-radius: 12px;
    border: 1px solid var(--border);
    flex-wrap: wrap;
}

.inv-filters form {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
}

.inv-btn {
    padding: 8px 16px;
    border-radius: 8px;
    border: none;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.inv-btn-primary { background: var(--accent); color: #fff; }
.inv-btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }

.inv-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.inv-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
}

.inv-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.2);
    border-color: var(--accent);
}

.inv-card-img {
    height: 160px;
    background: #1a1a1a;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.inv-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.inv-card-img .no-img {
    color: var(--muted);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.inv-status-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.1);
}

.status-active { color: #10b981; }
.status-maintenance { color: #f59e0b; }
.status-broken { color: #ef4444; }
.status-lost { color: #6b7280; }

.inv-card-body {
    padding: 16px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.inv-card-title {
    font-size: 16px;
    font-weight: 700;
    margin: 0 0 4px 0;
    color: var(--text);
}

.inv-card-cat {
    font-size: 12px;
    color: var(--accent);
    margin-bottom: 12px;
    font-weight: 500;
}

.inv-info-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--muted);
    margin-bottom: 8px;
}

.inv-info-row svg { width: 14px; flex-shrink: 0; }

.inv-card-footer {
    padding: 12px 16px;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(0,0,0,0.1);
}

.inv-price {
    font-size: 15px;
    font-weight: 700;
    color: var(--text);
}

.inv-actions {
    display: flex;
    gap: 6px;
}

.inv-action-btn {
    background: var(--input-bg);
    border: 1px solid var(--border);
    color: var(--muted);
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.inv-action-btn:hover {
    color: var(--text);
    border-color: var(--muted);
}

.inv-action-btn.delete:hover {
    color: #ef4444;
    border-color: #ef4444;
}

/* Modal */
.inv-modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.8);
    backdrop-filter: blur(5px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
.inv-modal-overlay.active { display: flex; }

.inv-modal {
    background: var(--card);
    width: 100%;
    max-width: 800px;
    border-radius: 16px;
    border: 1px solid var(--border);
    max-height: 90vh;
    display: flex;
    flex-direction: column;
}

.inv-modal-header {
    padding: 16px 24px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.inv-modal-body {
    padding: 24px;
    overflow-y: auto;
}

.inv-modal-footer {
    padding: 16px 24px;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.inv-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

@media (max-width: 760px) {
    .inv-header-bar {
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 12px;
    }

    .inv-header-bar h2 {
        font-size: 19px !important;
        line-height: 1.2;
    }

    .inv-header-bar .inv-btn-primary {
        width: 100%;
        justify-content: center;
        min-height: 40px;
        border-radius: 11px;
        font-size: 13px;
    }

    .inv-filters {
        padding: 10px;
        border-radius: 12px;
        margin-bottom: 12px;
    }

    .inv-filters form {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        width: 100%;
        gap: 8px;
    }

    .inv-filters .form-control,
    .inv-filters .inv-btn {
        width: 100% !important;
        min-width: 0;
        min-height: 38px;
        border-radius: 10px;
        font-size: 12px;
    }

    .inv-filters input[name="q"] {
        grid-column: 1 / -1;
    }

    .inv-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin-top: 12px !important;
    }

    .inv-card {
        border-radius: 14px;
    }

    .inv-card:hover {
        transform: none;
    }

    .inv-card-img {
        height: 92px;
    }

    .inv-card-img .no-img svg {
        width: 26px;
        height: 26px;
    }

    .inv-card-img .no-img span {
        display: none;
    }

    .inv-status-badge {
        top: 7px;
        right: 7px;
        padding: 3px 7px;
        font-size: 9px;
        letter-spacing: 0;
    }

    .inv-card-body {
        padding: 10px;
    }

    .inv-card-title {
        font-size: 13px;
        line-height: 1.25;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .inv-card-cat {
        margin-bottom: 7px;
        font-size: 10px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .inv-info-row {
        margin-bottom: 5px;
        gap: 5px;
        font-size: 10px;
        min-width: 0;
    }

    .inv-info-row:first-of-type {
        display: none;
    }

    .inv-info-row svg {
        width: 12px;
    }

    .inv-info-row {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .inv-card-footer {
        padding: 8px 10px;
        gap: 8px;
    }

    .inv-price {
        min-width: 0;
        font-size: 12px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .inv-action-btn {
        width: 30px;
        height: 30px;
        border-radius: 9px;
    }

    .inv-modal-overlay {
        align-items: center;
        justify-content: center;
        padding: 12px;
        box-sizing: border-box;
    }

    .inv-modal {
        max-width: none;
        width: 100%;
        max-height: 88vh;
        border-radius: 18px;
    }

    #invForm {
        max-height: 88vh !important;
    }

    .inv-modal-header {
        padding: 14px 16px;
    }

    .inv-modal-header h3 {
        font-size: 18px;
    }

    .inv-modal-body {
        padding: 14px;
    }

    .inv-form-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .inv-form-grid .form-group {
        margin-bottom: 0;
    }

    .inv-form-grid .form-control {
        min-height: 40px;
        border-radius: 10px;
        font-size: 13px;
    }

    .inv-modal-footer {
        padding: 12px 14px;
        gap: 8px;
    }

    .inv-modal-footer .inv-btn {
        flex: 1;
        justify-content: center;
        min-height: 40px;
        border-radius: 10px;
    }
}

@media(max-width: 380px) {
    .inv-grid {
        gap: 8px;
    }

    .inv-card-img {
        height: 82px;
    }
}
</style>

<div class="inv-header-bar">
    <h2 style="margin:0;font-size:24px;">📦 Envanter Yönetimi</h2>
    
    <?php if($canEdit): ?>
    <button class="inv-btn inv-btn-primary" onclick="openInvModal()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Yeni Eşya Ekle
    </button>
    <?php endif; ?>
</div>

<div class="inv-filters">
    <form method="GET">
        <input type="text" name="q" placeholder="Arama..." class="form-control" style="width:200px;" value="<?= htmlspecialchars($search) ?>">
        
        <select name="category" class="form-control" style="width:150px;">
            <option value="">Tüm Kategoriler</option>
            <?php foreach($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= $catFilter === $cat ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
            <?php endforeach; ?>
        </select>

        <select name="status" class="form-control" style="width:130px;">
            <option value="">Tüm Durumlar</option>
            <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Aktif</option>
            <option value="maintenance" <?= $statusFilter === 'maintenance' ? 'selected' : '' ?>>Bakımda</option>
            <option value="broken" <?= $statusFilter === 'broken' ? 'selected' : '' ?>>Arızalı</option>
            <option value="lost" <?= $statusFilter === 'lost' ? 'selected' : '' ?>>Kayıp</option>
        </select>
        
        <button type="submit" class="inv-btn" style="background:var(--input-bg);border:1px solid var(--border);color:var(--text);">Filtrele</button>
        <?php if($search || $catFilter || $statusFilter): ?>
            <a href="?" class="inv-btn" style="color:var(--muted);text-decoration:none;">Temizle</a>
        <?php endif; ?>
    </form>
</div>

<?php if(empty($items)): ?>
    <div class="empty-state" style="margin-top:40px;">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg>
        <h3>Envanter Boş</h3>
        <p>Kayıtlı hiçbir eşya bulunamadı.</p>
    </div>
<?php else: ?>
    <div class="inv-grid" style="margin-top:24px;">
        <?php foreach($items as $item): ?>
            <div class="inv-card fade-in-up">
                <div class="inv-card-img">
                    <?php if(!empty($item['image_url'])): ?>
                        <img src="<?= BASE_URL . '/public/' . htmlspecialchars($item['image_url']) ?>" alt="Item Image">
                    <?php else: ?>
                        <div class="no-img">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            <span style="font-size:12px;">Görsel Yok</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="inv-status-badge status-<?= $item['status'] ?>">
                        <?php 
                        $statusText = 'Aktif';
                        if($item['status'] == 'maintenance') $statusText = 'Bakımda';
                        if($item['status'] == 'broken') $statusText = 'Arızalı';
                        if($item['status'] == 'lost') $statusText = 'Kayıp';
                        echo $statusText;
                        ?>
                    </div>
                </div>
                
                <div class="inv-card-body">
                    <h3 class="inv-card-title"><?= htmlspecialchars($item['item_name']) ?></h3>
                    <div class="inv-card-cat"><?= htmlspecialchars($item['category'] ?: 'Kategorisiz') ?></div>
                    
                    <div class="inv-info-row" title="Seri / Barkod No">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <?= htmlspecialchars($item['serial_number'] ?: '-') ?>
                    </div>
                    
                    <div class="inv-info-row" title="Konum">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <?= htmlspecialchars($item['location'] ?: 'Belirtilmedi') ?>
                    </div>
                    
                    <div class="inv-info-row" title="Zimmetli Kişi">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <?= htmlspecialchars($item['assigned_to'] ?: 'Boşta') ?>
                    </div>
                </div>
                
                <div class="inv-card-footer">
                    <div class="inv-price"><?= number_format($item['price'], 2, ',', '.') ?> ₺</div>
                    
                    <div class="inv-actions">
                        <?php if($item['assigned_to'] === $this->user['name']): ?>
                        <form method="POST" style="margin:0;">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="action" value="leave_room">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="inv-action-btn" style="width: auto; padding: 0 10px; font-size: 11px; font-weight: 500; color: #ef4444; border-color: rgba(239,68,68,0.3);" title="Odaya Bırak (Zimmetten Çıkar)">
                                Odaya Bırak
                            </button>
                        </form>
                        <button class="inv-action-btn" style="width: auto; padding: 0 10px; font-size: 11px; font-weight: 500; color: #3b82f6; border-color: rgba(59,130,246,0.3);" onclick="openTransferModal(<?= $item['id'] ?>, '<?= htmlspecialchars(addslashes($item['item_name'])) ?>')" title="Başka Birine Zimmetle">
                            Devret
                        </button>
                        <?php elseif($item['assigned_to'] !== $this->user['name']): ?>
                        <form method="POST" style="margin:0;">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="action" value="take_over">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="inv-action-btn" style="width: auto; padding: 0 10px; font-size: 11px; font-weight: 500; color: #8b5cf6; border-color: rgba(139,92,246,0.3);" title="Bana Zimmetle">
                                Devral
                            </button>
                        </form>
                        <?php endif; ?>

                        <?php if($canEdit): ?>
                        <button class="inv-action-btn" onclick="openInvModal(<?= htmlspecialchars(json_encode($item)) ?>)" title="Düzenle">
                            <svg width="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </button>
                        <form method="POST" style="margin:0;" onsubmit="return confirm('Bu eşyayı silmek istediğinize emin misiniz?');">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="action" value="delete_item">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="inv-action-btn delete" title="Sil">
                                <svg width="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if($canEdit): ?>
<!-- Form Modal -->
<div class="inv-modal-overlay" id="invModalOverlay" onclick="if(event.target===this) closeInvModal()">
    <div class="inv-modal">
        <form method="POST" enctype="multipart/form-data" id="invForm" style="margin:0;display:flex;flex-direction:column;max-height:90vh;">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="action" value="save_item">
            <input type="hidden" name="item_id" id="invItemId" value="">
            <input type="hidden" name="existing_image" id="invExistingImage" value="">
            
            <div class="inv-modal-header">
                <h3 style="margin:0;" id="invModalTitle">Yeni Eşya Ekle</h3>
                <button type="button" onclick="closeInvModal()" style="background:transparent;border:none;color:var(--muted);cursor:pointer;">
                    <svg width="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            
            <div class="inv-modal-body">
                <div class="inv-form-grid">
                    <div class="form-group" style="grid-column:1/-1">
                        <label class="form-label">Eşya Adı *</label>
                        <input type="text" name="item_name" id="invItemName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori</label>
                        <select name="category" id="invCategory" class="form-control">
                            <option value="">Kategori Seçiniz</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="Bilgisayar & Aksesuar">Bilgisayar & Aksesuar</option>
                            <option value="Mobilya">Mobilya</option>
                            <option value="Ofis & Kırtasiye">Ofis & Kırtasiye</option>
                            <option value="Sarf Malzeme">Sarf Malzeme</option>
                            <option value="Mutfak & Temizlik">Mutfak & Temizlik</option>
                            <option value="Diğer">Diğer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Seri / Barkod No</label>
                        <input type="text" name="serial_number" id="invSerial" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fiyat / Maliyet (₺)</label>
                        <input type="number" step="0.01" name="price" id="invPrice" class="form-control" value="0.00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Satın Alma Tarihi</label>
                        <input type="date" name="purchase_date" id="invPurchase" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konum</label>
                        <input type="text" name="location" id="invLocation" class="form-control" placeholder="Örn: Depo, 3. Kat">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Zimmetli Kişi</label>
                        <select name="assigned_to" id="invAssigned" class="form-control">
                            <option value="">Boşta (Zimmetsiz)</option>
                            <?php foreach($allUsers as $u): ?>
                                <option value="<?= htmlspecialchars($u['name']) ?>"><?= htmlspecialchars($u['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Durum</label>
                        <select name="status" id="invStatus" class="form-control">
                            <option value="active">Aktif (Kullanımda)</option>
                            <option value="maintenance">Bakımda</option>
                            <option value="broken">Arızalı</option>
                            <option value="lost">Kayıp / Çalıntı</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column:1/-1">
                        <label class="form-label">Fotoğraf (Opsiyonel)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <div id="invCurrentImg" style="margin-top:8px;font-size:12px;color:var(--muted);display:none;"></div>
                    </div>
                    <div class="form-group" style="grid-column:1/-1;margin-bottom:0;">
                        <label class="form-label">Notlar</label>
                        <textarea name="notes" id="invNotes" class="form-control" style="min-height:60px;"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="inv-modal-footer">
                <button type="button" class="inv-btn" style="background:var(--input-bg);border:1px solid var(--border);color:var(--text);" onclick="closeInvModal()">İptal</button>
                <button type="submit" class="inv-btn inv-btn-primary">Kaydet</button>
            </div>
        </form>
    </div>
</div>

<script>
function openInvModal(data = null) {
    const form = document.getElementById('invForm');
    if(data) {
        document.getElementById('invModalTitle').innerText = 'Eşyayı Düzenle';
        document.getElementById('invItemId').value = data.id;
        document.getElementById('invItemName').value = data.item_name;
        document.getElementById('invCategory').value = data.category || '';
        document.getElementById('invSerial').value = data.serial_number || '';
        document.getElementById('invPrice').value = data.price;
        document.getElementById('invPurchase').value = data.purchase_date || '';
        document.getElementById('invLocation').value = data.location || '';
        document.getElementById('invAssigned').value = data.assigned_to || '';
        document.getElementById('invStatus').value = data.status;
        document.getElementById('invNotes').value = data.notes || '';
        document.getElementById('invExistingImage').value = data.image_url || '';
        
        if(data.image_url) {
            document.getElementById('invCurrentImg').innerHTML = 'Mevcut görsel: ' + data.image_url.split('/').pop() + ' <br><i>(Değiştirmek için yeni dosya seçin)</i>';
            document.getElementById('invCurrentImg').style.display = 'block';
        } else {
            document.getElementById('invCurrentImg').style.display = 'none';
        }
    } else {
        document.getElementById('invModalTitle').innerText = 'Yeni Eşya Ekle';
        document.getElementById('invItemId').value = '';
        form.reset();
        document.getElementById('invExistingImage').value = '';
        document.getElementById('invCurrentImg').style.display = 'none';
    }
    
    document.getElementById('invModalOverlay').classList.add('active');
}

function closeInvModal() {
    document.getElementById('invModalOverlay').classList.remove('active');
}
</script>
<?php endif; ?>

<!-- Transfer Modal -->
<div class="inv-modal-overlay" id="transferModalOverlay" onclick="if(event.target===this) closeTransferModal()">
    <div class="inv-modal" style="max-width: 400px;">
        <form method="POST" style="margin:0;">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="action" value="transfer_item">
            <input type="hidden" name="item_id" id="transferItemId" value="">
            
            <div class="inv-modal-header">
                <h3 style="margin:0; font-size: 16px;">Eşyayı Devret</h3>
                <button type="button" onclick="closeTransferModal()" style="background:transparent;border:none;color:var(--muted);cursor:pointer;">
                    <svg width="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            
            <div class="inv-modal-body">
                <p style="font-size: 13px; color: var(--muted); margin-bottom: 16px;">
                    <strong id="transferItemName" style="color: var(--text);"></strong> adlı eşyayı kime devretmek istiyorsunuz?
                </p>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Kullanıcı Seçin</label>
                    <select name="new_user" class="form-control" required>
                        <option value="">Seçiniz...</option>
                        <?php foreach($allUsers as $u): ?>
                            <?php if($u['name'] !== $this->user['name']): ?>
                                <option value="<?= htmlspecialchars($u['name']) ?>"><?= htmlspecialchars($u['name']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="inv-modal-footer">
                <button type="button" class="inv-btn" style="background:var(--input-bg);color:var(--text);border:1px solid var(--border);" onclick="closeTransferModal()">İptal</button>
                <button type="submit" class="inv-btn inv-btn-primary">Devret</button>
            </div>
        </form>
    </div>
</div>

<script>
function openTransferModal(itemId, itemName) {
    document.getElementById('transferItemId').value = itemId;
    document.getElementById('transferItemName').textContent = itemName;
    document.getElementById('transferModalOverlay').classList.add('active');
}

function closeTransferModal() {
    document.getElementById('transferModalOverlay').classList.remove('active');
}
</script>
