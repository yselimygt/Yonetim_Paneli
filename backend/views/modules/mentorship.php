<?php
/**
 * @var array $app
 * @var array $department
 * @var array $user
 * @var bool $canManage
 * @var array $departmentMembers
 * @var array $pairs
 * @var int $activePairId
 * @var array|null $userPair
 * @var array $notes
 */
$appUrl = BASE_URL . "/panel/{$department['slug']}/apps/{$app['slug']}";

function getAvatarHtml($avatarStr, $nameStr, $size = 32) {
    if ($avatarStr) {
        return '<img src="'.htmlspecialchars(BASE_URL . '/public/' . $avatarStr).'" style="width:'.$size.'px; height:'.$size.'px; border-radius:50%; object-fit:cover;">';
    } else {
        $initials = strtoupper(mb_substr($nameStr, 0, 2));
        return '<div style="width:'.$size.'px; height:'.$size.'px; border-radius:50%; background:var(--border); display:flex; align-items:center; justify-content:center; font-size:'.($size/2.5).'px; font-weight:bold;">'.$initials.'</div>';
    }
}
?>

<style>
    .mentor-layout {
        display: flex;
        gap: 24px;
        min-height: 700px;
        flex-wrap: wrap;
    }
    .mentor-sidebar {
        flex: 0 0 320px;
    }
    .mentor-main {
        flex: 1;
        min-width: 400px;
    }
    
    .pair-card {
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
    }
    .pair-card:hover {
        border-color: #8b5cf6;
        box-shadow: 0 4px 12px rgba(139,92,246,0.1);
    }
    .pair-card.active {
        background: linear-gradient(135deg, rgba(139,92,246,0.05), rgba(139,92,246,0.01));
        border-color: #8b5cf6;
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border);
    }
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -35px;
        top: 4px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #8b5cf6;
        border: 3px solid var(--bg);
        box-shadow: 0 0 0 2px rgba(139,92,246,0.3);
    }
    .note-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
</style>

<!-- Header -->
<div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px;">
    <div style="display: flex; align-items: center; gap: 16px;">
        <div style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, rgba(139,92,246,0.2), rgba(139,92,246,0.05)); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2" width="28" height="28">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <div>
            <h3 style="font-size: 24px; font-weight: 700; color: var(--text); margin: 0;"><?= View::escape($app['name']) ?></h3>
            <p style="font-size: 14px; color: var(--muted); margin: 4px 0 0 0;">Usta-Çırak eşleştirmeleri ve gelişim takibi.</p>
        </div>
    </div>
    
    <?php if ($canManage): ?>
        <button class="btn btn-primary" style="padding: 10px 20px; border-radius: 8px; font-weight: 500; background: #8b5cf6; border-color: #8b5cf6;" onclick="document.getElementById('modal-create-pair').style.display='flex'">
            + Yeni Eşleştirme Yap
        </button>
    <?php endif; ?>
</div>

<div class="mentor-layout fade-in-up stagger-1">
    
    <!-- Sidebar: Pairs List -->
    <div class="mentor-sidebar">
        <h4 style="font-size: 13px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px;">Aktif Eşleşmeler</h4>
        
        <?php if (empty($pairs)): ?>
            <div style="background: var(--card); border: 1px dashed var(--border); padding: 30px; border-radius: 12px; text-align: center; color: var(--muted);">
                Şu an aktif bir Mentor-Menti eşleşmesi bulunmuyor.
            </div>
        <?php else: ?>
            <?php foreach ($pairs as $p): ?>
                <a href="<?= $appUrl ?>?pair=<?= $p['id'] ?>" class="pair-card <?= $activePairId == $p['id'] ? 'active' : '' ?>">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 4px; width: 40%;">
                            <?= getAvatarHtml($p['mentor_avatar'], $p['mentor_name'], 40) ?>
                            <span style="font-size: 11px; font-weight: 700; color: #8b5cf6; text-transform: uppercase;">Mentor</span>
                            <span style="font-size: 13px; font-weight: 600; text-align: center; line-height: 1.2;"><?= View::escape($p['mentor_name']) ?></span>
                        </div>
                        
                        <div style="color: var(--muted); opacity: 0.5;">
                            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </div>
                        
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 4px; width: 40%;">
                            <?= getAvatarHtml($p['mentee_avatar'], $p['mentee_name'], 40) ?>
                            <span style="font-size: 11px; font-weight: 700; color: #10b981; text-transform: uppercase;">Menti</span>
                            <span style="font-size: 13px; font-weight: 600; text-align: center; line-height: 1.2;"><?= View::escape($p['mentee_name']) ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Main Content: Timeline -->
    <div class="mentor-main">
        <?php if ($userPair): ?>
            
            <div style="background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 24px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="font-size: 20px; font-weight: 700; margin: 0 0 4px 0;">Gelişim Çizelgesi</h2>
                    <p style="font-size: 14px; color: var(--muted); margin: 0;">Bu alanda mentor veya menti haftalık görüşme notlarını ekleyebilir.</p>
                </div>
                
                <?php if ($canManage || $userPair['mentor_id'] == $user['id'] || $userPair['mentee_id'] == $user['id']): ?>
                    <button class="btn btn-outline" style="border-color: #8b5cf6; color: #8b5cf6;" onclick="document.getElementById('modal-add-note').style.display='flex'">
                        + Görüşme Notu Ekle
                    </button>
                <?php endif; ?>
            </div>
            
            <?php if (empty($notes)): ?>
                <div style="text-align: center; padding: 60px 20px; color: var(--muted); border: 1px dashed var(--border); border-radius: 16px;">
                    Henüz hiçbir görüşme notu veya değerlendirme girilmemiş.
                </div>
            <?php else: ?>
                <div class="timeline">
                    <?php foreach ($notes as $note): ?>
                        <div class="timeline-item">
                            <div class="note-card">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; border-bottom: 1px solid var(--border); padding-bottom: 16px;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <?= getAvatarHtml($note['writer_avatar'], $note['writer_name'], 36) ?>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: var(--text);"><?= View::escape($note['writer_name']) ?></div>
                                            <div style="font-size: 12px; color: var(--muted);"><?= date('d F Y', strtotime($note['meeting_date'])) ?> Tarihli Görüşme</div>
                                        </div>
                                    </div>
                                    <div style="font-size: 11px; color: var(--muted); background: var(--bg); padding: 4px 8px; border-radius: 6px;">
                                        Eklenme: <?= date('d.m.Y H:i', strtotime($note['created_at'])) ?>
                                    </div>
                                </div>
                                <div style="font-size: 14px; line-height: 1.6; color: var(--text);">
                                    <?= nl2br(View::escape($note['note'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($canManage): ?>
                <div style="margin-top: 40px; text-align: center; border-top: 1px solid var(--border); padding-top: 24px;">
                    <form action="<?= $appUrl ?>" method="POST" onsubmit="return confirm('Bu eşleştirmeyi tamamlandı olarak işaretlemek istediğinize emin misiniz?');">
                        <input type="hidden" name="action" value="complete_pair">
                        <input type="hidden" name="pair_id" value="<?= $userPair['id'] ?>">
                        <button type="submit" class="btn btn-outline" style="border-color: #10b981; color: #10b981;">✅ Eşleştirmeyi Başarıyla Tamamla</button>
                    </form>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 400px; border: 2px dashed var(--border); border-radius: 16px; color: var(--muted); background: var(--card);">
                <svg viewBox="0 0 24 24" width="48" height="48" stroke="currentColor" stroke-width="1.5" fill="none" style="margin-bottom: 16px; opacity: 0.5;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                <p>Detayları görmek için sol menüden bir eşleşme seçin.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal: Yeni Eşleştirme (Sadece Yöneticiler) -->
<?php if ($canManage): ?>
<div id="modal-create-pair" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card pop-in" style="width: 100%; max-width: 500px; margin: 20px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div class="card-header" style="background: var(--bg); padding: 20px; border-bottom: 1px solid var(--border);">
            <span style="font-size: 18px; font-weight: 600;">Yeni Mentor - Menti Eşleştirmesi</span>
            <button type="button" onclick="document.getElementById('modal-create-pair').style.display='none'" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 20px;">&times;</button>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="<?= $appUrl ?>" method="POST" style="margin: 0;">
                <input type="hidden" name="action" value="create_pair">
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="font-weight: 500; color: #8b5cf6;">Mentor (Senior Üye) Seçimi</label>
                    <select name="mentor_id" class="form-control" required style="padding: 10px 12px; border-radius: 8px;">
                        <option value="">-- Mentor Seçin --</option>
                        <?php foreach($departmentMembers as $dm): ?>
                            <option value="<?= $dm['id'] ?>"><?= View::escape($dm['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" style="font-weight: 500; color: #10b981;">Menti (Junior Üye) Seçimi</label>
                    <select name="mentee_id" class="form-control" required style="padding: 10px 12px; border-radius: 8px;">
                        <option value="">-- Menti Seçin --</option>
                        <?php foreach($departmentMembers as $dm): ?>
                            <option value="<?= $dm['id'] ?>"><?= View::escape($dm['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn btn-outline" style="border-radius: 8px;" onclick="document.getElementById('modal-create-pair').style.display='none'">İptal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px; background: #8b5cf6; border-color: #8b5cf6;">Eşleştir ve Başlat</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Modal: Görüşme Notu Ekle (Mentor & Menti & Yönetici) -->
<?php if ($userPair && ($canManage || $userPair['mentor_id'] == $user['id'] || $userPair['mentee_id'] == $user['id'])): ?>
<div id="modal-add-note" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card pop-in" style="width: 100%; max-width: 600px; margin: 20px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div class="card-header" style="background: var(--bg); padding: 20px; border-bottom: 1px solid var(--border);">
            <span style="font-size: 18px; font-weight: 600;">Görüşme Notu Ekle</span>
            <button type="button" onclick="document.getElementById('modal-add-note').style.display='none'" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 20px;">&times;</button>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="<?= $appUrl ?>" method="POST" style="margin: 0;">
                <input type="hidden" name="action" value="add_note">
                <input type="hidden" name="pair_id" value="<?= $userPair['id'] ?>">
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">Görüşme Tarihi</label>
                    <input type="date" name="meeting_date" class="form-control" required style="padding: 10px 12px; border-radius: 8px;" value="<?= date('Y-m-d') ?>">
                </div>
                
                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" style="font-weight: 500;">Notlar & Gelişim Özeti</label>
                    <textarea name="note" class="form-control" rows="5" style="padding: 12px; border-radius: 8px;" required placeholder="Bu haftaki görüşmede neler konuşuldu? Gelişim nasıl?"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn btn-outline" style="border-radius: 8px;" onclick="document.getElementById('modal-add-note').style.display='none'">İptal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px; background: #8b5cf6; border-color: #8b5cf6;">Zaman Tüneline Ekle</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
