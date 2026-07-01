<?php
/**
 * @var array $app
 * @var array $department
 * @var array $user
 * @var bool $isDirectorOrAdmin
 * @var array $statuses
 * @var array $sprints
 * @var array|null $activeSprint
 * @var array $groupedTasks
 * @var array $members
 */
$appUrl = BASE_URL . "/panel/{$department['slug']}/apps/{$app['slug']}";
?>

<style>
    /* Premium Agile Kanban Styles */
    .agile-container {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        padding: 10px 10px 30px 10px;
        min-height: 650px;
        scroll-behavior: smooth;
    }
    
    /* Sleek scrollbar for the container */
    .agile-container::-webkit-scrollbar {
        height: 8px;
    }
    .agile-container::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 10px;
    }
    .agile-container::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }
    .agile-container::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .kanban-column {
        flex: 1 1 200px;
        min-width: 200px;
        display: flex;
        flex-direction: column;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .kanban-header {
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255, 255, 255, 0.02);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--border);
    }

    .kanban-tasks {
        padding: 12px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        flex-grow: 1;
        overflow-y: auto;
        min-height: 200px;
        background: linear-gradient(to bottom, rgba(0,0,0,0.01), rgba(0,0,0,0.03));
    }
    
    .kanban-tasks::-webkit-scrollbar {
        width: 4px;
    }
    .kanban-tasks::-webkit-scrollbar-track { background: transparent; }
    .kanban-tasks::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }

    .kanban-task {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 16px;
        cursor: grab;
        transition: all 0.2s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
    }

    .kanban-task:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        border-color: var(--accent, #3b82f6);
    }

    .kanban-task:active {
        cursor: grabbing;
    }

    .kanban-task.dragging {
        opacity: 0.6;
        transform: scale(0.95);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .kanban-tasks.drag-over {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
    }

    .task-priority-high { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); }
    .task-priority-normal { background: rgba(156, 163, 175, 0.15); color: #9ca3af; border: 1px solid rgba(156,163,175,0.3); }
    .task-priority-low { background: rgba(59, 130, 246, 0.15); color: #3b82f6; border: 1px solid rgba(59,130,246,0.3); }

    .task-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid var(--card);
        background: var(--bg);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    .kanban-task:hover .task-avatar {
        transform: scale(1.1);
    }

    .sprint-select {
        background: var(--card);
        border: 1px solid var(--border);
        color: var(--text);
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        outline: none;
        transition: border-color 0.2s;
    }
    .sprint-select:focus {
        border-color: var(--accent, #3b82f6);
    }
</style>

<!-- Header & Sprint Selection -->
<div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px;">
    <div>
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, <?= View::escape($department['color'] ?? '#3b82f6') ?>20, <?= View::escape($department['color'] ?? '#3b82f6') ?>60); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <svg viewBox="0 0 24 24" fill="none" stroke="<?= View::escape($department['color'] ?? '#3b82f6') ?>" stroke-width="2" width="24" height="24">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line>
                </svg>
            </div>
            <div>
                <h3 style="font-size: 22px; font-weight: 700; color: var(--text); letter-spacing: -0.5px; mragin: 0;"><?= View::escape($app['name']) ?></h3>
                <p style="font-size: 13px; color: var(--muted); margin: 2px 0 0 0;">Sprintlerinizi planlayın ve görevleri dinamik olarak yönetin.</p>
            </div>
        </div>
    </div>
    
    <div style="display: flex; gap: 12px; align-items: center;">
        <form method="GET" action="" style="margin:0; display:flex; gap:8px;">
            <select name="sprint_id" class="sprint-select" onchange="this.form.submit()">
                <?php if (empty($sprints)): ?>
                    <option value="">Sprint Bulunamadı</option>
                <?php else: ?>
                    <?php foreach ($sprints as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $activeSprint && $activeSprint['id'] == $s['id'] ? 'selected' : '' ?>>
                            🎯 <?= View::escape($s['name']) ?> <?= $s['is_active'] ? '(Aktif Koşu)' : '' ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </form>

        <?php if ($isDirectorOrAdmin): ?>
            <button class="btn btn-outline" style="border-radius: 8px; padding: 8px 16px; font-weight: 500;" onclick="document.getElementById('modal-create-sprint').style.display='flex'">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" style="margin-right: 6px; vertical-align: text-bottom;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                Yeni Sprint
            </button>
        <?php endif; ?>

        <?php if ($activeSprint): ?>
            <button class="btn btn-primary" style="border-radius: 8px; padding: 8px 20px; font-weight: 500; box-shadow: 0 4px 14px rgba(59, 130, 246, 0.4);" onclick="openTaskModal()">
                + Görev Ekle
            </button>
        <?php endif; ?>
    </div>
</div>

<?php if ($isDirectorOrAdmin && !empty($pendingTasks)): ?>
<div style="margin-bottom: 24px;">
    <h4 style="margin: 0 0 12px 0; font-size: 16px; color: var(--text);">Onay Bekleyen Görevler</h4>
    <div style="display: flex; gap: 16px; overflow-x: auto; padding-bottom: 12px;">
        <?php foreach ($pendingTasks as $ptask): ?>
            <div class="kanban-task" style="min-width: 250px; background: rgba(245, 158, 11, 0.05); border: 1px solid rgba(245, 158, 11, 0.3);">
                <h5 style="margin: 0 0 8px 0; font-size: 14px;"><?= View::escape($ptask['title']) ?></h5>
                <p style="font-size: 12px; color: var(--muted); margin: 0 0 12px 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= View::escape($ptask['description']) ?></p>
                <div style="display: flex; gap: 8px;">
                    <form action="<?= $appUrl ?><?= $activeSprint ? '?sprint_id='.$activeSprint['id'] : '' ?>" method="POST" style="margin:0;">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="hidden" name="action" value="approve_task">
                        <input type="hidden" name="task_id" value="<?= $ptask['id'] ?>">
                        <button type="submit" class="btn btn-primary" style="padding: 4px 10px; font-size: 12px;">Onayla</button>
                    </form>
                    <button class="btn btn-outline" style="padding: 4px 10px; font-size: 12px;" onclick="editTaskModal(<?= htmlspecialchars(json_encode($ptask)) ?>)">Düzenle</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Agile Board Container -->
<div class="agile-container fade-in-up stagger-1">
    <?php foreach ($statuses as $statusKey => $status): ?>
        <div class="kanban-column" data-status="<?= View::escape($statusKey) ?>">
            <!-- Column Header -->
            <div class="kanban-header" style="border-top: 3px solid <?= $status['color'] ?>;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 10px; height: 10px; border-radius: 50%; background: <?= $status['color'] ?>; box-shadow: 0 0 8px <?= $status['color'] ?>;"></div>
                    <span style="font-weight: 600; color: var(--text); font-size: 15px;"><?= View::escape($status['label']) ?></span>
                </div>
                <span class="badge" style="background: var(--card); border: 1px solid var(--border); color: var(--text); font-weight: 600; padding: 4px 10px; border-radius: 12px; font-size: 11px;">
                    <?= count($groupedTasks[$statusKey]) ?>
                </span>
            </div>
            
            <!-- Column Body -->
            <div class="kanban-tasks" style="position: relative;">
                <?php if (empty($groupedTasks[$statusKey])): ?>
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; width: 100%; opacity: 0.4;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="32" height="32" style="margin-bottom: 8px;">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <p style="font-size: 12px; margin: 0;">Sürükleyip bırakın</p>
                    </div>
                <?php endif; ?>

                <?php foreach ($groupedTasks[$statusKey] as $task): ?>
                    <?php 
                        $priorityClass = 'task-priority-normal';
                        if ($task['priority'] === 'high') $priorityClass = 'task-priority-high';
                        if ($task['priority'] === 'low') $priorityClass = 'task-priority-low';
                    ?>
                    <div class="kanban-task" draggable="<?= $isDirectorOrAdmin ? 'true' : 'false' ?>" data-id="<?= $task['id'] ?>">
                        <!-- Task Header -->
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                            <span class="badge <?= $priorityClass ?>" style="font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 6px; letter-spacing: 0.5px;">
                                <?= strtoupper($task['priority'] === 'high' ? 'YÜKSEK' : ($task['priority'] === 'low' ? 'DÜŞÜK' : 'NORMAL')) ?>
                            </span>
                            
                            <?php if ($isDirectorOrAdmin): ?>
                            <button class="btn-task-edit" onclick="editTaskModal(<?= htmlspecialchars(json_encode($task)) ?>)" style="background:none; border:none; color: var(--muted); cursor:pointer; padding: 2px; border-radius: 4px; transition: background 0.2s, color 0.2s;" onmouseover="this.style.background='var(--border)'; this.style.color='var(--text)';" onmouseout="this.style.background='none'; this.style.color='var(--muted)';">
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </button>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Task Title & Desc -->
                        <div style="cursor:pointer;" onclick="viewTaskModal(<?= htmlspecialchars(json_encode($task)) ?>)">
                            <h4 style="font-size: 14px; font-weight: 600; color: var(--text); margin: 0 0 8px 0; line-height: 1.4; word-wrap: break-word;">
                                <?= View::escape($task['title']) ?>
                            </h4>
                            
                            <?php if (!empty($task['description'])): ?>
                                <p style="font-size: 12px; color: var(--muted); line-height: 1.5; margin: 0 0 16px 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= View::escape($task['description']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Task Footer -->
                        <div style="display: flex; justify-content: space-between; align-items: flex-end; border-top: 1px dashed var(--border); padding-top: 12px; margin-top: auto;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <?php if ($task['assignee_id']): ?>
                                    <div class="task-avatar" title="<?= View::escape($task['assignee_name']) ?>">
                                        <?php if (!empty($task['assignee_avatar'])): ?>
                                            <img src="<?= htmlspecialchars(BASE_URL . '/public/' . $task['assignee_avatar']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <span style="font-size: 10px; font-weight: bold; color: var(--text);"><?= strtoupper(mb_substr($task['assignee_name'], 0, 2)) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <span style="font-size: 11px; font-weight: 500; color: var(--muted);"><?= View::escape(mb_substr($task['assignee_name'], 0, 10)) ?>...</span>
                                <?php else: ?>
                                    <div class="task-avatar" style="border-style: dashed;">
                                        <svg viewBox="0 0 24 24" width="12" height="12" stroke="var(--muted)" stroke-width="2" fill="none"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <span style="font-size: 11px; font-weight: 600; color: var(--muted); background: var(--bg); padding: 2px 6px; border-radius: 4px;">#<?= $task['id'] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal: Yeni Sprint -->
<div id="modal-create-sprint" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card pop-in" style="width: 100%; max-width: 450px; margin: 20px; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div class="card-header" style="background: var(--bg); padding: 20px; border-bottom: 1px solid var(--border);">
            <span style="font-size: 18px; font-weight: 600;">Yeni Sprint Oluştur</span>
            <button type="button" onclick="document.getElementById('modal-create-sprint').style.display='none'" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 20px;">&times;</button>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="<?= $appUrl ?>" method="POST" style="margin: 0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="create_sprint">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">Sprint Adı <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Örn: Q3 Sprint 1" required style="padding: 10px 12px; border-radius: 8px;">
                </div>
                <div class="form-row" style="display: flex; gap: 16px; margin-bottom: 24px;">
                    <div class="form-group" style="flex: 1; margin: 0;">
                        <label class="form-label" style="font-weight: 500;">Başlangıç Tarihi</label>
                        <input type="date" name="start_date" class="form-control" style="padding: 10px 12px; border-radius: 8px;">
                    </div>
                    <div class="form-group" style="flex: 1; margin: 0;">
                        <label class="form-label" style="font-weight: 500;">Bitiş Tarihi</label>
                        <input type="date" name="end_date" class="form-control" style="padding: 10px 12px; border-radius: 8px;">
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn btn-outline" style="border-radius: 8px;" onclick="document.getElementById('modal-create-sprint').style.display='none'">İptal Et</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px;">Koşuyu Başlat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Görev -->
<div id="modal-task" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card pop-in" style="width: 100%; max-width: 550px; margin: 20px; max-height: 90vh; overflow-y: auto; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div class="card-header" style="background: var(--bg); padding: 20px; border-bottom: 1px solid var(--border);">
            <span id="task-modal-title" style="font-size: 18px; font-weight: 600;">Yeni Görev Ekle</span>
            <button type="button" onclick="document.getElementById('modal-task').style.display='none'" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 20px;">&times;</button>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="<?= $appUrl ?><?= $activeSprint ? '?sprint_id='.$activeSprint['id'] : '' ?>" method="POST" style="margin: 0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" id="task-action" value="create_task">
                <input type="hidden" name="task_id" id="task-id" value="">
                <input type="hidden" name="sprint_id" value="<?= $activeSprint ? $activeSprint['id'] : '' ?>">
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">Görev Başlığı <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="title" id="task-title" class="form-control" placeholder="Görev ne hakkında?" required style="padding: 10px 12px; border-radius: 8px; font-size: 15px;">
                </div>

                <div class="form-row" style="display: flex; gap: 16px; margin-bottom: 16px;">
                    <div class="form-group" style="flex: 1; margin: 0;">
                        <label class="form-label" style="font-weight: 500;">Durum</label>
                        <select name="status" id="task-status" class="form-control" style="padding: 10px 12px; border-radius: 8px;">
                            <?php foreach ($statuses as $key => $status): ?>
                                <option value="<?= View::escape($key) ?>"><?= View::escape($status['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1; margin: 0;">
                        <label class="form-label" style="font-weight: 500;">Öncelik Derecesi</label>
                        <select name="priority" id="task-priority" class="form-control" style="padding: 10px 12px; border-radius: 8px;">
                            <option value="low">Düşük Öncelik</option>
                            <option value="normal" selected>Normal</option>
                            <option value="high">Yüksek Öncelik</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">Sorumlu Kişi</label>
                    <select name="assignee_id" id="task-assignee" class="form-control" style="padding: 10px 12px; border-radius: 8px;">
                        <option value="">-- Atanmamış --</option>
                        <?php foreach ($members as $member): ?>
                            <option value="<?= $member['id'] ?>"><?= View::escape($member['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" style="font-weight: 500;">Detaylı Açıklama</label>
                    <textarea name="description" id="task-desc" class="form-control" style="min-height: 120px; padding: 12px; border-radius: 8px;" placeholder="Görevin detayları, linkler veya notlar..."></textarea>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 20px;">
                    <div id="task-delete-container" style="display: none;">
                        <?php if ($isDirectorOrAdmin): ?>
                        <button type="button" class="btn" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: none; border-radius: 8px; font-weight: 500; padding: 10px 16px;" onclick="deleteTask()">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" style="margin-right: 4px; vertical-align: text-bottom;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            Görevi Sil
                        </button>
                        <?php endif; ?>
                    </div>
                    <div style="display: flex; gap: 12px; margin-left: auto;">
                        <button type="button" class="btn btn-outline" style="border-radius: 8px; padding: 10px 20px;" onclick="document.getElementById('modal-task').style.display='none'">İptal</button>
                        <button type="submit" class="btn btn-primary" id="task-submit-btn" style="border-radius: 8px; padding: 10px 24px;">Kaydet</button>
                    </div>
                </div>
            </form>

            <form id="delete-task-form" action="<?= $appUrl ?><?= $activeSprint ? '?sprint_id='.$activeSprint['id'] : '' ?>" method="POST" style="display:none;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="delete_task">
                <input type="hidden" name="task_id" id="delete-task-id" value="">
            </form>
        </div>
    </div>
</div>

<!-- Modal: Görev Detayları (Yorum ve Dosyalar) -->
<div id="modal-view-task" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1050; align-items: center; justify-content: center;">
    <div class="card pop-in" style="width: 100%; max-width: 600px; margin: 20px; max-height: 90vh; display: flex; flex-direction: column; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div class="card-header" style="background: var(--bg); padding: 20px; border-bottom: 1px solid var(--border); flex-shrink: 0; align-items: flex-start;">
            <div>
                <span id="view-task-status" class="badge" style="font-size: 10px; margin-bottom: 8px; display: inline-block;">DURUM</span>
                <h3 id="view-task-title" style="margin: 0; font-size: 18px; font-weight: 600;">Görev Başlığı</h3>
            </div>
            <button type="button" onclick="document.getElementById('modal-view-task').style.display='none'" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 24px; line-height: 1;">&times;</button>
        </div>
        <div class="card-body" style="padding: 24px; overflow-y: auto; flex-grow: 1; display: flex; flex-direction: column; gap: 20px;">
            <div>
                <p id="view-task-desc" style="font-size: 14px; color: var(--text); line-height: 1.6; margin:0; white-space: pre-wrap;"></p>
            </div>
            
            <div style="border-top: 1px solid var(--border); padding-top: 20px; flex-grow: 1; display: flex; flex-direction: column;">
                <h4 style="font-size: 15px; font-weight: 600; margin: 0 0 16px 0;">Yorumlar ve Dosyalar</h4>
                <div id="task-comments-list" style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px;">
                    <!-- Yorumlar AJAX ile buraya yüklenecek -->
                </div>
            </div>
        </div>
        <div style="padding: 20px 24px; background: rgba(0,0,0,0.02); border-top: 1px solid var(--border); flex-shrink: 0;">
            <form id="comment-form" onsubmit="submitTaskComment(event)" style="margin:0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" id="view-task-id" name="task_id" value="">
                <input type="hidden" name="action" value="add_task_comment_ajax">
                <div style="background: var(--bg); border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; display: flex; flex-direction: column; gap: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); transition: border-color 0.2s;" onfocusin="this.style.borderColor='var(--accent)'" onfocusout="this.style.borderColor='var(--border)'">
                    <textarea name="comment" id="comment-textarea" placeholder="Bir yorum yazın..." style="border: none; background: transparent; resize: none; font-size: 14px; color: var(--text); outline: none; min-height: 44px; padding: 0; font-family: inherit; line-height: 1.5;"></textarea>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed var(--border); padding-top: 12px;">
                        <div style="display:flex; align-items: center; gap: 12px;">
                            <label style="cursor:pointer; display:flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:10px; background: rgba(0,0,0,0.04); color:var(--muted); transition: all 0.2s;" onmouseover="this.style.background='rgba(0,0,0,0.08)'; this.style.color='var(--text)';" onmouseout="this.style.background='rgba(0,0,0,0.04)'; this.style.color='var(--muted)';">
                                <input type="file" name="attachment" id="comment-file" style="display:none;" onchange="document.getElementById('file-name-display').innerText = this.files[0] ? this.files[0].name : ''">
                                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
                            </label>
                            <span id="file-name-display" style="font-size:12px; color:var(--accent); font-weight:500; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></span>
                        </div>
                        <button type="submit" class="btn btn-primary" style="padding: 8px 18px; border-radius: 10px; font-size: 14px; font-weight: 500; display:flex; align-items:center; gap:8px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
                            <span>Gönder</span>
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Modal Logic
function openTaskModal() {
    document.getElementById('task-action').value = 'create_task';
    document.getElementById('task-id').value = '';
    document.getElementById('task-title').value = '';
    document.getElementById('task-desc').value = '';
    document.getElementById('task-status').value = 'backlog';
    document.getElementById('task-priority').value = 'normal';
    document.getElementById('task-assignee').value = '';
    document.getElementById('task-modal-title').innerText = 'Yeni Görev Ekle';
    document.getElementById('task-submit-btn').innerText = 'Görevi Oluştur';
    document.getElementById('task-delete-container').style.display = 'none';
    document.getElementById('modal-task').style.display = 'flex';
}

function editTaskModal(task) {
    document.getElementById('task-action').value = 'edit_task';
    document.getElementById('task-id').value = task.id;
    document.getElementById('task-title').value = task.title;
    document.getElementById('task-desc').value = task.description || '';
    document.getElementById('task-status').value = task.status;
    document.getElementById('task-priority').value = task.priority;
    document.getElementById('task-assignee').value = task.assignee_id || '';
    document.getElementById('task-modal-title').innerText = 'Görevi Düzenle';
    document.getElementById('task-submit-btn').innerText = 'Güncelle';
    document.getElementById('task-delete-container').style.display = 'block';
    document.getElementById('delete-task-id').value = task.id;
    document.getElementById('modal-task').style.display = 'flex';
}

function deleteTask() {
    if(confirm('Bu görevi tamamen silmek istediğinize emin misiniz? Bu işlem geri alınamaz.')) {
        document.getElementById('delete-task-form').submit();
    }
}

function viewTaskModal(task) {
    document.getElementById('view-task-id').value = task.id;
    document.getElementById('view-task-title').innerText = task.title;
    document.getElementById('view-task-desc').innerText = task.description || 'Açıklama bulunmuyor.';
    
    let priorityColor = '#9ca3af';
    if(task.priority === 'high') priorityColor = '#ef4444';
    if(task.priority === 'low') priorityColor = '#3b82f6';
    
    document.getElementById('view-task-status').innerText = task.status.toUpperCase();
    document.getElementById('view-task-status').style.background = priorityColor + '20';
    document.getElementById('view-task-status').style.color = priorityColor;
    document.getElementById('view-task-status').style.border = '1px solid ' + priorityColor + '40';

    loadTaskComments(task.id);
    document.getElementById('modal-view-task').style.display = 'flex';
}

function loadTaskComments(taskId) {
    const list = document.getElementById('task-comments-list');
    list.innerHTML = '<div style="text-align:center; padding: 20px; color:var(--muted);"><svg class="spin" viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"><circle cx="12" cy="12" r="10"></circle><path d="M12 2v4"></path></svg></div>';
    
    const formData = new FormData();
    formData.append('action', 'get_task_comments_ajax');
    formData.append('task_id', taskId);
    formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?? '' ?>');

    fetch('<?= $appUrl ?>', {
        method: 'POST',
        body: formData,
        headers: {'X-Requested-With': 'XMLHttpRequest'}
    }).then(res => res.json()).then(data => {
        if(data.success) {
            list.innerHTML = '';
            if(data.comments.length === 0) {
                list.innerHTML = '<p style="font-size:12px; color:var(--muted); text-align:center; margin:0;">Henüz yorum veya dosya eklenmemiş.</p>';
                return;
            }
            
            data.comments.forEach(c => {
                let avatarHtml = `<div style="width:36px; height:36px; border-radius:50%; background:var(--border); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:bold; color:var(--muted); flex-shrink: 0;">${c.user_name.substring(0,2).toUpperCase()}</div>`;
                if(c.user_avatar) {
                    avatarHtml = `<img src="${c.user_avatar}" style="width:36px; height:36px; border-radius:50%; object-fit:cover; flex-shrink: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">`;
                }
                
                let fileHtml = '';
                if(c.file_path) {
                    fileHtml = `<div style="margin-top:${c.comment ? '10px' : '0'};"><a href="${c.file_path}" target="_blank" style="display:inline-flex; align-items:center; gap:8px; font-size:12px; font-weight:500; background:rgba(59, 130, 246, 0.08); padding:8px 14px; border-radius:8px; border:1px solid rgba(59, 130, 246, 0.2); color:var(--accent); text-decoration:none; transition: all 0.2s;" onmouseover="this.style.background='rgba(59, 130, 246, 0.15)';" onmouseout="this.style.background='rgba(59, 130, 246, 0.08)';"><svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> ${c.file_name}</a></div>`;
                }

                let commentText = c.comment ? `<p style="margin:0; font-size:14px; line-height:1.6; color:var(--text); white-space:pre-wrap;">${c.comment}</p>` : '';

                list.innerHTML += `
                    <div style="display:flex; gap:12px; margin-bottom: 8px;">
                        ${avatarHtml}
                        <div style="display: flex; flex-direction: column; align-items: flex-start; max-width: 85%;">
                            <div style="display:flex; align-items: baseline; gap:8px; margin-bottom: 4px; padding-left: 2px;">
                                <span style="font-size:13px; font-weight:600; color:var(--text);">${c.user_name}</span>
                                <span style="font-size:11px; color:var(--muted);">${c.created_at_formatted}</span>
                            </div>
                            <div style="background: var(--bg); padding: 12px 16px; border-radius: 4px 16px 16px 16px; border: 1px solid var(--border); box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                                ${commentText}
                                ${fileHtml}
                            </div>
                        </div>
                    </div>
                `;
            });
        }
    });
}

function submitTaskComment(e) {
    e.preventDefault();
    const form = e.target;
    const taskId = document.getElementById('view-task-id').value;
    const comment = document.getElementById('comment-textarea').value.trim();
    const file = document.getElementById('comment-file').files[0];
    
    if(!comment && !file) return;
    
    const formData = new FormData(form);
    
    const btn = form.querySelector('button[type="submit"]');
    const oldBtnText = btn.innerText;
    btn.innerText = 'Gönderiliyor...';
    btn.disabled = true;

    fetch('<?= $appUrl ?>', {
        method: 'POST',
        body: formData,
        headers: {'X-Requested-With': 'XMLHttpRequest'}
    }).then(res => res.json()).then(data => {
        btn.innerText = oldBtnText;
        btn.disabled = false;
        if(data.success) {
            document.getElementById('comment-textarea').value = '';
            document.getElementById('comment-file').value = '';
            document.getElementById('file-name-display').innerText = '';
            loadTaskComments(taskId);
        } else {
            alert(data.message || 'Bir hata oluştu.');
        }
    }).catch(err => {
        btn.innerText = oldBtnText;
        btn.disabled = false;
        alert('Bağlantı hatası.');
    });
}

const tasks = document.querySelectorAll('.kanban-task[draggable="true"]');
const columns = document.querySelectorAll('.kanban-tasks');

let draggedTask = null;

tasks.forEach(task => {
    task.addEventListener('dragstart', (e) => {
        draggedTask = task;
        // Fix for ghost image to look identical
        setTimeout(() => task.classList.add('dragging'), 0);
    });

    task.addEventListener('dragend', () => {
        task.classList.remove('dragging');
        draggedTask = null;
    });
});

columns.forEach(column => {
    column.addEventListener('dragover', e => {
        e.preventDefault();
        column.classList.add('drag-over');
        const afterElement = getDragAfterElement(column, e.clientY);
        const dragging = document.querySelector('.dragging');
        if (afterElement == null) {
            column.appendChild(dragging);
        } else {
            column.insertBefore(dragging, afterElement);
        }
    });

    column.addEventListener('dragleave', () => {
        column.classList.remove('drag-over');
    });

    column.addEventListener('drop', e => {
        column.classList.remove('drag-over');
        if(!draggedTask) return;
        
        const newStatus = column.closest('.kanban-column').dataset.status;
        const taskId = draggedTask.dataset.id;
        
        // Visual polish for drop
        draggedTask.style.transform = 'scale(1.02)';
        setTimeout(() => draggedTask.style.transform = '', 150);

        // Update badge counters
        document.querySelectorAll('.kanban-column').forEach(col => {
            const count = col.querySelectorAll('.kanban-task').length;
            col.querySelector('.kanban-header .badge').innerText = count;
        });

        // Calculate new order
        const columnTasks = [...column.querySelectorAll('.kanban-task')];
        const taskOrderData = columnTasks.map((t, index) => ({
            id: t.dataset.id,
            order: index
        }));

        // Send AJAX request for status
        const formData = new FormData();
        formData.append('action', 'update_status_ajax');
        formData.append('task_id', taskId);
        formData.append('status', newStatus);
        formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?? '' ?>');
        
        const orderIndex = taskOrderData.findIndex(t => t.id == taskId);
        formData.append('order', orderIndex > -1 ? orderIndex : 0);

        fetch('<?= $appUrl ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(res => res.json()).then(data => {
            if(data.success) {
                const orderForm = new FormData();
                orderForm.append('action', 'update_order_ajax');
                orderForm.append('tasks', JSON.stringify(taskOrderData));
                orderForm.append('csrf_token', '<?= $_SESSION['csrf_token'] ?? '' ?>');
                fetch('<?= $appUrl ?>', {
                    method: 'POST',
                    body: orderForm,
                    headers: {'X-Requested-With': 'XMLHttpRequest'}
                });
            }
        });
    });
});

function getDragAfterElement(container, y) {
    const draggableElements = [...container.querySelectorAll('.kanban-task:not(.dragging)')];

    return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect();
        const offset = y - box.top - box.height / 2;
        if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: child };
        } else {
            return closest;
        }
    }, { offset: Number.NEGATIVE_INFINITY }).element;
}
</script>
