<?php
if (!defined('BASE_URL')) exit;

$statusLabels = [
    'pending' => 'Bekliyor',
    'reviewing' => 'İnceleniyor',
    'approved' => 'Onaylandı',
    'rejected' => 'Reddedildi',
];

$totalApplications = count($applications);
$managementCount = 0;
$representativeCount = 0;

foreach ($applications as $applicationItem) {
    if (($applicationItem['type'] ?? '') === 'management') {
        $managementCount++;
    } else {
        $representativeCount++;
    }
}
?>

<style>
.applications-v2 {
    display: flex;
    flex-direction: column;
    gap: 12px;
    min-height: 0;
}

.applications-v2-head {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 12px;
}

.applications-v2-title {
    margin: 0;
    color: var(--text);
    font-size: 18px;
    font-weight: 800;
    line-height: 1.2;
}

.applications-v2-subtitle {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 12px;
}

.applications-v2-count {
    flex: 0 0 auto;
    padding: 7px 10px;
    border: 1px solid var(--border);
    border-radius: 999px;
    background: var(--card);
    color: var(--text);
    font-size: 11px;
    font-weight: 700;
}

.applications-v2-layout {
    display: grid;
    grid-template-columns: minmax(280px, 370px) minmax(0, 1fr);
    gap: 12px;
    height: calc(100vh - 184px);
    min-height: 520px;
}

.applications-v2-list,
.applications-v2-detail {
    min-width: 0;
    min-height: 0;
    border: 1px solid var(--border);
    border-radius: 14px;
    background: var(--card);
    overflow: hidden;
}

.applications-v2-list {
    display: flex;
    flex-direction: column;
}

.applications-v2-toolbar {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 12px;
    border-bottom: 1px solid var(--border);
    background: var(--card-alt);
}

.applications-v2-toolbar-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    color: var(--text);
    font-size: 13px;
    font-weight: 800;
}

.applications-v2-filters {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 6px;
}

.applications-v2-filter {
    min-height: 32px;
    padding: 6px 7px;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: var(--input-bg);
    color: var(--text);
    cursor: pointer;
    font-size: 10px;
    font-weight: 700;
    line-height: 1.1;
}

.applications-v2-filter.is-active {
    border-color: var(--primary);
    background: var(--primary);
    color: #061019;
}

.applications-v2-cards {
    display: flex;
    flex: 1 1 auto;
    min-height: 0;
    flex-direction: column;
    gap: 8px;
    overflow-y: auto;
    padding: 10px;
}

.applications-v2-card {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 5px 9px;
    width: 100%;
    min-height: 62px;
    padding: 10px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--input-bg);
    color: inherit;
    cursor: pointer;
    text-align: left;
}

.applications-v2-card:hover,
.applications-v2-card.is-active {
    border-color: var(--primary);
    background: rgba(245, 158, 11, .08);
}

.applications-v2-card.is-hidden {
    display: none !important;
}

.applications-v2-card-name {
    min-width: 0;
    overflow: hidden;
    color: var(--text);
    font-size: 13px;
    font-weight: 800;
    line-height: 1.2;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.applications-v2-card-date {
    color: var(--muted);
    font-size: 10px;
    line-height: 1.2;
    white-space: nowrap;
}

.applications-v2-card-type {
    min-width: 0;
    overflow: hidden;
    color: var(--text-secondary);
    font-size: 11px;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.applications-v2-status {
    align-self: end;
    justify-self: end;
    padding: 3px 7px;
    border-radius: 999px;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .3px;
    line-height: 1;
    text-transform: uppercase;
    white-space: nowrap;
}

.status-pending { background: rgba(234, 179, 8, .15); color: #eab308; border: 1px solid rgba(234, 179, 8, .32); }
.status-reviewing { background: rgba(59, 130, 246, .15); color: #3b82f6; border: 1px solid rgba(59, 130, 246, .32); }
.status-approved { background: rgba(34, 197, 94, .15); color: #22c55e; border: 1px solid rgba(34, 197, 94, .32); }
.status-rejected { background: rgba(239, 68, 68, .15); color: #ef4444; border: 1px solid rgba(239, 68, 68, .32); }

.applications-v2-empty-list,
.applications-v2-empty-detail {
    display: flex;
    flex: 1 1 auto;
    min-height: 220px;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 8px;
    padding: 20px;
    color: var(--muted);
    text-align: center;
}

.applications-v2-empty-detail.is-hidden {
    display: none !important;
}

.applications-v2-empty-list i,
.applications-v2-empty-detail i {
    font-size: 34px;
    opacity: .3;
}

.applications-v2-detail {
    display: flex;
    flex-direction: column;
}

.applications-v2-detail-card {
    display: flex;
    flex: 1 1 auto;
    min-height: 0;
    flex-direction: column;
    background: var(--bg);
}

.applications-v2-detail-card.is-hidden {
    display: none !important;
}

.applications-v2-detail-head {
    flex: 0 0 auto;
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 9px;
    padding: 12px;
    border-bottom: 1px solid var(--border);
    background: var(--card);
}

.applications-v2-back {
    display: none;
}

.applications-v2-detail-name {
    margin: 0;
    overflow: hidden;
    color: var(--text);
    font-size: 17px;
    font-weight: 900;
    line-height: 1.2;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.applications-v2-detail-meta {
    margin-top: 3px;
    color: #60a5fa;
    font-size: 11px;
    font-weight: 700;
}

.applications-v2-actions {
    grid-column: 1 / -1;
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 8px;
}

.applications-v2-status-form {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 8px;
    margin: 0;
}

.applications-v2-delete-form {
    margin: 0;
}

.applications-v2-select {
    width: 100% !important;
    min-height: 34px;
    border: 1px solid var(--border) !important;
    border-radius: 10px !important;
    background: var(--input-bg) !important;
    color: var(--text) !important;
    font-size: 12px;
}

.applications-v2-action {
    min-height: 34px;
    padding: 6px 10px;
    border-radius: 10px !important;
    font-size: 11px !important;
    font-weight: 800 !important;
    white-space: nowrap;
}

.applications-v2-delete {
    border: 1px solid #dc2626 !important;
    background: #ef4444 !important;
    color: #fff !important;
}

.applications-v2-detail-body {
    flex: 1 1 auto;
    min-height: 0;
    overflow-y: auto;
    background: var(--bg);
}

.applications-v2-info-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 9px;
    padding: 12px;
}

.applications-v2-info {
    display: flex;
    min-width: 0;
    align-items: center;
    gap: 9px;
    padding: 10px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--card);
}

.applications-v2-info-icon {
    display: flex;
    flex: 0 0 30px;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border: 1px solid rgba(59, 130, 246, .25);
    border-radius: 50%;
    background: rgba(59, 130, 246, .10);
    color: #3b82f6;
    font-size: 12px;
}

.applications-v2-info-text {
    min-width: 0;
}

.applications-v2-info-label {
    margin-bottom: 2px;
    color: #3b82f6;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: .55px;
    line-height: 1.2;
    text-transform: uppercase;
}

.applications-v2-info-value {
    overflow: hidden;
    color: var(--text);
    font-size: 12px;
    font-weight: 800;
    line-height: 1.25;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.applications-v2-section-title {
    margin: 0;
    padding: 4px 12px 0;
    color: var(--text);
    font-size: 13px;
    font-weight: 900;
}

.applications-v2-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
    padding: 10px 12px 18px;
}

.applications-v2-field {
    min-width: 0;
    padding: 12px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--card);
}

.applications-v2-field.is-wide {
    grid-column: 1 / -1;
}

.applications-v2-field-label {
    margin: 0 0 7px;
    color: #3b82f6;
    font-size: 10px;
    font-weight: 900;
    letter-spacing: .7px;
    line-height: 1.25;
    text-transform: uppercase;
}

.applications-v2-field-value {
    margin: 0;
    color: var(--text);
    font-size: 12px;
    line-height: 1.55;
    white-space: pre-wrap;
}

@media (max-width: 900px) {
    .applications-v2 {
        gap: 10px;
    }

    .applications-v2-head {
        align-items: center;
    }

    .applications-v2-title {
        font-size: 18px;
    }

    .applications-v2-subtitle {
        display: none;
    }

    .applications-v2-layout {
        display: block;
        height: auto;
        min-height: 0;
        transform: none !important;
        animation: none !important;
    }

    .applications-v2-list {
        border-radius: 14px;
    }

    .applications-v2-card {
        min-height: 58px;
        padding: 9px 10px;
    }

    .applications-v2-detail {
        position: fixed;
        inset: 0;
        z-index: 5000;
        display: none;
        border: 0;
        border-radius: 0;
        background: var(--bg);
    }

    .applications-v2-detail.is-open {
        display: flex;
    }

    .applications-v2-empty-detail {
        display: none !important;
    }

    .applications-v2-detail-card {
        width: 100%;
        height: 100dvh;
        background: var(--bg);
    }

    .applications-v2-detail-head {
        grid-template-columns: auto minmax(0, 1fr);
        gap: 7px 9px;
        padding: max(8px, env(safe-area-inset-top)) 10px 8px;
    }

    .applications-v2-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        grid-column: 1;
        grid-row: 1;
        width: 34px;
        min-width: 34px;
        min-height: 34px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--input-bg);
        color: var(--text);
        cursor: pointer;
        font-size: 24px;
        line-height: 1;
    }

    .applications-v2-heading {
        grid-column: 2;
        grid-row: 1;
        min-width: 0;
    }

    .applications-v2-detail-name {
        font-size: 14px;
    }

    .applications-v2-actions {
        grid-column: 1 / -1;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 7px;
    }

    .applications-v2-status-form {
        gap: 7px;
    }

    .applications-v2-select,
    .applications-v2-action {
        min-height: 32px;
        font-size: 10px !important;
    }

    .applications-v2-info-grid {
        grid-template-columns: 1fr 1fr;
        gap: 7px;
        padding: 8px 10px;
    }

    .applications-v2-info {
        gap: 7px;
        padding: 7px;
        border-radius: 10px;
    }

    .applications-v2-info-icon {
        flex-basis: 26px;
        width: 26px;
        height: 26px;
        font-size: 10px;
    }

    .applications-v2-info-label {
        font-size: 8px;
    }

    .applications-v2-info-value {
        font-size: 10px;
    }

    .applications-v2-section-title {
        padding: 4px 10px 0;
        font-size: 12px;
    }

    .applications-v2-form-grid {
        grid-template-columns: 1fr;
        gap: 7px;
        padding: 8px 10px calc(18px + env(safe-area-inset-bottom));
    }

    .applications-v2-field {
        padding: 10px;
        border-radius: 10px;
    }

    .applications-v2-field-label {
        margin-bottom: 6px;
        font-size: 9px;
    }

    .applications-v2-field-value {
        font-size: 11px;
        line-height: 1.45;
    }

    body.applications-v2-detail-open {
        overflow: hidden !important;
    }
}
</style>

<div class="applications-v2">
    <header class="applications-v2-head">
        <div>
            <h3 class="applications-v2-title"><i class="fas fa-file-text text-primary me-2"></i>Başvurular</h3>
            <p class="applications-v2-subtitle">Temsilcilik ve Yönetim Kurulu başvurularını yönetin.</p>
        </div>
        <div class="applications-v2-count"><?= $totalApplications ?> başvuru</div>
    </header>

    <div class="applications-v2-layout">
        <section class="applications-v2-list" aria-label="Başvuru listesi">
            <div class="applications-v2-toolbar">
                <div class="applications-v2-toolbar-title">
                    <span>Gelen Başvurular</span>
                    <span><?= $totalApplications ?></span>
                </div>
                <div class="applications-v2-filters" role="tablist" aria-label="Başvuru filtresi">
                    <button type="button" class="applications-v2-filter is-active" data-filter="all">Tümü</button>
                    <button type="button" class="applications-v2-filter" data-filter="management">Yönetim <?= $managementCount ?></button>
                    <button type="button" class="applications-v2-filter" data-filter="representative">Temsilci <?= $representativeCount ?></button>
                </div>
            </div>

            <div class="applications-v2-cards" id="applicationsList">
                <?php if (empty($applications)): ?>
                    <div class="applications-v2-empty-list">
                        <i class="fas fa-inbox"></i>
                        <span>Henüz başvuru bulunmuyor.</span>
                    </div>
                <?php else: ?>
                    <?php foreach ($applications as $application): ?>
                        <?php
                            $isManagement = ($application['type'] ?? '') === 'management';
                            $typeLabel = $isManagement ? 'Yönetim Kurulu' : 'Temsilcilik';
                            $filterType = $isManagement ? 'management' : 'representative';
                            $statusKey = $application['status'] ?? 'pending';
                            $statusLabel = $statusLabels[$statusKey] ?? 'Bilinmiyor';
                            $applicationJson = htmlspecialchars(
                                json_encode($application, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP),
                                ENT_QUOTES,
                                'UTF-8'
                            );
                        ?>
                        <button
                            type="button"
                            class="applications-v2-card"
                            data-application-card
                            data-type="<?= htmlspecialchars($filterType, ENT_QUOTES, 'UTF-8') ?>"
                            data-application="<?= $applicationJson ?>"
                        >
                            <span class="applications-v2-card-name"><?= htmlspecialchars($application['full_name'] ?? '-') ?></span>
                            <span class="applications-v2-card-date"><?= !empty($application['created_at']) ? date('d.m.Y H:i', strtotime($application['created_at'])) : '-' ?></span>
                            <span class="applications-v2-card-type"><i class="fas fa-briefcase opacity-50"></i> <?= $typeLabel ?></span>
                            <span class="applications-v2-status status-<?= htmlspecialchars($statusKey, ENT_QUOTES, 'UTF-8') ?>"><?= $statusLabel ?></span>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <aside class="applications-v2-detail" id="applicationsDetailPanel" aria-label="Başvuru detayı">
            <div class="applications-v2-empty-detail" id="applicationsEmptyDetail">
                <i class="fas fa-file-signature"></i>
                <strong>Başvuru seçin</strong>
                <span>Detayları görüntülemek için listeden bir başvuru seçin.</span>
            </div>

            <div class="applications-v2-detail-card is-hidden" id="applicationsDetailCard">
                <div class="applications-v2-detail-head">
                    <button type="button" class="applications-v2-back" onclick="closeApplicationDetail()" aria-label="Başvuru listesine dön">‹</button>
                    <div class="applications-v2-heading">
                        <h3 class="applications-v2-detail-name" id="detailName">-</h3>
                        <div class="applications-v2-detail-meta" id="detailMeta">-</div>
                    </div>

                    <div class="applications-v2-actions">
                        <form method="POST" action="" class="applications-v2-status-form">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="action" value="status">
                            <input type="hidden" name="id" id="statusApplicationId">
                            <select name="status" class="form-select form-select-sm applications-v2-select" id="statusSelect">
                                <option value="pending">Bekliyor</option>
                                <option value="reviewing">İnceleniyor</option>
                                <option value="approved">Onaylandı</option>
                                <option value="rejected">Reddedildi</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary applications-v2-action">Güncelle</button>
                        </form>

                        <form method="POST" action="" class="applications-v2-delete-form" onsubmit="return confirm('Bu başvuruyu kalıcı olarak silmek istediğinize emin misiniz?');">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" id="deleteApplicationId">
                            <button type="submit" class="btn btn-sm applications-v2-action applications-v2-delete">
                                <i class="fas fa-trash me-1"></i> Sil
                            </button>
                        </form>
                    </div>
                </div>

                <div class="applications-v2-detail-body" id="applicationsDetailBody">
                    <div class="applications-v2-info-grid">
                        <div class="applications-v2-info">
                            <div class="applications-v2-info-icon"><i class="fas fa-envelope"></i></div>
                            <div class="applications-v2-info-text">
                                <div class="applications-v2-info-label">E-posta</div>
                                <div class="applications-v2-info-value" id="detailEmail">-</div>
                            </div>
                        </div>
                        <div class="applications-v2-info">
                            <div class="applications-v2-info-icon"><i class="fas fa-phone"></i></div>
                            <div class="applications-v2-info-text">
                                <div class="applications-v2-info-label">Telefon</div>
                                <div class="applications-v2-info-value" id="detailPhone">-</div>
                            </div>
                        </div>
                        <div class="applications-v2-info">
                            <div class="applications-v2-info-icon"><i class="fas fa-university"></i></div>
                            <div class="applications-v2-info-text">
                                <div class="applications-v2-info-label">Üniversite</div>
                                <div class="applications-v2-info-value" id="detailUniversity">-</div>
                            </div>
                        </div>
                        <div class="applications-v2-info">
                            <div class="applications-v2-info-icon"><i class="fas fa-building"></i></div>
                            <div class="applications-v2-info-text">
                                <div class="applications-v2-info-label">Fakülte</div>
                                <div class="applications-v2-info-value" id="detailFaculty">-</div>
                            </div>
                        </div>
                        <div class="applications-v2-info">
                            <div class="applications-v2-info-icon"><i class="fas fa-graduation-cap"></i></div>
                            <div class="applications-v2-info-text">
                                <div class="applications-v2-info-label">Bölüm</div>
                                <div class="applications-v2-info-value" id="detailDepartment">-</div>
                            </div>
                        </div>
                        <div class="applications-v2-info">
                            <div class="applications-v2-info-icon"><i class="fas fa-list-ol"></i></div>
                            <div class="applications-v2-info-text">
                                <div class="applications-v2-info-label">Sınıf</div>
                                <div class="applications-v2-info-value" id="detailGrade">-</div>
                            </div>
                        </div>
                    </div>

                    <h4 class="applications-v2-section-title"><i class="fas fa-list-ul me-2 text-primary"></i>Form Detayları</h4>
                    <div class="applications-v2-form-grid" id="applicationsFormData"></div>
                </div>
            </div>
        </aside>
    </div>
</div>

<script>
const APPLICATION_FIELD_LABELS = {
    city: 'Şehir / İlçe',
    district: 'İlçe',
    department: 'Okunan Bölüm',
    grade: 'Sınıf / Derece',
    studentId: 'Öğrenci Numarası',
    studentNumber: 'Öğrenci Numarası',
    birthDate: 'Doğum Tarihi',
    linkedin: 'LinkedIn',
    github: 'GitHub',
    portfolio: 'Portfolyo',
    instagram: 'Instagram',
    cv: 'CV',
    motivation: 'Motivasyon',
    experience: 'Deneyim',
    skills: 'Yetenekler',
    interests: 'İlgi Alanları',
    availability: 'Uygunluk',
    preferredRole: 'Tercih Edilen Rol',
    preferredDepartment: 'Tercih Edilen Departman',
    targetTeam: 'Hedef Ekip',
    targetDepartment: 'Hedef Departman',
    whyTeam: 'Bu Ekibi Neden İstiyorsunuz?',
    previousExperience: 'Önceki Deneyimler',
    clubExperience: 'Topluluk Deneyimi',
    leadershipExperience: 'Liderlik Deneyimi',
    teamworkExperience: 'Takım Çalışması Deneyimi',
    contribution: 'Katkı',
    goals: 'Hedefler',
    strengths: 'Güçlü Yönler',
    weaknesses: 'Gelişim Alanları',
    notes: 'Notlar',
    message: 'Mesaj',
    argeTools: 'Kullandığı Araçlar / Programlar',
    argePastProjectsDetails: 'Geçmiş Proje Detayları',
    argeContribution: 'Ar-Ge Ekibine Katkısı / Beklentisi',
    argeTubitak: 'TÜBİTAK vb. Proje Fikri',
    argeWebsiteCritique: 'Web Sitesi İncelemesi / Eleştirisi',
    whyInnomis: 'Neden İnnOmis?',
    expectations: 'Beklentiler / Hedefler'
};

const APPLICATION_IGNORED_FIELDS = new Set([
    'name',
    'email',
    'phone',
    'university',
    'faculty',
    'agreed',
    'securityCode',
    'department',
    'grade'
]);

document.addEventListener('DOMContentLoaded', () => {
    document.body.classList.remove('applications-v2-detail-open');

    const cards = Array.from(document.querySelectorAll('[data-application-card]'));
    const filters = Array.from(document.querySelectorAll('.applications-v2-filter'));

    filters.forEach((filterButton) => {
        filterButton.addEventListener('click', () => {
            const filter = filterButton.dataset.filter || 'all';

            filters.forEach((button) => button.classList.toggle('is-active', button === filterButton));

            cards.forEach((card) => {
                const isVisible = filter === 'all' || card.dataset.type === filter;
                card.classList.toggle('is-hidden', !isVisible);
                if (!isVisible) {
                    card.classList.remove('is-active');
                }
            });

            if (!document.querySelector('[data-application-card].is-active')) {
                resetApplicationDetail();
            }
        });
    });

    cards.forEach((card) => {
        card.addEventListener('click', () => {
            try {
                const application = JSON.parse(card.dataset.application || '{}');
                openApplicationDetail(application, card);
            } catch (error) {
                console.error('Başvuru verisi okunamadı:', error);
            }
        });
    });
});

function setApplicationText(id, value) {
    const element = document.getElementById(id);
    if (element) {
        element.textContent = value || '-';
        element.title = value || '-';
    }
}

function getApplicationTypeLabel(type) {
    return type === 'management' ? 'Yönetim Kurulu' : 'Temsilcilik';
}

function formatApplicationDate(value) {
    if (!value) return '-';
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return value;
    return date.toLocaleString('tr-TR');
}

function getReadableFieldLabel(key) {
    if (APPLICATION_FIELD_LABELS[key]) {
        return APPLICATION_FIELD_LABELS[key];
    }

    return key
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
        .replace(/\bcontribution\b/gi, 'Katkı')
        .split(' ')
        .map((word) => word === 'ID' ? word : word.charAt(0).toLocaleUpperCase('tr-TR') + word.slice(1))
        .join(' ');
}

function normalizeApplicationValue(value) {
    if (Array.isArray(value)) {
        return value.join(', ');
    }

    if (typeof value === 'boolean') {
        return value ? 'Evet' : 'Hayır';
    }

    if (value === null || typeof value === 'undefined') {
        return '';
    }

    return String(value);
}

function renderApplicationFormData(application) {
    const container = document.getElementById('applicationsFormData');
    if (!container) return { department: '-', grade: '-' };

    container.innerHTML = '';

    let parsedData = {};
    let parsedDepartment = '-';
    let parsedGrade = '-';

    if (application.form_data) {
        try {
            parsedData = JSON.parse(application.form_data);
        } catch (error) {
            parsedData = { raw: application.form_data };
        }
    }

    if (parsedData.department) {
        parsedDepartment = normalizeApplicationValue(parsedData.department);
    }

    if (parsedData.grade) {
        parsedGrade = normalizeApplicationValue(parsedData.grade);
    }

    let renderedCount = 0;

    Object.entries(parsedData).forEach(([key, rawValue]) => {
        const value = normalizeApplicationValue(rawValue);
        if (APPLICATION_IGNORED_FIELDS.has(key) || !value) return;

        const field = document.createElement('div');
        field.className = 'applications-v2-field';
        if (value.length > 60) {
            field.classList.add('is-wide');
        }

        const label = document.createElement('h5');
        label.className = 'applications-v2-field-label';
        label.textContent = key === 'raw' ? 'Form Verisi' : getReadableFieldLabel(key);

        const text = document.createElement('p');
        text.className = 'applications-v2-field-value';
        text.textContent = value;

        field.append(label, text);
        container.appendChild(field);
        renderedCount++;
    });

    if (renderedCount === 0) {
        const empty = document.createElement('div');
        empty.className = 'applications-v2-field is-wide';
        empty.innerHTML = '<h5 class="applications-v2-field-label">Detay</h5><p class="applications-v2-field-value">Görüntülenecek ek detay bulunmuyor.</p>';
        container.appendChild(empty);
    }

    return { department: parsedDepartment, grade: parsedGrade };
}

function openApplicationDetail(application, card) {
    document.querySelectorAll('[data-application-card]').forEach((item) => item.classList.remove('is-active'));
    card?.classList.add('is-active');

    const empty = document.getElementById('applicationsEmptyDetail');
    const detail = document.getElementById('applicationsDetailCard');
    const panel = document.getElementById('applicationsDetailPanel');

    empty?.classList.add('is-hidden');
    detail?.classList.remove('is-hidden');
    panel?.classList.add('is-open');
    document.body.classList.add('applications-v2-detail-open');

    const typeLabel = getApplicationTypeLabel(application.type);
    const dateLabel = formatApplicationDate(application.created_at);
    const parsed = renderApplicationFormData(application);

    setApplicationText('detailName', application.full_name || '-');
    setApplicationText('detailMeta', `${typeLabel} • ${dateLabel}`);
    setApplicationText('detailEmail', application.email || '-');
    setApplicationText('detailPhone', application.phone || '-');
    setApplicationText('detailUniversity', application.university || '-');
    setApplicationText('detailFaculty', application.faculty || '-');
    setApplicationText('detailDepartment', parsed.department);
    setApplicationText('detailGrade', parsed.grade);

    const statusId = document.getElementById('statusApplicationId');
    const deleteId = document.getElementById('deleteApplicationId');
    const statusSelect = document.getElementById('statusSelect');

    if (statusId) statusId.value = application.id || '';
    if (deleteId) deleteId.value = application.id || '';
    if (statusSelect) statusSelect.value = application.status || 'pending';

    document.getElementById('applicationsDetailBody')?.scrollTo({ top: 0, behavior: 'auto' });
}

function resetApplicationDetail() {
    document.getElementById('applicationsEmptyDetail')?.classList.remove('is-hidden');
    document.getElementById('applicationsDetailCard')?.classList.add('is-hidden');
    closeApplicationDetail();
}

function closeApplicationDetail() {
    document.getElementById('applicationsDetailPanel')?.classList.remove('is-open');
    document.body.classList.remove('applications-v2-detail-open');
}

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeApplicationDetail();
    }
});
</script>
