<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Süper Admin</title>
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
            --accent:   <?= View::escape($_SESSION['user_theme_color'] ?? '#00BCD4') ?>;
            --accent-h: <?= View::escape($_SESSION['user_theme_color'] ?? '#00ACC1') ?>;
            --text:     #e8e8e8;
            --muted:    #555;
            --danger:   #ef4444;
            --success:  #22c55e;
            --warning:  #f59e0b;
            --sidebar-w: 220px;
            --input-bg: #0d0d0d;
            --card-alt: #151515;
            --modal-overlay: rgba(0,0,0,0.85);
            --mobile-page-x: 14px;
            --mobile-card-radius: 12px;
            --mobile-control-h: 44px;
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
            overflow-x: hidden;
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

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 90;
            background: rgba(0,0,0,0.58);
            border: 0;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            background: linear-gradient(180deg, rgba(255,255,255,0.02) 0%, transparent 100%);
        }

        .sidebar-brand h2 {
            font-size: 16px;
            font-weight: 700;
            color: var(--accent);
            letter-spacing: -0.2px;
        }

        .sidebar-brand span {
            display: block;
            font-size: 11px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
            font-weight: 600;
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

        .nav-link svg { 
            width: 18px; 
            height: 18px; 
            flex-shrink: 0;
            transition: transform 0.2s;
        }

        .nav-link:hover {
            opacity: 1;
            background: rgba(255,255,255,0.05);
            transform: translateX(4px);
        }

        .nav-link:hover svg {
            transform: scale(1.1);
        }

        .nav-link.active {
            opacity: 1;
            color: var(--accent);
            background: rgba(255,255,255, 0.05);
            font-weight: 600;
            box-shadow: inset 4px 0 0 var(--accent);
        }

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
        .role-superadmin { background: rgba(16,185,129,.15); color: #34d399; }

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
            height: 52px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 12px;
            background: var(--bg);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .mobile-menu-btn {
            display: none;
            width: 40px;
            height: 40px;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--card);
            color: var(--text);
            cursor: pointer;
        }

        .topbar h1 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
        }

        .topbar .breadcrumb {
            font-size: 12px;
            color: var(--muted);
            margin-left: auto;
        }

        .page-content {
            padding: 28px;
            flex: 1;
        }

        /* ── Genel Bileşenler ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-header h2 {
            font-size: 18px;
            font-weight: 600;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            transition: box-shadow 0.3s ease;
        }
        [data-theme="dark"] .card {
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }

        .card-body { padding: 20px 24px; }
        .card-header {
            padding: 14px 24px;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Stat cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 14px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 20px;
        }

        .stat-card .value {
            font-size: 30px;
            font-weight: 700;
            color: var(--accent);
            line-height: 1;
            margin-bottom: 6px;
        }

        .stat-card .label {
            font-size: 12px;
            color: var(--muted);
        }

        /* Table */
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { padding: 10px 16px; text-align: left; font-size: 11px; font-weight: 500; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border); }
        td { padding: 12px 16px; border-bottom: 1px solid rgba(255,255,255,.04); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,.015); }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
        }
        .badge-active  { background:rgba(34,197,94,.12);  color:#86efac; }
        .badge-inactive{ background:rgba(100,100,100,.12); color:#666; }
        .badge-director{ background:rgba(245,158,11,.12); color:#fcd34d; }
        .badge-president{ background:rgba(234,179,8,.12); color:#eab308; }
        .badge-vice-president{ background:rgba(250,204,21,.12); color:#facc15; }
        .badge-member  { background:rgba(0,188,212,.1);   color:#67e8f9; }
        .badge-mgmt    { background:rgba(245,158,11,.15); color:#fbbf24; border:1px solid rgba(245,158,11,.3);}

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all .2s ease;
        }
        .btn:active { transform: scale(0.96); }
        .btn-primary { background: var(--accent); color: #0a0a0a; box-shadow: 0 4px 14px rgba(0, 188, 212, 0.3); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0, 188, 212, 0.4); opacity: 0.9; }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text); }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .btn-danger  { background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.3); color: #fca5a5; }
        .btn-danger:hover { background: rgba(239,68,68,.2); }
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

        .mobile-bottom-menu {
            display: none;
        }

        /* Forms */
        .form-group { margin-bottom: 20px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-label { display: block; font-size: 11px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 8px; }
        .form-control {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 14px;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            outline: none;
            transition: all .2s ease;
        }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.15); }
        select.form-control { cursor: pointer; }
        textarea.form-control { resize: vertical; min-height: 90px; }

        .form-hint { font-size: 11px; color: var(--muted); margin-top: 5px; }

        /* Checkbox */
        .check-group { display: flex; align-items: center; gap: 8px; }
        .check-group input { accent-color: var(--accent); width: 15px; height: 15px; }
        .check-group label { font-size: 13px; color: var(--text); cursor: pointer; }

        /* Flash */
        .flash { border-radius: 8px; padding: 11px 16px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
        .flash.error   { background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.3); color:#fca5a5; }
        .flash.success { background:rgba(34,197,94,.1); border:1px solid rgba(34,197,94,.3); color:#86efac; }
        .flash.warning { background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:#fcd34d; }

        /* Color dot */
        .color-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }

        /* Actions */
        .actions { display: flex; gap: 6px; }

        /* Empty state */
        .empty-state { text-align: center; padding: 60px 20px; color: var(--muted); }
        .empty-state p { font-size: 13px; margin-top: 8px; }

        /* Modals (Global) */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: var(--modal-overlay);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        
        .modal.show {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            width: 100%;
            max-width: 500px;
            padding: 28px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            transform: scale(0.95) translateY(10px);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }
        
        .modal.show .modal-content {
            transform: scale(1) translateY(0);
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .modal-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            margin: 0;
        }

        .modal-close {
            background: transparent;
            border: none;
            color: var(--muted);
            font-size: 24px;
            line-height: 1;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .modal-close:hover {
            color: var(--text);
        }
        
        /* Compatibility for older modal-overlay class */
        .modal-overlay {
            display: none;
            position: fixed; inset: 0; background: var(--modal-overlay); backdrop-filter: blur(4px);
            z-index: 1000; align-items: center; justify-content: center; transition: opacity 0.3s ease;
            opacity: 0;
        }
        .modal-overlay.show { display: flex; opacity: 1; }
        .modal-overlay .modal-content {
            transform: scale(0.95) translateY(10px);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .modal-overlay.show .modal-content { transform: scale(1) translateY(0); }

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
            }

            body.sidebar-open {
                overflow: hidden;
            }

            .sidebar {
                width: min(84vw, 300px);
                transform: translateX(-100%);
                transition: transform 0.24s ease;
                box-shadow: none;
            }

            .sidebar.is-open {
                transform: translateX(0);
            }

            .sidebar-overlay.is-open {
                display: block;
                opacity: 1;
            }

            .sidebar-brand {
                padding: 18px 16px 14px;
                background: transparent;
            }

            .sidebar-brand h2 {
                font-size: 15px;
            }

            .sidebar-brand span {
                font-size: 10px;
                letter-spacing: .08em;
            }

            .sidebar-nav {
                padding: 10px;
            }

            .nav-section {
                padding: 14px 10px 6px;
                font-size: 9px;
                letter-spacing: .12em;
            }

            .nav-link {
                min-height: var(--mobile-control-h);
                margin-bottom: 2px;
                padding: 11px 12px;
                border-radius: 10px;
                opacity: .88;
            }

            .nav-link:hover {
                transform: none;
            }

            .nav-link:hover svg {
                transform: none;
            }

            .nav-link.active {
                background: rgba(0,188,212,.1);
                box-shadow: inset 3px 0 0 var(--accent);
            }

            .sidebar-footer {
                display: none;
            }

            .mobile-bottom-menu {
                position: fixed;
                left: 10px;
                right: 10px;
                bottom: calc(8px + env(safe-area-inset-bottom));
                z-index: 80;
                display: grid;
                grid-template-columns: minmax(0, 1fr) 64px minmax(0, 1fr);
                align-items: center;
                gap: 8px;
                min-height: 64px;
                padding: 8px 10px;
                border: 1px solid rgba(0,188,212,.26);
                border-radius: 18px;
                background:
                    linear-gradient(135deg, rgba(0,188,212,.16), rgba(15,23,42,.86) 42%, rgba(16,185,129,.11)),
                    color-mix(in srgb, var(--sidebar) 78%, #0f172a 22%);
                box-shadow:
                    0 16px 38px rgba(0,0,0,.42),
                    0 0 0 1px rgba(255,255,255,.035) inset,
                    0 -10px 28px rgba(0,188,212,.08);
                backdrop-filter: blur(18px);
                -webkit-backdrop-filter: blur(18px);
            }

            body.sidebar-open .mobile-bottom-menu {
                opacity: 0;
                pointer-events: none;
            }

            .mobile-bottom-user {
                display: flex;
                align-items: center;
                gap: 8px;
                min-width: 0;
            }

            .mobile-bottom-avatar {
                width: 30px;
                height: 30px;
                border-radius: 50%;
                object-fit: cover;
                border: 1px solid rgba(255,255,255,.1);
                flex: 0 0 auto;
            }

            .mobile-bottom-name {
                display: block;
                color: var(--text);
                font-size: 11px;
                font-weight: 700;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .mobile-bottom-role {
                display: inline-flex;
                margin-top: 3px;
                padding: 2px 6px;
                border-radius: 999px;
                background: rgba(16,185,129,.15);
                color: #34d399;
                font-size: 9px;
                font-weight: 800;
            }

            .mobile-bottom-logo {
                position: relative;
                justify-self: center;
                align-self: start;
                width: 58px;
                height: 58px;
                margin-top: -30px;
                border-radius: 50%;
                display: grid;
                place-items: center;
                border: 4px solid var(--bg);
                background:
                    radial-gradient(circle at 35% 28%, rgba(255,255,255,.18), transparent 34%),
                    var(--accent);
                color: #071112;
                font-size: 13px;
                font-weight: 900;
                box-shadow: 0 12px 28px rgba(0,188,212,.24);
                overflow: hidden;
            }

            .mobile-bottom-logo img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .mobile-bottom-actions {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 5px;
                min-width: 0;
            }

            .mobile-bottom-link {
                min-height: 30px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 1px solid rgba(0,188,212,.2);
                border-radius: 9px;
                background: rgba(6,182,212,.08);
                color: var(--text);
                text-decoration: none;
                font-size: 10px;
                font-weight: 700;
                white-space: nowrap;
            }

            .mobile-bottom-link.danger {
                color: #fca5a5;
                border-color: rgba(239,68,68,.22);
                background: rgba(239,68,68,.08);
            }

            .mobile-bottom-powered {
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

            .mobile-bottom-powered img {
                height: 12px;
                opacity: .72;
                
            }

            [data-theme="light"] .mobile-bottom-menu {
                border-color: rgba(0,188,212,.2);
                background:
                    linear-gradient(135deg, rgba(0,188,212,.12), rgba(255,255,255,.94) 42%, rgba(16,185,129,.08)),
                    rgba(255,255,255,.92);
                box-shadow:
                    0 14px 32px rgba(15,23,42,.14),
                    0 0 0 1px rgba(255,255,255,.72) inset,
                    0 -8px 22px rgba(0,188,212,.06);
            }

            [data-theme="light"] .mobile-bottom-avatar {
                border-color: rgba(15,23,42,.1);
            }

            [data-theme="light"] .mobile-bottom-name {
                color: #0f172a;
            }

            [data-theme="light"] .mobile-bottom-role {
                background: rgba(5,150,105,.12);
                color: #047857;
            }

            [data-theme="light"] .mobile-bottom-logo {
                border-color: #f3f4f6;
                color: #06343b;
                box-shadow: 0 12px 26px rgba(0,188,212,.18);
            }

            [data-theme="light"] .mobile-bottom-link {
                border-color: rgba(0,188,212,.22);
                background: rgba(0,188,212,.08);
                color: #0f172a;
            }

            [data-theme="light"] .mobile-bottom-link.danger {
                border-color: rgba(220,38,38,.18);
                background: rgba(220,38,38,.08);
                color: #b91c1c;
            }

            [data-theme="light"] .mobile-bottom-powered {
                color: #64748b;
            }

            [data-theme="light"] .mobile-bottom-powered img {
                opacity: .72;
                }

            .sidebar-user {
                align-items: flex-start;
                gap: 10px;
            }

            .main {
                width: 100%;
                margin-left: 0;
                min-width: 0;
                padding-bottom: 92px;
            }

            .topbar {
                min-height: 56px;
                height: auto;
                padding: 8px var(--mobile-page-x);
                backdrop-filter: blur(14px);
                -webkit-backdrop-filter: blur(14px);
                background: color-mix(in srgb, var(--bg) 92%, transparent);
            }

            .mobile-menu-btn {
                display: inline-flex;
                min-width: var(--mobile-control-h);
                min-height: var(--mobile-control-h);
                border-radius: 12px;
                background: transparent;
            }

            .topbar h1 {
                min-width: 0;
                flex: 1;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .topbar .breadcrumb {
                display: none;
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

            .panel-dept-sheet {
                box-shadow: none;
            }

            .btn-theme {
                width: var(--mobile-control-h);
                height: var(--mobile-control-h);
                margin-left: 0;
                flex: 0 0 auto;
                border-radius: 12px;
            }

            .page-content {
                padding: 16px var(--mobile-page-x) 28px;
            }

            .page-header,
            .card-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 10px;
            }

            .page-header {
                margin-bottom: 16px;
            }

            .page-header h2 {
                font-size: 20px;
                line-height: 1.22;
                overflow-wrap: anywhere;
            }

            .page-header .btn,
            .card-header .btn {
                width: 100%;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px;
                margin-bottom: 18px;
            }

            .stat-card {
                padding: 14px;
                border-radius: var(--mobile-card-radius);
            }

            .stat-card .value {
                font-size: 22px;
            }

            .card {
                border-radius: var(--mobile-card-radius);
                box-shadow: none !important;
                overflow: visible;
            }

            .card-body {
                padding: 16px;
            }

            .card-header {
                padding: 13px 16px;
            }

            table {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            thead,
            tbody,
            tr {
                width: max-content;
                min-width: 100%;
            }

            th,
            td {
                white-space: nowrap;
            }

            td {
                max-width: 240px;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            td:last-child,
            td:has(.actions) {
                max-width: none;
                overflow: visible;
            }

            .actions {
                flex-wrap: wrap;
                justify-content: flex-start;
                gap: 8px;
            }

            .actions .btn,
            .actions form {
                flex: 1 1 auto;
            }

            .actions form .btn {
                width: 100%;
                justify-content: center;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .form-group {
                margin-bottom: 16px;
            }

            .form-control {
                min-height: var(--mobile-control-h);
                border-radius: 10px;
            }

            .check-group {
                align-items: flex-start;
                min-height: var(--mobile-control-h);
            }

            .modal,
            .modal-overlay {
                align-items: flex-start;
                justify-content: center;
                padding: 12px;
                overflow-y: auto;
            }

            .modal-content,
            .modal-overlay .modal-content {
                width: min(100%, calc(100vw - 24px));
                max-width: none !important;
                max-height: calc(100dvh - 24px);
                overflow-y: auto;
                padding: 18px;
                border-radius: var(--mobile-card-radius);
                box-shadow: none;
            }

            .modal-header {
                gap: 10px;
                margin-bottom: 18px;
            }

            .modal-header h3 {
                line-height: 1.25;
                overflow-wrap: anywhere;
            }

            .modal-close {
                min-width: 40px;
                min-height: 40px;
                justify-content: center;
            }

            .settings-tabs {
                display: flex !important;
                gap: 8px !important;
                overflow-x: auto !important;
                padding-bottom: 6px !important;
                -webkit-overflow-scrolling: touch;
            }

            .settings-tabs .tab-btn,
            .tab-btn {
                flex: 0 0 auto !important;
                min-height: 44px !important;
                white-space: nowrap !important;
            }

            .dynamic-item,
            .routing-rule {
                padding: 16px !important;
            }

            .item-header {
                align-items: flex-start !important;
                flex-direction: column !important;
                gap: 10px !important;
            }

            .item-header .form-control {
                width: 100% !important;
            }

            .sponsors-grid {
                grid-template-columns: 1fr !important;
            }

            .gallery-layout,
            .app-assign-container,
            .app-container,
            .dashboard-panels-grid {
                display: flex !important;
                flex-direction: column !important;
                height: auto !important;
                min-height: 0 !important;
            }

            .gallery-sidebar,
            .dept-sidebar,
            .app-list-pane {
                width: 100% !important;
                flex: none !important;
                max-height: none !important;
            }

            .folder-list {
                display: flex !important;
                gap: 8px !important;
                overflow-x: auto !important;
                padding: 12px !important;
            }

            .folder-item {
                min-width: max-content !important;
                margin-bottom: 0 !important;
                transform: none !important;
            }

            .gallery-content,
            .app-detail-pane,
            .main-content {
                width: 100% !important;
                min-width: 0 !important;
            }

            .gallery-header-sticky,
            .app-detail-header,
            .assign-header {
                align-items: flex-start !important;
                flex-direction: column !important;
                gap: 12px !important;
                padding: 16px !important;
            }

            .image-grid,
            .modules-grid,
            .detail-grid,
            .profile-modal-grid {
                grid-template-columns: 1fr !important;
                padding: 16px !important;
            }

            .app-list-header .d-flex,
            .app-detail-header .d-flex,
            .base-info-row,
            .save-bar,
            .profile-modal-footer,
            .profile-history-item {
                align-items: stretch !important;
                flex-direction: column !important;
                gap: 10px !important;
            }

            .base-info-row {
                padding: 16px !important;
            }

            .info-item {
                min-width: 0 !important;
            }

            .info-item-text,
            .info-item-text div:last-child,
            .json-field p,
            .team-sub,
            .app-sender {
                white-space: normal !important;
                overflow-wrap: anywhere !important;
                max-width: 100% !important;
            }

            .profile-modal-card {
                width: min(100%, calc(100vw - 24px)) !important;
            }

            .profile-modal-head,
            .profile-modal-title,
            .team-group-head,
            .team-member,
            .team-person {
                align-items: flex-start !important;
                flex-direction: column !important;
                gap: 12px !important;
            }

            .team-actions,
            .team-actions .btn,
            .profile-modal-footer .btn,
            .save-bar .btn {
                width: 100% !important;
                justify-content: center !important;
            }

            .logs-mobile-card,
            .application-mobile-card {
                min-width: 0 !important;
            }
        }

        @media (max-width: 520px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .btn {
                min-height: 44px;
                justify-content: center;
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
<button type="button" class="sidebar-overlay" id="sidebarOverlay" aria-label="Menüyü kapat" onclick="closeSidebar()"></button>
<aside class="sidebar" id="superadminSidebar">
    <div class="sidebar-brand">
        <h2><?= APP_NAME ?></h2>
        <span>Süper Admin</span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Genel</div>
        <a href="<?= BASE_URL ?>/superadmin"
           class="nav-link <?= (str_ends_with($_SERVER['REQUEST_URI'], '/superadmin')) ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            Dashboard
        </a>

        <div class="nav-section">Yönetim</div>
        <a href="<?= BASE_URL ?>/superadmin/departments"
           class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/departments') ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/>
            </svg>
            Departmanlar
        </a>
        <a href="<?= BASE_URL ?>/superadmin/users"
           class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/users') ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
            </svg>
            Kullanıcılar
        </a>

        <div class="nav-section">Kariyer & İK</div>
        <a href="<?= BASE_URL ?>/superadmin/applications"
           class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/applications') ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
            </svg>
            Başvurular
        </a>

        <div class="nav-section">Web Sitesi</div>
        <a href="<?= BASE_URL ?>/superadmin/site-events"
           class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/site-events') ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            Etkinlikler
        </a>

        <a href="<?= BASE_URL ?>/superadmin/site-projects"
           class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/site-projects') ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
            </svg>
            Projeler
        </a>

        <a href="<?= BASE_URL ?>/superadmin/site-gallery"
           class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/site-gallery') ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                <polyline points="21 15 16 10 5 21"></polyline>
            </svg>
            Galeri
        </a>

        <a href="<?= BASE_URL ?>/superadmin/site-team"
           class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/site-team') ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            Ekip (Board)
        </a>

        <a href="<?= BASE_URL ?>/superadmin/site-history"
           class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/site-history') ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            Tarihçe & Başarılar
        </a>

        <a href="<?= BASE_URL ?>/superadmin/site-settings"
           class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/site-settings') ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
            Site Ayarları
        </a>

        <div class="nav-section">Sistem Ayarları</div>
        <a href="<?= BASE_URL ?>/superadmin/apps"
           class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/apps') ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
            Uygulama Ata
        </a>
        <a href="<?= BASE_URL ?>/superadmin/logs"
           class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/logs') ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            Sistem Logları
        </a>

        <div class="nav-section">Kısayollar</div>
        <a href="<?= BASE_URL ?>/panel" class="nav-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M4 19V5a2 2 0 012-2h12a2 2 0 012 2v14"/><path d="M12 22v-8"/><path d="M9 22h6"/>
            </svg>
            Departman Panelleri
        </a>
    </nav>

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
            <span class="role-badge role-superadmin">Süper Admin</span>
        </div>
        <a href="<?= BASE_URL ?>/profile" class="btn-logout" style="margin-bottom:6px;">Profilim</a>
        <a href="<?= BASE_URL ?>/logout" class="btn-logout">Çıkış Yap</a>
        <div style="font-size: 11px; text-align: center; color: var(--muted); margin-top: 12px; display: flex; align-items: center; justify-content: center; gap: 4px;">
            <span>Powered by</span>
            <a href="https://viperadev.com" target="_blank" class="vipera-link">
                <img src="https://viperadev.com/ViperaDev-Logo.png" alt="ViperaDev" class="vipera-logo">
            </a>
        </div>
    </div>
</aside>

<div class="mobile-bottom-menu" aria-label="Mobil hesap menüsü">
    <div class="mobile-bottom-user">
        <img src="<?= $avatarUrl ?>" alt="Avatar" class="mobile-bottom-avatar">
        <div style="min-width:0;">
            <span class="mobile-bottom-name"><?= View::escape($userName) ?></span>
            <span class="mobile-bottom-role">Süper Admin</span>
        </div>
    </div>

    <div class="mobile-bottom-logo" aria-label="Logo alanı">IA</div>

    <div class="mobile-bottom-actions">
        <a href="<?= BASE_URL ?>/profile" class="mobile-bottom-link">Profilim</a>
        <a href="<?= BASE_URL ?>/logout" class="mobile-bottom-link danger">Çıkış</a>
    </div>

    <div class="mobile-bottom-powered">
        <span>Powered by</span>
        <a href="https://viperadev.com" target="_blank" class="vipera-link">
            <img src="https://viperadev.com/ViperaDev-Logo.png" alt="ViperaDev" class="vipera-logo">
        </a>
    </div>
</div>

<!-- ── Main Content ── -->
<div class="main">
    <div class="topbar">
        <button type="button" class="mobile-menu-btn" aria-label="Menüyü aç" aria-controls="superadminSidebar" onclick="openSidebar()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <?php $allDepts = Auth::getDepartments(); ?>
        <?php if (count($allDepts) > 0): ?>
        <button type="button" class="panel-mobile-dept-btn" aria-label="Departman değiştir" onclick="openPanelDeptModal()">
            <span>Departman Seç</span>
            <i></i>
        </button>
        <?php endif; ?>
        <h1><?= $pageTitle ?? 'Dashboard' ?></h1>
        <span class="breadcrumb">Süper Admin</span>
        <button onclick="toggleTheme()" class="btn-theme" title="Tema Değiştir">
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

<?php if (isset($allDepts) && count($allDepts) > 0): ?>
<div class="panel-dept-modal" id="panelDeptModal" aria-hidden="true">
    <div class="panel-dept-sheet" role="dialog" aria-modal="true" aria-label="Departman değiştir">
        <div class="panel-dept-sheet-head">
            <strong>Departman Seç</strong>
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
                $deptColor = $dept['color'] ?? '#00BCD4';
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
                   class="panel-dept-option"
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
function openPanelDeptModal() {
    closeSidebar();
    const modal = document.getElementById('panelDeptModal');
    if(modal) {
        modal.style.display = 'flex';
        modal.offsetHeight; // trigger reflow
        modal.classList.add('show');
    }
}

function closePanelDeptModal() {
    const modal = document.getElementById('panelDeptModal');
    if(modal) {
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }
}

function openSidebar() {
    document.getElementById('superadminSidebar')?.classList.add('is-open');
    document.getElementById('sidebarOverlay')?.classList.add('is-open');
    document.body.classList.add('sidebar-open');
}

function closeSidebar() {
    document.getElementById('superadminSidebar')?.classList.remove('is-open');
    document.getElementById('sidebarOverlay')?.classList.remove('is-open');
    document.body.classList.remove('sidebar-open');
}

function toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme') || 'dark';
    const next = current === 'light' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
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

window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSidebar();
    }
});
</script>
</body>
</html>

