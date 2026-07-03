<?php
require 'backend/config/database.php';
$pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET, DB_USER, DB_PASS);
$stmt = $pdo->query("SELECT id, user_id, user_university_logo FROM site_team_profiles WHERE user_university_logo IS NOT NULL");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Profile ID " . $row['id'] . ": " . $row['user_university_logo'] . "\n";
}
