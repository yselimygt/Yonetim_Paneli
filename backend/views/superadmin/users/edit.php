<?php $pageTitle = 'Kullanıcı Düzenle'; ?>

<div class="page-header">
    <h2><?= View::escape($user['name']) ?> — Düzenle</h2>
    <a href="<?= BASE_URL ?>/superadmin/users" class="btn btn-outline">← Geri</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">

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

                <div class="form-group">
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

                <button type="submit" class="btn btn-primary">Güncelle</button>
            </form>
        </div>
    </div>

    <!-- Sağ: Departman Atamaları -->
    <div>
        <!-- Mevcut atamalar -->
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">Mevcut Departmanlar</div>
            <table>
                <thead><tr><th>Departman</th><th>Rol</th><th></th></tr></thead>
                <tbody>
                <?php if (empty($userDepts)): ?>
                    <tr><td colspan="3" style="color:var(--muted);text-align:center;padding:20px;">Atama yok</td></tr>
                <?php else: ?>
                    <?php foreach ($userDepts as $ud): ?>
                    <tr>
                        <td><?= View::escape($ud['dept_name']) ?></td>
                        <td>
                            <?php 
                                $badgeClass = 'badge-member';
                                $roleName = 'Üye';
                                if ($ud['role'] === 'director') {
                                    $badgeClass = 'badge-director';
                                    $roleName = 'Direktör';
                                } elseif ($ud['role'] === 'representative') {
                                    $badgeClass = 'badge-representative';
                                    $roleName = 'Kampüs Temsilcisi';
                                }
                            ?>
                            <span class="badge <?= $badgeClass ?>" <?= $ud['role'] === 'representative' ? 'style="background:rgba(239,68,68,0.15); color:#f87171;"' : '' ?>>
                                <?= $roleName ?>
                            </span>
                        </td>
                        <td>
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
                        <select name="department_id" class="form-control" required>
                            <option value="">— Seç —</option>
                            <?php foreach ($departments as $d): ?>
                            <?php $already = in_array($d['id'], $assignedIds); ?>
                            <option value="<?= $d['id'] ?>" <?= $already ? 'style="color:#555"' : '' ?>>
                                <?= View::escape($d['name']) ?><?= $already ? ' ✓' : '' ?>
                            </option>
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

                    <button type="submit" class="btn btn-primary" style="width:100%">Ata / Güncelle</button>
                </form>
            </div>
        </div>
    </div>

</div>
