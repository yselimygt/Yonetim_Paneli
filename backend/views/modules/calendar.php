<div style="display: grid; grid-template-columns: 1fr; gap: 24px; @media (min-width: 768px) { grid-template-columns: 2fr 1fr; }">

    <!-- SOL KOLON: Etkinlikler Listesi -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        
        <!-- Gelecek Etkinlikler -->
        <div class="card fade-in-up stagger-1">
            <div class="card-header">📅 Yaklaşan Etkinlikler (<?= count($upcomingEvents) ?>)</div>
            <div class="card-body" style="padding: 16px; display: flex; flex-direction: column; gap: 12px;">
                <?php if (empty($upcomingEvents)): ?>
                    <div class="empty-state" style="padding: 30px 0;">
                        <p>Planlanmış herhangi bir yaklaşan etkinlik yok.</p>
                    </div>
                <?php else: ?>
                    <?php 
                    $calStagger = 1;
                    foreach ($upcomingEvents as $ev): 
                    ?>
                        <div class="fade-in-up stagger-<?= min($calStagger++, 5) ?>" style="display: flex; gap: 16px; background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border); border-radius: 8px; padding: 14px; transition: transform 0.2s, background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.04)'; this.style.transform='translateX(4px)';" onmouseout="this.style.background='rgba(255,255,255,0.02)'; this.style.transform='translateX(0)';">
                            <!-- Tarih Rozeti -->
                            <div style="width: 60px; height: 60px; background: var(--accent); color: #0a0a0a; border-radius: 6px; display: flex; flex-direction: column; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span style="font-size: 18px; font-weight: 700; line-height: 1;"><?= date('d', strtotime($ev['event_date'])) ?></span>
                                <span style="font-size: 11px; text-transform: uppercase; font-weight: 600;"><?= strftime('%b', strtotime($ev['event_date'])) ?></span>
                            </div>

                            <!-- Detaylar -->
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
                                    <h4 style="font-size: 14px; font-weight: 600; color: var(--text);"><?= View::escape($ev['title']) ?></h4>
                                    
                                    <!-- Silme yetkisi -->
                                    <?php if ($isDirectorOrAdmin || (int)$ev['created_by'] === $user['id']): ?>
                                        <form method="POST" style="margin:0;" onsubmit="return confirm('Bu etkinliği silmek istediğinize emin misiniz?');">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="event_id" value="<?= $ev['id'] ?>">
                                            <button type="submit" style="background:transparent; border:none; color:var(--danger); cursor:pointer; font-size: 11px;">Sil</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                                <div style="font-size: 11px; color: var(--muted); margin-top: 2px;">
                                    ⏰ <?= $ev['event_time'] ? date('H:i', strtotime($ev['event_time'])) : 'Tüm Gün' ?> | ✍️ <?= View::escape($ev['creator_name']) ?>
                                </div>
                                <?php if (!empty($ev['description'])): ?>
                                    <p style="font-size: 12px; color: #aaa; margin-top: 8px; line-height: 1.5; white-space: pre-wrap;"><?= View::escape($ev['description']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Geçmiş Etkinlikler -->
        <div class="card fade-in-up stagger-2">
            <div class="card-header">⌛ Geçmiş Etkinlikler (<?= count($pastEvents) ?>)</div>
            <div class="card-body" style="padding: 16px; display: flex; flex-direction: column; gap: 10px;">
                <?php if (empty($pastEvents)): ?>
                    <div style="text-align: center; color: var(--muted); padding: 20px; font-size:12px;">Geçmiş etkinlik yok.</div>
                <?php else: ?>
                    <?php foreach ($pastEvents as $ev): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(0, 0, 0, 0.1); border: 1px solid var(--border); border-radius: 6px; padding: 10px 14px; opacity: 0.7;">
                            <div>
                                <span style="font-size:11px; color: var(--muted); font-family: monospace; margin-right: 10px;"><?= date('d.m.Y', strtotime($ev['event_date'])) ?></span>
                                <span style="font-size:13px; font-weight:500; color: #ccc;"><?= View::escape($ev['title']) ?></span>
                            </div>
                            
                            <?php if ($isDirectorOrAdmin || (int)$ev['created_by'] === $user['id']): ?>
                                <form method="POST" style="margin:0;" onsubmit="return confirm('Bu geçmiş etkinliği silmek istediğinize emin misiniz?');">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="event_id" value="<?= $ev['id'] ?>">
                                    <button type="submit" style="background:transparent; border:none; color:var(--danger); cursor:pointer; font-size:11px;">Sil</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- SAĞ KOLON: Etkinlik Ekleme Formu (Yetki varsa) -->
    <?php if ($canAdd): ?>
        <div class="card fade-in-up stagger-3" style="height: fit-content;">
            <div class="card-header">📅 Yeni Etkinlik Ekle</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="<?= BASE_URL ?>/panel/<?= View::escape($department['slug']) ?>/apps/<?= View::escape($app['slug']) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <input type="hidden" name="action" value="add">

                    <div class="form-group">
                        <label class="form-label">Etkinlik Adı *</label>
                        <input type="text" name="title" class="form-control" placeholder="Örn: Haftalık Toplantı" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tarih *</label>
                        <input type="date" name="event_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Saat (Opsiyonel)</label>
                        <input type="time" name="event_time" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Açıklama / Detaylar</label>
                        <textarea name="description" class="form-control" style="min-height: 80px;" placeholder="Gündem maddeleri, yer vb."></textarea>
                    </div>

                    <div style="margin-top: 16px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Ekle</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

</div>
