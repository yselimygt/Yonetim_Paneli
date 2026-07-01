<?php
/**
 * @var array $app
 * @var array $department
 * @var array $user
 * @var bool $canManage
 * @var bool $isSuperAdmin
 * @var array $requests
 */
$appUrl = BASE_URL . "/panel/{$department['slug']}/apps/{$app['slug']}";

function getBudgetStatusBadge($status) {
    switch($status) {
        case 'pending': return '<span style="background: rgba(245,158,11,0.1); color: #f59e0b; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">⏳ Bekliyor</span>';
        case 'director_approved': return '<span style="background: rgba(59,130,246,0.1); color: #3b82f6; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">✓ Yön. Onaylı (Admin Bekleniyor)</span>';
        case 'admin_approved': return '<span style="background: rgba(16,185,129,0.1); color: #10b981; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">✅ Tamamlandı / Ödendi</span>';
        case 'rejected': return '<span style="background: rgba(239,68,68,0.1); color: #ef4444; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">❌ Reddedildi</span>';
        default: return '';
    }
}
?>

<style>
    .req-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        transition: all 0.2s;
    }
    .req-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    }
</style>

<!-- Header -->
<div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px;">
    <div style="display: flex; align-items: center; gap: 16px;">
        <div style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, rgba(16,185,129,0.2), rgba(16,185,129,0.05)); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" width="28" height="28">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
        </div>
        <div>
            <h3 style="font-size: 24px; font-weight: 700; color: var(--text); margin: 0;"><?= View::escape($app['name']) ?></h3>
            <p style="font-size: 14px; color: var(--muted); margin: 4px 0 0 0;">Satın alma, harcama ve bütçe talepleri.</p>
        </div>
    </div>
    
    <button class="btn btn-primary" style="padding: 10px 20px; border-radius: 8px; font-weight: 500; background: #10b981; border-color: #10b981;" onclick="document.getElementById('modal-create-req').style.display='flex'">
        + Yeni Bütçe Talebi
    </button>
</div>

<div class="fade-in-up stagger-1">
    <?php if (empty($requests)): ?>
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px 20px; text-align: center; background: var(--card); border: 2px dashed var(--border); border-radius: 16px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="48" height="48" style="color: var(--muted); opacity: 0.5; margin-bottom: 16px;">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
            <h4 style="font-size: 18px; font-weight: 600; color: var(--text); margin: 0 0 8px 0;">Henüz Bir Talep Yok</h4>
            <p style="font-size: 14px; color: var(--muted); margin: 0; max-width: 400px;">Bütçe ve satın alma talepleri burada listelenecektir.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 24px;">
            <?php foreach ($requests as $req): ?>
                <div class="req-card">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                        <div>
                            <span style="font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase;">TALEP: #<?= $req['id'] ?></span>
                            <h4 style="font-size: 18px; font-weight: 700; color: var(--text); margin: 4px 0 0 0;"><?= View::escape($req['title']) ?></h4>
                            <div style="font-size: 13px; color: var(--muted); margin-top: 6px; display: flex; align-items: center; gap: 6px;">
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <?= View::escape($req['requester_name']) ?>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 24px; font-weight: 800; color: #10b981;">
                                <?= number_format($req['amount'], 2) ?> <?= View::escape($req['currency']) ?>
                            </div>
                        </div>
                    </div>
                    
                    <div style="background: var(--bg); padding: 12px; border-radius: 8px; border: 1px solid var(--border); font-size: 13px; color: var(--text); margin-bottom: 20px; line-height: 1.5;">
                        <strong style="color: var(--muted); display: block; margin-bottom: 4px;">Gerekçe / Açıklama:</strong>
                        <?= nl2br(View::escape($req['reason'])) ?>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div><?= getBudgetStatusBadge($req['status']) ?></div>
                        
                        <?php if ($req['status'] !== 'rejected' && $req['status'] !== 'admin_approved'): ?>
                            <div style="display: flex; gap: 8px;">
                                <!-- Director Approval -->
                                <?php if ($req['status'] === 'pending' && $canManage): ?>
                                    <form action="<?= $appUrl ?>" method="POST" style="margin:0;">
                                        <input type="hidden" name="action" value="approve_request">
                                        <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                                        <input type="hidden" name="new_status" value="director_approved">
                                        <button type="submit" class="btn btn-outline" style="padding: 6px 12px; border-radius: 6px; border-color: #3b82f6; color: #3b82f6;">Yönetici Onayı Ver</button>
                                    </form>
                                <?php endif; ?>

                                <!-- Admin Approval -->
                                <?php if ($req['status'] === 'director_approved' && $isSuperAdmin): ?>
                                    <form action="<?= $appUrl ?>" method="POST" style="margin:0;">
                                        <input type="hidden" name="action" value="approve_request">
                                        <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                                        <input type="hidden" name="new_status" value="admin_approved">
                                        <button type="submit" class="btn btn-outline" style="padding: 6px 12px; border-radius: 6px; border-color: #10b981; color: #10b981;">Son Onayı Ver (Tamamla)</button>
                                    </form>
                                <?php endif; ?>

                                <!-- Reject (Admin or Director) -->
                                <?php if ($canManage || $isSuperAdmin): ?>
                                    <form action="<?= $appUrl ?>" method="POST" style="margin:0;" onsubmit="return confirm('Bu talebi reddetmek istediğinize emin misiniz?');">
                                        <input type="hidden" name="action" value="reject_request">
                                        <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                                        <button type="submit" class="btn btn-outline" style="padding: 6px 12px; border-radius: 6px; border-color: #ef4444; color: #ef4444;">Reddet</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal: Yeni Talep -->
<div id="modal-create-req" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card pop-in" style="width: 100%; max-width: 600px; margin: 20px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div class="card-header" style="background: var(--bg); padding: 20px; border-bottom: 1px solid var(--border);">
            <span style="font-size: 18px; font-weight: 600;">Yeni Bütçe / Satın Alma Talebi</span>
            <button type="button" onclick="document.getElementById('modal-create-req').style.display='none'" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 20px;">&times;</button>
        </div>
        <div class="card-body" style="padding: 24px; max-height: 80vh; overflow-y: auto;">
            <form action="<?= $appUrl ?>" method="POST" style="margin: 0;">
                <input type="hidden" name="action" value="create_request">
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">Talep/İhtiyaç Adı <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Örn: Etkinlik için 50 adet Pizza" required style="padding: 10px 12px; border-radius: 8px;">
                </div>
                
                <div class="form-row" style="display: flex; gap: 16px; margin-bottom: 16px;">
                    <div class="form-group" style="flex: 2; margin: 0;">
                        <label class="form-label" style="font-weight: 500;">Tutar <span style="color:var(--danger);">*</span></label>
                        <input type="number" step="0.01" name="amount" class="form-control" required style="padding: 10px 12px; border-radius: 8px;" placeholder="0.00">
                    </div>
                    <div class="form-group" style="flex: 1; margin: 0;">
                        <label class="form-label" style="font-weight: 500;">Para Birimi</label>
                        <select name="currency" class="form-control" style="padding: 10px 12px; border-radius: 8px;">
                            <option value="TRY">TRY (₺)</option>
                            <option value="USD">USD ($)</option>
                            <option value="EUR">EUR (€)</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" style="font-weight: 500;">Gerekçe ve Detaylar <span style="color:var(--danger);">*</span></label>
                    <textarea name="reason" class="form-control" rows="4" style="padding: 12px; border-radius: 8px;" required placeholder="Bu bütçe nerede kullanılacak? Varsa link veya detay verin..."></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn btn-outline" style="border-radius: 8px;" onclick="document.getElementById('modal-create-req').style.display='none'">İptal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px; background: #10b981; border-color: #10b981;">Talebi Gönder</button>
                </div>
            </form>
        </div>
    </div>
</div>
