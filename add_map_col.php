<?php
require_once 'includes/db.php';
try {
    $pdo->exec("ALTER TABLE branches ADD COLUMN map_iframe TEXT DEFAULT NULL");
    echo "Success";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Success (already exists)";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>
