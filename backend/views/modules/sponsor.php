<style>
@media (max-width: 768px) {
    .sponsor-header {
        margin-bottom: 14px !important;
        gap: 12px;
        align-items: center !important;
    }
    .sponsor-header h3 {
        font-size: 18px !important;
        line-height: 1.15;
        margin: 0;
    }
    .sponsor-header p {
        display: none;
    }
    .sponsor-header .btn {
        min-height: 36px;
        padding: 8px 10px;
        border-radius: 10px;
        font-size: 12px;
        white-space: nowrap;
    }
    .sponsor-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 10px !important;
    }
    .sponsor-card {
        min-width: 0;
        border-radius: 14px;
    }
    .sponsor-logo-box {
        height: 88px !important;
        background: #1f2933 !important;
        padding: 8px !important;
    }
    [data-theme="light"] .sponsor-logo-box {
        background: #e5e7eb !important;
    }
    .sponsor-logo-empty {
        height: 88px !important;
        border-radius: 0;
    }
    .sponsor-card .card-body {
        padding: 10px !important;
    }
    .sponsor-card h4 {
        font-size: 13px !important;
        line-height: 1.2;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .sponsor-card .badge {
        font-size: 9px;
        padding: 3px 7px;
        border-radius: 999px;
    }
    .sponsor-type {
        font-size: 10px !important;
        margin-bottom: 8px !important;
    }
    .sponsor-contact {
        display: none;
    }
    .sponsor-notes {
        display: none;
    }
    .sponsor-actions {
        display: grid !important;
        grid-template-columns: 1fr 1fr;
        gap: 6px !important;
        margin-top: 10px !important;
        padding-top: 8px !important;
    }
    .sponsor-actions form {
        display: block !important;
        width: 100%;
    }
    .sponsor-actions .btn {
        width: 100%;
        min-height: 30px;
        padding: 6px 8px;
        border-radius: 9px;
        font-size: 11px;
    }
    #sponsorModal {
        padding: 14px;
        align-items: center !important;
        z-index: 2000 !important;
    }
    #sponsorModal > .card {
        width: 100% !important;
        max-width: 100% !important;
        max-height: calc(100vh - 28px);
        margin: auto !important;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    #sponsorModal .card-header {
        padding: 14px !important;
    }
    #sponsorModal .card-body {
        padding: 14px !important;
        overflow-y: auto;
    }
    #sponsorModal .form-row {
        grid-template-columns: 1fr !important;
        gap: 0 !important;
    }
    #sponsorModal .form-group {
        margin-bottom: 12px;
    }
    #sponsorModal .form-label {
        font-size: 11px;
        margin-bottom: 6px;
    }
    #sponsorModal .form-control {
        min-height: 38px;
        padding: 9px 10px;
        border-radius: 9px;
        font-size: 13px;
    }
    #sponsorModal textarea.form-control {
        min-height: 82px !important;
    }
    #sponsorModal form > div[style*="justify-content"] {
        display: grid !important;
        grid-template-columns: 1fr 1fr;
        gap: 8px !important;
    }
    #sponsorModal .btn {
        width: 100%;
        min-height: 38px;
        padding: 8px 10px;
        border-radius: 10px;
        font-size: 13px;
    }
}
</style>

<div class="sponsor-header" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h3 style="font-size: 16px; font-weight:600; color:var(--text);"><?= View::escape($app['name']) ?></h3>
        <p style="font-size: 12px; color: var(--muted);"><?= View::escape($app['description'] ?? '') ?></p>
    </div>
    <?php if ($isDirectorOrAdmin): ?>
    <button class="btn btn-primary btn-sm" onclick="openSponsorModal()">
        + Yeni Sponsor Ekle
    </button>
    <?php endif; ?>
</div>

<div class="sponsor-grid fade-in-up stagger-1" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px;">
    <?php if (empty($sponsors)): ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--muted); border: 1px dashed var(--border); border-radius: 8px;">
            Henüz sponsor eklenmemiş.
        </div>
    <?php else: ?>
        <?php 
        $sponStagger = 1;
        foreach ($sponsors as $sponsor): 
        ?>
            <div class="sponsor-card card fade-in-up stagger-<?= min($sponStagger++, 5) ?>" style="background: var(--card-alt); border-color: var(--border); overflow: hidden;">
                <?php if (!empty($sponsor['logo_url'])): ?>
                    <div class="sponsor-logo-box" style="height: 140px; background: #fff; display: flex; align-items: center; justify-content: center; padding: 10px;">
                        <img src="<?= BASE_URL ?>/<?= View::escape($sponsor['logo_url']) ?>" alt="Logo" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    </div>
                <?php else: ?>
                    <div class="sponsor-logo-empty" style="height: 140px; background: #222; display: flex; align-items: center; justify-content: center; color: var(--muted); font-size: 12px;">
                        Logo Yok
                    </div>
                <?php endif; ?>
                
                <div class="card-body" style="padding: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                        <h4 style="font-size: 16px; font-weight: 600; color: var(--text); margin: 0;"><?= View::escape($sponsor['name']) ?></h4>
                        <?php if ($sponsor['status'] === 'active'): ?>
                            <span class="badge" style="background: rgba(34,197,94,0.15); color: #4ade80;">Aktif</span>
                        <?php else: ?>
                            <span class="badge" style="background: rgba(239,68,68,0.15); color: #f87171;">Pasif</span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($sponsor['sponsorship_type'])): ?>
                        <div class="sponsor-type" style="font-size: 12px; color: var(--accent); margin-bottom: 12px; font-weight: 500;">
                            <?= View::escape($sponsor['sponsorship_type']) ?> Sponsor
                        </div>
                    <?php endif; ?>

                    <div class="sponsor-contact" style="font-size: 12px; color: var(--muted); line-height: 1.6;">
                        <?php if (!empty($sponsor['contact_person'])): ?>
                            <div>👤 <?= View::escape($sponsor['contact_person']) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($sponsor['phone'])): ?>
                            <div>📞 <a href="tel:<?= View::escape($sponsor['phone']) ?>" style="color:inherit;text-decoration:none;"><?= View::escape($sponsor['phone']) ?></a></div>
                        <?php endif; ?>
                        <?php if (!empty($sponsor['email'])): ?>
                            <div>✉️ <a href="mailto:<?= View::escape($sponsor['email']) ?>" style="color:inherit;text-decoration:none;"><?= View::escape($sponsor['email']) ?></a></div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($sponsor['notes'])): ?>
                        <div class="sponsor-notes" style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border); font-size: 11px; color: var(--muted);">
                            <?= nl2br(View::escape($sponsor['notes'])) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($isDirectorOrAdmin || (int)$sponsor['created_by'] === $user['id']): ?>
                        <div class="sponsor-actions" style="display: flex; gap: 8px; margin-top: 16px; border-top: 1px solid var(--border); padding-top: 12px;">
                            <button class="btn btn-outline btn-sm" style="flex:1;" onclick="editSponsor(<?= $sponsor['id'] ?>, '<?= View::escape(addslashes($sponsor['name'])) ?>', '<?= View::escape(addslashes($sponsor['contact_person'])) ?>', '<?= View::escape(addslashes($sponsor['email'])) ?>', '<?= View::escape(addslashes($sponsor['phone'])) ?>', '<?= View::escape(addslashes($sponsor['sponsorship_type'])) ?>', '<?= View::escape(addslashes($sponsor['status'])) ?>', '<?= View::escape(addslashes(str_replace(["\r", "\n"], ["\\r", "\\n"], $sponsor['notes']))) ?>')">Düzenle</button>
                            
                            <form method="POST" style="margin:0; flex:1; display:flex;" onsubmit="return confirm('Bu sponsoru silmek istediğinize emin misiniz?');">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="sponsor_id" value="<?= $sponsor['id'] ?>">
                                <button type="submit" class="btn btn-outline btn-sm" style="flex:1; border-color: rgba(239,68,68,0.3); color: #f87171;">Sil</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal Form -->
<div id="sponsorModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:var(--modal-overlay); z-index:9999; align-items:center; justify-content:center; overflow-y:auto;">
    <div class="card pop-in" style="width: 90%; max-width: 600px; margin: 20px auto;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; position: sticky; top:0; background:var(--card); z-index:10;">
            <span id="sponsorModalTitle">Yeni Sponsor Ekle</span>
            <button onclick="closeSponsorModal()" style="background:transparent; border:none; color:var(--text); font-size:18px; cursor:pointer;">&times;</button>
        </div>
        <div class="card-body" style="padding: 20px;">
            <form method="POST" action="<?= BASE_URL ?>/panel/<?= View::escape($department['slug']) ?>/apps/<?= View::escape($app['slug']) ?>" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" id="sponsorAction" name="action" value="add">
                <input type="hidden" id="sponsorEditId" name="sponsor_id" value="">

                <div class="form-row" style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Sponsor/Firma Adı *</label>
                        <input type="text" id="sponsorName" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sponsorluk Tipi</label>
                        <input type="text" id="sponsorType" name="sponsorship_type" class="form-control" placeholder="Altın, Gümüş, vb.">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">İlgili Kişi</label>
                    <input type="text" id="sponsorContact" name="contact_person" class="form-control">
                </div>

                <div class="form-row" style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
                    <div class="form-group">
                        <label class="form-label">E-posta</label>
                        <input type="email" id="sponsorEmail" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telefon</label>
                        <input type="text" id="sponsorPhone" name="phone" class="form-control">
                    </div>
                </div>

                <div class="form-row" style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Durum</label>
                        <select id="sponsorStatus" name="status" class="form-control">
                            <option value="active">Aktif</option>
                            <option value="inactive">Pasif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Logo (Opsiyonel)</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <small style="color:var(--muted); font-size:10px;">Düzenlerken boş bırakırsanız eski logo korunur.</small>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Notlar</label>
                    <textarea id="sponsorNotes" name="notes" class="form-control" style="min-height: 80px;"></textarea>
                </div>

                <div style="display:flex; justify-content: flex-end; gap: 10px; margin-top: 16px;">
                    <button type="button" class="btn btn-outline" onclick="closeSponsorModal()">İptal</button>
                    <button type="submit" class="btn btn-primary" id="sponsorSubmitBtn">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
window.openSponsorModal = function openSponsorModal() {
    document.getElementById('sponsorAction').value = 'add';
    document.getElementById('sponsorEditId').value = '';
    document.getElementById('sponsorName').value = '';
    document.getElementById('sponsorType').value = '';
    document.getElementById('sponsorContact').value = '';
    document.getElementById('sponsorEmail').value = '';
    document.getElementById('sponsorPhone').value = '';
    document.getElementById('sponsorStatus').value = 'active';
    document.getElementById('sponsorNotes').value = '';
    
    document.getElementById('sponsorModalTitle').innerText = 'Yeni Sponsor Ekle';
    document.getElementById('sponsorSubmitBtn').innerText = 'Ekle';
    document.getElementById('sponsorModal').style.display = 'flex';
};

window.editSponsor = function editSponsor(id, name, contact, email, phone, type, status, notes) {
    document.getElementById('sponsorAction').value = 'edit';
    document.getElementById('sponsorEditId').value = id;
    document.getElementById('sponsorName').value = name;
    document.getElementById('sponsorContact').value = contact;
    document.getElementById('sponsorEmail').value = email;
    document.getElementById('sponsorPhone').value = phone;
    document.getElementById('sponsorType').value = type;
    document.getElementById('sponsorStatus').value = status;
    
    // Convert literal \n or \r back to actual newlines for textarea
    const formattedNotes = notes.replace(/\\n/g, '\n').replace(/\\r/g, '\r');
    document.getElementById('sponsorNotes').value = formattedNotes;
    
    document.getElementById('sponsorModalTitle').innerText = 'Sponsor Düzenle';
    document.getElementById('sponsorSubmitBtn').innerText = 'Kaydet';
    document.getElementById('sponsorModal').style.display = 'flex';
};

window.closeSponsorModal = function closeSponsorModal() {
    document.getElementById('sponsorModal').style.display = 'none';
};

var sponsorModalEl = document.getElementById('sponsorModal');
if (sponsorModalEl) {
    sponsorModalEl.addEventListener('click', function(event) {
        if (event.target === sponsorModalEl) {
            window.closeSponsorModal();
        }
    });
}
</script>
