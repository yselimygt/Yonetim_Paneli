<?php
require_once __DIR__ . '/../../models/SiteSetting.php';
$globalSiteSettings = new SiteSetting();
$globalBrandSettings = $globalSiteSettings->get('brand');
$globalFaviconUrl = $globalBrandSettings['favicon'] ?? '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — <?= View::escape($currentDept['name'] ?? 'Panel') ?></title>
    <?php if ($globalFaviconUrl): ?>
        <link rel="icon" href="<?= View::escape($globalFaviconUrl) ?>">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        const storedTheme = localStorage.getItem('theme');
        if (storedTheme === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:       #0a0a0a;
            --sidebar:  #0f0f0f;
            --card:     #111111;
            --border:   #1e1e1e;
            --accent:   <?= View::escape($_SESSION['user_theme_color'] ?? ($currentDept['color'] ?? '#00BCD4')) ?>;
            --text:     #e8e8e8;
            --muted:    #555;
            --danger:   #ef4444;
            --success:  #22c55e;
            --warning:  #f59e0b;
            --sidebar-w: 230px;
            --input-bg: #0d0d0d;
            --card-alt: #151515;
            --modal-overlay: rgba(0,0,0,0.85);
        }

        [data-theme="light"] {
            --bg:       #f3f4f6;
            --sidebar:  #ffffff;
            --card:     #ffffff;
            --border:   #e5e7eb;
            --text:     #111827;
            --muted:    #6b7280;
            --input-bg: #f9fafb;
            --card-alt: #f8fafc;
            --modal-overlay: rgba(17,24,39,0.7);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ── Animations ── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes popIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .fade-in-up { animation: fadeInUp 0.4s ease-out forwards; opacity: 0; }
        .pop-in { animation: popIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }

        .stagger-1 { animation-delay: 0.05s; }
        .stagger-2 { animation-delay: 0.1s; }
        .stagger-3 { animation-delay: 0.15s; }
        .stagger-4 { animation-delay: 0.2s; }
        .stagger-5 { animation-delay: 0.25s; }

        /* Smooth transitions for interactive elements */
        .btn, .card, input, select, textarea, .nav-link {
            transition: all 0.2s ease;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 100;
            box-shadow: 2px 0 20px rgba(0,0,0,0.15);
            transition: width 0.3s ease;
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            background: linear-gradient(180deg, rgba(255,255,255,0.02) 0%, transparent 100%);
        }

        .sidebar-brand .dept-indicator {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 4px;
        }

        .dept-color-bar {
            width: 6px;
            height: 32px;
            border-radius: 4px;
            background: var(--accent);
            box-shadow: 0 0 12px var(--accent);
            opacity: 0.8;
        }

        .sidebar-brand h2 {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.2px;
        }

        .sidebar-brand .app-name {
            font-size: 11px;
            color: var(--muted);
            margin-top: 2px;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .sidebar-search {
            padding: 16px 12px 0 12px;
        }
        
        .sidebar-search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .sidebar-search-wrapper svg {
            position: absolute;
            left: 10px;
            color: var(--muted);
            opacity: 0.7;
        }
        
        .sidebar-search-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 12px 8px 34px;
            color: var(--text);
            font-size: 12px;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
        }
        
        .sidebar-search-input:focus {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--accent);
            box-shadow: 0 0 0 2px color-mix(in srgb, var(--accent) 20%, transparent);
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--border) transparent;
        }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        .nav-section {
            padding: 16px 14px 8px;
            font-size: 10px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            opacity: 0.7;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            margin-bottom: 4px;
            color: var(--text);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0.75;
        }

        .nav-link .icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
        }

        .nav-link:hover {
            opacity: 1;
            background: rgba(255,255,255,0.05);
            transform: translateX(4px);
        }

        .nav-link:hover .icon {
            transform: scale(1.1);
        }

        .nav-link.active {
            opacity: 1;
            color: var(--accent);
            background: rgba(255,255,255, 0.05);
            font-weight: 600;
            box-shadow: inset 4px 0 0 var(--accent);
        }

        .nav-category {
            margin: 10px 0 8px;
        }

        .nav-category:first-of-type {
            margin-top: 2px;
        }

        .nav-category-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            width: 100%;
            padding: 7px 10px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
            text-align: left;
            text-transform: uppercase;
        }

        .nav-category-toggle:hover {
            background: rgba(255,255,255,.035);
            color: var(--text);
        }

        .nav-category-title {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .nav-category-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 18px;
            padding: 0 6px;
            border: 1px solid var(--border);
            border-radius: 999px;
            color: var(--muted);
            font-size: 10px;
            letter-spacing: 0;
        }

        .nav-category-body {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .nav-category.active .nav-category-toggle {
            color: var(--accent);
        }

        .nav-category.active .nav-category-count {
            border-color: color-mix(in srgb, var(--accent) 42%, var(--border));
            color: var(--accent);
        }

        /* Yönetim özel nav */
        .nav-link.mgmt-link {
            color: #d97706;
        }
        .nav-link.mgmt-link:hover {
            color: var(--warning);
            background: rgba(245,158,11,0.05);
        }
        .nav-link.mgmt-link.active {
            color: var(--warning);
            background: rgba(245,158,11,0.1);
            box-shadow: inset 4px 0 0 var(--warning);
        }

        .dept-switcher {
            padding: 12px 16px;
            border-top: 1px solid rgba(255,255,255,0.03);
        }

        .dept-switch-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 500;
            color: var(--text);
            background: rgba(255,255,255,0.03);
            text-decoration: none;
            padding: 10px;
            border-radius: 8px;
            transition: all 0.2s;
            border: 1px solid var(--border);
        }
        .dept-switch-btn:hover { background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.1); }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.03);
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-user { font-size: 13px; font-weight: 500; color: var(--text); margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; }
        .sidebar-user-info { display: flex; align-items: center; gap: 10px; overflow: hidden; }
        .sidebar-avatar { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(255,255,255,0.1); flex-shrink: 0; }
        .sidebar-user-name { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .role-badge { display: inline-block; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 600; flex-shrink: 0; }
        .role-director { background: rgba(245,158,11,.15); color: #fbbf24; }
        .role-member   { background: rgba(0,188,212,.15);   color: #67e8f9; }

        .btn-logout {
            display: block;
            width: 100%;
            padding: 8px;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            border-radius: 6px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 500;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-logout:hover { background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.3); color: var(--danger); }

        /* ── Main ── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            height: 50px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 12px;
            background: var(--bg);
            position: sticky; top: 0; z-index: 50;
        }

        .topbar h1 { font-size: 13px; font-weight: 600; color: var(--text); }
        .topbar .sep { color: var(--border); }
        .topbar .sub { font-size: 12px; color: var(--muted); }
        .panel-mobile-menu-btn,
        .panel-mobile-dept-btn {
            display: none;
        }

        .page-content { padding: 28px; flex: 1; }

        /* ── Components ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .page-header h2 { font-size: 18px; font-weight: 600; }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
        }
        .card-body   { padding: 20px 24px; }
        .card-header {
            padding: 13px 20px;
            border-bottom: 1px solid var(--border);
            font-size: 12px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; margin-bottom: 24px; }
        .stat-card { background: var(--card); border: 1px solid var(--border); border-radius: 10px; padding: 18px; }
        .stat-card .value { font-size: 28px; font-weight: 700; color: var(--accent); line-height: 1; margin-bottom: 5px; }
        .stat-card .label { font-size: 11px; color: var(--muted); }

        /* App kartları */
        .apps-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 14px; }

        .app-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 20px;
            text-decoration: none;
            color: var(--text);
            transition: border-color .2s, transform .15s;
            display: flex;
            flex-direction: column;
            gap: 10px;
            position: relative;
        }
        .app-card:hover {
            border-color: var(--accent);
            transform: translateY(-2px);
        }

        .app-card-icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(var(--accent-rgb, 0,188,212), .12);
            color: var(--accent);
        }
        .app-card-icon svg { width: 18px; height: 18px; }

        .app-card-name { font-size: 13px; font-weight: 500; }
        .app-card-desc { font-size: 11px; color: var(--muted); line-height: 1.5; }
        .app-card-badge {
            position: absolute;
            top: 10px; right: 10px;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 3px;
            background: rgba(245,158,11,.1);
            color: #fbbf24;
            border: 1px solid rgba(245,158,11,.2);
        }

        /* Yönetim özel kart */
        .mgmt-card {
            background: linear-gradient(135deg, rgba(245,158,11,.05) 0%, rgba(245,158,11,.02) 100%);
            border-color: rgba(245,158,11,.2);
        }
        .mgmt-card:hover { border-color: var(--warning); }
        .mgmt-card .app-card-icon { background: rgba(245,158,11,.12); color: var(--warning); }

        /* Table */
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { padding: 10px 16px; text-align: left; font-size: 11px; font-weight: 500; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border); }
        td { padding: 12px 16px; border-bottom: 1px solid rgba(255,255,255,.04); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,.012); }

        /* Badge */
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 500; }
        .badge-active   { background:rgba(34,197,94,.12);  color:#86efac; }
        .badge-inactive { background:rgba(100,100,100,.12); color:#666; }
        .badge-director { background:rgba(245,158,11,.12); color:#fcd34d; }
        .badge-president{ background:rgba(234,179,8,.12); color:#eab308; }
        .badge-vice-president{ background:rgba(250,204,21,.12); color:#facc15; }
        .badge-member   { background:rgba(0,188,212,.1);   color:#67e8f9; }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px;
            border-radius: 7px;
            font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 500;
            cursor: pointer; border: none; text-decoration: none;
            transition: opacity .15s, transform .1s;
        }
        .btn:active { transform: scale(0.97); }
        .btn-primary { background: var(--accent); color: #0a0a0a; }
        .btn-primary:hover { opacity: .88; }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--muted); }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .btn-sm { padding: 5px 10px; font-size: 12px; }

        .btn-theme {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-left: 12px;
            transition: background 0.2s;
        }
        .btn-theme:hover { background: rgba(128,128,128,0.1); }

        /* Flash */
        .flash { border-radius: 8px; padding: 11px 16px; font-size: 13px; margin-bottom: 20px; }
        .flash.error   { background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.3); color:#fca5a5; }
        .flash.success { background:rgba(34,197,94,.1); border:1px solid rgba(34,197,94,.3); color:#86efac; }
        .flash.warning { background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:#fcd34d; }

        /* Forms */
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 11px; font-weight: 500; color: var(--muted); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 7px; }
        .form-control { width: 100%; background: var(--input-bg); border: 1px solid var(--border); border-radius: 7px; padding: 10px 13px; color: var(--text); font-family: 'Inter', sans-serif; font-size: 13px; outline: none; transition: border-color .2s; }
        .form-control:focus { border-color: var(--accent); }
        select.form-control { cursor: pointer; }
        textarea.form-control { resize: vertical; min-height: 80px; }

        .empty-state { text-align: center; padding: 60px 20px; color: var(--muted); }
        .empty-state svg { opacity: .2; margin-bottom: 12px; }
        .empty-state p { font-size: 13px; }

        .actions { display: flex; gap: 6px; }

        /* Modals (Global) */
        .modal {
            display: none;
            position: fixed; inset: 0; background: var(--modal-overlay); backdrop-filter: blur(4px);
            z-index: 1000; align-items: center; justify-content: center; padding: 20px; opacity: 0; transition: opacity 0.2s ease;
        }
        .modal.show { display: flex; opacity: 1; }
        .modal-content {
            background: var(--card); border: 1px solid var(--border); border-radius: 16px; width: 100%; max-width: 500px;
            padding: 28px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); transform: scale(0.95) translateY(10px);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); position: relative;
        }
        .modal.show .modal-content { transform: scale(1) translateY(0); }
        .modal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
        .modal-header h3 { font-size: 18px; font-weight: 600; color: var(--text); margin: 0; }
        .modal-close {
            background: transparent; border: none; color: var(--muted); font-size: 24px; line-height: 1; cursor: pointer;
            padding: 4px; display: flex; align-items: center; justify-content: center; transition: color 0.2s;
        }
        .modal-close:hover { color: var(--text); }
        .modal-overlay {
            display: none; position: fixed; inset: 0; background: var(--modal-overlay); backdrop-filter: blur(4px);
            z-index: 1000; align-items: center; justify-content: center; transition: opacity 0.3s ease; opacity: 0;
        }
        .modal-overlay.show { display: flex; opacity: 1; }
        .modal-overlay .modal-content { transform: scale(0.95) translateY(10px); transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        .modal-overlay.show .modal-content { transform: scale(1) translateY(0); }

        .panel-sidebar-overlay {
            display: none;
        }

        .panel-mobile-bottom-menu {
            display: none;
        }

        .panel-dept-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 1200;
            align-items: flex-end;
            justify-content: center;
            padding: 12px;
            background: var(--modal-overlay);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        .panel-dept-modal.show {
            display: flex;
        }

        .panel-dept-sheet {
            width: min(100%, 460px);
            max-height: min(78dvh, 620px);
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 16px 16px 12px 12px;
            background: var(--card);
            box-shadow: 0 18px 44px rgba(0,0,0,.34);
        }

        .panel-dept-sheet-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px;
            border-bottom: 1px solid var(--border);
        }

        .panel-dept-sheet-head strong {
            font-size: 14px;
        }

        .panel-dept-close {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: transparent;
            color: var(--text);
            cursor: pointer;
        }

        .panel-dept-list {
            display: grid;
            gap: 8px;
            max-height: calc(min(78dvh, 620px) - 64px);
            overflow-y: auto;
            padding: 12px;
        }

        .panel-dept-option {
            display: grid;
            grid-template-columns: 10px minmax(0, 1fr) auto;
            gap: 10px;
            align-items: center;
            min-height: 48px;
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 11px;
            color: var(--text);
            text-decoration: none;
            background: rgba(255,255,255,.018);
        }

        .panel-dept-option.active {
            border-color: color-mix(in srgb, var(--accent) 55%, transparent);
            background: color-mix(in srgb, var(--accent) 12%, transparent);
        }

        .panel-dept-dot {
            width: 10px;
            height: 30px;
            border-radius: 999px;
            background: var(--dept-color, var(--accent));
        }

        .panel-dept-name {
            min-width: 0;
            overflow: hidden;
            font-size: 12px;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .panel-dept-role {
            padding: 3px 7px;
            border-radius: 999px;
            background: rgba(255,255,255,.04);
            color: var(--muted);
            font-size: 9px;
            font-weight: 800;
        }

        @media (max-width: 900px) {
            body {
                display: block;
                overflow-x: hidden;
            }

            body.panel-menu-open {
                overflow: hidden;
            }

            .sidebar {
                display: flex;
                width: min(82vw, 300px);
                z-index: 1300;
                transform: translateX(-105%);
                box-shadow: 18px 0 44px rgba(0,0,0,.36);
                transition: transform .24s ease;
            }

            body.panel-menu-open .sidebar {
                transform: translateX(0);
            }

            .panel-sidebar-overlay {
                position: fixed;
                inset: 0;
                z-index: 1290;
                display: block;
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                background: rgba(0,0,0,.64);
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
                transition: opacity .2s ease, visibility .2s ease;
            }

            body.panel-menu-open .panel-sidebar-overlay {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
            }

            .sidebar-brand {
                padding: 18px 16px 14px;
            }

            .sidebar-nav {
                padding: 10px;
            }

            .nav-section {
                padding: 12px 10px 6px;
                font-size: 9px;
            }

            .nav-link {
                min-height: 42px;
                padding: 10px 11px;
                border-radius: 10px;
                font-size: 12px;
            }

            .sidebar-footer {
                display: none;
            }

            .panel-mobile-bottom-menu {
                position: fixed;
                left: 10px;
                right: 10px;
                bottom: calc(8px + env(safe-area-inset-bottom));
                z-index: 80;
                display: grid;
                grid-template-columns: minmax(0, 1fr) 58px minmax(0, 1fr);
                align-items: center;
                gap: 8px;
                min-height: 64px;
                padding: 8px 10px;
                border: 1px solid color-mix(in srgb, var(--accent) 28%, var(--border));
                border-radius: 18px;
                background:
                    linear-gradient(135deg, color-mix(in srgb, var(--accent) 17%, transparent), color-mix(in srgb, var(--sidebar) 82%, #111827) 48%, rgba(255,255,255,.035)),
                    var(--sidebar);
                box-shadow:
                    0 16px 38px rgba(0,0,0,.42),
                    0 0 0 1px rgba(255,255,255,.035) inset,
                    0 -10px 28px color-mix(in srgb, var(--accent) 10%, transparent);
                backdrop-filter: blur(18px);
                -webkit-backdrop-filter: blur(18px);
            }

            body.panel-menu-open .panel-mobile-bottom-menu {
                opacity: 0;
                pointer-events: none;
            }

            .panel-mobile-bottom-user {
                display: flex;
                align-items: center;
                gap: 8px;
                min-width: 0;
            }

            .panel-mobile-bottom-avatar {
                width: 30px;
                height: 30px;
                border-radius: 50%;
                object-fit: cover;
                border: 1px solid rgba(255,255,255,.1);
                flex: 0 0 auto;
            }

            .panel-mobile-bottom-name {
                display: block;
                overflow: hidden;
                color: var(--text);
                font-size: 11px;
                font-weight: 800;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .panel-mobile-bottom-role {
                display: inline-flex;
                margin-top: 3px;
                padding: 2px 6px;
                border-radius: 999px;
                background: rgba(245,158,11,.15);
                color: #fbbf24;
                font-size: 9px;
                font-weight: 800;
            }

            .panel-mobile-bottom-role.member {
                background: color-mix(in srgb, var(--accent) 18%, transparent);
                color: var(--accent);
            }

            .panel-mobile-bottom-logo {
                justify-self: center;
                align-self: start;
                display: grid;
                place-items: center;
                width: 54px;
                height: 54px;
                margin-top: -27px;
                border: 5px solid var(--bg);
                border-radius: 50%;
                background:
                    radial-gradient(circle at 35% 28%, rgba(255,255,255,.2), transparent 34%),
                    var(--accent);
                color: #071112;
                font-size: 13px;
                font-weight: 900;
                box-shadow: 0 12px 28px color-mix(in srgb, var(--accent) 26%, transparent);
            }

            .panel-mobile-bottom-actions {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 5px;
                min-width: 0;
            }

            .panel-mobile-bottom-link {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 30px;
                border: 1px solid color-mix(in srgb, var(--accent) 24%, var(--border));
                border-radius: 9px;
                background: color-mix(in srgb, var(--accent) 9%, transparent);
                color: var(--text);
                font-size: 10px;
                font-weight: 800;
                text-decoration: none;
                white-space: nowrap;
            }

            .panel-mobile-bottom-link.danger {
                border-color: rgba(239,68,68,.22);
                background: rgba(239,68,68,.08);
                color: #fca5a5;
            }

            .panel-mobile-bottom-powered {
                position: absolute;
                left: 50%;
                bottom: 3px;
                transform: translateX(-50%);
                display: inline-flex;
                align-items: center;
                gap: 3px;
                color: var(--muted);
                font-size: 8px;
                pointer-events: auto;
            }

            .panel-mobile-bottom-powered img {
                height: 12px;
                opacity: .72;
                
            }

            [data-theme="light"] .panel-mobile-bottom-menu {
                border-color: color-mix(in srgb, var(--accent) 22%, var(--border));
                background:
                    linear-gradient(135deg, color-mix(in srgb, var(--accent) 12%, transparent), rgba(255,255,255,.94) 48%, rgba(255,255,255,.62)),
                    rgba(255,255,255,.92);
                box-shadow:
                    0 14px 32px rgba(15,23,42,.14),
                    0 0 0 1px rgba(255,255,255,.72) inset,
                    0 -8px 22px color-mix(in srgb, var(--accent) 8%, transparent);
            }

            [data-theme="light"] .panel-mobile-bottom-avatar {
                border-color: rgba(15,23,42,.1);
            }

            [data-theme="light"] .panel-mobile-bottom-name {
                color: #0f172a;
            }

            [data-theme="light"] .panel-mobile-bottom-logo {
                border-color: #f3f4f6;
                color: #06343b;
            }

            [data-theme="light"] .panel-mobile-bottom-link {
                color: #0f172a;
            }

            [data-theme="light"] .panel-mobile-bottom-link.danger {
                color: #b91c1c;
            }

            [data-theme="light"] .panel-mobile-bottom-powered {
                color: #64748b;
            }

            [data-theme="light"] .panel-mobile-bottom-powered img {
                opacity: .72;
                }

            .main {
                width: 100%;
                margin-left: 0;
                min-width: 0;
                padding-bottom: 92px;
            }

            .topbar {
                min-height: 54px;
                height: auto;
                padding: 8px 12px;
                gap: 8px;
                background: color-mix(in srgb, var(--bg) 94%, transparent);
                backdrop-filter: blur(14px);
                -webkit-backdrop-filter: blur(14px);
            }

            .panel-mobile-menu-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                flex: 0 0 auto;
                width: 38px;
                height: 38px;
                border: 1px solid var(--border);
                border-radius: 11px;
                background: var(--card);
                color: var(--text);
                cursor: pointer;
            }

            .topbar h1 {
                display: none;
            }

            .topbar .sep {
                display: none;
            }

            .topbar .sub {
                min-width: 0;
                flex: 1;
                overflow: hidden;
                color: var(--text);
                font-size: 12px;
                font-weight: 700;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .panel-mobile-dept-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                max-width: 46%;
                min-height: 38px;
                padding: 7px 10px;
                border: 1px solid color-mix(in srgb, var(--accent) 34%, var(--border));
                border-radius: 11px;
                background: color-mix(in srgb, var(--accent) 11%, var(--card));
                color: var(--text);
                cursor: pointer;
                font-family: inherit;
            }

            .panel-mobile-dept-btn span {
                min-width: 0;
                overflow: hidden;
                font-size: 11px;
                font-weight: 800;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .panel-mobile-dept-btn i {
                flex: 0 0 auto;
                width: 8px;
                height: 24px;
                border-radius: 999px;
                background: var(--accent);
            }

            .btn-theme {
                flex: 0 0 auto;
                width: 38px;
                height: 38px;
                margin-left: 0 !important;
                border-radius: 11px;
            }

            .page-content {
                padding: 14px 12px 28px;
            }

            .nav-category {
                margin: 8px 0;
            }

            .nav-category-toggle {
                min-height: 34px;
                padding: 8px 10px;
                border: 1px solid var(--border);
                background: rgba(255,255,255,.025);
                font-size: 10px;
            }

            .nav-category-body {
                display: none;
                padding-top: 5px;
            }

            .nav-category.open .nav-category-body,
            .nav-category.active .nav-category-body {
                display: flex;
            }

            .nav-category.open .nav-category-toggle,
            .nav-category.active .nav-category-toggle {
                border-color: color-mix(in srgb, var(--accent) 38%, var(--border));
                background: rgba(255,255,255,.045);
            }

            .nav-category .nav-link {
                margin-bottom: 3px;
            }

            .panel-dept-sheet {
                box-shadow: none;
            }
        }
    
        .vipera-link {
            display: flex;
            align-items: center;
        }
        .vipera-logo {
            height: 18px;
            opacity: 0.7;
            
            transition: all 0.3s;
        }
        .vipera-link:hover .vipera-logo {
            opacity: 1;
            filter: brightness(1) invert(0);
        }
        [data-theme="light"] .vipera-logo {
            filter: brightness(0);
        }
        [data-theme="light"] .vipera-link:hover .vipera-logo {
            filter: brightness(1);
        }
    </style>
</head>
<body>

<!-- ── Sidebar ── -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="dept-indicator">
            <div class="dept-color-bar"></div>
            <div>
                <?php
                $sidebarTitle = $currentDept['name'] ?? 'Panel';
                ?>
                <h2><?= View::escape($sidebarTitle) ?></h2>
                <div class="app-name"><?= APP_NAME ?></div>
            </div>
        </div>
    </div>

    <?php
    if (!function_exists('panelSidebarIconPath')) {
        function panelSidebarIconPath(string $icon): string
        {
            $paths = [
                'form' => '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
                'file-text' => '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
                'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
                'check-square' => '<polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>',
                'megaphone' => '<path d="M22 17H2a3 3 0 000 3h20a3 3 0 000-3zM22 11H2M22 5H2"/>',
                'bar-chart' => '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
                'users' => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>',
                'pie-chart' => '<path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path>',
                'credit-card' => '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line>',
                'map-pin' => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
                'book-open' => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
                'award' => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>',
                'archive' => '<polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line>',
                'lock' => '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>',
                'scissors' => '<circle cx="6" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><line x1="20" y1="4" x2="8.12" y2="15.88"></line><line x1="14.47" y1="14.48" x2="20" y2="20"></line><line x1="8.12" y1="8.12" x2="12" y2="12"></line>',
                'qr' => '<rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>',
                'link' => '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>',
                'volume-2' => '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"/>',
                'flag' => '<path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/>',
                'target' => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
                'layers' => '<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 12 12 17 22 12"/><polyline points="2 17 12 22 22 17"/>',
                'video' => '<polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>',
                'user-check' => '<path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><polyline points="17 11 19 13 23 9"/>',
                'share-2' => '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>',
                'compass' => '<circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>',
                'folder' => '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>',
                'lightbulb' => '<line x1="9" y1="18" x2="15" y2="18"></line><line x1="10" y1="22" x2="14" y2="22"></line><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A6 6 0 1 0 5.5 8c0 1.5.6 2.89 1.62 3.84c.64.6 1.05 1.34 1.25 2.16"></path>',
                'layout' => '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line>',
                'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
                'feather' => '<path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16" y1="8" x2="2" y2="22"/><line x1="17.5" y1="15" x2="9" y2="15"/>',
                'navigation' => '<polygon points="3 11 22 2 13 21 11 13 3 11"/>',
                'gift' => '<polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>',
            ];

            return $paths[$icon] ?? '<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>';
        }
    }
    ?>

    <div class="sidebar-search">
        <div class="sidebar-search-wrapper">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" id="appSearchInput" class="sidebar-search-input" placeholder="Uygulama ara..." autocomplete="off">
        </div>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section">Panel</div>
        <a href="<?= BASE_URL ?>/panel/<?= View::escape($currentDept['slug'] ?? '') ?>"
           class="nav-link <?= ($activeSection ?? '') === 'dashboard' ? 'active' : '' ?>">
            <span class="icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="15" height="15">
                    <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
            </span>
            Dashboard
        </a>

        <?php if (!empty($deptApps)): ?>
        <div class="nav-section">Uygulamalar</div>
        <?php 
        $isDirectorOrAdmin = Auth::isSuperAdmin() || (($userRole ?? 'member') === 'director');
        $currentDeptSlug = $currentDept['slug'] ?? '';

        $sidebarCategories = [
            'people' => ['label' => 'Üyeler & Ağ', 'order' => 10],
            'project' => ['label' => 'Proje Yönetimi', 'order' => 20],
            'communication' => ['label' => 'İletişim & Başvurular', 'order' => 30],
            'events' => ['label' => 'Etkinlik & Takvim', 'order' => 40],
            'knowledge' => ['label' => 'Bilgi & Dosyalar', 'order' => 50],
            'finance' => ['label' => 'Finans & Raporlama', 'order' => 60],
            'operations' => ['label' => 'Operasyon & Araçlar', 'order' => 70],
            'governance' => ['label' => 'Yönetim & Denetim', 'order' => 80],
            'special' => ['label' => 'Departman Özel', 'order' => 90],
        ];

        $moduleCategories = [
            'members' => 'people',
            'members_public' => 'people',
            'directory' => 'people',
            'alumni' => 'people',
            'alumni_admin' => 'people',
            'mentorship' => 'people',
            'applications' => 'communication',
            'inbox' => 'communication',
            'announcement' => 'communication',
            'form' => 'communication',
            'form_manager' => 'communication',
            'form_grouped' => 'communication',
            'calendar' => 'events',
            'event' => 'events',
            'bootcamp' => 'events',
            'meeting' => 'events',
            'drive' => 'knowledge',
            'wiki' => 'knowledge',
            'media' => 'knowledge',
            'media_admin' => 'knowledge',
            'links' => 'knowledge',
            'links_admin' => 'knowledge',
            'links_public' => 'knowledge',
            'certificate' => 'knowledge',
            'finance' => 'finance',
            'dues' => 'finance',
            'budget' => 'finance',
            'report' => 'finance',
            'inventory' => 'operations',
            'passwords' => 'operations',
            'qr' => 'operations',
            'qr_admin' => 'operations',
            'shortener' => 'operations',
            'shortener_admin' => 'operations',
            'agile' => 'project',
            'ideahub' => 'project',
            'task_manager' => 'project',
            'audit' => 'governance',
            'bylaws' => 'governance',
            'sponsor' => 'communication',
            'partner' => 'communication',
            'social' => 'communication',
            'trophy' => 'events',
        ];

        $departmentProfiles = [
            'arge' => [
                'order' => ['project', 'knowledge', 'operations', 'communication', 'people', 'events', 'finance', 'governance', 'special'],
                'modules' => ['agile' => 'project', 'ideahub' => 'project', 'wiki' => 'knowledge', 'drive' => 'knowledge', 'inventory' => 'operations'],
            ],
            'teknoloji' => [
                'order' => ['project', 'knowledge', 'operations', 'communication', 'people', 'events', 'finance', 'governance', 'special'],
                'modules' => ['agile' => 'project', 'ideahub' => 'project', 'wiki' => 'knowledge', 'drive' => 'knowledge', 'passwords' => 'operations'],
            ],
            'insan' => [
                'order' => ['people', 'communication', 'events', 'knowledge', 'operations', 'project', 'finance', 'governance', 'special'],
                'modules' => ['members' => 'people', 'applications' => 'communication', 'alumni' => 'people', 'mentorship' => 'people'],
            ],
            'denetim' => [
                'order' => ['governance', 'communication', 'knowledge', 'people', 'operations', 'project', 'events', 'finance', 'special'],
                'modules' => ['audit' => 'governance', 'report' => 'governance', 'inbox' => 'communication'],
            ],
            'finans' => [
                'order' => ['finance', 'operations', 'communication', 'people', 'knowledge', 'project', 'events', 'governance', 'special'],
                'modules' => ['budget' => 'finance', 'report' => 'finance', 'inventory' => 'operations', 'dues' => 'finance'],
            ],
            'yonetim' => [
                'order' => ['governance', 'communication', 'people', 'finance', 'events', 'knowledge', 'operations', 'project', 'special'],
                'modules' => ['audit' => 'governance', 'announcement' => 'communication', 'members' => 'people', 'report' => 'governance'],
            ],
        ];

        $activeProfile = ['order' => [], 'modules' => []];
        foreach ($departmentProfiles as $slugNeedle => $profile) {
            if (stripos($currentDeptSlug, $slugNeedle) !== false) {
                $activeProfile = $profile;
                break;
            }
        }

        $categoryOrder = $activeProfile['order'] ?: array_keys($sidebarCategories);
        $categoryOrder = array_values(array_unique($categoryOrder));
        $categoryOrderMap = array_flip($categoryOrder);
        $sidebarGroups = [];
        $primaryItems = [];
        $renderedModules = [];
        $appIndex = 0;

        foreach ($deptApps as $app) {
            $modGroup = $app['module_type'];

            if ($modGroup === 'passwords' && !$isDirectorOrAdmin) {
                continue;
            }

            if ($app['module_type'] === 'form' && !in_array($app['slug'], ['form', 'form_manager'])) {
                continue;
            }

            if (in_array($modGroup, ['form', 'form_manager'])) {
                $modGroup = 'form_grouped';
            }

            if (in_array($modGroup, $renderedModules)) {
                continue;
            }
            $renderedModules[] = $modGroup;

            $href = BASE_URL . "/panel/" . View::escape($currentDeptSlug) . "/apps/" . View::escape($app['slug']);
            $isActive = ($activeApp ?? '') === $app['slug'];

            if (in_array($app['module_type'], ['form', 'form_manager'])) {
                $href = BASE_URL . "/panel/" . View::escape($currentDeptSlug) . "/forms";
                $isActive = ($activeSection ?? '') === 'forms';
            } elseif ($app['module_type'] === 'finance') {
                $href = BASE_URL . "/panel/" . View::escape($currentDeptSlug) . "/finance";
                $isActive = ($activeSection ?? '') === 'finance';
            } elseif ($app['module_type'] === 'dues') {
                $href = BASE_URL . "/panel/" . View::escape($currentDeptSlug) . "/dues";
                $isActive = ($activeSection ?? '') === 'dues';
            }

            $dbCategoryKey = trim((string)($app['sidebar_category_key'] ?? ''));
            $dbCategoryLabel = trim((string)($app['sidebar_category_label'] ?? ''));
            $isPrimaryApp = !empty($app['is_primary']);
            $sidebarOrder = (int)($app['sidebar_order'] ?? 100);
            $categoryKey = $activeProfile['modules'][$modGroup] ?? $moduleCategories[$modGroup] ?? 'special';
            if ($dbCategoryKey !== '') {
                $categoryKey = $dbCategoryKey;
            }
            if ($isPrimaryApp) {
                $primaryItems[] = [
                    'href' => $href,
                    'active' => $isActive,
                    'icon' => panelSidebarIconPath($app['icon'] ?? ''),
                    'label' => $app['module_type'] === 'form_manager' ? 'Form' : ($app['name'] ?? 'Uygulama'),
                    'primary' => true,
                    'order' => $sidebarOrder,
                    'index' => $appIndex++,
                    'color' => $app['color'] ?? null,
                ];
                continue;
            }
            $categoryMeta = $sidebarCategories[$categoryKey] ?? [
                'label' => $dbCategoryLabel !== '' ? $dbCategoryLabel : 'Departman Özel',
                'order' => 95,
            ];
            if ($dbCategoryLabel !== '') {
                $categoryMeta['label'] = $dbCategoryLabel;
            }

            $sidebarGroups[$categoryKey] ??= [
                'meta' => $categoryMeta,
                'items' => [],
                'active' => false,
                'order' => $categoryOrderMap[$categoryKey] ?? ($categoryMeta['order'] ?? 999),
            ];
            if (($categoryMeta['order'] ?? 999) < $sidebarGroups[$categoryKey]['order']) {
                $sidebarGroups[$categoryKey]['order'] = $categoryMeta['order'];
            }

            $sidebarGroups[$categoryKey]['items'][] = [
                'href' => $href,
                'active' => $isActive,
                'icon' => panelSidebarIconPath($app['icon'] ?? ''),
                'label' => $app['module_type'] === 'form_manager' ? 'Form' : ($app['name'] ?? 'Uygulama'),
                'primary' => $isPrimaryApp,
                'order' => $sidebarOrder,
                'index' => $appIndex++,
                'color' => $app['color'] ?? null,
            ];

            if ($isActive) {
                $sidebarGroups[$categoryKey]['active'] = true;
            }
        }

        usort($primaryItems, function ($a, $b) {
            if ($a['order'] === $b['order']) {
                return $a['index'] <=> $b['index'];
            }
            return $a['order'] <=> $b['order'];
        });

        foreach ($sidebarGroups as &$group) {
            usort($group['items'], function ($a, $b) {
                if ($a['primary'] !== $b['primary']) {
                    return $a['primary'] ? -1 : 1;
                }
                if ($a['order'] === $b['order']) {
                    return $a['index'] <=> $b['index'];
                }
                return $a['order'] <=> $b['order'];
            });
        }
        unset($group);

        uasort($sidebarGroups, function ($a, $b) {
            if ($a['order'] === $b['order']) {
                return ($a['items'][0]['index'] ?? 0) <=> ($b['items'][0]['index'] ?? 0);
            }
            return $a['order'] <=> $b['order'];
        });
        ?>
        <?php foreach ($primaryItems as $item): ?>
            <a href="<?= $item['href'] ?>" class="nav-link <?= $item['active'] ? 'active' : '' ?> primary" <?= ($item['active'] && !empty($item['color'])) ? 'style="--accent: '.htmlspecialchars($item['color']).';"' : '' ?>>
                <span class="icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="15" height="15">
                        <?= $item['icon'] ?>
                    </svg>
                </span>
                <?= View::escape($item['label']) ?>
            </a>
        <?php endforeach; ?>
        <?php foreach ($sidebarGroups as $categoryKey => $group): ?>
            <div class="nav-category <?= $group['active'] ? 'active open' : '' ?>" data-nav-category>
                <button type="button" class="nav-category-toggle" onclick="toggleSidebarCategory(this)" aria-expanded="<?= $group['active'] ? 'true' : 'false' ?>">
                    <span class="nav-category-title"><?= View::escape($group['meta']['label']) ?></span>
                    <span class="nav-category-count"><?= count($group['items']) ?></span>
                </button>
                <div class="nav-category-body">
                    <?php foreach ($group['items'] as $item): ?>
                        <a href="<?= $item['href'] ?>" class="nav-link <?= $item['active'] ? 'active' : '' ?> <?= $item['primary'] ? 'primary' : '' ?>" <?= ($item['active'] && !empty($item['color'])) ? 'style="--accent: '.htmlspecialchars($item['color']).';"' : '' ?>>
                            <span class="icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="15" height="15">
                                    <?= $item['icon'] ?>
                                </svg>
                            </span>
                            <?= View::escape($item['label']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <?php endif; ?>

    </nav>

    <!-- Departman değiştirme -->
    <?php $allDepts = Auth::getDepartments(); ?>
    <?php if (count($allDepts) > 1): ?>
    <div class="dept-switcher">
        <a href="<?= BASE_URL ?>/panel" class="dept-switch-btn">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Departman Değiştir
        </a>
    </div>
    <?php endif; ?>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-info">
                <?php 
                    $u = Auth::user();
                    $userName = $u['name'] ?? 'Admin';
                    $userAvatar = !empty($u['avatar']) ? BASE_URL . '/public/' . ltrim($u['avatar'], '/') : null;
                    $avatarUrl = $userAvatar ?: "https://ui-avatars.com/api/?name=" . urlencode($userName) . "&background=random&color=fff&size=128";
                ?>
                <img src="<?= $avatarUrl ?>" alt="Avatar" class="sidebar-avatar">
                <span class="sidebar-user-name"><?= View::escape($userName) ?></span>
            </div>
            <?php
                $r = $userRole ?? 'member';
                if ($r === 'director') {
                    $rClass = 'role-director';
                    $rLabel = 'Direktör';
                } elseif ($r === 'president') {
                    $rClass = 'role-director';
                    $rLabel = 'Başkan';
                } elseif ($r === 'vice_president') {
                    $rClass = 'role-director';
                    $rLabel = 'Başkan Yardımcısı';
                } else {
                    $rClass = 'role-member';
                    $rLabel = 'Üye';
                }
            ?>
            <span class="role-badge <?= $rClass ?>">
                <?= $rLabel ?>
            </span>
        </div>
        <a href="<?= BASE_URL ?>/profile" class="btn-logout" style="margin-bottom:6px;">Profilim</a>
        <a href="<?= BASE_URL ?>/logout" class="btn-logout">Çıkış</a>
        <div style="font-size: 11px; text-align: center; color: var(--muted); margin-top: 12px; display: flex; align-items: center; justify-content: center; gap: 4px;">
            <span>Powered by</span>
            <a href="https://viperadev.com" target="_blank" class="vipera-link">
                <img src="https://viperadev.com/ViperaDev-Logo.png" alt="ViperaDev" class="vipera-logo">
            </a>
        </div>
    </div>
</aside>

<button type="button" class="panel-sidebar-overlay" onclick="closePanelMenu()" aria-label="Menüyü kapat"></button>

<div class="panel-mobile-bottom-menu" aria-label="Mobil hesap menüsü">
    <div class="panel-mobile-bottom-user">
        <img src="<?= $avatarUrl ?>" alt="Avatar" class="panel-mobile-bottom-avatar">
        <div style="min-width:0;">
            <span class="panel-mobile-bottom-name"><?= View::escape($userName) ?></span>
            <?php
                $mr = $userRole ?? 'member';
                if ($mr === 'director') {
                    $mrClass = '';
                    $mrLabel = 'Direktör';
                } elseif ($mr === 'president') {
                    $mrClass = '';
                    $mrLabel = 'Başkan';
                } elseif ($mr === 'vice_president') {
                    $mrClass = '';
                    $mrLabel = 'Başkan Yardımcısı';
                } else {
                    $mrClass = 'member';
                    $mrLabel = 'Üye';
                }
            ?>
            <span class="panel-mobile-bottom-role <?= $mrClass ?>">
                <?= $mrLabel ?>
            </span>
        </div>
    </div>

    <div class="panel-mobile-bottom-logo" aria-label="Logo alanı">IA</div>

    <div class="panel-mobile-bottom-actions">
        <a href="<?= BASE_URL ?>/profile" class="panel-mobile-bottom-link">Profilim</a>
        <a href="<?= BASE_URL ?>/logout" class="panel-mobile-bottom-link danger">Çıkış</a>
    </div>

    <div class="panel-mobile-bottom-powered">
        <span>Powered by</span>
        <a href="https://viperadev.com" target="_blank" class="vipera-link">
            <img src="https://viperadev.com/ViperaDev-Logo.png" alt="ViperaDev" class="vipera-logo">
        </a>
    </div>
</div>

<!-- ── Main ── -->
<div class="main">
    <div class="topbar">
        <?php
        $topbarTitle = $currentDept['name'] ?? 'Panel';
        ?>
        <button type="button" class="panel-mobile-menu-btn" onclick="openPanelMenu()" aria-label="Menüyü aç">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="17" height="17" aria-hidden="true">
                <line x1="4" y1="7" x2="20" y2="7"></line>
                <line x1="4" y1="12" x2="20" y2="12"></line>
                <line x1="4" y1="17" x2="20" y2="17"></line>
            </svg>
        </button>
        <?php if (count($allDepts) > 1): ?>
        <button type="button" class="panel-mobile-dept-btn" onclick="openPanelDeptModal()" aria-label="Departman değiştir">
            <i aria-hidden="true"></i>
            <span><?= View::escape($topbarTitle) ?></span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
        <?php endif; ?>
        <h1><?= View::escape($topbarTitle) ?></h1>
        <span class="sep">/</span>
        <span class="sub"><?= View::escape($pageTitle ?? 'Dashboard') ?></span>
        <button onclick="toggleTheme()" class="btn-theme" title="Tema Değiştir" style="margin-left: auto;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
            </svg>
        </button>
    </div>

    <div class="page-content">
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="flash <?= View::escape($_SESSION['flash']['type']) ?>">
                <?= View::escape($_SESSION['flash']['message']) ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </div>
</div>

<?php if (count($allDepts) > 1): ?>
<div class="panel-dept-modal" id="panelDeptModal" aria-hidden="true">
    <div class="panel-dept-sheet" role="dialog" aria-modal="true" aria-label="Departman değiştir">
        <div class="panel-dept-sheet-head">
            <strong>Departman Değiştir</strong>
            <button type="button" class="panel-dept-close" onclick="closePanelDeptModal()" aria-label="Kapat">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="panel-dept-list">
            <?php foreach ($allDepts as $dept): ?>
                <?php
                $deptSlug = $dept['slug'] ?? '';
                $isActiveDept = $deptSlug === ($currentDept['slug'] ?? '');
                $deptColor = $dept['color'] ?? ($currentDept['color'] ?? '#00BCD4');
                $dr = $dept['role'] ?? 'member';
                if ($dr === 'director') {
                    $roleLabel = 'Direktör';
                } elseif ($dr === 'president') {
                    $roleLabel = 'Başkan';
                } elseif ($dr === 'vice_president') {
                    $roleLabel = 'Başkan Yardımcısı';
                } else {
                    $roleLabel = 'Üye';
                }
                ?>
                <a href="<?= BASE_URL ?>/panel/<?= View::escape($deptSlug) ?>"
                   class="panel-dept-option <?= $isActiveDept ? 'active' : '' ?>"
                   style="--dept-color: <?= View::escape($deptColor) ?>">
                    <span class="panel-dept-dot" aria-hidden="true"></span>
                    <span class="panel-dept-name"><?= View::escape($dept['name'] ?? 'Departman') ?></span>
                    <span class="panel-dept-role"><?= View::escape($roleLabel) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
function toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme') || 'dark';
    const next = current === 'light' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
}

function openPanelMenu() {
    closePanelDeptModal();
    document.body.classList.add('panel-menu-open');
}

function closePanelMenu() {
    document.body.classList.remove('panel-menu-open');
}

function toggleSidebarCategory(button) {
    const category = button.closest('[data-nav-category]');
    if (!category) return;

    const isOpen = category.classList.contains('open');
    const isActive = category.classList.contains('active');
    document.querySelectorAll('[data-nav-category]').forEach((item) => {
        if (!item.classList.contains('active')) {
            item.classList.remove('open');
            item.querySelector('.nav-category-toggle')?.setAttribute('aria-expanded', 'false');
        }
    });

    const nextOpen = isActive ? true : !isOpen;
    category.classList.toggle('open', nextOpen);
    button.setAttribute('aria-expanded', String(nextOpen));
}

// Global Modal Functions
function openModal(id) {
    const m = document.getElementById(id);
    if(m) {
        m.style.display = 'flex';
        // Force reflow for animation
        m.offsetHeight; 
        m.classList.add('show');
    }
}

function closeModal(id) {
    const m = document.getElementById(id);
    if(m) {
        m.classList.remove('show');
        setTimeout(() => {
            m.style.display = 'none';
        }, 300); // Wait for transition
    }
}

function toggleModal(id) {
    const m = document.getElementById(id);
    if(m) {
        if(m.classList.contains('show') || m.style.display === 'flex') {
            closeModal(id);
        } else {
            openModal(id);
        }
    }
}

// Close modals when clicking outside
window.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal') || e.target.classList.contains('modal-overlay')) {
        closeModal(e.target.id);
    }
});

function openPanelDeptModal() {
    const modal = document.getElementById('panelDeptModal');
    if (!modal) return;
    closePanelMenu();
    modal.classList.add('show');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
}

function closePanelDeptModal() {
    const modal = document.getElementById('panelDeptModal');
    if (!modal) return;
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
}

const panelDeptModal = document.getElementById('panelDeptModal');
if (panelDeptModal) {
    panelDeptModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closePanelDeptModal();
        }
    });
}

// Sidebar Search Logic
const appSearchInput = document.getElementById('appSearchInput');
if (appSearchInput) {
    appSearchInput.addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        
        document.querySelectorAll('.sidebar-nav > .nav-link').forEach(link => {
            if (link.textContent.toLowerCase().includes(term)) {
                link.style.display = '';
            } else {
                link.style.display = 'none';
            }
        });
        
        document.querySelectorAll('.sidebar-nav .nav-category').forEach(category => {
            const links = category.querySelectorAll('.nav-link');
            let hasVisibleLink = false;
            
            links.forEach(link => {
                if (link.textContent.toLowerCase().includes(term)) {
                    link.style.display = '';
                    hasVisibleLink = true;
                } else {
                    link.style.display = 'none';
                }
            });
            
            if (hasVisibleLink || term === '') {
                category.style.display = '';
                if (term !== '') {
                    category.classList.add('open');
                } else if (!category.classList.contains('active')) {
                    category.classList.remove('open');
                }
            } else {
                category.style.display = 'none';
            }
        });
    });
}

window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePanelMenu();
        closePanelDeptModal();
    }
});
</script>
</body>
</html>

