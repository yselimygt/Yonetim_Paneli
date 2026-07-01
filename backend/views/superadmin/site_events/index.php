<?php $pageTitle = 'Site Etkinlikleri'; ?>

<div class="page-header">
    <h2>Web Sitesi Etkinlikleri</h2>
    <button onclick="openModal('create-event-modal')" class="btn btn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Yeni Etkinlik
    </button>
</div>

<div class="card">
    <div style="overflow-x: auto;">
        <table style="min-width: 800px;">
            <thead>
                <tr>
                    <th>Görsel</th>
                    <th>Başlık</th>
                    <th>Kategori / Konum</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                    <th style="width: 140px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($events)): ?>
                <tr><td colspan="6" class="empty-state"><p>Henüz site etkinliği bulunmuyor.</p></td></tr>
            <?php else: ?>
                <?php foreach ($events as $e): ?>
                <tr>
                    <td>
                        <?php if ($e['image_url']): ?>
                            <?php 
                                $imgSrc = $e['image_url'];
                                if (!preg_match('/^https?:\/\//', $imgSrc)) {
                                    $imgSrc = BASE_URL . '/public/' . ltrim($imgSrc, '/');
                                }
                            ?>
                            <img src="<?= View::escape($imgSrc) ?>" alt="Event" style="width:60px;height:40px;border-radius:6px;object-fit:cover;">
                        <?php else: ?>
                            <div style="width:60px;height:40px;background:var(--border);border-radius:6px;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:10px;">Görsel Yok</div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong style="display:block;font-size:14px;color:var(--text);margin-bottom:4px;">
                            <?= View::escape($e['title']) ?>
                            <?php if(!empty($e['is_bootcamp'])): ?>
                                <span class="badge" style="background:rgba(59,130,246,0.15); color:#60a5fa; margin-left:8px; font-size:10px; border:1px solid rgba(59,130,246,0.3);">Bootcamp</span>
                            <?php elseif(!empty($e['is_app_event'])): ?>
                                <span class="badge" style="background:rgba(16,185,129,0.15); color:#34d399; margin-left:8px; font-size:10px; border:1px solid rgba(16,185,129,0.3);">Modül</span>
                            <?php endif; ?>
                        </strong>
                        <div style="font-size:12px;color:var(--muted);max-width:250px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            <?= View::escape($e['description'] ?? '') ?>
                        </div>
                    </td>
                    <td>
                        <div><span class="badge badge-member"><?= View::escape($e['category'] ?? 'Genel') ?></span></div>
                        <div style="font-size:12px;color:var(--muted);margin-top:4px;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:2px;margin-top:-2px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            <?= View::escape($e['location'] ?? 'Belirtilmedi') ?>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:13px;font-weight:500;">
                            <?= $e['event_date'] ? date('d M Y, H:i', strtotime($e['event_date'])) : 'Belirtilmedi' ?>
                        </div>
                    </td>
                    <td>
                        <?php if ($e['status'] === 'upcoming'): ?>
                            <span class="badge badge-active">Yaklaşan</span>
                        <?php elseif ($e['status'] === 'past'): ?>
                            <span class="badge badge-inactive">Geçmiş</span>
                        <?php else: ?>
                            <span class="badge badge-inactive"><?= View::escape($e['status']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="actions">
                            <?php if(empty($e['is_bootcamp']) && empty($e['is_app_event'])): ?>
                                <button type="button" class="btn btn-outline btn-sm" onclick='editEvent(<?= json_encode($e, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>Düzenle</button>
                                <form method="POST" action="<?= BASE_URL ?>/superadmin/site-events/<?= $e['id'] ?>/delete"
                                      onsubmit="return confirm('<?= View::escape($e['title']) ?> etkinliği silinsin mi?')">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div id="create-event-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Yeni Site Etkinliği</h3>
            <button type="button" class="modal-close" onclick="closeModal('create-event-modal')">&times;</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/superadmin/site-events">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Başlık *</label>
                    <input type="text" name="title" class="form-control" placeholder="MIS CONNECT'26" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tarih</label>
                    <input type="datetime-local" name="event_date" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Kısa Açıklama</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Etkinliğin kısa açıklaması..."></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" class="form-control" placeholder="Zirve, Eğitim, Panel vs.">
                </div>
                <div class="form-group">
                    <label class="form-label">Konum</label>
                    <input type="text" name="location" class="form-control" placeholder="Etkinlik yeri">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kategori Renk Sınıfı (Tailwind)</label>
                    <input type="text" name="tag_color" class="form-control" placeholder="Örn: bg-primary/20 text-primary...">
                </div>
                <div class="form-group">
                    <label class="form-label">Durum</label>
                    <select name="status" class="form-control">
                        <option value="upcoming">Yaklaşan</option>
                        <option value="past">Geçmiş</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Görsel URL</label>
                <input type="url" name="image_url" class="form-control" placeholder="https://...">
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:16px;">
                <button type="button" onclick="closeModal('create-event-modal')" class="btn btn-outline">İptal</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-event-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Etkinliği Düzenle</h3>
            <button type="button" class="modal-close" onclick="closeModal('edit-event-modal')">&times;</button>
        </div>
        <form method="POST" id="edit-event-form" action="">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Başlık *</label>
                    <input type="text" name="title" id="edit_title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tarih</label>
                    <input type="datetime-local" name="event_date" id="edit_event_date" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Kısa Açıklama</label>
                <textarea name="description" id="edit_description" class="form-control" rows="2"></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" id="edit_category" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Konum</label>
                    <input type="text" name="location" id="edit_location" class="form-control">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kategori Renk Sınıfı</label>
                    <input type="text" name="tag_color" id="edit_tag_color" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Durum</label>
                    <select name="status" id="edit_status" class="form-control">
                        <option value="upcoming">Yaklaşan</option>
                        <option value="past">Geçmiş</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Görsel URL</label>
                <input type="url" name="image_url" id="edit_image_url" class="form-control">
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:16px;">
                <button type="button" onclick="closeModal('edit-event-modal')" class="btn btn-outline">İptal</button>
                <button type="submit" class="btn btn-primary">Güncelle</button>
            </div>
        </form>
    </div>
</div>

<script>

function editEvent(e) {
    const form = document.getElementById('edit-event-form');
    form.action = '<?= BASE_URL ?>/superadmin/site-events/' + e.id;
    
    document.getElementById('edit_title').value = e.title || '';
    if (e.event_date) {
        document.getElementById('edit_event_date').value = e.event_date.replace(' ', 'T').slice(0, 16);
    } else {
        document.getElementById('edit_event_date').value = '';
    }
    document.getElementById('edit_description').value = e.description || '';
    document.getElementById('edit_category').value = e.category || '';
    document.getElementById('edit_location').value = e.location || '';
    document.getElementById('edit_tag_color').value = e.tag_color || '';
    document.getElementById('edit_status').value = e.status || 'upcoming';
    document.getElementById('edit_image_url').value = e.image_url || '';

    openModal('edit-event-modal');
}
</script>
