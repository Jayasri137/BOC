<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Global Rank: Politecnico di Milano (#98) — Italy’s first global Top-100 university.',
    'roi_priority' => 'Affordability: Public tuition as low as €900 – €4,000/year (Income-based).',
    'roi_wage' => 'Stay-back: 12 Months Post-Study Work Permit for all graduates.',
    'roi_qs' => 'Work Quota: Decreto Flussi 2026 allocates priority work slots for Indian professionals.',
    'living_cost_local' => '€6,072 – €8,500 / year',
    'living_cost_inr' => 'Required for Visa (~₹5.5L – ₹7.6L)',
    'visa_fee_local' => '€50 – €116',
    'visa_fee_inr' => 'National Type D visa (~₹4.5k – ₹10k)',
    'weekly_budget_local' => '€700 – €1,000',
    'weekly_budget_inr' => 'Monthly living expenses (affordable shared life)',
    'earnings_potential_local' => '€9,000 / year',
    'earnings_potential_inr' => 'MAECI Scholarship support potential',
    'upcoming_intakes' => "Fall (September) | Primary Intake\nSpring (February) | Limited Courses",
    'demand_careers' => "Fashion Design\nArchitecture\nAutomotive Engineering\nData Science\nArtificial Intelligence",
    'travel_hours' => "Approx 8 - 14 hours (Depending on route and city)"
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
    WHERE `slug` = 'italy'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Italy DB updated successfully.\n";

// Update Italy Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'italy'");
$stmt->execute();
$italy = $stmt->fetch();

if ($italy) {
    $countryId = $italy['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $italyUnis = [
        [
            'name' => 'Politecnico di Milano',
            'qs_ranking' => '#98 Globally',
            'specialization' => 'Architecture, Design, Engineering',
            'courses' => [
                ['name' => 'MSc in Computer Science and Engineering', 'duration' => '2 Years', 'tuition_fee' => '€900 - €3,900', 'intakes' => 'September'],
                ['name' => 'MSc in Integrated Product Design', 'duration' => '2 Years', 'tuition_fee' => '€3,900', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Sapienza University of Rome',
            'qs_ranking' => '#128 Globally',
            'specialization' => 'Classics, Data Science',
            'courses' => [
                ['name' => 'MSc in Data Science', 'duration' => '2 Years', 'tuition_fee' => '€1,000 - €2,900', 'intakes' => 'September'],
                ['name' => 'MSc in Artificial Intelligence', 'duration' => '2 Years', 'tuition_fee' => '€2,900', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Bologna',
            'qs_ranking' => '#138 Globally',
            'specialization' => 'Business, Economics',
            'courses' => [
                ['name' => 'MSc in International Management', 'duration' => '2 Years', 'tuition_fee' => '€3,300', 'intakes' => 'September'],
                ['name' => 'MSc in Economics', 'duration' => '2 Years', 'tuition_fee' => '€3,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Padua',
            'qs_ranking' => '#233 Globally',
            'specialization' => 'Physics, Medicine',
            'courses' => [
                ['name' => 'MSc in Physics', 'duration' => '2 Years', 'tuition_fee' => '€2,600', 'intakes' => 'September'],
                ['name' => 'MSc in Medicine and Surgery', 'duration' => '6 Years', 'tuition_fee' => '€2,600', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Politecnico di Torino',
            'qs_ranking' => '#242 Globally',
            'specialization' => 'Automotive Engineering',
            'courses' => [
                ['name' => 'MSc in Automotive Engineering', 'duration' => '2 Years', 'tuition_fee' => '€2,600', 'intakes' => 'September'],
                ['name' => 'MSc in Mechanical Engineering', 'duration' => '2 Years', 'tuition_fee' => '€2,600', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($italyUnis as $uniData) {
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
    echo "Italy Universities and Courses updated successfully.\n";
} else {
    echo "Italy not found in DB.\n";
}
?>
