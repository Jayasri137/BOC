<?php require 'includes/db.php';
// 1. Deactivate non-study abroad slides
$pdo->exec('UPDATE hero_slides SET is_active = 0 WHERE id IN (4, 5)');

// 2. Add or update a slide to be the dominant proposition
$title = 'Best Study Abroad Consultants <span>in Coimbatore</span>';
$desc = 'Your ultimate gateway to world-class education. We specialise exclusively in global university admissions, scholarships, and student visas across 20+ countries.';
$badge = '10,000+ Success Stories';

// Update slide 1
$stmt = $pdo->prepare('UPDATE hero_slides SET title = ?, description = ?, badge = ?, is_active = 1 WHERE id = 1');
$stmt->execute([$title, $desc, $badge]);

echo 'Slides updated successfully!';

