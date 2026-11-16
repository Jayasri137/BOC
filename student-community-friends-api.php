<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store, max-age=0');

require_once __DIR__ . '/includes/config.php';

function studentFriendsResponse(int $status, array $payload): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

function ensureStudentDirectMessageColumns(PDO $pdo): void
{
    if ((int) ($_SESSION['student_direct_message_schema_version'] ?? 0) >= 5) {
        return;
    }

    $columns = [
        'message_type' => "VARCHAR(32) NOT NULL DEFAULT 'message' AFTER message",
        'attachment_path' => 'VARCHAR(100) DEFAULT NULL AFTER message_type',
        'attachment_name' => 'VARCHAR(255) DEFAULT NULL AFTER attachment_path',
        'attachment_mime' => 'VARCHAR(127) DEFAULT NULL AFTER attachment_name',
        'attachment_size' => 'INT UNSIGNED DEFAULT NULL AFTER attachment_mime',
        'reply_to_id' => 'BIGINT UNSIGNED DEFAULT NULL AFTER attachment_size',
    ];
    foreach ($columns as $columnName => $definition) {
        $columnStmt = $pdo->query("SHOW COLUMNS FROM student_direct_messages LIKE " . $pdo->quote($columnName));
        if (!$columnStmt->fetch(PDO::FETCH_ASSOC)) {
            $pdo->exec("ALTER TABLE student_direct_messages ADD COLUMN {$columnName} {$definition}");
        }
    }
    $readColumnStmt = $pdo->query("SHOW COLUMNS FROM student_direct_messages LIKE 'recipient_read_at'");
    if (!$readColumnStmt->fetch(PDO::FETCH_ASSOC)) {
        $pdo->exec('ALTER TABLE student_direct_messages ADD COLUMN recipient_read_at DATETIME DEFAULT NULL AFTER reply_to_id');
        $pdo->exec('UPDATE student_direct_messages SET recipient_read_at = created_at WHERE recipient_read_at IS NULL');
    }
    $deliveredColumnStmt = $pdo->query("SHOW COLUMNS FROM student_direct_messages LIKE 'recipient_delivered_at'");
    if (!$deliveredColumnStmt->fetch(PDO::FETCH_ASSOC)) {
        $pdo->exec('ALTER TABLE student_direct_messages ADD COLUMN recipient_delivered_at DATETIME DEFAULT NULL AFTER recipient_read_at');
        $pdo->exec('UPDATE student_direct_messages SET recipient_delivered_at = recipient_read_at WHERE recipient_read_at IS NOT NULL');
    }
    $editedColumnStmt = $pdo->query("SHOW COLUMNS FROM student_direct_messages LIKE 'edited_at'");
    if (!$editedColumnStmt->fetch(PDO::FETCH_ASSOC)) {
        $pdo->exec('ALTER TABLE student_direct_messages ADD COLUMN edited_at DATETIME DEFAULT NULL AFTER recipient_delivered_at');
    }
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS student_chat_preferences (
            user_id INT NOT NULL,
            chat_type ENUM('direct', 'group') NOT NULL,
            chat_id INT NOT NULL,
            notification_mode ENUM('all', 'muted', 'mentions') NOT NULL DEFAULT 'all',
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (user_id, chat_type, chat_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    $_SESSION['student_direct_message_schema_version'] = 5;
}

function studentDirectAttachmentDetails(array $row): ?array
{
    if (empty($row['attachment_path'])) {
        return null;
    }

    return [
        'url' => 'student-direct-message-file.php?id=' . (int) $row['id'],
        'name' => (string) $row['attachment_name'],
        'mime' => (string) $row['attachment_mime'],
        'size' => (int) $row['attachment_size'],
    ];
}

function studentDirectReplyDetails(array $row): ?array
{
    if (empty($row['reply_to_id'])) {
        return null;
    }

    return [
        'id' => (int) $row['reply_to_id'],
        'sender_id' => (int) $row['reply_sender_id'],
        'sender_name' => (string) $row['reply_sender_name'],
        'message' => (string) $row['reply_message'],
        'message_type' => (string) $row['reply_message_type'],
        'attachment_name' => $row['reply_attachment_name'] === null
            ? null
            : (string) $row['reply_attachment_name'],
        'attachment_mime' => $row['reply_attachment_mime'] === null
            ? null
            : (string) $row['reply_attachment_mime'],
    ];
}

if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'student') {
    studentFriendsResponse(401, ['success' => false, 'message' => 'Please sign in with a student account.']);
}

$userId = (int) $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'] ?? '';

if ($method === 'GET') {
    $action = isset($_GET['action']) && is_string($_GET['action']) ? $_GET['action'] : '';
} elseif ($method === 'POST') {
    $request = $_POST;
    if (stripos((string) ($_SERVER['CONTENT_TYPE'] ?? ''), 'application/json') !== false) {
        $decoded = json_decode((string) file_get_contents('php://input'), true);
        if (is_array($decoded)) {
            $request = array_merge($request, $decoded);
        }
    }
    if (
        !isset($request['csrf_token']) ||
        !is_string($request['csrf_token']) ||
        empty($_SESSION['community_csrf_token']) ||
        !hash_equals($_SESSION['community_csrf_token'], $request['csrf_token'])
    ) {
        studentFriendsResponse(403, ['success' => false, 'message' => 'Session expired. Refresh the page and try again.']);
    }
    $action = isset($request['action']) && is_string($request['action']) ? $request['action'] : '';
} else {
    header('Allow: GET, POST');
    studentFriendsResponse(405, ['success' => false, 'message' => 'This request method is not supported.']);
}

function studentFriendsPublicUser(array $row): array
{
    $name = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));
    return [
        'id' => (int) $row['id'],
        'name' => $name !== '' ? $name : 'Student',
        'country' => (string) ($row['country'] ?? ''),
        'course' => (string) ($row['course'] ?? ''),
        'intake' => (string) ($row['intake'] ?? ''),
        'connection_status' => (string) ($row['connection_status'] ?? ''),
        'requester_id' => isset($row['requester_id']) ? (int) $row['requester_id'] : null,
    ];
}

try {
    if (in_array($action, ['inbox', 'conversation', 'read_status', 'send_message', 'delete_message', 'edit_message', 'notification_preference'], true)) {
        ensureStudentDirectMessageColumns($pdo);
    }

    if ($method === 'GET' && $action === 'notification_preference') {
        $targetId = filter_var($_GET['target_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($targetId === false || $targetId === $userId) {
            studentFriendsResponse(422, ['success' => false, 'message' => 'Choose a valid connection.']);
        }
        $connectionStmt = $pdo->prepare("
            SELECT 1 FROM student_community_connections
            WHERE user_one = ? AND user_two = ? AND status = 'accepted'
            LIMIT 1
        ");
        $connectionStmt->execute([min($userId, $targetId), max($userId, $targetId)]);
        if (!$connectionStmt->fetchColumn()) {
            studentFriendsResponse(403, ['success' => false, 'message' => 'This personal conversation is no longer available.']);
        }
        $preferenceStmt = $pdo->prepare("
            SELECT notification_mode FROM student_chat_preferences
            WHERE user_id = ? AND chat_type = 'direct' AND chat_id = ?
            LIMIT 1
        ");
        $preferenceStmt->execute([$userId, $targetId]);
        studentFriendsResponse(200, ['success' => true, 'notification_mode' => $preferenceStmt->fetchColumn() ?: 'all']);
    }

    if ($method === 'GET' && $action === 'read_status') {
        $targetId = filter_var($_GET['target_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($targetId === false || $targetId === $userId) {
            studentFriendsResponse(422, ['success' => false, 'message' => 'Choose a valid connection.']);
        }
        $connectionStmt = $pdo->prepare("
            SELECT 1 FROM student_community_connections
            WHERE user_one = ? AND user_two = ? AND status = 'accepted'
            LIMIT 1
        ");
        $connectionStmt->execute([min($userId, $targetId), max($userId, $targetId)]);
        if (!$connectionStmt->fetchColumn()) {
            studentFriendsResponse(403, ['success' => false, 'message' => 'This personal conversation is no longer available.']);
        }
        $statusStmt = $pdo->prepare("
            SELECT id, recipient_delivered_at, recipient_read_at FROM student_direct_messages
            WHERE sender_id = ? AND recipient_id = ? AND message_type = 'message'
            ORDER BY id DESC LIMIT 100
        ");
        $statusStmt->execute([$userId, $targetId]);
        studentFriendsResponse(200, ['success' => true, 'read_status' => $statusStmt->fetchAll(PDO::FETCH_ASSOC)]);
    }

    if ($method === 'GET' && $action === 'inbox') {
        $deliverStmt = $pdo->prepare("
            UPDATE student_direct_messages
            SET recipient_delivered_at = CURRENT_TIMESTAMP
            WHERE recipient_id = ? AND recipient_delivered_at IS NULL
        ");
        $deliverStmt->execute([$userId]);
        $threadsStmt = $pdo->prepare("
            SELECT
                CASE WHEN c.user_one = ? THEN c.user_two ELSE c.user_one END AS student_id,
                COALESCE(NULLIF(TRIM(CONCAT_WS(' ', u.first_name, u.last_name)), ''), 'Student') AS student_name,
                (SELECT CASE
                    WHEN m.message_type = 'deleted' THEN 'This message was deleted'
                    WHEN m.message <> '' THEN m.message
                    WHEN m.attachment_mime LIKE 'image/%' THEN 'Photo'
                    WHEN m.attachment_mime LIKE 'video/%' THEN 'Video'
                    WHEN m.attachment_path IS NOT NULL THEN 'Attachment'
                    ELSE ''
                 END FROM student_direct_messages m
                 WHERE m.sender_id = ? AND m.recipient_id = u.id
                    OR m.sender_id = u.id AND m.recipient_id = ?
                 ORDER BY m.id DESC LIMIT 1) AS last_message,
                (SELECT m.created_at FROM student_direct_messages m
                 WHERE m.sender_id = ? AND m.recipient_id = u.id
                    OR m.sender_id = u.id AND m.recipient_id = ?
                 ORDER BY m.id DESC LIMIT 1) AS last_message_at,
                (SELECT m.id FROM student_direct_messages m
                 WHERE m.sender_id = ? AND m.recipient_id = u.id
                    OR m.sender_id = u.id AND m.recipient_id = ?
                 ORDER BY m.id DESC LIMIT 1) AS last_message_id,
                (SELECT COUNT(*) FROM student_direct_messages unread
                 WHERE unread.sender_id = u.id AND unread.recipient_id = ?
                   AND unread.recipient_read_at IS NULL
                   AND unread.message_type <> 'deleted') AS unread_count
            FROM student_community_connections c
            INNER JOIN users u ON u.id = CASE WHEN c.user_one = ? THEN c.user_two ELSE c.user_one END
            WHERE (c.user_one = ? OR c.user_two = ?)
              AND c.status = 'accepted'
            ORDER BY COALESCE(last_message_at, c.updated_at) DESC
            LIMIT 100
        ");
        $threadsStmt->execute(array_fill(0, 11, $userId));
        studentFriendsResponse(200, ['success' => true, 'threads' => $threadsStmt->fetchAll(PDO::FETCH_ASSOC)]);
    }

    if ($method === 'GET' && $action === 'conversation') {
        $targetId = filter_var($_GET['target_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($targetId === false || $targetId === $userId) {
            studentFriendsResponse(422, ['success' => false, 'message' => 'Choose a valid connection.']);
        }
        $userOne = min($userId, $targetId);
        $userTwo = max($userId, $targetId);
        $connectionStmt = $pdo->prepare("
            SELECT 1 FROM student_community_connections
            WHERE user_one = ? AND user_two = ? AND status = 'accepted'
            LIMIT 1
        ");
        $connectionStmt->execute([$userOne, $userTwo]);
        if (!$connectionStmt->fetchColumn()) {
            studentFriendsResponse(403, ['success' => false, 'message' => 'Connect with this student before messaging.']);
        }
        $afterId = filter_var($_GET['after_id'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
        if ($afterId === false) {
            studentFriendsResponse(422, ['success' => false, 'message' => 'Invalid message cursor.']);
        }
        $markReadStmt = $pdo->prepare("
            UPDATE student_direct_messages
            SET recipient_delivered_at = COALESCE(recipient_delivered_at, CURRENT_TIMESTAMP),
                recipient_read_at = CURRENT_TIMESTAMP
            WHERE sender_id = ? AND recipient_id = ? AND recipient_read_at IS NULL
        ");
        $markReadStmt->execute([$targetId, $userId]);
        if ($afterId === 0) {
            $messagesStmt = $pdo->prepare("
                SELECT recent.id, recent.sender_id, recent.recipient_id, recent.message, recent.message_type,
                       recent.attachment_path, recent.attachment_name, recent.attachment_mime,
                       recent.attachment_size, recent.reply_to_id, recent.recipient_delivered_at, recent.recipient_read_at, recent.edited_at, recent.created_at,
                       COALESCE(NULLIF(TRIM(CONCAT_WS(' ', u.first_name, u.last_name)), ''), 'Student') AS sender_name,
                       replied.sender_id AS reply_sender_id,
                       COALESCE(NULLIF(TRIM(CONCAT_WS(' ', reply_user.first_name, reply_user.last_name)), ''), 'Student') AS reply_sender_name,
                       replied.message AS reply_message, replied.message_type AS reply_message_type,
                       replied.attachment_name AS reply_attachment_name,
                       replied.attachment_mime AS reply_attachment_mime
                FROM (
                    SELECT id, sender_id, recipient_id, message, message_type, attachment_path,
                           attachment_name, attachment_mime, attachment_size, reply_to_id, recipient_delivered_at, recipient_read_at, edited_at, created_at
                    FROM student_direct_messages
                    WHERE (sender_id = ? AND recipient_id = ?) OR (sender_id = ? AND recipient_id = ?)
                    ORDER BY id DESC
                    LIMIT 100
                ) recent
                INNER JOIN users u ON u.id = recent.sender_id
                LEFT JOIN student_direct_messages replied
                    ON replied.id = recent.reply_to_id
                   AND ((replied.sender_id = recent.sender_id AND replied.recipient_id = recent.recipient_id)
                     OR (replied.sender_id = recent.recipient_id AND replied.recipient_id = recent.sender_id))
                LEFT JOIN users reply_user ON reply_user.id = replied.sender_id
                ORDER BY recent.id ASC
            ");
            $messagesStmt->execute([$userId, $targetId, $targetId, $userId]);
        } else {
            $messagesStmt = $pdo->prepare("
                SELECT m.id, m.sender_id, m.recipient_id, m.message, m.message_type,
                       m.attachment_path, m.attachment_name, m.attachment_mime,
                       m.attachment_size, m.reply_to_id, m.recipient_delivered_at, m.recipient_read_at, m.edited_at, m.created_at,
                       COALESCE(NULLIF(TRIM(CONCAT_WS(' ', u.first_name, u.last_name)), ''), 'Student') AS sender_name,
                       replied.sender_id AS reply_sender_id,
                       COALESCE(NULLIF(TRIM(CONCAT_WS(' ', reply_user.first_name, reply_user.last_name)), ''), 'Student') AS reply_sender_name,
                       replied.message AS reply_message, replied.message_type AS reply_message_type,
                       replied.attachment_name AS reply_attachment_name,
                       replied.attachment_mime AS reply_attachment_mime
                FROM student_direct_messages m
                INNER JOIN users u ON u.id = m.sender_id
                LEFT JOIN student_direct_messages replied
                    ON replied.id = m.reply_to_id
                   AND ((replied.sender_id = m.sender_id AND replied.recipient_id = m.recipient_id)
                     OR (replied.sender_id = m.recipient_id AND replied.recipient_id = m.sender_id))
                LEFT JOIN users reply_user ON reply_user.id = replied.sender_id
                WHERE ((m.sender_id = ? AND m.recipient_id = ?) OR (m.sender_id = ? AND m.recipient_id = ?))
                  AND m.id > ?
                ORDER BY m.id ASC
                LIMIT 100
            ");
            $messagesStmt->execute([$userId, $targetId, $targetId, $userId, $afterId]);
            $deletedMessagesStmt = $pdo->prepare("
                SELECT m.id, m.sender_id, m.recipient_id, m.message, m.message_type,
                       m.attachment_path, m.attachment_name, m.attachment_mime,
                       m.attachment_size, m.reply_to_id, m.recipient_delivered_at, m.recipient_read_at, m.edited_at, m.created_at,
                       COALESCE(NULLIF(TRIM(CONCAT_WS(' ', u.first_name, u.last_name)), ''), 'Student') AS sender_name,
                       replied.sender_id AS reply_sender_id,
                       COALESCE(NULLIF(TRIM(CONCAT_WS(' ', reply_user.first_name, reply_user.last_name)), ''), 'Student') AS reply_sender_name,
                       replied.message AS reply_message, replied.message_type AS reply_message_type,
                       replied.attachment_name AS reply_attachment_name,
                       replied.attachment_mime AS reply_attachment_mime
                FROM student_direct_messages m
                INNER JOIN users u ON u.id = m.sender_id
                LEFT JOIN student_direct_messages replied
                    ON replied.id = m.reply_to_id
                   AND ((replied.sender_id = m.sender_id AND replied.recipient_id = m.recipient_id)
                     OR (replied.sender_id = m.recipient_id AND replied.recipient_id = m.sender_id))
                LEFT JOIN users reply_user ON reply_user.id = replied.sender_id
                WHERE ((m.sender_id = ? AND m.recipient_id = ?) OR (m.sender_id = ? AND m.recipient_id = ?))
                  AND m.id <= ? AND m.message_type = 'deleted'
                ORDER BY m.id DESC
                LIMIT 100
            ");
            $deletedMessagesStmt->execute([$userId, $targetId, $targetId, $userId, $afterId]);
        }
        $messages = $messagesStmt->fetchAll(PDO::FETCH_ASSOC);
        if ($afterId > 0) {
            $messages = array_merge($messages, $deletedMessagesStmt->fetchAll(PDO::FETCH_ASSOC));
            usort($messages, static function (array $a, array $b): int {
                return (int) $a['id'] <=> (int) $b['id'];
            });
        }
        foreach ($messages as &$directMessage) {
            $directMessage['attachment'] = studentDirectAttachmentDetails($directMessage);
            $directMessage['reply_to'] = studentDirectReplyDetails($directMessage);
            unset(
                $directMessage['attachment_path'],
                $directMessage['attachment_name'],
                $directMessage['attachment_mime'],
                $directMessage['attachment_size'],
                $directMessage['reply_to_id'],
                $directMessage['reply_sender_id'],
                $directMessage['reply_sender_name'],
                $directMessage['reply_message'],
                $directMessage['reply_message_type'],
                $directMessage['reply_attachment_name'],
                $directMessage['reply_attachment_mime']
            );
        }
        unset($directMessage);
        $studentStmt = $pdo->prepare("
            SELECT COALESCE(NULLIF(TRIM(CONCAT_WS(' ', first_name, last_name)), ''), 'Student')
            FROM users WHERE id = ? LIMIT 1
        ");
        $studentStmt->execute([$targetId]);
        studentFriendsResponse(200, [
            'success' => true,
            'student' => ['id' => $targetId, 'name' => (string) ($studentStmt->fetchColumn() ?: 'Student')],
            'messages' => $messages,
        ]);
    }

    if ($method === 'GET' && $action === 'discover') {
        $settingStmt = $pdo->prepare('SELECT discoverable FROM student_community_settings WHERE user_id = ? LIMIT 1');
        $settingStmt->execute([$userId]);
        $savedVisibility = $settingStmt->fetchColumn();
        $discoverable = $savedVisibility === false || (int) $savedVisibility === 1;

        $incomingStmt = $pdo->prepare("
            SELECT u.id, u.first_name, u.last_name,
                   COALESCE(NULLIF(u.target_country, ''), NULLIF(u.interest_destinations, '')) AS country,
                   COALESCE(NULLIF(u.preferred_degree, ''), NULLIF(u.interest_subjects, '')) AS course,
                   COALESCE(NULLIF(u.target_intake, ''), NULLIF(u.interest_intake_year, '')) AS intake,
                   c.requester_id
            FROM student_community_connections c
            INNER JOIN users u ON u.id = c.requester_id
            WHERE c.recipient_id = ? AND c.status = 'pending'
              AND COALESCE(u.is_active, 1) = 1 AND u.role = 'student'
            ORDER BY c.updated_at DESC
            LIMIT 50
        ");
        $incomingStmt->execute([$userId]);
        $incoming = array_map('studentFriendsPublicUser', $incomingStmt->fetchAll(PDO::FETCH_ASSOC));

        $search = isset($_GET['q']) && is_string($_GET['q']) ? substr(trim($_GET['q']), 0, 100) : '';
        $countryFilter = isset($_GET['country']) && is_string($_GET['country']) ? substr(trim($_GET['country']), 0, 100) : '';
        $intakeFilter = isset($_GET['intake']) && is_string($_GET['intake']) ? substr(trim($_GET['intake']), 0, 100) : '';
        $courseFilter = isset($_GET['course']) && is_string($_GET['course']) ? substr(trim($_GET['course']), 0, 100) : '';
        $where = [
            'u.id <> ?',
            'u.role = ?',
            'COALESCE(u.is_active, 1) = 1',
            'COALESCE(s.discoverable, 1) = 1',
            '(c.status IS NULL OR c.status <> ?)',
        ];
        $params = [$userId, 'student', 'blocked'];
        if ($search !== '') {
            $where[] = "(CONCAT_WS(' ', u.first_name, u.last_name, u.target_country, u.interest_destinations, u.preferred_degree, u.interest_subjects, u.target_intake, u.interest_intake_year) LIKE ?)";
            $params[] = '%' . $search . '%';
        }
        if ($countryFilter !== '') {
            $where[] = "(COALESCE(NULLIF(u.target_country, ''), u.interest_destinations) LIKE ?)";
            $params[] = '%' . $countryFilter . '%';
        }
        if ($intakeFilter !== '') {
            $where[] = "(COALESCE(NULLIF(u.target_intake, ''), u.interest_intake_year) LIKE ?)";
            $params[] = '%' . $intakeFilter . '%';
        }
        if ($courseFilter !== '') {
            $where[] = "(COALESCE(NULLIF(u.preferred_degree, ''), u.interest_subjects) LIKE ?)";
            $params[] = '%' . $courseFilter . '%';
        }

        $studentStmt = $pdo->prepare("
                SELECT u.id, u.first_name, u.last_name,
                       COALESCE(NULLIF(u.target_country, ''), NULLIF(u.interest_destinations, '')) AS country,
                       COALESCE(NULLIF(u.preferred_degree, ''), NULLIF(u.interest_subjects, '')) AS course,
                       COALESCE(NULLIF(u.target_intake, ''), NULLIF(u.interest_intake_year, '')) AS intake,
                       c.status AS connection_status,
                       c.requester_id
                FROM users u
                LEFT JOIN student_community_settings s ON s.user_id = u.id
                LEFT JOIN student_community_connections c
                    ON c.user_one = LEAST(?, u.id) AND c.user_two = GREATEST(?, u.id)
                WHERE " . implode(' AND ', $where) . "
                ORDER BY
                    (COALESCE(NULLIF(u.target_country, ''), u.interest_destinations) =
                     COALESCE(NULLIF((SELECT target_country FROM users WHERE id = ?), ''),
                              (SELECT interest_destinations FROM users WHERE id = ?))) DESC,
                    u.created_at DESC
                LIMIT 60
            ");
        $studentStmt->execute(array_merge([$userId, $userId], $params, [$userId, $userId]));
        $students = array_map('studentFriendsPublicUser', $studentStmt->fetchAll(PDO::FETCH_ASSOC));

        studentFriendsResponse(200, [
            'success' => true,
            'discoverable' => $discoverable,
            'students' => $students,
            'incoming_requests' => $incoming,
        ]);
    }

    if ($method !== 'POST') {
        studentFriendsResponse(400, ['success' => false, 'message' => 'Invalid student connection request.']);
    }

    if ($action === 'visibility') {
        $discoverable = filter_var($request['discoverable'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($discoverable === null) {
            studentFriendsResponse(422, ['success' => false, 'message' => 'Choose whether your profile should be discoverable.']);
        }
        $stmt = $pdo->prepare("
            INSERT INTO student_community_settings (user_id, discoverable)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE discoverable = VALUES(discoverable)
        ");
        $stmt->execute([$userId, $discoverable ? 1 : 0]);
        studentFriendsResponse(200, ['success' => true, 'discoverable' => $discoverable]);
    }

    if ($action === 'disconnect') {
        $targetId = filter_var($request['target_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($targetId === false || $targetId === $userId) {
            studentFriendsResponse(422, ['success' => false, 'message' => 'Choose a valid connection.']);
        }
        $disconnectStmt = $pdo->prepare("
            DELETE FROM student_community_connections
            WHERE user_one = ? AND user_two = ? AND status = 'accepted'
        ");
        $disconnectStmt->execute([min($userId, $targetId), max($userId, $targetId)]);
        if ($disconnectStmt->rowCount() !== 1) {
            studentFriendsResponse(409, ['success' => false, 'message' => 'This connection is no longer active.']);
        }
        studentFriendsResponse(200, ['success' => true, 'connection_status' => '']);
    }

    if ($method === 'POST' && $action === 'notification_preference') {
        $targetId = filter_var($request['target_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $mode = isset($request['mode']) && is_string($request['mode']) ? $request['mode'] : '';
        if ($targetId === false || $targetId === $userId || !in_array($mode, ['all', 'muted', 'mentions'], true)) {
            studentFriendsResponse(422, ['success' => false, 'message' => 'Choose a valid conversation notification setting.']);
        }
        $connectionStmt = $pdo->prepare("
            SELECT 1 FROM student_community_connections
            WHERE user_one = ? AND user_two = ? AND status = 'accepted'
            LIMIT 1
        ");
        $connectionStmt->execute([min($userId, $targetId), max($userId, $targetId)]);
        if (!$connectionStmt->fetchColumn()) {
            studentFriendsResponse(403, ['success' => false, 'message' => 'This personal conversation is no longer available.']);
        }
        $preferenceStmt = $pdo->prepare("
            INSERT INTO student_chat_preferences (user_id, chat_type, chat_id, notification_mode)
            VALUES (?, 'direct', ?, ?)
            ON DUPLICATE KEY UPDATE notification_mode = VALUES(notification_mode)
        ");
        $preferenceStmt->execute([$userId, $targetId, $mode]);
        studentFriendsResponse(200, ['success' => true, 'notification_mode' => $mode]);
    }

    if ($method === 'POST' && $action === 'edit_message') {
        $targetId = filter_var($request['target_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $messageId = filter_var($request['message_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $message = isset($request['message']) && is_string($request['message']) ? trim($request['message']) : '';
        $messageLength = preg_match_all('/./us', $message);
        if ($targetId === false || $targetId === $userId || $messageId === false || $message === '' || $messageLength === false || $messageLength > 2000) {
            studentFriendsResponse(422, ['success' => false, 'message' => 'Messages must contain 1 to 2,000 characters.']);
        }
        $pdo->beginTransaction();
        $connectionStmt = $pdo->prepare("
            SELECT 1 FROM student_community_connections
            WHERE user_one = ? AND user_two = ? AND status = 'accepted'
            LIMIT 1
            FOR UPDATE
        ");
        $connectionStmt->execute([min($userId, $targetId), max($userId, $targetId)]);
        if (!$connectionStmt->fetchColumn()) {
            $pdo->rollBack();
            studentFriendsResponse(403, ['success' => false, 'message' => 'This personal conversation is no longer available.']);
        }
        $messageStmt = $pdo->prepare("
            SELECT message_type FROM student_direct_messages
            WHERE id = ? AND sender_id = ? AND recipient_id = ?
            LIMIT 1 FOR UPDATE
        ");
        $messageStmt->execute([$messageId, $userId, $targetId]);
        $existing = $messageStmt->fetch(PDO::FETCH_ASSOC);
        if (!$existing || $existing['message_type'] !== 'message') {
            $pdo->rollBack();
            studentFriendsResponse(404, ['success' => false, 'message' => 'Message not found or cannot be edited.']);
        }
        $editStmt = $pdo->prepare("
            UPDATE student_direct_messages
            SET message = ?, edited_at = CURRENT_TIMESTAMP
            WHERE id = ? AND sender_id = ? AND recipient_id = ?
        ");
        $editStmt->execute([$message, $messageId, $userId, $targetId]);
        $pdo->commit();
        studentFriendsResponse(200, [
            'success' => true,
            'message' => [
                'id' => (int) $messageId,
                'message' => $message,
                'edited_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }

    if ($action === 'delete_message') {
        $targetId = filter_var($request['target_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $messageId = filter_var($request['message_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($targetId === false || $targetId === $userId || $messageId === false) {
            studentFriendsResponse(422, ['success' => false, 'message' => 'Choose a valid message.']);
        }

        $pdo->beginTransaction();
        $connectionStmt = $pdo->prepare("
            SELECT 1 FROM student_community_connections
            WHERE user_one = ? AND user_two = ? AND status = 'accepted'
            LIMIT 1
            FOR UPDATE
        ");
        $connectionStmt->execute([min($userId, $targetId), max($userId, $targetId)]);
        if (!$connectionStmt->fetchColumn()) {
            $pdo->rollBack();
            studentFriendsResponse(403, ['success' => false, 'message' => 'This personal conversation is no longer available.']);
        }

        $messageStmt = $pdo->prepare("
            SELECT attachment_path, message_type, created_at
            FROM student_direct_messages
            WHERE id = ? AND sender_id = ? AND recipient_id = ?
            LIMIT 1
            FOR UPDATE
        ");
        $messageStmt->execute([$messageId, $userId, $targetId]);
        $messageToDelete = $messageStmt->fetch(PDO::FETCH_ASSOC);
        if (!$messageToDelete) {
            $pdo->rollBack();
            studentFriendsResponse(404, ['success' => false, 'message' => 'Message not found or you cannot delete it.']);
        }
        if ($messageToDelete['message_type'] === 'deleted') {
            $pdo->rollBack();
            studentFriendsResponse(409, ['success' => false, 'message' => 'This message has already been deleted.']);
        }

        $deleteStmt = $pdo->prepare("
            UPDATE student_direct_messages
            SET message = 'This message was deleted',
                message_type = 'deleted',
                attachment_path = NULL,
                attachment_name = NULL,
                attachment_mime = NULL,
                attachment_size = NULL
            WHERE id = ? AND sender_id = ? AND recipient_id = ?
        ");
        $deleteStmt->execute([$messageId, $userId, $targetId]);
        $pdo->commit();

        $attachmentPath = (string) ($messageToDelete['attachment_path'] ?? '');
        if (preg_match('/\A[a-f0-9]{32}\.[a-z0-9]{1,10}\z/', $attachmentPath)) {
            $path = __DIR__ . '/uploads/direct_messages/' . $attachmentPath;
            if (is_file($path) && !unlink($path)) {
                error_log('Unable to remove deleted personal message attachment: ' . $path);
            }
        }

        studentFriendsResponse(200, [
            'success' => true,
            'message' => [
                'id' => (int) $messageId,
                'sender_id' => $userId,
                'recipient_id' => $targetId,
                'message' => 'This message was deleted',
                'message_type' => 'deleted',
                'created_at' => (string) $messageToDelete['created_at'],
                'sender_name' => '',
                'attachment' => null,
            ],
        ]);
    }

    if ($action === 'send_message') {
        $targetId = filter_var($request['target_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($targetId === false || $targetId === $userId) {
            studentFriendsResponse(422, ['success' => false, 'message' => 'Choose a valid connection.']);
        }
        $replyToId = null;
        if (isset($request['reply_to_id']) && $request['reply_to_id'] !== '') {
            $validatedReplyToId = filter_var($request['reply_to_id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if ($validatedReplyToId === false) {
                studentFriendsResponse(422, ['success' => false, 'message' => 'Choose a valid message to reply to.']);
            }
            $replyToId = (int) $validatedReplyToId;
        }
        $message = isset($request['message']) && is_string($request['message']) ? trim($request['message']) : '';
        $messageLength = preg_match_all('/./us', $message);
        $attachment = $_FILES['attachment'] ?? null;
        $hasAttachment = is_array($attachment) && ($attachment['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;
        if ($messageLength === false || $messageLength > 2000 || ($message === '' && !$hasAttachment)) {
            studentFriendsResponse(422, ['success' => false, 'message' => 'Enter a message or attach a file. Messages must be no more than 2,000 characters.']);
        }

        $attachmentMime = null;
        $attachmentExtension = null;
        $attachmentName = null;
        $attachmentSize = null;
        if ($hasAttachment) {
            if (($attachment['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                $uploadErrors = [
                    UPLOAD_ERR_INI_SIZE => 'The file exceeds the server upload limit.',
                    UPLOAD_ERR_FORM_SIZE => 'The file exceeds the upload limit.',
                    UPLOAD_ERR_PARTIAL => 'The file was only partially uploaded.',
                    UPLOAD_ERR_NO_TMP_DIR => 'The server upload directory is unavailable.',
                    UPLOAD_ERR_CANT_WRITE => 'The server could not save the uploaded file.',
                    UPLOAD_ERR_EXTENSION => 'The server blocked this upload.',
                ];
                $uploadError = (int) $attachment['error'];
                studentFriendsResponse(422, [
                    'success' => false,
                    'message' => $uploadErrors[$uploadError] ?? 'File upload failed.',
                ]);
            }

            $allowedFiles = [
                'jpg' => ['image/jpeg', 'image/jpeg'], 'jpeg' => ['image/jpeg', 'image/jpeg'],
                'png' => ['image/png', 'image/png'], 'gif' => ['image/gif', 'image/gif'],
                'webp' => ['image/webp', 'image/webp'],
                'mp4' => ['video/mp4', 'video/mp4'], 'webm' => ['video/webm', 'video/webm'],
                'mov' => ['video/quicktime', 'video/quicktime'],
                'pdf' => ['application/pdf', 'application/pdf'],
                'doc' => ['application/msword', 'application/msword'],
                'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'],
                'xls' => ['application/vnd.ms-excel', 'application/vnd.ms-excel'],
                'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip'],
                'ppt' => ['application/vnd.ms-powerpoint', 'application/vnd.ms-powerpoint'],
                'pptx' => ['application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/zip'],
                'txt' => ['text/plain', 'text/plain'], 'csv' => ['text/csv', 'text/plain'],
            ];
            $attachmentExtension = strtolower(pathinfo((string) ($attachment['name'] ?? ''), PATHINFO_EXTENSION));
            $attachmentSize = (int) ($attachment['size'] ?? 0);
            if ($attachmentSize < 1 || $attachmentSize > 25 * 1024 * 1024) {
                studentFriendsResponse(422, ['success' => false, 'message' => 'Attachments must be no larger than 25 MB.']);
            }
            if (!isset($allowedFiles[$attachmentExtension]) || !is_uploaded_file((string) ($attachment['tmp_name'] ?? ''))) {
                studentFriendsResponse(422, ['success' => false, 'message' => 'Unsupported attachment. Choose an image, video, or document.']);
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $attachmentMime = $finfo->file($attachment['tmp_name']);
            if (!in_array($attachmentMime, $allowedFiles[$attachmentExtension], true)) {
                studentFriendsResponse(422, ['success' => false, 'message' => 'The file content does not match its file type.']);
            }
            $attachmentName = trim(basename((string) $attachment['name']));
            if ($attachmentName === '' || strlen($attachmentName) > 255) {
                studentFriendsResponse(422, ['success' => false, 'message' => 'The attachment filename must be 255 characters or fewer.']);
            }
        }

        $pdo->beginTransaction();
        $connectionStmt = $pdo->prepare("
            SELECT 1 FROM student_community_connections
            WHERE user_one = ? AND user_two = ? AND status = 'accepted'
            LIMIT 1
            FOR UPDATE
        ");
        $connectionStmt->execute([min($userId, $targetId), max($userId, $targetId)]);
        if (!$connectionStmt->fetchColumn()) {
            $pdo->rollBack();
            studentFriendsResponse(403, ['success' => false, 'message' => 'Connect with this student before messaging.']);
        }
        if ($replyToId !== null) {
            $replyStmt = $pdo->prepare("
                SELECT id
                FROM student_direct_messages
                WHERE id = ?
                  AND ((sender_id = ? AND recipient_id = ?) OR (sender_id = ? AND recipient_id = ?))
                LIMIT 1
            ");
            $replyStmt->execute([$replyToId, $userId, $targetId, $targetId, $userId]);
            if (!$replyStmt->fetchColumn()) {
                $pdo->rollBack();
                studentFriendsResponse(422, ['success' => false, 'message' => 'The message you selected is not in this conversation.']);
            }
        }
        $studentStmt = $pdo->prepare("
            SELECT COALESCE(NULLIF(TRIM(CONCAT_WS(' ', first_name, last_name)), ''), 'Student')
            FROM users WHERE id = ? AND role = 'student' AND COALESCE(is_active, 1) = 1 LIMIT 1
        ");
        $studentStmt->execute([$userId]);
        $senderName = $studentStmt->fetchColumn();
        if ($senderName === false) {
            $pdo->rollBack();
            studentFriendsResponse(401, ['success' => false, 'message' => 'Your student account is not active.']);
        }
        $storedAttachmentName = null;
        $uploadedAttachmentPath = null;
        if ($hasAttachment) {
            $uploadDir = __DIR__ . '/uploads/direct_messages';
            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0750, true) && !is_dir($uploadDir)) {
                throw new RuntimeException('Unable to create the personal message attachment directory.');
            }
            $storedAttachmentName = bin2hex(random_bytes(16)) . '.' . $attachmentExtension;
            $uploadedAttachmentPath = $uploadDir . DIRECTORY_SEPARATOR . $storedAttachmentName;
            if (!move_uploaded_file($attachment['tmp_name'], $uploadedAttachmentPath)) {
                throw new RuntimeException('Unable to store the uploaded personal message attachment.');
            }
        }
        try {
            $insertStmt = $pdo->prepare("
                INSERT INTO student_direct_messages
                    (sender_id, recipient_id, message, attachment_path, attachment_name, attachment_mime, attachment_size, reply_to_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insertStmt->execute([
                $userId, $targetId, $message, $storedAttachmentName,
                $attachmentName, $attachmentMime, $attachmentSize, $replyToId,
            ]);
            $messageId = (int) $pdo->lastInsertId();
            $messageStmt = $pdo->prepare("
                SELECT m.created_at, replied.id AS reply_to_id, replied.sender_id AS reply_sender_id,
                       COALESCE(NULLIF(TRIM(CONCAT_WS(' ', reply_user.first_name, reply_user.last_name)), ''), 'Student') AS reply_sender_name,
                       replied.message AS reply_message, replied.message_type AS reply_message_type,
                       replied.attachment_name AS reply_attachment_name,
                       replied.attachment_mime AS reply_attachment_mime
                FROM student_direct_messages m
                LEFT JOIN student_direct_messages replied ON replied.id = m.reply_to_id
                LEFT JOIN users reply_user ON reply_user.id = replied.sender_id
                WHERE m.id = ?
            ");
            $messageStmt->execute([$messageId]);
            $savedMessageDetails = $messageStmt->fetch(PDO::FETCH_ASSOC);
            $createdAt = (string) $savedMessageDetails['created_at'];
            $notificationContent = (string) $senderName . ' sent you ' . (
                strpos((string) $attachmentMime, 'image/') === 0 ? 'a photo.' :
                (strpos((string) $attachmentMime, 'video/') === 0 ? 'a video.' :
                ($hasAttachment ? 'an attachment.' : 'a message.'))
            );
            $preferenceStmt = $pdo->prepare("
                SELECT notification_mode FROM student_chat_preferences
                WHERE user_id = ? AND chat_type = 'direct' AND chat_id = ?
                LIMIT 1
            ");
            $preferenceStmt->execute([$targetId, $userId]);
            $notificationMode = (string) ($preferenceStmt->fetchColumn() ?: 'all');
            $recipientNameStmt = $pdo->prepare("
                SELECT TRIM(CONCAT_WS(' ', first_name, last_name))
                FROM users WHERE id = ? LIMIT 1
            ");
            $recipientNameStmt->execute([$targetId]);
            $recipientName = trim((string) $recipientNameStmt->fetchColumn());
            $shouldNotify = $notificationMode === 'all' ||
                ($notificationMode === 'mentions' && $recipientName !== '' && stripos($message, '@' . $recipientName) !== false);
            if ($shouldNotify) {
                $notificationStmt = $pdo->prepare("
                    INSERT INTO notifications (user_id, title, message, type, category, link)
                    VALUES (?, 'New message', ?, 'info', 'community', ?)
                ");
                $notificationStmt->execute([
                    $targetId,
                    $notificationContent,
                    'student-dashboard.php?tab=community&community_tab=inbox&message_with=' . $userId
                ]);
            }
            $pdo->commit();
            $uploadedAttachmentPath = null;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            if ($uploadedAttachmentPath !== null && is_file($uploadedAttachmentPath)) {
                unlink($uploadedAttachmentPath);
            }
            throw $e;
        }

        studentFriendsResponse(201, [
            'success' => true,
            'message' => [
                'id' => $messageId,
                'sender_id' => $userId,
                'recipient_id' => $targetId,
                'message' => $message,
                'message_type' => 'message',
                'created_at' => $createdAt,
                'sender_name' => (string) $senderName,
                'reply_to' => studentDirectReplyDetails($savedMessageDetails),
                'attachment' => $storedAttachmentName === null ? null : [
                    'url' => 'student-direct-message-file.php?id=' . $messageId,
                    'name' => $attachmentName,
                    'mime' => $attachmentMime,
                    'size' => $attachmentSize,
                ],
            ],
        ]);
    }

    $targetId = filter_var($request['target_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($targetId === false || $targetId === $userId) {
        studentFriendsResponse(422, ['success' => false, 'message' => 'Choose a valid student.']);
    }
    $userOne = min($userId, $targetId);
    $userTwo = max($userId, $targetId);

    if ($action === 'request') {
        $profileStmt = $pdo->prepare("
            SELECT u.id FROM users u
            LEFT JOIN student_community_settings s ON s.user_id = u.id
            WHERE u.id = ? AND u.role = 'student' AND COALESCE(u.is_active, 1) = 1
              AND COALESCE(s.discoverable, 1) = 1
            LIMIT 1
        ");
        $profileStmt->execute([$targetId]);
        if (!$profileStmt->fetchColumn()) {
            studentFriendsResponse(404, ['success' => false, 'message' => 'This student is not available for connections.']);
        }

        $pdo->beginTransaction();
        $connectionStmt = $pdo->prepare("
            SELECT status, blocked_by
            FROM student_community_connections
            WHERE user_one = ? AND user_two = ?
            FOR UPDATE
        ");
        $connectionStmt->execute([$userOne, $userTwo]);
        $connection = $connectionStmt->fetch(PDO::FETCH_ASSOC);
        if ($connection && $connection['status'] === 'blocked') {
            $pdo->rollBack();
            studentFriendsResponse(403, ['success' => false, 'message' => 'This connection is unavailable.']);
        }
        if ($connection && in_array($connection['status'], ['pending', 'accepted'], true)) {
            $pdo->rollBack();
            studentFriendsResponse(409, ['success' => false, 'message' => $connection['status'] === 'pending' ? 'A connection request is already pending.' : 'You are already connected.']);
        }

        if ($connection) {
            $updateStmt = $pdo->prepare("
                UPDATE student_community_connections
                SET requester_id = ?, recipient_id = ?, status = 'pending', blocked_by = NULL, updated_at = CURRENT_TIMESTAMP
                WHERE user_one = ? AND user_two = ?
            ");
            $updateStmt->execute([$userId, $targetId, $userOne, $userTwo]);
        } else {
            $insertStmt = $pdo->prepare("
                INSERT INTO student_community_connections (user_one, user_two, requester_id, recipient_id, status)
                VALUES (?, ?, ?, ?, 'pending')
            ");
            $insertStmt->execute([$userOne, $userTwo, $userId, $targetId]);
        }

        $requesterStmt = $pdo->prepare("
            SELECT COALESCE(NULLIF(TRIM(CONCAT_WS(' ', first_name, last_name)), ''), 'A student')
            FROM users
            WHERE id = ?
            LIMIT 1
        ");
        $requesterStmt->execute([$userId]);
        $requesterName = (string) ($requesterStmt->fetchColumn() ?: 'A student');
        $notificationStmt = $pdo->prepare("
            INSERT INTO notifications (user_id, title, message, type, category, link)
            VALUES (?, 'New connection request', ?, 'info', 'community', 'student-dashboard.php?tab=community&community_tab=friends')
        ");
        $notificationStmt->execute([$targetId, $requesterName . ' sent you a connection request.']);

        $pdo->commit();
        studentFriendsResponse(201, ['success' => true, 'connection_status' => 'pending']);
    }

    if ($action === 'respond') {
        $decision = isset($request['decision']) && is_string($request['decision']) ? $request['decision'] : '';
        if (!in_array($decision, ['accept', 'decline'], true)) {
            studentFriendsResponse(422, ['success' => false, 'message' => 'Choose accept or decline.']);
        }
        $status = $decision === 'accept' ? 'accepted' : 'declined';
        if ($decision === 'accept') {
            $pdo->beginTransaction();
        }
        $stmt = $pdo->prepare("
            UPDATE student_community_connections
            SET status = ?, updated_at = CURRENT_TIMESTAMP
            WHERE user_one = ? AND user_two = ? AND recipient_id = ? AND status = 'pending'
        ");
        $stmt->execute([$status, $userOne, $userTwo, $userId]);
        if ($stmt->rowCount() !== 1) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            studentFriendsResponse(409, ['success' => false, 'message' => 'This request is no longer available.']);
        }
        if ($decision === 'accept') {
            $requesterStmt = $pdo->prepare("
                SELECT c.requester_id,
                       COALESCE(NULLIF(TRIM(CONCAT_WS(' ', u.first_name, u.last_name)), ''), 'A student') AS accepter_name
                FROM student_community_connections c
                INNER JOIN users u ON u.id = c.recipient_id
                WHERE c.user_one = ? AND c.user_two = ? AND c.recipient_id = ? AND c.status = 'accepted'
                LIMIT 1
            ");
            $requesterStmt->execute([$userOne, $userTwo, $userId]);
            $acceptedConnection = $requesterStmt->fetch(PDO::FETCH_ASSOC);
            if (!$acceptedConnection) {
                throw new RuntimeException('Unable to find the accepted connection request.');
            }
            $notificationStmt = $pdo->prepare("
                INSERT INTO notifications (user_id, title, message, type, category, link)
                VALUES (?, 'Connection request accepted', ?, 'success', 'community', 'student-dashboard.php?tab=community&community_tab=friends')
            ");
            $notificationStmt->execute([
                (int) $acceptedConnection['requester_id'],
                (string) $acceptedConnection['accepter_name'] . ' accepted your connection request.',
            ]);
            $pdo->commit();
        }
        studentFriendsResponse(200, ['success' => true, 'connection_status' => $status]);
    }

    if ($action === 'block') {
        $profileStmt = $pdo->prepare("SELECT 1 FROM users WHERE id = ? AND role = 'student' AND COALESCE(is_active, 1) = 1 LIMIT 1");
        $profileStmt->execute([$targetId]);
        if (!$profileStmt->fetchColumn()) {
            studentFriendsResponse(404, ['success' => false, 'message' => 'This student could not be found.']);
        }
        $stmt = $pdo->prepare("
            INSERT INTO student_community_connections
                (user_one, user_two, requester_id, recipient_id, status, blocked_by)
            VALUES (?, ?, ?, ?, 'blocked', ?)
            ON DUPLICATE KEY UPDATE status = 'blocked', blocked_by = VALUES(blocked_by), updated_at = CURRENT_TIMESTAMP
        ");
        $stmt->execute([$userOne, $userTwo, $userId, $targetId, $userId]);
        studentFriendsResponse(200, ['success' => true]);
    }

    if ($action === 'report') {
        $reason = isset($request['reason']) && is_string($request['reason']) ? trim($request['reason']) : '';
        $reasonLength = preg_match_all('/./us', $reason);
        if ($reasonLength === false || $reasonLength < 5 || $reasonLength > 1000) {
            studentFriendsResponse(422, ['success' => false, 'message' => 'Please describe the concern in 5 to 1,000 characters.']);
        }
        $profileStmt = $pdo->prepare("SELECT 1 FROM users WHERE id = ? AND role = 'student' LIMIT 1");
        $profileStmt->execute([$targetId]);
        if (!$profileStmt->fetchColumn()) {
            studentFriendsResponse(404, ['success' => false, 'message' => 'This student could not be found.']);
        }
        $reportStmt = $pdo->prepare('INSERT INTO student_community_reports (reporter_id, reported_user_id, reason) VALUES (?, ?, ?)');
        $reportStmt->execute([$userId, $targetId, $reason]);
        studentFriendsResponse(201, ['success' => true, 'message' => 'Your report was submitted for review.']);
    }

    studentFriendsResponse(400, ['success' => false, 'message' => 'Unknown student connection action.']);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Student community friends request failed: ' . $e->getMessage());
    $message = $e->getCode() === '42S02'
        ? 'Student messaging setup is incomplete. Run setup_student_features.php once, then refresh this page.'
        : 'The student directory is temporarily unavailable. Please try again.';
    studentFriendsResponse(500, ['success' => false, 'message' => $message]);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Unexpected student community friends error: ' . $e->getMessage());
    studentFriendsResponse(500, ['success' => false, 'message' => 'The student directory is temporarily unavailable. Please try again.']);
}
