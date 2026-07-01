<?php
/**
 * @var array $app
 * @var array $department
 * @var array $user
 * @var bool $canManage
 * @var array $meetings
 * @var array|null $activeMeeting
 * @var array $departmentMembers
 * @var array $approvals
 */
$appUrl = BASE_URL . "/panel/{$department['slug']}/apps/{$app['slug']}";

function getMeetingStatusBadge($status) {
    switch($status) {
        case 'planned': return '<span style="background: rgba(59,130,246,0.1); color: #3b82f6; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Planlandı</span>';
        case 'completed': return '<span style="background: rgba(16,185,129,0.1); color: #10b981; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Tamamlandı (Karar Alındı)</span>';
        case 'cancelled': return '<span style="background: rgba(239,68,68,0.1); color: #ef4444; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">İptal Edildi</span>';
        default: return '';
    }
}
?>

<style>
    .meeting-layout {
        display: flex;
        gap: 24px;
        min-height: 700px;
        flex-wrap: wrap;
    }
    .meeting-sidebar {
        flex: 0 0 320px;
    }
    .meeting-main {
        flex: 1;
        min-width: 400px;
    }
    
    .meeting-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        color: var(--text);
        display: block;
        border-left: 4px solid transparent;
    }
    .meeting-card:hover {
        transform: translateX(4px);
        border-left-color: var(--border);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .meeting-card.active {
        background: linear-gradient(to right, rgba(16,185,129,0.05), rgba(16,185,129,0));
        border-color: rgba(16,185,129,0.3);
        border-left-color: #10b981;
    }
    
    .approval-list {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 16px;
    }
    .approval-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid var(--border);
    }
    .approval-item:last-child {
        border-bottom: none;
    }
</style>

<!-- Header -->
<div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px;">
    <div style="display: flex; align-items: center; gap: 16px;">
        <div style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, rgba(16,185,129,0.2), rgba(16,185,129,0.1)); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" width="28" height="28">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
        </div>
        <div>
            <h3 style="font-size: 24px; font-weight: 700; color: var(--text); margin: 0;"><?= View::escape($app['name']) ?></h3>
            <p style="font-size: 14px; color: var(--muted); margin: 4px 0 0 0;">Toplantı planları, gündemler ve kararlar defteri.</p>
        </div>
    </div>
    
    <?php if ($canManage): ?>
        <button class="btn btn-primary" style="padding: 10px 20px; border-radius: 8px; font-weight: 500; background: #10b981; border-color: #10b981;" onclick="document.getElementById('modal-create-meeting').style.display='flex'">
            + Yeni Toplantı Planla
        </button>
    <?php endif; ?>
</div>

<div class="meeting-layout fade-in-up stagger-1">
    
    <!-- Sidebar: Meetings List -->
    <div class="meeting-sidebar">
        <h4 style="font-size: 13px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px;">Toplantı Geçmişi</h4>
        
        <?php if (empty($meetings)): ?>
            <div style="background: var(--card); border: 1px dashed var(--border); padding: 30px; border-radius: 12px; text-align: center; color: var(--muted);">
                Henüz planlanmış bir toplantı yok.
            </div>
        <?php else: ?>
            <?php foreach ($meetings as $m): ?>
                <a href="<?= $appUrl ?>?meeting=<?= $m['id'] ?>" class="meeting-card <?= $activeMeeting && $activeMeeting['id'] == $m['id'] ? 'active' : '' ?>">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <?= getMeetingStatusBadge($m['status']) ?>
                    </div>
                    <h4 style="font-size: 15px; font-weight: 600; margin: 0 0 8px 0; color: var(--text);"><?= View::escape($m['title']) ?></h4>
                    <div style="font-size: 12px; color: var(--muted); display: flex; align-items: center; gap: 6px;">
                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <?= date('d.m.Y H:i', strtotime($m['meeting_date'])) ?>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Main Content: Meeting Details -->
    <div class="meeting-main">
        <?php if ($activeMeeting): ?>
            <div style="background: var(--card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
                
                <div style="padding: 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <div style="margin-bottom: 8px;"><?= getMeetingStatusBadge($activeMeeting['status']) ?></div>
                        <h2 style="font-size: 24px; font-weight: 700; color: var(--text); margin: 0 0 16px 0;"><?= View::escape($activeMeeting['title']) ?></h2>
                        <div style="display: flex; gap: 20px; font-size: 13px; color: var(--muted); flex-wrap: wrap;">
                            <span style="display: flex; align-items: center; gap: 6px;">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                <?= date('d M Y, H:i', strtotime($activeMeeting['meeting_date'])) ?>
                            </span>
                            <span style="display: flex; align-items: center; gap: 6px;">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                <?= View::escape($activeMeeting['location'] ?? 'Belirtilmedi') ?>
                            </span>
                            <span style="display: flex; align-items: center; gap: 6px;">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                Oluşturan: <?= View::escape($activeMeeting['creator_name']) ?>
                            </span>
                        </div>
                    </div>

                    <?php if ($canManage): ?>
                        <div style="display: flex; gap: 8px;">
                            <form action="<?= $appUrl ?>" method="POST" style="margin:0;" onsubmit="return confirm('Bu toplantıyı silmek istediğinize emin misiniz?');">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                <input type="hidden" name="action" value="delete_meeting">
                                <input type="hidden" name="meeting_id" value="<?= $activeMeeting['id'] ?>">
                                <button type="submit" class="btn btn-outline" style="padding: 8px; border-radius: 8px; color: var(--danger); border-color: transparent;">
                                    <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="display: flex; flex-wrap: wrap;">
                    <div style="flex: 2; min-width: 300px; padding: 24px; border-right: 1px solid var(--border);">
                        <!-- Gündem -->
                        <h4 style="font-size: 16px; font-weight: 700; color: var(--text); margin: 0 0 12px 0; display: flex; align-items: center; gap: 8px;">
                            <svg viewBox="0 0 24 24" width="18" height="18" stroke="#3b82f6" stroke-width="2" fill="none"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                            Gündem Maddeleri
                        </h4>
                        <div style="font-size: 14px; line-height: 1.6; color: var(--text); background: var(--bg); padding: 16px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 30px;">
                            <?= nl2br(View::escape($activeMeeting['agenda'])) ?>
                        </div>

                        <!-- Kararlar (Tutanak) -->
                        <h4 style="font-size: 16px; font-weight: 700; color: var(--text); margin: 0 0 12px 0; display: flex; align-items: center; gap: 8px;">
                            <svg viewBox="0 0 24 24" width="18" height="18" stroke="#10b981" stroke-width="2" fill="none"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            Alınan Kararlar (Karar Defteri)
                        </h4>
                        
                        <?php if ($activeMeeting['status'] === 'completed' && $activeMeeting['decisions']): ?>
                            <div style="font-size: 14px; line-height: 1.6; color: var(--text); background: rgba(16,185,129,0.05); padding: 16px; border-radius: 8px; border: 1px solid rgba(16,185,129,0.2);">
                                <?= nl2br(View::escape($activeMeeting['decisions'])) ?>
                            </div>
                        <?php else: ?>
                            <?php if ($canManage): ?>
                                <div style="background: rgba(245,158,11,0.05); border: 1px dashed rgba(245,158,11,0.4); padding: 20px; border-radius: 8px;">
                                    <form action="<?= $appUrl ?>" method="POST" style="margin: 0;">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <input type="hidden" name="action" value="complete_meeting">
                                        <input type="hidden" name="meeting_id" value="<?= $activeMeeting['id'] ?>">
                                        <p style="font-size: 13px; color: #d97706; margin: 0 0 12px 0; font-weight: 500;">Toplantı tamamlandığında tutanağı/kararları aşağıya girerek onay sürecini başlatın:</p>
                                        <textarea name="decisions" class="form-control" rows="6" style="padding: 12px; border-radius: 8px; margin-bottom: 12px;" required placeholder="Madde 1: ...&#10;Madde 2: ..."></textarea>
                                        <button type="submit" class="btn btn-primary" style="background: #10b981; border-color: #10b981;">Tutanakları Kaydet ve Tamamla</button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <div style="font-size: 13px; color: var(--muted); padding: 16px; border: 1px dashed var(--border); border-radius: 8px; text-align: center;">
                                    Henüz kararlar girilmedi veya toplantı gerçekleşmedi.
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                    </div>

                    <div style="flex: 1; min-width: 250px; padding: 24px; background: rgba(0,0,0,0.01);">
                        <h4 style="font-size: 15px; font-weight: 600; color: var(--text); margin: 0 0 16px 0;">Dijital Karar Onayı</h4>
                        
                        <?php if ($activeMeeting['status'] === 'completed'): ?>
                            
                            <?php if (!isset($approvals[$user['id']])): ?>
                                <form action="<?= $appUrl ?>" method="POST" style="margin-bottom: 24px;">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                    <input type="hidden" name="action" value="approve_meeting">
                                    <input type="hidden" name="meeting_id" value="<?= $activeMeeting['id'] ?>">
                                    <button type="submit" class="btn btn-primary" style="width: 100%; background: #10b981; border-color: #10b981; padding: 12px;">
                                        Karar Defterini Onaylıyorum
                                    </button>
                                </form>
                            <?php else: ?>
                                <div style="margin-bottom: 24px; padding: 12px; background: rgba(16,185,129,0.1); border-radius: 8px; color: #10b981; font-size: 13px; font-weight: 600; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    Bu tutanağı onayladınız.
                                </div>
                            <?php endif; ?>

                            <div class="approval-list">
                                <p style="font-size: 12px; font-weight: 600; color: var(--muted); margin: 0 0 12px 0; text-transform: uppercase;">Departman Üyeleri</p>
                                <?php foreach ($departmentMembers as $member): ?>
                                    <div class="approval-item">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <?php if ($member['avatar']): ?>
                                                <img src="<?= htmlspecialchars(BASE_URL . '/public/' . $member['avatar']) ?>" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">
                                            <?php else: ?>
                                                <div style="width: 24px; height: 24px; border-radius: 50%; background: var(--border); display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold;">
                                                    <?= strtoupper(mb_substr($member['name'], 0, 2)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <span style="font-size: 13px; font-weight: 500; color: var(--text);"><?= View::escape($member['name']) ?></span>
                                        </div>
                                        
                                        <?php if (isset($approvals[$member['id']])): ?>
                                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="#10b981" stroke-width="2" fill="none" title="Onaylandı"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        <?php else: ?>
                                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="var(--muted)" stroke-width="2" fill="none" title="Bekliyor" style="opacity: 0.5;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div style="font-size: 13px; color: var(--muted); text-align: center; padding: 20px; background: var(--bg); border-radius: 12px;">
                                Onay süreci, toplantı tamamlanıp kararlar girildikten sonra açılacaktır.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
        <?php else: ?>
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 400px; border: 2px dashed var(--border); border-radius: 16px; color: var(--muted); background: var(--card);">
                <svg viewBox="0 0 24 24" width="48" height="48" stroke="currentColor" stroke-width="1.5" fill="none" style="margin-bottom: 16px; opacity: 0.5;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                <p>Detayları görmek için sol menüden bir toplantı seçin.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal: Yeni Toplantı -->
<?php if ($canManage): ?>
<div id="modal-create-meeting" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card pop-in" style="width: 100%; max-width: 600px; margin: 20px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div class="card-header" style="background: var(--bg); padding: 20px; border-bottom: 1px solid var(--border);">
            <span style="font-size: 18px; font-weight: 600;">Yeni Toplantı Planla</span>
            <button type="button" onclick="document.getElementById('modal-create-meeting').style.display='none'" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 20px;">&times;</button>
        </div>
        <div class="card-body" style="padding: 24px; max-height: 80vh; overflow-y: auto;">
            <form action="<?= $appUrl ?>" method="POST" style="margin: 0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="create_meeting">
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">Toplantı Başlığı <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Örn: 2024 Yılı Strateji Değerlendirme" required style="padding: 10px 12px; border-radius: 8px;">
                </div>
                
                <div class="form-row" style="display: flex; gap: 16px; margin-bottom: 16px;">
                    <div class="form-group" style="flex: 1; margin: 0;">
                        <label class="form-label" style="font-weight: 500;">Tarih <span style="color:var(--danger);">*</span></label>
                        <input type="date" name="meeting_date" class="form-control" required style="padding: 10px 12px; border-radius: 8px;" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="form-group" style="flex: 1; margin: 0;">
                        <label class="form-label" style="font-weight: 500;">Saat <span style="color:var(--danger);">*</span></label>
                        <input type="time" name="meeting_time" class="form-control" required style="padding: 10px 12px; border-radius: 8px;" value="14:00">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">Konum / Platform</label>
                    <input type="text" name="location" class="form-control" placeholder="Örn: Toplantı Salonu A veya Google Meet Linki" style="padding: 10px 12px; border-radius: 8px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" style="font-weight: 500;">Gündem Maddeleri <span style="color:var(--danger);">*</span></label>
                    <textarea name="agenda" class="form-control" rows="6" style="padding: 12px; border-radius: 8px;" required placeholder="1. Geçen ayın değerlendirmesi...&#10;2. Yeni bütçe planlaması..."></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn btn-outline" style="border-radius: 8px;" onclick="document.getElementById('modal-create-meeting').style.display='none'">İptal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px; background: #10b981; border-color: #10b981;">Planla ve Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
