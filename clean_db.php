<?php
require_once 'includes/db.php';
try {
    $pdo->exec("DELETE FROM process_steps WHERE id > 5");
    $pdo->exec("DELETE FROM services WHERE id > 8");
    echo "Successfully cleaned up database duplicates.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
