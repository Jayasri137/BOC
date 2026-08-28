<?php
require_once __DIR__ . '/includes/db.php';
$pdo->exec("CREATE TABLE IF NOT EXISTS `upcoming_batches` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `course_slug` VARCHAR(50) NOT NULL,
    `start_date` VARCHAR(100) NOT NULL,
    `batch_time` VARCHAR(100) NOT NULL,
    `batch_mode` VARCHAR(50) NOT NULL,
    `status` VARCHAR(50) NOT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
echo "Table created.\n";
