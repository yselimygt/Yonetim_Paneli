<?php
/**
 * @var array $app
 * @var array $department
 * @var array $user
 * @var bool $canManage
 * @var array $articles
 * @var array|null $activeArticle
 * @var array $categories
 * @var string $catFilter
 * @var string $qFilter
 */
$appUrl = BASE_URL . "/panel/{$department['slug']}/apps/{$app['slug']}";
?>

<style>
    .wiki-container {
        display: flex;
        gap: 24px;
        min-height: 700px;
        flex-wrap: wrap;
    }
    .wiki-sidebar {
        flex: 0 0 280px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .wiki-main {
        flex: 1;
        min-width: 400px;
    }
    .article-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        color: var(--text);
        display: block;
    }
    .article-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        border-color: #3b82f6;
    }
    .category-link {
        display: block;
        padding: 8px 12px;
        border-radius: 8px;
        color: var(--muted);
        text-decoration: none;
        transition: all 0.2s;
        font-size: 14px;
        font-weight: 500;
    }
    .category-link:hover, .category-link.active {
        background: rgba(59,130,246,0.1);
        color: #3b82f6;
    }
    
    .wiki-content-box {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
    }
    
    .wiki-content-text {
        font-size: 16px;
        line-height: 1.8;
        color: var(--text);
    }
    .wiki-content-text p {
        margin-bottom: 1.5em;
    }
    .wiki-content-text h1, .wiki-content-text h2, .wiki-content-text h3 {
        margin-top: 1.5em;
        margin-bottom: 0.5em;
        color: var(--text);
    }
    .wiki-content-text ul, .wiki-content-text ol {
        margin-bottom: 1.5em;
        padding-left: 20px;
    }
</style>

<!-- Header -->
<div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px;">
    <div style="display: flex; align-items: center; gap: 16px;">
        <div style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, rgba(59,130,246,0.2), rgba(59,130,246,0.1)); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" width="28" height="28">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
        </div>
        <div>
            <h3 style="font-size: 24px; font-weight: 700; color: var(--text); margin: 0;"><?= View::escape($app['name']) ?></h3>
            <p style="font-size: 14px; color: var(--muted); margin: 4px 0 0 0;">Kurumsal bilgi bankası, rehberler ve teknik dokümanlar.</p>
        </div>
    </div>
    
    <button class="btn btn-primary" style="padding: 10px 20px; border-radius: 8px; font-weight: 500;" onclick="document.getElementById('modal-create-article').style.display='flex'">
        + Yeni Makale Yaz
    </button>
</div>

<div class="wiki-container fade-in-up stagger-1">
    
    <!-- Sidebar -->
    <div class="wiki-sidebar">
        <!-- Search -->
        <div style="background: var(--bg); padding: 16px; border-radius: 16px; border: 1px solid var(--border);">
            <form action="<?= $appUrl ?>" method="GET" style="margin:0; position:relative;">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--muted);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" name="q" value="<?= View::escape($qFilter) ?>" placeholder="Makalelerde Ara..." style="width:100%; padding: 10px 12px 10px 36px; border-radius: 8px; border: 1px solid var(--border); background: var(--card); color: var(--text); font-size: 13px;">
            </form>
        </div>

        <!-- Categories -->
        <div style="background: var(--bg); padding: 16px; border-radius: 16px; border: 1px solid var(--border);">
            <h4 style="font-size: 13px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px;">Kategoriler</h4>
            
            <a href="<?= $appUrl ?>" class="category-link <?= $catFilter === '' ? 'active' : '' ?>">Tümü</a>
            <?php foreach ($categories as $cat): ?>
                <?php if ($cat): ?>
                    <a href="<?= $appUrl ?>?cat=<?= urlencode($cat) ?>" class="category-link <?= $catFilter === $cat ? 'active' : '' ?>">
                        <?= View::escape($cat) ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="wiki-main">
        <?php if ($activeArticle): ?>
            <!-- READ ARTICLE -->
            <div style="margin-bottom: 16px;">
                <a href="<?= $appUrl ?>" style="color: #3b82f6; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 4px; font-weight: 500;">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Tüm Makalelere Dön
                </a>
            </div>

            <div class="wiki-content-box">
                <div style="margin-bottom: 32px; border-bottom: 1px solid var(--border); padding-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
                        <div>
                            <span style="background: rgba(59,130,246,0.1); color: #3b82f6; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; margin-bottom: 12px; display: inline-block;">
                                <?= View::escape($activeArticle['category']) ?>
                            </span>
                            <h1 style="font-size: 32px; font-weight: 800; color: var(--text); margin: 0 0 16px 0; line-height: 1.2;">
                                <?= View::escape($activeArticle['title']) ?>
                            </h1>
                            
                            <div style="display: flex; align-items: center; gap: 16px; color: var(--muted); font-size: 13px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <?php if ($activeArticle['author_avatar']): ?>
                                        <img src="<?= htmlspecialchars(BASE_URL . '/public/' . $activeArticle['author_avatar']) ?>" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">
                                    <?php else: ?>
                                        <div style="width: 24px; height: 24px; border-radius: 50%; background: #3b82f6; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold;">
                                            <?= strtoupper(mb_substr($activeArticle['author_name'] ?? '?', 0, 2)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <span style="font-weight: 500; color: var(--text);"><?= View::escape($activeArticle['author_name']) ?></span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 4px;">
                                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    <?= date('d M Y', strtotime($activeArticle['created_at'])) ?>
                                </div>
                                <div style="display: flex; align-items: center; gap: 4px;">
                                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    <?= $activeArticle['views'] ?> Okunma
                                </div>
                            </div>
                        </div>

                        <?php if ($canManage || $activeArticle['author_id'] == $user['id']): ?>
                            <div style="display: flex; gap: 8px;">
                                <button class="btn btn-outline" style="padding: 8px 12px; border-radius: 8px; border-color: transparent;" onclick="document.getElementById('modal-edit-article').style.display='flex'">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </button>
                                <form action="<?= $appUrl ?>" method="POST" style="margin:0;" onsubmit="return confirm('Bu makaleyi silmek istediğinize emin misiniz?');">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                    <input type="hidden" name="action" value="delete_article">
                                    <input type="hidden" name="article_id" value="<?= $activeArticle['id'] ?>">
                                    <button type="submit" class="btn btn-outline" style="padding: 8px 12px; border-radius: 8px; color: var(--danger); border-color: transparent;">
                                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="wiki-content-text">
                    <?= nl2br(View::escape($activeArticle['content'])) ?>
                </div>
            </div>

            <!-- Modal: Edit Article -->
            <?php if ($canManage || $activeArticle['author_id'] == $user['id']): ?>
            <div id="modal-edit-article" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
                <div class="card pop-in" style="width: 100%; max-width: 800px; margin: 20px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
                    <div class="card-header" style="background: var(--bg); padding: 20px; border-bottom: 1px solid var(--border);">
                        <span style="font-size: 18px; font-weight: 600;">Makaleyi Düzenle</span>
                        <button type="button" onclick="document.getElementById('modal-edit-article').style.display='none'" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 20px;">&times;</button>
                    </div>
                    <div class="card-body" style="padding: 24px; max-height: 80vh; overflow-y: auto;">
                        <form action="<?= $appUrl ?>" method="POST" style="margin: 0;">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="action" value="edit_article">
                            <input type="hidden" name="article_id" value="<?= $activeArticle['id'] ?>">
                            
                            <div class="form-row" style="display: flex; gap: 16px; margin-bottom: 16px;">
                                <div class="form-group" style="flex: 2; margin: 0;">
                                    <label class="form-label" style="font-weight: 500;">Başlık <span style="color:var(--danger);">*</span></label>
                                    <input type="text" name="title" class="form-control" required style="padding: 10px 12px; border-radius: 8px;" value="<?= View::escape($activeArticle['title']) ?>">
                                </div>
                                <div class="form-group" style="flex: 1; margin: 0;">
                                    <label class="form-label" style="font-weight: 500;">Kategori</label>
                                    <input type="text" name="category" class="form-control" style="padding: 10px 12px; border-radius: 8px;" value="<?= View::escape($activeArticle['category']) ?>" list="category-list">
                                </div>
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 24px;">
                                <label class="form-label" style="font-weight: 500;">İçerik <span style="color:var(--danger);">*</span></label>
                                <textarea name="content" class="form-control" rows="15" style="padding: 12px; border-radius: 8px; font-family: inherit; font-size: 14px;" required><?= View::escape($activeArticle['content']) ?></textarea>
                            </div>

                            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                                <button type="button" class="btn btn-outline" style="border-radius: 8px;" onclick="document.getElementById('modal-edit-article').style.display='none'">İptal</button>
                                <button type="submit" class="btn btn-primary" style="border-radius: 8px;">Güncelle</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- LIST ARTICLES -->
            <?php if (empty($articles)): ?>
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 300px; background: var(--card); border: 2px dashed var(--border); border-radius: 16px; color: var(--muted);">
                    <svg viewBox="0 0 24 24" width="48" height="48" stroke="currentColor" stroke-width="1.5" fill="none" style="margin-bottom: 16px; opacity: 0.5;"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    <p>Henüz makale bulunmuyor.</p>
                </div>
            <?php else: ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 16px;">
                    <?php foreach ($articles as $art): ?>
                        <a href="<?= $appUrl ?>?article=<?= $art['id'] ?>" class="article-card">
                            <span style="font-size: 11px; font-weight: 700; color: #3b82f6; text-transform: uppercase; margin-bottom: 8px; display: block;">
                                <?= View::escape($art['category']) ?>
                            </span>
                            <h4 style="font-size: 18px; font-weight: 700; margin: 0 0 16px 0; line-height: 1.4; color: var(--text);">
                                <?= View::escape($art['title']) ?>
                            </h4>
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 12px; font-size: 12px; color: var(--muted);">
                                <span>Yazar: <strong><?= View::escape($art['author_name']) ?></strong></span>
                                <div style="display: flex; gap: 12px;">
                                    <span><?= date('d.m.Y', strtotime($art['created_at'])) ?></span>
                                    <span>👁 <?= $art['views'] ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Datalist for categories -->
<datalist id="category-list">
    <?php foreach ($categories as $cat): ?>
        <?php if($cat): ?><option value="<?= View::escape($cat) ?>"><?php endif; ?>
    <?php endforeach; ?>
</datalist>

<!-- Modal: Yeni Makale -->
<div id="modal-create-article" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card pop-in" style="width: 100%; max-width: 800px; margin: 20px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div class="card-header" style="background: var(--bg); padding: 20px; border-bottom: 1px solid var(--border);">
            <span style="font-size: 18px; font-weight: 600;">Yeni Makale Yaz</span>
            <button type="button" onclick="document.getElementById('modal-create-article').style.display='none'" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 20px;">&times;</button>
        </div>
        <div class="card-body" style="padding: 24px; max-height: 80vh; overflow-y: auto;">
            <form action="<?= $appUrl ?>" method="POST" style="margin: 0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="create_article">
                
                <div class="form-row" style="display: flex; gap: 16px; margin-bottom: 16px;">
                    <div class="form-group" style="flex: 2; margin: 0;">
                        <label class="form-label" style="font-weight: 500;">Başlık <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Örn: Yeni Sunucu Mimarisi Kurulum Rehberi" required style="padding: 10px 12px; border-radius: 8px;">
                    </div>
                    <div class="form-group" style="flex: 1; margin: 0;">
                        <label class="form-label" style="font-weight: 500;">Kategori</label>
                        <input type="text" name="category" class="form-control" placeholder="Örn: Rehberler" style="padding: 10px 12px; border-radius: 8px;" list="category-list">
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" style="font-weight: 500;">İçerik <span style="color:var(--danger);">*</span></label>
                    <textarea name="content" class="form-control" rows="15" style="padding: 12px; border-radius: 8px; font-family: inherit; font-size: 14px;" required placeholder="Makale içeriğinizi buraya yazın..."></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn btn-outline" style="border-radius: 8px;" onclick="document.getElementById('modal-create-article').style.display='none'">İptal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px;">Yayınla</button>
                </div>
            </form>
        </div>
    </div>
</div>
