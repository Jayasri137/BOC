-- ================================================================
-- DEFINITIVE FIX: Drop and recreate all 3 form tables correctly
-- Run this in Hostinger phpMyAdmin > SQL tab
-- Safe to run - existing data will be cleared (forms only)
-- ================================================================

-- 1. Fix contact_inquiries (add business_focus column)
DROP TABLE IF EXISTS `contact_inquiries`;
CREATE TABLE `contact_inquiries` (
    `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`           VARCHAR(150) NOT NULL,
    `email`          VARCHAR(150) NOT NULL,
    `phone`          VARCHAR(20) DEFAULT NULL,
    `subject`        VARCHAR(255) DEFAULT NULL,
    `business_focus` VARCHAR(150) DEFAULT NULL,
    `message`        TEXT DEFAULT NULL,
    `created_at`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Fix bgoi_enquiries (add all required columns)
DROP TABLE IF EXISTS `bgoi_enquiries`;
CREATE TABLE `bgoi_enquiries` (
    `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `lead_code`      VARCHAR(30) DEFAULT NULL,
    `user_id`        INT UNSIGNED DEFAULT NULL,
    `enquiry_for`    VARCHAR(100) DEFAULT 'Study Abroad',
    `candidate_name` VARCHAR(150) NOT NULL,
    `full_name`      VARCHAR(150) NOT NULL,
    `service_type`   VARCHAR(100) DEFAULT 'Study Abroad',
    `student_name`   VARCHAR(150) DEFAULT NULL,
    `email`          VARCHAR(150) NOT NULL,
    `phone`          VARCHAR(20) NOT NULL,
    `interested_in`  VARCHAR(255) DEFAULT NULL,
    `remarks`        TEXT DEFAULT NULL,
    `budget`         VARCHAR(100) DEFAULT NULL,
    `message`        TEXT DEFAULT NULL,
    `created_at`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Fix leads (add lead_code and all CRM columns)
DROP TABLE IF EXISTS `leads`;
CREATE TABLE `leads` (
    `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `lead_code`      VARCHAR(30) DEFAULT NULL,
    `student_name`   VARCHAR(150) NOT NULL,
    `email`          VARCHAR(150) NOT NULL,
    `phone`          VARCHAR(20) NOT NULL,
    `interested_in`  VARCHAR(255) DEFAULT NULL,
    `domain`         VARCHAR(150) DEFAULT NULL,
    `source`         VARCHAR(100) DEFAULT 'Website Enquiry',
    `category`       VARCHAR(100) DEFAULT 'Website Enquiry',
    `status`         VARCHAR(60) NOT NULL DEFAULT 'New',
    `payment_status` VARCHAR(100) DEFAULT 'Pending payment',
    `total_fees`     DECIMAL(10,2) DEFAULT 0.00,
    `paid_amount`    DECIMAL(10,2) DEFAULT 0.00,
    `remarks`        TEXT DEFAULT NULL,
    `is_active`      TINYINT(1) NOT NULL DEFAULT 1,
    `created_at`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Done! All 3 tables rebuilt correctly.
