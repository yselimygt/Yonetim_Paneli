<?php
$md = file_get_contents('docs/konya_universiteleri_bolumleri.md');
$lines = explode("\n", $md);

$data = [];
$currentUni = null;
$currentFaculty = null;

foreach ($lines as $line) {
    $line = trim($line);
    if (empty($line)) continue;

    if (preg_match('/^# (?!Konya Üniversiteleri)(.+)/', $line, $matches)) {
        $currentUni = trim($matches[1]);
        $data[$currentUni] = [];
        $currentFaculty = null;
    } elseif (preg_match('/^### (.+)/', $line, $matches)) {
        $currentFaculty = trim($matches[1]);
        if ($currentUni) {
            $data[$currentUni][$currentFaculty] = [];
        }
    } elseif (preg_match('/^- (.+)/', $line, $matches)) {
        $dept = trim($matches[1]);
        if ($currentUni && $currentFaculty) {
            $data[$currentUni][$currentFaculty][] = $dept;
        }
    }
}

file_put_contents('scratch_parsed_universities.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Parsed.\n";
