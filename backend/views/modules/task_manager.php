<div class="panel-header fade-in-up">
    <div>
        <h1 class="panel-title" style="display:flex; align-items:center; gap:8px;">
            <i data-lucide="check-square" style="width:24px; height:24px; color:var(--primary);"></i>
            Görev Yönetimi
        </h1>
        <p class="panel-subtitle">Kişisel ve ekip görevlerini takip edin</p>
    </div>
    <?php if ($isManagement): ?>
    <div class="panel-header-actions">
        <button class="btn btn-primary" onclick="openCreateTaskModal()">
            <i data-lucide="plus"></i> Yeni Görev
        </button>
    </div>
    <?php endif; ?>
</div>

<div class="panel-content fade-in-up stagger-1">
    <div class="tabs-container" style="margin-bottom: 24px; border-bottom: 1px solid var(--border);">
        <div class="tabs" style="display:flex; gap:16px;">
            <button class="tab-btn active" data-tab="my-tasks" style="padding:12px 16px; background:none; border:none; border-bottom: 2px solid var(--primary); color:var(--text); font-weight:500; cursor:pointer;">Görevlerim</button>
            <?php if ($isDirector && !$isManagement): ?>
            <button class="tab-btn" data-tab="dept-tasks" style="padding:12px 16px; background:none; border:none; border-bottom: 2px solid transparent; color:var(--muted); font-weight:500; cursor:pointer;">Departman Görevleri</button>
            <?php endif; ?>
            <?php if ($isManagement): ?>
            <button class="tab-btn" data-tab="all-tasks" style="padding:12px 16px; background:none; border:none; border-bottom: 2px solid transparent; color:var(--muted); font-weight:500; cursor:pointer;">Tüm Görevler</button>
            <button class="tab-btn" data-tab="mini-teams" style="padding:12px 16px; background:none; border:none; border-bottom: 2px solid transparent; color:var(--muted); font-weight:500; cursor:pointer;">Mini Takımlar</button>
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
        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px;">
            <?php foreach ($teams as $team): ?>
            <div class="panel-card p-4">
                <h3 style="font-weight:600; margin-bottom:8px; display:flex; align-items:center; gap:8px;">
                    <i data-lucide="shield" style="width:18px; color:var(--primary);"></i>
                    <?= htmlspecialchars($team['name']) ?>
                </h3>
                <p style="color:var(--muted); font-size:13px; margin-bottom:16px;"><?= nl2br(htmlspecialchars($team['description'] ?? '')) ?></p>
                
                <div style="font-size:12px; font-weight:600; margin-bottom:8px; color:var(--muted);">ÜYELER (<?= count($team['members']) ?>)</div>
                <div style="display:flex; flex-wrap:wrap; gap:8px;">
                    <?php foreach ($team['members'] as $m): ?>
                    <span style="padding:4px 8px; background:var(--bg-hover); border-radius:4px; font-size:12px;">
                        <?= htmlspecialchars($m['name']) ?>
                    </span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($teams)): ?>
            <p style="color:var(--muted);">Henüz bir mini takım oluşturulmadı.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

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
        echo '<div style="padding: 40px; text-align:center; color:var(--muted); background:var(--card); border-radius:12px; border:1px solid var(--border);">Bu kategoride görev bulunmuyor.</div>';
        return;
    }

    echo '<div style="display:flex; flex-direction:column; gap:16px;">';
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
        
        echo '<div class="panel-card p-4" style="border-left: 4px solid var(--primary);">';
        echo '  <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">';
        echo '      <div>';
        echo '          <h3 style="font-size:16px; font-weight:600; margin-bottom:4px;">'.htmlspecialchars($task['title']).'</h3>';
        echo '          <div style="font-size:12px; color:var(--muted); display:flex; gap:16px;">';
        echo '              <span><i data-lucide="calendar" style="width:14px; vertical-align:middle;"></i> Son: '.$dueDateStr.'</span>';
        echo '              <span><i data-lucide="user" style="width:14px; vertical-align:middle;"></i> Açan: '.htmlspecialchars($task['creator_name']).'</span>';
        echo '          </div>';
        echo '      </div>';
        echo '      <button class="btn btn-sm btn-outline" onclick="openTaskDetails('.htmlspecialchars(json_encode($task)).', '.(int)$currentUserId.', '.(int)$isManagement.')">Detaylar</button>';
        echo '  </div>';
        
        echo '  <p style="font-size:14px; color:var(--text); margin-bottom:16px;">'.nl2br(htmlspecialchars($task['description'])).'</p>';
        
        if ($isManagement || $viewContext === 'dept_tasks') {
            echo '  <div style="margin-bottom:12px;">';
            echo '      <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:4px; color:var(--muted);"><span>İlerleme</span><span>'.$completed.' / '.$total.' Tamamlandı</span></div>';
            echo '      <div style="height:6px; background:var(--bg-hover); border-radius:3px; overflow:hidden;">';
            echo '          <div style="height:100%; width:'.$progress.'%; background:var(--primary); transition:width 0.3s;"></div>';
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
            echo '  <div style="display:flex; align-items:center; gap:12px; margin-top:16px; padding-top:16px; border-top:1px solid var(--border);">';
            echo '      <span style="font-size:13px; font-weight:600;">Benim Durumum:</span>';
            echo '      <span style="padding:4px 8px; border-radius:4px; font-size:12px; font-weight:600; color:#fff; background:'.$statusColors[$myStatus].'">'.$statusLabels[$myStatus].'</span>';
            
            if ($myStatus !== 'completed') {
                if ($myStatus === 'pending') {
                    echo '      <button class="btn btn-sm" style="background:var(--info); color:#fff;" onclick="updateMyStatus('.$myAssignment['id'].', \'acknowledged\')">Görev Alındı İşaretle</button>';
                }
                echo '      <button class="btn btn-sm" style="background:var(--success); color:#fff;" onclick="updateMyStatus('.$myAssignment['id'].', \'completed\')">Tamamlandı İşaretle</button>';
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
<div id="taskDetailsModal" class="panel-card" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); width:90%; max-width:600px; z-index:1000; max-height:90vh; overflow-y:auto; padding:24px;">
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
<div id="createTaskModal" class="panel-card" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); width:90%; max-width:500px; z-index:1000; padding:24px;">
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
<div id="createTeamModal" class="panel-card" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); width:90%; max-width:500px; z-index:1000; padding:24px;">
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
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => {
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
