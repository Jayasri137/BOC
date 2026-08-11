<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Tuition Model: €0 Tuition Fees at public universities (Potential savings of ₹30L – ₹50L).',
    'roi_priority' => 'Stay-back Opportunity: 18-Month Job-Seeker Visa after graduation.',
    'roi_wage' => 'Industrial Leadership: Global leader in Industry 4.0, Green Technology, and Automotive Engineering.',
    'roi_qs' => 'APS Priority: Mandatory academic verification system ensuring strong visa credibility and application integrity.',
    'living_cost_local' => '€11,904 / year',
    'living_cost_inr' => 'Mandatory living fund (Approx. ₹10.7 Lakhs)',
    'visa_fee_local' => '€75',
    'visa_fee_inr' => 'Payable in INR during visa appointment',
    'weekly_budget_local' => '€150 – €400 / sem',
    'weekly_budget_inr' => 'Covers administration and public transport access',
    'earnings_potential_local' => '€12.41 / hr',
    'earnings_potential_inr' => 'Potential earnings up to €1,100/month through part-time work',
    'upcoming_intakes' => "Winter Intake (October) | Recommended Deadline: March – May 2026\nSummer Intake (April) | Recommended Deadline: January 2027",
    'demand_careers' => "Mechanical Engineering\nAutomotive Engineering\nArtificial Intelligence\nData Science\nRenewable Energy",
    'travel_hours' => "Approx 9 - 15 hours (Depending on route and stopovers)"
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
    WHERE `slug` = 'germany'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Germany DB updated successfully.\n";

// Update Germany Universities and Courses
$stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = 'germany'");
$stmt->execute();
$germany = $stmt->fetch();

if ($germany) {
    $countryId = $germany['id'];
    $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
    
    $germanyUnis = [
        [
            'name' => 'Technical University of Munich (TUM)',
            'qs_ranking' => '#28 Globally',
            'specialization' => 'Technology, Engineering',
            'courses' => [
                ['name' => 'MSc in Mechanical Engineering', 'duration' => '2 Years', 'tuition_fee' => '€0 - €1,500/sem', 'intakes' => 'Winter, Summer'],
                ['name' => 'MSc in Data Engineering', 'duration' => '2 Years', 'tuition_fee' => '€0 - €1,500/sem', 'intakes' => 'Winter, Summer']
            ]
        ],
        [
            'name' => 'Ludwig Maximilian University of Munich (LMU)',
            'qs_ranking' => 'Top 50+',
            'specialization' => 'Life Sciences, Research',
            'courses' => [
                ['name' => 'MSc in Data Science', 'duration' => '2 Years', 'tuition_fee' => '€0', 'intakes' => 'Winter'],
                ['name' => 'MSc in Biochemistry', 'duration' => '2 Years', 'tuition_fee' => '€0', 'intakes' => 'Winter, Summer']
            ]
        ],
        [
            'name' => 'Heidelberg University',
            'qs_ranking' => 'Global Leader',
            'specialization' => 'Medicine, Biotechnology',
            'courses' => [
                ['name' => 'MSc in Molecular Biotechnology', 'duration' => '2 Years', 'tuition_fee' => '€1,500/sem (Intl)', 'intakes' => 'Winter'],
                ['name' => 'MSc in Health Economics', 'duration' => '2 Years', 'tuition_fee' => '€1,500/sem (Intl)', 'intakes' => 'Winter']
            ]
        ],
        [
            'name' => 'Free University of Berlin',
            'qs_ranking' => 'Intl Reputation',
            'specialization' => 'Social Sciences, Humanities',
            'courses' => [
                ['name' => 'MA in Sociology', 'duration' => '2 Years', 'tuition_fee' => '€0', 'intakes' => 'Winter'],
                ['name' => 'MSc in Business Informatics', 'duration' => '2 Years', 'tuition_fee' => '€0', 'intakes' => 'Winter']
            ]
        ],
        [
            'name' => 'RWTH Aachen University',
            'qs_ranking' => 'Eng Excellence',
            'specialization' => 'Mechanical & Production',
            'courses' => [
                ['name' => 'MSc in Automotive Engineering', 'duration' => '1.5 Years', 'tuition_fee' => '€0', 'intakes' => 'Winter'],
                ['name' => 'MSc in Production Systems Engineering', 'duration' => '2 Years', 'tuition_fee' => '€0', 'intakes' => 'Winter']
            ]
        ]
    ];
    
    foreach ($germanyUnis as $uniData) {
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
    echo "Germany Universities and Courses updated successfully.\n";
} else {
    echo "Germany not found in DB.\n";
}
?>
