<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Central Asian Hub: Modern innovation & the strategic bridge to global medicine with 100% NMC compliant programs.',
    'roi_priority' => 'NMC Compliance Mastery: Bluestone focuses on Rule 5 Compliance, ensuring 54 months theory + 12 months internship alignment.',
    'roi_wage' => 'Digital Residency: QR-coded residence cards for faster processing. Simplified work permits for IT & Healthcare graduates.',
    'roi_qs' => 'Elite Standing: Al-Farabi Kazakh National University (#1 in Central Asia). High ROI medical education messaging.',
    'living_cost_local' => '$3,000 / year',
    'living_cost_inr' => 'Approx. ₹25k – ₹50k monthly living funds',
    'visa_fee_local' => '$200',
    'visa_fee_inr' => 'Multiple Entry Visa government fee (~₹16,600)',
    'weekly_budget_local' => '$300 – $600',
    'weekly_budget_inr' => 'Estimated monthly student maintenance budget',
    'earnings_potential_local' => '$3,500 – $5,000',
    'earnings_potential_inr' => 'Average annual MBBS Tuition Fees (NMC compliant)',
    'upcoming_intakes' => "September | Main Intake\nFebruary | Limited Intake",
    'demand_careers' => "MBBS / Medicine\nComputer Science & IT\nEngineering (Mechanical/Civil/Electrical)\nData Science & AI\nAviation & Energy Studies",
    'travel_hours' => "Approx 3 - 6 hours (Direct from Delhi/Mumbai)"
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
    WHERE `slug` = 'kazakhstan'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Kazakhstan DB updated successfully.\n";

// Update Kazakhstan Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'kazakhstan'");
$stmt->execute();
$kaz = $stmt->fetch();

if ($kaz) {
    $countryId = $kaz['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $kazUnis = [
        [
            'name' => 'Al-Farabi Kazakh National University',
            'qs_ranking' => '#1 in Central Asia',
            'specialization' => 'Science & International Relations',
            'courses' => [
                ['name' => 'MD Medicine (NMC Compliant)', 'duration' => '6 Years', 'tuition_fee' => '$4,500', 'intakes' => 'September'],
                ['name' => 'BSc in Computer Science', 'duration' => '4 Years', 'tuition_fee' => '$2,500', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Asfendiyarov Kazakh National Medical University',
            'qs_ranking' => 'Top Medical Hub',
            'specialization' => 'MBBS & Clinical Medicine',
            'courses' => [
                ['name' => 'MD Medicine (6-year program)', 'duration' => '6 Years', 'tuition_fee' => '$5,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Nazarbayev University',
            'qs_ranking' => 'Top Research Hub',
            'specialization' => 'Research & Engineering',
            'courses' => [
                ['name' => 'MSc in Computer Science', 'duration' => '2 Years', 'tuition_fee' => '$4,000', 'intakes' => 'September'],
                ['name' => 'BEng in Electrical Engineering', 'duration' => '4 Years', 'tuition_fee' => '$3,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Satbayev University',
            'qs_ranking' => 'Technical Leader',
            'specialization' => 'Technical & Engineering',
            'courses' => [
                ['name' => 'BEng in Mechanical Engineering', 'duration' => '4 Years', 'tuition_fee' => '$2,500', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'L.N. Gumilyov Eurasian National University',
            'qs_ranking' => 'Technical Hub',
            'specialization' => 'Engineering & Social Sciences',
            'courses' => [
                ['name' => 'MA in International Relations', 'duration' => '2 Years', 'tuition_fee' => '$2,000', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($kazUnis as $uniData) {
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
    echo "Kazakhstan Universities and Courses updated successfully.\n";
} else {
    echo "Kazakhstan not found in DB.\n";
}
?>
