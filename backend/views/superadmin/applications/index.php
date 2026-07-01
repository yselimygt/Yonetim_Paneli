<?php $pageTitle = 'Tüm Başvurular'; ?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0"><i class="fas fa-file-signature text-primary me-2"></i> Yönetim & Temsilcilik Başvuruları</h2>
    </div>
</div>

<style>
/* Split Pane Layout */
.app-container {
    display: flex;
    gap: 20px;
    height: calc(100vh - 200px);
    min-height: 500px;
}

/* Left Pane: List */
.app-list-pane {
    flex: 0 0 350px;
    display: flex;
    flex-direction: column;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
}

.app-list-header {
    padding: 16px;
    border-bottom: 1px solid var(--border);
    background: var(--card-alt);
}

.app-list-content {
    flex: 1;
    overflow-y: auto;
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.app-card {
    padding: 14px 16px;
    border: 1px solid var(--border);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: var(--input-bg);
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.app-card:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.app-card.active {
    border-color: var(--primary);
    background: rgba(245, 158, 11, 0.05);
    box-shadow: 0 0 0 1px var(--primary);
}
.app-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.app-sender {
    font-size: 14px;
    font-weight: 600;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
}
.app-date {
    font-size: 11px;
    color: var(--muted);
    flex-shrink: 0;
}
.app-subject {
    font-size: 12px;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 6px;
}

.app-badge {
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    align-self: flex-start;
}
.badge-pending { background: rgba(234, 179, 8, 0.15); color: #eab308; border: 1px solid rgba(234, 179, 8, 0.3); }
.badge-reviewing { background: rgba(59, 130, 246, 0.15); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.3); }
.badge-approved { background: rgba(34, 197, 94, 0.15); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3); }
.badge-rejected { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }

/* Right Pane: Details */
.app-detail-pane {
    flex: 1;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.app-detail-header {
    padding: 24px;
    border-bottom: 1px solid var(--border);
    background: var(--card-alt);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.app-detail-content {
    flex: 1;
    overflow-y: auto;
    padding: 0;
    background: var(--bg);
}

.app-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-muted);
}
.app-empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.2;
}

/* Detail Form Data Grid */
.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
    padding: 24px;
}
.json-field {
    background: var(--card);
    padding: 16px;
    border-radius: 8px;
    border: 1px solid var(--border);
}
.json-field.full-width {
    grid-column: 1 / -1;
}
.json-field h6 {
    margin-bottom: 10px;
    color: #3b82f6 !important; /* Mavi renk (kesin) */
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.json-field p {
    margin-bottom: 0;
    color: var(--text);
    font-size: 14px;
    line-height: 1.5;
    white-space: pre-wrap;
}

/* Base Info Row */
.base-info-row {
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
    padding: 20px 24px;
    background: var(--card);
    border-bottom: 1px solid var(--border);
}
.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
}
.info-item-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(59, 130, 246, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.2);
}
.info-item-text div:first-child {
    font-size: 11px;
    color: #3b82f6;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}
.info-item-text div:last-child {
    font-size: 14px;
    font-weight: 500;
    color: #f3f4f6;
}
.status-select-custom {
    background-color: #1f2937 !important;
    color: #f9fafb !important;
    border: 1px solid #4b5563 !important;
}

@media (max-width: 900px) {
    .app-container { flex-direction: column; height: auto; }
    .app-list-pane { flex: none; max-height: 400px; }
    .app-detail-pane { min-height: 600px; }
}
.d-none { display: none !important; }
</style>

<div class="app-container fade-in-up stagger-1">
    <!-- Sol Liste -->
    <div class="app-list-pane">
        <div class="app-list-header">
            <h5 class="mb-0 fs-6 fw-bold">Başvuru Listesi</h5>
        </div>
        <div class="app-list-content" id="appList">
            <?php if(empty($applications)): ?>
                <div style="text-align: center; padding: 30px 10px; color: var(--muted); font-size: 13px;">
                    <i class="fas fa-inbox fa-2x mb-2 opacity-50"></i><br>
                    Henüz başvuru bulunmuyor.
                </div>
            <?php else: ?>
                <?php foreach($applications as $app): 
                    $typeLabel = $app['type'] === 'management' ? 'Yönetim Kurulu' : 'Temsilcilik';
                    $badgeClass = 'badge-' . $app['status'];
                    $statusLabel = match($app['status']) {
                        'pending' => 'Bekliyor',
                        'reviewing' => 'İnceleniyor',
                        'approved' => 'Onaylandı',
                        'rejected' => 'Reddedildi',
                        default => 'Bilinmiyor'
                    };
                ?>
                    <div class="app-card" onclick='showDetails(<?= json_encode($app, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                        <div class="app-card-header">
                            <span class="app-sender"><?= htmlspecialchars($app['full_name']) ?></span>
                            <span class="app-date"><?= date('d.m.Y H:i', strtotime($app['created_at'])) ?></span>
                        </div>
                        <div class="app-subject">
                            <i class="fas fa-briefcase opacity-50"></i> <?= $typeLabel ?>
                        </div>
                        <span class="app-badge <?= $badgeClass ?>"><?= $statusLabel ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sağ Detay -->
    <div class="app-detail-pane" id="appDetailPane">
        <div class="app-empty-state" id="emptyState">
            <i class="fas fa-file-signature"></i>
            <h5 style="color:var(--text);">Başvuru Seçin</h5>
            <p style="font-size:13px;">Detayları görüntülemek için sol taraftan bir başvuru seçin.</p>
        </div>
        
        <div id="detailContent" class="d-none" style="display: flex; flex-direction: column; height: 100%;">
            <!-- Header -->
            <div class="app-detail-header">
                <div>
                    <h3 class="mb-1 text-white fw-bold" id="d-name" style="font-size: 20px;">-</h3>
                    <div class="fs-6" style="color: #60a5fa;" id="d-type-date">-</div>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <form method="POST" action="<?= BASE_URL ?>/superadmin/applications/status" id="statusForm" class="d-flex gap-2 align-items-center mb-0">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="hidden" name="id" id="status_id">
                        <select name="status" class="form-select form-select-sm w-auto status-select-custom" id="status_select">
                            <option value="pending">Bekliyor</option>
                            <option value="reviewing">İnceleniyor</option>
                            <option value="approved">Onaylandı</option>
                            <option value="rejected">Reddedildi</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Güncelle</button>
                    </form>
                    <form method="POST" action="<?= BASE_URL ?>/superadmin/applications/delete" onsubmit="return confirm('Bu başvuruyu kalıcı olarak silmek istediğinize emin misiniz?');" class="mb-0">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="hidden" name="id" id="delete_id">
                        <button type="submit" class="btn btn-sm" style="background-color: #ef4444; border-color: #dc2626; color: white; padding: 4px 10px;"><i class="fas fa-trash me-1"></i> Sil</button>
                    </form>
                </div>
            </div>

            <!-- Content -->
            <div class="app-detail-content">
                <!-- Temel Bilgiler -->
                <div class="base-info-row">
                    <div class="info-item">
                        <div class="info-item-icon"><i class="fas fa-envelope"></i></div>
                        <div class="info-item-text">
                            <div>E-posta</div>
                            <div id="d-email">-</div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-icon"><i class="fas fa-phone"></i></div>
                        <div class="info-item-text">
                            <div>Telefon</div>
                            <div id="d-phone">-</div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-icon"><i class="fas fa-university"></i></div>
                        <div class="info-item-text">
                            <div>Üniversite</div>
                            <div id="d-university">-</div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-icon"><i class="fas fa-graduation-cap"></i></div>
                        <div class="info-item-text">
                            <div>Bölüm</div>
                            <div id="d-faculty">-</div>
                        </div>
                    </div>
                </div>

                <!-- Dinamik Form Detayları -->
                <div class="px-4 pt-4 pb-2">
                    <h6 style="color:var(--text); font-weight:600; font-size:14px; margin:0;"><i class="fas fa-list-ul me-2 text-primary"></i> Form Detayları</h6>
                </div>
                <div class="detail-grid" id="d-formdata">
                    <!-- JSON fields will be appended here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showDetails(app) {
    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('detailContent').style.display = 'flex';
    document.getElementById('detailContent').classList.remove('d-none');
    
    document.querySelectorAll('.app-card').forEach(c => c.classList.remove('active'));
    event.currentTarget.classList.add('active');

    const typeLabel = app.type === 'management' ? 'Yönetim Kurulu' : 'Temsilcilik';
    const dateStr = new Date(app.created_at).toLocaleString('tr-TR');

    document.getElementById('d-name').textContent = app.full_name;
    document.getElementById('d-type-date').textContent = `${typeLabel} • ${dateStr}`;
    document.getElementById('d-email').textContent = app.email || '-';
    document.getElementById('d-phone').textContent = app.phone || '-';
    document.getElementById('d-university').textContent = app.university || '-';
    document.getElementById('d-faculty').textContent = app.faculty || '-';
    
    document.getElementById('status_id').value = app.id;
    document.getElementById('delete_id').value = app.id;
    document.getElementById('status_select').value = app.status;

    const fdContainer = document.getElementById('d-formdata');
    fdContainer.innerHTML = '';
    
    if (app.form_data) {
        try {
            const data = JSON.parse(app.form_data);
            const ignoreKeys = ['name', 'email', 'phone', 'university', 'faculty', 'agreed', 'securityCode'];
            
            let hasFields = false;
            
            const keyMap = {
                'city': 'Şehir / İlçe',
                'department': 'Okunan Bölüm',
                'grade': 'Sınıf / Derece',
                'studentId': 'Öğrenci Numarası',
                'targetTeam': 'Hedef Ekip',
                'argeTools': 'Kullandığı Araçlar / Programlar',
                'argePastProjectsDetails': 'Geçmiş Proje Detayları',
                'argeContribution': 'Ar-Ge Ekibine Katkısı / Beklentisi',
                'argeTubitak': 'TÜBİTAK vb. Proje Fikri',
                'argeWebsiteCritique': 'Web Sitesi İncelemesi / Eleştirisi',
                'whyInnomis': 'Neden İnnOmis?',
                'expectations': 'Beklentiler / Hedefler'
            };

            for (const [key, value] of Object.entries(data)) {
                if (ignoreKeys.includes(key) || !value || value.length === 0) continue;
                
                let formattedKey = keyMap[key];
                if (!formattedKey) {
                    // Fallback to Title Case if not in map
                    formattedKey = key.replace(/([a-z])([A-Z])/g, '$1 $2');
                    formattedKey = formattedKey.split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase()).join(' ');
                }
                
                let displayValue = value;
                if (Array.isArray(value)) {
                    displayValue = value.join(', ');
                } else if (typeof value === 'boolean') {
                    displayValue = value ? 'Evet' : 'Hayır';
                }

                // Make long text take full width
                const isLongText = typeof displayValue === 'string' && displayValue.length > 60;
                
                const fieldHtml = `
                    <div class="json-field ${isLongText ? 'full-width' : ''}">
                        <h6>${formattedKey}</h6>
                        <p>${displayValue}</p>
                    </div>
                `;
                fdContainer.insertAdjacentHTML('beforeend', fieldHtml);
                hasFields = true;
            }
            
            if (!hasFields) {
                fdContainer.innerHTML = '<div style="grid-column: 1/-1;" class="text-muted text-center p-4 border border-secondary rounded">Görüntülenecek ek detay bulunmuyor.</div>';
            }
        } catch(e) {
            fdContainer.innerHTML = `<div style="grid-column: 1/-1;"><pre class="text-muted">${app.form_data}</pre></div>`;
        }
    } else {
        fdContainer.innerHTML = '<div style="grid-column: 1/-1;" class="text-muted text-center p-4 border border-secondary rounded">Ek detay bulunmuyor.</div>';
    }
}
</script>
