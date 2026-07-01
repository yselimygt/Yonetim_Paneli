<?php
/**
 * @var array $app
 * @var array $department
 * @var array $user
 * @var bool $canEdit
 * @var array $trophies
 */
$appUrl = BASE_URL . "/panel/{$department['slug']}/apps/{$app['slug']}";

function getTrophyBadge($type) {
    switch($type) {
        case 'hackathon': return '<span style="background: rgba(236,72,153,0.2); color: #ec4899; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase;">💻 Hackathon</span>';
        case 'competition': return '<span style="background: rgba(245,158,11,0.2); color: #f59e0b; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase;">🏆 Yarışma</span>';
        case 'project': return '<span style="background: rgba(59,130,246,0.2); color: #3b82f6; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase;">🚀 Proje</span>';
        case 'organization': return '<span style="background: rgba(16,185,129,0.2); color: #10b981; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase;">🤝 Organizasyon</span>';
        case 'social': return '<span style="background: rgba(139,92,246,0.2); color: #8b5cf6; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase;">🌱 Sosyal Sorumluluk</span>';
        default: return '<span style="background: rgba(107,114,128,0.2); color: #6b7280; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase;">✨ Başarı</span>';
    }
}
?>

<style>
    .trophy-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
        padding-bottom: 40px;
    }
    
    .trophy-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        height: 400px;
        cursor: default;
    }
    
    .trophy-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(245, 158, 11, 0.15);
        border-color: rgba(245, 158, 11, 0.4);
    }
    
    .trophy-image-wrapper {
        height: 220px;
        width: 100%;
        position: relative;
        overflow: hidden;
        background: #000;
    }
    
    .trophy-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.85;
        transition: transform 0.5s ease, opacity 0.5s ease;
    }
    
    .trophy-card:hover .trophy-image {
        transform: scale(1.08);
        opacity: 1;
    }
    
    .trophy-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 50%;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        z-index: 1;
    }
    
    .trophy-badge {
        position: absolute;
        top: 16px;
        right: 16px;
        z-index: 2;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    .trophy-date {
        position: absolute;
        bottom: 12px;
        left: 16px;
        color: #fff;
        z-index: 2;
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }
    
    .trophy-content {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        background: var(--card);
        position: relative;
        z-index: 2;
    }
    
    .trophy-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 8px 0;
        line-height: 1.3;
    }
    
    .trophy-desc {
        font-size: 13px;
        color: var(--muted);
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin: 0;
    }
    
    .trophy-action-btn {
        background: rgba(255, 255, 255, 0.9);
        color: #333;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transform: scale(0.8);
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .trophy-action-btn.delete {
        background: rgba(239, 68, 68, 0.9);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }
    
    .trophy-card:hover .trophy-action-btn {
        opacity: 1;
        transform: scale(1);
    }
    
    .trophy-action-btn:hover {
        transform: scale(1.1) !important;
    }
    
    .trophy-action-btn.delete:hover {
        background: #dc2626;
    }
    .trophy-action-btn.edit:hover {
        background: #e5e7eb;
    }
</style>

<!-- Header -->
<div style="margin-bottom: 40px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px;">
    <div style="display: flex; align-items: center; gap: 20px;">
        <div style="width: 64px; height: 64px; border-radius: 20px; background: linear-gradient(135deg, rgba(245,158,11,0.2), rgba(245,158,11,0.05)); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 16px rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" width="32" height="32" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                <path d="M4 22h16"></path>
                <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
            </svg>
        </div>
        <div>
            <h3 style="font-size: 28px; font-weight: 800; color: var(--text); margin: 0; background: linear-gradient(to right, #f59e0b, #d97706); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Şeref Kürsüsü</h3>
            <p style="font-size: 15px; color: var(--muted); margin: 6px 0 0 0;">Takımımızın gurur tablosu; başarılar, kupalar ve ödüller galerisi.</p>
        </div>
    </div>
    
    <?php if ($canEdit): ?>
        <button class="btn btn-primary" style="padding: 12px 24px; border-radius: 12px; font-weight: 600; background: linear-gradient(135deg, #f59e0b, #d97706); border: none; box-shadow: 0 4px 15px rgba(245,158,11,0.3);" onclick="document.getElementById('modal-add-trophy').style.display='flex'">
            <span style="font-size: 16px; margin-right: 6px;">+</span> Kürsüye Yeni Ödül Ekle
        </button>
    <?php endif; ?>
</div>

<!-- Gallery -->
<?php if (empty($trophies)): ?>
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 80px 20px; text-align: center; background: var(--card); border: 2px dashed var(--border); border-radius: 24px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" width="64" height="64" style="color: var(--muted); opacity: 0.5; margin-bottom: 20px;">
            <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
        </svg>
        <h4 style="font-size: 18px; font-weight: 600; color: var(--text); margin: 0 0 8px 0;">Henüz Bir Ödül Eklenmemiş</h4>
        <p style="font-size: 14px; color: var(--muted); margin: 0; max-width: 400px;">Kazanılan başarılar ve ödüller eklendiğinde burada muazzam bir vitrin oluşacak.</p>
    </div>
<?php else: ?>
    <div class="trophy-grid fade-in-up stagger-1">
        <?php foreach ($trophies as $trophy): ?>
            <div class="trophy-card">
                <?php if ($canEdit): ?>
                    <div style="position: absolute; top: 16px; left: 16px; z-index: 10; display: flex; gap: 8px;">
                        <button type="button" class="trophy-action-btn edit" title="Ödülü Düzenle" onclick="openEditTrophyModal(<?= htmlspecialchars(json_encode($trophy)) ?>)">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </button>
                        <form action="<?= $appUrl ?>" method="POST" onsubmit="return confirm('Bu ödülü silmek istediğinize emin misiniz? (Görsel de silinecektir)');" style="margin:0;">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="action" value="delete_trophy">
                            <input type="hidden" name="trophy_id" value="<?= $trophy['id'] ?>">
                            <button type="submit" class="trophy-action-btn delete" title="Ödülü Sil">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
                
                <div class="trophy-badge">
                    <?= getTrophyBadge($trophy['award_type']) ?>
                </div>
                
                <div class="trophy-image-wrapper">
                    <img src="<?= htmlspecialchars(BASE_URL . '/public/uploads/trophies/' . $trophy['image_path']) ?>" alt="Trophy Image" class="trophy-image">
                    <div class="trophy-overlay"></div>
                    <div class="trophy-date">
                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <?= date('F Y', strtotime($trophy['award_date'])) ?>
                    </div>
                </div>
                
                <div class="trophy-content">
                    <h4 class="trophy-title"><?= View::escape($trophy['title']) ?></h4>
                    <p class="trophy-desc" title="<?= View::escape($trophy['description']) ?>">
                        <?= View::escape($trophy['description']) ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Modal: Add Trophy -->
<?php if ($canEdit): ?>
<div id="modal-add-trophy" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(8px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card pop-in" style="width: 100%; max-width: 600px; margin: 20px; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); border: 1px solid var(--border);">
        <div class="card-header" style="background: var(--bg); padding: 24px; border-bottom: 1px solid var(--border); border-top-left-radius: 20px; border-top-right-radius: 20px;">
            <span style="font-size: 20px; font-weight: 700; color: var(--text);">Yeni Ödül / Başarı Ekle</span>
            <button type="button" onclick="document.getElementById('modal-add-trophy').style.display='none'" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 24px; line-height: 1;">&times;</button>
        </div>
        <div class="card-body" style="padding: 24px; max-height: 80vh; overflow-y: auto;">
            <form action="<?= $appUrl ?>" method="POST" enctype="multipart/form-data" style="margin: 0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="add_trophy">
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="font-weight: 600;">Başarı / Ödül Başlığı <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Örn: NASA Space Apps 2023 Birinciliği" required style="padding: 12px 16px; border-radius: 12px; font-size: 14px;">
                </div>
                
                <div style="display: flex; gap: 16px; margin-bottom: 20px;">
                    <div class="form-group" style="flex: 1; margin: 0;">
                        <label class="form-label" style="font-weight: 600;">Kategori</label>
                        <select name="award_type" class="form-control" style="padding: 12px 16px; border-radius: 12px; font-size: 14px;">
                            <option value="hackathon">Hackathon</option>
                            <option value="competition">Yarışma</option>
                            <option value="project">Proje</option>
                            <option value="organization">Organizasyon</option>
                            <option value="social">Sosyal Sorumluluk</option>
                            <option value="other">Diğer Başarılar</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1; margin: 0;">
                        <label class="form-label" style="font-weight: 600;">Tarih (Ay/Yıl baz alınır) <span style="color:var(--danger);">*</span></label>
                        <input type="date" name="award_date" class="form-control" required style="padding: 12px 16px; border-radius: 12px; font-size: 14px;" value="<?= date('Y-m-d') ?>">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="font-weight: 600;">Vitrin Görseli (Kupa, Plaket, Belge vb.) <span style="color:var(--danger);">*</span></label>
                    <div style="border: 2px dashed var(--border); padding: 20px; border-radius: 12px; text-align: center; background: rgba(0,0,0,0.02);">
                        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required style="width: 100%; cursor: pointer;">
                        <p style="font-size: 12px; color: var(--muted); margin: 8px 0 0 0;">Yalnızca JPG, PNG veya WEBP. İdeal oran 16:9 yatay görsellerdir.</p>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" style="font-weight: 600;">Hikayesi / Açıklama <span style="color:var(--danger);">*</span></label>
                    <textarea name="description" class="form-control" rows="4" style="padding: 12px 16px; border-radius: 12px; font-size: 14px;" required placeholder="Bu ödülü nasıl kazandınız? Projeniz neydi?"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn btn-outline" style="border-radius: 12px; padding: 10px 20px; font-weight: 600;" onclick="document.getElementById('modal-add-trophy').style.display='none'">İptal Et</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 12px; padding: 10px 24px; font-weight: 600; background: linear-gradient(135deg, #f59e0b, #d97706); border: none;">Kürsüye Ekle</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Modal: Edit Trophy -->
<?php if ($canEdit): ?>
<div id="modal-edit-trophy" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(8px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card pop-in" style="width: 100%; max-width: 600px; margin: 20px; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); border: 1px solid var(--border);">
        <div class="card-header" style="background: var(--bg); padding: 24px; border-bottom: 1px solid var(--border); border-top-left-radius: 20px; border-top-right-radius: 20px;">
            <span style="font-size: 20px; font-weight: 700; color: var(--text);">Ödülü / Başarıyı Düzenle</span>
            <button type="button" onclick="document.getElementById('modal-edit-trophy').style.display='none'" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 24px; line-height: 1;">&times;</button>
        </div>
        <div class="card-body" style="padding: 24px; max-height: 80vh; overflow-y: auto;">
            <form action="<?= $appUrl ?>" method="POST" enctype="multipart/form-data" style="margin: 0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="edit_trophy">
                <input type="hidden" name="trophy_id" id="edit-trophy-id">
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="font-weight: 600;">Başarı / Ödül Başlığı <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="title" id="edit-trophy-title" class="form-control" required style="padding: 12px 16px; border-radius: 12px; font-size: 14px;">
                </div>
                
                <div style="display: flex; gap: 16px; margin-bottom: 20px;">
                    <div class="form-group" style="flex: 1; margin: 0;">
                        <label class="form-label" style="font-weight: 600;">Kategori</label>
                        <select name="award_type" id="edit-trophy-type" class="form-control" style="padding: 12px 16px; border-radius: 12px; font-size: 14px;">
                            <option value="hackathon">Hackathon</option>
                            <option value="competition">Yarışma</option>
                            <option value="project">Proje</option>
                            <option value="organization">Organizasyon</option>
                            <option value="social">Sosyal Sorumluluk</option>
                            <option value="other">Diğer Başarılar</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1; margin: 0;">
                        <label class="form-label" style="font-weight: 600;">Tarih (Ay/Yıl baz alınır) <span style="color:var(--danger);">*</span></label>
                        <input type="date" name="award_date" id="edit-trophy-date" class="form-control" required style="padding: 12px 16px; border-radius: 12px; font-size: 14px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="font-weight: 600;">Vitrin Görseli (Değiştirmek istemiyorsanız boş bırakın)</label>
                    <div style="border: 2px dashed var(--border); padding: 20px; border-radius: 12px; text-align: center; background: rgba(0,0,0,0.02);">
                        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" style="width: 100%; cursor: pointer;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" style="font-weight: 600;">Hikayesi / Açıklama <span style="color:var(--danger);">*</span></label>
                    <textarea name="description" id="edit-trophy-desc" class="form-control" rows="4" style="padding: 12px 16px; border-radius: 12px; font-size: 14px;" required></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn btn-outline" style="border-radius: 12px; padding: 10px 20px; font-weight: 600;" onclick="document.getElementById('modal-edit-trophy').style.display='none'">İptal Et</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 12px; padding: 10px 24px; font-weight: 600; background: linear-gradient(135deg, #f59e0b, #d97706); border: none;">Değişiklikleri Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditTrophyModal(trophy) {
        document.getElementById('edit-trophy-id').value = trophy.id;
        document.getElementById('edit-trophy-title').value = trophy.title;
        document.getElementById('edit-trophy-type').value = trophy.award_type;
        document.getElementById('edit-trophy-date').value = trophy.award_date;
        document.getElementById('edit-trophy-desc').value = trophy.description;
        document.getElementById('modal-edit-trophy').style.display = 'flex';
    }
</script>
<?php endif; ?>
