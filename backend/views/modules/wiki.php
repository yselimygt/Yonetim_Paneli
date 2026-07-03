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
    .wiki-mobile-back {
        display: none;
    }

    @media (max-width: 768px) {
        .wiki-header {
            margin-bottom: 16px !important;
            align-items: center !important;
            gap: 12px !important;
        }
        .wiki-header-main {
            gap: 10px !important;
            min-width: 0;
        }
        .wiki-header-icon {
            width: 42px !important;
            height: 42px !important;
            border-radius: 12px !important;
            flex: 0 0 auto;
        }
        .wiki-header h3 {
            font-size: 18px !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .wiki-header p {
            display: none;
        }
        .wiki-header .btn {
            width: 100%;
            min-height: 40px;
            padding: 9px 12px !important;
            border-radius: 12px !important;
            font-size: 13px;
        }
        .wiki-container {
            display: block;
            min-height: 0;
        }
        .wiki-sidebar {
            width: 100%;
            flex: none;
            gap: 8px;
            margin-bottom: 10px;
        }
        .wiki-sidebar > div {
            padding: 10px !important;
            border-radius: 14px !important;
        }
        .wiki-sidebar input {
            min-height: 38px;
            border-radius: 9px !important;
            font-size: 12px !important;
        }
        .wiki-sidebar h4 {
            display: none;
        }
        .wiki-sidebar > div:nth-child(2) {
            display: flex;
            gap: 7px;
            overflow-x: auto;
            padding-bottom: 8px !important;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }
        .category-link {
            flex: 0 0 auto;
            padding: 7px 10px;
            border-radius: 999px;
            font-size: 11px;
            background: var(--card);
            border: 1px solid var(--border);
        }
        .wiki-main {
            min-width: 0;
            width: 100%;
        }
        .wiki-main > div[style*="grid-template-columns"] {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 10px !important;
        }
        .article-card {
            padding: 11px;
            border-radius: 14px;
            margin-bottom: 0;
            min-width: 0;
        }
        .article-card:hover {
            transform: none;
            box-shadow: none;
        }
        .article-card span:first-child {
            font-size: 9px !important;
            margin-bottom: 6px !important;
        }
        .article-card h4 {
            font-size: 13px !important;
            line-height: 1.25 !important;
            margin-bottom: 10px !important;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .article-card h4 + div {
            display: block !important;
            padding-top: 8px !important;
            font-size: 10px !important;
        }
        .article-card h4 + div > div {
            margin-top: 5px;
            gap: 7px !important;
        }
        .wiki-content-box {
            padding: 14px;
            border-radius: 14px;
            box-shadow: none;
        }
        .wiki-content-box h1 {
            font-size: 22px !important;
        }
        .wiki-content-box > div:first-child {
            margin-bottom: 18px !important;
            padding-bottom: 14px !important;
        }
        .wiki-content-box > div:first-child > div {
            gap: 12px !important;
        }
        .wiki-content-box > div:first-child div[style*="align-items: center"] {
            flex-wrap: wrap;
            gap: 8px !important;
            font-size: 11px !important;
        }
        .wiki-content-text {
            font-size: 14px;
            line-height: 1.65;
        }
        .wiki-container.article-open .wiki-sidebar,
        .wiki-container.article-open + #modal-create-article {
            display: none;
        }
        .wiki-header.article-open .btn {
            display: none;
        }
        .wiki-container.article-open .wiki-main {
            width: 100%;
        }
        .wiki-container.article-open .wiki-main > div:first-child {
            display: none;
        }
        .wiki-container.article-open .wiki-content-box {
            border: 0;
            background: transparent;
            padding: 0;
        }
        .wiki-container.article-open .wiki-content-box > div:first-child {
            display: none;
        }
        .wiki-container.article-open .wiki-content-text {
            font-size: 15px;
            line-height: 1.75;
            background: rgba(59, 130, 246, 0.06);
            border: 1px solid rgba(59, 130, 246, 0.12);
            border-radius: 14px;
            padding: 14px;
        }
        .wiki-container.article-open .wiki-mobile-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-height: 34px;
            padding: 7px 10px;
            margin-bottom: 12px;
            border-radius: 10px;
            background: var(--card);
            border: 1px solid var(--border);
            color: #3b82f6;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
        }
        #modal-create-article,
        #modal-edit-article {
            padding: 14px;
            align-items: center !important;
            overflow-y: auto;
            z-index: 2000 !important;
        }
        #modal-create-article > .card,
        #modal-edit-article > .card {
            width: 100% !important;
            max-width: 100% !important;
            max-height: calc(100vh - 28px);
            margin: auto !important;
            border-radius: 14px !important;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        #modal-create-article .card-header,
        #modal-edit-article .card-header {
            padding: 14px !important;
        }
        #modal-create-article .card-body,
        #modal-edit-article .card-body {
            padding: 14px !important;
            max-height: none !important;
            overflow-y: auto;
        }
        #modal-create-article .form-row,
        #modal-edit-article .form-row {
            flex-direction: column;
            gap: 0 !important;
            margin-bottom: 12px !important;
        }
        #modal-create-article .form-group,
        #modal-edit-article .form-group {
            margin-bottom: 12px !important;
        }
        #modal-create-article .form-label,
        #modal-edit-article .form-label {
            font-size: 11px;
            margin-bottom: 6px;
        }
        #modal-create-article .form-control,
        #modal-edit-article .form-control {
            min-height: 38px;
            padding: 9px 10px !important;
            border-radius: 9px !important;
            font-size: 13px !important;
        }
        #modal-create-article textarea.form-control,
        #modal-edit-article textarea.form-control {
            min-height: 220px;
        }
        #modal-create-article form > div:last-child,
        #modal-edit-article form > div:last-child {
            display: grid !important;
            grid-template-columns: 1fr 1fr;
            gap: 8px !important;
        }
        #modal-create-article .btn,
        #modal-edit-article .btn {
            width: 100%;
            min-height: 38px;
            padding: 8px 10px !important;
            border-radius: 10px !important;
            font-size: 13px;
        }
    }
</style>

<!-- Header -->
<div class="wiki-header <?= $activeArticle ? 'article-open' : '' ?>" style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px;">
    <div class="wiki-header-main" style="display: flex; align-items: center; gap: 16px;">
        <div class="wiki-header-icon" style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, rgba(59,130,246,0.2), rgba(59,130,246,0.1)); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#modal-create-article, #modal-edit-article').forEach(function(modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
});
</script>

<div class="wiki-container <?= $activeArticle ? 'article-open' : '' ?> fade-in-up stagger-1">
    
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
            <a href="<?= $appUrl ?>" class="wiki-mobile-back">
                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Geri
            </a>
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
