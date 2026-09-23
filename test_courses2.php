<?php require 'includes/db.php'; $stmt = $pdo->query('SELECT university_id, COUNT(*) FROM courses GROUP BY university_id LIMIT 10'); print_r($stmt->fetchAll());
