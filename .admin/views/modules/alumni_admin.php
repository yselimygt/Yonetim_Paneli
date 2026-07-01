<?php
if(!function_exists('getInitials')) {
    function getInitials($name) {
        $words = explode(' ', $name);
        $initials = '';
        foreach($words as $w) {
            if(!empty($w)) $initials .= mb_strtoupper(mb_substr($w, 0, 1));
        }
        return mb_substr($initials, 0, 2);
    }
}
?>
<!-- Leaflet & Chart.js -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.alumni-container {
    max-width: 1200px;
    margin: 0 auto;
}
.alumni-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}
.tabs {
    display: flex;
    gap: 16px;
    border-bottom: 1px solid var(--border);
    margin-bottom: 24px;
}
.tab-btn {
    background: none;
    border: none;
    color: var(--muted);
    font-size: 16px;
    font-weight: 500;
    padding: 12px 16px;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
}
.tab-btn:hover { color: var(--text); }
.tab-btn.active {
    color: var(--accent);
    border-bottom-color: var(--accent);
}
.tab-pane { display: none; }
.tab-pane.active { display: block; }

.alumni-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
}
.alumni-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 24px;
    position: relative;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}
.alumni-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -8px rgba(0,0,0,0.15);
    border-color: var(--accent);
}
.alumni-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--surface);
    color: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 16px;
    object-fit: cover;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.alumni-name { font-size: 18px; font-weight: 600; color: var(--text); margin-bottom: 4px; }
.alumni-title { font-size: 14px; color: var(--muted); margin-bottom: 12px; min-height: 40px; }
.alumni-meta { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; margin-bottom: 16px; }
.alumni-badge {
    background: var(--surface); color: var(--text); font-size: 12px; padding: 4px 10px; border-radius: 20px; font-weight: 500; display: flex; align-items: center; gap: 4px;
}
.badge-mentor { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.badge-spotlight { background: rgba(245, 158, 11, 0.1); color: #f59e0b; position:absolute; top:12px; left:12px; border-radius:50%; width:32px; height:32px; display:flex; align-items:center; justify-content:center;}

.alumni-card-actions { margin-top: auto; width: 100%; display: flex; gap: 8px; }
.alumni-card-actions .btn { flex: 1; height: 36px; }

/* Modals */
.modal-overlay {
    position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
    display: none; align-items: center; justify-content: center; z-index: 1000;
}
.modal-overlay.active { display: flex; }
.modal-content {
    background: var(--card); width: 100%; max-width: 600px; border-radius: 16px; box-shadow: 0 24px 48px -12px rgba(0,0,0,0.25); max-height: 90vh; overflow-y: auto;
}
.modal-header {
    padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: var(--card); z-index: 10;
}
.modal-header h3 { margin: 0; font-size: 18px; }
.modal-body { padding: 24px; }
.modal-close { background: none; border: none; color: var(--muted); cursor: pointer; padding: 4px; }
.modal-close:hover { color: var(--text); }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-group.full { grid-column: 1 / -1; }

.job-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 20px; display: flex; justify-content: space-between; align-items: center; margin-bottom:16px; transition: all 0.2s; }
.job-card:hover { border-color: var(--accent); }
.job-type-badge { padding:4px 10px; border-radius:6px; font-size:12px; font-weight:600; background:var(--surface); }
.job-type-badge.is { background:rgba(59,130,246,0.1); color:#3b82f6; }
.job-type-badge.staj { background:rgba(245,158,11,0.1); color:#f59e0b; }

#map { height: 350px; border-radius: 12px; margin-bottom:24px; background: #e5e5e5; z-index: 1; }
</style>

<div class="alumni-container">
    <div class="alumni-header">
        <div>
            <h2 style="margin:0; font-size:24px;">Mezunlar (Alumni) Ağı</h2>
            <p style="color:var(--muted); margin:4px 0 0 0;">Geçmiş üyelerimizle iletişimde kalın ve networking yapın.</p>
        </div>
        <div>
            <button class="btn btn-outline" onclick="openJobModal()">Yeni İlan Ekle</button>
            <button class="btn btn-primary" onclick="openAlumniModal()" style="margin-left:8px;">Yeni Mezun Ekle</button>
        </div>
    </div>

    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('directory', this)">Dizin & Harita</button>
        <button class="tab-btn" onclick="switchTab('jobs', this)">Kariyer Panosu</button>
        <button class="tab-btn" onclick="switchTab('stats', this)">İstatistikler</button>
    </div>

    <!-- TAB: DIRECTORY -->
    <div id="tab-directory" class="tab-pane active">
        <!-- Harita Alanı -->
        <div id="map"></div>

        <!-- Filters -->
        <div style="background: var(--card); padding: 16px; border-radius: 12px; margin-bottom: 24px; display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <form method="GET" action="" style="display:flex; gap:12px; width: 100%; margin:0;">
                <input type="text" name="q" value="<?= View::escape($search ?? '') ?>" class="form-control" style="flex:1; min-width:200px;" placeholder="İsim, e-posta, üniversite ara...">
                
                <select name="year" class="form-control" style="width:140px;">
                    <option value="">Tüm Yıllar</option>
                    <?php foreach($years as $y): ?>
                        <option value="<?= $y ?>" <?= ($gradYear == $y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="company" class="form-control" style="width:180px;">
                    <option value="">Tüm Şirketler</option>
                    <?php foreach($companies as $c): ?>
                        <option value="<?= View::escape($c) ?>" <?= ($company == $c) ? 'selected' : '' ?>><?= View::escape($c) ?></option>
                    <?php endforeach; ?>
                </select>

                <label style="display:flex; align-items:center; gap:6px; cursor:pointer; color:var(--text); font-size:14px; user-select:none;">
                    <input type="checkbox" name="mentor" value="1" <?= $mentorOnly ? 'checked' : '' ?> onchange="this.form.submit()">
                    Mentörler
                </label>

                <button type="submit" class="btn btn-primary">Filtrele</button>
                <?php if($search || $mentorOnly || $company || $gradYear): ?>
                    <a href="?" class="btn btn-outline">Temizle</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Grid -->
        <div class="alumni-grid">
            <?php if(empty($alumni)): ?>
                <div style="grid-column: 1 / -1; text-align:center; padding: 60px 0; color: var(--muted);">
                    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:16px; opacity:0.5;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <p>Aradığınız kriterlerde mezun bulunamadı.</p>
                </div>
            <?php endif; ?>

            <?php foreach($alumni as $alum): ?>
                <div class="alumni-card">
                    <?php if($alum['is_spotlight']): ?>
                        <div class="badge-spotlight" title="Başarı Hikayesi / Öne Çıkan">★</div>
                    <?php endif; ?>
                    <?php if($alum['is_mentor']): ?>
                        <div style="position:absolute; top:12px; right:12px; color:#10b981;" title="Mentörlüğe Açık">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"></path></svg>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($alum['avatar_path'])): ?>
                        <img src="<?= BASE_URL . '/public/' . View::escape($alum['avatar_path']) ?>" alt="" class="alumni-avatar">
                    <?php else: ?>
                        <div class="alumni-avatar"><?= getInitials($alum['full_name']) ?></div>
                    <?php endif; ?>

                    <div class="alumni-name"><?= View::escape($alum['full_name']) ?></div>
                    <div class="alumni-title">
                        <?php if($alum['job_title']): ?>
                            <?= View::escape($alum['job_title']) ?> 
                            <?php if($alum['company']): ?><br><span style="color:var(--text); font-weight:500;">@ <?= View::escape($alum['company']) ?></span><?php endif; ?>
                        <?php else: ?>
                            <i>Belirtilmedi</i>
                        <?php endif; ?>
                    </div>

                    <div class="alumni-meta">
                        <?php if($alum['graduation_year']): ?>
                            <span class="alumni-badge">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                                <?= View::escape($alum['graduation_year']) ?>
                            </span>
                        <?php endif; ?>
                        <?php if($alum['location']): ?>
                            <span class="alumni-badge">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                <?= View::escape($alum['location']) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="alumni-card-actions">
                        <button class="btn btn-outline" onclick='viewAlumni(<?= json_encode($alum) ?>)'>İncele</button>
                        <?php if($alum['is_mentor']): ?>
                            <button class="btn btn-primary" onclick='openMessageModal(<?= json_encode($alum) ?>)'>İletişime Geç</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- TAB: JOBS -->
    <div id="tab-jobs" class="tab-pane">
        <?php if(empty($jobs)): ?>
            <div style="text-align:center; padding: 60px 0; color: var(--muted);">
                <p>Henüz ilan yayınlanmamış.</p>
            </div>
        <?php else: ?>
            <?php foreach($jobs as $job): ?>
                <div class="job-card">
                    <div style="display:flex; gap:16px; align-items:center;">
                        <?php if(!empty($job['posted_by_avatar'])): ?>
                            <img src="<?= BASE_URL . '/public/' . View::escape($job['posted_by_avatar']) ?>" alt="" style="width:50px; height:50px; border-radius:50%; object-fit:cover;">
                        <?php else: ?>
                            <div style="width:50px; height:50px; border-radius:50%; background:var(--surface); display:flex; align-items:center; justify-content:center; font-weight:bold; color:var(--text);">
                                <?= getInitials($job['posted_by_name'] ?? 'U K') ?>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h4 style="margin:0 0 4px 0; font-size:16px; display:flex; align-items:center; gap:8px;">
                                <?= View::escape($job['title']) ?>
                                <span class="job-type-badge <?= strtolower($job['job_type']) == 'staj' ? 'staj' : 'is' ?>"><?= View::escape($job['job_type']) ?></span>
                            </h4>
                            <div style="color:var(--muted); font-size:14px; display:flex; gap:12px;">
                                <span><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle; margin-right:4px;"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg><?= View::escape($job['company']) ?></span>
                                <?php if($job['location']): ?><span><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle; margin-right:4px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg><?= View::escape($job['location']) ?></span><?php endif; ?>
                                <span><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle; margin-right:4px;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg><?= date('d.m.Y', strtotime($job['created_at'])) ?></span>
                            </div>
                        </div>
                    </div>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <?php if($job['apply_url']): ?>
                            <a href="<?= View::escape($job['apply_url']) ?>" target="_blank" class="btn btn-primary">Başvur</a>
                        <?php endif; ?>
                        <form method="POST" action="" onsubmit="return confirm('İlanı silmek istiyor musunuz?');" style="margin:0;">
                            <input type="hidden" name="action" value="delete_job">
                            <input type="hidden" name="item_id" value="<?= $job['id'] ?>">
                            <button type="submit" class="btn btn-outline" style="color:var(--danger); border-color:transparent;" title="Sil">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- TAB: STATS -->
    <div id="tab-stats" class="tab-pane">
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px;">
            <div style="background:var(--card); border:1px solid var(--border); border-radius:12px; padding:24px;">
                <h4 style="margin:0 0 16px 0; font-size:16px;">Şirket Dağılımı</h4>
                <canvas id="companyChart" height="250"></canvas>
            </div>
            <div style="background:var(--card); border:1px solid var(--border); border-radius:12px; padding:24px;">
                <h4 style="margin:0 0 16px 0; font-size:16px;">Mezuniyet Yılı Dağılımı</h4>
                <canvas id="yearChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Modals ... -->
<!-- Include Alumni Modal and View Modal here, mostly same as before... -->
<?php require_once __DIR__ . '/_alumni_modals.php'; ?>

<script>
function switchTab(tabId, btnElement) {
    document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    
    document.getElementById('tab-' + tabId).classList.add('active');
    btnElement.classList.add('active');
    
    if(tabId === 'directory' && window.map) {
        setTimeout(() => { window.map.invalidateSize(); }, 100);
    }
}

// Leaflet Map Init
let alumniData = <?= json_encode($alumni) ?>;
document.addEventListener("DOMContentLoaded", function() {
    // Harita merkezini Türkiye'ye ayarlayalım
    window.map = L.map('map').setView([39.0, 35.0], 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(window.map);

    // Coğrafi kodlama (Basit şehir ismiyle mock veya gerçek API kullanımı)
    // Şehir koordinatlarını manuel eklemek zor olduğundan, bilinen bazı şehirleri mapleyelim:
    const cityCoords = {
        'istanbul': [41.0082, 28.9784],
        'ankara': [39.9334, 32.8597],
        'izmir': [38.4192, 27.1287],
        'london': [51.5074, -0.1278],
        'berlin': [52.5200, 13.4050],
        'new york': [40.7128, -74.0060]
    };

    alumniData.forEach(alum => {
        if(alum.location) {
            let loc = alum.location.toLowerCase();
            let coords = null;
            for(let key in cityCoords) {
                if(loc.includes(key)) { coords = cityCoords[key]; break; }
            }
            if(coords) {
                // Pin ekle
                let marker = L.marker(coords).addTo(window.map);
                marker.bindPopup(`<b>${alum.full_name}</b><br>${alum.company || alum.job_title || ''}<br>${alum.location}`);
            }
        }
    });

    // Chart.js Init
    initCharts();
});

function initCharts() {
    // Şirket Verisi Hazırlama
    let companyCounts = {};
    let yearCounts = {};
    
    alumniData.forEach(alum => {
        if(alum.company) {
            companyCounts[alum.company] = (companyCounts[alum.company] || 0) + 1;
        }
        if(alum.graduation_year) {
            yearCounts[alum.graduation_year] = (yearCounts[alum.graduation_year] || 0) + 1;
        }
    });

    let compLabels = Object.keys(companyCounts);
    let compData = Object.values(companyCounts);
    let yrLabels = Object.keys(yearCounts).sort();
    let yrData = yrLabels.map(y => yearCounts[y]);

    const textColor = document.documentElement.getAttribute('data-theme') === 'light' ? '#333' : '#eee';

    new Chart(document.getElementById('companyChart'), {
        type: 'pie',
        data: {
            labels: compLabels,
            datasets: [{
                data: compData,
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899']
            }]
        },
        options: { plugins: { legend: { labels: { color: textColor } } } }
    });

    new Chart(document.getElementById('yearChart'), {
        type: 'bar',
        data: {
            labels: yrLabels,
            datasets: [{
                label: 'Mezun Sayısı',
                data: yrData,
                backgroundColor: '#3b82f6'
            }]
        },
        options: {
            scales: {
                y: { ticks: { color: textColor, stepSize:1 } },
                x: { ticks: { color: textColor } }
            },
            plugins: { legend: { display: false } }
        }
    });
}
</script>
