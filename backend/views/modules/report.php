<?php
// views/modules/report.php
// Beklenen değişkenler: $deptApps, $financeStats, $memberStats, $inventoryStats, $bootcampStats
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.rep-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.kpi-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    position: relative;
    overflow: hidden;
}

.kpi-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: var(--accent);
}

.kpi-card.kpi-finance::before { background: #10b981; }
.kpi-card.kpi-members::before { background: #3b82f6; }
.kpi-card.kpi-inventory::before { background: #8b5cf6; }
.kpi-card.kpi-bootcamp::before { background: #f59e0b; }

.kpi-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.kpi-value {
    font-size: 28px;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 4px;
}

.kpi-sub {
    font-size: 13px;
    color: var(--muted);
}

.charts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 32px;
}

.chart-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 20px;
    min-width: 0;
}

.chart-card h3 {
    margin: 0 0 16px 0;
    font-size: 16px;
    font-weight: 600;
    color: var(--text);
    border-bottom: 1px solid var(--border);
    padding-bottom: 12px;
}

.export-section {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 24px;
}

.export-section h3 {
    margin: 0 0 16px 0;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.export-form {
    display: flex;
    gap: 16px;
    align-items: center;
}

.rep-btn {
    padding: 10px 20px;
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.rep-btn:hover { opacity: 0.9; transform: translateY(-1px); }

@media(max-width: 768px) {
    .rep-header {
        margin-bottom: 16px;
    }
    .rep-header h2 {
        font-size: 20px !important;
        line-height: 1.15;
    }
    .rep-header p {
        display: none;
    }
    .kpi-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 14px;
    }
    .kpi-card {
        padding: 13px;
        border-radius: 14px;
        min-width: 0;
        box-shadow: none;
    }
    .kpi-card::before {
        height: 3px;
    }
    .kpi-title {
        font-size: 10px;
        letter-spacing: 0;
        margin-bottom: 6px;
    }
    .kpi-value {
        font-size: 20px;
        line-height: 1.15;
        margin-bottom: 4px;
        overflow-wrap: anywhere;
    }
    .kpi-sub {
        font-size: 10px;
        line-height: 1.25;
    }
    .charts-grid {
        grid-template-columns: 1fr;
        gap: 10px;
        margin-bottom: 14px;
    }
    .chart-card {
        padding: 14px;
        border-radius: 14px;
    }
    .chart-card h3 {
        font-size: 13px;
        margin-bottom: 10px;
        padding-bottom: 10px;
    }
    .chart-card > div {
        height: 210px !important;
    }
    .export-section {
        padding: 14px;
        border-radius: 14px;
    }
    .export-section h3 {
        font-size: 15px;
        margin-bottom: 8px;
    }
    .export-section p {
        display: none;
    }
    .export-form {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }
    .export-form .form-control,
    .export-form select {
        max-width: none !important;
        min-height: 38px;
        padding: 9px 10px;
        border-radius: 9px;
        font-size: 13px;
    }
    .rep-btn {
        width: 100%;
        min-height: 38px;
        justify-content: center;
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 13px;
    }
}
</style>

<div class="rep-header">
    <div>
        <h2 style="margin:0;font-size:24px;">📊 Analiz ve Raporlar</h2>
        <p style="color:var(--muted);margin:4px 0 0 0;font-size:14px;">Departmanınıza ait uygulamaların özet analizleri.</p>
    </div>
</div>

<div class="kpi-grid">
    <div class="kpi-card kpi-finance">
        <div class="kpi-title">Mevcut Bakiye</div>
        <div class="kpi-value"><?= number_format($financeStats['balance'], 2, ',', '.') ?> ₺</div>
        <div class="kpi-sub">Gelir: <?= number_format($financeStats['income'], 0) ?> ₺ | Gider: <?= number_format($financeStats['expense'], 0) ?> ₺</div>
    </div>
    
    <div class="kpi-card kpi-members">
        <div class="kpi-title">Toplam Üye</div>
        <div class="kpi-value"><?= $memberStats['total'] ?></div>
        <div class="kpi-sub"><?= $memberStats['active'] ?> Aktif, <?= $memberStats['passive'] ?> Pasif</div>
    </div>
    
    <div class="kpi-card kpi-inventory">
        <div class="kpi-title">Envanter Hacmi</div>
        <div class="kpi-value"><?= $inventoryStats['total_items'] ?> Adet</div>
        <div class="kpi-sub">Değer: <?= number_format($inventoryStats['total_value'], 0, ',', '.') ?> ₺ (<?= $inventoryStats['broken'] ?> Arızalı)</div>
    </div>
    
    <div class="kpi-card kpi-bootcamp">
        <div class="kpi-title">Bootcamp Programları</div>
        <div class="kpi-value"><?= $bootcampStats['total'] ?></div>
        <div class="kpi-sub"><?= $bootcampStats['active'] ?> Aktif Program</div>
    </div>
</div>

<div class="charts-grid">
    <div class="chart-card">
        <h3>Finansal Dağılım</h3>
        <div style="position: relative; height: 250px; width: 100%;">
            <canvas id="financeChart"></canvas>
        </div>
    </div>
    
    <div class="chart-card">
        <h3>Bölümlere Göre Üyeler (İlk 5)</h3>
        <div style="position: relative; height: 250px; width: 100%;">
            <canvas id="memberChart"></canvas>
        </div>
    </div>
    
    <div class="chart-card">
        <h3>Envanter Dağılımı (Kategori)</h3>
        <div style="position: relative; height: 250px; width: 100%;">
            <canvas id="inventoryChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <h3>Bootcamp Alanları</h3>
        <div style="position: relative; height: 250px; width: 100%;">
            <canvas id="bootcampChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <h3>Etkinlik Türleri</h3>
        <div style="position: relative; height: 250px; width: 100%;">
            <canvas id="eventChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <h3>En Çok Katılım Alan Formlar</h3>
        <div style="position: relative; height: 250px; width: 100%;">
            <canvas id="formChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <h3>Sponsor & Mekan Durumu</h3>
        <div style="position: relative; height: 250px; width: 100%;">
            <canvas id="partnerChart"></canvas>
        </div>
    </div>
</div>

<div class="export-section">
    <h3>
        <svg width="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
        Excel / CSV Olarak İndir
    </h3>
    <p style="color:var(--muted); margin-bottom:16px;">Bir uygulamanın tüm kayıtlarını dışa aktarmak için aşağıdan seçin.</p>
    
    <form method="POST" class="export-form">
        <input type="hidden" name="action" value="export_csv">
        <select name="export_app_id" class="form-control" style="max-width:300px;" required>
            <option value="">Uygulama Seçin...</option>
            <?php foreach($deptApps as $app): ?>
                <option value="<?= $app['id'] ?>"><?= htmlspecialchars($app['name']) ?> (<?= $app['module_type'] ?>)</option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="rep-btn">
            Dışa Aktar
        </button>
    </form>
</div>

<script>
if (typeof Chart !== 'undefined') {
// Chart Defaults for Dark/Light Mode adaptivity
Chart.defaults.color = '#888';
Chart.defaults.font.family = 'inherit';

// 1. Finance Chart (Doughnut)
const ctxFinance = document.getElementById('financeChart').getContext('2d');
new Chart(ctxFinance, {
    type: 'doughnut',
    data: {
        labels: ['Toplam Gelir', 'Toplam Gider'],
        datasets: [{
            data: [<?= $financeStats['income'] ?>, <?= $financeStats['expense'] ?>],
            backgroundColor: ['#10b981', '#ef4444'],
            borderWidth: 0,
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' }
        },
        cutout: '70%'
    }
});

// 2. Member Chart (Bar)
<?php 
$topDeps = array_slice($memberStats['by_department'], 0, 5);
$memLabels = array_keys($topDeps);
$memData = array_values($topDeps);
?>
const ctxMember = document.getElementById('memberChart').getContext('2d');
new Chart(ctxMember, {
    type: 'bar',
    data: {
        labels: <?= json_encode($memLabels) ?>,
        datasets: [{
            label: 'Üye Sayısı',
            data: <?= json_encode($memData) ?>,
            backgroundColor: '#3b82f6',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' } },
            x: { grid: { display: false } }
        }
    }
});

// 3. Inventory Chart (Pie)
<?php 
$invLabels = array_keys($inventoryStats['by_category']);
$invData = array_values($inventoryStats['by_category']);
?>
new Chart(document.getElementById('inventoryChart').getContext('2d'), {
    type: 'pie',
    data: {
        labels: <?= json_encode($invLabels) ?>,
        datasets: [{
            data: <?= json_encode($invData) ?>,
            backgroundColor: ['#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6', '#64748b'],
            borderWidth: 0
        }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' } } }
});

// 4. Bootcamp Chart (Doughnut)
<?php 
$bcLabels = array_keys($bootcampStats['by_category']);
$bcData = array_values($bootcampStats['by_category']);
?>
new Chart(document.getElementById('bootcampChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($bcLabels) ?>,
        datasets: [{
            data: <?= json_encode($bcData) ?>,
            backgroundColor: ['#f59e0b', '#3b82f6', '#ec4899', '#8b5cf6'],
            borderWidth: 0
        }]
    },
    options: { responsive: true, maintainAspectRatio: false, cutout: '60%', plugins: { legend: { position: 'bottom' } } }
});

// 5. Event Chart (Pie)
new Chart(document.getElementById('eventChart').getContext('2d'), {
    type: 'pie',
    data: {
        labels: ['Fiziksel', 'Online (Çevrimiçi)'],
        datasets: [{
            data: [<?= $eventStats['physical'] ?>, <?= $eventStats['online'] ?>],
            backgroundColor: ['#f97316', '#0ea5e9'],
            borderWidth: 0
        }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
});

// 6. Form Chart (Bar - Horizontal)
<?php 
$formLabels = array_keys($formStats);
$formData = array_values($formStats);
?>
new Chart(document.getElementById('formChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($formLabels) ?>,
        datasets: [{
            label: 'Katılım Sayısı',
            data: <?= json_encode($formData) ?>,
            backgroundColor: '#10b981',
            borderRadius: 4
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' } },
            y: { grid: { display: false } }
        }
    }
});

// 7. Partner Chart (Doughnut)
new Chart(document.getElementById('partnerChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Aktif Anlaşmalar', 'Pasif Anlaşmalar'],
        datasets: [{
            data: [<?= $partnerStats['active'] ?>, <?= $partnerStats['inactive'] ?>],
            backgroundColor: ['#10b981', '#64748b'],
            borderWidth: 0
        }]
    },
    options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'bottom' } } }
});
}
</script>
