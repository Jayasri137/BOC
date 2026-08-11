<?php
// create_tables.php - Database installer for Bluestone Overseas production database
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/config.php'; // loads includes/db.php which has production credentials

$status = [];

// Define Table creation SQLs (Safe, non-destructive using CREATE TABLE IF NOT EXISTS)
// Note: We omit the foreign key constraint on users table to ensure successful creation even if 'users' table is created later.
$queries = [
    'contact_inquiries' => "
        CREATE TABLE IF NOT EXISTS `contact_inquiries` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `name` VARCHAR(255) DEFAULT NULL,
          `email` VARCHAR(255) DEFAULT NULL,
          `phone` VARCHAR(20) DEFAULT NULL,
          `business_focus` TEXT DEFAULT NULL,
          `message` TEXT DEFAULT NULL,
          `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",
    'bgoi_enquiries' => "
        CREATE TABLE IF NOT EXISTS `bgoi_enquiries` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `lead_code` VARCHAR(50) DEFAULT NULL,
          `user_id` INT(11) DEFAULT NULL,
          `enquiry_for` VARCHAR(100) DEFAULT NULL,
          `candidate_name` VARCHAR(255) DEFAULT NULL,
          `full_name` VARCHAR(255) DEFAULT NULL,
          `service_type` VARCHAR(100) DEFAULT NULL,
          `phone` VARCHAR(20) DEFAULT NULL,
          `email` VARCHAR(100) DEFAULT NULL,
          `remarks` TEXT DEFAULT NULL,
          `budget` VARCHAR(50) DEFAULT NULL,
          `details` LONGTEXT DEFAULT NULL,
          `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",
    'leads' => "
        CREATE TABLE IF NOT EXISTS `leads` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `student_name` VARCHAR(255) NOT NULL,
          `email` VARCHAR(255) DEFAULT NULL,
          `phone` VARCHAR(20) NOT NULL,
          `domain` VARCHAR(100) NOT NULL,
          `source` VARCHAR(100) DEFAULT NULL,
          `status` ENUM('New','Follow Up','Waiting for Confirmation','Enrolled','Closed','Invalid','Dropped') DEFAULT 'New',
          `assigned_to` INT(11) DEFAULT NULL,
          `created_at` DATETIME DEFAULT NULL,
          `assigned_to_name` VARCHAR(255) DEFAULT NULL,
          `assigned_by` INT(11) DEFAULT NULL,
          `interested_in` VARCHAR(255) DEFAULT NULL,
          `remarks` TEXT DEFAULT NULL,
          `deleted_remarks` TEXT DEFAULT NULL,
          `assigned_by_name` VARCHAR(255) DEFAULT NULL,
          `payment_status` VARCHAR(50) DEFAULT 'Pending payment',
          `total_fees` DECIMAL(12,2) DEFAULT 0.00,
          `paid_amount` DECIMAL(12,2) DEFAULT 0.00,
          `invalid_reason` VARCHAR(255) DEFAULT NULL,
          `is_active` TINYINT(1) NOT NULL DEFAULT 1,
          `deleted_at` DATETIME DEFAULT NULL,
          `deleted_by` VARCHAR(255) DEFAULT NULL,
          `deletion_remark` TEXT DEFAULT NULL,
          `lead_code` VARCHAR(20) DEFAULT NULL,
          `category` VARCHAR(255) DEFAULT NULL,
          `app_user_id` INT(11) DEFAULT NULL,
          `source_type` VARCHAR(100) DEFAULT NULL,
          `company_name` VARCHAR(255) DEFAULT NULL,
          `extra_financials` TEXT DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `idx_leads_lead_code` (`lead_code`),
          KEY `assigned_to` (`assigned_to`),
          KEY `idx_dashboard_lookup` (`domain`,`status`,`created_at`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    "
];

// Execute table creations
foreach ($queries as $tableName => $sql) {
    try {
        $pdo->exec($sql);
        $status[$tableName] = [
            'success' => true,
            'message' => "Table '{$tableName}' created successfully (or already existed)!"
        ];
    } catch (PDOException $e) {
        $status[$tableName] = [
            'success' => false,
            'message' => "Failed to create table '{$tableName}': " . $e->getMessage()
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Table Installer | Bluestone Overseas</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #ef4444;
            --primary-dark: #dc2626;
            --bg: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --border: rgba(255, 255, 255, 0.1);
            --success: #10b981;
            --error: #f43f5e;
            --text: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at top, #1e1b4b 0%, var(--bg) 100%);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .container {
            width: 100%;
            max-width: 650px;
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), #ec4899);
        }

        .header {
            margin-bottom: 2.5rem;
        }

        .logo-icon {
            font-size: 3.5rem;
            color: var(--primary);
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #ffffff 50%, var(--text-muted) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 1rem;
        }

        .db-info {
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            font-size: 0.95rem;
        }

        .db-info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
        }

        .db-info-item strong {
            color: var(--text);
        }

        .table-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2.5rem;
            text-align: left;
        }

        .table-item {
            background: rgba(15, 23, 42, 0.3);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
        }

        .table-item:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.2);
            background: rgba(15, 23, 42, 0.5);
        }

        .table-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .table-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .table-name {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .status-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }

        .status-badge.success {
            background: rgba(16, 185, 129, 0.15);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-badge.error {
            background: rgba(244, 63, 94, 0.15);
            color: var(--error);
            border: 1px solid rgba(244, 63, 94, 0.3);
        }

        .notice {
            background: rgba(234, 179, 8, 0.1);
            border: 1px solid rgba(234, 179, 8, 0.2);
            color: #fde047;
            padding: 1rem 1.5rem;
            border-radius: 16px;
            font-size: 0.9rem;
            line-height: 1.5;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            text-align: left;
        }

        .notice i {
            margin-top: 0.15rem;
            font-size: 1.1rem;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }

        @media (max-width: 480px) {
            .container { padding: 1.5rem; }
            .db-info { flex-direction: column; gap: 0.5rem; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="logo-icon"><i class="fa-solid fa-database"></i></div>
        <h1>Database Installer</h1>
        <p class="subtitle">Setting up required tables for Bluestone Overseas</p>
    </div>

    <div class="db-info">
        <div class="db-info-item">
            <i class="fa-solid fa-server"></i>
            <span>Host: <strong><?php echo htmlspecialchars($host); ?></strong></span>
        </div>
        <div class="db-info-item">
            <i class="fa-solid fa-database"></i>
            <span>DB: <strong><?php echo htmlspecialchars($dbname); ?></strong></span>
        </div>
    </div>

    <div class="table-list">
        <?php foreach ($status as $tableName => $info): ?>
            <div class="table-item">
                <div class="table-meta">
                    <div class="table-icon">
                        <i class="fa-solid fa-table"></i>
                    </div>
                    <span class="table-name"><?php echo htmlspecialchars($tableName); ?></span>
                </div>
                <div class="status-badge <?php echo $info['success'] ? 'success' : 'error'; ?>">
                    <i class="fa-solid <?php echo $info['success'] ? 'fa-circle-check' : 'fa-circle-xmark'; ?>"></i>
                    <span><?php echo $info['success'] ? 'Success' : 'Failed'; ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="notice">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span>
            <strong>Security Warning:</strong> Once installation is complete, please delete the <strong>create_tables.php</strong> file from your web server to prevent unauthorized database modifications.
        </span>
    </div>
</div>

</body>
</html>
