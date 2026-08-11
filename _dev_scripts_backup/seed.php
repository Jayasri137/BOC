<?php
require_once 'includes/db.php';

try {
    // 1. Process Steps
    $pdo->exec("CREATE TABLE IF NOT EXISTS process_steps (
        id INT AUTO_INCREMENT PRIMARY KEY,
        icon VARCHAR(255) NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        color VARCHAR(50),
        is_active TINYINT(1) DEFAULT 1
    )");

    $stmt = $pdo->query("SELECT COUNT(*) FROM process_steps");
    if ($stmt->fetchColumn() == 0) {
        $steps = [
            ['fa-comments','Free Counselling','Book a free session with our expert counsellor who assesses your profile and goals.','blue'],
            ['fa-magnifying-glass','Course & Country Selection','We shortlist the best universities and programs matching your ambitions and budget.','purple'],
            ['fa-file-contract','Application Filing','Our team prepares and submits your application with a flawless SOP and documents.','orange'],
            ['fa-passport','Visa Processing','Get expert help with student visa applications, ensuring all requirements are met.','teal'],
            ['fa-plane-departure','Fly Abroad!','Pre-departure briefing, accommodation guidance and you are off to your dream university!','pink'],
        ];
        $insert = $pdo->prepare("INSERT INTO process_steps (icon, title, description, color) VALUES (?, ?, ?, ?)");
        foreach ($steps as $s) {
            $insert->execute($s);
        }
        echo "Inserted process_steps.\n";
    }

    // 2. Countries
    $pdo->exec("CREATE TABLE IF NOT EXISTS countries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slug VARCHAR(100) NOT NULL,
        name VARCHAR(100) NOT NULL,
        flag VARCHAR(20) NOT NULL,
        description TEXT,
        is_active TINYINT(1) DEFAULT 1
    )");

    $stmt = $pdo->query("SELECT COUNT(*) FROM countries");
    if ($stmt->fetchColumn() == 0) {
        $all_countries = [
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
        $insert = $pdo->prepare("INSERT INTO countries (slug, name, flag, description) VALUES (?, ?, ?, ?)");
        foreach ($all_countries as $c) {
            $insert->execute($c);
        }
        echo "Inserted countries.\n";
    }

    // 3. Services
    $pdo->exec("CREATE TABLE IF NOT EXISTS services (
        id INT AUTO_INCREMENT PRIMARY KEY,
        icon VARCHAR(255) NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        link VARCHAR(255),
        color VARCHAR(50),
        is_active TINYINT(1) DEFAULT 1
    )");

    $stmt = $pdo->query("SELECT COUNT(*) FROM services");
    if ($stmt->fetchColumn() == 0) {
        $services = [
            ['fa-user-graduate','Student Counselling','Personalised guidance to help you choose the right course and institution matching your academic goals and budget.','student-counselling.php','blue'],
            ['fa-university','University Selection','We help identify the best-fit universities across 20+ countries based on your profile and aspirations.','university-selection.php','purple'],
            ['fa-file-contract','Admission Processing','Expert application management ensuring all documents are accurate, complete and submitted on time.','admission-processing.php','orange'],
            ['fa-hand-holding-dollar','Financial Assistance','Guidance on scholarships, student loans and funding options to make your dream affordable.','financial-assistance.php','teal'],
            ['fa-passport','Visa Processing','End-to-end visa assistance with a 98% success rate, navigating complex immigration requirements.','visa-processing.php','pink'],
            ['fa-house','Accommodation & Travel','We help arrange housing and travel plans so you arrive and settle comfortably in your new country.','accommodation.php','gold'],
            ['fa-pen-to-square','Test Preparation','Specialised coaching for IELTS, TOEFL and PTE to achieve the scores required by top universities.','test-prep.php','blue'],
            ['fa-briefcase','Part-Time Job Help','Guidance on finding legal part-time work opportunities abroad to support yourself financially.','part-time-jobs.php','purple'],
        ];
        $insert = $pdo->prepare("INSERT INTO services (icon, title, description, link, color) VALUES (?, ?, ?, ?, ?)");
        foreach ($services as $s) {
            $insert->execute($s);
        }
        echo "Inserted services.\n";
    }

    // 4. Test Preps
    $pdo->exec("CREATE TABLE IF NOT EXISTS test_preps (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slug VARCHAR(100) NOT NULL,
        name VARCHAR(100) NOT NULL,
        icon VARCHAR(255) NOT NULL,
        description TEXT,
        image_path VARCHAR(255),
        is_active TINYINT(1) DEFAULT 1
    )");

    $stmt = $pdo->query("SELECT COUNT(*) FROM test_preps");
    if ($stmt->fetchColumn() == 0) {
        $db_tests = [
            [
                'ielts',
                'IELTS',
                'fa-pen-to-square',
                'Master the IELTS exam with our comprehensive training and expert guidance.',
                'assets/images/service_coaching_3d.png'
            ],
            [
                'toefl',
                'TOEFL',
                'fa-globe',
                'Ace the TOEFL with proven strategies and extensive practice tests.',
                'assets/images/service_university_3d.png'
            ],
            [
                'pte',
                'PTE',
                'fa-computer',
                'Achieve your desired PTE score with our specialized computer-based training.',
                'assets/images/service_guidance_3d.png'
            ]
        ];
        $insert = $pdo->prepare("INSERT INTO test_preps (slug, name, icon, description, image_path) VALUES (?, ?, ?, ?, ?)");
        foreach ($db_tests as $t) {
            $insert->execute($t);
        }
        echo "Inserted test_preps.\n";
    }

    echo "All data migrated successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
