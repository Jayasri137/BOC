<?php
require_once 'includes/db.php';
$stmt = $pdo->query("SELECT slug FROM countries");
while ($row = $stmt->fetch()) {
    echo $row['slug'] . "\n";
}
?>
