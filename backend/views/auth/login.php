<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Giriş</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script>
        const storedTheme = localStorage.getItem('theme');
        if (storedTheme === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:      #0a0a0a;
            --card:    #111111;
            --border:  #1e1e1e;
            --accent:  #00BCD4;
            --accent-h:#00ACC1;
            --text:    #f0f0f0;
            --muted:   #555555;
            --input-bg:#0d0d0d;
        }

        [data-theme="light"] {
            --bg:      #f3f4f6;
            --card:    #ffffff;
            --border:  #e5e7eb;
            --text:    #111827;
            --muted:   #6b7280;
            --input-bg:#f9fafb;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wrap { width: 100%; max-width: 400px; padding: 16px; }

        .brand { text-align: center; margin-bottom: 32px; }
        .brand h1 { font-size: 22px; font-weight: 600; color: var(--accent); }
        .brand p  { font-size: 13px; color: var(--muted); margin-top: 4px; }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 32px;
        }

        .form-group { margin-bottom: 18px; }

        label {
            display: block;
            font-size: 11px;
            font-weight: 500;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 8px;
        }

        input[type="email"],
        input[type="password"],
        input[type="number"] {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 11px 14px;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            transition: border-color .2s;
            outline: none;
            -moz-appearance: textfield;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; }
        input:focus { border-color: var(--accent); }

        /* Robot doğrulama */
        .captcha-row { display: flex; gap: 10px; align-items: stretch; }
        .captcha-q {
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 11px 16px;
            font-size: 15px;
            font-weight: 600;
            color: var(--accent);
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
            white-space: nowrap;
            min-width: 110px;
            user-select: none;
        }
        .captcha-row input { flex: 1; }

        hr.divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 20px 0;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
        }
        .remember-row input[type="checkbox"] {
            accent-color: var(--accent);
            width: 15px; height: 15px;
            cursor: pointer;
        }
        .remember-row label {
            font-size: 13px;
            color: var(--muted);
            text-transform: none;
            letter-spacing: 0;
            margin: 0;
            cursor: pointer;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: var(--accent);
            color: #0a0a0a;
            border: none;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, transform .1s;
        }
        .btn:hover  { background: var(--accent-h); }
        .btn:active { transform: scale(0.98); }

        .flash {
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 13px;
            margin-bottom: 18px;
        }
        .flash.error   { background:rgba(239,68,68,.12); border:1px solid rgba(239,68,68,.3);  color:#fca5a5; }
        .flash.success { background:rgba(34,197,94,.12); border:1px solid rgba(34,197,94,.3);  color:#86efac; }
        .flash.warning { background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.3); color:#fcd34d; }

        .footer { text-align: center; margin-top: 24px; font-size: 12px; color: #2a2a2a; }
        
        .btn-theme-login {
            position: absolute;
            top: 20px;
            right: 20px;
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-theme-login:hover { background: rgba(128,128,128,0.1); }
        
        .vipera-link {
            display: flex;
            align-items: center;
        }
        .vipera-logo {
            height: 18px;
            opacity: 0.7;
            filter: brightness(0) invert(1);
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

<button onclick="toggleTheme()" class="btn-theme-login" title="Tema Değiştir">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
        <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
    </svg>
</button>

<div class="wrap">
    <div class="brand">
        <h1><?= APP_NAME ?></h1>
        <p>Yönetim Paneli</p>
    </div>

    <div class="card">

        <?php if (isset($_SESSION['flash'])): ?>
            <div class="flash <?= View::escape($_SESSION['flash']['type']) ?>">
                <?= View::escape($_SESSION['flash']['message']) ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/login" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="form-group">
                <label for="email">E-posta</label>
                <input type="email" id="email" name="email"
                    placeholder="ornek@domain.com"
                    value="<?= View::escape($_POST['email'] ?? '') ?>"
                    required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Şifre</label>
                <input type="password" id="password" name="password"
                    placeholder="••••••••" required>
            </div>

            <hr class="divider">

            <div class="form-group">
                <label>Robot Doğrulaması</label>
                <div class="captcha-row">
                    <div class="captcha-q"><?= View::escape(Auth::getCaptchaQuestion()) ?></div>
                    <input type="number" id="captcha" name="captcha"
                        placeholder="Sonuç" required autocomplete="off">
                </div>
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember_me" name="remember_me" value="1">
                <label for="remember_me">Beni hatırla (7 gün)</label>
            </div>

            <button type="submit" class="btn">Giriş Yap</button>
        </form>
    </div>

    <div class="footer">
        <?= APP_NAME ?> &copy; <?= date('Y') ?><br>
        <div style="font-size: 11px; display: flex; align-items: center; justify-content: center; gap: 4px; margin-top: 6px;">
            <span>Powered by</span>
            <a href="https://viperadev.com" target="_blank" class="vipera-link">
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
