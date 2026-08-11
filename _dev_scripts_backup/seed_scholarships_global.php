<?php
require_once 'includes/db.php';
header('Content-Type: text/plain');

echo "Starting Optimized Global Scholarship Seeding...\n";

try {
    $stmt = $pdo->query("SELECT u.id, u.name as uni_name, c.name as country_name FROM universities u JOIN countries c ON u.country_id = c.id");
    $universities = $stmt->fetchAll();

    $batchSize = 50;
    $values = [];
    $count = 0;

    foreach ($universities as $uni) {
        $uniId = $uni['id'];
        $uniName = $uni['uni_name'];
        $countryName = $uni['country_name'];

        // Simple check inside loop is fine, but for speed we could pre-fetch existing IDs
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM scholarships WHERE university_id = :uid");
        $checkStmt->execute(['uid' => $uniId]);
        if ($checkStmt->fetchColumn() > 0) continue;

        $numScholarships = rand(1, 2);
        for ($i = 0; $i < $numScholarships; $i++) {
            $scholName = ($i == 0) ? "$uniName International Excellence Scholarship" : "Global $countryName Student Grant";
            $amount = ($i == 0) ? "Up to 50% Tuition Fee Waiver" : "$2,000 - $5,000 One-time Grant";
            $eligibility = ($i == 0) ? "Outstanding academic records and strong statement of purpose." : "Applicable for international students from developing countries.";
            $deadline = ($i == 0) ? "Rolling Basis / 3 months before intake" : "December 31, 2026";

            if (strpos($countryName, 'United Kingdom') !== false) $amount = str_replace('$', '£', $amount);
            elseif (strpos($countryName, 'Australia') !== false) $amount = str_replace('$', '$', $amount) . " AUD";
            elseif (strpos($countryName, 'Europe') !== false || in_array($countryName, ['Germany', 'France', 'Ireland', 'Italy', 'Netherlands', 'Spain', 'Austria', 'Belgium', 'Luxembourg'])) $amount = str_replace('$', '€', $amount);

            $values[] = [
                'uid' => $uniId,
                'name' => $scholName,
                'amount' => $amount,
                'elig' => $eligibility,
                'deadline' => $deadline
            ];
            $count++;
        }
    }

    if (!empty($values)) {
        $pdo->beginTransaction();
        $insStmt = $pdo->prepare("INSERT INTO scholarships (university_id, name, amount, eligibility, deadline, is_active) VALUES (:uid, :name, :amount, :elig, :deadline, 1)");
        foreach ($values as $v) {
            $insStmt->execute($v);
        }
        $pdo->commit();
    }

    echo "Finished! Total scholarships seeded: $count\n";

} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
