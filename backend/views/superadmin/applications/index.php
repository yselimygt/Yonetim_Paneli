<?php $pageTitle = 'Tüm Başvurular'; ?>

<div class="row mb-4 applications-page-head">
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

.app-detail-close {
    display: none;
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

@media (max-width: 700px) {
    .applications-page-head {
        margin-bottom: 16px !important;
    }

    .applications-page-head h2 {
        font-size: 18px !important;
        line-height: 1.3;
    }

    .app-list-pane,
    .app-detail-pane {
        border-radius: 12px;
    }

    .app-list-pane {
        border: 0;
        background: transparent;
        max-height: none;
    }

    .app-list-header {
        border: 0;
        background: transparent;
        padding: 0 0 10px;
    }

    .application-filters {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 6px !important;
    }

    .application-filters .btn {
        width: 100%;
        min-height: 30px;
        padding: 0 7px;
        border-radius: 8px;
        font-size: 10px;
    }

    .app-list-content {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 7px;
        max-height: none;
        overflow: visible;
        padding: 0;
    }

    .app-card {
        gap: 6px;
        border-radius: 12px;
        padding: 8px;
        box-shadow: none;
        min-width: 0;
    }

    .app-card-header {
        display: grid;
        gap: 4px;
    }

    .app-sender,
    .app-subject {
        max-width: 100%;
        min-width: 0;
        white-space: normal;
        overflow-wrap: anywhere;
    }

    .app-sender {
        font-size: 12px;
        font-weight: 700;
    }

    .app-subject {
        font-size: 10px;
    }

    .app-date {
        display: none;
    }

    .app-badge {
        font-size: 9px;
        padding: 2px 6px;
    }

    .app-detail-pane {
        display: none;
        position: fixed;
        inset: 10px;
        z-index: 1200;
        min-height: 0;
        max-height: calc(100dvh - 20px);
        background: var(--card);
        box-shadow: 0 16px 42px rgba(0,0,0,.35);
    }

    .app-detail-pane.is-open {
        display: flex;
    }

    .app-detail-header {
        flex-wrap: wrap;
        gap: 10px;
        padding: 14px;
    }

    .app-detail-header h3 {
        font-size: 16px !important;
        overflow-wrap: anywhere;
    }

    .app-detail-close {
        display: inline-flex;
        width: 34px;
        height: 34px;
        flex: 0 0 auto;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--input-bg);
        color: var(--text);
        font-size: 20px;
        line-height: 1;
    }

    .app-empty-state {
        display: none !important;
        min-height: 0;
        padding: 32px 20px;
        text-align: center;
    }

    #detailContent {
        min-height: 0;
        height: auto !important;
    }

    .application-detail-actions,
    .application-status-form,
    .application-delete-form {
        display: grid !important;
        width: 100%;
        gap: 8px !important;
    }

    .application-detail-actions {
        flex: 0 0 100%;
        grid-template-columns: 1fr;
    }

    .application-status-form {
        grid-template-columns: minmax(0, 1fr) auto;
    }

    .application-status-form .form-select,
    .application-status-form .btn,
    .application-delete-form .btn {
        width: 100% !important;
    }

    .app-detail-content {
        overflow: auto;
    }

    .base-info-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
        padding: 16px;
    }

    .info-item {
        align-items: flex-start;
        min-width: 0;
    }

    .info-item-text {
        min-width: 0;
    }

    .info-item-text div:last-child {
        color: var(--text);
        overflow-wrap: anywhere;
    }

    .detail-grid {
        grid-template-columns: 1fr;
        gap: 12px;
        padding: 16px;
    }

    .json-field {
        padding: 14px;
    }

    .json-field p,
    .json-field pre {
        overflow-wrap: anywhere;
        white-space: pre-wrap;
    }
}
.d-none { display: none !important; }
</style>

<div class="app-container fade-in-up stagger-1">
    <!-- Sol Liste -->
    <div class="app-list-pane">
        <div class="app-list-header d-flex flex-column gap-3">
            <h5 class="mb-0 fs-6 fw-bold">Başvuru Listesi</h5>
            <div class="d-flex gap-2 application-filters">
                <button class="btn btn-sm btn-primary app-filter-btn active" data-filter="all">Tümü</button>
                <button class="btn btn-sm btn-outline-secondary app-filter-btn" data-filter="management">Yönetim</button>
                <button class="btn btn-sm btn-outline-secondary app-filter-btn" data-filter="representative">Temsilci</button>
            </div>
        </div>
        <div class="app-list-content" id="appList">
            <?php if(empty($applications)): ?>
                <div style="text-align: center; padding: 30px 10px; color: var(--muted); font-size: 13px;">
                    <i class="fas fa-inbox fa-2x mb-2 opacity-50"></i><br>
                    Henüz başvuru bulunmuyor.
                </div>
            <?php else: ?>
                <?php foreach($applications as $app): 
                    $isManagement = $app['type'] === 'management';
                    $typeLabel = $isManagement ? 'Yönetim Kurulu' : 'Temsilcilik';
                    $filterType = $isManagement ? 'management' : 'representative';
                    $badgeClass = 'badge-' . $app['status'];
                    $statusLabel = match($app['status']) {
                        'pending' => 'Bekliyor',
                        'reviewing' => 'İnceleniyor',
                        'approved' => 'Onaylandı',
                        'rejected' => 'Reddedildi',
                        default => 'Bilinmiyor'
                    };
                ?>
                    <div class="app-card" data-app-type="<?= $filterType ?>" onclick='showDetails(<?= json_encode($app, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
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
                <button type="button" class="app-detail-close" onclick="closeApplicationDetail()" aria-label="Detayı kapat">&times;</button>
                <div class="d-flex gap-2 align-items-center application-detail-actions">
                    <form method="POST" action="<?= BASE_URL ?>/superadmin/applications/status" id="statusForm" class="d-flex gap-2 align-items-center mb-0 application-status-form">
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
                    <form method="POST" action="<?= BASE_URL ?>/superadmin/applications/delete" onsubmit="return confirm('Bu başvuruyu kalıcı olarak silmek istediğinize emin misiniz?');" class="mb-0 application-delete-form">
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
                        <div class="info-item-icon"><i class="fas fa-building"></i></div>
                        <div class="info-item-text">
                            <div>Fakülte</div>
                            <div id="d-faculty">-</div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-icon"><i class="fas fa-graduation-cap"></i></div>
                        <div class="info-item-text">
                            <div>Bölüm</div>
                            <div id="d-department">-</div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-icon"><i class="fas fa-list-ol"></i></div>
                        <div class="info-item-text">
                            <div>Sınıf</div>
                            <div id="d-grade">-</div>
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
document.addEventListener('DOMContentLoaded', () => {
    const filterBtns = document.querySelectorAll('.app-filter-btn');
    const cards = document.querySelectorAll('.app-card');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            // Reset buttons
            filterBtns.forEach(b => {
                b.classList.remove('btn-primary', 'active');
                b.classList.add('btn-outline-secondary');
            });
            e.target.classList.remove('btn-outline-secondary');
            e.target.classList.add('btn-primary', 'active');
            
            const filter = e.target.getAttribute('data-filter');
            
            // Filter cards
            cards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-app-type') === filter) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // If active card is hidden, reset details pane
            const activeCard = document.querySelector('.app-card.active');
            if (activeCard && activeCard.style.display === 'none') {
                document.getElementById('emptyState').style.display = 'flex';
                document.getElementById('detailContent').style.display = 'none';
                activeCard.classList.remove('active');
            }
        });
    });
});

function showDetails(app) {
    document.getElementById('appDetailPane').classList.add('is-open');
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
    
    // Extract department and grade from form_data
    let parsedDepartment = '-';
    let parsedGrade = '-';

    if (app.form_data) {
        try {
            const data = JSON.parse(app.form_data);
            if (data.department) {
                parsedDepartment = data.department;
            }
            if (data.grade) {
                parsedGrade = data.grade;
            }
            const ignoreKeys = ['name', 'email', 'phone', 'university', 'faculty', 'agreed', 'securityCode', 'department', 'grade'];
            
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

    document.getElementById('d-department').textContent = parsedDepartment;
    document.getElementById('d-grade').textContent = parsedGrade;
}

function closeApplicationDetail() {
    document.getElementById('appDetailPane').classList.remove('is-open');
}
</script>
