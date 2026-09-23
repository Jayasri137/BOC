<?php require 'includes/db.php'; $stmt = $pdo->query('SELECT name, COUNT(*) as c FROM countries GROUP BY name HAVING c > 1'); $dups = $stmt->fetchAll(); print_r($dups);
