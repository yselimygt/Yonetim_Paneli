<?php
$host = 'localhost';
$dbname = 'innomist_mytree';
$username = 'innomist_tree';
$password = 'Oguz.438';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If database doesn't exist, we might be running the setup script, so we can ignore or handle
    if (strpos($e->getMessage(), "Unknown database") !== false) {
        // Database might not exist yet
    } else {
        die("Connection failed: " . $e->getMessage());
    }
}
?>
