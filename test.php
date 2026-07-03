<?php
require 'backend/config/app.php';
require 'backend/config/database.php';
require 'backend/core/Database.php';

try {
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT id, config FROM apps WHERE module_type = 'shortener' AND config LIKE ? ESCAPE '\\\\'");
    $stmt->execute(['%test%']);
    echo 'success';
} catch (Exception $e) {
    echo $e->getMessage();
}
