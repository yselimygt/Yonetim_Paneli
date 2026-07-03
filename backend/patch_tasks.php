<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/core/Database.php';

try {
    $pdo = Database::getInstance();

    $pdo->exec("CREATE TABLE IF NOT EXISTS `app_tasks` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(255) NOT NULL,
        `description` text DEFAULT NULL,
        `created_by` int(11) NOT NULL,
        `status` varchar(50) DEFAULT 'pending',
        `due_date` datetime DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        KEY `idx_app_tasks_created_by` (`created_by`),
        CONSTRAINT `fk_app_tasks_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `app_task_assignees` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `task_id` int(11) NOT NULL,
        `user_id` int(11) NOT NULL,
        `status` varchar(50) DEFAULT 'pending',
        `completed_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_app_task_assignees_task` (`task_id`),
        KEY `idx_app_task_assignees_user` (`user_id`),
        CONSTRAINT `fk_app_task_assignees_task` FOREIGN KEY (`task_id`) REFERENCES `app_tasks` (`id`) ON DELETE CASCADE,
        CONSTRAINT `fk_app_task_assignees_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `app_task_comments` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `task_id` int(11) NOT NULL,
        `user_id` int(11) NOT NULL,
        `comment` text NOT NULL,
        `created_at` timestamp NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        KEY `idx_app_task_comments_task` (`task_id`),
        KEY `idx_app_task_comments_user` (`user_id`),
        CONSTRAINT `fk_app_task_comments_task` FOREIGN KEY (`task_id`) REFERENCES `app_tasks` (`id`) ON DELETE CASCADE,
        CONSTRAINT `fk_app_task_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `app_task_teams` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `description` varchar(255) DEFAULT NULL,
        `created_by` int(11) NOT NULL,
        `created_at` timestamp NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        KEY `idx_app_task_teams_created_by` (`created_by`),
        CONSTRAINT `fk_app_task_teams_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `app_task_team_members` (
        `team_id` int(11) NOT NULL,
        `user_id` int(11) NOT NULL,
        PRIMARY KEY (`team_id`,`user_id`),
        KEY `idx_app_task_team_members_user` (`user_id`),
        CONSTRAINT `fk_app_task_team_members_team` FOREIGN KEY (`team_id`) REFERENCES `app_task_teams` (`id`) ON DELETE CASCADE,
        CONSTRAINT `fk_app_task_team_members_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    echo "Tables created successfully.\n";

    // Ensure is_global_module column exists
    try {
        $pdo->exec("ALTER TABLE apps ADD COLUMN is_global_module TINYINT(1) DEFAULT 0");
    } catch (Exception $e) {
        // Ignore if exists
    }

    // Insert module app if not exists for "yonetim"
    $stmt = $pdo->query("SELECT id FROM departments WHERE slug = 'yonetim' LIMIT 1");
    $dept = $stmt->fetch();
    if ($dept) {
        $stmtApp = $pdo->prepare("SELECT id FROM apps WHERE module_type = 'task_manager' LIMIT 1");
        $stmtApp->execute();
        $app = $stmtApp->fetch();
        if (!$app) {
            $pdo->prepare("INSERT INTO apps (name, slug, module_type, department_id, icon, is_global_module) VALUES (?, ?, ?, ?, ?, ?)")
                ->execute(['Görev Yönetimi', 'tasks', 'task_manager', $dept['id'], 'check-square', 1]);
            echo "App 'Görev Yönetimi' created.\n";
        } else {
            $pdo->prepare("UPDATE apps SET is_global_module = 1 WHERE module_type = 'task_manager'")->execute();
            echo "App 'Görev Yönetimi' already exists, set as global.\n";
        }
    } else {
        echo "Department 'yonetim' not found. App not created.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
