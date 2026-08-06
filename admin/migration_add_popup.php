<?php
// admin/migration_add_popup.php - Migration to create site_popup table
require_once __DIR__ . '/../includes/config.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS `site_popup` (
        `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        `image_path` VARCHAR(255) NOT NULL,
        `link_url` VARCHAR(255) DEFAULT '',
        `is_active` TINYINT(1) NOT NULL DEFAULT 0,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    $pdo->exec($sql);
    
    // Check if the table is empty and insert a default row
    $stmt = $pdo->query("SELECT COUNT(*) FROM `site_popup`");
    $count = $stmt->fetchColumn();
    if ($count == 0) {
        $pdo->exec("INSERT INTO `site_popup` (`image_path`, `link_url`, `is_active`) VALUES ('', '', 0)");
    }
    
    echo "Table site_popup created successfully and seeded.\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
