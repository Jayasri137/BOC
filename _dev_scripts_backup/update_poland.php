<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Economic Engine: Poland’s job market is growing faster than many EU economies, becoming Europe’s leading Nearshoring destination.',
    'roi_priority' => 'Digital Residency: New 2026 “Karta Pobytu” system enables fully digital residence applications and faster approvals.',
    'roi_wage' => 'Work Rights: Full-time students at public universities can work unlimited hours without a separate work permit.',
    'roi_qs' => 'Stay-back: 1-Year Job Search Extension for all non-EU graduates, with a pathway to the premium EU Blue Card.',
    'living_cost_local' => 'PLN 13,730 / year',
    'living_cost_inr' => 'Approx. ₹2.9 Lakhs for annual maintenance',
    'visa_fee_local' => '€200',
    'visa_fee_inr' => 'National Visa (Type D) application fee (~₹18,000)',
    'weekly_budget_local' => 'PLN 1,373',
    'weekly_budget_inr' => 'Minimum monthly living funds required (~₹29k)',
    'earnings_potential_local' => 'PLN 10,000 – 18,000',
    'earnings_potential_inr' => 'Average annual non-medical Tuition Fees (~₹2.1L – ₹3.8L)',
    'upcoming_intakes' => "Winter (October) | Primary Intake\nSummer (February) | Secondary Intake",
    'demand_careers' => "Artificial Intelligence\nCyber Security\nMechanical Engineering\nLogistics & Supply Chain\nData Analytics",
    'travel_hours' => "Approx 8 - 15 hours (Depending on route and city)"
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
    WHERE `slug` = 'poland'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Poland DB updated successfully.\n";

// Update Poland Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'poland'");
$stmt->execute();
$poland = $stmt->fetch();

if ($poland) {
    $countryId = $poland['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $polandUnis = [
        [
            'name' => 'University of Warsaw',
            'qs_ranking' => '#271 Globally',
            'specialization' => 'Data Science, Physics, International Business',
            'courses' => [
                ['name' => 'MSc in Data Science and Business Analytics', 'duration' => '2 Years', 'tuition_fee' => '€3,500', 'intakes' => 'October'],
                ['name' => 'MA in International Business Program', 'duration' => '2 Years', 'tuition_fee' => '€4,000', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'Jagiellonian University',
            'qs_ranking' => '#303 Globally',
            'specialization' => 'Medicine, Law, Humanities',
            'courses' => [
                ['name' => 'Doctor of Medicine (MD)', 'duration' => '6 Years', 'tuition_fee' => '€15,000', 'intakes' => 'October'],
                ['name' => 'MA in International Relations', 'duration' => '2 Years', 'tuition_fee' => '€3,000', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'Warsaw University of Technology',
            'qs_ranking' => '#487 Globally',
            'specialization' => 'Civil & Electrical Engineering',
            'courses' => [
                ['name' => 'MSc in Computer Science (AI)', 'duration' => '1.5 Years', 'tuition_fee' => '€4,500', 'intakes' => 'October, February'],
                ['name' => 'MSc in Electrical Engineering', 'duration' => '1.5 Years', 'tuition_fee' => '€4,500', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'Adam Mickiewicz University',
            'qs_ranking' => '#741 Globally',
            'specialization' => 'Social Sciences, Humanities',
            'courses' => [
                ['name' => 'MA in International Management', 'duration' => '2 Years', 'tuition_fee' => '€2,500', 'intakes' => 'October'],
                ['name' => 'MA in Psychology', 'duration' => '2 Years', 'tuition_fee' => '€2,500', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'AGH University of Krakow',
            'qs_ranking' => '#801 Globally',
            'specialization' => 'Mining, Cyber Security, Applied Sciences',
            'courses' => [
                ['name' => 'MSc in Cyber Security', 'duration' => '1.5 Years', 'tuition_fee' => '€3,500', 'intakes' => 'October'],
                ['name' => 'MSc in Mechatronic Engineering', 'duration' => '1.5 Years', 'tuition_fee' => '€3,500', 'intakes' => 'October']
            ]
        ]
    ];
    
    foreach ($polandUnis as $uniData) {
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
    echo "Poland Universities and Courses updated successfully.\n";
} else {
    echo "Poland not found in DB.\n";
}
?>
