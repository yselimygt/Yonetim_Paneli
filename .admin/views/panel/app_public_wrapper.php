<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= View::escape($pageTitle ?? 'Form') ?></title>
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
            --accent:   <?= View::escape($currentDept['color'] ?? '#00BCD4') ?>;
            --text:     #e8e8e8;
            --muted:    #555;
            --danger:   #ef4444;
            --success:  #22c55e;
            --warning:  #f59e0b;
            --input-bg: #0d0d0d;
            --card-alt: #151515;
        }

        [data-theme="light"] {
            --bg:       #f3f4f6;
            --card:     #ffffff;
            --border:   #e5e7eb;
            --text:     #111827;
            --muted:    #6b7280;
            --input-bg: #f9fafb;
            --card-alt: #f8fafc;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
        }
        
        .public-app-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .alert {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            background: var(--card);
            border: 1px solid var(--border);
            color: var(--text);
        }
        
        .alert-success { border-left: 4px solid var(--success); }
        .alert-danger { border-left: 4px solid var(--danger); }
        .alert-warning { border-left: 4px solid var(--warning); }
        .alert-info { border-left: 4px solid var(--accent); }
    </style>
</head>
<body>
    <div class="public-app-container">
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-<?= View::escape($_SESSION['flash']['type']) ?>">
                <?= View::escape($_SESSION['flash']['message']) ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <?= $moduleContent ?>
    </div>
</body>
</html>
