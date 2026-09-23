<?php require 'includes/db.php'; $stmt = $pdo->query('SELECT id, name, image_path FROM team_members'); print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
