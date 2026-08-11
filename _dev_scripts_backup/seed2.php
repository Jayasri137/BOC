<?php
require 'includes/db.php';

// 1. Gallery Items
$pdo->exec("CREATE TABLE IF NOT EXISTS gallery_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_path VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$stmt = $pdo->query("SELECT COUNT(*) FROM gallery_items");
if ($stmt->fetchColumn() == 0) {
    $gallery = [
        ['assets/images/md-gallery5.png', 'Student Seminar Event'],
        ['assets/images/ias5.png', 'IELTS Coaching Session'],
        ['assets/images/start.png', 'Pre-Departure Briefing'],
        ['assets/images/img1.png', 'Visa Success Meet'],
        ['assets/images/placement.jpeg', 'Placement Seminar'],
        ['assets/images/img2.png', 'University Tour'],
        ['assets/images/img3.png', 'Admission Success']
    ];
    $insert = $pdo->prepare("INSERT INTO gallery_items (image_path, title) VALUES (?, ?)");
    foreach ($gallery as $item) {
        $insert->execute($item);
    }
    echo "Inserted gallery_items.\n";
} else {
    echo "gallery_items already populated.\n";
}

// 2. Testimonial Videos
$pdo->exec("CREATE TABLE IF NOT EXISTS testimonial_videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(255) NOT NULL,
    details VARCHAR(255) NOT NULL,
    youtube_url VARCHAR(255) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$stmt = $pdo->query("SELECT COUNT(*) FROM testimonial_videos");
if ($stmt->fetchColumn() == 0) {
    $videos = [
        ['Sai Raksha Manoharan', 'MSc in United Kingdom', 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
        ['Ashok Saravanan', 'MBA in Canada', 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
        ['Priya Krishnamoorthy', 'MS in United States', 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
        ['Anish Kumar', 'BE in Australia', 'https://www.youtube.com/embed/dQw4w9WgXcQ']
    ];
    $insert = $pdo->prepare("INSERT INTO testimonial_videos (student_name, details, youtube_url) VALUES (?, ?, ?)");
    foreach ($videos as $video) {
        $insert->execute($video);
    }
    echo "Inserted testimonial_videos.\n";
} else {
    echo "testimonial_videos already populated.\n";
}

// 3. Update Services if they don't match the hardcoded ones
$stmt = $pdo->query("SELECT COUNT(*) FROM services WHERE title = 'Student Counselling'");
if ($stmt->fetchColumn() > 0) {
    // Wait, the DB already has valid services, but let's make sure the imageMap in index.php matches them!
}
