<style>
/* Layout */
.app-assign-container {
    display: block;
}
.dept-sidebar {
    width: 100%;
    background: var(--card);
    border: 1px solid var(--border, #1e1e1e);
    border-radius: 12px;
    padding: 16px;
}
.dept-sidebar h3 {
    font-size: 14px;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0 0 16px 0;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border);
}
.dept-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 10px;
}
.dept-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    color: var(--text);
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 4px;
    transition: all 0.2s;
    border: 1px solid transparent;
}
.dept-link:hover {
    background: rgba(255,255,255,0.02);
}
.dept-link.active {
    background: rgba(var(--accent-rgb, 0,188,212), 0.1);
    color: var(--accent);
    border-color: rgba(var(--accent-rgb, 0,188,212), 0.3);
}

.main-content {
    flex: 1;
    background: var(--card);
    border: 1px solid var(--border, #1e1e1e);
    border-radius: 12px;
    padding: 32px;
}
.empty-selection {
    text-align: center;
    padding: 80px 20px;
    color: var(--muted);
}
.empty-selection svg { opacity: 0.2; margin-bottom: 16px; }
.empty-selection h2 { font-size: 18px; color: var(--text); margin-bottom: 8px; }

.assign-header {
    margin-bottom: 32px;
}
.assign-header h2 { font-size: 22px; font-weight: 600; margin: 0 0 8px 0; }
.assign-header p { color: var(--muted); font-size: 14px; margin: 0; }

.modules-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 32px;
}

.mod-card {
    background: var(--bg);
    border: 2px solid var(--border);
    border-radius: 12px;
    padding: 24px 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
    user-select: none;
}
.mod-card:hover {
    border-color: #3b82f6;
    background: rgba(59,130,246,0.02);
}
.mod-card.selected {
    border-color: #10b981;
    background: rgba(16,185,129,0.05);
}
.mod-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: var(--card);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    color: var(--muted);
    transition: all 0.2s;
}
.mod-card.selected .mod-icon {
    background: #10b981;
    color: #fff;
    box-shadow: 0 4px 12px rgba(16,185,129,0.3);
}
.mod-card:hover:not(.selected) .mod-icon {
    color: #3b82f6;
}
.mod-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--text);
}
.mod-checkbox {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 20px;
    height: 20px;
    border-radius: 6px;
    border: 2px solid var(--border);
    background: var(--bg);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    pointer-events: none; /* Let card handle click */
}
.mod-card.selected .mod-checkbox {
    border-color: #10b981;
    background: #10b981;
}
.mod-checkbox svg {
    color: #fff;
    opacity: 0;
    transition: opacity 0.2s;
}
.mod-card.selected .mod-checkbox svg {
    opacity: 1;
}
.mod-sidebar-tools {
    display: grid;
    gap: 6px;
    margin-top: 8px;
}
.mod-sidebar-row {
    display: grid;
    grid-template-columns: 1fr 58px;
    gap: 6px;
}
.mod-sidebar-tools select,
.mod-sidebar-tools input {
    width: 100%;
    min-width: 0;
    background: var(--input-bg);
    border: 1px solid var(--border);
    border-radius: 7px;
    color: var(--text);
    font-size: 11px;
    padding: 6px;
}
.mod-primary-toggle {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    min-height: 28px;
    border: 1px solid rgba(16,185,129,0.28);
    border-radius: 7px;
    color: #10b981;
    font-size: 10px;
    font-weight: 700;
    background: rgba(16,185,129,0.055);
}
.mod-primary-toggle input {
    width: 13px;
    height: 13px;
    accent-color: #10b981;
}

.save-bar {
    border-top: 1px solid var(--border);
    padding-top: 24px;
    display: flex;
    justify-content: flex-end;
}
.apps-assign-modal {
    position: fixed;
    inset: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 18px;
    background: var(--modal-overlay, rgba(0,0,0,0.85));
    backdrop-filter: blur(4px);
}
.apps-assign-dialog {
    width: min(980px, 100%);
    max-height: min(88vh, 760px);
    overflow: hidden;
    border: 1px solid var(--border);
    border-radius: 16px;
    background: var(--card);
    box-shadow: 0 18px 48px rgba(0,0,0,0.35);
}
.apps-assign-dialog__header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 14px;
    padding: 18px 20px;
    border-bottom: 1px solid var(--border);
}
.apps-assign-dialog__header .assign-header {
    margin: 0;
}
.apps-assign-close {
    display: inline-flex;
    flex: 0 0 auto;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--muted);
    text-decoration: none;
}
.apps-assign-close:hover {
    color: var(--text);
    border-color: var(--accent);
}
.apps-assign-dialog__body {
    max-height: calc(min(88vh, 760px) - 78px);
    overflow-y: auto;
    padding: 18px 20px 20px;
}

@media (max-width: 700px) {
    .apps-page-header {
        margin-bottom: 10px;
    }

    .apps-page-header h2 {
        font-size: 18px;
    }

    .apps-page-header p {
        display: none;
    }

    .dept-sidebar {
        border: 0;
        border-radius: 0;
        background: transparent;
        padding: 0 !important;
        overflow: visible;
    }

    .dept-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .dept-sidebar h3 {
        display: none;
    }

    .dept-link {
        min-height: 54px;
        min-width: 0;
        margin: 0;
        padding: 10px;
        border-color: rgba(255,255,255,0.055);
        border-radius: 10px;
        background: rgba(255,255,255,0.025);
        font-size: 12px;
        line-height: 1.15;
        white-space: normal;
    }

    .dept-link span {
        min-width: 0;
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .dept-link svg {
        display: none;
    }

    .dept-link.active {
        background: rgba(0,188,212,0.18);
        border-color: rgba(0,188,212,0.34);
        box-shadow: none;
    }

    .main-content {
        border: 0;
        border-radius: 0;
        background: transparent;
        padding: 0 !important;
    }

    .empty-selection {
        margin-top: 10px;
        padding: 28px 14px;
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 12px;
        background: rgba(255,255,255,0.018);
    }

    .empty-selection svg {
        width: 34px;
        height: 34px;
        margin-bottom: 10px;
    }

    .empty-selection h2 {
        font-size: 14px;
        margin-bottom: 4px;
    }

    .empty-selection p {
        font-size: 11px;
    }

    .apps-assign-modal {
        align-items: flex-end;
        padding: 8px;
    }

    .apps-assign-dialog {
        max-height: calc(100dvh - 18px);
        border-radius: 15px 15px 10px 10px;
        box-shadow: none;
    }

    .apps-assign-dialog__header {
        align-items: center;
        padding: 12px;
    }

    .apps-assign-close {
        width: 34px;
        height: 34px;
        border-radius: 9px;
    }

    .apps-assign-dialog__body {
        max-height: calc(100dvh - 92px);
        padding: 10px 12px 12px;
    }

    .assign-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
    }

    .assign-header h2 {
        min-width: 0;
        margin: 0;
        overflow: hidden;
        font-size: 15px;
        line-height: 1.2;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .assign-header p {
        display: none;
    }

    .modules-grid {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 6px;
        margin-bottom: 10px;
        padding: 0 !important;
    }

    .mod-card {
        display: grid;
        grid-template-columns: 38px minmax(0, 1fr);
        column-gap: 8px;
        align-items: center;
        min-height: 62px;
        padding: 8px 26px 8px 8px;
        border-width: 1px;
        border-radius: 9px;
        background: rgba(255,255,255,0.018);
        text-align: left;
    }

    .mod-card:hover {
        background: rgba(255,255,255,0.025);
        transform: none;
    }

    .mod-card.selected {
        border-color: rgba(16,185,129,0.45);
        background: rgba(16,185,129,0.08);
    }

    .mod-icon {
        width: 34px;
        height: 34px;
        margin: 0;
        border-radius: 9px;
    }

    .mod-icon svg {
        width: 21px;
        height: 21px;
    }

    .mod-checkbox {
        top: 6px;
        right: 6px;
        width: 16px;
        height: 16px;
        border-width: 1px;
        border-radius: 5px;
    }

    .mod-checkbox svg {
        width: 10px;
        height: 10px;
    }

    .mod-name,
    .mod-inputs input {
        overflow-wrap: anywhere;
    }

    .mod-name {
        display: none;
    }

    .mod-inputs {
        margin-top: 0 !important;
        min-width: 0;
    }

    .mod-inputs input[type="text"] {
        min-height: 20px !important;
        margin-bottom: 3px !important;
        padding: 0 !important;
        border: 0 !important;
        border-radius: 0 !important;
        background: transparent !important;
        font-size: 10px !important;
        font-weight: 700;
        text-align: left !important;
        box-shadow: none !important;
    }

    .mod-inputs > div {
        justify-content: flex-start !important;
        gap: 4px !important;
        width: max-content;
        max-width: 100%;
        padding: 2px 5px;
        border: 1px solid rgba(255,255,255,0.055);
        border-radius: 999px;
        background: rgba(255,255,255,0.025);
    }

    .mod-inputs > div span {
        font-size: 8px !important;
        line-height: 1;
    }

    .mod-inputs input[type="number"] {
        width: 34px !important;
        min-height: 18px;
        padding: 0 !important;
        border: 0 !important;
        border-radius: 0 !important;
        background: transparent !important;
        font-size: 9px !important;
        box-shadow: none !important;
    }

    .mod-sidebar-tools {
        grid-column: 1 / -1;
        grid-template-columns: minmax(0, 1fr);
        gap: 4px;
        margin-top: 6px;
    }

    .mod-sidebar-row {
        grid-template-columns: minmax(0, 1fr) 42px;
        gap: 4px;
    }

    .mod-sidebar-tools select,
    .mod-sidebar-tools input {
        min-height: 24px;
        border-radius: 6px;
        font-size: 9px;
        padding: 3px 5px;
    }

    .mod-primary-toggle {
        min-height: 24px;
        border-radius: 6px;
        font-size: 9px;
    }

    .save-bar {
        align-items: stretch;
        flex-direction: column;
        position: sticky;
        bottom: 0;
        padding-top: 10px;
        border-top-color: rgba(255,255,255,0.055);
        background: var(--card);
    }

    .save-bar .btn {
        width: 100%;
        min-height: 36px;
        padding: 8px 10px !important;
        border-radius: 9px;
        font-size: 11px !important;
        box-shadow: none;
    }
}
</style>

<div class="page-header apps-page-header">
    <div>
        <h2>Uygulama Ata</h2>
        <p style="color:var(--muted); font-size:13px; margin-top:4px;">Departmanlara çekirdek modülleri tanımlayın.</p>
    </div>
</div>

<div class="app-assign-container">
    
    <!-- Sol: Departmanlar -->
    <div class="dept-sidebar">
        <h3>Departmanlar</h3>
        <div class="dept-list">
            <?php foreach ($departments as $dept): ?>
                <a href="?dept_id=<?= $dept['id'] ?>" class="dept-link <?= $selectedDeptId === $dept['id'] ? 'active' : '' ?>">
                    <span><?= View::escape($dept['name']) ?></span>
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="main-content">
        <div class="empty-selection">
            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16m-7 6h7"/></svg>
            <h2>Departman Seçin</h2>
            <p>Uygulama ataması yapmak için bir departman kartına dokunun.</p>
        </div>
    </div>
</div>

<?php if ($selectedDept): ?>
    <div class="apps-assign-modal" id="appsAssignModal" role="dialog" aria-modal="true" aria-labelledby="appsAssignTitle">
        <div class="apps-assign-dialog">
            <div class="apps-assign-dialog__header">
            <div class="assign-header">
                    <h2 id="appsAssignTitle"><?= View::escape($selectedDept['name']) ?> Uygulamaları</h2>
                <p>Bu departmanın sidebar'ında görünecek uygulamaları seçin.</p>
            </div>
                <a href="<?= BASE_URL ?>/superadmin/apps" class="apps-assign-close" aria-label="Kapat">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </a>
            </div>

            <div class="apps-assign-dialog__body">
            <form method="POST" action="<?= BASE_URL ?>/superadmin/apps/assign">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="department_id" value="<?= $selectedDept['id'] ?>">

                <div class="modules-grid">
                    <?php foreach ($coreModules as $type => $info): ?>
                        <?php $isSelected = in_array($type, $assignedModules); ?>
                        <div class="mod-card <?= $isSelected ? 'selected' : '' ?>" onclick="toggleModule(this, '<?= $type ?>')">
                            <div class="mod-checkbox">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                            </div>
                            <div class="mod-icon">
                                <?php if (in_array($info['icon'], ['form', 'file-text'])): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                <?php elseif ($info['icon'] === 'calendar'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <?php elseif ($info['icon'] === 'check-square'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                <?php elseif ($info['icon'] === 'megaphone'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 17H2a3 3 0 000 3h20a3 3 0 000-3zM22 11H2M22 5H2"/></svg>
                                <?php elseif ($info['icon'] === 'volume-2'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M19.07 4.93a10 10 0 010 14.14M15.54 8.46a5 5 0 010 7.07"/></svg>
                                <?php elseif ($info['icon'] === 'bar-chart'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                                <?php elseif ($info['icon'] === 'users'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                                <?php elseif ($info['icon'] === 'pie-chart'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                                <?php elseif ($info['icon'] === 'credit-card'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                                <?php elseif ($info['icon'] === 'book'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                <?php elseif ($info['icon'] === 'briefcase'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                <?php elseif ($info['icon'] === 'map-pin'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                <?php elseif ($info['icon'] === 'book-open'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                                <?php elseif ($info['icon'] === 'award'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"></circle><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"></path></svg>
                                <?php elseif ($info['icon'] === 'archive'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg>
                                <?php elseif ($info['icon'] === 'link'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                <?php elseif ($info['icon'] === 'qr'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                <?php elseif ($info['icon'] === 'scissors'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><line x1="20" y1="4" x2="8.12" y2="15.88"></line><line x1="14.47" y1="14.48" x2="20" y2="20"></line><line x1="8.12" y1="8.12" x2="12" y2="12"></line></svg>
                                <?php elseif ($info['icon'] === 'folder'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                                <?php elseif ($info['icon'] === 'layout'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                                <?php elseif ($info['icon'] === 'lock'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                <?php elseif ($info['icon'] === 'lightbulb'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="9" y1="18" x2="15" y2="18"></line><line x1="10" y1="22" x2="14" y2="22"></line><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A6 6 0 1 0 5.5 8c0 1.5.6 2.89 1.62 3.84c.64.6 1.05 1.34 1.25 2.16"></path></svg>
                                <?php elseif ($info['icon'] === 'check-circle'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                <?php elseif ($info['icon'] === 'user-plus'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                <?php elseif ($info['icon'] === 'flag'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                                <?php elseif ($info['icon'] === 'target'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                                <?php elseif ($info['icon'] === 'layers'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 12 12 17 22 12"/><polyline points="2 17 12 22 22 17"/></svg>
                                <?php elseif ($info['icon'] === 'video'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                                <?php elseif ($info['icon'] === 'user-check'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><polyline points="17 11 19 13 23 9"/></svg>
                                <?php elseif ($info['icon'] === 'share-2'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                                <?php elseif ($info['icon'] === 'compass'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>
                                <?php elseif ($info['icon'] === 'shield'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                <?php elseif ($info['icon'] === 'feather'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16" y1="8" x2="2" y2="22"/><line x1="17.5" y1="15" x2="9" y2="15"/></svg>
                                <?php elseif ($info['icon'] === 'navigation'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                                <?php elseif ($info['icon'] === 'gift'): ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                                <?php else: ?>
                                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                <?php endif; ?>
                            </div>
                            
                            <?php 
                                $currentName = $appData[$type]['name'] ?? $info['name'];
                                $currentSort = $appData[$type]['sort_order'] ?? 0;
                                $currentCategory = $appData[$type]['sidebar_category_key'] ?? '';
                                $currentCategoryLabel = $appData[$type]['sidebar_category_label'] ?? '';
                                $currentSidebarOrder = $appData[$type]['sidebar_order'] ?? ($currentSort ?: 100);
                                $isPrimary = !empty($appData[$type]['is_primary']);
                                $categorySelectValue = $isPrimary ? '' : (array_key_exists($currentCategory, $sidebarCategories) ? $currentCategory : ($currentCategory !== '' ? 'custom' : ''));
                            ?>
                            <div class="mod-inputs" style="margin-top: 8px;">
                                <input type="text" name="names[<?= $type ?>]" value="<?= View::escape($currentName) ?>" 
                                       onclick="event.stopPropagation()" 
                                       class="form-control form-control-sm" style="text-align:center; font-size:12px; margin-bottom:6px; background:var(--input-bg); border:1px solid var(--border); border-radius:6px; padding:6px; width:100%; color:var(--text);" placeholder="Uygulama Adı">
                                       
                                <div style="display:flex; align-items:center; gap:6px; justify-content:center;">
                                    <span style="font-size:11px; color:var(--muted);">Sıra:</span>
                                    <input type="number" name="sort_orders[<?= $type ?>]" value="<?= $currentSort ?>" 
                                           onclick="event.stopPropagation()" 
                                           class="form-control form-control-sm" style="text-align:center; font-size:12px; width:50px; background:var(--input-bg); border:1px solid var(--border); border-radius:6px; padding:4px; color:var(--text);">
                                </div>
                            </div>

                            <div class="mod-sidebar-tools" onclick="event.stopPropagation()">
                                <div class="mod-sidebar-row">
                                    <select name="sidebar_categories[<?= $type ?>]" aria-label="Sidebar kategorisi">
                                        <option value="">Varsayılan</option>
                                        <?php foreach ($sidebarCategories as $categoryKey => $categoryLabel): ?>
                                            <option value="<?= View::escape($categoryKey) ?>" <?= $categorySelectValue === $categoryKey ? 'selected' : '' ?>>
                                                <?= View::escape($categoryLabel) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="number" name="sidebar_orders[<?= $type ?>]" value="<?= (int)$currentSidebarOrder ?>" aria-label="Sidebar sırası">
                                </div>
                                <input type="text"
                                       name="custom_category_labels[<?= $type ?>]"
                                       value="<?= $categorySelectValue === 'custom' ? View::escape($currentCategoryLabel) : '' ?>"
                                       placeholder="Özel kategori adı">
                                <label class="mod-primary-toggle">
                                    <input type="checkbox" name="primary_modules[]" value="<?= $type ?>" <?= $isPrimary ? 'checked' : '' ?>>
                                    Ana uygulama
                                </label>
                            </div>
                            
                            <!-- Hidden input for form submission -->
                            <input type="checkbox" class="module-select-input" name="modules[]" value="<?= $type ?>" style="display:none;" <?= $isSelected ? 'checked' : '' ?>>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="save-bar">
                    <button type="submit" class="btn btn-primary" style="background:#10b981; border:none; color:#fff; font-size:15px; padding:12px 24px;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
function toggleModule(card, type) {
    const checkbox = card.querySelector('.module-select-input');
    if (!checkbox) return;
    checkbox.checked = !checkbox.checked;
    
    if (checkbox.checked) {
        card.classList.add('selected');
    } else {
        card.classList.remove('selected');
    }
}

document.getElementById('appsAssignModal')?.addEventListener('click', function(event) {
    if (event.target === this) {
        window.location.href = '<?= BASE_URL ?>/superadmin/apps';
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && document.getElementById('appsAssignModal')) {
        window.location.href = '<?= BASE_URL ?>/superadmin/apps';
    }
});
</script>
