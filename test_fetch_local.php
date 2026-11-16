<?php
require 'includes/config.php';
$stmt = $pdo->query("SELECT id, phone, student_name, status, primary_status, source FROM leads ORDER BY id DESC LIMIT 10");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
