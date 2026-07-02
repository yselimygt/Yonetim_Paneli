<?php $pageTitle = 'Site Tarihçesi (Başarılar / Ödüller)'; ?>

<style>
@media (max-width: 700px) {
    .history-card {
        border: 0;
        background: transparent;
        box-shadow: none;
    }

    .history-table-wrap {
        overflow: visible !important;
    }

    .history-table {
        display: table;
        overflow: visible;
    }

    .history-table thead {
        display: none;
    }

    .history-table,
    .history-table tbody,
    .history-table tr,
    .history-table td {
        width: 100%;
    }

    .history-table tbody {
        display: grid;
        gap: 12px;
    }

    .history-table tr {
        display: grid;
        gap: 10px;
        border: 1px solid var(--border);
        border-radius: 16px;
        background: var(--card);
        padding: 14px;
        box-shadow: 0 8px 28px rgba(0,0,0,0.14);
    }

    .history-table td {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        max-width: none;
        border-bottom: 1px solid rgba(255,255,255,.04);
        padding: 0 0 10px;
        white-space: normal;
        overflow: visible;
        text-align: right;
    }

    .history-table td::before {
        content: attr(data-label);
        flex: 0 0 104px;
        color: var(--muted);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .05em;
        text-align: left;
        text-transform: uppercase;
    }

    .history-table td:last-child {
        display: block;
        border-bottom: 0;
        padding-bottom: 0;
    }

    .history-table td:last-child::before {
        display: block;
        margin-bottom: 8px;
    }

    .history-title-cell,
    .history-description {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .history-title-cell a {
        display: inline-flex;
        margin-top: 6px;
        overflow-wrap: anywhere;
    }

    .history-description {
        white-space: normal;
    }

    .history-actions {
        display: grid !important;
        grid-template-columns: 1fr 1fr;
        gap: 8px !important;
    }

    .history-actions .btn,
    .history-actions form {
        width: 100%;
    }

    .history-actions .btn {
        justify-content: center;
    }

    .history-modal-actions {
        flex-direction: column-reverse;
    }

    .history-modal-actions .btn {
        width: 100%;
    }
}
</style>

<div class="page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <h2>Site Tarihçesi (Başarılar / Ödüller)</h2>
    <button class="btn btn-primary" onclick="openModal('addHistoryModal')">Yeni Kayıt Ekle</button>
</div>

<div class="card history-card">
    <div class="history-table-wrap" style="overflow-x:auto;">
        <table class="table history-table">
            <thead>
                <tr>
                    <th style="width:100px;">Yıl</th>
                    <th>Başlık</th>
                    <th>Açıklama</th>
                    <th>Tür</th>
                    <th style="width:100px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($historyItems)): ?>
                <tr>
                    <td colspan="5" data-label="" style="text-align:center; padding:20px; color:var(--muted);">Henüz kayıt eklenmemiş.</td>
                </tr>
                <?php else: ?>
                    <?php foreach($historyItems as $h): ?>
                    <tr>
                        <td data-label="Yıl"><strong><?= View::escape($h['year']) ?></strong></td>
                        <td data-label="Başlık" class="history-title-cell">
                            <div><?= View::escape($h['title']) ?></div>
                            <?php if($h['link']): ?>
                                <a href="<?= View::escape($h['link']) ?>" target="_blank" style="font-size:12px; color:var(--primary);">Bağlantı</a>
                            <?php endif; ?>
                        </td>
                        <td data-label="Açıklama">
                            <div class="history-description" style="font-size:13px; color:var(--muted);">
                                <?= View::escape(mb_strimwidth($h['description'], 0, 80, '...')) ?>
                            </div>
                        </td>
                        <td data-label="Tür">
                            <span class="badge" style="background:var(--bg); border:1px solid var(--border); color:var(--text);">
                                <?= $h['type'] === 'achievement' ? 'Başarı' : 'Ödül' ?>
                            </span>
                        </td>
                        <td data-label="İşlemler">
                            <div class="history-actions" style="display:flex; gap:8px;">
                                <button class="btn btn-sm btn-outline" onclick='editHistory(<?= json_encode($h) ?>)'>Düzenle</button>
                                <form method="POST" action="<?= BASE_URL ?>/superadmin/site-history/<?= $h['id'] ?>/delete" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
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

<!-- Modal: Add History -->
<div id="addHistoryModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Yeni Kayıt Ekle</h3>
            <button class="modal-close" onclick="closeModal('addHistoryModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-history">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Yıl</label>
                        <input type="text" name="year" class="form-control" placeholder="Örn: 2024" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tür</label>
                        <select name="type" class="form-control" required>
                            <option value="achievement">Başarı (Zaman Çizelgesi)</option>
                            <option value="award">Ödül</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Başlık</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Açıklama (Opsiyonel)</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">İkon (Lucide icon adı)</label>
                        <input type="text" name="icon" class="form-control" placeholder="Örn: Trophy, Target">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Link (Opsiyonel)</label>
                        <input type="url" name="link" class="form-control">
                    </div>
                </div>

                <div class="history-modal-actions" style="margin-top:20px; display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('addHistoryModal')">İptal</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit History -->
<div id="editHistoryModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Kaydı Düzenle</h3>
            <button class="modal-close" onclick="closeModal('editHistoryModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" id="editHistoryForm">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Yıl</label>
                        <input type="text" name="year" id="edit_year" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tür</label>
                        <select name="type" id="edit_type" class="form-control" required>
                            <option value="achievement">Başarı (Zaman Çizelgesi)</option>
                            <option value="award">Ödül</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Başlık</label>
                    <input type="text" name="title" id="edit_title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Açıklama (Opsiyonel)</label>
                    <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">İkon</label>
                        <input type="text" name="icon" id="edit_icon" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Link (Opsiyonel)</label>
                        <input type="url" name="link" id="edit_link" class="form-control">
                    </div>
                </div>

                <div class="history-modal-actions" style="margin-top:20px; display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('editHistoryModal')">İptal</button>
                    <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editHistory(item) {
    document.getElementById('edit_year').value = item.year;
    document.getElementById('edit_type').value = item.type;
    document.getElementById('edit_title').value = item.title;
    document.getElementById('edit_description').value = item.description || '';
    document.getElementById('edit_icon').value = item.icon || '';
    document.getElementById('edit_link').value = item.link || '';

    document.getElementById('editHistoryForm').action = `<?= BASE_URL ?>/superadmin/site-history/${item.id}`;
    openModal('editHistoryModal');
}
</script>
