<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store, max-age=0');

require_once __DIR__ . '/includes/config.php';

function studentGroupChatResponse(int $status, array $payload): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

function ensureGroupMessageTypeColumn(PDO $pdo): void
{
    if ((int) ($_SESSION['community_group_message_schema_version'] ?? 0) >= 5) {
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
        $columnStmt = $pdo->query("SHOW COLUMNS FROM community_group_messages LIKE " . $pdo->quote($columnName));
        if (!$columnStmt->fetch(PDO::FETCH_ASSOC)) {
            $pdo->exec("ALTER TABLE community_group_messages ADD COLUMN {$columnName} {$definition}");
        }
    }
    $editedColumnStmt = $pdo->query("SHOW COLUMNS FROM community_group_messages LIKE 'edited_at'");
    if (!$editedColumnStmt->fetch(PDO::FETCH_ASSOC)) {
        $pdo->exec('ALTER TABLE community_group_messages ADD COLUMN edited_at DATETIME DEFAULT NULL AFTER reply_to_id');
    }
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS student_group_message_reads (
            user_id INT NOT NULL,
            group_id INT NOT NULL,
            last_read_message_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (user_id, group_id),
            INDEX idx_group_message_reads_group (group_id, last_read_message_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    $pdo->exec("
        INSERT IGNORE INTO student_group_message_reads (user_id, group_id, last_read_message_id)
        SELECT gm.student_id, gm.group_id, COALESCE(MAX(m.id), 0)
        FROM community_group_members gm
        LEFT JOIN community_group_messages m ON m.group_id = gm.group_id
        GROUP BY gm.student_id, gm.group_id
    ");
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
    $_SESSION['community_group_message_schema_version'] = 5;
}

function studentGroupAttachmentDetails(array $row): ?array
{
    if (empty($row['attachment_path'])) {
        return null;
    }

    return [
        'url' => 'student-group-chat-file.php?id=' . (int) $row['id'],
        'name' => (string) $row['attachment_name'],
        'mime' => (string) $row['attachment_mime'],
        'size' => (int) $row['attachment_size'],
    ];
}

function studentGroupReplyDetails(array $row): ?array
{
    if (empty($row['reply_to_id'])) {
        return null;
    }

    return [
        'id' => (int) $row['reply_to_id'],
        'user_id' => (int) $row['reply_user_id'],
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
    studentGroupChatResponse(401, ['success' => false, 'message' => 'Please sign in with a student account.']);
}

$userId = (int) $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'] ?? '';
$jsonBody = [];

if ($method === 'POST') {
    if (stripos((string) ($_SERVER['CONTENT_TYPE'] ?? ''), 'application/json') !== false) {
        $rawBody = file_get_contents('php://input');
        if (is_string($rawBody) && $rawBody !== '') {
            $decodedBody = json_decode($rawBody, true);
            if (is_array($decodedBody)) {
                $jsonBody = $decodedBody;
            }
        }
    }
    $request = array_merge($_POST, $jsonBody);
    if (
        !isset($request['csrf_token']) ||
        !is_string($request['csrf_token']) ||
        empty($_SESSION['community_csrf_token']) ||
        !hash_equals($_SESSION['community_csrf_token'], $request['csrf_token'])
    ) {
        studentGroupChatResponse(403, ['success' => false, 'message' => 'Session expired. Refresh the page and try again.']);
    }
} elseif ($method === 'GET') {
    $request = $_GET;
} else {
    header('Allow: GET, POST');
    studentGroupChatResponse(405, ['success' => false, 'message' => 'This request method is not supported.']);
}

$action = isset($request['action']) && is_string($request['action']) ? $request['action'] : '';
$groupId = filter_var($request['group_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

try {
    if (in_array($action, ['messages', 'read_status', 'join', 'send', 'delete', 'edit', 'notification_preference', 'unread_counts', 'create'], true)) {
        ensureGroupMessageTypeColumn($pdo);
    }

    if ($method === 'GET' && $action === 'unread_counts') {
        $unreadStmt = $pdo->prepare("
            SELECT gm.group_id, COUNT(m.id) AS unread_count
            FROM community_group_members gm
            LEFT JOIN student_group_message_reads read_cursor
                ON read_cursor.user_id = gm.student_id AND read_cursor.group_id = gm.group_id
            LEFT JOIN community_group_messages m
                ON m.group_id = gm.group_id
               AND m.id > COALESCE(read_cursor.last_read_message_id, 0)
               AND m.user_id <> gm.student_id
               AND m.message_type = 'message'
            WHERE gm.student_id = ?
            GROUP BY gm.group_id
        ");
        $unreadStmt->execute([$userId]);
        $unreadCounts = [];
        $totalUnread = 0;
        foreach ($unreadStmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $count = (int) $row['unread_count'];
            $unreadCounts[(string) $row['group_id']] = $count;
            $totalUnread += $count;
        }
        studentGroupChatResponse(200, [
            'success' => true,
            'unread_counts' => $unreadCounts,
            'total_unread' => $totalUnread,
        ]);
    }

    if ($method === 'GET' && $action === 'notification_preference') {
        if ($groupId === false) {
            studentGroupChatResponse(422, ['success' => false, 'message' => 'Choose a valid group.']);
        }
        $memberStmt = $pdo->prepare('SELECT 1 FROM community_group_members WHERE group_id = ? AND student_id = ? LIMIT 1');
        $memberStmt->execute([$groupId, $userId]);
        if (!$memberStmt->fetchColumn()) {
            studentGroupChatResponse(403, ['success' => false, 'message' => 'Join this group to view its notification setting.']);
        }
        $preferenceStmt = $pdo->prepare("
            SELECT notification_mode FROM student_chat_preferences
            WHERE user_id = ? AND chat_type = 'group' AND chat_id = ?
            LIMIT 1
        ");
        $preferenceStmt->execute([$userId, $groupId]);
        studentGroupChatResponse(200, ['success' => true, 'notification_mode' => $preferenceStmt->fetchColumn() ?: 'all']);
    }

    if ($method === 'GET' && $action === 'read_status') {
        if ($groupId === false) {
            studentGroupChatResponse(422, ['success' => false, 'message' => 'Choose a valid group.']);
        }
        $memberStmt = $pdo->prepare('SELECT 1 FROM community_group_members WHERE group_id = ? AND student_id = ? LIMIT 1');
        $memberStmt->execute([$groupId, $userId]);
        if (!$memberStmt->fetchColumn()) {
            studentGroupChatResponse(403, ['success' => false, 'message' => 'Join this group to view message read status.']);
        }
        $statusStmt = $pdo->prepare("
            SELECT own.id,
                   (SELECT COUNT(*) FROM student_group_message_reads r
                    WHERE r.group_id = own.group_id
                      AND r.last_read_message_id >= own.id
                      AND r.user_id <> ?) AS read_count
            FROM (
                SELECT id, group_id FROM community_group_messages
                WHERE group_id = ? AND user_id = ? AND message_type = 'message'
                ORDER BY id DESC LIMIT 100
            ) own
        ");
        $statusStmt->execute([$userId, $groupId, $userId]);
        studentGroupChatResponse(200, ['success' => true, 'read_status' => $statusStmt->fetchAll(PDO::FETCH_ASSOC)]);
    }

    if ($method === 'GET' && $action === 'messages') {
        if ($groupId === false) {
            studentGroupChatResponse(422, ['success' => false, 'message' => 'Choose a valid group.']);
        }

        $memberStmt = $pdo->prepare("
            SELECT g.id, g.name,
                   (SELECT COUNT(*) FROM community_group_members gm WHERE gm.group_id = g.id) AS member_count
            FROM community_groups g
            INNER JOIN community_group_members mine
                ON mine.group_id = g.id AND mine.student_id = ?
            WHERE g.id = ? AND g.is_active = 1
            LIMIT 1
        ");
        $memberStmt->execute([$userId, $groupId]);
        $group = $memberStmt->fetch(PDO::FETCH_ASSOC);
        if (!$group) {
            studentGroupChatResponse(403, ['success' => false, 'message' => 'Join this group to view its chat.']);
        }

        $afterId = filter_var($request['after_id'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
        if ($afterId === false) {
            studentGroupChatResponse(422, ['success' => false, 'message' => 'Invalid message cursor.']);
        }

        if ($afterId === 0) {
            $messageStmt = $pdo->prepare("
                SELECT recent.id, recent.user_id, recent.message, recent.message_type, recent.reply_to_id,
                       recent.attachment_path, recent.attachment_name, recent.attachment_mime,
                       recent.attachment_size, recent.edited_at, recent.created_at,
                       COALESCE(NULLIF(TRIM(CONCAT_WS(' ', u.first_name, u.last_name)), ''), 'Student') AS sender_name
                FROM (
                    SELECT id, user_id, message, message_type, reply_to_id, attachment_path,
                           attachment_name, attachment_mime, attachment_size, edited_at, created_at
                    FROM community_group_messages
                    WHERE group_id = ?
                    ORDER BY id DESC
                    LIMIT 100
                ) recent
                LEFT JOIN users u ON u.id = recent.user_id
                ORDER BY recent.id ASC
            ");
            $messageStmt->execute([$groupId]);
        } else {
            $messageStmt = $pdo->prepare("
                SELECT m.id, m.user_id, m.message, m.message_type, m.reply_to_id,
                       m.attachment_path, m.attachment_name, m.attachment_mime,
                       m.attachment_size, m.edited_at, m.created_at,
                       COALESCE(NULLIF(TRIM(CONCAT_WS(' ', u.first_name, u.last_name)), ''), 'Student') AS sender_name
                FROM community_group_messages m
                LEFT JOIN users u ON u.id = m.user_id
                WHERE m.group_id = ? AND m.id > ?
                ORDER BY m.id ASC
                LIMIT 100
            ");
            $messageStmt->execute([$groupId, $afterId]);
            $deletedMessagesStmt = $pdo->prepare("
                SELECT m.id, m.user_id, m.message, m.message_type, m.reply_to_id,
                       m.attachment_path, m.attachment_name, m.attachment_mime,
                       m.attachment_size, m.edited_at, m.created_at,
                       COALESCE(NULLIF(TRIM(CONCAT_WS(' ', u.first_name, u.last_name)), ''), 'Student') AS sender_name
                FROM community_group_messages m
                LEFT JOIN users u ON u.id = m.user_id
                WHERE m.group_id = ? AND m.id <= ? AND m.message_type = 'deleted'
                ORDER BY m.id DESC
                LIMIT 100
            ");
            $deletedMessagesStmt->execute([$groupId, $afterId]);
        }

        $messages = $messageStmt->fetchAll(PDO::FETCH_ASSOC);
        if ($afterId > 0) {
            $messages = array_merge($messages, $deletedMessagesStmt->fetchAll(PDO::FETCH_ASSOC));
            usort($messages, static function (array $a, array $b): int {
                return (int) $a['id'] <=> (int) $b['id'];
            });
        }
        if ($messages) {
            $messageIds = array_map(static function (array $message): int {
                return (int) $message['id'];
            }, $messages);
            $readCountsStmt = $pdo->prepare("
                SELECT m.id, COUNT(r.user_id) AS read_count
                FROM community_group_messages m
                LEFT JOIN student_group_message_reads r
                    ON r.group_id = m.group_id
                   AND r.last_read_message_id >= m.id
                   AND r.user_id <> ?
                WHERE m.group_id = ? AND m.id IN (" . implode(',', array_fill(0, count($messageIds), '?')) . ")
                GROUP BY m.id
            ");
            $readCountsStmt->execute(array_merge([$userId, $groupId], $messageIds));
            $readCounts = [];
            foreach ($readCountsStmt->fetchAll(PDO::FETCH_ASSOC) as $readCountRow) {
                $readCounts[(int) $readCountRow['id']] = (int) $readCountRow['read_count'];
            }
            foreach ($messages as &$readMessage) {
                $readMessage['read_count'] = $readCounts[(int) $readMessage['id']] ?? 0;
            }
            unset($readMessage);
            $visibleMessageIds = array_map(static function (array $message): int {
                return (int) $message['id'];
            }, $messages);
            $lastVisibleMessageId = max($visibleMessageIds);
            $markGroupReadStmt = $pdo->prepare("
                INSERT INTO student_group_message_reads (user_id, group_id, last_read_message_id)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE last_read_message_id = GREATEST(last_read_message_id, VALUES(last_read_message_id))
            ");
            $markGroupReadStmt->execute([$userId, $groupId, $lastVisibleMessageId]);
        }
        $replyIds = [];
        foreach ($messages as $chatMessage) {
            if (!empty($chatMessage['reply_to_id'])) {
                $replyIds[] = (int) $chatMessage['reply_to_id'];
            }
        }
        $replyDetailsById = [];
        if ($replyIds) {
            $replyIds = array_values(array_unique($replyIds));
            $replyStmt = $pdo->prepare("
                SELECT replied.id, replied.user_id, replied.message, replied.message_type,
                       replied.attachment_name, replied.attachment_mime,
                       COALESCE(NULLIF(TRIM(CONCAT_WS(' ', reply_user.first_name, reply_user.last_name)), ''), 'Student') AS sender_name
                FROM community_group_messages replied
                LEFT JOIN users reply_user ON reply_user.id = replied.user_id
                WHERE replied.group_id = ? AND replied.id IN (" . implode(',', array_fill(0, count($replyIds), '?')) . ")
            ");
            $replyStmt->execute(array_merge([$groupId], $replyIds));
            foreach ($replyStmt->fetchAll(PDO::FETCH_ASSOC) as $replyRow) {
                $replyDetailsById[(int) $replyRow['id']] = $replyRow;
            }
        }
        foreach ($messages as &$chatMessage) {
            $chatMessage['attachment'] = studentGroupAttachmentDetails($chatMessage);
            $replyRow = $replyDetailsById[(int) ($chatMessage['reply_to_id'] ?? 0)] ?? null;
            if ($replyRow !== null) {
                $chatMessage['reply_user_id'] = $replyRow['user_id'];
                $chatMessage['reply_sender_name'] = $replyRow['sender_name'];
                $chatMessage['reply_message'] = $replyRow['message'];
                $chatMessage['reply_message_type'] = $replyRow['message_type'];
                $chatMessage['reply_attachment_name'] = $replyRow['attachment_name'];
                $chatMessage['reply_attachment_mime'] = $replyRow['attachment_mime'];
            }
            $chatMessage['reply_to'] = $replyRow === null
                ? null
                : studentGroupReplyDetails($chatMessage);
            unset(
                $chatMessage['reply_to_id'],
                $chatMessage['reply_user_id'],
                $chatMessage['reply_sender_name'],
                $chatMessage['reply_message'],
                $chatMessage['reply_message_type'],
                $chatMessage['reply_attachment_name'],
                $chatMessage['reply_attachment_mime'],
                $chatMessage['attachment_path'],
                $chatMessage['attachment_name'],
                $chatMessage['attachment_mime'],
                $chatMessage['attachment_size']
            );
        }
        unset($chatMessage);

        studentGroupChatResponse(200, [
            'success' => true,
            'group' => $group,
            'messages' => $messages,
        ]);
    }

    if ($method !== 'POST') {
        studentGroupChatResponse(400, ['success' => false, 'message' => 'Invalid group request.']);
    }

    if ($action === 'create') {
        $name = isset($request['name']) && is_string($request['name']) ? trim($request['name']) : '';
        $description = isset($request['description']) && is_string($request['description']) ? trim($request['description']) : '';
        $nameLength = preg_match_all('/./us', $name);
        $descriptionLength = preg_match_all('/./us', $description);
        if ($name === '' || $nameLength === false || $nameLength > 150) {
            studentGroupChatResponse(422, ['success' => false, 'message' => 'Group names must be between 1 and 150 characters.']);
        }
        if ($descriptionLength === false || $descriptionLength > 500) {
            studentGroupChatResponse(422, ['success' => false, 'message' => 'Group descriptions must be 500 characters or fewer.']);
        }

        $pdo->beginTransaction();
        $createStmt = $pdo->prepare('INSERT INTO community_groups (name, description, is_active) VALUES (?, ?, 1)');
        $createStmt->execute([$name, $description]);
        $newGroupId = (int) $pdo->lastInsertId();
        $memberStmt = $pdo->prepare('INSERT INTO community_group_members (group_id, student_id) VALUES (?, ?)');
        $memberStmt->execute([$newGroupId, $userId]);
        $readCursorStmt = $pdo->prepare('INSERT INTO student_group_message_reads (user_id, group_id, last_read_message_id) VALUES (?, ?, 0)');
        $readCursorStmt->execute([$userId, $newGroupId]);
        $pdo->commit();

        studentGroupChatResponse(201, [
            'success' => true,
            'group' => [
                'id' => $newGroupId,
                'name' => $name,
                'description' => $description,
                'member_count' => 1,
            ],
        ]);
    }

    if ($groupId === false) {
        studentGroupChatResponse(422, ['success' => false, 'message' => 'Choose a valid group.']);
    }

    $activeGroupStmt = $pdo->prepare('SELECT id, name FROM community_groups WHERE id = ? AND is_active = 1 LIMIT 1');
    $activeGroupStmt->execute([$groupId]);
    $activeGroup = $activeGroupStmt->fetch(PDO::FETCH_ASSOC);
    if (!$activeGroup) {
        studentGroupChatResponse(404, ['success' => false, 'message' => 'This group is no longer available.']);
    }

    if ($action === 'join') {
        $senderStmt = $pdo->prepare("
            SELECT COALESCE(NULLIF(TRIM(CONCAT_WS(' ', first_name, last_name)), ''), 'Student')
            FROM users WHERE id = ? LIMIT 1
        ");
        $senderStmt->execute([$userId]);
        $senderName = (string) ($senderStmt->fetchColumn() ?: 'Student');

        $pdo->beginTransaction();
        $joinStmt = $pdo->prepare('INSERT IGNORE INTO community_group_members (group_id, student_id) VALUES (?, ?)');
        $joinStmt->execute([$groupId, $userId]);
        $wasAdded = $joinStmt->rowCount() > 0;
        if ($wasAdded) {
            $readCursorStmt = $pdo->prepare("
                INSERT INTO student_group_message_reads (user_id, group_id, last_read_message_id)
                SELECT ?, ?, COALESCE(MAX(id), 0)
                FROM community_group_messages
                WHERE group_id = ?
                ON DUPLICATE KEY UPDATE last_read_message_id = GREATEST(last_read_message_id, VALUES(last_read_message_id))
            ");
            $readCursorStmt->execute([$userId, $groupId, $groupId]);
            $eventStmt = $pdo->prepare("
                INSERT INTO community_group_messages (group_id, user_id, message, message_type)
                VALUES (?, ?, 'joined the group', 'member_joined')
            ");
            $eventStmt->execute([$groupId, $userId]);

            $notificationStmt = $pdo->prepare("
                INSERT INTO notifications (user_id, title, message, type, category, link)
                SELECT gm.student_id, ?, ?, 'info', 'community', ?
                FROM community_group_members gm
                WHERE gm.group_id = ? AND gm.student_id <> ?
            ");
            $notificationStmt->execute([
                'New member in ' . $activeGroup['name'],
                $senderName . ' joined the group.',
                'student-dashboard.php?tab=community&community_tab=groups&group_id=' . $groupId,
                $groupId,
                $userId,
            ]);
        }
        $countStmt = $pdo->prepare('SELECT COUNT(*) FROM community_group_members WHERE group_id = ?');
        $countStmt->execute([$groupId]);
        $memberCount = (int) $countStmt->fetchColumn();
        $pdo->commit();
        studentGroupChatResponse(200, ['success' => true, 'member_count' => $memberCount, 'added' => $wasAdded]);
    }

    if ($action === 'leave') {
        $leaveStmt = $pdo->prepare('DELETE FROM community_group_members WHERE group_id = ? AND student_id = ?');
        $leaveStmt->execute([$groupId, $userId]);
        $countStmt = $pdo->prepare('SELECT COUNT(*) FROM community_group_members WHERE group_id = ?');
        $countStmt->execute([$groupId]);
        studentGroupChatResponse(200, ['success' => true, 'member_count' => (int) $countStmt->fetchColumn()]);
    }

    if ($action === 'delete') {
        $messageId = filter_var($request['message_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($messageId === false) {
            studentGroupChatResponse(422, ['success' => false, 'message' => 'Choose a valid message.']);
        }

        $memberStmt = $pdo->prepare('SELECT 1 FROM community_group_members WHERE group_id = ? AND student_id = ? LIMIT 1');
        $memberStmt->execute([$groupId, $userId]);
        if (!$memberStmt->fetchColumn()) {
            studentGroupChatResponse(403, ['success' => false, 'message' => 'Join this group to delete messages.']);
        }

        $pdo->beginTransaction();
        $messageStmt = $pdo->prepare("
            SELECT attachment_path, message_type, created_at
            FROM community_group_messages
            WHERE id = ? AND group_id = ? AND user_id = ?
            LIMIT 1
            FOR UPDATE
        ");
        $messageStmt->execute([$messageId, $groupId, $userId]);
        $messageToDelete = $messageStmt->fetch(PDO::FETCH_ASSOC);
        if (!$messageToDelete) {
            $pdo->rollBack();
            studentGroupChatResponse(404, ['success' => false, 'message' => 'Message not found or you cannot delete it.']);
        }
        if ($messageToDelete['message_type'] === 'deleted') {
            $pdo->rollBack();
            studentGroupChatResponse(409, ['success' => false, 'message' => 'This message has already been deleted.']);
        }

        $deleteStmt = $pdo->prepare("
            UPDATE community_group_messages
            SET message = 'This message was deleted',
                message_type = 'deleted',
                attachment_path = NULL,
                attachment_name = NULL,
                attachment_mime = NULL,
                attachment_size = NULL
            WHERE id = ? AND group_id = ? AND user_id = ?
        ");
        $deleteStmt->execute([$messageId, $groupId, $userId]);
        $pdo->commit();

        $attachmentPath = (string) ($messageToDelete['attachment_path'] ?? '');
        if (preg_match('/\A[a-f0-9]{32}\.[a-z0-9]{1,10}\z/', $attachmentPath)) {
            $path = __DIR__ . '/uploads/group_chat/' . $attachmentPath;
            if (is_file($path) && !unlink($path)) {
                error_log('Unable to remove deleted group chat attachment: ' . $path);
            }
        }

        studentGroupChatResponse(200, [
            'success' => true,
            'message' => [
                'id' => (int) $messageId,
                'user_id' => $userId,
                'message' => 'This message was deleted',
                'message_type' => 'deleted',
                'created_at' => (string) $messageToDelete['created_at'],
                'sender_name' => '',
                'attachment' => null,
            ],
        ]);
    }

    if ($action === 'notification_preference') {
        $mode = isset($request['mode']) && is_string($request['mode']) ? $request['mode'] : '';
        if ($groupId === false || !in_array($mode, ['all', 'muted', 'mentions'], true)) {
            studentGroupChatResponse(422, ['success' => false, 'message' => 'Choose a valid group notification setting.']);
        }
        $memberStmt = $pdo->prepare('SELECT 1 FROM community_group_members WHERE group_id = ? AND student_id = ? LIMIT 1');
        $memberStmt->execute([$groupId, $userId]);
        if (!$memberStmt->fetchColumn()) {
            studentGroupChatResponse(403, ['success' => false, 'message' => 'Join this group to change its notification setting.']);
        }
        $preferenceStmt = $pdo->prepare("
            INSERT INTO student_chat_preferences (user_id, chat_type, chat_id, notification_mode)
            VALUES (?, 'group', ?, ?)
            ON DUPLICATE KEY UPDATE notification_mode = VALUES(notification_mode)
        ");
        $preferenceStmt->execute([$userId, $groupId, $mode]);
        studentGroupChatResponse(200, ['success' => true, 'notification_mode' => $mode]);
    }

    if ($action === 'edit') {
        $messageId = filter_var($request['message_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $message = isset($request['message']) && is_string($request['message']) ? trim($request['message']) : '';
        $messageLength = preg_match_all('/./us', $message);
        if ($groupId === false || $messageId === false || $message === '' || $messageLength === false || $messageLength > 2000) {
            studentGroupChatResponse(422, ['success' => false, 'message' => 'Messages must contain 1 to 2,000 characters.']);
        }
        $memberStmt = $pdo->prepare('SELECT 1 FROM community_group_members WHERE group_id = ? AND student_id = ? LIMIT 1');
        $memberStmt->execute([$groupId, $userId]);
        if (!$memberStmt->fetchColumn()) {
            studentGroupChatResponse(403, ['success' => false, 'message' => 'Join this group to edit messages.']);
        }
        $pdo->beginTransaction();
        $messageStmt = $pdo->prepare("
            SELECT message_type FROM community_group_messages
            WHERE id = ? AND group_id = ? AND user_id = ?
            LIMIT 1 FOR UPDATE
        ");
        $messageStmt->execute([$messageId, $groupId, $userId]);
        $existing = $messageStmt->fetch(PDO::FETCH_ASSOC);
        if (!$existing || $existing['message_type'] !== 'message') {
            $pdo->rollBack();
            studentGroupChatResponse(404, ['success' => false, 'message' => 'Message not found or cannot be edited.']);
        }
        $editStmt = $pdo->prepare("
            UPDATE community_group_messages
            SET message = ?, edited_at = CURRENT_TIMESTAMP
            WHERE id = ? AND group_id = ? AND user_id = ?
        ");
        $editStmt->execute([$message, $messageId, $groupId, $userId]);
        $pdo->commit();
        studentGroupChatResponse(200, [
            'success' => true,
            'message' => [
                'id' => (int) $messageId,
                'user_id' => $userId,
                'message' => $message,
                'edited_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }

    if ($action === 'send') {
        $replyToId = null;
        if (isset($request['reply_to_id']) && $request['reply_to_id'] !== '') {
            $validatedReplyToId = filter_var($request['reply_to_id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if ($validatedReplyToId === false) {
                studentGroupChatResponse(422, ['success' => false, 'message' => 'Choose a valid message to reply to.']);
            }
            $replyToId = (int) $validatedReplyToId;
        }
        $message = isset($request['message']) && is_string($request['message']) ? trim($request['message']) : '';
        $messageLength = preg_match_all('/./us', $message);
        $attachment = $_FILES['attachment'] ?? null;
        $hasAttachment = is_array($attachment) && ($attachment['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;
        if ($messageLength === false || $messageLength > 2000 || ($message === '' && !$hasAttachment)) {
            studentGroupChatResponse(422, ['success' => false, 'message' => 'Enter a message or attach a file. Messages must be no more than 2,000 characters.']);
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
                studentGroupChatResponse(422, [
                    'success' => false,
                    'message' => $uploadErrors[$uploadError] ?? 'File upload failed.',
                ]);
            }

            $allowedFiles = [
                'jpg' => ['image/jpeg', 'image/jpeg'],
                'jpeg' => ['image/jpeg', 'image/jpeg'],
                'png' => ['image/png', 'image/png'],
                'gif' => ['image/gif', 'image/gif'],
                'webp' => ['image/webp', 'image/webp'],
                'mp4' => ['video/mp4', 'video/mp4'],
                'webm' => ['video/webm', 'video/webm'],
                'mov' => ['video/quicktime', 'video/quicktime'],
                'pdf' => ['application/pdf', 'application/pdf'],
                'doc' => ['application/msword', 'application/msword'],
                'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'],
                'xls' => ['application/vnd.ms-excel', 'application/vnd.ms-excel'],
                'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip'],
                'ppt' => ['application/vnd.ms-powerpoint', 'application/vnd.ms-powerpoint'],
                'pptx' => ['application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/zip'],
                'txt' => ['text/plain', 'text/plain'],
                'csv' => ['text/csv', 'text/plain'],
            ];
            $attachmentExtension = strtolower(pathinfo((string) ($attachment['name'] ?? ''), PATHINFO_EXTENSION));
            $attachmentSize = (int) ($attachment['size'] ?? 0);
            if ($attachmentSize < 1 || $attachmentSize > 25 * 1024 * 1024) {
                studentGroupChatResponse(422, ['success' => false, 'message' => 'Attachments must be no larger than 25 MB.']);
            }
            if (!isset($allowedFiles[$attachmentExtension]) || !is_uploaded_file((string) ($attachment['tmp_name'] ?? ''))) {
                studentGroupChatResponse(422, ['success' => false, 'message' => 'Unsupported attachment. Choose an image, video, or document.']);
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $attachmentMime = $finfo->file($attachment['tmp_name']);
            if (!in_array($attachmentMime, $allowedFiles[$attachmentExtension], true)) {
                studentGroupChatResponse(422, ['success' => false, 'message' => 'The file content does not match its file type.']);
            }
            $attachmentName = trim(basename((string) $attachment['name']));
            if ($attachmentName === '' || strlen($attachmentName) > 255) {
                studentGroupChatResponse(422, ['success' => false, 'message' => 'The attachment filename must be 255 characters or fewer.']);
            }
        }

        $memberStmt = $pdo->prepare('SELECT 1 FROM community_group_members WHERE group_id = ? AND student_id = ? LIMIT 1');
        $memberStmt->execute([$groupId, $userId]);
        if (!$memberStmt->fetchColumn()) {
            studentGroupChatResponse(403, ['success' => false, 'message' => 'Join this group before sending messages.']);
        }

        $replyDetails = null;
        if ($replyToId !== null) {
            $replyStmt = $pdo->prepare("
                SELECT replied.id AS reply_to_id, replied.user_id AS reply_user_id,
                       replied.message AS reply_message, replied.message_type AS reply_message_type,
                       replied.attachment_name AS reply_attachment_name,
                       replied.attachment_mime AS reply_attachment_mime,
                       COALESCE(NULLIF(TRIM(CONCAT_WS(' ', reply_user.first_name, reply_user.last_name)), ''), 'Student') AS reply_sender_name
                FROM community_group_messages replied
                LEFT JOIN users reply_user ON reply_user.id = replied.user_id
                WHERE replied.id = ? AND replied.group_id = ? AND replied.message_type <> 'deleted'
                LIMIT 1
            ");
            $replyStmt->execute([$replyToId, $groupId]);
            $replyRow = $replyStmt->fetch(PDO::FETCH_ASSOC);
            if (!$replyRow) {
                studentGroupChatResponse(422, ['success' => false, 'message' => 'That message cannot be replied to.']);
            }
            $replyDetails = studentGroupReplyDetails($replyRow);
        }

        $senderStmt = $pdo->prepare("
            SELECT COALESCE(NULLIF(TRIM(CONCAT_WS(' ', first_name, last_name)), ''), 'Student')
            FROM users WHERE id = ? LIMIT 1
        ");
        $senderStmt->execute([$userId]);
        $senderName = (string) ($senderStmt->fetchColumn() ?: 'Student');

        $storedAttachmentName = null;
        $uploadedAttachmentPath = null;
        if ($hasAttachment) {
            $uploadDir = __DIR__ . '/uploads/group_chat';
            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0750, true) && !is_dir($uploadDir)) {
                throw new RuntimeException('Unable to create the group attachment directory.');
            }
            $storedAttachmentName = bin2hex(random_bytes(16)) . '.' . $attachmentExtension;
            $uploadedAttachmentPath = $uploadDir . DIRECTORY_SEPARATOR . $storedAttachmentName;
            if (!move_uploaded_file($attachment['tmp_name'], $uploadedAttachmentPath)) {
                throw new RuntimeException('Unable to store the uploaded group attachment.');
            }
        }

        try {
            $pdo->beginTransaction();
            $insertStmt = $pdo->prepare("
                INSERT INTO community_group_messages
                    (group_id, user_id, message, attachment_path, attachment_name, attachment_mime, attachment_size, reply_to_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insertStmt->execute([
                $groupId,
                $userId,
                $message,
                $storedAttachmentName,
                $attachmentName,
                $attachmentMime,
                $attachmentSize,
                $replyToId,
            ]);
            $messageId = (int) $pdo->lastInsertId();

            $notificationMessage = $senderName . ': ' . ($message !== '' ? $message : 'shared ' . (
                strpos((string) $attachmentMime, 'image/') === 0 ? 'a photo' :
                (strpos((string) $attachmentMime, 'video/') === 0 ? 'a video' : 'a file')
            ));
            $recipientsStmt = $pdo->prepare("
                SELECT gm.student_id,
                       COALESCE(NULLIF(TRIM(CONCAT_WS(' ', u.first_name, u.last_name)), ''), '') AS member_name,
                       COALESCE(pref.notification_mode, 'all') AS notification_mode
                FROM community_group_members gm
                LEFT JOIN users u ON u.id = gm.student_id
                LEFT JOIN student_chat_preferences pref
                    ON pref.user_id = gm.student_id AND pref.chat_type = 'group' AND pref.chat_id = gm.group_id
                WHERE gm.group_id = ? AND gm.student_id <> ?
            ");
            $recipientsStmt->execute([$groupId, $userId]);
            $notificationStmt = $pdo->prepare("
                INSERT INTO notifications (user_id, title, message, type, category, link)
                VALUES (?, ?, ?, 'info', 'community', ?)
            ");
            $notificationLink = 'student-dashboard.php?tab=community&community_tab=groups&group_id=' . $groupId;
            foreach ($recipientsStmt->fetchAll(PDO::FETCH_ASSOC) as $recipient) {
                $mode = (string) $recipient['notification_mode'];
                $memberName = trim((string) $recipient['member_name']);
                $shouldNotify = $mode === 'all' ||
                    ($mode === 'mentions' && $memberName !== '' && stripos($message, '@' . $memberName) !== false);
                if ($shouldNotify) {
                    $notificationStmt->execute([
                        (int) $recipient['student_id'],
                        'New message in ' . $activeGroup['name'],
                        $notificationMessage,
                        $notificationLink,
                    ]);
                }
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

        $savedMessage = [
            'id' => $messageId,
            'user_id' => $userId,
            'message' => $message,
            'message_type' => 'message',
            'created_at' => date('Y-m-d H:i:s'),
            'sender_name' => $senderName,
            'reply_to' => $replyDetails,
            'attachment' => $storedAttachmentName === null ? null : [
                'url' => 'student-group-chat-file.php?id=' . $messageId,
                'name' => $attachmentName,
                'mime' => $attachmentMime,
                'size' => $attachmentSize,
            ],
        ];

        studentGroupChatResponse(201, [
            'success' => true,
            'message' => $savedMessage,
        ]);
    }

    studentGroupChatResponse(400, ['success' => false, 'message' => 'Unknown group action.']);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Student group chat request failed: ' . $e->getMessage());
    $message = $e->getCode() === '42S02'
        ? 'Group chat setup is incomplete. Run setup_student_features.php once, then refresh this page.'
        : 'The group service is temporarily unavailable. Please try again.';
    studentGroupChatResponse(500, ['success' => false, 'message' => $message]);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Unexpected student group chat error: ' . $e->getMessage());
    studentGroupChatResponse(500, ['success' => false, 'message' => 'The group service is temporarily unavailable. Please try again.']);
}
