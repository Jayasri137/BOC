<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Global Powerhouse: Home to Peking University (#2 in Asia) and the elite C9 League research ecosystem.',
    'roi_priority' => 'MBBS Choice: 45+ universities offering English-medium medical programs (NMC-aligned options available).',
    'roi_wage' => 'JW202 System: Full guidance on JW201/JW202 issuance (mandatory for X1 visa) and CSC scholarship access.',
    'roi_qs' => 'Tech Dominance: Global leader in AI, 5G, Robotics, and Semiconductor research (Huawei, Alibaba, Tencent).',
    'living_cost_local' => '26,400 – 51,600 CNY / year',
    'living_cost_inr' => 'Approx. ₹3L – ₹6L for annual living costs',
    'visa_fee_local' => '₹15,000 – ₹18,000',
    'visa_fee_inr' => 'Includes VFS + stamping fees for X1 visa',
    'weekly_budget_local' => '2,200 – 4,300 CNY',
    'weekly_budget_inr' => 'Estimated monthly student budget (~₹25k - ₹50k)',
    'earnings_potential_local' => '20,000 – 40,000 CNY',
    'earnings_potential_inr' => 'Average annual non-medical Tuition Fees',
    'upcoming_intakes' => "September | Main Intake\nSpring | Limited programs",
    'demand_careers' => "Artificial Intelligence & Robotics\nMBBS & Health Sciences\nSemiconductor Engineering\nInternational Trade & Finance\nBiotechnology",
    'travel_hours' => "Approx 6 - 9 hours (Direct from Delhi/Mumbai)"
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
    WHERE `slug` = 'china'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "China DB updated successfully.\n";

// Update China Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'china'");
$stmt->execute();
$china = $stmt->fetch();

if ($china) {
    $countryId = $china['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $chinaUnis = [
        [
            'name' => 'Peking University',
            'qs_ranking' => '#2 Asia',
            'specialization' => 'Medicine, Physics, Humanities',
            'courses' => [
                ['name' => 'MBBS (English Medium)', 'duration' => '6 Years', 'tuition_fee' => '45,000 CNY', 'intakes' => 'September'],
                ['name' => 'MSc in Physics', 'duration' => '2 Years', 'tuition_fee' => '30,000 CNY', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Tsinghua University',
            'qs_ranking' => 'MIT of China',
            'specialization' => 'Engineering, CS, AI',
            'courses' => [
                ['name' => 'MSc in Artificial Intelligence', 'duration' => '2 Years', 'tuition_fee' => '35,000 CNY', 'intakes' => 'September'],
                ['name' => 'BEng in Mechanical Engineering', 'duration' => '4 Years', 'tuition_fee' => '30,000 CNY', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Fudan University',
            'qs_ranking' => 'Top Business Hub',
            'specialization' => 'Business, Medicine, International Relations',
            'courses' => [
                ['name' => 'Global MBA', 'duration' => '2 Years', 'tuition_fee' => '40,000 CNY', 'intakes' => 'September'],
                ['name' => 'MBBS', 'duration' => '6 Years', 'tuition_fee' => '42,000 CNY', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Zhejiang University',
            'qs_ranking' => 'Engineering Research Leader',
            'specialization' => 'MBBS + Engineering research',
            'courses' => [
                ['name' => 'BEng in Semiconductor Technology', 'duration' => '4 Years', 'tuition_fee' => '28,000 CNY', 'intakes' => 'September'],
                ['name' => 'MBBS (NMC Compliant)', 'duration' => '6 Years', 'tuition_fee' => '38,000 CNY', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Shanghai Jiao Tong University',
            'qs_ranking' => 'Marine & Robotics Excellence',
            'specialization' => 'Robotics, Business Excellence',
            'courses' => [
                ['name' => 'MSc in Robotics', 'duration' => '2 Years', 'tuition_fee' => '32,000 CNY', 'intakes' => 'September'],
                ['name' => 'BBA in Supply Chain Management', 'duration' => '4 Years', 'tuition_fee' => '26,000 CNY', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($chinaUnis as $uniData) {
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
    echo "China Universities and Courses updated successfully.\n";
} else {
    echo "China not found in DB.\n";
}
?>
