<?php
require_once 'includes/db.php';
try {
    $stmt = $pdo->query("SELECT id, title FROM process_steps ORDER BY id ASC");
    $steps = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Process Steps (" . count($steps) . "):\n";
    foreach ($steps as $s) echo $s['id'] . " - " . $s['title'] . "\n";

    echo "\n------------------\n\n";

    $stmt = $pdo->query("SELECT id, title FROM services ORDER BY id ASC");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Services (" . count($services) . "):\n";
    foreach ($services as $s) echo $s['id'] . " - " . $s['title'] . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
