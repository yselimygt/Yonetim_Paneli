<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Database.php';

try {
    $pdo = Database::getInstance();

    // 1. Create table
    $pdo->exec("DROP TABLE IF EXISTS site_events");
    $sql = "
    CREATE TABLE IF NOT EXISTS site_events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        event_date DATETIME,
        end_date DATETIME,
        location VARCHAR(255),
        platform VARCHAR(255),
        event_type VARCHAR(50) DEFAULT 'physical',
        category VARCHAR(100),
        tag_color VARCHAR(100),
        status VARCHAR(50) DEFAULT 'upcoming',
        image_url VARCHAR(255),
        target_date DATETIME NULL,
        registration_link VARCHAR(255),
        speakers JSON NULL,
        agenda JSON NULL,
        xp_reward INT DEFAULT 10,
        committee_id INT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql);
    echo "Table 'site_events' created or already exists.\n";

    // 2. Clear existing data (optional, but good for idempotent seed)
    $pdo->exec("TRUNCATE TABLE site_events");
    echo "Table 'site_events' truncated.\n";

    // 3. Seed data
    $events = [
        [
            'title' => "MIS CONNECT'26",
            'description' => 'Teknoloji, kariyer, girişimcilik ve inovasyon ekosistemini öğrencilerle buluşturan güçlü bir zirve deneyimi.',
            'event_date' => '2026-04-15 09:00:00',
            'location' => 'Konya',
            'category' => 'Zirve',
            'tag_color' => 'bg-primary/20 text-primary border-primary/20',
            'status' => 'upcoming',
            'image_url' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&q=80&w=800',
            'target_date' => '2026-04-15 09:00:00',
            'registration_link' => '#',
            'speakers' => json_encode([
                ['name' => 'Dr. Ahmet Yılmaz', 'role' => 'Yapay Zeka Araştırmacısı'],
                ['name' => 'Zeynep Kaya', 'role' => 'Startup Kurucusu']
            ], JSON_UNESCAPED_UNICODE),
            'agenda' => json_encode([
                ['time' => '09:00 - 10:00', 'activity' => 'Kayıt ve Networking'],
                ['time' => '10:00 - 11:30', 'activity' => 'Açılış Konuşmaları'],
                ['time' => '11:45 - 13:00', 'activity' => 'Geleceğin Teknolojileri Paneli']
            ], JSON_UNESCAPED_UNICODE)
        ],
        [
            'title' => 'AI Workshop',
            'description' => 'Yapay zeka araçları ve üretken teknoloji üzerine uygulamalı eğitim.',
            'event_date' => '2025-10-10 14:00:00', // Mock future date
            'location' => 'Selçuk Üniversitesi',
            'category' => 'Eğitim',
            'tag_color' => 'bg-accent-purple/20 text-accent-purple border-accent-purple/20',
            'status' => 'upcoming',
            'image_url' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?auto=format&fit=crop&q=80&w=800',
            'target_date' => null,
            'registration_link' => '#',
            'speakers' => json_encode([
                ['name' => 'Murat Can', 'role' => 'AI Engineer']
            ], JSON_UNESCAPED_UNICODE),
            'agenda' => json_encode([
                ['time' => '14:00 - 15:00', 'activity' => 'Prompt Engineering Temelleri'],
                ['time' => '15:15 - 16:30', 'activity' => 'Uygulamalı Proje Geliştirme']
            ], JSON_UNESCAPED_UNICODE)
        ],
        [
            'title' => 'Girişimcilik Buluşması',
            'description' => 'Fikirden ürüne giden süreç ve startup ekosistemi üzerine söyleşi.',
            'event_date' => '2025-11-20 13:00:00', // Mock past date conceptually
            'location' => 'KTO Karatay Üni.',
            'category' => 'Panel',
            'tag_color' => 'bg-accent-cyan/20 text-accent-cyan border-accent-cyan/20',
            'status' => 'past',
            'image_url' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32d7?auto=format&fit=crop&q=80&w=800',
            'target_date' => null,
            'registration_link' => null,
            'speakers' => json_encode([
                ['name' => 'Elif Şahin', 'role' => 'Melek Yatırımcı']
            ], JSON_UNESCAPED_UNICODE),
            'agenda' => json_encode([
                ['time' => '13:00 - 15:00', 'activity' => 'Girişimcilik Söyleşisi']
            ], JSON_UNESCAPED_UNICODE)
        ],
        [
            'title' => 'Kariyer Paneli',
            'description' => 'Sektör profesyonellerinden kariyer yolculuğu tavsiyeleri.',
            'event_date' => '2025-12-01 10:00:00', // Mock past date
            'location' => 'KTÜN',
            'category' => 'Kariyer',
            'tag_color' => 'bg-accent-green/20 text-accent-green border-accent-green/20',
            'status' => 'past',
            'image_url' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&q=80&w=800',
            'target_date' => null,
            'registration_link' => null,
            'speakers' => json_encode([
                ['name' => 'Burak Aydın', 'role' => 'Kariyer Danışmanı']
            ], JSON_UNESCAPED_UNICODE),
            'agenda' => null
        ]
    ];

    $stmt = $pdo->prepare("
        INSERT INTO site_events (
            title, description, event_date, location, category, tag_color, status, image_url, target_date, registration_link, speakers, agenda
        ) VALUES (
            :title, :description, :event_date, :location, :category, :tag_color, :status, :image_url, :target_date, :registration_link, :speakers, :agenda
        )
    ");

    foreach ($events as $event) {
        $stmt->execute([
            ':title' => $event['title'],
            ':description' => $event['description'],
            ':event_date' => $event['event_date'],
            ':location' => $event['location'],
            ':category' => $event['category'],
            ':tag_color' => $event['tag_color'],
            ':status' => $event['status'],
            ':image_url' => $event['image_url'],
            ':target_date' => $event['target_date'],
            ':registration_link' => $event['registration_link'],
            ':speakers' => $event['speakers'],
            ':agenda' => $event['agenda']
        ]);
    }
    
    echo "Seed data inserted successfully.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
