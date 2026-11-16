<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/includes/auth.php';
check_auth();

header('Content-Type: application/json; charset=utf-8');

$tables = [
    'announcements' => 'announcements',
    'branches' => 'branches',
    'community_groups' => 'community_groups',
    'community_posts' => 'community_posts',
    'community_users' => 'community_users',
    'countries' => 'countries',
    'gallery_items' => 'gallery_items',
    'news_articles' => 'news_articles',
    'services' => 'services',
    'site_popup' => 'site_popup',
    'specialist_services' => 'specialist_services',
    'testimonials' => 'testimonials',
    'testimonial_videos' => 'testimonial_videos',
    'test_preps' => 'test_preps',
    'universities' => 'universities',
    'courses' => 'courses',
    'scholarships' => 'scholarships'
];

$entity = isset($_POST['entity']) && is_string($_POST['entity']) ? $_POST['entity'] : '';
$id = isset($_POST['id']) && is_string($_POST['id']) ? filter_var($_POST['id'], FILTER_VALIDATE_INT) : false;
$status = isset($_POST['status']) && is_string($_POST['status']) ? filter_var($_POST['status'], FILTER_VALIDATE_INT) : false;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($tables[$entity]) || $id === false || $id <= 0 || !in_array($status, [0, 1], true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid active status update.']);
    exit;
}

try {
    $table = $tables[$entity];
    $stmt = $pdo->prepare("UPDATE `$table` SET is_active = :status WHERE id = :id");
    $stmt->execute(['status' => $status, 'id' => $id]);

    if ($stmt->rowCount() === 0) {
        $exists = $pdo->prepare("SELECT 1 FROM `$table` WHERE id = :id");
        $exists->execute(['id' => $id]);
        if (!$exists->fetchColumn()) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'The selected record was not found.']);
            exit;
        }
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    error_log('Admin active toggle failed for ' . $entity . ' record ' . $id . ': ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Could not update the active status.']);
}
