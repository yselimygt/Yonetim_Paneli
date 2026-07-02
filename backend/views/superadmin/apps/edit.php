<?php $pageTitle = 'Uygulama Düzenle'; ?>

<style>
@media (max-width: 700px) {
    .app-edit-header {
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .app-edit-title {
        min-width: 0;
        font-size: 15px;
        line-height: 1.25;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .app-edit-header .btn {
        flex: 0 0 auto;
        min-height: 34px;
        padding: 7px 10px;
        border-radius: 9px;
        font-size: 11px;
    }

    .app-edit-card {
        border: 0;
        border-radius: 0;
        background: transparent;
        max-width: none !important;
        box-shadow: none;
    }

    .app-edit-card .card-body {
        padding: 0;
    }

    .app-edit-card .form-row {
        gap: 0;
    }

    .app-edit-card .form-group {
        margin-bottom: 10px;
    }

    .app-edit-card .form-label {
        margin-bottom: 5px;
        font-size: 10px;
        letter-spacing: .04em;
        color: rgba(255,255,255,0.5);
    }

    .app-edit-card .form-control {
        min-height: 38px;
        border-radius: 9px;
        background: rgba(255,255,255,0.035);
        border-color: rgba(255,255,255,0.055);
        font-size: 12px;
    }

    .app-edit-card textarea.form-control {
        min-height: 92px;
    }

    .app-edit-options {
        padding-top: 0 !important;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .app-edit-options .check-group {
        align-items: center;
        min-height: 38px;
        margin: 0 !important;
        border: 1px solid rgba(255,255,255,0.055);
        border-radius: 9px;
        background: rgba(255,255,255,0.025);
        padding: 9px;
    }

    .app-edit-options .check-group label {
        font-size: 11px;
    }

    .app-edit-actions {
        flex-direction: column;
        gap: 8px !important;
        margin-top: 12px !important;
        padding-top: 12px;
        border-top: 1px solid rgba(255,255,255,0.055);
    }

    .app-edit-actions .btn {
        width: 100%;
        min-height: 40px;
        border-radius: 10px;
        font-size: 12px;
    }
}
</style>

<div class="page-header app-edit-header">
    <h2 class="app-edit-title">Uygulama Düzenle — <?= View::escape($app['name']) ?></h2>
    <a href="<?= BASE_URL ?>/superadmin/apps" class="btn btn-outline">← Geri</a>
</div>

<div class="card app-edit-card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>/superadmin/apps/<?= $app['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Ad *</label>
                    <input type="text" name="name" class="form-control" value="<?= View::escape($app['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Departman</label>
                    <input type="text" class="form-control" disabled
                        value="<?= View::escape(Database::getInstance()->query("SELECT name FROM departments WHERE id = {$app['department_id']}")->fetchColumn()) ?>"
                        style="opacity:.5">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Modül Tipi</label>
                    <input type="text" class="form-control" disabled
                        value="<?= View::escape(App::MODULE_TYPES[$app['module_type']] ?? $app['module_type']) ?>"
                        style="opacity:.5">
                </div>
                <div class="form-group">
                    <label class="form-label">İkon</label>
                    <input type="text" name="icon" class="form-control" value="<?= View::escape($app['icon'] ?? 'box') ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Açıklama</label>
                <textarea name="description" class="form-control"><?= View::escape($app['description'] ?? '') ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Sıralama</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= (int)$app['sort_order'] ?>" min="0">
                </div>
                <div class="form-group app-edit-options" style="padding-top:28px">
                    <div class="check-group" style="margin-bottom:10px">
                        <input type="checkbox" id="director_only_e" name="director_only" value="1" <?= $app['director_only'] ? 'checked' : '' ?>>
                        <label for="director_only_e">Sadece Direktör</label>
                    </div>
                    <div class="check-group">
                        <input type="checkbox" id="is_active_app" name="is_active" value="1" <?= $app['is_active'] ? 'checked' : '' ?>>
                        <label for="is_active_app">Aktif</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Konfigürasyon (JSON)</label>
                <textarea name="config" class="form-control" style="font-family:monospace; min-height: 120px;"><?= View::escape($app['config'] ?? '{}') ?></textarea>
            </div>

            <div class="app-edit-actions" style="display:flex;gap:10px;margin-top:16px;">
                <button type="submit" class="btn btn-primary">Güncelle</button>
                <a href="<?= BASE_URL ?>/superadmin/apps" class="btn btn-outline">İptal</a>
            </div>
        </form>
    </div>
</div>
