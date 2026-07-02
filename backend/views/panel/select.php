<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Departman Seç</title>
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
            --card:     #111111;
            --border:   #1e1e1e;
            --text:     #e8e8e8;
            --muted:    #555;
            --sa-card:  #1a150b;
            --bottom-bg: #151515;
            --bottom-border: #2a2a2a;
        }

        [data-theme="light"] {
            --bg:       #f3f4f6;
            --card:     #ffffff;
            --border:   #e5e7eb;
            --text:     #111827;
            --muted:    #6b7280;
            --sa-card:  #fffbeb;
            --bottom-bg: #ffffff;
            --bottom-border: #e5e7eb;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .header { text-align: center; margin-bottom: 28px; width: 100%; max-width: 700px; }
        .header h1 { font-size: 18px; font-weight: 700; color: var(--text); letter-spacing: -.01em; }
        .header p { font-size: 13px; color: var(--muted); margin: 7px auto 0; max-width: 330px; line-height: 1.35; }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 14px;
            width: 100%;
            max-width: 700px;
        }

        .dept-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 22px 20px;
            text-decoration: none;
            color: var(--text);
            transition: border-color .2s, transform .15s;
            display: flex;
            flex-direction: column;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .dept-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 3px;
            background: var(--c);
        }

        .dept-card:hover {
            border-color: var(--c);
            transform: translateY(-2px);
        }

        .dept-card .dept-name { font-size: 14px; font-weight: 600; }
        .dept-card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .dept-card-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .dept-card .dept-role {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
            width: fit-content;
        }
        .dept-card .dept-desc { font-size: 12px; color: var(--muted); line-height: 1.5; margin-top: 2px; }

        .mgmt-badge {
            position: absolute; top: 14px; right: 14px;
            font-size: 9px; font-weight: 600;
            background: rgba(245,158,11,.15); color: #fbbf24;
            border: 1px solid rgba(245,158,11,.3);
            border-radius: 3px; padding: 2px 6px;
            letter-spacing: 0.5px; text-transform: uppercase;
        }

        .footer-links {
            display: flex;
            gap: 20px;
            margin-top: 32px;
        }

        .footer-links a {
            font-size: 12px;
            color: var(--muted);
            text-decoration: none;
            transition: color .15s;
            cursor: pointer;
        }
        .footer-links a:hover { color: var(--text); }

        .footer-mark {
            display: none;
        }

        @media (max-width: 640px) {
            body {
                min-height: 100dvh;
                justify-content: flex-start;
                padding: max(28px, env(safe-area-inset-top)) 18px calc(96px + env(safe-area-inset-bottom));
                background:
                    radial-gradient(circle at top, rgba(255,255,255,.035), transparent 34%),
                    var(--bg);
            }

            .header {
                margin: 112px auto 24px;
            }

            .header h1 {
                font-size: 17px;
            }

            .header p {
                font-size: 12px;
                color: color-mix(in srgb, var(--muted) 86%, var(--text));
            }

            .grid {
                display: flex;
                flex-direction: column;
                gap: 12px;
                max-width: 360px;
            }

            .dept-card {
                min-height: 96px;
                padding: 17px 18px;
                border-radius: 11px;
                border-color: color-mix(in srgb, var(--c) 34%, var(--border));
                background:
                    linear-gradient(90deg, color-mix(in srgb, var(--c) 10%, transparent), transparent 72%),
                    var(--card);
                transform: translateZ(0);
            }

            .dept-card::before {
                height: 3px;
            }

            .dept-card:hover {
                transform: none;
            }

            .dept-card .dept-name {
                min-width: 0;
                padding-right: 4px;
                font-size: 14px;
                font-weight: 800;
                line-height: 1.25;
            }

            .dept-card .dept-role,
            .mgmt-badge {
                position: static;
                flex: 0 0 auto;
                border-radius: 999px;
                font-size: 10px;
                font-weight: 800;
                letter-spacing: 0;
                text-transform: none;
            }

            .dept-card .dept-desc {
                display: -webkit-box;
                overflow: hidden;
                margin-top: 4px;
                color: color-mix(in srgb, var(--muted) 88%, var(--text));
                font-size: 11px;
                line-height: 1.35;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
            }

            .footer-links {
                position: fixed;
                left: 18px;
                right: 18px;
                bottom: calc(12px + env(safe-area-inset-bottom));
                z-index: 20;
                display: grid;
                grid-template-columns: 1fr 54px 1fr;
                align-items: center;
                gap: 10px;
                margin: 0;
                padding: 10px;
                border: 1px solid var(--bottom-border);
                border-radius: 18px;
                background: var(--bottom-bg);
                box-shadow: 0 14px 38px rgba(0,0,0,.32);
            }

            .footer-links a {
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 38px;
                border-radius: 12px;
                background: rgba(255,255,255,.035);
                color: var(--text);
                font-size: 11px;
                font-weight: 700;
            }

            .footer-mark {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 54px;
                height: 54px;
                margin-top: -26px;
                border: 5px solid var(--bg);
                border-radius: 50%;
                background: color-mix(in srgb, var(--text) 10%, var(--bottom-bg));
                color: var(--text);
                font-size: 13px;
                font-weight: 900;
                box-shadow: 0 10px 24px rgba(0,0,0,.28);
            }
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Departman Seçin</h1>
    <p>Merhaba, <?= View::escape(Auth::user()['name']) ?> — devam etmek istediğiniz departmanı seçin</p>
</div>

<div class="grid">
    <?php foreach ($departments as $dept): ?>
    <?php
    $isDir = $dept['role'] === 'director';
    $roleLabel = $isDir ? 'Direktör' : 'Üye';
    $roleBg    = $isDir ? 'rgba(245,158,11,.12)' : 'rgba(0,188,212,.1)';
    $roleColor = $isDir ? '#fcd34d' : '#67e8f9';
    ?>
    <a href="<?= BASE_URL ?>/panel/<?= View::escape($dept['slug']) ?>"
       class="dept-card"
       style="--c: <?= View::escape($dept['color']) ?>">

        <div class="dept-card-head">
            <div class="dept-name"><?= View::escape($dept['name']) ?></div>
            <div class="dept-card-tags">
                <?php if ($dept['is_management']): ?>
                    <span class="mgmt-badge">Yönetim</span>
                <?php endif; ?>
                <span class="dept-role" style="background:<?= $roleBg ?>;color:<?= $roleColor ?>">
                    <?= $roleLabel ?>
                </span>
            </div>
        </div>

        <?php if (!empty($dept['description'])): ?>
        <div class="dept-desc"><?= View::escape(mb_substr($dept['description'], 0, 60)) ?>...</div>
        <?php endif; ?>

    </a>
    <?php endforeach; ?>
    <?php if (Auth::isSuperAdmin()): ?>
    <a href="<?= BASE_URL ?>/superadmin"
       class="dept-card"
       style="--c: #fbbf24; border-style: dashed; background: var(--sa-card);">
        <span class="mgmt-badge" style="background:rgba(245,158,11,.15); border:none; color:#fbbf24;">Sistem</span>
        <div class="dept-name" style="color:#fbbf24">Süper Admin Paneli</div>
        <div class="dept-role" style="background:rgba(245,158,11,.2); color:#fcd34d">
            Sistem Yöneticisi
        </div>
        <div class="dept-desc">Sistem ayarları, kullanıcı yönetimi ve genel atamalar.</div>
    </a>
    <?php endif; ?>
</div>

<div class="footer-links">
    <a onclick="toggleTheme()">Tema Değiştir</a>
    <span class="footer-mark"><?= View::escape(mb_substr(Auth::user()['name'] ?? APP_NAME, 0, 1)) ?></span>
    <a href="<?= BASE_URL ?>/logout">Çıkış Yap</a>
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
