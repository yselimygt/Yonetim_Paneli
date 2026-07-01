<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — <?= View::escape($currentDept['name'] ?? 'Panel') ?></title>
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
            --accent:   <?= View::escape($currentDept['color'] ?? '#00BCD4') ?>;
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
        $renderedModules = [];
        // Mevcut kullanıcının departmandaki rolünü kontrol et
        $isDirectorOrAdmin = Auth::isSuperAdmin() || (($userRole ?? 'member') === 'director');

        foreach ($deptApps as $app): 
            $modGroup = $app['module_type'];
            
            // Eğer modül passwords ise ve kullanıcı director veya super admin değilse atla
            if ($modGroup === 'passwords' && !$isDirectorOrAdmin) {
                continue;
            }

            // Bireysel formları ayıklamak için slug kontrolü
            // Ana form uygulamasının slug'ı her zaman 'form' veya 'form_manager' dır.
            // Eğer slug farklıysa (örn. gfhx-1781094217) bu bireysel oluşturulmuş bir formdur.
            if ($app['module_type'] === 'form' && !in_array($app['slug'], ['form', 'form_manager'])) {
                continue;
            }

            if (in_array($modGroup, ['form', 'form_manager'])) $modGroup = 'form_grouped';

            if (in_array($modGroup, $renderedModules)) continue;
            $renderedModules[] = $modGroup;
        ?>
            <?php
            // Setup routing and highlighting based on module type
            $href = BASE_URL . "/panel/" . View::escape($currentDept['slug']) . "/apps/" . View::escape($app['slug']);
            $isActive = ($activeApp ?? '') === $app['slug'];
            
            if (in_array($app['module_type'], ['form', 'form_manager'])) {
                $href = BASE_URL . "/panel/" . View::escape($currentDept['slug']) . "/forms";
                $isActive = ($activeSection ?? '') === 'forms';
            } elseif ($app['module_type'] === 'finance') {
                $href = BASE_URL . "/panel/" . View::escape($currentDept['slug']) . "/finance";
                $isActive = ($activeSection ?? '') === 'finance';
            } elseif ($app['module_type'] === 'dues') {
                $href = BASE_URL . "/panel/" . View::escape($currentDept['slug']) . "/dues";
                $isActive = ($activeSection ?? '') === 'dues';
            }

            // Icons
            $svgPath = '<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>';
            if (in_array($app['icon'], ['form', 'file-text'])) $svgPath = '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>';
            if ($app['icon'] === 'calendar') $svgPath = '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>';
            if ($app['icon'] === 'check-square') $svgPath = '<polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>';
            if ($app['icon'] === 'megaphone') $svgPath = '<path d="M22 17H2a3 3 0 000 3h20a3 3 0 000-3zM22 11H2M22 5H2"/>';
            if ($app['icon'] === 'bar-chart') $svgPath = '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>';
            if ($app['icon'] === 'users') $svgPath = '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>';
            if ($app['icon'] === 'pie-chart') $svgPath = '<path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path>';
            if ($app['icon'] === 'credit-card') $svgPath = '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line>';
            if ($app['icon'] === 'map-pin') $svgPath = '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>';
            if ($app['icon'] === 'book-open') $svgPath = '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>';
            if ($app['icon'] === 'award') $svgPath = '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>';
            if ($app['icon'] === 'archive') $svgPath = '<polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line>';
            if ($app['icon'] === 'lock') $svgPath = '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>';
            if ($app['icon'] === 'scissors') $svgPath = '<circle cx="6" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><line x1="20" y1="4" x2="8.12" y2="15.88"></line><line x1="14.47" y1="14.48" x2="20" y2="20"></line><line x1="8.12" y1="8.12" x2="12" y2="12"></line>';
            if ($app['icon'] === 'qr') $svgPath = '<rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>';
            if ($app['icon'] === 'link') $svgPath = '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>';
            if ($app['icon'] === 'volume-2') $svgPath = '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"/>';
            if ($app['icon'] === 'flag') $svgPath = '<path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/>';
            if ($app['icon'] === 'target') $svgPath = '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>';
            if ($app['icon'] === 'layers') $svgPath = '<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 12 12 17 22 12"/><polyline points="2 17 12 22 22 17"/>';
            if ($app['icon'] === 'video') $svgPath = '<polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>';
            if ($app['icon'] === 'user-check') $svgPath = '<path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><polyline points="17 11 19 13 23 9"/>';
            if ($app['icon'] === 'share-2') $svgPath = '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>';
            if ($app['icon'] === 'compass') $svgPath = '<circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>';
            if ($app['icon'] === 'folder') $svgPath = '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>';
            if ($app['icon'] === 'lightbulb') $svgPath = '<line x1="9" y1="18" x2="15" y2="18"></line><line x1="10" y1="22" x2="14" y2="22"></line><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A6 6 0 1 0 5.5 8c0 1.5.6 2.89 1.62 3.84c.64.6 1.05 1.34 1.25 2.16"></path>';
            if ($app['icon'] === 'layout') $svgPath = '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line>';
            if ($app['icon'] === 'shield') $svgPath = '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>';
            if ($app['icon'] === 'feather') $svgPath = '<path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16" y1="8" x2="2" y2="22"/><line x1="17.5" y1="15" x2="9" y2="15"/>';
            if ($app['icon'] === 'navigation') $svgPath = '<polygon points="3 11 22 2 13 21 11 13 3 11"/>';
            if ($app['icon'] === 'gift') $svgPath = '<polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>';
            ?>
            <a href="<?= $href ?>" class="nav-link <?= $isActive ? 'active' : '' ?>">
                <span class="icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="15" height="15">
                        <?= $svgPath ?>
                    </svg>
                </span>
                <?= View::escape($app['module_type'] === 'form_manager' ? 'Form' : $app['name']) ?>
            </a>
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
                    $userName = Auth::user()['name'] ?? 'Admin';
                    $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($userName) . "&background=random&color=fff&size=128";
                ?>
                <img src="<?= $avatarUrl ?>" alt="Avatar" class="sidebar-avatar">
                <span class="sidebar-user-name"><?= View::escape($userName) ?></span>
            </div>
            <span class="role-badge <?= ($userRole ?? 'member') === 'director' ? 'role-director' : 'role-member' ?>">
                <?= ($userRole ?? 'member') === 'director' ? 'Direktör' : 'Üye' ?>
            </span>
        </div>
        <a href="<?= BASE_URL ?>/profile" class="btn-logout" style="margin-bottom:6px;">Profilim</a>
        <a href="<?= BASE_URL ?>/logout" class="btn-logout">Çıkış</a>
        <div style="font-size: 11px; text-align: center; color: var(--muted); margin-top: 12px; display: flex; align-items: center; justify-content: center; gap: 4px;">
            <span>Powered by</span>
            <a href="https://viperadev.com" target="_blank" style="display: flex; align-items: center;">
                <img src="https://viperadev.com/ViperaDev-Logo.png" alt="ViperaDev" style="height: 18px; opacity: 0.7; filter: brightness(0) invert(1); transition: all 0.3s;" onmouseover="this.style.opacity='1'; this.style.filter='none';" onmouseout="this.style.opacity='0.7'; this.style.filter='brightness(0) invert(1)';">
            </a>
        </div>
    </div>
</aside>

<!-- ── Main ── -->
<div class="main">
    <div class="topbar">
        <?php
        $topbarTitle = $currentDept['name'] ?? 'Panel';
        ?>
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

<script>
function toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme') || 'dark';
    const next = current === 'light' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
}
</script>
</body>
</html>
