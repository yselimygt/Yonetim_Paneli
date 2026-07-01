<?php
// views/modules/partner.php
// $partners
// $canEdit

function renderPartnerCard($partner, $canEdit) {
    $name = htmlspecialchars($partner['venue_name']);
    $address = htmlspecialchars($partner['address'] ?? '');
    $mapLink = htmlspecialchars($partner['map_link'] ?? '');
    $discount = htmlspecialchars($partner['discount_detail'] ?? '');
    $contactPerson = htmlspecialchars($partner['contact_person'] ?? '');
    $contactPhone = htmlspecialchars($partner['contact_phone'] ?? '');
    $img = $partner['image_path'] ? BASE_URL . '/public/' . $partner['image_path'] : '';
    
    $statusClass = $partner['status'] ? 'prt-badge-active' : 'prt-badge-inactive';
    $statusText = $partner['status'] ? 'Aktif' : 'Pasif';
    $opacity = $partner['status'] ? '1' : '0.65';
    
    $csrf = $_SESSION['csrf_token'] ?? '';
    
    ob_start();
    ?>
    <div class="prt-card" style="opacity: <?= $opacity ?>;">
        <?php if($img): ?>
            <div class="prt-img-wrapper">
                <img src="<?= $img ?>" alt="Görsel" class="prt-img" loading="lazy">
                <div class="prt-img-overlay"></div>
            </div>
        <?php endif; ?>
        
        <div class="prt-content">
            <div class="prt-header">
                <h3 class="prt-title"><?= $name ?></h3>
                <span class="prt-badge <?= $statusClass ?>"><?= $statusText ?></span>
            </div>
            
            <div class="prt-details">
                <?php if($discount): ?>
                    <div class="prt-row discount-row">
                        <svg class="prt-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        <span style="font-weight:600; color:var(--text);"><?= $discount ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if($address): ?>
                    <div class="prt-row">
                        <svg class="prt-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span><?= $address ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if($contactPerson || $contactPhone): ?>
                    <div class="prt-row">
                        <svg class="prt-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                        <span><?= $contactPerson ?> <?= $contactPhone ? ' - ' . $contactPhone : '' ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="prt-footer">
            <?php if($mapLink): ?>
                <a href="<?= $mapLink ?>" target="_blank" class="prt-btn-map" title="Haritada Gör">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                    Harita
                </a>
            <?php else: ?>
                <div></div>
            <?php endif; ?>
        
        <?php if($canEdit): ?>
            <div class="prt-actions">
                <button type="button" class="prt-action-btn" title="Düzenle" onclick='openPartnerEditModal(<?= json_encode($partner, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>)'>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <form method="POST" style="margin:0;">
                    <input type="hidden" name="action" value="toggle_status">
                    <input type="hidden" name="partner_id" value="<?= $partner['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <button type="submit" class="prt-action-btn" title="<?= $partner['status'] ? 'Pasife Al' : 'Aktif Yap' ?>">
                        <?php if($partner['status']): ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        <?php else: ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <?php endif; ?>
                    </button>
                </form>
                <form method="POST" style="margin:0;" onsubmit="return confirm('Bu mekanı silmek istediğinize emin misiniz?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="partner_id" value="<?= $partner['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <button type="submit" class="prt-action-btn delete">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                    </button>
                </form>
            </div>
        <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
?>

<style>
/* Partner Module Premium UI */
:root {
    --prt-card: var(--card);
    --prt-border: rgba(255, 255, 255, 0.08);
    --prt-muted: #9ca3af;
    --prt-hover: #2a2a2a;
    --prt-primary: #14b8a6;
    --prt-primary-hover: #0d9488;
}

[data-theme="light"] {
    --prt-card: #ffffff;
    --prt-border: #e5e7eb;
    --prt-muted: #6b7280;
    --prt-hover: #f9fafb;
}

.prt-header-area {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}

.prt-header-title { font-size: 24px; font-weight: 700; color: var(--text); margin: 0; }
.prt-header-subtitle { color: var(--prt-muted); font-size: 14px; margin: 4px 0 0 0; }

.prt-btn-add {
    background: linear-gradient(135deg, var(--prt-primary) 0%, var(--prt-primary-hover) 100%);
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(20, 184, 166, 0.25);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.prt-btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(20, 184, 166, 0.35);
}

.prt-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.prt-card {
    background: var(--prt-card);
    border: 1px solid var(--prt-border);
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    animation: fadeUp 0.5s ease-out backwards;
    display: flex;
    flex-direction: column;
}
.prt-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.12);
    border-color: rgba(20, 184, 166, 0.3);
}

.prt-img-wrapper {
    width: 100%;
    height: 180px;
    min-height: 180px;
    position: relative;
    overflow: hidden;
    background: #1a1a1a;
    flex-shrink: 0;
}
.prt-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.6s ease;
}
.prt-card:hover .prt-img { transform: scale(1.08); }

.prt-img-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0) 50%, rgba(0,0,0,0.6) 100%);
}

.prt-content {
    padding: 20px;
    flex-grow: 1;
}

.prt-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 16px;
}
.prt-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text);
    margin: 0;
    line-height: 1.3;
}

.prt-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    white-space: nowrap;
}
.prt-badge-active { background: rgba(16, 185, 129, 0.15); color: #10b981; }
.prt-badge-inactive { background: rgba(107, 114, 128, 0.15); color: #9ca3af; }

.prt-details {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 16px;
}
.prt-row {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    color: var(--prt-muted);
    font-size: 14px;
    line-height: 1.5;
}
.discount-row {
    background: rgba(20, 184, 166, 0.1);
    padding: 10px 12px;
    border-radius: 8px;
    border-left: 3px solid var(--prt-primary);
}
.prt-icon {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
    margin-top: 2px;
    color: var(--prt-primary);
}

.prt-footer {
    padding: 16px 20px;
    border-top: 1px solid var(--prt-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(0,0,0,0.02);
}

.prt-btn-map {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--prt-primary);
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: opacity 0.2s;
}
.prt-btn-map:hover { opacity: 0.8; }

.prt-actions {
    display: flex;
    gap: 6px;
}
.prt-action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    background: var(--card);
    color: var(--prt-muted);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
.prt-action-btn:hover { background: var(--prt-hover); color: var(--text); }
.prt-action-btn.delete:hover { color: #ef4444; background: rgba(239, 68, 68, 0.1); }
.prt-action-btn svg { width: 16px; height: 16px; }

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Modal CSS */
.modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);
    display: flex; justify-content: center; align-items: center;
    z-index: 1000; opacity: 0; pointer-events: none;
    transition: opacity 0.3s ease;
    padding: 20px;
}
.modal-overlay.active { opacity: 1; pointer-events: auto; }
.modal-content {
    background: var(--card);
    width: 100%; max-width: 800px;
    border-radius: 20px;
    border: 1px solid var(--prt-border);
    padding: 32px;
    box-shadow: 0 24px 48px rgba(0,0,0,0.2);
    transform: translateY(20px) scale(0.95);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    max-height: 90vh;
    overflow-y: auto;
}
.modal-overlay.active .modal-content { transform: translateY(0) scale(1); }

.modal-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 24px; border-bottom: 1px solid var(--prt-border); padding-bottom: 16px;
}
.modal-header h3 { margin: 0; font-size: 20px; color: var(--text); }
.close-modal {
    background: none; border: none; color: var(--prt-muted); cursor: pointer;
    padding: 8px; border-radius: 50%; transition: background 0.2s; display: flex;
}
.close-modal:hover { background: var(--prt-hover); color: var(--text); }

.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--prt-muted); font-size: 13px; }
.form-control {
    width: 100%; padding: 12px 16px; border-radius: 10px;
    border: 1px solid var(--prt-border); background: var(--bg); color: var(--text);
    font-size: 14px; transition: all 0.2s;
}
.form-control:focus { outline: none; border-color: var(--prt-primary); box-shadow: 0 0 0 3px rgba(20,184,166,0.1); }

.prt-btn-submit {
    width: 100%; padding: 14px; border: none; border-radius: 10px;
    background: linear-gradient(135deg, var(--prt-primary) 0%, var(--prt-primary-hover) 100%);
    color: #fff; font-size: 15px; font-weight: 600; cursor: pointer;
    transition: all 0.3s; margin-top: 10px;
}
.prt-btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(20,184,166,0.3); }

/* Scrollbar for modal */
.modal-content::-webkit-scrollbar { width: 8px; }
.modal-content::-webkit-scrollbar-track { background: var(--bg); border-radius: 4px; }
.modal-content::-webkit-scrollbar-thumb { background: var(--prt-border); border-radius: 4px; }
.modal-content::-webkit-scrollbar-thumb:hover { background: var(--prt-muted); }
</style>

<div class="prt-header-area fade-in-up">
    <div>
        <h2 class="prt-header-title">Anlaşmalı Mekanlar</h2>
        <p class="prt-header-subtitle">İndirimli ve anlaşmalı olduğunuz mekan/marka bilgilerini buradan yönetebilirsiniz.</p>
    </div>
    
    <?php if($canEdit): ?>
        <button class="prt-btn-add" onclick="openPartnerModal()">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Yeni Ekle
        </button>
    <?php endif; ?>
</div>

<div class="prt-grid">
    <?php 
    if(empty($partners)) {
        echo "<div style='color:var(--prt-muted); font-size:14px; padding:20px; background:var(--card); border-radius:12px; border:1px solid var(--border); grid-column: 1 / -1; text-align:center;'>Henüz anlaşmalı mekan eklenmemiş.</div>";
    }
    foreach($partners as $index => $partner) {
        // Adding stagger delay
        echo str_replace('class="prt-card"', 'class="prt-card stagger-'.min($index+1, 5).'"', renderPartnerCard($partner, $canEdit));
    }
    ?>
</div>

<?php if($canEdit): ?>
<!-- Ekle/Düzenle Modal -->
<div class="modal-overlay" id="prtModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Yeni Anlaşmalı Mekan Ekle</h3>
            <button type="button" class="close-modal" onclick="closePartnerModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" enctype="multipart/form-data" id="prtForm">
            <input type="hidden" name="action" id="prtAction" value="add">
            <input type="hidden" name="partner_id" id="prtId" value="">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <div style="display:flex; gap:16px;">
                <div class="form-group" style="flex:2;">
                    <label>Mekan / Marka Adı</label>
                    <input type="text" name="venue_name" class="form-control" required placeholder="Örn: X Coffee">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Yetkili Kişi</label>
                    <input type="text" name="contact_person" class="form-control" placeholder="Örn: Ahmet Yılmaz">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>İletişim No</label>
                    <input type="text" name="contact_phone" class="form-control phone-mask" placeholder="(555) 123 45 67" maxlength="15">
                </div>
            </div>
            
            <div style="display:flex; gap:16px;">
                <div class="form-group" style="flex:2;">
                    <label>Açık Adres</label>
                    <input type="text" name="address" class="form-control" placeholder="Örn: Caferağa Mah. Moda Cad. No:1">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Harita Linki (Google Maps)</label>
                    <input type="text" name="map_link" class="form-control" placeholder="Örn: https://maps.google.com/...">
                </div>
            </div>
            
            <div class="form-group">
                <label>Ayrıcalık / İndirim Detayı</label>
                <textarea name="discount_detail" class="form-control" rows="3" placeholder="Örn: Tüm kahvelerde %15 indirim..."></textarea>
            </div>
            
            <div class="form-group">
                <label>Görsel / Logo (Opsiyonel)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            
            <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:24px;">
                <button type="button" class="btn btn-outline" onclick="closePartnerModal()">İptal</button>
                <button type="submit" class="btn">Kaydet</button>
            </div>
        </form>
    </div>
</div>

<script>
function openPartnerModal() {
    document.getElementById('prtForm').reset();
    document.getElementById('modalTitle').textContent = 'Yeni Anlaşmalı Mekan Ekle';
    document.getElementById('prtAction').value = 'add';
    document.getElementById('prtId').value = '';
    
    document.getElementById('prtModal').classList.add('active');
}

function openPartnerEditModal(prt) {
    document.getElementById('prtForm').reset();
    document.getElementById('modalTitle').textContent = 'Mekanı Düzenle';
    document.getElementById('prtAction').value = 'edit';
    document.getElementById('prtId').value = prt.id;
    
    document.querySelector('input[name="venue_name"]').value = prt.venue_name;
    document.querySelector('input[name="address"]').value = prt.address || '';
    document.querySelector('input[name="map_link"]').value = prt.map_link || '';
    document.querySelector('input[name="contact_person"]').value = prt.contact_person || '';
    document.querySelector('input[name="contact_phone"]').value = prt.contact_phone || '';
    document.querySelector('textarea[name="discount_detail"]').value = prt.discount_detail || '';
    
    document.getElementById('prtModal').classList.add('active');
}

function closePartnerModal() {
    document.getElementById('prtModal').classList.remove('active');
}

document.addEventListener("DOMContentLoaded", function() {
    const phoneInputs = document.querySelectorAll('.phone-mask');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
            if (!x[1]) {
                e.target.value = '';
                return;
            }
            e.target.value = !x[2] ? '(' + x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? ' ' + x[3] : '') + (x[4] ? ' ' + x[4] : '');
        });
    });
});
</script>
<?php endif; ?>
