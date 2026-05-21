<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Elite Rankings: Home to 3 of the World’s Top 5 universities (QS 2026).',
    'roi_priority' => 'Shorter Degree Duration: Complete a Master’s in 1 year and a Bachelor’s in 3 years.',
    'roi_wage' => 'Graduate Route Visa: 2-Year Post-Study Work Visa remains active for 2026 graduates.',
    'roi_qs' => 'Global Research: 82% of UK research is rated “internationally excellent.”',
    'living_cost_local' => '£1,023 – £1,334 / mth',
    'living_cost_inr' => '~₹10.1L – ₹13.2L for 9 months (Outside vs London)',
    'visa_fee_local' => '£490',
    'visa_fee_inr' => 'Paid outside the UK (~₹54,000)',
    'weekly_budget_local' => '£12,000 – £25,000',
    'weekly_budget_inr' => 'Average Tuition (MBA/Medical higher)',
    'earnings_potential_local' => '£776 / year',
    'earnings_potential_inr' => 'Mandatory NHS health surcharge (~₹85,000)',
    'upcoming_intakes' => "Autumn (Sep/Oct) | Primary Intake\nSpring (Jan/Feb) | Secondary Intake\nSummer (May) | Limited Availability",
    'demand_careers' => "Artificial Intelligence\nBusiness Management\nData Science\nCyber Security\nMedicine & Healthcare",
    'travel_hours' => "Approx 9 - 14 hours (Depending on route and origin city)"
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
    WHERE `slug` = 'uk'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "UK DB updated successfully.\n";

// Update UK Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'uk'");
$stmt->execute();
$uk = $stmt->fetch();

if ($uk) {
    $countryId = $uk['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $ukUnis = [
        [
            'name' => 'Imperial College London',
            'qs_ranking' => '#2 Globally',
            'specialization' => 'Science, Engineering, Business',
            'courses' => [
                ['name' => 'MSc in Computing', 'duration' => '1 Year', 'tuition_fee' => '£35,000', 'intakes' => 'September'],
                ['name' => 'MSc in Management', 'duration' => '1 Year', 'tuition_fee' => '£33,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Oxford',
            'qs_ranking' => '#3 Globally',
            'specialization' => 'Research, Global Leadership',
            'courses' => [
                ['name' => 'MSc in Computer Science', 'duration' => '1 Year', 'tuition_fee' => '£32,000', 'intakes' => 'October'],
                ['name' => 'MBA', 'duration' => '1 Year', 'tuition_fee' => '£71,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Cambridge',
            'qs_ranking' => '#5 Globally',
            'specialization' => 'Innovation, Science',
            'courses' => [
                ['name' => 'Master of Finance', 'duration' => '1 Year', 'tuition_fee' => '£51,000', 'intakes' => 'September'],
                ['name' => 'MPhil in Machine Learning', 'duration' => '1 Year', 'tuition_fee' => '£35,000', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'UCL – University College London',
            'qs_ranking' => '#9 Globally',
            'specialization' => 'Multidisciplinary Excellence',
            'courses' => [
                ['name' => 'MSc in Data Science', 'duration' => '1 Year', 'tuition_fee' => '£38,000', 'intakes' => 'September'],
                ['name' => 'MSc in Finance', 'duration' => '1 Year', 'tuition_fee' => '£41,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Edinburgh',
            'qs_ranking' => '#27 Globally',
            'specialization' => 'AI, Social Sciences',
            'courses' => [
                ['name' => 'MSc in Artificial Intelligence', 'duration' => '1 Year', 'tuition_fee' => '£38,500', 'intakes' => 'September'],
                ['name' => 'MSc in Business Analytics', 'duration' => '1 Year', 'tuition_fee' => '£28,000', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($ukUnis as $uniData) {
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
    echo "UK Universities and Courses updated successfully.\n";
} else {
    echo "UK not found in DB.\n";
}
?>
