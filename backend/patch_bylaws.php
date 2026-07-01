<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/core/Database.php';

try {
    $pdo = Database::getInstance();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS `app_bylaw_documents` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `app_id` INT,
        `department_id` INT,
        `title` VARCHAR(255),
        `description` TEXT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // Add 'is_active' column
    try {
        $pdo->exec("ALTER TABLE `app_bylaw_documents` ADD COLUMN `is_active` TINYINT(1) DEFAULT 1");
        echo "Added 'is_active' column to app_bylaw_documents.\n";
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1060) {
            echo "'is_active' column already exists.\n";
        } else {
            echo "Error adding 'is_active' column: " . $e->getMessage() . "\n";
        }
    }

    echo "Bylaws database patched successfully.\n";

} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
}
