<style>
/* =========================================
   TASK MANAGER UI/UX STYLES
========================================= */
.task-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}
.task-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 6px 0;
    color: var(--text);
    letter-spacing: -0.5px;
}
.task-subtitle {
    margin: 0;
    color: var(--muted);
    font-size: 14px;
}

/* Tabs Mobile Scroll */
.tm-tabs-wrapper {
    margin-bottom: 24px;
    border-bottom: 1px solid var(--border);
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none; /* Firefox */
}
.tm-tabs-wrapper::-webkit-scrollbar {
    display: none; /* Safari/Chrome */
}
.tm-tabs {
    display: flex;
    gap: 8px;
    white-space: nowrap;
    padding-bottom: 2px;
}
.tm-tab-btn {
    padding: 12px 20px;
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    color: var(--muted);
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    border-radius: 6px 6px 0 0;
}
.tm-tab-btn:hover {
    color: var(--text);
    background: var(--bg-hover);
}
.tm-tab-btn.active {
    border-bottom: 2px solid var(--primary);
    color: var(--text);
    background: var(--bg-hover);
}

/* Task List Grid */
.tm-task-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
}

/* Task Card Enhancements */
.tm-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-left: 4px solid var(--primary);
    display: flex;
    flex-direction: column;
}
.tm-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}
.tm-card-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
    line-height: 1.4;
    color: var(--text);
}
.tm-card-desc {
    font-size: 14px;
    color: var(--muted);
    line-height: 1.5;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex-grow: 1;
}

/* Avatars Overlapping */
.tm-avatars {
    display: flex;
    align-items: center;
}
.tm-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 600;
    color: var(--text);
    border: 2px solid var(--card);
    margin-left: -8px;
    position: relative;
    transition: transform 0.2s ease;
}
.tm-avatar:first-child {
    margin-left: 0;
}
.tm-avatar:hover {
    transform: translateY(-2px);
    z-index: 10;
}
.tm-avatar-img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}
.tm-avatar-more {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--bg-hover);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
    border: 2px solid var(--card);
    margin-left: -8px;
}

/* Empty State */
.tm-empty {
    padding: 60px 20px;
    text-align: center;
    background: var(--card);
    border-radius: 16px;
    border: 1px dashed var(--border);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
}
.tm-empty-icon {
    width: 48px;
    height: 48px;
    color: var(--muted);
    opacity: 0.5;
}
.tm-empty-text {
    color: var(--muted);
    font-size: 15px;
}

/* Progress Bar */
.tm-progress-bg {
    height: 6px;
    background: var(--bg-hover);
    border-radius: 3px;
    overflow: hidden;
    margin-top: 6px;
}
.tm-progress-fill {
    height: 100%;
    background: var(--primary);
    transition: width 0.5s ease-out;
}

/* Modals Bottom Sheet on Mobile */
.tm-modal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    max-width: 600px;
    z-index: 1000;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 24px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}
@media (max-width: 768px) {
    .tm-modal {
        top: auto;
        bottom: 0;
        left: 0;
        transform: none;
        width: 100%;
        max-width: 100%;
        border-radius: 20px 20px 0 0;
        max-height: 85vh;
        border-bottom: none;
        border-left: none;
        border-right: none;
        animation: slideUp 0.3s ease-out forwards;
    }
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
}
</style>

<div class="task-manager-container" style="display:flex; flex-direction:column; gap:20px; width:100%; max-width:100%;">

<div class="task-header fade-in-up">
    <div>
        <h1 class="task-title">
            <i data-lucide="check-square" style="width:28px; height:28px; color:var(--primary);"></i>
            Görev Yönetimi
        </h1>
        <p class="task-subtitle">Kişisel ve ekip görevlerini takip edin</p>
    </div>
    <?php if ($isManagement): ?>
    <div>
        <button class="btn btn-primary" onclick="openCreateTaskModal()">
            <i data-lucide="plus"></i> Yeni Görev
        </button>
    </div>
    <?php endif; ?>
</div>

<div class="panel-content fade-in-up stagger-1">
    <div class="tm-tabs-wrapper">
        <div class="tm-tabs">
            <button class="tm-tab-btn active" data-tab="my-tasks">Görevlerim</button>
            <?php if ($isDirector && !$isManagement): ?>
            <button class="tm-tab-btn" data-tab="dept-tasks">Departman Görevleri</button>
            <?php endif; ?>
            <?php if ($isManagement): ?>
            <button class="tm-tab-btn" data-tab="all-tasks">Tüm Görevler</button>
            <button class="tm-tab-btn" data-tab="mini-teams">Mini Takımlar</button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Görev Listeleri -->
    <div class="tab-content active" id="tab-my-tasks">
        <?php renderTasksList($tasks, 'my_tasks', Auth::user()['id'], $isManagement); ?>
    </div>
    
    <?php if ($isDirector && !$isManagement): ?>
    <div class="tab-content" id="tab-dept-tasks" style="display:none;">
        <?php renderTasksList($tasks, 'dept_tasks', Auth::user()['id'], $isManagement); ?>
    </div>
    <?php endif; ?>

    <?php if ($isManagement): ?>
    <div class="tab-content" id="tab-all-tasks" style="display:none;">
        <?php renderTasksList($tasks, 'all_tasks', Auth::user()['id'], $isManagement); ?>
    </div>
    
    <div class="tab-content" id="tab-mini-teams" style="display:none;">
        <div style="display:flex; justify-content:flex-end; margin-bottom:16px;">
            <button class="btn btn-outline" onclick="openCreateTeamModal()">
                <i data-lucide="users"></i> Takım Oluştur
            </button>
        </div>
        <div class="tm-task-grid">
            <?php foreach ($teams as $team): ?>
            <div class="panel-card p-4 tm-card">
                <h3 style="font-weight:600; margin-bottom:8px; display:flex; align-items:center; gap:8px; font-size:16px;">
                    <i data-lucide="shield" style="width:18px; color:var(--primary);"></i>
                    <?= htmlspecialchars($team['name']) ?>
                </h3>
                <p class="tm-card-desc" style="margin-bottom:16px;"><?= nl2br(htmlspecialchars($team['description'] ?? '')) ?></p>
                
                <div style="margin-top:auto; padding-top:12px; border-top:1px solid var(--border);">
                    <div style="font-size:11px; font-weight:700; margin-bottom:8px; color:var(--muted); letter-spacing:0.5px;">ÜYELER (<?= count($team['members']) ?>)</div>
                    <div class="tm-avatars" style="flex-wrap:wrap;">
                        <?php foreach ($team['members'] as $m): ?>
                        <div class="tm-avatar" title="<?= htmlspecialchars($m['name']) ?>" style="margin-bottom:4px;">
                            <?= strtoupper(mb_substr($m['name'], 0, 2)) ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($teams)): ?>
            <div class="tm-empty" style="grid-column: 1 / -1;">
                <i data-lucide="users" class="tm-empty-icon"></i>
                <div class="tm-empty-text">Henüz bir mini takım oluşturulmadı.</div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
</div> <!-- /task-manager-container -->

<?php
function renderTasksList($tasks, $viewContext, $currentUserId, $isManagement) {
    // Filter tasks based on view context
    $filteredTasks = [];
    foreach ($tasks as $task) {
        $myAssignment = null;
        $hasOthers = false;
        
        foreach ($task['assignees'] as $a) {
            if ($a['user_id'] == $currentUserId) $myAssignment = $a;
            else $hasOthers = true;
        }

        if ($viewContext === 'my_tasks' && $myAssignment) {
            $filteredTasks[] = $task;
        } elseif ($viewContext === 'dept_tasks') {
            // Directors see tasks assigned to anyone (in their dept, already filtered by SQL)
            $filteredTasks[] = $task;
        } elseif ($viewContext === 'all_tasks') {
            $filteredTasks[] = $task;
        }
    }

    if (empty($filteredTasks)) {
        echo '<div class="tm-empty">';
        echo '  <i data-lucide="inbox" class="tm-empty-icon"></i>';
        echo '  <div class="tm-empty-text">Bu kategoride gösterilecek görev bulunmuyor.</div>';
        echo '</div>';
        return;
    }

    echo '<div class="tm-task-grid">';
    foreach ($filteredTasks as $task) {
        $myAssignment = null;
        foreach ($task['assignees'] as $a) {
            if ($a['user_id'] == $currentUserId) $myAssignment = $a;
        }
        
        // Calculate status metrics
        $total = count($task['assignees']);
        $completed = 0;
        foreach ($task['assignees'] as $a) {
            if ($a['status'] === 'completed') $completed++;
        }
        $progress = $total > 0 ? ($completed / $total) * 100 : 0;
        
        $dueDateStr = $task['due_date'] ? date('d M Y, H:i', strtotime($task['due_date'])) : 'Süresiz';
        
        echo '<div class="panel-card p-4 tm-card">';
        echo '  <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px; gap:12px;">';
        echo '      <h3 class="tm-card-title">'.htmlspecialchars($task['title']).'</h3>';
        echo '      <button class="btn btn-sm btn-outline" style="flex-shrink:0;" onclick="openTaskDetails('.htmlspecialchars(json_encode($task)).', '.(int)$currentUserId.', '.(int)$isManagement.')">Detaylar</button>';
        echo '  </div>';
        
        echo '  <p class="tm-card-desc">'.nl2br(htmlspecialchars($task['description'])).'</p>';
        
        echo '  <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:16px;">';
        echo '      <div style="font-size:12px; color:var(--muted); display:flex; flex-direction:column; gap:4px;">';
        echo '          <span style="display:flex; align-items:center; gap:4px;"><i data-lucide="calendar" style="width:14px;"></i> '.$dueDateStr.'</span>';
        echo '          <span style="display:flex; align-items:center; gap:4px;"><i data-lucide="user" style="width:14px;"></i> '.htmlspecialchars($task['creator_name']).'</span>';
        echo '      </div>';
        
        // Avatars
        echo '      <div class="tm-avatars">';
        $showLimit = 3;
        $count = 0;
        foreach ($task['assignees'] as $a) {
            if ($count < $showLimit) {
                $initials = mb_substr($a['user_name'], 0, 2);
                echo '<div class="tm-avatar" title="'.htmlspecialchars($a['user_name']).'">'.strtoupper($initials).'</div>';
            }
            $count++;
        }
        if ($count > $showLimit) {
            echo '<div class="tm-avatar-more">+'.($count - $showLimit).'</div>';
        }
        echo '      </div>';
        echo '  </div>';
        
        if ($isManagement || $viewContext === 'dept_tasks' || $viewContext === 'all_tasks') {
            echo '  <div style="margin-top:auto; padding-top:12px; border-top:1px solid var(--border);">';
            echo '      <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:4px; color:var(--muted);">';
            echo '          <span>İlerleme</span><span style="font-weight:600; color:var(--text);">'.$completed.' / '.$total.'</span>';
            echo '      </div>';
            echo '      <div class="tm-progress-bg">';
            echo '          <div class="tm-progress-fill" style="width:'.$progress.'%;"></div>';
            echo '      </div>';
            echo '  </div>';
        }

        if ($myAssignment) {
            $statusColors = [
                'pending' => 'var(--warning)',
                'acknowledged' => 'var(--info)',
                'completed' => 'var(--success)'
            ];
            $statusLabels = [
                'pending' => 'Bekliyor',
                'acknowledged' => 'Görev Alındı',
                'completed' => 'Tamamlandı'
            ];
            $myStatus = $myAssignment['status'];
            echo '  <div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; margin-top:16px; padding-top:16px; border-top:1px dashed var(--border);">';
            echo '      <div style="display:flex; align-items:center; gap:8px;">';
            echo '          <span style="font-size:12px; font-weight:600; color:var(--muted);">Durumum:</span>';
            echo '          <span style="padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; color:#fff; background:'.$statusColors[$myStatus].'; display:flex; align-items:center; gap:4px;">';
            if ($myStatus === 'completed') echo '<i data-lucide="check" style="width:12px;"></i> ';
            echo $statusLabels[$myStatus].'</span>';
            echo '      </div>';
            
            if ($myStatus !== 'completed') {
                if ($myStatus === 'pending') {
                    echo '      <button class="btn btn-sm" style="background:var(--info); color:#fff; flex:1; min-width:120px;" onclick="updateMyStatus('.$myAssignment['id'].', \'acknowledged\')">Görev Alındı</button>';
                } else {
                    echo '      <button class="btn btn-sm" style="background:var(--success); color:#fff; flex:1; min-width:120px;" onclick="updateMyStatus('.$myAssignment['id'].', \'completed\')">Tamamlandı</button>';
                }
            }
            echo '  </div>';
        }
        
        echo '</div>';
    }
    echo '</div>';
}
?>

<!-- Modals -->
<div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:999; backdrop-filter:blur(2px);"></div>

<!-- Task Details Modal -->
<div id="taskDetailsModal" class="tm-modal">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2 id="tdTitle" style="font-size:20px; font-weight:600;"></h2>
        <button class="btn btn-icon" onclick="closeModals()"><i data-lucide="x"></i></button>
    </div>
    
    <div style="font-size:14px; margin-bottom:24px;">
        <p id="tdDesc" style="color:var(--text); margin-bottom:16px;"></p>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; padding:12px; background:var(--bg-hover); border-radius:8px;">
            <div>
                <div style="font-size:11px; color:var(--muted); text-transform:uppercase;">Oluşturan</div>
                <div id="tdCreator" style="font-weight:500;"></div>
            </div>
            <div>
                <div style="font-size:11px; color:var(--muted); text-transform:uppercase;">Son Tarih</div>
                <div id="tdDue" style="font-weight:500;"></div>
            </div>
        </div>
    </div>
    
    <h3 style="font-size:14px; font-weight:600; margin-bottom:12px; border-bottom:1px solid var(--border); padding-bottom:8px;">Atamalar</h3>
    <div id="tdAssignees" style="display:flex; flex-direction:column; gap:8px; margin-bottom:24px; max-height:200px; overflow-y:auto;"></div>

    <h3 style="font-size:14px; font-weight:600; margin-bottom:12px; border-bottom:1px solid var(--border); padding-bottom:8px;">Yorumlar</h3>
    <div id="tdComments" style="display:flex; flex-direction:column; gap:12px; margin-bottom:16px; max-height:300px; overflow-y:auto; padding-right:8px;">
        <!-- Comments injected here -->
    </div>
    
    <form id="commentForm" onsubmit="submitComment(event)">
        <input type="hidden" id="tdTaskId" name="task_id">
        <input type="hidden" name="action" value="add_comment">
        <div style="display:flex; gap:8px;">
            <input type="text" name="comment" class="form-control" placeholder="Yorum yaz..." required style="flex:1;">
            <button type="submit" class="btn btn-primary"><i data-lucide="send" style="width:16px;"></i></button>
        </div>
    </form>
</div>

<?php if ($isManagement): ?>
<!-- Create Task Modal -->
<div id="createTaskModal" class="tm-modal">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2 style="font-size:20px; font-weight:600;">Yeni Görev</h2>
        <button class="btn btn-icon" onclick="closeModals()"><i data-lucide="x"></i></button>
    </div>
    <form method="POST" action="">
        <?= View::csrf() ?>
        <input type="hidden" name="action" value="create_task">
        <div class="form-group" style="margin-bottom:16px;">
            <label>Başlık</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group" style="margin-bottom:16px;">
            <label>Detaylı Açıklama</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group" style="margin-bottom:16px;">
            <label>Son Teslim Tarihi (Opsiyonel)</label>
            <input type="datetime-local" name="due_date" class="form-control">
        </div>
        
        <div class="form-group" style="margin-bottom:16px;">
            <label>Takıma Ata (Toplu)</label>
            <select name="team_ids[]" class="form-control" multiple style="height:80px;">
                <?php foreach ($teams as $t): ?>
                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?> (<?= count($t['members']) ?> Kişi)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="margin-bottom:24px;">
            <label>Kişilere Ata (Bireysel)</label>
            <select name="user_ids[]" class="form-control" multiple style="height:120px;">
                <?php foreach ($allUsers as $u): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <small style="color:var(--muted); display:block; margin-top:4px;">Birden fazla seçim için CTRL/CMD basılı tutun.</small>
        </div>
        <div style="display:flex; justify-content:flex-end; gap:8px;">
            <button type="button" class="btn btn-outline" onclick="closeModals()">İptal</button>
            <button type="submit" class="btn btn-primary">Görev Oluştur</button>
        </div>
    </form>
</div>

<!-- Create Team Modal -->
<div id="createTeamModal" class="tm-modal">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2 style="font-size:20px; font-weight:600;">Mini Takım Oluştur</h2>
        <button class="btn btn-icon" onclick="closeModals()"><i data-lucide="x"></i></button>
    </div>
    <form method="POST" action="">
        <?= View::csrf() ?>
        <input type="hidden" name="action" value="create_team">
        <div class="form-group" style="margin-bottom:16px;">
            <label>Takım Adı</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group" style="margin-bottom:16px;">
            <label>Açıklama (Opsiyonel)</label>
            <input type="text" name="description" class="form-control">
        </div>
        <div class="form-group" style="margin-bottom:24px;">
            <label>Takım Üyeleri</label>
            <select name="user_ids[]" class="form-control" multiple style="height:150px;" required>
                <?php foreach ($allUsers as $u): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="display:flex; justify-content:flex-end; gap:8px;">
            <button type="button" class="btn btn-outline" onclick="closeModals()">İptal</button>
            <button type="submit" class="btn btn-primary">Takım Oluştur</button>
        </div>
    </form>
</div>
<?php endif; ?>

<script>
// Tabs logic
document.querySelectorAll('.tm-tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tm-tab-btn').forEach(b => {
            b.classList.remove('active');
            b.style.borderBottomColor = 'transparent';
            b.style.color = 'var(--muted)';
        });
        document.querySelectorAll('.tab-content').forEach(c => {
            c.style.display = 'none';
        });
        
        btn.classList.add('active');
        btn.style.borderBottomColor = 'var(--primary)';
        btn.style.color = 'var(--text)';
        
        const tabId = 'tab-' + btn.getAttribute('data-tab');
        document.getElementById(tabId).style.display = 'block';
    });
});

function closeModals() {
    document.getElementById('modalOverlay').style.display = 'none';
    if(document.getElementById('createTaskModal')) document.getElementById('createTaskModal').style.display = 'none';
    if(document.getElementById('createTeamModal')) document.getElementById('createTeamModal').style.display = 'none';
    if(document.getElementById('taskDetailsModal')) document.getElementById('taskDetailsModal').style.display = 'none';
}

function openCreateTaskModal() {
    document.getElementById('modalOverlay').style.display = 'block';
    document.getElementById('createTaskModal').style.display = 'block';
}

function openCreateTeamModal() {
    document.getElementById('modalOverlay').style.display = 'block';
    document.getElementById('createTeamModal').style.display = 'block';
}

function openTaskDetails(task, currentUserId, isManagement) {
    document.getElementById('modalOverlay').style.display = 'block';
    document.getElementById('taskDetailsModal').style.display = 'block';
    
    document.getElementById('tdTitle').textContent = task.title;
    document.getElementById('tdDesc').textContent = task.description;
    document.getElementById('tdCreator').textContent = task.creator_name;
    document.getElementById('tdDue').textContent = task.due_date ? new Date(task.due_date).toLocaleString('tr-TR') : 'Süresiz';
    document.getElementById('tdTaskId').value = task.id;
    
    const assigneesDiv = document.getElementById('tdAssignees');
    assigneesDiv.innerHTML = '';
    
    const statuses = {
        'pending': '<span style="color:var(--warning); font-size:12px; font-weight:600;">Bekliyor</span>',
        'acknowledged': '<span style="color:var(--info); font-size:12px; font-weight:600;">Görev Alındı</span>',
        'completed': '<span style="color:var(--success); font-size:12px; font-weight:600;">Tamamlandı</span>'
    };
    
    task.assignees.forEach(a => {
        let actionHtml = '';
        if (isManagement && a.status !== 'completed') {
            // Management can force-complete anyone
            actionHtml = `<button class="btn btn-sm btn-outline" style="padding:2px 6px; font-size:11px;" onclick="updateMyStatus(${a.id}, 'completed')">Bitir</button>`;
        }
        
        assigneesDiv.innerHTML += `
            <div style="display:flex; justify-content:space-between; align-items:center; padding:8px; background:var(--bg-hover); border-radius:6px;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <div style="width:24px; height:24px; border-radius:50%; background:var(--border); display:flex; align-items:center; justify-content:center; font-size:10px;">${a.name.substring(0,2).toUpperCase()}</div>
                    <span style="font-size:13px; font-weight:500;">${a.name}</span>
                </div>
                <div style="display:flex; align-items:center; gap:12px;">
                    ${statuses[a.status]}
                    ${actionHtml}
                </div>
            </div>
        `;
    });
    
    loadComments(task.id);
}

function updateMyStatus(assigneeId, newStatus) {
    if(!confirm("Durumu güncellemek istediğinize emin misiniz?")) return;
    
    const formData = new URLSearchParams();
    formData.append('action', 'update_status');
    formData.append('assignee_id', assigneeId);
    formData.append('status', newStatus);
    formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]')?.content || '<?= $_SESSION['csrf_token'] ?>');
    
    fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData.toString()
    }).then(r => r.json()).then(res => {
        if(res.success) {
            window.location.reload();
        } else {
            alert(res.error || 'Bir hata oluştu.');
        }
    });
}

function loadComments(taskId) {
    const commentsDiv = document.getElementById('tdComments');
    commentsDiv.innerHTML = '<div style="text-align:center; padding:10px;"><i data-lucide="loader" class="spin"></i> Yükleniyor...</div>';
    if(window.lucide) lucide.createIcons();
    
    const formData = new URLSearchParams();
    formData.append('action', 'get_comments');
    formData.append('task_id', taskId);
    formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?>');
    
    fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData.toString()
    }).then(r => r.json()).then(res => {
        commentsDiv.innerHTML = '';
        if(res.success) {
            if(res.data.length === 0) {
                commentsDiv.innerHTML = '<div style="font-size:12px; color:var(--muted); text-align:center;">Henüz yorum yapılmamış.</div>';
                return;
            }
            res.data.forEach(c => {
                const date = new Date(c.created_at).toLocaleString('tr-TR', {day:'numeric', month:'short', hour:'2-digit', minute:'2-digit'});
                commentsDiv.innerHTML += `
                    <div style="padding:12px; background:var(--bg-hover); border-radius:8px;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                            <span style="font-size:12px; font-weight:600;">${c.name}</span>
                            <span style="font-size:11px; color:var(--muted);">${date}</span>
                        </div>
                        <div style="font-size:13px; color:var(--text);">${c.comment}</div>
                    </div>
                `;
            });
        }
    });
}

function submitComment(e) {
    e.preventDefault();
    const form = e.target;
    const input = form.querySelector('input[name="comment"]');
    const taskId = form.querySelector('input[name="task_id"]').value;
    const comment = input.value;
    
    const formData = new URLSearchParams();
    formData.append('action', 'add_comment');
    formData.append('task_id', taskId);
    formData.append('comment', comment);
    formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?>');
    
    fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData.toString()
    }).then(r => r.json()).then(res => {
        if(res.success) {
            input.value = '';
            loadComments(taskId); // reload comments
        } else {
            alert(res.error || 'Yorum eklenemedi.');
        }
    });
}
</script>
