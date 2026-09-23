<?php
require 'includes/db.php';
$stmt = $pdo->prepare('SELECT id, name, image_url FROM universities WHERE country_id = 113');
$stmt->execute();
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
