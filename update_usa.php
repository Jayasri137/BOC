<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Elite Rankings: Home to 10 of the World’s Top 20 universities (QS 2026).',
    'roi_priority' => 'Massive Choice: 4,000+ accredited universities and colleges across the United States.',
    'roi_wage' => 'Innovation Capital: Global leader in Research, AI, Engineering, and Entrepreneurship.',
    'roi_qs' => 'Career Advantage: Strong internship, OPT, and STEM career pathways for international students.',
    'living_cost_local' => '$10,000 – $20,000 / yr',
    'living_cost_inr' => 'Depends on city and lifestyle',
    'visa_fee_local' => '$185',
    'visa_fee_inr' => 'F-1 Visa Fee (Excludes SEVIS fee)',
    'weekly_budget_local' => '$20,000 – $40,000',
    'weekly_budget_inr' => 'Undergraduate Average Tuition',
    'earnings_potential_local' => '$1,000 – $2,500',
    'earnings_potential_inr' => 'Mandatory Health Insurance',
    'upcoming_intakes' => "Fall Intake (September) | Primary Intake\nSpring Intake (January) | Secondary Intake\nSummer Intake (May) | Limited Programs",
    'demand_careers' => "Artificial Intelligence\nData Science\nComputer Science\nCyber Security\nBiotechnology",
    'travel_hours' => "Approx 16 - 23 hours (Depending on route and origin city)"
];

$updateSql = "UPDATE `countries` SET 
    `roi_advantage` = :roi_advantage,
    `roi_priority` = :roi_priority,
    `roi_wage` = :roi_wage,
    `roi_qs` = :roi_qs,
    `living_cost_local` = :living_cost_local,
    `living_cost_inr` = :living_cost_inr,
    `visa_fee_local` = :visa_fee_local,
    `visa_fee_inr` = :visa_fee_inr,
    `weekly_budget_local` = :weekly_budget_local,
    `weekly_budget_inr` = :weekly_budget_inr,
    `earnings_potential_local` = :earnings_potential_local,
    `earnings_potential_inr` = :earnings_potential_inr,
    `upcoming_intakes` = :upcoming_intakes,
    `demand_careers` = :demand_careers,
    `travel_hours` = :travel_hours
    WHERE `slug` = 'usa'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "USA DB updated successfully.\n";

// Update USA Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'usa'");
$stmt->execute();
$usa = $stmt->fetch();

if ($usa) {
    $countryId = $usa['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $usaUnis = [
        [
            'name' => 'Massachusetts Institute of Technology (MIT)',
            'qs_ranking' => 'Top Global Ranking',
            'specialization' => 'Engineering, AI, Technology',
            'courses' => [
                ['name' => 'Master of Engineering', 'duration' => '1.5 Years', 'tuition_fee' => '$59,000', 'intakes' => 'September'],
                ['name' => 'MSc in Computer Science', 'duration' => '2 Years', 'tuition_fee' => '$57,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Stanford University',
            'qs_ranking' => 'Elite Global',
            'specialization' => 'Entrepreneurship, Computer Science',
            'courses' => [
                ['name' => 'MSc in Computer Science', 'duration' => '1.5 Years', 'tuition_fee' => '$58,000', 'intakes' => 'September'],
                ['name' => 'MBA', 'duration' => '2 Years', 'tuition_fee' => '$76,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Harvard University',
            'qs_ranking' => 'World-leading',
            'specialization' => 'Business, Law, Medicine',
            'courses' => [
                ['name' => 'MBA', 'duration' => '2 Years', 'tuition_fee' => '$74,000', 'intakes' => 'September'],
                ['name' => 'Master of Public Health', 'duration' => '1 Year', 'tuition_fee' => '$65,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of California, Berkeley',
            'qs_ranking' => 'Innovation Powerhouse',
            'specialization' => 'Engineering, Data Science',
            'courses' => [
                ['name' => 'Master of Engineering', 'duration' => '1 Year', 'tuition_fee' => '$55,000', 'intakes' => 'September'],
                ['name' => 'Master of Information and Data Science', 'duration' => '1.5 Years', 'tuition_fee' => '$51,000', 'intakes' => 'September, January']
            ]
        ],
        [
            'name' => 'Carnegie Mellon University',
            'qs_ranking' => 'Global Tech Leader',
            'specialization' => 'Robotics, AI, Computer Science',
            'courses' => [
                ['name' => 'MSc in Artificial Intelligence', 'duration' => '1.5 Years', 'tuition_fee' => '$53,000', 'intakes' => 'September'],
                ['name' => 'MSc in Robotics', 'duration' => '2 Years', 'tuition_fee' => '$54,000', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($usaUnis as $uniData) {
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
    echo "USA Universities and Courses updated successfully.\n";
} else {
    echo "USA not found in DB.\n";
}
?>
