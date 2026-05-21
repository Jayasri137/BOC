<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Baltic Tech-Hub: Lithuania is the EU’s leading Fintech licensing hub and a fast-growing startup ecosystem.',
    'roi_priority' => 'Residency Win: Direct application for a Temporary Residence Permit (TRP) via MIGRIS (No National Visa required).',
    'roi_wage' => 'Work Freedom: Full-time work rights (40h/week) for Master’s and PhD students throughout their studies.',
    'roi_qs' => 'Stay-back: 12-Month post-study residence permit to find employment or launch a startup.',
    'living_cost_local' => '€8,077 / year',
    'living_cost_inr' => 'Approx. ₹7.2 Lakhs including travel buffer for TRP',
    'visa_fee_local' => '€160',
    'visa_fee_inr' => 'Temporary Residence Permit (TRP) application fee (~₹14,500)',
    'weekly_budget_local' => '€577',
    'weekly_budget_inr' => 'Monthly living funds required for MIGRIS (~₹52k)',
    'earnings_potential_local' => '€2,500 – €6,500',
    'earnings_potential_inr' => 'Average annual Tuition Fees',
    'upcoming_intakes' => "Autumn (September) | Primary Intake\nSpring (February) | Secondary Intake",
    'demand_careers' => "Fintech\nArtificial Intelligence\nCyber Security\nRobotics\nSoftware Engineering",
    'travel_hours' => "Approx 9 - 14 hours (Depending on route and city)"
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
    WHERE `slug` = 'lithuania'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Lithuania DB updated successfully.\n";

// Update Lithuania Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'lithuania'");
$stmt->execute();
$lithuania = $stmt->fetch();

if ($lithuania) {
    $countryId = $lithuania['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $lithuaniaUnis = [
        [
            'name' => 'Vilnius University',
            'qs_ranking' => '#446 Globally',
            'specialization' => 'Research, Science, Humanities',
            'courses' => [
                ['name' => 'MSc in Fintech', 'duration' => '1.5 Years', 'tuition_fee' => '€4,000', 'intakes' => 'September'],
                ['name' => 'MSc in Data Science', 'duration' => '2 Years', 'tuition_fee' => '€4,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Kaunas University of Technology (KTU)',
            'qs_ranking' => 'Tech & Innovation Leader',
            'specialization' => 'Robotics, AI, Sustainable Technology',
            'courses' => [
                ['name' => 'MSc in Artificial Intelligence', 'duration' => '2 Years', 'tuition_fee' => '€3,500', 'intakes' => 'September, February'],
                ['name' => 'MSc in Robotics', 'duration' => '2 Years', 'tuition_fee' => '€3,500', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'VILNIUS TECH',
            'qs_ranking' => 'Engineering Excellence',
            'specialization' => 'Engineering, Architecture, Design',
            'courses' => [
                ['name' => 'MSc in Software Engineering', 'duration' => '2 Years', 'tuition_fee' => '€3,800', 'intakes' => 'September'],
                ['name' => 'MSc in Cyber Security', 'duration' => '2 Years', 'tuition_fee' => '€3,800', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Vytautas Magnus University (VMU)',
            'qs_ranking' => 'International Academic Reputation',
            'specialization' => 'Arts, Humanities, International Relations',
            'courses' => [
                ['name' => 'MA in Diplomacy and International Relations', 'duration' => '2 Years', 'tuition_fee' => '€3,000', 'intakes' => 'September'],
                ['name' => 'MSc in Business Analytics', 'duration' => '2 Years', 'tuition_fee' => '€3,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Mykolas Romeris University (MRU)',
            'qs_ranking' => 'Public Policy & Law Specialist',
            'specialization' => 'Law, Social Sciences, Public Administration',
            'courses' => [
                ['name' => 'LLM in European and International Business Law', 'duration' => '1 Year', 'tuition_fee' => '€3,500', 'intakes' => 'September, February'],
                ['name' => 'MA in Public Administration', 'duration' => '1.5 Years', 'tuition_fee' => '€3,000', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($lithuaniaUnis as $uniData) {
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
    echo "Lithuania Universities and Courses updated successfully.\n";
} else {
    echo "Lithuania not found in DB.\n";
}
?>
