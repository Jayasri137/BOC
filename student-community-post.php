<?php
$communityPostResponseSent = false;
$communityPostBufferLevel = ob_get_level();
ob_start();
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store, max-age=0');

function communityPostResponse(int $status, array $payload): void
{
    global $communityPostResponseSent, $communityPostBufferLevel;

    $communityPostResponseSent = true;
    while (ob_get_level() > $communityPostBufferLevel) {
        ob_end_clean();
    }
    http_response_code($status);
    echo json_encode($payload, JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

set_error_handler(function (int $severity, string $message, string $file, int $line): bool {
    if (!(error_reporting() & $severity)) {
        return false;
    }

    throw new ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(function (Throwable $exception): void {
    error_log('Unhandled student community post error: ' . $exception);
    communityPostResponse(500, ['success' => false, 'message' => 'The server could not process this post. Please try again or contact the site administrator.']);
});

register_shutdown_function(function () use (&$communityPostResponseSent, $communityPostBufferLevel): void {
    if ($communityPostResponseSent) {
        return;
    }

    $lastError = error_get_last();
    if ($lastError !== null && in_array($lastError['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR], true)) {
        error_log('Fatal student community post error: ' . $lastError['message'] . ' in ' . $lastError['file'] . ':' . $lastError['line']);
    }

    while (ob_get_level() > $communityPostBufferLevel) {
        ob_end_clean();
    }

    if (!headers_sent()) {
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: no-store, max-age=0');
        http_response_code(500);
    }
    echo json_encode([
        'success' => false,
        'message' => 'The server stopped while processing this post. Please check the hosting error log or contact the site administrator.',
    ], JSON_INVALID_UTF8_SUBSTITUTE);
});

session_start();
require_once __DIR__ . '/includes/config.php';
function validateCommunityContent($submittedContent): string
{
    if (!is_string($submittedContent)) {
        communityPostResponse(422, ['success' => false, 'message' => 'The description must be plain text.']);
    }

    $content = trim($submittedContent);
    if (strlen($content) > 8000) {
        communityPostResponse(422, ['success' => false, 'message' => 'The description must be 2,000 characters or fewer.']);
    }

    $characterCount = preg_match_all('/./us', $content);
    if ($characterCount === false || $characterCount > 2000) {
        communityPostResponse(422, ['success' => false, 'message' => 'The description must be 2,000 characters or fewer.']);
    }

    return $content;
}

function studentCommunityUploadDirectory(): string
{
    return __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'community' . DIRECTORY_SEPARATOR . 'students';
}

function storeCommunityMedia(array $file): array
{
    if (
        !isset($file['error'], $file['tmp_name'], $file['size']) ||
        !is_int($file['error']) ||
        !is_string($file['tmp_name']) ||
        !is_int($file['size'])
    ) {
        communityPostResponse(422, ['success' => false, 'message' => 'The selected media upload is invalid.']);
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $message = $file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE
            ? 'The selected file is too large.'
            : 'The media upload did not complete. Please try again.';
        communityPostResponse(422, ['success' => false, 'message' => $message]);
    }
    if (!is_uploaded_file($file['tmp_name'])) {
        communityPostResponse(422, ['success' => false, 'message' => 'The uploaded file could not be verified.']);
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    $allowedMedia = [
        'image/jpeg' => ['extension' => 'jpg', 'max_size' => 10 * 1024 * 1024],
        'image/png' => ['extension' => 'png', 'max_size' => 10 * 1024 * 1024],
        'image/gif' => ['extension' => 'gif', 'max_size' => 10 * 1024 * 1024],
        'image/webp' => ['extension' => 'webp', 'max_size' => 10 * 1024 * 1024],
        'video/mp4' => ['extension' => 'mp4', 'max_size' => 50 * 1024 * 1024],
        'video/webm' => ['extension' => 'webm', 'max_size' => 50 * 1024 * 1024],
        'video/quicktime' => ['extension' => 'mov', 'max_size' => 50 * 1024 * 1024],
    ];

    if (!isset($allowedMedia[$mimeType])) {
        communityPostResponse(422, ['success' => false, 'message' => 'Choose a supported image (JPG, PNG, GIF, WebP) or video (MP4, WebM, MOV).']);
    }
    if ($file['size'] > $allowedMedia[$mimeType]['max_size']) {
        $limit = strpos($mimeType, 'video/') === 0 ? '50 MB' : '10 MB';
        communityPostResponse(422, ['success' => false, 'message' => 'The selected file exceeds the ' . $limit . ' limit.']);
    }

    $uploadDirectory = studentCommunityUploadDirectory();
    if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true) && !is_dir($uploadDirectory)) {
        error_log('Unable to create student community upload directory: ' . $uploadDirectory);
        communityPostResponse(500, ['success' => false, 'message' => 'Media storage is unavailable right now.']);
    }

    $fileName = bin2hex(random_bytes(16)) . '.' . $allowedMedia[$mimeType]['extension'];
    $storedFilePath = $uploadDirectory . DIRECTORY_SEPARATOR . $fileName;
    if (!move_uploaded_file($file['tmp_name'], $storedFilePath)) {
        error_log('Unable to store student community upload: ' . $storedFilePath);
        communityPostResponse(500, ['success' => false, 'message' => 'Unable to save the selected media.']);
    }

    return [
        'path' => 'assets/images/community/students/' . $fileName,
        'stored_path' => $storedFilePath,
    ];
}

function removeStudentCommunityMedia(string $mediaPath): void
{
    $prefix = 'assets/images/community/students/';
    if (strpos($mediaPath, $prefix) !== 0) {
        return;
    }

    $fileName = substr($mediaPath, strlen($prefix));
    if ($fileName === '' || basename($fileName) !== $fileName) {
        return;
    }

    $filePath = studentCommunityUploadDirectory() . DIRECTORY_SEPARATOR . $fileName;
    if (is_file($filePath) && !unlink($filePath)) {
        error_log('Unable to remove student community media file: ' . $filePath);
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    communityPostResponse(405, ['success' => false, 'message' => 'Only post submissions are accepted.']);
}

if (
    empty($_POST) &&
    empty($_FILES) &&
    !empty($_SERVER['CONTENT_LENGTH'])
) {
    communityPostResponse(413, ['success' => false, 'message' => 'The upload is too large for the server to receive.']);
}

if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'student') {
    communityPostResponse(401, ['success' => false, 'message' => 'Please sign in with a student account to post.']);
}

if (
    !isset($_POST['csrf_token']) ||
    !is_string($_POST['csrf_token']) ||
    empty($_SESSION['community_csrf_token']) ||
    !hash_equals($_SESSION['community_csrf_token'], $_POST['csrf_token'])
) {
    communityPostResponse(403, ['success' => false, 'message' => 'Your session expired. Refresh the page and try again.']);
}

$userId = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
if (!$userId || $userId < 1) {
    communityPostResponse(401, ['success' => false, 'message' => 'Please sign in again to post.']);
}

try {
    $userStmt = $pdo->prepare("SELECT first_name, last_name, role FROM users WHERE id = ?");
    $userStmt->execute([$userId]);
    $student = $userStmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Community post account lookup failed: ' . $e->getMessage());
    communityPostResponse(500, ['success' => false, 'message' => 'Unable to verify your account right now.']);
}

if (!$student || $student['role'] !== 'student') {
    communityPostResponse(403, ['success' => false, 'message' => 'Only student accounts can publish community posts.']);
}

$action = $_POST['action'] ?? 'create';
if (!is_string($action) || !in_array($action, ['create', 'edit', 'delete'], true)) {
    communityPostResponse(422, ['success' => false, 'message' => 'Choose a valid post action.']);
}

if ($action !== 'create') {
    $postId = filter_var($_POST['post_id'] ?? null, FILTER_VALIDATE_INT);
    if (!$postId || $postId < 1) {
        communityPostResponse(422, ['success' => false, 'message' => 'The selected post is invalid.']);
    }

    try {
        $ownedPostStmt = $pdo->prepare('SELECT id, media_path FROM community_posts WHERE id = ? AND user_id = ? AND student_created = 1 LIMIT 1');
        $ownedPostStmt->execute([$postId, $userId]);
        $ownedPost = $ownedPostStmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Community post ownership lookup failed: ' . $e->getMessage());
        communityPostResponse(500, ['success' => false, 'message' => 'Unable to load your post right now.']);
    }

    if (!$ownedPost) {
        communityPostResponse(404, ['success' => false, 'message' => 'This post is no longer available or does not belong to you.']);
    }

    if ($action === 'delete') {
        try {
            $deleteStmt = $pdo->prepare('DELETE FROM community_posts WHERE id = ? AND user_id = ? AND student_created = 1');
            $deleteStmt->execute([$postId, $userId]);
        } catch (PDOException $e) {
            error_log('Community post deletion failed: ' . $e->getMessage());
            communityPostResponse(500, ['success' => false, 'message' => 'Unable to delete your post right now.']);
        }

        if ($deleteStmt->rowCount() !== 1) {
            communityPostResponse(404, ['success' => false, 'message' => 'This post is no longer available.']);
        }
        removeStudentCommunityMedia((string) ($ownedPost['media_path'] ?? ''));
        communityPostResponse(200, ['success' => true, 'message' => 'Your post was deleted.']);
    }
}

$content = validateCommunityContent($_POST['content'] ?? '');
$newMediaPath = '';
$storedFilePath = '';
$hasUpload = isset($_FILES['media']);
if ($hasUpload && (!is_array($_FILES['media']) || !isset($_FILES['media']['error']) || !is_int($_FILES['media']['error']))) {
    communityPostResponse(422, ['success' => false, 'message' => 'The selected media upload is invalid.']);
}
if ($hasUpload && $_FILES['media']['error'] === UPLOAD_ERR_NO_FILE) {
    $hasUpload = false;
}
if ($hasUpload) {
    $uploadedMedia = storeCommunityMedia($_FILES['media']);
    $newMediaPath = $uploadedMedia['path'];
    $storedFilePath = $uploadedMedia['stored_path'];
}

if ($action === 'edit') {
    $removeMedia = isset($_POST['remove_media']) && $_POST['remove_media'] === '1';
    $mediaPath = $newMediaPath !== ''
        ? $newMediaPath
        : ($removeMedia ? '' : (string) ($ownedPost['media_path'] ?? ''));
    if ($content === '' && $mediaPath === '') {
        if ($storedFilePath !== '' && is_file($storedFilePath)) {
            unlink($storedFilePath);
        }
        communityPostResponse(422, ['success' => false, 'message' => 'A post must have a description or media.']);
    }

    try {
        $updateStmt = $pdo->prepare('UPDATE community_posts SET content = ?, media_path = ? WHERE id = ? AND user_id = ? AND student_created = 1');
        $updateStmt->execute([$content, $mediaPath, $postId, $userId]);
    } catch (PDOException $e) {
        if ($storedFilePath !== '' && is_file($storedFilePath)) {
            unlink($storedFilePath);
        }
        error_log('Community post update failed: ' . $e->getMessage());
        communityPostResponse(500, ['success' => false, 'message' => 'Unable to update your post right now.']);
    }

    if ($newMediaPath !== '' || $removeMedia) {
        removeStudentCommunityMedia((string) ($ownedPost['media_path'] ?? ''));
    }
    communityPostResponse(200, ['success' => true, 'message' => 'Your post was updated.']);
}

if ($content === '' && $newMediaPath === '') {
    if ($storedFilePath !== '' && is_file($storedFilePath)) {
        unlink($storedFilePath);
    }
    communityPostResponse(422, ['success' => false, 'message' => 'Add a description or choose an image or video before sharing.']);
}

$authorName = trim($student['first_name'] . ' ' . $student['last_name']);
try {
    $postStmt = $pdo->prepare("
        INSERT INTO community_posts
            (user_id, content, destination_tag, media_path, avatar_url, author_name, destination, is_active, student_created)
        VALUES
            (?, ?, '', ?, '', ?, 'Student Community', 1, 1)
    ");
    $postStmt->execute([$userId, $content, $newMediaPath, $authorName]);
} catch (PDOException $e) {
    if ($storedFilePath !== '' && is_file($storedFilePath)) {
        unlink($storedFilePath);
    }
    error_log('Community post save failed: ' . $e->getMessage());
    if ($e->getCode() === '42S22' || $e->getCode() === '42S02') {
        communityPostResponse(500, ['success' => false, 'message' => 'The Community database needs its latest update. Please refresh the page and try again; if this continues, contact the site administrator.']);
    }
    communityPostResponse(500, ['success' => false, 'message' => 'Unable to share your post right now. Please try again.']);
}

communityPostResponse(201, ['success' => true, 'message' => 'Your post was shared with the student community.']);
