<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Baltic Tech Corridor: One of the most affordable Schengen destinations with tuition starting from €1,600/year.',
    'roi_priority' => 'AIC Verification Mastery: Structured EU entry via mandatory AIC validation and OCMA invitation coordination.',
    'roi_wage' => 'Stay-back: Up to 9 months for Master’s/PhD graduates to find employment. 20 hours/week work rights during studies.',
    'roi_qs' => 'Top Institution: Riga Technical University (#761 globally) leads with a strong employer reputation in EU job markets.',
    'living_cost_local' => '€9,360 / year',
    'living_cost_inr' => 'Approx. ₹70k / month monthly living cost',
    'visa_fee_local' => '€90',
    'visa_fee_inr' => 'Visa (D-Visa) government fee (~₹8,100)',
    'weekly_budget_local' => '€780 / month',
    'weekly_budget_inr' => 'Estimated monthly student budget',
    'earnings_potential_local' => '€2,700 – €3,500',
    'earnings_potential_inr' => 'Average annual Engineering Tuition Fees',
    'upcoming_intakes' => "September | Main Intake (Apply Mar-Jun)\nJan/Feb | Limited Intake",
    'demand_careers' => "IT & Software Engineering\nAI & Robotics\nAviation & Transport Engineering\nMedicine & Healthcare\nLogistics & Supply Chain",
    'travel_hours' => "Approx 8 - 10 hours (1 stop from Delhi/Mumbai)"
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
    WHERE `slug` = 'latvia'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Latvia DB updated successfully.\n";

// Update Latvia Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'latvia'");
$stmt->execute();
$latvia = $stmt->fetch();

if ($latvia) {
    $countryId = $latvia['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $latviaUnis = [
        [
            'name' => 'Riga Technical University',
            'qs_ranking' => '#761 Globally',
            'specialization' => 'Engineering, IT, Architecture',
            'courses' => [
                ['name' => 'BEng in Computer Systems', 'duration' => '3 Years', 'tuition_fee' => '€2,700', 'intakes' => 'September'],
                ['name' => 'MSc in Robotics', 'duration' => '2 Years', 'tuition_fee' => '€3,500', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Latvia',
            'qs_ranking' => '#801 Globally',
            'specialization' => 'Science, Medicine, Humanities',
            'courses' => [
                ['name' => 'Medicine (MD)', 'duration' => '6 Years', 'tuition_fee' => '€8,000', 'intakes' => 'September'],
                ['name' => 'BSc in Computer Science', 'duration' => '3 Years', 'tuition_fee' => '€2,200', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Riga Stradiņš University',
            'qs_ranking' => '#1001 Globally',
            'specialization' => 'Medicine, Healthcare',
            'courses' => [
                ['name' => 'Medicine (MD)', 'duration' => '6 Years', 'tuition_fee' => '€12,000', 'intakes' => 'September'],
                ['name' => 'Dentistry', 'duration' => '5 Years', 'tuition_fee' => '€14,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Transport & Telecommunication Institute',
            'qs_ranking' => 'Private Leader',
            'specialization' => 'Aviation, Logistics, IT',
            'courses' => [
                ['name' => 'BSc in Aviation Transport', 'duration' => '4 Years', 'tuition_fee' => '€4,500', 'intakes' => 'September'],
                ['name' => 'MSc in Logistics & Supply Chain', 'duration' => '2 Years', 'tuition_fee' => '€3,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Latvia University of Life Sciences & Tech',
            'qs_ranking' => '#1201 Globally',
            'specialization' => 'Agriculture, Veterinary',
            'courses' => [
                ['name' => 'Veterinary Medicine', 'duration' => '6 Years', 'tuition_fee' => '€6,000', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($latviaUnis as $uniData) {
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
    echo "Latvia Universities and Courses updated successfully.\n";
} else {
    echo "Latvia not found in DB.\n";
}
?>
