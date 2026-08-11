<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Innovation Leader: Home to globally recognized brands such as Spotify, IKEA, and Volvo.',
    'roi_priority' => 'Stay-back: 12-Month Post-Study Residence Permit to search for jobs or start a business.',
    'roi_wage' => 'Global Rankings: 5 Swedish universities ranked within the World’s Top 100 (QS 2026).',
    'roi_qs' => 'English-Taught Education: Over 1,000+ Bachelor’s, Master’s, and PhD programs available in English.',
    'living_cost_local' => 'SEK 10,656 / month',
    'living_cost_inr' => 'Mandatory maintenance (~₹88,000 / month)',
    'visa_fee_local' => 'SEK 1,500',
    'visa_fee_inr' => 'Residence Permit Fee (~₹12k)',
    'weekly_budget_local' => 'SEK 127,872',
    'weekly_budget_inr' => 'Annual Maintenance Funds required for 12-month permit (~₹10.5 Lakhs)',
    'earnings_potential_local' => 'SEK 80,000 - 155,000',
    'earnings_potential_inr' => 'Average Tuition Fees (Postgraduate)',
    'upcoming_intakes' => "Autumn (August/September) | Primary Intake\nSpring (January) | Secondary Intake",
    'demand_careers' => "Artificial Intelligence\nRenewable Energy\nSustainable Engineering\nData Science\nBiotechnology",
    'travel_hours' => "Approx 9 - 16 hours (Depending on route and city)"
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
    WHERE `slug` = 'sweden'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Sweden DB updated successfully.\n";

// Update Sweden Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'sweden'");
$stmt->execute();
$sweden = $stmt->fetch();

if ($sweden) {
    $countryId = $sweden['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $swedenUnis = [
        [
            'name' => 'KTH Royal Institute of Technology',
            'qs_ranking' => '#72 Globally',
            'specialization' => 'Engineering, Architecture',
            'courses' => [
                ['name' => 'MSc in Machine Learning', 'duration' => '2 Years', 'tuition_fee' => 'SEK 155,000', 'intakes' => 'August'],
                ['name' => 'MSc in Sustainable Technology', 'duration' => '2 Years', 'tuition_fee' => 'SEK 155,000', 'intakes' => 'August']
            ]
        ],
        [
            'name' => 'Lund University',
            'qs_ranking' => '#76 Globally',
            'specialization' => 'Physics, Medicine',
            'courses' => [
                ['name' => 'MSc in Physics (Nanoscience)', 'duration' => '2 Years', 'tuition_fee' => 'SEK 145,000', 'intakes' => 'August'],
                ['name' => 'MSc in International Marketing', 'duration' => '1 Year', 'tuition_fee' => 'SEK 120,000', 'intakes' => 'August']
            ]
        ],
        [
            'name' => 'Uppsala University',
            'qs_ranking' => '#92 Globally',
            'specialization' => 'Life Sciences, Law',
            'courses' => [
                ['name' => 'MSc in Bioinformatics', 'duration' => '2 Years', 'tuition_fee' => 'SEK 145,000', 'intakes' => 'August'],
                ['name' => 'LLM in Investment Law', 'duration' => '1 Year', 'tuition_fee' => 'SEK 100,000', 'intakes' => 'August']
            ]
        ],
        [
            'name' => 'Stockholm University',
            'qs_ranking' => '#124 Globally',
            'specialization' => 'Economics, Environmental Science',
            'courses' => [
                ['name' => 'MSc in Banking and Finance', 'duration' => '2 Years', 'tuition_fee' => 'SEK 90,000', 'intakes' => 'August'],
                ['name' => 'MSc in Environmental Science', 'duration' => '2 Years', 'tuition_fee' => 'SEK 140,000', 'intakes' => 'August']
            ]
        ],
        [
            'name' => 'Chalmers University of Technology',
            'qs_ranking' => '#227 Globally',
            'specialization' => 'Maritime, Sustainability',
            'courses' => [
                ['name' => 'MSc in Sustainable Energy Systems', 'duration' => '2 Years', 'tuition_fee' => 'SEK 140,000', 'intakes' => 'August'],
                ['name' => 'MSc in Automotive Engineering', 'duration' => '2 Years', 'tuition_fee' => 'SEK 140,000', 'intakes' => 'August']
            ]
        ]
    ];
    
    foreach ($swedenUnis as $uniData) {
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
    echo "Sweden Universities and Courses updated successfully.\n";
} else {
    echo "Sweden not found in DB.\n";
}
?>
