<?php
require 'includes/db.php';
try {
    $pdo->exec("DELETE FROM countries ORDER BY id DESC LIMIT 1");
    echo "Deleted extra country.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
