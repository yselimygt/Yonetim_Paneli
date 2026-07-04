<?php
require 'backend/config/database.php';
require 'backend/core/Database.php';
$db = Database::getInstance();
try {
    $db->query("ALTER TABLE app_events ADD COLUMN location_link VARCHAR(255) DEFAULT NULL");
    echo "Added to app_events\n";
} catch (Exception $e) {
    echo "app_events: " . $e->getMessage() . "\n";
}

try {
    $db->query("ALTER TABLE site_events ADD COLUMN location_link VARCHAR(255) DEFAULT NULL");
    echo "Added to site_events\n";
} catch (Exception $e) {
    echo "site_events: " . $e->getMessage() . "\n";
}
