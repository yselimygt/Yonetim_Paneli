<?php $pageTitle = 'Kullanıcı Düzenle'; ?>

<style>
@media (max-width: 800px) {
    .user-edit-title {
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .user-edit-layout {
        display: grid !important;
        grid-template-columns: 1fr !important;
        gap: 16px !important;
    }

    .user-edit-options {
        display: grid;
        gap: 10px;
    }

    .user-edit-options .check-group {
        align-items: flex-start;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--input-bg);
        padding: 12px;
        margin-top: 0 !important;
    }

    .user-update-button,
    .user-assign-button {
        width: 100%;
    }

    .user-assignments-table {
        display: table;
        overflow: visible;
    }

    .user-assignments-table thead {
        display: none;
    }

    .user-assignments-table,
    .user-assignments-table tbody,
    .user-assignments-table tr,
    .user-assignments-table td {
        width: 100%;
    }

    .user-assignments-table tbody {
        display: grid;
        gap: 10px;
        padding: 12px;
    }

    .user-assignments-table tr {
        display: grid;
        gap: 10px;
        border: 1px solid var(--border);
        border-radius: 14px;
        background: var(--input-bg);
        padding: 12px;
    }

    .user-assignments-table td {
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

    .user-assignments-table td::before {
        content: attr(data-label);
        flex: 0 0 104px;
        color: var(--muted);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .05em;
        text-align: left;
        text-transform: uppercase;
    }

    .user-assignments-table td:last-child {
        display: block;
        border-bottom: 0;
        padding-bottom: 0;
    }

    .user-assignments-table td:last-child::before {
        display: block;
        margin-bottom: 8px;
    }

    .user-assignments-table td form,
    .user-assignments-table td .btn {
        width: 100%;
    }

    .user-assignments-table td .btn {
        justify-content: center;
    }
}
</style>

<div class="page-header">
    <h2 class="user-edit-title"><?= View::escape($user['name']) ?> — Düzenle</h2>
    <a href="<?= BASE_URL ?>/superadmin/users" class="btn btn-outline">← Geri</a>
</div>

<div class="user-edit-layout" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">

    <!-- Sol: Kullanıcı Bilgileri -->
    <div class="card">
        <div class="card-header">Kullanıcı Bilgileri</div>
        <div class="card-body">
            <form method="POST" action="<?= BASE_URL ?>/superadmin/users/<?= $user['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                <div class="form-group">
                    <label class="form-label">Ad Soyad *</label>
                    <input type="text" name="name" class="form-control" value="<?= View::escape($user['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">E-posta</label>
                    <input type="text" class="form-control" value="<?= View::escape($user['email']) ?>" disabled style="opacity:.5">
                    <p class="form-hint">E-posta değiştirilemez.</p>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Yeni Şifre (Opsiyonel)</label>
                        <input type="password" name="password" class="form-control" placeholder="Değiştirmek için girin">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Doğum Tarihi</label>
                        <input type="date" name="birth_date" class="form-control" value="<?= View::escape($user['birth_date'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group user-edit-options">
                    <div class="check-group">
                        <input type="checkbox" id="is_active_u" name="is_active" value="1" <?= $user['is_active'] ? 'checked' : '' ?>>
                        <label for="is_active_u">Aktif</label>
                    </div>
                    <div class="check-group" style="margin-top: 10px;">
                        <input type="checkbox" id="is_super_admin_u" name="is_super_admin" value="1" <?= $user['is_super_admin'] ? 'checked' : '' ?>>
                        <label for="is_super_admin_u" style="color:var(--warning); font-weight:600;">Süper Admin Yetkisi Ver</label>
                    </div>
                    <div class="check-group" style="margin-top: 10px;">
                        <input type="checkbox" id="can_manage_passwords_u" name="can_manage_passwords" value="1" <?= !empty($user['can_manage_passwords']) ? 'checked' : '' ?>>
                        <label for="can_manage_passwords_u" style="color:var(--accent); font-weight:600;">Şifreleri Düzenleyebilir/Silebilir</label>
                    </div>
                    <div class="check-group" style="margin-top: 10px;">
                        <input type="checkbox" id="is_representative_u" name="is_representative" value="1" <?= !empty($user['is_representative']) ? 'checked' : '' ?>>
                        <label for="is_representative_u" style="color:#f87171; font-weight:600;">Kampüs Temsilcisi Rolü</label>
                    </div>
                    <div class="check-group" style="margin-top: 10px;">
                        <input type="checkbox" id="is_alumni_u" name="is_alumni" value="1" <?= !empty($user['is_alumni']) ? 'checked' : '' ?>>
                        <label for="is_alumni_u" style="color:#a78bfa; font-weight:600;">Mezun Rolü</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary user-update-button">Güncelle</button>
            </form>
        </div>
    </div>

            </form>
        </div>
    </div>

    <!-- Sağ: Departman Atamaları -->
    <div>
        <!-- Mevcut atamalar -->
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">Mevcut Departmanlar</div>
            <table class="user-assignments-table">
                <thead><tr><th>Departman</th><th>Rol</th><th></th></tr></thead>
                <tbody>
                <?php if (empty($userDepts)): ?>
                    <tr><td colspan="3" data-label="" style="color:var(--muted);text-align:center;padding:20px;">Atama yok</td></tr>
                <?php else: ?>
                    <?php foreach ($userDepts as $ud): ?>
                    <tr>
                        <td data-label="Departman"><?= View::escape($ud['dept_name']) ?></td>
                        <td data-label="Rol">
                            <?php 
                                $badgeClass = 'badge-member';
                                $roleName = 'Üye';
                                if ($ud['role'] === 'director') {
                                    $badgeClass = 'badge-director';
                                    $roleName = 'Direktör';
                                } elseif ($ud['role'] === 'representative') {
                                    $badgeClass = 'badge-representative';
                                    $roleName = 'Kampüs Temsilcisi';
                                } elseif ($ud['role'] === 'president') {
                                    $badgeClass = 'badge-president';
                                    $roleName = 'Başkan';
                                } elseif ($ud['role'] === 'vice_president') {
                                    $badgeClass = 'badge-vice-president';
                                    $roleName = 'Başkan Yardımcısı';
                                }
                            ?>
                            <span class="badge <?= $badgeClass ?>" <?= $ud['role'] === 'representative' ? 'style="background:rgba(239,68,68,0.15); color:#f87171;"' : '' ?>>
                                <?= $roleName ?>
                            </span>
                        </td>
                        <td data-label="İşlem">
                            <form method="POST" action="<?= BASE_URL ?>/superadmin/users/<?= $user['id'] ?>/assign">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                <input type="hidden" name="department_id" value="<?= $ud['department_id'] ?>">
                                <input type="hidden" name="action" value="remove">
                                <button type="submit" class="btn btn-danger btn-sm">Çıkar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Yeni atama -->
        <div class="card">
            <div class="card-header">Departman Ata</div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/superadmin/users/<?= $user['id'] ?>/assign">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <input type="hidden" name="action" value="assign">

                    <div class="form-group">
                        <label class="form-label">Departman</label>
                        <select name="department_id" class="form-control" id="deptSelect" required>
                            <option value="" data-management="0">— Seç —</option>
                            <?php foreach ($departments as $d): ?>
                            <?php $already = in_array($d['id'], $assignedIds); ?>
                            <option value="<?= $d['id'] ?>" data-management="<?= $d['is_management'] ?? 0 ?>" <?= $already ? 'style="color:#555"' : '' ?>>
                                <?= View::escape($d['name']) ?><?= $already ? ' ✓' : '' ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rol</label>
                        <select name="role" class="form-control" id="roleSelect">
                            <option value="member" class="role-normal">Üye</option>
                            <option value="representative" class="role-normal">Kampüs Temsilcisi</option>
                            <option value="director" class="role-normal">Direktör</option>
                            <option value="president" class="role-mgmt" style="display:none;" disabled>Başkan</option>
                            <option value="vice_president" class="role-mgmt" style="display:none;" disabled>Başkan Yardımcısı</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary user-assign-button" style="width:100%">Ata / Güncelle</button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var deptSelect = document.getElementById('deptSelect');
    var roleSelect = document.getElementById('roleSelect');
    
    if (deptSelect && roleSelect) {
        deptSelect.addEventListener('change', function() {
            var selected = this.options[this.selectedIndex];
            var isMgmt = selected.getAttribute('data-management') === '1';
            
            var normalRoles = roleSelect.querySelectorAll('.role-normal');
            var mgmtRoles = roleSelect.querySelectorAll('.role-mgmt');
            
            if (isMgmt) {
                normalRoles.forEach(function(el) { el.style.display = 'none'; el.disabled = true; });
                mgmtRoles.forEach(function(el) { el.style.display = 'block'; el.disabled = false; });
                roleSelect.value = 'president';
            } else {
                normalRoles.forEach(function(el) { el.style.display = 'block'; el.disabled = false; });
                mgmtRoles.forEach(function(el) { el.style.display = 'none'; el.disabled = true; });
                roleSelect.value = 'member';
            }
        });
    }
});
</script>
