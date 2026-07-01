<?php
/**
 * @var array $app
 * @var array $department
 * @var array $user
 * @var bool $canManage
 * @var array $myReports
 * @var array $allReports
 * @var array|null $activeReport
 * @var array $activeReportNotes
 */
$appUrl = BASE_URL . "/panel/{$department['slug']}/apps/{$app['slug']}";

function getStatusBadge($status) {
    switch($status) {
        case 'pending': return '<span style="background: rgba(245,158,11,0.2); color: #f59e0b; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Bekliyor</span>';
        case 'under_review': return '<span style="background: rgba(59,130,246,0.2); color: #3b82f6; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">İnceleniyor</span>';
        case 'resolved': return '<span style="background: rgba(16,185,129,0.2); color: #10b981; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Çözüldü</span>';
        case 'rejected': return '<span style="background: rgba(239,68,68,0.2); color: #ef4444; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Reddedildi / Asılsız</span>';
        default: return '';
    }
}

function getCategoryBadge($cat) {
    switch($cat) {
        case 'complaint': return '<span style="color: #ef4444; font-size: 12px; font-weight: 600;">🔴 Şikayet / İhlal</span>';
        case 'suggestion': return '<span style="color: #3b82f6; font-size: 12px; font-weight: 600;">💡 Öneri</span>';
        case 'evaluation': return '<span style="color: #8b5cf6; font-size: 12px; font-weight: 600;">📊 Değerlendirme</span>';
        default: return '';
    }
}
?>

<style>
    .audit-layout {
        display: flex;
        gap: 24px;
        min-height: 600px;
        flex-wrap: wrap;
    }
    .audit-sidebar {
        flex: 0 0 320px;
    }
    .audit-main {
        flex: 1;
        min-width: 400px;
    }
    .report-card {
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
    .report-card:hover {
        border-color: #8b5cf6;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .report-card.active {
        border-color: #8b5cf6;
        background: linear-gradient(to right, rgba(139,92,246,0.1), rgba(139,92,246,0.02));
        border-left: 4px solid #8b5cf6;
    }
    .chat-bubble {
        background: var(--bg);
        border: 1px solid var(--border);
        padding: 12px 16px;
        border-radius: 12px;
        border-top-left-radius: 0;
        margin-bottom: 16px;
        font-size: 13px;
        line-height: 1.5;
        position: relative;
    }
    .chat-bubble::before {
        content: '';
        position: absolute;
        top: -1px;
        left: -10px;
        border-width: 10px 10px 0 0;
        border-style: solid;
        border-color: var(--border) transparent transparent transparent;
    }
    .chat-bubble::after {
        content: '';
        position: absolute;
        top: 0;
        left: -8px;
        border-width: 9px 9px 0 0;
        border-style: solid;
        border-color: var(--bg) transparent transparent transparent;
    }
</style>

<!-- Header -->
<div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px;">
    <div style="display: flex; align-items: center; gap: 16px;">
        <div style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, rgba(139,92,246,0.2), rgba(139,92,246,0.1)); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2" width="28" height="28">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
        </div>
        <div>
            <h3 style="font-size: 24px; font-weight: 700; color: var(--text); margin: 0;"><?= View::escape($app['name']) ?></h3>
            <p style="font-size: 14px; color: var(--muted); margin: 4px 0 0 0;">Gizlilik esasına dayalı şikayet, öneri ve denetim modülü.</p>
        </div>
    </div>
    
    <?php if (!$canManage): ?>
        <button class="btn btn-primary" style="padding: 10px 20px; border-radius: 8px; font-weight: 500; background: #8b5cf6; border-color: #8b5cf6;" onclick="document.getElementById('modal-submit-report').style.display='flex'">
            Yeni Bildirim / Şikayet Gönder
        </button>
    <?php endif; ?>
</div>

<div class="audit-layout fade-in-up stagger-1">
    
    <?php if ($canManage): ?>
        <!-- MANAGER VIEW -->
        <div class="audit-sidebar">
            <h4 style="font-size: 13px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px;">Gelen Bildirimler</h4>
            
            <?php if (empty($allReports)): ?>
                <div style="background: var(--card); border: 1px dashed var(--border); padding: 30px; border-radius: 12px; text-align: center; color: var(--muted);">
                    Henüz bir bildirim yok.
                </div>
            <?php else: ?>
                <?php foreach ($allReports as $r): ?>
                    <a href="<?= $appUrl ?>?report=<?= $r['id'] ?>" class="report-card <?= $activeReport && $activeReport['id'] == $r['id'] ? 'active' : '' ?>">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <?= getCategoryBadge($r['category']) ?>
                            <?= getStatusBadge($r['status']) ?>
                        </div>
                        <h4 style="font-size: 14px; font-weight: 600; margin: 0 0 4px 0;"><?= View::escape($r['subject']) ?></h4>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 12px;">
                            <span style="font-size: 11px; color: var(--muted); display: flex; align-items: center; gap: 4px;">
                                <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                <?= date('d.m.Y H:i', strtotime($r['created_at'])) ?>
                            </span>
                            <?php if ($r['reporter_id']): ?>
                                <span style="font-size: 11px; font-weight: 500; color: var(--text);"><?= View::escape(mb_substr($r['reporter_name'], 0, 10)) ?>...</span>
                            <?php else: ?>
                                <span style="font-size: 11px; font-weight: 600; color: #f59e0b; background: rgba(245,158,11,0.1); padding: 2px 6px; border-radius: 4px;">Anonim Üye</span>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="audit-main">
            <?php if ($activeReport): ?>
                <div style="background: var(--card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                    <div style="padding: 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
                        <div>
                            <div style="margin-bottom: 8px;"><?= getCategoryBadge($activeReport['category']) ?></div>
                            <h2 style="font-size: 22px; font-weight: 700; color: var(--text); margin: 0 0 8px 0;"><?= View::escape($activeReport['subject']) ?></h2>
                            <div style="display: flex; gap: 16px; font-size: 13px; color: var(--muted);">
                                <span>Gönderim: <?= date('d.m.Y H:i', strtotime($activeReport['created_at'])) ?></span>
                                <span>Gönderen: <?= $activeReport['reporter_id'] ? '<strong style="color:var(--text);">'.View::escape($activeReport['reporter_name']).'</strong>' : '<strong style="color:#f59e0b;">Anonim</strong>' ?></span>
                            </div>
                        </div>

                        <div style="display: flex; gap: 12px; align-items: center;">
                            <form action="<?= $appUrl ?>" method="POST" style="margin:0; display:flex; gap:8px;">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="report_id" value="<?= $activeReport['id'] ?>">
                                <select name="status" class="form-control" style="padding: 8px 12px; border-radius: 8px; font-size: 13px; width: 140px;" onchange="this.form.submit()">
                                    <option value="pending" <?= $activeReport['status'] == 'pending' ? 'selected' : '' ?>>Bekliyor</option>
                                    <option value="under_review" <?= $activeReport['status'] == 'under_review' ? 'selected' : '' ?>>İnceleniyor</option>
                                    <option value="resolved" <?= $activeReport['status'] == 'resolved' ? 'selected' : '' ?>>Çözüldü</option>
                                    <option value="rejected" <?= $activeReport['status'] == 'rejected' ? 'selected' : '' ?>>Reddedildi/Asılsız</option>
                                </select>
                            </form>
                            
                            <form action="<?= $appUrl ?>" method="POST" style="margin:0;" onsubmit="return confirm('Bu kaydı tamamen silmek istediğinize emin misiniz?');">
                                <input type="hidden" name="action" value="delete_report">
                                <input type="hidden" name="report_id" value="<?= $activeReport['id'] ?>">
                                <button type="submit" class="btn btn-outline" style="padding: 8px; border-radius: 8px; color: var(--danger); border-color: transparent;">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div style="padding: 24px; font-size: 14px; line-height: 1.6; color: var(--text);">
                        <p style="font-weight: 600; margin-bottom: 8px; color: var(--muted);">Bildirim İçeriği:</p>
                        <div style="background: var(--bg); padding: 16px; border-radius: 12px; border: 1px solid var(--border);">
                            <?= nl2br(View::escape($activeReport['description'])) ?>
                        </div>
                    </div>

                    <!-- Internal Notes -->
                    <div style="background: rgba(139,92,246,0.03); padding: 24px; border-top: 1px solid var(--border);">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                            <svg viewBox="0 0 24 24" width="18" height="18" stroke="#8b5cf6" stroke-width="2" fill="none"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                            <h4 style="font-size: 15px; font-weight: 600; color: var(--text); margin: 0;">Kurul İçi Notlar (Gizli)</h4>
                        </div>
                        <p style="font-size: 12px; color: var(--muted); margin-bottom: 20px;">Bu notları gönderen kişi göremez, sadece yetkili kurul üyeleri görebilir.</p>

                        <div style="margin-bottom: 24px;">
                            <?php if (empty($activeReportNotes)): ?>
                                <p style="font-size: 13px; color: var(--muted); font-style: italic;">Henüz iç not eklenmemiş.</p>
                            <?php else: ?>
                                <?php foreach ($activeReportNotes as $note): ?>
                                    <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                                        <?php if ($note['author_avatar']): ?>
                                            <img src="<?= htmlspecialchars(BASE_URL . '/public/' . $note['author_avatar']) ?>" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                        <?php else: ?>
                                            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--border); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;">
                                                <?= strtoupper(mb_substr($note['author_name'], 0, 2)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div style="flex: 1;">
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                                <span style="font-size: 12px; font-weight: 600;"><?= View::escape($note['author_name']) ?></span>
                                                <span style="font-size: 11px; color: var(--muted);"><?= date('d.m.Y H:i', strtotime($note['created_at'])) ?></span>
                                            </div>
                                            <div class="chat-bubble">
                                                <?= nl2br(View::escape($note['note'])) ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <form action="<?= $appUrl ?>" method="POST" style="margin:0;">
                            <input type="hidden" name="action" value="add_note">
                            <input type="hidden" name="report_id" value="<?= $activeReport['id'] ?>">
                            <div style="display: flex; gap: 12px;">
                                <input type="text" name="note" class="form-control" placeholder="Bir iç not yazın..." required style="padding: 10px 16px; border-radius: 20px; flex: 1;">
                                <button type="submit" class="btn btn-primary" style="border-radius: 20px; padding: 10px 24px; background: #8b5cf6; border-color: #8b5cf6;">Ekle</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; border: 2px dashed var(--border); border-radius: 16px; color: var(--muted);">
                    <svg viewBox="0 0 24 24" width="48" height="48" stroke="currentColor" stroke-width="1.5" fill="none" style="margin-bottom: 16px; opacity: 0.5;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path></svg>
                    <p>Detayları görmek için sol menüden bir kayıt seçin.</p>
                </div>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <!-- MEMBER VIEW (Own Reports Only) -->
        <div style="width: 100%;">
            <div style="background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 24px; margin-bottom: 30px;">
                <h4 style="font-size: 18px; font-weight: 600; margin: 0 0 8px 0;">Geçmiş Bildirimleriniz</h4>
                <p style="font-size: 13px; color: var(--muted); margin-bottom: 24px;">Daha önce gönderdiğiniz şikayet veya önerilerin güncel durumunu buradan takip edebilirsiniz. İçerikleriniz tamamen gizlidir.</p>
                
                <?php if (empty($myReports)): ?>
                    <div style="text-align: center; padding: 30px; border: 1px dashed var(--border); border-radius: 12px; color: var(--muted);">
                        Geçmişe dönük bir bildiriminiz bulunmuyor.
                    </div>
                <?php else: ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px;">
                        <?php foreach ($myReports as $r): ?>
                            <div style="background: var(--bg); border: 1px solid var(--border); border-radius: 12px; padding: 16px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                                    <?= getCategoryBadge($r['category']) ?>
                                    <?= getStatusBadge($r['status']) ?>
                                </div>
                                <h4 style="font-size: 15px; font-weight: 600; margin: 0 0 8px 0;"><?= View::escape($r['subject']) ?></h4>
                                <div style="font-size: 12px; color: var(--muted); margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= View::escape($r['description']) ?>
                                </div>
                                <div style="font-size: 11px; color: var(--muted); border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: space-between;">
                                    <span><?= date('d.m.Y', strtotime($r['created_at'])) ?></span>
                                    <?php if (!$r['reporter_id']): ?>
                                        <span style="color: #f59e0b; font-weight: 600;">Gizli Gönderim</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal: Submit Report -->
<div id="modal-submit-report" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card pop-in" style="width: 100%; max-width: 600px; margin: 20px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div class="card-header" style="background: var(--bg); padding: 20px; border-bottom: 1px solid var(--border);">
            <span style="font-size: 18px; font-weight: 600;">Yeni Bildirim / Şikayet Gönder</span>
            <button type="button" onclick="document.getElementById('modal-submit-report').style.display='none'" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 20px;">&times;</button>
        </div>
        <div class="card-body" style="padding: 24px; max-height: 80vh; overflow-y: auto;">
            
            <div style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.3); padding: 12px 16px; border-radius: 8px; margin-bottom: 24px;">
                <p style="margin: 0; font-size: 12px; color: #d97706; line-height: 1.5;">
                    <strong>Gizlilik Uyarısı:</strong> Göndereceğiniz bu form yalnızca departmanınızın veya bu uygulamanın yetkili kurulu tarafından görülebilir. "Anonim" seçeneğini işaretlerseniz, yöneticiler dahi gönderenin siz olduğunuzu göremez.
                </p>
            </div>

            <form action="<?= $appUrl ?>" method="POST" style="margin: 0;">
                <input type="hidden" name="action" value="submit_report">
                
                <div class="form-row" style="display: flex; gap: 16px; margin-bottom: 16px;">
                    <div class="form-group" style="flex: 1; margin: 0;">
                        <label class="form-label" style="font-weight: 500;">Bildirim Türü</label>
                        <select name="category" class="form-control" style="padding: 10px 12px; border-radius: 8px;" required>
                            <option value="complaint">🔴 Şikayet / İhlal Bildirimi</option>
                            <option value="evaluation" selected>📊 Değerlendirme</option>
                            <option value="suggestion">💡 Öneri</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">Konu <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="subject" class="form-control" placeholder="Bildiriminizi özetleyen kısa bir başlık..." required style="padding: 10px 12px; border-radius: 8px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" style="font-weight: 500;">Detaylı Açıklama <span style="color:var(--danger);">*</span></label>
                    <textarea name="description" class="form-control" rows="6" style="padding: 12px; border-radius: 8px;" required placeholder="Lütfen durumu, varsa kişileri veya olayları tüm detaylarıyla açıklayın..."></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 24px; display: flex; align-items: center; gap: 12px; background: var(--bg); padding: 12px 16px; border-radius: 8px; border: 1px solid var(--border);">
                    <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1" style="width: 18px; height: 18px; cursor: pointer;">
                    <label for="is_anonymous" style="margin: 0; font-size: 14px; font-weight: 500; cursor: pointer;">Kimliğimi gizli tut (Anonim olarak gönder)</label>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn btn-outline" style="border-radius: 8px;" onclick="document.getElementById('modal-submit-report').style.display='none'">İptal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px; background: #8b5cf6; border-color: #8b5cf6;">Güvenli Gönder</button>
                </div>
            </form>
        </div>
    </div>
</div>
