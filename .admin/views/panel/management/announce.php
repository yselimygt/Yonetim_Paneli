<?php $pageTitle = 'Küresel Duyuru'; $activeSection = 'announce'; ?>

<div class="page-header">
    <h2>📢 Küresel Duyuru</h2>
</div>

<div style="display:grid;grid-template-columns:1fr 1.2fr;gap:20px;align-items:start;">

    <!-- Yeni duyuru formu -->
    <div class="card">
        <div class="card-header">Yeni Küresel Duyuru</div>
        <div class="card-body">
            <form method="POST" action="<?= BASE_URL ?>/panel/<?= View::escape($currentDept['slug']) ?>/management/announce">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="create">

                <div class="form-group">
                    <label class="form-label">Başlık *</label>
                    <input type="text" name="title" class="form-control" placeholder="Duyuru başlığı" required>
                </div>

                <div class="form-group">
                    <label class="form-label">İçerik *</label>
                    <textarea name="content" class="form-control" rows="5" placeholder="Duyuru metni..." required></textarea>
                </div>

                <div class="form-group" style="display:flex;align-items:center;gap:8px">
                    <input type="checkbox" id="is_pinned" name="is_pinned" value="1" style="accent-color:var(--accent);width:15px;height:15px;">
                    <label for="is_pinned" style="font-size:13px;color:var(--text);cursor:pointer;">Sabitle</label>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">
                    Tüm Departmanlara Gönder
                </button>
            </form>
        </div>
    </div>

    <!-- Geçmiş duyurular -->
    <div class="card">
        <div class="card-header">
            Küresel Duyurular
            <span style="font-size:11px;color:var(--muted)"><?= count($announcements) ?> duyuru</span>
        </div>
        <?php if (empty($announcements)): ?>
            <div class="empty-state"><p>Henüz küresel duyuru yok.</p></div>
        <?php else: ?>
        <div style="max-height:500px;overflow-y:auto;">
            <?php foreach ($announcements as $ann): ?>
            <div style="padding:16px 20px;border-bottom:1px solid rgba(255,255,255,.04);">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:6px;">
                    <div style="font-size:13px;font-weight:500;"><?= View::escape($ann['title']) ?></div>
                    <?php if ($ann['is_pinned']): ?>
                        <span style="font-size:10px;background:rgba(245,158,11,.1);color:#fbbf24;border-radius:3px;padding:1px 6px;white-space:nowrap;">📌 Sabit</span>
                    <?php endif; ?>
                </div>
                <div style="font-size:12px;color:var(--muted);line-height:1.6"><?= nl2br(View::escape($ann['content'])) ?></div>
                <div style="font-size:11px;color:#333;margin-top:8px;">
                    <?= date('d.m.Y H:i', strtotime($ann['created_at'])) ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

</div>
