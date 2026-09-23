<?php require 'includes/db.php'; $stmt = $pdo->query('SELECT COUNT(*) FROM courses'); echo 'Total courses: ' . $stmt->fetchColumn();
