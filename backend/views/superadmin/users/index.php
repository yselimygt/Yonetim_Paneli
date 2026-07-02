<?php $pageTitle = 'Kullanıcılar'; ?>

<style>
@media (max-width: 700px) {
    .users-card {
        border: 0;
        background: transparent;
        box-shadow: none;
    }

    .users-table {
        display: table;
        overflow: visible;
    }

    .users-table thead {
        display: none;
    }

    .users-table,
    .users-table tbody,
    .users-table tr,
    .users-table td {
        width: 100%;
    }

    .users-table tbody {
        display: grid;
        gap: 12px;
    }

    .users-table tr {
        display: grid;
        gap: 10px;
        border: 1px solid var(--border);
        border-radius: 16px;
        background: var(--card);
        padding: 14px;
        box-shadow: 0 8px 28px rgba(0,0,0,0.14);
    }

    .users-table td {
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

    .users-table td::before {
        content: attr(data-label);
        flex: 0 0 104px;
        color: var(--muted);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .05em;
        text-align: left;
        text-transform: uppercase;
    }

    .users-table td:last-child {
        display: block;
        border-bottom: 0;
        padding-bottom: 0;
    }

    .users-table td:last-child::before {
        display: block;
        margin-bottom: 8px;
    }

    .users-table .user-name,
    .users-table .user-email,
    .users-table .user-departments {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .users-table .user-name {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 6px;
    }

    .users-table .actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .users-table .actions .btn,
    .users-table .actions form {
        width: 100%;
    }

    .users-table .actions .btn {
        justify-content: center;
    }

    .user-create-options .check-group {
        align-items: flex-start;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--input-bg);
        padding: 12px;
    }

    .user-modal-actions {
        flex-direction: column-reverse;
    }

    .user-modal-actions .btn {
        width: 100%;
    }
}
</style>

<div class="page-header">
    <h2>Kullanıcılar</h2>
    <button onclick="toggleModal('create-user-modal')" class="btn btn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Yeni Kullanıcı
    </button>
</div>

<div class="card users-card">
    <table class="users-table">
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
            <tr><td colspan="6" data-label=""><div class="empty-state"><p>Henüz kullanıcı yok.</p></div></td></tr>
        <?php else: ?>
            <?php foreach ($users as $u): ?>
            <tr>
                <td data-label="Ad" class="user-name" style="font-weight:500">
                    <?= View::escape($u['name']) ?>
                    <?php if ($u['is_super_admin']): ?>
                        <span class="badge" style="background:rgba(245,158,11,.15); color:#fbbf24; margin-left:4px; font-size:10px;">SÜPER ADMİN</span>
                    <?php endif; ?>
                </td>
                <td data-label="E-posta" class="user-email" style="color:var(--muted);font-size:12px"><?= View::escape($u['email']) ?></td>
                <td data-label="Departmanlar" class="user-departments" style="color:var(--muted);font-size:12px"><?= View::escape($u['depts'] ?? '—') ?></td>
                <td data-label="Durum">
                    <span class="badge <?= $u['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                        <?= $u['is_active'] ? 'Aktif' : 'Pasif' ?>
                    </span>
                </td>
                <td data-label="Kayıt" style="color:var(--muted);font-size:12px"><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
                <td data-label="İşlemler">
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
<div id="create-user-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Yeni Kullanıcı</h3>
            <button class="modal-close" onclick="closeModal('create-user-modal')">&times;</button>
        </div>
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
                        <option value="representative">Kampüs Temsilcisi</option>
                        <option value="director">Direktör</option>
                    </select>
                </div>
            </div>

            <div class="form-group user-create-options" style="margin-top: 12px; display: flex; flex-direction: column; gap: 8px;">
                <div class="check-group">
                    <input type="checkbox" id="is_super_admin_c" name="is_super_admin" value="1">
                    <label for="is_super_admin_c" style="color:var(--warning); font-weight:600;">Süper Admin Yetkisi Ver</label>
                </div>
                <div class="check-group">
                    <input type="checkbox" id="can_manage_passwords_c" name="can_manage_passwords" value="1">
                    <label for="can_manage_passwords_c" style="color:var(--accent); font-weight:600;">Şifreleri Düzenleyebilir/Silebilir</label>
                </div>
                <div class="check-group">
                    <input type="checkbox" id="is_representative_c" name="is_representative" value="1">
                    <label for="is_representative_c" style="color:#f87171; font-weight:600;">Kampüs Temsilcisi Rolü</label>
                </div>
                <div class="check-group">
                    <input type="checkbox" id="is_alumni_c" name="is_alumni" value="1">
                    <label for="is_alumni_c" style="color:#a78bfa; font-weight:600;">Mezun Rolü</label>
                </div>
            </div>

            <div class="user-modal-actions" style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <button type="button" onclick="closeModal('create-user-modal')" class="btn btn-outline">İptal</button>
                <button type="submit" class="btn btn-primary">Oluştur</button>
            </div>
        </form>
    </div>
</div>


