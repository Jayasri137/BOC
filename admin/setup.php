<?php
// admin/setup.php - Database installer and seeder for Admin Panel
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../includes/config.php'; // loads database connection

$status = [];
$tables_created = [];

// Queries to create tables
$queries = [
    'admins' => "
        CREATE TABLE IF NOT EXISTS `admins` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `username` VARCHAR(50) NOT NULL UNIQUE,
          `password` VARCHAR(255) NOT NULL,
          `full_name` VARCHAR(100) NOT NULL,
          `email` VARCHAR(100) NOT NULL UNIQUE,
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",
    'services' => "
        CREATE TABLE IF NOT EXISTS `services` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `icon` VARCHAR(50) NOT NULL,
          `title` VARCHAR(100) NOT NULL,
          `description` TEXT NOT NULL,
          `link` VARCHAR(255) NOT NULL,
          `color` VARCHAR(20) NOT NULL,
          `is_active` TINYINT(1) DEFAULT 1,
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",
    'countries' => "
        CREATE TABLE IF NOT EXISTS `countries` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `slug` VARCHAR(50) NOT NULL UNIQUE,
          `name` VARCHAR(100) NOT NULL,
          `flag` VARCHAR(10) NOT NULL,
          `description` TEXT NOT NULL,
          `is_active` TINYINT(1) DEFAULT 1,
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",
    'testimonials' => "
        CREATE TABLE IF NOT EXISTS `testimonials` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `name` VARCHAR(100) NOT NULL,
          `detail` VARCHAR(100) NOT NULL,
          `initials` VARCHAR(10) NOT NULL,
          `text` TEXT NOT NULL,
          `stars` INT(11) DEFAULT 5,
          `color` VARCHAR(20) DEFAULT 'blue',
          `is_active` TINYINT(1) DEFAULT 1,
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",
    'hero_slides' => "
        CREATE TABLE IF NOT EXISTS `hero_slides` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `badge` VARCHAR(100) DEFAULT NULL,
          `title` VARCHAR(255) NOT NULL,
          `description` TEXT DEFAULT NULL,
          `button_text` VARCHAR(100) NOT NULL DEFAULT 'Get Started',
          `image_path` VARCHAR(255) NOT NULL,
          `is_active` TINYINT(1) DEFAULT 1,
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",
    'test_preps' => "
        CREATE TABLE IF NOT EXISTS `test_preps` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `slug` VARCHAR(50) NOT NULL UNIQUE,
          `name` VARCHAR(100) NOT NULL,
          `icon` VARCHAR(50) NOT NULL DEFAULT 'fa-pen-to-square',
          `description` TEXT NOT NULL,
          `feature1` VARCHAR(150) DEFAULT NULL,
          `feature2` VARCHAR(150) DEFAULT NULL,
          `feature3` VARCHAR(150) DEFAULT NULL,
          `feature4` VARCHAR(150) DEFAULT NULL,
          `color` VARCHAR(20) NOT NULL DEFAULT 'blue',
          `is_active` TINYINT(1) DEFAULT 1,
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",
    'gallery_items' => "
        CREATE TABLE IF NOT EXISTS `gallery_items` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `image_path` VARCHAR(255) NOT NULL,
          `title` VARCHAR(150) NOT NULL,
          `category` VARCHAR(100) NOT NULL DEFAULT 'Events',
          `is_active` TINYINT(1) DEFAULT 1,
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",
    'specialist_services' => "
        CREATE TABLE IF NOT EXISTS `specialist_services` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `title` VARCHAR(150) NOT NULL,
          `category_tag` VARCHAR(100) NOT NULL,
          `icon` VARCHAR(50) NOT NULL DEFAULT 'fa-briefcase',
          `description` TEXT NOT NULL,
          `bullet1` VARCHAR(150) DEFAULT NULL,
          `bullet2` VARCHAR(150) DEFAULT NULL,
          `bullet3` VARCHAR(150) DEFAULT NULL,
          `button_text` VARCHAR(100) NOT NULL DEFAULT 'Explore Details',
          `button_link` VARCHAR(255) NOT NULL DEFAULT 'contact.php',
          `color` VARCHAR(20) NOT NULL DEFAULT 'blue',
          `is_active` TINYINT(1) DEFAULT 1,
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",
    'branches' => "
        CREATE TABLE IF NOT EXISTS `branches` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `city` VARCHAR(100) NOT NULL,
          `icon` VARCHAR(50) NOT NULL DEFAULT 'fa-location-dot',
          `badge` VARCHAR(50) DEFAULT NULL,
          `address` TEXT NOT NULL,
          `is_active` TINYINT(1) DEFAULT 1,
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",
    'events' => "
        CREATE TABLE IF NOT EXISTS `events` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `title` VARCHAR(150) NOT NULL,
          `date_string` VARCHAR(50) NOT NULL,
          `location` VARCHAR(150) NOT NULL,
          `description` TEXT NOT NULL,
          `is_active` TINYINT(1) DEFAULT 1,
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",
    'news_articles' => "
        CREATE TABLE IF NOT EXISTS `news_articles` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `title` VARCHAR(150) NOT NULL,
          `date_string` VARCHAR(50) NOT NULL,
          `tag` VARCHAR(100) NOT NULL,
          `excerpt` TEXT NOT NULL,
          `emoji` VARCHAR(10) NOT NULL DEFAULT '📚',
          `link` VARCHAR(255) NOT NULL,
          `is_active` TINYINT(1) DEFAULT 1,
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",
    'testimonial_videos' => "
        CREATE TABLE IF NOT EXISTS `testimonial_videos` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `student_name` VARCHAR(150) NOT NULL,
          `details` VARCHAR(150) NOT NULL,
          `youtube_url` VARCHAR(255) NOT NULL,
          `is_active` TINYINT(1) DEFAULT 1,
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    "
];

// 1. Create Tables
foreach ($queries as $tableName => $sql) {
    try {
        $pdo->exec($sql);
        $status[$tableName] = [
            'success' => true,
            'message' => "Table '{$tableName}' verified/created successfully!"
        ];
        $tables_created[] = $tableName;
    } catch (PDOException $e) {
        $status[$tableName] = [
            'success' => false,
            'message' => "Failed to build table '{$tableName}': " . $e->getMessage()
        ];
    }
}

// 2. Seed default admin if empty
if (in_array('admins', $tables_created)) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM admins");
        if ($stmt->fetchColumn() == 0) {
            $default_user = 'admin';
            $default_pass = 'admin_password_123';
            $hash = password_hash($default_pass, PASSWORD_DEFAULT);
            $fullName = 'Super Administrator';
            $email = 'admin@bluestoneocs.com';
            
            $insert = $pdo->prepare("INSERT INTO admins (username, password, full_name, email) VALUES (:username, :password, :full_name, :email)");
            $insert->execute([
                'username' => $default_user,
                'password' => $hash,
                'full_name' => $fullName,
                'email' => $email
            ]);
            
            $status['admin_seeding'] = [
                'success' => true,
                'message' => "Default admin created! Username: <strong>$default_user</strong>, Password: <strong>$default_pass</strong>"
            ];
        } else {
            $status['admin_seeding'] = [
                'success' => true,
                'message' => "Admins table already populated. Default user creation skipped."
            ];
        }
    } catch (PDOException $e) {
        $status['admin_seeding'] = [
            'success' => false,
            'message' => "Admin seeding failed: " . $e->getMessage()
        ];
    }
}

// 3. Seed Services if empty
if (in_array('services', $tables_created)) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM services");
        if ($stmt->fetchColumn() == 0) {
            $services = [
                ['fa-user-graduate','Student Counselling','Personalised guidance to help you choose the right course and institution matching your academic goals and budget.','services.php?s=counselling','blue'],
                ['fa-university','University Selection','We help identify the best-fit universities across 20+ countries based on your profile and aspirations.','services.php?s=university','purple'],
                ['fa-file-contract','Admission Processing','Expert application management ensuring all documents are accurate, complete and submitted on time.','services.php?s=admission','orange'],
                ['fa-hand-holding-dollar','Financial Assistance','Guidance on scholarships, student loans and funding options to make your dream affordable.','services.php?s=financial','teal'],
                ['fa-passport','Visa Processing','End-to-end visa assistance with a 98% success rate, navigating complex immigration requirements.','services.php?s=visa','pink'],
                ['fa-house','Accommodation & Travel','We help arrange housing and travel plans so you arrive and settle comfortably in your new country.','services.php?s=accommodation','gold'],
                ['fa-pen-to-square','Test Preparation','Specialised coaching for IELTS, TOEFL and PTE to achieve the scores required by top universities.','test-prep.php','blue'],
                ['fa-briefcase','Part-Time Job Help','Guidance on finding legal part-time work opportunities abroad to support yourself financially.','services.php?s=jobs','purple']
            ];
            
            $insert = $pdo->prepare("INSERT INTO services (icon, title, description, link, color, is_active) VALUES (?, ?, ?, ?, ?, 1)");
            foreach ($services as $srv) {
                $insert->execute($srv);
            }
            $status['services_seeding'] = [
                'success' => true,
                'message' => "Seeded " . count($services) . " default Service cards successfully!"
            ];
        } else {
            $status['services_seeding'] = [
                'success' => true,
                'message' => "Services table already contains records. Seeding skipped."
            ];
        }
    } catch (PDOException $e) {
        $status['services_seeding'] = [
            'success' => false,
            'message' => "Services seeding failed: " . $e->getMessage()
        ];
    }
}

// 4. Seed Countries if empty
if (in_array('countries', $tables_created)) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM countries");
        if ($stmt->fetchColumn() == 0) {
            $countries = [
                ['usa', 'United States', '🇺🇸', 'World-class universities with cutting-edge research facilities.'],
                ['uk', 'United Kingdom', '🇬🇧', 'Short duration courses with excellent academic reputation.'],
                ['canada', 'Canada', '🇨🇦', 'Safe, multicultural and affordable with great PR pathways.'],
                ['australia', 'Australia', '🇦🇺', 'Globally recognised degrees with excellent post-study work rights.'],
                ['germany', 'Germany', '🇩🇪', 'Free or low-cost tuition at top-ranked public universities.'],
                ['ireland', 'Ireland', '🇮🇪', 'English-speaking, tech-hub with a vibrant student community.'],
                ['singapore', 'Singapore', '🇸🇬', 'Asia\'s education capital with globally ranked universities.'],
                ['newzealand', 'New Zealand', '🇳🇿', 'Safe, scenic and student-friendly with excellent QS-ranked universities.'],
                ['france', 'France', '🇫🇷', 'Global leader in business, fashion, and culinary arts.'],
                ['italy', 'Italy', '🇮🇹', 'Study at the world\'s oldest universities in the land of art.'],
                ['sweden', 'Sweden', '🇸🇪', 'Innovation hub of Europe and home to the Nobel Prize.'],
                ['south-korea', 'South Korea', '🇰🇷', 'Leading in technology, robotics, and advanced research.'],
                ['uae', 'UAE', '🇦🇪', 'Tax-free work opportunities and global branch campuses.'],
                ['netherlands', 'Netherlands', '🇳🇱', 'First non-English country to offer courses in English.'],
                ['switzerland', 'Switzerland', '🇨🇭', 'Global center for banking, research, and hospitality.'],
                ['malaysia', 'Malaysia', '🇲🇾', 'UK and Australian degrees at a fraction of the cost.'],
                ['denmark', 'Denmark', '🇩🇰', 'Focus on innovation and high standard of living.'],
                ['bulgaria', 'Bulgaria', '🇧🇬', 'EU-recognized degrees with low tuition and living costs.'],
                ['russia', 'Russia', '🇷🇺', 'Strong legacy in medicine and engineering at low cost.'],
                ['philippines', 'Philippines', '🇵🇭', 'US-pattern medical education in a friendly environment.']
            ];
            
            $insert = $pdo->prepare("INSERT INTO countries (slug, name, flag, description, is_active) VALUES (?, ?, ?, ?, 1)");
            foreach ($countries as $c) {
                $insert->execute($c);
            }
            $status['countries_seeding'] = [
                'success' => true,
                'message' => "Seeded " . count($countries) . " Study Destinations successfully!"
            ];
        } else {
            $status['countries_seeding'] = [
                'success' => true,
                'message' => "Countries table already contains records. Seeding skipped."
            ];
        }
    } catch (PDOException $e) {
        $status['countries_seeding'] = [
            'success' => false,
            'message' => "Countries seeding failed: " . $e->getMessage()
        ];
    }
}

// 5. Seed Testimonials if empty
if (in_array('testimonials', $tables_created)) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM testimonials");
        if ($stmt->fetchColumn() == 0) {
            $testimonials = [
                ['Sai Raksha Manoharan','MSc in UK','SR','I was confused about which country to choose. Bluestone\'s counsellors patiently guided me and I got into my dream university in the UK. The visa process was smooth and stress-free!',5, 'blue'],
                ['Ashok Saravanan','MBA in Canada','AS','From shortlisting universities to visa approval, Bluestone was with me at every step. Their 500+ university connections really helped me get a scholarship. Highly recommend!',5, 'purple'],
                ['Vinith Babu','B.Tech in Germany','VB','Getting free education in Germany seemed impossible until Bluestone showed me the pathway. They prepared all my documents perfectly and I got my visa in just 3 weeks!',5, 'orange'],
                ['Priya Krishnamoorthy','MS in USA','PK','Bluestone guided me through GRE prep, application essays and visa. I got into a top-50 US university with a scholarship. Best consultancy in Coimbatore!',5, 'teal'],
                ['Mohammed Farhan','MBA in Australia','MF','The team at Bluestone is extremely professional and caring. They helped me secure a student loan and arrange accommodation. Truly a one-stop solution!',5, 'pink'],
                ['Divya Lakshmi','Nursing in Ireland','DL','I had heard scary stories about visa rejections but Bluestone\'s visa team is exceptional. Got my Ireland visa approved in 2 weeks. Forever grateful!',5, 'gold']
            ];
            
            $insert = $pdo->prepare("INSERT INTO testimonials (name, detail, initials, text, stars, color, is_active) VALUES (?, ?, ?, ?, ?, ?, 1)");
            foreach ($testimonials as $t) {
                $insert->execute($t);
            }
            $status['testimonials_seeding'] = [
                'success' => true,
                'message' => "Seeded " . count($testimonials) . " student Testimonial cards successfully!"
            ];
        } else {
            $status['testimonials_seeding'] = [
                'success' => true,
                'message' => "Testimonials table already contains records. Seeding skipped."
            ];
        }
    } catch (PDOException $e) {
        $status['testimonials_seeding'] = [
            'success' => false,
            'message' => "Testimonials seeding failed: " . $e->getMessage()
        ];
    }
}

// 6. Seed Hero Slides if empty
if (in_array('hero_slides', $tables_created)) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM hero_slides");
        if ($stmt->fetchColumn() == 0) {
            $slides = [
                ['Biggest Education Fair', 'Scholarships – Attend <span>Bluestone’s Biggest</span> Education Fair', 'USA | UK | Canada | Australia | New Zealand | Germany | Ireland', 'Secure your spot', 'assets/images/img4.png'],
                ['98% Visa Success', 'Your Dream University is <span>One Step Away</span>', 'Expert guidance from counselling to visa with personalised support across 20+ countries.', 'Book Free Session', 'assets/images/img8.png'],
                ['Global Education', 'Access World Class <span>Education Systems</span>', 'Explore thousands of courses with our direct university partnerships worldwide.', 'Explore Courses', 'assets/images/img7.png'],
                ['PR & Immigration', 'Settle Abroad – Permanent <span>Residency Expert</span>', 'Your gateway to a global future. Expert PR guidance for Canada, Australia, and more.', 'Check Eligibility', 'assets/images/img2.png'],
                ['Jobs & Visas', 'Global Jobs, PR & <span>Visitor Visas</span>', 'Job assistance for all countries. Permanent Resident (PR) pathways for Australia & Canada. Visitor visa services also available.', 'Get Started Today', 'assets/images/img5.png']
            ];
            
            $insert = $pdo->prepare("INSERT INTO hero_slides (badge, title, description, button_text, image_path, is_active) VALUES (?, ?, ?, ?, ?, 1)");
            foreach ($slides as $slide) {
                $insert->execute($slide);
            }
            $status['hero_slides_seeding'] = [
                'success' => true,
                'message' => "Seeded " . count($slides) . " homepage Hero sliders successfully!"
            ];
        } else {
            $status['hero_slides_seeding'] = [
                'success' => true,
                'message' => "Hero slides table already populated."
            ];
        }
    } catch (PDOException $e) {
        $status['hero_slides_seeding'] = [
            'success' => false,
            'message' => "Hero slides seeding failed: " . $e->getMessage()
        ];
    }
}

// 7. Seed Test Preps if empty
if (in_array('test_preps', $tables_created)) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM test_preps");
        if ($stmt->fetchColumn() == 0) {
            $test_preps = [
                ['ielts', 'IELTS', 'fa-pen-to-square', 'International English Language Testing System', 'Band 7+ Achievers', 'Expert Trainers', 'Full Mock Tests', 'Study Material Included', 'blue'],
                ['toefl', 'TOEFL', 'fa-pen-to-square', 'Test of English as a Foreign Language', '100+ Scorers', 'Online & Offline', 'Practice Tests', 'Score Guarantee', 'purple'],
                ['pte', 'PTE Academic', 'fa-pen-to-square', 'Pearson Test of English Academic', '79+ Scorers', 'AI-Powered Practice', 'Fast Results', 'Scholarship Guidance', 'orange']
            ];
            
            $insert = $pdo->prepare("INSERT INTO test_preps (slug, name, icon, description, feature1, feature2, feature3, feature4, color, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
            foreach ($test_preps as $prep) {
                $insert->execute($prep);
            }
            $status['test_preps_seeding'] = [
                'success' => true,
                'message' => "Seeded " . count($test_preps) . " default Test Preparation systems!"
            ];
        } else {
            $status['test_preps_seeding'] = [
                'success' => true,
                'message' => "Test prep table already populated."
            ];
        }
    } catch (PDOException $e) {
        $status['test_preps_seeding'] = [
            'success' => false,
            'message' => "Test prep seeding failed: " . $e->getMessage()
        ];
    }
}

// 8. Seed Gallery if empty
if (in_array('gallery_items', $tables_created)) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM gallery_items");
        if ($stmt->fetchColumn() == 0) {
            $gallery = [
                ['assets/images/md gallery5.png', 'Successful Student Seminar', 'Events'],
                ['assets/images/ias5.png', 'Advanced IELTS Training Class', 'Training'],
                ['assets/images/start.png', 'Pre-Departure Briefing Session', 'Workshops']
            ];
            
            $insert = $pdo->prepare("INSERT INTO gallery_items (image_path, title, category, is_active) VALUES (?, ?, ?, 1)");
            foreach ($gallery as $g) {
                $insert->execute($g);
            }
            $status['gallery_seeding'] = [
                'success' => true,
                'message' => "Seeded default photo gallery entries!"
            ];
        } else {
            $status['gallery_seeding'] = [
                'success' => true,
                'message' => "Gallery table already contains records."
            ];
        }
    } catch (PDOException $e) {
        $status['gallery_seeding'] = [
            'success' => false,
            'message' => "Gallery seeding failed: " . $e->getMessage()
        ];
    }
}

// 9. Seed Specialist Services if empty
if (in_array('specialist_services', $tables_created)) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM specialist_services");
        if ($stmt->fetchColumn() == 0) {
            $specs = [
                ['Global Job Placement', 'Work Abroad', 'fa-briefcase', 'Unlock promising career opportunities worldwide. We provide dedicated work assistance, document preparation, and interview coaching for candidates aiming at any country globally.', 'Job support for all major countries', 'Resume & LinkedIn optimization', 'Professional interview coaching', 'Explore Job Services', 'contact.php', 'blue'],
                ['Australia & Canada PR', 'Settle Permanently', 'fa-passport', 'Secure a prosperous, long-term future for your family. We offer comprehensive residency point evaluations, document verification, and regional nomination pathways.', 'Skilled Independent pathways (Subclass 189/190)', 'Express Entry & provincial PNP streams', 'Transparent document auditing', 'Check Eligibility', 'contact.php', 'accent'],
                ['Visitor & Tourist Visas', 'Travel Worldwide', 'fa-plane-departure', 'Fast and worry-free tourist visa guidance. We prepare high-quality document dossiers, flight itineraries, and sponsorship details to secure your approvals.', 'Schengen, USA, UK & Canada visit visas', 'Flawless financial portfolio structuring', 'Quick application support', 'Get Visa Quote', 'contact.php', 'teal']
            ];
            
            $insert = $pdo->prepare("INSERT INTO specialist_services (title, category_tag, icon, description, bullet1, bullet2, bullet3, button_text, button_link, color, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
            foreach ($specs as $spec) {
                $insert->execute($spec);
            }
            $status['specialist_services_seeding'] = [
                'success' => true,
                'message' => "Seeded specialist consulting service cards!"
            ];
        } else {
            $status['specialist_services_seeding'] = [
                'success' => true,
                'message' => "Specialist services table already contains records."
            ];
        }
    } catch (PDOException $e) {
        $status['specialist_services_seeding'] = [
            'success' => false,
            'message' => "Specialist services seeding failed: " . $e->getMessage()
        ];
    }
}

// 10. Seed Branches if empty
if (in_array('branches', $tables_created)) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM branches");
        if ($stmt->fetchColumn() == 0) {
            $branches = [
                ['Coimbatore', 'fa-building', '(HQ)', 'Renaissance Terrace, No.126L, 2nd Floor, Opp. Bishop Appasamy College'],
                ['Chennai', 'fa-city', '', 'No.13, Velachery Main Road, Mailai Balaji Nagar, Pallikaranai'],
                ['Salem', 'fa-location-dot', '', '9.3/14, Vettukadu, Konganapuram PO, Edappadi TK'],
                ['Erode', 'fa-location-dot', '', 'No1, Vairam Street, Municipal Colony, Near Arasan Eye Hospital'],
                ['Namakkal', 'fa-location-dot', '', '53/17, Second Floor, Paramathi Main Road, S.P. Pudur'],
                ['Tirunelveli', 'fa-location-dot', '', 'No.160/5, First Floor, Apollo Pharmacy Upstairs, Thoothukudi Main Road'],
                ['Nepal', 'fa-globe-asia', '', 'MCVG+V9R Hongkong Bazzar, Bharatpur 44207, Nepal'],
                ['Canada', 'fa-flag', '', '30 Denton Ave Unit 214, Toronto, ON M1L 4P2']
            ];
            
            $insert = $pdo->prepare("INSERT INTO branches (city, icon, badge, address, is_active) VALUES (?, ?, ?, ?, 1)");
            foreach ($branches as $b) {
                $insert->execute($b);
            }
            $status['branches_seeding'] = [
                'success' => true,
                'message' => "Seeded branch locations list successfully!"
            ];
        } else {
            $status['branches_seeding'] = [
                'success' => true,
                'message' => "Branches table already contains records."
            ];
        }
    } catch (PDOException $e) {
        $status['branches_seeding'] = [
            'success' => false,
            'message' => "Branches seeding failed: " . $e->getMessage()
        ];
    }
}

// 11. Seed Events if empty
if (in_array('events', $tables_created)) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM events");
        if ($stmt->fetchColumn() == 0) {
            $events = [
                ['UK Education Fair 2025', 'May 15, 2025', 'Coimbatore Office', 'Meet representatives from 20+ top UK universities. Spot assessment and scholarship guidance.'],
                ['Canada Visa Workshop', 'May 22, 2025', 'Online (Zoom)', 'Learn about the latest SDS rules, GIC requirements, and post-study work permits in Canada.'],
                ['IELTS Masterclass', 'June 05, 2025', 'Chennai Office', 'Intensive 4-hour workshop covering tips and tricks to score 7.5+ in IELTS.']
            ];
            
            $insert = $pdo->prepare("INSERT INTO events (title, date_string, location, description, is_active) VALUES (?, ?, ?, ?, 1)");
            foreach ($events as $ev) {
                $insert->execute($ev);
            }
            $status['events_seeding'] = [
                'success' => true,
                'message' => "Seeded upcoming study fairs and seminars!"
            ];
        } else {
            $status['events_seeding'] = [
                'success' => true,
                'message' => "Events table already contains records."
            ];
        }
    } catch (PDOException $e) {
        $status['events_seeding'] = [
            'success' => false,
            'message' => "Events seeding failed: " . $e->getMessage()
        ];
    }
}

// 12. Seed News/Blog Articles if empty
if (in_array('news_articles', $tables_created)) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM news_articles");
        if ($stmt->fetchColumn() == 0) {
            $blogs = [
                ['Why Malta Is the Smart Choice for International Students?', 'April 2025', 'Study Abroad', 'Malta is quickly emerging as one of Europe\'s most affordable destinations...', '🇲🇹', 'blog-details.php?id=14'],
                ['Why New Zealand Is a Smart Choice for Higher Education', 'March 2025', 'Destination Guide', 'New Zealand offers world-class education and stunning landscapes...', '🇳🇿', 'blog-details.php?id=13'],
                ['Study at German International College – Gateway to Germany', 'February 2025', 'University News', 'German International College offers a unique pathway to public universities...', '🇩🇪', 'blog-details.php?id=12'],
                ['How to Prepare for Your IELTS Speaking Test', 'January 2025', 'Test Prep', 'Tips and tricks to boost your score in the IELTS speaking module...', '📚', 'blog-details.php?id=11'],
                ['Cost of Living in Canada for International Students', 'December 2024', 'Student Life', 'A detailed breakdown of monthly expenses in major Canadian cities...', '🇨🇦', 'blog-details.php?id=10'],
                ['Top 5 Scholarships for Indian Students in UK', 'November 2024', 'Scholarships', 'Explore the best funding options for your master\'s degree in the UK...', '🇬🇧', 'blog-details.php?id=9']
            ];
            
            $insert = $pdo->prepare("INSERT INTO news_articles (title, date_string, tag, excerpt, emoji, link, is_active) VALUES (?, ?, ?, ?, ?, ?, 1)");
            foreach ($blogs as $b) {
                $insert->execute($b);
            }
            $status['news_seeding'] = [
                'success' => true,
                'message' => "Seeded default news and articles successfully!"
            ];
        } else {
            $status['news_seeding'] = [
                'success' => true,
                'message' => "News articles table already contains records."
            ];
        }
    } catch (PDOException $e) {
        $status['news_seeding'] = [
            'success' => false,
            'message' => "News seeding failed: " . $e->getMessage()
        ];
    }
}

// 13. Seed Video Testimonials if empty
if (in_array('testimonial_videos', $tables_created)) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM testimonial_videos");
        if ($stmt->fetchColumn() == 0) {
            $videos = [
                ['Sai Raksha Manoharan', 'MSc in UK', 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
                ['Ashok Saravanan', 'MBA in Canada', 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
                ['Priya Krishnamoorthy', 'MS in USA', 'https://www.youtube.com/embed/dQw4w9WgXcQ']
            ];
            
            $insert = $pdo->prepare("INSERT INTO testimonial_videos (student_name, details, youtube_url, is_active) VALUES (?, ?, ?, 1)");
            foreach ($videos as $v) {
                $insert->execute($v);
            }
            $status['videos_seeding'] = [
                'success' => true,
                'message' => "Seeded student video testimonial channels!"
            ];
        } else {
            $status['videos_seeding'] = [
                'success' => true,
                'message' => "Video testimonials table already contains records."
            ];
        }
    } catch (PDOException $e) {
        $status['videos_seeding'] = [
            'success' => false,
            'message' => "Video testimonials seeding failed: " . $e->getMessage()
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Installer | Bluestone Overseas</title>
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

        .action-btn {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            color: white;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(239, 68, 68, 0.4);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="logo-icon"><i class="fa-solid fa-screwdriver-wrench"></i></div>
        <h1>Admin System Setup</h1>
        <p class="subtitle">Configuring Tables and Seeding Database for Bluestone Admin Panel</p>
    </div>

    <div class="table-list">
        <?php foreach ($status as $tableName => $info): ?>
            <div class="table-item">
                <div class="table-meta">
                    <div class="table-icon">
                        <i class="fa-solid <?php 
                            if ($tableName == 'admin_seeding') echo 'fa-user-shield';
                            elseif (strpos($tableName, 'seeding') !== false) echo 'fa-seedling';
                            else echo 'fa-table';
                        ?>"></i>
                    </div>
                    <span class="table-name"><?php echo str_replace('_', ' ', ucfirst($tableName)); ?></span>
                </div>
                <div class="status-badge <?php echo $info['success'] ? 'success' : 'error'; ?>">
                    <i class="fa-solid <?php echo $info['success'] ? 'fa-circle-check' : 'fa-circle-xmark'; ?>"></i>
                    <span><?php echo $info['success'] ? 'Success' : 'Failed'; ?></span>
                </div>
            </div>
            <?php if ($tableName == 'admin_seeding' && $info['success'] && isset($default_user)): ?>
                <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); padding: 1rem; border-radius: 12px; margin-top: -0.5rem; margin-bottom: 0.5rem; font-size: 0.9rem; text-align: left; color: #34d399; line-height: 1.5;">
                    <i class="fa-solid fa-triangle-exclamation" style="margin-right: 0.5rem;"></i>
                    Please save these credentials to log in! You should change this password inside your profile manager immediately after logging in.
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <a href="login.php" class="action-btn">
        <span>Go to Admin Login</span>
        <i class="fa-solid fa-arrow-right"></i>
    </a>
</div>

</body>
</html>
