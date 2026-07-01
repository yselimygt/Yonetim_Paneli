<?php
/**
 * @var array $app
 * @var array $department
 * @var array $user
 * @var bool $canManage
 * @var array $board
 */
$appUrl = BASE_URL . "/panel/{$department['slug']}/apps/{$app['slug']}";

function getPlatformColor($platform) {
    switch (strtolower($platform)) {
        case 'instagram': return 'linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%)';
        case 'linkedin': return '#0a66c2';
        case 'x': case 'twitter': return '#000000';
        case 'youtube': return '#ff0000';
        default: return '#6b7280';
    }
}
?>

<style>
    .kanban-board {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        padding-bottom: 16px;
        min-height: 450px;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }
    .kanban-column {
        flex: 1;
        min-width: 260px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
    }
    .kanban-column-header {
        padding: 16px;
        border-bottom: 1px solid var(--border);
        font-weight: 600;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .kanban-column-body {
        padding: 16px;
        flex: 1;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .soc-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .soc-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        border-color: #ec4899;
    }
    .soc-card.dragging {
        opacity: 0.5;
        border: 2px dashed #ec4899;
        transform: scale(0.98);
    }
    .kanban-column-body.drag-over {
        background: rgba(236,72,153,0.05);
        border-radius: 8px;
    }
    .soc-platform {
        display: inline-block;
        color: #fff;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    .soc-img-preview {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 6px;
        margin-bottom: 8px;
        background: var(--border);
    }
</style>

<!-- Header -->
<div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px;">
    <div style="display: flex; align-items: center; gap: 16px;">
        <div style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, rgba(236,72,153,0.2), rgba(236,72,153,0.05)); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#ec4899" stroke-width="2" width="28" height="28">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
        </div>
        <div>
            <h3 style="font-size: 24px; font-weight: 700; color: var(--text); margin: 0;"><?= View::escape($app['name']) ?></h3>
            <p style="font-size: 14px; color: var(--muted); margin: 4px 0 0 0;">Sosyal medya içerik planlayıcısı ve onay panosu.</p>
        </div>
    </div>
    
    <button class="btn btn-primary" style="padding: 10px 20px; border-radius: 8px; font-weight: 500; background: #ec4899; border-color: #ec4899;" onclick="document.getElementById('modal-create').style.display='flex'">
        + Yeni İçerik Taslağı Ekle
    </button>
</div>

<div class="kanban-board fade-in-up stagger-1">
    
    <!-- Drafts -->
    <div class="kanban-column">
        <div class="kanban-column-header" style="border-top: 4px solid #94a3b8; border-radius: 12px 12px 0 0;">
            📝 Taslaklar / Fikirler
            <span style="background:var(--bg); padding:2px 8px; border-radius:12px; font-size:12px;"><?= count($board['draft']) ?></span>
        </div>
        <div class="kanban-column-body" ondragover="allowDrop(event)" ondragleave="dragLeave(event)" ondrop="drop(event, 'draft')">
            <?php foreach ($board['draft'] as $post): ?>
                <div class="soc-card" id="post-<?= $post['id'] ?>" draggable="true" ondragstart="drag(event, <?= $post['id'] ?>)" onclick="openPostModal(<?= htmlspecialchars(json_encode($post)) ?>)">
                    <span class="soc-platform" style="background: <?= getPlatformColor($post['platform']) ?>"><?= View::escape($post['platform']) ?></span>
                    <?php if ($post['image_url']): ?>
                        <img src="<?= BASE_URL ?>/public/uploads/social/<?= $post['image_url'] ?>" class="soc-img-preview">
                    <?php endif; ?>
                    <h4 style="font-size: 14px; margin: 0 0 6px 0; color: var(--text);"><?= View::escape($post['title']) ?></h4>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                        <div style="font-size: 11px; color: var(--muted);">🗓 <?= date('d M Y H:i', strtotime($post['publish_date'])) ?></div>
                        <?php if ($post['assigned_to']): ?>
                            <?php if (!empty($post['assigned_avatar'])): ?>
                                <img src="<?= BASE_URL ?>/public/<?= $post['assigned_avatar'] ?>" title="<?= View::escape($post['assigned_name']) ?>" style="width: 24px; height: 24px; border-radius: 50%; border: 2px solid var(--bg); object-fit: cover;">
                            <?php else: ?>
                                <div title="<?= View::escape($post['assigned_name']) ?>" style="width: 24px; height: 24px; border-radius: 50%; border: 2px solid var(--bg); background: #3b82f6; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold;">
                                    <?= mb_strtoupper(mb_substr($post['assigned_name'], 0, 1, 'UTF-8'), 'UTF-8') ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Review -->
    <div class="kanban-column">
        <div class="kanban-column-header" style="border-top: 4px solid #f59e0b; border-radius: 12px 12px 0 0;">
            🔍 İnceleme (Onay Bekliyor)
            <span style="background:var(--bg); padding:2px 8px; border-radius:12px; font-size:12px;"><?= count($board['review']) ?></span>
        </div>
        <div class="kanban-column-body" ondragover="allowDrop(event)" ondragleave="dragLeave(event)" ondrop="drop(event, 'review')">
            <?php foreach ($board['review'] as $post): ?>
                <div class="soc-card" id="post-<?= $post['id'] ?>" draggable="true" ondragstart="drag(event, <?= $post['id'] ?>)" onclick="openPostModal(<?= htmlspecialchars(json_encode($post)) ?>)">
                    <span class="soc-platform" style="background: <?= getPlatformColor($post['platform']) ?>"><?= View::escape($post['platform']) ?></span>
                    <?php if ($post['image_url']): ?>
                        <img src="<?= BASE_URL ?>/public/uploads/social/<?= $post['image_url'] ?>" class="soc-img-preview">
                    <?php endif; ?>
                    <h4 style="font-size: 14px; margin: 0 0 6px 0; color: var(--text);"><?= View::escape($post['title']) ?></h4>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                        <div style="font-size: 11px; color: var(--muted);">🗓 <?= date('d M Y H:i', strtotime($post['publish_date'])) ?></div>
                        <?php if ($post['assigned_to']): ?>
                            <?php if (!empty($post['assigned_avatar'])): ?>
                                <img src="<?= BASE_URL ?>/public/<?= $post['assigned_avatar'] ?>" title="<?= View::escape($post['assigned_name']) ?>" style="width: 24px; height: 24px; border-radius: 50%; border: 2px solid var(--bg); object-fit: cover;">
                            <?php else: ?>
                                <div title="<?= View::escape($post['assigned_name']) ?>" style="width: 24px; height: 24px; border-radius: 50%; border: 2px solid var(--bg); background: #3b82f6; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold;">
                                    <?= mb_strtoupper(mb_substr($post['assigned_name'], 0, 1, 'UTF-8'), 'UTF-8') ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Approved -->
    <div class="kanban-column">
        <div class="kanban-column-header" style="border-top: 4px solid #3b82f6; border-radius: 12px 12px 0 0;">
            ✅ Onaylandı (Yayına Hazır)
            <span style="background:var(--bg); padding:2px 8px; border-radius:12px; font-size:12px;"><?= count($board['approved']) ?></span>
        </div>
        <div class="kanban-column-body" ondragover="allowDrop(event)" ondragleave="dragLeave(event)" ondrop="drop(event, 'approved')">
            <?php foreach ($board['approved'] as $post): ?>
                <div class="soc-card" id="post-<?= $post['id'] ?>" draggable="true" ondragstart="drag(event, <?= $post['id'] ?>)" onclick="openPostModal(<?= htmlspecialchars(json_encode($post)) ?>)">
                    <span class="soc-platform" style="background: <?= getPlatformColor($post['platform']) ?>"><?= View::escape($post['platform']) ?></span>
                    <?php if ($post['image_url']): ?>
                        <img src="<?= BASE_URL ?>/public/uploads/social/<?= $post['image_url'] ?>" class="soc-img-preview">
                    <?php endif; ?>
                    <h4 style="font-size: 14px; margin: 0 0 6px 0; color: var(--text);"><?= View::escape($post['title']) ?></h4>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                        <div style="font-size: 11px; color: var(--muted);">🗓 <?= date('d M Y H:i', strtotime($post['publish_date'])) ?></div>
                        <?php if ($post['assigned_to']): ?>
                            <?php if (!empty($post['assigned_avatar'])): ?>
                                <img src="<?= BASE_URL ?>/public/<?= $post['assigned_avatar'] ?>" title="<?= View::escape($post['assigned_name']) ?>" style="width: 24px; height: 24px; border-radius: 50%; border: 2px solid var(--bg); object-fit: cover;">
                            <?php else: ?>
                                <div title="<?= View::escape($post['assigned_name']) ?>" style="width: 24px; height: 24px; border-radius: 50%; border: 2px solid var(--bg); background: #3b82f6; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold;">
                                    <?= mb_strtoupper(mb_substr($post['assigned_name'], 0, 1, 'UTF-8'), 'UTF-8') ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Published -->
    <div class="kanban-column">
        <div class="kanban-column-header" style="border-top: 4px solid #10b981; border-radius: 12px 12px 0 0;">
            🚀 Yayınlandı (Arşiv)
            <span style="background:var(--bg); padding:2px 8px; border-radius:12px; font-size:12px;"><?= count($board['published']) ?></span>
        </div>
        <div class="kanban-column-body" style="opacity: 0.7;" ondragover="allowDrop(event)" ondragleave="dragLeave(event)" ondrop="drop(event, 'published')">
            <?php foreach ($board['published'] as $post): ?>
                <div class="soc-card" id="post-<?= $post['id'] ?>" draggable="true" ondragstart="drag(event, <?= $post['id'] ?>)" onclick="openPostModal(<?= htmlspecialchars(json_encode($post)) ?>)">
                    <span class="soc-platform" style="background: <?= getPlatformColor($post['platform']) ?>"><?= View::escape($post['platform']) ?></span>
                    <?php if ($post['image_url']): ?>
                        <img src="<?= BASE_URL ?>/public/uploads/social/<?= $post['image_url'] ?>" class="soc-img-preview">
                    <?php endif; ?>
                    <h4 style="font-size: 14px; margin: 0 0 6px 0; color: var(--text);"><?= View::escape($post['title']) ?></h4>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                        <div style="font-size: 11px; color: var(--muted);">🗓 <?= date('d M Y H:i', strtotime($post['publish_date'])) ?></div>
                        <?php if ($post['assigned_to']): ?>
                            <?php if (!empty($post['assigned_avatar'])): ?>
                                <img src="<?= BASE_URL ?>/public/<?= $post['assigned_avatar'] ?>" title="<?= View::escape($post['assigned_name']) ?>" style="width: 24px; height: 24px; border-radius: 50%; border: 2px solid var(--bg); object-fit: cover;">
                            <?php else: ?>
                                <div title="<?= View::escape($post['assigned_name']) ?>" style="width: 24px; height: 24px; border-radius: 50%; border: 2px solid var(--bg); background: #3b82f6; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold;">
                                    <?= mb_strtoupper(mb_substr($post['assigned_name'], 0, 1, 'UTF-8'), 'UTF-8') ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal: Yeni İçerik Ekle -->
<div id="modal-create" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card pop-in" style="width: 100%; max-width: 500px; margin: 20px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div class="card-header" style="background: var(--bg); padding: 20px; border-bottom: 1px solid var(--border);">
            <span style="font-size: 18px; font-weight: 600;">Yeni İçerik Fikri / Taslağı</span>
            <button type="button" onclick="document.getElementById('modal-create').style.display='none'" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 20px;">&times;</button>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="<?= $appUrl ?>" method="POST" style="margin: 0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="create_post">
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">İçerik Başlığı / Konusu</label>
                    <input type="text" name="title" class="form-control" required style="padding: 10px 12px; border-radius: 8px;" placeholder="Örn: 23 Nisan Kutlama Postu">
                </div>
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">Hedef Platform</label>
                    <select name="platform" class="form-control" required style="padding: 10px 12px; border-radius: 8px;">
                        <option value="Instagram">Instagram</option>
                        <option value="LinkedIn">LinkedIn</option>
                        <option value="X">X (Twitter)</option>
                        <option value="YouTube">YouTube</option>
                        <option value="Blog">Blog</option>
                    </select>
                </div>
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">Hedef Yayın Tarihi & Saati</label>
                    <input type="datetime-local" name="publish_date" class="form-control" required style="padding: 10px 12px; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" style="font-weight: 500;">Görevlendirilecek Kişi (İsteğe Bağlı)</label>
                    <select name="assigned_to" class="form-control" style="padding: 10px 12px; border-radius: 8px;">
                        <option value="">-- Atama Yok --</option>
                        <?php foreach ($members as $mem): ?>
                            <option value="<?= $mem['id'] ?>"><?= View::escape($mem['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn btn-outline" style="border-radius: 8px;" onclick="document.getElementById('modal-create').style.display='none'">İptal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px; background: #ec4899; border-color: #ec4899;">Taslağı Oluştur</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Post Details & Edit -->
<div id="modal-edit" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card pop-in" style="width: 100%; max-width: 600px; margin: 20px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-height: 90vh; display: flex; flex-direction: column;">
        
        <div class="card-header" style="background: var(--bg); padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between;">
            <div>
                <span id="edit-platform" class="soc-platform" style="background: #333;">Platform</span>
                <span id="edit-title" style="font-size: 18px; font-weight: 600; display: block; margin-top: 6px;">Başlık</span>
            </div>
            <button type="button" onclick="document.getElementById('modal-edit').style.display='none'" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 20px;">&times;</button>
        </div>
        
        <div class="card-body" style="padding: 24px; overflow-y: auto;">
            <!-- Current Image -->
            <div id="edit-image-container" style="display: none; margin-bottom: 20px;">
                <img id="edit-image" src="" style="width: 100%; max-height: 250px; object-fit: contain; border-radius: 8px; background: #000;">
            </div>

            <form id="edit-form" action="<?= $appUrl ?>" method="POST" enctype="multipart/form-data" style="margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid var(--border);">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="update_post">
                <input type="hidden" name="post_id" id="edit-post-id" value="">
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">Açıklama Metni (Copywriting)</label>
                    <textarea name="content_text" id="edit-content" class="form-control" rows="5" style="padding: 12px; border-radius: 8px;" placeholder="Post metni, hashtagler vb..."></textarea>
                </div>
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">Tasarım Görseli Yükle</label>
                    <input type="file" name="image" class="form-control" accept="image/*" style="padding: 10px 12px; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">Görevlendirilen Kişi</label>
                    <select name="assigned_to" id="edit-assigned-to" class="form-control" style="padding: 10px 12px; border-radius: 8px;">
                        <option value="">-- Atama Yok --</option>
                        <?php foreach ($members as $mem): ?>
                            <option value="<?= $mem['id'] ?>"><?= View::escape($mem['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-outline" style="width: 100%; border-color: #ec4899; color: #ec4899;">İçeriği & Görseli Kaydet</button>
            </form>

            <!-- Status Actions -->
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label style="font-weight: 600; font-size: 13px; color: var(--muted); text-transform: uppercase;">Durum Değiştir / Aksiyonlar</label>
                
                <div style="display: flex; gap: 10px; flex-wrap: wrap;" id="status-actions">
                    <!-- Buttons will be injected via JS based on current status and permissions -->
                </div>
                
                <?php if ($canManage): ?>
                    <div style="margin-top: 10px;">
                        <form action="<?= $appUrl ?>" method="POST" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="action" value="delete_post">
                            <input type="hidden" name="post_id" id="delete-post-id" value="">
                            <button type="submit" class="btn btn-outline" style="border-color: var(--danger); color: var(--danger); width: 100%;">Tüm İçeriği Sil</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <div style="border-top: 1px solid var(--border); padding-top: 20px; margin-top: 20px; flex-grow: 1; display: flex; flex-direction: column;">
                <h4 style="font-size: 15px; font-weight: 600; margin: 0 0 16px 0;">Yorumlar ve Dosyalar</h4>
                <div id="post-comments-list" style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px;">
                    <!-- Yorumlar AJAX ile buraya yüklenecek -->
                </div>
            </div>
            
        </div>

        <div style="padding: 20px 24px; background: rgba(0,0,0,0.02); border-top: 1px solid var(--border); flex-shrink: 0;">
            <form id="comment-form" onsubmit="submitPostComment(event)" style="margin:0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" id="comment-post-id" name="post_id" value="">
                <input type="hidden" name="action" value="add_post_comment_ajax">
                <div style="background: var(--bg); border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; display: flex; flex-direction: column; gap: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); transition: border-color 0.2s;" onfocusin="this.style.borderColor='#ec4899'" onfocusout="this.style.borderColor='var(--border)'">
                    <textarea name="comment" id="comment-textarea" placeholder="Bir yorum yazın..." style="border: none; background: transparent; resize: none; font-size: 14px; color: var(--text); outline: none; min-height: 44px; padding: 0; font-family: inherit; line-height: 1.5;"></textarea>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed var(--border); padding-top: 12px;">
                        <div style="display:flex; align-items: center; gap: 12px;">
                            <label style="cursor:pointer; display:flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:10px; background: rgba(0,0,0,0.04); color:var(--muted); transition: all 0.2s;" onmouseover="this.style.background='rgba(0,0,0,0.08)'; this.style.color='var(--text)';" onmouseout="this.style.background='rgba(0,0,0,0.04)'; this.style.color='var(--muted)';">
                                <input type="file" name="attachment" id="comment-file" style="display:none;" onchange="document.getElementById('file-name-display').innerText = this.files[0] ? this.files[0].name : ''">
                                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
                            </label>
                            <span id="file-name-display" style="font-size:12px; color:#ec4899; font-weight:500; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></span>
                        </div>
                        <button type="submit" class="btn btn-primary" style="padding: 8px 18px; border-radius: 10px; font-size: 14px; font-weight: 500; display:flex; align-items:center; gap:8px; background: #ec4899; border-color: #ec4899; box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);">
                            <span>Gönder</span>
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const baseUrl = '<?= BASE_URL ?>';
    const appUrl = '<?= $appUrl ?>';
    const canManage = <?= $canManage ? 'true' : 'false' ?>;

    // Drag & Drop
    function allowDrop(ev) {
        ev.preventDefault();
        const target = ev.target.closest('.kanban-column-body');
        if (target && !target.classList.contains('drag-over')) {
            target.classList.add('drag-over');
        }
    }
    
    function dragLeave(ev) {
        const target = ev.target.closest('.kanban-column-body');
        if (target) {
            target.classList.remove('drag-over');
        }
    }
    
    function drag(ev, postId) {
        ev.dataTransfer.setData("postId", postId);
        setTimeout(() => {
            document.getElementById('post-' + postId).classList.add('dragging');
        }, 0);
    }
    
    document.addEventListener('dragend', function(e) {
        if(e.target.classList && e.target.classList.contains('soc-card')) {
            e.target.classList.remove('dragging');
        }
        document.querySelectorAll('.kanban-column-body').forEach(col => {
            col.classList.remove('drag-over');
        });
    });

    function updateColumnCounts() {
        document.querySelectorAll('.kanban-column').forEach(col => {
            const count = col.querySelectorAll('.soc-card').length;
            const badge = col.querySelector('.kanban-column-header span');
            if(badge) badge.innerText = count;
        });
    }
    
    function drop(ev, newStatus) {
        ev.preventDefault();
        const target = ev.target.closest('.kanban-column-body');
        if (target) target.classList.remove('drag-over');

        var postId = ev.dataTransfer.getData("postId");
        var card = document.getElementById('post-' + postId);
        
        // Yetki kontrolü (sadece yöneticiler onay/yayın alanına taşıyabilir)
        if ((newStatus === 'approved' || newStatus === 'published') && !canManage) {
            alert("Bu alana (Onaylandı / Yayınlandı) taşımak için Yönetici olmalısınız.");
            return;
        }

        // Anında DOM güncellemesi ile görsel geri bildirim
        if (card && target) {
            target.appendChild(card);
            updateColumnCounts();
        }

        // Post the status change to backend silently
        let formData = new FormData();
        formData.append('action', 'change_status');
        formData.append('post_id', postId);
        formData.append('new_status', newStatus);
        formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?? '' ?>');

        fetch(appUrl, {
            method: 'POST',
            body: formData
        }).catch(err => {
            console.error("Hata:", err);
            alert("Taşıma işlemi sırasında hata oluştu. Sayfayı yenileyiniz.");
        });
    }

    function loadPostComments(postId) {
        const list = document.getElementById('post-comments-list');
        list.innerHTML = '<div style="text-align:center; padding: 20px; color:var(--muted);"><svg class="spin" viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"><circle cx="12" cy="12" r="10"></circle><path d="M12 2v4"></path></svg></div>';
        
        const formData = new FormData();
        formData.append('action', 'get_post_comments_ajax');
        formData.append('post_id', postId);
        formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?? '' ?>');

        fetch(appUrl, {
            method: 'POST',
            body: formData,
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        }).then(res => res.json()).then(data => {
            if(data.success) {
                list.innerHTML = '';
                if(data.comments.length === 0) {
                    list.innerHTML = '<p style="font-size:12px; color:var(--muted); text-align:center; margin:0;">Henüz yorum veya dosya eklenmemiş.</p>';
                    return;
                }
                
                data.comments.forEach(c => {
                    let avatarHtml = `<div style="width:36px; height:36px; border-radius:50%; background:var(--border); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:bold; color:var(--muted); flex-shrink: 0;">${c.user_name.substring(0,2).toUpperCase()}</div>`;
                    if(c.user_avatar) {
                        avatarHtml = `<img src="${c.user_avatar}" style="width:36px; height:36px; border-radius:50%; object-fit:cover; flex-shrink: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">`;
                    }
                    
                    let fileHtml = '';
                    if(c.file_path) {
                        fileHtml = `<div style="margin-top:${c.comment ? '10px' : '0'};"><a href="${c.file_path}" target="_blank" style="display:inline-flex; align-items:center; gap:8px; font-size:12px; font-weight:500; background:rgba(236, 72, 153, 0.08); padding:8px 14px; border-radius:8px; border:1px solid rgba(236, 72, 153, 0.2); color:#ec4899; text-decoration:none; transition: all 0.2s;" onmouseover="this.style.background='rgba(236, 72, 153, 0.15)';" onmouseout="this.style.background='rgba(236, 72, 153, 0.08)';"><svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> ${c.file_name}</a></div>`;
                    }

                    let commentText = c.comment ? `<p style="margin:0; font-size:14px; line-height:1.6; color:var(--text); white-space:pre-wrap;">${c.comment}</p>` : '';

                    list.innerHTML += `
                        <div style="display:flex; gap:12px; margin-bottom: 8px;">
                            ${avatarHtml}
                            <div style="display: flex; flex-direction: column; align-items: flex-start; max-width: 85%;">
                                <div style="display:flex; align-items: baseline; gap:8px; margin-bottom: 4px; padding-left: 2px;">
                                    <span style="font-size:13px; font-weight:600; color:var(--text);">${c.user_name}</span>
                                    <span style="font-size:11px; color:var(--muted);">${c.created_at_formatted}</span>
                                </div>
                                <div style="background: var(--bg); padding: 12px 16px; border-radius: 4px 16px 16px 16px; border: 1px solid var(--border); box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                                    ${commentText}
                                    ${fileHtml}
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
        });
    }

    function submitPostComment(e) {
        e.preventDefault();
        const form = e.target;
        const postId = document.getElementById('comment-post-id').value;
        const comment = document.getElementById('comment-textarea').value.trim();
        const file = document.getElementById('comment-file').files[0];
        
        if(!comment && !file) return;
        
        const formData = new FormData(form);
        const btn = form.querySelector('button[type="submit"]');
        const oldBtnText = btn.innerHTML;
        btn.innerHTML = 'Gönderiliyor...';
        btn.disabled = true;

        fetch(appUrl, {
            method: 'POST',
            body: formData,
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        }).then(res => res.json()).then(data => {
            btn.innerHTML = oldBtnText;
            btn.disabled = false;
            if(data.success) {
                document.getElementById('comment-textarea').value = '';
                document.getElementById('comment-file').value = '';
                document.getElementById('file-name-display').innerText = '';
                loadPostComments(postId);
            } else {
                alert(data.message || 'Bir hata oluştu.');
            }
        }).catch(err => {
            btn.innerHTML = oldBtnText;
            btn.disabled = false;
            alert('Bağlantı hatası.');
        });
    }

    function openPostModal(post) {
        document.getElementById('edit-post-id').value = post.id;
        if (document.getElementById('delete-post-id')) {
            document.getElementById('delete-post-id').value = post.id;
        }
        document.getElementById('comment-post-id').value = post.id;
        document.getElementById('edit-title').innerText = post.title;
        document.getElementById('edit-platform').innerText = post.platform;
        document.getElementById('edit-content').value = post.content_text || '';
        document.getElementById('edit-assigned-to').value = post.assigned_to || '';
        
        const imgContainer = document.getElementById('edit-image-container');
        const imgEl = document.getElementById('edit-image');
        if (post.image_url) {
            imgEl.src = baseUrl + '/public/uploads/social/' + post.image_url;
            imgContainer.style.display = 'block';
        } else {
            imgContainer.style.display = 'none';
        }

        // Render Status Buttons
        const actionsDiv = document.getElementById('status-actions');
        let html = '';
        
        const csrfInput = `<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">`;
        if (post.status === 'draft') {
            html += `<form action="${appUrl}" method="POST" style="margin:0;">${csrfInput}<input type="hidden" name="action" value="change_status"><input type="hidden" name="post_id" value="${post.id}"><input type="hidden" name="new_status" value="review"><button type="submit" class="btn btn-primary" style="background:#f59e0b; border-color:#f59e0b;">İncelemeye (Onaya) Gönder 🔍</button></form>`;
        }
        else if (post.status === 'review') {
            html += `<form action="${appUrl}" method="POST" style="margin:0;">${csrfInput}<input type="hidden" name="action" value="change_status"><input type="hidden" name="post_id" value="${post.id}"><input type="hidden" name="new_status" value="draft"><button type="submit" class="btn btn-outline" style="border-color:#94a3b8; color:#94a3b8;">Taslağa Geri Al</button></form>`;
            
            if (canManage) {
                html += `<form action="${appUrl}" method="POST" style="margin:0;">${csrfInput}<input type="hidden" name="action" value="change_status"><input type="hidden" name="post_id" value="${post.id}"><input type="hidden" name="new_status" value="approved"><button type="submit" class="btn btn-primary" style="background:#3b82f6; border-color:#3b82f6;">Onayla (Yayına Hazır) ✅</button></form>`;
            }
        }
        else if (post.status === 'approved') {
            if (canManage) {
                html += `<form action="${appUrl}" method="POST" style="margin:0;">${csrfInput}<input type="hidden" name="action" value="change_status"><input type="hidden" name="post_id" value="${post.id}"><input type="hidden" name="new_status" value="review"><button type="submit" class="btn btn-outline" style="border-color:#f59e0b; color:#f59e0b;">Onayı İptal Et</button></form>`;
                html += `<form action="${appUrl}" method="POST" style="margin:0;">${csrfInput}<input type="hidden" name="action" value="change_status"><input type="hidden" name="post_id" value="${post.id}"><input type="hidden" name="new_status" value="published"><button type="submit" class="btn btn-primary" style="background:#10b981; border-color:#10b981;">Yayınlandı Olarak İşaretle 🚀</button></form>`;
            }
        }
        else if (post.status === 'published') {
            if (canManage) {
                html += `<form action="${appUrl}" method="POST" style="margin:0;">${csrfInput}<input type="hidden" name="action" value="change_status"><input type="hidden" name="post_id" value="${post.id}"><input type="hidden" name="new_status" value="approved"><button type="submit" class="btn btn-outline" style="border-color:#3b82f6; color:#3b82f6;">Arşivden Çıkar</button></form>`;
            }
        }

        actionsDiv.innerHTML = html;

        loadPostComments(post.id);

        document.getElementById('modal-edit').style.display = 'flex';
    }
</script>
