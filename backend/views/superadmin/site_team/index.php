<?php
$pageTitle = 'Site Ekibi';
$totalAdmins = 0;
$visibleAdmins = 0;

foreach ($groups as $group) {
    foreach ($group['members'] as $member) {
        $totalAdmins++;
        if (!empty($member['is_visible'])) {
            $visibleAdmins++;
        }
    }
}

$hiddenAdmins = max(0, $totalAdmins - $visibleAdmins);
?>

<style>
    .team-bridge {
        max-width: 1180px;
    }

    .team-hero {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 18px;
        align-items: end;
        margin-bottom: 18px;
    }

    .team-kicker {
        color: var(--accent);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .team-hero h2 {
        font-size: 26px;
        line-height: 1.1;
        margin: 0;
    }

    .team-hero p {
        color: var(--muted);
        margin: 10px 0 0;
        max-width: 720px;
        line-height: 1.5;
    }

    .team-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(120px, 1fr));
        gap: 10px;
        margin-bottom: 18px;
    }

    .team-stat {
        background: linear-gradient(180deg, rgba(255,255,255,.045), rgba(255,255,255,.018));
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 14px 16px;
    }

    .team-stat strong {
        display: block;
        color: var(--text);
        font-size: 24px;
        line-height: 1;
        margin-bottom: 6px;
    }

    .team-stat span {
        color: var(--muted);
        font-size: 12px;
    }

    .team-note {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        background: rgba(0,188,212,.07);
        border: 1px solid rgba(0,188,212,.2);
        border-radius: 14px;
        padding: 14px 16px;
        margin-bottom: 18px;
    }

    .team-note-mark {
        width: 28px;
        height: 28px;
        border-radius: 10px;
        background: var(--accent);
        color: #071112;
        display: grid;
        place-items: center;
        font-weight: 900;
        flex: 0 0 auto;
    }

    .team-note strong {
        color: var(--text);
    }

    .team-note p {
        margin: 3px 0 0;
        color: var(--muted);
        line-height: 1.45;
    }

    .team-groups {
        display: grid;
        gap: 14px;
    }

    .team-group {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
    }

    .team-group-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 16px 18px;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(90deg, rgba(255,255,255,.04), transparent);
    }

    .team-group-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .team-group-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        background: rgba(0,188,212,.1);
        color: var(--accent);
        font-weight: 800;
    }

    .team-group h3 {
        margin: 0;
        font-size: 17px;
    }

    .team-group-meta {
        color: var(--muted);
        font-size: 12px;
        margin-top: 3px;
    }

    .team-member-list {
        display: grid;
        gap: 8px;
        padding: 10px;
    }

    .team-member {
        display: grid;
        grid-template-columns: minmax(220px, 1.3fr) minmax(160px, .9fr) minmax(220px, 1fr) 88px 64px auto;
        gap: 14px;
        align-items: center;
        padding: 12px;
        border: 1px solid transparent;
        border-radius: 12px;
        background: rgba(255,255,255,.018);
    }

    .team-member:hover {
        border-color: rgba(0,188,212,.18);
        background: rgba(255,255,255,.035);
    }

    .team-person {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .team-avatar {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        border: 1px solid var(--border);
        background: #070707;
        color: var(--accent);
        display: grid;
        place-items: center;
        font-weight: 800;
        object-fit: cover;
        flex: 0 0 auto;
    }

    .team-name,
    .team-role-title {
        color: var(--text);
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .team-sub {
        color: var(--muted);
        font-size: 12px;
        margin-top: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .team-label {
        color: var(--muted);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 5px;
        display: none;
    }

    .team-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .team-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 22px;
        padding: 3px 8px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid var(--border);
        color: var(--text);
        background: rgba(255,255,255,.03);
    }

    .team-pill.super {
        border-color: rgba(245,158,11,.24);
        background: rgba(245,158,11,.12);
        color: #fbbf24;
    }

    .team-pill.visible {
        border-color: rgba(34,197,94,.24);
        background: rgba(34,197,94,.12);
        color: #86efac;
    }

    .team-pill.hidden {
        color: var(--muted);
        background: rgba(148,163,184,.08);
    }

    .team-order {
        color: var(--text);
        font-weight: 800;
    }

    .team-actions {
        display: flex;
        justify-content: flex-end;
    }

    .team-empty {
        border: 1px dashed var(--border);
        border-radius: 16px;
        padding: 36px;
        text-align: center;
        color: var(--muted);
    }

    .team-modal-toggle {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--bg);
    }

    .profile-modal-card {
        max-width: 980px !important;
        width: min(980px, calc(100vw - 40px));
        height: min(860px, calc(100vh - 48px));
        max-height: calc(100vh - 48px);
        padding: 0 !important;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .profile-modal-card form {
        display: flex;
        flex: 1 1 auto;
        min-height: 0;
        flex-direction: column;
    }

    .profile-modal-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(90deg, rgba(0,188,212,.1), rgba(255,255,255,.02));
        margin: 0 !important;
    }

    .profile-modal-title {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .profile-modal-avatar {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: rgba(0,188,212,.12);
        color: var(--accent);
        border: 1px solid rgba(0,188,212,.24);
        display: grid;
        place-items: center;
        font-weight: 900;
        flex: 0 0 auto;
    }

    .profile-modal-title h3 {
        margin: 0;
        color: var(--text);
    }

    .profile-modal-subtitle {
        color: var(--muted);
        font-size: 12px;
        margin-top: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .profile-modal-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        overscroll-behavior: contain;
        padding: 20px 24px 6px;
        scrollbar-width: thin;
        scrollbar-color: rgba(0,188,212,.45) rgba(255,255,255,.05);
    }

    .profile-modal-body::-webkit-scrollbar {
        width: 10px;
    }

    .profile-modal-body::-webkit-scrollbar-track {
        background: rgba(255,255,255,.04);
        border-radius: 999px;
    }

    .profile-modal-body::-webkit-scrollbar-thumb {
        background: rgba(0,188,212,.45);
        border: 2px solid transparent;
        border-radius: 999px;
        background-clip: padding-box;
    }

    .profile-modal-grid {
        display: grid;
        gap: 16px;
    }

    .profile-section {
        border: 1px solid var(--border);
        border-radius: 16px;
        background: rgba(255,255,255,.018);
        padding: 16px;
    }

    .profile-section-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
    }

    .profile-section-title {
        color: var(--text);
        font-size: 14px;
        font-weight: 800;
        margin: 0;
    }

    .profile-section-desc {
        color: var(--muted);
        font-size: 12px;
        line-height: 1.45;
        margin-top: 4px;
    }

    .profile-section .form-group {
        margin-bottom: 14px;
    }

    .profile-section .form-group:last-child {
        margin-bottom: 0;
    }

    .profile-section .form-control {
        min-height: 44px;
    }

    .profile-section textarea.form-control {
        min-height: 96px;
    }

    .profile-switch {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 12px;
        align-items: center;
        padding: 14px;
        border: 1px solid rgba(0,188,212,.22);
        border-radius: 14px;
        background: rgba(0,188,212,.07);
    }

    .profile-switch input {
        width: 18px;
        height: 18px;
        accent-color: var(--accent);
    }

    .profile-switch strong {
        display: block;
        color: var(--text);
        margin-bottom: 2px;
    }

    .profile-switch span {
        color: var(--muted);
        font-size: 12px;
    }

    .profile-history-list {
        display: grid;
        gap: 10px;
    }

    .profile-history-item {
        display: grid;
        grid-template-columns: 150px 1fr auto;
        gap: 10px;
        align-items: center;
        padding: 10px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: rgba(0,0,0,.16);
    }

    .profile-modal-footer {
        display: flex;
        flex: 0 0 auto;
        justify-content: flex-end;
        gap: 10px;
        padding: 16px 24px;
        border-top: 1px solid var(--border);
        background: var(--card);
        box-shadow: 0 -12px 28px rgba(0,0,0,.18);
    }

    .profile-modal-footer .btn {
        min-width: 130px;
        justify-content: center;
    }

    @media (max-width: 1200px) {
        .team-member {
            grid-template-columns: minmax(220px, 1.2fr) minmax(140px, .8fr) minmax(200px, 1fr) auto;
        }

        .team-member > .team-cell:nth-child(4),
        .team-member > .team-cell:nth-child(5) {
            display: none;
        }
    }

    @media (max-width: 760px) {
        .team-hero,
        .team-stats {
            grid-template-columns: 1fr;
        }

        .team-member {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .team-member > .team-cell:nth-child(4),
        .team-member > .team-cell:nth-child(5) {
            display: block;
        }

        .team-label {
            display: block;
        }

        .team-actions {
            justify-content: flex-start;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .profile-modal-card {
            width: calc(100vw - 20px);
            max-height: calc(100vh - 20px);
        }

        .profile-modal-head,
        .profile-modal-body,
        .profile-modal-footer {
            padding-left: 16px;
            padding-right: 16px;
        }

        .profile-history-item {
            grid-template-columns: 1fr;
        }

        .profile-modal-footer {
            flex-direction: column-reverse;
        }

        .profile-modal-footer .btn {
            width: 100%;
        }
    }
</style>

<div class="team-bridge">
    <div class="team-hero">
        <div>
            <div class="team-kicker">Ekip Köprüsü</div>
            <h2>Site Ekibi</h2>
            <p>Web sitesindeki ekip alanı backend adminlerinden oluşur. Adminleri departman altında görür, sitede kimin yayınlanacağına buradan karar verirsiniz.</p>
        </div>
        <a href="<?= BASE_URL ?>/superadmin/users" class="btn btn-outline">Adminleri Yönet</a>
    </div>

    <div class="team-stats">
        <div class="team-stat">
            <strong><?= $totalAdmins ?></strong>
            <span>Toplam admin</span>
        </div>
        <div class="team-stat">
            <strong><?= $visibleAdmins ?></strong>
            <span>Sitede görünen</span>
        </div>
        <div class="team-stat">
            <strong><?= $hiddenAdmins ?></strong>
            <span>Gizli profil</span>
        </div>
    </div>

    <div class="team-note">
        <div class="team-note-mark">i</div>
        <div>
            <strong>Global ekip kaynağı</strong>
            <p>Bir admini sitede göstermek için satırdaki <strong>Site Profili</strong> butonundan görünürlüğü açın. Profil bilgileri frontend ekip alanına otomatik düşer.</p>
        </div>
    </div>

    <?php if (empty($groups)): ?>
        <div class="team-empty">
            Henüz aktif admin veya departman ataması bulunmuyor.
        </div>
    <?php else: ?>
        <div class="team-groups">
            <?php foreach ($groups as $group): ?>
                <section class="team-group">
                    <div class="team-group-head">
                        <div class="team-group-title">
                            <div class="team-group-icon"><?= View::escape(mb_substr($group['name'], 0, 1, 'UTF-8')) ?></div>
                            <div>
                                <h3><?= View::escape($group['name']) ?></h3>
                                <div class="team-group-meta"><?= count($group['members']) ?> admin · departman grubu</div>
                            </div>
                        </div>
                        <span class="team-pill">Departman Grubu</span>
                    </div>

                    <div class="team-member-list">
                        <?php foreach ($group['members'] as $admin): ?>
                            <?php
                                $payload = [
                                    'user_id' => (int) $admin['user_id'],
                                    'department_id' => $admin['department_id'] === null ? '' : (int) $admin['department_id'],
                                    'user_name' => $admin['user_name'],
                                    'email' => $admin['email'],
                                    'department_name' => $admin['department_name'] ?? 'Yönetim',
                                    'display_name' => $admin['display_name'] ?? '',
                                    'display_role' => $admin['display_role'] ?? '',
                                    'display_department' => $admin['display_department'] ?? '',
                                    'display_type' => $admin['display_type'] ?? 'department',
                                    'image_url' => $admin['image_url'] ?? '',
                                    'linkedin' => $admin['linkedin'] ?? '',
                                    'github' => $admin['github'] ?? '',
                                    'bio' => $admin['bio'] ?? '',
                                    'phone' => $admin['phone'] ?? '',
                                    'university' => $admin['university'] ?? '',
                                    'faculty' => $admin['faculty'] ?? '',
                                    'school_department' => $admin['school_department'] ?? '',
                                    'grade' => $admin['grade'] ?? '',
                                    'website' => $admin['user_website'] ?? '',
                                    'university_logo' => $admin['user_university_logo'] ?? '',
                                    'company_logo' => $admin['user_company_logo'] ?? '',
                                    'community_history_json' => $admin['community_history_json'] ?? '[]',
                                    'is_visible' => (int) ($admin['is_visible'] ?? 0),
                                    'sort_order' => (int) ($admin['sort_order'] ?? 0),
                                ];
                            ?>
                            <article class="team-member">
                                <div class="team-person">
                                    <?php if (!empty($admin['image_url'])): ?>
                                        <img class="team-avatar" src="<?= View::escape($admin['image_url']) ?>" alt="Profil">
                                    <?php else: ?>
                                        <div class="team-avatar"><?= View::escape(mb_substr($admin['user_name'], 0, 1, 'UTF-8')) ?></div>
                                    <?php endif; ?>
                                    <div style="min-width:0;">
                                        <div class="team-name"><?= View::escape($admin['user_name']) ?></div>
                                        <div class="team-sub"><?= View::escape($admin['email']) ?></div>
                                    </div>
                                </div>

                                <div class="team-cell">
                                    <div class="team-label">Panel Rolü</div>
                                    <div class="team-badges">
                                        <?php if (!empty($admin['is_super_admin'])): ?>
                                            <span class="team-pill super">Süper Admin</span>
                                        <?php endif; ?>
                                        <span class="team-pill"><?= ($admin['department_role'] ?? '') === 'director' ? 'Direktör' : 'Üye' ?></span>
                                    </div>
                                </div>

                                <div class="team-cell">
                                    <div class="team-label">Site Görünümü</div>
                                    <div class="team-role-title"><?= View::escape($admin['display_role']) ?></div>
                                    <div class="team-sub">
                                        <?= View::escape($admin['display_department']) ?> · <?= View::escape($admin['display_type']) ?>
                                    </div>
                                </div>

                                <div class="team-cell">
                                    <div class="team-label">Durum</div>
                                    <?php if ($admin['is_visible']): ?>
                                        <span class="team-pill visible">Sitede</span>
                                    <?php else: ?>
                                        <span class="team-pill hidden">Gizli</span>
                                    <?php endif; ?>
                                </div>

                                <div class="team-cell">
                                    <div class="team-label">Sıra</div>
                                    <span class="team-order"><?= (int) $admin['sort_order'] ?></span>
                                </div>

                                <div class="team-actions">
                                    <button class="btn btn-sm btn-primary" onclick='editProfile(<?= json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                        Site Profili
                                    </button>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div id="profileModal" class="modal">
    <div class="modal-content profile-modal-card">
        <div class="modal-header profile-modal-head">
            <div class="profile-modal-title">
                <div class="profile-modal-avatar" id="profileModalAvatar">?</div>
                <div style="min-width:0;">
                    <h3 id="profileModalTitle">Site Profili</h3>
                    <div id="profileModalSubtitle" class="profile-modal-subtitle"></div>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('profileModal')">&times;</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/superadmin/site-team/profile">
            <div class="modal-body profile-modal-body">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="user_id" id="profile_user_id">
                <input type="hidden" name="department_id" id="profile_department_id">

                <div class="profile-modal-grid">
                    <section class="profile-section">
                        <label class="profile-switch" for="profile_is_visible">
                            <input type="checkbox" id="profile_is_visible" name="is_visible" value="1">
                            <span>
                                <strong>Bu admin web sitesindeki ekip alanında görünsün</strong>
                                <span>Kapalıysa bilgiler kayıtlı kalır ama sitede yayınlanmaz.</span>
                            </span>
                        </label>
                    </section>

                    <section class="profile-section">
                        <div class="profile-section-head">
                            <div>
                                <h4 class="profile-section-title">Sitede Görünen Kimlik</h4>
                                <div class="profile-section-desc">Ekip kartında ve detay modalında görünen temel unvan bilgileri.</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Görünen Ad</label>
                                <input type="text" name="display_name" id="profile_display_name" class="form-control" placeholder="Boşsa admin adı kullanılır">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ekip Tipi</label>
                                <select name="display_type" id="profile_display_type" class="form-control">
                                    <option value="board">Yönetim Kurulu</option>
                                    <option value="department">Departman Üyesi</option>
                                    <option value="campus">Kampüs Temsilcisi</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Sitedeki Ünvan</label>
                                <input type="text" name="display_role" id="profile_display_role" class="form-control" placeholder="Örn: Yazılım Direktörü">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Sitedeki Departman/Kampüs</label>
                                <input type="text" name="display_department" id="profile_display_department" class="form-control">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Fotoğraf URL</label>
                                <input type="text" name="image_url" id="profile_image_url" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Sıralama</label>
                                <input type="number" name="sort_order" id="profile_sort_order" class="form-control" value="0">
                            </div>
                        </div>
                    </section>

                    <section class="profile-section">
                        <div class="profile-section-head">
                            <div>
                                <h4 class="profile-section-title">İletişim ve Bağlantılar</h4>
                                <div class="profile-section-desc">Detay popupındaki e-posta dışındaki bağlantı ve iletişim alanları.</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Telefon</label>
                                <input type="text" name="phone" id="profile_phone" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Web Sitesi</label>
                                <input type="text" name="website" id="profile_website" class="form-control">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">LinkedIn URL</label>
                                <input type="url" name="linkedin" id="profile_linkedin" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">GitHub URL</label>
                                <input type="url" name="github" id="profile_github" class="form-control">
                            </div>
                        </div>
                    </section>

                    <section class="profile-section">
                        <div class="profile-section-head">
                            <div>
                                <h4 class="profile-section-title">Eğitim ve Logolar</h4>
                                <div class="profile-section-desc">Sitedeki profil detayında fakülte, bölüm, sınıf ve logo alanlarını doldurur.</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Üniversite</label>
                                <input type="text" name="university" id="profile_university" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Fakülte</label>
                                <input type="text" name="faculty" id="profile_faculty" class="form-control">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Okul Bölümü</label>
                                <input type="text" name="school_department" id="profile_school_department" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Sınıf</label>
                                <input type="text" name="grade" id="profile_grade" class="form-control">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Üniversite Logo URL</label>
                                <input type="text" name="university_logo" id="profile_university_logo" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Şirket / Kurum Logo URL</label>
                                <input type="text" name="company_logo" id="profile_company_logo" class="form-control">
                            </div>
                        </div>
                    </section>

                    <section class="profile-section">
                        <div class="profile-section-head">
                            <div>
                                <h4 class="profile-section-title">Biyografi ve Topluluk Geçmişi</h4>
                                <div class="profile-section-desc">Kişinin detay popupındaki açıklama ve kariyer geçmişi alanları.</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kısa Biyografi</label>
                            <textarea name="bio" id="profile_bio" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Topluluk Geçmişi</label>
                            <div id="profile_history_container" class="profile-history-list"></div>
                            <button type="button" class="btn btn-sm btn-outline mt-2" onclick="addHistoryField('profile_history_container')">+ Geçmiş Ekle</button>
                        </div>
                    </section>
                </div>
            </div>

            <div class="profile-modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('profileModal')">İptal</button>
                <button type="submit" class="btn btn-primary">Profili Kaydet</button>
            </div>
        </form>
    </div>
</div>

<script>
function escapeAttr(value) {
    return String(value || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

function addHistoryField(containerId, year = '', role = '') {
    const html = `
        <div class="profile-history-item">
            <input type="text" name="history_year[]" class="form-control" placeholder="Dönem / yıl" value="${escapeAttr(year)}">
            <input type="text" name="history_role[]" class="form-control" placeholder="Görev" value="${escapeAttr(role)}">
            <button type="button" class="btn btn-outline btn-sm" onclick="this.parentElement.remove()">Sil</button>
        </div>
    `;
    document.getElementById(containerId).insertAdjacentHTML('beforeend', html);
}

function editProfile(admin) {
    document.getElementById('profileModalTitle').textContent = admin.user_name;
    document.getElementById('profileModalSubtitle').textContent = `${admin.email} · ${admin.department_name}`;
    document.getElementById('profileModalAvatar').textContent = (admin.user_name || '?').trim().substring(0, 1).toUpperCase();
    document.getElementById('profile_user_id').value = admin.user_id;
    document.getElementById('profile_department_id').value = admin.department_id;
    document.getElementById('profile_is_visible').checked = Number(admin.is_visible) === 1;
    document.getElementById('profile_display_name').value = admin.display_name || '';
    document.getElementById('profile_display_role').value = admin.display_role || '';
    document.getElementById('profile_display_department').value = admin.display_department || admin.department_name || '';
    document.getElementById('profile_display_type').value = admin.display_type || 'department';
    document.getElementById('profile_image_url').value = admin.image_url || '';
    document.getElementById('profile_phone').value = admin.phone || '';
    document.getElementById('profile_website').value = admin.website || '';
    document.getElementById('profile_university').value = admin.university || '';
    document.getElementById('profile_faculty').value = admin.faculty || '';
    document.getElementById('profile_school_department').value = admin.school_department || '';
    document.getElementById('profile_grade').value = admin.grade || '';
    document.getElementById('profile_university_logo').value = admin.university_logo || '';
    document.getElementById('profile_company_logo').value = admin.company_logo || '';
    document.getElementById('profile_linkedin').value = admin.linkedin || '';
    document.getElementById('profile_github').value = admin.github || '';
    document.getElementById('profile_bio').value = admin.bio || '';
    document.getElementById('profile_sort_order').value = admin.sort_order || 0;

    const historyContainer = document.getElementById('profile_history_container');
    historyContainer.innerHTML = '';

    try {
        const history = JSON.parse(admin.community_history_json || '[]');
        if (Array.isArray(history) && history.length > 0) {
            history.forEach(item => addHistoryField('profile_history_container', item.year, item.role));
        } else {
            addHistoryField('profile_history_container');
        }
    } catch (e) {
        addHistoryField('profile_history_container');
    }

    openModal('profileModal');
}
</script>
