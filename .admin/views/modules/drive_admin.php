<?php
if(!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
?>
<style>
.drive-container {
    max-width: 1000px;
    margin: 0 auto;
}
.drive-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0;
}
.drive-breadcrumbs {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    color: var(--text);
}
.drive-breadcrumbs a {
    color: var(--accent);
    text-decoration: none;
    font-weight: 500;
}
.drive-breadcrumbs a:hover {
    text-decoration: underline;
}
.drive-actions {
    display: flex;
    gap: 12px;
}
.action-bar {
    display: flex; /* Always visible */
    background: var(--accent);
    color: #fff;
    padding: 12px 24px;
    border-radius: 8px;
    margin-bottom: 16px;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.action-bar-buttons {
    display: flex;
    gap: 8px;
}
.action-bar-buttons .btn {
    background: rgba(255,255,255,0.2);
    color: #fff;
    border: none;
}
.action-bar-buttons .btn:hover {
    background: rgba(255,255,255,0.3);
}

.sticky-top-bar {
    position: sticky;
    top: 50px; /* Panel topbar is 50px */
    z-index: 40; /* Below panel topbar */
    background: var(--bg);
    padding-top: 12px;
    padding-bottom: 12px;
    margin-bottom: 12px;
}
.action-bar-buttons .btn:hover:not(:disabled) {
    background: rgba(255,255,255,0.3);
}
.action-bar-buttons .btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: rgba(255,255,255,0.1);
}

.drive-layout {
    display: flex;
    gap: 24px;
    align-items: flex-start;
}
.drive-main {
    flex: 1;
    min-width: 0; /* flexbox text overflow issues */
}
.drive-details-panel {
    width: 0;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    flex-shrink: 0;
    opacity: 0;
    visibility: hidden;
    overflow: hidden;
    transform: translateX(20px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: sticky;
    top: 160px; /* Allow space for sticky-top-bar above it */
}
.drive-details-panel.show {
    width: 300px;
    padding: 24px;
    opacity: 1;
    visibility: visible;
    transform: translateX(0);
}
.details-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 16px;
    display: block;
    color: var(--muted);
}
.details-title {
    font-size: 16px;
    font-weight: 600;
    text-align: center;
    margin-bottom: 24px;
    word-break: break-all;
}
.details-info-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 13px;
}
.details-info-label {
    color: var(--muted);
}
.details-info-val {
    font-weight: 500;
    text-align: right;
}

.drive-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
}
.drive-item {
    background: var(--card);
    border: 2px solid transparent;
    border-radius: 12px;
    padding: 24px 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    user-select: none;
    position: relative;
    text-align: center;
    color: var(--text);
}
.drive-item:hover {
    border-color: var(--accent);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.drive-item.selected {
    background: rgba(59, 130, 246, 0.05);
    border-color: var(--accent);
    box-shadow: 0 0 0 1px var(--accent);
}
.drive-item.drag-over {
    background: rgba(16, 185, 129, 0.1);
    border-color: #10B981;
    transform: scale(1.05);
}
.drive-item-icon {
    width: 48px;
    height: 48px;
    margin-bottom: 16px;
    color: var(--muted);
    pointer-events: none;
}
.drive-item-folder {
    color: var(--accent);
    fill: rgba(59, 130, 246, 0.1);
}
.drive-item-pdf { color: #EF4444; }
.drive-item-word { color: #3B82F6; }
.drive-item-excel { color: #10B981; }
.drive-item-image { color: #8B5CF6; }

.drive-item-name {
    font-size: 14px;
    font-weight: 500;
    color: var(--text);
    text-align: center;
    width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.drive-item-star {
    position: absolute;
    top: 8px;
    right: 8px;
    color: var(--muted);
    cursor: pointer;
    z-index: 10;
    opacity: 0;
    transition: opacity 0.2s;
    padding: 4px;
}
.drive-item:hover .drive-item-star, .drive-item-star.starred {
    opacity: 1;
}
.drive-item-star:hover {
    color: #eab308;
}

.drive-item-meta {
    font-size: 12px;
    margin-top: 8px;
    color: var(--muted);
}
.drive-list-header {
    display: none;
}
.sortable-header {
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: color 0.2s;
}
.sortable-header:hover {
    color: var(--text);
}
.drive-grid.list-view .drive-list-header {
    display: grid;
    grid-template-columns: 24px 32px 1fr 100px 120px 120px 32px;
    gap: 12px;
    align-items: center;
    padding: 8px 16px 12px 16px;
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
    border-bottom: 1px solid var(--border);
    margin-bottom: 4px;
    user-select: none;
}

.drive-grid.list-view {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.drive-grid.list-view .drive-item {
    display: grid;
    grid-template-columns: 24px 32px 1fr 100px 120px 120px 32px;
    gap: 12px;
    align-items: center;
    padding: 8px 16px;
    height: auto;
    text-align: left;
}
.drive-grid.list-view .drive-item:hover {
    transform: none;
}
.drive-grid.list-view .drive-item-icon {
    width: 28px;
    height: 28px;
    margin: 0;
}
.drive-grid.list-view .drive-item-name {
    text-align: left;
}
.drive-grid.list-view .drive-item-star {
    position: static;
    margin: 0;
    padding: 0;
}
.drive-grid .drive-item-date,
.drive-grid .drive-item-creator {
    display: none; /* Hide in grid view */
}
.drive-grid.list-view .drive-item-date,
.drive-grid.list-view .drive-item-creator {
    display: block; /* Show in list view */
    font-size: 13px;
    color: var(--muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.drive-grid.list-view .drive-item-meta {
    margin: 0;
    text-align: right;
    font-size: 13px;
}
.drive-item-actions {
    position: absolute;
    top: 8px;
    left: 8px;
    background: transparent;
    border: none;
    color: var(--muted);
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    opacity: 0;
    transition: opacity 0.2s, background 0.2s;
    z-index: 10;
}
.drive-item:hover .drive-item-actions {
    opacity: 1;
}
.drive-item-actions:hover {
    background: rgba(0,0,0,0.05);
    color: var(--text);
}
.drive-grid.list-view .drive-item-actions {
    position: static;
    opacity: 1;
}
.dropdown-menu {
    display: none;
    position: fixed;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    z-index: 10;
    min-width: 150px;
    text-align: left;
}
.dropdown-menu.show {
    display: block;
}
.dropdown-item {
    display: block;
    padding: 8px 16px;
    color: var(--text);
    text-decoration: none;
    font-size: 13px;
    border: none;
    background: transparent;
    width: 100%;
    text-align: left;
    cursor: pointer;
}
.dropdown-item:hover {
    background: var(--input-bg);
}
.dropdown-item.text-danger {
    color: var(--danger);
}

/* Modals */
.modal {
    display: none;
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}
.modal.show {
    display: flex;
}
.modal-content {
    background: var(--card);
    border-radius: 12px;
    width: 100%;
    max-width: 400px;
    padding: 24px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}
.modal-title {
    margin-top: 0;
    margin-bottom: 16px;
    font-size: 18px;
}
.modal-close {
    float: right;
    background: transparent;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: var(--muted);
}
.form-group {
    margin-bottom: 16px;
}
.form-label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    color: var(--muted);
}
.form-control {
    width: 100%;
    padding: 10px;
    background: var(--input-bg);
    border: 1px solid var(--border);
    border-radius: 8px;
    color: var(--text);
}
.form-control:focus {
    outline: none;
    border-color: var(--accent);
}

/* Upload Panel */
.upload-panel {
    position: fixed;
    bottom: 24px;
    right: 24px;
    width: 350px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    z-index: 9999;
    display: none;
    flex-direction: column;
    overflow: hidden;
}
.upload-panel.show {
    display: flex;
}
.upload-panel-header {
    background: var(--accent);
    color: #fff;
    padding: 12px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 500;
}
.upload-panel-header button {
    background: transparent;
    border: none;
    color: #fff;
    cursor: pointer;
    font-size: 18px;
}
.upload-list {
    max-height: 300px;
    overflow-y: auto;
    padding: 12px;
}
.upload-item {
    margin-bottom: 12px;
}
.upload-item:last-child {
    margin-bottom: 0;
}
.upload-item-name {
    font-size: 13px;
    margin-bottom: 6px;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.upload-progress-bg {
    background: var(--input-bg);
    border-radius: 4px;
    height: 6px;
    overflow: hidden;
    position: relative;
}
.upload-progress-bar {
    background: var(--accent);
    height: 100%;
    width: 0%;
    transition: width 0.2s;
}
.upload-status {
    font-size: 11px;
    color: var(--muted);
    margin-top: 4px;
    text-align: right;
}
.upload-status.success { color: #10B981; }
.upload-status.error { color: #EF4444; }

</style>

<div class="drive-container" id="driveContainer">
    <?php if(!empty($_SESSION['success_msg'])): ?>
        <div style="background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); color:#10b981; padding:12px 16px; border-radius:8px; margin-bottom:24px;">
            <?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
        </div>
    <?php endif; ?>
    <?php if(!empty($_SESSION['error_msg'])): ?>
        <div style="background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.2); color:#ef4444; padding:12px 16px; border-radius:8px; margin-bottom:24px;">
            <?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
        </div>
    <?php endif; ?>

    <div class="sticky-top-bar">
        <div class="action-bar" id="actionBar">
            <div><strong id="selectedCount">0</strong> öğe seçildi</div>
            <div class="action-bar-buttons">
                <button class="btn bulk-btn" disabled onclick="openBulkModal('copy_items')">Kopyala</button>
                <button class="btn bulk-btn" disabled onclick="openBulkModal('move_items')">Taşı</button>
                <button class="btn bulk-btn" disabled style="background: rgba(239,68,68,0.8);" onclick="submitBulkDelete()">Sil</button>
                <button class="btn bulk-btn" disabled onclick="clearSelection()">İptal</button>
            </div>
        </div>

        <div class="drive-header">
            <div class="drive-breadcrumbs">
                <?php if (!empty($isSearchOrStarred)): ?>
                    <a href="?">Ana Dizin</a>
                    <span>/</span>
                    <span><?= $search ? 'Arama Sonuçları: ' . View::escape($search) : 'Yıldızlı Dosyalar' ?></span>
                <?php else: ?>
                    <a href="?">Ana Dizin</a>
                    <?php foreach($breadcrumbs as $bc): ?>
                        <span>/</span>
                        <a href="?folder=<?= $bc['id'] ?>"><?= View::escape($bc['name']) ?></a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="drive-actions" style="display:flex; align-items:center; gap:8px;">
                <form method="GET" action="" style="display:flex; margin:0;">
                    <?php if($parentId): ?><input type="hidden" name="folder" value="<?= $parentId ?>"><?php endif; ?>
                    <input type="search" name="q" value="<?= View::escape($search ?? '') ?>" class="form-control" style="width:160px; padding: 6px 12px; font-size:13px; height:34px; border-top-right-radius:0; border-bottom-right-radius:0;" placeholder="Arama yap...">
                    <button type="submit" class="btn btn-outline" style="height:34px; border-left:0; border-top-left-radius:0; border-bottom-left-radius:0; padding:0 8px;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </button>
                </form>

                <a href="?starred=1" class="btn btn-outline <?= !empty($starred) ? 'btn-primary' : '' ?>" style="height: 34px; padding: 6px 10px;" title="Yıldızlı Dosyalar">
                    <svg width="16" height="16" fill="<?= !empty($starred) ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                </a>

                <button class="btn btn-outline" id="btnToggleView" onclick="toggleViewMode()" style="height:34px; padding:6px 10px;" title="Görünümü Değiştir">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                </button>

                <div style="width:1px; height:24px; background:var(--border); margin:0 2px;"></div>

                <select class="form-control" style="width:auto; padding: 6px 10px; font-size: 13px; height: 34px;" onchange="changeSort(this.value)">
                    <option value="name_asc" <?= ($sort ?? 'name_asc') === 'name_asc' ? 'selected' : '' ?>>Ad (A-Z)</option>
                    <option value="name_desc" <?= ($sort ?? '') === 'name_desc' ? 'selected' : '' ?>>Ad (Z-A)</option>
                    <option value="date_desc" <?= ($sort ?? '') === 'date_desc' ? 'selected' : '' ?>>En Yeniler</option>
                    <option value="date_asc" <?= ($sort ?? '') === 'date_asc' ? 'selected' : '' ?>>En Eskiler</option>
                    <option value="size_desc" <?= ($sort ?? '') === 'size_desc' ? 'selected' : '' ?>>Boyut (+)</option>
                    <option value="size_asc" <?= ($sort ?? '') === 'size_asc' ? 'selected' : '' ?>>Boyut (-)</option>
                    <option value="creator_asc" <?= ($sort ?? '') === 'creator_asc' ? 'selected' : '' ?>>Oluşturan (A-Z)</option>
                    <option value="creator_desc" <?= ($sort ?? '') === 'creator_desc' ? 'selected' : '' ?>>Oluşturan (Z-A)</option>
                </select>
                <button class="btn btn-outline" onclick="openModal('folderModal')" style="height: 34px; padding:6px 10px;" title="Yeni Klasör">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg>
                </button>
                <button class="btn btn-primary" onclick="openModal('uploadModal')" style="height: 34px; padding:6px 12px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:4px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    Yükle
                </button>
            </div>
        </div>
    </div>

    <!-- Layout Container -->
    <div class="drive-layout">
        <!-- Main Area -->
        <div class="drive-main">
            <div class="drive-grid" id="driveGrid">
                
                <?php
                $currentSort = $sort ?? 'name_asc';
                $sIcon = function($col) use ($currentSort) {
                    if (strpos($currentSort, $col.'_') === 0) {
                        return strpos($currentSort, 'asc') !== false ? '↑' : '↓';
                    }
                    return '';
                };
                ?>
                <div class="drive-list-header">
                    <div></div> <!-- Star -->
                    <div></div> <!-- Icon -->
                    <div class="sortable-header" onclick="toggleSortColumn('name', '<?= $currentSort ?>')">Ad <span><?= $sIcon('name') ?></span></div>
                    <div class="sortable-header" style="justify-content: flex-end;" onclick="toggleSortColumn('size', '<?= $currentSort ?>')">Boyut <span><?= $sIcon('size') ?></span></div>
                    <div class="sortable-header" onclick="toggleSortColumn('date', '<?= $currentSort ?>')">Tarih <span><?= $sIcon('date') ?></span></div>
                    <div class="sortable-header" onclick="toggleSortColumn('creator', '<?= $currentSort ?>')">Oluşturan <span><?= $sIcon('creator') ?></span></div>
                    <div></div> <!-- Actions -->
                </div>

                <?php if($parentId && $currentFolder): ?>
                    <div class="drive-item" 
                         data-type="folder" 
                         data-id="<?= $currentFolder['parent_id'] ?: 'root' ?>"
                         ondblclick="location.href='?folder=<?= $currentFolder['parent_id'] ?: '' ?>'"
                         ondragover="handleDragOver(event)"
                         ondragleave="handleDragLeave(event)"
                         ondrop="handleDrop(event, '<?= $currentFolder['parent_id'] ?: '' ?>')">
                        <svg class="drive-item-icon drive-item-folder" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M11 17l-5-5m0 0l5-5m-5 5h12"></path></svg>
                        <div class="drive-item-name">.. (Bir üst klasör)</div>
                    </div>
                <?php endif; ?>

        <?php if(empty($items) && !$parentId): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 48px; color: var(--muted);">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:16px;"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                <p>Henüz hiç dosya veya klasör yok.</p>
            </div>
        <?php endif; ?>

        <?php foreach($items as $item): ?>
            <div class="drive-item selectable-item" 
                 data-id="<?= $item['id'] ?>"
                 data-type="<?= $item['type'] ?>"
                 data-name="<?= View::escape($item['name']) ?>"
                 data-bytes="<?= $item['file_size'] ?? 0 ?>"
                 data-size="<?= $item['type'] === 'file' ? formatBytes($item['file_size']) : '-' ?>"
                 data-date="<?= date('d.m.Y H:i', strtotime($item['created_at'])) ?>"
                 data-type-label="<?= $item['type'] === 'folder' ? 'Klasör' : strtoupper(pathinfo($item['name'], PATHINFO_EXTENSION)) . ' Dosyası' ?>"
                 data-creator="<?= View::escape($item['creator_name'] ?? 'Bilinmiyor') ?>"
                 draggable="true"
                 oncontextmenu="handleContextMenu(event, <?= $item['id'] ?>)"
                 ondragstart="handleDragStart(event)"
                 <?= $item['type'] === 'folder' ? 'ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event, '.$item['id'].')"' : '' ?>
                 onclick="toggleSelection(event, <?= $item['id'] ?>)"
                 ondblclick="<?= $item['type'] === 'folder' ? 'location.href=\'?folder='.$item['id'].'\'"' : 'openLightbox(\''.BASE_URL.'/'.$item['file_path'].'\', \''.strtolower(pathinfo($item['name'], PATHINFO_EXTENSION)).'\')' ?>">
                 
                 <div class="drive-item-star <?= !empty($item['is_starred']) ? 'starred' : '' ?>" onclick="toggleStar(event, <?= $item['id'] ?>)" title="Yıldızla">
                    <svg width="16" height="16" fill="<?= !empty($item['is_starred']) ? '#eab308' : 'none' ?>" stroke="<?= !empty($item['is_starred']) ? '#eab308' : 'currentColor' ?>" stroke-width="2" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                 </div>

                <?php if($item['type'] === 'folder'): ?>
                    <svg class="drive-item-icon drive-item-folder" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                <?php else: 
                    $ext = strtolower(pathinfo($item['name'], PATHINFO_EXTENSION));
                    $iconClass = '';
                    if($ext === 'pdf') $iconClass = 'drive-item-pdf';
                    elseif(in_array($ext, ['doc','docx'])) $iconClass = 'drive-item-word';
                    elseif(in_array($ext, ['xls','xlsx','csv'])) $iconClass = 'drive-item-excel';
                    elseif(in_array($ext, ['jpg','jpeg','png','gif'])) $iconClass = 'drive-item-image';
                ?>
                    <svg class="drive-item-icon <?= $iconClass ?>" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                <?php endif; ?>

                <div class="drive-item-name" title="<?= View::escape($item['name']) ?>">
                    <?= View::escape($item['name']) ?>
                </div>
                <div class="drive-item-meta drive-item-size">
                    <?php if($item['type'] === 'file'): ?>
                        <?= formatBytes($item['file_size'] ?? 0) ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </div>
                <div class="drive-item-date">
                    <?= date('d.m.Y H:i', strtotime($item['created_at'])) ?>
                </div>
                <div class="drive-item-creator">
                    <?= View::escape($item['creator_name'] ?? '-') ?>
                </div>

                <!-- İşlemler -->
                <button class="drive-item-actions" onclick="toggleDropdown(event, <?= $item['id'] ?>)">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                </button>
                <div class="dropdown-menu" id="dropdown-<?= $item['id'] ?>">
                    <?php if($item['type'] === 'file'): ?>
                        <a href="<?= BASE_URL ?>/<?= View::escape($item['file_path']) ?>" download="<?= View::escape($item['name']) ?>" class="dropdown-item" onclick="event.stopPropagation();">
                            İndir
                        </a>
                    <?php endif; ?>
                    <button type="button" class="dropdown-item" onclick="event.stopPropagation(); openBulkActionFromMenu('copy_items')">
                        Kopyala
                    </button>
                    <button type="button" class="dropdown-item" onclick="event.stopPropagation(); openBulkActionFromMenu('move_items')">
                        Taşı
                    </button>
                    <button type="button" class="dropdown-item" onclick="openRenameModal(event, <?= $item['id'] ?>, '<?= View::escape($item['name']) ?>')">
                        Yeniden Adlandır
                    </button>
                    <form method="POST" id="singleDeleteForm_<?= $item['id'] ?>" style="display:none;">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="hidden" name="action" value="delete_item">
                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                        <input type="hidden" name="parent_id" value="<?= $parentId ?? '' ?>">
                    </form>
                    <button type="button" class="dropdown-item text-danger" onclick="event.stopPropagation(); openSingleDeleteModal(<?= $item['id'] ?>)">Sil</button>
                </div>
            </div>
        <?php endforeach; ?>
            </div> <!-- End Grid -->
        </div> <!-- End Main -->

        <!-- Details Panel -->
        <div class="drive-details-panel" id="detailsPanel">
            <svg id="dp-icon" class="details-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"></svg>
            <div class="details-title" id="dp-name">Dosya Adı</div>
            
            <div class="details-info-row">
                <div class="details-info-label">Tür</div>
                <div class="details-info-val" id="dp-type">Klasör</div>
            </div>
            <div class="details-info-row">
                <div class="details-info-label">Boyut</div>
                <div class="details-info-val" id="dp-size">1.2 MB</div>
            </div>
            <div class="details-info-row">
                <div class="details-info-label">Oluşturulma</div>
                <div class="details-info-val" id="dp-date">12.06.2026</div>
            </div>
            <div class="details-info-row">
                <div class="details-info-label">Oluşturan</div>
                <div class="details-info-val" id="dp-creator">Bilinmiyor</div>
            </div>
        </div>
    </div> <!-- End Layout -->
</div>

<!-- Yeni Klasör Modal -->
<div class="modal" id="folderModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('folderModal')">&times;</button>
        <h3 class="modal-title">Yeni Klasör</h3>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="action" value="create_folder">
            <input type="hidden" name="parent_id" value="<?= $parentId ?? '' ?>">
            <div class="form-group">
                <label class="form-label">Klasör Adı</label>
                <input type="text" name="name" class="form-control" required autofocus>
            </div>
            <div style="text-align: right;">
                <button type="button" class="btn btn-outline" onclick="closeModal('folderModal')">İptal</button>
                <button type="submit" class="btn btn-primary">Oluştur</button>
            </div>
        </form>
    </div>
</div>

<!-- Dosya Yükle Modal -->
<div class="modal" id="uploadModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('uploadModal')">&times;</button>
        <h3 class="modal-title">Dosya Yükle</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="action" value="upload_file">
            <input type="hidden" name="parent_id" value="<?= $parentId ?? '' ?>">
            <div class="form-group">
                <label class="form-label">Dosya Seçin (Birden fazla seçebilirsiniz)</label>
                <input type="file" id="multiFileInput" class="form-control" multiple required>
                <small style="color:var(--muted); display:block; margin-top:4px;">İzin verilenler: pdf, doc, xls, jpg vb. (Maks 20MB)</small>
            </div>
            <div style="text-align: right;">
                <button type="button" class="btn btn-outline" onclick="closeModal('uploadModal')">İptal</button>
                <button type="button" class="btn btn-primary" onclick="startMultiUpload()">Yükle</button>
            </div>
        </form>
    </div>
</div>

<!-- Yeniden Adlandır Modal -->
<div class="modal" id="renameModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('renameModal')">&times;</button>
        <h3 class="modal-title">Yeniden Adlandır</h3>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="action" value="rename_item">
            <input type="hidden" name="parent_id" value="<?= $parentId ?? '' ?>">
            <input type="hidden" name="item_id" id="renameItemId" value="">
            <div class="form-group">
                <label class="form-label">Yeni Ad</label>
                <input type="text" name="new_name" id="renameItemName" class="form-control" required>
            </div>
            <div style="text-align: right;">
                <button type="button" class="btn btn-outline" onclick="closeModal('renameModal')">İptal</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>
        </form>
    </div>
</div>

<!-- Toplu İşlem Modalı (Taşı/Kopyala) -->
<div class="modal" id="bulkActionModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('bulkActionModal')">&times;</button>
        <h3 class="modal-title" id="bulkActionTitle">Hedef Seç</h3>
        <form method="POST" id="bulkActionForm">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="action" id="bulkActionInput" value="">
            <input type="hidden" name="parent_id" value="<?= $parentId ?? '' ?>">
            <input type="hidden" name="item_ids" id="bulkItemIds" value="">
            
            <div class="form-group">
                <label class="form-label">Hedef Klasör</label>
                <select name="target_parent_id" class="form-control">
                    <option value="">-- Ana Dizin --</option>
                    <?php foreach($allFolders ?? [] as $f): ?>
                        <option value="<?= $f['id'] ?>"><?= View::escape($f['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="text-align: right;">
                <button type="button" class="btn btn-outline" onclick="closeModal('bulkActionModal')">İptal</button>
                <button type="submit" class="btn btn-primary">Uygula</button>
            </div>
        </form>
    </div>
</div>

<!-- Silme Onay Modalı -->
<div class="modal" id="deleteConfirmModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('deleteConfirmModal')">&times;</button>
        <h3 class="modal-title">Silme Onayı</h3>
        <p id="deleteConfirmMessage">Bu öğeyi kalıcı olarak silmek istediğinize emin misiniz?</p>
        <div style="text-align: right; margin-top: 16px;">
            <button type="button" class="btn btn-outline" onclick="closeModal('deleteConfirmModal')">İptal</button>
            <button type="button" class="btn btn-primary" style="background: #EF4444; border-color: #EF4444;" onclick="confirmDeleteAction()">Sil</button>
        </div>
    </div>
</div>

<!-- Lightbox Modal (Dosya Önizleme) -->
<div class="modal" id="lightboxModal" style="background:rgba(0,0,0,0.9); z-index: 10000;" onclick="closeModal('lightboxModal')">
    <button class="modal-close" style="color:#fff; right: 24px; top: 24px; font-size: 32px;" onclick="closeModal('lightboxModal')">&times;</button>
    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; padding: 48px; box-sizing: border-box;">
        <img id="lightboxImg" src="" style="max-width: 100%; max-height: 100%; object-fit: contain; display: none; border-radius: 8px;">
        <iframe id="lightboxIframe" src="" style="width: 100%; height: 100%; border: none; display: none; background: #fff; border-radius: 8px;"></iframe>
    </div>
</div>

<!-- Gizli Form (Sürükle bırak için) -->
<form method="POST" id="dragDropForm" style="display:none;">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <input type="hidden" name="action" value="move_items">
    <input type="hidden" name="parent_id" value="<?= $parentId ?? '' ?>">
    <input type="hidden" name="item_ids" id="dragItemIds" value="">
    <input type="hidden" name="target_parent_id" id="dragTargetParent" value="">
</form>

<!-- Gizli Form (Toplu Silme için) -->
<form method="POST" id="bulkDeleteForm" style="display:none;">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <input type="hidden" name="action" value="bulk_delete">
    <input type="hidden" name="parent_id" value="<?= $parentId ?? '' ?>">
    <input type="hidden" name="item_ids" id="deleteItemIds" value="">
</form>

<!-- Yükleme Paneli -->
<div class="upload-panel" id="uploadPanel">
    <div class="upload-panel-header">
        <span>Yüklemeler</span>
        <button onclick="document.getElementById('uploadPanel').classList.remove('show'); window.location.reload();">&times;</button>
    </div>
    <div class="upload-list" id="uploadList">
        <!-- JS ile doldurulacak -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('driveViewMode') === 'list') {
        document.getElementById('driveGrid').classList.add('list-view');
    }
});

function toggleViewMode() {
    const grid = document.getElementById('driveGrid');
    const isList = grid.classList.toggle('list-view');
    localStorage.setItem('driveViewMode', isList ? 'list' : 'grid');
}

function toggleStar(e, id) {
    e.stopPropagation(); // prevent selection
    const formData = new FormData();
    formData.append('action', 'toggle_star');
    formData.append('item_id', id);
    formData.append('is_ajax', '1');

    fetch('', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        if(data.status === 'success') {
            const svg = e.currentTarget.querySelector('svg');
            if(data.is_starred) {
                svg.setAttribute('fill', '#eab308');
                svg.setAttribute('stroke', '#eab308');
                e.currentTarget.classList.add('starred');
            } else {
                svg.setAttribute('fill', 'none');
                svg.setAttribute('stroke', 'currentColor');
                e.currentTarget.classList.remove('starred');
            }
        }
    });
}

function changeSort(val) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', val);
    window.location.href = url.toString();
}

function toggleSortColumn(col, currentSort) {
    let newSort = col + '_asc';
    if (currentSort === col + '_asc') {
        newSort = col + '_desc';
    } else if (currentSort === col + '_desc') {
        newSort = col + '_asc';
    } else {
        if (col === 'date' || col === 'size') {
            newSort = col + '_desc';
        }
    }
    changeSort(newSort);
}

function formatBytesJS(bytes, decimals = 2) {
    if (!bytes || bytes === 0) return '0 B';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

// --- Seçim İşlemleri ---
let selectedIds = new Set();
let lastSelectedIndex = -1;
let deleteMode = null;
let deleteTargetId = null;

function toggleSelection(e, id) {
    if (e.target.closest('.drive-item-actions') || e.target.closest('.dropdown-menu')) return;
    
    const allItems = Array.from(document.querySelectorAll('.selectable-item'));
    const item = document.querySelector(`.selectable-item[data-id="${id}"]`);
    const currentIndex = allItems.indexOf(item);
    
    if (e.shiftKey && lastSelectedIndex !== -1) {
        document.getSelection().removeAllRanges(); 
        
        const start = Math.min(lastSelectedIndex, currentIndex);
        const end = Math.max(lastSelectedIndex, currentIndex);
        
        if (!e.ctrlKey && !e.metaKey) {
            selectedIds.clear();
            allItems.forEach(el => el.classList.remove('selected'));
        }
        
        for (let i = start; i <= end; i++) {
            const currentItem = allItems[i];
            const currentId = parseInt(currentItem.getAttribute('data-id'));
            selectedIds.add(currentId);
            currentItem.classList.add('selected');
        }
    } else {
        if (!e.ctrlKey && !e.metaKey) {
            if (selectedIds.size === 1 && selectedIds.has(id)) {
                selectedIds.clear();
                item.classList.remove('selected');
                lastSelectedIndex = -1;
            } else {
                selectedIds.clear();
                allItems.forEach(el => el.classList.remove('selected'));
                selectedIds.add(id);
                item.classList.add('selected');
                lastSelectedIndex = currentIndex;
            }
        } else {
            if (selectedIds.has(id)) {
                selectedIds.delete(id);
                item.classList.remove('selected');
            } else {
                selectedIds.add(id);
                item.classList.add('selected');
            }
            lastSelectedIndex = currentIndex;
        }
    }
    
    updateActionBar();
}

function selectAll() {
    selectedIds.clear();
    document.querySelectorAll('.selectable-item').forEach(el => {
        const id = parseInt(el.getAttribute('data-id'));
        selectedIds.add(id);
        el.classList.add('selected');
    });
    updateActionBar();
}

function clearSelection() {
    selectedIds.clear();
    document.querySelectorAll('.selectable-item').forEach(el => el.classList.remove('selected'));
    updateActionBar();
}

function updateActionBar() {
    const count = document.getElementById('selectedCount');
    count.innerText = selectedIds.size;
    
    const buttons = document.querySelectorAll('.bulk-btn');
    if (selectedIds.size > 0) {
        buttons.forEach(btn => btn.disabled = false);
    } else {
        buttons.forEach(btn => btn.disabled = true);
    }

    // Details Panel Update
    const dp = document.getElementById('detailsPanel');
    if (selectedIds.size === 1) {
        const id = Array.from(selectedIds)[0];
        const item = document.querySelector(`.selectable-item[data-id="${id}"]`);
        if (item) {
            document.getElementById('dp-name').innerText = item.getAttribute('data-name');
            document.getElementById('dp-type').innerText = item.getAttribute('data-type-label');
            document.getElementById('dp-size').innerText = item.getAttribute('data-size');
            document.getElementById('dp-date').innerText = item.getAttribute('data-date');
            document.getElementById('dp-creator').innerText = item.getAttribute('data-creator');
            
            // Icon copy
            const iconSvg = item.querySelector('.drive-item-icon');
            if (iconSvg) {
                document.getElementById('dp-icon').innerHTML = iconSvg.innerHTML;
                document.getElementById('dp-icon').className = 'details-icon ' + iconSvg.classList[1]; // color class
            }
            dp.classList.add('show');
        }
    } else if (selectedIds.size > 1) {
        let totalBytes = 0;
        let folderCount = 0;
        let fileCount = 0;
        let creators = new Set();
        
        selectedIds.forEach(id => {
            const item = document.querySelector(`.selectable-item[data-id="${id}"]`);
            if (item) {
                totalBytes += parseInt(item.getAttribute('data-bytes') || 0);
                if (item.getAttribute('data-type') === 'folder') {
                    folderCount++;
                } else {
                    fileCount++;
                }
                const creator = item.getAttribute('data-creator');
                if (creator && creator !== '-') creators.add(creator);
            }
        });
        
        document.getElementById('dp-name').innerText = `${selectedIds.size} öğe seçildi`;
        
        let typeStrs = [];
        if (folderCount > 0) typeStrs.push(`${folderCount} Klasör`);
        if (fileCount > 0) typeStrs.push(`${fileCount} Dosya`);
        document.getElementById('dp-type').innerText = typeStrs.join(', ');
        
        document.getElementById('dp-size').innerText = fileCount > 0 ? formatBytesJS(totalBytes) : '-';
        document.getElementById('dp-date').innerText = '-';
        document.getElementById('dp-creator').innerText = creators.size === 1 ? Array.from(creators)[0] : '-';
        
        document.getElementById('dp-icon').innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>`;
        document.getElementById('dp-icon').className = 'details-icon text-muted';
        
        dp.classList.add('show');
    } else {
        dp.classList.remove('show');
    }
}

// --- Kısayollar ve Pano (Clipboard) İşlemleri ---
let clipboard = null;

document.addEventListener('keydown', function(e) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
        return;
    }

    if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
        e.preventDefault();
        selectAll();
    }
    
    if ((e.ctrlKey || e.metaKey) && e.key === 'c') {
        if (selectedIds.size > 0) {
            e.preventDefault();
            clipboard = { action: 'copy_items', itemIds: Array.from(selectedIds) };
            showToast(selectedIds.size + " öğe kopyalandı.");
        }
    }
    
    if ((e.ctrlKey || e.metaKey) && e.key === 'x') {
        if (selectedIds.size > 0) {
            e.preventDefault();
            clipboard = { action: 'move_items', itemIds: Array.from(selectedIds) };
            showToast(selectedIds.size + " öğe kesildi.");
        }
    }
    
    if ((e.ctrlKey || e.metaKey) && e.key === 'v') {
        if (clipboard && clipboard.itemIds.length > 0) {
            e.preventDefault();
            document.getElementById('dragItemIds').value = clipboard.itemIds.join(',');
            document.getElementById('dragTargetParent').value = '<?= $parentId ?? '' ?>';
            
            const form = document.getElementById('dragDropForm');
            form.querySelector('input[name="action"]').value = clipboard.action;
            form.submit();
        }
    }
    
    if (e.key === 'Delete' || e.key === 'Backspace') {
        if (selectedIds.size > 0) {
            e.preventDefault();
            submitBulkDelete();
        }
    }
    
    if (e.key === 'F2') {
        if (selectedIds.size === 1) {
            e.preventDefault();
            const id = Array.from(selectedIds)[0];
            const item = document.querySelector(`.selectable-item[data-id="${id}"]`);
            if (item) {
                const name = item.querySelector('.drive-item-name').innerText.trim();
                openRenameModal(e, id, name);
            }
        }
    }

    if (e.key === 'Escape') {
        clearSelection();
        closeAllDropdowns();
    }
});

// --- Silme İşlemleri ---
function submitBulkDelete() {
    if(selectedIds.size === 0) return;
    deleteMode = 'bulk';
    document.getElementById('deleteConfirmMessage').innerText = selectedIds.size + ' öğeyi kalıcı olarak silmek istediğinize emin misiniz?';
    openModal('deleteConfirmModal');
}

function openSingleDeleteModal(id) {
    closeAllDropdowns();
    deleteMode = 'single';
    deleteTargetId = id;
    document.getElementById('deleteConfirmMessage').innerText = 'Bu öğeyi kalıcı olarak silmek istediğinize emin misiniz?';
    openModal('deleteConfirmModal');
}

function confirmDeleteAction() {
    if (deleteMode === 'bulk') {
        document.getElementById('deleteItemIds').value = Array.from(selectedIds).join(',');
        document.getElementById('bulkDeleteForm').submit();
    } else if (deleteMode === 'single') {
        const form = document.getElementById('singleDeleteForm_' + deleteTargetId);
        if (form) form.submit();
    }
}

// --- Yeniden Adlandırma (F2) ---
function openRenameModal(e, id, name) {
    if (e) {
        e.stopPropagation();
        e.preventDefault();
    }
    closeAllDropdowns();
    document.getElementById('renameItemId').value = id;
    document.getElementById('renameItemName').value = name;
    openModal('renameModal');
}

// --- Toast Bildirimleri ---
function showToast(msg) {
    const t = document.createElement('div');
    t.style.cssText = 'position:fixed; bottom:24px; left:50%; transform:translateX(-50%); background:var(--text); color:var(--card); padding:12px 24px; border-radius:8px; z-index:9999; box-shadow:0 4px 12px rgba(0,0,0,0.15); font-size:14px; opacity:0; transition:opacity 0.3s;';
    t.innerText = msg;
    document.body.appendChild(t);
    setTimeout(() => { t.style.opacity = '1'; }, 10);
    setTimeout(() => { 
        t.style.opacity = '0'; 
        setTimeout(() => t.remove(), 300);
    }, 3000);
}

// --- Dosya Önizleme (Lightbox) ---
function openLightbox(url, ext) {
    document.getElementById('lightboxImg').style.display = 'none';
    document.getElementById('lightboxIframe').style.display = 'none';
    
    if (['jpg','jpeg','png','gif','webp', 'svg'].includes(ext)) {
        document.getElementById('lightboxImg').src = url;
        document.getElementById('lightboxImg').style.display = 'block';
    } else if (['pdf','txt'].includes(ext)) {
        document.getElementById('lightboxIframe').src = url;
        document.getElementById('lightboxIframe').style.display = 'block';
    } else {
        window.open(url, '_blank');
        return;
    }
    openModal('lightboxModal');
}

// --- Masaüstünden Sürükle-Bırak ---
const driveContainer = document.getElementById('driveGrid') || document.body;
const dropZone = document.createElement('div');
dropZone.id = 'desktopDropZone';
dropZone.style.cssText = 'position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); color:#fff; display:none; align-items:center; justify-content:center; z-index:10000; font-size:32px; font-weight:bold; backdrop-filter:blur(4px); transition: opacity 0.2s; opacity: 0; pointer-events: none;';
dropZone.innerText = 'Dosyaları Buraya Bırakın';
document.body.appendChild(dropZone);

let dragCounter = 0;
document.addEventListener('dragenter', (e) => {
    if (e.dataTransfer.types && e.dataTransfer.types.includes('Files')) {
        e.preventDefault();
        dragCounter++;
        dropZone.style.display = 'flex';
        setTimeout(() => {
            dropZone.style.opacity = '1';
            dropZone.style.pointerEvents = 'auto';
        }, 10);
    }
});
document.addEventListener('dragleave', (e) => {
    dragCounter--;
    if (dragCounter === 0) {
        dropZone.style.opacity = '0';
        dropZone.style.pointerEvents = 'none';
        setTimeout(() => dropZone.style.display = 'none', 200);
    }
});
document.addEventListener('dragover', e => { e.preventDefault(); });
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dragCounter = 0;
    dropZone.style.opacity = '0';
    dropZone.style.pointerEvents = 'none';
    setTimeout(() => dropZone.style.display = 'none', 200);
    
    const files = e.dataTransfer.files;
    if (files && files.length > 0) {
        document.getElementById('multiFileInput').files = files;
        startMultiUpload();
    }
});

// --- Sürükle / Bırak (Mevcut Öğeleri Taşıma) ---
function openBulkModal(actionType) {
    if(selectedIds.size === 0) return;
    document.getElementById('bulkActionInput').value = actionType;
    document.getElementById('bulkItemIds').value = Array.from(selectedIds).join(',');
    document.getElementById('bulkActionTitle').innerText = actionType === 'move_items' ? 'Taşı' : 'Kopyala';
    openModal('bulkActionModal');
}

function openBulkActionFromMenu(actionType) {
    closeAllDropdowns();
    openBulkModal(actionType);
}

// --- Sürükle Bırak İşlemleri ---
function handleDragStart(e) {
    const id = parseInt(e.currentTarget.getAttribute('data-id'));
    let ids = Array.from(selectedIds);
    if(!selectedIds.has(id)) {
        ids = [id];
    }
    e.dataTransfer.setData('text/plain', ids.join(','));
    e.dataTransfer.effectAllowed = 'move';
}

function handleDragOver(e) {
    e.preventDefault();
    e.currentTarget.classList.add('drag-over');
    e.dataTransfer.dropEffect = 'move';
}

function handleDragLeave(e) {
    e.currentTarget.classList.remove('drag-over');
}

function handleDrop(e, targetId) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');
    const ids = e.dataTransfer.getData('text/plain');
    if (!ids) return;

    // Eğer targetId "root" ise ana dizine taşı, yani boş string gönder
    let target = targetId === 'root' ? '' : targetId;

    document.getElementById('dragItemIds').value = ids;
    document.getElementById('dragTargetParent').value = target;
    document.getElementById('dragDropForm').submit();
}

// --- Mevcut Modal/Dropdown İşlemleri ---
function openModal(id) {
    document.getElementById(id).classList.add('show');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('show');
}
function openRenameModal(e, id, currentName) {
    e.stopPropagation();
    document.getElementById('renameItemId').value = id;
    document.getElementById('renameItemName').value = currentName;
    openModal('renameModal');
    closeAllDropdowns();
}

function positionMenu(menu, x, y) {
    menu.style.left = x + 'px';
    menu.style.top = y + 'px';
    
    // Menünün ekrandan taşmasını engelle
    requestAnimationFrame(() => {
        const rect = menu.getBoundingClientRect();
        if (rect.right > window.innerWidth) {
            menu.style.left = (window.innerWidth - rect.width - 10) + 'px';
        }
        if (rect.bottom > window.innerHeight) {
            menu.style.top = (window.innerHeight - rect.height - 10) + 'px';
        }
    });
}

function handleContextMenu(e, id) {
    e.preventDefault();
    e.stopPropagation();
    
    // Sağ tıklanan öğe önceden seçili değilse, sadece bu öğeyi seç
    if (!selectedIds.has(id)) {
        clearSelection();
        const item = document.querySelector(`.selectable-item[data-id="${id}"]`);
        selectedIds.add(id);
        item.classList.add('selected');
        updateActionBar();
    }
    
    closeAllDropdowns();
    const menu = document.getElementById('dropdown-' + id);
    menu.classList.add('show');
    positionMenu(menu, e.clientX, e.clientY);
}

function toggleDropdown(e, id) {
    e.stopPropagation();
    e.preventDefault();
    const btn = e.currentTarget;
    const rect = btn.getBoundingClientRect();
    
    const wasOpen = document.getElementById('dropdown-' + id).classList.contains('show');
    closeAllDropdowns();
    
    if (!wasOpen) {
        const menu = document.getElementById('dropdown-' + id);
        menu.classList.add('show');
        positionMenu(menu, rect.right - 150, rect.bottom + 5);
    }
}

function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.classList.remove('show');
    });
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.selectable-item')) {
        closeAllDropdowns();
    }
});
document.addEventListener('contextmenu', function(e) {
    if (!e.target.closest('.selectable-item')) {
        closeAllDropdowns();
    }
});

// --- Çoklu Dosya Yükleme (AJAX) ---
function startMultiUpload() {
    const input = document.getElementById('multiFileInput');
    if(input.files.length === 0) return;

    closeModal('uploadModal');
    
    const panel = document.getElementById('uploadPanel');
    const list = document.getElementById('uploadList');
    panel.classList.add('show');
    
    let filesArray = Array.from(input.files);
    let completedCount = 0;

    filesArray.forEach((file, index) => {
        // UI oluştur
        const id = 'upload_item_' + Date.now() + '_' + index;
        const div = document.createElement('div');
        div.className = 'upload-item';
        div.innerHTML = `
            <div class="upload-item-name" title="${file.name}">${file.name}</div>
            <div class="upload-progress-bg">
                <div class="upload-progress-bar" id="${id}_bar"></div>
            </div>
            <div class="upload-status" id="${id}_status">Yükleniyor 0%</div>
        `;
        list.appendChild(div);

        // AJAX Gönderimi
        const formData = new FormData();
        formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?? '' ?>');
        formData.append('action', 'upload_file');
        formData.append('parent_id', '<?= $parentId ?? '' ?>');
        formData.append('is_ajax', '1');
        formData.append('file', file);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', window.location.href, true);

        // Upload Progress olayı
        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                document.getElementById(`${id}_bar`).style.width = percent + '%';
                document.getElementById(`${id}_status`).innerText = 'Yükleniyor ' + percent + '%';
            }
        };

        xhr.onload = function() {
            completedCount++;
            if (xhr.status === 200) {
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (res.status === 'success') {
                        document.getElementById(`${id}_bar`).style.background = '#10B981';
                        document.getElementById(`${id}_status`).innerText = 'Tamamlandı';
                        document.getElementById(`${id}_status`).classList.add('success');
                    } else {
                        document.getElementById(`${id}_bar`).style.background = '#EF4444';
                        document.getElementById(`${id}_status`).innerText = res.message || 'Hata';
                        document.getElementById(`${id}_status`).classList.add('error');
                    }
                } catch(err) {
                    document.getElementById(`${id}_bar`).style.background = '#EF4444';
                    document.getElementById(`${id}_status`).innerText = 'Sunucu Hatası';
                    document.getElementById(`${id}_status`).classList.add('error');
                }
            } else {
                document.getElementById(`${id}_bar`).style.background = '#EF4444';
                document.getElementById(`${id}_status`).innerText = 'Hata (' + xhr.status + ')';
                document.getElementById(`${id}_status`).classList.add('error');
            }

            // Bütün dosyalar bittiyse paneli kapatmadan önce listeyi yenile
            if(completedCount === filesArray.length) {
                setTimeout(() => {
                    window.location.reload();
                }, 2000); // 2 saniye sonra yenile ki kullanıcı sonuçları görsün
            }
        };

        xhr.onerror = function() {
            completedCount++;
            document.getElementById(`${id}_bar`).style.background = '#EF4444';
            document.getElementById(`${id}_status`).innerText = 'Ağ Hatası';
            document.getElementById(`${id}_status`).classList.add('error');
            if(completedCount === filesArray.length) {
                setTimeout(() => { window.location.reload(); }, 2000);
            }
        };

        xhr.send(formData);
    });

    // Inputu temizle
    input.value = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        selectedIds.clear();
        document.querySelectorAll('.selectable-item').forEach(el => el.classList.remove('selected'));
        lastSelectedIndex = -1;
        updateActionBar();
    }
});
</script>
