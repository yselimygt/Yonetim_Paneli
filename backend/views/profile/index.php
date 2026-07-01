<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Profilim</title>
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
            --accent:   #00BCD4;
            --input-bg: #0d0d0d;
        }

        [data-theme="light"] {
            --bg:       #f3f4f6;
            --card:     #ffffff;
            --border:   #e5e7eb;
            --text:     #111827;
            --muted:    #6b7280;
            --input-bg: #f9fafb;
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

        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { font-size: 18px; font-weight: 600; color: var(--text); }
        .header p { font-size: 13px; color: var(--muted); margin-top: 6px; }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 32px 36px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            transition: box-shadow 0.3s ease;
        }
        
        [data-theme="dark"] .card {
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }

        /* Flash */
        .flash { border-radius: 10px; padding: 12px 16px; font-size: 13px; margin-bottom: 24px; }
        .flash.error   { background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.3); color:#fca5a5; }
        .flash.success { background:rgba(34,197,94,.1); border:1px solid rgba(34,197,94,.3); color:#86efac; }
        .flash.warning { background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:#fcd34d; }

        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 11px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 8px; }
        .form-control { width: 100%; background: var(--input-bg); border: 1px solid var(--border); border-radius: 10px; padding: 12px 14px; color: var(--text); font-family: 'Inter', sans-serif; font-size: 14px; outline: none; transition: all .2s ease; }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.15); }
        .form-control::file-selector-button { border: 1px solid var(--border); background: var(--card); color: var(--text); padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: background 0.2s; margin-right: 12px; }
        .form-control::file-selector-button:hover { background: rgba(255,255,255,0.05); }
        
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 12px 20px;
            border-radius: 10px;
            font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600;
            cursor: pointer; border: none; text-decoration: none;
            width: 100%;
            transition: all .2s ease;
        }
        .btn:active { transform: scale(0.96); }
        .btn-primary { background: var(--accent); color: #0a0a0a; box-shadow: 0 4px 14px rgba(0, 188, 212, 0.3); }
        .btn-primary:hover { opacity: .9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0, 188, 212, 0.4); }
        
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .avatar-preview { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid var(--border); box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 16px; display: block; margin-left: auto; margin-right: auto; }
        
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
        .hint { font-size: 11px; color: var(--muted); margin-top: 4px; display: block; }
    </style>
</head>
<body>

<div class="header">
    <h1>Profilim</h1>
    <p>Hesap bilgilerinizi güncelleyin</p>
</div>

<div class="card">
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="flash <?= View::escape($_SESSION['flash']['type']) ?>">
            <?= View::escape($_SESSION['flash']['message']) ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/profile" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= View::escape($_SESSION['csrf_token']) ?>">
        
        <div class="form-group">
            <?php if (!empty($user['avatar'])): ?>
                <img src="<?= BASE_URL ?>/public/<?= View::escape($user['avatar']) ?>" class="avatar-preview" alt="Avatar">
            <?php endif; ?>
            <label class="form-label">Profil Fotoğrafı</label>
            <input type="file" name="avatar" class="form-control" accept="image/*">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Ad Soyad</label>
                <input type="text" name="name" class="form-control" value="<?= View::escape($user['name']) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">E-posta</label>
                <input type="email" name="email" class="form-control" value="<?= View::escape($user['email']) ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Telefon Numarası</label>
                <input type="text" name="phone" class="form-control" value="<?= View::escape($user['phone'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Doğum Tarihi</label>
                <input type="date" name="birth_date" class="form-control" value="<?= View::escape($user['birth_date'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Üniversite</label>
                <input type="text" name="university" class="form-control" value="<?= View::escape($user['university'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Fakülte</label>
                <input type="text" name="faculty" class="form-control" value="<?= View::escape($user['faculty'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Bölüm</label>
                <input type="text" name="school_department" class="form-control" value="<?= View::escape($user['school_department'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Sınıf</label>
                <input type="text" name="grade" class="form-control" value="<?= View::escape($user['grade'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Yeni Şifre</label>
            <input type="password" name="password" class="form-control">
            <span class="hint">Şifrenizi değiştirmek istemiyorsanız boş bırakın.</span>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Kaydet</button>
    </form>
</div>

<div class="footer-links">
    <?php if (Auth::isSuperAdmin()): ?>
        <a href="<?= BASE_URL ?>/superadmin">Geri Dön</a>
    <?php elseif (count($departments) === 1): ?>
        <a href="<?= BASE_URL ?>/panel/<?= View::escape($departments[0]['slug']) ?>">Geri Dön</a>
    <?php else: ?>
        <a href="<?= BASE_URL ?>/panel">Geri Dön</a>
    <?php endif; ?>
    <a onclick="toggleTheme()">Tema Değiştir</a>
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
