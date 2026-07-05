<?php
require_once '../includes/db.php';

$username = 'mekroket';
$password = 'Oguz.438';
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = ?");
    $stmt->execute([$hash, $username]);
    
    if ($stmt->rowCount() > 0) {
        echo "Şifre başarıyla güncellendi. Yeni şifre: $password";
    } else {
        // Try inserting if not exists
        $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        try {
            $stmt->execute([$username, $hash]);
            echo "Admin kullanıcısı oluşturuldu. Şifre: $password";
        } catch (PDOException $e) {
            echo "Güncelleme yapılamadı (Kullanıcı zaten var olabilir ama şifre aynı olabilir) veya hata oluştu: " . $e->getMessage();
        }
    }
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?>
