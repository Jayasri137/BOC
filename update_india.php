<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Bharat Innovates: A global engine of research & innovation with 54 universities in QS World Rankings 2026.',
    'roi_priority' => 'Digital Gateway: Study in India (SII) centralized portal for admissions and visa coordination. English-medium STEM instruction.',
    'roi_wage' => 'Scholarship Lead: ICCR scholarships and SII fee waivers up to 100% for merit international students.',
    'roi_qs' => 'Massive Scale: 1,300+ universities offering global quality at a fraction of Western costs. Top NIRF and QS ranked hubs.',
    'living_cost_local' => '$4,000 – $8,000 / year',
    'living_cost_inr' => 'Approx. ₹25k – ₹50k monthly living funds',
    'visa_fee_local' => '$100 – $250',
    'visa_fee_inr' => 'Visa & FRRO government fees (~₹8k – ₹20k)',
    'weekly_budget_local' => '$300 – $600',
    'weekly_budget_inr' => 'Estimated monthly student maintenance',
    'earnings_potential_local' => '$500 – $3,000',
    'earnings_potential_inr' => 'Average annual public university Tuition Fees',
    'upcoming_intakes' => "July/August | Main Intake\nJan/Feb | Limited Intake",
    'demand_careers' => "AI & Data Science\nComputer Science & Engineering\nMedicine (MBBS)\nCybersecurity\nLaw & Public Policy\nBusiness Administration (MBA)",
    'travel_hours' => "Domestic / International Hub (Major connectivity)"
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
    WHERE `slug` = 'india'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "India DB updated successfully.\n";

// Update India Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'india'");
$stmt->execute();
$india = $stmt->fetch();

if ($india) {
    $countryId = $india['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $indiaUnis = [
        [
            'name' => 'IIT Bombay',
            'qs_ranking' => 'Top Global',
            'specialization' => 'AI, Engineering, Technology',
            'courses' => [
                ['name' => 'MTech in Computer Science', 'duration' => '2 Years', 'tuition_fee' => '₹2,00,000', 'intakes' => 'July'],
                ['name' => 'MTech in AI & Data Science', 'duration' => '2 Years', 'tuition_fee' => '₹2,50,000', 'intakes' => 'July']
            ]
        ],
        [
            'name' => 'Indian Institute of Science (IISc), Bengaluru',
            'qs_ranking' => '#1 India Research',
            'specialization' => 'Science & Engineering Research',
            'courses' => [
                ['name' => 'PhD in Biological Sciences', 'duration' => '5 Years', 'tuition_fee' => '₹50,000 /yr', 'intakes' => 'August'],
                ['name' => 'MTech in Robotics', 'duration' => '2 Years', 'tuition_fee' => '₹1,00,000', 'intakes' => 'August']
            ]
        ],
        [
            'name' => 'University of Delhi (DU)',
            'qs_ranking' => 'Top National',
            'specialization' => 'Commerce, Arts & Science',
            'courses' => [
                ['name' => 'BCom (Hons)', 'duration' => '3 Years', 'tuition_fee' => '₹15,000 /yr', 'intakes' => 'July'],
                ['name' => 'MA in International Relations', 'duration' => '2 Years', 'tuition_fee' => '₹12,000 /yr', 'intakes' => 'July']
            ]
        ],
        [
            'name' => 'IIT Delhi',
            'qs_ranking' => 'Top Global',
            'specialization' => 'Applied AI & Robotics',
            'courses' => [
                ['name' => 'BTech in Computer Science', 'duration' => '4 Years', 'tuition_fee' => '₹2,00,000 /yr', 'intakes' => 'July']
            ]
        ],
        [
            'name' => 'Ashoka University',
            'qs_ranking' => 'Private Elite',
            'specialization' => 'Liberal Arts & Global Studies',
            'courses' => [
                ['name' => 'BA in Liberal Arts', 'duration' => '3 Years', 'tuition_fee' => '₹8,00,000 /yr', 'intakes' => 'August']
            ]
        ]
    ];
    
    foreach ($indiaUnis as $uniData) {
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
    echo "India Universities and Courses updated successfully.\n";
} else {
    echo "India not found in DB.\n";
}
?>
