<?php require 'includes/db.php'; $stmt = $pdo->query('SELECT university_id, COUNT(*) FROM courses GROUP BY university_id ORDER BY COUNT(*) DESC LIMIT 5'); print_r($stmt->fetchAll());
