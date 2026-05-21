<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Mediterranean Lifestyle & Talent: Accessing the birthplace of academia with modern, high-tech career pathways and New 2026 Talent Visa.',
    'roi_priority' => 'Elite Rankings: National Technical University of Athens (#355) leads technical education. Strong EU mobility positioning.',
    'roi_wage' => 'Stay-back: 12-month "Type H.11" post-study permit to search for work and secure EU Blue Card eligibility.',
    'roi_qs' => 'Talent Hub: Greece is no longer just a tourist destination. Expansion of 200+ English-taught programs in Tech, Shipping, and Business.',
    'living_cost_local' => '€7,200 / year',
    'living_cost_inr' => 'Approx. €600 / month covers rent, food & transit',
    'visa_fee_local' => '€100 – €180',
    'visa_fee_inr' => 'Visa (Type-D) government fee (~₹9k – ₹16k)',
    'weekly_budget_local' => '€15 / month',
    'weekly_budget_inr' => 'Public Transport Card with student discount',
    'earnings_potential_local' => '€3,000 – €9,000',
    'earnings_potential_inr' => 'Average annual Master\'s Tuition Fees',
    'upcoming_intakes' => "October | Main Intake\nLimited Secondary intakes",
    'demand_careers' => "Engineering & Architecture\nShipping & Maritime Studies\nData Science & AI\nBusiness & Finance\nArchaeology & History",
    'travel_hours' => "Approx 9 - 13 hours (From Delhi/Mumbai/Bangalore)"
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
    WHERE `slug` = 'greece'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Greece DB updated successfully.\n";

// Update Greece Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'greece'");
$stmt->execute();
$greece = $stmt->fetch();

if ($greece) {
    $countryId = $greece['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $greeceUnis = [
        [
            'name' => 'National Technical University of Athens',
            'qs_ranking' => '#355 Globally',
            'specialization' => 'Engineering & Architecture',
            'courses' => [
                ['name' => 'MSc in Structural Engineering', 'duration' => '2 Years', 'tuition_fee' => '€3,500', 'intakes' => 'October'],
                ['name' => 'MSc in Naval Architecture', 'duration' => '2 Years', 'tuition_fee' => '€4,000', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'National & Kapodistrian University of Athens',
            'qs_ranking' => '#390 Globally',
            'specialization' => 'Medicine, Law, Archaeology',
            'courses' => [
                ['name' => 'MD Medicine (English Track)', 'duration' => '6 Years', 'tuition_fee' => '€12,000', 'intakes' => 'October'],
                ['name' => 'MA in Greek Archaeology', 'duration' => '1 Year', 'tuition_fee' => '€3,000', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'Aristotle University of Thessaloniki',
            'qs_ranking' => '#485 Globally',
            'specialization' => 'Science & Humanities',
            'courses' => [
                ['name' => 'MSc in Data Science & AI', 'duration' => '1.5 Years', 'tuition_fee' => '€5,000', 'intakes' => 'October'],
                ['name' => 'BSc in Environmental Science', 'duration' => '4 Years', 'tuition_fee' => '€1,500', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'Athens University of Economics & Business',
            'qs_ranking' => '#951 Globally',
            'specialization' => 'Finance & MBA',
            'courses' => [
                ['name' => 'International MBA', 'duration' => '1 Year', 'tuition_fee' => '€10,000', 'intakes' => 'October'],
                ['name' => 'MSc in International Shipping', 'duration' => '1 Year', 'tuition_fee' => '€6,000', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'University of Crete',
            'qs_ranking' => '#628 Globally',
            'specialization' => 'Biology & Materials Science',
            'courses' => [
                ['name' => 'MSc in Molecular Biology', 'duration' => '2 Years', 'tuition_fee' => '€4,500', 'intakes' => 'October']
            ]
        ]
    ];
    
    foreach ($greeceUnis as $uniData) {
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
    echo "Greece Universities and Courses updated successfully.\n";
} else {
    echo "Greece not found in DB.\n";
}
?>
