<?php require 'includes/db.php'; $stmt = $pdo->query('SELECT qs_ranking, COUNT(*) FROM universities GROUP BY qs_ranking'); print_r($stmt->fetchAll());
