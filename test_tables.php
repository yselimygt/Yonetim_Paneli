<?php
require 'backend/config/database.php';
require 'backend/core/Database.php';

$db = Database::getInstance();
$stmt = $db->query('SHOW TABLES');
print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
