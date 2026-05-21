<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Elite Rankings: Home to ETH Zurich (#7 globally) — the highest-ranked university in Continental Europe.',
    'roi_priority' => 'Low Tuition Advantage: Public university tuition ranges from CHF 400 – CHF 1,500 per semester (Approx. ₹38k – ₹1.4L).',
    'roi_wage' => 'Hospitality Excellence: Switzerland remains the world’s #1 destination for Hospitality and Luxury Management education.',
    'roi_qs' => 'Stay-back Opportunity: 6-Month dedicated job-seeker period for non-EU graduates.',
    'living_cost_local' => 'CHF 21,000 – 27,000',
    'living_cost_inr' => 'Approx. ₹20L – ₹26L required for visa approval',
    'visa_fee_local' => 'CHF 88',
    'visa_fee_inr' => 'Additional VFS charges apply (~₹10,600 total)',
    'weekly_budget_local' => 'CHF 730 – 1,220 / sem',
    'weekly_budget_inr' => 'Semester fees at ETH Zurich and similar institutions',
    'earnings_potential_local' => 'CHF 1,600 – 2,200 / mth',
    'earnings_potential_inr' => 'Includes accommodation, transport, and daily expenses',
    'upcoming_intakes' => "Fall Intake (September) | Deadline: Feb – April 2026\nSpring Intake (February) | Deadline: Sept – Oct 2026",
    'demand_careers' => "Hospitality & Luxury Management\nData Science\nRobotics & AI\nFinance & Banking\nBiotechnology",
    'travel_hours' => "Approx 8 - 14 hours (Depending on route and origin city)"
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
    WHERE `slug` = 'switzerland'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Switzerland DB updated successfully.\n";

// Update Switzerland Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'switzerland'");
$stmt->execute();
$switzerland = $stmt->fetch();

if ($switzerland) {
    $countryId = $switzerland['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $switzerlandUnis = [
        [
            'name' => 'ETH Zurich',
            'qs_ranking' => '#7 Globally',
            'specialization' => 'Science, Technology, Engineering',
            'courses' => [
                ['name' => 'MSc in Computer Science', 'duration' => '1.5 Years', 'tuition_fee' => 'CHF 730/sem', 'intakes' => 'September'],
                ['name' => 'MSc in Mechanical Engineering', 'duration' => '1.5 Years', 'tuition_fee' => 'CHF 730/sem', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'EPFL Lausanne',
            'qs_ranking' => '#22 Globally',
            'specialization' => 'Data Science, Robotics',
            'courses' => [
                ['name' => 'MSc in Data Science', 'duration' => '2 Years', 'tuition_fee' => 'CHF 780/sem', 'intakes' => 'September'],
                ['name' => 'MSc in Robotics', 'duration' => '2 Years', 'tuition_fee' => 'CHF 780/sem', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Zurich (UZH)',
            'qs_ranking' => '#100 Globally',
            'specialization' => 'Economics, Finance, Law',
            'courses' => [
                ['name' => 'MSc in Finance', 'duration' => '1.5 Years', 'tuition_fee' => 'CHF 1,220/sem', 'intakes' => 'September'],
                ['name' => 'MSc in Economics', 'duration' => '1.5 Years', 'tuition_fee' => 'CHF 1,220/sem', 'intakes' => 'September, February']
            ]
        ],
        [
            'name' => 'University of Geneva',
            'qs_ranking' => '#155 Globally',
            'specialization' => 'International Relations',
            'courses' => [
                ['name' => 'Master in International Affairs', 'duration' => '2 Years', 'tuition_fee' => 'CHF 500/sem', 'intakes' => 'September'],
                ['name' => 'MSc in Business Analytics', 'duration' => '1.5 Years', 'tuition_fee' => 'CHF 500/sem', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'EHL Hospitality Business School',
            'qs_ranking' => '#1 Globally',
            'specialization' => 'Hospitality & Luxury Management',
            'courses' => [
                ['name' => 'MSc in Global Hospitality Business', 'duration' => '1.5 Years', 'tuition_fee' => 'CHF 35,000', 'intakes' => 'September, February'],
                ['name' => 'Bachelor of Science in Hospitality', 'duration' => '4 Years', 'tuition_fee' => 'CHF 40,000/yr', 'intakes' => 'September, February']
            ]
        ]
    ];
    
    foreach ($switzerlandUnis as $uniData) {
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
    echo "Switzerland Universities and Courses updated successfully.\n";
} else {
    echo "Switzerland not found in DB.\n";
}
?>
