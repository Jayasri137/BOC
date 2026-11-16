<?php
require 'includes/config.php';
$stmt = $pdo->query('DESCRIBE community_posts');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
