<?php
require_once __DIR__ . '/includes/db.php';
try {
    $pdo->exec("ALTER TABLE upcoming_batches ADD COLUMN duration VARCHAR(50) DEFAULT '' AFTER batch_mode");
    echo "Column 'duration' added.\n";
    
    // Update existing dummy data to have some duration
    $pdo->exec("UPDATE upcoming_batches SET duration = '1.5 Months'");
    echo "Dummy data updated with duration.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Column 'duration' already exists.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
