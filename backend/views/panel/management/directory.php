<?php $pageTitle = 'Kurumsal Rehber'; $activeSection = 'directory'; ?>

<div class="page-header">
    <h2>👥 Genel Kurumsal Rehber</h2>
    <span style="font-size:12px;color:var(--muted)"><?= $totalUserCount ?> üye · <?= count($allDepts) ?> departman</span>
</div>

<!-- Arama -->
<div style="margin-bottom:20px;">
    <input type="text" id="searchInput" placeholder="İsim veya e-posta ara..." class="form-control"
           style="max-width:360px;" oninput="filterDirectory(this.value)">
</div>

<div class="card">
    <table id="directoryTable">
        <thead>
            <tr>
                <th>Ad Soyad</th>
                <th>E-posta</th>
                <th>Departmanlar</th>
                <th>Durum</th>
                <th>Kayıt</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($directory)): ?>
            <tr><td colspan="5"><div class="empty-state"><p>Kayıtlı üye yok.</p></div></td></tr>
        <?php else: ?>
            <?php foreach ($directory as $u): ?>
            <tr class="dir-row">
                <td style="font-weight:500"><?= View::escape($u['name']) ?></td>
                <td style="color:var(--muted);font-size:12px"><?= View::escape($u['email']) ?></td>
                <td>
                    <?php
                    $deptList = explode(',', $u['depts_info'] ?? '');
                    foreach ($deptList as $dl):
                        $dl = trim($dl);
                        if (!$dl) continue;
                        [$dName, $dRole] = explode('|', $dl . '|member');
                    ?>
                    <span class="badge <?= trim($dRole) === 'director' ? 'badge-director' : 'badge-member' ?>"
                          style="margin-right:3px;margin-bottom:3px;">
                        <?= View::escape(trim($dName)) ?>
                    </span>
                    <?php endforeach; ?>
                </td>
                <td>
                    <span class="badge <?= $u['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                        <?= $u['is_active'] ? 'Aktif' : 'Pasif' ?>
                    </span>
                </td>
                <td style="color:var(--muted);font-size:12px"><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function filterDirectory(q) {
    q = q.toLowerCase();
    document.querySelectorAll('.dir-row').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
    });
}
</script>
