<?php
header('Content-Type: application/json; charset=utf-8');
$input = file_get_contents('php://input');
$data = json_decode($input, true);
if (!is_array($data) || empty($data['imageData'])) {
    http_response_code(400);
    echo json_encode(['error' => '缺少图像数据']);
    exit;
}
$imageData = $data['imageData'];
if (strpos($imageData, 'data:image') !== 0 || strpos($imageData, ';base64,') === false) {
    http_response_code(400);
    echo json_encode(['error' => '图像数据格式不正确']);
    exit;
}
list($meta, $content) = explode(',', $imageData, 2);
$extension = 'png';
if (strpos($meta, 'image/jpeg') !== false) {
    $extension = 'jpg';
} elseif (strpos($meta, 'image/webp') !== false) {
    $extension = 'webp';
}
$binary = base64_decode($content);
if ($binary === false) {
    http_response_code(400);
    echo json_encode(['error' => '图像数据无法解析']);
    exit;
}
$uploadDir = __DIR__ . '/../uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0775, true);
}
$filename = 'badge_' . uniqid() . '.' . $extension;
$filePath = $uploadDir . '/' . $filename;
if (file_put_contents($filePath, $binary) === false) {
    http_response_code(500);
    echo json_encode(['error' => '图像保存失败']);
    exit;
}
$relativePath = 'uploads/' . $filename;
echo json_encode(['path' => $relativePath]);
