<?php
session_start();

if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'student') {
    http_response_code(401);
    exit('Please sign in with a student account.');
}

require_once __DIR__ . '/includes/config.php';

$messageId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($messageId === false) {
    http_response_code(404);
    exit('File not found.');
}

try {
    $fileStmt = $pdo->prepare("
        SELECT m.attachment_path, m.attachment_name, m.attachment_mime
        FROM student_direct_messages m
        INNER JOIN student_community_connections c
            ON c.user_one = LEAST(m.sender_id, m.recipient_id)
           AND c.user_two = GREATEST(m.sender_id, m.recipient_id)
           AND c.status = 'accepted'
        WHERE m.id = ?
          AND m.attachment_path IS NOT NULL
          AND (m.sender_id = ? OR m.recipient_id = ?)
        LIMIT 1
    ");
    $fileStmt->execute([$messageId, (int) $_SESSION['user_id'], (int) $_SESSION['user_id']]);
    $file = $fileStmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Student personal message attachment lookup failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Unable to load this file.');
}

if (!$file || !preg_match('/\A[a-f0-9]{32}\.[a-z0-9]{1,10}\z/', (string) $file['attachment_path'])) {
    http_response_code(404);
    exit('File not found.');
}

$filePath = __DIR__ . '/uploads/direct_messages/' . $file['attachment_path'];
if (!is_file($filePath) || !is_readable($filePath)) {
    http_response_code(404);
    exit('File not found.');
}

$allowedMimes = [
    'image/jpeg', 'image/png', 'image/gif', 'image/webp',
    'video/mp4', 'video/webm', 'video/quicktime',
    'application/pdf', 'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/vnd.ms-powerpoint',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'application/zip', 'text/plain', 'text/csv',
];
$mime = in_array($file['attachment_mime'], $allowedMimes, true) ? $file['attachment_mime'] : 'application/octet-stream';
$originalName = (string) $file['attachment_name'];
$fallbackName = preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName) ?: 'attachment';
$forceDownload = isset($_GET['download']) && $_GET['download'] === '1';
$disposition = !$forceDownload && (strpos($mime, 'image/') === 0 || strpos($mime, 'video/') === 0)
    ? 'inline'
    : 'attachment';
$size = filesize($filePath);

header('Content-Type: ' . $mime);
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store, max-age=0');
header('Content-Disposition: ' . $disposition . '; filename="' . $fallbackName . '"; filename*=UTF-8\'\'' . rawurlencode($originalName));
header('Accept-Ranges: bytes');

$start = 0;
$end = $size - 1;
if (isset($_SERVER['HTTP_RANGE']) && preg_match('/\Abytes=(\d*)-(\d*)\z/', trim($_SERVER['HTTP_RANGE']), $range)) {
    if ($range[1] === '' && $range[2] !== '') {
        $start = max(0, $size - (int) $range[2]);
    } else {
        $start = (int) $range[1];
        if ($range[2] !== '') {
            $end = min($end, (int) $range[2]);
        }
    }

    if ($start > $end || $start >= $size) {
        http_response_code(416);
        header('Content-Range: bytes */' . $size);
        exit;
    }
    http_response_code(206);
    header("Content-Range: bytes {$start}-{$end}/{$size}");
}

$length = $end - $start + 1;
header('Content-Length: ' . $length);
$handle = fopen($filePath, 'rb');
if ($handle === false) {
    http_response_code(500);
    exit('Unable to read this file.');
}
fseek($handle, $start);
while ($length > 0 && !feof($handle)) {
    $chunk = fread($handle, min(8192, $length));
    if ($chunk === false || $chunk === '') {
        break;
    }
    echo $chunk;
    $length -= strlen($chunk);
}
fclose($handle);
