<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Precision Education: Global leader in Robotics, Automotive Engineering, AI, and Semiconductor Tech.',
    'roi_priority' => 'Visa success (~95%) through structured COE process. Designated Activities Visa for 1-year job search after graduation.',
    'roi_wage' => 'Work & Study: 28 hours/week during sessions and 40 hours during holidays. Average earnings: ¥1,000 – ¥1,500/hour.',
    'roi_qs' => 'Elite Heritage: Home to University of Tokyo (#36) and Kyoto University (#62). Growing English-taught G30 programs.',
    'living_cost_local' => '¥2,000,000 / year',
    'living_cost_inr' => 'Approx. ₹11.5L – ₹12.5L proof of funds for COE',
    'visa_fee_local' => '¥3,000',
    'visa_fee_inr' => 'Paid at Embassy/VFS (~₹1,750)',
    'weekly_budget_local' => '¥120,000 – ¥150,000',
    'weekly_budget_inr' => 'Estimated monthly student budget (~₹68k - ₹85k)',
    'earnings_potential_local' => '¥535,800 – ¥820,000',
    'earnings_potential_inr' => 'Average annual non-medical Tuition Fees (Public Universities)',
    'upcoming_intakes' => "April | Main Intake\nSept/Oct | Secondary Intake",
    'demand_careers' => "Robotics & Automation\nAutomotive Engineering\nSemiconductor Technology\nArtificial Intelligence\nGame Development",
    'travel_hours' => "Approx 8 - 14 hours (Direct or one-stop from major Indian cities)"
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
    WHERE `slug` = 'japan'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Japan DB updated successfully.\n";

// Update Japan Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'japan'");
$stmt->execute();
$japan = $stmt->fetch();

if ($japan) {
    $countryId = $japan['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $japanUnis = [
        [
            'name' => 'The University of Tokyo',
            'qs_ranking' => '#36 Globally',
            'specialization' => 'Engineering, Physics, AI',
            'courses' => [
                ['name' => 'MSc in Intelligent Services Engineering', 'duration' => '2 Years', 'tuition_fee' => '¥535,800', 'intakes' => 'April, October'],
                ['name' => 'BSc in Environmental Sciences (English)', 'duration' => '4 Years', 'tuition_fee' => '¥535,800', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'Kyoto University',
            'qs_ranking' => '#62 Globally',
            'specialization' => 'Research & Nobel-level Science',
            'courses' => [
                ['name' => 'MSc in Informatics', 'duration' => '2 Years', 'tuition_fee' => '¥535,800', 'intakes' => 'April, October'],
                ['name' => 'International Course in Civil Engineering', 'duration' => '4 Years', 'tuition_fee' => '¥535,800', 'intakes' => 'April']
            ]
        ],
        [
            'name' => 'Tohoku University',
            'qs_ranking' => '#160 Globally',
            'specialization' => 'Materials Science & Innovation',
            'courses' => [
                ['name' => 'MSc in Mechanical Engineering', 'duration' => '2 Years', 'tuition_fee' => '¥535,800', 'intakes' => 'October'],
                ['name' => 'Future Global Leadership (FGL) Program', 'duration' => '4 Years', 'tuition_fee' => '¥535,800', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'Osaka University',
            'qs_ranking' => '#165 Globally',
            'specialization' => 'Robotics & Chemical Engineering',
            'courses' => [
                ['name' => 'MSc in Robotics', 'duration' => '2 Years', 'tuition_fee' => '¥535,800', 'intakes' => 'April, October'],
                ['name' => 'Human Sciences International Undergraduate Degree', 'duration' => '4 Years', 'tuition_fee' => '¥535,800', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'Nagoya University',
            'qs_ranking' => '#178 Globally',
            'specialization' => 'Automotive & Life Sciences',
            'courses' => [
                ['name' => 'MSc in Automotive Engineering (English)', 'duration' => '2 Years', 'tuition_fee' => '¥535,800', 'intakes' => 'October'],
                ['name' => 'G30 International Program - Engineering', 'duration' => '4 Years', 'tuition_fee' => '¥535,800', 'intakes' => 'October']
            ]
        ]
    ];
    
    foreach ($japanUnis as $uniData) {
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
    echo "Japan Universities and Courses updated successfully.\n";
} else {
    echo "Japan not found in DB.\n";
}
?>
