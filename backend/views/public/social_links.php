<?php
/** @var array $socialLinks */
/** @var array $brandSettings */

$siteTitle = $brandSettings['site_title'] ?? 'Resmi Sosyal Medya';
$faviconUrl = $brandSettings['favicon'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($siteTitle) . '&background=0D8ABC&color=fff&rounded=true&size=128';

// Fallback if empty favicon
if (empty($faviconUrl)) {
    $faviconUrl = 'https://ui-avatars.com/api/?name=' . urlencode($siteTitle) . '&background=0D8ABC&color=fff&rounded=true&size=128';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8') ?> - Sosyal Medya</title>
    
    <link rel="icon" href="<?= htmlspecialchars($faviconUrl, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-color: #0f172a;
            --text-color: #f8fafc;
            --card-bg: rgba(30, 41, 59, 0.6);
            --card-border: rgba(255, 255, 255, 0.1);
            --btn-bg: rgba(255, 255, 255, 0.05);
            --btn-hover: rgba(255, 255, 255, 0.1);
            --btn-border: rgba(255, 255, 255, 0.15);
            --primary-glow: rgba(56, 189, 248, 0.4);
            --secondary-glow: rgba(168, 85, 247, 0.4);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background Gradients */
        .bg-glow-1 {
            position: absolute;
            top: -10%;
            left: -10%;
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, var(--primary-glow) 0%, transparent 60%);
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            animation: float 10s ease-in-out infinite alternate;
        }

        .bg-glow-2 {
            position: absolute;
            bottom: -10%;
            right: -10%;
            width: 60vw;
            height: 60vw;
            background: radial-gradient(circle, var(--secondary-glow) 0%, transparent 60%);
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            animation: float 12s ease-in-out infinite alternate-reverse;
        }

        @keyframes float {
            0% { transform: translate(0, 0); }
            100% { transform: translate(10%, 10%); }
        }

        .container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .avatar-wrapper {
            position: relative;
            width: 110px;
            height: 110px;
            margin: 0 auto 20px;
        }

        .avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.2);
            padding: 4px;
            background: var(--bg-color);
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }

        .avatar-glow {
            position: absolute;
            inset: -5px;
            border-radius: 50%;
            background: linear-gradient(45deg, #38bdf8, #a855f7);
            z-index: -1;
            filter: blur(10px);
            opacity: 0.7;
            animation: spin 4s linear infinite;
        }

        @keyframes spin {
            100% { transform: rotate(360deg); }
        }

        h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
            background: linear-gradient(to right, #fff, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p.subtitle {
            font-size: 14px;
            color: #94a3b8;
            margin-bottom: 30px;
            font-weight: 400;
        }

        .links-container {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .link-btn {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 16px 20px;
            background: var(--btn-bg);
            border: 1px solid var(--btn-border);
            border-radius: 14px;
            color: var(--text-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .link-btn::before {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
            transition: left 0.5s ease;
        }

        .link-btn:hover {
            background: var(--btn-hover);
            transform: translateY(-3px);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }

        .link-btn:hover::before {
            left: 100%;
        }

        .link-icon {
            font-size: 20px;
            width: 32px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            opacity: 0.9;
        }

        .link-text {
            flex-grow: 1;
            text-align: center;
            padding-right: 32px; /* to balance the icon width */
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #64748b;
        }

        .footer a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer a:hover {
            color: #fff;
        }
    </style>
</head>
<body>

    <div class="bg-glow-1"></div>
    <div class="bg-glow-2"></div>

    <div class="container">
        <div class="avatar-wrapper">
            <div class="avatar-glow"></div>
            <img src="<?= htmlspecialchars($faviconUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Logo" class="avatar">
        </div>

        <h1><?= htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="subtitle">Resmi Sosyal Medya Hesaplarımız</p>

        <div class="links-container">
            <?php if (empty($socialLinks)): ?>
                <p style="color: #94a3b8; font-size: 14px; padding: 20px 0;">Henüz sosyal medya hesabı eklenmemiş.</p>
            <?php else: ?>
                <?php foreach ($socialLinks as $link): ?>
                    <a href="<?= htmlspecialchars($link['url'] ?? '#', ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="link-btn">
                        <div class="link-icon">
                            <i class="<?= htmlspecialchars($link['icon'] ?? 'fa-solid fa-link', ENT_QUOTES, 'UTF-8') ?>"></i>
                        </div>
                        <span class="link-text"><?= htmlspecialchars($link['title'] ?? 'Bağlantı', ENT_QUOTES, 'UTF-8') ?></span>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="footer">
            &copy; <?= date('Y') ?> <?= htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8') ?>. Tüm hakları saklıdır.
            <div style="margin-top: 16px; display: flex; align-items: center; justify-content: center; gap: 6px; font-size: 10px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;">
                <span>Powered by</span>
                <a href="https://viperadev.com" target="_blank" style="display:flex; align-items: center; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <img src="https://viperadev.com/ViperaDev-Logo.png" alt="ViperaDev" style="height: 18px; filter: grayscale(100%) opacity(0.6); transition: all 0.3s;" onmouseover="this.style.filter='grayscale(0%) opacity(1)'" onmouseout="this.style.filter='grayscale(100%) opacity(0.6)'">
                </a>
            </div>
        </div>
    </div>

</body>
</html>
