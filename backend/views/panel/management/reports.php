<?php $pageTitle = 'Departmanlar Arası Rapor İzleme'; $activeSection = 'reports'; ?>

<div class="page-header">
    <h2>📊 Rapor İzleme</h2>
    <span style="font-size:12px;color:var(--muted)">Tüm departman verileri</span>
</div>

<div style="display:grid;gap:20px;">

    <?php foreach ($allDepts as $dept): ?>
    <div class="card">
        <div class="card-header" style="border-left:3px solid <?= View::escape($dept['color']) ?>;padding-left:17px;">
            <?= View::escape($dept['name']) ?>
            <div style="display:flex;gap:8px;align-items:center;">
                <span style="font-size:11px;color:var(--muted);"><?= $dept['member_count'] ?> üye</span>
                <span style="font-size:11px;color:var(--muted);">·</span>
                <span style="font-size:11px;color:var(--muted);"><?= $dept['app_count'] ?> uygulama</span>
            </div>
        </div>
        <table>
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
                <tr>
                    <td style="font-weight:500;font-size:13px"><?= View::escape($m['user_name']) ?></td>
                    <td>
                        <span class="badge <?= $m['role'] === 'director' ? 'badge-director' : 'badge-member' ?>">
                            <?= $m['role'] === 'director' ? 'Direktör' : 'Üye' ?>
                        </span>
                    </td>
                    <td style="color:var(--muted);font-size:12px"><?= View::escape($m['email']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php endforeach; ?>

</div>
