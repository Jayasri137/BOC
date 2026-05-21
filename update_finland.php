<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Happiest Education: Finland consistently ranks among the world’s happiest, safest, and most innovative nations.',
    'roi_priority' => 'Stay-back: 2-Year extended residence permit to look for work after graduation.',
    'roi_wage' => 'Work Rights: Students can work up to 30 hours/week (Increased from 25 hours).',
    'roi_qs' => 'Path to PR: Eligibility for Permanent Residency after 4 years of continuous residence.',
    'living_cost_local' => '€9,600 / year',
    'living_cost_inr' => 'Official Migri benchmark (~₹8.7 Lakhs / year)',
    'visa_fee_local' => '€450',
    'visa_fee_inr' => 'Online application via Enter Finland (~₹40k)',
    'weekly_budget_local' => '€800',
    'weekly_budget_inr' => 'Monthly living budget required (~₹72k)',
    'earnings_potential_local' => '€8,000 – €18,000',
    'earnings_potential_inr' => 'Average annual Tuition Fees',
    'upcoming_intakes' => "Joint Application (January) | Primary Intake\nRolling Admissions (Nov - March) | UAS Institutions",
    'demand_careers' => "Artificial Intelligence\n6G Research\nSustainability\nSoftware Engineering\nRenewable Energy",
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
    WHERE `slug` = 'finland'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Finland DB updated successfully.\n";

// Update Finland Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'finland'");
$stmt->execute();
$finland = $stmt->fetch();

if ($finland) {
    $countryId = $finland['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $finlandUnis = [
        [
            'name' => 'Aalto University',
            'qs_ranking' => '#114 Globally',
            'specialization' => 'Art, Design, Business & Technology',
            'courses' => [
                ['name' => 'MSc in Machine Learning, Data Science and Artificial Intelligence', 'duration' => '2 Years', 'tuition_fee' => '€15,000', 'intakes' => 'January'],
                ['name' => 'MSc in International Design Business Management', 'duration' => '2 Years', 'tuition_fee' => '€15,000', 'intakes' => 'January']
            ]
        ],
        [
            'name' => 'University of Helsinki',
            'qs_ranking' => '#116 Globally',
            'specialization' => 'Science, Medicine & Humanities',
            'courses' => [
                ['name' => 'MSc in Data Science', 'duration' => '2 Years', 'tuition_fee' => '€13,000', 'intakes' => 'January'],
                ['name' => 'MSc in Atmospheric Sciences', 'duration' => '2 Years', 'tuition_fee' => '€13,000', 'intakes' => 'January']
            ]
        ],
        [
            'name' => 'University of Oulu',
            'qs_ranking' => '#342 Globally',
            'specialization' => '6G Research & Wireless Communications',
            'courses' => [
                ['name' => 'MSc in Wireless Communications Engineering', 'duration' => '2 Years', 'tuition_fee' => '€10,000', 'intakes' => 'January'],
                ['name' => 'MSc in Software Engineering', 'duration' => '2 Years', 'tuition_fee' => '€10,000', 'intakes' => 'January']
            ]
        ],
        [
            'name' => 'University of Turku',
            'qs_ranking' => '#366 Globally',
            'specialization' => 'Biotechnology & Medicine',
            'courses' => [
                ['name' => 'MSc in Biomedical Sciences', 'duration' => '2 Years', 'tuition_fee' => '€12,000', 'intakes' => 'January'],
                ['name' => 'MSc in Cyber Security', 'duration' => '2 Years', 'tuition_fee' => '€12,000', 'intakes' => 'January']
            ]
        ],
        [
            'name' => 'LUT University',
            'qs_ranking' => '#397 Globally',
            'specialization' => 'Clean Energy & Circular Economy',
            'courses' => [
                ['name' => 'MSc in Clean Energy Processes', 'duration' => '2 Years', 'tuition_fee' => '€9,500', 'intakes' => 'January'],
                ['name' => 'MSc in Circular Economy', 'duration' => '2 Years', 'tuition_fee' => '€9,500', 'intakes' => 'January']
            ]
        ]
    ];
    
    foreach ($finlandUnis as $uniData) {
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
    echo "Finland Universities and Courses updated successfully.\n";
} else {
    echo "Finland not found in DB.\n";
}
?>
