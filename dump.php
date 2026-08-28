<?php $pdo = new PDO('mysql:host=localhost;dbname=bluestone;charset=utf8mb4', 'root', ''); $stmt = $pdo->query('SHOW TABLES'); print_r($stmt->fetchAll(PDO::FETCH_COLUMN)); ?>
