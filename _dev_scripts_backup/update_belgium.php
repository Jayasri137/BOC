<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Capital of Europe: Study at the HQ of the EU and NATO with world-class policy and research exposure.',
    'roi_priority' => 'Multilingual Excellence: Home to KU Leuven (#60 Globally) with 500+ English-taught Master’s programs.',
    'roi_wage' => 'Stay-back: 12-Month Orientation Year to search for jobs or start a startup across the entire EU.',
    'roi_qs' => 'Industry Link: Strong pipelines into biotech, pharmaceuticals, and logistics (GSK, Pfizer, Janssen).',
    'living_cost_local' => '€12,744 / year',
    'living_cost_inr' => 'Approx. ₹11.8 Lakhs required for Blocked Account visa',
    'visa_fee_local' => '€180 – €220',
    'visa_fee_inr' => 'Type D visa administrative processing fee (~₹16k - ₹20k)',
    'weekly_budget_local' => '€1,062 / month',
    'weekly_budget_inr' => 'Updated EU threshold (~₹98,000 monthly student budget)',
    'earnings_potential_local' => '€3,000 – €9,000',
    'earnings_potential_inr' => 'Average annual non-medical Tuition Fees (Subsidized Public)',
    'upcoming_intakes' => "September | Main Intake\nSpring | Limited Programs",
    'demand_careers' => "Life Sciences & Biotech\nAI & Data Science\nEU Law & Public Policy\nInternational Relations\nLogistics & Supply Chain",
    'travel_hours' => "Approx 8 - 10 hours (Typical 1-stop from major Indian cities)"
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
    WHERE `slug` = 'belgium'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Belgium DB updated successfully.\n";

// Update Belgium Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'belgium'");
$stmt->execute();
$belgium = $stmt->fetch();

if ($belgium) {
    $countryId = $belgium['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $belgiumUnis = [
        [
            'name' => 'KU Leuven',
            'qs_ranking' => '#60 Globally',
            'specialization' => 'Engineering, Pharmacy, AI',
            'courses' => [
                ['name' => 'MSc in Artificial Intelligence', 'duration' => '1 Year', 'tuition_fee' => '€4,000', 'intakes' => 'September'],
                ['name' => 'MSc in Mechanical Engineering', 'duration' => '2 Years', 'tuition_fee' => '€3,500', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Ghent University',
            'qs_ranking' => 'Top Life Sciences Hub',
            'specialization' => 'Biotechnology, Veterinary Science',
            'courses' => [
                ['name' => 'MSc in Bioinformatics', 'duration' => '2 Years', 'tuition_fee' => '€3,000', 'intakes' => 'September'],
                ['name' => 'MSc in Environmental Science', 'duration' => '2 Years', 'tuition_fee' => '€2,500', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Université Libre de Bruxelles (ULB)',
            'qs_ranking' => 'Policy & Law Leader',
            'specialization' => 'Law, Politics, Medicine',
            'courses' => [
                ['name' => 'MA in International Relations', 'duration' => '1 Year', 'tuition_fee' => '€4,500', 'intakes' => 'September'],
                ['name' => 'MSc in Biomedical Sciences', 'duration' => '2 Years', 'tuition_fee' => '€5,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Antwerp',
            'qs_ranking' => 'Logistics Hub',
            'specialization' => 'Logistics, Business, Applied Sciences',
            'courses' => [
                ['name' => 'MSc in Supply Chain Management', 'duration' => '1 Year', 'tuition_fee' => '€6,000', 'intakes' => 'September'],
                ['name' => 'MSc in Global Management', 'duration' => '1 Year', 'tuition_fee' => '€8,500', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'UCLouvain',
            'qs_ranking' => 'Top French-speaking Research',
            'specialization' => 'Business, Engineering, Research',
            'courses' => [
                ['name' => 'MA in Economics', 'duration' => '2 Years', 'tuition_fee' => '€3,500', 'intakes' => 'September'],
                ['name' => 'MSc in Data Science (English)', 'duration' => '2 Years', 'tuition_fee' => '€4,000', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($belgiumUnis as $uniData) {
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
    echo "Belgium Universities and Courses updated successfully.\n";
} else {
    echo "Belgium not found in DB.\n";
}
?>
