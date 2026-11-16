<?php
/**
 * Student Shortlist API - Server-side persistence
 * Replaces localStorage-only shortlist with database-backed storage.
 * 
 * GET:              List all shortlisted courses
 * POST action=add:  Add a course to shortlist
 * POST action=remove: Remove a course from shortlist
 * POST action=sync:   Bulk sync from localStorage (migration)
 */
session_start();
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store, max-age=0');

require_once __DIR__ . '/includes/config.php';

function shortlistResponse(int $status, array $payload): void {
    http_response_code($status);
    echo json_encode($payload, JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

// Auth check
if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'student') {
    shortlistResponse(401, ['success' => false, 'message' => 'Please sign in with a student account.']);
}

$userId = (int) $_SESSION['user_id'];

// ─── GET: List shortlisted courses ───
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->prepare("
            SELECT id, course_name, university_name, country_name, duration, tuition_fee, intakes, added_at
            FROM student_shortlist
            WHERE user_id = ?
            ORDER BY added_at DESC
        ");
        $stmt->execute([$userId]);
        $courses = $stmt->fetchAll();
        shortlistResponse(200, ['success' => true, 'courses' => $courses, 'count' => count($courses)]);
    } catch (PDOException $e) {
        error_log('Shortlist list failed: ' . $e->getMessage());
        shortlistResponse(500, ['success' => false, 'message' => 'Unable to load your shortlist.']);
    }
}

// POST requests require CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['csrf_token']) ||
        !is_string($_POST['csrf_token']) ||
        empty($_SESSION['community_csrf_token']) ||
        !hash_equals($_SESSION['community_csrf_token'], $_POST['csrf_token'])
    ) {
        // Also accept JSON body
        $jsonBody = json_decode(file_get_contents('php://input'), true);
        if (
            !is_array($jsonBody) ||
            !isset($jsonBody['csrf_token']) ||
            empty($_SESSION['community_csrf_token']) ||
            !hash_equals($_SESSION['community_csrf_token'], $jsonBody['csrf_token'])
        ) {
            shortlistResponse(403, ['success' => false, 'message' => 'Session expired. Refresh the page.']);
        }
        // Merge JSON body into POST
        $_POST = array_merge($_POST, $jsonBody);
    }
}

$action = $_POST['action'] ?? '';

// ─── ADD to shortlist ───
if ($action === 'add') {
    $courseName = trim($_POST['course_name'] ?? '');
    $universityName = trim($_POST['university_name'] ?? '');

    if (empty($courseName) || empty($universityName)) {
        shortlistResponse(422, ['success' => false, 'message' => 'Course and university name are required.']);
    }

    // Max 50 shortlisted courses
    try {
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM student_shortlist WHERE user_id = ?");
        $countStmt->execute([$userId]);
        if ((int) $countStmt->fetchColumn() >= 50) {
            shortlistResponse(429, ['success' => false, 'message' => 'Maximum 50 courses can be shortlisted.']);
        }
    } catch (PDOException $e) {}

    $countryName = mb_substr(trim($_POST['country_name'] ?? ''), 0, 100);
    $duration = mb_substr(trim($_POST['duration'] ?? ''), 0, 100);
    $tuitionFee = mb_substr(trim($_POST['tuition_fee'] ?? ''), 0, 100);
    $intakes = mb_substr(trim($_POST['intakes'] ?? ''), 0, 200);

    try {
        $stmt = $pdo->prepare("
            INSERT INTO student_shortlist (user_id, course_name, university_name, country_name, duration, tuition_fee, intakes)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                country_name = VALUES(country_name),
                duration = VALUES(duration),
                tuition_fee = VALUES(tuition_fee),
                intakes = VALUES(intakes)
        ");
        $stmt->execute([$userId, $courseName, $universityName, $countryName, $duration, $tuitionFee, $intakes]);

        shortlistResponse(201, ['success' => true, 'message' => 'Course shortlisted.']);
    } catch (PDOException $e) {
        error_log('Shortlist add failed: ' . $e->getMessage());
        shortlistResponse(500, ['success' => false, 'message' => 'Unable to save to shortlist.']);
    }
}

// ─── REMOVE from shortlist ───
if ($action === 'remove') {
    $courseName = trim($_POST['course_name'] ?? '');
    $universityName = trim($_POST['university_name'] ?? '');

    if (empty($courseName)) {
        shortlistResponse(422, ['success' => false, 'message' => 'Course name is required.']);
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM student_shortlist WHERE user_id = ? AND course_name = ? AND university_name = ?");
        $stmt->execute([$userId, $courseName, $universityName]);
        shortlistResponse(200, ['success' => true, 'message' => 'Course removed from shortlist.']);
    } catch (PDOException $e) {
        error_log('Shortlist remove failed: ' . $e->getMessage());
        shortlistResponse(500, ['success' => false, 'message' => 'Unable to remove from shortlist.']);
    }
}

// ─── SYNC: Bulk import from localStorage (one-time migration) ───
if ($action === 'sync') {
    $coursesJson = $_POST['courses'] ?? '';
    if (is_string($coursesJson)) {
        $courses = json_decode($coursesJson, true);
    } elseif (is_array($coursesJson)) {
        $courses = $coursesJson;
    } else {
        $courses = [];
    }

    if (!is_array($courses) || empty($courses)) {
        shortlistResponse(422, ['success' => false, 'message' => 'No courses to sync.']);
    }

    $synced = 0;
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO student_shortlist (user_id, course_name, university_name, country_name, duration, tuition_fee, intakes)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($courses as $c) {
        if (!is_array($c) || empty($c['course_name'])) continue;
        try {
            $stmt->execute([
                $userId,
                mb_substr(trim($c['course_name'] ?? ''), 0, 300),
                mb_substr(trim($c['university_name'] ?? ''), 0, 300),
                mb_substr(trim($c['country_name'] ?? ''), 0, 100),
                mb_substr(trim($c['duration'] ?? ''), 0, 100),
                mb_substr(trim($c['tuition_fee'] ?? ''), 0, 100),
                mb_substr(trim($c['intakes'] ?? ''), 0, 200)
            ]);
            $synced++;
        } catch (PDOException $e) {
            // Skip individual failures
        }
    }

    shortlistResponse(200, [
        'success' => true,
        'message' => "Synced {$synced} courses to your account.",
        'synced' => $synced
    ]);
}

shortlistResponse(422, ['success' => false, 'message' => 'Invalid action.']);
