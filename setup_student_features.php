<?php
/**
 * Database Migration: Student Portal Enhancement Tables
 * Run this script ONCE to create the required tables.
 * 
 * Tables created:
 *  1. student_documents  - Document upload & management
 *  2. student_shortlist  - Server-side shortlist persistence
 *  3. notifications      - Student notification system
 *  4. community_group_members - Student group membership
 *  5. community_group_messages - Student group chat messages
 *  6. password_resets    - Secure password reset tokens
 *  7. login_attempts    - Brute-force rate limiting
 *  8. student_community_settings - Student directory privacy preferences
 *  9. student_community_connections - Student connection requests and blocks
 * 10. student_community_reports - Student safety reports
 * 11. student_direct_messages - Private messages between connected students
 * 12. student_crm_notification_cursors - CRM update notification tracking
 */

require_once __DIR__ . '/includes/config.php';

$tables = [];

// 1. Student Documents
$tables['student_documents'] = "
CREATE TABLE IF NOT EXISTS student_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    doc_type VARCHAR(50) NOT NULL COMMENT 'passport, transcript, sop, lor, ielts_scorecard, pte_scorecard, offer_letter, visa_docs, other',
    doc_label VARCHAR(150) NOT NULL COMMENT 'User-friendly name',
    file_name VARCHAR(255) NOT NULL COMMENT 'Original file name',
    file_path VARCHAR(500) NOT NULL COMMENT 'Server storage path',
    file_size INT UNSIGNED NOT NULL DEFAULT 0,
    mime_type VARCHAR(100) NOT NULL DEFAULT '',
    status ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending',
    admin_remarks TEXT DEFAULT NULL,
    uploaded_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reviewed_at DATETIME DEFAULT NULL,
    reviewed_by VARCHAR(100) DEFAULT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    INDEX idx_user_id (user_id),
    INDEX idx_doc_type (doc_type),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// 2. Student Shortlist
$tables['student_shortlist'] = "
CREATE TABLE IF NOT EXISTS student_shortlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_name VARCHAR(300) NOT NULL,
    university_name VARCHAR(300) NOT NULL,
    country_name VARCHAR(100) DEFAULT NULL,
    duration VARCHAR(100) DEFAULT NULL,
    tuition_fee VARCHAR(100) DEFAULT NULL,
    intakes VARCHAR(200) DEFAULT NULL,
    flag VARCHAR(10) DEFAULT '🌍',
    rank_badge VARCHAR(50) DEFAULT '#Top 100 QS',
    min_ielts VARCHAR(50) DEFAULT '6.5',
    added_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_user_course (user_id, course_name(191), university_name(191)),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// 3. Notifications
$tables['notifications'] = "
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'action') NOT NULL DEFAULT 'info',
    category VARCHAR(50) NOT NULL DEFAULT 'general' COMMENT 'application, document, community, system, general',
    link VARCHAR(500) DEFAULT NULL COMMENT 'URL to navigate on click',
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_unread (user_id, is_read),
    INDEX idx_user_created (user_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// 4. Community group membership
$tables['community_group_members'] = "
CREATE TABLE IF NOT EXISTS community_group_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    student_id INT NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_group_student (group_id, student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// 5. Community group chat messages
$tables['community_group_messages'] = "
CREATE TABLE IF NOT EXISTS community_group_messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    message_type VARCHAR(32) NOT NULL DEFAULT 'message',
    attachment_path VARCHAR(100) DEFAULT NULL,
    attachment_name VARCHAR(255) DEFAULT NULL,
    attachment_mime VARCHAR(127) DEFAULT NULL,
    attachment_size INT UNSIGNED DEFAULT NULL,
    reply_to_id BIGINT UNSIGNED DEFAULT NULL,
    edited_at DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_group_message (group_id, id),
    INDEX idx_group_message_sender (group_id, user_id, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

$tables['student_group_message_reads'] = "
CREATE TABLE IF NOT EXISTS student_group_message_reads (
    user_id INT NOT NULL,
    group_id INT NOT NULL,
    last_read_message_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, group_id),
    INDEX idx_group_message_reads_group (group_id, last_read_message_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// 6. Student directory privacy; profiles are private until students opt in.
$tables['student_community_settings'] = "
CREATE TABLE IF NOT EXISTS student_community_settings (
    user_id INT NOT NULL PRIMARY KEY,
    discoverable TINYINT(1) NOT NULL DEFAULT 1,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// 7. Connection requests and blocks
$tables['student_community_connections'] = "
CREATE TABLE IF NOT EXISTS student_community_connections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_one INT NOT NULL,
    user_two INT NOT NULL,
    requester_id INT NOT NULL,
    recipient_id INT NOT NULL,
    status ENUM('pending', 'accepted', 'declined', 'blocked') NOT NULL DEFAULT 'pending',
    blocked_by INT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_student_connection_pair (user_one, user_two),
    INDEX idx_student_connection_recipient (recipient_id, status, updated_at),
    INDEX idx_student_connection_requester (requester_id, status, updated_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// 8. Safety reports
$tables['student_community_reports'] = "
CREATE TABLE IF NOT EXISTS student_community_reports (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reporter_id INT NOT NULL,
    reported_user_id INT NOT NULL,
    reason TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_community_report_target (reported_user_id, created_at),
    INDEX idx_community_reporter (reporter_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// 9. Private messages are available only between accepted connections.
$tables['student_direct_messages'] = "
CREATE TABLE IF NOT EXISTS student_direct_messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    message TEXT NOT NULL,
    message_type VARCHAR(32) NOT NULL DEFAULT 'message',
    attachment_path VARCHAR(100) DEFAULT NULL,
    attachment_name VARCHAR(255) DEFAULT NULL,
    attachment_mime VARCHAR(127) DEFAULT NULL,
    attachment_size INT UNSIGNED DEFAULT NULL,
    reply_to_id BIGINT UNSIGNED DEFAULT NULL,
    recipient_read_at DATETIME DEFAULT NULL,
    recipient_delivered_at DATETIME DEFAULT NULL,
    edited_at DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_direct_message_pair (sender_id, recipient_id, id),
    INDEX idx_direct_message_inbox (recipient_id, sender_id, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// 10. CRM notification sync cursor
$tables['student_crm_notification_cursors'] = "
CREATE TABLE IF NOT EXISTS student_crm_notification_cursors (
    student_user_id INT NOT NULL PRIMARY KEY,
    crm_lead_id INT NOT NULL,
    last_history_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
    last_remarks LONGTEXT DEFAULT NULL,
    last_lead_snapshot LONGTEXT DEFAULT NULL,
    last_message_snapshot LONGTEXT DEFAULT NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// 11. Password Resets
$tables['password_resets'] = "
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    used TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token (token),
    INDEX idx_email (email),
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// 12. Login Attempts (rate limiting)
$tables['login_attempts'] = "
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    email VARCHAR(255) NOT NULL DEFAULT '',
    attempted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ip_time (ip_address, attempted_at),
    INDEX idx_email_time (email, attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

echo "<h2>Student Portal Database Migration</h2>";
echo "<pre>";

$success = 0;
$errors = 0;

foreach ($tables as $tableName => $sql) {
    try {
        $pdo->exec($sql);
        echo "✅ Table '{$tableName}' created (or already exists).\n";
        $success++;
    } catch (PDOException $e) {
        echo "❌ Error creating '{$tableName}': " . $e->getMessage() . "\n";
        $errors++;
    }
}

try {
    foreach ([
        'message_type' => "VARCHAR(32) NOT NULL DEFAULT 'message' AFTER message",
        'attachment_path' => 'VARCHAR(100) DEFAULT NULL AFTER message_type',
        'attachment_name' => 'VARCHAR(255) DEFAULT NULL AFTER attachment_path',
        'attachment_mime' => 'VARCHAR(127) DEFAULT NULL AFTER attachment_name',
        'attachment_size' => 'INT UNSIGNED DEFAULT NULL AFTER attachment_mime',
        'reply_to_id' => 'BIGINT UNSIGNED DEFAULT NULL AFTER attachment_size',
    ] as $columnName => $definition) {
        $columnStmt = $pdo->query("SHOW COLUMNS FROM community_group_messages LIKE " . $pdo->quote($columnName));
        if (!$columnStmt->fetch(PDO::FETCH_ASSOC)) {
            $pdo->exec("ALTER TABLE community_group_messages ADD COLUMN {$columnName} {$definition}");
            echo "✅ Added group chat {$columnName} support.\n";
        }
    }
} catch (PDOException $e) {
    echo "❌ Error adding group chat attachment support: " . $e->getMessage() . "\n";
    $errors++;
}

try {
    foreach ([
        'message_type' => "VARCHAR(32) NOT NULL DEFAULT 'message' AFTER message",
        'attachment_path' => 'VARCHAR(100) DEFAULT NULL AFTER message_type',
        'attachment_name' => 'VARCHAR(255) DEFAULT NULL AFTER attachment_path',
        'attachment_mime' => 'VARCHAR(127) DEFAULT NULL AFTER attachment_name',
        'attachment_size' => 'INT UNSIGNED DEFAULT NULL AFTER attachment_mime',
        'reply_to_id' => 'BIGINT UNSIGNED DEFAULT NULL AFTER attachment_size',
    ] as $columnName => $definition) {
        $columnStmt = $pdo->query("SHOW COLUMNS FROM student_direct_messages LIKE " . $pdo->quote($columnName));
        if (!$columnStmt->fetch(PDO::FETCH_ASSOC)) {
            $pdo->exec("ALTER TABLE student_direct_messages ADD COLUMN {$columnName} {$definition}");
            echo "✅ Added personal message {$columnName} support.\n";
        }
    }
} catch (PDOException $e) {
    echo "❌ Error adding personal message support: " . $e->getMessage() . "\n";
    $errors++;
}

try {
    $readColumnStmt = $pdo->query("SHOW COLUMNS FROM student_direct_messages LIKE 'recipient_read_at'");
    if (!$readColumnStmt->fetch(PDO::FETCH_ASSOC)) {
        $pdo->exec('ALTER TABLE student_direct_messages ADD COLUMN recipient_read_at DATETIME DEFAULT NULL AFTER reply_to_id');
        $pdo->exec('UPDATE student_direct_messages SET recipient_read_at = created_at WHERE recipient_read_at IS NULL');
        echo "✅ Added personal message read status support.\n";
    }
    $deliveredColumnStmt = $pdo->query("SHOW COLUMNS FROM student_direct_messages LIKE 'recipient_delivered_at'");
    if (!$deliveredColumnStmt->fetch(PDO::FETCH_ASSOC)) {
        $pdo->exec('ALTER TABLE student_direct_messages ADD COLUMN recipient_delivered_at DATETIME DEFAULT NULL AFTER recipient_read_at');
        $pdo->exec('UPDATE student_direct_messages SET recipient_delivered_at = recipient_read_at WHERE recipient_read_at IS NOT NULL');
        echo "✅ Added personal message delivery status support.\n";
    }
    foreach ([
        'edited_at' => 'DATETIME DEFAULT NULL',
    ] as $columnName => $definition) {
        $columnStmt = $pdo->query("SHOW COLUMNS FROM student_direct_messages LIKE " . $pdo->quote($columnName));
        if (!$columnStmt->fetch(PDO::FETCH_ASSOC)) {
            $pdo->exec("ALTER TABLE student_direct_messages ADD COLUMN {$columnName} {$definition} AFTER recipient_delivered_at");
            echo "✅ Added personal message {$columnName} support.\n";
        }
    }
    foreach ([
        'edited_at' => 'DATETIME DEFAULT NULL',
    ] as $columnName => $definition) {
        $columnStmt = $pdo->query("SHOW COLUMNS FROM community_group_messages LIKE " . $pdo->quote($columnName));
        if (!$columnStmt->fetch(PDO::FETCH_ASSOC)) {
            $pdo->exec("ALTER TABLE community_group_messages ADD COLUMN {$columnName} {$definition}");
            echo "✅ Added group chat {$columnName} support.\n";
        }
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

    // Ensure student_documents has full synchronized schema columns
    foreach ([
        'doc_key' => 'VARCHAR(100) DEFAULT NULL',
        'doc_label' => 'VARCHAR(255) DEFAULT NULL',
        'category' => 'VARCHAR(100) DEFAULT NULL',
        'name' => 'VARCHAR(255) DEFAULT NULL',
        'file_url' => 'VARCHAR(500) DEFAULT NULL',
        'size' => 'VARCHAR(50) DEFAULT "1.5 MB"',
        'file_extension' => 'VARCHAR(20) DEFAULT "pdf"',
        'status' => 'VARCHAR(50) DEFAULT "Under Review"',
        'admin_notes' => 'TEXT DEFAULT NULL',
        'verified_by' => 'VARCHAR(150) DEFAULT NULL',
        'uploaded_by' => 'VARCHAR(150) DEFAULT NULL',
        'expiry_date' => 'DATE DEFAULT NULL',
        'updated_at' => 'DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
    ] as $colName => $colDef) {
        $colStmt = $pdo->query("SHOW COLUMNS FROM student_documents LIKE " . $pdo->quote($colName));
        if (!$colStmt->fetch(PDO::FETCH_ASSOC)) {
            $pdo->exec("ALTER TABLE student_documents ADD COLUMN {$colName} {$colDef}");
            echo "✅ Added student_documents {$colName} support.\n";
        }
    }
} catch (PDOException $e) {
    echo "❌ Error adding student chat feature support: " . $e->getMessage() . "\n";
    $errors++;
}

echo "\n--- Migration Complete ---\n";
echo "Success: {$success} | Errors: {$errors}\n";

// Create uploads directory for documents
$docUploadDir = __DIR__ . '/uploads/student_documents';
if (!is_dir($docUploadDir)) {
    if (mkdir($docUploadDir, 0755, true)) {
        echo "\n✅ Upload directory created: uploads/student_documents/\n";
    } else {
        echo "\n⚠️ Could not create upload directory. Please create 'uploads/student_documents/' manually.\n";
    }
} else {
    echo "\n✅ Upload directory already exists.\n";
}

$groupChatUploadDir = __DIR__ . '/uploads/group_chat';
if (!is_dir($groupChatUploadDir)) {
    if (mkdir($groupChatUploadDir, 0750, true)) {
        echo "\n✅ Group chat attachment directory created.\n";
    } else {
        echo "\n⚠️ Could not create the group chat attachment directory.\n";
    }
} else {
    echo "\n✅ Group chat attachment directory already exists.\n";
}

$directMessageUploadDir = __DIR__ . '/uploads/direct_messages';
if (!is_dir($directMessageUploadDir)) {
    if (mkdir($directMessageUploadDir, 0750, true)) {
        echo "\n✅ Personal message attachment directory created.\n";
    } else {
        echo "\n⚠️ Could not create the personal message attachment directory.\n";
    }
} else {
    echo "\n✅ Personal message attachment directory already exists.\n";
}

echo "</pre>";
echo "<p><strong>You can now safely delete this file (setup_student_features.php).</strong></p>";
