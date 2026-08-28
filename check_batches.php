<?php
require_once __DIR__ . '/includes/db.php';
$pdo->exec("INSERT INTO upcoming_batches (course_slug, start_date, batch_time, batch_mode, status, is_active) VALUES ('german', '20th Sept, 2026', '10:00 AM - 12:00 PM', 'Online', 'Filling Fast', 1)");
$pdo->exec("INSERT INTO upcoming_batches (course_slug, start_date, batch_time, batch_mode, status, is_active) VALUES ('pte', '22nd Sept, 2026', '06:00 PM - 08:00 PM', 'Offline', 'Open', 1)");
$pdo->exec("INSERT INTO upcoming_batches (course_slug, start_date, batch_time, batch_mode, status, is_active) VALUES ('ielts', '25th Sept, 2026', '11:00 AM - 01:00 PM', 'Hybrid', 'Closed', 1)");
$pdo->exec("INSERT INTO upcoming_batches (course_slug, start_date, batch_time, batch_mode, status, is_active) VALUES ('japanese', '01st Oct, 2026', '09:00 AM - 11:00 AM', 'Online', 'Filling Fast', 1)");
$pdo->exec("INSERT INTO upcoming_batches (course_slug, start_date, batch_time, batch_mode, status, is_active) VALUES ('toefl', '05th Oct, 2026', '02:00 PM - 04:00 PM', 'Offline', 'Open', 1)");
echo "Inserted dummy batches.";
