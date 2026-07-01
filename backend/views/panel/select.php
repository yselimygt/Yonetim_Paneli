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
        }

        [data-theme="light"] {
            --bg:       #f3f4f6;
            --card:     #ffffff;
            --border:   #e5e7eb;
            --text:     #111827;
            --muted:    #6b7280;
            --sa-card:  #fffbeb;
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

        .header { text-align: center; margin-bottom: 40px; }
        .header h1 { font-size: 18px; font-weight: 600; color: var(--text); }
        .header p { font-size: 13px; color: var(--muted); margin-top: 6px; }

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

        <?php if ($dept['is_management']): ?>
            <span class="mgmt-badge">Yönetim</span>
        <?php endif; ?>

        <div class="dept-name"><?= View::escape($dept['name']) ?></div>

        <div class="dept-role" style="background:<?= $roleBg ?>;color:<?= $roleColor ?>">
            <?= $roleLabel ?>
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
