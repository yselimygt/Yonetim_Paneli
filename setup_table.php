<?php
require 'backend/core/Database.php';
require 'backend/config/app.php';
require 'backend/config/database.php';

try {
    $db = Database::getInstance();
    $sql = "CREATE TABLE IF NOT EXISTS app_inbox_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        app_id INT NOT NULL,
        department_id INT NOT NULL,
        type VARCHAR(50) NOT NULL,
        sender_name VARCHAR(255) NULL,
        company_name VARCHAR(255) NULL,
        email VARCHAR(255) NULL,
        phone VARCHAR(50) NULL,
        category VARCHAR(100) NULL,
        message TEXT NULL,
        status ENUM('unread', 'read', 'archived') DEFAULT 'unread',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (app_id) REFERENCES apps(id) ON DELETE CASCADE,
        FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    $db->exec($sql);
    echo "Tablo basariyla olusturuldu.\n";
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage() . "\n";
}
