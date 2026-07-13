<?php
require 'c:\xampp\htdocs\Bluestone Overseas\includes\config.php';
try {
    $stmt = $pdo->query("SELECT id, name FROM universities WHERE name LIKE '%Anglia%'");
    $unis = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($unis);

    foreach ($unis as $u) {
        $stmt2 = $pdo->prepare("SELECT * FROM courses WHERE university_id = ?");
        $stmt2->execute([$u['id']]);
        print_r($stmt2->fetchAll(PDO::FETCH_ASSOC));
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
