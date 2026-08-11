<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'High-Value Europe: One of the lowest costs of living in the EU with historic academic prestige.',
    'roi_priority' => 'Stay-back: 9-Month Study-to-Work residence permit to find employment or start a business.',
    'roi_wage' => 'Scholarship Lead: Home to the prestigious Stipendium Hungaricum Scholarship (Fully Funded).',
    'roi_qs' => 'Medical Excellence: Medical and Engineering degrees recognized across the UK, USA, and EU.',
    'living_cost_local' => '€6,500 – €8,000 / year',
    'living_cost_inr' => 'Approx. ₹5.8L – ₹7.2L required for visa',
    'visa_fee_local' => '€110',
    'visa_fee_inr' => 'Standard long-stay D-Type study visa fee (~₹10k)',
    'weekly_budget_local' => '€550 – €750',
    'weekly_budget_inr' => 'Monthly living budget including accommodation (~₹50k - ₹68k)',
    'earnings_potential_local' => '€3,000 – €6,000',
    'earnings_potential_inr' => 'Average annual non-medical Tuition Fees',
    'upcoming_intakes' => "Fall (September) | Primary Intake\nSpring (February) | Secondary Intake",
    'demand_careers' => "Medicine & Healthcare\nAutomotive Engineering\nArtificial Intelligence\nComputer Science\nInternational Business",
    'travel_hours' => "Approx 8 - 14 hours (Depending on route and city)"
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
    WHERE `slug` = 'hungary'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Hungary DB updated successfully.\n";

// Update Hungary Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'hungary'");
$stmt->execute();
$hungary = $stmt->fetch();

if ($hungary) {
    $countryId = $hungary['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $hungaryUnis = [
        [
            'name' => 'University of Szeged',
            'qs_ranking' => '#601-610 Globally',
            'specialization' => 'Medicine, Science, Humanities',
            'courses' => [
                ['name' => 'Doctor of General Medicine (MD)', 'duration' => '6 Years', 'tuition_fee' => '€15,200', 'intakes' => 'September'],
                ['name' => 'MSc in Computer Science', 'duration' => '2 Years', 'tuition_fee' => '€6,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Debrecen',
            'qs_ranking' => 'Top Choice for Indian Students',
            'specialization' => 'Medicine, Agriculture',
            'courses' => [
                ['name' => 'MD in Medicine', 'duration' => '6 Years', 'tuition_fee' => '€16,000', 'intakes' => 'September'],
                ['name' => 'BSc in Mechanical Engineering', 'duration' => '3.5 Years', 'tuition_fee' => '€5,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Eötvös Loránd University (ELTE)',
            'qs_ranking' => 'Budapest Academic Leader',
            'specialization' => 'Computer Science, Psychology',
            'courses' => [
                ['name' => 'MSc in Computer Science', 'duration' => '2 Years', 'tuition_fee' => '€6,000', 'intakes' => 'September, February'],
                ['name' => 'MA in Psychology', 'duration' => '2 Years', 'tuition_fee' => '€4,200', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Budapest University of Technology & Economics (BME)',
            'qs_ranking' => 'Premier Engineering Institution',
            'specialization' => 'Engineering, Technology',
            'courses' => [
                ['name' => 'MSc in Automotive Engineering', 'duration' => '2 Years', 'tuition_fee' => '€4,500', 'intakes' => 'September'],
                ['name' => 'MSc in Computer Science', 'duration' => '2 Years', 'tuition_fee' => '€4,500', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Pécs',
            'qs_ranking' => 'Historic Research University',
            'specialization' => 'Business, Medical Research',
            'courses' => [
                ['name' => 'General Medicine', 'duration' => '6 Years', 'tuition_fee' => '€14,800', 'intakes' => 'September'],
                ['name' => 'MSc in International Business', 'duration' => '2 Years', 'tuition_fee' => '€5,000', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($hungaryUnis as $uniData) {
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
    echo "Hungary Universities and Courses updated successfully.\n";
} else {
    echo "Hungary not found in DB.\n";
}
?>
