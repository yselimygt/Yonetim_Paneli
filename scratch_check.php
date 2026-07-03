<?php
require 'backend/config/database.php';
require 'backend/core/Database.php';
$db = Database::getInstance();
$stmt = $db->query("SELECT * FROM site_team_profiles");
$profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt2 = $db->query("SELECT * FROM user_departments");
$user_depts = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$stmt3 = $db->query("SELECT * FROM departments");
$depts = $stmt3->fetchAll(PDO::FETCH_ASSOC);

echo "--- Profiles ---\n";
print_r($profiles);
echo "\n--- User Depts ---\n";
print_r($user_depts);
echo "\n--- Depts ---\n";
print_r($depts);
