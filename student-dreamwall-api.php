<?php
session_start();
require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store, max-age=0');

function studentDreamwallResponse(int $status, array $payload): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

function studentDreamwallText($value, string $field, int $maxLength, bool $required = false): string
{
    if (!is_string($value)) {
        studentDreamwallResponse(422, ['success' => false, 'message' => $field . ' must be plain text.']);
    }

    $value = trim($value);
    if ($required && $value === '') {
        studentDreamwallResponse(422, ['success' => false, 'message' => $field . ' is required.']);
    }
    $characterCount = preg_match_all('/./us', $value);
    if ($characterCount === false || $characterCount > $maxLength) {
        studentDreamwallResponse(422, ['success' => false, 'message' => $field . ' must be ' . $maxLength . ' characters or fewer.']);
    }
    return $value;
}

function studentDreamwallStoreProofImage(array $file): ?string
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if (!isset($file['tmp_name'], $file['size']) || !is_string($file['tmp_name']) || !is_int($file['size'])) {
        studentDreamwallResponse(422, ['success' => false, 'message' => 'The selected proof image is invalid.']);
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $message = in_array($file['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)
            ? 'The proof image must be 5 MB or smaller.'
            : 'The proof image upload did not complete. Please try again.';
        studentDreamwallResponse(422, ['success' => false, 'message' => $message]);
    }
    if ($file['size'] < 1 || $file['size'] > 5 * 1024 * 1024 || !is_uploaded_file($file['tmp_name'])) {
        studentDreamwallResponse(422, ['success' => false, 'message' => 'Choose an image no larger than 5 MB.']);
    }

    $imageInfo = @getimagesize($file['tmp_name']);
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    $allowedImages = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];
    if ($imageInfo === false || !isset($allowedImages[$mimeType]) || $imageInfo['mime'] !== $mimeType) {
        studentDreamwallResponse(422, ['success' => false, 'message' => 'Choose a valid JPG, PNG, or WebP image.']);
    }

    $uploadDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'dreamwall';
    if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true) && !is_dir($uploadDirectory)) {
        error_log('Unable to create Dreamwall upload directory: ' . $uploadDirectory);
        studentDreamwallResponse(500, ['success' => false, 'message' => 'Proof image storage is unavailable right now.']);
    }

    $fileName = bin2hex(random_bytes(16)) . '.' . $allowedImages[$mimeType];
    if (!move_uploaded_file($file['tmp_name'], $uploadDirectory . DIRECTORY_SEPARATOR . $fileName)) {
        error_log('Unable to store Dreamwall proof image: ' . $uploadDirectory . DIRECTORY_SEPARATOR . $fileName);
        studentDreamwallResponse(500, ['success' => false, 'message' => 'Unable to save the proof image.']);
    }

    return 'uploads/dreamwall/' . $fileName;
}

function studentDreamwallRemoveProofImage(?string $proofPath): void
{
    $prefix = 'uploads/dreamwall/';
    if (!is_string($proofPath) || strpos($proofPath, $prefix) !== 0) {
        return;
    }

    $fileName = substr($proofPath, strlen($prefix));
    if ($fileName === '' || basename($fileName) !== $fileName) {
        return;
    }

    $filePath = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'dreamwall' . DIRECTORY_SEPARATOR . $fileName;
    if (is_file($filePath) && !unlink($filePath)) {
        error_log('Unable to remove Dreamwall proof image: ' . $filePath);
    }
}

if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'student') {
    studentDreamwallResponse(401, ['success' => false, 'message' => 'Sign in with a student account to use Dreamwall.']);
}

$studentId = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($studentId === false) {
    studentDreamwallResponse(401, ['success' => false, 'message' => 'Your student session is invalid. Sign in again.']);
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method === 'GET') {
    $type = isset($_GET['type']) && is_string($_GET['type']) ? trim($_GET['type']) : 'All';
    $country = isset($_GET['country']) && is_string($_GET['country']) ? trim($_GET['country']) : 'All';
    $allowedTypes = ['All', 'Visa Approved', 'Offer Letter', 'Scholarship', 'IELTS Success'];
    if (!in_array($type, $allowedTypes, true)) {
        studentDreamwallResponse(422, ['success' => false, 'message' => 'Choose a valid milestone category.']);
    }
    if (strlen($country) > 100) {
        studentDreamwallResponse(422, ['success' => false, 'message' => 'Choose a valid country.']);
    }

    $conditions = ["d.review_status = 'approved'", 'd.is_active = 1'];
    $params = [$studentId];
    if ($type !== 'All') {
        $conditions[] = 'd.milestone_type = ?';
        $params[] = $type;
    }
    if ($country !== 'All' && $country !== '') {
        $conditions[] = 'd.country = ?';
        $params[] = $country;
    }

    try {
        $stmt = $pdo->prepare("
            SELECT d.id, d.student_id, d.student_name, d.student_avatar, d.title,
                   d.university_name, d.country, d.country_flag, d.milestone_type,
                   d.story, d.proof_image_url, d.intake, d.cheer_count, d.created_at,
                   EXISTS(
                       SELECT 1 FROM community_dreamwall_cheers c
                       WHERE c.dreamwall_id = d.id AND c.student_id = ?
                   ) AS is_cheered
            FROM community_dreamwall d
            WHERE " . implode(' AND ', $conditions) . "
            ORDER BY d.created_at DESC, d.id DESC
            LIMIT 100
        ");
        $stmt->execute($params);
        studentDreamwallResponse(200, $stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        error_log('Student Dreamwall lookup failed: ' . $e->getMessage());
        studentDreamwallResponse(503, ['success' => false, 'message' => 'Dreamwall is not set up yet. Ask an administrator to run the database setup.']);
    }
}

if ($method !== 'POST') {
    header('Allow: GET, POST');
    studentDreamwallResponse(405, ['success' => false, 'message' => 'Use GET to view milestones or POST to submit an action.']);
}

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
$isMultipartRequest = stripos($contentType, 'multipart/form-data') === 0;
if (
    $isMultipartRequest &&
    empty($_POST) &&
    empty($_FILES) &&
    !empty($_SERVER['CONTENT_LENGTH']) &&
    (int) $_SERVER['CONTENT_LENGTH'] > 0
) {
    studentDreamwallResponse(413, ['success' => false, 'message' => 'The request is too large for the server to receive.']);
}
$request = $isMultipartRequest
    ? $_POST
    : json_decode(file_get_contents('php://input'), true);
if (!is_array($request)) {
    studentDreamwallResponse(400, ['success' => false, 'message' => 'The request body must be valid JSON.']);
}
if (
    !isset($request['csrf_token']) ||
    !is_string($request['csrf_token']) ||
    empty($_SESSION['community_csrf_token']) ||
    !hash_equals($_SESSION['community_csrf_token'], $request['csrf_token'])
) {
    studentDreamwallResponse(403, ['success' => false, 'message' => 'Your session expired. Refresh the page and try again.']);
}

$action = isset($request['action']) && is_string($request['action']) ? $request['action'] : '';
if ($action === 'create') {
    $milestoneType = studentDreamwallText($request['milestone_type'] ?? null, 'Milestone type', 50, true);
    if (!in_array($milestoneType, ['Visa Approved', 'Offer Letter', 'Scholarship', 'IELTS Success'], true)) {
        studentDreamwallResponse(422, ['success' => false, 'message' => 'Choose a valid milestone type.']);
    }

    $title = studentDreamwallText($request['title'] ?? null, 'Headline', 255, true);
    $university = studentDreamwallText($request['university_name'] ?? null, 'University name', 255, true);
    $country = studentDreamwallText($request['country'] ?? null, 'Country', 100, true);
    $countryFlag = studentDreamwallText($request['country_flag'] ?? '', 'Country flag', 20);
    $story = studentDreamwallText($request['story'] ?? null, 'Story', 1200, true);
    $intake = studentDreamwallText($request['intake'] ?? null, 'Intake', 100, true);
    $proofImagePath = null;
    try {
        $studentStmt = $pdo->prepare("
            SELECT TRIM(CONCAT_WS(' ', first_name, last_name))
            FROM users
            WHERE id = ? AND role = 'student'
            LIMIT 1
        ");
        $studentStmt->execute([$studentId]);
        $studentName = trim((string) $studentStmt->fetchColumn());
        if ($studentName === '') {
            studentDreamwallResponse(401, ['success' => false, 'message' => 'Your student profile could not be found. Sign in again.']);
        }

        $proofImagePath = isset($_FILES['proof_image']) && is_array($_FILES['proof_image'])
            ? studentDreamwallStoreProofImage($_FILES['proof_image'])
            : null;
        $insertStmt = $pdo->prepare("
            INSERT INTO community_dreamwall
                (student_id, student_name, title, university_name, country, country_flag,
                 milestone_type, story, proof_image_url, intake, review_status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
        ");
        $insertStmt->execute([
            $studentId, $studentName, $title, $university, $country, $countryFlag ?: null,
            $milestoneType, $story, $proofImagePath, $intake
        ]);
        studentDreamwallResponse(201, [
            'success' => true,
            'id' => (int) $pdo->lastInsertId(),
            'review_status' => 'pending',
            'message' => 'Your milestone was sent to the admin team for review.'
        ]);
    } catch (PDOException $e) {
        if (isset($proofImagePath)) {
            studentDreamwallRemoveProofImage($proofImagePath);
        }
        error_log('Student Dreamwall submission failed: ' . $e->getMessage());
        studentDreamwallResponse(500, ['success' => false, 'message' => 'Your milestone could not be submitted. Please try again.']);
    }
}

if ($action === 'cheer') {
    $milestoneId = filter_var($request['milestone_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($milestoneId === false) {
        studentDreamwallResponse(422, ['success' => false, 'message' => 'Choose a valid milestone.']);
    }

    try {
        $pdo->beginTransaction();
        $milestoneStmt = $pdo->prepare("
            SELECT id FROM community_dreamwall
            WHERE id = ? AND review_status = 'approved' AND is_active = 1
            FOR UPDATE
        ");
        $milestoneStmt->execute([$milestoneId]);
        if (!$milestoneStmt->fetchColumn()) {
            $pdo->rollBack();
            studentDreamwallResponse(404, ['success' => false, 'message' => 'This milestone is no longer available.']);
        }

        $cheerStmt = $pdo->prepare('SELECT id FROM community_dreamwall_cheers WHERE dreamwall_id = ? AND student_id = ? LIMIT 1');
        $cheerStmt->execute([$milestoneId, $studentId]);
        $isCheered = !$cheerStmt->fetchColumn();
        if ($isCheered) {
            $insertCheer = $pdo->prepare('INSERT INTO community_dreamwall_cheers (dreamwall_id, student_id) VALUES (?, ?)');
            $insertCheer->execute([$milestoneId, $studentId]);
            $pdo->prepare('UPDATE community_dreamwall SET cheer_count = cheer_count + 1 WHERE id = ?')->execute([$milestoneId]);
        } else {
            $pdo->prepare('DELETE FROM community_dreamwall_cheers WHERE dreamwall_id = ? AND student_id = ?')->execute([$milestoneId, $studentId]);
            $pdo->prepare('UPDATE community_dreamwall SET cheer_count = GREATEST(cheer_count - 1, 0) WHERE id = ?')->execute([$milestoneId]);
        }

        $countStmt = $pdo->prepare('SELECT cheer_count FROM community_dreamwall WHERE id = ?');
        $countStmt->execute([$milestoneId]);
        $cheerCount = (int) $countStmt->fetchColumn();
        $pdo->commit();
        studentDreamwallResponse(200, ['success' => true, 'is_cheered' => $isCheered, 'cheer_count' => $cheerCount]);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('Student Dreamwall cheer update failed: ' . $e->getMessage());
        studentDreamwallResponse(500, ['success' => false, 'message' => 'Your cheer could not be saved. Please try again.']);
    }
}

studentDreamwallResponse(400, ['success' => false, 'message' => 'Choose a valid Dreamwall action.']);
