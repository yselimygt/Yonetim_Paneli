<?php 
$pageTitle = 'Dashboard'; 
$activeSection = 'dashboard'; 

if (isset($members) && is_array($members)) {
    usort($members, function($a, $b) {
        $roles = [
            'president' => 4,
            'vice_president' => 3,
            'director' => 2,
            'coordinator' => 1
        ];
        $weightA = isset($roles[$a['role']]) ? $roles[$a['role']] : 0;
        $weightB = isset($roles[$b['role']]) ? $roles[$b['role']] : 0;
        return $weightB - $weightA;
    });
}
?>

<style>
    @media (max-width: 700px) {
        .panel-dashboard-welcome {
            margin-bottom: 16px !important;
        }

        .panel-dashboard-welcome h2 {
            font-size: 16px !important;
            line-height: 1.25;
        }

        .panel-dashboard-welcome p {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            font-size: 11px !important;
            line-height: 1.35;
        }

        .panel-dashboard-stats {
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px !important;
        }

        .panel-dashboard-stats > .stat-card {
            width: auto !important;
            min-width: 0 !important;
            padding: 14px !important;
            border-radius: 10px;
        }

        .panel-dashboard-stats .value {
            font-size: 24px !important;
        }

        .panel-dashboard-members-strip {
            grid-column: 1 / -1;
            min-width: 0 !important;
            padding: 12px !important;
            align-items: flex-start !important;
            gap: 12px;
        }

        .panel-dashboard-members-strip > div:first-child {
            font-size: 10px !important;
            letter-spacing: .4px !important;
        }

        .panel-dashboard-members-strip > div:last-child {
            max-width: 56%;
            overflow: hidden;
        }

        .panel-dashboard-main {
            display: grid !important;
            grid-template-columns: 1fr;
            gap: 16px !important;
            margin: 18px 0 22px !important;
        }

        .panel-dashboard-col,
        .panel-dashboard-calendar {
            min-width: 0 !important;
            width: 100%;
        }

        .panel-section-title {
            margin-bottom: 8px !important;
            font-size: 10px !important;
            letter-spacing: .5px !important;
        }

        .panel-announcement-item {
            padding: 13px !important;
            gap: 10px !important;
        }

        .panel-announcement-icon {
            width: 34px !important;
            height: 34px !important;
            border-radius: 9px !important;
        }

        .panel-announcement-item h4 {
            font-size: 13px !important;
            line-height: 1.25;
        }

        .panel-announcement-item p {
            display: -webkit-box;
            overflow: hidden;
            font-size: 11px !important;
            line-height: 1.35 !important;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .panel-announcement-meta {
            align-items: flex-start !important;
            flex-direction: column;
            gap: 5px;
            font-size: 10px !important;
        }

        .panel-month-summary {
            margin-top: 14px !important;
            padding: 14px !important;
        }

        .panel-calendar-wrap {
            display: grid !important;
            grid-template-columns: 1fr;
            gap: 12px !important;
        }

        .panel-calendar-grid-card,
        .panel-calendar-detail-card {
            min-width: 0 !important;
            padding: 14px !important;
        }

        .panel-calendar-grid-card [style*="gap: 10px"] {
            gap: 6px !important;
        }

        #cal-days-grid {
            gap: 6px !important;
        }

        #cal-days-grid > div {
            border-radius: 8px !important;
            font-size: 11px !important;
        }

        .panel-dashboard-apps {
            margin-bottom: 18px !important;
        }

        .panel-dashboard-apps-grid {
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 10px !important;
        }

        .panel-dashboard-app-card {
            min-height: 112px;
            align-items: flex-start !important;
            flex-direction: column;
            justify-content: space-between !important;
            padding: 13px !important;
            border-radius: 11px !important;
        }

        .panel-dashboard-app-card > div:first-child {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 9px !important;
            width: 100%;
        }

        .panel-dashboard-app-icon {
            width: 34px !important;
            height: 34px !important;
            border-radius: 9px !important;
        }

        .panel-dashboard-app-icon svg {
            width: 18px;
            height: 18px;
        }

        .panel-dashboard-app-card h3 {
            display: -webkit-box;
            overflow: hidden;
            font-size: 12px !important;
            line-height: 1.2;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .panel-dashboard-app-card p {
            display: none;
        }

        .panel-dashboard-app-card > div:last-child {
            align-self: flex-end;
            font-size: 11px !important;
        }

        .panel-members-table {
            overflow: hidden;
        }

        .panel-members-table table,
        .panel-members-table tbody,
        .panel-members-table tr,
        .panel-members-table td {
            display: block;
            width: 100%;
        }

        .panel-members-table thead {
            display: none;
        }

        .panel-members-table tr {
            padding: 12px;
            border-bottom: 1px solid var(--border);
        }

        .panel-members-table tr:last-child {
            border-bottom: 0;
        }

        .panel-members-table td {
            padding: 3px 0 !important;
            border: 0 !important;
        }

        .panel-members-table td:nth-child(2) {
            font-size: 11px !important;
        }
    }

    /* Departman Üyeleri Modal Styles */
    .members-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .members-modal-overlay.show {
        display: flex;
        opacity: 1;
    }
    .members-modal {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        width: 100%;
        max-width: 500px;
        max-height: 80vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transform: translateY(20px);
        transition: transform 0.3s ease;
    }
    .members-modal-overlay.show .members-modal {
        transform: translateY(0);
    }
    .members-modal-header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .members-modal-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: var(--text);
    }
    .members-modal-close {
        background: transparent;
        border: none;
        color: var(--muted);
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .members-modal-close:hover {
        background: var(--bg);
        color: var(--text);
    }
    .members-modal-body {
        padding: 0;
        overflow-y: auto;
    }
    .members-modal-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .members-modal-list li {
        padding: 12px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .members-modal-list li:last-child {
        border-bottom: none;
    }
    .member-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(59,130,246,0.1), rgba(16,185,129,0.1));
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: var(--text);
        font-size: 14px;
        flex-shrink: 0;
        border: 2px solid var(--card);
        object-fit: cover;
    }
    .member-info {
        flex-grow: 1;
        min-width: 0;
    }
    .member-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin: 0 0 2px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .member-email {
        font-size: 12px;
        color: var(--muted);
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .panel-dashboard-members-strip {
        transition: background 0.2s, transform 0.2s;
    }
    .panel-dashboard-members-strip:hover {
        background: var(--bg);
        transform: translateY(-2px);
    }
</style>

<!-- Hoş Geldiniz -->
<div class="panel-dashboard-welcome fade-in-up stagger-1" style="margin-bottom:28px;">
    <h2 style="font-size:20px;font-weight:600;margin-bottom:4px;">
        Hoş geldiniz, <?= View::escape(Auth::user()['name']) ?>
    </h2>
    <p style="font-size:13px;color:var(--muted)">
        <?= View::escape($currentDept['name']) ?> departmanı paneli
        <?php if ($currentDept['is_management']): ?>
            <span style="background:rgba(245,158,11,.1);color:#fbbf24;border:1px solid rgba(245,158,11,.2);border-radius:4px;padding:1px 8px;font-size:10px;margin-left:8px;vertical-align:middle;">
                YÖNETİM PANELİ
            </span>
        <?php endif; ?>
    </p>
</div>

<!-- İki Kolonlu Üst Kısım: Duyurular (Sol) & Takvim (Sağ) -->
<div class="panel-dashboard-main fade-in-up stagger-3" style="display:flex; flex-wrap:wrap; gap:24px; margin-bottom: 28px; margin-top: 28px; align-items:stretch;">
    
    <!-- Sol Kolon: Son Duyurular -->
    <div class="panel-dashboard-col" style="flex: 1; min-width: 320px; display: flex; flex-direction: column;">
        <div class="panel-section-title" style="font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">
            Son Duyurular
        </div>
        <div class="card" style="padding:0; overflow:hidden;">
            <?php if (empty($latestAnnouncements)): ?>
                <div style="padding: 24px; text-align:center; color:var(--muted); font-size:14px;">
                    Henüz yayınlanmış bir duyuru bulunmuyor.
                </div>
            <?php else: ?>
                <div style="display:flex; flex-direction:column;">
                    <?php foreach ($latestAnnouncements as $idx => $ann): ?>
                        <div class="panel-announcement-item" style="padding:16px 20px; border-bottom: <?= $idx === count($latestAnnouncements)-1 ? 'none' : '1px solid var(--border)' ?>; display:flex; align-items:flex-start; gap:16px;">
                            <div class="panel-announcement-icon" style="width:40px; height:40px; border-radius:10px; background:rgba(245,158,11,0.1); color:#f59e0b; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 17H2a3 3 0 000 3h20a3 3 0 000-3zM22 11H2M22 5H2"/></svg>
                            </div>
                            <div style="flex-grow:1;">
                                <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
                                    <h4 style="margin:0; font-size:15px; font-weight:600; color:var(--text);">
                                        <?= View::escape($ann['title']) ?>
                                    </h4>
                                    <?php if ($ann['is_global']): ?>
                                        <span style="background:rgba(245,158,11,.15); color:var(--warning); font-size:9px; padding:2px 6px; border-radius:4px; font-weight:700;">KÜRESEL</span>
                                    <?php endif; ?>
                                </div>
                                <p style="margin:0 0 8px 0; font-size:13px; color:var(--muted); line-height:1.5;">
                                    <?= nl2br(View::escape(mb_substr($ann['content'], 0, 150))) ?><?= mb_strlen($ann['content']) > 150 ? '...' : '' ?>
                                </p>
                                <div class="panel-announcement-meta" style="display:flex; align-items:center; justify-content:space-between; font-size:11px; color:var(--muted);">
                                    <span><?= View::escape($ann['author_name']) ?> &bull; <?= date('d.m.Y H:i', strtotime($ann['created_at'])) ?></span>
                                    <?php if ($ann['app_slug']): ?>
                                        <a href="<?= BASE_URL ?>/panel/<?= View::escape($currentDept['slug']) ?>/apps/<?= View::escape($ann['app_slug']) ?>" style="color:#3b82f6; text-decoration:none; font-weight:500;">Tümünü Gör &rarr;</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Alt Taraf: Ay Özeti (Duyurular Altına Taşındı) -->
        <div class="panel-month-summary card" style="margin-top:24px; padding:24px; background:rgba(236,72,153,0.03); border:1px solid rgba(236,72,153,0.1); flex-grow: 1; display: flex; flex-direction: column;">
            <div style="font-weight:600; font-size:15px; color:#ec4899; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Ay Özeti
            </div>
            <div id="cal-month-summary" style="display:flex; flex-direction:column; gap:8px; font-size:13px; color:var(--text);">
                <!-- JS ile Doldurulacak -->
            </div>
        </div>
    </div>

    <!-- Sağ Kolon: Takvim -->
    <div class="panel-dashboard-calendar" style="flex: 2; min-width: 500px; display: flex; flex-direction: column;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
            <div class="panel-section-title" style="font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:1px;">
                Takvim
            </div>
        </div>
        
        <div class="panel-calendar-wrap" style="display:flex; gap:20px; align-items:stretch; flex-wrap:wrap; flex-grow: 1;">
            <!-- Sol Taraf: Takvim Grid -->
            <div class="panel-calendar-grid-card card" style="flex:1; flex-basis:45%; min-width:250px; padding: 24px; display: flex; flex-direction: column; justify-content: space-between;">
                <div style="width: 100%; margin: 0 auto;">
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; text-align: center;">
                        <div style="font-weight:600; color:var(--muted); font-size:12px; padding-bottom:8px;">Pzt</div>
                        <div style="font-weight:600; color:var(--muted); font-size:12px; padding-bottom:8px;">Sal</div>
                        <div style="font-weight:600; color:var(--muted); font-size:12px; padding-bottom:8px;">Çar</div>
                        <div style="font-weight:600; color:var(--muted); font-size:12px; padding-bottom:8px;">Per</div>
                        <div style="font-weight:600; color:var(--muted); font-size:12px; padding-bottom:8px;">Cum</div>
                        <div style="font-weight:600; color:var(--muted); font-size:12px; padding-bottom:8px;">Cmt</div>
                        <div style="font-weight:600; color:var(--muted); font-size:12px; padding-bottom:8px;">Paz</div>
                    </div>
                    <div id="cal-days-grid" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; text-align: center; margin-top:8px;">
                        <!-- JS ile Doldurulacak -->
                    </div>
                </div>
                
                <div style="display:flex; gap:8px; align-items:center; justify-content: center; margin-top: 24px;">
                    <button id="cal-prev" style="background:var(--card); border:1px solid var(--border); color:var(--text); border-radius:6px; padding:4px 8px; cursor:pointer;">&larr;</button>
                    <span id="cal-month-year" style="font-size:14px; font-weight:600; color:var(--text); min-width:120px; text-align:center;"></span>
                    <button id="cal-next" style="background:var(--card); border:1px solid var(--border); color:var(--text); border-radius:6px; padding:4px 8px; cursor:pointer;">&rarr;</button>
                </div>
            </div>

            <!-- Sağ Taraf: Gün Detayları -->
            <div class="panel-calendar-detail-card card" style="flex:1; flex-basis:45%; min-width:250px; padding: 24px; display:flex; flex-direction:column; background:rgba(16,185,129,0.03); border:1px solid rgba(16,185,129,0.1);">
                <div style="font-weight:600; font-size:15px; color:#10b981; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span id="cal-detail-title">Gün Detayları</span>
                </div>
                <div id="cal-detail-content" style="flex-grow:1; display:flex; flex-direction:column; gap:12px; font-size:13px; color:var(--text);">
                    <!-- JS ile Doldurulacak -->
                </div>
            </div>
        </div>
        
        
    </div>
</div>

<!-- Stat kartları ve Departman Üyeleri -->
<div class="panel-dashboard-stats fade-in-up stagger-2" style="display:flex; flex-wrap:wrap; gap:12px; margin-bottom:28px;">
    <div class="stat-card" style="width: 140px; flex-shrink: 0; min-width: 120px;">
        <div class="value"><?= $memberCount ?></div>
        <div class="label">Üye</div>
    </div>
    <div class="stat-card" style="width: 140px; flex-shrink: 0; min-width: 120px;">
        <div class="value"><?= $appCount ?></div>
        <div class="label">Uygulama</div>
    </div>
    <?php if ($currentDept['is_management']): ?>
    <div class="stat-card" style="width: 140px; flex-shrink: 0; min-width: 120px; border-color:rgba(245,158,11,.2);">
        <div class="value" style="color:var(--warning)"><?= $totalUsers ?></div>
        <div class="label">Toplam Üye</div>
    </div>
    <div class="stat-card" style="width: 140px; flex-shrink: 0; min-width: 120px; border-color:rgba(245,158,11,.2);">
        <div class="value" style="color:var(--warning)"><?= $totalDepts ?></div>
        <div class="label">Departman</div>
    </div>
    <?php endif; ?>
    
    <!-- Departman Üyeleri -->
    <div class="panel-dashboard-members-strip stat-card" style="flex-grow: 1; min-width: 300px; display: flex; align-items: center; justify-content: space-between; padding: 12px 24px; cursor: pointer;" onclick="openMembersModal()">
        <div style="font-size:12px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:1px; display:flex; align-items:center; gap:8px;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            Departman Üyeleri
        </div>
        <div style="display:flex; align-items:center; padding-left:12px;">
            <?php foreach (array_slice($members, 0, 10) as $idx => $mem): ?>
                <?php if (!empty($mem['avatar'])): ?>
                    <img title="<?= View::escape($mem['user_name']) ?>" src="<?= BASE_URL . '/public/' . htmlspecialchars($mem['avatar']) ?>" style="width:36px; height:36px; border-radius:50%; object-fit:cover; border:2px solid var(--card); margin-left: <?= $idx === 0 ? '0' : '-12px' ?>; cursor:default; z-index: <?= 10 - $idx ?>; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'" alt="">
                <?php else: ?>
                    <div title="<?= View::escape($mem['user_name']) ?>" style="width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg, rgba(59,130,246,0.1), rgba(16,185,129,0.1)); color:var(--text); display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:600; border:2px solid var(--card); margin-left: <?= $idx === 0 ? '0' : '-12px' ?>; cursor:default; z-index: <?= 10 - $idx ?>; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                        <?= mb_strtoupper(mb_substr($mem['user_name'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if(count($members) > 10): ?>
                <div style="width:36px; height:36px; border-radius:50%; background:var(--border); color:var(--text); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; border:2px solid var(--card); margin-left:-12px; z-index: 0;">
                    +<?= count($members) - 10 ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Hızlı Erişim (Atanan Uygulamalar) -->
<?php if (!empty($deptApps)): ?>
<div class="panel-dashboard-apps fade-in-up stagger-4" style="margin-bottom: 24px;">
    <div class="panel-section-title" style="font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">
        Hızlı Erişim
    </div>
    <div class="panel-dashboard-apps-grid" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap:16px;">
        <?php 
        $renderedModules = [];
        $appStagger = 1;
        foreach ($deptApps as $app): 
            if ($app['module_type'] === 'form' && !in_array($app['slug'], ['form', 'form_manager'])) {
                continue;
            }

            $modGroup = $app['module_type'];
            if (in_array($modGroup, ['form', 'form_manager'])) $modGroup = 'form_grouped';
            
            if (in_array($modGroup, $renderedModules)) continue;
            $renderedModules[] = $modGroup;
        ?>
            <?php
            // Director-only kontrol
            $isDir = Auth::isDirectorOf($currentDept['slug']) || Auth::isSuperAdmin();
            if ($app['director_only'] && !$isDir) continue;

            $href = BASE_URL . "/panel/" . View::escape($currentDept['slug']) . "/apps/" . View::escape($app['slug']);
            if (in_array($app['module_type'], ['form', 'form_manager'])) {
                $href = BASE_URL . "/panel/" . View::escape($currentDept['slug']) . "/forms";
            } elseif ($app['module_type'] === 'finance') {
                $href = BASE_URL . "/panel/" . View::escape($currentDept['slug']) . "/finance";
            } elseif ($app['module_type'] === 'dues') {
                $href = BASE_URL . "/panel/" . View::escape($currentDept['slug']) . "/dues";
            } elseif ($app['module_type'] === 'social_links') {
                $href = BASE_URL . "/panel/" . View::escape($currentDept['slug']) . "/social-links";
            }

            // Colors based on module type
            $color = '#3b82f6'; // Mavi (Default)
            
            // People
            if (in_array($app['module_type'], ['members', 'members_public'])) $color = '#3b82f6';
            if ($app['module_type'] === 'directory') $color = '#8b5cf6';
            if (in_array($app['module_type'], ['alumni', 'alumni_admin'])) $color = '#6366f1';
            if ($app['module_type'] === 'mentorship') $color = '#ec4899';

            // Communication
            if ($app['module_type'] === 'applications') $color = '#f59e0b';
            if ($app['module_type'] === 'inbox') $color = '#3b82f6';
            if ($app['module_type'] === 'announcement') $color = '#f97316';
            if (in_array($app['module_type'], ['form', 'form_manager', 'form_grouped'])) $color = '#10b981';
            if ($app['module_type'] === 'sponsor') $color = '#eab308';
            if ($app['module_type'] === 'partner') $color = '#14b8a6';
            if ($app['module_type'] === 'social') $color = '#ec4899';

            // Events
            if ($app['module_type'] === 'calendar') $color = '#ec4899';
            if ($app['module_type'] === 'event') $color = '#f97316';
            if ($app['module_type'] === 'bootcamp') $color = '#6366f1';
            if ($app['module_type'] === 'meeting') $color = '#8b5cf6';
            if ($app['module_type'] === 'trophy') $color = '#eab308';

            // Knowledge
            if ($app['module_type'] === 'drive') $color = '#3b82f6';
            if ($app['module_type'] === 'wiki') $color = '#14b8a6';
            if (in_array($app['module_type'], ['media', 'media_admin'])) $color = '#ec4899';
            if (in_array($app['module_type'], ['links', 'links_admin', 'links_public'])) $color = '#6366f1';
            if ($app['module_type'] === 'certificate') $color = '#f59e0b';

            // Finance
            if ($app['module_type'] === 'finance') $color = '#22c55e';
            if ($app['module_type'] === 'dues') $color = '#ef4444';
            if (in_array($app['module_type'], ['budget', 'report'])) $color = '#10b981';

            // Operations
            if ($app['module_type'] === 'inventory') $color = '#8b5cf6';
            if ($app['module_type'] === 'passwords') $color = '#ef4444';
            if (in_array($app['module_type'], ['qr', 'qr_admin'])) $color = '#14b8a6';
            if (in_array($app['module_type'], ['shortener', 'shortener_admin'])) $color = '#f59e0b';

            // Project
            if ($app['module_type'] === 'agile') $color = '#0ea5e9';
            if ($app['module_type'] === 'ideahub') $color = '#f59e0b';

            // Governance
            if ($app['module_type'] === 'audit') $color = '#ef4444';
            if ($app['module_type'] === 'bylaws') $color = '#64748b';


            // Default Icon (Monitor)
            $svgPath = '<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>';

            // Icons based on module type
            switch ($app['module_type']) {
                case 'members':
                case 'members_public':
                case 'bylaws':
                    $svgPath = '<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'; // users
                    break;
                case 'directory':
                    $svgPath = '<path d="M17 18a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2"/><rect width="18" height="18" x="3" y="4" rx="2"/><circle cx="12" cy="10" r="2"/><line x1="8" x2="8" y1="2" y2="4"/><line x1="16" x2="16" y1="2" y2="4"/>'; // contact
                    break;
                case 'alumni':
                case 'alumni_admin':
                    $svgPath = '<path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>'; // graduation-cap
                    break;
                case 'mentorship':
                    $svgPath = '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>'; // heart
                    break;
                case 'applications':
                case 'form':
                case 'form_manager':
                case 'form_grouped':
                    $svgPath = '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>'; // file-text
                    break;
                case 'inbox':
                    $svgPath = '<polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>'; // inbox
                    break;
                case 'announcement':
                    $svgPath = '<path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>'; // megaphone
                    break;
                case 'sponsor':
                case 'partner':
                    $svgPath = '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>'; // briefcase
                    break;
                case 'social':
                    $svgPath = '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>'; // share-2
                    break;
                case 'calendar':
                case 'event':
                    $svgPath = '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'; // calendar
                    break;
                case 'bootcamp':
                    $svgPath = '<path d="M19 20 10 4"/><path d="m5 20 9-16"/><path d="M3 20h18"/><path d="m12 15-3 5"/><path d="m12 15 3 5"/>'; // tent
                    break;
                case 'meeting':
                    $svgPath = '<polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>'; // video
                    break;
                case 'trophy':
                case 'certificate':
                    $svgPath = '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>'; // award
                    break;
                case 'drive':
                    $svgPath = '<line x1="22" y1="12" x2="2" y2="12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/><line x1="6" y1="16" x2="6.01" y2="16"/><line x1="10" y1="16" x2="10.01" y2="16"/>'; // hard-drive
                    break;
                case 'wiki':
                    $svgPath = '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>'; // book-open
                    break;
                case 'media':
                case 'media_admin':
                    $svgPath = '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>'; // image
                    break;
                case 'links':
                case 'links_admin':
                case 'links_public':
                    $svgPath = '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>'; // link
                    break;
                case 'finance':
                case 'dues':
                    $svgPath = '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>'; // credit-card
                    break;
                case 'budget':
                    $svgPath = '<path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/>'; // pie-chart
                    break;
                case 'report':
                    $svgPath = '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>'; // bar-chart
                    break;
                case 'inventory':
                    $svgPath = '<line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>'; // package
                    break;
                case 'passwords':
                    $svgPath = '<path d="m15.5 7.5 2.3 2.3a1 1 0 0 0 1.4 0l2.1-2.1a1 1 0 0 0 0-1.4L19 4"/><path d="m21 2-9.6 9.6"/><circle cx="7.5" cy="15.5" r="5.5"/>'; // key
                    break;
                case 'qr':
                case 'qr_admin':
                    $svgPath = '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>'; // qr
                    break;
                case 'shortener':
                case 'shortener_admin':
                    $svgPath = '<circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><line x1="20" y1="4" x2="8.12" y2="15.88"/><line x1="14.47" y1="14.48" x2="20" y2="20"/><line x1="8.12" y1="8.12" x2="12" y2="12"/>'; // scissors
                    break;
                case 'agile':
                    $svgPath = '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><rect x="7" y="7" width="3" height="9"/><rect x="14" y="7" width="3" height="5"/>'; // trello (kanban)
                    break;
                case 'ideahub':
                    $svgPath = '<line x1="9" y1="18" x2="15" y2="18"/><line x1="10" y1="22" x2="14" y2="22"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A6 6 0 1 0 5.5 8c0 1.5.6 2.89 1.62 3.84c.64.6 1.05 1.34 1.25 2.16"/>'; // lightbulb
                    break;
                case 'audit':
                    $svgPath = '<path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M15 2H9a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z"/><polyline points="8 14 11 17 16 12"/>'; // clipboard-check
                    break;
            }

            // Explicit overrides if $app['icon'] matches standard names
            if (in_array($app['icon'], ['form', 'file-text'])) $svgPath = '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>';
            if ($app['icon'] === 'calendar') $svgPath = '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>';
            if ($app['icon'] === 'check-square') $svgPath = '<polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>';
            if ($app['icon'] === 'megaphone') $svgPath = '<path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>';
            if ($app['icon'] === 'bar-chart') $svgPath = '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>';
            if ($app['icon'] === 'users') $svgPath = '<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>';
            if ($app['icon'] === 'pie-chart') $svgPath = '<path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/>';
            if ($app['icon'] === 'credit-card') $svgPath = '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>';
            if ($app['icon'] === 'map-pin') $svgPath = '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>';
            if ($app['icon'] === 'book-open') $svgPath = '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>';
            if ($app['icon'] === 'award') $svgPath = '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>';
            if ($app['icon'] === 'archive') $svgPath = '<polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/>';
            if ($app['icon'] === 'book') $svgPath = '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>';
            if ($app['icon'] === 'briefcase') $svgPath = '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>';
            if ($app['icon'] === 'link') $svgPath = '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>';
            if ($app['icon'] === 'qr') $svgPath = '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>';
            if ($app['icon'] === 'scissors') $svgPath = '<circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><line x1="20" y1="4" x2="8.12" y2="15.88"/><line x1="14.47" y1="14.48" x2="20" y2="20"/><line x1="8.12" y1="8.12" x2="12" y2="12"/>';
            if ($app['icon'] === 'folder') $svgPath = '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>';
            if ($app['icon'] === 'layout') $svgPath = '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/>';
            if ($app['icon'] === 'lock') $svgPath = '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>';
            if ($app['icon'] === 'lightbulb') $svgPath = '<line x1="9" y1="18" x2="15" y2="18"/><line x1="10" y1="22" x2="14" y2="22"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A6 6 0 1 0 5.5 8c0 1.5.6 2.89 1.62 3.84c.64.6 1.05 1.34 1.25 2.16"/>';
            if ($app['icon'] === 'check-circle') $svgPath = '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>';
            if ($app['icon'] === 'user-plus') $svgPath = '<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>';
            ?>
            <a href="<?= $href ?>" 
               class="panel-dashboard-app-card fade-in-up stagger-<?= min($appStagger++, 5) ?>"
               style="display:flex; align-items:center; justify-content:space-between; background:var(--card); border:1px solid var(--border); border-radius:12px; padding:20px 24px; text-decoration:none; transition:all 0.2s;"
               onmouseover="this.style.borderColor='<?= $color ?>50'; this.style.background='<?= $color ?>10';" onmouseout="this.style.borderColor='var(--border)'; this.style.background='var(--card)';">
                <div style="display:flex; align-items:center; gap:16px;">
                    <div class="panel-dashboard-app-icon" style="width:48px; height:48px; border-radius:10px; background:<?= $color ?>20; display:flex; align-items:center; justify-content:center; color:<?= $color ?>;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><?= $svgPath ?></svg>
                    </div>
                    <div>
                        <h3 style="margin:0 0 4px 0; font-size:16px; color:var(--text);"><?= View::escape($app['module_type'] === 'form_manager' ? 'Form' : $app['name']) ?></h3>
                        <p style="margin:0; font-size:13px; color:var(--muted);"><?= View::escape(mb_substr($app['description'] ?? 'Uygulama', 0, 45)) ?>...</p>
                    </div>
                </div>
                <div style="color:<?= $color ?>; font-weight:600; font-size:16px;" aria-hidden="true">&rarr;</div>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php else: ?>
<div class="card">
    <div class="card-body">
        <div class="empty-state">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
            <p>Bu departmana henüz uygulama eklenmemiş.</p>
            <?php if (Auth::isSuperAdmin()): ?>
                <a href="<?= BASE_URL ?>/superadmin/apps" class="btn btn-primary btn-sm" style="margin-top:12px;">Uygulama Ekle</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    const calendarData = <?= $calendarData ?? '{"birthdays":[],"events":[]}' ?>;
    const trMonths = ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'];
    
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth(); 
    let currentYear = currentDate.getFullYear();
    let selectedDate = new Date(currentYear, currentMonth, currentDate.getDate());

    function renderCalendar() {
        document.getElementById('cal-month-year').innerText = trMonths[currentMonth] + ' ' + currentYear;
        
        const daysGrid = document.getElementById('cal-days-grid');
        daysGrid.innerHTML = '';
        
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        
        let startDay = firstDay === 0 ? 6 : firstDay - 1; 
        
        const emptyCellHtml = '<div style="background:var(--bg); border-radius:10px; aspect-ratio: 1/1; opacity:0.3;"></div>';
        
        for (let i = 0; i < startDay; i++) {
            daysGrid.innerHTML += emptyCellHtml;
        }
        
        for (let day = 1; day <= daysInMonth; day++) {
            const hasBirthdays = calendarData.birthdays.filter(b => b.month === currentMonth + 1 && b.day === day);
            
            const dateStr = currentYear + '-' + String(currentMonth + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
            const allEvents = calendarData.events.filter(e => e.date === dateStr);
            const hasHolidays = allEvents.filter(e => e.is_holiday);
            const hasExams = allEvents.filter(e => e.is_exam);
            const hasEvents = allEvents.filter(e => e.is_academic_event || (!e.is_holiday && !e.is_exam));
            
            // Check connections
            const prevDateStr = currentYear + '-' + String(currentMonth + 1).padStart(2, '0') + '-' + String(day - 1).padStart(2, '0');
            const nextDateStr = currentYear + '-' + String(currentMonth + 1).padStart(2, '0') + '-' + String(day + 1).padStart(2, '0');
            const prevEvents = calendarData.events.filter(e => e.date === prevDateStr);
            const nextEvents = calendarData.events.filter(e => e.date === nextDateStr);
            
            let connectLeft = false;
            let connectRight = false;
            
            if (hasHolidays.length > 0) {
                connectLeft = prevEvents.some(e => e.is_holiday && hasHolidays.some(h => h.title === e.title));
                connectRight = nextEvents.some(e => e.is_holiday && hasHolidays.some(h => h.title === e.title));
            } else if (hasExams.length > 0) {
                connectLeft = prevEvents.some(e => e.is_exam && hasExams.some(h => h.title === e.title));
                connectRight = nextEvents.some(e => e.is_exam && hasExams.some(h => h.title === e.title));
            } else if (hasEvents.length > 0) {
                connectLeft = prevEvents.some(e => (e.is_academic_event || (!e.is_holiday && !e.is_exam)) && hasEvents.some(h => h.title === e.title));
                connectRight = nextEvents.some(e => (e.is_academic_event || (!e.is_holiday && !e.is_exam)) && hasEvents.some(h => h.title === e.title));
            }
            
            let bg = 'var(--bg)';
            let color = 'var(--text)';
            let border = '1px solid transparent';
            let iconHtml = '';
            let bridgeHtml = '';
            
            const isToday = (day === currentDate.getDate() && currentMonth === currentDate.getMonth() && currentYear === currentDate.getFullYear());
            const isSelected = (day === selectedDate.getDate() && currentMonth === selectedDate.getMonth() && currentYear === selectedDate.getFullYear());
            
            if (isToday) border = '1px solid var(--text)';
            if (isSelected) border = '2px solid var(--accent)';
            
            let brTopLeft = '10px';
            let brBottomLeft = '10px';
            let brTopRight = '10px';
            let brBottomRight = '10px';
            
            if (connectLeft && day > 1) {
                brTopLeft = '0';
                brBottomLeft = '0';
            }
            if (connectRight && day < daysInMonth) {
                brTopRight = '0';
                brBottomRight = '0';
            }
            
            // User requested emojis only at the beginning (connectLeft = false) or end (connectRight = false)
            const showEmoji = (!connectLeft || !connectRight);
            
            let activeTypes = [];
            
            if (hasHolidays.length > 0) {
                activeTypes.push({
                    color: '#ea580c',
                    bgBase: 'rgba(249,115,22,0.15)',
                    bgFade: 'rgba(234,88,12,0.05)',
                    borderRgba: 'rgba(249,115,22,0.4)',
                    icon: hasHolidays[0].icon || '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800" width="16" height="11" style="vertical-align: middle; border-radius: 2px; display: inline-block;"><rect width="1200" height="800" fill="#E30A17"/><circle cx="425" cy="400" r="200" fill="#fff"/><circle cx="475" cy="400" r="160" fill="#E30A17"/><polygon points="800,400 644.25,450.45 693.4,304.5 561.6,400 725,495.5" fill="#fff"/></svg>',
                    isPrimary: true
                });
            }
            if (hasExams.length > 0) {
                activeTypes.push({
                    color: '#2563eb',
                    bgBase: 'rgba(59,130,246,0.15)',
                    bgFade: 'rgba(37,99,235,0.05)',
                    borderRgba: 'rgba(59,130,246,0.4)',
                    icon: '📝',
                    isPrimary: true
                });
            }
            if (hasEvents.length > 0) {
                activeTypes.push({
                    color: '#059669',
                    bgBase: 'rgba(16,185,129,0.15)',
                    bgFade: 'rgba(5,150,105,0.05)',
                    borderRgba: 'rgba(16,185,129,0.4)',
                    icon: '📅',
                    isPrimary: true
                });
            }
            if (hasBirthdays.length > 0) {
                activeTypes.push({
                    color: '#ec4899',
                    bgBase: 'rgba(236,72,153,0.15)',
                    bgFade: 'rgba(219,39,119,0.05)',
                    borderRgba: 'rgba(236,72,153,0.4)',
                    icon: '🎂',
                    isPrimary: false
                });
            }
            
            if (activeTypes.length === 1) {
                let t = activeTypes[0];
                bg = `linear-gradient(135deg, ${t.bgBase}, ${t.bgFade})`;
                baseColor = t.bgBase;
                bridgeColor = t.bgBase; // using base for bridge
                color = t.color;
                border = isSelected ? `2px solid ${t.color}` : `1px solid ${t.borderRgba}`;
                if (showEmoji || !t.isPrimary) {
                    iconHtml = `<div style="position:absolute; top:-4px; right:-4px; font-size:14px;">${t.icon}</div>`;
                }
            } else if (activeTypes.length > 1) {
                let stops = [];
                let step = 100 / activeTypes.length;
                let iconsArr = [];
                
                for (let i = 0; i < activeTypes.length; i++) {
                    let start = i * step;
                    let end = (i + 1) * step;
                    stops.push(`${activeTypes[i].bgBase} ${start}%`);
                    stops.push(`${activeTypes[i].bgBase} ${end}%`);
                    if (showEmoji || !activeTypes[i].isPrimary) {
                        iconsArr.push(`<span>${activeTypes[i].icon}</span>`);
                    }
                }
                
                bg = `linear-gradient(135deg, ${stops.join(', ')})`;
                color = 'var(--text)';
                border = isSelected ? '2px solid var(--accent)' : '1px solid rgba(150,150,150,0.3)';
                
                // Do not bridge when multiple events exist to prevent weird multi-color bridges
                baseColor = '';
                bridgeColor = '';
                connectLeft = false;
                connectRight = false;
                brTopLeft = '10px'; brBottomLeft = '10px'; brTopRight = '10px'; brBottomRight = '10px';
                
                if (iconsArr.length > 0) {
                    iconHtml = `<div style="position:absolute; top:-4px; right:-4px; font-size:12px; display:flex; gap:2px; flex-direction:column; align-items:flex-end;">${iconsArr.join('')}</div>`;
                }
            }
            
            // Visual bridges
            if (connectRight && day < daysInMonth && baseColor) {
                bridgeHtml += `<div style="position:absolute; top:-1px; right:-6px; bottom:-1px; width:6px; background:${bridgeColor}; border-top:1px solid ${border.split('solid ')[1]}; border-bottom:1px solid ${border.split('solid ')[1]}; z-index:1;"></div>`;
            }
            
            if (connectLeft && day > 1) {
                border = border.replace('solid ', 'solid transparent'); // don't draw left border
                // We'll fix the border via inline style specifically if needed
            }
            if (connectRight && day < daysInMonth) {
                // don't draw right border natively
            }
            
            const fontWeight = (hasBirthdays.length > 0 || hasEvents.length > 0 || hasHolidays.length > 0 || hasExams.length > 0) ? '700' : '500';
            const cursor = 'pointer';
            
            const cell = document.createElement('div');
            cell.style.cssText = `background:${bg}; color:${color}; border:${border}; border-radius:${brTopLeft} ${brTopRight} ${brBottomRight} ${brBottomLeft}; aspect-ratio: 1/1; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:${fontWeight}; position:relative; transition: transform 0.2s; cursor:${cursor}; box-sizing:border-box;`;
            
            if (connectLeft && day > 1 && baseColor) {
                cell.style.borderLeft = 'none';
            }
            if (connectRight && day < daysInMonth && baseColor) {
                cell.style.borderRight = 'none';
            }
            
            cell.innerHTML = `<span>${day}</span>${iconHtml}${bridgeHtml}`;
            
            cell.onmouseover = () => { if (!connectLeft && !connectRight) cell.style.transform = 'translateY(-2px)' };
            cell.onmouseout = () => { if (!connectLeft && !connectRight) cell.style.transform = 'translateY(0)' };
            
            cell.onclick = () => {
                selectedDate = new Date(currentYear, currentMonth, day);
                renderCalendar();
                renderDayDetails();
            };
            
            daysGrid.appendChild(cell);
        }
        
        const totalCells = startDay + daysInMonth;
        const targetCells = 42; // Sabit 6 satır (6x7 = 42 kutucuk)
        for (let i = totalCells; i < targetCells; i++) {
            daysGrid.innerHTML += emptyCellHtml;
        }
        
        renderMonthSummary();
    }
    
    function renderDayDetails() {
        const titleEl = document.getElementById('cal-detail-title');
        const contentEl = document.getElementById('cal-detail-content');
        
        const day = selectedDate.getDate();
        const month = selectedDate.getMonth();
        const year = selectedDate.getFullYear();
        
        titleEl.innerText = `${day} ${trMonths[month]} ${year}`;
        contentEl.innerHTML = '';
        
        const bdays = calendarData.birthdays.filter(b => b.month === month + 1 && b.day === day);
        const dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
        const allEvts = calendarData.events.filter(e => e.date === dateStr);
        const evts = allEvts.filter(e => !e.is_holiday);
        const holidays = allEvts.filter(e => e.is_holiday);
        
        if (bdays.length === 0 && allEvts.length === 0) {
            contentEl.innerHTML = '<div style="color:var(--muted); text-align:center; padding:20px 0;">Bu güne ait detay bulunmuyor.</div>';
            return;
        }
        
        if (holidays.length > 0) {
            const block = document.createElement('div');
            block.style.cssText = 'padding:10px; background:rgba(249,115,22,0.1); border-radius:8px; border:1px solid rgba(249,115,22,0.2); margin-bottom:10px;';
            block.innerHTML = `<div style="font-weight:600; color:#ea580c; margin-bottom:6px;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800" width="16" height="11" style="vertical-align: middle; border-radius: 2px; display: inline-block;"><rect width="1200" height="800" fill="#E30A17"/><circle cx="425" cy="400" r="200" fill="#fff"/><circle cx="475" cy="400" r="160" fill="#E30A17"/><polygon points="800,400 644.25,450.45 693.4,304.5 561.6,400 725,495.5" fill="#fff"/></svg> Resmi Tatiller</div>`;
            holidays.forEach(h => {
                let hIcon = h.icon || '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800" width="16" height="11" style="vertical-align: middle; border-radius: 2px; display: inline-block;"><rect width="1200" height="800" fill="#E30A17"/><circle cx="425" cy="400" r="200" fill="#fff"/><circle cx="475" cy="400" r="160" fill="#E30A17"/><polygon points="800,400 644.25,450.45 693.4,304.5 561.6,400 725,495.5" fill="#fff"/></svg>';
                block.innerHTML += `<div style="margin-bottom:6px;">
                    <div style="font-weight:600;">${hIcon} ${h.title}</div>
                    <div style="font-size:11px; color:var(--muted);">${h.category || ''}</div>
                </div>`;
            });
            contentEl.appendChild(block);
        }
        
        if (bdays.length > 0) {
            const block = document.createElement('div');
            block.style.cssText = 'padding:10px; background:rgba(236,72,153,0.1); border-radius:8px; border:1px solid rgba(236,72,153,0.2); margin-bottom:10px;';
            block.innerHTML = `<div style="font-weight:600; color:#ec4899; margin-bottom:6px;">🎂 Doğum Günleri</div>`;
            bdays.forEach(b => {
                block.innerHTML += `<div>&bull; ${b.name}</div>`;
            });
            contentEl.appendChild(block);
        }
        
        if (evts.length > 0) {
            const block = document.createElement('div');
            block.style.cssText = 'padding:10px; background:rgba(59,130,246,0.1); border-radius:8px; border:1px solid rgba(59,130,246,0.2);';
            block.innerHTML = `<div style="font-weight:600; color:#3b82f6; margin-bottom:6px;">📅 Etkinlikler</div>`;
            evts.forEach(e => {
                let timeStr = e.time ? e.time.substring(0,5) + ' - ' : '';
                block.innerHTML += `<div style="margin-bottom:6px;">
                    <div style="font-weight:600;">${timeStr}${e.title}</div>
                    <div style="font-size:11px; color:var(--muted);">${e.category || ''}</div>
                </div>`;
            });
            contentEl.appendChild(block);
        }
    }
    
    function renderMonthSummary() {
        const summaryEl = document.getElementById('cal-month-summary');
        summaryEl.innerHTML = '';
        
        const bdays = calendarData.birthdays.filter(b => b.month === currentMonth + 1);
        const evts = calendarData.events.filter(e => {
            if(!e.date) return false;
            const parts = e.date.split('-');
            return parseInt(parts[0]) === currentYear && parseInt(parts[1]) === currentMonth + 1;
        });
        
        bdays.sort((a,b) => a.day - b.day);
        evts.sort((a,b) => parseInt(a.date.split('-')[2]) - parseInt(b.date.split('-')[2]));
        
        if (bdays.length === 0 && evts.length === 0) {
            summaryEl.innerHTML = '<div style="color:var(--muted);">Bu ay için özet bulunmuyor.</div>';
            return;
        }
        
        const grid = document.createElement('div');
        grid.style.cssText = 'display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:20px;';
        
        if (bdays.length > 0) {
            const col = document.createElement('div');
            col.innerHTML = '<div style="font-weight:600; color:#ec4899; margin-bottom:8px;">🎂 Bu Ay Doğanlar</div>';
            const list = document.createElement('div');
            list.style.cssText = 'display:flex; flex-direction:column; gap:6px;';
            bdays.forEach(b => {
                list.innerHTML += `<div><span style="color:var(--muted); margin-right:8px; display:inline-block; width:20px;">${b.day}</span> ${b.name}</div>`;
            });
            col.appendChild(list);
            grid.appendChild(col);
        }
        
        if (evts.length > 0) {
            const col = document.createElement('div');
            col.innerHTML = '<div style="font-weight:600; color:#3b82f6; margin-bottom:8px;">📅 Bu Ayki Etkinlikler</div>';
            const list = document.createElement('div');
            list.style.cssText = 'display:flex; flex-direction:column; gap:6px;';
            const seenTitles = new Set();
            evts.forEach(e => {
                if (seenTitles.has(e.title)) return;
                seenTitles.add(e.title);
                const day = parseInt(e.date.split('-')[2]);
                let timeStr = e.time ? e.time.substring(0,5) + ' ' : '';
                list.innerHTML += `<div><span style="color:var(--muted); margin-right:8px; display:inline-block; width:20px;">${day}</span> ${timeStr}<b>${e.title}</b></div>`;
            });
            col.appendChild(list);
            grid.appendChild(col);
        }
        
        summaryEl.appendChild(grid);
    }
    
    document.getElementById('cal-prev').onclick = () => {
        currentMonth--;
        if(currentMonth < 0) { currentMonth = 11; currentYear--; }
        renderCalendar();
    };
    
    document.getElementById('cal-next').onclick = () => {
        currentMonth++;
        if(currentMonth > 11) { currentMonth = 0; currentYear++; }
        renderCalendar();
    };
    
    // Initial Render
    renderCalendar();
    renderDayDetails(); // Bugünün detayını göster
</script>

<!-- Departman Üyeleri (Direktör veya Yönetim) -->
<?php if ($userRole === 'director' || $currentDept['is_management']): ?>
<div style="margin-top:28px;">
    <div style="font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">
        Departman Üyeleri
    </div>
    <div class="panel-members-table card">
        <table>
            <thead><tr><th>Ad</th><th>E-posta</th><th>Rol</th><th>Durum</th></tr></thead>
            <tbody>
            <?php if (empty($members)): ?>
                <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:24px">Üye yok</td></tr>
            <?php else: ?>
                <?php foreach ($members as $m): ?>
                <tr>
                    <td style="font-weight:500"><?= View::escape($m['user_name']) ?></td>
                    <td style="color:var(--muted);font-size:12px"><?= View::escape($m['email']) ?></td>
                    <td>
                        <?php
                        $badgeClass = 'badge-member';
                        $roleName = 'Üye';
                        if ($m['role'] === 'director') {
                            $badgeClass = 'badge-director';
                            $roleName = 'Direktör';
                        } elseif ($m['role'] === 'president') {
                            $badgeClass = 'badge-president';
                            $roleName = 'Başkan';
                        } elseif ($m['role'] === 'vice_president') {
                            $badgeClass = 'badge-vice-president';
                            $roleName = 'Başkan Yardımcısı';
                        }
                        ?>
                        <span class="badge <?= $badgeClass ?>">
                            <?= $roleName ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?= $m['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                            <?= $m['is_active'] ? 'Aktif' : 'Pasif' ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Departman Üyeleri Modal -->
<div id="membersModal" class="members-modal-overlay" onclick="if(event.target === this) closeMembersModal()">
    <div class="members-modal">
        <div class="members-modal-header">
            <h3>Departman Üyeleri</h3>
            <button class="members-modal-close" onclick="closeMembersModal()">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="members-modal-body">
            <?php if (empty($members)): ?>
                <div style="padding: 24px; text-align: center; color: var(--muted);">Üye bulunamadı.</div>
            <?php else: ?>
                <ul class="members-modal-list">
                    <?php foreach ($members as $m): ?>
                        <li>
                            <?php if (!empty($m['avatar'])): ?>
                                <img src="<?= BASE_URL . '/public/' . htmlspecialchars($m['avatar']) ?>" class="member-avatar" alt="">
                            <?php else: ?>
                                <div class="member-avatar"><?= mb_strtoupper(mb_substr($m['user_name'], 0, 1)) ?></div>
                            <?php endif; ?>
                            <div class="member-info">
                                <p class="member-name"><?= View::escape($m['user_name']) ?></p>
                                <p class="member-email"><?= View::escape($m['email']) ?></p>
                            </div>
                            <div>
                                <?php
                                $badgeClass = 'badge-member';
                                $roleName = 'Üye';
                                if ($m['role'] === 'director') {
                                    $badgeClass = 'badge-director';
                                    $roleName = 'Direktör';
                                } elseif ($m['role'] === 'president') {
                                    $badgeClass = 'badge-president';
                                    $roleName = 'Başkan';
                                } elseif ($m['role'] === 'vice_president') {
                                    $badgeClass = 'badge-vice-president';
                                    $roleName = 'Başkan Yardımcısı';
                                }
                                ?>
                                <span class="badge <?= $badgeClass ?>" style="font-size: 10px; padding: 2px 6px;">
                                    <?= $roleName ?>
                                </span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function openMembersModal() {
    const modal = document.getElementById('membersModal');
    modal.style.display = 'flex';
    // Trigger reflow
    void modal.offsetWidth;
    modal.classList.add('show');
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function closeMembersModal() {
    const modal = document.getElementById('membersModal');
    modal.classList.remove('show');
    setTimeout(() => {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }, 300);
}
</script>
