<?php
// Bu sayfaya doğrudan erişimi AuthController::showPending() sağlar
// $user değişkeni o metod tarafından set edilmiş olur
$userName = $user['name'] ?? 'Kullanıcı';

require_once __DIR__ . '/../../models/SiteSetting.php';
$ss = new SiteSetting();
$brand = $ss->get('brand');
$logoUrl  = $brand['logo']    ?? '';
$siteName = $brand['name']    ?? APP_NAME;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Hoş Geldiniz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <?php if (!empty($brand['favicon'])): ?>
        <link rel="icon" href="<?= View::escape($brand['favicon']) ?>">
    <?php endif; ?>
    <style>
        :root {
            --bg-color:       #050505;
            --surface:        rgba(20,20,20,0.6);
            --surface-border: rgba(255,255,255,0.08);
            --text-primary:   #ffffff;
            --text-secondary: #a0a0a0;
            --accent:         #f26b21;
            --shadow:         0 12px 40px 0 rgba(0,0,0,0.5);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Floating background glows */
        .bg-shape {
            position: absolute;
            filter: blur(100px);
            z-index: 0;
            border-radius: 50%;
            animation: float 20s infinite ease-in-out alternate;
            pointer-events: none;
        }
        .bg-shape-1 {
            width: 40vw; height: 40vw;
            max-width: 500px; max-height: 500px;
            background: rgba(242,107,33,0.10);
            top: -10%; left: -10%;
        }
        .bg-shape-2 {
            width: 45vw; height: 45vw;
            max-width: 600px; max-height: 600px;
            background: rgba(0,188,212,0.07);
            bottom: -20%; right: -10%;
            animation-delay: -5s;
        }
        .bg-shape-3 {
            width: 300px; height: 300px;
            background: rgba(242,107,33,0.05);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: -10s;
        }

        @keyframes float {
            0%   { transform: translate(0,0) scale(1); }
            50%  { transform: translate(40px,40px) scale(1.08); }
            100% { transform: translate(-15px,60px) scale(0.95); }
        }

        /* Card */
        .card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 460px;
            margin: 20px;
            padding: 48px 40px 40px;
            background: var(--surface);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--surface-border);
            border-radius: 28px;
            box-shadow: var(--shadow);
            text-align: center;
            animation: popIn 0.6s cubic-bezier(0.16,1,0.3,1) forwards;
        }

        @keyframes popIn {
            from { opacity:0; transform: scale(0.95) translateY(20px); }
            to   { opacity:1; transform: scale(1) translateY(0); }
        }

        /* Icon badge */
        .icon-badge {
            width: 72px; height: 72px;
            margin: 0 auto 24px;
            border-radius: 20px;
            background: rgba(242,107,33,0.12);
            border: 1px solid rgba(242,107,33,0.25);
            display: flex; align-items: center; justify-content: center;
            font-size: 32px;
            animation: pulse 3s ease-in-out infinite;
        }
        @keyframes pulse {
            0%,100% { box-shadow: 0 0 0 0 rgba(242,107,33,0.2); }
            50%      { box-shadow: 0 0 0 12px rgba(242,107,33,0); }
        }

        /* Logo */
        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 32px;
        }
        .brand img {
            height: 32px;
            width: auto;
            object-fit: contain;
        }
        .brand-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.3px;
        }

        h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -0.4px;
        }
        h1 span { color: var(--accent); }

        .subtitle {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.65;
            margin-bottom: 32px;
        }

        /* Info steps */
        .steps {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 32px;
            text-align: left;
        }
        .step {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px;
            font-size: 13px;
            color: var(--text-secondary);
        }
        .step-num {
            flex-shrink: 0;
            width: 24px; height: 24px;
            border-radius: 7px;
            background: rgba(242,107,33,0.15);
            border: 1px solid rgba(242,107,33,0.3);
            color: var(--accent);
            font-size: 11px;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.06);
            margin: 0 0 24px;
        }

        /* Logout button */
        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 22px;
            border-radius: 12px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-logout:hover {
            background: rgba(239,68,68,0.1);
            border-color: rgba(239,68,68,0.3);
            color: #f87171;
        }

        .footer-note {
            margin-top: 20px;
            font-size: 11px;
            color: rgba(255,255,255,0.2);
        }
    </style>
</head>
<body>
    <div class="bg-shape bg-shape-1"></div>
    <div class="bg-shape bg-shape-2"></div>
    <div class="bg-shape bg-shape-3"></div>

    <div class="card">

        <!-- Brand -->
        <?php if ($logoUrl): ?>
        <div class="brand">
            <img src="<?= View::escape($logoUrl) ?>" alt="<?= View::escape($siteName) ?>">
            <span class="brand-name"><?= View::escape($siteName) ?></span>
        </div>
        <?php else: ?>
        <div class="brand">
            <span class="brand-name"><?= View::escape($siteName) ?></span>
        </div>
        <?php endif; ?>

        <!-- Icon -->
        <div class="icon-badge">⏳</div>

        <!-- Title -->
        <h1>Merhaba, <span><?= View::escape(explode(' ', $userName)[0]) ?>!</span></h1>
        <p class="subtitle">
            Hesabınız başarıyla oluşturuldu. Sisteme tam erişim sağlayabilmeniz için
            bir yönetici tarafından departmanınıza atanmanız gerekmektedir.
        </p>

        <!-- Steps -->
        <div class="steps">
            <div class="step">
                <div class="step-num">✓</div>
                Hesabınız oluşturuldu ve aktif
            </div>
            <div class="step">
                <div class="step-num">2</div>
                Yönetici onayı ve departman ataması bekleniyor
            </div>
            <div class="step">
                <div class="step-num">3</div>
                Atandıktan sonra panele otomatik erişim sağlayacaksınız
            </div>
        </div>

        <hr class="divider">

        <!-- Logout -->
        <a href="<?= BASE_URL ?>/logout" class="btn-logout">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            Çıkış Yap
        </a>

        <p class="footer-note"><?= View::escape($siteName) ?> &copy; <?= date('Y') ?></p>
    </div>
</body>
</html>
