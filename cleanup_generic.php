<?php
require 'includes/config.php';
$countries = $pdo->query("SELECT name FROM countries")->fetchAll(PDO::FETCH_ASSOC);
foreach($countries as $c) {
    $cname = addslashes($c['name']);
    $pdo->query("DELETE FROM universities WHERE name = 'University of $cname'");
}
echo "Cleaned up generic 'University of [Country]' entries.";
