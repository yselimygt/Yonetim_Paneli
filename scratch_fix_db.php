<?php
require 'backend/config/database.php';
$pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET, DB_USER, DB_PASS);

$update1 = "UPDATE users SET university_logo = REPLACE(university_logo, 'http://localhost/innomis.tr/backend/assets/uni/', '/assets/uni/') WHERE university_logo LIKE '%http://localhost/innomis.tr/backend/assets/uni/%'";
$stmt1 = $pdo->prepare($update1);
$stmt1->execute();
echo "Updated " . $stmt1->rowCount() . " rows from localhost URL.\n";

$update2 = "UPDATE users SET university_logo = REPLACE(university_logo, '/innomis/assets/uni/', '/assets/uni/') WHERE university_logo LIKE '%/innomis/assets/uni/%'";
$stmt2 = $pdo->prepare($update2);
$stmt2->execute();
echo "Updated " . $stmt2->rowCount() . " rows from /innomis/ URL.\n";
