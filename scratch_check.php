<?php
require 'backend/config/database.php';
require 'backend/core/Database.php';
$db = Database::getInstance();
$stmt = $db->query('SELECT university, faculty, school_department, grade FROM users WHERE id = 2');
print_r($stmt->fetch(PDO::FETCH_ASSOC));
