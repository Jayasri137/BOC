<?php
require 'includes/db.php';

// Truncate existing tables
$pdo->exec("TRUNCATE TABLE services");
$pdo->exec("TRUNCATE TABLE countries");
$pdo->exec("TRUNCATE TABLE test_preps");
$pdo->exec("TRUNCATE TABLE gallery_items");
$pdo->exec("TRUNCATE TABLE testimonial_videos");
$pdo->exec("TRUNCATE TABLE process_steps");

// 1. Services
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
$stmt = $pdo->prepare("INSERT INTO services (icon, title, description, link, color) VALUES (?, ?, ?, ?, ?)");
foreach ($services as $s) {
    $stmt->execute($s);
}

// 2. Countries
$all_countries = [
    ['usa', 'United States', '🇺🇸', 'World-class universities with cutting-edge research facilities.'],
    ['uk', 'United Kingdom', '🇬🇧', 'Rich academic heritage and 1-year master programs.'],
    ['canada', 'Canada', '🇨🇦', 'High quality of life and great post-study work options.'],
    ['australia', 'Australia', '🇦🇺', 'Top-ranked universities in a welcoming, multicultural society.'],
    ['germany', 'Germany', '🇩🇪', 'Tuition-free public universities and a strong engineering hub.'],
    ['new-zealand', 'New Zealand', '🇳🇿', 'Safe environment with excellent education standards.'],
    ['ireland', 'Ireland', '🇮🇪', 'Europe\'s tech hub with fantastic graduate opportunities.'],
    ['france', 'France', '🇫🇷', 'Affordable tuition with world-renowned business schools.'],
    ['netherlands', 'Netherlands', '🇳🇱', 'Progressive, English-taught programs in a vibrant culture.'],
    ['sweden', 'Sweden', '🇸🇪', 'Innovative education focusing on sustainability and tech.'],
];
$stmt = $pdo->prepare("INSERT INTO countries (slug, name, flag, description) VALUES (?, ?, ?, ?)");
foreach ($all_countries as $c) {
    $stmt->execute($c);
}

// 3. Test Preps
$db_tests = [
    [
        'slug' => 'ielts',
        'name' => 'IELTS',
        'icon' => 'fa-pen-to-square',
        'description' => 'Master the IELTS exam with our comprehensive training and expert guidance.',
        'image_path' => 'assets/images/service_coaching_3d.png'
    ],
    [
        'slug' => 'toefl',
        'name' => 'TOEFL',
        'icon' => 'fa-pen-to-square',
        'description' => 'Achieve your desired TOEFL score with our specialized coaching programs.',
        'image_path' => 'assets/images/service_guidance_3d.png'
    ],
    [
        'slug' => 'pte',
        'name' => 'PTE',
        'icon' => 'fa-pen-to-square',
        'description' => 'Get ready for PTE with our intensive practice sessions and mock tests.',
        'image_path' => 'assets/images/service_university_3d.png'
    ]
];
$stmt = $pdo->prepare("INSERT INTO test_preps (slug, name, icon, description, image_path) VALUES (?, ?, ?, ?, ?)");
foreach ($db_tests as $t) {
    $stmt->execute([$t['slug'], $t['name'], $t['icon'], $t['description'], $t['image_path']]);
}

// 4. Process Steps
$steps = [
    ['assets/images/img1.png', 'Free Counselling', 'Book a free session with our expert counsellor who assesses your profile and goals.', 'yellow'],
    ['assets/images/img2.png', 'Course & Country Selection', 'We shortlist the best universities and programs matching your ambitions and budget.', 'blue'],
    ['assets/images/img3.png', 'Application Filing', 'Our team prepares and submits your application with a flawless SOP and documents.', 'purple'],
    ['assets/images/img4.png', 'Visa Processing', 'Get expert help with student visa applications, ensuring all requirements are met.', 'orange'],
    ['assets/images/img5.png', 'Fly Abroad!', 'Pre-departure briefing, accommodation guidance and you are off to your dream university!', 'teal']
];
$stmt = $pdo->prepare("INSERT INTO process_steps (icon, title, description, color) VALUES (?, ?, ?, ?)");
foreach ($steps as $s) {
    $stmt->execute($s);
}

// 5. Gallery
$gallery = [
    ['assets/images/md-gallery5.png', 'Student Seminar Event'],
    ['assets/images/ias5.png', 'IELTS Coaching Session'],
    ['assets/images/start.png', 'Pre-Departure Briefing'],
    ['assets/images/img1.png', 'Visa Success Meet'],
    ['assets/images/placement.jpeg', 'Placement Seminar'],
    ['assets/images/img2.png', 'University Tour'],
    ['assets/images/img3.png', 'Admission Success']
];
$stmt = $pdo->prepare("INSERT INTO gallery_items (image_path, title) VALUES (?, ?)");
foreach ($gallery as $g) {
    $stmt->execute($g);
}

// 6. Testimonial Videos
$videos = [
    ['Sai Raksha Manoharan', 'MSc in United Kingdom', 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
    ['Ashok Saravanan', 'MBA in Canada', 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
    ['Priya Krishnamoorthy', 'MS in United States', 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
    ['Anish Kumar', 'BE in Australia', 'https://www.youtube.com/embed/dQw4w9WgXcQ']
];
$stmt = $pdo->prepare("INSERT INTO testimonial_videos (student_name, details, youtube_url) VALUES (?, ?, ?)");
foreach ($videos as $v) {
    $stmt->execute($v);
}

echo "Successfully migrated all local arrays to remote DB.";
