/*
  Bluestone Overseas Consultants - Database Table Definitions
  Target Database: u287260207_overseas_newdb
  Target Host: auth-db1278.hstgr.io
  
  Instructions:
  You can import or paste this script inside the SQL tab of phpMyAdmin in your Hostinger panel.
*/

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Create contact_inquiries Table
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

-- 2. Create bgoi_enquiries Table
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

-- 3. Create leads Table
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
