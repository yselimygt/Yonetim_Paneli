<?php $pageTitle = 'Uygulama Düzenle'; ?>

<div class="page-header">
    <h2>Uygulama Düzenle — <?= View::escape($app['name']) ?></h2>
    <a href="<?= BASE_URL ?>/superadmin/apps" class="btn btn-outline">← Geri</a>
</div>

<div class="card" style="max-width:600px;">
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
                <div class="form-group" style="padding-top:28px">
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

            <div style="display:flex;gap:10px;margin-top:16px;">
                <button type="submit" class="btn btn-primary">Güncelle</button>
                <a href="<?= BASE_URL ?>/superadmin/apps" class="btn btn-outline">İptal</a>
            </div>
        </form>
    </div>
</div>
