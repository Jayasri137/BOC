<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Global Hub: Home to campuses of world-class universities from the UK, USA, and Australia.',
    'roi_priority' => 'Golden Visa Potential: Exclusive 10-year residency pathways for high-achieving graduates (GPA 3.5+).',
    'roi_wage' => 'Student Work Rights: 24 Hours/Week part-time work allowed with a No Objection Certificate (NOC).',
    'roi_qs' => 'Tax Freedom: 0% Income Tax on future earnings in the UAE.',
    'living_cost_local' => 'AED 3,000 – 5,000 / month',
    'living_cost_inr' => 'Shared accommodation and transport included',
    'visa_fee_local' => 'AED 3,000 – 5,500',
    'visa_fee_inr' => 'Includes medical tests and residency documentation',
    'weekly_budget_local' => 'AED 40,000 – 80,000',
    'weekly_budget_inr' => 'Approx. ₹9.4L – ₹18.8L depending on university and program',
    'earnings_potential_local' => 'AED 35 – 50 / hr',
    'earnings_potential_inr' => 'Retail, hospitality, and campus roles (Approx. ₹800 – ₹1.1k/hr)',
    'upcoming_intakes' => "Fall Intake (September) | Recommended Deadline: May 2026\nWinter Intake (January) | Recommended Deadline: October 2026",
    'demand_careers' => "Artificial Intelligence\nCyber Security\nSustainable Architecture\nFintech\nHealthcare Management",
    'travel_hours' => "Approx 3.5 - 5 hours (Direct Flights from India)"
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
    WHERE `slug` = 'uae'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "UAE DB updated successfully.\n";

// Update UAE Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'uae'");
$stmt->execute();
$uae = $stmt->fetch();

if ($uae) {
    $countryId = $uae['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $uaeUnis = [
        [
            'name' => 'Khalifa University',
            'qs_ranking' => '#3 Arab Region',
            'specialization' => 'Engineering, Science',
            'courses' => [
                ['name' => 'BSc in Computer Engineering', 'duration' => '4 Years', 'tuition_fee' => 'AED 60,000', 'intakes' => 'Fall'],
                ['name' => 'MSc in Artificial Intelligence', 'duration' => '2 Years', 'tuition_fee' => 'AED 80,000', 'intakes' => 'Fall']
            ]
        ],
        [
            'name' => 'United Arab Emirates University (UAEU)',
            'qs_ranking' => 'National Leader',
            'specialization' => 'Medicine, Life Sciences',
            'courses' => [
                ['name' => 'Doctor of Medicine (MD)', 'duration' => '6 Years', 'tuition_fee' => 'AED 75,000', 'intakes' => 'Fall'],
                ['name' => 'MSc in Life Sciences', 'duration' => '2 Years', 'tuition_fee' => 'AED 65,000', 'intakes' => 'Fall']
            ]
        ],
        [
            'name' => 'American University of Sharjah (AUS)',
            'qs_ranking' => 'Top Employer Rep',
            'specialization' => 'Architecture, Design',
            'courses' => [
                ['name' => 'Bachelor of Architecture', 'duration' => '5 Years', 'tuition_fee' => 'AED 95,000', 'intakes' => 'Fall, Spring'],
                ['name' => 'BSc in Design Management', 'duration' => '4 Years', 'tuition_fee' => 'AED 85,000', 'intakes' => 'Fall, Spring']
            ]
        ],
        [
            'name' => 'University of Wollongong in Dubai (UOWD)',
            'qs_ranking' => 'Premier Branch',
            'specialization' => 'Computer Science, IT',
            'courses' => [
                ['name' => 'Bachelor of Computer Science', 'duration' => '3 Years', 'tuition_fee' => 'AED 62,000', 'intakes' => 'Autumn, Spring'],
                ['name' => 'Master of IT Management', 'duration' => '1.5 Years', 'tuition_fee' => 'AED 85,000', 'intakes' => 'Autumn, Spring']
            ]
        ],
        [
            'name' => 'Heriot-Watt University Dubai',
            'qs_ranking' => 'Global Rep',
            'specialization' => 'Business, Fashion Design',
            'courses' => [
                ['name' => 'BA in Fashion Design', 'duration' => '3 Years', 'tuition_fee' => 'AED 58,000', 'intakes' => 'September'],
                ['name' => 'MBA', 'duration' => '1 Year', 'tuition_fee' => 'AED 90,000', 'intakes' => 'September, January']
            ]
        ]
    ];
    
    foreach ($uaeUnis as $uniData) {
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
    echo "UAE Universities and Courses updated successfully.\n";
} else {
    echo "UAE not found in DB.\n";
}
?>
