<?php
require 'includes/config.php';
$stmt = $pdo->query('SELECT id, name, slug FROM countries ORDER BY name ASC');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
