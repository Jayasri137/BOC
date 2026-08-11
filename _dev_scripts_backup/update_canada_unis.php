<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

// Get Canada ID
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'canada'");
$stmt->execute();
$canada = $stmt->fetch();

if ($canada) {
    $countryId = $canada['id'];

    // Delete existing
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $canadaUnis = [
        [
            'name' => 'University of Toronto',
            'qs_ranking' => '#29',
            'specialization' => 'AI, Engineering',
            'courses' => [
                ['name' => 'Master of Science in Applied Computing (AI)', 'duration' => '16 Months', 'tuition_fee' => '$40,000 CAD', 'intakes' => 'Fall'],
                ['name' => 'Master of Engineering', 'duration' => '1 Year', 'tuition_fee' => '$38,000 CAD', 'intakes' => 'Fall, Winter']
            ]
        ],
        [
            'name' => 'McGill University',
            'qs_ranking' => '#27',
            'specialization' => 'Medicine, Research',
            'courses' => [
                ['name' => 'Master of Science (Thesis)', 'duration' => '2 Years', 'tuition_fee' => '$25,000 CAD', 'intakes' => 'Fall'],
                ['name' => 'Master of Business Administration (MBA)', 'duration' => '1-2 Years', 'tuition_fee' => '$50,000 CAD', 'intakes' => 'Fall']
            ]
        ],
        [
            'name' => 'University of British Columbia',
            'qs_ranking' => '#40',
            'specialization' => 'Sustainability, Environmental Sciences',
            'courses' => [
                ['name' => 'Master of Engineering Leadership', 'duration' => '1 Year', 'tuition_fee' => '$35,000 CAD', 'intakes' => 'Fall'],
                ['name' => 'Master of Data Science', 'duration' => '10 Months', 'tuition_fee' => '$45,000 CAD', 'intakes' => 'Fall']
            ]
        ],
        [
            'name' => 'University of Alberta',
            'qs_ranking' => '#94',
            'specialization' => 'Energy, Healthcare',
            'courses' => [
                ['name' => 'Master of Nursing', 'duration' => '2 Years', 'tuition_fee' => '$20,000 CAD', 'intakes' => 'Fall'],
                ['name' => 'Master of Science in Energy Systems', 'duration' => '2 Years', 'tuition_fee' => '$22,000 CAD', 'intakes' => 'Fall']
            ]
        ],
        [
            'name' => 'University of Waterloo',
            'qs_ranking' => '#119',
            'specialization' => 'Co-op Programs, Technology',
            'courses' => [
                ['name' => 'Master of Mathematics in Computer Science', 'duration' => '2 Years', 'tuition_fee' => '$25,000 CAD', 'intakes' => 'Fall, Winter'],
                ['name' => 'Master of Engineering (Co-op)', 'duration' => '2 Years', 'tuition_fee' => '$30,000 CAD', 'intakes' => 'Fall']
            ]
        ]
    ];
    
    foreach ($canadaUnis as $uniData) {
        $uniStmt = $pdo->prepare("INSERT INTO `universities` (country_id, name, qs_ranking, specialization, is_active) VALUES (:cid, :name, :qs, :spec, 1)");
        $uniStmt->execute([
            'cid' => $countryId,
            'name' => $uniData['name'],
            'qs' => $uniData['qs_ranking'],
            'spec' => $uniData['specialization']
        ]);
        $uniId = $pdo->lastInsertId();
        
        foreach ($uniData['courses'] as $cData) {
            $cStmt = $pdo->prepare("INSERT INTO `courses` (university_id, name, duration, tuition_fee, intakes, is_active) VALUES (:uid, :name, :duration, :fee, :intakes, 1)");
            $cStmt->execute([
                'uid' => $uniId,
                'name' => $cData['name'],
                'duration' => $cData['duration'],
                'fee' => $cData['tuition_fee'],
                'intakes' => $cData['intakes']
            ]);
        }
    }
    echo "Canada Universities updated successfully.\n";
} else {
    echo "Canada not found in DB.\n";
}
?>
