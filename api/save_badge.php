<?php
header('Content-Type: application/json; charset=utf-8');
$input = file_get_contents('php://input');
$data = json_decode($input, true);
if (!is_array($data) || empty($data['texturePath']) || empty($data['position'])) {
    http_response_code(400);
    echo json_encode(['error' => '参数不完整']);
    exit;
}
$texturePath = $data['texturePath'];
if (strpos($texturePath, '..') !== false) {
    http_response_code(400);
    echo json_encode(['error' => '非法纹理路径']);
    exit;
}
$position = $data['position'];
$x = isset($position['x']) ? (float)$position['x'] : 0;
$z = isset($position['z']) ? (float)$position['z'] : 0;
$file = __DIR__ . '/../data/badges.json';
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}
$existing = json_decode(file_get_contents($file), true);
if (!is_array($existing)) {
    $existing = [];
}
$badge = [
    'id' => uniqid('badge_'),
    'texturePath' => $texturePath,
    'position' => ['x' => $x, 'z' => $z]
];
$existing[] = $badge;
if (file_put_contents($file, json_encode($existing, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) === false) {
    http_response_code(500);
    echo json_encode(['error' => '保存失败']);
    exit;
}
echo json_encode(['success' => true, 'badge' => $badge]);
