<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Innovation & Equality: Denmark consistently ranks among the world’s Top 3 for happiness and safety.',
    'roi_priority' => 'Establishment Card: Up to 3 Years post-study stay-back for STEM and IT graduates.',
    'roi_wage' => 'Work Rights: Work up to 90 hours/month during study and full-time during summer.',
    'roi_qs' => 'Global Rankings: 3 Danish universities ranked within the World’s Top 150 (QS 2026).',
    'living_cost_local' => 'DKK 6,820 / month',
    'living_cost_inr' => 'Mandatory maintenance requirement (~₹83,000 / month)',
    'visa_fee_local' => 'DKK 2,115',
    'visa_fee_inr' => 'Official SIRI processing fee (~₹25,500)',
    'weekly_budget_local' => 'DKK 7,500 - 10,000',
    'weekly_budget_inr' => 'Average monthly living expenses including rent (~₹90k - ₹1.2L)',
    'earnings_potential_local' => 'DKK 60,000 - 130,000',
    'earnings_potential_inr' => 'Average annual Master’s Tuition Fees',
    'upcoming_intakes' => "Autumn (September) | Primary Intake\nSpring (February) | Secondary Intake",
    'demand_careers' => "Renewable Energy Engineering\nArtificial Intelligence\nBiotechnology\nData Science\nSustainable Architecture",
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
    WHERE `slug` = 'denmark'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Denmark DB updated successfully.\n";

// Update Denmark Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'denmark'");
$stmt->execute();
$denmark = $stmt->fetch();

if ($denmark) {
    $countryId = $denmark['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $denmarkUnis = [
        [
            'name' => 'University of Copenhagen',
            'qs_ranking' => '#101 Globally',
            'specialization' => 'Health Sciences, Humanities, Life Sciences',
            'courses' => [
                ['name' => 'MSc in Bioinformatics', 'duration' => '2 Years', 'tuition_fee' => 'DKK 120,000', 'intakes' => 'September'],
                ['name' => 'MSc in Global Health', 'duration' => '2 Years', 'tuition_fee' => 'DKK 100,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Technical University of Denmark (DTU)',
            'qs_ranking' => '#107 Globally',
            'specialization' => 'Sustainable Energy, Engineering',
            'courses' => [
                ['name' => 'MSc in Sustainable Energy', 'duration' => '2 Years', 'tuition_fee' => 'DKK 112,000', 'intakes' => 'September, February'],
                ['name' => 'MSc in Autonomous Systems', 'duration' => '2 Years', 'tuition_fee' => 'DKK 112,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Aarhus University',
            'qs_ranking' => '#131 Globally',
            'specialization' => 'Environmental Science, Business',
            'courses' => [
                ['name' => 'MSc in Environmental Science', 'duration' => '2 Years', 'tuition_fee' => 'DKK 105,000', 'intakes' => 'September'],
                ['name' => 'MSc in Business Analytics', 'duration' => '2 Years', 'tuition_fee' => 'DKK 90,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Southern Denmark (SDU)',
            'qs_ranking' => '#303 Globally',
            'specialization' => 'Robotics, International Business',
            'courses' => [
                ['name' => 'MSc in Engineering (Robotics)', 'duration' => '2 Years', 'tuition_fee' => 'DKK 100,000', 'intakes' => 'September, February'],
                ['name' => 'MSc in Economics & Business Admin', 'duration' => '2 Years', 'tuition_fee' => 'DKK 85,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Aalborg University',
            'qs_ranking' => '#306 Globally',
            'specialization' => 'Problem-Based Learning, Engineering',
            'courses' => [
                ['name' => 'MSc in Sustainable Energy Engineering', 'duration' => '2 Years', 'tuition_fee' => 'DKK 95,000', 'intakes' => 'September'],
                ['name' => 'MSc in Artificial Intelligence', 'duration' => '2 Years', 'tuition_fee' => 'DKK 95,000', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($denmarkUnis as $uniData) {
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
    echo "Denmark Universities and Courses updated successfully.\n";
} else {
    echo "Denmark not found in DB.\n";
}
?>
