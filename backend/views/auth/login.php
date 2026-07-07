<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Giriş</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        const storedTheme = localStorage.getItem('theme');
        if (storedTheme === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>
    <style>
        :root {
            --bg-color: #050505;
            --surface: rgba(20, 20, 20, 0.6);
            --surface-border: rgba(255, 255, 255, 0.08);
            --text-primary: #ffffff;
            --text-secondary: #a0a0a0;
            --accent: #f26b21;
            --accent-hover: #ff7e36;
            --input-bg: rgba(255, 255, 255, 0.03);
            --input-border: rgba(255, 255, 255, 0.1);
            --input-focus: rgba(242, 107, 33, 0.5);
            --shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.5);
        }

        [data-theme="light"] {
            --bg-color: #f0f2f5;
            --surface: rgba(255, 255, 255, 0.85);
            --surface-border: rgba(0, 0, 0, 0.08);
            --text-primary: #1a1a1a;
            --text-secondary: #666666;
            --accent: #e85d15;
            --accent-hover: #f26b21;
            --input-bg: rgba(0, 0, 0, 0.02);
            --input-border: rgba(0, 0, 0, 0.12);
            --input-focus: rgba(232, 93, 21, 0.3);
            --shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.08);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

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
            transition: background-color 0.4s ease;
        }

        /* Abstract Background Elements */
        .bg-shape {
            position: absolute;
            filter: blur(100px);
            z-index: -1;
            border-radius: 50%;
            animation: float 20s infinite ease-in-out alternate;
        }
        
        .bg-shape-1 {
            width: 40vw;
            height: 40vw;
            max-width: 500px;
            max-height: 500px;
            background: rgba(242, 107, 33, 0.12);
            top: -10%;
            left: -10%;
        }
        
        .bg-shape-2 {
            width: 45vw;
            height: 45vw;
            max-width: 600px;
            max-height: 600px;
            background: rgba(0, 188, 212, 0.08);
            bottom: -20%;
            right: -10%;
            animation-delay: -5s;
        }
        
        [data-theme="light"] .bg-shape-1 { background: rgba(242, 107, 33, 0.15); }
        [data-theme="light"] .bg-shape-2 { background: rgba(0, 188, 212, 0.12); }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(50px, 50px) scale(1.1); }
            100% { transform: translate(-20px, 80px) scale(0.9); }
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 40px;
            margin: 20px;
            background: var(--surface);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--surface-border);
            border-radius: 28px;
            box-shadow: var(--shadow);
            z-index: 10;
            opacity: 0;
            transform: translateY(20px);
            animation: slideUpFade 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slideUpFade {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand {
            text-align: center;
            margin-bottom: 36px;
        }

        .brand img {
            width: 72px;
            height: auto;
            margin-bottom: 18px;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.15));
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        .brand img:hover {
            transform: scale(1.1) rotate(-3deg);
        }

        .brand h1 {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .brand p {
            font-size: 14.5px;
            color: var(--text-secondary);
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 22px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            transition: color 0.3s ease;
        }

        .form-control {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 14px;
            padding: 15px 16px;
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
        }

        .form-control:focus {
            background: rgba(255,255,255,0.06);
            border-color: var(--accent);
            box-shadow: 0 0 0 4px var(--input-focus);
        }
        
        [data-theme="light"] .form-control:focus {
            background: #ffffff;
        }

        .form-control::placeholder {
            color: var(--text-secondary);
            opacity: 0.6;
        }

        .captcha-row {
            display: flex;
            gap: 12px;
            align-items: stretch;
        }

        .captcha-q {
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 14px;
            padding: 0 20px;
            font-size: 17px;
            font-weight: 700;
            color: var(--accent);
            font-family: 'Courier New', Courier, monospace;
            min-width: 110px;
            user-select: none;
            letter-spacing: 1px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 26px 0;
        }

        .remember-row input[type="checkbox"] {
            appearance: none;
            width: 22px;
            height: 22px;
            border: 2px solid var(--input-border);
            border-radius: 6px;
            background: var(--input-bg);
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .remember-row input[type="checkbox"]:checked {
            background: var(--accent);
            border-color: var(--accent);
        }

        .remember-row input[type="checkbox"]:checked::after {
            content: '';
            width: 5px;
            height: 11px;
            border: solid white;
            border-width: 0 2.5px 2.5px 0;
            transform: rotate(45deg);
            position: absolute;
            top: 2px;
        }

        .remember-row label {
            font-size: 14px;
            color: var(--text-secondary);
            cursor: pointer;
            user-select: none;
            font-weight: 500;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: var(--accent);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 6px 16px rgba(242, 107, 33, 0.3);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-submit::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
            transition: 0.6s;
        }
        
        .btn-submit:hover::after {
            left: 100%;
        }

        .btn-submit:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(242, 107, 33, 0.45);
        }

        .btn-submit:active {
            transform: translateY(1px);
            box-shadow: 0 4px 12px rgba(242, 107, 33, 0.3);
        }

        .flash {
            border-radius: 14px;
            padding: 14px 18px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }
        
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }

        .flash.error   { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.25); color: #f87171; }
        .flash.success { background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.25); color: #4ade80; }
        .flash.warning { background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.25); color: #fbbf24; }
        
        [data-theme="light"] .flash.error { color: #dc2626; }
        [data-theme="light"] .flash.success { color: #16a34a; }
        [data-theme="light"] .flash.warning { color: #d97706; }

        .footer {
            text-align: center;
            margin-top: 36px;
            font-size: 13px;
            color: var(--text-secondary);
            border-top: 1px solid var(--surface-border);
            padding-top: 24px;
        }
        
        .footer-logo-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 12px;
        }

        .btn-theme-login {
            position: absolute;
            top: 24px;
            right: 24px;
            background: var(--surface);
            backdrop-filter: blur(10px);
            border: 1px solid var(--surface-border);
            color: var(--text-primary);
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            z-index: 20;
        }

        .btn-theme-login:hover {
            transform: rotate(15deg) scale(1.1);
            border-color: var(--text-secondary);
            background: rgba(255,255,255,0.05);
        }
        
        [data-theme="light"] .btn-theme-login:hover {
            background: #ffffff;
        }

        .vipera-link {
            display: flex;
            align-items: center;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }
        .vipera-link:hover {
            opacity: 1;
        }
        
        .vipera-logo {
            height: 18px;
            filter: brightness(0) invert(1);
            transition: all 0.3s;
        }
        
        .vipera-link:hover .vipera-logo {
            filter: brightness(1) invert(0);
        }
        
        [data-theme="light"] .vipera-logo {
            filter: brightness(0);
        }
        
        [data-theme="light"] .vipera-link:hover .vipera-logo {
            filter: brightness(1);
        }
        
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .input-icon {
            position: absolute;
            left: 18px;
            color: var(--text-secondary);
            pointer-events: none;
            transition: color 0.3s, transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        .form-control:focus + .input-icon,
        .form-control:not(:placeholder-shown) + .input-icon {
            color: var(--accent);
            transform: scale(1.1);
        }
        
        .form-control.with-icon {
            padding-left: 52px;
        }
    </style>
</head>
<body>

<div class="bg-shape bg-shape-1"></div>
<div class="bg-shape bg-shape-2"></div>

<button onclick="toggleTheme()" class="btn-theme-login" title="Tema Değiştir">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
        <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
    </svg>
</button>

<div class="login-container">
    <div class="brand">
        <img src="<?= BASE_URL ?>/public/assets/logo/Ana-Logo-Arma.png" alt="Innomis">
        <h1><?= APP_NAME ?></h1>
        <p>Yönetim Paneli Girişi</p>
    </div>

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="flash <?= View::escape($_SESSION['flash']['type']) ?>">
            <?php 
                $icon = 'M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z M12 8V12 M12 16H12.01';
                if ($_SESSION['flash']['type'] === 'success') {
                    $icon = 'M22 11.08V12a10 10 0 1 1-5.93-9.14 M22 4L12 14.01l-3-3';
                }
            ?>
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                <path d="<?= $icon ?>"></path>
            </svg>
            <?= View::escape($_SESSION['flash']['message']) ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/login" autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div class="form-group">
            <label for="email">E-posta Adresi</label>
            <div class="input-wrapper">
                <input type="email" id="email" name="email" class="form-control with-icon"
                    placeholder="ornek@domain.com"
                    value="<?= View::escape($_POST['email'] ?? '') ?>"
                    required autofocus>
                <svg class="input-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline>
                </svg>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Şifre</label>
            <div class="input-wrapper">
                <input type="password" id="password" name="password" class="form-control with-icon"
                    placeholder="••••••••" required>
                <svg class="input-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
        </div>

        <div class="form-group">
            <label>Güvenlik Doğrulaması</label>
            <div class="captcha-row">
                <div class="captcha-q"><?= View::escape(Auth::getCaptchaQuestion()) ?></div>
                <input type="number" id="captcha" name="captcha" class="form-control"
                    placeholder="Sonuç" required autocomplete="off">
            </div>
        </div>

        <div class="remember-row">
            <input type="checkbox" id="remember_me" name="remember_me" value="1">
            <label for="remember_me">Oturumumu açık tut (7 gün)</label>
        </div>

        <button type="submit" class="btn-submit">
            <span>Sisteme Giriş Yap</span>
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-top:2px;">
                <line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </button>
    </form>

    <div class="footer">
        <div><?= APP_NAME ?> &copy; <?= date('Y') ?></div>
        <div class="footer-logo-row">
            <span>Powered by</span>
            <a href="https://viperadev.com" target="_blank" class="vipera-link" title="ViperaDev">
                <img src="https://viperadev.com/ViperaDev-Logo.png" alt="ViperaDev" class="vipera-logo">
            </a>
        </div>
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
