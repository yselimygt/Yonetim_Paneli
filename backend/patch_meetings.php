<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/core/Database.php';

try {
    $pdo = Database::getInstance();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE TABLE IF NOT EXISTS `app_meetings` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `app_id` INT,
        `department_id` INT,
        `created_by` VARCHAR(255),
        `title` VARCHAR(255),
        `meeting_date` DATETIME,
        `location` VARCHAR(255),
        `agenda` TEXT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `app_meeting_approvals` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `meeting_id` INT,
        `user_id` INT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // Add 'status' column to app_meetings
    try {
        $pdo->exec("ALTER TABLE `app_meetings` ADD COLUMN `status` VARCHAR(255) DEFAULT 'upcoming'");
    } catch (PDOException $e) {}

    // Add 'decisions' column to app_meetings
    try {
        $pdo->exec("ALTER TABLE `app_meetings` ADD COLUMN `decisions` TEXT");
    } catch (PDOException $e) {}

    // Alter 'meeting_date' to DATETIME
    try {
        $pdo->exec("ALTER TABLE `app_meetings` MODIFY COLUMN `meeting_date` DATETIME");
    } catch (PDOException $e) {}

    // Add 'approved_at' to app_meeting_approvals
    try {
        $pdo->exec("ALTER TABLE `app_meeting_approvals` ADD COLUMN `approved_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    } catch (PDOException $e) {}

    echo "Meetings database patched successfully.\n";

} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
}
