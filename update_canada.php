<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$data = [
    'roi_advantage' => 'Graduate Exemption: Zero Cap — Master’s and PhD applicants at public universities are fully exempt from the national student cap.',
    'roi_priority' => 'Stay-back (PGWP): Up to 3 Years for all Master’s graduates, regardless of program duration.',
    'roi_wage' => 'GIC Requirement: $22,895 CAD (Official 2026 living fund benchmark).',
    'roi_qs' => 'Visa Priority: 14-Day Processing for eligible PhD applicants applying from outside Canada.',
    'living_cost_local' => '$22,895 CAD',
    'living_cost_inr' => 'Approx. ₹15.3 Lakhs',
    'visa_fee_local' => '$150 CAD',
    'visa_fee_inr' => '+ $85 Biometrics',
    'weekly_budget_local' => '$15,000 – $40,000 CAD',
    'weekly_budget_inr' => 'STEM and MBA programs may range from $30k – $65k',
    'earnings_potential_local' => '$16 – $18/hr CAD',
    'earnings_potential_inr' => 'Students can work up to 24 hours/week during sessions',
    'upcoming_intakes' => "Fall Intake (September 2026) | Recommended Application Period: December 2025 – January 2026\nWinter Intake (January 2027) | Recommended Application Period: June 2026\nSummer Intake (May 2027) | Limited Professional/Language Courses",
    'demand_careers' => "Artificial Intelligence & Machine Learning\nData Science\nBusiness Management\nCyber Security\nHealthcare & Nursing\nCivil & Mechanical Engineering"
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
    `demand_careers` = :demand_careers
    WHERE `slug` = 'canada'";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute($data);
echo "Canada DB updated successfully.\n";
?>
