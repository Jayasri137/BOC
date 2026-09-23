<?php require 'includes/db.php'; $stmt = $pdo->query('SHOW COLUMNS FROM countries'); $columns = $stmt->fetchAll(); foreach($columns as $col) { echo $col['Field'] . ' - ' . $col['Type'] . PHP_EOL; }
