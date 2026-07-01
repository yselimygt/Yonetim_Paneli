<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Sayfa Bulunamadı</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: #070708;
            color: #e2e8f0;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 440px;
            width: 100%;
            text-align: center;
            padding: 40px 24px;
            background: #0f0f11;
            border: 1px solid #1e1e24;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
            position: relative;
            overflow: hidden;
        }
        .container::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(0, 188, 212, 0.05) 0%, rgba(0,0,0,0) 70%);
            pointer-events: none;
        }
        .icon-wrapper {
            width: 80px; height: 80px;
            background: rgba(0, 188, 212, 0.08);
            border: 1px solid rgba(0, 188, 212, 0.2);
            color: #00BCD4;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 0 20px rgba(0, 188, 212, 0.1);
        }
        h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        p {
            font-size: 14px;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 28px;
        }
        .btn {
            display: inline-block;
            background: #00BCD4;
            color: #070708;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: opacity 0.15s, transform 0.1s;
        }
        .btn:active {
            transform: scale(0.98);
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-wrapper">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M16 16s-1.5-2-4-2-4 2-4 2"></path>
                <line x1="9" y1="9" x2="9.01" y2="9"></line>
                <line x1="15" y1="9" x2="15.01" y2="9"></line>
            </svg>
        </div>
        <h1>Sayfa Bulunamadı (404)</h1>
        <p>Aradığınız adres silinmiş, ismi değiştirilmiş veya geçici olarak kullanım dışı olabilir.</p>
        <a href="<?= BASE_URL ?>/" class="btn">Ana Sayfaya Dön</a>
    </div>
</body>
</html>
