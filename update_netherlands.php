<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'English Mastery: Over 2,100+ programs taught entirely in English — the highest in non-native Europe.',
    'roi_priority' => 'Stay-back: 1 Year “Zoekjaar” (Orientation Year) allowing graduates to work freely.',
    'roi_wage' => 'Global Rankings: 10+ Dutch universities ranked within the World’s Top 200 (QS 2026).',
    'roi_qs' => 'Quality of Life: Consistently ranked among Global Top 5 for happiness and safety.',
    'living_cost_local' => '€15,000 – €18,000 / year',
    'living_cost_inr' => 'Required for visa approval (~₹14.2L – ₹16.3L)',
    'visa_fee_local' => '€254',
    'visa_fee_inr' => 'Official IND residence permit fee (~₹23k)',
    'weekly_budget_local' => '€1,000 – €1,500',
    'weekly_budget_inr' => 'Monthly Living Cost (inc. Health Insurance)',
    'earnings_potential_local' => '€3,122 / month',
    'earnings_potential_inr' => 'Highly Skilled Migrant salary threshold (Approx)',
    'upcoming_intakes' => "Fall (September) | Primary Intake\nSpring (February) | Limited Courses",
    'demand_careers' => "Artificial Intelligence\nWater Management\nSustainable Engineering\nData Science\nCyber Security",
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
    WHERE `slug` = 'netherlands'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Netherlands DB updated successfully.\n";

// Update Netherlands Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'netherlands'");
$stmt->execute();
$netherlands = $stmt->fetch();

if ($netherlands) {
    $countryId = $netherlands['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $netherlandsUnis = [
        [
            'name' => 'University of Amsterdam (UvA)',
            'qs_ranking' => '#55 Globally',
            'specialization' => 'Communication, Media, Psychology',
            'courses' => [
                ['name' => 'MSc in Communication Science', 'duration' => '1 Year', 'tuition_fee' => '€18,500', 'intakes' => 'September'],
                ['name' => 'MSc in Data Science & AI', 'duration' => '1 Year', 'tuition_fee' => '€20,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Delft University of Technology (TU Delft)',
            'qs_ranking' => '#88 Globally',
            'specialization' => 'Engineering, Robotics, Water Management',
            'courses' => [
                ['name' => 'MSc in Aerospace Engineering', 'duration' => '2 Years', 'tuition_fee' => '€21,000', 'intakes' => 'September'],
                ['name' => 'MSc in Robotics', 'duration' => '2 Years', 'tuition_fee' => '€21,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Leiden University',
            'qs_ranking' => '#75 Globally',
            'specialization' => 'International Law, Politics',
            'courses' => [
                ['name' => 'LLM in International Law', 'duration' => '1 Year', 'tuition_fee' => '€19,500', 'intakes' => 'September, February'],
                ['name' => 'MSc in International Relations', 'duration' => '1 Year', 'tuition_fee' => '€19,500', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Groningen',
            'qs_ranking' => '#78 Globally',
            'specialization' => 'Research & Innovation',
            'courses' => [
                ['name' => 'MSc in Energy for Society', 'duration' => '1 Year', 'tuition_fee' => '€17,000', 'intakes' => 'September'],
                ['name' => 'MSc in Biomedical Sciences', 'duration' => '2 Years', 'tuition_fee' => '€17,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Eindhoven University of Technology (TU/e)',
            'qs_ranking' => 'Industry Leader',
            'specialization' => 'Technology & Industry Collaboration',
            'courses' => [
                ['name' => 'MSc in Embedded Systems', 'duration' => '2 Years', 'tuition_fee' => '€18,000', 'intakes' => 'September'],
                ['name' => 'MSc in Sustainable Energy Technology', 'duration' => '2 Years', 'tuition_fee' => '€18,000', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($netherlandsUnis as $uniData) {
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
    echo "Netherlands Universities and Courses updated successfully.\n";
} else {
    echo "Netherlands not found in DB.\n";
}
?>
