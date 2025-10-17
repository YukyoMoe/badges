<?php
header('Content-Type: application/json; charset=utf-8');
$file = __DIR__ . '/../data/badges.json';
if (!file_exists($file)) {
    echo json_encode([]);
    exit;
}
$raw = file_get_contents($file);
$data = json_decode($raw, true);
if (!is_array($data)) {
    $data = [];
}
echo json_encode($data);
