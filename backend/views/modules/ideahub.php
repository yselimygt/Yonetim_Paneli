<?php
/**
 * @var array $app
 * @var array $department
 * @var array $user
 * @var string $userRole
 * @var bool $isDirectorOrAdmin
 * @var array $ideas
 * @var array $categories
 * @var array $statuses
 */

$appUrl = BASE_URL . "/panel/{$department['slug']}/apps/{$app['slug']}";

// Simple helper for time ago
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;
    if ($diff < 60) return 'Az önce';
    if ($diff < 3600) return floor($diff / 60) . ' dk önce';
    if ($diff < 86400) return floor($diff / 3600) . ' saat önce';
    return floor($diff / 86400) . ' gün önce';
}
?>

<style>
@media (max-width: 760px) {
    div[style*="margin-bottom: 24px; display: flex"] {
        align-items: flex-start !important;
        gap: 10px !important;
        margin-bottom: 14px !important;
    }

    div[style*="margin-bottom: 24px; display: flex"] h3 {
        font-size: 18px !important;
        line-height: 1.2 !important;
        margin: 0 !important;
    }

    div[style*="margin-bottom: 24px; display: flex"] p {
        display: none !important;
    }

    div[style*="margin-bottom: 24px; display: flex"] > .btn {
        width: 100% !important;
        justify-content: center !important;
        min-height: 40px !important;
        border-radius: 11px !important;
        font-size: 13px !important;
    }

    div[style*="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr))"] {
        grid-template-columns: 1fr !important;
        gap: 12px !important;
    }

    div[style*="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr))"] > .card {
        border-radius: 14px !important;
        overflow: hidden !important;
    }

    div[style*="flex-grow: 1; padding: 20px"] {
        padding: 14px !important;
    }

    div[style*="justify-content: space-between; align-items: flex-start; margin-bottom: 16px"] {
        margin-bottom: 10px !important;
        gap: 8px !important;
    }

    div[style*="width: 36px; height: 36px"] {
        width: 32px !important;
        height: 32px !important;
    }

    div[style*="margin-bottom: 16px;"] {
        margin-bottom: 12px !important;
    }

    div[style*="margin-bottom: 16px;"] h3 {
        font-size: 14px !important;
        line-height: 1.3 !important;
        margin-bottom: 7px !important;
    }

    div[style*="margin-bottom: 16px;"] p {
        display: -webkit-box !important;
        -webkit-line-clamp: 3 !important;
        -webkit-box-orient: vertical !important;
        overflow: hidden !important;
        font-size: 12px !important;
        line-height: 1.45 !important;
    }

    div[style*="padding: 12px 20px; background: var(--card-alt)"] {
        padding: 10px 12px !important;
        gap: 8px !important;
    }

    div[style*="padding: 12px 20px; background: var(--card-alt)"] .btn-sm {
        min-width: 34px !important;
        min-height: 32px !important;
        padding: 6px 9px !important;
        border-radius: 9px !important;
        font-size: 11px !important;
    }

    div[id^="comments-"] {
        padding: 12px !important;
    }

    div[id^="comments-"] form {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) auto !important;
        gap: 7px !important;
    }

    div[id^="comments-"] .form-control {
        min-width: 0 !important;
        height: 36px !important;
    }

    #modal-create-idea,
    #modal-update-status {
        align-items: center !important;
        justify-content: center !important;
        padding: 12px !important;
        box-sizing: border-box !important;
    }

    #modal-create-idea > .card,
    #modal-update-status > .card {
        width: 100% !important;
        max-width: none !important;
        max-height: 88vh !important;
        margin: 0 auto !important;
        border-radius: 18px !important;
        overflow-y: auto !important;
    }

    #modal-create-idea .card-body,
    #modal-update-status .card-body {
        padding: 14px !important;
    }

    #modal-create-idea .form-group,
    #modal-update-status .form-group {
        margin-bottom: 13px !important;
    }

    #modal-create-idea .form-control,
    #modal-update-status .form-control {
        min-height: 40px !important;
        border-radius: 10px !important;
        font-size: 13px !important;
    }

    #modal-create-idea textarea.form-control {
        min-height: 92px !important;
    }

    #modal-create-idea form > div[style*="justify-content: flex-end"],
    #modal-update-status form > div[style*="justify-content: flex-end"] {
        display: flex !important;
        gap: 8px !important;
        margin-top: 16px !important;
    }

    #modal-create-idea form > div[style*="justify-content: flex-end"] .btn,
    #modal-update-status form > div[style*="justify-content: flex-end"] .btn {
        flex: 1 !important;
        justify-content: center !important;
    }
}
</style>

<!-- Header -->
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <div>
        <h3 style="font-size: 18px; font-weight: 600; color: var(--text); display: flex; align-items: center; gap: 8px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="<?= View::escape($department['color'] ?? '#00BCD4') ?>" stroke-width="2" width="24" height="24">
                <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.9 1.3 1.5 1.5 2.5"></path>
                <path d="M9 18h6"></path><path d="M10 22h4"></path>
            </svg>
            <?= View::escape($app['name']) ?>
        </h3>
        <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">
            <?= View::escape($app['description'] ?? 'Yaratıcı fikirlerinizi paylaşın, oylayın ve tartışın.') ?>
        </p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('modal-create-idea').style.display='flex'">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" style="margin-right: 6px;">
            <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Yeni Fikir Paylaş
    </button>
</div>

<!-- Ideas List -->
<div class="fade-in-up stagger-1" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
    <?php if (empty($ideas)): ?>
        <div class="empty-state" style="grid-column: 1 / -1; background: var(--card); border: 1px dashed var(--border); border-radius: 10px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="48" height="48" style="opacity: 0.3; margin: 0 auto 16px;">
                <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.9 1.3 1.5 1.5 2.5"></path>
                <path d="M9 18h6"></path><path d="M10 22h4"></path>
                <line x1="2" y1="2" x2="22" y2="22"></line>
            </svg>
            <h3 style="font-size: 16px; font-weight: 500; color: var(--text); margin-bottom: 8px;">Henüz Fikir Yok</h3>
            <p>İlk fikri siz paylaşın ve ilham kaynağı olun!</p>
        </div>
    <?php else: ?>
        <?php $cardStagger = 1; foreach ($ideas as $idea): ?>
            <div class="card fade-in-up stagger-<?= min($cardStagger++, 5) ?>" style="display: flex; flex-direction: column;">
                
                <div class="card-body" style="flex-grow: 1; padding: 20px;">
                    <!-- Idea Header -->
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--border); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                <?php if (!empty($idea['creator_avatar'])): ?>
                                    <img src="<?= htmlspecialchars(BASE_URL . '/public/' . $idea['creator_avatar']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <span style="color: var(--text); font-weight: bold; font-size: 12px;">
                                        <?= strtoupper(mb_substr($idea['creator_name'], 0, 2)) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 style="font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 2px;"><?= View::escape($idea['creator_name']) ?></h4>
                                <span style="font-size: 11px; color: var(--muted); display: flex; align-items: center; gap: 4px;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="10" height="10"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    <?= timeAgo($idea['created_at']) ?>
                                </span>
                            </div>
                        </div>
                        <?php $statusInfo = $statuses[$idea['status']] ?? ['label' => 'Bilinmiyor', 'color' => '#9ca3af']; ?>
                        <span class="badge" style="background: <?= $statusInfo['color'] ?>20; color: <?= $statusInfo['color'] ?>; padding: 4px 8px; font-size: 10px;">
                            <?= View::escape($statusInfo['label']) ?>
                        </span>
                    </div>

                    <!-- Idea Content -->
                    <div style="margin-bottom: 16px;">
                        <h3 style="font-size: 16px; font-weight: 600; color: var(--text); margin-bottom: 8px; line-height: 1.4;"><?= View::escape($idea['title']) ?></h3>
                        <span class="badge" style="background: var(--card-alt); border: 1px solid var(--border); color: var(--muted); font-size: 10px; margin-bottom: 12px; display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="10" height="10"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                            <?= View::escape($idea['category']) ?>
                        </span>
                        <p style="font-size: 13px; color: var(--text); opacity: 0.8; line-height: 1.6; white-space: pre-wrap;"><?= View::escape($idea['description']) ?></p>
                    </div>
                </div>

                <!-- Idea Footer Actions -->
                <div style="padding: 12px 20px; background: var(--card-alt); border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; gap: 8px;">
                        <!-- Upvote -->
                        <form action="<?= $appUrl ?>" method="POST" style="margin: 0;">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="action" value="upvote">
                            <input type="hidden" name="idea_id" value="<?= $idea['id'] ?>">
                            <button type="submit" class="btn btn-sm <?= $idea['user_voted'] > 0 ? 'btn-primary' : 'btn-outline' ?>" style="gap: 4px; padding: 6px 12px;">
                                <svg viewBox="0 0 24 24" fill="<?= $idea['user_voted'] > 0 ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2" width="14" height="14">
                                    <path d="M12 19V5M5 12l7-7 7 7"/>
                                </svg>
                                <?= $idea['vote_count'] ?>
                            </button>
                        </form>
                        
                        <!-- Comment Toggle -->
                        <button onclick="document.getElementById('comments-<?= $idea['id'] ?>').style.display = document.getElementById('comments-<?= $idea['id'] ?>').style.display === 'none' ? 'block' : 'none';" class="btn btn-sm btn-outline" style="gap: 4px; padding: 6px 12px;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <?= count($idea['comments']) ?>
                        </button>
                    </div>

                    <!-- Admin Actions -->
                    <?php if ($isDirectorOrAdmin || $idea['user_id'] == $user['id']): ?>
                        <div style="display: flex; gap: 8px;">
                            <?php if ($isDirectorOrAdmin): ?>
                                <button onclick="openStatusModal(<?= $idea['id'] ?>, '<?= View::escape(addslashes($idea['status'])) ?>')" class="btn btn-sm btn-outline" title="Durumu Güncelle" style="padding: 6px; border-color: transparent;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
                                </button>
                            <?php endif; ?>
                            
                            <form action="<?= $appUrl ?>" method="POST" style="margin: 0;" onsubmit="return confirm('Bu fikri silmek istediğinize emin misiniz?');">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="idea_id" value="<?= $idea['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline" title="Sil" style="padding: 6px; border-color: transparent; color: var(--danger);">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Comments Section (Hidden by default) -->
                <div id="comments-<?= $idea['id'] ?>" style="display: none; border-top: 1px solid var(--border); padding: 20px; background: var(--bg);">
                    <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 16px;">
                        <?php if (empty($idea['comments'])): ?>
                            <p style="font-size: 12px; color: var(--muted); font-style: italic; text-align: center;">Henüz yorum yapılmamış.</p>
                        <?php else: ?>
                            <?php foreach ($idea['comments'] as $comment): ?>
                                <div style="display: flex; gap: 12px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--border); display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden;">
                                        <?php if (!empty($comment['user_avatar'])): ?>
                                            <img src="<?= htmlspecialchars(BASE_URL . '/public/' . $comment['user_avatar']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <span style="font-size: 10px; font-weight: bold; color: var(--text);"><?= strtoupper(mb_substr($comment['user_name'], 0, 2)) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div style="background: var(--card); border: 1px solid var(--border); padding: 12px; border-radius: 8px; flex-grow: 1;">
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                            <strong style="font-size: 12px; color: var(--text);"><?= View::escape($comment['user_name']) ?></strong>
                                            <span style="font-size: 10px; color: var(--muted);"><?= timeAgo($comment['created_at']) ?></span>
                                        </div>
                                        <p style="font-size: 12px; color: var(--text); opacity: 0.9; white-space: pre-wrap; line-height: 1.5; margin: 0;"><?= View::escape($comment['comment']) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Add Comment Form -->
                    <form action="<?= $appUrl ?>" method="POST" style="display: flex; gap: 8px; margin: 0;">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="hidden" name="action" value="add_comment">
                        <input type="hidden" name="idea_id" value="<?= $idea['id'] ?>">
                        <input type="text" name="comment" class="form-control" placeholder="Yorum yazın..." required style="flex-grow: 1; padding: 8px 12px; font-size: 12px;">
                        <button type="submit" class="btn btn-primary btn-sm" style="padding: 8px 16px;">Gönder</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal: Yeni Fikir Ekle -->
<div id="modal-create-idea" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: var(--modal-overlay); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card pop-in" style="width: 100%; max-width: 500px; margin: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div class="card-header">
            <span>Yeni Fikir Paylaş</span>
            <button type="button" onclick="document.getElementById('modal-create-idea').style.display='none'" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 16px;">&times;</button>
        </div>
        <div class="card-body">
            <form action="<?= $appUrl ?>" method="POST" style="margin: 0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="create">
                
                <div class="form-group">
                    <label class="form-label">Fikir Başlığı *</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select name="category" class="form-control" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= View::escape($cat) ?>"><?= View::escape($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Detaylı Açıklama *</label>
                    <textarea name="description" class="form-control" style="min-height: 120px;" placeholder="Fikriniz nedir? Ne gibi faydalar sağlar?" required></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px;">
                    <button type="button" class="btn btn-outline" onclick="document.getElementById('modal-create-idea').style.display='none'">İptal</button>
                    <button type="submit" class="btn btn-primary">Fikri Gönder</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('modal-create-idea').addEventListener('click', function(e) {
    if (e.target === this) {
        this.style.display = 'none';
    }
});
</script>

<!-- Modal: Durum Güncelleme -->
<?php if ($isDirectorOrAdmin): ?>
<div id="modal-update-status" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: var(--modal-overlay); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card pop-in" style="width: 100%; max-width: 400px; margin: 20px;">
        <div class="card-header">
            <span>Durumu Güncelle</span>
            <button type="button" onclick="document.getElementById('modal-update-status').style.display='none'" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 16px;">&times;</button>
        </div>
        <div class="card-body">
            <form action="<?= $appUrl ?>" method="POST" style="margin: 0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="idea_id" id="status_idea_id" value="">
                
                <div class="form-group">
                    <label class="form-label">Yeni Durum</label>
                    <select name="status" id="status_select" class="form-control">
                        <?php foreach ($statuses as $key => $status): ?>
                            <option value="<?= View::escape($key) ?>"><?= View::escape($status['label']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px;">
                    <button type="button" class="btn btn-outline" onclick="document.getElementById('modal-update-status').style.display='none'">İptal</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openStatusModal(ideaId, currentStatus) {
    document.getElementById('status_idea_id').value = ideaId;
    document.getElementById('status_select').value = currentStatus;
    document.getElementById('modal-update-status').style.display = 'flex';
}

document.getElementById('modal-update-status').addEventListener('click', function(e) {
    if (e.target === this) {
        this.style.display = 'none';
    }
});
</script>
<?php endif; ?>
