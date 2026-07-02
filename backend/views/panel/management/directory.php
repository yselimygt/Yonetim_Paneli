<?php $pageTitle = 'Kurumsal Rehber'; $activeSection = 'directory'; ?>

<style>
@media (max-width: 900px) {
    .mgmt-directory-header {
        align-items: flex-start;
        gap: 4px;
        margin-bottom: 14px;
    }

    .mgmt-directory-header h2 {
        font-size: 18px;
        line-height: 1.2;
        margin: 0;
    }

    .mgmt-directory-search {
        margin-bottom: 12px !important;
    }

    .mgmt-directory-search .form-control {
        max-width: none !important;
        width: 100%;
        min-height: 40px;
        border-radius: 10px;
        font-size: 13px;
    }

    .mgmt-directory-card {
        padding: 10px;
        border-radius: 12px;
        overflow: visible;
    }

    #directoryTable,
    #directoryTable tbody,
    #directoryTable tr,
    #directoryTable td {
        display: block;
        width: 100%;
    }

    #directoryTable thead {
        display: none;
    }

    #directoryTable tr.dir-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 7px 10px;
        padding: 12px;
        margin-bottom: 10px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--card-alt);
    }

    #directoryTable tr.dir-row:last-child {
        margin-bottom: 0;
    }

    #directoryTable td {
        padding: 0 !important;
        border: 0 !important;
        background: transparent !important;
    }

    #directoryTable td[data-label="Ad Soyad"] {
        grid-column: 1 / -1;
        grid-row: 1;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 14px;
        font-weight: 700 !important;
    }

    #directoryTable td[data-label="Çalıştığı Yer"] {
        grid-column: 1;
        grid-row: 2;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: var(--muted);
        font-size: 12px !important;
    }

    #directoryTable td[data-label="İlan"] {
        grid-column: 2;
        grid-row: 2;
        text-align: right;
    }

    .directory-title-pill {
        display: inline-flex;
        align-items: center;
        min-height: 22px;
        max-width: 120px;
        padding: 3px 8px;
        border-radius: 999px;
        background: rgba(0, 188, 212, 0.12);
        color: var(--primary);
        font-size: 10px;
        font-weight: 700;
        line-height: 1.2;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}
</style>

<div class="page-header mgmt-directory-header">
    <h2>👥 Genel Kurumsal Rehber</h2>
    <span style="font-size:12px;color:var(--muted)"><?= $totalUserCount ?> kayıt</span>
</div>

<!-- Arama -->
<div class="mgmt-directory-search" style="margin-bottom:20px;">
    <input type="text" id="searchInput" placeholder="Ad, çalıştığı yer veya ilan ara..." class="form-control"
           style="max-width:360px;" oninput="filterDirectory(this.value)">
</div>

<div class="card mgmt-directory-card">
    <table id="directoryTable">
        <thead>
            <tr>
                <th>Ad Soyad</th>
                <th>Çalıştığı Yer</th>
                <th>İlan</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($directory)): ?>
            <tr><td colspan="3"><div class="empty-state"><p>Kayıtlı rehber kaydı yok.</p></div></td></tr>
        <?php else: ?>
            <?php foreach ($directory as $u): ?>
            <tr class="dir-row">
                <td data-label="Ad Soyad" style="font-weight:500"><?= View::escape($u['name'] ?? '') ?></td>
                <td data-label="Çalıştığı Yer" style="color:var(--muted);font-size:12px">
                    <?= View::escape($u['company'] ?: '-') ?>
                </td>
                <td data-label="İlan">
                    <span class="directory-title-pill">
                        <?= View::escape($u['title'] ?: '-') ?>
                    </span>
                </td>
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
