<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'English-Taught Gateway: 100% English-speaking education system in the heart of the EU.',
    'roi_priority' => 'Schengen Mobility: Residence Permit enables visa-free travel across 29 countries. 12-Month stay-back for graduates.',
    'roi_wage' => 'Work Rights: Legal to work 20 hours/week from the first year. Single Permit pathway for professional employment transition.',
    'roi_qs' => 'Fast-Track Careers: Rising European hub for FinTech, iGaming, Blockchain, and Digital Marketing (HSBC, PwC, Betsson).',
    'living_cost_local' => '€14,000 / year',
    'living_cost_inr' => 'Approx. ₹12.6 Lakhs for annual maintenance',
    'visa_fee_local' => '€80 – €100',
    'visa_fee_inr' => 'Paid during VFS appointment (~₹7,200 - ₹9,000)',
    'weekly_budget_local' => '€800 – €1,200',
    'weekly_budget_inr' => 'Estimated monthly student budget (~₹72k - ₹1.1L)',
    'earnings_potential_local' => '€5,000 – €12,000',
    'earnings_potential_inr' => 'Average annual non-medical Tuition Fees (Public Universities)',
    'upcoming_intakes' => "Autumn (October) | Main Intake\nSpring (February) | Secondary Intake",
    'demand_careers' => "FinTech & Blockchain\niGaming & Digital Media\nCyber Security\nHotel & Tourism Management\nInternational Business",
    'travel_hours' => "Approx 10 - 17 hours (Depending on route and city)"
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
    WHERE `slug` = 'malta'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Malta DB updated successfully.\n";

// Update Malta Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'malta'");
$stmt->execute();
$malta = $stmt->fetch();

if ($malta) {
    $countryId = $malta['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $maltaUnis = [
        [
            'name' => 'University of Malta',
            'qs_ranking' => '#941 Globally',
            'specialization' => 'IT, AI, Medicine',
            'courses' => [
                ['name' => 'MSc in Artificial Intelligence', 'duration' => '1 Year', 'tuition_fee' => '€10,500', 'intakes' => 'October'],
                ['name' => 'BSc in Software Development', 'duration' => '3 Years', 'tuition_fee' => '€8,500', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'MCAST',
            'qs_ranking' => 'Vocational Leader',
            'specialization' => 'Professional Degrees, Applied Sciences',
            'courses' => [
                ['name' => 'BA in Business Enterprise', 'duration' => '3 Years', 'tuition_fee' => '€6,500', 'intakes' => 'October, February'],
                ['name' => 'BEng in Mechanical Engineering (Plant)', 'duration' => '4 Years', 'tuition_fee' => '€7,000', 'intakes' => 'October']
            ]
        ],
        [
            'name' => 'American University of Malta',
            'qs_ranking' => 'US-Style Curriculum',
            'specialization' => 'Business, Engineering',
            'courses' => [
                ['name' => 'MBA (International)', 'duration' => '1 Year', 'tuition_fee' => '€12,000', 'intakes' => 'September, January'],
                ['name' => 'BSc in Game Development', 'duration' => '4 Years', 'tuition_fee' => '€9,500', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Middlesex University Malta',
            'qs_ranking' => 'British Standard',
            'specialization' => 'British IT & Computer Science',
            'courses' => [
                ['name' => 'BSc in Computer Science', 'duration' => '3 Years', 'tuition_fee' => '€9,000', 'intakes' => 'September'],
                ['name' => 'MSc in Cyber Security and Pen Testing', 'duration' => '1 Year', 'tuition_fee' => '€11,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'GBSB Global Business School',
            'qs_ranking' => 'FinTech & Entrepreneurship',
            'specialization' => 'Digital Business',
            'courses' => [
                ['name' => 'Master in FinTech & Digital Banking', 'duration' => '1 Year', 'tuition_fee' => '€12,000', 'intakes' => 'October, January, April'],
                ['name' => 'MA in Digital Marketing', 'duration' => '1 Year', 'tuition_fee' => '€10,000', 'intakes' => 'October']
            ]
        ]
    ];
    
    foreach ($maltaUnis as $uniData) {
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
    echo "Malta Universities and Courses updated successfully.\n";
} else {
    echo "Malta not found in DB.\n";
}
?>
