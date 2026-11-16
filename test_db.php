<?php
require 'includes/config.php';
$stmt = $pdo->query('DESCRIBE leads');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
