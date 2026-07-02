<?php $pageTitle = 'Site Tarihçesi (Başarılar / Ödüller)'; ?>

<style>
.history-type-pill,
.history-link-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 22px;
    padding: 3px 8px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    border: 1px solid var(--border);
    color: var(--text);
    background: var(--bg);
    text-decoration: none;
}

.history-type-pill.achievement {
    border-color: rgba(34,197,94,.24);
    background: rgba(34,197,94,.12);
    color: #86efac;
}

.history-type-pill.award {
    border-color: rgba(245,158,11,.28);
    background: rgba(245,158,11,.13);
    color: #fbbf24;
}

.history-link-pill {
    border-color: rgba(0,188,212,.25);
    background: rgba(0,188,212,.1);
    color: var(--accent);
}

.history-source-pill {
    display: inline-flex;
    align-items: center;
    min-height: 22px;
    padding: 3px 8px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    border: 1px solid rgba(245,158,11,.25);
    background: rgba(245,158,11,.1);
    color: #fbbf24;
}

.history-image-thumb {
    width: 96px;
    height: 68px;
    border-radius: 8px;
    border: 1px solid var(--border);
    object-fit: cover;
    display: block;
    background: var(--bg);
}

.history-image-placeholder {
    width: 96px;
    height: 68px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: rgba(255,255,255,.025);
    color: var(--muted);
    display: grid;
    place-items: center;
    font-size: 11px;
    font-weight: 800;
}

@media (max-width: 700px) {
    .history-page-header {
        margin-bottom: 10px !important;
    }

    .history-page-header h2 {
        font-size: 14px;
        line-height: 1.15;
        max-width: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .history-page-header .btn {
        min-height: 30px;
        padding: 6px 9px;
        font-size: 11px;
    }

    .history-card {
        border: 0;
        background: transparent;
        box-shadow: none;
    }

    .history-table-wrap {
        overflow: visible !important;
    }

    .history-table {
        display: block;
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
        display: block;
        column-count: 2;
        column-gap: 8px;
    }

    .history-table tr {
        display: inline-grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        align-content: start;
        gap: 7px;
        width: 100%;
        min-width: 0;
        margin: 0 0 8px;
        border: 1px solid var(--border);
        border-radius: 9px;
        background:
            linear-gradient(145deg, rgba(245,158,11,.1), transparent 58%),
            rgba(255,255,255,.025);
        padding: 8px;
        box-shadow: none;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .history-table tr.history-trophy-row {
        overflow: hidden;
        background:
            linear-gradient(145deg, rgba(245,158,11,.16), transparent 62%),
            rgba(255,255,255,.03);
        border-color: rgba(245,158,11,.24);
    }

    .history-table tr.history-empty-row {
        grid-column: 1 / -1;
    }

    .history-table td {
        display: block;
        max-width: none;
        border-bottom: 0;
        padding: 0;
        white-space: normal;
        overflow: visible;
        text-align: left;
    }

    .history-table td::before {
        display: none;
    }

    .history-year-cell {
        order: 2;
    }

    .history-title-cell {
        order: 3;
        grid-column: 1 / -1;
    }

    .history-image-cell {
        order: 1;
        grid-column: 1 / -1;
        width: calc(100% + 16px) !important;
        margin: -8px -8px 0;
    }

    .history-image-cell.is-empty {
        display: none !important;
    }

    .history-image-thumb {
        width: 100%;
        height: 132px;
        border-radius: 9px 9px 0 0;
        border: 0;
        object-fit: contain;
        background: #050505;
    }

    .history-image-placeholder {
        width: 100%;
        height: 132px;
        border-radius: 9px 9px 0 0;
        border: 0;
    }

    .history-table tr.history-trophy-row:nth-child(3n) .history-image-thumb,
    .history-table tr.history-trophy-row:nth-child(3n) .history-image-placeholder {
        height: 166px;
    }

    .history-table tr.history-trophy-row:nth-child(4n) .history-image-thumb,
    .history-table tr.history-trophy-row:nth-child(4n) .history-image-placeholder {
        height: 112px;
    }

    .history-description-cell {
        display: none !important;
    }

    .history-type-cell {
        order: 2;
    }

    .history-actions-cell {
        order: 5;
        grid-column: 1 / -1;
    }

    .history-table td:last-child {
        display: block;
    }

    .history-title-cell,
    .history-description {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .history-title-cell a {
        margin-top: 5px;
    }

    .history-title-text {
        color: var(--text);
        font-size: 12px;
        font-weight: 800;
        line-height: 1.22;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .history-year-cell strong {
        display: inline-flex;
        min-height: 22px;
        align-items: center;
        justify-content: center;
        padding: 3px 8px;
        border-radius: 999px;
        border: 1px solid rgba(0,188,212,.25);
        background: rgba(0,188,212,.1);
        color: var(--accent);
        font-size: 10px;
    }

    .history-type-pill,
    .history-link-pill,
        .history-source-pill {
        min-height: 20px;
        padding: 2px 6px;
        font-size: 9px;
    }

    .history-actions {
        display: grid !important;
        grid-template-columns: 1fr 1fr;
        gap: 6px !important;
    }

    .history-actions .btn,
    .history-actions form {
        width: 100%;
    }

    .history-actions .btn {
        min-height: 28px;
        padding: 5px 7px;
        font-size: 10px;
        justify-content: center;
    }

    .history-modal-actions {
        flex-direction: row !important;
    }

    .history-modal-actions .btn {
        flex: 1 1 0;
        min-width: 0;
        width: auto;
    }
}
</style>

<div class="page-header history-page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <h2>Site Tarihçesi (Başarılar / Ödüller)</h2>
    <button class="btn btn-primary" onclick="openModal('addHistoryModal')">Yeni Kayıt Ekle</button>
</div>

<div class="card history-card">
    <div class="history-table-wrap" style="overflow-x:auto;">
        <table class="table history-table">
            <thead>
                <tr>
                    <th style="width:112px;">Görsel</th>
                    <th style="width:100px;">Yıl</th>
                    <th>Başlık</th>
                    <th>Açıklama</th>
                    <th>Tür</th>
                    <th style="width:100px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($historyItems)): ?>
                <tr class="history-empty-row">
                    <td colspan="6" data-label="" style="text-align:center; padding:20px; color:var(--muted);">Henüz kayıt eklenmemiş.</td>
                </tr>
                <?php else: ?>
                    <?php foreach($historyItems as $h): ?>
                    <?php $isTrophyAward = ($h['source'] ?? 'history') === 'trophy'; ?>
                    <tr class="<?= $isTrophyAward ? 'history-trophy-row' : '' ?>">
                        <td data-label="Görsel" class="history-image-cell <?= $isTrophyAward ? '' : 'is-empty' ?>">
                            <?php if($isTrophyAward && !empty($h['image_path'])): ?>
                                <img src="<?= BASE_URL ?>/public/uploads/trophies/<?= View::escape($h['image_path']) ?>" alt="<?= View::escape($h['title']) ?>" class="history-image-thumb">
                            <?php elseif($isTrophyAward): ?>
                                <div class="history-image-placeholder">Yok</div>
                            <?php endif; ?>
                        </td>
                        <td data-label="Yıl" class="history-year-cell"><strong><?= View::escape($h['year']) ?></strong></td>
                        <td data-label="Başlık" class="history-title-cell">
                            <div class="history-title-text"><?= View::escape($h['title']) ?></div>
                            <?php if($h['link']): ?>
                                <a href="<?= View::escape($h['link']) ?>" target="_blank" class="history-link-pill">Bağlantı</a>
                            <?php endif; ?>
                            <?php if($isTrophyAward): ?>
                                <span class="history-source-pill">Ödüller Modülü</span>
                            <?php endif; ?>
                        </td>
                        <td data-label="Açıklama" class="history-description-cell">
                            <div class="history-description" style="font-size:13px; color:var(--muted);">
                                <?= View::escape(mb_strimwidth($h['description'], 0, 80, '...')) ?>
                            </div>
                        </td>
                        <td data-label="Tür" class="history-type-cell">
                            <span class="history-type-pill <?= $h['type'] === 'achievement' ? 'achievement' : 'award' ?>">
                                <?= $h['type'] === 'achievement' ? 'Başarı' : 'Ödül' ?>
                            </span>
                        </td>
                        <td data-label="İşlemler" class="history-actions-cell">
                            <div class="history-actions" style="display:flex; gap:8px;">
                                <?php if($isTrophyAward): ?>
                                    <button class="btn btn-sm btn-outline" onclick='editTrophy(<?= json_encode($h, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>Düzenle</button>
                                    <form method="POST" action="<?= BASE_URL ?>/superadmin/site-history/trophies/<?= (int) ($h['trophy_id'] ?? 0) ?>/delete" onsubmit="return confirm('Bu ödülü silmek istediğinize emin misiniz?');">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline" onclick='editHistory(<?= json_encode($h, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>Düzenle</button>
                                    <form method="POST" action="<?= BASE_URL ?>/superadmin/site-history/<?= $h['id'] ?>/delete" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                                    </form>
                                <?php endif; ?>
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

<!-- Modal: Edit Trophy Award -->
<div id="editTrophyModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Ödülü Düzenle</h3>
            <button class="modal-close" onclick="closeModal('editTrophyModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" id="editTrophyForm" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tarih</label>
                        <input type="date" name="award_date" id="edit_trophy_award_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori</label>
                        <select name="award_type" id="edit_trophy_award_type" class="form-control" required>
                            <option value="hackathon">Hackathon</option>
                            <option value="competition">Yarışma</option>
                            <option value="project">Proje</option>
                            <option value="organization">Organizasyon</option>
                            <option value="social">Sosyal Sorumluluk</option>
                            <option value="other">Diğer Başarılar</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Başlık</label>
                    <input type="text" name="title" id="edit_trophy_title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Açıklama</label>
                    <textarea name="description" id="edit_trophy_description" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Görseli Değiştir (Opsiyonel)</label>
                    <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
                </div>

                <div class="history-modal-actions" style="margin-top:20px; display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('editTrophyModal')">İptal</button>
                    <button type="submit" class="btn btn-primary">Ödülü Kaydet</button>
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

function editTrophy(item) {
    document.getElementById('edit_trophy_award_date').value = (item.award_date || '').substring(0, 10);
    document.getElementById('edit_trophy_award_type').value = item.award_type || 'other';
    document.getElementById('edit_trophy_title').value = item.title || '';
    document.getElementById('edit_trophy_description').value = item.description || '';
    document.getElementById('editTrophyForm').action = `<?= BASE_URL ?>/superadmin/site-history/trophies/${item.trophy_id}`;
    openModal('editTrophyModal');
}
</script>
