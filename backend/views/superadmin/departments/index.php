<?php $pageTitle = 'Departmanlar'; ?>

<style>
@media (max-width: 700px) {
    .departments-card {
        border: 0;
        background: transparent;
        box-shadow: none;
    }

    .departments-table {
        display: table;
        overflow: visible;
    }

    .departments-table thead {
        display: none;
    }

    .departments-table,
    .departments-table tbody,
    .departments-table tr,
    .departments-table td {
        width: 100%;
    }

    .departments-table tbody {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
    }

    .departments-table tr {
        display: grid;
        gap: 7px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background:
            linear-gradient(135deg, color-mix(in srgb, var(--dept-color, var(--accent)) 22%, transparent), transparent 58%),
            var(--card);
        border-color: color-mix(in srgb, var(--dept-color, var(--border)) 45%, var(--border));
        padding: 10px;
        box-shadow: none;
        min-width: 0;
    }

    .departments-table td {
        display: block;
        max-width: none;
        border-bottom: 0;
        padding: 0;
        white-space: normal;
        overflow: visible;
        text-align: left;
        line-height: 1.35;
    }

    .departments-table td::before {
        content: attr(data-label);
        display: block;
        color: var(--muted);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .06em;
        text-align: left;
        text-transform: uppercase;
        margin-bottom: 3px;
    }

    .departments-table td:last-child {
        display: block;
        border-bottom: 0;
        padding-bottom: 0;
    }

    .departments-table td:last-child::before {
        display: block;
        margin-bottom: 6px;
    }

    .departments-table .dept-name,
    .departments-table .dept-slug,
    .departments-table .dept-description {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .departments-table .dept-description {
        max-width: none !important;
        white-space: normal !important;
        overflow: visible !important;
        text-overflow: clip !important;
    }

    .departments-table .dept-color,
    .departments-table .dept-slug,
    .departments-table .dept-description,
    .departments-table .dept-status {
        display: none;
    }

    .departments-table .actions {
        display: grid;
        grid-template-columns: 1fr;
        gap: 6px;
    }

    .departments-table .actions .btn,
    .departments-table .actions form {
        width: 100%;
    }

    .departments-table .actions .btn {
        justify-content: center;
        min-height: 38px;
        padding: 8px 10px;
    }

    .departments-table .dept-actions::before {
        display: none !important;
    }

    .departments-table .empty-state {
        grid-column: 1 / -1;
        text-align: center;
    }

    .departments-table .empty-state::before {
        display: none;
    }

    .department-modal-actions {
        flex-direction: column-reverse;
    }

    .department-modal-actions .btn {
        width: 100%;
    }
}
</style>

<div class="page-header">
    <h2>Departmanlar</h2>
    <button onclick="toggleModal('create-dept-modal')" class="btn btn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Yeni Departman
    </button>
</div>

<div class="card departments-card">
    <table class="departments-table">
        <thead>
            <tr>
                <th>Renk</th>
                <th>Ad</th>
                <th>Slug</th>
                <th>Açıklama</th>
                <th>Tip</th>
                <th>Durum</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($departments)): ?>
            <tr><td colspan="7" class="empty-state" data-label=""><p>Henüz departman yok.</p></td></tr>
        <?php else: ?>
            <?php foreach ($departments as $d): ?>
            <tr style="--dept-color: <?= View::escape($d['color']) ?>;">
                <td data-label="Renk" class="dept-color">
                    <span class="color-dot" style="background:<?= View::escape($d['color']) ?>;width:14px;height:14px;border-radius:50%;display:inline-block;"></span>
                </td>
                <td data-label="Ad" class="dept-name" style="font-weight:500"><?= View::escape($d['name']) ?></td>
                <td data-label="Slug" class="dept-slug" style="color:var(--muted);font-size:12px;font-family:monospace"><?= View::escape($d['slug']) ?></td>
                <td data-label="Açıklama" class="dept-description" style="color:var(--muted);font-size:12px;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    <?= View::escape($d['description'] ?? '—') ?>
                </td>
                <td data-label="Tip">
                    <?php if ($d['is_management']): ?>
                        <span class="badge badge-mgmt">Yönetim</span>
                    <?php else: ?>
                        <span class="badge badge-member">Standart</span>
                    <?php endif; ?>
                </td>
                <td data-label="Durum" class="dept-status">
                    <span class="badge <?= $d['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                        <?= $d['is_active'] ? 'Aktif' : 'Pasif' ?>
                    </span>
                </td>
                <td data-label="İşlemler" class="dept-actions">
                    <div class="actions">
                        <a href="<?= BASE_URL ?>/superadmin/departments/<?= $d['id'] ?>/edit" class="btn btn-outline btn-sm">Düzenle</a>
                        <form method="POST" action="<?= BASE_URL ?>/superadmin/departments/<?= $d['id'] ?>/delete"
                              onsubmit="return confirm('<?= View::escape($d['name']) ?> silinsin mi?')">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Create Modal -->
<div id="create-dept-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Yeni Departman</h3>
            <button class="modal-close" onclick="closeModal('create-dept-modal')">&times;</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/superadmin/departments">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Ad *</label>
                    <input type="text" name="name" class="form-control" placeholder="Sosyal Medya" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Renk</label>
                    <input type="color" name="color" class="form-control" value="#00BCD4" style="height:42px;padding:4px 8px;cursor:pointer">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Açıklama</label>
                <textarea name="description" class="form-control" placeholder="Departmanın işlevi..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">İkon (Feather adı)</label>
                <input type="text" name="icon" class="form-control" placeholder="share-2" value="folder">
                <p class="form-hint">feathericons.com'dan bir ikon adı girin</p>
            </div>

            <div class="form-group">
                <div class="check-group">
                    <input type="checkbox" id="is_management_c" name="is_management" value="1">
                    <label for="is_management_c">Yönetim Paneli (Özel Ayrıcalıklar)</label>
                </div>
            </div>

            <div class="department-modal-actions" style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <button type="button" onclick="closeModal('create-dept-modal')" class="btn btn-outline">İptal</button>
                <button type="submit" class="btn btn-primary">Oluştur</button>
            </div>
        </form>
    </div>
</div>

