<?php $pageTitle = 'Küresel Duyuru'; $activeSection = 'announce'; ?>

<style>
@media (max-width: 900px) {
    .mgmt-announce-header {
        margin-bottom: 14px;
    }

    .mgmt-announce-header h2 {
        font-size: 18px;
        line-height: 1.2;
        margin: 0;
    }

    .mgmt-announce-grid {
        grid-template-columns: 1fr !important;
        gap: 12px !important;
    }

    .mgmt-announce-grid .card {
        border-radius: 12px;
    }

    .mgmt-announce-grid .card-header {
        min-height: 42px;
        padding: 12px 14px;
        font-size: 13px;
    }

    .mgmt-announce-grid .card-body {
        padding: 14px;
    }

    .mgmt-announce-grid .form-group {
        margin-bottom: 12px;
    }

    .mgmt-announce-grid .form-control {
        min-height: 40px;
        font-size: 13px;
        border-radius: 10px;
    }

    .mgmt-announce-grid textarea.form-control {
        min-height: 112px;
    }

    .mgmt-announce-submit {
        min-height: 40px;
        border-radius: 10px;
        font-size: 12px;
    }

    .mgmt-announcements-list {
        max-height: none !important;
        overflow: visible !important;
        padding: 10px;
    }

    .mgmt-announcement-item {
        padding: 12px !important;
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 10px;
        background: var(--card-alt);
    }

    .mgmt-announcement-item:last-child {
        margin-bottom: 0;
    }

    .mgmt-announcement-head {
        align-items: center !important;
        margin-bottom: 7px !important;
    }

    .mgmt-announcement-title {
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 13px !important;
    }

    .mgmt-announcement-content {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-size: 12px !important;
        line-height: 1.45 !important;
    }

    .mgmt-announcement-date {
        color: var(--muted) !important;
    }
}
</style>

<div class="page-header mgmt-announce-header">
    <h2>📢 Küresel Duyuru</h2>
</div>

<div class="mgmt-announce-grid" style="display:grid;grid-template-columns:1fr 1.2fr;gap:20px;align-items:start;">

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

                <button type="submit" class="btn btn-primary mgmt-announce-submit" style="width:100%;margin-top:8px;">
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
        <div class="mgmt-announcements-list" style="max-height:500px;overflow-y:auto;">
            <?php foreach ($announcements as $ann): ?>
            <div class="mgmt-announcement-item" style="padding:16px 20px;border-bottom:1px solid rgba(255,255,255,.04);">
                <div class="mgmt-announcement-head" style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:6px;">
                    <div class="mgmt-announcement-title" style="font-size:13px;font-weight:500;"><?= View::escape($ann['title']) ?></div>
                    <?php if ($ann['is_pinned']): ?>
                        <span style="font-size:10px;background:rgba(245,158,11,.1);color:#fbbf24;border-radius:3px;padding:1px 6px;white-space:nowrap;">📌 Sabit</span>
                    <?php endif; ?>
                </div>
                <div class="mgmt-announcement-content" style="font-size:12px;color:var(--muted);line-height:1.6"><?= nl2br(View::escape($ann['content'])) ?></div>
                <div class="mgmt-announcement-date" style="font-size:11px;color:#333;margin-top:8px;">
                    <?= date('d.m.Y H:i', strtotime($ann['created_at'])) ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

</div>
