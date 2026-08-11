<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Adriatic Gateway: Study in the heart of Europe with full EU + Schengen membership and 12-month post-study work rights.',
    'roi_priority' => 'Elite Heritage: Home to University of Zagreb (#701 Globally) with public tuition starting from just €1,000/year.',
    'roi_wage' => 'Stay-back: 12-month job-seeking residence permit after graduation. Part-time work via official "Student Service" system.',
    'roi_qs' => 'Compliance Intelligence: Strategic focus on Nostrification (degree recognition) mastery for seamless EU-wide transitions.',
    'living_cost_local' => '€4,800 – €9,600 / year',
    'living_cost_inr' => 'Approx. ₹36k – ₹72k monthly living cost',
    'visa_fee_local' => '€93',
    'visa_fee_inr' => 'Visa (D-Type) government fee (~₹8,400)',
    'weekly_budget_local' => '€400 – €800 / month',
    'weekly_budget_inr' => 'Estimated monthly student budget',
    'earnings_potential_local' => '€1,000 – €7,000',
    'earnings_potential_inr' => 'Average annual non-medical Tuition Fees (Public Universities)',
    'upcoming_intakes' => "October | Main Intake (Apply Mar-Jun)\nMarch | Limited Intake",
    'demand_careers' => "Medicine & Healthcare\nComputer Science & IT\nEngineering\nEconomics & Business\nTourism & Hospitality",
    'travel_hours' => "Approx 9 - 11 hours (1 stop from Delhi/Mumbai)"
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
    WHERE `slug` = 'croatia'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Croatia DB updated successfully.\n";

// Update Croatia Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'croatia'");
$stmt->execute();
$croatia = $stmt->fetch();

if ($croatia) {
    $countryId = $croatia['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $croatiaUnis = [
        [
            'name' => 'University of Zagreb',
            'qs_ranking' => '#701–710 Globally',
            'specialization' => 'Medicine, Humanities, Sciences',
            'courses' => [
                ['name' => 'Medicine (English Track)', 'duration' => '6 Years', 'tuition_fee' => '€12,000', 'intakes' => 'October'],
                ['name' => 'MSc in Computing', 'duration' => '2 Years', 'tuition_fee' => '€4,000', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'University of Rijeka',
            'qs_ranking' => '#1201+ Globally',
            'specialization' => 'Maritime, Engineering',
            'courses' => [
                ['name' => 'MSc in Naval Architecture', 'duration' => '2 Years', 'tuition_fee' => '€3,500', 'intakes' => 'October'],
                ['name' => 'BEng in Mechanical Engineering', 'duration' => '3 Years', 'tuition_fee' => '€2,500', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'University of Split',
            'qs_ranking' => '#1201+ Globally',
            'specialization' => 'Tourism, Life Sciences',
            'courses' => [
                ['name' => 'MA in Tourism & Hospitality', 'duration' => '2 Years', 'tuition_fee' => '€3,000', 'intakes' => 'October'],
                ['name' => 'MSc in Marine Biology', 'duration' => '2 Years', 'tuition_fee' => '€2,500', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'RIT Croatia',
            'qs_ranking' => 'US-Affiliated',
            'specialization' => 'IT, Business, Computing',
            'courses' => [
                ['name' => 'BSc in Web and Mobile Computing', 'duration' => '4 Years', 'tuition_fee' => '€7,000', 'intakes' => 'October'],
                ['name' => 'BSc in Hospitality Management', 'duration' => '4 Years', 'tuition_fee' => '€6,500', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'University of Zadar',
            'qs_ranking' => 'Regional Leader',
            'specialization' => 'Archaeology, Linguistics',
            'courses' => [
                ['name' => 'MA in Applied Linguistics', 'duration' => '2 Years', 'tuition_fee' => '€2,000', 'intakes' => 'October']
            ]
        ]
    ];
    
    foreach ($croatiaUnis as $uniData) {
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
    echo "Croatia Universities and Courses updated successfully.\n";
} else {
    echo "Croatia not found in DB.\n";
}
?>
