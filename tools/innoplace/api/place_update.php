<?php
// api/place_update.php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

header("Content-Type: application/json");

// --- OYUN AYARLARI ---
$limit_sure = 30; // Saniye
$limit_hak = 15;  // Hak Sayısı

// 1. KİMLİK BELİRLEME (Girişsiz / IP Bazlı)
if (!empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Giriş yapmayanları IP adresinden tanı (Misafir Modu)
    $user_id = crc32($_SERVER['REMOTE_ADDR']); 
}

// HOSTING VERİTABANI BİLGİLERİ
$db_host = "localhost";
$db_user = "innomist_place";
$db_pass = "Oguz.438";
$db_name = "innomist_innoplace_pixels";

try {
    $conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) { throw new Exception("Bağlantı hatası"); }

    $inputJSON = file_get_contents("php://input");
    $data = json_decode($inputJSON);

    $x = $data->x ?? null; 
    $y = $data->y ?? null; 
    $color = $data->color ?? null;

    if (!is_numeric($x) || !is_numeric($y) || $x < 0 || $x > 99 || $y < 0 || $y > 99) {
        echo json_encode(["status" => "error", "message" => "Geçersiz koordinat."]);
        exit;
    }

    // 2. KOTA KONTROLÜ
    $stmt = $conn->prepare("SELECT TIMESTAMPDIFF(SECOND, created_at, NOW()) as saniye_farki FROM innoplace_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT ?");
    $stmt->bind_param("ii", $user_id, $limit_hak);
    $stmt->execute();
    $result = $stmt->get_result();

    $logs = [];
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row['saniye_farki'];
    }

    if (count($logs) >= $limit_hak) {
        $gecen_sure = $logs[$limit_hak - 1]; 
        if ($gecen_sure < 0) $gecen_sure = 0;

        if ($gecen_sure < $limit_sure) {
            $wait_seconds = $limit_sure - $gecen_sure;
            echo json_encode([
                "status" => "error",
                "code" => "COOLDOWN",
                "wait_seconds" => $wait_seconds,
                "message" => "Enerji doluyor..."
            ]);
            exit;
        }
    }

    // 3. BOYAMA İŞLEMİ
    $sql = "INSERT INTO innoplace_pixels (x, y, color, last_user_id, updated_at) 
            VALUES (?, ?, ?, ?, NOW()) 
            ON DUPLICATE KEY UPDATE color = VALUES(color), last_user_id = VALUES(last_user_id), updated_at = NOW()";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $x, $y, $color, $user_id);

    if ($stmt->execute()) {
        $conn->query("INSERT INTO innoplace_logs (user_id) VALUES ($user_id)");
        $conn->query("DELETE FROM innoplace_logs WHERE user_id = $user_id AND created_at < (NOW() - INTERVAL 120 SECOND)");
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Kayıt hatası."]);
    }
    $conn->close();

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Sunucu hatası."]);
}
?>