<?php
header('Content-Type: application/json');

// Get JSON data from request body
$input = file_get_contents('php://input');
$newDraft = json_decode($input, true);

if (!$newDraft) {
    echo json_encode(['status' => 'error', 'message' => 'Geçersiz veri']);
    exit;
}

$file = 'database.json';

// Initialize file if not exists
if (!file_exists($file)) {
    file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
}

// Read current drafts
$current = file_get_contents($file);
$drafts = json_decode($current, true);

if (!is_array($drafts)) {
    $drafts = [];
}

// Add new draft
if (!isset($newDraft['status'])) {
    $newDraft['status'] = 'Beklemede';
}
$drafts[] = $newDraft;

// Save back to file
if (file_put_contents($file, json_encode($drafts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['status' => 'success', 'id' => $newDraft['id']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Dosya yazılamadı']);
}
?>
