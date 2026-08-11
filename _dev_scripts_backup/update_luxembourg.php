<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Wealth of Innovation: Study in the world’s richest nation and the administrative heart of the European Union.',
    'roi_priority' => 'Professional Integration: Direct access to EU institutions, Amazon EU HQ, and 120+ global financial headquarters.',
    'roi_wage' => 'Stay-back: 9-month job search permit. 100% free public transport (trains, trams, buses) for all students.',
    'roi_qs' => 'Elite Reputation: University of Luxembourg (#381) ranked in top 1% for international outlook with strong high-income placement.',
    'living_cost_local' => '€18,211 / year',
    'living_cost_inr' => 'Approx. ₹1.36 Lakhs/month for annual maintenance',
    'visa_fee_local' => '€50',
    'visa_fee_inr' => 'Visa (D-Type) government fee (~₹4,500)',
    'weekly_budget_local' => '€1,517 / month',
    'weekly_budget_inr' => 'Estimated monthly student budget including high living standards',
    'earnings_potential_local' => '€800 – €4,000 / year',
    'earnings_potential_inr' => 'Highly subsidized annual public Tuition Fees (per semester approx ₹36k-72k)',
    'upcoming_intakes' => "September | Main Intake\nLimited Secondary intakes",
    'demand_careers' => "Finance & Banking\nEuropean Governance & Policy\nComputer Science & AI\nData Science\nLaw & International Relations",
    'travel_hours' => "Approx 10 - 13 hours (From Delhi/Mumbai/Bangalore)"
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
    WHERE `slug` = 'luxembourg'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Luxembourg DB updated successfully.\n";

// Update Luxembourg Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'luxembourg'");
$stmt->execute();
$lux = $stmt->fetch();

if ($lux) {
    $countryId = $lux['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $luxUnis = [
        [
            'name' => 'University of Luxembourg',
            'qs_ranking' => '#381 Globally',
            'specialization' => 'Finance, Law, Computer Science',
            'courses' => [
                ['name' => 'MSc in Banking and Finance', 'duration' => '2 Years', 'tuition_fee' => '€800 /yr', 'intakes' => 'September'],
                ['name' => 'MSc in Information and Computer Sciences', 'duration' => '2 Years', 'tuition_fee' => '€800 /yr', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Sacred Heart University Luxembourg',
            'qs_ranking' => 'Top Business Hub',
            'specialization' => 'MBA, Business',
            'courses' => [
                ['name' => 'Executive MBA', 'duration' => '1.5 Years', 'tuition_fee' => '€25,000', 'intakes' => 'September'],
                ['name' => 'Professional MBA', 'duration' => '2 Years', 'tuition_fee' => '€20,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'LUNEX University',
            'qs_ranking' => 'Health & Sports Science',
            'specialization' => 'Health, Sports Science',
            'courses' => [
                ['name' => 'BSc in Physiotherapy', 'duration' => '3 Years', 'tuition_fee' => '€8,000 /yr', 'intakes' => 'September'],
                ['name' => 'MSc in Sports Management', 'duration' => '2 Years', 'tuition_fee' => '€6,500 /yr', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'European Institute of Public Administration (EIPA)',
            'qs_ranking' => 'EU Policy Leader',
            'specialization' => 'EU Policy & Governance',
            'courses' => [
                ['name' => 'Master of European Governance', 'duration' => '2 Years', 'tuition_fee' => '€3,000 /yr', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'BBI Luxembourg',
            'qs_ranking' => 'Hospitality Leader',
            'specialization' => 'Hospitality & Tourism',
            'courses' => [
                ['name' => 'BA in Hospitality Management', 'duration' => '3 Years', 'tuition_fee' => '€9,500 /yr', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($luxUnis as $uniData) {
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
    echo "Luxembourg Universities and Courses updated successfully.\n";
} else {
    echo "Luxembourg not found in DB.\n";
}
?>
