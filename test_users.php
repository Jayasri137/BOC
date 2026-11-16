<?php
require 'includes/config.php';
$stmt = $pdo->query("SELECT * FROM community_users");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
echo "\n---\n";
$stmt = $pdo->query("SELECT * FROM community_posts");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
