<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Historic Excellence: Home to Charles University, one of the world’s oldest institutions, now a leading European Tech hub.',
    'roi_priority' => 'Free-Tuition Path: Students achieving B2 level in Czech can study at public universities with €0 tuition fees.',
    'roi_wage' => 'Earnings Power: New 2026 minimum wage of CZK 22,400/month (Approx. ₹1.03 Lakhs) for graduates.',
    'roi_qs' => 'Stay-back: Automatic visa extension for graduates, with an easy switch to the Employee Card system.',
    'living_cost_local' => 'CZK 115,810 / year',
    'living_cost_inr' => 'Approx. ₹5.3 Lakhs for annual maintenance',
    'visa_fee_local' => 'CZK 2,500',
    'visa_fee_inr' => 'Residence Permit application fee (~₹11,500)',
    'weekly_budget_local' => 'CZK 134.40 / hr',
    'weekly_budget_inr' => 'Minimum hourly student wage (~₹620/hour)',
    'earnings_potential_local' => '€3,000 – €10,000',
    'earnings_potential_inr' => 'Average annual non-medical Tuition Fees',
    'upcoming_intakes' => "Winter (Sept/Oct) | Primary Intake\nSpring (February) | Secondary Intake",
    'demand_careers' => "Artificial Intelligence\nRobotics & Automation\nCyber Security\nBiotechnology\nInternational Business",
    'travel_hours' => "Approx 8 - 15 hours (Depending on route and city)"
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
    WHERE `slug` = 'czech-republic'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Czech Republic DB updated successfully.\n";

// Update Czech Republic Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'czech-republic'");
$stmt->execute();
$czech = $stmt->fetch();

if ($czech) {
    $countryId = $czech['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $czechUnis = [
        [
            'name' => 'Charles University',
            'qs_ranking' => '#265 Globally',
            'specialization' => 'Medicine, Law, Humanities',
            'courses' => [
                ['name' => 'MD in General Medicine (English)', 'duration' => '6 Years', 'tuition_fee' => '€16,000', 'intakes' => 'September'],
                ['name' => 'LLM in International Human Rights Law', 'duration' => '1 Year', 'tuition_fee' => '€6,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Czech Technical University in Prague (CTU)',
            'qs_ranking' => '#416 Globally',
            'specialization' => 'AI, Robotics, Engineering',
            'courses' => [
                ['name' => 'MSc in Cybernetics and Robotics', 'duration' => '2 Years', 'tuition_fee' => '€5,000', 'intakes' => 'September, February'],
                ['name' => 'MSc in Artificial Intelligence', 'duration' => '2 Years', 'tuition_fee' => '€5,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Masaryk University',
            'qs_ranking' => '#430 Globally',
            'specialization' => 'Computer Science, Business',
            'courses' => [
                ['name' => 'MSc in Software Systems and Service Management', 'duration' => '2 Years', 'tuition_fee' => '€3,500', 'intakes' => 'September, February'],
                ['name' => 'MA in International Business', 'duration' => '2 Years', 'tuition_fee' => '€3,500', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Brno University of Technology',
            'qs_ranking' => '#575 Globally',
            'specialization' => 'High-Tech Engineering',
            'courses' => [
                ['name' => 'MSc in Information Technology', 'duration' => '2 Years', 'tuition_fee' => '€4,000', 'intakes' => 'September'],
                ['name' => 'MSc in Power Engineering', 'duration' => '2 Years', 'tuition_fee' => '€4,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Chemistry & Technology Prague',
            'qs_ranking' => '#638 Globally',
            'specialization' => 'Biotechnology, Chemical Engineering',
            'courses' => [
                ['name' => 'MSc in Biotechnology and Food Science', 'duration' => '2 Years', 'tuition_fee' => '€3,000', 'intakes' => 'September'],
                ['name' => 'MSc in Chemical Engineering', 'duration' => '2 Years', 'tuition_fee' => '€3,000', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($czechUnis as $uniData) {
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
    echo "Czech Republic Universities and Courses updated successfully.\n";
} else {
    echo "Czech Republic not found in DB.\n";
}
?>
