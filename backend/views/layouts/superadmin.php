<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Süper Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            --accent:   #00BCD4;
            --accent-h: #00ACC1;
            --text:     #e8e8e8;
            --muted:    #555;
            --danger:   #ef4444;
            --success:  #22c55e;
            --warning:  #f59e0b;
            --sidebar-w: 220px;
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

    </style>
</head>
<body>

<!-- ── Sidebar ── -->
<aside class="sidebar">
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
                    $userName = Auth::user()['name'] ?? 'Admin';
                    $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($userName) . "&background=random&color=fff&size=128";
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
            <a href="https://viperadev.com" target="_blank" style="display: flex; align-items: center;">
                <img src="https://viperadev.com/ViperaDev-Logo.png" alt="ViperaDev" style="height: 18px; opacity: 0.7; filter: brightness(0) invert(1); transition: all 0.3s;" onmouseover="this.style.opacity='1'; this.style.filter='none';" onmouseout="this.style.opacity='0.7'; this.style.filter='brightness(0) invert(1)';">
            </a>
        </div>
    </div>
</aside>

<!-- ── Main Content ── -->
<div class="main">
    <div class="topbar">
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

<script>
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
</script>
</body>
</html>
