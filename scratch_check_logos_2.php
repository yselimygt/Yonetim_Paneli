<?php
require 'backend/config/database.php';
$pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET, DB_USER, DB_PASS);
$stmt = $pdo->query("SELECT id, name, university_logo FROM users");
while($row = $stmt->fetch()) {
    if ($row['university_logo']) {
        echo "User " . $row['id'] . " (" . $row['name'] . "): " . $row['university_logo'] . "\n";
    }
}
