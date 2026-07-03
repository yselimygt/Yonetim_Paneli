<?php $pageTitle = 'Kullanıcılar'; ?>

<style>
.user-dept-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
}

.user-dept-pill {
    display: inline-flex;
    max-width: 100%;
    min-height: 22px;
    align-items: center;
    border: 1px solid color-mix(in srgb, var(--pill-color, var(--accent)) 42%, var(--border));
    border-radius: 999px;
    background: color-mix(in srgb, var(--pill-color, var(--accent)) 16%, transparent);
    color: color-mix(in srgb, var(--pill-color, var(--accent)) 76%, var(--text));
    font-size: 10px;
    font-weight: 800;
    line-height: 1.2;
    padding: 3px 7px;
    overflow-wrap: anywhere;
}

.user-assignment-list {
    display: grid;
    gap: 8px;
    margin-bottom: 14px;
}

.user-assignment-card {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 10px;
    align-items: center;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--input-bg);
    padding: 10px;
}

.user-assignment-name {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    align-items: center;
    min-width: 0;
}

.user-assignment-dot {
    width: 9px;
    height: 9px;
    flex: 0 0 auto;
    border-radius: 999px;
}

.user-assign-form {
    border-top: 1px solid var(--border);
    padding-top: 14px;
}

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
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 7px;
        padding: 8px;
    }

    .users-table tr {
        display: grid;
        gap: 7px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--input-bg);
        padding: 8px;
        box-shadow: none;
        min-width: 0;
    }

    .users-table td {
        display: block;
        max-width: none;
        border-bottom: 0;
        padding: 0;
        white-space: normal;
        overflow: visible;
        text-align: left;
        line-height: 1.35;
    }

    .users-table td::before {
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

    .users-table td:last-child {
        display: block;
        border-bottom: 0;
        padding-bottom: 0;
    }

    .users-table td:last-child::before {
        display: block;
        margin-bottom: 8px;
    }

    .users-table .user-email,
    .users-table .user-status,
    .users-table .user-created {
        display: none;
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
        justify-content: flex-start;
        gap: 6px;
        font-weight: 700 !important;
    }

    .users-table .user-name::before,
    .users-table .user-departments::before,
    .users-table .user-actions::before {
        display: none;
    }

    .users-table .actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
    }

    .users-table .actions .btn,
    .users-table .actions form {
        width: 100%;
    }

    .users-table .actions .btn {
        justify-content: center;
        min-height: 28px;
        padding: 0 8px;
        border-radius: 8px;
        font-size: 10px;
    }

    .user-create-options .check-group {
        align-items: flex-start;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--input-bg);
        padding: 12px;
    }

    .user-edit-options {
        display: grid;
        gap: 8px;
    }

    .user-edit-options .check-group {
        align-items: flex-start;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--input-bg);
        padding: 12px;
        margin-top: 0 !important;
    }

    .user-modal-actions {
        flex-direction: row !important;
        flex-wrap: nowrap;
    }

    .user-modal-actions .btn {
        width: auto;
        flex: 1 1 0;
        min-width: 0;
        min-height: 34px;
        justify-content: center;
        padding: 0 8px;
        font-size: 11px;
        white-space: normal;
    }

    .user-assignment-card {
        grid-template-columns: 1fr;
    }

    .user-assignment-card .btn {
        width: 100%;
        justify-content: center;
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
            <?php
                $deptPills = [];
                if (!empty($u['departments_meta'])) {
                    foreach (explode('||', $u['departments_meta']) as $deptMeta) {
                        [$deptName, $deptColor] = array_pad(explode('::', $deptMeta, 2), 2, '#00BCD4');
                        $deptPills[] = ['name' => $deptName, 'color' => $deptColor ?: '#00BCD4'];
                    }
                }
            ?>
            <tr>
                <td data-label="Ad" class="user-name" style="font-weight:500">
                    <?= View::escape($u['name']) ?>
                    <?php if ($u['is_super_admin']): ?>
                        <span class="badge" style="background:rgba(245,158,11,.15); color:#fbbf24; margin-left:4px; font-size:10px;">SÜPER ADMİN</span>
                    <?php endif; ?>
                </td>
                <td data-label="E-posta" class="user-email" style="color:var(--muted);font-size:12px"><?= View::escape($u['email']) ?></td>
                <td data-label="Departmanlar" class="user-departments" style="color:var(--muted);font-size:12px">
                    <div class="user-dept-pills">
                        <?php if (empty($deptPills)): ?>
                            <span class="user-dept-pill" style="--pill-color:#6b7280;">Departmansız</span>
                        <?php else: ?>
                            <?php foreach ($deptPills as $dept): ?>
                                <span class="user-dept-pill" style="--pill-color:<?= View::escape($dept['color']) ?>;"><?= View::escape($dept['name']) ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </td>
                <td data-label="Durum" class="user-status">
                    <span class="badge <?= $u['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                        <?= $u['is_active'] ? 'Aktif' : 'Pasif' ?>
                    </span>
                </td>
                <td data-label="Kayıt" class="user-created" style="color:var(--muted);font-size:12px"><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
                <td data-label="İşlemler" class="user-actions">
                    <div class="actions">
                        <button type="button" onclick="toggleModal('edit-user-modal-<?= (int) $u['id'] ?>')" class="btn btn-outline btn-sm">Düzenle</button>
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

<?php if (!empty($users)): ?>
    <?php foreach ($users as $u): ?>
    <div id="edit-user-modal-<?= (int) $u['id'] ?>" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><?= View::escape($u['name']) ?> — Düzenle</h3>
                <button class="modal-close" onclick="closeModal('edit-user-modal-<?= (int) $u['id'] ?>')">&times;</button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/superadmin/users/<?= (int) $u['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                <div class="form-group">
                    <label class="form-label" for="user_name_<?= (int) $u['id'] ?>">Ad Soyad *</label>
                    <input type="text" id="user_name_<?= (int) $u['id'] ?>" name="name" class="form-control" value="<?= View::escape($u['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">E-posta</label>
                    <input type="text" class="form-control" value="<?= View::escape($u['email']) ?>" disabled style="opacity:.5">
                    <p class="form-hint">E-posta değiştirilemez.</p>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="user_password_<?= (int) $u['id'] ?>">Yeni Şifre (Opsiyonel)</label>
                        <input type="password" id="user_password_<?= (int) $u['id'] ?>" name="password" class="form-control" placeholder="Değiştirmek için girin">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="user_birth_date_<?= (int) $u['id'] ?>">Doğum Tarihi</label>
                        <input type="date" id="user_birth_date_<?= (int) $u['id'] ?>" name="birth_date" class="form-control" value="<?= View::escape($u['birth_date'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group user-edit-options">
                    <div class="check-group">
                        <input type="checkbox" id="is_active_<?= (int) $u['id'] ?>" name="is_active" value="1" <?= $u['is_active'] ? 'checked' : '' ?>>
                        <label for="is_active_<?= (int) $u['id'] ?>">Aktif</label>
                    </div>
                    <div class="check-group">
                        <input type="checkbox" id="is_super_admin_<?= (int) $u['id'] ?>" name="is_super_admin" value="1" <?= $u['is_super_admin'] ? 'checked' : '' ?>>
                        <label for="is_super_admin_<?= (int) $u['id'] ?>" style="color:var(--warning); font-weight:600;">Süper Admin Yetkisi Ver</label>
                    </div>
                    <div class="check-group">
                        <input type="checkbox" id="can_manage_passwords_<?= (int) $u['id'] ?>" name="can_manage_passwords" value="1" <?= !empty($u['can_manage_passwords']) ? 'checked' : '' ?>>
                        <label for="can_manage_passwords_<?= (int) $u['id'] ?>" style="color:var(--accent); font-weight:600;">Şifreleri Düzenleyebilir/Silebilir</label>
                    </div>
                    <div class="check-group">
                        <input type="checkbox" id="is_representative_<?= (int) $u['id'] ?>" name="is_representative" value="1" <?= !empty($u['is_representative']) ? 'checked' : '' ?>>
                        <label for="is_representative_<?= (int) $u['id'] ?>" style="color:#f87171; font-weight:600;">Kampüs Temsilcisi Rolü</label>
                    </div>
                    <div class="check-group">
                        <input type="checkbox" id="is_alumni_<?= (int) $u['id'] ?>" name="is_alumni" value="1" <?= !empty($u['is_alumni']) ? 'checked' : '' ?>>
                        <label for="is_alumni_<?= (int) $u['id'] ?>" style="color:#a78bfa; font-weight:600;">Mezun Rolü</label>
                    </div>
                </div>

                <div class="user-modal-actions" style="display:flex;gap:10px;justify-content:flex-end;margin-top:12px;">
                    <button type="button" class="btn btn-outline" onclick="switchUserModal('edit-user-modal-<?= (int) $u['id'] ?>', 'assign-user-modal-<?= (int) $u['id'] ?>')">Departman Atamaları</button>
                    <button type="button" onclick="closeModal('edit-user-modal-<?= (int) $u['id'] ?>')" class="btn btn-outline">İptal</button>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </div>
            </form>
        </div>
    </div>

    <?php
        $assignedDepts = $userAssignments[(int) $u['id']] ?? [];
        $assignedIds = array_map('intval', array_column($assignedDepts, 'department_id'));
    ?>
    <div id="assign-user-modal-<?= (int) $u['id'] ?>" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><?= View::escape($u['name']) ?> — Departman Atamaları</h3>
                <button class="modal-close" onclick="closeModal('assign-user-modal-<?= (int) $u['id'] ?>')">&times;</button>
            </div>

            <div class="user-assignment-list">
                <?php if (empty($assignedDepts)): ?>
                    <div class="empty-state" style="padding:14px;">
                        <p>Atama yok.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($assignedDepts as $ud): ?>
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
                    <div class="user-assignment-card">
                        <div class="user-assignment-name">
                            <span class="user-assignment-dot" style="background:<?= View::escape($ud['dept_color'] ?? '#00BCD4') ?>;"></span>
                            <strong><?= View::escape($ud['dept_name']) ?></strong>
                            <span class="badge <?= $badgeClass ?>" <?= $ud['role'] === 'representative' ? 'style="background:rgba(239,68,68,0.15); color:#f87171;"' : '' ?>>
                                <?= $roleName ?>
                            </span>
                        </div>
                        <form method="POST" action="<?= BASE_URL ?>/superadmin/users/<?= (int) $u['id'] ?>/assign">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="department_id" value="<?= (int) $ud['department_id'] ?>">
                            <input type="hidden" name="action" value="remove">
                            <button type="submit" class="btn btn-danger btn-sm">Çıkar</button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <form method="POST" action="<?= BASE_URL ?>/superadmin/users/<?= (int) $u['id'] ?>/assign" class="user-assign-form">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="assign">

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="assign_department_<?= (int) $u['id'] ?>">Departman</label>
                        <select name="department_id" id="assign_department_<?= (int) $u['id'] ?>" class="form-control assign-dept-select" required>
                            <option value="" data-management="0">— Seç —</option>
                            <?php foreach ($departments as $d): ?>
                            <?php $already = in_array((int) $d['id'], $assignedIds, true); ?>
                            <option value="<?= (int) $d['id'] ?>" data-management="<?= $d['is_management'] ?? 0 ?>" <?= $already ? 'style="color:#555"' : '' ?>>
                                <?= View::escape($d['name']) ?><?= $already ? ' ✓' : '' ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="assign_role_<?= (int) $u['id'] ?>">Rol</label>
                        <select name="role" id="assign_role_<?= (int) $u['id'] ?>" class="form-control assign-role-select">
                            <option value="member" class="role-normal">Üye</option>
                            <option value="representative" class="role-normal">Kampüs Temsilcisi</option>
                            <option value="director" class="role-normal">Direktör</option>
                            <option value="president" class="role-mgmt" style="display:none;" disabled>Başkan</option>
                            <option value="vice_president" class="role-mgmt" style="display:none;" disabled>Başkan Yardımcısı</option>
                        </select>
                    </div>
                </div>

                <div class="user-modal-actions" style="display:flex;gap:10px;justify-content:flex-end;margin-top:12px;">
                    <button type="button" class="btn btn-outline" onclick="switchUserModal('assign-user-modal-<?= (int) $u['id'] ?>', 'edit-user-modal-<?= (int) $u['id'] ?>')">Bilgilere Dön</button>
                    <button type="button" onclick="closeModal('assign-user-modal-<?= (int) $u['id'] ?>')" class="btn btn-outline">İptal</button>
                    <button type="submit" class="btn btn-primary">Ata / Güncelle</button>
                </div>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

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
                    <select name="department_id" id="create_dept_select" class="form-control">
                        <option value="" data-management="0">— Seç —</option>
                        <?php foreach ($departments as $d): ?>
                        <option value="<?= $d['id'] ?>" data-management="<?= $d['is_management'] ?? 0 ?>"><?= View::escape($d['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Rol</label>
                    <select name="role" id="create_role_select" class="form-control">
                        <option value="member" class="role-normal">Üye</option>
                        <option value="representative" class="role-normal">Kampüs Temsilcisi</option>
                        <option value="director" class="role-normal">Direktör</option>
                        <option value="president" class="role-mgmt" style="display:none;" disabled>Başkan</option>
                        <option value="vice_president" class="role-mgmt" style="display:none;" disabled>Başkan Yardımcısı</option>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    var createDeptSelect = document.getElementById('create_dept_select');
    var createRoleSelect = document.getElementById('create_role_select');
    
    if (createDeptSelect && createRoleSelect) {
        createDeptSelect.addEventListener('change', function() {
            var selected = this.options[this.selectedIndex];
            var isMgmt = selected.getAttribute('data-management') === '1';
            
            var normalRoles = createRoleSelect.querySelectorAll('.role-normal');
            var mgmtRoles = createRoleSelect.querySelectorAll('.role-mgmt');
            
            if (isMgmt) {
                normalRoles.forEach(function(el) { el.style.display = 'none'; el.disabled = true; });
                mgmtRoles.forEach(function(el) { el.style.display = 'block'; el.disabled = false; });
                createRoleSelect.value = 'president';
            } else {
                normalRoles.forEach(function(el) { el.style.display = 'block'; el.disabled = false; });
                mgmtRoles.forEach(function(el) { el.style.display = 'none'; el.disabled = true; });
                createRoleSelect.value = 'member';
            }
        });
    }

    document.querySelectorAll('.assign-dept-select').forEach(function(select) {
        select.addEventListener('change', function() {
            var form = this.closest('form');
            var roleSelect = form.querySelector('.assign-role-select');
            
            if (roleSelect) {
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
            }
        });
    });
});

function switchUserModal(fromId, toId) {
    closeModal(fromId);
    setTimeout(function() {
        openModal(toId);
    }, 320);
}
</script>


