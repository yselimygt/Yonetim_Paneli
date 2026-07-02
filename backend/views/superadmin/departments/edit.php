<?php $pageTitle = 'Departman Düzenle'; ?>

<style>
@media (max-width: 700px) {
    .department-edit-card {
        max-width: none !important;
    }

    .department-edit-title {
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .department-edit-actions {
        flex-direction: column;
    }

    .department-edit-actions .btn {
        width: 100%;
    }

    .department-edit-checks {
        display: grid;
        gap: 10px;
    }

    .department-edit-checks .form-group {
        margin-bottom: 0;
    }

    .department-edit-checks .check-group {
        align-items: flex-start;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--input-bg);
        padding: 12px;
    }

    .department-edit-fields {
        gap: 0;
    }
}
</style>

<div class="page-header">
    <h2 class="department-edit-title">Departman Düzenle — <?= View::escape($department['name']) ?></h2>
    <a href="<?= BASE_URL ?>/superadmin/departments" class="btn btn-outline">← Geri</a>
</div>

<div class="card department-edit-card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>/superadmin/departments/<?= $department['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="form-row department-edit-fields">
                <div class="form-group">
                    <label class="form-label">Ad *</label>
                    <input type="text" name="name" class="form-control"
                        value="<?= View::escape($department['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Renk</label>
                    <input type="color" name="color" class="form-control"
                        value="<?= View::escape($department['color']) ?>"
                        style="height:42px;padding:4px 8px;cursor:pointer">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Açıklama</label>
                <textarea name="description" class="form-control"><?= View::escape($department['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">İkon</label>
                <input type="text" name="icon" class="form-control"
                    value="<?= View::escape($department['icon'] ?? 'folder') ?>">
                <p class="form-hint">feathericons.com'dan bir ikon adı girin</p>
            </div>

            <div class="form-row department-edit-checks">
                <div class="form-group">
                    <div class="check-group">
                        <input type="checkbox" id="is_management_e" name="is_management" value="1"
                            <?= $department['is_management'] ? 'checked' : '' ?>>
                        <label for="is_management_e">Yönetim Paneli Ayrıcalığı</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="check-group">
                        <input type="checkbox" id="is_active_e" name="is_active" value="1"
                            <?= $department['is_active'] ? 'checked' : '' ?>>
                        <label for="is_active_e">Aktif</label>
                    </div>
                </div>
            </div>

            <div class="department-edit-actions" style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" class="btn btn-primary">Güncelle</button>
                <a href="<?= BASE_URL ?>/superadmin/departments" class="btn btn-outline">İptal</a>
            </div>
        </form>
    </div>
</div>
