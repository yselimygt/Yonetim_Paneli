<?php
require 'backend/config/database.php';
require 'backend/core/Database.php';
$db = Database::getInstance();

echo "=== Forms ===\n";
$stmt = $db->query("SELECT id, name, slug, module_type FROM apps WHERE module_type = 'form'");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
