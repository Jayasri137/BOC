<?php
require 'includes/db.php';

$missing_countries = [
    ['singapore', 'Singapore', '🇸🇬', 'Asia\'s education capital with globally ranked universities.'],
    ['newzealand', 'New Zealand', '🇳🇿', 'Safe, scenic and student-friendly with excellent QS-ranked universities.'],
    ['italy', 'Italy', '🇮🇹', 'Study at the world\'s oldest universities in the land of art.'],
    ['south-korea', 'South Korea', '🇰🇷', 'Leading in technology, robotics, and advanced research.'],
    ['uae', 'UAE', '🇦🇪', 'Tax-free work opportunities and global branch campuses.'],
    ['switzerland', 'Switzerland', '🇨🇭', 'Global center for banking, research, and hospitality.'],
    ['malaysia', 'Malaysia', '🇲🇾', 'UK and Australian degrees at a fraction of the cost.'],
    ['denmark', 'Denmark', '🇩🇰', 'Focus on innovation and high standard of living.'],
    ['bulgaria', 'Bulgaria', '🇧🇬', 'EU-recognized degrees with low tuition and living costs.'],
    ['russia', 'Russia', '🇷🇺', 'Strong legacy in medicine and engineering at low cost.'],
    ['philippines', 'Philippines', '🇵🇭', 'US-pattern medical education in a friendly environment.']
];

$stmt = $pdo->prepare("INSERT INTO countries (slug, name, flag, description) VALUES (?, ?, ?, ?)");
foreach ($missing_countries as $c) {
    // Check if exists first to avoid duplicates
    $check = $pdo->prepare("SELECT COUNT(*) FROM countries WHERE slug = ?");
    $check->execute([$c[0]]);
    if ($check->fetchColumn() == 0) {
        $stmt->execute($c);
        echo "Inserted: " . $c[1] . "\n";
    }
}
echo "Done inserting missing countries.";
