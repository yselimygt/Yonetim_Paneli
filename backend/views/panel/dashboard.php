<?php $pageTitle = 'Dashboard'; $activeSection = 'dashboard'; ?>

<!-- Hoş Geldiniz -->
<div class="fade-in-up stagger-1" style="margin-bottom:28px;">
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

<!-- Stat kartları -->
<div class="stats-grid fade-in-up stagger-2">
    <div class="stat-card">
        <div class="value"><?= $memberCount ?></div>
        <div class="label">Üye</div>
    </div>
    <div class="stat-card">
        <div class="value"><?= $appCount ?></div>
        <div class="label">Uygulama</div>
    </div>
    <?php if ($currentDept['is_management']): ?>
    <div class="stat-card" style="border-color:rgba(245,158,11,.2);">
        <div class="value" style="color:var(--warning)"><?= $totalUsers ?></div>
        <div class="label">Toplam Üye</div>
    </div>
    <div class="stat-card" style="border-color:rgba(245,158,11,.2);">
        <div class="value" style="color:var(--warning)"><?= $totalDepts ?></div>
        <div class="label">Departman</div>
    </div>
    <?php endif; ?>
</div>
<!-- İki Kolonlu Üst Kısım: Duyurular (Sol) & Takvim (Sağ) -->
<div class="fade-in-up stagger-3" style="display:flex; flex-wrap:wrap; gap:24px; margin-bottom: 28px; margin-top: 28px; align-items:stretch;">
    
    <!-- Sol Kolon: Son Duyurular -->
    <div style="flex: 1; min-width: 320px; display: flex; flex-direction: column;">
        <div style="font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">
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
                        <div style="padding:16px 20px; border-bottom: <?= $idx === count($latestAnnouncements)-1 ? 'none' : '1px solid var(--border)' ?>; display:flex; align-items:flex-start; gap:16px;">
                            <div style="width:40px; height:40px; border-radius:10px; background:rgba(245,158,11,0.1); color:#f59e0b; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
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
                                <div style="display:flex; align-items:center; justify-content:space-between; font-size:11px; color:var(--muted);">
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
        <div class="card" style="margin-top:24px; padding:24px; background:rgba(236,72,153,0.03); border:1px solid rgba(236,72,153,0.1); flex-grow: 1; display: flex; flex-direction: column;">
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
    <div style="flex: 2; min-width: 500px; display: flex; flex-direction: column;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
            <div style="font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:1px;">
                Takvim
            </div>
            <div style="display:flex; gap:8px; align-items:center;">
                <button id="cal-prev" style="background:var(--card); border:1px solid var(--border); color:var(--text); border-radius:6px; padding:4px 8px; cursor:pointer;">&larr;</button>
                <span id="cal-month-year" style="font-size:14px; font-weight:600; color:var(--text); min-width:120px; text-align:center;"></span>
                <button id="cal-next" style="background:var(--card); border:1px solid var(--border); color:var(--text); border-radius:6px; padding:4px 8px; cursor:pointer;">&rarr;</button>
            </div>
        </div>
        
        <div style="display:flex; gap:20px; align-items:stretch; flex-wrap:wrap; flex-grow: 1;">
            <!-- Sol Taraf: Takvim Grid -->
            <div class="card" style="flex:1; flex-basis:45%; min-width:250px; padding: 24px;">
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
            </div>

            <!-- Sağ Taraf: Gün Detayları -->
            <div class="card" style="flex:1; flex-basis:45%; min-width:250px; padding: 24px; display:flex; flex-direction:column; background:rgba(16,185,129,0.03); border:1px solid rgba(16,185,129,0.1);">
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

<!-- Hızlı Erişim (Atanan Uygulamalar) -->
<?php if (!empty($deptApps)): ?>
<div class="fade-in-up stagger-4" style="margin-bottom: 24px;">
    <div style="font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">
        Hızlı Erişim
    </div>
    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap:16px;">
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
            }

            // Colors based on module
            $color = '#3b82f6';
            if ($app['module_type'] === 'report') $color = '#10b981';
            if ($app['module_type'] === 'announcement') $color = '#f59e0b';
            if ($app['module_type'] === 'directory') $color = '#8b5cf6';
            if ($app['module_type'] === 'calendar') $color = '#ec4899';
            
            if ($app['module_type'] === 'finance') $color = '#22c55e';
            if ($app['module_type'] === 'dues') $color = '#ef4444';
            if ($app['module_type'] === 'event') $color = '#f97316'; // Orange
            if ($app['module_type'] === 'partner') $color = '#14b8a6'; // Teal
            if ($app['module_type'] === 'bootcamp') $color = '#6366f1'; // Indigo
            if ($app['module_type'] === 'certificate') $color = '#f59e0b'; // Amber
            if ($app['module_type'] === 'inventory') $color = '#8b5cf6'; // Violet

            $svgPath = '<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>';
            if (in_array($app['icon'], ['form', 'file-text'])) $svgPath = '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>';
            if ($app['icon'] === 'calendar') $svgPath = '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>';
            if ($app['icon'] === 'check-square') $svgPath = '<polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>';
            if ($app['icon'] === 'megaphone') $svgPath = '<path d="M22 17H2a3 3 0 000 3h20a3 3 0 000-3zM22 11H2M22 5H2"/>';
            if ($app['icon'] === 'bar-chart') $svgPath = '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>';
            if ($app['icon'] === 'users') $svgPath = '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>';
            if ($app['icon'] === 'pie-chart') $svgPath = '<path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path>';
            if ($app['icon'] === 'credit-card') $svgPath = '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line>';
            if ($app['icon'] === 'map-pin') $svgPath = '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>';
            if ($app['icon'] === 'book-open') $svgPath = '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>';
            if ($app['icon'] === 'award') $svgPath = '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>';
            if ($app['icon'] === 'archive') $svgPath = '<polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line>';
            if ($app['icon'] === 'book') $svgPath = '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>';
            if ($app['icon'] === 'briefcase') $svgPath = '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>';
            if ($app['icon'] === 'link') $svgPath = '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>';
            if ($app['icon'] === 'qr') $svgPath = '<rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>';
            if ($app['icon'] === 'scissors') $svgPath = '<circle cx="6" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><line x1="20" y1="4" x2="8.12" y2="15.88"></line><line x1="14.47" y1="14.48" x2="20" y2="20"></line><line x1="8.12" y1="8.12" x2="12" y2="12"></line>';
            if ($app['icon'] === 'folder') $svgPath = '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>';
            if ($app['icon'] === 'layout') $svgPath = '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line>';
            if ($app['icon'] === 'lock') $svgPath = '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>';
            if ($app['icon'] === 'lightbulb') $svgPath = '<line x1="9" y1="18" x2="15" y2="18"></line><line x1="10" y1="22" x2="14" y2="22"></line><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A6 6 0 1 0 5.5 8c0 1.5.6 2.89 1.62 3.84c.64.6 1.05 1.34 1.25 2.16"></path>';
            if ($app['icon'] === 'check-circle') $svgPath = '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>';
            if ($app['icon'] === 'user-plus') $svgPath = '<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line>';
            ?>
            <a href="<?= $href ?>" 
               class="fade-in-up stagger-<?= min($appStagger++, 5) ?>"
               style="display:flex; align-items:center; justify-content:space-between; background:var(--card); border:1px solid var(--border); border-radius:12px; padding:20px 24px; text-decoration:none; transition:all 0.2s;"
               onmouseover="this.style.borderColor='<?= $color ?>50'; this.style.background='<?= $color ?>10';" onmouseout="this.style.borderColor='var(--border)'; this.style.background='var(--card)';">
                <div style="display:flex; align-items:center; gap:16px;">
                    <div style="width:48px; height:48px; border-radius:10px; background:<?= $color ?>20; display:flex; align-items:center; justify-content:center; color:<?= $color ?>;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><?= $svgPath ?></svg>
                    </div>
                    <div>
                        <h3 style="margin:0 0 4px 0; font-size:16px; color:var(--text);"><?= View::escape($app['module_type'] === 'form_manager' ? 'Form' : $app['name']) ?></h3>
                        <p style="margin:0; font-size:13px; color:var(--muted);"><?= View::escape(mb_substr($app['description'] ?? 'Uygulamaya git', 0, 45)) ?>...</p>
                    </div>
                </div>
                <div style="color:<?= $color ?>; font-weight:600; font-size:14px;">Git &rarr;</div>
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
        
        for (let i = 0; i < startDay; i++) {
            daysGrid.innerHTML += '<div></div>';
        }
        
        for (let day = 1; day <= daysInMonth; day++) {
            const hasBirthdays = calendarData.birthdays.filter(b => b.month === currentMonth + 1 && b.day === day);
            
            const dateStr = currentYear + '-' + String(currentMonth + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
            const hasEvents = calendarData.events.filter(e => e.date === dateStr);
            
            let bg = 'var(--bg)';
            let color = 'var(--text)';
            let border = '1px solid transparent';
            let iconHtml = '';
            
            const isToday = (day === currentDate.getDate() && currentMonth === currentDate.getMonth() && currentYear === currentDate.getFullYear());
            const isSelected = (day === selectedDate.getDate() && currentMonth === selectedDate.getMonth() && currentYear === selectedDate.getFullYear());
            
            if (isToday) border = '1px solid var(--text)';
            if (isSelected) border = '2px solid var(--accent)';
            
            if (hasBirthdays.length > 0 && hasEvents.length > 0) {
                bg = 'linear-gradient(135deg, rgba(236,72,153,0.15) 50%, rgba(59,130,246,0.15) 50%)';
                color = 'var(--text)';
                border = isSelected ? '2px solid var(--accent)' : '1px solid rgba(236,72,153,0.4)';
                iconHtml = '<div style="position:absolute; top:-4px; right:-4px; font-size:12px; display:flex; gap:2px;"><span>🎂</span><span>📅</span></div>';
            } else if (hasBirthdays.length > 0) {
                bg = 'linear-gradient(135deg, rgba(236,72,153,0.15), rgba(219,39,119,0.05))';
                color = '#ec4899';
                border = isSelected ? '2px solid #ec4899' : '1px solid rgba(236,72,153,0.4)';
                iconHtml = '<div style="position:absolute; top:-4px; right:-4px; font-size:14px;">🎂</div>';
            } else if (hasEvents.length > 0) {
                bg = 'linear-gradient(135deg, rgba(59,130,246,0.15), rgba(37,99,235,0.05))';
                color = '#3b82f6';
                border = isSelected ? '2px solid #3b82f6' : '1px solid rgba(59,130,246,0.4)';
                iconHtml = '<div style="position:absolute; top:-4px; right:-4px; font-size:14px;">📅</div>';
            }
            
            const fontWeight = (hasBirthdays.length > 0 || hasEvents.length > 0) ? '700' : '500';
            const cursor = 'pointer';
            
            const cell = document.createElement('div');
            cell.style.cssText = `background:${bg}; color:${color}; border:${border}; border-radius:10px; aspect-ratio: 1/1; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:${fontWeight}; position:relative; transition: transform 0.2s; cursor:${cursor}; box-sizing:border-box;`;
            cell.innerHTML = `<span>${day}</span>${iconHtml}`;
            
            cell.onmouseover = () => cell.style.transform = 'translateY(-2px)';
            cell.onmouseout = () => cell.style.transform = 'translateY(0)';
            
            cell.onclick = () => {
                selectedDate = new Date(currentYear, currentMonth, day);
                renderCalendar();
                renderDayDetails();
            };
            
            daysGrid.appendChild(cell);
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
        const evts = calendarData.events.filter(e => e.date === dateStr);
        
        if (bdays.length === 0 && evts.length === 0) {
            contentEl.innerHTML = '<div style="color:var(--muted); text-align:center; padding:20px 0;">Bu güne ait detay bulunmuyor.</div>';
            return;
        }
        
        if (bdays.length > 0) {
            const block = document.createElement('div');
            block.style.cssText = 'padding:10px; background:rgba(236,72,153,0.1); border-radius:8px; border:1px solid rgba(236,72,153,0.2);';
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
            evts.forEach(e => {
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
    <div class="card">
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
                        <span class="badge <?= $m['role'] === 'director' ? 'badge-director' : 'badge-member' ?>">
                            <?= $m['role'] === 'director' ? 'Direktör' : 'Üye' ?>
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
