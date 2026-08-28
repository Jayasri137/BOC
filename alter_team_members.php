<?php
require_once __DIR__ . '/includes/db.php';

try {
    $sql = "ALTER TABLE team_members MODIFY image_path LONGTEXT NOT NULL";
    $pdo->exec($sql);
    echo "Column modified successfully!\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
