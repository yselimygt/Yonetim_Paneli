<?php
$currentUserId = Auth::user()['id'] ?? 0;

// Helper to calculate time ago
if (!function_exists('tmTimeAgo')) {
    function tmTimeAgo($datetime) {
        $time = strtotime($datetime);
        $diff = time() - $time;
        if ($diff < 60) return 'Az önce';
        if ($diff < 3600) return floor($diff / 60) . ' dk önce';
        if ($diff < 86400) return floor($diff / 3600) . ' saat önce';
        return floor($diff / 86400) . ' gün önce';
    }
}

if (!function_exists('tmAvatarHtml')) {
    function tmAvatarHtml($avatar, $name, $size = 36, $margin = '0', $extraStyle = '') {
        $sizePx = $size . 'px';
        if (!empty($avatar)) {
            return '<img src="'.BASE_URL.'/public/'.htmlspecialchars($avatar).'" alt="" class="tm-avatar" title="'.htmlspecialchars($name).'" style="margin:'.$margin.'; width:'.$sizePx.'; height:'.$sizePx.'; object-fit:cover; '.$extraStyle.'">';
        }
        return '<div class="tm-avatar" title="'.htmlspecialchars($name).'" style="margin:'.$margin.'; width:'.$sizePx.'; height:'.$sizePx.'; '.$extraStyle.'">'.strtoupper(mb_substr($name ?? 'A', 0, 2)).'</div>';
    }
}
?>

<style>
/* Glassmorphism & Premium UI Styles (NO BOOTSTRAP) */
.tm-wrapper, .modal-overlay {
    --glass-bg: var(--card-alt, var(--card));
    --glass-border: var(--border);
    --glow-primary: rgba(59, 130, 246, 0.2);
    --glow-success: rgba(34, 197, 94, 0.2);
    --glow-warning: rgba(245, 158, 11, 0.2);
    --glow-info: rgba(6, 182, 212, 0.2);
    font-family: 'Inter', sans-serif;
}
.tm-wrapper {
    animation: fadeIn 0.4s ease;
}
.glass-card {
    background: var(--glass-bg);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    overflow: hidden;
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.glass-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
}
.glass-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.4);
    border-color: rgba(59, 130, 246, 0.5);
}

.btn-glass {
    backdrop-filter: blur(4px);
    border-radius: 8px;
    font-weight: 500;
    letter-spacing: 0.3px;
    transition: all 0.2s ease;
    padding: 10px 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    font-family: inherit;
    font-size: 13px;
    text-decoration: none;
    gap: 6px;
}
.btn-glass-primary {
    background: rgba(59, 130, 246, 0.15);
    border: 1px solid rgba(59, 130, 246, 0.3);
    color: #60a5fa;
}
.btn-glass-primary:hover { background: rgba(59, 130, 246, 0.25); box-shadow: 0 0 15px var(--glow-primary); color: #93c5fd; }
.btn-glass-success {
    background: rgba(34, 197, 94, 0.15);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #4ade80;
}
.btn-glass-success:hover { background: rgba(34, 197, 94, 0.25); box-shadow: 0 0 15px var(--glow-success); color: #86efac; }
.btn-glass-info {
    background: rgba(6, 182, 212, 0.15);
    border: 1px solid rgba(6, 182, 212, 0.3);
    color: #22d3ee;
}
.btn-glass-info:hover { background: rgba(6, 182, 212, 0.25); box-shadow: 0 0 15px var(--glow-info); color: #67e8f9; }
.btn-glass-outline {
    background: transparent;
    border: 1px solid var(--border);
    color: var(--muted);
}
.btn-glass-outline:hover { background: rgba(255,255,255,0.05); color: var(--text); border-color: rgba(255,255,255,0.2); }
.btn-glass-sm { padding: 6px 12px; font-size: 11px; border-radius: 6px; }

.form-control-glass {
    width: 100%;
    background: rgba(128, 128, 128, 0.1);
    border: 1px solid var(--glass-border);
    color: var(--text);
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 13px;
    transition: all 0.2s;
    font-family: inherit;
}
.form-control-glass:focus {
    outline: none;
    border-color: rgba(59, 130, 246, 0.5);
    background: rgba(128, 128, 128, 0.15);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
.form-control-glass::placeholder {
    color: var(--muted);
}

.badge-neon {
    padding: 6px 10px;
    border-radius: 6px;
    font-weight: 500;
    font-size: 11px;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.badge-neon-success { background: rgba(34, 197, 94, 0.1); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2); }
.badge-neon-warning { background: rgba(245, 158, 11, 0.1); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.2); }
.badge-neon-info { background: rgba(6, 182, 212, 0.1); color: #22d3ee; border: 1px solid rgba(6, 182, 212, 0.2); }
.badge-neon-muted { background: rgba(128, 128, 128, 0.1); color: var(--muted); border: 1px solid var(--glass-border); }

.glass-checkbox-list {
    background: rgba(128, 128, 128, 0.05);
    border: 1px solid var(--glass-border);
    border-radius: 8px;
    max-height: 160px;
    overflow-y: auto;
    padding: 6px;
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.glass-checkbox-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.2s;
    color: var(--text);
    font-size: 13px;
}
.glass-checkbox-item:hover { background: rgba(128, 128, 128, 0.1); }
.glass-checkbox-item input[type="checkbox"] {
    accent-color: #3b82f6;
    width: 15px;
    height: 15px;
    cursor: pointer;
}
.glass-checkbox-list::-webkit-scrollbar { width: 6px; }
.glass-checkbox-list::-webkit-scrollbar-track { background: transparent; }
.glass-checkbox-list::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
.glass-checkbox-list::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

.neon-progress-bg {
    background: rgba(255,255,255,0.05);
    border-radius: 10px;
    height: 8px;
    overflow: hidden;
    margin-top: 8px;
}
.neon-progress-bar {
    background: linear-gradient(90deg, #3b82f6, #60a5fa);
    box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
    height: 100%;
    border-radius: 10px;
    transition: width 1s ease-in-out;
}
.neon-progress-bar.success {
    background: linear-gradient(90deg, #22c55e, #4ade80);
    box-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
}

.tm-tabs {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
    border-bottom: 1px solid var(--border);
    padding-bottom: 12px;
    overflow-x: auto;
}
.tm-tab-btn {
    background: transparent;
    border: none;
    color: var(--muted);
    font-size: 14px;
    font-weight: 500;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}
.tm-tab-btn:hover {
    color: var(--text);
    background: rgba(255,255,255,0.05);
}
.tm-tab-btn.active {
    color: #60a5fa;
    background: rgba(59, 130, 246, 0.1);
    box-shadow: inset 0 0 10px rgba(59, 130, 246, 0.1);
}

.tm-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 600;
    color: var(--text);
    border: 2px solid var(--card-alt);
    margin-left: -10px;
    overflow: hidden;
}
.tm-avatar:first-child { margin-left: 0; }
.tm-avatar img { width: 100%; height: 100%; object-fit: cover; }

.modal-overlay {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.modal-overlay.show { display: flex; }
.modal-glass {
    width: 100%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>

<div class="tm-wrapper">
    <!-- Header -->
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h3 style="font-size: 18px; font-weight: 600; color: var(--text); display: flex; align-items: center; gap: 8px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="<?= View::escape($department['color'] ?? '#3b82f6') ?>" stroke-width="2" width="24" height="24">
                    <path d="M12 2l3 6 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1 3-6z"></path>
                </svg>
                <?= View::escape($app['name'] ?? 'Görev Yöneticisi') ?>
            </h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">
                <?= View::escape($app['description'] ?? 'Kişisel ve departman görevlerinizi yönetin.') ?>
            </p>
        </div>
        
        <?php $canManage = !empty($isManagement) || !empty($isDirector); ?>
        <?php if ($canManage): ?>
        <button class="btn-glass btn-glass-primary" onclick="document.getElementById('modal-create-task').classList.add('show')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Yeni Görev Ata
        </button>
        <?php endif; ?>
    </div>

    <!-- Tabs -->
    <div class="tm-tabs">
        <button class="tm-tab-btn active" data-tab="tab-my">Görevlerim</button>
        <?php if (!empty($isDirector) && empty($isManagement)): ?>
        <button class="tm-tab-btn" data-tab="tab-dept">Departman Görevleri</button>
        <?php endif; ?>
        <?php if (!empty($isManagement)): ?>
        <button class="tm-tab-btn" data-tab="tab-all">Tüm Görevler</button>
        <?php endif; ?>
        <?php if ($canManage): ?>
        <button class="tm-tab-btn" data-tab="tab-teams">Takımlar</button>
        <?php endif; ?>
    </div>

    <!-- Content Panes -->
    <div>
        <div class="tm-pane active" id="tab-my">
            <?php renderNeonGrid($tasks ?? [], 'my_tasks', $currentUserId, $canManage, !empty($isManagement)); ?>
        </div>
        
        <?php if (!empty($isDirector) && empty($isManagement)): ?>
        <div class="tm-pane" id="tab-dept" style="display:none;">
            <?php renderNeonGrid($tasks ?? [], 'dept_tasks', $currentUserId, $canManage, !empty($isManagement)); ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($isManagement)): ?>
        <div class="tm-pane" id="tab-all" style="display:none;">
            <?php renderNeonGrid($tasks ?? [], 'all_tasks', $currentUserId, $canManage, !empty($isManagement)); ?>
        </div>
        <?php endif; ?>
        
        <?php if ($canManage): ?>
        <div class="tm-pane" id="tab-teams" style="display:none;">
            <div style="display:flex; justify-content:flex-end; margin-bottom:20px;">
                <button class="btn-glass btn-glass-outline" onclick="document.getElementById('modal-create-team').classList.add('show')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Yeni Takım
                </button>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
                <?php if (empty($teams)): ?>
                    <div class="glass-card" style="grid-column: 1 / -1; padding: 40px; text-align: center;">
                        <span style="color:var(--muted); font-size:14px;">Henüz takım oluşturulmadı.</span>
                    </div>
                <?php else: ?>
                    <?php foreach ($teams as $team): ?>
                    <div class="glass-card" style="padding:20px; display:flex; flex-direction:column; position:relative;">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
                            <h3 style="font-size:16px; font-weight:600; color:var(--text); margin:0;"><?= htmlspecialchars($team['name'] ?? '') ?></h3>
                            <div style="display:flex; gap:4px;">
                                <button class="btn-glass btn-glass-outline" style="padding:4px; border-radius:4px;" onclick="tmOpenEditTeam(<?= htmlspecialchars(json_encode($team)) ?>)" title="Takımı Düzenle">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </button>
                                <button class="btn-glass btn-glass-danger" style="padding:4px; border-radius:4px; color:#f87171; border-color:rgba(248,113,113,0.3);" onclick="tmDeleteTeam(<?= $team['id'] ?>)" title="Takımı Sil">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                </button>
                            </div>
                        </div>
                        <p style="font-size:13px; color:var(--muted); margin-bottom:20px; flex-grow:1;"><?= nl2br(htmlspecialchars($team['description'] ?? '')) ?></p>
                        
                        <div style="padding-top:16px; border-top:1px solid rgba(255,255,255,0.05);">
                            <div style="font-size:11px; color:var(--muted); margin-bottom:8px; text-transform:uppercase; letter-spacing:1px;">ÜYELER (<?= count($team['members'] ?? []) ?>)</div>
                            <div style="display:flex; align-items:center;">
                                <?php foreach ($team['members'] ?? [] as $m): ?>
                                <?= tmAvatarHtml($m['avatar'] ?? null, $m['name'] ?? 'A', 32, '-8px', 'background: var(--bg);') ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
function renderNeonGrid($tasks, $viewContext, $currentUserId, $canManage, $isRealManagement = false) {
    $filtered = [];
    foreach ($tasks as $task) {
        $myAssignment = null;
        foreach ($task['assignees'] ?? [] as $a) {
            if (($a['user_id'] ?? 0) == $currentUserId) $myAssignment = $a;
        }
        if ($viewContext === 'my_tasks' && $myAssignment) {
            $filtered[] = $task;
        } elseif ($viewContext === 'dept_tasks' || $viewContext === 'all_tasks') {
            $filtered[] = $task;
        }
    }

    if (empty($filtered)) {
        echo '<div class="glass-card" style="grid-column: 1 / -1; padding: 60px; text-align: center; border-style: dashed;">';
        echo '  <p style="color:var(--muted); font-size:14px; margin:0;">Bu sekmede gösterilecek görev yok.</p>';
        echo '</div>';
        return;
    }

    echo '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">';
    foreach ($filtered as $task) {
        $myAssignment = null;
        $total = count($task['assignees'] ?? []);
        $completed = 0;
        
        foreach ($task['assignees'] ?? [] as $a) {
            if (($a['user_id'] ?? 0) == $currentUserId) $myAssignment = $a;
            if (($a['status'] ?? '') === 'completed') $completed++;
        }
        
        $progress = $total > 0 ? ($completed / $total) * 100 : 0;
        $dueDateStr = !empty($task['due_date']) ? date('d M Y', strtotime($task['due_date'])) : 'Süresiz';
        $dueClass = 'badge-neon-muted';
        if (!empty($task['due_date']) && strtotime($task['due_date']) < time() && $progress < 100) {
            $dueClass = 'badge-neon-danger';
        }
        
        echo '<div class="glass-card" style="display:flex; flex-direction:column;">';
        echo '  <div style="padding: 20px; flex-grow:1;">';
        
        // Header (Creator & Date)
        echo '      <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px;">';
        echo '          <div style="display:flex; align-items:center; gap:10px;">';
        echo '              ' . tmAvatarHtml($task['creator_avatar'] ?? null, $task['creator_name'] ?? 'A', 36, '0');
        echo '              <div>';
        $isYonetim = ($task['creator_dept_slug'] ?? '') === 'yonetim';
        $originLabel = $isYonetim ? 'Yönetimden' : 'Departmandan';
        $originColor = $isYonetim ? '#f43f5e' : '#3b82f6';
        $originBg = $isYonetim ? 'rgba(244,63,94,0.1)' : 'rgba(59,130,246,0.1)';
        $originBorder = $isYonetim ? 'rgba(244,63,94,0.3)' : 'rgba(59,130,246,0.3)';

        echo '                  <div style="font-size:13px; font-weight:600; color:var(--text); display:flex; align-items:center; gap:6px;">';
        echo '                      '.htmlspecialchars($task['creator_name'] ?? '');
        echo '                      <span style="font-size:10px; font-weight:500; padding:2px 6px; border-radius:12px; background:'.$originBg.'; color:'.$originColor.'; border:1px solid '.$originBorder.'; line-height:1;">'.$originLabel.'</span>';
        echo '                  </div>';
        echo '                  <div style="font-size:11px; color:var(--muted);">'.tmTimeAgo($task['created_at']).'</div>';
        echo '              </div>';
        echo '          </div>';
        echo '          <span class="badge-neon '.$dueClass.'">';
        echo '              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>';
        echo                $dueDateStr;
        echo '          </span>';
        echo '      </div>';
        
        // Content
        echo '      <h3 style="font-size:16px; font-weight:600; color:var(--text); margin-bottom:8px; line-height:1.4;">'.htmlspecialchars($task['title'] ?? '').'</h3>';
        echo '      <p style="font-size:13px; color:var(--text); opacity:0.8; line-height:1.6; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;">'.nl2br(htmlspecialchars($task['description'] ?? '')).'</p>';
        echo '  </div>';
        
        // Footer Actions
        echo '  <div style="padding:16px 20px; background:rgba(0,0,0,0.1); border-top:1px solid rgba(255,255,255,0.05);">';
        
        // Assignees & Progress (for management/all views)
        echo '      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">';
        echo '          <div style="display:flex; align-items:center;">';
        $showLimit = 3; $count = 0;
        foreach ($task['assignees'] ?? [] as $a) {
            if ($count < $showLimit) {
                echo tmAvatarHtml($a['avatar'] ?? null, $a['name'] ?? 'A', 32, '-8px', 'background:var(--bg); border-color:var(--glass-border); box-shadow:0 0 0 2px var(--card);');
            }
            $count++;
        }
        if ($count > $showLimit) echo '<div class="tm-avatar" style="background:var(--bg);">+'.($count - $showLimit).'</div>';
        echo '          </div>';
        
        $canEdit = $isRealManagement || ($canManage && !$isYonetim);
        echo '          <button class="btn-glass btn-glass-outline btn-glass-sm" onclick="tmOpenDetails('.htmlspecialchars(json_encode($task)).', '.(int)$currentUserId.', '.(int)$canEdit.')">Detaylar</button>';
        echo '      </div>';
        
        if ($canManage || $viewContext === 'dept_tasks' || $viewContext === 'all_tasks') {
            echo '      <div class="neon-progress-bg">';
            echo '          <div class="neon-progress-bar '.($progress == 100 ? 'success' : '').'" style="width:'.$progress.'%;"></div>';
            echo '      </div>';
        }
        
        // My Actions
        if ($myAssignment) {
            $myStatus = $myAssignment['status'] ?? 'pending';
            echo '      <div style="display:flex; gap:8px; margin-top:16px;">';
            if ($myStatus === 'pending') {
                echo '          <button class="btn-glass btn-glass-info" style="flex:1;" onclick="tmUpdateStatus('.$myAssignment['id'].', \'acknowledged\')">Görev Alındı</button>';
            } elseif ($myStatus === 'acknowledged') {
                echo '          <button class="btn-glass btn-glass-success" style="flex:1;" onclick="tmUpdateStatus('.$myAssignment['id'].', \'completed\')">Tamamlandı</button>';
            } else {
                echo '          <div class="badge-neon badge-neon-success" style="width:100%; justify-content:center; padding:10px;">Tamamladınız</div>';
            }
            echo '      </div>';
        }
        
        echo '  </div>';
        echo '</div>';
    }
    echo '</div>';
}
?>

<!-- MODALS -->
<div id="modal-task-details" class="modal-overlay">
    <div class="glass-card modal-glass">
        <div style="padding:24px; border-bottom: 1px solid var(--glass-border);">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:36px; height:36px; border-radius:10px; background:rgba(6, 182, 212, 0.15); display:flex; align-items:center; justify-content:center; color:#22d3ee;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </div>
                    <h2 id="tmTdTitle" style="font-size:20px; font-weight:600; color:var(--text); margin:0;"></h2>
                </div>
                <div id="tmTdActions" style="display:flex; gap:8px;">
                    <!-- Actions populated by JS -->
                </div>
                <div style="display:flex; gap:8px;">
                    <button class="btn-glass btn-glass-outline" style="padding:6px; border-radius:50%; margin-top:2px;" onclick="this.closest('.modal-overlay').classList.remove('show')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
            </div>
        </div>
        <div style="padding:24px;">
            <p id="tmTdDesc" style="font-size:14px; color:var(--text); opacity:0.9; margin-bottom:24px; line-height:1.6; white-space:pre-wrap; background:rgba(0,0,0,0.15); padding:16px; border-radius:8px; border:1px solid rgba(255,255,255,0.05);"></p>
            
            <h3 style="font-size:13px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:12px;">Atamalar</h3>
            <div id="tmTdAssignees" style="display:flex; flex-direction:column; gap:8px; margin-bottom:24px;"></div>

            <h3 style="font-size:13px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:12px;">Yorumlar</h3>
            <div id="tmTdComments" style="display:flex; flex-direction:column; gap:12px; margin-bottom:16px; max-height:300px; overflow-y:auto; padding-right:8px;"></div>
            
            <form onsubmit="tmSubmitComment(event); return false;" style="display:flex; gap:8px; margin:0; padding-top:16px; border-top:1px solid var(--glass-border);">
                <input type="hidden" id="tmTdTaskId" name="task_id">
                <input type="text" name="comment" class="form-control-glass" style="flex:1;" placeholder="Bir yorum ekle..." required>
                <button type="submit" class="btn-glass btn-glass-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<?php if ($canManage): ?>
<div id="modal-create-task" class="modal-overlay">
    <div class="glass-card modal-glass">
        <div style="padding:24px; border-bottom: 1px solid var(--glass-border);">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; border-radius:10px; background:rgba(59, 130, 246, 0.15); display:flex; align-items:center; justify-content:center; color:#60a5fa;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    </div>
                    <h2 style="font-size:18px; font-weight:600; margin:0; color:var(--text);">Yeni Görev Ata</h2>
                </div>
                <button class="btn-glass btn-glass-outline" style="padding:6px; border-radius:50%;" onclick="this.closest('.modal-overlay').classList.remove('show')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
        </div>
        <div style="padding:24px;">
            <form method="POST" action="" style="margin:0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="create_task">
                
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div>
                        <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px; font-weight:500;">Görev Başlığı *</label>
                        <input type="text" name="title" class="form-control-glass" placeholder="Örn: Tasarım revizyonu..." required>
                    </div>
                    <div>
                        <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px; font-weight:500;">Son Tarih</label>
                        <input type="datetime-local" name="due_date" class="form-control-glass" style="color-scheme: dark;">
                    </div>
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px; font-weight:500;">Açıklama / Detaylar</label>
                    <textarea name="description" class="form-control-glass" style="min-height:80px; resize:vertical;" placeholder="Görevin detaylarını buraya yazın..." rows="3"></textarea>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:24px;">
                    <div>
                        <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px; font-weight:500;">Takıma Ata</label>
                        <div class="glass-checkbox-list">
                            <?php foreach ($teams ?? [] as $t): ?>
                            <label class="glass-checkbox-item">
                                <input type="checkbox" name="team_ids[]" value="<?= $t['id'] ?>">
                                <span><?= htmlspecialchars($t['name'] ?? '') ?></span>
                            </label>
                            <?php endforeach; ?>
                            <?php if(empty($teams)): ?>
                                <div style="padding:10px; font-size:12px; color:var(--muted); text-align:center;">Takım bulunamadı.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px; font-weight:500;">Kişilere Ata</label>
                        <div class="glass-checkbox-list">
                            <?php foreach ($allUsers ?? [] as $u): ?>
                            <label class="glass-checkbox-item">
                                <input type="checkbox" name="user_ids[]" value="<?= $u['id'] ?>">
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <?= tmAvatarHtml($u['avatar'] ?? null, $u['name'] ?? 'A', 20, '0', 'font-size:9px;') ?>
                                    <span><?= htmlspecialchars($u['name'] ?? '') ?></span>
                                </div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:12px; padding-top:16px; border-top: 1px solid var(--glass-border);">
                    <button type="button" class="btn-glass btn-glass-outline" onclick="this.closest('.modal-overlay').classList.remove('show')">İptal Et</button>
                    <button type="submit" class="btn-glass btn-glass-primary" style="padding-left:24px; padding-right:24px;">Görevi Oluştur</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal-edit-task" class="modal-overlay">
    <div class="glass-card modal-glass">
        <div style="padding:24px; border-bottom: 1px solid var(--glass-border);">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; border-radius:10px; background:rgba(245, 158, 11, 0.15); display:flex; align-items:center; justify-content:center; color:#fbbf24;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </div>
                    <h2 style="font-size:18px; font-weight:600; margin:0; color:var(--text);">Görevi Düzenle</h2>
                </div>
                <button class="btn-glass btn-glass-outline" style="padding:6px; border-radius:50%;" onclick="this.closest('.modal-overlay').classList.remove('show')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
        </div>
        <div style="padding:24px;">
            <form method="POST" action="" style="margin:0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="edit_task">
                <input type="hidden" name="task_id" id="edit_task_id">
                
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div>
                        <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px; font-weight:500;">Görev Başlığı *</label>
                        <input type="text" name="title" id="edit_task_title" class="form-control-glass" required>
                    </div>
                    <div>
                        <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px; font-weight:500;">Son Tarih</label>
                        <input type="datetime-local" name="due_date" id="edit_task_due_date" class="form-control-glass" style="color-scheme: dark;">
                    </div>
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px; font-weight:500;">Açıklama / Detaylar</label>
                    <textarea name="description" id="edit_task_desc" class="form-control-glass" style="min-height:80px; resize:vertical;" rows="3"></textarea>
                </div>

                <div style="display:grid; grid-template-columns: 1fr; gap:16px; margin-bottom:24px;">
                    <div>
                        <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px; font-weight:500;">Kişilere Ata (Yeniden Seçin)</label>
                        <div class="glass-checkbox-list">
                            <?php foreach ($allUsers ?? [] as $u): ?>
                            <label class="glass-checkbox-item">
                                <input type="checkbox" name="user_ids[]" value="<?= $u['id'] ?>" class="edit_task_user_chk">
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <?= tmAvatarHtml($u['avatar'] ?? null, $u['name'] ?? 'A', 20, '0', 'font-size:9px;') ?>
                                    <span><?= htmlspecialchars($u['name'] ?? '') ?></span>
                                </div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:12px; padding-top:16px; border-top: 1px solid var(--glass-border);">
                    <button type="button" class="btn-glass btn-glass-outline" onclick="this.closest('.modal-overlay').classList.remove('show')">İptal Et</button>
                    <button type="submit" class="btn-glass btn-glass-primary" style="padding-left:24px; padding-right:24px;">Değişiklikleri Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal-create-team" class="modal-overlay">
    <div class="glass-card modal-glass" style="max-width: 500px;">
        <div style="padding:24px; border-bottom: 1px solid var(--glass-border);">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; border-radius:10px; background:rgba(34, 197, 94, 0.15); display:flex; align-items:center; justify-content:center; color:#4ade80;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <h2 style="font-size:18px; font-weight:600; margin:0; color:var(--text);">Yeni Takım Oluştur</h2>
                </div>
                <button class="btn-glass btn-glass-outline" style="padding:6px; border-radius:50%;" onclick="this.closest('.modal-overlay').classList.remove('show')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
        </div>
        <div style="padding:24px;">
            <form method="POST" action="" style="margin:0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="create_team">
                
                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px; font-weight:500;">Takım Adı *</label>
                    <input type="text" name="name" class="form-control-glass" placeholder="Örn: Ar-Ge Ekibi" required>
                </div>
                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px; font-weight:500;">Açıklama (Opsiyonel)</label>
                    <input type="text" name="description" class="form-control-glass" placeholder="Takımın amacı...">
                </div>
                <div style="margin-bottom:24px;">
                    <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px; font-weight:500;">Takım Üyelerini Seçin *</label>
                    <div class="glass-checkbox-list" style="max-height: 200px;">
                        <?php foreach ($allUsers ?? [] as $u): ?>
                        <label class="glass-checkbox-item">
                            <input type="checkbox" name="user_ids[]" value="<?= $u['id'] ?>">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <?= tmAvatarHtml($u['avatar'] ?? null, $u['name'] ?? 'A', 24, '0', 'font-size:10px;') ?>
                                <span><?= htmlspecialchars($u['name'] ?? '') ?></span>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:12px; padding-top:16px; border-top: 1px solid rgba(255,255,255,0.05);">
                    <button type="button" class="btn-glass btn-glass-outline" onclick="this.closest('.modal-overlay').classList.remove('show')">İptal Et</button>
                    <button type="submit" class="btn-glass btn-glass-success" style="padding-left:24px; padding-right:24px;">Takımı Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($canManage): ?>
<div id="modal-edit-team" class="modal-overlay">
    <div class="glass-card modal-glass" style="max-width: 500px;">
        <div style="padding:24px; border-bottom: 1px solid var(--glass-border);">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; border-radius:10px; background:rgba(245, 158, 11, 0.15); display:flex; align-items:center; justify-content:center; color:#fbbf24;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </div>
                    <h2 style="font-size:18px; font-weight:600; margin:0; color:var(--text);">Takımı Düzenle</h2>
                </div>
                <button class="btn-glass btn-glass-outline" style="padding:6px; border-radius:50%;" onclick="this.closest('.modal-overlay').classList.remove('show')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
        </div>
        <div style="padding:24px;">
            <form method="POST" action="" style="margin:0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="edit_team">
                <input type="hidden" name="team_id" id="edit_team_id">
                
                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px; font-weight:500;">Takım Adı *</label>
                    <input type="text" name="name" id="edit_team_name" class="form-control-glass" required>
                </div>
                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px; font-weight:500;">Açıklama (Opsiyonel)</label>
                    <input type="text" name="description" id="edit_team_desc" class="form-control-glass">
                </div>
                <div style="margin-bottom:24px;">
                    <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px; font-weight:500;">Takım Üyelerini Seçin *</label>
                    <div class="glass-checkbox-list" style="max-height: 200px;">
                        <?php foreach ($allUsers ?? [] as $u): ?>
                        <label class="glass-checkbox-item">
                            <input type="checkbox" name="user_ids[]" value="<?= $u['id'] ?>" class="edit_team_user_chk">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <?= tmAvatarHtml($u['avatar'] ?? null, $u['name'] ?? 'A', 24, '0', 'font-size:10px;') ?>
                                <span><?= htmlspecialchars($u['name'] ?? '') ?></span>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:12px; padding-top:16px; border-top: 1px solid rgba(255,255,255,0.05);">
                    <button type="button" class="btn-glass btn-glass-outline" onclick="this.closest('.modal-overlay').classList.remove('show')">İptal Et</button>
                    <button type="submit" class="btn-glass btn-glass-warning" style="padding-left:24px; padding-right:24px;">Değişiklikleri Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.querySelectorAll('.tm-tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tm-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tm-pane').forEach(c => c.style.display = 'none');
        
        btn.classList.add('active');
        const tabId = btn.getAttribute('data-tab');
        const pane = document.getElementById(tabId);
        if(pane) pane.style.display = 'block';
    });
});

document.querySelectorAll('.modal-overlay').forEach(wrapper => {
    let mousedownTarget = null;
    wrapper.addEventListener('mousedown', e => mousedownTarget = e.target);
    wrapper.addEventListener('touchstart', e => mousedownTarget = e.target, {passive: true});
    wrapper.addEventListener('click', e => {
        if (e.target === wrapper && mousedownTarget === wrapper) {
            wrapper.classList.remove('show');
        }
        mousedownTarget = null;
    });
});

let currentTaskForEdit = null;

function tmOpenDetails(task, currentUserId, canEdit) {
    currentTaskForEdit = task;
    document.getElementById('modal-task-details').classList.add('show');
    document.getElementById('tmTdTitle').textContent = task.title || '';
    document.getElementById('tmTdDesc').textContent = task.description || '';
    document.getElementById('tmTdTaskId').value = task.id;
    
    const actionsDiv = document.getElementById('tmTdActions');
    actionsDiv.innerHTML = '';
    if (canEdit) {
        actionsDiv.innerHTML = `
            <button class="btn-glass btn-glass-outline" style="padding:6px; margin-top:2px;" onclick="tmOpenEditFromDetails()" title="Düzenle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            </button>
            <button class="btn-glass btn-glass-danger" style="padding:6px; margin-top:2px; color:#f87171; border-color:rgba(248,113,113,0.3);" onclick="tmDeleteTask(${task.id})" title="Sil">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
            </button>
        `;
    }

    const assigneesDiv = document.getElementById('tmTdAssignees');
    assigneesDiv.innerHTML = '';
    
    (task.assignees || []).forEach(a => {
        let badge = '';
        if (a.status === 'completed') badge = '<span class="badge-neon badge-neon-success">Tamamlandı</span>';
        else if (a.status === 'acknowledged') badge = '<span class="badge-neon badge-neon-info">Görev Alındı</span>';
        else badge = '<span class="badge-neon badge-neon-warning">Bekliyor</span>';

        let actionHtml = '';
        if (canEdit && a.status !== 'completed') {
            actionHtml = `<button class="btn-glass btn-glass-outline btn-glass-sm" onclick="tmUpdateStatus(${a.id}, 'completed')">Bitir</button>`;
        }
        
        assigneesDiv.innerHTML += `
            <div style="display:flex; justify-content:space-between; align-items:center; padding:12px; background:rgba(255,255,255,0.02); border-radius:8px; border:1px solid rgba(255,255,255,0.05);">
                <div style="display:flex; align-items:center; gap:10px;">
                    ${a.avatar ? `<img src="<?= BASE_URL ?>/public/${a.avatar}" class="tm-avatar" style="margin:0; object-fit:cover;">` : `<div class="tm-avatar" style="margin:0;">${(a.name || '?').substring(0,2).toUpperCase()}</div>`}
                    <span style="font-size:13px; font-weight:500; color:var(--text);">${a.name}</span>
                </div>
                <div style="display:flex; align-items:center; gap:8px;">
                    ${badge}
                    ${actionHtml}
                </div>
            </div>
        `;
    });
    
    tmLoadComments(task.id);
}

function tmOpenEditFromDetails() {
    if(!currentTaskForEdit) return;
    document.getElementById('modal-task-details').classList.remove('show');
    document.getElementById('modal-edit-task').classList.add('show');
    
    document.getElementById('edit_task_id').value = currentTaskForEdit.id;
    document.getElementById('edit_task_title').value = currentTaskForEdit.title || '';
    document.getElementById('edit_task_desc').value = currentTaskForEdit.description || '';
    
    if (currentTaskForEdit.due_date) {
        document.getElementById('edit_task_due_date').value = currentTaskForEdit.due_date.replace(' ', 'T');
    } else {
        document.getElementById('edit_task_due_date').value = '';
    }
    
    // Check assignees
    const assigneeIds = (currentTaskForEdit.assignees || []).map(a => a.user_id.toString());
    document.querySelectorAll('.edit_task_user_chk').forEach(chk => {
        chk.checked = assigneeIds.includes(chk.value);
    });
}

function tmUpdateStatus(assigneeId, newStatus) {
    if(!confirm("Durumu güncellemek istediğinize emin misiniz?")) return;
    const formData = new URLSearchParams();
    formData.append('action', 'update_status');
    formData.append('assignee_id', assigneeId);
    formData.append('status', newStatus);
    formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]')?.content || '<?= $_SESSION['csrf_token'] ?? '' ?>');
    
    fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: formData.toString()
    }).then(r => r.json()).then(res => {
        if(res.success) window.location.reload();
        else alert(res.error || 'Hata oluştu.');
    });
}

function tmLoadComments(taskId) {
    const commentsDiv = document.getElementById('tmTdComments');
    commentsDiv.innerHTML = '<div style="text-align:center; padding:20px; color:var(--muted); font-size:12px;">Yükleniyor...</div>';
    
    const formData = new URLSearchParams();
    formData.append('action', 'get_comments');
    formData.append('task_id', taskId);
    formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?? '' ?>');
    
    fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: formData.toString()
    }).then(r => r.json()).then(res => {
        commentsDiv.innerHTML = '';
        if(res.success) {
            if(!res.data || res.data.length === 0) {
                commentsDiv.innerHTML = '<div style="font-size:13px; color:var(--muted); text-align:center; padding:20px;">Henüz yorum yapılmamış.</div>';
                return;
            }
            res.data.forEach(c => {
                const date = new Date(c.created_at).toLocaleString('tr-TR', {day:'numeric', month:'short', hour:'2-digit', minute:'2-digit'});
                commentsDiv.innerHTML += `
                    <div style="padding:12px; background:rgba(255,255,255,0.02); border-radius:8px; border:1px solid rgba(255,255,255,0.05);">
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                            <span style="font-size:13px; font-weight:600; color:var(--text);">${c.name}</span>
                            <span style="font-size:11px; color:var(--muted);">${date}</span>
                        </div>
                        <div style="font-size:13px; color:var(--text); opacity:0.9; line-height:1.5;">${c.comment}</div>
                    </div>
                `;
            });
        }
    });
}

function tmSubmitComment(e) {
    e.preventDefault();
    const form = e.target;
    const input = form.querySelector('input[name="comment"]');
    const taskId = form.querySelector('input[name="task_id"]').value;
    
    const formData = new URLSearchParams();
    formData.append('action', 'add_comment');
    formData.append('task_id', taskId);
    formData.append('comment', input.value);
    formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?? '' ?>');
    
    fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: formData.toString()
    }).then(r => r.json()).then(res => {
        if(res.success) {
            input.value = '';
            tmLoadComments(taskId);
        } else {
            alert(res.error || 'Yorum eklenemedi.');
        }
    }).catch(err => {
        console.error(err);
        alert('Bir hata oluştu. Yorum eklenemedi.');
    });
}

function tmOpenEditTeam(team) {
    document.getElementById('edit_team_id').value = team.id;
    document.getElementById('edit_team_name').value = team.name || '';
    document.getElementById('edit_team_desc').value = team.description || '';
    
    document.querySelectorAll('.edit_team_user_chk').forEach(chk => {
        chk.checked = false;
        if (team.members) {
            team.members.forEach(m => {
                if (m.user_id == chk.value) chk.checked = true;
            });
        }
    });
    
    document.getElementById('modal-edit-team').classList.add('show');
}

function tmDeleteTeam(teamId) {
    if (!confirm('Bu takımı silmek istediğinize emin misiniz?')) return;
    const formData = new URLSearchParams();
    formData.append('action', 'delete_team');
    formData.append('team_id', teamId);
    formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?? '' ?>');
    
    fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: formData.toString()
    }).then(r => r.json()).then(res => {
        if(res.success) {
            location.reload();
        } else {
            alert(res.error || 'Silinirken bir hata oluştu.');
        }
    }).catch(err => {
        console.error(err);
        alert('Bir hata oluştu. Takım silinemedi.');
    });
}
</script>
