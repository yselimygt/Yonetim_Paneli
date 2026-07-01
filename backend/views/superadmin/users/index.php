<?php $pageTitle = 'Kullanıcılar'; ?>

<div class="page-header">
    <h2>Kullanıcılar</h2>
    <button onclick="toggleModal('create-user-modal')" class="btn btn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Yeni Kullanıcı
    </button>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Ad</th>
                <th>E-posta</th>
                <th>Departmanlar</th>
                <th>Durum</th>
                <th>Kayıt</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($users)): ?>
            <tr><td colspan="6"><div class="empty-state"><p>Henüz kullanıcı yok.</p></div></td></tr>
        <?php else: ?>
            <?php foreach ($users as $u): ?>
            <tr>
                <td style="font-weight:500">
                    <?= View::escape($u['name']) ?>
                    <?php if ($u['is_super_admin']): ?>
                        <span class="badge" style="background:rgba(245,158,11,.15); color:#fbbf24; margin-left:4px; font-size:10px;">SÜPER ADMİN</span>
                    <?php endif; ?>
                </td>
                <td style="color:var(--muted);font-size:12px"><?= View::escape($u['email']) ?></td>
                <td style="color:var(--muted);font-size:12px"><?= View::escape($u['depts'] ?? '—') ?></td>
                <td>
                    <span class="badge <?= $u['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                        <?= $u['is_active'] ? 'Aktif' : 'Pasif' ?>
                    </span>
                </td>
                <td style="color:var(--muted);font-size:12px"><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
                <td>
                    <div class="actions">
                        <a href="<?= BASE_URL ?>/superadmin/users/<?= $u['id'] ?>/edit" class="btn btn-outline btn-sm">Düzenle</a>
                        <form method="POST" action="<?= BASE_URL ?>/superadmin/users/<?= $u['id'] ?>/delete"
                              onsubmit="return confirm('<?= View::escape($u['name']) ?> silinsin mi?')">
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

<!-- Create User Modal -->
<div id="create-user-modal" style="display:none;position:fixed;inset:0;background:var(--modal-overlay);z-index:200;align-items:center;justify-content:center;">
    <div style="background:var(--card);border:1px solid var(--border);border-radius:12px;padding:28px;width:520px;max-width:95vw;">
        <h3 style="font-size:15px;margin-bottom:20px;">Yeni Kullanıcı</h3>
        <form method="POST" action="<?= BASE_URL ?>/superadmin/users">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Ad Soyad *</label>
                    <input type="text" name="name" class="form-control" placeholder="Ali Veli" required>
                </div>
                <div class="form-group">
                    <label class="form-label">E-posta *</label>
                    <input type="email" name="email" class="form-control" placeholder="ali@domain.com" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Şifre *</label>
                    <input type="password" name="password" class="form-control" placeholder="En az 6 karakter" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Doğum Tarihi</label>
                    <input type="date" name="birth_date" class="form-control">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Departman (Opsiyonel)</label>
                    <select name="department_id" class="form-control">
                        <option value="">— Seç —</option>
                        <?php foreach ($departments as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= View::escape($d['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Rol</label>
                    <select name="role" class="form-control">
                        <option value="member">Üye</option>
                        <option value="director">Direktör</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-top: 12px; display: flex; flex-direction: column; gap: 8px;">
                <div class="check-group">
                    <input type="checkbox" id="is_super_admin_c" name="is_super_admin" value="1">
                    <label for="is_super_admin_c" style="color:var(--warning); font-weight:600;">Süper Admin Yetkisi Ver</label>
                </div>
                <div class="check-group">
                    <input type="checkbox" id="can_manage_passwords_c" name="can_manage_passwords" value="1">
                    <label for="can_manage_passwords_c" style="color:var(--accent); font-weight:600;">Şifreleri Düzenleyebilir/Silebilir</label>
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <button type="button" onclick="toggleModal('create-user-modal')" class="btn btn-outline">İptal</button>
                <button type="submit" class="btn btn-primary">Oluştur</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleModal(id) {
    const m = document.getElementById(id);
    m.style.display = m.style.display === 'flex' ? 'none' : 'flex';
}
</script>
