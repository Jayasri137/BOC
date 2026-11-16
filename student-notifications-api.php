<?php
/**
 * Student Notifications API
 * 
 * GET:                     List notifications (with unread count)
 * POST action=mark_read:   Mark a specific notification as read
 * POST action=mark_all_read: Mark all notifications as read
 */
session_start();
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store, max-age=0');

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/crm_db.php';

function notifResponse(int $status, array $payload): void {
    http_response_code($status);
    echo json_encode($payload, JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

function syncCrmLeadNotifications(PDO $pdo, int $userId): void {
    try {
        if ((int) ($_SESSION['student_crm_notification_cursor_schema_version'] ?? 0) < 4) {
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS student_crm_notification_cursors (
                    student_user_id INT NOT NULL PRIMARY KEY,
                    crm_lead_id INT NOT NULL,
                    last_history_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
                    last_remarks LONGTEXT DEFAULT NULL,
                    last_lead_snapshot LONGTEXT DEFAULT NULL,
                    last_message_snapshot LONGTEXT DEFAULT NULL,
                    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
            foreach ([
                'last_remarks' => 'LONGTEXT DEFAULT NULL AFTER last_history_id',
                'last_lead_snapshot' => 'LONGTEXT DEFAULT NULL AFTER last_remarks',
                'last_message_snapshot' => 'LONGTEXT DEFAULT NULL AFTER last_lead_snapshot'
            ] as $columnName => $columnDefinition) {
                $columnsStmt = $pdo->query("SHOW COLUMNS FROM student_crm_notification_cursors LIKE " . $pdo->quote($columnName));
                if (!$columnsStmt->fetch(PDO::FETCH_ASSOC)) {
                    $pdo->exec("ALTER TABLE student_crm_notification_cursors ADD COLUMN {$columnName} {$columnDefinition}");
                }
            }
            $_SESSION['student_crm_notification_cursor_schema_version'] = 4;
        }

        $linkStmt = $pdo->prepare('SELECT crm_lead_id, linked_at FROM student_crm_links WHERE student_user_id = ? LIMIT 1');
        $linkStmt->execute([$userId]);
        $link = $linkStmt->fetch(PDO::FETCH_ASSOC);
        $leadId = (int) ($link['crm_lead_id'] ?? 0);
        if ($leadId < 1) {
            return;
        }

        $crmPdo = get_central_crm_pdo();
        $leadSnapshotStmt = $crmPdo->prepare("
            SELECT lead_code, status, primary_status, secondary_status, overseas_pipeline_status, assigned_to_name,
                   university_name, course_name, intake, remarks, passport_no, university_student_id
            FROM leads
            WHERE id = ? AND is_active = 1
            LIMIT 1
        ");
        $leadSnapshotStmt->execute([$leadId]);
        $leadSnapshot = $leadSnapshotStmt->fetch(PDO::FETCH_ASSOC);
        if (!$leadSnapshot) {
            return;
        }
        $currentLeadSnapshot = [];
        foreach ($leadSnapshot as $field => $value) {
            $currentLeadSnapshot[$field] = (string) ($value ?? '');
        }
        $currentRemarks = $currentLeadSnapshot['remarks'] ?? '';

        $currentMessageSnapshot = null;
        $latestMessageEditors = [];
        try {
            $messagesStmt = $crmPdo->prepare("
                SELECT id, subject, description, created_by, created_at, updated_at
                FROM lead_remarks_messages
                WHERE lead_id = ?
                ORDER BY id ASC
            ");
            $messagesStmt->execute([$leadId]);
            $currentMessages = $messagesStmt->fetchAll(PDO::FETCH_ASSOC);
            $currentMessageSnapshot = [];
            foreach ($currentMessages as $message) {
                $messageId = (string) $message['id'];
                $currentMessageSnapshot[$messageId] = [
                    'subject' => (string) ($message['subject'] ?? ''),
                    'description' => (string) ($message['description'] ?? ''),
                    'created_by' => (string) ($message['created_by'] ?? ''),
                    'created_at' => (string) ($message['created_at'] ?? ''),
                    'updated_at' => (string) ($message['updated_at'] ?? '')
                ];
            }
        } catch (PDOException $e) {
            error_log('CRM comment messages unavailable for notification sync: ' . $e->getMessage());
        }
        try {
            $messageEditorsStmt = $crmPdo->prepare("
                SELECT h.message_id, h.edited_by
                FROM lead_remarks_messages_history h
                INNER JOIN (
                    SELECT message_id, MAX(id) AS latest_id
                    FROM lead_remarks_messages_history
                    WHERE lead_id = ?
                    GROUP BY message_id
                ) latest ON latest.latest_id = h.id
            ");
            $messageEditorsStmt->execute([$leadId]);
            foreach ($messageEditorsStmt->fetchAll(PDO::FETCH_ASSOC) as $messageEditor) {
                $latestMessageEditors[(string) $messageEditor['message_id']] = (string) ($messageEditor['edited_by'] ?? '');
            }
        } catch (PDOException $e) {
            error_log('CRM comment edit authors unavailable: ' . $e->getMessage());
        }

        $pdo->beginTransaction();
        $cursorStmt = $pdo->prepare('SELECT crm_lead_id, last_history_id, last_remarks, last_lead_snapshot, last_message_snapshot, updated_at FROM student_crm_notification_cursors WHERE student_user_id = ? FOR UPDATE');
        $cursorStmt->execute([$userId]);
        $cursor = $cursorStmt->fetch(PDO::FETCH_ASSOC);

        if (!$cursor) {
            $cursorLeadId = null;
        } else {
            $cursorLeadId = (int) $cursor['crm_lead_id'];
        }
        $messageSnapshotNeedsBaseline = $cursorLeadId === $leadId &&
            $currentMessageSnapshot !== null &&
            !is_array(json_decode((string) ($cursor['last_message_snapshot'] ?? ''), true));

        if ($cursorLeadId !== $leadId) {
            $baselineHistoryId = 0;
            try {
                $baselineStmt = $crmPdo->prepare("
                    SELECT COALESCE(MAX(id), 0)
                    FROM lead_edit_history
                    WHERE lead_id = ? AND changed_at <= ?
                ");
                $baselineStmt->execute([$leadId, $link['linked_at']]);
                $baselineHistoryId = (int) $baselineStmt->fetchColumn();
            } catch (PDOException $e) {
                error_log('CRM lead history baseline unavailable: ' . $e->getMessage());
            }

            if ($cursorLeadId === null) {
                $saveCursorStmt = $pdo->prepare('INSERT INTO student_crm_notification_cursors (student_user_id, crm_lead_id, last_history_id, last_remarks, last_lead_snapshot, last_message_snapshot, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)');
            } else {
                $saveCursorStmt = $pdo->prepare('UPDATE student_crm_notification_cursors SET crm_lead_id = ?, last_history_id = ?, last_remarks = ?, last_lead_snapshot = ?, last_message_snapshot = ?, updated_at = ? WHERE student_user_id = ?');
            }
            $saveCursorStmt->execute(
                $cursorLeadId === null
                    ? [$userId, $leadId, $baselineHistoryId, $currentRemarks, json_encode($currentLeadSnapshot, JSON_INVALID_UTF8_SUBSTITUTE), $currentMessageSnapshot === null ? null : json_encode($currentMessageSnapshot, JSON_INVALID_UTF8_SUBSTITUTE), $link['linked_at']]
                    : [$leadId, $baselineHistoryId, $currentRemarks, json_encode($currentLeadSnapshot, JSON_INVALID_UTF8_SUBSTITUTE), $currentMessageSnapshot === null ? null : json_encode($currentMessageSnapshot, JSON_INVALID_UTF8_SUBSTITUTE), $link['linked_at'], $userId]
            );
            $cursor = [
                'crm_lead_id' => $leadId,
                'last_history_id' => $baselineHistoryId,
                'last_remarks' => $currentRemarks,
                'last_lead_snapshot' => json_encode($currentLeadSnapshot, JSON_INVALID_UTF8_SUBSTITUTE),
                'last_message_snapshot' => $currentMessageSnapshot === null ? null : json_encode($currentMessageSnapshot, JSON_INVALID_UTF8_SUBSTITUTE),
                'updated_at' => $link['linked_at']
            ];
        }

        $history = [];
        try {
            $historyStmt = $crmPdo->prepare("
                SELECT id, field_name, old_value, new_value, action_type, changed_by, changed_at
                FROM lead_edit_history
                WHERE lead_id = ? AND id > ?
                ORDER BY id ASC
                LIMIT 100
            ");
            $historyStmt->execute([$leadId, (int) $cursor['last_history_id']]);
            $history = $historyStmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('CRM lead history unavailable; using lead snapshot: ' . $e->getMessage());
        }

        $lastRemarks = $cursor['last_remarks'];

        $fieldLabels = [
            'lead_code' => 'application reference',
            'student_name' => 'candidate name',
            'last_name' => 'candidate name',
            'email' => 'email address',
            'phone' => 'phone number',
            'category' => 'candidate category',
            'status' => 'application status',
            'primary_status' => 'application stage',
            'secondary_status' => 'application details',
            'overseas_pipeline_status' => 'application progress',
            'university_name' => 'university',
            'course_name' => 'course',
            'intake' => 'intake',
            'university_student_id' => 'university student ID',
            'passport_no' => 'passport details',
            'assigned_to_name' => 'counselor assignment',
            'remarks' => 'application notes',
            'franchise_comments' => 'application notes',
            'college_contacts' => 'university contact details',
            'candidate_photo' => 'profile photo',
            'documents' => 'documents',
            'interested_in' => 'study interests',
            'payment_status' => 'payment status',
            'total_fees' => 'application fees',
            'paid_amount' => 'payment details'
        ];
        $formatHistoryValue = static function ($value): string {
            $value = trim((string) ($value ?? ''));
            return $value === '' ? '(empty)' : $value;
        };
        $insertNotificationStmt = $pdo->prepare("
            INSERT INTO notifications (user_id, title, message, type, category, link)
            VALUES (?, ?, ?, 'info', 'application', 'student-dashboard.php?tab=application')
        ");

        $notificationGroups = [];
        $latestHistoryValues = [];
        foreach ($history as $entry) {
            $groupKey = implode('|', [
                (string) ($entry['action_type'] ?? ''),
                (string) ($entry['changed_by'] ?? ''),
                (string) ($entry['changed_at'] ?? '')
            ]);
            $fieldName = (string) ($entry['field_name'] ?? '');
            $label = $fieldLabels[$fieldName] ?? ucfirst(str_replace('_', ' ', $fieldName ?: 'application details'));
            $oldValue = $formatHistoryValue($entry['old_value'] ?? '');
            $newValue = $formatHistoryValue($entry['new_value'] ?? '');
            $latestHistoryValues[$fieldName] = trim((string) ($entry['new_value'] ?? ''));
            if (!isset($notificationGroups[$groupKey]['changes'][$label])) {
                $notificationGroups[$groupKey]['changes'][$label] = [
                    'old' => $oldValue,
                    'new' => $newValue
                ];
            } else {
                $notificationGroups[$groupKey]['changes'][$label]['new'] = $newValue;
            }
            $notificationGroups[$groupKey]['last_id'] = (int) $entry['id'];
        }

        $previousLeadSnapshot = json_decode((string) ($cursor['last_lead_snapshot'] ?? ''), true);
        if (!is_array($previousLeadSnapshot)) {
            $previousLeadSnapshot = [];
            if ($lastRemarks !== null) {
                $previousLeadSnapshot['remarks'] = (string) $lastRemarks;
            }
        }
        $snapshotChanges = [];
        foreach ($currentLeadSnapshot as $field => $currentValue) {
            if (!array_key_exists($field, $previousLeadSnapshot)) {
                continue;
            }
            $previousValue = (string) ($previousLeadSnapshot[$field] ?? '');
            if ($previousValue === $currentValue) {
                continue;
            }
            if (isset($latestHistoryValues[$field]) && $latestHistoryValues[$field] === $currentValue) {
                continue;
            }
            $label = $fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field));
            $snapshotChanges[$label] = [
                'old' => $formatHistoryValue($previousValue),
                'new' => $formatHistoryValue($currentValue)
            ];
        }
        if ($snapshotChanges) {
            $notificationGroups['lead_snapshot_' . $leadId . '_' . $userId] = ['changes' => $snapshotChanges];
        }

        $previousMessageSnapshot = json_decode((string) ($cursor['last_message_snapshot'] ?? ''), true);
        if (!is_array($previousMessageSnapshot)) {
            $previousMessageSnapshot = [];
        }
        if (is_array($currentMessageSnapshot) && !$messageSnapshotNeedsBaseline) {
            foreach ($currentMessageSnapshot as $messageId => $message) {
                $previousMessage = $previousMessageSnapshot[$messageId] ?? null;
                $oldSubject = (string) ($previousMessage['subject'] ?? '');
                $oldDescription = (string) ($previousMessage['description'] ?? '');
                $newSubject = (string) ($message['subject'] ?? '');
                $newDescription = (string) ($message['description'] ?? '');
                $isNewMessage = $previousMessage === null;
                $isEditedMessage = !$isNewMessage &&
                    ($oldSubject !== $newSubject || $oldDescription !== $newDescription);
                if (!$isNewMessage && !$isEditedMessage) {
                    continue;
                }

                $messageText = static function (string $subject, string $description): string {
                    $parts = array_values(array_filter(
                        [trim($subject), trim($description)],
                        static fn(string $part): bool => $part !== ''
                    ));
                    return $parts ? implode("\n", $parts) : '(empty)';
                };
                $messageLines = [];
                if ($isNewMessage) {
                    $messageLines[] = 'New CRM comment added';
                    $messageLines[] = 'Comment: ' . $messageText($newSubject, $newDescription);
                } else {
                    $messageLines[] = 'CRM comment updated';
                    $messageLines[] = 'Previous: ' . $messageText($oldSubject, $oldDescription);
                    $messageLines[] = 'Updated: ' . $messageText($newSubject, $newDescription);
                }
                $changedBy = $latestMessageEditors[$messageId] ?? ($message['created_by'] ?? '');
                if ($changedBy !== '') {
                    $messageLines[] = 'Updated by: ' . $changedBy;
                }
                $insertNotificationStmt->execute([
                    $userId,
                    $isNewMessage ? 'New CRM comment' : 'CRM comment updated',
                    implode("\n", $messageLines)
                ]);
            }

            foreach ($previousMessageSnapshot as $messageId => $previousMessage) {
                if (isset($currentMessageSnapshot[$messageId])) {
                    continue;
                }
                $removedMessage = [ (string) ($previousMessage['subject'] ?? ''), (string) ($previousMessage['description'] ?? '') ];
                $removedMessage = array_values(array_filter(
                    array_map('trim', $removedMessage),
                    static fn(string $part): bool => $part !== ''
                ));
                $insertNotificationStmt->execute([
                    $userId,
                    'CRM comment removed',
                    "A CRM comment was removed:\n" . ($removedMessage ? implode("\n", $removedMessage) : '(empty)')
                ]);
            }
        }

        foreach ($notificationGroups as $group) {
            if (empty($group['changes'])) {
                continue;
            }
            $details = [];
            foreach ($group['changes'] as $label => $change) {
                $details[] = $label . ': ' . $change['old'] . ' → ' . $change['new'];
            }
            $title = count($details) === 1 ? ucfirst(strtok($details[0], ':')) . ' updated' : 'Application updated';
            $insertNotificationStmt->execute([$userId, $title, implode("\n", $details)]);
        }

        $lastHistoryId = $history ? (int) $history[count($history) - 1]['id'] : (int) $cursor['last_history_id'];
        $updateCursorStmt = $pdo->prepare('UPDATE student_crm_notification_cursors SET last_history_id = ?, last_remarks = ?, last_lead_snapshot = ?, last_message_snapshot = COALESCE(?, last_message_snapshot) WHERE student_user_id = ?');
        $updateCursorStmt->execute([
            $lastHistoryId,
            $currentRemarks,
            json_encode($currentLeadSnapshot, JSON_INVALID_UTF8_SUBSTITUTE),
            $currentMessageSnapshot === null ? null : json_encode($currentMessageSnapshot, JSON_INVALID_UTF8_SUBSTITUTE),
            $userId
        ]);
        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('CRM student notification sync failed: ' . $e->getMessage());
        throw $e;
    }
}

// Auth check
if (empty($_SESSION['user_id'])) {
    notifResponse(401, ['success' => false, 'message' => 'Please sign in.']);
}

$userId = (int) $_SESSION['user_id'];

// ─── GET: List notifications ───
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $limit = min(50, max(1, (int) ($_GET['limit'] ?? 20)));
    $offset = max(0, (int) ($_GET['offset'] ?? 0));

    try {
        $notificationSyncFailed = false;
        try {
            syncCrmLeadNotifications($pdo, $userId);
        } catch (Throwable $syncError) {
            $notificationSyncFailed = true;
            error_log('CRM notification sync skipped; continuing application refresh: ' . $syncError->getMessage());
        }

        $application = null;
        $remarkHistory = [];
        $linkStmt = $pdo->prepare('SELECT crm_lead_id FROM student_crm_links WHERE student_user_id = ? LIMIT 1');
        $linkStmt->execute([$userId]);
        $crmLeadId = (int) $linkStmt->fetchColumn();
        if ($crmLeadId > 0) {
            $crmPdo = get_central_crm_pdo();
            $applicationStmt = $crmPdo->prepare("
                SELECT lead_code, status, primary_status, secondary_status, overseas_pipeline_status, assigned_to_name,
                       university_name, course_name, intake, remarks, updated_at, passport_no, university_student_id
                FROM leads
                WHERE id = ? AND is_active = 1
                LIMIT 1
            ");
            $applicationStmt->execute([$crmLeadId]);
            $application = $applicationStmt->fetch(PDO::FETCH_ASSOC) ?: null;
            if ($application) {
                unset($application['updated_at']);

                try {
                    $currentMessagesStmt = $crmPdo->prepare("
                        SELECT id AS message_id, subject, description, created_by AS changed_by,
                               COALESCE(updated_at, created_at) AS changed_at, 'current' AS version_type
                        FROM lead_remarks_messages
                        WHERE lead_id = ?
                        ORDER BY COALESCE(updated_at, created_at) DESC, id DESC
                    ");
                    $currentMessagesStmt->execute([$crmLeadId]);
                    $currentMessages = $currentMessagesStmt->fetchAll(PDO::FETCH_ASSOC);
                    $remarkHistory = $currentMessages;

                    $messageHistoryStmt = $crmPdo->prepare("
                        SELECT message_id, subject, description, edited_by AS changed_by,
                               edited_at AS changed_at, 'previous' AS version_type
                        FROM lead_remarks_messages_history
                        WHERE lead_id = ?
                        ORDER BY edited_at DESC, id DESC
                    ");
                    $messageHistoryStmt->execute([$crmLeadId]);
                    $previousMessages = $messageHistoryStmt->fetchAll(PDO::FETCH_ASSOC);

                    $remarkHistory = array_merge($currentMessages, $previousMessages);
                    usort($remarkHistory, static function (array $a, array $b): int {
                        $timeOrder = strtotime((string) $b['changed_at']) <=> strtotime((string) $a['changed_at']);
                        if ($timeOrder !== 0) {
                            return $timeOrder;
                        }
                        return ($a['version_type'] === 'current' ? 0 : 1) <=> ($b['version_type'] === 'current' ? 0 : 1);
                    });

                    if (!empty($currentMessages[0])) {
                        $latestMessage = $currentMessages[0];
                        $latestRemarkParts = array_filter([
                            trim((string) ($latestMessage['subject'] ?? '')),
                            trim((string) ($latestMessage['description'] ?? ''))
                        ], static fn(string $part): bool => $part !== '');
                        if ($latestRemarkParts) {
                            $application['remarks'] = implode("\n", $latestRemarkParts);
                        }
                    }
                } catch (PDOException $remarksError) {
                    error_log('CRM remark message history unavailable: ' . $remarksError->getMessage());
                }
            }
        }

        $legacyNotificationStmt = $pdo->prepare("
            DELETE FROM notifications
            WHERE user_id = ?
              AND category = 'application'
              AND title = 'Application updated'
              AND message = 'Your CRM application details were updated. View Application Status for the latest information.'
        ");
        $legacyNotificationStmt->execute([$userId]);

        $groupNotificationLinksStmt = $pdo->prepare("
            UPDATE notifications n
            INNER JOIN community_groups g
                ON n.title = CONCAT('New message in ', g.name)
                OR n.title = CONCAT('New member in ', g.name)
            SET n.link = CONCAT(
                'student-dashboard.php?tab=community&community_tab=groups&group_id=',
                g.id
            )
            WHERE n.user_id = ?
              AND n.category = 'community'
              AND n.link = 'student-dashboard.php?tab=community'
        ");
        $groupNotificationLinksStmt->execute([$userId]);

        // Get unread count
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
        $countStmt->execute([$userId]);
        $unreadCount = (int) $countStmt->fetchColumn();

        // Get notifications
        $stmt = $pdo->prepare("
            SELECT id, title, message, type, category, link, is_read, created_at,
                   GREATEST(0, TIMESTAMPDIFF(SECOND, created_at, CURRENT_TIMESTAMP)) AS age_seconds
            FROM notifications
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$userId, $limit, $offset]);
        $notifications = $stmt->fetchAll();

        // Format dates
        foreach ($notifications as &$n) {
            $ts = strtotime($n['created_at']);
            $diff = (int) $n['age_seconds'];
            unset($n['age_seconds']);
            if ($diff < 60) {
                $n['time_ago'] = 'Just now';
            } elseif ($diff < 3600) {
                $n['time_ago'] = floor($diff / 60) . 'm ago';
            } elseif ($diff < 86400) {
                $n['time_ago'] = floor($diff / 3600) . 'h ago';
            } elseif ($diff < 604800) {
                $n['time_ago'] = floor($diff / 86400) . 'd ago';
            } else {
                $n['time_ago'] = date('M j', $ts);
            }
        }
        unset($n);

        notifResponse(200, [
            'success' => true,
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
            'notification_sync_failed' => $notificationSyncFailed,
            'remark_history' => $remarkHistory,
            'application' => $application
        ]);
    } catch (Throwable $e) {
        error_log('Notifications fetch failed: ' . $e->getMessage());
        notifResponse(500, ['success' => false, 'message' => 'Unable to load notifications.']);
    }
}

// POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    notifResponse(405, ['success' => false, 'message' => 'Method not allowed.']);
}

$action = $_POST['action'] ?? '';

// ─── Mark single notification as read ───
if ($action === 'mark_read') {
    $notifId = filter_var($_POST['notification_id'] ?? null, FILTER_VALIDATE_INT);
    if (!$notifId || $notifId < 1) {
        notifResponse(422, ['success' => false, 'message' => 'Invalid notification ID.']);
    }

    try {
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
        $stmt->execute([$notifId, $userId]);
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
        $countStmt->execute([$userId]);
        notifResponse(200, ['success' => true, 'unread_count' => (int) $countStmt->fetchColumn()]);
    } catch (PDOException $e) {
        error_log('Mark notification read failed: ' . $e->getMessage());
        notifResponse(500, ['success' => false, 'message' => 'Unable to update notification.']);
    }
}

// ─── Mark all as read ───
if ($action === 'mark_all_read') {
    try {
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
        $stmt->execute([$userId]);
        notifResponse(200, ['success' => true, 'unread_count' => 0, 'message' => 'All notifications marked as read.']);
    } catch (PDOException $e) {
        error_log('Mark all read failed: ' . $e->getMessage());
        notifResponse(500, ['success' => false, 'message' => 'Unable to update notifications.']);
    }
}

notifResponse(422, ['success' => false, 'message' => 'Invalid action.']);
