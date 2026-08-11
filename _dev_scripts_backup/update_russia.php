<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Practical Excellence: Access to world-class government infrastructure and elite medical education at the lowest global cost.',
    'roi_priority' => 'MBBS Authority: 100% NMC (India) compliant MBBS programs (5.8 years) recognized by WDOMS, WHO, and ECFMG.',
    'roi_wage' => 'New Skilled Visa (2026): Direct PR pathway for STEM graduates. No IELTS/TOEFL required for most English-medium programs.',
    'roi_qs' => 'Elite Engineering: Bauman Moscow State Technical University and MSU lead global aerospace and robotics research.',
    'living_cost_local' => '₹1.5 Lakhs – ₹2.0 Lakhs / year',
    'living_cost_inr' => 'Approx. ₹12k – ₹16k monthly (hostel + Indian mess available)',
    'visa_fee_local' => '₹4,000 – ₹12,000',
    'visa_fee_inr' => 'VFS processing cost for student visa',
    'weekly_budget_local' => '₹3,000 – ₹4,500',
    'weekly_budget_inr' => 'Estimated weekly student living and food cost',
    'earnings_potential_local' => '₹2.5L – ₹8L',
    'earnings_potential_inr' => 'Average annual non-medical Tuition Fees (Government Universities)',
    'upcoming_intakes' => "September | Main Intake\nWindow: May – July",
    'demand_careers' => "Medicine (MBBS)\nAerospace Engineering\nRobotics & AI\nNuclear Engineering\nComputer Science",
    'travel_hours' => "Approx 6 - 8 hours (Direct from major Indian cities)"
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
    WHERE `slug` = 'russia'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Russia DB updated successfully.\n";

// Update Russia Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'russia'");
$stmt->execute();
$russia = $stmt->fetch();

if ($russia) {
    $countryId = $russia['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $russiaUnis = [
        [
            'name' => 'Lomonosov Moscow State University',
            'qs_ranking' => 'Russia\'s #1',
            'specialization' => 'Mathematics, Physics, Research',
            'courses' => [
                ['name' => 'MSc in Fundamental Physics', 'duration' => '2 Years', 'tuition_fee' => '₹4,50,000', 'intakes' => 'September'],
                ['name' => 'MA in Economics', 'duration' => '2 Years', 'tuition_fee' => '₹3,50,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Bauman Moscow State Technical University',
            'qs_ranking' => 'Elite Engineering Hub',
            'specialization' => 'Robotics, Aerospace, Mechanical',
            'courses' => [
                ['name' => 'BEng in Aerospace Engineering', 'duration' => '4 Years', 'tuition_fee' => '₹3,80,000', 'intakes' => 'September'],
                ['name' => 'MSc in Robotics and Autonomy', 'duration' => '2 Years', 'tuition_fee' => '₹4,00,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Kazan Federal University',
            'qs_ranking' => 'Top MBBS Choice',
            'specialization' => 'Medicine, Health Sciences',
            'courses' => [
                ['name' => 'General Medicine (MBBS)', 'duration' => '5.8 Years', 'tuition_fee' => '₹3,20,000', 'intakes' => 'September'],
                ['name' => 'BSc in Biotechnology', 'duration' => '4 Years', 'tuition_fee' => '₹2,50,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'RUDN University',
            'qs_ranking' => 'Highly International',
            'specialization' => 'Medicine, Global Studies',
            'courses' => [
                ['name' => 'General Medicine (English Medium)', 'duration' => '5.8 Years', 'tuition_fee' => '₹6,50,000', 'intakes' => 'September'],
                ['name' => 'MA in International Relations', 'duration' => '2 Years', 'tuition_fee' => '₹3,50,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Saint Petersburg State University',
            'qs_ranking' => 'Top IT Hub',
            'specialization' => 'IT, Sciences, Humanities',
            'courses' => [
                ['name' => 'MSc in Data Science', 'duration' => '2 Years', 'tuition_fee' => '₹4,20,000', 'intakes' => 'September'],
                ['name' => 'MA in Management', 'duration' => '2 Years', 'tuition_fee' => '₹3,80,000', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($russiaUnis as $uniData) {
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
    echo "Russia Universities and Courses updated successfully.\n";
} else {
    echo "Russia not found in DB.\n";
}
?>
