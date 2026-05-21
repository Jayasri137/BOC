<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Indian Ocean Hub: Earn British, Australian, and French degrees at affordable costs in a safe tropical paradise.',
    'roi_priority' => 'Fees Security: Pay the majority of tuition fees only after receiving visa approval. No interview process for most Indian applicants.',
    'roi_wage' => 'Stay-back: 3-Year Young Professional Occupation Permit (YPOP) for eligible graduates with employment offers.',
    'roi_qs' => 'Tourism & Fintech Gateway: Rapid growth in Hospitality, Banking, and IT Services with strong Indian cultural influence.',
    'living_cost_local' => 'USD $4,800 – $9,600 / year',
    'living_cost_inr' => 'Approx. ₹4L – ₹8L for annual maintenance',
    'visa_fee_local' => 'Included',
    'visa_fee_inr' => 'Visa/Entry Permit usually processed by the institution',
    'weekly_budget_local' => 'USD $400 – $800',
    'weekly_budget_inr' => 'Estimated monthly student budget (~₹33k - ₹66k)',
    'earnings_potential_local' => 'USD $4,000 – $10,000',
    'earnings_potential_inr' => 'Average annual non-medical Tuition Fees',
    'upcoming_intakes' => "Jan/Feb | Primary Intake\nAug/Sept | Secondary Intake",
    'demand_careers' => "Hospitality & Tourism\nFintech & Banking\nSoftware Development\nInternational Business\nHealthcare",
    'travel_hours' => "Approx 6 - 8 hours (Direct flights from major Indian cities)"
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
    WHERE `slug` = 'mauritius'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Mauritius DB updated successfully.\n";

// Update Mauritius Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'mauritius'");
$stmt->execute();
$mauritius = $stmt->fetch();

if ($mauritius) {
    $countryId = $mauritius['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $mauritiusUnis = [
        [
            'name' => 'University of Mauritius',
            'qs_ranking' => 'National Flagship',
            'specialization' => 'Engineering, Science, Research',
            'courses' => [
                ['name' => 'BEng (Hons) Civil Engineering', 'duration' => '4 Years', 'tuition_fee' => 'USD $4,500', 'intakes' => 'August'],
                ['name' => 'MBA (General)', 'duration' => '2 Years', 'tuition_fee' => 'USD $5,000', 'intakes' => 'August, January']
            ]
        ],
        [
            'name' => 'Middlesex University Mauritius',
            'qs_ranking' => 'British Standard',
            'specialization' => 'IT, Psychology, Law',
            'courses' => [
                ['name' => 'BSc Computer Science (Cybersecurity)', 'duration' => '3 Years', 'tuition_fee' => 'USD $7,500', 'intakes' => 'September, January'],
                ['name' => 'MSc Applied Psychology', 'duration' => '1 Year', 'tuition_fee' => 'USD $8,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Curtin Mauritius',
            'qs_ranking' => 'Australian Hub',
            'specialization' => 'Design, Business, Tech',
            'courses' => [
                ['name' => 'Bachelor of Commerce', 'duration' => '3 Years', 'tuition_fee' => 'USD $8,500', 'intakes' => 'February, July'],
                ['name' => 'Bachelor of Design', 'duration' => '3 Years', 'tuition_fee' => 'USD $8,500', 'intakes' => 'February']
            ]
        ],
        [
            'name' => 'Vatel Mauritius',
            'qs_ranking' => 'French Hospitality Leader',
            'specialization' => 'Hotel Management, Tourism',
            'courses' => [
                ['name' => 'Bachelor in International Hotel Management', 'duration' => '3 Years', 'tuition_fee' => 'USD $6,000', 'intakes' => 'September, February'],
                ['name' => 'MBA in International Hotel Management', 'duration' => '1.5 Years', 'tuition_fee' => 'USD $9,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Amity Institute of Higher Education',
            'qs_ranking' => 'Indian Student Favorite',
            'specialization' => 'MBA, Global Business',
            'courses' => [
                ['name' => 'MBA (International)', 'duration' => '1 Year', 'tuition_fee' => 'USD $5,500', 'intakes' => 'September, January'],
                ['name' => 'BSc Information Technology', 'duration' => '3 Years', 'tuition_fee' => 'USD $4,000', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($mauritiusUnis as $uniData) {
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
    echo "Mauritius Universities and Courses updated successfully.\n";
} else {
    echo "Mauritius not found in DB.\n";
}
?>
