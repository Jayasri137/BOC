<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Hallyu Innovation: High-tech superpower offering a structured growth ladder from education to global tech careers.',
    'roi_priority' => 'Elite Standing: Seoul National University (#39 Globally) and KAIST lead Asia’s top STEM institutions.',
    'roi_wage' => 'K-CORE Visa: New skilled transition visa for graduates. Work up to 35 hours/week during sessions and full-time on vacations.',
    'roi_qs' => 'Industry Link: Strong pipelines into Samsung, LG, Hyundai, Naver, and Kakao. talent-driven immigration system.',
    'living_cost_local' => '$9,000 – $10,000 / year',
    'living_cost_inr' => 'Approx. ₹7.5L – ₹8.5L required proof-of-funds for D-2 visa',
    'visa_fee_local' => '₩30,000',
    'visa_fee_inr' => 'Mandatory Alien Registration Card (ARC) fee (~₹1,900)',
    'weekly_budget_local' => '₩10,320 / hour',
    'weekly_budget_inr' => 'Updated 2026 minimum wage (~₹660/hour)',
    'earnings_potential_local' => '₩2.4M – ₩8M / semester',
    'earnings_potential_inr' => 'Tuition varies between subsidized National and Private STEM universities',
    'upcoming_intakes' => "March | Main Intake (Apply Sep-Nov)\nSeptember | Secondary Intake (Apply May-Jun)",
    'demand_careers' => "Artificial Intelligence & Robotics\nSemiconductor & Electrical Engineering\nGame Design & Digital Media\nAutomotive Engineering\nBiotechnology",
    'travel_hours' => "Approx 7 - 10 hours (Direct from Delhi/Mumbai)"
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
    WHERE `slug` = 'south-korea'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "South Korea DB updated successfully.\n";

// Update South Korea Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'south-korea'");
$stmt->execute();
$korea = $stmt->fetch();

if ($korea) {
    $countryId = $korea['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $koreaUnis = [
        [
            'name' => 'Seoul National University',
            'qs_ranking' => '#39 Globally',
            'specialization' => 'Engineering, Medicine, Policy',
            'courses' => [
                ['name' => 'MSc in Computer Science and Engineering', 'duration' => '2 Years', 'tuition_fee' => '₩4,500,000', 'intakes' => 'March, September'],
                ['name' => 'MBA (Global)', 'duration' => '1.5 Years', 'tuition_fee' => '₩15,000,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'KAIST',
            'qs_ranking' => 'Asia\'s Top STEM',
            'specialization' => 'AI, Robotics, Aerospace',
            'courses' => [
                ['name' => 'MSc in Robotics', 'duration' => '2 Years', 'tuition_fee' => '₩6,000,000', 'intakes' => 'March, September'],
                ['name' => 'MSc in Artificial Intelligence', 'duration' => '2 Years', 'tuition_fee' => '₩6,000,000', 'intakes' => 'March']
            ]
        ],
        [
            'name' => 'Yonsei University',
            'qs_ranking' => '#226 Globally',
            'specialization' => 'Business, Global Studies',
            'courses' => [
                ['name' => 'BA in Global Studies', 'duration' => '4 Years', 'tuition_fee' => '₩7,500,000', 'intakes' => 'March, September'],
                ['name' => 'MSc in Bio-Convergence', 'duration' => '2 Years', 'tuition_fee' => '₩8,000,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Korea University',
            'qs_ranking' => '#251 Globally',
            'specialization' => 'Law, Business',
            'courses' => [
                ['name' => 'Master of Global Business', 'duration' => '1 Year', 'tuition_fee' => '₩12,000,000', 'intakes' => 'March, September'],
                ['name' => 'LLM in International Legal Affairs', 'duration' => '1 Year', 'tuition_fee' => '₩10,000,000', 'intakes' => 'March']
            ]
        ],
        [
            'name' => 'Hanyang University',
            'qs_ranking' => '#168 Globally',
            'specialization' => 'Engineering & Automotive Tech',
            'courses' => [
                ['name' => 'BEng in Future Automotive Engineering', 'duration' => '4 Years', 'tuition_fee' => '₩6,500,000', 'intakes' => 'March'],
                ['name' => 'MSc in Electronic Engineering', 'duration' => '2 Years', 'tuition_fee' => '₩7,000,000', 'intakes' => 'March, September']
            ]
        ]
    ];
    
    foreach ($koreaUnis as $uniData) {
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
    echo "South Korea Universities and Courses updated successfully.\n";
} else {
    echo "South Korea not found in DB.\n";
}
?>
