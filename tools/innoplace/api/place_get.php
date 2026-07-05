<?php
// api/place_get.php
error_reporting(0); // HTML hatalarını gizle
ini_set('display_errors', 0);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// HOSTING VERİTABANI BİLGİLERİ
$db_host = "localhost";
$db_user = "innomist_place";
$db_pass = "Oguz.438";
$db_name = "innomist_innoplace_pixels";

try {
    $conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        throw new Exception("Veritabanı bağlantı hatası");
    }

    $result = $conn->query("SELECT x, y, color FROM innoplace_pixels");

    $pixels = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $pixels[] = $row;
        }
    }

    echo json_encode($pixels);
    $conn->close();

} catch (Exception $e) {
    echo json_encode([]); // Hata olursa boş dizi dön
}
?>