<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Clinical Excellence: The gold standard for European medical education with 5.8–6 year MD programs fully aligned with NMC 2021 Gazette.',
    'roi_priority' => 'Compliance Mastery: India-backed medical pathway to Europe with structured internship and high FMGE/NExT success focus.',
    'roi_wage' => 'No IELTS: Direct admission based on PCB marks + NEET qualification. Direct PR pathway for long-term clinical training.',
    'roi_qs' => 'Safety Lead: Ranked among the Top 10 safest countries globally. High mobility for PG pathways in Germany, UK, and USA.',
    'living_cost_local' => '$3,200 – $4,800 / year',
    'living_cost_inr' => 'Approx. ₹2.9L – ₹4.4L for annual hostel & Indian mess',
    'visa_fee_local' => '₹12,000 – ₹15,000',
    'visa_fee_inr' => 'Government fees for Visa (D3) + Invitation issuance',
    'weekly_budget_local' => '$5,000',
    'weekly_budget_inr' => 'Mandatory proof of funds (~₹4.5L approx.)',
    'earnings_potential_local' => '$4,800 – $8,500',
    'earnings_potential_inr' => 'Average annual medical Tuition Fees',
    'upcoming_intakes' => "September | Main Intake\nFebruary | Limited Intake",
    'demand_careers' => "MBBS / MD Medicine\nDentistry\nNursing & Allied Health\nPharmacy\nBusiness Administration\nInformation Technology",
    'travel_hours' => "Approx 6 - 8 hours (Direct from Delhi/Mumbai)"
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
    WHERE `slug` = 'georgia'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Georgia DB updated successfully.\n";

// Update Georgia Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'georgia'");
$stmt->execute();
$georgia = $stmt->fetch();

if ($georgia) {
    $countryId = $georgia['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $georgiaUnis = [
        [
            'name' => 'Tbilisi State Medical University (TSMU)',
            'qs_ranking' => 'Top Medical Hub',
            'specialization' => 'Medicine',
            'courses' => [
                ['name' => 'MD Medicine (NMC Compliant)', 'duration' => '6 Years', 'tuition_fee' => '$8,000', 'intakes' => 'September'],
                ['name' => 'Dentistry', 'duration' => '5 Years', 'tuition_fee' => '$5,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Ivane Javakhishvili Tbilisi State University',
            'qs_ranking' => '#651 Global',
            'specialization' => 'Research, Science, Medicine',
            'courses' => [
                ['name' => 'MD Medicine', 'duration' => '6 Years', 'tuition_fee' => '$7,500', 'intakes' => 'September'],
                ['name' => 'BBA in International Business', 'duration' => '4 Years', 'tuition_fee' => '$4,000', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Georgian National University (SEU)',
            'qs_ranking' => 'Regional Leader',
            'specialization' => 'Medicine, Business',
            'courses' => [
                ['name' => 'MD Medicine', 'duration' => '6 Years', 'tuition_fee' => '$5,500', 'intakes' => 'September'],
                ['name' => 'BSc in Information Technology', 'duration' => '4 Years', 'tuition_fee' => '$3,500', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'David Tvildiani Medical University (DTMU)',
            'qs_ranking' => 'FMGE Focused',
            'specialization' => 'Medicine, USMLE Pathway',
            'courses' => [
                ['name' => 'MD Medicine (USMLE Integrated)', 'duration' => '6 Years', 'tuition_fee' => '$8,500', 'intakes' => 'September']
            ]
        ],
        [
            'name' => 'Ilia State University',
            'qs_ranking' => '#1000+ Global',
            'specialization' => 'Interdisciplinary Studies',
            'courses' => [
                ['name' => 'BEng in Computer Engineering', 'duration' => '4 Years', 'tuition_fee' => '$4,500', 'intakes' => 'September'],
                ['name' => 'BSc in Life Sciences', 'duration' => '4 Years', 'tuition_fee' => '$4,000', 'intakes' => 'September']
            ]
        ]
    ];
    
    foreach ($georgiaUnis as $uniData) {
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
    echo "Georgia Universities and Courses updated successfully.\n";
} else {
    echo "Georgia not found in DB.\n";
}
?>
