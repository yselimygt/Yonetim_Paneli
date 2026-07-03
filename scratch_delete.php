<?php
require 'backend/config/database.php';
require 'backend/core/Database.php';
$db = Database::getInstance();

$stmt = $db->prepare("DELETE FROM site_team_profiles WHERE user_id = 2 AND department_id IS NULL");
$stmt->execute();
echo "Silinen satir sayisi: " . $stmt->rowCount() . "\n";
