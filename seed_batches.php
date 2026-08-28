<?php
require_once 'includes/config.php';

try {
    $pdo->exec("DELETE FROM upcoming_batches");
    echo "Old batches deleted.\n";

    $batches = [
        // IELTS
        ['ielts', '03 Sep', '6:00 PM - 7:30 PM', 'Online', ''],
        ['ielts', '08 Sep', '8:00 AM - 9:30 AM', 'Online', ''],
        ['ielts', '11 Sep', '8:00 PM - 9:30 PM', 'Online', ''],
        ['ielts', '15 Sep', '10:00 PM - 11:30 PM', 'Online', ''],
        ['ielts', '23 Sep', '6:00 PM - 7:30 PM', 'Online', ''],
        ['ielts', '29 Sep', '8:00 AM - 9:30 AM', 'Online', ''],
        // GERMAN
        ['german', '04 Sep', '8:00 AM - 9:30 AM', 'Online', 'A1'],
        ['german', '11 Sep', '6:00 PM - 7:30 PM', 'Online', 'A2'],
        ['german', '14 Sep', '6:30 PM - 8:00 PM', 'Online', 'A1'],
        ['german', '22 Sep', '7:00 AM - 8:30 AM', 'Online', 'A1'],
        // FRENCH
        ['french', '07 Sep', '4:30 PM - 6:00 PM', 'Online', 'A1'],
        ['french', '15 Sep', '7:30 AM - 9:00 AM', 'Online', 'B1'],
        ['french', '18 Sep', '10:00 AM - 11:30 AM', 'Online', 'A2'],
        ['french', '25 Sep', '8:30 PM - 10:00 PM', 'Online', 'A1'],
        // GRE/GMAT
        ['gre-gmat', '07 Sep', '10:00 PM - 11:30 PM', 'Online', ''],
        ['gre-gmat', '22 Sep', '7:30 AM - 9:00 AM', 'Online', ''],
        // SAT
        ['sat', '04 Sep', '6:00 PM - 7:30 PM', 'Online', ''],
        ['sat', '14 Sep', '8:00 PM - 9:30 PM', 'Online', ''],
        // PTE
        ['pte', '05 Sep', '8:00 AM - 9:30 AM', 'Online', ''],
        ['pte', '14 Sep', '8:30 PM - 10:00 PM', 'Online', ''],
        ['pte', '24 Sep', '6:00 PM - 7:30 PM', 'Online', ''],
        // DET
        ['det', '08 Sep', '7:00 AM - 8:30 AM', 'Online', ''],
        ['det', '18 Sep', '10:00 PM - 11:30 PM', 'Online', ''],
        // TOEFL
        ['toefl', '08 Sep', '7:00 AM - 8:00 AM', 'Online', ''],
        // dMAT
        ['dmat', '05 Sep', '10:00 PM - 11:30 PM', 'Online', '']
    ];

    $stmt = $pdo->prepare("INSERT INTO upcoming_batches (course_slug, start_date, batch_time, batch_mode, duration, status, is_active) VALUES (?, ?, ?, ?, ?, 'Open', 1)");

    $count = 0;
    foreach($batches as $b) {
        $date = $b[1] . ' 2026';
        $duration = !empty($b[4]) ? $b[4] . ' Level' : 'Standard';
        $stmt->execute([$b[0], $date, $b[2], $b[3], $duration]);
        $count++;
    }

    echo "Inserted $count batches successfully.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
