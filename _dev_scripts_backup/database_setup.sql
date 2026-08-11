-- ============================================================
-- Bluestone Overseas - Complete Database Setup
-- Database: u287260207_overseas_newdb
-- Import this file via Hostinger phpMyAdmin > Import tab
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- TABLE: contact_inquiries
-- ============================================================
CREATE TABLE IF NOT EXISTS `contact_inquiries` (
    `id`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(150) NOT NULL,
    `email`      VARCHAR(150) NOT NULL,
    `phone`      VARCHAR(20) DEFAULT NULL,
    `subject`    VARCHAR(255) DEFAULT NULL,
    `message`    TEXT DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: bgoi_enquiries
-- ============================================================
CREATE TABLE IF NOT EXISTS `bgoi_enquiries` (
    `id`            INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_name`  VARCHAR(150) NOT NULL,
    `email`         VARCHAR(150) NOT NULL,
    `phone`         VARCHAR(20) NOT NULL,
    `interested_in` VARCHAR(255) DEFAULT NULL,
    `message`       TEXT DEFAULT NULL,
    `created_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: leads  (Master CRM table)
-- ============================================================
CREATE TABLE IF NOT EXISTS `leads` (
    `id`             INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `lead_code`      VARCHAR(30) NOT NULL UNIQUE,
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
    PRIMARY KEY (`id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_source` (`source`),
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: admins
-- ============================================================
CREATE TABLE IF NOT EXISTS `admins` (
    `id`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `full_name`  VARCHAR(150) NOT NULL DEFAULT 'Administrator',
    `username`   VARCHAR(80) NOT NULL UNIQUE,
    `email`      VARCHAR(150) NOT NULL UNIQUE,
    `password`   VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default admin: username=admin  password=admin_password_123
INSERT IGNORE INTO `admins` (`full_name`, `username`, `email`, `password`) VALUES
('Administrator', 'admin', 'admin@bluestoneocs.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ============================================================
-- TABLE: services
-- ============================================================
CREATE TABLE IF NOT EXISTS `services` (
    `id`          INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `icon`        VARCHAR(80) NOT NULL DEFAULT 'fa-graduation-cap',
    `title`       VARCHAR(150) NOT NULL,
    `description` TEXT NOT NULL,
    `link`        VARCHAR(255) DEFAULT '#',
    `color`       VARCHAR(30) DEFAULT 'blue',
    `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `services` (`icon`, `title`, `description`, `link`, `color`, `is_active`) VALUES
('fa-user-graduate',    'Student Counselling',          'Personalized guidance to help students choose the right course and university based on their profile, budget, and career goals.',                    '#', 'blue',   1),
('fa-file-alt',         'Application Assistance',       'End-to-end support for preparing and submitting university applications, ensuring all documentation meets institutional requirements.',                '#', 'purple', 1),
('fa-passport',         'Visa Guidance',                'Expert visa counselling and application support to maximize your visa approval chances with the right documents and interview preparation.',         '#', 'orange', 1),
('fa-home',             'Pre-Departure Briefing',       'Comprehensive pre-departure orientation covering accommodation, banking, travel, and life abroad to ensure a smooth transition.',                    '#', 'teal',   1),
('fa-language',         'IELTS/TOEFL Coaching',         'Structured language test coaching programs with experienced trainers to help you achieve your target band scores for international admissions.',      '#', 'pink',   1),
('fa-university',       'University Selection',         'Strategic university shortlisting based on your academic profile, career aspirations, and financial budget for the best possible study outcomes.',   '#', 'gold',   1),
('fa-money-bill-wave',  'Scholarship Assistance',       'Identify and apply for scholarships, grants, and funding opportunities to ease your financial burden while studying abroad.',                        '#', 'blue',   1),
('fa-plane-departure',  'Travel & Accommodation',       'Assistance with flight bookings, airport pickups, and finding safe, affordable accommodation near your chosen institution.',                         '#', 'purple', 1);

-- ============================================================
-- TABLE: countries
-- ============================================================
CREATE TABLE IF NOT EXISTS `countries` (
    `id`          INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `slug`        VARCHAR(80) NOT NULL UNIQUE,
    `name`        VARCHAR(150) NOT NULL,
    `flag`        VARCHAR(20) NOT NULL DEFAULT '🌍',
    `description` TEXT DEFAULT NULL,
    `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `countries` (`slug`, `name`, `flag`, `description`, `is_active`) VALUES
('usa',            'United States',     '🇺🇸', 'Home to world-class Ivy League universities and cutting-edge research programs. Top destination for STEM, business, and liberal arts education.',                          1),
('uk',             'United Kingdom',    '🇬🇧', 'Oxford, Cambridge, and hundreds of globally ranked universities. Fast-track 1-year master programs with strong post-study work visa options.',                          1),
('canada',         'Canada',            '🇨🇦', 'Known for affordable tuition, high quality of life, and an excellent Post Graduate Work Permit (PGWP) leading to Permanent Residency pathways.',                      1),
('australia',      'Australia',         '🇦🇺', 'A top destination with universities ranked in global top 100, post-study work rights, and a multicultural, welcoming environment for international students.',         1),
('germany',        'Germany',           '🇩🇪', 'Tuition-free or low-cost public universities, strong engineering and technical programs, and a booming economy offering exceptional career prospects.',                 1),
('new-zealand',    'New Zealand',       '🇳🇿', 'Pristine natural beauty combined with high-quality education. Post-study work visas allow you to gain international work experience after graduation.',                 1),
('ireland',        'Ireland',           '🇮🇪','English-speaking EU country with strong tech sector ties to Google, Meta, and Apple. Excellent graduate employment rates and welcoming culture.',                       1),
('france',         'France',            '🇫🇷', 'World-renowned institutions like Sciences Po and HEC Paris, with affordable tuition at public universities and a vibrant cultural experience.',                        1),
('netherlands',    'Netherlands',       '🇳🇱', 'Progressive, internationally-oriented programs taught in English, with a strong tech and business ecosystem in Amsterdam and other major cities.',                     1),
('sweden',         'Sweden',            '🇸🇪', 'Innovative education system with a focus on sustainability, design, and technology. Known for work-life balance and excellent quality of life.',                        1),
('singapore',      'Singapore',         '🇸🇬', 'Gateway to Asia with world-class universities, a safe environment, and strong business connections across Southeast Asia and beyond.',                                  1),
('dubai',          'Dubai (UAE)',        '🇦🇪', 'Rapidly growing education hub in the Middle East with international branch campuses and strong networking opportunities in a tax-free economy.',                       1),
('japan',          'Japan',             '🇯🇵', 'Technologically advanced nation with world-class research facilities, rich cultural heritage, and generous government scholarships for international students.',        1),
('south-korea',    'South Korea',       '🇰🇷', 'K-culture powerhouse with globally competitive universities, strong scholarships, and excellent programs in technology, engineering, and business.',                   1),
('malaysia',       'Malaysia',          '🇲🇾', 'Affordable study destination with English-medium instruction, diverse culture, and branch campuses of renowned UK and Australian universities.',                       1),
('italy',          'Italy',             '🇮🇹', 'Prestigious institutions like Politecnico di Milano with strong programs in design, architecture, and engineering in a culturally rich environment.',                  1),
('switzerland',    'Switzerland',       '🇨🇭', 'Home to top-ranked business schools and hospitality institutes. A multilingual country offering education in English, French, German, and Italian.',                   1),
('spain',          'Spain',             '🇪🇸', 'Vibrant, affordable study destination with leading universities in Barcelona and Madrid. Strong programs in business, arts, and Mediterranean culture.',               1),
('china',          'China',             '🇨🇳', 'Fast-growing academic powerhouse with government scholarships, affordable living, and booming technology and business opportunities for global graduates.',             1),
('russia',         'Russia',            '🇷🇺', 'Affordable, high-quality education with strengths in medicine, engineering, and science. Government scholarship programs available for international students.',        1);

-- ============================================================
-- TABLE: testimonials
-- ============================================================
CREATE TABLE IF NOT EXISTS `testimonials` (
    `id`        INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`      VARCHAR(150) NOT NULL,
    `detail`    VARCHAR(255) NOT NULL,
    `initials`  VARCHAR(4) NOT NULL DEFAULT 'ST',
    `text`      TEXT NOT NULL,
    `stars`     TINYINT(1) NOT NULL DEFAULT 5,
    `color`     VARCHAR(30) DEFAULT 'blue',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `testimonials` (`name`, `detail`, `initials`, `text`, `stars`, `color`, `is_active`) VALUES
('Priya Sharma',      'MSc Data Science, UK',            'PS', 'Bluestone Overseas made my dream of studying in the UK a reality. Their counsellors guided me through every step — from shortlisting universities to visa approval. Highly recommend!',                                            5, 'blue',   1),
('Rahul Nair',        'MBA Finance, Canada',             'RN', 'I was confused about which university to choose in Canada. The team at Bluestone gave me honest, practical advice and helped me secure admission to a top business school with a partial scholarship!',                           5, 'purple', 1),
('Ananya Krishnan',   'Bachelor of Engineering, Germany','AK', 'Getting into a German university seemed overwhelming but Bluestone simplified everything. They helped with language requirements, document translation, and even pre-departure briefing. Outstanding service!',                  5, 'teal',   1),
('Mohammed Al Farsi', 'MS Computer Science, USA',        'MF', 'The IELTS coaching and university application support from Bluestone was exceptional. I received admits from 4 universities in the USA. Their team is dedicated and truly cares about student success.',                         5, 'orange', 1),
('Sneha Patel',       'Master of Nursing, Australia',    'SP', 'Bluestone handled everything from application to visa stamping. The counsellors are knowledgeable, patient, and always available. My Australian student visa was approved in under 3 weeks!',                                   5, 'pink',   1),
('Karthik Reddy',     'BSc Hospitality, Switzerland',    'KR', 'I never imagined studying in Switzerland but Bluestone made it possible. They identified the right scholarship for me and prepared me thoroughly for the interview. Life-changing experience — thank you!',                      5, 'gold',   1);

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- Setup complete! All 6 tables created and seeded.
-- Admin Login: username = admin | password = admin_password_123
-- IMPORTANT: Change the default admin password after first login!
-- ============================================================
