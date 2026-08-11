<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Affordable Excellence: Study for ₹80,000 – ₹2.5 Lakhs/year in one of Asia’s fastest-growing economies.',
    'roi_priority' => 'MBBS Advantage: 100% NMC-compliant English-medium programs at half the cost of Indian private colleges.',
    'roi_wage' => 'DH Visa System: Fully digital, university-sponsored visa with the ability to convert to work status locally.',
    'roi_qs' => 'Fast-Track Career: Strategic hub for Semiconductor Manufacturing, FinTech, and Smart Manufacturing (Samsung, Intel, LG).',
    'living_cost_local' => '₹3.0 Lakhs – ₹5.4 Lakhs / year',
    'living_cost_inr' => 'Approx. ₹25k – ₹45k monthly student budget',
    'visa_fee_local' => '₹2,200 – ₹4,500',
    'visa_fee_inr' => 'DH Visa stamping fee depending on entry type',
    'weekly_budget_local' => '₹6,000 – ₹12,000',
    'weekly_budget_inr' => 'Estimated monthly food and basic expenses',
    'earnings_potential_local' => '₹3.5 Lakhs – ₹6 Lakhs',
    'earnings_potential_inr' => 'Average annual non-medical Tuition Fees',
    'upcoming_intakes' => "Fall (September) | Main Intake\nSpring (February) | Secondary Intake",
    'demand_careers' => "MBBS & Health Sciences\nSemiconductor Manufacturing\nArtificial Intelligence\nSoftware Engineering\nLogistics & Supply Chain",
    'travel_hours' => "Approx 4.5 - 8 hours (Direct or one-stop from major Indian cities)"
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
    WHERE `slug` = 'vietnam'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Vietnam DB updated successfully.\n";

// Update Vietnam Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'vietnam'");
$stmt->execute();
$vietnam = $stmt->fetch();

if ($vietnam) {
    $countryId = $vietnam['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $vietnamUnis = [
        [
            'name' => 'Vietnam National University, Hanoi',
            'qs_ranking' => '#158 Asia',
            'specialization' => 'Research, Engineering, Science',
            'courses' => [
                ['name' => 'BEng in Computer Science (English)', 'duration' => '4 Years', 'tuition_fee' => '₹1,50,000', 'intakes' => 'September'],
                ['name' => 'MA in International Business', 'duration' => '2 Years', 'tuition_fee' => '₹1,20,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Duy Tan University',
            'qs_ranking' => '#165 Asia',
            'specialization' => 'Hospitality & Leisure Management',
            'courses' => [
                ['name' => 'BSc in Hotel Management (English)', 'duration' => '4 Years', 'tuition_fee' => '₹1,80,000', 'intakes' => 'September, February'],
                ['name' => 'BSc in Software Engineering', 'duration' => '4 Years', 'tuition_fee' => '₹1,60,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Vietnam National University, Ho Chi Minh City',
            'qs_ranking' => '#175 Asia',
            'specialization' => 'Business & Technology',
            'courses' => [
                ['name' => 'MSc in Artificial Intelligence', 'duration' => '2 Years', 'tuition_fee' => '₹2,00,000', 'intakes' => 'September'],
                ['name' => 'BA in International Finance', 'duration' => '4 Years', 'tuition_fee' => '₹1,40,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'VinUniversity',
            'qs_ranking' => 'Elite English-Taught',
            'specialization' => 'Health Sciences, Engineering',
            'courses' => [
                ['name' => 'Doctor of Medicine (MBBS)', 'duration' => '6 Years', 'tuition_fee' => '₹5,00,000', 'intakes' => 'September'],
                ['name' => 'BSc in Mechanical Engineering', 'duration' => '4 Years', 'tuition_fee' => '₹2,50,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Ton Duc Thang University',
            'qs_ranking' => '#231 Asia',
            'specialization' => 'Innovation & Applied Sciences',
            'courses' => [
                ['name' => 'BSc in Biotechnology', 'duration' => '4 Years', 'tuition_fee' => '₹1,20,000', 'intakes' => 'September'],
                ['name' => 'MA in Public Health', 'duration' => '1.5 Years', 'tuition_fee' => '₹1,50,000', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($vietnamUnis as $uniData) {
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
    echo "Vietnam Universities and Courses updated successfully.\n";
} else {
    echo "Vietnam not found in DB.\n";
}
?>
