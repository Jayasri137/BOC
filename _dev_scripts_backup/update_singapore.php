<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Elite Rankings: Home to NUS (#8) and NTU (#12) — two of the world’s Top 15 universities.',
    'roi_priority' => 'Tuition Grant Advantage: Up to 50% tuition subsidy through the MOE Tuition Grant.',
    'roi_wage' => 'Student Work Rights: Work up to 16 hours/week during academic terms at approved institutions.',
    'roi_qs' => 'Global Safety: Consistently ranked among the safest cities in the world for international students.',
    'living_cost_local' => 'SGD 25,000 – 35,000',
    'living_cost_inr' => 'Approx. ₹16L – ₹22L required for visa and living proof',
    'visa_fee_local' => 'SGD 30',
    'visa_fee_inr' => 'Non-refundable processing fee',
    'weekly_budget_local' => 'SGD 18,000 – 22,000',
    'weekly_budget_inr' => 'Undergraduate rates with MOE Tuition Grant',
    'earnings_potential_local' => 'SGD 1,200 – 1,800 / mth',
    'earnings_potential_inr' => 'Includes accommodation, transport, and food',
    'upcoming_intakes' => "Main Intake (August) | Deadline: Jan – Feb 2026\nSecondary Intake (January) | Deadline: Sept 2026",
    'demand_careers' => "Artificial Intelligence\nData Science\nBusiness & Finance\nCyber Security\nBiotechnology",
    'travel_hours' => "Approx 4 - 6.5 hours (Depending on route and origin city)"
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
    WHERE `slug` = 'singapore'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Singapore DB updated successfully.\n";

// Update Singapore Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'singapore'");
$stmt->execute();
$singapore = $stmt->fetch();

if ($singapore) {
    $countryId = $singapore['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $singaporeUnis = [
        [
            'name' => 'National University of Singapore (NUS)',
            'qs_ranking' => '#8 Globally',
            'specialization' => 'Engineering, Law, Technology',
            'courses' => [
                ['name' => 'Master of Computing', 'duration' => '1.5 Years', 'tuition_fee' => 'SGD 45,000', 'intakes' => 'August'],
                ['name' => 'MSc in Business Analytics', 'duration' => '1 Year', 'tuition_fee' => 'SGD 55,000', 'intakes' => 'August']
            ]
        ],
        [
            'name' => 'Nanyang Technological University (NTU)',
            'qs_ranking' => '#12 Globally',
            'specialization' => 'AI, Materials Science',
            'courses' => [
                ['name' => 'MSc in Artificial Intelligence', 'duration' => '1 Year', 'tuition_fee' => 'SGD 48,000', 'intakes' => 'August'],
                ['name' => 'MSc in Financial Technology', 'duration' => '1 Year', 'tuition_fee' => 'SGD 52,000', 'intakes' => 'August']
            ]
        ],
        [
            'name' => 'Singapore Management University (SMU)',
            'qs_ranking' => 'Specialized Elite',
            'specialization' => 'Business, Accounting, Finance',
            'courses' => [
                ['name' => 'Master of Professional Accounting', 'duration' => '1 Year', 'tuition_fee' => 'SGD 42,000', 'intakes' => 'August'],
                ['name' => 'MSc in Wealth Management', 'duration' => '1 Year', 'tuition_fee' => 'SGD 60,000', 'intakes' => 'July']
            ]
        ],
        [
            'name' => 'Singapore University of Technology & Design (SUTD)',
            'qs_ranking' => 'MIT Collab',
            'specialization' => 'Design, Innovation, Technology',
            'courses' => [
                ['name' => 'Master of Innovation by Design', 'duration' => '1 Year', 'tuition_fee' => 'SGD 38,000', 'intakes' => 'September'],
                ['name' => 'MSc in Security by Design', 'duration' => '1 Year', 'tuition_fee' => 'SGD 40,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'SIM Global Education',
            'qs_ranking' => 'Premier Private',
            'specialization' => 'UK & Australian Partner Degrees',
            'courses' => [
                ['name' => 'MSc in Management (Partner)', 'duration' => '1.5 Years', 'tuition_fee' => 'SGD 30,000', 'intakes' => 'March, September'],
                ['name' => 'Bachelor of IT (Partner)', 'duration' => '3 Years', 'tuition_fee' => 'SGD 25,000/yr', 'intakes' => 'January, July']
            ]
        ]
    ];
    
    foreach ($singaporeUnis as $uniData) {
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
    echo "Singapore Universities and Courses updated successfully.\n";
} else {
    echo "Singapore not found in DB.\n";
}
?>
