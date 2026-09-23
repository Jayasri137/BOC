<?php
require 'includes/config.php';
$stmt = $pdo->query('DESCRIBE courses');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . ' - ' . $row['Type'] . "\n";
}
?>
