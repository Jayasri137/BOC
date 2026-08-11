<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Affordable Excellence: Public university tuition starts at just €726 per semester (~₹65,000).',
    'roi_priority' => 'Stay-back: 12-Month Job Seeker Visa after graduation.',
    'roi_wage' => 'Work Rights: International students can work up to 20 hours/week from Day 1.',
    'roi_qs' => 'Global Rankings: Home to University of Vienna (#152) and TU Wien (#197).',
    'living_cost_local' => '€722.58 - €1,308.39 / month',
    'living_cost_inr' => 'Approx €8,671 - €15,700 / year (~₹7.8L - ₹14.1L)',
    'visa_fee_local' => '€160',
    'visa_fee_inr' => 'Residence Permit processing fee approx (~₹14.5k)',
    'weekly_budget_local' => '€726.72',
    'weekly_budget_inr' => 'Standard regulated tuition per semester (~₹65k)',
    'earnings_potential_local' => '€518.44 / month',
    'earnings_potential_inr' => 'Tax-free marginal earnings limit',
    'upcoming_intakes' => "Winter (October) | Primary Intake\nSummer (March) | Secondary Intake",
    'demand_careers' => "Automotive Engineering\nRenewable Energy\nRobotics\nArtificial Intelligence\nData Science",
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
    WHERE `slug` = 'austria'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Austria DB updated successfully.\n";

// Update Austria Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'austria'");
$stmt->execute();
$austria = $stmt->fetch();

if ($austria) {
    $countryId = $austria['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $austriaUnis = [
        [
            'name' => 'University of Vienna',
            'qs_ranking' => '#152 Globally',
            'specialization' => 'Law, Humanities, Data Science',
            'courses' => [
                ['name' => 'MSc in Data Science', 'duration' => '2 Years', 'tuition_fee' => '€726 / semester', 'intakes' => 'October'],
                ['name' => 'MA in International Relations', 'duration' => '2 Years', 'tuition_fee' => '€726 / semester', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'TU Wien (Vienna University of Technology)',
            'qs_ranking' => '#197 Globally',
            'specialization' => 'Robotics, Architecture, Engineering',
            'courses' => [
                ['name' => 'MSc in Computer Science', 'duration' => '2 Years', 'tuition_fee' => '€726 / semester', 'intakes' => 'October'],
                ['name' => 'MSc in Mechanical Engineering', 'duration' => '2 Years', 'tuition_fee' => '€726 / semester', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'University of Innsbruck',
            'qs_ranking' => 'Global Recognition',
            'specialization' => 'Physics, Business, Mountain Research',
            'courses' => [
                ['name' => 'MSc in Strategic Management', 'duration' => '2 Years', 'tuition_fee' => '€726 / semester', 'intakes' => 'October, March'],
                ['name' => 'MSc in Physics', 'duration' => '2 Years', 'tuition_fee' => '€726 / semester', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'TU Graz',
            'qs_ranking' => 'Technology Excellence',
            'specialization' => 'Automotive Engineering, Computer Science',
            'courses' => [
                ['name' => 'MSc in Automotive Engineering', 'duration' => '2 Years', 'tuition_fee' => '€726 / semester', 'intakes' => 'October'],
                ['name' => 'MSc in Renewable Energy Systems', 'duration' => '2 Years', 'tuition_fee' => '€726 / semester', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'University of Graz',
            'qs_ranking' => 'International Reputation',
            'specialization' => 'Life Sciences, Environmental Systems',
            'courses' => [
                ['name' => 'MSc in Molecular Biology', 'duration' => '2 Years', 'tuition_fee' => '€726 / semester', 'intakes' => 'October'],
                ['name' => 'MSc in Environmental Systems Science', 'duration' => '2 Years', 'tuition_fee' => '€726 / semester', 'intakes' => 'October']
            ]
        ]
    ];
    
    foreach ($austriaUnis as $uniData) {
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
    echo "Austria Universities and Courses updated successfully.\n";
} else {
    echo "Austria not found in DB.\n";
}
?>
