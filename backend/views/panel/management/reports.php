<?php $pageTitle = 'Departmanlar Arası Rapor İzleme'; $activeSection = 'reports'; ?>

<style>
@media (max-width: 900px) {
    .mgmt-reports-header {
        align-items: flex-start;
        gap: 4px;
        margin-bottom: 14px;
    }

    .mgmt-reports-header h2 {
        font-size: 18px;
        line-height: 1.2;
        margin: 0;
    }

    .mgmt-reports-grid {
        gap: 12px !important;
    }

    .mgmt-report-card {
        border-radius: 12px;
        overflow: hidden;
    }

    .mgmt-report-card .card-header {
        padding: 12px 14px !important;
        gap: 6px;
        font-size: 14px;
    }

    .mgmt-report-card .card-header > div {
        gap: 6px !important;
    }

    .mgmt-report-table,
    .mgmt-report-table tbody,
    .mgmt-report-table tr,
    .mgmt-report-table td {
        display: block;
        width: 100%;
    }

    .mgmt-report-table thead {
        display: none;
    }

    .mgmt-report-table {
        padding: 10px;
    }

    .mgmt-report-table tr.mgmt-report-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 4px 10px;
        padding: 11px 12px;
        margin-bottom: 9px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--card-alt);
    }

    .mgmt-report-table tr.mgmt-report-row:last-child {
        margin-bottom: 0;
    }

    .mgmt-report-table td {
        padding: 0 !important;
        border: 0 !important;
        background: transparent !important;
    }

    .mgmt-report-table td[data-label="Üye"] {
        grid-column: 1;
        grid-row: 1;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .mgmt-report-table td[data-label="Rol"] {
        grid-column: 2;
        grid-row: 1;
        text-align: right;
    }

    .mgmt-report-table td[data-label="E-posta"] {
        grid-column: 1 / -1;
        grid-row: 2;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 11px !important;
    }
}
</style>

<div class="page-header mgmt-reports-header">
    <h2>📊 Rapor İzleme</h2>
    <span style="font-size:12px;color:var(--muted)">Tüm departman verileri</span>
</div>

<div class="mgmt-reports-grid" style="display:grid;gap:20px;">

    <?php foreach ($allDepts as $dept): ?>
    <div class="card mgmt-report-card">
        <div class="card-header" style="border-left:3px solid <?= View::escape($dept['color']) ?>;padding-left:17px;">
            <?= View::escape($dept['name']) ?>
            <div style="display:flex;gap:8px;align-items:center;">
                <span style="font-size:11px;color:var(--muted);"><?= $dept['member_count'] ?> üye</span>
                <span style="font-size:11px;color:var(--muted);">·</span>
                <span style="font-size:11px;color:var(--muted);"><?= $dept['app_count'] ?> uygulama</span>
            </div>
        </div>
        <table class="mgmt-report-table">
            <thead>
                <tr>
                    <th>Üye</th>
                    <th>Rol</th>
                    <th>E-posta</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($dept['members'])): ?>
                <tr><td colspan="3" style="color:var(--muted);padding:16px;text-align:center;font-size:12px;">Henüz üye yok</td></tr>
            <?php else: ?>
                <?php foreach ($dept['members'] as $m): ?>
                <tr class="mgmt-report-row">
                    <td data-label="Üye" style="font-weight:500;font-size:13px"><?= View::escape($m['user_name']) ?></td>
                    <td data-label="Rol">
                        <span class="badge <?= $m['role'] === 'director' ? 'badge-director' : 'badge-member' ?>">
                            <?= $m['role'] === 'director' ? 'Direktör' : 'Üye' ?>
                        </span>
                    </td>
                    <td data-label="E-posta" style="color:var(--muted);font-size:12px"><?= View::escape($m['email']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php endforeach; ?>

</div>
