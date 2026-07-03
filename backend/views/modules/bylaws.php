<?php
/**
 * @var array $app
 * @var array $department
 * @var array $user
 * @var bool $canEdit
 * @var array $documents
 * @var array|null $activeDoc
 * @var array|null $activeVersion
 * @var array $allVersions
 */
$appUrl = BASE_URL . "/panel/{$department['slug']}/apps/{$app['slug']}";
?>

<style>
    .bylaws-container {
        display: flex;
        gap: 24px;
        min-height: 600px;
        flex-wrap: wrap;
    }
    
    .bylaws-sidebar {
        flex: 0 0 320px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .bylaws-main {
        flex: 1;
        min-width: 400px;
        display: flex;
        flex-direction: column;
    }

    .doc-list-item {
        padding: 16px;
        border-radius: 12px;
        background: var(--card);
        border: 1px solid var(--border);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        color: var(--text);
        display: block;
        margin-bottom: 8px;
    }
    .doc-list-item:hover {
        border-color: var(--accent, #3b82f6);
        transform: translateX(4px);
    }
    .doc-list-item.active {
        background: linear-gradient(to right, rgba(59,130,246,0.1), rgba(59,130,246,0.02));
        border-color: #3b82f6;
        border-left: 4px solid #3b82f6;
    }

    .bylaw-content-box {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        font-size: 15px;
        line-height: 1.7;
        color: var(--text);
    }

    .bylaw-content-box p {
        margin-bottom: 16px;
    }

    .search-input-wrapper {
        position: relative;
        margin-bottom: 24px;
    }
    .search-input-wrapper svg {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
    }
    .search-input-wrapper input {
        width: 100%;
        padding: 12px 16px 12px 48px;
        border-radius: 12px;
        background: var(--bg);
        border: 1px solid var(--border);
        color: var(--text);
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-input-wrapper input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
        outline: none;
    }
    
    .highlight {
        background-color: rgba(250, 204, 21, 0.4);
        padding: 0 2px;
        border-radius: 3px;
    }

    .version-badge {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .bylaws-page-header {
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 20px;
    }

    .bylaws-title-wrap {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .bylaws-title-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(16,185,129,0.2), rgba(16,185,129,0.1));
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .bylaws-list-shell {
        background: var(--bg);
        padding: 16px;
        border-radius: 16px;
        border: 1px solid var(--border);
    }

    .bylaws-list-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 16px;
    }

    .bylaws-doc-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .bylaws-doc-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .bylaws-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .bylaws-modal-card {
        width: 100%;
        max-width: 600px;
        margin: 0;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        overflow: hidden;
    }

    .bylaws-modal-card.wide {
        max-width: 800px;
    }

    .bylaws-modal-body {
        padding: 24px;
        max-height: 80vh;
        overflow-y: auto;
    }

    .bylaws-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    @media (max-width: 768px) {
        .bylaws-page-header {
            margin-bottom: 14px;
            align-items: center;
            gap: 10px;
        }

        .bylaws-title-wrap {
            gap: 10px;
            min-width: 0;
        }

        .bylaws-title-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
        }

        .bylaws-title-icon svg {
            width: 20px;
            height: 20px;
        }

        .bylaws-page-header h3 {
            font-size: 18px !important;
            line-height: 1.15;
        }

        .bylaws-page-header p {
            display: none;
        }

        .bylaws-page-header .btn {
            padding: 8px 10px !important;
            border-radius: 9px !important;
            font-size: 11px;
            white-space: nowrap;
        }

        .bylaws-container {
            display: block;
            min-height: 0;
        }

        .bylaws-sidebar {
            display: block;
            margin-bottom: 14px;
        }

        .bylaws-main {
            min-width: 0;
            width: 100%;
        }

        .search-input-wrapper {
            margin-bottom: 10px;
        }

        .search-input-wrapper input {
            min-height: 38px;
            padding: 10px 12px 10px 38px;
            border-radius: 10px;
            font-size: 12px;
        }

        .search-input-wrapper svg {
            left: 12px;
            width: 15px;
            height: 15px;
        }

        .bylaws-list-shell {
            padding: 0;
            border: 0;
            background: transparent;
        }

        .bylaws-list-title {
            display: none;
        }

        #doc-list {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 2px 2px 8px;
            scroll-snap-type: x proximity;
            -webkit-overflow-scrolling: touch;
        }

        #doc-list::-webkit-scrollbar {
            height: 4px;
        }

        #doc-list::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 999px;
        }

        .doc-list-item {
            flex: 0 0 72%;
            min-height: 76px;
            margin: 0;
            padding: 12px;
            border-radius: 13px;
            scroll-snap-align: start;
        }

        .doc-list-item:hover {
            transform: none;
        }

        .doc-list-item.active {
            border-left-width: 1px;
            box-shadow: 0 10px 22px rgba(59,130,246,.12);
        }

        .doc-list-item > div:first-child {
            font-size: 12px !important;
            line-height: 1.25;
        }

        .doc-list-item > div:last-child {
            font-size: 10px !important;
            -webkit-line-clamp: 1 !important;
        }

        .bylaws-doc-head {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
            margin-bottom: 12px;
        }

        .bylaws-doc-head h2 {
            font-size: 18px !important;
            line-height: 1.2;
            margin-bottom: 7px !important;
        }

        .version-badge {
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 10px;
            flex-wrap: wrap;
        }

        .bylaws-doc-actions {
            display: grid;
            grid-template-columns: 1fr auto auto;
            gap: 7px;
            align-items: stretch;
        }

        .bylaws-doc-actions .search-input-wrapper {
            grid-column: 1 / -1;
            margin-bottom: 0 !important;
        }

        .bylaws-doc-actions .search-input-wrapper input {
            width: 100% !important;
        }

        .bylaws-doc-actions .btn {
            min-height: 34px;
            padding: 8px 9px !important;
            border-radius: 9px !important;
            font-size: 10px;
            white-space: nowrap;
        }

        #version-dropdown {
            position: fixed !important;
            left: 12px !important;
            right: 12px !important;
            top: auto !important;
            bottom: calc(88px + env(safe-area-inset-bottom)) !important;
            width: auto !important;
            max-height: 42vh;
            overflow-y: auto;
            z-index: 1100 !important;
        }

        .bylaw-content-box {
            padding: 16px;
            border-radius: 14px;
            background: color-mix(in srgb, var(--card) 86%, #0ea5e9 6%);
            font-size: 13px;
            line-height: 1.65;
        }

        .bylaw-content-box p {
            margin-bottom: 10px;
        }

        .bylaws-modal {
            align-items: flex-end;
            padding: 10px;
            z-index: 1500;
        }

        .bylaws-modal-card,
        .bylaws-modal-card.wide {
            max-width: none;
            max-height: calc(92dvh - env(safe-area-inset-bottom));
            border-radius: 16px 16px 12px 12px;
        }

        .bylaws-modal .card-header {
            padding: 14px !important;
        }

        .bylaws-modal .card-header span {
            max-width: calc(100vw - 94px);
            overflow: hidden;
            font-size: 14px !important;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .bylaws-modal-body {
            padding: 14px;
            max-height: calc(92dvh - 58px - env(safe-area-inset-bottom));
        }

        .bylaws-modal textarea {
            min-height: 132px;
            font-size: 12px;
        }

        .bylaws-form-actions {
            display: grid !important;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .bylaws-form-actions .btn {
            width: 100%;
            min-height: 38px;
        }
    }
</style>

<!-- Header -->
<div class="bylaws-page-header">
    <div class="bylaws-title-wrap">
        <div class="bylaws-title-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" width="28" height="28">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </div>
        <div>
            <h3 style="font-size: 24px; font-weight: 700; color: var(--text); margin: 0;"><?= View::escape($app['name']) ?></h3>
            <p style="font-size: 14px; color: var(--muted); margin: 4px 0 0 0;">Kurumsal yönetmelik ve tüzükleri arayın ve okuyun.</p>
        </div>
    </div>
    
    <?php if ($canEdit): ?>
        <button class="btn btn-primary" style="padding: 10px 20px; border-radius: 8px; font-weight: 500;" onclick="openBylawsModal('modal-create-doc')">
            + Yeni Yönetmelik Ekle
        </button>
    <?php endif; ?>
</div>

<div class="bylaws-container fade-in-up stagger-1">
    
    <!-- Sidebar: Document List -->
    <div class="bylaws-sidebar">
        <!-- Search inside documents list (filters titles) -->
        <div class="search-input-wrapper">
            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" id="doc-search" placeholder="Yönetmelik Ara..." onkeyup="filterDocs()">
        </div>

        <div class="bylaws-list-shell">
            <h4 class="bylaws-list-title">Tüzük ve Yönetmelikler</h4>
            
            <div id="doc-list">
                <?php if (empty($documents)): ?>
                    <p style="font-size: 13px; color: var(--muted); text-align: center; padding: 20px 0;">Henüz doküman eklenmedi.</p>
                <?php else: ?>
                    <?php foreach ($documents as $doc): ?>
                        <a href="<?= $appUrl ?>?doc=<?= $doc['id'] ?>" class="doc-list-item <?= $activeDoc && $activeDoc['id'] == $doc['id'] ? 'active' : '' ?>">
                            <div style="font-weight: 600; margin-bottom: 4px; font-size: 14px;"><?= View::escape($doc['title']) ?></div>
                            <?php if ($doc['description']): ?>
                                <div style="font-size: 12px; color: var(--muted); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= View::escape($doc['description']) ?></div>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Area: Document Content -->
    <div class="bylaws-main">
        <?php if ($activeDoc): ?>
            
            <!-- Document Header Actions -->
            <div class="bylaws-doc-head">
                <div>
                    <h2 style="font-size: 26px; font-weight: 700; color: var(--text); margin: 0 0 8px 0;"><?= View::escape($activeDoc['title']) ?></h2>
                    <?php if ($activeVersion): ?>
                        <div class="version-badge">
                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                            Versiyon: <?= View::escape($activeVersion['version_number']) ?>
                            <?php if ($activeVersion['is_published']): ?>
                                <span style="background: rgba(16,185,129,0.2); color: #10b981; padding: 2px 6px; border-radius: 12px; font-size: 10px; margin-left: 4px;">Aktif (Yayında)</span>
                            <?php else: ?>
                                <span style="background: rgba(245,158,11,0.2); color: #f59e0b; padding: 2px 6px; border-radius: 12px; font-size: 10px; margin-left: 4px;">Eski Sürüm / Taslak</span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <span style="font-size: 13px; color: var(--danger);">Bu yönetmeliğin henüz bir içeriği yok.</span>
                    <?php endif; ?>
                </div>

                <div class="bylaws-doc-actions">
                    <!-- Real-time Text Search in Document -->
                    <div class="search-input-wrapper" style="margin-bottom: 0;">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" id="content-search" placeholder="Metin içinde ara..." style="padding: 10px 16px 10px 40px; font-size: 13px; width: 200px;" onkeyup="searchInContent()">
                    </div>

                    <?php if ($canEdit): ?>
                        <div style="position: relative;">
                            <button class="btn btn-outline" style="padding: 10px 16px; border-radius: 8px;" onclick="document.getElementById('version-dropdown').style.display = document.getElementById('version-dropdown').style.display === 'block' ? 'none' : 'block'">
                                Versiyon Geçmişi ▾
                            </button>
                            <div id="version-dropdown" style="display: none; position: absolute; right: 0; top: 100%; margin-top: 8px; background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 8px; width: 280px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); z-index: 100;">
                                <?php foreach ($allVersions as $v): ?>
                                    <a href="<?= $appUrl ?>?doc=<?= $activeDoc['id'] ?>&v=<?= $v['id'] ?>" style="display: block; padding: 10px; text-decoration: none; border-radius: 8px; color: var(--text); background: <?= $activeVersion && $activeVersion['id'] == $v['id'] ? 'rgba(59,130,246,0.1)' : 'transparent' ?>; margin-bottom: 4px;">
                                        <div style="font-weight: 600; font-size: 13px; display: flex; justify-content: space-between;">
                                            <span><?= View::escape($v['version_number']) ?></span>
                                            <?php if ($v['is_published']): ?>
                                                <span style="color: #10b981; font-size: 10px;">Aktif</span>
                                            <?php endif; ?>
                                        </div>
                                        <div style="font-size: 11px; color: var(--muted); margin-top: 4px; display: flex; justify-content: space-between;">
                                            <span><?= View::escape($v['changes_summary'] ?? '') ?></span>
                                            <span><?= View::escape(mb_substr($v['creator_name'] ?? '', 0, 15)) ?></span>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                                <hr style="border: 0; border-top: 1px solid var(--border); margin: 8px 0;">
                                <button class="btn btn-primary btn-sm" style="width: 100%;" onclick="openBylawsModal('modal-add-version'); document.getElementById('version-dropdown').style.display='none';">
                                    + Yeni Versiyon Çıkar
                                </button>
                            </div>
                        </div>

                        <?php if ($activeVersion && !$activeVersion['is_published']): ?>
                            <form action="<?= $appUrl ?>" method="POST" style="margin:0;">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                <input type="hidden" name="action" value="publish_version">
                                <input type="hidden" name="document_id" value="<?= $activeDoc['id'] ?>">
                                <input type="hidden" name="version_id" value="<?= $activeVersion['id'] ?>">
                                <button type="submit" class="btn btn-primary" style="padding: 10px 16px; border-radius: 8px; background: #10b981; border-color: #10b981;">
                                    Bu Versiyonu Yayınla
                                </button>
                            </form>
                        <?php endif; ?>

                        <form action="<?= $appUrl ?>" method="POST" style="margin:0;" onsubmit="return confirm('Bu yönetmeliği ve tüm versiyonlarını silmek istediğinize emin misiniz?');">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="action" value="delete_document">
                            <input type="hidden" name="document_id" value="<?= $activeDoc['id'] ?>">
                            <button type="submit" class="btn btn-outline" style="padding: 10px; border-radius: 8px; color: var(--danger); border-color: transparent;">
                                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Content Area -->
            <div class="bylaw-content-box" id="bylaw-content-display">
                <?php if ($activeVersion): ?>
                    <div id="searchable-content">
                        <?= nl2br(View::escape($activeVersion['content'])) ?>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; color: var(--muted); padding: 40px;">
                        Henüz bir versiyon/içerik eklenmemiş. Lütfen "Yeni Versiyon Çıkar" butonuyla ilk içeriği girin.
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if ($activeVersion && !empty($activeVersion['changes_summary'])): ?>
                <div style="margin-top: 20px; padding: 16px; background: rgba(59,130,246,0.05); border-left: 4px solid #3b82f6; border-radius: 0 8px 8px 0;">
                    <h5 style="margin: 0 0 8px 0; font-size: 13px; color: #3b82f6;">Değişiklik Özeti (Sürüm Notu)</h5>
                    <p style="margin: 0 0 12px 0; font-size: 13px; color: var(--text); opacity: 0.9;"><?= View::escape($activeVersion['changes_summary']) ?></p>
                    <div style="display: flex; align-items: center; gap: 8px; border-top: 1px solid rgba(59,130,246,0.1); padding-top: 12px;">
                        <?php if (!empty($activeVersion['creator_avatar'])): ?>
                            <img src="<?= htmlspecialchars(BASE_URL . '/public/' . $activeVersion['creator_avatar']) ?>" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <?php else: ?>
                            <div style="width: 24px; height: 24px; border-radius: 50%; background: #3b82f6; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                <?= strtoupper(mb_substr($activeVersion['creator_name'] ?? '?', 0, 2)) ?>
                            </div>
                        <?php endif; ?>
                        <span style="font-size: 12px; color: var(--muted);">Bu değişikliği <strong style="color: var(--text);"><?= View::escape($activeVersion['creator_name'] ?? 'Bilinmiyor') ?></strong> yaptı.</span>
                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; border: 2px dashed var(--border); border-radius: 16px; color: var(--muted);">
                <svg viewBox="0 0 24 24" width="48" height="48" stroke="currentColor" stroke-width="1.5" fill="none" style="margin-bottom: 16px; opacity: 0.5;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                <p>Görüntülemek için sol menüden bir doküman seçin.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal: Yeni Doküman Oluştur -->
<?php if ($canEdit): ?>
<div id="modal-create-doc" class="bylaws-modal">
    <div class="card pop-in bylaws-modal-card">
        <div class="card-header" style="background: var(--bg); padding: 20px; border-bottom: 1px solid var(--border);">
            <span style="font-size: 18px; font-weight: 600;">Yeni Yönetmelik/Tüzük Ekle</span>
            <button type="button" onclick="closeBylawsModal('modal-create-doc')" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 20px;">&times;</button>
        </div>
        <div class="card-body bylaws-modal-body">
            <form action="<?= $appUrl ?>" method="POST" style="margin: 0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="create_document">
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">Başlık <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Örn: Personel Disiplin Yönetmeliği" required style="padding: 10px 12px; border-radius: 8px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">Kısa Açıklama</label>
                    <textarea name="description" class="form-control" rows="2" style="padding: 10px 12px; border-radius: 8px;"></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" style="font-weight: 500;">v1.0 İlk İçerik (Maddeler)</label>
                    <textarea name="initial_content" class="form-control" rows="10" style="padding: 12px; border-radius: 8px; font-family: monospace;" required placeholder="Madde 1: ...&#10;Madde 2: ..."></textarea>
                </div>

                <div class="bylaws-form-actions">
                    <button type="button" class="btn btn-outline" style="border-radius: 8px;" onclick="closeBylawsModal('modal-create-doc')">İptal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px;">Oluştur</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($activeDoc): ?>
<!-- Modal: Yeni Versiyon -->
<div id="modal-add-version" class="bylaws-modal">
    <div class="card pop-in bylaws-modal-card wide">
        <div class="card-header" style="background: var(--bg); padding: 20px; border-bottom: 1px solid var(--border);">
            <span style="font-size: 18px; font-weight: 600;">Yeni Versiyon Çıkar: <?= View::escape($activeDoc['title']) ?></span>
            <button type="button" onclick="closeBylawsModal('modal-add-version')" style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 20px;">&times;</button>
        </div>
        <div class="card-body bylaws-modal-body">
            <form action="<?= $appUrl ?>" method="POST" style="margin: 0;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="action" value="add_version">
                <input type="hidden" name="document_id" value="<?= $activeDoc['id'] ?>">
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">Versiyon Numarası <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="version_number" class="form-control" placeholder="Örn: v1.1 veya 2023-Revizyon" required style="padding: 10px 12px; border-radius: 8px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500;">Değişiklik Özeti (Sürüm Notu)</label>
                    <input type="text" name="changes_summary" class="form-control" placeholder="Örn: Madde 4.2 güncellendi, yeni mesai saatleri eklendi." required style="padding: 10px 12px; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" style="font-weight: 500;">Tam Metin (Yeni İçerik)</label>
                    <p style="font-size: 12px; color: var(--muted); margin-bottom: 8px;">Mevcut aktif metni kopyalayıp üzerinde değişiklik yapabilirsiniz.</p>
                    <textarea name="content" class="form-control" rows="15" style="padding: 12px; border-radius: 8px; font-family: monospace;" required><?= $activeVersion ? View::escape($activeVersion['content']) : '' ?></textarea>
                </div>

                <div class="bylaws-form-actions">
                    <button type="button" class="btn btn-outline" style="border-radius: 8px;" onclick="closeBylawsModal('modal-add-version')">İptal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px;">Yayınla ve Aktif Yap</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<script>
function openBylawsModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeBylawsModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

// Filter documents by title
function filterDocs() {
    let input = document.getElementById('doc-search');
    let filter = input.value.toLowerCase();
    let nodes = document.querySelectorAll('.doc-list-item');
    
    nodes.forEach(node => {
        if (node.innerText.toLowerCase().includes(filter)) {
            node.style.display = "block";
        } else {
            node.style.display = "none";
        }
    });
}

// Search inside active document content
function searchInContent() {
    let input = document.getElementById('content-search').value;
    let contentContainer = document.getElementById('searchable-content');
    if(!contentContainer) return;

    // Reset highlights first
    if (window.originalContent === undefined) {
        window.originalContent = contentContainer.innerHTML; // Store original
    }
    
    if (input.trim() === '') {
        contentContainer.innerHTML = window.originalContent;
        return;
    }

    // Strip previous highlights (basic reset)
    contentContainer.innerHTML = window.originalContent;
    
    // Highlight new
    let regex = new RegExp("(" + escapeRegExp(input) + ")", "gi");
    let textNodes = getTextNodes(contentContainer);
    
    // Traverse and replace
    // This is a basic highlighter avoiding HTML tags breakdown.
    // Instead of complex innerHTML replace, a simple one works for plain text converted with nl2br.
    const tempHtml = contentContainer.innerHTML;
    // To prevent breaking tags, we match text outside tags.
    const newHtml = tempHtml.replace(/(>)([^<]*)(<)/g, function(match, p1, p2, p3) {
        return p1 + p2.replace(regex, "<span class='highlight'>$1</span>") + p3;
    });
    // Also handle text before first tag and after last tag
    contentContainer.innerHTML = newHtml;
}

function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
}

function getTextNodes(node) {
    let all = [];
    for (node = node.firstChild; node; node = node.nextSibling) {
        if (node.nodeType == 3) all.push(node);
        else all = all.concat(getTextNodes(node));
    }
    return all;
}

// Close dropdowns when clicked outside
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('version-dropdown');
    if (dropdown && !e.target.closest('#version-dropdown') && !e.target.closest('[onclick*="version-dropdown"]')) {
        dropdown.style.display = 'none';
    }

    if (e.target.classList.contains('bylaws-modal')) {
        closeBylawsModal(e.target.id);
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key !== 'Escape') return;
    document.querySelectorAll('.bylaws-modal').forEach(modal => {
        if (modal.style.display === 'flex') {
            closeBylawsModal(modal.id);
        }
    });
});
</script>
