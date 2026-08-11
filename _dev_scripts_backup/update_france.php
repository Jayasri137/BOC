<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Elite Business Schools: Home to HEC Paris and INSEAD — world-ranked leaders.',
    'roi_priority' => 'The 5-Year Advantage: Master’s graduates may receive a 5-year Schengen Circulation Visa.',
    'roi_wage' => 'Subsidized Living: International students eligible for CAF, reducing rent by 30–40%.',
    'roi_qs' => 'Stay-back Opportunity: 1 to 2 Years APS/Job Seeker residence permit after graduation.',
    'living_cost_local' => '€7,380 / year',
    'living_cost_inr' => 'Minimum required (~€615/month)',
    'visa_fee_local' => '€50 - €99',
    'visa_fee_inr' => 'Campus France + Visa Fee (~₹15k - ₹20k)',
    'weekly_budget_local' => '€2,850 – €3,879',
    'weekly_budget_inr' => 'Annual Public University Fees (Bachelor/Master)',
    'earnings_potential_local' => '€11.65 / hour',
    'earnings_potential_inr' => 'Gross Minimum Wage (SMIC)',
    'upcoming_intakes' => "Fall (September) | Primary Intake\nSpring (January) | Secondary Intake (Business Schools)",
    'demand_careers' => "Luxury Brand Management\nAerospace Engineering\nArtificial Intelligence\nFashion Design\nCulinary Arts",
    'travel_hours' => "Approx 9 - 15 hours (Depending on route and city)"
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
    WHERE `slug` = 'france'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "France DB updated successfully.\n";

// Update France Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'france'");
$stmt->execute();
$france = $stmt->fetch();

if ($france) {
    $countryId = $france['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $franceUnis = [
        [
            'name' => 'Université PSL (Paris)',
            'qs_ranking' => '#24 Globally',
            'specialization' => 'Research, Science',
            'courses' => [
                ['name' => 'Master in Life Sciences', 'duration' => '2 Years', 'tuition_fee' => '€3,800', 'intakes' => 'September'],
                ['name' => 'Master in Physics', 'duration' => '2 Years', 'tuition_fee' => '€3,800', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Institut Polytechnique de Paris',
            'qs_ranking' => '#38 Globally',
            'specialization' => 'Engineering, STEM',
            'courses' => [
                ['name' => 'MSc in Cybersecurity', 'duration' => '2 Years', 'tuition_fee' => '€12,000', 'intakes' => 'September'],
                ['name' => 'MSc in Artificial Intelligence', 'duration' => '2 Years', 'tuition_fee' => '€12,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'HEC Paris',
            'qs_ranking' => 'Top Business School',
            'specialization' => 'MBA, Management',
            'courses' => [
                ['name' => 'Master in Management (MiM)', 'duration' => '2 Years', 'tuition_fee' => '€45,000', 'intakes' => 'September'],
                ['name' => 'MBA', 'duration' => '16 Months', 'tuition_fee' => '€76,000', 'intakes' => 'January, September']
            ]
        ],
        [
            'name' => 'Sorbonne University',
            'qs_ranking' => 'Global Reputation',
            'specialization' => 'Arts, Humanities, Medicine',
            'courses' => [
                ['name' => 'Master in Humanities', 'duration' => '2 Years', 'tuition_fee' => '€3,800', 'intakes' => 'September'],
                ['name' => 'Master in Medicine (Advanced)', 'duration' => '2 Years', 'tuition_fee' => '€3,800', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'INSEAD',
            'qs_ranking' => 'Top Business School',
            'specialization' => 'Global MBA',
            'courses' => [
                ['name' => 'MBA', 'duration' => '10 Months', 'tuition_fee' => '€98,000', 'intakes' => 'January, September'],
                ['name' => 'Master in Management', 'duration' => '1 Year', 'tuition_fee' => '€49,000', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($franceUnis as $uniData) {
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
    echo "France Universities and Courses updated successfully.\n";
} else {
    echo "France not found in DB.\n";
}
?>
