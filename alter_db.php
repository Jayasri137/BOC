<?php
require 'includes/config.php';

$columns = $pdo->query("SHOW COLUMNS FROM community_posts")->fetchAll(PDO::FETCH_COLUMN);
$migrations = [
    'avatar_url' => 'VARCHAR(255) DEFAULT NULL AFTER media_path',
    'author_name' => 'VARCHAR(150) DEFAULT NULL',
    'destination' => 'VARCHAR(150) DEFAULT NULL',
    'student_created' => 'TINYINT(1) NOT NULL DEFAULT 0',
];

foreach ($migrations as $column => $definition) {
    if (!in_array($column, $columns, true)) {
        $pdo->exec("ALTER TABLE community_posts ADD COLUMN `$column` $definition");
    }
}

$pdo->exec("
    CREATE TABLE IF NOT EXISTS student_crm_links (
        student_user_id INT(11) NOT NULL,
        crm_lead_id INT(11) NOT NULL,
        linked_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (student_user_id),
        UNIQUE KEY uq_student_crm_links_lead (crm_lead_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");

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

$pdo->exec("
    CREATE TABLE IF NOT EXISTS website_visit_daily (
        visit_date DATE NOT NULL,
        page_views BIGINT UNSIGNED NOT NULL DEFAULT 0,
        unique_visitors BIGINT UNSIGNED NOT NULL DEFAULT 0,
        PRIMARY KEY (visit_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS website_visit_visitors (
        visit_date DATE NOT NULL,
        visitor_hash CHAR(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
        PRIMARY KEY (visit_date, visitor_hash)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");

echo "Community, CRM mapping, and website visit tracking schemas are up to date.";
