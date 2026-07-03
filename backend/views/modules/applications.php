<?php
if (!defined('BASE_URL')) exit;
// Data $applications is provided by ApplicationsModule
?>

<div class="applications-module-head" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h3 style="font-size: 16px; font-weight:600; color:var(--text);"><i class="fas fa-file-text text-primary me-2"></i> Başvurular</h3>
        <p style="font-size: 12px; color: var(--muted);">Temsilcilik ve Yönetim Kurulu başvurularını buradan yönetebilirsiniz.</p>
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
.json-field p.highlight {
    font-weight: 600;
    color: var(--primary);
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
    .applications-module-head {
        margin-bottom: 12px !important;
    }

    .applications-module-head h3 {
        margin: 0;
        font-size: 17px !important;
    }

    .applications-module-head p {
        display: none;
    }

    .app-container {
        display: block;
        height: auto;
        min-height: 0;
    }

    .app-list-pane {
        flex: none;
        max-height: none;
        border-radius: 14px;
    }

    .app-list-header {
        padding: 12px;
        gap: 10px !important;
    }

    .app-list-header h4 {
        font-size: 13px !important;
    }

    .app-list-header .d-flex.gap-2 {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 6px !important;
    }

    .app-filter-btn {
        min-height: 32px;
        padding: 6px 7px;
        border-radius: 9px;
        font-size: 10px;
    }

    .app-list-content {
        max-height: none;
        padding: 10px;
        gap: 8px;
    }

    .app-card {
        min-height: 76px;
        padding: 10px 11px;
        border-radius: 12px;
        gap: 5px;
    }

    .app-card:hover {
        transform: none;
        box-shadow: none;
    }

    .app-sender {
        max-width: 57vw;
        font-size: 12px;
    }

    .app-date {
        font-size: 10px;
    }

    .app-subject {
        font-size: 11px;
    }

    .app-badge {
        padding: 2px 7px;
        font-size: 9px;
    }

    .app-detail-pane {
        position: fixed;
        inset: 0;
        z-index: 1450;
        display: none;
        min-height: 0;
        border: 0;
        border-radius: 0;
        background: rgba(0,0,0,.58);
        padding: 10px;
        align-items: flex-end;
        justify-content: center;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    .app-detail-pane.mobile-open {
        display: flex;
    }

    .app-empty-state {
        display: none !important;
    }

    #detailContent {
        width: 100%;
        height: auto !important;
        max-height: calc(100dvh - 112px - env(safe-area-inset-bottom));
        overflow: hidden;
        border: 1px solid var(--border);
        border-radius: 16px 16px 12px 12px;
        background: var(--card);
    }

    .app-detail-header {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 10px;
        padding: 13px 14px;
        align-items: start;
    }

    .app-detail-header h3 {
        font-size: 15px !important;
        color: var(--text) !important;
    }

    #d-type-date {
        font-size: 11px !important;
    }

    .application-mobile-close {
        display: inline-flex !important;
        grid-column: 2;
        grid-row: 1;
        min-height: 32px;
        padding: 7px 10px;
        border-radius: 9px;
        font-size: 11px;
    }

    .app-detail-header > .d-flex {
        grid-column: 1 / -1;
        display: grid !important;
        grid-template-columns: 1fr auto;
        gap: 8px !important;
        width: 100%;
    }

    .app-detail-header form {
        width: 100%;
    }

    .app-detail-header .application-status-form {
        display: grid !important;
        grid-template-columns: 1fr auto;
        gap: 7px !important;
    }

    .status-select-custom {
        width: 100% !important;
        min-height: 34px;
        font-size: 11px;
    }

    .app-detail-header .btn {
        min-height: 34px;
        padding: 6px 9px !important;
        font-size: 10px;
        white-space: nowrap;
    }

    .app-detail-content {
        flex: 0 1 auto;
        max-height: calc(100dvh - 244px - env(safe-area-inset-bottom));
        overflow-y: auto;
    }

    .base-info-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        padding: 10px;
    }

    .info-item {
        min-width: 0;
        gap: 8px;
        padding: 8px;
        border: 1px solid var(--border);
        border-radius: 11px;
        background: var(--input-bg);
    }

    .info-item-icon {
        width: 28px;
        height: 28px;
        flex: 0 0 auto;
        font-size: 11px;
    }

    .info-item-text {
        min-width: 0;
    }

    .info-item-text div:first-child {
        font-size: 9px;
    }

    .info-item-text div:last-child {
        overflow: hidden;
        font-size: 11px;
        color: var(--text);
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .detail-grid {
        grid-template-columns: 1fr;
        gap: 9px;
        padding: 10px;
    }

    .app-detail-content .px-4 {
        padding: 8px 10px 4px !important;
    }

    .app-detail-content .px-4 h6 {
        font-size: 12px !important;
    }

    .json-field {
        padding: 12px;
        border-radius: 11px;
    }

    .json-field h6 {
        margin-bottom: 6px;
        font-size: 10px;
    }

    .json-field p {
        font-size: 12px;
        line-height: 1.55;
    }
}
.d-none { display: none !important; }
</style>

<div class="app-container fade-in-up stagger-1">
    <!-- Sol Liste -->
    <div class="app-list-pane">
        <div class="app-list-header d-flex flex-column gap-3">
            <h4 style="font-size: 14px; margin: 0; color: var(--text);">Gelen Başvurular (<?= count($applications) ?>)</h4>
            <div class="d-flex gap-2">
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
                    <div class="app-card" data-app-type="<?= $filterType ?>" onclick='showDetails(this, <?= json_encode($app, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
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
                <button type="button" class="application-mobile-close btn btn-outline" onclick="closeApplicationDetail()" style="display:none;">Kapat</button>
                <div class="d-flex gap-2 align-items-center">
                    <form method="POST" action="" id="statusForm" class="d-flex gap-2 align-items-center mb-0 application-status-form">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="hidden" name="action" value="status">
                        <input type="hidden" name="id" id="status_id">
                        <select name="status" class="form-select form-select-sm w-auto status-select-custom" id="status_select">
                            <option value="pending">Bekliyor</option>
                            <option value="reviewing">İnceleniyor</option>
                            <option value="approved">Onaylandı</option>
                            <option value="rejected">Reddedildi</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Güncelle</button>
                    </form>
                    <form method="POST" action="" onsubmit="return confirm('Bu başvuruyu kalıcı olarak silmek istediğinize emin misiniz?');" class="mb-0">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="hidden" name="action" value="delete">
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

function showDetails(cardEl, app) {
    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('detailContent').style.display = 'flex';
    document.getElementById('detailContent').classList.remove('d-none');
    
    document.querySelectorAll('.app-card').forEach(c => c.classList.remove('active'));
    if (cardEl) {
        cardEl.classList.add('active');
    }

    if (window.innerWidth <= 900) {
        document.getElementById('appDetailPane')?.classList.add('mobile-open');
        document.body.style.overflow = 'hidden';
    }

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
                'district': 'İlçe',
                'department': 'Okunan Bölüm',
                'grade': 'Sınıf / Derece',
                'studentId': 'Öğrenci Numarası',
                'studentNumber': 'Öğrenci Numarası',
                'birthDate': 'Doğum Tarihi',
                'linkedin': 'LinkedIn',
                'github': 'GitHub',
                'portfolio': 'Portfolyo',
                'instagram': 'Instagram',
                'cv': 'CV',
                'motivation': 'Motivasyon',
                'experience': 'Deneyim',
                'skills': 'Yetenekler',
                'interests': 'İlgi Alanları',
                'availability': 'Uygunluk',
                'preferredRole': 'Tercih Edilen Rol',
                'preferredDepartment': 'Tercih Edilen Departman',
                'targetTeam': 'Hedef Ekip',
                'targetDepartment': 'Hedef Departman',
                'whyTeam': 'Bu Ekibi Neden İstiyorsunuz?',
                'previousExperience': 'Önceki Deneyimler',
                'clubExperience': 'Topluluk Deneyimi',
                'leadershipExperience': 'Liderlik Deneyimi',
                'teamworkExperience': 'Takım Çalışması Deneyimi',
                'contribution': 'Katkı',
                'goals': 'Hedefler',
                'strengths': 'Güçlü Yönler',
                'weaknesses': 'Gelişim Alanları',
                'notes': 'Notlar',
                'message': 'Mesaj',
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
                    formattedKey = key
                        .replace(/([a-z])([A-Z])/g, '$1 $2')
                        .replace(/_/g, ' ')
                        .replace(/\bid\b/gi, 'ID')
                        .replace(/\burl\b/gi, 'Bağlantı')
                        .replace(/\blink\b/gi, 'Bağlantı')
                        .replace(/\bname\b/gi, 'Ad')
                        .replace(/\bemail\b/gi, 'E-posta')
                        .replace(/\bphone\b/gi, 'Telefon')
                        .replace(/\buniversity\b/gi, 'Üniversite')
                        .replace(/\bfaculty\b/gi, 'Fakülte')
                        .replace(/\bdepartment\b/gi, 'Bölüm')
                        .replace(/\bgrade\b/gi, 'Sınıf')
                        .replace(/\bteam\b/gi, 'Ekip')
                        .replace(/\brole\b/gi, 'Rol')
                        .replace(/\bwhy\b/gi, 'Neden')
                        .replace(/\btools\b/gi, 'Araçlar')
                        .replace(/\bproject\b/gi, 'Proje')
                        .replace(/\bprojects\b/gi, 'Projeler')
                        .replace(/\bdetails\b/gi, 'Detaylar')
                        .replace(/\bexperience\b/gi, 'Deneyim')
                        .replace(/\bskills\b/gi, 'Yetenekler')
                        .replace(/\bexpectations\b/gi, 'Beklentiler')
                        .replace(/\bcontribution\b/gi, 'Katkı');
                    formattedKey = formattedKey.split(' ').map(w => w === 'ID' ? w : w.charAt(0).toLocaleUpperCase('tr-TR') + w.slice(1)).join(' ');
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
    document.getElementById('appDetailPane')?.classList.remove('mobile-open');
    document.body.style.overflow = '';
}

document.getElementById('appDetailPane')?.addEventListener('click', function(e) {
    if (window.innerWidth <= 900 && e.target === this) {
        closeApplicationDetail();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeApplicationDetail();
    }
});
</script>
