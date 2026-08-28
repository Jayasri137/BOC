<?php
require_once __DIR__ . '/includes/db.php';

try {
    $sql = "ALTER TABLE countries
        ADD COLUMN overview_image VARCHAR(255) DEFAULT NULL,
        ADD COLUMN cost_image VARCHAR(255) DEFAULT NULL,
        ADD COLUMN scholarships_image VARCHAR(255) DEFAULT NULL,
        ADD COLUMN intakes_image VARCHAR(255) DEFAULT NULL,
        ADD COLUMN eligibility_image VARCHAR(255) DEFAULT NULL,
        ADD COLUMN exams_image VARCHAR(255) DEFAULT NULL,
        ADD COLUMN visa_image VARCHAR(255) DEFAULT NULL,
        ADD COLUMN jobs_image VARCHAR(255) DEFAULT NULL,
        ADD COLUMN cities_image VARCHAR(255) DEFAULT NULL,
        ADD COLUMN admits_image VARCHAR(255) DEFAULT NULL,
        ADD COLUMN news_image VARCHAR(255) DEFAULT NULL
    ";

    $pdo->exec($sql);
    echo "Columns added successfully!\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Columns already exist.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
?>
