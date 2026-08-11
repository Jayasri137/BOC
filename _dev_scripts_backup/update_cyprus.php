<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Mediterranean Gateway: High-quality European education in a safe, sunny, and business-friendly destination.',
    'roi_priority' => 'No IELTS Required: Many institutions accept MOI certificates or conduct internal English assessments.',
    'roi_wage' => 'Stay-back: Up to 12 Months of post-study work rights for international graduates.',
    'roi_qs' => 'Elite Ranking: University of Cyprus (#452 Globally) leads the country’s academic reputation.',
    'living_cost_local' => '€9,000 – €12,000 / year',
    'living_cost_inr' => 'Approx. ₹8.1L – ₹10.8L required for visa approval',
    'visa_fee_local' => '€60',
    'visa_fee_inr' => 'Visa application fee (~₹5.5k), plus VFS charges',
    'weekly_budget_local' => '€700 – €1,000',
    'weekly_budget_inr' => 'Monthly living budget including accommodation (~₹63k - ₹90k)',
    'earnings_potential_local' => '€5,000 – €10,000',
    'earnings_potential_inr' => 'Average annual non-medical Tuition Fees',
    'upcoming_intakes' => "Fall (Sept/Oct) | Primary Intake\nSpring (February) | Secondary Intake",
    'demand_careers' => "Fintech\nShipping & Maritime\nArtificial Intelligence\nHotel Management\nRenewable Energy",
    'travel_hours' => "Approx 8 - 12 hours (Depending on route and city)"
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
    WHERE `slug` = 'cyprus'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Cyprus DB updated successfully.\n";

// Update Cyprus Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'cyprus'");
$stmt->execute();
$cyprus = $stmt->fetch();

if ($cyprus) {
    $countryId = $cyprus['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $cyprusUnis = [
        [
            'name' => 'University of Cyprus (UCY)',
            'qs_ranking' => '#452 Globally',
            'specialization' => 'Engineering, Law, Research',
            'courses' => [
                ['name' => 'MSc in Computer Science', 'duration' => '1.5 Years', 'tuition_fee' => '€5,125', 'intakes' => 'September'],
                ['name' => 'Master in Business Administration (MBA)', 'duration' => '1 Year', 'tuition_fee' => '€10,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Nicosia (UNIC)',
            'qs_ranking' => 'Global Blockchain Leader',
            'specialization' => 'Digital Currency, Fintech, AI',
            'courses' => [
                ['name' => 'MSc in Blockchain and Digital Currency', 'duration' => '1.5 Years', 'tuition_fee' => '€9,500', 'intakes' => 'September, February'],
                ['name' => 'MSc in Artificial Intelligence', 'duration' => '1.5 Years', 'tuition_fee' => '€9,500', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Cyprus University of Technology (CUT)',
            'qs_ranking' => 'Applied Sciences Excellence',
            'specialization' => 'Environmental Science, Engineering',
            'courses' => [
                ['name' => 'MSc in Environmental Biosciences', 'duration' => '1.5 Years', 'tuition_fee' => '€4,000', 'intakes' => 'September'],
                ['name' => 'MSc in Civil Engineering', 'duration' => '1.5 Years', 'tuition_fee' => '€4,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'European University Cyprus (EUC)',
            'qs_ranking' => 'Innovation & Medical Leader',
            'specialization' => 'Medicine, Healthcare, Technology',
            'courses' => [
                ['name' => 'Doctor of Medicine (MD)', 'duration' => '6 Years', 'tuition_fee' => '€18,000', 'intakes' => 'September'],
                ['name' => 'MSc in Cybersecurity', 'duration' => '1.5 Years', 'tuition_fee' => '€7,500', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Frederick University',
            'qs_ranking' => 'Premier Private Institution',
            'specialization' => 'Architecture, Business, Engineering',
            'courses' => [
                ['name' => 'MSc in Maritime Operations and Management', 'duration' => '1 Year', 'tuition_fee' => '€8,000', 'intakes' => 'September, February'],
                ['name' => 'BSc in Architecture', 'duration' => '4 Years', 'tuition_fee' => '€6,500', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($cyprusUnis as $uniData) {
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
    echo "Cyprus Universities and Courses updated successfully.\n";
} else {
    echo "Cyprus not found in DB.\n";
}
?>
