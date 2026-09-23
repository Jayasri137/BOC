<?php
require 'includes/db.php';
$stmt = $pdo->query("SELECT id, name FROM universities WHERE name LIKE 'University of % 1' OR name LIKE 'University of % 2' OR name LIKE 'University of % 3' OR name LIKE 'University of % 4' OR name LIKE 'University of % 5'");
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($res);

$stmt2 = $pdo->query("SELECT name FROM universities WHERE qs_ranking = 'Top 500'");
$res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
print_r($res2);
