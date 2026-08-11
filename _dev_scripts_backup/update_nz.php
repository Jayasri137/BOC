<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Global Standing: All 8 New Zealand universities are ranked within the World’s Top 3%.',
    'roi_priority' => 'Stay-back Opportunity: Up to 3 Years Post-Study Work Visa for eligible degree holders.',
    'roi_wage' => 'Peace & Safety: Ranked as the #4 Most Peaceful Country globally.',
    'roi_qs' => 'Graduate Success: 95% of graduates secure employment within 6 months.',
    'living_cost_local' => '$20,000 / year',
    'living_cost_inr' => 'Mandatory benchmark for visa approval (~₹10.2 Lakhs)',
    'visa_fee_local' => '$750',
    'visa_fee_inr' => 'Includes Conservation & Tourism Levy',
    'weekly_budget_local' => '$25,000 – $45,000 (UG)',
    'weekly_budget_inr' => 'Competitive tuition for globally ranked universities',
    'earnings_potential_local' => '$20,000 – $37,000 (PG)',
    'earnings_potential_inr' => 'Strong ROI for Business and IT programs',
    'upcoming_intakes' => "Intake 1 (Feb) | Deadline: Sept 2026\nIntake 2 (Jul) | Deadline: March 2027",
    'demand_careers' => "Civil Engineering\nClinical Psychology\nSoftware Development\nEnvironmental Science\nCyber Security\nAgriculture",
    'travel_hours' => "Approx 16 - 22 hours (Depending on route and stopovers)"
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
    WHERE `slug` = 'new-zealand'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "New Zealand DB updated successfully.\n";

// Update New Zealand Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'new-zealand'");
$stmt->execute();
$newzealand = $stmt->fetch();

if ($newzealand) {
    $countryId = $newzealand['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $nzUnis = [
        [
            'name' => 'University of Auckland',
            'qs_ranking' => '#65 Globally',
            'specialization' => 'Engineering, Business',
            'courses' => [
                ['name' => 'Master of Engineering', 'duration' => '1.5 Years', 'tuition_fee' => 'NZD 45,000', 'intakes' => 'February, July'],
                ['name' => 'Master of Business Management', 'duration' => '1.5 Years', 'tuition_fee' => 'NZD 42,000', 'intakes' => 'February, July']
            ]
        ],
        [
            'name' => 'University of Otago',
            'qs_ranking' => '#214 Globally',
            'specialization' => 'Medicine, Health Sciences',
            'courses' => [
                ['name' => 'Master of Health Sciences', 'duration' => '2 Years', 'tuition_fee' => 'NZD 38,000', 'intakes' => 'February'],
                ['name' => 'Master of Public Health', 'duration' => '1 Year', 'tuition_fee' => 'NZD 40,000', 'intakes' => 'February']
            ]
        ],
        [
            'name' => 'University of Waikato',
            'qs_ranking' => '#235 Globally',
            'specialization' => 'Cyber Security, Management',
            'courses' => [
                ['name' => 'Master of Cyber Security', 'duration' => '1.5 Years', 'tuition_fee' => 'NZD 35,000', 'intakes' => 'February, July'],
                ['name' => 'Master of Management', 'duration' => '1 Year', 'tuition_fee' => 'NZD 32,000', 'intakes' => 'February']
            ]
        ],
        [
            'name' => 'Massey University',
            'qs_ranking' => '#239 Globally',
            'specialization' => 'Agriculture, Aviation',
            'courses' => [
                ['name' => 'Master of Agriculture', 'duration' => '1.5 Years', 'tuition_fee' => 'NZD 36,000', 'intakes' => 'February, July'],
                ['name' => 'Master of Veterinary Studies', 'duration' => '1 Year', 'tuition_fee' => 'NZD 42,000', 'intakes' => 'February']
            ]
        ],
        [
            'name' => 'Victoria University of Wellington',
            'qs_ranking' => '#244 Globally',
            'specialization' => 'Law, Public Policy',
            'courses' => [
                ['name' => 'Master of Laws', 'duration' => '1 Year', 'tuition_fee' => 'NZD 34,000', 'intakes' => 'February, July'],
                ['name' => 'Master of Public Policy', 'duration' => '1.5 Years', 'tuition_fee' => 'NZD 31,000', 'intakes' => 'February, July']
            ]
        ]
    ];
    
    foreach ($nzUnis as $uniData) {
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
    echo "New Zealand Universities and Courses updated successfully.\n";
} else {
    echo "New Zealand not found in DB.\n";
}
?>
