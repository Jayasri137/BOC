<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Smart Hub: Home to elite branch campuses of Monash, Nottingham, Southampton, and Heriot-Watt.',
    'roi_priority' => 'Global Degree Advantage: Earn UK, Australian, or Irish degrees at nearly 40–60% lower cost than studying in the home country.',
    'roi_wage' => 'Graduate Pass: New 12-Month Post-Study Graduate Pass for searching jobs and gaining local industry exposure.',
    'roi_qs' => 'ASEAN Career Gateway: Strategic access to Southeast Asia’s booming AI, Semiconductor, and Fintech markets.',
    'living_cost_local' => 'RM 18,000 – RM 30,000 / year',
    'living_cost_inr' => 'Approx. ₹3.3L – ₹5.5L for annual student expenses',
    'visa_fee_local' => 'Varies via EMGS',
    'visa_fee_inr' => 'Processed through EMGS (Education Malaysia Global Services)',
    'weekly_budget_local' => 'RM 1,500 – RM 2,500',
    'weekly_budget_inr' => 'Estimated monthly student budget (~₹27k - ₹45k)',
    'earnings_potential_local' => 'RM 30,000 – RM 75,000',
    'earnings_potential_inr' => 'Average annual non-medical Tuition Fees',
    'upcoming_intakes' => "Sept/Oct | Primary Intake\nFeb/March | Secondary Intake\nRolling | Private University Options",
    'demand_careers' => "Semiconductor Manufacturing\nArtificial Intelligence\nFintech & Digital Banking\nCybersecurity\nSmart Manufacturing",
    'travel_hours' => "Approx 4 - 6 hours (Direct flights from major Indian cities)"
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
    WHERE `slug` = 'malaysia'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Malaysia DB updated successfully.\n";

// Update Malaysia Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'malaysia'");
$stmt->execute();
$malaysia = $stmt->fetch();

if ($malaysia) {
    $countryId = $malaysia['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $malaysiaUnis = [
        [
            'name' => 'Monash University Malaysia',
            'qs_ranking' => 'QS #36 (Parent Univ)',
            'specialization' => 'Business, Engineering, IT',
            'courses' => [
                ['name' => 'Bachelor of Computer Science', 'duration' => '3 Years', 'tuition_fee' => 'RM 48,000', 'intakes' => 'Feb, July, Oct'],
                ['name' => 'Bachelor of Business and Commerce', 'duration' => '3 Years', 'tuition_fee' => 'RM 42,000', 'intakes' => 'Feb, July, Oct']
            ]
        ],
        [
            'name' => 'University of Southampton Malaysia',
            'qs_ranking' => 'QS #87 (Parent Univ)',
            'specialization' => 'Engineering, Computer Science',
            'courses' => [
                ['name' => 'MEng in Aeronautics and Astronautics', 'duration' => '4 Years', 'tuition_fee' => 'RM 55,000', 'intakes' => 'September'],
                ['name' => 'BSc in Computer Science (AI)', 'duration' => '3 Years', 'tuition_fee' => 'RM 45,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'University of Nottingham Malaysia',
            'qs_ranking' => 'QS #97 (Parent Univ)',
            'specialization' => 'Arts, Science, Engineering',
            'courses' => [
                ['name' => 'BEng in Mechanical Engineering', 'duration' => '3 Years', 'tuition_fee' => 'RM 52,000', 'intakes' => 'September'],
                ['name' => 'MSc in International Business', 'duration' => '1 Year', 'tuition_fee' => 'RM 50,000', 'intakes' => 'Sept, February']
            ]
        ],
        [
            'name' => 'University of Reading Malaysia',
            'qs_ranking' => 'QS #194 (Parent Univ)',
            'specialization' => 'Real Estate, Finance, Pharmacy',
            'courses' => [
                ['name' => 'BSc in Finance and Business Management', 'duration' => '3 Years', 'tuition_fee' => 'RM 38,000', 'intakes' => 'Sept, Jan'],
                ['name' => 'Bachelor of Pharmacy (BPharm)', 'duration' => '4 Years', 'tuition_fee' => 'RM 40,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Heriot-Watt University Malaysia',
            'qs_ranking' => 'QS #287 (Parent Univ)',
            'specialization' => 'Actuarial Science, Petroleum Eng',
            'courses' => [
                ['name' => 'BSc in Actuarial Science', 'duration' => '3 Years', 'tuition_fee' => 'RM 45,000', 'intakes' => 'Sept, Jan'],
                ['name' => 'MBA (Edinburgh Business School)', 'duration' => '1 Year', 'tuition_fee' => 'RM 55,000', 'intakes' => 'Sept, Jan, May']
            ]
        ]
    ];
    
    foreach ($malaysiaUnis as $uniData) {
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
    echo "Malaysia Universities and Courses updated successfully.\n";
} else {
    echo "Malaysia not found in DB.\n";
}
?>
