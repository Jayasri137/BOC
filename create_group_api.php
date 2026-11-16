<?php
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'This request method is not supported.']);
    exit;
}

$_POST['action'] = 'create';
if (!isset($_POST['description']) && isset($_POST['desc'])) {
    $_POST['description'] = $_POST['desc'];
}

require __DIR__ . '/student-group-chat-api.php';
    