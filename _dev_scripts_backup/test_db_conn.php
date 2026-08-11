<?php
try {
    $host = '194.59.164.60';
    $user = 'u287260207_bgoi_user';
    $pass = '4g@LMW2026';
    $dbname = 'u287260207_bgoi_bg';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "--- COLUMNS FOR bgoi_enquiries ---\n";
    $stmt = $pdo->query("DESCRIBE bgoi_enquiries");
    while($row = $stmt->fetch()) {
        echo "{$row['Field']} - {$row['Type']}\n";
    }

    echo "\n--- COLUMNS FOR contact_inquiries ---\n";
    $stmt = $pdo->query("DESCRIBE contact_inquiries");
    while($row = $stmt->fetch()) {
        echo "{$row['Field']} - {$row['Type']}\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
