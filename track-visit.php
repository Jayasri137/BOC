<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit;
}

header('Cache-Control: no-store, max-age=0');
header('X-Content-Type-Options: nosniff');

$cookieName = 'bo_visitor_id';
$visitorToken = $_COOKIE[$cookieName] ?? '';
if (!is_string($visitorToken) || !preg_match('/\A[a-f0-9]{64}\z/', $visitorToken)) {
    $visitorToken = bin2hex(random_bytes(32));
    setcookie($cookieName, $visitorToken, [
        'expires' => time() + 31536000,
        'path' => '/',
        'secure' => isset($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

$visitorHash = hash('sha256', $visitorToken);
$visitDate = gmdate('Y-m-d');

try {
    require_once __DIR__ . '/includes/config.php';

    $pdo->beginTransaction();

    $dailyVisit = $pdo->prepare("
        INSERT INTO website_visit_daily (visit_date, page_views, unique_visitors)
        VALUES (:visit_date, 1, 0)
        ON DUPLICATE KEY UPDATE page_views = page_views + 1
    ");
    $dailyVisit->execute(['visit_date' => $visitDate]);

    $dailyVisitor = $pdo->prepare("
        INSERT IGNORE INTO website_visit_visitors (visit_date, visitor_hash)
        VALUES (:visit_date, :visitor_hash)
    ");
    $dailyVisitor->execute([
        'visit_date' => $visitDate,
        'visitor_hash' => $visitorHash,
    ]);

    if ($dailyVisitor->rowCount() === 1) {
        $incrementVisitors = $pdo->prepare("
            UPDATE website_visit_daily
            SET unique_visitors = unique_visitors + 1
            WHERE visit_date = :visit_date
        ");
        $incrementVisitors->execute(['visit_date' => $visitDate]);
    }

    $pdo->commit();
    http_response_code(204);
} catch (PDOException $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Website visit tracking failed: ' . $e->getMessage());
    http_response_code(503);
}
