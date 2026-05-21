<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Lifestyle & Value: Public university tuition can be as low as ₹80,000 per year.',
    'roi_priority' => 'Enhanced Work Rights: International students can now work up to 30 hours/week.',
    'roi_wage' => 'Stay-back Opportunity: 1-Year Job Seeker Visa after graduation.',
    'roi_qs' => 'Global Advantage: Degrees recognized across 48 European countries (Bologna Advantage).',
    'living_cost_local' => '€600 / month',
    'living_cost_inr' => 'Based on IPREM benchmark (~₹54,000 / month)',
    'visa_fee_local' => '€80',
    'visa_fee_inr' => 'Standard student visa processing fee (~₹7.2k)',
    'weekly_budget_local' => '€7,200',
    'weekly_budget_inr' => 'Annual Maintenance Funds required for 1-year visa (~₹6.5 Lakhs)',
    'earnings_potential_local' => '€750 – €3,500',
    'earnings_potential_inr' => 'Annual Public University Tuition Fees (Approx)',
    'upcoming_intakes' => "Fall (Sept/Oct) | Primary Intake\nSpring (Feb) | Mainly Private Business Schools",
    'demand_careers' => "Renewable Energy\nBusiness Analytics\nArtificial Intelligence\nTourism & Hospitality\nData Science",
    'travel_hours' => "Approx 10 - 16 hours (Depending on route and city)"
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
    WHERE `slug` = 'spain'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Spain DB updated successfully.\n";

// Update Spain Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'spain'");
$stmt->execute();
$spain = $stmt->fetch();

if ($spain) {
    $countryId = $spain['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $spainUnis = [
        [
            'name' => 'University of Barcelona (UB)',
            'qs_ranking' => '#160 Globally',
            'specialization' => 'Medicine, Life Sciences, Business',
            'courses' => [
                ['name' => 'MSc in International Business', 'duration' => '1 Year', 'tuition_fee' => '€3,500', 'intakes' => 'September'],
                ['name' => 'MSc in Business Analytics', 'duration' => '1 Year', 'tuition_fee' => '€3,500', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Universitat Autònoma de Barcelona (UAB)',
            'qs_ranking' => '#172 Globally',
            'specialization' => 'Biosciences, Economics',
            'courses' => [
                ['name' => 'MSc in Data Science', 'duration' => '1 Year', 'tuition_fee' => '€4,000', 'intakes' => 'September'],
                ['name' => 'MSc in Economics', 'duration' => '1 Year', 'tuition_fee' => '€4,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Complutense University of Madrid (UCM)',
            'qs_ranking' => '#187 Globally',
            'specialization' => 'Law, Political Science',
            'courses' => [
                ['name' => 'Master in International Relations', 'duration' => '1 Year', 'tuition_fee' => '€3,200', 'intakes' => 'September'],
                ['name' => 'Master in Sustainable Energy', 'duration' => '1 Year', 'tuition_fee' => '€3,200', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Autonomous University of Madrid (UAM)',
            'qs_ranking' => '#206 Globally',
            'specialization' => 'Physics, Environmental Sciences',
            'courses' => [
                ['name' => 'MSc in Theoretical Physics', 'duration' => '1 Year', 'tuition_fee' => '€3,000', 'intakes' => 'September'],
                ['name' => 'MSc in Environmental Sciences', 'duration' => '1 Year', 'tuition_fee' => '€3,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Navarra',
            'qs_ranking' => '#262 Globally',
            'specialization' => 'MBA, Journalism, Business',
            'courses' => [
                ['name' => 'Master in Management (MIM)', 'duration' => '1 Year', 'tuition_fee' => '€25,000', 'intakes' => 'September'],
                ['name' => 'Global MBA', 'duration' => '1.5 Years', 'tuition_fee' => '€35,000', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($spainUnis as $uniData) {
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
    echo "Spain Universities and Courses updated successfully.\n";
} else {
    echo "Spain not found in DB.\n";
}
?>
