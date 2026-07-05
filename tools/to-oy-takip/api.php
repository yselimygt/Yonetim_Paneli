<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$votesFile = 'votes.json';

// Veritabanı dosyası yoksa oluştur
if (!file_exists($votesFile)) {
    file_put_contents($votesFile, json_encode([]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = isset($input['action']) ? $input['action'] : '';

    if ($action === 'vote') {
        $id = $input['id'];
        $change = (int)$input['change'];

        // Dosya kilitlenerek aynı anda birden fazla kişinin veriyi bozması önleniyor
        $fileLock = fopen($votesFile, 'c+');
        flock($fileLock, LOCK_EX);

        $filesize = filesize($votesFile);
        $data = [];
        if ($filesize > 0) {
            $jsonStr = fread($fileLock, $filesize);
            $data = json_decode($jsonStr, true) ?: [];
        }

        if (!isset($data[$id])) {
            $data[$id] = 0;
        }

        $data[$id] += $change;
        if ($data[$id] < 0) $data[$id] = 0; // Oyların eksiye düşmesine izin verilmez

        ftruncate($fileLock, 0);
        rewind($fileLock);
        fwrite($fileLock, json_encode($data));
        flock($fileLock, LOCK_UN);
        fclose($fileLock);

        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    if ($action === 'reset') {
        file_put_contents($votesFile, json_encode([]));
        echo json_encode(['status' => 'success', 'data' => []]);
        exit;
    }
}

// GET İsteği - Sadece mevcut verileri okuyup döner
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = file_get_contents($votesFile);
    echo $data ?: '{}';
    exit;
}
?>
