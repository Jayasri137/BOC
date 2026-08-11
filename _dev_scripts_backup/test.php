<?php
require 'c:\xampp\htdocs\Bluestone Overseas\includes\config.php';
try {
    $stmt = $pdo->query("DESCRIBE courses");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
