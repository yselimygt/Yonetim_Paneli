<?php
require 'backend/config/database.php';
require 'backend/core/Database.php';

$db = Database::getInstance();
try {
    $db->query("ALTER TABLE departments ADD COLUMN tag VARCHAR(50) DEFAULT 'main' AFTER is_management");
    echo "Column 'tag' added successfully.";
} catch (Exception $e) {
    echo "Error or column already exists: " . $e->getMessage();
}
