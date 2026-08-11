<?php
require 'includes/db.php';
try {
    $stmt = $pdo->query("DESCRIBE services");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    
    $stmt = $pdo->query("SELECT * FROM services LIMIT 1");
    print_r($stmt->fetch(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
