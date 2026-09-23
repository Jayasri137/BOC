<?php require 'includes/db.php'; $stmt = $pdo->query('SELECT COUNT(*) FROM universities WHERE qs_ranking LIKE "%Top 500"'); echo $stmt->fetchColumn();
