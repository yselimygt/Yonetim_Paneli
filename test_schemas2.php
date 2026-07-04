<?php
require 'backend/config/database.php';
require 'backend/core/Database.php';
$db = Database::getInstance();

echo "=== site_events ===\n";
print_r($db->query("SHOW COLUMNS FROM site_events")->fetchAll(PDO::FETCH_COLUMN));

echo "\n=== app_events ===\n";
print_r($db->query("SHOW COLUMNS FROM app_events")->fetchAll(PDO::FETCH_COLUMN));

echo "\n=== apps ===\n";
print_r($db->query("SHOW COLUMNS FROM apps")->fetchAll(PDO::FETCH_COLUMN));
