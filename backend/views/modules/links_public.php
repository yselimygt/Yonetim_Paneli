<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= View::escape($profile['name']) ?> | Bağlantılar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --theme-color: <?= View::escape($profile['theme_color'] ?? '#3b82f6') ?>;
            --bg-color: <?= $profile['theme_color'] ?? '#3b82f6' ?>15; /* 15 is hex alpha for ~8% opacity */
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: #1a1a1a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            width: 100%;
            max-width: 680px;
            padding: 48px 24px;
            text-align: center;
        }
        .profile-img {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 24px auto;
            border: 5px solid #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background: #fff;
        }
        .profile-title {
            font-size: 22px;
            font-weight: 700;
            margin: 0 0 8px 0;
            color: #111827;
        }
        .profile-bio {
            font-size: 15px;
            color: #4b5563;
            margin: 0 0 32px 0;
            line-height: 1.5;
        }
        .links-wrapper {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .link-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: #fff;
            color: #111827;
            text-decoration: none;
            padding: 24px 32px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: all 0.2s ease-in-out;
            border: 1px solid transparent;
        }
        .link-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            border-color: var(--theme-color);
            color: var(--theme-color);
        }
        .link-icon {
            position: absolute;
            left: 24px;
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        
        .footer {
            margin-top: 48px;
            font-size: 13px;
            color: #6b7280;
        }
        .footer a {
            color: var(--theme-color);
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php 
        $imgSrc = $profile['image'];
        if (!empty($imgSrc) && !preg_match('/^https?:\/\//i', $imgSrc)) {
            $imgSrc = BASE_URL . '/public/' . ltrim($imgSrc, '/');
        }
        ?>
        <?php if(!empty($imgSrc)): ?>
            <img src="<?= View::escape($imgSrc) ?>" alt="Profil" class="profile-img">
        <?php else: ?>
            <div class="profile-img" style="display:flex; align-items:center; justify-content:center; background:var(--theme-color); color:#fff; font-size:40px; font-weight:700;">
                <?= mb_substr(View::escape($profile['name']), 0, 1) ?>
            </div>
        <?php endif; ?>

        <h1 class="profile-title"><?= View::escape($profile['name']) ?></h1>
        
        <?php if(!empty($profile['bio'])): ?>
            <p class="profile-bio"><?= nl2br(View::escape($profile['bio'])) ?></p>
        <?php endif; ?>

        <div class="links-wrapper">
            <?php foreach($links as $link): ?>
                <?php 
                $iconSrc = $link['icon'] ?? '';
                if (!empty($iconSrc) && !preg_match('/^https?:\/\//i', $iconSrc)) {
                    $iconSrc = BASE_URL . '/public/' . ltrim($iconSrc, '/');
                }
                ?>
                <a href="<?= View::escape($link['url']) ?>" target="_blank" rel="noopener noreferrer" class="link-btn">
                    <?php if(!empty($iconSrc)): ?>
                        <img src="<?= View::escape($iconSrc) ?>" class="link-icon" alt="">
                    <?php endif; ?>
                    <?= View::escape($link['title']) ?>
                </a>
            <?php endforeach; ?>
            
            <?php if(empty($links)): ?>
                <div style="padding:32px; color:#6b7280; background:#fff; border-radius:12px;">
                    Henüz bağlantı eklenmemiş.
                </div>
            <?php endif; ?>
        </div>

        <div style="font-size: 11px; display: flex; align-items: center; justify-content: center; gap: 4px; margin-top: 10px; opacity: 0.8;">
            <span>Powered by</span>
            <a href="https://viperadev.com" target="_blank" style="display: flex; align-items: center;">
                <img src="https://viperadev.com/ViperaDev-Logo.png" alt="ViperaDev" style="height: 16px; opacity: 0.7; filter: grayscale(1); transition: all 0.3s;" onmouseover="this.style.opacity='1'; this.style.filter='none';" onmouseout="this.style.opacity='0.7'; this.style.filter='grayscale(1)';">
            </a>
        </div>
    </div>
</body>
</html>
