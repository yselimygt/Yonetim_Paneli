<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/core/Database.php';

try {
    $pdo = Database::getInstance();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS `app_wiki_articles` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `app_id` INT,
        `department_id` INT,
        `author_id` INT,
        `title` VARCHAR(255),
        `content` TEXT,
        `category` VARCHAR(255),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // Try to add 'views' column if it doesn't exist
    try {
        $pdo->exec("ALTER TABLE `app_wiki_articles` ADD COLUMN `views` INT DEFAULT 0");
        echo "Added 'views' column to app_wiki_articles.\n";
    } catch (PDOException $e) {
        // 1060 Duplicate column name
        if ($e->errorInfo[1] == 1060) {
            echo "'views' column already exists.\n";
        } else {
            echo "Error adding 'views' column: " . $e->getMessage() . "\n";
        }
    }

    echo "Wiki database patched successfully.\n";

} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
}
