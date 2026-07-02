<?php $pageTitle = 'Dashboard'; ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="value"><?= $deptCount ?></div>
        <div class="label">Departman</div>
    </div>
    <div class="stat-card">
        <div class="value"><?= $userCount ?></div>
        <div class="label">Kullanıcı</div>
    </div>
    <div class="stat-card">
        <div class="value"><?= $appCount ?></div>
        <div class="label">Uygulama</div>
    </div>
    <div class="stat-card">
        <div class="value"><?= $assignCount ?></div>
        <div class="label">Atama</div>
    </div>
</div>

<div class="dashboard-panels-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

    <!-- Son Departmanlar -->
    <div class="card">
        <div class="card-header">
            Departmanlar
            <a href="<?= BASE_URL ?>/superadmin/departments" class="btn btn-outline btn-sm">Tümü</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Ad</th>
                    <th>Üye</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departments as $d): ?>
                <tr>
                    <td>
                        <span class="color-dot" style="background:<?= View::escape($d['color']) ?>"></span>
                        <?= View::escape($d['name']) ?>
                        <?php if ($d['is_management']): ?>
                            <span class="badge badge-mgmt" style="margin-left:4px;font-size:9px;">YÖN</span>
                        <?php endif; ?>
                    </td>
                    <td style="color:var(--muted)"><?= $d['member_count'] ?></td>
                    <td>
                        <span class="badge <?= $d['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                            <?= $d['is_active'] ? 'Aktif' : 'Pasif' ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Son Kullanıcılar -->
    <div class="card">
        <div class="card-header">
            Son Kullanıcılar
            <a href="<?= BASE_URL ?>/superadmin/users" class="btn btn-outline btn-sm">Tümü</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Ad</th>
                    <th>E-posta</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentUsers)): ?>
                <tr><td colspan="3" style="color:var(--muted);text-align:center;padding:24px;">Henüz kullanıcı yok</td></tr>
                <?php else: ?>
                <?php foreach ($recentUsers as $u): ?>
                <tr>
                    <td><?= View::escape($u['name']) ?></td>
                    <td style="color:var(--muted);font-size:12px"><?= View::escape($u['email']) ?></td>
                    <td>
                        <span class="badge <?= $u['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                            <?= $u['is_active'] ? 'Aktif' : 'Pasif' ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
