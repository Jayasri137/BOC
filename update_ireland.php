<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'English Advantage: The only native English-speaking country in the Eurozone — no language barrier for Indian students.',
    'roi_priority' => 'Stay-back Opportunity: 2 Years (Stamp 1G) for all Master’s graduates.',
    'roi_wage' => 'Tech Headquarters: European base for Google, Apple, Meta, Intel, and other global innovators.',
    'roi_qs' => 'Student Earnings: Minimum wage increased to €14.15/hr (Effective January 1, 2026).',
    'living_cost_local' => '€10,000 / year',
    'living_cost_inr' => 'Mandatory financial proof (~₹9.2 Lakhs)',
    'visa_fee_local' => '€60',
    'visa_fee_inr' => 'Non-refundable Single Entry Study Visa fee',
    'weekly_budget_local' => '€10,000 – €25,000',
    'weekly_budget_inr' => 'Strong ROI for Business and STEM Master’s programs',
    'earnings_potential_local' => '€1,000 – €1,500 / month',
    'earnings_potential_inr' => 'Regional cities are more affordable than Dublin',
    'upcoming_intakes' => "Semester 1 (September) | Window: February – July 2026\nSemester 2 (January) | Recommended Deadline: September 2026",
    'demand_careers' => "Artificial Intelligence\nData Science\nPharmaceutical Sciences\nBiotechnology\nCyber Security\nBusiness Analytics",
    'travel_hours' => "Approx 10 - 16 hours (Depending on route and stopovers)"
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
    WHERE `slug` = 'ireland'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Ireland DB updated successfully.\n";

// Update Ireland Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'ireland'");
$stmt->execute();
$ireland = $stmt->fetch();

if ($ireland) {
    $countryId = $ireland['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $irelandUnis = [
        [
            'name' => 'Trinity College Dublin (TCD)',
            'qs_ranking' => '#75 Globally',
            'specialization' => 'Law, Arts, Humanities',
            'courses' => [
                ['name' => 'MSc in Computer Science', 'duration' => '1 Year', 'tuition_fee' => '€25,000', 'intakes' => 'September'],
                ['name' => 'LLM (Master of Laws)', 'duration' => '1 Year', 'tuition_fee' => '€21,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University College Dublin (UCD)',
            'qs_ranking' => '#171 Globally',
            'specialization' => 'Business, Engineering',
            'courses' => [
                ['name' => 'MSc in Data Analytics', 'duration' => '1 Year', 'tuition_fee' => '€22,000', 'intakes' => 'September'],
                ['name' => 'MSc in Engineering', 'duration' => '1 Year', 'tuition_fee' => '€24,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Galway',
            'qs_ranking' => '#289 Globally',
            'specialization' => 'MedTech, Marine Research',
            'courses' => [
                ['name' => 'MSc in Biotechnology', 'duration' => '1 Year', 'tuition_fee' => '€18,000', 'intakes' => 'September'],
                ['name' => 'MSc in MedTech Innovation', 'duration' => '1 Year', 'tuition_fee' => '€19,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University College Cork (UCC)',
            'qs_ranking' => '#292 Globally',
            'specialization' => 'Sustainability, Pharmacy',
            'courses' => [
                ['name' => 'MSc in Pharmaceutical Sciences', 'duration' => '1 Year', 'tuition_fee' => '€20,000', 'intakes' => 'September'],
                ['name' => 'MSc in Environmental Science', 'duration' => '1 Year', 'tuition_fee' => '€19,500', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Limerick (UL)',
            'qs_ranking' => '#426 Globally',
            'specialization' => 'Employability, Technology',
            'courses' => [
                ['name' => 'MSc in Software Engineering', 'duration' => '1 Year', 'tuition_fee' => '€17,000', 'intakes' => 'September'],
                ['name' => 'MSc in Business Management', 'duration' => '1 Year', 'tuition_fee' => '€16,500', 'intakes' => 'September, January']
            ]
        ]
    ];
    
    foreach ($irelandUnis as $uniData) {
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
    echo "Ireland Universities and Courses updated successfully.\n";
} else {
    echo "Ireland not found in DB.\n";
}
?>
