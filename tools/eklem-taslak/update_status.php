<?php
header('Content-Type: application/json');

// Get JSON data from request body
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['id']) || !isset($data['status']) || !isset($data['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Geçersiz veri']);
    exit;
}

// Security: Simple password check
$ADMIN_PASSWORD = 'innox123'; // Updated password
if ($data['password'] !== $ADMIN_PASSWORD) {
    echo json_encode(['status' => 'error', 'message' => 'Hatalı şifre!']);
    exit;
}

$file = 'database.json';
if (!file_exists($file)) {
    echo json_encode(['status' => 'error', 'message' => 'Dosya bulunamadı']);
    exit;
}

// Read and update
$current = file_get_contents($file);
$drafts = json_decode($current, true);

$updated = false;
foreach ($drafts as &$d) {
    if ($d['id'] === $data['id']) {
        $d['status'] = $data['status'];
        $updated = true;
        break;
    }
}

if ($updated) {
    if (file_put_contents($file, json_encode($drafts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Dosya yazılamadı']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Taslak bulunamadı']);
}
?>
