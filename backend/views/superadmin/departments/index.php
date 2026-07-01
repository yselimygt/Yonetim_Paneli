<?php $pageTitle = 'Departmanlar'; ?>

<div class="page-header">
    <h2>Departmanlar</h2>
    <button onclick="toggleModal('create-dept-modal')" class="btn btn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Yeni Departman
    </button>
</div>

<div class="card">
    <table>
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
            <tr><td colspan="7" class="empty-state"><p>Henüz departman yok.</p></td></tr>
        <?php else: ?>
            <?php foreach ($departments as $d): ?>
            <tr>
                <td>
                    <span class="color-dot" style="background:<?= View::escape($d['color']) ?>;width:14px;height:14px;border-radius:50%;display:inline-block;"></span>
                </td>
                <td style="font-weight:500"><?= View::escape($d['name']) ?></td>
                <td style="color:var(--muted);font-size:12px;font-family:monospace"><?= View::escape($d['slug']) ?></td>
                <td style="color:var(--muted);font-size:12px;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    <?= View::escape($d['description'] ?? '—') ?>
                </td>
                <td>
                    <?php if ($d['is_management']): ?>
                        <span class="badge badge-mgmt">Yönetim</span>
                    <?php else: ?>
                        <span class="badge badge-member">Standart</span>
                    <?php endif; ?>
                </td>
                <td>
                    <span class="badge <?= $d['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                        <?= $d['is_active'] ? 'Aktif' : 'Pasif' ?>
                    </span>
                </td>
                <td>
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

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <button type="button" onclick="closeModal('create-dept-modal')" class="btn btn-outline">İptal</button>
                <button type="submit" class="btn btn-primary">Oluştur</button>
            </div>
        </form>
    </div>
</div>

