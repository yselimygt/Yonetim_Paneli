<?php
/**
 * InnoMIS — Resmi Sosyal Medya Bağlantıları (Linktree Tarzı)
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';

// Helper for extracting username from URL
function extractUsername($url) {
    if (!$url) return '';
    $path = trim(parse_url($url, PHP_URL_PATH), '/');
    $parts = explode('/', $path);
    $username = end($parts);
    // Remove query strings or fragments if they accidentally got in the path
    $username = preg_replace('/[?#].*$/', '', $username);
    return $username ? '@' . $username : '';
}

// Fetch settings
$instagram = getSetting('sm_instagram');
$linkedin = getSetting('sm_linkedin');
$twitter = getSetting('sm_x');
$youtube = getSetting('sm_youtube');
$tiktok = getSetting('sm_tiktok');
$whatsapp = getSetting('sm_whatsapp');
$register_form = getSetting('sm_register_form');
$nsosyal = getSetting('sm_nsosyal');
$website = getSetting('site_url', 'https://innomis.tr');

// Fetch site title and description for display
$siteTitle = getSetting('site_name', 'InnoMIS Community');
// If site_name is just "InnoMIS", append "Community" to match previous hardcoded style if desired, or just use as is
// Let's use it as is, or default to InnoMIS Community
$siteBio = getSetting('site_description', 'Yenilikçi Eğitim ve Proje Topluluğu');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InnoMIS Community - Resmi Linkler</title>
    <meta name="description" content="InnoMIS Community resmi sosyal medya hesapları ve bağlantıları.">
    
    <link rel="icon" type="assets/image/png" href="../assets/image/logo/Ana-Logo-Arma.png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome for Social Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-dark: #090a0f;
            --surface: #151515;
            --surface-hover: #1f1f1f;
            --border: #2a2a2a;
            --border-hover: #3a3a3a;
            --primary: #6366f1;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 3rem 1rem 2rem;
        }

        .container {
            width: 100%;
            max-width: 580px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        /* Profile Header */
        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
            animation: fetchDown 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(-20px);
        }

        .profile-logo {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: contain;
            background: #ffffff;
            padding: 5px;
            margin: 0 auto 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.05);
        }

        .profile-logo:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
        }

        .profile-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
            color: #ffffff;
        }

        .profile-bio {
            font-size: 0.95rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        /* Links List */
        .links-container {
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
        }

        .link-card {
            display: flex;
            align-items: center;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.15rem 1.4rem;
            text-decoration: none;
            color: var(--text-main);
            transition: all 0.25s ease;
            position: relative;
            opacity: 0;
            transform: translateY(15px);
            animation: fetchUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Staggered Animation Delay for links */
        .link-card:nth-child(1) { animation-delay: 0.15s; }
        .link-card:nth-child(2) { animation-delay: 0.25s; }
        .link-card:nth-child(3) { animation-delay: 0.35s; }
        .link-card:nth-child(4) { animation-delay: 0.45s; }
        .link-card:nth-child(5) { animation-delay: 0.55s; }
        .link-card:nth-child(6) { animation-delay: 0.65s; }
        .link-card:nth-child(7) { animation-delay: 0.75s; }
        .link-card:nth-child(8) { animation-delay: 0.85s; }
        .link-card:nth-child(9) { animation-delay: 0.95s; }

        .link-card:hover {
            background: var(--surface-hover);
            border-color: var(--border-hover);
            transform: translateY(-2px);
        }

        /* Brand Colors on Hover */
        .link-instagram:hover { border-color: #E1306C; }
        .link-linkedin:hover { border-color: #0077B5; }
        .link-twitter:hover { border-color: #ffffff; }
        .link-youtube:hover { border-color: #FF0000; }
        .link-tiktok:hover { border-color: #69C9D0; }
        .link-website:hover { border-color: var(--primary); }

        .link-icon {
            font-size: 1.5rem;
            width: 36px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 1.1rem;
            transition: transform 0.2s ease;
        }

        /* Specific Brand Icon Colors */
        .link-instagram .link-icon { background: -webkit-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .link-linkedin .link-icon { color: #0077B5; }
        .link-twitter .link-icon { color: #ffffff; }
        .link-youtube .link-icon { color: #FF0000; }
        .link-tiktok .link-icon { color: #ffffff; text-shadow: -1px -1px 0 #0ff, 1px 1px 0 #f00; }
        .link-website .link-icon { color: var(--primary); }

        .link-card:hover .link-icon {
            transform: scale(1.08);
        }

        .link-content {
            flex-grow: 1;
        }

        .link-title {
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: 0.1rem;
        }

        .link-username {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        .link-arrow {
            color: var(--text-muted);
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .link-card:hover .link-arrow {
            transform: translateX(4px);
            color: #ffffff;
        }

        /* Footer */
        .footer {
            margin-top: 3.5rem;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-muted);
            opacity: 0;
            animation: fetchUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            animation-delay: 1.1s;
        }

        .footer a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer a:hover {
            color: #ffffff;
        }

        /* Animations */
        @keyframes fetchDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fetchUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .profile-logo { width: 90px; height: 90px; }
            .profile-title { font-size: 1.35rem; }
            .link-card { padding: 1rem 1.2rem; }
            .link-icon { font-size: 1.4rem; margin-right: 0.9rem; }
        }

        .link-whatsapp:hover { border-color: #25D366; }
        .link-whatsapp .link-icon { color: #25D366; }

        .link-register:hover { border-color: #f97316; }
        .link-register .link-icon { color: #f97316; }

        .link-nsosyal:hover { border-color: #8b5cf6; }
        .link-nsosyal .link-icon { color: #8b5cf6; }
    </style>
</head>
<body>

    <div class="container">
        
        <!-- Header -->
        <header class="profile-header">
            <!-- Logonun yolu, kök dizinde 'assets/image/logo/Büyük-Logo.png' olarak varsayıldı. Bulunamazsa tarayıcı 'alt' metnini gösterir -->
            <img src="../assets/image/logo/Büyük-Logo.png" alt="<?= e($siteTitle) ?> Logo" class="profile-logo" onerror="this.src='../assets/image/logo/Ana-Logo-Arma.png'; this.onerror='';">
            <h1 class="profile-title"><?= e($siteTitle) ?></h1>
            <p class="profile-bio"><?= e($siteBio) ?></p>
        </header>

        <!-- Links Box -->
        <main class="links-container">
            
            <?php if ($instagram): ?>
            <a href="<?= e($instagram) ?>" target="_blank" rel="noopener noreferrer" class="link-card link-instagram">
                <div class="link-icon">
                    <i class="fa-brands fa-instagram"></i>
                </div>
                <div class="link-content">
                    <div class="link-title">Instagram</div>
                    <div class="link-username"><?= e(extractUsername($instagram)) ?></div>
                </div>
                <div class="link-arrow">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
            <?php endif; ?>

            <?php if ($linkedin): ?>
            <a href="<?= e($linkedin) ?>" target="_blank" rel="noopener noreferrer" class="link-card link-linkedin">
                <div class="link-icon">
                    <i class="fa-brands fa-linkedin-in"></i>
                </div>
                <div class="link-content">
                    <div class="link-title">LinkedIn</div>
                    <div class="link-username"><?= e(extractUsername($linkedin) ? ltrim(extractUsername($linkedin), '@') : 'InnoMIS Community') ?></div>
                </div>
                <div class="link-arrow">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
            <?php endif; ?>

            <?php if ($twitter): ?>
            <a href="<?= e($twitter) ?>" target="_blank" rel="noopener noreferrer" class="link-card link-twitter">
                <div class="link-icon">
                    <i class="fa-brands fa-x-twitter"></i>
                </div>
                <div class="link-content">
                    <div class="link-title">X (Twitter)</div>
                    <div class="link-username"><?= e(extractUsername($twitter)) ?></div>
                </div>
                <div class="link-arrow">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
            <?php endif; ?>

            <?php if ($youtube): ?>
            <a href="<?= e($youtube) ?>" target="_blank" rel="noopener noreferrer" class="link-card link-youtube">
                <div class="link-icon">
                    <i class="fa-brands fa-youtube"></i>
                </div>
                <div class="link-content">
                    <div class="link-title">YouTube</div>
                    <div class="link-username"><?= e(extractUsername($youtube)) ?></div>
                </div>
                <div class="link-arrow">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
            <?php endif; ?>

            <?php if ($tiktok): ?>
            <a href="<?= e($tiktok) ?>" target="_blank" rel="noopener noreferrer" class="link-card link-tiktok">
                <div class="link-icon">
                    <i class="fa-brands fa-tiktok"></i>
                </div>
                <div class="link-content">
                    <div class="link-title">TikTok</div>
                    <div class="link-username"><?= e(extractUsername($tiktok)) ?></div>
                </div>
                <div class="link-arrow">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
            <?php endif; ?>

            <?php if ($whatsapp): ?>
            <a href="<?= e($whatsapp) ?>" target="_blank" rel="noopener noreferrer" class="link-card link-whatsapp">
                <div class="link-icon">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <div class="link-content">
                    <div class="link-title">WhatsApp Grubu</div>
                    <div class="link-username">Topluluk Duyuru Kanalı</div>
                </div>
                <div class="link-arrow">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
            <?php endif; ?>

            <?php if ($register_form): ?>
            <a href="<?= e($register_form) ?>" target="_blank" rel="noopener noreferrer" class="link-card link-register">
                <div class="link-icon">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <div class="link-content">
                    <div class="link-title">Topluluk Kayıt Formu</div>
                    <div class="link-username">Aramıza Katılın</div>
                </div>
                <div class="link-arrow">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
            <?php endif; ?>

            <?php if ($nsosyal): ?>
            <a href="<?= e($nsosyal) ?>" target="_blank" rel="noopener noreferrer" class="link-card link-nsosyal">
                <div class="link-icon">
                    <i class="fa-solid fa-n" style="font-weight: 900;"></i>
                </div>
                <div class="link-content">
                    <div class="link-title">N Sosyal</div>
                    <div class="link-username">Üniversite Ağımız</div>
                </div>
                <div class="link-arrow">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
            <?php endif; ?>

            <!-- Web Site -->
            <a href="<?= e($website) ?>" class="link-card link-website">
                <div class="link-icon">
                    <i class="fa-solid fa-globe"></i>
                </div>
                <div class="link-content">
                    <div class="link-title">Resmi Web Sitesi</div>
                    <div class="link-username"><?= e(parse_url($website, PHP_URL_HOST) ?? 'innomis.tr') ?></div>
                </div>
                <div class="link-arrow">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>

        </main>

        <footer class="footer">
            <p>&copy; <?php echo date('Y'); ?> InnoMIS Community. Tüm hakları saklıdır.</p>
            <p style="margin-top: 0.5rem; font-size: 0.75rem; opacity: 0.7;">
                <a href="/">Ana Sayfaya Dön</a> | <a href="https://yavuzselimyigit.com" target="_blank" style="color: inherit; text-decoration: none; font-weight: 600;">by yselimygt</a>
            </p>
        </footer>

    </div>

</body>
</html>
