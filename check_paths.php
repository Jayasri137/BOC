<?php
require_once __DIR__ . '/includes/db.php';
$stmt = $pdo->query("SELECT id, SUBSTRING(image_path, 1, 60) as path FROM team_members");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
