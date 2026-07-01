<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Database.php';

try {
    $db = Database::getInstance();

    $db->exec("DROP TABLE IF EXISTS site_settings");
    $db->exec("CREATE TABLE site_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        key_name VARCHAR(100) UNIQUE NOT NULL,
        json_value JSON,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 2. Create site_projects table
    $db->exec("DROP TABLE IF EXISTS site_projects");
    $db->exec("CREATE TABLE site_projects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        status VARCHAR(50),
        category VARCHAR(100),
        icon VARCHAR(50),
        link VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 3. Create site_gallery table
    $db->exec("DROP TABLE IF EXISTS site_gallery");
    $db->exec("CREATE TABLE site_gallery (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255),
        category VARCHAR(100),
        image_url VARCHAR(500) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 4. Create site_team_members table
    $db->exec("DROP TABLE IF EXISTS site_team_members");
    $db->exec("CREATE TABLE site_team_members (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        role VARCHAR(255),
        type ENUM('board', 'department_director', 'department_member', 'campus_rep', 'alumni') NOT NULL,
        group_name VARCHAR(100),
        initials VARCHAR(10),
        linkedin VARCHAR(255),
        email VARCHAR(255),
        faculty VARCHAR(255),
        department_study VARCHAR(255),
        grade VARCHAR(50),
        university_logo VARCHAR(500),
        community_history_json JSON,
        phone VARCHAR(50),
        github VARCHAR(255),
        website VARCHAR(255),
        company_logo VARCHAR(500),
        current_job VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 5. Create site_history table (for achievements and awards)
    $db->exec("DROP TABLE IF EXISTS site_history");
    $db->exec("CREATE TABLE site_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type ENUM('achievement', 'award') NOT NULL,
        year VARCHAR(20),
        title VARCHAR(255),
        description TEXT,
        tag VARCHAR(100),
        tag_color VARCHAR(100),
        organization VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    echo "Tablolar basariyla olusturuldu.\n";

    // --- SEED site_settings ---
    $settings = [
        'popupConfig' => [
            'enabled' => true,
            'storageKey' => 'innomis_announcement_seen_v1',
            'autoCloseSec' => 5,
            'badge' => '📣 Duyuru',
            'title' => 'InnoMIS Community\'ye Hoş Geldin!',
            'description' => 'Teknoloji, girişimcilik ve yapay zeka dünyasına adım atmaya hazır mısın? InnoMIS Community\'nin etkinliklerini, eğitimlerini ve projelerini takip et — topluluğun bir parçası ol!',
            'cta' => ['label' => 'Topluluğa Katıl', 'href' => '/kayit'],
            'secondaryCta' => ['label' => 'Keşfet', 'href' => '/#about']
        ],
        'stats' => [
            ['label' => 'Aktif Üye', 'value' => '500+', 'desc' => 'Topluluk ekosisteminde yer alan öğrenci ağı.'],
            ['label' => 'Etkinlik', 'value' => '25+', 'desc' => 'Workshop, panel, zirve ve buluşmalar.'],
            ['label' => 'Eğitim Saati', 'value' => '40+', 'desc' => 'Teknoloji ve kariyer odaklı eğitim içerikleri.'],
            ['label' => 'İş Birliği', 'value' => '15+', 'desc' => 'Marka, topluluk ve sektör paydaşları.']
        ],
        'sponsors' => [
            '/innomis/assets/sponsor/kapsul.png',
            '/innomis/assets/sponsor/redbull.png',
            '/innomis/assets/sponsor/sorbil.webp',
            '/innomis/assets/sponsor/techcareernet.png',
            '/innomis/assets/uni/kgtuLogo.png',
            '/innomis/assets/uni/ktoLogo.png',
            '/innomis/assets/uni/ktunLogo.png',
            '/innomis/assets/uni/neuLogo.png',
            '/innomis/assets/uni/selcukLogo.png'
        ],
        'faqs' => [
            ['q' => 'InnoMIS Community’ye kimler katılabilir?', 'a' => 'Teknoloji, girişimcilik, kariyer ve topluluk çalışmalarına ilgi duyan üniversite öğrencileri katılabilir.'],
            ['q' => 'Etkinliklere katılım ücretli mi?', 'a' => 'Etkinliğe göre değişebilir. Duyurularda katılım koşulları açıkça belirtilir.'],
            ['q' => 'Ekiplerde görev almak mümkün mü?', 'a' => 'Evet. Sosyal medya, etkinlik, Ar-Ge, sponsorluk, tasarım, yazılım ve operasyon ekiplerinde görev alınabilir.'],
            ['q' => 'Sponsor veya partner olarak nasıl iletişime geçilir?', 'a' => 'İletişim formu, e-posta veya sosyal medya hesapları üzerinden toplulukla iletişime geçilebilir.'],
            ['q' => 'Etkinlik duyurularını nereden takip edebilirim?', 'a' => 'Instagram, LinkedIn ve web sitesi üzerinden güncel duyurular takip edilebilir.']
        ]
    ];

    $stmtSet = $db->prepare("INSERT INTO site_settings (key_name, json_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE json_value = VALUES(json_value)");
    foreach ($settings as $key => $val) {
        $stmtSet->execute([$key, json_encode($val, JSON_UNESCAPED_UNICODE)]);
    }
    echo "site_settings verileri eklendi.\n";

    // --- SEED site_projects ---
    $projects = [
        ['Topluluk Yönetim Sistemi', 'Üye, ekip ve etkinlik süreçlerini daha düzenli yönetmek için geliştirilen dijital altyapı.', 'Geliştiriliyor', 'Web', 'Code', null],
        ['InnoMIS Akademi', 'Topluluk içi düzenlenen eğitimlerin (video, not, atölye materyalleri) depolanacağı dijital öğrenme portalı.', 'Yakında', 'E-Learning', 'MonitorSmartphone', null],
        ['Kariyer & Mezun Ağı Portalı', 'Mezunların staj ve iş ilanlarını aktif öğrencilerle paylaşabildiği, mentorluk süreçlerinin yürütüldüğü iletişim aracı.', 'Planlanıyor', 'HR Tech', 'Users', null],
        ['Hackathon Yönetim Aracı', 'Takım oluşturma, proje teslimi ve jüri puanlama süreçlerini dijitalleştiren altyapı.', 'Fikir Aşaması', 'Event Tech', 'Sparkles', null],
        ['Sosyal Medya Tasarım Hafızası', 'Topluluğun kurumsal kimliğini koruyan görsel arşiv ve tasarım sistemi.', 'Aktif', 'Design', 'Palette', '#'],
        ['AI Deney Alanı', 'Yapay zeka araçlarıyla içerik, otomasyon ve fikir geliştirme denemeleri.', 'Deneysel', 'AI', 'Bot', '#']
    ];
    $db->exec("TRUNCATE TABLE site_projects");
    $stmtProj = $db->prepare("INSERT INTO site_projects (title, description, status, category, icon, link) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($projects as $p) {
        $stmtProj->execute($p);
    }
    echo "site_projects verileri eklendi.\n";

    // --- SEED site_gallery ---
    $gallery = [
        ['MIS CONNECT Sahnesi', 'Zirve', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=1000&auto=format&fit=crop'],
        ['AI Workshop', 'Eğitim', 'https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=1000&auto=format&fit=crop'],
        ['Ekip Buluşması', 'Topluluk', 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=1000&auto=format&fit=crop'],
        ['Kariyer Paneli', 'Etkinlik', 'https://images.unsplash.com/photo-1475721025505-44fbbf23d436?q=80&w=1000&auto=format&fit=crop'],
        ['Topluluk Anı', 'Sosyal', 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?q=80&w=1000&auto=format&fit=crop'],
        ['Hazırlık Süreci', 'Ekip', 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=1000&auto=format&fit=crop']
    ];
    $db->exec("TRUNCATE TABLE site_gallery");
    $stmtGal = $db->prepare("INSERT INTO site_gallery (title, category, image_url) VALUES (?, ?, ?)");
    foreach ($gallery as $g) {
        $stmtGal->execute($g);
    }
    echo "site_gallery verileri eklendi.\n";

    // --- SEED site_history ---
    $achievements = [
        ['achievement', '2026', 'MIS CONNECT’26 Hazırlıkları', 'Teknoloji ve kariyer odaklı büyük zirve için ekipler, tasarımlar ve dijital altyapı hazırlıkları başlatıldı.', 'Zirve', 'text-primary border-primary/30', null],
        ['achievement', '2025', 'Yılın Sosyal Medyayı En İyi Yöneten Topluluğu', 'Topluluğun dijital iletişim, içerik ve görsel kimlik başarısı ödüllendirildi.', 'Ödül', 'text-accent-green border-accent-green/30', null],
        ['achievement', '2025', 'Kurumsal Kimlik Yenilendi', 'Renk paleti, görsel dil, maskot, sosyal medya tasarımları ve web kimliği yeniden tasarlandı.', 'Marka', 'text-accent-purple border-accent-purple/30', null],
        ['achievement', '2024', 'Yeni Ekip Yapılanması', 'Etkinlik, sosyal medya, Ar-Ge, sponsorluk ve operasyon ekipleri daha sistemli hale getirildi.', 'Ekip', 'text-accent-cyan border-accent-cyan/30', null],
        ['award', '2025', 'Yılın En İyi Topluluk İletişimi', 'Topluluğun dijital iletişim ve sosyal medya başarısı.', null, null, 'Tech Community Awards'],
        ['award', '2024', 'Yılın İnovatif Teknoloji Topluluğu', 'Yıl boyunca üretilen projeler ve sağlanan ekosistem katkısı.', null, null, 'Genç Bilişimciler Zirvesi'],
        ['award', '2023', 'En Aktif Öğrenci Topluluğu', 'Yıl boyunca düzenlenen yüksek katılımlı teknik eğitimler.', null, null, 'Üniversite Etkinlikleri Zirvesi']
    ];
    $db->exec("TRUNCATE TABLE site_history");
    $stmtHist = $db->prepare("INSERT INTO site_history (type, year, title, description, tag, tag_color, organization) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($achievements as $a) {
        $stmtHist->execute($a);
    }
    echo "site_history verileri eklendi.\n";

    // --- SEED site_team_members ---
    $db->exec("TRUNCATE TABLE site_team_members");
    $stmtTeam = $db->prepare("INSERT INTO site_team_members 
        (name, role, type, group_name, initials, linkedin, email, faculty, department_study, grade, university_logo, community_history_json, phone, github, website, company_logo, current_job) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    function insertTeam($data) {
        global $stmtTeam;
        $history = isset($data['history']) ? json_encode($data['history'], JSON_UNESCAPED_UNICODE) : null;
        $stmtTeam->execute([
            $data['name'], $data['role'], $data['type'], $data['group_name'], $data['initials'] ?? null, 
            $data['linkedin'] ?? null, $data['email'] ?? null, $data['faculty'] ?? null, 
            $data['department_study'] ?? null, $data['grade'] ?? null, $data['university_logo'] ?? null, 
            $history, $data['phone'] ?? null, $data['github'] ?? null, $data['website'] ?? null, 
            $data['company_logo'] ?? null, $data['current_job'] ?? null
        ]);
    }

    // Board
    insertTeam(['name'=>'Ahmet Yılmaz', 'role'=>'Yönetim Kurulu Başkanı', 'type'=>'board', 'group_name'=>'Yönetim Kurulu', 'initials'=>'AY', 'linkedin'=>'#', 'email'=>'ahmet.yilmaz@innomis.tr', 'faculty'=>'Mühendislik Mimarlık Fakültesi', 'department_study'=>'Bilgisayar Mühendisliği', 'grade'=>'4. Sınıf', 'university_logo'=>'/innomis/assets/uni/neuLogo.png', 'history'=>[['period'=>'Oca 2025 – Devam ediyor · 6 ay', 'title'=>'Yönetim Kurulu Başkanı', 'dept'=>'Yönetim Kurulu'],['period'=>'Mar 2024 – Oca 2025 · 10 ay', 'title'=>'Genel Koordinatör', 'dept'=>'Organizasyon'],['period'=>'2023', 'title'=>'Genel Üye', 'dept'=>'InnoMIS Community']]]);
    insertTeam(['name'=>'Ayşe Demir', 'role'=>'Başkan Yardımcısı', 'type'=>'board', 'group_name'=>'Yönetim Kurulu', 'initials'=>'AD', 'linkedin'=>'#', 'email'=>'ayse.demir@innomis.tr', 'faculty'=>'Sosyal ve Beşeri Bilimler Fakültesi', 'department_study'=>'İşletme', 'grade'=>'3. Sınıf', 'university_logo'=>'/innomis/assets/uni/neuLogo.png', 'history'=>[['period'=>'Şub 2025 – Devam ediyor · 5 ay', 'title'=>'Başkan Yardımcısı', 'dept'=>'Yönetim Kurulu'],['period'=>'Eyl 2024 – Şub 2025 · 6 ay', 'title'=>'İnsan Kaynakları Direktörü', 'dept'=>'İnsan Kaynakları'],['period'=>'2023', 'title'=>'Genel Üye', 'dept'=>'InnoMIS Community']]]);

    echo "site_team_members (board) eklendi.\n";
    echo "Seed islemi basariyla tamamlandi.\n";

} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
