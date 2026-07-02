<?php $pageTitle = 'Dashboard'; ?>

<style>
@media (max-width: 700px) {
    .dashboard-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .dashboard-stats .stat-card {
        background: transparent;
        box-shadow: none;
    }

    .dashboard-table {
        display: table;
        overflow: visible;
    }

    .dashboard-table thead {
        display: none;
    }

    .dashboard-table,
    .dashboard-table tbody,
    .dashboard-table tr,
    .dashboard-table td {
        width: 100%;
    }

    .dashboard-table tbody {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 7px;
        padding: 8px;
    }

    .dashboard-users-table tbody {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .dashboard-table tr {
        display: grid;
        gap: 6px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--input-bg);
        padding: 8px;
        min-width: 0;
    }

    .dashboard-table td {
        display: block;
        max-width: none;
        border-bottom: 0;
        padding: 0;
        white-space: normal;
        overflow: visible;
        text-align: left;
        line-height: 1.35;
    }

    .dashboard-table td::before {
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

    .dashboard-table td:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .dashboard-table .dash-primary {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .dashboard-table .dash-primary::before {
        margin-bottom: 5px;
    }

    .dashboard-table .dashboard-empty-row {
        grid-column: 1 / -1;
    }

    .dashboard-table .dashboard-empty-cell {
        text-align: center;
    }

    .dashboard-table .dashboard-empty-cell::before {
        display: none;
    }
}
</style>

<div class="stats-grid dashboard-stats">
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
        <table class="dashboard-table dashboard-users-table">
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
                    <td data-label="Ad" class="dash-primary">
                        <span class="color-dot" style="background:<?= View::escape($d['color']) ?>"></span>
                        <?= View::escape($d['name']) ?>
                        <?php if ($d['is_management']): ?>
                            <span class="badge badge-mgmt" style="margin-left:4px;font-size:9px;">YÖN</span>
                        <?php endif; ?>
                    </td>
                    <td data-label="Üye" style="color:var(--muted)"><?= $d['member_count'] ?></td>
                    <td data-label="Durum">
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
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Ad</th>
                    <th>E-posta</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentUsers)): ?>
                <tr class="dashboard-empty-row"><td colspan="3" class="dashboard-empty-cell" data-label="" style="color:var(--muted);text-align:center;padding:24px;">Henüz kullanıcı yok</td></tr>
                <?php else: ?>
                <?php foreach ($recentUsers as $u): ?>
                <tr>
                    <td data-label="Ad" class="dash-primary"><?= View::escape($u['name']) ?></td>
                    <td data-label="E-posta" class="dash-primary" style="color:var(--muted);font-size:12px"><?= View::escape($u['email']) ?></td>
                    <td data-label="Durum">
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
