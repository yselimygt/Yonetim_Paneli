<?php
require 'backend/config/database.php';
require 'backend/core/Database.php';
print_r(Database::getInstance()->query('DESCRIBE app_events')->fetchAll(PDO::FETCH_ASSOC));
print_r(Database::getInstance()->query('DESCRIBE site_events')->fetchAll(PDO::FETCH_ASSOC));
