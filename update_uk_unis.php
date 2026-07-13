<?php
require_once __DIR__ . '/includes/db.php';

$universities = [
    "University of Leicester",
    "Coventry University",
    "Newcastle University",
    "University of Sussex",
    "University of East Anglia",
    "University of Surrey",
    "University of Greenwich",
    "University of Hull - London",
    "University of Sunderland (Sunderland Campus)",
    "University of Brighton",
    "Buckinghamshire New University",
    "Keele University",
    "De Montfort University",
    "Aston University - London Campus",
    "University of Roehampton",
    "University of Sunderland (London Campus)",
    "Anglia Ruskin University - London Campus",
    "University of East London",
    "Edinburgh Napier University",
    "Anglia Ruskin University"
];

try {
    // 1. Get UK Country ID
    $stmt = $pdo->prepare("SELECT id FROM countries WHERE slug = 'uk'");
    $stmt->execute();
    $uk = $stmt->fetch();
    
    if (!$uk) {
        die("UK not found in countries table.");
    }
    
    $ukId = $uk['id'];
    
    // 2. Delete existing universities for UK
    $stmtDelete = $pdo->prepare("DELETE FROM universities WHERE country_id = :cid");
    $stmtDelete->execute(['cid' => $ukId]);
    echo "Deleted existing universities for UK (ID: $ukId).\n";
    
    // 3. Insert new universities
    $stmtInsert = $pdo->prepare("INSERT INTO universities (country_id, name, is_active) VALUES (:cid, :name, 1)");
    $count = 0;
    foreach ($universities as $uni) {
        $stmtInsert->execute(['cid' => $ukId, 'name' => $uni]);
        $count++;
    }
    
    echo "Successfully inserted $count new universities for UK.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
