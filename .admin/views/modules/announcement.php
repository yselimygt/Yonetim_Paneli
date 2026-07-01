<div style="display: grid; grid-template-columns: 1fr; gap: 24px;">

    <!-- Duyuru Oluşturma (Yetki varsa) -->
    <?php if ($canPost): ?>
        <div class="card fade-in-up stagger-1">
            <div class="card-header">Yeni Duyuru Yayınla</div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/panel/<?= View::escape($department['slug']) ?>/apps/<?= View::escape($app['slug']) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <input type="hidden" name="action" value="add">

                    <div class="form-group">
                        <label class="form-label">Başlık *</label>
                        <input type="text" name="title" class="form-control" placeholder="Duyuru başlığını giriniz" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Duyuru İçeriği *</label>
                        <textarea name="content" class="form-control" style="min-height: 120px;" placeholder="Duyuru detaylarını yazınız..." required></textarea>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px;">
                        <?php if ($isDirectorOrAdmin): ?>
                            <div class="check-group" style="display:flex; align-items:center; gap:8px;">
                                <input type="checkbox" id="is_pinned" name="is_pinned" value="1">
                                <label for="is_pinned" style="font-size:12px; color:var(--muted); cursor:pointer;">Bu duyuruyu en üste sabitle</label>
                            </div>
                        <?php else: ?>
                            <div></div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primary">Yayınla</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Duyurular Listesi -->
    <div style="display: flex; flex-direction: column; gap: 16px;">
        <h3 style="font-size:14px; text-transform:uppercase; color:var(--muted); font-weight:600; letter-spacing:0.5px;">Duyurular</h3>
        
        <?php if (empty($announcements)): ?>
            <div class="card fade-in-up stagger-2">
                <div class="empty-state">
                    <p>Henüz yayınlanmış bir duyuru bulunmuyor.</p>
                </div>
            </div>
        <?php else: ?>
            <?php 
            $annStagger = 2;
            foreach ($announcements as $announce): 
            ?>
                <?php
                $isGlobal = !empty($announce['is_global']);
                $isPinned = !empty($announce['is_pinned']);
                
                // Kart stilini belirleyelim
                $cardStyle = '';
                if ($isGlobal) {
                    $cardStyle = 'border-left: 4px solid var(--warning); background: linear-gradient(135deg, rgba(245,158,11,.03) 0%, rgba(0,0,0,0) 100%);';
                } elseif ($isPinned) {
                    $cardStyle = 'border-left: 4px solid var(--accent); background: linear-gradient(135deg, rgba(0,188,212,.03) 0%, rgba(0,0,0,0) 100%);';
                }
                ?>
                <div class="card fade-in-up stagger-<?= min($annStagger++, 5) ?>" style="<?= $cardStyle ?>">
                    <div class="card-body">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px; gap:12px;">
                            <div>
                                <h4 style="font-size: 15px; font-weight:600; color:var(--text); display:flex; align-items:center; gap:8px;">
                                    <?= View::escape($announce['title']) ?>
                                    <?php if ($isGlobal): ?>
                                        <span class="badge" style="background:rgba(245,158,11,.15); color:var(--warning); font-size:9px;">KÜRESEL DUYURU</span>
                                    <?php elseif ($isPinned): ?>
                                        <span class="badge" style="background:rgba(0,188,212,.15); color:var(--accent); font-size:9px;">📌 SABİTLENMİŞ</span>
                                    <?php endif; ?>
                                </h4>
                                <div style="font-size:11px; color:var(--muted); margin-top:4px;">
                                    ✍️ <strong><?= View::escape($announce['author_name']) ?></strong> tarafından <?= date('d.m.Y H:i', strtotime($announce['created_at'])) ?> tarihinde yayınlandı
                                </div>
                            </div>

                            <!-- Yönetici Butonları -->
                            <?php if ($isDirectorOrAdmin && !$isGlobal): ?>
                                <div style="display:flex; gap:8px;">
                                    <form method="POST" style="margin:0;">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <input type="hidden" name="action" value="toggle_pin">
                                        <input type="hidden" name="announcement_id" value="<?= $announce['id'] ?>">
                                        <button type="submit" class="btn btn-outline btn-sm" style="padding: 3px 8px; font-size:10px;">
                                            <?= $isPinned ? 'Sabitlemeyi Kaldır' : 'Sabitle' ?>
                                        </button>
                                    </form>

                                    <form method="POST" style="margin:0;" onsubmit="return confirm('Bu duyuruyu silmek istediğinize emin misiniz?');">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="announcement_id" value="<?= $announce['id'] ?>">
                                        <button type="submit" class="btn btn-outline btn-sm" style="padding: 3px 8px; font-size:10px; color:var(--danger); border-color:rgba(239,68,68,0.2);">
                                            Sil
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div style="font-size:13px; color:var(--text); line-height:1.6; white-space:pre-wrap; background:rgba(255,255,255,0.01); padding:12px; border-radius:6px; border:1px solid rgba(255,255,255,0.02);"><?= View::escape($announce['content']) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>
